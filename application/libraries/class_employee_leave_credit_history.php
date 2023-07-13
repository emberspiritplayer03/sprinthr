<?php
class Employee_Leave_Credit_History {
	public $id;
	public $leave_id;
	public $employee_id;
	public $credits_added;	
	public $date_added;	
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setLeaveId($value) {
		$this->leave_id = $value;
	}
	
	public function getLeaveId() {
		return $this->leave_id;
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}
	
	public function setCreditsAdded($value) {
		$this->credits_added = $value;
	}
	
	public function getCreditsAdded() {
		return $this->credits_added;
	}
	
	public function setDateAdded($value) {
		$this->date_added = $value;
	}
	
	public function getDateAdded() {
		return $this->date_added;
	}
}
?>