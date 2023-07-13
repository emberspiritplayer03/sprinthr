<?php
/*
	Usage:
		$c = new Company('Gleent');
		$c->setBusinessDescription('Web Development Company');
		$c->setBusinessAddress('Laguna');
		$c->setBusinessEmail('info@gleent.com');
		$c->setBusinessPhoneNo('5024826');
		$c->save();	
*/
class G_Job_Employment_Status {
	public $id;
	public $job_id;
	public $company_structure_id;
	public $code;
	public $status;
	public $title;
	public $employment_status_id;
	public $employment_status;

	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function setJobId($value) {
		$this->job_id = $value;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;
	}
	
	public function setCode($value) {
		$this->code = $value;
	}
	
	public function setStatus($value) {
		$this->status = $value;
	}
	
	public function setTitle($value) {
		$this->title = $value;
	}
	
	public function setEmploymentStatusId($value) {
		$this->employment_status_id = $value;
	}
	
	public function setEmploymentStatus($value) {
		$this->employment_status = $value;
	}
	


	 public function getId() {
		return $this->id;
	}
	
	public function getJobId() {
		return $this->job_id;
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function getCode() {
		return $this->code;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getEmploymentStatusId() {
		return $this->employment_status_id;
	}
	
	public function getEmploymentStatus() {
		return $this->employment_status;
	}
				
	public function save() {
		return G_Job_Employment_Status_Manager::save($this);
	}
	
	public function delete() {
		G_Job_Employment_Status_Manager::delete($this);
	}
	
	public function countEmployee()
	{
		G_Job_Employment_Status_Helper::countEmployee($this);
	}
	

}
?>