<?php
class Payslip_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appMainScript('payslip_base.js');
		Loader::appMainScript('payslip.js');			
		Loader::appMainUtilities();		
		Loader::appStyle('style.css');
		$this->var['payroll'] = 'selected';			
		
		Utilities::checkModulePackageAccess('attendance','payroll');	
	}

	function index() {		
		$date 		  = Tools::getGmtDate('Y-m-d');
		$current_year = date("Y");
		$cycle		  = G_Salary_Cycle_Finder::findDefault();
		$current 	  = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
		$payout_date  = Tools::getPayoutDate($date, $cycle->getCutOffs(), $cycle->getPayoutDays());
		G_Cutoff_Period_Manager::savePeriod($current_year,$current['start'], $current['end'], G_Salary_Cycle::TYPE_SEMI_MONTHLY, $payout_date);				
					
		$this->var['page_title'] = 'Manage Payslip';
		$this->var['periods'] = G_Payslip_Helper::getPeriods();
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('payslip/index.php', $this->var);	
	}
	
	function manage() {
		Jquery::loadMainTipsy();	
		Jquery::loadMainInlineValidation2();
		
		$this->var['page_title'] = 'Manage Payslip';
		$this->var['from'] = $from = $_GET['from'];
		$this->var['to'] = $to = $_GET['to'];
		$this->var['salt'] = md5($from .'-'. $to);
		
		$this->var['is_generated'] = false;
		if (G_Payslip_Helper::countPeriod($from, $to) > 0) {
			$this->var['is_generated'] = true;
			$this->var['employees'] = G_Employee_Finder::findByPayslipPeriod($from, $to);
		}
		$this->var['payout_date'] = G_Payslip_Helper::getPeriodPayoutDate($from, $to);
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('payslip/manage.php', $this->var);	
	}
	
	function show_payslip() {		
		Jquery::loadMainTipsy();	
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainInlineValidation2();
		$this->var['encrypted_employee_id'] = $_GET['employee_id'];	
		$employee_id = $this->var['employee_id'] = Utilities::decrypt($_GET['employee_id']);
		$from = $this->var['from'] = $_GET['from'];
		$to = $this->var['to'] = $_GET['to'];		
		//$e = Employee_Factory::get($employee_id);
		$e = G_Employee_Finder::findByIdBothArchiveAndNot(Utilities::decrypt($_GET['employee_id']));
		
		$this->var['page_title'] =  "Payslip";
		$this->var['module_title'] =  ': <b class="mplynm">' .$e->getName(). '</b>';
		//$this->var['module_title'] =  ': <b class="mplynm">' .$e->getName(). '</b>' . "<a class='gray_button title_back_button' href='" . url('payslip/manage?from='. $from .'&to='. $to). "'><i></i>Back to List</a>";"Payslip: ". '<b class="mplynm">' .$e->getName(). '</b>' . "<a class='gray_button title_back_button' href='" . url('payslip/manage?from='. $from .'&to='. $to). "'><i></i>Back to List</a>";
		$this->var['period'] = '<b>Period:</b>'. ' <span>'.Tools::dateFormat($from).'</span>'. ' <strong>&nbsp;to&nbsp;</strong> '. '<span>'.Tools::dateFormat($to).'</span>';
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		$ph = new G_Payslip_Helper($p);
		$this->var['total_earnings'] = $total_earnings = $ph->computeTotalEarnings();
		$this->var['total_deductions'] = $total_deductions = $ph->computeTotalDeductions();
		$this->var['net_pay'] = $total_earnings - $total_deductions;
		
		$this->var['earnings'] = $earnings = (array) $p->getEarnings();
		$this->var['other_earnings'] = $other_earnings = (array) $p->getOtherEarnings();
		$this->var['deductions'] = $deductions = (array) $p->getDeductions();
		$this->var['other_deductions'] = $other_deductions = (array) $p->getOtherDeductions();
		//$this->var['total_earnings'] = $p->computeTotalEarnings();
		//$this->var['total_deductions'] = $p->computeTotalDeductions();
		//$this->var['total_allowance'] = $p->computeTotalEarnings(Earning::EARNING_TYPE_ALLOWANCE);
		$this->var['net_pay'] = $p->getNetPay();
		$this->var['gross_pay'] = $p->getGrossPay();
		
		// Employee Navigation		
		//$previous_employee_id = $this->var['previous_employee_id'] = G_Employee_Helper::getPreviousIdAlphabetic($employee_id);
		//$next_employee_id = $this->var['next_employee_id'] = G_Employee_Helper::getNextIdAlphabetic($employee_id);
		//$this->var['previous_encrypted_employee_id'] = Utilities::encrypt($previous_employee_id);
		//$this->var['next_encrypted_employee_id'] = Utilities::encrypt($next_employee_id);		
		
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('payslip/show_payslip.php', $this->var);		
	}
	
	function export_payslip() {
		$employee_id = (int) $_GET['employee_id'];
		$from = $this->var['from'] = $_GET['from'];
		$to = $this->var['to'] = $_GET['to'];
		$e = $this->var['employee'] = Employee_Finder::findById($employee_id);
		$this->var['payout_date'] = Payslip_Helper::getPeriodPayoutDate($from, $to);
		
		Loader::appLibrary('pdf_writer');
		$this->var['payslip'] = $payslip = Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		$this->var['ps'] = new Payslip_Helper($payslip);
		$this->view->render('payslip/export_payslip.php', $this->var);
	}
	
	function add_earning() {
		$this->var['action'] = url('payslip/_add_earning');
		$this->var['employee_id'] = $_GET['employee_id'];
		$this->var['from'] = $_GET['from'];
		$this->var['to'] = $_GET['to'];
		
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('payslip/add_earning_form.php', $this->var);
	}
	
	function add_deduction() {
		$this->var['action'] = url('payslip/_add_deduction');
		$this->var['employee_id'] = $_GET['employee_id'];
		$this->var['from'] = $_GET['from'];
		$this->var['to'] = $_GET['to'];
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('payslip/add_deduction_form.php', $this->var);
	}
	
	function change_deduction_amount() {
		$this->var['action'] = url('payslip/_change_deduction_amount');
		$this->var['employee_id'] = $_GET['employee_id'];
		$this->var['from'] = $_GET['from'];
		$this->var['to'] = $_GET['to'];
		$this->var['amount'] = $_GET['amount'];
		$this->var['label'] = $_GET['label'];
		$this->var['variable'] = $_GET['variable'];
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('payslip/change_deduction_amount_form.php', $this->var);
	}
	
	function generate() {
		$from = $_POST['from'];
		$to = $_POST['to'];
		$salt = $_POST['salt'];
		if (!empty($from) && !empty($to)) {
			if (md5($from .'-'. $to) != $salt) {
				exit('Invalid Salt');
			}
			G_Payslip_Helper::updatePayslipsByPeriod($from, $to);
		}		
	}
	
	function download_payslips() {
		$from = $this->var['from'] = $_GET['from'];//'2012-07-06';
		$to = $this->var['to'] = $_GET['to'];//'2012-07-20';
		$c = G_Cutoff_Period_Finder::findByPeriod($from, $to);
		if ($c) {
			$payout_date = $c->getPayoutDate();	
		}
						
		$this->var['payout_date'] = date('M j, Y', strtotime($payout_date));
		$this->var['period'] = date('M j', strtotime($from)) .' to '. date('M j, Y', strtotime($to));	
//		$this->var['employees'] = $employees = G_Employee_Finder::findByPayslipPeriod($from, $to);
//		$this->var['filename'] = "Payslip_". $from ."_to_". $to .".xls";
//		$this->view->noTemplate();
		
		
		if (strtotime($from) && strtotime($to)) {
			if (G_Payslip_Helper::countPeriod($from, $to) == 0) {
				G_Payslip_Helper::updatePayslipsByPeriod($from, $to);
			}
			$this->var['employees'] = $employees = G_Employee_Finder::findByPayslipPeriod($from, $to);
			$payslips = G_Payslip_Helper::getAllPayslipsByPeriodGroupByEmployee($from, $to);
			$this->var['payslips'] = $payslips;
			$this->var['total_employees'] = count($employees);
			$this->var['filename'] = 'Payslips_'. $from .'_to_'. $to .'.xls';
		}
				
		$this->view->render('payslip/download_payslip.php', $this->var);		
	}
	
	function dialog_change_period_payout_date() {
		$this->var['from'] = $_GET['from'];
		$this->var['to'] = $_GET['to'];
		$this->var['current_payout_date'] = $_GET['current_payout_date'];
		$this->view->noTemplate();
		$this->view->render('payslip/dialog_change_period_payout_date_form.php', $this->var);
	}
	
	function _add_earning() {
		$employee_id = $this->var['employee_id'] = Utilities::decrypt($_POST['employee_id']);
		$from = $_POST['from'];
		$to = $_POST['to'];
		$earning_type = $_POST['earning_type'];
		$label = $_POST['label'];
		$amount = $_POST['amount'];
		
		$e = Employee_Factory::get($employee_id);
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		$ph = new G_Payslip_Helper($p);
		if (!$ph->getLabel($label)) {
			$ear[] = $ear_obj = new Earning($label, $amount, Earning::TAXABLE, (int) $earning_type);
			$p->addOtherEarnings($ear);
			$gross_pay = $ph->computeTotalEarnings();
			$p->setGrossPay($gross_pay);
			$net_pay = $gross_pay - $ph->computeTotalDeductions();
			$p->setNetPay($net_pay);
			$p->save();
			$return['added'] = true;
		} else {
			$return['added'] = false;
			$return['already_exist'] = true;
		}
		echo json_encode($return);	
	}	
	
	function _add_deduction() {
		$employee_id = $this->var['employee_id'] = Utilities::decrypt($_POST['employee_id']);
		$from = $_POST['from'];
		$to = $_POST['to'];
		$label = $_POST['label'];
		$amount = $_POST['amount'];
		$deduction_type = (int) $_POST['deduction_type'];
		
		$e = Employee_Factory::get($employee_id);
		
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		$ph = new G_Payslip_Helper($p);
		if (!$ph->getLabel($label)) {
			$d[] = new Deduction($label, $amount, $deduction_type);
			$p->addOtherDeductions($d);
			$gross_pay = $ph->computeTotalEarnings();
			$p->setGrossPay($gross_pay);
			$net_pay = $gross_pay - $ph->computeTotalDeductions();
			$p->setNetPay($net_pay);			
			$p->save();
			$return['added'] = true;
		} else {
			$return['added'] = false;
			$return['already_exist'] = true;
		}							
		echo json_encode($return);			
	}
	
	function _remove_deduction() {
		$employee_id = $this->var['employee_id'] = Utilities::decrypt($_POST['employee_id']);
		$from = $_POST['from'];
		$to = $_POST['to'];
		$label = $_POST['label'];
		$e = Employee_Factory::get($employee_id);
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		$ph = new G_Payslip_Helper($p);
		$p->removeOtherDeduction($label);
		
		$gross_pay = $ph->computeTotalEarnings();
		$p->setGrossPay($gross_pay);	
		
		$net_pay = $gross_pay - $ph->computeTotalDeductions();
		$p->setNetPay($net_pay);	
			
		$p->save();
	
		$return['removed'] = true;
		echo json_encode($return);
	}
	
	function _remove_earning() {
		$employee_id = $this->var['employee_id'] = Utilities::decrypt($_POST['employee_id']);
		$from = $_POST['from'];
		$to = $_POST['to'];
		$label = $_POST['label'];
		$e = Employee_Factory::get($employee_id);
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		$ph = new G_Payslip_Helper($p);
		$p->removeOtherEarning($label);

		$gross_pay = $ph->computeTotalEarnings();
		$p->setGrossPay($gross_pay);	
		
		$net_pay = $gross_pay - $ph->computeTotalDeductions();
		$p->setNetPay($net_pay);	
			
		$p->save();
	
		$return['removed'] = true;
		echo json_encode($return);
	}
	
	function _update_employee_payslip() {
		$id = $_GET['employee_id'];	
		$employee_id = Utilities::decrypt($id);		
		$e = G_Employee_Finder::findById($employee_id);
		if ($e) {
			$from = $_GET['from'];
			$to = $_GET['to'];
			
			$is_updated = G_Payslip_Helper::updatePayslip($e, $from, $to);
			$return['is_updated'] = true;
			$return['message'] = 'Payslip has been updated';
		} else {
			$return['is_updated'] = false;
			$return['message'] = "Payslip has not been updated. There's no employee found";
		}
		echo json_encode($return);
	}
	
	function _update_payslips() {
		$from = $_GET['from'];
		$to = $_GET['to'];
		
		G_Payslip_Helper::updatePayslipsByPeriod($from, $to);
		$return['is_updated'] = true;
		$return['message'] = 'Payslips has been updated';
		echo json_encode($return);
	}			
	
	function _change_deduction_amount() {
		$from = $_POST['from'];
		$to = $_POST['to'];
		$employee_id = $this->var['employee_id'] = Utilities::decrypt($_POST['employee_id']);
		$label = $_POST['label'];
		$variable = $_POST['variable'];
		$amount = (float) $_POST['amount'];		
		
		$e = Employee_Factory::get($employee_id);
		
		// Payables
		$pf = G_Payment_History_Finder::findByEmployeeAndPaymentNameAndDatePaid($e, $label, $to);
		if ($pf) {
			$pf->setAmountPaid($amount);
			$pf->save();
		}
				
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);
		$p->removeDeduction($variable);
		$obj_deduct = new Deduction($label, $amount);
										
		switch ($variable):
			case 'philhealth':									
				$obj_deduct->setVariable('philhealth');									
				$p->setPhilhealth($amount);
			break;
			case 'pagibig':
				$obj_deduct->setVariable('pagibig');		
				$p->setPagibig($amount);
			break;
			case 'sss':
				$obj_deduct->setVariable('sss');									
				$p->setSSS($amount);
			break;
		endswitch;
		
		$p->addDeductions($obj_deduct);	
		//$p->setWithheldTax($p->computeTotalDeductions(Deduction::DEDUCTION_TYPE_TAX));
		//$net_pay = $p->computeNetPay();
		//$p->setNetPay($net_pay);
				
		$p->save();
		
		$return['changed'] = true;
		echo json_encode($return);		
	}

	function payslip_preview()
	{
		ini_set("memory_limit", "999M");
		/*set_time_limit(999999999999999999999);
				
		//Employee
		$e['name'] 		        = 'Aala, Karen Grace';
		$e['employee_number']   = 'E10001';	
		//Pay Period
		$p['from']              = '2012-08-01';
		$p['to']			    = '2012-08-15';
		//Earnings
		$ea['basic_pay']	    = 5000.00;
		$ea['overtime']		    = 361.27;	
		//Deductions
		$de['sss']			    = 4.00;
		$de['philhealth']       = 9.00;
		$de['pagibig']          = 2296.00;
		$de['late']             = 11.94;
		$de['undertime']        = 0.00;
		$de['absent_amount']    = 0.00;
		$de['suspended_amount'] = 0.00;
		
		$pName = Generate_Payslip::payslip($e,$p,$ea,$de);
		if($pName){
			header('Location: http://gleent.internal/products/sprint/hr/files/payslip/payslip.pdf');
		}*/
		
		$period['from'] = $_GET['from'];
		$period['to']   = $_GET['to'];
		$eid 		    = $_GET['employee_id'];		
		
		$ei = G_Employee_Finder::findById(Utilities::decrypt($eid));
		$e  = G_Employee_Helper::findByEmployeeId($ei->getId());
		
		$leaves = G_Employee_Leave_Available_Helper::getEmployeeLeaveAvailable($ei);		
		$p	    = G_Payslip_Finder::findByEmployeeAndPeriod($ei, $period['from'], $period['to']);
		$ph     = new G_Payslip_Helper($p);		
		/*$earnings   = (array) $p->getEarnings();
		$deductions = (array) $p->getDeductions();*/
		
		$other_earnings = (array) $p->getOtherEarnings();
		$deductions     = (array) $p->getDeductions();	
			
		//exit;
		$pName    = Generate_Payslip::payslipCarbonized($e,$leaves,$ph,$period,$p);		
		$pdf_path = 'http://' . $_SERVER['SERVER_NAME'] . BASE_FOLDER . 'files/payslip/' . $pName;
		if($pName){
			header('Location: ' . $pdf_path);
		}
	}
	
	function swift_send($a,$e,$p)
	{
		//Loader::appLibrary('swiftmailer/lib/swift_required');		
		$msg .= 'Hi <b>' . $e['name'] . '</b>,<br><br>';
		$msg .= '<p>Attached is your payslip from <b>' . $p['from'] . '</b> to <b>' . $p['to'] . '</b></p>';
		$msg .= '<p>Kindly check</p>';
		$msg .= '<p>Thank you</p>';
		$msg .= '<br /><p><i>This is an auto email. Do not reply.</i></p>';
		
		$subject       = '[SprintHr]Payslip';
		//$email         = 'bryan.yobi@gmail.com'; 
		$email         = 'marlito.dungog@gleent.com';
		$smtp          = 'mail.krikel.com';
		$port          = 26;
		$username      = 'support@krikel.com';
		$password      = 'Gl33ntxyz';
		$from['email'] = 'support@krikel.com';	
		$from['title'] = 'Sprint Hr Admin';		
		$send 	       = Tools::sendMailSwiftMailer($subject,$email,$msg,$smtp,$port,$username,$password,$from,$a);
		return $send;
	}
	
	function email()
	{
		//Employee
		$e['name'] 		        = 'Aala, Karen Grace';
		$e['employee_number']   = 'E12324-22';	
		//Pay Period
		$p['from']              = '2012-08-01';
		$p['to']			    = '2012-08-15';
		//Earnings
		$ea['basic_pay']	    = 5000.00;
		$ea['overtime']		    = 361.27;	
		//Deductions
		$de['sss']			    = 4.00;
		$de['philhealth']       = 9.00;
		$de['pagibig']          = 2296.00;
		$de['late']             = 11.94;
		$de['undertime']        = 0.00;
		$de['absent_amount']    = 0.00;
		$de['suspended_amount'] = 0.00;
		
		$attachment             = Generate_Payslip::payslip($e,$p,$ea,$de);			
		$num_sent			    = $this->swift_send($attachment,$e,$p);
		
		if($num_sent > 0){
			$json['error'] = 0;
		}else{$json['error'] = 1;}
		
		echo json_encode($json);
		
	}
}
?>