<?php
#########################################################################
#                            admin_maj.php                              #
#                                                                       #
#            interface permettant la mise à jour de la base de données  #
#               Dernière modification : 10/07/2006                      #
#                                                                       #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
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

include "include/connect.inc.php";
include "include/config.inc.php";
include "include/misc.inc.php";
include "include/functions.inc.php";
include "include/$dbsys.inc.php";

// Settings
require_once("./include/settings.inc.php");
//Chargement des valeurs de la table settingS
if (!loadSettings())
    die("Erreur chargement settings");

// Session related functions
require_once("./include/session.inc.php");

// Paramètres langage
include "include/language.inc.php";

function traite_requete($requete="") {
    $retour="";
    $res = grr_sql_query($requete);
    $erreur_no = mysqli_errno();
    if (!$erreur_no) {
        $retour = "";
    } else {
        switch ($erreur_no) {
        case "1060":
        // le champ existe déjà : pas de problème
        $retour = "";
        break;
        case "1061":
        // La cléf existe déjà : pas de problème
        $retour = "";
        break;
        case "1062":
        // Présence d'un doublon : création de la cléf impossible
        $retour = "<font color=\"#FF0000\">Erreur (<b>non critique</b>) sur la requête : <i>".$requete."</i> (".mysqli_errno()." : ".mysqli_error().")</font><br>\n";
        break;
        case "1068":
        // Des cléfs existent déjà : pas de problème
        $retour = "";
        break;
        case "1091":
        // Déjà supprimé : pas de problème
        $retour = "";
        break;
        default:
        $retour = "<font color=\"#FF0000\">Erreur sur la requête : <i>".$requete."</i> (".mysqli_errno()." : ".mysqli_error().")</font><br>\n";
        break;
        }
    }
    return $retour;
}


$valid = isset($_POST["valid"]) ? $_POST["valid"] : 'no';
$version_old = isset($_POST["version_old"]) ? $_POST["version_old"] : '';

if (isset($_POST['submit'])) {
    if (isset($_POST['login']) && isset($_POST['password'])) {
        // Test pour tenir compte du changement de nom de la table grr_utilisateurs lors du passage à la version 1.8
        $num_version = grr_sql_query1("select NAME from ".$_COOKIE["table_prefix"]."_setting WHERE NAME='version'");
        if ($num_version != -1)
            $sql = "select upper(login) login, password, prenom, nom, statut from ".$_COOKIE["table_prefix"]."_utilisateurs where login = '" . $_POST['login'] . "' and password = md5('" . $_POST['password'] . "') and etat != 'inactif' and statut='administrateur' ";
        else
            $sql = "select upper(login) login, password, prenom, nom, statut from utilisateurs where login = '" . $_POST['login'] . "' and password = md5('" . $_POST['password'] . "') and etat != 'inactif' and statut='administrateur' ";
        $res_user = grr_sql_query($sql);
        $num_row = grr_sql_count($res_user);
        if ($num_row == 1) {
            $valid='yes';
        } else {
            $message = get_vocab("wrong_pwd");
        }
    }
}


if ((!@grr_resumeSession()) and $valid!='yes') {
    ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <HTML>
    <HEAD>
    <META HTTP-EQUIV="Content-Type" content="text/html; charset=<?php
    if ($unicode_encoding)
        echo "utf-8";
    else
        echo $charset_html;
    ?>">
    <link REL="stylesheet" href="themes/default/css/style.css" type="text/css">
    <TITLE> GRR </TITLE>
    <LINK REL="SHORTCUT ICON" href="./favicon.ico">
    </HEAD>
    <BODY>
    <form action="admin_maj.php" method='POST' style="width: 100%; margin-top: 24px; margin-bottom: 48px;">
    <div class="center">
    <H2><?php echo get_vocab("maj_bdd"); ?></H2>

    <?php
    if (isset($message)) {
        echo("<p><font color=red>" . encode_message_utf8($message) . "</font></p>");
    }
    ?>
    <fieldset style="padding-top: 8px; padding-bottom: 8px; width: 40%; margin-left: auto; margin-right: auto;">
    <legend style="font-variant: small-caps;"><?php echo get_vocab("identification"); ?></legend>
    <table style="width: 100%; border: 0;" cellpadding="5" cellspacing="0">
    <tr>
    <td style="text-align: right; width: 40%; font-variant: small-caps;"><label for="login"><?php echo get_vocab("login"); ?></label></td>
    <td style="text-align: center; width: 60%;"><input type="text" name="login" size="16"></td>
    </tr>
    <tr>
    <td style="text-align: right; width: 40%; font-variant: small-caps;"><label for="password"><?php echo get_vocab("pwd"); ?></label></td>
    <td style="text-align: center; width: 60%;"><input type="password" name="password" size="16"></td>
    </tr>
    </table>
    <input type="submit" name="submit" value="<?php echo get_vocab("submit"); ?>" style="font-variant: small-caps;">
    </fieldset>
    </div>
    </form>
    </body>
    </html>
    <?php
    die();
};

