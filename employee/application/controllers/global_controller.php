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
		$this->createUserViewsVariables();

		$u = G_User_Finder::findByEmployeeId(Utilities::decrypt($this->global_user_eid));
		if($u) {
            $this->var['user'] = $u;
		}
		$this->appVersion();
		$this->companySettings();
		$this->getEmployeeNotifications();

		/*$this->isSessionFilesExists();
		
		
		$this->sprintSwitchToMenu();		
		$this->hdrNotification();
		$this->generateCurrentCutOffPeriod();

		//$this->validateIpAddress();		
        $u = G_User_Finder::findByEmployeeId(Utilities::decrypt($this->global_user_eid));
		if($u) {
            $this->var['user'] = $u;
		}
		
		$this->appVersion();
		$this->companySettings();
		$this->getNewNotifications();		
		$this->userInitialPayPeriodModalBox();
		$this->var['hdr_help_url'] = help_url();*/
	}

	function userInitialPayPeriodModalBox() {
		$new_session_id = session_id();
		$ini_file = TEMP_USER_FOLDER . 'ini_' . $new_session_id;
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

	function generateCurrentCutOffPeriod(){				
		$cp = new G_Cutoff_Period();
		$cp->generateCurrentCutoffPeriod();
	}

	function sprintHdrMenu( $core_module = '', $parent_module = '' ){		
		if( $core_module == G_Sprint_Modules::EMPLOYEE ){		
			$user_actions = $this->global_user_employee_actions;
		}

		$menu = new Sprint_Menu_Builder($user_actions, $core_module, $parent_module);
		$header_menu = $menu->buildHeaderMenu();
		$this->var['hdr_sprint_menu'] = $header_menu;

		
	}

	function sprintSwitchToMenu(){				
		$permissions['hr']      = $this->global_user_hr_actions;
		$permissions['payroll'] = $this->global_user_payroll_actions;
		$permissions['dtr']     = $this->global_user_dtr_actions;

		$menu = new Sprint_Menu_Builder($permissions);
		$switch_to = $menu->buildSwitchToMenu();

		$this->var['hdr_switch_to'] = $switch_to;
	}

	function isSessionFilesExists() {		
		$u = new G_Employee_User();
		$this->user_session_files = $u->isUserSessionFilesExists();
		if(!$this->user_session_files) {			
			$_SESSION['sprint_hr']['redirect_uri'] = $next;
			//header("Location:".BASE_FOLDER."index.php/login");
		}
	}

	function isLogin() {		
		if(!$this->user_session_files || !$this->global_user_eid) {			
			header("Location:".BASE_FOLDER."index.php/login");
		}
	}

	function createUserViewsVariables() {
		$this->var['hdr_user_eid']           = $this->global_user_eid;
		$this->var['hdr_username']           = $this->global_user_username;
		$this->var['hdr_employee_name']      = $this->global_user_employee_name;
		$this->var['hdr_empployee_position'] = $this->global_user_position;
		$this->var['hdr_employee_code']      = $this->global_user_employee_code;
		$this->var['hdr_profile_image']      = $this->global_user_profile_image;
		$this->var['hdr_payroll_actions']    = $this->global_user_employee_actions;
		$this->var['hdr_employee_override_access']      = G_Employee_User::OVERRIDE_EMPLOYEE_ACCESS;

	}

	function createGlobalUserVariables() {
		if( $this->user_session_files ){
			$u = new G_Employee_User();
			$user_data = $u->getUserInfoDataFromTextFile();
			//Utilities::displayArray($user_data);

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
			$employee_data      = $user_data['user_actions'][3];
			$user_actions = array();

			foreach($employee_data as $key => $value){					
				if( trim($value) != "no access" && trim($value) != G_Employee_User::OVERRIDE_EMPLOYEE_ACCESS){
					$mod_actions = array();
					$mod_actions = explode(":", $value);
					$user_actions['employee'][$key]['module'] = $mod_actions[0]; 
					$user_actions['employee'][$key]['action'] = $mod_actions[1]; 
				}else{
					$this->global_user_employee_actions = $value;
				}
			}

			if( !empty($user_actions['employee']) ){
				$this->global_user_employee_actions = $user_actions['employee'];
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
			$this->global_user_employee_actions      = "no access";
		}
	}

	function hdrNotification() {
	$n = new G_Notifications();
    $n->updateNotifications();
    $new_notifications = $n->countNotifications();

	$btn_notification_config = array(
	   	'module'	=> 'hr',
	   	'parent_index'	=> 'reports',
	   	'child_index'	=> 'reports_notifications',
	   	'required_permission' => Sprint_Modules::PERMISSION_03,
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

	function validatePermission($module = '', $parent_index = '', $child_index = '', $show_error_page = true) {

		if($module == G_Sprint_Modules::EMPLOYEE) {
			$global_user_action = $this->global_user_employee_actions;
		}

		$permissions = new G_Validate_Permission($global_user_action);
		$permissions->setModule($module);
		$permissions->setParentIndex($parent_index);
		$permissions->setChildIndex($child_index);
		$permissions->setShowErrorPage($show_error_page);
		return $permissions->getUserPermission();
	}

	function getNewNotifications() {
		$n = new G_Notifications();
        $n->updateNotifications();
        $this->var['new_notifications'] = $n->countNotifications();
	}

	function getEmployeeNotifications() {
		$user_id = Utilities::decrypt($this->global_user_eid);
		$r = new G_Request();
		$r->setApproverEmployeeId($user_id);
		$data = $r->getPendingForApprovalRequest();

		$this->var['emp_request_approval']  	= $data['needs_approval'];
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

}
?>