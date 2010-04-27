<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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


?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" media="all" href="../css/styles.css" />
<link type="image/x-icon" rel="shortcut icon" href="../images/favicon.ico"/>
<title>eWeek - Event Scan-In</title>

<script type="text/javascript" src="jquery-1.4.1.min.js"></script>
<script type="text/javascript">


var history_1 = ["",-1];
var history_2 = ["",-1];
var history_3 = ["",-1];
var history_4 = ["",-1];
var history_5 = ["",-1];

function push_history(v,id){
   history_5 = history_4;
   history_4 = history_3;
   history_3 = history_2;
   history_2 = history_1;
   history_1 = [v,id]; 
}  


function process_scan(){
   if (window.XMLHttpRequest){
      // IE7+, FF, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
   }
   else{
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }

   xmlhttp.onreadystatechange=function(){
      if(xmlhttp.readyState == 4){
         response_json = JSON.parse(xmlhttp.responseText);
         statcode = Number(response_json['state']);
         var el = document.getElementById('result');
         if ( 0 == statcode ){
            // RED
            el.innerHTML = "<h1># " + response_json['bid'] + " Invalid Passport</h1>";
            $('#result_wrap').css('background-color', '#ff2222');
            push_history("Invalid passport #" + response_json['bid'] + " scanned.",-1); 
         }
         else if ( 1 == statcode ){
            // YELLOW
            el.innerHTML = "<h1>Welcome.  Please register your passport.</h1> (barcode #" + response_json['bid'] + ")";
            $('#result_wrap').css('background-color', '#ffff00');
            push_history("Unregistered passport #" + response_json['bid'] + " scanned.",-1);
         }
         else if ( 2 == statcode ){
            el.innerHTML = "<h1>Welcome " + response_json['name'] + "! </h1> (barcode #" + response_json['bid']+ ")";
            $('#result_wrap').css('background-color', '#00ff00');
            push_history(response_json['name'] + "'s passport #" + response_json['bid'] + " scanned.",-1);
         }
         else if ( 3 == statcode ){
            el.innerHTML = "<h1># " + response_json['bid'] + " has already been stamped. </h1>";
            $('#result_wrap').css('background-color', '#ff6600');
            push_history("Passport #" + response_json['bid'] + " has already been scanned.", -1);
         }
         else{
            el.innerHTML = statcode;
            $('#result_wrap').css('background-color', '#f0f0f0');
         }
         var strHTML = history_1[0] + "<p>";
         strHTML += history_2[0] + "<p>";
         strHTML += history_3[0] + "<p>";
         strHTML += history_4[0] + "<p>";
         strHTML += history_5[0] + "<p>";

         var elh = document.getElementById('history');
         elh.innerHTML = strHTML;
      } 
   }

   bid = document.getElementById('barcode').value;
   eid = document.getElementById('eventid').value;
   document.getElementById('barcode').value = "";

   xmlhttp.open("GET","controller.php?state=xhr_process_scan&bid="+bid+"&eid="+eid,true);

   xmlhttp.send(null);
}



</script>
<style type="text/css">
body {
      background-color: #25AAE1;
}

#scanbox {
   width: 800px ;
   margin-left: auto ;
   margin-right: auto ;
   text-align: center;
   padding-top:10px;
   padding-bottom:10px;
   margin-bottom: 10px;
   background-color: #159AD1;
   color: #fff;
}

#result_wrap {
   width: 800px ;
   height: 200px ;
   margin-left: auto ;
   margin-right: auto ;
   text-align: center;
   background-color: #159AD1;
   border: 10px #159AD1 solid;
}

#result {
   position: relative;
   top: 40%;
}   

#history{
   margin-top: 10px;
   width: 800px ;
   height: 180px ;
   margin-left: auto ;
   margin-right: auto ;
   text-align: center;
   padding-top: 20px;
   background-color: #159AD1;
}
   
   

</style>

</head>

<body>

<div id="wrapper">
        <div id="header">
        <div class="left">
                <img src="../images/eweek_logo.png" alt="eWeek 2010" border="0" />
        </div>
        <div class="left">
                <img src="../images/bg.png" alt="" border="0" />
        </div>
    </div>


<div id="scanbox"> 
<h1>Event: <?php echo $args['event_info']['name']; ?></h1>
<form name="scanfrm" action="javascript:process_scan()">
Scan:<input type="hidden" name="eventid" id="eventid" value="<?php echo $args["eid"]; ?>" />
<input type="text" name="barcode" id="barcode" />
</form>
</div>
<p>

<div id="result_wrap"><div id="result"></div></div>
<p>

<div id="history"></div>
</div>

<a href="controller.php">Back to Admin Screen</a>

</body>

</html>
