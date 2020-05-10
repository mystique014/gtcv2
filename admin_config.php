<?php
#########################################################################
#                            admin_config.php                           #
#                                                                       #
#        Interface permettant à l'administrateur                        #
#        la configuration de certains paramètres généraux               #
#                Dernière modification :  24/01/2010                     #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 * Modification S Duchemin
 * Affichage d'un message d'information en page d'accueil !!
 * Affichage d'une rubrique pour régler la limitation du nombre de réservation actives sur l'ensemble des installations ( maxallressources != 0)
 *
 * This file is part of GRR.
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
include "include/misc.inc.php";

$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
if(authGetUserLevel(getUserName(),-1) < 5)
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}
if (isset($_GET['url_disconnect'])) {
    if (!saveSetting("url_disconnect", $_GET['url_disconnect'])) {
        echo "Erreur lors de l'enregistrement de url_disconnect ! <br>";
    }
}

if (isset($_GET['infos'])) {
    if (!saveSetting("infos", $_GET['infos'])) {
        echo "Erreur lors de l'enregistrement de l'information ! <br>";
    }
}

if (isset($_GET['title_home_page'])) {
    if (!saveSetting("title_home_page", $_GET['title_home_page'])) {
        echo "Erreur lors de l'enregistrement de title_home_page !<br>";
        die();
    }
}
if (isset($_GET['message_home_page'])) {
    if (!saveSetting("message_home_page", $_GET['message_home_page'])) {
        echo "Erreur lors de l'enregistrement de message_home_page !<br>";
        die();
    }
}
if (isset($_GET['company'])) {
    if (!saveSetting("company", $_GET['company'])) {
        echo "Erreur lors de l'enregistrement de company !<br>";
        die();
    }
}
if (isset($_GET['webmaster_name'])) {
    if (!saveSetting("webmaster_name", $_GET['webmaster_name'])) {
        echo "Erreur lors de l'enregistrement de webmaster_name !<br>";
        die();
    }
}
if (isset($_GET['webmaster_email'])) {
    if (!saveSetting("webmaster_email", $_GET['webmaster_email'])) {
        echo "Erreur lors de l'enregistrement de webmaster_email !<br>";
        die();
    }
}
if (isset($_GET['technical_support_email'])) {
    if (!saveSetting("technical_support_email", $_GET['technical_support_email'])) {
        echo "Erreur lors de l'enregistrement de technical_support_email !<br>";
        die();
    }
}
if (isset($_GET['grr_url'])) {
    if (!saveSetting("grr_url", $_GET['grr_url'])) {
        echo "Erreur lors de l'enregistrement de grr_url !<br>";
        die();
    }
}

if (isset($_GET['disable_login'])) {
    if (!saveSetting("disable_login", $_GET['disable_login'])) {
        echo "Erreur lors de l'enregistrement de disable_login !<br>";
        die();
    }
}

// Style/thème
if (isset($_GET['default_css'])) {
    if (!saveSetting("default_css", $_GET['default_css'])) {
        echo "Erreur lors de l'enregistrement de default_css !<br>";
        die();
    }
}

// langage
if (isset($_GET['default_language'])) {
    if (!saveSetting("default_language", $_GET['default_language'])) {
        echo "Erreur lors de l'enregistrement de default_language !<br>";
        die();
    }
    unset ($_SESSION['default_language']);

}

// Type d'affichage des listes des domaines et des ressources
if (isset($_GET['area_list_format'])) {
    if (!saveSetting("area_list_format", $_GET['area_list_format'])) {
        echo "Erreur lors de l'enregistrement de area_list_format !<br>";
        die();
    }
}

// domaine par défaut
if (isset($_GET['id_area'])) {
    if (!saveSetting("default_area", $_GET['id_area'])) {
        echo "Erreur lors de l'enregistrement de default_area !<br>";
        die();
    }
}
if (isset($_GET['id_room'])) {
    if (!saveSetting("default_room", $_GET['id_room'])) {
        echo "Erreur lors de l'enregistrement de default_room !<br>";
        die();
    }
}


