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
<title>eWeek - Passport Team Creation</title>
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

    <h1>eWeek Passport Team Creation</h1>
    
    <br /><br /><br />
    <div class="login">
		
        <br /><br />
		<?PHP
        if ($args["teamNameInvalid"]) {
        ?>
        
        <span class="login_fail">Team name invalid. Try again.</span>
        
        <?PHP
        }
		else {
			echo("<br />");
		}
        ?>
        <form method="post" action="<?PHP echo($_SERVER["SCRIPT_NAME"]); ?>?state=team_registration">
          <label for="teamName" class="login_label"><strong>Team Name:</strong></label>
          <input id="teamName" name="teamName" type="text" class="login_input" /><br>
          <input type="submit" value="Create Team" class="login_submit" />
          <input type="hidden" name="teamRegistrationAction" value="create" />
          <input type="hidden" name="bid" value="<?PHP echo($args["bid"]); ?>" />
          <input type="hidden" name="pin" value="<?PHP echo($args["pin"]); ?>" />
        </form>
        <p class="ortext">-- or --</p>
        <form method="post" action="<?PHP echo($_SERVER["SCRIPT_NAME"]); ?>?state=stats">
          <input type="submit" value="Back to Stats" class="login_submit" />
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
