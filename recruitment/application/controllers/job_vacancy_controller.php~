<?php
class Job_Vacancy_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appStyle('style.css');

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
		$this->applicant_login();
				
		$al = G_Applicant_Logs_Finder::findById(Utilities::decrypt($_SESSION['sprint_applicant']['applicant_id']));
		if($al->getIsPasswordChange() == G_Applicant_Logs::IS_YES) {
			$this->job_vacancy_list();
		} else {
			$this->change_password();
		}
 		
	}
}
?>