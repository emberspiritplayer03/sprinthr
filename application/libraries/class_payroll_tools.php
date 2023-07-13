<?php
class Payroll_Tools extends Tools {
	
	public static function getTimeInAndOut($time_punches) {
		
		foreach ($time_punches as $data) {						
			if ($time_in == '') {
				$time_in = $data['time_in'];				
			}
			$time_out = $data['time_out'];
			if ($previous_time_out != '') {
				$total_hours = Tools::getHoursDifference($previous_time_out, $data['time_in']);
				if ($total_hours >= 8) {
					$time_out = $previous_time_out;
					break;
				} else {
					$time_out = $data['time_out'];	
				}
			}			
			$previous_time_out = $data['time_out'];
		}		
		$records['time_in'] = $time_in;
		$records['time_out'] = $time_out;
		return $records;
	}
	
	/*
		Gets the covered cycle date of $date
	*/
	public static function getCoveredCycleDate($date, $cycle) {
		$day = date('j', strtotime($date));
		$mk_date = strtotime($date);
		foreach ($cycle as $c) {
			if ($c != 0) {
				list($start, $end) = explode('-', $c);
				
				if ($start > $end) { // add 1 month to $end if $start is greater than $end
					//$cycle_date['start'] = Tools::getGmtDate("Y-m-{$start}");
					$cycle_date['start'] = Tools::getGmtDate("Y-m-{$start}", strtotime("$date -1 month"));
				} else {
					//$cycle_date['end'] = Tools::getGmtDate("Y-m-{$end}");
					$cycle_date['start'] = Tools::getGmtDate("Y-m-{$start}");
				}
				
				$cycle_date['end'] = Tools::getGmtDate("Y-m-{$end}");
				
				if ($mk_date >= strtotime($cycle_date['start']) && $mk_date <= strtotime($cycle_date['end'])) {
					return $cycle_date;
				}
								
//				if (Payroll_Tools::isNumberWithin($day, $c)) {

//					return $cycle_date;
//				}
			}
		}
		return false;
	}
	
	public static function isFirstTimeWithinSecondTime($first_time, $second_time) {
		$second_time_in = strtotime($second_time['time_in']);
		$second_time_out = strtotime($second_time['time_out']);		
		$first_time_in = strtotime($first_time['time_in']);
		while ($second_time_in != $second_time_out) {
			if ($second_time_in == $first_time_in) {
				return true;
			} 			
			$date = date('H:i:s', $second_time_in);
			$second_time_in = strtotime($date . "+1 minute");
		}
		return false;
	}	
	
	/*
		Checks if $number is in between $boundery. $number=4, $bounderies=21-5. Is 4 within 21-5
	*/
	public static function isNumberWithin($number, $boundery) {
//		$numbers = explode('-', $boundery);
//		sort($numbers);
//		$return = false;
//		if ($number >= $numbers[0] && $number <= $numbers[1]) {
//			$return = true;
//		}
//		return $return;
		
//		$return = false;
//		list($start_day, $end_day) = explode('-', $boundery);
//		if ($start_day >= $number && $end_day >= $number) {
//			$return = true;
//		}
//		return $return;
	}
	
	/*
		Extracts between dates
		
		Usage: 
			$start_date = '2010-11-20';
			$end_date = '2010-11-25';
			$x = Payroll_Tools::getBetweenDates($start_date, $end_date);
		
		Output:
			Array
			(
				[0] => 2010-11-20
				[1] => 2010-11-21
				[2] => 2010-11-22
				[3] => 2010-11-23
				[4] => 2010-11-24
				[5] => 2010-11-25
			)				
	*/
	public static function getBetweenDates($start_date, $end_date) {
		$mk_start = strtotime($start_date);
		$mk_end= strtotime($end_date);
		while ($mk_start <= $mk_end) {				
			$date = date('Y-m-d', $mk_start);
			$data[] = $date;
			$mk_start = strtotime($date . "+1 day");
		}
		return $data;
	}
	
	/*
		Returns day format of the given $date. 'su', 'm', 'tu', 'w', 'th', 'f', 'sa'
	*/
	public static function getDayFormat($date) {
		if (!empty($date)) {
			$days = array('su', 'm', 'tu', 'w', 'th', 'f', 'sa');
			$day_of_the_week = date('w', strtotime($date)); // gets the day of the week. 0=sunday, 1=monday, ....,  6=saturday		
			return $days[$day_of_the_week];
		}
	}
	
