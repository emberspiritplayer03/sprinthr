<?php
class Leave_Controller extends Controller
{
	function __construct()
	{	
		parent::__construct();
		
		Loader::appStyle('style.css');
		Loader::appMainScript('leave.js');
		Loader::appMainScript('leave_base.js');
		
		$this->eid                  = $_SESSION['sprint_hr']['employee_id'];
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
		$this->c_date  				= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		$this->default_method       = 'index';					
		$this->var['leave']         = 'selected';			
		$this->var['employee']      = 'selected';
		$this->var['eid']           = $this->eid;	
		$this->var['departments']   = G_Company_Structure_Finder::findByParentID(1);
		$this->var['company_structure_id'] = $this->company_structure_id;
	}

	function index()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$this->var['recent']        = 'class="selected"';
		$this->var['import_action'] = url('leave/_import_leave_excel');
		$this->var['page_title']    = 'Leave Management';
		$this->var['module'] 		= 'leave'; 
		$this->view->setTemplate('template_clerk_leftsidebar.php');
		$this->view->render('leave/index.php',$this->var);		
	}
	
	function approved()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$this->var['approved']      = 'class="selected"';
		$this->var['import_action'] = url('leave/_import_leave_excel');
		$this->var['page_title']    = 'Leave Management';		
		$this->var['module'] 		= 'leave'; 
		$this->view->setTemplate('template_clerk_leftsidebar.php');
		$this->view->render('leave/approved.php',$this->var);		
	}
	
	function history()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$this->var['history']       = 'class="selected"';
		$this->var['import_action'] = url('leave/_import_leave_excel');
		$this->var['page_title']    = 'Leave Management';
		$this->var['module'] 		= 'leave'; 
		$this->view->setTemplate('template_clerk_leftsidebar.php');
		$this->view->render('leave/history.php',$this->var);		
	}
	
	function archives()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		$this->var['archives']      = 'class="selected"';
		$this->var['import_action'] = url('leave/_import_leave_excel');
		$this->var['page_title']    = 'Leave Management';	
		$this->var['module'] 		= 'leave'; 	
		$this->view->setTemplate('template_clerk_leftsidebar.php');
		$this->view->render('leave/archives.php',$this->var);		
	}
	
	function _load_leave_list_archives_dt() 
	{
		$this->var['dept_id'] = $_POST['dept_id'];
		$this->view->render('leave/_leave_list_archives_dt.php',$this->var);
	}
	
	function _load_employee_leave_available_dt() 
	{
		$this->var['h_employee_id'] = Utilities::decrypt($_POST['employee_id']);
		$this->view->render('leave/leave_list/_leave_available_list_dt.php',$this->var);
	}
	
	function _load_employee_leave_list_dt() 
	{
		$this->var['h_employee_id'] = Utilities::decrypt($_POST['employee_id']);
		$this->view->render('leave/leave_list/_leave_list_dt.php',$this->var);
	}
	
	function _load_leave_history_dt() 
	{
		$this->var['dept_id'] = $_POST['dept_id'];
		$this->view->render('leave/_leave_history_dt.php',$this->var);
	}
	
	function _load_leave_list_dt() 
	{
		$this->var['dept_id'] = $_POST['dept_id'];
		$this->view->render('leave/_leave_list_dt.php',$this->var);
	}
	
	function _load_approved_leave_list_dt() 
	{
		$this->var['dept_id'] = $_POST['dept_id'];
		$this->view->render('leave/_leave_approved_list_dt.php',$this->var);
	}
	
	function _pending_leave_with_selected_action()
	{
		sleep(1);	
		$mArray = $_POST['dtChk'];
		if($mArray){			
			$cd = 0;
			$c  = 0;
			foreach($mArray as $key => $value){	
				//Get Record	
				$lr = G_Employee_Leave_Request_Finder::findById($value);						
				if($lr){						
					$d++;
					if($_POST['chkAction'] == 'archive'){																	
						//Archive Request//
						$lr->setIsArchive(G_Employee_Leave_Request::YES);
						$lr->save();
						$json['message'] = 'Successfully archived ' . $d . ' record(s)';	
						$json['is_success'] = 1;
						/////////////////
						
					}
				}
			}	
		}else{
			$json['is_success'] = 1;
		}
		echo json_encode($json);
	}
	
	function _archive_with_selected_action() 
	{
		sleep(1);	
		$mArray = $_POST['dtChk'];
		if($mArray){			
			$cd = 0;
			$c  = 0;
			foreach($mArray as $key => $value){	
				//Get Record													
				$d++;
				
				if($_POST['chkActionSub'] == 'restore_leave_request'){
					$l = G_Employee_Leave_Request_Finder::findById($value);
					if($l){																	
						//Archive Leave Type//
						$l->setIsArchive(G_Employee_Leave_Request::NO);
						$l->save();
						$json['message']    = 'Successfully restored ' . $d . ' archived record(s)';	
						$json['is_success'] = 1;
						$json['form']		= 2;
						/////////////////	
					}else{
						$json['message']    = 'No record(s) to restore';	
						$json['is_success'] = 1;
						$json['form']		= 2;	
					}
				}					
				
			}		
		
		}else{
			$json['is_success'] = 1;
		}		
		echo json_encode($json);
	}
	
	function _load_server_approved_leave_list_dt() {
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LEAVE_REQUEST);
		$dt->setCustomField(array('emp_name' => 'firstname,lastname','job_name'=>'jbh.name','leave_type' =>'l.name'));
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_LEAVE_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id LEFT JOIN " . G_LEAVE ." l ON " . G_EMPLOYEE_LEAVE_REQUEST . ".leave_id =l.id LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON e.id = gsh.employee_id");
		if($_GET['dept_id']){
			$dt->setCondition(' is_archive = "' . G_Employee_Leave_Request::NO . '" AND gsh.company_structure_id='. Utilities::decrypt($_GET['dept_id']) . ' AND is_approved = "' . G_Employee_Leave_Request::APPROVED . '"');
		}else{
			$dt->setCondition(' is_archive = "' . G_Employee_Leave_Request::NO . '" AND is_approved ="'. G_Employee_Leave_Request::APPROVED . '"');
		}
		$dt->setColumns('date_start,date_end');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(0);		
		echo $dt->constructDataTable();
	}
	
	function _load_server_leave_list_dt() {
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LEAVE_REQUEST);
		$dt->setCustomField(array('emp_name' => 'firstname,lastname','job_name'=>'jbh.name','leave_type' =>'l.name'));
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_LEAVE_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id LEFT JOIN " . G_LEAVE ." l ON " . G_EMPLOYEE_LEAVE_REQUEST . ".leave_id =l.id LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON e.id = gsh.employee_id");
		if($_GET['dept_id']){
			$dt->setCondition(' is_archive = "' . G_Employee_Leave_Request::NO . '" AND gsh.company_structure_id='. Utilities::decrypt($_GET['dept_id']) . ' AND is_approved = "' . G_Employee_Leave_Request::PENDING . '"');
		}else{
			$dt->setCondition(' is_archive = "' . G_Employee_Leave_Request::NO . '" AND is_approved="' . G_Employee_Leave_Request::PENDING . '"');
		}
		$dt->setColumns('date_start,date_end');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editLeaveRequestForm(\'e_id\');\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveLeaveRequest(\'e_id\',\'is_approved\')\"></a></li></ul></div>'));
		echo $dt->constructDataTable();
	}
	
	function _load_server_leave_list_archives_dt() 
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LEAVE_REQUEST);
		$dt->setCustomField(array('emp_name' => 'firstname,lastname','job_name'=>'jbh.name','leave_type' =>'l.name'));
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_LEAVE_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id LEFT JOIN " . G_LEAVE ." l ON " . G_EMPLOYEE_LEAVE_REQUEST . ".leave_id =l.id LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON e.id = gsh.employee_id");
		if($_GET['dept_id']){
			$dt->setCondition(' is_archive = "' . G_Employee_Leave_Request::NO . '" AND gsh.company_structure_id='. Utilities::decrypt($_GET['dept_id']));
		}else{
			$dt->setCondition(' is_archive = "' . G_Employee_Leave_Request::YES . '"');
		}
		$dt->setColumns('date_start,date_end,is_approved');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:archivesEnableDisableWithSelected(2);\" value=\"id\"></li><li><a title=\"Restore Archived\" id=\"delete\" class=\"ui-icon ui-icon-refresh g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:restoreLeaveRequest(\'e_id\',\'is_approved\')\"></a></li></ul></div>'));	
		echo $dt->constructDataTable();
	}
	
	function _load_server_employee_leave_available_dt() 
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LEAVE_AVAILABLE);		
		$dt->setJoinTable("LEFT JOIN " . G_LEAVE . " l");			
		$dt->setJoinFields(G_EMPLOYEE_LEAVE_AVAILABLE . ".leave_id =l.id");
		$dt->setCondition(G_EMPLOYEE_LEAVE_AVAILABLE . '.employee_id='. Utilities::decrypt($_GET['employee_id']));				
		$dt->setColumns('name,no_of_days_alloted,no_of_days_available');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);		
		echo $dt->constructDataTable();
	}
	
	function _load_server_employee_leave_list_dt() 
	{
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LEAVE_REQUEST);		
		$dt->setJoinTable("LEFT JOIN " . G_LEAVE . " l");			
		$dt->setJoinFields(G_EMPLOYEE_LEAVE_REQUEST . ".leave_id =l.id");
		$dt->setCondition('employee_id='. Utilities::decrypt($_GET['employee_id']));				
		$dt->setColumns('date_applied,date_start,date_end,name,is_approved');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		/*$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Leave History\" id=\"delete\" class=\"ui-icon ui-icon-search g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:load_show_leave_details(id)\"></a></li></ul></div>'));*/
		echo $dt->constructDataTable();
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
		
		if($_GET['dept_id']){
			$dt->setCondition(' gsh.company_structure_id='. Utilities::decrypt($_GET['dept_id']));
		}else{
						
		}
		
		$dt->setColumns('employment_status');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(1);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Leave History\" id=\"delete\" class=\"ui-icon ui-icon-search g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:load_show_leave_details(id)\"></a></li></ul></div>'));
		echo $dt->constructDataTable();
	}
	
	function ajax_quick_add_leave_request() 
	{
		$e 					     = G_Employee_Finder::findById($_POST['h_employee_id']);		
		$this->var['e']			 = $e;	
		$this->var['token']		 = Utilities::createFormToken();
		$this->var['leaves']     = $leave = G_Leave_Finder::findAll();		
		$this->var['page_title'] = 'Add New Leave Request';		
		$this->view->render('leave/form/quick_add_request_leave.php',$this->var);
	}
	
	function ajax_add_new_leave_request() 
	{
		sleep(1);
		$this->var['e']			 = $e;	
		$this->var['token']		 = Utilities::createFormToken();
		$this->var['leaves']     = $leave = G_Leave_Finder::findAllIsNotArchive();		
		$this->var['page_title'] = 'Add New Leave Request';		
		$this->view->render('leave/form/request_leave.php',$this->var);
	}
	
	function ajax_edit_leave_request() 
	{
		$l 					     = G_Employee_Leave_Request_Finder::findById(Utilities::decrypt($_POST['c_leave_id']));
		$e 						 = G_Employee_Finder::findById($l->getEmployeeId());		
		$this->var['e']			 = $e;
		$this->var['leave']		 = $l;	
		$this->var['token']		 = Utilities::createFormToken();
		$this->var['leaves']     = $leave = G_Leave_Finder::findAllIsNotArchive();		
		$this->var['page_title'] = 'Edit Leave Request';		
		$this->view->render('leave/form/edit_request_leave.php',$this->var);
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
		sleep(1);
		if(!empty($_POST)) {
			$employee  = G_Employee_Finder::findById(Utilities::decrypt($_POST['h_employee_id']));
			if($employee) {
				//$a = G_Attendance_Finder::findByEmployeeAndDate($employee, $_POST['start_date']);
				$attendance = G_Attendance_Finder::findByEmployeeAndPeriod($employee, $_POST['start_date'],$_POST['end_date']);
				if($attendance) {
					$this->var['attendance'] = $attendance;//$a->getTimesheet();
				}
			}
			$this->view->render('leave/form/_show_specific_schedule.php',$this->var);
		}
	}
	
	function _load_get_employee_leave_available() {
		sleep(1);
		if(!empty($_POST)) {
			$employee  = G_Employee_Finder::findById(Utilities::decrypt($_POST['h_employee_id']));
			if($employee) {				
				$leave_available = G_Employee_Leave_Available_Finder::findByEmployeeId($employee->getId());
			}
			$this->var['leave_available'] = $leave_available;
			$this->view->render('leave/form/_show_leave_available.php',$this->var);
		}
	}
	
	function _insert_new_employee_leave()
	{

		Utilities::verifyFormToken($_POST['token']);

		$row = $_POST;
		if($_POST['employee_id']){
			if($_POST['leave_request_id']){
				$l = G_Employee_Leave_Request_Finder::findById(Utilities::decrypt($_POST['leave_request_id']));
			}else{
				$l = new G_Employee_Leave_Request();			
				//$l->setDateApplied($_POST['date_applied']);
				$l->setDateApplied($this->c_date);
			}
			$l->setEmployeeId(Utilities::decrypt($_POST['employee_id']));
			$l->setLeaveId(Utilities::decrypt($_POST['leave_id']));
			$l->setCompanyStructureId($this->company_structure_id);			
			$l->setDateStart($_POST['date_start']);
			$l->setDateEnd($_POST['date_end']);			
			$l->setApplyHalfDayDateStart($_POST['start_halfday'] ? G_Employee_Leave_Request::YES : G_Employee_Leave_Request::NO);
			$l->setApplyHalfDayDateEnd($_POST['end_halfday'] ? G_Employee_Leave_Request::YES : G_Employee_Leave_Request::NO);			
			
			$l->setLeaveComments($_POST['leave_comments']);
			//$l->setCreatedBy(Utilities::decrypt($this->eid));
			$l->setCreatedBy(Utilities::decrypt($this->eid));
			$l->setIsApproved(G_Employee_Leave_Request::PENDING);	
			$l->setIsArchive(G_Employee_Leave_Request::NO);
			
			
			//Validate if there are still leave credits
			if($_POST['is_paid'] == G_Employee_Leave_Request::YES){
				$la = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveId(Utilities::decrypt($_POST['employee_id']),Utilities::decrypt($_POST['leave_id']));	
				if($la){
					if($la->getNoOfDaysAvailable() > $_POST['number_of_days']){
						$l->setIsPaid($_POST['is_paid']);
						$err = '';
					}else{
						$l->setIsPaid(G_Employee_Leave_Request::NO);
						$err = '<br /><b>Note: Cannot set to with pay. No enough available leave credits.</b>';
					}	
				}else{
					$l->setIsPaid(G_Employee_Leave_Request::NO);
					$err = '<br /><b>Note: Cannot set to with pay. No available leave credits.</b>';
				}
			}
			//
			
			$l->save();
			
			/*if ($_POST['is_approved']) {
				$start_date = strtotime($_POST['date_start']);
				$end_date   = strtotime($_POST['date_end']);
				$emp = G_Employee_Finder::findById($_POST['employee_id']);
				if ($emp) {
					if ($start_date && $end_date) {
						$start_date = date('Y-m-d', $start_date);
						$end_date   = date('Y-m-d', $end_date);
						
						$dates = Tools::getBetweenDates($start_date, $end_date);
						foreach ($dates as $date) {
							G_Attendance_Helper::updateAttendance($emp, $date);								
						}	
					}
				}
			}*/
			
			$es = G_Employee_Subdivision_History_Finder::findRecentHistoryByEmployeeId(Utilities::decrypt($_POST['employee_id']));
			
			if($es){
				$json['es_id']= $es->getCompanyStructureId();
			}
			
			$json['e_id']	  = $_POST['employee_id'];
			$json['is_saved'] = 1;
			$json['message']  = 'Record was successfully saved.' . $err;
		}else {
			$json['is_saved'] = 0;
			$json['message']  = 'Error in sql';
		}
		
		echo json_encode($json);
	}
	
	function _update_new_employee_leave()
	{

		Utilities::verifyFormToken($_POST['token']);

		$row = $_POST;
		if($_POST['edit_employee_id'] && $_POST['edit_leave_request_id']){			
			$e = G_Employee_Leave_Request_Finder::findById($_POST['edit_leave_request_id']);			
			$e->setEmployeeId($_POST['edit_employee_id']);
			$e->setLeaveId(Utilities::decrypt($_POST['edit_leave_id']));
			$e->setCompanyStructureId($this->company_structure_id);
			$e->setDateApplied($_POST['edit_date_applied']);
			$e->setDateStart($_POST['edit_date_start']);
			$e->setDateEnd($_POST['edit_date_end']);
			$e->setLeaveComments($_POST['edit_leave_comments']);
			$e->setCreatedBy(Utilities::decrypt($this->eid));
			//$e->setIsApproved($_POST['is_approved']);	
			$e->setIsArchive(G_Employee_Leave_Request::NO);
			$e->save();
			
			/*if ($_POST['is_approved']) {
				$start_date = strtotime($_POST['date_start']);
				$end_date   = strtotime($_POST['date_end']);
				$emp = G_Employee_Finder::findById($_POST['employee_id']);
				if ($emp) {
					if ($start_date && $end_date) {
						$start_date = date('Y-m-d', $start_date);
						$end_date   = date('Y-m-d', $end_date);
						
						$dates = Tools::getBetweenDates($start_date, $end_date);
						foreach ($dates as $date) {
							G_Attendance_Helper::updateAttendance($emp, $date);								
						}	
					}
				}
			}*/
			$json['e_id']     = $_POST['edit_employee_id'];
			$json['message']  = 'Record was successfully updated.';
			$json['is_saved'] = 1;
		}else {
			$json['is_saved'] = 0;
			$json['message']  = 'Error in sql';
		}
		
		echo json_encode($json);
	}
	
	function _load_show_leave_details()
	{
		sleep(1);
		if(!empty($_POST['h_employee_id'])) {
			$this->var['employee'] 		= $employee = G_Employee_Helper::findByEmployeeId($_POST['h_employee_id']);			
			$this->var['h_employee_id'] = $_POST['h_employee_id'];
			$this->view->render('leave/leave_list/show_leave_details.php',$this->var);
		}
	}
	
	function _load_overtime_list_dt() 
	{
		$this->var['h_employee_id'] = $_POST['h_employee_id'];
		$this->view->render('overtime/overtime_list/_overtime_list_dt.php',$this->var);
	}
		
	function _load_archive_leave_request() 
	{
		if(!empty($_POST)) {
			$l = G_Employee_Leave_Request_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($l) {
				$eid = $l->getEmployeeId();
				
				$json['e_id']     = $l->getEmployeeId();
				$json['is_success'] = 1;
				$l->setIsArchive(G_Employee_Leave_Request::YES);
				$l->save();
				
				$es = G_Employee_Subdivision_History_Finder::findRecentHistoryByEmployeeId($eid);
			
				if($es){
					$json['es_id']= $es->getCompanyStructureId();
				}
							
			}
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}	
	
	function _load_restore_leave_request() 
	{
		if(!empty($_POST)) {
			$l = G_Employee_Leave_Request_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($l) {
				$eid = $l->getEmployeeId();
				
				$json['e_id']     = $l->getEmployeeId();
				$json['is_success'] = 1;
				$l->setIsArchive(G_Employee_Leave_Request::NO);
				$l->save();
				
				$es = G_Employee_Subdivision_History_Finder::findRecentHistoryByEmployeeId($eid);
			
				if($es){
					$json['es_id']= $es->getCompanyStructureId();
				}
							
			}
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}
	
	function _load_token() 
	{
		$token = Utilities::createFormToken();
		$return['token'] = $token;
		echo json_encode($return);
	}
	
	function _import_leave_excel()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
				
		$file = $_FILES['leave']['tmp_name'];
		//$file = BASE_PATH . 'files/files/attendance.xls';
		$data = new Excel_Reader($file);
		$total_row = $data->countRow();
		
		$error_count = 0;
		$imported_count = 0;
		
		$error_employee_code = 0;
		$error_complete_name = 0;
		$error_date_start =0;
		$error_date_end = 0;
		$error_date_applied =0;
		for ($i = 1; $i <= $total_row; $i++) {

				$excel_employee_code = (string) trim($data->getValue($i, 'A'));				
				$excel_lastname = (string) trim(utf8_encode($data->getValue($i, 'B')));
				$excel_firstname = (string) trim(utf8_encode($data->getValue($i, 'C')));
				$excel_middlename = (string) trim(utf8_encode($data->getValue($i, 'D')));
				$excel_leave_type = (string) trim(utf8_encode($data->getValue($i, 'E')));
				
				$date_applied = (string) trim($data->getValue($i, 'F'));
				$excel_date_applied = date('Y-m-d', strtotime($date_applied));
				
				$date_start = (string) trim($data->getValue($i, 'G'));
				$excel_date_start = date('Y-m-d', strtotime($date_start));
				
				$date_end = (string) trim($data->getValue($i, 'H'));
				$excel_date_end = date('Y-m-d', strtotime($date_end));
				
				$excel_is_paid = (string) trim(utf8_encode($data->getValue($i, 'I')));
				$excel_comment = (string) trim(utf8_encode($data->getValue($i, 'J')));
												
				$company_structure_id = $_SESSION['hr']['company_structure_id'];
				
			if($i>1) {
	
				if ($excel_employee_code) {
					$e = G_Employee_Finder::findByEmployeeCode($excel_employee_code);
					if (!$e) {
						$error_count++;
						$error_employee_code++; // no employee code
						$code[] = $excel_employee_code;
					}else {
						
						$leave_type = G_Leave_Finder::findByName($excel_leave_type);
						if($leave_type) {
							
							$l = new G_Employee_Leave_Request;
							$l->setCompanyStructureId($company_structure_id);
							$l->setEmployeeId($e->getId());
							$l->setLeaveId($leave_type->getId());
							$l->setDateApplied($excel_date_applied);
							$l->setDateStart($excel_date_start);
							$l->setDateEnd($excel_date_end);
							$l->setLeaveComments($excel_comment);
							$l->setIsApproved(G_Employee_Leave_Request::APPROVED);
							$l->setIsArchive(G_Employee_Leave_Request::NO);
							$l->save();
							
							if (strtotime($excel_date_start) && strtotime($excel_date_end)) {
								$start_date = date('Y-m-d', strtotime($excel_date_start));
								$end_date = date('Y-m-d', strtotime($excel_date_end));
								
								$dates = Tools::getBetweenDates($start_date, $end_date);
								foreach ($dates as $date) {
									G_Attendance_Helper::updateAttendance($e, $date);								
								}	
							}
							$imported_count++;
						}else {
							
							//create new leave type
							$leave_type = new G_Leave;
							$leave_type->setCompanyStructureId($company_structure_id);	
							$leave_type->setName($excel_leave_type);
							$is_paid = ($excel_is_paid=='yes') ? 1 : 0 ;
							$leave_type->setIsPaid($is_paid);
							$leave_type_id = $leave_type->save();
							//create leave request
							$l = new G_Employee_Leave_Request;
							$l->setCompanyStructureId($company_structure_id);
							$l->setEmployeeId($e->getId());
							$l->setLeaveId($leave_type_id);
							$l->setDateApplied($excel_date_applied);
							$l->setDateStart($excel_date_start);
							$l->setDateEnd($excel_date_end);
							$l->setLeaveComments($excel_comment);
							$l->setIsArchive(G_Employee_Leave_Request::NO);
							$l->setIsApproved(1);
							$l->save();
							
							if (strtotime($excel_date_start) && strtotime($excel_date_end)) {
								$start_date = date('Y-m-d', strtotime($excel_date_start));
								$end_date = date('Y-m-d', strtotime($excel_date_end));
								
								$dates = Tools::getBetweenDates($start_date, $end_date);
								foreach ($dates as $date) {
									G_Attendance_Helper::updateAttendance($e, $date);								
								}	
							}						
							$imported_count++;	
							
						}	
					}			
				}else {
					//search by name
					$error_count++;
					$error_employee_code++;
					
				}
				
				$error_complete_name=0;
			}
		}
				
		if ($imported_count > 0) {
			$return['is_imported'] = true;
			if ($error_count > 0) {
				$total_row = $total_row - 1; // minus the excel title header
				$msg =  $imported_count. ' of '.$total_row .' records has been successfully imported.';
				if($error_employee_code>0) {
					$msg .= '<br> '. $error_employee_code.' error(s) found in Employee Code.<br>
							List of Employee Code does not exist<br>
					';	
					foreach($code as $key=>$value) {
						$msg .= "Row: " .$value.'<br>';
					}
				}
	
				$return['message']= $msg;
			} else {
				$return['message'] = $imported_count . ' Record(s) has been successfully imported.';
			}
		} else {
			$return['message'] = 'There was a problem importing the leave. Please contact the administrator.';
		}
		//echo json_encode($return);	
		echo $return['message'];
	}
}
?>