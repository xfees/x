// JavaScript Document
var HOST_IP_ADDRESS = window.location.host;

var CMSSITEPATH = "http://"+HOST_IP_ADDRESS+'/x/gateway';

var IMAGEMEDIAPATH = "http://"+HOST_IP_ADDRESS+"/media";

$(function() {  
   $('.error').hide();  
   if($('#searchparamALL').length > 0){
			$('#searchparamALL').addClass('selected');
	 }
    $(".listing").mouseover(function(){
		  $(this).addClass("alternate");
    }).mouseout(function(){
	    $(this).removeClass("alternate");
    });
   $('#addcontent').click(function(){
	 if(accessAddFalg == '%$#@%') {
		showAccessMsg();
		return;
	}
	showAddEditForm(0);					   
   })
});

function showAddEditForm(isReset) {
    $('#backtomodule').html('<a href="javascript:;" onclick="getReset()"><span class="iconBack">&nbsp;</span>Go Back</a>');
    $("#dataimage").html('');
    $("#addcontent").addClass("selected");
    $('.editformimage').remove();  
    $('.error').hide();
    $('.error1').hide();
    $('#divTrash').hide(); 
    if($("#divTxt").length > 0)
    {
        $("#divTxt ul").text("");
        $('#polloptionid').val(1);
        for (var i=0;i<4;i++) {
            addFormField();
        }
    }
    if ($("#subsection")) {
        $('#subsection').hide();
    }
    $("#displaycontent").hide();
    $("#editcontent, #addpassword").show();
    $("#delImg,#crossImg, #editpassword").hide();	 
    $(".hidden").val("");
    $("#action").val("a");
    if (typeof(tinyMCE) != 'undefined') { //----for setting tinyMCE blank if its present in module
	    tinyMCE.execCommand('mceRemoveControl', false, 'captionid');
	    document.getElementsByTagName('textarea #captionid').value="";
	    tinyMCE.execCommand('mceAddControl', false, 'captionid');  
	    $('#captionid').val('');
    }
    $('#savebutton').attr('disabled',false); //-------Enable the save button previously disabled	
    $('#formloading').hide();
    if (typeof(isReset)!="undefined") {
        if(isReset==0) {
            $("form")[0].reset();	
        }
    }
}
/**** To reset all form values*********/
function getReset() {
	$(".hidden").val("");
	$('#divTrash').show(); 
	$("#backtomodule").html('');
	$("#addcontent").removeClass("selected");
	$('#addEditText').html('Add New');
	$('.error').hide();
	$("#action").val("a");
	$("form")[0].reset();
	$("#editcontent").hide();
	$("#displaycontent").show();
	if ($("#blackdivlight").length > 0) {
		 $("#blackdivlight").hide();
	}
	if ($("#selectedcontentplaceholder").length>0) {	//for trending keywords
		$('#selectedcontentplaceholder').hide();	
	}
}
/**** To get edit content from getfile*********/
function getEditDetails(id, filename) {  	
	$('#savebutton').attr('disabled',false);	//-------Enable the save button previously disabled	
	$('#backtomodule').html('<a href="javascript:;" onclick="getReset()"><span class="iconBack">&nbsp;</span>Go Back</a>');
	$('#formloading').hide();	
	$('#addEditText').html('Edit');
	$("#delImg, #crossImg , #imgtag, #hdln1").hide();
	$('#hdlnplaceholder1').show();
	$("#addcontent").addClass("selected");
	$.post('get.php', {'id' :id, 'action':'e'}, function(data) {// alert(data);
		if(data == '%$#@%') {
			getReset();
			showAccessMsg();
			return;
		}
		showEdit(data);
	});
	$('#divTrash').hide();
}
/*********** TO ShoW MSG ***************/
function showAccessMsg(){ //alert('here');
	var readonlymsg='Permission denied. No action will be performed for this Account.';	
	Toast.show("Permission denied. No action will be performed for this Account.");
	return;
}
/**** To Validate Email Address*********/
function isValidEmail(str) 
{
    emailRe = /^\w+([\.-]?\w+)*@\w+([\.-]?(\w)+)*\.(\w{2}|(com|net|org|edu|int|mil|gov|arpa|biz|aero|name|coop|info|pro|museum))$/
    if (!emailRe.test(str))	{
	    return false
    } else {
	    return true
    }
}
/**** To Validate The Uploaded File is an Image (.jpg,.gif)*********/
function isValidImage(str) {
	var error=0;
	var exterror=0;
	var nameerror=0;
	var lastcount=str.split('\\').length;
	var uploadimg=str.split('\\')[lastcount-1];		
	var pos=uploadimg.lastIndexOf(".");				
	var str1=uploadimg.substring(pos);				
	var str=str1.toLowerCase();						
	//Check if the Image is a valid format
	if(str==".jpg" || str== ".gif" || str==".jpeg")
	{
		exterror=0;	//The image is not a .jpg or .gif
	} else {
		exterror=1;	
	}
	//Check if the imagename is valid
	var imagename=uploadimg.substring(0,pos);
	if (isalphanumeric(imagename) == false) {
		nameerror=2;
	}
	if (exterror==0 && nameerror==0){
		error=0;
	} else if (exterror!=0){
		error = exterror;
	} else if (nameerror!=0){
		error = nameerror;
	}
	return(error);		
}
/**** To Validate If the given string is a valid URL*********/
function isValidURL(url)
{
    var RegExp = /^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/;
    if(RegExp.test(url))
	{
        return true;
    }
	else
	{
        return false;
    }
}
/**** To Validate If the given string contains only Alphabets & Numbers, nothing else*********/
function isalphanumeric(str)
{
	var bReturn = true;
	var valid="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-";
	var invalidfirst = "_-";
	var invalidlast = "_-";
	for (var i=0; i<str.length; i++) 
	{
		if ( i == 0 && (invalidfirst.indexOf(str.charAt(i)) > 0))
		{
			bReturn = false;
			break;
		}
		else if ( i == (str.length-1) && (invalidlast.indexOf(str.charAt(i)) > 0))
		{
			bReturn = false;
			break;
		}
		else if (valid.indexOf(str.charAt(i)) < 0)
		{
			bReturn = false;
			break;
		}
	}
	return(bReturn);
}

