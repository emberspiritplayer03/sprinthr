<?php
class G_Employee_Loan_Details extends Employee_Loan_Details {
	
	public $date_created;
	public $amount_paid;
	public $remarks;
	
	const YES = 'Yes';
	const NO  = 'No';
	
	//object
	protected $gel;
		
	public function __construct() {
		
	}
	
	public function setRemarks($value) {
		$this->remarks = $value;
	}
	
	public function getRemarks() {
		return $this->remarks;
	}	
	
	public function setAmountPaid($value) {
		$this->amount_paid = $value;
	}
	
	public function getAmountPaid() {
		return $this->amount_paid;
	}	
	
	public function setDateCreated($value) {
		$this->date_created = $value;
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}	
	
	public function save() {		
		return G_Employee_Loan_Details_Manager::save($this);
	}
	
	public function deleteAllUnpaidPaymentByLoanId(G_Employee_Loan $gel) {
		return G_Employee_Loan_Details_Manager::deleteAllUnpaidPaymentByLoanId($gel);
	}
	
	public function deleteAllByLoanId(G_Employee_Loan $gel) {
		return G_Employee_Loan_Details_Manager::deleteAllByLoanId($gel);
	}
	
	public function appendPayement() {
		return G_Employee_Loan_Details_Manager::appendPayement($this);
	}
	
	public function delete() {
		return G_Employee_Loan_Details_Manager::delete($this);
	}
}
?>