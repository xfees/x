/***************************/
//@Author: Adrian "yEnS" Mato Gondelle
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatus = 0;

//loading popup with jQuery magic!
function loadPopup(id){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$("#backgroundPopup"+id).css({
			"opacity": "0.7"
		});		
		$("#backgroundPopup"+id).fadeIn("slow");
		
		$("#popupContact"+id).fadeIn("slow");
		popupStatus = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopup(id,module){
	//disables popup only if it is enabled
	if(popupStatus==1){
		$("#backgroundPopup"+id).fadeOut("slow");
		$("#popupContact"+id).fadeOut("slow");	
		$.post("get"+module+".php", {"refreshDivId" :id,"flag" : 'm'}, function(data) { //alert(data);
			$("#singleCont"+id).html("");	
			$("#singleCont"+id).append(data);																  
		}); 
		popupStatus = 0;
	}
}

//centering popup
function centerPopup(id){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupContact"+id).height();
	var popupWidth = $("#popupContact"+id).width();
	//centering
	$("#popupContact"+id).css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopup"+id).css({
		"height": windowHeight,
		"width": (windowWidth-2)
	});
	
}


//CONTROLLING EVENTS IN jQuery

	function addbutton(id,module){
		//alert("id"+id);
	//LOADING POPUP
	//Click the button event!

		//centering with css
		centerPopup(id);
		/*alert(id);
		return false;*/
		//load popup
		loadPopup(id);
		/*alert(id);
		return false;*/
				
	//CLOSING POPUP
	//Click the x event!
	$("#popupContactClose"+id).click(function(){
		disablePopup(id,module);
	});
	//Click out event!
	$("#backgroundPopup"+id).click(function(){
		disablePopup(id,module);
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatus==1){
			disablePopup(id,module);
		}
	});

	}
