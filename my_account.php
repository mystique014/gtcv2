<?php
#########################################################################
#                            my_account.php                             #
#                                                                       #
#        Interface permetant à l'utilisateur de gérer son compte        #
#                Dernière modification : 17/09/2008                    #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 *
 * Modification S Duchemin
 * Ajout des paramètres personnels des utilisateurs pour qu'ils aient la possibilité de les mettre à jour/supprimer 
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
include "include/misc.inc.php";
include "include/functions.inc.php";
include "include/$dbsys.inc.php";
    #Settings
require_once("./include/settings.inc.php");
    #Chargement des valeurs de la table settings
if (!loadSettings())
    die("Erreur chargement settings");

    #Fonction relative à la session
require_once("./include/session.inc.php");
    #Si il n'y a pas de session crée, on déconnecte l'utilisateur.
if (!grr_resumeSession())
{
    header("Location: ./logout.php?auto=1");
    die();
};



   #Si nous ne savons pas la date, nous devons la créer
$day = isset($_POST["day"]) ? $_POST["day"] : (isset($_GET["day"]) ? $_GET["day"] : date("d"));
$month = isset($_POST["month"]) ? $_POST["month"] : (isset($_GET["month"]) ? $_GET["month"] : date("m"));
$year = isset($_POST["year"]) ? $_POST["year"] : (isset($_GET["year"]) ? $_GET["year"] : date("y"));

if (($authentification_obli==0) and (!isset($_SESSION['login'])))
{
    $session_login = '';
    $session_statut = '';
}
else
{
    $session_login = $_SESSION['login'];
    $session_statut = $_SESSION['statut'];
}

// Paramètres langage
include "include/language.inc.php";

$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
if (authGetUserLevel(getUserName(),-1) <= 1)
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}


