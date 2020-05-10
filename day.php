<?php
#########################################################################
#                         day.php                                       #
#                                                                       #
#    Permet l'affichage de la page d'accueil lorsque l'on est en mode   #
#    d'affichage "jour".                                                #
#                                                                       #
#                  Dernière modification : 19/09/2008                   #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 * D'après http://mrbs.sourceforge.net/
 
 * Modification S Duchemin
 * Choix dans la réservation s'il s'agit d'un Utilisateur ou d'un administrateur
 * Choix dans la réservation s'il s'agit d'un admin de domaine ou ressource
 *
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

#Paramètres de connection
require_once("./include/settings.inc.php");

#Chargement des valeurs de la table settings
if (!loadSettings())
    die("Erreur chargement settings");

#Fonction relative à la session
require_once("./include/session.inc.php");
   #Si nous ne savons pas la date, nous devons la créer
if (!isset($day) or !isset($month) or !isset($year))
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
} else
{
    // Vérification des dates
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

    #Si la date n'est pas valide, ils faut la modifier (Si le nombre de jours est suppérieur au nombre de jours dans un mois)
    while (!checkdate($month, $day, $year))
        $day--;
}
$date_now = time();

// Resume session
if ((!grr_resumeSession())and ($authentification_obli==1)) {
    header("Location: ./logout.php?auto=1");
    die();
};

if (empty($area)) $area = get_default_area();

// Paramètres langage
include "include/language.inc.php";

// On affiche le lien "format imprimable" en bas de la page
$affiche_pview = '1';
if (!isset($_GET['pview'])) $_GET['pview'] = 0; else $_GET['pview'] = 1;

$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];

if (($authentification_obli==0) and (!isset($_SESSION['login']))) {
    $session_login = '';
    $session_statut = '';
    $type_session = "no_session";
} else {
    $session_login = $_SESSION['login'];
    $session_statut = $_SESSION['statut'];
    $type_session = "with_session";
}

// Récupération des données concernant l'affichage du planning du domaine
get_planning_area_values($area);

// Si aucun domaine n'est défini
if ($area == 0) {
   print_header($day, $month, $year, $area,$type_session);
   echo "<H2>".get_vocab("noareas")."</H1>";
   echo "<A HREF='admin_accueil.php'>".get_vocab("admin")."</A>\n
   </BODY>
   </HTML>";
   exit();
}
# print the header
print_header($day, $month, $year, $area, $type_session);
$cal = isset($_GET["cal"]) ? $_GET["cal"] : NULL;


		
	
		
	
	if ($cal == 1)
	{
	echo'<div class="row">'.PHP_EOL;
	echo'<div class="col-md-12">'.PHP_EOL;
	echo "<table width=\"100%\" cellspacing=1 border=0><tr>\n<td>";
	minicals($year, $month, $day, $area, -1, 'day');
	echo "</table><table width=\"100%\" cellspacing=1 border=0>\n";
	echo'</div>'.PHP_EOL;
	echo'</div>'.PHP_EOL;
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

if (check_begin_end_bookings($day, $month, $year))
{
    showNoBookings($day, $month, $year, $area,$back,$type_session);
    exit();
}

// On vérifie une fois par jour si le délai de confirmation des réservations est dépassé
// Si oui, les réservations concernées sont supprimées et un mail automatique est envoyé.
if ((!isset($verif_reservation_auto)) or ($verif_reservation_auto == 0))
    verify_confirm_reservation();


//Création d'une row pour le lien montrer/cacher le header
//echo'<div class="container-fluid">'.PHP_EOL;
echo'<div class="row">'.PHP_EOL;
echo'<div class="col-md-12 center">'.PHP_EOL;
$v= mktime(0,0,0,$month,$day,$year);
$yea = date("Y",$v);
$mm = date("m",$v);
$dd = date("d",$v);
	if ($cal == 1)
	{
	echo "<table width=\"100%\" border=0><tr><td align='center'><a href=\"day.php?year=$yea&amp;month=$mm&amp;day=$dd&amp;area=$area&amp;room=$room&amp;cal=0\">Cacher le calendrier</a></td></tr></table>\n";
	} 
	else 
	{
	echo "<table width=\"100%\" border=0><tr><td align='center'><a href=\"day.php?year=$yea&amp;month=$mm&amp;day=$dd&amp;area=$area&amp;room=$room&amp;cal=1\">Afficher le calendrier</a></td></tr></table>\n";
	}
echo'</div>'.PHP_EOL;
echo'</div>'.PHP_EOL;
?>
<script type="text/javascript" src="functions.js" language="javascript"></script>
<?php

// Si format imprimable ($_GET['pview'] = 1), on n'affiche pas cette partie
if ($_GET['pview'] != 1) {

// Si c'est un admin qui est connecté, on affiche le nombre de personnes actuellement connectées.
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
    // Vérification du numéro de version
    if (verif_version()) {
        echo "<script type=\"text/javascript\" language=\"javascript\">";
        echo " alert(\"".get_vocab("maj_bdd_not_update").get_vocab("please_go_to_admin_maj.php")."\")";
        echo "</script>";
    }
}

// Affichage d'un message pop-up
if (!($javascript_info_disabled)) {
    echo "<script type=\"text/javascript\" language=\"javascript\">";
    if (isset($_SESSION['displ_msg']))  echo " alert(\"".get_vocab("message_records")."\")";
    echo "</script>";
}
unset ($_SESSION['displ_msg']);

#Show all avaliable areas
# need to show either a select box or a normal html list,
echo'<div class="row">'.PHP_EOL;
echo'<div class="col-md-12">'.PHP_EOL;
if ($area_list_format != "list") {
    echo make_area_select_html('day.php', $area, $year, $month, $day, $session_login); # from functions.inc.php
} else {
    echo make_area_list_html('day.php', $area, $year, $month, $day, $session_login); # from functions.inc.php
}


echo "<table class='table' width=\"100%\" cellspacing=0 border=0><tr>\n";

if (isset($_SESSION['default_list_type']) or ($authentification_obli==1)) {
    $area_list_format = $_SESSION['default_list_type'];
} else {
    $area_list_format = getSettingValue("area_list_format");
}
echo'</div>'.PHP_EOL;
echo'</div>'.PHP_EOL;




// fin de la condition "Si format imprimable"
}

