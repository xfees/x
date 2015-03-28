// ********************************* /
// FileName: tools.js
// Author: Marghoob Suelman
// Desc: This script needs jquery UI, will be responsible for common effects and utilities
// Created: 17 May, 2011
// Version: 1.5
// ********************************* /

var Utils = {};
Utils = {
	scrollMe: function(div, speed) {
		var position = $("#"+div).position();
		var windowPos = $(window).scrollTop();
		if(windowPos<45) {
			windowPos = 45;
		}
		//console.debug("windowPos "+windowPos);
		var scrollSpeed = (typeof(speed)=="undefined") ? 500 : speed;
		$("#"+div).animate({top:(windowPos)}, {queue:false, duration:scrollSpeed});
	},
	scrollWin: function(where, duration) {
		var pos;
		if(typeof(where)=="number") {
			pos = where;
		} else {
			pos = $(where).position();
			pos = pos.top;
			//alert(pos);
		}
		var d = (typeof(duration)=="undefined") ? 500 : duration;
		$("html, body").animate({scrollTop:(pos)}, {queue:false, duration:d});
	},
	toTitleCase: function (str)
	{
		return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
	},
	setPos: function(s, t, w) {
		//more will improve later
		if(typeof(s)=="undefined" || typeof(t)=="undefined") return false;
		var source_target = s;
		var to_target = t;
		var where = (typeof(w)=='undefined') ? 'right' : w;
		var pos = $(to_target).position();
		var top, left, right;
		//console.debug(where + " " +pos.left + " " +pos.top)
		switch(where) {
			case 'right':
				left = pos.left + $(to_target).width();
				top = pos.top;
			break;
			case 'left':
				left = pos.left -  $(source_target).width();
				top = pos.top;				
			break;
			case 'bottomLeft':
				left = pos.left;
				top = pos.top+$(to_target).height();
			break;
			case 'topLeft':
				left = pos.left;
				top = pos.top-$(source_target).height();				
			break;
			case 'bottomRight':
				left = pos.left+$(to_target).width();
				top = pos.top+$(to_target).height();
			break;
			case 'topRight':
				left = pos.left + $(to_target).width();
				top = pos.top-$(source_target).height();				
			break;
			case 'center':
				left = (pos.left + $(to_target).width()/2) - ($(source_target).width()/2);
				top = (pos.top + $(to_target).height()/2) - ($(source_target).height()/2);
			break;
			default:
				left = (pos.left + $(to_target).width()/2) - ($(source_target).width()/2);
				top = (pos.top + $(to_target).height()/2) - ($(source_target).height()/2);
			break;
		}
		//console.debug("top "+top+ " left "+left);
		$(source_target).css({position:'absolute', top:top+'px', left:left+'px'});
	},
	queryString: function (ji, custom) {
		var query = (typeof(custom)=="undefined") ? window.location.search.substring(1) : custom;
		var hu = query;
		var gy = hu.split("&");
		for (i=0;i<gy.length;i++) {
			var ft = gy[i].split("=");
			if (ft[0] == ji) {
				return ft[1];
			}
		}
		return "";
	},	
	queryStringForPaging: function (allParams) {
		var hu = window.location.search.substring(1);
		var gy = hu.split("&");
		var str = '';
		var allparam = (typeof(allParams)=="undefined") ? false : true; //not using
		for (i=0;i<gy.length;i++) {
			var current = gy[i].split("=");
			var key = current[0];
			var value = current[1];
			if(key!="offset" && typeof(value)!='undefined') {
				str += key+"="+value+"&";
			}
		}
		return str;
	},
	addToParam: function(params, key, val) {
		if(typeof(key)!== "undefined" && typeof(val)!== "undefined") {
			var pm = (params.length==0) ? "" : "&";
			if(params.indexOf(key)!=-1) {
				params = params.replace(key+"="+this.queryString(key, params), key+"="+val);
			} else {
				//var pm = "&";
				params = params+pm+key+"="+val;
			}
		}	
		return params;
	}
}

