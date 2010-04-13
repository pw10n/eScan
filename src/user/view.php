<?PHP

if (!CONTROLLER_INCLUDED) {
  die("");
}

function login_view($args) {
  include("login_view.php");
}

function registration_view($args) {
  include("registration_view.php");
}

function stats_view($args) {
  include("stats_view.php");
}

function team_registration_create_view($args) {
  include("team_registration_create_view.php");
}

function team_registration_add_view($args) {
  include("team_registration_add_view.php");
}

function team_leaderboard_view($args) {
  include("team_leaderboard_view.php");
}

?>

