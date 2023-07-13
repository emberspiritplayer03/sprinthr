<?php
class Employee_Undertime_Request {
	public $id;
	public $employee_id;
	public $date_applied;	
	public $date_of_undertime;
	public $time_out;
	public $reason;	
	
	const PENDING 		= 'Pending';
	const APPROVED 		= 'Approved';
	const DISAPPROVED	= 'Disapproved';
	
	const YES = 'Yes';
	const NO  = 'No';
	
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
	
	public function setDateApplied($value) {
		$this->date_applied = $value;
	}
	
	public function getDateApplied() {
		return $this->date_applied;
	}
	
	public function setDateOfUndertime($value) {
		$this->date_of_undertime = $value;
	}
	
	public function getDateOfUndertime() {
		return $this->date_of_undertime;
	}
	
	public function setTimeOut($value) {
		$this->time_out = $value;
	}
	
	public function getTimeOut() {
		return $this->time_out;
	}
	
	public function setReason($value) {
		$this->reason = $value;
	}
	
	public function getReason() {
		return $this->reason;
	}
}
?>