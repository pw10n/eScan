<?PHP

require_once "../controller.php";
require_once "model.php";
require_once "view.php";

$state_map = array(
  "" => default_model,
  "login" => login_model,
  "registration" => registration_model,
  "stats" => stats_model,
  "team_registration" => team_registration_model,
  "team_leaderboard" => team_leaderboard_model
);

$view_map = array(
  "" => login_view,
  "login" => login_view,
  "registration" => registration_view,
  "stats" => stats_view,
  "team_registration_create" => team_registration_create_view,
  "team_registration_add" => team_registration_add_view,
  "team_leaderboard" => team_leaderboard_view
);

main_controller_from_get($state_map, $view_map);

?>

