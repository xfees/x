// Tab Switcher
// Author: Marghoob Suleman | Search me on google
// jquery.tabs.js
// Created: unknown date
// version: 1.53
// Revision: 14
// History: 05/Apr/2010, 09/June/2010, 21/June/2010, 08/Dec/2010, 
// Last updated date: 11/Jan/2011 - expand and collapse method added;
// Features/public methods: next, previous, auto, getTitle, switchTabByCounter and more...
// 
;(function($){
		   var msMyTabs = function(element, settings) {
			var settings = $.extend({
								  tabs:'a',
								  contentSuffix:'_content',
								  event:'click',
								  selected:'selected',
								  effects:'fade',
								  callback:'',
								  defaultTab:'',
								  onInit:'',
								  auto:0
								  }, settings);		
			   var $this = this; //class object
			   var elementid = $(element).attr("id");
			   var oProp = new Object();
			   oProp.old = "";
			   oProp.isFirst = 0;
			   oProp.isLast = 0;
			   oProp.currenTabCounter =0;
			   var internalTabClassName = "msTabsContents"+elementid;
			   var intid = 0;
			   var init = function() {
				   //first time only
				   $("#"+elementid + " " + settings.tabs).bind(settings.event, function() {
																					  $this.switchTab(this.id);
																					  });
				   if($("#"+elementid + " " + settings.tabs +" a").length>0) {
					 $("#"+elementid + " " + settings.tabs +" a").bind(settings.event, function(evt) {
																					  evt.preventDefault();
																					  });				   
				   };
				  //hide all content
				   var allTabs = $("#"+elementid + " " + settings.tabs);
				   oProp.allTabs = allTabs;
				   for(var iCount=0;iCount<allTabs.length;iCount++) {
					   if(settings.defaultTab!=allTabs[iCount].id) {
						   $("#"+allTabs[iCount].id+settings.contentSuffix).hide();
						   //set class for internal use
						   $("#"+allTabs[iCount].id+settings.contentSuffix).addClass(internalTabClassName);
					   };
				   };
				   if(settings.defaultTab=='') {
					   settings.defaultTab = $("#"+elementid + " " + settings.tabs)[0].id;
					   //alert("settings.defaultTab "+settings.defaultTab);
				   };
				   $this.switchTab(settings.defaultTab);
				   if(settings.auto>0) {
					   //make it auto tab
				    $("#"+elementid + " " + settings.tabs).bind("mouseover", function(evt) {
																					  evt.preventDefault();
																					  pauseTabs();
																					  });
				    $("#"+elementid + " " + settings.tabs).bind("mouseout", function(evt) {
																					 evt.preventDefault();
																					  startAutoTabs();
																					  });
				    $("."+internalTabClassName).bind("mouseover", function(evt) {
																					  evt.preventDefault();
																					  pauseTabs();
																					  });
				    $("."+internalTabClassName).bind("mouseout", function(evt) {
																					 evt.preventDefault();
																					  startAutoTabs();
																					  });
						//start auto
					   startAutoTabs();
				   };
			   	
				if(settings.onInit!='') {
					eval(settings.onInit)($this);
				};
			   };
			   var nextTab = function () {
				   oProp.isLast = 0;
				   var totalTabs = oProp.allTabs;
				   if(oProp.currenTabCounter < totalTabs.length-1) {
					   oProp.currenTabCounter++;
					   var tabid = totalTabs[oProp.currenTabCounter].id;
					   //next is success
					   if(oProp.currenTabCounter == totalTabs.length-1) {
						   oProp.isLast = 1;
					   };
					   $this.switchTab(tabid);
				   };
				   //alert(" oProp.isLast " + oProp.isLast);
				   //zero means last
				   return oProp.isLast;
			   };
			   var previousTab = function () {
				   oProp.isFirst = 0;
				   var totalTabs = oProp.allTabs;
				   if(oProp.currenTabCounter > 0) {
					   oProp.currenTabCounter--;
					   var tabid = totalTabs[oProp.currenTabCounter].id;
					   //next is success
					   if(oProp.currenTabCounter==0) {
						   oProp.isFirst = 1;
					   };
					   $this.switchTab(tabid);
				   };
				   //zero means first
				   return oProp.isFirst;
			   };
			   this.previous = function() {
				   var isSuccess = previousTab();
				   return isSuccess;
			   };		   
			   this.next = function() {
				   var isSuccess = nextTab();
				   return isSuccess;
			   };
			   var getTabPosition = function (id) {
				   var allTabs = oProp.allTabs;
				   	for(var iCount=0;iCount<allTabs.length;iCount++) {
						if(allTabs[iCount].id==id) {
							return iCount;
						};
					};
					return -1;
			   };
			   this.switchTab = function(evt) {
				   if(typeof(evt)!="string") {
					   evt.preventDefault();
					   evt.stopPropagation();
				   } else if(typeof(evt)=="string") {
					   $("#"+evt).show();
				   };
				   this.collapseAll();
				   var id = (typeof(evt)=="string") ? evt : $(this).attr("id");
				   oProp.tabId = id;
				   oProp.currenTabCounter = getTabPosition(id);
				   var content = id+settings.contentSuffix;
				   oProp.content = content;
				   if(oProp.old!="") {
					  $("#"+oProp.old).removeClass(settings.selected);
					  if(settings.effects=='fade') {
						  $("#"+oProp.oldContent).hide();
						  $("#"+oProp.oldContent).fadeOut("fast", function(evt) {$("#"+content).fadeIn("fast", function() {
																														 fireCallback();
																														});});
					  } else {
						  $("#"+oProp.oldContent).slideUp("fast", function(evt) {$("#"+content).slideDown("fast", function() {
																														    fireCallback();
																														   });});
					  };
				   } else {
					   if(settings.effects=='fade') {
							$("#"+content).fadeIn("fast", function() {
																   fireCallback();
																   }); 
					   } else {
						   $("#"+content).slideDown("fast", function() {
																	 fireCallback();
																	 }); 
					   };
				   };
				   $("#"+id).addClass(settings.selected);
			   };
			   var fireCallback = function() {
				   oProp.old = oProp.tabId;
				   oProp.oldContent = oProp.content;				   
				   if(settings.callback!='') {
					   //alert(id+" settings.callback "+settings.callback);
					   eval(settings.callback)($this);
				   };				   				   	
			   };
			   this.getTitle = function() {	
			   		return $("#"+oProp.tabId).text();
			   };
			   var autoTabs = function () {
				   var totalTabs = oProp.allTabs;
				   var isLast = nextTab();
				   //console.debug("isLast "+isLast);
				   if(isLast===1) {
					   	oProp.currenTabCounter = -1;
				   };
			   };
			   var pauseTabs = function() {
				   window.clearInterval(intid);
			   };
			   var startAutoTabs = function() {
				   if(settings.auto>0) {
					   //make it auto tab
					 if(intid!=0) {
						window.clearInterval(intid);
					 }
					intid = window.setInterval(autoTabs, settings.auto);
				   };
			   };
			   this.getCurrentCounter = function() {
				   return oProp.currenTabCounter;
			   };
			   this.getAllTabs = function() {
				   return oProp.allTabs;
			   };
			   this.getAllProperties = function() {
				 return oProp;
			   };
			   this.switchTabByCounter = function(cnt) {
					var totalTabs = oProp.allTabs;
				   oProp.currenTabCounter = cnt;
				   var tabid = totalTabs[oProp.currenTabCounter].id;
				   $this.switchTab(tabid);
			   };
			   this.expandAll = function() {
				   var allTabs = this.getAllTabs();
				   for(var iCount=0;iCount<allTabs.length;iCount++) {
					   $("#"+allTabs[iCount].id).addClass(settings.selected);
					   $("#"+allTabs[iCount].id+settings.contentSuffix).show();
				   };				   
			   };
			   this.collapseAll = function() {
				   var allTabs = this.getAllTabs();
				   for(var iCount=0;iCount<allTabs.length;iCount++) {
					   		$("#"+allTabs[iCount].id).removeClass(settings.selected);
						   $("#"+allTabs[iCount].id+settings.contentSuffix).hide();
				   };
			   };
			   //init;
			   init();
		   };		   		   
	   $.fn.msTabs = function(opt) {  
			return this.each(function() {
								  var element = $(this);
								  var myplugin = new msMyTabs(element, opt);
								  element.data("msTabs", myplugin);
								  });		   
	   };
   })(jQuery);