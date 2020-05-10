<?php
#########################################################################
#                            admin_tgroup.php                             #
#                                                                       #
#            interface de gestion des groupes             #
#               Dernière modification : avril 2009                     #
#                                                                       #
#                                                                       #
#########################################################################
/*
 * S Duchemin
 *
 *
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


if(authGetUserLevel(getUserName(),-1) < 5)
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

$back = "";
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];

if ((isset($_GET['msg'])) and isset($_SESSION['displ_msg'])) {
   $msg = $_GET['msg'];
   unset($_SESSION['displ_msg']);
}
else
   $msg = '';
# print the page header
print_header("","","","",$type="with_session", $page="admin");
// Affichage de la colonne de gauche
include "admin_col_gauche.php";

?>
<script src="./functions.js" type="text/javascript" language="javascript"></script>
<?php
//
// Suppression d'un type de réservation
//
if ((isset($_GET['action_del'])) and ($_GET['js_confirmed'] ==1) and ($_GET['action_del']='yes')) {
    // faire le test si il existe une réservation en cours avec ce type de réservation
    $group_id = grr_sql_query1("select group_letter from ".$_COOKIE["table_prefix"]."_group where id = '".$_GET['group_del']."'");
    $test1 = grr_sql_query1("select count(id) from ".$_COOKIE["table_prefix"]."_group_calendar where group_id= '".$group_id."'");
    $test2 = grr_sql_query1("select count(id) from ".$_COOKIE["table_prefix"]."_group_repeat where group_id= '".$group_id."'");
    if (($test1 != 0) or ($test2 != 0)) {
        $msg =  "Suppression impossible : des zones ont été enregistrées avec ce groupe.";
    } else {
        $sql = "DELETE FROM ".$_COOKIE["table_prefix"]."_group WHERE id='".$_GET['group_del']."'";
        if (grr_sql_command($sql) < 0) {fatal_error(1, "<p>" . grr_sql_error());}
        $sql = "DELETE FROM ".$_COOKIE["table_prefix"]."_j_group_area WHERE id_group='".$_GET['group_del']."'";
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

echo "<h2>".get_vocab('admin_group.php')."</h2>";
echo get_vocab('admin_group_explications');
echo "<BR>\n";
echo "<BR>\n";
echo "| <a href=\"admin_group_modify.php?id=0\">".get_vocab("display_add_group")."</a> |\n";

$sql = "SELECT id, group_name, order_display, couleur, group_letter FROM ".$_COOKIE["table_prefix"]."_group
ORDER BY order_display,group_letter";
$res = grr_sql_query($sql);
$nb_lignes = grr_sql_count($res);
if ($nb_lignes == 0) {
    // fin de l'affichage de la colonne de droite
    echo "</td></tr></table>";
    echo "</body></html>";
    die();
}



// Affichage du tableau
echo "<table border=\"1\" cellpadding=\"3\">\n";
// echo "<tr><td><b>".get_vocab("group_num")."</a></b></td>\n";
echo "<td><b>".get_vocab("group_num")."</a></b></td>\n";
echo "<td><b>".get_vocab("group_name")."</a></b></td>\n";
echo "<td><b>".get_vocab("group_color")."</a></b></td>\n";
echo "<td><b>".get_vocab("group_order")."</a></b></td>\n";
echo "<td><b>".get_vocab("delete")."</b></td>";
echo "</tr>";
if ($res) {
    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
    $id_group        = $row[0];
    $group_name      = $row[1];
    $order_display     = $row[2];
    $couleur = $row[3];
    $group_letter = $row[4];
    // Affichage des numéros et descriptions
    $col[$i][1] = $group_letter;
    $col[$i][2] = $id_group;
    $col[$i][3] = $group_name;
    // Affichage de l'ordre
    $col[$i][4]= $order_display;
    $col[$i][5]= $couleur;

    echo "<tr>\n";
    echo "<td>{$col[$i][1]}</td>\n";
    echo "<td><a href='admin_group_modify.php?id_group={$col[$i][2]}'>{$col[$i][3]}</a></td>\n";
    echo "<td bgcolor='".$tab_couleur[$col[$i][5]]."'></td>\n";
    echo "<td>{$col[$i][4]}</td>\n";
    $themessage = get_vocab("confirm_del");
    echo "<td><a href='admin_group.php?&amp;group_del={$col[$i][2]}&amp;action_del=yes' onclick='return confirmlink(this, \"{$col[$i][1]}\", \"$themessage\")'>".get_vocab("delete")."</a></td>";
    // Fin de la ligne courante
    echo "</tr>";
    }
}

echo "</table>";

// Test de cohérence des groupes

    $res = grr_sql_query("select distinct group_id from ".$_COOKIE["table_prefix"]."_group_calendar order by group_id");
    if ($res) {
        $liste = "";
        for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
            $test = grr_sql_query1("select group_letter from ".$_COOKIE["table_prefix"]."_group where group_letter='".$row[0]."'");
            if ($test == -1) $liste .= $row[0]." ";
        }
        if ($liste != "") {
            echo "<br><table border=\"1\" cellpadding=\"5\"><tr><td><p><font color=red><b>ATTENTION : votre table des groupes d'utilisateurs n'est pas à jour :</b></font></p>";
            echo "<p>Un ou plusieurs groupes d'utilisateurs sont actuellement utilisés dans les réservations
            mais ne figurent pas dans la tables des groupes. Cela risque d'engendrer des messages d'erreur. <b>Il s'agit du ou des groupes suivants : ".$liste."</b>";
            echo "<br><br>Vous devez donc définir ci-dessus, le ou les groupes manquants.</p></td></tr></table>";
        }
    }


// fin de l'affichage de la colonne de droite
echo "</td></tr></table>";

?>
</body>
</html>