// Automatic mail
if (isset($_GET['automatic_mail'])) {
    if (!saveSetting("automatic_mail", $_GET['automatic_mail'])) {
        echo "Erreur lors de l'enregistrement de automatic_mail !<br>";
        die();
    }
}
// Compteur invite
if (isset($_GET['compteurinvite'])) {
    if (!(preg_match("/^[0-9]{1,}$/", $_GET['compteurinvite'])) || $_GET['compteurinvite'] < 1) {
        $_GET['compteurinvite'] = 0;
    }
    if (!saveSetting("compteurinvite", $_GET['compteurinvite'])) {
        echo "Erreur lors de l'enregistrement de compteurinvite !<br>";
    }
}

// Année sportive
if (isset($_GET['default_year'])) {
    if (!(preg_match("/^[0-9]{1,}$/", $_GET['default_year'])) || $_GET['default_year'] < 1) {
        $_GET['default_year'] = 0;
    }
    if (!saveSetting("default_year", $_GET['default_year'])) {
        echo "Erreur lors de l'enregistrement de l'année sportive!<br>";
    }
}

// Max session length
if (isset($_GET['sessionMaxLength'])) {
    if (!(preg_match ("/^[0-9]{1,}$/", $_GET['sessionMaxLength'])) || $_GET['sessionMaxLength'] < 1) {
        $_GET['sessionMaxLength'] = 30;
    }
    if (!saveSetting("sessionMaxLength", $_GET['sessionMaxLength'])) {
        echo "Erreur lors de l'enregistrement de sessionMaxLength !<br>";
    }
}

// bookingdouble
if (isset($_GET['bookingdouble'])) {
    if (!saveSetting("bookingdouble", $_GET['bookingdouble'])) {
        echo "Erreur lors de l'enregistrement de bookingdouble !<br>";
        die();
    }
}

// max_all_ressources
if (isset($_GET['maxallressources'])) {
    if (!saveSetting("maxallressources", $_GET['maxallressources'])) {
        echo "Erreur lors de l'enregistrement de maxallressources !<br>";
        die();
    }
}

// Met à jour dans la BD du champ qui détermine si la fonctionnalité "multisite" est activée ou non
if (isset($_GET['module_multisite'])) {
    if (!saveSetting("module_multisite", $_GET['module_multisite'])) {
        echo "Erreur lors de l'enregistrement de module_multisite ! <br />";
    } else {
        if ($_GET['module_multisite'] == 'Oui') {
            // On crée un site par défaut s'il n'en existe pas
            $id_site = grr_sql_query1("select min(id) from ".$_COOKIE["table_prefix"]."_site");
            if ($id_site == -1) {
              $sql="INSERT INTO ".$_COOKIE["table_prefix"]."_site
       		    SET sitecode='1', sitename='site par défaut'";
              if (grr_sql_command($sql) < 0)
                fatal_error(0,'<p>'.grr_sql_error().'</p>');
              $id_site = mysqli_insert_id();
            }
            // On affecte tous les domaines à un site.
            $sql = "select id from ".$_COOKIE["table_prefix"]."_area";
            $res = grr_sql_query($sql);
            if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
            {
              // l'area est-elle déjà affectée à un site ?
              $test_site = grr_sql_query1("select count(id_area) from ".$_COOKIE["table_prefix"]."_j_site_area where id_area='".$row[0]."'");
              if ($test_site==0) {
                  $sql="INSERT INTO ".$_COOKIE["table_prefix"]."_j_site_area SET id_site='".$id_site."', id_area='".$row[0]."'";
                  if (grr_sql_command($sql) < 0)
                    fatal_error(0,'<p>'.grr_sql_error().'</p>');
              }
            }
        }
    }
}

