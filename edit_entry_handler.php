<?php
#########################################################################
#                        edit_entry_handler.php                         #
#                                                                       #
#            Permet de vérifier la validitée de l'édition               #
#                ou de la création d'une réservation                     #
#                                                                       #
#            Dernière modification : 24/01/2010                         #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 * D'après http://mrbs.sourceforge.net/
 *
 * Modification S Duchemin
 * Refonte du traitement de la réservation
 * Interdiction de réserver deux heures consécutives pour un utilisateur avec la même personne (paramétrable)
 * Interdiction de réserver plusieurs courts à la même heure.
 * Dans le cas d'un droit de réservation >1 sur un court, impossibilité de réserver deux heures consécutives
 * Vérification du compteur invité
 * Envoi d'un mail automatique à l'adversaire lorsqu'un utilisateur réserve un créneau
 * Possiblité de controler le nombre de réservation maximum par semaine pour un utilisateur si max_booking_week différent de -1
 * limitation du nombre de réservation actives sur l'ensemble des installations ( maxallressources != -1)
 * Possibilité d'autoriser une réservation championnat individuel de 2 h (à régler dans l'administration des utilisateurs)
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
include "include/misc.inc.php";

// Settings
require_once("./include/settings.inc.php");
//Chargement des valeurs de la table settingS
if (!loadSettings())
    die("Erreur chargement settings");

// Session related functions
require_once("./include/session.inc.php");
// Resume session
if (!grr_resumeSession()) {
    header("Location: ./logout.php?auto=1");
    die();
};

// Paramètres langage
include "include/language.inc.php";

$erreur = 'n';

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    settype($id,"integer");
} else $id = NULL;
$name = isset($_GET["name"]) ? $_GET["name"] : NULL;
$description = isset($_GET["description"]) ? $_GET["description"] : NULL;
$ampm = isset($_GET["ampm"]) ? $_GET["ampm"] : NULL;
$duration = isset($_GET["duration"]) ? $_GET["duration"] : NULL;
$duration = str_replace(",", ".", "$duration ");
$hour = isset($_GET["hour"]) ? $_GET["hour"] : NULL;
if (isset($hour)) {
    settype($hour,"integer");
    if ($hour > 23) $hour = 23;
    if ($hour < 8 ) $hour = 8;
}
$minute = NULL;
$minute = isset($_GET["minute"]) ? $_GET["minute"] : NULL;
if (isset($minute)) {
   settype($minute,"integer");
   if ($minute > 59) $hour = 59;
}

$type = isset($_GET["type"]) ? $_GET["type"] : NULL;
$rep_type = isset($_GET["rep_type"]) ? $_GET["rep_type"] : NULL;
if (isset($rep_type)) settype($rep_type,"integer");
$rep_num_weeks = isset($_GET["rep_num_weeks"]) ? $_GET["rep_num_weeks"] : NULL;
if (isset($rep_num_weeks)) settype($rep_num_weeks,"integer");
if ($rep_num_weeks < 2) $rep_num_weeks = 1;
// Compatibilité avec mrbs
if (($rep_num_weeks >= 2) and ($rep_type==2)) $rep_type = 6;
$rep_month = isset($_GET["rep_month"]) ? $_GET["rep_month"] : NULL;
if (($rep_type==3) and ($rep_month == 3)) $rep_type =3;
if (($rep_type==3) and ($rep_month == 5)) $rep_type =5;
$returl = isset($_GET["returl"]) ? $_GET["returl"] : NULL;
$create_by = isset($_GET["create_by"]) ? $_GET["create_by"] : NULL;
$rep_id = isset($_GET["rep_id"]) ? $_GET["rep_id"] : NULL;
$rep_day = isset($_GET["rep_day"]) ? $_GET["rep_day"] : NULL;
$rep_end_day = isset($_GET["rep_end_day"]) ? $_GET["rep_end_day"] : NULL;
$rep_end_month = isset($_GET["rep_end_month"]) ? $_GET["rep_end_month"] : NULL;
$rep_end_year = isset($_GET["rep_end_year"]) ? $_GET["rep_end_year"] : NULL;
$room_back = isset($_GET["room_back"]) ? $_GET["room_back"] : NULL;
if (isset($room_back)) settype($room_back,"integer");
$page = verif_page();
if ($page == '') $page="day";
$option_reservation = isset($_GET["option_reservation"]) ? $_GET["option_reservation"] : NULL;

