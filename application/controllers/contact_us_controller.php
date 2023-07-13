<?php
class Contact_Us_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appStyle('front.css');			
		Loader::appStyle('style_website.css');			
	}

	function index()
	{
		Loader::appScript("jquery-1.4.2.min.js");
		Loader::appScript("contact_us.js");
		Jquery::loadInlineValidation();
		Jquery::loadJqueryFormSubmit();
		$this->create_global_token();
		$this->view->setTemplate('template_fullwidth.php');
		$this->var['page_title']= 'Contact Us';
		$this->var['body_class']= 'body_contact_us';
		$this->view->render('pages/front/contact_us.php',$this->var);
	}
}
?>