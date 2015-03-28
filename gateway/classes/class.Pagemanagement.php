<?php
class Pagemanagement {
	protected $finalData = array();
	private $db;
	private $tableName;
	/********************* START OF CONSTRUCTOR *******************************/
	public function __construct() {
		$this -> tableName = 'pagemaster';
		$this -> db = Database::Instance();
	}

	public function getPagemaster($arrWhere = '') {
		$arrSectionTree = array();
		$arrWhere = $arrWhere;
		//$arrWhere['status'] = 1;
		//$arrWhere['parentid'] = 0;

		$orderby = 'name';
		$limit = '';
		$dataArr = $this -> db -> getDataFromTable($arrWhere,  $this -> tableName, "id, name", $orderby, $limit);

		foreach ($dataArr as $r) {
			$arrSectionTree[$r['id']] = $r['name'];
		}

		return $arrSectionTree;
	}
	
	public function getBlocksByPage($pageids) {
		$sql = 'select t1.id as page_id,t1.name as page_name,t2.id as block_id, t2.name as block_name from pagemaster t1 left join blocks t2 on t1.id=t2.page_id';
		if(!empty($pageids)) { 
			$sql .= ' where t1.id in ('.$pageids.')';
		}
		$sql .= ' order by t1.id, t2.id';
		$this->db->query($sql);
		return $this->db->getResultSet();
	}
	
	public function insertPagemaster($values) {
		return $this -> db -> insertDataIntoTable($values,  $this -> tableName);
	}// eof insertTable
}