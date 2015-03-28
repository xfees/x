function mpSearchImages(filter, page) {
	var keyword = $('#searchImage').val();
   
   var fromDate = '';
   var toDate = '';
	var sortBy = '';
	
	if(filter == 1) {		
		fromDate = $("#fromDate").val();
		toDate = $("#toDate").val();
      sortBy = '';
		if($('#sortBy1').attr('checked')) {
			sortBy = '1';
		} else if($('#sortBy2').attr('checked')) {
			sortBy = '2';
		} else {
			sortBy = '';
		}
	}
	
    var status = 'valid';
    
    if(keyword=='') {
         alert('Please enter a keyword!');
         $('#keyword').focus();
         status = 'invalid';
    }
    
    if(status=='valid') {
    	$('#loader').show();
    	$.post("getmedia.php", { action: 'search', keyword: keyword, fromDate : fromDate, toDate : toDate, sortBy : sortBy, page: page}, 
    	function(result) {    		
    		$("#result-area").html(result);
    		$('.calendar2').datepicker({dateFormat:'dd-mm-yy'});
    		$('#loader').hide();
    	});
    }
    
    $('#is_cropped').val('0');
    
    mpCloseAddImage();
}


function mpUploadImage() {
	var image_field_name = $('#uploadImage').attr('name');
    var callurl = CMSROOTPATH + '/plugins/media/getmedia.php';
    
    $('#loader').show();
    $.ajaxFileUpload({
        url : callurl,
        secureuri : false,
        fileElementId : ''+image_field_name,
        fileElementName : ''+image_field_name,
        moduleName : ''+$('#mName').val(),
        useType : ''+$('#uType').val(),
        dataType : 'json',
        async : false,
        success : function(data, status) {
          $('#loader').hide();
          if(data.error != '') {
            alert(data.error);
          } else {            
            mpOpenUploadedImage(data.msg);
          }
        },
        error : function(data, status, e) {
        	$('#loader').hide();
        	alert(data.error);
        }
    });
}

function mpOpenUploadedImage(img) {
	
	$('#pImgAlt').val('');
	
	if($('#uType').val() == 'gallary') {
		tinyMCE.execCommand('mceRemoveControl', false, 'pCaption');
		//$('#pTitle').val('');
		$('#pCredit').val('');
		$('#pCaption').val('');
		$('#pAuthor').val('');
		$('#pDate').val('');
		$('#pKeywords').val('');
      $('#pHeadline').val('');
		$('#id').val('');
		$('#isEdit').val('-1');
	}
	
	$('#url').val(img);
	$('#origonal_url').val(img);
	$('#is_stored').val(1);
	$('#is_editorial').val(2);
	$('#pImg').attr('src', img);
   $('#c-img').attr('src', img);
	
	if($('#uType').val() == 'gallary') {
		$('.calendar2').datepicker({dateFormat:'dd-mm-yy'});
		mptinyinit();
	}
   
   $('#is_cropped').val('0');

	$('#addMedia').show();
	$('#result-area').hide();
	
	$('#crop-image').hide();
	$('#add-image').show();	
}

function mpOpenAddImage(id) {
	
	$('#pImgAlt').val('');
	
	if($('#uType').val() == 'gallary') {
		tinyMCE.execCommand('mceRemoveControl', false, 'pCaption');
		//$('#pTitle').val('');
		$('#pCredit').val('');
		$('#pCaption').val('');
		$('#pAuthor').val('');
		$('#pDate').val('');
		$('#pKeywords').val('');
      $('#pHeadline').val('');
		$('#id').val('');
		$('#isEdit').val('-1');
	} else {
      $('#pImgCaption').val('');  
   }
		
	var img = $('#'+id+'_url');
	var alt = $('#'+id+'_alt');	
	var is_editorial = $('#'+id+'_is_editorial');
	var is_stored = $('#'+id+'_is_stored');

	$('#url').val(img.val());
	$('#origonal_url').val(img.val());
	$('#downloaded_url').val('');
	$('#is_stored').val(is_stored.val());
	$('#is_editorial').val(is_editorial.val());
	$('#pImg').attr('src', img.val());
	$('#pImg').attr('alt', alt.val());
   $('#c-img').attr('src', img.val());

	$('#pImgAlt').val(alt.val());
	
	if($('#uType').val() == 'gallary') {		
		//$('#pTitle').val($('#'+id+'_title').val());
		$('#pCredit').val($('#'+id+'_credits').val());
		$('#pCaption').val($('#'+id+'_caption').val());
		$('#pAuthor').val($('#'+id+'_author').val());
		$('#pDate').val($('#'+id+'_date').val());
		$('#pKeywords').val($('#'+id+'_keywords').val());
      $('#pHeadline').val($('#'+id+'_title').val());      
	} else {
     $('#pImgCaption').val($('#'+id+'_caption').val());
   }
	
	if($('#uType').val() == 'gallary') {
		$('.calendar2').datepicker({dateFormat:'dd-mm-yy'});
		mptinyinit();
	}
   
   $('#is_cropped').val('0');

	$('#addMedia').show();
	$('#result-area').hide();
	
	$('#crop-image').hide();
	$('#add-image').show();
	
}

