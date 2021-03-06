<?php
#########################################################################
#                            week_all.php                               #
#    Permet l'affichage des r�servation d'une semaine                   #
#              pour toutes les ressources d'un domaine.                 #
#             Derni�re modification : 01/11/2009                       #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 *
 * 
 * Modification S Duchemin
 * Choix dans la r�servation s'il s'agit d'un Utilisateur ou d'un administrateur
 * Choix dans la r�servation s'il s'agit d'un admin de domaine ou ressource
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
include "include/misc.inc.php";
include "include/functions.inc.php";
include "include/$dbsys.inc.php";
include "include/mincals.inc.php";

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

// Param�tres langage
include "include/language.inc.php";

// On affiche le lien "format imprimable" en bas de la page
$affiche_pview = '1';
if (!isset($_GET['pview'])) $_GET['pview'] = 0; else $_GET['pview'] = 1;

# Default parameters:
if (empty($debug_flag)) $debug_flag = 0;

# If we don't know the right date then use today:
if (!isset($day) or !isset($month) or !isset($year))
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
} else {
    // V�rification des dates
    settype($month,"integer");
    settype($day,"integer");
    settype($year,"integer");
    $minyear = strftime("%Y", getSettingValue("begin_bookings"));
    $maxyear = strftime("%Y", getSettingValue("end_bookings"));
    if ($day < 1) $day = 1;
    if ($day > 31) $day = 31;
    if ($month < 1) $month = 1;
    if ($month > 12) $month = 12;
    if ($year < $minyear) $year = $minyear;
    if ($year > $maxyear) $year = $maxyear;
    # Make the date valid if day is more then number of days in month:
    while (!checkdate($month, $day, $year))
        $day--;
}

if (($authentification_obli==0) and (!isset($_SESSION['login']))) {
    $session_login = '';
    $session_statut = '';
    $type_session = "no_session";
} else {
    $session_login = $_SESSION['login'];
    $session_statut = $_SESSION['statut'];
    $type_session = "with_session";
}
$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];

