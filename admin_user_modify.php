<?php
#########################################################################
#                        admin_user_modify.php                          #
#                                                                       #
#            Interface de modification/création d'un utilisateur        #
#                                                                       #
#            Dernière modification : 01/11/2009                       #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 * D'après http://mrbs.sourceforge.net/
 *
 * Modification S Duchemin
 * Refonte  de l'interface formulaire
 * Ajout de l'adresse, tél, abonnement, date de naissance (accessible et modifiable dans l'interface personnelle de l'utilisateur)
 * Affichage de la photo si elle est disponible
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
$day   = date("d");
$month = date("m");
$year  = date("Y");

if(authGetUserLevel(getUserName(),-1) < 5)
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}
#If we dont know the right date then make it up
unset($user_login);
$user_login = isset($_GET["user_login"]) ? $_GET["user_login"] : NULL;
$valid = isset($_GET["valid"]) ? $_GET["valid"] : NULL;
$msg='';
$user_nom='';
$user_prenom='';
$user_datenais='';
$user_tel='';
$user_telport='';
$user_mail='';
$user_statut='';
$user_abt='';
$user_source='local';
$user_group='';
$user_etat='';
$user_adresse='';
$user_code='';
$user_ville='';
$user_invite='';
$user_inviteactif='';
$user_champio='';
$user_solo='';
$user_licence='';
$user_classement='';
$user_badge='';
$display="";
$retry='';