$user_login = isset($_POST["user_login"]) ? $_POST["user_login"] : ($user_login = isset($_GET["user_login"]) ? $_GET["user_login"] : NULL);
$valid = isset($_POST["valid"]) ? $_POST["valid"] : NULL;
$msg='';
if ($valid == "yes")
{
    $reg_password_a = isset($_POST["reg_password_a"]) ? $_POST["reg_password_a"] : NULL;
    $reg_password1 = isset($_POST["reg_password1"]) ? $_POST["reg_password1"] : NULL;
    $reg_password2 = isset($_POST["reg_password2"]) ? $_POST["reg_password2"] : NULL;
    $reg_email = isset($_POST["reg_email"]) ? $_POST["reg_email"] : NULL;
	$reg_tel = isset($_POST["reg_tel"]) ? $_POST["reg_tel"] : NULL;
	$reg_telport = isset($_POST["reg_telport"]) ? $_POST["reg_telport"] : NULL;
	$reg_adresse = isset($_POST["reg_adresse"]) ? $_POST["reg_adresse"] : NULL;
	$reg_code = isset($_POST["reg_code"]) ? $_POST["reg_code"] : NULL;
	$reg_ville = isset($_POST["reg_ville"]) ? $_POST["reg_ville"] : NULL;
	$reg_licence = isset($_POST["reg_licence"]) ? $_POST["reg_licence"] : NULL;
	$reg_classement = isset($_POST["reg_classement"]) ? $_POST["reg_classement"] : NULL;
	if ($reg_password_a != '')
    {
        $reg_password_a_c = md5($reg_password_a);
        if ($_SESSION['password'] == $reg_password_a_c)
        {
            if ($reg_password1 != $reg_password2)
                $msg = get_vocab("wrong_pwd2");
            else
            {
                $reg_password1 = md5($reg_password1);
                $sql = "UPDATE grr_utilisateurs SET password='" . protect_data_sql($reg_password1)."' WHERE login='". $_SESSION['login']."'";
                if (grr_sql_command($sql) < 0)
                    fatal_error(0, get_vocab("update_pwd_failed") . grr_sql_error());
                else
                {
                    $msg = get_vocab("update_pwd_succeed");
                    $_SESSION['password'] = $reg_password1;
                }
            }
        }
        else
            $msg = get_vocab("wrong_old_pwd");
    }
    $sql = "SELECT email, source, tel, telport, adresse, code, ville, licence, classement FROM grr_utilisateurs WHERE login='".$_SESSION['login']."'";
    $res = grr_sql_query($sql);
    if ($res)
    {
        for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
            $user_email = $row[0];
            $user_source = $row[1];
			$user_tel = $row[2];
			$user_telport = $row[3];
			$user_adresse = $row[4];
			$user_code = $row[5];
			$user_ville = $row[6];
			$user_licence = $row[7];
			$user_classement = $row[8];
			
    }
    if ($user_email != $reg_email) 
    {
        $sql = "UPDATE grr_utilisateurs SET email = '" . protect_data_sql($reg_email)."' WHERE login='". $_SESSION['login']."'";
        if (grr_sql_command($sql) < 0)
            fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
        else
            $msg = $msg." ".get_vocab("update_email_succeed");
    }
	if ($user_tel != $reg_tel) 
    {
        $sql = "UPDATE grr_utilisateurs SET tel = '" . protect_data_sql($reg_tel)."' WHERE login='". $_SESSION['login']."'";
        if (grr_sql_command($sql) < 0)
            fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
        else
           ;
    }
	if ($user_telport != $reg_telport) 
    {
        $sql = "UPDATE grr_utilisateurs SET telport = '" . protect_data_sql($reg_telport)."' WHERE login='". $_SESSION['login']."'";
        if (grr_sql_command($sql) < 0)
            fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
        else
           ;
    }
	if ($user_adresse != $reg_adresse) 
    {
        $sql = "UPDATE grr_utilisateurs SET adresse = '" . protect_data_sql($reg_adresse)."' WHERE login='". $_SESSION['login']."'";
        if (grr_sql_command($sql) < 0)
            fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
        else
         ;
    }
	if ($user_code != $reg_code) 
    {
        $sql = "UPDATE grr_utilisateurs SET code = '" . protect_data_sql($reg_code)."' WHERE login='". $_SESSION['login']."'";
        if (grr_sql_command($sql) < 0)
            fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
        else
     ;
    }
	if ($user_ville != $reg_ville) 
    {
        $sql = "UPDATE grr_utilisateurs SET ville = '" . protect_data_sql($reg_ville)."' WHERE login='". $_SESSION['login']."'";
        if (grr_sql_command($sql) < 0)
            fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
        else
            ;
    }
	if ($user_licence != $reg_licence) 
    {
        $sql = "UPDATE grr_utilisateurs SET licence = '" . protect_data_sql($reg_licence)."' WHERE login='". $_SESSION['login']."'";
        if (grr_sql_command($sql) < 0)
            fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
        else
            ;
    }
	if ($user_classement != $reg_classement) 
    {
        $sql = "UPDATE grr_utilisateurs SET classement = '" . protect_data_sql($reg_classement)."' WHERE login='". $_SESSION['login']."'";
        if (grr_sql_command($sql) < 0)
            fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
        else
            ;
    }
}
if (($valid == "yes") or ($valid=="reset"))
{
    $default_area = isset($_POST["id_area"]) ? $_POST["id_area"] : NULL;
    $default_room = isset($_POST["id_room"]) ? $_POST["id_room"] : NULL;
    $default_style = isset($_POST["default_css"]) ? $_POST["default_css"] : NULL;
    $default_list_type = isset($_POST["area_list_format"]) ? $_POST["area_list_format"] : NULL;
    $default_language = isset($_POST["default_language"]) ? $_POST["default_language"] : NULL;
    $sql = "UPDATE grr_utilisateurs SET
        default_area = '" . protect_data_sql($default_area)."',
        default_room = '" . protect_data_sql($default_room)."',
        default_style = '" . protect_data_sql($default_style)."',
        default_list_type = '" . protect_data_sql($default_list_type)."',
        default_language = '" . protect_data_sql($default_language)."'
        WHERE login='". $_SESSION['login']."'";
    if (grr_sql_command($sql) < 0)
        fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
    else
    {
        if (($default_area !='') and ($default_area !='0')) $_SESSION['default_area'] = $default_area; else $_SESSION['default_area'] = getSettingValue("default_area");
        if (($default_room !='') and ($default_room !='0')) $_SESSION['default_room'] = $default_room; else $_SESSION['default_room'] = getSettingValue("default_room");
        if ($default_style !='') $_SESSION['default_style'] = $default_style; else $_SESSION['default_style'] = getSettingValue("default_css");
        if ($default_list_type !='') $_SESSION['default_list_type'] = $default_list_type; else $_SESSION['default_list_type'] = getSettingValue("area_list_format");
        if ($default_language !='') $_SESSION['default_language'] = $default_language; else $_SESSION['default_language'] = getSettingValue("default_language");
        $msg = $msg."\\n".get_vocab("message_records");


    }
}
print_header($day, $month, $year, isset($area) ? $area : "");
?>
<script type="text/javascript" src="functions.js" language="javascript"></script>
<?php
    #On appelle les informations de l'utilisateur pour les afficher
