<?php
#########################################################################
#                        search.php                                     #
#                                                                       #
#            script de recherche et d'affichage des résultats           #
#                                                                       #
#            Dernière modification : 10/07/2006                         #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 * D'après http://mrbs.sourceforge.net/
 *
 * This file is part of GRR.
 *
 * GRR is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GRR is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GRR; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */
include "include/connect.inc.php";
include "include/config.inc.php";
include "include/functions.inc.php";
include "include/$dbsys.inc.php";
include "include/mrbs_sql.inc.php";
// Settings
require_once("./include/settings.inc.php");
//Chargement des valeurs de la table settingS
if (!loadSettings())
    die("Erreur chargement settings");

// Session related functions
require_once("./include/session.inc.php");
// Resume session
if ((!grr_resumeSession())and ($authentification_obli==1)) {
    header("Location: ./logout.php?auto=1");
    die();
};

// Paramètres langage
include "include/language.inc.php";

// On affiche le lien "format imprimable" en bas de la page
if (!isset($_GET['pview'])) $_GET['pview'] = 0; else $_GET['pview'] = 1;

if (($authentification_obli==0) and (!isset($_SESSION['login']))) {
    $type_session = "no_session";
} else {
    $type_session = "with_session";
}
$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];

// Il faut être "plus" que simple visiteur pour avoir accès à l'outil de recherche
// Ou bien il faut que $allow_search_for_not_connected=1
if (!((authGetUserLevel(getUserName(),-1) >= 1) or (isset($allow_search_for_not_connected) and ($allow_search_for_not_connected==1))))
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

$search_str = isset($_GET["search_str"]) ? $_GET["search_str"] : NULL;
$search_pos = isset($_GET["search_pos"]) ? $_GET["search_pos"] : NULL;

#If we dont know the right date then make it up

if(!isset($day) or !isset($month) or !isset($year))
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
}

if(empty($area))
    $area = get_default_area();

# Need all these different versions with different escaping.
# search_str must be left as the html-escaped version because this is
# used as the default value for the search box in the header.
if (!empty($search_str))
{
    $search_text = unslashes($search_str);
    $search_url = urlencode($search_text);
    $search_str = htmlspecialchars($search_text);
} else {
    $search_text = '';
    $search_url = '';
    $search_str = '';
}

print_header($day, $month, $year, $area, $type_session);

// Si la table j_user_area est vide, il faut modifier la requête
$test_grr_j_user_area = grr_sql_count(grr_sql_query("SELECT * from ".$_COOKIE["table_prefix"]."_j_user_area"));

// Si format imprimable ($_GET['pview'] = 1), on n'affiche pas cette partie
if ($_GET['pview'] != 1) {
    echo "<H3>" . get_vocab("advanced_search") . " </H3>";
    echo "<FORM METHOD=GET ACTION=\"search.php\">";
    echo get_vocab("search_for") . get_vocab("deux_points")."<INPUT TYPE=TEXT SIZE=25 NAME=\"search_str\" VALUE=\"".$search_str."\">&nbsp;&nbsp;";
    echo ucfirst( get_vocab("from")). get_vocab("deux_points");
    genDateSelector ("", $day, $month, $year,"");
    echo "&nbsp;&nbsp;<INPUT TYPE=SUBMIT VALUE=\"" . get_vocab("search_button") ."\">
    <br>(".get_vocab("dans les champs suivants : ")."".get_vocab("sum_by_creator")." - ".get_vocab("sum_by_descrip")." - ".get_vocab("match_descr").")
    <hr></form>";
}

if (!$search_str)
{
    echo "<H3>" . get_vocab("invalid_search") . "</H3>";
    include "include/trailer.inc.php";
    exit;
}

# now is used so that we only display entries newer than the current time
$search_date=mktime(0,0,0,$month,$day,$year);
echo "<H3>" . get_vocab("search_results") . " \"<font color=\"blue\">$search_str</font>\" (".get_vocab("from").get_vocab("deux_points").utf8_strftime($dformat, $search_date).")</H3>\n";

$now = mktime(0, 0, 0, $month, $day, $year);

# This is the main part of the query predicate, used in both queries:
$sql_pred = "( " . grr_sql_syntax_caseless_contains("E.create_by", $search_text)
        . " OR " . grr_sql_syntax_caseless_contains("E.name", $search_text)
        . " OR " . grr_sql_syntax_caseless_contains("E.description", $search_text)
        . ") ";

// cas d'un utilisateur qui n'est pas administrateur
if(authGetUserLevel(getUserName(),-1) < 5)
if ($test_grr_j_user_area == 0)
    $sql_pred .= " and a.access='a' and R.area_id =a.id  ";
else
    if ($type_session == "with_session") // Si l'utilisateur n'est pas administrateur, seuls les domaines auxquels il a accès sont pris en compte
        $sql_pred .= " and ((j.login='".$_SESSION['login']."' and j.id_area=a.id and a.access='r') or (a.access='a')) and R.area_id =a.id  ";
    else
        $sql_pred .= " and (a.access='a') and R.area_id =a.id  ";

$sql_pred .= " AND E.end_time > $now";

# The first time the search is called, we get the total
# number of matches.  This is passed along to subsequent
# searches so that we don't have to run it for each page.
$total = isset($_GET["total"]) ? $_GET["total"] : NULL;

