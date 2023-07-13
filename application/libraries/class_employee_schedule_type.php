<?php

class Employee_Schedule_Type {
	public $id;
	public $date;
	public $employee_id;
	public $schedule_type;
	public $schedule_template_id;
	
	public function __construct() {
	
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}

	public function setDate($value) {
		$this->date = $value;
	}
	
	public function getDate() {
		return $this->date;
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}
	
	public function setScheduleType($value) {
		$this->schedule_type = $value;
	}
	
	public function getScheduleType() {
		return $this->schedule_type;
	}
	
	public function setScheduleTemplateId($value) {
		$this->schedule_template_id = $value;
	}
	
	public function getScheduleTemplateId() {
		return $this->schedule_template_id;
	}
}
?>