if ($valid == "yes") {
    $reg_nom = isset($_GET["reg_nom"]) ? $_GET["reg_nom"] : NULL;
    $reg_prenom = isset($_GET["reg_prenom"]) ? $_GET["reg_prenom"] : NULL;
    $reg_datenais = isset($_GET["reg_datenais"]) ? $_GET["reg_datenais"] : NULL;
    $reg_tel = isset($_GET["reg_tel"]) ? $_GET["reg_tel"] : NULL;
    $reg_telport = isset($_GET["reg_telport"]) ? $_GET["reg_telport"] : NULL;
    $new_login = isset($_GET["new_login"]) ? $_GET["new_login"] : NULL;
    $reg_password = isset($_GET["reg_password"]) ? $_GET["reg_password"] : NULL;
    $reg_password2 = isset($_GET["reg_password2"]) ? $_GET["reg_password2"] : NULL;
    $reg_statut = isset($_GET["reg_statut"]) ? $_GET["reg_statut"] : NULL;
    $reg_abt = isset($_GET["reg_abt"]) ? $_GET["reg_abt"] : NULL;
    $reg_email = isset($_GET["reg_email"]) ? $_GET["reg_email"] : NULL;
    $reg_etat = isset($_GET["reg_etat"]) ? $_GET["reg_etat"] : NULL;
    $reg_source = isset($_GET["reg_source"]) ? $_GET["reg_source"] : NULL;
	$reg_group = isset($_GET["reg_group"]) ? $_GET["reg_group"] : NULL;
    $reg_adresse = isset($_GET["reg_adresse"]) ? $_GET["reg_adresse"] : NULL;
	$reg_code = isset($_GET["reg_code"]) ? $_GET["reg_code"] : NULL;
	$reg_ville = isset($_GET["reg_ville"]) ? $_GET["reg_ville"] : NULL;
	$reg_invite = isset($_GET["reg_invite"]) ? $_GET["reg_invite"] : NULL;
	$reg_inviteactif = isset($_GET["reg_inviteactif"]) ? $_GET["reg_inviteactif"] : NULL;
	$reg_champio = isset($_GET["reg_champio"]) ? $_GET["reg_champio"] : NULL;
	$reg_solo = isset($_GET["reg_solo"]) ? $_GET["reg_solo"] : NULL;
	$reg_licence= isset($_GET["reg_licence"]) ? $_GET["reg_licence"] : NULL;
	$reg_classement= isset($_GET["reg_classement"]) ? $_GET["reg_classement"] : NULL;
	$reg_badge= isset($_GET["reg_badge"]) ? $_GET["reg_badge"] : NULL;

    if (($reg_nom == '') or ($reg_prenom == '') or ($reg_datenais == '')) {
        $msg = get_vocab("please_enter_name");
        $retry = 'yes';
    } else {
        //
        // actions si un nouvel utilisateur a été défini
        //
        if ((isset($new_login)) and ($new_login!='') and (preg_match ("/^[a-zA-Z0-9_.]{1,20}$/", $new_login)) ) {
            $new_login = strtoupper($new_login);
            $reg_password_c = md5($reg_password);
            if (($reg_password != $reg_password2) or (strlen($reg_password) < $pass_leng)) {
                $msg = get_vocab("passwd_error");
                $retry = 'yes';
            } else {
                $sql = "SELECT * FROM ".$_COOKIE["table_prefix"]."_utilisateurs WHERE login = '".$new_login."'";
                $res = grr_sql_query($sql);
                $nombreligne = grr_sql_count ($res);
                if ($nombreligne != 0) {
                    $msg = get_vocab("error_exist_login");
                    $retry = 'yes';
                } else {
                    $sql = "INSERT INTO ".$_COOKIE["table_prefix"]."_utilisateurs SET
                    nom='".protect_data_sql($reg_nom)."',
                    prenom='".protect_data_sql($reg_prenom)."',
                    login='".protect_data_sql($new_login)."',
                    password='".protect_data_sql($reg_password_c)."',
                    statut='".protect_data_sql($reg_statut)."',
                    abt='".protect_data_sql($reg_abt)."',
                    email='".protect_data_sql($reg_email)."',
                    etat='".protect_data_sql($reg_etat)."',
                    default_area = '1',
                    default_room = '-1',
                    default_style = 'forestier',
                    default_list_type = 'select',
                    default_language = 'fr',
                    source='local',
                    datenais='".protect_data_sql($reg_datenais)."',
					group_id='".protect_data_sql($reg_group)."',
					tel='".protect_data_sql($reg_tel)."',
					telport='".protect_data_sql($reg_telport)."',
					adresse='".protect_data_sql($reg_adresse)."',
					code='".protect_data_sql($reg_code)."',
					ville='".protect_data_sql($reg_ville)."',
					invite='".protect_data_sql($reg_invite)."',
					inviteactif='".protect_data_sql($reg_inviteactif)."',
					champio='".protect_data_sql($reg_champio)."',
					solo='".protect_data_sql($reg_solo)."',
					licence='".protect_data_sql($reg_licence)."',
					classement='".protect_data_sql($reg_classement)."',
					badge='".protect_data_sql($reg_badge)."'";
                    if (grr_sql_command($sql) < 0)
                        {fatal_error(0, get_vocab("msg_login_created_error") . grr_sql_error());
                    } else {
                        $msg = get_vocab("msg_login_created");
                    }
                    $user_login = $new_login;
                }
            }
//
//action s'il s'agit d'une modification
//
        } else if ((isset($user_login)) and ($user_login!='')) {
            if (isset($reg_source)) {
                // On demande un changement de la source ext->local
                $reg_password_c = md5($reg_password);
                if (($reg_password != $reg_password2) or (strlen($reg_password) < $pass_leng)) {
                    $msg = get_vocab("passwd_error");
                    $retry = 'yes';
                }
                $source = "source='local',password='".protect_data_sql($reg_password_c)."',";
            } else {
                $source = "";
            }
        if ($retry != 'yes') {
            $sql = "UPDATE ".$_COOKIE["table_prefix"]."_utilisateurs SET nom='".protect_data_sql($reg_nom)."',
            prenom='".protect_data_sql($reg_prenom)."',
            statut='".protect_data_sql($reg_statut)."',
            abt='".protect_data_sql($reg_abt)."',
            email='".protect_data_sql($reg_email)."',".$source."
            etat='".protect_data_sql($reg_etat)."',
            datenais='".protect_data_sql($reg_datenais)."',
            tel='".protect_data_sql($reg_tel)."',
			group_id='".protect_data_sql($reg_group)."',
            telport='".protect_data_sql($reg_telport)."',
			adresse='".protect_data_sql($reg_adresse)."',
			code='".protect_data_sql($reg_code)."',
			ville='".protect_data_sql($reg_ville)."',
			invite='".protect_data_sql($reg_invite)."',
			inviteactif='".protect_data_sql($reg_inviteactif)."',
			champio='".protect_data_sql($reg_champio)."',
			solo='".protect_data_sql($reg_solo)."',
			licence='".protect_data_sql($reg_licence)."',
			classement='".protect_data_sql($reg_classement)."',
			badge='".protect_data_sql($reg_badge)."'
            WHERE login='".protect_data_sql($user_login)."'";
            if (grr_sql_command($sql) < 0)
                {fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
            } else {
                $msg = get_vocab("message_records");
            }

            // Cas où on a déclaré un utilisateur inactif, on le supprime dans les tables ".$_COOKIE["table_prefix"]."_j_user_area,  ".$_COOKIE["table_prefix"]."_j_mailuser_room
            if ($reg_etat != 'actif') {
                $sql = "DELETE FROM ".$_COOKIE["table_prefix"]."_j_user_area WHERE login='$user_login'";
                if (grr_sql_command($sql) < 0) fatal_error(0, get_vocab('message_records_error') . grr_sql_error());
                $sql = "DELETE FROM ".$_COOKIE["table_prefix"]."_j_mailuser_room WHERE login='$user_login'";
                if (grr_sql_command($sql) < 0) fatal_error(0, get_vocab('message_records_error') . grr_sql_error());
            }

            // Cas où on a déclaré un utilisateur visiteur, on le supprime dans les tables ".$_COOKIE["table_prefix"]."_j_user_area, ".$_COOKIE["table_prefix"]."_j_mailuser_room et ".$_COOKIE["table_prefix"]."_j_user_room

            if ($reg_statut=='visiteur') {
                $sql = "DELETE FROM ".$_COOKIE["table_prefix"]."_j_user_room WHERE login='$user_login'";
                if (grr_sql_command($sql) < 0)
                    fatal_error(0, get_vocab('message_records_error') . grr_sql_error());
                $sql = "DELETE FROM ".$_COOKIE["table_prefix"]."_j_mailuser_room WHERE login='$user_login'";
                if (grr_sql_command($sql) < 0)
                    fatal_error(0, get_vocab('message_records_error') . grr_sql_error());
                $sql = "DELETE FROM ".$_COOKIE["table_prefix"]."_j_user_area WHERE login='$user_login'";
                if (grr_sql_command($sql) < 0)
                    fatal_error(0, get_vocab('message_records_error') . grr_sql_error());
            }
            if ($reg_statut=='administrateur') {
                $sql = "DELETE FROM ".$_COOKIE["table_prefix"]."_j_user_room WHERE login='$user_login'";
                if (grr_sql_command($sql) < 0)
                    fatal_error(0, get_vocab('message_records_error') . grr_sql_error());
                $sql = "DELETE FROM ".$_COOKIE["table_prefix"]."_j_user_area WHERE login='$user_login'";
                if (grr_sql_command($sql) < 0)
                    fatal_error(0, get_vocab('message_records_error') . grr_sql_error());
            }
        }

        } else {
            $msg = get_vocab("only_letters_and_numbers");
            $retry = 'yes';
        }
    }
    if ($retry == 'yes') {
        $user_nom = $reg_nom;
        $user_prenom = $reg_prenom;
        $user_statut = $reg_statut;
        $user_abt = $reg_abt;
        $user_mail = $reg_email;
        $user_etat = $reg_etat;
        $user_datenais = $reg_datenais;
        $user_tel = $reg_tel;
        $user_telport = $reg_telport;
		$user_adresse = $reg_adresse;
		$user_group = $reg_group;
		$user_code = $reg_code;
		$user_ville = $reg_ville;
		$user_invite = $reg_invite;
		$user_inviteactif = $reg_inviteactif;
		$user_champio = $reg_champio;
		$user_solo = $reg_solo;
		$user_licence = $reg_licence;
		$user_classement = $reg_classement;
		$user_badge = $reg_badge;
		
    }
}

