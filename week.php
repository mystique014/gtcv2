<?php
#########################################################################
#                            week.php                                   													  #
#    Permet l'affichage de la page d'accueil lorsque l'on est en mode  									  #
#    d'affichage "semaine".                                             												  #
#       Derni�re modification : 07/11/2010											                                  #
#                                                                     														  #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 * D'apr�s http://mrbs.sourceforge.net/
 *
 * Modification S Duchemin
 * Choix dans la r�servation s'il s'agit d'un Utilisateur ou d'un administrateur
 * Correction du bug d'affichage pour le changement d'heure dernier dimanche de Mars et d'Octobre
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
if (empty($room))
    $room = grr_sql_query1("select min(id) from ".$_COOKIE["table_prefix"]."_room where area_id=$area");
$area =  mrbsGetRoomArea($room);


# Note $room will be -1 if there are no rooms; this is checked for below.

// R�cup�ration des donn�es concernant l'affichage du planning du domaine
get_planning_area_values($area);

// Param�tres langage
include "include/language.inc.php";

// On affiche le lien "format imprimable" en bas de la page
$affiche_pview = '1';
if (!isset($_GET['pview'])) $_GET['pview'] = 0; else $_GET['pview'] = 1;

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

$date_now = time();

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
# Set the date back to the previous $weekstarts day (Sunday, if 0):
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
// Si le dimanche correspondant au changement d'heure est entre $time et $time_old, on corrige de +1 h ou -1 h.
if (!isset($correct_heure_ete_hiver) or ($correct_heure_ete_hiver == 1)) {
    if  ((heure_ete_hiver("ete",$year,0) <= $time_old) and (heure_ete_hiver("ete",$year,0) >= $time) and ($time_old != $time) and (date("H", $time)== 23))
        $decal = 3600;
    else
        $decal = 0;
    $time += $decal;
}

// On v�rifie une fois par jour si le d�lai de confirmation des r�servations est d�pass�
// Si oui, les r�servations concern�es sont supprim�es et un mail automatique est envoy�.
if ((!isset($verif_reservation_auto)) or ($verif_reservation_auto == 0))
    verify_confirm_reservation();

// $day_week, $month_week, $year_week sont jours, semaines et ann�es correspondant au premier jour de la semaine
$day_week   = date("d", $time);
$month_week = date("m", $time);
$year_week  = date("Y", $time);

# print the page header
print_header($day, $month, $year, $area, $type_session);




#Draw the three month calendars
	$cal = isset($_GET["cal"]) ? $_GET["cal"] : NULL;
	if ($cal == 1)
	{
	echo'<div class="row">'.PHP_EOL;
	echo'<div class="col-md-12">'.PHP_EOL;
	echo "<table width=\"100%\" cellspacing=1 border=0><tr>\n<td>";
    minicals($year, $month, $day, $area, $room, 'week');
	echo "</table><table width=\"100%\" cellspacing=1 border=0>\n";
	echo'</div>'.PHP_EOL;
	echo'</div>'.PHP_EOL;
	}
echo'<div class="row">'.PHP_EOL;
echo'<div class="col-md-12 center">'.PHP_EOL;
$v= mktime(0,0,0,$month,$day,$year);
$yy = date("Y",$v);
$mm = date("m",$v);
$dd = date("d",$v);
if ($cal == 1)
{
echo "<a href=\"week.php?year=$yy&amp;month=$mm&amp;day=$dd&amp;area=$area&amp;room=$room&amp;cal=0\">Cacher le calendrier</a>\n";
} else {
echo "<a href=\"week.php?year=$yy&amp;month=$mm&amp;day=$dd&amp;area=$area&amp;room=$room&amp;cal=1\">Afficher le calendrier</a>\n";
}	
echo'</div>'.PHP_EOL;
echo'</div>'.PHP_EOL;
	
