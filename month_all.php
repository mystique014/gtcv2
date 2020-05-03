<?php
#########################################################################
#                            month_all.php                              #
#                                                                       #
#            Interface d'accueil avec affichage par mois                #
#             des réservation de toutes les ressources d'un domaine     #
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

// Paramètres langage
include "include/language.inc.php";

// On affiche le lien "format imprimable" en bas de la page
$affiche_pview = '1';
if (!isset($_GET['pview'])) $_GET['pview'] = 0; else $_GET['pview'] = 1;

# Default parameters:
if (empty($debug_flag)) $debug_flag = 0;
if (empty($month) || empty($year) || !checkdate($month, 1, $year))
{
    $month = date("m");
    $year  = date("Y");
}
$day = 1;

if (($authentification_obli==0) and (!isset($_SESSION['login']))) {
    $session_login = '';
    $session_statut = '';
    $type_session = "no_session";
} else {
    $session_login = $_SESSION['login'];
    $session_statut = $_SESSION['statut'];
    $type_session = "with_session";
}
$back = "";
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];

if ($type_session == "with_session") $_SESSION['type_month_all'] = "month_all";

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

# 3-value compare: Returns result of compare as "< " "= " or "> ".
function cmp3($a, $b)
{
    if ($a < $b) return "< ";
    if ($a == $b) return "= ";
    return "> ";
}

// On vérifie une fois par jour si le délai de confirmation des réservations est dépassé
// Si oui, les réservations concernées sont supprimées et un mail automatique est envoyé.
if ((!isset($verif_reservation_auto)) or ($verif_reservation_auto == 0))
    verify_confirm_reservation();

# print the page header
print_header($day, $month, $year, $area, $type_session);

#Draw the three month calendars
    $cal = isset($_GET["cal"]) ? $_GET["cal"] : NULL;
	if ($cal == 1)
	{
	echo'<div class="row">'.PHP_EOL;
	echo'<div class="col-md-12 center">'.PHP_EOL;
    echo "<table width=\"100%\" cellspacing=1 border=0><tr>\n<td>";
	minicals($year, $month, $day, $area, -1, 'day');
	echo "</table><table width=\"100%\" cellspacing=1 border=0>\n";

	echo'</div>'.PHP_EOL;
	echo'</div>'.PHP_EOL;
	}
	
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
# Note $room will be -1 if there are no rooms; this is checked for below.

// Récupération des données concernant l'affichage du planning du domaine
get_planning_area_values($area);

# Month view start time. This ignores morningstarts/eveningends because it
# doesn't make sense to not show all entries for the day, and it messes
# things up when entries cross midnight.
$month_start = mktime(0, 0, 0, $month, 1, $year);

# What column the month starts in: 0 means $weekstarts weekday.
$weekday_start = (date("w", $month_start) - $weekstarts + 7) % 7;

$days_in_month = date("t", $month_start);

$month_end = mktime(23, 59, 59, $month, $days_in_month, $year);

if ($enable_periods=='y') {
    $resolution = 60;
    $morningstarts = 12;
    $eveningends = 12;
    $eveningends_minutes = count($periods_name)-1;
}

$this_area_name = "";
$this_room_name = "";

echo'<div class="container-fluid">'.PHP_EOL;
echo'<div class="row">'.PHP_EOL;
echo'<div class="col-md-12 center">'.PHP_EOL;
$v= mktime(0,0,0,$month,$day,$year);
$yea = date("Y",$v);
$mm = date("m",$v);
$dd = date("d",$v);

if ($cal == 1)
{
echo "</td><td align='center'><a href=\"month_all.php?year=$yea&amp;month=$mm&amp;day=$dd&amp;area=$area&amp;room=$room&amp;cal=0\">Cacher le calendrier</a></td></tr></table>\n";
} else {
echo "</td><td align='center'><a href=\"month_all.php?year=$yea&amp;month=$mm&amp;day=$dd&amp;area=$area&amp;room=$room&amp;cal=1\">Afficher le calendrier</a></td></tr></table>\n";
}   
echo'</div>'.PHP_EOL;
echo'</div>'.PHP_EOL;
echo'</div>'.PHP_EOL;

// Si format imprimable ($_GET['pview'] = 1), on n'affiche pas cette partie
if ($_GET['pview'] != 1) {
    #Table avec areas, rooms, minicals.
    //echo "<table width=\"100%\" cellspacing=\"15\" border=\"0\"><tr><td>";
	echo'<div class="container-fluid">'.PHP_EOL;
    if (isset($_SESSION['default_list_type']) or ($authentification_obli==1)) {
        $area_list_format = $_SESSION['default_list_type'];
    } else {
        $area_list_format = getSettingValue("area_list_format");
    }

    # show either a select box or the normal html list
    if ($area_list_format != "list") {
		echo'<div class="row">'.PHP_EOL;
		echo'<div class="col-xs-6 left">'.PHP_EOL;
        echo make_area_select_html('month_all.php', $area, $year, $month, $day, $session_login); # from functions.inc.php
		echo'</div>'.PHP_EOL;
		echo'<div class="col-xs-3 left">'.PHP_EOL;
        echo make_room_select_html('month', $area, "", $year, $month, $day);
		echo'</div>'.PHP_EOL;
    } else {
        //echo "<table cellspacing=15><tr><td>";
		echo'<div class="row">'.PHP_EOL;
		echo'<div class="col-xs-6 left">'.PHP_EOL; 
        echo make_area_list_html('month_all.php', $area, $year, $month, $day, $session_login); # from functions.inc.php
        #Montre toutes les rooms du domaine affiché
        //echo "</td><td>";
        make_room_list_html('month.php', $area, "", $year, $month, $day);
        //echo "</td></tr></table>";
		echo'</div>'.PHP_EOL;
		echo'</div>'.PHP_EOL;
    }
    //echo "</td>\n";
	echo'</div>'.PHP_EOL;
}

