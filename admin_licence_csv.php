<?php
#########################################################################
#                            admin_licence_csv.php                  #
#                                                                       #
#               script d'importation des licences                    #
#               à partir d'un export simple FFT fichier CSV                      #
#               Dernière modification : 110/09/2012                      #
#                                                                       #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2012 Stéphane Duchemin
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

# print the page header
print_header("","","","",$type="with_session", $page="admin");

?>
<p>| <a href="admin_user.php"><?php echo get_vocab("back");?></a> |</p>

<?php
$reg_data = isset($_POST["reg_data"]) ? $_POST["reg_data"] : NULL;
$is_posted = isset($_POST["is_posted"]) ? $_POST["is_posted"] : NULL;
$test_login_existant='';
$test_nom_prenom_existant='';
$test_login='';


if ($reg_data != 'yes') {

// $long_max : doit être plus grand que la plus grande ligne trouvée dans le fichier CSV
$long_max = 8000;
if ($is_posted != '1') {
    ?>
    <form enctype="multipart/form-data" action="admin_licence_csv.php" method=post name=formulaire>
    <?php $csvfile=""; ?>
    <p><?php echo get_vocab("admin_licence_csv0"); ?><INPUT TYPE=FILE NAME="csvfile"></p>
    <INPUT TYPE=HIDDEN name=is_posted value = 1>
    <p><?php echo get_vocab("admin_licence_csv1"); ?>&nbsp;
    <INPUT TYPE=CHECKBOX NAME="en_tete" VALUE="yes" CHECKED></p>
    <INPUT TYPE=SUBMIT value = <?php echo get_vocab("submit"); ?> ><BR>
    </FORM>
    <?php

    echo get_vocab("admin_licence_csv2");

    echo get_vocab("admin_licence_csv3");


}
if ($is_posted == '1') {
    $valid = isset($_POST["valid"]) ? $_POST["valid"] : NULL;
    $en_tete = isset($_POST["en_tete"]) ? $_POST["en_tete"] : NULL;
    $csv_file = isset($_FILES["csvfile"]) ? $_FILES["csvfile"] : NULL;

    echo "<form enctype='multipart/form-data' action='admin_licence_csv.php' method=post >";
    if($csv_file['tmp_name'] != "") {
        $fp = @fopen($csv_file['tmp_name'], "r");
        if(!$fp) {
            echo get_vocab("admin_licence_csv4");
        } else {
            $row = 0;
            echo "<table border=1><tr><td><p>".get_vocab("name")."</p></td><td><p>".get_vocab("first_name")."</p></td><td><p>".get_vocab("chiffre")."</p></td><td><p>".get_vocab("lettre")."</p></td><td><p>".get_vocab("classe")."</p></td></tr>";
            $valid = 1;
            while(!feof($fp)) {
                if ($en_tete == 'yes') {
                    $data = fgetcsv ($fp, $long_max, ";");
                    $en_tete = 'no';
                }
                $data = fgetcsv ($fp, $long_max, ";");
                $num = count ($data);
                if ($num == 5) {
                $row++;
                echo "<tr>";
                for ($c=0; $c<$num; $c++) {
                    switch ($c) {
                    case 0:
                        //Nom
                        $test_nom_prenom_existant = 'no';
                        if (preg_match ("/^.{1,30}$/", $data[$c])) {
                            $test_nom = protect_data_sql($data[$c]);
                            $test_prenom = protect_data_sql($data[$c+1]);
                            $test_nom_prenom = grr_sql_count(grr_sql_query("SELECT nom FROM grr_utilisateurs WHERE (nom='$test_nom' and prenom = '$test_prenom')"));
                            if ($test_nom_prenom!='0') {
                                $test_nom_prenom_existant = 'yes';
                                echo "<td><p><font color = blue>$data[$c]</font></p></td>";
                            } else {
                                echo "<td><p>$data[$c]</p></td>";
                            }
                            $data_nom = htmlentities($data[$c]);
                            echo "<INPUT TYPE=HIDDEN name='reg_nom[$row]' value=\"$data_nom\">";
                        } else {
                        echo "<td><font color = red>???</font></td>";
                        }
                        break;
                    case 1:
                        //Prenom
                        if (preg_match ("/^.{1,30}$/", $data[$c])) {
                            if ($test_nom_prenom_existant == 'yes') {
                                echo "<td><p><font color = blue>$data[$c]</font></p></td>";
                            } else {
                                echo "<td><p>$data[$c]</p></td>";
                            }
                            $data_prenom = htmlentities($data[$c]);
                            echo "<INPUT TYPE=HIDDEN name='reg_prenom[$row]' value =\"$data_prenom\">";
                        } else {
                            echo "<td><font color = red>???</font></td>";
                            $valid = 0;
                        }
                        break;
                    case 2:
                        // Chiffre
                        if (preg_match ("/^.{".$pass_leng.",30}$/", $data[$c])) {
                            $data_chiffre = htmlentities($data[$c]);
                            echo "<td><p>$data[$c]</p></td>";
                            echo "<INPUT TYPE=HIDDEN name='reg_chiffre[$row]' value =\"$data_chiffre\">";
                        } else {
                            echo "<td><font color = red>???</font></td>";
                            $valid = 0;
                        }
                        break;
                    case 3:
                        //Lettre
                        if (preg_match ("/^.{0,30}$/", $data[$c])) {
                            $data_lettre = htmlentities($data[$c]);
							echo "<td><p>$data[$c]</p></td>";
                            echo "<INPUT TYPE=HIDDEN name='reg_lettre[$row]' value =\"$data_lettre\">";
                        } else {
                            echo "<td><font color = red>???</font></td>";
                            $valid = 0;
                        }
                        break;
					case 4:
						// Classement
                        if ((preg_match ("/^.{1,100}$/", $data[$c])) or ($data[$c]=='')){
                            $data_classement= htmlentities($data[$c]);
                            echo "<td><p>$data[$c]&nbsp;</p></td>";
                            echo "<INPUT TYPE=HIDDEN name='reg_classement[$row]' value =\"$data_classement\">";
                        } else {
                            echo "<td><font color = red>???</font></td>";
                            $valid = 0;
                        }
                        break;
                        
                    }
                }
                echo "</tr>";
                }
            }
            fclose($fp);
            echo "</table>";
            echo "<p>".get_vocab("admin_licence_csv5")."$row ".get_vocab("admin_licence_csv6")."</p>";
            if ($row > 0) {
                if ($test_login_existant == "oui") {
                    echo get_vocab("admin_licence_csv7");
                }
                if ($test_nom_prenom_existant == 'yes') {
                    echo get_vocab("admin_licence_csv8");
                }
                if ($valid == '1') {
                    echo "<input type=submit value='".get_vocab("submit") ."'>";
                    echo "<INPUT TYPE=HIDDEN name=nb_row value = $row>";
                    echo "<INPUT TYPE=HIDDEN name=reg_data value='yes'>";
                    echo "</FORM>";
                } else {
                    echo get_vocab("admin_licence_csv9");
                    echo "</FORM>";
                }
            } else {
                echo "<p>".get_vocab("admin_licence_csv10")."</p>";
            }
        }
    } else {
        echo "<p>".get_vocab("admin_licence_csv11")."</p>";
    }
}

} else {
    // Phase d'enregistrement des données
    $nb_row = isset($_POST["nb_row"]) ? $_POST["nb_row"] : NULL;
    $reg_lettre = isset($_POST["reg_lettre"]) ? $_POST["reg_lettre"] : NULL;
	$reg_chiffre = isset($_POST["reg_chiffre"]) ? $_POST["reg_chiffre"] : NULL;
    $reg_login = isset($_POST["reg_login"]) ? $_POST["reg_login"] : NULL;
    $reg_nom = isset($_POST["reg_nom"]) ? $_POST["reg_nom"] : NULL;
    $reg_prenom = isset($_POST["reg_prenom"]) ? $_POST["reg_prenom"] : NULL;
	$reg_classement = isset($_POST["reg_classement"]) ? $_POST["reg_classement"] : NULL;
	

    $nb_row++;
    for ($row=1; $row<$nb_row; $row++) {
        $reg_nom[$row] = protect_data_sql(corriger_caracteres($reg_nom[$row]));
        $reg_prenom[$row] = protect_data_sql(corriger_caracteres($reg_prenom[$row]));
		$reg_licence[$row] = $reg_chiffre[$row].$reg_lettre[$row];

        $test_login = grr_sql_count(grr_sql_query("SELECT login FROM grr_utilisateurs WHERE nom='$reg_nom[$row]' AND prenom='$reg_prenom[$row]'"));
        if ($test_login != 0) {
            $regdata = grr_sql_query("UPDATE grr_utilisateurs SET nom='".$reg_nom[$row]."',prenom='".$reg_prenom[$row]."',licence='".$reg_licence[$row]."',classement='".$reg_classement[$row]."' WHERE nom='".$reg_nom[$row]."' AND prenom='".$reg_prenom[$row]."'");
        }   
        
    }
}
?>
</body>
</html>