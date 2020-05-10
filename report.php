<?php
#########################################################################
#                            report.php                                 #
#                                                                       #
#            interface afficheant un rapport des réservations           #
#               Dernière modification : 10/07/2006                      #
#                                                                       #
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
    #Paramètres de connection
require_once("./include/settings.inc.php");
    #Chargement des valeurs de la table settings
if (!loadSettings())
    die("Erreur chargement settings");

    #Fonction relative à la session
require_once("./include/session.inc.php");
    #Si il n'y a pas de session crée, on déconnecte l'utilisateur.
if (!grr_resumeSession()) {
    header("Location: ./logout.php?auto=1");
    die();
};

// Paramètres langage
include "include/language.inc.php";

// On affiche le lien "format imprimable" en bas de la page
if (!isset($_GET['pview'])) $_GET['pview'] = 0; else $_GET['pview'] = 1;

    #Récupération des informations relatives au serveur.
$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
    #Renseigne les droits de l'utilisateur, si les droits sont insufisants, l'utilisateur est avertit.
if(authGetUserLevel(getUserName(),-1) < 2)
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

    #Champs de création du rapport.
$From_day = isset($_GET["From_day"]) ? $_GET["From_day"] : NULL;
$From_month = isset($_GET["From_month"]) ? $_GET["From_month"] : NULL;
$From_year = isset($_GET["From_year"]) ? $_GET["From_year"] : NULL;
$To_day = isset($_GET["To_day"]) ? $_GET["To_day"] : NULL;
$To_month = isset($_GET["To_month"]) ? $_GET["To_month"] : NULL;
$To_year = isset($_GET["To_year"]) ? $_GET["To_year"] : NULL;
$areamatch = isset($_GET["areamatch"]) ? $_GET["areamatch"] : NULL;
$roommatch = isset($_GET["roommatch"]) ? $_GET["roommatch"] : NULL;
$typematch = isset($_GET["typematch"]) ? $_GET["typematch"] : NULL;
$namematch = isset($_GET["namematch"]) ? $_GET["namematch"] : NULL;
$descrmatch = isset($_GET["descrmatch"]) ? $_GET["descrmatch"] : NULL;
$loginmatch = isset($_GET["loginmatch"]) ? $_GET["loginmatch"] : NULL;
$summarize = isset($_GET["summarize"]) ? $_GET["summarize"] : NULL;
$sumby = isset($_GET["sumby"]) ? $_GET["sumby"] : NULL;
$sortby = isset($_GET["sortby"]) ? $_GET["sortby"] : "a";

// Si la table j_user_area est vide, il faut modifier la requête
$test_grr_j_user_area = grr_sql_count(grr_sql_query("SELECT * from ".$_COOKIE["table_prefix"]."_j_user_area"));


