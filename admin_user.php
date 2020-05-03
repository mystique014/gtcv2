<?php
#########################################################################
#                            admin_user.php                             #
#                                                                       #
#            interface de gestion des utilisateurs                      #
#               Dernière modification : 179/10/2011                    #
#                                                                       #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 *
 * Modification S Duchemin
 * Refonte totale de l'interface administrateur
 * Ajout d'une interface d'envoi de mail en nombres
 * Ajout option demande accusé de réception
 * Ajout option copie au club
 *
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
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
$display = isset($_GET["display"]) ? $_GET["display"] : NULL;
$order_by = isset($_GET["order_by"]) ? $_GET["order_by"] : NULL;
$cochemail = isset($_GET["cochemail"]) ? $_GET["cochemail"] : NULL;
$date_1 = isset($_GET["date_1"]) ? $_GET["date_1"] : NULL;
$date_2 = isset($_GET["date_2"]) ? $_GET["date_2"] : NULL;

$msg = '';
$mail = '';
if(authGetUserLevel(getUserName(),-1) < 5)
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}
# print the page header
print_header("","","","",$type="with_session", $page="admin");
// Affichage de la colonne de gauche
include "admin_col_gauche.php";

?>
<script type="text/javascript" src="./functions.js" language="javascript"></script>
<?php
// Nettoyage de la base locale
// On propose de supprimer les utilisateurs ext de GRR qui ne sont plus présents dans la base LCS
if ((isset($_GET['action'])) and ($_GET['action'] =="nettoyage") and (getSettingValue("sso_statut") == "lcs")) {
    // Sélection des utilisateurs non locaux
    $sql = "SELECT login, etat, source FROM grr_utilisateurs where source='ext'";
    $res = grr_sql_query($sql);
    if ($res) {
        include LCS_PAGE_LDAP_INC_PHP;
        for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
        $user_login = $row[0];
        $user_etat[$i] = $row[1];
        $user_source = $row[2];
        list($user, $groups)=people_get_variables($user_login, false);
        $flag = 1;
        if ($user["uid"] == "") {
            if ($flag == 1) $msg=get_vocab("mess2_maj_base_locale");
            $flag = 0;
            // L'utilisateur n'est plus présent dans la base LCS, on le supprime
            $sql = "DELETE FROM grr_utilisateurs WHERE login='".$user_login."'";
            if (grr_sql_command($sql) < 0) {fatal_error(1, "<p>" . grr_sql_error());}  else {
                grr_sql_command("DELETE FROM grr_j_mailuser_room WHERE login='".$user_login."'");
                grr_sql_command("DELETE FROM grr_j_user_area WHERE login='".$user_login."'");
                grr_sql_command("DELETE FROM grr_j_user_room WHERE login='".$user_login."'");
                grr_sql_command("DELETE FROM grr_j_useradmin_area WHERE login='".$user_login."'");
                $msg .= "\\n".$user_login;
            }
        }
        }
        if ($flag == 1) $msg =get_vocab("mess3_maj_base_locale");
     }
}

