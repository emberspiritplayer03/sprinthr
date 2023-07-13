<?php

class Employee_Group_Schedule {
	public $id;
	public $employee_group_id;
	public $schedule_group_id;
	public $schedule_id;
	public $date_start;
	public $date_end;
	public $employee_group;
	
	
	public function __construct() {
	
	}
	
	public function setId($value) {
		$this->id_share = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setEmployeeGroupId($value) {
		$this->employee_group_id = $value;
	}
	
	public function getEmployeeGroupId() {
		return $this->employee_group_id;
	}
	
	public function setScheduleGroupId($value) {
		$this->schedule_group_id = $value;
	}
	
	public function getScheduleGroupId() {
		return $this->schedule_group_id;
	}
	
	public function setScheduleId($value) {
		$this->schedule_id = $value;
	}
	
	public function getScheduleId() {
		return $this->schedule_id;
	}
	
	public function setDateStart($value) {
		$this->date_start = $value;
	}
	
	public function getDateStart() {
		return $this->date_start;
	}
	
	public function setDateEnd($value) {
		$this->date_end = $value;
	}
	
	public function getDateEnd() {
		return $this->date_end;
	}
	
	public function setEmployeeGroup($value) {
		$this->employee_group = $value;
	}
	
	public function getEmployeeGroup() {
		return $this->employee_group;
	}
}
?>