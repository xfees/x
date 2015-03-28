<?php
class Database{
    private static $instance = array();
    private $connection;
    private $ConnectionIdentifier;
    private $result;
    private $row;
    private $sql;
    private $error;

    private function __construct($index) {
        global $dbdetails ;
        $this->connection = new mysqli($dbdetails[$index]['host'], $dbdetails[$index]['user'], $dbdetails[$index]['password'], $dbdetails[$index]['database']);
        $this->ConnectionIdentifier = $index ;
    }

    private function __clone() {
        $this->connection->close();
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __toString() {
        $this->connection->close();
        trigger_error('Print is not allowed.', E_USER_ERROR);
    }

    public static function Instance($index = 'x') {
        if(!isset(self::$instance[$index])) {
            self::$instance[$index] = new Database($index);
        }
        return self::$instance[$index];
    }

    public function trackLog($sql, $log_id,$query_status, $action=''){ //echo 'menuuuuuu'.$_SESSION['TOPMENU'];
	    $db_flag = true;
	    $log = array();
	    $log['author_id'] = $_SESSION['ITUser']['ID'];
	    $log['name'] = $_SESSION['ITUser']['USERNAME'];
	    $log['module_name'] = $_SESSION['TOPMENU'];
	    $log['ip'] = getIP();
	    $log['url'] = $_SERVER['REQUEST_URI'];
	    $log['insertdate'] = date('Y-m-d H:i:s');
	    $log['query_that_is_executed'] = addslashes(trim($sql));
	    $log['db_name'] = $this->ConnectionIdentifier;
	    $log['record_id'] = (int) $log_id;
	    $log['query_status'] = $query_status;
	    if ($action == '') {
		    $log['action'] = $_POST['action'];
	    } else {
		    $log['action'] = $action;
	    }
	    if (extension_loaded('mongo')) {
		    $object_admin_log = new MongoModel(MONGO_COLL_ADMIN_LOG);
		    if ( $object_admin_log->insert($log) ){
			    $db_flag = false;
		    }
	    }
	    if ($db_flag) {
		    $sqlTracklog = 'INSERT into admin_action_log (author_id,name,module_name,ip,url,insertdate,query_that_is_executed, record_id, query_status,action) values ("'.$_SESSION["ITUser"]["ID"].'","'.$_SESSION['ITUser']['USERNAME'].'","'.$_SESSION['TOPMENU'].'","'.getIP().'","'.$_SERVER["REQUEST_URI"].'","'.date('Y-m-d H:i:s').'","'.$log['query_that_is_executed'].'", "'.$log['record_id'].'", "'.$log['query_status'].'","'.$_POST['action'].'")';
		    $res = $this->connection->query($sqlTracklog);
	    }
    }

    public function query($sql, $debug = 0, $ismongo = '') {
	    if(is_object($this->result)){
		    $this->result->close();
	    }
	    $this->sql = $sql;
	    if($debug == 1 || $_GET["debug"] == 1){
		    echo $sql;
	    }
	    if($this->result = $this->connection->query($this->sql)) {
		    $return = true;
	    } else {
		    $this->error = $this->connection->error ;
		    if($debug == 1 || $_GET["debug"] == 1) {
			    print "failed query";
			    print $this->error ;
		    }
		    $return = false;
	    }
	    return $return;
    }

    public function getRowCount() {
        return $this->result->num_rows ;
    }

    public function getInsertedAutoId() {
        return $this->connection->insert_id ;
    }

    public function getAffectedRowCount() {
        return $this->connection->affected_rows;
    }

    public function fetch() {
        return $this->result->fetch_array(MYSQLI_ASSOC);
    }

    public function getResultSet() {
        $resultSet = array();
        while($row = $this->result->fetch_array(MYSQLI_ASSOC)) {
            $resultSet[] = $row;
        }
        return $resultSet;
    }

    public function insertDataIntoTable($keyValueArray, $table, $debug=false) {
        $countTableData = count($keyValueArray);    
        $sql = "INSERT INTO `{$table}` SET ";
        $i=0;
        foreach($keyValueArray as $key=>$val) {
            $i++;
            $sql .= $key . "='" . $this->db_escape($val) . "'";
            if($countTableData != $i) {
                $sql .= ", ";
            }
        }
        $res=$this->query($sql, $debug, '', $log_id);
        $insertID=$this->getInsertedAutoId();
        $log_id = $insertID;

        switch($table) {
            case 'content_metadata':
            case 'content_section_relation':
            case 'media':
            case 'content_counts':
		            $log_id = $keyValueArray['content_id'];
		            break;
        }
        $this->trackLog($sql, $log_id, $res);
        return $insertID;
    }

    public function insertDataMultiIntoTable($keyValueArray, $table, $fields, $debug=false) {
        $countrows = count($keyValueArray);
        $sql = "INSERT INTO `{$table}` {$fields} VALUES";
        $j=1;   
        foreach($keyValueArray as $item) {
            $countTableData = count($item);   
            $i=0;
            foreach($item as $key=>$val) {
                if($i==0) {
                    $sql =$sql." (";
                }
                $sql .= "'" . $this->db_escape($val) . "'";
                if($countTableData > ($i+1)) {
                    $sql .= ", ";
                } else {
                    $sql .= ") ";
                }
                $i++;
            }
            if($countrows > $j) {
                $sql .= ", ";
            }
            $j++;
        }//echo $sql;die;
        $res = $this->query($sql, $debug, '', $log_id);
        $insertID = $this->getInsertedAutoId();
        return $insertID;
    }

    public function updateDataIntoTable($keyValueArray, $whereClauseKeyValArray, $table, $debug = false) {
        $countTableData = count($keyValueArray);
        $sql = "UPDATE `{$table}` SET ";
        $i=0;
        $w=0;
        $log_id = 0;
        foreach ($keyValueArray as $key=>$val) {
            $i++;
            if($key == 'countupdate') {
                $sql .= $this->db_escape($val);
                break;
            } else {
                $sql .= $key."='" . $this->db_escape($val) . "'";
                if($countTableData != $i) {
                    $sql .=", ";
                }
            }
        }
        $countWhereClauseData = count($whereClauseKeyValArray);
        if($countWhereClauseData > 0) {
            $sql .=" where ";
            foreach($whereClauseKeyValArray as $key=>$val) {
                $w++;
                $sql .= $key."='" . $this->db_escape($val) . "'";
                if($countWhereClauseData != $w) {
                    $sql .=" and ";
                }
            }
        }
        //echo $sql;
        $res = $this->query($sql, $debug, '');
        $rowCount = $this->getAffectedRowCount();
        switch($table) {
            case 'media':
	            $log_id = $keyValueArray['content_id'];
	            break;
        }
        $action = '';
        if($_POST['action'] == 'p' && $keyValueArray['status'] == '0'){
            $action = 'u';
        }
        $log_id = (($log_id != 0)?$log_id:(isset($whereClauseKeyValArray['id']) ? $whereClauseKeyValArray['id'] : $whereClauseKeyValArray['content_id']));
        $this->trackLog($sql, $log_id, $res, $action);
        return $rowCount;
    }

    public function getDataFromTable($keyValueArray, $table, $fields = '*', $orderBy = "", $limit = "", $debug = false) {
        $posts = array();
        $countTableData = count($keyValueArray);
        $sql = "SELECT $fields FROM $table";
        $i=0;
        foreach ($keyValueArray as $key => $val) {
            $i++;
            if ($i == 1) {
                $sql .=" where ";
            }
            if ($key == 'sqlclause' || $key == 'notequal') {
                $sql .= $val;
            } else {
                $sql .= $key."='".$val."'";
            }
            if ($countTableData != $i) {
                $sql .=" and ";
            }
        }
        if ($orderBy!="") {
          $sql .=" order by ".$orderBy;
        }
        if ($limit!="") {
            $sql .=" limit ".$limit;
        }
        if ($debug) {
            echo $sql;
        }
        $this->query($sql);
        while ($row  = $this->fetch()) {
            array_push($posts, $row);
        }
        return $posts;
    }

    public function deleteDataFromTable($whereClauseKeyValArray, $table, $debug = false){
        // $countTableData = count($keyValueArray);
        $sql = "DELETE FROM `{$table}` ";    
        $w=0;
        $countWhereClauseData = count($whereClauseKeyValArray);
        if($countWhereClauseData > 0){
          $sql .=" where ";
          foreach ($whereClauseKeyValArray as $key=>$val) {
            $w++;
            $sql .= $key."='".$val."'";
            if ($countWhereClauseData != $w) {
              $sql .=" and ";
            }
          }
        }
        if ($debug == true) {
            echo $sql;
        }
        $res = $this->query($sql);
        $rowCount = $this->getAffectedRowCount();
        $log_id = (isset($whereClauseKeyValArray['id']) ? $whereClauseKeyValArray['id'] : $whereClauseKeyValArray['content_id']);
        $this->trackLog($sql, $log_id, $res);
        return $rowCount;
    }

    public function get_resultset($sql) {
        if ($result = $this->connection->query($sql)) {
            return $result;
        } else {
            $this->error = $this->connection->error;
            return false;
        }
    }


    public function __destruct() {
        if(is_object($this->result)) {
            $this->result->close();
        }
        $this->connection->close();
    }

    public function db_escape($string) {
        return $this->connection->real_escape_string(trim($string));
    }
}

