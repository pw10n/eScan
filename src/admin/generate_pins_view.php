<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" media="all" href="../css/styles.css" />
<link type="image/x-icon" rel="shortcut icon" href="../images/favicon.ico"/>
<title>eWeek - Pin Generation/Lookup</title>
</head>

<body <?PHP if(count($args["pins"]) < 20) { echo("onload=\"document.getElementById('bidLower').focus();\""); } ?>>

<div id="wrapper">
        <div id="header">
        <div class="left">
                <img src="../images/eweek_logo.png" alt="eWeek 2010" border="0" />
        </div>
        <div class="left">
                <img src="../images/bg.png" alt="" border="0" />
        </div>
    </div>

<h1>Pin Generation/Lookup</h1>


<?PHP
if (count($args["pins"]) > 0) {
?>
<table border="1" cellpadding="1" cellspacing="2">
  <tr>
    <td><strong>Barcode</strong></td>
	<td><strong>Pin</strong></td>
  </tr>
  <?PHP
  foreach($args["pins"] as $bid => $pin) {
  ?>
  <tr>
    <td><?PHP echo($bid); ?></td>
	<td><?PHP echo($pin); ?></td>
  </tr>
  <?PHP
  }
  ?>
</table>
<hr>
<?PHP
}
?>

<form method="post" action="?state=generate_pins">
Generate/Lookup pins for barcodes 
<input type="text" name="bidLower" id="bidLower"> thru 
<input type="text" name="bidUpper">
<input type="submit" value="Generate Pins">
</form>

<a href="controller.php">Back to Admin Screen</a>

</div>

</body>
</html>
