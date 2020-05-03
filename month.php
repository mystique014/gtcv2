<?php
#########################################################################
#                            month.php                                  #
#                                                                       #
#            Interface d'accueil avec affichage par mois                #
#            Dernière modification : 16/09/2006                         #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 * D'après http://mrbs.sourceforge.net/
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
include "include/connect.inc.php";
include "include/config.inc.php";
include "include/functions.inc.php";
include "include/$dbsys.inc.php";
include "include/mincals.inc.php";
    #Settings
require_once("./include/settings.inc.php");
        #Chargement des valeurs de la table settings
if (!loadSettings())
    die("Erreur chargement settings");

    #Fonction relative à la session
require_once("./include/session.inc.php");
    #Si il n'y a pas de session crée et que l'identification est requise, on déconnecte l'utilisateur.
if ((!grr_resumeSession())and ($authentification_obli==1))
{
    header("Location: ./logout.php?auto=1");
    die();
};

if (empty($area))
    $area = get_default_area();
if (empty($room))
    $room = grr_sql_query1("select min(id) from grr_room where area_id=$area");
    #Si il n'y a pas de room, $room va être a -1

// Récupération des données concernant l'affichage du planning du domaine
get_planning_area_values($area);

// Paramètres langage
include "include/language.inc.php";

// On affiche le lien "format imprimable" en bas de la page
$affiche_pview = '1';
if (!isset($_GET['pview'])) $_GET['pview'] = 0; else $_GET['pview'] = 1;

    #Paramètres par défaut
if (empty($debug_flag)) $debug_flag = 0;
if (empty($month) || empty($year) || !checkdate($month, 1, $year))
{
    $month = date("m");
    $year  = date("Y");
}
$day = 1;
    #Renseigne la session de l'utilisateur, sans identification ou avec identification.
if (($authentification_obli==0) and (!isset($_SESSION['login'])))
{
    $session_login = '';
    $session_statut = '';
    $type_session = "no_session";
}
else
{
    $session_login = $_SESSION['login'];
    $session_statut = $_SESSION['statut'];
    $type_session = "with_session";
}
    #Récupération des informations relatives au serveur.
$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
    #Renseigne les droits de l'utilisateur, si les droits sont insufisants, l'utilisateur est avertit.
if (check_begin_end_bookings($day, $month, $year))
{
    showNoBookings($day, $month, $year, $area,$back,$type_session );
    exit();
}
if((authGetUserLevel(getUserName(),-1) < 1) and ($authentification_obli==1))
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}
if(authUserAccesArea($session_login, $area)==0)
{
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}
    #Fonction de comparaison, retourne "<" "=" ou ">"
function cmp3($a, $b)
{
    if ($a < $b) return "< ";
    if ($a == $b) return "= ";
    return "> ";
}

// On vérifie une fois par jour si le délai de confirmation des réservations est dépassé
// Si oui, les réservations concernées sont supprimées et un mail automatique est envoyé.
if ((!isset($verif_reservation_auto)) or ($verif_reservation_auto == 0))
    verify_confirm_reservation();

    #Affiche les informations dans l'header
print_header($day, $month, $year, $area, $type_session);

 #Affiche le calendrier des 3 mois
    $cal = isset($_GET["cal"]) ? $_GET["cal"] : NULL;
	if ($cal == 1)
	{
	echo'<div class="row">'.PHP_EOL;
	echo'<div class="col-md-12 center">'.PHP_EOL;
    echo "<table width=\"100%\" cellspacing=1 border=0><tr>\n<td>";
	minicals($year, $month, $day, $area, -1, 'day');
	echo "</table><table width=\"100%\" cellspacing=1 border=0>\n";

	echo'</div>'.PHP_EOL;
	echo'</div>'.PHP_EOL;
	}
// Affichage d'un message pop-up
if (!($javascript_info_disabled)) {
    echo "<script type=\"text/javascript\" language=\"javascript\">";
    if (isset($_SESSION['displ_msg']))  echo " alert(\"".get_vocab("message_records")."\")";
    echo "</script>";
}
unset ($_SESSION['displ_msg']);

    #Heure de dénut du mois, cela ne sert à rien de reprndre les valeur morningstarts/eveningends