$demande_confirmation = 'no';
global $con;
if (isset($_GET['begin_day']) and isset($_GET['begin_month']) and isset($_GET['begin_year'])) {
    while (!checkdate($_GET['begin_month'],$_GET['begin_day'],$_GET['begin_year']))
        $_GET['begin_day']--;
    $begin_bookings = mktime(0,0,0,$_GET['begin_month'],$_GET['begin_day'],$_GET['begin_year']);
    $test_del1 = mysqli_num_rows(mysqli_query($con,"select * from ".$_COOKIE["table_prefix"]."_entry WHERE (end_time < '$begin_bookings' )"));
    $test_del2 = mysqli_num_rows(mysqli_query($con,"select * from ".$_COOKIE["table_prefix"]."_repeat WHERE (end_date < '$begin_bookings')"));
    if (($test_del1!=0) or ($test_del2!=0)) {
        $demande_confirmation = 'yes';
    } else {
        if (!saveSetting("begin_bookings", $begin_bookings))
        echo "Erreur lors de l'enregistrement de begin_bookings !<br>";
    }

}
if (isset($_GET['end_day']) and isset($_GET['end_month']) and isset($_GET['end_year'])) {
    while (!checkdate($_GET['end_month'],$_GET['end_day'],$_GET['end_year']))
        $_GET['end_day']--;
    $end_bookings = mktime(0,0,0,$_GET['end_month'],$_GET['end_day'],$_GET['end_year']);
    if ($end_bookings < $begin_bookings) $end_bookings = $begin_bookings;


    $test_del1 = mysqli_num_rows(mysqli_query($con,"select * from ".$_COOKIE["table_prefix"]."_entry WHERE (start_time > '$end_bookings' )"));
    $test_del2 = mysqli_num_rows(mysqli_query($con,"select * from ".$_COOKIE["table_prefix"]."_repeat WHERE (start_time > '$end_bookings')"));
    if (($test_del1!=0) or ($test_del2!=0)) {
        $demande_confirmation = 'yes';
    } else {
        if (!saveSetting("end_bookings", $end_bookings))
        echo "Erreur lors de l'enregistrement de end_bookings !<br>";
    }


}

if ($demande_confirmation == 'yes') {
    header("Location: ./admin_confirm_change_date_bookings.php?end_bookings=$end_bookings&begin_bookings=$begin_bookings");
    die();
}

if (!loadSettings())
    die("Erreur chargement settings");

# print the page header
print_header("","","","",$type="with_session", $page="admin");

// Affichage de la colonne de gauche
include "admin_col_gauche.php";

echo "<h2>".get_vocab('admin_config.php')."</h2>";
echo "<p>".get_vocab('mess_avertissement_config')."</p>";
//
// dans le cas de mysqli, on propose une sauvegarde de la base
//
if ($dbsys == "mysql") {;
    //
    // Saving base
    //********************************
    //
    echo "<hr>";
    echo "<h3>".get_vocab('title_backup')."</h3>";
    echo "<p>".get_vocab("explain_backup")."</p>";
    echo "<p><i>".get_vocab("warning_message_backup")."</i></p>";
    ?>
    <form action="admin_save_mysql.php" method='GET' style="width: 100%;" name="form1">
    <center><input type="submit" value=" <?php echo get_vocab("submit_backup"); ?>" style="font-variant: small-caps;"/></center>
    </form>
    <?php
}

//
// Suspendre les connexions
//*************************
//
?>
<form action="admin_config.php" name="nom_formulaire" method='GET' style="width: 100%;">
<hr>
<?php echo "<h3>".get_vocab('title_disable_login')."</h3>";
echo "<p>".get_vocab("explain_disable_login")."</p>";
?>
<input type='radio' name='disable_login' value='yes' id='label_1' <?php if (getSettingValue("disable_login")=='yes') echo "checked";?> > <label for='label_1'><?php echo get_vocab("disable_login_on");

?></label>
<br><input type='radio' name='disable_login' value='no' id='label_2' <?php if (getSettingValue("disable_login")=='no') echo "checked";?>> <label for='label_2'><?php echo get_vocab("disable_login_off"); ?></label>
<center><input type="submit" value=" <?php echo get_vocab("submit"); ?>" style="font-variant: small-caps;"/></center>
<?php
//Affichage d'un message d'information en page d'accueil !!
//***************************************************
echo "<hr>";
    echo "<h3>".get_vocab('admin_infos.php')."</h3>";
    echo "<p>".get_vocab("explain_infos")."</p>";
    $value_infos=getSettingValue("infos");
    echo "<INPUT TYPE=\"text\" name=\"infos\" size=100 value =\"$value_infos\"/>\n<br><br>";
    echo "<center><INPUT type=\"submit\" name=\"Valider\" value=\"Valider\" />\n</center>\n";
