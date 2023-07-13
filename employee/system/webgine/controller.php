<?php
/**
 * WGFramework Controller class
 *
 * Controls model, view and loader
 *
 * @package		WGFramework
 * @author		Webgroundz
 * @category	System
 */

class Controller extends Global_Controller
{
	/**
	 * Instance of View class
	 *
	 * @var object
	 */
	protected $view;

	/**
	 * Instance of Model class
	 *
	 * @var object
	 */
	protected $model;

	/**
	 * Instance of Loader class
	 *
	 * @var object
	 */
	protected $loader;

	/**
	 * Defined variable to use within Controller class
	 *
	 * @var array
	 */
	protected $var = array();

	/**
	 * Default method to be executed if no request method
	 *
	 * @var string
	 */
	public $default_method;

	/**
	 * The constructor
	 *
	 */
	function __construct()
	{
		session_start();
		parent::__construct();
		$this->view = new View;
		$this->model = new Model($this);
		$this->loader = new Loader($this);
	}

	
	/**
	 * Get the parameter value via $_GET
	 * 	
	 * @param string $var the key of the $_GET
	 * @return string
	 */
	public function getParam($var)
	{
		return $_GET[$var];
	}

	/**
	 * Get the post value via $_POST
	 *
	 * @param string $var the key of the $_POST
	 * @return string
	 */
	public function getPost($var)
	{
		return $_POST[$var];
	}
}
?>