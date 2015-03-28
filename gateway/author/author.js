var MODULE_NAME='author';
function cleardivimag(){
	$('#imagecrossdiv').hide('');
	$('#authoroldthumbnail').val('');
}
function saveAuthor(){
    var flag=0;
	checkauthnameexist();
	checkavailable();
    if ($('#usernameavailable_error').css("display") != "none") {
        flag=1;	
    }
    if ($('#nameavailable_error').css("display") != "none"  ) {
        flag=1;	
    }
	if ($('#existemail_error').css("display") != "none"  ) {
        flag=1;	
    }
    $('.error').hide();
    var name=Trimnew($('#nameid').val());
    var username=Trimnew($('#usernameid').val()); //alert(username);
    var password=Trimnew($('#passwordid').val()); 
    var email=$('#emailid').val();
    var readonly=$('#readonly').attr('checked');
    var author=$('#author').attr('checked');
    var authorthumbnail = $('#authorthumbnail').val();
    var admin=$('#admin').attr('checked');
    var module=$('#modules').val();		
	var by_line=$('input:radio[name=by_line]:checked').length;
	var by_line_val=0;
	var sendreqfuc=0;
    if(document.getElementById('editpassword').style.display=='block') {
        var editform = 1;
    }
    else {
        var editform = 0;
    } 
	if(by_line==0 && flag!=1){
		$('label#by_line_error').show();
		flag=1;
	}
	else
	{
		by_line_val=$('input:radio[name=by_line]:checked').val();
	}
    if(name=="" && flag!=1) {
        $('label#name_error').show();
		$('label#name_error').html('&nbsp;&nbsp;Please Enter Name');
        $('#nameid').focus();
        flag=1;
    } else if(!(/[a-zA-Z0-9]/.test(name))){
		$('label#name_error').html('Author name should not conatin any special character');
		$('label#name_error').show();
        $('#nameid').focus();
		flag=1;
	}
	 if(email=="" && flag!=1) {
        $('label#email_error').show();
        $('#emailid').focus();
        flag=1;
    }
    if(email != "" && flag!=1) {
        emailvalid=isValidEmail(email);	
        if(emailvalid!=true) {
            $('label#invalidemail_error').show();
            $('#emailid').focus();			
            flag=1;		
        }
    }
	
   if(username=="" && flag!=1 && by_line_val!=1) {
        $('label#username_error').show();
        $('#usernameid').focus();
        flag=1;
    }
   if(password=="" && editform==0 && flag!=1 && by_line_val!=1) {
        $('label#password_error').show();
        $('#passwordid').focus();
        flag=1;
    }
   if((module=="" || module==null) && flag!=1 && by_line_val!=1) {
        $('label#module_error').show();
        $('#modules').focus();		
        flag=1;	
   }
   if(readonly==false && author==false && admin==false && flag!=1 && by_line_val!=1) {
        $('label#right_error').show();
        $('#readonly').focus();
        flag=1;
    }	
	if(username != "" && flag!=1 && by_line_val!=1) {
		usernamevalid=checkUserName(username);	
		if(usernamevalid!=true)	{
			$('label#username_error').show();
			$('#nameid').focus();	
			flag=1;		
		}
			
	}//if username!=''
    //else total
    if (password != "" && flag!=1 && by_line_val!=1) {
        passvalid=passwordLength(password);	
        if(passvalid!=true)
        {						
            $('#passwordid').focus();	
            $('label#invalidpassword_error').show();						
            flag=1;		
        }	
    }
    
    if(flag==0){//**** If there is an Image to be uploaded, upload & add it to the datastring
        /*if(authorthumbnail != "" ) {
            var resultcase=isValidImage(authorthumbnail);	
            switch (resultcase)
            {
                case 1:
                    alert('The uploaded file is not a valid Image. Please upload only .jpg or .gif');
                    sendrequest();
                    break;
                case 2:
                    alert('The uploaded filename is not valid');
                    sendrequest();
                    break;	
                case 0:
                    ajaxFileUpload('authorthumbnail','authorthumbnail',"author");
                    sendreqfuc=1;
                    break;
            }            	
        }*/	
    }
    if(flag == 1) {
        return false;
    } else {
        $('#savebutton').attr('disabled','disabled');	
        $('#formloading').show();
        $('#dataimage').html('');
        return true;
    }
}

