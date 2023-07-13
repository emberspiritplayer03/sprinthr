<?php
/*
 *  Usage:
        $schedule_in = '2014-01-20 20:00:00';
        $schedule_out = '2014-01-21 05:00:00';
        $overtime_in = '2014-01-21 05:00:00';
        $overtime_out = '2014-01-21 09:00:00';
        $actual_in = '2014-01-20 20:00:00';
        $actual_out = '2014-01-21 09:00:00';

        $ns = new Nightshift_Calculator;
        $ns->setScheduledTimeIn($schedule_in);
        $ns->setScheduledTimeOut($schedule_out);
        $ns->setOvertimeIn($overtime_in);
        $ns->setOvertimeOut($overtime_out);
        $ns->setActualTimeIn($actual_in);
        $ns->setActualTimeOut($actual_out);
        $ns_hours = $ns->compute();
*/
class Nightshift_Calculator {
	protected $ns_start_time = '22:00:00';
	protected $ns_end_time = '06:00:00';
	protected $actual_time_in;
	protected $actual_time_out;
	protected $scheduled_time_in;
	protected $scheduled_time_out;
	protected $ot_in;
	protected $ot_out;
	
	public function __construct() {
		//Get NS hours in sprint variables
		 $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_NIGHTSHIFT_HOUR);
		 $value = $sv->getVariableValue(); 

