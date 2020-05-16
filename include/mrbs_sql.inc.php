<?php
#########################################################################
#                            mrbs_sql.inc.php                           #
#           Bibliothèque de fonctions propres à GRR                     #
#           Dernière modification : 10/07/2006                          #
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

/** mrbsCheckFree()
 *
 * Check to see if the time period specified is free
 *
 * $room_id   - Which room are we checking
 * $starttime - The start of period
 * $endtime   - The end of the period
 * $ignore    - An entry ID to ignore, 0 to ignore no entries
 * $repignore - A repeat ID to ignore everything in the series, 0 to ignore no series
 *
 * Returns:
 *   nothing   - The area is free
 *   something - An error occured, the return value is human readable
 */

function mrbsCheckFree($room_id, $starttime, $endtime, $ignore, $repignore)
{
    global $vocab;
    # Select any meetings which overlap ($starttime,$endtime) for this room:
    $sql = "SELECT id, name, start_time FROM grr_entry WHERE
        start_time < '".$endtime."' AND end_time > '".$starttime."'
        AND room_id = '".$room_id."'";

    if ($ignore > 0)
        $sql .= " AND id <> $ignore";
    if ($repignore > 0)
        $sql .= " AND repeat_id <> $repignore";
    $sql .= " ORDER BY start_time";

    $res = grr_sql_query($sql);
    if(! $res)
        return grr_sql_error();
    if (grr_sql_count($res) == 0)
    {
        grr_sql_free($res);
        return "";
    }
    // Get the room's area ID for linking to day, week, and month views:
    $area = mrbsGetRoomArea($room_id);

    // Build a string listing all the conflicts:
    $err = "";
    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
        $starts = getdate($row[2]);
        $param_ym = "area=$area&amp;year=$starts[year]&amp;month=$starts[mon]";
        $param_ymd = $param_ym . "&amp;day=$starts[mday]";

        $err .= "<LI><A HREF=\"view_entry.php?id=$row[0]\">$row[1]</A>"
        . " ( " . utf8_strftime('%A %d %B %Y %T', $row[2]) . ") "
        . "(<A HREF=\"day.php?$param_ymd\">".get_vocab("viewday")."</a>"
        . " | <A HREF=\"week.php?room=$room_id&amp;$param_ymd\">".get_vocab("viewweek")."</a>"
        . " | <A HREF=\"month.php?room=$room_id&amp;$param_ym\">".get_vocab("viewmonth")."</a>)\n";
    }
    return $err;
}

/** grrDelEntryInConflict()
 *
 *  Efface les réservation qui sont en partie ou totalement dans le créneau $starttime<->$endtime
 *
 * $room_id   - Which room are we checking
 * $starttime - The start of period
 * $endtime   - The end of the period
 * $ignore    - An entry ID to ignore, 0 to ignore no entries
 * $repignore - A repeat ID to ignore everything in the series, 0 to ignore no series
 *
 * Returns:
 *   nothing   - The area is free
 *   something - An error occured, the return value is human readable
 *   if $flag = 1, return the number of erased entries.
 */
function grrDelEntryInConflict($room_id, $starttime, $endtime, $ignore, $repignore, $flag)
{
    global $vocab, $dformat;

    # Select any meetings which overlap ($starttime,$endtime) for this room:
    $sql = "SELECT id FROM grr_entry WHERE
        start_time < '".$endtime."' AND end_time > '".$starttime."'
        AND room_id = '".$room_id."'";
    if ($ignore > 0)
        $sql .= " AND id <> $ignore";
    if ($repignore > 0)
        $sql .= " AND repeat_id <> $repignore";
    $sql .= " ORDER BY start_time";

    $res = grr_sql_query($sql);
    if(! $res)
        return grr_sql_error();
    if (grr_sql_count($res) == 0)
    {
        grr_sql_free($res);
        return "";
    }
    # Efface les résas concernées
    $err = "";
    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
        if (getSettingValue("automatic_mail") == 'yes') echo send_mail($row[0],3,$dformat);
        $result = mrbsDelEntry(getUserName(), $row[0], NULL , 1);
    }
    if ($flag == 1) return $result;
}