if (isset($option_reservation))
    settype($option_reservation,"integer");
else
    $option_reservation = -1;
if (isset($_GET["confirm_reservation"]))
    $option_reservation = -1;
$type_affichage_reser = isset($_GET["type_affichage_reser"]) ? $_GET["type_affichage_reser"] : NULL;


//On recupere toute les variables d'environnement pour pouvoir retourner sur edit_entry.php si une erreur se produit.
$return_link = "<form action=edit_entry_user.php>\n";
$typelist = array('area','room','day','month','year','minute','hour','day','name','type','description',
          'end_day','end_month','end_year','end_hour','end_minute','rep_type','rep_num_weeks',
          'rep_month','rep_end_day','rep_end_month','rep_end_year','rep_id','edit_type','type_affichage_reser');

foreach ($typelist as $element)
{
  if ( isset($_GET[$element]))
    {
      $return_link .= "<input type=hidden name=\"".$element."\" value=\"".$_GET["$element"]."\">";
      $return_link .= "<BR>".$element." ++ ".$_GET["$element"];
    }
}

$return_link .= "<input type=submit value=\"".get_vocab('back')."\">";
$return_link .= "</form>\n";





// On récupère la valeur de $area
$area = mrbsGetRoomArea($_GET['rooms'][0]);

//Récupération des données additionnelles
$overload_data = array();
$overload_fields_list = mrbsOverloadGetFieldslist($area);

foreach ($overload_fields_list as $overfield=>$fieldtype)
{
  $id_field = $overload_fields_list[$overfield]["id"];
  $fieldname = "addon_".$id_field;
  if (isset($_GET[$fieldname])) $overload_data[$id_field] = $_GET[$fieldname];
  else $overload_data[$id_field] = "";
}
//Fin de récupération des données additionnelles.





//If we dont know the right date then make it up
if(!isset($day) or !isset($month) or !isset($year))
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
}
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];

// Récupération des données concernant l'affichage du planning du domaine
get_planning_area_values($area);

if(authGetUserLevel(getUserName(),-1) < 2)
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

if (check_begin_end_bookings($day, $month, $year))
{
    showNoBookings($day, $month, $year, $area,$back);
    exit();
}

