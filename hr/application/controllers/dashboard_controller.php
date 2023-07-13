<?php
class Dashboard_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appMainUtilities();

		$employee = G_Employee_Finder::findById(Utilities::decrypt($this->global_user_eid));
		if($employee) {
			$position = G_Employee_Job_History_Finder::findCurrentJob($employee);
			if($position){
				$this->h_job_position_id 	= Utilities::encrypt($position->getJobId());
			}
		}

		$this->h_employee_id   			= $this->global_user_eid; //employee
		$this->h_company_structure_id 	= $this->global_user_ecompany_structure_id;
		$this->c_date  					= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');

		$this->default_method   		= 'index';

		$this->var['h_employee_id']  	= $this->h_employee_id;
		$this->var['h_job_position_id'] = $this->h_job_position_id;
		$this->var['h_company_structure_id'] = $this->h_company_structure_id;

		Loader::appStyle('style.css');
		Loader::appMainScript('dashboard.js');
		Loader::appMainLibrary('FusionCharts');
		$this->var['dashboard'] = 'selected';
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];

	}

	function index()
	{
		$this->general_information();
	}

	function general_information()
	{
		$module_access 		= HR;
		$sub_module_access	= array(DASHBOARD=>"general_information");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt(h_employee_id),$module_access,$sub_module_access);

		Yui::loadMainDatatable();

		$this->var['page_title'] = 'Dashboard';
		$this->view->setTemplate('template.php');
		$this->view->render('dashboard/general_information/index.php',$this->var);

	}

	function _json_encode_attendance_list()
	{
		Utilities::ajaxRequest();
		$search = ($_GET['fieldname']  !='') ? "WHERE ". $_GET['fieldname']." like '". $_GET['search'] ."%'": '' ;


		if($_GET['date']!='undefined' && $_GET['date']!='') {

			$date = $_GET['date'];
		}else {

			$date = date("Y-m-d");
		}
		$sql = "SELECT * FROM g_fp_attendance_log a
				WHERE 	a.date = ". Model::safeSql($date) ."
				";
		$attendance = Model::runSql($sql,true);

		$count_total = $attendance;
		$total = count($attendance);
		$total_records =count($count_total);

		header("Content-Type: application/json");
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($attendance) . "}";
	}

	function recruitment_dashboard()
	{
		$module_access 		= HR;
		$sub_module_access	= array(DASHBOARD=>"recruitment");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt(h_employee_id),$module_access,$sub_module_access);

		Yui::loadMainDatatable();
		Jquery::loadMainTipsy();
		$year = date("Y");
		$month = '';

		$this->var['total_applicant'] = $applicant = G_Applicant_Helper::getTotalApplicantByMonth($year,$month);

		$this->var['page_title'] = 'Dashboard';
		$this->view->setTemplate('template.php');
		$this->view->render('dashboard/recruitment/index.php',$this->var);
	}

	function employee_dashboard()
	{
        redirect('schedule');

        // NOT IMPLEMENTED YET
		$module_access 		= HR;
		$sub_module_access	= array(DASHBOARD=>"employee");
		G_Access_Rights_Helper::verifyUserAccessRights(Utilities::decrypt(h_employee_id),$module_access,$sub_module_access);

		Yui::loadMainDatatable();
		$year = date("Y");
		$month = '';

		$this->var['total_employee'] = G_Employee_Helper::getTotalEmployeeByMonth($year,$month);
		$total_hired = G_Employee_Helper::getTotalHiredByYearAndMonth();
		$total_terminated = G_Employee_Helper::getTotalTerminatedByYearAndMonth();

		$this->var['total_employee'] = array_merge((array)$total_hired,(array)$total_terminated);

 		$this->var['headcount_by_department'] = G_Employee_Helper::getHeadcountByDepartment();
		$this->var['total_salary_by_range'] = G_Employee_Helper::getTotalSalaryByRange();
		$this->var['total_salary_by_department'] = G_Employee_Helper::getTotalSalaryByDepartment();
		$this->var['page_title'] = 'Dashboard';


		$this->view->setTemplate('template.php');
		$this->view->render('dashboard/employee/index.php',$this->var);
	}

	function _load_total_employee_by_year() {
		$this->var['total_employee'] = $a = G_Employee_Helper::getTotalEmployeeByMonth($_POST['year']);
		$total_hired = G_Employee_Helper::getTotalHiredByYearAndMonth();
		$total_terminated = G_Employee_Helper::getTotalTerminatedByYearAndMonth();
		$this->var['total_employee'] = array_merge((array)$total_hired,(array)$total_terminated);
		$this->view->render('dashboard/employee/includes/no_employees_by_year.php',$this->var);
	}

	function _load_tabular_total_applicant_by_year()
	{
		if($_POST['year']){
			$year = $_POST['year'];
		}else{
			$year = date("Y");
		}
		$month = '';

		$this->var['total_applicant'] = $applicant = G_Applicant_Helper::getTotalApplicantByMonth($year,$month);
		$this->view->render('dashboard/recruitment/includes/no_applicant_by_year.php',$this->var);
	}

	function _load_graphical_total_applicant_by_year()
	{
		if($_POST['year']){
			$year = $_POST['year'];
		}else{
			$year = date("Y");
		}
		$applicant = G_Applicant_Helper::getTotalApplicantByMonth($year,$month);
		$this->var['total_applicant'] = $applicant;
		$this->view->noTemplate();
		$this->view->render('dashboard/recruitment/includes/no_graphical_applicant_by_year.php',$this->var);
	}

	function _load_employee_summary_by_date_range()
	{
		if($_POST['date_from'] && $_POST['date_to']){
			$date_start = $_POST['date_from'];
			$date_end   = $_POST['date_to'];
		}else{
			$date_start = date('Y/m/d');
			$date_end   = date('Y/m/d');
		}
		$status = G_Employee_Job_History_Helper::getTotalEmployeeStatusByDateRange($date_start,$date_end,1);
		$this->var['status'] = $status;
		$this->view->noTemplate();
		$this->view->render('dashboard/employee/includes/employee_summary.php',$this->var);
	}

	function _load_headcount_by_department()
	{
		$this->var['headcount_by_department'] = G_Employee_Helper::getHeadcountByDepartment();
		$this->view->noTemplate();
		$this->view->render('dashboard/employee/includes/headcount_by_department.php',$this->var);
	}
}
?>
