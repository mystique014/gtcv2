<?php
#########################################################################
#                            admin_compta_export.php                       #
#                                                                       #
#               script d'export de la base compatable     #
#               Dernière modification : 2/11/2012                    #
#                                                                       #
#                                                                       #
#########################################################################
/*
 * Copyright  Stéphane Duchemin
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


$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = $_SERVER['HTTP_REFERER'];
if(authGetUserLevel(getUserName(),-1) < 5)
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}

//Extraire le nom des colonnes
$rsColumn = grr_sql_query("SHOW COLUMNS FROM grr_compta");
$columnLine="";
$columnCount=0;
$separator=";";
if ($rsColumn) {
    if (mysqli_num_rows($rsColumn) > 0) {
        while ($row = mysqli_fetch_assoc($rsColumn)) {
            $columnLine .= $row['Field'].$separator;
            $columnCount++;
        }
        $columnLine .="\n";
    }
}

//Extraire les données
$rsData=grr_sql_query("SELECT * FROM grr_compta");
$dataLine="";
while ($row = mysqli_fetch_array($rsData)) {
    for ($i = 0; $i < $columnCount; $i++) {
        $dataLine.=$row[$i].$separator;
    }
    $dataLine.="\n";
}

//Envoyer le contenu au navigateur internet
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=export_compta.csv");
echo $columnLine.$dataLine;
exit;
?>

