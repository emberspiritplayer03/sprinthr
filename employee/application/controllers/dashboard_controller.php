<?php
class Dashboard_Controller extends Controller
{
	function __construct()
	{	
		parent::__construct();
		$this->isLogin();
		$this->sprintHdrMenu(G_Sprint_Modules::EMPLOYEE, 'employee_dashboard');
		$this->validatePermission(G_Sprint_Modules::EMPLOYEE,'employee_dashboard','');
		Loader::appStyle('style.css');
		Loader::appMainScript('employee_request.js');
		Loader::appMainScript('employee_request_base.js');	
	}
	
	function index()
	{
		$user_id = Utilities::decrypt($this->global_user_eid);
		$e = G_Employee_Finder::findById($user_id);
		if($e) {
			$this->var['leave_available'] 			= G_Employee_Leave_Available_Helper::getEmployeeLeaveAvailable($e);
		}else{
			redirect('login/logout');
		}
		$r = new G_Request();
		$r->setApproverEmployeeId($user_id);
		$data = $r->getPendingForApprovalRequest();

		$this->var['request_needs_approval']  	= $data['needs_approval'];
		$this->var['page_title'] 				= 'Dashboard';
		$this->view->setTemplate('template_employee_portal.php');
		$this->view->render('dashboard/index.php',$this->var);
	}

	function for_approval()
	{
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();

		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		
		Jquery::loadMainBootStrapDropDown();

		$user_id = Utilities::decrypt($this->global_user_eid);

		$r = new G_Request();
		$r->setApproverEmployeeId($user_id);

		$r->setRequestType(G_Request::PREFIX_OVERTIME);
		$data = $r->getPendingForApprovalRequest();
		$this->var['overtime_for_approval'] = $data['needs_approval'];

		$r->setRequestType(G_Request::PREFIX_LEAVE);
		$data = $r->getPendingForApprovalRequest();
		$this->var['leave_for_approval'] = $data['needs_approval'];

		$r->setRequestType(G_Request::PREFIX_OFFICIAL_BUSSINESS);
		$data = $r->getPendingForApprovalRequest();
		$this->var['ob_for_approval'] = $data['needs_approval'];

		$this->var['page_title'] = 'For Your Approval';
		$this->view->setTemplate('template_employee_portal.php');
		$this->view->render('dashboard/for_approval.php',$this->var);
	}

	function _load_for_approval_data() {
		if($_GET['action'] == "overtime") {
			$this->var['request_prefix'] = G_Request::PREFIX_OVERTIME;
			$this->view->render('dashboard/_overtime_request_for_approval_dt.php',$this->var);
		}else if($_GET['action'] == "leave"){
			$this->view->render('dashboard/_leave_request_for_approval_dt.php',$this->var);
		}else if($_GET['action'] == "ob"){
			$this->view->render('dashboard/_ob_request_for_approval_dt.php',$this->var);
		}else{
			echo "<div class=\"alert alert-error\">Unable to load data.</div>";
		}
	}

	function _count_new_employee_notifications() {
		$user_id = Utilities::decrypt($this->global_user_eid);
		$r = new G_Request();
		$r->setApproverEmployeeId($user_id);
		$data = $r->getPendingForApprovalRequest();
		$return['new_notifications'] = $data['needs_approval'];
		
		$r->setRequestType(G_Request::PREFIX_OVERTIME);
		$data = $r->getPendingForApprovalRequest();
		$return['overtime_for_approval'] = $data['needs_approval'];

		$r->setRequestType(G_Request::PREFIX_LEAVE);
		$data = $r->getPendingForApprovalRequest();
		$return['leave_for_approval'] = $data['needs_approval'];

		$r->setRequestType(G_Request::PREFIX_OFFICIAL_BUSSINESS);
		$data = $r->getPendingForApprovalRequest();
		$return['ob_for_approval'] = $data['needs_approval'];

        
        
        echo json_encode($return);
	}

	function ajax_approve_request() {
		if(isset($_POST['eid'])) {
			$eid = Utilities::decrypt($_POST['eid']);
			$request = G_Request_Finder::findById($eid);
			$data = $request->getPendingForApprovalRequest();

			if($data['needs_approval'] > 0 && in_array($eid,$data['requests'])) {
				if($request){
					$this->var['eid'] = Utilities::encrypt($request->getId());
					$this->var['token'] = Utilities::createFormToken();
					$this->view->render('dashboard/form/approve_request.php', $this->var);	
				}else{
					echo "<div class=\"alert alert-error\">Record not found.</div>";
				}
			}else{
				echo "<div class=\"alert alert-warning\">Cannot approve. Still waiting for lower level approval.</div>";
			}
		}else{
			echo "<div class=\"alert alert-error\">Unable to load data.</div>";
		}
	}

