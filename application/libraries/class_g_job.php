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
class G_Job {
	public $id;
	public $company_structure_id;
	public $job_specification_id;
	public $title;
	public $is_active;
	
	public $name;
	public $description;
	public $duties;
	
	const ACTIVE   = 1;
	const INACTIVE = 0;
	
	const DEFAULT_JOB_SPECIFICATION_ID = 0;
	
	public function __construct($id = '') {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;
	}
	
	public function setJobSpecificationId($value) {
		$this->job_specification_id = $value;
	}
	
	public function setTitle($value) {
		$this->title = $value;
	}
	
	public function setIsActive($value) {
		$this->is_active = $value;
	}
	
	public function setName($value) {
		$this->name = $value;
	}
	
	public function setDescription($value) {
		$this->description = $value;
	}
	
	public function setDuties($value) {
		$this->duties = $value;
	}
	
	
	

	public function getId() {
		return $this->id;
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function getJobSpecificationId() {
		return $this->job_specification_id;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getIsActive() {
		return $this->is_active;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function getDuties() {
		return $this->duties;
	}

	public function setAsActive() {
		G_Job_Manager::setAsActive($this);
	}
	
	public function setAsNotActive() {
		G_Job_Manager::setAsIsNotArchive($this);
	}
	
	public function setAllNotActiveToActive() {
		G_Job_Manager::setAllNotActiveToActive($this);
	}
				
	public function save() {
		return G_Job_Manager::save($this);
	}
	
	public function delete() {
		G_Job_Manager::delete($this);
	}
	
	public function deleteAllActive() {
		G_Job_Manager::deleteAllActive();
	}
	
	public function saveToEmployee(G_Employee $e,$start_date, $end_date) {
		G_Job_Manager::saveToEmployee($this, $e,$start_date,$end_date);
	}
}
?>