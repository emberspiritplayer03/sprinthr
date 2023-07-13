<?php
class Schedule_Template {	
	protected $id;
	protected $schedule_type;
	protected $schedule_name;
	protected $required_working_hours;
	protected $schedule_in;
	protected $schedule_out;
	protected $break_out;
	protected $break_in;

	function __construct() {

	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}	
	
	public function setName($value) {
		$this->schedule_name = $value;	
	}
	
	public function getName() {
		return $this->schedule_name;	
	}
	
	public function setRequiredWorkingHours($value) {
		$this->required_working_hours = $value;	
	}	
	
	public function getRequiredWorkingHours() {
		return $this->required_working_hours;	
	}
	
	public function setScheduleIn($value) {
		$this->schedule_in = $value;	
	}
	
	public function getScheduleIn() {
		return $this->schedule_in;	
	}
	
	public function setScheduleOut($value) {
		$this->schedule_out = $value;	
	}

	public function getScheduleOut() {
		return $this->schedule_out;	
	}

	public function getBreakIn() {
		return $this->break_in;	
	}
	
	public function setBreakIn($value) {
		$this->break_in = $value;	
	}

	public function getBreakOut() {
		return $this->break_out;	
	}
	
	public function setBreakOut($value) {
		$this->break_out = $value;	
	}

	public function setScheduleType($value) {
		$this->schedule_type = $value;	
	}
	
	public function getScheduleType() {
		return $this->schedule_type;	
	}
}
?>