# Report on one entry. See below for columns in $row[].
#$last_area_room garde les valeur de "area" et "room".
function reporton(&$row, &$last_area_room, $dformat)
{
    global $vocab, $enable_periods;
    #Affiche "area" et "room" mais seulement quand ceux-ci changent.
    $area_room = htmlspecialchars($row[8]) . " - " . htmlspecialchars($row[9]);
    $areadescrip = " - " . htmlspecialchars($row[10]);
    if ($area_room != $last_area_room)
    {
        echo "<hr><h2>".get_vocab("room")." $area_room $areadescrip</h2>\n";
        $last_area_room = $area_room;
    }
    echo "<hr><table width=\"100%\">\n";

    # Brief Description (title), linked to view_entry:
    echo "<tr><td class=\"BL\"><a href=\"view_entry.php?id=$row[0]\">"
        . htmlspecialchars($row[3]) . "</a></td>\n";

    # From date-time and duration:
    echo "<td class=\"BR\" align=right>";
    if ($enable_periods=='y') {
        echo describe_period_span($row[1], $row[2]);
        echo "</td></tr>\n";;

    } else {
        echo describe_span($row[1], $row[2],$dformat);
        echo "<br>".   date("d\/m\/Y\ \-\ H\:i",$row[1])." ==> ".date("d\/m\/Y\ \-\ H\:i",$row[2])."</td></tr>\n";
    }
    #Description
    echo "<tr><td class=\"BL\" colspan=2><b>".get_vocab('description')."</b> " .
        nl2br(htmlspecialchars($row[4])) . "</td></tr>\n";
    #Type de réservation
    $et = grr_sql_query1("select type_name from ".$_COOKIE["table_prefix"]."_type_area where type_letter='".$row[5]."'");
    if ($et == -1) $et = "?".$row[5]."?";
    echo "<tr><td class=\"BL\" colspan=2><b>".get_vocab('type')."</b> $et</td></tr>\n";
    #Affichage de "crée par" et de la date de la dernière mise à jour
    $sql_creator = "SELECT prenom, nom FROM ".$_COOKIE["table_prefix"]."_utilisateurs WHERE login = '".$row[6]."'";
    $res_creator = grr_sql_query($sql_creator);
    if ($res_creator) $row_user = grr_sql_row($res_creator, 0);

    echo "<tr><td class=\"BL\" colspan=2><small><b>".get_vocab('createdby')."</b> " .
        htmlspecialchars($row_user[0]) ." ". htmlspecialchars($row_user[1]).", <b>".get_vocab('lastupdate')."</b> " .
        date_time_string($row[7],$dformat) . "</small></td></tr>\n";
    echo "</table>\n";
}
#Statistique d'une entrée. Cela va créer une table de toutes les areas et rooms uniques.
#Cela va devenir la colonne et la ligne d'entête de la table de staistique.'
# Collect summary statistics on one entry. See below for columns in $row[].
# $sumby selects grouping on brief description (d) or created by (c).
# This also builds hash tables of all unique names and rooms. When sorted,
# these will become the column and row headers of the summary table.
function accumulate(&$row, &$count, &$hours, $report_start, $report_end,
    &$room_hash, &$name_hash)
{
    global $sumby;
    #Description "Créer par":
    $name = htmlspecialchars($row[($sumby == "d" ? 3 : 6)]);
    #Area et room:
    $room = htmlspecialchars($row[8]) . "<br>" . htmlspecialchars($row[9]) . "<br>" .htmlspecialchars($row[10]);
    #Ajoute le nombre de réservations pour cette "room" et nom.
    @$count[$room][$name]++;
    #Ajoute le nombre d'heure ou la ressource est utilisée.
    @$hours[$room][$name] += (min((int)$row[2], $report_end)
        - max((int)$row[1], $report_start)) / 3600.0;
    $room_hash[$room] = 1;
    $name_hash[$name] = 1;
}

function accumulate_periods(&$row, &$count, &$hours, $report_start, $report_end,
    &$room_hash, &$name_hash)
{
    global $sumby;
        global $periods_name;
        $max_periods = count($periods_name);

    # Use brief description or created by as the name:
    $name = htmlspecialchars($row[($sumby == "d" ? 3 : 6)]);
    # Area and room separated by break:
    $room = htmlspecialchars($row[9]) . "<br>" . htmlspecialchars($row[10]);
    # Accumulate the number of bookings for this room and name:
    @$count[$room][$name]++;
    # Accumulate hours used, clipped to report range dates:
        $dur = (min((int)$row[2], $report_end) - max((int)$row[1], $report_start))/60;
    if ($dur < (24*60))
        @$hours[$room][$name] += $dur;
    else
        @$hours[$room][$name] += ($dur % $max_periods) + floor( $dur/(24*60) ) * $max_periods;
    $room_hash[$room] = 1;
    $name_hash[$name] = 1;
}




    #Table contenant un compteur (int) et une heure (float):
function cell($count, $hours)
{
    echo "<td class=\"BR\" align=right>($count) "
    . sprintf("%.2f", $hours) . "</td>\n";
}

