<?php
class Schedule {	
	protected $id;
	protected $schedule_name;
	protected $working_days; // mon,tue,wed,thu,fri,sat,sun
	protected $time_in; // 09:00:00
	protected $time_out; // 18:00:00
	
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
	
	public function setWorkingDays($value) {
		$this->working_days = $value;	
	}	
	
	public function getWorkingDays() {
		return $this->working_days;	
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
}
?>