function mpCloseAddImage() {
    $('#is_cropped').val('0');
	$('#addMedia').hide();
	$('#crop-image').hide();
	$('#result-area').show();
}

function mpLoadImageCrop() {	
	$('#d-image').html('');	
	$('#d-image').html('<img id="c-img" />');	
	$('#c-img').attr('src', $('#url').val());
	$('#preview').attr('src', $('#url').val());
   $('#preview').css({});

	// reset coordinates
	$('#x1').val('');
	$('#x2').val('');
	$('#y1').val('');
	$('#y2').val('');
	$('#h').val('');
	$('#w').val('');
	
	var mW = $('#mW').val();
	var mH = $('#mH').val();
	if(mW != 0 && mH != 0 && $('#uType').val() != 'gallary' && $('#uType').val() != 'editor' && $('#uType').val() != 'article') {		
		$('#c-img').Jcrop({
	        onChange : mpShowCoords,
	        onSelect : mpShowCoords,
	        onRelease : mpClearCoords,
	        setSelect : [0,0,mW,mH],
	        aspectRatio : mW / mH,
	        minSize : [mW, mH],
           boxWidth : 400,
           boxHeight : 300           
	    });
		
	} else {
     if(mW != 0 && mH != 0) {
       if($('#contentType').val() == '7') {         
         // picture story
         $('#c-img').Jcrop({
              onChange : mpShowCoords,
              onSelect : mpShowCoords,
              onRelease : mpClearCoords,
              setSelect : [0,0,mW,mH],	        
              maxSize : [mW, mH],
              boxWidth : 400,
              boxHeight : 300
          });
       } else {
          $('#c-img').Jcrop({
              onChange : mpShowCoords,
              onSelect : mpShowCoords,
              onRelease : mpClearCoords,
              setSelect : [0,0,mW,mH],	        
              minSize : [mW, 0],
              boxWidth : 400,
              boxHeight : 300
          });
       }
     } else {
       $('#c-img').Jcrop({
	        onChange : mpShowCoords,
	        onSelect : mpShowCoords,
	        onRelease : mpClearCoords,
          boxWidth : 400,
          boxHeight : 300
	    });
     }
	}
   
	$('#crop-image').show();
	$('#add-image').hide();
}

function mpClearCoords() {
    $('#coords input').val('');
    $('#h').css({
        color : 'red'
    });
    window.setTimeout(function() {
        $('#h').css({
            color : 'inherit'
        });
    }, 500);
}

function mpShowCoords(c) {
    $('#x1').val(c.x);
    $('#y1').val(c.y);
    $('#x2').val(c.x2);
    $('#y2').val(c.y2);
    $('#w').val(c.w);
    $('#h').val(c.h);
    
    var rx = 100 / c.w;
	var ry = 100 / c.h;
	
	var iH = $('#c-img').attr('height');
	var iW = $('#c-img').attr('width');

  $('#cropW').text(c.w);
  $('#cropH').text(c.h);
	
	$('#preview').css({
		width : Math.round(rx * iW) + 'px',
		height : Math.round(ry * iH) + 'px',
		marginLeft : '-' + Math.round(rx * c.x) + 'px',
		marginTop : '-' + Math.round(ry * c.y) + 'px'
	});
}

// reload existing image
function mpUndoCropImage() {
	if($('#downloaded_url').val() != '') {
		if($('#is_editorial').val() == 1) {
			$('#is_editorial').val(2);
		}
		
		$('#url').val($('#downloaded_url').val());
		$("#pImg").attr('src', $('#url').val());          			
      
      $('#is_cropped').val('0');
      
		mpLoadImageCrop();
	}
}

