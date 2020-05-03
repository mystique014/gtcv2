<?php
#########################################################################
#                         edit_entry_user.php                           #
#                                                                       #
#          Interface d'édition d'une réservation d'un utilisateur       #
#                                                                       #
#                  Dernière modification : 01/11/2009                 #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2006 Stéphane Duchemin
 * Affichage du nom de domaine dans le cas où il y a plusieurs Domaines
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

include "include/admin.inc.php";
include "include/mrbs_sql.inc.php";
include "include/misc.inc.php";
include "include/lib.inc.php";


// Initialisation
if (isset($_GET["id"]))
{
  $id = $_GET["id"];
  settype($id,"integer");
}
else $id = NULL;

$period = isset($_GET["period"]) ? $_GET["period"] : NULL;
if (isset($period)) settype($period,"integer");
if (isset($period)) $end_period = $period;

$edit_type = isset($_GET["edit_type"]) ? $_GET["edit_type"] : NULL;
if(!isset($edit_type)) $edit_type = "";

// si $edit_type = "series", cela signifie qu'on édite une "périodicité"
$page = verif_page();
if (isset($_GET["hour"]))
{
  $hour = $_GET["hour"];
  settype($hour,"integer");
  if ($hour < 10) $hour = "0".$hour;
}
else $hour = NULL;

if (isset($_GET["minute"]))
{
  $minute = $_GET["minute"];
  settype($minute,"integer");
  if ($minute < 10) $minute = "0".$minute;
}
else $minute = NULL;

$rep_num_weeks='';

global $twentyfourhour_format;
//Si nous ne savons pas la date, nous devons la créer

if(!isset($day) or !isset($month) or !isset($year))
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
}

// s'il s'agit d'une modification, on récupère l'id de l'area et l'id de la room
if (isset($id))
{
  if ($info = mrbsGetEntryInfo($id))
    {
      $area  = mrbsGetRoomArea($info["room_id"]);
      $room = $info["room_id"];
    }
  else
    {
      $area = "";
      $room = "";
    }
}

if(empty($area))  $area = get_default_area();

// Récupération des données concernant l'affichage du planning du domaine
get_planning_area_values($area);

// Récupération d'info sur la rerssource
$type_affichage_reser = grr_sql_query1("select type_affichage_reser from grr_room where id='".$room."'");
$delais_option_reservation  = grr_sql_query1("select delais_option_reservation from grr_room where id='".$room."'");

//Vérification de la présence de réservations
if (check_begin_end_bookings($day, $month, $year))
{
    if (($authentification_obli==0) and (!isset($_SESSION['login']))) $type_session = "no_session";
    else $type_session = "with_session";
    showNoBookings($day, $month, $year, $area,"",$type_session);
    exit();
}

$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
//Vérification des droits d''accès

if(authGetUserLevel(getUserName(),-1) < 2)
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

if(authUserAccesArea($_SESSION['login'], $area)==0)
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

if(UserRoomMaxBooking(getUserName(), $room, 1) == 0)
{
    showAccessDeniedMaxBookings($day, $month, $year, $area, $room, $back);
    exit();
}
//Vérification si l'on édite une périodicité ($edit_type = "series") ou bien une réservation simple


/*
* Cette page peut ajouter ou modifier une réservation
* Nous devons savoir:
*  - Le nom de la personne qui a réservé
*  - La description de la réservation
*  - La Date (option de sélection pour le jour, mois, année)
*  - L'heure
*  - La durée
*  - Le statut de la réservation en cours
* Premièrement nous devons savoir si c'est une nouvelle réservation ou bien une modification
* Si c'est une modification, nous devons reprendre toute les informations de cette réservation
* Si l'ID est présente, c'est une modification
*/

