<?php
class MongoModel
{

	private $__conn;
	private $__coll;
	private $__db;
	
	private static $__connection = array();
	private static $__dbs = array();
	private static $__collection = array();

	public function __construct($collection) {
		$this->__conn = MONGO_SERVER_IP;
		$this->__db = MONGO_DB;
		$this->__coll = $collection;
		$this->__load();
	}
        
	private function __load() {
	    try {
	        if (!isset(self::$__connection[$this->__conn])) {
		        self::$__connection[$this->__conn] = new \Mongo($this->__conn);                
	        }
	        if ( !isset(self::$__dbs[$this->__db])) {
		        self::$__dbs[$this->__db] = self::$__connection[$this->__conn]->selectDB($this->__db);
	        }
	        if ( !isset(self::$__connection[$this->__coll])) {
		        self::$__collection[$this->__coll] = self::$__dbs[$this->__db]->selectCollection($this->__coll);
	        }
	    }
	    catch (Exception $e) {
	        //print $e->getMessage();
	    }
	}

	public function insert($f) {
        $r_flag = false;
        if (is_object(self::$__collection[$this->__coll])) {
            try {
                self::$__collection[$this->__coll]->insert($f);
                $r_flag = true;
            } catch (Exception $e) {
                $r_flag = false;
            }	
        }      
        return $r_flag;
	}

	public function get($params) {
        $query = isset($params['query']) ? $params['query'] : array(); 
        $limit = ( isset($params['limit']) &&  is_int($params['limit']) ) ? $params['limit'] : 0; 
        $skip = ( isset($params['skip']) &&  is_int($params['skip']) ) ? $params['skip'] : 0; 
        $sort = isset($params['sort']) ? $params['sort'] : array();
        $cursor = self::$__collection[$this->__coll]->find($query)->limit($limit)->skip($skip)->sort($sort);

        $k = array();
        $i = 0;

        while( $cursor->hasNext()) {
	        $k[$i] = $cursor->getNext();
		    $i++;
        }
        return $k;
	}

	public function getOne($params) {
		$query = isset($params['query']) ? $params['query'] : array();             
		$fields = isset($params['fields']) ? $params['fields'] : array();
		return self::$__collection[$this->__coll]->findOne($query, $fields);
	}
	
	public function count($query = array()) {
		return self::$__collection[$this->__coll]->count($query);
	}

	public function update($f1, $f2) {
		self::$__collection[$this->__coll]->update($f1, $f2);
	}

	public function getAll() {
		$cursor = self::$__collection[$this->__coll]->find();
		foreach ($cursor as $id => $value)
		{
			echo "$id: ";
			var_dump( $value );
		}
	}

	public function delete($f, $one = FALSE) {
		$c = self::$__collection[$this->__coll]->remove($f, $one);
		return $c;
	}

	public function ensureIndex($args) {
		return self::$__collection[$this->__coll]->ensureIndex($args);
	}
}

