<?php 
	$className = 'header';
	$classCnt = 'header';
	if($sortBy == 'name') {
	 	if($sortSeq == 'ASC') {
			$className = 'headerSortDown';
		} else if($sortSeq == 'DESC') {
			$className = 'headerSortUp';
		}
	} elseif($sortBy == 'cnt') {
		if($sortSeq == 'ASC') {
			$classCnt = 'headerSortDown';
		} elseif($sortSeq == 'DESC') {
			$classCnt = 'headerSortUp';
		}
	}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablesorter">  
    <thead>
	<tr class="removeheading titlebar">
	  <th width="4%" class="pL">Id</th>
	  <th width="10%" valign="middle" class=""><div style="text-align:right; float:left;"><img class="listSeparator" width="2" height="31" border="0" align="absmiddle" src="<?php echo IMAGEPATH;?>/list-separator.gif"></div>
		<div style="float: left; padding: 8px 0 0 10px;">Image</div></th>
		<th width="10%" valign="middle" class="<?php echo $className; ?>" onclick="sortTable(this, 'name')"><div style="text-align:right; float:left;"><img class="listSeparator" width="2" height="31" border="0" align="absmiddle" src="<?php echo IMAGEPATH;?>/list-separator.gif"></div>
		<div style="float: left; padding: 8px 0 0 10px;">Name</div></th>
		<th width="6%" valign="middle" class=""><div style="text-align:right; float:left;"><img class="listSeparator" width="2" height="31" border="0" align="absmiddle" src="<?php echo IMAGEPATH;?>/list-separator.gif"></div>
		<div style="float: left; padding: 8px 0 0 10px;">Rights</div></th>
		<th width="6%" valign="middle" class=""><div style="text-align:right; float:left;"><img class="listSeparator" width="2" height="31" border="0" align="absmiddle" src="<?php echo IMAGEPATH;?>/list-separator.gif"></div>
		<div style="float: left; padding: 8px 0 0 10px;">Type</div></th>
		<th width="7%" valign="middle" class=""><div style="text-align:right; float:left;"><img class="listSeparator" width="2" height="31" border="0" align="absmiddle" src="<?php echo IMAGEPATH;?>/list-separator.gif"></div>
		<div style="float: left; padding: 8px 0 0 10px;">Email Id</div></th>
        <th width="6%" valign="middle" class=""><div style="text-align:right; float:left;"><img class="listSeparator" width="2" height="31" border="0" align="absmiddle" src="<?php echo IMAGEPATH;?>/list-separator.gif"></div>
		<div style="float: left; padding: 8px 0 0 10px;">Last Login</div></th>
	  <th width="7%" valign="middle" class="<?php echo $classCnt; ?>" onclick="sortTable(this, 'cnt')"><div style="text-align:right; float:left;"><img class="listSeparator" width="2" height="31" border="0" align="absmiddle" src="<?php echo IMAGEPATH;?>/list-separator.gif"></div>
		<div style="float: left; padding: 8px 0 0 10px;">Story Count</div></th>
      <th width="12%" valign="middle" class=""><div style="text-align:right; float:left;"><img class="listSeparator" width="2" height="31" border="0" align="absmiddle" src="<?php echo IMAGEPATH;?>/list-separator.gif"></div>
		<div style="float: left; padding: 8px 0 0 10px;">Actions</div></th>
    </tr>
	</thead>
	<tbody>
	<tr class="removeheading">
      <td class="pL pTB" id="notification" colspan="5"></td>
    </tr>
	<?php
	if(!empty($result_data))
			{
				foreach($result_data as $key=>$val){
					
					if($flag == 'a'  || isset($search)){			//----------If action was add then add a new div 
	?>
	<tr id="singleCont<?php echo $val['id'];?>" class="listing" onmouseover="javascript:this.className='alternate'" onmouseout="javascript:this.className='listing'">
<?php } ?>
            <td class="pL pTB grayText"><a href="<?php echo $modulePath; ?>/form.php?id=<?php echo $val['id'];?>&action=m"><?php echo $val['id']; ?></a></td>
<?php if($val['thumbnail'] == ''){
?>
      <td class="pL"><img src="<?php echo IMAGEPATH;?>/notFound_75x75.gif" alt="<?php echo $val['name'];?>" height="75" width="75" /></td>
<?php
}else{
$widthval=$sizearrayPlugin[$_SESSION['TOPMENU']]['thumbnail'][width][count($sizearrayPlugin[$_SESSION['TOPMENU']]['thumbnail'][width])-2];
$heigthval=$sizearrayPlugin[$_SESSION['TOPMENU']]['thumbnail'][height][count($sizearrayPlugin[$_SESSION['TOPMENU']]['thumbnail'][width])-2];
$sizeval=$widthval.'x'.$heigthval;
$thumbnailval=getthumbnail($val['thumbnail'],$sizeval);
?>
      <td class="pL pTB"><img src="<?php echo SITEAUTHORPATH;?>/<?php echo $thumbnailval;?>" alt="<?php echo $val['name'];?>" height="60" width="60" /></td>
 <?php
}
?>
      <td class="pL">
	  <div id="hdlnplaceholder<?php echo $val['id']?>" ><?php echo $val["name"];?></div>
	  </td>
	   <td class="pL"><?php echo $userDetails[$val['rights']];?></td>
	   <td class="pL"><?php 
		switch ($val['by_line']) {
			case 1:
				$by_line='By Line';
			break;
			case 2:
				$by_line='CMS User';
			break;
			case 3:
				$by_line='Both';
			break;
			}
		echo $by_line;?></td>
	    <td class="pL"><?php echo $val["email"];?></td>
      <td class="pL grayText_big"><?php echo getdisplaydatetime($val["lastvisit"]);?></td>
	   <td class="pL"><?php if($val["story_count"]!=0){?><a href="javascript:void(0);" onclick="ModalBox.open('<?php print CMSSITEPATH ?>/author/stats.php?searchByAuthor=<?php echo $val['id']?>', 850, 550);" title="View Statistics"><?php echo number_format($val["story_count"]);?></a><?php } else {echo $val["story_count"];}?></td>
	   <td class="padding5">
		<div class="actions">
		<?php
		if(RIGHTS == 0){
			if($action=='tc'){?>
			  <a href="javascript:void(0);" class="restore" title="Restore"><b>Restore</b></a>
		<?php } else { ?>
			  <a href="javascript:void(0);" class="edit" title="Edit" >Edit</a>					 
			  <a href="javascript:void(0);" class="delete" title="Delete">Delete</a>
<?php	    }
		} else {
			if ($action == 'tc' || $displaypage == 'trashcan') { ?>
				<a href="javascript:void(0);" onclick='callUnDelete(<?php echo $val['id'];?>,"<?php echo strtolower($_SESSION['TOPMENU'])?>")' class="restore" title="Restore"><b>Restore</b></a>
	  <?php }else{?>
			   <a href="<?php echo $modulePath; ?>/form.php?id=<?php echo $val['id'];?>&action=m" class="edit" title="Edit">Edit</a>
 			   <a href="<?php echo $modulePath; ?>/storydetails.php?id=<?php echo $val['id'];?>" title="View Story Details" class="details">Details</a> 
			   <?php if($val["email"]!= ''){ ?>
		       <a href="javascript:void(0);" onclick="ModalBox.open('<?php print CMSSITEPATH ?>/author/authormail.php?email=<?php echo $val['email']?>', 500, 400);" title="Email <?php echo $val["name"];?>" class="email">Email</a>
			<?php } ?>
			   <a href="javascript:void(0);" class="log" onclick="ModalBox.open('<?php print CMSSITEPATH ?>/adminlog/display.php?hide_layout=1&searchByAuthor=<?php echo $val['id']?>', 850, 550);" href="javascript:void(0);" title="View logs">Log</a>
			   <a href="javascript:;" onclick='callDelete("<?php echo $val['id'];?>","<?php echo strtolower($_SESSION['TOPMENU'])?>")' class="delete" title="Delete">Delete</a>       <?php }
		} ?>
		</div>
	 </td>
      <?php
	if ($flag == 'a' || isset($search)) {
		echo '</tr>';
	}
	}
	}else{
?>
    <tr>
      <td colspan="4" class="pL pTB" id="norecordsdiv">No Records</td>
    </tr>
<?php
		}//end of else	if(isset($search)){
	?>
	</tbody>
	<tfoot>
    <tr>
      <td colspan="4">
<?php
 if(Common::l($_GET['gs_paging'])!="0") {
    echo $paginate -> render($total);
 }
//---------Output pagination Div
?>
      </td>
    </tr>
	</tfoot>
</table>
