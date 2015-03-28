<?php
//Include Configuration Files
include_once ('../config.php');
$_SESSION['TOPMENU'] = "category";
$filename = strtolower($_SESSION['TOPMENU']);
//Initialization of Database Object
$conn = Database::Instance();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Section Management</title>    
    <?php include_once '../incHeaderScript.php'; ?>	 	
    <script type='text/javascript' src='<?php echo $filename ?>.js'></script>
    <script type='text/javascript'>
      $(document).ready(function(){
        var id;
        $(":input[type=text]").focus(function () {
          id = this.id;
        });
        $(":input[type=text]").keyup(function(event){ 
          if(event.keyCode==13) {  
            $('#'+id).trigger('blur');
          } 
        });
      });
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
    <?php
    include_once (CMSROOTPATH . "/topmenu.php");
    ?>
    <div class="content">
      <div class="title">
        Category Management
      </div>
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
                  <div class="leftlinks">
                    <?php
                    include_once (CMSROOTPATH . "/leftmenu.php");
                    ?>
                  </div>
                </td>
                <td width="10" class="boxMiddleRight"></td>
              </tr>
              <tr>
                <td class="boxbottomLeft"></td>
                <td class="boxbottomMiddle"></td>
                <td class="boxbottomRight"></td>
              </tr>
            </table>
          </td>
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
                    <a <?php echo (RIGHTS != 0) ? 'id="addcontent"' : ''; ?> style="cursor:pointer;" onclick="javascript: $('#sectionname').removeAttr('readonly');"> <span class="iconAdd">&nbsp;</span> <span id="addEditText">Add New </span> <?php echo $_SESSION['TOPMENU']; ?></a>
                    <div id="divTrash" style="float:right">
                      <a href="javascript: void(0);" onclick="getTrash();"> <span class="iconTrash">&nbsp;</span> Trash Can</a>
                    </div>
                    <span id="backtomodule" style="margin-right:5px;" onclick="javascript: $('#sectionname').removeAttr('readonly');"></span>
                  </div>
                  <div id="editcontent" style="display:none;">
                    <div class="contentBox clearfix">
                      <form name="dataform">
                        <input type="hidden" value="a" name="action" id="action" class="hidden" />
                        <input type="hidden" value="" name="id" id="id" class="hidden" />
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td class="col1 moreP">Category Name <span class="required">*</span></td>
                            <td class="col2">
                              <input name="name" type="text" class="inputForm" id="sectionname" maxlength="50" /><br/><font color="#f00">Once saved, section name cannot be edited and should not contain any special character</font>
                              <input type="hidden" value="" name="old_sectionname" id="old_sectionname" />
                            </td>
                            <td class="col3"><label class="error" id="sectionname_error">&nbsp;&nbsp;Please
                                Enter Section Name</label><label class="error1" id="sectionnameavailable_error" style="font-style: italic;padding-left: 20px;color: red;display:none;">&nbsp;&nbsp;Section name Already Exists</label></td>
                          </tr>
                          <tr>
                            <td class="col1">Parent Category </td>
                            <td class="col2" id="tdSection">
                              <?php
                              $objCategory = new Category();
                              $categoryArray = $objCategory->getCategoryTree();
                              ?>
                              <select style="width: 175px;" id="parentsectionname" name="parentid" class="select">
                                <option value="">--SELECT PARENT ID--</option>
                                <?php foreach ($categoryArray as $sectionId => $section) { ?>
                                  <option label="<?php echo $strTitle; ?>" value="<?php echo $sectionId; ?>" <?php if ($_POST['searchByCategory'] == $sectionId) { ?> selected="selected" <?php } ?> ><?php echo $section; ?></option>
                                <?php } ?>
                              </select>
                            </td>
                            <td class="col3">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="col1">Meta Title</td>
                            <td class="col2">
                              <input name="metatitle" type="text" class="inputForm" id="metatitle"  maxlength="600"/>
                            </td>
                            <td class="col3">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="col1">Meta Keywords</td>
                            <td class="col2">												<textarea name="metakeyword" id="metakeyword" rows="8" cols="45"></textarea></td>
                            <td class="col3">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="col1">Meta Description</td>
                            <td class="col2">												<textarea name="metadescription" id="metadescription" rows="8" cols="45"></textarea></td>
                            <td class="col3">&nbsp;</td>
                          </tr>
                          <tr valign="top">
                            <td class="col1">Logo</td>
                            <td class="col2">
                              <input type="file" name="sectionthumbnail" id="sectionthumbnail" />
                              <input type="hidden" name="oldimage" id="oldimageid" />
                              <div style="position:relative;width:100px;" id='imagecrossdiv'>                                
                                <a href='#' id='crossImg' class='cross' onclick="cleardivimag('')"></a>
                                <span class="h1tdB1" id="dataimage"></span>		
                              </div>
                            </td>
                            <td class="col3"><label id="imageinfo" class="info">Image should having .jpg or .gif filetype with minimum resolution of <?php echo end($sizearray['section']['width']) . "x" . end($sizearray['section']['height']); ?> or more.</label> &nbsp;<span class="h1tdB1" id="dataimage"></span></td>
                          </tr>
                          <tr valign="top">
                          	<td class="col1">
                          		Make as Tab
                          	</td>
                          	<td class="col2">
                          		<input type="checkbox" name="is_tab" id="is_tab" value="1"/>
                          	</td>
                          	<td class="col3">&nbsp;</td>
                          </tr>
                          <tr>
                            <tr>
                              <td class="col1">&nbsp;</td>
                              <td align="center" class="save"><img id="savebutton" src="<?php echo IMAGEPATH; ?>/btn-save.gif" onclick="getContent()" />&nbsp;&nbsp; <img onclick="getReset()" src="<?php echo IMAGEPATH; ?>/btn-cancel.gif" /><span id="formloading" style="display:none;"><img src="<?php echo IMAGEPATH; ?>/indicator_2.gif" border="0" alt="indicator" /></span></td>
                              <td class="col3">&nbsp;</td>
                            </tr>
                        </table>
                      </form>
                    </div>
                  </div>
                  <div id="displaycontent" class="padding12">
                    <div class="sorting"></div>
                    <!--content display starts here -->
                    <?php
                    require_once(CMSROOTPATH . '/section/searchform.php');
                    ?>
                    <div id="mainContainer">
                      <?php
                      include_once ('getsection.php');
                      ?>
                    </div>
                  </div></td>
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
    <?php
    include_once (CMSROOTPATH . "/incFooter.php");
    ?>
  </body>
</html>