// On appelle les informations de l'utilisateur pour les afficher :
if (isset($user_login) and ($user_login!='')) {
    $sql = "SELECT nom, prenom, statut, etat, email, source, datenais, tel, telport, abt, adresse, code, ville, invite, group_id, licence, classement, champio, badge, solo, inviteactif FROM ".$_COOKIE["table_prefix"]."_utilisateurs WHERE login='$user_login'";
    $res = grr_sql_query($sql);
    if ($res) {
        for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
        $user_nom = $row[0];
        $user_prenom = $row[1];
        $user_statut = $row[2];
        $user_etat = $row[3];
        $user_mail = $row[4];
        $user_source = $row[5];
        $user_datenais = $row[6];
        $user_tel = $row[7];
        $user_telport = $row[8];
        $user_abt = $row[9];
		$user_adresse= $row[10];
		$user_code = $row[11];
		$user_ville = $row[12];
		$user_invite = $row[13];
		$user_group= $row[14];
		$user_licence = $row[15];
		$user_classement= $row[16];
		$user_champio= $row[17];
		$user_badge= $row[18];
		$user_solo= $row[19];
		$user_inviteactif= $row[20];
        }
    }
}
if((authGetUserLevel(getUserName(),-1) < 1) and ($authentification_obli==1))
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}


