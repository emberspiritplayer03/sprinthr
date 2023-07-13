<?php
class Overtime_Controller extends Controller
{
	function __construct() {
		parent::__construct();
		$this->isLogin();
		$this->sprintHdrMenu(G_Sprint_Modules::EMPLOYEE, 'employee_overtime');
		$this->validatePermission(G_Sprint_Modules::EMPLOYEE,'employee_overtime','');
		Loader::appStyle('style.css');
		Loader::appMainScript('employee_request.js');
		Loader::appMainScript('employee_request_base.js');
	}
	
	function index() {	
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();

		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');

		Jquery::loadMainBootStrapDropDown();		
		$btn_file_overtime_config = array(
    		'href' 					=> 'javascript:void(0);',
    		'id' 					=> 'btn-file-overtime',
    		'class' 				=> 'add_button',
    		'icon' 					=> '<i class="icon-plus "></i>',
    		'caption' 				=> '<b>File Overtime</b>'
    		);

        $this->var['btn_file_request'] = G_Button_Builder::createAnchorTag($btn_file_overtime_config);

		$this->var['page_title'] = 'Overtime';
		$this->view->setTemplate('template_employee_portal.php');
		$this->view->render('overtime/index.php',$this->var);
	}

	function ajax_file_overtime() {	
		$user_id = Utilities::decrypt($this->global_user_eid);
		$gra = new G_Request_Approver();
		$gra->setEmployeeId($user_id);
		$approvers = $gra->getEmployeeRequestApprovers();
		
		$this->var['approvers'] = $approvers;
		$this->var['token'] = Utilities::createFormToken();
		$this->view->render('overtime/form/file_overtime.php', $this->var);	
	}

	function ajax_edit_overtime_form() {    
		$id = Utilities::decrypt($_GET['eid']);
		$o  = G_Overtime_Finder::findById($id);
        if ($o) {        
        	$this->var['eid']      = Utilities::encrypt($o->getId());
        	$this->var['date'] 	   = $o->getDate();
            $this->var['time_in']  = Tools::timeFormat($o->getTimeIn());
            $this->var['time_out'] = Tools::timeFormat($o->getTimeOut());
            $this->var['date_string'] = Tools::convertDateFormat($o->getDate());
            $this->var['action']      = url('overtime/_edit_overtime');
            $this->view->render('overtime/form/ajax_edit_overtime_form.php',$this->var);
        }else{
        	echo "Record not found";
        }
    }

	function ajax_view_overtime_approvers() {			
		if($_POST){
			$user_id = Utilities::decrypt($this->global_user_eid);
			$request_id = Utilities::decrypt($_POST['request_id']);
			$request_type = G_Request::PREFIX_OVERTIME;
			$request_approvers = G_Request_Finder::findByRequestorIdAndRequestIdAndRequestType($user_id,$request_id,$request_type);

			if($request_approvers) {
				$this->var['request_approvers'] = $request_approvers;
				$this->var['token'] = Utilities::createFormToken();
				$this->view->render('overtime/form/view_overtime_approvers.php', $this->var);	
			}else{
				echo "<div class=\"alert alert-error\">Record not found</div>";
			}
			
		}else{
			echo "<div class=\"alert alert-error\">Unable to load data</div>";
		}
		
	}

	function _load_data() {
		if($_GET['action'] == "pending") {
			$this->view->render('overtime/_overtime_pending_dt.php',$this->var);
		}else if($_GET['action'] == "approved"){
			$this->view->render('overtime/_overtime_approved_dt.php',$this->var);
		}else if($_GET['action'] == "disapproved"){
			$this->view->render('overtime/_overtime_disapproved_dt.php',$this->var);
		}else{
			echo "<div class=\"alert alert-error\">Unable to load data</div>";
		}
	}

