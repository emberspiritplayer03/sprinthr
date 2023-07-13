<?php
class G_Applicant_Logs extends Applicant_Logs {

	protected $default_password;	

	protected $company_structure_id;	
	
	// CONSTANTS
	const PENDING 			= 'Pending';
	const EXPIRED 			= 'Expired';
	const VALIDATED		= 'Validated';
	
	const IS_YES			= 'Yes';
	const IS_NO				= 'No';
	
	const MAX_HR_VERIFICATION = 24;
		
	public function __construct() {}

	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}	
	
	public function setDefaultPassword($value) {
		$this->default_password = $value;
	}
	
	public function getDefaultPassword() {
		return $this->default_password;
	}
	
	public function getUserIPAndCountry() {		
		return G_Applicant_Logs_Helper::getUserIPAndCountry();
	}	
	
	public function generateApplicantRandomPassword() {		
		return G_Applicant_Logs_Helper::generateApplicantRandomPassword();
	}	
	
	public function generateVerificationLink() {		
		$id = $this->id;
		return G_Applicant_Logs_Helper::generateVerificationLink($id);
	}	
	
	public function generateVerificationLinkWithJobId($jeid) {		
		$id = $this->id;
		return G_Applicant_Logs_Helper::generateVerificationLinkWithJobId($id,$jeid);
	}	
	
	public function save() {		
		return G_Applicant_Logs_Manager::save($this);
	}
	
	public function activateAccount() {		
		return G_Applicant_Logs_Manager::activateAccount($this);
	}
	
	public function save_link() {		
		return G_Applicant_Logs_Manager::save_link($this);
	}
	
	public function checkIfExpired() {
		$total_hr = Tools::computeTimeDifferenceInHrs($this->date_time_created,Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila'));	
		if($total_hr >= self::MAX_HR_VERIFICATION){
			return true;		
		}else{
			return false;
		}	
	}
	
	public function validateAccount() {
		
		$is_expired = self::checkIfExpired();		
		if($is_expired){			
			self::setAccountToExpired();	
			$error = 1;
		}elseif($this->status == self::PENDING){
			$error = 0;
		}elseif($this->status == self::VALIDATED){
			$error = 2;
		}		
		return $error;	
	}
	
	public function generateEmailExistsError($error) {
		if($error > 0){
			$message = "<div style=\"margin-top:4px;width:200px;\" class=\"label label-warning\"><i class=\"icon-remove icon-white\"></i> Not Available</div>";
		}else{
			$message = "<div style=\"margin-top:4px;width:200px;\" class=\"label label-success\"><i class=\"icon-ok icon-white\"></i> Available</div>";
		}
		
		return $message;
	}
	
	public function createApplicantSessionInfo() {
		$session = new WG_Session(array('namespace' => 'sprint_applicant'));				
		$session->set('company_structure_id', $this->company_structure_id);
		$session->set('username',  $this->email);
		$session->set('applicant_name',  $this->lastname . ", " . $this->firstname);
		$session->set('applicant_id',Utilities::encrypt($this->id));
	}
	
	public function delete() {
		return G_Applicant_Logs_Manager::delete($this);
	}
	
	public function setAccountToExpired() {		
		G_Applicant_Logs_Manager::setAccountToExpired($this);
	}
}
?>