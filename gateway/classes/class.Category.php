<?php
class Section {
	protected $finalData = array();
	private $db;
	private $tableName;
	/********************* START OF CONSTRUCTOR *******************************/
	public function __construct() {
		$this -> tableName = 'section';
		$this -> db = Database::Instance();
	}

	/**************************** END OF CONSTRUCTOR **************************/
	public function getListingData($search, $offset, $recperpage, $searchData, $status = '') {
		$keyValueArray = array();
		if ($status == '-1') {
			$keyValueArray['status'] = -1;
		} else {
			$keyValueArray['notequal'] = "status != -1";
		}
	    $main_sql = '1=1';
		if(array_key_exists('name',$searchData)) {
		    	$main_sql .= ' and name like \''.$searchData['name'].'%\'';
		}
		if(array_key_exists('parent_id',$searchData)) {
		    	$main_sql .= ' and parentid=\''.$searchData['parent_id'].'%\'';
		}
		
		if ($search == 'byname') {
			$keyValueArray['sqlclause'] = "name like '$searchData%'";
		}else if ($search == 'integer') {
			$keyValueArray['sqlclause'] = "substring(name,1,1) between '0' AND '9'";
		}else if ($search == 'by_section') {
			if($searchData !=''){
				$keyValueArray['sqlclause'] = "parentid = $searchData ";
			}
		}
		$keyValueArray['sqlclause'] = $main_sql;
		$limit = $offset . "," . $recperpage;

		$dataArr = $this -> db -> getDataFromTable($keyValueArray, $this -> tableName, "id,name,parentid,thumbnail,priority", "updatedate desc", $limit);
		if (count($dataArr) > 0) {
			$finalData['rowcount'] = count($dataArr);
			$i = 0;
			for ($p = 0; $p < $finalData['rowcount']; $p++) {
				$this -> finalData[] = $dataArr[$p];
			}
		}
		return $this -> finalData;
	}// eof getDefault

	public function getPagination($search, $searchData, $status) {
		// $status=1 for display listing status=-1 for trashcan
		$keyValueArray = array();
		$keyValueArray['status'] = $status;
		if ($search == 'byname') {
			$keyValueArray['sqlclause'] = "name like '$searchData%'";
		}else if ($search == 'integer') {
			$keyValueArray['sqlclause'] = "substring(name,1,1) between '0' and '9'";
		}else if ($search == 'by_section') {
			if($searchData !=''){
				$keyValueArray['sqlclause'] = "parentid = $searchData ";
			}
		}
		$main_sql = '1=1';
		if(array_key_exists('name',$searchData)) {
		    	$main_sql .= ' and name like \''.$searchData['name'].'%\'';
		}
		if(array_key_exists('parent_id',$searchData)) {
		    	$main_sql .= ' and parentid=\''.$searchData['parent_id'].'%\'';
		}
		$keyValueArray['sqlclause'] = $main_sql;
		$dataArr = $this -> db -> getDataFromTable($keyValueArray, $this -> tableName, "count(id) as cnt");
		$sql_count = $dataArr[0]['cnt'];
		return $sql_count;
	}// eof getPaginationQuery

	public function getEditData($id) {
		global $sizearray;
		$widthval=min($sizearray[$_SESSION['TOPMENU']][width]); 
		$heigthval=min($sizearray[$_SESSION['TOPMENU']][height]);
		$sizeval=$widthval.'x'.$heigthval;
	
		if (intval($id)) {
			$keyValueArray = array();
			$keyValueArray['id'] = intval($id);
			$dataArr = $this -> db -> getDataFromTable($keyValueArray, $this -> tableName, "*");
			$dataArr[0]['oldthumbnail']=$dataArr[0]['thumbnail'];
			$dataArr[0]['thumbnail']=getthumbnail($dataArr[0]['thumbnail'],$sizeval);
			if (count($dataArr)) {
				$json = json_encode($dataArr);
			}
		}
		return $json;
	}

	public function insertTable($values) {
		$return = $this -> db -> insertDataIntoTable($values, $this -> tableName);
		 process_content_cache('all','sec');
		 return $return;
	}// eof insertTable

	public function updateTable($values, $whereArr) {
		$return = $this -> db -> updateDataIntoTable($values, $whereArr, $this -> tableName);
		 process_content_cache('all','sec');
		 return $return;
	}// eof updatetable

