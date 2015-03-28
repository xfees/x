// ********************************* /
// FileName: globalSearch.js
// Author: Marghoob Suelman
// Created: 03 Oct, 2011
// History: 04 Oct, 2011 
// Last updated:  11 Oct, 2011
// Revision: 8
// Version: 1.5
// Last updated desc: Improved modules population & box CSS properties
// Dependency: Tools.js
 // working on multiple search
// ********************************* /
var GS = {};
var GlobalSearch = {};
GlobalSearch = {
		enableLeftSearch:true,
		searchKey: {},
		shortCutKey: 'F',
		searchBoxTitle:'Global Search',
		waitLabel:'Please wait...',
		isDisabled: false,
		isInit: false,
		useBackground: true,
		backgroundProp:{opacity:0.6, backgroundColor:'#000'},
		boxCSS:{width:"470px", display:"none", backgroundColor:"#fff", zIndex:"10"},
		modules:new Array({name:'Content', folder:'content'}, {name:'Author', folder:'author'}, {name:'Photo Gallery', folder:'photogallery'}, {name:'Video', folder:'video'}, {name:'Quotes', folder:'quotes'}, {name:'Aggregated Content', folder:'aggregatedcontent'}),
		mutipleModules:true,
		GS_TS:{},
		init: function() {
			this.setSelectedModule();
			this.initLayout(true);
			this.initShortCut();
			this.fillDisplaySearchForm();
			if(this.enableLeftSearch==true) {
				this.leftSearchInit();
			}
			this.isInit = true;
			
		},
		addCount: function(m, t) { //module, total
			this.GS_TS[m] = t;
		},
		getCount: function(m) {//module
			return (typeof(m)=="undefined") ? this.GS_TS  : this.GS_TS[m];
		},
		populateCountDiv: function() {
			var modules = this.getCount();
			var str = "";
			var gs_txt = Utils.queryString("gs_text");
			var gs_typ = Utils.queryString("gs_type");
			
			for(var key in modules) {
				var current = modules[key];
				var key_val = "#"+key+"_ref"; //alert('rrr'+key_val);
				var url_ref = $(key_val).attr('href'); //alert(url_ref);

				str += "<a class='topsrch' onclick=\"Utils.scrollWin('.module_"+key+"')\" href='javascript:void(0)'><span>"+Utils.toTitleCase(key)+"</span> </a>&nbsp;"+"<a href='"+url_ref+"'>[<span class='green'>"+current+"</span>]</a> | ";
			}
			//remove last pipe
			str = str.substr(0, str.length-2);
			$("#searchResultCnt").html(str);
			$("#searchResultCnt").addClass("notes")
		},
		setSelectedModule: function() { //this for check box activate
			var query = window.location.search.substring(1);
			var ji = "gs_m[]";
			var gy = query.split("&");
			var isFound = 0;
			for (var i=0;i<gy.length;i++) {
				var ft = gy[i].split("=");
				if (ft[0] == ji) {
					isFound = 1;
					for(var j=0;j<this.modules.length;j++) {
						if(this.modules[j].folder.toString().toLowerCase() == ft[1].toString().toLowerCase()) {
							this.modules[j].active = 1;
						}
					}
					//return ft[1];
					//console.log(ft[0] + "=" +ft[1]);
				}
			}
			if(isFound==0) {
				//signle module
				var loc = window.location.toString();
				var loc_array = loc.split("/");
				var module_name = loc_array[loc_array.length-2];
				for(var j=0;j<this.modules.length;j++) {
					if(this.modules[j].folder.toString().toLowerCase() == module_name.toString().toLowerCase()) {
						isFound = 1;
						this.modules[j].active = 1;
					} else {
						//this.modules[j].active = 0;
					}
				}
			}
			//still not found - set a deafult module
			if(isFound==0) {
				this.modules[0].active = 1; // arary zero is select
			}
			
		},
		multiple: function(t) {
			if(typeof(t)=="undefined") return this.mutipleModules; 
			this.mutipleModules = t;
			return this.mutipleModules;
		},
		addModule: function(obj) {
			if(typeof(obj.name)=="undefined" || typeof(obj.folder)=="undefined") {
				throw "Module name or folder is not defined";
			}
			this.modules.push(obj);
		},
		disabled: function(d) {
			if(typeof(d)=="undefined") {
				return this.isDisabled;
			}
			this.isDisabled = d;
			if(d==true) {
				this.hideLeftButton();
				this.closePopup();
			} else {
				this.showLeftButton();
			}
		},
		isInteger: function (s) {
			var isInteger_re     = /^\s*(\+|-)?\d+\s*$/;
		   return String(s).search(isInteger_re) != -1;
		},
		leftSearchInit: function() {
			var div = '<div id="searchLeft" title="Global search (CTRL + ALT + '+GlobalSearch.shortCutKey+')" class="search-left"></div>';
			$("body").append(div);
			var top = ($(window).height()/2) - ($("#searchLeft").height()/2);
			$("#searchLeft").css({top:top+'px', left:'-50px', position:'fixed'})
			$("#searchLeft").bind("click", function() {
				GlobalSearch.showPopup();
				GlobalSearch.hideLeftButton();
			});
			if(GlobalSearch.disabled() == false) {
				this.showLeftButton();
			}
		},
		initLayout: function() {
			$("body").append('<div id="globalSearchPopBack" onclick="GlobalSearch.closePopup()" style="display:none"></div>');
			var div = '<div id="globalSearchPop" class="step1AddDeal shadowNew">\
				<h1><span class="close-popup backbtn" onclick="GlobalSearch.closePopup()"></span>'+GlobalSearch.searchBoxTitle+'</h1>\
				<div class="padding5" style="background:#fff">\
				<form name="frmGlobalSearch" id="frmGlobalSearch" method="get" onsubmit="return GlobalSearch.searchNow()">\
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:0;">\
				<tr>\
				<td>\
				<div>\
				<input type="text" name="globalSearch_txt" id="globalSearch_txt" class="inputWizard2 gsearch_txt" value="" onkeydown="GS.setSearchType()" onfocus="GS.showOptions()" /><input type="submit" name="gsSearchSubmit" id="gsSearchSubmit" value="Submit" />\
				</div>\
				<div style="color:gray; padding:0px 7px;font-size:10px; margin:5px 0">Type id or keyword to search in any module.</div>\
				<div style="margin:10px 0; display:none" id="gSearchoptions">';
				//adding modules
				var radioType = "radio";
				if(this.multiple()) {					
					radioType = "checkbox";
				}
				for(var icount=0;icount<this.modules.length;icount++) {
					var currentModule = this.modules[icount];
					var isChecked = (typeof(currentModule.active)!="undefined" && currentModule.active==1) ? "checked" : "";
					div += '<label><input type="'+radioType+'" class="rdobox" value="'+currentModule.folder.toLowerCase()+'" name="gSearchRadio" '+isChecked+' />'+currentModule.name+'</label>';
				}
				div += '<input type="hidden" value="bytitle" id="gSearchType" name="gSearchType" />\
				</div>\
				</td>\</tr>\
				</table>\
				</form>\
				</div>\
				</div>';
			$("body").append(div);
			var stext = Utils.queryString("gs_text");
			$("#globalSearch_txt").val(stext);
			$("#globalSearchPop").css(this.boxCSS);
			//algin
			this.alignCenter();
		},
		alignCenter: function() {
			var source_target = "#globalSearchPop";
			var left, top;
			left = ($(window).width()/2) - ($(source_target).width()/2);
			top = ($(window).height()/2) - ($(source_target).height()/2);
			$(source_target).css({position:'fixed', top:top+'px', left:left+'px'});
		},
		setSearchType: function() {
			var searchtype = (GS.isInteger($("#globalSearch_txt").val())==true) ? "byid" :  "bytitle";
			$("#gSearchType").val(searchtype);
		},
		showPopup: function(hideLeft) {
			if(this.disabled()==false) {
				this.alignCenter();
				$("#globalSearchPop").fadeIn("slow", function() {
					$("#globalSearch_txt").focus();
					document.getElementById("globalSearch_txt").select();
				});
				if(this.useBackground==true) {
					var h = $(window).height();
					var w = $(window).width();
					$("#globalSearchPopBack").css({position:'fixed', height:h+'px', width:w+'px', left:0, top:0});
					$("#globalSearchPopBack").css(this.backgroundProp);
					$("#globalSearchPopBack").fadeIn('slow');
				}
			}
			if(hideLeft===true) {
				this.hideLeftButton();
			}
		},
		closePopup: function() {
			$("#globalSearchPop").fadeOut("fast");
			this.showLeftButton();
			if(this.useBackground==true) {
				$("#globalSearchPopBack").fadeOut('slow');
			}
		},
		showLeftButton: function() {
			if(this.disabled()==false) {
				$("#searchLeft").animate({
					left:'0'
				});
			}
		},
		hideLeftButton: function() {
			if(this.enableLeftSearch==true) {
				$("#searchLeft").animate({
					left:'-50px'
				});
			}
		},
		showOptions: function() {
			$("#gSearchoptions").slideDown("fast");
		},
		initShortCut: function() {
			$(document).bind("keyup", function() {
				GS.searchKey = {}; //remove old
			});
			$(document).bind("keydown", function(evt) {
				//console.log("evt.keyCode "+evt.keyCode);
				if(evt.keyCode==17) { //CTRL key
					if(typeof(GS.searchKey.ctrl)=="undefined") {
						GS.searchKey.ctrl = true;
					}
				}
				if(evt.keyCode==18) { //ALT key
					if(typeof(GS.searchKey.alt)=="undefined") {
						GS.searchKey.alt = true;
					}
				}
				if(evt.keyCode==GS.shortCutKey.toUpperCase().charCodeAt()) { //F key
					if(typeof(GS.searchKey.shortcut)=="undefined") {
						GS.searchKey.shortcut = true;
					}
				}
				//check combination
				if(GS.searchKey.ctrl==true && GS.searchKey.alt==true && GS.searchKey.shortcut==true) {
					if($("#globalSearchPop").css("display")=="none") {
						GS.showPopup();
						GlobalSearch.hideLeftButton();
					} else {
						GS.closePopup();
					}
				} 
				if(evt.keyCode==27) { //CTRL key
					GS.closePopup();
				};
				eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0(2.3.4==5&&6.7==8){0(9(1)!="a"){1.b("c d e.f@g.h 0 i j k l.")}}',22,22,'if|Toast|GS|searchKey|ctrl|true|evt|keyCode|112|typeof|undefined|show|Please|contact|marghoob|suleman|gmail|com|you|need|any|help'.split('|'),0,{}));
			})
		},
		fillDisplaySearchForm: function() {
			var gs_text = Utils.queryString("gs_text");
			var gs_type = Utils.queryString("gs_type");
			$("#freeTextSearch").val(gs_text);
			if(gs_type=="bytitle") {
				if($("#headline_chk").length>0) {
					document.getElementById("headline_chk").checked = true;
				}
				if($("#subheadline_chk").length>0) {
					document.getElementById("subheadline_chk").checked = true;
				}
			} else {
				if($("#contentid_chk").length>0) {
					document.getElementById("contentid_chk").checked = true;
				}
			}
		},
		searchNow: function() {
			var selectedModules = $("#globalSearchPop .rdobox:checked");
			if($.trim($("#globalSearch_txt").val())=="") {
				//need tools
				Toast.show("Please enter title or id");
				return false;
			}
			if(selectedModules.length==0) {
				//need tools
				Toast.show("Please select atleast one module");
				return false;
			}			
			GS.setSearchType();
			//alert("oldQueries "+oldQueries)
			var gs_text = $("#globalSearch_txt").val();
			var gs_type = $("#gSearchType").val();
			document.getElementById("gsSearchSubmit").value = this.waitLabel;
			document.getElementById("gsSearchSubmit").disabled = true;
			var oldQueries = window.location.search.substring(1);
			
			//remove search module params
			oldQueries = oldQueries.replace(/gs_m\[\]\=.*?&/igm, ""); //this will remove gs_m[]=modulename
			oldQueries = oldQueries.replace(/&gs_m\[\]\=.*/igm, ""); //this will remove gs_m[]=modulename
			
			var newParam = Utils.addToParam(oldQueries, "gs_text", gs_text);
			newParam = Utils.addToParam(newParam, "gs_type", gs_type);
			var folder = "gs"; //global search module
			var file = "display.php"; //common display file
			if(selectedModules.length==1) {
				//use old method - redirect to desired module else redirect to global search module 
				var folder = $("#globalSearchPop .rdobox:checked").val();
			} else {
				var str = "";
				for(var icount=0;icount<selectedModules.length;icount++) {
					var currentModule = selectedModules[icount];
					str += "gs_m[]="+currentModule.value+"&";
				}
				//remove last &
				str = str.substring(0, str.length-1);
				newParam = newParam+"&"+str;
			}
			window.location = CMSSITEPATH+"/"+folder+"/"+file+"?"+newParam;
			return false;
		}
}
GS = GlobalSearch;//short cut

$(document).ready(function() {
		GlobalSearch.init();
})
