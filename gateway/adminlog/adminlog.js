var MODULE_NAME = 'adminlog';
var MOBULE_FILE = 'get' + MODULE_NAME + '.php';

function searchform()
{
	$("#searchForm .current").removeClass("current");
	$("#searchForm select[value!='']").addClass("current");
	$("#searchForm input:text[value!='']").addClass("current");
	if($("#searchForm .current").length>0) {
		FX.highlight("#searchForm .current");
	}
    searchdata(MODULE_NAME,'bydata', '','','','','','') ;                            
}

function resetSearch() {
	document.getElementById("searchForm").reset(); 
	searchContent();
}
function searchByAuthorId(v) {
	$("#searchByAuthor").val(v);
	searchContent();
}
function searchByModuleId(v) {
	//$("#module_id :selected").text(v)
	//$("#module_id").val(v);
	$("#module_id option").filter(function() {
    return this.text == v; 
	}).attr('selected', true);searchContent();

}
function searchByActionType(v) {
	$("#searchByAction").val(v);
	searchContent();
}
function searchById(v) {
	$("#searchById").val(v);
	searchContent();
}