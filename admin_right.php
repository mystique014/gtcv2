<?php
#########################################################################
#                           admin_right.php                             #
#                                                                       #
#                       Interface de gestion des                        #
#                  droits de gestion des utilisateurs                   #
#                                                                       #
#                  Derni�re modification : 01/12/2005                   #
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
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];

$day   = date("d");
$month = date("m");
$year  = date("Y");

if(authGetUserLevel(getUserName(),-1,'area') < 4)
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

# print the page header
print_header("","","","",$type="with_session", $page="admin");
// Affichage de la colonne de gauche
include "admin_col_gauche.php";


$reg_admin_login = isset($_GET["reg_admin_login"]) ? $_GET["reg_admin_login"] : NULL;
$action = isset($_GET["action"]) ? $_GET["action"] : NULL;
$msg='';

if ($reg_admin_login) {
    // On commence par v�rifier que le professeur n'est pas d�j� pr�sent dans cette liste.
    if ($room !=-1) {
        // Ressource
        // On v�rifie que la ressource $room existe
        $test = grr_sql_query1("select id from grr_room where id='".$room."'");
        if ($test == -1) {
            showAccessDenied($day, $month, $year, $area,$back);
            exit();
        }
        // La ressource existe : on v�rifie les privil�ges de l'utilisateur
        if(authGetUserLevel(getUserName(),$room) < 4)
        {
            showAccessDenied($day, $month, $year, $area,$back);
            exit();
        }

        $sql = "SELECT * FROM grr_j_user_room WHERE (login = '$reg_admin_login' and id_room = '$room')";
        $res = grr_sql_query($sql);
        $test = grr_sql_count($res);
        if ($test != "0") {
            $msg = get_vocab("warning_exist");
        } else {
            if ($reg_admin_login != '') {
                $sql = "INSERT INTO grr_j_user_room SET login= '$reg_admin_login', id_room = '$room'";
                if (grr_sql_command($sql) < 0) {fatal_error(1, "<p>" . grr_sql_error());}  else {$msg=get_vocab("add_user_succeed");}
            }
        }
    } else {
        // Domaine
        // On v�rifie que le domaine $area existe
        $test = grr_sql_query1("select id from grr_area where id='".$area."'");
        if ($test == -1) {
            showAccessDenied($day, $month, $year, $area,$back);
            exit();
        }
        // Le domaine existe : on v�rifie les privil�ges de l'utilisateur
        if(authGetUserLevel(getUserName(),$area,'area') < 4)
        {
            showAccessDenied($day, $month, $year, $area,$back);
            exit();
        }

        $sql = "select id from grr_room where area_id=$area";
        $res = grr_sql_query($sql);
        if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
            $sql2 = "select login from grr_j_user_room where (login = '$reg_admin_login' and id_room = '$row[0]')";
            $res2 = grr_sql_query($sql2);
            $nb = grr_sql_count($res2);
            if ($nb==0) {
                $sql3 = "insert into grr_j_user_room (login, id_room) values ('$reg_admin_login',$row[0])";
                if (grr_sql_command($sql3) < 0) {fatal_error(1, "<p>" . grr_sql_error());}  else {$msg=get_vocab("add_user_succeed");}
            }
        }
    }
}

if ($action) {
    if ($action == "del_admin") {
        if(authGetUserLevel(getUserName(),$room) < 4)
        {
            showAccessDenied($day, $month, $year, $area,$back);
            exit();
        }

        unset($login_admin); $login_admin = $_GET["login_admin"];
        $sql = "DELETE FROM grr_j_user_room WHERE (login='$login_admin' and id_room = '$room')";
        if (grr_sql_command($sql) < 0) {fatal_error(1, "<p>" . grr_sql_error());} else {$msg=get_vocab("del_user_succeed");}
    }
    if ($action == "del_admin_all") {
        if(authGetUserLevel(getUserName(),$area,'area') < 4)
        {
            showAccessDenied($day, $month, $year, $area,$back);
            exit();
        }

        $sql = "select id from grr_room where area_id=$area order by room_name";
        $res = grr_sql_query($sql);
        if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
            $sql2 = "DELETE FROM grr_j_user_room WHERE (login='".$_GET['login_admin']."' and id_room = '$row[0]')";
            if (grr_sql_command($sql2) < 0) {fatal_error(1, "<p>" . grr_sql_error());} else {$msg=get_vocab("del_user_succeed");}
        }
    }

}

