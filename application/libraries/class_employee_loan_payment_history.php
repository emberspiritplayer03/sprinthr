<?php
class Employee_Loan_Payment_History{
	protected $id;	
	protected $employee_id;
	protected $loan_id;	
	protected $reference_number;
	protected $loan_payment_scheduled_date;
	protected $amount_to_pay;	
	protected $amount_paid;
	protected $date_paid;
	protected $remarks;
	protected $is_lock;
	
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
		if( $value != '' ){
			$date_format = date("Y-m-d",strtotime($value));
			$this->loan_payment_scheduled_date = $date_format;
		}else{
			$this->loan_payment_scheduled_date = '';
		}
	}
	
	public function getLoanPaymentScheduledDate() {
		return $this->loan_payment_scheduled_date;
	}
	
	public function setAmountToPay($value) {
		$this->amount_to_pay = $value;
	}
	
	public function getAmountToPay() {
		return $this->amount_to_pay;
	}
	
	public function setAmountPaid($value) {
		$this->amount_paid = $value;
	}
	
	public function getAmountPaid() {
		return $this->amount_paid;
	}

	public function setDatePaid($value) {
		if( $value != '' ){
			$date_format = date("Y-m-d",strtotime($value));
			$this->date_paid = $date_format;
		}else{
			$this->date_paid = '';
		}
	}
	
	public function getDatePaid() {
		return $this->date_paid;
	}
	
	public function setRemarks($value) {
		$this->remarks = $value;
	}
	
	public function getRemarks() {
		return $this->remarks;
	}

	public function setIsLock($value) {
		$this->is_lock = $value;
	}
	
	public function getIsLock() {
		return $this->is_lock;
	}

	public function requiredFields() {
		$required_fields = array("date_paid" => "Date Paid");
		return $required_fields;
	}
}
?>