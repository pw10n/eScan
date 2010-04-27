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


define("CONTROLLER_INCLUDED", 1);

function main_controller_from_get($states, $views) {
  // Determine if we have been given a state in the GET parameters.
  $state = "";

  if (isset($_GET["state"]) && isset($states[$_GET["state"]])) {
    $state = $_GET["state"];
  }

  main_controller($state, $states, $views);
}

function main_controller($state, $states, $views) {
  // Verify that defaults have been set for states and views.
  if (!isset($states[""])) {
    die("Must set default state.");
  }

  if (!isset($views[""])) {
    die("Must set default view.");
  }

  if(!array_key_exists($state, $states)){
    die("Invalid State");
  }

  // Execute the model code for the state and capture the result.
  $result = $states[$state]();

  $view_args = array();

  if (isset($result["args"])) {
    $view_args = $result["args"];
  }

  // Determine if we have been given back a valid view.
  $view = "";

  if (isset($views[$result["view"]])) {
    $view = $result["view"];
  }

  if (!array_key_exists($view, $views)){
    die("Invalid View");
  }

  // Call the view code.
  $views[$view]($view_args);
}

?>
