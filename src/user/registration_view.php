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
<title>eWeek - Passport Registration</title>
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
    <h1>Passport Registration</h1>

    <br /><br /><br />
    <div class="registration">
		
		<br />
    
		<?PHP
        
        $fields = array(
          "fn" => "First Name",
          "ln" => "Last Name",
          "em" => "Email Address",
          "ma" => "Major",
          "opt" => "Opt-out of Email"
        );
        
        if (count($args["badRegistration"]) > 0) {
          echo "<span style=\"color: red\">Bad Registration!  The following fields were missing or invalid: ";
          
          $seperator = "";
          foreach ($args["badRegistration"] as $field) {
            echo $seperator . $fields[$field];
            $seperator = ", ";
          }
          
          echo "</span>";
        }
        
        ?>
        
        <form method="post" action="<?PHP echo($_SERVER["SCRIPT_NAME"]); ?>?state=registration">
          <input type="hidden" name="bid" value="<?PHP echo($args["bid"]); ?>" />
          <input type="hidden" name="pin" value="<?PHP echo($args["pin"]); ?>" />
          <p>
          	<label class="login_label"><strong>Barcode</strong></label>
            <div class="reg_barcode"><?PHP echo($args["bid"]); ?></div>
          </p>
          <br />
          <p>
          <label class="login_label"><strong>First Name</strong></label>
          <input type="text" name="fn" id="fn" value="<?PHP echo(isset($args["registration"]) ? $args["registration"]["fn"] : ""); ?>" class="login_input" />
          </p>
          <br />
          <p>
            <label class="login_label"><strong>Last Name</strong></label>
            <input type="text" name="ln" id="ln"  value="<?PHP echo(isset($args["registration"]) ? $args["registration"]["ln"] : ""); ?>" class="login_input" />
          </p>
          <br />
          <p>
            <label class="login_label"><strong>Email</strong></label>
            <input type="text" name="em" id="em"  value="<?PHP echo(isset($args["registration"]) ? $args["registration"]["em"] : ""); ?>" class="login_input" />
          </p>
          <br />
          <p>
            <label class="login_label"><strong>Major</strong></label>
            <select name="ma" id="ma">
              <?PHP
              if (isset($args["majors"])) {
                foreach ($args["majors"] as $code => $name) {
                  $selectedString = isset($args["registration"]) &&
                                    strcmp($args["registration"]["ma"], $code) == 0 ? " selected=\"selected\"" : "";
                  echo("<option value=\"$code\" title=\"$name\"" . $selectedString . ">$name</option>\n");
                }
              }
            ?>
            </select>
          </p>
          <br />
          <p>
            <label class="reg_opt">
            <input type="checkbox" name="opt" id="opt" value="on" <?PHP echo(isset($args["registration"]) && $args["registration"]["opt"] == 1 ? "checked=\"checked\"" : ""); ?>/>
            Opt-Out of Email Communication</label>
          </p>
          <br />
          <p>
            <input type="submit" value="Submit" class="login_submit" />
          </p>
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
