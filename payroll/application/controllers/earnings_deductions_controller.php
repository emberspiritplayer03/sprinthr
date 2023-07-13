<?php
class Earnings_Deductions_Controller extends Controller
{
	function __construct()
	{
		$this->login();
		parent::__construct();		
		Loader::appMainUtilities();
		Loader::appStyle('style.css');
		$this->var['earnings_deductions'] = 'selected';
	}
	
	function index() {
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		
		$this->var['page_title'] = 'Earnings / Deductions';
		//$this->var['action'] = url('attendance/manage');
		$this->var['periods'] = G_Payslip_Helper::getPeriods();
		$this->view->setTemplate('template.php');
		$this->view->render('earnings_deductions/index.php',$this->var);		
	}
	
	function earnings() {
		Jquery::loadMainTipsy();
		
		$this->var['page_title'] = 'Earnings';
		$this->var['periods'] = G_Payslip_Helper::getPeriods();
		$this->view->setTemplate('template.php');
		$this->view->render('earnings_deductions/earnings.php',$this->var);		
	}
	
	function loans() {
		Jquery::loadMainTipsy();
		
		$this->var['page_title'] = 'Loans';
		$this->var['periods'] = G_Payslip_Helper::getPeriods();
		$this->view->setTemplate('template.php');
		$this->view->render('earnings_deductions/loans.php',$this->var);		
	}
	
	function other_deductions() {
		Jquery::loadMainTipsy();
		
		$this->var['page_title'] = 'Other Deductions';
		$this->var['periods'] = G_Payslip_Helper::getPeriods();
		$this->view->setTemplate('template.php');
		$this->view->render('earnings_deductions/other_deductions.php',$this->var);		
	}
	
	function government_deductions() {
		Jquery::loadMainTipsy();
		
		$this->var['page_title'] = 'Government Deductions';
		$this->var['periods'] = G_Payslip_Helper::getPeriods();
		$this->view->setTemplate('template.php');
		$this->view->render('earnings_deductions/government_deductions.php',$this->var);		
	}
}
?>