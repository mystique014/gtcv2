<?php
#########################################################################
#                         admin_edit_room                               #
#                                                                       #
#                       Interface de création/modification              #
#                     des domaines et des ressources                    #
#                                                                       #
#                  Dernière modification : 10/07/2006                   #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 * D'après http://mrbs.sourceforge.net/
 *
 * Modification S Duchemin
 *
 * Nombre d'heures de réservation possible par joueur/court/semaine !
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

if (getSettingValue("module_multisite") == "Oui")
$id_site = isset($_POST["id_site"]) ? $_POST["id_site"] : (isset($_GET["id_site"]) ? $_GET["id_site"] : -1);
$action = isset($_POST["action"]) ? $_POST["action"] : (isset($_GET["action"]) ? $_GET["action"] : NULL);
$add_area = isset($_POST["add_area"]) ? $_POST["add_area"] : (isset($_GET["add_area"]) ? $_GET["add_area"] : NULL);
$area_id = isset($_POST["area_id"]) ? $_POST["area_id"] : (isset($_GET["area_id"]) ? $_GET["area_id"] : NULL);
$retour_page = isset($_POST["retour_page"]) ? $_POST["retour_page"] : (isset($_GET["retour_page"]) ? $_GET["retour_page"] : NULL);
$room = isset($_POST["room"]) ? $_POST["room"] : (isset($_GET["room"]) ? $_GET["room"] : NULL);
$id_area = isset($_POST["id_area"]) ? $_POST["id_area"] : (isset($_GET["id_area"]) ? $_GET["id_area"] : NULL);
$change_area = isset($_POST["change_area"]) ? $_POST["change_area"] : NULL;
$area_name = isset($_POST["area_name"]) ? $_POST["area_name"] : NULL;
$group_id = isset($_POST["group_id"]) ? $_POST["group_id"] : NULL;
$access = isset($_POST["access"]) ? $_POST["access"] : NULL;
$ip_adr = isset($_POST["ip_adr"]) ? $_POST["ip_adr"] : NULL;
$room_name = isset($_POST["room_name"]) ? $_POST["room_name"] : NULL;
$description = isset($_POST["description"]) ? $_POST["description"] : NULL;
$capacity = isset($_POST["capacity"]) ? $_POST["capacity"] : NULL;
$delais_max_resa_room  = isset($_POST["delais_max_resa_room"]) ? $_POST["delais_max_resa_room"] : NULL;
$delais_min_resa_room  = isset($_POST["delais_min_resa_room"]) ? $_POST["delais_min_resa_room"] : NULL;
$delais_option_reservation  = isset($_POST["delais_option_reservation"]) ? $_POST["delais_option_reservation"] : NULL;
$allow_action_in_past  = isset($_POST["allow_action_in_past"]) ? $_POST["allow_action_in_past"] : NULL;
$dont_allow_modify  = isset($_POST["dont_allow_modify"]) ? $_POST["dont_allow_modify"] : NULL;
$max_booking = isset($_POST["max_booking"]) ? $_POST["max_booking"] : NULL;
$max_booking_week = isset($_POST["max_booking_week"]) ? $_POST["max_booking_week"] : NULL;
$statut_room = isset($_POST["statut_room"]) ? "0" : "1";
$show_fic_room = isset($_POST["show_fic_room"]) ? "y" : "n";
$picture_room = isset($_POST["picture_room"]) ? $_POST["picture_room"] : NULL;
$comment_room = isset($_POST["comment_room"]) ? $_POST["comment_room"] : NULL;
$show_comment = isset($_POST["show_comment"]) ? "y" : "n";
$change_done = isset($_POST["change_done"]) ? $_POST["change_done"] : NULL;
$area_order = isset($_POST["area_order"]) ? $_POST["area_order"] : NULL;
$room_order = isset($_POST["room_order"]) ? $_POST["room_order"] : NULL;
$change_room = isset($_POST["change_room"]) ? $_POST["change_room"] : NULL;
$number_periodes = isset($_POST["number_periodes"]) ? $_POST["number_periodes"] : NULL;
$type_affichage_reser = isset($_POST["type_affichage_reser"]) ? $_POST["type_affichage_reser"] : NULL;
settype($type_affichage_reser,"integer");

$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];

if (isset($_POST["change_room_and_back"])) {
    $change_room = "yes";
    $change_done = "yes";
}

if (isset($_POST["change_area_and_back"])) {
    $change_area = "yes";
    $change_done = "yes";
}
// mémorisation du chemin de retour
if (!isset($retour_page)) {
    $retour_page = $back;
    // on nettoie la chaine :
    $long_chaine_a_supprimer = strlen(strstr($retour_page,"&amp;msg=")); // longueur de la chaine à partir de la première occurence de &amp;msg=
	if ($long_chaine_a_supprimer==0) $long_chaine_a_supprimer = strlen(strstr($retour_page,"?msg="));
    $long = strlen($retour_page) - $long_chaine_a_supprimer;
    $retour_page = substr($retour_page,0,$long);
}
$day   = date("d");
$month = date("m");
$year  = date("Y");

// modification d'une resource : admin ou gestionnaire
if (authGetUserLevel(getUserName(),-1) < 5)
{
    if (isset($room))
      {
        // Il s'agit d'une modif de ressource
        if (((authGetUserLevel(getUserName(),$room) < 3))  or (!verif_acces_ressource(getUserName(), $room))) {
            showAccessDenied($day, $month, $year, '',$back);
            exit();
        }
    } else {
        if (isset($area_id)) {
            // On vérifie que le domaine $area_id existe
            $test = grr_sql_query1("select id from ".$_COOKIE["table_prefix"]."_area where id='".$area_id."'");
            if ($test == -1) {
                showAccessDenied($day, $month, $year, '',$back);
                exit();
            }
            // Il s'agit de l'ajout d'une ressource
            // On vérifie que l'utilisateur a le droit d'ajouter des ressources
            if ((authGetUserLevel(getUserName(),$area_id,'area') < 4)) {
                showAccessDenied($day, $month, $year, '',$back);
               exit();
            }
        } else if (isset($id_area)) {
            // On vérifie que le domaine $area existe
            $test = grr_sql_query1("select id from ".$_COOKIE["table_prefix"]."_area where id='".$id_area."'");
            if ($test == -1) {
                showAccessDenied($day, $month, $year, '',$back);
                exit();
            }
            // Il s'agit de la modif d'un domaine
            if ((authGetUserLevel(getUserName(),$id_area,'area') < 4)) {
                showAccessDenied($day, $month, $year, '',$back);
               exit();
            }
        }
    }
}



$msg ='';

