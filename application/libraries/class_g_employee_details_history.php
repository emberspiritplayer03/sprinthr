<?php

class G_Employee_Details_History {
	public $id;
	public $employee_id;
	public $employee_code;
	public $remarks;
	public $history_date;
	public $modified_by;
	public $date_modifed;
	public $is_archive;
	
	const YES 	= 'Yes';
	const NO	= 'No';
	
	function __construct($id) {
		$this->id = $id;
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
	
	public function setEmployeeCode($value) {
		$this->employee_code = $value;	
	}
	
	public function getEmployeeCode() {
		return $this->employee_code;	
	}
	
	public function getFullname() {
		return $this->firstname .' '. $this->lastname;	
	}
	
	public function setModifiedBy($value) {
		$this->modified_by = $value;	
	}
	
	public function getModifiedBy() {
		return $this->modified_by;	
	}
	
	public function setRemarks($value) {
		$this->remarks = $value;	
	}
	
	public function getRemarks() {
		return $this->remarks;	
	}
	
	public function setHistoryDate($value) {
		$this->history_date = $value;	
	}
	
	public function getHistoryDate() {
		return $this->history_date;	
	}
	
	public function setDateModified($value) {
		$this->date_modifed = $value;	
	}
	
	public function getDateModified() {
		return $this->date_modifed;	
	}
	
	public function setIsArchive($value) {
		$this->is_archive = $value;	
	}
	
	public function getIsArchive() {
		return $this->is_archive;	
	}
	
	public function save () {
		return G_Employee_Details_History_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Details_History_Manager::delete($this);
	}
	
	public function archive() {
		return G_Employee_Details_History_Manager::archive($this);
	}

}

?>