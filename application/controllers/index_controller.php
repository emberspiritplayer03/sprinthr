<?php
class Index_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appStyle('style_website.css');			
	}

	function index()
	{
		Jquery::loadInlineValidation();
		$this->view->setTemplate('template_home.php');
		$this->var['page_title']= 'Company overview';
		$this->view->render('index/index.php',$this->var);
	}
}
?>