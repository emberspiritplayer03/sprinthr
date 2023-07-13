<?php
class Settings_Company_Benefits {
	public $id;
	public $company_structure_id;
	public $benefit_code;
	public $benefit_name;
	public $benefit_type;
	public $benefit_description;
	public $is_taxable;
	public $is_archive;
	public $date_created;
			
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
        
        public function setBenefitCode($value) {
		$this->benefit_code = $value;
	}
	
	public function getBenefitCode() {
		return $this->benefit_code;
	}
	
	public function setBenefitName($value) {
		$this->benefit_name = $value;
	}
	
	public function getBenefitName() {
		return $this->benefit_name;
	}	
	
	public function setBenefitDescription($value) {
		$this->benefit_description = $value;
	}
	
	public function getBenefitDescription() {
		return $this->benefit_description;
	}
	
	public function setBenefitType($value) {
		$this->benefit_type = $value;
	}
	
	public function getBenefitType() {
		return $this->benefit_type;
	}
	
	public function setIsTaxable($value) {
		$this->is_taxable = $value;
	}
	
	public function getIsTaxable() {
		return $this->is_taxable;
	}	
	
	public function setBenefitAmount($value) {
		$this->benefit_amount = $value;
	}
	
	public function getBenefitAmount() {
		return $this->benefit_amount;
	}	
	
	public function setIsArchive($value) {
		$this->is_archive = $value;
	}
	
	public function getIsArchive() {
		return $this->is_archive;
	}	
	
	public function setDateCreated($value) {
		$this->date_created = $value;
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}
}
?>