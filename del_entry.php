<?php
#########################################################################
#                          del_entry.php                                #
#                                                                       #
#                  Interface de suppresssion d'une r�servation          #
#                                                                       #
#                  Derni�re modification : 10/07/2006                   #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 * D'apr�s http://mrbs.sourceforge.net/
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
include "include/mrbs_sql.inc.php";
// Settings
require_once("./include/settings.inc.php");
//Chargement des valeurs de la table settingS
if (!loadSettings())
    die("Erreur chargement settings");

// Session related functions
require_once("./include/session.inc.php");

// Resume session
if (!grr_resumeSession()) {
    header("Location: ./logout.php?auto=1");
    die();
};

// Param�tres langage
include "include/language.inc.php";

$series = isset($_GET["series"]) ? $_GET["series"] : NULL;
if (isset($series)) settype($series,"integer");
$page = verif_page();
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    settype($id,"integer");
} else {
    die();
}

if($info = mrbsGetEntryInfo($id))
{
    $day   = strftime("%d", $info["start_time"]);
    $month = strftime("%m", $info["start_time"]);
    $year  = strftime("%Y", $info["start_time"]);

    $area  = mrbsGetRoomArea($info["room_id"]);
    $back = "";
    if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
    if(authGetUserLevel(getUserName(),-1) < 2)
    {
        showAccessDenied($day, $month, $year, $area,$back);
        exit();
    }
    if(!getWritable($info["create_by"], getUserName(),$id))
    {
        showAccessDenied($day, $month, $year, $area,$back);
        exit;
    }
    if(authUserAccesArea($_SESSION['login'], $area)==0)
    {
        showAccessDenied($day, $month, $year, $area,$back);
        exit();
    }

    grr_sql_begin();
    if (getSettingValue("automatic_mail") == 'yes') {
        echo send_mail($id,3,$dformat);
    }

    $result = mrbsDelEntry(getUserName(), $id, $series, 1);
    grr_sql_commit();
    if ($result)
    {
        $_SESSION['displ_msg'] = 'yes';
        Header("Location: ".$page.".php?day=$day&month=$month&year=$year&area=$area&room=".$info["room_id"]);
        exit();
    }
}

// If you got this far then we got an access denied.
$day   = date("d");
$month = date("m");
$year  = date("Y");
showAccessDenied($day, $month, $year, $area,$back);
?>