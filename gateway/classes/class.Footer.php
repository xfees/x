<?php
class Footer {
	protected $finalData = array();
	private $db;
	private $tableName;
	/********************* START OF CONSTRUCTOR *******************************/
	public function __construct() {
		$this -> tableName = 'footer';
		$this -> db = Database::Instance();
	}

	public function insertTable($values) {
		return $this -> db -> insertDataIntoTable($values,  $this -> tableName);
	}// eof insertTable
	
	public function updateTable($values, $where) {
		return $this -> db -> updateDataIntoTable($values, $where, $this -> tableName);
	}// eof updateTable
	
	public function toggleStatus($where, $status) {
		$sql = "UPDATE footer SET status = ".$status." WHERE id =".$where." OR block_id =".$where;
		$this -> db -> query($sql);
		return $this->db->getAffectedRowCount();
	}// eof deleteTable
        
        public function deleteBlock($conditionsArray) {
            $this->db->deleteDataFromTable($conditionsArray ,$this -> tableName);
        }// eof deleteBlock
}
?>