// Nettoyage de la base locale
// On propose de supprimer les utilisateurs ext de GRR qui ne sont plus présents dans la base LCS
if ((isset($_GET['action'])) and ($_GET['action'] =="synchro") and (getSettingValue("sso_statut") == "lcs")) {
    $statut_eleve = getSettingValue("lcs_statut_eleve");
    $statut_non_eleve = getSettingValue("lcs_statut_prof");
    include LCS_PAGE_LDAP_INC_PHP;
    $users = search_people ("(cn=*)");
    $liste_nouveaux = "";
    $liste_pb_insertion = "";
    $liste_update = "";
    $liste_pb_update = "";
    for ( $loop=0; $loop<count($users); $loop++ ) {
        $user_login = $users[$loop]["uid"];
        list($user, $groups)=people_get_variables($user_login, true);
        $user_nom = $user["nom"];
        $user_fullname = $user["fullname"];
        $user_email = $user["email"];
        $long = strlen($user_fullname) - strlen($user_nom);
        $user_prenom = substr($user_fullname, 0, $long) ;
        if (is_eleve($user_login))
            $user_statut = $statut_eleve;
        else
            $user_statut = $statut_non_eleve;
        $groupe = "";
        for ( $loop2=0; $loop2<count($groups); $loop2++ ) {
            if (($groups[$loop2]["cn"] == "Profs") or ($groups[$loop2]["cn"] == "Administratifs") or ($groups[$loop2]["cn"] == "Eleves") )
            $groupe .= $groups[$loop2]["cn"].", ";
        }
        if ($groupe == "") $groupe = "vide";


        $test = grr_sql_query1("select count(login) from grr_utilisateurs where login = '".$user_login."'");
        if ($test == 0) {
            // On insert le nouvel utilisteur
            $sql = "INSERT INTO grr_utilisateurs SET
            nom='".protect_data_sql($user_nom)."',
            prenom='".protect_data_sql($user_prenom)."',
            statut='".protect_data_sql($user_statut)."',
            email='".protect_data_sql($user_email)."',
            source='ext',
            etat='actif',
            login='".protect_data_sql($user_login)."'";
            if (grr_sql_command($sql) < 0)
                $liste_pb_insertion .= $user_login." (".$user_prenom." ".$user_nom.")<br>";
            else
                $liste_nouveaux .= $user_login." (".$user_prenom." ".$user_nom.")<br>";
        } else {
            $test2 = grr_sql_query1("select source from grr_utilisateurs where login = '".$user_login."'");
            if ($test2 == 'ext') {
                // On met à jour
                $sql = "UPDATE grr_utilisateurs SET
                nom='".protect_data_sql($user_nom)."',
                prenom='".protect_data_sql($user_prenom)."',
                email='".protect_data_sql($user_email)."'
                where login='".protect_data_sql($user_login)."'";
            }
            if (grr_sql_command($sql) < 0)
                $liste_pb_update .= $user_login." (".$user_prenom." ".$user_nom.")<br>";
            else
                $liste_update .= $user_login." (".$user_prenom." ".$user_nom.")<br>";
        }
//        echo "login : ".$user_login." Nom : ".$user_nom." Prénom : ".$user_prenom." Email : ".$user_email." Etat : ".$etat." Groupes : ".$groupe;
//        echo "<br>";

    }
    $mess = "";
    if ($liste_pb_insertion != "")
        $mess .= "<b><font color='red'>".get_vocab("liste_pb_insertion")."</b><br>".$liste_pb_insertion."</font><br>";
    if ($liste_pb_update != "")
        $mess .= "<b><font color='red'>".get_vocab("liste_pb_update")."</b><br>".$liste_pb_update."</font><br>";
    if ($liste_nouveaux != "")
        $mess .= "<b>".get_vocab("liste_nouveaux_utilisateurs")."</b><br>".$liste_nouveaux."<br>";
    if ($liste_update != "")
        $mess .= "<b>".get_vocab("liste_utilisateurs_modifie")."</b><br>".$liste_update."<br>";
}


//
// Supression d'un utilisateur
//
if ((isset($_GET['action_del'])) and ($_GET['js_confirmed'] ==1)) {
    $temp = $_GET['user_del'];
    if ($temp != $_SESSION['login']) {
        $sql = "DELETE FROM grr_utilisateurs WHERE login='$temp'";
        if (grr_sql_command($sql) < 0) {fatal_error(1, "<p>" . grr_sql_error());}  else {
            grr_sql_command("DELETE FROM grr_j_mailuser_room WHERE login='$temp'");
            grr_sql_command("DELETE FROM grr_j_user_area WHERE login='$temp'");
            grr_sql_command("DELETE FROM grr_j_user_room WHERE login='$temp'");
            grr_sql_command("DELETE FROM grr_j_useradmin_area WHERE login='$temp'");
			grr_sql_command("DELETE FROM grr_compta WHERE login='$temp'");
            $msg=get_vocab("del_user_succeed");
        }
    }
}

//if ($mess != "")
//    echo "<p>".$mess."</p>";
//echo "<noscript>";
//echo "<font color='red'>$msg</font>";
//echo "</noscript>";
//if (($msg) and (!($javascript_info_admin_disabled)))  {
//    echo "<script type=\"text/javascript\" language=\"javascript\">";
//    echo "<!--\n";
//    echo " alert(\"".$msg."\")";
//    echo "//-->";
//    echo "</script>";
//}
echo "<h2>".get_vocab('admin_user.php')."</h2>";
if (empty($display)) { $display = 'actifs'; }
if (empty($order_by)) { $order_by = 'nom,prenom'; }
if (empty($cochemail)) { $cochemail = 'aucun'; }

