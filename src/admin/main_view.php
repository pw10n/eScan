<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" media="all" href="../css/styles.css" />
<link type="image/x-icon" rel="shortcut icon" href="../images/favicon.ico"/>

<style type="text/css">
#events dt {
  font-weight: bold;
  padding-top: 10px
}
</style>

<title>eWeek - Passport Administration</title>
</head>

<div id="wrapper">
        <div id="header">
        <div class="left">
                <img src="../images/eweek_logo.png" alt="eWeek 2010" border="0" />
        </div>
        <div class="left">
                <img src="../images/bg.png" alt="" border="0" />
        </div>
    </div>

<h1>Passport Administration</h1>

<p>You are logged in as <strong><?PHP echo($args["user"]); ?></strong>. (<a href="http://logout:logout@acm.calpoly.edu/eweek_passport/admin">Logout</a>)<br>
<i>NOTE:</i> All administrative actions are logged.  Please help us maintain fairness by being only using this site for accurate reporting.  Do not accept boarcode numbers without a passport present.  Do not scan more than one passport per person.</p>

<h2>Events</h2>
   <dl id="events">
   <?php 
   foreach ($args["events"] as $eid => &$event){
   ?>
         <dt><?php echo $event["name"]; ?></dt>
         <dd>[<a href="<?php echo "controller.php?state=scan&eid=" . $eid; ?>">scan</a>]
         <?php
            foreach ($event["points"] as $pname => &$point){
         ?>
            <small>
            [<a href="<?php echo "controller.php?state=pscan&eid=" . $eid . "&act=" . urlencode($pname); ?>"><?php echo $pname;?></a>]
            </small>
         <?php
            }
         ?>
         </dd>
   <?php
   }
   ?>
   </dl>
<!-- end Events -->

<h2>Tasks</h2>
   <ul>
      <li><a href="controller.php?state=event_raffle">Event Raffle</a></li>
      <li><a href="controller.php?state=finale_raffle">Finale Raffle</a></li>
      <li><a href="controller.php?state=statistics">Statistics</a></li>
      <li><a href="controller.php?state=generate_pins">Generate/Lookup Pins</a>
      <li><a href="controller.php?state=swap">Swap Passports</a>
   </ul>
<!-- end Tasks -->
</div>
</body>
</html>
