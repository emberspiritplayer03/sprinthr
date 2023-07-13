<?php
class Benchmark_Controller extends Controller
{
	function __construct() {
		parent::__construct();
	}
	
	function index() {
		//$file = $_FILES['timesheet']['tmp_name'];
		$file = BASE_PATH . 'files/files/import_schedule_by_employee_id.xlsx';
		$g = new G_Schedule_Import($file);		
		$g->importEmployeesInSchedule(15);

//		$tax_table = Tax_Table_Factory::get(Tax_Table::SEMI_MONTHLY);		
//		$tax = new Tax_Calculator;
//		$tax->setTaxTable($tax_table);
//		$tax->setTaxableIncome(6266.76);
//		$tax->setNumberOfDependent(0);
//		echo $tax->compute();
		
		$p = new Payslip_Hour;
		$p->setRegularNightShift(1);
//		$p->setRestdayNightShift(9);
//		$p->setHolidaySpecialNightShift(9);		
//		$p->setHolidaySpecialRestdayNightShift(2);
//		$p->setHolidayLegalNightShift(9);
//		$p->setHolidayLegalRestdayNightShift(2);
		$x = new Payslip_Percentage_Rate;
		$x->setNightShiftDiff(110); // 10%
		$c = new Payslip_Amount_Calculator;
		$c->setPayslipHour($p);
		$c->setPayslipRate($x);
		$c->setSalaryPerDayAmount(250);
		$c->setSalaryPerHourAmount(31.25);
		$c->computeRegularNightShift();
//		$c->computeRestDayNightShift();
//		$c->computeHolidaySpecialNightShift();
//		$c->computeHolidaySpecialRestDayNightShift();
//		$c->computeHolidayLegalNightShift();
//		$c->computeHolidayLegalRestDayNightShift();			
	}
	
	function testUrlSubFolder()
	{
		echo payroll_url('test/test');
	}
	
	function timesheet() {
		//$file = BASE_PATH . 'files/files/attendance_im_digital.xls';
		//echo $ext = end(explode('.',$file));
//		//$time = new Timesheet_Import($file);
//		//$return = $time->import();
//		$time = new G_Timesheet_Import_IM($file);
//		$return = $time->import();
	}
	
	function read_large_excel_data() {
		$this->view->render('benchmark/read_large_excel_data.php', $this->var);
	}
	
	function export_timesheet_to_excel() {
		$this->var['employees'] = G_Employee_Finder::findAllActive();	
		$this->view->render('benchmark/timesheet_to_excel.php', $this->var);
	}
	
	function add_attendance() {
		$day = 20;
		//for ($day = 6; $day <= 20; $day++) {
			if ($day != 7 || $day != 8 || $day != 14 || $day != 15) {
				for ($i = 49; $i <= 5142; $i++) {
					$e = Employee_Factory::get($i);
					// 16 to 20
					$date = '2012-07-'. $day;
					$time_in = '09:00:00';
					$time_out = '18:00:00';
		//			$overtime_in = '18:00:00';
		//			$overtime_out = '20:30:00';
					G_Attendance_Helper::recordTimecard($e, $date, $time_in, $time_out, $overtime_in, $overtime_out);		
				}	
			}
		//}

	}
	
	function add_employees() {
		for ($i = 6001; $i <= 6700; $i++) {
			$e = new G_Employee;
			$md5 = md5($i);
			$code = substr($md5, 0, 10);
			$e->setEmployeeCode($code);
			$e->setFirstname('Jose'. $i);
			$e->setLastname('Rizal'. $i);
			$e->setHiredDate(date("Y-m-d"));
			$employee_id = $e->save();
			
			$e = Employee_Factory::get($employee_id);
			$hash = Utilities::createHash($employee_id);
			$e->addHash($hash);
			$p = G_Job_Finder::findById(9);
			$p->saveToEmployee($e, date("Y-m-d") );
			
			$c = G_Company_Structure_Finder::findById(1);
			$c->addEmployee($e);
			
			$c = G_Company_Structure_Finder::findById(54);
			$c->addEmployeeToSubdivision($e,date("Y-m-d"));
				
			$b = G_Company_Branch_Finder::findById(1);
			$b->addEmployee($e, date("Y-m-d"));
			
			$status = G_Job_Employment_Status_Finder::findById(3);
			$employee_job = G_Employee_Job_History_Finder::findCurrentJob($e);
			$employee_job->setEmploymentStatus($status);
			$employee_job->save();
			
			$salary = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($e);	
			if($salary) {
				$salary->setEmployeeId($employee_id);
				$salary->setJobSalaryRateId(1);
				$salary->setBasicSalary(10000);
				$salary->setType('monthly_rate');
				$salary->setPayPeriodId(1);
				$salary->setStartDate(date('Y-m-d'));
				$salary->save();
			}else {
				$employee_salary = new G_Employee_Basic_Salary_History;	
				$employee_salary->setId($salary->id);
				$employee_salary->setEmployeeId($employee_id);
				$employee_salary->setJobSalaryRateId(1);
				$employee_salary->setType('monthly_rate');
				$employee_salary->setBasicSalary(10000);
				$employee_salary->setPayPeriodId(1);
				$employee_salary->setStartDate(date('Y-m-d'));
				$employee_salary->save();
			}			
		}		
	}
	
