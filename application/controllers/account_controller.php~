<?php
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Account_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appStyle('style_website.css');
		$this->c_date = Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
	}

	function verification()
	{			
		if(!empty($_GET['hid']) && !empty($_GET['eid'])){
			$eid = $_GET['eid'];
			$hid = $_GET['hid'];
			
			Utilities::verifyHash(Utilities::decrypt($eid),$hid);				
			$dhid = Utilities::decrypt($eid);			
		
			$log = G_Applicant_Logs_Finder::findById($dhid);
			if($log){				
				$error = $log->validateAccount();								
				if($error == 0){		
					echo 1;
					//Activate Account
					$log->activateAccount();					
					$csid = G_Company_Structure_Finder::findByMainParent();					
					$log->setCompanyStructureId($csid->getId());					
					$log->createApplicantSessionInfo();						
					if($_GET['jeid']){
						redirect("job_vacancy/apply?eid=". $_GET['eid'] . "&hid=" . $_GET['hid'] . "&jeid=" . $_GET['jeid']);
					}else{
						redirect("register?eid=". $_GET['eid'] . "&hid=" . $_GET['hid']);
					}
				}else{
					Loader::appScript("account.js");
					$this->var['eid']       = $eid;
					$this->var['hid']			= $hid;
					$this->var['error']     = $error;		
					if($error == 2){
						$this->var['page_title']= 'Account Verification Error';
					}else{			
  						$this->var['page_title']= 'Account Verification Error';
  					}
					$this->view->setTemplate('template_register.php');					
					$this->view->render('account_verification/error.php',$this->var);
				}
				
			}else{
				$this->var['page_title']= 'Account Verification Error';
				$this->view->setTemplate('template_register.php');
				$this->view->render('account_verification/error.php',$this->var);
			}		
		}else{			
			$url = recruitment_url("register");
			header( 'Location: ' . $url) ;
		}
	}
	
	function resend_confirmation()
	{
		if(!empty($_POST['hid']) && !empty($_POST['eid'])){
			$eid = $_POST['eid'];
			$hid = $_POST['hid'];
			
			Utilities::verifyHash(Utilities::decrypt($eid),$hid);				
			$dhid = Utilities::decrypt($eid);			
		
			$log = G_Applicant_Logs_Finder::findById($dhid);
			if($log){
				$this->var['page_title']= 'Account Verification';
				$this->view->setTemplate('template_register.php');					
				$this->view->render('account_verification/resend_email.php',$this->var);
			}
		}else{
			redirect("registration");
		}
	}
	
	function _resend_confirmation()
	{
		if(!empty($_POST['hid']) && !empty($_POST['eid'])){
			$eid = $_POST['eid'];
			$hid = $_POST['hid'];
			
			Utilities::verifyHash(Utilities::decrypt($eid),$hid);				
			$dhid = Utilities::decrypt($eid);			
		
			$log = G_Applicant_Logs_Finder::findById($dhid);
			if($log){
				$new_link   = $log->generateVerificationLink();
				$new_pw     = $log->generateApplicantRandomPassword();
				$ip_country = $log->getUserIPAndCountry();
				
				$log->setIp($ip_country['ip']);
				$log->setCountry($ip_country['country']);
				$log->setPassword($new_pw['epassword']); 
				$log->setStatus(G_Applicant_Logs::PENDING);
				$log->setDateTimeCreated($this->c_date);
				$log->setLink($new_link);
				$saved = $log->save();
				
				$json['is_success'] = 1;
				$json['url']		  = url("account/resend_confirmation?eid={$eid}&hid={$hid}");				
			}else{
				$json['is_success'] = 0;
				$json['message']    = 'Invalid user account.';
				
			}
			echo json_encode($json);
		}
		
			$recruit_info = G_Applicant_Logs_Finder::findById($dhid);
						
			$recruit_info->setDefaultPassword($new_pw['password']);
			
			$email = new Sprint_Email();			
			$email->setFrom("hr@sprinthr.com");
			$email->setTo($recruit_info->getEmail());
			$email->setSubject("[SprintHR: Register] New Confirmation Link");
			$email->recruitmentMessageBodyEmail($recruit_info);
			$email->recruitmentConfirmationEmail();					
	}
	
	function _cancel_registration() 
	{
		if(!empty($_POST['hid']) && !empty($_POST['eid'])){
			$eid = $_POST['eid'];
			$hid = $_POST['hid'];
			
			Utilities::verifyHash(Utilities::decrypt($eid),$hid);				
			$dhid = Utilities::decrypt($eid);			
		
			$log = G_Applicant_Logs_Finder::findById($dhid);
			if($log){
				$log->delete();
				$json['is_success'] = 1;
				$json['url']		  = url("register");				
			}else{
				$json['message']    = "Invalid user account.";
			}
		}else{
			$json['is_success'] = 0;
			$json['message']    = "Invalid user account.";
		}
		echo json_encode($json);
	}
	
}
?>