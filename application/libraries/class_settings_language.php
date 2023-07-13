<?php
class Settings_Language {
	public $id;
	public $company_structure_id;
	public $language;		
	
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
	
	public function setLanguage($value) {
		$this->language = $value;
	}
	
	public function getLanguage() {
		return $this->language;
	}
}
?>