#y? are year, month and day of yesterday
#t? are year, month and day of tomorrow
$i= mktime(0,0,0,$month,$day-1,$year);
$yy = date("Y",$i);
$ym = date("m",$i);
$yd = date("d",$i);
$i= mktime(0,0,0,$month,$day+1,$year);
$ty = date("Y",$i);
$tm = date("m",$i);
$td = date("d",$i);

# Define the start and end of the day.
 $morningstarts_minutes = grr_sql_query1("select minute_morningstarts_area from ".$_COOKIE["table_prefix"]."_area where id=$area");
$am7=mktime($morningstarts,$morningstarts_minutes,0,$month,$day,$year);
$pm7=mktime($eveningends,$eveningends_minutes,0,$month,$day,$year);

#Show current date
$this_area_name = grr_sql_query1("select area_name from ".$_COOKIE["table_prefix"]."_area where id='".protect_data_sql($area)."'");
echo "<td VALIGN=MIDDLE><h4 align=center>" .$this_area_name." - ". ucfirst(utf8_strftime($dformat, $am7)) . " <br> ".get_vocab("all_areas")."</h4></td></tr></table>\n";



// Si format imprimable ($_GET['pview'] = 1), on n'affiche pas cette partie
if ($_GET['pview'] != 1) {
    #Show Go to day before and after links
    echo "<table width=\"100%\" border='0'><tr>\n<td align=left>\n<a href=\"day.php?year=$yy&amp;month=$ym&amp;day=$yd&amp;area=$area\">&lt;&lt; ".get_vocab('daybefore')."</a></td>\n<td align=center><a href=\"day.php?area=$area\">".get_vocab('gototoday')."</a></td>\n<td align=right><a href=\"day.php?year=$ty&amp;month=$tm&amp;day=$td&amp;area=$area\">".get_vocab('dayafter')." &gt;&gt;</a></td>\n</tr></table>\n";
}

#We want to build an array containing all the data we want to show
#and then spit it out.

