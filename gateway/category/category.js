var MODULE_NAME = 'section';

function cleardivimag(){
	$('#imagecrossdiv').hide('');
	$('#oldimageid').val('');
}

function getContent() {//----------Function to send data to 2db file after validation
	$('.error').hide();
	var flag = 0;
	var sendreqfuc = 0;
	var section_name = Trimnew($('#sectionname').val());
	var sectionthumbnail = $('#sectionthumbnail').val();

	if(section_name == "") {
		$('label#sectionname_error').show();
		$('label#sectionname_error').html('&nbsp;&nbsp;Please Enter Section Name');
		$('#sectionname').focus();
		flag = 1;
	} else if(!(/[a-zA-Z0-9]/.test(section_name))) {
		$('label#sectionname_error').html('Section name should not conatin any special character');
		$('label#sectionname_error').show();
		$('#sectionname').focus();
		flag = 1;
	}
	if(flag == 0) {//**** If there is an Image to be uploaded, upload & add it to the datastring
		if(sectionthumbnail != "") {
			var resultcase = isValidImage(sectionthumbnail);
			switch (resultcase) {
				case 1:
					alert('The uploaded file is not a valid Image. Please upload only .jpg or .gif');
					sendrequest();
					break;
				case 2:
					alert('The uploaded filename is not valid');
					sendrequest();
					break;
				case 0:
					ajaxFileUpload('sectionthumbnail', 'sectionthumbnail', "section");
					sendreqfuc = 1;
					break;
			}
		}
		$('#savebutton').attr('disabled', 'disabled');
		$('#formloading').show();
		$('#dataimage').html('');

		if(sectionthumbnail == "" && sendreqfuc == 0) {
			sendrequest();
		}
	}
}

function checkavailable() {
	var sectionname = Trimnew($('#sectionname').val());
	var id;
	if($('#id').val() != "") {
		id = $('#id').val();
	} else {
		id = "";
	}
	$.post('sectionavailibility.php', {
		'val' : sectionname,
		'id' : id
	}, function(response) {
		if(response ==1) {
			$('#sectionname').val('');
			$('#sectionname').focus();
			$('#sectionnameavailable_error').show();
		} else {
			$('#sectionnameavailable_error').hide();
		}
	});
}

function checkavailablelist(id) {
	var sectionname = Trimnew($('#hdlnval'+id).val());
	
	$.post('sectionavailibility.php', {
		'val' : sectionname,
		'id' : id
	}, function(response) {
		if(response ==1) {
			$('#hdlnval'+id).val('');
			$('#hdlnval'+id).focus();
			$('#sectionnameavailable_error'+id).show();
		} else {
			$('#sectionnameavailable_error'+id).hide();
		}
	});
}

//This function sends data to be processed
function sendrequest(imagename) {
	var datastring = $('form').serialize();
	if( typeof (imagename) != 'undefined' && imagename != "") {
		datastring = datastring + "&thumbnail=" + imagename;
	}
	$.ajax({
		type : "POST",
		url : "get" + MODULE_NAME + ".php",
		data : datastring,
		success : function (resultdata){
			/* code to append latest added option to drop down	*/
			var jObj=eval("("+resultdata+")");
			$('#tdSection').html(jObj.strddl);
			displayContent(resultdata);	
		}
	});
	$('#addpassword').show();
	$('#editpassword').hide();
}



