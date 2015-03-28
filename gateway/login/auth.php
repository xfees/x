<?php
include_once ('../inc/constants.php');
include_once ('../inc/functions.php');
include_once ('../inc/mail_functions.php');
include_once ('../securimage/securimage.php');
$securimage = new Securimage();

$objModel = new Login();
$action = $_POST['action'];

switch($action) {
    case 'login':
        if ($securimage->check($_POST['captcha_code']) == false) {
          echo 'captcha_fail';
          exit;
        }
        $user =	$_POST['username'];
        $pswd =	$_POST['password'];
        
        $ip = getIP();
        $pswd = sha1($pswd);
        
        // log login attempt
        $arrLog = array();
        $arrLog['username'] = $user;
        $arrLog['password'] = $pswd;
        $arrLog['ip'] = $ip;
        $arrLog['insertdate'] = date('Y-m-d H:i:s');
        $res = $objModel->checkLogin($user, $pswd);      
        if(is_array($res)) {        
            if(count($res) == 1) {          
                $userid = $res[0]['id'];
                $user = $res[0]['name'];
                $rights = $res[0]['rights'];
                $rightsmod = $res[0]['rightsmod'];
                switch($rights) {
                case '0':
                    $userright = 0;
                    break;
                case '1':
                    $userright = 1;
                    break;
                case '2':
                    $userright = 2;
                    break;
                default:	// Keep it for Read-only, so that no action will be performed for this author
                    $userright = 0;
                    break;
                }// eof switch
                $_SESSION['ITUser'] = array();
                $_SESSION['ITUser']['ID'] = $userid;
                $_SESSION['ITUser']['USERNAME'] = $user;
                $_SESSION['ITUser']['RIGHTS'] = $userright;
                $_SESSION['ITUser']['RIGHTSMOD'] = $rightsmod;
                $_SESSION['TOPMENU'] = (isset($_SESSION['TOPMENU']))?"Homepage" : $_SESSION['TOPMENU'];
                $_SESSION['ITUser']['LAST_ACTIVITY'] = time();
                unset($_SESSION['NewUser']);
                $Go = 1;
                // Update the Last_visit time in db_author_master
                if (!empty($userid)) {
                    $val = array();
                    $val['lastvisit'] = @date("Y-m-d H:i:s");
                    $where = array();
                    $where['id'] = $userid;
                    $objModel->updateTable($val, $where);            
                }// eof empty $userid
			} else {
				$Go = 0;
	        } //eof count $res == 1
        } else {
            $Go = -1; // Query has an error
        } // eof if array $res
        break;
    case 'forget_password':
        $user =	$_POST['username'];
        $usercnt = strlen($user);
        if ($usercnt < 1) {
            $Go = 'Please enter a valid Username or Email address.';
        } else {
            $res = $objModel->getUserByUsername($user);      
            if(is_array($res)) {        
                if(count($res) > 0) {
                    if($res[0]['status'] != '-1') {
                        $userid		= encryptdata($res[0]['id']);
                        $username  	= $res[0]['username'];
                        $name  		= $res[0]['name'];
			            $email		= $res[0]['email'];
			            $subject	= WEB_SITE_NAME . ' CMS: Password Reset';
			            $Lurl  		= CMSSITEPATH.'/reset-password.php?auth='.$userid;
                        $body = 'Greetings '.$name.',<br/>
					        Your account details are as follows:-<br><br>
					        Username: ' . $username. '<br>
					        To reset your account password, please click on the below link:
					        <br>
					        <br>
					        <a href="' . $Lurl . '">' . $Lurl . '</a>
					        <br>
					        <br>
					        Regards,
					        <br>
					        Team  ' . WEB_SITE_NAME . '
					        <br><br>
					        ';
                        $fromEmail = 'Administrator<' . NOREPLY . '>';
                        sendHTMLMail($email, $subject, $body, $fromEmail); 
                        $Go = 'Please check your email address which is registered with us.';
                    } else {
                        $Go = 'Sorry, Your account has been deleted by Site Administrator.';
                    }
                } else {
                    $Go = 'Sorry, Username or Email Address is not registered with us.';
                }
            } else {
                $Go = 'Sorry, Invalid Username or Email Address.';
            }   
        }
	    break;
    case 'resetpwd':
        $user =	$_POST['id'];
        $pswd =	$_POST['password'];
        $pswd = sha1($pswd);
        $res = $objModel->getUser($user);
        $oldpwd = $res[0]['password'];
        if ($oldpwd == $pswd) {
	        $Go = 2;
        } else {		
            $val = array();
            $val['password'] = $pswd;
            $val['is_password_changed'] = 1;
            $where = array();
            $where['id'] = $user;
            $objModel->updateTable($val, $where);			   
              
            $userid = $res[0]['id'];
            $user = $res[0]['name'];
            $rights = $res[0]['rights'];
            $rightsmod = $res[0]['rightsmod'];
            switch($rights) {
                case '0':
                  $userright=0;
                  break;
                case '1':
                  $userright=1;
                  break;
                case '2':
                  $userright=2;
                  break;
                default:	// Keep it for Read-only, so that no action will be performed for this author
                  $userright=0;
                  break;
            }// eof switch
            $_SESSION['ITUser'] = array();
            $_SESSION['ITUser']['ID'] = $userid;
            $_SESSION['ITUser']['USERNAME'] = $user;
            $_SESSION['ITUser']['RIGHTS'] = $userright;
            $_SESSION['ITUser']['RIGHTSMOD'] = $rightsmod;
            $_SESSION['TOPMENU'] = (isset($_SESSION['TOPMENU']))?"Homepage" : $_SESSION['TOPMENU'];
            $_SESSION['ITUser']['LAST_ACTIVITY'] = time();
            unset($_SESSION['NewUser']);				 
            $Go = 1;
            // Update the Last_visit time in db_author_master
            if (!empty($userid)) {
                $val = array();
                $val['lastvisit'] = @date("Y-m-d H:i:s");
                $where = array();
                $where['id'] = $userid;
                $objModel->updateTable($val, $where); 
                  
                $username  	= $res[0]['username'];
                $name  		= $res[0]['name'];
                $email		= $res[0]['email'];
                $subject	= WEB_SITE_NAME . ' CMS: Password Reset';
                $body = 'Greetings '.$name.'<br/><br/>
                        Your password has been changed.<br/><br/>
                        Your account details are as follows:-<br><br>

                        Username: ' . $username. '<br>
                        Password: '. $_POST['password'].  ' <br><br>

                        Regards,
                        <br>
                        Team  ' . WEB_SITE_NAME . '
                        <br><br>
                        ';
                $fromEmail = 'Administrator<' . NOREPLY . '>';
                sendHTMLMail($email, $subject, $body, $fromEmail);          
            } // eof empty $userid
        } // eof oldpwd != newpwd
        break;
}
echo intval($Go);
exit;
