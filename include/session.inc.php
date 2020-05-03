<?php
#########################################################################
#                            session.inc.php                            #
#                                                                       #
#        Bibliothèque de fonctions gérant les sessions                  #
#                Dernière modification : 19/05/2006                     #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 * D'après http://mrbs.sourceforge.net/
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

/**
 * Open a new session
 *
 * Check the provided login and password
 * Register data from the database to the session cookie
 * Log the session
 *
 * Returns 1 if login succeeded, >= 1 otherwise
 *
 * @_login              string                  Login of the user
 * @_password           string                  Password
 *
 * @return              bool                    The session is open
 */

function grr_opensession($_login, $_password, $_user_ext_authentifie = '', $tab_login=array())
{
    // Initialisation de $auth_ldap
    $auth_ldap = 'no';
    // On traite le cas où l'utilisateur a été authentifié par le service CAS ou lemonldap
    if ($_user_ext_authentifie != '') {
        // Statut par défaut
        $_statut = "";
        $sso = getSettingValue("sso_statut");
        if ($sso == "cas_visiteur") $_statut = "visiteur";
        else if ($sso == "cas_utilisateur") $_statut = "utilisateur";
        else if ($sso == "lemon_visiteur") $_statut = "visiteur";
        else if ($sso == "lemon_utilisateur") $_statut = "utilisateur";
        else if ($sso == "lcs") {
            if ($_user_ext_authentifie == "lcs_eleve") $_statut = getSettingValue("lcs_statut_eleve");
            if ($_user_ext_authentifie == "lcs_non_eleve") $_statut = getSettingValue("lcs_statut_prof");
        }
        $sql = "select upper(login) login, password, prenom, nom, statut, now() start, default_area, default_room, default_style, default_list_type, default_language, source, group_id
        from grr_utilisateurs
        where login = '" . protect_data_sql($_login) . "' and
        password = '' and
        etat != 'inactif'";
        $res_user = grr_sql_query($sql);
        $num_row = grr_sql_count($res_user);
        if ($num_row == 1) {  // L'utilisateur est présent dans la base locale
            if ($sso == "lcs") { // Mise à jour des données
                $nom_user = $tab_login["nom"];
                $email_user = $tab_login["email"];
                $prenom_user = $tab_login["fullname"];
                // On met à jour
                $sql = "UPDATE grr_utilisateurs SET
                nom='".protect_data_sql($nom_user)."',
                prenom='".protect_data_sql($prenom_user)."',
                email='".protect_data_sql($email_user)."'
                where login='".protect_data_sql($_login)."'";
            }
            if (grr_sql_command($sql) < 0)
                {fatal_error(0, get_vocab("msg_login_created_error") . grr_sql_error());
                return "2";
                die();
            }

            // on récupère les données de l'utilisateur dans $row
            $row = grr_sql_row($res_user,0);
        } else { // L'utilisateur n'est pas présent dans la base locale
            // On teste si un utilisateur porte déjà le même login
            $test = grr_sql_query1("select login from grr_utilisateurs where login = '".protect_data_sql($_login)."'");
            if ($test != '-1') {
                // le login existe déjà : impossible d'importer le profil.
                return "3";
                die();
            } else {
                if ($sso == "lcs") {
                    $nom_user = $tab_login["nom"];
                    $email_user = $tab_login["email"];
                    $prenom_user = $tab_login["fullname"];
                } else {
                    $nom_user = $_login;
                    $email_user = "";
                    $prenom_user = "";
                }

                // On insère le nouvel utilisateur
                $sql = "INSERT INTO grr_utilisateurs SET
                nom='".protect_data_sql($nom_user)."',
                prenom='".protect_data_sql($prenom_user)."',
                login='".protect_data_sql($_login)."',
                password='',
                statut='".$_statut."',
                email='".protect_data_sql($email_user)."',
                etat='actif',
                source='ext'";
                if (grr_sql_command($sql) < 0)
                    {fatal_error(0, get_vocab("msg_login_created_error") . grr_sql_error());
                    return "2";
                    die();
                }
                // on récupère les données de l'utilisateur
                $sql = "select upper(login) login, password, prenom, nom, statut, now() start, default_area, default_room, default_style, default_list_type, default_language, source
                from grr_utilisateurs
                where login = '" . protect_data_sql($_login) . "' and
                source = 'ext' and
                etat != 'inactif'";
                $res_user = grr_sql_query($sql);
                $num_row = grr_sql_count($res_user);
                if ($num_row == 1) {
                    // on récupère les données de l'utilisateur dans $row
                    $row = grr_sql_row($res_user,0);
               } else {
                   return "2";
                   die();
               }
            }
        }
    } else {   // On traite le cas usuel (non CAS)
        $sql = "select upper(login) login, password, prenom, nom, statut, now() start, default_area, default_room, default_style, default_list_type, default_language, source, group_id
        from grr_utilisateurs
        where login = '" . protect_data_sql($_login) . "' and
        password = md5('" . $_password . "') and
        etat != 'inactif'";
        $res_user = grr_sql_query($sql);
        $num_row = grr_sql_count($res_user);
        if ($num_row != 1) {  // L'utilisateur n'est pas présent dans la base locale
            // On tente une authentification ldap
            if ((getSettingValue("ldap_statut") != '') and (@function_exists("ldap_connect")) and (@file_exists("include/config_ldap.inc.php"))) {
                if ($user_dn = grr_verif_ldap($_login, $_password)) {
                    $auth_ldap = 'yes';
                } else {
                    // Echec de l'authentification ldap
                    return "4";
                    exit();
                }
            } else {
                return "2";
                exit();
            }
        } else {
            // on récupère les données de l'utilisateur dans $row
            $row = grr_sql_row($res_user,0);
        }

    }

    // On tente d'interroger la base ldap pour obtenir des infos sur l'utilisateur
    if ($auth_ldap == 'yes') {
        // on regarde si un utilisateur ldap ayant le même login existe déjà
        $sql = "select upper(login) login, password, prenom, nom, statut, now() start, default_area, default_room, default_style, default_list_type, default_language, source
        from grr_utilisateurs
        where login = '" . protect_data_sql($_login) . "' and
        source = 'ext' and
        etat != 'inactif'";
        $res_user = grr_sql_query($sql);
        $num_row = grr_sql_count($res_user);
        if ($num_row == 1) {
            // un utilisateur ldap ayant le même login existe déjà
            // on récupère les données de l'utilisateur dans $row
            $row = grr_sql_row($res_user,0);
        } else {
             // pas d'utilisateur ldap ayant le même login dans la base GRR
             // Lire les infos sur l'utilisateur depuis LDAP
             include "config_ldap.inc.php";
             // Connexion à l'annuaire
             $ds = grr_connect_ldap($ldap_adresse,$ldap_port,$ldap_login,$ldap_pwd);
             if ($ds) {
                 $result = @ldap_read($ds, $user_dn, "objectClass=*", array("cn", "mail"));
             }
             if (!$result) {
                 return "2";
                 die();
             }
             // Recuperer les donnees de l'utilisateur
             $info = @ldap_get_entries($ds, $result);
             if (!is_array($info)) {
                 return "2";
                 die();
             }
             for ($i = 0; $i < $info["count"]; $i++) {
                 $val = $info[$i];
                 if (is_array($val)) {
                     if (isset($val['cn'][0])) $l_nom = $val['cn'][0]; else $l_nom="Nom à préciser";
                     if (isset($val['mail'][0])) $l_email = $val['mail'][0]; else $l_email='';
                 }
             }
            // Convertir depuis UTF-8 (jeu de caracteres par defaut)
            if (function_exists("utf8_decode")) {
                $l_email = utf8_decode($l_email);
                $l_nom = utf8_decode($l_nom);
            }
            // On teste si un utilisateur porte déjà le même login
            $test = grr_sql_query1("select login from grr_utilisateurs where login = '".protect_data_sql($_login)."'");
            if ($test != '-1') {
                // authentification bonne mais le login existe déjà : impossible d'importer le profil.
                return "3";
                die();
            } else {
                // On insère le nouvel utilisateur
                $sql = "INSERT INTO grr_utilisateurs SET
                nom='".protect_data_sql($l_nom)."',
                prenom='',
                login='".protect_data_sql($_login)."',
                password='',
                statut='".getSettingValue("ldap_statut")."',
                email='".protect_data_sql($l_email)."',
                etat='actif',
                source='ext'";
                if (grr_sql_command($sql) < 0)
                    {fatal_error(0, get_vocab("msg_login_created_error") . grr_sql_error());
                    return "2";
                    die();
                }

                $sql = "select upper(login) login, password, prenom, nom, statut, now() start, default_area, default_room, default_style, default_list_type, default_language, source
                from grr_utilisateurs
                where login = '" . protect_data_sql($_login) . "' and
                source = 'ext' and
                etat != 'inactif'";
                $res_user = grr_sql_query($sql);
                $num_row = grr_sql_count($res_user);
                if ($num_row == 1) {
                    // on récupère les données de l'utilisateur dans $row
                    $row = grr_sql_row($res_user,0);
               } else {
                   return "2";
                   die();
               }
            }
        }
    }

    // On teste si la connexion est active ou non
    if ((getSettingValue("disable_login")=='yes') and ($row[4] != "administrateur")) {
        return "2";
        die();
    }

    //
    // A ce stade, on dispose dans tous les cas d'un tableau $row contenant les informations nécessaires à l'établissment d'une session
    //

    // Session starts now
    session_name(SESSION_NAME);
    session_start();

    // Is this user already connected ?
    $sql = "select SESSION_ID from grr_log where SESSION_ID = '" . session_id() . "' and LOGIN = '" . protect_data_sql($_login) . "' and now() between START and END";
    $res = grr_sql_query($sql);
    $num_row = grr_sql_count($res);
    if (($num_row > 0) and isset($_SESSION['start'])) {
        $sql = "update grr_log set END = now() + interval " . getSettingValue("sessionMaxLength") . " minute where SESSION_ID = '" . session_id() . "' and START = '" . $_SESSION['start'] . "'";
    //  $sql = "update grr_log set END = now() + interval " . getSettingValue("sessionMaxLength") . " minute where SESSION_ID = '" . session_id() . "'";

        $res = grr_sql_query($sql);
        return "1";
    } else {
		session_unset();
	// session_destroy();
    }

    // reset $_SESSION
    $_SESSION = array();
    $_SESSION['login'] = $row[0];
    $_SESSION['password'] = $row[1];
    $_SESSION['prenom'] = $row[2];
    $_SESSION['nom'] = $row[3];
    $_SESSION['statut'] = $row[4];
    $_SESSION['start'] = $row[5];
    $_SESSION['maxLength'] = getSettingValue("sessionMaxLength");
    if (($row[6] !='') and ($row[6] !='0')) $_SESSION['default_area'] = $row[6]; else $_SESSION['default_area'] = getSettingValue("default_area");
    if (($row[7] !='') and ($row[7] !='0')) $_SESSION['default_room'] = $row[7]; else $_SESSION['default_room'] = getSettingValue("default_room");
    if ($row[8] !='') $_SESSION['default_style'] = $row[8]; else $_SESSION['default_style'] = getSettingValue("default_css");
    if ($row[9] !='') $_SESSION['default_list_type'] = $row[9]; else $_SESSION['default_list_type'] = getSettingValue("area_list_format");
    if ($row[10] !='') $_SESSION['default_language'] = $row[10]; else $_SESSION['default_language'] = getSettingValue("default_language");
    $_SESSION['source_login'] = $row[11];
	$_SESSION['group_id'] = $row[12];

    // It's a new connection, insert into log
    if (isset($_SERVER["HTTP_REFERER"])) $httpreferer = $_SERVER["HTTP_REFERER"]; else $httpreferer = '';
    $sql = "insert into grr_log (LOGIN, START, SESSION_ID, REMOTE_ADDR, USER_AGENT, REFERER, AUTOCLOSE, END) values (
                '" . $_SESSION['login'] . "',
                '" . $_SESSION['start'] . "',
                '" . session_id() . "',
                '" . $_SERVER['REMOTE_ADDR'] . "',
                '" . $_SERVER['HTTP_USER_AGENT'] . "',
                '" . $httpreferer . "',
                '1',
                '" . $_SESSION['start'] . "' + interval " . getSettingValue("sessionMaxLength") . " minute
            )
        ;";
    $res = grr_sql_query($sql);
	//error_log("Voici les valeurs passées : ".$sql."", 1,"steduchemin@gmail.com");
    return "1";
}