function showEdit(data) {//----------response from get file for showing edit form
	var details = eval("(" + data + ")");
	document.getElementById('sectionname').value = '' + details[0].name;
	document.getElementById('metakeyword').value = '' + details[0].metakeyword;
	document.getElementById('metatitle').value = '' + details[0].metatitle;
	document.getElementById('metadescription').value = '' + details[0].metadescription;
	document.getElementById('parentsectionname').value = '' + details[0].parentid;
	document.getElementById('id').value = '' + details[0].id;
	if(details[0].is_tab == 1) {
		document.getElementById('is_tab').checked = true;
	} else {
		document.getElementById('is_tab').checked = false;
	}
        
	$('#oldimageid').val(details[0].oldthumbnail);

	if(details[0].thumbnail != '' && details[0].thumbnail != null){
			$('#dataimage').html('<img src="'+IMAGEMEDIAPATH+"/"+details[0].thumbnail+'" alt="" id="imgtag"  height="100px" width="100px" style="display: block;" />');
			 $("#delImg,#crossImg").show();	 
	}
	if($('#imagecrossdiv')){
		$('#imagecrossdiv').show('');
	}
	$('#action').val("m");
	$("#displaycontent").hide();
	$("#editcontent").show();
    $('#sectionname').attr('readonly', 'readonly');
	$('#old_sectionname').val(details[0].name);
	
}

function getTrash() {
	/*	getReset();
	$.ajax({
		type : "POST",
		url : "get" + MODULE_NAME + ".php",
		data : "action=tc",
		success : function(resultdata) {
			$('#mainContainer').html(resultdata);
		}
	});*/
	$("#displaypage").val("trashcan");
	$('#divTrash').html('<a href="javascript: void(0)" onclick="getDisplay()"><span class="iconBack">&nbsp;</span>Back To Section</a>');
	searchform();
}

function getDisplay(){
		/*getReset();
	$.ajax({
		   type: "POST",
		   url : "get" + MODULE_NAME + ".php",
		   success: function(resultdata){
			   		$('#mainContainer').html(resultdata); 
		   }
	});	*/
	$("#displaypage").val("listing");
	$('#divTrash').html('<a href="javascript: void(0)" onclick="getTrash()"><span class="iconTrash">&nbsp;</span>Trash Can</a>');
	searchform();
}

function searchform(){
	$("#searchForm .current").removeClass("current");
	$("#searchForm select[value!='']").addClass("current");
	$("#searchForm input:text[value!='Type Section Name Here']").addClass("current");
	if($("#searchForm .current").length>0) {
		FX.highlight("#searchForm .current");
	}
	searchdata('section','bydata','','','','','','') ;
	
}

function resetGrayButton() {
	$(".btnGraySelected").removeClass("btnGraySelected");
}


function closeMorePopup(t) {
	$(t).parent().slideUp("fast", function() {
		resetGrayButton();
	});
}

function updateColor(id, st) {
	var id_ = id;
	var status = st;
	var mainHolder = "singleCont"+id_;
	if(status==1) {
		$("#"+mainHolder + "").find(".contentid").addClass("greenBG").removeClass("blueBG");
	} else {
		$("#"+mainHolder + "").find(".contentid").addClass("blueBG").removeClass("greenBG");
	}
}

function searchByAuthorId(v) {
	$("#searchByAuthor").val(v);
	searchContent();
}
function searchByContentType(v) {
	$("#searchByContype").val(v);
	searchContent();
}
function searchByCategory(v) {
	$("#searchByCategory").val(v);
	searchContent();
}
function changeListingType(h, t) {
	var holder = h;
	$(".currentGrid").removeClass("currentGrid");
	$("#viewType").val(t);
	$(holder).addClass("currentGrid");
	searchContent();
}

function showPreviewLink(id) {
	DialogBox.showAlert("Preview will be displayed later...");
}

function resetSearch() {
	document.getElementById("searchForm").reset(); 
	searchContent();
}

function save_priority(val,txtid){
	if(isNumeric(val) == false ){
		alert("Only numeric values allowed");
		$('#priority'+txtid).val("");
		$('#priority'+txtid).focus();
	}else{
		if(val != ''){
			$.post("getsection.php", {'txtid': txtid,'txtpriority':val,'action': 'priority'}, 
				function(data) {   		
				$('#changepriority'+txtid).html(val);
				$('#priority'+txtid).val("");
			});	
		} 
	}
}

function searchBySectionInContent(cid) {
 	var cat_id = cid;
	window.location = "../content/display.php?searchByCategory="+cat_id;   
}