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


if (!CONTROLLER_INCLUDED) {
  die("");
}

require_once("../common.php");
require_once("../dbconn.php");

// A simple model that directs the user to the login page.
//
// Returns:
//   A response array suitable for the action(s) taken by this model.
function default_model() {
  return handle_login();
}

// Executes the given model function only if the user supplied barcode id and
// pin represent a valid user.  Otherwise, the login module is executed.
//
// Args:
//   model_function - the model function to execute, this should be of the form
//                    function($bid, $pin)
//
// Returns:
//   A response array suitable for the action(s) taken by this model (if any).
function do_logged_in_model($model_function) {
  if (!post_has_login_info()) {
    return handle_login();
  }

  $bid = sanitized_bid();
  $pin = sanitized_pin();

  // Check for valid login
  if (validate_login($bid, $pin)) {
    return $model_function($bid, $pin);
  } else {
    return handle_failed_login();
  }
}

// The model for handling user login.
//
// Returns:
//   A response array suitable for the action(s) taken by this model.
function login_model() {
  return do_logged_in_model(login_model_helper);
}

function login_model_helper($bid, $pin) {
  // Check if the passport has been registered already or not.  New passports
  // need to be registered.  Already registered passports go straight to the
  // stats screen.

  $user = get_user($bid);

  switch($user["s"]) {
    case PASSPORT_STATE_UNREGISTERED:
      return handle_registration($bid, $pin);
      break;

    case PASSPORT_STATE_REGISTERED:
      return handle_stats($bid, $pin);
      break;

    case PASSPORT_STATE_SWAPPED_OUT:
    default:
      return handle_failed_login();
  }
}

// The model for handling user registration
//
// Returns:
//   A response array suitable for the action(s) taken by this model.
function registration_model() {
  return do_logged_in_model(registration_model_helper);
}

function registration_model_helper($bid, $pin) {
  // Make sure that the passport has not already been registered.  If it has
  // already been registered, then we can go straight to the stats.  If not,
  // we try to submit the user's registration.
  if (is_registered($bid)) {
    return handle_stats($bid, $pin);
  } else {
    $registration = sanitized_registration();

    // Make sure that the entered registration fields are valid.
    $badRegistrationFields = validate_registration($registration);

    if (count($badRegistrationFields) == 0) {
      register_user($bid, $registration);
      $user = get_user($bid);
      log_entry(LOG_MODE_USER,
                LOG_USER_ACTION_REGISTER,
                array("actorUid" => $user["uid"],
                      "actorBid" => $bid,
                      "targetUid" => $user["uid"],
                      "targetBid" => $bid,
                      "comment" => addslashes(serialize($registration))));
      return handle_stats($bid, $pin, array("justRegistered" => true));
    } else {
      return handle_registration($bid,
                                 $pin,
            			               $registration,
                                 array("badRegistration" =>
                                         $badRegistrationFields));
    }
  }
}

// Model for handling user statistics requests.
//
// Returns:
//   A response array suitable for the action(s) taken by this model.
function stats_model() {
  return do_logged_in_model(stats_model_helper);
}

function stats_model_helper($bid, $pin) {
  return handle_stats($bid, $pin);
}

function team_registration_model() {
  return do_logged_in_model(team_registration_model_helper);
}

function team_registration_model_helper($bid, $pin) {
  $user = get_user($bid);

  if ($user != null) {
    // The user is not on a team, so they can start one.
    if ($user["tid"] == PASSPORT_NO_TEAM_TID) {
      if (isset($_POST["teamRegistrationAction"])) {
        return handle_team_registration($bid, $pin);
      }

      return array("view" => "team_registration_create",
                   "args" => array("bid" => $bid,
                                   "pin" => $pin));
    }

    // Make sure that they are the captain of the team that they are on.
    $team = get_team($user["tid"]);
    if ($team != null && strcmp($team["cid"], $user["uid"]) == 0) {
      return handle_team_registration($bid, $pin);
    }
  }

  return handle_stats($bid, $pin);
}