function mpCropImage() {
    var submit = false;
    if(parseInt(jQuery('#w').val()) > 0) {
    	
    	$('#loader').show();
        //submit = true;
    	$.post(
  	    	"getmedia.php", {
      			action: 'crop', 
      			x1: $('#x1').val(),
      			x2: $('#x2').val(),
      			y1: $('#y1').val(),
      			y3: $('#y2').val(),
      			h: $('#h').val(),
      			w: $('#w').val(),
      			url : $('#url').val(),
      			module : $('#mName').val(),
      			is_editorial : $('#is_editorial').val(),
      			is_stored : $('#is_stored').val(),
      			downloaded_url : $('#downloaded_url').val() 
      		}, 
      		function(result) {      			
      			var jObj = eval("("+result+")");
      			$('#loader').hide();
      			if(jObj.status == 'fail') {
          			alert(jObj.msg);
      			} else {
          			// crop success
          			$('#url').val(jObj.iCPath);
          			$('#downloaded_url').val(jObj.iTPath);
          			$('#is_stored').val(1);
                  
                  $('#is_cropped').val(1);

          			// load cropped image
          			$("#pImg").attr('src', $('#url').val());          			
          			mpLoadImageCrop();
      			}    	
  			}
		);
    } else {
        submit = false;
        alert('Please select a crop region then press submit.');
    }
    return submit;
}

function mpUseImage() {
	
	if($.trim($('#pImgAlt').val()) == '') {
		alert('Please enter Image Alt.');
		$('#pImgAlt').focus();
		return false;
	}
	
	if($('#uType').val() == 'gallary') {
		tinyMCE.triggerSave();
		
		// validation for gallary
		if($.trim($('#pCaption').val()) == '') {
			alert('Please enter caption.');
			$('#pCaption').focus();
			return false;
		}
	}
   
   var mW = $('#mW').val();
   var mH = $('#mH').val();
   var iH = $('#c-img').attr('height');
   var iW = $('#c-img').attr('width');
   var noCrop =0; 
   //if this varible is set to 1 only then we dont want to croping, for that u just add the module name in the if condition in the dipaly file
   if($('#noCrop').val()){
	   var noCrop = $('#noCrop').val();
   }
   if(noCrop == '0'){
	   if(mW !=0 && mH !=0) {
        if($('#uType').val() == 'gallary' || $('#uType').val() == 'editor' || $('#uType').val() == 'article') {
          if($('#uType').val() == 'gallary' && $('#contentType').val() == '7') {
            if(iW > mW) {
              alert('Image width is greater then maximum size requirements. Please crop or upload different image.');
              return false;
            }
          } else if(iW < mW) {
            alert('Image width is not satisfying the minimum size requirements. Please crop or upload different image.');
            return false;
          }
        } else {
        if($('#is_cropped').val() != '1') {
          if(iW < mW || iH < mH) {
            alert('Image height/width is not satisfying the minimum size requirements. Please crop or upload different image.');
            return false;
          }          

          var a1 = parseFloat(iW/iH);
          var a2 = parseFloat(mW/mH);
          a1 = Math.round(a1*100)/100;
          a2 = Math.round(a2*100)/100;
          if(a1 != a2) {        
            alert('Image height width ratio is not proper. Please crop the image or use different image');
            return false;
          }
        }
      }
	   }
   }
   // code for submit
	$('#loader').show();
   
   var options = {};
	
	if($('#uType').val() == 'gallary') {
		options = {
			action: 'use',       			
  			url : $('#url').val(),      			
  			is_editorial : $('#is_editorial').val(),
  			is_stored : $('#is_stored').val(),
  			downloaded_url : $('#downloaded_url').val(),
  			origonal_url : $('#origonal_url').val(),
  			module : $('#mName').val(),
  			control : $('#cName').val(),
  			type : $('#uType').val(),
  			contentType : $('contentType').val(),
  			//section : $('section').val(),         
  			pImgAlt : $('#pImgAlt').val(),
  			pCredit : $('#pCredit').val(),
  			pCaption : $('#pCaption').val(),
  			pAuthor : $('#pAuthor').val(),
  			pDate : $('#pDate').val(),
  			pKeywords : $('#pKeywords').val(),
         pHeadline : $('#pHeadline').val(),
  			id : $('#id').val(),
  			cnt : $('#imageCount').val() 
  		};
	
	} else {		
		options = {
			action: 'use',       			
  			url : $('#url').val(),      			
  			is_editorial : $('#is_editorial').val(),
  			is_stored : $('#is_stored').val(),
  			downloaded_url : $('#downloaded_url').val(),
  			origonal_url : $('#origonal_url').val(),
  			module : $('#mName').val(),
  			control : $('#cName').val(),
  			type : $('#uType').val(),
  			contentType : $('#contentType').val(),
  			//section : $('#section').val(),
         bid : $('#bid').val(),
         rid : $('#rid').val(),
  			pImgAlt : $('#pImgAlt').val(),
         pImgCaption : $('#pImgCaption').val()         
  		};		
	}
	
	$.post(
    	"getmedia.php", options, 
  		function(result) {
  			$('#loader').hide();
  			var jObj = eval("("+result+")");
  			if(jObj.status == 'fail') {
      			alert(jObj.msg);
  			} else {
      			// crop success
  				if($('#uType').val() == 'editor') {
  					parent.tinyMCE.execCommand('mceInsertContent', false, jObj.html);
  				}
  				else if($('#uType').val() == 'gallary') {
  					var cnt = $('#imageCount').val();
  					cnt++;
  					$('#imageCount').val(cnt);
  					
  					var edit = $('#isEdit').val();
  					if(edit != -1) {
  						//mpRemoveImageFromStrip(edit);
  						$('#strip_' + edit).replaceWith(jObj.html);
  					} else {
  						$('#gallary-images').append(jObj.html);	
  					}
  					
  					
  					var l = $('#gallary-images li').length;
  					$('#gallary-images').width(l*78);
  					mpEnableSortable();
  					mpCloseAddImage();
  				} else if($('#mName').val() == 'blocks') {
              //alert(jObj.html);              
              var obj = parent.document.getElementById('mediasingleCont'+$('#rid').val());
              if(!obj){
                var obj = parent.document.getElementById('media'+$('#rid').val());
              }
              if(obj) {
                obj.setAttribute('class','mp-edit');
              }
                
            } else {
  					var obj = parent.document.getElementById($('#cName').val() + '_container');
  					obj.innerHTML = jObj.html;
  				}
  				
  				$('#uploadImage').val('');
  				
  				if($('#uType').val() != 'gallary') {
  					$('#pImgAlt').val('');
               $('#pImgCaption').val('');               
  					parent.ModalBox.close();
  				} else {
  					$('#pImgAlt').val('');
  		  			$('#pCredit').val('');
  		  			$('#pCaption').val('');
  		  			$('#pAuthor').val('');
  		  			$('#pDate').val('');
  		  			$('#pKeywords').val('');
               $('#pHeadline').val('');
  		  			$('#id').val('');
  				}
  			}
  		}
	);
     
   return true;  
}

