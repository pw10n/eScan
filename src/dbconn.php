<?PHP

require_once "dbinfo.php";
require_once "common.php";

define(LOG_MODE_USER, 0);
define(LOG_MODE_ADMIN, 1);

define(LOG_USER_ACTION_REGISTER, 0);
define(LOG_USER_ACTION_CREATE_TEAM, 1);
define(LOG_USER_ACTION_ADD_TEAM_MEMBER, 2);
define(LOG_USER_ACTION_REMOVE_TEAM_MEMBER, 3);

define(LOG_ADMIN_ACTION_SCAN_ATTENDANCE, 0);
define(LOG_ADMIN_ACTION_SCAN_EXTRA_POINTS, 1);
define(LOG_ADMIN_ACTION_GENERATE_PIN, 2);
define(LOG_ADMIN_ACTION_SWAP_PASSPORT, 3);

$user_base_query = "SELECT *, (SELECT sum(`pts`) FROM `score` WHERE `score`.`uid` = `users`.`uid`) as `pts`, (SELECT count(*) FROM `score` WHERE `score`.`uid` = `users`.`uid` and `score`.`act` = 0) as `evts` FROM `users`";

$team_base_query = "SELECT *, (SELECT sum(`pts`) FROM `users`, `score` WHERE `users`.`uid` = `score`.`uid` AND `users`.`tid` = `team`.`tid`) as `pts` FROM `team`";

// Opens and returns a new MySQL database connection.
//
// Returns:
//   A newly opened MySQL connection.
function get_mysql_connection() {
  global $mysql_server, $mysql_user, $mysql_pass, $mysql_db;

  $con = mysql_connect($mysql_server, $mysql_user, $mysql_pass);

  if ($con == false) {
    die('mysql_connect: ' . mysql_error());
  }

  mysql_select_db($mysql_db, $con) or die('mysql_select_db: ' . mysql_error());

  return $con;
}


function get_event($eid){
   global $events_data;
   return $events_data[$eid];
}


// Adds an unregistered, teamless user to the database with the given barcode id
// and pin.
//
// Args:
//   bid - barcode id of the user to add
//   pin - pin for the added user
function add_user($bid, $pin) {
  $con = get_mysql_connection();
  $result = mysql_query("INSERT INTO `users`(`bid`, `pin`, `s`, `tid`) ".
                        "VALUES(" . $bid . ", " . $pin . ", " . PASSPORT_STATE_UNREGISTERED . ", " . PASSPORT_NO_TEAM_TID . ")", $con)
            or die('mysql_query: ' . mysql_error());
}

// Gets the row from the database for the user with the given barcode id with
// the total points (pts) and number of events attended (evts) appended to each
// record..
//
// Args:
//   bid - the barcode id of the user
// Returns:
//   The user with barcode id bid or null if no such user exists.
function get_user($bid) {
  if (strlen($bid) == 0) {
    return null;
  }

  global $user_base_query;

  $con = get_mysql_connection();

  $result = mysql_query($user_base_query . " WHERE `bid` = " . $bid, $con)
            or die('mysql_query: ' . mysql_error());

  $row = mysql_fetch_array($result);

  mysql_close($con);

  return $row;
}

// Gets all registered teams from the database with their total points, sorted
// with most points first.
//
// Returns:
//   An array containing an entry for each team in the database plus a 'pts'
//   field that indicates the total points earned by the team.  The array is
//   sorted in decsending order by total points.
function get_all_teams() {
  global $team_base_query;

  return query_to_array($team_base_query . " ORDER BY `pts` DESC");
}

// Gets the team with the given tid from the database, including its total
// number of points.
//
// Args:
//   tid - the team id of the team to get
//
// Returns:
//   An array containing the data that represents the team with the given tid
//   or null if no such team exists.
function get_team($tid) {
  if (strlen($tid) == 0) {
    return null;
  }

  global $team_base_query;

  $con = get_mysql_connection();

  $result = mysql_query($team_base_query . " WHERE `tid` = " . $tid, $con)
            or die('mysql_query: ' . mysql_error());

  $row = mysql_fetch_array($result);

  mysql_close($con);

  return $row;
}

