<?php
class Employee_Breaktime {
	public $id;
	public $employee_id;
	public $date;
	public $time_in;
	public $time_out;
	public $late_hours;	
	
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
	
	public function setDate($value) {
		$this->date = $value;
	}
	
	public function getDate() {
		return $this->date;
	}

	public function setTimeIn($value) {
		$this->time_in = $value;
	}
	
	public function getTimeIn() {
		return $this->time_in;
	}

	public function setTimeOut($value) {
		$this->time_out = $value;
	}
	
	public function getTimeOut() {
		return $this->time_out;
	}

	public function setLateHours($value) {
		$this->late_hours = $value;
	}
	
	public function getLateHours() {
		return $this->late_hours;
	}


}
?>