function team_leaderboard_model() {
  return array("view" => "team_leaderboard",
               "args" => array("teams" => get_all_teams()));
}

// Returns a sanitized registration array based on the HTTP POST parameters.
//
// Returns:
//   A sanitized registration array populated using the HTTP POST parameters.
function sanitized_registration() {
  $registration = array();
  $registration["fn"] = trim(filter_input(INPUT_POST,
                                          "fn",
                                          FILTER_SANITIZE_SPECIAL_CHARS,
                                          FILTER_FLAG_STRIP_LOW |
                                              FILTER_FLAG_STRIP_HIGH));
  $registration["ln"] = trim(filter_input(INPUT_POST,
                                          "ln",
                                          FILTER_SANITIZE_SPECIAL_CHARS,
                                          FILTER_FLAG_STRIP_LOW |
                                              FILTER_FLAG_STRIP_HIGH));
  $registration["em"] = trim(filter_input(INPUT_POST,
                                          "em",
                                          FILTER_SANITIZE_EMAIL));

  $registration["ma"] = trim(filter_input(INPUT_POST,
                                          "ma",
                                          FILTER_SANITIZE_SPECIAL_CHARS,
                                          FILTER_FLAG_STRIP_LOW |
                                              FILTER_FLAG_STRIP_HIGH));
  $registration["opt"] = trim(filter_input(INPUT_POST,
                                           "opt",
                                           FILTER_SANITIZE_SPECIAL_CHARS,
                                           FILTER_FLAG_STRIP_LOW |
                                               FILTER_FLAG_STRIP_HIGH));

  // Put the opt-out field in the expected format.
  if (strcmp($registration["opt"], "on") == 0) {
    $registration["opt"] = PASSPORT_EMAIL_OPT_OUT;
  } else {
    $registration["opt"] = PASSPORT_EMAIL_OPT_IN;
  }

  return $registration;
}

// Determines if the given data is a valid registration.
//
// Args:
//   registration - array containing registration data to validate
//
// Returns:
//   An array of the fields that are invalid.
function validate_registration($registration) {
  $invalid_fields = array();

  if (strlen($registration["fn"]) == 0 ||
      count(preg_grep("/^[a-zA-Z]+$/", array($registration["fn"]))) != 1) {
    $invalid_fields[] = "fn";
  }

  if (strlen($registration["ln"]) == 0 ||
      count(preg_grep("/^[a-zA-Z]+$/", array($registration["ln"]))) != 1) {
    $invalid_fields[] = "ln";
  }

  if (!array_search($registration["ma"],
                    array_map(extract_major_code, get_majors()))) {

    $invalid_fields[] = "ma";
  }

  if (!filter_var($registration["em"], FILTER_VALIDATE_EMAIL)) {
    $invalid_fields[] = "em";
  }

  return $invalid_fields;
}

// Handles a failed login attempt.
//
// Return:
//   A response array indicating a failed login attempt.
function handle_failed_login() {
  return handle_login(array("loginFailure" => true));
}

// Handles requests that will be replied to with a login page.
//
// Args:
//   extra_args - extra arguments to pass to the view
//
// Return:
//   A response array for showing the login view.
function handle_login($extra_args=array()) {
  return array("view" => "login", "args" => $extra_args);
}

// Handles the reply to a request to register a user.
//
// Args:
//   bid - barcode id of the user
//   pin - pin for the user
//   registration - registration array to get data from
//   extra_args - extra arguments to pass to the view
//
// Return:
//   A response array suitable for the action(s) taken.
function handle_registration($bid,
                             $pin,
			     $registration=array(),
			     $extra_args=array()) {

  return array("view" => "registration",
               "args" => array("bid" => $bid,
                               "pin" => $pin,
                               "registration" => $registration,
                               "majors" => get_majors()) +
		         $extra_args);
}