// Registers a team with the given team name and given captain.
//
// Arts:
//   team_name - name of the team to register
//   cid - uid of the captain of the team
//
// Returns:
//   The tid of the newly registered team.
function register_team($team_name, $cid) {
  $con = get_mysql_connection();

  mysql_query("INSERT INTO `team` (`cid`, `name`) VALUES (" . $cid . ", \"" . $team_name . "\")", $con)
  or die ('mysql_query: ' . mysql_error());

  $tid = mysql_insert_id($con);

  mysql_close($con);

  return $tid;
}

// Assigns the user with the given barcode id to the given team.
//
// Args:
//   bid - barcode id of the user to assign
//   tid - team id to assign the user to
function assign_user_to_team($bid, $tid) {
  if (strlen($bid) == 0 && strlen($pin)) {
    return null;
  }

  $con = get_mysql_connection();

  mysql_query("UPDATE `users` SET `tid` = " . $tid . " WHERE `bid` = " . $bid,
              $con)
  or die('mysql_query: ' . mysql_error());
}

// Gets all members of the team with the given team id.
//
// Args:
//   tid - team id to get team members for
//
// Returns:
//   An array of team members on the team with the given tid.
function get_team_members($tid) {
  global $user_base_query;

  return query_to_array($user_base_query . " WHERE `tid` = " . $tid);
}

// Gets the score rows from the database for the user with the given barcode id.
//
// Args:
//   bid - the barcode id of the user
// Returns:
//   The user's score record.
function get_user_scores($bid) {

  $con = get_mysql_connection();

  $user = get_user($bid);
  $uid = $user["uid"];

  if ($uid == null) {
    return array();
  }

  $scores = query_to_array("SELECT * FROM `score` WHERE `uid` = " . $uid, $con);
  mysql_close($con);
  return $scores;
}

// Gets all of the registered, prize eligible users that have attended a given
// minimum number of events.
//
// Args:
//   minNumEvents - the minimum number of events the user should have attended
//
// Returns:
//   An array of users records for registered, prize eligible users that have
//   attended the given minimum number of events.
function get_eligible_users_with_min_events($minNumEvents) {
  global $user_base_query;

  return query_to_array($user_base_query . " WHERE (SELECT count(*) FROM `score` WHERE `score`.`act` = 0 AND `score`.`uid` = `users`.`uid`) >= " . $minNumEvents . " AND `s` = " . PASSPORT_STATE_REGISTERED . " AND `elig`");
}

// Gets all of the users that attended a given event.
//
// Args:
//   eventId - the id of the event to get attendees for
//
// Returns:
//   An array of user records that represent users that attended the event
//   with the given eventId.
function get_users_by_event($eventId) {
  global $user_base_query;

  return query_to_array($user_base_query . ", `score` WHERE `score`.`uid` = `users`.`uid` AND `eid` = " . $eventId);
}

// Gets all users that are either registered, scanned in, or on a team.
//
// Returns:
//   An array of all registered, scanned in, or on a team users.
function get_actioned_users() {
  global $user_base_query;

  return query_to_array($user_base_query . " WHERE `s` = " . PASSPORT_STATE_REGISTERED . " OR `uid` IN (SELECT `uid` FROM `score`) OR `tid` != " . PASSPORT_NO_TEAM_TID);
}

// Gets the total number of attendees at all events that have had at least one
// attendee.
//
// Returns:
//   An associative array of eventIds to attendance count for events that have
//   had at least one attendee.
function get_all_event_attendances() {
  $con = get_mysql_connection();

  return query_to_array("SELECT `eid`, count(*) as `att` FROM `score` WHERE `act` = 0 GROUP BY `eid`");
}

