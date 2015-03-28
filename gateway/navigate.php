	<script type='text/javascript' src='<?php echo JSFILEPATH;?>/ts_picker.js'></script>
<?php
$module=strtolower($_SESSION['TOPMENU']);
$ctr = 0;

$action_search = ($action == 'tc')?$action:'';
?>
<form method="post" action="javascript:void(0);" id="navigateForm" name="navigateForm">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="bottom"> 
				<div id="aCategories" align="left" class="alphabet">
					<?php if(strrpos($module,"popup") )
					{
					?>
						<a href="javascript:void(0);" id="searchparam09" 
						<?php if(isset($_POST['data']) && $_POST['data'] == "09") { echo ' class="selected" '; $ctr++; }?>
						onclick="searchdata('<?php echo strtolower($module); ?>','integer','09'+';*;*'+document.navigateForm.publishval.value,'','','','','<?php echo $action_search;?>','10');">0-9</a> |
						<?php  for($start=65; $start<91; $start++) {  ?>
						<a href="javascript:void(0);" id="searchparam<?php echo chr($start); ?>" 
						<?php if(isset($_POST['data']) && $_POST['data'] == chr($start)) { echo ' class="selected" '; $ctr++; } ?>
						onclick="searchdata('<?php echo $module; ?>','byname','<?php echo chr($start); ?>'+';*;*'+document.navigateForm.publishval.value,'','','','','<?php echo $action_search;?>','');"><?php echo chr($start); ?></a>
						<?php  } ?>	
						| <a href="javascript:void(0);" id="searchparamALL" 
						<?php if($ctr == 0) echo ' class="selected" '; ?>
						onclick="searchdata('<?php echo $module; ?>','first','ALL'+';*;*'+document.navigateForm.publishval.value,'','','','','<?php echo $action_search;?>','');">ALL</a>
					<?php
					}
					else
					{
					?>	
						<a href="javascript:void(0);" id="searchparam09" 
						<?php if(isset($_POST['data']) && $_POST['data'] == "09") { echo ' class="selected" '; $ctr++; }?>
						onclick="searchdata('<?php echo strtolower($module); ?>','integer','09','','','','','<?php echo $action_search;?>','10');">0-9</a> |
						<?php  for($start=65; $start<91; $start++) {  ?>
						<a href="javascript:void(0);" id="searchparam<?php echo chr($start); ?>" 
						<?php if(isset($_POST['data']) && $_POST['data'] == chr($start)) { echo ' class="selected" '; $ctr++; } ?>
						onclick="searchdata('<?php echo $module; ?>','byname','<?php echo chr($start); ?>','','','','','<?php echo $action_search;?>','');"><?php echo chr($start); ?></a>
						<?php  } ?>	
						| <a href="javascript:void(0);" id="searchparamALL" 
						<?php if($ctr == 0) echo ' class="selected" '; ?>
						onclick="searchdata('<?php echo $module; ?>','first','ALL','','','','','<?php echo $action_search;?>','');">ALL</a>
					<?php
					}
					?>
					
				</div>	
			</td>
            <?php
			if($module == 'section'){
			?>
            <td align="right" valign="top"> 
				<div> 
				<?php 
					if($_POST['search']=='by_section' && $_POST['data'] !=''){
						$val = $_POST['data'];
					}else{
						$val = '';
					}
					//echo $val;
				?>
					<select id="contentsection" name="contentsection" onchange="searchdata('<?php echo $module; ?>','by_section',this.value,'','','','','');">
				<option value="">Search by Parent Section</option>
			<?php
					$secObj = new Section();
					$secArr = $secObj->getParentSectionTree($val);
					foreach($secArr as $key=>$val)
					{
			?>
						<option value="<?php echo $key;?>" <?php if($_POST['search']=='by_section' && $_POST['data'] == $key) {?> selected="selected" <?php } ?>>
						<?php echo $val;?>
						</option>
			<?php 		
					}
			?>											
				</select>
				</div>
			</td>
            <?php } ?>
			

			<td align="right" valign="top"> 
				
				<?php if(strrpos($module,"popup"))
			{
				if(strrpos($module,"uote"))
				{
			?>
			 <input type="hidden" name="publishval" id="publishval" value="" style="width:10%;height:20px;" />
			
			 <?php
				}
					else
				{
			?>
			 <input type="text" name="publishval" id="publishval" value="" style="width:10%;height:20px;" class="inputForm calendar" onclick="show_calendar('document.navigateForm.publishval', document.navigateForm.publishval.value);"/>
			<?php
				}
			?>
			<div class="search">
			 <input type="image" src="<?php echo IMAGEPATH; ?>/btn-search.gif" align="right"  onclick="var input=this.form.autoTextBox1.value+';*;*'+this.form.publishval.value;javascript:searchdata('<?php echo $module; ?>', 'byname', input, '', '','','','<?php echo $action_search;?>', '')" /><input name="textSearch" id="autoTextBox1"  name="autoTextBox1" type="text" class="input"  />
				</div>
			<?php
			}
			else
			{
			?>
				<div class="search">
					<input type="image" src="<?php echo IMAGEPATH; ?>/btn-search.gif" align="right"  onclick="javascript:searchdata('<?php echo $module; ?>', 'byname', this.form.autoTextBox1.value, '', '','','','<?php echo $action_search;?>', '')" /><input name="textSearch" id="autoTextBox1"  name="autoTextBox1" type="text" class="input"  />
				</div>
			<?php
			}
			?>     
			</td>
		</tr>
	</table>
</form>