/**** To Validate If the given string contains only Numbers, nothing else*********/
function isNumeric(str)
{
	var bReturn = true;
	var valid="0123456789+- ";
	var invalidfirst = "+- ";
	var invalidlast = "+- ";
	for (var i=0; i<str.length; i++) {
		if ( i == 0 && (invalidfirst.indexOf(str.charAt(i)) > 0))
		{
			bReturn = false;
			break;
		}
		else if ( i == (str.length-1) && (invalidlast.indexOf(str.charAt(i)) > 0))
		{
			bReturn = false;
			break;
		}
		else if (valid.indexOf(str.charAt(i)) < 0)
		{
			bReturn = false;
			break;
		}
	}
	return(bReturn);
}

/**** To Delete the content*********/
var UndoAction = {
	undoSteps: new Array(),
	redoSteps: new Array(),
	undoDelete: function() {
	if (this.undoSteps.length > 0) {
		var current = this.undoSteps[this.undoSteps.length-1];
		var filename = current.ajaxPath; 
		var id = current.id;
		$.post(filename, {'id':id,'action':'r'}, function(data) { 
			if(data == '%$#@%') {
				Toast.show("There is some error!");
				return;
			}
			var jObj=eval("("+data+")"); 
			if (jObj.status==1) {
				var ac = UndoAction.undoSteps.pop();//remove last action
				UndoAction.redoSteps.push(current); //for future use
				if(UndoAction.undoSteps.length==0) {
					UndoAction.hideUndoPanel();
				}
				var divID = "singleCont"+jObj.id;
				FX.come("#"+divID, function() {
					FX.highlight("#"+divID, function() {
						//will do something
					});
				});
			}
		});
	}
	},
	addToHistory: function(obj) {
		this.undoSteps.push(obj);
	},
	showUndoPanel: function() {
		if($("#undeoPanel").length==0) {
			var holder = "#mainContainer"
			if($("#searchResultCnt").length>0) {
				holder = "#searchResultCnt"
			}
			$(holder).before('<div style="display:none" class="undo-panel" id="undeoPanel"><ul class="ui-widget ui-helper-clearfix"><li onclick="UndoAction.undoDelete()" title="Undo Delete" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowreturnthick-1-w"></span></li></ul>');
		} 
		$("#undeoPanel").slideDown('slow');
	},
	hideUndoPanel: function() {
		$("#undeoPanel").slideUp('slow');
	}
}
//desc: compatibale without jquery ui 
function callDelete(id,filename){
	var msg = "Are you sure you want to delete this record !!!";
	if(typeof($.ui)!=="undefined" && typeof(DialogBox)!=="undefined") {
		//needs jquery UI and tools.js
		DialogBox.showConfirm(msg, "Delete", function(ok) {
			if(ok == true) {
				deleteNow();
			}
		});
	} else {
		var confirmation=confirm(msg);
		if(confirmation)
		{
			deleteNow();
		}
	}	
	//delete now
	function deleteNow() {		
		$.post(CMSSITEPATH + '/' + filename + '/get.php', {'id':id, 'action':'d'}, function(data) {
			if(data == '%$#@%') {
				showAccessMsg();
				return;
			}
			//add for history
			//adding to undo history only if success
			var jObj=eval("("+data+")"); 
			if (jObj.status==1) {
				var divID = "singleCont"+jObj.id;
				var html = $("#" + divID).html();
				var obj = {ajaxPath:CMSSITEPATH + '/' + filename + '/get.php', id:id, module:filename, html:html};
				UndoAction.addToHistory(obj);
				UndoAction.showUndoPanel();
			}
			removeDiv(data);
		});
	}
}
//desc: compatibale without jquery ui
function callUnDelete(id,filename,pollid)
{
	var msg = "Are you sure you want to restore this record !!!";
	if(typeof($.ui) !== "undefined" && typeof(DialogBox) !== "undefined") {
		//needs jquery UI and tools.js
		DialogBox.showConfirm(msg, "Restore", function(ok) {
			if(ok==true) {
				restoreNow();
			}
		});
	} else {
		var confirmation=confirm(msg);
		if(confirmation)
		{
			restoreNow();
		}
	}
	//restore now
	function restoreNow() {
		$.post(CMSSITEPATH + '/' + filename + '/get.php', {'id':id, 'action':'r'}, function(data) {
			if(data == '%$#@%') {
				showAccessMsg();
				return;
			}
			removeDiv(data);
		});
	}
}
/**** To Remove the Deleted div*********/
//this method needs jquery ui
function removeDiv(success){ //alert(success);
	var jObj=eval("("+success+")"); 
	if(jObj.status==1){
		var divID = "singleCont"+jObj.id;
		FX.transfer("#"+divID, "#divTrash");
		//$('#'+divID).fadeOut('slow');
		FX.explode('#'+divID, function() {
			Toast.show(jObj.msg, undefined, 2000);
		});
	}
	if(jObj.numRecords == 0){		//------------If it is a last record then show "NO Records"
		$("#mainContainer").html('<tr><td class="pL pTB" style="padding:\'10px\', fontSize:\'14px\', backgroundColor:\'#FFFFCC\', border:\'1px solid #c3c3c3\', margin:\'10px 0\'" id="norecordsdiv">No Records</td></tr>');	
	}
}

