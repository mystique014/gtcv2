<?php
/**
 * install_site.php
 * Interface d'installation de GTC pour un environnement mysql MULTISITE
 *
 */
require_once("include/config.inc.php");
require_once("include/misc.inc.php");
require_once("include/functions.inc.php");
require_once("include/connect.inc.php");
//require_once("include/connect.inc.php");
$etape = isset($_GET["etape"]) ? $_GET["etape"] : 3;
$table_prefix = isset($_GET["table_prefix"]) ? $_GET["table_prefix"] : NULL;
$table_prefix = str_replace("'", "", $table_prefix);
$search  = array(' ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
$replace = array('', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
$table_prefix = str_replace($search, $replace, $table_prefix);
// Traite la chaine pour la transformer en minuscules
$table_prefix = strtolower ($table_prefix);
// Limite le nombre de caractères à 20
$table_prefix = substr($table_prefix, 0, 20);
$description = isset($_GET["description"]) ? $_GET["description"] : NULL;
$ad_mail = isset($_GET["ad_mail"]) ? $_GET["ad_mail"] : NULL;
// Pour cette page uniquement, on désactive l'UTF8 et on impose l'ISO-8859-1
$unicode_encoding = 1;
$charset_html = "utf-8";
function begin_html()
{
	echo '<div style="margin-left:15%;margin-right:15%;"><table><tr><td>';
	echo '<body background="images/fond.jpg">';

}
function end_html()
{
	echo '</td></tr></table></div></body></html>';
}
/**
 * @param integer $row
 */

if ($etape == 5)
{	
	echo begin_page("Installation de GTC");
	begin_html();
	$_COOKIE["table_prefix"] = $table_prefix;			
	echo "<br><h2>Dernière étape : C'est terminé !</h2>";
	echo "<p>";
	echo "<p>Vous pouvez maintenant commencer à utiliser le système de réservation pour votre site : ".$_COOKIE["table_prefix"]."</p>";
	echo "<br>Soit, en passant par le <a href = 'site.php'>Portail </a>";
	echo "<br>Soit, directement sans passer par le portail à cette adresse <a href = 'login.php?table_prefix=".$_COOKIE["table_prefix"].". Pensez à mettre ce lien en favori !' >Votre site </a>";
	echo "<p>";
	echo "<p>Pour vous connecter la première fois en tant qu'administrateur, utilisez l'identifiant de connection <b>\"administrateur\"</b> et le mot de passe <b>\"azerty\"</b>.</p>";
	echo "<p>N'oubliez pas de changer le mot de passe !</p>";
		echo "<p>Enjoy !</p>";
				
	end_html();
	die();
}
			

if ($etape == 4)
{
	$test1 = 'no';
	if (($description != '') AND ($table_prefix != '') AND ($ad_mail != '') AND (isset($_GET['conditions'])))
	{	
			$db = mysqli_connect("$dbHost", "$dbUser", "$dbPass", "$dbDb");
			$_COOKIE["table_prefix"] = $table_prefix;
			// Premier test : on vérifie que le nom de site (préfixe) n'existe pas.
			$r = mysqli_query($db, "SELECT name FROM sites WHERE name = '".$_COOKIE["table_prefix"]."' ");
			$result = mysqli_num_rows($r);
			//echo $result;
				if ($result != 0)
				{
					echo "<br /><h2>L'installation n'a pas pu se terminer normalement : ce site existe déjà.</h2>";
					$test1 = 'no';
				}else
				{
					$test1 = 'yes';
				}
				
			// Deuxième test : on vérifie que l'adresse mail n'est pas utilisée.
			$r2 = mysqli_query($db, "SELECT email FROM sites WHERE email = '".$ad_mail."' ");
			$result2 = mysqli_num_rows($r2);
			//echo $result2;
				if ($result2 != 0)
				{
					echo "<br /><h2>L'installation n'a pas pu se terminer normalement : adresse mail déjà utilisée. Rappel : une seule installation par club.</h2>";
					$test2 = 'no';
				}else
				{
					$test2 = 'yes';
				}
		
		if (($test1 == 'yes') AND ($test2 == 'yes'))
		{
			echo begin_page("Installation de GTC");
			begin_html();
			echo "<br /><h2>Création des tables de la base</h2>";
			$db = mysqli_connect("$dbHost", "$dbUser", "$dbPass", "$dbDb");
	
			if (mysqli_select_db($db, "$dbDb"))
			{
				$fd = fopen("tables.my.sql", "r");
				$result_ok = 'yes';
				while (!feof($fd))
				{
					$query = fgets($fd);
					$query = trim($query);
					$query = preg_replace("/DROP TABLE IF EXISTS grr/","DROP TABLE IF EXISTS ".$_COOKIE["table_prefix"],$query);
					$query = preg_replace("/CREATE TABLE grr/","CREATE TABLE ".$_COOKIE["table_prefix"],$query);
					$query = preg_replace("/INSERT INTO grr/","INSERT INTO ".$_COOKIE["table_prefix"],$query);
					//echo $query;
					if ($query != '')
					{
					$reg = mysqli_query($db, $query);
						if (!$reg)
						{
						echo "<br /><font color=\"red\">ERROR</font> : '$query'";
						$result_ok = 'no';
						}
					}
				}
				$sql = "INSERT INTO sites (description, name, email, timestamp)VALUES ('".$description."','".$_COOKIE["table_prefix"]."','".$ad_mail."',CURRENT_TIMESTAMP)";
				$result =mysqli_query($db, $sql);
				fclose($fd);
				mail('stephane.duchemin3@libertysurf.fr', 'Nouvelle installation GTCV2',$ad_mail."  ".$description."  ".$_COOKIE["table_prefix"],'');
				mail('gtcmulti@gmail.com', 'Nouvelle installation GTCV2',$ad_mail."  ".$description."  ".$_COOKIE["table_prefix"],'');
				mail($ad_mail, 'Nouvelle installation GTCV2','Bienvenue le site '.$_COOKIE["table_prefix"].' est installé vous pouvez vous connecter sur le portail http://gtcv2multi.alwaysdata.net/site.php en indiquant le nom du site que vous avez renseigné dans le formulaire. Soit, directement sans passer par le portail à cette adresse http://gtcv2multi.alwaysdata.net/login.php?table_prefix='.$_COOKIE["table_prefix"].'. Utilisez l\'identifiant: "administrateur" et le mot de passe "azerty". Pensez à les modifier dans votre espace administration. Bonne navigation','');
					if ($result_ok == 'yes')
				{
			
					$table_prefix =$_COOKIE["table_prefix"];
					echo "<b>La structure de votre base de données est installée.</b><br />Vous pouvez passer à l'étape suivante.";
					echo "<form action='install_site.php' method='get'>";
					echo "<input type='hidden' name='etape' value='5' />";
					echo "<input type='hidden' name='table_prefix' value=\"$table_prefix\" />";
					echo "<div style=\"text-align:right;\"><input type='submit' class='fondl' name='Valider' value='Suivant &gt;&gt;' /><div>";
					echo "</form>";
				}
				if ($result_ok != 'yes')
				{
				echo "<p><b>L'opération a échoué.</b> Retournez à la page précédente, sélectionnez une autre base ou créez-en une nouvelle. Vérifiez les informations fournies par votre hébergeur.</p>";
				}
			}
			else
			{
			echo "<p><b>Impossible de sélectionner la base. GTC n'a peut-être pas pu créer la base.</b></p>";
			}
			end_html();
		}
	}else
	{
	echo "<br /><h2>Veuillez complétez les informations et accepter les conditions générales !</h2>\n";
	end_html();
	die();
	}	
}
else if ($etape == 3)
{
	echo begin_page("Installation de GTC");
	begin_html();
	echo "<h2>Choisissez un nom à votre site</h2>\n";
	echo "<form action='install_site.php' method='get'><div>\n";
	echo "<input type='hidden' name='etape' value='4' />\n";
	echo "<fieldset><label><b>Nom du site :</b><br /></label>\n";
    echo "Le nom s'écrit en <b>lettres minuscules, 40 caractères maximum</b>.";
    echo "<br /><input type='text' name='description' class='fondo' value=\"\" size='30' />\n";
    echo "</fieldset>\n";
    echo "<br /><fieldset><label><b>Préfixe du site :</b><br /></label>\n";
    echo "Le préfixe s'écrit en <b>lettres minuscules, non accentuées, sans espace, 20 caractères maximum</b>.";
    echo "<br /><input type='text' name='table_prefix' class='fondo' value=\"\" size='10' />\n";
    echo "</fieldset>\n";
	echo "<br /><fieldset><label><b>Adresse mail de contact :</b><br /></label>\n";
    echo "<br /><input type='text' name='ad_mail' class='fondo' value=\"\" size='35' />\n";
    echo "</fieldset>\n";
	echo "<br /><fieldset><label>J'accepte les <font color=\"blue\"><a href = 'conditions.html'>conditions générales</a></font></label><input type=\"checkbox\" name=\"conditions\" />\n";
	echo "</fieldset>\n";
	echo "<div style=\"text-align:right;\"><input type='submit' class='fondl' name='Valider' value='Suivant &gt;&gt;' /></div>\n";
	echo "</div></form>\n";
	end_html();
}
?>