function mpRemoveImage(module, control, type) {
	var obj = $('#'+control+'_container');	
	var tHtml = '<a href="javascript:void(0);" onclick="mpOpenPlugin(\''+module+'\',\''+control+'\',\''+type+'\');">Upload or Search</a>';
	obj.html(tHtml);
	
	$('#old'+control).val('');
}


function mpShowImageForEdit(module, control, type, displayImage) {
	var obj = $('#'+control+'_container');
	var tHtml = "<div style='float:left'>";	
    tHtml += "<img src='"+displayImage+"' height='100px' width='100px'/>";
    tHtml += "</div>";    
    tHtml += "<div style='float:left; margin-left: 10px'>";
    tHtml += "<a href='#' onclick='mpRemoveImage(\""+module+"\",\""+control+"\",\""+type+"\")'>X</a>";
    tHtml += "</div>";    
	tHtml += "</div>";
    
	obj.html(tHtml);
}

var mpPluginStatus = 0;

function mpOpenPlugin(mName, cName, uType, bid, rid, image, alt) {
	
	var pPath = CMSROOTPATH+'/plugins/';
	pPath += 'media/display.php?m='+mName+'&c='+cName+'&u='+uType;
   
   if(mName == 'blocks') {     
     rid = $("#"+rid).find(".recordid").val();
     if(image) {
        pPath += '&bid='+bid+'&rid='+rid+'&image='+image+'&alt='+alt;
     } else {
        pPath += '&bid='+bid+'&rid='+rid;
     }
   }
	
   if(mName == 'photogallery' && uType == 'gallary') {     
      var obj1 = parent.document.getElementById('contype_id');
      if(obj1 && obj1.value == '') {
			alert('Please select content type.');
			obj1.focus();
			return;
		}
      
      if(obj1) {
			pPath += '&cType='+obj1.value;
		} else {
			pPath += '&cType=';
		}
   } else if(mName == 'content' && uType == 'editor') {     
      var obj1 = parent.document.getElementById('contype_id');
      if(obj1 && obj1.value == '') {
			alert('Please select content type.');
			obj1.focus();
			return;
		}
      
      if(obj1) {
			pPath += '&cType='+obj1.value;
		} else {
			pPath += '&cType=';
		}
   } else {
     pPath += '&cType=';
   }
  
	var windowWidth = document.documentElement.clientWidth;
	var w = windowWidth - 100;
	var h = 550;
	if(uType == 'gallary') {
		h = document.documentElement.clientHeight - 100;
	}
	
	ModalBox.open(pPath, w, h);
}