?>
| <a href="admin_user_modify.php?display=<?php echo $display; ?>"><?php echo get_vocab("display_add_user"); ?></a> |
<a href="admin_import_users_csv.php"><?php echo get_vocab("display_add_user_list_csv"); ?></a> |
<a href="admin_licence_csv.php"><?php echo get_vocab("ajoutlicence"); ?></a> |
<a href="admin_actif.php"><?php echo get_vocab("actif_inactif"); ?></a> 

<?php
// On propose de supprimer les utilisateurs ext de GRR qui ne sont plus présents dans la base LCS
if (getSettingValue("sso_statut") == "lcs") {
    echo "<br>Opérations LCS : | <a href=\"admin_user.php?action=nettoyage\" onclick=\"return confirmlink(this, '".AddSlashes(get_vocab("mess_maj_base_locale"))."', '".get_vocab("maj_base_locale")."')\">".get_vocab("maj_base_locale")."</a> |";
    echo " <a href=\"admin_user.php?action=synchro\" onclick=\"return confirmlink(this, '".AddSlashes(get_vocab("mess_synchro_base_locale"))."', '".get_vocab("synchro_base_locale")."')\">".get_vocab("synchro_base_locale")."</a> |";
}

echo "<form action=\"admin_user.php\" method=\"get\">\n";
echo "<table border=\"1\">\n";
echo "<tr>\n";
echo "<td>".get_vocab("display_all_user.php")."<INPUT TYPE=\"radio\" NAME=\"display\" value=\"tous\"";
if ($display=='tous') {echo " CHECKED";}
echo "></td>";
?>
<td>
 &nbsp;&nbsp;<?php echo get_vocab("display_user_on.php"); ?><INPUT TYPE="radio" NAME="display" value='actifs' <?php if ($display=='actifs') {echo " CHECKED";} ?>></td>
 <td>
 &nbsp;&nbsp;<?php echo get_vocab("display_user_off.php"); ?><INPUT TYPE="radio" NAME="display" value='inactifs' <?php if ($display=='inactifs') {echo " CHECKED";} ?>></td>
  <td>
 &nbsp;&nbsp;<?php echo get_vocab("display_admins.php"); ?><INPUT TYPE="radio" NAME="display" value='admins' <?php if ($display=='admins') {echo " CHECKED";} ?>></td>
 <td><input type=submit value=<?php echo get_vocab("OK"); ?>></td>
 </tr>
 </table>

<input type=hidden name=order_by value=<?php echo $order_by; ?>>
</form>


<?php
//sélection des membres pour envoi mail
echo "<form action=\"admin_user.php\" method=\"get\">\n";
echo "<b>Pour l'envoi de mail :</b>";
echo "<table border=\"1\">\n";
echo "<tr>\n";
echo "<td>".get_vocab("mail_user_on.php")."<INPUT TYPE=\"radio\" NAME=\"cochemail\" value=\"tous\"";
if ($cochemail=='tous') {echo " CHECKED";}
echo "></td>";
echo "<td>".get_vocab("mail_user_off.php")."<INPUT TYPE=\"radio\" NAME=\"cochemail\" value=\"aucun\"";
if ($cochemail=='aucun') {echo "checked";}
echo "></td>";
//Recherche du nom des groupes d'utilisateurs
	$sql = "SELECT group_name FROM grr_group";
	$res = grr_sql_query($sql);
	for ($i = 0; ($rowgr = grr_sql_row($res, $i)); $i++)
	{
	echo "<td>".$rowgr[0]."<INPUT TYPE=\"radio\" NAME=\"cochemail\" value=\"".$rowgr[0]."\"";
	if ($cochemail== $rowgr[0]) {echo " CHECKED";}
	echo "></td>";
	}
