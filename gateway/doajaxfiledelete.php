<?php

error_reporting(0);
include_once('config.php');

$db = Database::Instance() ;
//print_r($_POST);

$id = $_POST['id'];
$module = $_POST['module'];
$table = $_POST['tab'];
$column = $_POST['col'];
$imgpath = $_POST['imgpath'];

$strImgPath = $serverpath[$module].'/'.$_POST['imgpath'];
$updateArray[$column] = '';
$whereArr = array('id'=>$id);

//var_dump($db);
$returnVal = $db->updateDataIntoTable( $updateArray, $whereArr, $table );

if( $returnVal > 0){
	 if(is_file($strImgPath)){
			@unlink($strImgPath);
	 }
	 $status = 'Success';
}
else{
	 $status = 'Failed';
}
$id = $_POST['id']; 

$jsonStr ="{'status':'$status','id':'$id','module':'$module'}";
echo $jsonStr;
?>