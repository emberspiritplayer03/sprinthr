<?php
class Employee_Loan_Payment_Schedule {
	public $id;
	public $employee_id;
	public $loan_id;
	public $reference_number;	
	public $loan_payment_scheduled_date;
	public $amount_to_pay;
	public $amount_paid;
	public $date_paid;	
	public $is_lock;
	public $remarks;
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
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
	
	public function setReferenceNumber($value) {
		$this->reference_number = $value;
	}
	
	public function getReferenceNumber() {
		return $this->reference_number;
	}
	
	public function setLoanPaymentScheduledDate($value) {
		$this->loan_payment_scheduled_date = $value;
	}
	
	public function getLoanPaymentScheduledDate() {
		return $this->loan_payment_scheduled_date;
	}

	public function setAmountToPay($value){
		$this->amount_to_pay = $value;
	}

	public function getAmountToPay($value){
		return $this->amount_to_pay;
	}
	
	public function setAmountPaid($value) {
		$this->amount_paid = $value;
	}
	
	public function getAmountPaid() {
		return $this->amount_paid;
	}
	
	public function setDatePaid($value) {
		$this->date_paid = $value;
	}
	
	public function getDatePaid() {
		return $this->date_paid;
	}

	public function setIsLock($value) {
		$this->is_lock = $value;
	}
	
	public function getIsLock() {
		return $this->is_lock;
	}
	
	public function setRemarks($value) {
		$this->remarks = $value;
	}
	
	public function getRemarks() {
		return $this->remarks;
	}
}
?>