// Gestion des ressources
global $con;
if ((!empty($room)) or (isset($area_id))) {

    // Enregistrement d'une ressource
    if (isset($change_room))
    {
        if (isset($_POST['sup_img'])) {
            $dest = './images/';
            $ok1 = false;
            if ($f = @fopen("$dest/.test", "w")) {
                @fputs($f, '<'.'?php $ok1 = true; ?'.'>');
                @fclose($f);
                include("$dest/.test");
            }
            if (!$ok1) {
                $msg .= "L\'image n\'a pas pu être supprimée : problème d\'écriture sur le répertoire. Veuillez signaler ce problème à l\'administrateur du serveur.\\n";
                $ok = 'no';
            } else {
                if (@file_exists($dest."img_".$room.".jpg")) unlink($dest."img_".$room.".jpg");
                if (@file_exists($dest."img_".$room.".png")) unlink($dest."img_".$room.".png");
                if (@file_exists($dest."img_".$room.".gif")) unlink($dest."img_".$room.".gif");

                $picture_room = "";
            }
        }
        if (empty($capacity)) $capacity = 0;
        if ($capacity<0) $capacity = 0;
        settype($delais_max_resa_room,"integer");
        if ($delais_max_resa_room<0) $delais_max_resa_room = -1;
        settype($delais_min_resa_room,"integer");
        if ($delais_min_resa_room<0) $delais_min_resa_room = 0;
        settype($delais_option_reservation,"integer");
        if ($delais_option_reservation<0) $delais_option_reservation = 0;
        if ($allow_action_in_past == '') $allow_action_in_past = 'n';
        if ($dont_allow_modify == '') $dont_allow_modify = 'n';
        if ((isset($room)) and !((isset($action) and ($action=="duplique_room"))) ) {
        if ($max_booking=='') $max_booking = -1;
        if ($max_booking<-1) $max_booking = -1;
        if ($max_booking_week=='') $max_booking_week = -1;
        if ($max_booking_week<-1) $max_booking_week = -1;
        //if (isset($room)) {
            $sql = "UPDATE ".$_COOKIE["table_prefix"]."_room SET
            room_name='".protect_data_sql($room_name)."',
            description='".protect_data_sql($description)."', ";
            if ($picture_room != '') $sql .= "picture_room='".protect_data_sql($picture_room)."', ";
            $sql .= "comment_room='".protect_data_sql(corriger_caracteres($comment_room))."',
            show_comment='".$show_comment."',
            area_id='".$area_id."',
            show_fic_room='".$show_fic_room."',
            capacity='".$capacity."',
            delais_max_resa_room='".$delais_max_resa_room."',
            delais_min_resa_room='".$delais_min_resa_room."',
            delais_option_reservation='".$delais_option_reservation."',
            allow_action_in_past='".$allow_action_in_past."',
            dont_allow_modify='".$dont_allow_modify."',
            order_display='".protect_data_sql($area_order)."',
            type_affichage_reser='".$type_affichage_reser."',
            max_booking='".$max_booking."',
			max_booking_week='".$max_booking_week."',
            statut_room='".$statut_room."'
            WHERE id=$room";
            if (grr_sql_command($sql) < 0) {
                fatal_error(0, get_vocab('update_room_failed') . grr_sql_error());
                $ok = 'no';
            }
        } else {
            $sql = "insert into ".$_COOKIE["table_prefix"]."_room
            SET room_name='".protect_data_sql($room_name)."',
            area_id='".$area_id."',
            description='".protect_data_sql($description)."',
            picture_room='".protect_data_sql($picture_room)."',
            comment_room='".protect_data_sql(corriger_caracteres($comment_room))."',
            show_fic_room='".$show_fic_room."',
            capacity='".$capacity."',
            delais_max_resa_room='".$delais_max_resa_room."',
            delais_min_resa_room='".$delais_min_resa_room."',
            delais_option_reservation='".$delais_option_reservation."',
            allow_action_in_past='".$allow_action_in_past."',
            dont_allow_modify='".$dont_allow_modify."',
            order_display='".protect_data_sql($area_order)."',
            type_affichage_reser='".$type_affichage_reser."',
            max_booking='".$max_booking."',
			max_booking_week='".$max_booking_week."',
            statut_room='".$statut_room."'";
            if (grr_sql_command($sql) < 0) fatal_error(1, "<p>" . grr_sql_error());
            $room = mysqli_insert_id($con);
        }
		#Si room_name est vide on le change maintenant que l'on a l'id room
		if ($room_name == '') {
			$room_name = get_vocab("room")." ".$room;
			grr_sql_command("UPDATE ".$_COOKIE["table_prefix"]."_room SET room_name='".protect_data_sql($room_name)."' WHERE id=$room");
		}
        $doc_file = isset($_FILES["doc_file"]) ? $_FILES["doc_file"] : NULL;
        if (preg_match("`\.([^.]+)$`", $doc_file['name'], $match)) {
            $ext = strtolower($match[1]);
            if ($ext!='jpg' and $ext!='png'and $ext!='gif') {
                $msg .= "L\'image n\'a pas pu être enregistrée : les seules extentions autorisées sont gif, png et jpg.\\n";
                $ok = 'no';
            } else {
                $dest = './images/';
                $ok1 = false;
                if ($f = @fopen("$dest/.test", "w")) {
                    @fputs($f, '<'.'?php $ok1 = true; ?'.'>');
                    @fclose($f);
                    include("$dest/.test");
                }
                if (!$ok1) {
                    $msg .= "L\'image n\'a pas pu être enregistrée : problème d\'écriture sur le répertoire IMAGES. Veuillez signaler ce problème à l\'administrateur du serveur.\\n";
                    $ok = 'no';
                } else {
                    $old = getSettingValue("logo_etab");
                    $ok1 = @copy($doc_file['tmp_name'], $dest.$doc_file['name']);
                    if (!$ok1) $ok1 = @move_uploaded_file($doc_file['tmp_name'], $dest.$doc_file['name']);
                    if (!$ok1) {
                        $msg .= "L\'image n\'a pas pu être enregistrée : problème de transfert. Le fichier n\'a pas pu être transféré sur le répertoire IMAGES. Veuillez signaler ce problème à l\'administrateur du serveur.\\n";
                        $ok = 'no';
                    } else {
                        $tab = explode(".", $doc_file['name']);
                        $ext = strtolower($tab[1]);

                        if (@file_exists($dest."img_".$room.".".$ext)) @unlink($dest."img_".$room.".".$ext);
                        rename($dest.$doc_file['name'],$dest."img_".$room.".".$ext);
                        $picture_room = "img_".$room.".".$ext;
                        $sql_picture = "UPDATE ".$_COOKIE["table_prefix"]."_room SET picture_room='".protect_data_sql($picture_room)."' WHERE id=".$room;
                        if (grr_sql_command($sql_picture) < 0) {
                            fatal_error(0, get_vocab('update_room_failed') . grr_sql_error());
                            $ok = 'no';
                        }
                    }
               }
           }
        } else if ($doc_file['name'] != '') {
           $msg .= "L\'image n\'a pas pu être enregistrée : le fichier image sélectionné n'est pas valide !\\n";
           $ok = 'no';
        }
        $msg .= get_vocab("message_records");

    }

	
    // Si pas de problème, retour à la page d'accueil après enregistrement
    if ((isset($change_room)) and (!isset($ok))) {
        $_SESSION['displ_msg'] = 'yes';
        if (strpos($retour_page, ".php?") == "") $car = "?"; else $car = "&";
        $_SESSION['displ_msg'] = 'yes';
        Header("Location: ".$retour_page.$car."msg=".$msg);
        exit();
    }

    # print the page header
    print_header("","","","",$type="with_session", $page="admin");
    if (($msg) and (!($javascript_info_admin_disabled)))  {
        echo "<script type=\"text/javascript\" language=\"javascript\">";
        echo "<!--\n";
        echo " alert(\"".$msg."\")";
        echo "//-->";
        echo "</script>";
    }
// Affichage de la colonne de gauche
include "admin_col_gauche.php";
    ?>
    <script  type="text/javascript" src="functions.js" language="javascript"></script>
    <?php

    // affichage du formulaire
    if (isset($room)) {
        // Il s'agit d'une modification d'une ressource
        $res = grr_sql_query("SELECT * FROM ".$_COOKIE["table_prefix"]."_room WHERE id=$room");
        if (! $res) fatal_error(0, get_vocab('error_room') . $room . get_vocab('not_found'));
        $row = grr_sql_row_keyed($res, 0);
        grr_sql_free($res);
        $area_id = grr_sql_query1("select area_id from ".$_COOKIE["table_prefix"]."_room where id='".$room."'");
        $area_name = grr_sql_query1("select area_name from ".$_COOKIE["table_prefix"]."_area where id='".$area_id."'");
        if ($action=="duplique_room")
            echo "<h2>".get_vocab("match_area").get_vocab('deux_points')." ".$area_name."<br />".get_vocab("duplique_ressource")."</h2>\n";
        else
            echo "<h2>".get_vocab("match_area").get_vocab('deux_points')." ".$area_name."<br />".get_vocab("editroom")."</h2>\n";
    } else {
        // Il s'agit de l'enregistrement d'une nouvelle ressource
        $row['picture_room'] = '';
        $row["id"] = '';
        $row["room_name"]= '';
        $row["description"] = '';
        $row['comment_room'] = '';
        $row['show_comment'] = 'n';
        $row["capacity"]   = '';
        $row["delais_max_resa_room"] = -1;
        $row["delais_min_resa_room"] = 0;
        $row["delais_option_reservation"] = 0;
        $row["allow_action_in_past"] = 'n';
        $row["dont_allow_modify"] = 'n';
        $row["order_display"]  = 0;
        $row["type_affichage_reser"]  = 0;
        $row["max_booking"] = -1;
        $row["max_booking_week"] = -1;
        $row['statut_room'] = '';
        $row['show_fic_room'] = '';
        $area_name = grr_sql_query1("select area_name from ".$_COOKIE["table_prefix"]."_area where id='".$area_id."'");
        echo "<h2>".get_vocab("match_area").get_vocab('deux_points')." ".$area_name."<br />".get_vocab("addroom")."</h2>\n";

    }
    ?>
    <form enctype="multipart/form-data" action="admin_edit_room.php" method="post" name="main">

    <?php
    echo "<div>";
    if (isset($action)) echo "<input type=\"hidden\" name=\"action\" value=\"duplique_room\" />\n";
    if ($row["id"] != '') echo "<input type=\"hidden\" name=\"room\" value=\"".$row["id"]."\">\n";
    if (isset($retour_page)) echo "<input type=\"hidden\" name=\"retour_page\" value=\"".$retour_page."\">\n";
    if (isset($area_id)) echo "<input type=\"hidden\" name=\"area_id\" value=\"".$area_id."\">\n";
    echo "</div>";
    $nom_picture = '';
    if ($row['picture_room'] != '') $nom_picture = "./images/".$row['picture_room'];
    echo "<CENTER>";

    echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"6\">\n";
    echo "<tr><td>".get_vocab("name").get_vocab("deux_points")."</td><td style=\"width:30%;\">\n";
    // seul l'administrateur peut modifier le nom de la ressource
    if ((authGetUserLevel(getUserName(),$area_id,"area") >=4) or (authGetUserLevel(getUserName(),$room) >=4)) {
        echo "<input type=\"text\" name=\"room_name\" size=\"40\" value=\"".htmlspecialchars($row["room_name"])."\" />\n";
    } else {
        echo "<input type=\"hidden\" name=\"room_name\" value=\"".htmlspecialchars($row["room_name"])."\" />\n";
        echo "<b>".htmlspecialchars($row["room_name"])."</b>\n";
    }
    echo "</td></tr>\n";
    // Description
    echo "<tr><td>".get_vocab("description")."</td><td><input type=\"text\" name=\"description\"  size=\"40\" value=\"".htmlspecialchars($row["description"])."\" /></td></tr>\n";
    // Domaine

    $enable_periods = grr_sql_query1("select enable_periods from ".$_COOKIE["table_prefix"]."_area where id='".$area_id."'");
    if (((authGetUserLevel(getUserName(),$area_id,"area") >=4) or (authGetUserLevel(getUserName(),$room) >=4)) and ($enable_periods == 'n')) {
      // les créneaux sont basés sur le temps : on ne peut pas changer une ressource de domaine
      if(authGetUserLevel(getUserName(),-1,'area') >= 6)
        $sql = "SELECT id,area_name
        FROM ".$_COOKIE["table_prefix"]."_area where enable_periods='n'
        ORDER BY area_name ASC";
      else if(authGetUserLevel(getUserName(),$area_id,'area') == 5)
        $sql = "SELECT distinct a.id, a.area_name
        FROM ".$_COOKIE["table_prefix"]."_area a, ".$_COOKIE["table_prefix"]."_j_site_area j, ".$_COOKIE["table_prefix"]."_site s,  ".$_COOKIE["table_prefix"]."_j_useradmin_site u
        WHERE a.id=j.id_area and u.id_site=j.id_site  and s.id=u.id_site and u.login='".getUserName()."'  and  enable_periods='n'
        ORDER BY a.area_name ASC";

      else
        $sql = "SELECT id,area_name
        FROM ".$_COOKIE["table_prefix"]."_area a,  ".$_COOKIE["table_prefix"]."_j_useradmin_area u
        WHERE a.id=u.id_area and u.login='".getUserName()."' and  a.enable_periods='n'
        ORDER BY a.area_name ASC";
      $res = grr_sql_query($sql);
      $nb_area = grr_sql_count($res);
      if ($nb_area > 1) {
        echo "<tr><td>".get_vocab('match_area').get_vocab('deux_points')."</td>\n";
        echo "<td><select name=\"area_id\" >\n
        <option value=\"-1\">".get_vocab('choose_an_area')."</option>\n";
          for ($enr = 0; ($row1 = grr_sql_row($res, $enr)); $enr++) {
            echo "<option value=\"".$row1[0]."\"";
            if ($area_id == $row1[0])
              echo ' selected="selected"';
            echo '>'.htmlspecialchars($row1[1]);
            echo '</option>'."\n";
          }
          grr_sql_free($res);
          echo "</select></td></tr>";
      } else {
         if (isset($area_id)) echo "<input type=\"hidden\" name=\"area_id\" value=\"".$area_id."\" />\n";
      }
    } else {
      // les créneaux sont basés sur les intitulés : on ne peut pas changer une ressource de domaine
         if (isset($area_id)) echo "<input type=\"hidden\" name=\"area_id\" value=\"".$area_id."\" />\n";
    }
    // Ordre d'affichage du domaine
    echo "<tr><td>".get_vocab("order_display").get_vocab("deux_points")."</td>\n";
    echo "<td><input type=\"text\" name=\"area_order\" size=\"1\" value=\"".htmlspecialchars($row["order_display"])."\" /></td>\n";
    echo "</tr>\n";

	// Déclarer ressource indisponible
    echo "<tr><td>".get_vocab("declarer_ressource_indisponible")."<br /><i>".get_vocab("explain_max_booking")."</i></td>
    <td><input type=\"checkbox\" name=\"statut_room\" ";
    if ($row['statut_room'] == "0") echo " checked=\"checked\" ";
    echo "/></td></tr>\n";
    // Afficher la fiche de présentation de la ressource
    echo "<tr><td>".get_vocab("montrer_fiche_présentation_ressource")."</td>
    <td><input type=\"checkbox\" name=\"show_fic_room\" ";
    if ($row['show_fic_room'] == "y") echo " checked=\"checked\" ";
	echo "/><a href='javascript:centrerpopup(\"view_room.php?id_room=$room\",600,480,\"scrollbars=yes,statusbar=no,resizable=yes\")' title=\"".get_vocab("fiche_ressource")."\">
	   <img src=\"img_grr/details.png\"  alt=\"détails\" class=\"image\"  /></a></td></tr>\n";
    // Choix de l'image de la ressource
    echo "<tr><td>".get_vocab("choisir_image_ressource")."</td>
    <td><input type=\"file\" name=\"doc_file\" size=\"30\" /></td></tr>\n";
	echo "<tr><td>".get_vocab("supprimer_image_ressource").get_vocab("deux_points");
	if (@file_exists($nom_picture)) {
    		echo "<b>$nom_picture</b></td><td><input type=\"checkbox\" name=\"sup_img\" /></td></tr>";}
		else{
			echo "<b>".get_vocab("nobody")."</b></td><td><input type=\"checkbox\" disabled=\"disabled\" name=\"sup_img\" /></td></tr>";
    }
    echo "<tr><td>".get_vocab("Afficher_description_complete_dans_titre_plannings")."</td>\n
    <td><input type=\"checkbox\" name=\"show_comment\" ";
    if ($row['show_comment'] == "y") echo " checked ";
	echo "/></td></tr>\n";
    // Description complète
    echo "<tr><td colspan=\"2\">".get_vocab("description complete");
    if (getSettingValue("use_fckeditor") != 1)
        echo " ".get_vocab("description complete2");
    echo get_vocab("deux_points")."<br />";
    if (getSettingValue("use_fckeditor") == 1) {
      echo "<textarea class=\"ckeditor\" id=\"editor1\" name=\"comment_room\" rows=\"8\" cols=\"120\">\n";
      echo $row['comment_room'];
      echo "</textarea>\n";
?>
<?php

    } else {
        echo "<textarea name=\"comment_room\" rows=\"8\" cols=\"120\" >".$row['comment_room']."</textarea>";
    }
    echo "</td></tr></table>\n";

    echo "<h3>".get_vocab("configuration_ressource")."</h3>\n";

    // Type d'affichage : durée ou heure/date de fin de réservation
    echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"6\">\n";
    echo "<tr><td>".get_vocab("type_affichage_reservation").get_vocab("deux_points")."</td>\n";
    echo "<td>";
    echo "<label><input type=\"radio\" name=\"type_affichage_reser\" value=\"0\" ";
    if (($row["type_affichage_reser"]) == 0) echo " checked=\"checked\" ";
    echo "/>";
    echo get_vocab("affichage_reservation_duree");
    //echo "</label><br /><label><input type=\"radio\" name=\"type_affichage_reser\" value=\"1\" ";
    //if (($row["type_affichage_reser"]) == 1) echo " checked=\"checked\" ";
    //echo "/>";
    //echo get_vocab("affichage_reservation_date_heure");
    echo "</label></td>\n";
    echo "</tr>\n";

    // Capacité
    echo "<tr><td>".get_vocab("capacity").": </td><td><input type=\"text\" name=\"capacity\" size=\"1\" value=\"".$row["capacity"]."\" /></td></tr>\n";
    // seul les administrateurs de la ressource peuvent modifier le nombre max de réservation par utilisateur
    if ((authGetUserLevel(getUserName(),$area_id,"area") >=3) or (authGetUserLevel(getUserName(),$room) >=3)) {
        echo "<tr><td>".get_vocab("max_booking")." ";
        echo "</td><td><input type=\"text\" name=\"max_booking\" size=\"1\" value=\"".$row["max_booking"]."\" /></td></tr>";

    } else if ($row["max_booking"] != "-1") {
        echo "<tr><td>".get_vocab("msg_max_booking").get_vocab("deux_points")."</td><td>
        <input type=\"hidden\" name=\"max_booking\" value=\"".$row["max_booking"]."\">
        <b>".htmlspecialchars($row["max_booking"])."</b>
        </td></tr>";
    }
    // Nombre d'heures de réservation possible par semaine !
    // seul les administrateurs de la ressource peuvent modifier le nombre max de réservation par utilisateur et par semaine
    if ((authGetUserLevel(getUserName(),$area_id,"area") >=3) or (authGetUserLevel(getUserName(),$room) >=3)) {
        echo "<TR><TD>".get_vocab("max_booking_week")." </TD><TD>
        <input type=\"text\" name=\"max_booking_week\" value=\"".$row["max_booking_week"]."\"></TD></TR>";

    } else if ($row["max_booking_week"] != "-1") {
        echo "<TR><TD>".get_vocab("msg_max_booking_week")."</TD><TD>
        <input type=\"hidden\" name=\"max_booking_week\" value=\"".$row["max_booking_week"]."\">
        <b>".htmlspecialchars($row["max_booking_week"])."</b>
        </TD></TR>";
    }

    // L'utilisateur ne peut pas réserver au-delà d'un certain temps
    echo "<tr><td>".get_vocab("delais_max_resa_room").": </td><td><input type=\"text\" name=\"delais_max_resa_room\" size=\"1\" value=\"".$row["delais_max_resa_room"]."\" /></td></tr>\n";
    // L'utilisateur ne peut pas réserver en-dessous d'un certain temps
    echo "<tr><td>".get_vocab("delais_min_resa_room").": ";
    echo "</td><td><input type=\"text\" name=\"delais_min_resa_room\" size=\"5\" value=\"".$row["delais_min_resa_room"]."\" /></td></tr>\n";
    // L'utilisateur peut poser poser une option de réservation
    echo "<TR><TD>".get_vocab("msg_option_de_reservation").": </TD>
    <td><input type=\"text\" name=\"delais_option_reservation\" size=\"5\" value=\"".$row["delais_option_reservation"]."\" /></td></tr>\n";

    // L'utilisateur peut réserver dans le passé
    echo "<tr><td>".get_vocab("allow_action_in_past")."<br /><i>".get_vocab("allow_action_in_past_explain")."</i></td><td><input type=\"checkbox\" name=\"allow_action_in_past\" value=\"y\" ";
    if ($row["allow_action_in_past"] == 'y') echo " checked=\"checked\"";
    echo " /></td></tr>\n";

    // L'utilisateur ne peut pas modifier ou supprimer ses propres réservations
    echo "<tr><td>".get_vocab("dont_allow_modify")."</td><td><input type=\"checkbox\" name=\"dont_allow_modify\" value=\"y\" ";
    if ($row["dont_allow_modify"] == 'y') echo " checked=\"checked\"";
    echo " /></td></tr>\n";

    // Déclarer ressource indisponible
    echo "<TR><TD>".get_vocab("declarer_ressource_indisponible")."<br><i>".get_vocab("explain_max_booking")."</i></TD>
    <TD><input type=\"checkbox\" name=\"statut_room\" ";
    if ($row['statut_room'] == "0") echo " checked ";
    echo "/></TD></TR>\n";
    // Afficher la fiche de présentation de la ressource
    echo "<TR><TD>".get_vocab("montrer_fiche_présentation_ressource")."</TD>
    <TD><input type=\"checkbox\" name=\"show_fic_room\" ";
    if ($row['show_fic_room'] == "y") echo " checked ";
    echo "/></TD></TR>\n";
    // Choix de l'image de la ressource
    echo "<TR><TD>".get_vocab("choisir_image_ressource")."</TD>
    <TD><INPUT TYPE=\"FILE\" name=\"doc_file\" /></TD></TR>\n";

    if (@file_exists($nom_picture)) {
    echo "<TR><TD>".get_vocab("supprimer_image_ressource")."</TD><TD><input type=\"checkbox\" name=\"sup_img\"></TD></TR>";
    }

    echo "</table>\n";
    echo "<div style=\"text-align:center;\"><br />\n";
    echo "<input type=\"submit\" name=\"change_room\"  value=\"".get_vocab("save")."\" />\n";
    //echo "<input type=\"submit\" name=\"change_done\" value=\"".get_vocab("back")."\" />";
    //echo "<input type=\"submit\" name=\"change_room_and_back\" value=\"".get_vocab("save_and_back")."\" />";
    if (@file_exists($nom_picture) && $nom_picture) {
        echo "<br /><br /><b>".get_vocab("Image de la ressource").get_vocab("deux_points")."</b><br /><img src=\"".$nom_picture."\" alt=\"logo\" />";
    } else {
        echo "<br /><br /><b>".get_vocab("Pas image disponible")."</b>";
    }
    ?>
    </div>
    </form>
<?php

}

