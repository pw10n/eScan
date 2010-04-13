<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" media="all" href="../css/styles.css" />
<link type="image/x-icon" rel="shortcut icon" href="../images/favicon.ico"/>
<title>eWeek - Swap Passports</title>

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


<h1>Swap Passports</h1>

<p><i>Reminder: </i> We cannot do anything about lost passports that have not been registered.</p>

<?PHP
if (!$args["justSwapped"]) {
?>
<form action="controller.php?state=swap" method="POST">
  <label for="ln"><strong>Lastname:</strong> </label>
  <input type="text" name="ln" id="ln" />
  <input type="submit" value="Lookup Passport" />
</form>
<?PHP
}
?>

<?PHP
if (isset($args["matches"])) {
?>
<hr>
<?PHP
  if (count($args["matches"]) == 0) {
?>
    <strong>No matches found.</strong>
<?PHP
  } else {
?>
<h2>Registered Passport with Lastname "<?PHP echo($args["ln"]); ?>"</h2>

<?PHP
if ($args["swapError"]) {
?>
<span style="color:red">Could not complete swap.  Please check your inputted values and try again.</span>
<?PHP
}
?>

<?PHP
    foreach ($args["matches"] as $match) {
      if ($match["s"] == PASSPORT_STATE_REGISTERED) {
?>
        <form action="controller.php?state=swap" method="POST">
          <input type="hidden" name="ln" value="<?PHP echo($args["ln"]); ?>" />
          <input type="hidden" name="old_bid" value="<?PHP echo($match["bid"]); ?>" />
          <input type="text" size="6" name="new_bid" />
          <input type="submit" value="Swap" />
          <strong><?PHP echo($match["fn"]); ?> <?PHP echo($match["ln"]); ?></strong> - <?PHP echo($match["em"]); ?> - Current Barcode: <?PHP echo($match["bid"]); ?>
        </form>
<?PHP
      }
    }
  }
}
?>

<?PHP
if ($args["justSwapped"]) {
?>
  Just swapped user's passport from barcode <?PHP echo($args["old_bid"]); ?> to barcode <?PHP echo($args["new_bid"]); ?>.  Please remind the passport holder that the old passport is now permently invalid and can be thrown away.
<?PHP
}
?>

<hr>

<a href="controller.php">Back to Admin Screen</a>

</div>

</body>
</html>
