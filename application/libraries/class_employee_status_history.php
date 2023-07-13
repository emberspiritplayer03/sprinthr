<?php
class Employee_Status_History {
	public $id;
	public $employee_id;
	public $employee_status_id;	
	public $status;
	public $start_date;	
	public $end_date;
	
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
	
	public function setEmployeeStatusId($value) {
		$this->employee_status_id = $value;
	}
	
	public function getEmployeeStatusId() {
		return $this->employee_status_id;
	}
	
	public function setStatus($value) {
		$this->status = $value;
	}
	
	public function getStatus() {
		return $this->status;
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

}
?>