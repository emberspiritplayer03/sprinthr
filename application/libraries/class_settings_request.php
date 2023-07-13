<?php
class Settings_Request {
	public $id;
	public $title;
	public $type;
	public $departments;
	public $positions;
	public $employees;
	public $description;		
	
	const OT      	= 'Overtime';
	const LEAVE   	= 'Leave';
	const RESTDAY	= 'Rest Day';
	const CHANGED_SCHEDULE 	= 'Change Schedule';
	const UNDERTIME	= 'Undertime';
	const MAKE_UP	= 'Make Up Schedule';
	const OB		= 'Official Business';
	const GENERIC 	= 'Generic';
	const AC 		= 'Attendance Correction';
	
	const APPLY_TO_ALL = -1;
	const NA	    = -1;
			
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
	
	public function setTitle($value) {
		$this->title = $value;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setType($value) {
		$this->type = $value;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function setDepartments($value) {
		$this->departments = $value;
	}
	
	public function getDepartments() {
		return $this->departments;
	}
	
	public function setEmployees($value) {
		$this->employees = $value;
	}
	
	public function getEmployees() {
		return $this->employees;
	}
	
	public function setPositions($value) {
		$this->positions = $value;
	}
	
	public function getPositions() {
		return $this->positions;
	}
	
	public function setDescription($value) {
		$this->description = $value;
	}
	
	public function getDescription() {
		return $this->description;
	}
}
?>