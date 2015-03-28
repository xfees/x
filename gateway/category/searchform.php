<?php
$db = Database::Instance();
?>
<script type="text/javascript">
	function searchContent() {
		searchform();
		return false;
	}

	function alignArrow() {

	}

	function showFreeBox() {
		FX.slideDown("#freeTextOptions");
	}

	//checks that an input string is an integer, with an optional +/- sign character.
	function isInteger(s) {
		var isInteger_re = /^\s*(\+|-)?\d+\s*$/;
		return String(s).search(isInteger_re) != -1;
	}
</script>
<form name="searchForm" id="searchForm" action="" onsubmit="return searchContent()">
	<div class="searchdiv">
		<table border="0" cellspacing="1" cellpadding="2" class="searchTable">
			<tr>
				<td><?php
				$sel_cat = (Common::l($_POST['searchByParentSection']) == '') ? 'Select category' : Common::l($_POST['searchByParentSection']);
				echo Common::getCategoryCombo("searchByParentSection", $sel_cat, 'class="inputSelectControl1 blueText"');
				?></td>
				
			
				<!--<td>
				<input type="text" name="searchBySectionName" autocomplete="off" id="searchBySectionName" class="inputWizard2 freetext-search" size="10" value="Type Section Name Here" onblur="javascript: if(this.value == '') this.value = 'Type Section Name Here';" onfocus="javascript: showFreeBox(); if(this.value == 'Type Section Name Here') this.value = '';" />
				<div class="popdown boxRound abs" id="freeTextOptions" style="display:none">
					<span class="ui-icon ui-icon-circle-close icon-close"  onclick="closeMorePopup(this);"  title="close"></span>
					<label for="section_chk">Search by Section Name</label>
					<input type="hidden" name="displaypage" id="displaypage" value="listing" />
				</div></td>-->
				<td><span class="btnset">
					<input type="submit" value="Search" class="btnSubmit btntool" id="submitFrm" name="submitFrm" />
					<input onclick="resetSearch()" class="btntool" type="button" value="Reset" title="Clear Search" />
				</span><?php
				$getCSSGrid = ($_SESSION['ITUser']['viewType'] == 'grid') ? "currentGrid" : "";
				$getCSSList = ($_SESSION['ITUser']['viewType'] != 'grid') ? "currentGrid" : "";
				?>
				<input type="hidden" name="viewType" id="viewType" value="<?php echo $_SESSION['ITUser']['viewType'];?>" />
				</td>
			</tr>
		</table>
	</div>
</form>
