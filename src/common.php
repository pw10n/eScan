<?PHP
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

// DEPRECATED - Will move to MySQL
$events = array(
  0  => "Micro System Technology",
  1  => "Development of 3cm Ion Thruster for Spacecraft Applications",
  2  => "Compostite Sturctures-Delarmination Arres",
  3  => "UAV Lab- Interdisciplinary design and construction of UAVs",
  4  => "Development of Adaptive Technologies to Aid Persons of Disabilities- Dart-throwing Machines",
  5  => "Collabrative Agent Design",
  6  => "Interdisciplinary Satallite Design",
  );

// DEPRECATED - Will move to MySQL
$events_data = array(
  0  => Array("name"=>$events[0],
              "points"=>Array()),
  1  => Array("name"=>$events[1],
              "points"=>Array()),
  2  => Array("name"=>$events[2],
              "points"=>Array()),
  3  => Array("name"=>$events[3],
              "points"=>Array()),
  4  => Array("name"=>$events[4],
              "points"=>Array()),
  5  => Array("name"=>$events[5],
              "points"=>Array()),
  6  => Array("name"=>$events[6],
              "points"=>Array()),
  );

// DEPRECATED - Will move to MySQL
// An ascending SORTED array of prize levels.
$prize_levels = array(
  1 => "$50 Amazon Gift Card (1 of 3)",
  5 => "Netbook (1 of 2)",
  9 => "26\" LCD HDTV");

// Configuration Options
define(MAX_TEAM_MEMBERS, 10);

// Pre-Defined Constants
define(PASSPORT_NO_TEAM_TID, -1);

define(PASSPORT_STATE_UNREGISTERED, 1);
define(PASSPORT_STATE_REGISTERED, 2);
define(PASSPORT_STATE_SWAPPED_OUT, 3);

define(PASSPORT_EMAIL_OPT_IN, 0);
define(PASSPORT_EMAIL_OPT_OUT, 1);

define(SCORE_ATTENDANCE_POINTS, 1);

define(SCORE_TYPE_ATTENDANCE, 0);
define(SCORE_TYPE_POINTS, 1);

define(DEFAULT_STATISTICS_TIME_SLICE, 10);

// Utility Functions

// Gets the username provided by the user via HTTP authentication.
//
// Returns:
//   The username provided by the user via HTTP authentication.
function http_authenticated_user() {
  return $_SERVER["PHP_AUTH_USER"];
}

?>
