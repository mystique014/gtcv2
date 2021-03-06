<?php
#########################################################################
#                            admin_type_modify.php                      #
#                                                                       #
#      interface de cr�ation/modification des types de r�servations     #
#               Derni�re modification : 10/03/2005                      #
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
$id_type = isset($_GET["id_type"]) ? $_GET["id_type"] : 0;
$type_name = isset($_GET["type_name"]) ? $_GET["type_name"] : NULL;
$order_display = isset($_GET["order_display"]) ? $_GET["order_display"] : NULL;
$type_letter = isset($_GET["type_letter"]) ? $_GET["type_letter"] : NULL;
$couleur = isset($_GET["couleur"]) ? $_GET["couleur"] : NULL;

// Gestion du retour � la page pr�c�dente sans enregistrement
if (isset($_GET['change_done']))
{
    Header("Location: "."admin_type.php");
    exit();
}
$msg ='';

// Enregistrement
if (isset($_GET['change_type'])) {
    $_SESSION['displ_msg'] = "yes";
    if ($type_name == '') $type_name = "A d�finir";
    if ($type_letter == '') $type_letter = "A";
    if ($couleur == '') $couleur = "1";
    if ($id_type>0)
    {
        // Test sur $type_letter
        $test = grr_sql_query1("select count(id) from grr_type_area where type_letter='".$type_letter."' and id!='".$id_type."'");
        if ($test > 0) {
            $msg = "Enregistrement impossible : Un type portant la m�me lettre existe d�j�.";
        } else {
            $sql = "UPDATE grr_type_area SET
            type_name='".protect_data_sql($type_name)."',
            order_display =";
            if (is_numeric($order_display))
              $sql= $sql .intval($order_display).",";
            else
              $sql= $sql ."0,";
            $sql = $sql . 'type_letter="'.$type_letter.'",';
            $sql = $sql . 'couleur="'.$couleur.'"';
            $sql = $sql . " WHERE id=$id_type";
            if (grr_sql_command($sql) < 0)
                {
                fatal_error(0, get_vocab('update_type_failed') . grr_sql_error());
                $ok = 'no';
                } else
                    $msg = get_vocab("message_records");
        }
    }
    else
    {
        // Test sur $type_letter
        $test = grr_sql_query1("select count(id) from grr_type_area where type_letter='".$type_letter."'");
        if ($test > 0) {
            $msg = "Enregistrement impossible : Un type portant la m�me lettre existe d�j�.";
        } else {
            $sql = "INSERT INTO grr_type_area SET
            type_name='".protect_data_sql($type_name)."',
            order_display =";
            if (is_numeric($order_display))
              $sql= $sql .intval($order_display).",";
            else
              $sql= $sql ."0,";
            $sql = $sql . 'type_letter="'.$type_letter.'",';
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

    if ((isset($_GET['change_type'])) and (!isset($ok)))
    {
      Header("Location: "."admin_type.php?msg=".$msg);
      exit();
    }
}


# print the page header
    print_header("","","","",$type="with_session", $page="admin");
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

    if ((isset($id_type)) and ($id_type>0)) {
        $res = grr_sql_query("SELECT * FROM grr_type_area WHERE id=$id_type");
        if (! $res) fatal_error(0, get_vocab('message_records_error'));
        $row = grr_sql_row_keyed($res, 0);
        grr_sql_free($res);
        $change_type='modif';
        echo "<h2 ALIGN=CENTER>".get_vocab("admin_type_modify_modify.php")."</h2>";
    } else {
        $row["id"] = '0';
        $row["type_name"] = '';
        $row["type_letter"] = '';
        $row["order_display"]  = 0;
        $row["couleur"]  = '';
        echo "<h2 ALIGN=CENTER>".get_vocab('admin_type_modify_create.php')."</h2>";
    }
    echo get_vocab('admin_type_explications')."<BR><BR>";
    ?>
    <form action="admin_type_modify.php" method='GET'>
    <?php
    echo "<input type=\"hidden\" name=\"id_type\" value=\"".$id_type."\">\n";

    echo "<CENTER>
    <TABLE border=1>\n";
    echo "<TR>";
    echo "<TD>".get_vocab("type_name").get_vocab("deux_points")."</TD>\n";
    echo "<TD><input type=\"text\" name=\"type_name\" value=\"".htmlspecialchars($row["type_name"])."\" size=\"20\" /></TD>\n";
    echo "</TR><TR>\n";
    echo "<TD>".get_vocab("type_num").get_vocab("deux_points")."</TD>\n";
    echo "<TD>";
    echo "<select name=\"type_letter\" size=\"1\">\n";
    echo "<option value=''>".get_vocab("choose")."</option>\n";
    $letter = "A";
    for ($i=1;$i<=26;$i++) {
       echo "<option value='".$letter."' ";
       if ($row['type_letter'] == $letter) echo " selected";
       echo ">".$letter."</option>\n";
       $letter++;
    }
    echo "</select>";
    echo "</TD>\n";
    echo "</TR><TR>\n";
    echo "<TD>".get_vocab("type_order").get_vocab("deux_points")."</TD>\n";
    echo "<TD><input type=\"text\" name=\"order_display\" value=\"".htmlspecialchars($row["order_display"])."\" size=\"20\" /></TD>\n";
    echo "</TR>";
   if ($row["couleur"]  != '') {
        echo "<TR>\n";
        echo "<TD>".get_vocab("type_color").get_vocab("deux_points")."</TD>\n";
        echo "<TD bgcolor=\"".$tab_couleur[$row["couleur"]]."\">&nbsp;</TD>";
        echo "</TR>";
    }
    echo "</TABLE>\n";
    echo get_vocab("type_color").get_vocab("deux_points");
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

    <input type=submit name="change_type"
    value="<?php echo get_vocab("save") ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit name="change_done" value="<?php echo get_vocab("back") ?>">
    </CENTER>
    </form>



</body>
</html>