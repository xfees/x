<?php
include_once ("inc/constants.php");
include_once ("inc/functions.php");
include_once ("inc/mail_functions.php");

$objModel = new Login();


$auther_id = !empty($_GET['auth']) ? decryptdata($_GET['auth']):0;

if($auther_id==0 || $auther_id=='')
{	
	//header("Location:unauth.php");
}
else //checking is active user or not
{
	$res = $objModel->getUserById($auther_id);      
            
      if(is_array($res)) {        
        if(count($res) > 0) {
          if($res[0]['status'] == '0') {
		  //	header("Location:unauth.php");
		  }
		}
	  }
}//end of else
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reset Password</title>
<link rel="stylesheet" type="text/css" href="css/cms.css" />
<script type='text/javascript' src='js/jquery.js'></script>
<script type='text/javascript' src='js/common.js'></script>
</head>
<style type="text/css">
.loginpanel {
	width: 600px;
	margin: 0 auto;
}
</style>
<script>
function ChangePassword()
{
	var new_password = $('#new_password').val();
	var conf_password = $('#conf_password').val();
	var id = Trimnew($('#auther').val());
	if(new_password == "") 
	{
		alert("Please Enter New password.");
		$('#new_password').focus();
		return false;
	} 
	else if(conf_password =="")
	{
		alert("Please Enter Confirm Password.");
		$('#conf_password').focus();
		return false;
	}	

	else if(conf_password!=new_password)
	{
		alert("Password mis-matched, Please try again.");
		$('#conf_password').val('');
		$('#new_password').val('');
		$('#new_password').focus();
		return false;
	}
	var passreturn = passwordLength(new_password);
	$('#resetresult').html('');

	if(passreturn == true)
	{
		$.post("<?php echo CMSSITEPATH.'/';?>authpasswordReset.php", {'id':id, 'new_passw':new_password}, function(resultdata) 
		{  
			$('#resetresult').html(resultdata);
		});
	}	
}
</script>
<script type="text/javascript">
$(document).ready(function() {
//alert('ss');
$('#new_password').focus();
$('#new_password').val('');
});

</script>

<body>
	<div class="logintop"></div>
	<div id="logo"></div>
	<div class="loginpanel">
		  
		<div  class="loginpanel">
		    Set Your New Password
			
	           <form>
                <div>
                	<input type="hidden" value="<?php echo $_GET['auth'];?>" name="auther" id="auther">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0"
                        class="box">
                        
                        <tr>
                            <td width="10" class="boxtopLeft"></td>
                       		<td class="boxtopMiddle"></td>
	                        <td class="boxtopRight"></td>
                    	</tr>
                    <tr>
                        <td width="10" class="boxMiddleLeft"></td>
                        <td class="boxMiddleMiddle padding12">
                        <div id="resetresult" style="padding-bottom:5px;"></div>
                            <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                <tr>
                                    <td colspan="2" class="normalText15">
										<label for="new_password">New Password</label>
                                     </td>
                                 </tr>
                                 <tr>
                                        <td>
                                            <input class="inputlogin" type="password" name="new_password" id="new_password" />   
                                        </td>
                                  </tr>
                                  <tr>
                                        <td colspan="2" class="normalText15">
                                            <label for="conf_password">Confirm Password</label>
                                        </td>
                                  </tr>
                                  <tr>
                                  		<td>
                                        	<input class="inputlogin" type="password" name="conf_password" id="conf_password" />   		
                                        </td>
                                   </tr>
                                   <tr><td colspan="2" class="normalText15"></td></tr>	
									<tr>
                                    	<td>
                                        	<div class="submitbar"> <img src="images/btn-submit.gif" alt="Submit" border="0" align="absmiddle" onclick="ChangePassword();" style="cursor:pointer" /> 
		</div>
                                        </td>
                                    </tr>
							</table>
                            </td>
                            </tr>
                            </table>
                            </div>
		
    </form>
    </div>
    <div class="loginpanel_footer"><img src="images/login-btmRight-corner.gif" align="right" /><img src="images/login-btmLeft-corner.gif" /></div>
</div>
<div class="footer">&copy; Indiatimes.com</div>
</body>
</html>