$month_start = mktime(0, 0, 0, $month, 1, $year);
    #Dans quel colonne l'affichage commence: 0 veut dire $weekstarts
$weekday_start = (date("w", $month_start) - $weekstarts + 7) % 7;
$days_in_month = date("t", $month_start);
$month_end = mktime(23, 59, 59, $month, $days_in_month, $year);

if ($enable_periods=='y') {
    $resolution = 60;
    $morningstarts = 12;
    $eveningends = 12;
    $eveningends_minutes = count($periods_name)-1;
}
//Création d'une row pour le lien montrer/cacher le header
echo'<div class="container-fluid">'.PHP_EOL;
echo'<div class="row">'.PHP_EOL;
echo'<div class="col-md-12 center">'.PHP_EOL;
$v= mktime(0,0,0,$month,$day,$year);
$yea = date("Y",$v);
$mm = date("m",$v);
$dd = date("d",$v);

if ($cal == 1)
{
echo "</td><td align='center'><a href=\"month.php?year=$yea&amp;month=$mm&amp;day=$dd&amp;area=$area&amp;room=$room&amp;cal=0\">Cacher le calendrier</a></td></tr></table>\n";
} else {
echo "</td><td align='center'><a href=\"month.php?year=$yea&amp;month=$mm&amp;day=$dd&amp;area=$area&amp;room=$room&amp;cal=1\">Afficher le calendrier</a></td></tr></table>\n";
}  
echo'</div>'.PHP_EOL;
echo'</div>'.PHP_EOL;
echo'</div>'.PHP_EOL;
// Si format imprimable ($_GET['pview'] = 1), on n'affiche pas cette partie
if ($_GET['pview'] != 1) {
	echo'<div class="container-fluid">'.PHP_EOL;
	
    #Table avec areas, rooms, minicals.
    
    $this_area_name = "";
    $this_room_name = "";
    if (isset($_SESSION['default_list_type']) or ($authentification_obli==1))
        $area_list_format = $_SESSION['default_list_type'];
    else
        $area_list_format = getSettingValue("area_list_format");
        #Affiche une liste déroulante ou bien un liste HTML
    if ($area_list_format != "list")
    {
        echo'<div class="row">'.PHP_EOL;
		echo'<div class="col-xs-6 left">'.PHP_EOL;
		echo make_area_select_html($type_month_all.'.php', $area, $year, $month, $day, $session_login); # from functions.inc.php
		echo'</div>'.PHP_EOL;
		echo'<div class="col-xs-3 left">'.PHP_EOL;
        echo make_room_select_html('month', $area, $room, $year, $month, $day);
		echo'</div>'.PHP_EOL;
    }
    else
    {
        //echo "<table cellspacing=15><tr><td>";
		//echo "<table width=\"100%\" cellspacing=1 border=1><tr><td>";
		echo'<div class="row">'.PHP_EOL;
		echo'<div class="col-xs-6 left">'.PHP_EOL;       
		echo make_area_list_html($type_month_all.'.php', $area, $year, $month, $day, $session_login); # from functions.inc.php
        #Montre toutes les rooms du domaine affiché
        //echo "</td><td>";
        make_room_list_html('month.php', $area, $room, $year, $month, $day);
        //echo "</td></tr></table>";
		echo'</div>'.PHP_EOL;
		echo'</div>'.PHP_EOL;
    }
    //echo "</td>\n";
	
	echo'</div>'.PHP_EOL;
	
}
$this_area_name = grr_sql_query1("select area_name from grr_area where id=$area");
$this_room_name = grr_sql_query1("select room_name from grr_room where id=$room");
$this_room_name_des = grr_sql_query1("select description from grr_room where id=$room");

    #O,n arrête si il n'y a pas de room dans cet area
if ($room <= 0)
{
    echo "<h1>".get_vocab("no_rooms_for_area")."</h1>";
    include "include/trailer.inc.php";
    exit;
}
    #Affiche le mois, l'année, la room et l'area
