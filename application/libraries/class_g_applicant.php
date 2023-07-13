<?php
class G_Applicant {
	public $id;
	public $hash;
	public $photo;
	public $employee_id;
	public $company_structure_id;	
	public $job_vacancy_id;
	public $job_id;
	public $application_status_id;
	public $lastname;
	public $firstname;
	public $middlename;
	public $extension_name;
	
	public $gender;
	public $marital_status;
	public $birthdate;
	public $birth_place;
	
	public $address;
	public $city;
	public $province;
	public $zip_code;
	public $country;
	public $home_telephone;
	public $mobile;
	public $email_address;
	public $qualification;
	public $applied_date_time;
	public $hired_date;
	public $rejected_date;
	public $resume_name;
	public $resume_path;
	
	public $sss_number;
	public $tin_number;
	public $pagibig_number;
	public $philhealth_number;
	
	public $position_applied;

	public $application_status;
	
	//objects
	protected $gcs;
		
	public function __construct() {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setHash($value) {
		$this->hash = $value;
	}
	
	public function getHash() {
		return $this->hash;
	}
	
	public function setPhoto($value) {
		$this->photo = $value;
	}
	
	public function getPhoto() {
		return $this->photo;
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function setJobVacancyId($value) {
		$this->job_vacancy_id = $value;
	}
	
	public function getJobVacancyId() {
		return $this->job_vacancy_id;
	}
	
	public function setJobId($value) {
		$this->job_id = $value;
	}
	
	public function getJobId() {
		return $this->job_id;
	}
	
	public function setApplicationStatusId($value) {
		$this->application_status_id = $value;
	}
	
	public function getApplicationStatusId() {
		return $this->application_status_id;
	}
	
	public function setLastname($value) {
		$this->lastname = $value;
	}
	
	public function getLastname() {
		return $this->lastname;
	}
	
	public function setFirstname($value) {
		$this->firstname= $value;
	}
	
	public function getFirstname() {
		return $this->firstname;
	}
	
	public function setMiddlename($value) {
		$this->middlename = $value;
	}
	
	public function getMiddlename() {
		return $this->middlename;
	}
	
	public function setExtensionName($value) {
		$this->extension_name= $value;
	}
	
	public function getExtensionName() {
		return $this->extension_name;
	}

	
	public function setGender($value) {
		$this->gender = $value;
	}
	
	public function getGender() {
		return $this->gender;
	}
	
	public function setMaritalStatus($value) {
		$this->marital_status = $value;
	}
	
	public function getMaritalStatus() {
		return $this->marital_status;
	}
	
	public function setAddress($value) {
		$this->address = $value;
	}
	
	public function getAddress() {
		return $this->address;
	}
	
	public function setBirthdate($value) {
		$this->birthdate = $value;
	}
	
	public function getBirthdate() {
		return $this->birthdate;
	}
	
	public function setBirthPlace($value) {
		$this->birth_place = $value;
	}
	
	public function getBirthPlace() {
		return $this->birth_place;
	}
	
	public function setCity($value) {
		$this->city = $value;
	}
	
	public function getCity() {
		return $this->city;
	}
	
	public function setProvince($value) {
		$this->province = $value;
	}
	
	public function getProvince() {
		return $this->province;
	}
	
	public function setZipCode($value) {
		$this->zip_code = $value;
	}
	
	public function getZipCode() {
		return $this->zip_code;
	}
	
	public function setCountry($value) {
		$this->country = $value;
	}
	
	public function getCountry() {
		return $this->country;
	}
	
	public function setHomeTelephone($value) {
		$this->home_telephone = $value;
	}
	
	public function getHomeTelephone() {
		return $this->home_telephone;
	}
	
	public function setMobile($value) {
		$this->mobile = $value;
	}
	
	public function getMobile() {
		return $this->mobile;
	}
	
	public function setEmailAddress($value) {
		$this->email_address = $value;
	}
	
	public function getEmailAddress() {
		return $this->email_address;
	}
	
	public function setQualification($value) {
		$this->qualification = $value;
	}
	
	public function getQualification() {
		return $this->qualification;
	}
	
	public function setAppliedDateTime($value) {
		$this->applied_date_time = $value;
	}
	
	public function getAppliedDateTime() {
		return $this->applied_date_time;
	}
	
	public function setResumeName($value) {
		$this->resume_name = $value;
	}
	
	public function getResumeName() {
		return $this->resume_name;
	}
	
	public function setResumePath($value) {
		$this->resume_path = $value;
	}
	
	public function getResumePath() {
		return $this->resume_path;
	}
	
	public function setPositionApplied($value) {
		$this->position_applied = $value;
	}
	
	public function getPositionApplied() {
		return $this->position_applied;
	}
	
	public function setApplicationStatus($value) {
		$this->application_status = $value;
	}
	
	public function getApplicationStatus() {
		return $this->application_status;
	}
	
	public function setHiredDate($value) {
		$this->hired_date = $value;
	}
	
	public function getHiredDate() {
		return $this->hired_date;
	}
	
	public function setRejectedDate($value) {
		$this->rejected_date = $value;
	}
	
	public function getRejectedDate() {
		return $this->rejected_date;
	}
	
	public function setSssNumber($value) {
		$this->sss_number = $value;	
	}
	
	public function getSssNumber() {
		return $this->sss_number;	
	}
	
	public function setTinNumber($value) {
		$this->tin_number = $value;	
	}
	
	public function getTinNumber() {
		return $this->tin_number;	
	}
	
	public function setPagibigNumber($value) {
		$this->pagibig_number = $value;	
	}
	
	public function getPagibigNumber() {
		return $this->pagibig_number;	
	}
	
	public function setPhilhealthNumber($value) {
		$this->philhealth_number = $value;	
	}
	
	public function getPhilhealthNumber() {
		return $this->philhealth_number;	
	}
		
	public function save () {
		return G_Applicant_Manager::save($this, $gcs);
	}
	
	public function delete() {
		return G_Applicant_Manager::delete($this);
	}
	
	public function cancelApplication() {

		//delete application requirements
		$r = G_Applicant_Requirements_Finder::findByApplicantId($this->getId());
		if($r) { $r->delete(); }
		
		//delete applicant examination
		$e = G_Applicant_Examination_Finder::findByApplicantId2($this->getId());
		if($e) { $e->deleteAllByApplicantId(); }
		
		//delete job applicant event
		$j = G_Job_Application_Event_Finder::findByApplicantId2($this->getId());
		if($j) { $j->delete(); }
		
		return G_Applicant_Manager::delete($this);
	}
	
}
?>