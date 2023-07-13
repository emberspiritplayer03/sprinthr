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
class G_Job_Salary_Rate {
	public $id;
	public $company_structure_id;
	public $job_level;
	public $minimum_salary;
	public $maximum_salary;
	public $step_salary;

	
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;
	}
	
	public function setJobLevel($value) {
		$this->job_level = $value;
	}
	
	public function setMinimumSalary($value) {
		$this->minimum_salary = $value;
	}
	
	public function setMaximumSalary($value) {
		$this->maximum_salary = $value;
	}

	public function setStepSalary($value) {
		$this->step_salary = $value;
	}
	

	public function getId() {
		return $this->id;
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function getJobLevel() {
		return $this->job_level;
	}
	
	public function getMinimumSalary() {
		return $this->minimum_salary;
	}
	
	public function getMaximumSalary() {
		return $this->maximum_salary;
	}
	
	public function getStepSalary() {
		return $this->step_salary;
	}


				
	public function save() {
		return G_Job_Salary_Rate_Manager::save($this);
	}
	
	public function delete() {
		G_Job_Salary_Rate_Manager::delete($this);
	}
	
}
?>