/******* To Check Username *****************/
function checkUserName(username){
    var valid="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ._-";
    var num=0;
    var alphabet=0;
    var extra=0;
    var spacecnt=0;
    var temp=username;
    var usernamelen=temp.length;
    if (usernamelen>25) {
        alert("Your username cannot be greater than 25 characters.");		
        return false;
    } else {
        for(var j=0; j<usernamelen; j++) {	
            if (valid.indexOf(username.charAt(j)) > 0) {
                continue;
            } else {
                extra=1;
                break;
            }
        }
    }
    if(extra  == 1) {
        alert("Your username can have only alphabets or numbers.");		
        return false;
    } else {
        return true;
    }
}

function checkavailable(){
    var username=Trimnew($('#usernameid').val()); 
    if(username != '' ){
        var id;
        if($('#id').val() != "")
        {
            id=$('#id').val();
        }
        else
        {
            id="";
        }
		$.post('authoravailibility.php',{'val':username,'id':id},function(response){ 
            if(response != 0)
            {
                $('#usernameid').val();
                $('#usernameavailable_error').show();
                $('#username_error').hide();
            }else{
                $('#usernameavailable_error').hide();
                $('#username_error').hide();
            }
        });
    }else{
        $('#username_error').show();
        $('#usernameavailable_error').hide();
    }
}

function checkemailexist()
{
    var email=Trimnew($('#emailid').val()); 
    var flag=0;
    var id;
    if($('#id').val() != "")
    {
        id=$('#id').val();
    }
    else
    {
        id="";
    }
    if(email=="") {
        $('label#email_error').show();
        $('#emailid').focus();
        flag=1;
    }
    else if(email != "") {
        emailvalid=isValidEmail(email);	
        if(emailvalid!=true) {
            $('label#invalidemail_error').show();
            $('label#email_error').hide();
            $('#emailid').focus();	
            flag=1;
        }
    }
    if(flag!=1)
    {
        $('label#invalidemail_error').hide();
        $('label#email_error').hide();
        $.post('authoravailibility.php',{'email':email,'id':id},function(response){ 
            if(response != 0)
            {
                $('#emailid').val();
                $('#existemail_error').show();
            } else {
                $('#existemail_error').hide();
            }
        });
    }   
}

function checkauthnameexist() {
    var username=Trimnew($('#nameid').val()); 
	var by_line_val=$('input:radio[name=by_line]:checked').val();
	var by_line=$('input:radio[name=by_line]:checked').length;
	if(username != '' && by_line!=0){
        var id;
        if($('#id').val() != "")
        {
            id=$('#id').val();
        }
        else
        {
            id="";
        }
		
		$.post('authoravailibility.php', {'authorname':username, 'id':id, 'by_line':by_line_val}, function(response) { 
            if(response != 0)
            {
                $('#usernameid').val();
                $('#nameavailable_error').show();
				$('#by_line_error').hide();
                $('#name_error').hide();
            } else {
                $('#nameavailable_error').hide();
				$('#by_line_error').hide();
                $('#name_error').hide();
            }
        });
    } else {
        if(by_line == 0 && username == '')
		{
			$('#by_line_error').show();
			$('#name_error').show();
		}
		else if(by_line == 0)
		{
			$('#by_line_error').show();
			$('#name_error').hide();
		}
		else if(username == '')
		{
			$('#name_error').show();
			$('#by_line_error').hide();
		}
        $('#nameavailable_error').hide();
    }
}
//This function sends data to be processed
function sendrequest(imagename){ 
    var datastring=$('#dataform').serialize();		
    if(typeof(imagename) != 'undefined' && imagename != "")	{
        datastring=datastring+"&thumbnail="+imagename;
    }	
    datastring += "&update=true"; //alert(datastring)
    $.ajax({
        type: "POST",
        url: "getauthor.php",
        data: datastring,
        success: displayContent
    });
    $('#addpassword').show();
    $('#editpassword').hide();
}
	
