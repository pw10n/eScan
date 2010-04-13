<?PHP

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
