<?php
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Applicant_Login_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		//Loader::appMainUtilities();		
		Loader::appMainStyle('style_website.css');
		$this->c_date  			    = Tools::getCurrentDateTime('Y-m-d h:i:s','Asia/Manila');			
		$this->applicant_id 			 = $_SESSION['sprint_applicant']['applicant_id'];
		$this->company_structure_id = $_SESSION['sprint_applicant']['company_structure_id'];
		$this->username				 = $_SESSION['sprint_applicant']['username'];		
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
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		
		$e = G_Applicant_Logs_Finder::findById(Utilities::decrypt($_SESSION['sprint_applicant']['applicant_id']));
		
		$this->var['company_structure_id'] =  $_SESSION['sprint_applicant']['company_structure_id'];
		$this->var['username'] 			     =  $_SESSION['sprint_applicant']['username'];
		$this->var['applicant_name']	     =  $_SESSION['sprint_applicant']['username'];
		$this->var['applicant_id'] 		  =  $_SESSION['sprint_applicant']['applicant_id'];
		
		if(!empty($_SESSION['sprint_applicant']['redirect_uri'])) {
			$this->var['url_param']	= "?next=".$_SESSION['sprint_applicant']['redirect_uri']."&redirect=true";
		}
		
		$this->var['token'] = Utilities::createFormToken();
		$this->view->setTemplate('login.php');
		$this->view->render('login/index.php',$this->var);
	}
	
	function _login()
	{
		if(Utilities::isFormTokenValid($_POST['token'])) {
			$username  = $_POST['username'];
			$password  = $_POST['password'];
			$image     = BASE_FOLDER . 'themes/' . THEME . '/themes-images/login/error.png';
			
			$e 		  = G_Applicant_Logs_Finder::findByEmail($username);
			if($e) {
				if($e->getStatus() == G_Applicant_Logs::VALIDATED) {
					
					$csid = G_Company_Structure_Finder::findByMainParent();
					$encryptPass = Utilities::encrypt($password);
					
					if($e->getPassword() == $encryptPass) {
						$e->setCompanyStructureId($csid->getId());
						$e->createApplicantSessionInfo();						
						$return['is_success'] = 1;						
					} else {
						$return['is_success'] = 0;
						$return['message']    = '
								<div class="alert alert-block alert-error fade in">
									<button class="close" data-dismiss="alert" type="button" onclick="javascript:hideAlertBox();">×</button>
									<h4 class="alert-heading">
											<img src="' . $image . '" alt="error" class="error_icon" />Invalid Username or PASSWORD							
									</h4>
								</div>';
					}
					
				} else {
					$return['is_success'] = 0;
					$return['message']    = '
							<div class="alert alert-block alert-error fade in">
								<button class="close" data-dismiss="alert" type="button" onclick="javascript:hideAlertBox();">×</button>
								<h4 class="alert-heading">
										<img src="' . $image . '" alt="error" class="error_icon" />Account Not Yet Validated.							
								</h4>
							</div>';
				}
			} else {
				$return['is_success'] = 0;
				$return['message']    = '
						<div class="alert alert-block alert-error fade in">
							<button class="close" data-dismiss="alert" type="button" onclick="javascript:hideAlertBox();">×</button>
							<h4 class="alert-heading">
									<img src="' . $image . '" alt="error" class="error_icon" />Invalid USERNAME or Password							
							</h4>
						</div>';
			}			
		}
		
		$return['token'] = Utilities::createFormToken();
		echo json_encode($return);
	}
	
	function _isLogin()
	{
		$return = false;

		if ($_SESSION['sprint_applicant']['company_structure_id'] && $_SESSION['sprint_applicant']['username'] && $_SESSION['sprint_applicant']['applicant_id'])
		{
			$return = true;
		}
		
		return $return;
	}
	
	function _isAccountValidated()
	{
		$return = true;
		
		$v = G_Applicant_Logs_Finder::findById(Utilities::decrypt($_SESSION['sprint_applicant']['applicant_id']));
		if($v) {
			if($v->getStatus() == G_Applicant_Logs::PENDING) {
				$return = false;	
			} elseif($v->getStatus() == G_Applicant_Logs::EXPIRED) {
				$return = false;
			}
		}else{
			$return = false;
		}
		return $return;
	}
	
	function logout()
	{
		unset($_SESSION['sprint_applicant']);
		redirect('applicant_login');
	}	

}
?>