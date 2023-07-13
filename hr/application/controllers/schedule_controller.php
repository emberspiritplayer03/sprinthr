<?php
class Schedule_Controller extends Controller
{
	function __construct()
	{
	    $this->login();
        $this->module = 'hr'; // used in global_controller->has_access_module()

		parent::__construct();

		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainScript('schedule_base.js');
		Loader::appMainScript('schedule.js');
		Loader::appMainUtilities();		
		Loader::appStyle('style.css');

		/*
		$n = new G_Notifications();
        $n->updateNotifications();
        $count_all_new_notifications = $n->countNotifications();
        */

        $count_all_new_notifications = 1;
		$this->sprintHdrMenu(G_Sprint_Modules::HR, 'schedule');		
		$this->var['schedule'] = 'selected';
		$this->var['employee'] = 'selected';
		$this->var['page_title'] = '<a href="'. url('schedule') .'">Schedule</a>';
		$this->validatePermission(G_Sprint_Modules::HR,'schedule','');
	}


	function index()
	{
		Utilities::verifyAccessRights($employee_id,$module,$action);
	
		Jquery::loadMainTextBoxList();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();

		Loader::appMainScript('restday_base.js');
		Loader::appMainScript('restday.js');

        if ($_GET['month'] == '') {
            $show_month = $this->var['show_month'] = date("n");
        } else {
            $show_month = $this->var['show_month'] = $_GET['month'];
        }

        if ($_GET['year'] == '') {
            $show_year = $this->var['show_year'] = date("Y");
        } else {
            $show_year = $this->var['show_year'] = $_GET['year'];
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['count_schedule_notifications']  = $count_schedule_notifications = 1;        

        $_SESSION['show_schedule_month'] = $show_month;
        $_SESSION['show_schedule_year'] = $show_year;

		$this->var['page_title'] = 'Schedule';
		$this->var['action'] = url('schedule/show_employee');
        $this->var['months'] = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $this->var['years'] = array(date('Y'), date('Y')-1);

		//$this->var['schedules'] = G_Schedule_Finder::findAll();

		$btn_create_schedule_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:createWeeklySchedule();',
    		'id' 					=> 'add_employee_button_wrapper',
    		'class' 				=> 'add_button tooltip',
    		'icon' 					=> '',
    		'additional_attribute' 	=> 'title="Create New Schedule"',
    		'caption' 				=> '<strong>+</strong><b>Create Schedule</b>'
    		); 
    	
    	$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
    	$this->var['btn_create_schedule'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_create_schedule_config);

		$this->view->setTemplate('template_schedule.php');
		$this->view->render('schedule/index.php',$this->var);		
	}
	
	function schedule_error()
	{
		$this->view->setTemplate('template.php');
		$this->view->render('attendance/error/schedule_error.php',$this->var);		
	}

    /*
    *   Deprecated
    */
	function show_employee_old() {
		Jquery::loadMainTextBoxList();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();

		Loader::appMainScript('restday_base.js');
		Loader::appMainScript('restday.js');

		$this->var['query'] = $query = $_GET['query'];
		$this->var['action'] = url('schedule/show_employee');
		if ($query != '') {
			$this->var['employees'] = G_Employee_Finder::searchAllByFirstnameAndLastnameAndEmployeeCodeAndDepartmentNameAndSection($query);
		}
		$this->view->setTemplate('template.php');
		$this->view->render('schedule/show_employee.php',$this->var);
	}

	function show_employee() {
		Jquery::loadMainTextBoxList();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();

		Loader::appMainScript('restday_base.js');
		Loader::appMainScript('restday.js');

		$this->var['query'] = $query = $_GET['query'];
		$this->var['action'] = url('schedule/show_employee');
		if ($query != '') {
			//$this->var['employees'] = G_Employee_Finder::searchActiveByFirstnameAndLastnameAndEmployeeCode($query);
            $this->var['employees'] = G_Employee_Finder::searchAllByFirstnameAndLastnameAndEmployeeCodeAndDepartmentNameAndSection($query);
		}

		//Check if query string is department or section
		$btn_schedule_department_section = '';
		$fields = array("id","title","type","parent_id");
		$cs = new G_Company_Structure();
		$cs->setTitle($query);
		$cs_data = $cs->getDepartmentDetailsByTitle($fields);

		if( !empty($cs_data) ){			
			$eid = Utilities::encrypt($cs_data['id']);
			$btn_schedule_department_section = "<a data-index=\"{$eid}\" class=\"edit_button btn-show-dept-section-schedule\" href=\"javascript:void(0);\"><i class=\"icon icon-calendar\"></i>Show <b>" . $cs_data['title'] . "</b> Schedules</a>";
		}
		$this->var['btn_schedule_department_section'] = $btn_schedule_department_section;
		$this->view->setTemplate('template.php');
		$this->view->render('schedule/show_employee_list.php',$this->var);
	}

	function show_department_schedule() {
		Jquery::loadMainTextBoxList();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();

		Loader::appMainScript('restday_base.js');
		Loader::appMainScript('restday.js');

		$eid = $_GET['eid'];		
		if( !empty($eid) ){
			$id = Utilities::decrypt($eid);
			$cs = new G_Company_Structure();
			$cs->setId($id);
			$schedules = $cs->getSchedules();
			$group_id  = $schedules['group_details']['id'];
	        $btn_add_change_schedule_config = array(
		    		'module'				=> 'hr',
		    		'parent_index'			=> 'schedule',
		    		'child_index'			=> '',
		    		'href' 					=> 'javascript:void(0);',
		    		'onclick' 				=> 'javascript:createSpecificSchedule();',
		    		'id' 					=> '',
		    		'class' 				=> '',
		    		'icon' 					=> '',
		    		'wrapper_start'			=> '(',
		    		'wrapper_end'			=> ')',
		    		'additional_attribute' 	=> 'title="Add Change Schedule"',
		    		'caption' 				=> 'Add Change Schedule'
	    		);

	    	$btn_assign_new_schedule_config = array(
		    		'module'				=> 'hr',
		    		'parent_index'			=> 'schedule',
		    		'child_index'			=> '',
		    		'href' 					=> 'javascript:void(0);',
		    		'onclick' 				=> 'javascript:createAndAssignWeeklyScheduleToGroup("' . $group_id . '");',
		    		'id' 					=> '',
		    		'class' 				=> '',
		    		'icon' 					=> '',
		    		'wrapper_start'			=> '(',
		    		'wrapper_end'			=> ')',
		    		'additional_attribute' 	=> 'title="Assign New Schedule"',
		    		'caption' 				=> 'Assign New Schedule'
	    		); 

	    	$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
	    	$current_year      = date("Y");

		    for($start_month = 1; $start_month <= 12; $start_month++){
		    	$c = new G_Calendar_Restday($current_year, $start_month);
	       		$c->setGroupId($id);
	        	$c->setPermission($permission_action);
		    	$calendar .= "<li>" . $c->groupDisplayRd() . "</li>";
		    }

		    $this->var['action'] 					= url("schedule/show_employee");
			$this->var['group_details']				= $schedules['group_details'];
			$this->var['schedules']                 = $schedules['schedules'];
		    $this->var['permission_action'] 		= $permission_action;
		    $this->var['btn_add_change_schedule'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_change_schedule_config);
		    $this->var['btn_assign_new_schedule'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_assign_new_schedule_config);	  		
	        $this->var['calendar'] = $calendar;
	        $this->view->setTemplate('template.php');
	        $this->view->render('schedule/show_group_schedules.php',$this->var);
		}else{
			redirect('schedule');
		}
	}