$sql = "SELECT nom, prenom, statut, email, default_area, default_room, default_style, default_list_type, default_language, source, invite, tel, telport, adresse, code, ville, licence, classement, group_id  FROM grr_utilisateurs WHERE login='".$_SESSION['login']."'";
$res = grr_sql_query($sql);
if ($res)
{
    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
    $user_nom = $row[0];
    $user_prenom = $row[1];
    $user_statut = $row[2];
    $user_email = $row[3];
    if (($row[4] !='') and ($row[4] !='0')) $default_area = $row[4]; else $default_area = getSettingValue("default_area");
    if (($row[5] !='') and ($row[5] !='0')) $default_room = $row[5]; else $default_room = getSettingValue("default_room");
    if ($row[6] !='') $default_css = $row[6]; else $default_css = getSettingValue("default_css");
    if ($row[7] !='') $default_list_type = $row[7]; else $default_list_type = getSettingValue("area_list_format");
    if ($row[8] !='') $default_language = $row[8]; else $default_language = getSettingValue("default_language");
    $user_source = $row[9];
	$user_invite = $row[10];
	$user_tel = $row[11];
	$user_telport = $row[12];
	$user_adresse = $row[13];
	$user_code = $row[14];
	$user_ville = $row[15];
	$user_licence = $row[16];
	$user_classement = $row[17];
	$user_group = $row[18];
    }
}
echo "<noscript>";
echo "<font color='red'>$msg</font>";
echo "</noscript>";
if ($msg)
{
    echo "<script type=\"text/javascript\" language=\"javascript\">";
    echo "<!--\n";
    echo " alert(\"".$msg."\")";
    echo "//-->";
    echo "</script>";
    unset($msg);
}
// Recherche du du nom de groupe de l'utilisateur pour afficher
				$sql2 = "select group_name from grr_group WHERE id=$user_group";
				$gr = grr_sql_query1($sql2);
