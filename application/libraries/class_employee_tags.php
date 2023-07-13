<?php
class Employee_Tags {
	public $id;
	public $company_structure_id;
	public $employee_id;
	public $tags;	
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id;
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}
	
	public function setTags($value) {
		$this->tags = $value;
	}
	
	public function getTags() {
		return $this->tags;
	}
}
?>