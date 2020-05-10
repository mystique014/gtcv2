<?php
#########################################################################
#                            admin_compta.php                             #
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
echo "<h2>".get_vocab('admin_compta.php')."</h2>";
echo get_vocab('admin_compta_explications');
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
$action_copy = isset($_GET["action_copy"]) ? $_GET["action_copy"] : NULL;
$default_year = isset($_GET["default_year"]) ? $_GET["default_year"] : NULL;
$year = isset($_GET["year"]) ? $_GET["year"] : NULL;
$order_by = isset($_GET["order_by"]) ? $_GET["order_by"] : NULL;
$user_date='';
$user_description='';
$user_categorie = isset($_GET["user_categorie"]) ? $_GET["user_categorie"] : NULL;
$user_montant='';
$user_mode='';
$retry='';
$msg = '';

if (isset($year)){
$default_year = $year;
} else {
$year = getSettingvalue("default_year");
}

?>
<script type="text/javascript" src="./functions.js" language="javascript"></script>
<?php

if ($valid == "yes") {
    $reg_login = isset($_GET["user_login"]) ? $_GET["user_login"] : NULL;
    $reg_date = isset($_GET["reg_date"]) ? $_GET["reg_date"] : NULL;
    $reg_description = isset($_GET["reg_description"]) ? $_GET["reg_description"] : NULL;
	$reg_categorie = isset($_GET["reg_categorie"]) ? $_GET["reg_categorie"] : NULL;
    $reg_montant = isset($_GET["reg_montant"]) ? $_GET["reg_montant"] : NULL;
    $reg_mode = isset($_GET["reg_mode"]) ? $_GET["reg_mode"] : NULL;
	
	

    if (($reg_date == '') or ($reg_description == '') or ($reg_montant == '') or ($reg_mode == '')) {
        $msg = get_vocab("please_enter_fiche");
        $retry = 'yes';
    } else {
        //
        // action s'il s'agit d'une modification de fiche
        //
        if ($action_modify =='yes') {
		$temp = $_GET['user_modify'];
		if ($retry != 'yes') {
            $sql = "UPDATE ".$_COOKIE["table_prefix"]."_compta SET login='".protect_data_sql($reg_login)."',
            statut='administrateur',
			date='".protect_data_sql($reg_date)."',
            description='".protect_data_sql($reg_description)."',
			categorie='".protect_data_sql($reg_categorie)."',
            montant='".protect_data_sql($reg_montant)."',
            mode='".protect_data_sql($reg_mode)."',
            default_year='".protect_data_sql($default_year)."'
			WHERE id='".protect_data_sql($temp)."'";
            if (grr_sql_command($sql) < 0)
                {fatal_error(0, get_vocab("message_records_error") . grr_sql_error());
            } else {
                $msg = get_vocab("msg_fiche_modified");
				
            }
        }       
            
		//
		//actions si une nouvelle fiche comptable est créée
		//
        } else if ($action_modify !='yes')  {
          $sql = "INSERT INTO ".$_COOKIE["table_prefix"]."_compta SET
                    login='".protect_data_sql($reg_login)."',
					statut='administrateur',
                    date='".protect_data_sql($reg_date)."',
                    description='".protect_data_sql($reg_description)."',
					categorie='".protect_data_sql($reg_categorie)."',
                    montant='".protect_data_sql($reg_montant)."',
                    mode='".protect_data_sql($reg_mode)."',
                    default_year='".protect_data_sql($default_year)."'";
                    if (grr_sql_command($sql) < 0)
                        {fatal_error(0, get_vocab("msg_fiche_created_error") . grr_sql_error());
                    } else {
                        $msg = get_vocab("msg_fiche_created");
                    }  
        } else {
            $msg = get_vocab("only_letters_and_numbers");
            $retry = 'yes';
        }
	}
    
    if ($retry == 'yes') {
		
		$user_date =  $reg_date;
		$user_description = $reg_description;
		$user_montant = $reg_montant;
		$user_mode = $reg_mode;
		$user_categorie = $reg_categorie;
		
	}
}

