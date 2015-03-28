<div class="hideLeftMenu blackButton" onclick="showHideLeftPanel()"><span> &laquo;</span> Hide Menu</div>
<?php
if(isset($_COOKIE['TOPMENU']))	{
	$leftmenufilename=$filename;
	if(substr_count($leftmenufilename,"trashcan"))
	{
		$leftmenufilename=substr($leftmenufilename,0,-8);
	}
	$result_cokkie=new query();
	$sql_cokkie="SELECT id,name,headingname,displayname,module_pid FROM cms_modules WHERE name='".$leftmenufilename."'";
	$result_cokkie->myquery($sql_cokkie,$m_mysql->conn);
	$result_cokkie->myfetch();
	$modeleParentId=$result_cokkie->row['module_pid'];
	$mainModuleName=$leftmenufilename;
	if($modeleParentId == 0){
		$displayParentName=$result_cokkie->row['displayname'];
		$parentFolderName=$leftmenufilename;
		$modeleParentId=$result_cokkie->row['id'];
	} else{
		$result_parent=new query();
		$sql_parent="SELECT name,displayname FROM cms_modules WHERE id=".$modeleParentId;
		$result_parent->myquery($sql_parent,$m_mysql->conn);
		$result_parent->myfetch();
		$displayParentName=$result_parent->row['displayname'];
		$parentFolderName=strtolower($result_parent->row['name']);
	}
	
	if($leftmenufilename == $mainModuleName)
	{			
		if(stristr($_SERVER['REQUEST_URI'], '/'.$parentFolderName.'/display.php') == TRUE || stristr($_SERVER['REQUEST_URI'], '/'.$parentFolderName.'/displaytrashcan.php') == TRUE)
		{
			echo '<a href="'.CMSNAVIGATEPATH."/".$parentFolderName.'/display.php" class="selected">&nbsp;&nbsp;'.$displayParentName.'</a>';
		}
		else
		{
			echo '<a href="'.CMSNAVIGATEPATH."/".$parentFolderName.'/display.php">&nbsp;&nbsp;'.$displayParentName.'</a>';
		}

		$result_letfdata=new query();
		$sql_left="SELECT id,name,headingname,displayname FROM cms_modules WHERE module_pid = $modeleParentId ORDER BY display_order ";
		$result_letfdata->myquery($sql_left,$m_mysql->conn);
		while($result_letfdata->myfetch())
		{
			$foldername=strtolower($result_letfdata->row['name']);
			$displayName=$result_letfdata->row['displayname'];
			if($foldername=='approvedcomment')
			{
				$foldername = 'comment';
				if(stristr($_SERVER['REQUEST_URI'], '/'.$foldername.'/displayapproved.php') == TRUE) 
				{
					echo '<a href="'.CMSNAVIGATEPATH."/".$foldername.'/displayapproved.php" class="selected">&nbsp;&nbsp;'.$displayName.'</a>';
				}
				else
				{
					echo '<a href="'.CMSNAVIGATEPATH."/".$foldername.'/displayapproved.php">&nbsp;&nbsp;'.$displayName.'</a>';
				}				
				//$foldername = 'comment';
			}else if($parentFolderName=='box'){
					echo (stristr($_SERVER['REQUEST_URI'], '/box/display.php?boxtype='.$foldername)==TRUE) ? '<a href="'.CMSNAVIGATEPATH.'/box/display.php?boxtype='.$foldername.'" class="selected">&nbsp;&nbsp;'.$displayName.'</a>':'<a href="'.CMSNAVIGATEPATH.'/box/display.php?boxtype='.$foldername.'">&nbsp;&nbsp;'.$displayName.'</a>';
					
			}else{
				if(stristr($_SERVER['REQUEST_URI'], '/'.$foldername.'/display.php') == TRUE || stristr($_SERVER['REQUEST_URI'], '/'.$foldername.'/displaytrashcan.php') == TRUE) 
				{
					echo '<a href="'.CMSNAVIGATEPATH."/".$foldername.'/display.php" class="selected">&nbsp;&nbsp;'.$displayName.'</a>';
				}
				else
				{
					echo '<a href="'.CMSNAVIGATEPATH."/".$foldername.'/display.php">&nbsp;&nbsp;'.$displayName.'</a>';
				}
			}//end of else
			
		}	//end of while
		$result_letfdata->myfree();
	}//end of if
}?>
<div class="helplegend">
<div class="leftPanel_tool">Action Legends</div>
	<div class="actions clear nobtmborder">
	<a href="javascript:;" class="edit" title="Edit">Edit</a>
	</div>Edit
	<div class="actions clear nobtmborder">
	<a href="javascript:;" class="delete" title="Delete">Delete</a>
	</div>Delete
	<div class="actions clear nobtmborder">
	<a href="javascript:;" class="preview" title="Preview">Preview Content</a>
	</div>Preview Content
	<div class="actions clear nobtmborder">
	<a href="javascript:;" class="published" title="click to publish ">published</a>
	</div>Published
	<div class="actions clear nobtmborder">
	<a href="javascript:;" class="unPublished" title="click to un-publish">unPublished</a>
	</div>UnPublished
	<div class="actions clear nobtmborder">
	<a href="javascript:;" class="restore" title="click to restore">restore</a>
	</div>Restore
	<div class="actions clear nobtmborder">
	<a href="javascript:;" class="approvedComments" title="Approved Comments">Approved Comments</a>
	</div>Approved Comments
	<div class="actions clear nobtmborder">
	<a href="javascript:;" class="unApprovedComments" title="Unapproved Comments">Unapproved Comments</a>
	</div>Unapproved Comments

</div>
<script type="text/javascript">
/*********** admin panel show hide **********/
function showHideLeftPanel() {
	$(".leftPanel").toggle(function(){
		try{
			if($(".leftPanel").css("display")=='block'){
//				alert("isIE6:"+isIE6+"  "+$(".leftPanel").css("display"));
				$(".leftPanel").css({"display":"table-cell"});
			}
		}catch(e){}
	});
	$("#showme").toggle();
}
$(document).ready(function() {
	//append a td
	//$(".title").after("<div width='2' id='showme' class='showMenuButton' style='display:none' valign='top' onclick='showHideLeftPanel()'><span class='span'><label title='Show Menu'>&raquo;</label></span></div>");
	//$("#showme").css({top:'150px'})
	//showHideLeftPanel();
});

</script>