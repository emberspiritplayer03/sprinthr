<?php
class G_Employee_Extend_Contract {
	
	public $id;
	public $employee_id;
	public $start_date;
	public $end_date;
	public $attachment;
	public $remarks;
	public $is_done;
	
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
	
	public function setStartDate($value) {
		$this->start_date= $value;	
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
	
	public function setAttachment($value) {
		$this->attachment = $value;	
	}
	
	public function getAttachment() {
		return $this->attachment;
	}
	
	public function setRemarks($value) {
		$this->remarks = $value;	
	}
	
	public function getRemarks() {
		return $this->remarks;
	}
	
	public function setIsDone($value) {
		$this->is_done = $value;	
	}
	
	public function getIsDone() {
		return $this->is_done;
	}
	
	
		
	public function save() {
		return G_Employee_Extend_Contract_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Extend_Contract_Manager::delete($this);
	}
}

?>