	function _file_overtime() {

		$date_start = $_POST['date_of_overtime'];
		$end_date = $_POST['date_of_overtime'];
        $time_in = Tools::convert12To24Hour($_POST['start_time']);
        $time_out = Tools::convert12To24Hour($_POST['end_time']);
        $user_id = Utilities::decrypt($this->global_user_eid);

        $return['is_saved'] = true;
        $return['message'] = 'Overtime has been successfully added';

			if(Utilities::isFormTokenValid($_POST['token'])) {
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
				
				$settings_request = G_Settings_Request_Finder::findByType(Settings_Request::OT);
				$employee		  = G_Employee_Finder::findById($user_id);

                $a = G_Attendance_Finder::findByEmployeeAndDate($employee, $date_start);
                if($a) {
                	$t = $a->getTimesheet();
                	if($t && $t->getDateOut() != "") {
                		$end_date = $t->getDateOut();
                	}
                }

                if (!Tools::isTime1LessThanTime2($time_in, $time_out) && date("a",strtotime($time_in)) == date("a",strtotime($time_out)) ) {
                    $return['message']  = 'Time start ('. Tools::convert24To12Hour($time_in) .') must be less than time end ('. Tools::convert24To12Hour($time_out) .')';
                    $return['is_saved'] = false;
                } else if ($a) {
                    $t = $a->getTimesheet();                     
                    if ($t && $t->getTimeOut() != '') {
                    	$timesheet_time_out = Tools::convert12To24Hour($t->getTimeOut());

                    	if( strtotime($t->getDateIn()) == strtotime($t->getDateOut()) ) {
                        	if (!Tools::isTime1LessThanTime2($time_out,$timesheet_time_out)) {                           	     	
	                            $return['message'] = 'Overtime must not exceed the actual time out. <br/>The actual time out on that date was <b>'. Tools::convert24To12Hour($t->getTimeOut()) .'</b>';
	                            $return['is_saved'] = false;
	                        }
                    	}elseif( strtotime($t->getDateIn()) < strtotime($t->getDateOut()) ) {
                    		$stamp = date("a",strtotime($time_out));
                    		if ( strtotime($time_out) > strtotime($timesheet_time_out) && $stamp != "pm" ) {                           	     	
	                            $return['message'] = 'Overtime must not exceed the actual time out. <br/>The actual time out on that date was <b>'. Tools::convert24To12Hour($t->getTimeOut()) .'</b>';
	                            $return['is_saved'] = false;
	                        }
                    	}

                        
                    }else{
	                    $return['message'] = 'You have no timeout on the selected day. Cannot file overtime.';
	                    $return['is_saved'] = false;
	                }
                } else {
                	$return['message'] = 'You have no attendace on the selected day. Cannot file overtime.';
		            $return['is_saved'] = false;
                }
			} else {
				$return['message']  = 'Error: Invalid Token. Request will not be saved.';
				$return['is_saved'] = false;
			}

            if ($return['is_saved']) {
				    $overtime = G_Overtime_Finder::findByEmployeeAndDate($employee, $date_start);
					if(!$overtime) {
					    $overtime = new G_Overtime();
					}
					$overtime->setDate($date_start);
					$overtime->setTimeIn($time_in);
					$overtime->setTimeOut($time_out);
					$overtime->setDateIn($date_start);
					$overtime->setDateOut($end_date);
					$overtime->setEmployeeId($user_id);
					$overtime->setReason(Tools::stringReplace($_POST['reason']));
                    $overtime->setStatus(G_Employee_Overtime_Request::PENDING);
                    $overtime->setDateCreated(date("Y-m-d H:i:s"));
					$request_id = $overtime->save();

    				G_Attendance_Helper::updateAttendance($employee, $date_start);

					if($request_id) {
	    				$request_type = G_Request::PREFIX_OVERTIME;
	    				$approvers    = $_POST['approvers'];
	    				$r = new G_Request();
				        $r->setRequestorEmployeeId($user_id);
				        $r->setRequestId($request_id);
				        $r->setRequestType($request_type);
				        $r->saveEmployeeRequest($approvers); //Save request approvers
			    	}
            }

		$token = Utilities::createFormToken();
		$return['token'] = $token;
		echo json_encode($return);
	}