# Output the summary table (a "cross-tab report"). $count and $hours are
# 2-dimensional sparse arrays indexed by [area/room][name].
# $room_hash & $name_hash are arrays with indexes naming unique rooms and names.
function do_summary(&$count, &$hours, &$room_hash, &$name_hash,$enable_periods)
{
    global $vocab;

    # Make a sorted array of area/rooms, and of names, to use for column
    # and row indexes. Use the rooms and names hashes built by accumulate().
    # At PHP4 we could use array_keys().
    reset($room_hash);
    while (list($room_key) = each($room_hash)) $rooms[] = $room_key;
    ksort($rooms);
    reset($name_hash);
    while (list($name_key) = each($name_hash)) $names[] = $name_key;
    ksort($names);
    $n_rooms = sizeof($rooms);
    $n_names = sizeof($names);

    if ($enable_periods == 'y')
        echo "<hr><h1>".get_vocab("summary_header_per")."</h1><table border=2 cellspacing=4>\n";
    else
        echo "<hr><h1>".get_vocab("summary_header")."</h1><table border=2 cellspacing=4>\n";
    echo "<tr><td>&nbsp;</td>\n";
    for ($c = 0; $c < $n_rooms; $c++)
    {
        echo "<td class=\"BL\" align=left><b>$rooms[$c]</b></td>\n";
        $col_count_total[$c] = 0;
        $col_hours_total[$c] = 0.0;
    }
    echo "<td class=\"BR\" align=right><br><b>".get_vocab("total")."</b></td></tr>\n";
    $grand_count_total = 0;
    $grand_hours_total = 0;

    for ($r = 0; $r < $n_names; $r++)
    {
        $row_count_total = 0;
        $row_hours_total = 0.0;
        $name = $names[$r];

        echo "<tr><td class=\"BR\" align=right><b>$name</b></td>\n";
        for ($c = 0; $c < $n_rooms; $c++)
        {
            $room = $rooms[$c];
            if (isset($count[$room][$name]))
            {
                $count_val = $count[$room][$name];
                $hours_val = $hours[$room][$name];
                cell($count_val, $hours_val);
                $row_count_total += $count_val;
                $row_hours_total += $hours_val;
                $col_count_total[$c] += $count_val;
                $col_hours_total[$c] += $hours_val;
            } else {
                echo "<td>&nbsp;</td>\n";
            }
        }
        cell($row_count_total, $row_hours_total);
        echo "</tr>\n";
        $grand_count_total += $row_count_total;
        $grand_hours_total += $row_hours_total;
    }
    echo "<tr><td class=\"BR\" align=right><b>".get_vocab("total")."</b></td>\n";
    for ($c = 0; $c < $n_rooms; $c++)
        cell($col_count_total[$c], $col_hours_total[$c]);
    cell($grand_count_total, $grand_hours_total);
    echo "</tr></table>\n";
}
   #Si nous ne savons pas la date, nous devons la créer
if(!isset($day) or !isset($month) or !isset($year))
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
}
if(empty($area))
    $area = get_default_area();
    #Affiche les informations dans l'header
print_header($day, $month, $year, $area);
if (isset($areamatch))
{
    #Applique les paramètres par defaut.
    #S'assurer que ces paramètres ne sont pas cités.
    $areamatch = unslashes($areamatch);
    $roommatch = unslashes($roommatch);
    $typematch = unslashes($typematch);
    $namematch = unslashes($namematch);
    $descrmatch = unslashes($descrmatch);
    $loginmatch = unslashes($loginmatch);

    #Mettre les valeurs par défaut quand le formulaire est réutilisé.
    $areamatch_default = htmlspecialchars($areamatch);
    $roommatch_default = htmlspecialchars($roommatch);
    $typematch_default = htmlspecialchars($typematch);
    $namematch_default = htmlspecialchars($namematch);
    $descrmatch_default = htmlspecialchars($descrmatch);
    $loginmatch_default = htmlspecialchars($loginmatch);
}
else
{
    #Nouveau rapport (défaut).
    $areamatch_default = "";
    $roommatch_default = "";
    $typematch_default = "";
    $namematch_default = "";
    $descrmatch_default = "";
    $loginmatch_default = "";
    $From_day = $day;
    $From_month = $month;
    $From_year = $year;
    $To_time = mktime(0, 0, 0, $month, $day + $default_report_days, $year);
    $To_day   = date("d", $To_time);
    $To_month = date("m", $To_time);
    $To_year  = date("Y", $To_time);
}
    #$summarize: 1=Rapport seulement, 2=Résumé seulement, 3=Les deux.
if (empty($summarize)) $summarize = 1;
    #$sumby: d=Par une description brève, c=Par créateur.