if (isset($id))
{
    $sql = "select name, create_by, description, start_time, end_time,
            type, room_id, entry_type, repeat_id, option_reservation from grr_entry where id=$id";
    $res = grr_sql_query($sql);
    if (! $res) fatal_error(1, grr_sql_error());
    if (grr_sql_count($res) != 1) fatal_error(1, get_vocab('entryid') . $id . get_vocab('not_found'));
    $row = grr_sql_row($res, 0);
    grr_sql_free($res);
    $name        = $row[0];
    $create_by   = $row[1];
    $description = $row[2];

    $start_day   = strftime('%d', $row[3]);
    $start_month = strftime('%m', $row[3]);
    $start_year  = strftime('%Y', $row[3]);
    $start_hour  = strftime('%H', $row[3]);
    $start_min   = strftime('%M', $row[3]);

    $end_day   = strftime('%d', $row[4]);
    $end_month = strftime('%m', $row[4]);
    $end_year  = strftime('%Y', $row[4]);
    $end_hour  = strftime('%H', $row[4]);
    $end_min   = strftime('%M', $row[4]);


    $duration    = $row[4]-$row[3];
    $type        = $row[5];
    $room_id     = $row[6];
    $entry_type  = $row[7];
    $rep_id      = $row[8];
    $option_reservation  = $row[9];
    $modif_option_reservation = 'n';
    if($entry_type >= 1)
    // il s'agit d'une réservation à laquelle est associée une périodicité
    {
        $sql = "SELECT rep_type, start_time, end_date, rep_opt, rep_num_weeks
                FROM grr_repeat WHERE id='".protect_data_sql($rep_id)."'";

        $res = grr_sql_query($sql);
        if (! $res) fatal_error(1, grr_sql_error());
        if (grr_sql_count($res) != 1) fatal_error(1, get_vocab('repeat_id') . $rep_id . get_vocab('not_found'));

        $row = grr_sql_row($res, 0);
        grr_sql_free($res);

        $rep_type = $row[0];

        if($edit_type == "series")
        // on edite la périodicité associée à la réservation et non la réservation elle-même
        {
            $start_day   = (int)strftime('%d', $row[1]);
            $start_month = (int)strftime('%m', $row[1]);
            $start_year  = (int)strftime('%Y', $row[1]);

            $rep_end_day   = (int)strftime('%d', $row[2]);
            $rep_end_month = (int)strftime('%m', $row[2]);
            $rep_end_year  = (int)strftime('%Y', $row[2]);

            switch($rep_type)
            {
                case 2:
                    // semaine
                case 6:
                    // Toutes les n-semaines
                    $rep_day[0] = $row[3][0] != "0";
                    $rep_day[1] = $row[3][1] != "0";
                    $rep_day[2] = $row[3][2] != "0";
                    $rep_day[3] = $row[3][3] != "0";
                    $rep_day[4] = $row[3][4] != "0";
                    $rep_day[5] = $row[3][5] != "0";
                    $rep_day[6] = $row[3][6] != "0";

                    if ($rep_type == 6)
                    {
                        $rep_num_weeks = $row[4];
                    }

                    break;

                default:
                    $rep_day = array(0, 0, 0, 0, 0, 0, 0);
            }
        }
        else
        // on edite la réservation elle-même et non pas de périodicité associée
        {
            $rep_type     = $row[0];
            $rep_end_date = strftime($dformat,$row[2]);
            $rep_opt      = $row[3];
            if ($rep_type == 6)
            {
                $rep_num_weeks = $row[4];
            }

        }
    }
}
else
{
  //Ici, c'est une nouvelle réservation, les donnée arrivent quelque soit le boutton selectionné.
    $edit_type   = "series";
    $name        = "";
    $create_by   = getUserName();
    $description = "";
    $start_day   = $day;
    $start_month = $month;
    $start_year  = $year;
    $start_hour  = $hour;
    (isset($minute)) ? $start_min = $minute : $start_min ='00';

    $end_day   = $day;
    $end_month = $month;
    $end_year  = $year;
    $end_hour  = $hour;
    (isset($minute)) ? $end_min = $minute : $end_min ='00';

    if ($enable_periods == 'y')
        $duration    = 60;
    else
        $duration    = 60 * 60;
    $type        = "";
    $room_id     = $room;
    $id = 0;
    $rep_id        = 0;
    $rep_type      = 0;
    $rep_end_day   = $day;
    $rep_end_month = $month;
    $rep_end_year  = $year;
    $rep_day       = array(0, 0, 0, 0, 0, 0, 0);
//    $option_reservation = mktime(0,0,0,date("m"),date("d"),date("Y"));
    $option_reservation = -1;
    $modif_option_reservation = 'y';

    // Pour gérer le retour.
    $typelist = array('area','room','day','month','year','minute','hour','day','name','type','description',
              'end_day','end_month','end_year','end_hour','end_minute','rep_type','rep_num_weeks',
              'rep_month','rep_end_day','rep_end_month','rep_end_year','rep_id','edit_type',
              'type_affichage_reser');

    foreach ($typelist as $element)
      {
    if ( isset($_GET[$element]))
      {
        $$element = $_GET[$element];
      }
      }
}
//Transforme $duration en un nombre entier
if ($enable_periods=='y')
    toPeriodString($start_min, $duration, $dur_units);
else
    toTimeString($duration, $dur_units);
//Maintenant nous connaissons tous les champs
if(!getWritable($create_by, getUserName(),$id))
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit;
}

// On cherche s'il y a d'autres domaines auxquels l'utilisateur a accès
$nb_areas = 0;
$sql = "select id, area_name from grr_area";
$res = grr_sql_query($sql);
$allareas_id = array();
if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
{
  array_push($allareas_id,$row[0]);
  if (authUserAccesArea(getUserName(),$row[0])==1)
    {

      $nb_areas++;
    }
}

print_header($day, $month, $year, $area);

?>
<script type="text/javascript" src="./functions.js" language="javascript"></script>
<body onLoad="document.forms['main'].name.focus()">

