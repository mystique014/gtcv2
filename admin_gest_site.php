<?php
#########################################################################
#                            admin_gest_site.php                                  #
#                                                                       #
#            interface de gestion des sites                                    #
#               Derni�re modification : 28/04/2019
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
// Param�tres langage
include "include/language.inc.php";
include "include/mysql.inc.php";
$con= new mysqli($dbHost, $dbUser, $dbPass, $dbDb);


/* V�rification de la connexion */
if (mysqli_connect_errno()) {
    printf("�chec de la connexion : %s\n", mysqli_connect_error());
    exit();
}
if (isset($_GET['table_sup']))
	{	
	$table_sup = $_GET["table_sup"];
	$sql = "DELETE FROM sites WHERE name='".$table_sup."'";
	//Recherche de l'adresse mail pour pr�venir la personne de la fermeture du site
	$r = mysqli_query($con, "SELECT email FROM sites WHERE name = '".$table_sup."' ");
	$row_mail=mysqli_fetch_row($r);

       if (mysqli_query($con, $sql)) {
			echo "Les tables de ".$table_sup." sont d&eacute;truite";
			mail($row_mail[0], 'Utilisation du site de r�servation GTCV2','Vous avez install� un espace de r�servation '.$table_sup.' sur le site GTCV2 http://gtcv2multi.alwaysdata.net/gtc/ - Site de r�servation en ligne. Nous avons constat� qu\'il n\'�tait pas utilis�. Il a donc �te d�truit. Bien cordialement. L\'�quipe GTC','');
			} else {
			echo "Error deleting record: " . mysqli_error($con);
			}
			
	$show ="SELECT table_name FROM information_schema.tables WHERE table_schema = '".$dbDb."' AND table_name LIKE '".$table_sup."%'";
	if ($result=mysqli_query($con,$show))
	{
		// Fetch one and one row
		while ($row=mysqli_fetch_row($result))
		{
		$sql2 = "DROP TABLE ".$row[0].""; 	
		mysqli_query($con, $sql2) or die(mysql_error());
		}
	// Free result set
	mysqli_free_result($result);
	}
	}

		// On cr�� la requ�te
$req = "SELECT name FROM sites ORDER BY name";
$res = $con->query($req);
$req1 ="SHOW�TABLES�FROM�gtcv2multi_gtcv2�LIKE�'%log'";
$res1 = $con->query($req1);
?>
<div align="center">
<h1><?php echo "Interface de gestion"; ?></h1>
<h2><?php echo "GTCV2 !"; ?></h2>
<IMG SRC="img_grr/logo.jpg" ALT="Logo" TITLE="Logo du club"><br><br>
</div>

<form action="admin_gest_site.php" method='GET'>
<fieldset style="padding-top: 8px; padding-bottom: 8px; width: 40%; margin-left: auto; margin-right: auto;">
<legend class="fontcolor3" style="font-variant: small-caps;"><?php echo "Choisissez le site &agrave; supprimer dans la liste"; ?></legend>
<table style="width: 100%; border: 0;" cellpadding="5" cellspacing="0">
<tr>
<td style="text-align: right; width: 40%; font-variant: small-caps;"><?php echo get_vocab("site"); ?></td>

<?php
echo "<td><select name=\"table_sup\" size=\"1\">\n";

    while ($resultat = mysqli_fetch_row($res)) {
    echo "<option value='$resultat[0]'";
	if ($user_abt == $resultat[0]) {
	echo " SELECTED>";
	}else{ echo ">";
	}
    echo $resultat[0].'</option>'."\n";
    }
    echo '</select>'."\n"; 
	echo "</td></tr>\n";
	
	
?>
</table>
<input type="submit" name="submit" value="<?php echo get_vocab("delete"); ?>" style="font-variant: small-caps;">
</fieldset>
</form>

<fieldset style="padding-top: 8px; padding-bottom: 8px; width: 40%; margin-left: auto; margin-right: auto;">
<legend class="fontcolor3" style="font-variant: small-caps;"><?php echo  "Informations"?></legend>
<table style="width: 100%; border: 0;" cellpadding="5" cellspacing="0">
<tr>
<td style="text-align: left; width: 40% ">
<blink style="color:#900"><?php echo "Ce formulaire permet de d&eacute;truire les sites !"; ?></blink></td>
</tr>
</table>
</fieldset>