if (empty($sumby)) $sumby = "d";
    #Le formulaire.


if ($summarize != 4) {
?>
<div align=center><h1><?php echo get_vocab("report_on");?></h1>
<form method='GET' action=report.php>
<?php
// Si format imprimable ($_GET['pview'] = 1), on n'affiche pas cette partie
if ($_GET['pview'] != 1) {
?>
<table>
<tr><td class="CR"><?php echo get_vocab("report_start");?></td>
    <td class="CL"> <font size="-1">
    <?php genDateSelector("From_", $From_day, $From_month, $From_year,""); ?>
    </font></td></tr>
<tr><td class="CR"><?php echo get_vocab("report_end");?></td>
    <td class="CL"> <font size="-1">
    <?php genDateSelector("To_", $To_day, $To_month, $To_year,""); ?>
    </font></td></tr>
<tr><td class="CR"><?php echo get_vocab("match_area").get_vocab("deux_points");?></td>
    <td class="CL"><input type=text name=areamatch size=18
    value="<?php echo $areamatch_default; ?>">
    </td></tr>
<tr><td class="CR"><?php echo get_vocab("room").get_vocab("deux_points");?></td>
    <td class="CL"><input type=text name=roommatch size=18
    value="<?php echo $roommatch_default; ?>">
    </td></tr>
<tr><td class="CR"><?php echo get_vocab("type").get_vocab("deux_points");?></td>
    <td class="CL"><input type=text name=typematch size=18
    value="<?php echo $typematch_default; ?>">
    </td></tr>

<tr><td class="CR"><?php echo get_vocab("namebooker").get_vocab("deux_points");?></td>
    <td class="CL"><input type=text name=namematch size=18
    value="<?php echo $namematch_default; ?>">
    </td></tr>
<tr><td class="CR"><?php echo get_vocab("match_descr");?>:</td>
    <td class="CL"><input type=text name=descrmatch size=18
    value="<?php echo $descrmatch_default; ?>">
    </td></tr>
<tr><td class="CR"><?php echo get_vocab("match_login").get_vocab("deux_points");?></td>
    <td class="CL"><input type=text name=loginmatch size=18
    value="<?php echo $loginmatch_default; ?>">
    </td></tr>
<tr><td class="CR"><?php echo get_vocab("include");?></td>
    <td class="CL">
      <input type=radio name=summarize value=1<?php if ($summarize==1) echo " checked";
        echo ">" . get_vocab("report_only");?>
      <input type=radio name=summarize value=2<?php if ($summarize==2) echo " checked";
        echo ">" . get_vocab("summary_only");?>
      <input type=radio name=summarize value=3<?php if ($summarize==3) echo " checked";
        echo ">" . get_vocab("report_and_summary");?>
      <input type=radio name=summarize value=4<?php if ($summarize==4) echo " checked";
        echo ">" . get_vocab("csv");?>
    </td></tr>
<tr><td class="CR"><?php echo get_vocab("summarize_by");?></td>
    <td class="CL">
      <input type=radio name=sumby value=d<?php if ($sumby=="d") echo " checked";
        echo ">" . get_vocab("sum_by_descrip");?>
      <input type=radio name=sumby value=c<?php if ($sumby=="c") echo " checked";
        echo ">" . get_vocab("sum_by_creator");?>
    </td></tr>
<tr><td colspan=2 align=center><input type=submit value="<?php echo get_vocab('submitquery') ?>">
</td></tr>
<?php
}
?>
</table>
</div>
</form>



<?php
}

    # Résultats:
