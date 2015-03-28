<script>
  function editBlocks(bid) {
    var block_id = bid;
    var holder = "mainsectionDiv"+block_id;
    var block_title = $("#"+holder).find(".spanBlockName a").text();
    var block_url = $("#"+holder).find("#block_url").val();
	$("#block_id_txt").val(block_id);	
    $("#blockname_txt").val(block_title);
	$("#blockname_url").val(block_url);
       $("#block_priority").val($("#main_block_priority"+bid).val());
       
    Utils.setPos("#blocknamePop", "#"+holder, "top");
    FX.come("#blocknamePop");
  }
  
  
  function editChildBlocks(bid) {
    var block_id = bid;
    var holder = "singleCont"+block_id;
    $.post("common.php",{action: 'getChildBlock', id:bid},function(datas){
        data=eval("{"+datas+"}");
        $("#Childblock_id_txt").val(data[0].id);	
        $("#Childblockname_txt").val(data[0].headline);
        $("#Childblockname_url").val(data[0].headline_link);
    });
    
    Utils.setPos("#ChildblocknamePop", "#"+holder, "top");
    FX.come("#ChildblocknamePop");
  }
  
  
  
  function saveBlockName() {
    
	var text = $("#blockname_txt").val();
	var tlink = $("#blockname_url").val();
        var priority = $("#block_priority").val();
        
    if(text=="") {
      Toast.show("Please enter block name");
      Toast.alignWithModalBox("blockname_txt");
      return false;
    }
    
    if(tlink=="") {
      Toast.show("Please enter block url");
      Toast.alignWithModalBox("blockname_url");
      return false;
    }  
    
    if(isNaN(priority)) {
      Toast.show("Please enter valid priority");
      Toast.alignWithModalBox("block_priority");
      return false;
    }
        
    var params = $("#blockNameFrm").serialize();
    $.ajax({
      url:'common.php',
      data:params,
      type:'post',
      success: function(res) {
        afterEditBlock(res);
      }
    });
    return false;
  }
  
  
   function saveChildBlockName() {
    
	var text = $("#Childblockname_txt").val();
	var tlink = $("#Childblockname_url").val();
        
    if(text=="") {
      Toast.show("Please enter block name");
      Toast.alignWithModalBox("Childblockname_txt");
      return false;
    }
    
    if(tlink=="") {
      Toast.show("Please enter block url");
      Toast.alignWithModalBox("Childblockname_url");
      return false;
    }  
        
    var params = $("#ChildblockNameFrm").serialize();
    $.ajax({
      url:'common.php',
      data:params,
      type:'post',
      success: function(res) {
        afterEditChildBlock(res);
      }
    });
    return false;
  }
  
  function closeBlockNamePop() {
    FX.out("#blocknamePop");
  }
  function closeChildBlockNamePop() {
    FX.out("#ChildblocknamePop");
  }
  
  function afterEditBlock(res) {
    FX.out("#blocknamePop");
    var results = eval("["+res+"]");
    results = results[0];
    if(results.id>0) {
      var holder = "mainsectionDiv"+results.id;
      var block_title = results.headline; 
      var block_url = results.headline_link;
      $("#"+holder).find(".spanBlockName a").text(block_title);
	  $("#"+holder).find(".spanBlockName a").attr("href", block_url);

      $("#"+holder).find("#block_url").val(block_url);
      Toast.show("Edited successfully..");
    } else {
      Toast.show("There is some error in editing block");
    }

  }
  
   function afterEditChildBlock(res) {
    FX.out("#ChildblocknamePop");
    var results = eval("["+res+"]");
    results = results[0];
    if(results.id>0) {
      var holder = "singleCont"+results.id;
      var block_title = results.headline; 
      var block_url = results.headline_link;
      $("#block_link"+results.id).html('<a href="'+block_url+'" target="_blank" style="font-size: 11px;"> '+block_title+'</a>');
      Toast.show("Edited successfully..");
    } else {
      Toast.show("There is some error in editing block");
    }

  }
</script>

<div id="blocknamePop" class="step1AddDeal shadowNew" style="width:350px; display:none; background:#fff; z-index:10">
  <h1><span class="close-popup backbtn" onClick="closeBlockNamePop()"></span>Edit block name</h1>
  <div class="padding5" style="background:#fff">
    <form name="blockNameFrm" id="blockNameFrm" method="post" onSubmit="">
      <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#c3c3c3" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
        <tr>
          <td style="background-color:#fff; color:#000"><strong>Text</strong></td>
          <td style="background-color:#fff; color:#000">
            <input type="text" class="inputForm2" name="blockname_txt" id="blockname_txt" />
          </td>
        </tr>
        <tr>
        <td width="30%"  style="background-color:#fff; color:#000"><strong>Url</strong></td>
        <td style="background-color:#fff; color:#000" align="left">
           <input type="text" class="inputForm2" name="blockname_url" id="blockname_url" />
        </td>
        </tr>
      <tr>
        <td width="30%"  style="background-color:#fff; color:#000"><strong>Priority</strong></td>
        <td style="background-color:#fff; color:#000" align="left">
            <input type="text" maxlength="2" class="inputForm2" name="block_priority" id="block_priority" />
        </td>
        </tr>
      
	  <tr>
          <td align="center" colspan="2" style="background-color:#fff; color:#000">
            <input type="hidden" id="block_id_txt" name="block_id_txt" value="0" />
            <input type="hidden"  name="action" value="editblock" />
            <input type="button" class="btnSubmit" value="Cancel" onClick="closeBlockNamePop()" />
            <input type="button" class="btnSubmit" value="Save" onclick="return saveBlockName()"/> 
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>




<div id="ChildblocknamePop" class="step1AddDeal shadowNew" style="width:350px; display:none; background:#fff; z-index:10">
  <h1><span class="close-popup backbtn" onClick="closeChildBlockNamePop()"></span>Edit block name</h1>
  <div class="padding5" style="background:#fff">
    <form name="ChildblockNameFrm" id="ChildblockNameFrm" method="post" onSubmit="">
      <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#c3c3c3" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
        <tr>
          <td style="background-color:#fff; color:#000"><strong>Text</strong></td>
          <td style="background-color:#fff; color:#000">
            <input type="text" class="inputForm2" name="Childblockname_txt" id="Childblockname_txt" />
          </td>
        </tr>
        <tr>
        <td width="30%"  style="background-color:#fff; color:#000"><strong>Url</strong></td>
        <td style="background-color:#fff; color:#000" align="left">
           <input type="text" class="inputForm2" name="Childblockname_url" id="Childblockname_url" />
        </td>
      </tr>
	  <tr>
          <td align="center" colspan="2" style="background-color:#fff; color:#000">
            <input type="hidden" id="Childblock_id_txt" name="Childblock_id_txt" value="0" />
            <input type="hidden"  name="action" value="editChildblock" />
            <input type="button" class="btnSubmit" value="Cancel" onClick="closeChildBlockNamePop()" />
            <input type="button" class="btnSubmit" value="Save" onclick="return saveChildBlockName()"/> 
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
