<?php
#########################################################################
#                         admin_accueil                                 #
#                                                                       #
#                       Interface d'accueil de l'administration         #
#                     des domaines et des ressources                    #
#                                                                       #
#                  Dernière modification : 21/05/2005                   #
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
include "include/admin.inc.php";

$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = htmlspecialchars($_SERVER['HTTP_REFERER']);
if(authGetUserLevel(getUserName(),-1,'area') < 4)
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    showAccessDenied($day, $month, $year, '',$back);
    exit();
}

# print the page header
print_header("","","","",$type="with_session", $page="admin");

include "admin_col_gauche.php";
?>
<table border="0">
	<tr>
		<td align="center" ><br><p style="font-size:20pt"><?php echo get_vocab("edit_logo"); ?> </p></td>
	</tr>
	<tr>
		<td align="center"><img src="img_grr/logo.gif" alt="GTC !"  border="0" /></td>
	</tr>
	<tr>
		<td align="center">
			Attention au format du logo !!!<br>Type<b> .gif  </b>taille 80 x 80 px
			<FORM METHOD="POST"
			ENCTYPE="multipart/form-data">
			<INPUT TYPE=HIDDEN NAME=MAX_FILE_SIZE
			VALUE=<? echo $MFS;?>>
			<INPUT TYPE=FILE NAME="userfile"><BR>
			<INPUT TYPE=SUBMIT value="Enregistrer le fichier">
			</FORM>
		</td>
	</tr>
<?
//gestion du logo du club
// Taille max des fichiers (octets)
$MFS=300024;
// Répertoire de stockage
$rep="img_grr/";
if(isset($_FILES['userfile'])) {
	if($_FILES['userfile']['size']>0) {
		$savefile= $rep."logo.gif";
		$temp = $_FILES['userfile']['tmp_name'];
			if (move_uploaded_file($temp, $savefile)) { ?>
			<b>Votre fichier a bien  	&eacute;t&eacute; enregistr&eacute; !</b>
			<BR>Nom : <?echo "logo.gif";?>
			<BR>Taille : <?echo $_FILES['userfile']['size'];?>
			<BR>Type : <?echo $_FILES['userfile']['type'];?>
		<?} else { ?>
		<b>Erreur d'enregistrement !</b>
		<? }

	} else { ?>
	<b>Trop gros fichier !</b>
	<i>( <? echo $MFS;?> octets max.)</i>
	<? } 
} ?>
	<tr>
		
		
	</tr>  
</table>
</body>
</html>
