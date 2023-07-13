<?php
class Benchmark_Leo_Controller extends Controller
{
	function __construct() {
		parent::__construct();
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
	}
	function index() {			
		$start 	= "16:38:00";
		$end	= "17:00:00";
		//echo round(abs($start - $end) / 60,2). " minute";
		$a = round(abs(strtotime($start) - strtotime($end)) / 60,2) / 60;
		echo $a . '<br/>';
		
		$undertime_hours = Tools::getHoursDifference($start, $end);
		echo $undertime_hours;
	}
	
	function import_employee() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		$file = BASE_PATH . 'files/import_employee_summit.xls';
		$e = new Employee_Import($file);
		$return = $e->import();
	}

	function import_ot() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		$file = BASE_PATH . 'files/files/import_overtime_summit.xlsx';
		//$time = new Timesheet_Import($file);
		$time = new G_Overtime_Import_Pending($file);
		$time->import();		
	}
	
	function getAllEmployeeByJobId() {
		$test = G_Employee_Job_History_Helper::getAllEmployeeIdByJobIdConcatString(16);
		Tools::showArray($test);
	}
	
	function getAllEmployeeByDepartmentId() {
		$test = G_Employee_Subdivision_History_Helper::getAllEmployeeIdByJobIdConcatString(16);
		Tools::showArray($test);
	}
	
	function show_serialize() {
		$user_access = array('dashboard'=>1,'employee_profile'=> array('compensation'=>0,'employment_status'=>1));
		$a = serialize($user_access);
		
		Tools::showArray(unserialize($a));
	}
	
	function verifyUserCredentials() {
		//Tools::showArray(unserialize('a:1:{s:2:"hr";a:2:{s:6:"parent";a:1:{s:9:"dashboard";s:1:"1";}s:9:"dashboard";a:3:{s:19:"general_information";s:1:"1";s:8:"employee";s:1:"0";s:11:"recruitment";s:1:"1";}}}'));	
		$rights = unserialize('a:1:{s:2:"hr";a:2:{s:6:"parent";a:1:{s:9:"dashboard";s:1:"1";}s:9:"dashboard";a:3:{s:19:"general_information";s:1:"1";s:8:"employee";s:1:"0";s:11:"recruitment";s:1:"1";}}}');
		echo $rights['hr']['parent']['dashboard'];	
		
	
	}
	
	function print_details() {
		$this->view->render('benchmark_leo/print_details.php');
	}
	
	function print_personal_details($employee_id) {
		$employee_id = 1;
		
		$this->load_employee_photo($employee_id);
		
		$this->var['details'] 	= $details = G_Employee_Finder::findById($employee_id);
		$this->var['field'] 	= G_Settings_Employee_Field_Finder::findByScreen('#personal_details');
		G_Employee_Dynamic_Field_Finder::findFieldNotUnderSettingsEmployeeField('#personal_details',$details);
		
		$this->var['title_personal_details'] = "Personal Details";
		$this->view->render('benchmark_leo/personal_details.php',$this->var);
	}
	
	function print_contact_details($employee_id) {
		$employee_id = 1;
		
		$this->var['details'] = $details  = G_Employee_Contact_Details_Finder::findByEmployeeId($employee_id);
		$this->load_employee_photo($employee_id);
		
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['field'] 			= G_Settings_Employee_Field_Finder::findByScreen('#contact_details');
		$this->var['employee_id'] 		= $employee_id;
		$this->var['title_contact_details'] = "Contact Details";
		
		$this->view->render('benchmark_leo/contact_details.php',$this->var);
	}
	
	 function print_emergency_contacts() {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$this->var['contacts'] 			= G_Employee_Emergency_Contact_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		$this->var['title_emergency_contacts'] = "Emergency Contacts";
		
		$this->view->render('benchmark_leo/emergency_contacts.php',$this->var);
	}
	
	function print_dependents($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$this->var['dependents'] 		= G_Employee_Dependent_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		$this->var['title_dependent'] 	= "Dependents";
		
		$this->view->render('benchmark_leo/dependents.php',$this->var);
	}
	
	function print_banks($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$this->var['banks']	 			= $e = G_Employee_Direct_Deposit_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		$this->var['title_dependent'] 	= "Dependents";
		
		$this->view->render('benchmark_leo/dependents.php',$this->var);
	}
	
	function print_employement_information($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$d = G_Employee_Helper::findByEmployeeId($employee_id);
		
		$branch 		= G_Company_Branch_Finder::findByCompanyStructureId($d['company_structure_id']);
		$department 	= G_Company_Structure_finder::findByCompanyBranchId($d['branch_id']);
		$job 			= G_Job_Finder::findByCompanyStructureId($d['company_structure_id']);
		$job_category 	= G_Eeo_Job_Category_finder::findByCompanyStructureId($d['company_structure_id']);
		
		$employee = G_Employee_Helper::findByEmployeeId($employee_id);
		$position =  G_Job_Finder::findById($d['job_id']);
		if($position_id!=0) {
			$total_status = G_Job_Employment_Status_Helper::countTotalRecordsByJobId($position);	
		}else {
			$total_status = 0;	
		}
		
		if($total_status>0){
			$status = G_Job_Employment_Status_Finder::findByJobId($position->getId());
			$status_type = 1; // status by position
		}else {
			$status = G_Settings_Employment_Status_Finder::findByCompanyStructureId($this->company_structure_id);
			$status_type =0; // default status
		}
		if($employee['employment_status']=='Terminated') {
			
			$memo = G_Employee_Memo_Finder::findByEmployeeId(Utilities::decrypt($_GET['employee_id']));	
			
			foreach($memo as $key=> $val) {
				if($val->title=='Terminated') {
					$this->var['terminated_memo'] = $val->memo;
				}
			}
		}
		
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		$this->var['status'] = $status;
		$this->var['status_type'] = $status_type;
		$this->var['employment_status'] = $employee['employment_status'];
		
		$this->var['branch'] 		= $branch;
		$this->var['d'] 			= $d;
		$this->var['department'] 	= $department;
		$this->var['job'] 			= $job;
		$this->var['job_category'] 	= $job_category;
		
		## Subdivision History ##
		$this->var['subdivision_history'] 	= $history = G_Employee_Subdivision_History_Finder::findByEmployeeId($employee_id);
		$this->var['department'] 			= $department = G_Company_Structure_Finder::findParentChildByBranchId($this->company_structure_id);
		
		## Job History ##
		
		$history 	= G_Employee_Job_History_Finder::findByEmployeeId($employee_id);
		$job 		= G_Job_Finder::findByCompanyStructureId($this->company_structure_id);
		$status 	= G_Settings_Employment_Status_Finder::findByCompanyStructureId($this->company_structure_id);
		
		$this->var['job'] = $job;
		$this->var['job_history'] = $history;
		$this->var['status'] = $status;
	
		$this->view->render('benchmark_leo/employment_status.php',$this->var);
	}
	
	function print_compensation($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$employee 			= G_Employee_Finder::findById($employee_id);
		$employee_salary 	= G_Employee_Basic_Salary_History_Finder::findCurrentSalary($employee);
	
		$employee_rate 			= G_Job_Salary_Rate_Finder::findById($employee_salary->job_salary_rate_id);
		$employee_pay_period 	= G_Settings_Pay_Period_Finder::findById($employee_salary->pay_period_id);
	
		## Compensation ##
		$pay_period = G_Settings_Pay_Period_Finder::findByCompanyStructureId($this->company_structure_id);
		$rate = G_Job_Salary_Rate_Finder::findByCompanyStructureId($this->company_structure_id);
		$this->var['employee_id'] 			= Utilities::encrypt($employee->id);
		$this->var['employee_salary'] 		= $employee_salary;
		$this->var['employee_rate'] 		= $employee_rate;
		$this->var['employee_pay_period'] 	= $employee_pay_period;
		$this->var['pay_period'] 			= $pay_period;
		$this->var['rate'] 					= $rate;
		
		## Compensation History ##
		$history = G_Employee_Basic_Salary_History_Finder::findByEmployeeId($employee_id);

		$this->var['compensation_history'] = $history;
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		
		$this->view->render('benchmark_leo/compensation.php',$this->var);
	}
	
	function print_contract($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$this->var['durations'] 		= G_Employee_Extend_Contract_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		$this->view->render('benchmark_leo/contract.php',$this->var);
	}
	
	function print_contribution($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$this->var['c']					= G_Employee_Contribution_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		$this->view->render('benchmark_leo/contribution.php',$this->var);
	}
	
	function print_performance($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$this->var['performance']  		= G_Employee_Performance_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		$this->view->render('benchmark_leo/performance.php',$this->var);
	}
	
	function print_training($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$this->var['training'] 			= G_Employee_Training_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		$this->view->render('benchmark_leo/training.php',$this->var);
	}
	
	function print_memo($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$this->var['memo'] 				= G_Employee_Memo_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		$this->view->render('benchmark_leo/memo_notes.php',$this->var);
	}
	
	function print_requirements($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		$e = G_Employee_Requirements_Finder::findByEmployeeId($employee_id);
		$data[] = unserialize($e->requirements);	
		$this->var['requirements'] = $data;
		
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		$this->view->render('benchmark_leo/requirements.php',$this->var);
	}
	
	function print_supervisor($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$this->var['subordinate'] 	= G_Employee_Supervisor_Finder::findByEmployeeId($employee_id);
		$this->var['supervisor'] 	= G_Employee_Supervisor_Finder::findBySupervisorId($employee_id);
		
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		$this->view->render('benchmark_leo/supervisor.php',$this->var);
	}
	
	function print_leave($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$availables = G_Employee_Leave_Available_Finder::findByEmployeeId($employee_id);
		$request 	= G_Employee_Leave_Request_Finder::findByEmployeeId($employee_id);
		$gcs 		= G_Company_Structure_Finder::findById($this->company_structure_id);
		$leaves 	= G_Leave_Finder::findByCompanyStructureId($gcs);

		$this->var['leaves'] = $leaves;
		$this->var['request'] = $request;
		$this->var['availables'] = $availables;
		
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		$this->view->render('benchmark_leo/leave.php',$this->var);
	}
	
	## DEDUCTION, not yet finish ##
	function print_deduction($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$this->var['loans'] 			= $loans = G_Employee_Loan_Helper::getAllLoansByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		//$this->view->render('benchmark_leo/leave.php',$this->var);
	}
	
	function print_work_experience($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$this->var['work_experience'] 	= G_Employee_Work_Experience_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		$this->view->render('benchmark_leo/work_experience.php',$this->var);
	}
	
	function print_education($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$this->var['education'] 		= G_Employee_Education_Finder::findByEmployeeId(Utilities::decrypt($employee_id));
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		$this->view->render('benchmark_leo/education.php',$this->var);
	}
	
	function print_skills($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$this->var['skills'] 			= G_Employee_Skills_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		$this->view->render('benchmark_leo/skills.php',$this->var);
	}
	
	function print_language($employee_id) {
		$employee_id = 1;
		$this->load_employee_photo($employee_id);
		
		$this->var['languages'] 		= G_Employee_Language_Finder::findByEmployeeId($employee_id);
		$this->var['employee_details'] 	= G_Employee_Helper::findByEmployeeId($employee_id);
		$this->var['employee_id'] 		= $employee_id;
		
		$this->view->render('benchmark_leo/language.php',$this->var);
	}
	
	function load_employee_photo($employee_id) {
		$employee = G_Employee_Finder::findById($employee_id);
		$file = PHOTO_FOLDER.$employee->getPhoto();
		if(Tools::isFileExist($file)==true && $employee->getPhoto()!='') {
			$this->var['filemtime'] = $file = md5($employee->getPhoto()).date("His");
		}else {
			$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';
		}
		
	}
	
	function date_diff() {
		$from 	= '2009-12-25';
		$to		= '2009-12-31';
		
		echo Tools::getDayDifference($from,$to);
	}
	
	function leaveUpdateAttendance() {
		$employee_id = 6;
		$date = '2013-01-01';
		$e = G_Employee_Finder::findById($employee_id);
		G_Attendance_Helper::generateAttendance($e,$date);
	}
	
	function checkExcess() {
		$scheduled_time_in = '08:00:00';
		$scheduled_time_out = '17:00:00';
		$overtime_in = '17:00:00';
		$overtime_out = '05:00:00';	
					
		$o = new G_Overtime_Calculator;
		$o->setScheduleIn($scheduled_time_in);
		$o->setScheduleOut($scheduled_time_out);
		$o->setOvertimeIn($overtime_in);
		$o->setOvertimeOut($overtime_out);
		//$ot_hours = $o->computeHours();
		//$ot_excess_hours = $o->computeExcessHours();
		$ot_nd = $o->computeNightDiff();
		//$ot_excess_nd = $o->computeExcessNightDiff();	
		
		echo '<br/><br/>';
		echo "ot_hours : {$ot_hours} <br/>";
		echo "ot_excess_hours : {$ot_excess_hours} <br/>";
		echo "ot_nd : {$ot_nd} <br/>";
		echo "ot_excess_nd : {$ot_excess_nd} <br/>";
		
		
		echo '<br/><br/>- Correct Results -<br/> ';
		echo "ot_hours : 8 <br/>";
		echo "ot_excess_hours : 4 <br/>";
		echo "ot_nd : 3 <br/>";
		echo "ot_excess_nd : 4 <br/>";
		
	}
	
	function test()
	{
		if(APPLICANT_EVENT_SEND_EMAIL == true){
			echo 'correct (false)';
		}
	}

	
}
?>