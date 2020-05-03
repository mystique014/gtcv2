<?php
#########################################################################
#                         verif_auto_grr.php                            #
#                                                                       #
#    V�rification des r�servations pour lesquelles                      #
#      le d�lai de confirmation est d�pass�                             #
#  Si oui, les r�servations concern�es sont supprim�es                  #
#           et un mail automatique est envoy�.                          #
#                                                                       #
#                  Derni�re modification : 13/04/2006                   #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 * D'apr�s http://mrbs.sourceforge.net/
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


// L'ex�cution de ce script requiert un mot de passe :
// Exemple : si le mot de passe est jamesbond007, vous devrez indiquer une URL du type :
// http://mon-site.fr/grr/verif_auto_grr.php?mdp=jamesbond007
// Le mot de passe  est d�fini dans le fichier config.inc.php

// D�but du script
include "include/connect.inc.php";
include "include/config.inc.php";
include "include/functions.inc.php";
include "include/$dbsys.inc.php";
require_once("./include/settings.inc.php");
if (!loadSettings())
    die("Erreur chargement settings");


if ((!isset($_GET['mdp'])) or ($_GET['mdp'] != $motdepasse_verif_auto_grr) or ($motdepasse_verif_auto_grr=='')) {
    showHeaderPage();
    echo "Le mot de passe fourni est invalide.";
    showFooterPage();
    die();
}

showHeaderPage();
// On v�rifie une fois par jour si le d�lai de confirmation des r�servations est d�pass�
// Si oui, les r�servations concern�es sont supprim�es et un mail automatique est envoy�.
verify_confirm_reservation();
echo "Le script a �t� ex�cut�.";
showFooterPage();

function showHeaderPage()
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>GRR - V�rification des r�servation � supprimer</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15"/>
<meta http-equiv="expires" content="0">
</head>
<body>
<?
}

function showFooterPage()
{
?>
</body>
</html>
<?
}


?>
