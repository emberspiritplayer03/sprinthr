<?php 


function __autoload($class_name) {
	
	if($class_name!='finfo') {
		$class_name = "class_" . strtolower($class_name);
     	Loader::appLibrary($class_name);	
		
	}
	
}


?>