// Gets the user's statistics from the database and responds with them for the
// stats view.
//
// Args:
//   bid - the barcode id of the user to get stats for
//   pin - the user's pin
//   extra_args - extra arguments to return to the view
//
// Returns:
//   A response array suitable for handling a stats query.
function handle_stats($bid, $pin, $extra_args=array()) {

  global $events;
  global $prize_levels;

  $scores = get_user_scores($bid);
  $user = get_user($bid);

  return array("view" => "stats",
               "args" => (array("bid" => $bid,
                                "pin" => $pin,
                                "uid" => $user["uid"],
                                "registration" => $user,
                                "scores" => $scores,
                                "events" => $events,
                                "prizeLevels" => $prize_levels,
                                "team" => get_team($user["tid"])) +
                          $extra_args));
}

function handle_team_registration($bid, $pin, $extra_args=array()) {
  $team_registration = sanitized_team_registration();
  $user = get_user($bid);
  $tid = $user["tid"];
  $team = get_team($tid);
  $team_members = get_team_members($tid);

  // Choose appropriate action to perform.
  if (strcmp($team_registration["teamRegistrationAction"], "remove") == 0) {
    $userToRemove = get_user($team_registration["teamRegistrationBid"]);
    if ($userToRemove != null && $userToRemove["tid"] == $tid) {
      assign_user_to_team($userToRemove["bid"], PASSPORT_NO_TEAM_TID);
      log_entry(LOG_MODE_USER,
                LOG_USER_ACTION_REMOVE_TEAM_MEMBER,
                array("actorUid" => $user["uid"],
                      "actorBid" => $user["bid"],
                      "targetUid" => $user_to_add["uid"],
                      "targetBid" => $user_to_add["bid"],
                      "targetTid" => PASSPORT_NO_TEAM_TID,
                      comment => addslashes(serialize($team_registration))));

      $team = get_team($tid);
      $team_members = get_team_members($tid);
      return array("view" => "team_registration_add",
                   "args" => array("bid" => $bid,
                                   "pin" => $pin,
                                   "teamMemberJustRemoved" => $user_to_remove,
                                   "teamMembers" => $team_members,
                                   "team" => $team) + $extra_args);
    }
  } else if (strcmp($team_registration["teamRegistrationAction"], "create") == 0) {
    if (valid_team_name($team_registration["teamName"])) {
      $tid = register_team($team_registration["teamName"], $user["uid"]);

      log_entry(LOG_MODE_USER,
                LOG_USER_ACTION_CREATE_TEAM,
                array("actorUid" => $user["uid"],
                      "actorBid" => $user["bid"],
                      "targetTid" => $tid,
                      comment => addslashes(serialize($team_registration))));

      if ($tid == PASSPORT_NO_TEAM_TID) {
        return array("view" => "team_registration_create",
                     "args" => array("bid" => $bid,
                                     "pin" => $pin) + $extra_args);
      }

      assign_user_to_team($bid, $tid);

      log_entry(LOG_MODE_USER,
                LOG_USER_ACTION_ADD_TEAM_MEMBER,
                array("actorUid" => $user["uid"],
                      "actorBid" => $user["bid"],
                      "targetUid" => $user["uid"],
                      "targetBid" => $user["bid"],
                      "targetTid" => $tid,
                      comment => addslashes(serialize($team_registration))));

      $team_members = get_team_members($tid);
      $team = get_team($tid);
      return array("view" => "team_registration_add",
                   "args" => array("bid" => $bid,
                                   "pin" => $pin,
                                   "teamJustCreated" => true,
                                   "teamMembers" => $team_members,
                                   "team" => $team) + $extra_args);
    } else {
      return array("view" => "team_registration_create",
                   "args" => array("bid" => $bid,
                                   "pin" => $pin,
                                   "teamNameInvalid" => true) + $extra_args);
    }
  } else if (strcmp($team_registration["teamRegistrationAction"], "add") == 0) {
    $user = get_user($bid);
    $team = get_team($user["tid"]);
    $team_members = get_team_members($user["tid"]);
   
    if (count($team_members) < MAX_TEAM_MEMBERS) {
      if (validate_login($team_registration["teamRegistrationBid"],
                         $team_registration["teamRegistrationPin"])) {
        $user_to_add = get_user($team_registration["teamRegistrationBid"]);

        if ($user_to_add["tid"] == PASSPORT_NO_TEAM_TID) {
          assign_user_to_team($user_to_add["bid"], $tid);

          log_entry(LOG_MODE_USER,
                    LOG_USER_ACTION_ADD_TEAM_MEMBER,
                    array("actorUid" => $user["uid"],
                          "actorBid" => $user["bid"],
                          "targetUid" => $user_to_add["uid"],
                          "targetBid" => $user_to_add["bid"],
                          "targetTid" => $tid,
                          comment => addslashes(serialize($team_registration))));

          $team_members = get_team_members($user["tid"]);
          return array("view" => "team_registration_add",
                       "args" => array("bid" => $bid,
                                       "pin" => $pin,
                                       "teamMemberJustAdded" => $user_to_add,
                                       "teamMembers" => $team_members,
                                       "team" => $team) + $extra_args);
        } else {
          return array("view" => "team_registration_add",
                       "args" => array("bid" => $bid,
                                       "pin" => $pin,
                                       "alreadyOnTeam" => true,
                                       "teamMembers" => $team_members,
                                       "team" => $team) + $extra_args);
        }
      } else {
        return array("view" => "team_registration_add",
                     "args" => array("bid" => $bid,
                                     "pin" => $pin,
                                     "teamMemberBad" => true,
                                     "teamMembers" => $team_members,
                                     "team" => $team) + $extra_args);
      }
    } else {
      return array("view" => "team_registration_add",
                   "args" => array("bid" => $bid,
                                   "pin" => $pin,
                                   "teamFull" => true,
                                   "teamMembers" => $team_members,
                                   "team" => $team) + $extra_args);
    }
  }

  // No action specified, just need to show the user the current members.
  return array("view" => "team_registration_add",
                   "args" => array("bid" => $bid,
                                   "pin" => $pin,
                                   "teamMembers" => $team_members,
                                   "team" => $team) + $extra_args);
}

