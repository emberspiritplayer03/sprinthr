<?php
class Dashboard_Controller extends Controller
{
	function __construct()
	{
		
		parent::__construct();
		Loader::appMainUtilities();
		
		Loader::appStyle('style.css');
		Loader::appMainScript('dashboard.js');
		Loader::appMainLibrary('FusionCharts');
		$this->var['dashboard'] = 'selected';
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
		
		Utilities::checkModulePackageAccess('attendance','payroll');
		
	}
	
	function index()
	{
		$this->general_information();
	}
	
	

	function general_information()
	{
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
		Yui::loadMainDatatable();
		$year = date("Y");
		$month = '';
		
		$this->var['total_employee'] = G_Employee_Helper::getTotalEmployeeByMonth($year,$month);
		$this->var['headcount_by_department'] = G_Employee_Helper::getHeadcountByDepartment();
		$this->var['total_salary_by_range'] = G_Employee_Helper::getTotalSalaryByRange();
		$this->var['total_salary_by_department'] = G_Employee_Helper::getTotalSalaryByDepartment();
		$this->var['page_title'] = 'Dashboard';
		$this->view->setTemplate('template.php');
		$this->view->render('dashboard/statistics/index.php',$this->var);
	}
	
	function employee_dashboard()
	{
		Yui::loadMainDatatable();
		$year = date("Y");
		$month = '';
		
		$this->var['total_employee'] = G_Employee_Helper::getTotalEmployeeByMonth($year,$month);
		$this->var['headcount_by_department'] = G_Employee_Helper::getHeadcountByDepartment();
		$this->var['total_salary_by_range'] = G_Employee_Helper::getTotalSalaryByRange();
		$this->var['total_salary_by_department'] = G_Employee_Helper::getTotalSalaryByDepartment();
		$this->var['page_title'] = 'Dashboard';
		$this->view->setTemplate('template.php');
		$this->view->render('dashboard/employee/index.php',$this->var);
	}
	
	function _load_total_employee_by_year() 
	{
		$year = $_POST['year'];
		$this->var['total_employee'] = G_Employee_Helper::getTotalEmployeeByMonth($year,$month);
		$this->view->noTemplate();
		$this->view->render('dashboard/employee/includes/no_employees_by_year.php',$this->var);
	}
	
	function _load_headcount_by_department() 
	{
		$this->var['headcount_by_department'] = G_Employee_Helper::getHeadcountByDepartment();
		$this->view->noTemplate();
		$this->view->render('dashboard/employee/includes/headcount_by_department.php',$this->var);
	}	
}
?>