<?php
error_reporting(1);
/* * *** INCLUDE CONNECTION FILE ********************************************************************* */
include_once('config.php');
$action		= $_POST['action'];
$queryval   = $_POST['queryInput'];
if($action	== 'querry' && $queryval != ''){
	$db = Database::Instance() ;
	$strQry = $queryval;
	echo "Querry : ".$strQry."<br/> Output : ";
	echo $db->query($strQry);
	$pos = strpos($strQry, 'update');
	$pos1 = strpos($strQry, 'insert');
	if ($pos === false) {
		if ($pos1 === false) {
			$output = $db->getResultSet();
		}
		else
		{ 
			$output="Insert Id:".$db->getInsertedAutoId();
		}
	} else {
		$output="Effected rows :".$db->getAffectedRowCount();
	}
	
	echo '<pre> ';print_r($output); echo '</pre>';
	exit;
}
?>
<script>
function saveBlock(){
    var queryInput = jQuery.trim($("#queryInput").val());
    if(queryInput=="") {
		alert('Enter Some query');  
		$('#queryInput').focus();
    	return false;
    }
    
    $.ajax({
      url:'fireQuerry.php',
      data:{action:'querry', queryInput:queryInput},
      type:'post',
      success: function(res) { // alert(res);
		    $("#errormsg").show();
			$('#queryInput').val('');
	  		$('#errormsg').html(res);
      }
    });
}
</script>
<?php include_once 'incHeaderScript.php'; ?>   

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Query Execution Form</title>
<style type="text/css">
.boxtxt{ width:98%; padding:10px; margin:5px 0; border:1px solid #ccc;}p{margin:0; padding:0;}.error1{ background-color: #FF9;margin:5px 0; width:90%; display:block; padding:5px; font:nomal 12px Arial, Helvetica, sans-serif;color:#f00;border:1px solid #F00;}
</style>
</head>
<body>
<div style="clear:both;"></div>
<div style="width:700px;margin:40px auto;">
<form id="form1" name="form1" method="post" action="">
<h3>Query Execution </h3>
<p><textarea name="queryInput" id="queryInput" rows="3" cols="20" style="height:90px" class="textarea"></textarea></p>
<p style="padding-top:10px;" align="center"><img onclick="cancelBlock()" src="<?php echo IMAGEPATH; ?>/btn-cancel.gif" />&nbsp;&nbsp;<img id="savebutton" src="<?php echo IMAGEPATH; ?>/btn-save.gif" onclick="saveBlock()" /></p>
<div class="error1" id="errormsg" style="display:none;" >error msg</div>
</form>
</div>
</body>
</html>