echo "<form name=\"nom_formulaire\" action=\"my_account.php\" method='POST'>";
echo "<br><TABLE BORDER=0 width=\"100%\"><tr><td>".get_vocab("login").get_vocab("deux_points"). $_SESSION['login']."</td><td><a title=\"".htmlspecialchars(get_vocab("see_all_the_rooms_for_the_day"))."\" href=\"day.php\">>>>RETOUR Planning<<<</a></td></tr>";
echo "<tr><td>".get_vocab("last_name").get_vocab("deux_points").$user_nom."</td><td>".get_vocab("first_name").get_vocab("deux_points").$user_prenom."</td></tr>";
echo "<tr><td>".get_vocab("mail_user").get_vocab("deux_points")."";
if (($user_source == 'ext') and (getSettingValue("sso_statut") == "lcs")) {
    echo $user_email;
    echo "<input type=\"hidden\" name=\"reg_mail\" value=\"".htmlspecialchars($user_email)."\"></td>";
} else {
	echo "<input type=text name=reg_email size=30";
    if ($user_email) { echo " value=\"".htmlspecialchars($user_email)."\"></td>";} else {echo">"; }
    echo "<td>".get_vocab("tel").get_vocab("deux_points");
    echo "<input type=text name=reg_tel size=15";
    if ($user_tel) { echo " value=\"".htmlspecialchars($user_tel)."\"></td></tr>";} else {echo">"; }
    echo "<tr><td>".get_vocab("telport").get_vocab("deux_points");
	echo "<input type=text name=reg_telport size=15";
    if ($user_telport) { echo " value=\"".htmlspecialchars($user_telport)."\"></td>";} else {echo">"; }
    echo "<td>".get_vocab("adresse").get_vocab("deux_points");
    echo "<input type=text name=reg_adresse size=35";
    if ($user_adresse) { echo " value=\"".htmlspecialchars($user_adresse)."\"></td></tr>";} else {echo">"; }
    echo "<tr><td>".get_vocab("code").get_vocab("deux_points");
	echo "<input type=text name=reg_code size=6";
    if ($user_code) { echo " value=\"".htmlspecialchars($user_code)."\"></td>";} else {echo">"; }
    echo "<td>".get_vocab("ville").get_vocab("deux_points");
	echo "<input type=text name=reg_ville size=25";
    if ($user_ville) { echo " value=\"".htmlspecialchars($user_ville)."\"></td></tr>";} else {echo">"; }
	echo "<tr><td>".get_vocab("licence").get_vocab("deux_points");
	echo "<input type=text name=reg_licence size=15";
    if ($user_licence) { echo " value=\"".htmlspecialchars($user_licence)."\"></td>";} else {echo">"; }
	echo "<td>".get_vocab("classement").get_vocab("deux_points");
	echo "<input type=text name=reg_classement size=5";
    if ($user_classement) { echo " value=\"".htmlspecialchars($user_classement)."\"></td></tr>";} else {echo">"; }
	echo "<tr><td>".get_vocab("statut").get_vocab("deux_points")."<font color=\"red\">".$user_statut."</font></td>";
	echo "<td>".get_vocab("admin_group.php").get_vocab("deux_points")."<font color=\"red\">".$gr."</font></td></tr>";
	echo "</table>";
}
 echo "<br>".get_vocab("invite").get_vocab("deux_points")."<font color=\"red\">".$user_invite."</font></b>";
if ($user_source != 'ext') {
?>
<br><br>
<TABLE BORDER=0 width="100%">
<TR>
<TD onClick="clicMenu('1')" class="fontcolor4"   style="cursor: hand" align=center ><span class="bground"><B><a href='#'><?php echo get_vocab("click_here_to_modify_pwd") ?></a></B></span>
</TD>
</TR>
<TR style="display:none" id="menu1">
<TD><br>
<?php
echo "<p>".get_vocab("pwd_msg_warning")."</p>";
echo get_vocab("old_pwd").get_vocab("deux_points")."<input type=password name=reg_password_a size=20>";
echo "<br>".get_vocab("new_pwd1").get_vocab("deux_points")."<input type=password name=reg_password1 size=20>";
echo "<br>".get_vocab("new_pwd1").get_vocab("deux_points")."<input type=password name=reg_password2 size=20>";
?>
</TD></TR>
</TABLE>
<br><hr>
<?php
}
    #Configuration de l'affichage par defaut
echo "<h3>".get_vocab("default_parameter_values_title")."</h3>";
    #Choix du type d'affichage
echo "<h4>".get_vocab("explain_area_list_format")."</h4>";
echo "<table><tr><td>".get_vocab("liste_area_list_format")."</td><td>";
echo "<input type='radio' name='area_list_format' value='list' "; if ($default_list_type =='list') echo "checked"; echo ">";
echo "</td></tr>";
echo "<tr><td>".get_vocab("select_area_list_format")."</td><td>";
echo "<input type='radio' name='area_list_format' value='select' "; if ($default_list_type =='select') echo "checked"; echo ">";
echo "</td></tr></table>";
    #Choix du domaine et de la ressource
    #http://www.phpinfo.net/articles/article_listes.html