if($enable_periods=='y') {
    $resolution = 60;
    $morningstarts = 12;
    $morningstarts_minutes = grr_sql_query1("select minute_morningstarts_area from ".$_COOKIE["table_prefix"]."_area where id=$area");
    $eveningends = 12;
    $eveningends_minutes = count($periods_name)-1;
}

?>
<script type="text/javascript" src="functions.js" language="javascript"></script>
<?php

// Si format imprimable ($_GET['pview'] = 1), on n'affiche pas cette partie
if ($_GET['pview'] != 1) {

// Affichage d'un message pop-up
if (!($javascript_info_disabled)) {
    echo "<script type=\"text/javascript\" language=\"javascript\">";
    if (isset($_SESSION['displ_msg']))  echo " alert(\"".get_vocab("message_records")."\")";
    echo "</script>";
}
unset ($_SESSION['displ_msg']);

// Si c'est un admin qui est connect�, on affiche le nombre de personnes actuellement connect�es.
if(authGetUserLevel(getUserName(),-1) >= 5)
{
    $sql = "select LOGIN from ".$_COOKIE["table_prefix"]."_log where END > now()";
    $res = grr_sql_query($sql);
    $nb_connect = grr_sql_count($res);
    if ($nb_connect == 1) {
        echo "<a href='admin_view_connexions.php'>".$nb_connect.get_vocab("one_connected")."</a>";
    } else {
        echo "<a href='admin_view_connexions.php'>".$nb_connect.get_vocab("several_connected")."</a>";
    }
    $version_old = getSettingValue("version");
    if (($version_old =='') or ($version_grr > $version_old)) {
        echo "<script type=\"text/javascript\" language=\"javascript\">";
        echo " alert(\"".get_vocab("maj_bdd_not_update").get_vocab("please_go_to_admin_maj.php")."\")";
        echo "</script>";
    }
}

// fin de la condition "Si format imprimable"
}

# Define the start of day and end of day (default is 7-7)
    $morningstarts_minutes = grr_sql_query1("select minute_morningstarts_area from ".$_COOKIE["table_prefix"]."_area where id=$area");
	$eveningends_minutes = grr_sql_query1("select eveningends_minutes_area from ".$_COOKIE["table_prefix"]."_area where id=$area");

	$am7=mktime($morningstarts,$morningstarts_minutes,0,$month_week,$day_week,$year_week);
	$pm7=mktime($eveningends,$eveningends_minutes,0,$month,$day_week,$year_week);

# Start and end of week:
$week_midnight = mktime(0, 0, 0, $month_week, $day_week, $year_week);
$week_start = $am7;
$week_end = mktime($eveningends, $eveningends_minutes, 0, $month_week, $day_week+6, $year_week);
$this_area_name = "";
$this_room_name = "";



// Si format imprimable ($_GET['pview'] = 1), on n'affiche pas cette partie
if ($_GET['pview'] != 1) {
	
	
	
    # Table with areas, rooms, minicals.
    //echo "<table width=\"100%\" cellspacing=0 border=1><tr><td>\n";

    if (isset($_SESSION['default_list_type']) or ($authentification_obli==1)) {
        $area_list_format = $_SESSION['default_list_type'];
    } else {
        $area_list_format = getSettingValue("area_list_format");
    }
	
    # show either a select box or the normal html list
    if ($area_list_format != "list") {
		echo'<div class="row">'.PHP_EOL;
		echo'<div class="col-xs-3">'.PHP_EOL;
        echo make_area_select_html('week_all.php', $area, $year, $month, $day, $session_login); # from functions.inc.php
        //echo "</td>\n<td>\n";
		echo'</div>'.PHP_EOL;
		echo'<div class="col-xs-3 ">'.PHP_EOL;
        echo make_room_select_html('week', $area, $room, $year, $month, $day);
		echo'</div>'.PHP_EOL;
		echo'</div>'.PHP_EOL;
		
    } else {
		echo'<div class="row">'.PHP_EOL;
		echo'<div class="col-xs-3">'.PHP_EOL;
        //echo "<table width=\"100%\" cellspacing=0><tr><td>\n";
        echo make_area_list_html('week_all.php', $area, $year, $month, $day, $session_login); # from functions.inc.php
		echo'</div>'.PHP_EOL;
        # Show all rooms in the current area
        //echo "</td>\n<td>\n";
		echo'<div class="col-xs-3">'.PHP_EOL;
        make_room_list_html('week.php', $area, $room, $year, $month, $day);
        //echo "</td>\n";
		echo'</div>'.PHP_EOL;
		echo'</div>'.PHP_EOL;
    }
    //echo "</td>\n";

    echo'<div class="row">'.PHP_EOL;
	echo'<div class="col-md-12">'.PHP_EOL;
	//echo "<table width=\"100%\" cellspacing=1 border=0>\n";
	
}