// Ajout ou modification d'un domaine
if ((!empty($id_area)) or (isset($add_area)))
{
  if (isset($change_area)) {
    // Affectation à un site : si aucun site n'a été affecté
    if ((getSettingValue("module_multisite") == "Oui") and ($id_site==-1)) {
      // On affiche un message d'avertissement
      ?>
      <script type="text/javascript">
      alert("<?php echo get_vocab('choose_a_site'); ?>");
      </script>
      <?php
      // On empêche le retour à la page admin_room
      unset($change_done);
    } else {
      // Un site a été affecté, on peut continuer
      // la valeur par défaut ne peut être infériure au plus petit bloc réservable
      if ($_POST['duree_par_defaut_reservation_area'] < $_POST['resolution_area']) $_POST['duree_par_defaut_reservation_area'] = $_POST['resolution_area'];
        // la valeur par défaut doit être un multiple du plus petit bloc réservable
        $_POST['duree_par_defaut_reservation_area']= intval($_POST['duree_par_defaut_reservation_area']/$_POST['resolution_area'])*$_POST['resolution_area'];

        $display_days = "";
        for ($i = 0; $i < 7; $i++) {
            if (isset($_POST['display_day'][$i]))
                $display_days .= "y";
            else
                $display_days .= "n";
        }
		

    if ($_POST['morningstarts_area'] > $_POST['eveningends_area'])
      $_POST['eveningends_area'] = $_POST['morningstarts_area'];


        if ($access) {$access='r';} else {$access='a';}
      if ((isset($id_area)) and !((isset($action) and ($action=="duplique_area"))) ) {
            // s'il y a changement de type de créneaux, on efface les réservations du domaines
        $old_enable_periods = grr_sql_query1("select enable_periods from ".$_COOKIE["table_prefix"]."_area WHERE id='".$id_area."'");
            if ($old_enable_periods != $_POST['enable_periods']) {
                $del = grr_sql_query("DELETE ".$_COOKIE["table_prefix"]."_entry FROM ".$_COOKIE["table_prefix"]."_entry, ".$_COOKIE["table_prefix"]."_room, ".$_COOKIE["table_prefix"]."_area WHERE
                ".$_COOKIE["table_prefix"]."_entry.room_id = ".$_COOKIE["table_prefix"]."_room.id and
                ".$_COOKIE["table_prefix"]."_room.area_id = ".$_COOKIE["table_prefix"]."_area.id and
          ".$_COOKIE["table_prefix"]."_area.id = '".$id_area."'");
                $del = grr_sql_query("DELETE ".$_COOKIE["table_prefix"]."_repeat FROM ".$_COOKIE["table_prefix"]."_repeat, ".$_COOKIE["table_prefix"]."_room, ".$_COOKIE["table_prefix"]."_area WHERE
                ".$_COOKIE["table_prefix"]."_repeat.room_id = ".$_COOKIE["table_prefix"]."_room.id and
                ".$_COOKIE["table_prefix"]."_room.area_id = ".$_COOKIE["table_prefix"]."_area.id and
          ".$_COOKIE["table_prefix"]."_area.id = '".$id_area."'");
            }

            $sql = "UPDATE ".$_COOKIE["table_prefix"]."_area SET
            area_name='".protect_data_sql($area_name)."',
            access='".protect_data_sql($access)."',
            order_display='".protect_data_sql($area_order)."',
            ip_adr='".protect_data_sql($ip_adr)."',
            calendar_default_values = 'n',
			minute_morningstarts_area = '".protect_data_sql($_POST['minute_morningstarts_area'])."',
            morningstarts_area = '".protect_data_sql($_POST['morningstarts_area'])."',
            eveningends_area = '".protect_data_sql($_POST['eveningends_area'])."',
            resolution_area = '".protect_data_sql($_POST['resolution_area'])."',
        duree_par_defaut_reservation_area = '".protect_data_sql($_POST['duree_par_defaut_reservation_area'])."',
            eveningends_minutes_area = '".protect_data_sql($_POST['eveningends_minutes_area'])."',
            weekstarts_area = '".protect_data_sql($_POST['weekstarts_area'])."',
            enable_periods = '".protect_data_sql($_POST['enable_periods'])."',
            twentyfourhour_format_area = '".protect_data_sql($_POST['twentyfourhour_format_area'])."',
            display_days = '".$display_days."',
			group_id = '".protect_data_sql($group_id)."'
        WHERE id=$id_area";
            if (grr_sql_command($sql) < 0) {
                fatal_error(0, get_vocab('update_area_failed') . grr_sql_error());
                $ok = 'no';
            }
        } else {
            $sql = "INSERT INTO ".$_COOKIE["table_prefix"]."_area SET
            area_name='".protect_data_sql($area_name)."',
            access='".protect_data_sql($access)."',
            order_display='".protect_data_sql($area_order)."',
            ip_adr='".protect_data_sql($ip_adr)."',
            calendar_default_values = 'n',
			minute_morningstarts_area = '".protect_data_sql($_POST['minute_morningstarts_area'])."',
            morningstarts_area = '".protect_data_sql($_POST['morningstarts_area'])."',
            eveningends_area = '".protect_data_sql($_POST['eveningends_area'])."',
            resolution_area = '".protect_data_sql($_POST['resolution_area'])."',
			duree_par_defaut_reservation_area = '".protect_data_sql($_POST['duree_par_defaut_reservation_area'])."',
            eveningends_minutes_area = '".protect_data_sql($_POST['eveningends_minutes_area'])."',
            weekstarts_area = '".protect_data_sql($_POST['weekstarts_area'])."',
            enable_periods = '".protect_data_sql($_POST['enable_periods'])."',
            twentyfourhour_format_area = '".protect_data_sql($_POST['twentyfourhour_format_area'])."',
            display_days = '".$display_days."',
			group_id = '".protect_data_sql($_POST['group_id'])."'
            ";
			//echo $sql;
            if (grr_sql_command($sql) < 0) fatal_error(1, "<p>" . grr_sql_error());
          $id_area = grr_sql_insert_id("".$_COOKIE["table_prefix"]."_area", "id");
        }
      // Affectation à un site
      if (getSettingValue("module_multisite") == "Oui") {
        $sql = "delete from ".$_COOKIE["table_prefix"]."_j_site_area where id_area='".$id_area."'";
        if (grr_sql_command($sql) < 0) fatal_error(0, "<p>".grr_sql_error()."</p>");
        $sql = "INSERT INTO ".$_COOKIE["table_prefix"]."_j_site_area SET id_site='".$id_site."', id_area='".$id_area."'";
        if (grr_sql_command($sql) < 0) fatal_error(0, "<p>".grr_sql_error()."</p>");
      }

		  #Si area_name est vide on le change maintenant que l'on a l'id area
		  if ($area_name == '') {
			  $area_name = get_vocab("match_area")." ".$id_area;
			  grr_sql_command("UPDATE ".$_COOKIE["table_prefix"]."_area SET area_name='".protect_data_sql($area_name)."' WHERE id=$id_area");
		  }
		  #on crée ou recrée ".$_COOKIE["table_prefix"]."_area_periodes pour le domaine
		  if (protect_data_sql($_POST['enable_periods'])=='y') {
			  if (isset($number_periodes)) {
          settype($number_periodes,"integer");
          if ($number_periodes < 1) $number_periodes = 1;
          $del_periode = grr_sql_query("delete from ".$_COOKIE["table_prefix"]."_area_periodes where id_area='".$id_area."'");
          #on efface le modèle par défaut avec area=0
          $del_periode = grr_sql_query("delete from ".$_COOKIE["table_prefix"]."_area_periodes where id_area='0'");
          $i = 0;
          $num = 0;
          while ($i < $number_periodes) {
				    $temp = "periode_".$i;
				    if (isset($_POST[$temp])) {
						  $nom_periode = corriger_caracteres($_POST[$temp]);
						  $reg_periode = grr_sql_query("insert into ".$_COOKIE["table_prefix"]."_area_periodes set
              id_area='".$id_area."',
              num_periode='".$num."',
              nom_periode='".protect_data_sql($nom_periode)."'
              ");
              #on crée un modèle par défaut avec area=0
              $reg_periode = grr_sql_query("insert into ".$_COOKIE["table_prefix"]."_area_periodes set
              id_area='0',
              num_periode='".$num."',
              nom_periode='".protect_data_sql($nom_periode)."'");
              $num++;
            }
            $i++;
          }
			  }
		  }
        $msg = get_vocab("message_records");
 }
  }
    if ($access=='a') {
    $sql = "DELETE FROM ".$_COOKIE["table_prefix"]."_j_user_area WHERE id_area='$id_area'";
        if (grr_sql_command($sql) < 0)
            fatal_error(0, get_vocab('update_area_failed') . grr_sql_error());
    }
/*
  if ((isset($change_done)) and (!isset($ok))) {
        if (strpos($retour_page, ".php?") == "") $car = "?"; else $car = "&";
        $_SESSION['displ_msg'] = 'yes';
        Header("Location: ".$retour_page.$car."msg=".$msg);
        exit();
    }
*/
    # print the page header
    print_header("","","","",$type="with_session", $page="admin");
    if (($msg) and (!($javascript_info_admin_disabled)))  {
        echo "<script type=\"text/javascript\" language=\"javascript\">";
        echo "<!--\n";
        echo " alert(\"".$msg."\")";
        echo "//-->";
        echo "</script>";
    }
	// Affichage de la colonne de gauche
include "admin_col_gauche.php";
  $avertissement = get_vocab("avertissement_change_type");
  ?>
  <script type="text/javascript">
  function bascule()
    {
    menu_1 = document.getElementById('menu1');
    menu_2 = document.getElementById('menu2');
    if (document.getElementById('main').enable_periods[0].checked)
    {
        menu_1.style.display = "";
        menu_2.style.display = "none";
    }
    if (document.getElementById('main').enable_periods[1].checked)
    {
        menu_1.style.display = "none";
        menu_2.style.display = "";
   }
   alert("<?php echo $avertissement; ?>");
    }

	function aff_creneaux()
    {
		nb_cr = document.getElementById('nb_per');
		if (isNaN(Number(nb_cr.value))) nb_cr.value=1;
		if (nb_cr.value>50) nb_cr.value=50;
		if (nb_cr.value<1) nb_cr.value=1;
		for (var i=1; i<=nb_cr.value; i++) {
			document.getElementById('c'+i).style.display='';
		}
		for (var i; i<=50; i++) {
			document.getElementById('c'+i).style.display='none';
		}
		return false;
    }
    </script>

  <?php
  echo "<div class=\"page_sans_col_gauche\">";
  if (isset($id_area)) {
        $res = grr_sql_query("SELECT * FROM ".$_COOKIE["table_prefix"]."_area WHERE id=$id_area");
        if (! $res) fatal_error(0, get_vocab('error_area') . $id_area . get_vocab('not_found'));
        $row = grr_sql_row_keyed($res, 0);
        grr_sql_free($res);
        if ($action=="duplique_area") {
        echo "<h2>".get_vocab("duplique_domaine")."</h2>";
        } else {
        echo "<h2>".get_vocab("editarea")."</h2>";
        }
        if ($row["calendar_default_values"] == 'y') {
			$row["minute_morningstarts_area"] = $minute_morningstarts_area;
            $row["morningstarts_area"] = $morningstarts;
            $row["eveningends_area"] = $eveningends;
            $row["resolution_area"] = $resolution;
            $row["duree_par_defaut_reservation_area"] = $duree_par_defaut_reservation_area;
            $row["eveningends_minutes_area"] = $eveningends_minutes;
            $row["weekstarts_area"] = $weekstarts;
            $row["twentyfourhour_format_area"] = $twentyfourhour_format;
            $row["display_days"] = $display_days;
			$row["group_id"] = $group_id;
        }
        if ($row["enable_periods"] != 'y') $row["enable_periods"] = 'n';
        if (getSettingValue("module_multisite") == "Oui")
            $id_site=grr_sql_query1("select id_site from ".$_COOKIE["table_prefix"]."_j_site_area where id_area='".$id_area."'");
    }
      else
    {
        $row["id"] = '';
        $row["area_name"] = '';
        $row["order_display"]  = '';
        $row["access"] = '';
        $row["ip_adr"] = '';
		$row["minute_morningstarts_area"] = 0;
        $row["morningstarts_area"] = $morningstarts;
        $row["eveningends_area"] = $eveningends;
        $row["resolution_area"] = $resolution;
        $row["duree_par_defaut_reservation_area"] = $resolution;
        $row["eveningends_minutes_area"] = $eveningends_minutes;
        $row["weekstarts_area"] = $weekstarts;
        $row["twentyfourhour_format_area"] = $twentyfourhour_format;
        $row["enable_periods"] = 'n';
        $row["display_days"] = "yyyyyyy";
		$row["group_id"] = $group_id;
        echo "<h2>".get_vocab('addarea')."</h2>";
    }
    ?>
    <form action="admin_edit_room.php" method="post" id="main">
    <?php
    echo "<div>";
    if (isset($action)) echo "<input type=\"hidden\" name=\"action\" value=\"duplique_area\" />\n";
    if (isset($retour_page)) echo "<input type=\"hidden\" name=\"retour_page\" value=\"".$retour_page."\" />";
    if ($row['id'] != '') echo "<input type=\"hidden\" name=\"id_area\" value=\"".$row["id"]."\" />";
    if (isset($add_area)) echo "<input type=\"hidden\" name=\"add_area\" value=\"".$add_area."\" />\n";
    echo "</div>";
	
    echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"6\"><tr>";
    // Nom du domaine
    echo "<td>".get_vocab("name").get_vocab("deux_points")."</td>\n";
    echo "<td style=\"width:30%;\"><input type=\"text\" name=\"area_name\" size=\"40\" value=\"".htmlspecialchars($row["area_name"])."\" /></td>\n";
    echo "</tr><tr>\n";
    // Ordre d'affichage du domaine
    echo "<td>".get_vocab("order_display").get_vocab("deux_points")."</td>\n";
    echo "<td><input type=\"text\" name=\"area_order\" size=\"1\" value=\"".htmlspecialchars($row["order_display"])."\" /></td>\n";
    echo "</tr><tr>\n";
    // Accès restreint ou non ?
    echo "<td>".get_vocab("access").get_vocab("deux_points")."</td>\n";
    echo "<td><input type=\"checkbox\" name=\"access\"";
    if ($row["access"] == 'r') echo "checked=\"checked\"";
    echo " /></td>\n";
    echo "</tr>";
// Nom du groupe d'utilisateurs
	echo "<TD>".get_vocab("admin_group.php").get_vocab("deux_points")."</TD>";
	echo "<TD><select name=\"group_id\" size=\"1\">\n";
				// Recherche du group_id de l'area pour afficher
				$sql2 = "select group_id from ".$_COOKIE["table_prefix"]."_area WHERE id=$id_area";
				$gr = grr_sql_query1($sql2);
	$sql = "select id, group_name from ".$_COOKIE["table_prefix"]."_group order by order_display";
	$res = grr_sql_query($sql);
    while ($resultat = mysqli_fetch_row($res)) {
    echo "<option value=\"".$resultat[0]."\"";
	if ( $gr == $resultat[0]) {
	echo " SELECTED>";
	}else{ echo ">";
	}
    echo $resultat[1].'</option>'."\n";
    }
    // Site
    if (getSettingValue("module_multisite") == "Oui") {
      // Affiche une liste déroulante des sites;
      if(authGetUserLevel(getUserName(),-1,'area') >= 5)
        $sql = "SELECT id,sitecode,sitename
        FROM ".$_COOKIE["table_prefix"]."_site
        ORDER BY sitename ASC";
      else
        $sql = "SELECT id,sitecode,sitename
        FROM ".$_COOKIE["table_prefix"]."_site s,  ".$_COOKIE["table_prefix"]."_j_useradmin_site u
        WHERE s.id=u.id_site and u.login='".getUserName()."'
        ORDER BY s.sitename ASC";
      $res = grr_sql_query($sql);
      $nb_site = grr_sql_count($res);
      echo "<tr><td>".get_vocab('site').get_vocab('deux_points')."</td>\n";
      if ($nb_site > 1) {
        echo "<td><select name=\"id_site\" >\n
           <option value=\"-1\">".get_vocab('choose_a_site')."</option>\n";
        for ($enr = 0; ($row1 = grr_sql_row($res, $enr)); $enr++) {
          echo "<option value=\"".$row1[0]."\"";
          if ($id_site == $row1[0])
            echo ' selected="selected"';
          echo '>'.htmlspecialchars($row1[2]);
          echo '</option>'."\n";
        }
        //grr_sql_free($res);
        echo "</select></td></tr>";
      } else {
        // un seul site
        $row1 = grr_sql_row($res, 0);
        echo "<td>".$row1[2]."<input type=\"hidden\" name=\"id_site\" value=\"".$id_site."\" /></td></tr>\n";
      }
	
	}
    
    echo '</select>'."\n";
    echo "</TD></TR>";
    // Adresse IP client :
    if (OPTION_IP_ADR==1) {
        echo "<TR>\n";
        echo "<TD>".get_vocab("ip_adr").get_vocab("deux_points")."</TD>";
        echo "<TD><input type=text name=\"ip_adr\" value=\"".htmlspecialchars($row["ip_adr"])."\"></TD>\n";
        echo "</TR>\n";
    }
	
    echo "</table>";
    // Configuration des plages horaires ...
    echo "<h3>".get_vocab("configuration_plages_horaires")."</h3>";


    // Début de la semaine: 0 pour dimanche, 1 pou lundi, etc.
    echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"6\">";
    echo "<tr>\n";
    echo "<td>".get_vocab("weekstarts_area").get_vocab("deux_points")."</td>\n";
    echo "<td style=\"width:30%;\"><select name=\"weekstarts_area\" size=\"1\">\n";
    $k = 0;
    while ($k < 7) {
        $tmp=mktime(0,0,0,10,2+$k,2005);
        echo "<option value=\"".$k."\" ";
        if ($k == $row['weekstarts_area']) echo " selected=\"selected\"";
        echo ">".utf8_strftime("%A", $tmp)."</option>\n";
        $k++;
    }
    echo "</select></td>\n";
    echo "</tr>";

    // Définition des jours de la semaine à afficher sur les plannings et calendriers
    echo "<tr>\n";
    echo "<td>".get_vocab("cocher_jours_a_afficher")."</td>\n";
    echo "<td>\n";
    for ($i = 0; $i < 7; $i++)
    {
      echo "<label><input name=\"display_day[".$i."]\" type=\"checkbox\"";
      if (substr($row["display_days"],$i,1) == 'y') echo " checked=\"checked\"";
      echo " />" . day_name($i) . "</label><br />\n";
    }
        echo "</td>\n";
	echo "</tr></table>";

	echo "<h3>".get_vocab("type_de_creneaux")."</h3>";
	echo "<table>";

    //echo "<p style=\"text-align:left;\"><b>ATTENTION :</b> Les deux types de configuration des créneaux sont incompatibles entre eux : un changement du type de créneaux entraîne donc, après validation, un <b>effacement de toutes les réservations  de ce domaine</b></p>.";
    echo "<tr><td colspan=\"2\"><label><input type=\"radio\" name=\"enable_periods\" value=\"n\" onclick=\"bascule()\" ";
    if ($row["enable_periods"] == 'n') echo "checked=\"checked\"";
    echo " />".get_vocab("creneaux_de_reservation_temps")."</label><br />";
    //echo "<label><input type=\"radio\" name=\"enable_periods\" value=\"y\" onclick=\"bascule()\" ";
    //if ($row["enable_periods"] == 'y') echo "checked=\"checked\"";
    //echo " />".get_vocab("creneaux_de_reservation_pre_definis")."</label>
	 echo "</td></tr></table>";

    //Les créneaux de réservation sont basés sur des intitulés pré-définis.
    $sql_periode = grr_sql_query("SELECT num_periode, nom_periode FROM ".$_COOKIE["table_prefix"]."_area_periodes where id_area='".$id_area."' order by num_periode");
    $num_periodes = grr_sql_count($sql_periode);
    if (!isset($number_periodes))
        if ($num_periodes == 0)
            $number_periodes = 10;
        else
            $number_periodes = $num_periodes;

    if ($row["enable_periods"] == 'y')
        echo "<table id=\"menu2\" border=\"1\" cellspacing=\"1\" cellpadding=\"6\">";
    else
        echo "<table style=\"display:none\" id=\"menu2\" border=\"1\" cellspacing=\"1\" cellpadding=\"6\">";
    echo "<tr><td>".get_vocab("nombre_de_creneaux").get_vocab("deux_points")."</td>";
    echo "<td style=\"width:30%;\"><input type=\"text\" id=\"nb_per\" name=\"number_periodes\" size=\"1\" onkeypress=\"if (event.keyCode==13) return aff_creneaux()\" value=\"$number_periodes\" />
	<a href=\"#Per\" onclick=\"javascript:return(aff_creneaux())\">".get_vocab("goto")."</a>\n";

    echo "</td></tr>\n<tr><td colspan=\"2\">";
    $i = 0;
    while ($i < 50) {
        $nom_periode = grr_sql_query1("select nom_periode FROM ".$_COOKIE["table_prefix"]."_area_periodes where id_area='".$id_area."' and num_periode= '".$i."'");
        if ($nom_periode == -1) $nom_periode = "";
        echo "<table style=\"display:none\" id=\"c".($i+1)."\"><tr><td>".get_vocab("intitule_creneau").($i+1).get_vocab("deux_points")."</td>";
        echo "<td style=\"width:30%;\"><input type=\"text\" name=\"periode_".$i."\" value=\"".htmlentities($nom_periode)."\" size=\"20\" /></td></tr></table>\n";
        $i++;
    }
    echo "</table>";

    // Cas ou les créneaux de réservations sont basés sur le temps
    if ($row["enable_periods"] == 'n')
        echo "<table id=\"menu1\" border=\"1\" cellspacing=\"1\" cellpadding=\"6\">";
    else
        echo "<table style=\"display:none\" id=\"menu1\" border=\"1\" cellspacing=\"1\" cellpadding=\"6\">";
    // Heure de début de réservation
    echo "<tr>";
    echo "<td>".get_vocab("morningstarts_area").get_vocab("deux_points")."</td>\n";
    echo "<td style=\"width:30%;\"><select name=\"morningstarts_area\" size=\"1\">\n";
    $k = 0;
    while ($k < 24) {
        echo "<option value=\"".$k."\" ";
        if ($k == $row['morningstarts_area']) echo " selected=\"selected\"";
        echo ">".$k."</option>\n";
        $k++;
    }
    echo "</select>\n";
	// Minute de début de réservation
  
    echo "<select name=\"minute_morningstarts_area\" size=\"1\">\n";
    $k = 0;
    while ($k < 60) {
        echo "<option value=\"".$k."\" ";
        if ($k == $row['minute_morningstarts_area']) echo " selected";
        echo ">".$k."</option>\n";
        $k=$k+30;
    }
    echo "</select></td>\n";
    echo "</tr>";

    // Heure de fin de réservation
    echo "<tr>\n";
    echo "<td>".get_vocab("eveningends_area").get_vocab("deux_points")."</td>\n";
    echo "<td><select name=\"eveningends_area\" size=\"1\">\n";
    $k = 0;
    while ($k < 24) {
        echo "<option value=\"".$k."\" ";
        if ($k == $row['eveningends_area']) echo " selected=\"selected\"";
        echo ">".$k."</option>\n";
        $k++;
    }
    echo "</select></td>\n";
    echo "</tr>";

    // Minutes à ajouter à l'heure $eveningends pour avoir la fin réelle d'une journée.
    echo "<tr>\n";
    echo "<td>".get_vocab("eveningends_minutes_area").get_vocab("deux_points")."</td>\n";
    echo "<td><input type=\"text\" name=\"eveningends_minutes_area\" size=\"5\" value=\"".htmlspecialchars($row["eveningends_minutes_area"])."\" /></td>\n";
    echo "</tr>";

    // Resolution - quel bloc peut être réservé, en secondes
    echo "<tr>\n";
    echo "<td>".get_vocab("resolution_area").get_vocab("deux_points")."</td>\n";
    echo "<td><input type=\"text\" name=\"resolution_area\" size=\"5\" value=\"".htmlspecialchars($row["resolution_area"])."\" /></td>\n";
    echo "</tr>";
    // Valeur par défaut de la durée d'une réservation
    echo "<tr>\n";
    echo "<td>".get_vocab("duree_par_defaut_reservation_area").get_vocab("deux_points")."</td>\n";
    echo "<td><input type=\"text\" name=\"duree_par_defaut_reservation_area\" size=\"5\" value=\"".htmlspecialchars($row["duree_par_defaut_reservation_area"])."\" /></td>\n";
    echo "</tr>";

    // Format d'affichage du temps : valeur 0 pour un affichage « 12 heures » et valeur 1 pour un affichage  « 24 heure ».
    echo "<tr>\n";
    echo "<td>".get_vocab("twentyfourhour_format_area").get_vocab("deux_points")."</td>\n";
    echo "<td>\n";
    echo "<label><input type=\"radio\" name=\"twentyfourhour_format_area\" value=\"0\" ";
    if ($row['twentyfourhour_format_area'] == 0) echo " checked=\"checked\"";
    echo " />".get_vocab("twentyfourhour_format_12")."</label>\n<br />";
    echo "<label><input type=\"radio\" name=\"twentyfourhour_format_area\" value=\"1\" ";
    if ($row['twentyfourhour_format_area'] == 1) echo " checked=\"checked\"";
    echo " />".get_vocab("twentyfourhour_format_24")."</label>\n";
    echo "</td>\n";
    echo "</tr>\n";

    echo "</table>";

    echo "<div style=\"text-align:center;\">\n";
    echo "<input type=\"submit\" name=\"change_area\" value=\"".get_vocab("save")."\" />\n";
//echo "<input type=\"submit\" name=\"change_done\" value=\"".get_vocab("back")."\" />\n";
//echo "<input type=\"submit\" name=\"change_area_and_back\" value=\"".get_vocab("save_and_back")."\" />\n";
    echo "</div></form>";

    echo "<script type=\"text/javascript\">";
    echo "aff_creneaux();";
    echo "</script>";
    echo "</div>";

 } ?>
</body>
</html>