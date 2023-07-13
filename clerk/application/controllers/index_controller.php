<?php
class Index_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		Loader::appStyle('style.css');
		
		$this->eid              = Utilities::encrypt(1);
		$this->default_method   = 'index';					
		
		$this->var['employee'] = 'selected';
		$this->var['eid']      = $this->eid;	
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];	
	}

	function index()
	{
		$this->dashboard();		
	}
	
	function clerk()
	{
		$this->var['page_title'] = 'Clerk';
		$this->var['token'] = Utilities::createFormToken();
		Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();

	
		$company_structure_id = $this->company_structure_id;
		

		$this->view->setTemplate('template_clerk.php');
		$this->view->render('dashboard/index.php',$this->var);
	}
	
	function dashboard()
	{		
		$company_structure_id = $_SESSION['hr']['company_structure_id'];
		$this->var['dashboard']  = 'selected';				
		$this->var['page_title'] = 'Dashboard';
		$this->var['token']      = Utilities::createFormToken();		
		$this->view->setTemplate('template_clerk.php');
		$this->view->render('dashboard/index.php',$this->var);
	}
	
	function overtime()
	{		
		$company_structure_id = $_SESSION['hr']['company_structure_id'];
		$this->var['overtime']   = 'selected';				
		$this->var['page_title'] = 'Overtime';
		$this->var['token']      = Utilities::createFormToken();		
		$this->view->setTemplate('template_clerk.php');
		$this->view->render('dashboard/index.php',$this->var);
	}

	
	
	
}
?>