echo "<hr>";
//
// Url de déconnexion
//*******************
//
echo "<H3>".get_vocab("Url_de_deconnexion")."</H3>\n";
echo "<p>".get_vocab("Url_de_deconnexion_explain")."</p>\n";
echo "<p><i>".get_vocab("Url_de_deconnexion_explain2")."</i></p>";
echo "<br>".get_vocab("Url_de_deconnexion").get_vocab("deux_points")."\n";
$value_url=getSettingValue("url_disconnect");
echo $value_url;
//echo "<INPUT TYPE=\"text\" name=\"url_disconnect\" size=40 value =\"$value_url\"/>\n<br><br>";
//echo "<center><INPUT type=\"submit\" name=\"Valider\" value=\"Valider\" />\n</center>\n";
echo "<hr>";
//
// Config générale
//****************
//
echo "<h3>".get_vocab("miscellaneous")."</h3>";
?>
<table border='0'>

<tr><td><?php echo get_vocab("title_home_page"); ?></td>
<td><input type="text" name="title_home_page" size="40" value="<?php echo(getSettingValue("title_home_page")); ?>"></td>
</tr>
<tr><td><?php echo get_vocab("message_home_page"); ?></td>
<td><TEXTAREA NAME="message_home_page" ROWS="3" COLS="40"><?php echo(getSettingValue("message_home_page")); ?></TEXTAREA></td>

</tr>
<tr><td><?php echo get_vocab("company"); ?></td>
<td><input type="text" name="company" size="40" value="<?php echo(getSettingValue("company")); ?>"></td>
</tr>
<?php
//<tr>
//<td><?php echo get_vocab("grr_url"); ?><?php //</td>
//<td><input type="text" name="grr_url" size="40" value="<?php echo(getSettingValue("grr_url")); ?><?php //"></td>
//</tr>
?>

<tr>
<td><?php echo get_vocab("webmaster_name"); ?></td>
<td><input type="text" name="webmaster_name" size="40" value="<?php echo(getSettingValue("webmaster_name")); ?>"></td>
</tr>
<tr>
<td><?php echo get_vocab("webmaster_email"); ?></td>
<td><input type="text" name="webmaster_email" size="40" value="<?php echo(getSettingValue("webmaster_email")); ?>"></td>
</tr>
<tr>
<td><?php echo get_vocab("technical_support_email"); ?></td>
<td><input type="text" name="technical_support_email" size="40" value="<?php echo(getSettingValue("technical_support_email")); ?>"></td>
</tr>
</table>
<input type="submit" value="<?php echo get_vocab("submit"); ?>" style="font-variant: small-caps;"/>
<hr>
<?php
//
// Compteur invité
//********************
//
echo "<h3>".get_vocab("title_invite")."</h3>";
?>
<table border='0'>
<tr><td><?php echo get_vocab("compteur_invite"); ?></td><td>
<input type="text" name="compteurinvite" size="0" value="<?php echo(getSettingValue("compteurinvite")); ?>"></td>
<td><input type="submit" value="<?php echo get_vocab("submit"); ?>" style="font-variant: small-caps;"/></td>
</tr>
</table>
<?php echo get_vocab("explain_compteur_invite");
?>
<?php 
/*
//
// Module multisite
//********************
//
echo "<h3>".get_vocab("Activer_module_multisite")."</h3>\n";
?>
<table border='0'>
<tr><td><?php echo get_vocab("Activer_module_multisite").get_vocab("deux_points"); ?></td><td>
<select name='module_multisite'>
<?php 
if (getSettingValue("module_multisite") == "Oui") {
    echo "<option value=\"Oui\" selected=\"selected\">".get_vocab('YES')."</option>\n";
    echo "<option value=\"Non\">".get_vocab('NO')."</option>\n";
} else {
    echo "<option value=\"Oui\">".get_vocab('YES')."</option>\n";
    echo "<option value=\"Non\" selected=\"selected\">".get_vocab('NO')."</option>\n";
}
echo "</select>\n</td>\n</tr>\n</table>\n";
*/
?>

