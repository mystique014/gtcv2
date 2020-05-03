<?php
#########################################################################
#                         admin_edit_room                               #
#                                                                       #
#                       Interface de création/modification              #
#                     des champs additionnels.                          #
#                                                                       #
#                  Dernière modification : 10/07/2006                   #
#                                                                       #
#########################################################################
/*
 * Copyright 2006 Institut National d'Histoire de l'Art
 * Copyright 2006 Mathieu Ignacio
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

/** grrDelOverloadFromEntries()
 * Supprime les données du champ $id_field de toutes les réservations
 */
function grrDelOverloadFromEntries($id_field)
{
  $begin_string = "<".$id_field.">";
  $end_string = "</".$id_field.">";
  // On cherche à quel domaine est rattaché le champ additionnel
  $id_area = grr_sql_query1("select id_area from grr_overload where id='".$id_field."'");
  if ($id_area == -1) fatal_error(0, get_vocab('error_area') . $id_area . get_vocab('not_found'));
  // On cherche toutes les ressources du domaine
  $call_rooms = grr_sql_query("select id from grr_room where area_id = '".$id_area."'");
  if (! $call_rooms) fatal_error(0, get_vocab('error_room') . $id_room . get_vocab('not_found'));
  for ($i = 0; ($row = grr_sql_row($call_rooms, $i)); $i++) {
      // On cherche toutes les resas de cette resources
      $call_resa = grr_sql_query("select id, overload_desc from grr_entry where room_id ='".$row[0]."'");
      if (! $call_resa) fatal_error(0, get_vocab('invalid_entry_id'));
      for ($j = 0; ($row2 = grr_sql_row($call_resa, $j)); $j++) {
          $overload_desc = $row2[1];
          $begin_pos = strpos($overload_desc,$begin_string);
          $end_pos = strpos($overload_desc,$end_string);
          if ( $begin_pos !== false && $end_pos !== false ) {
              $endpos = $end_pos + 1 + strlen($begin_string);
              $debut_new_chaine = substr($overload_desc,0,$begin_pos);
              $fin_new_chaine = substr($overload_desc,$endpos);
              $new_chaine = $debut_new_chaine.$fin_new_chaine;
              grr_sql_command("update grr_entry set overload_desc = '".$new_chaine."' where id = '".$row2[0]."'");
          }

       }
      // On cherche toutes les resas de cette resources
      $call_resa = grr_sql_query("select id, overload_desc from grr_repeat where room_id ='".$row[0]."'");
      if (! $call_resa) fatal_error(0, get_vocab('invalid_entry_id'));
      for ($j = 0; ($row2 = grr_sql_row($call_resa, $j)); $j++) {
          $overload_desc = $row2[1];
          $begin_pos = strpos($overload_desc,$begin_string);
          $end_pos = strpos($overload_desc,$end_string);
          if ( $begin_pos !== false && $end_pos !== false ) {
              $endpos = $end_pos + 1 + strlen($begin_string);
              $debut_new_chaine = substr($overload_desc,0,$begin_pos);
              $fin_new_chaine = substr($overload_desc,$endpos);
              $new_chaine = $debut_new_chaine.$fin_new_chaine;
              grr_sql_command("update grr_repeat set overload_desc = '".$new_chaine."' where id = '".$row2[0]."'");
          }

       }
    }
}

if(authGetUserLevel(getUserName(),-1,'area') < 4)
{
    $back = '';
    if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

// print the page header
print_header("","","","",$type="with_session", $page="admin");
// Affichage de la colonne de gauche
include "admin_col_gauche.php";

?>
<script type="text/javascript" src="./functions.js" language="javascript"></script>
<?php

echo "<h2>".get_vocab("admin_overload.php")."</h2>";

// Intitialistion des données
if (isset($_POST["action"])) $action = $_POST["action"]; else $action = "default";

// 1- On récupère la liste des domaines accessibles à l'utilisateur dans un tableau.
$res = grr_sql_query("select id, area_name, access from grr_area order by order_display");
if (! $res) fatal_error(0, grr_sql_error());

$userdomain = array();
if (grr_sql_count($res) != 0)
{
  for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
      if(authGetUserLevel(getUserName(),$row[0],'area') >= 4)
    {
      $userdomain[$row[0]] = $row[1];
    }
    }
}



// 2- On traite la demande si nécessaire
if ($action == "add")
{
  $arearight = False;

  // on récupère les données importantes du POST.

  if (isset($_POST["id_area"])) $id_area = $_POST["id_area"];
  else $id_area = 0;
  settype($id_area,"integer");

  if (isset($_POST["fieldname"])) $fieldname = $_POST["fieldname"];
  else $fieldname = "";

  if (isset($_POST["fieldtype"])) $fieldtype = $_POST["fieldtype"];
  else $fieldtype = "";


  // Gestion des droits : on vérifie que le user a les droits pour id_area..
  foreach ( $userdomain as $key=>$value )
    if ( $key == $id_area ) $arearight = True;


  // On fait l'action si l'id/area a été validé.
  if ( $arearight == True)
    {
      $sql = "insert into grr_overload (id_area, fieldname, fieldtype) values ($id_area, '".protect_data_sql($fieldname)."', '".protect_data_sql($fieldtype)."');";
      if (grr_sql_command($sql) < 0) fatal_error(0, "$sql \n\n" . grr_sql_error());
    }
}


