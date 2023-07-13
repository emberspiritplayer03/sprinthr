<?php
class G_Employee_Rest_Day_Request {
	
	public $id;
	public $company_structure_id;
	public $employee_id;
	public $schedule_id;
	public $date_applied;
	public $date_start;
	public $date_end;
	public $rest_day_comments;	
	public $is_approved;
	public $is_archive;

	const PENDING 		= "Pending";
	const APPROVED 		= "Approved";
	const DISAPPROVED	= "Disapproved";
	
	const YES = "Yes";
	const NO  = "No";
	
	function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id= $value;	
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}
	
	public function setScheduleId($value) {
		$this->schedule_id = $value;	
	}
	
	public function getScheduleId() {
		return $this->schedule_id;
	}
	
	
	public function setDateApplied($value) {
		$this->date_applied = $value;	
	}
	
	public function getDateApplied() {
		return $this->date_applied;
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
	
	public function setRestDayComments($value) {
		$this->rest_day_comments = $value;	
	}
	
	public function getRestDayComments() {
		return $this->rest_day_comments;
	}
	
	public function setIsApproved($value) {
		$this->is_approved = $value;	
	}
	
	public function getIsApproved() {
		return $this->is_approved;
	}
	
	public function setIsArchive($value) {
		$this->is_archive = $value;	
	}
	
	public function getIsArchive() {
		return $this->is_archive;
	}
	
	
	public function save() {
		return G_Employee_Rest_Day_Request_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Rest_Day_Request_Manager::delete($this);
	}
}

?>