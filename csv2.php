<?php
#########################################################################
#                            csv2.php                                   #
#                                                                       #
#            script  de constitution du fichiers CSV                    #
#            du rapport des réservations                                #
#            Dernière modification : 18/05/2006                         #
#                                                                       #
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
include "include/connect.inc.php";
include "include/config.inc.php";
include "include/functions.inc.php";
include "include/$dbsys.inc.php";
    #Paramètres de connection
require_once("./include/settings.inc.php");
    #Chargement des valeurs de la table settings
if (!loadSettings())
    die("Erreur chargement settings");

    #Fonction relative à la session
require_once("./include/session.inc.php");
    #Si il n'y a pas de session crée et que l'identification est requise, on déconnecte l'utilisateur.
if ((!grr_resumeSession())and ($authentification_obli==1)) {
    header("Location: ./logout.php?auto=1");
    die();
};
    #Renseigne les droits de l'utilisateur, si les droits sont insufisants, l'utilisateur est deconnecté.
if(authGetUserLevel(getUserName(),-1) < 2)
{
    header("Location: ./logout.php?auto=1");
    exit();
}

header("Content-Type: application/csv-tab-delimited-table");
header("Content-disposition: filename=resume.csv");

// Paramètres langage
include "include/language.inc.php";

   #Si nous ne savons pas la date, nous devons la créer
if(!isset($day) or !isset($month) or !isset($year))
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
}
    #Récupération des informations relatives au serveur.
$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];

// Initialisation
$report_end = isset($_GET["report_end"]) ? $_GET["report_end"] : NULL;
if (isset($report_end )) settype ($report_end ,"integer"); else die();
$report_start = isset($_GET["report_start"]) ? $_GET["report_start"] : NULL;
if (isset($report_start )) settype ($report_start ,"integer");  else die();
$From_day = isset($_GET["From_day"]) ? $_GET["From_day"] : NULL;
if (isset($From_day )) settype ($From_day ,"integer");
$From_month = isset($_GET["From_month"]) ? $_GET["From_month"] : NULL;
if (isset($From_month)) settype ($From_month,"integer");
$From_year = isset($_GET["From_year"]) ? $_GET["From_year"] : NULL;
if (isset($From_year)) settype ($From_year,"integer");
$To_day = isset($_GET["To_day"]) ? $_GET["To_day"] : NULL;
if (isset($To_day)) settype ($To_day,"integer");
$To_month = isset($_GET["To_month"]) ? $_GET["To_month"] : NULL;
if (isset($To_month)) settype ($To_month,"integer");
$To_year = isset($_GET["To_year"]) ? $_GET["To_year"] : NULL;
if (isset($To_year)) settype ($To_year,"integer");
$areamatch = isset($_GET["areamatch"]) ? urldecode($_GET["areamatch"]) : NULL;
$roommatch = isset($_GET["roommatch"]) ? urldecode($_GET["roommatch"]) : NULL;
$namematch = isset($_GET["namematch"]) ? urldecode($_GET["namematch"]) : NULL;
$descrmatch = isset($_GET["descrmatch"]) ? urldecode($_GET["descrmatch"]) : NULL;
$sumby = isset($_GET["sumby"]) ? $_GET["sumby"] : NULL;
$typematch = isset($_GET["typematch"]) ? urldecode($_GET["typematch"]) : NULL;
$loginmatch = isset($_GET["loginmatch"]) ? urldecode($_GET["loginmatch"]) : NULL;

    #Renseigne les droits de l'utilisateur, si les droits sont insufisants, l'utilisateur est avertit.
