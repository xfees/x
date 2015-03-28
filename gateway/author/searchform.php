<?php
$conn = Database::Instance();
?>
<script type="text/javascript">
function searchContent() {
	searchform();
	return false;
}
</script>
	<form name="searchForm" id="searchForm" action="" onsubmit="return searchContent()">
	<div class="searchdiv">
		<table border="0" cellspacing="1" cellpadding="2" class="searchTable">
			<tbody>
				<tr>
					<!--<td>Author Type</td>-->
					<td>
					<input type="hidden" name="displaypage" id="displaypage" value="listing" />
					<?php
					  $conn->query("select id,rights from author group by rights");
					  while($types_of_author = $conn->fetch()) {
					  	$type[] = $types_of_author;
					  }
					?>
					<select name="searchByAuthorType" id="searchByAuthorType" class="inputSelectControl1 blueText">
						<option value="">Select Author Rights</option>
						<?php
						foreach($type as $s){
							?>
					<option <?php if($_GET['searchByAuthorType']!='' && $_GET['searchByAuthorType']==$s['rights']){ ?> selected="selected" <?php } ?> value="<?php echo $s['rights']; ?>"><?php echo $userDetails[$s['rights']]; ?></option>
					<?php	} ?>
					</select>
					</td>
					<td>
					 <select name="searchByAuthorByline" id="searchByAuthorByline" class="inputSelectControl1 blueText">
						<option value="">Select Author Type</option>
						<option value="1">By Line</option>
						<option value="2">CMS User</option>
						<option value="3">Both</option>
						</select>
						</td>
					<td>
					  <input type="text" id="freeTextSearch" name="searchByAuthorName" class="inputWizard2" value="Search By Name" />
					</td>
					<!--<td>Search Name</td>-->
					<!--<td> Search Email</td>-->
					<td>
					  <input type="text" id="searchByEmail" name="searchByEmail" class="inputWizard2" value="Search by Email" />
					  </td>
					<td> 
						<select id="sortBy" name="sortBy" class="inputSelectControl1 blueText">
							<option value="">Sort By</option>
							<option value="name">Name</option>
							<option value="cnt">Story Count</option>
						</select>
					</td>
					<td>
						<select id="sortSeq" name="sortSeq" class="inputSelectControl1 blueText">
							<option value="ASC">ASC</option>
							<option value="DESC">DESC</option>
						</select>
					</td>
					<td><input type="submit" value="Search" class="btnSubmit" id="submitFrm" name="submitFrm" />
					<input onclick="resetSearch();" class="btntool" type="reset" value="Reset" title="Clear Search" />
					</td>
					<td style="background:#fff">
					<?php 
					$getCSSGrid = ($_SESSION['ITUser']['viewType']=='grid') ? "currentGrid" : "";
					$getCSSList = ($_SESSION['ITUser']['viewType']!='grid') ? "currentGrid" : "";
					?>
					<span class="iconGrid hand <?php echo $getCSSGrid?>" title="Grid View" onclick="changeListingType(this, 'grid')"></span><span class="iconList hand <?php echo $getCSSList;?>" title="List View"  onclick="changeListingType(this, 'list')"></span>
					<input type="hidden" name="viewType" id="viewType" value="<?php echo $_SESSION['ITUser']['viewType'];?>" />
					</td>
				</tr>
			</tbody>
		</table>
		</div>
	</form>

<div style="clear: both;height: 10px;"></div>
