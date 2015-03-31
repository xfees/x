<?php
error_reporting(1);
/* * *** INCLUDE CONNECTION FILE ********************************************************************* */
include_once('../config.php');
include_once('../inc/mail_functions.php');
/* * *** INSTANTIATE MODEL CLASS ********************************************************************* */
$modelObj = new Author();
$db = Database::Instance();
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

$sortBy = isset($_POST['sortBy']) ? $_POST['sortBy'] : '';
$sortSeq = isset($_POST['sortSeq']) ? $_POST['sortSeq'] : 'ASC';

$freeTextSearch = Common::l($_POST['freeTextSearch']);
$displaypage = Common::l($_POST['displaypage']);

$gs_text = Common::l($_GET['gs_text']);
$gs_type = Common::l($_GET['gs_type']);

if (Common::l($_GET['searchByAuthorType']) != "") {
    //set in post - post variable is already in place
    $_POST['searchByAuthorType'] = Common::l($_GET['searchByAuthorType']);
    //enable search;
    $_POST['search'] = 'bydata';
}
if (Common::l($_GET['searchByCategory']) != "") {
    //set in post - post variable is already in place
    $_POST['searchBySectionId'] = Common::l($_GET['searchByCategory']);
    //enable search;
    $_POST['search'] = 'bydata';
}
if ($gs_text != "" && $freeTextSearch == "") { //apply this search only on page laod
    $_POST['search'] = 'bydata';
    if ($gs_type == "bytitle") {
    $_POST['headline_chk'] = '1'; //this is already implemented below
    } else {
    $_POST['contentid_chk'] = '1'; //this is already implemented below
    }
    $freeTextSearch = $gs_text;
}
//end

if (isset($_POST['search'])) {
    $searchData = '';
    $searchDataArray = array();

    if ($freeTextSearch != '') {
        $_POST['searchByAuthorName'] = $freeTextSearch;
    }
    if (isset($_POST['searchByAuthorType']) && $_POST['searchByAuthorType'] != '') {
        $searchDataArray['rights'] = $_POST['searchByAuthorType'];
        $searchData .= 'searchByAuthorType=' . $_POST['searchByAuthorType'] . '&';
    }
    if (isset($_POST['searchByEmail']) && $_POST['searchByEmail'] != '' && $_POST['searchByEmail'] != 'Search by Email') {
        $searchDataArray['email'] = $_POST['searchByEmail'];
        $searchData .= 'searchByEmail=' . $_POST['searchByEmail'] . '&';
    }
    if (isset($_POST['searchByAuthorName']) && $_POST['searchByAuthorName'] != '' && $_POST['searchByAuthorName'] != 'Search By Name') {
        $searchDataArray['name'] = $_POST['searchByAuthorName'];
        $searchData .= 'searchByAuthorName=' . $_POST['searchByAuthorName'] . '&';
    }
    if (isset($_POST['searchByAuthorByline']) && $_POST['searchByAuthorByline'] != '') {
        $searchDataArray['by_line'] = $_POST['searchByAuthorByline'];
        $searchData .= 'searchByAuthorByline=' . $_POST['searchByAuthorByline'] . '&';
    }
    if ($_POST['search'] == 'byname') {
        $searchDataArray['search'] = $_POST['data'];
        $searchData .= 'search=byname&';
    }
    $searchData = substr($searchData, 0, -1);
}

/* Initialize paginate and supply it with necessary params */
$paginate = new Paginate(CUR_DIR, $search, $searchData, $dispfirstpage, $displastpage, $pg, $action, $recperpage);
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
$id = 0;
$insertArr = array();
$arrUnset = array('action', 'oldauthorthumbnail', 'authoroldthumbnail', 'oldusername', 'oldname', 'textSearch', 'update', 'filename', 'modulename', 'rights_add', 'rights_edit', 'rights_del', 'rights_pub', 'rights_feature', 'searchByAuthorType', 'searchByEmail', 'useType', 'section_id');
$strUserRights = '';

