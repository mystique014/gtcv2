<?php
#########################################################################
#                            view_room.php                              #
#                                                                       #
#                          Fiche ressource                              #
#               Derni�re modification : 10/07/2009                      #
#                                                                       #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 * 
 * Modification S Duchemin 
 * Ajout affichage du nombre de r�servation par individu, par court et par semaine 
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

if (($authentification_obli==0) and (!isset($_SESSION['login']))) {
    $type_session = "no_session";
} else {
    $type_session = "with_session";
}

if((authGetUserLevel(getUserName(),-1) < 1) and ($authentification_obli==1))
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

$id_room = isset($_GET["id_room"]) ? $_GET["id_room"] : NULL;
if (isset($id_room)) settype($id_room,"integer");
else
$print = "all";

echo begin_page(getSettingValue("company").get_vocab("deux_points").get_vocab("mrbs"));

$res = grr_sql_query("SELECT * FROM grr_room WHERE id=$id_room");
if (! $res) fatal_error(0, get_vocab('error_room') . $id_room . get_vocab('not_found'));

$row = grr_sql_row_keyed($res, 0);
grr_sql_free($res);

?>
<h3 ALIGN=CENTER><?php echo get_vocab("room").get_vocab("deux_points")."&nbsp;".htmlspecialchars($row["room_name"]);

$id_area = mrbsGetRoomArea($id_room);
$area_name = grr_sql_query1("select area_name from grr_area where id='".$id_area."'");
$area_access = grr_sql_query1("select access from grr_area where id='".$id_area."'");
echo "<br>(".$area_name;
if ($area_access == 'r') echo " - ".get_vocab("access");
echo ")";
echo "</H3>";

if ($row['statut_room'] == "0")
    echo "<h2 align=center><font color=\"#BA2828\">".get_vocab("ressource_temporairement_indisponible")."</font></H2>";

?>
<CENTER>
<TABLE cellpadding="5" cellspacing="5">
<TR><TD><?php echo get_vocab("description") ?></TD><TD><?php echo htmlspecialchars($row["description"]); ?></TD></TR>
<?php
if ($row["comment_room"] != '')
    echo "<TR><TD>".get_vocab("match_descr").":</TD><TD>".$row["comment_room"]."</TD></TR>";
if ($row["capacity"] != '0')
    echo "<TR><TD>".get_vocab("capacity_2")."</TD><TD>".$row["capacity"]."</TD></TR>";

echo "</TABLE>";

if ($row["max_booking"] != "-1")
        echo "<br>".get_vocab("msg_max_booking")." <font color='red'>".$row["max_booking"]."</font>";
if ($row["max_booking_week"] != "-1") {
        echo "<br>".get_vocab("msg_max_booking_week")." <font color='red'>".$row["max_booking_week"]."</font>";
		}else{
		echo "<br>".get_vocab("msg_max_booking_week_illimite");
		}
if ($row["delais_max_resa_room"] != "-1")
        echo "<br>".get_vocab("delais_max_resa_room_2")." <font color='red'>".$row["delais_max_resa_room"]."</font>";
if ($row["delais_min_resa_room"] != "0")
        echo "<br>".get_vocab("delais_min_resa_room_2")." <b>".$row["delais_min_resa_room"]."</b>";
if ( getSettingValue("maxallressources") != -1){
		echo "<br>".get_vocab('title_booking_max_all_ressources')."&nbsp;&nbsp<font color='red'>".getSettingValue("maxallressources")."</font>";
} else {
echo "<br>".get_vocab('title_booking_max_all_ressources')."&nbsp;&nbsp<font color='red'>Pas de restriction !&nbsp;&nbsp;</font>";
}
$nom_picture = '';
if ($row['picture_room'] != '') $nom_picture = "./images/".$row['picture_room'];
if (@file_exists($nom_picture) && $nom_picture) {
   echo "<br><br><b>".get_vocab("Image de la ressource").": </b><br><IMG SRC=\"".$nom_picture."\" BORDER=0 ALT=\"logo\">";
} else {
   echo "<br><br><b>".get_vocab("Pas d'image disponible")."</b>";
}
echo "</CENTER>";
include "include/trailer.inc.php";