# print the page header
print_header("","","","",$type="with_session", $page="admin");

echo "<noscript>";
echo "<font color='red'>$msg</font>";
echo "</noscript>";
if (($msg) and (!($javascript_info_admin_disabled)))  {
    echo "<script type=\"text/javascript\" language=\"javascript\">";
    echo "<!--\n";
    echo " alert(\"".$msg."\")";
    echo "//-->";
    echo "</script>";
    unset($msg);
}

if (isset($user_login) and ($user_login!='')) {
    echo "<h2>".get_vocab('admin_user_modify_modify.php')."</h2>";
} else {
    echo "<h2>".get_vocab('admin_user_modify_create.php')."</h2>";
}


?>
<p class=bold>
| <a href="admin_user.php?display=<?php echo $display; ?>"><?php echo get_vocab("back"); ?></a> |
<?php
if (isset($user_login) and ($user_login!='')) {
    echo "<a href=\"admin_user_modify.php?display=$display\">".get_vocab("display_add_user")."</a> | ";
    if (($user_source=='local') or ($user_source==''))
    echo "<a href=\"admin_change_pwd.php?user_login=$user_login\">".get_vocab("change_pwd")."</a> |";
} ?>
<br><?php echo get_vocab("required"); ?>
</p>
<form action="admin_user_modify.php?display=<?php echo $display; ?>" method='GET'>

<span class="norme">
<?php
echo get_vocab("login")." *".get_vocab("deux_points");
if (isset($user_login) and ($user_login!='')) {
    echo $user_login."&nbsp;&nbsp;&nbsp;<a href=\"images/".$user_login.".jpg\">Voir la photo</a>";
    echo "<input type=\"hidden\" name=\"reg_login\" value=\"$user_login\" />\n";
} else {
    echo "<input type=\"text\" name=\"new_login\" size=\"20\" value=\"".htmlentities($user_login)."\" />\n";
}
echo "<table border=\"0\" cellpadding=\"5\"><tr>";
echo "<td>".get_vocab("last_name")." *".get_vocab("deux_points")."</td><td><input type=\"text\" name=\"reg_nom\" size=\"20\" value=\"";
if ($user_nom) echo htmlspecialchars($user_nom);
echo "\"></td>\n";
echo "<td>".get_vocab("first_name")." *".get_vocab("deux_points")."</td><td><input type=\"text\" name=\"reg_prenom\" size=\"20\" value=\"";
if ($user_nom) echo htmlspecialchars($user_prenom);
echo "\"></td>\n";
echo "<td>".get_vocab("datenais")." * AAAAMMJJ".get_vocab("deux_points")."</td><td><input type=\"date\" name=\"reg_datenais\" size=\"20\" value=\"";
if ($user_datenais) echo ($user_datenais);
echo "\"></td>\n";
echo "<td></td><td></td></tr>\n";

echo "<tr><td>".get_vocab("mail_user").get_vocab("deux_points")."</td><td><input type=\"text\" name=\"reg_email\" size=\"25\" value=\"";
if ($user_mail) echo htmlspecialchars($user_mail);
echo "\"></td>\n";

echo "<td>".get_vocab("statut").get_vocab("deux_points")."</td>\n";
echo "<td><SELECT name=\"reg_statut\" size=\"1\">\n";
echo "<option value=\"utilisateur\" "; if ($user_statut == "utilisateur") { echo "SELECTED";}; echo ">".get_vocab("statut_user")."</option>\n";
echo "<option value=\"visiteur\" "; if ($user_statut == "visiteur") { echo "SELECTED";}; echo ">".get_vocab("statut_visitor")."</option>\n";
//if ((getSettingValue("ldap_statut") == '') or (getSettingValue("sso_statut") == '')) {
   echo "<option value=\"administrateur\" "; if ($user_statut == "administrateur") { echo "SELECTED";}; echo ">".get_vocab("statut_administrator")."</option>\n";
