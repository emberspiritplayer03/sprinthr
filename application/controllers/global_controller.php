<?php

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
		Loader::appUtilities();
		Loader::appLibrary('class_loader');
		Loader::includeScript('init.js');		
		
		$this->isSessionFilesExists();
		$this->createGlobalUserVariables();
		$this->createUserViewsVariables();
		$this->defaultModuleUrl();
		//$this->validateIpAddress();
	}

	function validateIpAddress() {
		$client_ip = Tools::get_client_ip(); // get user ip address
		$check_connection = Tools::is_connected(); // check if the user has internet connection
		$valid_ip_file = $_SERVER['DOCUMENT_ROOT'] . BASE_FOLDER . "files/files/valid_ip.txt"; //list of valid ip

		$io   = new IO_Reader();
		$io->setFileName($valid_ip_file);
		$valid_ip = $io->readTextFile();

		if($check_connection && (in_array($client_ip, $valid_ip[0]) === false)) {
			include APP_PATH . 'errors/forbidden.php';
			die();
		}
	}

	function createUserViewsVariables() {		
		$this->var['hdr_username']           = $this->global_user_username;
		$this->var['hdr_employee_name']      = $this->global_user_employee_name;
		$this->var['hdr_employee_code']      = $this->global_user_employee_code;
		$this->var['hdr_empployee_position'] = $this->global_user_position;
		$this->var['hdr_hr_actions']         = $this->global_user_hr_actions;
		$this->var['hdr_dtr_actions']        = $this->global_user_dtr_actions;
		$this->var['hdr_payroll_actions']    = $this->global_user_payroll_actions;
		$this->var['hdr_employee_actions']   = $this->global_user_employee_actions;
		$this->var['hdr_hr_override_access']      = G_Employee_User::OVERRIDE_HR_ACCESS;
		$this->var['hdr_payroll_override_access'] = G_Employee_User::OVERRIDE_PAYROLL_ACCESS;
		$this->var['hdr_dtr_override_access']     = G_Employee_User::OVERRIDE_DTR_ACCESS;
	}

	function getModuleUrl($allowed_modules = array(), $core_module = '') {
		$modules      = $allowed_modules;
		$module_name  = "";
		foreach( $modules as $module ){
			$action = $module['action'];
			if( $action != G_Sprint_Modules::PERMISSION_04 ){
				$module_name = $module['module'];
				break;
			}
		}

		if( !empty($module_name) ){
			$sprint_modules = new G_Sprint_Modules($core_module);
			$properties   = $sprint_modules->getModuleProperties($module_name);						
			//$redirect_url = str_replace(BASE_FOLDER, HR_BASE_FOLDER, $properties['url']);
			$redirect_url = $properties['url'];												
		}else{
			$redirect_url = "";		
		}

		return $redirect_url;
	}

	function defaultModuleUrl() {
		$hr_redirect_url       = self::getModuleUrl($this->global_user_hr_actions, G_Sprint_Modules::HR);
		$payroll_redirect_url  = self::getModuleUrl($this->global_user_payroll_actions, G_Sprint_Modules::PAYROLL);
		$dtr_redirect_url      = self::getModuleUrl($this->global_user_dtr_actions, G_Sprint_Modules::DTR);
		$employee_redirect_url = self::getModuleUrl($this->global_user_dtr_actions, G_Sprint_Modules::EMPLOYEE);

		if( empty($hr_redirect_url) ){
			$hr_redirect_url = hr_url("employee");		
		}

		if( empty($payroll_redirect_url) ){
			$payroll_redirect_url = hr_url("payroll_register/generation");
		}

		if( empty($dtr_redirect_url) ){
			$dtr_redirect_url = hr_url("dtr");
		}

		if( empty($employee_redirect_url) ){
			$employee_redirect_url = employee_url("dashboard");
		}

		$this->default_redirect_hr_url       = $hr_redirect_url;
		$this->default_redirect_dtr_url      = $dtr_redirect_url;
		$this->default_redirect_payroll_url  = $payroll_redirect_url;
		$this->default_redirect_employee_url = $employee_redirect_url; 
	}

	function isSessionFilesExists() {		
		$u = new G_Employee_User();
		$this->user_session_files = $u->isUserSessionFilesExists();
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
			$this->all_actions = $user_data['user_actions'];

			$hr_data       = $user_data['user_actions'][0];
			$dtr_data      = $user_data['user_actions'][1];
			$payroll_data  = $user_data['user_actions'][2];
			$employee_data = $user_data['user_actions'][3];
			$user_actions  = array();

			foreach($hr_data as $key => $value){					
				if( trim($value) != "no access" && trim($value) != G_Employee_User::OVERRIDE_HR_ACCESS){					
					$mod_actions = array();
					$mod_actions = explode(":", $value);
					$user_actions['hr'][$key]['module'] = $mod_actions[0]; 
					$user_actions['hr'][$key]['action'] = $mod_actions[1]; 
				}else{
					$this->global_user_hr_actions = trim($value);
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
					$this->global_user_payroll_actions = trim($value);
				}
			}

			foreach($employee_data as $key => $value){				
				if( trim($value) != "no access" && trim($value) != G_Employee_User::OVERRIDE_EMPLOYEE_ACCESS){
					$mod_actions = array();
					$mod_actions = explode(":", $value);
					$user_actions['employee'][$key]['module'] = $mod_actions[0]; 
					$user_actions['employee'][$key]['action'] = $mod_actions[1]; 
				}else{
					$this->global_user_employee_actions = trim($value);
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

    // APPLY AUTO SETTINGS AFTER LOGIN
    function applyAutoSettings() {
        // SUPPLY DEFAULT HOLIDAYS TO A NEW YEAR
        G_Holiday_Helper::copyDefaultHolidaySettings(Tools::getGmtDate('Y'));

        // ADD NEW CUTOFF PERIOD
        G_Cutoff_Period_Helper::addNewPeriod();
    }
	
	function create_global_token() {
		$this->var['g_token'] = Utilities::createFormToken();
	}
	
	function applicant_session_info() {
		if($_SESSION['sprint_applicant']){
			$this->var['hdr_email_address']    = $_SESSION['sprint_applicant']['username'];
			$this->var['hdr_applicant_name']   = $_SESSION['sprint_applicant']['applicant_name'];
		}
	}
	
	function count_applicant_total_application() {
		if($_SESSION['sprint_applicant']['username']){
			$count = G_Applicant_Helper::countTotalApplicantPendingApplicationByEmailAddress($_SESSION['sprint_applicant']['username']);
		}else{
			$count = 0;
		}
		$this->var['total_pending_applications'] = $count;
	}
	
	function login()
	{
		Loader::appController('login');	
		$login = new Login_Controller;
		if (!$login->_isLogin())
		{
			redirect('login');
		}
	}
	
	function audit_login()
	{
		Loader::appController('audit_login');	
		$login = new Audit_Login_Controller;
		if (!$login->_isLogin())
		{
			redirect('audit_login');
		}
		
	}
	
	function subLogin()
	{
		Loader::appController('login');	
		$login = new Login_Controller;
		if (!$login->_isLogin())
		{
			
		}else{
			redirect('login');
		}
	}
	
	function is_trial_period()
	{
		if(TRIAL_PERIOD == true){
			$this->var['is_trial_period'] = true;
		}else{
			$this->var['is_trial_period'] = false;
		}	
	}
	
	function is_evaluation_version()
	{
		if(EVALUATION_VERSION == true){
			$logo = 'evaluation_logo.png';
		}else{
			$logo = 'logo.png';
		}
		$this->var['sprint_logo'] = $logo;
	}
	
	function companyModuleEnabled()
	{
		$mod_package    = $GLOBALS['module_package']['attendance'];
		$mod_package_hr = $GLOBALS['module_package']['hr'];
		
		$this->var['mod_package']    = $mod_package;
		$this->var['mod_package_hr'] = $mod_package_hr;
	}
	
	function db_admin_login() {
		Loader::appController('login');
		$login = new Login_Controller;
		if ($login->isDatabaseLogin())
		{
			return true;
		}else{
			redirect('login/database');
		}
	}
	
	function triggerAuditTrail($status,$action,$additional_details = NULL,$user = NULL) {
		if(!empty($user)) {
			$auser = $user;
		}else{
			$auser = $_SESSION['sprint_hr']['username'];
		}
		$audit = new Sprint_Audit();			
		$audit->setUser($auser);
		$audit->setAction($action);
		$audit->setDetails($additional_details);
		$audit->triggerAudit($status); //0=fail, 1=success			
	}

	
	function triggerAudit($status,$user,$action,$additional_details = NULL) {
		$audit = new Sprint_Audit();			
		$audit->setUser($user);
		$audit->setAction($action);
		$audit->setDetails($additional_details);
		$audit->triggerAudit($status); //0=fail, 1=success	
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