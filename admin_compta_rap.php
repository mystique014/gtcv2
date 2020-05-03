<?php
#########################################################################
#                            admin_compta_rap.php                             #
#                                                                       #
#            permet de faire les rapprochements comptables         #
#               Dernière modification : octobre 2012                    #
#                                                                       #
#                                                                       #
#########################################################################
/*
 * S Duchemin
 *
 *
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

include "include/admin.inc.php";


if(authGetUserLevel(getUserName(),-1) < 5)
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

$back = "";
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];

if ((isset($_GET['msg'])) and isset($_SESSION['displ_msg'])) {
   $msg = $_GET['msg'];
   unset($_SESSION['displ_msg']);
}
else
	$msg = '';
# print the page header
print_header("","","","",$type="with_session", $page="admin");
// Affichage de la colonne de gauche
include "admin_col_gauche.php";
echo "<h2>".get_vocab('admin_compta_rap.php')."</h2>";
echo get_vocab('admin_compta_rap_explications');

#If we dont know the right date then make it up
$valid = isset($_GET["valid"]) ? $_GET["valid"] : NULL;
$default_year = getSettingvalue("default_year");
$msg = '';
$rap = '';
$sec = '';


?>
<script type="text/javascript" src="./functions.js" language="javascript"></script>
<?php

if ($valid == "yes"){
	if (isset($_GET['rap'])){
    $rap = $_GET['rap'];	
	$nbr = count ($rap);

        // enregistrement du rapprochement
        //
		$i = 0;
		while ($i < $nbr) {
		    $sql = "UPDATE grr_compta SET rap='1' WHERE id='".protect_data_sql($rap[$i])."'";
            if (grr_sql_command($sql) < 0)
                {fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
            } else {
                $msg = get_vocab("msg_rap");
			}
			$i++;
        }  
	} elseif (isset($_GET['sec'])) {
	$sec = $_GET['sec'];	
	$nbr = count ($sec);

        // enregistrement du rapprochement
        //
		$i = 0;
		while ($i < $nbr) {
		    $sql = "UPDATE grr_compta SET rap='0' WHERE id='".protect_data_sql($sec[$i])."'";
            if (grr_sql_command($sql) < 0)
                {fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
            } else {
                $msg = get_vocab("msg_rap");
			}
			$i++;
        }
	
	}
}


if((authGetUserLevel(getUserName(),-1) < 1) and ($authentification_obli==1))
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

echo "<noscript>";
echo "<font color='red'>$msg</font>";
echo "</noscript>";
if ($msg)   {
    echo "<script type=\"text/javascript\" language=\"javascript\">";
    echo "<!--\n";
    echo " alert(\"".$msg."\")";
    echo "//-->";
    echo "</script>";
    unset($msg);
}
?>
<form action="admin_compta_rap.php" method='GET'>

<span class="norme">

<?php
// On choisit l'année d'affichage (par défaut la valeur est l'année sportive)
//On commence par récupérer toutes les années enregistrées dans la base avec le login du joueur
//On tri et élimine les doublons pour un affichage ordonné en liste
//--------------------------------------------------------------------------------
echo "<p><b>Liste des fiches pour l'ann&eacute;e :  <font color='red'><b>".getSettingValue("default_year")."</b></font></b></p>";
echo "<table border=1 cellpadding=0>";
echo "<tr><td>".get_vocab("secours").get_vocab("deux_points")."</td>";
echo "<td><input type='checkbox' name='secours' onclick='this.form.submit()'></td><td>".get_vocab("secours_explication")."</td></tr>";
echo "</table>";

//--------------------------------------------------------------------------------
$bgcolor = '#E9E9E4';
// Affichage du tableau
echo "<table border=1 cellpadding=3>";
echo "<tr><td><b>".get_vocab("datee")."</b></td>";
echo "<td><b>".get_vocab("mode")."</b></td>";
echo "<td><b>".get_vocab("description")."</b></td>";
echo "<td><b>".get_vocab("abonnes")."</b></td>";
echo "<td><b>".get_vocab("nom_categorie")."</b></td>";
echo "<td><b>".get_vocab("montant")."</b></td>";
echo "<td><b>".get_vocab("admin_compta_rap.php")."</b></td>";
if (isset($_GET['secours'])){
echo "<td><b>".get_vocab("secours")."</b></td>";
}
echo "</tr>";


// On appelle les informations de l'utilisateur pour les afficher :
    $sql = "SELECT id, date, mode, description, login, categorie, montant, default_year, rap  FROM grr_compta WHERE default_year='$default_year' ORDER BY date DESC";
    $res = grr_sql_query($sql);
    if ($res) {
		for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
        $user_id = $row[0];
		$user_date= $row[1];
		$user_mode = $row[2];
		$user_description = $row[3];
		$user_login = $row[4];
        $user_categorie = $row[5];
		$user_montant = $row[6];
		$user_year = $row[7];
		$user_rap = $row[8];
		
        
				
		//construction des cellules du tableau
		$col[$i][0] = $user_id;
		$col[$i][1] = $user_date;
		$col[$i][2] = $user_mode;
		$col[$i][3] = $user_description;
		$col[$i][4] = $user_login;
		//affichage du nom de la catégorie
		$sql = "SELECT name FROM grr_categorie_compta WHERE id='$user_categorie'";
		$result = grr_sql_query($sql);
		 for ($j = 0; ($row = grr_sql_row($result, $j)); $j++)
        {
        $nom_categorie = $row[0];
		}
		$col[$i][5] = $nom_categorie;
		$col[$i][6] = $user_montant;
		
		if ($user_rap == '1'){
		$bgcolor = '#FFCC99';
		} else {
		$bgcolor = '#E9E9E4';
		}		
		//affichage des cellules
		echo "<tr><td bgcolor='$bgcolor'>{$col[$i][1]}</td>";
		echo "<td bgcolor='$bgcolor'>{$col[$i][2]}</td>";
		echo "<td bgcolor='$bgcolor'>{$col[$i][3]}</td>";
		echo "<td bgcolor='$bgcolor'><a href='admin_user_modify.php?user_login=$user_login&amp'>{$col[$i][4]}</td>";
		echo "<td bgcolor='$bgcolor'>{$col[$i][5]}</td>";
		echo "<td align='right' bgcolor='$bgcolor'>{$col[$i][6]}</td>";
		
		
		// Affichage de la case rapprochement
		if ($user_rap == '1'){
		echo "<td bgcolor='$bgcolor'></td>";
		} else {
		echo "<td bgcolor='$bgcolor'><input type='checkbox' name='rap[]' value='$user_id' ></td>";
		}
		
		
		//Affichage  de la case  secours pour " Dé_rapprocher une fiche" si une fiche a été rapprochée par erreur
		if ((isset($_GET['secours'])) AND ($user_rap == '1')){
		echo "<td bgcolor='$bgcolor'><input type='checkbox' name='sec[]' value='$user_id' ></td>";
		}
		echo "</tr>";
        }
		
	}
echo "<input type=\"hidden\" name=\"valid\" value=\"yes\">\n";
echo "<br><div id=\"fixe\" style=\"text-align:center;\"><input type=\"submit\" name=\"ok\" value='Envoyer' style=\"font-variant: small-caps;\"/></div>";
echo "</form>";
echo "</table>";
echo "</body></html>";
?>