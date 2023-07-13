<?php
class Leave_Controller extends Controller
{
	function __construct() {
		parent::__construct();
		$this->isLogin();
		$this->sprintHdrMenu(G_Sprint_Modules::EMPLOYEE, 'employee_leave');
		$this->validatePermission(G_Sprint_Modules::EMPLOYEE,'employee_leave','');
		Loader::appStyle('style.css');
		Loader::appMainScript('leave.js');
		Loader::appMainScript('employee_request.js');
		Loader::appMainScript('employee_request_base.js');
	}
	
	function index() {
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainBootStrapDropDown();

		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');

		$btn_file_leave_config = array(
    		'href' 					=> 'javascript:void(0);',
    		'id' 					=> 'btn-file-leave',
    		'class' 				=> 'add_button',
    		'icon' 					=> '<i class="icon-plus "></i>',
    		'caption' 				=> '<b>File Leave</b>'
    		);

        $this->var['btn_file_request'] = G_Button_Builder::createAnchorTag($btn_file_leave_config);

		$this->var['page_title'] = 'Leave';
		$this->view->setTemplate('template_employee_portal.php');
		$this->view->render('leave/index.php',$this->var);
	}

	function ajax_file_leave() {
		$user_id = Utilities::decrypt($this->global_user_eid);
		$gra = new G_Request_Approver();
		$gra->setEmployeeId($user_id);
		$approvers = $gra->getEmployeeRequestApprovers();
		
		$this->var['approvers'] = $approvers;
		$this->var['leaves']     = $leave = G_Leave_Finder::findAllIsNotArchive();	
		$this->var['leave_available'] = G_Employee_Leave_Available_Finder::findByEmployeeId(Utilities::decrypt($this->global_user_eid));	
		$this->var['token'] = Utilities::createFormToken();
		$this->view->render('leave/form/file_leave.php', $this->var);	
	}

	function ajax_view_leave_approvers() {			
		if($_POST){
			$user_id = Utilities::decrypt($this->global_user_eid);
			$request_id = Utilities::decrypt($_POST['request_id']);
			$request_type = G_Request::PREFIX_LEAVE;
			$request_approvers = G_Request_Finder::findByRequestorIdAndRequestIdAndRequestType($user_id,$request_id,$request_type);

			if($request_approvers) {
				$this->var['request_approvers'] = $request_approvers;
				$this->var['token'] = Utilities::createFormToken();
				$this->view->render('leave/form/view_leave_approvers.php', $this->var);	
			}else{
				echo "<div class=\"alert alert-error\">Record not found</div>";
			}
			
		}else{
			echo "<div class=\"alert alert-error\">Unable to load data</div>";
		}
		
	}

	function _ajax_load_available_leave_credit() {
		$user_id = Utilities::decrypt($this->global_user_eid);
		$leave_id = Utilities::decrypt($_POST['eid']);
		$this->var['leave_credit'] = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveId($user_id,$leave_id);	
		$this->view->render('leave/form/_available_leave_credit.php', $this->var);	
	}

	function _load_data() {
		if($_GET['action'] == "pending") {
			$this->view->render('leave/_leave_pending_dt.php',$this->var);
		}else if($_GET['action'] == "approved"){
			$this->view->render('leave/_leave_approved_dt.php',$this->var);
		}else if($_GET['action'] == "disapproved"){
			$this->view->render('leave/_leave_disapproved_dt.php',$this->var);
		}else{
			echo "<div class=\"alert alert-error\">Unable to load data</div>";
		}
	}

