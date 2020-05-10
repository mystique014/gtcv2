<?php
#########################################################################
#                    admin_config_ldap.php                              #
#                                                                       #
#            interface permettant la configuration de l'accès           #
#                     à un annuaire LDAP                                #
#               Dernière modification : 10/07/2006                      #
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

// Session related functions
require_once("./include/session.inc.php");

// Paramètres langage
include "include/language.inc.php";

//Chargement des valeurs de la table settingS
if (!loadSettings())
    die("Erreur chargement settings");


$valid = isset($_POST["valid"]) ? $_POST["valid"] : 'no';
$etape = isset($_POST["etape"]) ? $_POST["etape"] : '0';
$adresse = isset($_POST["adresse"]) ? $_POST["adresse"] : NULL;
$port = isset($_POST["port"]) ? $_POST["port"] : NULL;
$login_ldap = isset($_POST["login_ldap"]) ? $_POST["login_ldap"] : NULL;
$pwd_ldap = isset($_POST["pwd_ldap"]) ? $_POST["pwd_ldap"] : NULL;
$base_ldap = isset($_POST["base_ldap"]) ? $_POST["base_ldap"] : NULL;
$base_ldap_autre = isset($_POST["base_ldap_autre"]) ? $_POST["base_ldap_autre"] : NULL;
$titre_ldap = "Configuration de l'authentification LDAP";


if (isset($_POST['reg_ldap_statut'])) {
    if ($_POST['ldap_statut'] == "no_ldap") {
        $req = grr_sql_query("delete from ".$_COOKIE["table_prefix"]."_setting where NAME = 'ldap_statut'");
        $grrSettings['ldap_statut'] = '';
    } else {
        if (!saveSetting("ldap_statut", $_POST['ldap_statut'])) {
            echo encode_message_utf8("Erreur lors de l'enregistrement de ldap_statut !<br>");
        }
        $grrSettings['ldap_statut'] = $_POST['ldap_statut'];
    }
}

//Chargement des valeurs de la table settingS
if (!loadSettings())
    die("Erreur chargement settings");

if (isset($_POST['submit'])) {
    if (isset($_POST['login']) && isset($_POST['password'])) {
        $sql = "select upper(login) login, password, prenom, nom, statut from ".$_COOKIE["table_prefix"]."_utilisateurs where login = '" . $_POST['login'] . "' and password = md5('" . $_POST['password'] . "') and etat != 'inactif' and statut='administrateur' ";
        $res_user = grr_sql_query($sql);
        $num_row = grr_sql_count($res_user);
        if ($num_row == 1) {
            $valid='yes';
        } else {
            $message = get_vocab("wrong_pwd");
        }
    }
}


