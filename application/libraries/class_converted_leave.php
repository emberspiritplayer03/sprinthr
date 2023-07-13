<?php
class Converted_Leave {
	public $id;
	public $employee_id;
	public $leave_id;
	public $year;	
	public $total_leave_converted;	
	public $amount;
	public $date_converted;
	public $created;	
	
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
	
	public function setYear($value) {
		$this->year = $value;
	}
	
	public function getYear() {
		return $this->year;
	}
	
	public function setTotalLeaveConverted($value) {
		$this->total_leave_converted = $value;
	}
	
	public function getTotalLeaveConverted() {
		return $this->total_leave_converted;
	}

	public function setAmount($value) {
		$this->amount = $value;
	}
	
	public function getAmount() {
		return $this->amount;
	}

	public function setDateConverted($value) {
		$this->date_converted = $value;
	}
	
	public function getDateConverted() {
		return $this->date_converted;
	}

	public function setCreated($value) {
		$this->created = $value;
	}
	
	public function getCreated() {
		return $this->created;
	}
}
?>