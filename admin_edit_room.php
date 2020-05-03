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

$add_area = isset($_POST["add_area"]) ? $_POST["add_area"] : (isset($_GET["add_area"]) ? $_GET["add_area"] : NULL);
$area_id = isset($_POST["area_id"]) ? $_POST["area_id"] : (isset($_GET["area_id"]) ? $_GET["area_id"] : NULL);
$retour_page = isset($_POST["retour_page"]) ? $_POST["retour_page"] : (isset($_GET["retour_page"]) ? $_GET["retour_page"] : NULL);
$room = isset($_POST["room"]) ? $_POST["room"] : (isset($_GET["room"]) ? $_GET["room"] : NULL);
$area = isset($_POST["area"]) ? $_POST["area"] : (isset($_GET["area"]) ? $_GET["area"] : NULL);
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
$change_done = isset($_POST["change_done"]) ? $_POST["change_done"] : NULL;
$area_order = isset($_POST["area_order"]) ? $_POST["area_order"] : NULL;
$room_order = isset($_POST["room_order"]) ? $_POST["room_order"] : NULL;
$change_room = isset($_POST["change_room"]) ? $_POST["change_room"] : NULL;
$number_periodes = isset($_POST["number_periodes"]) ? $_POST["number_periodes"] : NULL;
$type_affichage_reser = isset($_POST["type_affichage_reser"]) ? $_POST["type_affichage_reser"] : NULL;
settype($type_affichage_reser,"integer");

$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];

// mémorisation du chemin de retour
if (!isset($retour_page)) $retour_page = $back;

$day   = date("d");
$month = date("m");
$year  = date("Y");

// modification d'une resource : admin ou gestionnaire
if (authGetUserLevel(getUserName(),-1) < 5)  {
    if (isset($room)) {
        // Il s'agit d'une modif de ressource
        if ((authGetUserLevel(getUserName(),$room) < 3)) {
            showAccessDenied($day, $month, $year, $area,$back);
            exit();
        }
    } else {
        if (isset($area_id)) {
            // On vérifie que le domaine $area_id existe
            $test = grr_sql_query1("select id from grr_area where id='".$area_id."'");
            if ($test == -1) {
                showAccessDenied($day, $month, $year, $area,$back);
                exit();
            }
            // Il s'agit de l'ajout d'une ressource
            // On vérifie que l'utilisateur a le droit d'ajouter des ressources
            if ((authGetUserLevel(getUserName(),$area_id,'area') < 4)) {
                showAccessDenied($day, $month, $year, $area,$back);
               exit();
            }
        } else if (isset($area)) {
            // On vérifie que le domaine $area existe
            $test = grr_sql_query1("select id from grr_area where id='".$area."'");
            if ($test == -1) {
                showAccessDenied($day, $month, $year, $area,$back);
                exit();
            }
            // Il s'agit de la modif d'un domaine
            if ((authGetUserLevel(getUserName(),$area,'area') < 4)) {
                showAccessDenied($day, $month, $year, $area,$back);
               exit();
            }
        }
    }
}


// Gestion du retour à la page précédente sans enregistrement
if (isset($change_done))
{
    Header("Location: ".$retour_page);
    exit();
}

$msg ='';

