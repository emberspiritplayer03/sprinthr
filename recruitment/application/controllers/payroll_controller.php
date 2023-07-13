<?php
class Payroll_Controller extends Controller
{
	function __construct()
	{
		$this->login();
		parent::__construct();		
		Loader::appMainUtilities();			
	}

	function download_payroll_register() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$this->var['from'] = $from = $_GET['from'];
		$this->var['to'] = $to = $_GET['to'];
		
		if (strtotime($from) && strtotime($to)) {
			//$this->var['employees'] = $employees = G_Employee_Finder::findAllActiveByDate($from);
			$this->var['employees'] = $employees = G_Employee_Finder::findByPayslipPeriod($from, $to);
			$payslips = G_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
			$this->var['payslips'] = $payslips;
			$this->var['total_employees'] = count($employees);
		}
		$this->view->render('payroll/download_payroll_register.php', $this->var);	
	}
	
}
?>