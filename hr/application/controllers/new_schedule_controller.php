<?php
class New_Schedule_Controller extends Controller
{
	function __construct()
	{
	    $this->login();
        $this->module = 'hr'; // used in global_controller->has_access_module()

		parent::__construct();
		Loader::appMainScript('schedule12.js');
		Loader::appMainScript('schedule_base12.js');
		Loader::appMainScript('restday_base.js');
		Loader::appMainScript('restday.js');	
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');				
		Loader::appMainUtilities();
		Loader::appStyle('style.css');
		Loader::appMainScript('leave.js');
		Loader::appMainScript('leave_base.js');

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
    	$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'new_schedule','');
		redirect("new_schedule/dashboard");
	}
	//new schedule
	function script(){
		Jquery::loadMainTextBoxList();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();

		Loader::appMainScript('restday_base.js');
		Loader::appMainScript('restday.js');
	}
	function script_month(){
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
	}
	//Schedule Main
	function schedule_main(){
		$this->script();
		
		if ($_GET['date'] == '') {
            $date = $this->var['date'] = date("n");
        } else {
            $date = $this->var['date'] = $_GET['date'];
        } 

        $_SESSION['date'] = $date;
		$date = $_SESSION['date'];
		$this->var['date_log'] = $date;
        $this->var['schedule_groups'] = G_Schedule_Group_Finder::findAllSchedule();
		//$this->var['schedule_groups'] = G_Schedule_Group_Finder::findAll();
    	$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		

		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllScheduleMain($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		$this->var['staggered_notif'] = 0;
		$this->var['shift_notif'] = 0;
		$this->var['compress_notif'] = 0;
		$this->var['flexible_notif'] = 0;
		$this->var['all_notif'] = 0;

		foreach($v2_employee_attendance as $d_in){
			$schedule = G_Employee_Schedule_Type_Finder::findByEmployeeAndDateScheduleMain($d_in->getEmployeeId(), $_SESSION['date']);
			if($schedule->getScheduleType() == "Staggered"){
				$this->var['staggered_notif']++;
				$this->var['all_notif']++;
			}
			else if($schedule->getScheduleType() == "Compressed"){
				$this->var['compress_notif']++;
				$this->var['all_notif']++;
			}
			else if($schedule->getScheduleType() == "Shift"){
				$this->var['shift_notif']++;
				$this->var['all_notif']++;
			}
			else if($schedule->getScheduleType() == "Flexible"){
				$this->var['flexible_notif']++;
				$this->var['all_notif']++;
			}
			
		}
		
    	$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');	
	
		$this->var['page_title']   = 'Schedule Main';
		$this->view->setTemplate('template_new_schedule.php');
		$this->view->render('new_schedule/schedule_main.php',$this->var);	
	}
	function schedule_main_all_schedule() {
		//$this->var['employees'] = G_Employee_Finder::findAllEmployeeByScheduleGroup();

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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/schedule_main_all_schedule.php',$this->var);
	}
	function _load_schedule_main_all_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY TIME(actual_time_in) desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllScheduleMain($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);

		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeIn() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeIn());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeOut() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeOut());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';
				$json['table'] .= "	<a class=\"btn btn-small\" href=\"javascript:void(0);\" id=\"staggered\" onclick=\"editEmployeeSchedule('" . Utilities::encrypt($d_in->getId()) . "');\">
											<i class=\"icon-pencil icon-fade\"></i> Edit
										</a>
										<!--<a class=\"btn btn-small\" href=\"javascript:void(0);\" onclick=\"deleteDTRLogTimeInTimeOut('" . Utilities::encrypt($d_in->getId()) . "');\">
											<i class=\"icon-trash icon-fade\"></i> Delete
										</a>-->";
				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}

	function dashboard(){
		$this->script();
		
		if ($_GET['date'] == '') {
            $date = $this->var['date'] = date("n");
        } else {
            $date = $this->var['date'] = $_GET['date'];
        } 

        $_SESSION['date'] = $date;
		$date = $_SESSION['date'];
		$this->var['date_log'] = $date;
        $this->var['schedule_groups'] = G_Schedule_Group_Finder::findAllSchedule();
		//$this->var['schedule_groups'] = G_Schedule_Group_Finder::findAll();
    	
    	$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		
		$this->var['dashboard']   = 'class="selected"';					
	
		$this->var['page_title']   = 'Dashboard';
		$this->view->setTemplate('template_new_schedule.php');
		$this->view->render('new_schedule/dashboard.php',$this->var);	
	}
	//Paginator for Schedule
	function _load_paginator($count, $json){
		//Construct Paginator			
		if ($_POST['paginatorIndex']) {
			$paginator_index = $_POST['paginatorIndex'];
		} else {
			$paginator_index = 1;
		}

		if ($count > 0) {
			$json['paginator'] .= '<div id="yui-dt0-paginator0" class="yui-dt-paginator yui-pg-container">';
			$skip_counting = 0;

			//Add First BTN
			//Validate if first record for first record
			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first">First</span>';
			} else {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',1)">First</a></span>';
			}
			//

			//Add Prev Btn
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting - $_POST['limit'];
				}
				$skip_counting += $_POST['limit'];
			}

			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous">Previous</span>';
			} else {
				$prev_record = $paginator_index - 1;
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $prev_record . ');">Previous</a></span>';
			}
			//

			//Construct Paginator index
			$skip_counting = 0;
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting + $_POST['limit'];
					//$json['paginator'] .= '<li class="paginator_selected">' . $i . '</li>';		
				} else {
					//$json['paginator'] .= '<li><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $i . ');">' . $i . '</a></li>';		
				}

				$skip_counting += $_POST['limit'];
			}
			//

			//Add Next Btn
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next">Next</span>';
			} else {
				$next_record = $paginator_index + 1;
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $next_record . ');">Next</a></span>';
			}
			//


			//Add Last BTN
			//Validate if last record
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last">Last</span>';
			} else {
				$skip_counting -= $_POST['limit'];
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $count . ')">Last</a></span>';
			}
			//
			//
			$json['paginator'] .= '</div>';
		}
		//
	}
	//Shift Schedule
	function index_shift_schedule(){
		$this->script();
        $this->script_month();
		Loader::appMainScript('project_site31.js');
		Loader::appMainScript('project_site_base22.js');

		if ($_GET['date'] == '') {
			$date = $this->var['date'] = date("n");
		} else {
			$date = $this->var['date'] = $_GET['date'];
		} 

		$_SESSION['date'] = $date;
		$date = $_SESSION['date'];
		$this->var['date_log'] = $_GET['date'];

		$name = "Default Shift AM";
		$schedule_shift = G_Schedule_Template_Finder::findDefault($name);
		foreach($schedule_shift as $shift){
			$this->var['hours'] = $shift->getRequiredWorkingHours();
			if($shift->getScheduleIn() == "00:00:00"){
				$this->var['time_in'] = "";
			}else{
				$this->var['time_in'] = Tools::convert24To12Hour($shift->getScheduleIn());
			}
			if($shift->getScheduleOut() == "00:00:00"){
				$this->var['time_out'] = "";
			}else{
				$this->var['time_out'] = Tools::convert24To12Hour($shift->getScheduleOut());
			}
		}
		$name = "Default Shift PM";
		$schedule_shift = G_Schedule_Template_Finder::findDefault($name);
		foreach($schedule_shift as $shift){
			$this->var['hours'] = $shift->getRequiredWorkingHours();
			if($shift->getScheduleIn() == "00:00:00"){
				$this->var['time_in_pm'] = "";
			}else{
				$this->var['time_in_pm'] = Tools::convert24To12Hour($shift->getScheduleIn());
			}
			if($shift->getScheduleOut() == "00:00:00"){
				$this->var['time_out_pm'] = "";
			}else{
				$this->var['time_out_pm'] = Tools::convert24To12Hour($shift->getScheduleOut());
			}
		}

        $this->var['action'] = url('new_schedule/_add_shift_schedule');

		$btn_add_employees_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:assignShiftScheduleEmployeeList();',
    		'id' 					=> '',
    		'class' 				=> 'btn btn-mini',
    		'icon' 					=> '<i class="icon-plus"><span class="tooltip" title="Add Employees"></span></i>',
    		'additional_attribute' 	=> 'title="Add Employees"',
    		'caption' 				=> 'Add Employee'
		); 

		$btn_remove_all_employees_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:removeAllStaggeredScheduleMemberEmployees("'.$id.'");',
    		'id' 					=> '',
    		'class' 				=> 'relative delete_link red',
    		'icon' 					=> '<span class="delete"></span>',
    		'additional_attribute' 	=> 'title="Remove all employees"',
    		'caption' 				=> 'Remove all employees'
		); 

		$btn_create_schedule_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:createShiftSchedule();',
    		'id' 					=> 'add_employee_button_wrapper',
    		'class' 				=> 'add_button tooltip',
    		'icon' 					=> '',
    		'additional_attribute' 	=> 'title="Create New Schedule"',
    		'caption' 				=> '<strong>+</strong><b>Create Schedule</b>'
    		); 
    	
    	$this->var['btn_create_schedule'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_create_schedule_config);
	
    	$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
    	$this->var['btn_add_department'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_department_config);
    	$this->var['btn_add_employees'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_employees_config);
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
		
		$this->var['shift_schedule']   = 'class="selected"';					
					
		$this->var['page_title']   = 'Shift Schedule';
		$this->view->setTemplate('template_new_schedule.php');
		$this->view->render('new_schedule/shift_schedule/index_shift_schedule.php',$this->var);	
	}

	function load_shift_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');

		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
				

			
			';
		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/shift_schedule/shift_schedule.php', $this->var);
	}
	
	function _load_shift_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		$schedule_type = "Shift";

		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeIn() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeIn());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeOut() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeOut());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';
				$json['table'] .= "	<a class=\"btn btn-small\" href=\"javascript:void(0);\" id=\"staggered\" onclick=\"editEmployeeSchedule('" . Utilities::encrypt($d_in->getId()) . "');\">
											<i class=\"icon-pencil icon-fade\"></i> Edit
										</a>
										<!--<a class=\"btn btn-small\" href=\"javascript:void(0);\" onclick=\"deleteDTRLogTimeInTimeOut('" . Utilities::encrypt($d_in->getId()) . "');\">
											<i class=\"icon-trash icon-fade\"></i> Delete
										</a>-->";
				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';	
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}

	function load_no_shift_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');

		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
				

			
			';
		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/shift_schedule/no_shift_schedule.php', $this->var);
	}
	
	function _load_no_shift_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		if (count($employee_ids) > 0) {
			foreach ($employee_ids as $key => $field)
			$employee_ids[$key] = Utilities::decrypt($field);
		}
		$log_ids = array();
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);

		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		$g = G_Employee_Schedule_Type_Finder::findByDate($_SESSION['date']);
		$employee_with_schedule = array();
		foreach ($g as $emp_sched) {
		  $is_employee_id_exist = 0;
		  foreach ($employee_with_schedule as $e_array) {
			if ($e_array == $emp_sched->employee_id) {
			  $is_employee_id_exist = 1;
			} else {
			  continue;
			}
		  }
		  if ($is_employee_id_exist == 1) {
			continue;
		  } else {
			$employee_with_schedule[] = $emp_sched->employee_id;
		  }
		}
		$employee = G_Employee_Finder::findAll();
		foreach($employee as $emp){
			$is_employee_have_sched = 0;
			foreach($employee_with_schedule as $emp_with_sched){
				if($emp_with_sched == $emp->getId()){
					$is_employee_have_sched = 1;
				}
			}
			if($is_employee_have_sched == 1){

			}else{
				$remarks = "";
				//$remarks = $d_in->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];
	
				$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);
	
				if ($device_info) {
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
	
				$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($emp->getId());
				
				$json['table'] .= '<tr>';
				$json['table'] .= '<td>';
				$json['table'] .= '<input type="checkbox" name="dtrChkNoSchedule[]" onchange="javascript:checkUncheckNoSchedule();" value="' . $emp->getId() . '" style="margin-left: 5px;">';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'No Schedule';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'N/A';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'N/A';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
				$json['table'] .= $department->title;
				$json['table'] .= '</td>';
	
				/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($emp->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/
	
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= '<td>';
					$json['table'] .= "	<a class=\"btn btn-small\" href=\"javascript:void(0);\" 
											onclick=\"editEmployeeNoSchedule('" . Utilities::encrypt($emp->getId()) . "');\">
											<i class=\"icon-pencil icon-fade\"></i> Edit
										</a>";
					$json['table'] .= '</td>';
				}
	
				$json['table'] .= '</tr>';
			}
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}

	function load_leave_shift_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');
		
		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
			';

		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/shift_schedule/leave_shift_schedule.php', $this->var);
	}
	
	function _load_leave_shift_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		if (count($employee_ids) > 0) {
			foreach ($employee_ids as $key => $field)
			$employee_ids[$key] = Utilities::decrypt($field);
		}
		
		$log_ids = array();
		$schedule_type = "Leave";
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChkLeave[]" onchange="javascript:checkUncheckLeave();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->employee_code;
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';


			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';
				if ($d_in->is_approved == "Pending") {
					$json['table'] .= "Pending <a title=\"Approve\" id=\"edit\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveLeaveRequest('" . Utilities::encrypt($d_in->id) . "');\"></a>";
				} else {
					$json['table'] .= "Approved";
				}
				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}

	function load_rest_day_shift_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');

		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }


		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
				

			
			';

		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/shift_schedule/rest_day_shift_schedule.php', $this->var);
	}
	
	function _load_rest_day_shift_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		
		$schedule_type = "Rest Day";
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}
		$count = count($data);
			
		//Utilities::displayArray($v2_employee_leave);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChkRestDay[]" onchange="javascript:checkUncheckRestDay();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeIn() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeIn());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeOut() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeOut());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';

				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }

		//Construct Paginator			
		if ($_POST['paginatorIndex']) {
			$paginator_index = $_POST['paginatorIndex'];
		} else {
			$paginator_index = 1;
		}

		if ($count > 0) {
			$json['paginator'] .= '<div id="yui-dt0-paginator0" class="yui-dt-paginator yui-pg-container">';
			$skip_counting = 0;

			//Add First BTN
			//Validate if first record for first record
			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first">First</span>';
			} else {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',1)">First</a></span>';
			}
			//

			//Add Prev Btn
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting - $_POST['limit'];
				}
				$skip_counting += $_POST['limit'];
			}

			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous">Previous</span>';
			} else {
				$prev_record = $paginator_index - 1;
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $prev_record . ');">Previous</a></span>';
			}
			//

			//Construct Paginator index
			$skip_counting = 0;
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting + $_POST['limit'];
					//$json['paginator'] .= '<li class="paginator_selected">' . $i . '</li>';		
				} else {
					//$json['paginator'] .= '<li><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $i . ');">' . $i . '</a></li>';		
				}

				$skip_counting += $_POST['limit'];
			}
			//

			//Add Next Btn
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next">Next</span>';
			} else {
				$next_record = $paginator_index + 1;
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $next_record . ');">Next</a></span>';
			}
			//


			//Add Last BTN
			//Validate if last record
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last">Last</span>';
			} else {
				$skip_counting -= $_POST['limit'];
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $count . ')">Last</a></span>';
			}
			//
			//
			$json['paginator'] .= '</div>';
		}
		//
		echo json_encode($json);
	}

	function load_ob_shift_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');

		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
				

			
			';
		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/shift_schedule/ob_shift_schedule.php', $this->var);
	}
	
	function _load_ob_shift_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		$schedule_type = "OB";
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());
			$schedule = G_Employee_Schedule_Type_Finder::findAllByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChkOB[]" onchange="javascript:checkUncheckOB();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->employee_code;
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';
				if ($d_in->is_approved == "Pending") {
					$json['table'] .= "Pending <a title=\"Approve\" id=\"edit\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveLeaveRequest('" . Utilities::encrypt($d_in->id) . "');\"></a>";
				} else {
					$json['table'] .= "Approved";
				}
				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}

	function load_shift_schedule_list_dt(){
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$schedule_groups = G_Schedule_Template_Finder::findByScheduleType("Shift");

		foreach($schedule_groups as $schedule){
			$json['table'] .= "<tr id=\"<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
			
			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getName();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getRequiredWorkingHours();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getScheduleIn();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getScheduleOut();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getBreakOut();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getBreakIn();
			$json['table'] .= '</td>';

			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			if ($permission_action == Sprint_Modules::PERMISSION_02 && $schedule->getName() != "Default Shift AM" && $schedule->getName() != "Default Shift PM") {
				$json['table'] .= 	"
									<a class=\"btn btn-small\" href=\"javascript:void(0);\" onclick=\"editSchedule('" . Utilities::encrypt($schedule->getId()) . "');\">
										<i class=\"icon-pencil icon-fade\"></i> Edit
									</a>
									<a class=\"btn btn-small\" href=\"javascript:void(0)\"  onclick=\"javascript:removeSchedule('" . $schedule->getId() . "');\" style=\"display: inline-block;\" title=\"Remove\">
										<i class=\"icon-trash icon-fade\"></i> Delete
									</a>
									";
			}
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
		}
		
        if ($json['table'] == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}
	//Compress Schedule
	function index_compress_schedule(){
		$this->script();
        $this->script_month();
		Loader::appMainScript('project_site31.js');
		Loader::appMainScript('project_site_base22.js');

		if ($_GET['date'] == '') {
			$date = $this->var['date'] = date("n");
		} else {
			$date = $this->var['date'] = $_GET['date'];
		} 

		$_SESSION['date'] = $date;
		$date = $_SESSION['date'];
		$this->var['date_log'] = $_GET['date'];

		$name = "Default Compressed";
		$schedule_compress = G_Schedule_Template_Finder::findDefault($name);
		foreach($schedule_compress as $compress){
			$this->var['hours'] = $compress->getRequiredWorkingHours();
		}
		
        $this->var['action'] = url('new_schedule/_add_compress_schedule');

		$btn_add_employees_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:assignCompressScheduleEmployeeList();',
    		'id' 					=> '',
    		'class' 				=> 'btn btn-mini',
    		'icon' 					=> '<i class="icon-plus"><span class="tooltip" title="Add Employees"></span></i>',
    		'additional_attribute' 	=> 'title="Add Employees"',
    		'caption' 				=> 'Add Employee'
		); 

		$btn_remove_all_employees_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:removeAllStaggeredScheduleMemberEmployees("'.$id.'");',
    		'id' 					=> '',
    		'class' 				=> 'relative delete_link red',
    		'icon' 					=> '<span class="delete"></span>',
    		'additional_attribute' 	=> 'title="Remove all employees"',
    		'caption' 				=> 'Remove all employees'
		); 
		$btn_create_schedule_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:createCompressSchedule();',
    		'id' 					=> 'add_employee_button_wrapper',
    		'class' 				=> 'add_button tooltip',
    		'icon' 					=> '',
    		'additional_attribute' 	=> 'title="Create New Schedule"',
    		'caption' 				=> '<strong>+</strong><b>Create Schedule</b>'
    		); 
    	
    	$this->var['btn_create_schedule'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_create_schedule_config);
	
    	$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
    	$this->var['btn_add_department'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_department_config);
    	$this->var['btn_add_employees'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_employees_config);
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
		
		$this->var['compress_schedule']   = 'class="selected"';					
					
		$this->var['page_title']   = 'Compress Schedule';
		$this->view->setTemplate('template_new_schedule.php');
		$this->view->render('new_schedule/compress_schedule/index_compress_schedule.php',$this->var);	
	}

	function load_compress_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');

		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
				

			
			';
		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/compress_schedule/compress_schedule.php', $this->var);
	}
	
	function _load_compress_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);

		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		$schedule_type = "Compressed";

		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeIn() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeIn());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeOut() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeOut());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';
				$json['table'] .= "	<a class=\"btn btn-small\" href=\"javascript:void(0);\" id=\"staggered\" onclick=\"editEmployeeSchedule('" . Utilities::encrypt($d_in->getId()) . "');\">
											<i class=\"icon-pencil icon-fade\"></i> Edit
										</a>
										<!--<a class=\"btn btn-small\" href=\"javascript:void(0);\" onclick=\"deleteDTRLogTimeInTimeOut('" . Utilities::encrypt($d_in->getId()) . "');\">
											<i class=\"icon-trash icon-fade\"></i> Delete
										</a>-->";
				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';	
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}

	function load_no_compress_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');

		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
				

			
			';
		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/compress_schedule/no_compress_schedule.php', $this->var);
	}
	
	function _load_no_compress_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		if (count($employee_ids) > 0) {
			foreach ($employee_ids as $key => $field)
			$employee_ids[$key] = Utilities::decrypt($field);
		}
		$log_ids = array();
		$v2_attendance = G_Attendance_Log_Finder_V2::findV2AttendanceByPeriod($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		$data_time_in  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogsTimeIn($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		$data_time_out  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogsTimeOut($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);

		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
	
		$g = G_Employee_Schedule_Type_Finder::findByDate($_SESSION['date']);
		$employee_with_schedule = array();
		foreach ($g as $emp_sched) {
		  $is_employee_id_exist = 0;
		  foreach ($employee_with_schedule as $e_array) {
			if ($e_array == $emp_sched->employee_id) {
			  $is_employee_id_exist = 1;
			} else {
			  continue;
			}
		  }
		  if ($is_employee_id_exist == 1) {
			continue;
		  } else {
			$employee_with_schedule[] = $emp_sched->employee_id;
		  }
		}
		$employee = G_Employee_Finder::findAll();
		foreach($employee as $emp){
			$is_employee_have_sched = 0;
			foreach($employee_with_schedule as $emp_with_sched){
				if($emp_with_sched == $emp->getId()){
					$is_employee_have_sched = 1;
				}
			}
			if($is_employee_have_sched == 1){

			}else{
				$remarks = "";
				//$remarks = $d_in->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];
	
				$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);
	
				if ($device_info) {
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
	
				$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($emp->getId());
				
				$json['table'] .= '<tr>';
				$json['table'] .= '<td>';
				$json['table'] .= '<input type="checkbox" name="dtrChkNoSchedule[]" onchange="javascript:checkUncheckNoSchedule();" value="' . $emp->getId() . '" style="margin-left: 5px;">';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'No Schedule';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'N/A';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'N/A';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
				$json['table'] .= $department->title;
				$json['table'] .= '</td>';
	
				/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($emp->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/
	
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= '<td>';
					$json['table'] .= "	<a class=\"btn btn-small\" href=\"javascript:void(0);\" 
											onclick=\"editEmployeeNoSchedule('" . Utilities::encrypt($emp->getId()) . "');\">
											<i class=\"icon-pencil icon-fade\"></i> Edit
										</a>";
					$json['table'] .= '</td>';
				}
	
				$json['table'] .= '</tr>';
			}
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}

	function load_leave_compress_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');
		
		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
			';

		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/compress_schedule/leave_compress_schedule.php', $this->var);
	}
	
	function _load_leave_compress_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		if (count($employee_ids) > 0) {
			foreach ($employee_ids as $key => $field)
			$employee_ids[$key] = Utilities::decrypt($field);
		}
		
		$log_ids = array();
		$schedule_type = "Leave";
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChkLeave[]" onchange="javascript:checkUncheckLeave();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->employee_code;
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';


			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';
				if ($d_in->is_approved == "Pending") {
					$json['table'] .= "Pending <a title=\"Approve\" id=\"edit\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveLeaveRequest('" . Utilities::encrypt($d_in->id) . "');\"></a>";
				} else {
					$json['table'] .= "Approved";
				}
				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}

	function load_rest_day_compress_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');

		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }


		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
				

			
			';

		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/compress_schedule/rest_day_compress_schedule.php', $this->var);
	}
	
	function _load_rest_day_compress_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);

		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}
		$count = count($data);

		$schedule_type = "Rest Day";
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}
		$count = count($data);
			
		//Utilities::displayArray($v2_employee_leave);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChkRestDay[]" onchange="javascript:checkUncheckRestDay();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeIn() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeIn());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeOut() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeOut());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';

				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}

	function load_ob_compress_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');

		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
				

			
			';
		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/compress_schedule/ob_compress_schedule.php', $this->var);
	}
	
	function _load_ob_compress_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		$schedule_type = "OB";
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());
			$schedule = G_Employee_Schedule_Type_Finder::findAllByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChkOB[]" onchange="javascript:checkUncheckOB();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->employee_code;
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';
				if ($d_in->is_approved == "Pending") {
					$json['table'] .= "Pending <a title=\"Approve\" id=\"edit\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveLeaveRequest('" . Utilities::encrypt($d_in->id) . "');\"></a>";
				} else {
					$json['table'] .= "Approved";
				}
				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }

		//Construct Paginator			
		if ($_POST['paginatorIndex']) {
			$paginator_index = $_POST['paginatorIndex'];
		} else {
			$paginator_index = 1;
		}

		if ($count > 0) {
			$json['paginator'] .= '<div id="yui-dt0-paginator0" class="yui-dt-paginator yui-pg-container">';
			$skip_counting = 0;

			//Add First BTN
			//Validate if first record for first record
			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first">First</span>';
			} else {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',1)">First</a></span>';
			}
			//

			//Add Prev Btn
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting - $_POST['limit'];
				}
				$skip_counting += $_POST['limit'];
			}

			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous">Previous</span>';
			} else {
				$prev_record = $paginator_index - 1;
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $prev_record . ');">Previous</a></span>';
			}
			//

			//Construct Paginator index
			$skip_counting = 0;
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting + $_POST['limit'];
					//$json['paginator'] .= '<li class="paginator_selected">' . $i . '</li>';		
				} else {
					//$json['paginator'] .= '<li><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $i . ');">' . $i . '</a></li>';		
				}

				$skip_counting += $_POST['limit'];
			}
			//

			//Add Next Btn
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next">Next</span>';
			} else {
				$next_record = $paginator_index + 1;
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $next_record . ');">Next</a></span>';
			}
			//


			//Add Last BTN
			//Validate if last record
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last">Last</span>';
			} else {
				$skip_counting -= $_POST['limit'];
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $count . ')">Last</a></span>';
			}
			//
			//
			$json['paginator'] .= '</div>';
		}
		//
		echo json_encode($json);
	}
	function load_compressed_schedule_list_dt(){
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$schedule_groups = G_Schedule_Template_Finder::findByScheduleType("Compressed");

		foreach($schedule_groups as $schedule){
			$json['table'] .= "<tr id=\"<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
			
			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getName();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getRequiredWorkingHours();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getScheduleIn();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getScheduleOut();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getBreakOut();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getBreakIn();
			$json['table'] .= '</td>';

			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			if ($permission_action == Sprint_Modules::PERMISSION_02 && $schedule->getName() != "Default Compressed") {
				$json['table'] .= 	"
									<a class=\"btn btn-small\" href=\"javascript:void(0);\" onclick=\"editSchedule('" . Utilities::encrypt($schedule->getId()) . "');\">
										<i class=\"icon-pencil icon-fade\"></i> Edit
									</a>
									<a class=\"btn btn-small\" href=\"javascript:void(0)\"  onclick=\"javascript:removeSchedule('" . $schedule->getId() . "');\" style=\"display: inline-block;\" title=\"Remove\">
										<i class=\"icon-trash icon-fade\"></i> Delete
									</a>
									";
			}
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
		}
		
        if ($json['table'] == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}
	//Staggered Schedule
	function index_staggered_schedule(){
		$this->script();
        $this->script_month();
		Loader::appMainScript('project_site31.js');
		Loader::appMainScript('project_site_base22.js');

		if ($_GET['date'] == '') {
			$date = $this->var['date'] = date("n");
		} else {
			$date = $this->var['date'] = $_GET['date'];
		} 

		$_SESSION['date'] = $date;
		$date = $_SESSION['date'];
		$this->var['date_log'] = $_GET['date'];

		$name = "Default Staggered";
		$schedule_staggered = G_Schedule_Template_Finder::findDefault($name);
		foreach($schedule_staggered as $staggered){
			$this->var['hours'] = $staggered->getRequiredWorkingHours();
		}
		
        $this->var['action'] = url('new_schedule/_add_staggered_schedule');

		$btn_add_employees_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:assignStaggeredScheduleEmployeeList();',
    		'id' 					=> '',
    		'class' 				=> 'btn btn-mini',
    		'icon' 					=> '<i class="icon-plus"><span class="tooltip" title="Add Employees"></span></i>',
    		'additional_attribute' 	=> 'title="Add Employees"',
    		'caption' 				=> 'Add Employee'
		); 

		$btn_remove_all_employees_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:removeAllStaggeredScheduleMemberEmployees("'.$id.'");',
    		'id' 					=> '',
    		'class' 				=> 'relative delete_link red',
    		'icon' 					=> '<span class="delete"></span>',
    		'additional_attribute' 	=> 'title="Remove all employees"',
    		'caption' 				=> 'Remove all employees'
		); 

		$btn_create_schedule_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:createStaggeredSchedule();',
    		'id' 					=> 'add_employee_button_wrapper',
    		'class' 				=> 'add_button tooltip',
    		'icon' 					=> '',
    		'additional_attribute' 	=> 'title="Create New Schedule"',
    		'caption' 				=> '<strong>+</strong><b>Create Schedule</b>'
    		); 
    	
    	$this->var['btn_create_schedule'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_create_schedule_config);
	
    	$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
    	$this->var['btn_add_department'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_department_config);
    	$this->var['btn_add_employees'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_employees_config);
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
		
		$this->var['staggered_schedule']   = 'class="selected"';					
		$this->var['page_title']   = 'Staggered Schedule';
		$this->view->setTemplate('template_new_schedule.php');
		$this->view->render('new_schedule/staggered_schedule/index_staggered_schedule.php',$this->var);	
	}

	function load_staggered_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');

		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
				

			
			';
		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/staggered_schedule/staggered_schedule.php', $this->var);
	}
	
	function _load_staggered_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();

		$log_ids = array();
		$schedule_type = "Staggered";

		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeIn() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeIn());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeOut() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeOut());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';
				$json['table'] .= "	<a class=\"btn btn-small\" href=\"javascript:void(0);\" id=\"staggered\" onclick=\"editEmployeeSchedule('" . Utilities::encrypt($d_in->getId()) . "');\">
											<i class=\"icon-pencil icon-fade\"></i> Edit
										</a>
										<!--<a class=\"btn btn-small\" href=\"javascript:void(0);\" onclick=\"deleteDTRLogTimeInTimeOut('" . Utilities::encrypt($d_in->getId()) . "');\">
											<i class=\"icon-trash icon-fade\"></i> Delete
										</a>-->";
				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';	
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }

		//Construct Paginator			
		if ($_POST['paginatorIndex']) {
			$paginator_index = $_POST['paginatorIndex'];
		} else {
			$paginator_index = 1;
		}

		if ($count > 0) {
			$json['paginator'] .= '<div id="yui-dt0-paginator0" class="yui-dt-paginator yui-pg-container">';
			$skip_counting = 0;

			//Add First BTN
			//Validate if first record for first record
			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first">First</span>';
			} else {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',1)">First</a></span>';
			}
			//

			//Add Prev Btn
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting - $_POST['limit'];
				}
				$skip_counting += $_POST['limit'];
			}

			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous">Previous</span>';
			} else {
				$prev_record = $paginator_index - 1;
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $prev_record . ');">Previous</a></span>';
			}
			//

			//Construct Paginator index
			$skip_counting = 0;
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting + $_POST['limit'];
					//$json['paginator'] .= '<li class="paginator_selected">' . $i . '</li>';		
				} else {
					//$json['paginator'] .= '<li><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $i . ');">' . $i . '</a></li>';		
				}

				$skip_counting += $_POST['limit'];
			}
			//

			//Add Next Btn
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next">Next</span>';
			} else {
				$next_record = $paginator_index + 1;
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $next_record . ');">Next</a></span>';
			}
			//


			//Add Last BTN
			//Validate if last record
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last">Last</span>';
			} else {
				$skip_counting -= $_POST['limit'];
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $count . ')">Last</a></span>';
			}
			//
			//
			$json['paginator'] .= '</div>';
		}
		//
		echo json_encode($json);
	}

	function load_no_staggered_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');

		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
				

			
			';
		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/staggered_schedule/no_staggered_schedule.php', $this->var);
	}
	
	function _load_no_staggered_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		if (count($employee_ids) > 0) {
			foreach ($employee_ids as $key => $field)
			$employee_ids[$key] = Utilities::decrypt($field);
		}
		$log_ids = array();
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);

		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		$g = G_Employee_Schedule_Type_Finder::findByDate($_SESSION['date']);
		$employee_with_schedule = array();
		foreach ($g as $emp_sched) {
		  $is_employee_id_exist = 0;
		  foreach ($employee_with_schedule as $e_array) {
			if ($e_array == $emp_sched->employee_id) {
			  $is_employee_id_exist = 1;
			} else {
			  continue;
			}
		  }
		  if ($is_employee_id_exist == 1) {
			continue;
		  } else {
			$employee_with_schedule[] = $emp_sched->employee_id;
		  }
		}
		
		$employee = G_Employee_Finder::findAll();
		foreach($employee as $emp){
			$is_employee_have_sched = 0;
			foreach($employee_with_schedule as $emp_with_sched){
				if($emp_with_sched == $emp->getId()){
					$is_employee_have_sched = 1;
				}
			}
			if($is_employee_have_sched == 1){

			}else{
				$remarks = "";
				//$remarks = $d_in->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];
	
				$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);
	
				if ($device_info) {
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
	
				$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($emp->getId());
				
				$json['table'] .= '<tr>';
				$json['table'] .= '<td>';
				$json['table'] .= '<input type="checkbox" name="dtrChkNoSchedule[]" onchange="javascript:checkUncheckNoSchedule();" value="' . $emp->getId() . '" style="margin-left: 5px;">';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'No Schedule';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'N/A';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'N/A';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
				$json['table'] .= $department->title;
				$json['table'] .= '</td>';
	
				/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($emp->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/
	
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= '<td>';
					$json['table'] .= "	<a class=\"btn btn-small\" href=\"javascript:void(0);\" 
											onclick=\"editEmployeeNoSchedule('" . Utilities::encrypt($emp->getId()) . "');\">
											<i class=\"icon-pencil icon-fade\"></i> Edit
										</a>";
					$json['table'] .= '</td>';
				}
	
				$json['table'] .= '</tr>';
			}
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }

		//Construct Paginator			
		if ($_POST['paginatorIndex']) {
			$paginator_index = $_POST['paginatorIndex'];
		} else {
			$paginator_index = 1;
		}

		if ($count > 0) {
			$json['paginator'] .= '<div id="yui-dt0-paginator0" class="yui-dt-paginator yui-pg-container">';
			$skip_counting = 0;

			//Add First BTN
			//Validate if first record for first record
			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first">First</span>';
			} else {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',1)">First</a></span>';
			}
			//

			//Add Prev Btn
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting - $_POST['limit'];
				}
				$skip_counting += $_POST['limit'];
			}

			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous">Previous</span>';
			} else {
				$prev_record = $paginator_index - 1;
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $prev_record . ');">Previous</a></span>';
			}
			//

			//Construct Paginator index
			$skip_counting = 0;
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting + $_POST['limit'];
					//$json['paginator'] .= '<li class="paginator_selected">' . $i . '</li>';		
				} else {
					//$json['paginator'] .= '<li><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $i . ');">' . $i . '</a></li>';		
				}

				$skip_counting += $_POST['limit'];
			}
			//

			//Add Next Btn
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next">Next</span>';
			} else {
				$next_record = $paginator_index + 1;
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $next_record . ');">Next</a></span>';
			}
			//


			//Add Last BTN
			//Validate if last record
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last">Last</span>';
			} else {
				$skip_counting -= $_POST['limit'];
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $count . ')">Last</a></span>';
			}
			//
			//
			$json['paginator'] .= '</div>';
		}
		//
		echo json_encode($json);
	}

	function load_leave_staggered_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');
		
		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
			';

		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/staggered_schedule/leave_staggered_schedule.php', $this->var);
	}
	
	function _load_leave_staggered_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		if (count($employee_ids) > 0) {
			foreach ($employee_ids as $key => $field)
			$employee_ids[$key] = Utilities::decrypt($field);
		}
		
		$log_ids = array();
		$schedule_type = "Leave";
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChkLeave[]" onchange="javascript:checkUncheckLeave();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->employee_code;
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';


			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';
				if ($d_in->is_approved == "Pending") {
					$json['table'] .= "Pending <a title=\"Approve\" id=\"edit\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveLeaveRequest('" . Utilities::encrypt($d_in->id) . "');\"></a>";
				} else {
					$json['table'] .= "Approved";
				}
				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }

		//Construct Paginator			
		if ($_POST['paginatorIndex']) {
			$paginator_index = $_POST['paginatorIndex'];
		} else {
			$paginator_index = 1;
		}

		if ($count > 0) {
			$json['paginator'] .= '<div id="yui-dt0-paginator0" class="yui-dt-paginator yui-pg-container">';
			$skip_counting = 0;

			//Add First BTN
			//Validate if first record for first record
			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first">First</span>';
			} else {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',1)">First</a></span>';
			}
			//

			//Add Prev Btn
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting - $_POST['limit'];
				}
				$skip_counting += $_POST['limit'];
			}

			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous">Previous</span>';
			} else {
				$prev_record = $paginator_index - 1;
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $prev_record . ');">Previous</a></span>';
			}
			//

			//Construct Paginator index
			$skip_counting = 0;
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting + $_POST['limit'];
					//$json['paginator'] .= '<li class="paginator_selected">' . $i . '</li>';		
				} else {
					//$json['paginator'] .= '<li><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $i . ');">' . $i . '</a></li>';		
				}

				$skip_counting += $_POST['limit'];
			}
			//

			//Add Next Btn
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next">Next</span>';
			} else {
				$next_record = $paginator_index + 1;
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $next_record . ');">Next</a></span>';
			}
			//


			//Add Last BTN
			//Validate if last record
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last">Last</span>';
			} else {
				$skip_counting -= $_POST['limit'];
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $count . ')">Last</a></span>';
			}
			//
			//
			$json['paginator'] .= '</div>';
		}
		//
		echo json_encode($json);
	}

	function load_rest_day_staggered_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');

		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }


		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
				

			
			';

		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/staggered_schedule/rest_day_staggered_schedule.php', $this->var);
	}
	
	function _load_rest_day_staggered_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		$schedule_type = "Rest Day";
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}
		$count = count($data);
			
		//Utilities::displayArray($v2_employee_leave);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChkRestDay[]" onchange="javascript:checkUncheckRestDay();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeIn() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeIn());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeOut() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeOut());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';

				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }

		//Construct Paginator			
		if ($_POST['paginatorIndex']) {
			$paginator_index = $_POST['paginatorIndex'];
		} else {
			$paginator_index = 1;
		}

		if ($count > 0) {
			$json['paginator'] .= '<div id="yui-dt0-paginator0" class="yui-dt-paginator yui-pg-container">';
			$skip_counting = 0;

			//Add First BTN
			//Validate if first record for first record
			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first">First</span>';
			} else {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',1)">First</a></span>';
			}
			//

			//Add Prev Btn
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting - $_POST['limit'];
				}
				$skip_counting += $_POST['limit'];
			}

			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous">Previous</span>';
			} else {
				$prev_record = $paginator_index - 1;
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $prev_record . ');">Previous</a></span>';
			}
			//

			//Construct Paginator index
			$skip_counting = 0;
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting + $_POST['limit'];
					//$json['paginator'] .= '<li class="paginator_selected">' . $i . '</li>';		
				} else {
					//$json['paginator'] .= '<li><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $i . ');">' . $i . '</a></li>';		
				}

				$skip_counting += $_POST['limit'];
			}
			//

			//Add Next Btn
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next">Next</span>';
			} else {
				$next_record = $paginator_index + 1;
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $next_record . ');">Next</a></span>';
			}
			//


			//Add Last BTN
			//Validate if last record
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last">Last</span>';
			} else {
				$skip_counting -= $_POST['limit'];
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $count . ')">Last</a></span>';
			}
			//
			//
			$json['paginator'] .= '</div>';
		}
		//
		echo json_encode($json);
	}

	function load_ob_staggered_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');

		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
				

			
			';
		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/staggered_schedule/ob_staggered_schedule.php', $this->var);
	}
	
	function _load_ob_staggered_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		$schedule_type = "OB";
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());
			$schedule = G_Employee_Schedule_Type_Finder::findAllByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChkOB[]" onchange="javascript:checkUncheckOB();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->employee_code;
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';
				if ($d_in->is_approved == "Pending") {
					$json['table'] .= "Pending <a title=\"Approve\" id=\"edit\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveLeaveRequest('" . Utilities::encrypt($d_in->id) . "');\"></a>";
				} else {
					$json['table'] .= "Approved";
				}
				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }

		//Construct Paginator			
		if ($_POST['paginatorIndex']) {
			$paginator_index = $_POST['paginatorIndex'];
		} else {
			$paginator_index = 1;
		}

		if ($count > 0) {
			$json['paginator'] .= '<div id="yui-dt0-paginator0" class="yui-dt-paginator yui-pg-container">';
			$skip_counting = 0;

			//Add First BTN
			//Validate if first record for first record
			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first">First</span>';
			} else {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',1)">First</a></span>';
			}
			//

			//Add Prev Btn
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting - $_POST['limit'];
				}
				$skip_counting += $_POST['limit'];
			}

			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous">Previous</span>';
			} else {
				$prev_record = $paginator_index - 1;
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $prev_record . ');">Previous</a></span>';
			}
			//

			//Construct Paginator index
			$skip_counting = 0;
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting + $_POST['limit'];
					//$json['paginator'] .= '<li class="paginator_selected">' . $i . '</li>';		
				} else {
					//$json['paginator'] .= '<li><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $i . ');">' . $i . '</a></li>';		
				}

				$skip_counting += $_POST['limit'];
			}
			//

			//Add Next Btn
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next">Next</span>';
			} else {
				$next_record = $paginator_index + 1;
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $next_record . ');">Next</a></span>';
			}
			//


			//Add Last BTN
			//Validate if last record
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last">Last</span>';
			} else {
				$skip_counting -= $_POST['limit'];
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $count . ')">Last</a></span>';
			}
			//
			//
			$json['paginator'] .= '</div>';
		}
		//
		echo json_encode($json);
	}

	function load_staggered_schedule_list_dt(){
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$schedule_groups = G_Schedule_Template_Finder::findByScheduleType("Staggered");

		foreach($schedule_groups as $schedule){
			$json['table'] .= "<tr id=\"<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
			
			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getName();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getRequiredWorkingHours();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getScheduleIn();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getScheduleOut();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getBreakOut();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getBreakIn();
			$json['table'] .= '</td>';

			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			if ($permission_action == Sprint_Modules::PERMISSION_02 && $schedule->getName() != "Default Staggered") {
				$json['table'] .= 	"
									<a class=\"btn btn-small\" href=\"javascript:void(0);\" onclick=\"editSchedule('" . Utilities::encrypt($schedule->getId()) . "');\">
										<i class=\"icon-pencil icon-fade\"></i> Edit
									</a>
									<a class=\"btn btn-small\" href=\"javascript:void(0)\"  onclick=\"javascript:removeSchedule('" . $schedule->getId() . "');\" style=\"display: inline-block;\" title=\"Remove\">
										<i class=\"icon-trash icon-fade\"></i> Delete
									</a>
									";
			}
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
		}
		
        if ($json['table'] == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}
	//Flextime Schedule
	function index_flextime_schedule(){
		$this->script();
        $this->script_month();
		Loader::appMainScript('project_site31.js');
		Loader::appMainScript('project_site_base22.js');

		if ($_GET['date'] == '') {
			$date = $this->var['date'] = date("n");
		} else {
			$date = $this->var['date'] = $_GET['date'];
		} 

		$_SESSION['date'] = $date;
		$date = $_SESSION['date'];
		$this->var['date_log'] = $_GET['date'];

		$name = "Default Flexible";
		$schedule_flexible = G_Schedule_Template_Finder::findDefault($name);
		foreach($schedule_flexible as $flexible){
			$this->var['hours'] = $flexible->getRequiredWorkingHours();
			if($flexible->getScheduleIn() == "00:00:00"){
				$this->var['time_in'] = "";
			}else{
				$this->var['time_in'] = Tools::convert24To12Hour($flexible->getScheduleIn());
			}
			if($flexible->getScheduleOut() == "00:00:00"){
				$this->var['time_out'] = "";
			}else{
				$this->var['time_out'] = Tools::convert24To12Hour($flexible->getScheduleOut());
			}
			
		}
		
        $this->var['action'] = url('new_schedule/_add_flextime_schedule');

		$btn_add_employees_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:assignFlexibleScheduleEmployeeList();',
    		'id' 					=> '',
    		'class' 				=> 'btn btn-mini',
    		'icon' 					=> '<i class="icon-plus"><span class="tooltip" title="Add Employees"></span></i>',
    		'additional_attribute' 	=> 'title="Add Employees"',
    		'caption' 				=> 'Add Employee'
		); 

		$btn_remove_all_employees_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:removeAllStaggeredScheduleMemberEmployees("'.$id.'");',
    		'id' 					=> '',
    		'class' 				=> 'relative delete_link red',
    		'icon' 					=> '<span class="delete"></span>',
    		'additional_attribute' 	=> 'title="Remove all employees"',
    		'caption' 				=> 'Remove all employees'
		); 
		$btn_create_schedule_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:createFlexibleSchedule();',
    		'id' 					=> 'add_employee_button_wrapper',
    		'class' 				=> 'add_button tooltip',
    		'icon' 					=> '',
    		'additional_attribute' 	=> 'title="Create New Schedule"',
    		'caption' 				=> '<strong>+</strong><b>Create Schedule</b>'
    		); 
    	
    	$this->var['btn_create_schedule'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_create_schedule_config);
	
    	$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
    	$this->var['btn_add_department'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_department_config);
    	$this->var['btn_add_employees'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_employees_config);
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
		
		$this->var['flextime_schedule']   = 'class="selected"';					
					
		$this->var['page_title']   = 'Flextime Schedule';
		$this->view->setTemplate('template_new_schedule.php');
		$this->view->render('new_schedule/flextime_schedule/index_flextime_schedule.php',$this->var);	
	}

	function load_flextime_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');

		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
				

			
			';
		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/flextime_schedule/flextime_schedule.php', $this->var);
	}
	
	function _load_flextime_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		$schedule_type = "Flexible";

		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeIn() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeIn());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeOut() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeOut());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';
				$json['table'] .= "	<a class=\"btn btn-small\" href=\"javascript:void(0);\" id=\"staggered\" onclick=\"editEmployeeSchedule('" . Utilities::encrypt($d_in->getId()) . "');\">
											<i class=\"icon-pencil icon-fade\"></i> Edit
										</a>
										<!--<a class=\"btn btn-small\" href=\"javascript:void(0);\" onclick=\"deleteDTRLogTimeInTimeOut('" . Utilities::encrypt($d_in->getId()) . "');\">
											<i class=\"icon-trash icon-fade\"></i> Delete
										</a>-->";
				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';	
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}

	function load_no_flextime_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');

		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
				

			
			';
		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/flextime_schedule/no_flextime_schedule.php', $this->var);
	}
	
	function _load_no_flextime_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		if (count($employee_ids) > 0) {
			foreach ($employee_ids as $key => $field)
			$employee_ids[$key] = Utilities::decrypt($field);
		}
		$log_ids = array();
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);

		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		$g = G_Employee_Schedule_Type_Finder::findByDate($_SESSION['date']);
		$employee_with_schedule = array();
		foreach ($g as $emp_sched) {
		  $is_employee_id_exist = 0;
		  foreach ($employee_with_schedule as $e_array) {
			if ($e_array == $emp_sched->employee_id) {
			  $is_employee_id_exist = 1;
			} else {
			  continue;
			}
		  }
		  if ($is_employee_id_exist == 1) {
			continue;
		  } else {
			$employee_with_schedule[] = $emp_sched->employee_id;
		  }
		}
		$employee = G_Employee_Finder::findAll();
		foreach($employee as $emp){
			$is_employee_have_sched = 0;
			foreach($employee_with_schedule as $emp_with_sched){
				if($emp_with_sched == $emp->getId()){
					$is_employee_have_sched = 1;
				}
			}
			if($is_employee_have_sched == 1){

			}else{
				$remarks = "";
				//$remarks = $d_in->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];
	
				$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);
	
				if ($device_info) {
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
	
				$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($emp->getId());
				
				$json['table'] .= '<tr>';
				$json['table'] .= '<td>';
				$json['table'] .= '<input type="checkbox" name="dtrChkNoSchedule[]" onchange="javascript:checkUncheckNoSchedule();" value="' . $emp->getId() . '" style="margin-left: 5px;">';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'No Schedule';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'N/A';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'N/A';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
				$json['table'] .= $department->title;
				$json['table'] .= '</td>';
	
				/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($emp->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/
	
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= '<td>';
					$json['table'] .= "	<a class=\"btn btn-small\" href=\"javascript:void(0);\" 
											onclick=\"editEmployeeNoSchedule('" . Utilities::encrypt($emp->getId()) . "');\">
											<i class=\"icon-pencil icon-fade\"></i> Edit
										</a>";
					$json['table'] .= '</td>';
				}
	
				$json['table'] .= '</tr>';
			}
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}

	function load_leave_flextime_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');
		
		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
			';

		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/flextime_schedule/leave_flextime_schedule.php', $this->var);
	}
	
	function _load_leave_flextime_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		if (count($employee_ids) > 0) {
			foreach ($employee_ids as $key => $field)
			$employee_ids[$key] = Utilities::decrypt($field);
		}
		
		$log_ids = array();
		$schedule_type = "Leave";
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChkLeave[]" onchange="javascript:checkUncheckLeave();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->employee_code;
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';


			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';
				if ($d_in->is_approved == "Pending") {
					$json['table'] .= "Pending <a title=\"Approve\" id=\"edit\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveLeaveRequest('" . Utilities::encrypt($d_in->id) . "');\"></a>";
				} else {
					$json['table'] .= "Approved";
				}
				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}

	function load_rest_day_flextime_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');

		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }


		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
				

			
			';

		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/flextime_schedule/rest_day_flextime_schedule.php', $this->var);
	}
	
	function _load_rest_day_flextime_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		
		$schedule_type = "Rest Day";
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}
		$count = count($data);
			
		//Utilities::displayArray($v2_employee_leave);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChkRestDay[]" onchange="javascript:checkUncheckRestDay();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeIn() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeIn());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			if($d_in->getTimeOut() == null){
				$json['table'] .= "";
			}else{
				$json['table'] .= Tools::timeFormat($d_in->getTimeOut());
			}
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';

				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}

	function load_ob_flextime_schedule(){
		Jquery::loadMainKdatatable();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Loader::appMainStyle('assets/datatable/datatable-paginator.css');

		$now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

		$this->var['is_enable_popup_notification'] = true;
		$this->var['is_dtr_notification'] 		   = true;
		$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
		$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications;

		$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		$this->var['from']       				= $from_date;
		$this->var['to']         				= $to_date;

		$this->var['error_notification'] 		= '
				
				<button class="gray_button pull-right cancel-filter" style="display:none;">
					Cancel
				</button>
				<div class="dropdown dropright pull-right dtr-error-list" id="dropholder" style="width: fit-content;">
					<button class="gray_button">
						<div class="caret pull-right" style="margin-top: 6px !important;margin-left: 8px !important;"></div>
						<div class="pull-right">Filter <i class="icon-exclamation-sign"></i> <label class="filter-name"></label></div>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);" data-filter="multiple-in" id="filter_multiple_in" class="text-black">Multiple IN</a></li>
						<li><a href="javascript:void(0);" data-filter="multiple-out" id="filter_multiple_out" class="text-black">Multiple OUT</a></li>
						<li><a href="javascript:void(0);" data-filter="incomplete-break-logs" id="filter_incomplete_break_logs" class="text-black">Incomplete Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="no-break-logs" id="filter_no_break_logs" class="text-black">No Break Logs</a></li> 
						<li><a href="javascript:void(0);" data-filter="early-break-out" id="filter_early_break_out" class="text-black">Early Break Out</a></li> 
						<li><a href="javascript:void(0);" data-filter="late-break-in" id="filter_late_break_in" class="text-black">Late Break IN</a></li> 
					</ul>
				</div>
				

			
			';
		$department_filter = G_Company_Structure_Finder::findAll();
		$this->var['department_filter'] 		= $department_filter;
		$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();


		$this->view->render('new_schedule/flextime_schedule/ob_flextime_schedule.php', $this->var);
	}
	
	function _load_ob_flextime_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		$schedule_type = "OB";
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id'], $schedule_type);
		
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$remarks = "";
			//$remarks = $d_in->getRemarks();
			//get device no.
			$remarks2 = explode(':', $remarks);
			$machine_no = $remarks2[1];

			$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);

			if ($device_info) {
				//utilities::displayArray($device_info->device_name);exit();
				$device_name = $device_info->device_name;
			}

			$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());
			$schedule = G_Employee_Schedule_Type_Finder::findAllByEmployeeId($d_in->getEmployeeId());

			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '<input type="checkbox" name="dtrChkOB[]" onchange="javascript:checkUncheckOB();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $d_in->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->employee_code;
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
			$json['table'] .= '</td>';

			$json['table'] .= '<td>';
			$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
			$json['table'] .= $department->title;
			$json['table'] .= '</td>';

			/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/

			/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/

			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= '<td>';
				if ($d_in->is_approved == "Pending") {
					$json['table'] .= "Pending <a title=\"Approve\" id=\"edit\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveLeaveRequest('" . Utilities::encrypt($d_in->id) . "');\"></a>";
				} else {
					$json['table'] .= "Approved";
				}
				$json['table'] .= '</td>';
			}

			$json['table'] .= '</tr>';
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }

		//Construct Paginator			
		if ($_POST['paginatorIndex']) {
			$paginator_index = $_POST['paginatorIndex'];
		} else {
			$paginator_index = 1;
		}

		if ($count > 0) {
			$json['paginator'] .= '<div id="yui-dt0-paginator0" class="yui-dt-paginator yui-pg-container">';
			$skip_counting = 0;

			//Add First BTN
			//Validate if first record for first record
			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first">First</span>';
			} else {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',1)">First</a></span>';
			}
			//

			//Add Prev Btn
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting - $_POST['limit'];
				}
				$skip_counting += $_POST['limit'];
			}

			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous">Previous</span>';
			} else {
				$prev_record = $paginator_index - 1;
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $prev_record . ');">Previous</a></span>';
			}
			//

			//Construct Paginator index
			$skip_counting = 0;
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting + $_POST['limit'];
					//$json['paginator'] .= '<li class="paginator_selected">' . $i . '</li>';		
				} else {
					//$json['paginator'] .= '<li><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $i . ');">' . $i . '</a></li>';		
				}

				$skip_counting += $_POST['limit'];
			}
			//

			//Add Next Btn
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next">Next</span>';
			} else {
				$next_record = $paginator_index + 1;
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $next_record . ');">Next</a></span>';
			}
			//


			//Add Last BTN
			//Validate if last record
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last">Last</span>';
			} else {
				$skip_counting -= $_POST['limit'];
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $count . ')">Last</a></span>';
			}
			//
			//
			$json['paginator'] .= '</div>';
		}
		//
		echo json_encode($json);
	}

	function load_flexible_schedule_list_dt(){
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$schedule_groups = G_Schedule_Template_Finder::findByScheduleType("Flexible");

		foreach($schedule_groups as $schedule){
			$json['table'] .= "<tr id=\"<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
			
			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getScheduleType();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getName();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getRequiredWorkingHours();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getScheduleIn();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getScheduleOut();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getBreakOut();
			$json['table'] .= '</td>';

			$json['table'] .= "<td>";
			$json['table'] .= $schedule->getBreakIn();
			$json['table'] .= '</td>';

			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			if ($permission_action == Sprint_Modules::PERMISSION_02 && $schedule->getName() != "Default Flexible") {
				$json['table'] .= 	"
									<a class=\"btn btn-small\" href=\"javascript:void(0);\" onclick=\"editSchedule('" . Utilities::encrypt($schedule->getId()) . "');\">
										<i class=\"icon-pencil icon-fade\"></i> Edit
									</a>
									<a class=\"btn btn-small\" href=\"javascript:void(0)\"  onclick=\"javascript:removeSchedule('" . $schedule->getId() . "');\" style=\"display: inline-block;\" title=\"Remove\">
										<i class=\"icon-trash icon-fade\"></i> Delete
									</a>
									";
			}
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
		}
		
        if ($json['table'] == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}

	function security_schedule(){
		$this->script();
        $this->script_month();

		$this->var['action'] = url('new_schedule/show_employee');
        $this->var['months'] = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $this->var['years'] = array(date('Y'), date('Y')-1);

		//$this->var['schedules'] = G_Schedule_Finder::findAll();
		
		$this->var['security_schedule']   = 'class="selected"';					
					
		$this->var['page_title']   = 'Staggered Schedule';
		$this->view->setTemplate('template_new_schedule.php');
		$this->view->render('new_schedule/security_schedule.php',$this->var);	
	}

	function actual_hours(){
		$this->script();
        $this->script_month();

		$this->var['action'] = url('new_schedule/show_employee');
        $this->var['months'] = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $this->var['years'] = array(date('Y'), date('Y')-1);

		//$this->var['schedules'] = G_Schedule_Finder::findAll();
		
		$this->var['actual_hours']   = 'class="selected"';					
					
		$this->var['page_title']   = 'Actual Hours';
		$this->view->setTemplate('template_new_schedule.php');
		$this->view->render('new_schedule/actual_hours.php',$this->var);	
	}

	function calendar(){
		$this->script();
        $this->script_month();
		
		$this->var['calendar']   = 'class="selected"';					
					
		$this->var['page_title']   = 'Calendar';
		$this->view->setTemplate('template_new_schedule.php');
		$this->view->render('new_schedule/calendar.php',$this->var);	
	}

	function schedule_list(){
		$this->script();
        $this->script_month();
		
		$this->var['schedule_list']   = 'class="selected"';					
					
		$this->var['page_title']   = 'Schedule List';
		$this->view->setTemplate('template_new_schedule.php');
		$this->view->render('new_schedule/schedule_list.php',$this->var);	
	}

	function set_employee_schedule(){
		$this->script();
        $this->script_month();
		
		if ($_GET['date'] == '') {
            $date = $this->var['date'] = date("n");
        } else {
            $date = $this->var['date'] = $_GET['date'];
        } 

        $_SESSION['date'] = $date;
		$date = $_SESSION['date'];
		$this->var['date_log'] = $date;
        $this->var['schedule_groups'] = G_Schedule_Group_Finder::findAllSchedule();
		//$this->var['schedule_groups'] = G_Schedule_Group_Finder::findAll();
    	
    	$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');

		$this->var['set_employee_schedule']   = 'class="selected"';					
					
		$this->var['page_title']   = 'Set Employee Schedule';
		$this->view->setTemplate('template_new_schedule.php');
		$this->view->render('new_schedule/set_employee_schedule.php',$this->var);	
	}

	function mass_set_schedule_monthly(){
		$this->script();
        $this->script_month();
		
		$this->var['mass_set_schedule_monthly']   = 'class="selected"';					
					
		$this->var['page_title']   = 'Mass Set Schedule Monthly';
		$this->view->setTemplate('template_new_schedule.php');
		$this->view->render('new_schedule/mass_set_schedule_monthly.php',$this->var);	
	}

	function schedule_settings(){
		$this->script();
        $this->script_month();				
	
		$schedule_settings = G_Schedule_Settings_Finder::findAll();
		foreach($schedule_settings as $settings){
			$this->var['checked_shift'] = $settings->getShift();
			$this->var['checked_flexible'] = $settings->getFlexible();
			$this->var['checked_compressed'] = $settings->getCompressed();
			$this->var['checked_staggered'] = $settings->getStaggered();
			$this->var['checked_security'] = $settings->getSecurity();
			$this->var['checked_actual'] = $settings->getActual();
			$this->var['checked_per_trip'] = $settings->getPerTrip();
		}

		$stagger_name = "Default Staggered";
		$schedule_type = G_Schedule_Template_Finder::findDefault($stagger_name);
		foreach($schedule_type as $schedule){
			$this->var['staggered_hours'] = $schedule->getRequiredWorkingHours();
		}

		$stagger_name = "Default Compressed";
		$schedule_type = G_Schedule_Template_Finder::findDefault($stagger_name);
		foreach($schedule_type as $schedule){
			$this->var['compressed_hours'] = $schedule->getRequiredWorkingHours();
		}

		$stagger_name = "Default Flexible";
		$schedule_type = G_Schedule_Template_Finder::findDefault($stagger_name);
		foreach($schedule_type as $schedule){
			$this->var['flexible_hours'] = $schedule->getRequiredWorkingHours();
		}
		
		$this->var['action'] = url('new_schedule/save_schedule_settings');
		$this->var['schedule_settings']   = 'class="selected"';
		$this->var['page_title']   = 'Schedule Settings';
		$this->view->setTemplate('template_new_schedule.php');
		$this->view->render('new_schedule/schedule_settings.php',$this->var);	
	}

	function _load_schedule_settings()
	{
		$this->validatePermission(G_Sprint_Modules::HR,'schedule','');

		Utilities::ajaxRequest();
		$j = G_Job_Finder::findAll();
		$project_site = G_PROJECT_SITE::findAllProjectSite();
		$this->var['departments'] = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByParentId($this->company_structure_id);
		$this->var['job']         = $j;
		$this->var['title']       = "Schedule Settings";
		$this->var['project_site'] = $project_site;
		$this->view->noTemplate();
		$this->view->render('new_schedule/schedule_settings.php',$this->var);	
	}
	
	function save_schedule_settings(){
		$settings = new G_Schedule_Settings;
		$settings->setId(1);
		$settings->setShift($_POST['shift']);
		$settings->setFlexible($_POST['flexible']);
		$settings->setCompressed($_POST['compressed']);
		$settings->setStaggered($_POST['staggered']);
		$settings->setSecurity($_POST['security']);
		$settings->setActual($_POST['actual']);
		$settings->setPerTrip($_POST['per_trip']);
		$settings->save();

		$name_staggered = 'Default Staggered';
		$name_compressed = 'Default Compressed';
		$name_flexible = 'Default Flexible';

		$default_staggered = G_Schedule_Template_Finder::findAllToUpdate($name_staggered);
		if($_POST['staggered_hours'] != NULL){
			foreach($default_staggered as $sched){
				$sched->setRequiredWorkingHours($_POST['staggered_hours']);
				$sched->save();
			}
		}
		
		$default_compressed = G_Schedule_Template_Finder::findAllToUpdate($name_compressed);
		if($_POST['compressed_hours'] != NULL){
			foreach($default_compressed as $sched){
				$sched->setRequiredWorkingHours($_POST['compressed_hours']);
				$sched->save();
			}
		}

		$default_flexible = G_Schedule_Template_Finder::findAllToUpdate($name_flexible);
		if($_POST['flexible_hours'] != NULL){
			foreach($default_flexible as $sched){
				$sched->setRequiredWorkingHours($_POST['flexible_hours']);
				$sched->save();
			}
		}
		
		redirect("new_schedule/schedule_settings");
	}

	function schedule_error()
	{
		$this->view->setTemplate('template.php');
		$this->view->render('attendance/error/schedule_error.php',$this->var);		
	}
	
	function show_staggered_schedule() {
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTipsy();		

		Loader::appMainScript('restday_base.js');
		Loader::appMainScript('restday.js');		
		
		$public_id = (string) $_GET['id'];
		$s = G_Schedule_Template_Finder::findByPublicId($public_id);
		if ($s) {
			//$breaktime_schedules = $s->getAttachedBreaktimeSchedules();
			//$breaktime_string    = implode(" / ", $breaktime_schedules);

			//$this->var['breaktime']     = $breaktime_string;
			$this->var['public_id']     	= $public_id;
			$this->var['schedule_id']   	= $s->getId();
			$this->var['schedule_name'] 	= $title = $s->getName();
            $this->var['effectivity_date'] 	= $s->getStartDate();
            $this->var['end_date']         	= $s->getEndDate();
			$this->var['title'] 			= '- '. $title;
			$schedules = G_Schedule_Template_Finder::findAllById($s);
			$this->var['schedule_date_time'] = G_Schedule_Template_Helper::showSchedulesWithHours($schedules);
			$this->view->setTemplate('template.php');

			$btn_edit_schedule_config = array(
	    		'module'				=> 'hr',
	    		'parent_index'			=> 'schedule',
	    		'child_index'			=> '',
	    		'href' 					=> 'javascript:void(0);',
	    		'onclick' 				=> 'javascript:editStaggeredSchedule("'.$public_id.'");',
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
	    		'onclick' 				=> 'javascript:deleteStaggeredSchedule("'.$public_id.'");',
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
	    	//$effectivity_date = $s->getEffectivityDate();

	    	if($s->getName() == 'default'){
	    		$nw_effectiveDate = '';
	    		$nw_endDate = '';
	    	}
	    	else{
	    		$nw_effectiveDate = $s->getStartDate();
	    		$nw_endDate = $s->getEndDate();
	    	}
	    	$end_date = $s->getEndDate();
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_VIEW, ' Employee Schedule of', $title, $nw_effectiveDate, $nw_endDate, 1, '', '');

			if ($s->isDefault()) {
				$this->view->render('new_schedule/show_schedule_default.php',$this->var);
			} else {
				$this->view->render('new_schedule/show_staggered_schedule.php',$this->var);
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
		$id = $_POST['id'];
		if (empty($_POST['name'])) { $error++; }
		if (empty($_POST['required_working_hours'])) { $error++; }

		if ($error > 0) {
			exit('Failed to edit schedule');	
		}
		
		$schedule_name = $_POST['name'];
		$hours = $_POST['required_working_hours'];
		if($_POST['schedule_type'] == "Staggered" || $_POST['schedule_type'] == "Compress"){
			$schedule_in = "00:00:00";
			$break_out = "00:00:00";
			$break_in = "00:00:00";
			$schedule_out = "00:00:00";
		}else{
			$schedule_in = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['schedule_in']['hh']) .':'. Tools::addLeadingZero($_POST['schedule_in']['mm']) .' '. $_POST['schedule_in']['am']));
			$schedule_out = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['schedule_out']['hh']) .':'. Tools::addLeadingZero($_POST['schedule_out']['mm']) .' '. $_POST['schedule_out']['am']));
			$break_in = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['break_in']['hh']) .':'. Tools::addLeadingZero($_POST['break_in']['mm']) .' '. $_POST['break_in']['am']));
			$break_out = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['break_out']['hh']) .':'. Tools::addLeadingZero($_POST['break_out']['mm']) .' '. $_POST['break_out']['am']));	
		}
				
		$s = G_Schedule_Template_Finder::findById($id);
		$s->setName($schedule_name);
		$s->setRequiredWorkingHours($hours);
		$s->setScheduleIn($schedule_in);
		$s->setScheduleOut($schedule_out);
		$s->setBreakOut($break_out);
		$s->setBreakIn($break_in);
		$is_saved = $s->saveStaggered();
		
		if ($is_saved) {
			$return['schedule_name'] = $schedule_name;
			$return['required_working_hours'] = $hours;
			$return['schedule_in'] = Tools::timeFormat($schedule_in);
			$return['schedule_out'] = Tools::timeFormat($schedule_out);
			$return['break_in'] = Tools::timeFormat($break_in);
			$return['break_out'] = Tools::timeFormat($break_out);
			$return['message'] = 'Schedule has been saved';
			$return['is_saved'] = true;
		} else {
			$return['message'] = "There's an error occured. Schedule has not been saved. Please contact the developer.";
			$return['is_saved'] = false;
		}
		echo json_encode($return);
	}

	function _edit_employee_schedule() {
		$error = 0;
		$schedule_id = (int) $_POST['schedule_id'];
		$employee_id = $_POST['employee_id'];
		$v2_employee_attendance_id = $_POST['v2_employee_attendance_id'];
		
		if (empty($schedule_id)) { $error++; }
	
		if ($error > 0) {
			exit('Failed to edit schedule');	
		}
		
		$schedule_name = $_POST['name'];
		
		$schedule_id = (int) $_POST['schedule_id'];

		$s = G_Schedule_Template_Finder::findById($schedule_id);
		//$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
		//$start_date = $effectivity_date; //$c->getStartDate();
		if ($c) {
			$end_date = $c->getEndDate();
		}

		$e = G_Employee_Finder::findById($employee_id);
		$employe_already_assigned = G_Employee_Schedule_Type_Finder::findByEmployeeAndDate($e, $_SESSION['date']);

		$is_saved = $s->assignToEmployee($e, $employe_already_assigned, $_SESSION['date']); //here
		$employe_already_assigned = G_Employee_Schedule_Type_Finder::findByEmployeeAndDate($e, $_SESSION['date']);
		
		// UPDATE ATTENDANCE
		$is_saved = G_Employee_Attendance_Manager_V2::updateScheduleTemplate($v2_employee_attendance_id, $employe_already_assigned);
		if ($c) {
			//G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
		}

		if ($is_saved) {
			$return['schedule_name'] = $schedule_name;
			$return['message'] = 'Schedule has been saved';
			$return['is_saved'] = true;
		} else {
			$return['message'] = "There's an error occured. Schedule has not been saved. Please contact the developer.";
			$return['is_saved'] = false;
		}
		echo json_encode($return);
	}

	function _assign_staggered_schedule() {
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
					$s = G_Schedule_Template_Finder::findByPublicId($schedule_id);					
					if (!G_Schedule_Template_Helper::isGroupAlreadyAssigned($g, $s)) {
					    $effectivity_date = $s->getStartDate();
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
    			$s = G_Schedule_Template_Finder::findByPublicId($schedule_id);
    			$effectivity_date = $s->getStartDate();    			
    			$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
    			$start_date = $effectivity_date;//$c->getStartDate();
    			if($c) {
    				$end_date = $c->getEndDate();
                }

				foreach ($employees as $employee_id) {
					$e = G_Employee_Finder::findById($employee_id);

					if (!G_Schedule_Template_Helper::isEmployeeAlreadyAssigned($e, $s)) {
						$s->assignToEmployee($e, $s->getStartDate(), $s->getEndDate());//here
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

	function _remove_all_staggered_schedule_member_employees() {
		$schedule_group_public_id = (string) $_POST['schedule_group_public_id'];
	
		$is_removed = false;
		$s = G_Schedule_Template_Finder::findByPublicId($schedule_group_public_id);
		$is_removed = G_Schedule_Template_Manager::removeAllEmployee($s);
		/*if ($s) {
			$effectivity_date = $s->getStartDate();
			
            $es = G_Employee_Finder::findByScheduleGroup($s);
            $is_removed = $s->removeEmployeesStaggeredSchedule();
			if ($is_removed) {
				$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
				$start_date = $effectivity_date;//$c->getStartDate();
				if($c) {
					$end_date = $c->getEndDate();
					G_Attendance_Helper::updateAttendanceByEmployeesAndPeriod($es, $start_date, $end_date);
				}
			}
		}*/
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
			//$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_DELETE, ' ALL Schedule to ', $s->getName(), $effectivity_date, $s->getEndDate(), 0, '', '');
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

	function _remove_staggered_schedule_member() {
		$employee_group_id = (int) $_POST['employee_group_id'];
		$schedule_id = $_POST['schedule_id'];
		$employee_or_group = (string) $_POST['employee_or_group'];

		$is_removed = false;
		$s = G_Schedule_Template_Finder::findByPublicId($schedule_id);
		if ($s) {
			$effectivity_date = $s->getStartDate();
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
	
	function _delete_staggered_schedule() {
		$schedule_id = $_POST['schedule_id'];		
		$is_deleted = false;
		$s = G_Schedule_Template_Finder::findByPublicId($schedule_id);
		
		if($s){
			$staggered_schedule = G_Schedule_Template_Finder::findById($s->getId());

			$date = $s->getStartDate();

			if ($s->countMembers() == 0) {
				$employees = G_Employee_Finder::findByScheduleGroup($s);
				$is_deleted = $staggered_schedule->deleteSchedule(); // delete schedules under this group
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
				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_DELETE, 'Schedule of ', $s->getName(), $s->getStartDate(), $s->getEndDate(), 0, '', '');
			}
		}

		if ($s) {
			$date = $s->getStartDate();
			if ($s->isDefault()) {
				$return['message'] = "This is the default schedule. You can't delete the default schedule.";
				$is_deleted = false;

				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_DELETE, 'Schedule', $s->getName(), $s->getStartDate(), $s->getEndDate(), 0, '', '');

			} else {
				if ($s->countMembers() == 0) {
					/*$employees = G_Employee_Finder::findSpecificEmployeeByStaggeredScheduleGroup($s);
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
					$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_DELETE, 'Schedule of ', $s->getName(), $s->getStartDate(), $s->getEndDate(), 0, '', '');
				}
			}
		} else {
			$return['message'] = 'An error occured. Schedule has not been deleted. Please contact the developer';	

				//General Reports / Shr Audit Trail
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_DELETE, 'Schedule of ', $s->getName(), $s->getStartDate(), $s->getEndDate(), 0, '', '');
		}
		$return['is_deleted'] = $is_deleted;

		if ($is_deleted) {
			$return['message'] = 'Schedule has been deleted';

			//General Reports / Shr Audit Trail
			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_DELETE, 'Schedule of ', $s->getName(), $s->getStartDate(), $s->getEndDate(), 1, '', '');
		}
		echo json_encode($return);
	}
	
	function _add_staggered_schedule() {
		$this->_edit_staggered_schedule();
	}

	function _edit_staggered_schedule(){
		$hours             	= $_POST['hours'];
		$is_changed       	= $_POST['is_changed'];
		$schedule 			= $_POST;
		
		if ($schedule['hours'] != 0) {
			$name = "Default Staggered";
			$schedule = G_Schedule_Template_Finder::findAllToUpdate($name);
			
			if (!$schedule) {
				$group = new G_Schedule_Template;
			}
			foreach($schedule as $sched){
				$sched->setRequiredWorkingHours($hours);
				if ($sched->getId() > 0) {
					$group_id = $sched->getId();
					$sched->saveStaggered();
				} else {
					$group_id = $sched->saveStaggered();
				}
				$schedule_name = $sched->getName();
			}
			//important for payroll generation
			//$group = G_Schedule_Template_Finder::findById($group_id);
			//$group->setEndDate($end_date);
			//G_Schedule_Template_Helper::updateEmployeeStartAndEndDate($group, $effectivity_date);
			

			//  UPDATE ATTENDANCE
			if ($is_changed == 'yes') {
				$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
				$start_date = $effectivity_date; //$c->getStartDate();
				if ($c) {
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
			$return['title_string'] = $schedule_name;
			$return['schedule_group_id'] = $group_id;
		} else {
			$return['message'] = 'You have to add at least 1 schedule';
			$return['error_type'] = 'no_entry';
			$return['is_saved'] = false;

			//General Reports / Shr Audit Trail
			//$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, 'Schedule of ', $group->getName(), $effectivity_date, $end_date, 0, '', '');
		}
		$now = date('Y-m-d');
		redirect("new_schedule/index_staggered_schedule?date={$now}");
	}

	function _add_compress_schedule() {
		$this->_edit_compress_schedule();
	}
	function _edit_compress_schedule(){
		$hours             	= $_POST['hours'];
		$is_changed       	= $_POST['is_changed'];
		$schedule 			= $_POST;
		
		if ($schedule['hours'] != 0) {
			$name = "Default Compressed";
			$schedule = G_Schedule_Template_Finder::findAllToUpdate($name);
			
			if (!$schedule) {
				$group = new G_Schedule_Template;
			}
			foreach($schedule as $sched){
				$sched->setRequiredWorkingHours($hours);
				if ($sched->getId() > 0) {
					$group_id = $sched->getId();
					$sched->saveStaggered();
				} else {
					$group_id = $sched->saveStaggered();
				}
				$schedule_name = $sched->getName();
			}
			//important for payroll generation
			//$group = G_Schedule_Template_Finder::findById($group_id);
			//$group->setEndDate($end_date);
			//G_Schedule_Template_Helper::updateEmployeeStartAndEndDate($group, $effectivity_date);
			

			//  UPDATE ATTENDANCE
			if ($is_changed == 'yes') {
				$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
				$start_date = $effectivity_date; //$c->getStartDate();
				if ($c) {
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
			$return['title_string'] = $schedule_name;
			$return['schedule_group_id'] = $group_id;
		} else {
			$return['message'] = 'You have to add at least 1 schedule';
			$return['error_type'] = 'no_entry';
			$return['is_saved'] = false;

			//General Reports / Shr Audit Trail
			//$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, 'Schedule of ', $group->getName(), $effectivity_date, $end_date, 0, '', '');
		}
		$now = date('Y-m-d');
		redirect("new_schedule/index_compress_schedule?date={$now}");
	}

	function _add_flextime_schedule() {
		$this->_edit_flextime_schedule();
	}
	function _edit_flextime_schedule(){
		$hours             	= $_POST['hours'];
		$time_in			= $_POST['time_in'];
		$time_out			= $_POST['time_out'];
		$is_changed       	= $_POST['is_changed'];
		$schedule 			= $_POST;
		
		if ($schedule['hours'] != 0) {
			$name = "Default Flexible";
			$schedule = G_Schedule_Template_Finder::findAllToUpdate($name);
			
			if (!$schedule) {
				$group = new G_Schedule_Template;
			}
			foreach($schedule as $sched){
				$sched->setRequiredWorkingHours($hours);
				$sched->setScheduleIn(Tools::convert12To24Hour($time_in));
				$sched->setScheduleOut(Tools::convert12To24Hour($time_out));
				if ($sched->getId() > 0) {
					$group_id = $sched->getId();
					$sched->saveStaggered();
				} else {
					$group_id = $sched->saveStaggered();
				}
				$schedule_name = $sched->getName();
			}
			//important for payroll generation
			//$group = G_Schedule_Template_Finder::findById($group_id);
			//$group->setEndDate($end_date);
			//G_Schedule_Template_Helper::updateEmployeeStartAndEndDate($group, $effectivity_date);
			

			//  UPDATE ATTENDANCE
			if ($is_changed == 'yes') {
				$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
				$start_date = $effectivity_date; //$c->getStartDate();
				if ($c) {
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
			$return['title_string'] = $schedule_name;
			$return['schedule_group_id'] = $group_id;
		} else {
			$return['message'] = 'You have to add at least 1 schedule';
			$return['error_type'] = 'no_entry';
			$return['is_saved'] = false;

			//General Reports / Shr Audit Trail
			//$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, 'Schedule of ', $group->getName(), $effectivity_date, $end_date, 0, '', '');
		}
		$now = date('Y-m-d');
		redirect("new_schedule/index_flextime_schedule?date={$now}");
	}
	//Add Employee
	function ajax_assign_staggered_schedule_employee_list() {
		$schedule_type = "Staggered";
		$this->var['schedule'] = G_Schedule_Template_Finder::findByScheduleType($schedule_type);
		$this->view->noTemplate();
		$this->view->render('project_site/forms/ajax_assign_schedule_employee_list_form.php',$this->var);
	}
	function ajax_assign_compress_schedule_employee_list() {
		$schedule_type = "Compressed";
		$this->var['schedule'] = G_Schedule_Template_Finder::findByScheduleType($schedule_type);
		$this->view->noTemplate();
		$this->view->render('project_site/forms/ajax_assign_schedule_employee_list_form.php',$this->var);
	}
	function ajax_assign_flexible_schedule_employee_list() {
		$schedule_type = "Flexible";
		$this->var['schedule'] = G_Schedule_Template_Finder::findByScheduleType($schedule_type);
		$this->view->noTemplate();
		$this->view->render('project_site/forms/ajax_assign_schedule_employee_list_form.php',$this->var);
	}
	function ajax_assign_shift_schedule_employee_list() {
		$schedule_type = "Shift";
		$this->var['schedule'] = G_Schedule_Template_Finder::findByScheduleType($schedule_type);
		$this->view->noTemplate();
		$this->view->render('project_site/forms/ajax_assign_schedule_employee_list_form.php',$this->var);
	}
	function _assign_schedule_from_employee_list() {
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
			$schedule_id = $_POST['schedule'];
			if (!empty($employees)) {
				$s = G_Schedule_Template_Finder::findById($schedule_id);
				//$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
				//$start_date = $effectivity_date; //$c->getStartDate();
				$start_date = $_POST['start_date'];
				$end_date = $_POST['end_date'];
				$begin = new DateTime($start_date);
				$end   = new DateTime($end_date);

				for($i = $begin; $i <= $end; $i->modify('+1 day')){
					$date = $i->format("Y-m-d");
					foreach ($employees as $employee_id) {
						$e = G_Employee_Finder::findById($employee_id);

						$employe_already_assigned = G_Employee_Schedule_Type_Finder::findAllByEmployeeIdAndScheduleId($employee_id, $schedule_id);
						$s->assignToEmployee($e, $employe_already_assigned, $date); //here

						// UPDATE ATTENDANCE
						if ($c) {
							G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
						}

						//General Reports / Shr Audit Trail
						$shr_emp = G_Employee_Helper::findByEmployeeId($employee_id);
						$emp_name = $shr_emp['firstname'] . ' ' . $shr_emp['lastname'];
						$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, ' Schedule of ', $emp_name, $start_date, $date, 1, $shr_emp['position'], $shr_emp['department']);
					}
				}
			}

			$return['saved'] = true;
			
			echo json_encode($return);
		}			
	}

	//Create Schedule - Flexible
	function _create_flexible_schedule() {
		$hours             	= $_POST['hours'];
		$schedule_name 		= $_POST['name'];
		$start_date 		= $_POST['start_date'];
		$end_date         	= $_POST['end_date'];
		$is_changed       	= $_POST['is_changed'];
		$schedule 			= $_POST;
		
		if ($hours > 0) {
			$group = new G_Schedule_Template;
			
			$group->setName($schedule_name);
			$group->setScheduleType("Flexible");
			$group->setRequiredWorkingHours($hours);
			$group->setScheduleIn(Tools::convert12To24Hour($_POST['time_in']));
			$group->setScheduleOut(Tools::convert12To24Hour($_POST['time_out']));

			if ($group->getId() > 0) {
				$group_id = $group->getId();
				$group->save();
			} else {
				$group_id = $group->save();
			}
			

			//  UPDATE ATTENDANCE
			if ($is_changed == 'yes') {
				$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
				$start_date = $effectivity_date; //$c->getStartDate();
				if ($c) {
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
			$return['schedule_group_id'] = $group_id;
		} else {
			$return['message'] = 'You have to add at least 1 schedule';
			$return['error_type'] = 'no_entry';
			$return['is_saved'] = false;

			//General Reports / Shr Audit Trail
			//$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, 'Schedule of ', $group->getName(), $effectivity_date, $end_date, 0, '', '');
		}
		$now = date('Y-m-d');
		redirect("new_schedule/index_flextime_schedule?date={$now}");
	}
	//Create Schedule - Staggered
	function _create_staggered_schedule() {
		$hours             	= $_POST['hours'];
		$schedule_name 		= $_POST['name'];
		$start_date 		= $_POST['start_date'];
		$end_date         	= $_POST['end_date'];
		$is_changed       	= $_POST['is_changed'];
		$schedule 			= $_POST;
		
		if ($hours > 0) {
			$group = new G_Schedule_Template;
			
			$group->setName($schedule_name);
			$group->setScheduleType("Staggered");
			$group->setRequiredWorkingHours($hours);

			if ($group->getId() > 0) {
				$group_id = $group->getId();
				$group->saveStaggered();
			} else {
				$group_id = $group->saveStaggered();
			}
			

			//  UPDATE ATTENDANCE
			if ($is_changed == 'yes') {
				$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
				$start_date = $effectivity_date; //$c->getStartDate();
				if ($c) {
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
			$return['schedule_group_id'] = $group_id;
		} else {
			$return['message'] = 'You have to add at least 1 schedule';
			$return['error_type'] = 'no_entry';
			$return['is_saved'] = false;

			//General Reports / Shr Audit Trail
			//$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, 'Schedule of ', $group->getName(), $effectivity_date, $end_date, 0, '', '');
		}
		$now = date('Y-m-d');
		redirect("new_schedule/index_staggered_schedule?date={$now}");
	}
	//Create Schedule - Compress
	function _create_compress_schedule() {
		$hours             	= $_POST['hours'];
		$schedule_name 		= $_POST['name'];
		$start_date 		= $_POST['start_date'];
		$end_date         	= $_POST['end_date'];
		$is_changed       	= $_POST['is_changed'];
		$schedule 			= $_POST;
		
		if ($hours > 0) {
			$group = new G_Schedule_Template;
			
			$group->setName($schedule_name);
			$group->setScheduleType("Compressed");
			$group->setRequiredWorkingHours($hours);

			if ($group->getId() > 0) {
				$group_id = $group->getId();
				$group->save();
			} else {
				$group_id = $group->save();
			}
			

			//  UPDATE ATTENDANCE
			if ($is_changed == 'yes') {
				$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
				$start_date = $effectivity_date; //$c->getStartDate();
				if ($c) {
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
			$return['schedule_group_id'] = $group_id;
		} else {
			$return['message'] = 'You have to add at least 1 schedule';
			$return['error_type'] = 'no_entry';
			$return['is_saved'] = false;

			//General Reports / Shr Audit Trail
			//$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, 'Schedule of ', $group->getName(), $effectivity_date, $end_date, 0, '', '');
		}
		$now = date('Y-m-d');
		redirect("new_schedule/index_compress_schedule?date={$now}");
	}
	//Create Schedule - Shift
	function _create_shift_schedule() {
		$schedule_name 		= $_POST['name'];
		$is_changed       	= $_POST['is_changed'];
		$schedule 			= $_POST;
		
		if ($_POST['time_in'] != NULL || $_POST['time_out'] != NULL) {
			$group = new G_Schedule_Template;
			
			$group->setName($schedule_name);
			$group->setScheduleType("Shift");
			$group->setScheduleIn(Tools::convert12To24Hour($_POST['time_in']));
			$group->setScheduleOut(Tools::convert12To24Hour($_POST['time_out']));

			if ($group->getId() > 0) {
				$group_id = $group->getId();
				$group->save();
			} else {
				$group_id = $group->save();
			}
			

			//  UPDATE ATTENDANCE
			if ($is_changed == 'yes') {
				$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
				$start_date = $effectivity_date; //$c->getStartDate();
				if ($c) {
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
			$return['schedule_group_id'] = $group_id;
		} else {
			$return['message'] = 'You have to add at least 1 schedule';
			$return['error_type'] = 'no_entry';
			$return['is_saved'] = false;

			//General Reports / Shr Audit Trail
			//$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, 'Schedule of ', $group->getName(), $effectivity_date, $end_date, 0, '', '');
		}
		$now = date('Y-m-d');
		redirect("new_schedule/index_shift_schedule?date={$now}");
	}

	function _add_shift_schedule() {
		$this->_edit_shift_schedule();
	}
	function _edit_shift_schedule(){
		$hours             	= $_POST['hours'];
		$time_in_am			= $_POST['time_in_am'];
		$time_out_am		= $_POST['time_out_am'];
		$time_in_pm			= $_POST['time_in_pm'];
		$time_out_pm		= $_POST['time_out_pm'];
		$is_changed       	= $_POST['is_changed'];
		$schedule 			= $_POST;
		
		if ($schedule['time_in_am'] != 0 || $schedule['time_out_am'] != 0 || $schedule['time_in_pm'] != 0 || $schedule['time_out_pm'] != 0) {
			$name = "Default Shift AM";
			$schedule = G_Schedule_Template_Finder::findAllToUpdate($name);
			
			if (!$schedule) {
				$group = new G_Schedule_Template;
			}
			foreach($schedule as $sched){
				$sched->setRequiredWorkingHours($hours);
				$sched->setScheduleIn(Tools::convert12To24Hour($time_in_am));
				$sched->setScheduleOut(Tools::convert12To24Hour($time_out_am));
				if ($sched->getId() > 0) {
					$group_id = $sched->getId();
					$sched->save();
				} else {
					$group_id = $sched->save();
				}
				$schedule_name = $sched->getName();
			}

			$name = "Default Shift PM";
			$schedule = G_Schedule_Template_Finder::findAllToUpdate($name);
			
			if (!$schedule) {
				$group = new G_Schedule_Template;
			}
			foreach($schedule as $sched){
				$sched->setRequiredWorkingHours($hours);
				$sched->setScheduleIn(Tools::convert12To24Hour($time_in_pm));
				$sched->setScheduleOut(Tools::convert12To24Hour($time_out_pm));
				if ($sched->getId() > 0) {
					$group_id = $sched->getId();
					$sched->save();
				} else {
					$group_id = $sched->save();
				}
				$schedule_name = $sched->getName();
			}
			//important for payroll generation
			//$group = G_Schedule_Template_Finder::findById($group_id);
			//$group->setEndDate($end_date);
			//G_Schedule_Template_Helper::updateEmployeeStartAndEndDate($group, $effectivity_date);
			

			//  UPDATE ATTENDANCE
			if ($is_changed == 'yes') {
				$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
				$start_date = $effectivity_date; //$c->getStartDate();
				if ($c) {
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
			$return['title_string'] = $schedule_name;
			$return['schedule_group_id'] = $group_id;
		} else {
			$return['message'] = 'You have to add at least 1 schedule';
			$return['error_type'] = 'no_entry';
			$return['is_saved'] = false;

			//General Reports / Shr Audit Trail
			//$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, 'Schedule of ', $group->getName(), $effectivity_date, $end_date, 0, '', '');
		}
		$now = date('Y-m-d');
		redirect("new_schedule/index_shift_schedule?date={$now}");
	}
	/*old staggered schedule
	function _edit_staggered_schedule()
	{
		$name             	= $_POST['name'];
		$public_id        	= $_POST['id'];
		$start_date 		= $_POST['start_date'];
		$end_date         	= $_POST['end_date'];
		$is_changed       	= $_POST['is_changed'];
		$schedule 			= $_POST;
		
		if (count($schedule['day_hours']) > 0) {
			$group = G_Schedule_Template_Finder::findByPublicId($public_id);
			
			if (!$group) {
				$group = new G_Staggered_Schedule;
			}

			$group->setStartDate($start_date);
			$group->setEndDate($end_date);
			$group->setName($name);

			
			//important for payroll generation
			//$group = G_Schedule_Template_Finder::findById($group_id);
			//$group->setEndDate($end_date);
			//G_Staggered_Schedule_Group_Helper::updateEmployeeStartAndEndDate($group, $effectivity_date);
			
			$i = 1;
			$working_days_all = array();
			$hours_all = array();
			foreach ($schedule['day_hours'] as $days => $hours) {
				if($hours){
					$working_days_all[]= $days;
					$hours_all[] = $hours;
				}
				
			}
			$days = implode(',', $working_days_all);
			$hours = implode(',', $hours_all);

			$group->setName($name);
			$group->setWorkingDays($days);
			$group->setHours($hours);
			
			if ($group->getId() > 0) {
				$group_id = $group->getId();
				$group->save();
			} else {
				$group_id = $group->save();
			}
			
			$all_schedules = G_Schedule_Template_Finder::findAllByScheduleGroup($group);
			foreach ($all_schedules as $all_schedule) {
				//$schedule_string .=  '<div>'. Tools::timeFormat($all_schedule->getTimeIn()) .' - '. Tools::timeFormat($all_schedule->getTimeOut()) .' - '. $all_schedule->getWorkingDays().' </div>';
				$schedule_string .= '<li><div class="item-detail-styled">
                  <i class="icon-time icon-fade vertical-middle"></i>
                  <strong>' . $all_schedule->getWorkingDays() . '</strong>
                  (' . $all_schedule->getHours() . ')
                </div></li>';
			}

			//  UPDATE ATTENDANCE
			if ($is_changed == 'yes') {
				$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
				$start_date = $effectivity_date; //$c->getStartDate();
				if ($c) {
					$end_date = $c->getEndDate();
					$employees = G_Employee_Finder::findByScheduleGroup($group);
					$dates = Tools::getBetweenDates($start_date, $end_date);
					foreach ($employees as $e) {
						if ($e) {
							foreach ($dates as $date) {
								$attendances[] = G_Attendance_Helper::generateAttendance($e, $date);
							}
							// G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
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
			//$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, 'Schedule of ', $group->getName(), $effectivity_date, $end_date, 0, '', '');
		}
		echo json_encode($return);
	}*/

	function ajax_batch_edit_site_attendance_logs() {
		$logs = $_POST['logs'];
		$employee_logs = array();
		
		$this->var['token'] = Utilities::createFormToken();
		if($logs){
			foreach ($logs as $key => $log) {
				
				$at = G_Employee_Attendance_Finder_V2::findById($log['id']);
				if($at){
					$employee_logs[] = $at;
	
				}
			}
	
			$this->var['employee_logs'] = $employee_logs;
			$this->var['schedule'] = G_Schedule_Template_Finder::findAll();
			$this->view->render('new_schedule/ajax_batch_edit_schedule.php', $this->var);
		}
	}
	function ajax_batch_edit_no_schedule() {
		$logs = $_POST['logs'];

		$employee_logs = array();

		$this->var['token'] = Utilities::createFormToken();

		if($logs){
			foreach ($logs as $key => $log) {
				
				$at = G_Employee_Finder::findById($log['id']);
	
				if($at){
					$employee_logs[] = $at;
	
				}
			}
			$this->var['employee_logs'] = $employee_logs;
			$this->var['schedule'] = G_Schedule_Template_Finder::findAll();
			$this->view->render('new_schedule/ajax_batch_edit_no_schedule.php', $this->var);
		}
	}

	function ajax_edit_schedule() {
		$id = Utilities::decrypt($_GET['id']);
		if($id){
			$schedule_template = G_Schedule_Template_Finder::findById($id);

			if($schedule_template){
				$this->var['token'] = Utilities::createFormToken();
				$this->var['schedule_template']    	= $schedule_template;

				$this->view->render('new_schedule/ajax_edit_schedule.php', $this->var);
			}else{
			
			}
		}else{
			
		}
	}
	function ajax_edit_employee_schedule() {
		$id_in = Utilities::decrypt($_GET['eid_in']);

		if($id_in){
			$at_time_in = G_Employee_Attendance_Finder_V2::findById($id_in);

			if($at_time_in){
				$this->var['token'] = Utilities::createFormToken();
				$this->var['at_time_in']    	= $at_time_in;
				
				$this->var['schedule'] = G_Schedule_Template_Finder::findAll();
				$this->var['devices'] = G_Attendance_Log_Finder_V2::getDevices();
				$this->view->render('new_schedule/ajax_edit_employee_schedule.php', $this->var);
			}else{
			
			}
		}else{
			
		}
	}
	function ajax_edit_no_schedule() {
		$id_in = Utilities::decrypt($_GET['eid_in']);
		
		if($id_in){
			$at_time_in = G_Employee_Finder::findById($id_in);

			if($at_time_in){
				$this->var['token'] = Utilities::createFormToken();
				$this->var['at_time_in']    	= $at_time_in;
				
				$this->var['schedule'] = G_Schedule_Template_Finder::findAll();
				$this->var['devices'] = G_Attendance_Log_Finder_V2::getDevices();
				$this->view->render('new_schedule/ajax_edit_no_schedule.php', $this->var);
			}else{
			
			}
		}else{
			
		}
		
	}

	function ajax_get_employees_autocomplete(){
		$q = Model::safeSql(strtolower($_GET["search"]), false);

		if ($q != '') {
			$employees = G_Employee_Finder::searchByFirstnameAndLastname($q);

			foreach ($employees as $e) {
				$response[] = array($e->getId(), $e->getFullname(), null);
			}
		}

		if (count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);
	}	

	function ajax_show_schedule_list() {
		$s = G_Schedule_Finder::findAll();
		$this->var['schedules'] = G_Schedule_Helper::mergeByName($s);
		$this->view->noTemplate();
		$this->view->render('new_schedule/ajax_schedule_list.php',$this->var);
	}
	
	//old stagger schedule
	/*
	function ajax_show_staggered_schedule_list() {
	    $month = $_SESSION['show_schedule_month'];
        $year = $_SESSION['show_schedule_year'];
        $this->var['schedule_groups'] 		= G_Schedule_Template_Finder::findAllByMonthAndYearWithDefault($month, $year);
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
   
   		$this->view->noTemplate();
		$this->view->render('new_schedule/ajax_staggered_schedule_list.php',$this->var);
	}*/
	//new stagger schedule
	function ajax_show_staggered_schedule_list() {
	    $month = $_SESSION['show_schedule_month'];
        $year = $_SESSION['show_schedule_year'];
        $this->var['schedule_groups'] 		= G_Schedule_Template_Finder::findAllByMonthAndYearWithDefault($month, $year);
    	$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
   
   		$this->view->noTemplate();
		$this->view->render('new_schedule/ajax_staggered_schedule_list.php',$this->var);
	}

	function ajax_show_staggered_schedule_members_list() {
		$id = $_GET['schedule_id'];
		$g = G_Schedule_Template_Finder::findByPublicId($id);
		$this->var['schedule_id'] = $id;//$g->getId();
		$this->var['employees'] = G_Employee_Finder::findByStaggeredScheduleGroup($g);
		//$this->var['groups'] = G_Group_Finder::findByScheduleGroup($g);
		
		/*$btn_add_department_config = array(
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
		); */

		$btn_add_employees_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'schedule',
    		'child_index'			=> '',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:assignStaggeredScheduleEmployees("'.$id.'");',
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
    		'onclick' 				=> 'javascript:removeAllStaggeredScheduleMemberEmployees("'.$id.'");',
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
		$this->view->render('new_schedule/ajax_staggered_schedule_members_list.php',$this->var);
	}

	//employee
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
		$this->view->render('new_schedule/ajax_schedule_members_list.php',$this->var);
	}
	//dashboard ajax show schedule members
	//shift
	function dashboard_ajax_show_shift_schedule_members_list() {
		//$this->var['employees'] = G_Employee_Finder::findAllEmployeeByScheduleGroup();

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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/dashboard_ajax_shift_schedule_members_list.php',$this->var);
	}
	function dashboard_ajax_show_shift_leave_schedule_members_list(){
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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/dashboard_ajax_shift_leave_schedule_members_list.php',$this->var);
	}
	function dashboard_ajax_show_shift_ob_schedule_members_list(){
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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/dashboard_ajax_shift_ob_schedule_members_list.php',$this->var);
	}
	function dashboard_ajax_show_shift_no_schedule_members_list(){
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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/dashboard_ajax_shift_no_schedule_members_list.php',$this->var);
	}
	//compress
	function dashboard_ajax_show_compress_schedule_members_list() {
		//$this->var['employees'] = G_Employee_Finder::findAllEmployeeByScheduleGroup();

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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/dashboard_ajax_compress_schedule_members_list.php',$this->var);
	}
	function dashboard_ajax_show_compress_leave_schedule_members_list(){
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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/dashboard_ajax_compress_leave_schedule_members_list.php',$this->var);
	}
	function dashboard_ajax_show_compress_ob_schedule_members_list(){
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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/dashboard_ajax_compress_ob_schedule_members_list.php',$this->var);
	}
	function dashboard_ajax_show_compress_no_schedule_members_list(){
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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/dashboard_ajax_compress_no_schedule_members_list.php',$this->var);
	}
	//staggered
	function dashboard_ajax_show_staggered_schedule_members_list() {
		//$this->var['employees'] = G_Employee_Finder::findAllEmployeeByScheduleGroup();

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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/dashboard_ajax_staggered_schedule_members_list.php',$this->var);
	}
	function dashboard_ajax_show_staggered_leave_schedule_members_list(){
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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/dashboard_ajax_staggered_leave_schedule_members_list.php',$this->var);
	}
	function dashboard_ajax_show_staggered_ob_schedule_members_list(){
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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/dashboard_ajax_staggered_ob_schedule_members_list.php',$this->var);
	}
	function dashboard_ajax_show_staggered_no_schedule_members_list(){
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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/dashboard_ajax_staggered_no_schedule_members_list.php',$this->var);
	}
	//flextime
	function dashboard_ajax_show_flextime_schedule_members_list() {
		//$this->var['employees'] = G_Employee_Finder::findAllEmployeeByScheduleGroup();

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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/dashboard_ajax_flextime_schedule_members_list.php',$this->var);
	}
	function dashboard_ajax_show_flextime_leave_schedule_members_list() {
		//$this->var['employees'] = G_Employee_Finder::findAllEmployeeByScheduleGroup();

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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/dashboard_ajax_flextime_leave_schedule_members_list.php',$this->var);
	}
	function dashboard_ajax_show_flextime_ob_schedule_members_list() {
		//$this->var['employees'] = G_Employee_Finder::findAllEmployeeByScheduleGroup();

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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/dashboard_ajax_flextime_ob_schedule_members_list.php',$this->var);
	}
	function dashboard_ajax_show_flextime_no_schedule_members_list(){
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
    	$this->var['btn_remove_all_employees'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_remove_all_employees_config);
    	
		$this->view->noTemplate();
		$this->view->render('new_schedule/dashboard_ajax_flextime_no_schedule_members_list.php',$this->var);
	}

	//Load Dashboard
	//Shift
	function load_dashboard_shift_schedule_dt(){
		utilities::displayArray($_POST);
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);

		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		$name = "shift am"; //Default working days for both shift AM and shift PM
		$schedule = G_Schedule_Template_Finder::findDefault($name);
		$working_days = array();
		foreach($schedule as $sched){
			$working_days = $sched->getWorkingDays();
		}
		$work_days = explode(",", $working_days);
		$working_days = $work_days;
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$schedule = G_Employee_Schedule_Type_Finder::findAllByEmployeeId($d_in->getEmployeeId());
			if($schedule->getScheduleId() == 3){
				$employee_with_schedule = "Shift AM";
				$name = "shift am";
			}else if($schedule->getScheduleId() == 4){
				$employee_with_schedule = "Shift PM";
				$name = "shift pm";
			}
			$schedule_name = "shift schedule";
			$schedule_type = G_Schedule_Type_Finder::findByName($schedule_name);

			$id = G_Employee_Schedule_Type_Finder::findSchedule($d_in->getEmployeeId(), $schedule_type);
			$schedule_date = G_Schedule_Template_Finder::findByDate($_SESSION['date'], $name);
			foreach($id as $sched){
				$emp_sched = $sched->employee_id;
			}
			$date = $_SESSION['date'];
    		$newDate = date('l', strtotime($date));
			$day_shorten = strtolower(substr($newDate, 0, 3));
			
			foreach ($working_days as $work_days) {
				if ($work_days == $day_shorten) {
					$checked = 1;
					break;
				}else{
					$checked = 0;
				}
			}
			if($d_in->getEmployeeId() == $emp_sched && $schedule_date != NULL && $checked == 1){
				$remarks = "";
				//$remarks = $d_in->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];
	
				$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);
	
				if ($device_info) {
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
	
				$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());
				
				$json['table'] .= '<tr>';
				$json['table'] .= '<td>';
				$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employee_with_schedule;
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= Tools::timeFormat($d_in->getTimeIn());
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= Tools::timeFormat($d_in->getTimeOut());
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
				$json['table'] .= '</td>';
			
				$json['table'] .= '<td>';
				$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
				$json['table'] .= $department->title;
				$json['table'] .= '</td>';

				/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/
	
				/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/
	
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= '<td>';
					$json['table'] .= "	<a class=\"btn btn-small\" href=\"javascript:void(0);\" id=\"staggered\" onclick=\"editEmployeeSchedule('" . Utilities::encrypt($d_in->getId()) . "');\">
											<i class=\"icon-pencil icon-fade\"></i> Edit
										</a>
										<!--<a class=\"btn btn-small\" href=\"javascript:void(0);\" onclick=\"deleteDTRLogTimeInTimeOut('" . Utilities::encrypt($d_in->getId()) . "');\">
											<i class=\"icon-trash icon-fade\"></i> Delete
										</a>-->";
					$json['table'] .= '</td>';
				}
	
				$json['table'] .= '</tr>';
			}
			
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }

		//Construct Paginator			
		if ($_POST['paginatorIndex']) {
			$paginator_index = $_POST['paginatorIndex'];
		} else {
			$paginator_index = 1;
		}

		if ($count > 0) {
			$json['paginator'] .= '<div id="yui-dt0-paginator0" class="yui-dt-paginator yui-pg-container">';
			$skip_counting = 0;

			//Add First BTN
			//Validate if first record for first record
			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first">First</span>';
			} else {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',1)">First</a></span>';
			}
			//

			//Add Prev Btn
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting - $_POST['limit'];
				}
				$skip_counting += $_POST['limit'];
			}

			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous">Previous</span>';
			} else {
				$prev_record = $paginator_index - 1;
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $prev_record . ');">Previous</a></span>';
			}
			//

			//Construct Paginator index
			$skip_counting = 0;
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting + $_POST['limit'];
					//$json['paginator'] .= '<li class="paginator_selected">' . $i . '</li>';		
				} else {
					//$json['paginator'] .= '<li><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $i . ');">' . $i . '</a></li>';		
				}

				$skip_counting += $_POST['limit'];
			}
			//

			//Add Next Btn
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next">Next</span>';
			} else {
				$next_record = $paginator_index + 1;
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $next_record . ');">Next</a></span>';
			}
			//


			//Add Last BTN
			//Validate if last record
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last">Last</span>';
			} else {
				$skip_counting -= $_POST['limit'];
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $count . ')">Last</a></span>';
			}
			//
			//
			$json['paginator'] .= '</div>';
		}
		//
		echo json_encode($json);
	}
	function load_dashboard_shift_leave_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_start desc, date_applied desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		if (count($employee_ids) > 0) {
			foreach ($employee_ids as $key => $field)
			$employee_ids[$key] = Utilities::decrypt($field);
		}
		
		$log_ids = array();
		$v2_employee_leave = G_Employee_Leave_Request_Finder::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		
		//Utilities::displayArray($v2_employee_leave);
		//Construct table content
		foreach($v2_employee_leave as $d_in){
			$schedule = G_Employee_Schedule_Type_Finder::findAllByEmployeeId($d_in->getEmployeeId());
			if($schedule->getScheduleId() == 3){
				$employee_with_schedule = "Shift AM";
			}else if($schedule->getScheduleId() == 4){
				$employee_with_schedule = "Shift PM";
			}

			$schedule_name = "shift schedule";
			$schedule_type = G_Schedule_Type_Finder::findByName($schedule_name);
			$id = G_Employee_Schedule_Type_Finder::findSchedule($d_in->getEmployeeId(), $schedule_type);
			foreach($id as $sched){
				$employee_sched = $sched->employee_id;
			}
			if($d_in->getEmployeeId() == $employee_sched){
				$remarks = "";
				//$remarks = $d_in->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];
	
				$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);
	
				if ($device_info) {
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
				
				$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());
				
				$json['table'] .= '<tr>';
				$json['table'] .= '<td>';
				$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employee_with_schedule;
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->employee_code;
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
				$json['table'] .= '</td>';
	
			
				$json['table'] .= '<td>';
				$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
				$json['table'] .= $department->title;
				$json['table'] .= '</td>';

				/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/
	
				/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/
				
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= '<td>';
					if($d_in->is_approved == "Pending"){
						$json['table'] .= "Pending <a title=\"Approve\" id=\"edit\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveLeaveRequest('" . Utilities::encrypt($d_in->id) . "');\"></a>";
					}else{
						$json['table'] .= "Approved";
					}
					$json['table'] .= '</td>';
				}
	
				$json['table'] .= '</tr>';
			}
			
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }

		//Construct Paginator			
		if ($_POST['paginatorIndex']) {
			$paginator_index = $_POST['paginatorIndex'];
		} else {
			$paginator_index = 1;
		}

		if ($count > 0) {
			$json['paginator'] .= '<div id="yui-dt0-paginator0" class="yui-dt-paginator yui-pg-container">';
			$skip_counting = 0;

			//Add First BTN
			//Validate if first record for first record
			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first">First</span>';
			} else {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',1)">First</a></span>';
			}
			//

			//Add Prev Btn
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting - $_POST['limit'];
				}
				$skip_counting += $_POST['limit'];
			}

			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous">Previous</span>';
			} else {
				$prev_record = $paginator_index - 1;
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $prev_record . ');">Previous</a></span>';
			}
			//

			//Construct Paginator index
			$skip_counting = 0;
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting + $_POST['limit'];
					//$json['paginator'] .= '<li class="paginator_selected">' . $i . '</li>';		
				} else {
					//$json['paginator'] .= '<li><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $i . ');">' . $i . '</a></li>';		
				}

				$skip_counting += $_POST['limit'];
			}
			//

			//Add Next Btn
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next">Next</span>';
			} else {
				$next_record = $paginator_index + 1;
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $next_record . ');">Next</a></span>';
			}
			//


			//Add Last BTN
			//Validate if last record
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last">Last</span>';
			} else {
				$skip_counting -= $_POST['limit'];
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $count . ')">Last</a></span>';
			}
			//
			//
			$json['paginator'] .= '</div>';
		}
		//
		echo json_encode($json);
	}
	function load_dashboard_shift_ob_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_start desc, date_applied desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		$v2_employee_ob = G_Employee_Official_Business_Request_Finder::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);

		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		
		//Utilities::displayArray($v2_employee_leave);
		//Construct table content
		foreach($v2_employee_ob as $d_in){
			$schedule = G_Employee_Schedule_Type_Finder::findAllByEmployeeId($d_in->getEmployeeId());
			if($schedule->getScheduleId() == 3){
				$employee_with_schedule = "Shift AM";
			}else if($schedule->getScheduleId() == 4){
				$employee_with_schedule = "Shift PM";
			}

			$schedule_name = "shift schedule";
			$schedule_type = G_Schedule_Type_Finder::findByName($schedule_name);
			$id = G_Employee_Schedule_Type_Finder::findSchedule($d_in->getEmployeeId(), $schedule_type);
			foreach($id as $sched){
				$employee_sched = $sched->employee_id;
			}
			if($d_in->getEmployeeId() == $employee_sched){
				$remarks = "";
				//$remarks = $d_in->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];
	
				$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);
	
				if ($device_info) {
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
				
				$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());
				
				$json['table'] .= '<tr>';
				$json['table'] .= '<td>';
				$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employee_with_schedule;
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->employee_code;
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
				$json['table'] .= '</td>';
			
				$json['table'] .= '<td>';
				$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
				$json['table'] .= $department->title;
				$json['table'] .= '</td>';

				/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/
	
				/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/
	
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= '<td>';
					if($d_in->is_approved == "Pending"){
						$json['table'] .= "Pending <a title=\"Approve\" id=\"edit\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveLeaveRequest('" . Utilities::encrypt($d_in->id) . "');\"></a>";
					}else if($d_in->is_approved == "Approved"){
						$json['table'] .= "Approved";
					}
					$json['table'] .= '</td>';
				}
	
				$json['table'] .= '</tr>';
			}
			
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }

		//Construct Paginator			
		if ($_POST['paginatorIndex']) {
			$paginator_index = $_POST['paginatorIndex'];
		} else {
			$paginator_index = 1;
		}

		if ($count > 0) {
			$json['paginator'] .= '<div id="yui-dt0-paginator0" class="yui-dt-paginator yui-pg-container">';
			$skip_counting = 0;

			//Add First BTN
			//Validate if first record for first record
			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first">First</span>';
			} else {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',1)">First</a></span>';
			}
			//

			//Add Prev Btn
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting - $_POST['limit'];
				}
				$skip_counting += $_POST['limit'];
			}

			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous">Previous</span>';
			} else {
				$prev_record = $paginator_index - 1;
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $prev_record . ');">Previous</a></span>';
			}
			//

			//Construct Paginator index
			$skip_counting = 0;
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting + $_POST['limit'];
					//$json['paginator'] .= '<li class="paginator_selected">' . $i . '</li>';		
				} else {
					//$json['paginator'] .= '<li><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $i . ');">' . $i . '</a></li>';		
				}

				$skip_counting += $_POST['limit'];
			}
			//

			//Add Next Btn
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next">Next</span>';
			} else {
				$next_record = $paginator_index + 1;
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $next_record . ');">Next</a></span>';
			}
			//


			//Add Last BTN
			//Validate if last record
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last">Last</span>';
			} else {
				$skip_counting -= $_POST['limit'];
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $count . ')">Last</a></span>';
			}
			//
			//
			$json['paginator'] .= '</div>';
		}
		//
		echo json_encode($json);
	}
	function load_dashboard_shift_no_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		if (count($employee_ids) > 0) {
			foreach ($employee_ids as $key => $field)
			$employee_ids[$key] = Utilities::decrypt($field);
		}
		$log_ids = array();
		$v2_attendance = G_Attendance_Log_Finder_V2::findV2AttendanceByPeriod($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		$data_time_in  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogsTimeIn($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		$data_time_out  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogsTimeOut($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);

		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
	
		$schedule_groups = G_Schedule_Template_Finder::findAll();
		$g = G_Employee_Schedule_Type_Finder::findAll();
		$employees_schedule = $g;

		$employee_with_schedule = array();
		foreach ($g as $emp_sched) {
		  $is_employee_id_exist = 0;
		  foreach ($employee_with_schedule as $e_array) {
			if ($e_array == $emp_sched->employee_id) {
			  $is_employee_id_exist = 1;
			} else {
			  continue;
			}
		  }
		  if ($is_employee_id_exist == 1) {
			continue;
		  } else {
			$employee_with_schedule[] = $emp_sched->employee_id;
		  }
		}
		$employee = G_Employee_Finder::findAll();
		foreach($employee as $emp){
			$is_employee_have_sched = 0;
			foreach($employee_with_schedule as $emp_with_sched){
				if($emp_with_sched == $emp->getId()){
					$is_employee_have_sched = 1;
				}
			}
			if($is_employee_have_sched == 1){

			}else{
				$remarks = "";
				//$remarks = $d_in->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];
	
				$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);
	
				if ($device_info) {
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
	
				$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($emp->getId());
				
				$json['table'] .= '<tr>';
				$json['table'] .= '<td>';
				$json['table'] .= '<input type="checkbox" name="dtrChkNoSchedule[]" onchange="javascript:checkUncheckNoSchedule();" value="' . $emp->getId() . '" style="margin-left: 5px;">';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'No Schedule';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'N/A';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'N/A';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
				$json['table'] .= $department->title;
				$json['table'] .= '</td>';
	
				/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($emp->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/
	
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= '<td>';
					$json['table'] .= "	<a class=\"btn btn-small\" href=\"javascript:void(0);\" 
											onclick=\"editEmployeeNoSchedule('" . Utilities::encrypt($emp->getId()) . "');\">
											<i class=\"icon-pencil icon-fade\"></i> Edit
										</a>";
					$json['table'] .= '</td>';
				}
	
				$json['table'] .= '</tr>';
			}
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}
	//Compress
	function load_dashboard_compress_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);

		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		$name = "default compress";
		$schedule_staggered = G_Schedule_Template_Finder::findDefault($name);
		$working_days = array();
		foreach($schedule_staggered as $staggered){
			$working_days = $staggered->getWorkingDays();
		}
		$work_days = explode(",", $working_days);
		$working_days = $work_days;
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$name = "compress schedule";
			$schedule_type = G_Schedule_Type_Finder::findByName($name);
			$id = G_Employee_Schedule_Type_Finder::findSchedule($d_in->getEmployeeId(), $schedule_type);
			$schedule_date = G_Schedule_Template_Finder::findByDate($_SESSION['date'], $name);
			foreach($id as $sched){
				$emp_sched = $sched->employee_id;
			}
			$date = $_SESSION['date'];
    		$newDate = date('l', strtotime($date));
			$day_shorten = strtolower(substr($newDate, 0, 3));
			
			foreach ($working_days as $work_days) {
				if ($work_days == $day_shorten) {
					$checked = 1;
					break;
				}else{
					$checked = 0;
				}
			}

			if($d_in->getEmployeeId() == $emp_sched && $schedule_date != NULL && $checked == 1){
				$remarks = "";
				//$remarks = $d_in->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];
	
				$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);
	
				if ($device_info) {
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
	
				$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());
				$schedule = G_Employee_Schedule_Type_Finder::findAllByEmployeeId($d_in->getEmployeeId());
				$sched = G_Schedule_Type_Finder::findById($schedule->getScheduleTypeId());
				if($sched->getName() == 'compress schedule'){
					$employee_with_schedule = "Compress";
				}
				
				$json1['table'] .= '<tr>';
				$json1['table'] .= '<td>';
				$json1['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
				$json1['table'] .= '</td>';
	
				$json1['table'] .= '<td>';
				$json1['table'] .= $employee_with_schedule;
				$json1['table'] .= '</td>';
	
				$json1['table'] .= '<td>';
				$json1['table'] .= Tools::timeFormat($d_in->getTimeIn());
				$json1['table'] .= '</td>';
	
				$json1['table'] .= '<td>';
				$json1['table'] .= Tools::timeFormat($d_in->getTimeOut());
				$json1['table'] .= '</td>';
	
				$json1['table'] .= '<td>';
				$json1['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
				$json1['table'] .= '</td>';
			
				$json1['table'] .= '<td>';
				$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
				$json1['table'] .= $department->title;
				$json1['table'] .= '</td>';

				/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/
	
				/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/
	
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json1['table'] .= '<td>';
					$json1['table'] .= "	<a class=\"btn btn-small\" href=\"javascript:void(0);\" id=\"staggered\" onclick=\"editEmployeeSchedule('" . Utilities::encrypt($d_in->getId()) . "');\">
											<i class=\"icon-pencil icon-fade\"></i> Edit
										</a>
										<!--<a class=\"btn btn-small\" href=\"javascript:void(0);\" onclick=\"deleteDTRLogTimeInTimeOut('" . Utilities::encrypt($d_in->getId()) . "');\">
											<i class=\"icon-trash icon-fade\"></i> Delete
										</a>-->";
					$json1['table'] .= '</td>';
				}
	
				$json1['table'] .= '</tr>';
			}
			
		}
		if ($json1 == null) {
			$json1['table'] .= '<tr>';
			
			$json1['table'] .= '<td>';
			$json1['table'] .= '</td>';
			$json1['table'] .= '<td>';
			$json1['table'] .= '</td>';
			$json1['table'] .= '<td>';
			$json1['table'] .= '</td>';
			$json1['table'] .= '<td>';
			$json1['table'] .= '<i>- no data found - </i>';
			$json1['table'] .= '</td>';
            $json1['table'] .= '<td>';
			$json1['table'] .= '</td>';
			$json1['table'] .= '<td>';
			$json1['table'] .= '</td>';
			$json1['table'] .= '<td>';
			$json1['table'] .= '</td>';

			$json1['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json1);
		echo json_encode($json1);
	}
	function load_dashboard_compress_leave_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_start desc, date_applied desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		if (count($employee_ids) > 0) {
			foreach ($employee_ids as $key => $field)
			$employee_ids[$key] = Utilities::decrypt($field);
		}
		
		$log_ids = array();
		$v2_employee_leave = G_Employee_Leave_Request_Finder::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		
		//Utilities::displayArray($v2_employee_leave);
		//Construct table content
		foreach($v2_employee_leave as $d_in){
			$name = "compress schedule";
			$schedule_type = G_Schedule_Type_Finder::findByName($name);
			$id = G_Employee_Schedule_Type_Finder::findSchedule($d_in->getEmployeeId(), $schedule_type);
			foreach($id as $sched){
				$employee_sched = $sched->employee_id;
			}
			if($d_in->getEmployeeId() == $employee_sched){
				$remarks = "";
				//$remarks = $d_in->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];
	
				$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);
	
				if ($device_info) {
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
				
				$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());
				$schedule = G_Employee_Schedule_Type_Finder::findAllByEmployeeId($d_in->getEmployeeId());
				$sched = G_Schedule_Type_Finder::findById($schedule->getScheduleTypeId());
				if($sched->getName() == 'compress schedule'){
					$employee_with_schedule = "Compress";
				}
				
				$json['table'] .= '<tr>';
				$json['table'] .= '<td>';
				$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employee_with_schedule;
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->employee_code;
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
				$json['table'] .= '</td>';
	
			
				$json['table'] .= '<td>';
				$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
				$json['table'] .= $department->title;
				$json['table'] .= '</td>';

				/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/
	
				/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/
				
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= '<td>';
					if($d_in->is_approved == "Pending"){
						$json['table'] .= "Pending <a title=\"Approve\" id=\"edit\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveLeaveRequest('" . Utilities::encrypt($d_in->id) . "');\"></a>";
					}else{
						$json['table'] .= "Approved";
					}
					$json['table'] .= '</td>';
				}
	
				$json['table'] .= '</tr>';
			}
			
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}
	function load_dashboard_compress_ob_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_start desc, date_applied desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		$v2_employee_ob = G_Employee_Official_Business_Request_Finder::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);

		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		
		//Utilities::displayArray($v2_employee_leave);
		//Construct table content
		foreach($v2_employee_ob as $d_in){
			$name = "compress schedule";
			$schedule_type = G_Schedule_Type_Finder::findByName($name);
			$id = G_Employee_Schedule_Type_Finder::findSchedule($d_in->getEmployeeId(), $schedule_type);
			foreach($id as $sched){
				$employee_sched = $sched->employee_id;
			}
			if($d_in->getEmployeeId() == $employee_sched){
				$remarks = "";
				//$remarks = $d_in->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];
	
				$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);
	
				if ($device_info) {
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
				
				$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());
				$schedule = G_Employee_Schedule_Type_Finder::findAllByEmployeeId($d_in->getEmployeeId());
				$sched = G_Schedule_Type_Finder::findById($schedule->getScheduleTypeId());
				if($sched->getName() == 'compress schedule'){
					$employee_with_schedule = "Staggered";
				}
				
				$json['table'] .= '<tr>';
				$json['table'] .= '<td>';
				$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employee_with_schedule;
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->employee_code;
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
				$json['table'] .= '</td>';
			
				$json['table'] .= '<td>';
				$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
				$json['table'] .= $department->title;
				$json['table'] .= '</td>';

				/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/
	
				/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/
	
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= '<td>';
					if($d_in->is_approved == "Pending"){
						$json['table'] .= "<a title=\"Approve\" id=\"edit\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveLeaveRequest('" . Utilities::encrypt($d_in->id) . "');\"></a>";
					}
					$json['table'] .= '</td>';
				}
	
				$json['table'] .= '</tr>';
			}
			
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }

		//Construct Paginator			
		if ($_POST['paginatorIndex']) {
			$paginator_index = $_POST['paginatorIndex'];
		} else {
			$paginator_index = 1;
		}

		if ($count > 0) {
			$json['paginator'] .= '<div id="yui-dt0-paginator0" class="yui-dt-paginator yui-pg-container">';
			$skip_counting = 0;

			//Add First BTN
			//Validate if first record for first record
			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first">First</span>';
			} else {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',1)">First</a></span>';
			}
			//

			//Add Prev Btn
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting - $_POST['limit'];
				}
				$skip_counting += $_POST['limit'];
			}

			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous">Previous</span>';
			} else {
				$prev_record = $paginator_index - 1;
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $prev_record . ');">Previous</a></span>';
			}
			//

			//Construct Paginator index
			$skip_counting = 0;
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting + $_POST['limit'];
					//$json['paginator'] .= '<li class="paginator_selected">' . $i . '</li>';		
				} else {
					//$json['paginator'] .= '<li><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $i . ');">' . $i . '</a></li>';		
				}

				$skip_counting += $_POST['limit'];
			}
			//

			//Add Next Btn
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next">Next</span>';
			} else {
				$next_record = $paginator_index + 1;
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $next_record . ');">Next</a></span>';
			}
			//


			//Add Last BTN
			//Validate if last record
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last">Last</span>';
			} else {
				$skip_counting -= $_POST['limit'];
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $count . ')">Last</a></span>';
			}
			//
			//
			$json['paginator'] .= '</div>';
		}
		//
		echo json_encode($json);
	}
	function load_dashboard_compress_no_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		if (count($employee_ids) > 0) {
			foreach ($employee_ids as $key => $field)
			$employee_ids[$key] = Utilities::decrypt($field);
		}
		$log_ids = array();
		$v2_attendance = G_Attendance_Log_Finder_V2::findV2AttendanceByPeriod($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		$data_time_in  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogsTimeIn($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		$data_time_out  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogsTimeOut($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);

		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
	
		$schedule_groups = G_Schedule_Template_Finder::findAll();
		$g = G_Employee_Schedule_Type_Finder::findAll();
		$employees_schedule = $g;

		$employee_with_schedule = array();
		foreach ($g as $emp_sched) {
		  $is_employee_id_exist = 0;
		  foreach ($employee_with_schedule as $e_array) {
			if ($e_array == $emp_sched->employee_id) {
			  $is_employee_id_exist = 1;
			} else {
			  continue;
			}
		  }
		  if ($is_employee_id_exist == 1) {
			continue;
		  } else {
			$employee_with_schedule[] = $emp_sched->employee_id;
		  }
		}
		$employee = G_Employee_Finder::findAll();
		foreach($employee as $emp){
			$is_employee_have_sched = 0;
			foreach($employee_with_schedule as $emp_with_sched){
				if($emp_with_sched == $emp->getId()){
					$is_employee_have_sched = 1;
				}
			}
			if($is_employee_have_sched == 1){

			}else{
				$remarks = "";
				//$remarks = $d_in->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];
	
				$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);
	
				if ($device_info) {
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
	
				$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($emp->getId());
				
				$json['table'] .= '<tr>';
				$json['table'] .= '<td>';
				$json['table'] .= '<input type="checkbox" name="dtrChkNoSchedule[]" onchange="javascript:checkUncheckNoSchedule();" value="' . $emp->getId() . '" style="margin-left: 5px;">';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'No Schedule';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'N/A';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'N/A';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
				$json['table'] .= $department->title;
				$json['table'] .= '</td>';
	
				/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($emp->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/
	
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= '<td>';
					$json['table'] .= "	<a class=\"btn btn-small\" href=\"javascript:void(0);\" 
											onclick=\"editEmployeeNoSchedule('" . Utilities::encrypt($emp->getId()) . "');\">
											<i class=\"icon-pencil icon-fade\"></i> Edit
										</a>";
					$json['table'] .= '</td>';
				}
	
				$json['table'] .= '</tr>';
			}
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}
	//Staggered
	function load_dashboard_staggered_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		
		$log_ids = array();
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);

		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		$name = "default staggered";
		$schedule_staggered = G_Schedule_Template_Finder::findDefault($name);
		$working_days = array();
		foreach($schedule_staggered as $staggered){
			$working_days = $staggered->getWorkingDays();
		}
		$work_days = explode(",", $working_days);
		$working_days = $work_days;
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
		foreach($v2_employee_attendance as $d_in){
			$name = "staggered schedule";
			$schedule_type = G_Schedule_Type_Finder::findByName($name);
			$id = G_Employee_Schedule_Type_Finder::findSchedule($d_in->getEmployeeId(), $schedule_type);
			$schedule_date = G_Schedule_Template_Finder::findByDate($_SESSION['date'], $name);
			foreach($id as $sched){
				$emp_sched = $sched->employee_id;
			}
			$date = $_SESSION['date'];
    		$newDate = date('l', strtotime($date));
			$day_shorten = strtolower(substr($newDate, 0, 3));
			
			foreach ($working_days as $work_days) {
				if ($work_days == $day_shorten) {
					$checked = 1;
					break;
				}else{
					$checked = 0;
				}
			}

			if($d_in->getEmployeeId() == $emp_sched && $schedule_date != NULL && $checked == 1){
				$remarks = "";
				//$remarks = $d_in->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];
	
				$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);
	
				if ($device_info) {
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
	
				$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());
				$schedule = G_Employee_Schedule_Type_Finder::findAllByEmployeeId($d_in->getEmployeeId());
				$sched = G_Schedule_Type_Finder::findById($schedule->getScheduleTypeId());
				if($sched->getName() == 'staggered schedule'){
					$employee_with_schedule = "Staggered";
				}
				
				$json['table'] .= '<tr>';
				$json['table'] .= '<td>';
				$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employee_with_schedule;
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= Tools::timeFormat($d_in->getTimeIn());
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= Tools::timeFormat($d_in->getTimeOut());
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
				$json['table'] .= '</td>';
			
				$json['table'] .= '<td>';
				$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
				$json['table'] .= $department->title;
				$json['table'] .= '</td>';

				/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/
	
				/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/
	
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= '<td>';
					$json['table'] .= "	<a class=\"btn btn-small\" href=\"javascript:void(0);\" id=\"staggered\" onclick=\"editEmployeeSchedule('" . Utilities::encrypt($d_in->getId()) . "');\">
											<i class=\"icon-pencil icon-fade\"></i> Edit
										</a>
										<!--<a class=\"btn btn-small\" href=\"javascript:void(0);\" onclick=\"deleteDTRLogTimeInTimeOut('" . Utilities::encrypt($d_in->getId()) . "');\">
											<i class=\"icon-trash icon-fade\"></i> Delete
										</a>-->";
					$json['table'] .= '</td>';
				}
	
				$json['table'] .= '</tr>';
			}
			
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }

		//Construct Paginator			
		if ($_POST['paginatorIndex']) {
			$paginator_index = $_POST['paginatorIndex'];
		} else {
			$paginator_index = 1;
		}

		if ($count > 0) {
			$json['paginator'] .= '<div id="yui-dt0-paginator0" class="yui-dt-paginator yui-pg-container">';
			$skip_counting = 0;

			//Add First BTN
			//Validate if first record for first record
			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first">First</span>';
			} else {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',1)">First</a></span>';
			}
			//

			//Add Prev Btn
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting - $_POST['limit'];
				}
				$skip_counting += $_POST['limit'];
			}

			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous">Previous</span>';
			} else {
				$prev_record = $paginator_index - 1;
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $prev_record . ');">Previous</a></span>';
			}
			//

			//Construct Paginator index
			$skip_counting = 0;
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting + $_POST['limit'];
					//$json['paginator'] .= '<li class="paginator_selected">' . $i . '</li>';		
				} else {
					//$json['paginator'] .= '<li><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $i . ');">' . $i . '</a></li>';		
				}

				$skip_counting += $_POST['limit'];
			}
			//

			//Add Next Btn
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next">Next</span>';
			} else {
				$next_record = $paginator_index + 1;
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $next_record . ');">Next</a></span>';
			}
			//


			//Add Last BTN
			//Validate if last record
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last">Last</span>';
			} else {
				$skip_counting -= $_POST['limit'];
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $count . ')">Last</a></span>';
			}
			//
			//
			$json['paginator'] .= '</div>';
		}
		//
		echo json_encode($json);
	}
	function load_dashboard_staggered_leave_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_start desc, date_applied desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		if (count($employee_ids) > 0) {
			foreach ($employee_ids as $key => $field)
			$employee_ids[$key] = Utilities::decrypt($field);
		}
		
		$log_ids = array();
		$v2_employee_leave = G_Employee_Leave_Request_Finder::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		
		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		
		//Utilities::displayArray($v2_employee_leave);
		//Construct table content
		foreach($v2_employee_leave as $d_in){
			$name = "staggered schedule";
			$schedule_type = G_Schedule_Type_Finder::findByName($name);
			$staggered_id = G_Employee_Schedule_Type_Finder::findSchedule($d_in->getEmployeeId(), $schedule_type);
			foreach($staggered_id as $stag){
				$employee_staggered = $stag->employee_id;
			}
			if($d_in->getEmployeeId() == $employee_staggered){
				$remarks = "";
				//$remarks = $d_in->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];
	
				$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);
	
				if ($device_info) {
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
				
				$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($d_in->getEmployeeId());
				$schedule = G_Employee_Schedule_Type_Finder::findAllByEmployeeId($d_in->getEmployeeId());
				$sched = G_Schedule_Type_Finder::findById($schedule->getScheduleTypeId());
				if($sched->getName() == 'staggered schedule'){
					$employee_with_schedule = "Staggered";
				}
				
				$json['table'] .= '<tr>';
				$json['table'] .= '<td>';
				$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $d_in->getId() . '" style="margin-left: 5px;">';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employee_with_schedule;
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->employee_code;
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
				$json['table'] .= '</td>';
	
			
				$json['table'] .= '<td>';
				$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
				$json['table'] .= $department->title;
				$json['table'] .= '</td>';

				/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($d_in->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/
	
				/*$json['table'] .= '<td>';
				$activity_name = G_Activity_Skills_Finder::findById($d_in->getActivityId());
				$json['table'] .= $activity_name->activity_skills_name;
				$json['table'] .= '</td>';*/
				
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= '<td>';
					if($d_in->is_approved == "Pending"){
						$json['table'] .= "Pending <a title=\"Approve\" id=\"edit\" class=\"ui-icon ui-icon-check g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:approveLeaveRequest('" . Utilities::encrypt($d_in->id) . "');\"></a>";
					}else{
						$json['table'] .= "Approved";
					}
					$json['table'] .= '</td>';
				}
	
				$json['table'] .= '</tr>';
			}
			
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }

		//Construct Paginator			
		if ($_POST['paginatorIndex']) {
			$paginator_index = $_POST['paginatorIndex'];
		} else {
			$paginator_index = 1;
		}

		if ($count > 0) {
			$json['paginator'] .= '<div id="yui-dt0-paginator0" class="yui-dt-paginator yui-pg-container">';
			$skip_counting = 0;

			//Add First BTN
			//Validate if first record for first record
			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first">First</span>';
			} else {
				$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',1)">First</a></span>';
			}
			//

			//Add Prev Btn
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting - $_POST['limit'];
				}
				$skip_counting += $_POST['limit'];
			}

			if ($paginator_index == 1) {
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous">Previous</span>';
			} else {
				$prev_record = $paginator_index - 1;
				$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $prev_record . ');">Previous</a></span>';
			}
			//

			//Construct Paginator index
			$skip_counting = 0;
			for ($i = 1; $i <= $count; $i++) {
				if ($paginator_index == $i) {
					$s_skip_counting = $skip_counting + $_POST['limit'];
					//$json['paginator'] .= '<li class="paginator_selected">' . $i . '</li>';		
				} else {
					//$json['paginator'] .= '<li><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $i . ');">' . $i . '</a></li>';		
				}

				$skip_counting += $_POST['limit'];
			}
			//

			//Add Next Btn
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next">Next</span>';
			} else {
				$next_record = $paginator_index + 1;
				$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $next_record . ');">Next</a></span>';
			}
			//


			//Add Last BTN
			//Validate if last record
			if ($paginator_index == $count) {
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last">Last</span>';
			} else {
				$skip_counting -= $_POST['limit'];
				$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $count . ')">Last</a></span>';
			}
			//
			//
			$json['paginator'] .= '</div>';
		}
		//
		echo json_encode($json);
	}
	function load_dashboard_staggered_ob_schedule_dt(){
		$date = $_SESSION['date'];
		$date_log = $date;
        
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$employee_array = array();
		//echo $date_log;
		foreach ($schedule_groups as $schedule_group) {
		  $employees = G_Employee_Finder::findByStaggeredScheduleGroup($schedule_group);
			if (!empty($employees)){
				foreach ($employees as $e) {
					$employee_array[] = $e->getId();			
					$json['table'] .= "<tr id=\"<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
					$json['table'] .= '<td>';
					$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $e->getId() . '" style="margin-left: 5px;">';
					$json['table'] .= '</td>';

					$json['table'] .= "<td>";
					if ($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"\" style=\"float:right;\" title=\"Remove\"></a>";
					} 
					$json['table'] .= $e->getLastname() . ", " . $e->getFirstname();
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					$json['table'] .= $schedule_group->getName();
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					$timeIn = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeIn($e->getId(), $date_log);
					foreach ($timeIn as $time) {
						$in = $time['time'];
						break;
					}
					$json['table'] .= $in;
					if ($in == NULL) {
						$json['table'] .= "N/A";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
					$timeOut = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeOut($e->getId(), $date_log);
					foreach ($timeOut as $time) {
						$out = $time['time'];
						break;
					}
					$json['table'] .= $out;
					if ($out == NULL) {
						$json['table'] .= "N/A";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";

					if ($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"javascript:removeScheduleMember('" . $e->getId() . "' , '" . $schedule_id . "');\" style=\"display: inline-block;\" title=\"Remove\"><i class=\"icon-remove\"><span class=\"tooltip\" title=\"Remove\"></span></i></a>";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "</tr>";
				}
			}
		}
		
        if ($employees == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}
	function load_dashboard_staggered_no_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		if (count($employee_ids) > 0) {
			foreach ($employee_ids as $key => $field)
			$employee_ids[$key] = Utilities::decrypt($field);
		}
		$log_ids = array();
		$v2_attendance = G_Attendance_Log_Finder_V2::findV2AttendanceByPeriod($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		$data_time_in  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogsTimeIn($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		$data_time_out  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogsTimeOut($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);

		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
	
		$schedule_groups = G_Schedule_Template_Finder::findAll();
		$g = G_Employee_Schedule_Type_Finder::findAll();
		$employees_schedule = $g;

		$employee_with_schedule = array();
		foreach ($g as $emp_sched) {
		  $is_employee_id_exist = 0;
		  foreach ($employee_with_schedule as $e_array) {
			if ($e_array == $emp_sched->employee_id) {
			  $is_employee_id_exist = 1;
			} else {
			  continue;
			}
		  }
		  if ($is_employee_id_exist == 1) {
			continue;
		  } else {
			$employee_with_schedule[] = $emp_sched->employee_id;
		  }
		}
		$employee = G_Employee_Finder::findAll();
		foreach($employee as $emp){
			$is_employee_have_sched = 0;
			foreach($employee_with_schedule as $emp_with_sched){
				if($emp_with_sched == $emp->getId()){
					$is_employee_have_sched = 1;
				}
			}
			if($is_employee_have_sched == 1){

			}else{
				$remarks = "";
				//$remarks = $d_in->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];
	
				$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);
	
				if ($device_info) {
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
	
				$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($emp->getId());
				
				$json['table'] .= '<tr>';
				$json['table'] .= '<td>';
				$json['table'] .= '<input type="checkbox" name="dtrChkNoSchedule[]" onchange="javascript:checkUncheckNoSchedule();" value="' . $emp->getId() . '" style="margin-left: 5px;">';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'No Schedule';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'N/A';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'N/A';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
				$json['table'] .= $department->title;
				$json['table'] .= '</td>';
	
				/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($emp->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/
	
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= '<td>';
					$json['table'] .= "	<a class=\"btn btn-small\" href=\"javascript:void(0);\" 
											onclick=\"editEmployeeNoSchedule('" . Utilities::encrypt($emp->getId()) . "');\">
											<i class=\"icon-pencil icon-fade\"></i> Edit
										</a>";
					$json['table'] .= '</td>';
				}
	
				$json['table'] .= '</tr>';
			}
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}
	//Flextime
	function load_dashboard_flextime_schedule_dt(){
		$date = $_SESSION['date'];
		$date_log = $date;
        
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$employee_array = array();
		//echo $date_log;
		/*foreach ($schedule_groups as $schedule_group) {
		  $employees = G_Employee_Finder::findByStaggeredScheduleGroup($schedule_group);
			if (!empty($employees)){
				foreach ($employees as $e) {
					$employee_array[] = $e->getId();			
					$json['table'] .= "<tr id=\"<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
					$json['table'] .= '<td>';
					$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $e->getId() . '" style="margin-left: 5px;">';
					$json['table'] .= '</td>';

					$json['table'] .= "<td>";
					if ($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"\" style=\"float:right;\" title=\"Remove\"></a>";
					} 
					$json['table'] .= $e->getLastname() . ", " . $e->getFirstname();
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					$json['table'] .= $schedule_group->getName();
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					$timeIn = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeIn($e->getId(), $date_log);
					foreach ($timeIn as $time) {
						$in = $time['time'];
						break;
					}
					$json['table'] .= $in;
					if ($in == NULL) {
						$json['table'] .= "N/A";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
					$timeOut = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeOut($e->getId(), $date_log);
					foreach ($timeOut as $time) {
						$out = $time['time'];
						break;
					}
					$json['table'] .= $out;
					if ($out == NULL) {
						$json['table'] .= "N/A";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";

					if ($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"javascript:removeScheduleMember('" . $e->getId() . "' , '" . $schedule_id . "');\" style=\"display: inline-block;\" title=\"Remove\"><i class=\"icon-remove\"><span class=\"tooltip\" title=\"Remove\"></span></i></a>";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "</tr>";
				}
			}
		}*/
		
        if ($employee_array == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}
	function load_dashboard_flextime_leave_schedule_dt(){
		$date = $_SESSION['date'];
		$date_log = $date;
       
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$employee_array = array();
		//echo $date_log;
		/*foreach ($schedule_groups as $schedule_group) {
		  $employees = G_Employee_Finder::findByStaggeredScheduleGroup($schedule_group);
			if (!empty($employees)){
				foreach ($employees as $e) {
					$employee_array[] = $e->getId();			
					$json['table'] .= "<tr id=\"<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
					$json['table'] .= '<td>';
					$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $e->getId() . '" style="margin-left: 5px;">';
					$json['table'] .= '</td>';

					$json['table'] .= "<td>";
					if ($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"\" style=\"float:right;\" title=\"Remove\"></a>";
					} 
					$json['table'] .= $e->getLastname() . ", " . $e->getFirstname();
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					$json['table'] .= $schedule_group->getName();
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					$timeIn = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeIn($e->getId(), $date_log);
					foreach ($timeIn as $time) {
						$in = $time['time'];
						break;
					}
					$json['table'] .= $in;
					if ($in == NULL) {
						$json['table'] .= "N/A";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
					$timeOut = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeOut($e->getId(), $date_log);
					foreach ($timeOut as $time) {
						$out = $time['time'];
						break;
					}
					$json['table'] .= $out;
					if ($out == NULL) {
						$json['table'] .= "N/A";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";

					if ($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"javascript:removeScheduleMember('" . $e->getId() . "' , '" . $schedule_id . "');\" style=\"display: inline-block;\" title=\"Remove\"><i class=\"icon-remove\"><span class=\"tooltip\" title=\"Remove\"></span></i></a>";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "</tr>";
				}
			}
		}*/
		
        if ($employee_array == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}
	function load_dashboard_flextime_ob_schedule_dt(){
		$date = $_SESSION['date'];
		$date_log = $date;
       
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$employee_array = array();
		//echo $date_log;
		/*foreach ($schedule_groups as $schedule_group) {
		  $employees = G_Employee_Finder::findByStaggeredScheduleGroup($schedule_group);
			if (!empty($employees)){
				foreach ($employees as $e) {
					$employee_array[] = $e->getId();			
					$json['table'] .= "<tr id=\"<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
					$json['table'] .= '<td>';
					$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $e->getId() . '" style="margin-left: 5px;">';
					$json['table'] .= '</td>';

					$json['table'] .= "<td>";
					if ($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"\" style=\"float:right;\" title=\"Remove\"></a>";
					} 
					$json['table'] .= $e->getLastname() . ", " . $e->getFirstname();
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					$json['table'] .= $schedule_group->getName();
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					$timeIn = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeIn($e->getId(), $date_log);
					foreach ($timeIn as $time) {
						$in = $time['time'];
						break;
					}
					$json['table'] .= $in;
					if ($in == NULL) {
						$json['table'] .= "N/A";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
					$timeOut = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeOut($e->getId(), $date_log);
					foreach ($timeOut as $time) {
						$out = $time['time'];
						break;
					}
					$json['table'] .= $out;
					if ($out == NULL) {
						$json['table'] .= "N/A";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";

					if ($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"javascript:removeScheduleMember('" . $e->getId() . "' , '" . $schedule_id . "');\" style=\"display: inline-block;\" title=\"Remove\"><i class=\"icon-remove\"><span class=\"tooltip\" title=\"Remove\"></span></i></a>";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "</tr>";
				}
			}
		}*/
		
        if ($employee_array == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}
	function load_dashboard_flextime_no_schedule_dt(){
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR, 'attendance', 'attendance_daily_time_record');

		if (!empty($_POST['sortColumn'])) {
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];
		} else {
			$order_by = "ORDER BY date_attendance desc, actual_time_in desc ";
		}

		if (!empty($_POST['displayStart'])) {
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		} else {
			$limit = "LIMIT " . $_POST['limit'];
		}
		if ($_POST['emp_sel']) {
			$arr_names = explode(",", $_POST['emp_sel']);
			foreach ($arr_names as $key => $field)
			$arr_names[$key] = Utilities::decrypt($field);
		}

		$employee_ids = $_POST['emp_sel'] ? explode(",", $_POST['emp_sel']) : array();
		if (count($employee_ids) > 0) {
			foreach ($employee_ids as $key => $field)
			$employee_ids[$key] = Utilities::decrypt($field);
		}
		$log_ids = array();
		$v2_attendance = G_Attendance_Log_Finder_V2::findV2AttendanceByPeriod($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		$data_time_in  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogsTimeIn($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		$data_time_out  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogsTimeOut($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);
		$v2_employee_attendance = G_Employee_Attendance_Finder_V2::findAllByPeriod($_SESSION['date'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], [], $_POST['device_id']);

		if (count($log_ids) > 0) {
			$data  = G_Attendance_Log_Finder_V2::findAllByPeriodWithBreakLogs($_POST['date_from'], $_POST['date_to'], $employee_ids, $order_by, $limit, $_POST['filter'], $log_ids);
		}

		$count = count($data);
		//Utilities::displayArray($v2_employee_attendance);
		//Construct table content
	
		$schedule_groups = G_Schedule_Template_Finder::findAll();
		$g = G_Employee_Schedule_Type_Finder::findAll();
		$employees_schedule = $g;

		$employee_with_schedule = array();
		foreach ($g as $emp_sched) {
		  $is_employee_id_exist = 0;
		  foreach ($employee_with_schedule as $e_array) {
			if ($e_array == $emp_sched->employee_id) {
			  $is_employee_id_exist = 1;
			} else {
			  continue;
			}
		  }
		  if ($is_employee_id_exist == 1) {
			continue;
		  } else {
			$employee_with_schedule[] = $emp_sched->employee_id;
		  }
		}
		$employee = G_Employee_Finder::findAll();
		foreach($employee as $emp){
			$is_employee_have_sched = 0;
			foreach($employee_with_schedule as $emp_with_sched){
				if($emp_with_sched == $emp->getId()){
					$is_employee_have_sched = 1;
				}
			}
			if($is_employee_have_sched == 1){

			}else{
				$remarks = "";
				//$remarks = $d_in->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];
	
				$device_info = G_Attendance_Log_Finder_V2::findDevice($machine_no);
	
				if ($device_info) {
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
	
				$employeeId = G_Employee_Finder::findEmployeeCodeByEmployeeId($emp->getId());
				
				$json['table'] .= '<tr>';
				$json['table'] .= '<td>';
				$json['table'] .= '<input type="checkbox" name="dtrChkNoSchedule[]" onchange="javascript:checkUncheckNoSchedule();" value="' . $emp->getId() . '" style="margin-left: 5px;">';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'No Schedule';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'N/A';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= 'N/A';
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$json['table'] .= $employeeId->lastname . ", " . $employeeId->firstname . " " . $employeeId->middlename . (G_Employee_Finder::findByIdIsArchive($employeeId->getId()) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
				$json['table'] .= '</td>';
	
				$json['table'] .= '<td>';
				$department = G_Company_Structure_Finder::findById($employeeId->department_company_structure_id);
				$json['table'] .= $department->title;
				$json['table'] .= '</td>';
	
				/*$json['table'] .= '<td>';
				$project_name = G_Project_Site_Finder::findById($emp->getProjectSiteId());
				$json['table'] .= $project_name->projectname;
				$json['table'] .= '</td>';*/
	
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= '<td>';
					$json['table'] .= "	<a class=\"btn btn-small\" href=\"javascript:void(0);\" 
											onclick=\"editEmployeeNoSchedule('" . Utilities::encrypt($emp->getId()) . "');\">
											<i class=\"icon-pencil icon-fade\"></i> Edit
										</a>";
					$json['table'] .= '</td>';
				}
	
				$json['table'] .= '</tr>';
			}
		}
		if ($json == null) {
			$json['table'] .= '<tr>';
			
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no data found - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';

			$json['table'] .= '</tr>';
        }
		$this->_load_paginator($count, $json);
		echo json_encode($json);
	}

	//Set Employee Schedule
	function set_employee_ajax_show_shift_schedule_members_list() {
		//$this->var['employees'] = G_Employee_Finder::findAllEmployeeByScheduleGroup();

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
		$this->view->render('new_schedule/set_employee_ajax_show_shift_schedule_members_list.php',$this->var);
	}
	function set_employee_ajax_show_compress_schedule_members_list() {
		//$this->var['employees'] = G_Employee_Finder::findAllEmployeeByScheduleGroup();

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
		$this->view->render('new_schedule/set_employee_ajax_show_compress_schedule_members_list.php',$this->var);
	}
	function set_employee_ajax_show_staggered_schedule_members_list() {
		//$this->var['employees'] = G_Employee_Finder::findAllEmployeeByScheduleGroup();

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
		$this->view->render('new_schedule/set_employee_ajax_show_staggered_schedule_members_list.php',$this->var);
	}
	function set_employee_ajax_show_flextime_schedule_members_list() {
		//$this->var['employees'] = G_Employee_Finder::findAllEmployeeByScheduleGroup();

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
		$this->view->render('new_schedule/set_employee_ajax_show_flextime_schedule_members_list.php',$this->var);
	}
	function set_employee_ajax_show_no_schedule_members_list() {
		//$this->var['employees'] = G_Employee_Finder::findAllEmployeeByScheduleGroup();

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
		$this->view->render('new_schedule/set_employee_ajax_show_no_schedule_members_list.php',$this->var);
	}
	//Load Set Employee Schedule
	function load_set_employee_shift_schedule_dt(){
		$date = $_SESSION['date'];
		$date_log = $date;
        
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$employee_array = array();
		//echo $date_log;
		/*foreach ($schedule_groups as $schedule_group) {
		  $employees = G_Employee_Finder::findByStaggeredScheduleGroup($schedule_group);
			if (!empty($employees)){
				foreach ($employees as $e) {
					$employee_array[] = $e->getId();
					$json['table'] .= "<tr id=\"<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
					$json['table'] .= '<td>';
					$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $e->getId() . '" style="margin-left: 5px;">';
					$json['table'] .= '</td>';

					$json['table'] .= "<td>";
					if ($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"\" style=\"float:right;\" title=\"Remove\"></a>";
					} 
					$json['table'] .= $e->getLastname() . ", " . $e->getFirstname();
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					$json['table'] .= $schedule_group->getName();
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					$timeIn = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeIn($e->getId(), $date_log);
					foreach ($timeIn as $time) {
						$in = $time['time'];
						break;
					}
					$json['table'] .= $in;
					if ($in == NULL) {
						$json['table'] .= "N/A";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
					$timeOut = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeOut($e->getId(), $date_log);
					foreach ($timeOut as $time) {
						$out = $time['time'];
						break;
					}
					$json['table'] .= $out;
					if ($out == NULL) {
						$json['table'] .= "N/A";
					}
	
	
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					if ($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"javascript:removeScheduleMember('" . $e->getId() . "' , '" . $schedule_id . "');\" style=\"display: inline-block;\" title=\"Remove\"><i class=\"icon-remove\"><span class=\"tooltip\" title=\"Remove\"></span></i></a>";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "</tr>";
				}
			}
		}*/
		
        if ($employee_array == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}
	function load_set_employee_compress_schedule_dt(){
		$date = $_SESSION['date'];
		$date_log = $date;
        
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$employee_array = array();
		//echo $date_log;
		/*foreach ($schedule_groups as $schedule_group) {
		  $employees = G_Employee_Finder::findByStaggeredScheduleGroup($schedule_group);
			if (!empty($employees)){
				foreach ($employees as $e) {
					$employee_array[] = $e->getId();
					$json['table'] .= "<tr id=\"<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
					$json['table'] .= '<td>';
					$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $e->getId() . '" style="margin-left: 5px;">';
					$json['table'] .= '</td>';

					$json['table'] .= "<td>";
					if ($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"\" style=\"float:right;\" title=\"Remove\"></a>";
					} 
					$json['table'] .= $e->getLastname() . ", " . $e->getFirstname();
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					$json['table'] .= $schedule_group->getName();
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					$timeIn = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeIn($e->getId(), $date_log);
					foreach ($timeIn as $time) {
						$in = $time['time'];
						break;
					}
					$json['table'] .= $in;
					if ($in == NULL) {
						$json['table'] .= "N/A";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
					$timeOut = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeOut($e->getId(), $date_log);
					foreach ($timeOut as $time) {
						$out = $time['time'];
						break;
					}
					$json['table'] .= $out;
					if ($out == NULL) {
						$json['table'] .= "N/A";
					}
	
	
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					if ($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"javascript:removeScheduleMember('" . $e->getId() . "' , '" . $schedule_id . "');\" style=\"display: inline-block;\" title=\"Remove\"><i class=\"icon-remove\"><span class=\"tooltip\" title=\"Remove\"></span></i></a>";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "</tr>";
				}
			}
		}*/
		
        if ($employee_array == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}
	function load_set_employee_staggered_schedule_dt(){
		$date = $_SESSION['date'];
		$date_log = $date;
        $schedule_groups = G_Schedule_Template_Finder::findAllByDate($date);
		
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$employee_array = array();
		//echo $date_log;
		foreach ($schedule_groups as $schedule_group) {
		  $employees = G_Employee_Finder::findByStaggeredScheduleGroup($schedule_group);
			if (!empty($employees)){
				foreach ($employees as $e) {
					$employee_array[] = $e->getId();
					$json['table'] .= "<tr id=\"<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
					$json['table'] .= '<td>';
					$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $e->getId() . '" style="margin-left: 5px;">';
					$json['table'] .= '</td>';

					$json['table'] .= "<td>";
					if ($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"\" style=\"float:right;\" title=\"Remove\"></a>";
					} 
					$json['table'] .= $e->getLastname() . ", " . $e->getFirstname();
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					$json['table'] .= $schedule_group->getName();
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					$timeIn = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeIn($e->getId(), $date_log);
					foreach ($timeIn as $time) {
						$in = $time['time'];
						break;
					}
					$json['table'] .= $in;
					if ($in == NULL) {
						$json['table'] .= "N/A";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
					$timeOut = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeOut($e->getId(), $date_log);
					foreach ($timeOut as $time) {
						$out = $time['time'];
						break;
					}
					$json['table'] .= $out;
					if ($out == NULL) {
						$json['table'] .= "N/A";
					}
	
	
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					if ($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"javascript:removeScheduleMember('" . $e->getId() . "' , '" . $schedule_id . "');\" style=\"display: inline-block;\" title=\"Remove\"><i class=\"icon-remove\"><span class=\"tooltip\" title=\"Remove\"></span></i></a>";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "</tr>";
				}
			}
		}
		
        if ($employees == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}
	function load_set_employee_flextime_schedule_dt(){
		$date = $_SESSION['date'];
		$date_log = $date;
       
		
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$employee_array = array();
		//echo $date_log;
		/*foreach ($schedule_groups as $schedule_group) {
		  $employees = G_Employee_Finder::findByStaggeredScheduleGroup($schedule_group);
			if (!empty($employees)){
				foreach ($employees as $e) {
					$employee_array[] = $e->getId();
					$json['table'] .= "<tr id=\"<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
					$json['table'] .= '<td>';
					$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $e->getId() . '" style="margin-left: 5px;">';
					$json['table'] .= '</td>';

					$json['table'] .= "<td>";
					if ($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"\" style=\"float:right;\" title=\"Remove\"></a>";
					} 
					$json['table'] .= $e->getLastname() . ", " . $e->getFirstname();
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					$json['table'] .= $schedule_group->getName();
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					$timeIn = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeIn($e->getId(), $date_log);
					foreach ($timeIn as $time) {
						$in = $time['time'];
						break;
					}
					$json['table'] .= $in;
					if ($in == NULL) {
						$json['table'] .= "N/A";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
					$timeOut = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeOut($e->getId(), $date_log);
					foreach ($timeOut as $time) {
						$out = $time['time'];
						break;
					}
					$json['table'] .= $out;
					if ($out == NULL) {
						$json['table'] .= "N/A";
					}
	
	
					$json['table'] .= "</td>";
					$json['table'] .= "<td>";
					if ($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"javascript:removeScheduleMember('" . $e->getId() . "' , '" . $schedule_id . "');\" style=\"display: inline-block;\" title=\"Remove\"><i class=\"icon-remove\"><span class=\"tooltip\" title=\"Remove\"></span></i></a>";
					}
					$json['table'] .= "</td>";
					$json['table'] .= "</tr>";
				}
			}
		}*/
		
        if ($employee_array == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}
	function load_set_employee_no_schedule_dt(){
		$date = $_SESSION['date'];
		$date_log = $date;
        
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$employee_array = array();
		//echo $date_log;
		foreach ($schedule_groups as $schedule_group) {
		  $employees = G_Employee_Finder::findByStaggeredScheduleGroup($schedule_group);

			if (!empty($employees)){
				foreach ($employees as $e) {
					$employee_array[] = $e->getId();
				}
			}
		}

		$employees_without_staggered = G_Employee_Finder::findAll();
		foreach($employees_without_staggered as $empId_without_staggered){
			$condition = 0;
			foreach($employee_array as $empId_with_staggered_schedule){
				if($empId_without_staggered->getId() != $empId_with_staggered_schedule){
					$condition = 1;
				}
			}

			if($condition == 1){
				$json['table'] .= "<tr id=\"<?php echo $empId_without_staggered->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
				$json['table'] .= '<td>';
				$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="' . $e->getId() . '" style="margin-left: 5px;">';
				$json['table'] .= '</td>';
				
				$json['table'] .= "<td>";
				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"\" style=\"float:right;\" title=\"Remove\"></a>";
				} 
				$json['table'] .= $empId_without_staggered->getLastname() . ", " . $empId_without_staggered->getFirstname();
				$json['table'] .= "</td>";
				$json['table'] .= "<td>";
				$json['table'] .= "No Schedule";
				$json['table'] .= "</td>";
				$json['table'] .= "<td>";
				$timeIn = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeIn($empId_without_staggered->getId(), $date_log);
				foreach ($timeIn as $time) {
					$in = $time['time'];
					break;
				}
				$json['table'] .= $in;
				if ($in == NULL) {
					$json['table'] .= "N/A";
				}
				$json['table'] .= "</td>";
				$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
				$timeOut = G_Attendance_Log_Finder_V2::findTimeByEmployeeIdTypeOut($empId_without_staggered->getId(), $date_log);
				foreach ($timeOut as $time) {
					$out = $time['time'];
					break;
				}
				$json['table'] .= $out;
				if ($out == NULL) {
					$json['table'] .= "N/A";
				}
				$json['table'] .= "</td>";
				$json['table'] .= "<td>";

				if ($permission_action == Sprint_Modules::PERMISSION_02) {
					$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"javascript:removeScheduleMember('" . $e->getId() . "' , '" . $schedule_id . "');\" style=\"display: inline-block;\" title=\"Remove\"><i class=\"icon-remove\"><span class=\"tooltip\" title=\"Remove\"></span></i></a>";
				}
				$json['table'] .= "</td>";
				$json['table'] .= "</tr>";
			}
		}
		
        if ($employees == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}
	function ajax_assign_schedule_groups() {
		$this->var['schedule_id'] = $_GET['schedule_id'];
		$this->view->noTemplate();
		$this->view->render('new_schedule/forms/ajax_assign_schedule_groups_form.php',$this->var);
	}
	function ajax_create_flexible_schedule(){
		$this->var['action'] = url('new_schedule/_create_flexible_schedule');
		$this->view->noTemplate();
		$this->view->render('new_schedule/forms/ajax_create_flexible_schedule_form.php',$this->var);
	}
	function ajax_create_staggered_schedule() {
		$this->var['action'] = url('new_schedule/_create_staggered_schedule');
		$this->view->noTemplate();
		$this->view->render('new_schedule/forms/ajax_create_staggered_schedule_form.php',$this->var);
	}
	function ajax_create_compress_schedule() {
		$this->var['action'] = url('new_schedule/_create_compress_schedule');
		$this->view->noTemplate();
		$this->view->render('new_schedule/forms/ajax_create_compress_schedule_form.php',$this->var);
	}
	function ajax_create_shift_schedule(){
		$this->var['action'] = url('new_schedule/_create_shift_schedule');
		$this->view->noTemplate();
		$this->view->render('new_schedule/forms/ajax_create_shift_schedule_form.php',$this->var);
	}
	function ajax_assign_schedule_employees() {
		$this->var['schedule_id'] = $_GET['schedule_id'];
		$this->view->noTemplate();
		$this->view->render('new_schedule/forms/ajax_assign_schedule_employees_form.php',$this->var);
	}

	function ajax_assign_staggered_schedule_employees() {
		$this->var['schedule_id'] = $_GET['schedule_id'];
		$this->view->noTemplate();
		$this->view->render('new_schedule/forms/ajax_assign_staggered_schedule_employees_form.php',$this->var);
	}

	function ajax_edit_staggered_schedule_form() {
		$this->var['action']    = url('new_schedule/_edit_staggered_schedule');
		$this->var['public_id'] = $public_id = $_GET['public_id'];
		$group = G_Schedule_Template_Finder::findByPublicId($public_id);
		$this->var['group_name']   = $group->getName();
		$this->var['is_default']   = $group->isDefault();

		$effect_date = $group->getStartDate();
		$end_date    = $group->getEndDate();

		if (!strtotime($effect_date)) {
			$effect_date = date('Y-m-d');
		}
		$wd = $group->getWorkingDays();
		$h = $group->getHours();

		$working_days = explode(",",$wd);
		$hours = explode(",",$h);

		$working_days_array = array_filter($working_days);
		$hours_array = array_filter($hours);
		//naka-ready na for mon-sun input values
		$this->var['working_days'] = $working_days_array;
		$this->var['hours'] = $hours_array;
		$this->var['end_date']          = $end_date;
		$this->var['effectivity_date']  = $effect_date;
		$this->var['schedules'] 	    = G_Schedule_Template_Finder::findAllByScheduleGroup($group);
		$this->view->noTemplate();
		$this->view->render('new_schedule/forms/ajax_edit_staggered_schedule_form.php',$this->var);
	}
	//Employee List
	function load_staggered_schedule_employee_list_dt(){
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$date = $_SESSION['date'];
		$schedule_groups = G_Schedule_Template_Finder::findByScheduleType("Staggered");
		$g = G_Employee_Schedule_Type_Finder::findAll();
		$employees_schedule = $g;

		$employee_array = array();
		foreach ($g as $emp_sched) {
		  $is_employee_id_exist = 0;
		  foreach ($employee_array as $e_array) {
			if ($e_array == $emp_sched->employee_id) {
			  $is_employee_id_exist = 1;
			} else {
			  continue;
			}
		  }
		  if ($is_employee_id_exist == 1) {
			continue;
		  } else {
			$employee_array[] = $emp_sched->employee_id;
		  }
		}

		$multiple_schedule = array();
        foreach ($employee_array as $e_array) {
          $multiple_schedule = null;
          foreach ($schedule_groups as $schedule_group) {
			
            $id = $schedule_group->getId();
            $g = G_Schedule_Template_Finder::findById($id);

            $employees = G_Employee_Finder::findSpecificEmployeeByStaggeredScheduleGroup($g, $e_array, $date);
			
            if ($employees) {
              $multiple_schedule[] = $schedule_group->getName();
            }
          }

          if ($multiple_schedule) {
            $employees = G_Employee_Finder::findEmployeeByStaggeredScheduleGroup($e_array);
          }

          //Utilities::displayArray($multiple_schedule);
          foreach ($employees as $e){
			$json['table'] .= "<tr id=\"<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"\" style=\"float:right;\" title=\"Remove\"></a>";
			}
			$json['table'] .= $e->getLastname() . ', '. $e->getFirstname();
			$json['table'] .= '</td>';
			$sched_name = implode(', ', $multiple_schedule);
			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			$json['table'] .= $sched_name;
			$json['table'] .= '</td>';

			$department_name = G_Company_Structure_Finder::findById($e->department_company_structure_id);
			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			$json['table'] .= $department_name->getTitle();
			$json['table'] .= '</td>';

			$project_site_name = G_Project_Site_Finder::findById($e->project_site_id);
			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			$json['table'] .= $project_site_name->getprojectname();
			$json['table'] .= '</td>';

			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\"  onclick=\"javascript:removeAllStaggeredScheduleMemberEmployees('" . $e->getId() . "', '" . $schedule_id . "');\" style=\"display: inline-block;\" title=\"Remove\"><i class=\"icon-remove\"><span class=\"tooltip\" title=\"Remove\"></span></i></a>";
			}
			$json['table'] .= '</td>';
            $json['table'] .= '</tr>';
          	}
        }
        if ($json['table'] == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}
	function load_compressed_schedule_employee_list_dt(){
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$date = $_SESSION['date'];
		$schedule_groups = G_Schedule_Template_Finder::findByScheduleType("Compressed");
		$g = G_Employee_Schedule_Type_Finder::findAll();
		$employees_schedule = $g;

		$employee_array = array();
		foreach ($g as $emp_sched) {
		  $is_employee_id_exist = 0;
		  foreach ($employee_array as $e_array) {
			if ($e_array == $emp_sched->employee_id) {
			  $is_employee_id_exist = 1;
			} else {
			  continue;
			}
		  }
		  if ($is_employee_id_exist == 1) {
			continue;
		  } else {
			$employee_array[] = $emp_sched->employee_id;
		  }
		}

		$multiple_schedule = array();
        foreach ($employee_array as $e_array) {
          $multiple_schedule = null;
          foreach ($schedule_groups as $schedule_group) {
			
            $id = $schedule_group->getId();
            $g = G_Schedule_Template_Finder::findById($id);

            $employees = G_Employee_Finder::findSpecificEmployeeByStaggeredScheduleGroup($g, $e_array, $date);
			
            if ($employees) {
              $multiple_schedule[] = $schedule_group->getName();
            }
          }

          if ($multiple_schedule) {
            $employees = G_Employee_Finder::findEmployeeByStaggeredScheduleGroup($e_array);
          }

          //Utilities::displayArray($multiple_schedule);
          foreach ($employees as $e){
			$json['table'] .= "<tr id=\"<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"\" style=\"float:right;\" title=\"Remove\"></a>";
			}
			$json['table'] .= $e->getLastname() . ', '. $e->getFirstname();
			$json['table'] .= '</td>';
			$sched_name = implode(', ', $multiple_schedule);
			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			$json['table'] .= $sched_name;
			$json['table'] .= '</td>';

			$department_name = G_Company_Structure_Finder::findById($e->department_company_structure_id);
			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			$json['table'] .= $department_name->getTitle();
			$json['table'] .= '</td>';

			$project_site_name = G_Project_Site_Finder::findById($e->project_site_id);
			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			$json['table'] .= $project_site_name->getprojectname();
			$json['table'] .= '</td>';

			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\"  onclick=\"javascript:removeAllStaggeredScheduleMemberEmployees('" . $e->getId() . "', '" . $schedule_id . "');\" style=\"display: inline-block;\" title=\"Remove\"><i class=\"icon-remove\"><span class=\"tooltip\" title=\"Remove\"></span></i></a>";
			}
			$json['table'] .= '</td>';
            $json['table'] .= '</tr>';
          	}
        }
        if ($json['table'] == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}
	function load_shift_schedule_employee_list_dt(){
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$date = $_SESSION['date'];
		$schedule_groups = G_Schedule_Template_Finder::findByScheduleType("Shift");
		$g = G_Employee_Schedule_Type_Finder::findAll();
		$employees_schedule = $g;

		$employee_array = array();
		foreach ($g as $emp_sched) {
		  $is_employee_id_exist = 0;
		  foreach ($employee_array as $e_array) {
			if ($e_array == $emp_sched->employee_id) {
			  $is_employee_id_exist = 1;
			} else {
			  continue;
			}
		  }
		  if ($is_employee_id_exist == 1) {
			continue;
		  } else {
			$employee_array[] = $emp_sched->employee_id;
		  }
		}

		$multiple_schedule = array();
        foreach ($employee_array as $e_array) {
          $multiple_schedule = null;
          foreach ($schedule_groups as $schedule_group) {
			
            $id = $schedule_group->getId();
            $g = G_Schedule_Template_Finder::findById($id);

            $employees = G_Employee_Finder::findSpecificEmployeeByStaggeredScheduleGroup($g, $e_array, $date);
			
            if ($employees) {
              $multiple_schedule[] = $schedule_group->getName();
            }
          }

          if ($multiple_schedule) {
            $employees = G_Employee_Finder::findEmployeeByStaggeredScheduleGroup($e_array);
          }

          //Utilities::displayArray($multiple_schedule);
          foreach ($employees as $e){
			$json['table'] .= "<tr id=\"<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"\" style=\"float:right;\" title=\"Remove\"></a>";
			}
			$json['table'] .= $e->getLastname() . ', '. $e->getFirstname();
			$json['table'] .= '</td>';
			$sched_name = implode(', ', $multiple_schedule);
			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			$json['table'] .= $sched_name;
			$json['table'] .= '</td>';

			$department_name = G_Company_Structure_Finder::findById($e->department_company_structure_id);
			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			$json['table'] .= $department_name->getTitle();
			$json['table'] .= '</td>';

			$project_site_name = G_Project_Site_Finder::findById($e->project_site_id);
			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			$json['table'] .= $project_site_name->getprojectname();
			$json['table'] .= '</td>';

			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\"  onclick=\"javascript:removeAllStaggeredScheduleMemberEmployees('" . $e->getId() . "', '" . $schedule_id . "');\" style=\"display: inline-block;\" title=\"Remove\"><i class=\"icon-remove\"><span class=\"tooltip\" title=\"Remove\"></span></i></a>";
			}
			$json['table'] .= '</td>';
            $json['table'] .= '</tr>';
          	}
        }
        if ($json['table'] == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}
	
	function load_flexible_schedule_employee_list_dt(){
		$permission_action 		= $this->validatePermission(G_Sprint_Modules::HR,'schedule','');
		$date = $_SESSION['date'];
		$schedule_groups = G_Schedule_Template_Finder::findByScheduleType("Flexible");
		$g = G_Employee_Schedule_Type_Finder::findAll();
		$employees_schedule = $g;

		$employee_array = array();
		foreach ($g as $emp_sched) {
		  $is_employee_id_exist = 0;
		  foreach ($employee_array as $e_array) {
			if ($e_array == $emp_sched->employee_id) {
			  $is_employee_id_exist = 1;
			} else {
			  continue;
			}
		  }
		  if ($is_employee_id_exist == 1) {
			continue;
		  } else {
			$employee_array[] = $emp_sched->employee_id;
		  }
		}

		$multiple_schedule = array();
        foreach ($employee_array as $e_array) {
          $multiple_schedule = null;
          foreach ($schedule_groups as $schedule_group) {
			
            $id = $schedule_group->getId();
            $g = G_Schedule_Template_Finder::findById($id);

            $employees = G_Employee_Finder::findSpecificEmployeeByStaggeredScheduleGroup($g, $e_array, $date);
			
            if ($employees) {
              $multiple_schedule[] = $schedule_group->getName();
            }
          }

          if ($multiple_schedule) {
            $employees = G_Employee_Finder::findEmployeeByStaggeredScheduleGroup($e_array);
          }

          //Utilities::displayArray($multiple_schedule);
          foreach ($employees as $e){
			$json['table'] .= "<tr id=\"<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee\">";
			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\" onclick=\"\" style=\"float:right;\" title=\"Remove\"></a>";
			}
			$json['table'] .= $e->getLastname() . ', '. $e->getFirstname();
			$json['table'] .= '</td>';
			$sched_name = implode(', ', $multiple_schedule);
			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			$json['table'] .= $sched_name;
			$json['table'] .= '</td>';

			$department_name = G_Company_Structure_Finder::findById($e->department_company_structure_id);
			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			$json['table'] .= $department_name->getTitle();
			$json['table'] .= '</td>';

			$project_site_name = G_Project_Site_Finder::findById($e->project_site_id);
			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			$json['table'] .= $project_site_name->getprojectname();
			$json['table'] .= '</td>';

			$json['table'] .= "<td style=\"border-bottom:1px solid #cccccc\">";
			if ($permission_action == Sprint_Modules::PERMISSION_02) {
				$json['table'] .= "<a class=\"\" href=\"javascript:void(0)\"  onclick=\"javascript:removeAllStaggeredScheduleMemberEmployees('" . $e->getId() . "', '" . $schedule_id . "');\" style=\"display: inline-block;\" title=\"Remove\"><i class=\"icon-remove\"><span class=\"tooltip\" title=\"Remove\"></span></i></a>";
			}
			$json['table'] .= '</td>';
            $json['table'] .= '</tr>';
          	}
        }
        if ($json['table'] == null) {
			$json['table'] .= '<tr>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '<i>- no record - </i>';
			$json['table'] .= '</td>';
            $json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '<td>';
			$json['table'] .= '</td>';
			$json['table'] .= '</tr>';
        }
		echo json_encode($json);
	}

	//Batch Update
	function _batch_update_new_schedule(){
		$error = 0;
		$v2_employee_attendance_id = $_POST['v2_employee_attendance_id'];
		
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
			if ($_POST['ids']) {
				foreach($_POST['ids'] as $key => $emp_id){
					$employees[] = $emp_id;
				}
				
			}
		}
		if (empty($groups) && empty($employees)) {
			$error++;
		}
		
		if ($_POST['ids']) {
			$schedule_id = $_POST['schedule'];
			if (!empty($employees)) {
				$s = G_Schedule_Template_Finder::findById($schedule_id);
				$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
				$start_date = $effectivity_date; //$c->getStartDate();
				if ($c) {
					$end_date = $c->getEndDate();
				}

				foreach ($employees as $employee_id) {
					$e = G_Employee_Finder::findById($employee_id);

					$employe_already_assigned = G_Employee_Schedule_Type_Finder::findByEmployeeAndDate($e, $_SESSION['date']);

					$s->assignToEmployee($e, $employe_already_assigned, $_SESSION['date']); //here
					$employe_already_assigned = G_Employee_Schedule_Type_Finder::findByEmployeeAndDate($e, $_SESSION['date']);
					
					// UPDATE ATTENDANCE
					$saved = G_Employee_Attendance_Manager_V2::updateScheduleTemplate($v2_employee_attendance_id, $employe_already_assigned);
					if ($c) {
						G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
					}
					
					//General Reports / Shr Audit Trail
					$shr_emp = G_Employee_Helper::findByEmployeeId($employee_id);
					$emp_name = $shr_emp['firstname'] . ' ' . $shr_emp['lastname'];
					$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, ' Schedule of ', $emp_name, $start_date, $_SESSION['date'], 1, $shr_emp['position'], $shr_emp['department']);
				}
			}

			$json['is_saved'] = 1;			
			$json['message']  = 'Record was successfully saved.';
		}else {
			$json['is_saved'] = 0;
			$json['message']  = 'Data Error';
		}
		echo json_encode($json);
	}
}
