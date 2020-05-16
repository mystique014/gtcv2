<?php
#########################################################################
#                         view_entry.php                                #
#                                                                       #
#                  Interface de visualisation d'une réservation         #
#                                                                       #
#                  Dernière modification : 17/09/2008                   #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 * D'après http://mrbs.sourceforge.net/
 *
 * Modification S Duchemin
 * Affichage des noms prénoms et photos des joueurs
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
include "include/misc.inc.php";
include "include/mrbs_sql.inc.php";


// Settings
require_once("./include/settings.inc.php");

//Chargement des valeurs de la table settingS
if (!loadSettings())
    die("Erreur chargement settings");

// Session related functions
require_once("./include/session.inc.php");

// Paramètres langage
include "include/language.inc.php";

// Resume session
$fin_session = 'n';
if (!grr_resumeSession())
    $fin_session = 'y';

if (($fin_session == 'y') and ($authentification_obli==1)) {
    header("Location: ./logout.php?auto=1");
    die();
};

if (($authentification_obli==0) and (!isset($_SESSION['login']))) {
    $session_login = '';
    $type_session = "no_session";
}
else
{
  $session_login = $_SESSION['login'];
  $type_session = "with_session";
}

// Initialisation
unset($reg_statut_id);
$reg_statut_id = isset($_GET["statut_id"]) ? "y" : "-";
if (isset($_GET["id"]))
{
  $id = $_GET["id"];
  settype($id,"integer");
}
else
{
  die();
}

$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];


// Recherche des infos liée à la réservation
$sql = "SELECT ".$_COOKIE["table_prefix"]."_entry.name,
       ".$_COOKIE["table_prefix"]."_entry.description,
       ".$_COOKIE["table_prefix"]."_entry.create_by,
       ".$_COOKIE["table_prefix"]."_room.room_name,
       ".$_COOKIE["table_prefix"]."_area.area_name,
       ".$_COOKIE["table_prefix"]."_entry.type,
       ".$_COOKIE["table_prefix"]."_entry.room_id,
       ".$_COOKIE["table_prefix"]."_entry.repeat_id,
    " . grr_sql_syntax_timestamp_to_unix("".$_COOKIE["table_prefix"]."_entry.timestamp") . ",
       (".$_COOKIE["table_prefix"]."_entry.end_time - ".$_COOKIE["table_prefix"]."_entry.start_time),
       ".$_COOKIE["table_prefix"]."_entry.start_time,
       ".$_COOKIE["table_prefix"]."_entry.end_time,
       ".$_COOKIE["table_prefix"]."_area.id,
       ".$_COOKIE["table_prefix"]."_entry.statut_entry,
       ".$_COOKIE["table_prefix"]."_room.delais_option_reservation,
       ".$_COOKIE["table_prefix"]."_entry.option_reservation
FROM ".$_COOKIE["table_prefix"]."_entry, ".$_COOKIE["table_prefix"]."_room, ".$_COOKIE["table_prefix"]."_area
WHERE ".$_COOKIE["table_prefix"]."_entry.room_id = ".$_COOKIE["table_prefix"]."_room.id
  AND ".$_COOKIE["table_prefix"]."_room.area_id = ".$_COOKIE["table_prefix"]."_area.id
  AND ".$_COOKIE["table_prefix"]."_entry.id='".$id."'
";

$res = grr_sql_query($sql);
if (! $res) fatal_error(0, grr_sql_error());

if(grr_sql_count($res) < 1) fatal_error(0, get_vocab('invalid_entry_id'));

$row = grr_sql_row($res, 0);
grr_sql_free($res);

$name         = htmlspecialchars($row[0]);
$description  = htmlspecialchars($row[1]);
$create_by    = htmlspecialchars($row[2]);
$room_name    = htmlspecialchars($row[3]);
$area_name    = htmlspecialchars($row[4]);
$type         = $row[5];
$room_id      = $row[6];
$repeat_id    = $row[7];
$updated      = time_date_string($row[8],$dformat);
$duration     = $row[9];
$area      = $row[12];
$statut_id = $row[13];
$delais_option_reservation = $row[14];
$option_reservation = $row[15];
$rep_type = 0;

// Si l'utilisateur est administrateur, possibilité de modifier le statut de la réservation (en cours / libérée)
if (($fin_session == 'n') and isset($_SESSION['login']) and (authGetUserLevel($_SESSION['login'],$room_id) >= 3) and (isset($_GET['ok']))) {
    $upd1 = "update ".$_COOKIE["table_prefix"]."_entry set statut_entry='-' where room_id = '".$room_id."'";
    if (grr_sql_command($upd1) < 0) return 0;
    $upd2 = "update ".$_COOKIE["table_prefix"]."_entry set statut_entry='$reg_statut_id' where id = '".$id."'";
    if (grr_sql_command($upd2) < 0) return 0;
    header("Location: ".$_GET['back']."");
}

