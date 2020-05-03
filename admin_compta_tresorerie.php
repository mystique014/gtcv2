<?php
#########################################################################
#                            admin_compta_tresorerie.php                             #
#                                                                       #
#            interface trésorerie        #
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
echo "<h2>".get_vocab('admin_compta_tresorerie.php')."</h2>";
echo get_vocab('admin_compta_tresorerie_explications');

#If we dont know the right date then make it up
$valid = isset($_GET["valid"]) ? $_GET["valid"] : NULL;
$msg = '';

?>
<script type="text/javascript" src="./functions.js" language="javascript"></script>
<?php

if ($valid == "yes") {
    $reg_id_debit = isset($_GET["reg_id_debit"]) ? $_GET["reg_id_debit"] : NULL;
    $reg_id_credit = isset($_GET["reg_id_credit"]) ? $_GET["reg_id_credit"] : NULL;
    $reg_montant = isset($_GET["reg_montant"]) ? $_GET["reg_montant"] : NULL;
  
    if (($reg_id_debit == '') or ($reg_id_credit == '') or ($reg_montant == '')) {
        $msg = get_vocab("please_enter_fiche");
    } else {
        //
        // lecture du solde du compte débité
        //
         $sql = "SELECT id, solde FROM grr_compte_tresorerie WHERE id='$reg_id_debit'";
			$res = grr_sql_query($sql);
			if ($res) {
				for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
				{
				$solde_debit = $row[1];
				}
			}
		//
        // lecture du solde du compte crédité
        //
         $sql = "SELECT id, solde FROM grr_compte_tresorerie WHERE id='$reg_id_credit'";
			$res = grr_sql_query($sql);
			if ($res) {
				for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
				{
				$solde_credit = $row[1];
				}
			}
					
		//
		// Vérification de la possibilité de retirer le montant du solde débité
		//
		
		if ($solde_debit > $reg_montant) {
			$new_solde = $solde_debit - $reg_montant;	
            $sql = "UPDATE grr_compte_tresorerie SET solde='".protect_data_sql($new_solde)."'
            WHERE id='".protect_data_sql($reg_id_debit)."'";
            if (grr_sql_command($sql) < 0)
                {fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
            } else {
				$new_solde1 = $solde_credit + $reg_montant;	
				$sql = "UPDATE grr_compte_tresorerie SET solde='".protect_data_sql($new_solde1)."'
				WHERE id='".protect_data_sql($reg_id_credit)."'";
				if (grr_sql_command($sql) < 0)
					{fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
				} else {
                $msg = get_vocab("msg_fiche_modified");
				}
			}
		} else {
			$msg = get_vocab("debit_grand");
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
<p class=bold>
<a href="admin_compta_compte_tresorerie.php"><?php echo get_vocab("admin_compta_compte_tresorerie.php"); ?></a> |
<br>
</p>
<form action="admin_compta_tresorerie.php" method='GET'>

<span class="norme">
<?php
if (isset($_SESSION['login'])){$user_login = $_SESSION['login'];};
	//recherche du nom prénom de l'utilisateur conserné
	$sql = "SELECT nom, prenom FROM grr_utilisateurs WHERE login='$user_login'";
    $res = grr_sql_query($sql);
    if ($res) {
        for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
        $user_nom = $row[0];
        $user_prenom = $row[1];
        }
    }
// -----------------------------------------//Affichage du formulaire
echo "<table border=\"0\" cellpadding=\"5\"><tr>";
//---------Nom du compte à débiter
echo "<td>".get_vocab("nom_compte_debit")." ".get_vocab("deux_points")."</td>";
echo "<td><select name=\"reg_id_debit\">";
$sql = "SELECT id, name FROM grr_compte_tresorerie ORDER BY name";
$res = grr_sql_query($sql);
if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
{
echo "<option value='".$row[0]."'>";
echo "".$row[1]."</OPTION>";
}
echo "</select>";
echo "</td>\n";
echo "<td>".get_vocab("montant")." ".get_vocab("deux_points")."</td><td><input type=\"text\" name=\"reg_montant\" size=\"8\" value=\"";
echo "\"></td>\n";
//---------Nom du compte à créditer
echo "<td>".get_vocab("nom_compte_credit")." ".get_vocab("deux_points")."</td>";
echo "<td><select name=\"reg_id_credit\">";
$sql = "SELECT id, name FROM grr_compte_tresorerie ORDER BY name";
$res = grr_sql_query($sql);
if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
{
echo "<option value='".$row[0]."'>";
echo "".$row[1]."</OPTION>";
}
echo "</select>";
//------------------//fin liste catégorie
echo "</td></tr>\n";
echo "</table>";
echo "<input type=\"hidden\" name=\"valid\" value=\"yes\" />\n";
echo "<input type=\"hidden\" name=\"user_login\" value=\"".$user_login."\" />\n";
echo "<br><center><input type=\"submit\" value=\"".get_vocab("submit")."\" /></center>\n";
echo "</span></form>\n";

//-----------------------------fin du formulaire

// On liste les comptes existantes
//--------------------------------------------------------------------------------
echo "<table border=\"0\"><tr><td><p><b>Liste des comptes :</b></p></td></tr></table>";


//--------------------------------------------------------------------------------
$bgcolor = '#E9E9E4';
// Affichage du tableau
echo "<table border=\"1\" cellpadding=3>";
echo "<tr><td><b>".get_vocab("nom_compte")."</b></td>";
echo "<td><b>".get_vocab("solde_compte")."</b></td>";
echo "<td><b>".get_vocab("courant_compte")."</b></td>";
echo "</tr>";


// On appelle les informations de l'utilisateur pour les afficher :
    $sql = "SELECT id, name, courant, solde FROM grr_compte_tresorerie ORDER BY id";
    $res = grr_sql_query($sql);
    if ($res) {
	
	    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
        $compte_id = $row[0];
		$compte_name = $row[1];
		$compte_courant = $row[2];
		$compte_solde = $row[3];
	
		
		//construction des cellules du tableau
		$col[$i][0] = $compte_id;
		$col[$i][1] = $compte_name;
		$col[$i][2] = $compte_courant;
		$col[$i][3] = $compte_solde;
		
		If ($compte_courant =='1'){
		$bgcolor = '#FFCC99';
		} else {
		$bgcolor = '#E9E9E4';
		}			
				
		//affichage des cellules
		echo "<tr><td bgcolor='$bgcolor'>{$col[$i][1]}</td>";
		echo "<td td align='right' bgcolor='$bgcolor'>{$col[$i][3]}</td>";
		if ($compte_courant =='1') {
		echo "<td align='center' bgcolor='$bgcolor'>oui</td>";
		} else {
		echo "<td align='center' bgcolor='$bgcolor'>non</td>";
		}
		echo "</tr>";
        }
	}
echo "</table>";
echo "</body></html>";
?>