if ($type_affichage_reser == 0) {
    // La fin de réservation est calculée à partir d'une durée
    $period = isset($_GET["period"]) ? $_GET["period"] : NULL;
    if (isset($period)) settype($period,"integer");
    $dur_units = isset($_GET["dur_units"]) ? $_GET["dur_units"] : NULL;
    $all_day = isset($_GET["all_day"]) ? $_GET["all_day"] : NULL;

    if($enable_periods=='y') {
        $resolution = 60;
        $hour = 12;
        $minute = $period;
        $max_periods = count($periods_name);
        if( $dur_units == "periods" && ($minute + $duration) > $max_periods )
        {
            $duration = (24*60*floor($duration/$max_periods)) + ($duration%$max_periods);
        }
        if( $dur_units == "days" && $minute == 0 )
        {
            $dur_units = "periods";
            $duration = $max_periods + ($duration-1)*60*24;
        }
    }
    // Units start in seconds
    $units = 1.0;

    switch($dur_units)
    {
        case "years":
            $units *= 52;
        case "weeks":
            $units *= 7;
        case "days":
            $units *= 24;
        case "hours":
            $units *= 60;
        case "periods":
        case "minutes":
            $units *= 60;
        case "seconds":
           break;
    }
    // Units are now in "$dur_units" numbers of seconds
   if(isset($all_day) && ($all_day == "yes") && ($dur_units!="days")) {
        if($enable_periods=='y') {
            $starttime = mktime(12, 0, 0, $month, $day, $year);
            $endtime   = mktime(12, $max_periods, 0, $month, $day, $year);
        } else {
      $starttime = mktime($morningstarts, 0, 0, $month, $day  , $year);
      $endtime   = mktime($eveningends, 0, $resolution, $month, $day, $year);
        }
    } else {
        if (!$twentyfourhour_format)
        {
          if (isset($ampm) && ($ampm == "pm"))
         {
           $hour += 12;
         }
        }
       $starttime = mktime($hour, $minute, 0, $month, $day, $year);
       $endtime   = mktime($hour, $minute, 0, $month, $day, $year) + ($units * $duration);
       if ($endtime <= $starttime)
           $erreur = 'y';

       # Round up the duration to the next whole resolution unit.
       # If they asked for 0 minutes, push that up to 1 resolution unit.
       $diff = $endtime - $starttime;
        if (($tmp = $diff % $resolution) != 0 || $diff == 0)
            $endtime += $resolution - $tmp;

   }
} else {

    // La fin de réservation est calculée à  partir d'une date


    // Cas particulier des réservation par créneaux pré-définis
    if($enable_periods=='y') {
        $resolution = 60;
        $hour = 12;
        $_GET["end_hour"] = 12;
        if (isset($_GET["period"]))
            $minute = $_GET["period"];
        else
            $erreur='y';
        if (isset($_GET["end_period"]))
            $_GET["end_minute"] = $_GET["end_period"]+1;
        else
            $erreur='y';
    }

    if (!isset($_GET["end_day"]) or !isset($_GET["end_month"]) or !isset($_GET["end_year"]) or !isset($_GET["end_hour"]) or !isset($_GET["end_minute"]))
    {
        $erreur = 'y';
    } else {
        $end_day = $_GET["end_day"];
        $end_year = $_GET["end_year"];
        $end_month = $_GET["end_month"];
        $end_hour = $_GET["end_hour"];
        $end_minute = $_GET["end_minute"];
        settype($end_month,"integer");
        settype($end_day,"integer");
        settype($end_year,"integer");
        settype($end_minute,"integer");
        settype($end_hour,"integer");
        $minyear = strftime("%Y", getSettingValue("begin_bookings"));
        $maxyear = strftime("%Y", getSettingValue("end_bookings"));
        if ($end_day < 1) $end_day = 1;
        if ($end_day > 31) $end_day = 31;
        if ($end_month < 1) $end_month = 1;
        if ($end_month > 12) $end_month = 12;
        if ($end_year < $minyear) $end_year = $minyear;
        if ($end_year > $maxyear) $end_year = $maxyear;

    //Si la date n'est pas valide on arrête
        if (!checkdate($end_month, $end_day, $end_year))
            $erreur = 'y';

        $starttime = mktime($hour, $minute, 0, $month, $day, $year);
        $endtime   = mktime($end_hour, $end_minute, 0, $end_month, $end_day, $end_year);

        if ($endtime <= $starttime)
            $erreur = 'y';

        # Round up the duration to the next whole resolution unit.
        # If they asked for 0 minutes, push that up to 1 resolution unit.
        $diff = $endtime - $starttime;
        if (($tmp = $diff % $resolution) != 0 || $diff == 0)
            $endtime += $resolution - $tmp;
        }
}



if ($endtime <= $starttime)
    $erreur = 'y';


// Si il y a tentative de réserver en-deça du temps limite
if ($erreur == 'y') {
    print_header($day, $month, $year, $area);
    echo "<H2>Erreur dans la date de fin de réservation</H2>";
    echo $return_link;
    include "include/trailer.inc.php";
    die();
}








if(isset($rep_type) && isset($rep_end_month) && isset($rep_end_day) && isset($rep_end_year))
// Si une périodicité a été définie
{
    // Get the repeat entry settings
    // Calcul de la date de fin de périodicité
    $rep_enddate = mktime($hour, $minute, 0, $rep_end_month, $rep_end_day, $rep_end_year);
    // Cas où la date de fin de périodicité est supérieure à la date de fin de réservation
    if ($rep_enddate > getSettingValue("end_bookings")) $rep_enddate = getSettingValue("end_bookings");
} else
    //  Si aucune périodicité n'a été définie
    $rep_type = 0;

if(!isset($rep_day))
    $rep_day = "";




