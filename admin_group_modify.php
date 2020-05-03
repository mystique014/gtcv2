<?php
#########################################################################
#                            admin_group_modify.php                      #
#                                                                       #
#      interface de création/modification des groupes d'utilisateurs     #
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
$id_group = isset($_GET["id_group"]) ? $_GET["id_group"] : 0;
$group_name = isset($_GET["group_name"]) ? $_GET["group_name"] : NULL;
$order_display = isset($_GET["order_display"]) ? $_GET["order_display"] : NULL;
$group_letter = isset($_GET["group_letter"]) ? $_GET["group_letter"] : NULL;
$couleur = isset($_GET["couleur"]) ? $_GET["couleur"] : NULL;

// Gestion du retour à la page précédente sans enregistrement
if (isset($_GET['change_done']))
{
    Header("Location: "."admin_group.php");
    exit();
}
$msg ='';

// Enregistrement
if (isset($_GET['change_group'])) {
    $_SESSION['displ_msg'] = "yes";
    if ($group_name == '') $group_name = "A définir";
    if ($group_letter == '') $group_letter = "A";
    if ($couleur == '') $couleur = "1";
    if ($id_group>0)
    {
        // Test sur $group_letter
        $test = grr_sql_query1("select count(id) from grr_group where group_letter='".$group_letter."' and id!='".$id_group."'");
        // Test sur nom du groupe
		$test2 = grr_sql_query1("select count(id) from grr_group where group_name='".$group_name."' and id!='".$id_group."'");
		if ($test > 0 OR $test2>0) {
            $msg = "Enregistrement impossible : Un groupe portant la meme lettre existe deja ou le meme nom.";
        } else {
            $sql = "UPDATE grr_group SET
            group_name='".protect_data_sql($group_name)."',
            order_display =";
            if (is_numeric($order_display))
              $sql= $sql .intval($order_display).",";
            else
              $sql= $sql ."0,";
            $sql = $sql . 'group_letter="'.$group_letter.'",';
            $sql = $sql . 'couleur="'.$couleur.'"';
            $sql = $sql . " WHERE id=$id_group";
            if (grr_sql_command($sql) < 0)
                {
                fatal_error(0, get_vocab('update_group_failed') . grr_sql_error());
                $ok = 'no';
                } else
                    $msg = get_vocab("message_records");
        }
    }
    else
    {
        // Test sur $group_letter
        $test = grr_sql_query1("select count(id) from grr_group where group_letter='".$group_letter."'");
        // Test sur nom du groupe
		$test2 = grr_sql_query1("select count(id) from grr_group where group_name='".$group_name."'");
		if ($test > 0 OR $test2>0) {
            $msg = "Enregistrement impossible : Un groupe portant la même lettre existe déjà ou le même nom.";
        } else {
            $sql = "INSERT INTO grr_group SET
            group_name='".protect_data_sql($group_name)."',
            order_display =";
            if (is_numeric($order_display))
              $sql= $sql .intval($order_display).",";
            else
              $sql= $sql ."0,";
            $sql = $sql . 'group_letter="'.$group_letter.'",';
            $sql = $sql . 'couleur="'.$couleur.'"';
            if (grr_sql_command($sql) < 0)
                {
                fatal_error(1, "<p>" . grr_sql_error());
                $ok = 'no';
                } else {
                    $msg = get_vocab("message_records");
                }
        }

    }

    if ((isset($_GET['change_group'])) and (!isset($ok)))
    {
      Header("Location: "."admin_group.php?msg=".$msg);
      exit();
    }
}


# print the page header
    print_header("","","","",$group="with_session", $page="admin");
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

    if ((isset($id_group)) and ($id_group>0)) {
        $res = grr_sql_query("SELECT * FROM grr_group WHERE id=$id_group");
        if (! $res) fatal_error(0, get_vocab('message_records_error'));
        $row = grr_sql_row_keyed($res, 0);
        grr_sql_free($res);
        $change_group='modif';
        echo "<h2 ALIGN=CENTER>".get_vocab("admin_group_modify_modify.php")."</h2>";
    } else {
        $row["id"] = '0';
        $row["group_name"] = '';
        $row["group_letter"] = '';
        $row["order_display"]  = 0;
        $row["couleur"]  = '';
        echo "<h2 ALIGN=CENTER>".get_vocab('admin_group_modify_create.php')."</h2>";
    }
    echo get_vocab('admin_group_explications')."<BR><BR>";
    ?>
    <form action="admin_group_modify.php" method='GET'>
    <?php
    echo "<input type=\"hidden\" name=\"id_group\" value=\"".$id_group."\">\n";

    echo "<CENTER>
    <TABLE border=1>\n";
    echo "<TR>";
    echo "<TD>".get_vocab("group_name").get_vocab("deux_points")."</TD>\n";
    echo "<TD><input type=\"text\" name=\"group_name\" value=\"".htmlspecialchars($row["group_name"])."\" size=\"20\" /></TD>\n";
    echo "</TR><TR>\n";
    echo "<TD>".get_vocab("group_num").get_vocab("deux_points")."</TD>\n";
    echo "<TD>";
    echo "<select name=\"group_letter\" size=\"1\">\n";
    echo "<option value=''>".get_vocab("choose")."</option>\n";
    $letter = "A";
    for ($i=1;$i<=26;$i++) {
       echo "<option value='".$letter."' ";
       if ($row['group_letter'] == $letter) echo " selected";
       echo ">".$letter."</option>\n";
       $letter++;
    }
    echo "</select>";
    echo "</TD>\n";
    echo "</TR><TR>\n";
    echo "<TD>".get_vocab("group_order").get_vocab("deux_points")."</TD>\n";
    echo "<TD><input type=\"text\" name=\"order_display\" value=\"".htmlspecialchars($row["order_display"])."\" size=\"20\" /></TD>\n";
    echo "</TR>";
   if ($row["couleur"]  != '') {
        echo "<TR>\n";
        echo "<TD>".get_vocab("group_color").get_vocab("deux_points")."</TD>\n";
        echo "<TD bgcolor=\"".$tab_couleur[$row["couleur"]]."\">&nbsp;</TD>";
        echo "</TR>";
    }
    echo "</TABLE>\n";
    echo get_vocab("group_color").get_vocab("deux_points");
    echo "<table border=2><tr>\n";
    $nct = 0;
    foreach($tab_couleur as $key=>$value)
    {
      $checked = " ";
      if ($key == $row["couleur"])
          $checked = "checked";
      if (++$nct > 4)
            {
                $nct = 1;
                echo "</tr><tr>";
            }
      echo "<TD bgcolor=\"".$tab_couleur[$key]."\"><input type=\"radio\" name=\"couleur\" value=\"".$key."\" ".$checked.">______________</TD>";
    }
    echo "</tr></table>\n";

?>

    <input type=submit name="change_group"
    value="<?php echo get_vocab("save") ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit name="change_done" value="<?php echo get_vocab("back") ?>">
    </CENTER>
    </form>



</body>
</html>