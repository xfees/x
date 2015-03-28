<script type="text/javascript">
  //add new block
  function afterAddBlock(res) {
    ModalBox.close();
    var results = eval("["+res+"]");
    results = results[0];
    if(results.insertId>0) {
      Toast.show("Added successfully");
	  getblocks();
    } else {
      Toast.show("There is some error in adding block");
    }	
  }
  
  function addnewBlock() {
    $("#newblock").modal();
  }

  function addnewChildBlock(headline_id,block_type) {
    $("#newChildBlock").modal();
    $("#headline_id").val(headline_id);
    if(block_type=='trending') {
        $("#trending_keyword").val("1");
    } else {
        $("#trending_keyword").val("0");        
    }
  }

  function cancelBlock() {
    $.modal.close();
  }
  function saveBlock() {
    var text = $("#blockname").val();
	var tlink = $("#blocklink").val();
    var priority = $('#priority').val();
        
    if(text=="") {
      Toast.show("Please enter block name");
      Toast.alignWithModalBox("blockname");
      return false;
    }
    
    if(tlink=="") {
      Toast.show("Please enter block url");
      Toast.alignWithModalBox("blocklink");
      return false;
    }  
    
	if(priority == "") {
        Toast.show("Please enter priority");
        Toast.alignWithModalBox("priority");
        return false;
    }
	
    $.ajax({
      url:'common.php',
      data:{action:'saveblock', block_name:text, block_link:tlink, priority:priority},
      type:'post',
      success: function(res) {
        afterAddBlock(res);
      }
    })
  }
  
  
   function saveChildBlock() {
    var text = $("#child_blockname").val();
    var tlink = $("#child_blocklink").val();
    var headline_id= $('#headline_id').val();
    var trending= $('#trending_keyword').val();
        
    if(text=="") {
      Toast.show("Please enter block name");
      Toast.alignWithModalBox("child_blockname");
      return false;
    }
    
    if(tlink=="") {
      Toast.show("Please enter block url");
      Toast.alignWithModalBox("child_blocklink");
      return false;
    }  
   
    $.ajax({
      url:'common.php',
      data:{action:'saveChildblock', block_name:text, block_link:tlink, trending:trending, headline_id:headline_id},
      type:'post',
      success: function(res) {
        afterAddBlock(res);
      }
    })
  }
 
 
  
  function checkForTab() {
    if($('#is_tab:checked').length == 1) {
      $('.show-on-tab').show();
    } else {
      $('.show-on-tab').hide();
      $('#archive_section_id').val('');
      $('#tab_priority').val('');      
    }
  }
</script>
<div id="newblock" style="width:500px; display:none;  height: 210px">
  <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#c3c3c3" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
    <tbody>
      <tr>
        <td width="30%" style="background-color:#fff; color:#000"><strong>Text</strong></td>
        <td style="background-color:#fff; color:#000">
          <input name="blockname" type="text" class="inputForm2" id="blockname" maxlength="50" />
        </td>
      </tr>
      <tr>
        <td width="30%" style="background-color:#fff; color:#000"><strong>Url</strong></td>
        <td style="background-color:#fff; color:#000">
          <input name="blocklink" type="text" class="inputForm2" id="blocklink" maxlength="50" />
        </td>
      </tr>
      <tr>
        <td width="30%"  style="background-color:#fff; color:#000"><strong>Priority</strong></td>
        <td style="background-color:#fff; color:#000">
          <input name="priority" type="text" class="inputForm2" id="priority" maxlength="2" size="2" style="width: 50px"/>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="background-color:#fff; color:#000" align="center">
          <img onclick="cancelBlock()" src="<?php echo IMAGEPATH; ?>/btn-cancel.gif" />&nbsp;&nbsp;<img id="savebutton" src="<?php echo IMAGEPATH; ?>/btn-save.gif" onclick="saveBlock()" />
        </td>
      </tr>
    </tbody>
  </table>	
</div>


<div id="newChildBlock" style="width:500px; display:none;  height: 210px">
  <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#c3c3c3" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
    <tbody>
      <tr>
        <td width="30%" style="background-color:#fff; color:#000"><strong>Text</strong></td>
        <td style="background-color:#fff; color:#000">
            <input type="hidden" name="headline_id" value="" id="headline_id" />
            <input type="hidden" name="trending_keyword" value="" id="trending_keyword" />
          <input name="child_blockname" type="text" class="inputForm2" id="child_blockname" maxlength="50" />
        </td>
      </tr>
      <tr>
        <td width="30%" style="background-color:#fff; color:#000"><strong>Url</strong></td>
        <td style="background-color:#fff; color:#000">
          <input name="child_blocklink" type="text" class="inputForm2" id="child_blocklink" />
        </td>
      </tr>
      <tr>
        <td colspan="2" style="background-color:#fff; color:#000" align="center">
          <img onclick="cancelBlock()" src="<?php echo IMAGEPATH; ?>/btn-cancel.gif" />&nbsp;&nbsp;<img id="savebutton" src="<?php echo IMAGEPATH; ?>/btn-save.gif" onclick="saveChildBlock()" />
        </td>
      </tr>
    </tbody>
  </table>	
</div>


