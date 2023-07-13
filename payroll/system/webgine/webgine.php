<?php
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

ob_start();

//require_once ("Benchmark/Timer.php");
//$timer = new Benchmark_Timer;
//$timer->start();
//$timer->setMarker('Marker 1');
// include Loader class

// start the output buffer

require_once WEBGINE_PATH . '/loader.php';

// include system functions
Loader::webgine('base');
Loader::webgine('error');

$error = new Wg_Error();
$error->setErrorHandler();

// include the Global_Controller class
Loader::appController('global');

// include the Model, View, Controller class
Loader::webgine('model');
Loader::webgine('controller');
Loader::webgine('view');

// include Config
Loader::appConfig('version');
Loader::appConfig('config');
//Benchmarking
//Loader::appMainLibrary('test_driven_development/autorun');
//Loader::appMainLibrary('test_driven_development/simple_test_case');
//Loader::appMainLibrary('test_driven_development/unit_test_case');



// USE THE constant in main folder
Loader::appMainConfig('constants');
//USE THE config_company
Loader::appMainConfig('config_company');
Loader::appConfig('lang_en');

// include Database config
Loader::appConfig('database');
$base_folder = BASE_FOLDER;
if (!HIDE_INDEX_PAGE) { $base_folder .= INDEX_PAGE . '/'; }

write_top_script('
var base_url="' . $base_folder . '";
var base_folder = "' . BASE_FOLDER . '";
var theme = "'. THEME .'";
');

if (DB_DATABASE != '')
{
	//connect to database
	$cnt = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
	mysql_select_db(DB_DATABASE, $cnt) or die(mysql_error());
	mysql_query ('SET NAMES UTF8');
}

// get current controller request
$controller = get_controller();

if (!is_valid_controller($controller)) {
	$controller = IF_INVALID_CONTROLLER;
}
Loader::appController($controller);

// execute the class and method
$suffix = get_controller_suffix();

$c = "{$controller}{$suffix}";
$wg_obj = new $c;

// if invalid method
if (!$method = get_method($wg_obj))
{	
	display_error();
}

if ($wg_obj->default_method != $method)
{
	define('HAS_URL_METHOD', 'true');
}

define("CONTROLLER", $controller);
define("METHOD", $method);

if(MAINTENANCE_MODE==true) {
	display_offline();
}
$wg_obj->$method();

//$timer->stop();
//$timer->display();
?>