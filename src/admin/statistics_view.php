<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" media="all" href="../css/styles.css" />
<link type="image/x-icon" rel="shortcut icon" href="../images/favicon.ico"/>
<title>eWeek - Statistics</title>

<style type="text/css">
.event {
padding-top: 10px
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


<h1>Statistics</h1>

<h2>General</h2>

<?PHP echo($args["numRegisteredPassports"]); ?> passports have been registered of the <?PHP echo($args["numActionedPassports"]); ?> passports that have had some action taken on them (registration, scan-in, placed on a team).  This roughly means that <?PHP echo(sprintf("%.0f", $args["numRegisteredPassports"] * 100 / $args["numActionedPassports"])); ?>% of the passports that we have handed out have been registered.<br>

<?PHP
$labels = array();
$data = array();
$totalMajorsInChart = 0;

foreach ($args["majors"] as $numEventsRow) {
  $totalMajorsInChart += $numEventsRow["att"];
}

foreach ($args["majors"] as $numEventsRow) {
  $labels[] = $numEventsRow["ma"] . " (" . sprintf("%.1f", 100 * $numEventsRow["att"] / $totalMajorsInChart) . "%)";
  $data[] = sprintf("%.2f", $numEventsRow["att"] / $totalMajorsInChart);
}
?>

<img src="http://chart.apis.google.com/chart?chs=500x325&cht=p&chd=t:<?PHP echo(implode(",", $data)); ?>&chl=<?PHP echo(implode("|", $labels)); ?>&chco=FFCC00|0000FF">

<hr>

<h2>Attendance</h2>
<p>
Total scan-ins: <?PHP echo(isset($args["totalScans"]) ? $args["totalScans"] : 0); ?> <br>
Average Event Attendance: <?PHP echo(sprintf("%.0f", isset($args["averageAttendance"]) ? $args["averageAttendance"] : 0)); ?>

<h3>Number of Events Attended</h3>
<?PHP
$labels = array();
$data = array();

foreach ($args["numEvents"] as $numEventsRow) {
  $labels[] = $numEventsRow["evts"];
  $data[] = $numEventsRow["att"];
}
?>

<img src="http://chart.apis.google.com/chart?chs=450x<?PHP echo(27 * (count($data) + 1)); ?>&cht=bhs&chd=t:<?PHP echo(implode(",", $data)); ?>&chxr=0,0,<?PHP echo($max); ?>&chds=0,<?PHP echo($max); ?>&chxt=x,y&chxl=1:|<?PHP echo(implode("|", array_reverse($labels))); ?>|&chm=N*f0*,000000,0,-1,11">

<h3>First Event Attended</h3>
<?PHP
$labels = array();
$data = array();
$max = 0;

foreach ($args["firstEvent"] as $firstEventRow) {
  $labels[] = $args["events"][$firstEventRow["feid"]];
  $data[] = $firstEventRow["att"];

  if ($firstEventRow["att"] > $max) {
    $max = $firstEventRow["att"];
  }
}
?>

<img src="http://chart.apis.google.com/chart?chs=600x<?PHP echo(27 * (count($data) + 1)); ?>&cht=bhs&chd=t:<?PHP echo(implode(",", $data)); ?>&chxr=0,0,<?PHP echo($max); ?>&chds=0,<?PHP echo($max); ?>&chxt=x,y&chxl=1:|<?PHP echo(implode("|", array_reverse($labels))); ?>|&chm=N*f0*,000000,0,-1,11">

<h3>Event Totals</h3>
<?PHP
$labels = array();
$data = array();
$max = 0;

foreach ($args["eventStats"] as $event) {
  $labels[] = $event["name"];
  $data[] = $event["attendance"];

  if ($event["attendance"] > $max) {
    $max = $event["attendance"];
  }
}
?>

<img src="http://chart.apis.google.com/chart?chs=600x<?PHP echo(27 * (count($data) + 1)); ?>&cht=bhs&chd=t:<?PHP echo(implode(",", $data)); ?>&chxr=0,0,<?PHP echo($max); ?>&chds=0,<?PHP echo($max); ?>&chxt=x,y&chxl=1:|<?PHP echo(implode("|", array_reverse($labels))); ?>|&chm=N*f0*,000000,0,-1,11">

</p>

<hr>

<h2>Prize Eligibility</h2>
<p>

<?PHP
foreach ($args["prizeEligibility"] as $prize => $numEligible) {
?>
Eligible for <?PHP echo($prize); ?>: <?PHP echo(isset($numEligible) ? $numEligible : 0); ?> (<?PHP echo(sprintf("%.0f", (isset($numEligible) ? $numEligible : 0) * 100 / $args["numRegisteredPassports"])); ?>%)<br>
<?PHP
}
?>

</p>

<hr>

<h2>Team Statistics</h2>
Number of teams: <?PHP echo(count($args["teams"])); ?>

<h3>Leaderboard</h3>
            <table cellpadding="4" cellspacing="1" border="1" class="stats_table">
              <tr bgcolor="#fee3ad">
                <td width="350px"><strong>Team Name</strong></td>
                <td width="100px"><strong>Score</strong></td>
              </tr>

            <?PHP
            foreach ($args["teams"] as $team) {
            ?>
              <tr>
                <td><?PHP echo($team["name"]); ?></td>
                <td><?PHP echo(isset($team["pts"]) ? $team["pts"] : 0); ?></td>
              </tr>
            <?PHP
            }
            ?>

            </table>

<hr>

<h2>By Event</h2>

<?PHP
foreach ($args["eventStats"] as $eventId => $event) {
?>
<h3 class="event"><?PHP echo($event["name"]); ?></h3>
Attendance: <?PHP echo($event["attendance"]); ?><br>

<?PHP
  if ($event["attendance"] > 0) {
    $data = array();
    $labels = array();
    $max = 0;

    foreach($event["sliced"] as $slice) {
      $data[] = $slice["att"];

      $minute = $slice["timegroup"] % 60;
      $hour = (($slice["timegroup"] - $minute) / 60) % 12;

      if ($hour == 0) {
        $hour = 12;
      }

      $labels[] = sprintf("%d:%02d", $hour, $minute);
      if ($slice["att"] > $max) {
        $max = $slice["att"];
      }
    }
?>

<div style="float:left">
<img src="http://chart.apis.google.com/chart?chs=450x<?PHP echo(27 * (count($data) + 1)); ?>&cht=bhs&chd=t:<?PHP echo(implode(",", $data)); ?>&chxr=0,0,<?PHP echo($max); ?>&chds=0,<?PHP echo($max); ?>&chxt=x,y&chxl=1:|<?PHP echo(implode("|", array_reverse($labels))); ?>|&chm=N*f0*,000000,0,-1,11">
</div>

<?PHP
$labels = array();
$data = array();
$totalMajorsInChart = 0;

foreach ($event["majors"] as $numEventsRow) {
  $totalMajorsInChart += $numEventsRow["att"];
}

foreach ($event["majors"] as $numEventsRow) {
  $labels[] = $numEventsRow["ma"] . " (" . sprintf("%.1f", 100 * $numEventsRow["att"] / $totalMajorsInChart) . "%)";
  $data[] = sprintf("%.2f", $numEventsRow["att"] / $totalMajorsInChart);
}
?>

<div style="position:relative; left: 15px">
<img src="http://chart.apis.google.com/chart?chs=500x325&cht=p&chd=t:<?PHP echo(implode(",", $data)); ?>&chl=<?PHP echo(implode("|", $labels)); ?>&chco=FFCC00|0000FF">
</div>

<br clear="all" />

<?PHP
  }
}
?>

<hr>

<a href="controller.php">Back to Admin Screen</a>

</div>

</body>
</html>
