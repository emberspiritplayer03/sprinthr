<?php 

class Yui {
	
	//Usage: Yui::loadDatatable();
	
	public static function loadDatatable($element) {
		Loader::appScript('datatable/datatable.js');
		Loader::appScript('yui/build/yahoo-dom-event/yahoo-dom-event.js');
		Loader::appScript('yui/build/element/element-min.js');
		Loader::appScript('yui/build/utilities/utilities.js');
		Loader::appScript('yui/build/datasource/datasource-min.js');
		Loader::appScript('yui/build/paginator/paginator-min.js');
		Loader::appScript('yui/build/datatable/datatable.js');
		Loader::appStyle('assets/datatable/datatable.css');
		Loader::appStyle('assets/datatable/datatable-paginator.css');
	}
	
	public static function loadMainDatatable($element) {
		Loader::appMainScript('datatable/datatable.js');
		Loader::appMainScript('yui/build/yahoo-dom-event/yahoo-dom-event.js');
		Loader::appMainScript('yui/build/element/element-min.js');
		Loader::appMainScript('yui/build/utilities/utilities.js');
		Loader::appMainScript('yui/build/datasource/datasource-min.js');
		Loader::appMainScript('yui/build/paginator/paginator-min.js');
		Loader::appMainScript('yui/build/datatable/datatable.js');
		Loader::appMainStyle('assets/datatable/datatable.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');
	}
}

?>