	function ajax_disapprove_request() {
		if(isset($_POST['eid'])) {
			$eid = Utilities::decrypt($_POST['eid']);
			$request = G_Request_Finder::findById($eid);
			$data = $request->getPendingForApprovalRequest();

			if($data['needs_approval'] > 0 && in_array($eid,$data['requests'])) {
				if($request){
					$this->var['eid'] = Utilities::encrypt($request->getId());
					$this->var['token'] = Utilities::createFormToken();
					$this->view->render('dashboard/form/disapprove_request.php', $this->var);	
				}else{
					echo "<div class=\"alert alert-error\">Record not found.</div>";
				}
			}else{
				echo "<div class=\"alert alert-warning\">Cannot disapprove. Still waiting for lower level approval.</div>";
			}
		}else{
			echo "<div class=\"alert alert-error\">Unable to load data.</div>";
		}
	}

	function _approve_request() {
		if(isset($_POST['eid'])) {
			$eid = Utilities::decrypt($_POST['eid']);
			$request = G_Request_Finder::findById($eid);
			if($request){
				$request_type = $request->getRequestType();
				$data[$_POST['eid']] = array(
					"status" => G_Request::APPROVED,
					"remarks" 	=> $_POST['remarks']
				);

				$date = date("Y-m-d H:i:s");
				$request->setActionDate($date);
				$return = $request->updateRequestApproversDataById($data);

				if($return['is_success']) {
					$request->updateRequestStatus();
				}
				$return['message'] = 'Record has been successfully approved';
				$return['request_type'] = $request_type;
			}else{
				$return['is_success'] = false;
				$return['message'] = "Record not found.";
			}
		}else{
			$return['is_success'] = false;
			$return['message'] = "Record not found.";
		}
		echo json_encode($return);
	}

	function _disapprove_request() {
		if(isset($_POST['eid'])) {
			$eid = Utilities::decrypt($_POST['eid']);
			$request = G_Request_Finder::findById($eid);
			if($request){
				$request_type = $request->getRequestType();

				$data[$_POST['eid']] = array(
					"status" => G_Request::DISAPPROVED,
					"remarks" 	=> $_POST['remarks']
				);

				$date = date("Y-m-d H:i:s");
				$request->setActionDate($date);
				$return = $request->updateRequestApproversDataById($data);

				if($return['is_success']) {
					$request->updateRequestStatus();
				}
				$return['message'] = 'Record has been successfully disapproved';
				$return['request_type'] = $request_type;
			}else{
				$return['is_success'] = false;
				$return['message'] = "Record not found.";
			}
		}else{
			$return['is_success'] = false;
			$return['message'] = "Record not found.";
		}
		echo json_encode($return);
	}

	function _with_selected_action() {
		$json['is_success'] = 0;
		$json['message'] = "Please select at least one request.";
		$success_counter = 0;
		if(count($_POST['dtChk']) > 0) {
			foreach($_POST['dtChk'] as $key => $value) {
				$eid = Utilities::decrypt($value);
				$request = G_Request_Finder::findById($eid);
				if($request) {
					$request_type = $request->getRequestType();

					if($_POST['chkAction'] == "approve") {
						$data[$value] = array(
							"status" => G_Request::APPROVED
						);
						$success_counter++;
						$json['message'] = $success_counter . " request(s) has been approved.";
					}elseif($_POST['chkAction'] == "disapprove"){
						$data[$value] = array(
							"status" => G_Request::DISAPPROVED
						);
						$success_counter++;
						$json['message'] = $success_counter . " request(s) has been disapproved.";
					}

					$date = date("Y-m-d H:i:s");
					$request->setActionDate($date);
					$return = $request->updateRequestApproversDataById($data);

					if($return['is_success']) {
						$request->updateRequestStatus();
					}
					$json['request_type'] = $request_type;
					$json['is_success'] = 1;				
				}
			}
		}

		echo json_encode($json);
	}

