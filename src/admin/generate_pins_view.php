<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
