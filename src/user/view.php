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

