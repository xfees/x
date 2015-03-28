<?php
/***** INCLUDE CONNECTION FILE **********************************************************************/
include_once('config.php');

/*****  SETTING COOKIE VARIABLES **********************************************************************/
$_SESSION['TOPMENU'] = "Homepage";
$filename = strtolower($_SESSION['TOPMENU']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo WEB_SITE_NAME;?></title>
	<!-- *****  Include the CSS FILES *********************************************************************-->
	<style type="text/css">@import url(<?php echo CSSFILEPATH;?>/cms.css);</style>
	<!-- *****  Include the JS FILES & Functions *********************************************************************-->
	<script type='text/javascript' src='<?php echo JSFILEPATH;?>/jquery.js'></script>
	<script type='text/javascript' src='<?php echo JSFILEPATH;?>/jquery.plugin.js'></script>
	<script type='text/javascript' src='<?php echo JSFILEPATH;?>/common.js'></script>
	<script type='text/javascript' src="<?php echo JSFILEPATH;?>/tools.js"></script>
	<script type='text/javascript' src='<?php echo JSFILEPATH ?>/globalSearch.js?v=1.1'></script>
    <script type="text/javascript" src="<?php echo JSFILEPATH?>/jquery.simplemodal-1.4.1.js"></script>
	<link type="text/css" href="<?php echo CSSFILEPATH?>/simpleModal.css" rel="stylesheet" />
	
</head>
<body>
<?php
	include_once('topmenu.php');
?>
<div class="content" style="margin-top:10px;">	
	<div class="rightPanel" id="displaycontent">
		<div id="mainContainer">
		<!-- table start -->
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="box">
		  <tr> 
			<td width="10" class="boxtopLeft"></td>
			<td class="boxtopMiddle"></td>
			<td class="boxtopRight"></td>
		  </tr>
		  <tr> 
			<td width="10" class="boxMiddleLeft"></td>
			<td class="boxMiddleMiddle">
			<table cellpadding="5" cellspacing="15" width="100%">
				<tr valign="top">  
<?php					
            $cnt = 0;
			foreach($modulearray['id'] as $i => $id) {
			  $parentFolderName = strtolower($modulearray['name'][$i]);
              $path = CMSSITEPATH.'/'.$parentFolderName.'/display.php';  
			  $divVal = '';            
              if(!empty($modulearray['displayname'][$i])) {
			  		$cms_p_modules_id = $modulearray['id'][$i];
					$arrWhere = array();
					$arrWhere['sqlclause'] = "id IN ($cmsmodules_id)";
					$arrWhere['status'] = 1;
					$arrWhere['module_pid'] = $cms_p_modules_id;
					$c_modules = $db->getDataFromTable($arrWhere, 'cms_modules', 'id, name, displayname, headingname', "display_order ASC");
					//echo '<br>'.count($c_modules).'<br>';
				if(count($c_modules) > 0 || in_array($cms_p_modules_id,$cmsModuleArray) ){
					if(in_array($cms_p_modules_id,$cmsModuleArray)){
						$divVal='<div style="padding-bottom:5px;"><a href="'.$path.'">Manage '.$modulearray['displayname'][$i].'</a></div>';
					}
?>
				<td>
					<fieldset>
					<legend title="<?php echo ucfirst($modulearray['headingname'][$i]);?>">
					  <?php echo ucfirst($modulearray['headingname'][$i]);?>
					</legend>                            
					<?php echo $divVal;?>
					<?php
					//for sub category listing
												
					
					//echo '<pre>';print_r($c_modules);	echo '</pre>';					
					foreach($c_modules as $c_m) {
						if($c_m['name']=='approvedcomment') {
							$fullpath = CMSSITEPATH.'/comment/displayapproved.php';	
						} else if($c_m['name']=='hometopbox') {
							$fullpath = CMSSITEPATH.'/box/display.php?boxtype=hometopbox';	
						} else {
							$fullpath = CMSSITEPATH.'/'.strtolower($c_m['name']).'/display.php';
						}
						$showName= $c_m['displayname'];
					?>
						<div style="padding-bottom:5px;"><a href="<?php echo $fullpath;?>">Manage <?php echo $showName;?></a></div>
					<?php
					}
					?>								
					</fieldset>
				</td>
<?php			$cnt++;
			}//end of if

				if($cnt % 4 == 0) {
					echo '</tr><tr>';
				}
			}
	      }//end of for loop
?>
		</tr>
	</table>
</td>
    <td width="10" class="boxMiddleRight"></td>
  </tr>
  <tr> 
    <td class="boxbottomLeft"></td>
    <td class="boxbottomMiddle"></td>
    <td class="boxbottomRight"></td>
  </tr>
</table>
<!-- table end -->
						
			</div>
	 	</div>	
 	</div>	
<?php 
include_once(CMSSITEPATH."/incFooter.php"); 
?>	
</body>
</html>
