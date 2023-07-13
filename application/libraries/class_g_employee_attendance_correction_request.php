<?php
class G_Employee_Attendance_Correction_Request {
	
	public $id;
	public $company_structure_id;
	public $employee_id;
	public $date_applied;
	public $date_in;
	public $time_in;
	public $time_out;
	public $correct_date_in;
	public $correct_time_in;
	public $correct_time_out;
	public $comment;	
	public $is_approved;
	public $is_archive;

	const PENDING 		= 'Pending';
	const APPROVED 		= 'Approved';
	const DISAPPROVED	= 'Disapproved';
	
	const YES 			= 'Yes';
	const NO			= 'No';
	
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
	
	public function setDateIn($value) {
		$this->date_in = $value;	
	}
	
	public function getDateIn() {
		return $this->date_in;
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
	
	public function setCorrectDateIn($value) {
		$this->correct_date_in = $value;	
	}
	
	public function getCorrectDateIn() {
		return $this->correct_date_in;
	}
	
	public function setCorrectTimeIn($value) {
		$this->correct_time_in = $value;	
	}
	
	public function getCorrectTimeIn() {
		return $this->correct_time_in;
	}
	
	public function setCorrectTimeOut($value) {
		$this->correct_time_out = $value;	
	}
	
	public function getCorrectTimeOut() {
		return $this->correct_time_out;
	}
	
	public function setComment($value) {
		$this->comment = $value;	
	}
	
	public function getComment() {
		return $this->comment;
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
		return G_Employee_Attendance_Correction_Request_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Attendance_Correction_Request_Manager::delete($this);
	}
}

?>