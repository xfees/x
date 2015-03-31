<?php
//INCLUDE COMMON CONFIGURATION FILE
include_once('../config.php');
//INSTANTIATE MODEL CLASS
$model = ucfirst(CUR_DIR);
$modelObj = new $model();
//FETCHING VARIABLES
$recperpage = isset($_POST['recperpage']) ? $_POST['recperpage'] : MAX_FORMS_PER_PAGE;
$pg = isset($_POST['pg']) ? $_POST['pg'] : "";
$dispfirstpage = isset($_POST['dispfirstpage']) ? $_POST['dispfirstpage'] : "";
$displastpage = isset($_POST['displastpage']) ? $_POST['displastpage'] : "";
$search = isset($_POST['search']) ? $_POST['search'] : "first";
$flag = isset($_POST['flag']) ? $_POST['flag'] : "";
$searchData = isset($_POST['data']) ? $_POST['data'] : '';
$action = isset($_POST['action']) ? $_POST['action'] : '';
$searchDataArray = array();
if (isset($_POST['search'])) {
    $searchData = '';
    $searchDataArray = array();
    if (isset($_POST['searchByCategoryName']) && $_POST['searchByCategoryName'] != '' && $_POST['searchByCategoryName'] != 'Type Category Name Here') {
        $searchDataArray['name'] = $_POST['searchByCategoryName'];
        $searchData .= 'searchByCategoryName=' . $_POST['searchByCategoryName'] . '&';
    }
    if (isset($_POST['searchByParentCategory']) && $_POST['searchByParentCategory'] != '') {
        $searchDataArray['parent_id'] = $_POST['searchByParentCategory'];
        $searchData .= 'searchByParentCategory=' . $_POST['searchByParentCategory'] . '&';
    }
    $searchData = substr($searchData, 0, -1);
}
$displaypage = Common::l($_POST['displaypage']);
//Initialize paginate and supply it with necessary params
$paginate = new Paginate(CUR_DIR, $search, $searchData, $dispfirstpage, $displastpage, $pg, $action, $recperpage);
$offset = $paginate->offset;
$recperpage = $paginate->recperpage;
/* 
Cases for Post action
  INSERT = a
  UPDATE = m
  EDIT   = e
  DELETE = d
  TRASHCAN = tc
  RESTORE = r
*/
$returnVal = 0;
$id = NULL;
$strDdl = '';
$arrUnset = array('parentid', 'metatitle', 'metakeyword', 'metadescription');

