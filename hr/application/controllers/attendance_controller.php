<?php
class Attendance_Controller extends Controller
{
	function __construct() {
		parent::__construct();
		Loader::appMainScript('attendance_base.js');
		Loader::appMainScript('attendance.js');
		Loader::appMainScript('schedule_base.js');
		Loader::appMainScript('schedule.js');
		Loader::appMainScript('restday_base.js');
		Loader::appMainScript('restday.js');					
		Loader::appMainUtilities();
		Loader::appStyle('style.css');

		//Jquery::loadMainTextBoxList();
		
		$this->sprintHdrMenu(G_Sprint_Modules::HR, 'attendance');				
		$this->check_schedule();
		
		if($_GET['hpid']){
			$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = G_Cutoff_Period_Helper::isPeriodLock($_GET['hpid']);
		}else{
			$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'];
		}
		
		$this->var['employee']     	    = 'selected';
		$this->var['attendance'] 		= 'selected';	

		$this->validatePermission(G_Sprint_Modules::HR,'attendance','');

	}

	function index_deprecated() {
		Loader::appMainScript('payslip_base.js');
		Loader::appMainScript('payslip.js');
		Loader::appMainScript('settings_base.js');
		//Style::loadTableThemes();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTipsy();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();
		
		$this->var['page_title'] = 'Attendance';
		//$this->var['action'] = url('attendance/ajax_search_employee');
		$this->var['action'] = url('attendance/manage');
		$this->var['periods'] = G_Payslip_Helper::getPeriods();
		$this->view->setTemplate('template.php');
		$this->view->render('attendance/index.php',$this->var);		
	}
	
