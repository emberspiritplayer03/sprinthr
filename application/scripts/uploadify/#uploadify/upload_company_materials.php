<?php 
define('BASE_FOLDER', '/client/');
if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];	
	$folder_name = strrchr($_REQUEST['folder'], '/files/companies/');	
	$targetPath  = $_SERVER['DOCUMENT_ROOT'] . BASE_FOLDER . 'files/companies/' . $folder_name . '/';
	$targetPath  = str_replace("/index.php","",$targetPath);
	$targetFile  =  $targetPath . $_FILES['Filedata']['name'];			
	//echo $targetFile;
	if( move_uploaded_file($tempFile,$targetFile)){		
		echo true;
	}else{
		echo false;
	}
		
}

?>