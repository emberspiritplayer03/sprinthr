<?php
class Sandbox_Controller extends Controller
{
	function __construct() {
		parent::__construct();
		ini_set("memory_limit", "999M");
	}
	
	function index() {
		echo 'it works!';
	}
	
	function read_excel() {
		$this->view->render('sandbox/sandbox1/read_excel.php', $this->var);
	}
}
?>