echo "<td><div align=center>Ann&eacute;e d&eacute;but <br><input type=\"text\" name=\"date_1\" size=\"2\" value=\"\"</td>";
echo "<td><div align=center>Ann&eacute;e fin <br><input type=\"text\" name=\"date_2\" size=\"2\" value=\"\"</td>";
echo "<td><input type=submit value=".get_vocab("OK");
echo "></td></tr></table>"; 
echo "<input type=hidden name=order_by value=".$order_by;
echo "></form>";

// Affichage du tableau
echo "<table border=1 cellpadding=3>";
echo "<tr><td><b><a href='admin_user.php?order_by=login&amp;display=$display'>".get_vocab("login_name")."</a></b></td>";
echo "<td><b><a href='admin_user.php?order_by=nom,prenom&amp;display=$display'>".get_vocab("names")."</a></b></td>";
echo "<td><b>".get_vocab("tel")."</b></td>";
echo "<td><b><a href='admin_user.php?order_by=licence,nom,prenom&amp;display=$display'>".get_vocab("licence")."</a></b></td>";
echo "<td><b><a href='admin_user.php?order_by=classement,nom,prenom&amp;display=$display'>".get_vocab("classement")."</a></b></td>";
echo "<td><b><a href='admin_user.php?order_by=datenais,nom,prenom&amp;display=$display'>".get_vocab("datenais")."</a></b></td>";
echo "<td><b><a href='admin_user.php?order_by=abt,nom,prenom&amp;display=$display'>".get_vocab("abonnement")."</b></td>";
echo "<td><b><a href='admin_user.php?order_by=group_id,nom,prenom&amp;display=$display'>".get_vocab("group")."</b></td>";
echo "<td><b>".get_vocab("photo")."</b></td>";
echo "<td><b>".get_vocab("mail_user")."</b></td>";
echo "<td><b>".get_vocab("courrier")."</b></td>";
echo "<td><b>".get_vocab("delete")."</b></td>";
echo "</tr>";
 
$nba = 0;
$nbi = 0;
//initialisation des compteurs d'abonnements en fonction du nombre de ces abonnements
$compteur = NULL;
$nb_abt = grr_sql_query1("select count(id) from grr_abt");
$h = 0;
while ($h < $nb_abt+1){
 $compteur[$h] = 0;
 $h++;
 }

