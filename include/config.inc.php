<?php
###########################################################################
#   Fichier de configuration de GRR
#   Configurez ce fichier pour votre site
###########################################################################

##############
# Type d'accès
##############
# $authentification_obli = 1 : il est obligatoire de se connecter pour accéder au site.
# $authentification_obli = 0 : Il n'est pas nécessaire de se connecter pour voir les réservations mais la connection est
# obligatoire si l'utilisateur veut réserver ou modifier une réservation
# Modifiez éventuellement la ligne suivante en remplaçant 1 par 0.
$authentification_obli = 0 ;

################################################
# Configuration du planning : valeurs par défaut
# Une interface en ligne permet une configuration domaine par domaine de ces valeurs
################################################
# Resolution - quel bloc peut être réservé, en secondes
# remarque : 1800 secondes = 1/2 heure.
$resolution = 3600;

# Début et fin d'une journée : valeur entières uniquement de 0 à 23
# morningstarts doit être inférieur à  < eveningends.
$morningstarts = 8;
$eveningends   = 19;

# Minutes à ajouter à l'heure $eveningends pour avoir la fin réelle d'une journée.
# Examples: pour que le dernier bloc réservable de la journée soit 16:30-17:00, mettre :
# eveningends=16 et eveningends_minutes=30.
# Pour avoir une journée de 24 heures avec un pas de 15 minutes mettre :
# morningstarts=0; eveningends=23;
# eveningends_minutes=45; et resolution=900.
$eveningends_minutes = 0;

# Début de la semaine: 0 pour dimanche, 1 pour lundi, etc.
$weekstarts = 0;

# Format d'affichage du temps : valeur 0 pour un affichage « 12 heures » et valeur 1 pour un affichage  « 24 heure ».
$twentyfourhour_format = 1;

#########
# Divers
#########

// Permettre à un utilisateur non connecté (dans le cas ou $authentification_obli = 0) d'accéder à l'outil de recherche
// Par défaut et dans une souci de confidentialité, il faut être connecté pour avoir accès à l'outil de recherche ($allow_search_for_not_connected = 0).
//$allow_search_for_not_connected = 1 ==> un utilisateur non connecté peut accéder à l'outil de recherche
//$allow_search_for_not_connected = 0 ==> un utilisateur non connecté ne peut pas accéder à l'outil de recherche
$allow_search_for_not_connected = 0;

// $nb_year_calendar permet de fixer la plage de choix de l'année dans le choix des dates de début et fin des réservations
// La plage s'étend de année_en_cours - $nb_year_calendar à année_en_cours + $nb_year_calendar
// Par exemple, si on fixe $nb_year_calendar = 5 et que l'on est en 2005, la plage de choix de l'année s'étendra de 2000 à 2010
$nb_year_calendar = 5;

# $allow_user_delete_after_beginning=0 : un utilisateur ne peut pas supprimer ou modifier une réservation en cours.
# $allow_user_delete_after_beginning=1 : un utilisateur peut supprimer ou modifier dans certaines conditions une réservation en cours (et dont il est propriétaire).
$allow_user_delete_after_beginning=0;

// Avance en nombre d'heure du serveur sur les postes clients
// Le paramètre $correct_diff_time_local_serveur permet de corriger une différence d'heure entre le serveur et les postes clients
// Exemple : si Grr est installé sur un serveur configuré GMT+1 alors qu'il est utilisé dans un pays dont le fuseau horaire est GMT-5
// Le serveur a donc six heures d'avance sur les postes clients
// On indique alors : $correct_diff_time_local_serveur=6;
$correct_diff_time_local_serveur=0;

/* Paramétrage du fuseau horaire (imposer à GRR un fuseau horaire différent de celui du serveur)
 TZ (Time Zone) est une variable permettant de préciser dans quel fuseau horaire, GRR travaille.
 L'ajustement de cette variable TZ permet au programme GRR de travailler dans la zone de votre choix.
 la valeur à donner à TZ diffère d'un système à un autre (Windows, Linux, ...)
 Par exemple, sur un système Linux, si on désire retarder de 7 heures l'heure système de GRR, on aura :
 putenv("TZ=posix/Etc/GMT-7")
 Remarque : putenv() est la fonction php  qui permet de fixer la valeur d'une variable d'environnement.
 Cette valeur n'existe que durant la vie du script courant, et l'environnement initial sera restauré lorsque le script sera terminé.
 En résumé, pour activer cette fonctionnalité, décommentez la ligne suivante (en supprimant les deux premiers caractères //,
 et remplacez -7 par +n ou -n où "n" est le nombre d'heures d'avance ou de retard de GRR sur l'heure système du serveur.
*/
//putenv("TZ=posix/Etc/GMT-7");