#If we dont know the right date then make it up
if(!isset($day) or !isset($month) or !isset($year))
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
}


if((authGetUserLevel(getUserName(),-1) < 1) and ($authentification_obli==1))
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}
if(authUserAccesArea($session_login, $area)==0)
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

$date_now = time();

$page = verif_page();

print_header($day, $month, $year, $area, $type_session);

// Récupération des données concernant l'affichage du planning du domaine
get_planning_area_values($area);

if($enable_periods=='y') list( $start_period, $start_date) =  period_date_string($row[10]);
else $start_date = time_date_string($row[10],$dformat);

if($enable_periods=='y') list( , $end_date) =  period_date_string($row[11], -1);
else $end_date = time_date_string($row[11],$dformat);

// Nom, prénom et email du créateur de la réser
$sql = "select nom, prenom, email, login from ".$_COOKIE["table_prefix"]."_utilisateurs where login='$create_by'";
$res = grr_sql_query($sql);

if ($res) $row_user_login = grr_sql_row($res, 0);

if($repeat_id != 0)
{
    $res = grr_sql_query("SELECT rep_type, end_date, rep_opt, rep_num_weeks
                        FROM ".$_COOKIE["table_prefix"]."_repeat WHERE id=$repeat_id");
    if (! $res) fatal_error(0, grr_sql_error());

    if (grr_sql_count($res) == 1)
    {
        $row = grr_sql_row($res, 0);
        $rep_type     = $row[0];
        $rep_end_date = utf8_strftime($dformat,$row[1]);
        $rep_opt      = $row[2];
        $rep_num_weeks = $row[3];
    }
    grr_sql_free($res);
}

if ($enable_periods=='y') toPeriodString($start_period, $duration, $dur_units);
else toTimeString($duration, $dur_units);

$repeat_key = "rep_type_" . $rep_type;

# Now that we know all the data we start drawing it

?>
<?php    // Lit le premier caractère de la chaîne

if ($tab = str_word_count($description,1))
{
$pnom = $tab[1];
$pnom = $pnom[0];

$partenaire = $pnom.$tab[0];
$login_partenaire = strtoupper($partenaire);
} else {
$login_partenaire = '';
}
?>

 <center><table border="0">
   <tr><td><?php echo $name ?></td><td><?php    echo nl2br($description)  ?></td></tr>
	 <tr><td><?php echo '<img src="images/'.$row_user_login[3].'.jpg" border="0" />';?></td><td><?php echo '<img src="images/'.$login_partenaire.'.jpg" border="0" />';?></td></tr>
   <?php
    //Informations additionnelles
    $overload_data = mrbsEntryGetOverloadDesc($id);
    foreach ($overload_data as $fieldname=>$fielddata) {
        echo "<tr><TD><b>".$fieldname."</b></td>\n";
        echo "<td>".$fielddata."</td></tr>\n";
    }


   ?>
   <tr>
    <td><b><?php echo get_vocab("room").get_vocab("deux_points")  ?></b></td>
    <td><?php    echo  nl2br($area_name . " - " . $room_name) ?></td>
   </tr>
   <tr>
    <td><b><?php echo get_vocab("start_date").get_vocab("deux_points") ?></b></td>
<td><?php    echo $start_date         ?></td>
   </tr>
   <tr>
    <td><b><?php echo get_vocab("duration")            ?></b></td>
    <td><?php    echo $duration . " " . $dur_units ?></td>
   </tr>
   <tr>
    <td><b><?php echo get_vocab("end_date") ?></b></td>
    <td><?php    echo $end_date         ?></td>
   </tr>
   <?php
   echo "<tr><td><b>".get_vocab("type").get_vocab("deux_points")."</b></td>\n";
   $type_name = grr_sql_query1("select type_name from ".$_COOKIE["table_prefix"]."_type_area where type_letter='".$type."'");
   if ($type_name == -1) $type_name = "?$type?";
   echo "<td>".$type_name."</td></tr>";
   ?>
   <tr>
    <td><b><?php echo get_vocab("createdby") ?></b></td>
    <td><?php    echo "<a href='mailto:".$row_user_login[2]."'>".$row_user_login[1]." ".$row_user_login[0]."</a>";         ?></td>
   </tr>
    <tr>
    <td><b><?php echo get_vocab("lastupdate") ?></b></td>
    <td><?php    echo $updated            ?></td>
   </tr>
   <tr>
    <td><b><?php echo get_vocab("rep_type")  ?></b></td>
    <td><?php    echo get_vocab($repeat_key) ?></td>
   </tr>


    <?php

// Option de réservation
if (($delais_option_reservation > 0) and ($option_reservation!=-1))
{
  echo "<TR bgcolor=\"#FF6955\"><TD><B>".get_vocab("reservation_a_confirmer_au_plus_tard_le")."</B></TD>\n";
  echo "<TD><b>".time_date_string_jma($option_reservation,$dformat)."</b>\n";
  echo "</TD></TR>\n";
}

if($rep_type != 0)
{
    $opt = "";
    if (($rep_type == 2) || ($rep_type == 6))
    {
        # Display day names according to language and preferred weekday start.
        for ($i = 0; $i < 7; $i++)
        {
            $daynum = ($i + $weekstarts) % 7;
            if ($rep_opt[$daynum]) $opt .= day_name($daynum) . " ";
        }
    }
    if ($rep_type == 6)
    {
        echo "<tr><td><b>".get_vocab("rep_num_weeks")." ".get_vocab("rep_for_nweekly")."</b></td><td>$rep_num_weeks</td></tr>\n";
    }

    if($opt)
        echo "<tr><td><b>".get_vocab("rep_rep_day")."</b></td><td>$opt</td></tr>\n";

    echo "<tr><td><b>".get_vocab("rep_end_date")."</b></td><td>$rep_end_date</td></tr>\n";
}
?>

</table> </center>
<p>
<?php

if ((getWritable($create_by, getUserName(),$id)) and verif_booking_date(getUserName(), $id, $room_id, -1, $date_now, $enable_periods)) { 
    
	if(authGetUserLevel(getUserName(),-1) > 2) { ?>
	 <center><a href="edit_entry.php?id=<?php echo $id ?>&amp;page=<?php echo $page; ?>"><?php echo get_vocab("editentry") ?></a></center>
    <?php
    if($repeat_id)
        echo " -  <center><a href=\"edit_entry.php?id=$id&amp;edit_type=series&amp;day=$day&amp;month=$month&amp;year=$year&amp;page=$page\">".get_vocab("editseries")."</a></center>";
}?>
	
    
     <center><A HREF="del_entry.php?id=<?php echo $id ?>&amp;series=0&amp;page=<?php echo $page; ?>" onClick="return confirm('<?php echo get_vocab("confirmdel") ?>');"><?php echo get_vocab("deleteentry") ?></A></center>
    <?php

    if($repeat_id)
        echo " -  <center><A HREF=\"del_entry.php?id=$id&amp;series=1&amp;day=$day&amp;month=$month&amp;year=$year&amp;page=$page\" onClick=\"return confirm('".get_vocab("confirmdel")."');\">".get_vocab("deleteseries")."</A> <center>";

} ?>

 <center><a href="<?php echo $back ?>"><?php echo get_vocab("returnprev") ?></a></center>
<?php

// Si l'utilisateur est administrateur, possibilité de modifier le statut de la réservation (en cours / libérée)
if ( isset($_SESSION['login']) and (authGetUserLevel($_SESSION['login'],$room_id) >= 3))          {
    echo "<form name=\"form\" action=\"view_entry.php\" method=\"GET\">";
    echo "<br>
     <center><table border=\"1\" width=\"100%\"><tr><td align=\"center\" valign=\"middle\">
    <font size=\"+1\">".get_vocab("signaler_reservation_en_cours")."</font>".get_vocab("deux_points")."<input type=\"checkbox\" name=\"statut_id\" value=\"y\" ";
    if ($statut_id=='y') echo " checked ";
    echo " />
    <br><font size=\"-1\">".get_vocab("une_seule_reservation_en_cours")."</font>
    <center><input type=\"submit\" name=\"ok\" value=\"Envoyer\" /></center>
    </td></tr></table></center>\n";
    echo "<input type=\"hidden\" name=\"day\" value=\"".$day."\" />";
    echo "<input type=\"hidden\" name=\"month\" value=\"".$month."\" />";
    echo "<input type=\"hidden\" name=\"year\" value=\"".$year."\" />";
    echo "<input type=\"hidden\" name=\"page\" value=\"".$page."\" />";
    echo "<input type=\"hidden\" name=\"id\" value=\"".$id."\" />";
    echo "<input type=\"hidden\" name=\"back\" value=\"".$back."\" />";
    echo "</form>";
}

include "include/trailer.inc.php"; ?>