/******* To Check Password *****************/
function passwordLength(password){//alert('here'+password);
		var num=0;
		var alphabet=0;
		var extra=0;
		var temp=password;
		for(var j=0; j<temp.length; j++){
			var alphaa = temp.charAt(j);
			var hh = alphaa.charCodeAt(0);
			if(hh > 47 && hh<58){
				num=num+1;
				continue;
			}
			if((hh > 64 && hh<91) || (hh > 96 && hh<123)){
				alphabet=alphabet+1;
				continue;
			}
			extra=extra+1;		
		}
		if(num < 3 || alphabet < 3 || extra  < 2){
			//alert("Your password must have minimium three alphabets ,three numbers and two special characters ");
			return false;
		}else {
			return true;
		}
}

/******* To display Edited Content *****************/
function displayContent(success)
{ 
	var jObj=eval("("+success+")");
	$("#editcontent").hide();
	$("#addcontent").removeClass("selected");
	$('#addEditText').html('Add New');
	$('#backtomodule').html('');
	$("#displaycontent").show();	
	$('#divTrash').show();
	if(jObj.status =='1' || jObj.status =='0')
	{
		$.post("get.php",function(data) 
		{
			$("#mainContainer").html(data);
			Toast.show(jObj.msg);			
		});
	}
 
}

