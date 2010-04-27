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


// Used to ensure that this file is only included once.
define("CONTROLLER_INCLUDED", 1);

// Helper function that executes main_controller using the state from the HTTP
// GET parameters.
//
// Args:
//   states - an associative array of state names to state handling functions
//   views - an associative array of view names to view functions
//
// See: main_controller($state, $states, $views)
function main_controller_from_get($states, $views) {
  // Use the default state if no GET state parameters were specified.
  $state = "";

  // Determine if we have been given a state in the GET parameters.
  if (isset($_GET["state"]) && isset($states[$_GET["state"]])) {
    $state = $_GET["state"];
  }

  main_controller($state, $states, $views);
}

// The main controller function that handles the given state and then shows the
// view requested by the state function's return value.
//
// Args:
//   state - the name of the state to execute
//   states - an associative array of state names to state handling functions;
//            state handling functions take no arguments and return an
//            associative array as follows:
//              "view" -> name of the view to show
//              "args" -> associative array of arguments to pass to the view
//   views - an associative array of view names to view functions; view
//           functions take in an associative array of arguments as a parameter
//           and have no return value
function main_controller($state, $states, $views) {
  // Verify that the default state has been set.
  if (!isset($states[""])) {
    die("Must set default state.");
  }

  // Verify that the default view has been set.
  if (!isset($views[""])) {
    die("Must set default view.");
  }

  // Make sure that the desired state exists.
  if(!array_key_exists($state, $states)){
    die("Invalid State");
  }

  // Execute the model code for the state and capture the result.
  $result = $states[$state]();

  // Create an empty array for view args.
  $view_args = array();

  // If the state code returned a view args array, use it.
  if (isset($result["args"])) {
    $view_args = $result["args"];
  }

  // Use the default view if none was specified.
  $view = "";

  if (isset($views[$result["view"]])) {
    $view = $result["view"];
  }

  // Determine if we have been given back a valid view.
  if (!array_key_exists($view, $views)){
    die("Invalid View");
  }

  // Call the view code.
  $views[$view]($view_args);
}

?>