//}
echo "</select></td>\n";

echo "<td>".get_vocab("activ_no_activ").get_vocab("deux_points")."</td>";
echo "<td><select name=\"reg_etat\" size=\"1\">\n";
echo "<option value=\"actif\" ";
if ($user_etat == "actif")  echo "SELECTED";
echo ">".get_vocab("activ_user")."</option>\n";
echo "<option value=\"inactif\" ";
if ($user_etat == "inactif")  echo "SELECTED";
echo ">".get_vocab("no_activ_user")."</option>\n";
echo "</select></td></tr>\n";
echo "<td>".get_vocab("tel")."".get_vocab("deux_points")."</td><td><input type=\"text\" name=\"reg_tel\"  size=\"20\"value=\"";
if ($user_tel) echo ($user_tel);
echo "\"></td>\n";
echo "<td>".get_vocab("telport")."".get_vocab("deux_points")."</td><td><input type=\"text\" name=\"reg_telport\" size=\"20\" value=\"";
if ($user_telport) echo ($user_telport);
echo "\"></td>\n";
echo "<td>".get_vocab("abonnement").get_vocab("deux_points")."</td>";
echo "<td><select name=\"reg_abt\" size=\"1\">\n";
$sql = "select id, abt_name from ".$_COOKIE["table_prefix"]."_abt order by order_display";
$res = grr_sql_query($sql);
    while ($resultat = mysqli_fetch_row($res)) {
    echo "<option value='$resultat[0]'";
	if ($user_abt == $resultat[0]) {
	echo " SELECTED>";
	}else{ echo ">";
	}
    echo $resultat[1].'</option>'."\n";
    }
    echo '</select>'."\n"; 
echo "</td></tr>\n";
echo "<td>".get_vocab("adresse")."".get_vocab("deux_points")."</td><td><input type=\"text\" name=\"reg_adresse\"  size=\"20\"value=\"";
if ($user_adresse) echo ($user_adresse);
echo "\"></td>\n";
echo "<td>".get_vocab("code")."".get_vocab("deux_points")."</td><td><input type=\"text\" name=\"reg_code\" size=\"10\" value=\"";
if ($user_code) echo ($user_code);
echo "\"></td>\n";
echo "<td>".get_vocab("ville").get_vocab("deux_points")."</td><td><input type=\"text\" name=\"reg_ville\"  size=\"20\"value=\"";
if ($user_ville) echo ($user_ville);
echo "\"></td>\n";
echo "</td></tr>\n";
$compteur = getSettingValue("compteurinvite");
$compteurinvite = (int)$compteur;
echo "<td>".get_vocab("invite").get_vocab("deux_points")."<span title=\"Activer pour un joueur qui souhaite inviter un joueur ext&eacute;rieur. R&eacute;glez le nombre d'invitations\"><img src=\"img_grr/pointinter.gif\"></span></td>";
echo "<td><select name=\"reg_invite\" size=\"1\">\n";
for ($c = 0; $c <= $compteurinvite; $c++){
echo "<option value=\"$c\"";
if ($user_invite == $c ) echo "SELECTED";
echo ">".$c."</option>\n";
}
echo "</select>\n";
echo "<select name=\"reg_inviteactif\" size=\"1\">\n";
echo "<option value=\"actif\" ";
if ($user_inviteactif == "actif")  echo "SELECTED";
echo ">".get_vocab("activ_user")."</option>\n";
echo "<option value=\"inactif\" ";
if (($user_inviteactif == "inactif") OR ($user_inviteactif != "actif")) echo "SELECTED";
echo ">".get_vocab("no_activ_user")."</option>\n";
echo "</select></td>\n";
echo "<td>".get_vocab("admin_group.php").get_vocab("deux_points")."</td>";
echo "<td><select name=\"reg_group\" size=\"1\">\n";
$sql = "select id, group_name from ".$_COOKIE["table_prefix"]."_group order by order_display";
$res = grr_sql_query($sql);
    while ($resultat = mysqli_fetch_row($res)) {
    echo "<option value='$resultat[0]'";
	if ($user_group == $resultat[0]) {
	echo " SELECTED>";
	}else{ echo ">";
	}
    echo $resultat[1].'</option>'."\n";
    }
    echo '</select>'."\n"; 
