<?php
class Reconstructed_Overtime_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->login();
		
		Loader::appMainScript('overtime.js');
		
		Loader::appMainScript('reconstructed_overtime.js');
		Loader::appMainScript('reconstructed_overtime_base.js');

		Loader::appStyle('style.css');
		
		$this->eid             	= Utilities::encrypt(1);				
		$this->c_date			= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		
		$this->var['eid']      	= $this->eid;
		$this->var['overtime']  = 'selected';
		$this->var['employee'] 	= 'selected';
		
		$this->var['company_structure_id'] = $this->company_structure_id = 1;
		//$this->var['company_structure_id'] = $this->company_structure_id = $_SESSION['hr']['company_structure_id'];	
	}

	function index()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainScript('attendance.js');
		Loader::appMainScript('attendance_base.js');
		
		$this->var['page_title'] 	= 'Overtime Management';
		$this->var['recent'] 		= 'selected';
		$this->var['sidebar']		= 1;
		
		
		$this->var['module'] 		= 'overtime';
		$this->view->setTemplate('template_clerk_leftsidebar.php');
		
		$this->var['departments'] = $departments = G_Company_Structure_Finder::findParentChildByCompanyStructureId($this->company_structure_id);
		$this->view->render('reconstructed_overtime/index.php',$this->var);
	}
	
	// OVERTIME DATATABLE WITH FILTER BY DEPARTMENT
	function _load_pending_overtime_list_dt() {
		$this->view->render('reconstructed_overtime/_pending_overtime_list_dt.php',$this->var);
	}
	
	function _load_server_pending_overtime_list_dt() {
		
		if($_GET['department']) {
			$sqlcat 	= " LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON e.id = gsh.employee_id";
			$sqlcond	= " AND gsh.company_structure_id = " . Utilities::decrypt($_GET['department']);
		}
		
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_OVERTIME_REQUEST);
		$dt->setCustomField(array('name' => 'firstname,lastname','job_name'=>'jbh.name'));
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_OVERTIME_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id $sqlcat");
		$dt->setCondition(' is_approved = "'.G_Employee_Overtime_Request::PENDING.'"  AND is_archive = "'.G_Employee_Overtime_Request::NO.'"'.$sqlcond);
		$dt->setColumns('date_start,time_in,time_out,overtime_comments,is_approved');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editOvertimeRequestForm(\'e_id\');\"></a></li><li><a title=\"Delete\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:deleteOvertimeRequest(\'e_id\',\'is_approved\')\"></ul></div>'));
		echo $dt->constructDataTable();
	}
	
	function ajax_add_new_overtime_request() 
	{
		$this->var['token']		 = Utilities::createFormToken();	
		$this->var['page_title'] = 'Add New Overtime Request';		
		$this->view->render('reconstructed_overtime/form/request_overtime.php',$this->var);
	}

}
?>