    function show_employee_schedule() {
		Jquery::loadMainTextBoxList();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();

		Loader::appMainScript('restday_base.js');
		Loader::appMainScript('restday.js');

		$encrypted_employee_id = $this->var['encrypted_employee_id'] = $_GET['eid'];
        $hash = $this->var['hash'] = $_GET['hash'];

        if ($_GET['month'] == '') {
            $show_month = $this->var['show_month'] = date("n");
        } else {
            $show_month = $this->var['show_month'] = $_GET['month'];
        }        

        if ($_GET['year'] == '') {
            $show_year = $this->var['show_year'] = date("Y");
        } else {
            $show_year = $this->var['show_year'] = $_GET['year'];
        }        
		$employee_id = Utilities::decrypt($encrypted_employee_id);
		Utilities::verifyHash($employee_id, $hash);

		$e = G_Employee_Finder::findById($employee_id);
        $schedules = G_Schedule_Group_Helper::getAllScheduleGroupsByEmployeeAndMonthAndYear($e, $show_month, $show_year);
        //$schedules = G_Schedule_Group_Helper::getAllScheduleGroupsByEmployee($e);
        $changed_schedules = G_Schedule_Specific_Finder::findAllByEmployeeAndMonthAndYear($e, $show_month, $show_year);//G_Schedule_Specific_Finder::findAllByEmployee($e);

        //$rest_days = G_Restday_Finder::findAllByEmployee($e);
        $this->var['e_id'] = $e->getId();
        $this->var['name'] = $e->getName();
        $this->var['action'] = url('schedule/show_employee');
        $this->var['changed_schedules'] = $changed_schedules;
        $this->var['schedule_groups'] = $schedules;
        //$this->var['rest_days'] = $rest_days;

        $this->var['months'] = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");        
        $this->var['start_year'] = 2015;

        $btn_add_change_schedule_config = array(
	    		'module'				=> 'hr',
	    		'parent_index'			=> 'schedule',
	    		'child_index'			=> '',
	    		'href' 					=> 'javascript:void(0);',
	    		'onclick' 				=> 'javascript:createSpecificSchedule('.$e->getId().');',
	    		'id' 					=> '',
	    		'class' 				=> '',
	    		'icon' 					=> '',
	    		'wrapper_start'			=> '(',
	    		'wrapper_end'			=> ')',
	    		'additional_attribute' 	=> 'title="Add Change Schedule"',
	    		'caption' 				=> 'Add Change Schedule'
    		);

    	$btn_assign_new_schedule_config = array(
	    		'module'				=> 'hr',
	    		'parent_index'			=> 'schedule',
	    		'child_index'			=> '',
	    		'href' 					=> 'javascript:void(0);',
	    		'onclick' 				=> 'javascript:createAndAssignWeeklySchedule('.$e->getId().');',
	    		'id' 					=> '',
	    		'class' 				=> '',
	    		'icon' 					=> '',
	    		'wrapper_start'			=> '(',
	    		'wrapper_end'			=> ')',
	    		'additional_attribute' 	=> 'title="Assign New Schedule"',
	    		'caption' 				=> 'Assign New Schedule'
    		); 

	    $this->var['permission_action'] = $permission_action = $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
	    $this->var['btn_add_change_schedule'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_change_schedule_config);
	    $this->var['btn_assign_new_schedule'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_assign_new_schedule_config);	  
	
	    for($start_month = 1; $start_month <= 12; $start_month++){
	    	$c = new G_Calendar_Restday($show_year, $start_month);
       		$c->setEmployee($e);
        	$c->setPermission($permission_action);
	    	$calendar .= "<li>" . $c->display() . "</li>";
	    }
	            
        //$c->setPreviousLink('<a href="javascript:showRestdayCalendar('. $c->getPreviousMonth() .', '. $c->getPreviousYear() .')">Previous</a>');
        //$c->setNextLink('<a href="javascript:showRestdayCalendar('. $c->getNextMonth() .', '. $c->getNextYear() .')">Next</a>');
        $this->var['calendar']      = $calendar;
        $this->var['selected_year'] = $show_year;

        $this->view->setTemplate('template.php');
        $this->view->render('schedule/show_employee_schedules.php',$this->var);
    }

    function _copy_default_restday() {   
    	$return['message']    = "Cannot save data!";
		$return['is_success'] = false; 	
    	if( isset($_GET['eid'])){
    		$group_id = Utilities::decrypt($_GET['eid']);
    		$rd = new G_Group_Restday();
			$return = $rd->setGroupId($group_id)->getAllDefaultRestDay()->saveRestDaysToDepartment();

			if( $return['is_success'] ){
				//Update attendance
				$dates = $rd->a_rest_day;	
				$employees = G_Employee_Finder::findAllEmployeesByDepartmentSectionId($group_id);
				foreach( $employees as $e ){	
					foreach( $dates as $value ){
						$date = date("Y-m-d",strtotime($value['date']));
						G_Attendance_Helper::updateAttendance($e, $date);
					}
				}	
			}
    	}    	

    	echo json_encode($return);
    }

    function _copy_default_restday_to_employee() {   
    	$return['message']    = "Cannot save data!";
		$return['is_success'] = false; 	
    	if( isset($_GET['eid'])){    		
    		$employee_id = Utilities::decrypt($_GET['eid']);
			$rd = new G_Restday();
			$return = $rd->setEmployeeId($employee_id)->getAllDefaultRestDay()->convertArrayToObject()->saveDefaultRestDays();

			if( $return['is_success'] ){
				//Update attendance
				$e = G_Employee_Finder::findById($employee_id);
				if( $e ){
					$dates = $rd->a_rest_day;	
					foreach( $dates as $value ){
						$date = date("Y-m-d",strtotime($value['date']));
						G_Attendance_Helper::updateAttendance($e, $date);
					}
				}
			}
    	}    	

    	echo json_encode($return);
    }

    function _copy_default_restday_to_all_employee() {   

    	$return_restday['message']    = "Cannot save data!";
		$return_restday['is_success'] = false; 		

		$employees = G_Employee_Finder::findAllActiveEmployeesWithSelectedEmployeeFields(); 

		if($employees) {
			$updated_employee_count = 0;
			$success_id_array       = array();
			foreach($employees as $emp_key => $emp) {
				$rd = new G_Restday();
				$ret = $rd->setEmployeeId($emp['id'])->getAllDefaultRestDay()->convertArrayToObject()->saveDefaultRestDays();	

				if( $ret['is_success'] ) {

					//Update Attendance
					$e = G_Employee_Finder::findById($emp['id']);
					if( $e ){
						$dates = $rd->a_rest_day;	
						foreach( $dates as $value ){
							$date = date("Y-m-d",strtotime($value['date']));
							G_Attendance_Helper::updateAttendance($e, $date);
						}
						$success_id_array[] = $emp['id'];
					}
					$updated_employee_count++;
				}
			}

			if($updated_employee_count > 0) {
				$return_restday['message']    = "Default restday was successfully copied to all employees, total employee is: " . $updated_employee_count;
			} else {
				$return_restday['message']    = "No data to save";
			}
	    	
			$return_restday['is_success'] = true; 				

		}

		echo json_encode($return_restday);
    }    