// Dans le cas d'une réservation sans périodicité, on teste si la résa tombe un jour "hors réservation"
// On définit les jours temps "minuit" de début et fin
$day_temp   = date("d",$starttime);
$month_temp = date("m",$starttime);
$year_temp  = date("Y",$starttime);
$starttime_midnight = mktime(0, 0, 0, $month_temp, $day_temp, $year_temp);
$day_temp   = date("d",$endtime);
$month_temp = date("m",$endtime);
$year_temp  = date("Y",$endtime);
$endtime_midnight = mktime(0, 0, 0, $month_temp, $day_temp, $year_temp);
// On teste
if (resa_est_hors_reservation($starttime_midnight , $endtime_midnight )) {
    print_header($day, $month, $year, $area);
    echo "<H2>Erreur dans la date de fin de réservation</H2>";
    echo $return_link;
    include "include/trailer.inc.php";
    die();
}


# For weekly repeat(2), build string of weekdays to repeat on:
$rep_opt = "";
if (($rep_type == 2) || ($rep_type == 6))
    for ($i = 0; $i < 7; $i++) $rep_opt .= empty($rep_day[$i]) ? "0" : "1";


# Expand a series into a list of start times:
if ($rep_type != 0)
    // $reps est un tableau des dates de début de réservation
    $reps = mrbsGetRepeatEntryList($starttime, isset($rep_enddate) ? $rep_enddate : 0,
        $rep_type, $rep_opt, $max_rep_entrys, $rep_num_weeks);

# When checking for overlaps, for Edit (not New), ignore this entry and series:
$repeat_id = 0;
if (isset($id) and ($id!=0)) {
    $ignore_id = $id;
    $repeat_id = grr_sql_query1("SELECT repeat_id FROM ".$_COOKIE["table_prefix"]."_entry WHERE id=$id");
    if ($repeat_id < 0) $repeat_id = 0;
} else     $ignore_id = 0;

# Acquire mutex to lock out others trying to book the same slot(s).
if (!grr_sql_mutex_lock('grr_entry'))
    fatal_error(1, get_vocab('failed_to_acquire'));

$date_now = time();
$error_booking_in_past = 'no';
$error_booking_room_out = 'no';
$error_delais_max_resa_room = 'no';
$error_delais_min_resa_room = 'no';
$error_date_option_reservation = 'no';
$error_booking_double = 'no';
$error_booking_week = 'no';
$error_booking_same_date_other_room = 'no';
$error_invite = 'no';
$error_booking_max_all_ressources = 'no';

foreach ( $_GET['rooms'] as $room_id ) {
    # On verifie qu'aucune réservation ne se situe dans la passé
    if ($rep_type != 0 && !empty($reps))  {
        $diff = $endtime - $starttime;
        $i = 0;
        while (($i < count($reps)) and ($error_booking_in_past == 'no') and ($error_delais_max_resa_room == 'no') and ($error_delais_min_resa_room == 'no') and ($error_date_option_reservation=='no')) {
            if (!(verif_booking_date(getUserName(), -1, $room_id, $reps[$i], $date_now, $enable_periods))) $error_booking_in_past = 'yes';
            if (!(verif_delais_max_resa_room(getUserName(), $room_id, $reps[$i]))) $error_delais_max_resa_room = 'yes';
            if (!(verif_delais_min_resa_room(getUserName(), $room_id, $reps[$i]))) $error_delais_min_resa_room = 'yes';
            if (!(verif_date_option_reservation($option_reservation, $reps[$i]))) $error_date_option_reservation = 'yes';
            $i++;
        }
    } else {
        if (isset($id) and ($id!=0)) {
            if (!(verif_booking_date(getUserName(), $id, $room_id, $starttime, $date_now, $enable_periods, $endtime))) $error_booking_in_past = 'yes';
        } else {
            if (!(verif_booking_date(getUserName(), -1, $room_id, $starttime, $date_now, $enable_periods))) $error_booking_in_past = 'yes';
        }
        if (!(verif_delais_max_resa_room(getUserName(), $room_id, $starttime))) $error_delais_max_resa_room = 'yes';
        if (!(verif_delais_min_resa_room(getUserName(), $room_id, $starttime))) $error_delais_min_resa_room = 'yes';
        if (!(verif_date_option_reservation($option_reservation, $starttime))) $error_date_option_reservation = 'yes';
    }
	
    $statut_room = grr_sql_query1("select statut_room from ".$_COOKIE["table_prefix"]."_room where id = '$room_id'");
    // on vérifie qu\'un utilisateur non autorisé ne tente pas de réserver une ressource non disponible
    if (($statut_room == "0") and authGetUserLevel(getUserName(),$room_id) < 3)
        $error_booking_room_out = 'yes';
} # end foreach rooms