	function _file_leave() {
		if(Utilities::isFormTokenValid($_POST['token'])) {    
			$user_id 	  = Utilities::decrypt($this->global_user_eid); 
			
			$ai = new G_Allowed_Ip();
			$ai->setEmployeeId($user_id);
			$is_allowed = $ai->validateUserIp();
			if(!$is_allowed) {
				$json['is_success'] = false;
				$json['is_saved'] 	= false;
				$json['message']    = "Your IP is not allowed to file request.";
				$json['token'] 		= Utilities::createFormToken();
        		echo json_encode($json);
				exit();
			}

			$company_structure_id = Utilities::decrypt($this->global_user_ecompany_structure_id);     
	        $leave_id     = Utilities::decrypt($_POST['leave_id']);
	        $date_start   = date("Y-m-d",strtotime($_POST['date_start']));
	        $date_end     = date("Y-m-d",strtotime($_POST['date_end']));
	        $date_applied = date("Y-m-d");
	        $time_applied = date("H:i:s");
	        $comment 	  = $_POST['reason'];	        
	        $status       = G_Employee_Leave_Request::PENDING;
	        $created_by   = G_Employee_Helper::getEmployeeNameById($user_id);

	        $is_paid      = G_Employee_Leave_Request::NO;
			$leave_id = Utilities::decrypt($_POST['leave_id']);
			$leave_credit = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveId($user_id,$leave_id);	
			if($leave_credit) {
				if($leave_credit->getNoOfDaysAvailable() > 0 ) {
					$is_paid = G_Employee_Leave_Request::YES;
				}
			}

	        if( $_POST['start_halfday'] ){
	        	$is_halfday = G_Employee_Leave_Request::YES;
	        }else{
	        	$is_halfday = G_Employee_Leave_Request::NO;
	        }

	        $el = new G_Employee_Leave_Request();        
			$el->setCompanyStructureId($company_structure_id);
			$el->setEmployeeId($user_id);
			$el->setLeaveId($leave_id);
			$el->setDateApplied($date_applied);
	        $el->setTimeApplied($time_applied);
			$el->setDateStart($date_start);
			$el->setDateEnd($date_end);
			$el->setApplyHalfDayDateStart($is_halfday);		
			$el->setLeaveComments($comment);
			$el->setIsApproved($status);
			$el->setIsPaid($is_paid);
			$el->setCreatedBy($created_by);			
			$json = $el->saveRequest();

			if( $json['is_success'] ){
				$el->addLeaveToAttendance($e);

				$request_id = $json['last_insert_id'];
				/*$requests = array();
				for($i = 1; $i <= $_POST['no_of_level']; $i++) {
					$approver_id = Utilities::decrypt($_POST['approver_id_'.$i]);
					$e = G_Employee_Finder::findById($approver_id);
					$approver_name = "";
					if($e){
						$approver_name = $e->getLastName() .", ". $e->getFirstName() ." ". $e->getMiddleName();
					}
					$requests[] = array(
						"requestor_employee_id" 	=> Model::safeSql($user_id),
						"request_id" 				=> Model::safeSql($request_id),
						"request_type" 				=> Model::safeSql(G_Request::PREFIX_LEAVE),
						"approver_employee_id" 		=> Model::safeSql($approver_id),
						"approver_name"				=> Model::safeSql($approver_name),
						"status" 					=> Model::safeSql(G_Request::PENDING),
						"is_lock" 					=> Model::safeSql(G_Request::NO),
						"remarks" 					=> Model::safeSql(''),
						"action_date" 				=> Model::safeSql('')
					);
				}

				G_Request::bulkInsertRequests($requests);*/
				
				if($request_id) {
					$request_type = G_Request::PREFIX_LEAVE;
					$approvers    = $_POST['approvers'];
					$r = new G_Request();
			        $r->setRequestorEmployeeId($user_id);
			        $r->setRequestId($request_id);
			        $r->setRequestType($request_type);
			        $r->saveEmployeeRequest($approvers); //Save request approvers
		    	}

				$json['is_saved'] = true;
				$json['message']    = "Leave has been successfully added";
			}else{
				$json['is_saved'] = false;
			}

        }else{
        	$json['is_saved'] = false;
        	$json['message']    = "Invalid form entries";
        }

        $json['token'] = Utilities::createFormToken();
        echo json_encode($json);
	}

	function _load_leave_pending_dt()
	{
		$user_id = Utilities::decrypt($this->global_user_eid);

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LEAVE_REQUEST);
		$dt->setSQL("
			SELECT elr.id, DATE_FORMAT(elr.date_applied,'%b %d, %Y') as date_filed, elr.time_applied, 
				l.name, DATE_FORMAT(elr.date_start,'%b %d, %Y') as date_start, DATE_FORMAT(elr.date_end,'%b %d, %Y') as date_end, elr.leave_comments, 
				(SELECT CONCAT('for approval of ', approver_name) FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_LEAVE)." AND request_id = elr.id AND status = ".Model::safeSql(G_Request::PENDING)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id ASC LIMIT 1) as status
			FROM ". G_EMPLOYEE_LEAVE_REQUEST ." elr 
			LEFT JOIN ". G_LEAVE ." l 
				ON elr.leave_id = l.id	
		");		
		$dt->setCountSQL("SELECT COUNT(c.id) as c FROM " . G_EMPLOYEE_LEAVE_REQUEST . " c LEFT JOIN ". G_LEAVE ." l ON c.leave_id = l.id	");	
		