// Changement d'heure été<->hiver
// $correct_heure_ete_hiver = 1 => GRR prend en compte les changements d'heure
// $correct_heure_ete_hiver = 0 => GRR ne prend en compte les changements d'heure
// Par défaut ($correct_heure_ete_hiver non définie) GRR prend en compte les changements d'heure.
$correct_heure_ete_hiver = 1;

# Affichage d'un domaine par defaut en fonction de l'adresse IP de la machine cliente (voir documentation)
# Mettre 0 ou 1 pour désactiver ou activer la fonction dans la page de gestion des domaines
define('OPTION_IP_ADR', 0);

# Nom de la session PHP.
# Le nom de session fait référence à l'identifiant de session dans les cookies.
# Il ne doit contenir que des caractères alpha-numériques; si possible, il doit être court et descriptif.
# Normalement, vous n'avez pas à modifier ce paramètre.
# Mais si un navigateur est amené à se connecter au cours de la même session, à deux sites GRR différents,
# ces deux sites GRR doivent avoir des noms de session différents.
# Dans ce cas, il vous faudra changer la valeur GRR ci-dessous par une autre valeur.
define('SESSION_NAME', "GRR");

# Nombre maximum (+1) de réservations autorisés lors d'une réservation avec périodicité
$max_rep_entrys = 365 + 1;

# Lors de l'édition d'un rapport, valeur par défaut en nombre de jours
# de l'intervalle de temps entre la date de début du rapport et la date de fin du rapport.
$default_report_days = 60;

# Cette variable fixe le nombre maximal de résultats lors d'une recherche
$search["count"] = 20;

# Positionner la valeur $unicode_encoding à 1 pour utiliser l'UTF-8 dans toutes les pages et dans la base
# Dans le cas contraire, les textes stockés dans la base dépendent des différents encodage selon la langue selectionnée par l'utilisateur
# Il est fortement conseillé de lire le fichier notes-utf8.txt à la racine de cette archive
$unicode_encoding = 1;

# Longueur minimale du mot de passe exigé
$pass_leng = 5;

# Ouvrir les pages au format imprimable dans une nouvelle fenêtre du navigateur (0 pour non et 1 pour oui)
$pview_new_windows=1;

# Désactive les messages javascript (pop-up) après la création/modificatio/suppression d'une réservation
# 1 = Oui, 0 = Non
$javascript_info_disabled = 0;
# Désactive les messages javascript d'information (pop-up) dans les menus d'administration
# 1 = Oui, 0 = Non
$javascript_info_admin_disabled = 0;

# Afficher la description complète de la réservation dans les vues semaine et mois.
# $display_full_description=1 : la description complète s'affiche.
# $display_full_description=0 : la description complète ne s'affiche pas.
$display_full_description=1;

# Affichage du contenu des "info-bulles" des réservations, dans les vues journées, semaine et mois.
# $display_info_bulle = 0 : pas d'info-bulle.
# $display_info_bulle = 1 : affichage des noms et prénoms du créateur de la réservation.
# $display_info_bulle = 2 : affichage de la description complète de la réservation.
$display_info_bulle=1;

# Paramètre supplémentaire de configuration de l'envoi automatique des mails, inutile sur la plupart des serveurs.
# Pour l'envoi des mails automatiques, GRR utilise la fonction PHP mail().
# Sur certains serveurs (par exemple les serveurs Kwartz www.kwartz.com), l'utilisation de cette fonction requiert
# un paramètre additionnel.
# Syntaxe : mail($email, $sujet, $message, $headers [, $parametre_additionnel])
# Si le cinquième argument $parametre_additionnel est fourni, GRR l'utilise dans son appel du programme d'envoi de courrier électronique.
# Attention : le cinquième paramètre a été ajouté en PHP 4.0.5. et ne fonctionne donc pas avec une version inférieure
#
# Dans le cas ou le serveur utilise sendmail et selon la configuration de celui-ci, il peut être nécessaire
# d'ajouter l'agument "-f adresse@fai", où adresse@fai est un mail quelconque mais fonctionnel.
# Si vous êtes dans ce cas, décommentez la ligne suivante en remplaçant "adresse@fai" par une adresse email valide.
//DEFINE("parametre_additionnel","-f adresse@fai");

# Après installation de GRR, si vous avez le message "Fatal error: Call to undefined function: mysqli_real_escape_string() ...",
# votre version de PHP est inférieure à 4.3.0.
# En effet, la fonction mysqli_real_escape_string() est disponible à partir de la version 4.3.0 de php.
# Vous devriez mettre à jour votre version de php.
# Sinon, positionnez la variable suivante à "0"; (valeur par défaut = 1)
$use_function_mysqli_real_escape_string = 1;

