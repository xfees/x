<?php
include_once("inc/constants.php");
//testing all constants 
function returnConstants ($prefix) {
    foreach (get_defined_constants() as $key=>$value)
        if (substr($key,0,strlen($prefix))==$prefix)  $dump[$key] = $value;
    if(empty($dump)) { return "Error: No Constants found with prefix '".$prefix."'"; }
    else { return $dump; }
}

$consVarArray = get_defined_constants(true);
$consVariablesArray = $consVarArray['user'];

if(sizeof($consVariablesArray)>0)
{
	$i=0;
	foreach($consVariablesArray as $conVarName => $conVarValue)
	{
		$i++;
		$css = ($i % 2) ? 'background-color:#f2f2f2; border-bottom:1px solid #c3c3c3' : 'background-color:#fff; border-bottom:1px solid #c3c3c3';
		
		echo '<div style="padding:5px;'.$css.'"><div style="float:left; width:40%"><b>'. $conVarName . '</b> </div><div style="float:left; width:60%;">'.$conVarValue.'</div><div style="clear:both"></div></div>' ;
	}
	//	print_r($consVariablesArray);
}
else
	echo 'No defined variables found.';
	
?>