/******* To Change Publish flag of Content *****************/
function changeStatus(id, filename, holder, cb)
{		
	var display, title, css;  //alert(id);	
	var callback = cb;
	if(typeof(holder)!="undefined") {
		publishHolder = holder.id;
		$("#"+publishHolder).addClass("loaderCircle");
	}	
	$.post(CMSSITEPATH + '/' + filename+'/get.php', {'id':id, 'action':'p'}, function(resultdata) {	//alert(resultdata);	
		if(resultdata == '%$#@%') { //alert('here');
			$("#"+publishHolder).removeClass("loaderCircle");
			showAccessMsg();
			return;
		}
		if(resultdata == 'access denied') {
			var readonlymsg='This is a Read-Only Account. No action will be performed for this Account.';	
			var readonlystr="{'msg':'"+readonlymsg+"'}";	
			removeDiv(readonlystr);
			return;
		}	
		if(resultdata == 'previewfirst'){
			$("#"+publishHolder).removeClass("loaderCircle");
			Toast.show("Please preview this content first.");
			return false;
		}
		var resultarray = resultdata.split("|");
		var cssPublished;
		//alert("resultarray "+resultarray)
		if(resultarray[0].toString()=='1') {		
			if(resultarray[1] == 1) {
				display = 'Publish';	
				title = 'Click to UnPublish';	
				cssPublished = 'published';	
				$('#publishspan'+id).removeClass('unPublished');			
			}
			else {
				display='UnPublish';	
				title = 'Click to Publish';	
				cssPublished = 'unPublished';	
				$('#publishspan'+id).removeClass('published');
			}
		}
		if(typeof(publishHolder)!="undefined") {
			$("#"+publishHolder).removeClass("loaderCircle");
		}
		$('#publishspan'+id).html(display);	
		$('#publishspan'+id).attr("title", title);	
		$('#publishspan'+id).addClass(cssPublished);	
		if(typeof(callback)!="undefined") {
			//alert(id + " "+resultarray[0])
			arguments.length = 2;
			arguments[0] = id; //set id;
			arguments[1] = resultarray[1]; //set restult;
			callback.apply(this, arguments);
		}
	});
}

/******* To Change feature flag of Scorecard *****************/
function changeFeature(id, filename, holder, cb, status, islive,isFeatured)
{		
	if((status == 0 || islive == 0) && isFeatured == 0 ){
		alert('This match should be published and live to become featured.');
		return false;
	} else {	
		if(confirm('Are you sure you want to proceed ?')){
			var display, title, css;  //alert(id);	
			var callback = cb;
			
			//check if this is content module
		
			if(typeof(holder)!="undefined") {
				publishHolder = holder.id;
				$("#"+publishHolder).addClass("loaderCircle");
			}	
			$.post(CMSSITEPATH + '/' + filename + '/get.php',{'id':id, 'action':'f'}, function(resultdata) {	//alert(resultdata);	
				if(resultdata == '%$#@%') { //alert('here');
					$("#"+publishHolder).removeClass("loaderCircle");
					showAccessMsg();
					return;
				}
				if(resultdata == 'access denied') {
					var readonlymsg='This is a Read-Only Account. No action will be performed for this Account.';	
					var readonlystr="{'msg':'"+readonlymsg+"'}";	
					removeDiv(readonlystr);
					return;
				}	
				var resultarray = resultdata.split("|");
				var cssPublished;
				//alert("resultarray "+resultarray)
				if(resultarray[0].toString()=='1') {		
					if(resultarray[1] == 1) {
						display = 'Featured';	
						title = 'Click to undo Featured';	
						cssPublished = 'published';	
						$('#featurespan'+id).removeClass('unPublished');			
					}
					else {
						display='Not Featured';	
						title = 'Click to make Featured';	
						cssPublished = 'unPublished';	
						$('#featurespan'+id).removeClass('published');
					}
				}
				if(typeof(publishHolder)!="undefined") {
					$("#"+publishHolder).removeClass("loaderCircle");
				}
				$('#featurespan'+id).html(display);	
				$('#featurespan'+id).attr("title", title);	
				$('#featurespan'+id).addClass(cssPublished);	
				if(typeof(callback)!="undefined") {
					//alert(id + " "+resultarray[0])
					arguments.length = 2;
					arguments[0] = id; //set id;
					arguments[1] = resultarray[1]; //set restult;
					callback.apply(this, arguments);
				}
			});
		} else {
			return false;	
		}
	}
}

