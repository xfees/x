 <div style="padding:5px">
     <a href="javascript:void(0)" style="font-size: 15px;font-weight: bold;" onclick="addnewBlock()">Add Block</a>
</div>
<?php
error_reporting(0);
include_once('../config.php');
$_SESSION['TOPMENU'] = 'footer';
$module = "footer";
$conn = Database::Instance();
$id = $_POST['id'];
$action = $_POST['action'];
if ($action == 'g') {
  $search = 'byname';
  $pg_id = intval($id);
  $sql = "SELECT * FROM footer WHERE block_id=0 and status!=-1 ORDER BY priority";
  $conn->query($sql);
  $rowcnt = $conn->getRowCount();
  if ($rowcnt > 0) {
    $resultblocksData = $conn->getResultSet();
    foreach ($resultblocksData as $resultblocks) {
		if($resultblocks["status"] == 0) {
			$display='Click to Publish';
			$title = 'Click to Publish';
			$cssPublished = 'unPublished';
			$status = "1";
		} else {
			$display='Click to UnPublish';
			$title = 'Click to UnPublish';
			$cssPublished = 'published';
			$status = "0";
		}
      $block_id = $resultblocks["id"];
      $ulid = "rowCont" . $resultblocks['id'];
      ?>
<div style="clear: both;height: 1px;">
    &nbsp;
</div>
      <div class="sections" id="mainsectionDiv<?php echo $resultblocks['id']; ?>"> 
        <h2 class="pL pTB" style="border: 1px solid #C3C3C3;background-color:#303A45;color:#FFF;margin-bottom: 7px;">
          <span style="float:right" class="whitelinks">
<div class="floatR">
<a href="javascript:void(0);" id="publishspan<?php echo $resultblocks['id'];?>" onclick="changeStat('<?php echo $resultblocks['id'];?>', '<?php echo $status ;?>')" class="<?php echo $cssPublished;?>" title="<?php echo $title;?>"><?php echo $display;?></a> 
<span class="gray">&nbsp;|&nbsp;</span>
<!--
<a href="javascript:void(0);" onclick='callUnDelete(<?php echo $resultblocks['id'];?>,"<?php echo $_SESSION['TOPMENU']?>")' class="restore" title="Restore"><b>Restore</b></a>
-->
<a href="javascript:void(0)" onclick="editBlocks('<?php echo $resultblocks['id']; ?>')" class="edit" title="Edit">Edit</a> 
<span class="gray">&nbsp;|&nbsp;</span>
<a href="javascript:void(0)" title="Delete" class="delete" onclick="changeStat('<?php echo $resultblocks['id']; ?>', '-1')">Delete</a>

</div>
		    <!--<span class="editBlock"><a href="javascript:void(0)" onclick="editBlocks('<?php echo $resultblocks['id']; ?>')">Edit</a></span>
            <span class="gray">&nbsp;|&nbsp;</span>
            <span class="deleteBlock"><a href="javascript:void(0)" onclick="deleteBlocks('<?php echo $resultblocks['id']; ?>')">Delete</a></span>
            <span class="gray">&nbsp;|&nbsp;</span>
            <span class="deleteTrends"><a href="javascript:void(0)" onclick="publishBlocks('<?php echo $resultblocks['id']; ?>')">Publish</a></span>-->
          </span>
            <input type="hidden" id="main_block_priority<?php echo $resultblocks["id"]; ?>" name="main_block_priority<?php echo $resultblocks["id"]; ?>" value="<?php echo $resultblocks["priority"]; ?>" />
          <span class="spanBlockName">
              <a target="_blank" style="color: #fff;" href="http://<?php echo $resultblocks["headline_link"]; ?>"><?php echo $resultblocks["headline"]; ?></a>
		  </span>
          <span style="display:none">
            <input type="hidden" id="h_priority" value="<?php echo $resultblocks["priority"];?>"/>
			<input type="hidden" id="block_id" value="<?php echo $resultblocks["id"];?>"/>
			<input type="hidden" id="block_url" value="<?php echo $resultblocks["headline_link"];?>"/>
          </span>
          <span class="loading" style="display:none"></span>
        </h2>
          
          <div style="float: right; width:49%;">
           <h2 class="pL pTB" style="border: 1px solid #C3C3C3;color:#000;">
               <span style="color: grey;">
		  	Trending Links
                        <a href="javascript: void(0);" onclick="addnewChildBlock('<?php echo $resultblocks["id"]; ?>','trending');" style="font-size: 11px;">Add Link</a>
		  </span>
          <span style="display:none">
            <input type="hidden" id="h_priority" value="<?php echo $resultblocks["priority"];?>"/>
			<input type="hidden" id="block_id" value="<?php echo $resultblocks["id"];?>"/>
			<input type="hidden" id="block_url" value="<?php echo $resultblocks["headline_link"];?>"/>
          </span>
               <span class="loading" style="display:none"></span>
        </h2>
              <ul class="dropable" id="<?php echo $ulid ?>"  style="min-height: 187px;">
          <input class="blockid" type="hidden" value="<?php echo $block_id; ?>" />
          <?php
          $sql = "select * from footer WHERE block_id = $resultblocks[id] and link_type=2 and status!=-1 ORDER BY priority";  // Block Data
          $conn->query($sql);
          $resultData = $conn->getResultSet();
          if(!empty($resultData)){
          foreach ($resultData as $resultDataVal) {
            $contentID = $resultDataVal['content_id'];
            $status = $resultDataVal['status'];
            if ($status == 0) {
              $display = 'UnPublish';
              $title = 'Click to Publish';
              $cssPublished = 'unPublished';
            } else {
              $display = 'Publish';
              $title = 'Click to UnPublish';
              $cssPublished = 'published';
            }
            ?>
           <li class="listing move" id='singleCont<?php echo $resultDataVal['id'] ?>'>
              <input type='hidden' value='<?php echo $resultDataVal['id'] ?>' class='recordid' />
              <input type="hidden" class="contentid" value="<?php echo $contentID ?>" />
              <input type="hidden" class="priority" value="<?php echo $resultDataVal["priority"] ?>" />
              <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                  <td width="60%" class="lc_content">
                    <a style="float:left" onclick="deleteRow('singleCont<?php echo $resultDataVal['id'] ?>')" class="ui-icon ui-icon-trash" title="Delete" href="javascript:void(0)">Delete</a>
                    <span style="color:gray"  id="block_link<?php echo $resultDataVal['id']; ?>">
                        <a href="<?php echo $resultDataVal['headline_link']; ?>" target="_blank" style="font-size: 11px;"> <?php echo $resultDataVal['headline']; ?> </a>
                    </span>
                  </td>
                  <td class="rc_content" style="font-size: 11px;">
                    <?php echo getdisplaydatetime($resultDataVal["insertdate"]) ?>
                  </td>
                  <td class="pL">
                    <div class="actions">
                      <a href="javascript:void(0);" id="publishspan<?php echo $resultDataVal['id']; ?>" onclick="changeStatus('<?php echo $resultDataVal['id']; ?>', '<?php echo $_SESSION['TOPMENU']; ?>', 'singleCont<?php echo $resultDataVal['id']; ?>')" class="<?php echo $cssPublished; ?>" title="<?php echo $title; ?>"><?php echo $display; ?></a>
                      <a href="javascript:void(0)" onclick="editChildBlocks('<?php echo $resultDataVal['id']; ?>')" class="edit" title="Edit">Edit</a> 
                    </div>
                  </td>                  
                </tr>
              </table>
            </li>                
            <?php
          }
          } else {
              ?>
            <li><center>No Link Submitted.</center></li>
            <?php
          }
          ?>                  
        </ul>
        <div style="display:none" class="savep sections"><input id="priority" type='button' width=200 value='Save Priorities' onclick="savePriorities('<?php echo $ulid ?>')" /> <span class="statustext"></span></div>
          </div>
          
          
          
          <div style="float: left;width: 49%;">
          <h2 class="pL pTB" style="border: 1px solid #C3C3C3;color:#000;">
              <span  style="color: grey;">
		  	Block Links
                        <a href="javascript: void(0);" onclick="addnewChildBlock('<?php echo $resultblocks["id"]; ?>','block');" style="font-size: 11px;">Add Link</a>
		  </span>
          <span style="display:none">
            <input type="hidden" id="h_priority" value="<?php echo $resultblocks["priority"];?>"/>
			<input type="hidden" id="block_id" value="<?php echo $resultblocks["id"];?>"/>
			<input type="hidden" id="block_url" value="<?php echo $resultblocks["headline_link"];?>"/>
          </span>
               <span class="loading" style="display:none"></span>
        </h2>
              <ul class="dropable" id="<?php echo $ulid ?>" style="min-height: 187px;">
          <input class="blockid" type="hidden" value="<?php echo $block_id; ?>" />
          <?php
          $sql = "select * from footer WHERE block_id = $resultblocks[id] and link_type=3 and status!=-1 ORDER BY priority";  // Block Data
          $conn->query($sql);
          $resultData = $conn->getResultSet();
          if(!empty($resultData)) {
          foreach ($resultData as $resultDataVal) {
            $contentID = $resultDataVal['content_id'];
            $status = $resultDataVal['status'];
            if ($status == 0) {
              $display = 'UnPublish';
              $title = 'Click to Publish';
              $cssPublished = 'unPublished';
            } else {
              $display = 'Publish';
              $title = 'Click to UnPublish';
              $cssPublished = 'published';
            }
            ?>
           <li class="listing move" id='singleCont<?php echo $resultDataVal['id'] ?>'>
              <input type='hidden' value='<?php echo $resultDataVal['id'] ?>' class='recordid' />
              <input type="hidden" class="contentid" value="<?php echo $contentID ?>" />
              <input type="hidden" class="priority" value="<?php echo $resultDataVal["priority"] ?>" />
              <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                  <td width="60%" class="lc_content">
                    <a style="float:left" onclick="deleteRow('singleCont<?php echo $resultDataVal['id'] ?>')" class="ui-icon ui-icon-trash" title="Delete" href="javascript:void(0)">Delete</a>
                    <span style="color:gray" id="block_link<?php echo $resultDataVal['id']; ?>">
                        <a href="<?php echo $resultDataVal['headline_link']; ?>" target="_blank" style="font-size: 11px;"> <?php echo $resultDataVal['headline']; ?> </a>
                    </span>
                  </td>
                  <td class="rc_content" style="font-size: 11px;">
                    <?php echo getdisplaydatetime($resultDataVal["insertdate"]) ?>
                  </td>
                  <td class="pL">
                    <div class="actions">
                      <a href="javascript:void(0);" id="publishspan<?php echo $resultDataVal['id']; ?>" onclick="changeStatus('<?php echo $resultDataVal['id']; ?>', '<?php echo $_SESSION['TOPMENU']; ?>', 'singleCont<?php echo $resultDataVal['id']; ?>')" class="<?php echo $cssPublished; ?>" title="<?php echo $title; ?>"><?php echo $display; ?></a>
                      <a href="javascript:void(0)" onclick="editChildBlocks('<?php echo $resultDataVal['id']; ?>')" class="edit" title="Edit">Edit</a> 
                    </div>
                  </td>                  
                </tr>
              </table>
            </li>                
            <?php
          }
          } else {
              ?>
            <li><center>No Link submitted</center></li>
            <?php
          }
          ?>                  
        </ul>
                  <div style="display:none" class="savep sections"><input id="priority" type='button' width=200 value='Save Priorities' onclick="savePriorities('<?php echo $ulid ?>')" /> <span class="statustext"></span></div>

      </div>
              
         
          
        
      </div>
      <?php
    }
    ?>
   
    <?php
  }
} else if ($action == 'u') {
  $blockid = $_POST['blockid'];
  $pageid = $_POST['pageid'];
  $ids = explode(",", $id);
  $sql_query = '';
  foreach ($ids as $key => $value) {

    $sql_query = "UPDATE footer SET priority='" . ($key + 1) . "' WHERE id='" . $value . "' AND block_id='" . $blockid . "';";
    $conn->query($sql_query);
  }
}
?>