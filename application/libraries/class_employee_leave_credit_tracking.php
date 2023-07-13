<?php
class Employee_Leave_Credit_Tracking {
	public $id;
	public $employee_id;
	public $leave_id;
	public $credit;	
	public $date;	
	
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
	
	public function setLeaveId($value) {
		$this->leave_id = $value;
	}
	
	public function getLeaveId() {
		return $this->leave_id;
	}
	
	public function setCredit($value) {
		$this->credit = $value;
	}
	
	public function getCredit() {
		return $this->credit;
	}
	
	public function setDate($value) {
		$this->date = $value;
	}
	
	public function getDate() {
		return $this->date;
	}
}
?>