$this_area_name = grr_sql_query1("select area_name from grr_area where id=$area");
$this_room_name = grr_sql_query1("select room_name from grr_room where id=$room");
$this_room_name_des = grr_sql_query1("select description from grr_room where id=$room");

# Don't continue if this area has no rooms:
if ($room <= 0)
{
    echo "<h1>".get_vocab("no_rooms_for_area")."</h1>";
    include "include/trailer.inc.php";
    exit;
}

# Show Month, Year, Area, Room header:
if (($this_room_name_des) and ($this_room_name_des!="-1")) {
    $this_room_name_des = " (".$this_room_name_des.")";
} else {
    $this_room_name_des = "";
}

 echo "<td VALIGN=MIDDLE><h4 align=center>" . ucfirst(utf8_strftime("%B %Y", $month_start))
  . " - ".ucfirst($this_area_name)." - ".get_vocab("all_areas")." <a href=\"month_all2.php?year=$year&amp;month=$month&amp;area=$area\"><img src=\"img_grr/change_view.png\" alt=\"".get_vocab("change_view")."\" title=\"".get_vocab("change_view")."\" border=\"0\" /></a></h2>\n";



# Show Go to month before and after links
#y? are year and month of the previous month.
#t? are year and month of the next month.

$i= mktime(0,0,0,$month-1,1,$year);
$yy = date("Y",$i);
$ym = date("n",$i);

$i= mktime(0,0,0,$month+1,1,$year);
$ty = date("Y",$i);
$tm = date("n",$i);
// Si format imprimable ($_GET['pview'] = 1), on n'affiche pas cette partie
if ($_GET['pview'] != 1) {
    echo "<table width=\"100%\" border='0'><tr><td>
      <a href=\"month_all.php?year=$yy&amp;month=$ym&amp;area=$area&amp;room=$room\">
      &lt;&lt; ".get_vocab("monthbefore")."</a></td>
      <td>&nbsp;</td>
      <td align=right><a href=\"month_all.php?year=$ty&amp;month=$tm&amp;area=$area&amp;room=$room\">
      ".get_vocab("monthafter")." &gt;&gt;</a></td></tr></table>";
}

if ($debug_flag)
    echo "<p>DEBUG: month=$month year=$year start=$weekday_start range=$month_start:$month_end\n";

# Used below: localized "all day" text but with non-breaking spaces:
$all_day = str_replace(" ", "&nbsp;", get_vocab("all_day"));

