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
<title>eWeek - Team Roster</title>
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

    <h1>eWeek Team Roster for <?PHP echo($args["team"]["name"]); ?></h1>

    <div class="team_reg_add">
		
		<br />
		    
		<?PHP
        if (isset($args["teamMemberJustAdded"])) {
        ?>
        <p>Just added <?PHP echo($args["teamMemberJustAdded"]["fn"] . " " . $args["teamMemberJustAdded"]["ln"]); ?> (barcode <?PHP echo($args["teamMemberJustAdded"]["bid"]); ?>) to your team.</p>
        <?PHP
        }
        ?>
		<?PHP
        if (isset($args["teamMemberJustRemoved"])) {
        ?>
        <p>Just removed  <?PHP echo($args["teamMemberJustRemoved"]["fn"] . " " . $args["teamMemberJustRemoved"]["ln"]); ?> (barcode <?PHP echo($args["teamMemberJustRemoved"]["bid"]); ?>) from your team.</p>
        <?PHP
        }
        ?>
        
        <?PHP
        if ($args["alreadyOnTeam"]) {
        ?>
        <span style="color: red">Cannot add member, already on a team.</span>
        <?PHP
        }
        ?>
        
        <?PHP
        if ($args["teamMemberBad"]) {
        ?>
        <span style="color: red">Cannot add member, invalid barcode or pin.</span>
        <?PHP
        }
        ?>
        
        <?PHP
        if ($args["teamFull"]) {
        ?>
        <span style="color: red">Cannot add member, team is full.</span>
        <?PHP
        }
        ?>
        
        <p>Your team has <?PHP echo($args["team"]["pts"]); ?> point(s).</p>
        
        <?PHP
        if (count($args["teamMembers"]) > 0) {
        ?>
        
        <br />
        <h2>Current Members</h2>
        
        <table border="1" cellspacing="1" cellpadding="4" class="stats_table">
          <tr bgcolor="#fee3ad">
            <td width="350px"><strong>Name (Barcode)</strong></td>
            <td width="100px"><strong>Points</strong></td>
            <td width="100px"></td>
          </tr>
        
        <?PHP
          foreach ($args["teamMembers"] as $teamMember) {
        ?>
          <tr>
            <td><?PHP echo($teamMember["fn"] . " " . $teamMember["ln"]); ?> (<?PHP echo($teamMember["bid"]); ?>)</td>
            <td><?PHP echo($teamMember["pts"]); ?></td>
            <td>
            <?PHP
              if ($args["bid"] != $teamMember["bid"]) {
            ?>
            <form action="controller.php?state=team_registration" method="POST">
            <input type="hidden" name="teamRegistrationBid" value="<?PHP echo($teamMember["bid"]); ?>" />
            <input type="hidden" name="teamRegistrationAction" value="remove" />
            <input type="hidden" name="bid" value="<?PHP echo($args["bid"]); ?>" />
            <input type="hidden" name="pin" value="<?PHP echo($args["pin"]); ?>" />
            <input type="submit" value="Remove Team Member" />
            </form>
            <?PHP
              }
            ?>
            </td>
          </tr>
        <?PHP
          }
        ?>
        
        </table>
        
        <?PHP
        }
        ?>
        
        <?PHP
        if (count($args["teamMembers"]) < MAX_TEAM_MEMBERS) {
        ?>
        
        <br /><br />
        <h2>Add Members</h2>
        <form method="post" action="<?PHP echo($_SERVER["SCRIPT_NAME"]); ?>?state=team_registration">
          <label>Barcode Number
          <input type="text" name="teamRegistrationBid" id="teamRegistrationBid" />
          </label>
          <label>Pin
          <input type="text" name="teamRegistrationPin" id="teamRegistrationPin" />
          </label>
          <input type="submit" value="Add Team Member" class="stats_submit" />
          <input type="hidden" name="teamRegistrationAction" value="add" />
          <input type="hidden" name="bid" value="<?PHP echo($args["bid"]); ?>" />
          <input type="hidden" name="pin" value="<?PHP echo($args["pin"]); ?>" />
        </form>
        <?PHP
        }
        ?>
        <br />
        <p><a href="controller.php?state=team_leaderboard" class="golink">Team Leaderboard</a></p>
        <br />
        <form method="post" action="<?PHP echo($_SERVER["SCRIPT_NAME"]); ?>?state=stats">
          <input type="submit" value="Back to Stats" class="stats_submit" />
          <input type="hidden" name="bid" value="<?PHP echo($args["bid"]); ?>" />
          <input type="hidden" name="pin" value="<?PHP echo($args["pin"]); ?>" />
        </form>
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
