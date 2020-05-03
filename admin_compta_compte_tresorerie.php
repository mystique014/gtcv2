<?php
#########################################################################
#                            admin_compta_compte_tresorerie.php                             #
#                                                                       #
#            interface de création de comptes de trésorerie       #
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
echo "<h2>".get_vocab('admin_compta_compte_tresorerie.php')."</h2>";
echo get_vocab('admin_compta_compte_tresorerie_explications');


#If we dont know the right date then make it up
$valid = isset($_GET["valid"]) ? $_GET["valid"] : NULL;
$display = isset($_GET["display"]) ? $_GET["display"] : NULL;
$action_modify = isset($_GET["action_modify"]) ? $_GET["action_modify"] : NULL;
$msg = '';
$nbr = '';
$temp = '';

?>
<script type="text/javascript" src="./functions.js" language="javascript"></script>
<?php

if ($valid == "yes") {
    $reg_compte = isset($_GET["reg_compte"]) ? $_GET["reg_compte"] : NULL;
	$reg_solde = isset($_GET["reg_solde"]) ? $_GET["reg_solde"] : NULL;
	$reg_courant = isset($_GET["reg_courant"]) ? $_GET["reg_courant"] : NULL;
	
	
	if (($reg_compte== '') OR ($reg_solde== '')){
        $msg = get_vocab("please_enter_fiche");
        
    } else {
		$temp = $_GET['id_compte'];
		//controle s'il existe déjà un compte courant actif
		$nbr = grr_sql_query1 ("SELECT count(id) FROM grr_compte_tresorerie WHERE courant='1' AND id !='$temp'");
        //
        // action s'il s'agit d'une modification de compte
        //
        if ($action_modify =='yes') {
		
		
		if (($reg_courant == '' )) {
		    $sql = "UPDATE grr_compte_tresorerie SET name='".protect_data_sql($reg_compte)."',
			solde ='".protect_data_sql($reg_solde)."',
			courant ='".protect_data_sql($reg_courant)."'
            WHERE id='".protect_data_sql($temp)."'";
            if (grr_sql_command($sql) < 0)
                {fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
            } else {
                $msg = get_vocab("msg_compte_modified");
			}
		} else if ((($reg_courant == '1' )) AND (($nbr =='0'))){
		$sql = "UPDATE grr_compte_tresorerie SET name='".protect_data_sql($reg_compte)."',
			solde ='".protect_data_sql($reg_solde)."',
			courant ='".protect_data_sql($reg_courant)."'
            WHERE id='".protect_data_sql($temp)."'";
            if (grr_sql_command($sql) < 0)
                {fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
            } else {
                $msg = get_vocab("msg_compte_modified");
			}
		} else {
		
		$msg = get_vocab("msg_compte_unique_error");
		}
		//
		//actionssi une nouvelle catégorie comptable est créée
		//
        } else if (($reg_courant == '') OR (($reg_courant == '1') AND ($nbr =='0')))  {
          $sql = "INSERT INTO grr_compte_tresorerie SET
                    name='".protect_data_sql($reg_compte)."',
					solde ='".protect_data_sql($reg_solde)."',
					courant='".protect_data_sql($reg_courant)."'";
                    if (grr_sql_command($sql) < 0)
                        {fatal_error(0, get_vocab("msg_fiche_created_error") . grr_sql_error());
                    } else {
                        $msg = get_vocab("msg_compte_created");
                    }  
        } else {
            $msg = get_vocab("msg_compte_unique_error");
		}
	}
    
}

// On appelle les informations de la fiche à modifier pour les afficher :
if ($action_modify =='yes') {
	$temp = $_GET['id_compte'];
    $sql = "SELECT id, name, solde FROM grr_compte_tresorerie WHERE id='$temp'";
    $res = grr_sql_query($sql);
    if ($res) {
        for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
        $id_compte = $row[0];
        $mod_compte = $row[1];
		$solde_compte = $row[2];
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

//
// Supression d'une fiche comptable
//
if ((isset($_GET['action_del']) and ($_GET['js_confirmed'] ==1))) {
		$temp = $_GET['compte_del'];
        $sql = "DELETE FROM grr_compte_tresorerie WHERE id='$temp'";
        if (grr_sql_command($sql) < 0) {fatal_error(1, "<p>" . grr_sql_error());}  else {
           $msg=get_vocab("del_fiche");
        }
    
}


?>
<p class=bold>
|<a href="admin_compta_compte_tresorerie.php"><?php echo get_vocab("fiche"); ?></a> |
<a href="admin_compta_tresorerie.php?year=<?php echo getSettingValue("default_year"); ?>"><?php echo get_vocab("back"); ?></a> |
<br>
</p>
<form action="admin_compta_compte_tresorerie.php?display=<?php echo $display; ?>" method='GET'>

<span class="norme">
<?php

// -----------------------------------------//Affichage du formulaire
echo "<table border=\"0\" cellpadding=\"5\"><tr>";
echo "<td>".get_vocab("nom_compte")."</td><td><input type=\"text\" name=\"reg_compte\" size=\"25\" value=\"";
if (isset($mod_compte)) echo htmlspecialchars($mod_compte);
echo "\"></td>\n";
echo "<td>".get_vocab("solde_compte")."</td><td><input type=\"text\" name=\"reg_solde\" size=\"8\" value=\"";
if (isset($solde_compte)) echo htmlspecialchars($solde_compte);
echo "\"></td>\n";
echo "<td>compte courant<input type='radio' name='reg_courant' value='1'></td>"; 
if (getSettingValue("disable_login")=='yes') echo "checked";
echo "</table>";
echo "<input type=\"hidden\" name=\"valid\" value=\"yes\" />\n";
if (isset($_GET['action_modify'])) {
echo "<br><center><input type=\"submit\" value=\"".get_vocab("change")."\" /></center>\n";
echo "<input type=\"hidden\" name=\"action_modify\" value=\"yes\" />\n";
echo "<input type=\"hidden\" name=\"id_compte\" value=\"$id_compte\" />\n";
}else{
echo "<br><center><input type=\"submit\" value=\"".get_vocab("submit")."\" /></center>\n";
}
echo "</span></form>\n";

//-----------------------------fin du formulaire

// On liste les comptes existantes
//--------------------------------------------------------------------------------
echo "<table border=\"0\"><tr><td><p><b>Liste des comptes :</b></p></td></tr></table>";


//--------------------------------------------------------------------------------

// Affichage du tableau
echo "<table border=\"1\" cellpadding=3>";
echo "<tr><td><b>".get_vocab("nom_compte")."</b></td>";
echo "<td><b>".get_vocab("solde_compte")."</b></td>";
echo "<td><b>".get_vocab("courant_compte")."</b></td>";
echo "<td><b>".get_vocab("edit")."</b></td>";
echo "<td><b>".get_vocab("delete")."</b></td>";
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
				
		// Affichage du lien 'modifier'
		
        $themessage = get_vocab("confirm_edit");
        echo "<td><a href='admin_compta_compte_tresorerie.php?id_compte={$col[$i][0]}&amp;action_modify=yes&amp;' onclick='return confirmlink(this, \"$themessage\")'>".get_vocab("edit")."</a></td>";
				
		// Affichage du lien 'supprimer'
		
        $themessage = get_vocab("confirm_del");
        echo "<td><a href='admin_compta_compte_tresorerie.php?compte_del={$col[$i][0]}&amp;action_del=yes&amp;' onclick='return confirmlink(this, \"$themessage\")'>".get_vocab("delete")."</a></td>";
		echo "</tr>";
        }
		
    }
echo "</table>";
echo "</body></html>";
