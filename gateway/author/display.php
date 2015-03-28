<?php
/***** INCLUDE CONNECTION FILE ************************************************************************************/
include_once('../config.php');
$_SESSION['TOPMENU']="author";
$filename=strtolower($_SESSION['TOPMENU']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Author Management</title>
	<?php 
	include_once '../incHeaderScript.php';
	?>	 	
	<script type='text/javascript' src='author.js'></script>
	<script type='text/javascript'>	
	// callback function to bring a hidden box back
	function callback() {
		console.log("finish");
	};
	</script>
	<style>
	.ui-effects-transfer { border: 2px dotted gray; } 
	</style>
</head>
<body>
<?php include_once(CMSROOTPATH."/topmenu.php");?>
<div class="content">
<div class="title">Author Management</div>
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
            <td class="boxMiddleMiddle">
	           <div class="leftlinks"><?php include_once(CMSROOTPATH."/leftmenu.php");?></div>            
            </td>
            <td width="10" class="boxMiddleRight"></td>
          </tr>
          <tr>
            <td class="boxbottomLeft"></td>
            <td class="boxbottomMiddle"></td>
            <td class="boxbottomRight"></td>
          </tr>
        </table></td>
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
		<div id="tabs" class="padding12">
			<a  style="cursor:pointer;" href="form.php"><span class="iconAdd">&nbsp;</span><span id="addEditText">Add New</span> <?php echo ucfirst($_SESSION['TOPMENU']);?></a> 
			<a href="javascript:void(0);" onclick="ModalBox.open('<?php print CMSSITEPATH ?>/author/authormail.php?email=all', 500, 400);" title="Email All"><span class="iconEmail">&nbsp;</span><span id="emailAll">Email All</span></a> 
			<div id="divTrash" style="float: right;">
				<a href="javascript:" onclick="getTrash()"><span class="iconTrash">&nbsp;</span>Trash Can</a>
			</div>
			<span id="backtomodule" style="margin-right:5px;"></span>
		</div>
      		<br clear="all" />
        </div>
		<div id="displaycontent" class="padding12">
			<div class="sorting"></div>
			<!--content display starts here -->
			<?php
					require_once(CMSROOTPATH . '/author/searchform.php');
			?>
			<div id="mainContainer">
			<?php include_once('getauthor.php'); ?>
			</div>	
		</div>		
			<!-- content display ends here -->		
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
<?php include_once(CMSROOTPATH."/incFooter.php"); ?>
</body>
<script type="text/javascript">
$(document).ready(function() {
	<?php if(isset($_GET['gs_text'])) { ?>
	$("#freeTextSearch").val('<?php echo $_GET['gs_text']; ?>');
	<?php } else { ?>
	$("#freeTextSearch").val('Search By Name');
	<?php } ?>
	$("#searchByEmail").val('Search by Email');
	 $('input[type=text]').focus(function() {
	  if($(this).val() == $(this).attr('defaultValue')) {
		 $(this).val('');
	  }
   })
   .blur(function() {
	  if($(this).val().length == 0) {
		 $(this).val($(this).attr('defaultValue'));
	  }
   });
})
</script>
</html>