	function index() {
        
        $now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }
        redirect("attendance/manage?from={$from_date}&to={$to_date}&hpid={$hpid}");

	}
	
	function view_payroll_year() {
		Loader::appMainScript('payslip_base.js');
		Loader::appMainScript('payslip.js');
		Loader::appMainScript('settings_base.js');				
		Jquery::loadMainTipsy();
		$data = G_Cutoff_Period_Finder::findAllDistinctYearTag();
		
		$this->var['page_title'] = 'Attendance';		
		$this->var['data']		 = $data;
		$this->view->setTemplate('template.php');
		$this->view->render('attendance/year_selection.php',$this->var);	
	}
	
	function view_cutoff_periods() {
		Loader::appMainScript('payslip_base.js');
		Loader::appMainScript('payslip.js');
		Loader::appMainScript('settings_base.js');
		//Style::loadTableThemes();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTipsy();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();
		
		$this->var['page_title'] = 'Attendance : ' . $_GET['year'];
		//$this->var['action'] = url('attendance/ajax_search_employee');
		$this->var['action']  = url('attendance/manage');
		$this->var['year']	  = $_GET['year'];
		$this->var['periods'] = G_Payslip_Helper::getPayrollPeriodsByYearTag($_GET['year']);
		$this->view->setTemplate('template.php');
		$this->view->render('attendance/payroll_periods.php',$this->var);		
	}
		
	function download_timesheet() {
		set_time_limit(999999999999999999999);
		$this->var['from'] = $from = $_GET['from'];
		$this->var['to'] = $to = $_GET['to'];
		
		if (strtotime($from) && strtotime($to)) {
			$this->var['employees'] = $employees = G_Employee_Finder::findAllActiveByDate($to);
			$h = G_Payslip_Hour_Helper::getAllHoursByEmployeesAndPeriod($employees, $from, $to);
			$this->var['hours'] = $h;
			$this->var['total_employees'] = count($employees);
		}
		$this->view->render('attendance/download_timesheet.php', $this->var);		
	}
	
	function check_schedule()
	{
		$s = G_Schedule_Helper::isScheduleEmpty();		
		if($s == 0){
			redirect('schedule/schedule_error');
		}
	}
	
	function download_timesheet_breakdown() {
		ini_set("memory_limit", "999M");		
		set_time_limit(999999999999999999999);
		
		$this->var['from'] 	= $from = $_GET['from'];
		$this->var['to']    = $to   = $_GET['to'];
		
		//$this->var['from'] = $from = '2012-11-16';
		//$this->var['to']   = $to   = '2012-11-16';
		
		if (strtotime($from) && strtotime($to)) {
			//$this->var['employees'] = $employees = G_Employee_Finder::findAllActiveByDate($to);
			$this->var['employees'] = $employees = G_Employee_Finder::findAllActiveWithTerminated($from);			
			
			$at = G_Attendance_Helper::getAllAttendanceGroupByEmployeeAndDate($employees, $from, $to);
			$this->var['dates'] = Tools::getBetweenDates($from, $to);
			$this->var['attendance'] = $at;
			$this->var['total_employees'] = count($employees);
		}
		$this->view->render('attendance/download_timesheet_breakdown.php', $this->var);		
	}
	
	function download_timesheet_breakdown_by_employee_and_period() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$employee_id = Utilities::decrypt($_GET['employee_id']);
		$this->var['from'] = $from = $_GET['from'];
		$this->var['to'] = $to = $_GET['to'];
		
		if (strtotime($from) && strtotime($to)) {
			$employees[$employee_id] = G_Employee_Finder::findById($employee_id);
			$this->var['employees'] = $employees;
			$at = G_Attendance_Helper::getAllAttendanceGroupByEmployeeAndDate($employees, $from, $to);
			$this->var['dates'] = Tools::getBetweenDates($from, $to);
			$this->var['attendance'] = $at;
			$this->var['total_employees'] = count($employees);
		}

		if( isset($_GET['report']) && $_GET['report'] == 'summarized' ){
			$this->view->render('attendance/download_timesheet_breakdown_summarized.php', $this->var);	
		}else{
			$this->view->render('attendance/download_timesheet_breakdown.php', $this->var);	
		}
	}	
	
	function download_filtered_timesheet_breakdown_by_employee_and_period() {		
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$eid    = $_POST['employee_id'];	
		$ar     = Tools::convertEncryptedStringToArray(",",$eid);
		$ar_eid = implode(",",$ar);
		
		$from   = $_POST['filter_timesheet_breakdown_date_from'];
		$to     = $_POST['filter_timesheet_breakdown_date_to'];
		
		if (strtotime($from) && strtotime($to)) {			
			if($_POST['all_employee']){
				$employees = G_Employee_Finder::findAllActive();				
			}else{
				$employees = G_Employee_Finder::findAllInArrayId($ar_eid);
			}
			//$employees[$employee_id] = G_Employee_Finder::findById($employee_id);
			$at 						    = G_Attendance_Helper::getAllAttendanceGroupByEmployeeAndDate($employees, $from, $to);			
			$this->var['employees']  		= $employees;		
			$this->var['dates'] 		 	= Tools::getBetweenDates($from, $to);
			$this->var['from'] 		 	   	= $from;
			$this->var['to']			 	= $to;
			$this->var['attendance']	   	= $at;		
			$this->var['total_employees'] 	= count($employees);			
		}
		$this->view->render('attendance/download_timesheet_breakdown.php', $this->var);	
	}	
	
	function download_timesheet_breakdown_by_department() {
		ini_set("memory_limit", "999M");		
		set_time_limit(999999999999999999999);
		
		$this->var['from'] 	= $from = $_GET['from'];
		$this->var['to']    = $to   = $_GET['to'];
		
		/*$this->var['from'] = $from = '2012-11-16';
		$this->var['to']   = $to   = '2012-11-16'; */
		
		if (strtotime($from) && strtotime($to)) {
			if(!empty($_SESSION['sprint_hr']['tmp']['h_department_id'])) {
				$department_id = Utilities::decrypt($_SESSION['sprint_hr']['tmp']['h_department_id']);
				$this->var['employees'] = $employees = G_Employee_Finder::findAllEmployeeWithOvertimeByDateRangeAndDepartment($department_id,$from,$to);
				$at = G_Attendance_Helper::getAllAttendanceGroupByEmployeeAndDate($employees, $from, $to);
				$this->var['dates'] = Tools::getBetweenDates($from, $to);
				$this->var['attendance'] = $at;
				$this->var['total_employees'] = count($employees);
				
			} else {
				$this->var['employees'] = $employees = G_Employee_Finder::findAllActiveByDate($to);
				$at = G_Attendance_Helper::getAllAttendanceGroupByEmployeeAndDate($employees, $from, $to);
				$this->var['dates'] = Tools::getBetweenDates($from, $to);
				$this->var['attendance'] = $at;
				$this->var['total_employees'] = count($employees);
			}
			
		}
		$this->view->render('attendance/download_timesheet_breakdown.php', $this->var);		
	}

	function manageDepre2017() {
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTipsy();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();			

		$ar_date['from'] = $_GET['from'];
		$ar_date['to']   = $_GET['to'];
		
		$this->var['page_title'] = 'Attendance';
		$this->var['start_date'] = $start_date = $_GET['from'];
		$this->var['end_date']   = $_GET['to'];
		$this->var['query'] 	 = $query = $_GET['query'];
		
        $per_page = 10;
        $page_number = (int) $_GET['pageID'];
        if ($page_number > 0) {
            $page_number--;
            $start_record = $page_number * $per_page;
        } else {
            $start_record = $page_number;
        }
		
		if ($query != '') {
			if($_GET['s_exact']){
				$this->var['checked']   = 'checked="checked"';
				$this->var['employees']   = G_Employee_Finder::searchActiveByExactFirstnameAndLastnameAndEmployeeCodeWithCriteriaTerminationDate($query,$ar_date);
				//$this->var['employees'] = G_Employee_Finder::searchActiveByExactFirstnameAndLastnameAndEmployeeCode($query);
			}else{
				$this->var['employees'] = G_Employee_Finder::searchAllByFirstnameAndLastnameAndEmployeeCodeAndDepartmentNameAndSection($query);//G_Employee_Finder::searchActiveByFirstnameAndLastnameAndEmployeeCodeWithCriteriaTerminationDate($query,$ar_date);
				//$this->var['employees'] = G_Employee_Finder::searchActiveByFirstnameAndLastnameAndEmployeeCode($query);
			}
		} else {
			$this->var['checked']   = '';
            $total_records = G_Employee_Helper::countAllActiveByDate($start_date);
			$this->var['employees'] = $records = G_Employee_Finder::findAllActive($start_date, "{$start_record}, {$per_page}");
		}

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

		$this->var['action'] = url('attendance/manage');
		$this->view->setTemplate('template.php');
		$this->var['page_title'] = '<a href="'. url('attendance') .'">Attendance</a>';

        $cutoff_id 		 = Utilities::decrypt($_GET['hpid']);
        //$previous_cutoff = G_Cutoff_Period_Finder::findPreviousByCutoffId($cutoff_id);
        //$next_cutoff     = G_Cutoff_Period_Finder::findNextByCutoffId($cutoff_id);
        $from_date = $_GET['from'];
       	$to_date   = $_GET['to'];
       	$eid       = $_GET['hpid'];

       /* $c  = new G_Cutoff_Period();
		$c->setId($cutoff_id);
		$next_cutoff_data = $c->getNextCutOff();
		$previous_cutoff_data = $c->getPreviousCutOff();*/

		$date = $from_date;
		$c = new G_Cutoff_Period();
		$previous_cutoff_data = $c->getPreviousCutOffByDate($date);
		$next_cutoff_data = $c->getNextCutOffByDate($date);

		/*$next_from = $next_cutoff_data['period_start'];
		$next_to   = $next_cutoff_data['period_end'];
		$next_id   = Utilities::encrypt($next_cutoff_data['id']);*/

		$next_from = $next_cutoff_data['start_date'];
		$next_to   = $next_cutoff_data['end_date'];
		$next_id   = Utilities::encrypt($next_cutoff_data['eid']);

		if( !empty($next_from) ){
			$this->var['next_cutoff_link'] = url("attendance/manage?from={$next_from}&to={$next_to}&hpid={$next_id}");
		}else{
			$this->var['next_cutoff_link'] = url("attendance/manage?from={$from_date}&to={$to_date}&hpid={$eid}");
		}

		/*$previous_from = $previous_cutoff_data['period_start'];
		$previous_to   = $previous_cutoff_data['period_end'];
		$previous_id   = Utilities::encrypt($previous_cutoff_data['id']);	*/	

		$previous_from = $previous_cutoff_data['start_date'];
		$previous_to   = $previous_cutoff_data['end_date'];
		$previous_id   = $previous_cutoff_data['eid'];

		if( !empty($previous_from) ){
			$this->var['previous_cutoff_link'] = url("attendance/manage?from={$previous_from}&to={$previous_to}&hpid={$previous_id}");
		}else{
			$this->var['previous_cutoff_link'] = url("attendance/manage?from={$from_date}&to={$to_date}&hpid={$eid}");
		}

        /*if ($previous_cutoff) {
            $previous_from = $previous_cutoff->getStartDate();
            $previous_to   = $previous_cutoff->getEndDate();
            $previous_id   = Utilities::encrypt($previous_cutoff->getId());
            $this->var['previous_cutoff_link'] = url("attendance/manage?from={$previous_from}&to={$previous_to}&hpid={$previous_id}");
        }

        if ($next_cutoff) {
            $next_from = $next_cutoff->getStartDate();
            $next_to   = $next_cutoff->getEndDate();
            $next_id   = Utilities::encrypt($next_cutoff->getId());
            $this->var['next_cutoff_link'] = url("attendance/manage?from={$next_from}&to={$next_to}&hpid={$next_id}");
        }*/

        $all_payroll_years 				= G_Cutoff_Period_Helper::sqlGetAllExistYearTags();
        $this->var['all_cutoff_years'] 	= $all_payroll_years;
        $this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_timesheet');
		$this->view->render('attendance/manage.php',$this->var);
	}	
	
	function manage() {
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTipsy();
		Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();		

		$frequency_id = 1;
		$employee_ids = array();
  $employee_ids_qry = " e.id IN () AND ";

  if( isset($_GET['selected_frequency']) ) {
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
			else if ($frequency_id == 3) {
				$cutoff_data  	   = G_Monthly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			else {
				$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}

			if($cutoff_data) {
				$from 	   = $cutoff_data->getStartDate();
				$to        = $cutoff_data->getEndDate();
				$cutoff_id = $cutoff_data->getId();
			}
		} else {
			$from = $_GET['from'];
			$to   = $_GET['to'];
			$this->var['cutoff_selected'] = $from . "/" . $to;
		}

  $s = G_Employee_Basic_Salary_History_Finder::findByDateAndFrequency($to, $frequency_id);

  foreach ($s as $key => $data) {
   $employee_ids[] = $data->employee_id;
  }

  if (count($employee_ids) > 0) {
   $employee_ids_qry = " e.id IN (".implode (",", $employee_ids).") AND ";
  }


		$ar_date['from'] = $from;
		$ar_date['to']   = $to;
		$this->var['frequency_id'] = $frequency_id;
		
		$this->var['page_title'] = 'Attendance';
		$this->var['start_date'] = $start_date = $from;
		$this->var['end_date']   = $to;
		$this->var['query'] 	 = $query = $_GET['query'];
		
        $per_page = 10;
        $page_number = (int) $_GET['pageID'];
        if ($page_number > 0) {
            $page_number--;
            $start_record = $page_number * $per_page;
        } else {
            $start_record = $page_number;
        }
		
		if ($query != '') {
			if($_GET['s_exact']){
				$this->var['checked']   = 'checked="checked"';
				$this->var['employees']   = G_Employee_Finder::searchActiveByExactFirstnameAndLastnameAndEmployeeCodeWithCriteriaTerminationDate($query,$ar_date, $employee_ids_qry);
			}else{
				$this->var['employees'] = G_Employee_Finder::searchAllByFirstnameAndLastnameAndEmployeeCodeAndDepartmentNameAndSection($query, $employee_ids_qry);
			}
		} else {
			$this->var['checked']   = '';
            $total_records = G_Employee_Helper::countAllActiveByDate($start_date, "", $employee_ids_qry);
			$this->var['employees'] = $records = G_Employee_Finder::findAllActive($start_date, "{$start_record}, {$per_page}", "", $employee_ids_qry);
		}

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

		$this->var['action'] = url('attendance/manage');
		$this->view->setTemplate('template.php');
		$this->var['page_title'] = '<a href="'. url('attendance') .'">Attendance</a>';

        $from_date = $from;
       	$to_date   = $to;
       	$eid       = $cutoff_id;

		$count_employee_notifications = G_Notifications_Helper::countTotalAttendanceNotifications();
		//$count_employee_notifications = 1;

		$this->var['is_enable_popup_notification']    = true;
		$this->var['count_attendance_notifications']  = $count_attendance_notifications; 		

		$this->var['year_selected']     = $_GET['year_selected'];
        $all_payroll_years 				= G_Cutoff_Period_Helper::sqlGetAllExistYearTags();
        $this->var['all_cutoff_years'] 	= $all_payroll_years;
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_timesheet');
		$this->var['employee_break_logs_summary_helper'] = new G_Employee_Break_Logs_Summary_Helper();
		$this->view->render('attendance/manage.php',$this->var);
	}
	
	function face_recognition_timesheet_generator() {
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();			
		$this->var['page_title'] = 'Attendance';
		$this->var['action'] = url('attendance/excel_face_recognition_timesheet_generator');
		$this->view->setTemplate('template.php');
		$this->var['page_title'] = '<a href="'. url('attendance') .'">Attendance</a>';
		$this->view->render('attendance/facerecognition_timesheet_generator.php',$this->var);	
	}
	
	function excel_face_recognition_timesheet_generator() {
		$this->view->noTemplate();
		$this->view->render('attendance/excel_facerecognition_timesheet_generator.php',$this->var);	
	}
	
	function attendance_logs() {	
		/*$this->var['page_title'] = 'Attendance Logs';
		$this->var['periods'] = G_Payslip_Helper::getPeriods();
		$this->view->setTemplate('template.php');
		$this->view->render('attendance/period_attendance_logs.php', $this->var);*/

        $now = date('Y-m-d');
        $p = G_Cutoff_Period_Finder::findByDate($now);
        
        if ($p) {
            $hpid = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date = $p->getEndDate();
        }

        redirect("attendance/attendance_logs_period?from={$from_date}&to={$to_date}&hpid={$hpid}");
	}
	
	function attendance_logs_period() {
		if(!empty($_GET)) {
			Jquery::loadMainKdatatable();
			Jquery::loadMainTipsy();
			Jquery::loadMainTextBoxList();
			Jquery::loadMainInlineValidation2();
			Jquery::loadMainJqueryFormSubmit();
            Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
            Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
			Loader::appMainStyle('assets/datatable/datatable-paginator.css');

			$btn_import_dtr_config = array(
        		'module'				=> 'hr',
        		'parent_index'			=> 'attendance',
        		'child_index'			=> 'attendance_daily_time_record',
        		'href' 					=> 'javascript:void(0);',
        		'onclick' 				=> 'javascript:importTimesheet();',
        		'id' 					=> '',
        		'class' 				=> 'gray_button float-right',
        		'icon' 					=> '<i class="icon-excel icon-custom"></i>',
        		'additional_attribute' 	=> '',
        		'caption' 				=> 'Import DTR'
        		); 

        	$btn_add_attendance_log_config = array(
        		'module'				=> 'hr',
        		'parent_index'			=> 'attendance',
        		'child_index'			=> 'attendance_daily_time_record',
        		'href' 					=> 'javascript:void(0);',
        		'onclick' 				=> 'javascript:addAttendanceLog();',
        		'id' 					=> '',
        		'class' 				=> 'gray_button float-right',
        		'icon' 					=> '<i class="icon-plus"></i>',
        		'additional_attribute' 	=> '',
        		'caption' 				=> 'Add Attendance Log'
        		); 

        	$btn_sync_attendance_config = array(
        		'module'				=> 'hr',
        		'parent_index'			=> 'attendance',
        		'child_index'			=> 'attendance_daily_time_record',
        		'href' 					=> 'javascript:void(0);',
        		'onclick' 				=> 'javascript:modalSyncAttendanceData();',
        		'id' 					=> '',
        		'class' 				=> 'gray_button float-right',
        		'icon' 					=> '<i class="icon-refresh"></i>',
        		'additional_attribute' 	=> '',
        		'caption' 				=> 'Synchronize Attendance'
        		); 

        	$this->var['is_enable_popup_notification'] = true;
        	$this->var['is_dtr_notification'] 		   = true;
			$count_employee_dtr_notifications 		   = G_Notifications_Helper::countTotalAttendanceNotifications();
			$this->var['count_employee_dtr_notifications'] = $count_employee_dtr_notifications; 	        	
        	
        	$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_daily_time_record');
        	$this->var['btn_add_attendance_log'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_attendance_log_config);
        	$this->var['btn_sync_attendance_log'] 	= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_sync_attendance_config);
        	$this->var['btn_import_dtr'] 			= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_import_dtr_config);
			$this->var['from']       				= $_GET['from'];
			$this->var['to']         				= $_GET['to'];
			$this->var['page_title'] 				= 'Daily Time Records';

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
			$this->var['devices'] 					= G_Employee_Helper::getAllDeviceNo();

			$this->view->setTemplate('template.php');
			$this->view->render('attendance/attendance_logs.php', $this->var);


		} else {
			redirect('attendance_logs');
		}
				
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
	
	function convert_employee_id()
	{
		if($_POST['emp_sel']){
			$arrId    = explode(",",$_POST['emp_sel']);	
			$arr_size = count($arrId);				
			$counter  = 1;
				
			foreach($arrId as $key => $value){
				$did = Model::safeSql(Utilities::decrypt($value));				
				if($counter < $arr_size){
				  $a_id .= $did . "-";
				}else{$a_id .= $did;}
				$counter++;
			}
			$json['did'] = $a_id;
			echo json_encode($json);
		}
	}

	function _load_edit_timesheet_inout()
	{		
		if($_GET['eid'] && $_GET['date']){
			$user_id = Utilities::decrypt($_GET['eid']); 
			$e = G_Employee_Finder::findById($user_id);
			if( $e ){
				$employee_code = $e->getEmployeeCode();
				$date 	 = date("Y-m-d",$_GET['date']);			
				$fp      = new G_Fp_Attendance_Logs($date);
				$data    = $fp->setEmployeeCode($employee_code)->getEmployeeLogs()->groupData()->getProperty('logs');					
				$this->var['eid']      = Utilities::encrypt($employee_code);
				$this->var['log_date'] = date("M d, Y",strtotime($date));
				if( $data ){
					$this->var['logs_in']  = $data[$date]['in'];
					$this->var['logs_out'] = $data[$date]['out'];
					$this->view->render('attendance/forms/edit_timesheet_inout.php',$this->var);	
				}else{
					$this->var['attendance_date'] = $date;					
					$this->view->render('attendance/forms/add_timesheet_inout.php',$this->var);	
				}
			}else{
				echo "Employee record does not exits!";
			}
		}
	}
	
	function _load_attendance_logs_dt() 
	{	
		sleep(1);
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_daily_time_record');

		if(!empty($_POST['sortColumn'])){
			$order_by = "ORDER BY " . $_POST['sortColumn'] . " " . $_POST['orderBy'];		
		}else{
			$order_by = "ORDER BY date desc, time desc ";		
		}
		
		if(!empty($_POST['displayStart'])){
			$limit  = "LIMIT " . $_POST['displayStart'] . "," . $_POST['limit'];
		}else{
			 $limit = "LIMIT " . $_POST['limit'];
		}
		if($_POST['emp_sel']){
			$arr_names = explode(",",$_POST['emp_sel']);
			foreach($arr_names as $key => $field)
			$arr_names[$key]=Utilities::decrypt($field); 
		}
		
		if($_POST['error_type'] == G_Attendance_Log::INCOMPLETE_SWIPE){			
			if($_POST['emp_sel']){	
				$data  = G_Attendance_Log_Finder::findAllWithoutInOutLogsByPeriodAndEmployeeIdWithLimitDebug($_POST['emp_sel'],$_POST['date_from'], $_POST['date_to'],$order_by,$limit);			
				$count = ceil(G_Attendance_Log_Helper::countAllIncompleteSwipeByPeriodAndEmployeeIdDebug($_POST['emp_sel'],$_POST['date_from'], $_POST['date_to']) / $_POST['limit']);							
			}else{				
				$data  = G_Attendance_Log_Finder::findAllWithoutInOutLogsByPeriodAndEmployeeIdWithLimitDebug('',$_POST['date_from'], $_POST['date_to'],$order_by,$limit);							
				$count = ceil(G_Attendance_Log_Helper::countAllIncompleteSwipeByPeriodAndEmployeeIdDebug('',$_POST['date_from'], $_POST['date_to']) / $_POST['limit']);			
			}
		}elseif($_POST['error_type'] == G_Attendance_Log::MULTIPLE_SWIPE){
			if($_POST['emp_sel']){					
				$data  = G_Attendance_Log_Finder::findAllWithMultipleLogsByPeriodAndEmployeeIdWithLimit($_POST['emp_sel'],$_POST['date_from'], $_POST['date_to'],$order_by,$limit);			
				$count = ceil(G_Attendance_Log_Helper::countAllWithMultipleLogsAndEmployeeIdByPeriod($_POST['emp_sel'],$_POST['date_from'], $_POST['date_to']) / $_POST['limit']);
			}else{				
				$data  = G_Attendance_Log_Finder::findAllWithMultipleLogsByPeriodWithLimit($_POST['date_from'], $_POST['date_to'],$order_by,$limit);			
				$count = ceil(G_Attendance_Log_Helper::countAllWithMultipleLogsByPeriod($_POST['date_from'], $_POST['date_to']) / $_POST['limit']);
			}
		}else{
			// if($_POST['emp_sel']){				
			// 	$data  = G_Attendance_Log_Finder::findAllByPeriodAndEmployeeIdWithLimit($_POST['emp_sel'],$_POST['date_from'],$_POST['date_to'],$order_by,$limit);			
			// 	$count = ceil(G_Attendance_Log_Helper::countAllByPeriodAndEmployeeId($_POST['emp_sel'], $_POST['date_from'], $_POST['date_to']) / $_POST['limit']);			
			// }else{				
			// 	$data  = G_Attendance_Log_Finder::findAllByPeriodWithLimit($_POST['date_from'], $_POST['date_to'],$order_by,$limit);			
			// 	$count = ceil(G_Attendance_Log_Helper::countAllByPeriod($_POST['date_from'], $_POST['date_to']) / $_POST['limit']);			
			// }
			$employee_ids = $_POST['emp_sel'] ? explode(",",$_POST['emp_sel']) : array();
			if(count($employee_ids)>0){
				foreach($employee_ids as $key => $field)
				$employee_ids[$key]=Utilities::decrypt($field); 
			}
			
			$log_ids = array();

			$data  = G_Attendance_Log_Finder::findAllByPeriodWithBreakLogs($_POST['date_from'],$_POST['date_to'],$employee_ids,$order_by,$limit,$_POST['filter'],[],$_POST['device_id']);
			$filtered_data = G_Attendance_Log_Helper::getLogIdsWithErrorFilter($data, $_POST['filter'], $_POST['date_from'],$_POST['date_to']);	

			if ($_POST['filter'] == 'multiple-in') {
				$log_ids = array(
					'main' => $filtered_data['multiple_in_log_ids'],
					'break' => array()
				);
			}
			elseif ($_POST['filter'] == 'multiple-out') {
				$log_ids = array(
					'main' => $filtered_data['multiple_out_log_ids'],
					'break' => array()
				);
			}
			elseif ($_POST['filter'] == 'incomplete-break-logs') {
				$log_ids = array(
					'main' => array(),
					'break' => $filtered_data['incomplete_break_log_ids']
				);
			}
			elseif ($_POST['filter'] == 'early-break-out') {
				$log_ids = array(
					'main' => array(),
					'break' => $filtered_data['early_break_out_log_ids']
				);
			}
			elseif ($_POST['filter'] == 'late-break-in') {
				$log_ids = array(
					'main' => array(),
					'break' => $filtered_data['late_break_in_log_ids']
				);
			}
			elseif ($_POST['filter'] == 'no-break-logs') {
				$log_ids = array(
					'main' => $filtered_data['no_break_logs_ids'],
					'break' => array()
				);
			}

			if (count($log_ids) > 0) {
				$data  = G_Attendance_Log_Finder::findAllByPeriodWithBreakLogs($_POST['date_from'],$_POST['date_to'],$employee_ids,$order_by,$limit,$_POST['filter'], $log_ids);	
			}

			$count = count($data);
			// $count = ceil(G_Attendance_Log_Helper::countAllByPeriodWithBreakLogs($_POST['emp_sel'], $_POST['date_from'], $_POST['date_to']) / $_POST['limit']);	
		}
		
		//utilities::displayArray($data);exit();
		//Construct table content
		if($data){
			foreach($data as $d){

				$remarks = $d->getRemarks();
				//get device no.
				$remarks2 = explode(':', $remarks);
				$machine_no = $remarks2[1];

				$device_info = G_Attendance_Log_Finder::findDevice($machine_no);

				if($device_info){
					//utilities::displayArray($device_info->device_name);exit();
					$device_name = $device_info->device_name;
				}
				

				$employeeId = G_Employee_Finder::findByEmployeeCode($d->getEmployeeCode())->id; 
				$json['table'] .= '<tr>';
					$json['table'] .= '<td>';
						$json['table'] .= '<input type="checkbox" name="dtrChk[]" onchange="javascript:checkUncheck();" value="'. $d->getId() .'" data-type="'. $d->getType() .'" style="margin-left: 5px;">';
					$json['table'] .= '</td>';

					$json['table'] .= '<td>';
						$json['table'] .= $d->getEmployeeCode();
					$json['table'] .= '</td>';
					
					$json['table'] .= '<td>';
						$json['table'] .= $d->getEmployeeName() . (G_Employee_Finder::findByIdIsArchive($employeeId) ? '<p class="text-error" style="margin-bottom:0;"><em>Archived</em></p>' : '');
					$json['table'] .= '</td>';
					
					$json['table'] .= '<td>';
						$json['table'] .= Tools::convertDateFormat($d->getDate());
					$json['table'] .= '</td>';
					
					$json['table'] .= '<td>';
						$json['table'] .= Tools::timeFormat($d->getTime());
					$json['table'] .= '</td>';
					
					$json['table'] .= '<td>';
						$json['table'] .= $d->getType();
					$json['table'] .= '</td>';
					

					$json['table'] .= '<td>';
						//$json['table'] .=  (isset($device_name) && !empty($device_name) ? $device_name : null);
						$json['table'] .=  (isset($remarks) && !empty($remarks) ? explode(':', str_replace(' ', '_', $remarks))[1].'-'.$device_name : null);
					$json['table'] .= '</td>';

					if($permission_action == Sprint_Modules::PERMISSION_02) {
						$json['table'] .= '<td>';
								$json['table'] .= "<a class=\"btn btn-small\" href=\"javascript:void(0);\" onclick=\"editAttendanceLog('" . Utilities::encrypt($d->getId()) . "', '" . $d->getType() . "');\"><i class=\"icon-pencil icon-fade\"></i> Edit</a><a class=\"btn btn-small logs-delete-btn\" href=\"javascript:void(0);\" onclick=\"deleteDTRLog('" . Utilities::encrypt($d->getId()) . "', '" . $d->getType() . "');\"><i class=\"icon-trash icon-fade\"></i> Delete</a>";	
						$json['table'] .= '</td>';
					}
					
				$json['table'] .= '</tr>';			
			}
		}else{
			$json['table'] = 'No data found';
		}
		//
		
		//Construct Paginator			
			if($_POST['paginatorIndex']){
				$paginator_index = $_POST['paginatorIndex'];
			}else{
				$paginator_index = 1;
			}
			
			if($count > 0){				
				$json['paginator'] .= '<div id="yui-dt0-paginator0" class="yui-dt-paginator yui-pg-container">';	
				$skip_counting = 0;
				
				//Add First BTN
				//Validate if first record for first record
				if($paginator_index == 1){
					$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first">First</span>';
				}else{
					$json['paginator'] .= '<span id="yui-pg0-0-first-span11" class="yui-pg-first"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',1)">First</a></span>';
				}
				//
				
				//Add Prev Btn
				for($i=1; $i<=$count; $i++){
					if($paginator_index == $i){	$s_skip_counting = $skip_counting - $_POST['limit'];}
					$skip_counting += $_POST['limit'];
				}
				
				if($paginator_index == 1){
					$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous">Previous</span>';
				}else{
					$prev_record = $paginator_index - 1;					
					$json['paginator'] .= '<span id="yui-pg0-0-prev-span13" class="yui-pg-previous"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $prev_record . ');">Previous</a></span>';
				}
				//
				
				//Construct Paginator index
				$skip_counting = 0;
				for($i=1; $i<=$count; $i++){
					if($paginator_index == $i){	
						$s_skip_counting = $skip_counting + $_POST['limit'];			
						//$json['paginator'] .= '<li class="paginator_selected">' . $i . '</li>';		
					}else{
						//$json['paginator'] .= '<li><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $skip_counting . ',' . $i . ');">' . $i . '</a></li>';		
					}
					
					$skip_counting += $_POST['limit'];
				}
				//
				
				//Add Next Btn
				if($paginator_index == $count){
					$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next">Next</span>';
				}else{
					$next_record = $paginator_index + 1;
					$json['paginator'] .= '<span id="yui-pg0-0-next-span16" class="yui-pg-next"><a href="javascript:void(0);" onclick="javascript:gotoPage(' . $s_skip_counting . ',' . $next_record . ');">Next</a></span>';
				}
				//
				
				
				//Add Last BTN
				//Validate if last record
				if($paginator_index == $count){
					$json['paginator'] .= '<span id="yui-pg0-0-last-span18" class="yui-pg-last">Last</span>';
				}else{
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
	
	function _load_server_attendace_logs_dt() 
	{		
		Utilities::ajaxRequest();
		
		$date_from  = $_GET['date_from'];
		$date_to    = $_GET['date_to'];
		$error_type = $_GET['error_type'];
		
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_ATTENDANCE_LOG);
		$dt->setCustomField(array('employee_code' => 'employee_code','name' => 'firstname,lastname'));		
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_ATTENDANCE_LOG . ".employee_id = e.id");
		
		if($error_type == G_Attendance_Log::INCOMPLETE_SWIPE){
			
		}elseif($error_type == G_Attendance_Log::MULTIPLE_SWIPE){
			$dt->setCondition();						
		}else{
			$dt->setCondition(G_ATTENDANCE_LOG .'.date >= "' . $date_from . '" AND ' . G_ATTENDANCE_LOG. '.date <= "' . $date_to . '"');
		}
		
		$dt->setColumns('date,time,type');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);		
		$dt->setNumCustomColumn(0);		
		echo $dt->constructDataTable();
	}
	
	function attendance_logs_sub() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		Jquery::loadMainTextBoxList();
				
		$from = $_GET['from'];
		$to = $_GET['to'];
		$error_type = $_GET['error_type'];
		$employee_ids = $_GET['employees_autocomplete'];
		
		if ($from != '' && $to == '') {
			$to = $from;
		} else if ($from == '' && $to == '') {
			$from = date('Y-m-d');
			$to = date('Y-m-d');	
		}
		
		$this->var['from'] = $from;
		$this->var['to'] = $to;
		$this->var['error_type'] = $error_type;
		
		$this->var['action'] = url('attendance/attendance_logs');
		$this->var['page_title'] = 'Attendance Logs';
		
		$this->var['employee_names'] = G_Employee_Helper::getAllEmployeeNames();
		
		switch ($error_type):
		case 'multiple_swipes':
			$this->var['title'] = 'Multiple Swipes';
			$this->var['logs'] = G_Attendance_Log_Finder::findAllWithMultipleLogsByPeriod($from, $to);
		break;
		case 'no_time_in':
			$this->var['title'] = 'No Time In';
			$this->var['logs'] = G_Attendance_Log_Finder::findAllWithoutInLogsByPeriod($from, $to);
		break;
		case 'no_time_out':
			$this->var['title'] = 'No Time Out';
			$this->var['logs'] = G_Attendance_Log_Finder::findAllWithoutOutLogsByPeriod($from, $to);
		break;
		default:
			$this->var['title'] = 'Logs';
			$this->var['logs'] = G_Attendance_Log_Finder::findAllByPeriod($from, $to);
		endswitch;
		
		if ($_GET['download'] == 1) {
			$this->view->render('attendance/attendance_logs_download.php', $this->var);
		} else {
			$this->view->setTemplate('template.php');
			$this->view->render('attendance/attendance_logs.php', $this->var);
		}
	}
	
	function download_logs() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		Jquery::loadMainTextBoxList();
				
		$from 		  = $_GET['from'];
		$to 		  = $_GET['to'];
		$error_type   = $_GET['error_type'];
		$employee_ids = $_GET['employees_autocomplete'];
		$device_id = $_GET['device_id'];
		if ($from != '' && $to == '') {
			$to = $from;
		} else if ($from == '' && $to == '') {
			$from = date('Y-m-d');
			$to = date('Y-m-d');	
		}
		
		if($_GET['emp']){
			$arrId    = explode("-",$_GET['emp']);	
			$arr_size = count($arrId);				
			$counter  = 1;
				
			foreach($arrId as $key => $value){
				$did = Model::safeSql(Utilities::encrypt($value));				
				if($counter < $arr_size){
				  $a_id .= $did . ",";
				}else{$a_id .= $did;}
				$counter++;
			}						
		}
		
		$this->var['action'] = url('attendance/attendance_logs');
		$this->var['page_title'] = 'Attendance Logs';
		
		$this->var['employee_names'] = G_Employee_Helper::getAllEmployeeNames();
		
		switch ($error_type):
		case 'multiple_swipes':
			$this->var['title'] = 'Multiple Swipes';
			if($_GET['emp']){
				$this->var['logs'] = G_Attendance_Log_Finder::findAllWithMultipleLogsByPeriodAndEmployeeIdWithLimit($a_id, $from, $to, "", "", $device_id);
			}else{
				$this->var['logs'] = G_Attendance_Log_Finder::findAllWithMultipleLogsByPeriod($from, $to, $device_id);
			}
		break;
		case 'no_time_in_out':
			$this->var['title'] = 'No Time In / Time Out';
			if($_GET['emp']){
				$this->var['logs'] = G_Attendance_Log_Finder::findAllWithoutInOutLogsByPeriodAndEmployeeIdWithLimitDebug($a_id, $from, $to);
			}else{
				$this->var['logs'] = G_Attendance_Log_Finder::findAllWithoutInOutLogsByPeriod($from, $to);
			}
		break;		
		default:
			$this->var['title'] = 'Logs';
			if($_GET['emp']){
				$this->var['logs'] = G_Attendance_Log_Finder::findAllByPeriodAndEmployeeIdWithLimit($a_id,$from, $to, "", "", "", $device_id);
			}else{
				$this->var['logs'] = G_Attendance_Log_Finder::findAllByPeriod($from, $to, $device_id);				
			}
		endswitch;
		
		$this->var['from']      = $from;
		$this->var['to']         = $to;
		$this->var['error_type'] = $error_type;
		
		$this->view->render('attendance/attendance_logs_download.php', $this->var);
	}


	function error_no_time_in_out() {
		$from = $_GET['from'];
		$to = $_GET['to'];
		
		if ($from != '' && $to == '') {
			$to = $from;
		} else if ($from == '' && $to == '') {
			$from = date('Y-m-d');
			$to = date('Y-m-d');	
		}
		
		$this->var['from'] = $from;
		$this->var['to'] = $to;
		
		$this->var['page_title'] = 'No Time In or Out';
		$this->var['errors'] = G_Attendance_Error_Finder::findAllNoTimeInAndOutNotFixedByPeriod($from, $to);				
		$this->view->setTemplate('template.php');
		$this->view->render('attendance/error_no_time_in_out.php', $this->var);
	}	
	
	function show_attendance() {
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();	
		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
        Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');
		Jquery::loadMainBootStrapDropDown();
			
		if( isset($_GET['cutoff_period']) ) {

			$cutoff_period_arr = explode("/", $_GET['cutoff_period']);
			$period_start      = $cutoff_period_arr[0];
			$period_end   	   = $cutoff_period_arr[1];
			$this->var['cutoff_selected'] = $period_start."/".$period_end;

			if($_GET['frequency_id']){
				$frequency_id = $_GET['frequency_id'];
			}else{
				$frequency_id = $_GET['selected_frequency'];
			}


			if($frequency_id == 1){
						$cutoff_data  	   = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
				
			}else{
			
			$cutoff_data      = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
			}
			

			if($cutoff_data) {
				$from 	   = $cutoff_data->getStartDate();
				$to        = $cutoff_data->getEndDate();
				$cutoff_id = $cutoff_data->getId();
			}


			$this->var['employee_id'] = $_GET['employee_id'];
			$employee_id 			  = Utilities::decrypt($_GET['employee_id']);
			$this->var['start_date']  = $from;
			$this->var['end_date']    = $to;	
			$e = $this->var['e'] = G_Employee_Finder::findByIdBothArchiveAndNot($employee_id);			
		} else {
			$this->var['employee_id'] = $_GET['employee_id'];
			$employee_id 			  = Utilities::decrypt($_GET['employee_id']);
			$this->var['start_date']  = $_GET['from'];
			$this->var['end_date']    = $_GET['to'];		
			$hash 					  = $_GET['hash'];
			Utilities::verifyHash($employee_id, $hash);	
			$e = $this->var['e'] = G_Employee_Finder::findByIdBothArchiveAndNot($employee_id);			
		}

		$this->var['frequency_id'] = $_GET['selected_frequency'];
		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'attendance','attendance_timesheet');
		$this->var['page_title'] = '<a href="'. url('attendance') .'">Attendance</a>';
		$this->var['module_title'] =  ': <b class="mplynm">' .$e->getName(). ' ('. $e->getEmployeeCode() .')</b>';
		$this->view->setTemplate('template.php');
		$this->view->render('attendance/show_attendance.php', $this->var);
	}
	
	function _edit_attendance() {
		$date = $_POST['date'];
		$employee_id = Utilities::decrypt($_POST['employee_id']);
		$e = Employee_Factory::get($employee_id);
		
		$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
		if (!$a) {
			$a = new G_Attendance;
			$a->setDate($date);
		}
		$leave_id = (int) $_POST['attendance_type'];
		$a->setLeaveId($leave_id);
		$t = $a->getTimesheet();	
		
		if (!$t) {
			$t = new G_Timesheet;
		}				
		if ($leave_id) {
			$a->setAsLeave();
			$a->setAsAbsent();
			$a->setAsNotRestday();
			$a->setAsPaid();
		} else {
			switch ($_POST['attendance_type']):
				case 'present':
					$a->setAsPresent();						
					$a->setAsNotRestday();
					$a->setAsNotLeave();		
				break;
				case 'absent':				
					$a->setAsAbsent();
					$a->setAsNotRestday();
					$a->setAsNotLeave();
				break;
/*				case 'restday_present':
					$a->setAsPresent();
					$a->setAsRestday();
					$a->setAsNotLeave();				
				break;*/
				case 'restday':					
					$this->switchRestDay($date,$e); //<-- orig
					$a->setAsRestday();
					//$a->setAsAbsent();
					$a->setAsNotLeave();
				break;
			endswitch;
		}
		if ($_POST['is_paid']) {
			$a->setAsPaid();	
		} else {
			$a->setAsNotPaid();	
		}
		$a->setTimesheet($t);
		$is_saved = $a->recordToEmployee($e);
		
		//G_Attendance_Helper::updateAttendance($e, $date);
		
		$return['is_saved'] = $is_saved;
		if ($is_saved) {			
			$return['message'] = 'Attendance has been saved.';
		} else {
			$return['message'] = 'There was a problem saving the attendance. Please contact the administrator.';
		}
		echo json_encode($return);
	}

	function _load_delete_logs() 
	{
		$json['is_success'] = false;
		$json['message']    = "Cannot find record";
		if(!empty($_POST) && isset($_POST['id'])) {
			$id = Utilities::decrypt($_POST['id']);	

			
			$type = isset($_POST['type']) ? $_POST['type'] : '';
			$fp = false;

			if ($type == '' || strtolower($type) == strtolower(G_Attendance_Log::TYPE_IN) || strtolower($type) == strtolower(G_Attendance_Log::TYPE_OUT)) {
				$fp = new G_Fp_Attendance_Logs();
			}
			else {
				$fp = new G_Employee_Break_Logs();
			}

			if ($fp) {
				$fp->setId($id);
			}

			$json = $fp->deleteLog();	
		}
		
		echo json_encode($json);
	}
	
	function switchRestDay($date,$e)
	{	
		$date     = date("Y-m-d",strtotime($date));
		$year     = date("Y",strtotime($date));
		$day      = date("D",strtotime($date));
		$wkc	  = (int) date("W",strtotime($date));
		$wkc = $wkc - 1;		
		if($day == 'Sun'){
			$wkc      = $wkc + 1;		
		}				
		
		//$time_in  = date('H:i:s', strtotime('8AM'));
		//$time_out = date('H:i:s', strtotime('6PM'));
		
		if($e){			
			
			$week_dates = G_Attendance_Helper::getDatesByEmployeeNumberAndWeekNumber($e,$wkc);			
			$e_date     = G_Attendance_Finder::findByEmployeeAndDate($e,$date);			
			//print_r($week_dates);
			//exit;	
				if($e_date){
					$t = $e_date->getTimesheet();
					$cpd['is_restday'] = $e_date->isRestday();
					$cpd['is_paid']    = $e_date->isPaid();
					$cpd['is_present'] = $e_date->isPresent();
					$cpd['date']	   = $e_date->getDate();
					
					$cpd['time_in']    = date('H:i:s', strtotime($t->getTimeIn()));
					$cpd['time_out']   = date('H:i:s', strtotime($t->getTimeOut()));
				}
			
			
			 //print_r($e_date);
			
			
			foreach($week_dates as $key => $value){						
				$rd_tbl = G_Restday_Finder::findByEmployeeAndDate($e,$value['date_attendance']);		
				
				if($value['is_restday'] == 1){ //Old Rest Day					
					if($rd_tbl){ //Delete old entry in restday table
					
						 //Check if date exist in schedule specific table
						 $s = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, $value['date']);	
						 if($s){
							//echo '<br>Deleted Specific Schedule <br>';
							//$s->delete();										
						 }else{
							$s = new G_Schedule_Specific;
							$s->setDateStart($value['date']);
							$s->setDateEnd($value['date']);
							$s->setTimeIn($cpd['time_in']);
							$s->setTimeOut($cpd['time_out']);
							$s->setEmployeeId($e->getId());
							$s->save();
						 }
						
						 $rd_tbl->delete();		
						 $is_removed = G_Attendance_Helper::updateAttendance($e,$value['date_attendance']);	
					}
					
					$a2 = G_Attendance_Finder::findByEmployeeAndDate($e, $value['date_attendance']);										
					
					if($a2){						
						$a2->setAsNotRestday();	
						$a_day      = date("D",strtotime($value['date_attendance']));	
											
						if($a_day == 'Sun'){ //If Sunday set to absent
							//$a2->setAsNotPaid();	
							//$a2->setAsAbsent();	
							/*if($cpd['is_present'] == 1){
								$a2->setAsPresent();	
							}else{
								$a2->setAsAbsent();	
							}*/
							
							//$a2->setAsPresent();	
						}							
						/*if($cpd['is_paid'] == 1){
							$a2->setAsPaid();	
						}else{
							$a2->setAsNotPaid();	
						}
						
						if($cpd['is_present'] == 1){
							$a2->setAsPresent();	
						}else{
							$a2->setAsAbsent();	
						}*/
						
						$a2->recordToEmployee($e);															
						//$is_updated = G_Attendance_Helper::updateAttendance($e,$value['date_attendance']);	
					}					
				}
				
				if($value['date_attendance'] == $date){ //New Rest Day
					//Set new restday in attendance table
					$a2 = G_Attendance_Finder::findByEmployeeAndDate($e, $value['date_attendance']);					
					if($a2){
						$a2->setAsRestday();					
						$a2->recordToEmployee($e);																
						$is_updated = G_Attendance_Helper::updateAttendance($e,$value['date_attendance']);	
					}
					//
					
					//Add new restday in rest day table					
					$o = new G_Restday;
					$o->setDate($value['date_attendance']);
					$o->setTimeIn($cpd['time_in']);
					$o->setTimeOut($cpd['time_out']);
					$o->setEmployeeId($e->getId());
					$o->setReason('test');		
					$o->save();
					$s = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, $value['date_attendance']);	
					if($s){
						$s->delete();
					}					
					$is_updated = G_Attendance_Helper::updateAttendance($e,$value['date_attendance']);		
					//
				}
			}
			
		}
	}
	
	function _edit_timesheet() {
		$date = $_POST['date'];
		$employee_id = Utilities::decrypt($_POST['employee_id']);
		$e = Employee_Factory::get($employee_id);
		
		$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
		if (!$a) {
			$a = new G_Attendance;
			$a->setDate($date);
		}
		$overtime_hours = Tools::convertTimeToHour((int) $_POST['overtime_hours']['hh'] .':'. (int) $_POST['overtime_hours']['mm']);		
		$late_hours = Tools::convertTimeToHour((int) $_POST['late_hours']['hh'] .':'. (int) $_POST['late_hours']['mm']);
		$legal_hours = Tools::convertTimeToHour((int) $_POST['legal_hours']['hh'] .':'. (int) $_POST['legal_hours']['mm']);
		$legal_night_hours = Tools::convertTimeToHour((int) $_POST['legal_night_hours']['hh'] .':'. (int) $_POST['legal_night_hours']['mm']);
		$night_hours = Tools::convertTimeToHour((int) $_POST['night_hours']['hh'] .':'. (int) $_POST['night_hours']['mm']);
		$overtime_hours = Tools::convertTimeToHour((int) $_POST['overtime_hours']['hh'] .':'. (int) $_POST['overtime_hours']['mm']);
		$special_hours = Tools::convertTimeToHour((int) $_POST['special_hours']['hh'] .':'. (int) $_POST['special_hours']['mm']);
		$special_night_hours = Tools::convertTimeToHour((int) $_POST['special_night_hours']['hh'] .':'. (int) $_POST['special_night_hours']['mm']);
		$undertime_hours = Tools::convertTimeToHour((int) $_POST['undertime_hours']['hh'] .':'. (int) $_POST['undertime_hours']['mm']);		
		
		$t = $a->getTimesheet();
		if (!$t) {
			$t = new G_Timesheet;
		}
		$t->setScheduledTimeIn($t->getScheduledTimeIn());
		$t->setScheduledTimeOut($t->getScheduledTimeOut());
		$t->setTimeIn($t->getTimeIn());
		$t->setTimeOut($t->getTimeOut());
		$t->setOverTimeIn($t->getOverTimeIn());
		$t->setOverTimeOut($t->getOverTimeOut());
		$t->setTotalHoursWorked($t->getTotalHoursWorked());
		
		$t->setNightShiftHours($night_hours);
		$t->setNightShiftHoursSpecial($special_night_hours);
		$t->setNightShiftHoursLegal($legal_night_hours);
		$t->setHolidayHoursSpecial($special_hours);
		$t->setHolidayHoursLegal($legal_hours);
		$t->setOvertimeHours($overtime_hours);
		$t->setLateHours($late_hours);
		$t->setUndertimeHours($undertime_hours);		
		
		$a->setTimesheet($t);
		$is_saved = $a->recordToEmployee($e);

		$return['is_saved'] = $is_saved;
		if ($is_saved) {			
			$return['message'] = 'Attendance has been saved.';
		} else {
			$return['message'] = 'There was a problem saving the attendance. Please contact the administrator.';
		}
		echo json_encode($return);			
	}
	
	function _edit_time_in_out() {
		$employee_id = Utilities::decrypt($_POST['employee_id']);
		$e = Employee_Factory::get($employee_id);
		$date = $_POST['date'];
		$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
		if ($a) {
			$time_in = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['actual_time_in']['hh']) .':'. Tools::addLeadingZero($_POST['actual_time_in']['mm']) .' '. $_POST['actual_time_in']['am']));
			$time_out = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['actual_time_out']['hh']) .':'. Tools::addLeadingZero($_POST['actual_time_out']['mm']) .' '. $_POST['actual_time_out']['am']));
			$scheduled_time_in = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['scheduled_time_in']['hh']) .':'. Tools::addLeadingZero($_POST['scheduled_time_in']['mm']) .' '. $_POST['scheduled_time_in']['am']));
			$scheduled_time_out = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['scheduled_time_out']['hh']) .':'. Tools::addLeadingZero($_POST['scheduled_time_out']['mm']) .' '. $_POST['scheduled_time_out']['am']));			
			$t = $a->getTimesheet();
			$t->setTimeIn($time_in);
			$t->setTimeOut($time_out);
			$t->setScheduledTimeIn($scheduled_time_in);
			$t->setScheduledTimeOut($scheduled_time_out);
			
			if ($a->isRestday()) {
				$o = G_Restday_Finder::findByEmployeeAndDate($e, $date);
				if (!$o) {
					$o = new G_Restday;
				}
				$o->setDate($date);
				$o->setTimeIn($scheduled_time_in);
				$o->setTimeOut($scheduled_time_out);
				$o->setEmployeeId($e->getId());
				$o->save();
			} else {
				$ss = G_Schedule_Specific_Finder::findByEmployeeAndStartAndEndDate($e, $date, $date);
				if (!$ss) {
					$ss = new G_Schedule_Specific;
				}					
				$ss->setDateStart($date);
				$ss->setDateEnd($date);
				$ss->setTimeIn($scheduled_time_in);
				$ss->setTimeOut($scheduled_time_out);
				$ss->setEmployeeId($e->getId());
				$ss->save();
			}
			
			$a->setTimesheet($t);
			$is_saved = $a->recordToEmployee($e);
			G_Attendance_Helper::updateAttendance($e, $date);
			$return['is_saved'] = $is_saved;
		}
		if ($is_saved) {			
			$return['message'] = 'Timesheet has been saved.';
		} else {
			$return['message'] = 'There was a problem saving the timesheet. Please contact the administrator.';
		}
		echo json_encode($return);		
	}

    function _edit_actual_time() {
        $employee_id = (int) $_POST['employee_id'];
        $date = date('Y-m-d', strtotime($_POST['date']));
        $time_in = date('H:i:s', strtotime($_POST['time_in']));
        $time_out = date('H:i:s', strtotime($_POST['time_out']));

        $return['is_saved'] = false;
        $e = G_Employee_Finder::findById($employee_id);
        if ($e) {
            $is_saved = G_Attendance_Helper::recordTimeInOut($e, $date, $time_in, $time_out);
            G_Attendance_Helper::updateAttendance($e, $date);
        }

        $return['is_saved'] = $is_saved;
        echo json_encode($return);
    }
	
	function _edit_overtime_in_out() {
		$employee_id = Utilities::decrypt($_POST['employee_id']);
		$e = Employee_Factory::get($employee_id);
		$date = $_POST['date'];
		$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
		if ($a) {
			$time_in = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['actual_time_in']['hh']) .':'. Tools::addLeadingZero($_POST['actual_time_in']['mm']) .' '. $_POST['actual_time_in']['am']));
			$time_out = date('H:i:00', strtotime(Tools::addLeadingZero($_POST['actual_time_out']['hh']) .':'. Tools::addLeadingZero($_POST['actual_time_out']['mm']) .' '. $_POST['actual_time_out']['am']));
			$t = $a->getTimesheet();
			$t->setOverTimeIn($time_in);
			$t->setOverTimeOut($time_out);
						
			$a->setTimesheet($t);
			$is_saved = $a->recordToEmployee($e);
			
			$o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
			if (!$o) {
				$o = new G_Overtime;	
			}
			$o->setDate($date);
			$o->setTimeIn($time_in);
			$o->setTimeOut($time_out);
			$o->setEmployeeId($e->getId());
			$o->save();
			
			G_Attendance_Helper::updateAttendance($e, $date);
			$return['is_saved'] = $is_saved;
		}
		if ($is_saved) {
			$return['message'] = 'Timesheet has been saved.';
		} else {
			$return['message'] = 'There was a problem saving the timesheet. Please contact the administrator.';
		}
		echo json_encode($return);		
	}
	
	function _delete_overtime_by_employee_and_date() {
		$employee_id = Utilities::decrypt($_POST['employee_id']);
		$e = Employee_Factory::get($employee_id);
		$date = $_POST['date'];
			
		$o = G_Overtime_Finder::findByEmployeeAndDate($e, $date);
		if ($o) {
			$date = $o->getDate();
			$employee_id = $o->getEmployeeId();
			if ($o->delete()) {
				$e = G_Employee_Finder::findById($employee_id);
				if ($e) {
					G_Attendance_Helper::updateAttendance($e, $date);
				}
				$return['is_deleted'] = true;
				$return['message'] = 'Overtime has been deleted';
			} else {
				$return['is_deleted'] = false;
				$return['message'] = 'Overtime has not been deleted. Please contact the administrator';
			}
		} else {
			$return['is_deleted'] = false;
			$return['message'] = 'Overtime was not found.';
		}
		echo json_encode($return);
	}
	
	function _delete_restday_by_employee_and_date() {
		$employee_id = Utilities::decrypt($_POST['employee_id']);
		$e = Employee_Factory::get($employee_id);
		$date = $_POST['date'];
			
		$o = G_Restday_Finder::findByEmployeeAndDate($e, $date);
		if ($o) {
			$date = $o->getDate();
			$employee_id = $o->getEmployeeId();
			if ($o->delete()) {
				$e = G_Employee_Finder::findById($employee_id);
				if ($e) {
					G_Attendance_Helper::updateAttendance($e, $date);
				}
				$return['is_deleted'] = true;
				$return['message'] = 'Rest day has been deleted';
			} else {
				$return['is_deleted'] = false;
				$return['message'] = 'Rest day has not been deleted. Please contact the administrator';
			}
		} else {
			$return['is_deleted'] = false;
			$return['message'] = 'Rest day was not found.';
		}
		echo json_encode($return);
	}			
	
	function _delete_overtime() {
		$overtime_id = (int) $_POST['overtime_id'];		
		$o = G_Overtime_Finder::findById($overtime_id);
		if ($o) {
			$date = $o->getDate();
			$employee_id = $o->getEmployeeId();
			if ($o->delete()) {
				$e = G_Employee_Finder::findById($employee_id);
				if ($e) {
					G_Attendance_Helper::updateAttendance($e, $date);
				}
				$return['is_deleted'] = true;
				$return['message'] = 'Overtime has been deleted';
			} else {
				$return['is_deleted'] = false;
				$return['message'] = 'Overtime has not been deleted. Please contact the administrator';
			}
		} else {
			$return['is_deleted'] = false;
			$return['message'] = 'Overtime was not found.';
		}
		echo json_encode($return);
	}

    function _add_restday() {
        $month = $_POST['month'];
        $day = $_POST['day'];
        $year = $_POST['year'];
        $employee_id = (int) $_POST['employee_id'];
        $date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
        $e = G_Employee_Finder::findById($employee_id);
        if ($e) {
    		$o = new G_Restday;
    		$o->setDate($date);
    		$o->setEmployeeId($e->getId());
            $is_added = $o->save();
        }
        if ($is_added) {
		    G_Attendance_Helper::updateAttendance($e, $date);
			$return['is_added'] = true;
            $return['message'] = 'Rest Day has been successfully added.';
        } else {
            $return['is_added'] = false;
            $return['message'] = 'Rest Day has not been added.';
        }
        echo json_encode($return);
    }

    function _add_group_restday() {
        $month = $_POST['month'];
        $day   = $_POST['day'];
        $year  = $_POST['year'];
        $date  = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
        $group_id = (int) $_POST['group_id'];

        $c = G_Company_Structure_Finder::findById($group_id);
		if( $c ){
			$return = $c->addRestDay($date);
		}else{
			$return['is_added'] = false;
            $return['message'] = 'Rest Day has not been added.';
		}
		echo json_encode($return);
    }
	
	function _delete_restday() {
		$restday_id = (int) $_POST['restday_id'];		
		$o = G_Restday_Finder::findById($restday_id);
		if ($o) {
			$date = $o->getDate();
			$employee_id = $o->getEmployeeId();
			if ($o->delete()) {
				$e = G_Employee_Finder::findById($employee_id);
				if ($e) {
					G_Attendance_Helper::updateAttendance($e, $date);
				}
				$return['is_deleted'] = true;
				$return['message'] = 'Rest Day has been deleted';
			} else {
				$return['is_deleted'] = false;
				$return['message'] = 'Rest Day has not been deleted. Please contact the administrator';
			}
		} else {
			$return['is_deleted'] = false;
			$return['message'] = 'Rest Day was not found.';
		}
		echo json_encode($return);
	}		

	function _delete_group_restday() {
		$month = $_POST['month'];
        $day   = $_POST['day'];
        $year  = $_POST['year'];
        $date  = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
        $group_id = (int) $_POST['group_id'];

        $c	= G_Company_Structure_Finder::findById($group_id);
		if( $c ){
			$return = $c->deleteRestDay($date);
		}else{
			$return['is_deleted'] = false;
			$return['message'] = 'Rest Day was not found.';
		}
		
		echo json_encode($return);
	}		
	
	function _import_timesheet_excel() {
		ob_start();		
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);					
		$file = $_FILES['timesheet']['tmp_name'];		

		$is_imported = G_Attendance_Helper::importTimesheet($file);		
		if ($is_imported) {

			/*
			 * Add notifications
			*/
			$has_update = false;
			$n = G_Notifications_Finder::findByEventType('Update Attendance');
			if($n) {
	            $n->setStatus(G_Notifications::STATUS_NEW); 
	            $n->setItem(1);
	            $has_update = true;
			}

	        if($has_update) {
	            $n->setDateModified(date('Y-m-d H:i:s'));
	            $n->save();
	        } 	
			/*
			 * Add notifications - End
			*/	        		

			$return['is_imported'] = true;
			$return['message'] = 'Timesheet has been successfully imported';

			//General Reports / Shr Audit Trail
			if($n){
				$status = 'New';
			}
			else{
				$status = 'Update';
			}

        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_IMPORT, ' Timesheet ', $status, '', '', 1, '', '');

		} else {
			$return['is_imported'] = false;
			$return['message'] = 'An error occured. Please contact the administrator';

			//General Reports / Shr Audit Trail
			$DateModified =  $n->setDateModified(date('Y-m-d H:i:s'));
        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_IMPORT, ' Timesheet ', $status, '', '', 0, '', '');
		}

		ob_clean();
		ob_end_flush();

		echo json_encode($return);

	}
	
	function _import_overtime() {
		//ob_start();
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		$file = $_FILES['overtime_file']['tmp_name'];
        $ot_status = $_POST['overtime_status'];
		$time = new G_Overtime_Import($file);
        $time->setStatus($ot_status);
		$is_imported = $time->import();

		$return['no_html'] = false;
		if ($is_imported && !$time->hasError()) {
			/*$html = $time->constructAssignApproverHtml();
			echo $html;*/
			$return['is_imported'] = true;
			$return['message'] = 'Overtime has been successfully imported.';
            
            if($time->hasErrorDatetime() > 0){

            	$m = $time->getErrorDatetime();
            	$msg = "<br>Error Found: (".$time->hasErrorDatetime(). ")";
            	foreach ($m as $value) {
            		$msg .= "<br>". $value;
            	}

            	$return['message'] .= $msg;
            }

            ob_clean();
			ob_end_flush();
			echo json_encode($return);	

		} else if ($is_imported && $time->hasError()) {
			$return['is_imported'] = true;
			$return['message'] = "Some data has been imported but there are ". $time->getTotalErrors() ." errors found. To fix, click the Error Report tab";

			if($time->hasErrorDatetime() > 0){

            	$m = $time->getErrorDatetime();
            	$msg = "<br>Additional Error Found: (".$time->hasErrorDatetime(). ")";
            	foreach ($m as $value) {
            		$msg .= "<br>". $value;
            	} 

            	$return['message'] .= $msg;
            }

			ob_clean();
			ob_end_flush();
			echo json_encode($return);	
		} else {
			
			$return['is_imported'] = false;

			if($time->hasErrorDatetime() > 0){

				$return['message'] = 'There was a problem importing the overtime.';

            	$m = $time->getErrorDatetime();
            	$msg = "<br>Error Found: (".$time->hasErrorDatetime(). ")";
            	foreach ($m as $value) {
            		$msg .= "<br>". $value;
            	}

            	$return['message'] .= $msg;
            }

            else{
            	
         	    $return['message'] = 'There was a problem importing the overtime. Please contact the administrator.';
            }


            ob_clean();
			ob_end_flush();
			echo json_encode($return);	
        }
        	
	}

	function _assign_approver_to_imported_overtime() {
		if(!empty($_POST['request_emp_id'])) {
			$c = count($_POST['request_emp_id']);
			for($i = 1; $i <= $c; $i++) {
				$requestor_employee_id = $_POST['request_emp_id'][$i];
				$status = $_POST['request_status'][$i];

				$data['employee_id'] 	= $requestor_employee_id;
				$data['date'] 			= $_POST['request_date'][$i];
				$data['time_in'] 		= $_POST['request_time_in'][$i];
				$data['time_out'] 		= $_POST['request_time_out'][$i];
				$data['status'] 		= $status;

				$approvers_count = count($_POST['approver'][$requestor_employee_id]);
				if($approvers_count > 0) {
					$approvers_id = array();
					for($x = 1; $x <= $approvers_count; $x++) {
						$approvers_id[] = Utilities::encrypt($_POST['approver'][$requestor_employee_id][$x]);
					}
					$fields = array("id");
					$request = G_Overtime_Helper::sqlGetRequestDetailsByEmployeeIdAndDateAndTimeInAndTimeOutAndStatus($data, $fields);
					
					$request_id 	= $request[0]['id'];
					$request_type 	= G_Request::PREFIX_OVERTIME;


					$r = new G_Request();
			        $r->setRequestorEmployeeId($requestor_employee_id);
			        $r->setRequestId($request_id);
			        $r->setRequestType($request_type);
			        $r->setStatus($status);
			        $r->saveEmployeeRequest($approvers_id); //Save request approvers

				}
				
			}

		}
		$return['message'] = 'Overtime has been successfully imported.';
		$return['is_success'] = true;
		echo json_encode($return);
	}

    function _import_leave_credit() {
    	ob_start();
        ini_set("memory_limit", "999M");
        set_time_limit(999999999999999999999);
        $file = $_FILES['file']['tmp_name'];
        $covered_year = $_POST['covered_year'];

        $l = new G_Employee_Leave_Available_Importer($file);
        $l->setYear($covered_year);
        $is_imported = $l->import();

        if ($is_imported) {
            $return['is_imported'] = true;
            $return['message'] = 'Leave credits have been successfully added.';
        } else {
            $return['is_imported'] = false;
            $return['message'] = 'There was a problem importing the leave credits. Please contact the administrator.';
        }
        ob_clean();
		ob_end_flush();
        echo json_encode($return);
    }

    function _import_employee_leave_credit() {
    	//ob_start();
        ini_set("memory_limit", "999M");
        set_time_limit(999999999999999999999);
        $file = $_FILES['file']['tmp_name'];
        $covered_year = $_POST['covered_year'];

        $l = new G_Employee_Leave_Available_Importer($file);
        $l->setYear($covered_year);
        $is_imported = $l->importLeaveCredit(); //function import

        if ($is_imported) {
            $return['is_imported'] = true;
            $return['message'] = 'Leave credits have been successfully added.';
        } else {
            $return['is_imported'] = false;
            $return['message'] = 'There was a problem importing the leave credits. Please contact the administrator.';
        }
       /* ob_clean();
		ob_end_flush();*/
        echo json_encode($return);    	
    }
	
	function _import_restday() {
		ob_start();
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		$file = $_FILES['restday_file']['tmp_name'];
		$time = new G_Restday_Import($file);
		$is_imported = $time->import();
        $sched = new G_Schedule_Specific_Import($file);
        $sched->import();
		if ($is_imported) {
			$return['is_imported'] = true;
			$return['message'] = 'Rest Day has been successfully imported.';	
		} else {
			$return['is_imported'] = false;
			$return['message'] = 'There was a problem importing the Rest Day. Please contact the administrator.';
		}
		ob_clean();
		ob_end_flush();
		echo json_encode($return);		
	}
	
	function _import_overtime_pending() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		$file = $_FILES['overtime_file']['tmp_name'];
		$time = new G_Overtime_Import_Pending($file);
		$time->setCreatedBy(Utilities::decrypt($_POST['h_employee_id']));
		$is_imported = $time->import();		
		if ($is_imported) {
			$return['is_imported'] = true;
			$return['message'] = 'Overtime has been successfully imported.';	
		} else {
			$return['is_imported'] = false;
			$return['message'] = 'There was a problem importing the overtime. Please contact the administrator.';
		}
		echo json_encode($return);		
	}

	function _update_timesheet_inout(){
		$data = $_POST;		
		$result['is_success'] = false;
		$result['message']    = 'Cannot update record';

		if( !empty($data) ){			
			$employee_code = Utilities::decrypt($data['eid']);			
			$at = new G_Attendance_Log();
			$at->setEmployeeCode($employee_code);
			$result = $at->updateFpLogsEntries($data)->updateAttendance();	
		}
		echo json_encode($result);

	}

	function _add_timesheet_inout(){
		$data = $_POST;		
		$result['is_success'] = false;
		$result['message']    = 'Cannot update record';

		if( !empty($data) ){			
			$employee_code = Utilities::decrypt($data['eid']);			
			$e = G_Employee_Finder::findByEmployeeCode($employee_code);
			if( $e ){
				$date_in  = $data['in']['date'];
				$time_in  = $data['in']['time'];

				$date_out = $data['out']['date'];
				$time_out = $data['out']['time'];

				$date_time_in  = array($date_in => $time_in);
				$date_time_out = array($date_out => $time_out); 

				$at = new G_Attendance_Log();
				$at->setEmployeeId($e->getId());				
				$at->setDateTimeIn($date_time_in);
				$at->setDateTimeOut($date_time_out);
				$return = $at->addAttendanceLog();
				$return['is_success'] = $return['is_saved'];
			}			
		}
		echo json_encode($result);
	}

	function _save_attendance_log() {
		if( !empty($_POST['h_employee_id']) ){
			$employee_pkid = Utilities::decrypt($_POST['h_employee_id']);	
			$e = G_Employee_Finder::findById($employee_pkid);
			if( $e ){
				// $date_in  = $_POST['date_in'];
				// $time_in  = $_POST['time_in'];

				// $date_out = $_POST['date_out'];
				// $time_out = $_POST['time_out'];

				$date  = $_POST['date'];
				$time  = $_POST['time'];
				$type  = $_POST['type'];
				$remarks  = $_POST['remarks'];

				//General Reports / Shr Audit Trail
				if($type == 'in'){
					$new_type = 'TIME IN';
				}
				elseif($type == 'out'){
					$new_type = 'TIME OUT';
				}
				elseif($type == 'bin'){
					$new_type = 'BREAK IN';
				}
				elseif($type == 'bout'){
					$new_type = 'BREAK OUT';
				}
				elseif($type == 'otbin'){
					$new_type = 'OT BREAK IN';
				}
				elseif($type == 'otbout'){
					$new_type = 'OT BREAK OUT';
				}

				//save to fp_attendance_logs
				if (strtolower($type) == strtolower(G_Attendance_Log::TYPE_IN) || strtolower($type) == strtolower(G_Attendance_Log::TYPE_OUT)) {
					// $date_time_in  = array($date_in => $time_in);
					// $date_time_out = array($date_out => $time_out); 

					$date_time  = array($date => $time);
	
					$at = new G_Attendance_Log();
					$at->setEmployeeId($e->getId());	
					$at->setRemarks($remarks);	

					// $at->setDateTimeIn($date_time_in);
					// $at->setDateTimeOut($date_time_out);

					if (strtolower($type) == strtolower(G_Attendance_Log::TYPE_IN)) {
						$at->setDateTimeIn($date_time);
					}
					else if (strtolower($type) == strtolower(G_Attendance_Log::TYPE_OUT)) {
						$at->setDateTimeOut($date_time);
					}

					$return = $at->addAttendanceLog();

				
					//General Reports / Shr Audit Trail
					$shr_emp = G_Employee_Helper::findByEmployeeId($employee_pkid);
					$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        			$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, ' Attendance to ', $emp_name.' '.$new_type.' '. $date.' '.$time, 'None', ''.$date.''.$time.'', 1,  $shr_emp['position'],  $shr_emp['department']);

				}
				else {
					$at = new G_Employee_Break_Logs();
					$at->setEmployeeId($e->getId());	
					$at->setEmployeeCode($e->getEmployeeCode());	
					$at->setEmployeeName($e->getLastname() . ", " . $e->getFirstname());	
					$at->setDate(date("Y-m-d", strtotime($date)));	
					$at->setTime(date("H:i:s", strtotime($time)));	
					$at->setType($type);
					$at->setRemarks($remarks);
					$return_save = $at->save();
					
					if ($return_save) {
						$return['is_saved'] = true;
						$return['message']  = 'Record was successfully saved.';

						//General Reports / Shr Audit Trail
						$shr_emp = G_Employee_Helper::findByEmployeeId($employee_pkid);
						$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, ' Attendance to ', $emp_name.' '.$new_type.' '. $date.' '.$time, 'None', ''.$date.''.$time.'', 1,  $shr_emp['position'],  $shr_emp['department']);
					}
					else {
						$return['is_saved'] = false;
						$return['message']  = 'Date error.';

						//General Reports / Shr Audit Trail
						$shr_emp = G_Employee_Helper::findByEmployeeId($employee_pkid);
						$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        				$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, ' Attendance to ', $emp_name.' '.$new_type.' '. $date.' '.$time, 'None', ''.$date.''.$time.'', 0,  $shr_emp['position'],  $shr_emp['department']);

					}
				}

				/*
				 * Add notifications
				*/
				$has_update = false;
				$n = G_Notifications_Finder::findByEventType('Update Attendance');
				if($n) {
		            $n->setStatus(G_Notifications::STATUS_NEW); 
		            $n->setItem(1);
		            $has_update = true;
				}

		        if($has_update) {
		            $n->setDateModified(date('Y-m-d H:i:s'));
		            $n->save();
		        } 	
				/*
				 * Add notifications - End
				*/					
								
			}else{
				$return['is_saved'] = false;
				$return['message']  = 'Invalid form entry!';
				//General Reports / Shr Audit Trail
				$shr_emp = G_Employee_Helper::findByEmployeeId($employee_pkid);
				$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, ' Attendance to ', $emp_name.' '.$new_type.' '. $date.' '.$time, 'None', ''.$date.''.$time.'', 0,  $shr_emp['position'],  $shr_emp['department']);
			}
			
		}else{
			$return['is_saved'] = false;
			$return['message']  = 'Invalid form entry!';
			//General Reports / Shr Audit Trail
			$shr_emp = G_Employee_Helper::findByEmployeeId($employee_pkid);
			$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_ADD, ' Attendance to ', $emp_name.' '.$new_type.' '. $date.' '.$time, 'None', ''.$date.''.$time.'', 0,  $shr_emp['position'],  $shr_emp['department']);
		}
		echo json_encode($return);
	}
	
	function _update_attendance() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
				
		$from = $_GET['from'];
		$to = $_GET['to'];
		$frequency_id = $_GET['frequency_id'];
		
		$is_updated = G_Attendance_Helper::updateAttendanceByPeriodAndFrequency($from, $to, $frequency_id);
		$return['is_updated'] = $is_updated;
		if ($is_updated) {
			$return['message'] = 'Attendance has been successfully updated';

			//General Reports / Shr Audit Trail
        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Attendance Records ', '', $from, $to, 1, '', '');

		} else {
			$return['message'] = 'Attendance has not been updated';

			//General Reports / Shr Audit Trail
        	$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Attendance Records ', '', $from, $to, 0, '', '');

		}
		echo json_encode($return);
	}
	
	function _update_attendance_by_employee() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$employee_id = Utilities::decrypt($_GET['employee_id']);
		$from = $_GET['from'];
		$to = $_GET['to'];
		
		$e = Employee_Factory::get($employee_id);
		if ($e) {
			$is_updated = G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $from, $to);
		}
		$return['is_updated'] = $is_updated;
		if ($is_updated) {
			$return['message'] = 'Attendance has been successfully updated';
		} else {
			$return['message'] = 'Attendance has not been updated';
		}
		echo json_encode($return);
	}			
	
	function ajax_search_employee() {
		$query = $_GET['query'];
		if ($query == '') {
			$this->var['employees'] = G_Employee_Finder::findAllActive();
		} else {
			$this->var['employees'] = G_Employee_Finder::searchActiveByFirstnameAndLastnameAndEmployeeCode($query);
		}		
		$this->view->render('attendance/ajax_search_employee.php', $this->var);
	}

	function ajax_show_attendance_detailed() {		
		$employee_id    = Utilities::decrypt($_GET['employee_id']);
		$cutoff_periods = G_Cutoff_Period_Finder::findAll();
		$e              = Employee_Factory::getBothArchiveAndNot($employee_id);
		if( $e && $cutoff_periods ){
			$cutoff_periods_array = G_Cutoff_Period_Helper::convertToArray($cutoff_periods);	

			$start_date = (empty($_GET['start_date'])) ? $cutoff_periods[0]->getStartDate() : $_GET['start_date'];
			$end_date   = (empty($_GET['end_date'])) ? $cutoff_periods[0]->getEndDate() : $_GET['end_date'];
			$timesheet  = $e->getEmployeeTimesheetData($start_date, $end_date);	
			
			$this->var['start_date'] = $start_date;
			$this->var['end_date']   = $end_date;
			$this->var['timesheet']  = $timesheet;
			$this->view->render('attendance/ajax_show_attendance_non_editable_b.php', $this->var);
		}else{

		}
	}

	function ajax_attendance_more_details() {
		$id   = Utilities::decrypt($_GET['eid']);
		$date = date("Y-m-d",$_GET['date']);		
		$e    = G_Employee_Finder::findById($id);

		if( !empty($e) ){
			$a    = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
			if( !empty($a) ){					
				$data = $a->groupTimesheetData();
				$total_hrs_deductible = $data['total_hrs_deductible'];
				$break_logs_summary = G_Employee_Break_logs_Summary_Finder::findByEmployeeAttendanceId($a->getId());
	
				$this->var['schedule']   			= $data['schedule'];
				$this->var['attendance'] 			= $data['attendance'];
				$this->var['holiday']    			= $data['holiday'];
				$this->var['tardiness']  			= $data['tardiness'];
				$this->var['overtime']   			= $data['overtime'];
				$this->var['breaktime']  			= $data['breaktime'];
				$this->var['breaktime_hrs']      	= $data['breaktime_hrs'];
				$this->var['break']      			= $data['break'];
				$this->var['emp_id']     			= $a->getEmployeeId();
				$this->var['date']       			= $a->getDate();
				$this->var['break_logs_summary'] 	= $break_logs_summary;
				$this->var['total_hrs_deductible'] 	= $total_hrs_deductible;
				$this->view->render('attendance/ajax_attendance_other_details.php', $this->var);
			}else{
				echo "Attendance data not found";	
			}
		}else{
			echo "Attendance data not found";	
		}		
		
	}
	
	function ajax_show_attendance() { 

		$employee_id = Utilities::decrypt($_GET['employee_id']);
		$this->var['encrypted_employee_id'] = $_GET['employee_id'];
		$e = $this->var['e'] = Employee_Factory::getBothArchiveAndNot($employee_id);

		$is_period_lock = G_Cutoff_Period_Helper::isPeriodLockByDate($_GET['start_date'],$_GET['end_date']);

		$this->var['employee_name'] = $e->getName();
		$today = Tools::getGmtDate('Y-m-d');
		$cutoff_periods = G_Cutoff_Period_Finder::findAll();
		$cutoff_periods_array = G_Cutoff_Period_Helper::convertToArray($cutoff_periods);	
		
		$this->var['start_date'] = $start_date = (empty($_GET['start_date'])) ? $cutoff_periods[0]->getStartDate() : $_GET['start_date'];
		$this->var['end_date'] = $end_date = (empty($_GET['end_date'])) ? $cutoff_periods[0]->getEndDate() : $_GET['end_date'];	
		
		// Timesheet Navigation
		$date = $_GET['start_date'];
		$end_date = $_GET['end_date'];
		$cutoff_year = date('Y', strtotime($end_date));

		if($cutoff_year == date("Y")) {
			$c = new G_Cutoff_Period();
			$next     = $c->getNextCutOffByDate($date, $cutoff_year);
			$previous = $c->getPreviousCutOffByDate($date, $cutoff_year);
		} else {
			$c = new G_Cutoff_Period();
			$next     = $c->getNextCutOffByDate($date, $cutoff_year);
			$previous = $c->getPreviousCutOffByDate($date, $cutoff_year);
		}

		if(isset($_GET['year_selected'])) {
			$this->var['year_selected'] = $_GET['year_selected'];	
		} else {
			$this->var['year_selected'] = date("Y", strtotime($end_date));
		}
		
        $all_payroll_years 				= G_Cutoff_Period_Helper::sqlGetAllExistYearTags();
        $this->var['all_cutoff_years'] 	= $all_payroll_years;

		$this->var['next_start_date']     = $next['start_date'];
		$this->var['next_end_date']       = $next['end_date'];
		$this->var['previous_start_date'] = $previous['start_date'];
		$this->var['previous_end_date']   = $previous['end_date'];

		$selected_period 			 = array('start' => $start_date, 'end' => $end_date);
		$selected_key 				 = array_search($selected_period, $cutoff_periods_array);
		$this->var['is_period_lock'] = $is_period_lock;
		
		// Employee Navigation		
		
		$previous_employee_id = G_Employee_Helper::getPreviousIdAlphabetic($employee_id);
		$obj_previous_employee = Employee_Factory::get($previous_employee_id);
		$next_employee_id = G_Employee_Helper::getNextIdAlphabetic($employee_id);
		$obj_next_employee = Employee_Factory::get($next_employee_id);
		if ($obj_next_employee) {
			$this->var['next_employee_name'] = $obj_next_employee->getName();
		}
		if ($obj_previous_employee) {
			$this->var['previous_employee_name'] = $obj_previous_employee->getName();
		}
		$this->var['previous_encrypted_employee_id'] = Utilities::encrypt($previous_employee_id);
		$this->var['next_encrypted_employee_id'] = Utilities::encrypt($next_employee_id);		
		
		$this->var['frequency_id'] = $_GET['selected_frequency'];
		$this->var['dates'] 		  = Tools::getBetweenDates($start_date, $end_date);
		$attendance 				  = G_Attendance_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);
		$this->var['cutoff_selected'] = $start_date."/".$end_date;
		$attendance 				  = G_Attendance_Helper::changeArrayKeyToDateConstructed($attendance);		
		$this->var['employee_id'] 	  =  $employee_id;
	
		$this->var['attendance']  	  = $attendance;
		$this->var['employee_break_logs_summary_helper'] = new G_Employee_Break_Logs_Summary_Helper();

        $this->view->render('attendance/ajax_show_attendance_non_editable.php', $this->var);
	}
	
	function ajax_edit_attendance() {
		$this->var['leaves'] = G_Leave_Finder::findAll();
		$this->var['action'] = url('attendance/_edit_attendance');
		$this->var['date'] = $date = $_GET['date'];
		$this->var['employee_id'] = $_GET['employee_id'];
		$employee_id = Utilities::decrypt($_GET['employee_id']);
		$e = Employee_Factory::get($employee_id);
		$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
		if ($a) {
			if ($a->isPresent()) {
				if ($a->isRestday()) {
					$this->var['restday_present'] = 'checked="checked"';
				} else {
					$this->var['present'] = 'checked="checked"';
				}					
			} else {
				if ($a->isRestday()) {
					$this->var['restday'] = 'checked="checked"';
				} else {
					$this->var['absent'] = 'checked="checked"';	
				}
			}
			$this->var['leave_id'] = $a->getLeaveId();
			$this->var['is_paid'] = $a->isPaid();
		} else {
			$this->var['present'] = 'checked="checked"';
		}
		list($year, $month, $day) = explode('-', $date);
		$h = G_Holiday_Finder::findByMonthAndDay($month, $day);
		if ($h) {
			$this->var['not_present'] = 'Holiday';	
		} else {
			$this->var['not_present'] = 'Absent';
		}
		$this->view->render('attendance/ajax_edit_attendance.php', $this->var);
	}
	
	function ajax_edit_attendance_log() {
		$id = Utilities::decrypt($_GET['eid']);
		$type = isset($_GET['type']) ? $_GET['type'] : '';
 
		if($id){
			if ($type == '' || strtolower($type) == strtolower(G_Attendance_Log::TYPE_IN) || strtolower($type) == strtolower(G_Attendance_Log::TYPE_OUT)) {
				$at = G_Attendance_Log_Finder::findById($id);
			}
			else {
				$at = G_Employee_Break_logs_Finder::findById($id);
				if(!$at)
				$at = G_Attendance_Log_Finder::findById($id);
			}

			if($at){
				$this->var['token'] = Utilities::createFormToken();
				$this->var['at']    = $at;
		
				$this->var['log_types'] = array(
					G_Attendance_Log::TYPE_IN,
					G_Employee_Break_Logs::TYPE_BOUT,
					G_Employee_Break_Logs::TYPE_BIN,
					G_Employee_Break_Logs::TYPE_BOT_OUT,
					G_Employee_Break_Logs::TYPE_BOT_IN,
					G_Attendance_Log::TYPE_OUT
				);

				$this->var['devices'] = G_Attendance_Log_Finder::getDevices();
				$this->view->render('attendance/ajax_edit_attendance_log.php', $this->var);
			}else{
			
			}
		}else{
			
		}
		
	}
	
	function _update_attendance_log()
	{
		Utilities::verifyFormToken($_POST['token']);		
		$attendance_type = Utilities::decrypt($_POST['c_type']);
		if($_POST['eid']){		
			$at = false;
			if (strtolower($attendance_type) == strtolower(G_Attendance_Log::TYPE_IN) || strtolower($attendance_type) == strtolower(G_Attendance_Log::TYPE_OUT)) {
				$at = G_Attendance_Log_Finder::findById(Utilities::decrypt($_POST['eid']));
			}
			else {
				$at = G_Employee_Break_logs_Finder::findById(Utilities::decrypt($_POST['eid']));
				if(!$at){
					$at = G_Attendance_Log_Finder::findById(Utilities::decrypt($_POST['eid']));
					$breaknonsync = true;
				}
			}
			
			if($at){

				/*
				 * Add notifications
				*/
				$has_update = false;
				$n = G_Notifications_Finder::findByEventType('Update Attendance');
				if($n) {
		            $n->setStatus(G_Notifications::STATUS_NEW); 
		            $n->setItem(1);
		            $has_update = true;
				}

		        if($has_update) {
		            $n->setDateModified(date('Y-m-d H:i:s'));
		            $n->save();
		        } 	
				/*
				 * Add notifications - End
				*/	
				$at->setRemarks($_POST['remarks']);

				if(strtolower($attendance_type) == "in" || strtolower($attendance_type) == "out" || $breaknonsync){
					$at->changeType($_POST['a_type']);
                	$at->changeTime($_POST['a_time']);
				}else{
					if($_POST['a_type'] == "in" || $_POST['a_type'] == "out"){
						$_at = G_Attendance_Log_Finder::findById(Utilities::decrypt($_POST['eid']));
						if($_at){
							$at->deleteLog();
							$_at->changeType($_POST['a_type']);
                			$_at->changeTime($_POST['a_time']);
							G_Attendance_Log_Manager::resetLogsToNotTransferredById($_at->getId());
						}else{
							$_at = new G_Attendance_Log();
							$_at->setEmployeeId($at->getEmployeeId());
							$_at->setEmployeeCode($at->getEmployeeCode());
							$_at->setEmployeeName($at->getEmployeeName());
							$_at->setDate($at->getDate());
							$_at->setTime($at->getTime());
							$_at->setEmployeeId($at->getEmployeeId());
							$_at->setType(strtolower($_POST['a_type']));
							$_at->save();
							$at->deleteLog();
						}
					}else{	
						$_at = G_Attendance_Log_Finder::findById(Utilities::decrypt($_POST['eid']));
						$at->changeType($_POST['a_type']);
						$at->changeTime($_POST['a_time']);

						if($_at)
						{
							if($_POST['a_type'] == "in" || $_POST['a_type'] == "out")
							$_at->changeType($_POST['a_type']);
							$_at->changeTime($_POST['a_time']);
						}
					}
				}
				$json['is_saved'] = 1;
				$json['message']  = 'Record was successfully saved.';

				//General Reports / Shr Audit Trail
				$date = $at->getDate();
				$shr_emp = G_Employee_Helper::findByEmployeeId($at->getEmployeeId());
				$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Attendance log of ', $emp_name.' Date & Time:'. $date.''.$_POST['a_time'].' Type '. $_POST['a_type'], '', '', 1, '', '');

			}else{
				$json['is_saved'] = 0;
				$json['message']  = 'Data Error';

				//General Reports / Shr Audit Trail
				$date = $at->getDate();
				$shr_emp = G_Employee_Helper::findByEmployeeId($at->getEmployeeId());
				$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Attendance log of ', $emp_name.' Date & Time:'. $date.''.$_POST['a_time'].' Type '. $_POST['a_type'], '', '', 0, '', '');
			}
			
			$json['is_saved'] = 1;			
			$json['message']  = 'Record was successfully saved.';

				//General Reports / Shr Audit Trail
				$date = $at->getDate();
				$shr_emp = G_Employee_Helper::findByEmployeeId($at->getEmployeeId());
				$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Attendance log of ', $emp_name.' Date & Time:'. $date.''.$_POST['a_time'].' Type '. $_POST['a_type'], '', '', 1, '', '');

		}else {
			$json['is_saved'] = 0;
			$json['message']  = 'Data Error';

				//General Reports / Shr Audit Trail
				$date = $at->getDate();
				$shr_emp = G_Employee_Helper::findByEmployeeId($at->getEmployeeId());
				$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        		$this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Attendance log of ', $emp_name.' Date & Time:'. $date.''.$_POST['a_time'].' Type '. $_POST['a_type'], '', '', 0, '', '');

		}
		$json['is_saved'] = 1;			
		$json['message']  = 'Record was successfully saved.';

		//General Reports / Shr Audit Trail
		$date = $at->getDate();
		$shr_emp = G_Employee_Helper::findByEmployeeId($at->getEmployeeId());
		$emp_name = $shr_emp['firstname'].' '.$shr_emp['lastname'];
        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_NEW_UPDATE, ' Attendance log of ', $emp_name.' Date & Time: '.$date.''.$_POST['a_time'].' Type '.$_POST['a_type'], '', '', 1, '', '');

		echo json_encode($json);
	}

    function ajax_edit_actual_time_form() {
        $this->var['action']    = url('attendance/_edit_actual_time');
        $this->var['employee_id'] = $employee_id = $_GET['employee_id'];
        $this->var['date'] = $date = $_GET['date'];
        $this->var['date_string'] = Tools::convertDateFormat($date);

        $e = G_Employee_Finder::findById($employee_id);
        if ($e) {
            $a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
            if ($a) {
                $t = $a->getTimesheet();
                $time_in = '';
                $time_out = '';
                if ($t->getTimeIn() != '') {
                    $time_in = Tools::timeFormat($t->getTimeIn());
                }
                if ($t->getTimeOut() != '') {
                    $time_out = Tools::timeFormat($t->getTimeOut());
                }
                $this->var['time_in'] = $time_in;
                $this->var['time_out'] = $time_out;
            }
        }

        $this->view->render('attendance/ajax_edit_actual_time_form.php',$this->var);
    }
		
	function ajax_edit_time_in_out() {
		$this->var['action'] = url('attendance/_edit_time_in_out');
		$this->var['date'] = $date = $_GET['date'];
		$this->var['employee_id'] = $_GET['employee_id'];
		$employee_id = Utilities::decrypt($_GET['employee_id']);
		$e = Employee_Factory::get($employee_id);
		$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
		if ($a) {
			$t = $a->getTimesheet();
			list($actual_time_in_hh, $temp_actual_time_in_mm) = explode(':', Tools::timeFormat($t->getTimeIn()));
			list($actual_time_in_mm, $actual_time_in_am) = explode(' ', $temp_actual_time_in_mm);
			$this->var['actual_time_in_hh'] = $actual_time_in_hh;
			$this->var['actual_time_in_mm'] = $actual_time_in_mm;
			$this->var['actual_time_in_am'] = $actual_time_in_am;
			list($actual_time_out_hh, $temp_actual_time_out_mm) = explode(':', Tools::timeFormat($t->getTimeOut()));
			list($actual_time_out_mm, $actual_time_out_am) = explode(' ', $temp_actual_time_out_mm);
			$this->var['actual_time_out_hh'] = $actual_time_out_hh;
			$this->var['actual_time_out_mm'] = $actual_time_out_mm;
			$this->var['actual_time_out_am'] = $actual_time_out_am;
			
			list($scheduled_time_in_hh, $temp_scheduled_time_in_mm) = explode(':', Tools::timeFormat($t->getScheduledTimeIn()));
			list($scheduled_time_in_mm, $scheduled_time_in_am) = explode(' ', $temp_scheduled_time_in_mm);
			$this->var['scheduled_time_in_hh'] = $scheduled_time_in_hh;
			$this->var['scheduled_time_in_mm'] = $scheduled_time_in_mm;
			$this->var['scheduled_time_in_am'] = $scheduled_time_in_am;	
			
			list($scheduled_time_out_hh, $temp_scheduled_time_out_mm) = explode(':', Tools::timeFormat($t->getScheduledTimeOut()));
			list($scheduled_time_out_mm, $scheduled_time_out_am) = explode(' ', $temp_scheduled_time_out_mm);
			$this->var['scheduled_time_out_hh'] = $scheduled_time_out_hh;
			$this->var['scheduled_time_out_mm'] = $scheduled_time_out_mm;
			$this->var['scheduled_time_out_am'] = $scheduled_time_out_am;
		}
		$this->view->render('attendance/ajax_edit_time_in_out.php', $this->var);
	}
	
	function ajax_edit_overtime_in_out() {
		$this->var['action'] = url('attendance/_edit_overtime_in_out');
		$this->var['date'] = $date = $_GET['date'];
		$this->var['employee_id'] = $_GET['employee_id'];
		$employee_id = Utilities::decrypt($_GET['employee_id']);
		$e = Employee_Factory::get($employee_id);
		$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
		if ($a) {
			$t = $a->getTimesheet();
			$time_in = $t->getOverTimeIn();
			if ($time_in) {
				list($actual_time_in_hh, $temp_actual_time_in_mm) = explode(':', Tools::timeFormat($time_in));
				list($actual_time_in_mm, $actual_time_in_am) = explode(' ', $temp_actual_time_in_mm);
			} else {
				list($actual_time_in_hh, $temp_actual_time_in_mm) = explode(':', Tools::timeFormat($t->getScheduledTimeOut()));
				list($actual_time_in_mm, $actual_time_in_am) = explode(' ', $temp_actual_time_in_mm);
			}
			$this->var['actual_time_in_hh'] = $actual_time_in_hh;
			$this->var['actual_time_in_mm'] = $actual_time_in_mm;
			$this->var['actual_time_in_am'] = $actual_time_in_am;			
			
			
			$time_out = $t->getOverTimeOut();
			if ($time_out) {
				list($actual_time_out_hh, $temp_actual_time_out_mm) = explode(':', Tools::timeFormat($time_out));
				list($actual_time_out_mm, $actual_time_out_am) = explode(' ', $temp_actual_time_out_mm);
			} else {
				$mk_time_out = strtotime($t->getTimeOut());
				$mk_scheduled_time_out = strtotime($t->getScheduledTimeOut());
				if ($mk_time_out > $mk_scheduled_time_out) {
					$temp_time_out = $t->getTimeOut();	
				} else {
					$temp_time_out = date('g:i a', strtotime($t->getScheduledTimeOut() .'+1 hours'));	
				}
				
				list($actual_time_out_hh, $temp_actual_time_out_mm) = explode(':', Tools::timeFormat($temp_time_out));
				list($actual_time_out_mm, $actual_time_out_am) = explode(' ', $temp_actual_time_out_mm);
			}

			$this->var['actual_time_out_hh'] = $actual_time_out_hh;
			$this->var['actual_time_out_mm'] = $actual_time_out_mm;
			$this->var['actual_time_out_am'] = $actual_time_out_am;
		}
		$this->view->render('attendance/ajax_edit_overtime_in_out.php', $this->var);
	}	
	
	function ajax_edit_timesheet() {
		$this->var['date'] = $date  = $_GET['date'];
		$this->var['employee_id']   = $_GET['employee_id'];		
		$this->var['is_period_lock']= Utilities::decrypt($_GET['is_period_lock']);
		$this->var['action'] = url('attendance/_edit_timesheet');
		$employee_id = Utilities::decrypt($_GET['employee_id']);
		$e = Employee_Factory::get($employee_id);
		$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
		$this->var['timesheet'] = $a->getTimesheet();
		
		$this->view->render('attendance/ajax_edit_timesheet.php', $this->var);	
	}
	
	function ajax_fiter_timesheet_breakdown() {	
		$this->var['date_from'] = $_POST['date_from'];
		$this->var['date_to']   = $_POST['date_to'];	
		$this->view->render('attendance/ajax_filter_timesheet_breakdown.php', $this->var);	
	}
	
	function ajax_show_timesheet() {
		$this->var['date'] = $date = $_GET['date'];
		$this->var['employee_id'] = $_GET['employee_id'];		
		$this->var['action'] = url('attendance/_edit_timesheet');
		$employee_id = Utilities::decrypt($_GET['employee_id']);
		$e = Employee_Factory::get($employee_id);
		$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date);
		$this->var['timesheet'] = $a->getTimesheet();
		
		$this->view->render('attendance/ajax_show_timesheet.php', $this->var);	
	}

	function ajax_add_attendance_log() {	
		$now = date('Y-m-d');
        $p   = G_Cutoff_Period_Finder::findByDate($now);

        if ($p) {
            $hpid      = Utilities::encrypt($p->getId());
            $from_date = $p->getStartDate();
            $to_date   = $p->getEndDate();
		}
		
		$this->var['log_types'] = array(
			G_Attendance_Log::TYPE_IN,
			G_Employee_Break_Logs::TYPE_BOUT,
			G_Employee_Break_Logs::TYPE_BIN,
			G_Employee_Break_Logs::TYPE_BOT_OUT,
			G_Employee_Break_Logs::TYPE_BOT_IN,
			G_Attendance_Log::TYPE_OUT
		);

		$this->var['devices'] = G_Attendance_Log_Finder::getDevices();
        $this->var['from_date'] = $from_date;
        $this->var['to_date']   = $to_date;
		$this->var['action'] = url('attendance/_save_attendance_log');
		$this->view->render('attendance/ajax_add_attendance_log.php', $this->var);	
	}
	
	function ajax_import_timesheet() {	
		$this->var['action'] = url('attendance/_import_timesheet_excel');
		$this->view->render('attendance/ajax_import_timesheet.php', $this->var);	
	}

	function ajax_synchronize_attendance() {	
		$this->var['action'] = url('attendance_sync/ajax_sync_attendance_with_date_range');
		$this->view->render('attendance/ajax_attendance_sync.php', $this->var);	
	}
	
	function ajax_import_overtime() {	
		$this->var['action'] = url('attendance/_import_overtime');
		$this->view->render('attendance/ajax_import_overtime.php', $this->var);	
	}

    function ajax_import_leave_credit() {
        $this->var['form_id'] = 'import_leave_credit_form';
        //$this->var['action'] = url('attendance/_import_leave_credit');
        //$this->var['action'] = url('benchmark_revina/_import_leave_credit');
        $this->var['action'] = url('attendance/_import_employee_leave_credit');        
        $this->view->render('attendance/ajax_import_leave_credit.php', $this->var);
    }
	
	function ajax_import_overtime_pending() {	
		$this->var['h_employee_id'] = $_GET['h_employee_id'];
		$this->var['action'] = url('attendance/_import_overtime_pending');
		$this->view->render('attendance/ajax_import_overtime.php', $this->var);	
	}
	
	function html_import_overtime() {
		$this->view->setTemplate('template_blank.php');
		$this->view->render('attendance/html/html_import_overtime.php', $this->var);	
	}	
	
	function ajax_import_restday() {	
		$this->var['action'] = url('attendance/_import_restday');
		$this->view->render('attendance/ajax_import_restday.php', $this->var);	
	}
	
	function filter_by_range() {
		Jquery::loadMainTipsy();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();			
		$this->var['page_title'] = 'Attendance';
		
		if($_POST['from_date'] != '' && $_POST['to_date'] != '') {
			$this->var['start_date'] = $start_date = $_POST['from_date'];
			$this->var['end_date'] = $_POST['to_date'];					
		}else{
			$this->var['start_date'] = $start_date = $_GET['from'];
			$this->var['end_date'] = $_GET['to'];		
		}
		$this->var['query'] = $query = $_GET['query'];
		if ($query != '') {
			if($_GET['s_exact']){
				$this->var['checked']   = 'checked="checked"';
				$this->var['employees'] = G_Employee_Finder::searchActiveByExactFirstnameAndLastnameAndEmployeeCode($query);
			}else{
				$this->var['employees'] = G_Employee_Finder::searchActiveByFirstnameAndLastnameAndEmployeeCode($query);
			}
		} else {
			
			$this->var['checked']   = '';
			$this->var['employees'] = G_Employee_Finder::findAllActiveByDate($start_date);
		}		
		$this->var['action'] = url('attendance/filter_by_range');
		$this->view->setTemplate('template.php');
		$this->var['page_title'] = '<a href="'. url('attendance') .'">Attendance</a>: Filter by Range';
		$this->view->render('attendance/filter_by_range.php',$this->var);	
	
	}

	function ajax_load_payroll_period_by_year()
	{
		$selected_frequency = $_GET['selected_frequency'];
		$selected_year = $_GET['selected_year'];
		if( $selected_year == '' || $selected_year <= 0 ){
			$selected_year = date("Y");
		}		

	   $selected_year = $selected_year;
	   // $selected_frequency = 2;
        if ($selected_frequency == 2) {
	        $c = G_Weekly_Cutoff_Period_Finder::findAllCutoffByYear($selected_year);
        }
         else if ($selected_frequency == 3) {
	        $c = G_Monthly_Cutoff_Period_Finder::findAllCutoffByYear($selected_year);
	      
        }
        else {
	        $c = G_Cutoff_Period_Finder::findAllCutoffByYear($selected_year);
        }

       //General Reports / Shr Audit Trail
        list($cut_off_from, $cut_off_to) = explode('/', $_GET['selected_cutoff']);

        list($p_year, $p_month, $p_day) = explode('-', $cut_off_from);
	        $pmonthN = date("F", mktime(0, 0, 0, $p_month, 10));
	        if($p_day >= 16){
	        	$cut_of_period = $pmonthN.'-B';
	        }
	        else{
	            $cut_of_period = $pmonthN.'-A';	
	        }
		

        $this->var['selected_cutoff'] = $_GET['selected_cutoff'];
        $this->var['selected_year']   = $selected_year;
        $this->var['cutoff_periods']  = $c;
		$this->view->noTemplate();
		$this->view->render('attendance/_payroll_period.php',$this->var);
		

		//General Reports / Shr Audit Trail
        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_LOAD, 'Attendance Records of ', $cut_of_period, $cut_off_from, $cut_off_to, 1, '', '');
	}
	
	function ajax_batch_edit_attendance_log() {
		$logs = $_POST['logs'];
		$employee_logs = array();

		$this->var['token'] = Utilities::createFormToken();

		if($logs){
			foreach ($logs as $key => $log) {
				if ($log['type'] == '' || strtolower($log['type']) == strtolower(G_Attendance_Log::TYPE_IN) || strtolower($log['type']) == strtolower(G_Attendance_Log::TYPE_OUT)) {
					$at = G_Attendance_Log_Finder::findById($log['id']);
				}
				else {
					$at = G_Employee_Break_logs_Finder::findById($log['id']);
				}
	
				if($at){
					$employee_logs[] = $at;
	
				}
			}

			$this->var['employee_logs'] = $employee_logs;
			
			$this->var['log_types'] = array(
				G_Attendance_Log::TYPE_IN,
				G_Employee_Break_Logs::TYPE_BOUT,
				G_Employee_Break_Logs::TYPE_BIN,
				G_Employee_Break_Logs::TYPE_BOT_OUT,
				G_Employee_Break_Logs::TYPE_BOT_IN,
				G_Attendance_Log::TYPE_OUT
			);
			
			$this->view->render('attendance/ajax_batch_edit_attendance_log.php', $this->var);
		}
	}	
	
	function _batch_update_attendance_log()
	{
		$ids = $_POST['ids'];
		$a_time = $_POST['a_time'];
		$a_type = $_POST['a_type'];
		$previous_type = $_POST['previous_type'];

		Utilities::verifyFormToken($_POST['token']);

		if($ids){	
			foreach ($ids as $key => $id) {
				$at = false;
	
				if (strtolower($previous_type[$key]) == strtolower(G_Attendance_Log::TYPE_IN) || strtolower($previous_type[$key]) == strtolower(G_Attendance_Log::TYPE_OUT)) {
					$at = G_Attendance_Log_Finder::findById(Utilities::decrypt($id));
				}
				else {
					$at = G_Employee_Break_logs_Finder::findById(Utilities::decrypt($id));
				}
	
				if($at) {
					/*
					 * Add notifications
					*/
					$has_update = false;
					$n = G_Notifications_Finder::findByEventType('Update Attendance');
					if($n) {
						$n->setStatus(G_Notifications::STATUS_NEW); 
						$n->setItem(1);
						$has_update = true;
					}
	
					if($has_update) {
						$n->setDateModified(date('Y-m-d H:i:s'));
						$n->save();
					} 	
					/*
					 * Add notifications - End
					*/	
					
					if (strtolower($previous_type[$key]) == strtolower($a_type[$key])) {
						$at->changeTime($a_time[$key]);
						$at->changeType($a_type[$key], $a_time[$key]);
					}
					else {
						if (strtolower($a_type[$key]) == strtolower(G_Attendance_Log::TYPE_IN) || strtolower($a_type[$key]) == strtolower(G_Attendance_Log::TYPE_OUT)) {
							$new_at = new G_Attendance_Log;
		
							$date_time  = array($at->getDate() => $at->getTime());
			
							$new_at = new G_Attendance_Log();
							$new_at->setEmployeeId($at->getEmployeeId());	
		
							if (strtolower($a_type[$key]) == strtolower(G_Attendance_Log::TYPE_IN)) {
								$new_at->setDateTimeIn($date_time);
							}
							else if (strtolower($a_type[$key]) == strtolower(G_Attendance_Log::TYPE_OUT)) {
								$new_at->setDateTimeOut($date_time);
							}
		
							$new_at->addAttendanceLog();
						}
						else {
							$new_at = new G_Employee_Break_Logs();
							$new_at->setEmployeeId($at->getEmployeeId());	
							$new_at->setEmployeeCode($at->getEmployeeCode());	
							$new_at->setEmployeeName($at->getEmployeeName());	
							$new_at->setDate(date("Y-m-d", strtotime($at->getDate())));	
							$new_at->setTime(date("H:i:s", strtotime($at->getTime())));	
							$new_at->setType($a_type[$key]);
							$new_at->save();
						}

						$at->deleteLog();
					}
				}
			}
				
			$json['is_saved'] = 1;			
			$json['message']  = 'Record was successfully saved.';
		}
		else {
			$json['is_saved'] = 0;
			$json['message']  = 'Data Error';
		}

		echo json_encode($json);
	}


function ajax_batch_delete_attendance_log(){

		$logs = $_POST['logs'];
		//utilities::displayArray($logs);exit();
		$json['is_success'] = false;
		$counter = 0;

		 if(!empty($logs)){

		 	foreach($logs as $key => $data){

		 			$fp = false;

		 			$id = $data['id'];
		 			$type = $data['type'];

		 			if ($type == '' || strtolower($type) == strtolower(G_Attendance_Log::TYPE_IN) || strtolower($type) == strtolower(G_Attendance_Log::TYPE_OUT)) {
						$fp = new G_Fp_Attendance_Logs();
					}
					else {
						$fp = new G_Employee_Break_Logs();
					}

					if ($fp) {
						$fp->setId($id);
					}

					$json2 = $fp->deleteLog();

					if($json2['is_success'] == true){
						$counter++;
						$json['is_success'] = true;
					}

		 	}

		 }
		 else{
		 	$json['message']  = 'Error. No logs selected.';
		 }


		$json['message']  = 'Record was successfully saved. <br/> Total records deleted <b>'.$counter.'</b>';
		echo json_encode($json);

	}


}
?>