/** mrbsDelEntry()
 *
 * Delete an entry, or optionally all entrys.
 *
 * $user   - Who's making the request
 * $id     - The entry to delete
 * $series - If set, delete the series, except user modified entrys
 * $all    - If set, include user modified entrys in the series delete
 *
 * Returns:
 *   0        - An error occured
 *   non-zero - The entry was deleted
 */
function mrbsDelEntry($user, $id, $series, $all)
{
    $repeat_id = grr_sql_query1("SELECT repeat_id FROM grr_entry WHERE id='".$id."'");
    if ($repeat_id < 0)
        return 0;

    $sql = "SELECT create_by, id, entry_type FROM grr_entry WHERE ";

    if($series)
        $sql .= "repeat_id='".protect_data_sql($repeat_id)."'";
    else
        $sql .= "id='".$id."'";

    $res = grr_sql_query($sql);

    $removed = 0;

    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
        if(!getWritable($row[0], $user, $id))
            continue;

        if($series && $row[2] == 2 && !$all)
            continue;

        if (grr_sql_command("DELETE FROM grr_entry WHERE id=" . $row[1]) > 0)
            $removed++;
    }

    if ($repeat_id > 0 &&
            grr_sql_query1("SELECT count(*) FROM grr_entry WHERE repeat_id='".protect_data_sql($repeat_id)."'") == 0)
        grr_sql_command("DELETE FROM grr_repeat WHERE id='".$repeat_id."'");

    return $removed > 0;
}


/*
  mrbsGetAreaIdFromRoomId($room_id)
*/

function mrbsGetAreaIdFromRoomId($room_id)
{
  // Avec la room_id on récupère l'area_id
  $sqlstring = "select area_id from grr_room where id=$room_id";
  $result = grr_sql_query($sqlstring);

  if (! $result) fatal_error(1, grr_sql_error());
  if (grr_sql_count($result) != 1) fatal_error(1, get_vocab('roomid') . $id_entry . get_vocab('not_found'));

  $area_id_row = grr_sql_row($result, 0);
  grr_sql_free($result);

  return $area_id_row[0];

}



/** mrbsOverloadGetFieldslist()
 *
 * Return an array with all fields name
 * $id_area - Id of the id_area
 *
 */
function mrbsOverloadGetFieldslist($id_area,$room_id=0)
{
  if ($room_id > 0 )
    {
      // il faut rechercher le id_area en fonction du room_id

      $sqlstring = "select area_id from grr_room where id='".$room_id."'";
      $result = grr_sql_query($sqlstring);

      if (! $result) fatal_error(1, grr_sql_error());
      if (grr_sql_count($result) != 1) fatal_error(1, get_vocab('error_room') . $room_id . get_vocab('not_found'));

      $area_id_row = grr_sql_row($result, 0);
      grr_sql_free($result);

      $id_area = $area_id_row[0];

    }

  $sqlstring = "select fieldname,fieldtype, id from grr_overload where id_area='".$id_area."'";
  $result = grr_sql_query($sqlstring);
  $fieldslist = array();
  if (! $result) fatal_error(1, grr_sql_error());

  if (grr_sql_count($result) <0) fatal_error(1, get_vocab('error_area') . $id_area . get_vocab('not_found'));

  for ($i = 0; ($field_row = grr_sql_row($result, $i)); $i++)
    {
      $fieldslist[$field_row[0]]['type'] = $field_row[1];
      $fieldslist[$field_row[0]]['id'] = $field_row[2];
    }
  return $fieldslist;
}

/** mrbsEntryGetOverloadDesc()
 *
 * Return an array with all additionnal fields
 * $id - Id of the entry
 *
 */

