<?php
include '../../config.php';

if(isset($sizearrayPlugin[$_GET['m']][$_GET['u']]) && count($sizearrayPlugin[$_GET['m']][$_GET['u']]['width']) > 0) {
  $sizes = $sizearrayPlugin[$_GET['m']][$_GET['u']];
  $maxW = $sizes['width'][0];
  $maxH = $sizes['height'][0];    
} else if($_GET['m'] == 'blocks' && $_GET['u'] == 'thumbnail') {
  $sizes = $sizearrayPlugin[$_GET['m']][$_GET['u']][$_GET['bid']];
  $maxW = $sizes['width'][0];
  $maxH = $sizes['height'][0];
} else {
  $maxW = 0;
  $maxH = 0;
}

if($_GET['u'] == 'gallary' && $_GET['cType'] != PHOTOGALLERY) {
  //$maxW = 0;
  //$maxH = 0;
}

if($_GET['u'] == 'editor') {
  if($_GET['cType'] == NEWS || $_GET['cType'] == COLUMN) {
    //$maxW = 0;
    //$maxH = 0;
  } else {
    $maxW = 540;
    $maxH = 540;
  }  
} 

$bid = '';
$rid = '';
$image = '';
$alt = '';
if(isset($_GET['bid'])) {
  $bid = $_GET['bid'];
}
if(isset($_GET['rid'])) {
  $rid = $_GET['rid'];
}
if(isset($_GET['image'])) {
  //$image = $_GET['image'];
}
if(isset($_GET['alt'])) {
  //$alt = $_GET['alt'];
}

?><head>
	<?php  include_once CMSROOTPATH.'/incHeaderScript.php'; ?>
	<script type='text/javascript' src='<?php echo JSFILEPATH;?>/tiny_mce/tiny_mce.js'></script>
	<script type="text/javascript" src="<?php echo JSFILEPATH; ?>/ajaxfileupload.js"></script>	
	<script type="text/javascript" src="<?php echo CMSSITEPATH; ?>/plugins/media/media.js"></script>	 
	<script type='text/javascript' src='<?php echo JSFILEPATH; ?>/jcrop.js'></script>
	<link rel="stylesheet" href="<?php echo CSSFILEPATH; ?>/jcrop.css" type="text/css" />
	
	<script type="text/javascript">
		GlobalSearch.disabled(true);
	</script>
</head>

