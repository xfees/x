<?php
include_once '../config.php';
$object_admin_log = new AdminLog();
reset($dbdetails);
$db_name = 'x';
$module_id = isset($_POST['module_id']) ? $_POST['module_id'] : NULL;
$insertdate = isset($_POST['insertdate']) ? $_POST['insertdate'] : NULL;
$record_id = NULL;
$searchByAuthor = NULL;

if ( isset($_POST['searchByAuthor']) && !empty($_POST['searchByAuthor']) )
{
  $searchByAuthor = $_POST['searchByAuthor'];
}
else if ( isset($_GET['searchByAuthor']) && !empty($_GET['searchByAuthor']) )
{
  $searchByAuthor = $_GET['searchByAuthor'];
}
if ( isset($_POST['searchByAction']) && !empty($_POST['searchByAction']) )
{
  $searchByAction = $_POST['searchByAction'];
}
else if ( isset($_GET['searchByAction']) && !empty($_GET['searchByAction']) )
{
  $searchByAction = $_GET['searchByAction'];
}
if ( isset($_POST['searchById']) && !empty($_POST['searchById']) && ($_POST['searchById']!='Search by Id'))
{
  $searchById = $_POST['searchById'];
}
else if ( isset($_GET['searchById']) && !empty($_GET['searchById']) && ($_GET['searchById']!='Search by Id'))
{
  $searchById = $_GET['searchById'];
}
$arrSearch = array('db_name' => $db_name, 'module_id' => $module_id, 'insertdate' => $insertdate);
if($searchByAuthor != NULL) {
	$arrSearch['searchByAuthor'] = $searchByAuthor;
}
if($searchByAction != NULL) {
	$arrSearch['searchByAction'] = $searchByAction;
}
if($searchById != NULL) {
	$arrSearch['searchById'] = $searchById;
}