<SCRIPT type="text/javascript" LANGUAGE="JavaScript">
//Vérification de la forme
function check_1 ()
{
    if (isIE) menu = document.all['menu2'];
    if (isNN6) menu = document.getElementById('menu2');
    if (!document.forms["main"].rep_type[2].checked)
    {
      document.forms["main"].elements['rep_day[0]'].checked=false;
      document.forms["main"].elements['rep_day[1]'].checked=false;
      document.forms["main"].elements['rep_day[2]'].checked=false;
      document.forms["main"].elements['rep_day[3]'].checked=false;
      document.forms["main"].elements['rep_day[4]'].checked=false;
      document.forms["main"].elements['rep_day[5]'].checked=false;
      document.forms["main"].elements['rep_day[6]'].checked=false;
      menu.style.display = "none";

   } else {
      menu.style.display = "";
   }
}
function check_2 ()
{
   document.forms["main"].rep_type[2].checked=true;
   check_1 ();
}

function check_3 ()
{
   document.forms["main"].rep_type[3].checked=true;
}

function validate_and_submit ()
{
  if  (document.forms["main"].description.value=='0')
  {
     alert("<?php echo get_vocab("choose_a_partenaire") ?>");
     return false;
  }
  <?php if($enable_periods!='y') { ?>
    h = parseInt(document.forms["main"].hour.value);
    m = parseInt(document.forms["main"].minute.value);
    if(h > 23 || m > 59)
    {
      alert ("<?php echo get_vocab('you_have_not_entered') . '\n' . get_vocab('valid_time_of_day') ?>");
      return false;
    }
  <?php } ?>
  


    <?php
    if($edit_type == "series")
    {     ?>
  i1 = parseInt(document.forms["main"].id.value);
  i2 = parseInt(document.forms["main"].rep_id.value);
  n = parseInt(document.forms["main"].rep_num_weeks.value);
  if ((document.forms["main"].elements['rep_day[0]'].checked || document.forms["main"].elements['rep_day[1]'].checked || document.forms["main"].elements['rep_day[2]'].checked || document.forms["main"].elements['rep_day[3]'].checked || document.forms["main"].elements['rep_day[4]'].checked || document.forms["main"].elements['rep_day[5]'].checked || document.forms["main"].elements['rep_day[6]'].checked) && (!document.forms["main"].rep_type[2].checked))
  {
    alert("<?php echo get_vocab('no_compatibility_with_repeat_type'); ?>");
    return false;
  }
  if ((!document.forms["main"].elements['rep_day[0]'].checked && !document.forms["main"].elements['rep_day[1]'].checked && !document.forms["main"].elements['rep_day[2]'].checked && !document.forms["main"].elements['rep_day[3]'].checked && !document.forms["main"].elements['rep_day[4]'].checked && !document.forms["main"].elements['rep_day[5]'].checked && !document.forms["main"].elements['rep_day[6]'].checked) && (document.forms["main"].rep_type[2].checked))
  {
    alert("<?php echo get_vocab('choose_a_day'); ?>");
    return false;
  }
<?php
}
?>
// would be nice to also check date to not allow Feb 31, etc...
document.forms["main"].submit();

  return true;
}
</SCRIPT>

<?php
if ($id==0) $A = get_vocab("addentry"); else $A = get_vocab("editentry");
$B = get_vocab("nameuser").get_vocab("deux_points");
//$C = htmlspecialchars($name);
$D = get_vocab("adversaire");
$E = htmlspecialchars ( $description );
$F = get_vocab("date").get_vocab("deux_points");
$G = genDateSelectorForm("", $start_day, $start_month, $start_year,"");
$name = $_SESSION['nom']." ".$_SESSION['prenom'];

//Determine l'ID de "area" de la "room"
$sql = "select area_id from grr_room where id=$room_id";
$res = grr_sql_query($sql);
$row = grr_sql_row($res, 0);
$area_id = $row[0];
$type= 'A';


echo "<h2>$A</H2>
<FORM name=\"main\" action=\"edit_entry_handler.php\" method=\"get\">

<input type=hidden name=room value=\"$room\">
<input type=hidden name=area value=\"$area_id\">
<input type=hidden name=name value=\"$name\">
<input type=hidden name=type value=\"$type\">
<input type=hidden name=rooms[] value=\"$room\">
<input type=hidden name=duration value=\"$duration\">
<input type=hidden name=minute value=\"$start_min\">


<TABLE border=\"0\" class=\"EditEntryTable\">
<TR><TD class=\"E\"><B>$B</B></TD></TR>
<TR><TD class=\"CL\"><B>$name</B></TD></TR>
<tr><td>\n";
// Champs additionneles : on récupère les données de la réservation si il y en a
$overload_data = mrbsEntryGetOverloadDesc($id);
// Boucle sur les areas
foreach ($allareas_id as $idtmp) {
    $overload_fields = mrbsOverloadGetFieldslist($idtmp);
    foreach ($overload_fields as $fieldname=>$fieldtype) {
        if ($idtmp == $area_id)
            echo "<table style=\"display:''\" id=\"id_".$idtmp."_".$overload_fields[$fieldname]["id"]."\">\n";
        else
            echo "<table style=\"display:none\" id=\"id_".$idtmp."_".$overload_fields[$fieldname]["id"]."\">\n";
        echo "<TR><TD class=E><b>".$fieldname."</b></TD></TR>\n";
        if (isset($overload_data[$fieldname]))
            $data = $overload_data[$fieldname];
        else
            $data = "";
        if ($overload_fields[$fieldname]["type"] == "textarea" )
            echo "<TR><TD><TEXTAREA COLS=80 ROWS=2 name=\"addon_".$overload_fields[$fieldname]["id"]."\">$data</TEXTAREA></TD></TR>\n";
        else
            echo "<TR><TD><INPUT size=80 type=text name=\"addon_".$overload_fields[$fieldname]["id"]."\" value=\"$data\"></TD></TR>\n";
        echo "</table>\n";
    }
}
echo "</td></tr>\n";

