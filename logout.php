<?php
#########################################################################
#                            logout.php                                 #
#                                                                       #
#                      script de deconnexion                            #
#                                                                       #
#            Derni�re modification : 10/07/2006                         #
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

require_once("include/connect.inc.php");
require_once("include/config.inc.php");
include "include/functions.inc.php";
require_once("include/$dbsys.inc.php");
// Settings
require_once("./include/settings.inc.php");
//Chargement des valeurs de la table settingS
if (!loadSettings())
    die("Erreur chargement settings");

// Param�tres langage
include "include/language.inc.php";

require_once("./include/session.inc.php");

grr_closeSession($_GET['auto']);

//redirection vers l'url de d�connexion
$url = getSettingValue("url_disconnect");
if ($url != '') {
  header("Location: $url");
  exit;
}


if (isset($_GET['authentif_obli']) and ($_GET['authentif_obli'] == 'no')) {
   header("Location: ./".page_accueil()."");
   exit;
}
echo begin_page(get_vocab("mrbs"),"no_session");
?>
<div class="center">
<h1><?php echo get_vocab("disconnect"); ?></h1>
<p>
<?php
 if (!$_GET['auto']) {
     echo (get_vocab("msg_logout1")."<br/>");
 } else {
     echo (get_vocab("msg_logout2")."<br/>");
 }
?>
<a href="login.php"><?php echo (get_vocab("msg_logout3")."<br/>"); ?></a>
</p>
</div>
</body>
</html>