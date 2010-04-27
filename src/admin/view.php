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


function main_view($args){
   include "main_view.php";
}

function scan_view($args){
   include "scan_view.php";
}

function pinfo_view($args){

   //event, how many points, comment 

}

function pscan_view($args){
   include "pscan_view.php";
   // barcode

}

function error_view($args){
   echo '<html>';
   echo '<head><title>Error</title></head>';
   echo $args['message'];
   echo '</html>';
}

function event_raffle_view($args) {
  include("event_raffle_view.php");
}

function finale_raffle_view($args) {
  include("finale_raffle_view.php");
}

function statistics_view($args) {
  include("statistics_view.php");
}

function generate_pins_view($args) {
  include("generate_pins_view.php");
}

function xhr_scan_info($args){
   echo json_encode(array('state'=>$args['state'], 'name'=>$args['name'], 'bid'=>$args['bid']));
}

function swap_view($args) {
  include("swap_view.php");
}

?>
