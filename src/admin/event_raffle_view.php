<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" media="all" href="../css/styles.css" />
<link type="image/x-icon" rel="shortcut icon" href="../images/favicon.ico"/>
<title>eWeek - Event Raffle</title>
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

<h1>Event Raffle</h1>

<?PHP
if (isset($args["noWinner"])) {
?>
No attendees at <?PHP echo($args["events"][$args["eventId"]]); ?>.
<?PHP
} else if (isset($args["winner"])) {
?>
Raffle Winner for <?PHP echo($args["events"][$args["eventId"]]); ?>: <strong><?PHP echo($args["winner"]["fn"] . " " . $args["winner"]["ln"]); ?></strong> (Barcode <strong><?PHP echo($args["winner"]["bid"]); ?></strong>)

<form method="post" action="">
  <input type="hidden" name="eventId" value="<?PHP echo($args["eventId"]); ?>">
  <input type="submit" value="Choose Another Winner">
</form>
<?PHP
} else {
?>
<form method="post" action="">
  <label for="eventChoice">Choose Event: </label>
  <select id="eventChoice" name="eventId">
  <?PHP
  foreach ($args["events"] as $eventId => $eventName) {
  ?>
  <option value="<?PHP echo($eventId); ?>"><?PHP echo($eventName); ?></option>
  <?PHP
  }
  ?>
  </select>
  <input type="submit" value="Choose Winner">
</form>
<?PHP
}
?>
<a href="controller.php">Back to Admin Screen</a>
</body>
</html>