if ($action == "delete" )
{
  $arearight = False ;
  // on récupère les données importantes du POST.
  if (isset($_POST["id_overload"])) $id_overload = $_POST["id_overload"];
  else $id_overload = "";


  // Gestion des droits : on vérifie si l'id à supprimer est dans l'area autorisée.
  $sql = "select id_area from grr_overload where id=$id_overload;";
  $resquery = grr_sql_query($sql);
  if (! $resquery) fatal_error(0, grr_sql_error());

  if (grr_sql_count($resquery) > 0)
    for ($i = 0; ($row = grr_sql_row($resquery, $i)); $i++)
      {
    foreach ( $userdomain as $key=>$value )
      if ( $key == $row[0] ) $arearight = True;
      }

  // On fait l'action si l'id/area a été validé.
  if ( $arearight == True )
    {
      // Suppression des données dans les réservations déjà effectuées
      grrDelOverloadFromEntries($id_overload);
      $sql = "delete from grr_overload where id=$id_overload;";
      if (grr_sql_command($sql) < 0)
          fatal_error(0, "$sql \n\n" . grr_sql_error());

    }
  //FIXME : else, mettre un message d'erreur
}


if ($action == "change")
{
  $arearight = False ;

  // on récupère les données importantes du POST.
  if (isset($_POST["id_overload"])) $id_overload = $_POST["id_overload"];
  else $id_overload = "";
  settype($id_overload,"integer");

  if (isset($_POST["fieldname"])) $fieldname = $_POST["fieldname"];
  else $fieldname = "";

  if (isset($_POST["fieldtype"])) $fieldtype = $_POST["fieldtype"];
  else $fieldtype = "";

  // FIXME : Gestion des droits.

  // Gestion des droits : on vérifie si l'id à modifier est dans l'area autorisée.
  $sql = "select id_area from grr_overload where id=$id_overload;";
  $resquery = grr_sql_query($sql);
  if (! $resquery) fatal_error(0, grr_sql_error());

  if (grr_sql_count($resquery) > 0)
    for ($i = 0; ($row = grr_sql_row($resquery, $i)); $i++)
      {
    foreach ( $userdomain as $key=>$value )
      if ( $key == $row[0] ) $arearight = True;
      }

  // On fait l'action si l'id/area a été validé.

  if ( $arearight == True )
    {
      $sql = "update grr_overload set fieldname='".protect_data_sql($fieldname)."' where id=$id_overload;";
      if (grr_sql_command($sql) < 0) fatal_error(0, "$sql \n\n" . grr_sql_error());
      $sql = "update grr_overload set fieldtype='".protect_data_sql($fieldtype)."' where id=$id_overload;";
      if (grr_sql_command($sql) < 0) fatal_error(0, "$sql \n\n" . grr_sql_error());
    }
}

// X- On affiche la première ligne du tableau avec les libelles.
$html = "<p>".get_vocab("explication_champs_additionnels")."</p>";
$html .= "<table>";
$html .= "<tr><td>".get_vocab("match_area").get_vocab("deux_points")."</td>";
$html .= "<td>".get_vocab("fieldname").get_vocab("deux_points")."</td>";
$html .= "<td>".get_vocab("fieldtype").get_vocab("deux_points")."</td>";
$html .= "<td></td><td></td></tr>";

// X+1- On affiche le formulaire d'ajout
$html .= "\n<tr>\n<form method=\"post\" action=\"admin_overload.php\">\n";
$html .= "<td><select name=id_area>";

foreach( $userdomain as $key=>$value )
{
  $html .= "<option value=\"$key\">".$userdomain[$key]."</option>";
}

$html .= "</select></td>";
$html .= "<td><input type=text name=fieldname></td>";
$html .= "<td><select name=fieldtype><option value=text>".get_vocab("type_text")."</option><option value=textarea>".get_vocab("type_area")."</option></td>\n";
$html .= "<td><input type=submit name=submit value=".get_vocab('add')."></td>\n<td><input type=hidden name=action value=add></td>\n</form>\n</tr>";


// X+2- On affiche les données du tableau

$breakkey = "";

foreach( $userdomain as $key=>$value )
{
  $res = grr_sql_query("select id, fieldname, fieldtype from grr_overload where id_area=$key order by fieldname;");
  if (! $res) fatal_error(0, grr_sql_error());

  if (($key != $breakkey ) and (grr_sql_count($res) != 0)) $html .= "<tr><td colspan=5><hr></td></tr>";
  $breakkey = $key;

  if (grr_sql_count($res) != 0)
    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
      {
    $html .= "<tr>\n<form method=POST>\n<input type=hidden name=id_overload value=\"$row[0]\">\n";
    $html .= "<input type=hidden name=action value=change>\n";
    $html .= "<td>$userdomain[$key]</td>";


    $html .= "<td><input type=text name=fieldname value=\"".htmlspecialchars($row[1])."\"></td>\n";

    $html .= "<td><select name=fieldtype>\n";
    if ($row[2] =="textarea")
      $html .= "<option value=textarea selected>".get_vocab("type_area")."</option><option value=text>".get_vocab("type_text")."</option>\n";
    else $html .= "<option value=textarea>".get_vocab("type_area")."</option><option value=text selected>".get_vocab("type_text")."</option>\n";
    $html .= "</select></td>\n";

    $html .= "<td><input type=submit value=".get_vocab('change')."></td></form>\n";

    $html .= "<form method=POST><input type=hidden name=id_overload value=\"$row[0]\">\n";
    $html .= "<input type=hidden name=action value=delete>\n";
    $html .= "<td><input type=submit value=".get_vocab('del')." onclick=\"return confirmlink(this, '".AddSlashes(get_vocab("avertissement_suppression_champ_additionnel"))."', '".get_vocab("confirm_del")."')\" ></td></form></tr>";
      }

}
echo $html;



?>