<?php
//
// Année sportive
//********************
//
?><hr><?php
echo "<h3>".get_vocab("default_year")."</h3>";
?>
<table border='0'>
<tr><td><?php echo get_vocab("default_year"); ?></td><td>
<input type="text" name="default_year" size="0" value="<?php echo(getSettingValue("default_year")); ?>"></td>
<td><input type="submit" value="<?php echo get_vocab("submit"); ?>" style="font-variant: small-caps;"/></td>
</tr>
</table>
<?php echo get_vocab("explain_default_year");
//
// Durée d'une session
//********************
//
?><hr><?php
echo "<h3>".get_vocab("title_session_max_length")."</h3>";
?>
<table border='0'>
<tr><td><?php echo get_vocab("session_max_length"); ?></td><td>
<input type="text" name="sessionMaxLength" size="16" value="<?php echo(getSettingValue("sessionMaxLength")); ?>"></td>
<td><input type="submit" value="<?php echo get_vocab("submit"); ?>" style="font-variant: small-caps;"/></td>
</tr>
</table>
<?php echo get_vocab("explain_session_max_length");
//
// Autorisation de réserver deux heures consécutives  pour un abonné sur un même court
//********************************************************************
//
echo "<hr><h3>".get_vocab("title_bookingdouble")."</h3>";
?>
<table border='0'>
<tr><td><?php echo get_vocab("bookingdouble"); ?></td><td>
<input type="text" name="bookingdouble" size="16" value="<?php echo(getSettingValue("bookingdouble")); ?>"></td>
<td><input type="submit" value="<?php echo get_vocab("submit"); ?>" style="font-variant: small-caps;"/></td>
</tr>
</table>
<?php echo get_vocab("explain_bookingdouble");
//
// Nombre de réservations actives par utilisateur sur l'ensemble des installations !
//********************************************************************
//
echo "<hr><h3>".get_vocab("title_booking_max_all_ressources")."</h3>";
?>
<table border='0'>
<tr><td><?php echo get_vocab("max_all_ressources"); ?></td><td>
<input type="text" name="maxallressources" size="16" value="<?php echo(getSettingValue("maxallressources")); ?>"></td>
<td><input type="submit" value="<?php echo get_vocab("submit"); ?>" style="font-variant: small-caps;"/></td>
</tr>
</table>
<?php echo get_vocab("explain_booking_max_all_ressources");
//
//
// Début et fin des réservations
//******************************
//
echo "<hr><h3>".get_vocab("title_begin_end_bookings")."</h3>";
?>
<table border='0'>
<tr><td><?php echo get_vocab("begin_bookings"); ?></td><td>
<?php
$bday = strftime("%d", getSettingValue("begin_bookings"));
$bmonth = strftime("%m", getSettingValue("begin_bookings"));
$byear = strftime("%Y", getSettingValue("begin_bookings"));
genDateSelector("begin_", $bday, $bmonth, $byear,"more_years") ?>
</td>
<td>&nbsp;</td>
</tr>
</table>
<?php echo "<i>".get_vocab("begin_bookings_explain")."</i>";

?>
<br><br>
<table border='0'>
<tr><td><?php echo get_vocab("end_bookings"); ?></td><td>
<?php
$eday = strftime("%d", getSettingValue("end_bookings"));
$emonth = strftime("%m", getSettingValue("end_bookings"));
$eyear= strftime("%Y", getSettingValue("end_bookings"));
genDateSelector("end_",$eday,$emonth,$eyear,"more_years") ?>
</td>
<td><input type="submit" value="<?php echo get_vocab("submit"); ?>" style="font-variant: small-caps;"/></td>
</tr>
</table>
<?php echo "<i>".get_vocab("end_bookings_explain")."</i>";
//
// Automatic mail
//********************************
//
?>
<hr>
<?php echo "<h3>".get_vocab('title_automatic_mail')."</h3>";
echo "<p><i>".get_vocab("warning_message_mail")."</i></p>";
echo "<p>".get_vocab("explain_automatic_mail")."</p>";
?>
<input type='radio' name='automatic_mail' value='yes' id='label_3' <?php if (getSettingValue("automatic_mail")=='yes') echo "checked";?> > <label for='label_3'><?php echo get_vocab("mail_admin_on");
if (getSettingValue("automatic_mail") == 'yes') {
    echo " - <A HREF='admin_email_manager.php'>".get_vocab('admin_email_manager.php')."</A>";
}
?></label>
<br><input type='radio' name='automatic_mail' value='no' id='label_4' <?php if (getSettingValue("automatic_mail")=='no') echo "checked";?>> <label for='label_4'><?php echo get_vocab("mail_admin_off"); ?></label>
<center><input type="submit" value=" <?php echo get_vocab("submit"); ?>" style="font-variant: small-caps;"/></center>


