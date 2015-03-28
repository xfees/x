<?php error_reporting(0);
/***** INCLUDE CONNECTION FILE **********************************************************************/
	include_once('../config.php');/***** SELECT DATABASE **********************************************************************/
	$conn = Database::Instance();
/*****  FETCHING VARIABLES **********************************************************************/
	$module="footer";
	if(isset($_POST)){
		$id=$_POST['id'];
		$action=$_POST['action'];
		$refreshDivId=$_POST['refreshDivId']; 
		$flag=$_POST['flag'];
		$search=$_POST['search'];
		$searchSectionId=$_POST['data']; // this is section id
		$accesstosections=$_POST['accesstosections'];	
		$searchByHeadline=$_POST['searchByHeadline'];
		$searchById=intval($_POST['searchById']);
		$searchByDate=$_POST['searchByDate'];
		$searchByCategory=$_POST['searchByCategory'];
		$searchByAuthor=$_POST['searchByAuthor'];
	}


	if($action=='p'){
		if(intval($id) > 0){
			$statusData = $conn->getDataFromTable(array("id"=>$id), "footer", "status");	
			if($statusData[0]['status']==0){
				$update = 1;
			}else{
				$update = 0;
			}
			$response = $conn->updateDataIntoTable( array("status"=>$update), array("id"=>$id),"footer");	
			echo intval($response)."|".$update;
		}
		exit;
	}

	if(empty($search)){
		$search='first';
	}
	if($searchByDate!=""){
		$date=explode("-",$searchByDate);
		$year=explode(" ",$date[2]);
		$date1=$year[0]."-";
		if($date[1]<10)
			$date1.="0".$date[1]."-";
		else
			$date1.=$date[1]."-";
		if($date[0]<10)
			$date1.="0".$date[0]."";
		else
			$date1.=$date[0]."";
	}	
	if($_COOKIE['RIGHTS']==2){
		$userid=decryptdata($_COOKIE['ID']);
		$ShowRecordsSQL=" AND c.author_id=$userid ";
	}else{
		$ShowRecordsSQL='';
	}	
 
	$recperpage = isset($_POST['recperpage']) ? $_POST['recperpage'] : MAX_FORMS_PER_PAGE; 
	$pg = isset($_POST['pg']) ? $_POST['pg']: "";
	$dispfirstpage = isset($_POST['dispfirstpage']) ? $_POST['dispfirstpage'] : "";
	$displastpage = isset($_POST['displastpage']) ? $_POST['displastpage'] : "";
	$search = isset($_POST['search']) ? $_POST['search'] : "first";

	if(isset($search)){	
		$searchData = '' ;		
		$sql="";
		switch($search){
			case 'byname':				
				$sql = "SELECT c.id, c.headline1, c.contype_name, group_concat(cs.section_name) as section_name, c.publishdate 
						FROM content AS c
						LEFT JOIN content_section_relation AS cs ON cs.content_id = c.id
						WHERE c.status =  1 $ShowRecordsSQL";
				$sql_count = "select count(distinct c.id) as cnt  FROM content AS c
				LEFT JOIN content_section_relation AS cs ON cs.content_id = c.id where  c.status=1 $ShowRecordsSQL";
				
				if($searchByHeadline != "")
				{
					$searchData .= 'searchByHeadline=' . $searchByHeadline . '&' ; 
					$sql .=" AND c.headline1 LIKE '%$searchByHeadline%' ";
					$sql_count .=" AND c.headline1 LIKE '%$searchByHeadline%' ";
				}

				if($searchById != ""){
					$sql .=" AND c.id =$searchById ";
					$sql_count .=" AND c.id =$searchById  ";
					$searchData .= 'searchById=' . $searchById . '&' ; 
				}		

				if($date1 != ""){
					$sql .=" AND c.publishdate LIKE '$date1%' ";
					$sql_count .=" AND c.publishdate LIKE '$date1%' ";
					$searchData .= 'date1=' . $date1 . '&' ; 
				}	
				if($searchByContenttype != ""){
					$sql .=" AND c.contype_id =$searchByContenttype ";
					$sql_count .=" AND c.contype_id =$searchByContenttype ";
					$searchData .= 'searchByContenttype=' . $searchByContenttype . '&' ; 
				}
				if($searchByCategory != ""){

					$searchData .= 'searchByCategory=' . $searchByCategory . '&' ; 
					$sql .=" AND cs.section_id=$searchByCategory ";
					$sql_count .=" AND cs.section_id=$searchByCategory ";
				}
				
				if($searchByAuthor != ""){

					$searchData .= 'searchByAuthor=' . $searchByAuthor . '&' ; 
					$sql .="   AND FIND_IN_SET(".$searchByAuthor.",c.by_line_author_id) ";
					$sql_count .="   AND FIND_IN_SET(".$searchByAuthor.",c.by_line_author_id)  ";
				}
				break;	 
				
			case 'first':
				$sql = "SELECT c.id, c.headline1, c.contype_name, group_concat(cs.section_name) as section_name, c.id, c.publishdate 
						FROM content AS c 
						LEFT JOIN content_section_relation AS cs ON cs.content_id = c.id
						WHERE c.STATUS =  1 $ShowRecordsSQL ";
				$sql_count ="select count(distinct c.id) as cnt from content  AS c LEFT JOIN content_section_relation AS cs ON cs.content_id = c.id					 where c.status=1 $ShowRecordsSQL ";
				
				if($searchSectionId!="" && $searchSectionId!="0"){
					$sql .=" AND (cs.section_id=".$searchSectionId." OR cs.section_parentid=".$searchSectionId.")";
					$sql_count .=" AND (cs.section_id=".$searchSectionId." OR cs.section_parentid=".$searchSectionId.")";
				}				
				break;	
		}	
	
	/* Initialize paginate and supply it with necessary params */
		$paginate= new Paginate($module,$search,$searchData,$dispfirstpage,$displastpage,$pg, $action, $recperpage);
		$offset=$paginate->offset;
		$recperpage=$paginate->recperpage;
		$sql .=" group by c.id ORDER by c.publishdate DESC LIMIT $offset, $recperpage";
		$conn->query($sql);
		$count= $conn->getRowCount();
?>
		<li class="removeheading">
			 <table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr>
						<td width="60%" class="titlebar pL">
						Headline
						</td>
						<td class="titlebar pL">
						<img src="../images/list-separator.gif" width="2" height="31" border="0" align="absmiddle" class="listSeparator" />&nbsp;Publish Date
						</td>
						</tr>
			</table>
		</li>
<?php
		if($count > 0)
		{
		  	while($result_data=$conn->fetch())
			{
				$encryptedid=$result_data["id"];
?>				
				  <li class="move listing ui-state-defaults" id='singleCont<?php echo $result_data['id']?>'>
  					<input type='hidden' value='' class='recordid' />
					<input type="hidden" class="contentid" value="<?php echo $encryptedid?>" />
				   <table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr>
						<td width="60%" class="lc_content">
						<?php echo $result_data["headline1"]?><br />
					<span style="color:gray"><?php echo ($result_data['section_name']!='') ? $result_data['section_name'].' &rsaquo;':""?>
					<?php echo ($result_data['contype_name']!='') ? $result_data['contype_name']: ""; ?> </span>
					
						</td>
						<td class="rc_content">
						<?php echo getdisplaydatetime($result_data["publishdate"])?>
						</td>
						</tr>
				   </table>
				</li>                

<?php		
			}//end of while
		}//end of 						
		else 
		{ 
?>
				 <li class="pL pTB">NO RECORDS</li>
<?php			 
		}
?>
<li class="last">
<?php			
			$conn->query($sql_count);
			$coundData=$conn->fetch();
			echo $paginate->render($coundData['cnt']);	
?>
</li>
<?php
	}
?>