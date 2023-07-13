<?php
class G_Employee_Work_Experience {
	
	public $id;
	public $employee_id;
	public $company;
	public $address;
	public $job_title;
	public $from_date;
	public $to_date;
	public $comment;


	
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
	
	public function setCompany($value) {
		$this->company = $value;	
	}
	
	public function getCompany() {
		return $this->company;
	}
	
	public function setAddress($value) {
		$this->address = $value;	
	}
	
	public function getAddress() {
		return $this->address;
	}
	
	public function setJobTitle($value) {
		$this->job_title = $value;	
	}
	
	public function getJobTitle() {
		return $this->job_title;
	}
	
	public function setFromDate($value) {
		$this->from_date = $value;	
	}
	
	public function getFromDate() {
		return $this->from_date;
	}
	
	public function setToDate($value) {
		$this->to_date = $value;	
	}
	
	public function getToDate() {
		return $this->to_date;
	}
	
	public function setComment($value) {
		$this->comment = $value;	
	}
	
	public function getComment() {
		return $this->comment;
	}
	
		
	public function save() {
		return G_Employee_Work_Experience_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Work_Experience_Manager::delete($this);
	}
}

?>