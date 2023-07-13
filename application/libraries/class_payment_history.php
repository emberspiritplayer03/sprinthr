<?php
class Payment_History {
	protected $amount_paid;
	protected $date_paid;
	
	public function __construct() {
		
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
}
?>