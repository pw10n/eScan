<?php
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