// Début réservation
// Choix de l'adversaire
echo "<TR><TD class=\"E\"><B>".get_vocab("adversaire")."</B></TD></TR>\n";
echo "<TR><TD class=\"CL\"><SELECT name=\"description\" size=\"1\">\n";
echo "<OPTION VALUE='0'>".get_vocab("choose")."\n";
$sql = "SELECT login, nom, prenom, etat, statut, group_id FROM grr_utilisateurs 
WHERE statut != 'administrateur' AND login != '".$create_by."' AND etat = 'actif'
ORDER BY nom";
$res = grr_sql_query($sql);

if ($res)
  for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
      // La requête sql précédente laisse passer les cas où un type est non valide
      // dans le domaine concerné ET au moins dans un autre domaine, d'où le test suivant
     // $test = grr_sql_query1("select id_type from grr_j_type_area where id_type = '".$row[2]."' and id_area='".$area_id."'");
     // if ($test == -1)
	 //test si l'utilisateur possède le droit de réserver un créneau pour les championnats individuels, solo ou invite si oui on affiche dans la liste 'championnat individuel' ou/et 'solo' ou/et 'invite'
		if ( $row[0] == 'championnat')
		{
			$user_champio = grr_sql_query1("select champio from grr_utilisateurs where login = '$create_by'");
			if ($user_champio == 'actif')
				{
				echo "<OPTION VALUE=\"".$row[1]." ".$row[2]."\"";
				if ($description == $row[0]) echo " SELECTED";
				echo " >".$row[1]."  ".$row[2]."</option>\n";
				}
		} elseif ($row[0]  == 'solo')
		{
		// test si l'adversaire peut jouer en mode solo
			$user_solo = grr_sql_query1("select solo from grr_utilisateurs where login = '$create_by'");
			if ($user_solo == 'actif')
				{
				echo "<OPTION VALUE=\"".$row[1]." ".$row[2]."\"";
				if ($description == $row[0]) echo " SELECTED";
				echo " >".$row[1]."  ".$row[2]."</option>\n";
				}
		} elseif ( $row[0] == 'invite')
		{
		// test si l'adversaire peut jouer avec un invite	
			$user_inviteactif = grr_sql_query1("select inviteactif from grr_utilisateurs where login = '$create_by'");
			if ($user_inviteactif == 'actif')
				{	
				echo "<OPTION VALUE=\"".$row[1]." ".$row[2]."\"";
				if ($description == $row[0]) echo " SELECTED";
				echo " >".$row[1]."  ".$row[2]."</option>\n";
				}
		} elseif ( $row[5] == $_SESSION['group_id'])
		// test si l'adversaire fait partie du même groupe 
		{
		echo "<OPTION VALUE=\"".$row[1]." ".$row[2]."\"";
				if ($description == $row[0]) echo " SELECTED";
				echo " >".$row[1]."  ".$row[2]."</option>\n";
		} else 
		{
		}
	}
 		
echo "<TR><TD class=\"E\"><B>$F</B></TD></TR>
<TR><TD class=\"CL\">$G";

// Heure ou créneau de début de réservation
if ($enable_periods=='y')
{
  echo "<B>".get_vocab("period")."\n";
  echo "<SELECT NAME=\"period\">";
  foreach ($periods_name as $p_num => $p_val)
    {
      echo "<OPTION VALUE=$p_num";
      if( ( isset( $period ) && $period == $p_num ) || $p_num == $start_min)
    echo " SELECTED";
      echo ">$p_val";
    }
  echo "</SELECT>\n";
}
else
{
  echo "<INPUT NAME=\"hour\" SIZE=2 VALUE=\"";
  if (!$twentyfourhour_format && ($start_hour > 12)) echo ($start_hour - 12);
  else echo $start_hour;
echo"\" MAXLENGTH=2><B>".get_vocab("hours")."</B>\n";
// echo "<B>$start_hour ".get_vocab("hours")."</B>\n";
//<INPUT NAME=\"minute\" SIZE=2 VALUE=\"".$start_min."\" MAXLENGTH=2>";
if ($start_min > 0) {
echo $start_min;
echo "<B>&nbsp;".get_vocab("minutes")."</B>\n";
}
  if (!$twentyfourhour_format)
    {
      $checked = ($start_hour < 12) ? "checked" : "";
     echo "<INPUT NAME=\"ampm\" type=\"radio\" value=\"am\" $checked>".date("a",mktime(1,0,0,1,1,1970));
      $checked = ($start_hour >= 12) ? "checked" : "";
      echo "<INPUT NAME=\"ampm\" type=\"radio\" value=\"pm\" $checked>".date("a",mktime(13,0,0,1,1,1970));
    }
}