if ((!grr_resumeSession()) and $valid!='yes') {
    ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <HTML>
    <HEAD>
    <link REL="stylesheet" href="style.css" type="text/css">
    <TITLE> GRR </TITLE>
    <LINK REL="SHORTCUT ICON" href="./favicon.ico">
    </HEAD>
    <BODY>
    <form action="admin_config_ldap.php" method='POST' style="width: 100%; margin-top: 24px; margin-bottom: 48px;">
    <div class="center">
    <H2>Configuration de l'accès à LDAP</H2>

    <?php
    if (isset($message)) {
        echo("<p><font color=red>" . $message . "</font></p>");
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
    <link REL="stylesheet" href="style.css" type="text/css">
    <LINK REL="SHORTCUT ICON" href="favicon.ico">
    <TITLE> GRR </TITLE>
    </HEAD>
    <BODY>
    <?php
}

if ($etape == 3) {
    echo "<h2 align=\"center\">".$titre_ldap."</h2>";
    echo "<h2 align=\"center\">".encode_message_utf8("Enregistrement de la configuration.")."</h2>";
    if (!$base_ldap) $base_ldap = $base_ldap_autre;
    $ds = @ldap_connect("$adresse", "$port");
    // On dit qu'on utilise LDAP V3, sinon la V2 par défaut est utilisé et le bind ne passe pas.
    if (!(ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3)))
         echo encode_message_utf8("Impossible d'utiliser la norme LDAP V3<br>\n");
    // Accès non anonyme
    if ($login_ldap) {
       // On tente un bind
       $b = @ldap_bind($ds, "$login_ldap", "$pwd_ldap");
    }
    // On verifie le chemin fourni
    $result = @ldap_search($ds, $base_ldap, "objectClass=*");
    if ($result) {
        $ok = 'yes';
        $nom_fic = "include/config_ldap.inc.php";
        if (@file_exists($nom_fic)) @unlink($nom_fic);
        $f = @fopen($nom_fic, "wb");
        if (!$f) {
            $ok = 'no';
        } else {
            $conn = "<"."?php\n";
            $conn .= "# Les quatre lignes suivantes sont à modifier selon votre configuration\n";
            $conn .= "# ligne suivante : l'adresse de l'annuaire LDAP.\n";
            $conn .= "# Si c'est le même que celui qui heberge les scripts, mettre \"localhost\"\n";
            $conn .= "\$ldap_adresse=\"$adresse\";\n";
            $conn .= "# ligne suivante : le port utilisé\n";
            $conn .= "\$ldap_port=\"$port\";\n";
            $conn .= "# ligne suivante : l'identifiant et le mot de passe dans le cas d'un accès non anonyme\n";
            $conn .= "\$ldap_login=\"$login_ldap\";\n";
            $conn .= "\$ldap_pwd=\"$pwd_ldap\";\n";
            $conn .= "# ligne suivante : le chemin d'accès dans l'annuaire\n";
            $conn .= "\$ldap_base=\"$base_ldap\";\n";
            $conn .= "?".">";
            @fputs($f, $conn);
            if (!@fclose($f)) $ok='no';
        }
        if ($ok == 'yes') {
            echo encode_message_utf8("<B>Les données concernant l'accès à l'annuaire LDAP sont maintenant enregistrées dans le fichier \"".$nom_fic."\".</b>");
        } else {
            echo encode_message_utf8("<P>Le fichier \"".$nom_fic."\" n'est pas accessible en écriture.</p>");
            echo encode_message_utf8("<P>Vous devez changer les droits sur ce fichier afin de donner le droit d'écriture sur ce fichier puis recharger cette page.</p>");
        }
        if ($ok == 'yes') {
            echo "<FORM action=\"admin_config_ldap.php\" method=\"post\">";
            echo "<INPUT TYPE=\"hidden\" name=\"etape\" value=\"0\" />";
            echo "<INPUT TYPE=\"hidden\" name=\"valid\" value=\"$valid\" />";
            echo "<center><INPUT type=\"submit\" name=\"Valider\" value=\"Terminer\" /></center>";
            echo "</FORM>";
        }
    } else {
        echo encode_message_utf8("<b>Problème</b> : Le chemin que vous avez choisi ne semble pas valide. Retournez à la page précédente et vérifiez les informations fournies.");
    }

} else if ($etape == 2) {
    echo "<h2 align=\"center\">".$titre_ldap."</h2>";
    echo "<h2 align=\"center\">".encode_message_utf8("Connexion à l'annuaire LDAP.")."</h2>";
    // Connexion à l'annuaire
    $ds = @ldap_connect("$adresse", "$port");
    if ($ds) {
        $connexion_ok = 'yes';
        // On dit qu'on utilise LDAP V3, sinon la V2 par défaut est utilisé et le bind ne passe pas.
        if (!(ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3)))
             echo encode_message_utf8("Impossible d'utiliser la norme LDAP V3<br>\n");
        // Accès non anonyme
        if ($login_ldap) {
            // On tente un bind
            $b = @ldap_bind($ds, $login_ldap, $pwd_ldap);
            if ($b) {
                $connexion_ok = 'yes';
            } else {
                $connexion_ok = 'no';
            }
        }
        else {
        // Accès anonyme
            $b = @ldap_bind($ds);
            if ($b) {
                $connexion_ok = 'yes';
            } else {
                $connexion_ok = 'no';
            }
        }
    } else {
        $connexion_ok = 'no';
    }
    if ($connexion_ok == 'yes') {
        echo encode_message_utf8("<b>La connexion LDAP a réussi.</b>");
        echo "<FORM action=\"admin_config_ldap.php\" method=\"post\">";
        // On lit toutes les infos (objectclass=*) dans le dossier
        // Retourne un identifiant de résultat ($result), ou bien FALSE en cas d'erreur.
        $result = @ldap_read($ds, "", "objectclass=*", array("namingContexts"));
        $info = @ldap_get_entries($ds, $result);
        // Retourne un tableau associatif multi-dimensionnel ou FALSE en cas d'erreur. :
        // $info["count"] = nombre d'entrées dans le résultat
        // $info[0] : sous-tableau renfermant les infos de la première entrée
        // $info[n]["dn"] : dn de la n-ième entrée du résultat
        // $info[n]["count"] : nombre d'attributs de la n-ième entrée
        // $info[n][m] : m-ième attribut de la n-ième entrée
        // info[n]["attribut"]["count"] : nombre de valeur de cet attribut pour la n-ième entrée
        // $info[n]["attribut"][m] : m-ième valeur de l'attribut pour la n-ième entrée

        $checked = false;
        if (is_array($info) AND $info["count"] > 0) {
            echo encode_message_utf8("<P>Sélectionnez ci-dessous le chemin d'accès dans l'annuaire :</p>");
            echo "<UL>";
            $n = 0;
            for ($i = 0; $i < $info["count"]; $i++) {
                $names = $info[$i]["namingcontexts"];
                if (is_array($names)) {
                    for ($j = 0; $j < $names["count"]; $j++) {
                        $n++;
                        echo "<INPUT NAME=\"base_ldap\" VALUE=\"".htmlspecialchars($names[$j])."\" TYPE='Radio' id='tab$n'";
                        if (!$checked) {
                            echo " CHECKED";
                            $checked = true;
                        }
                        echo ">";
                        echo "<label for='tab$n'>".htmlspecialchars($names[$j])."</label><BR>\n";
                    }
                }
            }
            echo "</UL>Ou bien ";
          }
        echo "<INPUT NAME=\"base_ldap\" VALUE=\"\" TYPE='Radio' id=\"autre\"";
        if (!$checked) {
            echo " CHECKED";
            $checked = true;
        }
        echo ">";
        echo "<label for=\"autre\">".encode_message_utf8("Précisez le chemin : ")."</label> ";
        echo "<INPUT TYPE=\"text\" name=\"base_ldap_autre\" value=\"\" size=\"40\" />";

        echo "<INPUT TYPE=\"hidden\" name=\"etape\" value=\"3\" />";
        echo "<INPUT TYPE=\"hidden\" name=\"adresse\" value=\"$adresse\" />";
        echo "<INPUT TYPE=\"hidden\" name=\"port\" value=\"$port\" />";
        echo "<INPUT TYPE=\"hidden\" name=\"login_ldap\" value=\"$login_ldap\" />";
        echo "<INPUT TYPE=\"hidden\" name=\"pwd_ldap\" value=\"$pwd_ldap\" />";
        echo "<INPUT TYPE=\"hidden\" name=\"valid\" value=\"$valid\" />";
        echo "<center><INPUT type=\"submit\" name=\"Valider\" value=\"Suivant\" /></center>";
        echo "</FORM>";
    } else {
        echo encode_message_utf8("<B>La connexion au serveur LDAP a échoué.</B><br>");
        echo encode_message_utf8("Revenez à la page précédente et vérifiez les informations fournies.");
    }
} else if ($etape == 1) {
    echo "<h2 align=\"center\">".$titre_ldap."</h2>";
    echo "<h2 align=\"center\">".encode_message_utf8("Informations de connexion à l'annuaire LDAP.")."</h2>";
    echo "<form action=\"admin_config_ldap.php\" method=\"post\">";

    $adresse = 'localhost';
    $port = 389;
    echo "<INPUT TYPE=\"hidden\" NAME=\"etape\" VALUE=\"2\">";
    echo "<INPUT TYPE=\"hidden\" name=\"valid\" value=\"$valid\" />";
    echo encode_message_utf8("<H3>Adresse de l'annuaire</H3>
    Laissez «localhost» si l'annuaire est installé sur la même machine que GRR. Sinon, indiquez l'adresse du serveur.<br>");
    echo "<INPUT TYPE=\"text\" NAME=\"adresse\" VALUE=\"".$adresse."\" SIZE=\"20\">";
    echo encode_message_utf8("<H3>Numéro de port de l'annuaire</H3>
    Dans le doute, laissez la valeur par défaut : 389<br>");
    echo "<INPUT TYPE='text' NAME='port' VALUE=\"$port\" SIZE=\"20\">";

    echo encode_message_utf8("<h3>Type d'accès</H3>Si le serveur LDAP n'accepte pas d'accès anonyme,
    veuillez préciser un identifiant (par exemple « cn=jean, o=lycée, c=fr »).
    Dans le doute, laissez les champs suivants vides pour un accès anonyme.<br><b>Identifiant :</b><br>");
    echo "<INPUT TYPE=\"text\" NAME=\"login_ldap\" VALUE=\"\" SIZE=\"40\"><br>";

    echo "<b>Mot de passe :</b><br>";
    echo "<INPUT TYPE=\"password\" NAME=\"pwd_ldap\" VALUE=\"\" SIZE=\"40\"><br>";

    echo "<center><input type=submit value=\"Suivant\"></center>";
    echo "</form>";

} else if ($etape == 0) {
    if (!(function_exists("ldap_connect"))) {
        echo "<h2 align=\"center\">".$titre_ldap."</h2>";
        echo encode_message_utf8("<p align=\"center\"><b>Attention </b> : les fonctions liées à l'authentification <b>LDAP</b> ne sont pas activées sur votre serveur PHP.
        <br>La configuration LDAP est donc actuellement impossible.</body></html></p>");
        die();
    }
    echo "<h2 align=\"center\">".$titre_ldap."</h2>";
    echo encode_message_utf8("Si vous avez accès à un annuaire <b>LDAP</b>, vous pouvez configurer GRR afin que cet annuaire soit utilisé pour importer automatiquement des utilisateurs.");
    echo "<FORM action=\"admin_config_ldap.php\" method=\"post\">";
    echo "<INPUT TYPE=\"hidden\" name=\"etape\" value=\"0\" />";
    echo "<INPUT TYPE=\"hidden\" name=\"valid\" value=\"$valid\" />";
    echo "<INPUT TYPE=\"hidden\" name=\"reg_ldap_statut\" value=\"yes\" />";
    if (getSettingValue("ldap_statut") != '') {
        echo encode_message_utf8("<H3>L'authentification LDAP est activée.</H3>");
        echo encode_message_utf8("<H3>Statut par défaut des utilisateurs importés</H3>");
        echo encode_message_utf8("Choisissez le statut qui sera attribué aux personnes présentes
        dans l'annuaire LDAP lorsqu'elles se connectent pour la première fois.
        Vous pourrez par la suite modifier cette valeur pour chaque utilisateur.<BR>");
        echo "<INPUT TYPE=\"Radio\" name=\"ldap_statut\" value=\"visiteur\" ";
        if (getSettingValue("ldap_statut")=='visiteur') echo " checked ";
        echo "/>Visiteur<br>";
        echo "<INPUT TYPE=\"Radio\" name=\"ldap_statut\" value=\"utilisateur\" ";
        if (getSettingValue("ldap_statut")=='utilisateur') echo " checked ";
        echo "/>Usager<br>";
        echo "Ou bien <br>";
        echo "<INPUT TYPE=\"Radio\" name=\"ldap_statut\" value=\"no_ldap\"/>".encode_message_utf8("Désactiver l'authentification LDAP")."<br>";
        echo "<center><INPUT type=\"submit\" name=\"Valider\" value=\"Valider\" /></center>";
        echo "</FORM>";
    } else {
        echo encode_message_utf8("<H3>L'authentification LDAP n'est pas activée.</H3>");
        echo encode_message_utf8("<b>L'authentification LDAP est donc pour le moment impossible</b>. Activez l'authentification LDAP en choisissant le statut qui sera attribué aux personnes présentes
        dans l'annuaire LDAP lorsqu'elles se connectent pour la première fois.
        Vous pourrez par la suite modifier cette valeur pour chaque utilisateur.<BR>");
        echo "<INPUT TYPE=\"Radio\" name=\"ldap_statut\" value=\"visiteur\" />Visiteur<br>";
        echo "<INPUT TYPE=\"Radio\" name=\"ldap_statut\" value=\"utilisateur\" />Usager<br>";
        echo "<INPUT TYPE=\"Radio\" name=\"ldap_statut\" value=\"no_ldap\" checked />Ne pas activer<br>";

        echo "<center><INPUT type=\"submit\" name=\"Valider\" value=\"Valider\"  /></center>";
        echo "</FORM>";
    }
    echo "<hr>";

    if (@file_exists("include/config_ldap.inc.php")) {
        include("include/config_ldap.inc.php");
        echo encode_message_utf8("<H3>Configuration actuelle</H3> (Informations contenues dans le fichier \"config_ldap.inc.php\") :<br><ul>");
        echo encode_message_utf8("<li>Adresse de l'annuaire LDAP <b>: ".$ldap_adresse."</b></li>");
        echo encode_message_utf8("<li>Port utilisé : <b>".$ldap_port."</b></li>");
        echo encode_message_utf8("<li>Chemin d'accès dans l'annuaire : <b>".$ldap_base."</b></li>");
        if ($ldap_login) {
            echo encode_message_utf8("<li>Compte pour l'accès : <br>");
            echo "Identifiant : <b>".$ldap_login."</b><br>";
            echo "Mot de passe : <b>".$ldap_pwd."</b></li>";
        } else {
            echo encode_message_utf8("<li>Accès anonyme.</li>");
        }
        echo encode_message_utf8("</ul>Vous pouvez procéder à une nouvelle configuration LDAP.<br><b>Attention</b> : les données actuelles seront effacées.<br>");
    } else {
        echo encode_message_utf8("<H3>L'accès à l'annuaire LDAP n'est pas configuré.</H3><b>L'authentification LDAP est donc pour le moment impossible.</b>");
    }
    echo "<form action=\"admin_config_ldap.php\" method=\"post\">";
    echo "<INPUT TYPE=\"hidden\" NAME=\"etape\" VALUE=\"1\">";
    echo "<INPUT TYPE=\"hidden\" name=\"valid\" value=\"$valid\" />";
    echo "<center><input type=submit value=\"Configurer LDAP\"></center>";
    if (@file_exists("include/config_ldap.inc.php")) {
        if (($ldap_adresse != '') and ($ldap_port != '') and ($ldap_base != '')) {
            $ok = "<b><font color=\"green\">OK</font></b>";
            $failed = "<b><font color=\"red\">Echec</font></b>";
            echo "<hr>";
            echo "<H3>Test de connexion</H3>";
            $ds = @ldap_connect($ldap_adresse, $ldap_port);
            // On dit qu'on utilise LDAP V3, sinon la V2 par défaut est utilisé et le bind ne passe pas.
            if (ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3)) {
                echo encode_message_utf8("Norme LDAP utilisée : LDAP v3");
            } else {
                echo encode_message_utf8("Norme LDAP : impossible d'utiliser LDAP V3");
            }
            echo "<br>";
            // Accès non anonyme
            if ($ldap_login) {
                // On tente un bind
                $b = @ldap_bind($ds, $ldap_login, $ldap_pwd);
                if ($b) {
                    echo encode_message_utf8("Connexion à la base - utilisateur <b>".$ldap_login."</b> : ".$ok);
                } else {
                    echo encode_message_utf8("Connexion à la base - utilisateur <b>".$ldap_login."</b> : ".$failed);
                }
            } else {
                // Accès anonyme
                $b = @ldap_bind($ds);
                if ($b) {
                    echo encode_message_utf8("Connexion à la base - accès anonyme : ".$ok);
                } else {
                    echo encode_message_utf8("Connexion à la base - accès anonyme : ".$failed);
                }
            }
        }
    }
}





// fin de l'affichage de la colonne de droite
if ($valid == 'no') echo "</td></tr></table>";

?>
</body>
</html>