		 if( $value != "" ){
		 	 $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_NIGHTSHIFT_HOUR);
			 $value = $sv->getVariableValue(); 
			 if( $value != "" ){
			 	$a_time_in_out = explode("to", $value);		 	
			 	if( count($a_time_in_out) >= 2){		 		
			 		$time_in  = trim($a_time_in_out[0]);
			 		$time_out = trim($a_time_in_out[1]);
			 		$format   = "H:i:s";
			 		if( Tools::isValidDateTime($time_in,$format) && Tools::isValidDateTime($time_out,$format) ){
			 			$this->ns_start_time = $time_in;
			 			$this->ns_end_time   = $time_out;
			 		}
			 	}
			 }
		 }
	}
	
	public function setOvertimeIn($value) {
		$this->ot_in = $value;	
	}
	
	public function getOvertimeIn() {
		return $this->ot_in;	
	}
	
	public function setOvertimeOut($value) {
		$this->ot_out = $value;	
	}
	
	public function getOvertimeOut() {
		return $this->ot_out;	
	}	
	
	public function setNightShiftStartTime($value) {
		$this->ns_start_time = $value;	
	}
	
	public function getNightShiftStartTime() {
		return $this->ns_start_time;	
	}
	
	public function setNightShiftEndTime($value) {
		$this->ns_end_time = $value;	
	}
	
	public function getNightShiftEndTime() {
		return $this->ns_end_time;	
	}		
	
	public function setActualTimeIn($value) {
		$this->actual_time_in = $value;
	}
	
	public function setActualTimeOut($value) {
		$this->actual_time_out = $value;	
	}
	
	public function setScheduledTimeIn($value) {
		$this->scheduled_time_in = $value;	
	}
	
	public function setScheduledTimeOut($value) {
		$this->scheduled_time_out = $value;	
	}	
	
	private function getHours($time) {
		return date('H', strtotime($time));	
	}
	
	private function getMinutes($time) {
		return date('i', strtotime($time));	
	}
	
	private function getSeconds($time) {
		return date('s', strtotime($time));	
	}
	
	private function hasSchedule() {
		if (strtotime($this->scheduled_time_in) && strtotime($this->scheduled_time_out)) {
			return true;	
		} else {
			return false;	
		}
	}
	
	private function isBetweenNightshiftTime($time) {
		$str_time = strtotime($time);
		$ns_hours = Tools::getBetweenHours($this->ns_start_time, $this->ns_end_time);
		$is_between = in_array(date('H:00:00', $str_time), $ns_hours); 
		
		if ($is_between) {
			$actual_hour = date('H', $str_time);
			$actual_minutes = date('i', $str_time);
			$ns_end_hour = $this->getHours($this->ns_end_time);
			$ns_end_minutes = $this->getMinutes($this->ns_end_time);
			if ($actual_hour == $ns_end_hour && $actual_minutes > $ns_end_minutes) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;	
		}
	}
	
	private function isScheduleNightShift() {
		$ns_time_start = strtotime('17:00:00');
		$ns_time_end = strtotime('23:59:00');
		$time_in = strtotime(date('H:i:s', strtotime($this->scheduled_time_in)));		
		//echo $this->scheduled_time_in . "<br />";
		if (($time_in >= $ns_time_start && $time_in <= $ns_time_end) || (strtotime('00:00:00') <= $time_in && strtotime('06:00:00') >= $time_in )  ) {		
			return true;
		} else {
			return false;	
		}
	}
	
	private function hasOvertime() {
		if ($this->ot_in && $this->ot_out) {
			return true;	
		} else {
			return false;	
		}
	}
	
	private function isBetweenSchedule($time) {		
		if (Tools::isTimeBetweenHours($time, $this->scheduled_time_in, $this->scheduled_time_out)) {
			return true;	
		} else {
			return false;	
		}
	}
	
	public function isFirstTimeGreaterThanSecondTime($first_time, $second_time) {		
		if($second_time == '00:00:00'){
			$second_time = '24:00:00';			
		}
		
		$time1 = strtotime($first_time);
		$time2 = strtotime($second_time);		
		
		if ($time1 > $time2) {
			return true;	
		} else {
			return false;	
		}
	}

	public function compute() {
		if ($this->actual_time_in == $this->actual_time_out) {
			return 0;	
		}

		if(strtotime($this->actual_time_in) <= strtotime($this->scheduled_time_in))
		$this->actual_time_in = $this->scheduled_time_in;

		$hours_worked = Tools::computeHoursWorked($this->actual_time_in, $this->actual_time_out);
		
		if ($this->hasSchedule()) {
			if (!$this->isScheduleNightShift()) {
				return 0;
			}
		}
				
		if ($this->hasOvertime() && $this->hasSchedule() && $this->isBetweenSchedule($this->ot_in)) {
			$this->actual_time_out = $this->ot_in;
		} else if ($this->hasSchedule()) {
			
			if ($this->isFirstTimeGreaterThanSecondTime($this->actual_time_out, $this->scheduled_time_out)) {
				
				$this->actual_time_out = $this->scheduled_time_out;
			}
		}	

		$ns_hours = Tools::getBetweenHours($this->ns_start_time, $this->ns_end_time);
		$is_start_between = $this->isBetweenNightshiftTime($this->actual_time_in);
		$is_end_between = $this->isBetweenNightshiftTime($this->actual_time_out);
		
		if (!$is_start_between && !$is_end_between) {
			//$total_actual_hours = Tools::computeHoursDifference($this->actual_time_in, $this->actual_time_out);
			$total_actual_hours = Tools::computeHoursWorked($this->actual_time_in, $this->actual_time_out);
			if ($total_actual_hours > 30) {
				return 0;
			} else {
				$actual_hours = Tools::getBetweenHours($this->actual_time_in, $this->actual_time_out);
				$temp_first_time = '';
				$temp_second_time = '';
				foreach ($actual_hours as $actual_hour) {
					if (in_array($actual_hour, $ns_hours)) {
						if ($temp_first_time == '') {
							$temp_first_time = $actual_hour;
						} else {
							$temp_second_time =  $actual_hour;
						}
					}
				}

				$first_time = $temp_first_time;
				$second_time = $temp_second_time;
			}			
		} else if ($is_start_between && $is_end_between) {
			$first_time = $this->actual_time_in;	
			$second_time = $this->actual_time_out;
		} else if ($is_start_between && !$is_end_between) {
			$first_time = $this->actual_time_in;
			$second_time = $this->ns_end_time;
		} else if (!$is_start_between && $is_end_between) {
			$first_time = $this->ns_start_time;
			$second_time = $this->actual_time_out;
		} else {
			return 0;
		}
		
//		if (!$this->hasSchedule()) {
//			$is_break_covered = $this->isBreakTimeCovered($first_time, $second_time);
//			if ($is_break_covered) {
//				$break_hours=  $this->getBreakTimeHours();
//				$second_time = $this->subtractHours($second_time, $break_hours);
//			}
//		}

//		echo $first_time;
//		echo '<br>';
//		echo $second_time;
		
		if (strtotime($first_time) && strtotime($second_time)) {
			$nd_hours = Tools::getHoursDifference($first_time, $second_time);
		} else {
			$nd_hours = 0;	
		}
		
		if($this->scheduled_time_out == '24:00:00' || $this->scheduled_time_out == '00:00:00'){			
			$nd_hours = $this->subtractIfMoreThan2Hours($nd_hours);			
			return $nd_hours;
		}else{
			$nd_hours = $this->subtractIfMoreThan8Hours($nd_hours);
			if ($hours_worked < $nd_hours) {
				return 0;
			} else {
				return $nd_hours;	
			}
		}
	}
	
	private function subtractIfMoreThan2Hours($hours) {
		$limit_hours = 2;			
		if ($hours > $limit_hours) {			
			//$answer = $hours - ($hours - $limit_hours);
			$answer = $hours - ($hours - $limit_hours);
		} else {			
			$answer = $hours;	
		}
		return $answer;
	}
	
	private function subtractIfMoreThan7Hours($hours) {
		$limit_hours = 7;
		if ($hours > $limit_hours) {
			$answer = $hours - ($hours - $limit_hours);
		} else {
			$answer = $hours;	
		}
		return $answer;
	}
	
	private function subtractIfMoreThan8Hours($hours) {
		$limit_hours = 8;		
		if ($hours > $limit_hours) {
			$answer = $hours - ($hours - $limit_hours);
		} else {
			$answer = $hours;	
		}
		return $answer;
	}
	
	private function subtractHours($time, $subtract_hours) {
		$minus_minutes = (int) round($subtract_hours * 60);
		return date('H:i:s', strtotime($time." -$minus_minutes minutes"));	
	}
	
	private function getBreakIn() {
		$is_ns = Tools::isTimeNightShift($this->actual_time_in);
		if ($is_ns) {
			return '00:00:00';	
		} else {
			return '12:00:00';	
		}
	}
	
	private function getBreakOut() {
		$is_ns = Tools::isTimeNightShift($this->actual_time_in);
		if ($is_ns) {
			return '01:00:00';	
		} else {
			return '13:00:00';	
		}
	}
	
	private function getBreakTimeHours() {
		return Tools::computeHoursDifference($this->getBreakIn(), $this->getBreakOut());
	}
	
	private function isBreakTimeCovered($time_in, $time_out) {
		$break_in = $this->getBreakIn();
		$break_out = $this->getBreakOut();
		
		if (Tools::isTimeBetweenHours($break_in, $time_in, $time_out) && Tools::isTimeBetweenHours($break_out, $time_in, $time_out)) {
			return true;	
		} else {
			return false;	
		}
	}				
	
}
?>