<?php
class Author {
	protected $finalData = array();
	public $db;
	private $tableName;
	/********************* START OF CONSTRUCTOR *******************************/
	public function __construct() {
		$this -> tableName = 'author';
		$this -> db = Database::Instance();
	}
	/**************************** END OF CONSTRUCTOR **************************/

	public function getListingData($search, $offset, $recperpage, $searchData, $status = '', $sortBy = '', $sortSeq = 'DESC') {
		$keyValueArray = array();
		if ($status == '-1') {
			$keyValueArray['status'] = -1;
		} else {
			$keyValueArray['notequal'] = "status != -1";
		}

		foreach ($searchData as $key => $val) {
			if ($key != 'email' && $key != 'search' && $key != 'name') {
				$keyValueArray[$key] = $val;
			}
		}
		$keyValueArray['sqlclause']= '';
		if (array_key_exists('email', $searchData)) {
			$keyValueArray['sqlclause'] .= "  email like '%".$searchData['email']."%'";
		}
		if (array_key_exists('name', $searchData)) {
			if($keyValueArray['sqlclause'] == ''){
				$keyValueArray['sqlclause'] .= "  name like '%".$searchData['name']."%'";
			}else{
				$keyValueArray['sqlclause'] .= " AND  name like '%".$searchData['name']."%'";
			}
		}
		if ($search == 'byname') {
			$keyValueArray['sqlclause'] .= " name like '%".$searchData['search']."%' ";
		} elseif ($search == 'integer') {
			$keyValueArray['sqlclause'].= " substring(name,1,1) between '0' AND '9' ";
		}
		if($keyValueArray['sqlclause'] == '') {
			unset($keyValueArray['sqlclause']);
		}
		// Author User type Data
		if (RIGHTS == 2) {
			$keyValueArray['id'] = $_SESSION['ITUser']['ID'];
		}
		
		$sort = ($sortBy != '' && $sortBy != 'cnt') ? $sortBy : 'lastvisit';
		$sort .= ' '.$sortSeq;
		
		$limit = $offset . "," . $recperpage;
		$dataArr = $this -> db -> getDataFromTable($keyValueArray, $this -> tableName, "id,name,username,email,status,thumbnail,rights,lastvisit,by_line", $sort, $limit);
		if (count($dataArr) > 0) {
			$finalData['rowcount'] = count($dataArr);
			$i = 0;
			for ($p = 0; $p < $finalData['rowcount']; $p++) {
				$query='SELECT COUNT( id ) as cnt
				FROM `content`
				WHERE author_id ='. $dataArr[$p]['id'];
				$this -> db->query($query);
				if( $this -> db->getRowCount() > 0) {
				    $story_data= $this -> db->fetch();
				    $dataArr[$p]['story_count']=$story_data['cnt'];
				}
				$this -> finalData[] = $dataArr[$p];
			}
		}
		
		if($sortBy == 'cnt'){
			$b = array(); $c = array();
			foreach($this -> finalData as $k=>$v) {
				$b[$k] = intval($v['story_count']);
			}
			if($sortSeq == 'ASC') { asort($b); } else { arsort($b); }
			foreach($b as $k=>$v) {
				$c[] = $this -> finalData[$k];
			}
			$this -> finalData = $c;
		}
		return $this -> finalData;
	}// eof getListingData

	public function getPagination($search, $searchData, $status) {// $status=1 for display listing status=-1 for trashcan
		$keyValueArray = array();
		$keyValueArray['status'] = $status;
		
		foreach ($searchData as $key => $val) {
			if ($key != 'email' && $key != 'search' && $key != 'name') {
				$keyValueArray[$key] = $val;
			}
		}
		if (array_key_exists('email', $searchData)) {
			$keyValueArray['sqlclause'] = " email like '%".$searchData['email']."%'";
		}
		if (array_key_exists('name', $searchData)) {
			$keyValueArray['sqlclause'] = "name like '%".$searchData['name']."%'";
		}
		if ($search == 'byname') {
			$keyValueArray['sqlclause'] = "name like '%".$searchData['search']."%'";
		} elseif ($search == 'integer') {
			$keyValueArray['sqlclause'] = "substring(name,1,1) between '0' and '9'";
		}
		if (RIGHTS == 2) {
			$keyValueArray['id'] = $_SESSION['ITUser']['ID'];
		}
		$dataArr = $this -> db -> getDataFromTable($keyValueArray, $this -> tableName, "count(id) as cnt");
		$sql_count = $dataArr[0]['cnt'];
		return $sql_count;
	}// eof getPaginationQuery

	
	public function getEditData($id) {
	    global $sizearrayPlugin;
		if (intval($id)) {
			$keyValueArray = array();
			$keyValueArray['id'] = intval($id);
			$dataArr = $this->db->getDataFromTable($keyValueArray, $this->tableName, "*"); //print_r($dataArr);
			$dataArr[0]['oldthumbnail'] = $dataArr[0]['thumbnail'];
			if(count($dataArr)) {
				$json = json_encode($dataArr);
			}
		}
		return $json;
	}

	public function insertTable($values) {
		return $this -> db -> insertDataIntoTable($values, $this -> tableName);
	}// eof insertTable

	public function updateTable($values, $whereArr) {
		return $this -> db -> updateDataIntoTable($values, $whereArr, $this -> tableName);
	}// eof updatetable

	public function toggleTableStatus($val, $status) {
		$rowCount = 0;
		if (intval($val) > 0) {
			$rowCount = $this -> db -> updateDataIntoTable(array("status" => $status), array("id" => intval($val)), $this -> tableName);
		}
		return $rowCount;
	}// eof toggleStatus

	public function getAuthors($id = '') {
		$whereData = array('status' => 1);
		// Author User type Data
		if ($id != '') {
			$whereData['id'] = $id;
		}
		return $this -> db -> getDataFromTable($whereData, $this -> tableName, 'id,name', 'name');
	}

	public function getAuthorsbyType($type) {
		$whereData = array('sqlclause' => 'by_line IN ('.$type.')');
		$whereData = array('status' => 1);
		// Author User type Data
		if ($id != '') {
			$whereData['id'] = $id;
		}
		return $this -> db -> getDataFromTable($whereData, $this -> tableName, 'id,name', 'name');
	}

	public function getAuthor($id) {
		return $this -> db -> getDataFromTable(array('id' => $id), $this -> tableName, 'id,name');
	}

	public function getStoryListing($param){	
	
	}
}
?>