//******************* Dialog Box *************************************/
// version: 1.5
var DialogBox = {};
DialogBox = {
	init: function() {
		if($("#dialogMessage").length==0) {
			$("body").append("<div id='dialogMessage' style='display=none'></div>");
		}
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
	},
	showAlert: function(msg, t, fn){
		this.init();
		var title = (typeof(t)==="undefined") ? "" : t;
		$("#dialogMessage").html(msg);
		$("#dialogMessage").dialog({
			title:title,
			modal: true,
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
					if(typeof(fn)!=='undefined') {
						fn.apply(this, arguments);
					}
				}
			}
		});				
	}, 
	showConfirm: function(msg, t, callback) {
		this.init();
		$("#dialogMessage").html(msg);
		var title = (typeof(t)==="undefined") ? "" : t;
		$("#dialogMessage").dialog({
			title:title,
			modal: true,
			buttons: {
				No: function() {
					$( this ).dialog( "close" );
					eval(callback)(false);
					//callback.apply(this, arguments)
					//return false;
				},
				Yes: function() {
					$( this ).dialog( "close" );
					eval(callback)(true);
					//return true;
				}				
			}
		});		
	},
	setOption: function(prop, val) {
		$("#dialogMessage").dialog("option", prop, val);
	}
}
/*************** FORM UTILS ***************/
FRMS = {
	oldTextProp: {},
	restorOldText: function (arg) {
		var nameKey = $(arg).attr("name");
		var obj = this.oldTextProp[nameKey];
		if($.trim($(obj.target).val()) == "") {
			$(obj.target).val(obj.value);
			$(obj.target).unbind("blur");
			$(obj.target).removeClass("blackText");
		}
	},
	manageInputText: function (arg, dontClear) {
		var target = $(arg);
		var nameKey = $(arg).attr("name");
		var curerntValue = $(arg).val();
		if(typeof(this.oldTextProp[nameKey]) == "undefined") {
			//store first value
			this.oldTextProp[nameKey] = {target:target, value:curerntValue};
			$(target).bind("focus", function(evt) {
											 $(target).addClass("blackText");
											 evt.target.select();
											 });
			
		}
		if(curerntValue == this.oldTextProp[nameKey].value) {
			if(typeof(dontClear) == "undefined") {
				$(target).val("");
			}
			$(target).bind("blur", function() {
				FRMS.restorOldText(target);
			});		
		}
	},
	focus:function(id) {
		document.getElementById(id).focus();
		//document.getElementById(id).select();
	},
	selectCombo: function(t, s) {
		var target = document.getElementById(t);
		var allOptions = target.options;
		var searchFor = s;
		for(var icount=0;icount<allOptions.length;icount++) {
			if((allOptions[icount].text.toString().toLowerCase() == searchFor.toLowerCase()) || allOptions[icount].value==searchFor) {
				target.selectedIndex = icount;
				break;
			}
		}
		
	}
}

//Effects
var FX = {
	come: function(div, cb) {
		var target = div;
		var callback = cb;
		$(target).fadeIn("fast", function() {
										  if(typeof(callback)!="undefined") {
											  callback.apply(this, arguments);
										  }
										  })
	},
	out: function(div, cb) {
		var target = div;
		var callback = cb;
		$(target).fadeOut("fast", function() {
										  if(typeof(callback)!="undefined") {
											  callback.apply(this, arguments);
										  }
										  })		
	},
	highlight: function(div, cb) {
		var bgcolor = $(div).css("backgroundColor");
		var callback = cb;
		$(div).animate({
			backgroundColor: "#FFFF99"
		  }, 1000, function() {
				$(div).animate({
					backgroundColor: bgcolor
				  }, 1000, function() {
					   if(typeof(callback)!="undefined") {
							 callback.apply(this, arguments);
					   }
				  });			
		  });


	},
	toggleSlide: function(div, cb) {
		var callback = cb;
		$(div).toggle('slide', function() {
										if(typeof(callback)!="undefined") {
											callback.apply(this, arguments);
										}										
										});
	},
	slideDown: function(div, cb) {
		var callback = cb;
		$(div).slideDown('fast', function() {
										if(typeof(callback)!="undefined") {
											callback.apply(this, arguments);
										}										
										});
	},
	slideUp: function(div, cb) {
		var callback = cb;
		$(div).slideDown('fast', function() {
										if(typeof(callback)!="undefined") {
											callback.apply(this, arguments);
										}										
										});
	},
	scrollWinTo: function(w) {
		var div = w;
		if($(div).length>0) {
			$('html, body').animate({scrollTop:$(div).position().top}, 1000);
		}
	},
	explode: function(d, cb) {
		if(typeof($.ui) !== "undefined") {
			$(d).effect("explode", function() {
				if(typeof(cb)!=="undefined") {
					cb.apply(this, arguments);
				}
			})
		} else {
			//fade out if ui is not available
			$(d).fadeOut("slow", function() {
				cb.apply(this, arguments);
			})
		}
	},
	transfer: function(from, to, callback) {
		if(typeof($.fn.effect)!="undefined") {
                        if($(from).length>0 && $(to).length>0) {
                            var pos = $(to).position();
                            var speed = 500;
                            var options = { to: to, className: "ui-effects-transfer"};
                            $(from).effect("transfer", options, speed, callback);
                            FX.highlight(to);
                        }
		}
	}
}
var Fx = FX;