	public function toggleTableStatus($val, $status) {
		$rowCount = 0;
		if (intval($val) > 0) {
			$rowCount = $this -> db -> updateDataIntoTable(array("status" => $status), array("id" => intval($val)), $this -> tableName);
		}
		 process_content_cache($whereArr['content_id']);
		 process_content_cache('all','sec');
		return $rowCount;
	}// eof toggleStatus

	public function getSectionTree1($level, $curr = 0) {
		// initialize container array
		if (!isset($this -> sectionTree) || $curr == 0) {
			$this -> sectionTree = array();
			$this -> excludes = array();
		}

		$arrWhere = array();
		$arrWhere['status'] = 1;
		$arrWhere['parentid'] = 0;

		$orderby = 'id';
		$limit = '';
		$dataArr = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);
		foreach ($dataArr as $r) {
			if (!in_array($r['id'], $this -> excludes)) {

			}
		}
	}
	
	//this is used in navigation.php for search by section name
	public function getParentSectionTree($val) {
		// initialize container array
		if (!isset($this -> sectionTree) || $curr == 0) {
			$this -> sectionTree = array();
			$this -> excludes = array();
		}
		$arrWhere = array();
		$arrWhere['status'] = 1;
		$arrWhere['parentid'] = 0;
		$orderby = 'id';
		$limit = '';
		$dataArr = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);
		foreach ($dataArr as $r) {
			if (!in_array($r['id'], $this -> excludes)) {
				$arrSectionTree[$r['id']] = $r['name'];
				if($val != ''){
					if($val == $r['id'] || $r['id'] == $this ->getimediateparent($val)  ){
						$arrWhere['parentid'] = $r['id'];
						$dataArr2 = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);
						foreach ($dataArr2 as $r2) {
							$arrSectionTree[$r2['id']] = "---x---" . $r2['name'];
						}
					}
				}
			}
		}
		return $arrSectionTree;
	}
	
	public function getimediateparent($childid){
		$arrPsecWhere = array();
		$arrPsecWhere['status'] = 1;
		$arrPsecWhere['id'] = $childid;
		$datapsecArr = $this -> db -> getDataFromTable($arrPsecWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);
		return $datapsecArr[0]['parentid'];
	}
	
	public function getSectionTree() {
		$arrSectionTree = array();
		$arrWhere = array();
		$arrWhere['status'] = 1;
		$arrWhere['parentid'] = 0;
		$orderby = 'name';
		$limit = '';
		$dataArr = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);

		foreach ($dataArr as $r) {
			$arrSectionTree[$r['id']] = $r['name'];

			$arrWhere['parentid'] = $r['id'];
			$dataArr1 = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);
			//print_r($dataArr1);
			foreach ($dataArr1 as $r1) {
				$arrSectionTree[$r1['id']] = "---" . $r1['name'];

				$arrWhere['parentid'] = $r1['id'];
				$dataArr2 = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);
				//print_r($dataArr2);
				foreach ($dataArr2 as $r2) {
					$arrSectionTree[$r2['id']] = "---x---" . $r2['name'];
				}
			}
		}

		return $arrSectionTree;
	}

	public function getSectionTreeparent() {
		$arrSectionTree = array();
		$arrWhere = array();
		$arrWhere['status'] = 1;
		$arrWhere['parentid'] = 0;
		$orderby = 'id';
		$limit = '';
		$dataArr = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);

		foreach ($dataArr as $r) {
			$arrSectionTree[$r['id']] = $r['name'];

			//$arrWhere['parentid'] = $r['id'];
			//$dataArr1 = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);
			//print_r($dataArr1);
			/*foreach ($dataArr1 as $r1) {
				$arrSectionTree[$r1['id']] = "---" . $r1['name'];

				$arrWhere['parentid'] = $r1['id'];
				$dataArr2 = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);
				//print_r($dataArr2);
				foreach ($dataArr2 as $r2) {
					$arrSectionTree[$r2['id']] = "---x---" . $r2['name'];
				}
			}*/
		}

		return $arrSectionTree;
	}

	public function getSectionTreelist($parentid=0) {
		$arrSectionTree = array();
		$arrWhere = array();
		$arrWhere['status'] = 1;
		$arrWhere['parentid'] = $parentid;
		$orderby = 'name';
		$limit = '';
		$dataArr = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid, priority, is_tab", $orderby, $limit);
        $all_sections_id = array();

		foreach ($dataArr as $r) {
			$arrSectionTree[$r['id']]['data'] = $r['name'];
			$arrSectionTree[$r['id']]['priority'] = $r['priority'];
			$arrSectionTree[$r['id']]['is_tab'] = $r['is_tab'];
			$arrSectionTree[$r['id']]['childs'] = array();
                       
			$arrWhere['parentid'] = $r['id'];
			$dataArr1 = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid, priority, is_tab", $orderby, $limit);
                        
                        $all_sections_id[] = $r['id'];
			//print_r($dataArr1);
			foreach ($dataArr1 as $section_key => $r1) {
				//$arrSectionTree[$r1['id']] = "---" . $r1['name'];
				$arrSectionTree[$r['id']]['childs'][$r1['id']] = array();
				$arrSectionTree[$r['id']]['childs'][$r1['id']]['data'] = $r1['name'];
				$arrSectionTree[$r['id']]['childs'][$r1['id']]['priority'] = $r1['priority'];
				$arrSectionTree[$r['id']]['childs'][$r1['id']]['is_tab'] = $r1['is_tab'];
				$arrSectionTree[$r['id']]['childs'][$r1['id']]['childs'] = array();
                                $all_sections_id[] = $r1['id'];

				$arrWhere['parentid'] = $r1['id'];
				$dataArr2 = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid, is_tab", $orderby, $limit);

				//print_r($dataArr2);
				foreach ($dataArr2 as $section_key2 => $r2) {                                  
					//$arrSectionTree[$r2['id']] = "---x---" . $r2['name'];
					$arrSectionTree[$r['id']]['childs'][$r1['id']]['childs'][$r2['id']] = array();
					$arrSectionTree[$r['id']]['childs'][$r1['id']]['childs'][$r2['id']]['data'] = $r2['name'];
					$arrSectionTree[$r['id']]['childs'][$r1['id']]['childs'][$r2['id']]['priority'] = $r1['priority'];
					$arrSectionTree[$r['id']]['childs'][$r1['id']]['childs'][$r2['id']]['is_tab'] = $r1['is_tab'];
                                        $all_sections_id[] = $r2['id'];
				}
			}
		}
                if ( 0 < count($all_sections_id) )
                {
                    $keyValueArray['sqlclause'] = ' section_id in('.implode(',',$all_sections_id).') and c.is_aggregator_data=0 and csr.is_primary=1 and c.status!=-1 group by section_id';
                    $stories_counts = $this->db->getDataFromTable($keyValueArray, "content_section_relation as csr LEFT JOIN content as c on csr.content_id=c.id", "count(csr.id) as count,csr.section_id");

                    foreach($arrSectionTree as $key =>$section_data) {
                        if(isset($section_data['childs']) && !empty($section_data['childs'])) {
                            $childs = array();
                            $childs = $section_data['childs'];
                            foreach($childs as $section_key=>$child){
                            foreach($stories_counts as $story_count) {
                                    if($story_count['section_id']==$section_key){
                                        $arrSectionTree[$key]['childs'][$section_key]['story_count'] = $story_count['count'];
                                    }
                                }
                            }
                        } else {
                            foreach($stories_counts as $story_count) {
                                    if($story_count['section_id']==$key){
                                        $arrSectionTree[$key]['story_count'] = $story_count['count'];
                                    }
                                }
                        }
                    }
                }
                

		return $arrSectionTree;
	}

	public function getParentSection() {
		$arrSectionTree = array();
		$arrWhere = array();
		$arrWhere['status'] = 1;
		$arrWhere['parentid'] = 0;

		$orderby = 'id';
		$limit = '';
		$dataArr = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);

		foreach ($dataArr as $r) {
			$arrSectionTree[$r['id']] = $r['name'];
		}

		return $arrSectionTree;
	}
      
	public function getSectionDdl() {
		$arrData = $this->getSectionTree();
		$strReturn = '<select id="parentsectionname" name="parentid" class="select"><option value="">--SELECT PARENT ID--</option>';
		foreach($arrData as $id => $section) { 
			$strReturn .= '<option value="'.$id.'">'.$section.'</option>';
		}
		$strReturn .= '</select>';
		
		return $strReturn;
	}
	  
    public function updateSectionName($id,$section_value) {
       $this -> db -> updateDataIntoTable(array("section_name" => $section_value), array("section_id " => intval($id)), 'content_section_relation',false);
	   $this -> db -> updateDataIntoTable(array("section_parentname" => $section_value), array("section_parentid " => intval($id)), 'content_section_relation',false);
    }
}
?>