	function _load_overtime_request_for_approval_dt()
	{
		$user_id = Utilities::decrypt($this->global_user_eid);

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(REQUESTS);
		$dt->setSQL("
			SELECT gr.id, e.employee_code, CONCAT(e.firstname, ' ' ,e.lastname) as employee_name, DATE_FORMAT(eo.date_created,'%b %d, %Y') as date_filed, 
				DATE_FORMAT(eo.date_created,'%h:%i %p') as time_filed, DATE_FORMAT(eo.date,'%b %d, %Y') as date_of_overtime,
				eo.time_in, eo.time_out, eo.reason
			FROM ".REQUESTS." gr 
			LEFT JOIN ".EMPLOYEE." e
				ON gr.requestor_employee_id = e.id 
			LEFT JOIN ".G_EMPLOYEE_OVERTIME." eo
				ON gr.request_id = eo.id
		");		
		$dt->setCountSQL("SELECT COUNT(c.id) as c FROM ".REQUESTS." gr LEFT JOIN ".EMPLOYEE." e ON gr.requestor_employee_id = e.id LEFT JOIN ".G_EMPLOYEE_OVERTIME." eo ON gr.request_id = eo.id");	

		$dt->setCondition("gr.status = ". Model::safeSql(G_Request::PENDING) ." AND gr.approver_employee_id = ". Model::safeSql($user_id) ." AND gr.request_type = ". Model::safeSql(G_Request::PREFIX_OVERTIME));
		$dt->setColumns('employee_code,employee_name,date_filed,time_filed,date_of_overtime,time_in,time_out,reason');	
		$dt->setPreDefineSearch(
			array(				
				"date_filed" => "DATE_FORMAT(eo.date_created,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"time_filed" => "DATE_FORMAT(eo.date_created,'%h:%i %p') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_of_overtime" => "DATE_FORMAT(eo.date,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"employee_name" => "e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelectedOt();\" value=\"e_id\"></div>',
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-ot-edit-request\" ><i class=\"icon-pencil\"></i> Edit </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-ot-approve-request\" ><i class=\"icon-ok\"></i> Approve </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-ot-disapprove-request\" ><i class=\"icon-remove\"></i> Disapprove </a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function _load_leave_request_for_approval_dt()
	{
		$user_id = Utilities::decrypt($this->global_user_eid);

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(REQUESTS);
		$dt->setSQL("
			SELECT gr.id, e.employee_code, CONCAT(e.firstname, ' ' ,e.lastname) as employee_name, DATE_FORMAT(elr.date_applied,'%b %d, %Y') as date_filed, elr.time_applied,
				DATE_FORMAT(elr.date_start,'%b %d, %Y') as date_start, DATE_FORMAT(elr.date_end,'%b %d, %Y') as date_end, l.name as l_name, elr.leave_comments
			FROM ".REQUESTS." gr 
			LEFT JOIN ".EMPLOYEE." e
				ON gr.requestor_employee_id = e.id 
			LEFT JOIN ".G_EMPLOYEE_LEAVE_REQUEST." elr
				ON gr.request_id = elr.id
			LEFT JOIN ". G_LEAVE ." l 
				ON elr.leave_id = l.id
		");		
		$dt->setCountSQL("SELECT COUNT(c.id) as c FROM ".REQUESTS." gr LEFT JOIN ".EMPLOYEE." e ON gr.requestor_employee_id = e.id LEFT JOIN ".G_EMPLOYEE_LEAVE_REQUEST." elr ON gr.request_id = elr.id LEFT JOIN ". G_LEAVE ." l ON elr.leave_id = l.id");	

		$dt->setCondition("gr.status = ". Model::safeSql(G_Request::PENDING) ." AND gr.approver_employee_id = ". Model::safeSql($user_id) ." AND gr.request_type = ". Model::safeSql(G_Request::PREFIX_LEAVE));
		$dt->setColumns('employee_code,employee_name,date_filed,time_applied,date_start,date_end,l_name,leave_comments');	
		$dt->setPreDefineSearch(
			array(				
				"date_filed" => "DATE_FORMAT(elr.date_applied,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_start" => "DATE_FORMAT(elr.date_start,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_end" => "DATE_FORMAT(elr.date_end,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"employee_name" => "e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelectedLeave();\" value=\"e_id\"></div>',
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-ot-approve-request\" ><i class=\"icon-ok\"></i> Approve </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-ot-disapprove-request\" ><i class=\"icon-remove\"></i> Disapprove </a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function _load_ob_request_for_approval_dt()
	{
		$user_id = Utilities::decrypt($this->global_user_eid);

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(REQUESTS);
		$dt->setSQL("
			SELECT gr.id, e.employee_code, CONCAT(e.firstname, ' ' ,e.lastname) as employee_name, DATE_FORMAT(eob.date_applied,'%b %d, %Y') as date_filed, DATE_FORMAT(eob.date_applied,'%h:%i %p') as time_filed,
				DATE_FORMAT(eob.date_start,'%b %d, %Y') as date_start, DATE_FORMAT(eob.date_end,'%b %d, %Y') as date_end, eob.comments
			FROM ".REQUESTS." gr 
			LEFT JOIN ".EMPLOYEE." e
				ON gr.requestor_employee_id = e.id 
			LEFT JOIN ".G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST." eob
				ON gr.request_id = eob.id
		");		
		$dt->setCountSQL("SELECT COUNT(c.id) as c FROM ".REQUESTS." gr LEFT JOIN ".EMPLOYEE." e ON gr.requestor_employee_id = e.id LEFT JOIN ".G_EMPLOYEE_LEAVE_REQUEST." elr ON gr.request_id = elr.id LEFT JOIN ". G_LEAVE ." l ON elr.leave_id = l.id");	

		$dt->setCondition("gr.status = ". Model::safeSql(G_Request::PENDING) ." AND gr.approver_employee_id = ". Model::safeSql($user_id) ." AND gr.request_type = ". Model::safeSql(G_Request::PREFIX_OFFICIAL_BUSSINESS));
		$dt->setColumns('employee_code,employee_name,date_filed,time_filed,date_start,date_end,comments');	
		$dt->setPreDefineSearch(
			array(				
				"date_filed" => "DATE_FORMAT(eob.date_applied,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"time_filed" => "DATE_FORMAT(eob.date_applied,'%h:%i %p') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_start" => "DATE_FORMAT(eob.date_start,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_end" => "DATE_FORMAT(eob.date_end,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"employee_name" => "e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'"
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelectedOb();\" value=\"e_id\"></div>',
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-ot-approve-request\" ><i class=\"icon-ok\"></i> Approve </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-ot-disapprove-request\" ><i class=\"icon-remove\"></i> Disapprove </a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}
	
}
?>