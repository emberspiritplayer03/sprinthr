<?php
class About_Us_Controller extends Controller
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
		$this->var['page_title']= 'About Us';
		$this->view->render('pages/front/about_us.php',$this->var);
	}
}
?>