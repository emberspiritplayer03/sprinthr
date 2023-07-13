<?php
class Custom_Overtime{
	public $id;
	public $employee_id;
	public $date;
	public $start_time;
	public $end_time;
	public $day_type;
	public $status;
	
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
	
	public function setStartTime($value) {
		$this->start_time = $value;
	}
	
	public function getStartTime() {
		return $this->start_time;
	}
	
	public function setEndTime($value) {
		$this->end_time = $value;
	}
	
	public function getEndTime() {
		return $this->end_time;
	}
	
	public function setDayType($value) {
		$this->day_type = $value;
	}
	
	public function getDayType() {
		return $this->day_type;
	}

	public function setStatus($value) {
		$this->status = $value;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
}
?>