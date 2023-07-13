<?php
class Employee_Attendance_V2 {
	protected $date;
	protected $employee_schedule_id;
	protected $schedule_type;
	protected $time_in;	
	protected $time_out;
	protected $project_site_id;
	protected $activity_id;
	protected $has_error;
	protected $error_message;
	
	public function __construct() {
			
	}
	
	public function setDate($value) {
		$this->date = $value;	
	}
	
	public function getDate() {
		return $this->date;	
	}

	public function setEmployeeScheduleId($value) {
		$this->employee_schedule_id = $value;	
	}
	
	public function getEmployeeScheduleId() {
		return $this->employee_schedule_id;	
	}

	public function setScheduleType($value) {
		$this->schedule_type = $value;	
	}
	
	public function getScheduleType() {
		return $this->schedule_type;	
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

	public function setProjectSiteId($value) {
		$this->project_site_id = $value;	
	}

	public function getProjectSiteId() {
		return $this->project_site_id;	
	}

	public function setActivityId($value) {
		$this->activity_id = $value;	
	}

	public function getActivityId() {
		return $this->activity_id;	
	}

	public function setHasError($value) {
		$this->has_error = $value;	
	}
	
	public function getHasError() {
		return $this->has_error;	
	}

	public function setErrorMessage($value) {
		$this->error_message = $value;	
	}
	
	public function getErrorMessage() {
		return $this->error_message;	
	}	

}
?>