$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
if ((authGetUserLevel(getUserName(),-1) < 5) and ($valid != 'yes'))
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}
if ($valid == 'no') {
    # print the page header
    print_header("","","","",$type="with_session", $page="admin");
    // Affichage de la colonne de gauche
    include "admin_col_gauche.php";

} else {
    ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <HTML>
    <HEAD>
    <META HTTP-EQUIV="Content-Type" content="text/html; charset=<?php
    if ($unicode_encoding)
        echo "utf-8";
    else
        echo $charset_html;
    ?>">

    <link REL="stylesheet" href="style.css" type="text/css">
    <LINK REL="SHORTCUT ICON" href="favicon.ico">
    <TITLE> GRR </TITLE>
    </HEAD>
    <BODY>
    <?php
}

if (isset($_POST['maj'])) {
    // On commence la mise à jour
    $result = '';
    $result_inter = '';

    if  ($version_old < "1.4.9") {
        $result .= "<b>Mise à jour jusqu'à la version 1.4 :</b><br>";
        $result_inter .= traite_requete("ALTER TABLE mrbs_area ADD order_display TINYINT NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE mrbs_room ADD max_booking SMALLINT DEFAULT '-1' NOT NULL ;");
        $req = grr_sql_query1("SELECT VALUE FROM setting WHERE NAME='sessionMaxLength'");
        if ($req == -1) $result_inter .= traite_requete("INSERT INTO setting VALUES ('sessionMaxLength', '30');");

        $req = grr_sql_query1("SELECT VALUE FROM setting WHERE NAME='automatic_mail'");
        if ($req == -1) $result_inter .= traite_requete("INSERT INTO setting VALUES ('automatic_mail', 'yes');");

        $req = grr_sql_query1("SELECT VALUE FROM setting WHERE NAME='begin_bookings'");
        if ($req == -1) $result_inter .= traite_requete("INSERT INTO setting VALUES ('begin_bookings', '1062367200');");

        $req = grr_sql_query1("SELECT VALUE FROM setting WHERE NAME='end_bookings'");
        if ($req == -1) $result_inter .= traite_requete("INSERT INTO setting VALUES ('end_bookings', '1088546400');");

        $req = grr_sql_query1("SELECT VALUE FROM setting WHERE NAME='company'");
        if ($req == -1) $result_inter .= traite_requete("INSERT INTO setting VALUES ('company', 'Nom de l\'établissement');");

        $req = grr_sql_query1("SELECT VALUE FROM setting WHERE NAME='webmaster_name'");
        if ($req == -1) $result_inter .= traite_requete("INSERT INTO setting VALUES ('webmaster_name', 'Webmestre de GRR');");

        $req = grr_sql_query1("SELECT VALUE FROM setting WHERE NAME='webmaster_email'");
        if ($req == -1) $result_inter .= traite_requete("INSERT INTO setting VALUES ('webmaster_email', 'admin@mon.site.fr');");

        $req = grr_sql_query1("SELECT VALUE FROM setting WHERE NAME='technical_support_email'");
        if ($req == -1) $result_inter .= traite_requete("INSERT INTO setting VALUES ('technical_support_email', 'support.technique@mon.site.fr');");

        $req = grr_sql_query1("SELECT VALUE FROM setting WHERE NAME='grr_url'");
        if ($req == -1) $result_inter .= traite_requete("INSERT INTO setting VALUES ('grr_url', 'http://mon.site.fr/grr/');");

        $req = grr_sql_query1("SELECT VALUE FROM setting WHERE NAME='disable_login'");
        if ($req == -1) $result_inter .= traite_requete("INSERT INTO setting VALUES ('disable_login', 'no');");

        if ($result_inter == '') {
            $result .= "<font color=\"green\">Ok !</font><br>";
        } else {
            $result .= $result_inter;
        }
        $result_inter = '';
    }
    if ($version_old < "1.5.9") {
        $result .= "<b>Mise à jour jusqu'à la version 1.5 :</b><br>";
        // GRR1.5
        $result_inter .= traite_requete("ALTER TABLE utilisateurs ADD default_area SMALLINT NOT NULL ;");
        $result_inter .= traite_requete("ALTER TABLE utilisateurs ADD default_room SMALLINT NOT NULL ;");
        $result_inter .= traite_requete("ALTER TABLE utilisateurs ADD default_style VARCHAR( 50 ) NOT NULL ;");
        $result_inter .= traite_requete("ALTER TABLE utilisateurs ADD default_list_type VARCHAR( 50 ) NOT NULL ;");
        $result_inter .= traite_requete("ALTER TABLE utilisateurs ADD default_language VARCHAR( 3 ) NOT NULL ;");
        $req = grr_sql_query1("SELECT VALUE FROM setting WHERE NAME='title_home_page'");
        if ($req == -1) $result_inter .= traite_requete("INSERT INTO setting VALUES ('title_home_page', 'Gestion et Réservation de Ressources');");
        if ($req == -1) $result_inter .= traite_requete("INSERT INTO setting VALUES ('message_home_page', 'En raison du caractère personnel du contenu, ce site est soumis à des restrictions utilisateurs. Pour accéder aux outils de réservation, identifiez-vous :');");
        if ($result_inter == '') {
            $result .= "<font color=\"green\">Ok !</font><br>";
        } else {
            $result .= $result_inter;
        }
        $result_inter = '';
    }
    if ($version_old < "1.6.9") {
        $result .= "<b>Mise à jour jusqu'à la version 1.6 :</b><br>";
        // GRR1.6
        $req = grr_sql_query1("SELECT VALUE FROM setting WHERE NAME='default_language'");
        if ($req == -1) $result_inter .= traite_requete("INSERT INTO setting VALUES ('default_language', 'fr');");
        $result_inter .= traite_requete("ALTER TABLE mrbs_entry ADD statut_entry CHAR( 1 ) DEFAULT '-' NOT NULL ;");
        $result_inter .= traite_requete("ALTER TABLE mrbs_room ADD statut_room CHAR( 1 ) DEFAULT '1' NOT NULL ;");
        $result_inter .= traite_requete("ALTER TABLE mrbs_room ADD show_fic_room CHAR( 1 ) DEFAULT 'n' NOT NULL ;");
        $result_inter .= traite_requete("ALTER TABLE mrbs_room ADD picture_room VARCHAR( 50 ) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE mrbs_room ADD comment_room TEXT NOT NULL;");
        if ($result_inter == '') {
            $result .= "<font color=\"green\">Ok !</font><br>";
        } else {
            $result .= $result_inter;
        }
        $result_inter = '';
    }
    if ($version_old < "1.7.9") {
        $result .= "<b>Mise à jour jusqu'à la version 1.7 :</b><br>";
        // GRR1.7
        $result_inter .= traite_requete("ALTER TABLE utilisateurs ADD source VARCHAR( 10 ) NOT NULL ;");
        $result_inter .= traite_requete("ALTER TABLE j_mailuser_room CHANGE login login VARCHAR( 20 ) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE j_user_area CHANGE login login VARCHAR( 20 ) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE j_user_room CHANGE login login VARCHAR( 20 ) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE j_mailuser_room ADD PRIMARY KEY ( login , id_room ) ;");
        $result_inter .= traite_requete("ALTER TABLE j_user_area ADD PRIMARY KEY ( login , id_area ) ;");
        $result_inter .= traite_requete("ALTER TABLE j_user_room ADD PRIMARY KEY ( login , id_room ) ;");
        $result_inter .= traite_requete("ALTER TABLE log CHANGE LOGIN LOGIN VARCHAR( 20 ) NOT NULL;");
        $req = grr_sql_query1("SELECT VALUE FROM setting WHERE NAME='url_disconnect'");
        if ($req == -1) $result_inter .= traite_requete("INSERT INTO setting VALUES ('url_disconnect', '');");

        if ($result_inter == '') {
            $result .= "<font color=\"green\">Ok !</font><br>";
        } else {
            $result .= $result_inter;
        }
        $result_inter = '';
    }
    if ($version_old < "1.8.0.9") {
        $result .= "<b>Mise à jour jusqu'à la version 1.8 :</b><br>";
        // GRR1.8
        $result_inter .= traite_requete("ALTER TABLE utilisateurs CHANGE login login VARCHAR( 20 ) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE utilisateurs CHANGE nom nom VARCHAR( 30 ) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE utilisateurs CHANGE prenom prenom VARCHAR( 30 ) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE utilisateurs CHANGE password password VARCHAR( 32 ) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE utilisateurs CHANGE email email VARCHAR( 100 ) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE utilisateurs CHANGE statut statut VARCHAR( 30 ) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE utilisateurs ADD PRIMARY KEY ( login );");
        $result_inter .= traite_requete("CREATE TABLE IF NOT EXISTS ".$_COOKIE["table_prefix"]."_j_useradmin_area (login varchar(20) NOT NULL default '', id_area int(11) NOT NULL default '0', PRIMARY KEY  (login,id_area) );");
        $result_inter .= traite_requete("ALTER TABLE j_mailuser_room RENAME ".$_COOKIE["table_prefix"]."_j_mailuser_room;");
        $result_inter .= traite_requete("ALTER TABLE j_user_area RENAME ".$_COOKIE["table_prefix"]."_j_user_area;");
        $result_inter .= traite_requete("ALTER TABLE j_user_room RENAME ".$_COOKIE["table_prefix"]."_j_user_room;");
        $result_inter .= traite_requete("ALTER TABLE log RENAME ".$_COOKIE["table_prefix"]."_log;");
        $result_inter .= traite_requete("ALTER TABLE mrbs_area RENAME ".$_COOKIE["table_prefix"]."_area;");
        $result_inter .= traite_requete("ALTER TABLE mrbs_entry RENAME ".$_COOKIE["table_prefix"]."_entry;");
        $result_inter .= traite_requete("ALTER TABLE mrbs_repeat RENAME ".$_COOKIE["table_prefix"]."_repeat;");
        $result_inter .= traite_requete("ALTER TABLE mrbs_room RENAME ".$_COOKIE["table_prefix"]."_room;");
        $result_inter .= traite_requete("ALTER TABLE setting RENAME ".$_COOKIE["table_prefix"]."_setting;");
        $result_inter .= traite_requete("ALTER TABLE utilisateurs RENAME ".$_COOKIE["table_prefix"]."_utilisateurs;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_area ADD ip_adr VARCHAR(15) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_area CHANGE area_name area_name VARCHAR( 30 ) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_room CHANGE description description VARCHAR( 60 ) NOT NULL;");
        if ($result_inter == '') {
            $result .= "<font color=\"green\">Ok !</font><br>";
        } else {
            $result .= $result_inter;
        }
        $result_inter = '';

    }
    if ($version_old < "1.9.0.9") {
        $result .= "<b>Mise à jour jusqu'à la version 1.9 :</b><br>";
        // GRR1.9
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_area ADD morningstarts_area SMALLINT NOT NULL ,ADD eveningends_area SMALLINT NOT NULL , ADD resolution_area SMALLINT NOT NULL ,ADD eveningends_minutes_area SMALLINT NOT NULL ,ADD weekstarts_area SMALLINT NOT NULL ,ADD twentyfourhour_format_area SMALLINT NOT NULL ;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_area ADD calendar_default_values VARCHAR( 1 ) DEFAULT 'y' NOT NULL ;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_room ADD delais_max_resa_room SMALLINT DEFAULT '-1' NOT NULL ;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_room ADD delais_min_resa_room SMALLINT DEFAULT '0' NOT NULL ;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_room ADD order_display SMALLINT DEFAULT '0' NOT NULL ;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_room ADD allow_action_in_past VARCHAR( 1 ) DEFAULT 'n' NOT NULL ;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_j_mailuser_room CHANGE login login VARCHAR( 40 ) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_j_user_area CHANGE login login VARCHAR( 40 ) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_j_user_room CHANGE login login VARCHAR( 40 ) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_j_useradmin_area CHANGE login login VARCHAR( 40 ) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_log CHANGE LOGIN LOGIN VARCHAR( 40 ) NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_utilisateurs CHANGE login login VARCHAR( 40 ) NOT NULL;");
        if ($result_inter == '') {
            $result .= "<font color=\"green\">Ok !</font><br>";
        } else {
            $result .= $result_inter;
        }
        $result_inter = '';

    }
    if ($version_old < "1.9.1.9") {
        $result .= "<b>Mise à jour jusqu'à la version 1.9.1 :</b><br>";
        // GRR1.9.1
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_log CHANGE USER_AGENT USER_AGENT VARCHAR( 100 ) NOT NULL;");
        if ($result_inter == '') {
            $result .= "<font color=\"green\">Ok !</font><br>";
        } else {
            $result .= $result_inter;
        }
        $result_inter = '';
    }
    if ($version_old < "1.9.2.9") {
        $result .= "<b>Mise à jour jusqu'à la version 1.9.2 :</b><br>";
        // GRR1.9.2
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_area ADD enable_periods VARCHAR( 1 ) DEFAULT 'n' NOT NULL ;");
        $result_inter .= traite_requete("CREATE TABLE IF NOT EXISTS ".$_COOKIE["table_prefix"]."_area_periodes (id_area INT NOT NULL , num_periode SMALLINT NOT NULL , nom_periode VARCHAR( 100 ) NOT NULL );");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_room ADD delais_option_reservation SMALLINT DEFAULT '0' NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_entry ADD option_reservation INT DEFAULT '0' NOT NULL;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_room ADD dont_allow_modify VARCHAR( 1 ) DEFAULT 'n' NOT NULL ;");
        $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_room ADD type_affichage_reser SMALLINT DEFAULT '0' NOT NULL;");
        $result_inter .= traite_requete("CREATE TABLE IF NOT EXISTS ".$_COOKIE["table_prefix"]."_type_area (id int(11) NOT NULL auto_increment, type_name varchar(30) NOT NULL default '',order_display smallint(6) NOT NULL default '0',couleur smallint(6) NOT NULL default '0',type_letter char(2) NOT NULL default '',  PRIMARY KEY  (id));");
        $result_inter .= traite_requete("CREATE TABLE IF NOT EXISTS ".$_COOKIE["table_prefix"]."_j_type_area (id_type int(11) NOT NULL default '0', id_area int(11) NOT NULL default '0');");
        $result_inter .= traite_requete("CREATE TABLE IF NOT EXISTS ".$_COOKIE["table_prefix"]."_calendar (DAY int(11) NOT NULL default '0');");
        if ($result_inter == '') {
            $result .= "<font color=\"green\">Ok !</font><br>";
        } else {
            $result .= $result_inter;
        }
        $result_inter = '';

    }

    if ($version_old < "1.9.3.9") {
      $result .= "<b>Mise à jour jusqu'à la version 1.9.3 :</b><br>";
      // GRR1.9.3
      $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_entry ADD overload_desc text;");
      $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_repeat ADD overload_desc text;");
      $result_inter .= traite_requete("CREATE TABLE IF NOT EXISTS ".$_COOKIE["table_prefix"]."_overload (id int(11) NOT NULL auto_increment, id_area INT NOT NULL, fieldname VARCHAR(25) NOT NULL default '', fieldtype VARCHAR(25) NOT NULL default '', PRIMARY KEY  (id));");
      $result_inter .= traite_requete("ALTER TABLE ".$_COOKIE["table_prefix"]."_area ADD display_days VARCHAR( 7 ) DEFAULT 'yyyyyyy' NOT NULL;");
      $result_inter .= traite_requete("UPDATE ".$_COOKIE["table_prefix"]."_utilisateurs SET default_style='';");

      // Suppression du paramètre url_disconnect_lemon
      $req = grr_sql_query1("SELECT VALUE FROM setting WHERE NAME='url_disconnect_lemon'");
      if (($req != -1) and (($req != ""))) {
          $result_inter .= traite_requete("INSERT INTO ".$_COOKIE["table_prefix"]."_setting VALUES ('url_disconnect', '".$req."');");
          $del = traite_requete("DELETE from ".$_COOKIE["table_prefix"]."_setting where NAME='url_disconnect_lemon'");
      }
      // Mise à jour de cas_statut
      $req = grr_sql_query1("SELECT VALUE FROM setting WHERE NAME='cas_statut'");
      if ($req == "visiteur") {
          $result_inter .= traite_requete("INSERT INTO ".$_COOKIE["table_prefix"]."_setting VALUES ('sso_statut', 'cas_visiteur');");
          $del = traite_requete("DELETE from ".$_COOKIE["table_prefix"]."_setting where NAME='cas_statut'");
      }
      if ($req == "utilisateur") {
          $result_inter .= traite_requete("INSERT INTO ".$_COOKIE["table_prefix"]."_setting VALUES ('sso_statut', 'cas_utilisateur');");
          $del = traite_requete("DELETE from ".$_COOKIE["table_prefix"]."_setting where NAME='cas_statut'");
      }
      // Mise à jour de lemon_statut
      $req = grr_sql_query1("SELECT VALUE FROM setting WHERE NAME='lemon_statut'");
      if ($req == "visiteur") {
          $result_inter .= traite_requete("INSERT INTO ".$_COOKIE["table_prefix"]."_setting VALUES ('sso_statut', 'lemon_visiteur');");
          $del = traite_requete("DELETE from ".$_COOKIE["table_prefix"]."_setting where NAME='lemon_statut'");
      }
      if ($req == "utilisateur") {
          $result_inter .= traite_requete("INSERT INTO ".$_COOKIE["table_prefix"]."_setting VALUES ('sso_statut', 'lemon_utilisateur');");
          $del = traite_requete("DELETE from ".$_COOKIE["table_prefix"]."_setting where NAME='lemon_statut'");
      }



      if ($result_inter == '')
    {
      $result .= "<font color=\"green\">Ok !</font><br>";
    }
      else
    {
      $result .= $result_inter;
    }
      $result_inter = '';

    }


    // Mise à jour du numéro de version
    $req = grr_sql_query1("SELECT VALUE FROM ".$_COOKIE["table_prefix"]."_setting WHERE NAME='version'");
    if ($req == -1) {
        $result_inter .= traite_requete("INSERT INTO ".$_COOKIE["table_prefix"]."_setting VALUES ('version', '".$version_grr."');");
    } else {
        $result_inter .= traite_requete("UPDATE ".$_COOKIE["table_prefix"]."_setting SET VALUE='".$version_grr."' WHERE NAME='version';");
    }

    // Mise à jour du numéro de RC
    $req = grr_sql_command("DELETE FROM ".$_COOKIE["table_prefix"]."_setting WHERE NAME='versionRC'");
    $result_inter .= traite_requete("INSERT INTO ".$_COOKIE["table_prefix"]."_setting VALUES ('versionRC', '".$version_grr_RC."');");


    //Re-Chargement des valeurs de la table settingS
    if (!loadSettings()) {
        die("Erreur chargement settings");
    }
    echo "<script type=\"text/javascript\" language=\"javascript\">";
    echo " alert(\"".get_vocab("maj_good")."\")";
    echo "</script>";
}

