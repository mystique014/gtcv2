<?php
#########################################################################
#                            admin_abt_modify.php                      #
#                                                                       #
#      interface de création/modification des abonnements     #
#               Dernière modification : avril 2009                    #
#                                                                       #
#                                                                       #
#########################################################################
/*
 * s Duchemin
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

$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
if(authGetUserLevel(getUserName(),-1) < 5)
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}


// Initialisation
$id_abt = isset($_GET["id_abt"]) ? $_GET["id_abt"] : 0;
$abt_name = isset($_GET["abt_name"]) ? $_GET["abt_name"] : NULL;
$order_display = isset($_GET["order_display"]) ? $_GET["order_display"] : NULL;


// Gestion du retour à la page précédente sans enregistrement
if (isset($_GET['change_done']))
{
    Header("Location: "."admin_abt.php");
    exit();
}
$msg ='';

// Enregistrement
if (isset($_GET['change_abt'])) {
    $_SESSION['displ_msg'] = "yes";
    if ($abt_name == '') $abt_name = "A définir";
    if ($id_abt>0)
    {
        // Test sur $abt_name
        $test = grr_sql_query1("select count(id) from grr_abt where abt_name='".$abt_name."' and id!='".$id_abt."'");
        if ($test > 0) {
            $msg = "Enregistrement impossible : Un abonnement portant le même nom existe déjà.";
        } else {
            $sql = "UPDATE grr_abt SET
            abt_name='".protect_data_sql($abt_name)."',
            order_display =";
            if (is_numeric($order_display))
              $sql= $sql .intval($order_display);
            else
              $sql= $sql ."0";
              $sql = $sql . " WHERE id=$id_abt";
            if (grr_sql_command($sql) < 0)
                {
                fatal_error(0, get_vocab('update_abt_failed') . grr_sql_error());
                $ok = 'no';
                } else
                    $msg = get_vocab("message_records");
        }
    }
    else
    {
        // Test sur $abt_name
        $test = grr_sql_query1("select count(id) from grr_abt where abt_name='".$abt_name."'");
        if ($test > 0) {
            $msg = "Enregistrement impossible : Un abonnement portant le même nom existe déjà.";
        } else {
            $sql = "INSERT INTO grr_abt SET
            abt_name='".protect_data_sql($abt_name)."',
            order_display =";
            if (is_numeric($order_display))
              $sql= $sql .intval($order_display);
            else
              $sql= $sql ."0";
            if (grr_sql_command($sql) < 0)
                {
                fatal_error(1, "<p>" . grr_sql_error());
                $ok = 'no';
                } else {
                    $msg = get_vocab("message_records");
                }
        }

    }

    if ((isset($_GET['change_abt'])) and (!isset($ok)))
    {
      Header("Location: "."admin_abt.php?msg=".$msg);
      exit();
    }
}


# print the page header
    print_header("","","","",$abt="with_session", $page="admin");
    if (($msg) and (!($javascript_info_admin_disabled)))  {
        echo "<script type=\"text/javascript\" language=\"javascript\">";
        echo "<!--\n";
        echo " alert(\"".$msg."\")";
        echo "//-->";
        echo "</script>";
        unset($_SESSION['displ_msg']);
    }
    ?>
    <script src="functions.js" type="text/javascript" language="javascript"></script>
    <?php

   if ((isset($id_abt)) and ($id_abt>0)) {
        $res = grr_sql_query("SELECT * FROM grr_abt WHERE id=$id_abt");
        if (! $res) fatal_error(0, get_vocab('message_records_error'));
        $row = grr_sql_row_keyed($res, 0);
        grr_sql_free($res);
        $change_abt='modif';
        echo "<h2 ALIGN=CENTER>".get_vocab("admin_abt_modify_modify.php")."</h2>";
    } else {
        $row['id'] = '0';
        $row['abt_name'] = '';
        $row['order_display']  = 0;
        echo "<h2 ALIGN=CENTER>".get_vocab('admin_abt_modify_create.php')."</h2>";
    }

    echo get_vocab('admin_abt_explications')."<BR><BR>";
    ?>
    <form action="admin_abt_modify.php" method='GET'>
    <?php
    echo "<input type=\"hidden\" name=\"id_abt\" value=\"".$id_abt."\">\n";

    echo "<CENTER>
    <TABLE border=1>\n";
    echo "<TR>";
    echo "<TD>".get_vocab("abt_name").get_vocab("deux_points")."</TD>\n";
    echo "<TD><input type=\"text\" name=\"abt_name\" value=\"".htmlspecialchars($row['abt_name'])."\" size=\"20\" /></TD>\n";
    echo "</TR><TR>\n";

    echo "</TR><TR>\n";
    echo "<TD>".get_vocab("abt_order").get_vocab("deux_points")."</TD>\n";
    echo "<TD><input type=\"text\" name=\"order_display\" value=\"".htmlspecialchars($row['order_display'])."\" size=\"20\" /></TD>\n";
    echo "</TR>";

    echo "</TABLE>\n";


?>

    <input type=submit name="change_abt"
    value="<?php echo get_vocab("save") ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit name="change_done" value="<?php echo get_vocab("back") ?>">
    </CENTER>
    </form>



</body>
</html>