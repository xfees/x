<?php
class Category {
	protected $finalData = array();
	private $db;
	private $tableName;
	/********************* START OF CONSTRUCTOR *******************************/
	public function __construct() {
		$this -> tableName = 'category';
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
		} elseif ($search == 'integer') {
			$keyValueArray['sqlclause'] = "substring(name,1,1) between '0' AND '9'";
		} elseif ($search == 'by_section') {
			if($searchData !='') {
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

	public function getCategoryTree1($level, $curr = 0) {
		// initialize container array
		if (!isset($this -> categoryTree) || $curr == 0) {
			$this -> categoryTree = array();
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
	//this is used in navigation.php for search by category name
	public function getParentCategoryTree($val) {
		// initialize container array
		if (!isset($this -> categoryTree) || $curr == 0) {
			$this -> categoryTree = array();
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
				$arrCategoryTree[$r['id']] = $r['name'];
				if($val != ''){
					if($val == $r['id'] || $r['id'] == $this ->getimediateparent($val)  ){
						$arrWhere['parentid'] = $r['id'];
						$dataArr2 = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);
						foreach ($dataArr2 as $r2) {
							$arrCategoryTree[$r2['id']] = "---x---" . $r2['name'];
						}
					}
				}
			}
		}
		return $arrCategoryTree;
	}
	
	public function getimediateparent($childid){
		$arrPsecWhere = array();
		$arrPsecWhere['status'] = 1;
		$arrPsecWhere['id'] = $childid;
		$datapsecArr = $this -> db -> getDataFromTable($arrPsecWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);
		return $datapsecArr[0]['parentid'];
	}
	
	public function getCategoryTree() {
		$arrCategoryTree = array();
		$arrWhere = array();
		$arrWhere['status'] = 1;
		$arrWhere['parentid'] = 0;
		$orderby = 'name';
		$limit = '';
		$dataArr = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);
		foreach ($dataArr as $r) {
			$arrCategoryTree[$r['id']] = $r['name'];
			$arrWhere['parentid'] = $r['id'];
			$dataArr1 = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);
			//print_r($dataArr1);
			foreach ($dataArr1 as $r1) {
				$arrCategoryTree[$r1['id']] = "---" . $r1['name'];

				$arrWhere['parentid'] = $r1['id'];
				$dataArr2 = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);
				//print_r($dataArr2);
				foreach ($dataArr2 as $r2) {
					$arrCategoryTree[$r2['id']] = "---x---" . $r2['name'];
				}
			}
		}
		return $arrCategoryTree;
	}

    public function getCategoryTreeparent() {
        $arrCategoryTree = array();
        $arrWhere = array();
        $arrWhere['status'] = 1;
        $arrWhere['parentid'] = 0;
        $orderby = 'id';
        $limit = '';
        $dataArr = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);
        foreach ($dataArr as $r) {
	        $arrCategoryTree[$r['id']] = $r['name'];
        }
        return $arrCategoryTree;
    }

    public function getCategoryTreelist($parentid = 0, $status = 1) {
        $arrCategoryTree = array();
        $arrWhere = array();
        $arrWhere['status'] = $status;
        $arrWhere['parentid'] = $parentid;
        $orderby = 'name';
        $limit = '';
        $dataArr = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid, priority", $orderby, $limit);
        $all_sections_id = array();

        foreach ($dataArr as $r) {
            $arrCategoryTree[$r['id']]['data'] = $r['name'];
            $arrCategoryTree[$r['id']]['priority'] = $r['priority'];
            $arrCategoryTree[$r['id']]['childs'] = array();

            $arrWhere['parentid'] = $r['id'];
            $dataArr1 = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid, priority", $orderby, $limit);

            $all_sections_id[] = $r['id'];
            foreach ($dataArr1 as $category_key => $r1) {
                $arrCategoryTree[$r['id']]['childs'][$r1['id']] = array();
                $arrCategoryTree[$r['id']]['childs'][$r1['id']]['data'] = $r1['name'];
                $arrCategoryTree[$r['id']]['childs'][$r1['id']]['priority'] = $r1['priority'];
                $arrCategoryTree[$r['id']]['childs'][$r1['id']]['childs'] = array();
                $all_sections_id[] = $r1['id'];

                $arrWhere['parentid'] = $r1['id'];
                $dataArr2 = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);
                foreach ($dataArr2 as $category_key2 => $r2) {                                  
                    $arrCategoryTree[$r['id']]['childs'][$r1['id']]['childs'][$r2['id']] = array();
                    $arrCategoryTree[$r['id']]['childs'][$r1['id']]['childs'][$r2['id']]['data'] = $r2['name'];
                    $arrCategoryTree[$r['id']]['childs'][$r1['id']]['childs'][$r2['id']]['priority'] = $r1['priority'];
                    $all_sections_id[] = $r2['id'];
                }
            }
        }
        if ( 0 < count($all_sections_id) ) {
            $keyValueArray['sqlclause'] = ' category_id in('.implode(',', $all_sections_id).') and status != -1 group by category_id';
            $stories_counts = $this->db->getDataFromTable($keyValueArray, "content as c", "count(c.id) as count,c.category_id");
            foreach ($arrCategoryTree as $key =>$category_data) {
                if (isset($category_data['childs']) && !empty($category_data['childs'])) {
                    $childs = array();
                    $childs = $category_data['childs'];
                    foreach ($childs as $category_key=>$child) {
                        foreach ($stories_counts as $story_count) {
                            if ($story_count['category_id']==$category_key) {
                                $arrCategoryTree[$key]['childs'][$category_key]['story_count'] = $story_count['count'];
                            }
                        }
                    }
                } else {
                    foreach ($stories_counts as $story_count) {
                        if ($story_count['category_id']==$key) {
                            $arrCategoryTree[$key]['story_count'] = $story_count['count'];
                        }
                    }
                }
            }
        }
        return $arrCategoryTree;
    }

    public function getParentCategory() {
        $arrCategoryTree = array();
        $arrWhere = array();
        $arrWhere['status'] = 1;
        $arrWhere['parentid'] = 0;
        $orderby = 'id';
        $limit = '';
        $dataArr = $this -> db -> getDataFromTable($arrWhere, $this -> tableName, "id, name, parentid", $orderby, $limit);
        foreach ($dataArr as $r) {
            $arrCategoryTree[$r['id']] = $r['name'];
        }
        return $arrCategoryTree;
    }
      
    public function getCategoryDdl() {
        $arrData = $this->getCategoryTree();
        $strReturn = '<select id="parentsectionname" name="parentid" class="select"><option value="">--SELECT PARENT ID--</option>';
        foreach ($arrData as $id => $section) { 
            $strReturn .= '<option value="'.$id.'">'.$section.'</option>';
        }
        $strReturn .= '</select>';
        return $strReturn;
    }
	  
    public function updateCategoryName($id,$category_value) {
        $this -> db -> updateDataIntoTable(array("category_name" => $category_value), array("category_id " => intval($id)), 'content_category_relation',false);
        $this -> db -> updateDataIntoTable(array("category_parentname" => $category_value), array("category_parentid " => intval($id)), 'content_category_relation',false);
    }
}