function sanitized_team_registration() {
  $team_registration = array();

  $team_registration["teamRegistrationAction"] =
      $_POST["teamRegistrationAction"];
  $team_registration["teamName"] = trim(filter_input(INPUT_POST,
                                                     "teamName",
                                                      FILTER_SANITIZE_SPECIAL_CHARS,
                                                      FILTER_FLAG_STRIP_LOW |
                                                          FILTER_FLAG_STRIP_HIGH));
  $team_registration["teamRegistrationBid"] =
      filter_input(INPUT_POST,
                   'teamRegistrationBid',
                   FILTER_SANITIZE_NUMBER_INT);
  $team_registration["teamRegistrationPin"] =
      filter_input(INPUT_POST,
                   'teamRegistrationPin',
                   FILTER_SANITIZE_NUMBER_INT);

  return $team_registration;
}

function valid_team_name($team_name) {
  return strlen($team_name) > 0 &&
    count(preg_grep("/^[a-zA-Z ]+$/", array($team_name))) == 1;
}

// Determines if the given barcode id and pin are valid.
//
// Args:
//   bid - the barcode id of the user
//   pin - the supplied pin for the user
//
// Returns:
//   True if the user exists and has the given pin, false otherwise.
function validate_login($bid, $pin) {
  $user_record = get_user($bid);

  return $user_record != null &&
         isset($user_record["pin"]) &&
         strcmp($user_record["pin"], $pin) == 0;
}

// Determines if the post arguments include login data.
//
// Returns:
//   True if the $_POST array includes the bid and pin, false otherwise.
function post_has_login_info() {
  return isset($_POST["bid"]) && isset($_POST["pin"]);
}

// Sanitizes the user provided barcode id.
//
// Returns:
//   a safe copy of the user-supplied barcode id
function sanitized_bid() {
  return filter_input(INPUT_POST, 'bid', FILTER_SANITIZE_NUMBER_INT);
}

// Sanitizes the user provided pin.
//
// Returns:
//   a safe copy of the user-supplied pin
function sanitized_pin() {
  return filter_input(INPUT_POST, 'pin', FILTER_SANITIZE_NUMBER_INT);
}

?>