?>
<SCRIPT type="text/javascript" LANGUAGE="JavaScript">
function ModifierListe(code_item)
{
   lg = document.nom_formulaire.id_room.length;
   // On vide la liste
   for (i = lg - 1; i >= 0; i--)
   {
     document.nom_formulaire.id_room.options[i] = null;
   }
   code_rub = document.nom_formulaire.id_area.selectedIndex;
   <?php
   // Génération des Items par Rubriques
   // Cas où aucun domaine n'a été précisé
   echo "  if (document.nom_formulaire.id_area.options[code_rub].value == -1) {\n";
   echo "    document.nom_formulaire.id_room.length = 1;\n";
   echo "    document.nom_formulaire.id_room.options[0].value = -1;\n";
   echo "    document.nom_formulaire.id_room.options[0].text  = \"------\";\n";
   echo "    if (code_item == -1) document.nom_formulaire.id_room.options[0].selected = true;\n";
   echo "  }\n";
   // Cas où un domaine a été précisé
   $sql = "SELECT id FROM grr_area ORDER BY  order_display, area_name";
   $resultat = grr_sql_query($sql);
   $max_lignes = 0;
   $option_max = '';
    for ($enr = 0; ($row = grr_sql_row($resultat, $enr)); $enr++)
    {
     $sql  = "SELECT id, room_name ";
     $sql .= "FROM grr_room ";
     $sql .= "WHERE area_id='".$row[0]."'";
     $sql .= "ORDER BY order_display,room_name";
     $resultat2 = grr_sql_query($sql);
     echo "  if (document.nom_formulaire.id_area.options[code_rub].value == ".$row[0].") {\n";
     echo "    document.nom_formulaire.id_room.length = ".(grr_sql_count($resultat2)+4).";\n";
     $cpt = 0;
     echo "    document.nom_formulaire.id_room.options[0].value = -1;\n";
     echo "    document.nom_formulaire.id_room.options[0].text  = \"".get_vocab("default_room_all")."\";\n";
     echo "    if (code_item == -1) document.nom_formulaire.id_room.options[0].selected = true;\n";
     echo "    document.nom_formulaire.id_room.options[1].value = -2;\n";
     echo "    document.nom_formulaire.id_room.options[1].text  = \"".get_vocab("default_room_week_all")."\";\n";
     echo "    if (code_item == -2) document.nom_formulaire.id_room.options[1].selected = true;\n";
     echo "    document.nom_formulaire.id_room.options[2].value = -3;\n";
     echo "    document.nom_formulaire.id_room.options[2].text  = \"".get_vocab("default_room_month_all")."\";\n";
     echo "    if (code_item == -3) document.nom_formulaire.id_room.options[2].selected = true;\n";
     echo "    document.nom_formulaire.id_room.options[3].value = -4;\n";
     echo "    document.nom_formulaire.id_room.options[3].text  = \"".get_vocab("default_room_month_all_bis")."\";\n";
     echo "    if (code_item == -4) document.nom_formulaire.id_room.options[3].selected = true;\n";
     $cpt++;
     $cpt++;
     $cpt++;
     $cpt++;
        for ($enr2 = 0; ($row2 = grr_sql_row($resultat2, $enr2)); $enr2++)
        {
       echo "    document.nom_formulaire.id_room.options[".$cpt."].value = ".$row2[0].";\n";
       echo "    document.nom_formulaire.id_room.options[".$cpt."].text  = \"".$row2[1]." ".get_vocab("display_week")."\";\n";
       echo "    if (code_item == ".$row2[0].") document.nom_formulaire.id_room.options[".$cpt."].selected = true;\n";
       $cpt++;
       if ($cpt > $max_lignes) $max_lignes = $cpt;
       if (strlen($row2[1]) > strlen($option_max)) $option_max = $row2[1];
     }
     echo "  }\n";
   }
   ?>
}
</SCRIPT>
<?php
    #Liste des domaines
