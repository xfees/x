;(function($){
	$.fn.extend({
		//plugin name - animatemenu
		msMenu: function(options) {
			return this.each(function() {

				//Assign current element to variable, in this case is UL element
				var obj = $(this);
				//alert(obj);
				$("li ul", obj).each(function(i) {
					$(this).css('top', $(this).parent().outerHeight());
				})

				$("li", obj).hover(
					function () { 
					$(this).addClass('over'); 
					var pos = $(this).position();
					var height = $(this).height();
					if($(this).find("ul").length)
						{
						$(this).find("ul").css({display:'block',position:'absolute', left:(pos.left)+"px", top:(pos.top+height)+'px', zIndex:999, float:'left'}).show();
						} 
					},
					function () { $(this).removeClass('over'); if($(this).find("ul").length){$(this).find("ul").hide()}}
				);

			})
		}
	});
})(jQuery);

jQuery(document).ready(function()
								{
									if($("#adminMenu").length!=0) 
									{
										$("#adminMenu").msMenu();
									}
								});
