<?PHP

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