/**
 * Resume a session
 *
 * Check that all the expected data is present
 * Check login / password against database
 * Update the timeout in the grr_log table
 *
 * Returns true if session resumes, false otherwise
 *
 *
 * @return              bool                    The session resumed
 */
function grr_resumeSession()
{
    global $is_authentified_lcs;
    // Resuming session
    session_name(SESSION_NAME);
    session_start();
    // un utilisateur LCS connecté via son espace LCS est déconnecté si la session LCS est fermée
    if ((getSettingValue('sso_statut') == 'lcs') and ($is_authentified_lcs == 'no') and ($_SESSION['source_login'] == "ext")) {
        return (false);
        die();
    }

    if ((!isset($_SESSION)) or (!isset($_SESSION['login']))){
        return (false);
        die();
    }
    if ((getSettingValue("disable_login")=='yes') and ($_SESSION['statut'] != "administrateur")) {
        return (false);
        die();
    }
    // To be removed
    // Validating session data
    $sql = "select password = '" . $_SESSION['password'] . "' PASSWORD, login = '" . $_SESSION['login'] . "' LOGIN, statut = '" . $_SESSION['statut'] . "' STATUT
        from grr_utilisateurs where login = '" . $_SESSION['login'] . "'";

    $res = grr_sql_query($sql);
    $row = grr_sql_row($res, 0);
    // Checking for a timeout
    $sql2 = "select now() > END TIMEOUT from grr_log where SESSION_ID = '" . session_id() . "' and START = '" . $_SESSION['start'] . "'";
    if ($row[0] != "1" || $row[1] != "1" || $row[2] != "1") {
        return (false);
    } else if (grr_sql_query1($sql2)) { // Le temps d'inactivité est supérieur à la limite fixée.
        // cas d'une authentification LCS
        if (getSettingValue('sso_statut') == 'lcs') {
            if ($is_authentified_lcs == 'yes') // l'utilisateur est authentifié par LCS, on renouvelle la session
                {
                $sql = "update grr_log set END = now() + interval " . $_SESSION['maxLength'] . " minute where SESSION_ID = '" . session_id() . "' and START = '" . $_SESSION['start'] . "'";
                $res = grr_sql_query($sql);
                return (true);
            } else // L'utilisateur n'est plus authentifié
               return (false);
         } else  // cas général
               return (false);
    } else {
        $sql = "update grr_log set END = now() + interval " . $_SESSION['maxLength'] . " minute where SESSION_ID = '" . session_id() . "' and START = '" . $_SESSION['start'] . "'";
        $res = grr_sql_query($sql);
        return (true);
    }
}

