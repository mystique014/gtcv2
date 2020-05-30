<?php
#########################################################################
#                            admin_gest_site.php                                  #
#                                                                       #
#            interface de gestion des sites                                    #
#               Dernière modification : 28/04/2019
                    #
#                                                                       #
#                                                                       #
#########################################################################
/*
 */
/*
 include "include/admin.inc.php";

if(authGetUserLevel(getUserName(),-1) < 5)
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

$back = "";
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
*/
include "include/connect.inc.php";
// Paramètres langage
include "include/language.inc.php";
include "include/mysql.inc.php";
$con= new mysqli($dbHost, $dbUser, $dbPass, $dbDb);


/* Vérification de la connexion */
if (mysqli_connect_errno()) {
    printf("Échec de la connexion : %s\n", mysqli_connect_error());
    exit();
}
if (isset($_GET['envoi']))
	{	

	// On créé la requête

	$req = "SELECT name FROM sites ORDER BY name";
	$res = $con->query($req);
	echo "<table>";
	while ($resultat = mysqli_fetch_row($res)) {
	   $site = $resultat[0];
       $site_log = $resultat[0].'_log';
	   $req1 ="SELECT START FROM $site_log WHERE START > DATE_SUB(NOW(), INTERVAL 20 DAY)";
	   $res1 = $con->query($req1);
			if (NULL == mysqli_fetch_row($res1)){
				$message = $message . $site_log;
				echo "<tr>".$site_log."<br></tr>";
					//Recherche de l'adresse mail pour prévenir la personne de la fermeture du site
					$r = mysqli_query($con, "SELECT email FROM sites WHERE name = '".$site."' ");
					$row_mail=mysqli_fetch_row($r);
				mail($row_mail[0], 'Utilisation du site de réservation GTCV2','Vous avez installé un espace de réservation '.$site.' sur le site GTCV2 http://gtcv2multi.alwaysdata.net/gtc/ - Site de réservation en ligne. Nous avons constaté qu\'il n\'était pas utilisé. Il sera donc détruit prochainement. Bien cordialement. L\'équipe GTC','');
				mail('gtcmulti@gmail.com', 'Site averti inactif', $site,'' );
			}
    }
	mail('gtcmulti@gmail.com', 'Sites inactifs', $message,'' );
	echo "</table>";
	}
?>	

<div align="center">
<h1><?php echo "Interface de gestion"; ?></h1>
<h2><?php echo "GTCV2 !"; ?></h2>
<IMG SRC="img_grr/logo.jpg" ALT="Logo" TITLE="Logo du club"><br><br>
</div>


<form action="admin_gest_site_log.php" method='GET'>
<fieldset style="padding-top: 8px; padding-bottom: 8px; width: 40%; margin-left: auto; margin-right: auto;">
<legend class="fontcolor3" style="font-variant: small-caps;"><?php echo "Envoyer un mail aux sites inactifs depuis 3 semaines"; ?></legend>
<table style="width: 100%; border: 0;" cellpadding="5" cellspacing="0">
<tr>
<td style="text-align: right; width: 40%; font-variant: small-caps;"><?php echo get_vocab("site"); ?></td>


<input name="envoi" type="hidden" value="yes">
	
	

</table>
<input type="submit" name="submit" value="<?php echo get_vocab("sen_a_mail"); ?>" style="font-variant: small-caps;">
</fieldset>
</form>

<?php
	// On créé la requête

	$req = "SELECT name FROM sites ORDER BY name";
	$res = $con->query($req);
	echo "<table>";
	while ($resultat = mysqli_fetch_row($res)) {
	   $site = $resultat[0];
       $site_log = $resultat[0].'_log';
	   $req1 ="SELECT START FROM $site_log WHERE START > DATE_SUB(NOW(), INTERVAL 20 DAY)";
	   $res1 = $con->query($req1);
			if (NULL == mysqli_fetch_row($res1)){
				$message = $message . $site_log;
				echo "<tr>".$site_log."<br></tr>";
					//Recherche de l'adresse mail pour prévenir la personne de la fermeture du site
					$r = mysqli_query($con, "SELECT email FROM sites WHERE name = '".$site."' ");
					$row_mail=mysqli_fetch_row($r);
				//mail($row_mail[0], 'Utilisation du site de réservation GTCV2','Vous avez installé un espace de réservation '.$site.' sur le site GTCV2 http://gtcv2multi.alwaysdata.net/gtc/ - Site de réservation en ligne. Nous avons constaté qu\'il n\'était pas utilisé. Il sera donc détruit prochainement. Bien cordialement. L\'équipe GTC','');
				//mail('gtcmulti@gmail.com', 'Site averti inactif', $site,'' );
			}
    }
	//mail('gtcmulti@gmail.com', 'Sites inactifs', $message,'' );
	echo "</table>";
?>

<fieldset style="padding-top: 8px; padding-bottom: 8px; width: 40%; margin-left: auto; margin-right: auto;">
<legend class="fontcolor3" style="font-variant: small-caps;"><?php echo  "Informations"?></legend>
<table style="width: 100%; border: 0;" cellpadding="5" cellspacing="0">
<tr>
<td style="text-align: left; width: 40% ">
<blink style="color:#900"><?php echo "Ce formulaire permet de v&eacute;rifier l'activit&eacute; des sites !"; ?></blink></td>
</tr>
</table>
</fieldset>



