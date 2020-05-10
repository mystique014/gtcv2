<?php
#########################################################################
#                            month_all2.php                             #
#                                                                       #
#            Interface d'accueil avec affichage par mois                #
#             des r�servation de toutes les ressources d'un domaine     #
#            Derni�re modification : 16/09/2006                         #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 * D'apr�s http://mrbs.sourceforge.net/
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

// Param�tres langage
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
if ($type_session == "with_session") $_SESSION['type_month_all'] = "month_all2";

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

# 3-value compare: Returns result of compare as "< " "= " or "> ".
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

#Draw the three month calendars
    $cal = isset($_GET["cal"]) ? $_GET["cal"] : NULL;
	if ($cal == 1)
	{
	echo'<div class="row">'.PHP_EOL;
	echo'<div class="col-md-12 center">'.PHP_EOL;
	echo "<table width=\"100%\" cellspacing=1 border=0><tr>\n<td>";
    minicals($year, $month, $day, $area, $room, 'month_all2');
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
    $room = grr_sql_query1("select min(id) from ".$_COOKIE["table_prefix"]."_room where area_id=$area");
# Note $room will be -1 if there are no rooms; this is checked for below.

// R�cup�ration des donn�es concernant l'affichage du planning du domaine
get_planning_area_values($area);

echo'<div class="container-fluid">'.PHP_EOL;
echo'<div class="row">'.PHP_EOL;
echo'<div class="col-md-12 center">'.PHP_EOL;
$v= mktime(0,0,0,$month,$day,$year);
$yea = date("Y",$v);
$mm = date("m",$v);
$dd = date("d",$v);

if ($cal == 1)
{
echo "</td><td align='center'><a href=\"month_all2.php?year=$yea&amp;month=$mm&amp;day=$dd&amp;area=$area&amp;room=$room&amp;cal=0\">Cacher le calendrier</a></td></tr></table>\n";
} else {
echo "</td><td align='center'><a href=\"month_all2.php?year=$yea&amp;month=$mm&amp;day=$dd&amp;area=$area&amp;room=$room&amp;cal=1\">Afficher le calendrier</a></td></tr></table>\n";
} 
echo'</div>'.PHP_EOL;
echo'</div>'.PHP_EOL;
echo'</div>'.PHP_EOL;  

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
        echo make_area_select_html('month_all2.php', $area, $year, $month, $day, $session_login); # from functions.inc.php
		echo'</div>'.PHP_EOL;
		echo'<div class="col-xs-3 left">'.PHP_EOL;
        echo make_room_select_html('month', $area, "", $year, $month, $day);
		echo'</div>'.PHP_EOL;
    } else {
        //echo "<table cellspacing=15><tr><td>";
		echo'<div class="row">'.PHP_EOL;
		echo'<div class="col-xs-6 left">'.PHP_EOL; 
        echo make_area_list_html('month_all2.php', $area, $year, $month, $day, $session_login); # from functions.inc.php
        #Montre toutes les rooms du domaine affich�
        //echo "</td><td>";
        make_room_list_html('month.php', $area, "", $year, $month, $day);
        //echo "</td></tr></table>";
		echo'</div>'.PHP_EOL;
		echo'</div>'.PHP_EOL;

    }
    //echo "</td>\n";
	  //echo "</td>\n";
	echo'</div>'.PHP_EOL;

}