if (isset($areamatch))
{
    echo "<hr>";
    // Affichage d'un lien pour format imprimable
    if ( !isset($_GET['pview'])  or ($_GET['pview'] != 1)) {
        echo '<p><center><a href="' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . '&amp;pview=1" ';
        if ($pview_new_windows==1) echo ' target="_blank"';
        echo '>' . get_vocab("ppreview") . '</a></center><p>';
    }


  // Si format imprimable ($_GET['pview'] = 1), on n'affiche pas cette partie
  if (($_GET['pview'] != 1) and (($summarize == 1) or ($summarize == 3))) {

    echo "<center><table cellpadding=\"3\" cellspacing=\"0\" border=\"1\">";
    echo "<tr><td colspan=\"6\" align=\"center\">".get_vocab("trier_par").get_vocab("deux_points")."</td></tr><tr>";
    echo "<td>";
    if ($sortby != "a") {
        echo "<a href='report.php?From_day=$From_day&amp;From_month=$From_month&amp;From_year=$From_year&amp;To_day=$To_day&amp;To_month=$To_month&amp;To_year=$To_year&amp;areamatch=$areamatch&amp;roommatch=$roommatch&amp;typematch=$typematch&amp;namematch=$namematch&amp;descrmatch=$descrmatch&amp;loginmatch=$loginmatch&amp;summarize=$summarize&amp;sumby=$sumby&amp;sortby=a";
        if ($_GET['pview'] != 0) echo "&amp;pview=1";
        echo "'>".get_vocab("match_area")."</a>";
    } else
        echo "<b>>> ".get_vocab("match_area")." <<</b>";
    echo "</td>";

    echo "<td>";
    if ($sortby != "r") {
        echo "<a href='report.php?From_day=$From_day&amp;From_month=$From_month&amp;From_year=$From_year&amp;To_day=$To_day&amp;To_month=$To_month&amp;To_year=$To_year&amp;areamatch=$areamatch&amp;roommatch=$roommatch&amp;typematch=$typematch&amp;namematch=$namematch&amp;descrmatch=$descrmatch&amp;loginmatch=$loginmatch&amp;summarize=$summarize&amp;sumby=$sumby&amp;sortby=r";
        if ($_GET['pview'] != 0) echo "&amp;pview=1";
        echo "'>".get_vocab("room")."</a>";
    } else
        echo "<b>>> ".get_vocab("room")." <<</b>";
    echo "</td>";

    echo "<td>";
    if ($sortby != "d") {
        echo "<a href='report.php?From_day=$From_day&amp;From_month=$From_month&amp;From_year=$From_year&amp;To_day=$To_day&amp;To_month=$To_month&amp;To_year=$To_year&amp;areamatch=$areamatch&amp;roommatch=$roommatch&amp;typematch=$typematch&amp;namematch=$namematch&amp;descrmatch=$descrmatch&amp;loginmatch=$loginmatch&amp;summarize=$summarize&amp;sumby=$sumby&amp;sortby=d";
        if ($_GET['pview'] != 0) echo "&amp;pview=1";
        echo "'>".get_vocab("start_date")."</a>";
    } else
        echo "<b>>> ".get_vocab("start_date")." <<</b>";
    echo "</td>";

    echo "<td>";
    if ($sortby != "t") {
        echo "<a href='report.php?From_day=$From_day&amp;From_month=$From_month&amp;From_year=$From_year&amp;To_day=$To_day&amp;To_month=$To_month&amp;To_year=$To_year&amp;areamatch=$areamatch&amp;roommatch=$roommatch&amp;typematch=$typematch&amp;namematch=$namematch&amp;descrmatch=$descrmatch&amp;loginmatch=$loginmatch&amp;summarize=$summarize&amp;sumby=$sumby&amp;sortby=t";
        if ($_GET['pview'] != 0) echo "&amp;pview=1";
        echo "'>".get_vocab("type")."</a>";
    } else
        echo "<b>>> ".get_vocab("type")." <<</b>";
    echo "</td>";

    echo "<td>";
    if ($sortby != "c") {
        echo "<a href='report.php?From_day=$From_day&amp;From_month=$From_month&amp;From_year=$From_year&amp;To_day=$To_day&amp;To_month=$To_month&amp;To_year=$To_year&amp;areamatch=$areamatch&amp;roommatch=$roommatch&amp;typematch=$typematch&amp;namematch=$namematch&amp;descrmatch=$descrmatch&amp;loginmatch=$loginmatch&amp;summarize=$summarize&amp;sumby=$sumby&amp;sortby=c";
        if ($_GET['pview'] != 0) echo "&amp;pview=1";
        echo "'>".get_vocab("match_login")."</a>";
    } else
        echo "<b>>> ".get_vocab("match_login")." <<</b>";
    echo "</td>";

    echo "<td>";
    if ($sortby != "b") {
        echo "<a href='report.php?From_day=$From_day&amp;From_month=$From_month&amp;From_year=$From_year&amp;To_day=$To_day&amp;To_month=$To_month&amp;To_year=$To_year&amp;areamatch=$areamatch&amp;roommatch=$roommatch&amp;typematch=$typematch&amp;namematch=$namematch&amp;descrmatch=$descrmatch&amp;loginmatch=$loginmatch&amp;summarize=$summarize&amp;sumby=$sumby&amp;sortby=b";
        if ($_GET['pview'] != 0) echo "&amp;pview=1";
        echo "'>".get_vocab("namebooker")."</a>";
    } else
        echo "<b>>> ".get_vocab("namebooker")." <<</b>";
    echo "</td>";

    echo "</tr></table></center>";
  }

    #S'assurer que ces paramètres ne sont pas cités.
    $areamatch = unslashes($areamatch);
    $roommatch = unslashes($roommatch);
    $typematch = unslashes($typematch);
    $namematch = unslashes($namematch);
    $descrmatch = unslashes($descrmatch);
    #Les heures de début et de fin sont aussi utilisés pour mettre l'heure dans le rapport.
    $report_start = mktime(0, 0, 0, $From_month, $From_day, $From_year);
    $report_end = mktime(0, 0, 0, $To_month, $To_day+1, $To_year);
#   La requête SQL va contenir les colonnes suivantes:
# Col Index  Description:
#   1  [0]   Entry ID, Non affiché
#   2  [1]   Date de début (Unix)
#   3  [2]   Date de fin (Unix)
#   4  [3]   Descrition brêve,(HTML)
#   5  [4]   Descrition,(HTML)
#   6  [5]   Type
#   7  [6]   Créer par (nom ou IP), (HTML)
#   8  [7]   Timestamp (création), (Unix)
#   9  [8]   Area (HTML)
#  10  [9]   Room (HTML)
#  11  [10]  Room description
    $sql = "SELECT distinct e.id, e.start_time, e.end_time, e.name, e.description, "
        . "e.type, e.create_by, "
        .  grr_sql_syntax_timestamp_to_unix("e.timestamp")
        . ", a.area_name, r.room_name, r.description, a.id"
        . " FROM ".$_COOKIE["table_prefix"]."_entry e, ".$_COOKIE["table_prefix"]."_area a, ".$_COOKIE["table_prefix"]."_room r, ".$_COOKIE["table_prefix"]."_type_area t";

    // Si l'utilisateur n'est pas administrateur, seuls les domaines auxquels il a accès sont pris en compte
    if(authGetUserLevel(getUserName(),-1) < 5)
        if ($test_grr_j_user_area != 0)
           $sql .= ", ".$_COOKIE["table_prefix"]."_j_user_area j ";
        $sql .= " WHERE e.room_id = r.id AND r.area_id = a.id";
    // Si l'utilisateur n'est pas administrateur, seuls les domaines auxquels il a accès sont pris en compte
    if(authGetUserLevel(getUserName(),-1) < 5)
        if ($test_grr_j_user_area == 0)
            $sql .= " and a.access='a' ";
        else
            $sql .= " and ((j.login='".$_SESSION['login']."' and j.id_area=a.id and a.access='r') or (a.access='a')) ";

        $sql .= " AND e.start_time < $report_end AND e.end_time > $report_start";
    if (!empty($areamatch))
        $sql .= " AND" .  grr_sql_syntax_caseless_contains("a.area_name", $areamatch);
    if (!empty($roommatch))
        $sql .= " AND" .  grr_sql_syntax_caseless_contains("r.room_name", $roommatch);
    if ((!empty($typematch)) or ($sortby == 't'))
        $sql .= " AND" .  grr_sql_syntax_caseless_contains("t.type_name", $typematch)." AND t.type_letter = e.type ";
    else
        $sql .= " AND t.type_letter = e.type ";
    if (!empty($namematch))
        $sql .= " AND" .  grr_sql_syntax_caseless_contains("e.name", $namematch);
    if (!empty($descrmatch))
        $sql .= " AND" .  grr_sql_syntax_caseless_contains("e.description", $descrmatch);
    if (!empty($loginmatch))
        $sql .= " AND" .  grr_sql_syntax_caseless_contains("e.create_by", $loginmatch);
    if( $sortby == "a" )
        #Trié par: Area, room, debut, date/heure.
        $sql .= " ORDER BY 9,r.order_display,10,t.type_name,2";
    else if( $sortby == "r" )
        #Trié par: room, area, debut, date/heure.
        $sql .= " ORDER BY r.order_display,10,9,t.type_name,2";
    else if( $sortby == "d" )
        # Order by Start date/time, Area, Room
        $sql .= " ORDER BY 2,9,r.order_display,10,t.type_name";
    else if( $sortby == "t" )
        #Trié par: type, Area, room, debut, date/heure.
        $sql .= " ORDER BY t.type_name,9,r.order_display,10,2";
    else if( $sortby == "c" )
        #Trié par: createur, Area, room, debut, date/heure.
        $sql .= " ORDER BY e.create_by,9,r.order_display,10,2";
    else if( $sortby == "b" )
        #Trié par: createur, Area, room, debut, date/heure.
        $sql .= " ORDER BY e.name,9,r.order_display,10,2";
    $res = grr_sql_query($sql);
    if (! $res) fatal_error(0, grr_sql_error());
    $nmatch = grr_sql_count($res);
    if ($nmatch == 0)
    {
        echo "<P><B>" . get_vocab("nothing_found") . "</B>\n";
        grr_sql_free($res);
    }
    else
    {
        $last_area_room = "";
        echo "<P><B>" . $nmatch . " "
        . ($nmatch == 1 ? get_vocab("entry_found") : get_vocab("entries_found"))
        .  "</B>\n";

        for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
            // Récupération des données concernant l'affichage du planning du domaine
            get_planning_area_values($row[11]);

            if ($summarize & 1)
                reporton($row, $last_area_room, $dformat);
            if ($summarize & 2)
                if ($enable_periods=='y') {
                    accumulate_periods($row, $count, $hours, $report_start, $report_end,
                    $room_hash, $name_hash);
                    $do_sum1 = 'y';
                } else {
                    accumulate($row, $count2, $hours2, $report_start, $report_end,
                    $room_hash2, $name_hash2);
                    $do_sum2 = 'y';
                }
        }
        if ($summarize & 2) {
            if (isset($do_sum1)) do_summary($count, $hours, $room_hash, $name_hash,'y');
            if (isset($do_sum2)) do_summary($count2, $hours2, $room_hash2, $name_hash2,'n');
        }
        if ($summarize & 4)
        {
    echo "<center><H3>".get_vocab("indexcsv")."</H3></center>";
    echo'<div align=center><br><br><a href="csv.php?report_end='.$report_end.'&amp;report_start='.$report_start.'&amp;areamatch='.urlencode($areamatch).'&amp;roommatch='.urlencode($roommatch).'&amp;typematch='.urlencode($typematch).'&amp;namematch='.urlencode($namematch).'&amp;descrmatch='.urlencode($descrmatch).'&amp;loginmatch='.urlencode($loginmatch).'&amp;sumby='.$sumby.'&amp;From_day='.$From_day.'&amp;From_month='.$From_month.'&amp;From_year='.$From_year.'&amp;To_day='.$To_day.'&amp;To_month='.$To_month.'&amp;To_year='.$To_year.'">
        '.get_vocab("dlrapportcsv").'</A><br>';
    echo'<div align=center><br><br><a href="csv2.php?report_end='.$report_end.'&amp;report_start='.$report_start.'&amp;areamatch='.urlencode($areamatch).'&amp;roommatch='.urlencode($roommatch).'&amp;typematch='.urlencode($typematch).'&amp;namematch='.urlencode($namematch).'&amp;descrmatch='.urlencode($descrmatch).'&amp;loginmatch='.urlencode($loginmatch).'&amp;sumby='.$sumby.'&amp;From_day='.$From_day.'&amp;From_month='.$From_month.'&amp;From_year='.$From_year.'&amp;To_day='.$To_day.'&amp;To_month='.$To_month.'&amp;To_year='.$To_year.'">
        '.get_vocab("dlresumecsv").'</A><br>';
        }
    }
}
include "include/trailer.inc.php";