	function show_schedule() {
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTipsy();		

		Loader::appMainScript('restday_base.js');
		Loader::appMainScript('restday.js');		
		
		$public_id = (string) $_GET['id'];
		$s = G_Schedule_Group_Finder::findByPublicId($public_id);
		if ($s) {
			$breaktime_schedules = $s->getAttachedBreaktimeSchedules();
			$breaktime_string    = implode(" / ", $breaktime_schedules);

			$this->var['breaktime']     = $breaktime_string;
			$this->var['public_id']     = $public_id;
			$this->var['schedule_id']   = $s->getId();
			$this->var['schedule_name'] = $title = $s->getName();
			$this->var['grace_period']  = $s->getGracePeriod();
            $this->var['effectivity_date'] = $s->getEffectivityDate();
            $this->var['end_date']         = $s->getEndDate();
			$this->var['title'] = '- '. $title;
			$schedules = G_Schedule_Finder::findAllByScheduleGroup($s);
			$this->var['schedule_date_time'] = G_Schedule_Helper::showSchedules($schedules);
			$this->view->setTemplate('template.php');

			$btn_edit_schedule_config = array(
	    		'module'				=> 'hr',
	    		'parent_index'			=> 'schedule',
	    		'child_index'			=> '',
	    		'href' 					=> 'javascript:void(0);',
	    		'onclick' 				=> 'javascript:editWeeklySchedule("'.$public_id.'");',
	    		'id' 					=> '',
	    		'class' 				=> 'tooltip edit_button',
	    		'icon' 					=> '',
	    		'additional_attribute' 	=> 'title="Edit this schedule"',
	    		'caption' 				=> 'Edit Schedule'
    		); 

    		$btn_delete_schedule_config = array(
	    		'module'				=> 'hr',
	    		'parent_index'			=> 'schedule',
	    		'child_index'			=> '',
	    		'href' 					=> 'javascript:void(0);',
	    		'onclick' 				=> 'javascript:deleteSchedule("'.$public_id.'");',
	    		'id' 					=> '',
	    		'class' 				=> 'relative delete_link red',
	    		'icon' 					=> '<span class="delete"></span>',
	    		'additional_attribute' 	=> 'title="Delete this schedule"',
	    		'caption' 				=> 'Delete Schedule'
    		); 

    		$btn_import_employee_config = array(
	    		'module'				=> 'hr',
	    		'parent_index'			=> 'schedule',
	    		'child_index'			=> '',
	    		'href' 					=> 'javascript:void(0);',
	    		'onclick' 				=> 'javascript:importEmployeesInSchedule("'.$public_id.'");',
	    		'id' 					=> '',
	    		'class' 				=> 'tooltip add_button',
	    		'icon' 					=> '<strong><i class="icon-arrow-left"></i></strong>',
	    		'additional_attribute' 	=> 'title="Add employees to this schedule"',
	    		'caption' 				=> 'Import Employees'
    		); 

    		$current_year    = date("Y");
    		$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
    		$group_id          = G_Company_Structure::PARENT_ID;
		    for($start_month = 1; $start_month <= 12; $start_month++){
		    	$c = new G_Calendar_Restday($current_year, $start_month);
	       		$c->setGroupId($group_id);
	        	$c->setPermission($permission_action);
		    	$calendar .= "<li>" . $c->groupDisplayRd() . "</li>";
		    }

			$this->var['calendar'] = $calendar;
	    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
	    	$this->var['btn_edit_schedule'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_edit_schedule_config);
	    	$this->var['btn_delete_schedule'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_delete_schedule_config);
	    	$this->var['btn_import_employee'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_import_employee_config);


	    	//General Reports / Shr Audit Trail
	    	$effectivity_date = $s->getEffectivityDate();

	    	if($s->getName() == 'default'){
	    		$nw_effectiveDate = '';
	    		$nw_endDate = '';
	    	}
	    	else{
	    		$nw_effectiveDate = $s->getEffectivityDate();
	    		$nw_endDate = $s->getEndDate();
	    	}
	    	$end_date = $s->getEndDate();
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_VIEW, ' Employee Schedule of', $title, $nw_effectiveDate, $nw_endDate, 1, '', '');

			if ($s->isDefault()) {
				$this->view->render('schedule/show_schedule_default.php',$this->var);
			} else {
				$this->view->render('schedule/show_schedule.php',$this->var);
			}
		} else {
			display_error();	
		}
	}
	
	function _create_schedule() { // not used
		$error = 0;
		if (empty($_POST['schedule_name'])) { $error++; }
		if (empty($_POST['working_days'])) { $error++; }
		
		if ($error > 0) {
			exit('Failed to create schedule');	
		}
		
		$schedule_name = $_POST['schedule_name'];
		$working_days = implode(',', $_POST['working_days']);
		$time_in = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['time_in']['hh']) .':'. Tools::addLeadingZero($_POST['time_in']['mm']) .' '. $_POST['time_in']['am']));
		$time_out = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['time_out']['hh']) .':'. Tools::addLeadingZero($_POST['time_out']['mm']) .' '. $_POST['time_out']['am']));
				
		$s = new G_Schedule;
		$s->setName($schedule_name);
		$s->setWorkingDays($working_days);
		$s->setTimeIn($time_in);
		$s->setTimeOut($time_out);
		$id = $s->save();
		
		if ($id) {
			$return['schedule_id'] = $id;
			$return['schedule_name'] = $schedule_name;
			$return['working_days'] = $working_days;
			$return['time_in'] = Tools::timeFormat($time_in);
			$return['time_out'] = Tools::timeFormat($time_out);
			$return['message'] = 'Schedule has been created. <a href="javascript:void(0)" onclick="assignSchedule('. $id .')">Add groups or employees</a>';
			$return['is_created'] = true;

			
		} else {
			$return['message'] = "There's an error occured. Schedule has not been created. Please contact the developer.";
			$return['is_created'] = false;	

		}
		echo json_encode($return);		
	}
	
	function _edit_schedule() {
		$error = 0;
		$schedule_id = (int) $_POST['schedule_id'];
		if (empty($_POST['name'])) { $error++; }
		if (empty($_POST['working_day'])) { $error++; }
		if (empty($schedule_id)) { $error++; }
		
		if ($error > 0) {
			exit('Failed to edit schedule');	
		}
		
		$schedule_name = $_POST['name'];
		$working_days = implode(',', $_POST['working_day']);
		$time_in = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['time_in']['hh']) .':'. Tools::addLeadingZero($_POST['time_in']['mm']) .' '. $_POST['time_in']['am']));
		$time_out = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['time_out']['hh']) .':'. Tools::addLeadingZero($_POST['time_out']['mm']) .' '. $_POST['time_out']['am']));
				
		$s = G_Schedule_Finder::findById($schedule_id);
		$s->setName($schedule_name);
		$s->setWorkingDays($working_days);
		$s->setTimeIn($time_in);
		$s->setTimeOut($time_out);
		$is_saved = $s->save();
		
		if ($is_saved) {
			$return['schedule_name'] = $schedule_name;
			$return['working_days'] = $working_days;
			$return['time_in'] = Tools::timeFormat($time_in);
			$return['time_out'] = Tools::timeFormat($time_out);
			$return['message'] = 'Schedule has been saved';
			$return['is_saved'] = true;
		} else {
			$return['message'] = "There's an error occured. Schedule has not been saved. Please contact the developer.";
			$return['is_saved'] = false;
		}
		echo json_encode($return);
	}
	
	function _add_weekly_schedule() {
		$this->_edit_weekly_schedule();
	}
	
	function _edit_weekly_schedule() {
		$name             = $_POST['name'];
		$public_id        = $_POST['id'];
		$effectivity_date = $_POST['start_date'];
		$end_date         = $_POST['end_date'];
		$is_changed       = $_POST['is_changed'];
//		$schedule['time_in'] = array(
//			'mon' => '8:00 am', 
//			'tue' => '8:00 am', 
//			'wed' => '8:00 am', 
//			'thu' => '8:00 am', 
//			'fri' => '8:00 am', 
//			'sat' => '8:00 am', 
//			'sun' => '8:00 am'
//		);		
//		$schedule['time_out'] = array(
//			'mon' => '5:00 pm', 
//			'tue' => '5:00 pm', 
//			'wed' => '5:00 pm', 
//			'thu' => '5:00 pm', 
//			'fri' => '5:00 pm', 
//			'sat' => '6:00 pm', 
//			'sun' => '6:00 pm'
//		);
		//print_r($schedule);
		$schedule = $_POST;
		$merged_days = array();
		foreach ($schedule['time_in'] as $day => $schedule_time) {		
			if (strtotime($schedule_time)) {
				$schedule_time_in = $schedule_time;
				$schedule_time_out = $schedule['time_out'][$day];
				$merged_days[$schedule_time_in .'-'. $schedule_time_out][] = $day;
			}


		}
		if (count($merged_days) > 0) {

			$group = G_Schedule_Group_Finder::findByPublicId($public_id);
			
			if($group){
				$cur_name = $group->getName();
			}
			
			if($public_id != ''){


				$gwp = G_Schedule_Finder::getWorkingDaysByGroupId($group->getId(), $cur_name);
				$gwp_num = G_Schedule_Finder::getNumWorkingDaysByGroupId($group->getId(), $cur_name);
				
				if($gwp){
					//working days from database
					$array1 = explode(",",$gwp->getWorkingDays());
					$db_first_workingdays = $array1[0];
					$db_last_workingdays  = $array1[count($array1)-1];
					$from_db_working_days = $db_first_workingdays.' - '.$db_last_workingdays.'<br>';
				}

				$db_get_start_date = $group->getEffectivityDate();
				$db_get_end_date = $group->getEndDate();

				if($name != 'default' && $group->getEffectivityDate() != $effectivity_date){
					$new_from = $group->getEffectivityDate();
					$new_to = $effectivity_date;
					//echo 'one';
					//General Reports / Shr Audit Trail
					$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Schedule of ', $group->getName(), $new_from, $new_to, 1, '', '');

				}
				elseif($name != 'default' &&  $group->getEndDate() != $end_date){
					$new_from = $group->getEndDate();
					$new_to = $end_date;

					//General Reports / Shr Audit Trail
					$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Schedule of ', $group->getName(), $new_from, $new_to, 1, '', '');
				}
				elseif($name != 'default' &&  $group->getName() != $name){
					$new_from = $group->getName();
					$new_to = $name;

					//General Reports / Shr Audit Trail
					$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Schedule of ', $group->getName(), $new_from, $new_to, 1, '', '');
				}


				

			}

		
			if (!$group) {
				$group = new G_Schedule_Group;	
			}
			$group->setEffectivityDate($effectivity_date);
			$group->setEndDate($end_date);
			$group->setName($name);		
			$group->setGracePeriod($_POST['grace_period']);	
			if ($group->getId() > 0) {
				$group_id = $group->getId();
				$group->save();				
			} else {
				$group_id = $group->save();
			}
			$group = G_Schedule_Group_Finder::findById($group_id);
			$group->setEndDate($end_date);
			G_Schedule_Group_Helper::updateEmployeeStartAndEndDate($group, $effectivity_date);
						
			$s = G_Schedule_Finder::findAllByScheduleGroup($group);
			foreach ($s as $ss) {
				$old_time[] = $ss->getTimeIn() .'-'. $ss->getTimeOut();	
			}

			$i = 1;
			foreach ($merged_days as $time => $days) {

				list($time_in, $time_out) = explode('-', $time);
				$day = implode(',', $days);
				$time_in = date('H:i:s', strtotime($time_in));
				$time_out = date('H:i:s', strtotime($time_out));			
				$updated_time[] = $time_in .'-'. $time_out;

				$d = G_Schedule_Finder::findByScheduleGroupAndTimeInAndOut($group, $time_in, $time_out);

				if (!$d) {
					$d = new G_Schedule;		
				}

				$d->setName($name);
				$d->setWorkingDays($day);
				$d->setTimeIn($time_in);
				$d->setGracePeriod($_POST['grace_period']);
				$d->setTimeOut($time_out);

				if ($d->getId() > 0) {
					$schedule_id = $d->getId();
					$d->save();	
					
					//working days from database

					//working days from form
					$array = explode(",",$day);
					$form_first_working_days = $array[0];
					$form_last_working_days  = $array[count($array)-1];
					$to_form_working_days = $form_first_working_days.' - '.$form_last_working_days;


					if($name != 'default' && $db_get_start_date == $effectivity_date && $db_get_end_date == $end_date){

						$new_from = $from_db_working_days;
						$new_to = $from_db_working_days;

						//echo 'Single';
						//General Reports / Shr Audit Trail
						$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Schedule of ', $group->getName(), $new_from, $new_to, 1, '', '');
						
					}
					
					if($name == 'default'){
						$new_from = $from_db_working_days;
						$new_to = $to_form_working_days;
						//echo 'default';
						//General Reports / Shr Audit Trail
						$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Schedule of ', $group->getName(), $new_from, $new_to, 1, '', '');
					}
					//echo $gwp_num;
					if($name != 'default' && $gwp_num > 1){

						$counter = $i++;
						if($counter == 1 && $gwp_num == 1){
							$new_from = $from_db_working_days;
							$new_to = $to_form_working_days;
							//echo 'Multi';
							//General Reports / Shr Audit Trail
							$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Schedule of ', $group->getName(), $new_from, $new_to, 1, '', '');
						}

						if($counter == 2 && $gwp_num == 2){

							$new_from = $db_first_workingdays.'-'.$form_last_working_days;
							$new_to = $db_first_workingdays.'-'.$form_last_working_days;
							//echo 'Second';
							//General Reports / Shr Audit Trail
							$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Schedule of ', $group->getName(), $new_from, $new_to, 1, '', '');
						}

						if($counter == 3){

							$new_from = $db_first_workingdays.'-'.$form_last_working_days;
							$new_to = $db_first_workingdays.'-'.$form_last_working_days;
							//echo '3rd';
							//General Reports / Shr Audit Trail
							$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Schedule of ', $group->getName(), $new_from, $new_to, 1, '', '');
						}
						
					}
					else{

						$new_from = $from_db_working_days;
						$new_to = $from_db_working_days;
						//echo 'Else';
						//General Reports / Shr Audit Trail
						//$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Schedule of ', $group->getName(), $new_from, $new_to, 1, '', '');

					}

					//echo 'From: '.$new_from.' = To:'.$new_to;

					//General Reports / Shr Audit Trail
					//$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Schedule of ', $group->getName(), $new_from, $new_to, 1, '', '');
					
					
				} else {

					$schedule_id = $d->save();
					
					//General Reports / Shr Audit Trail
					$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, ' Schedule of ', $group->getName(), $effectivity_date, $end_date, 1, '', '');					

				}
				$sched = G_Schedule_Finder::findById($schedule_id);
				$sched->saveToScheduleGroup($group);
				
			}
			$to_be_deleted = array_diff($old_time, $updated_time);
			foreach ($to_be_deleted as $to_delete) {
				list($time_in, $time_out) = explode('-', $to_delete);
				$d = G_Schedule_Finder::findByScheduleGroupAndTimeInAndOut($group, $time_in, $time_out);
				if ($d) {
					$d->deleteSchedule();	
				}
			}
			$all_schedules = G_Schedule_Finder::findAllByScheduleGroup($group);
			foreach ($all_schedules as $all_schedule) {
				//$schedule_string .=  '<div>'. Tools::timeFormat($all_schedule->getTimeIn()) .' - '. Tools::timeFormat($all_schedule->getTimeOut()) .' - '. $all_schedule->getWorkingDays().' </div>';
                $schedule_string .= '<li><div class="item-detail-styled">
                  <i class="icon-time icon-fade vertical-middle"></i>
                  <strong>'. $all_schedule->getWorkingDays() .'</strong>
                  ('. Tools::timeFormat($all_schedule->getTimeIn()) .' - '. Tools::timeFormat($all_schedule->getTimeOut()) .')
                </div></li>';
			}
			
			
			//  UPDATE ATTENDANCE
			if ($is_changed == 'yes') {				
				$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
				$start_date = $effectivity_date;//$c->getStartDate();
				if($c){
				    $end_date = $c->getEndDate();
					$employees = G_Employee_Finder::findByScheduleGroup($group);
                    $dates = Tools::getBetweenDates($start_date, $end_date);
					foreach ($employees as $e) {
						if ($e) {
                            foreach ($dates as $date) {
                                $attendances[] = G_Attendance_Helper::generateAttendance($e, $date);
                            }
							/* G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date); */
						}
					}
                    //echo '<pre>';
                   // print_r($attendances);
					
                    G_Attendance_Helper::updateAttendanceByMultipleAttendance($attendances);

				}
			}

			$return['is_saved'] = true;
			$return['message'] = 'Schedule has been saved';
			$return['title_string'] = $group->getName();
			$return['schedule_string'] = $schedule_string;
			$return['schedule_group_id'] = $group_id;

		} else {
			$return['message'] = 'You have to add at least 1 schedule';
			$return['error_type'] = 'no_entry';
			$return['is_saved'] = false;

			 //General Reports / Shr Audit Trail
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, 'Schedule of ', $group->getName(), $effectivity_date, $end_date, 0, '', '');
		}						
		echo json_encode($return);	
	}

	function _edit_group_weekly_schedule() {
		$data  = $_POST;
		$eid   = $data['geid'];
		$token = $data['token'];

		if(Utilities::isFormTokenValid($token) && !empty($eid)){
			$id    = Utilities::decrypt($eid);
			$group = G_Company_Structure_Finder::findById($id);
			$schedule = $data['schedule'];
			if( $group ){
				$return = $group->addSchedule($schedule);
			}
		}else{			
			$return['message']    = 'Invalid form entries';			
			$return['is_success'] = false;
		}	

		$return['eid']   = $eid;
		$return['token'] = Utilities::createFormToken();

		echo json_encode($return);	
	}
	
	function _assign_group_schedule_to_employee() {
		$employee_id = $_POST['employee_id'];
		$group_id = (int) $_POST['schedule_group_id'];
		$e = G_Employee_Finder::findById($employee_id);
		$is_assigned = false;
		if ($e) {
			$group = G_Schedule_Group_Finder::findById($group_id);
			if ($group) {
				//$new_id = $group->assignToEmployee($e, Tools::getGmtDate('Y-m-d'));
                $new_id = $group->assignToEmployee($e, $group->getEffectivityDate());
				if ($new_id > 0) {
					$is_assigned = true;	
				}
			}					
		}
		if (!$is_assigned) {
			$return['message'] = 'Schedule has not been assigned';	
		} else {
			$return['message'] = 'Schedule has been assigned';
		}
		$return['is_assigned'] = $is_assigned;
		echo json_encode($return);
	}
	
	function _assign_schedule() {
		$error = 0;
		if (strlen($_POST['groups_autocomplete']) > 0) {
			$groups = explode(',', $_POST['groups_autocomplete']);
		}
		
		if( $_POST['apply_to_all'] ){
			$fields = array("id");
			$employee_ids = G_Employee_Helper::sqlAllActiveEmployee($fields);
			foreach( $employee_ids as $employee ){
				$employees[] = $employee['id'];
			}
		}else{
			if (strlen($_POST['employees_autocomplete']) > 0) {
				$employees = explode(',', $_POST['employees_autocomplete']);
			}
		}
		
		if (empty($groups) && empty($employees)) {
			$error++;
		}		
		if ($error > 0) {
			$return['message'] = 'Error occured.';
			$return['saved'] = false;
			echo json_encode($return);
		} else {
			$schedule_id = $_POST['schedule_id'];
			if (!empty($groups)) {
				foreach ($groups as $group_id) {
					$g = G_Group_Finder::findById($group_id);
					$s = G_Schedule_Group_Finder::findByPublicId($schedule_id);					
					if (!G_Schedule_Helper::isGroupAlreadyAssigned($g, $s)) {
					    $effectivity_date = $s->getEffectivityDate();
						$s->assignToGroup($g, $effectivity_date, $s->getEndDate());

        			    $c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
        			    if ($c) {
        			        $start_date = $effectivity_date;
        				    $end_date = $c->getEndDate();

                            $es = G_Employee_Finder::findAllByGroup($g);
                            G_Attendance_Helper::updateAttendanceByEmployeesAndPeriod($es, $start_date, $end_date);
                        }
					}
				}
			}
			if (!empty($employees)) {
    			$s = G_Schedule_Group_Finder::findByPublicId($schedule_id);
    			$effectivity_date = $s->getEffectivityDate();    			
    			$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
    			$start_date = $effectivity_date;//$c->getStartDate();
    			if($c) {
    				$end_date = $c->getEndDate();
                }

				foreach ($employees as $employee_id) {
					$e = G_Employee_Finder::findById($employee_id);

					if (!G_Schedule_Helper::isEmployeeAlreadyAssigned($e, $s)) {
						$s->assignToEmployee($e, $s->getEffectivityDate(), $s->getEndDate());
					}

					// UPDATE ATTENDANCE
                    if ($c) {
						G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
					}

					//General Reports / Shr Audit Trail
					$shr_emp = G_Employee_Helper::findByEmployeeId($employee_id);
					$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
					$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, ' Schedule of ', $emp_name, $start_date, $s->getEndDate(), 1, $shr_emp['position'], $shr_emp['department']);

				}
			}
			if ($s) {
				$return['public_id'] = $s->getPublicId();	
			}
			$return['saved'] = true;
			echo json_encode($return);
		}			
	}

	function _remove_all_schedule_member_employees() {
		$schedule_group_public_id = (string) $_POST['schedule_group_public_id'];

		$is_removed = false;
		$s = G_Schedule_Group_Finder::findByPublicId($schedule_group_public_id);
		if ($s) {
			$effectivity_date = $s->getEffectivityDate();

            $es = G_Employee_Finder::findByScheduleGroup($s);
            $is_removed = $s->removeEmployees();

			if ($is_removed) {
				$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
				$start_date = $effectivity_date;//$c->getStartDate();
				if($c) {
					$end_date = $c->getEndDate();
					G_Attendance_Helper::updateAttendanceByEmployeesAndPeriod($es, $start_date, $end_date);
				}
			}
		}
		$return['is_removed'] = $is_removed;
		if ($is_removed) {
			$return['message'] = 'Employees have been removed';

			//General Reports / Shr Audit Trail
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_DELETE, ' ALL Schedule to ', $s->getName(), $effectivity_date, $s->getEndDate(), 1, '', '');

		} else {
			$return['message'] = 'An error occured. Employees have not been removed. Please contact the developer';
			//General Reports / Shr Audit Trail
			$shr_emp = G_Employee_Helper::findByEmployeeId($employee_group_id);
			$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_DELETE, ' ALL Schedule to ', $s->getName(), $effectivity_date, $s->getEndDate(), 0, '', '');
		}
		echo json_encode($return);
	}
	
	function _remove_schedule_member() {
		$employee_group_id = (int) $_POST['employee_group_id'];
		$schedule_id = $_POST['schedule_id'];
		$employee_or_group = (string) $_POST['employee_or_group'];

		$is_removed = false;
		$s = G_Schedule_Group_Finder::findByPublicId($schedule_id);
		if ($s) {
			$effectivity_date = $s->getEffectivityDate();
			$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);

			if ($employee_or_group == 'employee') {
				$e = Employee_Factory::get($employee_group_id);
				if ($e) {
					$is_removed = $s->removeEmployee($e);

    				if ($is_removed && $c) {
    				    $start_date = $effectivity_date;
    					$end_date = $c->getEndDate();
    					G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
    				}
				}

			} else if ($employee_or_group == 'group') {
				$g = G_Group_Finder::findById($employee_group_id);
				if ($g) {
					$is_removed = $s->removeGroup($g);

      			    if ($is_removed && $c) {
      			        $start_date = $effectivity_date;
      				    $end_date = $c->getEndDate();

                        $es = G_Employee_Finder::findAllByGroup($g);
                        G_Attendance_Helper::updateAttendanceByEmployeesAndPeriod($es, $start_date, $end_date);
                    }
				}
			}
		}			
		$return['is_removed'] = $is_removed;
		if ($is_removed) {
			$return['message'] = 'Member has been removed';

			//General Reports / Shr Audit Trail
			$shr_emp = G_Employee_Helper::findByEmployeeId($employee_group_id);
			$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_DELETE, ' Schedule to ', $emp_name, $start_date, $s->getEndDate(), 1, $shr_emp['position'], $shr_emp['department']);

		} else {
			$return['message'] = 'An error occured. Member has not been removed. Please contact the developer';

			//General Reports / Shr Audit Trail
			$shr_emp = G_Employee_Helper::findByEmployeeId($employee_group_id);
			$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_DELETE, ' Schedule to ', $emp_name, $start_date, $s->getEndDate(), 0, $shr_emp['position'], $shr_emp['department']);
		}
		echo json_encode($return);
	}
	
	function _delete_specific_schedule() {
		$schedule_id = (int) $_POST['schedule_id'];		
		$s = G_Schedule_Specific_Finder::findById($schedule_id);
		if ($s) {
			$start_date  = $s->getDateStart();
			$end_date    = $s->getDateEnd();
			$employee_id = $s->getEmployeeId();

			$e 	   = G_Employee_Finder::findById($employee_id);			
			$dates = Tools::getBetweenDates($start_date, $end_date);
			foreach ($dates as $date) {
                $r = G_Restday_Finder::findByEmployeeAndDate($e, $date);
                if( $r ){
                    $r->removeFromRestDay();
                }
            }

			if ($s->delete()) {				
				if ($e) {
					G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
				}
				$return['employee_id'] = Utilities::encrypt($employee_id);
				$return['is_deleted']  = true;
				$return['message']     = 'Schedule has been deleted';
			} else {
				$return['is_deleted'] = false;
				$return['message'] = 'Schedule has not been deleted. Please contact the administrator';
			}
		} else {
			$return['is_deleted'] = false;
			$return['message'] = 'Schedule was not found.';
		}
		echo json_encode($return);
	}

	function _remove_group_schedule() {
		$return['is_success'] = false;
		$return['message']    = 'Record not found';

		$schedule_eid = $_POST['group_schedule_eid'];
		$group_eid    = $_POST['group_eid'];			

		if( !empty($group_eid) && !empty($schedule_eid) ){
			$id 		  = Utilities::decrypt($group_eid);
			$schedule_id  = Utilities::decrypt($schedule_eid);

			$c = G_Company_Structure_Finder::findById($id);
			if( !empty($c) ){			
				$return = $c->removeSchedule($schedule_id);
			}	

			$return['eid'] = $group_eid;
		}

		echo json_encode($return);
	}
	
	function _delete_schedule() {
		$schedule_id = $_POST['schedule_id'];		
		$is_deleted = false;
		$s = G_Schedule_Group_Finder::findByPublicId($schedule_id);

		if($s){
			$schd_name = G_Schedule_Finder::findById($schedule_id);
			$sched_name = $schd_name->schedule_name;
		}

		if ($s) {
			$date = $s->getEffectivityDate();
			if ($s->isDefault()) {
				$return['message'] = "This is the default schedule. You can't delete the default schedule.";
				$is_deleted = false;

				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_DELETE, 'Schedule', $s->getName(), $s->getEffectivityDate(), $s->getEndDate(), 0, '', '');

			} else {
				if ($s->countMembers() == 0) {
					$employees = G_Employee_Finder::findByScheduleGroup($s);
					$s->removeEmployees();
					$s->removeGroups();					
					$is_deleted = $s->delete(); // delete group
					$s->deleteSchedule(); // delete schedules under this group

					//  UPDATE ATTENDANCE
					/*$c = G_Cutoff_Period_Finder::findByDate($date);
                    if ($c) {
    					$start_date = $date;//$c->getStartDate();
    					$end_date = $c->getEndDate();
                    } */

					/*foreach ($employees as $e) {
						if ($e) {
							G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
						}
					}*/

				} else {
					$return['message'] = 'You have to remove first all groups and employees before you can delete this schedule';
					$is_deleted = false;
					//General Reports / Shr Audit Trail
					$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_DELETE, 'Schedule of ', $s->getName(), $s->getEffectivityDate(), $s->getEndDate(), 0, '', '');
				}
			}
		} else {
			$return['message'] = 'An error occured. Schedule has not been deleted. Please contact the developer';	

				//General Reports / Shr Audit Trail
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_DELETE, 'Schedule of ', $s->getName(), $s->getEffectivityDate(), $s->getEndDate(), 0, '', '');
		}
		$return['is_deleted'] = $is_deleted;
		if ($is_deleted) {
			$return['message'] = 'Schedule has been deleted';

			//General Reports / Shr Audit Trail
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_DELETE, 'Schedule of ', $s->getName(), $s->getEffectivityDate(), $s->getEndDate(), 1, '', '');
		}
		echo json_encode($return);
	}
	
	function _count_members() {
		$schedule_id = $_POST['schedule_id'];
		$s = G_Schedule_Group_Finder::findByPublicId($schedule_id);
		
		//$s = G_Schedule_Group_Finder::findById($schedule_id);
		if ($s) {
			$count = $s->countMembers();	
		}
		$return['count'] = $count;
		echo json_encode($return);	
	}
	
	function _import_employees_in_schedule() {
		ob_start();
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);

		$sg = G_Schedule_Group_Finder::findByPublicId($_POST['public_id']);
		$is_imported = false;
		if ($sg) {
			$effectivity_date = $sg->getEffectivityDate();
			$schedule_group_id = $sg->getId();
			$file = $_FILES['import_employees']['tmp_name'];
			//$file = BASE_PATH . 'files/sample_import_files/import_schedule_by_employees.xlsx';
			//$g = new G_Schedule_Import_Employees($file);
			//$g->setEffectivityDate($effectivity_date);

            $g = new G_Schedule_Import_Employees($file);
			$is_true = $g->import($sg);
			if ($is_true) {
				$is_imported = true;
			}
		}
		
		if ($is_imported) {
			$return['message'] = 'Employees have been imported';	
		} else {
			$return['message'] = 'There was an error while importing. Please contact the administrator';	
		}
		$return['is_imported'] = $is_imported;
		ob_clean();
		ob_end_flush();
		echo json_encode($return);
	}
	
	function _import_schedule() {
		ob_start();	
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		$date_start = $_POST['date_start'];
		$end_date   = $_POST['end_date'];
		
		if (!strtotime($date_start)) {
			$date_start = date('Y-m-d');	
		}		
		$file = $_FILES['import_schedule_file']['tmp_name'];

		$g = new G_Schedule_Import_Weekly($file);
		$g->setEffectivityDate($date_start);
		$g->setEndDate($end_date);
			
		if ($g->import()) {
			$es = $g->getEmployees();
			$c = G_Cutoff_Period_Finder::findByDate($date_start);
			if( $c ){
				$start_date = $date_start;//$c->getStartDate();
				$end_date = $c->getEndDate();
				
				foreach ($es as $employee_code) {
					$e = G_Employee_Finder::findByEmployeeCode($employee_code);
					if ($e) {
						G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
					}				
				}
			}
			$return['message'] = 'Schedule has been imported';
			$return['is_imported'] = true;
		} else {
			$return['message'] = 'There was an error while importing. Please contact the administrator';
			$return['is_imported'] = false;
		}

		ob_clean();
		ob_end_flush();
		echo json_encode($return);
	}
	
	function _import_schedule_specific() {
		ob_start();
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$file = $_FILES['import_schedule_specific_file']['tmp_name'];
		//$file = BASE_PATH . 'files/sample_import_files/import_schedule_weekly.xlsx';
		
		//$g = new G_Schedule_Import_Dates($file);
        $g = new G_Schedule_Specific_Import($file);
		if ($g->import()) {
			$return['message'] = 'Schedules has been imported';
			$return['is_imported'] = true;
		} else {
			$return['message'] = 'There was an error while importing. Please contact the administrator';
			$return['is_imported'] = false;
		}

		ob_clean();
		ob_end_flush();
		echo json_encode($return);
	}
	
	function _add_specific_schedule() {
		$employee_id = (int) $_POST['employee_id'];
		$start_date = $_POST['schedule_date'];
		$end_date = $_POST['schedule_end_date'];
		$time_in = $_POST['schedule_time_in'];
		$time_out = $_POST['schedule_time_out'];
        $is_restday = $_POST['is_restday'];
		
		if (Tools::isValidDate($start_date) && Tools::isValidTime($time_in) && Tools::isValidTime($time_out)) {
			$start_date = date('Y-m-d', strtotime($start_date));
			$time_in = date('H:i:s', strtotime($time_in));
			$time_out = date('H:i:s', strtotime($time_out));
			
			$e = G_Employee_Finder::findById($employee_id);
			if ($e) {
				if (Tools::isValidDate($end_date)) {
					$end_date = date('Y-m-d', strtotime($end_date));
				} else {
					$end_date = $start_date;	
				}
				
				if (strtotime($end_date) >= strtotime($start_date)) {
					$s = G_Schedule_Specific_Finder::findByEmployeeAndStartAndEndDate($e, $start_date, $end_date);
					if (!$s) {
						$s = new G_Schedule_Specific;
					}				
					$s->setDateStart($start_date);
					$s->setDateEnd($end_date);
					$s->setTimeIn($time_in);
					$s->setTimeOut($time_out);
					$s->setEmployeeId($e->getId());
					$is_saved = $s->save();

					$dates = Tools::getBetweenDates($start_date, $end_date);

                    foreach ($dates as $date) {
                        $r = G_Restday_Finder::findByEmployeeAndDate($e, $date);

                        if( $r ){

                        	if( $is_restday <> 'yes' ){
                        		$r->removeFromRestDay();
                        	}
                        }else{
                        	if( $is_restday == 'yes' ){
                        		$r = new G_Restday;
	                        	$r->setDate($date);
	                            $r->setTimeIn($time_in);
	                            $r->setTimeOut($time_out);
	                            $r->setEmployeeId($e->getId());
	                            $r->save();
                        	}                 

                        }
                    }

					if ($is_saved) {

						G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
						$return['is_saved'] = true;
						$return['message'] = 'Schedule has been saved';												
					} else {
						$return['is_saved'] = false;
						$return['message'] = 'There was a problem saving the schedule. Please contact the administrator';	
					}					
				} else {
					$return['is_saved'] = false;
					$return['message'] = 'Start Date must not greater than End Date';
				}				
			} else {
				$return['is_saved'] = false;
				$return['message'] = 'Employee was not found';
			}
		} else {
			$return['is_saved'] = false;
			$return['message'] = 'Schedule has not been saved. Invalid time or date format.';
		}
		
		echo json_encode($return);	
	}

	function ajax_show_schedule_list() {
		$s = G_Schedule_Finder::findAll();
		$this->var['schedules'] = G_Schedule_Helper::mergeByName($s);
		$this->view->noTemplate();
		$this->view->render('schedule/ajax_schedule_list.php',$this->var);
	}
	
	function ajax_show_weekly_schedule_list() {
	    $month = $_SESSION['show_schedule_month'];
        $year = $_SESSION['show_schedule_year'];
        $this->var['schedule_groups'] = G_Schedule_Group_Finder::findAllByMonthAndYearWithDefault($month, $year);
		//$this->var['schedule_groups'] = G_Schedule_Group_Finder::findAll();
    	
    	$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
   
   		$this->view->noTemplate();
		$this->view->render('schedule/ajax_weekly_schedule_list.php',$this->var);
	}	
	
	function ajax_show_schedule_members_list() {
		$id = $_GET['schedule_id'];
		$g = G_Schedule_Group_Finder::findByPublicId($id);
		$this->var['schedule_id'] = $id;//$g->getId();
		$this->var['employees'] = G_Employee_Finder::findByScheduleGroup($g);
		$this->var['groups'] = G_Group_Finder::findByScheduleGroup($g);
		
		$btn_add_department_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:assignScheduleGroups("'.$id.'");',
    		'id' 					=> '',
    		'class' 				=> 'btn btn-mini',
    		'icon' 					=> '<i class="icon-plus"><span class="tooltip" title="Add Groups or Department"></span></i>',
    		'additional_attribute' 	=> 'title="Add Groups or Department"',
    		'caption' 				=> 'Add'
		); 

		$btn_add_employees_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:assignScheduleEmployees("'.$id.'");',
    		'id' 					=> '',
    		'class' 				=> 'btn btn-mini',
    		'icon' 					=> '<i class="icon-plus"><span class="tooltip" title="Add Employees"></span></i>',
    		'additional_attribute' 	=> 'title="Add Employees"',
    		'caption' 				=> 'Add'
		); 

		$btn_remove_all_employees_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:removeAllScheduleMemberEmployees("'.$id.'");',
    		'id' 					=> '',
    		'class' 				=> 'relative delete_link red',
    		'icon' 					=> '<span class="delete"></span>',
    		'additional_attribute' 	=> 'title="Remove all employees"',
    		'caption' 				=> 'Remove all employees'
		); 
	
    	$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
    	$this->var['btn_add_department'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_department_config);
    	$this->var['btn_add_employees'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_employees_config);
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('schedule/ajax_schedule_members_list.php',$this->var);
	}
	
	function ajax_assign_schedule_groups() {
		$this->var['schedule_id'] = $_GET['schedule_id'];
		$this->view->noTemplate();
		$this->view->render('schedule/forms/ajax_assign_schedule_groups_form.php',$this->var);
	}
	
	function ajax_assign_schedule_employees() {
		$this->var['schedule_id'] = $_GET['schedule_id'];
		$this->view->noTemplate();
		$this->view->render('schedule/forms/ajax_assign_schedule_employees_form.php',$this->var);
	}
	
	function ajax_assign_schedule_groups_employees() {
		$this->var['schedule_id'] = (int) $_GET['schedule_id'];
		$this->view->noTemplate();
		$this->view->render('schedule/forms/ajax_assign_schedule_groups_employees_form.php',$this->var);
	}
	
	function ajax_edit_schedule_form() {
		$this->var['action'] = url('schedule/_edit_schedule');
		$this->var['schedule_id'] = $id = (int) $_GET['schedule_id'];
		$this->var['schedule'] = $s = G_Schedule_Finder::findById($id);		
		$this->view->noTemplate();
		$this->view->render('schedule/forms/ajax_edit_schedule_form.php',$this->var);
	}
	
	function ajax_edit_weekly_schedule_form() {
		$this->var['action']    = url('schedule/_edit_weekly_schedule');
		$this->var['public_id'] = $public_id = $_GET['public_id'];
		$group = G_Schedule_Group_Finder::findByPublicId($public_id);
		$this->var['group_name']   = $group->getName();
		$this->var['grace_period'] = $group->getGracePeriod();
		$this->var['is_default']   = $group->isDefault();

		$effect_date = $group->getEffectivityDate();
		$end_date    = $group->getEndDate();

		if (!strtotime($effect_date)) {
			$effect_date = date('Y-m-d');
		}

		$this->var['end_date']          = $end_date;
		$this->var['effectivity_date']  = $effect_date;
		$this->var['schedules'] 	    = G_Schedule_Finder::findAllByScheduleGroup($group);
		$this->view->noTemplate();
		$this->view->render('schedule/forms/ajax_edit_weekly_schedule_form.php',$this->var);
	}

	function ajax_edit_specific_schedule_form() {
		$this->var['action']    = url('schedule/_edit_specific_schedule');
		$this->var['schedule_id'] = $schedule_id = $_GET['schedule_id'];
        $s = G_Schedule_Specific_Finder::findById($schedule_id);               
        if( $s ){
        	$is_restday = G_Restday_Helper::countRestDayByEmployeeIdAndDate($s->getEmployeeId(),$s->getDateStart());
        	$this->var['is_restday'] = $is_restday;
        	$this->var['start_date'] = $start_date = $s->getDateStart();
	        $this->var['end_date']   = $end_date = $s->getDateEnd();        
	        $this->var['time_in']  = Tools::timeFormat($s->getTimeIn());
	        $this->var['time_out'] = Tools::timeFormat($s->getTimeOut());
	        $this->view->render('schedule/forms/ajax_edit_specific_schedule_form.php',$this->var);
        }else{
        	echo "Schedule not found!";
        }     
	}

    function _edit_specific_schedule() {

        $start_date = $_POST['schedule_date'];
        $end_date   = $_POST['schedule_end_date'];

        $time_in    = date('H:i:s', strtotime($_POST['schedule_time_in']));
        $time_out   = date('H:i:s', strtotime($_POST['schedule_time_out']));

        if ($end_date == '') {
            $end_date = $start_date;
        }

        $schedule_id = (int) $_POST['schedule_id'];
        $s = G_Schedule_Specific_Finder::findById($schedule_id);        
        if($s){
        	$e = G_Employee_Finder::findById($s->getEmployeeId());
        	$old_start_date = $s->getDateStart();
	        $old_end_date = $s->getDateEnd();

	        $s->setDateStart($start_date);
	        $s->setDateEnd($end_date);
	        $s->setTimeIn($time_in);
	        $s->setTimeOut($time_out);
	        $is_saved = $s->save();

	        $is_restday = $_POST['is_restday'];
	        $dates = Tools::getBetweenDates($start_date, $end_date);
	        foreach ($dates as $date) {
	            $r = G_Restday_Finder::findByEmployeeAndDate($e, $date);
	            if( $r ){
	                if( $is_restday <> 'yes' ){
	                    $r->removeFromRestDay();
	                }
	            }else{
	                if( $is_restday == 'yes' ){
                        $r = new G_Restday;
                        $r->setDate($date);
	                    $r->setTimeIn($time_in);
	                    $r->setTimeOut($time_out);
	                    $r->setEmployeeId($e->getId());
	                    $r->save();
	                }                               
	            }
	        }
        }
      
		if ($e) {
			G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
            G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $old_start_date, $old_end_date);
		}

		if ($is_saved) {
		    $return['message'] = "Schedule has been saved";
			$return['is_saved'] = true;
		} else {
			$return['message'] = "There's an error occured. Schedule has not been saved. Please contact the developer.";
			$return['is_saved'] = false;
		}
		echo json_encode($return);
    }
	
	function ajax_add_weekly_schedule_form() {
		$this->var['action'] = url('schedule/_add_weekly_schedule');
		$this->view->noTemplate();
		$this->view->render('schedule/forms/ajax_add_weekly_schedule_form.php',$this->var);
	}

	function ajax_group_add_weekly_schedule_form() {
		$eid = $_GET['eid'];
		if( !empty($eid) ){
			$fields = array("id","title");
			$id     = Utilities::decrypt($eid);
			$g      = new G_Company_Structure();
			$g->setId($id);
			$company_data = $g->getDepartmentDetailsById($fields);
			$company_data = Tools::encryptArrayIndexValue("id",$company_data);
			$this->var['token']        = Utilities::createFormToken();
			$this->var['company_data'] = $company_data;
			$this->var['action'] 	   = url('schedule/_edit_group_weekly_schedule');
			$this->view->noTemplate();
			$this->view->render('schedule/forms/ajax_group_add_weekly_schedule_form.php',$this->var);
		}else{
			echo "Department / Section not found";
		}
		
	}
	
	function ajax_import_employees_in_schedule() {
		$this->var['action'] = url('schedule/_import_employees_in_schedule');
		$this->var['public_id'] = $_GET['public_id'];
		$this->view->render('schedule/ajax_import_employees_in_schedule.php', $this->var);	
	}
	
	function ajax_import_schedule() {
		$this->var['action'] = url('schedule/_import_schedule');
		$this->view->render('schedule/forms/ajax_import_schedule.php', $this->var);	
	}
	
	function ajax_import_schedule_specific() {
		$this->var['action'] = url('schedule/_import_schedule_specific');
		$this->view->render('schedule/forms/ajax_import_schedule_specific.php', $this->var);	
	}
	
	function ajax_add_specific_schedule() {
		$this->var['action'] = url('schedule/_add_specific_schedule');
		$this->var['employee_id'] = $_GET['employee_id'];
		$this->view->render('schedule/forms/ajax_add_specific_schedule_form.php', $this->var);
	}
	
	function ajax_add_restday_schedule() {
		$this->var['action'] = url('schedule/_add_restday_schedule');
		$this->var['employee_id'] = $_GET['employee_id'];
		$this->view->render('schedule/forms/ajax_add_restday_schedule_form.php', $this->var);
	}	
	
	function html_show_import_format() {
		$this->view->setTemplate('template_blank.php');
		$this->view->render('schedule/html/html_show_import_format.php', $this->var);	
	}
	
	function html_import_changed_schedule() {
		$this->view->setTemplate('template_blank.php');
		$this->view->render('schedule/html/html_import_changed_schedule.php', $this->var);	
	}	

	function html_import_restday_schedule() {
		$this->view->setTemplate('template_blank.php');
		$this->view->render('schedule/html/html_import_restday_schedule.php', $this->var);	
	}
	
	
}
?>