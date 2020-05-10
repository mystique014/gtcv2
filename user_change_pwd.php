<?php
#########################################################################
#                       User_change_pwd.php                                   											#
#                                                                       													#
#                Réinitialisation du mot de passe                       											#
#                Dernière modification : 16/09/2009                   											#
#                                                                       													#
#########################################################################
/*
 * Copyright 2009- S duchemin
 *
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

if (isset($_POST['valid']) AND ($_POST['reg_email1'] != '') AND ($_POST['reg_login'] != '') AND $_POST['valid'] == 'yes'){

	$login = $_POST['reg_login'];
	$mail1 = $_POST['reg_email1'];
	$mail2 = $_POST['reg_email2'];
	
	if ($mail1 != $mail2){
	echo  "<h3>".get_vocab("error_mail")."</h3>";
	} else {
		$sql = "SELECT count(datenais) FROM ".$_COOKIE["table_prefix"]."_utilisateurs where email='$mail1' AND login='$login'";
		$resultat = grr_sql_query1($sql);
		if ($resultat == 0){
		echo  "<h3>".get_vocab("error_mail_empty")."</h3>";
		} else {
		
		//recherche de la date de naissance
		$sql = "SELECT datenais FROM ".$_COOKIE["table_prefix"]."_utilisateurs where email='$mail1' AND login='$login'";
		$resultat = grr_sql_query($sql);
		$row = mysqli_fetch_row($resultat);
		$datenais =  $row[0]; 
		
		$expediteur = "".getSettingValue("webmaster_email")."";
		$sujet = "".get_vocab("sujet_mail")."";
		$message_mail = "".get_vocab("message_mail")."";
		
		$boundary = "-----=" . md5( uniqid ( rand() ) );
		$headers  = "From: $expediteur\n";
  						$headers .= "Reply-To: $expediteur\n";
		// on indique qu'on a affaire à un email au format html et texte et
		// on spécifie la frontière (boundary) qui servira à séparer les deux parties
		// ainsi que la version mime
  						$headers .= "MIME-Version: 1.0\n";
  						$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"";
						$message  = "This is a multi-part message in MIME format.\n\n";
 						$message .= "--" . $boundary . "\n";
  						$message .= "Content-Type: text/plain; charset=\"UTF-8\"\n";
  						$message .= "Content-Transfer-Encoding: quoted-printable\n\n";
  						$message .= $message_mail;
  						$message .= $datenais;
						$message .= "\n\n";
						$message .= "--" . $boundary . "--\n";	
	
		mail($mail1, $sujet, $message, $headers);
		
		
		// Réinitialisation du mot de passe
				
		$reg_password1 = md5($datenais);
        $sql = "UPDATE ".$_COOKIE["table_prefix"]."_utilisateurs SET password='" . protect_data_sql($reg_password1)."' WHERE login='$login'";
		if (grr_sql_command($sql) < 0)
                    fatal_error(0, get_vocab("update_pwd_failed") . grr_sql_error());
                else
                {                    
		//Envoi du message de réinitialisation
		echo "<h3>".get_vocab("msg_mail")."</h3>";
				}
		}
		
	}
}

?>

<p>| <a href="login.php"><?php echo get_vocab("back");?></a> |</p>

<?php
echo "<h3>".get_vocab("init")."</h3>";

    echo "<form action=\"user_change_pwd.php\" method='POST'>";
    echo "<br>".get_vocab("user_mail");
	echo "<br><br>".get_vocab("login") .get_vocab("deux_points")."<input type=login name=reg_login size=20>";
    echo "<br><br>".get_vocab("adresse_mail1").get_vocab("deux_points")."<input type=email name=reg_email1 size=20>";
    echo "<br>".get_vocab("adresse_mail2").get_vocab("deux_points")."<input type=email name=reg_email2 size=20>";
    echo "<input type=hidden name=valid value=\"yes\">";
    echo "<br><input type=submit value=".get_vocab("submit")."></form>";
?>
</body>
</html>