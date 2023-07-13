<?php
class Loader
{
	protected $controller_object;
	public static $scriptstyle_to_load;
	public static $script_code;
	public static $script_top_code;
	public static $script_file;
	public static $script_to_include;

	public function __construct($controller_object)
	{
		$this->controller_object = $controller_object;
	}
	
	public function sys_library($class, $parameters = null, $object_name = null)
	{
		$prefix = "WG_";
		$class_name = $prefix.$class;
		if (file_exists(SYS_PATH . "libraries/$class" . EXT))
		{
			require_once SYS_PATH . "libraries/$class" . EXT;
			$object = $class;
			// is $object_name has value?
			// let the value of the $object_name be the instance of the class
			if ($object_name != null)
			{
				$object = $object_name;
			}
			if(is_array($parameters))
			{
				$this->controller_object->$object = new $class_name($parameters);
			}
			else
			{
				$this->controller_object->$object = new $class_name();
			}
		}
	}

	/**
	 * Includes an application class to the page. Classes are from applications/libraries
	 *
	 * @param string $class
	 * @param array $parameters
	 * @param string $object_name
	 * @param bool $initialize
	 */
	public function app_library($class, $parameters = null, $object_name = null)
	{
		$class = ucfirst($class);
		if (file_exists(APP_PATH . "libraries/$class" . EXT))
		{
			require_once APP_PATH . "libraries/$class" . EXT;
			$object = $class;

			// is $object_name has value?
			// let the value of the $object_name be the instance of the class
			if ($object_name != null)
			{
				$object = $object_name;
			}
			$object = strtolower($object);

			if(is_array($parameters))
			{
				$this->controller_object->$object = new $class($parameters);
			}
			else
			{
				$this->controller_object->$object = new $class();
			}
		}
	}

	/**
	 * Include application library without initializing
	 *
	 * @param mixed $class
	 */
	public static function appLibrary($class)
	{
		if (is_array($class))
		{
			foreach ($class AS $c)
			{
				$class = $c;
				if (!file_exists(APP_PATH . "libraries/$c" . EXT))
				{
					exit("<b>$c" . EXT . "</b> not found in " . APP_PATH . "libraries/");
				}
				require_once APP_PATH . "libraries/$c" . EXT;
			}
		}
		else
		{
			if (!file_exists(APP_PATH . "libraries/$class" . EXT))
			{
				exit("<b>$class" . EXT . "</b> not found in " . APP_PATH . "libraries/");
			}
			require_once APP_PATH . "libraries/$class" . EXT;
		}
	}

	/**
	 * Include system library without initializing
	 *
	 * @param mixed $class
	 */
	public static function sysLibrary($class)
	{
		if (is_array($class))
		{
			foreach ($class as $c)
			{
				$class = strtolower($c);
				if (!file_exists(SYS_PATH . "libraries/$c" . EXT))
				{
					exit("<b>$c" . EXT . "</b> not found in " . SYS_PATH . "libraries/");
				}
				include_once SYS_PATH . "libraries/$c" . EXT;
			}
		}
		else
		{
			//$class = ucfirst($class);
			$class = strtolower($class);
			if (!file_exists(SYS_PATH . "libraries/$class" . EXT))
			{
				exit("<b>$class" . EXT . "</b> not found in " . SYS_PATH . "libraries/");
			}
			include_once SYS_PATH . "libraries/$class" . EXT;
		}
	}

	/**
	 * Include webgine file
	 *
	 * @param string $file
	 */
	public static function webgine($file)
	{
		if (!file_exists(WEBGINE_PATH . "$file" . EXT))
		{
			exit("<b>$file" . EXT . "</b> not found in " . WEBGINE_PATH);		
		}
		require_once WEBGINE_PATH . "$file" . EXT;
	}

	/**
	 * Include config file located in <application folder>/config/
	 *
	 * @param string $file
	 */
	public static function appConfig($file)
	{
		if (!file_exists(APP_PATH . "config/$file" . EXT))
		{
			exit("<b>$file" . EXT . "</b> not found in " . APP_PATH . "config/");
		}
		require_once APP_PATH . "config/$file" . EXT;
	}
	
	/**
	 * Include controller located in application's controller folder
	 *
	 * @param string $file
	 */
	public static function appController($file)
	{
		$suffix = get_controller_suffix();
		if (!file_exists(APP_PATH . "controllers/{$file}$suffix" . EXT))
		{
			display_error();
		}
		require_once APP_PATH . "controllers/{$file}$suffix" . EXT;
	}

	/**
	 * Include controller located in application's controller folder and initialize
	 *
	 * @param string $file
	 */	
	public function controller($file, $object = null)
	{
		$suffix = get_controller_suffix();
		if (!file_exists(APP_PATH . "controllers/{$file}$suffix" . EXT))
		{
			exit("<b>{$file}$suffix" . EXT . "</b> not found in " . APP_PATH . "controllers/");
		}
		require_once APP_PATH . "controllers/{$file}$suffix" . EXT;

		$controller = "{$file}$suffix";
		if ($object != null)
		{
			$file = $object;
		}
		$this->controller_object->$file = new $controller();
	}

