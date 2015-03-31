<?php
//INCLUDE CONNECTION FILE
include_once('../config.php');
//DB Connection.
$db = Database::Instance();

$sectionname = $db->db_escape($_POST['val']);
$id = $_POST['id'];
$id = intval($id);
$num = 0;
if ($sectionname != '') {
	$sql="select id from category where name = '$sectionname' AND status=1";
	if ($id != 0) {
	    $sql .=" and id !=".$id;
    }
}	
//echo $sql;
$db->query($sql);
if ($db->getRowCount() > 0) {
   $num = $db->getRowCount();
}
echo $num;		
