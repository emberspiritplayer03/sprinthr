/*
 * 
 * Kdatatable 1.0 - Client-side datatable
 * Version 2.0
 * @requires jQuery v1.4.2.min.js
 * 
 * Copyright (c) 2012 Bryan Bio
 
 * @author Bryan Bio/bryan.yobi@gmail.com
 */
(function($) {
	$.fn.kdatatable = function(customOptions) {
		var options = $.extend({}, $.fn.kdatatable.defaultOptions, customOptions);
		
		return this.each(function() { //Loop over each element in the set and return them to keep the chain alive.
			var $this = $(this);
			var opt   = options;
			
			var limit = $("#" + opt.limit).val();
			
			$('#' + opt.loading_wrapper).html('loading...');
			$.post(opt.url,{limit:limit},
			function(o){
				$('#' + opt.table_wrapper).html(o.table);
				$('.' + opt.paginator_wrapper).html(o.paginator)
			},"json");	
			
			var gtp = function() {
				alert(1);
				function gotoPage(displayStart,paginatorIndex){		
					alert(1);
				}
		
			}
		
		});
		
		$.fn.kdatatable.defaultOptions = {
			url: "",
			limit: "dt_limit",
			table_wrapper: "product_list_wrapper",
			paginator_wrapper: "paginator",
			loading_wrapper: "dt_processing"             
		}
		
		function gotoPage(displayStart,paginatorIndex){		
			alert(1);
		}
	};
	
})(jQuery);