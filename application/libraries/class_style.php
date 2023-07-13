<?php
class Style {
	
	//Usage: Style::loadTextBoxList();
	
	public static function loadTableThemes($element) {
		Loader::appStyle('assets/tabletheme/table_theme.css');
	}
	
	public static function loadMainTableThemes($element) {
		Loader::appMainStyle('assets/tabletheme/table_theme.css');
	}
	
	public static function loadPeriodicTableForm() {
		Loader::appStyle('assets/periodictable/periodic_table_form.css');
		Loader::appScript('periodictable/periodictableform.js');
	}
	
	public static function loadMainPeriodicTableForm() {
		Loader::appMainStyle('assets/periodictable/periodic_table_form.css');
		Loader::appMainScript('periodictable/periodictableform.js');
	}
	
	public static function loadPeriodicTable() {
		Loader::appStyle('assets/periodictable/periodic_table.css');
		Loader::appScript('periodictable/periodictable.js');
	}
	
	public static function loadMainPeriodicTable() {
		Loader::appMainStyle('assets/periodictable/periodic_table.css');
		Loader::appMainScript('periodictable/periodictable.js');
	}
	
	public static function loadFormDesign() {
		Loader::appStyle('assets/formdesign/form_design.css');
	}
	
	public static function loadMainFormDesign() {
		Loader::appMainStyle('assets/formdesign/form_design.css');
	}
	
	
}

?>