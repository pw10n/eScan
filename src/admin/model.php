<?php
/*
 *  Copyright (c) 2010 Cal Poly Engineering Student Council
 *
 *  Developers:
 *    Brian Oppenheim <brianopp@gmail.com> 
 *    Prentice Wongvibulsin <me@prenticew.com>
 *
 *  Project URL:
 *    http://www.prenticew.com/escan
 *
 *  This file is part of eScan.
 *
 *   eScan is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   eScan is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with eScan.  If not, see <http://www.gnu.org/licenses/>.
 */


require_once "../dbconn.php";
require_once "../common.php";

function main_model(){
   global $events_data;
   return array("view" => "main",
                "args" => array("events" => $events_data,
                                "user" => http_authenticated_user()));
}

function scan_model(){
   $eid = filter_input(INPUT_GET,'eid',FILTER_SANITIZE_NUMBER_INT);
   $event_info = get_event($eid);
   return array( "view"=>"scan", "args"=>array("eid"=>$eid, "event_info"=>$event_info) );
}

function pscan_model(){
   global $events_data;
   $urlec_act = $_GET['act']; 
   $act = urldecode($urlec_act);
   $eid = filter_input(INPUT_GET,'eid',FILTER_SANITIZE_NUMBER_INT);
   $bid = filter_input(INPUT_GET,'bid',FILTER_SANITIZE_NUMBER_INT);

   // get people who have already been scanned for htis
   //

   $scanned = false;
   $pscanned = get_pscanned($eid, $act);

   // do pscan if bid is provided
   //

   if (isset($_GET['bid'])){
      $user = get_user($bid);
      if (false == in_array($user['uid'], $pscanned)){
         do_pscan($eid, $act, $user);
         log_entry(LOG_MODE_ADMIN,
                   LOG_ADMIN_ACTION_SCAN_EXTRA_POINTS,
                   array("adminUser" => http_authenticated_user(),
                         "targetUid" => $user["uid"],
                         "targetBid" => $user["bid"],
                         "targetPoints" => $events_data[$eid]['points'][$act],
                         "targetEid" => $eid,
                         "comment" => addslashes($act)));
         $scanned = true;
         $pscanned[] = $user['uid'];
      }
   }

   $users = array();
   foreach ($pscanned as $uid){
      $users[] = get_user(get_bid($uid));
   }

   return array( "view"=>"pscan", "args"=>array("pscanned"=>$pscanned, "scanned"=>$scanned, "act"=>$act, "eid"=>$eid, "users"=>$users, "event"=>$events_data[$eid]) );
}

function process_scan(){
   $bid = filter_input(INPUT_GET,'bid',FILTER_SANITIZE_NUMBER_INT); 
   $eid = filter_input(INPUT_GET,'eid',FILTER_SANITIZE_NUMBER_INT);

   $user = get_user($bid);
   $name = "";

   if(!$user){
      // uninit
      $state = 0;
   }
   else if (PASSPORT_STATE_UNREGISTERED == $user['s']){
      // needs to register
      $state = 1;
   }
   else{
      // registered
      $state = 2;
      $name = $user['fn'] . " " . $user['ln'];
   }
   if($user){
      //update
      if (do_scan($eid,$user)){
        log_entry(LOG_MODE_ADMIN,
                  LOG_ADMIN_ACTION_SCAN_ATTENDANCE,
                  array("adminUser" => http_authenticated_user(),
                        "targetUid" => $user["uid"],
                        "targetBid" => $user["bid"],
                        "targetPoints" => SCORE_ATTENDANCE_POINTS,
                        "targetEid" => $eid));
      } else {
        $state = 3;
      }
   }

   return array( "view"=>"xhr_scan_info", "args"=>array("state"=>$state,"name"=>$name,"bid"=>$bid) );
}

function event_raffle_model() {
  // TODO: sanitize eventId
  global $events;

  if (isset($_POST["eventId"])) {
    $attendees = get_users_by_event($_POST["eventId"]);

    $origNumAttendees = count($attendees);
    for ($i = 0; $i < $origNumAttendees; $i++) {
      if ($attendees[$i]["elig"] != 1) {
        unset($attendees[$i]);
      }
    }

    $attendees = array_values($attendees);
    $winner = $attendees[rand(0, count($attendees) - 1)];

    if(!isset($winner)) {
      return array("view" => "event_raffle",
                   "args" => array("noWinner" => true,
                                   "events" => $events,
                                   "eventId" => $_POST["eventId"]));
    }

    return array("view" => "event_raffle",
                 "args" => array("winner" => $winner,
                                 "events" => $events,
                                 "eventId" => $_POST["eventId"]));
  } else {
    return array("view" => "event_raffle",
                 "args" => array("events" => $events));
  }
}

function finale_raffle_model() {
  // TODO: sanitize prizeLEvelMin
  global $prize_levels;

  if (isset($_POST["prizeLevelMin"])) {
    $eligibles = get_eligible_users_with_min_events($_POST["prizeLevelMin"]);
    $winner = $eligibles[rand(0, count($eligibles) - 1)];

    if (!isset($winner)) {
      return array("view" => "finale_raffle",
                   "args" => array("noWinner" => true,
                                   "prizeLevels" => $prize_levels,
                                   "prizeLevelMin" => $_POST["prizeLevelMin"]));
    }

    return array("view" => "finale_raffle",
                 "args" => array("winner" => $winner,
                                 "prizeLevels" => $prize_levels,
                                 "prizeLevelMin" => $_POST["prizeLevelMin"]));
  } else {
    return array("view" => "finale_raffle",
                 "args" => array("prizeLevels" => $prize_levels));
  }
}