echo "</td>\n";
echo "<td>".get_vocab("championnat").get_vocab("deux_points")."<span title=\"Activer pour un joueur qui souhaite r&eacute;server 2 heures cons&eacute;cutives en championnat\"><img src=\"img_grr/pointinter.gif\"></span></td>";
echo "<td><select name=\"reg_champio\" size=\"1\">\n";
echo "<option value=\"actif\" ";
if ($user_champio == "actif")  echo "SELECTED";
echo ">".get_vocab("activ_user")."</option>\n";
echo "<option value=\"inactif\" ";
if (($user_champio == "inactif") OR ($user_champio != "actif")) echo "SELECTED";
echo ">".get_vocab("no_activ_user")."</option>\n";
echo "</select></td></tr>\n";
echo "<tr><td>".get_vocab("licence").get_vocab("deux_points");
echo "</td><td><input type=text name=reg_licence size=15";
if ($user_licence) { echo " value=\"".htmlspecialchars($user_licence)."\"></td>";} else {echo">"; }
echo "<td>".get_vocab("classement").get_vocab("deux_points");
echo "</td><td><input type=text name=reg_classement size=5";
if ($user_classement) { echo " value=\"".htmlspecialchars($user_classement)."\"></td>";} else {echo">"; }
echo "<td>".get_vocab("solo").get_vocab("deux_points")."<span title=\"Activer pour un joueur qui souhaite jouer seul\"><img src=\"img_grr/pointinter.gif\"></span></td>";
echo "<td><select name=\"reg_solo\" size=\"1\">\n";
echo "<option value=\"actif\" ";
if ($user_solo == "actif")  echo "SELECTED";
echo ">".get_vocab("activ_user")."</option>\n";
echo "<option value=\"inactif\" ";
if (($user_solo == "inactif") OR ($user_solo != "actif")) echo "SELECTED";
echo ">".get_vocab("no_activ_user")."</option>\n";
echo "</select></td></tr>\n";
echo "<tr><td>".get_vocab("code_badge").get_vocab("deux_points");
echo "</td><td><input type=text name=reg_badge size=20";
if ($user_badge) { echo " value=\"".htmlspecialchars($user_badge)."\"></td>";} else {echo">"; }
echo "</select></td>\n";
echo "<td></td><td><a href=\"admin_user_compta.php?user_login=$user_login&amp;year=".getSettingValue("default_year")."\">Fiche comptable</a></td></tr>";
echo "</table>";

if (!(isset($user_login)) or ($user_login=='')) {
    echo "<br>".get_vocab("pwd_toot_short")." *".get_vocab("deux_points")."<input type=\"password\" name=\"reg_password\" size=\"20\" />\n";
    echo "<br>".get_vocab("confirm_pwd")." *".get_vocab("deux_points")."<input type=\"password\" name=\"reg_password2\" size=\"20\" />\n";
}

echo "<br>";
if ($user_source == 'ext') {
    echo "<br><br><table border=\"1\" cellpadding=\"5\" cellspacing=\"1\" width=\"100%\"><tr><td>\n";
    echo get_vocab("authentification")." <b>".get_vocab("Externe")."</b><br>\n";
    echo "<input type=\"checkbox\" name=\"reg_source\" />".get_vocab("Changer_source_utilisateur_local")."<br>\n";
    echo "<br>".get_vocab("pwd_toot_short")." *".get_vocab("deux_points")."<input type=password name=reg_password size=20>\n";
    echo "<br>".get_vocab("confirm_pwd")." *".get_vocab("deux_points")."<input type=password name=reg_password2 size=20>\n";
    echo "</td></tr></table>\n";
}
echo "<input type=\"hidden\" name=\"valid\" value=\"yes\" />\n";
if (isset($user_login)) echo "<input type=\"hidden\" name=\"user_login\" value=\"".$user_login."\" />\n";
echo "<br><center><input type=\"submit\" value=\"".get_vocab("submit")."\" /></center>\n";
echo "</span></form>\n";

