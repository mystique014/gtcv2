<?php
#########################################################################
#                            mincals.inc.php                            #
#                                                                       #
#        Fonctions permettant d'afficher le mini calendrier             #
#       Dernière modification : 10/07/2006                              #
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
function minicals($year, $month, $day, $area, $room, $dmy)
{
global $display_day;

// Récupération des données concernant l'affichage du planning du domaine
get_planning_area_values($area);

// PHP Calendar Class
// Copyright David Wilkinson 2000. All Rights reserved.
// This software may be used, modified and distributed freely
// providing this copyright notice remains intact at the head
// of the file.
// This software is freeware. The author accepts no liability for
// any loss or damages whatsoever incurred directly or indirectly
// from the use of this script.
// URL:   http://www.cascade.org.uk/software/php/calendar/
// Email: davidw@cascade.org.uk

    #constructeur de la classe calendar
    class Calendar
    {
    var $month;
    var $year;
    var $day;
    var $h;
    var $area;
    var $room;
    var $dmy;
    var $week;
    function Calendar($day, $month, $year, $h, $area, $room, $dmy)
    {
        $this->day   = $day;
        $this->month = $month;
        $this->year  = $year;
        $this->h     = $h;
        $this->area  = $area;
        $this->room  = $room;
        $this->dmy   = $dmy;
    }
    function getCalendarLink($month, $year)
    {
        return "";
    }
        #Liens vers une une date donnée.
        function getDateLink($day, $month, $year)
        {
       global $vocab;
      if ($this->dmy=='day') return "<a title=\"".htmlspecialchars(get_vocab("see_all_the_rooms_for_the_day"))."\" href=\"".$this->dmy.".php?year=$year&amp;month=$month&amp;day=$day&amp;area=".$this->area."\"";
      if ($this->dmy!='day') return "<a title=\"".htmlspecialchars(get_vocab("see_all_the_rooms_for_the_day"))."\" href=\"day.php?year=$year&amp;month=$month&amp;day=$day&amp;area=".$this->area."\"";

   }
        #Calcule le nombre de jours dans un mois en tenant compte des années bissextiles.
    function getDaysInMonth($month, $year)
    {
        if ($month < 1 || $month > 12)
            return 0;
        $days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $d = $days[$month - 1];
        if ($month == 2)
        {
                #Vérification de l'année bissextile.
            if ($year%4 == 0)
            {
                if ($year%100 == 0)
                {
                    if ($year%400 == 0)
                        $d = 29;
                    }
                else
                    $d = 29;
                }
            }
        return $d;
    }
    function getFirstDays()
    {
      global $weekstarts, $display_day;
      $basetime = mktime(12,0,0,6,11+$weekstarts,2000);
      for ($i = 0, $s = ""; $i < 7; $i++)
      {
         $j = ($i + 7 + $weekstarts) % 7;
         $show = $basetime + ($i * 24 * 60 * 60);
         $fl = strftime('%a',$show);
         if ($display_day[$j] == 1)
             $s .= "<td align=center valign=top class=\"calendarHeader\">$fl</td>\n";
      }
      return $s;
    }
    function getHTML()
    {
        global $weekstarts, $vocab, $type_month_all, $display_day, $nb_display_day;
        // Calcul de la date courante
        $date_today = mktime(12, 0, 0, $this->month, $this->day, $this->year);
        // Calcul du numéro de semaine courante
        $week_today = strftime("%W",$date_today);

        if (!isset($weekstarts)) $weekstarts = 0;
        $s = "";
        $daysInMonth = $this->getDaysInMonth($this->month, $this->year);
        // Calcul de la date au 1er du mois de la date courante
        $date = mktime(12, 0, 0, $this->month, 1, $this->year);
        $first = (strftime("%w",$date) + 7 - $weekstarts) % 7;
        $monthName = utf8_strftime("%B",$date);
        $prevMonth = $this->getCalendarLink($this->month - 1 >   0 ? $this->month - 1 : 12, $this->month - 1 >   0 ? $this->year : $this->year - 1);
        $nextMonth = $this->getCalendarLink($this->month + 1 <= 12 ? $this->month + 1 :  1, $this->month + 1 <= 12 ? $this->year : $this->year + 1);
        $s .= "<table border = \"0\" class=\"calendar\" align=\"center\">\n";
        $s .= "<tr><td></td>\n";
        $s .= "<td align=center valign=top class=\"calendarHeader\" colspan=".$nb_display_day.">";
            #Permet de récupérer le numéro de la 1ere semaine affichée par le mini calendier.
            $week = number_format(strftime("%W",$date),0);
            if (($this->dmy!='day') and ($this->dmy!='week_all') and ($this->dmy!='month_all') and ($this->dmy!='month_all2'))
        $s .= "<a title=\"".htmlspecialchars(get_vocab("see_month_for_this_room"))."\" href=\"month.php?year=$this->year&amp;month=$this->month&amp;day=1&amp;area=$this->area&amp;room=$this->room\">$monthName&nbsp;$this->year</a>";
            else
         $s .= "<a title=\"".htmlspecialchars(get_vocab("see_all_the_rooms_for_the_month"))."\" href=\"".$type_month_all.".php?year=$this->year&amp;month=$this->month&amp;day=1&amp;area=$this->area\">$monthName&nbsp;$this->year</a>";
        $s .= "</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr><td></td>\n";
        $s .= $this->getFirstDays();
        $s .= "</tr>\n";
        $d = 1 - $first;
        $temp = 1;
        $numweek=1;
        while ($d <= $daysInMonth)
        {
            if (($week_today == $week) and ($this->h) and (($this->dmy=='week_all') or ($this->dmy=='week') )) $bg_lign = "span class=\"week\""; else $bg_lign = '';
            $s .= "<tr ".$bg_lign."><td class=\"calendarcol1\" align=right valign=top>";
            #Affichage du numéro de la semaine en cours à droite du calendrier et génère un lien sur la semaine voulue.
            if (($this->dmy!='day') and ($this->dmy!='week_all') and ($this->dmy!='month_all') and ($this->dmy!='month_all2'))
               $s .="<a title=\"".htmlspecialchars(get_vocab("see_week_for_this_room"))."\" href=\"week.php?year=$this->year&amp;month=$this->month&amp;day=$temp&amp;area=$this->area&amp;room=$this->room\">s".$week."</a>";
            else
                $s .="<a title=\"".htmlspecialchars(get_vocab("see_week_for_this_area"))."\" href=\"week_all.php?year=$this->year&amp;month=$this->month&amp;day=$temp&amp;area=$this->area\">s".$week."</a>";
            $temp=$temp+7;
            while ((!checkdate($this->month, $temp, $this->year)) and ($temp > 0))  $temp--;


            #Ancien affichage, affichait le numéro de la semaine dans le mois.
            $numweek++;
            #Nouveau affichage, affiche le numéro de la semaine dans l'année.Incrémentation de ce numéro à chaque nouvelle semaine.
            $week++;
            $s .= "</td>\n";
            for ($i = 0; $i < 7; $i++)
            {
                $j = ($i + 7 + $weekstarts) % 7;
                if ($display_day[$j] == "1") {// début condition "on n'affiche pas tous les jours de la semaine"
                $s .= "<td class=\"calendar\" align=center valign=top>";
                if ($d > 0 && $d <= $daysInMonth)
                {
                    $link = $this->getDateLink($d, $this->month, $this->year);
                    if ($link == "")
                        $s .= $d;
                        #Permet de colorer la date affichée sur la page
                    elseif (($d == $this->day) and ($this->h))
                        $s .= $link."><span class=\"cal_current_day\">$d</span></a>";
                    else
                        $s .= $link.">$d</a>";
                }
                else
                    $s .= "&nbsp;";
                  $s .= "</td>\n";
                }// fin condition "on n'affiche pas tous les jours de la semaine"
                $d++;
            }
            $s .= "</tr>\n";
        }
        $s .= "</table>\n";
        return $s;
    }
    }
    $lastmonth = mktime(0, 0, 0, $month-1, 1, $year);
    $thismonth = mktime(0, 0, 0, $month, $day, $year);
    $nextmonth = mktime(0, 0, 0, $month+1, 1, $year);
    echo "<td>";$cal = new Calendar(date("d",$lastmonth), date("m",$lastmonth), date("Y",$lastmonth), 0, $area, $room, $dmy);
    echo $cal->getHTML();
    echo "</td>";
    echo "<td>";$cal = new Calendar(date("d",$thismonth), date("m",$thismonth), date("Y",$thismonth), 1, $area, $room, $dmy);
    echo $cal->getHTML();
    echo "</td>";
    echo "<td>";$cal = new Calendar(date("d",$nextmonth), date("m",$nextmonth), date("Y",$nextmonth), 0, $area, $room, $dmy);
    echo $cal->getHTML();
    echo "</td>";
}?>