$this_area_name = grr_sql_query1("select area_name from ".$_COOKIE["table_prefix"]."_area where id=$area");
$this_room_name = grr_sql_query1("select room_name from ".$_COOKIE["table_prefix"]."_room where id=$room");
$this_room_name_des = grr_sql_query1("select description from ".$_COOKIE["table_prefix"]."_room where id=$room");
$this_statut_room = grr_sql_query1("select statut_room from ".$_COOKIE["table_prefix"]."_room where id=$room");
$this_show_fic_room = grr_sql_query1("select show_fic_room from ".$_COOKIE["table_prefix"]."_room where id=$room");
$this_delais_option_reservation = grr_sql_query1("select delais_option_reservation from ".$_COOKIE["table_prefix"]."_room where id=$room");


# Don't continue if this area has no rooms:
if ($room <= 0)
{
    echo "<h4>".get_vocab("no_rooms_for_area")."</h1>";
    include "include/trailer.inc.php";
    exit;
}


# Show area and room:
if (($this_room_name_des) and ($this_room_name_des!="-1")) {
    $this_room_name_des = " (".$this_room_name_des.")";
} else {
    $this_room_name_des = "";
}
echo "<h4 align=center>".ucfirst($this_area_name)." - $this_room_name $this_room_name_des\n";

if ($this_show_fic_room == 'y')
    echo "<A href='javascript:centrerpopup(\"view_room.php?id_room=$room\",600,480,\"scrollbars=yes,statusbar=no,resizable=yes\")' \" title=\"".get_vocab("fiche_ressource")."\">
           <img src=\"img_grr/details.png\" alt=\"d�tails\" border=\"0\" /></a>";
if (authGetUserLevel(getUserName(),$room) > 2)
    echo "<a href='admin_edit_room.php?room=$room'><img src=\"img_grr/editor.png\" alt=\"configuration\" border=\"0\" title=\"".get_vocab("Configurer la ressource")."\" width=\"30\" height=\"30\" /></a>";


if ($this_statut_room == "0")
    echo "<h4 align=center><font color=\"#BA2828\">".get_vocab("ressource_temporairement_indisponible")."</font></h4>";
	echo "</h4>";
		echo'</div>'.PHP_EOL;
		echo'</div>'.PHP_EOL;


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
    //echo "<table width=\"100%\"><tr><td>\n
	echo'<div class="row">'.PHP_EOL;
	echo'<div class="col-xs-6">'.PHP_EOL;
      echo'<a href="week.php?year='.$yy.'&amp;month='.$ym.'&amp;day='.$yd.'&amp;area='.$area.'&amp;room='.$room.'">&lt;&lt; '.get_vocab("weekbefore").'</a>'.PHP_EOL;
	  echo'</div>'.PHP_EOL;
	  echo'<div class="col-xs-6">'.PHP_EOL;
      echo'<span class="pull-right"><a href="week.php?year='.$ty.'&amp;month='.$tm.'&amp;day='.$td.'&amp;area='.$area.'&amp;room='.$room.'">'.get_vocab("weekafter").' &gt;&gt;</a></span>'.PHP_EOL;
	   echo'</div>'.PHP_EOL;
	   echo'</div>'.PHP_EOL;
}
#Get all appointments for this week in the room that we care about
# row[0] = Start time
# row[1] = End time
# row[2] = Entry type
# row[3] = Entry name (brief description)
# row[4] = Entry ID
# row[5] = creator of the booking
# row[6] = status of the booking
# row[7] = Full description
# The range predicate (starts <= week_end && ends > week_start) is
# equivalent but more efficient than the original 3-BETWEEN clauses.
$sql = "SELECT start_time, end_time, type, name, id, create_by, statut_entry, description, option_reservation
   FROM ".$_COOKIE["table_prefix"]."_entry
   WHERE room_id=$room
   AND start_time < ".($week_end+$resolution)." AND end_time > $week_start ORDER BY start_time";

