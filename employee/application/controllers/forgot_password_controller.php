<?php
class Forgot_Password_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();		
		Loader::appStyle('style.css');		
		$this->c_date = Tools::getCurrentDateTime('Y-m-d h:i:s','Asia/Manila');		
		$this->company_structure_id = 1;
		//$_SESSION['sprint_hr']['change_password_attempt'] = 0;
		
	}

	function index()
	{		
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainInlineValidation();
		$this->var['attempt']	   = $_SESSION['sprint_hr']['change_password_attempt'];
		$this->var['page_title']   = 'Forgot Password';
		$this->var['module_title'] = 'Forgot Password';	
		$this->view->setTemplate('template_forgot_password.php'); //template_settings
		$this->view->render('forgot_password/index.php',$this->var);
		
	}
	
	function update_password()
	{
		if($_POST){
			$eAr['username']  = $_POST['username'];
			$eAr['firstname'] = $_POST['firstname'];
			$eAr['lastname']  = $_POST['lastname'];
			$eAr['birthdate'] = $_POST['birthdate'];
			
			$verify = G_Employee_Helper::isUsernameFirstNameLastNameBirthdateByCompanyStructureId($eAr,$this->company_structure_id);
			if($verify > 0){
				$e = G_Employee_Finder::findByFirstnameLastnameBirthdate($eAr,$this->company_structure_id);
				if($e){
					$u = G_Employee_User_Finder::findByEmployeeId($e->getId());
					if($u){						
						$u->setPassword(Utilities::encrypt($_POST['password']));
						$u->updatePassword();
						$_SESSION['sprint_hr']['change_password_attempt'] = 0;
						$return['is_success'] = 1;
						$return['message']    = '<div class="alert alert-info">Successfully changed your password.Click <a href="' . url('login') . '"><b>here</b></a> to return to login screen.</div>';			
					}else{
						$_SESSION['sprint_hr']['change_password_attempt']++;
						$return['attempt']    = $_SESSION['sprint_hr']['change_password_attempt'];
						$return['is_success'] = 2;						
						$return['message']    = '<div class="alert alert-block alert-error fade in">Entered credential does not exists. Kindly check again entered <b>Username,Firstname,Lastname and Birthdate</b>.</div>';			
					}
				}else{
					$_SESSION['sprint_hr']['change_password_attempt']++;
					$return['attempt']    = $_SESSION['sprint_hr']['change_password_attempt'];
					$return['is_success'] = 2;
					$return['message']    = '<div class="alert alert-block alert-error fade in">Entered credential does not exists. Kindly check again entered <b>Username,Firstname,Lastname and Birthdate</b>.</div>';			
				}
			}else{
				$_SESSION['sprint_hr']['change_password_attempt']++;
				$return['attempt']    = $_SESSION['sprint_hr']['change_password_attempt'];
				$return['is_success'] = 2;
				$return['message']    = '<div class="alert alert-block alert-error fade in">Entered credential does not exists. Kindly check again entered <b>Username,Firstname,Lastname and Birthdate</b>.</div>';			
			}
		}else{
			$_SESSION['sprint_hr']['change_password_attempt']++;
			$return['attempt']    = $_SESSION['sprint_hr']['change_password_attempt'];
			$return['is_success'] = 3;
			$return['message']    = '<div class="alert alert-block alert-error fade in">Verification incomplete.Kindly enter your <b>Username,Firstname,Lastname and Birthdate</b> for verification.</div>';			
		}
		
		echo json_encode($return);
	}
}
?>