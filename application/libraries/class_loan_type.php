<?php
class Loan_Type {
	public $id;
	public $company_structure_id;
	public $loan_type;	
	
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
	
	public function setLoanType($value) {
		$this->loan_type = $value;
	}
	
	public function getLoanType() {
		return $this->loan_type;
	}
}
?>