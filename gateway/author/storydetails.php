<?php
/************************************************** Include Configuration Files*********************************** */
include_once ('../config.php');
error_reporting(1);

$_SESSION['TOPMENU'] = "author";
$filename = strtolower($_SESSION['TOPMENU']);
$main_file = "get{$filename}.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Author Management</title>
<!-- *****  Include the CSS FILES ********************************************************************************-->
<?php include_once '../incHeaderScript.php'; ?>	<script type='text/javascript' src='author.js'></script>
	<script type='text/javascript'>	
	// callback function to bring a hidden box back
	function callback() {
		console.log("finish");
	};
	</script><script type='text/javascript' src='author.js'></script>
	<script type='text/javascript'>	
	// callback function to bring a hidden box back
	function callback() {
		console.log("finish");
	};
	</script>
<script>
function resetSearch() {
    document.getElementById("searchForm").reset(); 
    searchform();
}

function searchform(){
    $("#searchForm .current").removeClass("current");
    $("#searchForm select[value!='']").addClass("current");
	$("#searchForm #freeTextSearch[value!='Type Headline']").addClass("current");
    if($("#searchForm .current").length>0) {
        FX.highlight("#searchForm .current");
    }
    searchdata('','bydata','','','','','','') ;
	return false;
}

function searchdata(filename,searchtype,data,currpage,lastpage,firstpage,recperpage,istrash,modulename, callback){	
	var datastring = "search="+searchtype;
	var serializedForm = $('#searchForm').serializeArray();
	$.each(serializedForm , function(i, field) {
		  serializedForm[i].value = $.trim(field.value);
		  if(serializedForm[i].value=='Type Headline')serializedForm[i].value ='';
	});
	data =  data+"&"+$.param(serializedForm);
	datastring = datastring+"&"+data;	
	datastring = datastring+"&pg="+currpage+"&displastpage="+lastpage+"&dispfirstpage="+firstpage+"&recperpage="+recperpage+"&action=story&searchByAuthor=<?php echo $_GET['id'];?>";
	$('#mainContainer').html('<div align="center"><img src="../images/ajax-loader.gif" border="0" /></div>'); 
	$.ajax({
	   type: "POST",
	   url: "getauthor.php",
	   data: datastring,
	   success: function(resultdata){		  
	   		$('#mainContainer').html(resultdata); 
	   }
	}); 
}
</script>
</head>
<body onload="searchdata('','bydata','','','','','','');">
<?php include_once (CMSROOTPATH . "/topmenu.php"); ?>
<div class="content">
  <div class="title">Author Management</div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" class="rightPanel">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="box">
        <tr>
          <td width="10" class="boxtopLeft"></td>
          <td class="boxtopMiddle"></td>
          <td class="boxtopRight"></td>
        </tr>
        <tr>
          <td width="10" class="boxMiddleLeft"></td>
          <td class="boxMiddleMiddle">
          <div id="tabs" class="padding12"> <span class="iconBack">&nbsp;</span> <a  href="display.php" style="cursor: pointer;"><span id="addEditText">Go To</span> Author List</a> <span id="backtomodule" style="margin-right: 5px;"></span> </div>
          <!--content display starts here -->
          <div style="margin:25px">
            <form name="searchForm" id="searchForm" action="" onsubmit="return searchform()">
              <div class="searchdiv">
                <table border="0" cellspacing="1" cellpadding="2" class="searchTable">
                  <tr>
                    <td><?php $sel_cat = (Common::l($_POST['searchByCategory'])=='') ? 'Select category' : Common::l($_POST['searchByCategory']); 
								echo $cmn->getCategoryCombo("searchByCategory", $sel_cat);	?>
                    </td>
                    <td><input type="text" name="freeTextSearch" id="freeTextSearch" class="inputWizard2 freetext-search" size="10" value="" /></td>
                    <td>
					  <span class="btnset">
						  <input type="submit" value="Search" class="btnSubmit btntool" id="submitFrm" name="submitFrm" />
						  <input onclick="resetSearch()" class="btntool" type="button" value="Reset" title="Reset Search" />
                      </span> 
					</td>
                  </tr>
                </table>
              </div>
            </form>
            <div style="clear: both;height: 10px;"></div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablesorter">
              <thead>
                <tr class="removeheading titlebar">
                  <th width="30%" valign="middle"><div style="float: left; padding: 5px 0 0 10px; ">Headline</div></th>
                  <th width="10%" valign="middle"><div style="text-align:right; float:left;"><img class="listSeparator" width="2" height="31" border="0" align="absmiddle" src="<?php echo IMAGEPATH;?>/list-separator.gif"></div>
                    <div style="float: left; padding: 8px 0 0 10px; ">Content Type</div></th>
                  <th width="10%"recperpage valign="middle"><div style="text-align:right; float:left;"><img class="listSeparator" width="2" height="31" border="0" align="absmiddle" src="<?php echo IMAGEPATH;?>/list-separator.gif"></div>
                    <div style="float: left; padding: 8px 0 0 10px; ">Section</div></th>
                  <?php if($displaypage!='trashcan' &&  RIGHTS != 0 ){?>
                  <th width="5%" valign="middle" class=""><div style="text-align:right; float:left;"><img class="listSeparator" width="2" height="31" border="0" align="absmiddle" src="<?php echo IMAGEPATH;?>/list-separator.gif"></div>
                    <div style="float: left; padding: 8px 0 0 10px; ">Actions</div></th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody id="mainContainer">
              </tbody>
            </table>
          </div>
        </td>
        
        <td width="10" class="boxMiddleRight">&nbsp;</td>
        </tr>
        <tr>
          <td class="boxbottomLeft"></td>
          <td class="boxbottomMiddle"></td>
          <td class="boxbottomRight"></td>
        </tr>
      </table>
      </td>
    </tr>
  </table>
</div>
<?php include_once (CMSROOTPATH . "/incFooter.php"); ?>
</body>
</html>
