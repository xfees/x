<?php
class Login {
	protected $finalData = array();
	private $db;
	private $tableName;
	/********************* START OF CONSTRUCTOR *******************************/
	public function __construct(){
		$this->tableName = 'author';
		$this->db = Database::Instance() ; 
	}
	/**************************** END OF CONSTRUCTOR **************************/
	
	public function insertLog($values) {
	  return $this->db->insertDataIntoTable($values, "cmslog");
	}// eof insertTable
	
	public function checkLogin($user, $pswd) {

	  $arrWhere = array();
	  $arrWhere['username'] = $this->db->db_escape($user);
	  $arrWhere['password'] = $this->db->db_escape($pswd);
	  $arrWhere['status'] = 1;
	  
	  $rows = $this->db->getDataFromTable($arrWhere, $this->tableName, '*', '', '', false);
	  return $rows;
	}
	
	public function getUserByUsername($user) {
	
	  $arrWhere = array();
	  $arrWhere['sqlclause'] = "(username = '$user' OR email = '$user') ";
	  $arrWhere['status'] = '1'; 
	  $rows = $this->db->getDataFromTable($arrWhere, $this->tableName, 'id,email,username,name,status','id desc','1');
	  return $rows;
	}
	
	public function getUserById($id) {
	
	  $arrWhere = array();
	  $arrWhere['id'] = $id;
	
	  $rows = $this->db->getDataFromTable($arrWhere,$this->tableName, 'id,email,username,name,status');
	  return $rows;
	}
	
	public function getUser($id) {
	
	  $arrWhere = array();
	  $arrWhere['id'] = $id;
	
	  $rows = $this->db->getDataFromTable($arrWhere,$this->tableName, '*');
	  return $rows;
	}
		
	public function updateTable($values, $whereArr){
	    return $this->db->updateDataIntoTable($values, $whereArr, $this->tableName);
	}// eof updatetable		
}
?>