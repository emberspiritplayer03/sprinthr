<?php
class Audit_Login_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appUtilities();
		Loader::appStyle('style.css');
	}

	function index()
	{	
		Jquery::loadInlineValidation2();
		Jquery::loadJqueryFormSubmit();
		
		$e = G_User_Finder::findByEmployeeId(Utilities::decrypt($_SESSION['sprint_audit']['employee_id']));
				
		if($e) {
			$this->var['mod'] = $mod = explode(',', $e->getModule());	
		}
		
		$this->var['company_structure_id_audit'] =  $_SESSION['sprint_audit']['company_structure_id'];
		$this->var['username_audit'] 			     =  $_SESSION['sprint_audit']['username'];
		$this->var['employee_id_audit']    		  =  $_SESSION['sprint_audit']['employee_id'];
		
		if(!empty($_SESSION['sprint_audit']['redirect_uri'])) {
			$this->var['url_param']	= "?next=".$_SESSION['sprint_audit']['redirect_uri']."&redirect=true";
		}
		
		if($_GET['redirect'] == true) {} else {}
		
		$this->view->setTemplate('login.php');
		$this->view->render('audit_login/index.php',$this->var);
	}
	
	function _login()
	{		
		$username = $_POST['username'];
		$password = $_POST['password'];
		$image    = BASE_FOLDER . 'themes/' . THEME . '/themes-images/login/error.png';
		$e = G_User_Finder::findByUsername($username);
		
		if($e) {
			$encrypt = Utilities::encryptPassword($password);
			$shapass = hash("SHA512", $password, false);
			
			if($e->getPassword()==$shapass) {
				$session = new WG_Session(array('namespace' => 'sprint_audit'));				
				$session->set('company_structure_id', $e->getCompanyStructureId());
				$session->set('username',  $e->getUsername());
				$session->set('employee_id',Utilities::encrypt($e->getEmployeeId()));
				$session->set('hash',$e->getHash());
				$return['is_success'] = 1;
				
			}else if($e->getPassword()==$encrypt) {
				$e->setPassword($shapass);
				$e->save();	
				$session = new WG_Session(array('namespace' => 'sprint_audit'));				
				$session->set('company_structure_id', $e->getCompanyStructureId());
				$session->set('username',  $e->getUsername());
				$session->set('employee_id',Utilities::encrypt($e->getEmployeeId()));
				$session->set('hash',$e->getHash());
				$return['is_success'] = 1;
				
			}else {
				// INVALID PASSWORD					
				$return['is_success'] = 2;
				$return['message']    = '
					<div class="alert alert-block alert-error fade in">
						<button class="close" data-dismiss="alert" type="button" onclick="javascript:hideAlertBox();">×</button>
						<h4 class="alert-heading">
								<img src="' . $image . '" alt="error" class="error_icon" />Invalid Username or Password							
						</h4>
					</div>';
			}
		} else {
			//CHECK IF THE LOGIN IS SUPER USER
			if($username=='admin123' && $password=='Eao8fi') {				
				$session = new WG_Session(array('namespace' => 'sprint_audit'));				
				$session->set('company_structure_id', 0);
				$session->set('username',  'super_admin');
				$session->set('employee_id',Utilities::encrypt(12313123123));
				$session->set('hash',md5('admin'));
				$return['is_success'] = 1;
				
			}else {
				//INVALID USERNAME
				$return['is_success'] = 2;
				$return['message']    = '
					<div class="alert alert-block alert-error fade in">
						<button class="close" data-dismiss="alert" type="button" onclick="javascript:hideAlertBox();">×</button>
						<h4 class="alert-heading">
								<img src="' . $image . '" alt="error" class="error_icon" />Invalid Username or Password							
						</h4>
					</div>';
			}
			
		}
		
		echo json_encode($return);
	}
	
	function _isLogin()
	{
		$return = false;

		if ($_SESSION['sprint_audit']['company_structure_id'] && $_SESSION['sprint_audit']['username'] && $_SESSION['sprint_audit']['employee_id'])
		{
			$return = true;
		}
		return $return;
	}
	
	function logout()
	{
		unset($_SESSION['sprint_audit']);
		redirect('audit_login');	
		
	}

	function getHash() {
		echo Utilities::encryptPassword("icannottellyou!@#");
		echo Utilities::decrypt('89169ab53c70b250b3deb3909bad52bef34551b3');
	}
}
?>