echo "</TD></TR>";


if ($type_affichage_reser == 0)
{
  // Durée
  //echo "<TR><TD class=\"E\"><B>".get_vocab("duration")."</B></TD></TR>\n";
  //echo "<TR><TD class=\"CL\"><INPUT NAME=\"duration\" SIZE=\"7\" VALUE=\"".$duration."\">";
  //echo "<SELECT name=\"dur_units\" size=\"1\">\n";
  //if($enable_periods == 'y') $units = array("periods", "days");
  //else $units = array("minutes", "hours", "days", "weeks");

  //while (list(,$unit) = each($units))
  //  {
  //    echo "<OPTION VALUE=$unit";
  //    if ($dur_units ==  get_vocab($unit)) echo " SELECTED";
  //    echo ">".get_vocab($unit)."</OPTION>\n";
  //  }
 // echo "</SELECT>\n";

  $fin_jour = $eveningends;
  $minute = $resolution/60;
  if ($minute == 60)
    {
      $fin_jour = $fin_jour+1;
      $af_fin_jour = $fin_jour." H";
    }
  else if ($minute != 0) $af_fin_jour = $fin_jour." H ".$minute;

//  echo "<INPUT name=\"all_day\" TYPE=\"checkbox\" value=\"yes\" />".get_vocab("all_day");
//  if ($enable_periods!='y') echo " (".$morningstarts." H - ".$af_fin_jour.")";
//  echo "</TD></TR>\n";

}
else
{
  // Date de début de réservation
  echo "<TR><TD class=\"E\"><B>".get_vocab("fin_reservation").get_vocab("deux_points")."</B></TD></TR>\n";
  echo "<TR><TD class=\"CL\" >";
  echo "<table border = 1><tr><td>\n";
  genDateSelector("end_", $end_day, $end_month, $end_year,"");
  echo "</TD>";
  // Heure ou créneau de fin de réservation
  if ($enable_periods=='y')
    {
      echo "<TD class=\"E\"><B>".get_vocab("period")."</B></TD>\n";
      echo "<TD class=\"CL\">\n";
      echo "<SELECT NAME=\"end_period\">";
      foreach ($periods_name as $p_num => $p_val)
    {
      echo "<OPTION VALUE=$p_num";
      if( ( isset( $end_period ) && $end_period == $p_num ) || ($p_num+1) == $end_min)
        echo " SELECTED";
      echo ">$p_val";
    }
      echo "</SELECT>\n</TD>\n";
    }
  else
    {
      echo "<TD CLASS=E><B>".get_vocab("time")."</B></TD>\n";
      echo "<TD CLASS=CL><INPUT NAME=\"end_hour\" SIZE=2 VALUE=\"";

      if (!$twentyfourhour_format && ($end_hour > 12))  echo ($end_hour - 12);
      else echo $end_hour;

      echo "\" MAXLENGTH=2></td><td>:</td><td><INPUT NAME=\"end_minute\" SIZE=2 VALUE=\"".$end_min."\" MAXLENGTH=2>";
      if (!$twentyfourhour_format)
    {
      $checked = ($end_hour < 12) ? "checked" : "";
      echo "<INPUT NAME=\"ampm\" type=\"radio\" value=\"am\" $checked>".date("a",mktime(1,0,0,1,1,1970));
      $checked = ($end_hour >= 12) ? "checked" : "";
      echo "<INPUT NAME=\"ampm\" type=\"radio\" value=\"pm\" $checked>".date("a",mktime(13,0,0,1,1,1970));
    }
      echo "</TD>";
    }
  echo "</TR></table>\n</td></tr>";

}

