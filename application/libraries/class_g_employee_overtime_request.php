<?php
class G_Employee_Overtime_Request {
	
	public $id;
	public $company_structure_id;
	public $employee_id;
	public $date_applied;
	public $date_start;
	public $date_end;
	public $time_in;
	public $time_out;
	public $overtime_comments;	
	public $is_approved;
	public $is_archive;
	public $created_by;

	const PENDING 		= 'Pending';
	const APPROVED 		= 'Approved';
	const DISAPPROVED	= 'Disapproved';
	
	const YES           = 'Yes';
	const NO  		    = 'No';
	
	const ARCHIVE		= 1;
	
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
	
	public function setTimeIn($value) {
		$this->time_in = $value;	
	}
	
	public function getTimeIn() {
		return $this->time_in;
	}
	
	public function setTimeOut($value) {
		$this->time_out = $value;	
	}
	
	public function getTimeOut() {
		return $this->time_out;
	}
	
	public function setOvertimeComments($value) {
		$this->overtime_comments = $value;	
	}
	
	public function getOvertimeComments() {
		return $this->overtime_comments;
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
	
	public function setCreatedBy($value) {
		$this->created_by = $value;	
	}
	
	public function getCreatedBy() {
		return $this->created_by;
	}
	
	public function save() {
		return G_Employee_Overtime_Request_Manager::save($this);
	}
	
	public function approve() {
		return G_Employee_Overtime_Request_Manager::approve($this);
	}
	
	public function disapprove() {
		return G_Employee_Overtime_Request_Manager::disapprove($this);
	}
	
	public function delete() {
		return G_Employee_Overtime_Request_Manager::delete($this);
	}
}

?>