<div id="plugin-area">
	<div id="upload-search" class="uploadSearch" style="padding: 10px; border-bottom: 1px solid; width:98%">
		<input type="hidden" name="mName" id="mName" value="<?php echo $_GET['m']?>"/>
		<input type="hidden" name="uType" id="uType" value="<?php echo $_GET['u']?>"/>
		<input type="hidden" name="cName" id="cName" value="<?php echo $_GET['c']?>"/>
      
      <input type="hidden" name="bid" id="bid" value="<?php echo $bid?>"/>
		<input type="hidden" name="rid" id="rid" value="<?php echo $rid?>"/>
      <input type="hidden" name="contentType" id="contentType" value="<?php echo $_GET['cType']?>"/>
      <?php /*		
		<input type="hidden" name="section" id="section" value="<?php echo $_GET['section']?>"/>
      */ ?>
		<input type="hidden" name="mW" id="mW" value="<?php echo $maxW?>"/>
		<input type="hidden" name="mH" id="mH" value="<?php echo $maxH?>"/>
        <?php 
		// this condition is used only when we dont want to crop the just add the module name in the if condition.
		if($_GET['m'] == 'moods'){ ?>
        <input type="hidden" value="1" name="noCrop" id="noCrop" class="hidden" />
       <?php } ?>
		<label>Upload Image</label> <input type="file" class="inputForm1" name="uploadImage" id="uploadImage" style="width:auto;" onchange="mpUploadImage()" />
		OR		
		<input type="text" name="searchImage" class="txtSearch" id="searchImage"> 
		<input type="button" value="Search" class="btnSearch" onclick="mpSearchImages(0, 1)">
		<?php if($maxH > 0 && $maxW > 0 && $_GET['u'] != 'gallary' && $_GET['u'] != 'editor' && $_GET['u'] != 'article') {?>
		<span style="padding-left:30px; color: red">Please upload / select image of size min <strong><?php echo $maxW.' x '.$maxH;?></strong> (width x height) and of ratio <strong><?php echo number_format($maxW / $maxH , 2)?></strong></span>
		<?php } else if($_GET['cType'] == PHOTOGALLERY || $_GET['u'] == 'editor' || $_GET['u'] == 'article') { ?>
      <span style="padding-left:30px; color: red">Please upload / select image of size min <strong><?php echo $maxW ?></strong> (width)</span>
    <?php } else if($_GET['cType'] == PICTURESTORY) { ?>
      <span style="padding-left:30px; color: red">Please upload / select image of size max <strong><?php echo $maxW ?></strong> (width)</span>
    <?php } ?>
		<span id="loader" style="float:right; display: none"><img src="<?php echo IMAGEPATH;?>/ajax-loader-circle.gif" alt="Loading" title="Loading"/>&nbsp;&nbsp;Please wait..</span>		
	</div>
	
  <?php if(!empty ($image)) { ?>
   <div id="result-area" style="display: none; height: 440px;">
	</div>
   <div id="addMedia" style="padding: 20px; height: 400px">
  <?php } else { ?>
   <div id="result-area" style="height: 440px;">
	</div>
	<div id="addMedia" style="display: none ; padding: 20px; height: 400px">
  <?php } ?>
    	<div id="">
    		<input type="hidden" id="url" name="url" value=""/>
    		<input type="hidden" id="origonal_url" name="origonal_url" value=""/>
    		<input type="hidden" id="downloaded_url" name="downloaded_url" value=""/>
    		<input type="hidden" id="is_editorial" name="is_editorial" value=""/>
    		<input type="hidden" id="is_stored" name="is_stored" value=""/>				
    		<div style="float: left; padding: 10px;" id="add-image">
    		<?php if($_GET['u'] != 'gallary') {?>
        		<table style="font-size: 12px" cellpadding="5px">
        			<tbody>
            			<tr>
            				<td rowspan="1" valign="top">
            					<div style="margin-bottom:10px; border: 1px grey solid">
                             <?php 
                             $timg = '';
                             if(!empty ($image)) {
                              $timg = SITE_MEDIA_URL.'/'.$image; 
                             }
                             ?>
                    				<img id="pImg" src="<?php echo $timg;?>" height="100px" width="100px" />				
                    			</div>
                    			<div>
                    				<a href="#" onclick="mpLoadImageCrop()"><img src="<?php echo IMAGEPATH?>/media-crop.png" alt="Crop" title="Crop"/></a>
                    			</div>
            				</td>
            				<td valign="top">
            					<span>Image Alt*</span><br /> 
            					<span><input type="text" id="pImgAlt" name="pImgAlt" style=" width: 200px" value="<?php echo $alt; ?>"/> </span><br />
                           <span>Caption</span><br /> 
            					<span>
                             <textarea id="pImgCaption" name="pImgCaption" style="height:100px; width: 200px"></textarea>                             
                           </span><br />
            				</td>
                        <td valign="top">
            					
            				</td>
            			</tr>            			
            			<tr>
            				<td colspan="3" align="center">
            					<input type="button" value="Use Image" onclick="mpUseImage()"   />
            					<input type="button" value="close" onclick="mpCloseAddImage()"   />
            				</td>
            			</tr>
        			</tbody>
        		</table>
        		<?php } else {?>
        		<table style="font-size: 12px" cellpadding="5px">
        			<tbody>
            			<tr>
            				<td rowspan="4" valign="top">
            					<div style="margin-bottom:10px; border: 1px grey solid">
                    				<img id="pImg" src="" height="100px" width="100px" />				
                    			</div>
                    			<div>
                    				<a href="#" onclick="mpLoadImageCrop()"><img src="<?php echo IMAGEPATH?>/media-crop.png" alt="Crop" title="Crop"/></a>
                    			</div>
            				</td>
            				<td valign="top">
            					<span>Image Alt*</span><br /> 
            					<span><input type="text" id="pImgAlt" name="pImgAlt" style=" width: 200px"/> </span><br />
            				</td>	
            				<td>
            					<span>Credit</span><br />
            					<span><input type="text" id="pCredit" name="pCredit" style=" width: 200px"/> </span><br />		
            				</td>				
            			</tr>
            			<tr>
            				<td>
            					<span>Keywords</span><br />
          						<span><input type="text" id="pKeywords" name="pKeywords" style=" width: 200px"/> </span>
          					</td>            				
            				<td>
            					<span>Byline/Author</span><br /> 
          						<span><input type="text" id="pAuthor" name="pAuthor" style=" width: 200px"/> </span><br />		
            				</td>
            			</tr>  			            				
            			<tr>				  				
                        <td>	
            					<span>Publish Date</span><br /> 
          						<span><input type="text" id="pDate" name="pDate"  class="calendar2 inputWizard2 search-date" style="height:25px"  style=" width: 200px"/> </span><br />		
            				</td>
                        <td>
            					<span>Headline</span><br />
          						<span><input type="text" id="pHeadline" name="pHeadline" style=" width: 200px"/> </span>
          					</td>                        
            			</tr>
            			<tr>  				  				  				
            				<td valign="top" colspan="2">
            					<span>Caption*</span><br /> 
          						<span><textarea id="pCaption" name="pCaption" style="height:100px; width: 200px"></textarea></span>
            				</td>
            			</tr>
            			
            			<tr>
            				<td colspan="3" align="center">
            					<input type="button" value="Use Image" onclick="mpUseImage()"   />
            					<input type="button" value="close" onclick="mpCloseAddImage()"   />
            					<input type="hidden" value="-1" name="isEdit" id="isEdit" />
            					<input type="hidden" value="" name="id" id="id" />                           
            				</td>
            			</tr>
        			</tbody>
        		</table>
        		<?php } ?>
    		</div>
    		<div id="crop-image" style="float: left; padding: 10px; display: none; width: 100%">    
    			<div>
              <div style="float: left">
                <div style="width:100px;height:100px;overflow:hidden; margin-right: 10px">
                  <img src="" id="preview" />
                </div>  
                <div style="margin-top:30px; display: none">
                  Selected Width <br/>
                  <input type="text" size="4" id="w" name="width" readonly="readonly"/> <br/>
                  Selected Height <br/>
                  <input type="text" size="4" id="h" name="height" readonly="readonly"/>
                </div>
              </div>      				
              <div id="d-image" style="border: 0px solid; width: 500px; height: 350px; overflow: auto; float: left">
                <img id="c-img" src=""/>
              </div>
              <div style="float: left">                    
                 <input type="hidden" id="x1" name="left" />
                 <input type="hidden" id="y1" name="top" />                
                 <input type="hidden" id="x2" name="right" />
                 <input type="hidden" id="y2" name="bottom" /> 
                 <input type="hidden" value="0" name="is_cropped" id="is_cropped" />
                 Width : <span id="cropW">0</span><br/>
                 Height : <span id="cropH">0</span>
              </div>
              <div style="clear:both"></div> 
            </div>
            <div style="padding-left: 100px">
              <input type="button" value="Crop" style="width: 98px; margin-top: 10px;" onclick="mpCropImage()"/>
              <input type="button" value="Undo" style="width: 98px; margin-top: 10px;" onclick="mpUndoCropImage()"/>
              <input type="button" value="Back" style="width: 98px; margin-top: 10px;" onclick="mpBackFromCrop()"/>
            </div>          
    		</div>
    		<div style="clear:both"></div>		
    	</div>	
    </div>
    <div style="clear:both" ></div>
    <?php if($_GET['u'] == 'gallary') {?>
    <div id="gallary-strip" style="height: 180px; ">
    	<div style="background-color:#ccc; border: 1px solid; padding: 5px">
    		<div style="float: left">Slides</div>
    		<div style="float: right"><a href="#" onclick="mpUseImageStrip()">Done</a></div>
    		<div style="clear:both"></div>
    	</div>
    	<div id="gallary-strip-place" style="height: 160px; ">
    		<input type="hidden" name="imageCount" value="0" id="imageCount">
    		<input type="hidden" name="imageOrder" value="" id="imageOrder">
    		<div style="height: 120px; width: 800px; overflow-x: auto;">
    			<ul id="gallary-images" style="height: 100px; width: 80px;"></ul>
    		</div>
    	</div>
    </div>
    <?php } ?>
</div>