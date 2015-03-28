<?php
	/***** INCLUDE CONNECTION FILE **********************************************************************/
	include_once('../config.php');
	$db = Database::Instance() ; 
	$username=$db->db_escape($_POST['val']);
	$authorname=$db->db_escape($_POST['authorname']);
	$by_line=$_POST['by_line'];
	$email=$_POST['email'];
	$id=$_POST['id'];
	$id=intval($id);
	$num=0;
	if ($authorname != '') {
	    $sql="select id from author where name='$authorname' AND status=1 AND by_line in ('".$by_line." , 3')";
	} elseif($email!='') {
		$sql="select id from author where email='$email' AND status=1 ";
	} else {
		$sql="select id from author where username='$username' AND status=1 ";
	}
	if($id != 0) {
		$sql .=" and id !=".$id;
	}
	//echo $sql;
	$db->query($sql);
	if($db->getRowCount()>0)
	$num=$db->getRowCount();
	echo $num;
