<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Forgot Password</title>
<link rel="stylesheet" type="text/css" href="../css/cms.css" />
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.plugin.js'></script>
<script type='text/javascript' src='../js/common.js'></script>
<style type="text/css">
.loginpanel {
	width: 600px;
	margin: 0 auto;
}
</style>
<script>
/*** To retrive forgot password ***/
function validateAccDetails()
{
	var username = Trimnew($('#username').val());
	$('#resetresult').html('');
	if(username == "") 
	{
		alert("Please Enter Username or Email address.");
		$('#username').focus();
		return false;
	}
	else
	{
		$.post(CMSSITEPATH+"/login/auth.php", {'username':username, 'action': 'forget_password'}, function(resultdata) 
		{  //alert(resultdata);																								
			$('#resetresult').html(resultdata);			
		});
	}
}
</script>
</head>
<body>
	<form>
		<div class="loginpanel">
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
						<table width="100%" border="0" cellspacing="0" cellpadding="3">
							<tr>
								<td colspan="2" class="normalText15">Please enter username/email
									address for which you want to reset password.
									<div style="color: #cc0000; font-size: 12px;" id="resetresult"></div>
								</td>
							</tr>
							<tr>
								<td colspan="2"><label for="username">Username/ Email</label>
								</td>
							</tr>
							<tr>
								<td><input class="inputlogin" type="text" name="username" id="username" /></td>
								<td align="center"><img src="../images/btn-submit.gif" alt="submit" border="0" align="absmiddle" onclick="validateAccDetails()" style="cursor: pointer" /></td>
							</tr>
						</table>
					</td>
					<td width="10" class="boxMiddleRight"></td>
				</tr>
				<tr>
					<td class="boxbottomLeft"></td>
					<td class="boxbottomMiddle"></td>
					<td class="boxbottomRight"></td>
				</tr>
			</table>
		</div>
	</form>
</body>
</html>
