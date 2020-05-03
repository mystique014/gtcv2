<?php
#########################################################################
#                    admin_calendar.php                                 #
#                                                                       #
#            interface permettant la la réservation en bloc             #
#                  de journées entières                                 #
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

include "include/admin.inc.php";
include "include/misc.inc.php";
include "include/mrbs_sql.inc.php";


$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];

$day   = date("d");
$month = date("m");
$year  = date("Y");

function getDaysInMonth($month, $year)
{
    if ($month < 1 || $month > 12)
    {
        return 0;
    }
    $days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $d = $days[$month - 1];
    if ($month == 2)
    {
        // Check for leap year
        // Forget the 4000 rule, I doubt I'll be around then...
        if ($year%4 == 0)
        {
            if ($year%100 == 0)
            {
                if ($year%400 == 0)
                {
                    $d = 29;
                }
            } else {
                $d = 29;
            }
        }
    }
    return $d;
}

function getFirstDays()
{
    global $weekstarts;
    $basetime = mktime(12,0,0,6,11+$weekstarts,2000);
    for ($i = 0, $s = ""; $i < 7; $i++)
    {
        $show = $basetime + ($i * 24 * 60 * 60);
        $fl = strftime('%a',$show);
        $s .= "<td align=center valign=top class=\"calendarHeader2\">$fl</td>\n";
    }
    return $s;
}


function cal($month, $year)
{
    global $weekstarts;
    if (!isset($weekstarts)) $weekstarts = 0;
    $s = "";
    $daysInMonth = getDaysInMonth($month, $year);
    $date = mktime(12, 0, 0, $month, 1, $year);
    $first = (strftime("%w",$date) + 7 - $weekstarts) % 7;
    $monthName = utf8_strftime("%B",$date);
    $s .= "<table class=\"calendar2\" border=1 cellspacing=3>\n";
    $s .= "<tr>\n";
    $s .= "<td align=center valign=top class=\"calendarHeader2\" colspan=7>$monthName&nbsp;$year</td>\n";
    $s .= "</tr>\n";
    $s .= "<tr>\n";
    $s .= getFirstDays();
    $s .= "</tr>\n";
    $d = 1 - $first;
    while ($d <= $daysInMonth)
    {
        $s .= "<tr>\n";
        for ($i = 0; $i < 7; $i++)
        {
            $basetime = mktime(12,0,0,6,11+$weekstarts,2000);
            $show = $basetime + ($i * 24 * 60 * 60);
            $nameday = utf8_strftime('%A',$show);

            $s .= "<td class=\"calendar2\" align=center valign=top>";
            if ($d > 0 && $d <= $daysInMonth)
            {
                $s .= $d;
                $temp = mktime(0,0,0,$month,$d,$year);
                // On teste si le jour est férié :
                $test = grr_sql_query1("select DAY from grr_calendar where DAY = '".$temp."'");
                if ($test == '-1')
                    $s .= "<br><INPUT TYPE=\"checkbox\" NAME=\"$temp\" VALUE=\"$nameday\" >";
                else
                    $s .= "<br><INPUT TYPE=\"checkbox\" name=\"$temp\" value=\"$nameday\"  disabled />";
            } else {
                $s .= "&nbsp;";
            }
            $s .= "</td>\n";
            $d++;
        }
        $s .= "</tr>\n";
    }
    $s .= "</table>\n";
    return $s;
}




if(authGetUserLevel(getUserName(),-1) < 5)
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

// Initialisation
$etape = isset($_POST["etape"]) ? $_POST["etape"] : NULL;
$areas = isset($_POST["areas"]) ? $_POST["areas"] : NULL;
$rooms = isset($_POST["rooms"]) ? $_POST["rooms"] : NULL;
$name = isset($_POST["name"]) ? $_POST["name"] : NULL;
$description = isset($_POST["description"]) ? $_POST["description"] : NULL;
$type = isset($_POST["type"]) ? $_POST["type"] : NULL;
$type_resa = isset($_POST["type_resa"]) ? $_POST["type_resa"] : NULL;