<?php
//
// Configuration de l'affichage par défaut
//****************************************
//
?>
<hr>
<?php echo "<h3>".get_vocab("default_parameter_values_title")."</h3>";
echo "<p>".get_vocab("explain_default_parameter")."</p>";
//
// Choix du type d'affichage
//
echo "<h4>".get_vocab("explain_area_list_format")."</h4>";
echo "<table><tr><td>".get_vocab("liste_area_list_format")."</td><td>";
echo "<input type='radio' name='area_list_format' value='list' "; if (getSettingValue("area_list_format")=='list') echo "checked"; echo ">";
echo "</td></tr>";
echo "<tr><td>".get_vocab("select_area_list_format")."</td><td>";
echo "<input type='radio' name='area_list_format' value='select' "; if (getSettingValue("area_list_format")=='select') echo "checked"; echo ">";
echo "</td></tr></table>";

//
// Choix du domaine et de la ressource
// http://www.phpinfo.net/articles/article_listes.html
//
?>

<SCRIPT  type="text/javascript" LANGUAGE="JavaScript">
function ModifierListe(code_item) {
   lg = document.nom_formulaire.id_room.length;
   // On vide la liste
   for (i = lg - 1; i >= 0; i--) {
     document.nom_formulaire.id_room.options[i] = null;
   }
   code_rub = document.nom_formulaire.id_area.selectedIndex;
   <?php

   // Génération des Items par Rubriques

   // Cas où aucun domaine n'a été précisé
   echo "  if (document.nom_formulaire.id_area.options[code_rub].value == -1) {\n";
   echo "    document.nom_formulaire.id_room.length = 1;\n";
   echo "    document.nom_formulaire.id_room.options[0].value = -1;\n";
   echo "    document.nom_formulaire.id_room.options[0].text  = \"------\";\n";
   echo "    if (code_item == -1) document.nom_formulaire.id_room.options[0].selected = true;\n";
   echo "  }\n";

   // Cas où un domaine a été précisé
   $sql = "SELECT id FROM ".$_COOKIE["table_prefix"]."_area ORDER BY  order_display, area_name";
   $resultat = grr_sql_query($sql);
   $max_lignes = 0;
   $option_max = '';

   for ($enr = 0; ($row = grr_sql_row($resultat, $enr)); $enr++) {
     $sql  = "SELECT id, room_name ";
     $sql .= "FROM ".$_COOKIE["table_prefix"]."_room ";
     $sql .= "WHERE area_id='".$row[0]."'";
     $sql .= "ORDER BY room_name";
     $resultat2 = grr_sql_query($sql);
     echo "  if (document.nom_formulaire.id_area.options[code_rub].value == ".$row[0].") {\n";
     echo "    document.nom_formulaire.id_room.length = ".(grr_sql_count($resultat2)+4).";\n";
     $cpt = 0;
     echo "    document.nom_formulaire.id_room.options[0].value = -1;\n";
     echo "    document.nom_formulaire.id_room.options[0].text  = \"".get_vocab("default_room_all")."\";\n";
     echo "    if (code_item == -1) document.nom_formulaire.id_room.options[0].selected = true;\n";
     echo "    document.nom_formulaire.id_room.options[1].value = -2;\n";
     echo "    document.nom_formulaire.id_room.options[1].text  = \"".get_vocab("default_room_week_all")."\";\n";
     echo "    if (code_item == -2) document.nom_formulaire.id_room.options[1].selected = true;\n";
     echo "    document.nom_formulaire.id_room.options[2].value = -3;\n";
     echo "    document.nom_formulaire.id_room.options[2].text  = \"".get_vocab("default_room_month_all")."\";\n";
     echo "    if (code_item == -3) document.nom_formulaire.id_room.options[2].selected = true;\n";
     echo "    document.nom_formulaire.id_room.options[3].value = -4;\n";
     echo "    document.nom_formulaire.id_room.options[3].text  = \"".get_vocab("default_room_month_all_bis")."\";\n";
     echo "    if (code_item == -4) document.nom_formulaire.id_room.options[3].selected = true;\n";
     $cpt++;
     $cpt++;
     $cpt++;
     $cpt++;
     for ($enr2 = 0; ($row2 = grr_sql_row($resultat2, $enr2)); $enr2++) {
       echo "    document.nom_formulaire.id_room.options[".$cpt."].value = ".$row2[0].";\n";
       echo "    document.nom_formulaire.id_room.options[".$cpt."].text  = \"".$row2[1]." ".get_vocab("display_week")."\";\n";
       echo "    if (code_item == ".$row2[0].") document.nom_formulaire.id_room.options[".$cpt."].selected = true;\n";
       $cpt++;
       if ($cpt > $max_lignes) $max_lignes = $cpt;
       if (strlen($row2[1]) > strlen($option_max)) $option_max = $row2[1];
     }
     echo "  }\n";
   }
   ?>
}
</SCRIPT>

