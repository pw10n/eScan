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