/******* To Upload Image by Using Ajax *****************/
function ajaxFileUpload(id,filename,module)	
{	//alert("id,filename,module,imagename"+id + " "+ filename+ " "+ module+ " "+ imagename);
	var callurl=CMSSITEPATH+'/doajaxfileupload.php?elementname='+id;	
	$.ajaxFileUpload
		(
			{	
				url:callurl, 
				secureuri:false,
				fileElementId:id,
				fileElementName:filename,
				moduleName:module,
				dataType: 'json',
				async:false,
				success: function (data, status)
				{	//alert(data.msg);
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							var set=0;
							sendrequest();
						}else{ 	
							sendrequest(data.msg);
						}
					}
				},
				error: function (data, status, e)
				{
					
				}
			}
		)
}

/******* To Delete Image by Using Ajax *****************/
function callDeleteimages( module, table, column ){
   var imgpath = $('#authoroldthumbnail').val();
   var id = $('#id').val();
   var callurl=CMSSITEPATH+'/doajaxfiledelete.php';	
   $.post(callurl, {'id' :id, 'imgpath':imgpath , 'module':module, 'tab':table, 'col':column   }, function(data) {
   		var jObj = eval("("+data+")");
	    if(jObj.status == 'Success'){
			$('#dataimage').html('');
			$("#delImg,#crossImg").hide();
		}
   });
}

/******* To search Data *****************/
function searchdata(filename,searchtype,data,currpage,lastpage,firstpage,recperpage,istrash,modulename, callback)
{	
	var search_pannel=new Array("searchByContype", "searchBySectionId" , "searchByVideoType", "searchByAuthorName" ,"searchByCategory","searchByDate","searchByAuthor","searchByByline","searchBySource","sortby","sortSeq","freeTextSearch","searchByUrl","searchByName");
	var elem = document.getElementById('searchForm').elements;
	var j=1;
	for(var i = 0; i < elem.length; i++)
	{
		var c_name='contentSearch_'+elem[i].name;
		//alert(elem[i].value+'------'+elem[i].name);
		if(elem[i].value != '' && (search_pannel.indexOf(elem[i].name) > -1)){
			Set_Cookie(c_name,elem[i].value,1,'/');
			if(j == 1){
				Set_Cookie("is_content_search","yes",1,'/');
			}
			j++;
		}else if(elem[i].value == ''){ //alert('d');
			//Set_Cookie(c_name,'',-1,'/');
			eraseCookie(c_name);
			if(elem[i].name == 'sortby'){
				document.getElementById(elem[i].name).value = 'sortby';
			}else if(elem[i].name == 'freeTextSearch'){
				document.getElementById(elem[i].name).value = 'Type Id/Headline/Summary';
			}
		}
		
	}
	if(currpage > 0  && currpage != 'next'){
			if(readCookie(c_name) != '' || readCookie(c_name) == 'null'){
				Set_Cookie("is_content_search","yes",1,'/');
				Set_Cookie("contentSearch_currpage",currpage,1,'/');
			}
			if(firstpage != ''){
				Set_Cookie("contentSearch_firstpage",firstpage,1,'/');
			}
			if(lastpage !=''){
				Set_Cookie("contentSearch_lastpage",lastpage,1,'/');
			}
			
	}else if(readCookie('contentSearch_currpage') > 1 && currpage != 'next'){
			currpage = 	readCookie('contentSearch_currpage');
			lastpage  = 	readCookie('contentSearch_lastpage');
			firstpage =     readCookie('contentSearch_firstpage');
	}
		
	var datastring ="search="+searchtype; //alert(datastring) ;
	//data = data+"&"+$('#searchForm').serialize();
	var serializedForm = $('#searchForm').serializeArray();
			$.each(serializedForm , function(i, field) {
		  serializedForm[i].value = $.trim(field.value);
		  if(serializedForm[i].value=='Type Id/Headline' || serializedForm[i].value=='Type Id/Headline/Summary' || serializedForm[i].value=='Type Headline' || serializedForm[i].value=='Type Name' || serializedForm[i].value=='Type Email' || serializedForm[i].value=='Type Contentid/Content/Comment/UserName' || serializedForm[i].value=='Type Filter word')serializedForm[i].value ='';
	});
	data =  data+"&"+$.param(serializedForm);
	datastring = datastring+"&"+data;	

	if(istrash=='tc'){
		datastring=datastring+"&action=tc";	
	}        

	datastring=datastring+"&pg="+currpage+"&displastpage="+lastpage+"&dispfirstpage="+firstpage+"&recperpage="+recperpage; //alert(datastring);

	if (filename=='pollpopup' || filename=='photogallerypopup' || filename=='contentpopup' || filename=='videopopup' || filename=='quotepopup') {
		$('#searchContainer').html('<div align="center"><img src="../images/ajax-loader.gif" border="0" /></div>'); 
	} else {
		$('#mainContainer').html('<div align="center"><img src="../images/ajax-loader.gif" border="0" /></div>'); 
	}
	$.ajax({
	   type: "POST",
	   url: "get.php",
	   data: datastring,
	   success: function(resultdata) {	
				$('#mainContainer').html(resultdata); 
				if(callback != undefined && callback != "") {
						if (typeof(callback)=="string") {
							eval(callback)(resultdata);
						} else {
							callback(resultdata);
						}
				}
	   }
	});
}

