<?php
class Reminders_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->login();
		Loader::appMainScript('employee.js');

		Loader::appStyle('style.css');
		$this->var['reminders'] = 'selected';

		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];

	}

	function index()
	{
		$this->var['page_title'] = 'Reminders';
		$this->var['page_subtitle'] = '<span>Manage your Reminders</span>';
		$this->view->setTemplate('template_reminders.php');
		$this->view->render('reminders/index.php',$this->var);
	}
	

	
}
?>