/*************** Toast by marghoob suleman ***************/
var Toaster = function(msg, w, timeout, callbackAfter) {
		var target = w;
		var where = (typeof(target)=="undefined") ? "window" : target;
		var sMsg = msg;
		var intervalid = 0;
		var iTimeout = (typeof(timeout)=="undefined") ? 1000 : timeout;
		if(typeof(sMsg)=="undefined") {
			return false;
		}
		var id = "toater_"+Toast.counter++;
		if($("#"+id).length==0) {
			//create new one
			$('body').append("<div id='"+id+"' class='toasterHolder'></div>");
		}
		$("#"+id).html(sMsg).hide();
		var top, left, right = 0;
		var pos;
		switch(where) {
			case 'window':
				//align to windows
				//console.debug("$(window).height() "+$(window).height())
				left = ($(window).width()/2)-($("#"+id).width()/2)
				top = ($(window).height()/2 - $("#"+id).height()/2)+$(window).scrollTop();
			break;
			default:
				//align to target's next to right
				target = (typeof(target)=="string") ? document.getElementById(target)  : target;
				pos = $(target).position();
				left = (pos.left+$(target).width()+10);
				top = (pos.top);//+$(window).scrollTop();
			break;
		}
//		console.debug(" left "+left + " top "+top);
		$("#"+id).css({position:'absolute', left:left+'px', top:top+'px', zIndex:9999});
		clearInterval(intervalid);
		FX.come("#"+id, function() {
									intervalid = setInterval(function(){
																	  FX.out("#"+id, function() {
																					  $("#"+id).remove();
																					  })
																	  clearInterval(intervalid);
																	  if(typeof(callbackAfter)!=="undefined") {
																		  callbackAfter.apply(this, arguments);
																	  }
																	  }, iTimeout);
									});
		this.killAll = function() {
			clearInterval(intervalid);
			$(".toasterHolder").remove();
		}
};

Toast = {
	id:'toaster',
	counter:20,
	timeout:3000,
	intervalid:0,
	show: function(msg, w, timeout, callbackAfter) {
		var iTimeout = (typeof(timeout)=="undefined") ? Toast.timeout : timeout;
		this.killAll();
		new Toaster(msg, w, iTimeout, callbackAfter);
	},
	showMultiple: function(msg, w, timeout, callbackAfter) {
		var iTimeout = (typeof(timeout)=="undefined") ? Toast.timeout : timeout;
		new Toaster(msg, w, iTimeout, callbackAfter);
	},
	killAll: function() {
		$(".toasterHolder").remove();
	},
	alignToast: function(onId, where) {
		var toasterid = $(".toasterHolder").attr("id");
		//console.log(toasterid + " "+onId);
		Utils.setPos("#"+toasterid, "#"+onId, where); //setPos: function(s, t, w)		
	},
	alignWithModalBox: function(src) {
		var toasterid = $(".toasterHolder").attr("id");
		var pos1 =  $("#"+src).position();
		var pos2 =  $("#simplemodal-container").position();
		var top = pos1.top + pos2.top;
		var left =pos1.left + pos2.left + $("#"+src).width();
		$("#"+toasterid).css({top:top+'px', left:left+'px'});
	},
	alignInModelWindow: function(modelid, elid) {
		var modelId = modelid;
		var elementid = elid;
		var s  = $("#"+modelId).position();
		var d = $("#"+elementid).position();
		var top = s.top + d.top;
		var left = s.left + d.left + $("#"+elementid).width();
		var toasterid = $(".toasterHolder").attr("id");
		$("#"+toasterid).css({top:top+'px', left:left+'px'}); //setPos: function(s, t, w)	
	}
}
/*************************/
//needs jquery.simplemodal-1.4.1.js and css
ModalBox = {
		open: function(url, w, h, o, c, oOpen) {
			var src = url;
			var overlayClose = (typeof(o)=="undefined") ? true : o;
			var onClose = (typeof(c)=="undefined") ? '' : c;
			var onOpenW = (typeof(oOpen)=="undefined") ? '' : oOpen;
			var windowHeight = (typeof(h)=="undefined") ? ($(window).height() - 100) : h; 
			var windowWidth = (typeof(w)=="undefined") ? ($(window).width() - 100) : w;
			$.modal('<iframe src="' + src + '" height="'+windowHeight+'" width="'+windowWidth+'" style="border:0">', {
				containerCss:{
					backgroundColor:"#fff",
					borderColor:"#fff",
					height:(windowHeight),
					padding:0,
					width:(windowWidth)
				},
				overlayClose:overlayClose,
				onClose: function() {
					$.modal.close();
					if(onClose!='') {
						onClose.apply(this, arguments);
					}
				},
				onShow: function() {
					if(onOpenW!='') {
						onOpenW.apply(this, arguments);
					}
				},
				escClose:true
			});
		},
		close: function() {
			$.modal.close();
		}
}