/**********   TRIM FUNCTION **********************************/
function Trimnew(str)
{
  return jQuery.trim((str));
}

function strrev( string ){
  	var ret = '', i = 0;
    string += '';
    for ( i = string.length-1; i >= 0; i-- ){
       ret += string.charAt(i);
    }
    return ret;
}


/******** Function to Open/Close DropDowns Ends **********************************/
function strpos( haystack, needle, offset){    // Finds position of first occurrence of a string within another  
    var i = (haystack+'').indexOf( needle, offset ); 
    return i===-1 ? false : i;
}

function checkkey(event){
	if(event.keyCode == 13){ 
		validateLogin();
		return false; 
	}else{
		return true;
	}
}

/*** Function to open popup window ***/
var popUpWin=0;
function ITWinPopUp(URLStr, width, height)
{
	var left = (screen.width/2) - width/2;
	var top = (screen.height/2) - height/2;
	if(popUpWin)
	{
		if(!popUpWin.closed) 
			popUpWin.close();
	}
	popUpWin = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbar=no,resizable=no,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
}

function ChangePassword()
{
	var new_password = $('#new_password').val();
	var conf_password = $('#conf_password').val();
	var id = Trimnew($('#auther').val());
	if(new_password == "") 
	{
		alert("Please Enter New password.");
		$('#new_password').focus();
		return false;
	} 
	else if(conf_password =="")
	{
		alert("Please Enter Confirm Password.");
		$('#conf_password').focus();
		return false;
	}	

	else if(conf_password!=new_password)
	{
		alert("Password mis-matched, Please try again.");
		$('#conf_password').val('');
		$('#new_password').val('');
		$('#new_password').focus();
		return false;
	}
	var passreturn = passwordLength(new_password);
	$('#resetresult').html('');

	if(passreturn == true)
	{
		$.post(CMSSITEPATH+"/authpassreset2db.php", {'id':id, 'new_passw':new_password}, function(resultdata) 
		{  
			$('#resetresult').html(resultdata);
		});
	}	
}

function quickUpdate(id , columnName, moduleName, placeHolder,e){
	if (e.keyCode == 13){
		var val = $('#'+placeHolder+'val'+id).val(); //alert(val);
		if(val != ''){
			$.post('get'+moduleName+'.php',{'id':id, 'column':columnName,'columnval':val,'action':'qe'},function(resultdata){
				/*if(resultdata == '%$#@%') {
					$('#hdln'+id).hide();
					$('#hdlnplaceholder'+id).show();
					showAccessMsg();
					return;
				}*/
				if(columnName.indexOf('date')!=-1)
				{
				val=$.format.date(val, "MMM yyyy, dd 'at hh:mm a");
				val=val.replace("'","");
				}
						$('#'+placeHolder+id).hide();
						$('#'+placeHolder+'placeholder'+id).show();
						$('#'+placeHolder+'placeholder'+id).html(val);
			});
		}else{
			$('#'+placeHolder+id).hide();
			$('#'+placeHolder+'placeholder'+id).show();	
		}
	}
}