// On appelle les informations de la fiche à modifier pour les afficher :
if ($action_modify =='yes' or $action_copy =='yes') {
    $sql = "SELECT date, description, categorie, montant, mode, default_year FROM ".$_COOKIE["table_prefix"]."_compta WHERE id='$user_modify'";
    $res = grr_sql_query($sql);
    if ($res) {
        for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
        $user_date = $row[0];
        $user_description = $row[1];
		$user_categorie = $row[2];
        $user_montant = $row[3];
        $user_mode = $row[4];
        $user_default_year = $row[5];
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
		$temp = $_GET['user_del'];
        $sql = "DELETE FROM ".$_COOKIE["table_prefix"]."_compta WHERE id='$temp'";
        if (grr_sql_command($sql) < 0) {fatal_error(1, "<p>" . grr_sql_error());}  else {
           $msg=get_vocab("del_fiche");
        }
    
}

if (empty($order_by)) { $order_by = 'date'; }
?>
<p class=bold>
|<a href="admin_compta.php?year=<?php echo getSettingValue("default_year"); ?>"><?php echo get_vocab("fiche"); ?></a> |
<a href="admin_categorie_compta.php"><?php echo get_vocab("categorie"); ?></a> |
<br>
</p>
<form action="admin_compta.php?display=<?php echo $display; ?>" method='GET'>

<span class="norme">
<?php
if (isset($_SESSION['login'])){$user_login = $_SESSION['login'];};
	//recherche du nom prénom de l'utilisateur conserné
	$sql = "SELECT nom, prenom FROM ".$_COOKIE["table_prefix"]."_utilisateurs WHERE login='$user_login'";
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
echo "<td>".get_vocab("description")."</td><td><input type=\"text\" name=\"reg_description\" size=\"15\" value=\"";
if ($user_description) echo htmlspecialchars($user_description);
echo "\"></td>\n";
echo "<td>".get_vocab("montant")." ".get_vocab("deux_points")."</td><td><input type=\"text\" name=\"reg_montant\" size=\"8\" value=\"";
if ($user_montant) echo ($user_montant);
echo "\"></td>\n";
//-----------------------//liste des catégories
echo "<td>".get_vocab("nom_categorie")." ".get_vocab("deux_points")."</td>";
echo "<td><select name=\"reg_categorie\">";
$sql = "SELECT id, name FROM ".$_COOKIE["table_prefix"]."_categorie_compta ORDER BY name";
$res = grr_sql_query($sql);


if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
{
echo "<option value='".$row[0]."'>";
echo "".$row[1]."</OPTION>";
}
echo "</select>";
//------------------//fin liste catégorie
echo "</td></tr>\n";
echo "<tr><td>".get_vocab("mode")." ".get_vocab("deux_points")."</td><td><input type=\"text\" name=\"reg_mode\" size=\"10\" value=\"";
if ($user_mode) echo htmlspecialchars($user_mode);
echo "\"></td>\n";
echo "<td>Date op&eacute;ration:</td><td><input onclick=\"ds_sh(this);\" name=\"reg_date\" readonly=\"readonly\" style=\"cursor: text\" /><br />";
echo "</td>\n";
echo "<td>".get_vocab("default_year")."<font color='red'><b>".getSettingValue("default_year")."</b></font></td>\n";
echo "<td><a href=\"admin_config.php\"><img src=\"img_grr/edit_s.png\" alt=\"". get_vocab("edit") ."\" title=\"".get_vocab("edit")."\" align=\"middle\" border=\"0\" /></a></td></tr>\n";
echo "</table>";
echo "<input type=\"hidden\" name=\"valid\" value=\"yes\" />\n";
echo "<input type=\"hidden\" name=\"user_login\" value=\"".$user_login."\" />\n";
if ($action_modify == 'yes'){
echo "<input type=\"hidden\" name=\"default_year\" value=\"".$user_default_year."\" />\n";
} else {
$default_year = getSettingValue("default_year");
echo "<input type=\"hidden\" name=\"default_year\" value=\"".$default_year."\" />\n";
}
if (isset($_GET['action_modify'])) {
echo "<br><center><input type=\"submit\" value=\"".get_vocab("change")."\" /></center>\n";
echo "<input type=\"hidden\" name=\"action_modify\" value=\"yes\" />\n";
$temp = $_GET['user_modify'];
echo "<input type=\"hidden\" name=\"user_modify\" value=\"$temp\" />\n";
}else{
echo "<br><center><input type=\"submit\" value=\"".get_vocab("submit")."\" /></center>\n";
}
echo "</span></form>\n";

//-----------------------------fin du formulaire
?>
<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
<tr><td id="ds_calclass">
</td></tr>
</table>

<script type="text/javascript">
// <!-- <![CDATA[

// Project: Dynamic Date Selector (DtTvB) - 2006-03-16
// Script featured on JavaScript Kit- http://www.javascriptkit.com
// Code begin...
// Set the initial date.
var ds_i_date = new Date();
ds_c_month = ds_i_date.getMonth() + 1;
ds_c_year = ds_i_date.getFullYear();

// Get Element By Id
function ds_getel(id) {
	return document.getElementById(id);
}

// Get the left and the top of the element.
function ds_getleft(el) {
	var tmp = el.offsetLeft;
	el = el.offsetParent
	while(el) {
		tmp += el.offsetLeft;
		el = el.offsetParent;
	}
	return tmp;
}
function ds_gettop(el) {
	var tmp = el.offsetTop;
	el = el.offsetParent
	while(el) {
		tmp += el.offsetTop;
		el = el.offsetParent;
	}
	return tmp;
}

// Output Element
var ds_oe = ds_getel('ds_calclass');
// Container
var ds_ce = ds_getel('ds_conclass');

// Output Buffering
var ds_ob = ''; 
function ds_ob_clean() {
	ds_ob = '';
}
function ds_ob_flush() {
	ds_oe.innerHTML = ds_ob;
	ds_ob_clean();
}
function ds_echo(t) {
	ds_ob += t;
}

var ds_element; // Text Element...

var ds_monthnames = [
'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
]; // You can translate it for your language.

var ds_daynames = [
'Dim', 'Lun', 'Mar', 'Me', 'Jeu', 'Ven', 'Sam'
]; // You can translate it for your language.

// Calendar template
function ds_template_main_above(t) {
	return '<table cellpadding="3" cellspacing="1" class="ds_tbl">'
	     + '<tr>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_py();">&lt;&lt;</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_pm();">&lt;</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_hi();" colspan="3">[Fermer]</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_nm();">&gt;</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_ny();">&gt;&gt;</td>'
		 + '</tr>'
	     + '<tr>'
		 + '<td colspan="7" class="ds_head">' + t + '</td>'
		 + '</tr>'
		 + '<tr>';
}

function ds_template_day_row(t) {
	return '<td class="ds_subhead">' + t + '</td>';
	// Define width in CSS, XHTML 1.0 Strict doesn't have width property for it.
}

function ds_template_new_week() {
	return '</tr><tr>';
}

function ds_template_blank_cell(colspan) {
	return '<td colspan="' + colspan + '"></td>'
}

function ds_template_day(d, m, y) {
	return '<td class="ds_cell" onclick="ds_onclick(' + d + ',' + m + ',' + y + ')">' + d + '</td>';
	// Define width the day row.
}

function ds_template_main_below() {
	return '</tr>'
	     + '</table>';
}

// This one draws calendar...
function ds_draw_calendar(m, y) {
	// First clean the output buffer.
	ds_ob_clean();
	// Here we go, do the header
	ds_echo (ds_template_main_above(ds_monthnames[m - 1] + ' ' + y));
	for (i = 0; i < 7; i ++) {
		ds_echo (ds_template_day_row(ds_daynames[i]));
	}
	// Make a date object.
	var ds_dc_date = new Date();
	ds_dc_date.setMonth(m - 1);
	ds_dc_date.setFullYear(y);
	ds_dc_date.setDate(1);
	if (m == 1 || m == 3 || m == 5 || m == 7 || m == 8 || m == 10 || m == 12) {
		days = 31;
	} else if (m == 4 || m == 6 || m == 9 || m == 11) {
		days = 30;
	} else {
		days = (y % 4 == 0) ? 29 : 28;
	}
	var first_day = ds_dc_date.getDay();
	var first_loop = 1;
	// Start the first week
	ds_echo (ds_template_new_week());
	// If sunday is not the first day of the month, make a blank cell...
	if (first_day != 0) {
		ds_echo (ds_template_blank_cell(first_day));
	}
	var j = first_day;
	for (i = 0; i < days; i ++) {
		// Today is sunday, make a new week.
		// If this sunday is the first day of the month,
		// we've made a new row for you already.
		if (j == 0 && !first_loop) {
			// New week!!
			ds_echo (ds_template_new_week());
		}
		// Make a row of that day!
		ds_echo (ds_template_day(i + 1, m, y));
		// This is not first loop anymore...
		first_loop = 0;
		// What is the next day?
		j ++;
		j %= 7;
	}
	// Do the footer
	ds_echo (ds_template_main_below());
	// And let's display..
	ds_ob_flush();
	// Scroll it into view.
	ds_ce.scrollIntoView();
}

// A function to show the calendar.
// When user click on the date, it will set the content of t.
function ds_sh(t) {
	// Set the element to set...
	ds_element = t;
	// Make a new date, and set the current month and year.
	var ds_sh_date = new Date();
	ds_c_month = ds_sh_date.getMonth() + 1;
	ds_c_year = ds_sh_date.getFullYear();
	// Draw the calendar
	ds_draw_calendar(ds_c_month, ds_c_year);
	// To change the position properly, we must show it first.
	ds_ce.style.display = '';
	// Move the calendar container!
	the_left = ds_getleft(t);
	the_top = ds_gettop(t) + t.offsetHeight;
	ds_ce.style.left = the_left + 'px';
	ds_ce.style.top = the_top + 'px';
	// Scroll it into view.
	ds_ce.scrollIntoView();
}

// Hide the calendar.
function ds_hi() {
	ds_ce.style.display = 'none';
}

// Moves to the next month...
function ds_nm() {
	// Increase the current month.
	ds_c_month ++;
	// We have passed December, let's go to the next year.
	// Increase the current year, and set the current month to January.
	if (ds_c_month > 12) {
		ds_c_month = 1; 
		ds_c_year++;
	}
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Moves to the previous month...
function ds_pm() {
	ds_c_month = ds_c_month - 1; // Can't use dash-dash here, it will make the page invalid.
	// We have passed January, let's go back to the previous year.
	// Decrease the current year, and set the current month to December.
	if (ds_c_month < 1) {
		ds_c_month = 12; 
		ds_c_year = ds_c_year - 1; // Can't use dash-dash here, it will make the page invalid.
	}
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Moves to the next year...
function ds_ny() {
	// Increase the current year.
	ds_c_year++;
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Moves to the previous year...
function ds_py() {
	// Decrease the current year.
	ds_c_year = ds_c_year - 1; // Can't use dash-dash here, it will make the page invalid.
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Format the date to output.
function ds_format_date(d, m, y) {
	// 2 digits month.
	m2 = '00' + m;
	m2 = m2.substr(m2.length - 2);
	// 2 digits day.
	d2 = '00' + d;
	d2 = d2.substr(d2.length - 2);
	// YYYY-MM-DD
	return y + '-' + m2 + '-' + d2;
	//return d2 + '-' + m2 + '-' + y;
}

// When the user clicks the day.
function ds_onclick(d, m, y) {
	// Hide the calendar.
	ds_hi();
	// Set the value of it, if we can.
	if (typeof(ds_element.value) != 'undefined') {
		ds_element.value = ds_format_date(d, m, y);
	// Maybe we want to set the HTML in it.
	} else if (typeof(ds_element.innerHTML) != 'undefined') {
		ds_element.innerHTML = ds_format_date(d, m, y);
	// I don't know how should we display it, just alert it to user.
	} else {
		alert (ds_format_date(d, m, y));
	}
}

// And here is the end.

// ]]> -->
</script>

<?php
// On choisit l'année d'affichage (par défaut la valeur est l'année sportive)
//On commence par récupérer toutes les années enregistrées dans la base avec le login du joueur
//On tri et élimine les doublons pour un affichage ordonné en liste
//--------------------------------------------------------------------------------
echo "<hr><table><td><p><b>Liste des fiches pour l'ann&eacute;e :</b></p></td><td>";
$out_html = "<form name=\"year\"><select name=\"year\" onChange=\"year_go()\">";
$out_html .= "<option value=\"admin_compta.php?year=-1&amp;user_login=$user_login\">".get_vocab('select');
$sql = "SELECT DISTINCT default_year FROM ".$_COOKIE["table_prefix"]."_compta WHERE login='$user_login' ORDER BY default_year";
$res = grr_sql_query($sql);
if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
{
    $selected = ($row[0] == $year) ? "selected" : "";
    $link = "admin_compta.php?year=$row[0]&amp;user_login=$user_login";
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
// Affichage du tableau
echo "<table border=1 cellpadding=3>";
echo "<tr><td><b><a href='admin_compta.php?order_by=date DESC'>".get_vocab("datee")."</a></b></td>";
echo "<td><b><a href='admin_compta.php?order_by=mode'>".get_vocab("mode")."</a></b></td>";
echo "<td><b><a href='admin_compta.php?order_by=description'>".get_vocab("description")."</a></b></td>";
echo "<td><b><a href='admin_compta.php?order_by=categorie'>".get_vocab("nom_categorie")."</a></b></td>";
echo "<td><b><a href='admin_compta.php?order_by=montant'>".get_vocab("montant")."</a></b></td>";
echo "<td><b>".get_vocab("copy")."</b></td>";
echo "<td><b>".get_vocab("edit")."</b></td>";
echo "<td><b>".get_vocab("delete")."</b></td>";
echo "</tr>";


// On appelle les informations de l'utilisateur pour les afficher :
    $sql = "SELECT id, date, mode, description, categorie, montant, default_year, rap FROM ".$_COOKIE["table_prefix"]."_compta WHERE login='$user_login' AND default_year='$year' ORDER BY date DESC";
    $res = grr_sql_query($sql);
    if ($res) {
		$total ='';
	    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
        $user_id = $row[0];
		$user_date= $row[1];
		$user_mode = $row[2];
		$user_description = $row[3];
        $user_categorie = $row[4];
		$user_montant = $row[5];
		$user_year = $row[6];
        $user_rap = $row[7];
				
		//construction des cellules du tableau
		$col[$i][0] = $user_id;
		$col[$i][1] = $user_date;
		$col[$i][2] = $user_mode;
		$col[$i][3] = $user_description;
		//affichage du nom de la catégorie
		$sql = "SELECT name FROM ".$_COOKIE["table_prefix"]."_categorie_compta WHERE id='$user_categorie'";
		$result = grr_sql_query($sql);
		 for ($j = 0; ($row = grr_sql_row($result, $j)); $j++)
        {
        $nom_categorie = $row[0];
		}
		$col[$i][4] = $nom_categorie;
		$col[$i][5] = $user_montant;
		$total += $col[$i][5];
		$col[$i][6] = $user_year ;
		
		if ($user_rap == '1'){
		$bgcolor = '#FFCC99';
		} else {
		$bgcolor = '#E9E9E4';
		}
				
		//affichage des cellules
		echo "<tr><td bgcolor='$bgcolor'>{$col[$i][1]}</td>";
		echo "<td bgcolor='$bgcolor'>{$col[$i][2]}</td>";
		echo "<td bgcolor='$bgcolor'>{$col[$i][3]}</td>";
		echo "<td bgcolor='$bgcolor'>{$col[$i][4]}</td>";
		echo "<td bgcolor='$bgcolor' align='right'>{$col[$i][5]}</td>";
		
		// Affichage du lien 'copier'
		if ($user_rap == '1'){
		$bgcolor = '#FFCC99';
		}else {
		$bgcolor = '';
		}
		echo "<td bgcolor='$bgcolor'><a href='admin_compta.php?user_modify={$col[$i][0]}&amp;action_copy=yes&amp;user_login=$user_login&amp;year=$user_year '>".get_vocab("copy")."</a></td>";
		
		
		// Affichage du lien 'modifier'
		if ($user_rap == '1'){
		$bgcolor = '#FFCC99';
		echo "<td bgcolor='$bgcolor'></td>";
		} else {
        $themessage = get_vocab("confirm_edit");
        echo "<td><a href='admin_compta.php?user_modify={$col[$i][0]}&amp;action_modify=yes&amp;user_login=$user_login&amp;year=$user_year ' onclick='return confirmlink(this, \"$user_login\", \"$themessage\")'>".get_vocab("edit")."</a></td>";
		}
		
		// Affichage du lien 'supprimer'
		if ($user_rap == '1'){
		$bgcolor = '#FFCC99';
		echo "<td bgcolor='$bgcolor'></td>";
		} else {
        $themessage = get_vocab("confirm_del");
        echo "<td><a href='admin_compta.php?user_del={$col[$i][0]}&amp;action_del=yes&amp;user_login=$user_login&amp;year=$user_year' onclick='return confirmlink(this, \"$user_login\", \"$themessage\")'>".get_vocab("delete")."</a></td>";
		echo "</tr>";
		}
        }
		echo "<tr><td></td><td></td><td></td><td align='right'><b>".get_vocab("solde")."</b></td>";
echo "<td align='right'><b>$total</b></td></tr>";
    }
echo "</table>";
echo "</body></html>";
?>