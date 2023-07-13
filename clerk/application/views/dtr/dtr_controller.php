<?php
class Dtr_Controller extends Controller
{
	function __construct()
	{	
		parent::__construct();
		Loader::appMainUtilities();		
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
		
		$this->var['dtr'] = 'selected';
		
		Loader::appStyle('style.css');
	}
	
	function index(){	
		Loader::appMainScript('dtr.js');
		
		$this->var['page_title'] = 'Daily Time Record';
	
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		
		$this->var['dtr'] 	= 'selected';
		$this->view->setTemplate('template.php');
		$this->view->render('dtr/index.php',$this->var);	
	}
	
	function _load_dtr_list_dt(){		
		$this->view->render('dtr/_dtr_list_dt.php',$this->var);
	}
	
	function _load_server_dtr_list_dt() {
		if($_GET['from'] != "" && $_GET["to"] != "") {
			$sqlcat = " AND date_attendance BETWEEN " . Model::safeSql($_GET['from']) . " AND " . Model::safeSql($_GET['to']);
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
		$dt->setCondition(" employee_id = " . Model::safeSql(Utilities::decrypt($this->eid)).$sqlcat);
		$dt->setColumns('date_attendance,actual_time_in,actual_time_out,is_present');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(0);		
		echo $dt->constructDataTable();
	}
	
	function print_dtr_report() {
		$e = G_Employee_Finder::findById(Utilities::decrypt($this->eid));
		$attendance = G_Attendance_Finder::findByEmployeeAndPeriod($e,$_POST['from'],$_POST['to']);
		
		if($_POST['from'] != "" && $_POST['to'] != "") {
			$this->var['filename']		= "dtr_".date("m-d-Y",strtotime($_POST['from']))."_".date("m-d-Y",strtotime($_POST['to']));
			$this->var['report_title'] 	= "Daily Time Record Report (".date("m/d/Y",strtotime($_POST['from']))." - ".date("m/d/Y",strtotime($_POST['to'])).")";
		} else {
			$this->var['filename'] = "dtr_".date("Y-m-d");
		}
		
		$this->var['attendance'] = $attendance;
		$this->view->render('dtr/_rpt_dtr_list.php',$this->var);
	}
}
?>