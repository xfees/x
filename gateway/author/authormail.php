<?php 
/***** INCLUDE CONNECTION FILE ************************************************************************************/
include_once('../config.php');
include_once('../inc/mail_functions.php');
$_SESSION['TOPMENU']="author";
$email = $_GET['email'];
$result_data = array();
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
		<link href="<?php echo CSSFILEPATH;?>/cms.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo CSSFILEPATH;?>/popup.css" rel="stylesheet" type="text/css" />
		<script>
			function sendMail(){
				var subj = $('#mailSubject').val();
				var bdy = $('#mailBody').val();
				var flag = 0;
				if(subj == ''){
					$('label#mailSubject_error').show();
					$('#mailSubject').focus();
					flag=1;
				}
				
				if(bdy == ''){
					$('label#mailBody_error').show();
					$('#mailBody').focus();
					flag=1;
				}
				
				if(flag == 0){
					$('#savebutton').attr('disabled','disabled');	
					return true;
				} else {
					return false;
				}
			}
		</script>
	</head>
	<body>
		<?php
if(isset($_POST) && !empty($_POST)){
	$action = $_POST['action'];
	$toMail = '';
	if($action == "all"){
		$db = Database::Instance() ; 
		$query="SELECT email FROM `author` where status = 1 ORDER BY email ASC";
		$db->query($query);
		if($db->getRowCount()>0) {
			$result_data=$db->getResultSet();
			if(!empty($result_data)){
				foreach($result_data as $val){
					$toMail .= $val['email'].'; ';
				}
			} 
		}
		$toMail = substr($toMail, 0, -2); 
	} else {
		$toMail = $_POST['mailTo'];
	}
	$subject = $_POST['mailSubject'];
	$body = $_POST['mailBody'];
	$fromEmail = 'Administrator<noreply@indiatimes.in>';
	if($toMail != ''){
		sendHTMLMail($toMail, $subject, $body, $fromEmail);
	}
?>
	<div style="padding:50px;color:#ff0000;font-weight:bold"> Your mail has been sent</div>
	<script>
	setTimeout(function() {window.parent.$.modal.close();}, 2000);
	</script>
<?php } else { ?>

		<div style="padding:10px;">
			<form name="frmMail" id="frmMail" action="" onSubmit="return sendMail()" method="post">
				<table cellpadding="0" cellspacing="10" border="0">
					<tr>
						<td><strong>To:</strong> <span class="required">*</span></td>
						<td class="frmTD1"><?php echo $email; ?><input type="hidden" name="mailTo" id="mailTo" value="<?php echo $email; ?>" class="inputForm"/></td>
					</tr>
					<tr>
						<td><strong>Subject:</strong> <span class="required">*</span></td>
						<td class="frmTD1"><input type="text" name="mailSubject" id="mailSubject" value="" size="50" class="inputForm"/><br/><label class="error" id="mailSubject_error" style="display:none">&nbsp;&nbsp;Please Enter Subject</label></td>
					</tr>
					<tr>
						<td valign="top"><strong>Body:</strong> <span class="required">*</span></td>
						<td class="frmTD1" style="width:80%"><textarea name="mailBody" id="mailBody" rows="10" cols="10"></textarea><br/><label class="error" id="mailBody_error" style="display:none">&nbsp;&nbsp;Please Enter Body Text</label></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" value="Send Mail" name="btnMail" id="savebutton"/><input type="hidden" name="action" id="action" value="<?php echo $email; ?>" class="inputForm"/></td>
					</tr>
				</table>
			</form>
		</div>
<?php } ?>
	</body>
</html>