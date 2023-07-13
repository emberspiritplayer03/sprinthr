<?php

//FRONT CONTROLLER

$application_folder = "application";



// =======================================



// DONT EDIT THIS PART



define("SYS_FOLDER", 'system');

define("APP_FOLDER", $application_folder);

define("BASE_PATH", str_replace("\\", "/", realpath(dirname(__FILE__))).'/');

define("EXT", '.php');



$system_folder_absolute = str_replace("\\", "/", realpath(dirname(__FILE__))).'/' . SYS_FOLDER . '/';

$application_folder_absolute = str_replace("\\", "/", realpath(dirname(__FILE__))).'/'. APP_FOLDER . '/';



define("SYS_PATH", $system_folder_absolute);

define("APP_PATH", $application_folder_absolute);

define("WEBGINE_PATH", SYS_FOLDER . '/webgine/');

require_once WEBGINE_PATH . "webgine.php";

 $_SERVER['DOCUMENT_ROOT']. MAIN_FOLDER.'/application/libraries'.LIBRARY_FOLDER;


?>

