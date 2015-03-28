<?php
class AdminLog {
    public function __construct() {

    }
    public function insert($data) {
        if (count($data) > 0) {
          
        }
    }
    public function getLogs($params) {
        $db_type = isset($params['db_type']) ? $params['db_type'] : 'mongodb';
        $result_type = isset($params['result_type']) ? $params['result_type'] : 'count';
        $db_name = isset($params['db_name']) ? $params['db_name'] : 'mongodb';
        switch ($db_type) {
            case 'mongodb':
                $object_mongodb = new MongoModel(MONGO_COLL_ADMIN_LOG);
                $mongo_query = array();
                $mongo_query['db_name'] = $db_name;
                if ( isset($params['author_id']) && !empty($params['author_id']) )
                {
                  $mongo_query['author_id'] = $params['author_id'];
                }
	            if ( isset($params['action']) && !empty($params['action']) )
                {
                  $mongo_query['action'] = $params['action'];
                }
                if ( isset($params['module_id']) && !empty($params['module_id']) )
                {
                    $db = Database::Instance();
                    $params['module_id'] = addslashes($params['module_id']);
                    $sql = "SELECT name FROM cms_modules where id='{$params['module_id']}' ";
                    $db->query($sql);
                    if ( $db->getRowCount() > 0 )
                    {
                    $result_module = $db->fetch();
                    $mongo_query['module_name'] = strtolower($result_module['name']);
                    }
                }

                if ( isset($params['insertdate']) && !empty($params['insertdate']) )
                {
                    $regex = '/^' . $params['insertdate'] . '/';
                    $mongo_query['insertdate'] = new MongoRegex($regex);
                }
			
	            if ( isset($params['record_id']) && !empty($params['record_id']) )
                {
                    $mongo_query['record_id'] = (int) $params['record_id'];
                }
                /*
                if ( isset($params['search']) && $params['search'] == 'byname' )
                {
                  $regex = '/^' . strtolower($params['search_data']) . '/';
                  $mongo_query['module_name'] = new MongoRegex($regex);
                }
                else if ( isset($params['search']) && $params['search'] == 'integer' )
                {
                  $regex = '/^[0-9]/';
                  $mongo_query['module_name'] = new MongoRegex($regex);
                }
	            */
                switch ( $result_type ) {
                    case 'count':
                        return $object_mongodb->count($mongo_query);
                        break;
                    case 'records':
                        $mongo_params = array();
                        $mongo_params['limit'] = (int) $params['recperpage'];
                        $mongo_params['skip'] = (int) ( $params['offset'] == 0 ) ? 0 : $params['offset'];
                        $mongo_params['query'] = $mongo_query;
                        $mongo_params['sort'] = array('insertdate' => -1);
                        return $object_mongodb->get($mongo_params);
                        break;
                }
                break;
            case 'db':
                break;
        }
    }
}

