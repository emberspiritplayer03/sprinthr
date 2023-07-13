<?php
class Ob_Controller extends Controller
{
	function __construct() {
		parent::__construct();
		$this->isLogin();
		$this->sprintHdrMenu(G_Sprint_Modules::EMPLOYEE, 'employee_official_business');
		$this->validatePermission(G_Sprint_Modules::EMPLOYEE,'employee_official_business','');
		Loader::appStyle('style.css');
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

		$btn_file_ob_config = array(
    		'href' 					=> 'javascript:void(0);',
    		'id' 					=> 'btn-file-ob',
    		'class' 				=> 'add_button',
    		'icon' 					=> '<i class="icon-plus "></i>',
    		'caption' 				=> '<b>File Official Business</b>'
    		);

        $this->var['btn_file_request'] = G_Button_Builder::createAnchorTag($btn_file_ob_config);

		$this->var['page_title'] = 'Official Business';
		$this->view->setTemplate('template_employee_portal.php');
		$this->view->render('ob/index.php',$this->var);
	}

	function ajax_file_ob() {	
		$user_id = Utilities::decrypt($this->global_user_eid);
		$gra = new G_Request_Approver();
		$gra->setEmployeeId($user_id);
		$approvers = $gra->getEmployeeRequestApprovers();
		
		$this->var['approvers'] = $approvers;
		$this->var['token'] = Utilities::createFormToken();
		$this->view->render('ob/form/file_ob.php', $this->var);	
	}

	function ajax_view_ob_approvers() {			
		if($_POST){
			$user_id = Utilities::decrypt($this->global_user_eid);
			$request_id = Utilities::decrypt($_POST['request_id']);
			$request_type = G_Request::PREFIX_OFFICIAL_BUSSINESS;
			$request_approvers = G_Request_Finder::findByRequestorIdAndRequestIdAndRequestType($user_id,$request_id,$request_type);

			if($request_approvers) {
				$this->var['request_approvers'] = $request_approvers;
				$this->var['token'] = Utilities::createFormToken();
				$this->view->render('ob/form/view_ob_approvers.php', $this->var);	
			}else{
				echo "<div class=\"alert alert-error\">Record not found</div>";
			}
			
		}else{
			echo "<div class=\"alert alert-error\">Unable to load data</div>";
		}
		
	}

	function _load_data() {
		if($_GET['action'] == "pending") {
			$this->view->render('ob/_ob_pending_dt.php',$this->var);
		}else if($_GET['action'] == "approved"){
			$this->view->render('ob/_ob_approved_dt.php',$this->var);
		}else if($_GET['action'] == "disapproved"){
			$this->view->render('ob/_ob_disapproved_dt.php',$this->var);
		}else{
			echo "<div class=\"alert alert-error\">Unable to load data</div>";
		}
	}

	function _file_ob() {
		if(Utilities::isFormTokenValid($_POST['token'])) {

			date_default_timezone_set('Asia/Manila');
			$current_date = date("Y-m-d H:i:s");
			$user_id = Utilities::decrypt($this->global_user_eid);
			
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

			$request_id = G_Employee_Official_Business_Request_Helper::addNewRequest($user_id, $current_date , $_POST['ob_date_from'], $_POST['ob_date_to'], $_POST['reason']);

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
					"request_type" 				=> Model::safeSql(G_Request::PREFIX_OFFICIAL_BUSSINESS),
					"approver_employee_id" 		=> Model::safeSql($approver_id),
					"approver_name"				=> Model::safeSql($approver_name),
					"status" 					=> Model::safeSql(G_Request::PENDING),
					"is_lock" 					=> Model::safeSql(G_Request::NO),
					"remarks" 					=> Model::safeSql(''),
					"action_date" 				=> Model::safeSql('')
				);
			}

			if($request_id) {
				G_Request::bulkInsertRequests($requests);
			}*/

			if( $request_id ){
				$request_type = G_Request::PREFIX_OFFICIAL_BUSSINESS;
				$approvers    = $_POST['approvers'];
				$r = new G_Request();
		        $r->setRequestorEmployeeId($user_id);
		        $r->setRequestId($request_id);
		        $r->setRequestType($request_type);
		        $r->saveEmployeeRequest($approvers); //Save request approvers
			}
			