$sql = "SELECT nom, prenom, licence, login, etat, source, email, datenais, tel, telport, abt, statut, classement, champio, group_id FROM grr_utilisateurs ORDER BY $order_by";
$res = grr_sql_query($sql);
if ($res) {
    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {

    $user_nom = $row[0];
    $user_prenom = $row[1];
    $user_licence = $row[2];
    $user_login = $row[3];
    $user_etat[$i] = $row[4];
    $user_source = $row[5];
    $user_email = $row[6];
    $user_datenais = $row[7];
    $user_tel = $row[8];
    $user_telport = $row[9];
    $user_abt = $row[10];
	$user_statut = $row[11];
	$user_classement = $row[12];
	$user_champio = $row[13];
	$user_groupe = $row[14];

	// Compteur du nombre d'abonnement
	if (($user_etat[$i] == 'actif') AND ($user_statut != 'administrateur') AND ($user_statut != 'visiteur')){
	++$compteur[$user_abt];
	}
    if (($user_etat[$i] == 'actif') and (($display == 'tous') or ($display == 'actifs')) and ($user_statut =='utilisateur')) {
        $affiche = 'yes';
    } else if (($user_etat[$i] != 'actif') and (($display == 'tous') or ($display == 'inactifs')) and ($user_statut =='utilisateur')) {
        $affiche = 'yes';
    } else {
        $affiche = 'no';
    }
    if (($affiche == 'yes') and ($user_statut =='utilisateur'))  {
    // Affichage des login, noms et prénoms
    $col[$i][1] = $user_login;
    $col[$i][2] = "$user_nom $user_prenom";

    // Affichage des ressources gérées

    // On teste si l'utilisateur administre un domaine
 //   $test_admin = grr_sql_query1("select count(a.area_name) from grr_area a
//    left join grr_j_useradmin_area j on a.id=j.id_area
//    where j.login = '".$user_login."'");
//    if (($test_admin > 0) or ($user_statut== 'administrateur')) $col[$i][3] = "<font color=\"#FF0000\"><b>A</b></font>"; else $col[$i][3] = "";
    // Si le domaine est restreint, on teste si l'utilateur a accès
//    $test_restreint = grr_sql_query1("select count(a.area_name) from grr_area a
//    left join grr_j_user_area j on a.id = j.id_area
 //   where j.login = '".$user_login."'");
//    if (($test_restreint > 0)  or ($user_statut== 'administrateur')) $col[$i][3] .= "<font color=\"#FF0000\"><b> R</b></font>"; else $col[$i][3] .= "";
//    // On teste si l'utilisateur administre une ressource
//    $test_room = grr_sql_query1("select count(r.room_name) from grr_room r
//    left join grr_j_user_room j on r.id=j.id_room
//    where j.login = '".$user_login."'");
//    if (($test_room > 0)  or ($user_statut== 'administrateur')) $col[$i][3] .= "<font color=\"#FF0000\"><b> G</b></font>"; else $col[$i][3] .= "";
//    // On teste si l'utilisateur reçoit des mails automatiques
//    $test_mail = grr_sql_query1("select count(r.room_name) from grr_room r
//    left join grr_j_mailuser_room j on r.id=j.id_room
//    where j.login = '".$user_login."'");
//    if ($test_mail > 0) $col[$i][3] .= "<font color=\"#FF0000\"><b> E</b></font>"; else $col[$i][3] .= "&nbsp;";
///

    // Affichage du statut
  //  if ($user_statut == "administrateur") {
  //      $color[$i]='red';
  //      $col[$i][4]=get_vocab("statut_administrator");
  //      }
  //  if ($user_statut == "visiteur") {
  //      $color[$i]='yellow';
  //      $col[$i][4]=get_vocab("statut_visitor");
  //      }
  //  if ($user_statut == "utilisateur") {
  //      $color[$i]='blue';
  //      $col[$i][4]=get_vocab("statut_user");
  //  }
   
    if ($user_etat[$i] == 'actif') {
        if ($user_champio == 'actif') {
		$bgcolor = '#40E0D0';
		} else {
		$bgcolor = '#E9E9E4';
		}
  //Compteur des abonnés actifs      
        $nba++;
    } else {
        $bgcolor = 'C0C0C0';
            //Compteur des abonnés inactifs      
        $nbi++;
    }
    // Affichage de la source
    //if (($user_source == 'local') or ($user_source == '')) {
    //    $col[$i][5]="Locale";
    //} else {
    //    $col[$i][5]="Ext.";
    //}
	$col[$i][4] = $user_licence;
	$col[$i][12] = $user_classement;
    $col[$i][7] = $user_datenais;
    $col[$i][8] = $user_tel;
    $col[$i][9] = $user_telport;
 // Recherche du nom de l'abonnement pour affichage
	$sql = "SELECT abt_name FROM grr_abt where id='$user_abt'";
	$resultat = grr_sql_query($sql);
	$row = mysqli_fetch_row($resultat);
	$col[$i][10] =  $row[0];
// Recherche du nom du groupe d'utilisateur
	$sql = "SELECT group_name FROM grr_group where id='$user_groupe'";
	$resultat = grr_sql_query($sql);
	$row = mysqli_fetch_row($resultat);
	$col[$i][14] =  $row[0];
	
  
    echo "<tr><td bgcolor='$bgcolor'>{$col[$i][1]}</td>";
    echo "<td bgcolor='$bgcolor'><a href='admin_user_modify.php?user_login=$user_login&amp;display=$display'>{$col[$i][2]}</a></td>";
    echo "<td bgcolor='$bgcolor'>{$col[$i][8]}<br>";
    echo "<bgcolor='$bgcolor'>{$col[$i][9]}</td>";
	echo "<td bgcolor='$bgcolor'>{$col[$i][4]}</td>";
	echo "<td bgcolor='$bgcolor'>{$col[$i][12]}</td>";
    echo "<td bgcolor='$bgcolor'>{$col[$i][7]}</td>";
    echo "<td bgcolor='$bgcolor'>{$col[$i][10]}</td>";
	echo "<td bgcolor='$bgcolor'>{$col[$i][14]}</td>";
    // Affichage du lien photo d'identité
    if ($_SESSION['login'] != $user_login) {
			if (file_exists('images/'.$user_login.'.jpg')){
        $themessage = get_vocab("ok");
        echo "<td bgcolor='$bgcolor'><a href='admin_photo.php?user_login=$user_login&amp;display=$display'>".get_vocab("ok")."</a></td>";
    } else {
        $themessage = get_vocab("add");
        echo "<td bgcolor='$bgcolor'><a href='admin_photo.php?user_login=$user_login&amp;display=$display'>".get_vocab("photo")."</a></td>";
    }
		}
     // Affichage du lien e-mail
    if ($_SESSION['login'] != $user_login) {
    // on test si un e-mail existe
      if ($user_email !="" ){
        $themessage = get_vocab("ok");
        echo "<td bgcolor='$bgcolor'><a href='admin_user_modify.php?user_login=$user_login&amp;display=$display'>".get_vocab("ok")."</a></td>";
    } else {
        $themessage = get_vocab("add");
        echo "<td bgcolor='$bgcolor'><a href='admin_user_modify.php?user_login=$user_login&amp;display=$display'>".get_vocab("mail_user")."</a></td>";
    }
		}
echo "<form ENCTYPE=\"multipart/form-data\" action=\"admin_user.php\" method=\"post\" >\n";	
    // Affichage case à cocher pour courrier
    
		if ($_SESSION['login'] != $user_login) {
			if ($cochemail == 'tous'){
				echo "<td bgcolor='$bgcolor'><input type='checkbox' name='mail[]' value='$user_email' checked></td>";
			}
			if ($cochemail == 'aucun'){
				echo "<td bgcolor='$bgcolor'><input type='checkbox' name='mail[]' value='$user_email' ></td>";
			} 
			//Pour tenir compte des dates de trie date_1 et date_2
			$year  = date("Y");
			if (empty($date_1)) { $date_1 = '1900'; }
			if (empty($date_2)) { $date_2 = $year; }
    		//Recherche du nom des groupes d'utilisateurs
				$sql = "SELECT group_name FROM grr_group WHERE id = '$user_groupe'";
				$resul = grr_sql_query($sql);
				for ($j = 0; ($rowg = grr_sql_row($resul, $j)); $j++)
				if (($cochemail == $rowg[0]) AND $user_datenais >= $date_1 AND $user_datenais <= $date_2){
					echo "<td bgcolor='$bgcolor'><input type='checkbox' name='mail[]' value='$user_email' checked ></td>";
				} elseif (($cochemail == $rowg[0]) AND ($user_datenais < $date_1 OR $user_datenais > $date_2)){
					echo "<td bgcolor='$bgcolor'><input type='checkbox' name='mail[]' value='$user_email' ></td>";
				} elseif (($cochemail != $rowg[0]) AND ($cochemail != 'tous') AND ($cochemail != 'aucun')){
				echo "<td bgcolor='$bgcolor'><input type='checkbox' name='mail[]' value='$user_email' ></td>";
				}
    	}
    
    // Affichage du lien 'supprimer'
    if ($_SESSION['login'] != $user_login) {
        $themessage = get_vocab("confirm_del");
        echo "<td bgcolor='$bgcolor'><a href='admin_user.php?user_del={$col[$i][1]}&amp;action_del=yes&amp;display=$display' onclick='return confirmlink(this, \"{$col[$i][1]}\", \"$themessage\")'>".get_vocab("delete")."</a></td>";
    } else {
        echo "<td bgcolor='$bgcolor'>&nbsp;</td>";
    }

    // Fin de la ligne courante
    echo "</tr>";
    } 
	
	//Affichage des admins généraux
	if (($display == 'admins') and ($user_statut =='administrateur') and ($user_login != 'supervision')) {
	// Affichage des login, noms et prénoms
    $col[$i][1] = $user_login;
    $col[$i][2] = "$user_nom $user_prenom";
	$col[$i][3] = $user_statut;
	if ($user_etat[$i] == 'actif') {
        $bgcolor = '#E9E9E4';
		}
	echo "<tr><td bgcolor='$bgcolor'>{$col[$i][1]}</td>";
    echo "<td bgcolor='$bgcolor'><a href='admin_user_modify.php?user_login=$user_login&amp;display=$display'>{$col[$i][2]}</a></td>";
    echo "<td bgcolor='$bgcolor'>&nbsp;</td>";
	echo "<td bgcolor='$bgcolor'>&nbsp;</td>";
	echo "<td bgcolor='$bgcolor'>&nbsp;</td>";
	echo "<td bgcolor='$bgcolor'>&nbsp;</td>";
	echo "<td bgcolor='$bgcolor'>&nbsp;</td>";
	echo "<td bgcolor='$bgcolor'>&nbsp;</td>";
	echo "<td bgcolor='$bgcolor'>&nbsp;</td>";
	if ($_SESSION['login'] != $user_login) {
    // on test si un e-mail existe
      if ($user_email !="" ){
        $themessage = get_vocab("ok");
        echo "<td bgcolor='$bgcolor'><a href='admin_user_modify.php?user_login=$user_login&amp;display=$display'>".get_vocab("ok")."</a></td>";
    } else {
        $themessage = get_vocab("add");
        echo "<td bgcolor='$bgcolor'><a href='admin_user_modify.php?user_login=$user_login&amp;display=$display'>".get_vocab("mail_user")."</a></td>";
    }
	echo "<td bgcolor='$bgcolor'>&nbsp;</td>";
	}
	        echo "<td bgcolor='$bgcolor'>&nbsp;</td>";
    }
	}	
}

