<?php
/************************************************** Include Configuration Files*********************************** */
include_once ('../config.php');

$_SESSION['TOPMENU'] = "author";
$filename = strtolower($_SESSION['TOPMENU']);
$main_file = "get{$filename}.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $modelObj = new Author();
    $returned_data = json_decode($modelObj->getEditData($_GET['id']), true);
    $data = $returned_data[0];
    $thumbnail = $data['thumbnail']; 
}
$modules_array = explode(',', $data['cmsmodules_id']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Author Management</title>
		<?php  include_once '../incHeaderScript.php'; ?>
		<link href="<?php echo CSSFILEPATH;?>/highslide_popup.css" rel="stylesheet" type="text/css" />
		<script type='text/javascript' src='<?php echo JSFILEPATH;?>/tiny_mce/tiny_mce.js'></script>
		<script type='text/javascript' src='author.js'></script>
		
		<!--  for media plugin -->
		<script type="text/javascript" src="<?php echo CMSSITEPATH; ?>/plugins/media/media.js"></script>
        <script>
            $(document).ready(function() {
			     if($('#action').val() == 'a') {
					    var oDate = new Date();
					    var ds = "-";
					    var ts = ":";
					    var sDate = (oDate.getDate())+ds+(oDate.getMonth()+1)+ds+(oDate.getFullYear()) + " " + (oDate.getHours())+ts+(oDate.getMinutes())+ts+(oDate.getSeconds());
					    $("#publishdate").val(sDate);
					    $('#showdateval').html(sDate);
				    }
		    })
		</script>
	</head>
	<body>		
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
							<div id="tabs" class="padding12">								
								<span class="iconBack">&nbsp;</span>
								<a  href="display.php" style="cursor: pointer;"><span id="addEditText">Go To</span> Authors List</a>
								<span id="backtomodule" style="margin-right: 5px;"></span>
							</div> 
							<!--content display starts here -->
    <div id="mainContainer">
       <div id="editcontent">
			<div class="line"></div>
			<form name="dataform" id="dataform" enctype="multipart/form-data" method="post" action="getauthor.php" onsubmit="return saveAuthor();">
			<input type="hidden" value="<?php echo $_GET['id']!=''?'m':'a'; ?>" name="action" id="action" class="hidden" />
			<input type="hidden" value="<?php echo $_GET['id']!=''?$_GET['id']:''; ?>" name="id" id="id" class="hidden" />
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="col1 moreP">User type <span class="required">*</span></td>
				<td class="col2">
				<input type="radio" name="by_line" value="1" <?php if($data['by_line']==1){ ?> checked="checked" <?php } ?>> By Line
				<input type="radio" name="by_line" value="2" <?php if($data['by_line']==2){ ?> checked="checked" <?php } ?>> CMS user
				<input type="radio" name="by_line" value="3" <?php if($data['by_line']==3){ ?> checked="checked" <?php } ?>> Both
				</td>
				<td class="col3">
				<label class="error" id="by_line_error">&nbsp;&nbsp;Please select user type</label>
                </td>
			</tr>
			<tr>
				<td class="col1 moreP">Name <span class="required">*</span></td>
				<td class="col2"><input type="text" value="<?php echo isset($data)?$data['name']:''; ?>" name="name" id="nameid" <?php echo $_GET['id']!=''?'readonly="readonly"':''; ?> class="inputForm"  onblur="checkauthnameexist();"/><br/><font color="#f00">Once saved, author name cannot be edited and should not contain any special character</font><input type="hidden" value="<?php echo isset($data)?$data['name']:''; ?>" name="oldname" id="oldnameid" class="inputForm" /></td>
				<td class="col3">
                	<label class="error" id="name_error">&nbsp;&nbsp;Please Enter Name</label>
                    <label class="error1" style="font-style: italic;padding-left: 20px;color: red;display:none" id="nameavailable_error">&nbsp;&nbsp;Name Already Exists</label>
                </td>
			</tr>
			<tr>
				<td class="col1 moreP">Email <span class="required">*</span></td>
				<td class="col2"><input value="<?php echo isset($data)?$data['email']:''; ?>" type="text" name="email" id="emailid" class="inputForm email" onblur="checkemailexist();"/></td>
				<td class="col3"><label class="error" id="email_error">&nbsp;&nbsp;Please Enter Email-Address</label>
							<label class="error" id="invalidemail_error">&nbsp;&nbsp;Please Enter Valid Email-Address</label>
							<label class="error" id="existemail_error">&nbsp;&nbsp;Email-Address already exists</label>
				</td>
			</tr>
			<tr class='hide'>
				<td class="col1 moreP">User Name <span class="required">*</span></td>
				<td class="col2">
					<input type="text" value="<?php echo isset($data)?$data['username']:''; ?>" name="username" id="usernameid" class="inputForm" onblur="checkavailable()" />
					<input type="hidden" name="oldusername" id="oldusernameid" /></td>
				<td class="col3"><label class="error" id="username_error">&nbsp;&nbsp;Please Enter User-Name</label> 
				<label class="error1" id="usernameavailable_error" style="font-style: italic;padding-left: 20px;color: red;display:none;">&nbsp;&nbsp;Username Already Exists</label></td>
			</tr>
			<tr id="addpassword" class='hide'>
				<td class="col1 moreP">Password <span class="required">*</span></td>
				<td valign="top" class="col2"><input type="password" name="password" id="passwordid" class="inputForm" /></td>
				<td class="col3"><label class="error" id="password_error">&nbsp;&nbsp;Please Enter Password</label>
				<label class="error" id="invalidpassword_error">&nbsp;&nbsp;Password should contain atleast 3 alphabets,3 numbers and 2 special chars</label>
				<label id="passwordinfo" class="info">Password should contain atleast 3 alphabets, 3 numbers and 2 special characters.</label> 
				</td>
			</tr>
			<tr id="editpassword" style="display:none;">
				<td class="col1"><a id="changepassid" href="javascript: void(0);" onclick="">Change Password</a></td>
				<td class="col2">&nbsp;</td>
				<td class="col3"><label class="error" id="password_error">&nbsp;&nbsp;Please Enter Password</label>
				<label class="error" id="invalidpassword_error">&nbsp;&nbsp;Password should contain atleast 3 alphabets,3 numbers and 2 special chars</label></td>
			</tr>
				
			<tr class='hide'>
				<td valign="top" class="col1 moreP">Module <span class="required">*</span></td>
				<td class="col2"><select class="select" name="cmsmodules_id[]" id="modules" multiple="multiple" size="6">
				<option value="0" >Select Module</option>
	<?php
				echo getModulesList();
	?>												
				</select></td>
				<td class="col3"><label class="error" id="module_error">&nbsp;&nbsp;Please select module name</label></td>
			</tr>
			<tr class='hide'>
				<td class="col1 moreP">Rights <span class="required">*</span></td>
				<td class="col2" style="width:28%;">
						<input type="radio" name="rights" id="readonly" value="0" checked="checked" onclick="setDefultRights(this.value)" /> Read Only, 
						<?php if( RIGHTS != 2){ ?>
							<input type="radio" name="rights" id="admin" value="1" onclick="setDefultRights(this.value)" /> Admin,
						<?php } ?>
						<input type="radio" name="rights" id="author" value="2" onclick="setDefultRights(this.value)" /> Author
						
						<input type="radio" name="rights" id="developer" value="3" onclick="setDefultRights(this.value)" /> Developer<br /> 
						
				</td>
				<td class="col3"><label class="error" id="right_error">&nbsp;&nbsp;Please select rights</label></td>
			</tr>
			<tr>
				<td class="col1 moreP">&nbsp;</td>
				<td class="col2">
					<div id='accesstype' style='display:none;'>
						<input type="checkbox" name="rights_add" id="rights_add" value="1"  /> Add &nbsp; 
						<input type="checkbox" name="rights_edit" id="rights_edit" value="1"  /> Edit &nbsp;
						<input type="checkbox" name="rights_del" id="rights_del" value="1" /> Delete &nbsp;						
						<input type="checkbox" name="rights_pub" id="rights_pub" value="1"  /> Publish &nbsp;
						<input type="checkbox" name="rights_feature" id="rights_feature" value="1" /> Featured &nbsp;			
						
					</div>
				</td>
				<td class="col3"><label class="error" id="right_error">&nbsp;&nbsp;Please select rights</label></td>
			</tr>
			<tr>
				<td class="col1">Image </td>
				<td class="col2">
                        <?php echo mpShowImageForEdit($filename, 'authorthumbnail', 'thumbnail', $thumbnail); ?>          
                                            <input type="hidden" value="<?php echo $thumbnail; ?>" name="oldauthorthumbnail" id="oldauthorthumbnail" class="hidden"  />                <span class="h1tdB1" id="dataimage"></span><span id="loading1" style="display:none;" class="loading"><img src="<?php echo IMAGEPATH; ?>/L.gif" id="dataimageid1" alt="loading" /></span>
    			</td>
				<td class="col3"> 
				<span class="loading" id="loading" style="display:none;"><img src="<?php echo IMAGEPATH;?>/L.gif" id="dataimageid" alt="" /></span>
				<label id="authorthumbnailinfo" class="info">Image should having .jpg or .gif filetype with minimum resolution of <?php echo end($sizearray['author']['width'])."x".end($sizearray['author']['height']);?> or more.</label></td>
			</tr>
            <tr>
				<td valign="top" class="col1">Designation </td>
				<td class="col2"><input value="<?php echo isset($data)?$data['designation']:''; ?>" type="text" name="designation" id="designation" class="inputForm" />
				</td>
				<td class="col3">&nbsp;</td>
			</tr>
			<tr>
				<td valign="top" class="col1">Bio-Data </td>
				<td class="col2"><textarea name="biodata" id="biodata" style="width: 100%;" class="textarea"><?php echo isset($data)?$data['biodata']:''; ?></textarea>
				</td>
				<td class="col3">&nbsp;</td>
			</tr>
			<tr>
				<td class="col1">&nbsp;</td>
				<td align="center" class="save">
				<input type="image" id="savebutton" src="<?php echo IMAGEPATH;?>/btn-save.gif" />&nbsp;&nbsp;
				<img onclick="location.href='display.php';" style="cursor: pointer;" src="<?php echo IMAGEPATH;?>/btn-cancel.gif" /> 
				<span id="formloading" style="display:none;"><img src="<?php echo IMAGEPATH;?>/indicator_2.gif" border="0" alt="indicator" /></span></td>
				<td class="col3">&nbsp;</td>
			</tr>
			</table>
			</form>
			<br clear="all" />
        </div>
                                                            
                                                            
                                                            </div>
							</td>
							<td width="10" class="boxMiddleRight">&nbsp;</td>
						</tr>
						<tr>
							<td class="boxbottomLeft"></td>
							<td class="boxbottomMiddle"></td>
							<td class="boxbottomRight"></td>
						</tr>
					</table></td>
				</tr>
			</table>
		</div>
       <script>
        $('#changepassid').click(function(){
			ModalBox.open(CMSSITEPATH+'/author/changepassword.php?id=<?php echo $_GET['id']; ?>',700,500);
		});
        if('<?php echo $data['password'] ?>'!='') {
		    $('#addpassword').hide();
		    document.getElementById('editpassword').style.display='block';
	    }
		var modulearray=<?php echo json_encode($modules_array); ?>;	
		$('#modules').val(modulearray);
		
		var pageidarray=<?php echo json_encode($pageid_array); ?>;	
		$('#pageid').val(pageidarray);
          
		        
		var rights='<?php echo $data['rights']!=''?$data['rights']:''; ?>';
		if(rights =='2'){
			$('#accesstype').show();
			$('#author').attr('checked','checked');
			
		}else if(rights =='1'){
			$('#accesstype').show();
			$('#admin').attr('checked','checked');		

		}else if(rights =='3'){
			$('#accesstype').show();
			$('#developer').attr('checked','checked');		

		}else{
			$('#accesstype').hide();
			$('#readonly').attr('checked','checked');
		}
        var rightsArray = '<?php echo $data['rightsmod']!=''?$data['rightsmod']:''; ?>';
		(rightsArray[0] == '1')?$('#rights_add').attr('checked',true):$('#rights_add').attr('checked',false);
		(rightsArray[1] == '1')?$('#rights_edit').attr('checked',true):$('#rights_edit').attr('checked',false);
		(rightsArray[2] == '1')?$('#rights_del').attr('checked',true):$('#rights_del').attr('checked',false);
		(rightsArray[3] == '1')?$('#rights_pub').attr('checked',true):$('#rights_pub').attr('checked',false);
		(rightsArray[4] == '1')?$('#rights_feature').attr('checked',true):$('#rights_feature').attr('checked',false);
		$("input:radio[name=by_line]").click(function() {
			var value = $(this).val();
			hideshow(value);
			checkauthnameexist();
		});
		function hideshow(value)
		{
			if(value==1)
			{
				$('.hide').hide();
				$('#accesstype').hide();
			}
			else
			{
				$('.hide').show();
				$('#accesstype').show();
			}
		}
		hideshow(<?php echo $data['by_line'];?>);
       </script>
		<?php
		include_once (CMSROOTPATH . "/incFooter.php");
		?>
	</body>
</html>
