<?php
$db = Database::Instance();

$db_list = array();
foreach ( $dbdetails as $key => $val )
{
  $db_list[$key] = $key;
}

$auth_id = '';
$object_author = new Author();
$authors = $object_author->getAuthors($auth_id);

$a_id = NULL; $r_id = NULL;

if ( isset($_POST['searchByAuthor']) && !empty($_POST['searchByAuthor']) )
{
  $a_id = $_POST['searchByAuthor'];
}
else if ( isset($_GET['searchByAuthor']) && !empty($_GET['searchByAuthor']) )
{
  $a_id = $_GET['searchByAuthor'];
}
if ( isset($_POST['record_id']) && !empty($_POST['record_id']) )
{
  $r_id = $_POST['record_id'];
}
else if ( isset($_GET['record_id']) && !empty($_GET['record_id']) )
{
  $r_id = $_GET['record_id'];
}
if ( isset($_POST['searchByAction']) && !empty($_POST['searchByAction']) )
{
  $act_id = $_POST['searchByAction'];
}
else if ( isset($_GET['searchByAction']) && !empty($_GET['searchByAction']) )
{
  $act_id = $_GET['searchByAction'];
}
$rec_id='';
if ( isset($_POST['searchById']) && !empty($_POST['searchById']) )
{
  $rec_id = $_POST['searchById'];
}
else if ( isset($_GET['searchById']) && !empty($_GET['searchById']) )
{
  $rec_id = $_GET['searchById'];
}
$s_module_id = isset($_POST['module_id']) ? $_POST['module_id'] : NULL;
$s_insertdate = isset($_POST['insertdate']) ? $_POST['insertdate'] : NULL;
$actions=array('a'=>'Added','d'=>'Deleted','p'=>'Published','u'=>'Unpublished','r'=>'Restored','m'=>'Modified','login'=>'Login');
?>

<script type="text/javascript">
function searchContent() {
	searchform();
	return false;
}
</script>

<form name="searchForm" id="searchForm" onSubmit="return false" action="">
  <div class="searchdiv">

    <table border="0" cellspacing="1" cellpadding="2" class="searchTable">
      <tr>
        <td>
          <!--<select class="inputSelectControl1" name="db_name" id="db_name">
            <?php foreach ( $db_list as $db_key => $db_val ): ?>
              <option value="<?php echo $db_key; ?>" <?php if ( $db_key == $_POST['db_name'] ): ?> selected="selected" <?php endif ?> ><?php echo $db_key; ?></option>
            <?php endforeach ?>              
          </select>-->
		 <select id="searchByAction" name="searchByAction" class="inputSelectControl1">
            <option value="">All Actions</option>
            <?php foreach ( $actions as $key => $value ): ?>
              <option value="<?php echo $key ?>" <?php if ( $key == $act_id ): ?> selected="selected" <?php endif ?> ><?php echo $value; ?></option>
            <?php endforeach ?>              
          </select>
        </td>
		<td>
          <input type="text" name="insertdate" id="insertdate" class="calendar2 inputWizard2 search-date" size="5" value="<?php echo $s_insertdate; ?>" />
          <script type="text/javascript">$(function (){$('#insertdate').datepicker({dateFormat:'dd-mm-yy'});});</script>
        </td>
        <td>
          <input type="text" id="searchById" name="searchById" class="inputWizard2" value="Search by Id" <?php echo $rec_id;?>/>
        </td>
        <td>
          <select id="searchByAuthor" name="searchByAuthor" class="inputSelectControl1">
            <option value="">All Authors</option>
            <?php foreach ( $authors as $author ): ?>
              <option value="<?php echo $author['id'] ?>" <?php if ( $author['id'] == $a_id ): ?> selected="selected" <?php endif ?> ><?php echo $author['name']; ?></option>
            <?php endforeach ?>              
          </select>
        </td>
		<?php if($r_id == NULL) {?>
        <td>
          <select id="module_id" name="module_id" class="inputSelectControl1">
              <option value="">All Modules</option>
              <?php echo getModulesListByname($s_module_id) ?>
            </select>	
        </td>
		<?php } ?>
        <td>
          <input type="submit" value="Search" onclick="searchform();" class="btnSubmit" id="submitFrm" name="submitFrm" />
		  <input type="hidden" value="<?php echo $r_id; ?>" name="record_id" />
		  <input onclick="resetSearch()" class="btntool" type="button" value="x" title="Clear Search" />          
        </td>        
      </tr>
    </table>					
  </div>
</form>
