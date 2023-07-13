<?php
class Employee_Make_Up_Schedule_Request {
	public $id;
	public $employee_id;
	public $date_applied;	
	public $date_from;
	public $date_to;
	public $start_time;	
	public $end_time;
	public $comment;
	
	const YES 		= 'Yes';
	const NO 		= 'No';
	
	const PENDING 		= 'Pending';
	const APPROVED 		= 'Approved';
	const DISAPPROVED	= 'Disapproved';
	
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
	
	public function setDateFrom($value) {
		$this->date_from = $value;
	}
	
	public function getDateFrom() {
		return $this->date_from;
	}
	
	public function setDateTo($value) {
		$this->date_to = $value;
	}
	
	public function getDateTo() {
		return $this->date_to;
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
	
	public function setComment($value) {
		$this->comment = $value;
	}
	
	public function getComment() {
		return $this->comment;
	}
}
?>