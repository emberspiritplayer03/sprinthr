<?php
class Employee_Loan_Details {
	public $id;
	public $company_structure_id;
	public $employee_id;
	public $loan_id;	
	public $date_of_payment;
	public $amount;
	public $is_paid;	
	public $remarks;	
	
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
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}
	
	public function setLoanId($value) {
		$this->loan_id = $value;
	}
	
	public function getLoanId() {
		return $this->loan_id;
	}
	
	public function setDateOfPayment($value) {
		$this->date_of_payment = $value;
	}
	
	public function getDateOfPayment() {
		return $this->date_of_payment;
	}
	
	public function setAmount($value) {
		$this->amount = $value;
	}
	
	public function getAmount() {
		return $this->amount;
	}
	
	public function setIsPaid($value) {
		$this->is_paid = $value;
	}
	
	public function getIsPaid() {
		return $this->is_paid;
	}
}
?>