// Si le test précédent est passé avec succès,
# Check for any schedule conflicts in each room we're going to try and
# book in
$err = "";
if (($error_booking_in_past == 'no') and ($error_delais_max_resa_room == 'no') and ($error_delais_min_resa_room == 'no')  and ($error_date_option_reservation == 'no')) {
    foreach ( $_GET['rooms'] as $room_id ) {
        if ($rep_type != 0 && !empty($reps))  {
            if(count($reps) < $max_rep_entrys) {
                $diff = $endtime - $starttime;
                for($i = 0; $i < count($reps); $i++) {
                    // Suppression des résa en conflit
                    if (isset($_GET['del_entry_in_conflict']) and ($_GET['del_entry_in_conflict']=='yes'))
                        grrDelEntryInConflict($room_id, $reps[$i], $reps[$i] + $diff, $ignore_id, $repeat_id, 0);
                    // On teste s'il reste des conflits
                    if ($i == (count($reps)-1))
                       $tmp = mrbsCheckFree($room_id, $reps[$i], $reps[$i] + $diff, $ignore_id, $repeat_id);
                    else
                       $tmp = mrbsCheckFree($room_id, $reps[$i], $reps[$i] + $diff, $ignore_id, $repeat_id);
                    if(!empty($tmp)) $err = $err . $tmp;
                }
            } else {
                $err .= get_vocab("too_may_entrys") . "<P>";
                $hide_title  = 1;
            }
        } else {
           // Suppression des résa en conflit
           if (isset($_GET['del_entry_in_conflict']) and ($_GET['del_entry_in_conflict']=='yes'))
               grrDelEntryInConflict($room_id, $starttime, $endtime-1, $ignore_id, $repeat_id, 0);
           // On teste s'il y a des conflits
           $err .= mrbsCheckFree($room_id, $starttime, $endtime-1, $ignore_id, $repeat_id);
        }
    } # end foreach rooms
}
//test si la personne qui réserve tente de réserver avec la même personne sur l'heure précédente ou suivante et sur une autre ressource à la même heure
	//test du paramètre bookingdouble (autorise ou non une réservation contigue à une autre (avant ou après))
	if(getSettingValue("bookingdouble") != '-1')
		{
		if (!(verif_booking_double($create_by, $name, $description, $area, $room_id, $starttime, $endtime))) $error_booking_double = 'yes';
		}