# print the page header
print_header($day, $month, $year, $area);
// Affichage de la colonne de gauche
include "admin_col_gauche.php";
?>
<script src="functions.js" type="text/javascript" language="javascript"></script>
<?php

echo "<h2>".get_vocab('admin_calendar_title.php')."</h2>";


if (isset($_POST['record']) and  ($_POST['record'] == 'yes')) {
    $etape = 4;
    $result = 0;
    $end_bookings = getSettingValue("end_bookings");
    // On reconstitue le tableau des ressources
    $sql = "select id from grr_room";
    $res = grr_sql_query($sql);
    if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
        $temp = "id_room_".$row[0];
        if (isset($_POST[$temp])) {
            // La ressource est selectionnée
//            $rooms[] = $id;
            // On récupère les données du domaine
            $area_id = grr_sql_query1("SELECT area_id FROM grr_room WHERE id = '".$row[0]."'");
            get_planning_area_values($area_id);
            $n = getSettingValue("begin_bookings");
            $month = strftime("%m", getSettingValue("begin_bookings"));
            $year = strftime("%Y", getSettingValue("begin_bookings"));
            $day = 1;
            while ($n <= $end_bookings) {
                $daysInMonth = getDaysInMonth($month, $year);
                $day = 1;
                while ($day <= $daysInMonth) {
                    $n = mktime(0,0,0,$month,$day,$year);
                    if (isset($_POST[$n])) {
                        // Le jour a été selectionné dans le calendrier
                        $starttime = mktime($morningstarts, 0, 0, $month, $day  , $year);
                        $endtime   = mktime($eveningends, 0, $resolution, $month, $day, $year);
                        // On efface toutes les résa en conflit
                        $result += grrDelEntryInConflict($row[0], $starttime, $endtime, 0, 0, 1);
                        // S'il s'agit d'une action de réservation, on réserve !
                        if ($type_resa == "resa") {
                            // Par sécurité, on teste quand même s'il reste des conflits
                            $err = mrbsCheckFree($row[0], $starttime, $endtime, 0,0);
                            if (!$err) {
                                mrbsCreateSingleEntry($starttime, $endtime, 0, 0, $row[0], getUserName(), $name, $type, $description, 0,array());

                            }
                        }
                    }
                    $day++;
                }
                $month++;
                if ($month == 13) {
                    $year++;
                    $month = 1;
                }
            }
        }
    }
}

