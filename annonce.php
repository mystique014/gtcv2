<?php
##########################################################
#                            annonce.php                             									#
#                                                                       									#
#        Interface permettant à l'utilisateur de déposer une demande ou recherche d'adversaire	#
#                Dernière modification : 19/12/2008               							#
#                                                                       									#
##########################################################
/*
 * Copyright 2003-2008 S. Duchemin
 
 */
include "include/connect.inc.php";
include "include/config.inc.php";
include "include/misc.inc.php";
include "include/functions.inc.php";
include "include/$dbsys.inc.php";
    #Settings
require_once("./include/settings.inc.php");
    #Chargement des valeurs de la table settings
if (!loadSettings())
    die("Erreur chargement settings");

    #Fonction relative à la session
require_once("./include/session.inc.php");
    #Si il n'y a pas de session crée, on déconnecte l'utilisateur.
if (!grr_resumeSession())
{
    header("Location: ./logout.php?auto=1");
    die();
};



   #Si nous ne savons pas la date, nous devons la créer
$day = isset($_POST["day"]) ? $_POST["day"] : (isset($_GET["day"]) ? $_GET["day"] : date("d"));
$month = isset($_POST["month"]) ? $_POST["month"] : (isset($_GET["month"]) ? $_GET["month"] : date("m"));
$year = isset($_POST["year"]) ? $_POST["year"] : (isset($_GET["year"]) ? $_GET["year"] : date("Y"));

if (($authentification_obli==0) and (!isset($_SESSION['login'])))
{
    $session_login = '';
    $session_statut = '';
}
else
{
    $session_login = $_SESSION['login'];
    $session_statut = $_SESSION['statut'];
}

// Paramètres langage
include "include/language.inc.php";

$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
if (authGetUserLevel(getUserName(),-1) <= 1)
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}


$valid = isset($_POST["valid"]) ? $_POST["valid"] : NULL;
$envoi = isset($_POST["envoi"]) ? $_POST["envoi"] : NULL;
$msg='';

//ajout des disponibilités du joueur connecté
if (($valid == "yes") and ($envoi == "Enregistrer"))
{
    $reg_login = isset($_POST["login"]) ? $_POST["login"] : NULL;
    $reg_dispos = isset($_POST["dispos"]) ? $_POST["dispos"] : NULL;
$sql = "SELECT nom, prenom FROM ".$_COOKIE["table_prefix"]."_utilisateurs WHERE login='".$_SESSION['login']."'";
$res = grr_sql_query($sql);

    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
    $user_nom = $row[0];
    $user_prenom = $row[1];
    }	
	//vérification si un enregistrement existe
	$sql = "SELECT dispos FROM ".$_COOKIE["table_prefix"]."_annonces WHERE login='$reg_login'";
	$res = grr_sql_query($sql);
	
	
	if (grr_sql_count ($res)>0)
    {
				$sql = "UPDATE ".$_COOKIE["table_prefix"]."_annonces SET nom ='". protect_data_sql($user_nom)."', prenom='". protect_data_sql($user_prenom)."', dispos='". protect_data_sql($reg_dispos)."' WHERE login='$reg_login'";
               
                if (grr_sql_command($sql) < 0)
                    fatal_error(0, get_vocab("update_pwd_failed") . grr_sql_error());
                else
                {
                    $msg = get_vocab("update_pwd_succeed");
                    $_SESSION['dispos'] = $reg_dispos;
                }
    }else{
				$sql = "INSERT INTO ".$_COOKIE["table_prefix"]."_annonces SET login='". protect_data_sql($reg_login)."',nom ='". protect_data_sql($user_nom)."', prenom='". protect_data_sql($user_prenom)."', dispos='". protect_data_sql($reg_dispos)."'";
                if (grr_sql_command($sql) < 0)
                    fatal_error(0, get_vocab("update_pwd_failed") . grr_sql_error());
                else
                {
                    $msg = get_vocab("update_pwd_succeed");
                    $_SESSION['dispos'] = $reg_dispos;
                }
	}
}
//suppression de l'annonce du joueur connecté
if (($valid == "yes") and ($envoi == "Effacer"))
{
	$reg_login = isset($_POST["login"]) ? $_POST["login"] : NULL;
    $reg_dispos = isset($_POST["dispos"]) ? $_POST["dispos"] : NULL;

$sql = "SELECT nom, prenom FROM ".$_COOKIE["table_prefix"]."_utilisateurs WHERE login='".$_SESSION['login']."'";
$res = grr_sql_query($sql);

    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
    $user_nom = $row[0];
    $user_prenom = $row[1];
    }	
	//vérification si un enregistrement existe
	$sql = "SELECT dispos FROM ".$_COOKIE["table_prefix"]."_annonces WHERE login='$reg_login'";
	$res = grr_sql_query($sql);
	
	
	if (grr_sql_count ($res)>0)
    {
				$sql = "DELETE FROM ".$_COOKIE["table_prefix"]."_annonces WHERE nom ='". protect_data_sql($user_nom)."' AND prenom ='". protect_data_sql($user_prenom)."'";
               
                if (grr_sql_command($sql) < 0)
                    fatal_error(0, get_vocab("update_pwd_failed") . grr_sql_error());
                else
                {
                    $msg = get_vocab("update_pwd_succeed");
                    $_SESSION['dispos'] = $reg_dispos;
                }
    }
}
//
// Supression d'un utilisateur
//
if (isset($_GET['action_del'])) {
    $temp = $_GET['user_del'];
    if ($temp != $_SESSION['login']) {
        $sql = "DELETE FROM ".$_COOKIE["table_prefix"]."_annonces WHERE login='$temp'";
        if (grr_sql_command($sql) < 0) {fatal_error(1, "<p>" . grr_sql_error());}  else {
            $msg=get_vocab("del_user_succeed");
        }
    }
}