function mrbsEntryGetOverloadDesc($id_entry)
{
  $room_id = 0;
  $overload_array = array();
  $overload_desc = "";
  // On récupère les données overload desc dans grr_entry.
  if ($id_entry != NULL)
    {
      $overload_array = array();
      $sqlstring = "select overload_desc,room_id from grr_entry where id=".$id_entry.";";
      $result = grr_sql_query($sqlstring);

      if (! $result) fatal_error(1, grr_sql_error());
      if (grr_sql_count($result) != 1) fatal_error(1, get_vocab('entryid') . $id_entry . get_vocab('not_found'));

      $overload_desc_row = grr_sql_row($result, 0);
      grr_sql_free($result);

      $overload_desc = $overload_desc_row[0];
      $room_id = $overload_desc_row[1];
    }
  if ( $room_id >0 )
    {
      $area_id = mrbsGetAreaIdFromRoomId($room_id);


      // Avec l'id_area on récupère la liste des champs additionnels dans grr_overload.
      $fieldslist = mrbsOverloadGetFieldslist($area_id);

      foreach ( $fieldslist as $field=>$fieldtype)
    {
      $begin_string = "<".$fieldslist[$field]['id'].">";
      $end_string = "</".$fieldslist[$field]['id'].">";
      $data = "";

      $begin_pos = strpos($overload_desc,$begin_string);
      $end_pos = strpos($overload_desc,$end_string);

      if ( $begin_pos !== false && $end_pos !== false )
        {
          $first = $begin_pos + strlen($begin_string);
          $data = substr($overload_desc,$first,$end_pos-$first);
          $overload_array[$field] = base64_decode($data);
        }
      else $overload_array[$field] = "";
    }
      return $overload_array;
    }
  return $overload_array;

}


/** mrbsCreateSingleEntry()
 *
 * Create a single (non-repeating) entry in the database
 *
 * $starttime   - Start time of entry
 * $endtime     - End time of entry
 * $entry_type  - Entry type
 * $repeat_id   - Repeat ID
 * $room_id     - Room ID
 * $owner       - Owner
 * $name        - Name
 * $type        - Type (Internal/External)
 * $description - Description
 *
 * Returns:
 *   0        - An error occured while inserting the entry
 *   non-zero - The entry's ID
 */
function mrbsCreateSingleEntry($starttime, $endtime, $entry_type, $repeat_id, $room_id,
                               $owner, $name, $type, $description, $option_reservation,$overload_data)
{
  $overload_data_string = "";
  $overload_fields_list = mrbsOverloadGetFieldslist(0,$room_id);

  foreach ($overload_fields_list as $field=>$fieldtype)
    {
      $id_field = $overload_fields_list[$field]['id'];
      if (array_key_exists($id_field,$overload_data))
      {
      $begin_string = "<".$id_field.">";
      $end_string = "</".$id_field.">";

    $overload_data_string .= $begin_string.base64_encode($overload_data[$id_field]).$end_string;
      }
    }


  $sql = "INSERT INTO grr_entry (  start_time,   end_time,   entry_type,    repeat_id,   room_id,
                                      create_by,    name, type, description, statut_entry, option_reservation,overload_desc)
                            VALUES ($starttime, $endtime, '".protect_data_sql($entry_type)."', $repeat_id, $room_id,
                                    '".protect_data_sql($owner)."', '".protect_data_sql($name)."', '".protect_data_sql($type)."', '".protect_data_sql($description)."', '-', '".$option_reservation."','".protect_data_sql($overload_data_string)."')";

    if (grr_sql_command($sql) < 0) return 0;

    return grr_sql_insert_id("grr_entry", "id");
}

/** mrbsCreateRepeatEntry()
 *
 * Creates a repeat entry in the data base
 *
 * $starttime   - Start time of entry
 * $endtime     - End time of entry
 * $rep_type    - The repeat type
 * $rep_enddate - When the repeating ends
 * $rep_opt     - Any options associated with the entry
 * $room_id     - Room ID
 * $owner       - Owner
 * $name        - Name
 * $type        - Type (Internal/External)
 * $description - Description
 *
 * Returns:
 *   0        - An error occured while inserting the entry
 *   non-zero - The entry's ID
 */
