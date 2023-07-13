<?php
class Break_Time_Schedule_Header {
	protected $id;
	protected $schedule_in;	
	protected $schedule_out;
	protected $break_time_schedules;
	protected $applied_to;
	protected $date_start;
	protected $date_created;
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = (int) $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
    public function setScheduleIn($value) {    	
    	$new_value = date("H:i:s",strtotime($value));
		$this->schedule_in = $new_value;
	}
	
	public function getScheduleIn() {
		return $this->schedule_in;
	}

	public function setScheduleOut($value) {
		$new_value = date("H:i:s",strtotime($value));
		$this->schedule_out = $new_value;
	}

	public function getScheduleOut() {
		return $this->schedule_out;
	}

	public function setBreakTimeSchedules($value) {
		$this->break_time_schedules = $value;
	}

	public function getBreakTimeSchedules() {
		return $this->break_time_schedules;
	}

	public function setAppliedTo($value) {
		$this->applied_to = $value;
	}

	public function getAppliedTo() {
		return $this->applied_to;
	}

	public function setDateStart($value) {
		$this->date_start = $value;
	}

	public function getDateStart() {
		return $this->date_start;
	}

	public function setDateCreated($value) {
		$this->date_created = $value;
	}

	public function getDateCreated() {
		return $this->date_created;
	}
}
?>