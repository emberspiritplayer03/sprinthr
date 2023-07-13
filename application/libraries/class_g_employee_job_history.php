<?php
class G_Employee_Job_History {
	
	public $id;
	public $employee_id;
	public $job_id;
	public $name;
	public $employment_status;
	public $start_date;
	public $end_date;
	


	
	function __construct($id = '') {
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
	
	public function setJobId($value) {
		$this->job_id = $value;	
	}
	
	public function getJobId() {
		return $this->job_id;
	}
	
	public function setName($value) {
		$this->name = $value;	
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setEmploymentStatus($value) {
		$this->employment_status = $value;	
	}
	
	public function getEmploymentStatus() {
		return $this->employment_status;
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
	
		
	public function save() {
		return G_Employee_Job_History_Manager::save($this);
	}
	
	public function resetEmployeeDefaultJob() {
		return G_Employee_Job_History_Manager::resetEmployeeDefaultJob($this);
	}
	public function resetEmployeeByJobHistoryId($job_id) {
		return G_Employee_Job_History_Manager::resetEmployeeByJobHistoryId($this,$job_id);
	}
	public function updateJobHistoryEndDate($history_id,$end_date) {
		return G_Employee_Job_History_Manager::updateJobHistoryEndDate($this,$history_id,$end_date);
	}
	public function delete() {
		return G_Employee_Job_History_Manager::delete($this);
	}
}

?>