			$return['is_saved'] = true;
        	$return['message'] = 'Official Business has been successfully added';
		} else {
			$return['message']  = 'Error: Invalid Token. Request will not be saved.';
			$return['is_saved'] = false;
		}
		$return['token'] = Utilities::createFormToken();
		echo json_encode($return);
	}

	function _load_ob_pending_dt()
	{
		$user_id = Utilities::decrypt($this->global_user_eid);

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST);
		$dt->setSQL("
			SELECT eob.id, DATE_FORMAT(eob.date_applied,'%b %d, %Y') as date_filed, DATE_FORMAT(eob.date_applied,'%h:%i %p') as time_filed, 
				DATE_FORMAT(eob.date_start,'%b %d, %Y') as date_start, DATE_FORMAT(eob.date_end,'%b %d, %Y') as date_end, eob.comments, 
				(SELECT CONCAT('for approval of ', approver_name) FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_OFFICIAL_BUSSINESS)." AND request_id = eob.id AND status = ".Model::safeSql(G_Request::PENDING)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id ASC LIMIT 1) as status
			FROM ". G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ." eob			
		");		
		$dt->setCountSQL("SELECT COUNT(c.id) as c FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . " c");	
		

		$dt->setCondition("eob.is_approved = ". Model::safeSql(Employee_Official_Business_Request::PENDING) ." AND eob.employee_id = ". Model::safeSql($user_id) ." AND eob.is_archive = ". Model::safeSql(Employee_Official_Business_Request::NO));
		$dt->setColumns('date_filed,time_filed,date_start,date_end,comments,status');	
		$dt->setPreDefineSearch(
			array(				
				"date_filed" => "DATE_FORMAT(eob.date_applied,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"time_filed" => "DATE_FORMAT(eob.date_applied,'%h:%i %p') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_start" => "DATE_FORMAT(eob.date_start,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_end" => "DATE_FORMAT(eob.date_end,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"status" => "(SELECT CONCAT('for approval of ', approver_name) FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_OFFICIAL_BUSSINESS)." AND request_id = eob.id AND status = ".Model::safeSql(G_Request::PENDING)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id ASC LIMIT 1) LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' "
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

	function _load_ob_approved_dt()
	{
		$user_id = Utilities::decrypt($this->global_user_eid);

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST);
		$dt->setSQL("
			SELECT eob.id, DATE_FORMAT(eob.date_applied,'%b %d, %Y') as date_filed, DATE_FORMAT(eob.date_applied,'%h:%i %p') as time_filed, 
				DATE_FORMAT(eob.date_start,'%b %d, %Y') as date_start, DATE_FORMAT(eob.date_end,'%b %d, %Y') as date_end, eob.comments, 
				(SELECT DATE_FORMAT(action_date,'%b %d, %Y') FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_OFFICIAL_BUSSINESS)." AND request_id = eob.id AND status = ".Model::safeSql(G_Request::APPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) as date_approved
			FROM ". G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ." eob			
		");		
		$dt->setCountSQL("SELECT COUNT(c.id) as c FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . " c");	
		

		$dt->setCondition("eob.is_approved = ". Model::safeSql(Employee_Official_Business_Request::APPROVED) ." AND eob.employee_id = ". Model::safeSql($user_id) ." AND eob.is_archive = ". Model::safeSql(Employee_Official_Business_Request::NO));
		$dt->setColumns('date_filed,time_filed,date_start,date_end,comments,date_approved');	
		$dt->setPreDefineSearch(
			array(				
				"date_filed" => "DATE_FORMAT(eob.date_applied,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"time_filed" => "DATE_FORMAT(eob.date_applied,'%h:%i %p') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_start" => "DATE_FORMAT(eob.date_start,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_end" => "DATE_FORMAT(eob.date_end,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_approved" => "(SELECT DATE_FORMAT(action_date,'%b %d, %Y') FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_OFFICIAL_BUSSINESS)." AND request_id = eob.id AND status = ".Model::safeSql(G_Request::APPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' "
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

	function _load_ob_disapproved_dt()
	{
		$user_id = Utilities::decrypt($this->global_user_eid);

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST);
		$dt->setSQL("
			SELECT eob.id, DATE_FORMAT(eob.date_applied,'%b %d, %Y') as date_filed, DATE_FORMAT(eob.date_applied,'%h:%i %p') as time_filed, 
				DATE_FORMAT(eob.date_start,'%b %d, %Y') as date_start, DATE_FORMAT(eob.date_end,'%b %d, %Y') as date_end, eob.comments, 
				(SELECT DATE_FORMAT(action_date,'%b %d, %Y') FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_OFFICIAL_BUSSINESS)." AND request_id = eob.id AND status = ".Model::safeSql(G_Request::DISAPPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) as date_disapproved, 
				(SELECT remarks FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_OFFICIAL_BUSSINESS)." AND request_id = eob.id AND status = ".Model::safeSql(G_Request::DISAPPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) as reason_for_disapproval
			FROM ". G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ." eob			
		");		
		$dt->setCountSQL("SELECT COUNT(c.id) as c FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . " c");	
		

		$dt->setCondition("eob.is_approved = ". Model::safeSql(Employee_Official_Business_Request::DISAPPROVED) ." AND eob.employee_id = ". Model::safeSql($user_id) ." AND eob.is_archive = ". Model::safeSql(Employee_Official_Business_Request::NO));
		$dt->setColumns('date_filed,time_filed,date_start,date_end,comments,date_disapproved,reason_for_disapproval');	
		$dt->setPreDefineSearch(
			array(				
				"date_filed" => "DATE_FORMAT(eob.date_applied,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"time_filed" => "DATE_FORMAT(eob.date_applied,'%h:%i %p') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_start" => "DATE_FORMAT(eob.date_start,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_end" => "DATE_FORMAT(eob.date_end,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_disapproved" => "(SELECT DATE_FORMAT(action_date,'%b %d, %Y') FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_OFFICIAL_BUSSINESS)." AND request_id = eob.id AND status = ".Model::safeSql(G_Request::DISAPPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"reason_for_disapproval" => "(SELECT remarks FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_OFFICIAL_BUSSINESS)." AND request_id = eob.id AND status = ".Model::safeSql(G_Request::DISAPPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' "
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