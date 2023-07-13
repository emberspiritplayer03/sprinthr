<?php
class Audit_Trail {
	public $id;
	public $user;
	public $action;
	public $event_status;
	public $details;	
	public $audit_date;
	public $ip_address;
	
	public function __construct() {
		
	}
	//id
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	//user
	public function setUser($value) {
		$this->user = $value;
	}
	
	public function getUser() {
		return $this->user;
	}
	//action
	public function setAction($value) {
		$this->action = $value;
	}
	
	public function getAction() {
		return $this->action;
	}
	//event_status
	public function setEventStatus($value) {
		$this->event_status = $value;
	}
	
	public function getEventStatus() {
		return $this->event_status;
	}
	//details;
	public function setDetails($value) {
		$this->details = $value;
	}
	
	public function getDetails() {
		return $this->details;
	}
	//audit_date;
	public function setAuditDate($value) {
		$this->audit_date = $value;
	}
	
	public function getAuditDate() {
		return $this->audit_date;
	}
	//ip_address;
	public function setIpAddress($value) {
		$this->ip_address = $value;
	}
	
	public function getIpAddress() {
		return $this->ip_address;
	}

}
?>