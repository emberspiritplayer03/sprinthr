<?php
class Overtime_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();

		$this->login();
        Loader::appMainScript('overtime_base.js');
		Loader::appMainScript('overtime.js');
        Loader::appMainScript('attendance_base.js');
		Loader::appMainScript('attendance.js');
        Loader::appMainScript('schedule_base.js');
        Loader::appMainScript('schedule.js');
		Loader::appStyle('style.css');

		$frequency_id = 1;

		if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
			$frequency_id = $_GET['selected_frequency'];
		}

		if( isset($_GET['cutoff_period']) ) {

			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;

			if ($frequency_id == 2) {
				$cutoff_data  	   = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			//monthly
			else if ($frequency_id == 3) {
				$cutoff_data  	   = G_Monthly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			else {
				$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			if($cutoff_data) {
				$from  = $cutoff_data->getStartDate();
				$to    = $cutoff_data->getEndDate();
				$hpid  = Utilities::encrypt($cutoff_data->getId());

				$this->var['get_from'] = $from;
				$this->var['get_to']   = $to;
				$this->var['get_hpid'] = $hpid;				
			}

			if($cutoff_data) {
				if($hpid){

					if ($frequency_id == 2) {
						$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Weekly_Cutoff_Period_Helper::isPeriodLock($hpid);
					}
					else if ($frequency_id == 3) {
						$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Monthly_Cutoff_Period_Helper::isPeriodLock($hpid);
					}
					else {
						$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Cutoff_Period_Helper::isPeriodLock($hpid);
					}

				}else{			
					$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'];
				}

				if($from && $to && $hpid){
		            $query = $_SERVER['QUERY_STRING'];
		            $this->var['download_url']    = url('overtime/download_ot?'. $query);
					$this->var['period_selected'] = '<small style="font-size:15px;">Period: <b>' . Tools::convertDateFormat($from) . ' </b> to <b>' . Tools::convertDateFormat($to) . '</b></small>';
				}				
			}			

		} else {
			if($_GET['hpid']){

					if ($frequency_id == 2) {
						$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Weekly_Cutoff_Period_Helper::isPeriodLock($_GET['hpid']);
					}
					//monthly
					else if ($frequency_id == 3) {
						$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Monthly_Cutoff_Period_Helper::isPeriodLock($hpid);
					}
					else {
						$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Cutoff_Period_Helper::isPeriodLock($_GET['hpid']);
					}

			}else{			
				$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'];
			}
			
			if($_GET['from'] && $_GET['to'] && $_GET['hpid']){
	            $query = $_SERVER['QUERY_STRING'];
	            $this->var['download_url']    = url('overtime/download_ot?'. $query);
				$this->var['period_selected'] = '<small style="font-size:15px;">Period: <b>' . Tools::convertDateFormat($_GET['from']) . ' </b> to <b>' . Tools::convertDateFormat($_GET['to']) . '</b></small>';
			}			
		}
		
		$this->eid             	= $this->global_user_eid;
		$this->c_date			= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		
		$this->var['eid']      	= $this->eid;
		$this->var['employee'] 	= 'selected';
		
		$this->var['company_structure_id'] = $this->company_structure_id = $this->global_user_ecompany_structure_id;	

		if(!isset($_GET['year_selected'])) {
			$this->var['year_selected']     = date("Y", strtotime($_GET['to']));
		} else {
			$this->var['year_selected']     = $_GET['year_selected'];	
		}		

		$this->sprintHdrMenu(G_Sprint_Modules::HR, 'attendance');		
		$this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_overtime');
	}
	
	//OLD
	/*
	function index()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		
		$this->var['department'] = $department = G_Company_Structure_Finder::findParentChildByCompanyStructureId($this->company_structure_id);
		
		$this->var['page_title'] = 'Overtime Requests';
		$this->view->setTemplate('template_overtime.php');
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
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Add Overtime Request\" id=\"add_request\" class=\"ui-icon ui-icon-plus g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:addOvertimeRequestForm(\'e_id\');\"></a></li><li><a title=\"View Overtime History\" id=\"view\" class=\"ui-icon ui-icon-search g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:load_show_overtime_details(\'e_id\')\"></a></li></ul></div>'));
		echo $dt->constructDataTable();
	}
	*/

    function _add_overtime() {
		$date_start = (!empty($_POST['start_date']) ? $_POST['start_date'] : $_POST['start_date_hideshow']);
		$end_date	= (!empty($_POST['start_date']) ? $_POST['start_date'] : $_POST['start_date_hideshow']);
		$time_in	= (!empty($_POST['start_time']) ? $_POST['start_time'] : $_POST['start_time_hideshow']);
		$time_out 	= (!empty($_POST['end_time']) ? $_POST['end_time'] : $_POST['end_time_hideshow']);

        $time_in  = Tools::convert12To24Hour($time_in);
        $time_out = Tools::convert12To24Hour($time_out);

        $return['is_saved'] = true;
        $return['message'] = 'Overtime has been successfully added';

			if(Utilities::isFormTokenValid($_POST['token'])) {
				$settings_request = G_Settings_Request_Finder::findByType(Settings_Request::OT);
				$employee		  = G_Employee_Finder::findById(Utilities::decrypt($_POST['h_employee_id']));

				//$return = G_Overtime_Helper::validateOvertimeRequest($employee,$date_start,$time_in,$time_out);
				//if($return['is_saved']) {

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

                     //General Reports / Shr Audit Trail
					$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
					$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_FILE, ' for Overtime of ', $emp_name, $date_start.' '.$time_in, $date_start.' '.$time_out, 0, $shr_emp['position'], $shr_emp['department']);

                } else if ($a) {
                    $t = $a->getTimesheet();                     
                    if ($t && $t->getTimeOut() != '') {
                    	$timesheet_time_out = Tools::convert12To24Hour($t->getTimeOut());

                    	if( strtotime($t->getDateIn()) == strtotime($t->getDateOut()) ) {
                        	if (!Tools::isTime1LessThanTime2($time_out,$timesheet_time_out)) {                           	     	
	                            $return['message'] = 'Overtime must not exceed the actual time out. <br/>The actual time out on that date was <b>'.date("M d, Y",strtotime($end_date))." ".Tools::convert24To12Hour($t->getTimeOut()) .'</b>';
	                            $return['is_saved'] = false;

	                             //General Reports / Shr Audit Trail
								$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
								$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        						$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_FILE, ' for Overtime of ', $emp_name, $date_start.' '.$time_in, $date_start.' '.$time_out, 0, $shr_emp['position'], $shr_emp['department']);

	                            //General Reports / Shr Audit Trail
								$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
								$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        						$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_FILE, ' for Overtime of ', $emp_name, $date_start.' '.$time_in, $date_start.' '.$time_out, 0, $shr_emp['position'], $shr_emp['department']);

	                        }
                    	}elseif( strtotime($t->getDateIn()) < strtotime($t->getDateOut()) ) {
                    		$stamp = date("a",strtotime($time_out));
                    		if ( strtotime($time_out) > strtotime($timesheet_time_out) && $stamp != "pm" ) {                           	     	
	                            $return['message'] = 'Overtime must not exceed the actual time out. <br/>The actual time out on that date was <b>'.date("M d, Y",strtotime($end_date))." ". Tools::convert24To12Hour($t->getTimeOut()) .'</b>';
	                            $return['is_saved'] = false;

	                             //General Reports / Shr Audit Trail
								$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
								$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        						$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_FILE, ' for Overtime of ', $emp_name, $date_start.' '.$time_in, $date_start.' '.$time_out, 0, $shr_emp['position'], $shr_emp['department']);

	                            //General Reports / Shr Audit Trail
								$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
								$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        						$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_FILE, ' for Overtime of ', $emp_name, $date_start.' '.$time_in, $date_start.' '.$time_out, 0, $shr_emp['position'], $shr_emp['department']);

	                        }
                    	}

                        
                    }else{
	                    $return['message'] = 'Employee has no timeout. Cannot file overtime.';
	                    $return['is_saved'] = false;

	                    //General Reports / Shr Audit Trail
						$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
						$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_FILE, ' for Overtime of ', $emp_name, $date_start.' '.$time_in, $date_start.' '.$time_out, 0, $shr_emp['position'], $shr_emp['department']);

	                }
                } else {
                	$return['message'] = 'Employee has no attendace. Cannot file overtime.';
		            $return['is_saved'] = false;

		            //General Reports / Shr Audit Trail
					$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
					$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_FILE, ' for Overtime of ', $emp_name, $date_start.' '.$time_in, $date_start.' '.$time_out, 0, $shr_emp['position'], $shr_emp['department']);
                }
			} else {
				$return['message']  = 'Error: Invalid Token. Request will not be saved.';
				$return['is_saved'] = false;

				//General Reports / Shr Audit Trail
				$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
				$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_FILE, ' for Overtime of ', $emp_name, $date_start.' '.$time_in, $date_start.' '.$time_out, 0, $shr_emp['position'], $shr_emp['department']);
			}

            if ($return['is_saved']) {
			    $overtime = G_Overtime_Finder::findByEmployeeAndDate($employee, $date_start);
				if(!$overtime) {
				    $overtime = new G_Overtime();
				}

				date_default_timezone_set('Asia/Manila');
				$current_date = date("Y-m-d H:i:s");

				//$override_status = G_Overtime::STATUS_PENDING;
				$overtime->setDate($date_start);
				$overtime->setTimeIn($time_in);
				$overtime->setTimeOut($time_out);
				$overtime->setDateIn($date_start);
				$overtime->setDateOut($end_date);
				$overtime->setEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
				$overtime->setReason(Tools::stringReplace($_POST['reason']));
	            $overtime->setStatus(G_Employee_Overtime_Request::PENDING);
	            //$overtime->setStatus($override_status);
	            $overtime->setDateCreated($current_date);
	            
				$request_id = $overtime->save();
				G_Attendance_Helper::updateAttendance($employee, $date_start);
 
				if($request_id) {
					$approvers    = $_POST['approvers'];
					$requestor_id = Utilities::decrypt($_POST['h_employee_id']);
					$request_type = G_Request::PREFIX_OVERTIME;
					
					$r = new G_Request();
			        $r->setRequestorEmployeeId($requestor_id);
			        $r->setRequestId($request_id);
			        $r->setRequestType($request_type);
			        $r->saveEmployeeRequest($approvers); //Save request approvers

			        //General Reports / Shr Audit Trail
					$shr_emp = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
					$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_FILE, ' for Overtime of ', $emp_name, $date_start.' '.$time_in, $date_start.' '.$time_out, 1, $shr_emp['position'], $shr_emp['department']);
				}
			
            }

		$token = Utilities::createFormToken();
		$return['token'] = $token;
		echo json_encode($return);
    }

    function _test_ot() {
		$date_start = "2015-03-12";
		$end_date	= "2015-03-12";
		$time_in	= "5:00 pm";
		$time_out 	= "8:00 pm";

        $time_in  = Tools::convert12To24Hour($time_in);
        $time_out = Tools::convert12To24Hour($time_out);

        $return['is_saved'] = true;
        $return['message'] = 'Overtime has been successfully added';

			if(!Utilities::isFormTokenValid($_POST['token'])) {
				$settings_request = G_Settings_Request_Finder::findByType(Settings_Request::OT);
				$employee		  = G_Employee_Finder::findById(2);

				//$return = G_Overtime_Helper::validateOvertimeRequest($employee,$date_start,$time_in,$time_out);
				//if($return['is_saved']) {

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
	                    $return['message'] = 'Employee has no timeout. Cannot file overtime.';
	                    $return['is_saved'] = false;
	                }
                } else {
                	$return['message'] = 'Employee has no attendace. Cannot file overtime.';
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

				$override_status = G_Overtime::STATUS_PENDING;
				$overtime->setDate($date_start);
				$overtime->setTimeIn($time_in);
				$overtime->setTimeOut($time_out);
				$overtime->setDateIn($date_start);
				$overtime->setDateOut($end_date);
				$overtime->setEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
				$overtime->setReason(Tools::stringReplace($_POST['reason']));
	            //$overtime->setStatus($_POST['status']);
	            $overtime->setStatus($override_status);
	            $overtime->setDateCreated(date("Y-m-d H:i:s"));
				$request_id = $overtime->save();

				$approvers    = $_POST['approvers'];
				$requestor_id = Utilities::decrypt($_POST['h_employee_id']);
				$request_type = G_Request::PREFIX_OVERTIME;

				$r = new G_Request();
		        $r->setRequestorEmployeeId($requestor_id);
		        $r->setRequestId($request_id);
		        $r->setRequestType($request_type);
		        $r->saveEmployeeRequest($approvers); //Save request approvers

				G_Attendance_Helper::updateAttendance($employee, $date_start);
            }

		$token = Utilities::createFormToken();
		$return['token'] = $token;
		echo json_encode($return);
    }

    function _approve_overtime() {
        $employee_id = (int) $_POST['employee_id'];
        $date = $_POST['date'];

        $return['is_saved'] = false;
        $e = G_Employee_Finder::findById($employee_id, $date);
        if ($e) {
            //$o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
            $o = G_Overtime_Finder::findById(Utilities::decrypt($_POST['oid']));
            if ($o) {
                $o->setStatus(G_Overtime::STATUS_APPROVED);
                $is_saved = $o->save();

                $request = new G_Request();
				$request->setRequestId($o->getId());
				$request->setRequestType(G_Request::PREFIX_OVERTIME);
				$request->resetToApprovedApproversStatusByRequestIdAndRequestType(); 

                G_Attendance_Helper::updateAttendance($e, $date);
            }
        }

        $return['is_saved'] = $is_saved;

        //General Reports / Shr Audit Trail
		$shr_emp = G_Employee_Helper::findByEmployeeId($_POST['employee_id']);
		$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_APPROVED, 'for Overtime of ', $emp_name, $date, $date, 1, $shr_emp['position'], $shr_emp['department']);

        echo json_encode($return);
    }

    function _disapprove_overtime() {
        $employee_id = (int) $_POST['employee_id'];
        $date = $_POST['date'];

        $return['is_saved'] = false;
        $e = G_Employee_Finder::findById($employee_id, $date);
        if ($e) {
            //$o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
            $o = G_Overtime_Finder::findById(Utilities::decrypt($_POST['oid']));
            if ($o) {
                $o->setStatus(G_Overtime::STATUS_DISAPPROVED);
                $is_saved = $o->save();

                $request = new G_Request();
				$request->setRequestId($o->getId());
				$request->setRequestType(G_Request::PREFIX_OVERTIME);
				$request->resetToDisApprovedApproversStatusByRequestIdAndRequestType(); 

                G_Attendance_Helper::updateAttendance($e, $date);
            }
        }

        $return['is_saved'] = $is_saved;
        echo json_encode($return);
    }

    function _set_pending_overtime() {
        $employee_id = (int) $_POST['employee_id'];
        $date = $_POST['date'];

        $return['is_saved'] = false;
        $e = G_Employee_Finder::findById($employee_id, $date);
        if ($e) {
            //$o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
            $o = G_Overtime_Finder::findById(Utilities::decrypt($_POST['oid']));
            if ($o) {
                $o->setStatus(G_Overtime::STATUS_PENDING);
                $is_saved = $o->save();

                $request = new G_Request();
				$request->setRequestId($o->getId());
				$request->setRequestType(G_Request::PREFIX_OVERTIME);
				$request->resetToPendingApproversStatusByRequestIdAndRequestType(); 

                G_Attendance_Helper::updateAttendance($e, $date);
            }
        }

        $return['is_saved'] = $is_saved;
        echo json_encode($return);
    }

	function insert_employee_request_overtime() {
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
					$employee_overtime_request->setIsApproved($_POST['status']);
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

    function check_overtime_error() {
        $employee_id = (int) $_POST['employee_id'];
        $date = $_POST['date'];

        $e = G_Employee_Finder::findById($employee_id);
        $has_error = true;
        $message = 'An error occurred.';
        if ($e) {

            $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
            G_Overtime_Error_Helper::updateOvertimeError($a, $e);
            $err = G_Overtime_Error_Finder::findUnfixedByEmployeeIdAndDate($e->getId(), $date);
            if ($err) {
                $message = $err->getMessage();
                $has_error = true;
            } else {
                $message = 'Error has been successfully fixed';
                $has_error = false;
            }

            /*
            $er = new G_Overtime_Error_Checker();
            $er->checkByEmployeeAndDate($e, $date);
            $err = G_Overtime_Error_Finder::findByEmployeeIdAndDate($e->getId(), $date);
            if ($err && !$er->hasError()) {
                $err->setAsFixed();
                $err->save();
                $message = 'Error has been successfully fixed';
                $has_error = false;
            } else if ($er->hasError()) {
                $message = $err->getMessage();
                $has_error = true;
            }*/
        }
        $return['has_error'] = $has_error;
        $return['message'] = $message;
        echo json_encode($return);
    }

    function _edit_overtime() {
        $employee_id = (int) $_POST['employee_id'];
        $date        = $_POST['date'];
        $time_in     = date('H:i:s', strtotime($_POST['time_in']));
        $time_out    = date('H:i:s', strtotime($_POST['time_out']));
        $is_saved    = true;
        $message     = 'Record was successfully updated';        

        $e = G_Employee_Finder::findById($employee_id);
        if ($e) {        	
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
                            $message = 'Overtime must not exceed the actual time out. <br/>The actual time out on that date was <b>'.date("M d, Y",strtotime($end_date))." ".Tools::convert24To12Hour($t->getTimeOut()) .'</b>';
                            $is_saved = false;
                        }
                	}elseif( strtotime($t->getDateIn()) < strtotime($t->getDateOut()) ) {
                		$stamp = date("a",strtotime($time_out));
                		if ( strtotime($time_out) > strtotime($timesheet_time_out) && $stamp != "pm" ) {                           	     	
                            $message = 'Overtime must not exceed the actual time out. <br/>The actual time out on that date was <b>'.date("M d, Y",strtotime($end_date))." ".Tools::convert24To12Hour($t->getTimeOut()) .'</b>';
                            $is_saved = false;
                        }
                	}

                    
                }else{
                    $message = 'Employee has no timeout. Cannot file overtime.';
                    $is_saved = false;
                }
            } else {
            	$message = 'Employee has no attendace. Cannot file overtime.';
	            $is_saved = false;
            }

            if( $is_saved ){
            	$o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
	            if ($o) {
	                $d = Tools::getAutoDateInAndOut($date, $time_in, $time_out);
	                $o->setTimeIn($time_in);
	                $o->setTimeOut($time_out);
	                $o->setDateIn($d['date_in']);
	                $o->setDateOut($d['date_out']);
	                //$o->setStatus(G_Overtime::STATUS_APPROVED);
	                $is_saved = $o->save();
	                if( $is_saved ){	                	
	                	//G_Attendance_Helper::updateAttendance($e, $date);
	                	$is_saved = true;
	            	}else{
	            		$is_saved = false;
        				$message  = 'Data not found';	
	            	}
	            }else{
	            	$is_saved = false;
        			$message  = 'Data not found';
	            }
            }
        }else{
        	$is_saved = false;
        	$message  = 'Employee data not found';
        }

        $return['is_saved'] = $is_saved;
        $return['message']  = $message;
        echo json_encode($return);
    }

    function ajax_edit_overtime_form() {
        $this->var['action']    = url('overtime/_edit_overtime');
        $this->var['employee_id'] = $employee_id = $_GET['employee_id'];
        $this->var['date'] = $date = $_GET['date'];
        $this->var['date_string'] = Tools::convertDateFormat($date);

        $e = G_Employee_Finder::findById($employee_id);
        if ($e) {
            //$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
            $o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
            if ($o) {
                //$t = $a->getTimesheet();

                $this->var['time_in'] = Tools::timeFormat($o->getTimeIn());
                $this->var['time_out'] = Tools::timeFormat($o->getTimeOut());
            }
        }

        $this->view->render('overtime/form/ajax_edit_overtime_out_form.php',$this->var);
    }

    function ajax_edit_custom_overtime() {	
		$co = G_Custom_Overtime_Finder::findById(Utilities::decrypt($_GET['eid']));
		if($co) {
			$e = G_Employee_Finder::findById($co->getEmployeeId());
			if($e) {
				$this->var['employee_name'] = $e->getFirstName() . ' ' . $e->getLastName();
			}
			$this->var['co']    = $co;
			$this->var['token'] = Utilities::createFormToken();
			$this->view->render('overtime/form/edit_custom_overtime.php', $this->var);	
		}else{
			echo "<div class=\"alert alert-error\">Record not found</div>";
		}
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
			
			$this->var['overtime'] = $overtime = G_Employee_Overtime_Request_Finder::findById(Utilities::decrypt($_POST['h_id']));
			
			$e = G_Employee_Finder::findById(Utilities::decrypt($_POST['h_employee_id']));
			$file = PHOTO_FOLDER.$e->getPhoto();
			
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
						$employee_overtime_request->setIsApproved($_POST['status']);
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
						}
						
						G_Attendance_Helper::updateAttendance($employee, $_POST['start_date_edit']);
						#G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($employee, $from, $to);
				} 
			} else { 
				$return['message']   = 'Error : Request overtime did not save successfully at this time.';
				$return['is_saved'] = false;
			}
		} else { 
			$return['message']   = 'Error : Request overtime did not save successfully at this time.';
			$return['is_saved'] = false;
		}
		echo json_encode($return);
	}

	function ajax_view_overtime_request_approvers() 
	{
		$date  		 = date("Y-m-d"); 		
		$cp          = new G_Cutoff_Period();
		$cutoff_data = $cp->getCurrentCutoffPeriod($date);				

		$request_id  = Utilities::decrypt($_GET['eid']);
		$approvers   = new G_Request();
		$approvers->setRequestId($request_id);
		$data = $approvers->getOvertimeRequestApproversStatus();		
		if( $data['total_approvers'] > 0 ){	
			$this->var['eid']        = $_GET['eid'];		
			$this->var['is_lock']    = $cutoff_data['is_lock'];			
			$this->var['total_approvers'] = $data['total_approvers'];
			$this->var['approvers']  = $data['approvers'];	
			$this->var['token']		 = Utilities::createFormToken();			
			$this->var['page_title'] = 'Overtime Request Approvers';		
			$this->view->render('overtime/form/view_overtime_request_approvers.php',$this->var);
		}else{
			echo "<div class=\"alert alert-error\">No approvers set for selected request</div><br />";
		}
	}

	function _update_overtime_request_approvers(){    	
    	Utilities::verifyFormToken($_POST['token']);    	

    	$data = $_POST['approvers'];    	    	
    	$id   = Utilities::decrypt($_POST['eid']);
    	$json['is_success'] = false;
		$json['message']    = "Cannot save record";

    	if( !empty($data) ){
    		$date 		  = $this->c_date;
    		$request_type = G_Request::PREFIX_OVERTIME;
    		$r = new G_Request();
    		$r->setRequestId($id);
    		$r->setActionDate($date);
    		$r->setRequestType($request_type);
			$json = $r->updateRequestApproversDataById($data);
			$r->updateRequestStatus();
    	}

    	echo json_encode($json);
    }

    function _update_custom_overtime() {
		$return['is_success'] = false;
		if(Utilities::isFormTokenValid($_POST['token'])) {	
			if( !empty($_POST['eid']) ) {
				$id = Utilities::decrypt($_POST['eid']);
				$co = G_Custom_Overtime_Finder::findById($id);
				if( $co ){
					$co->setStartTime(date("H:i:s",strtotime($_POST['custom_overtime_start_time'])));
					$co->setEndTime(date("H:i:s", strtotime($_POST['custom_overtime_end_time'])));
					$co->save();
				}

				$return['message'] = "Record was successfully updated.";
				$return['is_success'] = true;
			}else{
				$return['message'] = "Record does not exists!";
			}
		}else {
			$return['message'] = "Invalid form token";
		}
		$return['token'] = Utilities::createFormToken();
		echo json_encode($return);
	}
	
	function _load_disapprove_overtime_request() {
		if($_POST['h_id']){			
			$ot = G_Employee_Overtime_Request_Finder::findById(Utilities::decrypt($_POST['h_id']));	
			if($ot){
				$ot->disapprove();
				$return['is_saved'] = 1;
				$return['message']  = 'Request was successfully disapproved.';
			}else{
				$return['is_saved'] = 0;
				$return['message']  = 'Error in SQL';
			}
			
			echo json_encode($return);
		}
	}
	
	function _ajax_disapprove_custom_overtime() {
		$return['is_saved'] = 0;
		$return['message']  = 'Error in SQL';
		if($_POST['eid']){			
			$co = G_Custom_Overtime_Finder::findById(Utilities::decrypt($_POST['eid']));	
			if($co){
				$co->disapprove();
				$return['is_saved'] = 1;
				$return['message']  = 'Custom Overtime was successfully disapproved.';
			}			
		}
		echo json_encode($return);
	}

	function _ajax_approve_custom_overtime() {
		$return['is_saved'] = 0;
		$return['message']  = 'Error in SQL';
		if($_POST['eid']){			
			$co = G_Custom_Overtime_Finder::findById(Utilities::decrypt($_POST['eid']));	
			if($co){
				$co->approve();
				$return['is_saved'] = 1;
				$return['message']  = 'Custom Overtime was successfully approved.';
			}
		}
		echo json_encode($return);
	}

	function _load_approve_overtime_request() {
		if($_POST['h_id']){			
			$ot = G_Employee_Overtime_Request_Finder::findById(Utilities::decrypt($_POST['h_id']));	
			if($ot){
				$ot->approve();
				$return['is_saved'] = 1;
				$return['message']  = 'Request was successfully approved.';
			}else{
				$return['is_saved'] = 0;
				$return['message']  = 'Error in SQL';
			}
			
			echo json_encode($return);
		}
	}

	function _with_selected_action() {
		$return['is_success'] = 0;
		$return['message'] = "Please select at least one request.";
		$success_counter = 0;
		if(count($_POST['dtChk']) > 0) {
			foreach($_POST['dtChk'] as $key => $value) {
				$date = $_POST['date'][$key];
				$overtime_id = Utilities::decrypt($_POST['overtime_id'][$key]);
				$employee_id = Utilities::decrypt($value);
				$e = G_Employee_Finder::findById($employee_id, $date);
		        if ($e) {
		            //$o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
		            $o = G_Overtime_Finder::findById($overtime_id);
		            if ($o) {
		            	if($_POST['action'] == "approve") {
		                	$o->setStatus(G_Overtime::STATUS_APPROVED);
		            	}elseif($_POST['action'] == "disapprove"){
		            		$o->setStatus(G_Overtime::STATUS_DISAPPROVED);
		            	}elseif($_POST['action'] == "pending"){
		            		$o->setStatus(G_Overtime::STATUS_PENDING);
		            	}
		                $is_saved = $o->save();

		                $request = new G_Request();
						$request->setRequestId($o->getId());
						$request->setRequestType(G_Request::PREFIX_OVERTIME);
						if($_POST['action'] == "approve") {
							$request->resetToApprovedApproversStatusByRequestIdAndRequestType(); 
							$success_counter++;
							$return['message'] = $success_counter . " request(s) has been approved.";
						}elseif($_POST['action'] == "disapprove"){
							$request->resetToDisapprovedApproversStatusByRequestIdAndRequestType(); 
							$success_counter++;
							$return['message'] = $success_counter . " request(s) has been disapproved.";
						}elseif($_POST['action'] == "pending"){
							$request->resetToPendingApproversStatusByRequestIdAndRequestType(); 
							$success_counter++;
							$return['message'] = $success_counter . " request(s) has been set as pending.";
						}
		                G_Attendance_Helper::updateAttendance($e, $date);
		                $return['is_success'] = 1;
		            }
		        }

			}
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
		$this->var['errors'] = $errors = G_Overtime_Error_Finder::countAllErrorsNotFixed();
		if($errors > 0) {
			$this->var['total_errors'] = $errors;
		}
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
		$dt->setCustomField(array('name' => 'firstname,lastname','job_name'=>'jbh.name','job_name'=>'jbh.name'));
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_OVERTIME_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id");
		$dt->setCondition(' is_archive = "No" AND jbh.end_date =""');
		$dt->setColumns('date_start,time_in,time_out,overtime_comments');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(2);
		$dt->setCustomColumn(	
		array(
		'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" value=\"hid\" class=\"ckDt\" onclick=\"javascript:check_ckdt();\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editOvertimeRequestForm(\'e_id\');\"></a></li><li><a title=\"Delete\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:deleteOvertimeRequest(\'e_id\',\'is_approved\')\"></a></li></ul></div>'));
		echo $dt->constructDataTable();
		
		// old - for showing overtime details instead of pop up
		//'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" value=\"hid\" class=\"ckDt\" onclick=\"javascript:check_ckdt();\"></li><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:load_show_overtime_details(\'e_id\',\'employee\');\"></a></li><li><a title=\"Delete\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:deleteOvertimeRequest(\'e_id\',\'is_approved\')\"></a></li></ul></div>'));
		//editOvertimeRequestForm
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
	
	//
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
	
	function load_change_overtime_request_status() {
		if(!empty($_POST)) {

			$mArray = $_POST['dtChk'];
			foreach($mArray as $key => $value):
			$d++;
				$eor = G_Employee_Overtime_Request_Finder::findById($value);
			
				if($eor) {
					$employee = G_Employee_Finder::findById($eor->getEmployeeId());
	
					if($_POST['status'] == G_Employee_Overtime_Request::APPROVED) {
						$overtime = G_Overtime_Finder::findByEmployeeAndDate($employee,$eor->getDateStart());
						if(!$overtime) {
							$overtime = new G_Overtime();
						}
						
						$overtime->setDate($eor->getDateStart());
						$overtime->setTimeIn(Tools::convert12To24Hour($eor->getTimeIn()));
						$overtime->setTimeOut(Tools::convert12To24Hour($eor->getTimeOut()));
						$overtime->setEmployeeId($employee->getId());
						$overtime->setReason(Tools::stringReplace($eor->getOvertimeComments()));
						$overtime->save();
						
						$eor->setIsApproved($_POST['status']);
						$eor->save();
					
						G_Attendance_Helper::updateAttendance($employee, $eor->getDateStart());	
						$json['message']    = 'Successfully approved ' . $d . ' record(s)';	
						$json['is_success'] = 1;
						$json['load_dt']    = 1;
					}elseif($_POST['status'] == 'Archive'){
						if($eor) {
							$eor->setIsArchive(G_Employee_Overtime_Request::YES);
							$eor->save();
							
							$overtime = G_Overtime_Finder::findByEmployeeAndDate($employee,$eor->getDateStart());
							$overtime->delete();
							
							$json['message']    = 'Successfully archived ' . $d . ' record(s)';	
							$json['is_success'] = 1;
							$json['load_dt']    = 1;			
						}
					}elseif($_POST['status'] == 'Restore Archive'){
						if($eor) {
							$eor->setIsArchive(G_Employee_Overtime_Request::NO);
							$eor->save();	
							
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
		$dt->setCustomField(array('name' => 'firstname,lastname','job_name'=>'jbh.name','job_name'=>'jbh.name','employee_id'=>'jbh.employee_id'));
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_OVERTIME_REQUEST . ".employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id $sqlcat");
		$dt->setCondition(' is_archive = "No"'.$sqlcond);
		$dt->setColumns('date_start,time_in,time_out,overtime_comments,is_approved');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(2);
		$dt->setCustomColumn(	
		array(
		'1'=>'<input type=\"checkbox\" name=\"dtChk[]\" value=\"hid\" class=\"ckDt\" onclick=\"javascript:check_ckdt();\">',
		'2' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:load_show_overtime_details(\'e_id\',\'employee\');\"></a></li><li><a title=\"Delete\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:deleteOvertimeRequest(\'e_id\',\'is_approved\')\"></a></li></ul></div>'));
		echo $dt->constructDataTable();
	}
	
	/*
		RECONSTRUCTED OVERTIME MODULE (CLERK) :
	*/
	
	function index() {
		unset($_SESSION['sprint_hr']['tmp']);
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();

		$frequency_id = 1;

		if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
			$frequency_id = $_GET['selected_frequency'];
		}

		$this->var['selected_frequency'] = $frequency_id;
       		
		$this->var['page_title'] = 'Overtime Requests';
        $now = date('Y-m-d');
	    $p = G_Cutoff_Period_Finder::findByDate($now);
	    if ($p) {
	        $hpid = Utilities::encrypt($p->getId());
	        $from_date = $p->getStartDate();
	        $to_date = $p->getEndDate();
	    }
        redirect("overtime/period?from={$from_date}&to={$to_date}&hpid={$hpid}&selected_frequency={$frequency_id}");		
	}
	
	function period() {

		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTextBoxList();
		$enable_next_previous_link = false;

		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainScript('attendance.js');
		Loader::appMainScript('attendance_base.js');

		$frequency_id = 1;

		if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
			$frequency_id = $_GET['selected_frequency'];
		}

		$this->var['selected_frequency'] = $frequency_id;
		
		$this->var['page_title'] 	= 'Overtime Requests';

		if( isset($_GET['cutoff_period']) ) {
			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;

			if ($frequency_id == 2) {
				$cutoff_data  	   = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);

			}
			else if ($frequency_id == 3) {
				$cutoff_data  	   = G_Monthly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			else {
				$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);

			}

			if($cutoff_data) {
				$eid        = Utilities::encrypt($cutoff_data->getId());
				$cutoff_id 	= $cutoff_data->getId();
				$from_date  = $cutoff_data->getStartDate();
				$to_date 	= $cutoff_data->getEndDate();
			}

		} else {
			$eid       = $_GET['hpid'];
	        $cutoff_id = Utilities::decrypt($_GET['hpid']);
	        $from_date = $_GET['from'];
	        $to_date   = $_GET['to'];
	        $this->var['cutoff_selected'] = $from_date."/".$to_date;	

		}

        $this->var['from_period']   = $from_date;
		$this->var['to_period']	    = $to_date;

		if ($frequency_id == 2) {
        	$all_payroll_years 	= G_Weekly_Cutoff_Period_Helper::sqlGetAllExistYearTags();
		}
		else if ($frequency_id == 3) {
        	$all_payroll_years 				= G_Monthly_Cutoff_Period_Helper::sqlGetAllExistYearTags();
								}
		else {
        	$all_payroll_years 	= G_Cutoff_Period_Helper::sqlGetAllExistYearTags();
		}

        $this->var['all_cutoff_years'] 	= $all_payroll_years;		

		if($enable_next_previous_link) {
			$date = $from_date;

			if ($frequency_id == 2) {
				$c = new G_Weekly_Cutoff_Period();
			}
			else if ($frequency_id == 3) {
				$c = new G_Monthly_Cutoff_Period();
			}
			else {
				$c = new G_Cutoff_Period();
			}

			$previous_cutoff_data = $c->getPreviousCutOffByDate($date);
			$next_cutoff_data     = $c->getNextCutOffByDate($date);

			$next_from = $next_cutoff_data['start_date'];
			$next_to   = $next_cutoff_data['end_date'];
			$next_id   = $next_cutoff_data['eid'];

			if( !empty($_GET['sidebar']) ){
				$sidebar = "sidebar=" . $_GET['sidebar'] . "&";
			}else{
				$sidebar = '';
			}

			if( !empty($next_from) ){
				$this->var['next_cutoff_link'] = url("overtime/period?{$sidebar}from={$next_from}&to={$next_to}&hpid={$next_id}&selected_frequency={$frequency_id}");

			}else{
				$this->var['next_cutoff_link'] = url("overtime/period?{$sidebar}from={$from_date}&to={$to_date}&hpid={$eid}&selected_frequency={$frequency_id}");
			}

			$previous_from = $previous_cutoff_data['start_date'];
			$previous_to   = $previous_cutoff_data['end_date'];
			$previous_id   = $previous_cutoff_data['eid'];
			if( !empty($previous_from) ){
				$this->var['previous_cutoff_link'] = url("overtime/period?{$sidebar}from={$previous_from}&to={$previous_to}&hpid={$previous_id}&selected_frequency={$frequency_id}");
			}else{
				$this->var['previous_cutoff_link'] = url("overtime/period?{$sidebar}from={$from_date}&to={$to_date}&hpid={$eid}&selected_frequency={$frequency_id}");
			}
		}

        G_Overtime_Error_Finder::countRecords();
        $total_errors = G_Overtime_Error_Finder::findAllErrorsNotFixedByPeriod($from_date, $to_date);
        if ($total_errors > 0) {
            $total_error_string = "({$total_errors})";
        }
        $this->var['total_errors'] = $total_error_string;

        $btn_request_overtime_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'attendance',
    		'child_index'			=> 'attendance_overtime',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:show_request_overtime_form();',
    		'id' 					=> 'request_overtime_button',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Request Overtime</b>'
    		); 

    	$btn_import_overtime_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'attendance',
    		'child_index'			=> 'attendance_overtime',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:importOvertime();',
    		'id' 					=> 'import_ot',
    		'class' 				=> 'add_button pull-right',
    		'icon' 					=> '<i class="icon-arrow-left"></i>',
    		'additional_attribute' 	=> '',
    		'caption' 				=> 'Import OT'
    		); 
		
		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_overtime');
		$this->var['btn_request_overtime'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_request_overtime_config);
        $this->var['btn_import_overtime'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_import_overtime_config);

		if($_GET['sidebar'] == 2) {
			$this->var['ot_status'] = "approved";
			$this->var['get_sidebar'] = 2;
			$this->approved_overtime();
		} else if($_GET['sidebar'] == 3) {
			$this->var['get_sidebar'] = 3;
			$this->overtime_history();
		} else if($_GET['sidebar'] == 4) {
			$this->var['get_sidebar'] = 4;
            $this->error_reports();
        } else if ($_GET['sidebar'] == 5) {
        	$this->var['get_sidebar'] = 5;
        	$this->var['ot_status'] = "disapproved";
            $this->disapproved_overtime();
		} else if( $_GET['sidebar'] == 7 ) {
			$this->var['get_sidebar'] = 7;
			$this->var['ot_status'] = "approved";
            $this->custom_overtime();
		} else {
			$this->var['get_sidebar'] = '';
			$this->var['ot_status'] = "pending";
			$this->pending_overtime();
		}	
	}
	
	function pending_overtime() {
		Jquery::loadMainTextBoxList();

		$frequency_id = 1;

		if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
			$frequency_id = $_GET['selected_frequency'];
		}

		$this->var['selected_frequency'] = $frequency_id;

		if( isset($_GET['cutoff_period']) ) {
			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;

			if ($frequency_id == 2) {
				$cutoff_data  	   = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			else if ($frequency_id == 3) {
				$cutoff_data  	   = G_Monthly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			else {
				$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			if($cutoff_data) {
				$eid        = Utilities::encrypt($cutoff_data->getId());
				$cutoff_id 	= $cutoff_data->getId();
				$from_date  = $cutoff_data->getStartDate();
				$to_date 	= $cutoff_data->getEndDate();
				$hpid       = $eid;
			}
		} else {
		    $from_date	= $_GET['from'];
	        $to_date 	= $_GET['to'];
	        $hpid 		= $_GET['hpid'];
		}		

        $this->var['group_id'] = $group_id = (int) $_GET['group_id'];
        $this->var['download_url'] = url("overtime/period?from={$from_date}&to={$to_date}&hpid={$hpid}&group_id={$group_id}&download=yes&selected_frequency={$frequency_id}");

        
         //General Reports / Shr Audit Trail
        list($p_year, $p_month, $p_day) = explode('-', $from_date);
        $pmonthN = date("F", mktime(0, 0, 0, $p_month, 10));
        if($p_day >= 16){
        	$cut_of_period = $p_year.'-'.$pmonthN.'-B';
        }
        else{
            $cut_of_period = $p_year.'-'.$pmonthN.'-A';	
        }

		if($_GET['selected_frequency'] == 1){
			$frequency_name = 'Bi-Monthly';
		}
		else{
			$frequency_name = 'Weekly';
		}

        //end

        $eids = $_GET['eids'];
        if( !empty($eids) ){
        	$ids = explode(",", $eids);
        	foreach( $ids as $id ){
        		$a_new_ids[] = Utilities::decrypt($id);
        	}
        	$s_new_ids = implode(",", $a_new_ids);
        	$additional_query = " AND employee_id IN($s_new_ids)";        	
        }

	    if ($_GET['download'] == 'yes') {
            
            if ($group_id) {
                $records = G_Overtime_Finder::findAllPendingByGroupIdAndPeriod($group_id, $from_date, $to_date, $additional_query);
            } else {
                $records = G_Overtime_Finder::findAllPendingByPeriod($from_date, $to_date, $additional_query);
            }

            $this->var['overtime'] = $records;
            $this->var['filename'] = "pending_overtime_{$from_date}_{$to_date}.xls";
            $has_overtime = false;
            if ($records) {
                $has_overtime = true;
                 //General Reports / Shr Audit Trail
                $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_GENERATE, ' Overtime Pending Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency,', $period_start, $period_end, 1, '', '');
            }
            
            if($records == false){
            	 //General Reports / Shr Audit Trail
            	 $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_GENERATE, ' Overtime Pending Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency, No Data', $from_date, $to_date, 0, '', '');
            }

            $this->var['has_overtime'] = $has_overtime;

            $this->view->noTemplate();
            $this->view->render('overtime/overtime_download.php', $this->var);
            exit;
        }

		$this->var['recent'] 	= 'selected';
		$this->var['sidebar']	= 1;
		$this->var['module'] 	= 'overtime';
        $this->var['sub_title'] = 'Pending Overtime';
		$this->view->setTemplate('template_leftsidebar.php');

        $per_page = 10;
        $page_number = (int) $_GET['pageID'];
        if ($page_number > 0) {
            $page_number--;
            $start_record = $page_number * $per_page;
        } else {
            $start_record = $page_number;
        }

        if ($group_id) {
            G_Overtime_Finder::countRecords();
            $total_records = G_Overtime_Finder::findAllPendingByGroupIdAndPeriod($group_id, $from_date, $to_date, $additional_query);

            G_Overtime_Finder::setLimit($start_record, $per_page);
            $records = G_Overtime_Finder::findAllPendingByGroupIdAndPeriod($group_id, $from_date, $to_date, $additional_query);

        } else {
            G_Overtime_Finder::countRecords();
            $total_records = G_Overtime_Finder::findAllPendingByPeriod($from_date, $to_date, $additional_query);

            G_Overtime_Finder::setLimit($start_record, $per_page);
            $records = G_Overtime_Finder::findAllPendingByPeriod($from_date, $to_date, $additional_query);
        }
        $this->var['overtime'] = $records;

        // PAGER
        require_once 'Pager.php';

        $params = array(
            'mode'       => 'Sliding',
            'perPage'    => $per_page,
            'itemData'   => $records,
        );

        $pager =& Pager::factory($params);

        unset($pager);

        $params = array(
            'mode'     => 'Sliding',
            'perPage'  => $per_page,
            'delta'    => 5,
            'totalItems' => $total_records
        );
        $pager =& Pager::factory($params);
        $links = $pager->getLinks();
        $this->var['pager_links'] = $links['all'];

        $has_overtime = false;
        if ($records) {
            $has_overtime = true;
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, ' Overtime Pending Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency', $from_date, $to_date, 1, '', '');	
        }

        if($records == false){
        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, ' Overtime Pending Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency, No Data', $from_date, $to_date, 0, '', '');
        }

        $this->var['has_overtime'] = $has_overtime;
        
        $this->var['departments'] = G_Group_Finder::findAllDepartments();
		$this->view->render('overtime/period.php',$this->var);
	}

	function custom_overtime() {
		Jquery::loadMainBootStrapDropDown();

		$frequency_id = 1;

		if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
			$frequency_id = $_GET['selected_frequency'];
		}

		$this->var['selected_frequency'] = $frequency_id;

		if( isset($_GET['cutoff_period']) ) {
			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;

			if ($frequency_id == 2) {
				$cutoff_data  	   = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
				else if ($frequency_id == 3) {
				$cutoff_data  	   = G_Monthly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			else {
				$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			if($cutoff_data) {
				$from_date  = $cutoff_data->getStartDate();
				$to_date 	= $cutoff_data->getEndDate();
				$eid        = Utilities::encrypt($cutoff_data->getId());
				$hpid       = $eid;
				$cutoff_id 	= $cutoff_data->getId();
				$side_bar   = $_GET['sidebar'];
			}

			//General Reports / Shr Audit Trail
	        list($p_year, $p_month, $p_day) = explode('-', $from_date);
	        $pmonthN = date("F", mktime(0, 0, 0, $p_month, 10));
	        if($p_day >= 16){
	        	$cut_of_period = $p_year.'-'.$pmonthN.'-B';
	        }
	        else{
	            $cut_of_period = $p_year.'-'.$pmonthN.'-A';	
	        }

	        if($_GET['selected_frequency'] == 1){
			$frequency_name = 'Bi-Monthly';
			}
			else{
				$frequency_name = 'Weekly';
			}

			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, ' Custom Overtime Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency', $from_date, $to_date, 1, '', '');
			//end	

		} else {
			$from_date	= $_GET['from'];
	        $to_date    = $_GET['to'];
	        $hpid 	    = $_GET['hpid'];  
	        $side_bar   = $_GET['sidebar'];       
		}		

        $this->var['sidebar']	= 1;
		$this->var['module'] 	= 'overtime';
        $this->var['sub_title'] = 'Custom Overtime';
        $this->var['custom_overtime'] = 'selected';
        $this->var['period']    = array('from' => $from_date, 'to' => $to_date);
		$this->view->setTemplate('template_leftsidebar.php');          
		$this->view->render('overtime/custom_overtime.php',$this->var);
	}
	
	function approved_overtime() {

		$frequency_id = 1;

		if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
			$frequency_id = $_GET['selected_frequency'];
		}

		$this->var['selected_frequency'] = $frequency_id;

		if( isset($_GET['cutoff_period']) ) {
			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;

			if ($frequency_id == 2) {
				$cutoff_data  	   = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			else if ($frequency_id == 3) {
				$cutoff_data  	   = G_Monthly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			else {
				$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			if($cutoff_data) {
				$from_date  = $cutoff_data->getStartDate();
				$to_date 	= $cutoff_data->getEndDate();
				$eid        = Utilities::encrypt($cutoff_data->getId());
				$hpid       = $eid;
			}

		} else {
		    $from_date	= $_GET['from'];
	        $to_date 	= $_GET['to'];
	        $hpid 		= $_GET['hpid'];			
		}

        $this->var['group_id'] = $group_id = (int) $_GET['group_id'];
        $side_bar = $_GET['sidebar'];
        $this->var['download_url'] = url("overtime/period?sidebar={$side_bar}&from={$from_date}&to={$to_date}&hpid={$hpid}&group_id={$group_id}&download=yes&selected_frequency={$frequency_id}");

        //General Reports / Shr Audit Trail
        list($p_year, $p_month, $p_day) = explode('-', $from_date);
        $pmonthN = date("F", mktime(0, 0, 0, $p_month, 10));
        if($p_day >= 16){
        	$cut_of_period = $p_year.'-'.$pmonthN.'-B';
        }
        else{
            $cut_of_period = $p_year.'-'.$pmonthN.'-A';	
        }

		if($_GET['selected_frequency'] == 1){
			$frequency_name = 'Bi-Monthly';
		}
		else{
			$frequency_name = 'Weekly';
		}
        //end

        $eids = $_GET['eids'];
        if( !empty($eids) ){
        	$ids = explode(",", $eids);
        	foreach( $ids as $id ){
        		$a_new_ids[] = Utilities::decrypt($id);
        	}
        	$s_new_ids = implode(",", $a_new_ids);
        	$additional_query = " AND employee_id IN($s_new_ids)";        	
        }

	    if ($_GET['download'] == 'yes') {
            if ($group_id) {
                $records = G_Overtime_Finder::findAllApprovedByGroupIdAndPeriod($group_id, $from_date, $to_date, $additional_query);
            } else {
                $records = G_Overtime_Finder::findAllApprovedByPeriod($from_date, $to_date, $additional_query);
            }
            $this->var['overtime'] = $records;
            $this->var['filename'] = "approved_overtime_{$from_date}_{$to_date}.xls";
            
            $has_overtime = false;
            if ($records) {
                $has_overtime = true;
                 //General Reports / Shr Audit Trail
                $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_GENERATE, ' Overtime Approved Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency,', $from_date, $to_date, 1, '', '');
            }
            
            if($records == false){
            	 //General Reports / Shr Audit Trail
            	 $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_GENERATE, ' Overtime Approved Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency, No Data', $from_date, $to_date, 0, '', '');

            }

            $this->var['has_overtime'] = $has_overtime;

            $this->view->noTemplate();
            $this->view->render('overtime/overtime_download.php', $this->var);
            exit;
        }

		$this->var['approved'] 	= 'selected';
		$this->var['sidebar']	= 2;
		$this->var['module'] 	= 'overtime';
        $this->var['sub_title'] = 'Approved Overtime';
		$this->view->setTemplate('template_leftsidebar.php');

        $from_date	= $from_date;
        $to_date 	= $to_date;

        $per_page = 100;
        $page_number = (int) $_GET['pageID'];
        if ($page_number > 0) {
            $page_number--;
            $start_record = $page_number * $per_page;
        } else {
            $start_record = $page_number;
        }

        $this->var['group_id'] = $group_id = (int) $_GET['group_id'];
        if ($group_id) {
            G_Overtime_Finder::countRecords();
            $total_records = G_Overtime_Finder::findAllApprovedByGroupIdAndPeriod($group_id, $from_date, $to_date, $additional_query);

            G_Overtime_Finder::setLimit($start_record, $per_page);
            $records = G_Overtime_Finder::findAllApprovedByGroupIdAndPeriod($group_id, $from_date, $to_date, $additional_query);
        } else {
            G_Overtime_Finder::countRecords();
            $total_records = G_Overtime_Finder::findAllApprovedByPeriod($from_date, $to_date, $additional_query);

            G_Overtime_Finder::setLimit($start_record, $per_page);
            $records = G_Overtime_Finder::findAllApprovedByPeriod($from_date, $to_date, $additional_query);
        }
        $this->var['overtime'] = $records;

        // PAGER
        require_once 'Pager.php';

        $params = array(
            'mode'       => 'Sliding',
            'perPage'    => $per_page,
            'itemData'   => $records,
        );

        $pager =& Pager::factory($params);

        unset($pager);

        $params = array(
            'mode'     => 'Sliding',
            'perPage'  => $per_page,
            'delta'    => 5,
            'totalItems' => $total_records
        );
        $pager =& Pager::factory($params);
        $links = $pager->getLinks();
        $this->var['pager_links'] = $links['all'];

        $has_overtime = false;
        if ($records) {
            $has_overtime = true;

			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, ' Overtime Approved Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency', $from_date, $to_date, 1, '', '');	

        }

        if($records == false){
        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, ' Overtime Approved Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency, No Data', $from_date, $to_date, 0, '', '');
        }


        $this->var['has_overtime'] = $has_overtime;

        $this->var['departments'] = G_Group_Finder::findAllDepartments();
		$this->view->render('overtime/period.php',$this->var);
	}

	function disapproved_overtime() {

		$frequency_id = 1;

		if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
			$frequency_id = $_GET['selected_frequency'];
		}

		$this->var['selected_frequency'] = $frequency_id;

		if( isset($_GET['cutoff_period']) ) {
			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;

			if ($frequency_id == 2) {
				$cutoff_data  	   = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			else if ($frequency_id == 3) {
				$cutoff_data  	   = G_Monthly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			else {
				$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			if($cutoff_data) {
				$from_date  = $cutoff_data->getStartDate();
				$to_date 	= $cutoff_data->getEndDate();
				$eid        = Utilities::encrypt($cutoff_data->getId());
				$hpid       = $eid;
			}
		} else {
		    $from_date	= $_GET['from'];
	        $to_date 	= $_GET['to'];
	        $hpid 		= $_GET['hpid'];			
		}

        $this->var['group_id'] = $group_id = (int) $_GET['group_id'];
        $side_bar = $_GET['sidebar'];
        $this->var['download_url'] = url("overtime/period?sidebar={$side_bar}&from={$from_date}&to={$to_date}&hpid={$hpid}&group_id={$group_id}&download=yes&selected_frequency={$frequency_id}");

        //General Reports / Shr Audit Trail
        list($p_year, $p_month, $p_day) = explode('-', $from_date);
        $pmonthN = date("F", mktime(0, 0, 0, $p_month, 10));
        if($p_day >= 16){
        	$cut_of_period = $p_year.'-'.$pmonthN.'-B';
        }
        else{
            $cut_of_period = $p_year.'-'.$pmonthN.'-A';	
        }

		if($_GET['selected_frequency'] == 1){
			$frequency_name = 'Bi-Monthly';
		}
		else{
			$frequency_name = 'Weekly';
		}
        //end

        $eids = $_GET['eids'];
        if( !empty($eids) ){
        	$ids = explode(",", $eids);
        	foreach( $ids as $id ){
        		$a_new_ids[] = Utilities::decrypt($id);
        	}
        	$s_new_ids = implode(",", $a_new_ids);
        	$additional_query = " AND employee_id IN($s_new_ids)";        	
        }

	    if ($_GET['download'] == 'yes') {
            if ($group_id) {
                $records = G_Overtime_Finder::findAllDisapprovedByGroupIdAndPeriod($group_id, $from_date, $to_date, $additional_query);
            } else {
                $records = G_Overtime_Finder::findAllDisapprovedByPeriod($from_date, $to_date, $additional_query);
            }
            $this->var['overtime'] = $records;
            $this->var['filename'] = "disapproved_overtime_{$from_date}_{$to_date}.xls";
            $has_overtime = false;
            if ($records) {
                $has_overtime = true;
                 //General Reports / Shr Audit Trail
                $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_GENERATE, ' Overtime Disapproved Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency,', $from_date, $to_date, 1, '', '');
            }
            
            if($records == false){
            	 //General Reports / Shr Audit Trail
            	 $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_GENERATE, ' Overtime Disapproved Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency, No Data', $from_date, $to_date, 0, '', '');
            }

            $this->var['has_overtime'] = $has_overtime;

            $this->view->noTemplate();
            $this->view->render('overtime/overtime_download.php', $this->var);
            exit;
        }

        $this->var['disapproved'] 	= 'selected';
        $this->var['sidebar']	= 5;
        $this->var['module'] 	= 'overtime';
        $this->var['sub_title'] = 'Disapproved Overtime';
        $this->view->setTemplate('template_leftsidebar.php');

        $from_date	= $from_date; //$_GET['from'];
        $to_date 	= $to_date; //$_GET['to'];

        $per_page = 10;
        $page_number = (int) $_GET['pageID'];
        if ($page_number > 0) {
            $page_number--;
            $start_record = $page_number * $per_page;
        } else {
            $start_record = $page_number;
        }

        $this->var['group_id'] = $group_id = (int) $_GET['group_id'];
        if ($group_id) {
            G_Overtime_Finder::countRecords();
            $total_records = G_Overtime_Finder::findAllDisapprovedByGroupIdAndPeriod($group_id, $from_date, $to_date, $additional_query);

            G_Overtime_Finder::setLimit($start_record, $per_page);
            $records = G_Overtime_Finder::findAllDisapprovedByGroupIdAndPeriod($group_id, $from_date, $to_date, $additional_query);
        } else {
            G_Overtime_Finder::countRecords();
            $total_records = G_Overtime_Finder::findAllDisapprovedByPeriod($from_date, $to_date, $additional_query);

            G_Overtime_Finder::setLimit($start_record, $per_page);
            $records = G_Overtime_Finder::findAllDisapprovedByPeriod($from_date, $to_date, $additional_query);
        }
        $this->var['overtime'] = $records;
        // PAGER
        require_once 'Pager.php';

        $params = array(
            'mode'       => 'Sliding',
            'perPage'    => $per_page,
            'itemData'   => $records,
        );

        $pager =& Pager::factory($params);

        unset($pager);

        $params = array(
            'mode'     => 'Sliding',
            'perPage'  => $per_page,
            'delta'    => 5,
            'totalItems' => $total_records
        );
        $pager =& Pager::factory($params);
        $links = $pager->getLinks();
        $this->var['pager_links'] = $links['all'];

        $has_overtime = false;
        if ($records) {
            $has_overtime = true;

			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, ' Overtime Disapproved Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency', $from_date, $to_date, 1, '', '');	

        }

        if($records == false){
        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, ' Overtime Disapproved Requests of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency, No Data', $from_date, $to_date, 0, '', '');
        }

        $this->var['has_overtime'] = $has_overtime;

        //$this->var['departments'] = $departments = G_Company_Structure_Finder::findParentChildByCompanyStructureId($this->company_structure_id);
        $this->var['departments'] = G_Group_Finder::findAllDepartments();
        $this->view->render('overtime/period.php',$this->var);
    }
	
	function overtime_history() {
		$this->var['history'] 	= 'selected';
		$this->var['sidebar']	= 3;
		$this->var['module'] 	= 'overtime';
		$this->view->setTemplate('template_leftsidebar.php');
		
		//$this->var['departments'] = $departments = G_Company_Structure_Finder::findParentChildByCompanyStructureId($this->company_structure_id);
        $this->var['departments'] = G_Group_Finder::findAllDepartments();
		$this->view->render('overtime/period.php',$this->var);
	}
	
	function archived_overtime() {
		$this->var['archives'] 	= 'selected';
		$this->var['sidebar']	= 4;
		$this->var['module'] 	= 'overtime';
		$this->view->setTemplate('template_leftsidebar.php');
		
		$this->var['departments'] = $departments = G_Company_Structure_Finder::findParentChildByCompanyStructureId($this->company_structure_id);
		$this->view->render('overtime/period.php',$this->var);
	}

    function error_reports() {

					$frequency_id = 1;

					if( isset($_GET['selected_frequency']) && !empty($_GET['selected_frequency']) ) {
						$frequency_id = $_GET['selected_frequency'];
					}

					$this->var['selected_frequency'] = $frequency_id;

					if( isset($_GET['cutoff_period']) ) {
						$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
						$period_start      = $cutoff_period_arr[0];
						$period_end   	   = $cutoff_period_arr[1];
						$this->var['cutoff_selected'] = $period_start."/".$period_end;

						if ($frequency_id == 2) {
							$cutoff_data  	   = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
						}
						else if ($frequency_id == 3) {
							$cutoff_data  	   = G_Monthly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
						}
						else {
							$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
						}

						if($cutoff_data) {
							$from_date  = $cutoff_data->getStartDate();
							$to_date 	= $cutoff_data->getEndDate();
							$eid        = Utilities::encrypt($cutoff_data->getId());
							$hpid       = $eid;
						}
					} else {
					    $from_date	= $_GET['from'];
	        $to_date 	= $_GET['to'];
	        $hpid 		= $_GET['hpid'];			
					}

	    // $from_date	= $_GET['from'];
     //    $to_date = $_GET['to'];
     //    $hpid = $_GET['hpid'];
        $this->var['group_id'] = $group_id = (int) $_GET['group_id'];
        $side_bar = $_GET['sidebar'];
        $this->var['download_url'] = url("overtime/period?sidebar={$side_bar}&from={$from_date}&to={$to_date}&hpid={$hpid}&group_id={$group_id}&download=yes&selected_frequency={$frequency_id}");

         //General Reports / Shr Audit Trail
        list($p_year, $p_month, $p_day) = explode('-', $from_date);
        $pmonthN = date("F", mktime(0, 0, 0, $p_month, 10));
        if($p_day >= 16){
        	$cut_of_period = $p_year.'-'.$pmonthN.'-B';
        }
        else{
            $cut_of_period = $p_year.'-'.$pmonthN.'-A';	
        }

		if($_GET['selected_frequency'] == 1){
			$frequency_name = 'Bi-Monthly';
		}
		else{
			$frequency_name = 'Weekly';
		}

	    if ($_GET['download'] == 'yes') {
            if ($group_id) {
                $records = G_Overtime_Error_Finder::findAllErrorsNotFixedByGroupIdAndPeriod($group_id, $from_date, $to_date);
            } else {
                $records = G_Overtime_Error_Finder::findAllErrorsNotFixedByPeriod($from_date, $to_date);
            }
            $this->var['errors'] = $records;
            $this->var['filename'] = "error_overtime_{$from_date}_{$to_date}.xls";
            $has_overtime = false;
            if ($records) {
                $has_overtime = true;
                 //General Reports / Shr Audit Trail
                $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_GENERATE, ' Overtime Error Reports of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency,', $period_start, $period_end, 1, '', '');
            }
            
            if($records == false){
            	 //General Reports / Shr Audit Trail
            	 $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_GENERATE, ' Overtime Error Reports of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency, No Data', $from_date, $to_date, 0, '', '');
            }
            $this->var['has_error'] = $has_error;

            $this->view->noTemplate();
            $this->view->render('overtime/error_download.php', $this->var);
            exit;
        }

        $this->var['archives'] 	= 'selected';
        $this->var['sidebar']	= 4;
        $this->var['module'] 	= 'overtime';
        $this->var['sub_title'] = 'Overtime Error Report';
        $this->view->setTemplate('template_leftsidebar.php');

        $from_date	= $_GET['from'];
        $to_date = $_GET['to'];

        //echo $_GET['group_id'];
        //echo '<br>';

        $per_page = 10;
        $page_number = (int) $_GET['pageID'];
        if ($page_number > 0) {
            $page_number--;
            $start_record = $page_number * $per_page;
        } else {
            $start_record = $page_number;
        }

        $this->var['group_id'] = $group_id = (int) $_GET['group_id'];
        if ($group_id) {
            G_Overtime_Error_Finder::countRecords();
            $total_records = G_Overtime_Error_Finder::findAllErrorsNotFixedByGroupIdAndPeriod($group_id, $from_date, $to_date);

            G_Overtime_Error_Finder::setLimit($start_record, $per_page);
            $records = G_Overtime_Error_Finder::findAllErrorsNotFixedByGroupIdAndPeriod($group_id, $from_date, $to_date);
        } else {
            G_Overtime_Error_Finder::countRecords();
            $total_records = G_Overtime_Error_Finder::findAllErrorsNotFixedByPeriod($from_date, $to_date);

            G_Overtime_Error_Finder::setLimit($start_record, $per_page);
            $records = G_Overtime_Error_Finder::findAllErrorsNotFixedByPeriod($from_date, $to_date);
        }
        $this->var['errors'] = $records;

        // PAGER
        require_once 'Pager.php';

        $params = array(
            'mode'       => 'Sliding',
            'perPage'    => $per_page,
            'itemData'   => $records,
        );

        $pager =& Pager::factory($params);

        unset($pager);

        $params = array(
            'mode'     => 'Sliding',
            'perPage'  => $per_page,
            'delta'    => 5,
            'totalItems' => $total_records
        );
        $pager =& Pager::factory($params);
        $links = $pager->getLinks();
        $this->var['pager_links'] = $links['all'];

         //General Reports / Shr Audit Trail
        if($period_start == ''){
        	$new_period_start = $from_date;
    	}
    	else{
    		$new_period_start = $period_start;
    	}
    	if($period_end == ''){
    		$new_period_end = $to_date;
    	}
    	else{
    		$new_period_end = $period_end;
    	}

        $has_error = false;
        if ($records) {
            $has_error = true;
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, ' Overtime Error Reports of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency', $from_date, $to_date, 1, '', '');	
        }

        if($records == false){
        	
        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, ' Overtime Error Reports of ',' Cut Off Period of '.$cut_of_period.' and '.$frequency_name.' Frequency, No Data', $new_period_start, $new_period_end, 0, '', '');
        }
       
        $this->var['has_error'] = $has_error;

        //$this->var['departments'] = $departments = G_Company_Structure_Finder::findParentChildByCompanyStructureId($this->company_structure_id);
        $this->var['departments'] = G_Group_Finder::findAllDepartments();//G_Company_Structure_Finder::findParentChildByCompanyStructureId($this->company_structure_id);
        $this->view->render('overtime/period.php',$this->var);
    }


	function _load_pending_overtime_list_dt() {
		$this->var['errors'] = $errors = G_Overtime_Error_Finder::countAllErrorsNotFixed();
		if($errors > 0) {
			$this->var['total_errors'] = $errors;
		}
		$data['total_pending'] = $total_pending = G_Employee_Overtime_Request_Helper::countTotalPendingRequest();
		$this->view->render('overtime/_pending_overtime_list_dt.php',$this->var);
	}
	
	function _load_server_pending_overtime_list_dt() {
		Utilities::ajaxRequest();
		
		if($_GET['department']) {
			$sqlcat 	= " LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON e.id = gsh.employee_id";
			$sqlcond	= " AND gsh.company_structure_id = " . Utilities::decrypt($_GET['department'])." ";
		}
		
		$sqlcond .= ' AND jbh.end_date = "" AND date_start BETWEEN ' . Model::safeSql($_GET['from']) . ' AND ' . Model::safeSql($_GET['to']);
		
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
		if($_SESSION['sprint_hr']['is_period_lock'] == G_Cutoff_Period::NO){
			$dt->setNumCustomColumn(2);
			$dt->setCustomColumn(	
			array(			
				'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Edit\" id=\"delete\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editOvertimeRequestForm(\'e_id\');\"></a></li><li><a title=\"Approve\" id=\"delete\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveOvertimeRequest(\'e_id\');\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveOvertimeRequest(\'e_id\',1)\"></a></li></ul></div>'));
		} else {
			$dt->setNumCustomColumn(0);
		}
		echo $dt->constructDataTable();
	}
	
	function ajax_add_new_overtime_request() {
	
		$this->var['from']	= $_POST['from_period'];
		$this->var['to']	= $_POST['to_period'];
		
		$this->var['token']		 = Utilities::createFormToken();	
		$this->var['page_title'] = 'Add New Overtime Request';	
		$this->view->render('overtime/form/request_overtime.php',$this->var);
	}

	function _load_get_employee_request_approvers() {
		
		if(!empty($_POST)) {
			$employee  = G_Employee_Finder::findById(Utilities::decrypt($_POST['h_employee_id']));
			if($employee) {				
				$employee_id = $employee->getId();
				$gra = new G_Request_Approver();
				$gra->setEmployeeId($employee_id);
				$approvers = $gra->getEmployeeRequestApprovers();

				$this->var['approvers'] = $approvers;		
				$this->view->render('overtime/form/_show_request_approvers.php',$this->var);
			}		
		}
	}

	function _load_custom_overtime_list() {
		if( $_GET['date_from'] != '' && $_GET['date_to'] != '' ){
			$frequency_id = 1;

			if( isset($_GET['frequency_id']) && !empty($_GET['frequency_id']) ) {
				$frequency_id = $_GET['frequency_id'];
			}


			$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_overtime');
			$this->var['date_from'] = $_GET['date_from'];
			$this->var['date_to']   = $_GET['date_to'];
			$this->var['frequency_id']   = $frequency_id;
			$this->view->render('overtime/_custom_overtime_list_dt.php',$this->var);
		}else{
			echo "Invalid date selected";
		}
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

	function _load_server_custom_overtime_list_dt() {
		Utilities::ajaxRequest();

		$date_from = $_GET['from'];
		$date_to   = $_GET['to'];

		$frequency_id = 1;

		if( isset($_GET['frequency_id']) && !empty($_GET['frequency_id']) ) {
			$frequency_id = $_GET['frequency_id'];
		}

		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_CUSTOM_OVERTIME);
		$dt->setSQL("
			SELECT co.id, co.employee_id, co.date, co.start_time, co.end_time, co.day_type, co.status AS co_status,
				CONCAT(e.lastname, ' ', e.firstname)AS employee_name, jbh.name AS position 
			FROM " . G_CUSTOM_OVERTIME . " co 
				LEFT JOIN " . EMPLOYEE . " e ON co.employee_id = e.id 
				LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON co.employee_id = jbh.employee_id AND jbh.end_date = '' 
		");				
		$dt->setCountSQL("SELECT COUNT(co.id) as c FROM " . G_CUSTOM_OVERTIME . " co LEFT JOIN " . EMPLOYEE . " e ON co.employee_id = e.id LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON co.employee_id = jbh.employee_id AND jbh.end_date = ''");				
		$dt->setCondition("co.date BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to));		    	        
		$dt->setColumns('employee_name,position,date,start_time,end_time,day_type,co_status');            		
		$dt->setPreDefineSearch(
		   array(
		   	"employee_name" => "e.lastname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%' OR e.firstname LIKE '%" . addslashes($_REQUEST['sSearch']) . "%'",
			"position" => "jbh.name = " . Model::safeSql(addslashes($_REQUEST['sSearch'])),			
			"co_status" => "co.status = " . Model::safeSql(addslashes($_REQUEST['sSearch']))
			)
		);		
		$dt->setOrder('ASC');
		$dt->setSort(0);	
		if($_SESSION['sprint_hr']['is_period_lock'] == ($frequency_id != 2 ? G_Cutoff_Period::NO : G_Weekly_Cutoff_Period::NO)){
			$dt->setCustomColumn(
					array(		
							1 => '<div class=\"i_container\"><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></div>',
							2 => '<div class=\"btn-group pull-right\"><a class=\"btn dropdown-toggle\" href=\"#\">Action <span class=\"caret\"></span></a><ul class=\"dropdown-menu\"><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-edit-custom-overtime\" ><i class=\"icon-pencil\"></i> Edit </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-approve-custom-overtime\" ><i class=\"icon-ok\"></i> Approve </a></li><li><a href=\"javascript:void(0);\" id=\"e_id\" class=\"btn-disapprove-custom-overtime\" ><i class=\"icon-remove\"></i> Disapprove </a></li></ul></div>'
			));	
		} else {
			$dt->setCustomColumn(
					array(		
							1 => '<div></div>',
							2 => '<div></div>'
			));
		}						

		echo $dt->constructDataTableRightTools();
	}
	
	function _load_server_generic_overtime_list_dt() {
		
		if($_GET['department']) {
			$sqlcat 	= " LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " gsh ON e.id = gsh.employee_id";
			$sqlcond	= " AND gsh.company_structure_id = " . Utilities::decrypt($_GET['department'])."";
		}
		
		if($_GET['sidebar'] == 2) {
			$condition = ' is_approved = "'.G_Employee_Overtime_Request::APPROVED.'"  AND is_archive = "'.G_Employee_Overtime_Request::NO.'"'.$sqlcond;
		} else {
			$condition = ' is_archive = "'.G_Employee_Overtime_Request::YES.'"'.$sqlcond;
		}
		
		$condition .= ' AND jbh.end_date = "" AND date_start BETWEEN ' . Model::safeSql($_GET['from']) . ' AND ' . Model::safeSql($_GET['to']);
		
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
		//$dt->setNumCustomColumn(0);
		if($_SESSION['sprint_hr']['is_period_lock'] == G_Cutoff_Period::NO){
			$dt->setNumCustomColumn(2);
			$dt->setCustomColumn(	
			array(			
				'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Disapprove\" id=\"delete\" class=\"ui-icon ui-icon-close g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:disApproveOvertimeRequest(\'e_id\');\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveOvertimeRequest(\'e_id\',2)\"></a></li></ul></div>'));
		} else {
			$dt->setNumCustomColumn(0);
		}
		echo $dt->constructDataTable();
	}
	
	
	function load_show_overtime_history_details() {
		if(!empty($_POST['h_employee_id'])) {
			$this->load_summary_photo();
			$this->var['employee'] 		= $employee = G_Employee_Helper::findByEmployeeId(Utilities::decrypt($_POST['h_employee_id']));
			$this->var['h_employee_id'] = $_POST['h_employee_id'];
			$this->view->render('overtime/overtime_list/show_overtime_history_details.php',$this->var);
		}
	}
	
	function load_summary_photo()
	{
		$employee_id = Utilities::decrypt($_POST['h_employee_id']);
		$e = G_Employee_Finder::findById($employee_id);
		$file = PHOTO_FOLDER.$e->getPhoto();
		
		if(Tools::isFileExist($file)==true && $e->getPhoto()!='') {
			$this->var['filemtime'] = md5($e->getPhoto()).date("His");
			$this->var['filename'] = $file;			
		}else {
			$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';		
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
			$sqlcond	= " AND gsh.company_structure_id = " . Utilities::decrypt($_GET['department'])." ";
		}
	
		$condition 	= ' is_archive = "'.G_Employee_Overtime_Request::YES.'"'.$sqlcond;
		$condition .= ' AND jbh.end_date = "" AND date_start BETWEEN ' . Model::safeSql($_GET['from']) . ' AND ' . Model::safeSql($_GET['to']);
		
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
		if($_SESSION['sprint_hr']['is_period_lock'] == G_Cutoff_Period::NO){ 
			$dt->setNumCustomColumn(1);
			$dt->setCustomColumn(	
			array(
			'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><input type=\"checkbox\" name=\"dtChk[]\" onclick=\"javascript:enableDisableWithSelected();\" value=\"id\"></li><li><a title=\"Restore Archived\" id=\"delete\" class=\"ui-icon ui-icon-refresh g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:restoreOvertimeRequest(\'e_id\',\'is_approved\')\"></a></li></ul></div>'));	
		}
		$dt->setNumCustomColumn(0);
		echo $dt->constructDataTable();
	}
	
	function _load_restore_overtime_request() 
	{
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
	
	function _load_clear_import_error() {
		$errors = G_Overtime_Error_Finder::findAllErrorsNotFixed();
		foreach($errors as $e):
			$e->setAsFixed(YES);
			$e->addError();
		endforeach;	
	}
	
	function download_ot_error_log() {
		$this->var['filename'] 	 = 'overtime_errors_'.date('m-d-y').'.xls';
		$this->var['error_logs'] = $error_logs = G_Overtime_Error_Finder::findAllErrorsNotFixed();
		$this->view->render('overtime/error_log_report.html.php',$this->var);
	}
	
	function download_ot() {
		if(!empty($_GET)) {
		
			ini_set("memory_limit", "999M");
			set_time_limit(999999999999999999999);
			
			$from 	= $_GET['from'];
			$to		= $_GET['to'];
			$this->var['filename'] = "overtime_list_{$from}_{$to}.xls";
			$this->var['overtime'] = $overtime = G_Employee_Overtime_Request_Finder::findAllActiveOvertimeByFromTo($from,$to);
			
			$this->view->render('overtime/download_ot.html.php',$this->var);
		}
	}

	function ajax_load_payroll_period_by_year()
	{
		$selected_year = $_GET['selected_year'];
		$selected_frequency = $_GET['selected_frequency'];
		if( $selected_year == '' || $selected_year <= 0 ){
			$selected_year = date("Y");
		}		

        $selected_year = $selected_year;

        if ($selected_frequency == 2) {
       	 $c = G_Weekly_Cutoff_Period_Finder::findAllCutoffByYear($selected_year);
        }
         else if ($selected_frequency ==  3) {
       	 $c = G_Monthly_Cutoff_Period_Finder::findAllCutoffByYear($selected_year);
        }
        
        else {
       	 $c = G_Cutoff_Period_Finder::findAllCutoffByYear($selected_year);
        }

        $this->var['selected_cutoff'] = $_GET['selected_cutoff'];
        $this->var['selected_year']   = $selected_year;
        $this->var['selected_frequency']   = $selected_frequency;
        $this->var['cutoff_periods']  = $c;
		$this->view->noTemplate();
		$this->view->render('overtime/_payroll_period.php',$this->var);
	}		

}
?>