if ((empty($area)) and (isset($row[0]))) {
    if(authGetUserLevel(getUserName(),$row[0],'area') >= 5) $area = get_default_area();
    else {
    # Retourne le domaine par d�faut; Utilis� si aucun domaine n'a �t� d�fini.
// On cherche le premier domaine � acc�s non restreint
    $area = grr_sql_query1("SELECT a.id FROM grr_area a, grr_j_useradmin_area j
    WHERE a.id=j.id_area and j.login='".getUserName()."'
    ORDER BY a.access, a.order_display, a.area_name
    LIMIT 1");
    }
}
if (empty($room)) $room = -1;

echo "<h2>".get_vocab('admin_right.php')."</h2>";
echo "<p><i>".get_vocab("admin_right_explain")."</i></p>";
echo "<noscript>";
echo "<font color='red'>$msg</font>";
echo "</noscript>";
if (($msg) and (!($javascript_info_admin_disabled)))  {
    echo "<script type=\"text/javascript\" language=\"javascript\">";
    echo "<!--\n";
    echo " alert(\"".$msg."\")";
    echo "//-->";
    echo "</script>";
}

# Table with areas, rooms.
echo "<table><tr>";
$this_area_name = "";
$this_room_name = "";
# Show all areas
echo "<td ><p><b>".get_vocab("areas")."</b></p>";
$out_html = "<form name=\"area\"><select name=\"area\" onChange=\"area_go()\">";
$out_html .= "<option value=\"admin_right.php?area=-1\">".get_vocab('select');
    $sql = "select id, area_name from grr_area order by order_display";
    $res = grr_sql_query($sql);
    if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
        $selected = ($row[0] == $area) ? "selected" : "";
        $link = "admin_right.php?area=$row[0]";
        // On affiche uniquement les domaines administr�s par l'utilisateur
        if(authGetUserLevel(getUserName(),$row[0],'area') >= 4)
            $out_html .= "<option $selected value=\"$link\">" . htmlspecialchars($row[1]);
    }
    $out_html .= "</select>
    <SCRIPT type=\"text/javascript\" language=\"JavaScript\">
    <!--
    function area_go()
    {
    box = document.forms[\"area\"].area;
    destination = box.options[box.selectedIndex].value;
    if (destination) location.href = destination;
    }
    // -->
    </SCRIPT>
    <noscript>
    <input type=submit value=\"Change\">
    </noscript>
    </form>";

echo $out_html;


$this_area_name = grr_sql_query1("select area_name from grr_area where id=$area");
$this_room_name = grr_sql_query1("select room_name from grr_room where id=$room");
$this_room_name_des = grr_sql_query1("select description from grr_room where id=$room");
echo "</td>\n";

# Show all rooms in the current area
echo "<td><p><b>".get_vocab('rooms')."</b></p>";

# should we show a drop-down for the room list, or not?
$out_html = "<form name=\"room\"><select name=\"room\" onChange=\"room_go()\">";
$out_html .= "<option value=\"admin_right.php?area=$area&amp;room=-1\">".get_vocab('select_all');

    $sql = "select id, room_name, description from grr_room where area_id=$area order by order_display,room_name";
    $res = grr_sql_query($sql);
    if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
        if ($row[2]) {$temp = " (".htmlspecialchars($row[2]).")";} else {$temp="";}
        $selected = ($row[0] == $room) ? "selected" : "";
        $link = "admin_right.php?area=$area&amp;room=$row[0]";
        $out_html .= "<option $selected value=\"$link\">" . htmlspecialchars($row[1].$temp);
    }
    $out_html .= "</select>
       <SCRIPT type=\"text/javascript\" language=\"JavaScript\">
       <!--
       function room_go()
        {
        box = document.forms[\"room\"].room;
        destination = box.options[box.selectedIndex].value;
        if (destination) location.href = destination;
        }
        // -->
        </SCRIPT>

        <noscript>
        <input type=submit value=\"Change\">
        </noscript>
        </form>";

echo $out_html;
echo "</td>\n";
echo "</tr></table>\n";

# Don't continue if this area has no rooms:
if ($area <= 0)
{
    echo "<h1>".get_vocab("no_area")."</h1>";
    exit;
}
# Show area and room:
if ($this_room_name_des!='-1') {$this_room_name_des = " (".$this_room_name_des.")";} else {$this_room_name_des='';}
echo "<table border=1 cellpadding=5><tr><td>";
if ($room!='-1') {
    echo "<h3>".get_vocab("administration1")."</h3>";
    echo "<p>$this_room_name $this_room_name_des</p>\n";
} else {
    $is_admin='yes';
    echo "<h3>".get_vocab("administration2")."</h3>";
    $sql = "select id, room_name, description from grr_room where area_id=$area order by order_display,room_name";
    $res = grr_sql_query($sql);
    if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
        if ($row[2]) {$temp = " ($row[2])";} else {$temp="";}
        echo $row[1].$temp."<br>";
    }
}
?>
</td><td>
<?php
if ($room != -1) {
    $sql = "SELECT u.login, u.nom, u.prenom FROM grr_utilisateurs u, grr_j_user_room j WHERE (j.id_room='$room' and u.login=j.login)  order by u.nom, u.prenom";
    $res = grr_sql_query($sql);
    $nombre = grr_sql_count($res);
    if ($nombre!=0) echo "<h3>".get_vocab("user_list")."</h3>";
    if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
        $login_admin = $row[0];
        $nom_admin = $row[1];
        $prenom_admin = $row[2];
        echo "<b>";
        echo "$nom_admin $prenom_admin</b> | <a href='admin_right.php?action=del_admin&amp;login_admin=$login_admin&amp;room=$room&amp;area=$area'><font size=2>".get_vocab("delete")."</font></a><br>";
    }
    if ($nombre == 0) {
        echo "<h3><font color = red>".get_vocab("no_admin")."</font></h3>";
    }
} else {
    $exist_admin='no';
    $sql = "select login, nom, prenom from grr_utilisateurs where statut='utilisateur'";
    $res = grr_sql_query($sql);
    if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
        $is_admin='yes';
        $sql2 = "select id, room_name, description from grr_room where area_id=$area order by order_display,room_name";
        $res2 = grr_sql_query($sql2);
        if ($res2) {
            $test = grr_sql_count($res2);
            if ($test != 0) {
                for ($j = 0; ($row2 = grr_sql_row($res2, $j)); $j++)
                {
                $sql3 = "SELECT login FROM grr_j_user_room WHERE (id_room='".$row2[0]."' and login='".$row[0]."')";
                $res3 = grr_sql_query($sql3);
                $nombre = grr_sql_count($res3);
                if ($nombre==0) $is_admin='no';
                }
            } else {
                $is_admin='no';
            }
        }

        if ($is_admin=='yes') {
            if ($exist_admin=='no') {
                echo "<h3>".get_vocab("user_list")."</h3>";
                $exist_admin='yes';
            }
            echo "<b>";
            echo "$row[1] $row[2]</b> | <a href='admin_right.php?action=del_admin_all&amp;login_admin=$row[0]&amp;area=$area'><font size=2>".get_vocab("delete")."</font></a><br>";
        }
    }
    if ($exist_admin=='no') {
        echo "<h3><font color = red>".get_vocab("no_admin_all")."</font></h3>";
    }
}
?>
<h3><?php echo get_vocab("add_user_to_list");?></h3>
<form  action="admin_right.php" method='GET'>
<select size=1 name=reg_admin_login>
<option value=''><p><?php echo get_vocab("nobody"); ?></p></option>;
<?php
$sql = "SELECT login, nom, prenom FROM grr_utilisateurs WHERE  (etat!='inactif' and statut='utilisateur') order by nom, prenom";
$res = grr_sql_query($sql);
if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++) {
    if (authUserAccesArea($row[0],$area) <= 2) {
        echo "<option value=$row[0]><p>$row[1]  $row[2] </p></option>";
    }
}
?>
</select>
<input type=hidden name=area value="<?php echo $area;?>">
<input type=hidden name=room value=<?php echo $room;?>>
<input type=submit value='Enregistrer'>
</form>
</td></tr></table>

<?php
// fin de l'affichage de la colonne de droite
echo "</td></tr></table>";
?>
</body>
</html>