//-------------------------------------------- Used in Quick Add Author Functionality, check gateway/plugins/quick_add_author.php for more
function load_authors(added_author_id) {
	$.post(CMSSITEPATH+'/plugins/author/quick_add_author.php',{action: 'get',added_author_id: added_author_id},function(data){
		$("#author_id").append(data);
                $("#by_line_author_id").append(data);
	});
}
function changePosition(placeHolder,flag){
	var selIndex = document.getElementById(placeHolder).selectedIndex; 
	if(selIndex=='-1'){
		alert("Please Select 1 of the Option from list");
	}else{
		var selText	 = document.getElementById(placeHolder).options[selIndex].text;
		var selVal	 = document.getElementById(placeHolder).options[selIndex].value;	
		if(flag=='up'){
			if(selIndex==0){
				alert('This is 1st record...you can not do following operation on this record');
			}else{
				document.getElementById(placeHolder).options[selIndex].text = document.getElementById(placeHolder).options[selIndex-1].text;
				document.getElementById(placeHolder).options[selIndex].value = document.getElementById(placeHolder).options[selIndex-1].value;
				document.getElementById(placeHolder).options[selIndex].selected= false;
				document.getElementById(placeHolder).options[selIndex-1].text = selText;
				document.getElementById(placeHolder).options[selIndex-1].value = selVal;
				document.getElementById(placeHolder).options[selIndex-1].selected= true;
			}
		}else if(flag=='down'){
			var totalCount = document.getElementById(placeHolder).options.length;
			if(totalCount==selIndex){
				alert('This is last record...you can not do following operation on this record');	
			}else{
				document.getElementById(placeHolder).options[selIndex].text = document.getElementById(placeHolder).options[selIndex+1].text;
				document.getElementById(placeHolder).options[selIndex].value = document.getElementById(placeHolder).options[selIndex+1].value;
				document.getElementById(placeHolder).options[selIndex].selected= false;
				document.getElementById(placeHolder).options[selIndex+1].text = selText;
				document.getElementById(placeHolder).options[selIndex+1].value = selVal;
				document.getElementById(placeHolder).options[selIndex+1].selected= true;
			}
		}else if(flag=='delete'){
			//alert(selIndex);
			document.getElementById(placeHolder).remove(selIndex);
		}
	}
}
function add_author_quickly() {
	ModalBox.open(CMSSITEPATH+'/plugins/author/quick_add_author.php',700,500);
	return false;
}

function show_datetimepicker(select) {
  $(select).datetimepicker({dateFormat:'dd-mm-yy',showSecond: true,timeFormat: 'hh:mm:ss'});
}

function characterCount(val, placeholder, limit){
	var len = val.length; 
	if(len > limit){
		alert("Your exceeded the max character limit");
	}else{
		var remaining = limit-len;
		$('#'+placeholder).html(remaining);
	}
}

/*********** new methods ********************/
// author: marghoob suleman
// added on: 12 Oct, 2011
// Desc: some methods needs smiple modalbox js
// Status: Still working on this
/*************************************/
// needed for grid layout
// changes spanid color based on status
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

//content module
function showPreviewLink(id) {
	DialogBox.showAlert("Preview will be displayed later...");
}

//video module
function openvideopreview(vid){
	 var object_code = $('#object_code').val();
	 //alert(object_code)	 
	 if(typeof(object_code)=="undefined") {
		 ModalBox.open(CMSSITEPATH+'/video/getvideo.php?action=play&vid='+vid,650,500);
	 } else {
		 object_code=escape(object_code);
		 ModalBox.open(CMSSITEPATH+'/video/getvideo.php?action=play&vid='+vid+'&objectcode='+object_code,650,500);
	 }
	 
	
}

var MFL = {}; //Module Form Loader
MFL = {
	loadModule: function(nm, c, w, h) {
		var callback = c;
		var url = CMSSITEPATH+"/"+nm+"/"+nm+"-form.php";
		ModalBox.open(url, w, h, true, callback, function() {
			GS.disabled(true);
		});
	}, 
	closeModule: function() {
		ModalBox.close();
	}
}


