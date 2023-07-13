<?php
class Pagibig_Table {
	public $id;
	public $salary_from;
	public $salary_to;	
	public $multiplier_employee;
	public $multiplier_employer;
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setSalaryFrom($value) {
		$this->salary_from = $value;
	}
	
	public function getSalaryFrom() {
		return $this->salary_from;
	}
	
	public function setSalaryTo($value) {
		$this->salary_to = $value;
	}
	
	public function getSalaryTo() {
		return $this->salary_to;
	}
	
	public function setMultiplierEmployee($value) {
		$this->multiplier_employee = $value;
	}
	
	public function getMultiplierEmployee() {
		return $this->multiplier_employee;
	}
	
	public function setMultiplierEmployer($value) {
		$this->multiplier_employer = $value;
	}
	
	public function getMultiplierEmployer() {
		return $this->multiplier_employer;
	}
}
?>