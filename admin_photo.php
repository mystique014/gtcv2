<?php
#########################################################################
#                        admin_photo.php                          			#
#                                                                       #
#            Interface de création de photo identité     							  #
#                                                                       #
#            Dernière modification : 10/07/2006                         #
#                                                                       #
#########################################################################

include "include/admin.inc.php";


$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
$day   = date("d");
$month = date("m");
$year  = date("Y");

if(authGetUserLevel(getUserName(),-1) < 5)
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}
#If we dont know the right date then make it up
unset($user_login);
$user_login = isset($_GET["user_login"]) ? $_GET["user_login"] : NULL;
$valid = isset($_GET["valid"]) ? $_GET["valid"] : NULL;


if ($valid == "yes") {
    $reg_nom = isset($_GET["reg_nom"]) ? $_GET["reg_nom"] : NULL;
    $reg_prenom = isset($_GET["reg_prenom"]) ? $_GET["reg_prenom"] : NULL;
    $new_login = isset($_GET["new_login"]) ? $_GET["new_login"] : NULL;
   
}


if((authGetUserLevel(getUserName(),-1) < 1) and ($authentification_obli==1))
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}


# print the page header
print_header("","","","",$type="with_session", $page="admin");
$display = '';


?>
<p class=bold>
| <a href="admin_user.php?display=<?php echo $display; ?>"><?php echo get_vocab("back"); ?></a> |
</p>
<?php
echo get_vocab("login").get_vocab("deux_points");
if (isset($user_login) and ($user_login!='')) {
    echo $user_login;
    echo "<input type=\"hidden\" name=\"reg_login\" value=\"$user_login\" />\n";
} else {
    echo "<input type=\"text\" name=\"new_login\" size=\"20\" value=\"".htmlentities($user_login)."\" />\n";
}
?>
<HTML><BODY><CENTER>

<?
// Taille max des fichiers (octets)
$MFS=300024;
// Répertoire de stockage
$rep="images/";
if(isset($_FILES['userfile'])) {
if($_FILES['userfile']['size']>0) {
   $savefile= $rep.$user_login.".jpg";
   $temp = $_FILES['userfile']['tmp_name'];
       if (move_uploaded_file($temp, $savefile)) { ?>
      <b>Votre fichier a bien  	&eacute;t&eacute; enregistr&eacute; !</b>
<BR>Nom : <?echo $user_login.".jpg";?>
<BR>Taille : <?echo $_FILES['userfile']['size'];?>
<BR>Type : <?echo $_FILES['userfile']['type'];?>
<?   } else { ?>
      <b>Erreur d'enregistrement !</b>
   <? }

} else { ?>
   <b>Trop gros fichier !</b>
   <i>( <? echo $MFS;?> octets max.)</i>
<? } 
} ?>
 <TR> Attention au format des photos !!!<br>Type<b> .jpg  </b>taille photo identit&eacute; 35 x 45 mm</TR>  
<FORM METHOD="POST"
      ENCTYPE="multipart/form-data">
   <INPUT TYPE=HIDDEN NAME=MAX_FILE_SIZE
      VALUE=<? echo $MFS;?>>
   <INPUT TYPE=FILE NAME="userfile"><BR>
   <INPUT TYPE=SUBMIT value="Enregistrer le fichier">
</FORM>

LISTE DES FICHIERS TELECHARGES
<BR><TABLE border>

<? $dir = opendir($rep);?>

<? while ($f = readdir($dir))
   if(is_file($rep.$f)) { ?>
         <TR>
	   <TD>

          	<A href="<? echo $rep.$f; ?>"
               target="_blank"><? echo $f; ?></A>
        </TD>
         <TD align=right><? echo filesize($rep.$f); ?></TD>
         <TD>
            <? echo date("d/m/Y H:i:s",filectime($rep.$f)); ?>
         </TD></TR>
   <? }

closedir($dir); ?>
</TABLE>

</CENTER></BODY></HTML>
