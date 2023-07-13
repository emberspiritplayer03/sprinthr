<?php
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Global_Controller 
{
	function __construct()
	{
		Loader::helper(array('url', 'html'));
		Loader::sysLibrary('session');
		$this->session = $this->var['session'] = new WG_Session(array('namespace' => 'user'));
				
		$session = new WG_Session(array('namespace' => 'editor'));
		$session->set('ckfinder_baseUrl', BASE_FOLDER_EDITOR);
		$session->set('ckfinder_baseDir', BASE_FOLDER_EDITOR);

		Loader::appMainUtilities();	
		Loader::appMainLibrary('class_subfolder_loader');
		Loader::includeScript('init.js');
		
		$this->isSessionFilesExists();
		$this->createGlobalUserVariables();

		$headers = apache_request_headers();
		if( !isset($headers['X-Requested-With']) ){
			
			$this->createUserViewsVariables();
			$this->sprintSwitchToMenu();		

			/*if( (get_param(1) != 'attendance' && get_param(2) != 'manage') ) {
				$this->getNewNotifications();		
			}*/
			
			$this->hdrNotification();
			$this->hdrSyncData();			
			$this->generateYearlyCutoffPeriods();
			$this->generateYearlyWeeklyCutoffPeriods();
			
			//$this->autoResetConvertLeaveCredits();
			$this->updateEmployeeLeaveCredit();
			$this->updateBirthdayLeaveCredit();
			$this->yearlyLeaveAutoReset();
			$this->updateFiscalYear();

			//$this->validateIpAddress();		
	        $u = G_User_Finder::findByEmployeeId(Utilities::decrypt($this->global_user_eid));
			if($u) {
	            $this->var['user'] = $u;
			}
			
			$this->appVersion();
			$this->companySettings();		
			$this->userInitialPayPeriodModalBox();

			$this->var['hdr_help_url'] = help_url();

		}

		$this->notificationSettings();
	}

	function generateYearlyCutoffPeriods() {
		 $year   = date('Y');

		$total_cutoff = G_Cutoff_Period_Helper::countTotalCutoffByYear($year);
		if( $total_cutoff <= 12 ){
			//Generate cutoff periods
			$pp = G_Settings_Pay_Period_Finder::findDefault();
			if( $pp ){
				$cutoff   = explode(",", $pp->getCutOff());
				$cutoff_a = explode("-", $cutoff[0]); 
				$cutoff_b = explode("-", $cutoff[1]);
				$payout   = explode(",", $pp->getPayOutDay());
				
				$data[1]['a']      = $cutoff_a[0];
				$data[1]['b']      = $cutoff_a[1];
				$data[1]['payday'] = $payout[0];
				$data[2]['a']      = $cutoff_b[0];;
				$data[2]['b']      = $cutoff_b[1];;
				$data[2]['payday'] = $payout[1];

				$c = new G_Cutoff_Period();

				$return = $c->setNumberOfMonths(12)->generateIniCutOffPeriods($data);	
			}
		}
	}

	function generateYearlyWeeklyCutoffPeriods($start_date = false)
	{
		$year   = date('Y');
		$total_cutoff = G_Weekly_Cutoff_Period_Helper::countTotalCutoffByYear($year);
		if ($total_cutoff <= 12) {
			//Generate cutoff periods

			$year_now = date('Y');
			//start friday of current month
			//$given_year = strtotime($year_now);
			// start of year

			if (!$start_date) {

				$cutoff = G_Weekly_Cutoff_Period_Helper::getWeeklyCutoffPeriod();

				$seperate_day = explode("-", $cutoff);
				$start_date = $seperate_day[0];
			}

			$created  = date("Y-m-d H:i:s");

			$given_year = strtotime("1 January ". $year);

			$weekly = G_Weekly_Cutoff_Period_Helper::getWeeklyCutoffPeriod();

			$for_start = strtotime($start_date, $given_year);
			$for_end = strtotime('+1 year', $given_year);
			$year = date('Y', $given_year);


			$array_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

			$array_seperate = array();
			$array_cutoffs = array();
			$data = array();

			for ($i = $for_start; $i <= $for_end; $i = strtotime('+1 week', $i)) {
				$get_year = date('Y', $i);

				foreach ($array_months as  $value) {
					$cutoff_number = 1;
					if ($get_year == $year_now) {
						if (
							$value == date('F', $i)
						) {

							$data = [
								'month' => $value,
								'date_start' => date(
									'Y-m-d',
									$i
								),
								'date_end' =>  date(
									'Y-m-d',
									strtotime('+6 days', $i)
								)
							];
							array_push($array_cutoffs, $data);
						}
					}
				}
			}

			$weekly_by_group = G_Weekly_Cutoff_Period_Helper::generateWeeklyByGroup($array_cutoffs, "month");

			$insert_values = array();
			foreach ($weekly_by_group as $key => $value_by_group) {
				foreach ($array_months as $value_months) {
					if ($key == $value_months) {
						foreach ($value_by_group as $by_group_key => $value) {
							$cutoff = $by_group_key + 1;
							$values = "('" . Model::safeSql($year) . "'," . Model::safeSql($value['date_start']) . "," . Model::safeSql($value['date_end']) . "," . Model::safeSql($value['date_end']) . "," . $cutoff . "," . G_Salary_Cycle::TYPE_SEMI_MONTHLY . ",'" . G_Cutoff_Period::NO . "','" . G_Cutoff_Period::NO . "')";
							"<br>";

							array_push($insert_values, $values);
						}
					}
				}
			}


			$insert_sql_queries = implode(",", $insert_values);

			$return = G_Weekly_Cutoff_Period_Manager::bulkInsertWeeklyCutoff($insert_sql_queries);
		}
	}



// -------need to move

function generateWeeklyPeriod2(){
        $given_year = strtotime("1 January 2020");
        $for_start = strtotime('Wednesday', $given_year);
        $for_end = strtotime('+1 year', $given_year);
        for ($i = $for_start; $i <= $for_end; $i = strtotime('+1 week', $i)) {
            echo date('l Y-m-d', $i) . ' - ';
            echo date('l Y-m-d', strtotime('+6 days',$i)) . ' <br> ';

        }
    }

    // public function generateWeeklyByGroup($arr, $group, $preserveGroupKey = false, $preserveSubArrays = false) {
    //     $temp = array();
    //     foreach($arr as $key => $value) {
    //         $groupValue = $value[$group];
    //         if(!$preserveGroupKey)
    //         {
    //             unset($arr[$key][$group]);
    //         }
    //         if(!array_key_exists($groupValue, $temp)) {
    //             $temp[$groupValue] = array();
    //         }

    //         if(!$preserveSubArrays){
    //             $data = count($arr[$key]) == 1? array_pop($arr[$key]) : $arr[$key];
    //         } else {
    //             $data = $arr[$key];
    //         }
    //         $temp[$groupValue][] = $data;
    //     }
    //     return $temp;
    // }

    // public static function bulkInsertWeeklyCutoff($values){
    //     $sql = "INSERT INTO g_weekly_cutoff_period (year_tag,period_start,period_end,payout_date,cutoff_number,salary_cycle_id,is_lock,is_payroll_generated) 
    //         VALUES ".$values."
    //     ";
        
    //     Model::runSql($sql);
    //     return mysql_insert_id();    
    // }










	// --------need to move

	function getNewNotifications() {

		$from = $_GET['from'];
		$to   = $_GET['to'];

		if($from && $to) {
			$n = new G_Notifications();
	        $n->updateNotifications($from, $to);
		} else {
			$n = new G_Notifications();
	        $n->updateNotifications();
		}

        $this->global_total_notifications = $n->countNotifications();
        $this->var['new_notifications']   = $this->global_total_notifications;
	}
		
	function hdrNotification() {
		//$n = new G_Notifications();
	    //$n->updateNotifications();
	    $new_notifications = $this->global_total_notifications;

		$btn_notification_config = array(
		   	'module'	=> 'hr',
		   	'parent_index'	=> 'reports',
		   	'child_index'	=> 'reports_notifications',
		   	'required_permission' => Sprint_Modules::PERMISSION_01,
		   	'href' => url("notifications"),
		   	'onclick' => '',
		   	'id' => '',
		   	'class' => '',
		   	'icon' => '',
		   	'additional_attribute' => '',
		   	'wrapper_start' => "<div class=\"pt_notification\">",
		   	'caption' => "<i></i>Notification <span id=\"noti_count\" class=\"noti_count\">" . ($new_notifications > 0 ? $new_notifications : '') . "</span>",
		   	'wrapper_end' => "</div>"
	   	); 

		$this->var['hdr_notification'] = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_notification_config);

	}    			

	function hdrSyncData() {

		$btn_sync_data_config = array(
		   	'module'	=> 'hr',
		   	'parent_index'	=> 'settings',
		   	'child_index'	=> '',
		   	'required_permission' => Sprint_Modules::PERMISSION_03,
		   	'href' => "javascript:void(0);",
		   	'onclick' => '',
		   	'id' => 'btn-sync-data',
		   	'class' => '',
		   	'icon' => '',
		   	'additional_attribute' => '',
		   	'wrapper_start' => "<div class=\"pt_notification\">",
		   	'caption' => " <span class='icon-refresh icon-white'></span> Sync </a>",
		   	'wrapper_end' => "</div>"
	   	); 

		$xmlUrl = $_SERVER['DOCUMENT_ROOT'].MAIN_FOLDER. 'files/xml/settings/add_ons.xml';
		if(Tools::isFileExist($file)==true) {
			$xmlStr = file_get_contents($xmlUrl);
			$xmlStr = simplexml_load_string($xmlStr);

			$xml   = new Xml;
			$arrXml = $xml->objectsIntoArray($xmlStr);							
			$obj    = $xmlStr->xpath('//addons');
			$result = $obj[0]->employee_online_portal;				
		}else{
			$result = 'false';
		}

		if($result == "true") {
			$this->var['hdr_btn_sync_data'] = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_sync_data_config);
		}
	}

	function userInitialPayPeriodModalBox() {		
		$ini_filename = Sprint_Tables::INIT_FILE;
		$ini_file     = TEMP_USER_FOLDER . $ini_filename;
		$ini_modal_script = '';		
		if( Tools::isFileExistDirectPath($ini_file) ){			
			$ini_modal_script  = 'iniUserModal();';
			$ini_modal_wrapper = "<div id='ini_user_modal'></div>";
			$ini_id_wrapper    = 'ini_user_modal'; 
			unlink($ini_file);			
		}

		$this->var['ini_id_wrapper']    = $ini_id_wrapper;
		$this->var['ini_modal_script']  = $ini_modal_script;
		$this->var['ini_modal_wrapper'] = $ini_modal_wrapper;
	}


	function validateIpAddress() {
		$client_ip = Tools::get_client_ip(); // get user ip address
		$check_connection = Tools::is_connected(); // check if the user has internet connection
		$valid_ip_file = $_SERVER['DOCUMENT_ROOT'] . MAIN_FOLDER . "files/files/valid_ip.txt"; //list of valid ip

		$io   = new IO_Reader();
		$io->setFileName($valid_ip_file);
		$valid_ip = $io->readTextFile();

		if($check_connection && (in_array($client_ip, $valid_ip[0]) === false)) {
			include APP_PATH . 'errors/forbidden.php';
			die();
		}
	}

	function appVersion() {
		$v    = new G_Sprint_Version();
		$data = $v->getAppVersion();

		$version_part = explode("/", $data);		
		$app_version  = trim($version_part[0]);		
		$this->var['hdr_sprint_app_version'] = $app_version;

	}

	function companySettings() {
		$this->var['hdr_settings_sync_interval'] = INTERVAL_SYNC_ATTENDANCE;
	}

	function notificationSettings() {
		$this->var['HR_NOTIF_ENABLE']       = "";
		$this->var['PAYROLL_NOTIF_ENABLE']  = "";

        $notif = G_Settings_Notifications_Finder::findAll();
        foreach($notif as $n) {
        	if($n->getTitle() == "HR") {
        		$this->var['HR_NOTIF_ENABLE']       = $n->getIsEnable();
        	}
        	if($n->getTitle() == "PAYROLL") {
        		$this->var['PAYROLL_NOTIF_ENABLE']  = $n->getIsEnable();
        	}
        }
	}	

	function generateCurrentCutOffPeriod(){				
		$cp = new G_Cutoff_Period();
		$cp->generateCurrentCutoffPeriod();
	}

	function sprintHdrMenu( $core_module = '', $parent_module = '' ){		
		if( $core_module == G_Sprint_Modules::HR ){		
			$user_actions = $this->global_user_hr_actions;			
		}elseif( $core_module == G_Sprint_Modules::PAYROLL ){
			$user_actions = $this->global_user_payroll_actions;
		}elseif( $core_module == G_Sprint_Modules::DTR ){
			$user_actions = $this->global_user_dtr_actions;
		}elseif( $core_module == G_Sprint_Modules::AUDIT_TRAIL ){
			$user_actions = $this->global_user_audit_trail_actions;
		}


		$menu = new Sprint_Menu_Builder($user_actions, $core_module, $parent_module);
		$header_menu = $menu->buildHeaderMenu();
		$this->var['hdr_sprint_menu'] = $header_menu;

		$hr_reports_child_index_arr = array(
			"absences"						=> "attendance_absence_data",
			"tardiness"						=> "display_absence_quota_information",
			"reports_overtime"				=> "display_overtime",
			"undertime"						=> "display_undertime",
			"reports_leave"					=> "display_leave",
			"manpower_count"				=> "display_manpower_count",
			"end_of_contract"				=> "display_end_of_contract",
			"reports_daily_time_record"		=> "display_daily_time_record",
			"inc_time_in_and_time_out"		=> "display_incomplete_time_in_out",
			"reports_timesheet"				=> "display_timesheet",
			"reports_employment_status"		=> "display_employment_status",
			"reports_ee_er_contribution"	=> "display_ee_er_contribution",
			"audit_trail"					=> "audit_trail_data"
			);

		foreach($hr_reports_child_index_arr as $key => $value) {
			$r = $this->validatePermission(G_Sprint_Modules::HR,'reports',$key,false);
			if($r != '') {
				$this->var['hr_report_default_module'] = url("reports/time_management#{$value}");
				break;
			}
		}

		$payroll_reports_child_index_arr = array(
			"payslip"			=> "payslip",
			"payroll_register"	=> "payroll_register",
			"sss"				=> "sss_r1a",
			"philhealth"		=> "philhealth",
			"pagibig"			=> "pagibig",
			"tax"				=> "tax"
			);

		foreach($payroll_reports_child_index_arr as $key => $value) {
			$r = $this->validatePermission(G_Sprint_Modules::PAYROLL,'reports',$key,false);
			if($r != '') {
				$this->var['payroll_report_default_module'] = url("payroll_reports/payroll_management#{$value}");
				break;
			}
		}
	}

	function sprintSwitchToMenu(){				
		$permissions['hr']       = $this->global_user_hr_actions;
		$permissions['payroll']  = $this->global_user_payroll_actions;
		$permissions['dtr']      = $this->global_user_dtr_actions;
		$permissions['employee'] = $this->global_user_employee_actions;		
		$permissions['audit_trail'] = $this->global_user_audit_trail_actions;		
		
		$menu = new Sprint_Menu_Builder($permissions);
		$switch_to = $menu->buildSwitchToMenu();

		$this->var['hdr_switch_to'] = $switch_to;
	}

	function redirectNoAccessModule( $core_module = '', $module = '' ){
		if( $core_module == G_Sprint_Modules::HR ){		
			$user_actions = $this->global_user_hr_actions;
		}elseif( $core_module == G_Sprint_Modules::PAYROLL ){			
			$user_actions = $this->global_user_payroll_actions;
		}elseif( $core_module == G_Sprint_Modules::DTR ){
			$user_actions = $this->global_user_dtr_actions;
		}elseif( $core_module == G_Sprint_Modules::AUDIT_TRAIL ){
			$user_actions = $this->global_user_audit_trail_actions;
		}

		$mod = new G_Sprint_Modules($core_module);
		$mod->validateUserCanAccessModule($user_actions, $module);
	}

	function isSessionFilesExists() {		
		$u = new G_Employee_User();
		$this->user_session_files = $u->isUserSessionFilesExists();
		if(!$this->user_session_files) {			
			$_SESSION['sprint_hr']['redirect_uri'] = $next;
			header("Location:".MAIN_FOLDER."index.php/login");
		}
	}

	function createUserViewsVariables() {
		$this->var['hdr_user_eid']           = $this->global_user_eid;
		$this->var['hdr_username']           = $this->global_user_username;
		$this->var['hdr_employee_name']      = $this->global_user_employee_name;
		$this->var['hdr_empployee_position'] = $this->global_user_position;
		$this->var['hdr_employee_code']      = $this->global_user_employee_code;
		$this->var['hdr_profile_image']      = $this->global_user_profile_image;
		$this->var['hdr_hr_actions']         = $this->global_user_hr_actions;
		$this->var['hdr_dtr_actions']        = $this->global_user_dtr_actions;
		$this->var['hdr_payroll_actions']    = $this->global_user_payroll_actions;
		$this->var['hdr_audit_trail_actions']    = $this->global_user_audit_trail_actions;
		$this->var['hdr_hr_override_access']      = G_Employee_User::OVERRIDE_HR_ACCESS;
		$this->var['hdr_payroll_override_access'] = G_Employee_User::OVERRIDE_PAYROLL_ACCESS;
		$this->var['hdr_dtr_override_access']     = G_Employee_User::OVERRIDE_DTR_ACCESS;
		$this->var['hdr_audit_trail_override_access'] = G_Employee_User::OVERRIDE_AUDIT_TRAIL_ACCESS;
	}

	function createGlobalUserVariables() {
		if( $this->user_session_files ){
			$u = new G_Employee_User();
			$user_data = $u->getUserInfoDataFromTextFile();

			//User info
			$this->global_user_session_id 			 = $user_data['user_info'][0][0];
			$this->global_user_hash       			 = $user_data['user_info'][0][1];
			$this->global_user_ecompany_structure_id = $user_data['user_info'][0][2];
			$this->global_user_eid 				     = $user_data['user_info'][0][3];
			$this->global_user_role_name 			 = $user_data['user_info'][0][4];
			$this->global_user_employee_name 	     = $user_data['user_info'][0][5];
			$this->global_user_employee_code  	     = $user_data['user_info'][0][6];
			$this->global_user_position  	         = $user_data['user_info'][0][7];
			$this->global_user_username 			 = $user_data['user_info'][0][8];
			$this->global_user_profile_image 		 = $user_data['user_info'][0][9];

			//User actions
			$hr_data       = $user_data['user_actions'][0];
			$dtr_data      = $user_data['user_actions'][1];
			$payroll_data  = $user_data['user_actions'][2];
			$employee_data = $user_data['user_actions'][3];
			$audit_trail_data = $user_data['user_actions'][4];

			$user_actions  = array();

			foreach($employee_data as $key => $value){					
				if( trim($value) != "no access" ){					
					$mod_actions = array();
					$mod_actions = explode(":", $value);
					$user_actions['employee'][$key]['module'] = $mod_actions[0]; 
					$user_actions['employee'][$key]['action'] = $mod_actions[1]; 
				}else{
					$this->global_user_employee_actions = $value;
				}
			}

			foreach($hr_data as $key => $value){					
				if( trim($value) != "no access" && trim($value) != G_Employee_User::OVERRIDE_HR_ACCESS){
					$mod_actions = array();
					$mod_actions = explode(":", $value);
					$user_actions['hr'][$key]['module'] = $mod_actions[0]; 
					$user_actions['hr'][$key]['action'] = $mod_actions[1]; 
				}else{
					$this->global_user_hr_actions = $value;
				}
			}

			foreach($dtr_data as $key => $value){					
				if( trim($value) != "no access" && trim($value) != G_Employee_User::OVERRIDE_DTR_ACCESS){					
					$mod_actions = array();
					$mod_actions = explode(":", $value);
					$user_actions['dtr'][$key]['module'] = $mod_actions[0]; 
					$user_actions['dtr'][$key]['action'] = $mod_actions[1]; 
				}else{
					$this->global_user_dtr_actions = trim($value);
				}
			}

			foreach($payroll_data as $key => $value){				
				if( trim($value) != "no access" && trim($value) != G_Employee_User::OVERRIDE_PAYROLL_ACCESS){
					$mod_actions = array();
					$mod_actions = explode(":", $value);
					$user_actions['payroll'][$key]['module'] = $mod_actions[0]; 
					$user_actions['payroll'][$key]['action'] = $mod_actions[1]; 
				}else{
					$this->global_user_payroll_actions = $value;
				}
			}

			foreach($audit_trail_data as $key => $value){				
				if( trim($value) != "no access" && trim($value) != G_Employee_User::OVERRIDE_AUDIT_TRAIL_ACCESS){
					$mod_actions = array();
					$mod_actions = explode(":", $value);
					$user_actions['audit_trail'][$key]['module'] = $mod_actions[0]; 
					$user_actions['audit_trail'][$key]['action'] = $mod_actions[1]; 
				}else{
					$this->global_user_audit_trail_actions = $value;
				}
			}
			
			if( !empty($user_actions['hr']) ){
				$this->global_user_hr_actions = $user_actions['hr'];
			}

			if( !empty($user_actions['dtr']) ){
				$this->global_user_dtr_actions = $user_actions['dtr'];
			}

			if( !empty($user_actions['payroll']) ){
				$this->global_user_payroll_actions = $user_actions['payroll'];
			}

			if( !empty($user_actions['employee']) ){
				$this->global_user_employee_actions = $user_actions['employee'];
			}

			if( !empty($user_actions['audit_trail']) ){
				$this->global_user_audit_trail_actions = $user_actions['audit_trail'];
			}

		}else{

			$this->global_user_session_id 			 = "";
			$this->global_user_hash       			 = "";
			$this->global_user_ecompany_structure_id = "";
			$this->global_user_eid 				     = "";
			$this->global_user_role_name 			 = "";
			$this->global_user_employee_name 	     = "";
			$this->global_user_employee_code  	     = "";
			$this->global_user_username 			 = "";
			$this->global_user_profile_image 		 = "";
			$this->global_user_payroll_actions 		 = "no access";
			$this->global_user_hr_actions      		 = "no access";
			$this->global_user_dtr_actions      	 = "no access";
			$this->global_user_audit_trail_actions   = "no access";
		}
	}

	function validatePermission($module = '', $parent_index = '', $child_index = '', $show_error_page = true) {

		if($module == G_Sprint_Modules::HR) {
			$global_user_action = $this->global_user_hr_actions;
		}elseif($module == G_Sprint_Modules::PAYROLL){
			$global_user_action = $this->global_user_payroll_actions;
		}elseif($module == G_Sprint_Modules::AUDIT_TRAIL){
			$global_user_action = $this->global_user_audit_trail_actions;
		}else{
			$global_user_action = $this->global_user_dtr_actions;
		}

		$permissions = new G_Validate_Permission($global_user_action);
		$permissions->setModule($module);
		$permissions->setParentIndex($parent_index);
		$permissions->setChildIndex($child_index);
		$permissions->setShowErrorPage($show_error_page);
		return $permissions->getUserPermission();
	}
	
	function getAdminUserInfo() {
		$e = G_Employee_Finder::findById(Utilities::decrypt($_SESSION['sprint_hr']['employee_id']));
		if($e){
			$this->var['au_name'] = $e->getLastname() . ", " . $e->getFirstname();

		}
	}

	function ajax_get_form_token() {
		Utilities::createFormToken();
	}

	function is_evaluation_version()
	{
		if(EVALUATION_VERSION == true){
			$logo = 'evaluation_logo.png';
		}else{
			$logo = 'logo.png';
		}
		$this->var['sprint_logo'] = '$logo';
	}
	
	function sprint_package() {
		$hr_mod 			 = $GLOBALS['module_package']['hr'];
		$attendance_mod = $GLOBALS['module_package']['attendance'];
		
		$this->var['hr_mod']         = $hr_mod;
		$this->var['attendance_mod'] = $attendance_mod;
	}
	
	function is_trial_period()
	{
		if(TRIAL_PERIOD == true){
			$this->var['is_trial_period'] = true;
		}else{
			$this->var['is_trial_period'] = false;
		}	
	}
	
	function login()
	{
		
	}
	
	function has_access_module()
	{
		$u = G_User_Finder::findByEmployeeId(Utilities::decrypt($_SESSION['sprint_hr']['employee_id']));

		if($u) {
			$mod = explode(',', $u->getModule());
		}

		$has_access_module = false;
		foreach($mod as $key=>$val) {
			if(($val == $this->module) || empty($this->module)) {
				$has_access_module = true;
			}
		}
		if(!$has_access_module) {
			header("Location:".MAIN_FOLDER."index.php/login");
		}	
	}
	
	function triggerAuditTrail($status,$action,$additional_details = NULL,$user) {
		if(!empty($user)) {
			$auser = $user;
		}else{
			$auser = $_SESSION['sprint_hr']['username'];
		}
		$audit = new Sprint_Audit();	
		$audit->setUser($user);
		$audit->setAction($action);
		$audit->setDetails($additional_details);
		$audit->triggerAudit($status); //0=fail, 1=success			
	}

	function triggerAudit($status,$user,$action,$additional_details = NULL)
	{
		$audit = new Sprint_Audit();			
		$audit->setUser($user);
		$audit->setAction($action);
		$audit->setDetails($additional_details);
		$audit->triggerAudit($status); //0=fail, 1=success	
	}

	function updateEmployeeLeaveCredit()
	{
		//Handler will trigger only once a day
		$notification   = new G_Notifications();
	    $event_type_arr = $notification->getEventTypeArray();
		$n = G_Notifications_Finder::findByEventType($event_type_arr['LEAVE_ADDED']);
		if( $n ){
			$month = date('m');
			$year  = date('Y');
			$day   = date('d');
			$date_created  = $n->getDateCreated();
			$date_modified = trim($n->getDateModified());

			if( $date_modified != '' ){
				$n_month = date("m",strtotime($date_modified));
				$n_year  = date("Y",strtotime($date_modified));
				$n_day   = date("d",strtotime($date_modified));
			}else{
				$n_month = date("m",strtotime($date_created));
				$n_year  = date("Y",strtotime($date_created));
				$n_day   = date("d",strtotime($date_created));
			}

			if( $n_month == $month && $n_day == $day && $n_year == $year ){
				return true;
			}

		}

        $slg 	= new G_Settings_Leave_General();
    	$return = $slg->applyCredits(true);	   

    	if( $return['total'] > 0 ){
    		//Add to notification	    	
	    	$n = G_Notifications_Finder::findByEventType($event_type_arr['LEAVE_ADDED']);
	        if( empty($n) ){
	        	$n = new G_Notifications();  	        	
       			$n->setDescription("Employees who have gained yearly leave increase");      	
	            $n->setEventType($event_type_arr['LEAVE_ADDED']);
	            $n->setStatus(G_Notifications::STATUS_NEW); 
	            $n->setDateCreated(date('Y-m-d H:i:s'));
	        }else{
	        	$n->setDateModified(date('Y-m-d H:i:s'));
	        }
	        $n->setItem($return['total']);
	        $n->save();        
    	}    	
	}

	function updateBirthdayLeaveCredit()
	{
		$employees = G_Employee_Finder::findAllActiveRegularEmployees();
		$year = date("Y");

		foreach($employees as $emp_key => $emp) {
			$emp_id 	   	= $emp->getId();
			$employee_name 	= $emp->getFirstName() . " " . $emp->getLastName();
			$leave_id = 8; //Birthday Leave ID

			$leave_exist = G_Employee_Leave_Available_Helper::sqlIsEmployeeLeaveTypeExist($emp_id, $leave_id, $year);

			if($leave_exist <= 0) {

				$e = new G_Employee_Leave_Available;
				$e->setId($row['id']);
				$e->setEmployeeId($emp_id);
				$e->setLeaveId($leave_id);
				$e->setNoOfDaysAlloted(1);
				$e->setNoOfDaysAvailable(1);	
				$e->setCoveredYear(date("Y"));
				$json = $e->saveEmployeeLeaveCredits();

				if( $json['is_success'] ) {
					if( empty($row['id']) ) {
						//add also on employee leave credit histor
						$h = new G_Employee_Leave_Credit_History();
						$h->setEmployeeId($emp_id);
						$h->setLeaveId($leave_id);
						$h->setCreditsAdded(1);
						$h->addToHistory();
					}
				}				
			}
			
		}

	}

	function autoResetConvertLeaveCredits()
	{
		//Handler will trigger only once a day
		/*$notification   = new G_Notifications();
    	$event_type_arr = $notification->getEventTypeArray();
		$n = G_Notifications_Finder::findByEventType($event_type_arr['LEAVE_RESET']);
		if( $n ){
			$month = date('m');
			$year  = date('Y');
			$day   = date('d');
			$date_created  = $n->getDateCreated();
			$date_modified = trim($n->getDateModified());

			if( $date_modified != '' ){
				$n_month = date("m",strtotime($date_modified));
				$n_year  = date("Y",strtotime($date_modified));
				$n_day   = date("d",strtotime($date_modified));
			}else{
				$n_month = date("m",strtotime($date_created));
				$n_year  = date("Y",strtotime($date_created));
				$n_day   = date("d",strtotime($date_created));
			}

			if( $n_month == $month && $n_day == $day && $n_year == $year ){
				return false;
			}

		}*/

		$sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_FISCAL_YEAR);
        $fiscal_year = $sv->getVariableValue();
        $fiscal_year = date("Y-m-d", strtotime($fiscal_year . " " . date("Y")));

        $a_fiscal_year = explode("-", $fiscal_year);
        $day   = date("d");
        $month = date("m");

        if( $a_fiscal_year[1] == $month && $a_fiscal_year[2] == $day ){
        	$slg = new G_Settings_Leave_General();        	        	        	
        	$slg->resetLeaveCreditsByLeaveId(11);
        	
        	//Add to notification
        	//Total leave reset   
        	/*if( $slg->total_leave_reset > 0 ){        		
		        $n = G_Notifications_Finder::findByEventType($event_type_arr['LEAVE_RESET']);
		        if( empty($n) ){
		        	$n = new G_Notifications();		
		        	$n->setDescription("Yearly leave balance reset");      	        	
		            $n->setEventType($event_type_arr['LEAVE_RESET']);
		            $n->setStatus(G_Notifications::STATUS_NEW); 
		            $n->setDateCreated(date('Y-m-d H:i:s'));
		        }else{
		        	$n->setDateModified(date('Y-m-d H:i:s'));
		        }
		        $n->setItem($slg->total_leave_reset);
		        $n->save();
        	}       	        	

            //Total leave converted to cash
            if( $slg->total_leave_converted_to_cash > 0 ){
            	$n = G_Notifications_Finder::findByEventType($event_type_arr['LEAVE_CONVERTED']);
		        if( empty($n) ){
		        	$n = new G_Notifications();		        	
		            $n->setEventType($event_type_arr['LEAVE_CONVERTED']);
		            $n->setDescription("Total leave that have been converted from credit to cash");      	
		            $n->setStatus(G_Notifications::STATUS_NEW); 
		            $n->setDateCreated(date('Y-m-d H:i:s'));
		        }else{
		        	$n->setDateModified(date('Y-m-d H:i:s'));
		        }
		        $n->setItem($slg->total_leave_converted_to_cash);
		        $n->save();
            }*/
        }
	}

	function yearlyLeaveAutoReset(){
		$yr = G_Sprint_Variables_Finder::findByVariableName("year_leave_reset");
		if($yr &&  $yr->getValue() == date('Y', strtotime('-1 year'))){
			$default_rule = G_Settings_Leave_General_Finder::findDefaultLeaveGeneralRule();
			if($default_rule->getConvertLeaveCriteria()==3){
				$lr = new G_Settings_Leave_General;
				if($lr->yearlyLeaveAutoReset()){
					if(!$yr)
					$yr->setVariableName('year_leave_reset');
					$yr->setValue(date('Y'));
					$yr->save();
				}

			}
		}else if(!$yr){
			$default_rule = G_Settings_Leave_General_Finder::findDefaultLeaveGeneralRule();
			if($default_rule->getConvertLeaveCriteria()==3){
				$lr = new G_Settings_Leave_General;
				if($lr->yearlyLeaveAutoReset()){
					if(!$yr)
					$yr = new G_Sprint_Variables();
					$yr->setVariableName('year_leave_reset');
					$yr->setValue(date('Y'));
					$yr->save();
				}

			}
		}
		
	}

	function updateFiscalYear(){
		$fiscal_year = G_Sprint_Variables_Finder::findByVariableName("default_fiscal_year");
		if($fiscal_year){
			if(!($fiscal_year->getValue() == FISCAL_YEAR)){
				if(!$fiscal_year){
					$fiscal_year = new G_Sprint_Variables();
					$fiscal_year->setVariableName('default_fiscal_year');
					$fiscal_year->setValue(FISCAL_YEAR);
					$fiscal_year->save();
				}else{
					$fiscal_year->setValue(FISCAL_YEAR);
					$fiscal_year->save();
				}
			}
		}
	}



	//General report / audit trail
	function triggerShrAuditTrail($user,$role,$module,$activity_action,$activity_type,$audited_action,$from,$to,$event_status,$position,$department) {

		if(!empty($user)) {
			$auser = $user;
		}else{
			$auser = $_SESSION['sprint_hr']['username'];
		}
		
		
		$audit = new Sprint_Shr_Audit();			
		//$audit->setShrUser($employee_id);
		$audit->setShrUser($auser);
		$audit->setShrRole($role);
		$audit->setShrModule($module);
		$audit->setShrActivityAction($activity_action);
		$audit->setShrActivityType($activity_type);
		$audit->setShrAuditedAction($audited_action);
		$audit->setShrFrom($from);
		$audit->setShrTo($to);
		$audit->setShrPosition($position);
		$audit->setShrDepartment($department);
		$audit->triggerShrAudit($event_status); //0=failed, 1=success	
		


	}
	

}
?>