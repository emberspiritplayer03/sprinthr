<?php
/*
	$u = new Undertime_Calculator;
	$u->setScheduledTimeIn($scheduled_time_in);
	$u->setScheduledTimeOut($scheduled_time_out);
	$u->setActualTimeIn($actual_time_in);
	$u->setActualTimeOut($actual_time_out);
	$u->setBreakTimeIn($break_time_in);
	$u->setBreakTimeOut($break_time_out);			
	$undertime_hours = $u->computeUndertimeHours();
*/
class Undertime_Calculator {
	public $actual_date_in;
	public $actual_date_out;
	
	protected $scheduled_time_in;
	protected $scheduled_time_out;
	protected $actual_time_in;
	protected $actual_time_out;
	protected $break_time_in;
	protected $break_time_out;	
	
	
	public function __construct() {

	}
	
	public function setActualDateIn($value) {
		$this->actual_date_in = $value;	
	}
	
	public function setActualDateOut($value) {
		$this->actual_date_out = $value;	
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
	
	public function computeUndertimeHours() {
		$undertime_hours = 0;
		$undertime_hours = Tools::getHoursDifference($this->actual_time_out, $this->scheduled_time_out);

		if ($undertime_hours >= 8.75) {
			$undertime_hours = 0;	
		}

		$break_hours = $this->computeBreakHours();
		$hours = $undertime_hours - $break_hours;
		if ($hours >= 0) {
			return $hours;	
		} else {
			return 0;	
		}
	}	
	
	public function computeUndertimeHoursOld() {
		$undertime_hours = 0;
		#$undertime_hours = Tools::getHoursDifference($this->actual_time_out, $this->scheduled_time_out);
		$diff = Tools::getDayHourDifference($this->actual_date_in,$this->actual_date_in,$this->scheduled_time_in, $this->actual_time_out);
		$undertime_hours = $diff['total_hour'];
		//if ($undertime_hours >= 8) {
		//	$undertime_hours = 0;	
		//}
		$break_hours = $this->computeBreakHours();
		$hours = $undertime_hours - $break_hours;
		echo $hours;
		if ($hours >= 0) {
			return $hours;	
		} else {
			return 0;	
		}
	}
	
	function computeUndertime() {
		$total_schedule_hours = Tools::getHoursDifference($this->scheduled_time_in, $this->scheduled_time_out);
		$diff = Tools::getDayHourDifference($this->actual_date_in,$this->actual_date_out,$this->scheduled_time_in, $this->actual_time_out);
		$total_hours_worked = $diff['total_hour'];
		
		$undertime_hours = $total_schedule_hours - $total_hours_worked;
		$break_hours = $this->computeBreakHours();
		$hours = $undertime_hours - $break_hours;

		return $hours;
		#echo $undertime_hours;
	}
	
	private function computeBreakHours() {
		if (!$this->hasBreakHours()) {
			return 0;
		}
		
		if ($this->isScheduleNightShift()) {
			$this->convertAllTimeToDayShift();	
		}
		
		$break_in = strtotime($this->break_time_in);
		$break_out = strtotime($this->break_time_out);
		$time_out = strtotime($this->actual_time_out);
		/*echo "Break In:" . $this->break_time_in . "<br/>";
		echo "Break Out:" . $this->break_time_out . "<br/>";
		echo "Time Out:" . $this->actual_time_out . "<br/>";*/
			if ($time_out >= $break_in && $time_out <= $break_out) {
				#echo $this->break_time_in . '<br/>';
				#echo $this->break_time_out . '<br/>';
				#echo $this->actual_time_out . '<br/>';
				$diff =  Tools::getHoursDifference($this->actual_time_out, $this->break_time_out);
				echo $diff;
				if($diff == 0) {
					$diff = 1;	
				}
				return $diff;			
			}  else if ($time_out < $break_in && Tools::getHoursDifference($this->actual_time_out, $this->break_time_in) <= 8) {
				return Tools::getHoursDifference($this->break_time_in, $this->break_time_out);
			} else {
				return 0;	
			}
			
	}
	
	private function convertAllTimeToDayShift() {
		$this->break_time_in = date('H:i:s', strtotime($this->break_time_in . ' -12 hours'));
		$this->break_time_out = date('H:i:s', strtotime($this->break_time_out . ' -12 hours'));
		$this->scheduled_time_in = date('H:i:s', strtotime($this->scheduled_time_in . ' -12 hours'));
		$this->scheduled_time_out = date('H:i:s', strtotime($this->scheduled_time_out . ' -12 hours'));		
		$this->actual_time_in = date('H:i:s', strtotime($this->actual_time_in . ' -12 hours'));
		$this->actual_time_out = date('H:i:s', strtotime($this->actual_time_out . ' -12 hours'));
	}
	
	private function isScheduleNightShift() {
		$ns_time_start = strtotime('17:00:00');
		$ns_time_end = strtotime('23:59:00');
		$time_in = strtotime($this->scheduled_time_in);
		
		if ($time_in >= $ns_time_start && $time_in <= $ns_time_end) {
			return true;
		} else {
			return false;	
		}
	}
	
	private function hasBreakHours() {
		if ($this->break_time_in && $this->break_time_out) {
			return true;	
		} else {
			return false;	
		}
	}		
}
?>