$this_area_name = grr_sql_query1("select area_name from ".$_COOKIE["table_prefix"]."_area where id=$area");
$this_room_name = grr_sql_query1("select room_name from ".$_COOKIE["table_prefix"]."_room where id=$room");
$this_room_name_des = grr_sql_query1("select description from ".$_COOKIE["table_prefix"]."_room where id=$room");

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
  . " - ".ucfirst($this_area_name)." - ".get_vocab("all_areas")." <a href=\"month_all.php?year=$year&amp;month=$month&amp;area=$area\"><img src=\"img_grr/change_view.png\" alt=\"".get_vocab("change_view")."\" title=\"".get_vocab("change_view")."\" border=\"0\" /></a></h2>\n";


  
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
      <a href=\"month_all2.php?year=$yy&amp;month=$ym&amp;area=$area&amp;room=$room\">
      &lt;&lt; ".get_vocab("monthbefore")."</a></td>
      <td>&nbsp;</td>
      <td align=right><a href=\"month_all2.php?year=$ty&amp;month=$tm&amp;area=$area&amp;room=$room\">
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
# row[6] = statut
# row[7] = Description compl�te



$sql = "SELECT start_time, end_time,".$_COOKIE["table_prefix"]."_entry.id, name, create_by, room_name, statut_entry, ".$_COOKIE["table_prefix"]."_entry.description, ".$_COOKIE["table_prefix"]."_entry.option_reservation, ".$_COOKIE["table_prefix"]."_room.delais_option_reservation, type
   FROM ".$_COOKIE["table_prefix"]."_entry inner join ".$_COOKIE["table_prefix"]."_room on ".$_COOKIE["table_prefix"]."_entry.room_id=".$_COOKIE["table_prefix"]."_room.id
   WHERE (start_time <= $month_end AND end_time > $month_start and area_id='".$area."')
   ORDER by start_time, end_time, ".$_COOKIE["table_prefix"]."_room.room_name";

# Build an array of information about each day in the month.
# The information is stored as:
#  d[monthday]["id"][] = ID of each entry, for linking.
#  d[monthday]["data"][] = "start-stop" times of each entry.