if(!isset($total)) {
    $sql2 = "SELECT distinct E.id FROM ".$_COOKIE["table_prefix"]."_entry E, ".$_COOKIE["table_prefix"]."_room R";
    // Si l'utilisateur n'est pas administrateur, seuls les domaines auxquels il a accès sont pris en compte
    // On adapte la requête (voir $sql_pred plus haut)
    if(authGetUserLevel(getUserName(),-1) < 5)
    if ($test_grr_j_user_area == 0)
        $sql2 .= ", ".$_COOKIE["table_prefix"]."_area a ";
    else
        $sql2 .= ", ".$_COOKIE["table_prefix"]."_j_user_area j, ".$_COOKIE["table_prefix"]."_area a ";

    $sql2 .= " WHERE $sql_pred AND E.room_id = R.id";
    $result2 = grr_sql_query($sql2);
    $total = grr_sql_count($result2);
}

if($total <= 0)
{
    echo "<B>" . get_vocab("nothing_found") . "</B>\n";
    include "include/trailer.inc.php";
    exit;
} else {
    // Affichage d'un lien pour format imprimable
    if ( !isset($_GET['pview'])  or ($_GET['pview'] != 1)) {
        echo '<p><center><a href="' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . '&amp;pview=1" ';
        if ($pview_new_windows==1) echo ' target="_blank"';
        echo '>' . get_vocab("ppreview") . '</a></center><p>';
    }

}

if(!isset($search_pos) || ($search_pos <= 0))
    $search_pos = 0;
elseif($search_pos >= $total)
    $search_pos = $total - ($total % $search["count"]);

# Now we set up the "real" query using LIMIT to just get the stuff we want.
$sql = "SELECT distinct E.id, E.create_by, E.name, E.description, E.start_time, R.area_id, R.room_name
        FROM ".$_COOKIE["table_prefix"]."_entry E, ".$_COOKIE["table_prefix"]."_room R ";

// Si l'utilisateur n'est pas administrateur, seuls les domaines auxquels il a accès sont pris en compte
// On adapte la requête (voir $sql_pred plus haut)
    if(authGetUserLevel(getUserName(),-1) < 5)
        if ($test_grr_j_user_area == 0)
            $sql .= ", ".$_COOKIE["table_prefix"]."_area a ";
        else
            $sql .= ", ".$_COOKIE["table_prefix"]."_j_user_area j, ".$_COOKIE["table_prefix"]."_area a ";

        $sql .=" WHERE $sql_pred
        AND E.room_id = R.id
        ORDER BY E.start_time asc "
    . grr_sql_syntax_limit($search["count"], $search_pos);

# this is a flag to tell us not to display a "Next" link
$result = grr_sql_query($sql);
if (! $result) fatal_error(0, grr_sql_error());
$num_records = grr_sql_count($result);

$has_prev = $search_pos > 0;
$has_next = $search_pos < ($total-$search["count"]);

if($has_prev || $has_next)
{
    echo "<B>" . get_vocab("records") . ($search_pos+1) . get_vocab("through") . ($search_pos+$num_records) . get_vocab("of") . $total . "</B><BR>";

    # display a "Previous" button if necessary
    if($has_prev)
    {
        echo "<A HREF=\"search.php?search_str=$search_url&amp;search_pos=";
        echo max(0, $search_pos-$search["count"]);
        echo "&amp;total=$total&amp;year=$year&amp;month=$month&amp;day=$day\">";
    }

    echo "<B>" . get_vocab("previous") . "</B>";

    if($has_prev)
        echo "</A>";

    # print a separator for Next and Previous
    echo(" | ");

    # display a "Previous" button if necessary
    if($has_next)
    {
        echo "<A HREF=\"search.php?search_str=$search_url&amp;search_pos=";
        echo max(0, $search_pos+$search["count"]);
        echo "&amp;total=$total&amp;year=$year&amp;month=$month&amp;day=$day\">";
    }

    echo "<B>". get_vocab("next") ."</B>";

    if($has_next)
        echo "</A>";
}
?>
  <P>
  <TABLE BORDER=2 CELLSPACING=0 CELLPADDING=3>
   <TR>
    <TH><?php echo get_vocab("entry")       ?></TH>
    <TH><?php echo get_vocab("createdby")   ?></TH>
    <TH><?php echo get_vocab("namebooker").get_vocab("deux_points")  ?></TH>
    <TH><?php echo get_vocab("description") ?></TH>
    <TH><?php echo get_vocab("start_date").get_vocab("deux_points")  ?></TH>
   </TR>
<?php
for ($i = 0; ($row = grr_sql_row($result, $i)); $i++)
{
    echo "<TR>";
    echo "<TD> $row[6] - <A HREF=\"view_entry.php?id=$row[0]\">".get_vocab("view")."</A></TD>\n";
    echo "<TD>" . htmlspecialchars($row[1]) . "</TD>\n";
    echo "<TD>" . htmlspecialchars($row[2]) . "</TD>\n";
    echo "<TD>" . htmlspecialchars($row[3]) . "&nbsp;</TD>\n";
    // Récupération des données concernant l'affichage du planning du domaine
    get_planning_area_values($row[5]);
    if($enable_periods=='y')
        list(,$link_str) = period_date_string($row[4]);
    else
        $link_str = time_date_string($row[4],$dformat);

    // generate a link to the day.php
    $link = getdate($row[4]);
    echo "<TD><A HREF=\"day.php?day=$link[mday]&amp;month=$link[mon]&amp;year=$link[year]&amp;area=$row[5]\">"
    .  $link_str . "</A></TD>";
    echo "</TR>\n";
}

echo "</TABLE>\n";
include "include/trailer.inc.php";
?>