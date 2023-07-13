<?php
function new_autoload($class_name) {
	$class_name = "class_" . strtolower($class_name);
	$path = $_SERVER['DOCUMENT_ROOT'] . MAIN_FOLDER .'application/libraries/' . $class_name .'.php';
	if (is_file($path)) {
		if($class_name!='finfo') {			
			Loader::appMainLibrary($class_name);		
		}
	}
}

function php_excel_autoload($class_name) {
	$path = $_SERVER['DOCUMENT_ROOT'] . MAIN_FOLDER .'application/libraries/php_excel/' .str_replace('_', '/',$class_name) .'.php';
	if (is_file($path)) {
		include $path;
	}
}

function html_to_pdf($class_name) {
	$class_name = strtolower($class_name);
	$path = $_SERVER['DOCUMENT_ROOT'] . MAIN_FOLDER .'application/libraries/html2pdf/'. $class_name .'.class.php';
	if (is_file($path)) {
		//Loader::appMainLibrary('html2pdf/html2pdf.class');
		include $path;
	}
}

function swift_mailer_autoload($class_name) {
	$path = $_SERVER['DOCUMENT_ROOT'] . MAIN_FOLDER .'application/libraries/swiftmailer/lib/swift_required.php';
	if (is_file($path)) {
		include $path;
	}
}

spl_autoload_register('php_excel_autoload');
spl_autoload_register('html_to_pdf');
spl_autoload_register('swift_mailer_autoload');
spl_autoload_register('new_autoload');
?>