function showEdit(data){	//alert('here')		//----------response from get file for showing edit form
    $('.error1').hide();
    var jObj=JSON.parse(data); //alert(jObj);
    var id=jObj[0].id;
    var rightsArray = jObj[0].rightsmod;
        
    $('#id').val(jObj[0].id);
    $('#nameid').val(jObj[0].name);
    $('#usernameid').val(jObj[0].username);
    $('#oldusernameid').val(jObj[0].username);
    $('#emailid').val(jObj[0].email);
    $('#designation').val(jObj[0].designation);
    $('#biodata').val(jObj[0].biodata);
    $('#section_id').val(jObj[0].section_id);
    $('#twitter').val(jObj[0].twitter);
    $('#facebook').val(jObj[0].facebook);
    $('#websiteurl').val(jObj[0].websiteurl);
	if($('#imagecrossdiv')) {
		$('#imagecrossdiv').show('');
	}
	if(jObj[0].is_columnist==1) {
	document.getElementById('is_columnist').checked = true;
	} else {
	document.getElementById('is_columnist').checked = false;
	}
    $('#changepassid').click(function(){
        ModalBox.open(CMSROOTPATH+'/author/changepassword.php?id='+id,700,500);
    });
    var moduleobj=jObj[0].cmsmodules_id;
    var modulearray=jObj[0].cmsmodules_id || [];	
    modulearray=moduleobj.split(',');	
    $('#modules').val(modulearray);
		
    if(jObj[0].password!='') {
        $('#addpassword').hide();
        document.getElementById('editpassword').style.display='block';
    }
    $('#action').val("m");
    var rights=jObj[0].rights;
    if (rights =='2') {
        $('#accesstype').show();
        $('#author').attr('checked','checked');
    } else if(rights =='1') {
        $('#accesstype').show();
        $('#admin').attr('checked','checked');		
    } else if(rights =='3') {
        $('#accesstype').show();
        $('#developer').attr('checked','checked');		
    } else {
        $('#accesstype').hide();
        $('#readonly').attr('checked','checked');
    }
    (rightsArray[0] == '1')?$('#rights_add').attr('checked',true):$('#rights_add').attr('checked',false);
    (rightsArray[1] == '1')?$('#rights_edit').attr('checked',true):$('#rights_edit').attr('checked',false);
    (rightsArray[2] == '1')?$('#rights_del').attr('checked',true):$('#rights_del').attr('checked',false);
    (rightsArray[3] == '1')?$('#rights_pub').attr('checked',true):$('#rights_pub').attr('checked',false);
    (rightsArray[4] == '1')?$('#rights_feature').attr('checked',true):$('#rights_feature').attr('checked',false);

    $('#authorthumbnail').val('');
    var thumb=jObj[0].thumbnail;	
    $('#authoroldthumbnail').val(jObj[0].oldthumbnail);
    $("#displaycontent").hide();
    $("#editcontent").show();
}

function getTrash(){
    /*	$.ajax({
		   type: "POST",
		   url: "getauthor.php",
		   data: "action=tc",
		   success: function(resultdata){
			   		$('#mainContainer').html(resultdata); 
		   }
	});	*/
    $('#displaypage').val('trashcan');
    $('#divTrash').html('<a href="javascript: void(0)" onclick="getDisplay()"><span class="iconBack">&nbsp;</span>Back To Author</a>');
    searchform();

}

function getDisplay() {
    $.ajax({
        type: "POST",
        url: "getauthor.php",
        success: function(resultdata){
            $('#mainContainer').html(resultdata); 
        }
    });
    $('#displaypage').val('display');
    $('#divTrash').html('<a href="javascript: void(0)" onclick="getTrash()"><span class="iconTrash">&nbsp;</span>Trash Can</a>');
}