function mpBackFromCrop() {
	$('#crop-image').hide();
	$('#add-image').show();
}

function mpRemoveImageFromStrip(id) {
	$('#strip_' + id).remove();
}

function mpEditFromStrip(id) {
	
	tinyMCE.execCommand('mceRemoveControl', false, 'pCaption');
	
	$('#url').val($('#url_'+id).val());
	$('#origonal_url').val($('#origonal_url_'+id).val());
	
	$('#downloaded_url').val('');
	$('#is_stored').val($('#is_stored_'+id).val());
	$('#is_editorial').val($('#is_editorial_'+id).val());
	$('#pImg').attr('src', $('#url_'+id).val());
	$('#pImg').attr('alt', $('#pImgAlt_'+id).val());
	
	$('#pImgAlt').val($('#pImgAlt_'+id).val());	
	$('#pCredit').val($('#pCredit_'+id).val());
	$('#pCaption').val($('#pCaption_'+id).val());
	$('#pAuthor').val($('#pAuthor_'+id).val());
	$('#pDate').val($('#pDate_'+id).val());
	$('#pKeywords').val($('#pKeywords_'+id).val());
   $('#pHeadline').val($('#pHeadline_'+id).val());
	$('#id').val($('#id_'+id).val());	
	
	$('#isEdit').val(id);
	
	if($('#uType').val() == 'gallary') {
		$('.calendar2').datepicker({dateFormat:'dd-mm-yy'});
		mptinyinit();
	}
      
   $('#c-img').attr('src', $('#url_'+id).val());
		
	$('#addMedia').show();
	$('#result-area').hide();
	
	$('#crop-image').hide();
	$('#add-image').show();
}

function mpUseImageStrip() {
	var obj = parent.document.getElementById($('#cName').val() + '_container');
	if($('#imageCount').val() > 0) {
		var order = $('#gallary-images').sortable('toArray').toString();	
		$('#imageOrder').val(order);
		$(".mp-delete").hide();
		var ht = $('#gallary-strip-place').html();
		var module = $('#mName').val();
		var	control = $('#cName').val();
		var	type = $('#uType').val();
		ht = '<div id="gal-edit"><a href="javascript:void(0);" onclick="mpOpenPlugin(\''+module+'\',\''+control+'\',\''+type+'\');">Add / Edit Slides</a></div>'+ht;
		obj.innerHTML = ht;
	}
	parent.ModalBox.close();
}

$(document).ready(function() {
	if($('#uType').val() == 'gallary') {
		// load gallary
		
		var canEdit = parent.document.getElementById('gal-edit');

		if(canEdit) {
			var obj = parent.document.getElementById($('#cName').val() + '_container');
				
			$('#gallary-strip-place').html(obj.innerHTML);
			$(".mp-delete").show();
			$('#gal-edit').remove();
		}
		
		mpEnableSortable();		
	}
});

function mpEnableSortable() {
	$('#gallary-images').sortable({
		scroll: true,
	    axis: 'x'
	});
}


function mptinyinit() {
	
	//alert(tinyMCE);
	if(typeof(tinyMCE)!="undefined") {
		
		tinyMCE.init({// General options  //All this code if for the TINYMCE Editor
		    mode : "exact",
		    theme : "advanced",
		    elements : "pCaption",
		    plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		    // Theme options
		    theme_advanced_buttons1 : "bullist,numlist,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleprops,spellchecker",
		    theme_advanced_buttons2 : "",
		    theme_advanced_buttons3 : "",
		    theme_advanced_toolbar_location : "top",
		    theme_advanced_toolbar_align : "center",
		    theme_advanced_statusbar_location : "bottom",
		    theme_advanced_resizing : false,
		    template_external_list_url : "js/template_list.js",
		    external_link_list_url : "js/link_list.js",
		    external_image_list_url : "js/image_list.js",
		    media_external_list_url : "js/media_list.js"
		});
	} else {
		alert("Please check tinyMCE");
	}
}