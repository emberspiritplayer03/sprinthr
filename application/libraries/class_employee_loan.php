<?php
class Employee_Loan {
	public $id;
	public $company_structure_id;
	public $employee_id;
	public $loan_type_id;	
	public $interest_rate;
	public $months_to_pay;
	public $loan_amount;
	public $amount_paid;
	public $total_amount_to_pay;
	public $deduction_per_period;
	public $deduction_type;	
	public $start_date;	
	public $end_date;
	public $status;
	
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
	
	public function setLoanTypeId($value) {
		$this->loan_type_id = $value;
	}
	
	public function getLoanTypeId() {
		return $this->loan_type_id;
	}

	public function setMonthsToPay($value) {
		$this->months_to_pay = $value;
	}
	
	public function getMonthsToPay() {
		return $this->months_to_pay;
	}
	
	public function setInterestRate($value) {
		$this->interest_rate = $value;
	}
	
	public function getInterestRate() {
		return $this->interest_rate;
	}
	
	public function setLoanAmount($value) {
		$this->loan_amount = $value;
	}
	
	public function getLoanAmount() {
		return $this->loan_amount;
	}
	
	public function setAmountPaid($value) {
		$this->amount_paid = $value;
	}
	
	public function getAmountPaid() {
		return $this->amount_paid;
	}

	public function setTotalAmountToPay($value) {
		$this->total_amount_to_pay = $value;
	}
	
	public function getTotalAmountToPay() {
		return $this->total_amount_to_pay;
	}

	public function setDeductionPerPeriod($value) {
		$this->deduction_per_period = $value;
	}
	
	public function getDeductionPerPeriod() {
		return $this->deduction_per_period;
	}
	
	public function setDeductionType($value) {
		$this->deduction_type = $value;
	}
	
	public function getDeductionType() {
		return $this->deduction_type;
	}
	
	public function setStartDate($value) {
		$this->start_date = $value;
	}
	
	public function getStartDate() {
		return $this->start_date;
	}
	
	public function setEndDate($value) {
		$this->end_date = $value;
	}
	
	public function getEndDate() {
		return $this->end_date;
	}
	
	public function setStatus($value) {
		$this->status = $value;
	}
	
	public function getStatus() {
		return $this->status;
	}
}
?>