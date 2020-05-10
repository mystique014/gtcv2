<?php
#########################################################################
#                        astuce1_fr.php                                 #
#                                                                       #
#                fichier d'aide en fran�ais                             #
#                                                                       #
#            Derni�re modification : 26/02/2005                         #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
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
include "include/functions.inc.php";
include "include/$dbsys.inc.php";
// Settings
require_once("./include/settings.inc.php");
//Chargement des valeurs de la table settingS
if (!loadSettings())
    die("Erreur chargement settings");

// Session related functions
require_once("./include/session.inc.php");
// Resume session
if ((!resumeSession())and ($authentification_obli==1)) {
    header("Location: ./logout.php?auto=1");
    die();
};

// Param�tres langage
include "include/language.inc.php";

echo begin_page(get_vocab("mrbs"));
?>
<H2>Astuce : Comment rendre visible une ressource pour tous les utilisateurs, mais ��r�servable�� par un nombre restreint de personnes ?</H1>
<ul>
<li>Cr�er la ressource, et affecter la valeur 0 au champ ��Nombre max de r�servations par utilisateur��. De cette fa�on, plus personne ne peut r�server.</li>
<li>Ensuite, dans la page ��Gestion des droits d'administration des utilisateurs��, affecter aux utilisateurs de son choix, le droit d'administrer la ressource. Comme les administrateurs de ressources ne sont pas touch�s par la limite de r�servation, il pourront r�server cette ressource. Cependant, en tant qu'administrateur de cette ressource, chacun aura �videmment le droit d'�diter, supprimer ou cr�er n'importe quelle r�servation.</li>
</ul>
</body>
</html>