	function _edit_overtime() {
        $eid       = $_POST['eid'];
        $date      = $_POST['date'];
        $time_in   = date('H:i:s', strtotime($_POST['time_in']));
        $time_out  = date('H:i:s', strtotime($_POST['time_out']));
        $is_saved  = true;
        $message   = 'Cannot update record';        

        if( !empty($eid) ){
        	$o = G_Overtime_Finder::findById(Utilities::decrypt($eid));
        	if( !empty($o) ){
    			$employee_id = $o->getEmployeeId();
        		$e = G_Employee_Finder::findById($employee_id);
        		if( !empty($e) ){
        			$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);	
        			if($a) {
	                	$t = $a->getTimesheet();
	                	if($t && $t->getDateOut() != "") {
	                		$end_date = $t->getDateOut();
	                	}
	                }

	                if (!Tools::isTime1LessThanTime2($time_in, $time_out) && date("a",strtotime($time_in)) == date("a",strtotime($time_out)) ) {
	                    $message  = 'Time start ('. Tools::convert24To12Hour($time_in) .') must be less than time end ('. Tools::convert24To12Hour($time_out) .')';
	                    $is_saved = false;
	                } else if ($a) {
	                    $t = $a->getTimesheet();                     
	                    if ($t && $t->getTimeOut() != '') {
	                    	$timesheet_time_out = Tools::convert12To24Hour($t->getTimeOut());

	                    	if( strtotime($t->getDateIn()) == strtotime($t->getDateOut()) ) {
	                        	if (!Tools::isTime1LessThanTime2($time_out,$timesheet_time_out)) {                           	     	
		                            $message = 'Overtime must not exceed the actual time out. <br/>The actual time out on that date was <b>'. Tools::convert24To12Hour($t->getTimeOut()) .'</b>';
		                            $is_saved = false;
		                        }
	                    	}elseif( strtotime($t->getDateIn()) < strtotime($t->getDateOut()) ) {
	                    		$stamp = date("a",strtotime($time_out));
	                    		if ( strtotime($time_out) > strtotime($timesheet_time_out) && $stamp != "pm" ) {                           	     	
		                            $message = 'Overtime must not exceed the actual time out. <br/>The actual time out on that date was <b>'. Tools::convert24To12Hour($t->getTimeOut()) .'</b>';
		                            $is_saved = false;
		                        }
	                    	}

	                        
	                    }else{
		                    $message = 'You have no timeout on the selected day. Cannot file overtime.';
		                    $is_saved = false;
		                }
	                } else {
	                	$message = 'You have no attendace on the selected day. Cannot file overtime.';
			            $is_saved = false;
	                }
        		}
        		
        	}
        }

        if( $is_saved ){

        	$status = $o->getStatus();
        	if( $status == G_Overtime::STATUS_PENDING || $status == G_Overtime::STATUS_DISAPPROVED ){
	        	$d = Tools::getAutoDateInAndOut($date, $time_in, $time_out);
	            $o->setTimeIn($time_in);
	            $o->setTimeOut($time_out);
	            $o->setDateIn($d['date_in']);
	            $o->setDateOut($d['date_out']);            
	            $is_success = $o->save();
	            if( $is_success ){
	            	$message = 'Record was successfully updated'; 
	            	G_Attendance_Helper::updateAttendance($e, $date);
	        	}else{
	        		$is_saved = false;
					$message  = 'Data not found';	
	        	}
	        }else{
	        	$message  = 'Cannot update request. Request was already approved.'; 
	        	$is_saved = false;
	        }
        }

