/**
 * Order form
 * log
 * 2017-10-21	Created
 */
$(function(){
	
	/**
	 * extended the jQuery Class
	 */
	$.extend({
		orderFlow: {},
		init: function() {
			var $main = $('.contents');
			var $child = $main.children('.step');
			$.orderFlow = new PageTransitions($main, $child);
			$.orderFlow.init();
		},
		prev: function() {
			var prevPage = $.orderFlow.current - 1;
			var args = {"animation":2, "showPage":prevPage};
			$.orderFlow.nextPage(args);
		},
		next: function() {
			var nextPage = $.orderFlow.current + 1;
			var args = {"animation":1, "showPage":nextPage};
			$.orderFlow.nextPage(args);
		}
	});
	
	
});
