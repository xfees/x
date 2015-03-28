<?php
	include_once ("inc/constants.php");
	include_once ("inc/functions.php");
	include_once ("inc/mail_functions.php");
	//include_once ("classes/class.Login.php");
	//include_once ("classes/class.Database.php");
		
	$db = Database::Instance();
	$objModel = new Login();
    /***** FETCHING VARIABLES *****/		
	$id=decryptdata($_POST['id']);		// id is decrypted before excutings query
	$res = $objModel->getUserById($id);  
    if(is_array($res)) {        
        if(count($res) > 0) {
	          if($res[0]['status'] == '1') {
					$id			= $res[0]['id'];
					$username  	= $res[0]['username'];
					$name  		= $res[0]['name'];
					$email		= $res[0]['email'];
					$newpass = $_POST['new_passw'];
					$newpass = sha1($newpass);
					$update_sql = "update author set password='$newpass' where id=".$id."";	//echo "<p>sql=".$sql;
					$db->query($update_sql);
					$subject = ''.WEB_SITE_NAME.':Confirmation of Password Reset';
					$body = 'Your Account password has been updated in Indiatimes CMS.<br>Your New login details are:-<br>URL:-<a href="'.CMSSITEPATH.'/">'.CMSSITEPATH.'</a> <br>User Name:-'.$username.'<br>Password:-'.$_POST['new_passw'];
					$fromEmail = 'Administrator <' . NOREPLY . '>';
					sendHTMLMail($email, $subject, $body, $fromEmail); 
		  		}
		}
			echo '<font color="green" size="5">Password Succesfully Updated <a href="'.CMSSITEPATH.'/">Please Login</a></font>';
	    }else{
			echo '<font color="red" size="5">Password is Not Proper</font>';		
		}
?>