// Numéro de version effective
$version_old = getSettingValue("version");
if ($version_old == "") $version_old = "1.3";
// Numéro de RC
$version_old_RC = getSettingValue("versionRC");

// Calcul du numéro de version actuel de la base qui sert aux test de comparaison et de la chaine à afficher
if ($version_old_RC == "") {
    $version_old_RC = 9;
    $display_version_old = $version_old;
} else {
    $display_version_old = $version_old."_RC".$version_old_RC;
}
$version_old .= ".".$version_old_RC;

// Calcul de la chaine à afficher
if ($version_grr_RC == "") {
    $display_version_grr = $version_grr;
} else {
    $display_version_grr = $version_grr."_RC".$version_grr_RC;
}

echo "<h2>".get_vocab('admin_maj.php')."</h2>";
echo "<hr>";
// Numéro de version
echo "<h3>".get_vocab("num_version_title")."</h3>";
echo "<p>".get_vocab("num_version").$display_version_grr;
echo "</b></p>";
echo "<p>".get_vocab("maj_go_www")."<a href=\"".$grr_devel_url."\">".get_vocab("mrbs")."</a></p>";
echo "<hr>";
// Mise à jour de la base de donnée
echo "<h3>".get_vocab("maj_bdd")."</h3>";
// Vérification du numéro de version
if (verif_version()) {
    echo "<form action=\"admin_maj.php\" method=\"post\">";
    echo "<p><font color=red><b>".get_vocab("maj_bdd_not_update");
    echo " ".get_vocab("maj_version_bdd").$display_version_old;
    echo "</b></font><br>";
    echo get_vocab("maj_do_update")."<b>".$display_version_grr."</b></p>";
    echo "<input type=submit value='".get_vocab("maj_submit_update")."'>";
    echo "<input type=hidden name='maj' value='yes'>";
    echo "<input type=hidden name='version_old' value='$version_old'>";
    echo "<input type=hidden name='valid' value='$valid'>";
    echo "</form>";
} else {
    echo "<p>".get_vocab("maj_no_update_to_do")."</p>";
    echo "<p><center><a href=\"./\">".get_vocab("welcome")."</a></center></p>";
}