function mrbsCreateRepeatEntry($starttime, $endtime, $rep_type, $rep_enddate, $rep_opt,
                               $room_id, $owner, $name, $type, $description, $rep_num_weeks,$overload_data)
{
  $overload_data_string = "";
  $area_id = mrbsGetAreaIdFromRoomId($room_id);

  $overload_fields_list = mrbsOverloadGetFieldslist($area_id);

  foreach ($overload_fields_list as $field=>$fieldtype)
    {
      $id_field = $overload_fields_list[$field]['id'];
      if (array_key_exists($id_field,$overload_data))
      {
      $begin_string = "<".$id_field.">";
      $end_string = "</".$id_field.">";
      $overload_data_string .= $begin_string.base64_encode($overload_data[$id_field]).$end_string;
      }
    }
  $sql = "INSERT INTO grr_repeat (
  start_time, end_time, rep_type, end_date, rep_opt, room_id, create_by, type, name, description, rep_num_weeks, overload_desc)
  VALUES ($starttime, $endtime,  $rep_type, $rep_enddate, '$rep_opt', $room_id,   '".protect_data_sql($owner)."', '".protect_data_sql($type)."', '".protect_data_sql($name)."', '".protect_data_sql($description)."', '$rep_num_weeks','".protect_data_sql($overload_data_string)."')";


  if (grr_sql_command($sql) < 0)
    {
      return 0;

    }
  return grr_sql_insert_id("grr_repeat", "id");
}


/** same_day_next_month
 *  Return the number of days to step forward for a "monthly repeat,
 *  corresponding day" series - same week number and day of week next month.
 *  This function always returns either 28 or 35.
 *  For dates after the 28th day of a month, the results are undefined.
 */
function same_day_next_month($time)
{
    $days_in_month = date("t", $time);
    $day = date("d", $time);
    $weeknumber = (int)(($day - 1) / 7) + 1;
    if ($day + 7 * (5 - $weeknumber) <= $days_in_month) return 35;
    else return 28;
}

/** mrbsGetRepeatEntryList
 *
 * Returns a list of the repeating entrys
 *
 * $time     - The start time
 * $enddate  - When the repeat ends
 * $rep_type - What type of repeat is it
 * $rep_opt  - The repeat entrys
 * $max_ittr - After going through this many entrys assume an error has occured
 *
 * Returns:
 *   empty     - The entry does not repeat
 *   an array  - This is a list of start times of each of the repeat entrys
 */
function mrbsGetRepeatEntryList($time, $enddate, $rep_type, $rep_opt, $max_ittr, $rep_num_weeks)
{
    $sec   = date("s", $time);
    $min   = date("i", $time);
    $hour  = date("G", $time);
    $day   = date("d", $time);
    $month = date("m", $time);
    $year  = date("Y", $time);

    $entrys = array();
    $entrys_return = array();
    $k=0;
    for($i = 0; $i < $max_ittr; $i++)
    {
        $time = mktime($hour, $min, $sec, $month, $day, $year);
        if ($time > $enddate)
            break;
        $time2 = mktime(0, 0, 0, $month, $day, $year);

        if (!(est_hors_reservation($time2))) {
            $entrys_return[$k] = $time;
            $k++;
        }
        $entrys[$i] = $time;
        switch($rep_type)
        {
            // Daily repeat
            case 1:
                $day += 1;
                break;

            // Weekly repeat
            case 2:
                $j = $cur_day = date("w", $entrys[$i]);

                // Skip over days of the week which are not enabled:
                while (($j = ($j + 1) % 7) != $cur_day && !$rep_opt[$j])

                    $day += 1;

                $day += 1;
                break;

            // Monthly repeat
            case 3:
                $month += 1;
                break;

            // Yearly repeat
            case 4:
                $year += 1;
                break;

            // Monthly repeat on same week number and day of week
            case 5:
                $day += same_day_next_month($time);
                break;

            // n Weekly repeat
            case 6:
                $j = $cur_day = date("w", $entrys[$i]);
                // Skip over days of the week which are not enabled:
                while ((($j = ($j + 1) % (7*$rep_num_weeks)) != $cur_day && $j<7 &&!$rep_opt[$j]) or ($j>=7))
                {
                    $day += 1;
                }

                $day += 1;
                break;

            // Unknown repeat option
            default:
                return;
        }
    }

    return $entrys_return;
}