// Gestion des ressources
global $con;
if ((!empty($room)) or (isset($area_id))) {

    //test le nom de la room (permet de vérifier si elle existe déjà)
	$test1 = grr_sql_query1("select count(id) from grr_room where room_name='".$room_name."' and id!='".$room."'");
				
    
	// Enregistrement d'une ressource
   if ((isset($change_room)) AND $test1 == 0)
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
        if ($room_name=='') $room_name = "...";
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
        if ($max_booking=='') $max_booking = -1;
        if ($max_booking<-1) $max_booking = -1;
        if ($max_booking_week=='') $max_booking_week = -1;
        if ($max_booking_week<-1) $max_booking_week = -1;
        if (isset($room)) {
            $sql = "UPDATE grr_room SET
            room_name='".protect_data_sql($room_name)."',
            description='".protect_data_sql($description)."', ";
            if ($picture_room != '') $sql .= "picture_room='".protect_data_sql($picture_room)."', ";
            $sql .= "comment_room='".protect_data_sql(corriger_caracteres($comment_room))."',
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
            $sql = "insert into grr_room
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
        $doc_file = isset($_FILES["doc_file"]) ? $_FILES["doc_file"] : NULL;
        if (preg_match("/\.([^.]+)$/", $doc_file['name'], $match)) {
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
                        $sql_picture = "UPDATE grr_room SET picture_room='".protect_data_sql($picture_room)."' WHERE id=".$room;
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

    }else if ($test1 == 0){
	}else{
		$msg = "Enregistrement impossible : Une ressource portant le meme nom existe deja.";
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

    ?>
    <script  type="text/javascript" src="functions.js" language="javascript"></script>
    <?php

    // affichage du formulaire
    if (isset($room)) {
        // Il s'agit d'une modification d'une ressource
        $res = grr_sql_query("SELECT * FROM grr_room WHERE id=$room");
        if (! $res) fatal_error(0, get_vocab('error_room') . $room . get_vocab('not_found'));
        $row = grr_sql_row_keyed($res, 0);
        grr_sql_free($res);
        $temp = grr_sql_query1("select area_id from grr_room where id='".$room."'");
        $area_name = grr_sql_query1("select area_name from grr_area where id='".$temp."'");
        echo "<h2 ALIGN=CENTER>".get_vocab("match_area").get_vocab('deux_points')." ".$area_name."<br>".get_vocab("editroom")."</h2>\n";
    } else {
        // Il s'agit de l'enregistrement d'une nouvelle ressource
        $row['picture_room'] = '';
        $row["id"] = '';
        $row["room_name"]= '';
        $row["description"] = '';
        $row['comment_room'] = '';
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
        $area_name = grr_sql_query1("select area_name from grr_area where id='".$area_id."'");
        echo "<h2 ALIGN=CENTER>".get_vocab("match_area").get_vocab('deux_points')." ".$area_name."<br>".get_vocab("addroom")."</h2>\n";

    }
    ?>
    <form enctype="multipart/form-data" action="admin_edit_room.php" method="post" name="main">

    <?php
    if ($row["id"] != '') echo "<input type=\"hidden\" name=\"room\" value=\"".$row["id"]."\">\n";
    if (isset($retour_page)) echo "<input type=\"hidden\" name=\"retour_page\" value=\"".$retour_page."\">\n";
    if (isset($area_id)) echo "<input type=\"hidden\" name=\"area_id\" value=\"".$area_id."\">\n";
    ?>

    <?php
    $nom_picture = '';
    if ($row['picture_room'] != '') $nom_picture = "./images/".$row['picture_room'];
    echo "<CENTER>";

    echo "<TABLE border=\"0\" cellspacing=\"0\" cellpadding=\"6\">\n";
    echo "<TR><TD>".get_vocab("name").get_vocab("deux_points")."</TD><TD>\n";
    // seul l'administrateur peut modifier le nom de la ressource
    if ((authGetUserLevel(getUserName(),$area_id,"area") >=4) or (authGetUserLevel(getUserName(),$room) >=4)) {
        echo "<input type=\"text\" name=\"room_name\" size=\"50\" value=\"".htmlspecialchars($row["room_name"])."\">\n";
    } else {
        echo "<input type=\"hidden\" name=\"room_name\" value=\"".htmlspecialchars($row["room_name"])."\" />\n";
        echo "<b>".htmlspecialchars($row["room_name"])."</b>\n";
    }
    echo "</TD></TR>\n";
    // Description
    echo "<TR><TD>".get_vocab("description")."</TD><TD><input type=\"text\" name=\"description\"  size=\"50\" value=\"".htmlspecialchars($row["description"])."\"></TD></TR>\n";
    // Description complète
    echo "<TR><TD>".get_vocab("description complète")."</TD><TD><textarea name=\"comment_room\" rows=\"8\" cols=\"80\" wrap=\"virtual\">".$row['comment_room']."</textarea></TD></TR>\n";
    // Ordre d'affichage du domaine
    echo "<tr><TD>".get_vocab("order_display").get_vocab("deux_points")."</TD>\n";
    echo "<TD><input type=text name=\"area_order\" value=\"".htmlspecialchars($row["order_display"])."\"></TD>\n";
    echo "</TR>\n";

    // Type d'affichage : durée ou heure/date de fin de réservation
    echo "<tr><TD>".get_vocab("type_affichage_reservation").get_vocab("deux_points")."</TD>\n";
    echo "<TD>";
    echo "<input type=\"radio\" name=\"type_affichage_reser\" value=\"0\" ";
    if (($row["type_affichage_reser"]) == 0) echo " checked ";
    echo "/>";
    echo get_vocab("affichage_reservation_duree");
    /*echo "<br><input type=\"radio\" name=\"type_affichage_reser\" value=\"1\" ";
    if (($row["type_affichage_reser"]) == 1) echo " checked ";
    echo "/>";
    echo get_vocab("affichage_reservation_date_heure");*/
    echo "</TD>\n";
    echo "</TR>\n";
    echo "</table>\n<TABLE border=\"1\" cellspacing=\"0\" cellpadding=\"6\">\n";

    // Capacité
    echo "<TR><TD>".get_vocab("capacity").": </TD><TD><input type=text name=capacity value=\"".$row["capacity"]."\"></TD></TR>\n";
    // seul les administrateurs de la ressource peuvent modifier le nombre max de réservation par utilisateur
    if ((authGetUserLevel(getUserName(),$area_id,"area") >=3) or (authGetUserLevel(getUserName(),$room) >=3)) {
        echo "<TR><TD>".get_vocab("max_booking")." - <A href='javascript:centrerpopup(\"astuce1_fr.php\",600,480,\"scrollbars=yes,statusbar=no,resizable=yes\")'>Astuce</A><br><i>".get_vocab("explain_max_booking")."</i> </TD><TD>
        <input type=\"text\" name=\"max_booking\" value=\"".$row["max_booking"]."\"></TD></TR>";

    } else if ($row["max_booking"] != "-1") {
        echo "<TR><TD>".get_vocab("msg_max_booking")."</TD><TD>
        <input type=\"hidden\" name=\"max_booking\" value=\"".$row["max_booking"]."\">
        <b>".htmlspecialchars($row["max_booking"])."</b>
        </TD></TR>";
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
    echo "<TR><TD>".get_vocab("delais_max_resa_room").": </TD><TD><input type=text name=delais_max_resa_room value=\"".$row["delais_max_resa_room"]."\"></TD></TR>\n";
    // L'utilisateur ne peut pas réserver en-dessous d'un certain temps
    echo "<TR><TD>".get_vocab("delais_min_resa_room").": </TD><TD><input type=text name=delais_min_resa_room value=\"".$row["delais_min_resa_room"]."\"></TD></TR>\n";
    // L'utilisateur peut poser poser une option de réservation
    echo "<TR><TD>".get_vocab("msg_option_de_reservation").": </TD>
    <TD><input type=text name=delais_option_reservation value=\"".$row["delais_option_reservation"]."\"></TD></TR>\n";

    // L'utilisateur peut réserver dans le passé
    echo "<TR><TD>".get_vocab("allow_action_in_past")."<br><i>".get_vocab("allow_action_in_past_explain")."</i></TD><TD><input type=\"checkbox\" name=\"allow_action_in_past\" value=\"y\" ";
    if ($row["allow_action_in_past"] == 'y') echo " checked";
    echo " /></TD></tr>\n";

    // L'utilisateur ne peut pas modifier ou supprimer ses propres réservations
    echo "<TR><TD>".get_vocab("dont_allow_modify")."</TD><TD><input type=\"checkbox\" name=\"dont_allow_modify\" value=\"y\" ";
    if ($row["dont_allow_modify"] == 'y') echo " checked";
    echo " /></TD></tr>\n";

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
    ?>

    </TABLE>
    <input type=submit name="change_room"  value="<?php echo get_vocab("save") ?>">
    <?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type=\"submit\" name=\"change_done\" value=\"".get_vocab("back")."\">";
    if (@file_exists($nom_picture) && $nom_picture) {
        echo "<br><br><b>".get_vocab("Image de la ressource").get_vocab("deux_points")."</b><br><IMG SRC=\"".$nom_picture."\" BORDER=0 ALT=\"logo\">";
    } else {
        echo "<br><br><b>".get_vocab("Pas d'image disponible")."</b>";
    }
    ?>
    </CENTER>
    </form>
<?php

}
// Ajout ou modification d'un domaine
if ((!empty($area)) or (isset($add_area))) {
    if (isset($number_periodes)) {
        settype($number_periodes,"integer");
        if ($number_periodes < 1) $number_periodes = 1;
        $del_periode = grr_sql_query("delete from grr_area_periodes where id_area='".$area."'");
        $i = 0;
        $num = 0;
        while ($i < $number_periodes) {
            $temp = "periode_".$i;
            if (isset($_POST[$temp])) {
                $nom_periode = corriger_caracteres($_POST[$temp]);
                if ($nom_periode != "") {
                    $reg_periode = grr_sql_query("insert into grr_area_periodes set
                    id_area='".$area."',
                    num_periode='".$num."',
                    nom_periode='".protect_data_sql($nom_periode)."'
                    ");
                    $num++;
                }
            }
            $i++;
        }
    }

	//test le nom de l'area
	$test = grr_sql_query1("select count(id) from grr_area where area_name='".$area_name."' and id!='".$area."'");
				
    if ((isset($change_area)) AND $test == 0)
    {
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
        if ($area_name == '') $area_name = "...";
        if (isset($area)) {
            // s'il y a changement de type de créneaux, on efface les réservations du domaines
            $old_enable_periods = grr_sql_query1("select enable_periods from grr_area WHERE id='".$area."'");
            if ($old_enable_periods != $_POST['enable_periods']) {
                $del = grr_sql_query("DELETE grr_entry FROM grr_entry, grr_room, grr_area WHERE
                grr_entry.room_id = grr_room.id and
                grr_room.area_id = grr_area.id and
                grr_area.id = '".$area."'");
                $del = grr_sql_query("DELETE grr_repeat FROM grr_repeat, grr_room, grr_area WHERE
                grr_repeat.room_id = grr_room.id and
                grr_room.area_id = grr_area.id and
                grr_area.id = '".$area."'");
            }

            $sql = "UPDATE grr_area SET
            area_name='".protect_data_sql($area_name)."',
            access='".protect_data_sql($access)."',
            order_display='".protect_data_sql($area_order)."',
            ip_adr='".protect_data_sql($ip_adr)."',
            calendar_default_values = 'n',
			minute_morningstarts_area = '".protect_data_sql($_POST['minute_morningstarts_area'])."',
            morningstarts_area = '".protect_data_sql($_POST['morningstarts_area'])."',
            eveningends_area = '".protect_data_sql($_POST['eveningends_area'])."',
            resolution_area = '".protect_data_sql($_POST['resolution_area'])."',
            eveningends_minutes_area = '".protect_data_sql($_POST['eveningends_minutes_area'])."',
            weekstarts_area = '".protect_data_sql($_POST['weekstarts_area'])."',
            enable_periods = '".protect_data_sql($_POST['enable_periods'])."',
            twentyfourhour_format_area = '".protect_data_sql($_POST['twentyfourhour_format_area'])."',
            display_days = '".$display_days."',
			group_id = '".protect_data_sql($group_id)."'
            WHERE id=$area";
            if (grr_sql_command($sql) < 0) {
                fatal_error(0, get_vocab('update_area_failed') . grr_sql_error());
                $ok = 'no';
            }
        } else {
            $sql = "INSERT INTO grr_area SET
            area_name='".protect_data_sql($area_name)."',
            access='".protect_data_sql($access)."',
            order_display='".protect_data_sql($area_order)."',
            ip_adr='".protect_data_sql($ip_adr)."',
            calendar_default_values = 'n',
			minute_morningstarts_area = '".protect_data_sql($_POST['minute_morningstarts_area'])."',
            morningstarts_area = '".protect_data_sql($_POST['morningstarts_area'])."',
            eveningends_area = '".protect_data_sql($_POST['eveningends_area'])."',
            resolution_area = '".protect_data_sql($_POST['resolution_area'])."',
            eveningends_minutes_area = '".protect_data_sql($_POST['eveningends_minutes_area'])."',
            weekstarts_area = '".protect_data_sql($_POST['weekstarts_area'])."',
            enable_periods = '".protect_data_sql($_POST['enable_periods'])."',
            twentyfourhour_format_area = '".protect_data_sql($_POST['twentyfourhour_format_area'])."',
            display_days = '".$display_days."',
			group_id = '".protect_data_sql($_POST['group_id'])."'
            ";
            if (grr_sql_command($sql) < 0) fatal_error(1, "<p>" . grr_sql_error());
            $area = grr_sql_insert_id("grr_area", "id");
        }
        $msg = get_vocab("message_records");
    } else if ($test == 0){
	}else{
		$msg = "Enregistrement impossible : Un domaine portant le meme nom existe deja.";
	}
    if ($access=='a') {
        $sql = "DELETE FROM grr_j_user_area WHERE id_area='$area'";
        if (grr_sql_command($sql) < 0)
            fatal_error(0, get_vocab('update_area_failed') . grr_sql_error());
    }
/*
    if ((isset($change_area)) and (!isset($ok))) {
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
    $avertissement = get_vocab("avertissement_change_type");
    ?>
    <script  type="text/javascript" src="functions.js" language="javascript"></script>
    <SCRIPT type="text/javascript" LANGUAGE="JavaScript">
    function bascule()
    {
    //Booléen reconnaissant le navigateur
    isIE = (document.all)
    isNN6 = (!isIE) && (document.getElementById)
    //Compatibilité : l'objet menu est détecté selon le navigateur
    if (isIE) menu_1 = document.all['menu1'];
    if (isNN6) menu_1 = document.getElementById('menu1');
    if (isIE) menu_2 = document.all['menu2'];
    if (isNN6) menu_2 = document.getElementById('menu2');
    if (document.forms["main"].enable_periods[0].checked)
    {
        menu_1.style.display = "";
        menu_2.style.display = "none";
    }
    if (document.forms["main"].enable_periods[1].checked)
    {
        menu_1.style.display = "none";
        menu_2.style.display = "";
   }
   alert("<?php echo $avertissement; ?>");
    }

    </SCRIPT>


    <?php

    if (isset($area)) {
        $res = grr_sql_query("SELECT * FROM grr_area WHERE id=$area");
        if (! $res) fatal_error(0, get_vocab('error_area') . $area . get_vocab('not_found'));
        $row = grr_sql_row_keyed($res, 0);
        grr_sql_free($res);
        echo "<h2 ALIGN=CENTER>".get_vocab("editarea")."</h2>";
        if ($row["calendar_default_values"] == 'y') {
			$row["minute_morningstarts_area"] = $minute_morningstarts_area;
            $row["morningstarts_area"] = $morningstarts;
            $row["eveningends_area"] = $eveningends;
            $row["resolution_area"] = $resolution;
            $row["eveningends_minutes_area"] = $eveningends_minutes;
            $row["weekstarts_area"] = $weekstarts;
            $row["twentyfourhour_format_area"] = $twentyfourhour_format;
            $row["display_days"] = $display_days;
			$row["group_id"] = $group_id;
        }
        if ($row["enable_periods"] != 'y') $row["enable_periods"] = 'n';
    } else {
        $row["id"] = '';
        $row["area_name"] = '';
        $row["order_display"]  = '';
        $row["access"] = '';
        $row["ip_adr"] = '';
		$row["minute_morningstarts_area"] = 0;
        $row["morningstarts_area"] = $morningstarts;
        $row["eveningends_area"] = $eveningends;
        $row["resolution_area"] = $resolution;
        $row["eveningends_minutes_area"] = $eveningends_minutes;
        $row["weekstarts_area"] = $weekstarts;
        $row["twentyfourhour_format_area"] = $twentyfourhour_format;
        $row["enable_periods"] = 'n';
        $row["display_days"] = "yyyyyyy";
		$row["group_id"] = $group_id;
        echo "<h2 ALIGN=CENTER>".get_vocab('addarea')."</h2>";
    }
    ?>
    <form action="admin_edit_room.php" method="post" name="main">
    <?php
    if (isset($retour_page)) echo "<input type=\"hidden\" name=\"retour_page\" value=\"".$retour_page."\">";
    if ($row['id'] != '') echo "<input type=\"hidden\" name=\"area\" value=\"".$row["id"]."\">";
    if (isset($add_area)) echo "<input type=\"hidden\" name=\"add_area\" value=\"".$add_area."\">\n";
	
    echo "<CENTER><TABLE border=1><TR>";
    // Nom du domaine
    echo "<TD>".get_vocab("name").get_vocab("deux_points")."</TD>\n";
    echo "<TD><input type=text name=\"area_name\" value=\"".htmlspecialchars($row["area_name"])."\"></TD>\n";
    echo "</TR><TR>\n";
    // Ordre d'affichage du domaine
    echo "<TD>".get_vocab("order_display").get_vocab("deux_points")."</TD>\n";
    echo "<TD><input type=text name=\"area_order\" value=\"".htmlspecialchars($row["order_display"])."\"></TD>\n";
    echo "</TR><TR>\n";
    // Accès restreint ou non ?
    echo "<TD>".get_vocab("access").get_vocab("deux_points")."</TD>\n";
    echo "<TD><input type=checkbox name=\"access\"";
    if ($row["access"] == 'r') echo "checked";
    echo "></TD>\n";
    echo "</TR>\n";
	// Nom du groupe d'utilisateurs
	echo "<TD>".get_vocab("admin_group.php").get_vocab("deux_points")."</TD>";
	echo "<TD><select name=\"group_id\" size=\"1\">\n";
				// Recherche du group_id de l'area pour afficher
				$sql2 = "select group_id from grr_area WHERE id=$area";
				$gr = grr_sql_query1($sql2);
	$sql = "select id, group_name from grr_group order by order_display";
	$res = grr_sql_query($sql);
    while ($resultat = mysqli_fetch_row($res)) {
    echo "<option value=\"".$resultat[0]."\"";
	if ( $gr == $resultat[0]) {
	echo " SELECTED>";
	}else{ echo ">";
	}
    echo $resultat[1].'</option>'."\n";
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
    echo "</TABLE>";
    // Configuration des plages horaires ...
    echo "<H3>".get_vocab("configuration_plages_horaires")."</h3>";


    // Début de la semaine: 0 pour dimanche, 1 pou lundi, etc.
    echo "<TABLE border=1>";
    echo "<TR>\n";
    echo "<TD>".get_vocab("weekstarts_area").get_vocab("deux_points")."</TD>\n";
    echo "<TD><select name=\"weekstarts_area\" size=\"1\">\n";
    $k = 0;
    while ($k < 7) {
        $tmp=mktime(0,0,0,10,2+$k,2005);
        echo "<option value=\"".$k."\" ";
        if ($k == $row['weekstarts_area']) echo " selected";
        echo ">".utf8_strftime("%A", $tmp)."</option>\n";
        $k++;
    }
    echo "</select></TD>\n";
    echo "</TR></table>";

    // Définition des jours de la semaine à afficher sur les plannings et calendriers
    echo "<TABLE border=1>";
    echo "<TR>\n";
    echo "<TD colspan=\"7\">".get_vocab("cocher_jours_a_afficher")."</TD>\n</TR>\n";
    echo "<TR>\n";
    for ($i = 0; $i < 7; $i++)
    {
      echo "<TD><INPUT NAME=\"display_day[".$i."]\" TYPE=CHECKBOX";
      if (substr($row["display_days"],$i,1) == 'y') echo " CHECKED";
      echo " >" . day_name($i) . "</td>\n";
    }
    echo "</TR></table>";




    echo "<fieldset style=\"padding-top: 10px; padding-bottom: 10px; width: 80%; margin-left: auto; margin-right: auto;\">";
    echo "<legend style=\"font-variant: small-caps;\">".encode_message_utf8("Type de créneaux")."</legend>";
    //echo "<p style=\"text-align:left;\"><b>ATTENTION :</b> Les deux types de configuration des créneaux sont incompatibles entre eux : un changement du type de créneaux entraîne donc, après validation, un <b>effacement de toutes les réservations  de ce domaine</b></p>.";
    echo "<table border=\"0\">";
    echo "<tr><td><input type=\"radio\" name=\"enable_periods\" value=\"n\" onclick=\"bascule()\" ";
    if ($row["enable_periods"] == 'n') echo "checked";
    echo " /></td><td>".get_vocab("creneaux_de_reservation_temps")."</td></tr>";
    echo "<tr><td><input type=\"radio\" name=\"enable_periods\" value=\"y\" onclick=\"bascule()\" ";
    if ($row["enable_periods"] == 'y') echo "checked";
    echo " /></td><td>".get_vocab("creneaux_de_reservation_pre_definis")."</td></tr>";
    echo "</table>";

    //Les créneaux de réservation sont basés sur des intitulés pré-définis.
    $sql_periode = grr_sql_query("SELECT num_periode, nom_periode FROM grr_area_periodes where id_area='".$area."' order by num_periode");
    $num_periodes = grr_sql_count($sql_periode);
    if (!isset($number_periodes))
        if ($num_periodes == 0)
            $number_periodes = 10;
        else
            $number_periodes = $num_periodes;

    if ($row["enable_periods"] == 'y')
        echo "<TABLE border=\"1\" id=\"menu2\" cellpadding=\"5\">";
    else
        echo "<TABLE style=\"display:none\" border=\"1\" id=\"menu2\" cellpadding=\"5\">";
    echo "<TR><TD><i>".get_vocab("nombre_de_creneaux").get_vocab("deux_points")."</i></TD>";
    echo "<td><select name=\"number_periodes\" size=\"1\">\n";

    $j = 1;
    while ($j < 51) {
        echo "<option ";
        if ($j == $number_periodes) echo " selected ";
        echo ">".($j)."</option>\n";
        $j++;
    }
    echo "</select></td></TR>\n";
    $i = 0;
    while ($i < $number_periodes) {
        $nom_periode = grr_sql_query1("select nom_periode FROM grr_area_periodes where id_area='".$area."' and num_periode= '".$i."'");
        if ($nom_periode == -1) $nom_periode = "";
        echo "<TR><TD>".get_vocab("intitule_creneau").($i+1)."</TD>";
        echo "<td><input type=\"text\" name=\"periode_".$i."\" value=\"".htmlentities($nom_periode)."\" size=\"30\" /></td></TR>\n";
        $i++;
    }
    echo "</table>";

    // Cas ou les créneaux de réservations sont basés sur le temps
    if ($row["enable_periods"] == 'n')
        echo "<TABLE border=\"1\" id=\"menu1\" cellpadding=\"5\">";
    else
        echo "<TABLE style=\"display:none\" border=\"1\" id=\"menu1\" cellpadding=\"5\">";
    // Heure de début de réservation
    echo "<TR>";
    echo "<TD>".get_vocab("morningstarts_area").get_vocab("deux_points")."</TD>\n";
    echo "<TD><select name=\"morningstarts_area\" size=\"1\">\n";
    $k = 0;
    while ($k < 24) {
        echo "<option value=\"".$k."\" ";
        if ($k == $row['morningstarts_area']) echo " selected";
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
    echo "</select></TD>\n";
    echo "</TR>";

    // Heure de fin de réservation
    echo "<TR>\n";
    echo "<TD>".get_vocab("eveningends_area").get_vocab("deux_points")."</TD>\n";
    echo "<TD><select name=\"eveningends_area\" size=\"1\">\n";
    $k = 0;
    while ($k < 24) {
        echo "<option value=\"".$k."\" ";
        if ($k == $row['eveningends_area']) echo " selected";
        echo ">".$k."</option>\n";
        $k++;
    }
    echo "</select></TD>\n";
    echo "</TR>";

    // Minutes à ajouter à l'heure $eveningends pour avoir la fin réelle d'une journée.
    echo "<TR>\n";
    echo "<TD>".get_vocab("eveningends_minutes_area").get_vocab("deux_points")."</TD>\n";
    echo "<TD><input type=text name=\"eveningends_minutes_area\" value=\"".htmlspecialchars($row["eveningends_minutes_area"])."\"></TD>\n";
    echo "</TR>";

    // Resolution - quel bloc peut être réservé, en secondes
    echo "<TR>\n";
    echo "<TD>".get_vocab("resolution_area").get_vocab("deux_points")."</TD>\n";
    echo "<TD><input type=text name=\"resolution_area\" value=\"".htmlspecialchars($row["resolution_area"])."\"></TD>\n";
    echo "</TR><TR>\n";
    echo "</TR>";

    // Format d'affichage du temps : valeur 0 pour un affichage « 12 heures » et valeur 1 pour un affichage  « 24 heure ».
    echo "<TR>\n";
    echo "<TD>".get_vocab("twentyfourhour_format_area").get_vocab("deux_points")."</TD>\n";
    echo "<td><table><tr><td>";
    echo get_vocab("twentyfourhour_format_12")."</td><td><input type=\"radio\" name=\"twentyfourhour_format_area\" value=\"0\" ";
    if ($row['twentyfourhour_format_area'] == 0) echo " checked";
    echo " /></td></tr><tr><td>";
    echo get_vocab("twentyfourhour_format_24")."</td><td><input type=\"radio\" name=\"twentyfourhour_format_area\" value=\"1\" ";
    if ($row['twentyfourhour_format_area'] == 1) echo " checked";
    echo " /></td></tr></table>";
    echo "</td>";
    echo "</tr>";

    echo "</table>";
    echo "</fieldset>";

    echo "<input type=submit name=\"change_area\" value=\"".get_vocab("save")."\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
    echo "<input type=submit name=\"change_done\" value=\"".get_vocab("back")."\">\n";
    echo "</CENTER></form>";
    if (OPTION_IP_ADR==1) {
       echo "<br>".get_vocab("ip_adr_explain");
    }

 } ?>
</body>
</html>