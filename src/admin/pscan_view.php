<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" media="all" href="../css/styles.css" />
<link type="image/x-icon" rel="shortcut icon" href="../images/favicon.ico"/>
<title>eWeek - Event Scan-In</title>
</head>
<h1><?php echo $args["event"]["name"]; ?></h1>
<h3>Points: <?php echo $args["event"]["points"][$args["act"]]; ?></h3>
<p>&nbsp;<p>
<ul id="pscanned">
<?php
foreach($args['users'] as $user){
?>
   <li>
   <?php
   echo $user['fn'] . ' ' . $user['ln'] . ' &lt;'. $user['em'] . '&gt;'; 
   ?>
   </li>
<?php
}
?>
</ul>
<p>&nbsp;<p>
<form name="pscanfrm">
Scan:<input type="hidden" name="eid" id="eid" value="<?php echo $args["eid"]; ?>" />
<input type="hidden" name="act" id="act" value="<?php echo $args["act"]; ?>" />
<input type="hidden" name="state" id="state" value="pscan" />
<input type="text" name="bid" id="bid" />
</form>
<p>&nbsp;<p>
<a href="controller.php">Back to Admin</a>

</html>