#############
# Entry Types
#############
# Les lignes ci-dessous correspondent aux couleurs disponibles pour les types de réservation
# Vous pouvez modifier les couleurs ou même en rajouter à votre convenance.
$tab_couleur[1] = "#FFCCFF"; // mauve pâle
$tab_couleur[2] = "#99CCCC"; // bleu
$tab_couleur[3] = "#FF9999"; // rose pâle
$tab_couleur[4] = "#FFFF99"; // jaune pâle
$tab_couleur[5] = "#C0E0FF"; // bleu-vert
$tab_couleur[6] = "#FFCC99"; // pêche
$tab_couleur[7] = "#FF6666"; // rouge
$tab_couleur[8] = "#66FFFF"; // bleu "aqua"
$tab_couleur[9] = "#DDFFDD"; // vert clair
$tab_couleur[10] = "#CCCCCC"; // gris
$tab_couleur[11] = "#7EFF7E"; // vert pâle
$tab_couleur[12] = "#8000FF"; // violet
$tab_couleur[13] = "#FFFF00"; // jaune
$tab_couleur[14] = "#FF00DE"; // rose
$tab_couleur[15] = "#00FF00"; // vert
$tab_couleur[16] = "#FF8000"; // orange
$tab_couleur[17] = "#DEDEDE"; // gris clair
$tab_couleur[18] = "#C000FF"; // Mauve
$tab_couleur[19] = "#FF0000"; // rouge vif
$tab_couleur[20] = "#FFFFFF"; // blanc
$tab_couleur[21] = "#A0A000"; // Olive verte
$tab_couleur[22] = "#DAA520"; // marron goldenrod
$tab_couleur[23] = "#40E0D0"; // turquoise
$tab_couleur[24] = "#FA8072"; // saumon
$tab_couleur[25] = "#4169E1"; // bleu royal
$tab_couleur[26] = "#6A5ACD"; // bleu ardoise
$tab_couleur[27] = "#AA5050"; // bordeaux
$tab_couleur[28] = "FFBB20"; // pêche

#################################################################################################
# Cas ou la fonction "Poser des réservations sous réserve", est activée pour une ressource donnée
#################################################################################################

// Configuration de la tâche de suppression automatique  des réservations lorsque la réservation n'a pas été confirmée.
// $verif_reservation_auto = 0 : la tâche de suppression automatique est réalisée une fois par jour, lorsqu'un utilisateur se connecte.
// $verif_reservation_auto = 1 : la tâche de suppression automatique est déclenchée par l'exécution du script verif_auto_grr.php.
// Consulter la documentation ou le fichier "notes-reservation-sous-reserve.txt"
$verif_reservation_auto = 0;

// Cas où $verif_reservation_auto = 1
// L'exécution du script verif_auto_grr.php requiert un mot de passe :
// Exemple : si le mot de passe est jamesbond007, vous devrez indiquer une URL du type :
// http://mon-site.fr/grr/verif_auto_grr.php?mdp=jamesbond007
$motdepasse_verif_auto_grr = "jamesbond007";

##############################################################
# Paramètres propres à une authentification sur un serveur LCS
##############################################################
// Page d'authentification LCS
define('LCS_PAGE_AUTHENTIF',"../../lcs/auth.php");
// Page de la librairie ldap
define('LCS_PAGE_LDAP_INC_PHP',"/var/www/Annu/includes/ldap.inc.php");
// Réalise la connexion à la base d'authentification du LCS et include des fonctions de lcs/includes/functions.inc.php
define('LCS_PAGE_AUTH_INC_PHP',"/var/www/lcs/includes/headerauth.inc.php");

###################
# Database settings
###################

# Quel système de base de données : "pgsql"=PostgreSQL, "mysql"=MySQL
# Actuellement, GRR ne supporte que mysql.
$dbsys = "mysql";
# Uncomment this to NOT use PHP persistent (pooled) database connections:
//$db_nopersist = 1;

################################
# Backup information
#################################
//true=sauvegarde la structure des tables
$structure = true;
//true=sauvegarde les donnees des tables
$donnees = true;
//clause INSERT avec nom des champs
$insertComplet = false;

##########################################
# PHP System Configuration - do not change
##########################################
# Disable magic quoting on database returns:
#set_magic_quotes_runtime(0);

# Global settings array
$grrSettings = array();

# Make sure notice errors are not reported
//error_reporting (E_ALL ^ E_NOTICE);
?>