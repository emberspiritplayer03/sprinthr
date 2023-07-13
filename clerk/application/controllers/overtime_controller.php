<?php
class Overtime_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->login();
		Loader::appMainScript('overtime.js');
		Loader::appMainScript('overtime_base.js');

		Loader::appStyle('style.css');
		
		//$this->eid            = Utilities::encrypt(1);
		$this->eid             	= $_SESSION['sprint_hr']['employee_id'];
		$this->c_date			= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		
		$this->var['eid']      	= $this->eid;
		$this->var['overtime']  = 'selected';
		$this->var['employee'] 	= 'selected';
		
		$this->var['company_structure_id'] = $this->company_structure_id = 1;
		//$this->var['company_structure_id'] = $this->company_structure_id = $_SESSION['hr']['company_structure_id'];	
	}
	/*
	OLD OVERTIME MODULE
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
		
		$this->var['page_title'] = 'Overtime Management';
		$this->view->setTemplate('template_clerk.php');
		
		$this->var['department'] = $department = G_Company_Structure_Finder::findParentChildByCompanyStructureId($this->company_structure_id);
		$this->view->render('overtime/index.php',$this->var);
	}
	
	
	function _load_employee_list_dt() {
		$this->view->render('overtime/_employee_list_dt.php',$this->var);
	}
	
	function _load_server_employee_list_dt() {
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		$dt->setCustomField(array('employee_code'=>'employee_code','name' => 'firstname,lastname','job_name'=>'jbh.name'));
		$dt->setJoinTable("LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh");			
		$dt->setJoinFields(EMPLOYEE . ".id = jbh.employee_id");
		$dt->setCondition('');
		$dt->setColumns('employment_status');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Add Overtime Request\" id=\"add_request\" class=\"ui-icon ui-icon-plus g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:addOvertimeRequestForm(\'e_id\');\"></a></li><li><a title=\"View Overtime History\" id=\"view\" class=\"ui-icon ui-icon-search g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:load_show_overtime_details(\'e_id\')\"></a></li></a></li></ul></div>'));
		echo $dt->constructDataTable();
	}
	*/
	
	function insert_employee_request_overtime() {
		sleep(1);
		
		$date_start = (!empty($_POST['start_date']) ? $_POST['start_date'] : $_POST['start_date_hideshow']);
		$end_date 	= (!empty($_POST['end_date']) ? $_POST['end_date'] : $_POST['end_time_hideshow']);
		$time_in	= (!empty($_POST['start_time']) ? $_POST['start_time'] : $_POST['start_time_hideshow']);
		$time_out 	= (!empty($_POST['end_time']) ? $_POST['end_time'] : $_POST['end_time_hideshow']);
		
		if(!empty($_POST)) {
			if(Utilities::isFormTokenValid($_POST['token'])) {
				$settings_request = G_Settings_Request_Finder::findByType(Settings_Request::OT);
				$employee		  = G_Employee_Finder::findById(Utilities::decrypt($_POST['h_employee_id']));
				
				$return = G_Overtime_Helper::validateOvertimeRequest($employee,$date_start,$time_in,$time_out);
				if($return['is_saved']) {
						
					$employee_overtime_request = G_Employee_Overtime_Request_Finder::findByEmployeeIdAndDate(Utilities::decrypt($_POST['h_employee_id']), $date_start);

					if(!$employee_overtime_request) {
						$employee_overtime_request = new G_Employee_Overtime_Request;
					}
					$employee_overtime_request->setCompanyStructureId($this->company_structure_id);
					$employee_overtime_request->setEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
					$employee_overtime_request->setDateApplied($this->c_date);
					$employee_overtime_request->setDateStart($date_start);
					$employee_overtime_request->setDateEnd($end_date);
					$employee_overtime_request->setTimeIn(Tools::convert12To24Hour($time_in));
					$employee_overtime_request->setTimeOut(Tools::convert12To24Hour($time_out));
					$employee_overtime_request->setOvertimeComments(Tools::stringReplace($_POST['reason']));
					$employee_overtime_request->setIsApproved(G_Employee_Overtime_Request::PENDING);
					$employee_overtime_request->setIsArchive(G_Employee_Overtime_Request::NO);
					$employee_overtime_request->setCreatedBy(Utilities::decrypt($this->eid));
					$employee_overtime_request->save();
					
					if($_POST['status'] == G_Employee_Overtime_Request::APPROVED) {
						
						$overtime = G_Overtime_Finder::findByEmployeeAndDate($employee,$date_start);
						if(!$overtime) {
							$overtime = new G_Overtime();
						}
						
						$overtime->setDate($date_start);
						$overtime->setTimeIn(Tools::convert12To24Hour($time_in));
						$overtime->setTimeOut(Tools::convert12To24Hour($time_out));
						$overtime->setEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
						$overtime->setReason(Tools::stringReplace($_POST['reason']));
						$overtime->save();
						
					} else {
						$overtime = G_Overtime_Finder::findByEmployeeAndDate($employee,$date_start);
						if($overtime) {
							$overtime->delete();
						}	
					}
					
					G_Attendance_Helper::updateAttendance($employee, $date_start);
				}

			} else { 
				$return['message']  = 'Error : Invalid Token. Request will not be saved.';
				$return['is_saved'] = false;
			}
		}
		
		$token = Utilities::createFormToken();
		$return['token'] = $token;
		echo json_encode($return);
	}
	
	function _delete_overtime($e,$start_date) {
		$overtime = G_Overtime_Finder::findByEmployeeAndDate($e,$start_date);
		if($overtime) {
			$overtime->delete();
		}	
	}

	function _load_token() {
		$token = Utilities::createFormToken();
		$return['token'] = $token;
		echo json_encode($return);
	}
	
	function _load_show_overtime_details() {
		if(!empty($_POST)) {
			
			$this->var['employee'] 		= $employee = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
			$this->var['h_employee_id'] = $_POST['h_employee_id'];
	
			$e = G_Employee_Finder::findById(Utilities::decrypt($_POST['h_employee_id']));
			$file = MAIN_FOLDER. 'hr/files/photo/'.$e->getPhoto();
			
			if(Tools::isFileExist($file)==true && $e->getPhoto()!='') {
				$this->var['filemtime'] = md5($e->getPhoto()).date("His");
				$this->var['filename'] = $file;
				
			}else { $this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif'; }
			$this->view->render('overtime/overtime_list/show_overtime_details.php',$this->var);
		}
	}
	
	/*
	function _load_edit_overtime_request() {
		if(!empty($_POST)) {
			$this->var['overtime_request']	= $overtime_request = G_Employee_Overtime_Request_Finder::findById(Utilities::decrypt($_POST['h_id']));
			$this->view->render('overtime/overtime_list/form/edit_overtime_request.php',$this->var);
		}
	}
	*/
	
	function _load_update_overtime_request() {
		if(!empty($_POST)) {
			$employee_overtime_request = G_Employee_Overtime_Request_Finder::findById(Utilities::decrypt($_POST['hid']));
			
			$start_time = Tools::convert12To24Hour($_POST['start_time_edit']);
			$end_time   = Tools::convert12To24Hour($_POST['end_time_edit']);
			
			if($employee_overtime_request) {
				$employee = G_Employee_Finder::findById($employee_overtime_request->getEmployeeId());
				$return = G_Overtime_Helper::validateOvertimeRequest($employee,$_POST['start_date_edit'],$start_time,$end_time);
				if($return['is_saved']) {
					
					$employee_overtime_request->setDateStart($_POST['start_date_edit']);
					$employee_overtime_request->setDateEnd($_POST['end_date_edit']);
					$employee_overtime_request->setTimeIn($start_time);
					$employee_overtime_request->setTimeOut($end_time);
					$employee_overtime_request->setOvertimeComments($_POST['reason']);
					$employee_overtime_request->setCreatedBy(Utilities::decrypt($this->eid));
					$employee_overtime_request->save();
					
					if($_POST['status'] != G_Employee_Overtime_Request::APPROVED) {
						$this->_delete_overtime($employee,$_POST['start_date_edit']);
					} else {
						$overtime = G_Overtime_Finder::findByEmployeeAndDate($employee,$_POST['start_date_edit']);
						if(!$overtime) {
							$overtime = new G_Overtime();
						}
						
						$overtime->setDate($_POST['start_date_edit']);
						$overtime->setTimeIn(Tools::convert12To24Hour($start_time));
						$overtime->setTimeOut(Tools::convert12To24Hour($end_time));
						$overtime->setEmployeeId($employee->getId());
						$overtime->setReason(Tools::stringReplace($_POST['reason']));
						$overtime->save();
						
						G_Attendance_Helper::updateAttendance($employee, $_POST['start_date_edit']);
					}
				}
			} else { 
				$return['message']   = 'Error : Request overtime did not save successfully.';
				$return['is_saved'] = false;
			}
		} else { 
			$return['message']   = 'Error : Request overtime did not save successfully.';
			$return['is_saved'] = false;
		}
		echo json_encode($return);
	}
	
	function _load_delete_overtime_request() {
		if(!empty($_POST)) {
			$employee_overtime_request = G_Employee_Overtime_Request_Finder::findById(Utilities::decrypt($_POST['h_id']));
			if($employee_overtime_request) {
				$employee_overtime_request->setIsArchive(G_Employee_Overtime_Request::YES);
				$employee_overtime_request->save();
				
				$employee = G_Employee_Finder::findById($employee_overtime_request->getEmployeeId());
				$overtime = G_Overtime_Finder::findByEmployeeAndDate($employee,$employee_overtime_request->getDateStart());
				$overtime->delete();
				
				G_Attendance_Helper::updateAttendance($employee, $employee_overtime_request->getDateStart());
					
				///$employee_request = G_Employee_Request_Finder::findByRequestId(Utilities::decrypt($_POST['employee_id']));
				
				//$employee_request->delete();
				//$employee_leave_request->delete();
				/*
				$employee_request_approvers = G_Employee_Request_Approver_Finder::findAllByEmployeeRequestId($employee_request->getId());
				foreach($employee_request_approvers as $approvers):
					$approvers->delete();
				endforeach;
				*/
			}
		}
	}
	
	function _load_overtime_list_dt() {
		$this->view->render('overtime/_overtime_list_dt.php',$this->var);
	}
	
	function _load_server_overtime_list_dt() {
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_OVERTIME_REQUEST);
		$dt->setCustomField(array('name' => 'firstname,lastname','job_name'=>'jbh.name'));
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_OVERTIME_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id");
		$dt->setCondition(' is_archive = "No"');
		$dt->setColumns('date_start,time_in,time_out,overtime_comments,is_approved');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editOvertimeRequestForm(\'e_id\');\"></a></li><li><a title=\"Delete\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:deleteOvertimeRequest(\'e_id\',\'is_approved\')\"></a></li></ul></div>'));
		echo $dt->constructDataTable();
	}
	
	function ajax_get_employees_autocomplete() {
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$employees = G_Employee_Finder::searchByFirstnameAndLastname($q);
			
			foreach ($employees as $e) {
				$response[] = array(Utilities::encrypt($e->getId()), $e->getFullname(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}
	
	function load_get_specific_schedule() {
		if(!empty($_POST)) {
			$employee  = G_Employee_Finder::findById(Utilities::decrypt($_POST['h_employee_id']));
			if($employee) {
				$a = G_Attendance_Finder::findByEmployeeAndDate($employee, $_POST['start_date']);
				if($a) {
					$this->var['t'] = $t = $a->getTimesheet();
				}
			}
			$this->view->render('overtime/_show_specific_schedule.php',$this->var);
		}
	}
	
	
	// OVERTIME DATATABLE WITH FILTER BY DEPARTMENT
	function _load_overtime_list_dt_withselectionfilter() {
		$this->view->render('overtime/_overtime_list_dt_withfilterselection.php',$this->var);
	}
	
	function _load_server_overtime_list_dt_withfilterselection() {
		
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
		$dt->setCondition(' is_archive = "No"'.$sqlcond);
		$dt->setColumns('date_start,time_in,time_out,overtime_comments,is_approved');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editOvertimeRequestForm(\'e_id\');\"></a></li><li><a title=\"Delete\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:deleteOvertimeRequest(\'e_id\',\'is_approved\')\"></a></li></ul></div>'));
		echo $dt->constructDataTable();
	}
	
	/*
		RECONSTRUCTED OVERTIME MODULE (CLERK) :
	*/
	
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
		
		if($_GET['sidebar'] == 2) {
			$this->approved_overtime();
		} else if($_GET['sidebar'] == 3) {
			$this->overtime_history();
			
		} else if($_GET['sidebar'] == 4) {
			$this->archived_overtime();
		} else {
			$this->pending_overtime();
		}
	}
	
	function pending_overtime() {
		$this->var['recent'] 	= 'selected';
		$this->var['sidebar']	= 1;
		$this->var['module'] 	= 'overtime';
		$this->view->setTemplate('template_clerk_leftsidebar.php');
		
		$this->var['departments'] = $departments = G_Company_Structure_Finder::findParentChildByCompanyStructureId($this->company_structure_id);
		$this->view->render('overtime/index.php',$this->var);
	}
	
	function approved_overtime() {
		$this->var['approved'] 	= 'selected';
		$this->var['sidebar']	= 2;
		$this->var['module'] 	= 'overtime';
		$this->view->setTemplate('template_clerk_leftsidebar.php');
		
		$this->var['departments'] = $departments = G_Company_Structure_Finder::findParentChildByCompanyStructureId($this->company_structure_id);
		$this->view->render('overtime/index.php',$this->var);
	}
	
	function overtime_history() {
		$this->var['history'] 	= 'selected';
		$this->var['sidebar']	= 3;
		$this->var['module'] 	= 'overtime';
		$this->view->setTemplate('template_clerk_leftsidebar.php');
		
		$this->var['departments'] = $departments = G_Company_Structure_Finder::findParentChildByCompanyStructureId($this->company_structure_id);
		$this->view->render('overtime/index.php',$this->var);
	}
	
	function load_change_overtime_request_status() {
		if(!empty($_POST)) {
			$mArray = $_POST['dtChk'];
			foreach($mArray as $key => $value):
			$d++;
				$eor = G_Employee_Overtime_Request_Finder::findById($value);
				
				if($eor) {
					//$eor->setIsApproved($_POST['status']);
					//$eor->save();
					
					//$employee = G_Employee_Finder::findById($eor->getEmployeeId());
	
					if($_POST['chkAction'] == 'Archive'){
						$o = G_Employee_Overtime_Request_Finder::findById($value);
						if($o) {
							$o->setIsArchive(G_Employee_Overtime_Request::YES);
							$o->save();	
							
							$json['message']    = 'Successfully archived ' . $d . ' record(s)';	
							$json['is_success'] = 1;
							$json['load_dt']    = 1;			
						}
					}elseif($_POST['chkAction'] == 'Restore Archive'){
						$o = G_Employee_Overtime_Request_Finder::findById($value);
						if($o) {
							$o->setIsArchive(G_Employee_Overtime_Request::NO);
							$o->save();	
							
							$json['message']    = 'Successfully restored ' . $d . ' archived record(s)';	
							$json['is_success'] = 1;
							$json['load_dt']    = 4;			
						}
					}else {
					}
									
				}
			endforeach;
		}else{
			$json['is_success'] = 1;
		}
		echo json_encode($json);
	}
	
	function archived_overtime() {
		$this->var['archives'] 	= 'selected';
		$this->var['sidebar']	= 4;
		$this->var['module'] 	= 'overtime';
		$this->view->setTemplate('template_clerk_leftsidebar.php');
		
		$this->var['departments'] = $departments = G_Company_Structure_Finder::findParentChildByCompanyStructureId($this->company_structure_id);
		$this->view->render('overtime/index.php',$this->var);
	}


	function _load_pending_overtime_list_dt() {
		$this->view->render('overtime/_pending_overtime_list_dt.php',$this->var);
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
		$dt->setColumns('date_start,time_in,time_out,overtime_comments');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editOvertimeRequestForm(\'e_id\');\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveOvertimeRequest(\'e_id\',\'is_approved\')\"></a></li></ul></div>'));
		echo $dt->constructDataTable();
	}
	
	function ajax_add_new_overtime_request() 
	{
		$this->var['token']		 = Utilities::createFormToken();	
		$this->var['page_title'] = 'Add New Overtime Request';	
		$this->view->render('overtime/form/request_overtime.php',$this->var);
	}

	function _load_edit_overtime_request() {
		if(!empty($_POST)) {
			$this->var['overtime_request']	= $overtime_request = G_Employee_Overtime_Request_Finder::findById(Utilities::decrypt($_POST['h_id']));
			$this->var['employee']			= G_Employee_Finder::findById($overtime_request->getEmployeeId());
			$this->view->render('overtime/form/edit_overtime_request.php',$this->var);
		}
	}
	
	function _load_archive_overtime_request() {
		if(!empty($_POST)) {
			$o = G_Employee_Overtime_Request_Finder::findById(Utilities::decrypt($_POST['h_id']));
			if($o) {
				$o->setIsArchive(G_Employee_Overtime_Request::YES);
				$o->save();				
			}
			$json['is_saved'] = 1;
		}else{$json['is_saved'] = 0;}
		
		echo json_encode($json);
	}
	
	function _load_generic_overtime_list_dt() {
		$this->var['sidebar'] = $_POST['sidebar'];
		$this->view->render('overtime/_generic_overtime_list_dt.php',$this->var);
	}
	
	function _load_overtime_history_list_dt() {
		$this->var['sidebar'] = $_POST['sidebar'];
		$this->view->render('overtime/_overtime_history_list_dt.php',$this->var);
	}
	
	function _load_server_employee_list_dt() 
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(EMPLOYEE);
		$dt->setCustomField(array('employee_code'=>'employee_code','name' => 'firstname,lastname','job_name'=>'jbh.name'));
		
		$dt->setJoinTable("LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh");			
		$dt->setJoinFields(EMPLOYEE . ".id = jbh.employee_id LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON " . EMPLOYEE . ".id = gsh.employee_id");
		
		if($_GET['department']){
			$dt->setCondition(' gsh.company_structure_id='. Utilities::decrypt($_GET['department']));
		}
		
		$dt->setColumns('employment_status');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Leave History\" id=\"delete\" class=\"ui-icon ui-icon-search g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:load_show_overtime_history_details(\'e_id\')\"></a></li></ul></div>'));
		echo $dt->constructDataTable();
	}
	
	function _load_server_generic_overtime_list_dt() {
		
		if($_GET['department']) {
			$sqlcat 	= " LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON e.id = gsh.employee_id";
			$sqlcond	= " AND gsh.company_structure_id = " . Utilities::decrypt($_GET['department']);
		}
		
		if($_GET['sidebar'] == 2) {
			$condition = ' is_approved = "'.G_Employee_Overtime_Request::APPROVED.'"  AND is_archive = "'.G_Employee_Overtime_Request::NO.'"'.$sqlcond;
		} else {
			$condition = ' is_archive = "'.G_Employee_Overtime_Request::YES.'"'.$sqlcond;
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
		$dt->setCondition($condition);
		$dt->setColumns('date_start,time_in,time_out,overtime_comments');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(0);
		echo $dt->constructDataTable();
	}
	
	
	function load_show_overtime_history_details() {
		if(!empty($_POST['h_employee_id'])) {
			$this->var['employee'] 		= $employee = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
			$this->var['h_employee_id'] = $_POST['h_employee_id'];
			$this->view->render('overtime/overtime_list/show_overtime_history_details.php',$this->var);
		}
	}
	
	function load_employee_overtime_history_list_dt() {
		if(!empty($_POST['h_employee_id'])) {
			$this->var['h_employee_id'] = $_POST['h_employee_id'];
			$this->view->render('overtime/_employee_overtime_history_list_dt.php',$this->var);
		}
	}
	
	function _load_server_employee_overtime_history_list_dt() {

	
		$condition = ' '.G_EMPLOYEE_OVERTIME_REQUEST.".employee_id = ".Utilities::decrypt($_GET['employee_id']);

		
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_OVERTIME_REQUEST);
		$dt->setCustomField();
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_OVERTIME_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id $sqlcat");
		$dt->setCondition($condition);
		$dt->setColumns('date_start,time_in,time_out,overtime_comments');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(0);
		echo $dt->constructDataTable();
	}
	
	function _load_archived_overtime_list_dt() {
		$this->var['sidebar'] = $_POST['sidebar'];
		$this->view->render('overtime/_archived_overtime_list_dt.php',$this->var);
	}
	
	function _load_server_restore_overtime_list_dt() {
		
		if($_GET['department']) {
			$sqlcat 	= " LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON e.id = gsh.employee_id";
			$sqlcond	= " AND gsh.company_structure_id = " . Utilities::decrypt($_GET['department']);
		}
	
		$condition = ' is_archive = "'.G_Employee_Overtime_Request::YES.'"'.$sqlcond;
		
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
		$dt->setCondition($condition);
		$dt->setColumns('date_start,time_in,time_out,overtime_comments');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Restore Archived\" id=\"delete\" class=\"ui-icon ui-icon-refresh g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:restoreOvertimeRequest(\'e_id\',\'is_approved\')\"></a></li></ul></div>'));	
		echo $dt->constructDataTable();
	}
	
	function _load_restore_overtime_request() {
		if(!empty($_POST)) {
			$o = G_Employee_Overtime_Request_Finder::findById(Utilities::decrypt($_POST['h_id']));
			if($o) {
				$o->setIsArchive(G_Employee_Overtime_Request::NO);
				$o->save();				
			}
			$json['is_saved'] = 1;
		}else{$json['is_saved'] = 0;}
		
		echo json_encode($json);
	}
	
	

}
?>