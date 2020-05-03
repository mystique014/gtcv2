<?php
#########################################################################
#                            admin_compta_bilan.php                             #
#                                                                       #
#            interface de gestion  comptable du club          #
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
echo "<h2>".get_vocab('admin_compta_bilan.php')."</h2>";
echo get_vocab('admin_compta_bilan_explications');
?>
<head><style type="text/css">

.ds_box {
	background-color: #FFF;
	border: 1px solid #000;
	position: absolute;
	z-index: 32767;
}

.ds_tbl {
	background-color: #FFF;
}

.ds_head {
	background-color: #333;
	color: #FFF;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight: bold;
	text-align: center;
	letter-spacing: 2px;
}

.ds_subhead {
	background-color: #CCC;
	color: #000;
	font-size: 12px;
	font-weight: bold;
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	width: 32px;
}

.ds_cell {
	background-color: #EEE;
	color: #000;
	font-size: 13px;
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	padding: 5px;
	cursor: pointer;
}

.ds_cell:hover {
	background-color: #F3F3F3;
} /* This hover code won't work for IE */

</style></head>
<?php


#If we dont know the right date then make it up
$user_login = isset($_GET["user_login"]) ? $_GET["user_login"] : NULL;
$valid = isset($_GET["valid"]) ? $_GET["valid"] : NULL;
$display = isset($_GET["display"]) ? $_GET["display"] : NULL;
$user_modify = isset($_GET["user_modify"]) ? $_GET["user_modify"] : NULL;
$action_modify = isset($_GET["action_modify"]) ? $_GET["action_modify"] : NULL;
$default_year = isset($_GET["default_year"]) ? $_GET["default_year"] : NULL;
$user_date='';
$user_description='';
$user_categorie = isset($_GET["user_categorie"]) ? $_GET["user_categorie"] : NULL;
$cloture = isset($_GET["cloture"]) ? $_GET["cloture"] : NULL;
$action_purge = isset($_GET["action_purge"]) ? $_GET["action_purge"] : NULL;;
$msg = '';
$name='';
$solde='';
if (isset($year)){
$year = isset($_GET["year"]) ? $_GET["year"] : NULL;
$default_year = $year;
} else {
$year = getSettingvalue("default_year");
}

?>
<script type="text/javascript" src="./functions.js" language="javascript"></script>
<?php
//Cloture du bilan
if ($cloture == "yes") {
    $reg_solde = isset($_GET["solde"]) ? $_GET["solde"] : NULL;
	
	// Mise à jour du solde compte courant par le solde de l'exercice
	$sql = "UPDATE grr_compte_tresorerie SET solde='".protect_data_sql($reg_solde)."'
			WHERE courant ='1'";
            if (grr_sql_command($sql) < 0)
                {fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
            } else {
                $msg = get_vocab("msg_compte_modified");
			}
	if (isset($_GET['default_year'])) {
    if (!(preg_match("/^[0-9]{1,}$/", $_GET['default_year'])) || $_GET['default_year'] < 1) {
        $_GET['default_year'] = 0;
    }
    if (!saveSetting("default_year", $_GET['default_year'])) {
        echo "Erreur lors de l'enregistrement de l'année sportive!<br>";
    }
	}
}