#Get all appointments for today in the area that we care about
#Note: The predicate clause 'start_time <= ...' is an equivalent but simpler
#form of the original which had 3 BETWEEN parts. It selects all entries which
#occur on or cross the current day.
$sql = "SELECT ".$_COOKIE["table_prefix"]."_room.id, start_time, end_time, name, ".$_COOKIE["table_prefix"]."_entry.id, type, create_by, statut_entry, ".$_COOKIE["table_prefix"]."_entry.description, ".$_COOKIE["table_prefix"]."_entry.option_reservation
   FROM ".$_COOKIE["table_prefix"]."_entry, ".$_COOKIE["table_prefix"]."_room
   WHERE ".$_COOKIE["table_prefix"]."_entry.room_id = ".$_COOKIE["table_prefix"]."_room.id
   AND area_id = '".protect_data_sql($area)."'
   AND start_time < ".($pm7+$resolution)." AND end_time > $am7 ORDER BY start_time";

$res = grr_sql_query($sql);
if (! $res) {
//    fatal_error(0, grr_sql_error());
    include "include/trailer.inc.php";
    exit;
}

for ($i = 0; ($row = grr_sql_row($res, $i)); $i++) {
    # Each row weve got here is an appointment.
    #Row[0] = Room ID
    #row[1] = start time
    #row[2] = end time
    #row[3] = short description
    #row[4] = id of this booking
    #row[5] = type (internal/external)
    #row[6] = creator of the booking
    #row[7] = satut of the booking
    #row[8] = Full description

    # $today is a map of the screen that will be displayed
    # It looks like:
    #     $today[Room ID][Time][id]
    #                          [color]
    #                          [data]

    # Fill in the map for this meeting. Start at the meeting start time,
    # or the day start time, whichever is later. End one slot before the
    # meeting end time (since the next slot is for meetings which start then),
    # or at the last slot in the day, whichever is earlier.
    # Note: int casts on database rows for max may be needed for PHP3.
    # Adjust the starting and ending times so that bookings which don't
    # start or end at a recognized time still appear.
    $start_t = max(round_t_down($row[1], $resolution, $am7), $am7);
    $end_t = min(round_t_up($row[2], $resolution, $am7) - $resolution, $pm7);

    // Calcul du nombre de créneaux qu'occupe la réservation
    $cellules[$row[4]]=($end_t-$start_t)/$resolution+1;
    // Initialisation du compteur
    $compteur[$row[4]]=0;

    for ($t = $start_t; $t <= $end_t; $t += $resolution)
    {
        $today[$row[0]][$t]["id"]    = $row[4];
        $today[$row[0]][$t]["color"] = $row[5];
        $today[$row[0]][$t]["data"]  = "";
        $today[$row[0]][$t]["who"] = "";
        $today[$row[0]][$t]["statut"] = $row[7];
        $today[$row[0]][$t]["option_reser"] = $row[9];
        if ((isset($display_full_description)) and ($display_full_description==1))
            $today[$row[0]][$t]["description"] = $row[8];
    }

    # Show the name of the booker in the first segment that the booking
    # happens in, or at the start of the day if it started before today.
    $sql_creator = "SELECT prenom, nom FROM ".$_COOKIE["table_prefix"]."_utilisateurs WHERE login = '".$row[6]."'";
    $res_creator = grr_sql_query($sql_creator);
    if ($res_creator) {
    $row_user = grr_sql_row($res_creator, 0);
    }

    if ($row[1] < $am7) {
        $today[$row[0]][$am7]["data"] = $row[3];
        // Info-bulle
        if (!isset($display_info_bulle)) $display_info_bulle = "";
        if ($display_info_bulle == 1)
            $today[$row[0]][$am7]["who"] = get_vocab("created_by").$row_user[0]." ".$row_user[1];
        else if ($display_info_bulle == 2)
            $today[$row[0]][$am7]["who"] = $row[8];
        else
            $today[$row[0]][$am7]["who"] = "";
    } else {
        $today[$row[0]][$start_t]["data"] = $row[3];
        // Info-bulle
        if (!isset($display_info_bulle)) $display_info_bulle = "";
        if ($display_info_bulle == 1)
            $today[$row[0]][$start_t]["who"] = get_vocab("created_by").$row_user[0]." ".$row_user[1];
        else if ($display_info_bulle == 2)
            $today[$row[0]][$start_t]["who"] = $row[8];
        else
            $today[$row[0]][$start_t]["who"] = "";
    }
}
# We need to know what all the rooms area called, so we can show them all
# pull the data from the db and store it. Convienently we can print the room
# headings and capacities at the same time

