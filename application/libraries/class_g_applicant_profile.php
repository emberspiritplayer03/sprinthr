<?php
class G_Applicant_Profile extends Applicant_Profile {
	public $applicant_log_id;	
	public $company_structure_id;	
	
	public function __construct() {}

	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;
	}	
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function setApplicantLogId($value) {
		$this->applicant_log_id = $value;
	}	
	
	public function getApplicantLogId() {
		return $this->applicant_log_id;
	}
	
	public function copyApplicantProfile($other_details) {
		return G_Applicant_Profile_Helper::copyApplicantProfile($this,$other_details);
	}	
	
	public function save() {		
		return G_Applicant_Profile_Manager::save($this);
	}
		
	public function delete() {
		return G_Applicant_Profile_Manager::delete($this);
	}
	
}
?>