<?php
#########################################################################
#                            admin_import_user_csv.php                  #
#                                                                       #
#               script d'importation d'utilisateurs                     #
#                        à partir d'un fichier CSV                      #
#               Dernière modification : 17/09/2008                      #
#                                                                       #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 *
 * Modification s Duchemin
 * Ajout de la date de naissance dans l'import
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
$long_max = 30000;
if ($is_posted != '1') {
    ?>
    <form enctype="multipart/form-data" action="admin_import_users_csv.php" method=post name=formulaire>
    <?php $csvfile=""; ?>
    <p><?php echo get_vocab("admin_import_users_csv0"); ?><INPUT TYPE=FILE NAME="csvfile"></p>
    <INPUT TYPE=HIDDEN name=is_posted value = 1>
    <p><?php echo get_vocab("admin_import_users_csv1"); ?>&nbsp;
    <INPUT TYPE=CHECKBOX NAME="en_tete" VALUE="yes" CHECKED></p>
    <INPUT TYPE=SUBMIT value = <?php echo get_vocab("submit"); ?> ><BR>
    </FORM>
    <?php

    echo get_vocab("admin_import_users_csv2");

    echo get_vocab("admin_import_users_csv3");


}
if ($is_posted == '1') {
    $valid = isset($_POST["valid"]) ? $_POST["valid"] : NULL;
    $en_tete = isset($_POST["en_tete"]) ? $_POST["en_tete"] : NULL;
    $csv_file = isset($_FILES["csvfile"]) ? $_FILES["csvfile"] : NULL;

    echo "<form enctype='multipart/form-data' action='admin_import_users_csv.php' method=post >";
    if($csv_file['tmp_name'] != "") {
        $fp = @fopen($csv_file['tmp_name'], "r");
        if(!$fp) {
            echo get_vocab("admin_import_users_csv4");
        } else {
            $row = 0;
            echo "<table border=1><tr><td><p>".get_vocab("login")."</p></td><td><p>".get_vocab("name")."</p></td><td><p>".get_vocab("first_name")."</p></td><td><p>".get_vocab("pwd")."</p></td><td><p>".get_vocab("email")."</p></td><td><p>".get_vocab("datenais")."</p></td><td><p>".get_vocab("adresse")."</p></td><td><p>".get_vocab("code")."</p></td><td><p>".get_vocab("ville")."</p></td><td><p>".get_vocab("tel")."</p></td><td><p>".get_vocab("telport")."</p></td><td><p>".get_vocab("group")."</p></td></tr>";
            $valid = 1;
            while(!feof($fp)) {
                if ($en_tete == 'yes') {
                    $data = fgetcsv ($fp, $long_max, ";");
                    $en_tete = 'no';
                }
                $data = fgetcsv ($fp, $long_max, ";");
                $num = count ($data);
                if ($num == 12) {
                $row++;
                echo "<tr>";
                for ($c=0; $c<$num; $c++) {
                    switch ($c) {
                    case 0:
                        //login
                        if (preg_match ("/^[a-zA-Z0-9_.]{1,20}$/", $data[$c])) {
                            $data[$c] =    strtoupper($data[$c]);
                            $test = grr_sql_count(grr_sql_query("SELECT login FROM grr_utilisateurs WHERE login='$data[$c]'"));
                            if ($test!='0') {
                                echo "<td><p><font color = red>$data[$c]</font></p></td>";
                                echo "<INPUT TYPE=HIDDEN name='reg_stat[$row]' value=existant>";
                                $test_login_existant = "oui";
                                $login_exist = "oui";
                                $login_valeur = $data[$c];
                            } else {
                                echo "<td><p>$data[$c]</p></td>";
                                echo "<INPUT TYPE=HIDDEN name='reg_stat[$row]' value=nouveau>";
                                $login_exist = "non";
                            }
                            $data_login = htmlentities($data[$c]);
                            echo "<INPUT TYPE=HIDDEN name='reg_login[$row]' value =\"$data_login\">";
                        } else {
                            echo "<td><font color = red>???</font></td>";
                            $valid = 0;
                        }
                        break;
                    case 1:
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
                    case 2:
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
                    case 3:
                        // Mot de passe
                        if (preg_match ("/^.{".$pass_leng.",30}$/", $data[$c])) {
                            $data_mdp = htmlentities($data[$c]);
                            echo "<td><p>$data[$c]</p></td>";
                            echo "<INPUT TYPE=HIDDEN name='reg_mdp[$row]' value =\"$data_mdp\">";
                        } else {
                            echo "<td><font color = red>???</font></td>";
                            $valid = 0;
                        }
                        break;
                    case 4:
                        // Adresse E-mail
                        if ((preg_match ("/^.{1,100}$/", $data[$c])) or ($data[$c]=='')){
                            $data_email = htmlentities($data[$c]);
                            echo "<td><p>$data[$c]&nbsp;</p></td>";
                            echo "<INPUT TYPE=HIDDEN name='reg_email[$row]' value =\"$data_email\">";
                        } else if ($data[$c]=='-') {
                            echo "<td><font color = red>???</font></td>";
                            echo "<INPUT TYPE=HIDDEN name='reg_email[$row]' value =\"\">";
                        } else {
                            echo "<td><font color = red>???</font></td>";
                            //$valid = 0;
                        }
                        break;
					case 5:
                        // Date de naissance
                        if (preg_match ("/^.{".$pass_leng.",30}$/", $data[$c])) {
                            $data_date = htmlentities($data[$c]);
                            echo "<td><p>$data[$c]</p></td>";
                            echo "<INPUT TYPE=HIDDEN name='reg_date[$row]' value =\"$data_date\">";
                        } else {
                            echo "<td><font color = red>???</font></td>";
                            $valid = 0;
                        }
                        break;
					case 6:
                        //Adresse
                        if ((preg_match ("/^.{1,30}$/", $data[$c])) or ($data[$c]=='')){
                            $data_adresse = htmlentities($data[$c]);
							echo "<td><p>$data[$c]</p></td>";
                            echo "<INPUT TYPE=HIDDEN name='reg_adresse[$row]' value=\"$data_adresse\">";
                        } else {
                        echo "<td><font color = red>???</font></td>";
                        }
                        break;
					case 7:
                        // Code postal
                        if ((preg_match ("/^.{".$pass_leng.",30}$/", $data[$c])) or ($data[$c]=='')) {
                            $data_code = htmlentities($data[$c]);
                            echo "<td><p>$data[$c]</p></td>";
                            echo "<INPUT TYPE=HIDDEN name='reg_code[$row]' value =\"$data_code\">";
                        } else {
                            echo "<td><font color = red>???</font></td>";
                        }
                        break;
					case 8:
                        // Ville
                       if ((preg_match ("/^.{1,30}$/", $data[$c])) or ($data[$c]=='')) {
                            $data_ville = htmlentities($data[$c]);
							echo "<td><p>$data[$c]</p></td>";
                            echo "<INPUT TYPE=HIDDEN name='reg_ville[$row]' value=\"$data_ville\">";
                        } else {
                        echo "<td><font color = red>???</font></td>";
                        }
                        break;
					case 9:
                        // Téléphone
                        if ((preg_match ("/^.{".$pass_leng.",30}$/", $data[$c]))or ($data[$c]=='')) {
                            $data_tel = htmlentities($data[$c]);
                            echo "<td><p>$data[$c]</p></td>";
                            echo "<INPUT TYPE=HIDDEN name='reg_tel[$row]' value =\"$data_tel\">";
                        } else {
                            echo "<td><font color = red>???</font></td>";
                        }
                        break;
					case 10:
                        // Téléphone portable
                        if ((preg_match ("/^.{".$pass_leng.",30}$/", $data[$c]))or ($data[$c]=='')) {
                            $data_telport = htmlentities($data[$c]);
                            echo "<td><p>$data[$c]</p></td>";
                            echo "<INPUT TYPE=HIDDEN name='reg_telport[$row]' value =\"$data_telport\">";
                        } else {
                            echo "<td><font color = red>???</font></td>";
                        }
                        break;
					case 11:
                        // Groupe
                        if ($data[$c]) {
                            $data_groupe = htmlentities($data[$c]);
                            echo "<td><p>$data[$c]</p></td>";
                            echo "<INPUT TYPE=HIDDEN name='reg_groupe[$row]' value =\"$data_groupe\">";
                        } else {
                            echo "<td><font color = red>???</font></td>";
                        }
                        break;
                    }
                }
                echo "</tr>";
                }
            }
            fclose($fp);
            echo "</table>";
            echo "<p>".get_vocab("admin_import_users_csv5")."$row ".get_vocab("admin_import_users_csv6")."</p>";
            if ($row > 0) {
                if ($test_login_existant == "oui") {
                    echo get_vocab("admin_import_users_csv7");
                }
                if ($test_nom_prenom_existant == 'yes') {
                    echo get_vocab("admin_import_users_csv8");
                }
                if ($valid == '1') {
                    echo "<input type=submit value='".get_vocab("submit") ."'>";
                    echo "<INPUT TYPE=HIDDEN name=nb_row value = $row>";
                    echo "<INPUT TYPE=HIDDEN name=reg_data value='yes'>";
                    echo "</FORM>";
                } else {
                    echo get_vocab("admin_import_users_csv9");
                    echo "</FORM>";
                }
            } else {
                echo "<p>".get_vocab("admin_import_users_csv10")."</p>";
            }
        }
    } else {
        echo "<p>".get_vocab("admin_import_users_csv11")."</p>";
    }
}

} else {
    // Phase d'enregistrement des données
    $nb_row = isset($_POST["nb_row"]) ? $_POST["nb_row"] : NULL;
    $reg_stat = isset($_POST["reg_stat"]) ? $_POST["reg_stat"] : NULL;
    $reg_login = isset($_POST["reg_login"]) ? $_POST["reg_login"] : NULL;
    $reg_nom = isset($_POST["reg_nom"]) ? $_POST["reg_nom"] : NULL;
    $reg_prenom = isset($_POST["reg_prenom"]) ? $_POST["reg_prenom"] : NULL;
    $reg_email = isset($_POST["reg_email"]) ? $_POST["reg_email"] : NULL;
    $reg_mdp = isset($_POST["reg_mdp"]) ? $_POST["reg_mdp"] : NULL;
	$reg_date = isset($_POST["reg_date"]) ? $_POST["reg_date"] : NULL;
	$reg_adresse = isset($_POST["reg_adresse"]) ? $_POST["reg_adresse"] : NULL;
	$reg_code = isset($_POST["reg_code"]) ? $_POST["reg_code"] : NULL;
	$reg_ville = isset($_POST["reg_ville"]) ? $_POST["reg_ville"] : NULL;
	$reg_tel = isset($_POST["reg_tel"]) ? $_POST["reg_tel"] : NULL;
	$reg_telport = isset($_POST["reg_telport"]) ? $_POST["reg_telport"] : NULL;
	$reg_groupe = isset($_POST["reg_groupe"]) ? $_POST["reg_groupe"] : NULL;

    $nb_row++;
    for ($row=1; $row<$nb_row; $row++) {
        $reg_mdp[$row] = md5(unslashes($reg_mdp[$row]));
        // On nettoie les windozeries
        $reg_nom[$row] = protect_data_sql(corriger_caracteres($reg_nom[$row]));
        $reg_prenom[$row] = protect_data_sql(corriger_caracteres($reg_prenom[$row]));
        $reg_email[$row] = protect_data_sql(corriger_caracteres($reg_email[$row]));


        $test_login = grr_sql_count(grr_sql_query("SELECT login FROM grr_utilisateurs WHERE login='$reg_login[$row]'"));
        if ($test_login == 0) {
            $regdata = grr_sql_query("INSERT INTO grr_utilisateurs SET nom='".$reg_nom[$row]."',prenom='".$reg_prenom[$row]."',login='".$reg_login[$row]."',email='".$reg_email[$row]."',password='".protect_data_sql($reg_mdp[$row])."',datenais='".$reg_date[$row]."',adresse='".$reg_adresse[$row]."',code='".$reg_code[$row]."',ville='".$reg_ville[$row]."',tel='".$reg_tel[$row]."',telport='".$reg_telport[$row]."',group_id='".$reg_groupe[$row]."',statut='utilisateur',etat='actif',source='local'");
        } else {
            $regdata = grr_sql_query("UPDATE grr_utilisateurs SET nom='".$reg_nom[$row]."',prenom='".$reg_prenom[$row]."',email='".$reg_email[$row]."',password='".protect_data_sql($reg_mdp[$row])."',datenais='".$reg_date[$row]."',adresse='".$reg_adresse[$row]."',code='".$reg_code[$row]."',ville='".$reg_ville[$row]."',tel='".$reg_tel[$row]."',telport='".$reg_telport[$row]."',group_id='".$reg_groupe[$row]."',statut='utilisateur',etat='actif',source='local' WHERE login='".$reg_login[$row]."'");
        }
        if (!$regdata) {
            echo "<p><font color=red>".$reg_login[$row].get_vocab("deux_points").get_vocab("message_records_error")."</font></p>";
        } else {
            if ($reg_stat[$row] == "nouveau") {
                echo "<p>".$reg_login[$row].get_vocab("deux_points").get_vocab("admin_import_users_csv12")."</p>";
            } else {
                echo "<p>".$reg_login[$row].get_vocab("deux_points").get_vocab("message_records")."</p>";
            }
        }
    }
}
?>
</body>
</html>