$sql = "SELECT id, area_name, access FROM grr_area ORDER BY  order_display, area_name";
$resultat = grr_sql_query($sql);
echo "<h4>".get_vocab("explain_default_area_and_room")."</h4>";
echo "<table><tr><td>".get_vocab("default_area")."</td><td>";
echo "<SELECT NAME='id_area' onChange='ModifierListe(-1)'>\n";
echo "<OPTION VALUE='-1'>".get_vocab("choose_an_area")."</OPTION>\n";
for ($enr = 0; ($row = grr_sql_row($resultat, $enr)); $enr++)
{
  if(authUserAccesArea($session_login, $row[0])!=0) {
     echo "<OPTION VALUE='".$row[0]."'";
     if ($default_area == $row[0]) echo " SELECTED";
     echo ">".htmlspecialchars($row[1]);
     if ($row[2]=='r') echo " (".get_vocab("restricted").")";
     echo "</OPTION>\n";
  }
}
echo "</SELECT></td></tr>\n";
    #Liste des ressources
echo "<tr><td>".get_vocab("default_room")."</td><td>";
echo "<SELECT NAME='id_room'>\n";
for ($cpt = 0; $cpt < $max_lignes; $cpt++)
  echo "<OPTION>".str_replace(".", "--", $option_max)."</OPTION>\n";
echo "</SELECT></td></tr></table>\n";
if ($default_room)
    $id_room=$default_room;
else
    $id_room = -1;
echo "<SCRIPT type='text/javascript' LANGUAGE='JavaScript'>\n;ModifierListe(".$id_room.");\n</SCRIPT>\n";
// ----------------------------------------------------------------------------

//
// Choix de la feuille de style
//
echo "<h4>".get_vocab("explain_css")."</h4>";
echo "<table><tr><td>".get_vocab("choose_css")."</td><td>";
echo "<SELECT NAME='default_css'>\n";
$i=0;
while ($i < count($liste_themes)) {
   echo "<OPTION VALUE='".$liste_themes[$i]."'";
   if ($default_css == $liste_themes[$i]) echo " SELECTED";
   echo " >".encode_message_utf8($liste_name_themes[$i])."</OPTION>";
   $i++;
}
echo "</SELECT></td></tr></table>\n";

//
// Choix de la langue
//
echo "<h4>".get_vocab("choose_language")."</h4>";
echo "<table><tr><td>".get_vocab("choose_css")."</td><td>";
echo "<SELECT NAME='default_language'>\n";
$i=0;
while ($i < count($liste_language)) {
   echo "<OPTION VALUE='".$liste_language[$i]."'";
   if ($default_language == $liste_language[$i]) echo " SELECTED";
   echo " >".encode_message_utf8($liste_name_language[$i])."</OPTION>";
   $i++;
}
echo "</SELECT></td></tr></table>\n";

?>

<input type=hidden name=valid value="yes">
<input type=hidden name=day value="<?php echo $day;?>">
<input type=hidden name=month value="<?php echo $month;?>">
<input type=hidden name=year value="<?php echo $year;?>">
<br><input type=submit value=<?php echo get_vocab("submit") ; ?>>
</form>
<form name="reset" action="my_account.php" method='POST'>
<input type=hidden name=valid value="reset">
<input type=hidden name=day value="<?php echo $day;?>">
<input type=hidden name=month value="<?php echo $month;?>">
<input type=hidden name=year value="<?php echo $year;?>">
<input type=hidden name=id_area value="">
<input type=hidden name=id_room value="">
<input type=hidden name=default_css value="">
<input type=hidden name=area_list_format value="">
<input type=hidden name=default_language value="">
<input type=submit value="<?php echo get_vocab("reset") ; ?>">
</form></body></html>