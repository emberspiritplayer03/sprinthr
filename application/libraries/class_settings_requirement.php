<?php

class Settings_Requirement {
	public $id;
	public $company_structure_id;
	public $name;
	
	
	public function __construct() {
	
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
	
	public function setName($value) {
		$this->name = $value;
	}
	
	public function getName() {
		return $this->name;
	}
}
?>