print_header($day, $month, $year, isset($area) ? $area : "");
?>
<script type="text/javascript" src="functions.js" language="javascript"></script>
<?php
#On appelle les informations de l'utilisateur pour les afficher
$sql = "SELECT nom, prenom FROM ".$_COOKIE["table_prefix"]."_utilisateurs WHERE login='".$_SESSION['login']."'";
$res = grr_sql_query($sql);

    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
    $user_nom = $row[0];
    $user_prenom = $row[1];
    }
$sql = "SELECT dispos FROM ".$_COOKIE["table_prefix"]."_annonces WHERE login='".$_SESSION['login']."'";
$res = grr_sql_query($sql);
if ($res)
{
    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
    $user_dispos = $row[0];
    }
}	

echo "<form name=\"nom_formulaire\" action=\"annonce.php\" method='POST'>";
echo("<b>".get_vocab("login").get_vocab("deux_points"). $_SESSION['login']);
echo "<div align=center><table border=1>";
echo "<br>".get_vocab("last_name").get_vocab("deux_points").$user_nom."&nbsp;&nbsp;&nbsp;".get_vocab("first_name").get_vocab("deux_points").$user_prenom;
if (isset($user_dispos)) {
echo "<br><tr><td><div align=center>".get_vocab("dispos").get_vocab("deux_points")."</td>";
echo "<tr><td><textarea name=\"dispos\" cols=80 rows=5>".$user_dispos."</textarea></td>\n";
}else{
echo "<br><tr><td>".get_vocab("dispos").get_vocab("deux_points")."</td>";
echo "<tr><td><textarea name=\"dispos\" cols=80 rows=5>Je suis disponible le             de        &agrave;     . J'ai un niveau de tennis ...                          T&eacute;l:                         Email :                  </textarea></td>\n";
}
echo "</table>";

  #http://www.phpinfo.net/articles/article_listes.html
?>
<input type=hidden name=valid value="yes">
<input type=hidden name=login value="<?php echo $_SESSION['login'];?>">
<br><input type=submit name=envoi value=<?php echo get_vocab("save") ; ?>><input type=submit name=envoi value=<?php echo get_vocab("del") ; ?>><br>
</form>
<?php
//affichage des recherches d'adversiares
echo "<br><div align=center><table border=1>";
echo "<tr><td>".get_vocab("last_name")."&nbsp;&nbsp;&nbsp;".get_vocab("first_name")."&nbsp;&nbsp;&nbsp;</td><td>".get_vocab("dispos")."</td>";

#On appelle les informations des utilisateurs pour  affichage
$sql = "SELECT nom, prenom,dispos,login FROM ".$_COOKIE["table_prefix"]."_annonces ORDER BY nom";
$res = grr_sql_query($sql);

    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
    $user_nom = $row[0];
    $user_prenom = $row[1];
    $user_dispos = $row[2];
	$user_login = $row[3];
	$col[$i][1] = "$user_nom $user_prenom";
	$col[$i][2] = "$user_dispos";
	$col[$i][3] = "$user_login";
	echo "<tr><td>{$col[$i][1]}</td>";
	echo "<td>{$col[$i][2]}</td>";
	//echo "<tr><td>$user_nom $user_prenom</td><td>$user_dispos</td></tr>";
	// Affichage du lien 'supprimer'
    if (authGetUserLevel(getUserName(),-1) >4) {
        $themessage = get_vocab("confirm_del");
        echo "<td><a href='annonce.php?user_del={$col[$i][3]}&amp;action_del=yes&amp onclick='return confirmlink(this, \"$user_login\", \"$themessage\")'>".get_vocab("delete")."</a></td>";
    } else {
        echo "";
    }
    }

?>
