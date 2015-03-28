<?php
/* * **************************** INCLUDE COMMON CONFIGURATION FILE ********************************************************************* */
include_once('../config.php');
/* * ************************** INSTANTIATE MODEL CLASS ********************************************************************* */
$modelObj = new Section();
/* * ***  FETCHING VARIABLES ********************************************************************* */
//paging code
$recperpage = isset($_POST['recperpage']) ? $_POST['recperpage'] : MAX_FORMS_PER_PAGE;
$pg = isset($_POST['pg']) ? $_POST['pg'] : "";
$dispfirstpage = isset($_POST['dispfirstpage']) ? $_POST['dispfirstpage'] : "";
$displastpage = isset($_POST['displastpage']) ? $_POST['displastpage'] : "";
$search = isset($_POST['search']) ? $_POST['search'] : "first";
$flag = isset($_POST['flag']) ? $_POST['flag'] : "";
$searchData = isset($_POST['data']) ? $_POST['data'] : '';
$action = isset($_POST['action']) ? $_POST['action'] : '';
$_SESSION['TOPMENU'] = ($_SESSION['TOPMENU'] == 'section') ? $_SESSION['TOPMENU'] : 'section';
$searchDataArray = array();
if (isset($_POST['search'])) {
  $searchData = '';
  $searchDataArray = array();
  if (isset($_POST['searchBySectionName']) && $_POST['searchBySectionName'] != '' && $_POST['searchBySectionName'] != 'Type Section Name Here') {
    $searchDataArray['name'] = $_POST['searchBySectionName'];
    $searchData .= 'searchBySectionName=' . $_POST['searchBySectionName'] . '&';
  }
  if (isset($_POST['searchByParentSection']) && $_POST['searchByParentSection'] != '') {
    $searchDataArray['parent_id'] = $_POST['searchByParentSection'];
    $searchData .= 'searchByParentSection=' . $_POST['searchByParentSection'] . '&';
  }

  $searchData = substr($searchData, 0, -1);
}
$displaypage = Common::l($_POST['displaypage']);

/* Initialize paginate and supply it with necessary params */
$paginate = new Paginate(strtolower($_SESSION['TOPMENU']), $search, $searchData, $dispfirstpage, $displastpage, $pg, $action, $recperpage);
$offset = $paginate->offset;
$recperpage = $paginate->recperpage;