// Registers the user with the given barcode id and registration parameters.
//
// Args:
//   bid - barcode id of the user to register
//   registration - array of registration parameters
//
// Returns:
//   True if the registration succeeds, false otherwise.
function register_user($bid, $registration) {
  $con = get_mysql_connection();

  mysql_query("UPDATE `users` " .
                "SET `fn`=\"" . $registration['fn'] . "\", " .
                "`ln`=\"" . $registration['ln'] . "\", " .
                "`em`=\"" . $registration['em'] . "\", " .
                "`ma`=\"" . $registration['ma'] . "\", " .
                "`opt`=\"" . $registration['opt'] . "\", " .
                "`s`=" . PASSPORT_STATE_REGISTERED . " " .
                "WHERE `bid` = " . $bid,
              $con)
            or die('mysql_query: ' . mysql_error());

  mysql_close($con);
}

function do_scan($eid, $user){
  if (!$user){
     echo "error";
     return false;
  }

  global $user_base_query;

  $success = false;

  $con = get_mysql_connection();

  $uid = $user['uid'];
  // TODO: separate db logic from business logic 

  $check_query = "SELECT * FROM score WHERE `uid`=" . $uid . " and `eid`=" . $eid . " and `act`=0";

  $check_result = mysql_query($check_query, $con)
            or die('mysql_query: ' . mysql_error());
  $fetch_result = mysql_fetch_assoc($check_result);

  if (!$fetch_result){ // ensure this user has not been scanned
     $success = true;

     $scan_query = "INSERT INTO score (`uid`, `eid`, `ts`, `act`, `pts`) VALUES ('" . $uid . "','" . $eid . "',NOW(),'0','" . SCORE_ATTENDANCE_POINTS . "')";  

     $scan_result = mysql_query($scan_query, $con)
        or die('mysql_query: ' . mysql_error());
  }
  mysql_close($con);

  return $success;
}

function do_pscan($eid, $cact, $user){
  if (!$user){
     echo "error";
     return false;
  }

  global $events_data;
  global $user_base_query;

  $success = false;

  $con = get_mysql_connection();

  $uid = $user['uid'];
  // TODO: separate db logic from business logic 

  $check_query = "SELECT * FROM score WHERE `uid`=" . $uid . " and `eid`=" . $eid . " and `act`=1 and `comment`='" . urlencode($cact) . "'";

  $check_result = mysql_query($check_query, $con)
            or die('mysql_query: ' . mysql_error());
  $fetch_result = mysql_fetch_assoc($check_result);

  if (!$fetch_result){ // ensure this user has not been scanned
     $success = true;

     $scan_query = "INSERT INTO score (`uid`, `eid`, `ts`, `act`, `pts`, `comment`) VALUES ('" . $uid . "','" . $eid . "',NOW(),'1','" . $events_data[$eid]['points'][$cact] . "','" . urlencode($cact) . "')";  

     $scan_result = mysql_query($scan_query, $con)
        or die('mysql_query: ' . mysql_error());
  }
  mysql_close($con);

  return $success;
}
function get_all_scans() {
  return query_to_array("SELECT * FROM `score`");
}

function get_all_attendance_scans() {
  $con = get_mysql_connection();
  
  return query_to_array("SELECT * FROM `score` WHERE `act` = " . SCORE_TYPE_ATTENDANCE);
}

function get_event_attendance_by_time_slice($eventId, $timeSlice) {
  $con = get_mysql_connection();

  $result = mysql_query("SELECT count(*) as att, (60 * time(hour(`ts`)) + time(minute(`ts`))) - ((60 * time(hour(`ts`)) + time(minute(`ts`))) MOD " . $timeSlice . ") AS timegroup FROM `score` WHERE `act` = 0 AND `eid` = " . $eventId . " GROUP BY `timegroup`", $con)
            or die('mysql_query: ' . mysql_error());

  mysql_close($con);

  $rows = array();

  while (($row = mysql_fetch_array($result)) != null) {
    $rows[] = $row;
  }

  return $rows;

}

