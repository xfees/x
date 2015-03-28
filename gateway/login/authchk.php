<?php 	
include_once('../inc/constants.php');
/***** To check if the user is authenticated & has the needed sessions sets  *****/
if(!isset($_SESSION['ITUser']['LAST_ACTIVITY'])) {
    $_SESSION['ITUser']['LAST_ACTIVITY'] = time();
}
if(!isset($_SESSION['ITUser']['ID']) || !isset($_SESSION['ITUser']['USERNAME']) || !isset($_SESSION['ITUser']['RIGHTS']) || (time() - $_SESSION['ITUser']['LAST_ACTIVITY'] > 3600)) {
    session_destroy();
    $_SESSION['REDIRECT'] = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    $Go = CMSSITEPATH."/index.php";
    header("Location: $Go");
    exit;
}
$_SESSION['ITUser']['LAST_ACTIVITY'] = time();
// Access Check
$acmod = $_SESSION['ITUser']['RIGHTSMOD'];
$acaction = $_REQUEST['action'];
$accessFlag = true;

switch($acaction) {
    case 'a': // INSERT
	    $accessFlag = ($acmod[0]== 1) ? true : false;
		break;						
    case 'm': // Update
    case 'qe': // Quick Update
    case 'e': // Update
        if($acaction == 'm' && ($_REQUEST['savestay'] == 1 || $_REQUEST['taction'] == 'sas')) {
            $accessFlag = ($acmod[0] == 1) ? true : false;  // add - save and stay
        } else {
            $accessFlag = ($acmod[1] == 1) ? true : false;
        }
		break;
    case 'd': // Delete
		$accessFlag = ($acmod[2] == 1) ? true : false;
		break;
    case 'p': // Publish
		$accessFlag = ($acmod[3] == 1) ? true : false;						
		break;			 		  				   
}

if($acmod[0] == 0) {
    $jsCode = '<script type="text/javascript">
		var accessAddFalg = "%$#@%";
		</script>';
} else {
    $jsCode = '<script type="text/javascript">
		var accessAddFalg = "";
		</script>';
}
define('ACCESS',$accessFlag);
if (!ACCESS) {
    echo '%$#@%'; // access denied
    exit;
}

