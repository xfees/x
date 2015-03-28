<?php
/***** INCLUDE CONNECTION FILE ************************************************************************************/
include_once ('config.php');
$_SESSION['TOPMENU'] = "author";
$filename = strtolower($_SESSION['TOPMENU']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Author Management</title>
		<link href="<?php echo CSSFILEPATH;?>/cms.css" rel="stylesheet" type="text/css" />
		<!-- *****  Include the CSS FILES ********************************************************************************-->
		<!-- *****  Include the JS FILES & Functions *********************************************************************-->
		<script type="text/javascript" src="popupjquery/highslide-with-html.js"></script>
		<script type="text/javascript">
			hs.graphicsDir = '../popupjquery/graphics/';
			hs.outlineType = 'rounded-white';
			hs.outlineWhileAnimating = true;

		</script>
		<script type='text/javascript' src='<?php echo JSFILEPATH;?>/jquery.js'></script>
		<script type='text/javascript' src='<?php echo JSFILEPATH;?>/ajaxfileupload.js'></script>
		<script type='text/javascript' src='<?php echo JSFILEPATH;?>/common.js'></script>
		<script type='text/javascript' src='author.js'></script>
		<script>
			function getContent() {
				var name = Trimnew($('#nameid').val());
				var email = $('#emailid').val();
				var author = $('#author').attr('checked');
				var authorthumbnail = $('#authorthumbnail').val();
				var flag = 0;
				var sendreqfuc = 0;
				if(name == "") {
					alert('Please enter name!');
					$('#nameid').focus();
					flag = 1;
				} else if(email == "") {
					alert('Please enter email!');
					$('#emailid').focus();
					flag = 1;
				} else if(email != "") {
					emailvalid = isValidEmail(email);
					if(emailvalid != true) {
						alert('Please enter valid email!');
						$('#emailid').focus();
						flag = 1;
					}
				}

				if(flag == 0) {//**** If there is an Image to be uploaded, upload & add it to the datastring
					if(authorthumbnail != "") {
						var resultcase = isValidImage(authorthumbnail);
						switch (resultcase) {
							case 1:
								alert('The uploaded file is not a valid Image. Please upload only .jpg or .gif');
								sendrequest();
								break;
							case 2:
								alert('The uploaded filename is not valid');
								sendrequest();
								break;
							case 0:
								ajaxFileUpload('authorthumbnail', 'authorthumbnail', "author");
								sendreqfuc = 1;
								break;
						}
					}
					$('#savebutton').attr('disabled', 'disabled');
					$('#formloading').show();
					$('#dataimage').html('');

					if(authorthumbnail == "" && sendreqfuc == 0) {
						sendrequest();
					}
				}
			}

			function sendrequest(imagename) {
				var datastring = $('form').serialize();
				if( typeof (imagename) != 'undefined' && imagename != "") {
					datastring = datastring + "&thumbnail=" + imagename;
				}
				datastring += "&update=true";
				//alert(datastring)
				$.ajax({
					type : "POST",
					url : "author/getauthor.php",
					data : datastring,
					success : displayContent
				});

				$('#addpassword').show();
				$('#editpassword').hide();
			}

			function displayContent(success) {
				var jObj=eval("("+success+")"); 
				$("#status").html("Author is added successfully !");
				parent.load_authors(jObj.id);
				parent.window.hs.getExpander().close();
			}
		</script>
	</head>
	<body>
		<?php
		if (isset($_POST['action']) || !empty($_POST['action'])) {
			$conn = Database::Instance();
			
			$added_author_id = $_POST['added_author_id'];
			
			$conn -> query("select id,name from author");
			while ($authors_data = $conn -> fetch()) {
				$authors[] = $authors_data;
			}

			$result = '';
			$result .= '<option value="">Select One</option>';

			foreach ($authors as $author) {
				if($added_author_id==$author['id'])
				$result .= '<option value="' . $author['id'] . '" selected="selected">' . $author['name'] . '</option>';
				else
				$result .= '<option value="' . $author['id'] . '">' . $author['name'] . '</option>';
			}

			$result .= '<option onclick="return hs.htmlExpand(this, {objectType:\'iframe\' ,src:\'../quick_add_author.php\',onAfterClose :\'caller();\'});"  value="">Create New</option>';
			echo $result;
			exit;
		}
		?>
		<div class="content" style="padding: 0px;">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top">
					<div id="status" style="text-align: center;font-weight: bold;">
						&nbsp;
					</div></td>
				</tr>
				<tr>
					<td valign="top" class="rightPanel">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="box">
						<tr>
							<td width="10" class="boxMiddleLeft"></td>
							<td class="boxMiddleMiddle">
							<div id="editcontent">
								<div class="line"></div>
								<form name="dataform" id="dataform">
									<input type="hidden" value="a" name="action" id="action" class="hidden" />
									<input type="hidden" value="" name="id" id="id" class="hidden" />
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="col1 moreP">Name <span class="required">*</span></td>
											<td class="col2">
											<input type="text" name="name" id="nameid" class="inputForm" />
											</td>
											<td class="col3"><label class="error" id="name_error">&nbsp;&nbsp;Please Enter Name</label></td>
										</tr>
										<tr>
											<td class="col1 moreP">Email <span class="required">*</span></td>
											<td class="col2">
											<input type="text" name="email" id="emailid" class="inputForm email" />
											</td>
											<td class="col3"><label class="error" id="email_error">&nbsp;&nbsp;Please Enter Email-Address</label><label class="error" id="invalidemail_error">&nbsp;&nbsp;Please Enter Valid Email-Address</label></td>
										</tr>
										<tr>
											<td class="col1">Image </td>
											<td class="col2">
											<input type="file" name="authorthumbnail" id="authorthumbnail" value="" />
											<input type="hidden" name="authoroldthumbnail" id="authoroldthumbnail" class="hidden" />
											<div style="position:relative;width:100px;">
												<span class="h1tdB1" id="dataimage"></span>
											</div></td>
											<td class="col3"><span class="loading" id="loading" style="display:none;"><img src="<?php echo IMAGEPATH;?>/L.gif" id="dataimageid" alt="" /></span><label id="authorthumbnailinfo" class="info">Image should having .jpg or .gif filetype with resolution of 120x90 or more.</label></td>
										</tr>
										<tr>
											<td valign="top" class="col1">Designation </td>
											<td class="col2">
											<input type="text" name="designation" id="designation" class="inputForm" />
											</td>
											<td class="col3">&nbsp;</td>
										</tr>
										<tr>
											<td valign="top" class="col1">Bio-Data </td>
											<td class="col2">											<textarea name="biodata" id="biodata" style="width: 100%;"></textarea></td>
											<td class="col3">&nbsp;</td>
										</tr>
										<tr>
											<td valign="top" class="col1">Choose Section </td>
											<td class="col2"><?php
											$objSection = new Section();
											$sectionsArray = $objSection -> getSectionTree();
											?>
											<select id="section_id" name="section_id" class="select" style="width: 181px;">
												<option value="">Select One</option>
												<?php foreach($sectionsArray as $sectionId => $section ){
if(trim($section)!=''){
												?>
												<option value="<?php echo $sectionId;?>"><?php echo trim($section);?></option>
												<?php }
														}
												?>
											</select></td>
											<td class="col3">&nbsp;</td>
										</tr>
										<tr>
											<td valign="top" class="col1">Is Columnist </td>
											<td class="col2">
											<input type="checkbox" name="is_columnist" id="is_columnist" />
											</td>
											<td class="col3">&nbsp;</td>
										</tr>
										<tr>
											<td class="col1 moreP">&nbsp;</td>
											<td class="col2">
											<div id='accesstype' style='display:none;'>
												<input type="checkbox" name="rights_add" id="rights_add" value="1"  />
												Add &nbsp;
												<input type="checkbox" name="rights_edit" id="rights_edit" value="1"  />
												Edit &nbsp;
												<input type="checkbox" name="rights_del" id="rights_del" value="1" />
												Delete &nbsp;
												<input type="checkbox" name="rights_pub" id="rights_pub" value="1"  />
												Publish &nbsp;
												<input type="checkbox" name="rights_feature" id="rights_feature" value="1" />
												Featured &nbsp;
											</div></td>
											<td class="col3"><label class="error" id="right_error">&nbsp;&nbsp;Please select rights</label></td>
										</tr>
										<tr>
											<td class="col1">&nbsp;</td>
											<td align="center" class="save"><img id="savebutton" src="<?php echo IMAGEPATH;?>/btn-save.gif" onclick="getContent();"/>&nbsp;&nbsp; <img onclick="getReset();" src="<?php echo IMAGEPATH;?>/btn-cancel.gif" /><span id="formloading" style="display:none;"><img src="<?php echo IMAGEPATH;?>/indicator_2.gif" border="0" alt="indicator" /></span></td>
											<td class="col3">&nbsp;</td>
										</tr>
									</table>
								</form>
								<br clear="all" />
							</div>
							<div id="displaycontent" class="padding12">
								<div class="sorting"></div>
								<!--content display starts here -->
							</div><!-- content display ends here --></td>
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
	</body>
</html>