function get_bid($uid){
   $con = get_mysql_connection();
   $result = mysql_query("SELECT * FROM `users` WHERE `uid` = " . $uid);
   mysql_close($con);
   $row = mysql_fetch_array($result);
   return $row['bid'];
}
function log_entry($mode, $action, $optionalFieldsMap=array()) {
  $optionalFields = array();
  $optionalValues = array();

  foreach ($optionalFieldsMap as $field => $value) {
    $optionalFields[] = "`" . $field . "`";
    $optionalValues[] = "\"" . $value . "\"";
  }
  $query = "INSERT INTO `log` (`mode`, `action`" . (count($optionalFields) > 0 ? ", " : "") . implode($optionalFields, ", ") . ") VALUES (" . $mode . ", " . $action . (count($optionalValues) > 0 ? ", " : "") . implode($optionalValues, ", ") . ")";

  $con = get_mysql_connection();

  mysql_query($query, $con)
  or die ('mysql_query: ' . mysql_error());

  mysql_close($con);
}

function get_pscanned($eid, $cact){
   $retval = array();

   $con = get_mysql_connection();

   $result = mysql_query("SELECT * FROM `score` WHERE `eid` = " . $eid . " and `act`=1 and `comment`='" . urlencode($cact) . "'", $con)
      or die('mysql_query:' . mysql_error());

   mysql_close($con);

   while (($row = mysql_fetch_array($result)) != null){
      $retval[] = $row['uid'];
   }

   return $retval;
}

function get_users_by_lastname($lastname) {
  global $user_base_query;

  return query_to_array($user_base_query . " WHERE `ln` = \"" . $lastname . "\"");
}

function swap_passports($old_bid, $new_bid) {
  $old_user = get_user($old_bid);
  $new_user = get_user($new_bid);

  $con = get_mysql_connection();

  mysql_query("DELETE FROM `users` WHERE `uid` = " . $new_user["uid"], $con)
  or die('mysql_query: ' . mysql_error());

  mysql_query("UPDATE `users` SET `bid` = " . $new_user["bid"] . ", `pin` = " . $new_user["pin"] . " WHERE `uid` = " . $old_user["uid"], $con)
  or die('mysql_query: ' . mysql_error());

  mysql_close($con);
}

function query_to_array($query, $conToUse=null) {
  $con = $conToUse;

  if ($con == null) {
    $con = get_mysql_connection();
  }

  $result = mysql_query($query, $con)
            or die('mysql_query: ' . mysql_error());

  if ($conToUse == null) {
    mysql_close($con);
  }

  $rows = array();

  while (($row = mysql_fetch_array($result)) != null) {
    $rows[] = $row;
  }

  return $rows;
}

function get_num_events_attended_counts() {
  return query_to_array("SELECT evts, count(*) AS att FROM (SELECT (SELECT count(*) FROM `score` WHERE `act` = " . SCORE_TYPE_ATTENDANCE . " AND `score`.`uid` = `users`.`uid`) AS evts FROM `users`) AS userevts GROUP BY `evts` HAVING `evts` > 0 ORDER BY `evts`");
}

function get_first_event_counts() {
  return query_to_array("SELECT `mevts`.`feid`, count(*) AS att FROM (SELECT (SELECT min(`eid`) FROM `score` WHERE `score`.`uid` = `users`.`uid` AND `act` = " . SCORE_TYPE_ATTENDANCE . ") AS feid FROM `users`) AS mevts GROUP BY `mevts`.`feid` HAVING `mevts`.`feid` IS NOT NULL ORDER BY `mevts`.`feid`");
}

function get_major_counts($eid=-1) {
  $query = "SELECT `ma`, count(*) AS att FROM `users` WHERE `s` = " . PASSPORT_STATE_REGISTERED . " ";

  if ($eid != -1) {
    $query .= "AND `uid` IN (SELECT `uid` FROM `score` WHERE `act` = " . SCORE_TYPE_ATTENDANCE . " AND `eid` = " . $eid . ") ";
  }

  $query .= "GROUP BY `ma` ORDER BY `att`";

  return query_to_array($query);
}
?>