if ($action != '' && $action != 'tc') {
    $strUserRights .= ($_POST['rights_add']) ? '1' : '0';
    $strUserRights .= ($_POST['rights_edit']) ? '1' : '0';
    $strUserRights .= ($_POST['rights_del']) ? '1' : '0';
    $strUserRights .= ($_POST['rights_pub']) ? '1' : '0';
    $strUserRights .= ($_POST['rights_feature']) ? '1' : '0';
    switch ($action) {
        case 'apop':
        case 'a': // INSERT      
            foreach ($_POST as $key => $val) {
                if (!in_array($key, $arrUnset)) {
                    $insertArr[$key] = $_POST[$key];
                }
                if ($key == 'password' && $insertArr['password'] != '') {
                    $insertArr['password'] = sha1($insertArr['password']);
                }
            }//eof foreach
            $insertArr['rightsmod'] = $strUserRights;
            $insertArr['cmsmodules_id'] = implode(",", $insertArr['cmsmodules_id']);
            $insertArr['status'] = 1;
            $insertArr['name'] = trim(str_replace('/', ' ', $_POST['name']));
            $insertArr['insertdate'] = date('Y-m-d H:i:s');
            $insertArr['lastvisit'] = date('Y-m-d H:i:s');
            $insertArr['designation'] = $_POST['designation'];
            $insertArr['biodata'] = $_POST['biodata'];
            $insertArr['by_line'] = $_POST['by_line'];
            if ($_POST['by_line'] == 1) {
                unset($insertArr['username']);
                unset($insertArr['password']);
            }
            unset($insertArr['authorthumbnail']);
            unset($insertArr['x']);
            unset($insertArr['y']);
            if (isset($_POST['authorthumbnail'])) {
                $insertArr['thumbnail'] = $_POST['authorthumbnail']['path'];
            }
            $returnVal = $modelObj->insertTable($insertArr);
            $id = $returnVal;
            $subject = 'Welcome to ' . WEB_SITE_NAME . ' CMS';
            $body = 'Your Account is created @ ' . WEB_SITE_NAME . ' CMS for ' . DOMAIN_NAME . ' by its Webmaster.<br>Your login details are:-<br>URL:-<a href="' . CMSSITEPATH . '/">' . CMSSITEPATH . '</a> <br>User Name:-' . $insertArr['username'] . '<br>Password:-' . $_POST['password'];
            $fromEmail = 'Administrator<' . NOREPLY . '>';
            if (isset($insertArr['password']) && $insertArr['password'] != '') {
                sendHTMLMail($insertArr['email'], $subject, $body, $fromEmail);
            }
            break;
        case 'm': // UPDATE		      
            if (isset($_POST['authorthumbnail'])) {
                $updateArr['thumbnail'] = $_POST['authorthumbnail']['path'];
            } else {
                $updateArr['thumbnail'] = $_POST['oldauthorthumbnail'];
            }
            $strUserRights = '';
            $strUserRights .= ($_POST['rights_add']) ? '1' : '0';
            $strUserRights .= ($_POST['rights_edit']) ? '1' : '0';
            $strUserRights .= ($_POST['rights_del']) ? '1' : '0';
            $strUserRights .= ($_POST['rights_pub']) ? '1' : '0';
            $strUserRights .= ($_POST['rights_feature']) ? '1' : '0';
            $updateArr['rights'] = $_POST['rights'];
            $updateArr['rightsmod'] = $strUserRights;
            if (count($_POST['cmsmodules_id']) > 0) {
                $updateArr['cmsmodules_id'] = implode(",", $_POST['cmsmodules_id']);
            }
            $updateArr['email'] = $_POST['email'];
            $updateArr['name'] = $_POST['name'];
            $updateArr['username'] = $_POST['username'];
            $updateArr['biodata'] = $_POST['biodata'];
            $updateArr['designation'] = $_POST['designation'];
            $updateArr['by_line'] = $_POST['by_line'];      
            if ($_POST['password'] != '') {
                $updateArr['password'] = sha1($_POST['password']);
            }
            if ($_POST['by_line'] == 1) {
                unset($updateArr['username']);
                unset($updateArr['password']);
            }
            $whereArr = array('id' => $_POST['id']);
            $returnVal = $modelObj->updateTable($updateArr, $whereArr);      
            $id = $_POST['id'];
            break;
        case 'd': // DELETE
            $returnVal = $modelObj->toggleTableStatus($_POST['id'], '-1');
            $id = $_POST['id'];
            break;
        case 'r':  // RESTORE
            $returnVal = $modelObj->toggleTableStatus($_POST['id'], '1');
            $id = $_POST['id'];
            break;
        case 'e': // EDIT
            echo $data = $modelObj->getEditData($_POST['id']);
            exit;
            break;
        case 'qe':
            $modelObj->db->updateDataIntoTable(array($_POST['column'] => $_POST['columnval']), array('id' => $_POST['id']), 'author');
            exit;
            break;
        case 'graph':
            $authorid = isset($_POST['authorid']) ? $_POST['authorid'] : '';
            $date = isset($_POST['date']) ? $_POST['date'] : '';
            $datearr = explode(",", $date);
            $db = Database::Instance();
            $db->query("SELECT count(id) as cnt, DATE_FORMAT(insertdate,'%M %d, %Y') as date FROM `content` where author_id=" . $authorid . "  and SUBSTR(MONTHNAME(insertdate),1,3)='" . $datearr[0] . "' and year(insertdate)='" . $datearr[1] . "' GROUP BY day(insertdate)");
            $resultdata = $db->getResultSet();
            echo json_encode($resultdata);
            break;    
        case 'story':
            $module = 'content';
            //paging code
            $recperpage = isset($_POST['recperpage']) ? $_POST['recperpage'] : MAX_FORMS_PER_PAGE;
            $pg = isset($_POST['pg']) ? $_POST['pg'] : "";
            $dispfirstpage = isset($_POST['dispfirstpage']) ? $_POST['dispfirstpage'] : "";
            $displastpage = isset($_POST['displastpage']) ? $_POST['displastpage'] : "";
            $search = isset($_POST['search']) ? $_POST['search'] : "first";
            $flag = isset($_POST['flag']) ? $_POST['flag'] : "";
            if (isset($_POST['search']) && $_POST['search'] == 'bydata') {
                $searchData = '';
                $searchDataArray = array();
                if (isset($_POST['freeTextSearch']) && $_POST['freeTextSearch'] != '') {
                    $searchDataArray['headline1'] = $_POST['freeTextSearch'];
                    $searchData .= 'searchByHeadline=' . $_POST['freeTextSearch'] . '&';
                }
                if (isset($_POST['searchByCategory']) && $_POST['searchByCategory'] != '') {
                    $searchId = $_POST['searchByCategory'];
                    $whereVal = array('id' => $searchId);
                    $secArr = $db->getDataFromTable($whereVal, 'category', 'parentid');
                    if ($secArr[0]['parentid'] == 0) {
                        $searchDataArray['category_parentid'] = $_POST['searchByCategory'];
                    } else {
                        $searchDataArray['category_id'] = $_POST['searchByCategory'];
                    }
                    $searchData .= 'searchByCategory=' . $_POST['searchByCategory'] . '&';
                }
                if (isset($_POST['searchByAuthor']) && $_POST['searchByAuthor'] != '') {
                    $searchDataArray['author_id'] = $_POST['searchByAuthor'];
                    $searchData .= 'searchByAuthor=' . $_POST['searchByAuthor'] . '&';
                }
                $searchData = substr($searchData, 0, -1);
            }

            /* Initialize paginate and supply it with necessary params */
            $paginate = new Paginate('content', $search, $searchData, $dispfirstpage, $displastpage, $pg, '', $recperpage);
            $offset = $paginate->offset;
            $recperpage = $paginate->recperpage;
            $objSource = new Source();
            $result_data = $objSource->getContentListingData($search, $offset, $recperpage, $searchDataArray, 1);
            $total = count($result_data);
            $strOutput = '';
            if (!empty($result_data)) {
                foreach ($result_data as $key => $val) {
                    $strOutput .= '<tr id="singleCont' . $val['id'] . '" class="listing" onmouseover="javascript:this.className=\'alternate\'" onmouseout="javascript:this.className=\'listing\'"><td class="pL">' . $val["headline1"] . '</td><td class="pL grayText_big">' . $val["contype_name"] . '</td><td class="pL grayText_big">' . $val["section_name"] . '</td>';
                    if (RIGHTS != 0) {
                        $strOutput .= '<td class="padding5"><div class="actions"> <a href="' . CMSSITEPATH . '/content/managecontent.php?contentid=' . $val['id'] . '&action=m" class="edit" target="_blank">' . $val['id'] . '</a><a href="' . getPreviewLink($val['contype_id'], $val['id']) . '" target="_blank" class="preview" title="Preview">Preview</a><a href="javascript:void(0);" class="log" onclick="ModalBox.open(\'' . CMSSITEPATH . '/adminlog/display.php?hide_layout=1&record_id=' . $val['id'] . '\', 850, 550);" href="javascript:void(0);" title="View Story Updates">Log</a></div></td>';
                    }
                    $strOutput .= '</tr>';
                } //eof foreach $result_data 
                $strOutput .= '<tr><td colspan="5">';
                $sql_count = $objSource->getContentPagination($search, $searchDataArray, 1);
                $strOutput .= $paginate->render($sql_count);
                $strOutput .= '</td></tr>';
            } else {
                $strOutput .= '<tr><td colspan="6" class="pL pTB" id="norecordsdiv">No Records</td></tr>';
            }
            echo $strOutput;
            exit;
    default:
        break;
  } // eof switch $action

    if ($returnVal >= 0) {
        $msg = ressuccessmsg($action);
        $status = 1;
    } else {
        $msg = resfailedmsg($action);
        $status = 0;
    }
    if ($action == 'a' || $action == 'm') {
        header("Location: " . CMSSITEPATH . "/" . CUR_DIR . "/display.php?msg=$msg");
        echo '<script>';
        echo 'window.location="' . CMSSITEPATH . '/'. CUR_DIR .'/display.php?msg=' . $msg . '"';
        echo '</script>';
    }
    if ($action != 'tc' && $action != 'graph') {
        $jsonStr = "{'msg':'$msg','status':'$status','action':'$action','id':'$id','module':'" . CUR_DIR . "'}";
        echo $jsonStr;
    }
} else { ?>
  <div class="module_<?php echo CUR_DIR; ?>">
    <form name="listform" id="listform">
<?php
    if ($action == 'tc' || $displaypage == 'trashcan') {
        $statusData = '-1';
    } else {
        $statusData = '1';
    }
    $modulePath = CMSSITEPATH . "/" . CUR_DIR;
    $result_data = $modelObj->getListingData($search, $offset, $recperpage, $searchDataArray, $statusData, $sortBy, $sortSeq);
    $total = $modelObj->getPagination($search, $searchDataArray, $statusData);
?>
    <script type="text/javascript">
    if(typeof(GlobalSearch)!="undefined") {
      GlobalSearch.addCount("<?php echo CUR_DIR; ?>", "<?php echo $total; ?>");
    }
    </script>
<?php
    $pageType = (Common::l($_POST['viewType']) == '') ? $_SESSION['ITUser']['viewType'] : Common::l($_POST['viewType']);
    $_SESSION['ITUser']['viewType'] = $pageType;
    if ($pageType == "grid") {
        include("tpl-grid.php");
    } else {
        include("tpl-listing.php");
    }
?>
    </form>
  </div>
<?php
}
unset($_POST);
