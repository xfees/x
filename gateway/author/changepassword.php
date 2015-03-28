<?php
	include_once('../config.php');
	include_once('../inc/mail_functions.php');
	if(isset($_REQUEST['id']) && $_REQUEST['id']!='')
	$id=$_REQUEST['id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Change Password</title>
<style type="text/css">@import url(<?php echo CSSFILEPATH?>/cms.css);</style>
<script type='text/javascript' src='<?php echo JSFILEPATH;?>/jquery.js'></script>
<script type='text/javascript' src='<?php echo JSFILEPATH?>/common.js'></script>
<script type="text/javascript">
function getContent(){             //----------Function to send data to 2db file after validation
	$('.error').hide();
	var flag=0;
	var oldpass=Trimnew($('#oldpass').val());
	var	newpass=Trimnew($('#newpass').val());
	var id=$('#id').val();
	if(oldpass=="")
	{
		$('label#oldpass_error').show();
		$('#oldpass').focus();
		flag=1;
	}
	else if(newpass=="")
	{
		$('label#newpass_error').show();
		$('#newpass').focus();
		flag=1
	}
	else if(newpass!= "")
	{
		passvalid=passwordLength(newpass);
		if(passvalid!=true)
		{
			$('label#newpass_error').show();
			$('label#newpass_error').html('Please enter valid password');
			$('#newpass').focus();
			flag=1;		
		}	
	}
	if(flag==0 && id !='')
	{
		$('#changepassform').submit();
	}	
}
</script>
</head>
<body>
<?php
if(isset($_REQUEST['oldpass']) && $_REQUEST['oldpass']!='') {
	$id=trim($_REQUEST['id']);
	$oldpass = $_REQUEST['oldpass'];
	$oldpass = sha1($oldpass);
	$newpass = $_REQUEST['newpass'];
	$newpass = sha1($newpass);
	$db = Database::Instance();
	//echo "select id,username,email from author where id=".$id." and password='$oldpass'";
	$db->query("select id,username,email from author where id=".$id." and password='$oldpass'");
	if($db->getRowCount()>0) {
	    $row = $db->fetch();
		$username=$row['username'];
		$email=$row['email'];
		$update_sql = "update author set password='$newpass' where id=".$id."";	//echo "<p>sql=".$sql;
		$db->query($update_sql);
		
		echo '<div align="center" style="font-size:15px;font-weight:bold;color:#FF0000;margin-top:100px;"> <label style="text-align;margin-top:100px;" style="font-size:10px;font-weight:bold;color:#FF0000;">Password changed successfully!</label></div>';
		
		$subject = 'Indiatimes CMS: Password Changed';
		$body = 'Your Account password has been updated in Indiatimes CMS by its Webmaster.<br>Your login details are:-<br>URL:-<a href="'.CMSSITEPATH.'/">'.CMSSITEPATH.'</a> <br>User Name:-'.$username.'<br>Password:-'.$_REQUEST['newpass'];
		$fromEmail = 'Administrator<noreply@indiatimes.co.in>';
		sendHTMLMail($email, $subject, $body, $fromEmail); 
        exit; 		
		}
		else {
		 // echo "<label style=\"margin-top:20px;\">Old password entered is incorrect!</label>";
		  echo '<div align="center" style="font-size:15px;font-weight:bold;color:#FF0000;margin-top:10px;"> <label style="text-align;margin-top:100px;" style="font-size:10px;font-weight:bold;color:#FF0000;">Old password entered is incorrect!</label></div>';
		}
	}
?>
<div class="content">
	<div id="response">
	<form id="changepassform" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
		<input type="hidden" value="<?php echo $id;?>" name="id" id="id" />
		<table>
		<tr>
			<td class="col1">Old Password <span class="required">*</span></td>
			<td class="col2"><input type="password" name="oldpass" id="oldpass" class="inputForm" /></td>
			<td class="col3"><label class="error" id="oldpass_error">&nbsp;&nbsp;Please Enter Your Old Password</label></td>
			<td class="col3"><label class="error" id="wrongpass_error">&nbsp;&nbsp;Old password entered is incorrect</label></td>
		</tr>
		<tr>
			<td class="col1">New Password <span class="required">*</span></td>
			<td class="col2"><input type="password" name="newpass" id="newpass" class="inputForm" /></td>
			<td class="col3"><label class="error" id="newpass_error">&nbsp;&nbsp;Please Enter Your New Password</label></td>
		</tr>
		<tr>
			<td class="col1">&nbsp;</td>
			<td class="col2"><input type="button" id="savebutton" value="Save" onclick="getContent()" class="inputControl3" /></td>
			<td class="col3">&nbsp;</td>
		</tr>
		</table>
	 </form>	
	</div>
</div>	
</body>
</html>