$res = grr_sql_query($sql);
if (! $res) echo grr_sql_error();
else {
if (grr_sql_count($res) == 0) {
    echo "<center><h2>".get_vocab("nothing_found")."</h2></center></body></html>";
    die();
}

for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
{
    $sql_creator = "SELECT prenom, nom FROM ".$_COOKIE["table_prefix"]."_utilisateurs WHERE login = '$row[4]'";
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
        $temp = "";
        if ($display_info_bulle == 1)
            $temp = get_vocab("created_by").$row_user[0]." ".$row_user[1];
        else if ($display_info_bulle == 2)
            $temp = $row[7];

        if ($temp != "") $temp = " - ".$temp;
        $d[$day_num]["who1"][] = $row[3];
        $d[$day_num]["room"][]=$row[5] ;
        $d[$day_num]["res"][] = $row[6];
        $d[$day_num]["color"][] = $row[10];
        if ($row[9] > 0)
            $d[$day_num]["option_reser"][] = $row[8];
        else
            $d[$day_num]["option_reser"][] = -1;


        $midnight_tonight = $midnight + 86400;

        # Describe the start and end time, accounting for "all day"
        # and for entries starting before/ending after today.
        # There are 9 cases, for start time < = or > midnight this morning,
        # and end time < = or > midnight tonight.
        # Use ~ (not -) to separate the start and stop times, because MSIE
        # will incorrectly line break after a -.
        $all_day2 = str_replace("&nbsp;", " ", $all_day);
        if ($enable_periods == 'y') {
            $start_str = str_replace("&nbsp;", " ", period_time_string($row[0]));
            $end_str   = str_replace("&nbsp;", " ", period_time_string($row[1], -1));
            switch (cmp3($row[0], $midnight) . cmp3($row[1], $midnight_tonight))
            {
            case "> < ":         # Starts after midnight, ends before midnight
            case "= < ":         # Starts at midnight, ends before midnight
                    if ($start_str == $end_str)
                        $d[$day_num]["data"][] = $start_str." - ".$row[3].$temp;
                    else
                        $d[$day_num]["data"][] = $start_str . "~" . $end_str." - ".$row[3].$temp;
                    break;
            case "> = ":         # Starts after midnight, ends at midnight
                    $d[$day_num]["data"][] = $start_str . "~24:00"." - ".$row[3].$temp;
                    break;
            case "> > ":         # Starts after midnight, continues tomorrow
                    $d[$day_num]["data"][] = $start_str . "~====>"." - ".$row[3].$temp;
                    break;
            case "= = ":         # Starts at midnight, ends at midnight
                    $d[$day_num]["data"][] = $all_day2.$temp;
                    break;
            case "= > ":         # Starts at midnight, continues tomorrow
                    $d[$day_num]["data"][] = $all_day2 . "====>"." - ".$row[3].$temp;
                    break;
            case "< < ":         # Starts before today, ends before midnight
                    $d[$day_num]["data"][] = "<====~" . $end_str." - ".$row[3].$temp;
                    break;
            case "< = ":         # Starts before today, ends at midnight
                    $d[$day_num]["data"][] = "<====" . $all_day2." - ".$row[3].$temp;
                    break;
            case "< > ":         # Starts before today, continues tomorrow
                    $d[$day_num]["data"][] = "<====" . $all_day2 . "====>"." - ".$row[3].$temp;
                    break;
            }
        } else {
          switch (cmp3($row[0], $midnight) . cmp3($row[1], $midnight_tonight))
          {
            case "> < ":         # Starts after midnight, ends before midnight
            case "= < ":         # Starts at midnight, ends before midnight
                $d[$day_num]["data"][] = date(hour_min_format(), $row[0]) . "~" . date(hour_min_format(), $row[1])." - ".$row[3].$temp;
                break;
            case "> = ":         # Starts after midnight, ends at midnight
                $d[$day_num]["data"][] = date(hour_min_format(), $row[0]) . "~24:00"." - ".$row[3].$temp;
                break;
            case "> > ":         # Starts after midnight, continues tomorrow
                $d[$day_num]["data"][] = date(hour_min_format(), $row[0]) . "~====>"." - ".$row[3].$temp;
                break;
            case "= = ":         # Starts at midnight, ends at midnight
                $d[$day_num]["data"][] = $all_day2.$temp;
                break;
            case "= > ":         # Starts at midnight, continues tomorrow
                $d[$day_num]["data"][] = $all_day2 . "====>"." - ".$row[3].$temp;
                break;
            case "< < ":         # Starts before today, ends before midnight
                $d[$day_num]["data"][] = "<====~" . date(hour_min_format(), $row[1])." - ".$row[3].$temp;
                break;
            case "< = ":         # Starts before today, ends at midnight
                $d[$day_num]["data"][] = "<====" . $all_day2." - ".$row[3].$temp;
                break;
            case "< > ":         # Starts before today, continues tomorrow
                $d[$day_num]["data"][] = "<====" . $all_day2 . "====>"." - ".$row[3].$temp;
                break;
          }
        }

        # Only if end time > midnight does the loop continue for the next day.
        if ($row[1] <= $midnight_tonight) break;
        $day_num++;
        $t = $midnight = $midnight_tonight;
    }
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
$weekcol=0;
echo "<table class='table text-center' border=1 width=\"100%\">\n";
# Weekday name header row:
/*echo "<tr><th width=10px>&nbsp;</th>";
for ($weekcol = 0; $weekcol < 5; $weekcol++)
{
    echo "<th colspan=7 width=\"170px\">sem ".date("W",mktime(0,0,0,$month,$day,$year))."</th>";
    //echo "<th width=\"14%\">" . day_name(($weekcol + $weekstarts)%7) . "</th>";
    $day = $day+7;
}
echo "</tr>\n";*/


$sql = "select room_name, capacity, id, description from ".$_COOKIE["table_prefix"]."_room where area_id=$area order by order_display,room_name";
$res = grr_sql_query($sql);

// D�but affichage de la premi�re ligne
echo "<tr><th></th>\n";
$t2=gmmktime(0,0,0,$month,1,$year);
for ($k = 0; $k<$days_in_month; $k++) {
    $cday = date("j", $t2);
    $cweek = date("w", $t2);
    $name_day = ucfirst(utf8_strftime("%a %d", $t2));
    $t2 += 86400;
    // On inscrit le num�ro du mois dans la deuxi�me ligne
    if ($display_day[$cweek]==1)
        echo "<td valign=top height=50 class=\"cell_month\"><div class=\"monthday\"><a title=\"".htmlspecialchars(get_vocab("see_all_the_rooms_for_the_day"))."\"   href=\"day.php?year=$year&amp;month=$month&amp;day=$cday&amp;area=$area\">$name_day</a></div>\n";
}
echo "</tr>";
// Fin affichage de la premi�re ligne

$li=0;
for ($ir = 0; ($row = grr_sql_row($res, $ir)); $ir++)
{
    echo "<tr><th>" . htmlspecialchars($row[0]) ."</th>\n";
    $li++;
    $t2=gmmktime(0,0,0,$month    ,1,$year);
    for ($k = 0; $k<$days_in_month; $k++)
      {
        $cday = date("j", $t2);
        $cweek = date("w", $t2);
        $t2 += 86400;
       if ($display_day[$cweek]==1) { // D�but condition "on n'affiche pas tous les jours de la semaine"
        echo "<td height=50 valign=top class=\"cell_month\">&nbsp;";
    if (est_hors_reservation(mktime(0,0,0,$month,$cday,$year)))
            echo "<center><img src=\"img_grr/stop.png\" border=\"0\" alt=\"".get_vocab("reservation_impossible")."\"  title=\"".get_vocab("reservation_impossible")."\" width=\"16\" height=\"16\" /></center>";


        # Anything to display for this day?
        if (isset($d[$cday]["id"][0])) {

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
        for ($i = 0; $i < $n; $i++) {

        if ($d[$cday]["room"][$i]==$row[0]) {
                    #if ($i > 0 && $i % 2 == 0) echo "<br>"; else echo " ";
                    echo "\n<br><table width='100%'>";
                    tdcell($d[$cday]["color"][$i]);


           if ($d[$cday]["res"][$i]=='y') echo "&nbsp;<img src=\"img_grr/buzy.png\" alt=\"".get_vocab("reservation_en_cours")."\" title=\"".get_vocab("reservation_en_cours")."\" width=\"20\" height=\"20\" border=\"0\" />&nbsp;\n";
           // si la r�servation est � confirmer, on le signale
           if ((isset($d[$cday]["option_reser"][$i])) and ($d[$cday]["option_reser"][$i]!=-1)) echo "&nbsp;<img src=\"img_grr/small_flag.png\" alt=\"".get_vocab("reservation_a_confirmer_au_plus_tard_le")."\" title=\"".get_vocab("reservation_a_confirmer_au_plus_tard_le")."&nbsp;".time_date_string_jma($d[$cday]["option_reser"][$i],$dformat)."\" width=\"20\" height=\"20\" border=\"0\" />&nbsp;\n";


            echo "</font><font size=1pt><a title=\"".htmlspecialchars($d[$cday]["data"][$i])."\" href=\"view_entry.php?id=" . $d[$cday]["id"][$i]."&amp;page=month\">"
                    .htmlspecialchars($d[$cday]["who1"][$i]{0})
                    . "</font></font>"
                    . "</a></td></table>";
                }
    }


        }

        echo "</font>";
    }
    echo "</td>\n";
    } // fin condition "on n'affiche pas tous les jours de la semaine"
//    if (++$weekcol == 7) $weekcol = 0;

    }
}

/*# Skip from end of month to end of week:
if ($weekcol > 0) for (; $weekcol < 7; $weekcol++)
{
    echo "<td class=\"cell_month_o\" height=100>&nbsp;</td>\n";
}*/
echo "</tr></table>\n";
//show_colour_key($area);
include "include/trailer.inc.php";
?>