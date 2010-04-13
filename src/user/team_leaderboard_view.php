<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" media="all" href="../css/styles.css" />
<link type="image/x-icon" rel="shortcut icon" href="../images/favicon.ico"/>
<title>eWeek - Team Leaderboard</title>
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

    <h1>eWeek Team Leaderboard</h1>
    
    <br /><br /><br />
    <div class="leaderboard">
		
		<br />
		
        <div align="center">
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
        </div>
        
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
