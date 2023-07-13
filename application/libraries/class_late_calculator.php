<?php
class Late_Calculator {
	protected $grace_period; // Minutes
	protected $scheduled_time_in;
	protected $scheduled_time_out;
	protected $actual_time_in;
	protected $actual_time_out;
	protected $break_time_in;
	protected $break_time_out;
	
	public function __construct() {
			
	}
	
	public function setGracePeriod($value) {
		$this->grace_period = $value;	
	}
	
	public function setScheduledTimeIn($value) {
		$this->scheduled_time_in = $value;	
	}
	
	public function setScheduledTimeOut($value) {
		$this->scheduled_time_out = $value;	
	}
	
	public function setActualTimeIn($value) {
		$this->actual_time_in = $value;
	}
	
	public function setActualTimeOut($value) {
		$this->actual_time_out = $value;	
	}
	
	public function setBreakTimeIn($value) {
		$this->break_time_in = $value;
	}
	
	public function setBreakTimeOut($value) {
		$this->break_time_out = $value;	
	}	
	
	public function computeLateHours() {		
		$grace_period = Tools::numberFormat($this->grace_period / 60);
		$late_hours   = Tools::numberFormat(Tools::getHoursDifference($this->scheduled_time_in, $this->actual_time_in));		
		//echo "Org Grace Period:{$this->grace_period} <br> ";
		//echo "Grace Period:{$grace_period} <br> ";
		//echo "Late Hours:{$late_hours} ";
		if ($late_hours > $grace_period) {
			$actual_late_hours = $late_hours;
			if ($actual_late_hours >= 8) {
				return 0;	
			} else {
				$break_hours = $this->computeBreakHours();
				$hours = $actual_late_hours - $break_hours;	
				if ($hours >= 0) {
					return $hours;	
				} else {
					return 0;	
				}
			}
		} else {
			return 0;	
		}
	}
	
	private function hasBreakHours() {
		if ($this->break_time_in && $this->break_time_out) {
			return true;	
		} else {
			return false;	
		}
	}
	
	private function computeBreakHours() {
		if (!$this->hasBreakHours()) {
			return 0;
		}
		
		$break_in = strtotime($this->break_time_in);
		$break_out = strtotime($this->break_time_out);
		$time_in = strtotime($this->actual_time_in);
				
		if ($time_in >= $break_in && $time_in <= $break_out) {
			return Tools::getHoursDifference($this->break_time_in, $this->actual_time_in);
		} else if ($time_in > $break_out && Tools::getHoursDifference($this->break_time_out, $this->actual_time_in) <= 8) {
			return Tools::getHoursDifference($this->break_time_in, $this->break_time_out);
		} else {
			return 0;	
		}		
	}
}
?>