/** mrbsCreateRepeatingEntrys()
 *
 * Creates a repeat entry in the data base + all the repeating entrys
 *
 * $starttime   - Start time of entry
 * $endtime     - End time of entry
 * $rep_type    - The repeat type
 * $rep_enddate - When the repeating ends
 * $rep_opt     - Any options associated with the entry
 * $room_id     - Room ID
 * $owner       - Owner
 * $name        - Name
 * $type        - Type (Internal/External)
 * $description - Description
 *
 * Returns:
 *   0        - An error occured while inserting the entry
 *   non-zero - The entry's ID
 */
function mrbsCreateRepeatingEntrys($starttime, $endtime, $rep_type, $rep_enddate, $rep_opt,
                                   $room_id, $owner, $name, $type, $description, $rep_num_weeks, $option_reservation,$overload_data)
{
    global $max_rep_entrys;
    $reps = mrbsGetRepeatEntryList($starttime, $rep_enddate, $rep_type, $rep_opt, $max_rep_entrys, $rep_num_weeks);
    if(count($reps) > $max_rep_entrys)
        return 0;

    if(empty($reps))
    {
        mrbsCreateSingleEntry($starttime, $endtime, 0, 0, $room_id, $owner, $name, $type, $description, $option_reservation,$overload_data);
        return;
    }

    $ent = mrbsCreateRepeatEntry($starttime, $endtime, $rep_type, $rep_enddate, $rep_opt, $room_id, $owner, $name, $type, $description, $rep_num_weeks,$overload_data);
    if($ent)
    {
        $diff = $endtime - $starttime;

        for($i = 0; $i < count($reps); $i++)
            mrbsCreateSingleEntry($reps[$i], $reps[$i] + $diff, 1, $ent,
                 $room_id, $owner, $name, $type, $description, $option_reservation,$overload_data);
    }

    return $ent;
}

/* mrbsGetEntryInfo()
 *
 * Get the booking's entrys
 *
 * $id = The ID for which to get the info for.
 *
 * Returns:
 *    nothing = The ID does not exist
 *    array   = The bookings info
 */
function mrbsGetEntryInfo($id)
{
    $sql = "SELECT start_time, end_time, entry_type, repeat_id, room_id,
                   timestamp, create_by, name, type, description
                FROM grr_entry WHERE (ID = '".$id."')";

    $res = grr_sql_query($sql);
    if (! $res) return;

    $ret = array();
    if(grr_sql_count($res) > 0)
    {
        $row = grr_sql_row($res, 0);

        $ret['start_time']  = $row[0];
        $ret['end_time']    = $row[1];
        $ret['entry_type']  = $row[2];
        $ret['repeat_id']   = $row[3];
        $ret['room_id']     = $row[4];
        $ret['timestamp']   = $row[5];
        $ret['create_by']   = $row[6];
        $ret['name']        = $row[7];
        $ret['type']        = $row[8];
        $ret['description'] = $row[9];

    }
    grr_sql_free($res);

    return $ret;
}

function mrbsGetRoomArea($id)
{
    $id = grr_sql_query1("SELECT area_id FROM grr_room WHERE (id = '".$id."')");
    if ($id <= 0) return 0;
    return $id;
}

?>