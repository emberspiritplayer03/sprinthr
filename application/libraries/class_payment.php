<?php
class Payment {
	protected $name;
	protected $total_amount;
	protected $date_started;
	protected $payment_histories = array(); // array with instance of Payment_History
	
	public function __construct() {
		
	}
	
	public function setName($value) {
		$this->name = $value;	
	}
	
	public function getName() {
		return $this->name;	
	}
	
	public function setDateStarted($value) {
		$this->date_started = $value;	
	}
	
	public function getDateStarted() {
		return $this->date_started;	
	}	
	
	public function setTotalAmount($value) {
		$this->total_amount = $value;	
	}
	
	public function getTotalAmount() {
		return $this->total_amount;	
	}
	
	/*
		$ph = instance of Payment_History
	*/
	public function addPaymentHistory($ph) {
		$this->payment_histories[] = $ph;
	}
	
	public function getPaymentHistories() {
		return $this->payment_histories;
	}
}
?>