$db_type = isset($_POST['db_type']) ? $_POST['db_type'] : 'mongodb';
$recperpage = isset($_POST['recperpage']) ? $_POST['recperpage'] : MAX_FORMS_PER_PAGE;
$pg = isset($_POST['pg']) ? $_POST['pg'] : '';
$dispfirstpage = isset($_POST['dispfirstpage']) ? $_POST['dispfirstpage'] : '';
$displastpage = isset($_POST['displastpage']) ? $_POST['displastpage'] : '';
$search = isset($_POST['search']) ? $_POST['search'] : 'first';
$flag = isset($_POST['flag']) ? $_POST['flag'] : '';
$searchData = isset($_POST['data']) ? $_POST['data'] : http_build_query($arrSearch);
$search = isset($_POST['search']) ? $_POST['search'] : 'first';
$action = ( isset($_POST['action']) && !empty($_POST['action']) ) ? $_POST['action'] : NULL;
$paginate = new Paginate(strtolower($_SESSION['TOPMENU']), $search, $searchData, $dispfirstpage, $displastpage, $pg, $action, $recperpage);
$offset = $paginate->offset;
$recperpage = $paginate->recperpage;
//require_once CMSROOTPATH . '/adminlog/search_form.php';
?>
<?php if ($_SESSION['ITUser']['RIGHTS'] == 1): ?>
  <script type="text/javascript">
    function ClipBoard(object)
    {
      object.focus();
      object.select();          
    } 
  </script>
  <form name="listform" id="listform">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tbody>
        <tr class="removeheading">
          <!--<td valign="middle" class="titlebar">DB</td>-->
          <td valign="middle" class="titlebar" width="20%">Date</td>
          <td valign="middle" class="titlebar" width="20%"><img src="<?php echo IMAGEPATH; ?>/list-separator.gif" width="2" height="31" border="0" align="absmiddle" class="listSeparator" />Author</td>        
           <td valign="middle" class="titlebar" width="10%"><img src="<?php echo IMAGEPATH; ?>/list-separator.gif" width="2" height="31" border="0" align="absmiddle" class="listSeparator" />Action</td>
          <?php if($record_id == NULL) {?>
		  <td valign="middle" class="titlebar" width="20%"><img src="<?php echo IMAGEPATH; ?>/list-separator.gif" width="2" height="31" border="0" align="absmiddle" class="listSeparator" />Module</td>	
		  <td valign="middle" class="titlebar" width="10%"><img src="<?php echo IMAGEPATH; ?>/list-separator.gif" width="2" height="31" border="0" align="absmiddle" class="listSeparator" />Module ID</td>	  
		   <?php } ?>
          <td valign="middle" class="titlebar" width="20%"><img src="<?php echo IMAGEPATH; ?>/list-separator.gif" width="2" height="31" border="0" align="absmiddle" class="listSeparator" />Query</td>
        </tr>
        <tr class="removeheading">
          <td class="pL pTB" id="notification" colspan="6"></td>
        </tr>
        <?php
        $list_params = array();
        $statusData = 1;

        $list_params['db_type'] = $db_type;
        $list_params['db_name'] = $db_name;
        $list_params['result_type'] = 'records';
        $list_params['search'] = $search;
        $list_params['offset'] = $offset;
        $list_params['recperpage'] = $recperpage;
        $list_params['search_data'] = $searchData;
        $list_params['status'] = $statusData;

        if ($searchByAuthor != NULL) {
          $list_params['author_id'] = $searchByAuthor;
        }
		if ($searchByAction != NULL) {
          $list_params['action'] = $searchByAction;
        }
		if ($searchById != NULL) {
          $list_params['record_id'] = $searchById;
        }
        if ($module_id != NULL) {
          $list_params['module_id'] = $module_id;
        }
        if ($insertdate != NULL) {
          $list_params['insertdate'] = date('Y-m-d', strtotime($insertdate));
        }
		$result_data = $object_admin_log->getLogs($list_params);
		//echo '<pre>'; print_r($result_data ); echo '</pre>';
        $result_cnt = count($result_data);
        ?>

        <?php if ($result_cnt > 0): ?>
          <?php foreach ($result_data as $key => $data): ?>
		  <?php 
					$actiontype=$data['action'];
					switch ($actiontype)
						{
							case 'a': $actionstr = 'Added'; $style = "style='color:#008400'";break;
							case 'd': $actionstr = 'Deleted'; $style = "style='color:#FA3F50'";break;
							case 'p': $actionstr = 'Published'; $style = "style='color:#63A8D0'";break;
							case 'u': $actionstr = 'Unpublished'; $style = "style='color:#7109AA'";break;
							case 'r': $actionstr = 'Restored'; $style = "style='color:#AD65D4'";break;
							case 'm': $actionstr = 'Modified'; $style = "style='color:#FEB500'";break;
							case 'login': $actionstr = 'Login'; $style = "style='color:#009898'";break;
							default: $actionstr = $data['action'];$style = "style='color:#000000'"; break;
						}
			?>
            <?php if ($flag == 'a' || isset($search)): ?>
              <tr id="singleCont<?php echo $data['id']; ?>" class="listing" >
            <?php endif; ?>	
             <?php /*?><!-- <td class="pL"><?php echo $data['db_name']; ?></td>   --><?php */?>
              <td class="pL"><?php echo getdisplaydatetime($data['insertdate']); ?></td>                                 
              <td class="pL"><a href="javascript:void(0)" onclick="searchByAuthorId(<?php echo $data["author_id"]; ?>)" class="grayText"><?php echo $data['name']; ?></a></td>  
              <td class="pL" ><b>
			  	<?php if($actionstr!='' && ($actionstr != $data['action']))
				{
					echo '<a href="javascript:void(0)" onclick="searchByActionType(\''.$actiontype.'\')"'.$style.'>'.$actionstr.'</a>';
				}
				else if($actionstr!='')
				{
					echo $actionstr;
				}
				else
				{
					echo "-";
				}
				?>
				</b></td> 
			  <?php if($record_id == NULL) {?>
              <td class="pL"><?php echo $modulename= ($data['module_name']!='')?'<a href="javascript:void(0)" onclick="searchByModuleId(\''.ucfirst($data["module_name"]).'\')" class="grayText">'.ucfirst($data['module_name']).'</a>':'-'; ?></td> 
			  <td class="pL"><a href="javascript:void(0)" onclick="searchById(<?php echo $data["record_id"]; ?>)" class="grayText"><?php echo $data['record_id']; ?></a></td>                                                                                                       
			  <?php } ?>
              <td class="pL" style="width:28%">
                <textarea onclick="ClipBoard(this)" cols="35"><?php echo stripslashes($data['query_that_is_executed']); ?></textarea>        
              </td>
      <?php if ($flag == 'a' || isset($search)): ?>
              </tr>
      <?php endif; ?>			
            <?php endforeach; ?>
          <?php else: ?>
          <tr><td colspan="4" class="pL pTB" id="norecordsdiv">No Records</td></tr>			
        <?php endif; ?>
        <tr>
          <td colspan="6">
        <?php
        $list_params['result_type'] = 'count';
        $count = $object_admin_log->getLogs($list_params);
        print $paginate->render($count);
        ?>		
          </td>
        </tr>		
      </tbody>
    </table>
  </form>
<?php else: ?>
  No Access
<?php endif; ?>
