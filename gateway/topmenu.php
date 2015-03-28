<?php
include_once('inc/constants.php');
include_once('inc/functions.php');

$db = Database::Instance() ;

$fulltopURL = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$array = explode("/",$fulltopURL);
$cnt = count($array);
$homepage = $array[$cnt-1];

$myaccess = array();

$userid = $_SESSION['ITUser']['ID'];

$arrWhere = array();
$arrWhere['id'] = $userid;
$arrWhere['status'] = 1;
 
$modules = $db->getDataFromTable($arrWhere, 'author', 'cmsmodules_id');
$cmsmodules_id = $modules[0]['cmsmodules_id'];
$cmsModuleArray= explode(',',$cmsmodules_id);
$arrWhere = array();
$arrWhere['status'] = 1;
$arrWhere['module_pid'] = 0;

$modules = $db->getDataFromTable($arrWhere, 'cms_modules', 'id, name, displayname, headingname', "display_order ASC");
/*echo '<pre>';print_r($modules); echo '</pre>';
echo '<pre>';print_r($cmsModuleArray); echo '</pre>';
*/
?>
<script src="<?php echo JSFILEPATH;?>/tools.js"></script>
<div class="toolbar overlay-displace-top clearfix toolbar-processed" id="toolbar">
	<div class="toolbar-menu clearfix">
		<ul id="toolbar-home">
			<li class="home first last active">
				<a class="active" title="Home" href="<?php echo CMSSITEPATH; ?>/homepage.php"><span class="home-link">Home</span> </a>
			</li>
		</ul>
		<ul id="toolbar-user">
        	<li class="account first">
				<a id="changepasswordval" href="javascript: void(0);" onclick="ModalBox.open('<?php print CMSSITEPATH;?>/author/changepassword.php?id=<?php echo $_SESSION['ITUser']['ID']; ?>',650,400);"><img src='<?php print IMAGEPATH;?>/keepassx.png' height="25px;" title="Change Password" alt="Change Password" /></a>
			</li>
			<li class="account first">
				<a title="User account" href="#">Hello <strong><?php echo strtoupper($_SESSION['ITUser']['USERNAME']).'! ('.$userDetails[RIGHTS].')'?></strong></a>
			</li>
			<li class="logout last"><a
				href="<?php echo CMSSITEPATH;?>/login/logout.php">Log out</a>
			</li>
		</ul>
		<h2 class="element-invisible">Administrative toolbar</h2>
		<div id="adminMenu" class="droplinebar">
			<ul id="topNavigation">
<?php				
			foreach($modules as $m) {
				$name= strtolower($m['name']);
				$displayname= strtolower($m['displayname']);
				$modulearray['name'][] = $name;
				$modulearray['id'][] = $m['id'];
				$modulearray['headingname'][] = $m['headingname'];
				$modulearray['displayname'][] = $m['displayname'];
				$modulearr = 'module'.$name.'array';
				$myaccess[] = $name;
  	            if(in_array($_SESSION['TOPMENU'], $myaccess)) {
		          $active =  'active';		
		        } else {
		          $active = '';
		        }
				$arrWhere = array();
				$arrWhere['sqlclause'] = "id IN ($cmsmodules_id)";
				$arrWhere['status'] = 1;
				$arrWhere['module_pid'] = $m['id'];
				
				$c_modules = $db->getDataFromTable($arrWhere, 'cms_modules', 'id, name, displayname, headingname', "display_order ASC");
				$c_modules_id = $db->getDataFromTable($arrWhere, 'cms_modules', 'id', "display_order ASC");
				//echo '<pre>';print_r($c_modules); echo '</pre>';
				if(count($c_modules_id) > 0 || in_array($m['id'],$cmsModuleArray)){
					//print_r($c_modules['id']);
					$folderfullpath = CMSSITEPATH."/".strtolower($name).'/display.php';
					$path=''; $li_tag='';
					if(in_array($m['id'],$cmsModuleArray)){
						$path = CMSSITEPATH."/".$name.'/display.php';
						$li_tag='<li id="menu'.$c_modules[0]['id'].'" class="menuItem '.$active.'"><a href="'.$folderfullpath.'">'.$name.'</a></li>';
						$li_tag_sub='<li id="menu'.$c_modules[0]['id'].'" class="menuItem '.$active.'"><a href="'.$folderfullpath.'">'.$displayname.'</a></li>';
					}else{
						$path = 'javascript:void();';
						$li_tag='';
					}
					
					echo '<li id="menu'.$m['id'].'" class="menuItem '.$active.'"><a href="'.$path.'" >'.$m['headingname'].'</a>';
					if(count($c_modules) > 0) {								
						$name= $m['displayname'];
						
						if(in_array($_SESSION['TOPMENU'], $myaccess)) {
						  $active =  'active';
						} else {
						  $active = '';
						}
						
						echo '<ul id="menu'.$c_modules[0]['id'].'_child" class="shadow">';
						echo $li_tag_sub;
						 
						foreach($c_modules as $c_m) {
							$myaccess[] = strtolower($c_m['name']);
						  
							if($c_m['name']=='approvedcomment') {
								$folderlink = CMSSITEPATH.'/comment/displayapproved.php';
							} else if($c_m['name']=='hometopbox') {
								$folderlink = CMSSITEPATH.'/boxmaster/display.php';
							} else {
								$folderlink = CMSSITEPATH."/".strtolower($c_m['name']).'/display.php';
							}
	
							echo '<li><a href="'.$folderlink.'">'.$c_m['displayname'].'</a></li>';                
						}
						
						echo '</ul>';				
					}
					echo '</li>';
				}
			}			
?>
			</ul>
<?php
		$access = 0;

		if(in_array(strtolower($_SESSION['TOPMENU']), $myaccess)) {
		 $access=1;
		}		
		//add global search for all
		if($_SESSION['TOPMENU'] == "globalSearch" || $_SESSION['TOPMENU'] == "Homepage") {
			 $access=1;
		}
		
		//print_r($$modulecontent);
		if($access==0) {
			if($homepage != 'homepage.php') {
				$URL = CMSSITEPATH.'/homepage.php';	
				client_redirect($URL);
			}				
		}
		echo  $jsCode;
?>	    
   </div>
		<a class="toggle toggle-active toolbar-toggle-processed"
			title="Hide Icon Legend" href="#"
			onclick="$('#iconlegend').toggle('slow')">Hide Icon Legend</a>
	</div>
</div>
<div id="iconlegend">
<?php include('iconMenu.php'); ?>
</div>
<div id="blackdivlight"
	class="blackbg"></div>
