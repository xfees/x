<?php
error_reporting(0);
/***** INCLUDE CONNECTION FILE ************************************************************************************/
include_once('../config.php');
$db = Database::Instance() ;
$query="SELECT id,name FROM `section`";
$db->query($query);
if($db->getRowCount()>0)
$result_data=$db->getResultSet();
foreach($result_data as $val)
{ //print_r($val);
	 $db -> updateDataIntoTable(array("section_name" => $val['name']), array("section_id " => intval($val['id'])), 'content_section_relation',true);
	 echo "<br>";
	  $db -> updateDataIntoTable(array("section_parentname" => $val['name']), array("section_parentid " => intval($val['id'])), 'content_section_relation',true);
	  echo "<br><br>";
}
?>
