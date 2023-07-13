<?php
class Global_Controller
{
	function __construct()
	{		
		
		Loader::helper(array('url', 'html'));
		Loader::sysLibrary('session');		
		$this->session = $this->var['session'] = new WG_Session(array('namespace' => 'user'));
		$this->session = $this->var['session'] = new WG_Session(array('namespace' => 'hr'));
		
		$session = new WG_Session(array('namespace' => 'editor'));
		$session->set('ckfinder_baseUrl', BASE_FOLDER_EDITOR);
		$session->set('ckfinder_baseDir', BASE_FOLDER_EDITOR);
		Loader::appMainUtilities();	
		Loader::appMainLibrary('class_subfolder_loader');
		Loader::includeScript('init.js');
		
		$is_login = G_User_Helper::isLogin();
		if(!$is_login) {
			header("Location:".MAIN_FOLDER."index.php/login");
		}
		
		if(MOD_CLERK == false){
			header("Location:".MAIN_FOLDER."index.php/login");
		}
		
		$this->has_access_module();
		$this->is_evaluation_version();
		
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
	
	function ajax_get_form_token() {
		Utilities::createFormToken();
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
		$has_access_module=false;
		foreach($mod as $key=>$val) {
			if($val=='clerk') {
				$has_access_module = true;
			}
		}
		if(!$has_access_module) {
			header("Location:".MAIN_FOLDER."index.php/login");
		}	
	}
}
?>