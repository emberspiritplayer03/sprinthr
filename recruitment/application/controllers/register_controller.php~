<?php
class Register_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appStyle('style.css');
		$this->c_date  				= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
	}

	function index()
	{	
		Jquery::loadMainInlineValidation2();
		//Jquery::loadMainTextBoxList();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
				
		Loader::appMainScript('register.js');
		Loader::appMainScript('register_base.js');

		$this->var['token'] 		= Utilities::createFormToken();
		$this->var['action'] 	= url('register/save_register');

		$this->var['page_title']= 'Applicant Registration';
		$this->view->setTemplate('template_register.php');
		$this->view->render('register/form/register_form.php',$this->var);
	}
	
	function save_register()
	{
		if(Utilities::isFormTokenValid($_POST['token'])) {			
			$error = 0;
			if (!Tools::hasValue($_POST['firstname'])) { $error++; }
			if (!Tools::hasValue($_POST['lastname'])) { $error++; }
			if (!Tools::hasValue($_POST['email_address'])) { $error++; }
			
			//Check if email already exists
			$count   = G_Applicant_Logs_Helper::isEmailExist($_POST['email_address']);			
			//			
			if($count == 0){
				if($error == 0) {
					
					$al = new G_Applicant_Logs();	
					
					$user_info = $al->getUserIPAndCountry();
					$password  = $al->generateApplicantRandomPassword();
					
					$al->setIp($user_info['ip']);
					$al->setCountry($user_info['country']);
					$al->setFirstName($_POST['firstname']);
					$al->setLastName($_POST['lastname']);
					$al->setEmail($_POST['email_address']);
					$al->setPassword($password['epassword']);		
					$al->setIsPasswordChange(G_Applicant_Logs::IS_NO);		
					$al->setStatus(G_Applicant_Logs::PENDING);
					$al->setDateTimeCreated($this->c_date);
							
					$saved = $al->save();
					
					$user_log = G_Applicant_Logs_Finder::findById($saved);
					if($user_log){
						$link = $user_log->generateVerificationLink();
						$user_log->setLink($link);
						$user_log->save_link();
					} 
					
					if($saved) {				
						$return['message'] 		= '
						<div class="alert alert-info">
						<i class="icon-ok-sign"></i>
						<span class="message"></span>
						Thank you for registering. To complete the registration process, kindly check your registered email and follow the indicated instructions.
						</div>
						';	
						$return['is_success'] 	= 1;
					} else {
						$return['message'] 		= '
						<div class="ui-state-highlight ui-corner-all message_box">
						<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
						<span class="message"></span>
						Error Registering Applicant
						</div>
						';	
						$return['is_success'] 	= 0;				
					}
				
				} else {
						$return['message'] 		= '
						<div class="alert alert-error">
						<i class="icon-remove-sign"></i>
						<span class="message"></span>
						Error Registering Applicant
						</div>
						';	
						$return['is_success'] 	= 0;							
				}			
			}else{
				$return['message'] 		= '
				<div class="alert alert-error">
				<i class="icon-remove-sign"></i>
				<span class="message"></span>
				Email already used
				</div>
				';	
				$return['is_success'] 	= 0;
			}
		}else{
			$return['message'] 		= '
				<div class="alert alert-error">
				<i class="icon-remove-sign"></i>
				<span class="message"></span>
				Error Registering Applicant
				</div>
				';		
			$return['is_success'] 	= 0;		
		}
		
		$return['token'] = Utilities::createFormToken();
		
		if($saved) {
			
			$recruit_info = G_Applicant_Logs_Finder::findById($saved);
			if($recruit_info){
				$recruit_info->setDefaultPassword($password['password']);
				
				$email = new Sprint_Email();			
				$email->setFrom("hr@sprinthr.com");
				$email->setTo($_POST["email_address"]);
				$email->setSubject("SprintHR : Account Verification");
				$email->recruitmentMessageBodyEmail($recruit_info);
				$email->recruitmentConfirmationEmail();	
			}				

		}
		
		echo json_encode($return);						
	}

	function _check_email()
	{
		sleep(1);
		if($_POST['email']){			
			$count   = G_Applicant_Logs_Helper::isEmailExist($_POST['email']);
			
			$al      = new G_Applicant_Logs();			
			$message = $al->generateEmailExistsError($count);
		}
		$return['message'] = $message;
		echo json_encode($return);		
	}		

}
?>