if (($this_room_name_des) and ($this_room_name_des!="-1"))
    $this_room_name_des = " (".$this_room_name_des.")";
else
    $this_room_name_des = "";

echo "<td VALIGN=MIDDLE><h4 align=center>" . ucfirst(utf8_strftime("%B %Y", $month_start))
  . " - ".ucfirst($this_area_name)." - $this_room_name $this_room_name_des</h2>\n";
  


# Show Go to month before and after links
#y? are year and month of the previous month.
#t? are year and month of the next month.

$i= mktime(0,0,0,$month-1,1,$year);
$yy = date("Y",$i);
$ym = date("n",$i);

$i= mktime(0,0,0,$month+1,1,$year);
$ty = date("Y",$i);
$tm = date("n",$i);
// Si format imprimable ($_GET['pview'] = 1), on n'affiche pas cette partie
if ($_GET['pview'] != 1) {
    echo "<table width=\"100%\"><tr><td>
      <a href=\"month.php?year=$yy&amp;month=$ym&amp;area=$area&amp;room=$room\">
      &lt;&lt; ".get_vocab("monthbefore")."</a></td>
      <td>&nbsp;</td>
      <td align=right><a href=\"month.php?year=$ty&amp;month=$tm&amp;area=$area&amp;room=$room\">
      ".get_vocab("monthafter")." &gt;&gt;</a></td></tr></table>";
}
if ($debug_flag)
    echo "<p>DEBUG: month=$month year=$year start=$weekday_start range=$month_start:$month_end\n";
    #Remplace l'espace pour qu'il n'y ai pas de problèmes
$all_day = str_replace(" ", "&nbsp;", get_vocab("all_day"));
    #Récupérer toutes les réservations pour le mois de la room affichée
    # row[0] = Début de réservation
    # row[1] = Fin de réservation
    # row[2] = ID de la réservation
    # row[3] = Nom de la réservation
    # row[4] = Auteur de la réservation
    # row[5] = Description complète
$sql = "SELECT start_time, end_time, id, name, create_by, description, type
   FROM grr_entry
   WHERE room_id=$room
   AND start_time <= $month_end AND end_time > $month_start
   ORDER by 1";
    # Contruit un array des informations de chaques jours dans le mois
    # Ces informations sont sauvegardées:
    #  d[monthday]["id"][] = ID de chaque réservation, pour le lien
    #  d[monthday]["data"][] = Début et fin pour chaque réservation
$res = grr_sql_query($sql);
if (! $res)
    echo grr_sql_error();