if ($etape==4) {
    if ($result == '') $result = 0;
    if ($type_resa == "resa") {
        echo "<center><H3>".get_vocab("reservation_en_bloc")."</H3></center>";
        echo "<H3>".get_vocab("reservation_en_bloc_result")."</H3>";
        if ($result != 0) echo "<p>".get_vocab("reservation_en_bloc_result2")."<b>".$result."</b></p>";
    } else {
        echo "<center><H3><font color=\"#FF0000\">".get_vocab("suppression_en_bloc")."</font></H3></center>";
        echo "<h3>".get_vocab("suppression_en_bloc_result")."<b>".$result."</b></h3>";
    }

}
if ($etape==3) {
    // Etape N° 3
    echo "<center><H3>".get_vocab("etape_n")."3/3</H3></center>";
    if ($type_resa == "resa")
        echo "<center><H3>".get_vocab("reservation_en_bloc")."</H3></center>";
    else
        echo "<center><H3><font color=\"#FF0000\">".get_vocab("suppression_en_bloc")."</font></H3></center>";

    if (!isset($rooms)) {
        echo "<h3>".get_vocab("noarea")."</h3>";
        // fin de l'affichage de la colonne de droite
        echo "</td></tr></table>";
        echo "</body></html>";
        die();
    }

    echo "<table cellpadding=\"3\">\n";
    $basetime = mktime(12,0,0,6,11+$weekstarts,2000);
    for ($i = 0; $i < 7; $i++)
    {
        $show = $basetime + ($i * 24 * 60 * 60);
        $lday = utf8_strftime('%A',$show);
        echo "<tr>\n";
        echo "<td><span class='small'><a href='admin_calend.php' onclick=\"setCheckboxesGrr('formulaire', true, '$lday' ); return false;\">".get_vocab("check_all_the").$lday."s</a></span></td>\n";
        echo "<td><span class='small'><a href='admin_calend.php' onclick=\"setCheckboxesGrr('formulaire', false, '$lday' ); return false;\">".get_vocab("uncheck_all_the").$lday."s</a></span></td>\n";
        echo "</tr>\n";
    }
    echo "<tr>\n<td><span class='small'><a href='admin_calend.php' onclick=\"setCheckboxesGrr('formulaire', false, 'all'); return false;\">".get_vocab("uncheck_all_")."</a></span></td>\n";
    echo "<td></td></tr>\n";
    echo "</table>\n";
    echo "<form action=\"admin_calend.php\" method=\"post\" name=\"formulaire\">\n";
    echo "<table cellspacing=20>\n";

    $n = getSettingValue("begin_bookings");
    $end_bookings = getSettingValue("end_bookings");

    $debligne = 1;
    $month = strftime("%m", getSettingValue("begin_bookings"));
    $year = strftime("%Y", getSettingValue("begin_bookings"));

    while ($n <= $end_bookings) {
        if ($debligne == 1) {
            echo "<tr>\n";
            $inc = 0;
            $debligne = 0;
        }
        $inc++;
        echo "<td>\n";
        echo cal($month, $year);
        echo "</td>";
        if ($inc == 3) {
            echo "</tr>";
            $debligne = 1;
        }
        $month++;
        if ($month == 13) {
            $year++;
            $month = 1;
        }
        $n = mktime(0,0,0,$month,1,$year);
    }
    echo "</table>";
    echo "<input type=\"submit\" name=\"".get_vocab('save')."\" />\n";
    echo "<input type=\"hidden\" name=\"record\" value=\"yes\" />\n";
    echo "<input type=\"hidden\" name=\"etape\" value=\"4\" />\n";
    echo "<input type=\"hidden\" name=\"name\" value=\"".$name."\" />\n";
    echo "<input type=\"hidden\" name=\"description\" value=\"".$description."\" />\n";
    echo "<input type=\"hidden\" name=\"type\" value=\"".$type."\" />\n";
    echo "<INPUT TYPE=\"hidden\" name=\"type_resa\" value=\"".$type_resa."\" />\n";
    foreach ( $rooms as $room_id ) {
        $temp = "id_room_".$room_id;
        echo "<input type=\"hidden\" name=\"".$temp."\" value=\"yes\" />\n";
    }

    echo "</form>";
} else if ($etape==2) {
    // Etape 2
    ?>
    <SCRIPT  type="text/javascript"  LANGUAGE="JavaScript">
    <?php
    if ($type_resa == "resa") {
    ?>
    function validate_and_submit ()
    {
    if(document.forms["main"].name.value == "")
    {
    alert ( "<?php echo get_vocab('you_have_not_entered') . '\n' . get_vocab('brief_description') ?>");
    return false;
    }
    if  (document.forms["main"].elements[2].value =='')
    {
    alert("<?php echo get_vocab("choose_a_room"); ?>");
    return false;
    }
    if  (document.forms["main"].type.value=='0')
    {
    alert("<?php echo get_vocab("choose_a_type"); ?>");
    return false;
    }
    document.forms["main"].submit();
    return true;
    }
    <?php
    } else {
    ?>

    function validate_and_submit ()
    {
    if  (document.forms["main"].elements[0].value =='')
    {
    alert("<?php echo get_vocab("choose_a_room"); ?>");
    return false;
    }
    document.forms["main"].submit();
    return true;
    }
    <?php
    }
    ?>
    </SCRIPT>
    <?php

    echo "<center><H3>".get_vocab("etape_n")."2/3</H3></center>";
    if ($type_resa == "resa")
        echo "<center><H3>".get_vocab("reservation_en_bloc")."</H3></center>";
    else
        echo "<center><H3><font color=\"#FF0000\">".get_vocab("suppression_en_bloc")."</font></H3></center>";

    if (!isset($areas)) {
        echo "<h3>".get_vocab("noarea")."</h3>";
        // fin de l'affichage de la colonne de droite
        echo "</td></tr></table>";
        echo "</body></html>";
        die();
    }

    // Choix des ressources
    echo "<p><FORM action=\"admin_calend.php\" method=\"post\" name=\"main\">";
    echo "<TABLE BORDER=0>\n";
    if ($type_resa == "resa") {
      echo "<TR><TD CLASS=CR><B>".get_vocab("namebooker").get_vocab("deux_points")."</B></TD>\n";
      echo "<TD CLASS=CL><INPUT NAME=\"name\" SIZE=\"40\" VALUE=\"\"></TD></TR>";
      echo "<TR><TD CLASS=TR><B>".get_vocab("fulldescription")."</B></TD>\n";
      echo "<TD CLASS=TL><TEXTAREA NAME=\"description\" ROWS=\"8\" COLS=\"40\" WRAP=\"virtual\"></TEXTAREA></TD></TR>";
/*
    echo "<TR><TD CLASS=CR><B>".get_vocab("time")."</B></TD>\n";
    echo "<TD CLASS=CL><INPUT NAME=\"hour\" SIZE=\"2\" VALUE=\"";
    if (!$twentyfourhour_format && ($start_hour > 12)){ echo ($start_hour - 12);} else { echo $start_hour;}
    echo "\" MAXLENGTH=\"2\">:<INPUT NAME=\"minute\" SIZE=\"2\" VALUE=\"".$start_min."\" MAXLENGTH=\"2\">";
    if (!$twentyfourhour_format)
    {
       $checked = ($start_hour < 12) ? "checked" : "";
      echo "<INPUT NAME=\"ampm\" type=\"radio\" value=\"am\" $checked>".date("a",mktime(1,0,0,1,1,1970));
      $checked = ($start_hour >= 12) ? "checked" : "";
      echo "<INPUT NAME=\"ampm\" type=\"radio\" value=\"pm\" $checked>".date("a",mktime(13,0,0,1,1,1970));
    }
    echo "</TD></TR>\n";
    echo "<TR><TD CLASS=CR><B>".get_vocab("duration")."</B></TD>\n";
    echo "<TD CLASS=CL><INPUT NAME=\"duration\" SIZE=\"7\" VALUE=\"".$duration."\">";
    echo "<SELECT NAME=\"dur_units\">";
    $units = array("minutes", "hours", "days", "weeks");
    while (list(,$unit) = each($units))
    {
        echo "<OPTION VALUE=$unit";
        if ($dur_units ==  get_vocab($unit)) echo " SELECTED";
        echo ">".strtolower(get_vocab($unit));
    }
    echo "</SELECT>\n";
    $fin_jour = $eveningends;
    $minute = $resolution/60;
    if ($minute == 60) {
        $fin_jour = $fin_jour+1;
        $af_fin_jour = $fin_jour." H";
    } else if ($minute != 0) {
        $af_fin_jour = $fin_jour." H ".$minute;
    }
    echo "<INPUT NAME=\"all_day\" TYPE=\"checkbox\" VALUE=\"yes\">".get_vocab("all_day")." (".$morningstarts." H - ".$af_fin_jour.")";
    echo "</TD></TR>\n";
*/
    }
    echo "<tr><td class=CR><b>".get_vocab("rooms").get_vocab("deux_points")."</b></td>\n";
    echo "<td class=CL valign=top><table border=0><tr><td>";
    echo "<select name=\"rooms[]\" multiple>";
    foreach ( $areas as $area_id ) {
        # then select the rooms in that area
        $sql = "select id, room_name from grr_room where area_id=$area_id order by order_display,room_name";
        $res = grr_sql_query($sql);
        if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
            echo "<option value=\"".$row[0]."\">".$row[1];
        }

    }
    echo "</select></td><td>".get_vocab("ctrl_click")."</td></tr></table>\n";
    echo "</td></tr>\n";
    if ($type_resa == "resa") {
      echo "<TR><TD CLASS=CR><B>".get_vocab("type").get_vocab("deux_points")."</B></TD>\n";
      echo "<TD CLASS=CL><SELECT NAME=\"type\">\n";
      echo "<OPTION VALUE='0'>".get_vocab("choose")."\n";
      $sql = "SELECT t.type_name, t.type_letter FROM grr_type_area t
      LEFT JOIN grr_j_type_area j on j.id_type=t.id
      WHERE (j.id_area  IS NULL or (";
      $ind = 0;
      foreach ( $areas as $area_id ) {
          if ($ind != 0) $sql .= " and ";
          $sql .= "j.id_area != '".$area_id."'";
          $ind = 1;
      }
      $sql .= "))
      ORDER BY order_display";
      $res = grr_sql_query($sql);
      if ($res) {
      for ($i = 0; ($row = grr_sql_row($res, $i)); $i++) {
        echo "<OPTION VALUE=\"".$row[1]."\" ";
        if ($type == $row[1]) echo " SELECTED";
        echo " >".$row[0]."</option>\n";
        }
      }
      echo "</SELECT></TD></TR>";
    }
    echo "</table>\n";
    echo "<INPUT TYPE=\"hidden\" name=\"etape\" value=\"3\" />\n";
    echo "<INPUT TYPE=\"hidden\" name=\"type_resa\" value=\"".$type_resa."\" />\n";
    ?>
    <SCRIPT  type="text/javascript" LANGUAGE="JavaScript">
    document.writeln ( '<center><INPUT TYPE="button" VALUE="<?php echo get_vocab("submit")?>" ONCLICK="validate_and_submit()"></center>' );
    </SCRIPT>
    <NOSCRIPT>
    <INPUT TYPE="submit" VALUE="<?php echo get_vocab("submit")?>">
    </NOSCRIPT>
    <?php
    echo "</FORM>";

} else if (!$etape) {
    // Etape 1 :
    echo "<p>".get_vocab("admin_calendar_explain_1.php")."</p>";
    echo "<center><H3>".get_vocab("etape_n")."1/3</H3></center>";
    // Choix des domaines
    echo "<FORM action=\"admin_calend.php\" method=\"post\">\n";
    echo "<table border=\"1\"><tr><td>\n";
    echo "<p><b>".get_vocab("choix_domaines")."</b></p>";
    echo "<select name=\"areas[]\" multiple>\n";
    $sql = "select id, area_name from grr_area order by order_display, area_name";
    $res = grr_sql_query($sql);
    if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
        echo "<option value=\"".$row[0]."\">".$row[1]."</option>\n";
    }
    echo "</select><br>".get_vocab("ctrl_click");
    echo "</td><td>";
    echo "<p><b>".get_vocab("choix_action")."</b></p>";
    echo "<table><tr>";
    echo "<td><input type=\"radio\" name=\"type_resa\" value=\"resa\" checked /></td>\n";
    echo "<td>".get_vocab("reservation_en_bloc")."</td>\n";
    echo "</tr><tr>\n";
    echo "<td><input type=\"radio\" name=\"type_resa\" value=\"suppression\" /></td>\n";
    echo "<td>".get_vocab("suppression_en_bloc")."</td>\n";
    echo "</tr></table>\n";
    echo "</td></tr></table>\n";
    echo "<INPUT TYPE=\"hidden\" name=\"etape\" value=\"2\" />\n";
    echo "<center><INPUT type=\"submit\" name=\"Continuer\" value=\"".get_vocab("submit")."\" /></center>\n";
    echo "</FORM>\n";
}

// fin de l'affichage de la colonne de droite
echo "</td></tr></table>";

?>


</body>
</html>