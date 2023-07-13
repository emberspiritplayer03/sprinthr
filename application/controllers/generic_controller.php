<?php
class Generic_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appStyle('style.css');
	}

	function index()
	{			
		$this->view->setTemplate('template.php');
		$this->view->render('generic/index.php');
	}
}
?>