echo "</table>";


// fin de l'affichage de la colonne de droite
echo "</td></tr></table><br>";

echo "<div align=center>Nombre d'abonn&eacute;s actifs : ".$nba;
	if (($display == 'tous') or ($display == 'inactifs')) {
			echo "<div align=center>Nombre d'abonn&eacute;s inactifs : ".$nbi;
	}
echo "<center><table border=1 cellpadding=3>";
$j = 1;
while($j < $nb_abt+1) {
	$sql = "SELECT abt_name FROM grr_abt WHERE id='$j'";
	$resultat = grr_sql_query($sql);
	$row = mysqli_fetch_row($resultat);
	$nom_abt =  $row[0];
if ((isset($compteur[$j])) and ($compteur[$j] > 0)){
echo "<center><td><div align=center>".$nom_abt."  : ".$compteur[$j]."</td>";
} else { echo "<center><td><div align=center>".$nom_abt."  : 0</td>";
}
$j++;
}
echo "</tr></table><br><font color='red'>vous devez cocher les cases des membres auxquels vous souhaitez envoyer un message !!</font><br>";
echo "<input type='checkbox' name='accus'>Avec accus&eacute; de r&eacute;ception (ATTENTION valable pour un nombre limit&eacute; de mails)<br>";
echo "<input type='checkbox' name='copie'>Avec copie pour le club";
echo "<input type=\"hidden\" name=\"envoi\" value=\"yes\">\n";
echo "<br><input type=\"submit\" value='Envoyer'>\n";	
echo "<table border=1>";
echo "<tr><td style='background-color: #CC9933'>Sujet du courrier :</td><br>";
echo "<tr><td><textarea name=\"sujet\" cols=50 rows=0>Le bureau ...</textarea></td></tr>\n";
echo "<tr><td style='background-color: #CC9933'>Tapez le courrier &agrave; adresser aux membres s&eacute;lectionn&eacute;s :</td><br>";
echo "<tr><td><textarea name=\"courrier\" cols=80 rows=20>Le bureau vous informe ...</textarea></td></tr>\n";
echo "<tr><td style='background-color: #CC9933'>Fichier joint:</td><br>";
echo "<tr><td><input type=\"file\" name=\"nomfichier\"></td></tr>\n";
echo "</form>";
echo "</table>";