if (!(verif_booking_week($create_by, $name, $description, $room_id, $starttime, $endtime))) $error_booking_week = 'yes';
if (!(verif_booking_same_date_other_room($create_by, $name, $description, $area, $room_id, $starttime))) $error_booking_same_date_other_room = 'yes';
if (!(verif_invite($create_by, $description))) $error_invite = 'yes';
if (!(verif_booking_max_all_ressources($create_by, $name, $description, $area, $room_id, $starttime))) $error_booking_max_all_ressources = 'yes';
// Si tous les tests précédents sont passés avec succès :
if (empty($err)
    and ($error_booking_in_past == 'no')
    and ($error_delais_max_resa_room == 'no')
    and ($error_delais_min_resa_room == 'no')
    and ($error_booking_room_out == 'no')
    and ($error_date_option_reservation == 'no')
    and ($error_booking_double== 'no')
    and ($error_booking_week== 'no')
    and ($error_booking_same_date_other_room == 'no')
    and ($error_invite == 'no')
	and ($error_booking_max_all_ressources == 'no')
	)
{
    // On teste si l'utilisateur a le droit d'effectuer la série de réservation, compte tenu des
    // réser déjà effectuées et de la limite posée sur la ressource
    foreach ( $_GET['rooms'] as $room_id ) {
        $area = mrbsGetRoomArea($room_id);
        // Contrôle droit d'écriture
        if (isset($id) and ($id!=0)) {
            if(!getWritable($create_by, getUserName(),$id))
            {
                showAccessDenied($day, $month, $year, $area,$back);
                exit;
            }
        }
	
        // Contrôle accès restreint
        if(authUserAccesArea($_SESSION['login'], $area)==0)
        {
            showAccessDenied($day, $month, $year, $area,$back);
            exit();
        }
					
        if ($rep_type != 0 && !empty($reps))  {
            if (UserRoomMaxBooking(getUserName(), $room_id, count($reps)) == 0) {
               showAccessDeniedMaxBookings($day, $month, $year, $area, $room_id, $back);
              exit();
            }
        } else {
		    if ((UserRoomMaxBooking(getUserName(), $room_id, 1) == 0) OR (AdvRoomMaxBooking(getUserName(), $description, $room_id,1) == 0)) {
               showAccessDeniedMaxBookings($day, $month, $year, $area, $room_id, $back);
              exit();
            }
        }
    }




foreach ( $_GET['rooms'] as $room_id )
{
  if($rep_type != 0)
    {
      mrbsCreateRepeatingEntrys($starttime, $endtime, $rep_type, $rep_enddate, $rep_opt,
                $room_id, $create_by, $name, $type, $description, $rep_num_weeks, $option_reservation,$overload_data);


      $new_id = grr_sql_insert_id("grr_entry", "id");
      if (getSettingValue("automatic_mail") == 'yes')
    {
      if (isset($id) and ($id!=0))
        {
          send_mail($new_id,2,$dformat);
        }
      else
        {
          send_mail($new_id,1,$dformat);
        }
    }

    }
  else
    {
      // Mark changed entry in a series with entry_type 2:
      if ($repeat_id > 0)
    $entry_type = 2;
      else
    $entry_type = 0;

      // Create the entry:
			//test si la réservation est passée avec une description 'championnat individuel', si c'est le cas on applique  une résa de 2h !
			if  ($description == 'championnat individuel')
			{
			mrbsCreateSingleEntry($starttime, ($starttime + 7200), $entry_type, $repeat_id, $room_id,
                $create_by, $name, 'F', $description, $option_reservation,$overload_data);
			$new_id = grr_sql_insert_id("grr_entry", "id");
			} else {
			mrbsCreateSingleEntry($starttime, $endtime, $entry_type, $repeat_id, $room_id,
                $create_by, $name, $type, $description, $option_reservation,$overload_data);
			$new_id = grr_sql_insert_id("grr_entry", "id");
			}
      if (getSettingValue("automatic_mail") == 'yes')
    {
      if(isset($id) and ($id!=0))
        {
          send_mail($new_id,2,$dformat);
        }
      else
        {
          send_mail($new_id,1,$dformat);
        }
    }
unset ($_SESSION['displ_msg']);
    }
	


} // end foreach $rooms
// Envoi d'un mail / Découpage du nom prénom de l'adversaire ($description)
if (isset($description)){
$tableau = explode(" ", $description);
$exp = count($tableau);
if ($exp == 2){
$nomadv = $tableau[0];
$prenomadv = $tableau[1];
$sql = "select nom, prenom, email from ".$_COOKIE["table_prefix"]."_utilisateurs where nom ='".$nomadv."' and prenom = '".$prenomadv."'order by nom";
    $adv = grr_sql_query($sql);
	
for ($i = 0; ($row = grr_sql_row($adv, $i)); $i++)
       $destinataire = $row[2];
    
 //Recherche nom prénom et mail du joueur qui réserve
 $query = "SELECT nom, prenom FROM ".$_COOKIE["table_prefix"]."_utilisateurs WHERE login='$create_by'";
	$result = grr_sql_query ($query) or die ("Erreur pendant la requête");
	$line = mysqli_fetch_array ($result);
  list($nomres, $prenomres)=$line;    
  
$sqlres = "select nom, prenom, email from ".$_COOKIE["table_prefix"]."_utilisateurs where nom ='".$nomres."' and prenom = '".$prenomres."'order by nom";
    $res = grr_sql_query($sqlres);
	
for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
       $reserve = $row[2];
	   
//Recherche description de la room 
$sqlroom = "select room_name from ".$_COOKIE["table_prefix"]."_room where id='$room_back'";
    $room = grr_sql_query($sqlroom);
	
for ($i = 0; ($row = grr_sql_row($room, $i)); $i++)
       $desroom = $row[0];

 if ((isset($destinataire)) AND ($destinataire !='')){
 $expediteur = "".getSettingValue("webmaster_email")."";
 // on génère une chaîne de caractères aléatoire qui sera utilisée comme frontière
  $boundary = "-----=" . md5( uniqid ( rand() ) );

  $headers  = "From: $expediteur\n";
  $headers .= "Reply-To: $expediteur\n";
  // on indique qu'on a affaire à un email au format html et texte et
  // on spécifie la frontière (boundary) qui servira à séparer les deux parties
  // ainsi que la version mime
  $headers .= "MIME-Version: 1.0\n";
  $headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"";

  $message_txt  = "ATTENTION ceci est un mail automatique\n\n";
  $message_txt .= "$prenomres $nomres vous a choisi comme adversaire \n\n";
  $message_txt .= "$desroom est réservé\n";
  $message_txt .= "Début de la réservation : $hour heures $minute - le $day / $month / $year\n\n";
  $message_txt .= "Si cette réservation n'est pas sollicitée, veuillez informer le bureau du Tennis Club par retour\n";
  $message  = "This is a multi-part message in MIME format.\n\n";
  $message .= "--" . $boundary . "\n";
  $message .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
  $message .= "Content-Transfer-Encoding: quoted-printable\n\n";
  $message .= $message_txt;
  $message .= "\n\n";
  $message .= "--" . $boundary . "--\n";
  $message1_txt  = "ATTENTION ceci est un mail automatique destiné à $prenomres $nomres\n\n";
  $message1_txt .= "Vous avez réservé avec $prenomadv $nomadv \n\n";
  $message1_txt .= "sur $desroom\n";
  $message1_txt .= "Début de la réservation : $hour heures $minute - le $day / $month / $year\n\n";
  $message1_txt .= "Si cette réservation n'est pas sollicitée, veuillez informer le bureau du T.C.R. par retour\n";
  $message1  = "This is a multi-part message in MIME format.\n\n";
  $message1 .= "--" . $boundary . "\n";
  $message1 .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
  $message1 .= "Content-Transfer-Encoding: quoted-printable\n\n";
  $message1 .= $message1_txt;
  $message1 .= "\n\n";
  $message1 .= "--" . $boundary . "--\n";

  mail($destinataire, 'Reservation', $message, $headers);
  if ($reserve !='') {
  mail($reserve, 'Reservation', $message1, $headers);
  }
  }
  }
  }

  // Delete the original entry
    if(isset($id) and ($id!=0)) {
        if ($rep_type != 0)
            mrbsDelEntry(getUserName(), $id, "series", 1);
        else
            mrbsDelEntry(getUserName(), $id, "", 1);
    }

    grr_sql_mutex_unlock('grr_entry');

    $area = mrbsGetRoomArea($room_id);

    # Now its all done go back to the day view
    $_SESSION['displ_msg'] = 'yes';
    Header("Location: ".$page.".php?year=$year&month=$month&day=$day&area=$area&room=$room_back");
    exit;
}

