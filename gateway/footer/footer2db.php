<?php
/***** INCLUDE CONNECTION FILE **********************************************************************/
	include_once('../config.php');
	$conn = Database::Instance();

/*****  FETCHING VARIABLES **********************************************************************/
	$module="pagemanagement";	//-------module name to track the filename
	$action=$_POST['action'];
	$blockid=$_POST['blockid']; //-------decrypt ecrypted id	
	$pageid=$_POST['pageid'];
	$contentid=$_POST['contentid'];	
	$id= $_POST['id'];
		
/***** INSERT INTO db_region TABLE **********************************************************************/
	switch($action){
		case 'a':
			$data = $conn->getDataFromTable(array("block_id"=>$blockid, "page_id" => $pageid), "pagemanagement", "MAX(priority) as max");
			$priority = $data[0]['max'];
			$priority++; 
			$sql ="INSERT INTO footer (block_id,page_id,content_id,priority, status) VALUES('$blockid','$pageid','$contentid','$priority', 0) ";
		break;
		case 'd':
			 $sql="DELETE from footer WHERE id=$id";
		break;
		case 'db':
			 $sql="DELETE from footer WHERE block_id=$blockid;";
			 if($sql!='' && RIGHTS!=0){
				$conn->query($sql); 
			 } 
			 $sql = "DELETE from footer WHERE id=$blockid";
		break;
	}

		
	
//***** IF QUERY SET CORRECTLY *****************************************************************************/	
	if($sql!='' && RIGHTS!=0){
		$res = $conn->query($sql);
		if($action=='a'){
			$id=$conn->getInsertedAutoId();
		}else{
			$id=$id;
		}
	}

/***** GET THE NO. OF RECORDS 'ACTIVE' **************************************/
	$sql_count="SELECT count(ID) AS cnt FROM footer";
	$conn->query($sql_count);
	$result_count = $conn->fetch();
	$count= $result_count['cnt'];
	
   	if(RIGHTS==0){
	/***** IF RIGHTS=='READONLY' THEN SEND RESPONSE BACK AS 'No Action can be Performed for this Account' **************************************/
		$msg=chkrightsmsg($action);
		$status=0;
	}else if($res==true){
	/***** IF STATEMENT SUCCESS THEN SEND RESPONSE BACK AS 'SUCCESS' **************************************/   
		$msg=ressuccessmsg($action);
		$status = 1;
  }else {
	/***** IF STATEMENT FAILS THEN SEND RESPONSE BACK AS 'FAILED' **************************************/
		$msg = resfailedmsg($action);
		$status=0;
  }
  
/**** NOW SEND THE OUTPUT RESPONSE BACK ******************************************************************/
	$jsonStr ="{'msg':'$msg','status':'$status','action':'$action','id':'$id','module':'$module','numRecords':'$count'}";
	echo $jsonStr;
?>