/**
 * Close a session
 *
 * Set the closing time in the logs
 * Destroy all session data
 * @_auto               string                  Session auto-close flag
 * @return              nothing
 */
function grr_closeSession(&$_auto)
{
    settype($_auto,"integer");
    session_name(SESSION_NAME);
    session_start();
    // Sometimes 'start' may not exist, because the session was previously closed by another window
    // It's not necessary to grr_log this, then
    if (isset($_SESSION['start'])) {
            $sql = "update grr_log set AUTOCLOSE = '" . $_auto . "', END = now() where SESSION_ID = '" . session_id() . "' and START = '" . $_SESSION['start'] . "'";
        $res = grr_sql_query($sql);
    }
    session_unset();
    session_destroy();
}

function grr_verif_ldap($_login, $_password) {
    if ($_password == '') {
        return false;
        exit();
    }
    include "config_ldap.inc.php";
    $ds = grr_connect_ldap($ldap_adresse,$ldap_port,$ldap_login,$ldap_pwd);
    if ($ds) {
        // Attributs testés pour egalite avec le login
        $atts = array('uid', 'login', 'userid', 'cn', 'sn', 'samaccountname', 'userprincipalname');
        // uid, login, userid n'existent pas dans ActiveDirectory
        // samaccountname= login et userprincipalname= login@D-Admin.local sont propres à ActiveDirectory
        $login_search = preg_replace("/[^-@._[:space:][:alnum:]]/", "", $_login); // securite
        // Tenter une recherche pour essayer de retrouver le DN
        reset($atts);
        while (list(, $att) = each($atts)) {
            $filter = "($att=$login_search*)";
            $result = @ldap_search($ds, $ldap_base, $filter, array("dn"));
            $info = @ldap_get_entries($ds, $result);
            // Ne pas accepter les resultats si plus d'une entree
            // (on veut un attribut unique)
            if (is_array($info) AND $info['count'] == 1) {
               $dn = $info[0]['dn'];
                if (@ldap_bind($ds, $dn, $_password)) {
                    @ldap_unbind($ds);
                    return $dn;
                }
            }
        }
        // Si echec, essayer de deviner le DN
        reset($atts);
        while (list(, $att) = each($atts)) {
            $dn = "$att=$login_search, $ldap_base";
            if (@ldap_bind($ds, $dn, $_password)) {
                @ldap_unbind($ds);
                return $dn;
            }
        }
        return false;
    } else {
        return false;
    }
}

function grr_connect_ldap($l_adresse,$l_port,$l_login,$l_pwd) {
    $ds = @ldap_connect($l_adresse, $_port);
    if($ds) {
       // On dit qu'on utilise LDAP V3, sinon la V2 par défaut est utilisé et le bind ne passe pas.
       $norme =@ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
       // Accès non anonyme
       if ($l_login != '') {
          // On tente un bind
          $b = @ldap_bind($ds, $l_login, $l_pwd);
       } else {
          // Accès anonyme
          $b = @ldap_bind($ds);
       }
       if ($b) {
           return $ds;
       } else {
           return false;
       }
    } else {
       return false;
    }
}
?>