<?php
#########################################################################
#                            csv .php                                   #
#                                                                       #
#            script  de constitution du fichiers CSV                    #
#            du résumé des réservations                                 #
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
header("Content-disposition: filename=rapport.csv");

// Paramètres langage
include "include/language.inc.php";

   #Si nous ne savons pas la date, nous devons la créer
if(!isset($day) or !isset($month) or !isset($year))
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
}

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
$loginmatch = isset($_GET["loginmatch"]) ? urldecode($_GET["loginmatch"]) : NULL;
$typematch = isset($_GET["typematch"]) ? urldecode($_GET["typematch"]) : NULL;

$sumby = isset($_GET["sumby"]) ? $_GET["sumby"] : NULL;

    #Récupération des informations relatives au serveur.
$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
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
        . " FROM grr_entry e, grr_area a, grr_room r, grr_type_area t"
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
        echo unhtmlentities($vocab["summarize_by"]);
        if ($sumby=="c")
            echo unhtmlentities($vocab["sum_by_creator"]).str_replace("&nbsp;", " ",$vocab["deux_points"]).$day." ".$month." ".$year;
        else
            echo unhtmlentities($vocab["sum_by_descrip"]).str_replace("&nbsp;", " ",$vocab["deux_points"]).$day." ".$month." ".$year;
        if ($areamatch != null or $roommatch != null or $namematch != null or $descrmatch != null)
        {
            echo unhtmlentities($vocab["enrecherchant"]);
            echo "$areamatch $roommatch $namematch $descrmatch - $From_day/$From_month/$From_year -> $To_day/$To_month/$To_year";
        }
        echo "\r\n";
        echo unhtmlentities($vocab["createdby"]).";".unhtmlentities($vocab["areas"]).";".unhtmlentities($vocab["room"]).unhtmlentities(str_replace("&nbsp;", " ",$vocab["deux_points"])).";".unhtmlentities($vocab["description"]).";".unhtmlentities($vocab["time"])." - ".unhtmlentities($vocab["duration"]).";".unhtmlentities($vocab["namebooker"]).unhtmlentities(str_replace("&nbsp;", " ",$vocab["deux_points"])).";".unhtmlentities($vocab["match_descr"]).";".unhtmlentities($vocab["lastupdate"]).";\n";
    }
for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
    #Affichage de "crée par" et de la date de la dernière mise à jour
    echo htmlspecialchars($row[6]) . ";";
    #Area
    echo htmlspecialchars(removeMailUnicode($row[8])) . ";";
    #Ressource
    echo htmlspecialchars(removeMailUnicode($row[9])) . ";";
    #Description de la ressource
    echo htmlspecialchars(removeMailUnicode($row[10])) . ";";
    // Récupération des données concernant l'affichage du planning du domaine
    get_planning_area_values($row[11]);

    #Affichage de l'heure et de la durée de réservation
    if ($enable_periods=='y')
        echo describe_period_span($row[1], $row[2]) . ";";
    else
        echo describe_span($row[1], $row[2],$dformat) . ";";
    #Destination
    echo htmlspecialchars(removeMailUnicode($row[3])) . ";";
    #Description de la réservation
    $texte=str_replace(CHR(10)," ",removeMailUnicode($row[4]));
    $texte=str_replace(CHR(13)," ",$texte);
    echo ltrim(rtrim(htmlspecialchars($texte))) . ";";

    #Date derniere modif
    echo date_time_string($row[7],$dformat) . ";";
    echo "\r\n";
    }
?>