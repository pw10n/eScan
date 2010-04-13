<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" media="all" href="../css/styles.css" />
<link type="image/x-icon" rel="shortcut icon" href="../images/favicon.ico"/>
<title>eWeek - Passport Login</title>
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
    
    <h1>eWeek Passport Login Page</h1>
    
    <br /><br /><br />
    <div class="login">
		
		<br />
		<?PHP
        if ($args["loginFailure"]) {
          echo("<span class=\"login_fail\">Login failed. Please try again.</span>");
        }
		else {
		  echo("<br />");
		}
        ?>
        
        <form method="post" action="<?PHP echo($_SERVER["SCRIPT_NAME"]); ?>?state=login">
        <fieldset class="login_fieldset">
          <p>
            <label class="login_label"><strong>Barcode:</strong></label>
            <input type="text" name="bid" id="bid" value="<?PHP echo(isset($args["bid"]) ? $args["bid"] : ""); ?>" class="login_input" />
          </p>
          <br />
          <p>
            <label class="login_label"><strong>PIN:</strong></label>
            <input type="text" name="pin" id="pin" value="<?PHP echo(isset($args["pin"]) ? $args["pin"] : ""); ?>" class="login_input" />
          </p>
          <br />
          <p>
            <label>
            <input type="submit" name="button" id="button" value="Go!" class="login_submit" />
            </label>
          </p>
          </fieldset>
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
