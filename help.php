<?php
#########################################################################
#                            help.php                                   #
#                                                                       #
#                Interface d'aide à l'utilisateur                       #
#                Dernière modification : 10/07/2006                     #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 *
 *
 * Modification S Duchemin
 * Adaptation de l'aide
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
include "include/$dbsys.inc.php";
include "include/functions.inc.php";
// Settings
require_once("./include/settings.inc.php");
//Chargement des valeurs de la table settingS
if (!loadSettings())
    die("Erreur chargement settings");

// Session related functions
require_once("./include/session.inc.php");
// Resume session
if ((!grr_resumeSession())and ($authentification_obli==1)) {
    header("Location: ./logout.php?auto=1");
    die();
};

// Paramètres langage
include "include/language.inc.php";

if (($authentification_obli==0) and (!isset($_SESSION['login']))) {
    $type_session = "no_session";
} else {
    $type_session = "with_session";
}

#If we dont know the right date then make it up
if(!isset($day) or !isset($month) or !isset($year))
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
}
if(empty($area))
    $area = get_default_area();

print_header($day, $month, $year, $area, $type_session);
if (isset($_POST['aide'])) {
    if (!saveSetting("regle".$faqfilelang, $_POST['aide'])) {
        echo "Erreur lors de l'enregistrement de aide !<br>";
        die();
    }
}

//echo "<br><H3>Rappel :</H3><menu>Il est interdit de r&eacute;server deux heures cons&eacute;cutives avec la m&ecirc;me personne !</menu>";
//echo "<H3>" . get_vocab('help') . "</H3>\n";
echo "<menu>".get_vocab('please_contact') . '<a href="mailto:' . getSettingValue("webmaster_email")
    . '">' . getSettingValue("webmaster_name")
    . "</a> " . get_vocab('for_any_questions') ."</menu>". "\n";

//Affichage du message d'aide !!
//***************************************************

	
	echo getSettingValue("regle".$faqfilelang);
   
//	
if(authGetUserLevel(getUserName(),-1,'area') > 4){
?>	

<form action="help.php" name="formulaire" method='POST' style="width: 100%;">
<?php

	//Editeur wysiwyg / HTML
	echo "<a href='https://html-online.com/editor/'>https://html-online.com/editor/</a>";
    //Edition du message d'aide !!
	//***************************************************
	echo "<hr>";
    $value_infos=getSettingValue("regle".$faqfilelang);
	
    echo "<textarea name=\"aide\" cols=\"150\" rows=\"60\">".$value_infos."</textarea>\n<br><br>";
	echo "<center><INPUT type='submit' name='Valider' value=".get_vocab('submit')." />\n</center>\n";
	echo "<hr>";
	echo "</FORM>";
	//
}



//echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
//echo "<H3>" . get_vocab('about_mrbs') . "</H3>\n";
//echo "<P><a href=\"".$grr_devel_url."\">".get_vocab("mrbs")."</a> - ".get_vocab("grr_version").affiche_version()."\n";
//echo "<BR>" . get_vocab('database') . grr_sql_version() . "\n";
//echo "<BR>" . get_vocab('system') . php_uname() . "\n";
//echo "<BR>PHP : " . phpversion() . "\n";
include "include/trailer.inc.php";
?>