// Option de réservation
if (($delais_option_reservation > 0)
    and (($modif_option_reservation == 'y')
     or ((($modif_option_reservation == 'n')
          and ($option_reservation!=-1)) ) ))
{
  $day   = date("d");
  $month = date("m");
  $year  = date("Y");
  echo "<TR bgcolor=\"#FF6955\"><TD class=\"E\"><B>".get_vocab("reservation_a_confirmer_au_plus_tard_le");

  if ($modif_option_reservation == 'y')
    {
      echo "<SELECT name=\"option_reservation\" size=\"1\">\n";
      $k = 0;
      $selected = 'n';
      $aff_options = "";
      while ($k < $delais_option_reservation+1)
    {
      $day_courant = $day+$k;
      $date_courante = mktime(0,0,0,$month,$day_courant,$year);
      $aff_date_courante = time_date_string_jma($date_courante,$dformat);
      $aff_options .= "<option value = \"".$date_courante."\" ";
      if ($option_reservation == $date_courante)
        {
          $aff_options .= " selected ";
          $selected = 'y';
        }
      $aff_options .= ">".$aff_date_courante."</option>\n";
      $k++;
    }
      echo "<option value = \"-1\">".get_vocab("Reservation confirmee")."</option>\n";
      if (($selected == 'n') and ($option_reservation != -1))
    {
      echo "<option value = \"".$option_reservation."\" selected>".time_date_string_jma($option_reservation,$dformat)."</option>\n";
    }
      echo $aff_options;
      echo "</select>";
    }
  else
    {
      echo "<input type=\"hidden\" name=\"option_reservation\" value=\"".$option_reservation."\" />&nbsp;<b>".
        time_date_string_jma($option_reservation,$dformat)."</b>\n";
      echo "<br><input type=\"checkbox\" name=\"confirm_reservation\" value=\"y\" />".get_vocab("confirmer reservation")."\n";
    }
  echo "<br>".get_vocab("avertissement_reservation_a_confirmer")."</B>\n";
  echo "</TD></TR>\n";

}
/*---------------------------------------------------------------------------------------début de la suppression
// Domaines. Javascript important s'il y a plusieurs domaines.
if ($nb_areas > 1)
{
  ?>
 <script type="text/javascript" language="JavaScript">
    <!--
function changeRooms( formObj )
{
    areasObj = eval( "formObj.areas" );
    area = areasObj[areasObj.selectedIndex].value
    roomsObj = eval( "formObj.elements['rooms[]']" )
    typeObj = eval( "formObj.elements['type']" )

    // remove all entries
    for (i=0; i < (roomsObj.length); i++) {
      roomsObj.options[i] = null
    }
    for (i=0; i < (typeObj.length); i++)
    {
      typeObj.options[i] = null
    }

    // add entries based on area selected
    switch (area){
<?php
    // get the area id for case statement
    if ($enable_periods == 'y')
        $sql = "select id, area_name from grr_area where id='".$area."' order by area_name";
    else
        $sql = "select id, area_name from grr_area where enable_periods != 'y' order by area_name";
    $res = grr_sql_query($sql);

    if ($res)
    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
    if (authUserAccesArea(getUserName(),$row[0])==1)
      {
        print "      case \"".$row[0]."\":\n";
        // get rooms for this area
        $sql2 = "select id, room_name from grr_room where area_id='".$row[0]."' order by room_name";
            $res2 = grr_sql_query($sql2);

        if ($res2) for ($j = 0; ($row2 = grr_sql_row($res2, $j)); $j++)
        print "        roomsObj.options[$j] = new Option(\"".str_replace('"','\\"',$row2[1])."\",".$row2[0] .")\n";

        print "        typeObj.options[0] = new Option(\"".get_vocab("choose")."\",0)\n";
        $sql3 = "SELECT DISTINCT t.type_name, t.type_letter, t.id FROM grr_type_area t
        LEFT JOIN grr_j_type_area j on j.id_type=t.id
        WHERE (j.id_area  IS NULL or j.id_area != '".$row[0]."')
        ORDER BY t.order_display";
        $res3 = grr_sql_query($sql3);

        if ($res3)
        for ($j = 0; ($row3 = grr_sql_row($res3, $j)); $j++)
        {
          $test = grr_sql_query1("select id_type from grr_j_type_area where id_type = '".$row3[2]."' and id_area='".$row[0]."'");
          if ($test == -1)
        print "        typeObj.options[".($j+1)."] = new Option(\"".str_replace('"','\\"',$row3[0])."\",\"".$row3[1] ."\")\n";
        }

        // select the first entry by default to ensure
        // that one room is selected to begin with
        print "        roomsObj.options[0].selected = true\n";
        print "        typeObj.options[0].selected = true\n";
        // Affichage des champs additionnels
        foreach ($allareas_id as $idtmp) {
            $overload_fields = mrbsOverloadGetFieldslist($idtmp);
                foreach ($overload_fields as $fieldname=>$fieldtype) {
                    if ($idtmp == $row[0])
                        echo "        id_".$idtmp."_".$overload_fields[$fieldname]["id"].".style.display = \"\"\n";
                    else
                        echo "        id_".$idtmp."_".$overload_fields[$fieldname]["id"].".style.display = \"none\"\n";
                 }
        }
        print "        break\n";
      }
    }
?>
    } //switch



}

// create area selector if javascript is enabled as this is required
// if the room selector is to be updated.

this.document.writeln("<tr><td class=E><b><?php echo get_vocab("match_area").get_vocab("deux_points")?></b></td></TR><TR><td class=CL valign=top >");
this.document.writeln("<select name=\"areas\" onChange=\"changeRooms(this.form)\" >");
<?php
    // get list of areas
    if ($enable_periods == 'y')
      $sql = "select id, area_name from grr_area where id='".$area."' order by area_name";
    else
      $sql = "select id, area_name from grr_area where enable_periods != 'y' order by area_name";

 $res = grr_sql_query($sql);
 if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
   {
     if (authUserAccesArea(getUserName(),$row[0])==1) {

       $selected = "";
       if ($row[0] == $area) $selected = "SELECTED";
       print "this.document.writeln(\"<option $selected value=\\\"".$row[0]."\\\">".$row[1]."\")\n";
     }
   }
     ?>
this.document.writeln("</select>");
this.document.writeln("</td></tr>");

// -->
</script>
<?php
} # if $num_areas
*///------------------------------------------------------------------------------------fin de la suppression