# Chaque tableau row retourn� par la requ�te est une r�servation.
# On construit alors un tableau de la forme :
# d[weekday][slot][x], o� x = id, color, data.
# [slot] is based at 0 for midnight, but only slots within the hours of
# interest (morningstarts : eveningends) are filled in.
# [id] and [data] are only filled in when the meeting should be labeled,
# which is once for each meeting on each weekday.
# Note: weekday here is relative to the $weekstarts configuration variable.
# If 0, then weekday=0 means Sunday. If 1, weekday=0 means Monday.

$first_slot = (($morningstarts * 3600)+($morningstarts_minutes * 60))/ $resolution;
$last_slot = ($eveningends * 3600 + $eveningends_minutes * 60) / $resolution;

if ($debug_flag) echo "<br>DEBUG: query=$sql <br>first_slot=$first_slot - last_slot=$last_slot\n";

$res = grr_sql_query($sql);
if (! $res) echo grr_sql_error();
else for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
{
    $sql_creator = "SELECT prenom, nom FROM ".$_COOKIE["table_prefix"]."_utilisateurs WHERE login = '$row[5]'";
    $res_creator = grr_sql_query($sql_creator);
    if ($res_creator) $row_user = grr_sql_row($res_creator, 0);


    if ($debug_flag)
        echo "<br>DEBUG: result $i, id $row[4], starts $row[0] (".affiche_date($row[0])."), ends $row[1] (".affiche_date($row[1]).")\n";

    # Fill in slots for the meeting. Start at the meeting start time or
    # week start (which ever is later), and end one slot before the meeting
    # end time or week end (which ever is earlier).
    # Note: int casts on database rows for min and max is needed for PHP3.

    // Pour la r�servation en cours, on d�termine le d�but de la journ�e $debut_jour
    $month_current = date("m",$row[0]);
    $day_current = date("d",$row[0]);
    $year_current  = date("Y",$row[0]);
    $debut_jour=mktime($morningstarts,$morningstarts_minutes,0,$month_current,$day_current,$year_current);

    $t = max(round_t_down($row[0], $resolution, $debut_jour), $week_start);
    $end_t = min((int)round_t_up((int)$row[1],
                     (int)$resolution, $debut_jour),
                             (int)$week_end+1);
    $weekday = (date("w", $t) + 7 - $weekstarts) % 7;

    $prev_weekday = -1; # Invalid value to force initial label.
    $slot = ($t - $week_midnight) % 86400 / $resolution;
    do
    {
        if ($debug_flag) echo "<br>DEBUG: t=$t (".affiche_date($t)."), end_t=$end_t (".affiche_date($end_t)."), weekday=$weekday, slot=$slot\n";

        if ($slot < $first_slot)
        {
            # This is before the start of the displayed day; skip to first slot.
            $slot = $first_slot;
            $t = $weekday * 86400 + $am7;
            continue;
        }

        if ($slot <= $last_slot)
        {
            # This is within the working day; color it.
            $d[$weekday][$slot]["color"] = $row[2];
            # Only label it if it is the first time on this day:
            if ($prev_weekday != $weekday)
            {
                $prev_weekday = $weekday;
                $d[$weekday][$slot]["data"] = $row[3];
                $d[$weekday][$slot]["id"] = $row[4];
                // Info-bulle
                if (!isset($display_info_bulle)) $display_info_bulle = "";
                if ($display_info_bulle == 1)
                   $d[$weekday][$slot]["who"] = get_vocab("created_by").$row_user[0]." ".$row_user[1];
                else if ($display_info_bulle == 2)
                    $d[$weekday][$slot]["who"] = $row[7];
                else
                    $d[$weekday][$slot]["who"] = "";
                $d[$weekday][$slot]["statut"] = $row[6];
                if ((isset($display_full_description)) and ($display_full_description==1))
                      $d[$weekday][$slot]["description"] = $row[7];
                $d[$weekday][$slot]["option_reser"] = $row[8];

            }
        }
        # Step to next time period and slot:
        $t += $resolution;
        $slot++;

        if ($slot > $last_slot)
        {
            # Skip to first slot of next day:
            $weekday++;
            $slot = $first_slot;
            $t = $weekday * 86400 + $am7;
        }
    } while ($t < $end_t);
}
if ($debug_flag)
{
    echo "<p>DEBUG:<p><pre>\n";
    if (gettype($d) == "array")
    while (list($w_k, $w_v) = each($d))
        while (list($t_k, $t_v) = each($w_v))
            while (list($k_k, $k_v) = each($t_v))
                echo "d[$w_k][$t_k][$k_k] = '$k_v'\n";
    else echo "d is not an array!\n";
    echo "</pre><p>\n";
}

