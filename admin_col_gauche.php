<?php
#########################################################################
#                         admin_col_gauche                              #
#                                                                       #
#                       colonne de gauche des �crans                    #
#                          d'administration                             #
#                     des domaines et des ressources                    #
#                  Derni�re modification : 10/07/2006                   #
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

echo "<table border=0 cellspacing=4 cellpadding=4>";
// Affichage de la colonne de gauche

?>
<tr><td width="20%" nowrap>


<?php
if(isset($_SERVER['REQUEST_URI']))
{
  $url = parse_url($_SERVER['REQUEST_URI']);
  $pos = strrpos($url['path'], "/")+1;
  $chaine = substr($url['path'],$pos);
}
else
{
  $chaine = '';
}

function affichetableau($liste,$titre="")
{
  global $chaine, $vocab;
  if (count($liste) > 0)
    {
      echo "<fieldset style=\"padding-top: 5px; padding-bottom: 5px; padding-left:5px ; padding-right: 5px ; line-height:1.2em;\">";
      echo "<legend>$titre</legend>";
      $k = 0;
      foreach($liste as $key)
    {
      if ($k > 0) echo "<br>";
      if ($chaine == $key)
        echo "<li><span class=\"bground\"><b>".get_vocab($key)."</b></span>";
      else
        echo "<li><A HREF='".$key."'>".get_vocab($key)."</A>";
      $k++;
    }
      echo "</fieldset>";
    }
}

$liste = array();
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_config.php';
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_type.php';
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_calend_ignore.php';
affichetableau($liste,get_vocab("admin_menu_general"));

$liste = array();
if(authGetUserLevel(getUserName(),-1,'area') >= 4)
$liste[] = 'admin_room.php';
if(authGetUserLevel(getUserName(),-1,'area') >= 4)
$liste[] = 'admin_overload.php';
affichetableau($liste,get_vocab("admin_menu_arearoom"));

$liste = array();
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_user.php';
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_abt.php';
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_group.php';
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_right_admin.php';
if(authGetUserLevel(getUserName(),-1,'area') >= 4)
$liste[] = 'admin_access_area.php';
if(authGetUserLevel(getUserName(),-1,'area') >= 4)
$liste[] = 'admin_right.php' ;
affichetableau($liste,get_vocab("admin_menu_user"));

$liste = array();
$liste[] = 'admin_email_manager.php';
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_view_connexions.php';
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_calend.php';
affichetableau($liste,get_vocab("admin_menu_various"));

$liste = array();
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_compta.php';
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_compta_abonnes.php';
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_compta_rap.php';
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_compta_tresorerie.php';
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_compta_bilan.php';
affichetableau($liste,get_vocab("admin_menu_compta"));
/*
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_maj.php';



$liste = array();
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_config_ldap.php';
if(authGetUserLevel(getUserName(),-1,'area') >= 5)
$liste[] = 'admin_config_sso.php';
affichetableau($liste,get_vocab("admin_menu_auth"));
*/

// d�but affichage de la colonne de gauche
?>
<td>