# The room was not free.
grr_sql_mutex_unlock('grr_entry');


// Si il y a tentative de réserver dans le passé
if ($error_booking_in_past == 'yes') {
    $str_date = utf8_strftime("%d %B %Y, %H:%M", $date_now);
    print_header($day, $month, $year, $area);
    echo "<H2>" . get_vocab("booking_in_past") . "</H2>";
    if ($rep_type != 0 && !empty($reps))  {
        echo "<p>" . get_vocab("booking_in_past_explain_with_periodicity") . $str_date."</p>";
    } else {
        echo "<p>" . get_vocab("booking_in_past_explain") . $str_date."</p>";
    }
    echo $return_link;
    include "include/trailer.inc.php";
    die();
}
// Si il y a tentative de réserver au delà du temps limite
if ($error_delais_max_resa_room == 'yes') {
    print_header($day, $month, $year, $area);
    echo "<H2>" . get_vocab("error_delais_max_resa_room") ."</H2>";
    echo $return_link;
    include "include/trailer.inc.php";
    die();
}
// Si il y a tentative de réserver deux heures consécutives 
if ($error_booking_double == 'yes') {
    print_header($day, $month, $year, $area);
    echo "<H2>" . get_vocab("error_booking_double") ."</H2>";
    //echo $return_link;
    include "include/trailer.inc.php";
    die();
}

