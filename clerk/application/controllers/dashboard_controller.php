<?php
class Dashboard_Controller extends Controller {
	function __construct()
	{
		parent::__construct();
		
		Loader::appStyle('style.css');
		
		$employee = G_Employee_Finder::findById(Utilities::decrypt($_SESSION['sprint_hr']['employee_id']));
		if($employee) {
			$position = G_Employee_Job_History_Finder::findCurrentJob($employee);
			$this->h_job_position_id 	= Utilities::encrypt($position->getJobId());
		}
		
		$this->eid            			= $_SESSION['sprint_hr']['employee_id']; //employee
		$this->h_company_structure_id 	= Utilities::encrypt($_SESSION['sprint_hr']['company_structure_id']);
		$this->c_date  					= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		
		$this->default_method   		= 'index';
		
		$this->var['eid']       		= $this->eid;
		$this->var['h_job_position_id'] = $this->h_job_position_id;	
		$this->var['h_company_structure_id'] = $this->h_company_structure_id;					
		
		$this->var['employee'] = 'selected';
		Loader::appMainScript('dashboard_clerk.js');
	}

	function index(){
		
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		
		$this->var['dashboard']  = 'selected';				
		$this->var['page_title'] = 'Dashboard';
		$this->var['token']      = Utilities::createFormToken();		
		$this->view->setTemplate('template_clerk.php');
		$this->view->render('dashboard/index.php',$this->var);
	}
	
	function _load_employee_attendance_list_dt(){		
		$this->view->render('dashboard/_employee_attendance_list_dt.php',$this->var);
	}
	
	function _load_server_employee_attendance_list_dt() {
		if($_GET['from'] != "" && $_GET["to"] != "") {
			$sqlcat = " date_attendance = " . Model::safeSql(date("Y-m-d",strtotime("-1 day")));
		}
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_ATTENDANCE);
		$dt->setJoinTable();
		$dt->setJoinFields();
		$dt->setCondition($sqlcat);
		$dt->setColumns('date_attendance,actual_time_in,actual_time_out,is_present');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(0);		
		echo $dt->constructDataTable();
	}
	
	function _load_top_recent_ot_request() {
		$this->var['ot'] = $ot = G_Employee_Overtime_Request_Finder::findAllTopRecentRequest(G_Employee_Overtime_Request::PENDING,"ORDER BY id DESC","LIMIT 5");
		$this->view->render('dashboard/_recent_request_ot_list.php',$this->var);
	}
	
	function _load_top_recent_leave_request() {
		$this->var['leave'] = $leave = G_Employee_Leave_Request_Finder::findAllTopRecentRequest(G_Employee_Leave_Request::PENDING,"ORDER BY id DESC","LIMIT 5");
		$this->view->render('dashboard/_recent_request_leave_list.php',$this->var);
	}
	
	/* Approved */
	function _load_top_recent_approved_ot_request() {
		$this->var['ot'] = $ot = G_Employee_Overtime_Request_Finder::findAllTopRecentRequest(G_Employee_Overtime_Request::APPROVED,"ORDER BY id DESC","LIMIT 5");
		$this->view->render('dashboard/_recent_request_ot_list.php',$this->var);
	}
	
	function _load_top_recent_approved_leave_request() {
		$this->var['leave'] = $leave = G_Employee_Leave_Request_Finder::findAllTopRecentRequest(G_Employee_Leave_Request::APPROVED,"ORDER BY id DESC","LIMIT 5");
		$this->view->render('dashboard/_recent_request_leave_list.php',$this->var);
	}
}
?>