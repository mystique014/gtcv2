<?php
#########################################################################
#                            index.php                                  #
#              Dernière modification : 13/07/2006                       #
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
/*$_COOKIE["table_prefix"] =$_GET['table_prefix'];
//test du $_COOKIE["table_prefix"]
if (isset($_COOKIE["table_prefix"])){

} else {
header ('location : site.php');
}*/
//test du $_COOKIE["table_prefix"]
if (isset($_POST['table_prefix'])){
$_COOKIE["table_prefix"] =	$_POST['table_prefix'];
setcookie("table_prefix", $_COOKIE["table_prefix"]); 
} else if (isset ($_COOKIE["table_prefix"])){
setcookie("table_prefix", $_COOKIE["table_prefix"]);
}else{
header ('location : site.php');
}
require_once("include/config.inc.php");
if (file_exists("include/connect.inc.php"))
   include "include/connect.inc.php";
require_once("include/misc.inc.php");
require_once("include/functions.inc.php");
require_once("include/settings.inc.php");
// Paramètres langage
include "include/language.inc.php";
// Dans le cas d'une base mysql, on teste la bonne installation de la base et on propose une installation automatisée.

if ($dbsys == "mysql")
{
  $flag='';
  $correct_install = '';
  $msg='';
  if (@file_exists("include/connect.inc.php"))
    {
      require_once("include/connect.inc.php");
	  $con = mysqli_connect("$dbHost", "$dbUser", "$dbPass");
		if ($con)
		{
      
			if (mysqli_select_db($con,"$dbDb"))
			{
				 
			// Premier test
				$j = '0';
				while ($j < count($liste_tables))
				{
					$test = mysqli_query($con, "SELECT count(*) FROM ".$_COOKIE["table_prefix"].$liste_tables[$j]);
					if (!$test)
						$flag = 'yes';
					$j++;
				}
				if ($flag == 'no')
				{
					$msg = "<p>La connection au serveur $dbsys est établie mais certaines tables sont absentes de la base $dbDb.</p>";
					$correct_install = 'no';
				}
        
            }
			else
			{
				$msg = "La connection au serveur $dbsys est &eacute;tablie mais impossible de s&eacute;lectionner la base contenant les tables GRR.";
			$correct_install = 'no';
            }
        }
		else
		{
			$msg = "Erreur de connection au serveur $dbsys. Le fichier \"connect.inc.php\" ne contient peut-&ecirc;tre pas les bonnes informations de connection.";
		$correct_install = 'no';
        }
    }
	else
    {
		$msg = "Le fichier \"connect.inc.php\" contenant les informations de connection est introuvable.";
      $correct_install = 'no';
    }
  if ($correct_install=='no')
    {
      echo begin_page("GTC (Gestion Tennis Club) ");
      echo "<h1 class=\"center\">Gestion Tennis Club</h1>\n";
      echo "<div style=\"text-align:center;\"><span style=\"color:red;font-weight:bold\">".$msg."</span>\n";
      echo "<ul><li>Soit vous proc&eacute;dez &agrave; une mise &agrave; jour vers une nouvelle version de GTC. Dans ce cas, vous devez proc&eacute;der &agrave; une mise &agrave; jour de la base de donn&eacute;es mysql.<br />";
      echo "<b><a href='./admin_maj.php'>Mettre &agrave; jour la base mysql</a></b><br /></li>";
      echo "<li>Soit l'installation de GTC n'est peut-&ecirc;tre pas termin&eacute;e. Vous pouvez proc&eacute;der &agrave; une installation/r&eacute;installation de la base.<br />";
        echo "<a href='install_mysql.php'>Installer la base $dbsys</a></li></ul></div>";
        ?>
        </body>
    </html>
        <?php
        die();
    }
}
include "include/connect.inc.php";
require_once("include/$dbsys.inc.php");
require_once("./include/session.inc.php");
// Settings
require_once("./include/settings.inc.php");
//Chargement des valeurs de la table settingS

if (!loadSettings())
{
  die("Erreur chargement settings");
}

$cook = session_get_cookie_params();

