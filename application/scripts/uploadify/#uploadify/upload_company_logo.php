<?php 
define('BASE_FOLDER', '/clientmgt/');
if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];	
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . BASE_FOLDER . 'images/company_logo/';
	$targetPath = str_replace("/index.php","",$targetPath);
	//$targetFile =  $targetPath . $_FILES['Filedata']['name'];	
	
	$fileext        = strtolower(substr(strrchr($_FILES['Filedata']['name'], '.'), 0));			
	$new_filename   = strrchr($_REQUEST['folder'], '/company_logo/');	
	$new_filename 	= strtolower($new_filename);
	$new_filename   = str_replace(" ","-",$new_filename);
	$new_filename   = str_replace("'","",$new_filename);
	$new_filename   = str_replace("/","",$new_filename);			
	$new_filename   = str_replace("?","",$new_filename);
	$new_filename   = str_replace("!","",$new_filename);
	$new_filename   = str_replace(":","",$new_filename);
	$new_filename   = str_replace(".","",$new_filename);
	$new_filename   = str_replace(",","",$new_filename);
	$new_filename   = str_replace('"',"",$new_filename);
	$new_filename   = $new_filename . '-logo' . $fileext;
	
	
	$targetFile =  $targetPath . $new_filename;			
	//echo $targetFile;
	if( move_uploaded_file($tempFile,$targetFile)){		
		echo true;
	}else{
		echo false;
	}
		
}

?>