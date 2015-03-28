<?php
error_reporting(0);
//*********************************************************** Include Configuration Files
include_once ('../config.php');
foreach($_POST as $key=>$val) {
	$postData[$key] = trim(strip_tags($val));
}

switch ($postData['action']) {
	case 'saveblock':
		$obj = new Footer();
		$values['headline'] = $postData['block_name'];
		$values['headline_link'] = $postData['block_link'];
		$values['status'] = 1;
      	$values['link_type'] = 1;
      	$values['priority'] = $postData['priority'];
		$values['insertdate'] = date("Y-m-d H:i:s");
		$values['updatedate'] = date("Y-m-d H:i:s");
      	$insertStatus = $obj->insertTable($values);
		$values['insertId'] = $insertStatus;
		echo json_encode($values); 	
	break;
        case 'saveChildblock':
		$obj = new Footer();
		$values['headline'] = $postData['block_name'];
		$values['headline_link'] = $postData['block_link'];
		$values['status'] = 1;
          	$values['link_type'] = $postData['trending']==1?2:3;
          	$values['block_id'] = $postData['headline_id'];
		$values['insertdate'] = date("Y-m-d H:i:s");
		$values['updatedate'] = date("Y-m-d H:i:s");
          	$insertStatus = $obj->insertTable($values);
		$values['insertId'] = $insertStatus;
		echo json_encode($values); 	
	break;
	case 'addSection':
		$obj = new Pagemanagement();
		$values['name'] = $postData['section_name'];
		$values['insertdate'] = date("Y-m-d H:i:s");
		$values['updatedate'] = date("Y-m-d H:i:s");
		$insertStatus = $obj->insertPagemaster($values);
		$values['insertId'] = $insertStatus;
		echo json_encode($values); 	
	break;
	case 'toggleStatus':
		$obj = new Footer();
		$res = $obj->toggleStatus($postData['block_id'], $postData['status']);
		$ret = array();
		$ret["id"] = $postData['block_id'];
		echo json_encode($ret); 	
		break;
        case 'd':
            $obj = new Footer();
            $conditions['id'] = $postData['id'];
            $obj->deleteBlock($conditions);
	    echo json_encode($ret); 	
            break;    
        case 'getChildBlock':
             $conn = Database::Instance();
             $id = $postData['id'];
             $sql = "SELECT id,headline,headline_link FROM footer WHERE id=$id";
             $conn->query($sql);
             $resultblocksData = $conn->getResultSet();
             echo json_encode($resultblocksData); 
            break;
	case 'editblock':
	  $obj = new Footer();
          $values = array();
          $values['headline'] = $postData['blockname_txt'];
	  $values['headline_link'] = $postData['blockname_url'];
	  $values['priority'] = $postData['block_priority'];
      	  $values['updatedate'] = date('Y-m-d H:i:s');
      
          $where = array();
          $where['id'] = $postData['block_id_txt'];
          $ret = $obj->updateTable($values, $where);
          $res = array();
          $res['headline'] = $postData['blockname_txt'];
	  $res['headline_link'] = $postData['blockname_url'];
	  $res['id'] = $where['id'];      
          echo json_encode($res);
	break;
    case 'editChildblock':
	  $obj = new Footer();
          $values = array();
          $values['headline'] = $postData['Childblockname_txt'];
	  $values['headline_link'] = $postData['Childblockname_url'];
          $where = array();
          $where['id'] = $postData['Childblock_id_txt'];
      	  $values['updatedate'] = date('Y-m-d H:i:s');
          $ret = $obj->updateTable($values, $where);
          $res = array();
          $res['headline'] = $postData['Childblockname_txt'];
	  $res['headline_link'] = $postData['Childblockname_url'];
	  $res['id'] = $where['id'];      
          echo json_encode($res);
	break;
	default:
		echo "Nothing to do";
	break;
}