#This is where we start displaying stuff
echo "<table class='table table-responsive text-center' border=1 width=\"100%\">";

// Affichage de la premi�re ligne contenant le nom des jours (lundi, mardi, ...) et les dates ("10 juil", "11 juil", ...)
echo "<tr>\n<th class='text-center' width=\"1%\">&nbsp;</th>\n"; // Premi�re cellule vide
// Les cellules "jours de semaine"
switch ($dateformat) {
    case "en":
    $dformat = "%A<br>%b %d";
    break;
    case "fr":
    $dformat = "%A<br>%d %b";
    break;
}
$k=$day_week;
$num_week_day = $weekstarts; // Pour le calcul des jours � afficher
for ($t = $week_start; $t <= $week_end; $t += 86400) {
    if ($display_day[$num_week_day] == 1) // on n'affiche pas tous les jours de la semaine
        echo "<th class='text-center' width=\"14%\">" . utf8_strftime($dformat, $t) . "</th>\n";
    if (!isset($correct_heure_ete_hiver) or ($correct_heure_ete_hiver == 1)) {
        $num_day = strftime("%d", $t);
        // Si le dernier dimanche d'octobre est dans la semaine, on avance d'une heure
        if  (heure_ete_hiver("hiver",$year,0) == mktime(0,0,0,$month,$num_day,$year))
            $t +=3600;
    }
    $k++;
    $num_week_day++;// Pour le calcul des jours � afficher
    $num_week_day = $num_week_day % 7;// Pour le calcul des jours � afficher
}
echo "<th class='text-center' width=\"1%\">&nbsp;</th>\n</tr>\n"; // Derni�re cellule vide
// Fin de l'affichage de la premi�re ligne

// Affichage de la deuxi�me ligne du tableau contenant l'intitul� "Journ�e" avec lien vers day.php
echo "<tr>\n";
tdcell("cell_hours");
if ($enable_periods=='y')
    echo get_vocab("period");
else
    echo get_vocab("time");
