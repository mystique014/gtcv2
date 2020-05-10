<?php
#########################################################################
#                            admin_categoriecompta.php                             #
#                                                                       #
#            interface de création de catégories comptables        #
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
echo "<h2>".get_vocab('admin_categorie_compta.php')."</h2>";
echo get_vocab('admin_categorie_compta_explications');


#If we dont know the right date then make it up
$valid = isset($_GET["valid"]) ? $_GET["valid"] : NULL;
$display = isset($_GET["display"]) ? $_GET["display"] : NULL;
$action_modify = isset($_GET["action_modify"]) ? $_GET["action_modify"] : NULL;
$msg = '';

?>
<script type="text/javascript" src="./functions.js" language="javascript"></script>
<?php

if ($valid == "yes") {
    $reg_categorie = isset($_GET["reg_categorie"]) ? $_GET["reg_categorie"] : NULL;
   
   if ($reg_categorie== '') {
        $msg = get_vocab("please_enter_fiche");
        
    } else {
        //
        // action s'il s'agit d'une modification de catégorie
        //
        if ($action_modify =='yes') {
		$temp = $_GET['id_categorie'];
		
            $sql = "UPDATE ".$_COOKIE["table_prefix"]."_categorie_compta SET name='".protect_data_sql($reg_categorie)."'
            WHERE id='".protect_data_sql($temp)."'";
            if (grr_sql_command($sql) < 0)
                {fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
            } else {
                $msg = get_vocab("msg_categorie_modified");
				
            }
      
		//
		//actionssi une nouvelle catégorie comptable est créée
		//
        } else if ($action_modify !='yes')  {
          $sql = "INSERT INTO ".$_COOKIE["table_prefix"]."_categorie_compta SET
                    name='".protect_data_sql($reg_categorie)."'";
                    if (grr_sql_command($sql) < 0)
                        {fatal_error(0, get_vocab("msg_fiche_created_error") . grr_sql_error());
                    } else {
                        $msg = get_vocab("msg_fiche_created");
                    }  
        } else {
            $msg = get_vocab("only_letters_and_numbers");
		}
	}
    
}

// On appelle les informations de la fiche à modifier pour les afficher :
if ($action_modify =='yes') {
	$temp = $_GET['id_categorie'];
    $sql = "SELECT id, name FROM ".$_COOKIE["table_prefix"]."_categorie_compta WHERE id='$temp'";
    $res = grr_sql_query($sql);
    if ($res) {
        for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
        $id_categorie = $row[0];
        $mod_categorie = $row[1];
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
		$temp = $_GET['cat_del'];
        $sql = "DELETE FROM ".$_COOKIE["table_prefix"]."_categorie_compta WHERE id='$temp'";
        if (grr_sql_command($sql) < 0) {fatal_error(1, "<p>" . grr_sql_error());}  else {
           $msg=get_vocab("del_fiche");
        }
    
}


?>
<p class=bold>
|<a href="admin_categorie_compta.php"><?php echo get_vocab("fiche"); ?></a> |
<a href="admin_compta.php?year=<?php echo getSettingValue("default_year"); ?>"><?php echo get_vocab("back"); ?></a> |
<br>
</p>
<form action="admin_categorie_compta.php?display=<?php echo $display; ?>" method='GET'>

<span class="norme">
<?php

// -----------------------------------------//Affichage du formulaire
echo "<table border=\"0\" cellpadding=\"5\"><tr>";
echo "<td>".get_vocab("nom_categorie")."</td><td><input type=\"text\" name=\"reg_categorie\" size=\"30\" value=\"";
if (isset($mod_categorie)) echo htmlspecialchars($mod_categorie);
echo "\"></td>\n";
echo "</table>";
echo "<input type=\"hidden\" name=\"valid\" value=\"yes\" />\n";
if (isset($_GET['action_modify'])) {
echo "<br><center><input type=\"submit\" value=\"".get_vocab("change")."\" /></center>\n";
echo "<input type=\"hidden\" name=\"action_modify\" value=\"yes\" />\n";
echo "<input type=\"hidden\" name=\"id_categorie\" value=\"$id_categorie\" />\n";
}else{
echo "<br><center><input type=\"submit\" value=\"".get_vocab("submit")."\" /></center>\n";
}
echo "</span></form>\n";

//-----------------------------fin du formulaire

// On liste les catégories existantes
//--------------------------------------------------------------------------------
echo "<table border=\"0\"><tr><td><p><b>Liste des cat&eacute;gories :</b></p></td></tr></table>";


//--------------------------------------------------------------------------------
$bgcolor = '#E9E9E4';
// Affichage du tableau
echo "<table border=\"1\" cellpadding=3>";
echo "<tr><td><b>".get_vocab("nom_categorie")."</b></td>";
echo "<td><b>".get_vocab("edit")."</b></td>";
echo "<td><b>".get_vocab("delete")."</b></td>";
echo "</tr>";


// On appelle les informations de l'utilisateur pour les afficher :
    $sql = "SELECT id, name FROM ".$_COOKIE["table_prefix"]."_categorie_compta ORDER BY name";
    $res = grr_sql_query($sql);
    if ($res) {
	
	    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
        $cat_id = $row[0];
		$cat_name= $row[1];
	
		
		//construction des cellules du tableau
		$col[$i][0] = $cat_id;
		$col[$i][1] = $cat_name;
			
				
		//affichage des cellules
		echo "<tr><td bgcolor='$bgcolor'>{$col[$i][1]}</td>";
				
		// Affichage du lien 'modifier'
		
        $themessage = get_vocab("confirm_edit");
        echo "<td><a href='admin_categorie_compta.php?id_categorie={$col[$i][0]}&amp;action_modify=yes&amp;' onclick='return confirmlink(this, \"$themessage\")'>".get_vocab("edit")."</a></td>";
				
		// Affichage du lien 'supprimer'
		
        $themessage = get_vocab("confirm_del");
        echo "<td><a href='admin_categorie_compta.php?cat_del={$col[$i][0]}&amp;action_del=yes&amp;' onclick='return confirmlink(this, \"$themessage\")'>".get_vocab("delete")."</a></td>";
		echo "</tr>";
        }
		
    }
echo "</table>";
echo "</body></html>";