		$dt->setCondition("elr.is_approved = ". Model::safeSql(G_Employee_Leave_Request::PENDING) ." AND elr.employee_id = ". Model::safeSql($user_id) ." AND elr.is_archive = ". Model::safeSql(G_Employee_Leave_Request::NO));
		$dt->setColumns('date_filed,time_applied,name,date_start,date_end,leave_comments,status');	
		$dt->setPreDefineSearch(
			array(				
				"date_filed" => "DATE_FORMAT(elr.date_applied,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_start" => "DATE_FORMAT(elr.date_start,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_end" => "DATE_FORMAT(elr.date_end,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"status" => "(SELECT CONCAT('for approval of ', approver_name) FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_LEAVE)." AND request_id = elr.id AND status = ".Model::safeSql(G_Request::PENDING)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id ASC LIMIT 1) LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' "
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-view-approver\" ><i class=\"icon-list\"></i> View Approver(s) </a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function _load_leave_approved_dt()
	{
		$user_id = Utilities::decrypt($this->global_user_eid);

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LEAVE_REQUEST);
		$dt->setSQL("
			SELECT elr.id, DATE_FORMAT(elr.date_applied,'%b %d, %Y') as date_filed, elr.time_applied, 
				l.name, DATE_FORMAT(elr.date_start,'%b %d, %Y') as date_start, DATE_FORMAT(elr.date_end,'%b %d, %Y') as date_end, elr.leave_comments, 
				(SELECT DATE_FORMAT(action_date,'%b %d, %Y') FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_LEAVE)." AND request_id = elr.id AND status = ".Model::safeSql(G_Request::APPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) as date_approved
			FROM ". G_EMPLOYEE_LEAVE_REQUEST ." elr 
			LEFT JOIN ". G_LEAVE ." l 
				ON elr.leave_id = l.id	
		");		
		$dt->setCountSQL("SELECT COUNT(c.id) as c FROM " . G_EMPLOYEE_LEAVE_REQUEST . " c LEFT JOIN ". G_LEAVE ." l ON c.leave_id = l.id	");	
		

		$dt->setCondition("elr.is_approved = ". Model::safeSql(G_Employee_Leave_Request::APPROVED) ." AND elr.employee_id = ". Model::safeSql($user_id) ." AND elr.is_archive = ". Model::safeSql(G_Employee_Leave_Request::NO));
		$dt->setColumns('date_filed,time_applied,name,date_start,date_end,leave_comments,date_approved');	
		$dt->setPreDefineSearch(
			array(				
				"date_filed" => "DATE_FORMAT(elr.date_applied,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_start" => "DATE_FORMAT(elr.date_start,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_end" => "DATE_FORMAT(elr.date_end,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_approved" => "(SELECT DATE_FORMAT(action_date,'%b %d, %Y') FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_LEAVE)." AND request_id = elr.id AND status = ".Model::safeSql(G_Request::APPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' "
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-view-approver\" ><i class=\"icon-list\"></i> View Approver(s) </a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function _load_leave_disapproved_dt()
	{
		$user_id = Utilities::decrypt($this->global_user_eid);

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_LEAVE_REQUEST);
		$dt->setSQL("
			SELECT elr.id, DATE_FORMAT(elr.date_applied,'%b %d, %Y') as date_filed, elr.time_applied, 
				l.name, DATE_FORMAT(elr.date_start,'%b %d, %Y') as date_start, DATE_FORMAT(elr.date_end,'%b %d, %Y') as date_end, elr.leave_comments, 
				(SELECT DATE_FORMAT(action_date,'%b %d, %Y') FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_LEAVE)." AND request_id = elr.id AND status = ".Model::safeSql(G_Request::DISAPPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) as date_disapproved, 
				(SELECT remarks FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_LEAVE)." AND request_id = elr.id AND status = ".Model::safeSql(G_Request::DISAPPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) as reason_for_disapproval
			FROM ". G_EMPLOYEE_LEAVE_REQUEST ." elr 
			LEFT JOIN ". G_LEAVE ." l 
				ON elr.leave_id = l.id	
		");		
		$dt->setCountSQL("SELECT COUNT(c.id) as c FROM " . G_EMPLOYEE_LEAVE_REQUEST . " c LEFT JOIN ". G_LEAVE ." l ON c.leave_id = l.id	");	
		

		$dt->setCondition("elr.is_approved = ". Model::safeSql(G_Employee_Leave_Request::DISAPPROVED) ." AND elr.employee_id = ". Model::safeSql($user_id) ." AND elr.is_archive = ". Model::safeSql(G_Employee_Leave_Request::NO));
		$dt->setColumns('date_filed,time_applied,name,date_start,date_end,leave_comments,date_disapproved,reason_for_disapproval');	
		$dt->setPreDefineSearch(
			array(				
				"date_filed" => "DATE_FORMAT(elr.date_applied,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_start" => "DATE_FORMAT(elr.date_start,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_end" => "DATE_FORMAT(elr.date_end,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_disapproved" => "(SELECT DATE_FORMAT(action_date,'%b %d, %Y') FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_LEAVE)." AND request_id = elr.id AND status = ".Model::safeSql(G_Request::DISAPPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"reason_for_disapproval" => "(SELECT remarks FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_LEAVE)." AND request_id = elr.id AND status = ".Model::safeSql(G_Request::DISAPPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' "
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',
						2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-view-approver\" ><i class=\"icon-list\"></i> View Approver(s) </a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}
}
?>