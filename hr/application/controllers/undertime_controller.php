<?php
class Undertime_Controller extends Controller
{
	function __construct()
	{	
		parent::__construct();
		Loader::appMainUtilities();		
		Loader::appStyle('style.css');
		Loader::appMainScript('undertime.js');
		Loader::appMainScript('undertime_base.js');
		
		Loader::appStyle('style.css');
				
		if($_GET['hpid']){
			$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Cutoff_Period_Helper::isPeriodLock($_GET['hpid']);
		}else{			
			$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'];
		}
		
		$employee = G_Employee_Finder::findById(Utilities::decrypt($_SESSION['sprint_hr']['employee_id']));
		if($employee) {
			$position = G_Employee_Job_History_Finder::findCurrentJob($employee);
			if($position){
				$this->h_job_position_id 	= Utilities::encrypt($position->getJobId());
			}
		}
		
		if($_GET['from'] && $_GET['to'] && $_GET['hpid']){			
			$this->p_date_from = $_GET['from'];
			$this->p_date_to   = $_GET['to'];
			
			$this->var['download_url'] = url('reports/download_undertime_request?from=' . $_GET['from'] . '&to=' . $_GET['to'] . '&hpid=' . $_GET['hpid']);
		}
		
		$this->eid            			= $_SESSION['sprint_hr']['employee_id']; //employee
		$this->company_structure_id 	= Utilities::encrypt($_SESSION['sprint_hr']['company_structure_id']);		
		$this->c_date  					= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		$this->default_method   		= 'index';
		$this->var['employee']     	    = 'selected';
		$this->var['eid']       			= $this->eid;
		$this->var['h_job_position_id'] 	= $this->h_job_position_id;	
		$this->var['company_structure_id'] 	= $this->company_structure_id;
		$this->var['departments']           = G_Company_Structure_Finder::findByParentID($_SESSION['sprint_hr']['company_structure_id']);
		
		Utilities::checkModulePackageAccess('attendance','request');
		Utilities::checkModulePackageAccess('attendance','undertime_request');
		
		//employee module must be enable
		Utilities::checkModulePackageAccess('hr','employee');	
	}
	
	function index() {
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();		
		$this->var['page_title'] = 'Undertime Management';
		$this->var['periods'] = $periods = G_Payslip_Helper::getPeriods();
		$this->view->setTemplate('template.php');
		$this->view->render('undertime/index.php',$this->var);
	}
	
	function pending() {
		if(!empty($_GET)) {
			Jquery::loadMainInlineValidation2();
			Jquery::loadMainJqueryFormSubmit();
			Jquery::loadMainTipsy();
			Jquery::loadMainJqueryDatatable();
			Jquery::loadMainTextBoxList();
			
			Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
			Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
			
			$this->var['from_period'] 	  = $from = $_GET['from'];
			$this->var['to_period']		  = $to = $_GET['to'];
			$this->var['hpid']			  = $hpid	= $_GET['hpid'];
			
			$this->var['recent'] 	      = 'selected';
			$this->var['module'] 	      = 'undertime';
			$this->var['sidebar']	      = '1';
			$this->var['period_selected'] = '<small style="font-size:15px;">Period: <b>' . $_GET['from'] . ' </b> to <b>' . $_GET['to'] . '</b></small>';
			$this->var['page_title'] 	  = 'Undertime Management';

			$this->view->setTemplate('template_leftsidebar.php');			
			$this->view->render('undertime/period.php',$this->var);
			
		} else { redirect('undertime'); }
	}
	
	function _load_undertime_list_dt() {		
		$this->view->render('undertime/_undertime_pending_list_dt.php',$this->var);
	}
	