// *****************************************
// Edition de la partie ressources
// *****************************************

echo "\n<!-- ************* Ressources edition ***************** -->\n";
//Affichage  du domaine
$sql = "select area_name from grr_area where id='".$area."'";
$result = grr_sql_query($sql);
$line = mysqli_fetch_row ($result);
    $area_name = $line[0];
echo "<tr><td class=\"E\"><b>".get_vocab("match_area").get_vocab("deux_points")."</b></td></TR>\n";
echo "<TR><TD class=\"CL\"><B>$area_name <B></TD></TR>";
//echo "</td></tr>\n";
echo "<tr><td class=\"E\"><b>".get_vocab("court").get_vocab("deux_points")."</b></td></TR>\n";
//echo "<TR><td class=\"CL\" valign=\"top\"><table border=0><tr><td><select name=\"rooms[]\" multiple>";

//Sélection de la "room" dans l'"area"
//$sql = "select id, room_name, description from grr_room where area_id=$area_id order by order_display,room_name";
//$res = grr_sql_query($sql);
//if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
//{
//  $selected = "";
// if ($row[0] == $room_id) $selected = "SELECTED";
//  echo "<option $selected value=\"".$row[0]."\">".$row[1];
//}
echo "<TR><TD class=\"CL\"><B>Court N $room</B></TD></TR>";

//echo "</select></td><td>".get_vocab("ctrl_click")."</td></tr></table>\n";
//echo "</td></tr>\n";

// Type de réservation
//echo "<TR><TD class=\"E\"><B>".get_vocab("type").get_vocab("deux_points")."</B></TD></TR>\n";
//echo "<TR><TD class=\"CL\"><SELECT name=\"type\" size=\"1\">\n";
//echo "<OPTION VALUE='0'>".get_vocab("choose")."\n";
//$sql = "SELECT DISTINCT t.type_name, t.type_letter, t.id FROM grr_type_area t
//LEFT JOIN grr_j_type_area j on j.id_type=t.id
//WHERE (j.id_area  IS NULL or j.id_area != '".$area_id."')
//ORDER BY t.order_display";
//$res = grr_sql_query($sql);

//if ($res)
//  for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
//    {
      // La requête sql précédente laisse passer les cas où un type est non valide
      // dans le domaine concerné ET au moins dans un autre domaine, d'où le test suivant
//      $test = grr_sql_query1("select id_type from grr_j_type_area where id_type = '".$row[2]."' and id_area='".$area_id."'");
//      if ($test == -1)
//    {
//      echo "<OPTION VALUE=\"".$row[1]."\" ";
//      if ($type == $row[1]) echo " SELECTED";
//      echo " >".$row[0]."</option>\n";
//    }
//    }

//echo "</SELECT></TD></TR>\n";


// *****************************************
// Edition de la partie additionnelle
//
// *****************************************
echo "\n<!-- ************* Edition des champs additionnels***************** -->\n";

// on récupère la liste des domaines et on génère tous les formulaires.
$sql = "select id from grr_area;";
$res = grr_sql_query($sql);


// Dans le cas d'une nouvelle réservation, ou bien si on édite une réservation existante

// *****************************************
// Edition de la partie périodique
//
// *****************************************
echo "\n<!-- ************* Periodic edition ***************** -->\n";



