<?php
/*
	Usage:
		$scheduled_time_in = '08:00:00';
		$scheduled_time_out = '17:00:00';		
		$overtime_in = '04:00:00';
		$overtime_out = '07:13:00';
			
		$o = new G_Overtime_Calculator;
		$o->setScheduleIn($scheduled_time_in);
		$o->setScheduleOut($scheduled_time_out);		
		$o->setOvertimeIn($overtime_in);
		$o->setOvertimeOut($overtime_out);
		
		$ot_hours = $o->computeHours();	
		$ot_excess_hours = $o->computeExcessHours();
		$ot_nd = $o->computeNightDiff();
		$ot_excess_nd = $o->computeExcessNightDiff();	
*/
class G_Overtime_Calculator {
	protected $limit_hours = 8;
	protected $attendance_date;
	protected $ot_date_in;
	protected $ot_date_out;
	protected $ot_in;
	protected $ot_out;
	protected $schedule_in;
	protected $schedule_out;
	protected $is_subtract_break = false;
	
	public function __construct() {
			
	}
	
	public function setDate($value) {
		$this->attendance_date = $value; 
	}
	
	public function setSubtractBreak() {
		$this->is_subtract_break = true;	
	}
	
	public function isSubtractBreak() {
		return $this->is_subtract_break;	
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
	
	public function setLimitHours($value) {
		$this->limit_hours = $value;	
	}
	
	public function getLimitHours() {
		return $this->limit_hours;	
	}
	
	public function setOvertimeDateIn($value) {
		$this->ot_date_in = $value;	
	}
	
	public function getOvertimeDateIn() {
		return $this->ot_date_in;	
	}
	
	public function setOvertimeDateOut($value) {
		$this->ot_date_out= $value;	
	}
	
	public function getOvertimeDateOut() {
		return $this->ot_date_out;	
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
	
	public function isWithInBreakTime($time_in,$time_out){		
		$break_in 	= strtotime($this->getBreakInSub());
		$break_out 	= strtotime($this->getBreakOut());
		$otime_in	= strtotime($time_in);
		$otime_out	= strtotime($time_out);	
		//echo "otime in:{$time_in} <br/> otime out:{$time_out} <br /> break in:{$this->getBreakIn()} <br/> break out:{$this->getBreakOut()} <br/> otime_in:{$otime_in} <br> otime_out:{$otime_out} <br> break_in:{$break_in} <br> break_out:{$break_out}<br>";		
		if((($otime_in < $break_out) && ($otime_out > $break_in))){
			return true;				
		}else{	
			if(!empty($this->ot_date_out)){				
				//echo 1;
				if(strtotime($this->ot_date_out) > strtotime($this->ot_date_in)){
					//echo 9;
					$new_break_in  = $this->ot_date_in . " " . $this->getBreakInSub();
					$new_break_out = $this->ot_date_out . " " . $this->getBreakOut();
					
					$new_obreak_in  = strtotime($new_break_in);
					$new_obreak_out = strtotime($new_break_out);
					
					$new_time_in  = $this->ot_date_in . " " . $time_in;
					$new_time_out = $this->ot_date_out . " " . $time_out;
					
					$new_otime_in  = strtotime($new_time_in);
					$new_otime_out = strtotime($new_time_out);
					
					//echo "new otime in:{$new_time_in} <br/> new otime out:{$new_time_out} <br /> new break in:{new_break_in} <br/> new break out:{$new_break_out} <br/> new otime_in:{$new_otime_in} <br> new otime_out:{$new_otime_out} <br> new break_in:{$new_obreak_in} <br> new break_out:{$new_obreak_out}<br>";		
					if((($new_otime_in < $new_obreak_out) && ($new_otime_out > $new_obreak_in))){		
						return true;
					}else{
						return false;
					}
					
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		
		
	}

	public function subtractHoliday($total_hrs) {
		if($total_hrs > 1){
			$month = date("m",strtotime($this->attendance_date));
			$day   = date("d",strtotime($this->attendance_date));
			
			$h = G_Holiday_Finder::findByMonthAndDay($month,$day);
			if($h){
				//print_r($h);
				return $total_hrs - 1;
			}
			return $total_hrs;
		}else{return $total_hrs;}
	}
	
	public function computeHours() {		
		$time 	 = $this->getLimitTimeInOut();
		$time_in  = $time['in'];
		$time_out = $time['out'];
		//echo " OT OUT SUB2:{$time_out} <br/>";
		$ot_hours = self::computeHoursDifference($time_in, $time_out);
		//echo "Total OT HRS:{$ot_hours}";

		//echo "Total OT : " . $ot_hours . "<br/>";
		$date 	  = Tools::isDateSaturday($this->attendance_date);
		//echo $date;
		if($date  = Tools::isDateSaturday($this->attendance_date) || $date = Tools::isDateSunday($this->attendance_date)){
			$is_ot_covered = $this->isWithInBreakTime($time_in, $time_out);
			if ($is_ot_covered) {

			//echo $ot_hours;
				$ot_hours = $this->subtractBreakTimeHoursNotByHour($ot_hours,$time_in, $time_out);
			}else{
			
			}
		}else{
			if ($this->is_subtract_break) {
				if($date  = Tools::isDateSaturday($this->attendance_date) || $date = Tools::isDateSunday($this->attendance_date)){
					//$is_ot_covered = $this->isBreakTimeCoveredByOvertime();
					$is_ot_covered = $this->isBreakTimeCoveredByOvertime();
					if ($is_ot_covered && !$this->isWithInBreakTime($time_in, $time_out)) {
						$ot_hours = $this->subtractBreakTimeHoursNotByHour($ot_hours,$time_in, $time_out);
					}
				}
			}else{
				//if($this->schedule_out == $this->ot_in){
					//$ot_hours = $this->subtractBreakTimeHoursNotByHour($ot_hours,$time_in, $time_out);	
				//}

			}
		}
		//echo "Total OT: {$ot_hours}<br/>";
		$ot_hours = $this->deductForLimitHours($ot_hours);
		//$ot_hours = $this->subtractHoliday($ot_hours);
		//echo "New Total OT: {$ot_hours} <br/>";
		return $ot_hours;
	}
	
	function subtractBreakTimeHoursNotByHour($ot_hours,$time_in,$time_out) {
		$break_in 	= strtotime($this->getBreakIn());
		$break_out 	= strtotime($this->getBreakOut());
		$otime_in	= strtotime($time_in);
		$otime_out	= strtotime($time_out);		
		//echo "break in : {$break_in} break out : {$break_out} time_in : {$otime_in} time_out : {$otime_out}";
		
		if($otime_out > $break_in && $otime_out < $break_out) {	
		//echo "Total OT:{$ot_hours}";
		//echo "otime in:{$time_in} <br/> otime out:{$time_out} <br /> break in:{$this->getBreakIn()} <br/> break out:{$this->getBreakOut()} <br/> otime_in:{$otime_in} <br> otime_out:{$otime_out} <br> break_in:{$break_in} <br> break_out:{$break_out}<br>";		
			if($otime_out <= $break_out){
				$diff 	  = self::computeHoursDifference($time_out, $this->getBreakOut());
				$ot_hours = $ot_hours + $diff;
				$ot_hours = $this->subtractBreakTimeHours($ot_hours);				
			}else{
				$ot_hours = $this->subtractBreakTimeHours($ot_hours);
			}
			//$diff = self::computeHoursDifference($this->getBreakIn(), $time_out);			
			//echo "OT DIFF:{$diff} <br/>";
			//$ot_hours -= $diff;
			//echo "OT_HRS:{$ot_hours}";
		} else {			
			$ot_hours = $this->subtractBreakTimeHours($ot_hours);
		}
		
		
		return $ot_hours;
	}
	
	function isNightOT($time_in,$time_out) {
		$break_in 	= strtotime("12:00:00");
		$break_out 	= strtotime("01:00:00");
		$otime_in	= strtotime($time_in);
		$otime_out	= strtotime($time_out);
		
		if($otime_in >= $break_out && $otime_out <= $break_in) {
			
		} else {
			
		}
		
	}
	
	private function subtractBreakTimeHours($hours) {		
		return $hours - $this->getBreakTimeHours();	
	}
	
	private function isOvertimeMoreThan8Hours() {
		$hours = self::computeHoursDifference($this->ot_in, $this->ot_out);
		if ($hours > 8) {
			return true;	
		} else {
			return false;	
		}
	}
	
	private function isBreakTimeCoveredByOvertime() {
		 $is_covered = $this->isBreakTimeCovered($this->ot_in, $this->ot_out);
		 return $is_covered;
	}
	
	private function isBreakTimeCovered($time_in, $time_out) {	
		
		$break_in 	= strtotime($this->getBreakIn());
		$break_out 	= strtotime($this->getBreakOut());
		$otime_in	= strtotime($time_in);
		$otime_out	= strtotime($time_out);
		
		//echo "break_in:{$break_in} - break_out:{$break_out} - otime_in:{$otime_in} - otime_out:{$otime_out}" . "<br>";
		
		if((($otime_in >= $break_in) && ($otime_in < $break_out)) || ($otime_out <= $break_in)) {				
			return false;
		} else {
			return true;
		}
	
		/*if (Tools::isTimeBetweenHours($break_in, $time_in, $time_out) && Tools::isTimeBetweenHours($break_out, $time_in, $time_out)) {
			return true;
		} else {
			return false;	
		}*/
		
	}
	
	private function isBreakTimeCoveredBySchedule() {
		
		if ($this->hasSchedule()) {
			return $this->isBreakTimeCovered($this->schedule_in, $this->schedule_out);
		} else {
			return true;	
		}
	}
	
	private function deductForLimitHours($hours) {		
		$limit_hours = $this->limit_hours;
		if (!$limit_hours) {
			$limit_hours = 0;	
		}
		if ($hours > $limit_hours) {
			$answer = $hours - ($hours - $limit_hours);
		} else {
			$answer = $hours;	
		}
		return $answer;
	}
	
	private function getLimitTimeInOut() {
		$limit_hours = $this->limit_hours;		
		if ($this->hasSchedule()) {			
			$is_sched_covered = $this->isBreakTimeCovered($this->schedule_in, $this->schedule_out);
		} else {			
			$is_sched_covered = true;
		}

		$is_ot_covered = $this->isBreakTimeCovered($this->ot_in, $this->ot_out);			
		if ($is_sched_covered && $is_ot_covered) {
			$limit_hours = $limit_hours + $this->getBreakTimeHours();
		}
		//echo "S_OT_IN:{$this->ot_in} S_OT_OUT:{$this->ot_out}<br/>";	
		$hours = Tools::getHoursDifference($this->ot_in, $this->ot_out);
				
		//echo "Total Hours: {$hours}"; 
		if($date  = Tools::isDateSaturday($this->attendance_date) || $date = Tools::isDateSunday($this->attendance_date)){
			//echo "Limit HRS:" . $limit_hours;
		}else{

			$limit_hours = $limit_hours - 1;
		}
		$temp_hours = $hours - $limit_hours;		
		
		if($hours >= 8 && $hours< 9){
			$temp_hours = $hours - 8;
		}
		
		if ($temp_hours > 0) {
			$minus_minutes = (int) round($temp_hours * 60);	
		} else {
			$minus_minutes = 0;	
		}
		//echo "OOT OUT:{$minus_minutes}<br/>";
		$time['in'] = $this->ot_in;
		$time['out'] = date('H:i:s', strtotime($this->ot_out ." -$minus_minutes minutes"));
		return $time;
	}	
	
	private function hasSchedule() {
		if ($this->schedule_in && $this->schedule_out) {
			return true;	
		} else {
			return false;	
		}
	}
	
	public function computeExcessHours() {
		$time = $this->getLimitTimeInOut();
#		echo $this->ot_out;
		#Tools::showArray($time);	
		$time_in = $time['out'];
		//echo $time['out'];
		$time_out = $this->getExcessOut();	
		//echo "Excess : {$time_out}<br/>";			
		return self::computeHoursDifference($time_in, $time_out);
	}
	
	public function computeNightDiff() {
		$time = $this->getLimitTimeInOut();
		$time_in = $time['in'];
		$time_out = $time['out'];

		$ns = new Nightshift_Calculator_OT;
		$ns->setActualTimeIn($time_in);
		$ns->setActualTimeOut($time_out);
		$ns_hours = $ns->compute();

		$ns_hours = $this->deductForLimitHours($ns_hours);
		return $ns_hours;			
	}
	
	public function computeExcessNightDiff() {
		$time = $this->getLimitTimeInOut();
		$time_in = $time['out'];//$this->getExcessIn();
		$time_out = $this->getExcessOut();
				
		$ns = new Nightshift_Calculator_OT;
		$ns->setActualTimeIn($time_in);
		$ns->setActualTimeOut($time_out);
		return $ns->compute();
	}
	
	function computeHoursDifference($start,$end){
		$t['start'] = $start;
		$t['end']   = $end;
				
		list($h1, $m1) = sscanf($t['start'], "%d:%d");
		list($h2, $m2) = sscanf($t['end'], "%d:%d");
		
		$hrs = ($h2-$h1) + ($m2-$m1)/60;
		if ($hrs < 0) {
		  $hrs = $hrs + 24;
		}
		
		$total_ot = number_format($hrs,4, '.', '');		
		//echo $t['start'] . " Total OT:{$total_ot} <br/>";		
		return $total_ot;
	}
	
	public static function computeHoursDifferenceOld($start, $end) {			
		if($start == ''){
			$time['start'] = -1;
		}else{
			$time['start'] = strtotime($start);
		}
		
		if($end == ''){
			$time['end'] = -1;
		}else{
			$time['end'] = strtotime($end);
		}
		
		
		
		if ($time['start'] !== -1 && $time['end'] !== -1) {
			if ($time['end'] >= $time['start']) {
				$difference = $time['end'] - $time['start'];
				if ($days = intval((floor($difference/86400)))) {
					$difference = $difference % 86400;
				}
				if ($hours = intval((floor($difference/3600)))) {
					$difference = $difference % 3600;
				}
				if ($minutes = intval((floor($difference/60)))) {
					$difference = $difference % 60;
				}
				$difference = intval($difference);
								     
				return number_format((($hours * 60) + $minutes + $difference / 60) / 60, 4, '.', '');
			}
			else {				
				$difference = $time['start'] - $time['end'];
				//echo date("g:i a", $time['end']);				
				if ($days = intval((floor($difference/86400)))) {					
					$difference = $difference % 86400;
				}
				if ($hours = intval((floor($difference/3600)))) {					
					$difference = $difference % 3600;					
				}
				if ($minutes = intval((floor($difference/60)))) {					
					$difference = $difference % 60;
				}
				
				$difference = intval($difference);
				$hours 	    = 24 - $hours;		
				$total_diff = number_format((($hours * 60) + $minutes + $difference / 60) / 60, 4, '.', '');						
				return $total_diff;
			}
		} else {
			return 0;
		}
		return 0;
	}		
	
	private function getLimitIn() {
		return $this->ot_in;	
	}
	
	private function getLimitOut() {
		$limit_hours = $this->limit_hours;
		$hours = self::computeHoursDifference($this->ot_in, $this->ot_out);
		$temp_hours = $hours - $limit_hours;
				
		if ($temp_hours > 0) {
			$minus_minutes = ($temp_hours * 60);	
		} else {
			$minus_minutes = 0;	
		}
		return date('H:i:s', strtotime($this->ot_out ." -{$minus_minutes} minutes"));
	}
	
	private function getBreakTimeHours() {
		return self::computeHoursDifference($this->getBreakIn(), $this->getBreakOut());
	}
	
	private function getBreakIn() {
		$is_ns = Tools::isTimeNightShift($this->ot_in);
		if ($is_ns) {
			return '00:00:00';	
		} else {
			return '12:00:00';	
		}
	}
	
	private function getBreakInSub() {
		$is_ns = Tools::isTimeNightShift($this->ot_in);
		if ($is_ns) {
			return '23:59:59';	
		} else {
			return '12:00:00';	
		}
	}
	
	private function getBreakOut() {
		$is_ns = Tools::isTimeNightShift($this->ot_in);
		if ($is_ns) {
			return '01:00:00';	
		} else {
			return '13:00:00';	
		}
	}	
	
	public function getExcessIn() {
		$start_time = $this->ot_in;
		$end_time = $this->ot_out;
		$limit_hours = $this->limit_hours;
		$hours = self::computeHoursDifference($start_time, $end_time);
		$temp_hours = $hours - $limit_hours;
		if ($temp_hours > 0) {
			$minus_minutes = ($temp_hours * 60);	
		} else {
			$minus_minutes = 0;	
		}
		return date('H:i:s', strtotime($end_time ." -{$minus_minutes} minutes"));
		//$return['end'] = $end_time;//date('H:i:s', strtotime($end_time ." -{$minus_minutes} minutes"));
		//return $return;
	}
	
	public function getExcessOut() {
		return $this->ot_out;	
	}				
}
?>

<?php
/*
	$ns = new Nightshift_Calculator;
	$ns->setNightShiftStartTime('22:00:00');
	$ns->setNightShiftEndTime('06:00:00');
	$ns->setActualTimeIn('23:00:00');
	$ns->setActualTimeOut('08:00:00');
	echo $ns_hours = $ns->compute();
*/
class Nightshift_Calculator_OT {
	protected $ns_start_time = '22:00:00';
	protected $ns_end_time = '06:00:00';
	protected $actual_time_in;
	protected $actual_time_out;
	protected $scheduled_time_in;
	protected $scheduled_time_out;
	protected $ot_in;
	protected $ot_out;
	
	public function __construct() {

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
		$time_in = strtotime($this->scheduled_time_in);
		
		if ($time_in >= $ns_time_start && $time_in <= $ns_time_end) {
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
			if ($total_actual_hours > 15) {
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

		if (strtotime($first_time) && strtotime($second_time)) {
			//echo $first_time;
			//echo $second_time;
			$ns_dates = self::getDateInAndOut($first_time, $second_time, '2012-01-01');
			$ns_date_in = $ns_dates['date_in'];
			$ns_date_out = $ns_dates['date_out'];
			//echo "{$ns_date_in} {$first_time}";
			//echo '<br>';
			//echo "{$ns_date_out} {$second_time}";
			$nd_hours = self::computeHoursDifferenceByDateTime("{$ns_date_in} {$first_time}", "{$ns_date_out} {$second_time}");
			//$nd_hours = G_Overtime_Calculator::computeHoursDifference($first_time, $second_time);
		} else {
			$nd_hours = 0;	
		}
		
		return $nd_hours;
	}
	
	private static function getDateInAndOut($time_in, $time_out, $date) {
		if (!Tools::isTimeNightShift($time_in)) {
			$hours_worked = Tools::computeHoursDifference($time_in, $time_out);
		} else {
			$hours_worked = Tools::getHoursDifference($time_in, $time_out);
		}
					
		if (Tools::getAfternoon($time_in) && Tools::getMorning($time_out)) {
			$date_in = $date;
			if ($hours_worked <= 1) {
				$date_out = $date;
			} else {
				$date_out = date('Y-m-d', strtotime($date . '+1 day'));
			}
		} else if (Tools::getMorning($time_in) && Tools::getMorning($time_out) && $hours_worked > 15) {
			$date_in = $date;
			$date_out = date('Y-m-d', strtotime($date . '+1 day'));
		} else {
			$date_in = $date;
			$date_out = $date;
		}
		$data['date_in'] = $date_in;
		$data['date_out'] = $date_out;
		return $data;
	}	
	
	private static function computeHoursDifferenceByDateTime($date_time_in, $date_time_out) {
		$time = self::getTimeDifference("$date_time_in", "$date_time_out");
		return number_format(($time['days'] * 24) + (($time['hours'] * 60) + $time['minutes'] + $time['seconds'] / 60) / 60, 4, '.', '');
	}
	
	private static function getTimeDifference($start, $end) {
		$time['start'] = strtotime($start);
		$time['end'] = strtotime($end);
		if ($time['start'] !== -1 && $time['end'] !== -1) {
			if ($time['end'] >= $time['start']) {
				$difference = $time['end'] - $time['start'];
				if ($days = intval((floor($difference/86400))))
					$difference = $difference % 86400;
				if ($hours = intval((floor($difference/3600))) )
					$difference = $difference % 3600;
				if ($minutes = intval((floor($difference/60))) )
					$difference = $difference % 60;
				$difference = intval($difference);
				
/*				if ($hours > 0) { // this is to fix to bug: 01:00:00 - 2:00:00 = 1 hour (NOT 2 hours)
					$the_hour = date('H', strtotime($start));
					if ($the_hour == '00' || $the_hour == '01') {
						$hours = $hours - 1;	
					}
				}*/
				return(array('days'=> $days, 'hours' => $hours, 'minutes' => $minutes, 'seconds' => $difference));
			}
			else {
				return 0;
			}
		} else {
			return 0;
		}
		return 0;
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
		$break_in  = $this->getBreakIn();
		$break_out = $this->getBreakOut();
		
		if (Tools::isTimeBetweenHours($break_in, $time_in, $time_out) && Tools::isTimeBetweenHours($break_out, $time_in, $time_out)) {			
			return true;	
		} else {
			return false;	
		}
	}				
	
}
?>