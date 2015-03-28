<?php
include_once("../inc/constants.php");
include_once("../inc/functions.php");
$objModel = new Login();

if (!isset($_SESSION['NewUser']))
{
  header("location: ../index.php");
  exit;
} else {
	$auther_id = $_SESSION['NewUser']['ID'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reset Password</title>
<link rel="stylesheet" type="text/css" href="../css/cms.css" />
<style type="text/css">
.login_form{ background-color: #FFFFFF; border:1px solid #E5E5E5; box-shadow: 0 4px 10px -1px rgba(200, 200, 200, 0.7);
    font-weight: normal; margin-left: 8px;  padding-bottom: 46px;  padding-left: 24px;  padding-right: 24px;    padding-top: 26px;
	border-radius:8px; width:500px; margin:20% auto;
}
.login_form p{ margin:0px;}
.login_form label {  color: #000;  font-size: 16px;}
.login_form span {  margin-bottom: 10px; display:block; font-size: 11px; font-weight:bold; color:#990033; }
.login_form .inputlogin { background-color: #FBFBFB;border:1px solid #E5E5E5; box-shadow: 1px 1px 2px rgba(200, 200, 200, 0.2) inset;
    font-size: 24px; font-weight: 200;  line-height: 1;  margin:2px 6px 6px 0px;  outline-color: -moz-use-text-color;
    padding:3px;  width: 100%;}
</style>
<script type='text/javascript' src='../js/jquery-1.2.6.minwithblock.js'></script>
<script type='text/javascript' src='../js/common.js'></script>
<script>
function validateLogin()
{
	var new_password = $('#new_password').val();
	var conf_password = $('#conf_password').val();
	var id = Trimnew($('#auther').val());
	if(new_password == "") {
		$('#errPwd').html("Please Enter New password.");
		$('#new_password').focus();
		return false;
	} else{
		$('#errPwd').html("&nbsp;");
	}
	
	if(conf_password ==""){
		$('#errCPwd').html("Please Enter Confirm Password.");
		$('#conf_password').focus();
		return false;
	} else{
		$('#errCPwd').html("&nbsp;");
	}

	if(conf_password!=new_password)
	{
		$('#errorloginresult').html("Password mis-matched, Please try again.");
		$('#conf_password').val('');
		$('#new_password').val('');
		$('#new_password').focus();
		return false;
	} else {
		$('#errorloginresult').html("");
	}
	var passreturn = passwordLength(new_password);
	$('#errorloginresult').html('');

	if(passreturn == true)
	{
		$('#loader').show();
		$.post(CMSSITEPATH+"/login/auth.php", {'id':id, 'password':new_password, 'is_password_changed': '1', 'action': 'resetpwd'}, function(resultdata) { //alert(resultdata);
			if(resultdata == 0) {
				location.href = CMSSITEPATH+"/login/unauth.php";
			}else if(resultdata == -1) {
				location.href = CMSSITEPATH+"/error.html";
			}else if(resultdata == 1) {
				location.href = CMSSITEPATH+"/homepage.php";
			}else if(resultdata == 2) {
				$('#errorloginresult').html('Your new password cannot be same as old password');
				$('#loader').hide();
				$('#conf_password').val('');
				$('#new_password').val('');
				$('#new_password').focus();
			} else {
				$('#errorloginresult').html(resultdata);
			}
		});
	} else {
		$('#errPwd').html('Your password must have minimium three alphabets ,three numbers and two special characters ');
		$('#conf_password').val('');
		$('#new_password').val('');
		$('#new_password').focus();
		return false;
	}
}
function capLock(e){
	 kc = e.keyCode?e.keyCode:e.which;
	 sk = e.shiftKey?e.shiftKey:((kc == 16)?true:false);
	 if(((kc >= 65 && kc <= 90) && !sk)||((kc >= 97 && kc <= 122) && sk))
	  document.getElementById('divMayus').style.visibility = 'visible';
	 else
	  document.getElementById('divMayus').style.visibility = 'hidden';
}
</script>
</head>
<body>
<div class="logintop"></div>
<div id="logo"></div>
<div class="loginpanel">
  <div class="login_form"> <strong>Set Your New Password</strong> <br/>
    <span style="font-size:13px; color:#990033; font-weight:bold">Your password must have minimium 3 alphabets , 3 numbers and 2 special characters.</span>
    <div id="errorloginresult" style="font-size:11px; color:#990033; font-weight:bold"></div>
    <form>
      <input type="hidden" value="<?php echo $_SESSION['NewUser']['ID']; ?>" name="auther" id="auther">
      <div class="spacer" style="height:10px;"></div>
      <p>
        <label for="new_password">New Password</label>
        <br/>
        <input class="inputlogin" type="password" name="new_password" id="new_password" onkeypress="capLock(event);return checkkey(event);" tabindex="1" />
        <br/><div id="divMayus" style="visibility:hidden;font-size:12px;color:#990033;font-weight: normal;">Caps Lock is on.</div>
        <span id="errPwd">&nbsp;</span>
      </p>
      <p>
        <label for="conf_password">Confirm Password</label>
        <br/>
        <input class="inputlogin" type="password" name="conf_password" id="conf_password" onkeypress="return checkkey(event)" tabindex="2"/>
		<br/>
        <span id="errCPwd">&nbsp;</span>
      </p>
      <div class="submitbar" style="padding-top:0px;" > <img src="../images/btn-submit.gif" alt="Submit" border="0" align="left" onclick="validateLogin();" style="cursor:pointer" /> <span id="loader" style="display:none; position:absolute;margin:-134px 0 0 -293px; "><img src="http://timesdeal.com/images/ajax-loader1.gif" ></span> </div>
    </form>
  </div>
</div>
<div class="footer">&copy; Indiatimes.com</div>
<script>
$('#new_password').focus();
</script>
</body>
</html>