if($edit_type == "series")
{
  

  echo "<TR><TD><TABLE border=0 style=\"display:none\" id=\"menu1\" width=100%>\n ";

  echo "<TR><TD CLASS=F><B>".get_vocab("rep_type")."</B></TD></TR><TR><TD CLASS=CL>\n";


  echo "<table border=0  width=100% >\n";

  for($i = 0; $i<6 ; $i++)
    {
      if ($i != 5)
    {
      echo "<TR><TD><INPUT NAME=\"rep_type\" TYPE=\"RADIO\" VALUE=\"" . $i . "\"";
      if($i == $rep_type) echo " CHECKED";
      // Compatibilité avec mrbs
      if(($i == 2) and ($rep_type==6)) echo " CHECKED";
      if(($i == 3) and ($rep_type==5)) echo " CHECKED";
      echo " ONCLICK=\"check_1()\"></td><td>";
      // Dans le cas des semaines et des mois, on affichera plutôt un menu déroulant
      if (($i != 2) and ($i != 3))  echo get_vocab("rep_type_$i");
      echo "\n";
      // Dans le cas d'une périodicité semaine, on précise toutes les n-semaines
      if ($i == '2')
        {
          echo "<select name=\"rep_num_weeks\" size=\"1\" onfocus=\"check_2()\" onclick=\"check_2()\">\n";
          echo "<option value=1 >".get_vocab("every week")."</option>\n";
          $weeklist = array("unused","every week","week 1/2 ","week 1/3","week 1/4","week 1/5");

          for ( $weekit=2 ; $weekit<6 ; $weekit++ )
        {
          echo "<option value=$weekit ";
          if ($rep_num_weeks == $weekit) echo " selected";
          echo ">".get_vocab($weeklist[$weekit])."</option>\n";
        }
          echo "</select>\n";

        }
      if ($i == '3')
        {
          $monthrep3 = "";
          $monthrep5 = "";
          if ($rep_type == 3) $monthrep3 = " selected ";
          if ($rep_type == 5) $monthrep5 = " selected ";

          echo "<select name=\"rep_month\" size=\"1\" onfocus=\"check_3()\" onclick=\"check_3()\">\n";
          echo "<option value=3 $monthrep3>".get_vocab("rep_type_3")."</option>\n";
          echo "<option value=5 $monthrep5>".get_vocab("rep_type_5")."</option>\n";
          echo "</select>\n";
        }
    }

    }

  echo "</tr></table>\n\n";
  echo "<!-- ***** Fin de périodidité ***** -->\n";

  echo "</TD></TR>";
  echo "\n<TR><TD>\n";

  echo "<TR><TD CLASS=F><B>".get_vocab("rep_end_date")."</B></TD></TR>\n";

  echo "<TR><TD CLASS=CL>";
  genDateSelector("rep_end_", $rep_end_day, $rep_end_month, $rep_end_year,"");
  echo "</TD></TR></table>\n";

  echo "<TABLE style=\"display:none\" id=\"menu2\" width=100%>\n";

  echo "<TR><TD CLASS=F><B>".get_vocab("rep_rep_day")."</B>".get_vocab("rep_for_weekly")."</TD></TR>\n";
  echo "<TR><TD CLASS=CL>";

  //Affiche les checkboxes du jour en fonction de la date de début de semaine.
  for ($i = 0; $i < 7; $i++)
    {
      $wday = ($i + $weekstarts) % 7;
      echo "<INPUT NAME=\"rep_day[$wday]\" TYPE=CHECKBOX";
      if ($rep_day[$wday]) echo " CHECKED";
      echo " ONCLICK=\"check_1()\">" . day_name($wday) . "\n";
    }

  echo "</TD></TR>\n</TABLE>\n";

}
else
{
    // cas 1 : on édite une réservation simple

  $key = "rep_type_" . (isset($rep_type) ? $rep_type : "0");

  if (isset($rep_type)) echo "<tr><td class=\"E\"><b>".get_vocab('rep_type')."</b></td><td class=\"CL\">".get_vocab($key)."</td></tr>\n";

  if(isset($rep_type) && ($rep_type != 0))
    {
      $opt = "";
      if ($rep_type == 2)
        {
      //Affiche les checkboxes du jour en fonction de la date de début de semaine.
      for ($i = 0; $i < 7; $i++)
            {
          $wday = ($i + $weekstarts) % 7;
          if ($rep_opt[$wday]) $opt .= day_name($wday) . " ";
            }
        }
      if($opt)
    echo "<tr><td class=\"CR\"><b>".get_vocab('rep_rep_day')."</b></td><td class=\"CL\">$opt</td></tr>\n";
      if ($rep_type == 6)
    echo "<tr><td class=\"CR\"><b>".get_vocab('rep_num_weeks').get_vocab("deux_points")."</b></td><td class=\"CL\">$rep_num_weeks</td></tr>\n";
      echo "<tr><td class=\"CR\"><b>".get_vocab('rep_end_date')."</b></td><td class=\"CL\">$rep_end_date</td></tr>\n";
    }

}






echo "<center><INPUT TYPE='button' VALUE=".get_vocab("save")." ONCLICK=\"validate_and_submit()\"></center>";


//echo "<script type=\"text/javascript\">alert('".get_vocab("message_records_invite")."');</script>";
?>
<INPUT TYPE=HIDDEN NAME="returl"    VALUE="<?php echo $back ?>">
<INPUT TYPE=HIDDEN NAME="create_by" VALUE="<?php echo $create_by?>">
<INPUT TYPE=HIDDEN NAME="rep_id"    VALUE="<?php echo $rep_id?>">
<INPUT TYPE=HIDDEN NAME="edit_type" VALUE="<?php echo $edit_type?>">
<INPUT TYPE=HIDDEN NAME="page" VALUE="<?php echo $page?>">
<INPUT TYPE=HIDDEN NAME="room_back" VALUE="<?php echo $room_id?>">
<INPUT TYPE=HIDDEN NAME="back_2" VALUE="<?php echo $back?>">
<?php if ($id!=0) echo "<INPUT TYPE=HIDDEN NAME=\"id\" VALUE=\"$id\">\n";
echo "<INPUT TYPE=HIDDEN NAME=\"type_affichage_reser\" VALUE=\"$type_affichage_reser\">\n"; ?>
</FORM>


<?php include "include/trailer.inc.php" ?>