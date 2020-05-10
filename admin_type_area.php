<?php
#########################################################################
#                            admin_type_area.php                        #
#                                                                       #
#            interface de gestion des types de réservations             #
#                           pour un domaine                             #
#               Dernière modification : 10/03/2005                      #
#                                                                       #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau - Pascal Ragot
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


// Initialisation
$area_id = isset($_GET["area_id"]) ? $_GET["area_id"] : NULL;

if(authGetUserLevel(getUserName(),$area_id,'area') < 4)
{
    $back = '';
    if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

$back = "";
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];

// Gestion du retour à la page précédente sans enregistrement
if (isset($_GET['change_done']))
{
    Header("Location: "."admin_room.php");
    exit();
}


if ((isset($_GET['msg'])) and isset($_SESSION['displ_msg'])) {
   $msg = $_GET['msg'];
   unset($_SESSION['displ_msg']);
}
else
   $msg = '';
# print the page header
print_header("","","","",$type="with_session", $page="admin");

?>
<script src="./functions.js" type="text/javascript" language="javascript"></script>
<?php

$sql = "SELECT id, type_name, order_display, couleur, type_letter FROM ".$_COOKIE["table_prefix"]."_type_area
ORDER BY order_display, type_letter";


//
// Enregistrement
//
if (isset($_GET['valider']))  {
    $res = grr_sql_query($sql);
    if ($res) {
        for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
        if (isset($_GET[$row[0]])) {
            $del = grr_sql_query("delete from ".$_COOKIE["table_prefix"]."_j_type_area where id_area='".$area_id."' and id_type = '".$row[0]."'");
        } else {
            $test = grr_sql_query1("select count(id_type) from ".$_COOKIE["table_prefix"]."_j_type_area where id_area = '".$area_id."' and id_type = '".$row[0]."'");
            if ($test == 0) {
                // faire le test si il existe une réservation en cours avec ce type de réservation
//                $type_id = grr_sql_query1("select type_letter from ".$_COOKIE["table_prefix"]."_type_area where id = '".$row[0]."'");
//                $test1 = grr_sql_query1("select count(id) from ".$_COOKIE["table_prefix"]."_entry where type= '".$type_id."'");
//                $test2 = grr_sql_query1("select count(id) from ".$_COOKIE["table_prefix"]."_repeat where type= '".$type_id."'");
//                if (($test1 != 0) or ($test2 != 0)) {
//                    $msg =  "Suppression impossible : des réservations ont été enregistrées avec ce type.";
//                } else {
                    $sql1 = "insert into ".$_COOKIE["table_prefix"]."_j_type_area set id_area='".$area_id."', id_type = '".$row[0]."'";
                    if (grr_sql_command($sql1) < 0) {fatal_error(1, "<p>" . grr_sql_error());}
//                }

            }
        }
        }
    }
}

echo "<noscript>";
echo "<font color='red'>$msg</font>";
echo "</noscript>";
if (($msg) and (!($javascript_info_admin_disabled)))  {
    echo "<script type=\"text/javascript\" language=\"javascript\">";
    echo "<!--\n";
    echo " alert(\"".$msg."\")";
    echo "//-->";
    echo "</script>";
}

$area_name = grr_sql_query1("select area_name from ".$_COOKIE["table_prefix"]."_area where id='".$area_id."'");
echo "<center><h2>".get_vocab('admin_type.php')."</h2>";
echo "<h2>".get_vocab("match_area").get_vocab('deux_points')." ".$area_name."</h2></center>";

$res = grr_sql_query($sql);
$nb_lignes = grr_sql_count($res);
if ($nb_lignes == 0) {
    echo "</body></html>";
    die();
}

echo "<center><table width=\"80%\">";
if(authGetUserLevel(getUserName(),-1) >= 5)
echo "<tr><td><a href=\"admin_type_modify.php?id=0\">".get_vocab("display_add_type")."</a></td></tr>";
echo "<tr><td>".get_vocab("explications_active_type")."</td></tr>";
echo "<tr><td>\n";

echo "<form action=\"admin_type_area.php\" name=\"type\" method=\"get\">\n";
// Affichage du tableau
echo "<table border=\"1\" cellpadding=\"3\">\n";
// echo "<tr><td><b>".get_vocab("type_num")."</a></b></td>\n";
echo "<td><b>".get_vocab("type_num")."</a></b></td>\n";
echo "<td><b>".get_vocab("type_name")."</a></b></td>\n";
echo "<td><b>".get_vocab("type_color")."</a></b></td>\n";
echo "<td><b>".get_vocab("type_order")."</a></b></td>\n";
echo "<td><b>".get_vocab("type_valide_domaine")."</b></td>";
echo "</tr>";
if ($res) {
    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
    $id_type        = $row[0];
    $type_name      = $row[1];
    $order_display     = $row[2];
    $couleur = $row[3];
    $type_letter = $row[4];
    // Affichage des numéros et descriptions
    $col[$i][1] = $type_letter;
    $col[$i][2] = $id_type;
    $col[$i][3] = $type_name;
    // Affichage de l'ordre
    $col[$i][4]= $order_display;
    $col[$i][5]= $couleur;

    echo "<tr>\n";
    echo "<td>{$col[$i][1]}</td>\n";
    echo "<td>{$col[$i][3]}</td>\n";
    echo "<td bgcolor='".$tab_couleur[$col[$i][5]]."'></td>\n";
    echo "<td>{$col[$i][4]}</td>\n";
    echo "<td><input type=\"checkbox\" name=\"".$col[$i][2]."\" value=\"y\" ";
    $test = grr_sql_query1("select count(id_type) from ".$_COOKIE["table_prefix"]."_j_type_area where id_area = '".$area_id."' and id_type = '".$row[0]."'");
    if ($test < 1) echo " checked";
    echo " /></td>";
    // Fin de la ligne courante
    echo "</tr>";
    }
}
echo "</table>";
echo "</tr></table></center>";
echo "<input type=\"hidden\" name=\"area_id\" value=\"".$area_id."\" />";
echo "<center><input type=\"submit\" name=\"valider\" value=\"".get_vocab("submit")."\" />\n";
echo "&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"change_done\" value=\"".get_vocab("back")."\">";
echo "</center>";
echo "</form>\n";


?>
</body>
</html>