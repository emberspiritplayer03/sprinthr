<?php
class Shr_Audit_Trail {

	public $id;
	protected $employee_id;
	protected $user;
	protected $role;
	protected $module;
	protected $activity_action;
	protected $activity_type;
	protected $audited_action;
	protected $from;
	protected $to;
	protected $event_status;
	protected $position;
	protected $department;
	protected $audit_date;
	protected $audit_time;
	protected $ip_address;

	
	public function __construct() {
		
	}
	//id
	public function setShrId($value) {
		$this->id = $value;
	}
	
	public function getShrId() {
		return $this->id;
	}

	//employee id
	public function setShrEmployeeID($value) {
		$this->employee_id = $value;
	}

	public function getShrEmployeeID() {
		return $this->employee_id;
	}

	//user
	public function setShrUser($value) {
		$this->user = $value;
	}
	
	public function getShrUser() {
		return $this->user;
	}

	//role
	public function setShrRole($value) {
		$this->role = $value;
	}

	public function getShrRole() {
		return $this->role;
	}

	//module
	public function setShrModule($value) {
		$this->module = $value;
	}

	public function getShrModule() {
		return $this->module;
	}

	//activity action
	public function setShrActivityAction($value) {
		$this->activity_action = $value;	
	}

	public function getShrActivityAction() {
		return $this->activity_action;	
	}

	//activity type
	public function setShrActivityType($value) {
		$this->activity_type = $value;
	}

	public function getShrActivityType() {
		return $this->activity_type;
	}
	
	//audited action
	public function setShrAuditedAction($value) {
		$this->audited_action = $value;
	}

	public function getShrAuditedAction() {
		return $this->audited_action;
	}

	//from
	public function setShrFrom($value) {
		$this->from = $value;
	}

	public function getShrFrom() {
		return $this->from;
	}

	//to
	public function setShrTo($value) {
		$this->to = $value;
	}

	public function getShrTo() {
		return $this->to;
	}

	//event status
	public function setShrEventStatus($value) {
		$this->event_status = $value;
	}

	public function getShrEventStatus() {
		return $this->event_status;
	}

	//position
	public function setShrPosition($value) {
		$this->position = $value;
	}

	public function getShrPosition() {
		return $this->position;
	}

	//department
	public function setShrDepartment($value) {
		$this->department = $value;
	}

	public function getShrDepartment() {
		return $this->department;
	}
	
	//audit_date;
	public function setShrAuditDate($value) {
		$this->audit_date = $value;
	}
	
	public function getShrAuditDate() {
		return $this->audit_date;
	}

	//audit time
	public function setShrAuditTime($value) {
		$this->audit_time = $value;
	}

	public function getShrAuditTime() {
		return $this->audit_time;
	}

	//ip_address;
	public function setShrIpAddress($value) {
		$this->ip_address = $value;
	}
	
	public function getShrIpAddress() {
		return $this->ip_address;
	}

}
?>