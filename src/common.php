<?PHP

// DEPRECATED - Will move to MySQL
$events = array(
  0  => "Openeing Ceremony/Smile n' Nod",
  1  => "Wii Tournament",
  2  => "Ultimate Frisbee",
  3  => "Ms. Engineer Contest",
  4  => "Bowling Night",
  5  => "Engineering Issues",
  6  => "Boat Challenge",
  7  => "ACM Event",
  8  => "Farmer's Market",
  9  => "Faculty Appreciation Breakfast",
  10 => "Capture the Flag",
  11 => "Egg Launch",
  12 => "Casino Night",
  13 => "Outreach Activity",
  14 => "ESC Beta Test");

// DEPRECATED - Will move to MySQL
$events_data = array(
  0  => Array("name"=>"Openeing Ceremony/Smile n' Nod",
              "points"=>Array("volunteer"=>1)),
  1  => Array("name"=>"Wii Tournament",
              "points"=>Array("1st place"=>8,"2nd place"=>4,"volunteer"=>1)),
  2  => Array("name"=>"Ultimate Frisbee",
              "points"=>Array("winner"=>2,"volunteer"=>1)),
  3  => Array("name"=>"Ms. Engineer Contest",
              "points"=>Array("volunteer"=>1)),
  4  => Array("name"=>"Bowling Night",
              "points"=>Array("volunteer"=>1)),
  5  => Array("name"=>"Engineering Issues",
              "points"=>Array("volunteer"=>1)),
  6  => Array("name"=>"Boat Challenge",
              "points"=>Array("winner"=>0,"volunteer"=>1)),
  7  => Array("name"=>"ACM Sandwich Challange",
              "points"=>Array("winner"=>8,"volunteer"=>1)),
  8  => Array("name"=>"Farmer's Market",
              "points"=>Array("volunteer"=>1)),
  9  => Array("name"=>"Faculty Appreciation Breakfast",
              "points"=>Array("volunteer"=>1)), 
  10 => Array("name"=>"Capture the Flag",
              "points"=>Array("winner"=>2,"volunteer"=>1)),
  11 => Array("name"=>"Egg Launch",
              "points"=>Array("distance"=>8,"most creative"=>4,"volunteer"=>1)),
  12 => Array("name"=>"Casino Night",
              "points"=>Array("volunteer"=>1)),
  13 => Array("name"=>"Outreach Activity",
              "points"=>Array("volunteer"=>2)),
  14 => Array("name"=>"ESC Beta Test",
              "points"=>Array()),
  );

// DEPRECATED - Will move to MySQL
$majors = array(
  "AERO" => "Aerospace Engineering",
  "ARCE" => "Architecutral Engineering",
  "BMED" => "Biomedical Engineering",
  "BRAE" => "BioResource and Agricultural Engineering",
  "CE"   => "Civil Engineering",
  "CPE"  => "Computer Engineering",
  "CSC"  => "Computer Science",
  "EE"   => "Electrical Engineering",
  "ENVE" => "Environmental Engineering",
  "GENE" => "General Engineering",
  "IE"   => "Industrial Engineering",
  "MATE" => "Materials Engineering",
  "MFGE" => "Manufacturing Engineering",
  "ME"   => "Mechanical Engineering",
  "SE"   => "Software Engineering",
  "OTHR" => "Other");

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

define(PASSPORT_EMAIL_OPT_IN, 0);
define(PASSPORT_EMAIL_OPT_OUT, 1);

define(SCORE_ATTENDANCE_POINTS, 1);

define(SCORE_TYPE_ATTENDANCE, 0);
define(SCORE_TYPE_POINTS, 1);

define(DEFAULT_STATISTICS_TIME_SLICE, 10);

// Utility Functions

// Determines if the given user has registered their passport.
//
// Args:
//   bid - the barcode id of the passport to check
//
// Returns:
//   True if a passport with the given barcode id exists and is registered,
//   false otherwise.
function is_registered($bid) {
  $user_record = get_user($bid);

  return $user_record != null && $user_record["s"] == PASSPORT_STATE_REGISTERED;
}

// Gets the username provided by the user via HTTP authentication.
//
// Returns:
//   The username provided by the user via HTTP authentication.
function http_authenticated_user() {
  return $_SERVER["PHP_AUTH_USER"];
}

?>
