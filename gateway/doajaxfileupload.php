<?php
	error_reporting(0);
	include_once('config.php');
	$module=$_POST['modulename'];
	$filename=$_POST['filename'];
	
	$server_temp = SITE_MEDIA_TEMP_PATH . DIRECTORY_SEPARATOR;
	$server_final = SITE_MEDIA_PATH . DIRECTORY_SEPARATOR;
	$listid = $_REQUEST['listid'];

	$error = "";

	$fileElementName = $filename;
	$xfile_type = $_FILES[$fileElementName]["type"]; 
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{

			case '1':
				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case '2':
				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case '3':
				$error = 'The uploaded file was only partially uploaded';
				break;
			case '4':
				$error = 'No file was uploaded.';
				break;

			case '6':
				$error = 'Missing a temporary folder';
				break;
			case '7':
				$error = 'Failed to write file to disk';
				break;
			case '8':
				$error = 'File upload stopped by extension';
				break;
			case '999':
			default:
				$error = 'No error code avaiable';
		}
	}
	elseif(empty($_FILES[$filename]['tmp_name']) || $_FILES[$filename]['tmp_name'] == 'none')
	{
		$error = 'No file was uploaded!!..';
	}
	else if (ereg("^image/",$xfile_type) != true) 
	{	
		$error='Image File Error. Please upload a .jpg or .gif';
	}
	else 
	{
		//Upload the image & resize it to its requires sizes
		$resultthumb=uploadimage($_FILES[$filename],$module); 
		$imagename = $resultthumb[0];
		//for security reason, we force to remove all uploaded file
		@unlink($_FILES[$filename]);	
		
	}	
	echo "{";	
	echo				"error: '" . $error . "',\n";
	echo				"msg: '" . $imagename . "'\n";
	echo "}";
?>