<?php

// ----------------------------------------------------------------------------
// Liste domaines
// ----------------------------------------------------------------------------

$sql = "SELECT id, area_name, access FROM ".$_COOKIE["table_prefix"]."_area ORDER BY  order_display, area_name";
$resultat = grr_sql_query($sql);

//echo "<FORM METHOD=POST NAME='nom_formulaire'>";
echo "<h4>".get_vocab("explain_default_area_and_room")."</h4>";
echo "<table><tr><td>".get_vocab("default_area")."</td><td>";
echo "<SELECT NAME='id_area' onChange='ModifierListe(-1)'>\n";
echo "<OPTION VALUE='-1'>".get_vocab("choose_an_area")."</OPTION>\n";

for ($enr = 0; ($row = grr_sql_row($resultat, $enr)); $enr++) {
  echo "<OPTION VALUE='".$row[0]."'";
  if (getSettingValue("default_area") == $row[0]) echo " SELECTED";
  echo ">".htmlspecialchars($row[1]);
  if ($row[2]=='r') echo " (".get_vocab("restricted").")";
  echo "</OPTION>\n";
}
echo "</SELECT></td></tr>\n";

// ----------------------------------------------------------------------------
// Liste ressources
// ----------------------------------------------------------------------------
echo "<tr><td>".get_vocab("default_room")."</td><td>";
echo "<SELECT NAME='id_room'>\n";
for ($cpt = 0; $cpt < $max_lignes; $cpt++) {
  echo "<OPTION>".$cpt.str_replace(".", "--", $option_max)."</OPTION>\n";
}
echo "</SELECT></td></tr></table>\n";
if (getSettingValue("default_room")) {
    $id_room=getSettingValue("default_room");
} else {
    $id_room = -1;
}
echo "<SCRIPT type='text/javascript' LANGUAGE='JavaScript'>\n;ModifierListe(".$id_room.");\n</SCRIPT>\n";
// ----------------------------------------------------------------------------

//
// Choix de la feuille de style
//
echo "<h4>".get_vocab("explain_css")."</h4>";
echo "<table><tr><td>".get_vocab("choose_css")."</td><td>";
echo "<SELECT NAME='default_css'>\n";
$i=0;
while ($i < count($liste_themes)) {
   echo "<OPTION VALUE='".$liste_themes[$i]."'";
   if (getSettingValue("default_css") == $liste_themes[$i]) echo " SELECTED";
   echo " >".encode_message_utf8($liste_name_themes[$i])."</OPTION>";
   $i++;
}
echo "</SELECT></td></tr></table>\n";

//
// Choix de la langue
//
echo "<h4>".get_vocab("choose_language")."</h4>";
echo "<table><tr><td>".get_vocab("choose_css")."</td><td>";
echo "<SELECT NAME='default_language'>\n";
$i=0;
while ($i < count($liste_language)) {
   echo "<OPTION VALUE='".$liste_language[$i]."'";
   if (getSettingValue("default_language") == $liste_language[$i]) echo " SELECTED";
   echo " >".encode_message_utf8($liste_name_language[$i])."</OPTION>\n";
   $i++;
}

echo "</SELECT></td></tr></table>\n";
echo "<center><input type=\"submit\" value=\"".get_vocab("submit")."\" style=\"font-variant: small-caps;\"/></center>";
echo "</FORM>";

// fin de l'affichage de la colonne de droite
echo "</td></tr></table>";
?>
</body>
</html>