function setDefultRights( val){
    var intVal = parseInt(val);
    switch (intVal)
    {
        case 1:
            $('#accesstype').show();
            $('#rights_add').attr('checked',true);
            $('#rights_del').attr('checked',true);
            $('#rights_edit').attr('checked',true);
            $('#rights_pub').attr('checked',true);
            $('#rights_feature').attr('checked',true);
            break;
        case 2:
            $('#accesstype').show();
            $('#rights_add').attr('checked',true);
            $('#rights_del').attr('checked',false);
            $('#rights_edit').attr('checked',true);
            $('#rights_pub').attr('checked',false);
            $('#rights_feature').attr('checked',false);
            break;
        case 3:
            $('#accesstype').show();
            $('#rights_add').attr('checked',true);
            $('#rights_del').attr('checked',true);
            $('#rights_edit').attr('checked',true);
            $('#rights_pub').attr('checked',true);
            $('#rights_feature').attr('checked',true);
            break;
        case 0:
            $('#accesstype').hide();
            $('#rights_add').attr('checked',false);
            $('#rights_del').attr('checked',false);
            $('#rights_edit').attr('checked',false);
            $('#rights_pub').attr('checked',false);
            $('#rights_feature').attr('checked',false);
            break;
    }
}

function searchform(){
    $("#searchForm .current").removeClass("current");
    $("#searchForm select[value!='']").addClass("current");
	$("#searchForm #freeTextSearch[value!='Search By Name']").addClass("current");
	$("#searchForm #searchByEmail[value!='Search by Email']").addClass("current");
    if($("#searchForm .current").length>0) {
        FX.highlight("#searchForm .current");
    }
    searchdata('author','bydata','','','','','','') ;
}
function resetSearch() {
    document.getElementById("searchForm").reset(); 
    searchContent();
}

function resetGrayButton() {
    $(".btnGraySelected").removeClass("btnGraySelected");
}

function contentInit() {
    var div = Utils.queryString("div");
    if($("#singleCont"+div).length>0) {
        FX.highlight("#singleCont"+div);
    }
    var msg = Utils.queryString("msg");
    if(msg!="") {
        div = (div!="") ? "singleCont"+div : "";
        Toast.show(unescape(msg));
    }
	$('.calendar2').datepicker({dateFormat:'dd-mm-yy'});
    $(".icon-close").bind("click", function() {
        closeMorePopup(this);
    })
}

function editContent(id) {
    window.location = "managecontent.php?contentid="+id+"&action=m"
}

function closeMorePopup(t) {
    $(t).parent().slideUp("fast", function() {
        resetGrayButton();
    });
}

var oldId;
function showMorePop(h, id) {
    if(oldId!=id) {
        resetGrayButton();
        $(".popdown").slideUp("fast", function() {
			
            });
    }
    var holder = h;
    $(holder).addClass("btnGraySelected");
    var id_ = id;
    var mainHolder = "singleCont"+id_;
    $("#"+mainHolder + " > .popdown").slideToggle("fast", function() {
        if($(this).css("display")!='block') {
            resetGrayButton();
        }
    });
    oldId = id_;
}

function searchByRights(v) {
    $("#searchByAuthorType").val(v);
    searchContent();
}
function changeListingType(h, t) {
    var holder = h;
    $(".currentGrid").removeClass("currentGrid");
    $("#viewType").val(t);
    $(holder).addClass("currentGrid");
    searchContent();
}

function searchAuthorByAuthorType(v) {
        $("#searchByAuthorType").val(v);
	searchContent();
}

function sortTable(ele, val){
	if($(ele).hasClass("header")) {
		$(ele).removeClass("header");
		$(ele).addClass("headerSortUp");
		$('#sortBy').val(val);
		$('#sortSeq').val('ASC');
	} else if($(ele).hasClass("headerSortUp")) {
		$(ele).removeClass("headerSortUp");
		$(ele).addClass("headerSortDown");
		$('#sortBy').val(val);
		$('#sortSeq').val('ASC');
	} else {
		$(ele).removeClass("headerSortDown");
		$(ele).addClass("headerSortUp");
		$('#sortBy').val(val);
		$('#sortSeq').val('DESC');
	} 
	searchdata('author','bydata','','','','','','');
}