	/*
		Checks if day matched the list of days. $day=tu $based='m-tu-w'. Is 'tu' is matched with 'm-tu-w'
	*/
	public static function isDayMatched($day, $based) {
		$return = false;
		$based_days = explode('-', $based);
		if (in_array($day, $based_days)) {
			$return = true;
		}
		return $return;
	}
	
	/*
		Sort week days. From sundays to saturdays
		
		@param string $days. Ex: m-tu-w-th-f-sa-su
		@return string. Becomes: su-m-tu-w-th-f-sa
	*/
	public static function sortWeekDays($days) {
		$days_array = explode('-', $days);
		$arrangement = array('su', 'm', 'tu', 'w', 'th', 'f', 'sa');
		foreach ($arrangement as $a) {
			$key = array_search($a, $days_array);
			if (is_int($key)) {
				$arranged_keys[] = $key;
			}
		}
		foreach ($arranged_keys as $the_key) {
			$days_final[] = $days_array[$the_key];
		}
		return implode('-', $days_final);
	}
	
	/*
		Converts hour format to time format. 8.50 becomes 08:30
	*/
	public static function convertHourToTime($hour_format) {
		list($hours, $minutes) = explode('.', $hour_format);
		$minutes = ((float) "0.{$minutes}") ;
		return $hours . ':' . round(60 * $minutes);
	}
	
	/*
		Converts time format to hour format. 8 hour and 30 mins (8:30 becomes 8.50)
	*/	
	public static function convertTimeToHour($time) {
		list($hours, $minutes) = explode(':', $time);
		if (empty($hours) && empty($minutes)) {
			return 0;
		}
		//return Tools::numberFormat((($hours * 60) + $minutes) / 60);
		return (($hours * 60) + $minutes) / 60;
	}
	
	/*
		Usage:
			Payroll_Tools::convertToMilitaryTime('12:00 AM');
			
		Output:
			24:00:00
	*/
	public static function convertToMilitaryTime($time) {
		list($time_hours, $temp) = explode(':', $time);
		list($time_minutes, $time_am) = explode(' ', $temp);
		//if ($time_hours == 12)
	}
	
	/*
		Usage:
			$date = '2011-05-4';
			$cutoff[] = '21-5';
			$cutoff[] = '6-20';
			$x = Payroll_Tools::getCutOffPeriod($date, $cutoff);
		Output:
			$dates['start'] = '2011-02-02';
			$dates['end'] = '2011-02-20';	
	*/
	public static function getCutOffPeriod($date, $patterns) {		
		list($year, $month, $day) = explode('-', $date);
		foreach ($patterns as $cutoff) {
			list($start, $end) = explode('-', $cutoff);
			if ($start > $end) {
				$start_date = date("Y-m-{$start}", strtotime("{$date}"));
				$end_date = date("Y-m-{$end}", strtotime("{$start_date} +1 month"));
				if (Payroll_Tools::isDateWithinDates($date, $start_date, $end_date)) {
					$cutoff_dates['start'] = $start_date;
					$cutoff_dates['end'] = $end_date;					
					return $cutoff_dates;
				}
				
				$start_date = date("Y-m-{$start}", strtotime("{$date} -1 month"));
				$end_date = date("Y-m-{$end}", strtotime("{$start_date} + 1 month"));
				if (Payroll_Tools::isDateWithinDates($date, $start_date, $end_date)) {
					$cutoff_dates['start'] = date('Y-m-d', strtotime("$start_date"));
					$cutoff_dates['end'] = date('Y-m-d', strtotime("$end_date"));
					return $cutoff_dates;
				}
			} else {				
				$month_days = date('t', strtotime("{$date}")); //Number of days in the given month - 28 to 31
				if ($month_days < $end) {
					$end = $month_days;	
				}				
				$start_date = date("Y-m-{$start}", strtotime("{$date}"));
				$end_date = date("Y-m-{$end}", strtotime("{$date}"));
				
				if (Payroll_Tools::isDateWithinDates($date, $start_date, $end_date)) {
					$cutoff_dates['start'] = date('Y-m-d', strtotime("$start_date"));
					$cutoff_dates['end'] = date('Y-m-d', strtotime("$end_date"));
					return $cutoff_dates;
				}
			}
		}		
	}
	
	public static function isDateWithinDates($date, $start_date, $end_date) {
		$dates = Payroll_Tools::getBetweenDates($start_date, $end_date);
		foreach ($dates as $the_date) {
			if (strtotime($the_date) == strtotime($date)) {
				return true;
			}
		}
		return false;
	}
}
?>