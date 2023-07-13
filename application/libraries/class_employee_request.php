<?php
class Employee_Request {
	
	public $id;
	public $employee_id;
	public $settings_request_id;
	public $request_id;
	public $start_date;
	public $end_date;
	public $start_time;
	public $end_time;
	public $reason;
	public $status;
	public $date_created;
	
	const PENDING    = 0;
	const APPROVE    = 1;
	const DISAPPROVE = -1;
		
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
	
	public function setSettingsRequestId($value) {
		$this->settings_request_id = $value;
	}
	
	public function getSettingsRequestId() {
		return $this->settings_request_id;
	}
	
	public function setRequestId($value) {
		$this->request_id = $value;
	}
	
	public function getRequestId() {
		return $this->request_id;
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
	
	public function setReason($value) {
		$this->reason = $value;
	}
	
	public function getReason() {
		return $this->reason;
	}
	
	public function setStatus($value) {
		$this->status = $value;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function setDateCreated($value) {
		$this->date_created = $value;
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}
}
?>