<?php
class Menu_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appStyle('style.css');
	}

	function index()
	{
		$this->view->setTemplate('login.php');
		$this->view->render('menu/index.php',$this->var);
		
	}
}

?>