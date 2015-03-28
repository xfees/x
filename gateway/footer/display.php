<?php
error_reporting(0);

//*********************************************************** Include Configuration Files
include_once ('../config.php');

$_SESSION['TOPMENU'] = "footer";
$filename = strtolower($_SESSION['TOPMENU']);
$auth_id = '';
if(RIGHTS == 2){
	$auth_id = $_SESSION['ITUser']['ID'];
}
//**********************************************************  Initialization of Database Object
$objModel = new Footer();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Footer Management</title>
<!-- ******************************************************************************   Include Css Files **-->
<link href="<?php echo CSSFILEPATH;?>/cms.css" rel="stylesheet" type="text/css" />
<style>
h2, h1{margin:0; padding:0}
.managePages{}
.allsections{
background:#f2f2f2;border:1px solid #c3c3c3; 
}
.choose{padding:10px;}
.sections{padding:10px;background:#fff;}
.sections h2, .spanBlockName a, .spanBlockName a:hover{color:#000; padding:5px; font-size:16px;}
.dropable{border:1px solid #c3c3c3; padding:10px; background:#fff}

ul.dropable,
#mainContainer { list-style-type: none;}
ul.dropable li,
#mainContainer li {margin-bottom:3px;}
.cnt{cursor:pointer}
#simplemodal-container {
    background-color: #FFFFFF !important;
    border: 4px solid #fff  !important;
    color: #666;
    padding: 12px  !important;
}
.data{color:#666 }
.data a, .data a:visited{color:#0184AA !important; text-decoration:undeline !important}
.medium{font-size:14px; font-weight:bold;}
.big{font-size:18px; font-weight:bold;}
.whitelinks a, .whitelinks a:visited{font-size:11px; color:#F0f0f0 !important;}
</style>
<?php

include_once("../incHeaderScript.php");

?>
<script type="text/javascript" src="<?php echo CMSSITEPATH; ?>/plugins/media/media.js"></script>
<script type="text/javascript">
/************** sortable ***************/
var isSortedChanged = false;
var currentSortableID = '';
var staticid = 1;
var priority=0;
function activateSortable() {
	$(".sections ul.dropable").sortable({
   		change: function(event, ui) {
			isChanged= true;
			sortid = event.target.id;
			$("#"+sortid).parent().find(".statustext").html("");
			$("#"+sortid).parent().find(".savep").show();
			
			//console.debug(sortid);
		}
	});
	$(".sections ul.dropable").disableSelection();	
	$("#reorderSub").disableSelection();
}
function savePriorities(sortid) {
	if(sortid != '') {
			var allLI = $("#"+sortid +" li.listing");
			if(allLI.length>1) {
				$("#"+sortid).parent().find(".statustext").html("Please wait...");
				var id = '';
				for(var iCount=0;iCount<allLI.length;iCount++) {
					id += $("#"+$(allLI[iCount]).attr("id")).find(".recordid").val(); //$("#"+$(allLI[iCount]).attr("id")).find("recordid").val();
					
					if(iCount!=allLI.length-1) {
						id +=",";
					}
				}
				pageid = $("#pages").val();
				blockid = $("#"+sortid).parent().find(".blockid").val();
				$.ajax({
						   type: "POST",
						   url: "getblocks.php",
						   data: {id:id, action:'u', pageid:pageid, blockid:blockid},
						   success: function(data) {
						   		$("#"+sortid).parent().find(".statustext").html(data);
							    $("#"+sortid).parent().find(".savep").hide("slow");
						   }
					});
			} else {
				$("#"+sortid).parent().find(".statustext").html("Nothing to save...");
			}
	}
}

function showContentSearchForm() {
	$('#contentSearchHolder').toggle();
	if($('#contentSearchHolder').css("display") != "none") {
		$('#btnContentSearch').addClass('selected');
	} else {
		$('#btnContentSearch').removeClass('selected');
	}
}
function getblocks() {
	var datastring = "&action=g";	
	$.ajax({
		   type: "POST",
		   url: "getblocks.php",
		   data: datastring,
		   success: populateRow					  
	});
	$('#linkBlock').show();
	$('#linkBlock').click(function(){
		var text = $("option:selected", $("#pages")).text();
		var id = $("option:selected", $("#pages")).val();
		ModalBox.open('saveblocks.php?id='+id+'&name='+text,500,150);
	});	
}

function delBlock(val, pageid){
	if(confirm('Are you sure you want to delete !!!')){
		if(parseInt(val) > 0){
			var datastring = "blockid="+val+"&action=db";
			$.ajax({
				   type: "POST",
				   url: "footer2db.php",
				   data: datastring,
				   success: function(data) {
						getblocks(pageid);
				   }					  
			});
		}
	}
}

function populateRow(data) {	
	$("#blocksholder").html(data);	
	activateSortable();
	filterResults();	
	
}
function filterResults() {
	searchdata('footer','first','','','','','20','footer', '', enableDrag);
}

function sSearch()
{
	var sectionid = $("#pages").val();
	searchdata('<?php echo $filename?>','byname',sectionid,'','','','20','','', enableDrag)
}


function deleteRow(id) {
	$("#"+id).fadeOut("fast", function(arg) {
		updateDatabase(id, 'remove');
		$("#"+id).remove();
	});
}

var mpBlocks = ["<?php echo join("\", \"", $mpBlocks); ?>"];

function putContentBack(evt, ui) {
	var data = ui;
	var id = $(evt.target).attr("id");
	$("#"+id +" .remove").remove();
	var holderid = "droppeddata_"+staticid;
	var trash_icon = '<a style="float:left" href="javascript:void(0)" title="Delete" class="ui-icon ui-icon-trash" onclick="deleteRow(\''+holderid+'\')">Delete</a>';
	str = "<li id='"+holderid+"' class='listing move'>";
	str += data.html();
	str += "</li>";
	var status = '<div class="actions" style="float:right;margin-right:12px"><a href="javascript:void(0);" id="publishspan'+holderid+'" onclick=\'getStatusID("'+holderid+'")\' class="unPublished" title="Click to Publish">UnPublish</a>';
   //alert(str);
   
   var blockid = $("#"+id).find(".blockid").val();
   if(jQuery.inArray(blockid, mpBlocks) != -1) {
    status += "<a class='mp-add' href='javascript:void(0);' onclick='mpOpenPlugin(\"blocks\",\"thumbnail\",\"thumbnail\",\""+blockid+"\",\""+holderid+"\");'></a>";
   }
   
   status += '</div>';
	$("#"+id).append(str);
	$("#"+holderid).find(".lc_content").prepend(trash_icon);
	$("#"+holderid).find(".rc_content").append(status);
	$("#mainContainer li.last").before(data);
	staticid++;
	updateDatabase(holderid, 'add');
	activateSortable();
}	

function getStatusID(holder){
	var id = $("#"+holder).find(".recordid").val();
	$('#publishspan'+holder).attr('id', 'publishspan'+id);
	changeStatus(id, "footer", "singleCont"+id);
}

function updateDatabase(id, action) {
	var a = action;
	var holder = id;
	var pageid = $("#pages").val();
	var blockid = $("#"+holder).parent().find(".blockid").val();
	var contentid = $("#"+holder).find(".contentid").val();
	var id = 0;
	var oData = new Object();
	oData.pageid = pageid;
	oData.blockid = blockid;
	oData.contentid = contentid;
	switch(a) {
		case 'add':
			oData.action = 'a';
		break;
		case 'remove':
			priority--;
			oData.id = $("#"+holder).find(".recordid").val();
			oData.action = 'd';
		break;
	} //alert(oData);
	$.ajax({
			   type: "POST",
			   url: "footer2db.php",
			   data: oData,
			   success: function(data){
			   		showMessage(data, holder, oData)
				}
	});
}
function showMessage(data, holder, oData) {
	if(oData.action == 'a') {
		var jObj=eval("("+data+")");
		var conArr=jObj.content;
		$("#"+holder).find(".recordid").val(jObj.id);	
	}
}

/********* drag and drop *************/	
function enableDrag() {
				var $blocksholder = $('#blocksholder')
				var $contentHolder = $('#mainContainer');

				// let the gallery items be draggable
				$('li.listing',$contentHolder).draggable({
					cancel: 'a.ui-icon',// clicking an icon won't initiate dragging
					revert: 'invalid', // when not dropped, the item will revert back to its initial position
					containment: $('#demo-frame').length ? '#demo-frame' : 'document', // stick to demo-frame if present
					helper: 'clone',
					cursor: 'move'
				});

				// let the trash be droppable, accepting the gallery items
				$('ul.dropable', $blocksholder).droppable({
					accept: '#mainContainer li.listing',
					activeClass: 'ui-state-highlight',
					drop: function(ev, ui) {
						putContentBack(ev, ui.draggable);
					}
				});

				// let the gallery be droppable as well, accepting items from the trash
				$blocksholder.droppable({
					accept: '#blocksholder div.sections',
					activeClass: 'custom-state-active',
					drop: function(ev, ui) {
						//recycleImage(ui.draggable);
						alert("blocks dragged")
					}
				});
}
function changeStat(bid, status) {
	var blockid = bid; var action = '';
	if(status == -1) {
		DialogBox.showConfirm("Are you sure? This will delete whole block and its content.", "Delete Block", function(res) {
			if(res==true) {
				changeNow();
			}
		});
	} else if(status == 0){
		DialogBox.showConfirm("Are you sure you want to unpublish this whole block and its content.", "Unpublish Block", function(res) {
			if(res==true) {
				changeNow();
			}
		});
	} else {
		DialogBox.showConfirm("Are you sure you want to publish this whole block and its content.", "Publish Block", function(res) {
			if(res==true) {
				changeNow();
			}
		});
	}
	//working here;
	function changeNow() {
		$.ajax({
			url:'common.php',
			data:{action:'toggleStatus', block_id:blockid, status:status},
			type:'post',
			success: function(res) {
				var results = eval("["+res+"]");
				results = results[0];
				if(status == 0) {
					$("#publishspan"+results.id).text('Click to Publish');
					$("#publishspan"+results.id).attr("class", "unPublished");
					$("#publishspan"+results.id).attr("title", "Click to Publish");
					$("#publishspan"+results.id).click(function () { 
					  	changeStat(results.id, 1);
					});
					Toast.show("Block unpublished...", undefined, 2000);					
				} else if(status == 1) {
					$("#publishspan"+results.id).text('Click to UnPublish');
					$("#publishspan"+results.id).attr("class", "published");
					$("#publishspan"+results.id).attr("title", "Click to UnPublish");
					$("#publishspan"+results.id).click(function () { 
					  	changeStat(results.id, 0);
					});
					Toast.show("Block published...", undefined, 2000);
				} else {
					FX.explode("#mainsectionDiv"+results.id, function() {
						Toast.show("Block deleted...", undefined, 2000);
					});
				}
			}
		})
	}
}
</script>
</head>
<body>
<?php
	include_once(CMSROOTPATH.'/topmenu.php');
?>
<!-- new content -->
<div class="content">
  <div class="title">Footer Management</div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" class="leftPanel"><!-- make this td conditional -->
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="box">
          <tr>
            <td width="10" class="boxtopLeft"></td>
            <td class="boxtopMiddle"></td>
            <td class="boxtopRight"></td>
          </tr>
          <tr>
            <td width="10" class="boxMiddleLeft"></td>
            <td class="boxMiddleMiddle"><div class="leftlinks">
                <?php
                    include_once(CMSROOTPATH.'/leftmenu.php');
                ?>
              </div></td>
            <td width="10" class="boxMiddleRight"></td>
          </tr>
          <tr>
            <td class="boxbottomLeft"></td>
            <td class="boxbottomMiddle"></td>
            <td class="boxbottomRight"></td>
          </tr>
        </table></td>
      <td valign="top" class="rightPanel"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="box">
          <tr>
            <td width="10" class="boxtopLeft"></td>
            <td class="boxtopMiddle"></td>
            <td class="boxtopRight"></td>
          </tr>
          <tr>
            <td width="10" class="boxMiddleLeft"></td>
            <td class="boxMiddleMiddle"><div><span class="h1tdB1" id="notification"> <?php echo $msg?> </span></div>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td  valign="top"><div class="managePages" style="padding:10px;">
                      <h3>Manage Footer</h3>
                      <div class="allsections" id="blocksholder" style="background: none;overflow: hidden;padding-bottom: 10px;">
                        <div class="sections"><a onclick="addnewBlock()" href="javascript:void(0)">Add Headlines</a></div>
                      </div>
                    </div></td>
                  <td valign="top"></td>
                </tr>
              </table></td>
            <td width="10" class="boxMiddleRight"></td>
          </tr>
          <tr>
            <td class="boxbottomLeft"></td>
            <td class="boxbottomMiddle"></td>
            <td class="boxbottomRight"></td>
          </tr>
          </tbody>          
        </table></td>
    </tr>
  </table>
</div>
<script>
getblocks();
</script>
<!-- new content end -->
<?php  
include_once 'tpl-new-block.php';
include_once 'inc-edit-block.php';
/*include_once(CMSROOTPATH."/incFooter.php"); */
?>
</body>
</html>
