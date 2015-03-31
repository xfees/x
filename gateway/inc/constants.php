<?php
session_start();
error_reporting(1);
ini_set("display_errors", "On");
//All constants to be added here. This file will be included in all files
if(!isset($_SESSION['ITUser']['RIGHTS']) || $_SESSION['ITUser']['RIGHTS']==0){
	define('RIGHTS', '0');
}else{
	define('RIGHTS', $_SESSION['ITUser']['RIGHTS']);
}

$userDetails = array(
				'0' => 'Read Only',
				'1' => 'Admin',
				'2' => 'Author',
				'3' => 'Developer'
				);
define('APPLICATION_ENV', 'development');

/* provide the multiple databases details as an associative array 
where KEY = dbname referral name and 
VALUE = array of details which includes database name, hostname, password and username */
$dbdetails = array(
		'x' => array(
							"host" => 'localhost',
							"database" => 'x',
							"user"	   => 'root',
							"password" => 'redhat')
);


//define contype
define('NEWS',1);

/*bof mongodb conf*/
define('MONGO_SERVER_IP', 'localhost');
define('MONGO_DB', 'checkfees');
define('MONGO_COLL_ADMIN_LOG', 'admin_action_log');
/*eof mongodb conf*/

//constants defined for contenttype management Ends
$dirPath = explode("/", getcwd());
define('CUR_DIR', $dirPath[count($dirPath) - 1]);
define('MAX_FORMS_PER_PAGE', '20');
define('WEB_SITE_NAME', 'X'); // This constant is required for the Author Management Mail (New author/Password Reset)
define('DOMAIN_NAME', 'checkfees.in');
define('NOREPLY', 'noreply@checkfees.in');

define('DOMAIN_HOST_IP', $_SERVER['HTTP_HOST']);
//CMS file related Path Starts here
define('SITEPATH',"http://".DOMAIN_HOST_IP . "/x");
define('FRONTEND_SITE_URL', SITEPATH);
define('FRONTEND_MASTER_SITE_URL', FRONTEND_SITE_URL);//This should be point to master server or live and stating 
define('FRONTEND_MASTER_SITE_CURL_URL', FRONTEND_SITE_URL); //This should be point to master server or live and stating 

define('FRONTEND_MEMCACHE_API', FRONTEND_MASTER_SITE_URL . '/clean_cache.php');
define('FRONTEND_STATIC_GEN_API', FRONTEND_MASTER_SITE_URL . '/crons/cmd_generator.php');
define('ROOTPATH',$_SERVER['DOCUMENT_ROOT'] . "/x");
define('CMSSITEPATH',SITEPATH."/gateway");
define('CMSROOTPATH',ROOTPATH."/gateway");
define('TODBFILEPATH', CMSSITEPATH.'/2db');
define('FUNCTIONPATH',CMSROOTPATH."/inc/functions.php");
define('IMAGEFUNCTIONPATH',CMSROOTPATH."/inc/imagefunctions.php");
define('AUTHCHECK',CMSROOTPATH."/inc/authchk.php");
define('JSFILEPATH', CMSSITEPATH.'/js');
define('CSSFILEPATH', CMSSITEPATH.'/css');
define('IMAGEPATH', CMSSITEPATH.'/images');				//echo "<p>image=".IMAGEPATH;
define('MAILFUNCTIONPATH',CMSROOTPATH."/inc/mail_functions.php");

define('IMAGE_QUALITY', 90); //Image Quality to be considered for resizing.

/*bof CMS media*/
define('SITE_MEDIA_PATH', ROOTPATH . '/media');
define('SITE_MEDIA_URL', SITEPATH . '/media');
/*eof CMS media*/

//AUTHOR IMAGE SIZES ARRAY
define('SITEAUTHORPATH', SITE_MEDIA_URL);
$serverpath['author'] = SITE_MEDIA_PATH;
	
$sizearrayPlugin['author']['thumbnail'] =array (
        "width"  => array(150, 74, 60, 34),
        "height" => array(150, 74, 60, 34));

$TopFlap = array(1=>'Network Sites',2=>'Top Sites',3=>'News',4=>'Leisure',5=>'Communities',6=>'Services',7=>'Shop',8=>'Mobile',9=>'Other timesgroup footer');
