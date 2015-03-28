<?php
include_once('../inc/constants.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
<link rel="stylesheet" type="text/css" href="<?php echo CSSFILEPATH?>/cms.css" />
<script type='text/javascript' src='<?php echo JSFILEPATH?>/jquery-1.2.6.minwithblock.js'></script>
<script type='text/javascript' src='<?php echo JSFILEPATH?>/common.js'></script>
<META HTTP-EQUIV="Refresh" CONTENT="5; URL=<?php echo CMSSITEPATH;?>/index.php" /> 
</head>
<body>
	<div class="logintop">
		<a href="<?php echo SITEPATH;?>" class="link14Georgia noUnderline">Visit Website</a>
	</div>
	<div id="logo"></div>
	<div class="loginpanel">
		 <img src="<?php echo IMAGEPATH?>/access-denied.png" />
		<div style="padding-top:5px;">
			<span>You will be redirected to the login page in 5 sec. If not please &nbsp;
				<a href='<?php echo CMSSITEPATH?>/index.php' class="forgot">click here</a>&nbsp;to try again.
			</span>
		</div>

	</div>
	<div class="loginpanel_footer">
		<img src="<?php echo IMAGEPATH?>/login-btmRight-corner.gif" align="right" /><img
			src="<?php echo IMAGEPATH?>/login-btmLeft-corner.gif" />
	</div>
	</div>
	<div class="footer">&copy; Indiatimes.com</div>
</body>
</html>

