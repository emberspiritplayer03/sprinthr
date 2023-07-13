<?php
class Attendance_Log_V2 {
	protected $date;
	protected $time;	
	protected $time_out;
	protected $type; // 'in' or 'out'	
	protected $remarks;
	protected $project_site_id;
	protected $activity_name;
	protected $activity_id;
	
	public function __construct() {
			
	}
	
	public function setDate($value) {
		$this->date = $value;	
	}
	
	public function getDate() {
		return $this->date;	
	}
	
	public function setTime($value) {
		$this->time = $value;	
	}
	
	public function getTime() {
		return $this->time;	
	}
	
	public function setTimeOut($value) {
		$this->time_out = $value;	
	}

	public function getTimeOut() {
		return $this->time_out;	
	}

	public function setType($value) {
		$this->type = $value;	
	}
	
	public function getType() {
		return $this->type;	
	}	

	public function setRemarks($remarks)
	{
		$this->remarks = $remarks;
	}

	public function getRemarks()
	{
		return $this->remarks;
		// return isset($this->remarks) && !empty($this->remarks) ? explode(':', str_replace(' ', '_', $this->remarks))[1] : null;
	}

	public function setProjectSiteId($value) {
		$this->project_site_id = $value;	
	}

	public function getProjectSiteId() {
		return $this->project_site_id;	
	}

	public function setActivityName($value) {
		$this->activity_name = $value;	
	}

	public function getActivityName() {
		return $this->activity_name;	
	}

	public function setActivityId($value) {
		$this->activity_id = $value;	
	}

	public function getActivityId() {
		return $this->activity_id;	
	}

}
?>