else for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
{
    $sql_creator = "SELECT prenom, nom FROM grr_utilisateurs WHERE login = '$row[4]'";
    $res_creator = grr_sql_query($sql_creator);
    if ($res_creator)
        $row_user = grr_sql_row($res_creator, 0);
    if ($debug_flag)
        echo "<br>DEBUG: result $i, id $row[2], starts $row[0], ends $row[1]\n";
    #Remplir tous les jours ou cette réservation s'opère

    // début de la première réservation trouvée
    $t = max((int)$row[0], $month_start);
    // fin de la première réservation trouvée
    $end_t = min((int)$row[1], $month_end);
    // numéro du jour de la première réservation
    $day_num = date("j", $t);
    // On fixe le début de la journée ($midnight)
    if ($enable_periods == 'y')
        $midnight = mktime(12,0,0,$month,$day_num,$year);
    else
        $midnight = mktime(0, 0, 0, $month, $day_num, $year);
    while ($t < $end_t)
    {
        if ($debug_flag) echo "<br>DEBUG: Entry $row[2] day $day_num\n";
        $d[$day_num]["id"][] = $row[2];
        // Info-bulle
        if (!isset($display_info_bulle)) $display_info_bulle = "";
        if ($display_info_bulle == 1)
            $d[$day_num]["who"][] = get_vocab("created_by").$row_user[0]." ".$row_user[1];
        else if ($display_info_bulle == 2)
            $d[$day_num]["who"][] = $row[5];
        else
            $d[$day_num]["who"][] = "";

        $d[$day_num]["who1"][] = $row[3];
        $d[$day_num]["color"][] = $row[6];
        if ((isset($display_full_description)) and ($display_full_description==1))
            $d[$day_num]["description"][] = $row[5];
        // On incrémente de 24 h = 86400 secondes
        $midnight_tonight = $midnight + 86400;

        #Début et fin pour tous les jours
        #9 cas: Début < = ou > minuit
        #       Fin < = ou > minuit
        #Utiliser ~ (pas -) pour séparer l'heure de début et de fin (MSIE)
        if ($enable_periods == 'y') {
              $start_str = str_replace(" ", "&nbsp;", period_time_string($row[0]));
              $end_str   = str_replace(" ", "&nbsp;", period_time_string($row[1], -1));
              switch (cmp3($row[0], $midnight) . cmp3($row[1], $midnight_tonight))
              {
            case "> < ":         # Starts after midnight, ends before midnight
            case "= < ":         # Starts at midnight, ends before midnight
                    if ($start_str == $end_str)
                        $d[$day_num]["data"][] = $start_str;
                    else
                        $d[$day_num]["data"][] = $start_str . "~" . $end_str;
                    break;
            case "> = ":         # Starts after midnight, ends at midnight
                    $d[$day_num]["data"][] = $start_str . "~24:00";
                    break;
            case "> > ":         # Starts after midnight, continues tomorrow
                    $d[$day_num]["data"][] = $start_str . "~====&gt;";
                    break;
            case "= = ":         # Starts at midnight, ends at midnight
                    $d[$day_num]["data"][] = $all_day;
                    break;
            case "= > ":         # Starts at midnight, continues tomorrow
                    $d[$day_num]["data"][] = $all_day . "====&gt;";
                    break;
            case "< < ":         # Starts before today, ends before midnight
                    $d[$day_num]["data"][] = "&lt;====~" . $end_str;
                    break;
            case "< = ":         # Starts before today, ends at midnight
                    $d[$day_num]["data"][] = "&lt;====" . $all_day;
                    break;
            case "< > ":         # Starts before today, continues tomorrow
                    $d[$day_num]["data"][] = "&lt;====" . $all_day . "====&gt;";
                    break;
              }

        } else {
        switch (cmp3($row[0], $midnight) . cmp3($row[1], $midnight_tonight))
        {
            case "> < ":            #Début après minuit, fin avant minuit
            case "= < ":            #Début à minuit, fin avant minuit
                $d[$day_num]["data"][] = date(hour_min_format(), $row[0]) . "~" . date(hour_min_format(), $row[1]);
                break;
            case "> = ":            #Début après minuit, fin à minuit
                $d[$day_num]["data"][] = date(hour_min_format(), $row[0]) . "~24:00";
                break;
            case "> > ":            #Début après minuit, continue le lendemain
                $d[$day_num]["data"][] = date(hour_min_format(), $row[0]) . "~====&gt;";
                break;
            case "= = ":            #Début à minuit, fin à minuit
                $d[$day_num]["data"][] = $all_day;
                break;
            case "= > ":            #Début à minuit, continue le lendemain
                $d[$day_num]["data"][] = $all_day . "====&gt;";
                break;
            case "< < ":            #Début avant aujourdhui, fin avant minuit
                $d[$day_num]["data"][] = "&lt;====~" . date(hour_min_format(), $row[1]);
                break;
            case "< = ":            #Début avant aujourd'hui', fin à minuit
                $d[$day_num]["data"][] = "&lt;====" . $all_day;
                break;
            case "< > ":            #Début avant aujourd'hui', continue le lendemain
                $d[$day_num]["data"][] = "&lt;====" . $all_day . "====&gt;";
                break;
        }
        }
        #Seulement si l'heure de fin est pares minuit, on continue le jour prochain.
        if ($row[1] <= $midnight_tonight) break;
        $day_num++;
        $t = $midnight = $midnight_tonight;
    }
}
if ($debug_flag)
{
    echo "<p>DEBUG: Array of month day data:<p><pre>\n";
    for ($i = 1; $i <= $days_in_month; $i++)
    {
        if (isset($d[$i]["id"]))
        {
            $n = count($d[$i]["id"]);
            echo "Day $i has $n entries:\n";
            for ($j = 0; $j < $n; $j++)
                echo "  ID: " . $d[$i]["id"][$j] .
                    " Data: " . $d[$i]["data"][$j] . "\n";
        }
    }
    echo "</pre>\n";
}
echo "<table class='table text-center' border=1 width=\"100%\">\n<tr>";
    #Affichage des jours en entête