$sql = "select room_name, capacity, id, description, statut_room, show_fic_room, delais_option_reservation from ".$_COOKIE["table_prefix"]."_room where area_id='".protect_data_sql($area)."' order by order_display, room_name";
$res = grr_sql_query($sql);

# It might be that there are no rooms defined for this area.
# If there are none then show an error and dont bother doing anything
# else
if (! $res) fatal_error(0, grr_sql_error());
if (grr_sql_count($res) == 0)
{
    echo "<h1>".get_vocab('no_rooms_for_area')."</h1>";
    grr_sql_free($res);
}
else
{
	#This is where we start displaying stuff
    echo "<table class='table text-center' cellspacing=0 border=1 width=\"100%\">";

    // Première ligne du tableau
    echo "<tr>\n<th width=\"1%\">&nbsp;</th>";
    $room_column_width = (int)(95 / grr_sql_count($res));
    $nbcol = 0;
    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
        $room_name[$i] = $row[0];
        $id_room[$i] =  $row[2];
        $statut_room[$id_room[$i]] =  $row[4];
        $nbcol++;
        if ($row[1]) {
            $temp = "<br>($row[1] ".($row[1] >1 ? get_vocab("number_max2") : get_vocab("number_max")).")";
        } else {
            $temp="";
        }
        if ($statut_room[$id_room[$i]] == "0") $temp .= "<br><font color=\"#BA2828\"><font size=\"+1\"><b>".get_vocab("ressource_temporairement_indisponible")."</b></font></font>"; // Ressource temporairement indisponible
        echo "<th class='text-center' width=\"$room_column_width%\"";
        // Si la ressource est temporairement indisponible, on le signale
        if ($statut_room[$id_room[$i]] == "0") echo " class='avertissement' ";
        echo ">" . htmlspecialchars($row[0])."\n";
        if (htmlspecialchars($row[3]. $temp != '')) {
            //if (htmlspecialchars($row[3] != '')) $saut = "<br>"; else $saut = "";
           // echo "<br>-".$saut."<i><span class =\"small\">". htmlspecialchars($row[3]) . $temp."\n</span></i>";
        }
        //echo "<br>";
        if ($row[5] == 'y')
            echo "<A href='javascript:centrerpopup(\"view_room.php?id_room=$id_room[$i]\",600,480,\"scrollbars=yes,statusbar=no,resizable=yes\")' \" title=\"".get_vocab("fiche_ressource")."\"><img src=\"img_grr/details.png\" alt=\"détails\" border=\"0\" /></a>";
        if (authGetUserLevel(getUserName(),$id_room[$i]) > 2)
            echo "<a href='admin_edit_room.php?room=$id_room[$i]'><img src=\"img_grr/editor.png\" alt=\"configuration\" border=\"0\" title=\"".get_vocab("Configurer la ressource")."\" width=\"30\" height=\"30\" /></a>";
        echo "</th>";
        $rooms[] = $row[2];
        $delais_option_reservation[$row[2]] = $row[6];
    }
    echo "<th width=\"1%\">&nbsp;</th></tr>\n";

    // Deuxième ligne et lignes suivantes du tableau
    echo "<tr>\n";
    tdcell("cell_hours");
    if ($enable_periods == 'y')
        echo get_vocab('period');
    else
        echo get_vocab('time');
    echo "</td>\n";
    for ($i = 0; $i < $nbcol; $i++)
    {
        // Si la ressource est temporairement indisponible, on le signale, sinon, couleur normale
        if ($statut_room[$id_room[$i]] == "0") tdcell("avertissement"); else tdcell("cell_hours");
        echo "<a title=\"".htmlspecialchars(get_vocab("see_week_for_this_room"))."\"  href=\"week.php?year=$year&amp;month=$month&amp;day=$day&amp;area=$area&amp;room=$id_room[$i]\">".get_vocab("week")."</a><br><a title=\"".htmlspecialchars(get_vocab("see_month_for_this_room"))."\" href=\"month.php?year=$year&amp;month=$month&amp;day=$day&amp;area=$area&amp;room=$id_room[$i]\">".get_vocab("month")."</a>";
        echo "</td>\n";
    }
    tdcell("cell_hours");
    if ($enable_periods == 'y')
        echo get_vocab('period');
    else
        echo get_vocab('time');
    echo "</td>\n</tr>\n";



    // Début première boucle sur le temps
    for ($t = $am7; $t <= $pm7; $t += $resolution)
    {
        # Show the time linked to the URL for highlighting that time
        echo "<tr>\n";


        tdcell("cell_hours");
        if( $enable_periods == 'y' ){
            $time_t = date("i", $t);
            $time_t_stripped = preg_replace( "/^0/", "", $time_t );
            echo $periods_name[$time_t_stripped] . "</td>\n";
        } else
            echo date(hour_min_format(),$t) . "</td>\n";


        // Début Deuxième boucle sur la liste des ressources du domaine
        while (list($key, $room) = each($rooms))
        {
            if(isset($today[$room][$t]["id"])) // il y a une réservation sur le créneau
            {
                $id    = $today[$room][$t]["id"];
                $color = $today[$room][$t]["color"];
                $descr = htmlspecialchars($today[$room][$t]["data"]);
            }
            else
                unset($id);  // $id non défini signifie donc qu'il n'y a pas de résa sur le créneau

            // Définition des couleurs de fond de cellule
            if (isset($id))  // 1er cas : il y a une réservation sur le créneau
            {
                $c = $color;
            } else if ($statut_room[$room] == "0") // 2ème cas : ou bien la ressource est temporairement indisponible
                $c = "avertissement"; // on le signale par une couleur spécifique
            else  // 3ème cas : sinon, il s'agit d'un créneau libre
                $c = "empty_cell";

            // S'il s'agit d'un créneau avec une resa :
            // s'il s'agit du premier passage ($compteur[$id]=0), on fait un tdcell_rowspan
            // Sinon, pas de <td>
            if  (isset($id)) {
                if( $compteur[$id] == 0 ) tdcell_rowspan ($c, $cellules[$id]);
                $compteur[$id] = 1; // on incrémente le compteur initialement à zéro
            } else
                tdcell ($c); // il s'agit d'un créneau libre  -> <td> normal
            // Si $compteur[$id] a atteint == $cellules[$id]+1


            if(!isset($id)) // Le créneau est libre
            {
                $hour = date("H",$t);
                $minute  = date("i",$t);
                $date_booking = mktime($hour, $minute, 0, $month, $day, $year);
                echo "<center>";
                if (est_hors_reservation(mktime(0,0,0,$month,$day,$year)))
                    echo "<center><img src=\"img_grr/stop.png\" border=\"0\" alt=\"".get_vocab("reservation_impossible")."\"  title=\"".get_vocab("reservation_impossible")."\" width=\"16\" height=\"16\" /></center>";
                else

                if ((authGetUserLevel(getUserName(),-1) > 1)
                 and (UserRoomMaxBooking(getUserName(), $room, 1) != 0)
                 and verif_booking_date(getUserName(), -1, $room, $date_booking, $date_now, $enable_periods)
                 and verif_delais_max_resa_room(getUserName(), $room, $date_booking)
                 and verif_delais_min_resa_room(getUserName(), $room, $date_booking)
				 and UserAreaGroup(getUserName(), $area)
                 and (($statut_room[$room] == "1") or
                  (($statut_room[$room] == "0") and (authGetUserLevel(getUserName(),$room) > 2) ))) {
                    if ($enable_periods == 'y') {
                    			if ((authGetUserLevel(getUserName(),$room,'room') >= 3) OR (authGetUserLevel(getUserName(),$area,'area') >= 4))
													 	{
                        		echo "<a href=\"edit_entry.php?area=$area&amp;room=$room&amp;period=$time_t_stripped&amp;year=$year&amp;month=$month&amp;day=$day&amp;page=day\" title=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\" alt=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\"><img src=img_grr/new.png border=0></a>";
                    				}	else  {
                    		 		echo "<a href=\"edit_entry_user.php?area=$area&amp;room=$room&amp;period=$time_t_stripped&amp;year=$year&amp;month=$month&amp;day=$day&amp;page=day\" title=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\" alt=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\"><img src=img_grr/new.png border=0></a>";
														}
									}	else   {
													if ((authGetUserLevel(getUserName(),$room,'room') >= 3) OR (authGetUserLevel(getUserName(),$area,'area') >= 4))
													 	{
                        		echo "<a href=\"edit_entry.php?area=$area&amp;room=$room&amp;hour=$hour&amp;minute=$minute&amp;year=$year&amp;month=$month&amp;day=$day&amp;page=day\" title=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\" alt=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\"><img src=img_grr/new.png border=0></a>";
                						}	else  {
                								echo "<a href=\"edit_entry_user.php?area=$area&amp;room=$room&amp;hour=$hour&amp;minute=$minute&amp;year=$year&amp;month=$month&amp;day=$day&amp;page=day\" title=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\" alt=\"".get_vocab("cliquez_pour_effectuer_une_reservation")."\"><img src=img_grr/new.png border=0></a>";
                						}
                						}
								} else {													
                    echo "&nbsp;";
                }
                echo "</center>";
                echo "</td>\n";             
            }
            elseif ($descr != "")
            {
                // si la réservation est "en cours", on le signale
                if ((isset($today[$room][$t]["statut"])) and ($today[$room][$t]["statut"]=='y')) echo "&nbsp;<img src=\"img_grr/buzy.png\" alt=\"".get_vocab("reservation_en_cours")."\" title=\"".get_vocab("reservation_en_cours")."\" width=\"20\" height=\"20\" border=\"0\" />&nbsp;\n";
                // si la réservation est à confirmer, on le signale
                if (($delais_option_reservation[$room] > 0) and (isset($today[$room][$t]["option_reser"])) and ($today[$room][$t]["option_reser"]!=-1)) echo "&nbsp;<img src=\"img_grr/small_flag.png\" alt=\"".get_vocab("reservation_a_confirmer_au_plus_tard_le")."\" title=\"".get_vocab("reservation_a_confirmer_au_plus_tard_le")."&nbsp;".time_date_string_jma($today[$room][$t]["option_reser"],$dformat)."\" width=\"20\" height=\"20\" border=\"0\" />&nbsp;\n";

                #if it is booked then show
                if (($statut_room[$room] == "1") or
                (($statut_room[$room] == "0") and (authGetUserLevel(getUserName(),$room) > 2) )) {
                    echo " <a title=\"".htmlspecialchars($today[$room][$t]["who"])."\" href=\"view_entry.php?id=$id&amp;area=$area&amp;day=$day&amp;month=$month&amp;year=$year&amp;page=day\">$descr</a>";
                    if ((isset($display_full_description)) and ($display_full_description==1) and ($today[$room][$t]["description"]!= ""))
                    echo "<br><i>".$today[$room][$t]["description"]."</i>";

                } else {
                    echo " $descr";
                }
                echo "</td>\n";
            }
        } // Fin Deuxième boucle sur la liste des ressources du domaine

        // Répétition de la première colonne
        // Si la ressource est temporairement indisponible, on le signale, sinon, couleur normale
        tdcell("cell_hours");
        if( $enable_periods == 'y' ){
            $time_t = date("i", $t);
            $time_t_stripped = preg_replace( "/^0/", "", $time_t );
            echo $periods_name[$time_t_stripped] . "</td>\n";
        } else
            echo date(hour_min_format(),$t) . "</td>\n";

        echo "</tr>\n";

        reset($rooms);
    }
    // répétition de la ligne d'en-tête
    echo "<tr>\n<th>&nbsp;</th>";
    for ($i = 0; $i < $nbcol; $i++)
    {
        echo "<th class='text-center'";
        if ($statut_room[$id_room[$i]] == "0") echo " class='avertissement' ";
        echo ">" . htmlspecialchars($room_name[$i])."</th>";
    }
    echo "<th>&nbsp;</th></tr>\n";

    echo "</table>";
	// Si format imprimable ($_GET['pview'] = 1), on n'affiche pas cette partie
	if ($_GET['pview'] != 1) { 
    show_colour_key($area);
	}
}
include "include/trailer.inc.php";
?>