<?php
#########################################################################
#                            view_room.php                              #
#                                                                       #
#                  Liste des privil�ges d'un domaine                    #
#               Derni�re modification : 10/07/2006                      #
#                                                                       #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
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

$area_id = isset($_GET["area_id"]) ? $_GET["area_id"] : NULL;
if (isset($area_id)) settype($area_id,"integer");

if (authGetUserLevel(getUserName(),$area_id,"area") < 4)
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

echo begin_page(getSettingValue("company").get_vocab("deux_points").get_vocab("mrbs"));

$res = grr_sql_query("SELECT * FROM ".$_COOKIE["table_prefix"]."_area WHERE id='".$area_id."'");
if (! $res) fatal_error(0, get_vocab('error_room') . $id_room . get_vocab('not_found'));

$row = grr_sql_row_keyed($res, 0);
grr_sql_free($res);

?>
<h3 ALIGN=CENTER><?php echo get_vocab("match_area").get_vocab("deux_points")."&nbsp;".htmlspecialchars($row["area_name"]);
$area_access = $row["access"];
if ($area_access == 'r') echo " (<font color=\"#FF0000\">".get_vocab("access")."</font>)";
echo "</H3>";

// On affiche pour les administrateurs les utilisateurs ayant des privil�ges sur cette ressource
    echo "<h2>".get_vocab('utilisateurs ayant privileges sur domaine')."</h2>";
    $a_privileges = 'n';
    // on teste si des utilateurs administre le domaine
    $req_admin = "select u.login, u.nom, u.prenom  from ".$_COOKIE["table_prefix"]."_utilisateurs u
    left join ".$_COOKIE["table_prefix"]."_j_useradmin_area j on u.login=j.login
    where j.id_area = '".$area_id."' order by u.nom, u.prenom";
    $res_admin = grr_sql_query($req_admin);
    $is_admin = '';
    if ($res_admin) {
        for ($j = 0; ($row_admin = grr_sql_row($res_admin, $j)); $j++) {
            $is_admin .= $row_admin[1]." ".$row_admin[2]." (".$row_admin[0].")<br>";
        }
    }
    if ($is_admin != '') {
        $a_privileges = 'y';
        echo "<H3><b>".get_vocab("utilisateurs administrateurs domaine")."</b></H3>";
        echo $is_admin;
    }

    // Si le domaine est restreint, on teste si des utilateurs y ont acc�s
    if ($area_access == 'r') {
        $req_restreint = "select u.login, u.nom, u.prenom  from ".$_COOKIE["table_prefix"]."_utilisateurs u
        left join ".$_COOKIE["table_prefix"]."_j_user_area j on u.login=j.login
        where j.id_area = '".$area_id."' order by u.nom, u.prenom";
        $res_restreint = grr_sql_query($req_restreint);
        $is_restreint = '';
        if ($res_restreint) {
            for ($j = 0; ($row_restreint = grr_sql_row($res_restreint, $j)); $j++) {
                $is_restreint .= $row_restreint[1]." ".$row_restreint[2]." (".$row_restreint[0].")<br>";
            }
        }
        if ($is_restreint != '') {
            $a_privileges = 'y';
            echo "<H3><b>".get_vocab("utilisateurs acces restreint domaine")."</b></H3>";
            echo $is_restreint;
        }
    }
    if ($a_privileges == 'n') {
      echo get_vocab("aucun autilisateur").".";
  }


include "include/trailer.inc.php";