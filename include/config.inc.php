<?php
###########################################################################
#   Fichier de configuration de GRR
#   Configurez ce fichier pour votre site
###########################################################################

##############
# Type d'acc�s
##############
# $authentification_obli = 1 : il est obligatoire de se connecter pour acc�der au site.
# $authentification_obli = 0 : Il n'est pas n�cessaire de se connecter pour voir les r�servations mais la connection est
# obligatoire si l'utilisateur veut r�server ou modifier une r�servation
# Modifiez �ventuellement la ligne suivante en rempla�ant 1 par 0.
$authentification_obli = 0 ;

################################################
# Configuration du planning : valeurs par d�faut
# Une interface en ligne permet une configuration domaine par domaine de ces valeurs
################################################
# Resolution - quel bloc peut �tre r�serv�, en secondes
# remarque : 1800 secondes = 1/2 heure.
$resolution = 3600;

# D�but et fin d'une journ�e : valeur enti�res uniquement de 0 � 23
# morningstarts doit �tre inf�rieur �  < eveningends.
$morningstarts = 8;
$eveningends   = 19;

# Minutes � ajouter � l'heure $eveningends pour avoir la fin r�elle d'une journ�e.
# Examples: pour que le dernier bloc r�servable de la journ�e soit 16:30-17:00, mettre :
# eveningends=16 et eveningends_minutes=30.
# Pour avoir une journ�e de 24 heures avec un pas de 15 minutes mettre :
# morningstarts=0; eveningends=23;
# eveningends_minutes=45; et resolution=900.
$eveningends_minutes = 0;

# D�but de la semaine: 0 pour dimanche, 1 pour lundi, etc.
$weekstarts = 0;

# Format d'affichage du temps : valeur 0 pour un affichage ��12 heures�� et valeur 1 pour un affichage  ��24 heure��.
$twentyfourhour_format = 1;

#########
# Divers
#########

// Permettre � un utilisateur non connect� (dans le cas ou $authentification_obli = 0) d'acc�der � l'outil de recherche
// Par d�faut et dans une souci de confidentialit�, il faut �tre connect� pour avoir acc�s � l'outil de recherche ($allow_search_for_not_connected = 0).
//$allow_search_for_not_connected = 1 ==> un utilisateur non connect� peut acc�der � l'outil de recherche
//$allow_search_for_not_connected = 0 ==> un utilisateur non connect� ne peut pas acc�der � l'outil de recherche
$allow_search_for_not_connected = 0;

// $nb_year_calendar permet de fixer la plage de choix de l'ann�e dans le choix des dates de d�but et fin des r�servations
// La plage s'�tend de ann�e_en_cours - $nb_year_calendar � ann�e_en_cours + $nb_year_calendar
// Par exemple, si on fixe $nb_year_calendar = 5 et que l'on est en 2005, la plage de choix de l'ann�e s'�tendra de 2000 � 2010
$nb_year_calendar = 5;

# $allow_user_delete_after_beginning=0 : un utilisateur ne peut pas supprimer ou modifier une r�servation en cours.
# $allow_user_delete_after_beginning=1 : un utilisateur peut supprimer ou modifier dans certaines conditions une r�servation en cours (et dont il est propri�taire).
$allow_user_delete_after_beginning=0;

// Avance en nombre d'heure du serveur sur les postes clients
// Le param�tre $correct_diff_time_local_serveur permet de corriger une diff�rence d'heure entre le serveur et les postes clients
// Exemple : si Grr est install� sur un serveur configur� GMT+1 alors qu'il est utilis� dans un pays dont le fuseau horaire est GMT-5
// Le serveur a donc six heures d'avance sur les postes clients
// On indique alors : $correct_diff_time_local_serveur=6;
$correct_diff_time_local_serveur=0;