// Si il y a tentative de réserver au delà du quota d'heures par semaine
if ($error_booking_week == 'yes') {
    print_header($day, $month, $year, $area);
    echo "<H2>" . get_vocab("error_booking_week") ."</H2>";
echo "starttime : ". $starttime."<br>";
$reserv_week = (int)(($starttime + 259200)/604800);		//nombre de semaine en ENTIER
echo "reserv_week : ". $reserv_week."<br>";
$debsem = (($reserv_week) * 604800);
echo "debsem : ". $debsem."<br>" ;
$begin_week = $debsem - 259200;
echo "begin_week: ". $begin_week."<br>";
$end_week = $begin_week + 604800;
echo "end_week: ". $end_week."<br>";
   // echo $return_link;
    include "include/trailer.inc.php";
    die();
}


// Si il y a tentative de réserver avec un invité sans crédit
if ($error_invite == 'yes')  {
    print_header($day, $month, $year, $area);
    echo "<H2>" . get_vocab("error_invite") ."</H2>";
   // echo $return_link;
    include "include/trailer.inc.php";
    die();
}

// Si il y a tentative de réserver à la même heure sur une autre ressource
if ($error_booking_same_date_other_room == 'yes')  {
    print_header($day, $month, $year, $area);
    echo "<H2>" . get_vocab("error_same_date_other_room") ."</H2>";
   // echo $return_link;
    include "include/trailer.inc.php";
    die();
}

// Si il y a tentative de réserver sur d'autres ressources avec une limitation du nombre de réservation actives sur l'ensemble des installations ( maxallressources != -1)
if ($error_booking_max_all_ressources == 'yes')  {
    print_header($day, $month, $year, $area);
    echo "<H2>" . get_vocab("error_max_all_ressources") ."</H2>";
   // echo $return_link;
    include "include/trailer.inc.php";
    die();
}

// Si il y a tentative de réserver en-deça du temps limite
if ($error_delais_min_resa_room == 'yes') {
    print_header($day, $month, $year, $area);
    echo "<H2>" . get_vocab("error_delais_min_resa_room") ."</H2>";
    echo $return_link;
    include "include/trailer.inc.php";
    die();
}

// Si la date confirmation est supérieure à la date de début de réservation
if ($error_date_option_reservation == 'yes') {
    print_header($day, $month, $year, $area);
    echo "<H2>" . get_vocab("error_date_confirm_reservation") ."</H2>";
    echo $return_link;
    include "include/trailer.inc.php";
    die();
}



// Si l'utilisateur tente de réserver une ressource non disponible
if ($error_booking_room_out == 'yes') {
    print_header($day, $month, $year, $area);
    echo "<H2>" . get_vocab("norights") . "</H2>";
    echo "<p><b>" . get_vocab("tentative_reservation_ressource_indisponible") . "</b></p>";
    echo $return_link;
    include "include/trailer.inc.php";
    die();
}

if(strlen($err))
{
    print_header($day, $month, $year, $area);

    echo "<H2>" . get_vocab("sched_conflict") . "</H2>";
    if(!isset($hide_title))
    {
        echo get_vocab("conflict");
        echo "<UL>";
    }
    echo $err;

    if(!isset($hide_title))
        echo "</UL>";
        // possibilité de supprimer la (les) réservation(s) afin de valider la nouvelle réservation.
        if(authGetUserLevel(getUserName(),$area,'area') >= 4)
        echo "<center><table border=\"1\" cellpadding=\"10\" cellspacing=\"1\"><tr><td class='avertissement'><h3><a href='".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."&amp;del_entry_in_conflict=yes'>".get_vocab("del_entry_in_conflict")."</a></h4></td></tr></table></center><br>";

}
// Retour au calendrier





echo "<a href=\"$returl\">".get_vocab('returncal')."</a><p>";

include "include/trailer.inc.php"; ?>