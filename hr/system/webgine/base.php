<?php
/**
 * Get the requested controller
 *
 * @return string
 */
function get_controller()
{
	$path = ($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : $_SERVER['PHP_SELF'];
	if (BASE_FOLDER != '/')
	{
		$path = str_replace(BASE_FOLDER, '', ($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : $_SERVER['PHP_SELF']);
	}
	$first_character = $path[0];
	$last_character = $path[strlen($path) - 1];
	$path = ($first_character == '/') ? substr($path, 1) : $path; // remove first character if it is '/'
	$path = ($last_character == '/') ? substr($path, 0, strlen($path) - 1) : $path ; // remove last character if it is '/'

	// get the controller from URI
	$controllers = explode('/', $path);
	$controller = '';
	foreach ($controllers as $c) {
		if ($c != INDEX_PAGE && $c != '') {
			$controller = $c;	
			break;
		}
	}
	if ($controller == '') {
		$controller = DEFAULT_CONTROLLER;
	} else {
		if (!is_valid_controller($controller)) {
			$controller = IF_INVALID_CONTROLLER;
		}
	}
	return $controller;
}

/**
 * Get the requested controller's method
 *
 * @return string
 */
function get_method($wg_obj)
{
	$path = ($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : $_SERVER['PHP_SELF'];
	if (BASE_FOLDER != '/')
	{
		$path = str_replace(BASE_FOLDER, '', ($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : $_SERVER['PHP_SELF']);
	}
	$paths = explode("/", $path);

	$method = (sizeof($paths) > 1 && strlen($paths[1]) > 0) ? $paths[2] : DEFAULT_METHOD;
	if (!method_exists($wg_obj, $method))
	{
		// check if default method is set
		if (method_exists($wg_obj, $wg_obj->default_method))
		{			
			$method = $wg_obj->default_method;	
		}
		else
		{
			$method = (method_exists($wg_obj, "index") && $method == '') ? 'index' : false ;
		}
	}
	return $method;
}

/**
 * Get the base url ex. http://www.example.com/myfolder/
 *
 * @return string
 */
function get_base_url()
{
	return 'http://' . $_SERVER['HTTP_HOST'] . BASE_FOLDER;
}

function hr_url($url)
{
	return 'http://' . $_SERVER['HTTP_HOST'] . BASE_FOLDER . 'index.php/' . $url;
}

function help_url()
{
	return 'http://' . $_SERVER['HTTP_HOST'] . HELP_FOLDER . 'index.php/Main_Page';
}

function recruitment_url($url)
{
	return 'http://' . $_SERVER['HTTP_HOST'] . RECRUITMENT_BASE_FOLDER . 'index.php/' . $url;
}

function clerk_url($url)
{
	return 'http://' . $_SERVER['HTTP_HOST'] . CLERK_BASE_FOLDER . 'index.php/' . $url;
}

function clerk_schedule_url($url)
{
	return 'http://' . $_SERVER['HTTP_HOST'] . CLERK_SCHEDULE_BASE_FOLDER . 'index.php/' . $url;
}

function employee_url($url)
{
	return 'http://' . $_SERVER['HTTP_HOST'] . EMPLOYEE_BASE_FOLDER . 'index.php/' . $url;
}

function payroll_url($url)
{
	return 'http://' . $_SERVER['HTTP_HOST'] . PAYROLL_BASE_FOLDER . 'index.php/' . $url;
}

/**
 * Get the parameter value from URI
 *
 * @param int $param_number
 */
function get_param($param_number)
{
	$param_number = (int) --$param_number;
	$path = ($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : $_SERVER['PHP_SELF'];
	$first_character = $path[0];
	$last_character = $path[strlen($path) - 1];
	$path = ($first_character == '/') ? substr($path, 1) : $path; // remove first character if it is '/'
	$path = ($last_character == '/') ? substr($path, 0, strlen($path) - 1) : $path; // remove last character if it is '/'
	$paths = explode('/', $path);
	
	//print_r($paths);
//	if (HAS_URL_METHOD == 'true') 
//	{
//		array_shift($paths); //removes controller
//		array_shift($paths); // removes method	
//	}
//	else // no method in url
//	{
//		array_shift($paths); //removes controller
//	}
	$folders = get_base_folders();
	foreach ($paths as $key => $p) {
		if ($folders) {
			if (!in_array($p, $folders) && $p != INDEX_PAGE) {
				$new_methods[] = $p;	
			}
		}
	}
	return $new_methods[$param_number];
}

/**
 * Get base folder without '/'
 *
 * @return String
 */
function get_base_folders() {
	$base_folder = BASE_FOLDER;
	if ($base_folder == '/') {
		return false;	
	}
	
	$folders = explode('/', $base_folder);
	$new_folders = array();
	foreach ($folders as $f) {
		if ($f != '') {
			$new_folders[] = $f;
		}
	}
	return $new_folders;
}

/**
 * Get all params including controller and methods
 *
 * @return Array
 */
function get_all_params() {
	$path = ($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : $_SERVER['PHP_SELF'];
	$first_character = $path[0];
	$last_character = $path[strlen($path) - 1];
	$path = ($first_character == '/') ? substr($path, 1) : $path; // remove first character if it is '/'
	$path = ($last_character == '/') ? substr($path, 0, strlen($path) - 1) : $path; // remove last character if it is '/'
	$paths = explode('/', $path);
	return $paths;
}

/**
 * Get the controller's suffix
 *
 * @return string
 */
function get_controller_suffix()
{
	return '_controller';
}

function display_error($page = null, $options = '')
{
	if (is_array($options))
	{
		foreach ($options as $option => $value)
		{
			${$option} = $value;
		}
	}

	$page = ($error == null) ? '404.php' : $error ;
	include APP_PATH . 'errors/' . $page;
	die();
}

function display_offline()
{
	include APP_PATH . 'errors/offline.php';
	die();
}

function display_update_notice()
{
	include APP_PATH . 'views/updates/update_notice.php';
	
}

/**
 * Write Javascript code to be embedded within <head> tag
 *
 * @return void
 */
function write_script($code)
{
	Loader::$script_code .= $code;
	/*Loader::$scriptstyle_to_load .= '<script type="text/javascript">' . $code . '</script>|';*/
}

function write_top_script($code) {
	Loader::$script_top_code .= $code;
}

/**
 * Write Stylesheet to be embedded within <head> tag
 *
 * @return void
 */
function write_style($value)
{
	Loader::$scriptstyle_to_load .= '<style>' . $value .'</style>|';
}

function start_cache($cachefile, $cachetime = '') {
	$cachetime = ($cachetime == '') ? (30 * 60) : $cachetime ;
	$iETag = (int) filemtime($cachefile);   
	$last_modified = gmdate('D, d M Y H:i:s', $iETag).' GMT';
	
	//$cachetime = 15 * 60; // 5 minutes
	//if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile))) {
		//echo "Cached ".date('jS F Y H:i', filemtime($cachefile));
		//include($cachefile);
		//exit();
		
		if ((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $last_modified) ||
		 (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $iETag)) {
			header("{$_SERVER['SERVER_PROTOCOL']} 304 Not Modified");
			exit();
		}
		
		//echo "Cached ".date('jS F Y H:i', filemtime($cachefile));	
		if (file_exists($cachefile)) {
			header('Expires: '.gmdate('D, d M Y H:i:s', time() + $cachetime).' GMT'); // 1 year from now			
			header('Content-Type: text/html');
			header('Content-Length: '. strlen(file_get_contents($cachefile)));
			header("Last-Modified: " . $last_modified);//gmdate('D, d M Y H:i:s', $last_modified).' GMT');
			header('Cache-Control: max-age=' . $cachetime);	
			header("ETag:" . $iETag);			
			include($cachefile);
			exit();
		}
	//}
}

function end_cache($cachefile) {
	$fp = fopen($cachefile, 'w');
	fwrite($fp, ob_get_contents());
	fclose($fp);
	ob_end_flush();
}

function is_valid_controller($controller) {
	$suffix = get_controller_suffix();
	$return = true;
	if (!file_exists(APP_PATH . "controllers/{$controller}$suffix" . EXT)) {
		$return = false;
	}
	return $return;
}
?>