function compare_timegroups ($arg1, $arg2) {
  return $arg1["timegroup"] - $arg2["timegroup"];
}

function statistics_model() {
  global $events;
  global $events_data;
  global $prize_levels;

  $eventStats = array();

  foreach (get_all_event_attendances() as $event) {
    $sliced = get_event_attendance_by_time_slice($event["eid"], DEFAULT_STATISTICS_TIME_SLICE);

    foreach ($sliced as $slice) {
      $time = $slice["timegroup"];
      $zeroesAdded = 0;
      if (isset($prevTime)) {
        for ($i = $prevTime + DEFAULT_STATISTICS_TIME_SLICE; $i < $time; $i += DEFAULT_STATISTICS_TIME_SLICE) {
          if ($zeroesAdded >= 3) {
            break;
          }

          $sliced[] = array("timegroup" => $i, "att" => 0);
          $zeroesAdded++;
        }
      }

      $prevTime = $time;
    }

    unset($prevTime);
    usort($sliced, compare_timegroups);

    $eventStats[$event["eid"]] = array("name" => $events[$event["eid"]],
                                       "attendance" => $event["att"],
                                       "sliced" => $sliced,
                                       "majors" => get_major_counts($event["eid"]));
  }

  foreach ($events as $eventId => $eventName) {
    if (!isset($eventStats[$eventId])) {
      $eventStats[$eventId] = array("name" => $eventName,
                                    "attendance" => 0,
                                    "sliced" => array(),
                                    "majors" => array());
    }
  }

  ksort($eventStats);

  $prizeEligibility = array();
  foreach ($prize_levels as $minEvents => $prize) {
    $prizeEligibility[$prize] =
        count(get_eligible_users_with_min_events($minEvents));
  }

  return array("view" => "statistics",
               "args" => array("eventStats" => $eventStats,
                               "numRegisteredPassports" => count(get_eligible_users_with_min_events(0)),
                               "numActionedPassports" => count(get_actioned_users()),
                               "prizeEligibility" => $prizeEligibility,
                               "totalScans" => count(get_all_scans()),
                               "averageAttendance" => count(get_all_attendance_scans()) / count($events_data),
                               "teams" => get_all_teams(),
                               "majors" => get_major_counts(),
                               "firstEvent" => get_first_event_counts(),
                               "numEvents" => get_num_events_attended_counts(),
                               "events" => $events));
}

function generate_pins_model() {
  // TODO: sanitize bidLower and bidUpper
  if (isset($_POST["bidLower"]) || isset($_POST["bidUpper"])) {
    if (isset($_POST["bidLower"]) && strlen($_POST["bidUpper"]) == 0) {
      $_POST["bidUpper"] = $_POST["bidLower"];
    }

    if (isset($_POST["bidLower"]) && strlen($_POST["bidLower"]) &&
        isset($_POST["bidUpper"]) && strlen($_POST["bidUpper"])) {
      if ($_POST["bidLower"] <= $_POST["bidUpper"]) {
        $pins = array();

        for ($bid = $_POST["bidLower"]; $bid <= $_POST["bidUpper"]; $bid++) {
          $user = get_user($bid);

          if ($user != null) {
            $pins[$bid] = $user["pin"];
          } else {
            $generated_pin = rand(1000, 9999);
            $pins[$bid] = $generated_pin;
            add_user($bid, $generated_pin);
            $user = get_user($bid);
            log_entry(LOG_MODE_ADMIN,
                      LOG_ADMIN_ACTION_GENERATE_PIN,
                      array("adminUser" => http_authenticated_user(),
                            "targetUid" => $user["uid"],
                            "targetBid" => $user["bid"]));
          }
        }

        return array("view" => "generate_pins",
                     "args" => array("pins" => $pins));
      }

      return array("view" => "generate_pins",
                   "args" => array("invalidParameter" => true));
    }

    return array("view" => "generate_pins",
                 "args" => array("missingParameter" => true));
  }

  return array("view" => "generate_pins",
               "args" => array());
}

function swap_model() {
  // TODO: sanitize
  $lastname = $_POST["ln"];
  $old_bid = filter_input(INPUT_POST, 'old_bid', FILTER_SANITIZE_NUMBER_INT);
  $new_bid = filter_input(INPUT_POST, 'new_bid', FILTER_SANITIZE_NUMBER_INT);

  if (isset($old_bid) || isset($new_bid)) {
    if (isset($old_bid) && isset($new_bid)) {
      $ouser = get_user($old_bid);
      $nuser = get_user($new_bid);

      if ($ouser != null && $ouser["s"] == PASSPORT_STATE_REGISTERED &&
          $nuser != null && $nuser["s"] == PASSPORT_STATE_UNREGISTERED) {

        swap_passports($old_bid, $new_bid);
        log_entry(LOG_MODE_ADMIN,
                  LOG_ADMIN_ACTION_SWAP_PASSPORT,
                  array("adminUser" => http_authenticated_user(),
                        "targetUid" => $ouser["uid"],
                        "targetBid" => $ouser["bid"],
                        "targetBid2" => $nuser["bid"],
                        "comment" => "Deleted UID: " . $nuser["uid"]));
        return array("view" => "swap",
                     "args" => array("justSwapped" => true,
                                     "old_bid" => $old_bid,
                                     "new_bid" => $new_bid));
      }
    }

    return array("view" => "swap",
                 "args" => array("matches" => get_users_by_lastname($lastname),
                                 "ln" => $lastname,
                                 "swapError" => true));
  } else if (isset($lastname)) {
    return array("view" => "swap",
                 "args" => array("matches" => get_users_by_lastname($lastname),
                                 "ln" => $lastname));
  }

  return array("view" => "swap",
               "args" => array());
}

?>
