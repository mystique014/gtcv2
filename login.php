<?php
#########################################################################
#                            login.php                                  #
#                                                                       #
#            interface de connexion                                     #
#               Derni�re modification : 17/09/2008                      #
#                                                                       #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 *
 * Modification S Duchemin
 * Ajout du bandeau d'information en page d'accueil
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
 
//test du $_COOKIE["table_prefix"]
if (isset($_POST['table_prefix'])){
$_COOKIE["table_prefix"] =	$_POST['table_prefix'];
setcookie("table_prefix", $_COOKIE["table_prefix"]); 
} else if (isset($_GET['table_prefix'])){
$_COOKIE["table_prefix"] =	$_GET['table_prefix'];
setcookie("table_prefix", $_COOKIE["table_prefix"]); 
} else if (isset ($_COOKIE["table_prefix"])){
}else{
header ('location : site.php');
}
//echo $_COOKIE["table_prefix"]; 
include "include/connect.inc.php";
include "include/config.inc.php";
include "include/misc.inc.php";
include "include/functions.inc.php";
include "include/$dbsys.inc.php";

// Settings
require_once("./include/settings.inc.php");

//Chargement des valeurs de la table settingS
if (!loadSettings()) die("Erreur chargement settings");

// Param�tres langage
include "include/language.inc.php";

// Session related functions
require_once("./include/session.inc.php");

// V�rification du num�ro de version et renvoi automatique vers la page de mise � jour
if (verif_version()) {
    header("Location: ./admin_maj.php");
    exit();
}
// User wants to be authentified
if (isset($_POST['login']) && isset($_POST['password'])) {
    $result = grr_opensession($_POST['login'], unslashes($_POST['password']));
    if ($result=="2") {
        $message = get_vocab("echec_connexion_GRR");
        $message .= " ".get_vocab("wrong_pwd");
    } else if ($result == "3") {
        $message = get_vocab("echec_connexion_GRR");
        $message .= "<br>". get_vocab("importation_impossible");
    } else if ($result == "4") {
        //$message = get_vocab("importation_impossible");
        $message = get_vocab("echec_connexion_GRR");
        $message .= " ".get_vocab("causes_possibles");
        $message .= "<br>- ".get_vocab("wrong_pwd");
        $message .= "<br>- ". get_vocab("echec_authentification_ldap");
    } else {
        header("Location: ./".page_accueil()."");
        die();
    }
}
// on ferme une �ventuelle session ouverte pr�c�demment
grr_closeSession($_GET['auto']);

echo begin_page(getSettingValue("company").get_vocab("deux_points").get_vocab("mrbs"),"no_session");
?>
<div class="center">
<h1><?php echo getSettingValue("title_home_page"); ?></h1>
<h2><?php echo getSettingValue("company"); ?></h2>
<IMG SRC="img_grr/<?php echo $_COOKIE["table_prefix"]; ?>.jpg" ALT="Logo" TITLE="<?php echo $_COOKIE["table_prefix"]; ?>"><br><br>

<?php echo getSettingValue("message_home_page");
if ((getSettingValue("disable_login"))=='yes') echo "<br><br><font color='red'>".get_vocab("msg_login3")."</font>";
?>
</p>
<form action="login.php" method='POST' style="width: 100%; margin-top: 24px; margin-bottom: 48px;">
<?php
if ((isset($message)) and (getSettingValue("disable_login"))!='yes') {
    echo("<p><font color=red>" . $message . "</font></p>");
}
if ((getSettingValue('sso_statut') == 'cas_visiteur') or (getSettingValue('sso_statut') == 'cas_utilisateur')) {
    echo "<p><font size=\"+1\"><a href=\"./index.php\">".get_vocab("authentification_CAS")."</a></font></p>";
}
if (getSettingValue('sso_statut') == 'lcs') {
    echo "<p><font size=\"+1\"><a href=\"".LCS_PAGE_AUTHENTIF."\">".get_vocab("authentification_lcs")."</a></font></p>";
}


?>
<fieldset style="padding-top: 8px; padding-bottom: 8px; width: 40%; margin-left: auto; margin-right: auto;">
<legend class="fontcolor3" style="font-variant: small-caps;"><?php echo get_vocab("identification"); ?></legend>
<table style="width: 100%; border: 0;" cellpadding="5" cellspacing="0">
<tr>
<td style="text-align: right; width: 40%; font-variant: small-caps;"><?php echo get_vocab("login"); ?></td>
<td style="text-align: center; width: 60%;"><input type="text" name="login"></td>
</tr>
<tr>
<td style="text-align: right; width: 40%; font-variant: small-caps;"><?php echo get_vocab("pwd"); ?></td>
<td style="text-align: center; width: 60%;"><input type="password" name="password"></td>
</tr>
</table>
<input type="submit" name="submit" value="<?php echo get_vocab("submit"); ?>" style="font-variant: small-caps;">
</fieldset>
</form>



<fieldset style="padding-top: 8px; padding-bottom: 8px; width: 40%; margin-left: auto; margin-right: auto;">
<legend class="fontcolor3" style="font-variant: small-caps;"><?php echo  "Informations"?></legend>
<table style="width: 100%; border: 0;" cellpadding="5" cellspacing="0">
<tr>
<td style="text-align: left; width: 40% ">
<blink style="color:#900"><?php echo getSettingValue("infos"); ?></blink></td>
</tr>
</table>
</fieldset>
<br>
<?php 
echo "<a href=\"week.php?area=1&amp;room=1\">[".get_vocab("welcome")."]</A><br><br>\n";
echo "<a href=\"help.php\">[".get_vocab("fonctions")."]</A><a href=\"#reg\"></a><br><br>\n";
echo "<a href=\"mailto:".getSettingValue("webmaster_email")."\">[".get_vocab("administrator_contact")."]</A><br>\n";

//echo "<br><P class=\"small\"><a href=\"".$grr_devel_url."\">".get_vocab("mrbs")."</a> - ".get_vocab("grr_version").affiche_version();

//echo "<br>".get_vocab("msg_login1")."<a href=\"".$grr_devel_url."\">".$grr_devel_url."</a>";
//echo "<br><a href=\"mailto:".$grr_devel_email."\"> - ".get_vocab("autor_contact")." - </a></p>";
//?>



</div>
</body>
</html>