	/**
	 * Include script located in <system folder>/js/
	 *
	 * @param string $file
	 */
	public static function sysScript($file) {
		//$extention = '.js';
		$url = BASE_FOLDER . SYS_FOLDER . "/js/" . $file . $extention;
		self::$scriptstyle_to_load .= "<script type='text/javascript' src='" . $url . "'></script>|";
	}

	public static function appScript($file) {
		self::$script_file[] = BASE_FOLDER . APP_FOLDER . '/scripts/' . $file;
	}

	public static function cache($file) {
		self::$script_file[] = BASE_FOLDER . 'cache/' . $file;
	}

	/**
	 * Include css stylesheet
	 *
	 * @param string $file
	 */	
	public static function appStyle($file) {
		
		self::$scriptstyle_to_load .= '<link rel="stylesheet" href="'. BASE_FOLDER . 'themes/' . THEME . '/' . $file . '" />|';
	}
	
	public static function appUtilities() {
		Loader::appScript('generic.js');
		Loader::appScript('init.js');
		Loader::appStyle('assets/generic.css');
		Loader::sysLibrary('class_tool');
		Loader::sysLibrary('class_validate');		
	}

	/**
	 * Include helper located in <system folder>/helpers/
	 *
	 * @param mixed $file
	 */
	public static function helper($file) {
		if (is_array($file)) {
			foreach ($file as $f) {
				if (!file_exists(SYS_PATH . "helpers/{$f}_helper" . EXT)) {
					exit("<b>{$f}_helper" . EXT . "</b> not found in " . SYS_PATH);		
				}
				require_once SYS_PATH . "helpers/{$f}_helper" . EXT;
			}
		} else {
			if (!file_exists(SYS_PATH . "helpers/{$file}_helper" . EXT)) {
				exit("<b>{$file}_helper" . EXT . "</b> not found in " . SYS_PATH);		
			}
			require_once SYS_PATH . "helpers/{$file}_helper" . EXT;
		}
	}

	/**
	* Use this to load scripts and styles within the <head> tag
	*
	*/
	public static function get() {
	
		// from sysScript() and appStyle()
		ob_start();
		$includes = explode('|', self::$scriptstyle_to_load);
		if (is_array($includes)) {
			array_pop($includes);
			$incs = array_unique($includes);
			foreach ($incs as $include) {
				echo $include;
			}
		}
		
		// from write_top_script($code)
		echo '<script type="text/javascript">'. self::$script_top_code .'</script>';
				
		// from appScript() and cache()
		$script_files = array_unique(self::$script_file);
		foreach ($script_files as $script_file) {
			echo '<script type="text/javascript" src="'. $script_file .'"></script>';
		}
		
		// from write_script($code)
		/*echo '<script type="text/javascript">'. self::$script_code .'</script>';*/
		
		// from includeScript()		
		echo '<script type="text/javascript">';		
		foreach (self::$script_to_include as $script_to_include) {						
			include ($script_to_include);			
		}		
		$content = ob_get_contents();
		ob_end_clean();
		include 'application/libraries/jsmin.php';
		$content = JSMin::minify($content);
		echo $content;
		echo '</script>';
	}
	
	public static function getJsCache() {
		$dir = 'cache';
		$generic_file = 'generic.js';
    	echo '<script type="text/javascript" src="'. BASE_FOLDER . $dir . '/' . $generic_file .'"></script>';
	}
	
	public static function includeScript($file) {
		self::$script_to_include[] = APP_FOLDER . "/scripts/" . $file;
	}

	public static function not($value, $type)
	{
		switch($type)
		{
			case 'css':
				$to_be_removed = '<link rel="stylesheet" href="'. BASE_FOLDER .'css/' . $value . '" />';
			break;
			case 'script':
				$to_be_removed = "<script type='text/javascript' src='" . $value . "'></script>";
		}
		$includes = explode('|', self::$scriptstyle_to_load);
		
		if (is_array($includes))
		{
			array_pop($includes);
			$incs = array_unique($includes);
			$key = array_search($to_be_removed, $incs); 
			unset($incs[$key]);
			self::$scriptstyle_to_load = implode('|', $incs);
		}
	}

	public static function notScript($file)
	{
		$value = APP_FOLDER . '/scripts/' . $file;
		//self::not($value, 'script');
		$files = array_unique(self::$script_file);
		if (is_array($files)) {
			$incs = array_unique($files);
			$key = array_search($value, $incs);
			unset($incs[$key]);
			self::$script_file = array();
			self::$script_file = $incs;
		}
	}

	public static function notStyle($value)
	{
		self::not($value, 'css');
	}
}

?>