/* Cases for Post action
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
    case 'a': //-------------------------------------------------------------------------------------------- INSERT
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
      if(!empty($_POST['is_tab'])) {
      	$insertArr['is_tab'] = 1;
      } else {
      	$insertArr['is_tab'] = 0;
      }
      $insertArr['priority'] = 0;
      $insertArr['status'] = 1;
      $insertArr['insertdate'] = date('Y-m-d H:i:s');
      $insertArr['updatedate'] = date('Y-m-d H:i:s');
      $returnVal = $modelObj->insertTable($insertArr);
      $id = $returnVal;
      break;

    case 'm': //---------------------------------------------------------------------------------------------- UPDATE
      if (isset($_POST['thumbnail'])) {
        $conn = Database::Instance();
        $section_data = $conn->getDataFromTable(array('id' => $_POST['id']), 'section', 'id,thumbnail');
        unlink($serverpath_temp['section'] . '/' . $section_data[0]['thumbnail']);
        unlink($serverpath['section'] . '/' . $section_data[0]['thumbnail']);
        $_POST['thumbnail'] = $_POST['thumbnail'];
      } else {
        $oldimage = $_POST['oldimage'];
        $_POST['thumbnail'] = $oldimage;
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
      if(!empty($_POST['is_tab'])) {
      	$updateArr['is_tab'] = 1;
      } else {
      	$updateArr['is_tab'] = 0;
      }

      $whereArr = array('id' => $_POST['id']);

      $_POST['updatedate'] = date('Y-m-d H:i:s');
      $returnVal = $modelObj->updateTable($updateArr, $whereArr);
      if ($_POST['name'] != $_POST['old_sectionname']) {
        $modelObj->updateSectionName($_POST['id'], $_POST['name']);
      }
      $id = $_POST['id'];
      break;

    case 'd': //---------------------------------------------------------------------------------------------- DELETE
      $returnVal = $modelObj->toggleTableStatus($_POST['id'], '-1');
      $id = $_POST['id'];
      break;
    case 'r':  //------------------------------------------------------------------------------------------------- RESTORE
      $returnVal = $modelObj->toggleTableStatus($_POST['id'], '1');
      $id = $_POST['id'];
      break;
    case 'qe':  // --------------------------------------------------------------------------------  Quick Edit Case
      $conn = Database::Instance();
      $conn->updateDataIntoTable(array($_POST['column'] => $_POST['columnval']), array('id' => $_POST['id']), 'section');
      exit;
      break;
    case 'e':
      echo $data = $modelObj->getEditData($_POST['id']);
      exit;
      break;
    case 'priority':  //------------------------------------------------------------------------------------------------- PRIORITY
      $txt_id = $_POST['txtid'];
      $priority = $_POST['txtpriority'];
      $conn = Database::Instance();
      $res = $conn->getDataFromTable(array('id' => $txt_id, 'priority' => $priority), 'section', $fields = 'id', '', '', false);
      if (empty($res)) {
        $returnVal = $modelObj->updateTable(array('priority' => $priority), array('id' => $txt_id));
        exit;
      } else {
        echo '0';
        exit;
      }
      $id = $_POST['id'];
      break;
    default:
      break;
  } //--------------------------------------------------------------------------------------------------------- eof switch $action				
  if ($returnVal > 0) {
    $msg = ressuccessmsg($action);
    $status = 1;
    $strDdl = $modelObj->getSectionDdl();
  } else {
    $msg = resfailedmsg($action);
    $status = 0;
  }

  if ($displaypage != 'trashcan') {
    $jsonStr = "{'msg':'$msg','status':'$status','action':'$action','id':'$id','module':'" . strtolower($_SESSION['TOPMENU']) . "', 'strddl': '$strDdl'}";
    echo $jsonStr;
  }
} else {
  ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tbody>

      <tr class="removeheading"> 
        <td width="4%" valign="middle" class="titlebar pL">Id</td>
        <td width="27%" valign="middle" class="titlebar"><img src="<?php echo IMAGEPATH; ?>/list-separator.gif" width="2" height="31" border="0" align="absmiddle" class="listSeparator" />Section</td>
        <td width="15%" valign="middle" class="titlebar"><img src="<?php echo IMAGEPATH; ?>/list-separator.gif" width="2" height="31" border="0" align="absmiddle" class="listSeparator" />Parent Section </td>
        <td width="15%" valign="middle" class="titlebar"><img src="<?php echo IMAGEPATH; ?>/list-separator.gif" width="2" height="31" border="0" align="absmiddle" class="listSeparator" />Priority </td>
        <td width="15%" valign="middle" class="titlebar"><img src="<?php echo IMAGEPATH; ?>/list-separator.gif" width="2" height="31" border="0" align="absmiddle" class="listSeparator" />Is Tab </td>
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

      $searchByParentSection = isset($_POST['searchByParentSection']) ? $_POST['searchByParentSection'] : 0;
      $result_data = $modelObj->getSectionTreelist($searchByParentSection);
      //echo "<pre>"; print_r($result_data);die;
      if (!empty($result_data)) {
        foreach ($result_data as $key => $val) {

          if ($flag == 'a' || isset($search)) {   //----------If action was add then add a new div 
            ?>
            <tr id="singleCont<?php echo $key; ?>" class="listing" onmouseover="javascript:this.className='alternate'" onmouseout="javascript:this.className='listing'">
            <?php } ?>
            <td class="pL pTB grayText">
              <?php if ($displaypage != 'trashcan' && RIGHTS != 0) { ?>
                <a href="javascript: void(0);"  onclick='getEditDetails("<?php echo $key; ?>","<?php echo strtolower($_SESSION['TOPMENU']) ?>")'><?php echo $key; ?></a>
                <?php
              } else {
                echo $key;
              }
              ?>
            </td>

            <td class="pL">
              <b>
                <div id="hdlnplaceholder<?php echo $key ?>"><?php echo $parent = $val["data"]; ?></div>                
              </b>
            </td>
            <td class="pL">
              &nbsp;
              <?php
              if (count($val['childs']) > 0) {
                echo 'Parent';
              }
              ?>
            </td>
            <td class="pL">
              <span id="changepriority<?php echo $key; ?>"><?php echo $val['priority']; ?></span>
              <input maxlength="2" size="1" class="txtPriority" name="priority<?php echo $key; ?>" id="priority<?php echo $key; ?>" onblur="save_priority(this.value,'<?php echo $key; ?>')" type="text">
            </td>
            <td class="pL grayText"><?php if($val['is_tab'] == 1) {echo 'Yes';}else{echo 'No';} ?></td>
            <td class="pL grayText">
            <?php
            if(!empty($_POST['searchByParentSection'])) {
                if ($val['story_count'] > 0) {
                  echo $val['story_count'];
                } else {
                  echo '<label class="grayText_big">0</label>';
                }
            }
            ?>
            </td>
            <td class="padding5"><div class="actions">
                <?php
                if (RIGHTS == 0) {
                  if ($displaypage == 'trashcan') {
                    ?>
                    <a href="javascript:void(0);" class="restore" title="Restore"><b>Restore</b></a>
                  <?php } else { ?>
                    <a href="javascript:void(0);" class="edit" title="Edit" >Edit</a>
                    <a href="javascript:void(0);" class="delete" title="Delete">Delete</a>
                    <?php
                  }
                } else {
                  if ($displaypage == 'trashcan') {
                    ?>
                    <a href="javascript:void(0);" onclick='callUnDelete(<?php echo $key; ?>,"<?php echo strtolower($_SESSION['TOPMENU']) ?>")' class="restore" title="Restore"><b>Restore</b></a>
                  <?php } else { ?>			
                    <a href="javascript:;" class="edit" title="Edit" onclick='getEditDetails("<?php echo $key; ?>","<?php echo strtolower($_SESSION['TOPMENU']) ?>")'>Edit</a>
                    <a href="javascript:;" onclick='callDelete("<?php echo $key; ?>","<?php echo strtolower($_SESSION['TOPMENU']) ?>")' class="delete" title="Delete">Delete</a>
                    <?php
                  }
                }
                ?>
              </div></td>
            <?php
            if ($flag == 'a' || isset($search)) {
              echo '</tr>';
            }
            foreach ($val['childs'] as $key => $val) {
              $parent1 = $val["data"];

              if ($flag == 'a' || isset($search)) {   //----------If action was add then add a new div 
                ?>
              <tr id="singleCont<?php echo $key; ?>" class="listing" onmouseover="javascript:this.className='alternate'" onmouseout="javascript:this.className='listing'">
              <?php } ?>
              <td class="pL pTB grayText"><a href="javascript: void(0);"  onclick='getEditDetails("<?php echo $key; ?>","<?php echo strtolower($_SESSION['TOPMENU']) ?>")'><?php echo $key; ?></a></td>
              <td class="pL grayText_big">
                <div id="hdlnplaceholder<?php echo $key ?>" ><?php echo "---  " . $val["data"]; ?></div>                
              </td>
              <td class="pL">
                <?php echo $parent;?>
              </td>
              <td class="pL">
                <span id="changepriority<?php echo $key; ?>"><?php echo $val['priority']; ?></span>
                <input maxlength="2" size="1" class="txtPriority" name="priority<?php echo $key; ?>" id="priority<?php echo $key; ?>" onblur="save_priority(this.value,'<?php echo $key; ?>')" type="text" />
              </td>
              <td class="pL grayText">
              	<?php if($val['is_tab'] == 1) {echo 'Yes';}else{echo 'No';} ?>
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
              <td class="padding5"><div class="actions">
                  <?php
                  if (RIGHTS == 0) {
                    if ($displaypage == 'trashcan') {
                      ?>
                      <a href="javascript:void(0);" class="restore" title="Restore"><b>Restore</b></a>
                    <?php } else { ?>
                      <a href="javascript:void(0);" class="edit" title="Edit" >Edit</a>
                      <a href="javascript:void(0);" class="delete" title="Delete">Delete</a>
                      <?php
                    }
                  } else {
                    if ($displaypage == 'trashcan') {
                      ?>
                      <a href="javascript:void(0);" onclick='callUnDelete(<?php echo $key; ?>,"<?php echo strtolower($_SESSION['TOPMENU']) ?>")' class="restore" title="Restore"><b>Restore</b></a>
                    <?php } else { ?>			
                      <a href="javascript:;" class="edit" title="Edit" onclick='getEditDetails("<?php echo $key; ?>","<?php echo strtolower($_SESSION['TOPMENU']) ?>")'>Edit</a>
                      <a href="javascript:;" onclick='callDelete("<?php echo $key; ?>","<?php echo strtolower($_SESSION['TOPMENU']) ?>")' class="delete" title="Delete">Delete</a>
                      <?php
                    }
                  }
                  ?>
                </div></td>
              <?php
              if ($flag == 'a' || isset($search)) {
                echo '</tr>';
              }
              foreach ($val['childs'] as $key => $val) {
                if ($flag == 'a' || isset($search)) {   //----------If action was add then add a new div 
                  ?>
                <tr id="singleCont<?php echo $key; ?>" class="listing" onmouseover="javascript:this.className='alternate'" onmouseout="javascript:this.className='listing'">
                <?php } ?>
                <td class="pL pTB grayText"><a href="javascript: void(0);"  onclick='getEditDetails("<?php echo $key; ?>","<?php echo strtolower($_SESSION['TOPMENU']) ?>")'><?php echo $key; ?></a></td>
                <td class="pL grayText_big">
                  <div id="hdlnplaceholder<?php echo $key ?>" <?php if ($action != 'tc' and RIGHTS != 0) { ?>onclick="javascript:$('#hdln<?php echo $key ?>').show();$('#hdlnplaceholder<?php echo $key ?>').hide();$('#hdlnval<?php echo $key ?>').focus()" style="cursor:pointer" <?php } ?>><?php echo "--- ---  " . $val["data"]; ?></div>
                  <div id="hdln<?php echo $key ?>" style="display:none;"><input type="text" id="hdlnval<?php echo $key ?>" value="<?php echo $val["data"]; ?>" onkeyup="checkavailablelist(<?php echo $key ?>);quickUpdate(<?php echo $key ?>,'name','<?php echo strtolower($_SESSION["TOPMENU"]); ?>','hdln',event);" style="width:90%;height:30px;"/>&nbsp;<span onclick="javascript:$('#hdln<?php echo $key ?>').hide();$('#hdlnplaceholder<?php echo $key ?>').show();" style="cursor:pointer;color:red;font-weight:bold">X</span><br><label class="error1" id="sectionnameavailable_error<?php echo $key ?>" style="font-style: italic;padding-left: 20px;color: red;display:none;">&nbsp;&nbsp;Section name Already Exists</label></div>
                </td>
                <td class="pL">
                  <?php
                  //echo $parent1;
                  ?>
                </td>
                <td class="pL grayText">
              	<?php if($val['is_tab'] == 1) {echo 'Yes';}else{echo 'No';} ?>
              	</td>
                <td class="pL grayText">&nbsp;</td>
                <td class="padding5"><div class="actions">
                    <?php
                    if (RIGHTS == 0) {
                      if ($displaypage == 'trashcan') {
                        ?>
                        <a href="javascript:void(0);" class="restore" title="Restore"><b>Restore</b></a>
                      <?php } else { ?>
                        <a href="javascript:void(0);" class="edit" title="Edit" >Edit</a>
                        <a href="javascript:void(0);" class="delete" title="Delete">Delete</a>
                        <?php
                      }
                    } else {
                      if ($displaypage == 'trashcan') {
                        ?>
                        <a href="javascript:void(0);" onclick='callUnDelete(<?php echo $key; ?>,"<?php echo strtolower($_SESSION['TOPMENU']) ?>")' class="restore" title="Restore"><b>Restore</b></a>
                      <?php } else { ?>			
                        <a href="javascript:;" class="edit" title="Edit" onclick='getEditDetails("<?php echo $key; ?>","<?php echo strtolower($_SESSION['TOPMENU']) ?>")'>Edit</a>
                        <a href="javascript:;" onclick='callDelete("<?php echo $key; ?>","<?php echo strtolower($_SESSION['TOPMENU']) ?>")' class="delete" title="Delete">Delete</a>
                        <?php
                      }
                    }
                    ?>
                  </div></td>
                <?php
                if ($flag == 'a' || isset($search)) {
                  echo '</tr>';
                }
              }
            }
          }
        } else {
          ?>
        <tr>
          <td colspan="4" class="pL pTB" id="norecordsdiv">No Records</td>
        </tr>
        <?php
      }//end of else	if(isset($search)){
      ?>      
    </tbody>
  </table>
  <?php
}
?>