<?php
class Payroll_Register_Controller extends Controller
{
	function __construct()
	{
		$this->login();
		parent::__construct();		
		Loader::appMainUtilities();
		Loader::appStyle('style.css');
		$this->var['payroll_register'] = 'selected';
	}
	
	function index() {
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		
		$this->var['page_title'] = 'Payroll Register';
		//$this->var['action'] = url('attendance/manage');
		$this->var['periods'] = G_Payslip_Helper::getPeriods();
		$this->view->setTemplate('template.php');
		$this->view->render('payroll_register/index.php',$this->var);		
	}
	
	function generation() {
		Jquery::loadMainTipsy();
		
		$this->var['page_title'] = 'Payroll Generation';
		$this->var['periods'] = G_Payslip_Helper::getPeriods();
		$this->view->setTemplate('template.php');
		$this->view->render('payroll_register/generation.php',$this->var);		
	}
	
	function history() {
		Jquery::loadMainTipsy();
		
		$this->var['page_title'] = 'Payroll History';
		$this->var['periods'] = G_Payslip_Helper::getPeriods();
		$this->view->setTemplate('template.php');
		$this->view->render('payroll_register/history.php',$this->var);		
	}
}
?>