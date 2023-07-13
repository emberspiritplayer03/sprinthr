<?php
class Settings_Leave_Credit {
	
	protected $id;
	protected $employment_years;
	protected $default_credit;
	protected $leave_id;
	protected $employment_status_id;
	protected $is_archived;
	
	function __construct() {}
	//id
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;
	}
	//employment_years
	public function setEmploymentYears($value) {
		$this->employment_years = $value;	
	}
	
	public function getEmploymentYears() {
		return $this->employment_years;
	}	
	//default_credit
	public function setDefaultCredit($value) {
		$this->default_credit = $value;	
	}
	
	public function getDefaultCredit() {
		return $this->default_credit;
	}
	//leave_id
	public function setLeaveId($value) {
		$this->leave_id = $value;	
	}
	
	public function getLeaveId() {
		return $this->leave_id;
	}	
	//employment_status_id
	public function setEmploymentStatusId($value) {
		$this->employment_status_id = $value;	
	}
	
	public function getEmploymentStatusId() {
		return $this->employment_status_id;
	}
	//is_archived
	public function setIsArchived($value) {
		$this->is_archived = $value;	
	}
	
	public function getIsArchived() {
		return $this->is_archived;
	}

}

?>