/* Param�trage du fuseau horaire (imposer � GRR un fuseau horaire diff�rent de celui du serveur)
 TZ (Time Zone) est une variable permettant de pr�ciser dans quel fuseau horaire, GRR travaille.
 L'ajustement de cette variable TZ permet au programme GRR de travailler dans la zone de votre choix.
 la valeur � donner � TZ diff�re d'un syst�me � un autre (Windows, Linux, ...)
 Par exemple, sur un syst�me Linux, si on d�sire retarder de 7 heures l'heure syst�me de GRR, on aura :
 putenv("TZ=posix/Etc/GMT-7")
 Remarque : putenv() est la fonction php  qui permet de fixer la valeur d'une variable d'environnement.
 Cette valeur n'existe que durant la vie du script courant, et l'environnement initial sera restaur� lorsque le script sera termin�.
 En r�sum�, pour activer cette fonctionnalit�, d�commentez la ligne suivante (en supprimant les deux premiers caract�res //,
 et remplacez -7 par +n ou -n o� "n" est le nombre d'heures d'avance ou de retard de GRR sur l'heure syst�me du serveur.
*/
//putenv("TZ=posix/Etc/GMT-7");


// Changement d'heure �t�<->hiver
// $correct_heure_ete_hiver = 1 => GRR prend en compte les changements d'heure
// $correct_heure_ete_hiver = 0 => GRR ne prend en compte les changements d'heure
// Par d�faut ($correct_heure_ete_hiver non d�finie) GRR prend en compte les changements d'heure.
$correct_heure_ete_hiver = 1;

# Affichage d'un domaine par defaut en fonction de l'adresse IP de la machine cliente (voir documentation)
# Mettre 0 ou 1 pour d�sactiver ou activer la fonction dans la page de gestion des domaines
define('OPTION_IP_ADR', 0);

# Nom de la session PHP.
# Le nom de session fait r�f�rence � l'identifiant de session dans les cookies.
# Il ne doit contenir que des caract�res alpha-num�riques; si possible, il doit �tre court et descriptif.
# Normalement, vous n'avez pas � modifier ce param�tre.
# Mais si un navigateur est amen� � se connecter au cours de la m�me session, � deux sites GRR diff�rents,
# ces deux sites GRR doivent avoir des noms de session diff�rents.
# Dans ce cas, il vous faudra changer la valeur GRR ci-dessous par une autre valeur.
define('SESSION_NAME', "GRR");

# Nombre maximum (+1) de r�servations autoris�s lors d'une r�servation avec p�riodicit�
$max_rep_entrys = 365 + 1;

# Lors de l'�dition d'un rapport, valeur par d�faut en nombre de jours
# de l'intervalle de temps entre la date de d�but du rapport et la date de fin du rapport.
$default_report_days = 60;

# Cette variable fixe le nombre maximal de r�sultats lors d'une recherche
$search["count"] = 20;

# Positionner la valeur $unicode_encoding � 1 pour utiliser l'UTF-8 dans toutes les pages et dans la base
# Dans le cas contraire, les textes stock�s dans la base d�pendent des diff�rents encodage selon la langue selectionn�e par l'utilisateur
# Il est fortement conseill� de lire le fichier notes-utf8.txt � la racine de cette archive
$unicode_encoding = 1;

# Longueur minimale du mot de passe exig�
$pass_leng = 5;

# Ouvrir les pages au format imprimable dans une nouvelle fen�tre du navigateur (0 pour non et 1 pour oui)
$pview_new_windows=1;

# D�sactive les messages javascript (pop-up) apr�s la cr�ation/modificatio/suppression d'une r�servation
# 1 = Oui, 0 = Non
$javascript_info_disabled = 0;
# D�sactive les messages javascript d'information (pop-up) dans les menus d'administration
# 1 = Oui, 0 = Non
$javascript_info_admin_disabled = 0;

# Afficher la description compl�te de la r�servation dans les vues semaine et mois.
# $display_full_description=1 : la description compl�te s'affiche.
# $display_full_description=0 : la description compl�te ne s'affiche pas.
$display_full_description=1;

# Affichage du contenu des "info-bulles" des r�servations, dans les vues journ�es, semaine et mois.
# $display_info_bulle = 0 : pas d'info-bulle.
# $display_info_bulle = 1 : affichage des noms et pr�noms du cr�ateur de la r�servation.
# $display_info_bulle = 2 : affichage de la description compl�te de la r�servation.
$display_info_bulle=1;

