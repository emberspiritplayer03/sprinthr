<?php
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Register_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appStyle('style_website.css');
		$this->c_date  				= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
					
		$this->applicant_id 			 = $_SESSION['sprint_applicant']['applicant_id'];
		$this->company_structure_id = $_SESSION['sprint_applicant']['company_structure_id'];
		$this->username				 = $_SESSION['sprint_applicant']['username'];
		
		$this->var['profile_photo'] = 'test only';
		
		if($this->applicant_id){
			$count = G_Applicant_Profile_Helper::isApplicantLogIdExist(Utilities::decrypt($this->applicant_id));
						
			$this->a_has_applicant_info = $count;
			$this->is_profile_exist 	 = G_Applicant_Profile_Helper::isApplicantLogIdExist(Utilities::decrypt($this->applicant_id)); 
			$this->ahid 				    =  Utilities::createHash($this->applicant_id);
			$this->aeid 				    =  Utilities::encrypt($this->applicant_id);
		}
	}

	function index()
	{
		if(!empty($_GET['eid']) && !empty($_GET['hid'])){	
			if($this->applicant_id){
				$eid = $_GET['eid'];
				$hid = $_GET['hid'];
				Utilities::verifyHash(Utilities::decrypt($eid),$hid);
				$applicant_id = Utilities::decrypt($eid);			
				Loader::appScript("generic/main.js");
				Jquery::loadInlineValidation2();	
				//Jquery::loadTextBoxList();		
				Jquery::loadJqueryFormSubmit();
				Jquery::loadUploadify();
				
				$a = G_Applicant_Logs_Finder::findById($applicant_id);			
				
				$this->var['with_job_application'] = 0;
				$this->var['token']					  = Utilities::createFormToken();
				$this->var['date_appliend'] 		  = Tools::getCurrentDateTime('Y-m-d','Asia/Manila');
				$this->var['a']					     = $a;
				$this->var['page_title']			  = 'Step 02: Applicant Details';
				$this->view->setTemplate('template_fullwidth.php');
				$this->view->render('application/forms/registration_form_applicant_details.php',$this->var);
			}else{
				redirect();
			}			
		}else{	
			Jquery::loadInlineValidation2();				
			//Jquery::loadTextBoxList();
			Jquery::loadJqueryFormSubmit();
			Jquery::loadTipsy();						
			Loader::appScript('register.js');	
									
			$this->var['jeid']     			     = "none";
			$this->var['token']					  = Utilities::createFormToken();
			$this->var['action'] 			     = url('register/save_register');
			$this->var['page_title']= 'Applicant Registration';
			$this->view->setTemplate('template_fullwidth.php');
			$this->view->render('application/forms/register_form.php',$this->var);
		}
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
						$link = $user_log->generateVerificationLinkWithJobId($_POST['jeid']);
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
		
		if($saved) {
			
			$recruit_info = G_Applicant_Logs_Finder::findById($saved);
			if($recruit_info){
				$recruit_info->setDefaultPassword($password['password']);				
				$email = new Sprint_Email();							
				$email->setFrom("hr@sprinthr.com");
				$email->setTo($_POST["email_address"]);
				$email->setSubject("SprintHR : Step01 - Account Verification");
				$email->recruitmentSubMessageBodyEmail($recruit_info);				
				$email->mainSendEmail();	
			}				

		}
		
		$return['token'] = Utilities::createFormToken();
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