for ($weekcol = 0; $weekcol < 7; $weekcol++)
{
    $num_week_day = ($weekcol + $weekstarts)%7;
    if ($display_day[$num_week_day] == 1)  // on n'affiche pas tous les jours de la semaine
    echo "<th class='text-center' width=\"14%\">" . day_name(($weekcol + $weekstarts)%7) . "</th>";
}
echo "</tr><tr>\n";
    #Ne pas tenir compte des jours avant le début du mois
for ($weekcol = 0; $weekcol < $weekday_start; $weekcol++)
{
    $num_week_day = ($weekcol + $weekstarts)%7;
    if ($display_day[$num_week_day] == 1)  // on n'affiche pas tous les jours de la semaine
        echo "<td class=\"cell_month_o\" height=100>&nbsp;</td>\n";
}
    #Afficher le jour du mois
for ($cday = 1; $cday <= $days_in_month; $cday++)
{
    $num_week_day = ($weekcol + $weekstarts)%7;
    if ($weekcol == 0) echo "</tr><tr>\n";
    if ($display_day[$num_week_day] == 1) {// début condition "on n'affiche pas tous les jours de la semaine"
    echo "<td valign=top height=100 class=\"cell_month\"><div class=\"monthday\"><a title=\"".htmlspecialchars(get_vocab("see_all_the_rooms_for_the_day"))."\"   href=\"day.php?year=$year&amp;month=$month&amp;day=$cday&amp;area=$area\">$cday</a></div>\n";
    if (est_hors_reservation(mktime(0,0,0,$month,$cday,$year)))
            echo "<center><img src=\"img_grr/stop.png\" border=\"0\" alt=\"".get_vocab("reservation_impossible")."\"  title=\"".get_vocab("reservation_impossible")."\" width=\"16\" height=\"16\" /></center>";

    # Anything to display for this day?
    if (isset($d[$cday]["id"][0]))
    {
        echo "<font size=-2>";
        $n = count($d[$cday]["id"]);
        #Affiche l'heure de début et de fin, 2 par lignes avec lien pour voie la reservation
        #Si il y en a plus que 123, on affiche "..." après le 11ème
        for ($i = 0; $i < $n; $i++)
        {
            if ($i == 11 && $n > 12)
            {
                echo " ...\n";
                break;
            }
            if ($i > 0) echo "<br>"; else echo " ";
            echo "<b>";
            echo span_bgground($d[$cday]["color"][0]);
            echo $d[$cday]["data"][$i]
                . "<br>"
          . "<a title=\"".htmlspecialchars($d[$cday]["who"][$i])."\" href=\"view_entry.php?id=" . $d[$cday]["id"][$i]
          . "&amp;day=$cday&amp;month=$month&amp;year=$year&amp;page=month\">"
                    . htmlspecialchars($d[$cday]["who1"][$i])
          . "</a>";
          if ((isset($display_full_description)) and ($display_full_description==1) and ($d[$cday]["description"][$i]!= ""))
              echo "<br><i>(".$d[$cday]["description"][$i].")</i>";
          echo "<br></span></b>";
        }
        echo "</font>";

    }
    echo "</td>\n";
    } // fin condition "on n'affiche pas tous les jours de la semaine"
    if (++$weekcol == 7) $weekcol = 0;
}
    #Ne tiens pas en compte les journées après le derbier jour du mois
if ($weekcol > 0) for (; $weekcol < 7; $weekcol++)
{
    $num_week_day = ($weekcol + $weekstarts)%7;
    if ($display_day[$num_week_day] == 1)  // on n'affiche pas tous les jours de la semaine
        echo "<td class=\"cell_month_o\" height=100>&nbsp;</td>\n";
}
echo "</tr></table>\n";
include "include/trailer.inc.php";
?>