#   La requête SQL va contenir les colonnes suivantes:
# Col Index  Description:
#   1  [0]   Entry ID, Non affiché
#   2  [1]   Date de début (Unix)
#   3  [2]   Date de fin (Unix)
#   4  [3]   Descrition brêve,(HTML)
#   5  [4]   Description,(HTML)
#   6  [5]   Type
#   7  [6]   Créer par (nom ou IP), (HTML)
#   8  [7]   Timestamp (création), (Unix)
#   9  [8]   Area (HTML)
#  10  [9]   Room (HTML)
#  11  [10]  Room description
    $sql = "SELECT e.id, e.start_time, e.end_time, e.name, e.description, "
        . "e.type, e.create_by, "
        .  grr_sql_syntax_timestamp_to_unix("e.timestamp")
        . ", a.area_name, r.room_name, r.description, a.id"
        . " FROM ".$_COOKIE["table_prefix"]."_entry e, ".$_COOKIE["table_prefix"]."_area a, ".$_COOKIE["table_prefix"]."_room r, ".$_COOKIE["table_prefix"]."_type_area t"
        . " WHERE e.room_id = r.id AND r.area_id = a.id"
        . " AND e.start_time < $report_end AND e.end_time > $report_start";
    if (!empty($areamatch))
        $sql .= " AND" .  grr_sql_syntax_caseless_contains("a.area_name", unslashes($areamatch));
    if (!empty($roommatch))
        $sql .= " AND" .  grr_sql_syntax_caseless_contains("r.room_name", unslashes($roommatch));
    if ((!empty($typematch)) or ($sortby == 't'))
        $sql .= " AND" .  grr_sql_syntax_caseless_contains("t.type_name", $typematch)." AND t.type_letter = e.type ";
    else
        $sql .= " AND t.type_letter = e.type ";
    if (!empty($namematch))
        $sql .= " AND" .  grr_sql_syntax_caseless_contains("e.name", unslashes($namematch));
    if (!empty($descrmatch))
        $sql .= " AND" .  grr_sql_syntax_caseless_contains("e.description", unslashes($descrmatch));
    if (!empty($loginmatch))
        $sql .= " AND" .  grr_sql_syntax_caseless_contains("e.create_by", unslashes($loginmatch));

    #Trié par: Area, room, debut, date/heure.
    $sql .= " ORDER BY 9,r.order_display,10,2";
    $res = grr_sql_query($sql);
    if (! $res) fatal_error(0, grr_sql_error());
    $nmatch = grr_sql_count($res);
    if ($nmatch == 0)
    {
        echo "<P><B>" . unhtmlentities($vocab["nothing_found"]) . "</B>\r\n";
        grr_sql_free($res);
    }
    else
    {

        if ($sumby=="c")
            echo unhtmlentities($vocab["summarize_by"])." " .unhtmlentities($vocab["sum_by_creator"])." - $day $month $year;";
        else
            echo unhtmlentities($vocab["summarize_by"])." " .unhtmlentities($vocab["sum_by_descrip"])." - $day $month $year;";
        if ($areamatch != null or $roommatch != null or $namematch != null or $descrmatch != null)
        {
            echo unhtmlentities($vocab["enrecherchant"]);
            echo "$areamatch $roommatch $namematch $descrmatch - $From_day/$From_month/$From_year -> $To_day/$To_month/$To_year";
        }
        echo "\r\n";
    }
    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
        // Récupération des données concernant l'affichage du planning du domaine
        get_planning_area_values($row[11]);
        if ($enable_periods=='y') {
            // pour le décompte des créneaux
            accumulate_periods($row, $count1, $hours1, $report_start, $report_end, $room_hash1, $name_hash1);
            $do_sum1 = 'y';
        } else {
            // pour le décompte des heures
            accumulate($row, $count2, $hours2, $report_start, $report_end, $room_hash2, $name_hash2);
            $do_sum2 = 'y';
        }
        // pour le décompte des réservations
        accumulate($row, $count, $hours, $report_start, $report_end, $room_hash, $name_hash);
    }

    // Décompte des heures (cas ou $enable_periods != 'y')
    if (isset($do_sum1)) {
        echo "\r\n".unhtmlentities($vocab["summary_header"])." - ".unhtmlentities($vocab["summarize_by"])."\r\n";
        do_summary($count1, $hours1, $room_hash1, $name_hash1);
    }
    // Décompte des créneaux (cas ou $enable_periods == 'y')
    if (isset($do_sum2)) {
        echo "\r\n".unhtmlentities($vocab["summary_header_per"])." - ".unhtmlentities($vocab["summarize_by"])."\r\n";
        do_summary($count2, $hours2, $room_hash2, $name_hash2);
    }
    // Décompte des réservations
    echo "\r\n\r\n\r\n".unhtmlentities($vocab["summary_header_resa"])." - ".unhtmlentities($vocab["summarize_by"])."\r\n";
    do_summary2($count, $hours, $room_hash, $name_hash);


function accumulate(&$row, &$count, &$hours, $report_start, $report_end,
    &$room_hash, &$name_hash)
{
    global $sumby;
    #Description "Créer par":
    $name = htmlspecialchars($row[($sumby == "d" ? 3 : 6)]);
    #Area et room:
    $room = htmlspecialchars(removeMailUnicode($row[8])) . " " . htmlspecialchars(removeMailUnicode($row[9])) . " " . htmlspecialchars(removeMailUnicode($row[10]));
    #Ajoute le nombre de réservations pour cette "room" et nom.
    @$count[$room][$name]++;
    #Ajoute le nombre d'heure ou la ressource est utilisée.
    @$hours[$room][$name] += (min((int)$row[2], $report_end)
        - max((int)$row[1], $report_start)) / 3600.0;
    $room_hash[$room] = 1;
    $name_hash[$name] = 1;
}

function accumulate_periods(&$row, &$count, &$hours, $report_start, $report_end,
    &$room_hash, &$name_hash)
{
    global $sumby;
        global $periods_name;
        $max_periods = count($periods_name);

    # Use brief description or created by as the name:
    $name = htmlspecialchars($row[($sumby == "d" ? 3 : 6)]);
    # Area and room separated by break:
    $room = htmlspecialchars($row[9]) . " " . htmlspecialchars($row[10]);
    # Accumulate the number of bookings for this room and name:
    @$count[$room][$name]++;
    # Accumulate hours used, clipped to report range dates:
        $dur = (min((int)$row[2], $report_end) - max((int)$row[1], $report_start))/60;
    if ($dur < (24*60))
        @$hours[$room][$name] += $dur;
    else
        @$hours[$room][$name] += ($dur % $max_periods) + floor( $dur/(24*60) ) * $max_periods;
    $room_hash[$room] = 1;
    $name_hash[$name] = 1;
}


