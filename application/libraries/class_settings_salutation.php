<?php
class Settings_Salutation {
	public $id;
	public $company_structure_id;
	public $salutation;	
	public $description;
	
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
	
	public function setSalutation($value) {
		$this->salutation = $value;
	}
	
	public function getSalutation() {
		return $this->salutation;
	}
	
	public function setDescription($value) {
		$this->description = $value;
	}
	
	public function getDescription() {
		return $this->description;
	}
}
?>