echo "</td>\n";
$num_week_day = $weekstarts;// Pour le calcul des jours � afficher
for ($t = $week_start; $t <= $week_end; $t += 86400)// Pour le calcul des jours � afficher
{
    if ($display_day[$num_week_day] == 1) {
    tdcell("cell_hours");
    $num_day = strftime("%d", $t);
    if ($_GET['pview'] != 1)
    {
        echo "<a title=\"".htmlspecialchars(get_vocab("see_all_the_rooms_for_the_day"))."\" href=\"day.php?year=$year&amp;month=$month&amp;day=$num_day&amp;area=$area\">".get_vocab("allday")."</a>";
    }
    echo "</td>\n";
    }
    if (!isset($correct_heure_ete_hiver) or ($correct_heure_ete_hiver == 1)) {
        // Si le dernier dimanche d'octobre est dans la semaine, on avance d'une heure
        if  (heure_ete_hiver("hiver",$year,0) == mktime(0,0,0,$month,$num_day,$year))
            $t +=3600;
        if ((date("H",$t) == "13") or (date("H",$t) == "02"))
            $t -=3600;
    }
    $num_week_day++;// Pour le calcul des jours � afficher
    $num_week_day = $num_week_day % 7;// Pour le calcul des jours � afficher

}
tdcell("cell_hours");
if ($enable_periods=='y')
    echo get_vocab("period");
else
    echo get_vocab("time");
echo "</td>\n</tr>\n";
// Fin affichage de la deuxi�me ligne du tableau contenant l'intitul� "Journ�e" avec lien vers day.php


// D�but affichage des lignes contenant les r�servation
// Premi�re boucle bas�e sur les cr�neaux de temps
// Deuxi�me boucle interne sur les jours de la semaine