function resetUndo() {
     UndoAction.undoSteps = new Array();
     UndoAction.redoSteps = new Array();
     if($("#undeoPanel").length>0) {
          $("#undeoPanel").hide();
     }
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}
/******** Function to set cookie **************************/
function Set_Cookie( name, value, expires, path, domain, secure ){		//alert('name='+name+' value='+value);
		var today = new Date();
		today.setTime( today.getTime() );
		if ( expires )
		{
		expires = expires * 1000 * 60 * 60 * 24;
		}
		var expires_date = new Date( today.getTime() + (expires) );
		document.cookie = name + "=" +escape( value ) +
		( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) + 
		( ( path ) ? ";path=" + path : "" ) + 
		( ( domain ) ? ";domain=" + domain : "" ) +
		( ( secure ) ? ";secure" : "" );
}
	
function eraseCookie(name) {
	Set_Cookie(name,"",-1,"/");
}

function printCookies(w){
	cStr = "";
	pCOOKIES = new Array();
	pCOOKIES = document.cookie.split('; ');
	for(bb = 0; bb < pCOOKIES.length; bb++){
		NmeVal  = new Array();
		NmeVal  = pCOOKIES[bb].split('=');
		if(NmeVal[0]){
			cStr += NmeVal[0] + '=' + unescape(NmeVal[1]) + '; ';
		}
	}
	return cStr;
}

function unsetSearchparam(){
	//alert('here');
	//var search_pannel=new Array("searchByContype","searchByCategory","searchByDate","searchByAuthor","searchByByline","searchBySource","sortby","sortSeq","freeTextSearch");
	var search_pannel=new Array("searchByContype", "searchBySectionId" ,"searchByVideoType", "searchByAuthorName" ,"searchByCategory","searchByDate","searchByAuthor","searchByByline","searchBySource","sortby","sortSeq","freeTextSearch","searchByUrl","searchByName");
	
	var elem = document.getElementById('searchForm').elements;
	eraseCookie('is_content_search');
	eraseCookie('contentSearch_currpage');
	for(var i = 0; i < elem.length; i++)
	{
		var c_name='contentSearch_'+elem[i].name;
		if((readCookie(c_name) != '' || readCookie(c_name) == 'null') && (search_pannel.indexOf(elem[i].name) > -1)){
			//Set_Cookie(c_name,'',-1,'/');
			eraseCookie(c_name);
			if(elem[i].name == 'sortby'){
				document.getElementById(elem[i].name).value = 'sortby';
			}else if(elem[i].name == 'freeTextSearch'){
				document.getElementById(elem[i].name).value = 'Type Id/Headline/Summary';
			}
		}else{
				document.getElementById('sortby').value = 'sortby';
			}
	}
	
}


function setSearchparam(){
	//var search_pannel=new Array("searchByContype","searchByCategory","searchByDate","searchByAuthor","searchByByline","searchBySource","sortby","sortSeq","freeTextSearch");
	var search_pannel=new Array("searchByContype", "searchBySectionId" ,"searchByVideoType", "searchByAuthorName" ,"searchByCategory","searchByDate","searchByAuthor","searchByByline","searchBySource","sortby","sortSeq","freeTextSearch","searchByUrl","searchByName");
	
	var elem = document.getElementById('searchForm').elements;
	for(var i = 0; i < elem.length; i++)
	{
		var c_name='contentSearch_'+elem[i].name;
		if(readCookie(c_name) != '' && readCookie(c_name) != null && (search_pannel.indexOf(elem[i].name) > -1)){
			//var value_cname=readCookie(c_name).replace('%20',' ');
			var value_cname = decodeURI(readCookie(c_name));
			document.getElementById(elem[i].name).value = value_cname;
			if(elem[i].name == 'freeTextSearch' && readCookie(c_name) == null && readCookie(c_name) == ''){ //alert('saddd');
				document.getElementById(elem[i].name).value = 'Type Id/Headline/Summary';
			}
			/*if(elem[i].name == 'sortby' && readCookie(c_name) != null && readCookie(c_name) != ''){
				document.getElementById(elem[i].name).value = 'sortby';
			}*/
		}
	}
	smartSelect();
	searchform();
	return false;
}