// On affiche la liste des privilèges de cet utilisateurs
if ((isset($user_login)) and ($user_login!='')) {
  echo "<h2>".get_vocab('liste_privileges').$user_prenom." ".$user_nom." :</h2>";
  $a_privileges = 'n';
  $req_area = "select id, area_name, access from ".$_COOKIE["table_prefix"]."_area order by order_display";
  $res_area = grr_sql_query($req_area);
  if ($res_area) {
    for ($i = 0; ($row_area = grr_sql_row($res_area, $i)); $i++) {
        // On teste si l'utilisateur administre le domaine
        $test_admin = grr_sql_query1("select count(id_area) from ".$_COOKIE["table_prefix"]."_j_useradmin_area j where j.login = '".$user_login."' and j.id_area='".$row_area[0]."'");
        if ($test_admin >= 1) $is_admin = 'y'; else $is_admin = 'n';
        // On teste si l'utilisateur gère des ressources dans ce domaine
        $nb_room = grr_sql_query1("select count(r.room_name) from ".$_COOKIE["table_prefix"]."_room r
        left join ".$_COOKIE["table_prefix"]."_area a on r.area_id=a.id
        where a.id='".$row_area[0]."'");

        $req_room = "select r.room_name from ".$_COOKIE["table_prefix"]."_room r
        left join ".$_COOKIE["table_prefix"]."_j_user_room j on r.id=j.id_room
        left join ".$_COOKIE["table_prefix"]."_area a on r.area_id=a.id
        where j.login = '".$user_login."' and a.id='".$row_area[0]."'";
        $res_room = grr_sql_query($req_room);
        $is_gestionnaire = '';
        if ($res_room) {
            if ((grr_sql_count($res_room) == $nb_room) and ($nb_room!=0))
                $is_gestionnaire = $vocab["all_rooms"];
            else
            for ($j = 0; ($row_room = grr_sql_row($res_room, $j)); $j++) {
                $is_gestionnaire .= $row_room[0]."<br>";
            }
        }
        // On teste si l'utilisateur reçoit des mails automatiques
        $req_mail = "select r.room_name from ".$_COOKIE["table_prefix"]."_room r
        left join ".$_COOKIE["table_prefix"]."_j_mailuser_room j on r.id=j.id_room
        left join ".$_COOKIE["table_prefix"]."_area a on r.area_id=a.id
        where j.login = '".$user_login."' and a.id='".$row_area[0]."'";
        $res_mail = grr_sql_query($req_mail);
        $is_mail = '';
        if ($res_mail) {
            for ($j = 0; ($row_mail = grr_sql_row($res_mail, $j)); $j++) {
                $is_mail .= $row_mail[0]."<br>";
            }
        }
        // Si le domaine est restreint, on teste si l'utilateur a accès
        if ($row_area[2] == 'r') {
            $test_restreint = grr_sql_query1("select count(id_area) from ".$_COOKIE["table_prefix"]."_j_user_area j where j.login = '".$user_login."' and j.id_area='".$row_area[0]."'");
            if ($test_restreint >= 1) $is_restreint = 'y'; else $is_restreint = 'n';
        } else $is_restreint = 'n';

        if (($is_admin == 'y') or ($is_restreint == 'y') or ($is_gestionnaire != '') or ($is_mail != '')) {
            $a_privileges = 'y';
            echo "<H3>".get_vocab("match_area").get_vocab("deux_points").$row_area[1];
            if ($row_area[2] == 'r') echo " (".$vocab["restricted"].")";
            echo "</H3>";
            echo "<ul>";
            if ($is_admin == 'y') echo "<li><b>".get_vocab("administrateur du domaine")."</b></li>";
            if ($is_restreint == 'y') echo "<li><b>".get_vocab("a acces au domaine")."</b></li>";
            if ($is_gestionnaire != '') {
                echo "<li><b>".get_vocab("gestionnaire des resources suivantes")."</b><br>";
                echo $is_gestionnaire;
                echo "</li>";
            }
            if ($is_mail != '') {
                echo "<li><b>".get_vocab("est prevenu par mail")."</b><br>";
                echo $is_mail;
                echo "</li>";
            }
            echo "</ul>";
        }
    }
  }
  if ($a_privileges == 'n') {
      if ($user_statut == 'administrateur')
          echo get_vocab("administrateur general").".";
      else
          echo get_vocab("pas de privileges").".";
  }
}

echo "</body></html>";
?>