	function _load_server_undertime_pending_list_dt() {
		if($_GET['department']) {
			$sqlcat 	= " LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON e.id = gsh.employee_id";
			$sqlcond	= " AND gsh.company_structure_id = " . Utilities::decrypt($_GET['department'])." AND jbh.end_date = ''";
		}
		
		$sqlcond .= ' AND date_of_undertime BETWEEN ' . Model::safeSql($_GET['from']) . ' AND ' . Model::safeSql($_GET['to']);
		
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_UNDERTIME_REQUEST);	
		$dt->setCustomField(array('name' => 'firstname,lastname','job_name'=>'jbh.name'));	
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_UNDERTIME_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id $sqlcat");
		$dt->setCondition(' is_archive = "' . G_Employee_Undertime_Request::NO . '" AND is_approved="' . G_Employee_Undertime_Request::PENDING . '" '.$sqlcond);
		$dt->setColumns('date_of_undertime,time_out,reason');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		if($_SESSION['sprint_hr']['is_period_lock'] == G_Cutoff_Period::NO){
			$dt->setNumCustomColumn(1);
			$dt->setCustomColumn(	
			array(
			'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" class=\"dtCk\" name=\"dtChk[]\" onclick=\"javascript:uncheckCheckAll();\" value=\"id\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editUndertimeRequestForm(\'e_id\');\"></a></li><li><a title=\"Approve\" id=\"delete\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveUndertime(\'e_id\')\"></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveUndertimeRequest(\'e_id\',1)\"></li></ul></div>'));
		}else {
			$dt->setNumCustomColumn(0);
		}
		echo $dt->constructDataTable();
	}
	
	function ajax_add_new_undertime_request() {	
		$this->var['date_from']  = $_POST['date_from'];
		$this->var['date_to']	 = $_POST['date_to'];			
		$this->var['e']			 = $e;	
		$this->var['token']		 = Utilities::createFormToken();		
		$this->var['page_title'] = 'Add New Undertime Request';		
		$this->view->render('undertime/form/request_undertime.php',$this->var);
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
	
	function ajax_import_undertime() 
	{		
		$this->view->render('undertime/form/ajax_import_undertime.php',$this->var);
	}
	
	function import_undertime()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$file 	 = $_FILES['undertime_file']['tmp_name'];
		$undertime = new G_Undertime_Import($file);
		$undertime->setCompanyStructureId($this->company_structure_id);
		$undertime->setUserId(Utilities::decrypt($this->eid));
		
		$is_imported = $undertime->import();		
		
		if ($is_imported) {
			$return['is_imported'] = true;
			$return['message']     = 'Undertime Request(s) has been successfully imported.';	
		} else {
			$return['is_imported'] = false;
			$return['message']     = 'There was a problem importing undertime request(s). Please contact the administrator.';
		}
		echo json_encode($return);		
	}	
	
	function html_import_undertime() {
		$this->view->setTemplate('template_blank.php');
		$this->view->render('undertime/html/html_import_undertime.php', $this->var);	
	}	
	
	function _insert_new_employee_undertime_request() {

		Utilities::verifyFormToken($_POST['token']);
		if($_POST){
			if($_POST['undertime_request_id']){
				$gur = G_Employee_Undertime_Request_Finder::findById(Utilities::decrypt($_POST['undertime_request_id']));
			}else{
				$gur = new G_Employee_Undertime_Request();
				$gur->setDateApplied($this->c_date);
			}
			
			$gur->setCompanyStructureId(Utilities::decrypt($this->company_structure_id));
			$gur->setEmployeeId(Utilities::decrypt($_POST['h_employee_id']));			
			$gur->setDateOfUndertime($_POST['date_of_undertime']);	
			$gur->setTimeOut($_POST['timeout']);				
			$gur->setReason($_POST['reason']);				
			$gur->setIsApproved($_POST['is_approved']);	
			$gur->setCreatedBy(Utilities::decrypt($this->eid));				
			$gur->setIsArchive(G_Employee_Undertime_Request::NO);				
			$gur_id = $gur->save();
			
			if($_POST['is_approved'] == G_Employee_Undertime_Request::APPROVED){
				//Update Attendance
					$e = G_Employee_Finder::findById(Utilities::decrypt($_POST['h_employee_id']));
					if($e){
						$a = G_Attendance_Finder::findByEmployeeAndDate($e, $_POST['date_of_undertime']);
						if($a){
							$t = $a->getTimesheet();						
							$t->setTimeOut($_POST['timeout']);
							$a->setTimesheet($t);
							$is_saved = $a->recordToEmployee($e);
						}
					}
				//
			}
			
			//code of approver starts here
			/*$employee = G_Employee_Finder::findById(Utilities::decrypt($this->eid));
			$er = G_Employee_Request_Finder::findById(Utilities::decrypt($this->eid));
			$sr = G_Settings_Request_Helper::getAllApprovers(Settings_Request::UNDERTIME,$employee);
			foreach($sr as $sr_id):
				$sra = G_Settings_Request_Approver_Finder::findById($sr_id);
				$era = G_Employee_Request_Approver_Finder::findAllByRequestTypeRequestTypeIdPositionEmployeeId(Settings_Request::UNDERTIME,$gur_id,$sra->getPositionEmployeeId());
				if(!$era) {
					$era = new G_Employee_Request_Approver();
				}
				
				if($sra->getOverrideLevel() != 'Granted') {
					$override_level = ($sra->getLevel() == 1 ? 'Current' : '');
					$level = $sra->getLevel();
				} else { 
					$override_level = 'Granted';
					$level = 0;
				}
				
				$era->setRequestType(Settings_Request::UNDERTIME);
				$era->setRequestTypeId($gur_id);
				$era->setType($sra->getType());
				$era->setLevel($level);
				$era->setOverrideLevel($override_level);
				$era->setMessage($_POST['reason']);
				$era->setStatus(Employee_Undertime_Request::PENDING);
				$era_id = $era->save($er,$sra);
				
				//SEND EMAIL NOTIFICATION TO FIRST APPROVERS AND TO MAIN APPROVER
				if($sra->getLevel() == 1 || $sra->getOverrideLevel() == 'Granted') {
					$era = G_Employee_Request_Approver_Finder::findById($era_id);
					if($sra->getType() == Employee_Request_Approver::EMPLOYEE_ID) {
						Email_Templates::sendApproverRequestNotification($era,Settings_Request::UNDERTIME);
					} else {
						//Send to the Group (By Position)
						Email_Templates::sendApproverByPositionRequestNotification($era,Settings_Request::UNDERTIME);
					}
				}
			endforeach;*/
			
			$json['is_success'] = 1;
			$json['message']    = 'Record was successfully saved.' . $err;
		}else {
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		
		echo json_encode($json);
	}

	
	function _load_employee_get_specific_schedule() 
	{
		sleep(1);		
		$employee  = G_Employee_Finder::findById(Utilities::decrypt($this->eid));		
		if($employee) {				
			$attendance = G_Attendance_Finder::findByEmployeeAndPeriod($employee, $_POST['start_date'],$_POST['start_date']);
			if($attendance) {
				$this->var['attendance'] = $attendance;//$a->getTimesheet();
			}
		}
		$this->view->render('request/undertime/form/_show_specific_schedule.php',$this->var);		
	}
	
	function ajax_edit_undertime_request() {
		$u = G_Employee_Undertime_Request_Finder::findById(Utilities::decrypt($_POST['undertime_id']));
		
		$this->var['u']	         = $u;
		$this->var['employee']	 = $employee;
		$this->var['token']		 = Utilities::createFormToken();
		$this->var['page_title'] = 'Edit Undertime Request';		
		$this->view->render('undertime/form/edit_request_undertime.php',$this->var);
	}
	
	function undertime_with_selected_action() {
		if(!empty($_POST)) {
			$mArray = $_POST['dtChk'];
			foreach($mArray as $key => $value):
			$d++;
				$gur = G_Employee_Undertime_Request_Finder::findById($value);
				
				if($gur) {					
					if($_POST['chkAction'] == 'archive'){						
						$gur->setIsArchive(G_Employee_Undertime_Request::YES);
						$gur->save();		
						
						$json['message']    = 'Successfully archived ' . $d . ' record(s)';	
						$json['is_success'] = 1;
											
					}elseif($_POST['chkAction'] == 'restore'){
						$gur->setIsArchive(G_Employee_Undertime_Request::NO);
						$gur->save();					
						
						$json['message']    = 'Successfully restored ' . $d . ' archived record(s)';	
						$json['is_success'] = 1;							
					}elseif($_POST['chkAction'] == 'approve'){						
						$gur->approve();		
						
						$json['message']    = 'Successfully approved ' . $d . ' record(s)';	
						$json['is_success'] = 1;
					}elseif($_POST['chkAction'] == 'disapprove'){						
						$gur->disapprove();		
						
						$json['message']    = 'Successfully disapproved ' . $d . ' record(s)';	
						$json['is_success'] = 1;
					}
				}
			endforeach;
		}else{
			$json['is_success'] = 0;
		}
		echo json_encode($json);
	}

	function approved() {
		if(!empty($_GET)) {
			
			Jquery::loadMainInlineValidation2();
			Jquery::loadMainJqueryFormSubmit();
			Jquery::loadMainTipsy();
			Jquery::loadMainJqueryDatatable();
			Jquery::loadMainTextBoxList();
			
			Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
			Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
			
			$this->var['from_period'] 	= $from = $_GET['from'];
			$this->var['to_period']		= $to = $_GET['to'];
			$this->var['hpid']			= $hpid	= $_GET['hpid'];
			
			$this->var['approved'] 		  = 'selected';
			$this->var['module'] 		  = 'undertime';
			$this->var['sidebar']		  = '2';
			$this->var['period_selected'] = '<small style="font-size:15px;">Period: <b>' . $_GET['from'] . ' </b> to <b>' . $_GET['to'] . '</b></small>';
			$this->var['page_title'] 	  = 'Undertime Management';
			//$this->var['departments']     = $departments = G_Company_Structure_Finder::findParentChildByCompanyStructureId($this->company_structure_id);
			$this->view->setTemplate('template_leftsidebar.php');
			$this->view->render('undertime/period.php',$this->var);
			
		} else { redirect('undertime'); }
	}
	
	function _load_approved_undertime_list_dt() {		
		$this->view->render('undertime/_undertime_approved_list_dt.php',$this->var);
	}
	
	function _load_server_undertime_approved_list_dt() {
		if($_GET['department']) {
			$sqlcat 	= " LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON e.id = gsh.employee_id";
			$sqlcond	= " AND gsh.company_structure_id = " . Utilities::decrypt($_GET['department'])." AND jbh.end_date = ''";
		}
		
		$sqlcond .= ' AND date_of_undertime BETWEEN ' . Model::safeSql($_GET['from']) . ' AND ' . Model::safeSql($_GET['to']);
		
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_UNDERTIME_REQUEST);	
		$dt->setCustomField(array('name' => 'firstname,lastname','job_name'=>'jbh.name'));	
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_UNDERTIME_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id $sqlcat");
		$dt->setCondition(' is_archive = "' . G_Employee_Undertime_Request::NO . '" AND is_approved="' . G_Employee_Undertime_Request::APPROVED . '"'.$sqlcond);
		$dt->setColumns('date_of_undertime,time_out,reason');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		//$dt->setNumCustomColumn(1);		
		if($_SESSION['sprint_hr']['is_period_lock'] == G_Cutoff_Period::NO){
			$dt->setNumCustomColumn(1);
			$dt->setCustomColumn(	
			array(
			'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" class=\"dtCk\" name=\"dtChk[]\" onclick=\"javascript:uncheckCheckAll();\" value=\"id\"></li><li><a title=\"Disapprove\" id=\"delete\" class=\"ui-icon ui-icon-close g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:disApproveUndertime(\'e_id\')\"></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveUndertimeRequest(\'e_id\',2)\"></li></ul></div>'));
		}else {
			$dt->setNumCustomColumn(0);
		}
		echo $dt->constructDataTable();
	}
	
	function archives()
	{		
		if(!empty($_GET)) {
			
			Jquery::loadMainInlineValidation2();
			Jquery::loadMainJqueryFormSubmit();
			Jquery::loadMainTipsy();
			Jquery::loadMainJqueryDatatable();
			Jquery::loadMainTextBoxList();
			
			Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
			Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
			
			$this->var['from_period'] 	= $from = $_GET['from'];
			$this->var['to_period']		= $to = $_GET['to'];
			$this->var['hpid']			= $hpid	= $_GET['hpid'];
			
			$this->var['archives'] 	      = 'selected';
			$this->var['module'] 	      = 'undertime';
			$this->var['sidebar']	      = '3';
			$this->var['period_selected'] = '<small style="font-size:15px;">Period: <b>' . $_GET['from'] . ' </b> to <b>' . $_GET['to'] . '</b></small>';
			$this->var['page_title'] 	  = 'Undertime Management';

			$this->view->setTemplate('template_leftsidebar.php');
			//$this->var['departments'] = $departments = G_Company_Structure_Finder::findParentChildByCompanyStructureId($this->company_structure_id);
			$this->view->render('undertime/period.php',$this->var);
			
		} else { redirect('undertime'); }
	}
	
	function _load_archive_undertime_list_dt() {		
		$this->view->render('undertime/_undertime_archive_list_dt.php',$this->var);
	}
	
	function _load_server_undertime_archive_list_dt() {
		if($_GET['department']) {
			$sqlcat 	= " LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON e.id = gsh.employee_id";
			$sqlcond	= " AND gsh.company_structure_id = " . Utilities::decrypt($_GET['department'])." AND jbh.end_date = ''";
		}
		
		$sqlcond .= ' AND date_of_undertime BETWEEN ' . Model::safeSql($_GET['from']) . ' AND ' . Model::safeSql($_GET['to']);
		
		Utilities::ajaxRequest();
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_UNDERTIME_REQUEST);	
		$dt->setCustomField(array('name' => 'firstname,lastname','job_name'=>'jbh.name'));	
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_UNDERTIME_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id $sqlcat");
		$dt->setCondition(' is_archive = ' . Model::safeSql( G_Employee_Undertime_Request::YES) .' ' .$sqlcond);
		$dt->setColumns('date_of_undertime,time_out,reason');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		if($_SESSION['sprint_hr']['is_period_lock'] == G_Cutoff_Period::NO){
			$dt->setNumCustomColumn(1);
			$dt->setCustomColumn(	
			array(
			'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:uncheckCheckAll();\" value=\"id\"></li><li><a title=\"Restore Archived\" id=\"delete\" class=\"ui-icon ui-icon-refresh g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:restoreUndertimeRequest(\'e_id\')\"></ul></div>'));
		} else {
			$dt->setNumCustomColumn(0);
		}
		
		echo $dt->constructDataTable();
	}
	
	function _load_restore_undertime_request() {
		if(!empty($_POST)) {
			$gur = G_Employee_Undertime_Request_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($gur) {				
				$gur->setIsArchive(G_Employee_Undertime_Request::NO);
				$gur->save();
				$json['is_success'] = 1;
			}
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}
	
	
	
	function pendings()
	{		
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		
		$script = 'javascript:show_request_undertime_form();';
		$this->var['show_btn']		= 1;
		$this->var['add_script']    = $script;
		$this->var['btn_title']	    = 'Request Undertime';
		$this->var['pendings']		= 'class="selected"';
		$this->var['undertime']     = 'class="selected"';		
		$this->var['page_title']    = 'Undertime Management';		
		$this->view->setTemplate('template_leftsidebar.php');
		$this->view->render('request/undertime/pending.php',$this->var);		
	}
	
	function load_get_specific_schedule() 
	{
		sleep(1);		
		$employee  = G_Employee_Finder::findById(Utilities::decrypt($this->eid));
		if($employee) {				
			$attendance = G_Attendance_Finder::findByEmployeeAndPeriod($employee, $_POST['start_date'],$_POST['end_date']);
			if($attendance) {
				$this->var['attendance'] = $attendance;//$a->getTimesheet();
			}
		}
		$this->view->render('request/undertime/form/_show_specific_schedule.php',$this->var);		
	}
	
	function _load_archive_undertime_request() {
		if(!empty($_POST)) {
			$gur = G_Employee_Undertime_Request_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($gur) {				
				$json['is_success'] = 1;
				$gur->setIsArchive(G_Employee_Undertime_Request::YES);
				$gur->save();							
			}
		}else{$json['is_success'] = 0;}
		
		echo json_encode($json);
	}
	
	function _load_approve_undertime_request() {
		if(!empty($_POST)) {
			$gur = G_Employee_Undertime_Request_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gur) {				
				$gur->approve();
				$json['is_success'] = 1;
				$json['message']    = 'Request was successfully approved.';

			}
		}else{
			$json['is_success'] = 2;
			$json['message']    = 'Error in SQL';
		}
		
		echo json_encode($json);
	}
	
	function _load_disapprove_undertime_request() {
		if(!empty($_POST)) {
			$gur = G_Employee_Undertime_Request_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($gur) {				
				$gur->disapprove();
				$json['is_success'] = 1;
				$json['message']    = 'Request was successfully disapproved.';

			}
		}else{
			$json['is_success'] = 2;
			$json['message']    = 'Error in SQL';
		}
		
		echo json_encode($json);
	}
	
	function undertimeUpdateAttendance()
	{
		$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
		$hours_worked = Tools::computeHoursDifference($actual_time_in, $actual_time_out);
		$t = new G_Timesheet;	
		if ($hours_worked < 8) {
			$u = new Undertime_Calculator;
			$u->setScheduledTimeIn($scheduled_time_in);
			$u->setScheduledTimeOut($scheduled_time_out);
			$u->setActualTimeIn($actual_time_in);
			$u->setActualTimeOut($actual_time_out);
			$u->setBreakTimeIn($break_time_in);
			$u->setBreakTimeOut($break_time_out);			
			$undertime_hours = $u->computeUndertimeHours();
			$t->setUndertimeHours($undertime_hours);
		} else {
			$t->setUndertimeHours(0);
		}
		$a->setTimesheet($t);
	}
}
?>