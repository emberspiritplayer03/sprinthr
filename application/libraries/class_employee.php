<?php
class Employee {
	
	public $id;
	public $company_structure_id;
	public $salutation;
	public $employee_code;
	public $firstname;
	public $lastname;
	public $middlename;
	public $extension_name;
	public $nickname;
	public $birthdate;
	public $gender;
	public $marital_status;
	public $nationality;
	public $number_dependent;
	public $sss_number;
	public $tin_number;
	public $pagibig_number;
	public $philhealth_number;
	public $is_tax_exempted;
	public $photo;
	public $employment_status_id;
	public $employee_status_id;
	public $hired_date;
	public $resignation_date;
	public $endo_date;
	public $terminated_date;
	public $inactive_date;
    public $leave_date; // this is a copy of endo date, terminated date and resignation date
	public $eeo_job_category_id;
	public $is_confidential;
	public $frequency_id;
	public $cost_center;

	//new
	public $project_site_id;

	
	function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}

	public function getId() {
		return $this->id;
	}

	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;	
	}

	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function getSalutation() {
		return $this->salutation;
	}
	
	public function setSalutation($value) {
		$this->salutation = $value;	
	}
	
	public function setEmployeeCode($value) {
		$this->employee_code = $value;	
	}
	
	public function getEmployeeCode() {
		return $this->employee_code;	
	}
	
	public function setFirstname($value) {
		$this->firstname = $value;	
	}
	
	public function getFirstname() {
		return $this->firstname;	
	}
	
	public function setLastname($value) {
		$this->lastname = $value;	
	}
	
	public function getLastname() {
		return $this->lastname;	
	}
	
	public function setMiddlename($value) {
		$this->middlename = $value;	
	}
	
	public function getMiddlename() {
		return $this->middlename;	
	}
	
	public function setExtensionName($value) {
		$this->extension_name = $value;	
	}
	
	public function getExtensionName() {
		return $this->extension_name;	
	}
	
	
	public function setNickname($value) {
		$this->nickname = $value;	
	}
	
	public function getNickname() {
		return $this->nickname;	
	}
	
	public function setBirthdate($value) {
		$this->birthdate = $value;	
	}
	
	public function getBirthdate() {
		return $this->birthdate;	
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
	
	public function setNationality($value) {
		$this->nationality = $value;	
	}
	
	public function getNationality() {
		return $this->nationality;	
	}
	
	public function setNumberDependent($value) {
		$this->number_dependent = $value;	
	}
	
	public function getNumberDependent() {
		return $this->number_dependent;	
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

	public function setIsTaxExempted($value) {
		$this->is_tax_exempted = $value;	
	}
	
	public function getIsTaxExempted() {
		return $this->is_tax_exempted;	
	}
	
	public function setPhoto($value) {
		$this->photo = $value;	
	}
	
	public function getPhoto() {
		return $this->photo;	
	}
	
	public function setEmployeeStatusId($value) {
		$this->employee_status_id = $value;	
	}
	
	public function getEmployeeStatusId() {
		return $this->employee_status_id;	
	}

	public function setEmploymentStatusId($value) {
		$this->employment_status_id = $value;
	}

	public function getEmploymentStatusId() {
		return $this->employment_status_id;
	}
	
	public function setHiredDate($value) {
		$this->hired_date = $value;	
	}
	
	public function getHiredDate() {
		return $this->hired_date;	
	}
	
	public function setResignationDate($value) {
		$this->resignation_date = $value;
	}
	
	public function getResignationDate() {
		return $this->resignation_date;
	}
	
	public function setEndoDate($value) {
		$this->endo_date = $value;
	}	
	
	public function getEndoDate() {
		return $this->endo_date;
	}
	
	public function setTerminatedDate($value) {
		$this->terminated_date = $value;	
	}
	
	public function getTerminatedDate() {
		return $this->terminated_date;	
	}

	public function setInactiveDate($value) {
		$this->inactive_date = $value;	
	}
	
	public function getInactiveDate() {
		return $this->inactive_date;	
	}

    public function setLeaveDate($date) {
        $this->leave_date = $date;
    }

    public function getLeaveDate() {
        return $this->leave_date;
    }
		
	public function setEeoJobCategoryId($value) {
		$this->eeo_job_category_id = $value;	
	}
	
	public function getEeoJobCategoryId() {
		return $this->eeo_job_category_id;	
	}

	public function setSectionId($value) {
		$this->section_id = $value;	
	}
	
	public function getSectionId() {
		return $this->section_id;	
	}

	public function setIsConfidential($value) {
		$this->is_confidential = $value;	
	}
	
	public function getIsConfidential() {
		return $this->is_confidential;	
	}
	public function setFrequencyId($frequency_id) {
		$this->frequency_id = $frequency_id;	
	}
	
	public function getFrequencyId() {
		return $this->frequency_id;	
	}
	public function setCostCenter($cost_center) {
		$this->cost_center = $cost_center;	
	}
	
	public function getCostCenter() {
		return $this->cost_center;	
	}

	//new

	public function setProjectSiteId($value){
		$this->project_site_id = $value;
	}
	

	public function getProjectSiteId(){
		return $this->project_site_id;
	}
	
	
	
	
	public function getPosition($date = '') {
		Employee_Position::findByEmployeeAndDate($this, $date);
	}
	
	public function getFullname() {
		return $this->firstname .' ' . Tools::getFirstLetter($this->middlename) . '. ' . $this->lastname . ' '. $this->extension_name;	
	}
}

class Employee_Position {
	protected $position;
	protected $start_date;
	protected $end_date;
		
}
?>