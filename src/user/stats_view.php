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
<title>Passport Statistics</title>
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

    <h1>Discovering Research Week Passport Statistics</h1>

    <br />
    
    <div class="stats">
    
		<?PHP
        $is_registered_for_team = false;
        $is_team_captain = false;
        
        if (isset($args["team"]) && strcmp($args["team"]["cid"], $args["uid"]) == 0) {
          $is_team_captain = true;
        }
        
        if (isset($args["registration"]) && $args["registration"]["tid"] != -1) {
          $is_registered_for_team = true;
        }
        
        $eligible_for = NULL;
        $next_prize = NULL;
        $events_needed = 0;
        
        foreach ($args["prizeLevels"] as $num_events => $prize) {
          if ($next_prize == NULL) {
            $next_prize = $prize;
            $events_needed = $num_events - $args["registration"]["evts"];
          }
          
          if ($args["registration"]["evts"] >= $num_events) {
            $eligible_for = $prize;
            $next_prize = NULL;
          }
        }
        
        ?>
        
        <?PHP
        if (isset($args["justRegistered"]) && $args["justRegistered"]) {
        ?>
        
        <p><strong>Thanks for registering!</strong></p>
        
        <?PHP
        }
        ?>

        <a href="controller.php">Logout</a>
 
        <div class="stats_note">
            <p>Hi <?PHP echo(isset($args["registration"]) ? $args["registration"]["fn"] : ""); ?>, you have attended <strong><?PHP echo($args["registration"]["evts"]); ?></strong> event(s).  
            
            <?PHP
            if ($eligible_for != NULL) {
            ?>
            
            You are eligible for the drawing for <?PHP echo($eligible_for); ?>.  
            
            <?PHP
            }
            ?>
            
            <?PHP
            if ($next_prize != NULL) {
            ?>
            
            You can get eligible for the drawing for <?PHP echo($next_prize); ?> by attending <strong><?PHP echo($events_needed); ?></strong> more event(s).  
            
            <?PHP
            }
            ?>
            
            </p>
        </div>
        
        <br /><br />
        <h2>Passport History</h2>
        <br />
        <?PHP
        if (count($args["scores"]) > 0) {
        ?>
        <table border="1" cellspacing="1" cellpadding="4" class="stats_table">
            <tr bgcolor="#fee3ad">
                <td width="350px"><strong>Event</strong></td>
                <td width="150px"><strong>Reason</strong></td>
                <?PHP if ($is_registered_for_team) { ?>
                <td width="100px"><strong>Points</strong></td>
                <?PHP } ?>

            </tr>
            
            <?PHP
              foreach ($args["scores"] as $score) {
            ?>
            
            <tr>
                <td><?PHP echo($args["events"][$score["eid"]]); ?></td>
                <td><?PHP echo($score["act"] == 0 ? "Attended" : urldecode($score["comment"])); ?></td>
                <?PHP if ($is_registered_for_team) { ?>
                <td><?PHP echo(isset($score["pts"]) ? $score["pts"] : 0); ?></td>
                <?PHP } ?>
            </tr>
            
            <?PHP
              }
            ?>
        </table>
        
        <?PHP
        } else {
        ?>
        
        <p>You have not yet attended any events.  Once you've attended an event, you will see your history here.</p>
        
        <?PHP
        }
        ?>
        
	</div>

</div>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-12936006-3");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>

