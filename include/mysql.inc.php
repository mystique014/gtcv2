<?php
#########################################################################
#                            mysql.inc.php                              #
#                                                                       #
#               Bibliothèque de fonctions pour le support mysqli         #
#               Dernière modification : 10/07/2006                      #
#                                                                       #
#                                                                       #
#########################################################################
/*
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

// mysql.inc.php - Simple PHP database support for mysql.
// Include this file after defining the following variables:
//   $dbHost = The hostname of the database server
//   $dbUser = The username to use when connecting to the database
//   $dbPass = The database account password
//   $dbDb = The database name.
// Including this file connects you to the database, or exits on error.

// Cas d'une authentification LCS
// permet de savoir sur chaque script en testant $idpers si l'utilisateur est authentifié
// Est utile dans le cas où GRR n'est pas installé sur LCS en tant que Plugin


// Etablir la connexion à la base
if (empty($db_nopersist))
    $con= @mysqli_connect($dbHost, $dbUser, $dbPass);
else
    $con = mysqli_connect($dbHost, $dbUser, $dbPass);

if (!$con || !mysqli_select_db ($con,$dbDb))
{
    echo "\n<p>\n" . get_vocab('failed_connect_db') . "\n";
    exit;
}


// Free a results handle. You need not call this if you call ".$_COOKIE["table_prefix"]."_sql_row or
// grr_sql_row_keyed until the row returns 0, since grr_sql_row frees the results
// handle when you finish reading the rows.
function grr_sql_free($r)
{
    mysqli_free_result($r);
}

// Execute a non-SELECT SQL command (insert/update/delete).
// Returns the number of tuples affected if OK (a number >= 0).
// Returns -1 on error; use ".$_COOKIE["table_prefix"]."_sql_error to get the error message.
function grr_sql_command ($sql)
{
    global $con;
	if ($res=mysqli_query($con,$sql)) return mysqli_affected_rows($con);
    return -1;
}

// Execute an SQL query which should return a single non-negative number value.
// This is a lightweight alternative to ".$_COOKIE["table_prefix"]."_sql_query, good for use with count(*)
// and similar queries. It returns -1 on error or if the query did not return
// exactly one value, so error checking is somewhat limited.
// It also returns -1 if the query returns a single NULL value, such as from
// a MIN or MAX aggregate function applied over no rows.
function grr_sql_query1 ($sql)
{
   	global $con;
	$r = mysqli_query($con,$sql);
	if (! $r) return -1;
	$row = mysqli_fetch_row($r);
    if (mysqli_num_rows($r) != 1 || mysqli_num_fields($r) != 1
        || ($result_ = $row[0]) == "") $result_ = -1;
    mysqli_free_result($r);
	return $result_;
}

// Execute an SQL query. Returns a database-dependent result handle,
// which should be passed back to ".$_COOKIE["table_prefix"]."_sql_row or grr_sql_row_keyed to get the results.
// Returns 0 on error; use ".$_COOKIE["table_prefix"]."_sql_error to get the error message.
function grr_sql_query($sql)
{
    global $con;
	$r = mysqli_query($con,$sql);
	return $r;
}

// Return a row from a result. The first row is 0.
// The row is returned as an array with index 0=first column, etc.
// When called with i >= number of rows in the result, cleans up from
// the query and returns 0.
// Typical usage: $i = 0; while ((a = ".$_COOKIE["table_prefix"]."_sql_row($r, $i++))) { ... }
function grr_sql_row ($r, $i)
{
    if ($i >= mysqli_num_rows($r))
    {
        mysqli_free_result($r);
        return 0;
    }
    mysqli_data_seek($r, $i);
    return mysqli_fetch_row($r);
}

// Return a row from a result as an associative array keyed by field name.
// The first row is 0.
// This is actually upward compatible with ".$_COOKIE["table_prefix"]."_sql_row since the underlying
// routing also stores the data under number indexes.
// When called with i >= number of rows in the result, cleans up from
// the query and returns 0.
function grr_sql_row_keyed ($r, $i)
{
    if ($i >= mysqli_num_rows($r))
    {
        mysqli_free_result($r);
        return 0;
    }
    mysqli_data_seek($r, $i);
    return mysqli_fetch_array($r);
}

// Return the number of rows returned by a result handle from grr_sql_query.
function grr_sql_count ($r)
{
   	return mysqli_num_rows($r);
}

// Return the value of an autoincrement field from the last insert.
// Must be called right after an insert on that table!
function grr_sql_insert_id($table, $field)
{
    global $con;
	return mysqli_insert_id($con);
}

// Return the text of the last error message.
function grr_sql_error()
{
    global $con;
	return mysqli_error($con);
}

// Begin a transaction, if the database supports it. This is used to
// improve PostgreSQL performance for multiple insert/delete/updates.
// There is no rollback support, since mysql doesn't support it.
function grr_sql_begin()
{
}

// Commit (end) a transaction. See grr_sql_begin().
function grr_sql_commit()
{
}

// Acquire a mutual-exclusion lock on the named table. For portability:
// This will not lock out SELECTs.
// It may lock out DELETE/UPDATE/INSERT or not, depending on the implementation.
// It will lock out other callers of this routine with the same name argument.
// It may timeout in 20 seconds and return 0, or may wait forever.
// It returns 1 when the lock has been acquired.
// Caller must release the lock with grr_sql_mutex_unlock().
// Caller must not have more than one mutex at any time.
// Do not mix this with grr_sql_begin()/sql_end() calls.
//
// In mysql, we avoid table locks, and use low-level locks instead.
function grr_sql_mutex_lock($name)
{
    global $sql_mutex_shutdown_registered, $grr_sql_mutex_unlock_name;
    if (!grr_sql_query1("SELECT GET_LOCK('$name', 20)")) return 0;
    $grr_sql_mutex_unlock_name = $name;
    if (empty($sql_mutex_shutdown_registered))
    {
        register_shutdown_function("grr_sql_mutex_cleanup");
        $sql_mutex_shutdown_registered = 1;
    }
    return 1;
}

// Release a mutual-exclusion lock on the named table. See grr_sql_mutex_unlock.
function grr_sql_mutex_unlock($name)
{
    global $grr_sql_mutex_unlock_name;
    grr_sql_query1("SELECT RELEASE_LOCK('$name')");
    $grr_sql_mutex_unlock_name = "";
}

// Shutdown function to clean up a forgotten lock. For internal use only.
function grr_sql_mutex_cleanup()
{
    global $sql_mutex_shutdown_registered, $grr_sql_mutex_unlock_name;
    if (!empty($grr_sql_mutex_unlock_name))
    {
        grr_sql_mutex_unlock($grr_sql_mutex_unlock_name);
        $grr_sql_mutex_unlock_name = "";
    }
}


// Return a string identifying the database version:
function grr_sql_version()
{
    $r = grr_sql_query("select version()");
    $v = grr_sql_row($r, 0);
    grr_sql_free($r);
    return "mysql $v[0]";
}


// Generate non-standard SQL for LIMIT clauses:
function grr_sql_syntax_limit($count, $offset)
{
    return " LIMIT $offset,$count ";
}

// Generate non-standard SQL to output a TIMESTAMP as a Unix-time:
function grr_sql_syntax_timestamp_to_unix($fieldname)
{
    return " UNIX_TIMESTAMP($fieldname) ";
}

// Generate non-standard SQL to match a string anywhere in a field's value
// in a case insensitive manner. $s is the un-escaped/un-slashed string.
// In mysql, REGEXP seems to be case sensitive, so use LIKE instead. But this
// requires quoting of % and _ in addition to the usual.
function grr_sql_syntax_caseless_contains($fieldname, $s)
{
    $s = protect_data_sql($s);
//    $s = str_replace("'", "''", $s);
//    $s = str_replace("\\", "\\\\", $s);
    $s = str_replace("%", "\\%", $s);
    $s = str_replace("_", "\\_", $s);
    return " $fieldname LIKE '%$s%' ";
}

?>