<?php 
include_once('inc/constants.php');
if ( isset($_SESSION['ITUser']) && $_SESSION['ITUser']['ID'] != '' && (time() - $_SESSION['ITUser']['LAST_ACTIVITY'] < 1800))
{
  $redirect = 'homepage.php';
  if ( isset($_SERVER['HTTP_REFERER']) )
  {
    $redirect = $_SERVER['HTTP_REFERER'];
  }
  header("Location: $redirect");
  exit;
} else {
	unset($_SESSION['ITUser']);
	session_destroy();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Login</title>
		<link rel="stylesheet" type="text/css" href="css/cms.css" />
		<script type='text/javascript' src='js/jquery.js'></script>
		<script type='text/javascript' src='js/jquery.plugin.js'></script>
		<script type='text/javascript' src='js/common.js'></script>
		<script>
			$(document).ready(function() {
				$('#username').focus();
				$("#loginForm").validate({
					 submitHandler: function() {						 
					   validateLogin();
					 }
				});
			});
                        
			/**** Validate login is used for Login CMS *********/
			function validateLogin(){
					var username = Trimnew($('#username').val());
					var password = Trimnew($('#password').val());
					var catcha_code = Trimnew($('#captcha_code').val());
					if(username == "Username" || username == "") {
						alert("Please enter Username.");
						$('#username').focus();
						return false;
					} else if(password == "Password" || password == ""){
						alert("Please enter Password.");
						$('#password').focus();
						return false;
					} else if(catcha_code == "" || catcha_code == "Security Code"){						
						alert("Please enter Security Code.");
						$('#captcha_code').focus();
						return false;
					} else if(catcha_code.length != 4){
						alert("Please enter only 4 Characters in Security Code.");
						$('#captcha_code').focus();
						return false;
					}
					var passreturn = passwordLength(password); 
					if(passreturn == true){
						$.post(CMSSITEPATH+"/login/auth.php", {'username':username, 'password':password, 'action': 'login', 'captcha_code' : catcha_code}, function(resultdata) { //alert(resultdata);
							if(resultdata == 'captcha_fail'){
								alert("Wrong Security Code!!!");
								$('#captcha_code').focus();
								return false;
							}else if(resultdata == 0) {
								window.location.href = CMSSITEPATH + "/login/unauth.php";
							}else if(resultdata == -1) {
								window.location.href = CMSSITEPATH + "/error.html";
							}else if(resultdata == 1) {
								window.location.href = CMSSITEPATH + "/homepage.php";
							}else if(resultdata == 2) {
								window.location.href = CMSSITEPATH + "/login/change-password.php";
							} else {
								$('#errorloginresult').html(resultdata);
								return false;
							}
						});
					} else {
						$('#password').focus();
						return false;
					}
					return true;
			}
            function passwordLength(password){
                var num=0;
                var alphabet=0;
                var extra=0;
                var temp=password;
                for(var j=0; j<temp.length; j++){
                        var alphaa = temp.charAt(j);
                        var hh = alphaa.charCodeAt(0);
                        if(hh > 47 && hh<58){
                                num=num+1;
                                continue;
                        }
                        if((hh > 64 && hh<91) || (hh > 96 && hh<123)){
                                alphabet=alphabet+1;
                                continue;
                        }
                        extra=extra+1;		
                }
                if(num < 3 || alphabet < 3 || extra  < 2){
                        alert("Your password must have minimium three alphabets ,three numbers and two special characters ");
                        return false;
                }else {
                        return true;
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
        <style type="text/css">
body {   background: none repeat scroll 0 0 #EFF3F6 !important;}

.loginbox
{
    background:url(images/loginboxBg.jpg) repeat-y;
border: 1px solid #d8d8d8;
-moz-border-radius: 5px;
-webkit-border-radius:5px;
border-radius: 5px;
/*IE 7 AND 8 DO NOT SUPPORT BORDER RADIUS*/
-moz-box-shadow: 0px 0px 5px #eee;
-webkit-box-shadow: 0px 0px 5px #eee;
box-shadow: 1px 12px 5px #a2a2a2;
    color:#444;
    font:normal 12px/14px Arial, Helvetica, Sans-serif;
    margin:0 auto;
	overflow:hidden;
	padding:10px 0 0 0;
}
.loginbox.login
{
	height:330px;
    width:300px;
	position:absolute;
	left:50%;
	top:50%;
	margin:-260px 0 0 -166px;
}


.loginbox footer
{


   margin-top: -8px;
    padding: 2px 26px
}
.loginbox label
{
    display:block;
    font:14px/22px Arial, Helvetica, Sans-serif;
    margin:10px 0 0 6px;
	text-align:center;x	
}
.loginbox footer label{
	float:left;
	margin:4px 0 0;
}
.loginbox footer input[type=checkbox]{
	vertical-align:sub;
	*vertical-align:middle;
	margin-right:10px;
}
.loginbox input[type=text],
.loginbox input[type=password],
.txtField,
.cjComboBox
{
    border:1px solid #F7F9FA;
    -webkit-border-radius:5px;
    -moz-border-radius:5x;
    border-radius:5px;
    -moz-box-shadow:2px 3px 3px rgba(0, 0, 0, 0.06) inset, 0 0 1px #95a2a7 inset;
    -webkit-box-shadow:2px 3px 3px rgba(0, 0, 0, 0.06) inset, 0 0 1px #95a2a7 inset;
    box-shadow:2px 3px 3px rgba(0, 0, 0, 0.06) inset, 0 0 1px #95a2a7 inset;
    margin:3px 0 4px 8px;
    padding:8px 6px;
    width:244px;
    display:block;
	height:25px;
	font-size:11px;
	color:#aeaeae; 
}

.loginbox input[type=text]:active,
.loginbox input[type=password]:active,
.txtField:active,
.cjComboBox:active
{
	 color:#000;
}
.pass{ border-radius: 0 0 5px 5px !important; border-top:0px;margin-top:-7px !important;}
.cjComboBox
{
    width:294px;
}
.cjComboBox.small
{
    padding:3px 2px 3px 6px;
    width:100px;
    border-width:3px !important;
}
.txtField.small
{
    padding:3px 6px;
    width:200px;
    border-width:3px !important;
}

.rLink{padding:0 6px 0 0; font-size:11px; clear:both; }
.loginbox a{color:#999;}
.loginbox a:hover, .loginbox a:focus{text-decoration:underline;}
.loginbox a:active{color:#f84747;}
.btnLogin
{
    -moz-border-radius:2px;
    -webkit-border-radius:2px;
    border-radius:5px;
    background:#a1d8f0;
   background: -webkit-gradient(linear, 0 0, 0 100%, from(#4588da), to(#3570d0));
background: -moz-linear-gradient(top, #4588da, #3570d0);
	-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorStr='#badff3', EndColorStr='#7acbed')";
    border:1px solid #7db0cc !important;
    cursor: pointer;
    padding:11px 16px;
    font:bold 14px/14px Verdana, Tahomma, Geneva;
    text-shadow:rgba(0,0,0,0.2) 0 1px 0px; 
    color:#fff;
    -moz-box-shadow:inset rgba(255,255,255,0.6) 0 1px 1px, rgba(0,0,0,0.1) 0 1px 1px;
    -webkit-box-shadow:inset rgba(255,255,255,0.6) 0 1px 1px, rgba(0,0,0,0.1) 0 1px 1px;
    box-shadow:inset rgba(255,255,255,0.6) 0 1px 1px, rgba(0,0,0,0.1) 0 1px 1px;
    margin-left:12px;
    float:right;
	padding:9px 21px;
	width:256px;
}

.btnLogin:hover,
.btnLogin:focus,
.btnLogin:active{
   background: #42aaff;
   
}
.btnLogin:active
{
    text-shadow:rgba(0,0,0,0.3) 0 -1px 0px; 
}

.loginHead{ background:url(images/loginheadBg.jpg) repeat-x; border-bottom:1px solid #cdcdcd; text-align:center; padding:15px 5px 5px 5px; margin:0px; color:#4e4e4e; font-weight:bold; height:23px; margin-top:-10px; font-size:15px;}
.userN{ background:url(images/username.jpg) no-repeat 11px 12px; padding-left:30px !important;}
.passW{background:url(images/password.jpg) no-repeat 11px 15px; padding-left:30px !important;}
</style>
	</head>
	<body>
    
    

 <form class="loginbox login" id="loginForm" name="loginForm">
    <h2 class="loginHead">User Login</h2>     <br />           
    
    <input class="userN" type="text" value="Username" name="username" id="username" onfocus="if(this.value=='Username'){this.value='';}" onblur="if(this.value==''){this.value='Username';}"  />
    
    <input type="password" value="Password" name="password" id="password" onkeypress="capLock(event);return checkkey(event);" onkeypress="return checkkey(event)" onfocus="if(this.value=='Password'){this.value='';}" onblur="if(this.value==''){this.value='Password';}" class="pass passW" /> 

	<input class="pass" type="text" name="captcha_code" id="captcha_code" style="width:268px;vertical-align:top;" onfocus="if(this.value=='Security Code'){this.value='';}" onblur="if(this.value==''){this.value='Security Code';}" />
       
    <img src="<?php echo CMSSITEPATH ?>/securimage/securimage_show.php" alt="CAPTCHA Image" id="captcha_image" style='height:31px;width:90px; margin-left:12px;' />
    <a href="javascript:void(0);" title="Reload Image" onclick="javascript:document.getElementById('captcha_image').src='<?php echo CMSSITEPATH ?>/securimage/securimage_show.php?sid=' + Math.random(); document.getElementById('code').value=''; return false; "><img src="<?php echo CMSSITEPATH ?>/securimage/images/refresh.gif" alt="Reload Image" align="top" style="margin:5px 0 0 -5px;cursor:pointer" /></a>    
    <label><a href="javascript:;" onclick="ITWinPopUp('login/forgot-password.php',650,200);" class="forgot rLink">Forgot password/username?</a></label>
             
    <br />
    <footer>
	  <input type="submit" class="btnLogin" value="Login" alt="Login" >
	</footer>

<div id="divMayus" style="visibility:hidden;font-size:12px;color:#f00;font-weight: normal;text-align:center; ">Caps Lock is on.</div>
<span style="font-size:11px;color:#f00;font-weight: normal; display:block; margin:2px 0 0 0px; text-align:center; color:#999999;">This CMS is Firefox and Chrome compatible</span>					
          </form>
		       
	</body>
</html>