	function convertArrayToXml()
	{
		//echo "<pre>";
		//print_r($GLOBALS['hr']['requirements']);
		//foreach($GLOBALS['hr']['requirements'] as $key =>$value) {
			//$requirements[Tools::friendlyFormName($key)] = '';
		//}

	
		// requirements
		//CONVERT ARRAY TO XML
		//header("Content-Type:text/xml");
		//$xml = new Xml;
		//$ctr=1;
		//foreach($requirements as $key=>$val) {
		//		$var = 'req_'.$ctr;
		//		$ob->$var = $key;
		//		$ctr++;			
		//
		//print_r($ob);
		//$xml->setNode('Default_Requirements');
		//$xmlObj =  $xml->toXml($ob);
		//print_r($xmlObj);
	
	
		//CONVERT XML TO ARRAY
		
		$file = BASE_FOLDER. 'files/xml/requirements.xml';
		
		if(Tools::isFileExist($file)==true) {
			echo "file exist";
		}else {
			echo "no file exist";	
		}
		
		$xmlUrl = $_SERVER['DOCUMENT_ROOT'].BASE_FOLDER. 'files/xml/requirements.xml';
		$xmlStr = file_get_contents($xmlUrl);
		$xmlStr = simplexml_load_string($xmlStr);

		$xml2 = new Xml;
		$arrXml = $xml2->objectsIntoArray($xmlStr);
		print_r($arrXml);
		
	}
	
	
	function default_requirements()
	{
		$s = 'a:4:{s:20:"required_2x2_picture";s:0:"";s:7:"medical";s:0:"";s:3:"sss";s:0:"";s:3:"tin";s:0:"";}';
		$u = unserialize($s);
		echo "<pre>";
		print_r($u);
		
		$requirements = Requirements::getDefaultRequirements();	
		echo "<pre>";
		print_r($requirements);
	}
	
	function user_access()
	{
		
		
		$employee_id=1;
		$module = 'compensation';
		$action = 'Add';
		
		Utilities::verifyAccessRights($employee_id,$module,$action);
			
	}
	
	function email_buffer_test() {
		$r 			= G_Employee_Finder::findById(2); //requestor
		$department	= G_Employee_Subdivision_History_Finder::findEmployeeCurrentDepartment(2);
		$position	= G_Employee_Job_History_Finder::findCurrentJob($r);
		
		$approvers  = G_Employee_Finder::findById(2); //approvers
		$contact	= G_Employee_Contact_Details_Finder::findByEmployeeId(2);
		
		$ot['date_applied']	= date('m/d/Y g:f a',time());
		$ot['date_of_ot']	= date('m/d/Y g:f a',time());
		$ot['time_from']	= date('g:i a',time());
		$ot['time_to']		= date('g:i a',time());
		
		$ot['url_approve']		= url('request/approve');
		$ot['url_disapprove']	= url('request/approve');
		
		$emp['name'] 		= $r->getFullName();
		$emp['department'] 	= $department->getName();
		$emp['position']	= $position->getName();
		
		
		$appr['name']	= $approvers->getFullName();
		$appr['email']	= (!empty($contact) ? $contact->getWorkEmail() : ''); 
		
		
	}
	
	function get_all_unsend_emails() {
		$email = G_Email_Buffer_Finder::findAllEmailsNotSent();
		Tools::showArray($email);		
	}
	
	function send_approver_notification() {
		$era = G_Employee_Request_Approver_Finder::findById(1);
		Email_Templates::sendApproverRequestNotification($era);	
	}
	
	function send_approver_notification_byposition() {
		$era = G_Employee_Request_Approver_Finder::findById(3);
		Email_Templates::sendApproverByPositionRequestNotification($era);
	}
	
	function checkIfAlreadyApproved() {
		echo G_Employee_Request_Approver_Helper::verifyRequestIfAlreadyApproved(2);
	}
	
	
	function check_approver_status() {
		$approver_status = G_Employee_Request_Approver_Helper::validate_approver_status(1,2633);
	}
	
	public static function show_employee_specific_schedule() {
		
		//$employee  = G_Employee_Finder::findById(Utilities::decrypt($_SESSION['sprint_hr']['employee_id']));
		$employee  		= G_Employee_Finder::findById(4);
		$start_date 	= '2012-10-10';
		if($employee) {
			$a = G_Attendance_Finder::findByEmployeeAndDate($employee, $start_date);
			if($a) {
				$t = $a->getTimesheet();
				Tools::showArray($t);
			} else {
				echo 'No schedule found!';	
			}
		}	
	}
	
	public static function show_employee_all_schedule() {
		
		$start 	= '2012-08-01';
		$end	= '2012-10-31';
		//$employee  = G_Employee_Finder::findById(Utilities::decrypt($_SESSION['sprint_hr']['employee_id']));
		$employee  = G_Employee_Finder::findById(4);
		if($employee) {
			$a = G_Attendance_Finder::findByEmployeeAndPeriod($employee,$start,$end);
			Tools::showArray($a);
		}	
	}
	
	public static function get_employee_currently_log() {
		$employee = G_Employee_Finder::findById(4);
		Tools::showArray($employee);	
	}
}
?>