# $t is the date/time for the first day of the week (Sunday, if $weekstarts=0).
# $wt is for the weekday in the inner loop.
$t = $am7;
$nb_case=1;
$semaine_changement_heure_ete = 'no';
$semaine_changement_heure_hiver = 'no';
for ($slot = $first_slot; $slot <= $last_slot; $slot++)
{
    # Show the time linked to the URL for highlighting that time:
    echo "<tr>";
    tdcell("cell_hours");
    if($enable_periods=='y'){
        $time_t = date("i", $t);
         $time_t_stripped = preg_replace( "/^0/", "", $time_t );
         echo $periods_name[$time_t_stripped] . "</td>\n";
    } else {
        echo date(hour_min_format(),$t) ."</td>\n";
    }
    $wt = $t;

    $empty_color = "empty_cell";

    # See note above: weekday==0 is day $weekstarts, not necessarily Sunday.
    $num_week_day = $weekstarts;// Pour le calcul des jours � afficher
    for ($weekday = 0; $weekday < 7; $weekday++)
    {
        # Three cases:
        # color:  id:   Slot is:   Color:    Link to:
        # -----   ----- --------   --------- -----------------------
        # unset   -     empty      white,red add new entry
        # set     unset used       by type   none (unlabelled slot)
        # set     set   used       by type   view entry

        $wday = date("d", $wt);
        $wmonth = date("m", $wt);
        $wyear = date("Y", $wt);
        $hour = date("H",$wt);
        $minute  = date("i",$wt);

        
        
        
        if ($display_day[$num_week_day] == 1) {
		// Gestion du passage � l'heure d'�t�
		if (!isset($correct_heure_ete_hiver) or ($correct_heure_ete_hiver == 1)) {
            $temp =   mktime(0,0,0,$wmonth,$wday,$wyear);
            // On regarde s'il s'agit du dernier dimanche de mars
            if  (heure_ete_hiver("ete",$wyear,0) == $temp) {
			
                $semaine_changement_heure_ete = 'yes';
                $temp2 =   mktime($hour,0,0,$wmonth,$wday,$wyear);
                // 2 h du matin
                if  (heure_ete_hiver("ete", $wyear,2) < $temp2) {
                    $hour = date("H",$wt-3600);
                    $decale_slot = 1;
                    $insere_case = 'n';
                }
            // On regarde s'il s'agit du dernier dimanche d'octobre
            } else if  (heure_ete_hiver("hiver",$wyear,0) == $temp) {
                $semaine_changement_heure_hiver = 'yes';
                $temp2 =   mktime($hour,0,0,$wmonth,$wday,$wyear);
                // 2 h du matin
                if  (heure_ete_hiver("hiver", $wyear,2) < $temp2) {
                    
                    $hour = date("H",$wt+3600);
                    $decale_slot = -1;
                    $insere_case = 'n';
                } 
            } else {
                $decale_slot = 0;
                $insere_case = 'n';
            }
        }
		// Fin gestion du passage � l'heure d'�t�
		if(!isset($d[$weekday][$slot-$decale_slot*$nb_case]["color"])) // il s'agit d'un cr�neau libre
        {
		    $date_booking = mktime($hour, $minute, 0, $wmonth, $wday, $wyear);
            if ($this_statut_room == "0") tdcell("avertissement"); else tdcell($empty_color);
            if (est_hors_reservation(mktime(0,0,0,$wmonth,$wday,$wyear)))
                echo "<center><img src=\"img_grr/stop.png\" border=\"0\" alt=\"".get_vocab("reservation_impossible")."\"  title=\"".get_vocab("reservation_impossible")."\" width=\"16\" height=\"16\" /></center>";
            else

            if ((authGetUserLevel(getUserName(),-1) > 1)
			and (UserRoomMaxBooking(getUserName(), $room, 1) != 0)
            and verif_booking_date(getUserName(), -1,$room, $date_booking, $date_now, $enable_periods)
            and verif_delais_max_resa_room(getUserName(), $room, $date_booking)
            and verif_delais_min_resa_room(getUserName(), $room, $date_booking)
			and UserAreaGroup(getUserName(), $area)
			and (($this_statut_room == "1") or
              (($this_statut_room == "0") and (authGetUserLevel(getUserName(),-1) > 2) ))) {
                echo "<center>";
                if ($enable_periods=='y') {
                		if ((authGetUserLevel(getUserName(),$room,'room') >= 3) OR (authGetUserLevel(getUserName(),$area,'area') >= 4)) {
                    echo "<a href=\"edit_entry.php?room=$room&amp;area=$area"
                        . "&amp;period=$time_t_stripped&amp;year=$wyear&amp;month=$wmonth"
                        . "&amp;day=$wday&amp;page=week\" title=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\"><img src=img_grr/new.png border=0>";
                    echo "</a>";
                    } else {
                     echo "<a href=\"edit_entry_user.php?room=$room&amp;area=$area"
                        . "&amp;period=$time_t_stripped&amp;year=$wyear&amp;month=$wmonth"
                        . "&amp;day=$wday&amp;page=week\" title=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\"><img src=img_grr/new.png border=0>";
                    echo "</a>"; }
                } else {
                		if ((authGetUserLevel(getUserName(),$room,'room') >= 3) OR (authGetUserLevel(getUserName(),$area,'area') >= 4)) {
                    echo "<a href=\"edit_entry.php?room=$room&amp;area=$area"
                    . "&amp;hour=$hour&amp;minute=$minute&amp;year=$wyear&amp;month=$wmonth"
                    . "&amp;day=$wday&amp;page=week\" title=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\"><img src=img_grr/new.png border=0>";
                    echo "</a>";
                    } else {
                    echo "<a href=\"edit_entry_user.php?room=$room&amp;area=$area"
                    . "&amp;hour=$hour&amp;minute=$minute&amp;year=$wyear&amp;month=$wmonth"
                    . "&amp;day=$wday&amp;page=week\" title=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\"><img src=img_grr/new.png border=0>";
                    echo "</a>"; }
                }
                echo "</center>";
            } else {
                echo "&nbsp;";
            }

        } else {
            tdcell($d[$weekday][$slot-$decale_slot*$nb_case]["color"]);
            // si la ressource est "occup�e, on l'affiche
            if ((isset($d[$weekday][$slot-$decale_slot*$nb_case]["statut"])) and ($d[$weekday][$slot-$decale_slot*$nb_case]["statut"]=='y')) echo "&nbsp;<img src=\"img_grr/buzy.png\" alt=\"".get_vocab("reservation_en_cours")."\" title=\"".get_vocab("reservation_en_cours")."\" width=\"20\" height=\"20\" border=\"0\" />&nbsp;\n";
            // si la r�servation est � confirmer, on le signale
            if (($this_delais_option_reservation > 0) and (isset($d[$weekday][$slot-$decale_slot*$nb_case]["option_reser"])) and ($d[$weekday][$slot-$decale_slot*$nb_case]["option_reser"]!=-1)) echo "&nbsp;<img src=\"img_grr/small_flag.png\" alt=\"".get_vocab("reservation_a_confirmer_au_plus_tard_le")."\" title=\"".get_vocab("reservation_a_confirmer_au_plus_tard_le")."&nbsp;".time_date_string_jma($d[$weekday][$slot-$decale_slot*$nb_case]["option_reser"],$dformat)."\" width=\"20\" height=\"20\" border=\"0\" />&nbsp;\n";

            if (!isset($d[$weekday][$slot-$decale_slot*$nb_case]["id"])) {
                echo "&nbsp;\"&nbsp;";
            } else {
                if (($this_statut_room == "1") or
                (($this_statut_room == "0") and (authGetUserLevel(getUserName(),-1) > 2) ))

                {
                    echo " <a title=\"".htmlspecialchars($d[$weekday][$slot-$decale_slot*$nb_case]["who"])."\"  href=\"view_entry.php?id=" . $d[$weekday][$slot-$decale_slot*$nb_case]["id"]
                    . "&amp;area=$area&amp;day=$wday&amp;month=$wmonth&amp;year=$wyear&amp;page=week\">"
                    . htmlspecialchars($d[$weekday][$slot-$decale_slot*$nb_case]["data"]) . "</a>";
                } else {
                    echo htmlspecialchars($d[$weekday][$slot-$decale_slot*$nb_case]["data"]);

                }
                if ((isset($display_full_description)) and ($display_full_description==1) and ($d[$weekday][$slot-$decale_slot*$nb_case]["description"]!= ""))
                    echo "<br><i>".$d[$weekday][$slot-$decale_slot*$nb_case]["description"]."</i>";

            }
        }
        echo "</td>\n";
        }
        $wt += 86400;
        $num_week_day++;// Pour le calcul des jours � afficher
        $num_week_day = $num_week_day % 7;// Pour le calcul des jours � afficher

    }
    // r�p�tition de la premi�re colonne
    tdcell("cell_hours");
    if($enable_periods=='y'){
        $time_t = date("i", $t);
         $time_t_stripped = preg_replace( "/^0/", "", $time_t );
         echo $periods_name[$time_t_stripped] . "</td>\n";
    } else {
        echo date(hour_min_format(),$t) . "</td>\n";
    }
    echo "</tr>\n";
    $t += $resolution;
}

// r�p�tition de la premi�re ligne
echo "<tr>\n<th>&nbsp;</th>\n";
$num_week_day = $weekstarts;
for ($t = $week_start; $t <= $week_end; $t += 86400)
{
    if ($display_day[$num_week_day] == 1)
        echo "<th class='text-center'>" . strftime($dformat, $t) ."</th>\n";
    $k++;
    if (!isset($correct_heure_ete_hiver) or ($correct_heure_ete_hiver == 1)) {
        $num_day = strftime("%d", $t);
        // Si le dernier dimanche d'octobre est dans la semaine, on avance d'une heure
        if  (heure_ete_hiver("hiver",$year,0) == mktime(0,0,0,$month,$num_day,$year))
            $t +=3600;
        if ((date("H",$t) == "13") or (date("H",$t) == "02"))
            $t -=3600;
    }
    $num_week_day++;
    $num_week_day = $num_week_day % 7;
}
echo "<th>&nbsp;</th>\n</tr>\n";
// Fin R�p�tition de la premi�re ligne


echo "</table>";

//show_colour_key($area);

include "include/trailer.inc.php";
?>