#Get all meetings for this month in the room that we care about
# row[0] = Start time
# row[1] = End time
# row[2] = Entry ID
# row[3] = Entry name (brief description)
# row[4] = creator of the booking
# row[5] = Nom de la ressource
# row[6] = Description complète
$sql = "SELECT start_time, end_time, grr_entry.id, name, create_by, room_name, grr_entry.description, type
   FROM grr_entry inner join grr_room on grr_entry.room_id=grr_room.id
   WHERE (start_time <= $month_end AND end_time > $month_start and area_id='".$area."')
   ORDER by start_time, end_time, grr_room.room_name";

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

    if ($debug_flag)
        echo "<br>DEBUG: result $i, id $row[2], starts $row[0], ends $row[1]\n";

    # Fill in data for each day during the month that this meeting covers.
    # Note: int casts on database rows for min and max is needed for PHP3.
    $t = max((int)$row[0], $month_start);
    $end_t = min((int)$row[1], $month_end);
    $day_num = date("j", $t);
    if ($enable_periods == 'y')
        $midnight = mktime(12,0,0,$month,$day_num,$year);
    else
        $midnight = mktime(0, 0, 0, $month, $day_num, $year);
    while ($t < $end_t)
    {
        if ($debug_flag) echo "<br>DEBUG: Entry $row[2] day $day_num\n";
        $d[$day_num]["id"][] = $row[2];
        // Info-bulle
        if (!isset($display_info_bulle)) $display_info_bulle = "";
        if ($display_info_bulle == 1)
            $d[$day_num]["who"][] = get_vocab("created_by").$row_user[0]." ".$row_user[1];
        else if ($display_info_bulle == 2)
            $d[$day_num]["who"][] = $row[6];
        else
            $d[$day_num]["who"][] = "";

        $d[$day_num]["who1"][] = $row[3];
        $d[$day_num]["room"][]=$row[5] ;
        $d[$day_num]["color"][] = $row[7];
        if ((isset($display_full_description)) and ($display_full_description==1))
            $d[$day_num]["description"][] = $row[6];

        $midnight_tonight = $midnight + 86400;

        # Describe the start and end time, accounting for "all day"
        # and for entries starting before/ending after today.
        # There are 9 cases, for start time < = or > midnight this morning,
        # and end time < = or > midnight tonight.
        # Use ~ (not -) to separate the start and stop times, because MSIE
        # will incorrectly line break after a -.
        if ($enable_periods == 'y') {
            $start_str = str_replace(" ", "&nbsp;", period_time_string($row[0]));
            $end_str   = str_replace(" ", "&nbsp;", period_time_string($row[1], -1));
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
                $d[$day_num]["data"][] = date(hour_min_format(), $row[0]) . "~====&gt;";
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
        $day_num++;
        $t = $midnight = $midnight_tonight;
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

// Début du tableau affichant le planning
echo "<table class='table text-center' border=1 width=\"100%\">\n";

// Début affichage première ligne (intitulé des jours)
echo "<tr>";
for ($weekcol = 0; $weekcol < 7; $weekcol++)
{
    $num_week_day = ($weekcol + $weekstarts)%7;
    if ($display_day[$num_week_day] == 1)  // on n'affiche pas tous les jours de la semaine
        echo "<th class='text-center' width=\"14%\">" . day_name($num_week_day) . "</th>\n";
}
echo "</tr>\n";
// Fin affichage première ligne (intitulé des jours)


// Début affichage des lignes affichant les réservations
echo "<tr>\n";

// On grise les cellules appartenant au mois précédent
for ($weekcol = 0; $weekcol < $weekday_start; $weekcol++)
{
    $num_week_day = ($weekcol + $weekstarts)%7;
    if ($display_day[$num_week_day] == 1)  // on n'affiche pas tous les jours de la semaine
        echo "<td class=\"cell_month_o\" height=100>&nbsp;</td>\n";
}

// Début Première boucle sur les jours du mois
for ($cday = 1; $cday <= $days_in_month; $cday++)
{
    $num_week_day = ($weekcol + $weekstarts)%7;
    if ($weekcol == 0) echo "</tr><tr>\n";
    if ($display_day[$num_week_day] == 1) {// début condition "on n'affiche pas tous les jours de la semaine"
    echo "<td valign=top height=100 class=\"cell_month\">";
    // On affiche les jours du mois dans le coin supérieur gauche de chaque cellule
    echo "<div class=\"monthday\"><a title=\"".htmlspecialchars(get_vocab("see_all_the_rooms_for_the_day"))."\"   href=\"day.php?year=$year&amp;month=$month&amp;day=$cday&amp;area=$area\">$cday</a></div>\n";
    if (est_hors_reservation(mktime(0,0,0,$month,$cday,$year)))
            echo "<center><img src=\"img_grr/stop.png\" border=\"0\" alt=\"".get_vocab("reservation_impossible")."\"  title=\"".get_vocab("reservation_impossible")."\" width=\"16\" height=\"16\" /></center>";


    // Des réservation à afficher pour ce jour ?
    if (isset($d[$cday]["id"][0]))
    {
        echo "<font size=-2>";
        $n = count($d[$cday]["id"]);
        # Show the start/stop times, 2 per line, linked to view_entry.
        # If there are 12 or fewer, show them, else show 11 and "...".
        for ($i = 0; $i < $n; $i++)
        {
            if ($i == 11 && $n > 12)
            {
                echo " ...\n";
                break;
            }
            echo "<b>";


            echo span_bgground($d[$cday]["color"][$i]);
            echo "". $d[$cday]["data"][$i]
                . "<br><i><b>"
                . htmlspecialchars($d[$cday]["room"][$i])
                . "<br></b></i>"
          . "<a title=\"".htmlspecialchars($d[$cday]["who"][$i])."\" href=\"view_entry.php?id=" . $d[$cday]["id"][$i]
          . "&amp;day=$cday&amp;month=$month&amp;year=$year&amp;page=month_all\">"
          . htmlspecialchars($d[$cday]["who1"][$i])
          . "</a></b>";
          if ((isset($display_full_description)) and ($display_full_description==1) and ($d[$cday]["description"][$i]!= ""))
              echo "<br><i>(".$d[$cday]["description"][$i].")</i>";
          echo "</span><br><br>";
        }
        echo "</font>";
    }
    echo "</td>\n";
    } // fin condition "on n'affiche pas tous les jours de la semaine"
    if (++$weekcol == 7) $weekcol = 0;
}
// Fin Première boucle sur les jours du mois

// On grise les cellules appartenant au mois suivant
if ($weekcol > 0) for (; $weekcol < 7; $weekcol++)
{
    $num_week_day = ($weekcol + $weekstarts)%7;
    if ($display_day[$num_week_day] == 1)  // on n'affiche pas tous les jours de la semaine
        echo "<td class=\"cell_month_o\" height=100>&nbsp;</td>\n";
}
echo "</tr></table>\n";
show_colour_key($area);
include "include/trailer.inc.php";
?>