// Cas d'une authentification CAS
if ((getSettingValue('sso_statut') == 'cas_visiteur') or (getSettingValue('sso_statut') == 'cas_utilisateur'))
{
  require_once("./include/cas.inc.php");
  // A ce stade, l'utilisateur est authentifié par CAS
  $password = '';
  $user_ext_authentifie = 'cas';
  $result = grr_opensession($login,$password,$user_ext_authentifie) ;
  $message = '';
  if ($result=="2")
    {
      $message = get_vocab("echec_connexion_GRR");
      $message .= " ".get_vocab("wrong_pwd");
    }
  else if ($result == "3")
    {
      $message = get_vocab("echec_connexion_GRR");
      $message .= "<br>". get_vocab("importation_impossible");
    }
  else if ($result == "4")
    {
      //$message = get_vocab("importation_impossible");
      $message = get_vocab("echec_connexion_GRR");
      $message .= " ".get_vocab("causes_possibles");
      $message .= "<br>- ".get_vocab("wrong_pwd");
      $message .= "<br>- ". get_vocab("echec_authentification_ldap");
    }
  if ($message != '')
    {
      echo $result." ".$message;
      die();
    }


  if (grr_resumeSession() )
  {
   header("Location: ".htmlspecialchars_decode(page_accueil())."");
  }

// Cas d'une authentification Lemonldap
}
else if ((getSettingValue('sso_statut') == 'lemon_visiteur') or (getSettingValue('sso_statut') == 'lemon_utilisateur'))
{
  if (isset($_GET['login'])) $login = $_GET['login']; else $login = "";
  if (isset($_COOKIE['user'])) $cookie_user=$_COOKIE['user']; else $cookie_user="";
  if(empty($cookie_user) or $cookie_user != $login)
    {
      header("Location: ./login.php");
      // Echec de l'authentification lemonldap
      die();
      echo "</body></html>";
    }
  // A ce stade, l'utilisateur est authentifié par Lemonldap
  $user_ext_authentifie = 'lemon';
  $password = '';
  $result = grr_opensession($login,$password,$user_ext_authentifie) ;
  $message = '';
  if ($result=="2")
    {
      $message = get_vocab("echec_connexion_GRR");
      $message .= " ".get_vocab("wrong_pwd");
    }
  else if ($result == "3")
    {
      $message = get_vocab("echec_connexion_GRR");
      $message .= "<br>". get_vocab("importation_impossible");
    }
  else if ($result == "4")
    {
      //$message = get_vocab("importation_impossible");
      $message = get_vocab("echec_connexion_GRR");
      $message .= " ".get_vocab("causes_possibles");
      $message .= "<br>- ".get_vocab("wrong_pwd");
      $message .= "<br>- ". get_vocab("echec_authentification_ldap");
    }
  if ($message != '')
    {
      echo $result." ".$message;
      die();
    }


  if (grr_resumeSession() )
  {
   header("Location: ".htmlspecialchars_decode(page_accueil())."");
  }
// Cas d'une authentification LCS
}
else if (getSettingValue('sso_statut') == 'lcs')
{
  include LCS_PAGE_AUTH_INC_PHP;
  include LCS_PAGE_LDAP_INC_PHP;
  list ($idpers,$login) = isauth();
  if ($idpers) {
      list($user, $groups)=people_get_variables($login, false);
      $lcs_tab_login["nom"] = $user["nom"];
      $lcs_tab_login["email"] = $user["email"];
      $long = strlen($user["fullname"]) - strlen($user["nom"]);
      $lcs_tab_login["fullname"] = substr($user["fullname"], 0, $long) ;

      // A ce stade, l'utilisateur est authentifié par CAS
      // Etablir à nouveau la connexion à la base
      if (empty($db_nopersist))
          $con = mysqli_pconnect($dbHost, $dbUser, $dbPass);
      else
          $con = mysqli_connect($dbHost, $dbUser, $dbPass);
      if (!$con || !mysqli_select_db ($dbDb)) {
          echo "\n<p>\n" . get_vocab('failed_connect_db') . "\n";
          exit;
      }

      if (is_eleve($login))
         $user_ext_authentifie = 'lcs_eleve';
      else
         $user_ext_authentifie = 'lcs_non_eleve';
      $password = '';
      $result = grr_opensession($login,$password,$user_ext_authentifie,$lcs_tab_login) ;
      $message = '';
      if ($result=="2") {
          $message = get_vocab("echec_connexion_GRR");
          $message .= " ".get_vocab("wrong_pwd");
      } else if ($result == "3") {
           $message = get_vocab("echec_connexion_GRR");
           $message .= "<br>". get_vocab("importation_impossible");
      } else if ($result == "4") {
          $message = get_vocab("echec_connexion_GRR");
          $message .= " ".get_vocab("causes_possibles");
          $message .= "<br>- ".get_vocab("wrong_pwd");
         $message .= "<br>- ". get_vocab("echec_authentification_ldap");
      }
      if ($message != '') {
          fatal_error(1, $message);
          die();
      }
      if (grr_resumeSession() ) {
           header("Location: ".htmlspecialchars_decode(page_accueil())."");
      }
  } else {
    // L'utilisateur n'a pas été identifié'
      if ($authentification_obli==1) { // authentification obligatoire, l'utilisateur est renvoyé vers une page de connexion
         require_once("include/session.inc.php");
         grr_closeSession($_GET['auto']);
         header("Location:".LCS_PAGE_AUTHENTIF);
      } else {
         header("Location: ".htmlspecialchars_decode(page_accueil()).""); // authentification non obligatoire, l'utilisateur est simple visiteur
      }
   }

}
else
{
  if ($authentification_obli==1)
    {
      if ($cook["path"] != '')
    {
      if (grr_resumeSession())
        {

          header("Location: ".htmlspecialchars_decode(page_accueil())."");
        }
      else
        {
          header("Location: ./login.php");
        }
    }
      else
    {
      header("Location: ./login.php");
    }
    }
  else
    {
      header("Location: ".htmlspecialchars_decode(page_accueil())."");
    }
}
?>