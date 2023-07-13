<?php
class Break_Time_Schedule {
	protected $id;
	protected $schedule_in;	
	protected $schedule_out;
	protected $break_in;
	protected $break_out;
	protected $total_hrs_break;
	protected $to_deduct;	
	protected $to_required_logs;	
	protected $total_hrs_to_deduct;
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = (int) $value;
	}
	
	public function getId() {
		return $this->id;
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

	public function setBreakIn($value) {
		$this->break_in = $value;
	}

	public function getBreakIn() {
		return $this->break_in;
	}

	public function setBreakOut($value) {
		$this->break_out = $value;
	}

	public function getBreakOut() {
		return $this->break_out;
	}

	public function setTotalHrsBreak($value) {
		$this->total_hrs_break = $value;
	}

	public function getTotalHrsBreak() {
		return $this->total_hrs_break;
	}

	public function setToDeduct($value) {
		$this->to_deduct = isset($value) && !empty($value) ? $value : 0;
	}

	public function getToDeduct() {
		return $this->to_deduct;
	}

	public function setToRequiredLogs($to_required_logs)
	{
		$this->to_required_logs = isset($to_required_logs) && !empty($to_required_logs) ? $to_required_logs : 0;
	}

	public function getToRequiredLogs()
	{
		return $this->to_required_logs;
	}

	public function setTotalHrsToDeduct($value) {
		$this->total_hrs_to_deduct = $value;
	}

	public function getTotalHrsToDeduct() {
		return $this->total_hrs_to_deduct;
	}
}
?>