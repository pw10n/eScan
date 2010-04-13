<?php

require_once "../controller.php";
require_once "model.php";
require_once "view.php";

$state_map = array( 
   "" => main_model,
   "main" => main_model,
   "scan" => scan_model,
   "pscan" => pscan_model,
   "xhr_process_scan" => process_scan,
   "event_raffle" => event_raffle_model,
   "finale_raffle" => finale_raffle_model,
   "statistics" => statistics_model,
   "generate_pins" => generate_pins_model,
   "swap" => swap_model
);

$view_map = array(
   "" => main_view,
   "main" => main_view,
   "scan" => scan_view,
   "pscan" => pscan_view,
   "xhr_scan_info" => xhr_scan_info,
   "event_raffle" => event_raffle_view,
   "finale_raffle" => finale_raffle_view,
   "statistics" => statistics_view,
   "generate_pins" => generate_pins_view,
   "swap" => swap_view
);

main_controller_from_get($state_map, $view_map);

?>