        $return['request']  = G_Request::PREFIX_OVERTIME; 
        $return['is_saved'] = $is_saved;
        $return['message']  = $message;
        echo json_encode($return);
    }

	function _load_overtime_pending_dt()
	{
		$user_id = Utilities::decrypt($this->global_user_eid);

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_OVERTIME);
		$dt->setSQL("
			SELECT geo.id, DATE_FORMAT(geo.date,'%b %d, %Y') as date_of_overtime, DATE_FORMAT(geo.date_created,'%b %d, %Y') as date_filed, DATE_FORMAT(geo.date_created,'%h:%i %p') as time_filed, 
				geo.time_in, geo.time_out, geo.reason, 
				(SELECT CONCAT('for approval of ', approver_name) FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_OVERTIME)." AND request_id = geo.id AND status = ".Model::safeSql(G_Request::PENDING)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id ASC LIMIT 1) as status
			FROM ". G_EMPLOYEE_OVERTIME ." geo	
		");		
		$dt->setCountSQL("SELECT COUNT(geo.id) as c FROM " . G_EMPLOYEE_OVERTIME . " geo LEFT JOIN ".REQUESTS." gr ON gr.request_id = geo.id AND gr.status = ".Model::safeSql(G_Request::PENDING)." ");	
		

		$dt->setCondition("geo.status = ". Model::safeSql(G_Employee_Overtime_Request::PENDING) ." AND geo.employee_id = ". Model::safeSql($user_id) ." AND geo.is_archived = ". Model::safeSql(G_Employee_Overtime_Request::NO));
		$dt->setColumns('date_filed,time_filed,date_of_overtime,time_in,time_out,reason,status');	
		$dt->setPreDefineSearch(
			array(				
				"date_filed" => "DATE_FORMAT(geo.date_created,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"time_filed" => "DATE_FORMAT(geo.date_created,'%h:%i %p') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_of_overtime" => "DATE_FORMAT(geo.date,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"status" => "(SELECT CONCAT('for approval of ', approver_name) FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_OVERTIME)." AND request_id = geo.id AND status = ".Model::safeSql(G_Request::PENDING)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id ASC LIMIT 1) LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' "
			)
		); 	 
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
			array(		
				1 =>'<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',
				2 =>'<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-edit-overtime\"><i class=\"icon-pencil\"></i> Edit </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-view-approver\"><i class=\"icon-list\"></i> View Approver(s) </a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}

	function _load_overtime_approved_dt()
	{
		$user_id = Utilities::decrypt($this->global_user_eid);

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_OVERTIME);
		$dt->setSQL("
			SELECT geo.id, DATE_FORMAT(geo.date,'%b %d, %Y') as date_of_overtime, DATE_FORMAT(geo.date_created,'%b %d, %Y') as date_filed, DATE_FORMAT(geo.date_created,'%h:%i %p') as time_filed, 
				geo.time_in, geo.time_out, geo.reason, 
				(SELECT DATE_FORMAT(action_date,'%b %d, %Y') FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_OVERTIME)." AND request_id = geo.id AND status = ".Model::safeSql(G_Request::APPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) as date_approved
			FROM ". G_EMPLOYEE_OVERTIME ." geo			
		");		
		$dt->setCountSQL("SELECT COUNT(c.id) as c FROM " . G_EMPLOYEE_OVERTIME . " c");	
		

		$dt->setCondition("geo.status = ". Model::safeSql(G_Employee_Overtime_Request::APPROVED) ." AND geo.employee_id = ". Model::safeSql($user_id) ." AND geo.is_archived = ". Model::safeSql(G_Employee_Overtime_Request::NO));
		$dt->setColumns('date_filed,time_filed,date_of_overtime,time_in,time_out,reason,date_approved');	
		$dt->setPreDefineSearch(
			array(				
				"date_filed" => "DATE_FORMAT(geo.date_created,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"time_filed" => "DATE_FORMAT(geo.date_created,'%h:%i %p') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_of_overtime" => "DATE_FORMAT(geo.date,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_approved" => "(SELECT DATE_FORMAT(action_date,'%b %d, %Y') FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_OVERTIME)." AND request_id = geo.id AND status = ".Model::safeSql(G_Request::APPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' "
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

	function _load_overtime_disapproved_dt()
	{
		$user_id = Utilities::decrypt($this->global_user_eid);

		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_OVERTIME);
		$dt->setSQL("
			SELECT geo.id, DATE_FORMAT(geo.date,'%b %d, %Y') as date_of_overtime, DATE_FORMAT(geo.date_created,'%b %d, %Y') as date_filed, DATE_FORMAT(geo.date_created,'%h:%i %p') as time_filed, 
				geo.time_in, geo.time_out, geo.reason, 
				(SELECT DATE_FORMAT(action_date,'%b %d, %Y') FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_OVERTIME)." AND request_id = geo.id AND status = ".Model::safeSql(G_Request::DISAPPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) as date_disapproved, 
				(SELECT remarks FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_OVERTIME)." AND request_id = geo.id AND status = ".Model::safeSql(G_Request::DISAPPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) as reason_for_disapproval
			FROM ". G_EMPLOYEE_OVERTIME ." geo			
		");		
		$dt->setCountSQL("SELECT COUNT(c.id) as c FROM " . G_EMPLOYEE_OVERTIME . " c");	
		

		$dt->setCondition("geo.status = ". Model::safeSql(G_Employee_Overtime_Request::DISAPPROVED) ." AND geo.employee_id = ". Model::safeSql($user_id) ." AND geo.is_archived = ". Model::safeSql(G_Employee_Overtime_Request::NO));
		$dt->setColumns('date_filed,time_filed,date_of_overtime,time_in,time_out,reason,date_disapproved,reason_for_disapproval');	
		$dt->setPreDefineSearch(
			array(				
				"date_filed" => "DATE_FORMAT(geo.date_created,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"time_filed" => "DATE_FORMAT(geo.date_created,'%h:%i %p') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_of_overtime" => "DATE_FORMAT(geo.date,'%b %d, %Y') LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"date_disapproved" => "(SELECT DATE_FORMAT(action_date,'%b %d, %Y') FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_OVERTIME)." AND request_id = geo.id AND status = ".Model::safeSql(G_Request::DISAPPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' ",
				"reason_for_disapproval" => "(SELECT remarks FROM ".REQUESTS." WHERE request_type = ".Model::safeSql(G_Request::PREFIX_OVERTIME)." AND request_id = geo.id AND status = ".Model::safeSql(G_Request::DISAPPROVED)." AND requestor_employee_id = ". Model::safeSql($user_id) ." ORDER BY id DESC LIMIT 1) LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' "
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