if (isset($_POST['mail'])){
      			$cour = $_POST['courrier'];     //Texte du courrier
      			$courrier = trim($cour);		//Supprime les retour clavier
            $mail = $_POST['mail']; 						//Contenu des cases à cocher
						$sujet = $_POST['sujet'];          
     				//$courrier = nl2br ($courrier);
            $nbr = count ($mail);								// Nombre de mail contenu dans name='mail[]'
						
						//récupération des caractéristiques du fichier uploadé
						$name_file = $_FILES['nomfichier']['name']; 
						$source=$_FILES['nomfichier']['tmp_name'];
						
						//Transfert dans le répertoire /upload du serveur
						move_uploaded_file($source, "./upload/".$name_file); 

						//récupération du type d'extension
						$extension=get_extension($name_file);

								if($extension=="doc")
								{ $typ="application/msword";	}
								else if($extension=="xls")
								{ $typ="application/msexcel"; }
								else if($extension=="zip")
								{ $typ="application/zip"; }
								else if($extension=="pdf")
								{ $typ="application/pdf"; }
								else if($extension=="txt")
								{ $typ="text/plain"; }
								else if($extension=="jpg")
								{ $typ="image/jpeg"; }
								else if($extension=="bmp")
								{ $typ="image/bmp"; }
						
			echo "-----courrier envoy&eacute; &agrave;-----<br>\n";		
					$i = 0;
					while ($i < $nbr) {
    					       
 							$destinataire = $mail[$i];
 							$expediteur = "".getSettingValue("webmaster_email")."";
 	// on génère une chaîne de caractères aléatoire qui sera utilisée comme frontière
  						$boundary = "-----=" . md5( uniqid ( rand() ) );

  						$headers  = "From: $expediteur\n";
  						$headers .= "Reply-To: $expediteur\n";
	//test pour accusé de réception
						if (isset($_POST['accus'])){
						$headers .= "Return-Receipt-To: $expediteur\n";
						$headers .= "Disposition-Notification-To: $expediteur\r\n"; 
						}
  // on indique qu'on a affaire à un email au format html et texte et
  // on spécifie la frontière (boundary) qui servira à séparer les deux parties
  // ainsi que la version mime
  						$headers .= "MIME-Version: 1.0\n";
  						$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"";

  						$message_txt  = $courrier;
  
    					$message  = "This is a multi-part message in MIME format.\n\n";
 						$message .= "--" . $boundary . "\n";
  						$message .= "Content-Type: text/plain; charset=\"UTF-8\"\n";
  						$message .= "Content-Transfer-Encoding: quoted-printable\n\n";
  						$message .= $message_txt;
  						$message .= "\n\n";
  						  					
     					if($name_file!="" )  
    					{  
      								$file = $name_file;  
      								$fp = fopen("./upload/".$file, "rb" );    
      								$attachment = fread($fp, filesize("./upload/".$file));  
      								fclose($fp);  
       
      								$attachment = chunk_split(base64_encode($attachment));  
     					
     					$message .= "--" . $boundary . "\n";
						$message .= "Content-Type: $typ; name=\"$file\"\n";
     					$message .= "Content-Transfer-Encoding: base64\n";
     					$message .= "Content-Disposition:inline; filename=\"$file\"\n\n";
						$message .= $attachment . "\r\n";
							}

    					$message .= "--" . $boundary . "--\n";	
						if ($destinataire !='') {
						mail($destinataire, $sujet, $message, $headers);
						echo $destinataire ."<br>\n";
						}
							$i++;
							}
		//test copie pour le club
						if (isset($_POST['copie'])){
						//création de la liste des adreses mail envoyées
						
							$message1  = "This is a multi-part message in MIME format.\n\n";
							$message1 .= "--" . $boundary . "\n";
							$message1 .= "Content-Type: text/plain; charset=\"UTF-8\"\n";
							$message1 .= "Content-Transfer-Encoding: quoted-printable\n\n";
							$message1 .= $message_txt;
							$message1 .= "\n\n Liste des mails destinataires : \n";
								$i = 0;
							while ($i < $nbr) {
							$destinataire = $mail[$i];
							$message1  .= "\n".$destinataire;
							$i++;
							}
							$message1 .= "--\n" . $boundary . "--\n";
						mail($expediteur, $sujet, $message1, $headers);
						}
   							$mail= NULL;
	echo "-------------<br>\n";
							// suppression du fichier uploadé
							if($name_file!="")
							{ unlink("./upload/".$name_file); }
							
	// Affichage du contenu envoyé (dest, message, nom fichier uploadé)						
	echo $message_txt ."<br>\n";
	echo "-------------<br>\n";
	echo "fichier joint : ".$name_file."<br>\n";
							}
							
							//Réinitialisation des variables
							$destinataire ='';
							$courrier = '';
							$message = '';
							$attachment = '';
							$message1 = '';

?>

</body>
</html>