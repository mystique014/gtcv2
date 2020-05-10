<?php
#########################################################################
#                            site.php                                  #
#                                                                       #
#            interface de connexion                                     #
#               Dernière modification : 30/12/2016                    #
#                                                                       #
#                                                                       #
#########################################################################
/*
 */
include "include/connect.inc.php";
// Paramètres langage
include "include/language.inc.php";
$con= new mysqli($dbHost, $dbUser, $dbPass, $dbDb);


/* Vérification de la connexion */
if (mysqli_connect_errno()) {
    printf("Échec de la connexion : %s\n", mysqli_connect_error());
    exit();
}

// On créé la requête
$req = "SELECT name FROM sites ORDER BY name";

$res = $con->query($req);

?>
<div align="center">
<h1><?php echo "Portail"; ?></h1>
<h2><?php echo "GTCV2 !"; ?></h2>
<IMG SRC="img_grr/logo.jpg" ALT="Logo" TITLE="Logo du club"><br><br>
</div>
<form action="login.php" method='POST'>
<fieldset style="padding-top: 8px; padding-bottom: 8px; width: 40%; margin-left: auto; margin-right: auto;">
<legend class="fontcolor3" style="font-variant: small-caps;"><?php echo "Votre site est install&eacute;, choisissez-le dans la liste"; ?></legend>
<table style="width: 100%; border: 0;" cellpadding="5" cellspacing="0">
<tr>
<td style="text-align: right; width: 40%; font-variant: small-caps;"><?php echo get_vocab("site"); ?></td>

<?php
echo "<td><select name=\"table_prefix\" size=\"1\">\n";

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
<input type="submit" name="submit" value="<?php echo get_vocab("submit"); ?>" style="font-variant: small-caps;">
</fieldset>
</form>

<fieldset style="padding-top: 8px; padding-bottom: 8px; width: 40%; margin-left: auto; margin-right: auto;">
<legend class="fontcolor3" style="font-variant: small-caps;"><?php echo  "Informations"?></legend>
<table style="width: 100%; border: 0;" cellpadding="5" cellspacing="0">
<tr>
<td style="text-align: left; width: 40% ">
<blink style="color:#900"><?php echo "Placez la page du portail dans vos marques pages"; ?></blink></td>
</tr>
</table>
</fieldset>

