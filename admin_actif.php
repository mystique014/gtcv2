<?php
#########################################################################
#                           admin_actif                             													  #
#                                                                       													  #
#                       Interface d'accueil                             												  #
#             de Gestion des comptes Utilisateurs Actifs/Inactifs                   													  #
#                                                                       													  #
#                  Dernière modification : 19/10/2008                 											  #
#                                                                       													  #
#########################################################################
/*
 * Copyright 2003-2008 stéphane duchemin

 */
include "include/admin.inc.php";

$valid = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
$user_login = isset($_GET["user_login"]) ? $_GET["user_login"] : NULL;
$display = isset($_GET["display"]) ? $_GET["display"] : NULL;
$order_by = isset($_GET["order_by"]) ? $_GET["order_by"] : NULL;
$cochemail = isset($_GET["cochemail"]) ? $_GET["cochemail"] : NULL;


if(authGetUserLevel(getUserName(),-1,'area') < 4)
{
    $back = '';
    if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}
# print the page header
print_header("","","","",$type="with_session", $page="admin");
// Affichage de la colonne de gauche
include "admin_col_gauche.php";
?>
<script type="text/javascript" src="functions.js" language="javascript"></script>
<h2><?php echo get_vocab("actif_inactif"); ?></h2>

<?php
if (empty($display)) { $display = 'actifs'; }
if (empty($order_by)) { $order_by = 'nom,prenom'; }
if (empty($cochemail)) { $cochemail = 'aucun'; }

// Affichage du tableau
echo "<table border=1 cellpadding=3>";
echo "<td><b>".get_vocab("names")."</a></b></td>";
echo "<td><b>".get_vocab("activ_no_activ")."</a></b></td>";
echo "</tr>";


$sql = "SELECT nom, prenom, licence, login, etat, statut FROM ".$_COOKIE["table_prefix"]."_utilisateurs ORDER BY $order_by";
$res = grr_sql_query($sql);
if ($res) {
    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {

    $user_nom = $row[0];
    $user_prenom = $row[1];
    $user_licence = $row[2];
	$user_login = $row[3];
	$user_etat[$i] = $row[4];
	$user_statut[$i] = $row[5];
	
    if ($user_statut[$i] == 'utilisateur') {
    // Affichage des login, noms et prénoms
    $col[$i][1] = "$user_nom $user_prenom";
	$col[$i][4] = $user_etat[$i];
	echo "<input type='hidden' name='user[]' value='$user_login'>";
		
	if ($user_etat[$i] == 'actif') {
	$bgcolor = '#E9E9E4';
	echo "<tr><td bgcolor='$bgcolor'>{$col[$i][1]}</td>";
     } else {
    $bgcolor = 'C0C0C0'; 
	echo "<tr><td bgcolor='$bgcolor'>{$col[$i][1]}</td>";	  
    }
	echo "<td bgcolor='$bgcolor'>{$col[$i][4]}</td>";
	// Affichage case à cocher pour saisie licence
echo "<form ENCTYPE=\"multipart/form-data\" action=\"admin_actif.php\" method=\"POST\" >\n";   
    		if ($cochemail == 'tous'){
        echo "<td bgcolor='$bgcolor'><input type='checkbox' name='actif[]' value='$user_login' checked></td>";
    		} else {
    		echo "<td bgcolor='$bgcolor'><input type='checkbox' name='actif[]' value='$user_login' ></td>";
		}
		
	echo "</tr>";
    }
    }
}

echo "</table>";
echo "</td></tr></table><br>";

echo "<center><input type='radio' name='valid' value='activer' ";  if (getSettingValue("valid")=='activer') echo "checked"; echo ">Activer";
echo "</td></tr>";
echo "<input type='radio' name='valid' value='désactiver' "; if (getSettingValue("valid")=='désactiver') echo "checked"; echo ">D&eacute;sactiver";
echo "</td></tr>";

echo "<input type=\"submit\" name='valider' value=\"Valider\" /></center>\n";
echo "</form>";

//test si il faut ajouter ou supprimer les licences
$valid = isset($_POST["valid"]) ? $_POST["valid"] : NULL;

	if (isset($valid) AND ($valid != '')) {
	if($valid == 'activer'){
				$actif = $_POST['actif'];
				$nbr = count ($actif);
				$i = 0;
					while ($i < $nbr) {
					 $user = $actif[$i];
					 $sql = "UPDATE ".$_COOKIE["table_prefix"]."_utilisateurs SET etat= 'actif' WHERE login = '".protect_data_sql($user)."'";
   		      grr_sql_query($sql);
			  $i++;
					}
					$valid='';
		}else{
				$actif = $_POST['actif'];
				$nbr = count ($actif);
				$i = 0;
					while ($i < $nbr) {
					 $user = $actif[$i];
					 $sql = "UPDATE ".$_COOKIE["table_prefix"]."_utilisateurs SET etat= 'inactif' WHERE login = '".protect_data_sql($user)."'";
   		      grr_sql_query($sql);
			  $i++;
					}
					$valid='';
			}
	}


?>	
</body>
</html>