//Purge de la base compta des années antérieures à l'année N-5
if ($action_purge =="yes")  {
	$year = getSettingvalue("default_year");
	$year_purge = $year - 5;
	//echo $year_purge;
		$sql = "DELETE FROM grr_compta WHERE default_year <= $year_purge";
        if (grr_sql_command($sql) < 0) {fatal_error(1, "<p>" . grr_sql_error());}  else {
           $msg=get_vocab("purge_ef");
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
<p class=bold><hr>
<?php echo "<a href='admin_compta_export.php'\">".get_vocab("export")."</a>"; ?>|
<?php echo "<a href='admin_compta_bilan.php?action_purge=yes' onclick='return confirmlink(this, \"ATTENTION, vous allez purger les ann&eacute;es ant&eacute;rieures &agrave; N-5\")'>".get_vocab("purge")."</a>"; ?>|
<br>
</p>
<span class="norme">
<?php

// On choisit l'année d'affichage (par défaut la valeur est l'année sportive)
//On commence par récupérer toutes les années enregistrées dans la base avec le login du joueur
//On tri et élimine les doublons pour un affichage ordonné en liste
//--------------------------------------------------------------------------------
echo "<table><td><p><b>Bilan comptable pour l'ann&eacute;e :</b></p></td><td>";
$out_html = "<form name=\"year\"><select name=\"year\" onChange=\"year_go()\">";
$out_html .= "<option value=\"admin_compta_bilan.php?year=-1&amp;user_login=$user_login\">".get_vocab('select');
$sql = "SELECT DISTINCT default_year FROM grr_compta ORDER BY default_year";
$res = grr_sql_query($sql);
if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
{
    $selected = ($row[0] == $year) ? "selected" : "";
    $link = "admin_compta_bilan.php?year=$row[0]";
    $out_html .= "<option $selected value=\"$link\">" . htmlspecialchars($row[0]);
}
$out_html .= "</select>
<SCRIPT type=\"text/javascript\" language=\"JavaScript\">
<!--
function year_go()
{
box = document.forms[\"year\"].year;
destination = box.options[box.selectedIndex].value;
if (destination) location.href = destination;
}
// -->
</SCRIPT>
<noscript>
<input type=submit value=\"Change\">
</noscript>
</form>";
echo $out_html."</td></table>";
//--------------------------------------------------------------------------------
$bgcolor = '#E9E9E4';
// Affichage du tableau
echo "<table border=1 cellpadding=3>";
echo "<td  align='center'><b>".get_vocab("nom_categorie")."</b></td>";
echo "<td align='center'><b>".get_vocab("bilanop")."</b></td>";
//echo "<td align='center'><b>".get_vocab("dateactuelle")."</b></td>";
echo "</tr>";

//On recherche le nombre de categorie présente dans la comptabilité
	$sql = "SELECT DISTINCT categorie FROM grr_compta WHERE default_year='$year' ORDER BY categorie";
	$res = grr_sql_query($sql);
	$total ='';
	$totaldate ='';
	for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
		 {
		 $categorie = $row[0];
		 //bilan total
		 $sql = "SELECT SUM(montant) AS total FROM grr_compta WHERE default_year='$year' AND categorie='$categorie'";
		 $res1 = grr_sql_query($sql);
		 for ($j = 0; ($row1 = grr_sql_row($res1, $j)); $j++)
		 {
		 $montant = $row1[0];
		 }
		 //bilan à ce jour
		 $sql = "SELECT SUM(montant) AS total FROM grr_compta WHERE default_year='$year' AND categorie='$categorie' AND date <= current_date";
		 $res1 = grr_sql_query($sql);
		 for ($j = 0; ($row1 = grr_sql_row($res1, $j)); $j++)
		 {
		 $montantdate = $row1[0];
		 }
		//construction des cellules du tableau
		//affichage du nom de la catégorie
		$sql = "SELECT name FROM grr_categorie_compta WHERE id='$categorie'";
		$result = grr_sql_query($sql);
		 for ($k = 0; ($row = grr_sql_row($result, $k)); $k++)
        {
        $nom_categorie = $row[0];
		}
		$total += $montant;
		$totaldate += $montantdate;
		$montant = round($montant,2);
		$montantdate = round($montantdate,2);
		$col[$i][0] = $nom_categorie;		
		$col[$i][1] = $montant;
		//$col[$i][2] = $montantdate;
		
		
		//affichage des cellules
		echo "<tr><td bgcolor='$bgcolor'>{$col[$i][0]}</td>";
		echo "<td align= 'right' bgcolor='$bgcolor'>{$col[$i][1]}</td>";
		//echo "<td align= 'right' bgcolor='$bgcolor'>{$col[$i][2]}</td>";
		echo "</tr>";
		}
		$total = round($total,2);
		//$totaldate = round($totaldate,2);
	echo "<tr><td align='right'><b>".get_vocab("solde")."</b></td><td align= 'right'>$total</td>";
	//echo "<td align= 'right'>$totaldate</td>";

if ($year == getSettingvalue("default_year"))
{
// Affichage du montant compte courant et du solde
echo "<tr><td align='center'><b>".get_vocab("courant_compte")."</b></td>";
echo "<td align='center'><b>".get_vocab("montant")."</b></td>";
//echo "<td align='center'><b>".get_vocab("montant")."</b></td>";
echo "</tr>";

//On recherche le solde du compte de trésorerie présent dans la comptabilité
	$sql = "SELECT name, solde FROM grr_compte_tresorerie WHERE courant='1'";
	$res = grr_sql_query($sql);
	for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
		 {
		 $name = $row[0];
		 $solde = $row[1];
		 $total += $solde;
		 $totaldate += $solde;
		 }
//On recherche le solde du compte livret présent dans la comptabilité
	$sql = "SELECT name, solde FROM grr_compte_tresorerie WHERE courant='0'";
	$res = grr_sql_query($sql);
	for ($j = 0 ; ($row = grr_sql_row($res, $j)); $j++)
		 {
		 $name_livret = $row[0];
		 $solde_livret = $row[1];
		 }		 
		 
		//construction des cellules du tableau
		$total = round($total,2);
		$totaldate = round($totaldate,2);
		$solde = round($solde,2);
		$col[$i][0] = $name;
		$col[$i][1] = $solde;
		$col[$i][2] = $solde;
		
			//affichage des cellules
		echo "<tr><td align='center' bgcolor='$bgcolor'>{$col[$i][0]}</td>";
		echo "<td align= 'right' bgcolor='$bgcolor'>{$col[$i][1]}</td>";
		echo "</tr>";
		echo "<tr><td align='right'><b>".get_vocab("solde")."  exercice</b></td><td align= 'right'>$total</td></tr>";
		
		
		if (isset($solde_livret))
		{
		$solde_livret = round($solde_livret,2);
		$colliv[$j][0] = $name_livret;
		$colliv[$j][1] = $solde_livret;
		
		echo "<tr><td align='center' bgcolor='$bgcolor'>{$colliv[$j][0]}</td>";
		echo "<td align= 'right' bgcolor='$bgcolor'>{$colliv[$j][1]}</td>";
		echo "</tr>";
		}
echo "</table>";

?>
<form action="admin_compta_bilan.php" method='GET'>

<span class="norme">

<?php
echo "<table border=1 cellpadding=3>";
echo "<td><b>Cloture du bilan et changement d'exercice.</b>";
echo "<input type=\"hidden\" name=\"solde\" value='$total'>\n";
$def_year = getSettingvalue("default_year");
$def_year++;
echo "<input type=\"hidden\" name=\"default_year\" value='$def_year'>\n";
echo "<input type=\"hidden\" name=\"cloture\" value=\"yes\">\n";
echo "<br><center><input type=\"submit\" value=\"".get_vocab("submit")."\" title=\"Vous allez attribuer le solde exercice au compte courant et changer l'ann&eacute;e sportive !\" /></center></td>\n";
echo "</table>";
} else {
	echo "</table>";
}	
echo "</body></html>";