echo "<hr />";
if (isset($result)) {
    echo "<center><table width=\"80%\" border=\"1\" cellpadding=\"5\" cellspacing=\"1\"><tr><td><h2 align=\"center\">".encode_message_utf8("Résultat de la mise à jour")."</H2>";
    echo encode_message_utf8($result);
    echo $result_inter;
    echo "</td></tr></table></center>";
}

// Test de cohérence des types de réservation
if ($version_grr > "1.9.1") {
    $res = grr_sql_query("select distinct type from ".$_COOKIE["table_prefix"]."_entry order by type");
    if ($res) {
        $liste = "";
        for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
            $test = grr_sql_query1("select type_letter from ".$_COOKIE["table_prefix"]."_type_area where type_letter='".$row[0]."'");
            if ($test == -1) $liste .= $row[0]." ";
        }
        if ($liste != "") {
            echo encode_message_utf8("<table border=\"1\" cellpadding=\"5\"><tr><td><p><font color=red><b>ATTENTION : votre table des types de réservation n'est pas à jour :</b></font></p>");
            echo encode_message_utf8("<p>Depuis la version 1.9.2, les types de réservation ne sont plus définis dans le fichier config.inc.php
            mais directement en ligne. Un ou plusieurs types sont actuellement utilisés dans les réservations
            mais ne figurent pas dans la tables des types. Cela risque d'engendrer des messages d'erreur. <b>Il s'agit du ou des types suivants : ".$liste."</b>");
            echo encode_message_utf8("<br><br>Vous devez donc définir dans <a href= './admin_type.php'>l'interface de gestion des types</a>, le ou les types manquants, en vous aidant éventuellement des informations figurant dans votre ancien fichier config.inc.php.</p></td></tr></table>");
        }
    }
}


// fin de l'affichage de la colonne de droite
if ($valid == 'no') echo "</td></tr></table>";
?>
</body>
</html>