// Décompte des heures
function cell1($count, $hours)
{
    echo "". sprintf("%.2f", $hours) . ";";
}
// Décompte des réservation
function cell2($count, $hours)
{
    echo "$count;";
}
// Table de résumé. $count et $hours sont 2 rangées indexées par [area/room][name].
// $room_hash & $name_hash sont des rangées avec des indexes appelant des rooms et noms uniques.
// Décompte des heures
function do_summary(&$count, &$hours, &$room_hash, &$name_hash)
{
    echo" ;";
    global $vocab;
        #Array de area/room et des noms pour utiliser pour la colonne et l'indexe.
    reset($room_hash);
    while (list($room_key) = each($room_hash)) $rooms[] = $room_key;
    ksort($rooms);
    reset($name_hash);
    while (list($name_key) = each($name_hash)) $names[] = $name_key;
    ksort($names);
    $n_rooms = sizeof($rooms);
    $n_names = sizeof($names);
    for ($c = 0; $c < $n_rooms; $c++)
    {
        echo "$rooms[$c];";
        $col_count_total[$c] = 0;
        $col_hours_total[$c] = 0.0;
    }
    echo unhtmlentities($vocab['total']).";\r\n";
    $grand_count_total = 0;
    $grand_hours_total = 0;

    for ($r = 0; $r < $n_names; $r++)
    {
        $row_count_total = 0;
        $row_hours_total = 0.0;
        $name = $names[$r];
        if ($name!=null)
            echo "$name;";
        else echo "Salles;";
        for ($c = 0; $c < $n_rooms; $c++)
        {
            $room = $rooms[$c];
            if (isset($count[$room][$name]))
            {
                $count_val = $count[$room][$name];
                $hours_val = $hours[$room][$name];
                cell1($count_val, $hours_val);
                $row_count_total += $count_val;
                $row_hours_total += $hours_val;
                $col_count_total[$c] += $count_val;
                $col_hours_total[$c] += $hours_val;
            }
            else
                echo ";";
        }
        cell1($row_count_total, $row_hours_total);
        echo "\r\n";
        $grand_count_total += $row_count_total;
        $grand_hours_total += $row_hours_total;
    }
    echo unhtmlentities($vocab['total']).";";
    for ($c = 0; $c < $n_rooms; $c++)
        cell1($col_count_total[$c], $col_hours_total[$c]);
    cell1($grand_count_total, $grand_hours_total);
}
// Table de résumé. $count et $hours sont 2 rangées indexées par [area/room][name].
// $room_hash & $name_hash sont des rangées avec des indexes appelant des rooms et noms uniques.
// Décompte des réservations
function do_summary2(&$count, &$hours, &$room_hash, &$name_hash)
{
    echo" ;";
    global $vocab;
        #Array de area/room et des noms pour utiliser pour la colonne et l'indexe.
    reset($room_hash);
    while (list($room_key) = each($room_hash)) $rooms[] = $room_key;
    ksort($rooms);
    reset($name_hash);
    while (list($name_key) = each($name_hash)) $names[] = $name_key;
    ksort($names);
    $n_rooms = sizeof($rooms);
    $n_names = sizeof($names);
    for ($c = 0; $c < $n_rooms; $c++)
    {
        echo "$rooms[$c];";
        $col_count_total[$c] = 0;
        $col_hours_total[$c] = 0.0;
    }
    echo unhtmlentities($vocab['total']).";\r\n";
    $grand_count_total = 0;
    $grand_hours_total = 0;

    for ($r = 0; $r < $n_names; $r++)
    {
        $row_count_total = 0;
        $row_hours_total = 0.0;
        $name = $names[$r];
        if ($name!=null)
            echo "$name;";
        else echo "Salles;";
        for ($c = 0; $c < $n_rooms; $c++)
        {
            $room = $rooms[$c];
            if (isset($count[$room][$name]))
            {
                $count_val = $count[$room][$name];
                $hours_val = $hours[$room][$name];
                cell2($count_val, $hours_val);
                $row_count_total += $count_val;
                $row_hours_total += $hours_val;
                $col_count_total[$c] += $count_val;
                $col_hours_total[$c] += $hours_val;
            }
            else
                echo ";";
        }
        cell2($row_count_total, $row_hours_total);
        echo "\r\n";
        $grand_count_total += $row_count_total;
        $grand_hours_total += $row_hours_total;
    }
    echo unhtmlentities($vocab['total']).";";
    for ($c = 0; $c < $n_rooms; $c++)
        cell2($col_count_total[$c], $col_hours_total[$c]);
    cell2($grand_count_total, $grand_hours_total);
}
?>