if (check_begin_end_bookings($day, $month, $year))
{
    showNoBookings($day, $month, $year, $area,$back,$type_session);
    exit();
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

// Fonction de comparaison
// 3-value compare: Returns result of compare as "< " "= " or "> ".
function cmp3($a, $b)
{
    if ($a < $b) return "< ";
    if ($a == $b) return "= ";
    return "> ";
}

// On v�rifie une fois par jour si le d�lai de confirmation des r�servations est d�pass�
// Si oui, les r�servations concern�es sont supprim�es et un mail automatique est envoy�.
if ((!isset($verif_reservation_auto)) or ($verif_reservation_auto == 0))
    verify_confirm_reservation();

# print the page header
print_header($day, $month, $year, $area, $type_session);

// Affichage d'un message pop-up
if (!($javascript_info_disabled)) {
    echo "<script type=\"text/javascript\" language=\"javascript\">";
    if (isset($_SESSION['displ_msg']))  echo " alert(\"".get_vocab("message_records")."\")";
    echo "</script>";
}
unset ($_SESSION['displ_msg']);

if (empty($area))
    $area = get_default_area();
if (empty($room))
    $room = grr_sql_query1("select min(id) from grr_room where area_id=$area");

// R�cup�ration des donn�es concernant l'affichage du planning du domaine
get_planning_area_values($area);

if($enable_periods=='y') {
    $resolution = 60;
    $morningstarts = 12;
    $morningstarts_minutes = 0;
    $eveningends = 12;
    $eveningends_minutes = count($periods_name)-1;
}

$time = mktime(0, 0, 0, $month, $day, $year);
$time_old = $time;

// date("w", $time) : jour de la semaine en partant de dimancche
// date("w", $time) - $weekstarts : jour de la semaine en partant du jour d�fini dans GRR
// Si $day ne correspond pas au premier jour de la semaine tel que d�fini dans GRR,
// on recule la date jusqu'au pr�c�dent d�but de semaine
// Evidemment, probl�me possible avec les changement �t�-hiver et hiver-�t�
if (($weekday = (date("w", $time) - $weekstarts + 7) % 7) > 0)
{
    $time -= $weekday * 86400;
}

if (!isset($correct_heure_ete_hiver) or ($correct_heure_ete_hiver == 1)) {
    // Si le dimanche correspondant au changement d'heure est entre $time et $time_old, on corrige de +1 h ou -1 h.
    if  ((heure_ete_hiver("ete",$year,0) <= $time_old) and (heure_ete_hiver("ete",$year,0) >= $time) and ($time_old != $time))
        $decal = 3600;
//    else if  ((heure_ete_hiver("hiver",$year,0) <= $time_old) and (heure_ete_hiver("hiver",$year,0) >= $time) and ($time_old != $time))
//        $decal = -3600;
    else
        $decal = 0;
    $time += $decal;
}
// $day_week, $month_week, $year_week sont jours, semaines et ann�es correspondant au premier jour de la semaine
$day_week   = date("d", $time);
$month_week = date("m", $time);
$year_week  = date("Y", $time);

//$date_start : date de d�but des r�servation � extraire
$date_start = mktime($morningstarts,0,0,$month_week,$day_week,$year_week);

// Nombre de jours dans le mois
$days_in_month = date("t", $date_start);

if ($debug_flag)
    echo "$month_week $day_week ";

// $date_end : date de fin des r�servation � extraire
$date_end = mktime($eveningends, $eveningends_minutes, 0, $month_week, $day_week+6, $year_week);

// Si format imprimable ($_GET['pview'] = 1), on n'affiche pas cette partie
if ($_GET['pview'] != 1) {
    # Table with areas, rooms, minicals.
    echo "\n<table width=\"100%\" cellspacing=15><tr><td>\n";
    $this_area_name = "";
    $this_room_name = "";

    if (isset($_SESSION['default_list_type']) or ($authentification_obli==1)) {
        $area_list_format = $_SESSION['default_list_type'];
    } else {
        $area_list_format = getSettingValue("area_list_format");
    }

    # show either a select box or the normal html list
    if ($area_list_format != "list") {
        echo make_area_select_html('week_all.php', $area, $year, $month, $day, $session_login); # from functions.inc.php
        echo make_room_select_html('week', $area, "", $year, $month, $day);
    # echo make_room_select_html('month', $area, $room, $year, $month, $day);
    } else {
        echo "\n<table cellspacing=1 border=0><tr><td>\n";
        echo make_area_list_html('week_all.php', $area, $year, $month, $day, $session_login); # from functions.inc.php
        # Show all rooms in the current area
        echo "</td><td>";
        make_room_list_html('week.php', $area, "", $year, $month, $day);
        echo "</td></tr></table>";
    }
    echo "</td>\n";

    #Draw the three month calendars
   $cal = isset($_GET["cal"]) ? $_GET["cal"] : NULL;
	if ($cal == 1)
	{
    minicals($year, $month, $day, $area, $room, 'week_all');
	echo "</table><table width=\"100%\" cellspacing=1 border=0>\n";
	}
}

$this_area_name = grr_sql_query1("select area_name from grr_area where id=$area");
$this_room_name = grr_sql_query1("select room_name from grr_room where id=$room");
$this_room_name_des = grr_sql_query1("select description from grr_room where id=$room");

# Don't continue if this area has no rooms:
if ($room <= 0)
{
    echo "<h1>".get_vocab('no_rooms_for_area')."</h1>\n";
    include "include/trailer.inc.php";
    exit;
}

# Show Month, Year, Area, Room header:
if (($this_room_name_des) and ($this_room_name_des!="-1")) {
    $this_room_name_des = " (".$this_room_name_des.")";
} else {
    $this_room_name_des = "";
}
switch ($dateformat) {
    case "en":
    $dformat = "%A, %b %d";
    break;
    case "fr":
    $dformat = "%A %d %b";
    break;
}
$v= mktime(0,0,0,$month,$day,$year);
$yy = date("Y",$v);
$mm = date("m",$v);
$dd = date("d",$v);


 echo "<td VALIGN=MIDDLE><h2 align=center>".get_vocab("week").get_vocab("deux_points").utf8_strftime($dformat, $date_start)." - ". utf8_strftime($dformat, $date_end)
  . "<br> $this_area_name - ".get_vocab("all_rooms")."</h2></center>\n";
if ($cal == 1)
{
echo "</td><td align='right'><a href=\"week_all.php?year=$yy&amp;month=$mm&amp;day=$dd&amp;area=$area&amp;room=$room&amp;cal=0\">Cacher le calendrier</a></td></tr></table>\n";
} else {
echo "</td><td align='right'><a href=\"week_all.php?year=$yy&amp;month=$mm&amp;day=$dd&amp;area=$area&amp;room=$room&amp;cal=1\">Afficher le calendrier</a></td></tr></table>\n";
}


#y? are year, month and day of the previous week.
#t? are year, month and day of the next week.

$i= mktime(0,0,0,$month_week,$day_week-7,$year_week);
$yy = date("Y",$i);
$ym = date("m",$i);
$yd = date("d",$i);

$i= mktime(0,0,0,$month_week,$day_week+7,$year_week);
$ty = date("Y",$i);
$tm = date("m",$i);
$td = date("d",$i);
// Si format imprimable ($_GET['pview'] = 1), on n'affiche pas cette partie
if ($_GET['pview'] != 1) {
    #Show Go to week before and after links
    echo "\n<table width=\"100%\"><tr><td>
      <a href=\"week_all.php?year=$yy&amp;month=$ym&amp;day=$yd&amp;area=$area&amp;room=$room\">
      &lt;&lt; ".get_vocab("weekbefore")."</a></td>
      <td>&nbsp;</td>
      <td align=right><a href=\"week_all.php?year=$ty&amp;month=$tm&amp;day=$td&amp;area=$area&amp;room=$room\">".
      get_vocab('weekafter')." &gt;&gt;</a></td></tr></table>";
}

# Used below: localized "all day" text but with non-breaking spaces:
$all_day = str_replace(" ", "&nbsp;", get_vocab("all_day"));

#Get all meetings for this month in the room that we care about
# row[0] = Start time
# row[1] = End time
# row[2] = Entry ID
# row[3] = Entry name (brief description)
# row[4] = creator of the booking
# row[5] =
# row[6] =
# row[7] = status of the booking
# row[8] = Full description

$sql = "SELECT start_time, end_time, grr_entry.id, name, create_by, room_name,type, statut_entry, grr_entry.description, grr_entry.option_reservation, grr_room.delais_option_reservation
   FROM grr_entry, grr_room, grr_area
   where
   grr_entry.room_id=grr_room.id and
   grr_area.id = grr_room.area_id and
   grr_area.id = '".$area."' and
   start_time <= $date_end AND
   end_time > $date_start
   ORDER by start_time, end_time, grr_entry.id";

# Build an array of information about each day in the month.
# The information is stored as:
#  d[monthday]["id"][] = ID of each entry, for linking.
#  d[monthday]["data"][] = "start-stop" times of each entry.

$res = grr_sql_query($sql);
if (! $res) echo grr_sql_error();
else for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
{
    $sql_creator = "SELECT prenom, nom FROM grr_utilisateurs WHERE login = '$row[4]'";
    $res_creator = grr_sql_query($sql_creator);
    if ($res_creator) $row_user = grr_sql_row($res_creator, 0);


    # Fill in data for each day during the month that this meeting covers.
    # Note: int casts on database rows for min and max is needed for PHP3.
    $t = max((int)$row[0], $date_start);
    $end_t = min((int)$row[1], $date_end);
    $day_num = date("j", $t);
    $month_num = date("m", $t);
    $year_num = date("Y", $t);
    if ($enable_periods == 'y')
        $midnight = mktime(12,0,0,$month_num,$day_num,$year_num);
    else
        $midnight = mktime(0, 0, 0, $month_num, $day_num, $year_num);
// bug changement heure �t�/hiver
//    $midnight2 = gmmktime(0, 0, 0, $month_num, $day_num, $year_num);

    if ($debug_flag)
        echo "<br>DEBUG: result $i, id $row[2], starts $row[0], ends $row[1], temps en heures : ".($row[1]- $row[0])/(60*60).", midnight : $midnight \n";
    while ($t < $end_t)
    {
        if ($debug_flag) echo "<br>DEBUG: Entry $row[2] day $day_num\n";
        $d[$day_num]["id"][] = $row[2];
        // Info-bulle
        if (!isset($display_info_bulle)) $display_info_bulle = "";
        if ($display_info_bulle == 1)
           $d[$day_num]["who"][] = get_vocab("created_by").$row_user[0]." ".$row_user[1];
        else if ($display_info_bulle == 2)
           $d[$day_num]["who"][] = $row[8];
        else
           $d[$day_num]["who"][] = "";

        $d[$day_num]["who1"][] = $row[3];
        $d[$day_num]["room"][]=$row[5] ;
        $d[$day_num]["color"][]=$row[6];
        $d[$day_num]["res"][] = $row[7];
        if ((isset($display_full_description)) and ($display_full_description==1))
            $d[$day_num]["description"][] = $row[8];
        if ($row[10] > 0)
            $d[$day_num]["option_reser"][] = $row[9];
        else
            $d[$day_num]["option_reser"][] = -1;
        $midnight_tonight = $midnight + 86400;
        if (!isset($correct_heure_ete_hiver) or ($correct_heure_ete_hiver == 1)) {
            if  (heure_ete_hiver("hiver",$year_num,0) == mktime(0,0,0,$month_num,$day_num,$year_num))
                $midnight_tonight +=3600;
            else if  (heure_ete_hiver("ete",$year_num,0) == mktime(0,0,0,$month_num,$day_num,$year_num))
                $midnight_tonight -=3600;
        }

        # Describe the start and end time, accounting for "all day"
        # and for entries starting before/ending after today.
        # There are 9 cases, for start time < = or > midnight this morning,
        # and end time < = or > midnight tonight.
        # Use ~ (not -) to separate the start and stop times, because MSIE
        # will incorrectly line break after a -.
        if ($enable_periods == 'y') {
              $start_str = str_replace(" ", "&nbsp;", period_time_string($row[0]));
              $end_str   = str_replace(" ", "&nbsp;", period_time_string($row[1], -1));
              // Debug
              //echo affiche_date($row[0])." ".affiche_date($midnight)." ".affiche_date($row[1])." ".affiche_date($midnight_tonight)."<br>";
              switch (cmp3($row[0], $midnight) . cmp3($row[1], $midnight_tonight))
              {
            case "> < ":         # Starts after midnight, ends before midnight
            case "= < ":         # Starts at midnight, ends before midnight
                    if ($start_str == $end_str)
                        $d[$day_num]["data"][] = $start_str;
                    else
                        $d[$day_num]["data"][] = $start_str . "~" . $end_str;
                    break;
            case "> = ":         # Starts after midnight, ends at midnight
                    $d[$day_num]["data"][] = $start_str . "~24:00";
                    break;
            case "> > ":         # Starts after midnight, continues tomorrow
                    $d[$day_num]["data"][] = $start_str . "~====&gt;";
                    break;
            case "= = ":         # Starts at midnight, ends at midnight
                    $d[$day_num]["data"][] = $all_day;
                    break;
            case "= > ":         # Starts at midnight, continues tomorrow
                    $d[$day_num]["data"][] = $all_day . "====&gt;";
                    break;
            case "< < ":         # Starts before today, ends before midnight
                    $d[$day_num]["data"][] = "&lt;====~" . $end_str;
                    break;
            case "< = ":         # Starts before today, ends at midnight
                    $d[$day_num]["data"][] = "&lt;====" . $all_day;
                    break;
            case "< > ":         # Starts before today, continues tomorrow
                    $d[$day_num]["data"][] = "&lt;====" . $all_day . "====&gt;";
                    break;
              }
        } else {
          switch (cmp3($row[0], $midnight) . cmp3($row[1], $midnight_tonight))
          {
            case "> < ":         # Starts after midnight, ends before midnight
            case "= < ":         # Starts at midnight, ends before midnight
                $d[$day_num]["data"][] = date(hour_min_format(), $row[0]) . "~" . date(hour_min_format(), $row[1]);
                break;
            case "> = ":         # Starts after midnight, ends at midnight
                $d[$day_num]["data"][] = date(hour_min_format(), $row[0]) . "~24:00";
                break;
            case "> > ":         # Starts after midnight, continues tomorrow
                $d[$day_num]["data"][] = date(hour_min_format(), $row[0]) . "~===&gt;";
                break;
            case "= = ":         # Starts at midnight, ends at midnight
                $d[$day_num]["data"][] = $all_day;
                break;
            case "= > ":         # Starts at midnight, continues tomorrow
                $d[$day_num]["data"][] = $all_day . "====&gt;";
                break;
            case "< < ":         # Starts before today, ends before midnight
                $d[$day_num]["data"][] = "&lt;====~" . date(hour_min_format(), $row[1]);
                break;
            case "< = ":         # Starts before today, ends at midnight
                $d[$day_num]["data"][] = "&lt;====" . $all_day;
                break;
            case "< > ":         # Starts before today, continues tomorrow
                $d[$day_num]["data"][] = "&lt;====" . $all_day . "====&gt;";
                break;
          }
        }

        # Only if end time > midnight does the loop continue for the next day.
        if ($row[1] <= $midnight_tonight) break;

        $t = $midnight = $midnight_tonight;
        $day_num = date("j", $t);
    }
}
if ($debug_flag)
{
    echo "<p>DEBUG: Array of month day data:<p><pre>\n";
    for ($i = 1; $i <= $days_in_month; $i++)
    {
        if (isset($d[$i]["id"]))
        {
            $n = count($d[$i]["id"]);
            echo "Day $i has $n entries:\n";
            for ($j = 0; $j < $n; $j++)
                echo "  ID: " . $d[$i]["id"][$j] .
                    " Data: " . $d[$i]["data"][$j] . "\n";
        }
    }
    echo "</pre>\n";
}

echo "\n<table border=2 width=\"100%\">\n<tr>";

# We need to know what all the rooms area called, so we can show them all
# pull the data from the db and store it. Convienently we can print the room
# headings and capacities at the same time

$sql = "select room_name, capacity, id, description, statut_room from grr_room where area_id='".$area."' order by order_display, room_name";
$res = grr_sql_query($sql);

# It might be that there are no rooms defined for this area.
# If there are none then show an error and dont bother doing anything
# else
if (! $res) fatal_error(0, grr_sql_error());
if (grr_sql_count($res) == 0)
{
    echo "<h1>".get_vocab("no_rooms_for_area")."</h1>";
    grr_sql_free($res);
} else {
    // D�but Affichage de la premi�re ligne contenant les jours
    echo tdcell("cell_hours", 12)."<b>".get_vocab("rooms")."</b></td>\n"; // Premi�re cellule
    $t = $time;
    $num_week_day = $weekstarts; // Pour le calcul des jours � afficher
    for ($weekcol = 0; $weekcol < 7; $weekcol++)
    {
        $num_day = strftime("%d", $t);
        $temp_month = strftime("%m", $t);
        $temp_year = strftime("%Y", $t);
        $t += 86400;
        if (!isset($correct_heure_ete_hiver) or ($correct_heure_ete_hiver == 1)) {
            // Correction dans le cas d'un changement d'heure
            if  (heure_ete_hiver("hiver",$temp_year,0) == mktime(0,0,0,$temp_month,$num_day,$temp_year))
                $t +=3600;
            else if  (heure_ete_hiver("ete",$temp_year,0) == mktime(0,0,0,$temp_month,$num_day,$temp_year))
                $t -=3600;
        }
        if ($display_day[$num_week_day] == 1) // on n'affiche pas tous les jours de la semaine
            echo tdcell("cell_hours", 12.5)."<a href='day.php?year=".$temp_year."&month=".$temp_month."&day=".$num_day."&area=".$area."'>" . day_name(($weekcol + $weekstarts)%7) . " ".$num_day."</a></td>\n";
        $num_week_day++;// Pour le calcul des jours � afficher
        $num_week_day = $num_week_day % 7;// Pour le calcul des jours � afficher

    }
    echo "</tr>\n";
    // Fin Affichage de la premi�re ligne contenant les jours

  $li=0;
  // Boucle sur les ressources
  for ($ir = 0; ($row = grr_sql_row($res, $ir)); $ir++)
    {
    // Affichage de la premi�re colonne (nom des ressources)
    echo "<tr>\n";
    echo tdcell("cell_hours")."<a href='week.php?year=".$year."&month=".$month."&day=".$day."&room=".$row[2]."'>" . htmlspecialchars($row[0]) ."</a></td>\n";
    $li++;

    $t = $time;
    $t2 = $time;
    $num_week_day = $weekstarts; // Pour le calcul des jours � afficher
    for ($k = 0; $k<=6; $k++)
      {
        $cday = date("j", $t2);
        $cmonth = strftime("%m", $t2);
        $cyear = strftime("%Y", $t2);

        $t2 += 86400;
        if (!isset($correct_heure_ete_hiver) or ($correct_heure_ete_hiver == 1)) {
            // Correction dans le cas d'un changement d'heure
            $temp_day = strftime("%d", $t2);
            $temp_month = strftime("%m", $t2);
            $temp_year = strftime("%Y", $t2);
            if  (heure_ete_hiver("hiver",$temp_year,0) == mktime(0,0,0,$temp_month,$temp_day,$temp_year))
                $t2 +=3600;
            else if  (heure_ete_hiver("ete",$temp_year,0) == mktime(0,0,0,$temp_month,$temp_day,$temp_year))
                $t2 -=3600;
        }
        if ($display_day[$num_week_day] == 1) { // condition "on n'affiche pas tous les jours de la semaine"
        echo "<td style=\"vertical-align: middle;\" class=\"cell_month\">";
        # Anything to display for this day?
        if (isset($d[$cday]["id"][0])) {
            echo "<font size=-2>";
            $n = count($d[$cday]["id"]);
            # Show the start/stop times, 2 per line, linked to view_entry.
            # If there are 12 or fewer, show them, else show 11 and "...".
            for ($i = 0; $i < $n; $i++) {
                /*if ($i == 11 && $n > 12) {
                    echo " ...\n";
                    break;
                } */
                if ($d[$cday]["room"][$i]==$row[0]) {
                    #if ($i > 0 && $i % 2 == 0) echo "<br>"; else echo " ";
                    echo "\n<table width='100%' border='0'>";
                    tdcell($d[$cday]["color"][$i]);
                   if ($d[$cday]["res"][$i]=='y')
                       echo "&nbsp;<img src=\"img_grr/buzy.png\" alt=\"".get_vocab("reservation_en_cours")."\" title=\"".get_vocab("reservation_en_cours")."\" width=\"20\" height=\"20\" border=\"0\" />&nbsp;\n";
                   // si la r�servation est � confirmer, on le signale
                   if ((isset($d[$cday]["option_reser"][$i])) and ($d[$cday]["option_reser"][$i]!=-1)) echo "&nbsp;<img src=\"img_grr/small_flag.png\" alt=\"".get_vocab("reservation_a_confirmer_au_plus_tard_le")."\" title=\"".get_vocab("reservation_a_confirmer_au_plus_tard_le")."&nbsp;".time_date_string_jma($d[$cday]["option_reser"][$i],$dformat)."\" width=\"20\" height=\"20\" border=\"0\" />&nbsp;\n";

                    echo "</font><b>". $d[$cday]["data"][$i]
                    . "<br></b>"
                    . "<a title=\"".htmlspecialchars($d[$cday]["who"][$i])."\" href=\"view_entry.php?id=" . $d[$cday]["id"][$i]."&amp;page=week_all\">"
                    . htmlspecialchars($d[$cday]["who1"][$i])
                    . "</font>"
                    . "</a>";
                    if ((isset($display_full_description)) and ($display_full_description==1) and ($d[$cday]["description"][$i]!= ""))
                        echo "<br><i>".$d[$cday]["description"][$i]."</i>";

                    echo "</td></table>";
                }
            }
            echo "</font>";
        }
        //  Possibilit� de faire une nouvelle r�servation
        $hour = date("H",$date_now); // Heure actuelle
        $date_booking = mktime(24, 0, 0, $cmonth, $cday, $cyear); // minuit
        echo "<center>";
        if (est_hors_reservation(mktime(0,0,0,$cmonth,$cday,$cyear)))
            echo "<img src=\"img_grr/stop.png\" border=\"0\" alt=\"".get_vocab("reservation_impossible")."\"  title=\"".get_vocab("reservation_impossible")."\" width=\"16\" height=\"16\" />";
        else
            if ((authGetUserLevel(getUserName(),-1) > 1)
            and (UserRoomMaxBooking(getUserName(), $row[2], 1) != 0)
            and verif_booking_date(getUserName(), -1, $row[2], $date_booking, $date_now, $enable_periods)
            and verif_delais_max_resa_room(getUserName(), $row[2], $date_booking)
            and verif_delais_min_resa_room(getUserName(), $row[2], $date_booking)
			and UserAreaGroup(getUserName(), $area)
            and (($row[4] == "1") or
              (($row[4] == "0") and (authGetUserLevel(getUserName(),$room) > 2) ))) {
                if ($enable_periods == 'y') {
                	if ((authGetUserLevel(getUserName(),$room,'room') >= 3) OR (authGetUserLevel(getUserName(),$area,'area') >= 4))
									{
                    echo "<a href=\"edit_entry.php?area=$area&amp;room=".$row[2]."&amp;period=&amp;year=$cyear&amp;month=$cmonth&amp;day=$cday&amp;page=week_all\" title=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\" alt=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\"><img src=img_grr/new.png border=0></a>";
                	}	else  {
                	echo "<a href=\"edit_entry_user.php?area=$area&amp;room=".$row[2]."&amp;period=&amp;year=$cyear&amp;month=$cmonth&amp;day=$cday&amp;page=week_all\" title=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\" alt=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\"><img src=img_grr/new.png border=0></a>";
													}
								 } else {
								 	if ((authGetUserLevel(getUserName(),$room,'room') >= 3) OR (authGetUserLevel(getUserName(),$area,'area') >= 4))
									{
                    echo "<a href=\"edit_entry.php?area=$area&amp;room=".$row[2]."&amp;hour=$hour&amp;minute=0&amp;year=$cyear&amp;month=$cmonth&amp;day=$cday&amp;page=week_all\" title=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\" alt=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\"><img src=img_grr/new.png border=0></a>";
            			} else {
            			echo "<a href=\"edit_entry_user.php?area=$area&amp;room=".$row[2]."&amp;hour=$hour&amp;minute=0&amp;year=$cyear&amp;month=$cmonth&amp;day=$cday&amp;page=week_all\" title=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\" alt=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\"><img src=img_grr/new.png border=0></a>";
													}
									}
						} else {
                echo "&nbsp;";
            }
        echo "</center>";
        echo "</td>\n";
        }  // Fin de la condition "on n'affiche pas tous les jours de la semaine"
        if (++$weekcol == 5) $weekcol = 0;
        $num_week_day++;// Pour le calcul des jours � afficher
        $num_week_day = $num_week_day % 7;// Pour le calcul des jours � afficher

      }
      echo "</tr>";
    }
}
echo "</table>\n";
show_colour_key($area);

include "include/trailer.inc.php";
?>