if ($action != '' && $displaypage != 'trashcan') {
    switch ($action) {
        case 'a': //INSERT
            $insertArr = array();
            unset($_POST['textSearch']);
            unset($_POST['update']);
            unset($_POST['oldimage']);
            unset($_POST['action']);
            foreach ($_POST as $key => $val) {
                if (in_array($key, $arrUnset)) {
                    $insertArr[$key] = $_POST[$key];
                }
            }//eof foreach
            $strModule = '';
            $insertArr['name'] = trim(str_replace('/', ' ', $_POST['name']));
            $insertArr['thumbnail'] = $_POST['thumbnail'];
            $insertArr['priority'] = 0;
            $insertArr['status'] = 1;
            $insertArr['insertdate'] = date('Y-m-d H:i:s');
            $insertArr['updatedate'] = date('Y-m-d H:i:s');
            $returnVal = $modelObj->insertTable($insertArr);
            $id = $returnVal;
            break;
        case 'm': //UPDATE
            if (isset($_POST['thumbnail'])) {
                $_POST['thumbnail'] = $_POST['thumbnail'];
            } else {
                $_POST['thumbnail'] = $_POST['oldimage'];
            }
            foreach ($_POST as $key => $val) {
                if (in_array($key, $arrUnset)) {
                    $_POST[$key] = $_POST[$key];
                }
            }//eof foreach
            $updateArr['metakeyword'] = $_POST['metakeyword'];
            $updateArr['metatitle'] = $_POST['metatitle'];
            $updateArr['name'] = $_POST['name'];
            $updateArr['metadescription'] = $_POST['metadescription'];
            $updateArr['parentid'] = $_POST['parentid'];
            $updateArr['thumbnail'] = $_POST['thumbnail'];
            $updateArr['priority'] = $_POST['priority'];
            $whereArr = array('id' => $_POST['id']);
            $_POST['updatedate'] = date('Y-m-d H:i:s');
            $returnVal = $modelObj->updateTable($updateArr, $whereArr);
            if ($_POST['name'] != $_POST['old_sectionname']) {
                $modelObj->updateCategoryName($_POST['id'], $_POST['name']);
            }
            $id = $_POST['id'];
            break;
        case 'd': //DELETE
            $returnVal = $modelObj->toggleTableStatus($_POST['id'], '-1');
            $id = $_POST['id'];
            break;
        case 'r': //RESTORE
            $returnVal = $modelObj->toggleTableStatus($_POST['id'], '1');
            $id = $_POST['id'];
            break;
        case 'qe': //Quick Edit Case
            $modelObj->updateTable(array($_POST['column'] => $_POST['columnval']), array('id' => $_POST['id']));
            exit;
            break;
        case 'e': //EDIT
            echo $data = $modelObj->getEditData($_POST['id']);
            exit;
            break;
        case 'priority': //PRIORITY
            $returnVal = $modelObj->updateTable(array('priority' => $priority), array('id' => $txt_id));
            exit;
            break;
        default:
            break;
    } //eof switch

    if ($returnVal > 0) {
        $msg = ressuccessmsg($action);
        $status = 1;
        $strDdl = $modelObj->getCategoryDdl();
    } else {
        $msg = resfailedmsg($action);
        $status = 0;
    }
    $jsonStr = "{'msg':'$msg','status':'$status','action':'$action','id':'$id','module':'" . CUR_DIR . "', 'strddl': '$strDdl'}";
    echo $jsonStr;
} else { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tbody>
  <tr class="removeheading"> 
   <td width="4%" valign="middle" class="titlebar pL">Id</td>
   <td width="27%" valign="middle" class="titlebar"><img src="<?php echo IMAGEPATH; ?>/list-separator.gif" width="2" height="31" border="0" align="absmiddle" class="listSeparator" />Category</td>
   <td width="15%" valign="middle" class="titlebar"><img src="<?php echo IMAGEPATH; ?>/list-separator.gif" width="2" height="31" border="0" align="absmiddle" class="listSeparator" />Parent Category</td>
   <td width="15%" valign="middle" class="titlebar"><img src="<?php echo IMAGEPATH; ?>/list-separator.gif" width="2" height="31" border="0" align="absmiddle" class="listSeparator" />Priority </td>
   <td width="15%" valign="middle" class="titlebar"><img src="<?php echo IMAGEPATH; ?>/list-separator.gif" width="2" height="31" border="0" align="absmiddle" class="listSeparator" />Stories Count</td>
   <td width="17%" valign="middle" class="titlebar"><img src="<?php echo IMAGEPATH; ?>/list-separator.gif" width="2" height="31" border="0" align="absmiddle" class="listSeparator" />Actions</td>
  </tr>
  <!--notification starts here-->
  <tr class="removeheading"><td class="pL pTB" id="notification" colspan="4"></td></tr>
<?php
//Get the row count
if ($displaypage == 'trashcan') {
    $statusData = '-1';
} else {
    $statusData = '1';
}
$searchByParentCategory = isset($_POST['searchByParentCategory']) ? $_POST['searchByParentCategory'] : 0;
$result_data = $modelObj->getCategoryTreelist($searchByParentCategory, $statusData);
if (!empty($result_data)) {
    foreach ($result_data as $key => $val) {
        if ($flag == 'a' || isset($search)) { //If action was add then add a new div 
?>
  <tr id="singleCont<?php echo $key; ?>" class="listing" onmouseover="javascript:this.className='alternate'" onmouseout="javascript:this.className='listing'">
<?php   } ?>
   <td class="pL pTB grayText">
    <a href="javascript:void(0);" <?php if (RIGHTS != 0 && $displaypage == 'trashcan') { ?> onclick='getEditDetails("<?php echo $key; ?>","<?php echo CUR_DIR ?>")' <?php } ?>><?php echo $key; ?></a>
   </td>
   <td class="pL"><b><div id="hdlnplaceholder<?php echo $key ?>"><?php echo $parent = $val["data"]; ?></div></b></td>
   <td class="pL">&nbsp;<?php if (count($val['childs']) > 0) { echo 'Parent'; } ?></td>
   <td class="pL">
    <span id="changepriority<?php echo $key; ?>"><?php echo $val['priority']; ?></span>
    <input maxlength="2" size="1" class="txtPriority" name="priority<?php echo $key; ?>" id="priority<?php echo $key; ?>" onblur="save_priority(this.value,'<?php echo $key; ?>')" type="text">
   </td>
   <td class="pL grayText">
<?php
if (!empty($_POST['searchByParentCategory'])) {
    if ($val['story_count'] > 0) {
        echo $val['story_count'];
    } else {
        echo '<label class="grayText_big">0</label>';
    }
}
?>
   </td>
   <td class="padding5">
    <div class="actions">
<?php if ($displaypage == 'trashcan') { ?>
     <a href="javascript:void(0);" <?php if (RIGHTS != 0) { ?> onclick='callUnDelete(<?php echo $key; ?>,"<?php echo CUR_DIR ?>")' <?php } ?> class="restore" title="Restore"><b>Restore</b></a>
<?php } else { ?>
     <a href="javascript:;" class="edit" title="Edit" <?php if (RIGHTS != 0) { ?> onclick='getEditDetails("<?php echo $key; ?>","<?php echo CUR_DIR ?>")' <?php } ?>>Edit</a>
     <a href="javascript:;" <?php if (RIGHTS != 0) { ?> onclick='callDelete("<?php echo $key; ?>","<?php echo CUR_DIR ?>")' <?php } ?> class="delete" title="Delete">Delete</a>
<?php } ?>
    </div>
   </td>
<?php
if ($flag == 'a' || isset($search)) {
    echo '</tr>'; //closing <tr> openend above in same case.
}
foreach ($val['childs'] as $key => $val) {
    $parent1 = $val["data"];
    if ($flag == 'a' || isset($search)) {   //----------If action was add then add a new div 
?>
         <tr id="singleCont<?php echo $key; ?>" class="listing" onmouseover="javascript:this.className='alternate'" onmouseout="javascript:this.className='listing'">
<?php } ?>
          <td class="pL pTB grayText"><a href="javascript: void(0);"  onclick='getEditDetails("<?php echo $key; ?>","<?php echo CUR_DIR ?>")'><?php echo $key; ?></a></td>
          <td class="pL grayText_big"><div id="hdlnplaceholder<?php echo $key ?>" ><?php echo "---  " . $val["data"]; ?></div></td>
          <td class="pL"><?php echo $parent;?></td>
          <td class="pL">
           <span id="changepriority<?php echo $key; ?>"><?php echo $val['priority']; ?></span>
           <input maxlength="2" size="1" class="txtPriority" name="priority<?php echo $key; ?>" id="priority<?php echo $key; ?>" onblur="save_priority(this.value,'<?php echo $key; ?>')" type="text" />
          </td>
          <td class="pL grayText">  
<?php
if ($val['story_count'] > 0) {
  echo $val['story_count'];
} else {
  echo '<label class="grayText_big">0</label>';
}
?>                
          </td>
          <td class="padding5">
            <div class="actions">
        <?php if ($displaypage == 'trashcan') { ?>
             <a href="javascript:void(0);" <?php if (RIGHTS != 0) { ?> onclick='callUnDelete(<?php echo $key; ?>,"<?php echo CUR_DIR ?>")' <?php } ?> class="restore" title="Restore"><b>Restore</b></a>
        <?php } else { ?>
             <a href="javascript:;" class="edit" title="Edit" <?php if (RIGHTS != 0) { ?> onclick='getEditDetails("<?php echo $key; ?>","<?php echo CUR_DIR ?>")' <?php } ?>>Edit</a>
             <a href="javascript:;" <?php if (RIGHTS != 0) { ?> onclick='callDelete("<?php echo $key; ?>","<?php echo CUR_DIR ?>")' <?php } ?> class="delete" title="Delete">Delete</a>
        <?php } ?>
            </div>            
          </td>
<?php
    if ($flag == 'a' || isset($search)) {
        echo '</tr>';
    }
  } // eof of childs foreach
 }// eof of main foreach
} else { ?>
    <tr>
      <td colspan="4" class="pL pTB" id="norecordsdiv">No Records</td>
    </tr>
<?php } ?>      
    </tbody>
  </table>
<?php } // eod of 1st else. ?>