# Param�tre suppl�mentaire de configuration de l'envoi automatique des mails, inutile sur la plupart des serveurs.
# Pour l'envoi des mails automatiques, GRR utilise la fonction PHP mail().
# Sur certains serveurs (par exemple les serveurs Kwartz www.kwartz.com), l'utilisation de cette fonction requiert
# un param�tre additionnel.
# Syntaxe : mail($email, $sujet, $message, $headers [, $parametre_additionnel])
# Si le cinqui�me argument $parametre_additionnel est fourni, GRR l'utilise dans son appel du programme d'envoi de courrier �lectronique.
# Attention : le cinqui�me param�tre a �t� ajout� en PHP 4.0.5. et ne fonctionne donc pas avec une version inf�rieure
#
# Dans le cas ou le serveur utilise sendmail et selon la configuration de celui-ci, il peut �tre n�cessaire
# d'ajouter l'agument "-f adresse@fai", o� adresse@fai est un mail quelconque mais fonctionnel.
# Si vous �tes dans ce cas, d�commentez la ligne suivante en rempla�ant "adresse@fai" par une adresse email valide.
//DEFINE("parametre_additionnel","-f adresse@fai");

# Apr�s installation de GRR, si vous avez le message "Fatal error: Call to undefined function: mysqli_real_escape_string() ...",
# votre version de PHP est inf�rieure � 4.3.0.
# En effet, la fonction mysqli_real_escape_string() est disponible � partir de la version 4.3.0 de php.
# Vous devriez mettre � jour votre version de php.
# Sinon, positionnez la variable suivante � "0"; (valeur par d�faut = 1)
$use_function_mysqli_real_escape_string = 1;

#############
# Entry Types
#############
# Les lignes ci-dessous correspondent aux couleurs disponibles pour les types de r�servation
# Vous pouvez modifier les couleurs ou m�me en rajouter � votre convenance.
$tab_couleur[1] = "#FFCCFF"; // mauve p�le
$tab_couleur[2] = "#99CCCC"; // bleu
$tab_couleur[3] = "#FF9999"; // rose p�le
$tab_couleur[4] = "#FFFF99"; // jaune p�le
$tab_couleur[5] = "#C0E0FF"; // bleu-vert
$tab_couleur[6] = "#FFCC99"; // p�che
$tab_couleur[7] = "#FF6666"; // rouge
$tab_couleur[8] = "#66FFFF"; // bleu "aqua"
$tab_couleur[9] = "#DDFFDD"; // vert clair
$tab_couleur[10] = "#CCCCCC"; // gris
$tab_couleur[11] = "#7EFF7E"; // vert p�le
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
$tab_couleur[28] = "FFBB20"; // p�che

#################################################################################################
# Cas ou la fonction "Poser des r�servations sous r�serve", est activ�e pour une ressource donn�e
#################################################################################################

// Configuration de la t�che de suppression automatique  des r�servations lorsque la r�servation n'a pas �t� confirm�e.
// $verif_reservation_auto = 0 : la t�che de suppression automatique est r�alis�e une fois par jour, lorsqu'un utilisateur se connecte.
// $verif_reservation_auto = 1 : la t�che de suppression automatique est d�clench�e par l'ex�cution du script verif_auto_grr.php.
// Consulter la documentation ou le fichier "notes-reservation-sous-reserve.txt"
$verif_reservation_auto = 0;

// Cas o� $verif_reservation_auto = 1
// L'ex�cution du script verif_auto_grr.php requiert un mot de passe :
// Exemple : si le mot de passe est jamesbond007, vous devrez indiquer une URL du type :
// http://mon-site.fr/grr/verif_auto_grr.php?mdp=jamesbond007
$motdepasse_verif_auto_grr = "jamesbond007";

##############################################################
# Param�tres propres � une authentification sur un serveur LCS
##############################################################
// Page d'authentification LCS
define('LCS_PAGE_AUTHENTIF',"../../lcs/auth.php");
// Page de la librairie ldap
define('LCS_PAGE_LDAP_INC_PHP',"/var/www/Annu/includes/ldap.inc.php");
// R�alise la connexion � la base d'authentification du LCS et include des fonctions de lcs/includes/functions.inc.php
define('LCS_PAGE_AUTH_INC_PHP',"/var/www/lcs/includes/headerauth.inc.php");

###################
# Database settings
###################

# Quel syst�me de base de donn�es : "pgsql"=PostgreSQL, "mysql"=MySQL
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