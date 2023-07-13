<?php
class Loan_Deduction_Type {
	public $id;
	public $company_structure_id;
	public $deduction_type;	
	
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
	
	public function setDeductionType($value) {
		$this->deduction_type = $value;
	}
	
	public function getDeductionType() {
		return $this->deduction_type;
	}
}
?>