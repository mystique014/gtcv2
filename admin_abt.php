<?php
#########################################################################
#                            admin_abt.php                             #
#                                                                       #
#            interface de gestion des abonnements           #
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
        $sql = "DELETE FROM grr_abt WHERE id='".$_GET['abt_del']."'";
        if (grr_sql_command($sql) < 0) {fatal_error(1, "<p>" . grr_sql_error());}
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

echo "<h2>".get_vocab('admin_abt.php')."</h2>";
echo get_vocab('admin_abt_explications');
echo "<BR>\n";
echo "<BR>\n";
echo "| <a href=\"admin_abt_modify.php?id=0\">".get_vocab("display_add_abt")."</a> |\n";

$sql = "SELECT id, abt_name, order_display FROM grr_abt ORDER BY order_display";
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
// echo "<tr><td><b>".get_vocab("abt_num")."</a></b></td>\n";
echo "<td><b>".get_vocab("abt_name")."</a></b></td>\n";
echo "<td><b>".get_vocab("abt_order")."</a></b></td>\n";
echo "<td><b>".get_vocab("delete")."</b></td>";
echo "</tr>";
if ($res) {
    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
    $id_abt        = $row[0];
    $abt_name      = $row[1];
    $order_display     = $row[2];
    // Affichage des numéros et descriptions
    $col[$i][1] = $abt_name;
    $col[$i][2] = $id_abt;
    $col[$i][3] = $order_display;


    echo "<tr>\n";
    echo "<td><a href='admin_abt_modify.php?id_abt={$col[$i][2]}'>{$col[$i][1]}</a></td>\n";
    echo "<td>{$col[$i][3]}</td>\n";
    $themessage = get_vocab("confirm_del");
    echo "<td><a href='admin_abt.php?&amp;abt_del={$col[$i][2]}&amp;action_del=yes' onclick='return confirmlink(this, \"{$col[$i][1]}\", \"$themessage\")'>".get_vocab("delete")."</a></td>";
    // Fin de la ligne courante
    echo "</tr>";
    }
}

echo "</table>";


// fin de l'affichage de la colonne de droite
echo "</td></tr></table>";

?>
</body>
</html>