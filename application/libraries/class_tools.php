<?php
class Tools {
    public static function countDaysDifference($date1, $date2) {
        $start_date = $date1;
        $end_date = $date2;
        $d = new DateTime($start_date);
        $d2 = new DateTime($end_date);
        $x = $d->diff($d2);
        return $x->days + 1;
    }

    public static function getMonthString($month_number) {
        $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        return $months[$month_number - 1];
    }

    public static function getAutoDateInAndOut($date, $time_in, $time_out) {
        $dates['date_in'] = $date;
        $dates['date_out'] = $date;
        if (Tools::isTimeAfternoon($time_in) && Tools::isTimeMorning($time_out)) {
            $dates['date_in'] = $date;
            $tomorrow_date = Tools::getTomorrowDate("{$date} {$time_out}");
            $dates['date_out'] = $tomorrow_date;
        }
        return $dates;
    }

    public static function computeHoursDifferenceByDateTime($date_time_in, $date_time_out) {
        $d1 = new DateTime("{$date_time_in}");
        $d2 = new DateTime("{$date_time_out}");
        $d = $d1->diff($d2);
        $hours = $d->h;
        $minutes_to_hours = $d->i / 60; // convert to hours

        return (float) $hours + $minutes_to_hours;
    }

    public static function newComputeHoursDifferenceByDateTime($date_time_in, $date_time_out) {
        $start_date = new DateTime($date_time_in);
		$since_start = $start_date->diff(new DateTime($date_time_out));

		$minutes = $since_start->days * 24 * 60;
		$minutes += $since_start->h * 60;
		$minutes += $since_start->i;
		$hours   = $minutes / 60;
		return (float) $hours;
    }

    public static function getTomorrowDate($date_time) {
        $d1 = new DateTime("{$date_time}");
        $d1->modify('+1 day');
        $date = $d1->format('Y-m-d');
        return $date;
    }


    //monthly
	public static function getCutOffPeriodMonthly($date, $patterns) {	

		list($year, $month, $day) = explode('-', $date);

		foreach ($patterns as $key => $cutoff) {

			list($start, $end) = explode('-', $cutoff);

			if ($start > $end) {

				$start_date = date("Y-m-{$start}", strtotime("{$date}"));
				$end_date = date("Y-m-{$end}", strtotime("{$start_date} +1 month"));
				if (Tools::isDateWithinDates($date, $start_date, $end_date)) {
					$cutoff_dates['start'] = $start_date;
					$cutoff_dates['end'] = $end_date;
                    $cutoff_dates['cutoff_number'] = $key + 1;
					return $cutoff_dates;
				}
				
				$start_date = date("Y-m-{$start}", strtotime("{$date} -1 month"));
				$end_date = date("Y-m-{$end}", strtotime("{$start_date} + 1 month"));
				if (Tools::isDateWithinDates($date, $start_date, $end_date)) {
					$cutoff_dates['start'] = date('Y-m-d', strtotime("$start_date"));
					$cutoff_dates['end'] = date('Y-m-d', strtotime("$end_date"));
                    $cutoff_dates['cutoff_number'] = $key + 1;
					return $cutoff_dates;
				}
				
				
				


			} else {

				$month_days = date('t', strtotime("{$date}")); //Number of days in the given month - 28 to 31
				if ($month_days < $end) {
					$end = $month_days;	
				}				
				$start_date = date("Y-m-{$start}", strtotime("{$date}"));
				$end_date = date("Y-m-{$end}", strtotime("{$date}"));
				
				if (Tools::isDateWithinDates($date, $start_date, $end_date)) {
					$cutoff_dates['start'] = date('Y-m-d', strtotime("$start_date"));
					$cutoff_dates['end'] = date('Y-m-d', strtotime("$end_date"));
                    $cutoff_dates['cutoff_number'] = $key + 1;
					return $cutoff_dates;
				}
			}
		}		
	}


    public static function isValidDateTime($date = '', $format = ''){
    	if( $format == '' ){
    		$format = 'Y-m-d';
    	}
    	$d = DateTime::createFromFormat($format, $date);
    	return $d && $d->format($format) == $date;
    }

    /*
    *   Gets the start date and end date of the week (Sunday is the first day of the week)
    *
    *   Returns array - $date['start_date'] and $date['end_date'];
    */
    public function findWeekStartDateAndEndDate($date) {
        $current_day = (int) date("w", strtotime($date));
        $d = new DateTime($date);
        $d->modify("-{$current_day} day");
        $return['start_date'] = $d->format('Y-m-d');
        $d->modify("+6 days");
        $return['end_date'] = $d->format('Y-m-d');

        return $return;
    }

	/*
		UPDATED FUNCTIONS FROM IM
	*/
	public function getDateInAndOut($time_in, $time_out, $date) {
		if (!Tools::isTimeNightShift($time_in)) {
			$hours_worked = Tools::computeHoursDifference($time_in, $time_out);
		} else {
			$hours_worked = Tools::getHoursDifference($time_in, $time_out);
		}
					
		if (Tools::getAfternoon($time_in) && Tools::getMorning($time_out)) {
			$date_in = $date;
			if ($hours_worked <= 4) {
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
	public function computeHourDifference($date_time_in, $date_time_out) {
		$time = Tools::getTimeDifference("$date_time_in", "$date_time_out");
		//return number_format((($time['hours'] * 60) + $time['minutes'] + $time['seconds'] / 60) / 60, 4, '.', '');	
		return number_format(($time['days'] * 24) + (($time['hours'] * 60) + $time['minutes'] + $time['seconds'] / 60) / 60, 4, '.', '');
	}
	
	/* END */
	
	
	public function computeTimeDifference($start_time,$end_time) {
		$start 	= strtotime($start_time);
		$end	   = strtotime($end_time);
		echo round(abs($start - $end) / 60,2). " minute";
	}
	
	public function computeTimeDifferenceInHrs($start_date_time,$end_date_time) {
		$start_date_time = strtotime($start_date_time);
		$end_date_time	  = strtotime($end_date_time);
		return round(abs($start_date_time - $end_date_time) / 3600,2). " hours";
	}
	
	public function send_email_default($from, $to, $subject, $body) {		
		$headers = "From: " . $from . "\r\n";										
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";		
		try{  
		 @mail($to, $subject, $body, $headers);	
		}  
		catch (Exception $e){  
		 echo($e->getMessage().'<pre>'.$e->getTraceAsString().'</pre>');
		}  
		return true;
	}
	
	//get first character of a string
	
	public function getFirstLetter($string) {
		
		return $string[0];
	}
		
	public function computeHoursWorked($time_in, $time_out) {
		if (!Tools::isTimeNightShift($time_in)) {
			$hours_worked = Tools::computeHoursDifference($time_in, $time_out);
		} else {
			$hours_worked = Tools::getHoursDifference($time_in, $time_out);
		}
		return $hours_worked;
	}

    public static function isTimeMorning($time) {
        $time_start = strtotime('00:00:00');
        $time_end = strtotime('11:59:00');
        $is_morning_time = false;

        $mktime = strtotime($time);
        if ($mktime >= $time_start && $mktime <= $time_end) {
            $is_morning_time = true;
        }
        return $is_morning_time;
    }

    public static function isNightShift($time) {
        $end_night_shift = strtotime(G_Attendance::END_NIGHT_SHIFT_TIME);
        $is_night_shift  = false;

        $mktime = strtotime($time);
        if ($mktime <= $end_night_shift) {
            $is_night_shift = true;
        }
        return $is_night_shift;
    }

    public static function isTimeAfternoon($time) {
        $time_start = strtotime('00:00:00');
        $time_end = strtotime('11:59:00');

        $mktime = strtotime($time);
        if ($mktime >= $time_start && $mktime <= $time_end) {
            $is_afternoon_time = false;
        } else {
            $is_afternoon_time = true;
        }
        return $is_afternoon_time;
    }

	public static function getAfternoon($time) {
		$time_start = strtotime('00:00:00');
		$time_end = strtotime('11:59:00');
		$afternoon_time = '';
		$mktime = strtotime($time);
		if ($mktime >= $time_start && $mktime <= $time_end) {
			//$afternoon_time = $time;
		} else {
			$afternoon_time = $time;
		}
		return ($afternoon_time == '') ? false : $afternoon_time ;
	}
	
	public function decrpytEncryptedStringToArray($string) {
		$arr = explode(",",$string);
		foreach($arr as $p){			
			if (!empty($p)) {				
				$new_array[] = Utilities::decrypt($p);
			}
		}

		return implode(",",$new_array);
	}

	public function getMorning($time) {
		$time_start = strtotime('00:00:00');
		$time_end = strtotime('11:59:00');
		$morning_time = '';

		$mktime = strtotime($time);
		if ($mktime >= $time_start && $mktime <= $time_end) {
			$morning_time = $time;
		}
		return ($morning_time == '') ? false : $morning_time ;
	}

	
	public static function isDateSaturday($date) {
		$saturday = 6;
		$day = date('w', strtotime($date));
		if ($saturday == $day) {
			return true;	
		} else {
			return false;	
		}
	}
	
	public static function isDateSunday($date) {
		$sunday = 0;
		$day = date('w', strtotime($date));
		if ($sunday == $day) {
			return true;	
		} else {
			return false;	
		}
	}	
	
	public static function isTimeNightShift($time) {
		$ns_time_start = strtotime('17:00:00');
		$ns_time_end = strtotime('23:59:00');
		$time = strtotime($time);
		
		if ($time >= $ns_time_start && $time <= $ns_time_end) {
			return true;
		} else {
			return false;	
		}
	}	
	/*
		$value - '2:00 pm';
		
		output:
			'14:00:00'
	*/
	public static function convert12To24Hour($time) {
		list($hour, $temp_minutes) = explode(':', $time);
		list($minutes, $am) = explode(' ', $temp_minutes);
		return date('H:i:00', strtotime(Tools::addLeadingZero($hour) .':'. Tools::addLeadingZero($minutes) .' '. $am));
	}
	
	public static function convert24To12Hour($time) {
		return date('g:i a', strtotime($time));	
	}
	
	public static function isTimeBetweenHours($time, $time_start, $time_end) {
		$str_time = strtotime($time);
		$temp_time = date('H:00:00', $str_time);		
		$hours = Tools::getBetweenHours($time_start, $time_end);
		if (in_array($temp_time, $hours)) {
			return true;	
		} else {
			return false;	
		}
	}
	
	/*
		GET LIMIT TIME
		EXAMPLE: IF YOU HAVE 8:00am - 4:00pm and want to get only the 3 hours of it.
		THE ANSWERS IS: 8:00am - 11:00am
		
		$limit_hours - how many hours need to get
		$start_time - what time it starts
		$end_time - what time it ends
		
		Output:
			Array
			(
				[start] => 17:00:00
				[end] => 22:00:00
			)		
	*/
	public static function getLimitTime($limit_hours, $start_time, $end_time) {
		$hours = Tools::computeHoursDifference($start_time, $end_time);
		$temp_hours = $hours - $limit_hours;
		if ($temp_hours > 0) {
			$minus_minutes = ($temp_hours * 60);	
		} else {
			$minus_minutes = 0;	
		}
		$return['start'] = $start_time;
		$return['end'] = date('H:i:s', strtotime($end_time ." -{$minus_minutes} minutes"));
		return $return;
	}
	
	/*
		GET EXCESS TIME
		EXAMPLE: IF YOU WANT TO LIMIT TO 2 HOURS AND GET THE EXCESS TIME OF 8:00am - 11:00am
		ANSWER: 10:00am - 11:00am
	*/
	public static function getExcessTime($limit_hours, $start_time, $end_time) {
		$hours = Tools::computeHoursDifference($start_time, $end_time);
		$temp_hours = $hours - $limit_hours;
		if ($temp_hours > 0) {
			$minus_minutes = ($temp_hours * 60);	
		} else {
			$minus_minutes = 0;	
		}
		$return['start'] = date('H:i:s', strtotime($end_time ." -{$minus_minutes} minutes"));
		$return['end'] = $end_time;//date('H:i:s', strtotime($end_time ." -{$minus_minutes} minutes"));
		return $return;
	}
	
	public static function computeHoursDifference($start, $end) {
		$time['start'] = strtotime($start);
		$time['end'] = strtotime($end);		
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
				
				if ($hours > 0) { // this is to fix to bug: 01:00:00 - 2:00:00 = 1 hour (NOT 2 hours)
					$the_hour = date('H', strtotime($start));
					if ($the_hour == '00' || $the_hour == '01') {
						$hours = $hours - 1;	
					}
				}
								     
				return number_format((($hours * 60) + $minutes + $difference / 60) / 60, 4, '.', '');
			}
			else {
				$difference = $time['start'] - $time['end'];
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
				$hours = 24 - $hours;
				return number_format((($hours * 60) + $minutes + $difference / 60) / 60, 4, '.', '');
			}
		} else {
			return 0;
		}
		return 0;
	}	
	
	/*
		Like getBetweenDates() but it gets between hours
		EXAMPLE: 8:00am - 11:00am
		ANSWER: 
			Array
			(
				[0] => 08:00:00
				[1] => 09:00:00
				[2] => 10:00:00
				[3] => 11:00:00
			)
		
	*/
	public static function getBetweenHours($start_time, $end_time) {
		$midnight = false;
		$temp_start_time = date('H:00:00', strtotime($start_time));
		$temp_end_time = date('H:00:00', strtotime($end_time));
		if ($temp_end_time == '00:00:00') {
			$midnight = true;
			$temp_end_time = '01:00:00';
		}
		$mk_start = strtotime($temp_start_time);
		$date = date('H:i:s', $mk_start);
		$mk_end = strtotime($temp_end_time);
		
		while ($mk_start != $mk_end) {			
			$date = date('H:i:s', $mk_start);
			$data[] = $date;
			$mk_start = strtotime($date . "+1 hour");
		}
		if (!$midnight) {
			$date = date('H:i:s', $mk_start);
			$data[] = $date;
			$mk_start = strtotime($date . "+1 hour");
		}
		return $data;
	}
	
	public static function getHoursDifference($start, $end) {
		$uts['start'] = strtotime($start);
		$uts['end'] = strtotime($end);
		if( $uts['start']!==-1 && $uts['end']!==-1 ) {
			if($uts['end'] >= $uts['start']) {
				$time = Tools::getTimeDifference($start,$end);
				$hours = number_format((($time['hours'] * 60) + $time['minutes'] + $time['seconds'] / 60) / 60, 4, '.', '');
				return $hours;
			} else {		
				$start = date('H:i:s', strtotime("$start -12 hours"));
				$end = date('H:i:s', strtotime("$end -12 hours"));
				$time = Tools::getTimeDifference($start, $end);
				return number_format((($time['hours'] * 60) + $time['minutes'] + $time['seconds'] / 60) / 60, 4, '.', '');
			}
		} else {
			return 0;//trigger_error( "Invalid date/time data detected", E_USER_WARNING );
		}
		return 0;
	}	
	
	public static function numberFormat($value, $decimal = 2) {
		return number_format($value, $decimal, '.', '');
	}	

	public static function objectToArray($object) {
		$array = array();
		$array = (is_object($object)) ? get_object_vars($object): $object;
		return $array;
	}	
	
	
	//0=a, 1=b
	function change_to_letters($string) {
	
		$replacements = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u");
		$string = $replacements[$string];
		return $string;
	}
	
	//search in array
	//example find word = 'bl'
	// array('black','blue','green','yello');
	//result array('black','blue');
	function searchInArray($data= array(),$input) {
		
		$result = array_filter($data, function ($item) use ($input) {
		    if (stripos($item, $input) !== false) {
       			return true;
		    }
			return false;
		});
		return $result;
	}
	
	
	public static function getTimeDifference($start, $end) {
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
				
				if ($hours > 0) { // this is to fix to bug: 01:00:00 - 2:00:00 = 1 hour (NOT 2 hours)
					$the_hour = date('H', strtotime($start));
					if ($the_hour == '00' || $the_hour == '01') {
						$hours = $hours - 1;	
					}
				}
				return(array('days '=> $days, 'hours' => $hours, 'minutes' => $minutes, 'seconds' => $difference));
			}
			else {
				return 0;
			}
		} else {
			return 0;
		}
		return 0;
	}
	/*
	* Returns number of days. difference of two dates
	*
	* @param string $from 2009-12-25
	* @param string $to 2009-12-28	
	* @return int
	*/		 
	 public static function getDayDifference($from, $to) {
	 	list($from_year, $from_month, $from_day) = explode('-', $from);
	 	list($to_year, $to_month, $to_day) = explode('-', $to);
		$from = mktime(0, 0, 0, $from_month, $from_day, $from_year);
		$to = mktime(0, 0, 0, $to_month, $to_day, $to_year);
		$date_diff = $to - $from;
		return floor($date_diff/(60*60*24));
	 }
	 
	/*
	* Returns the dates based from GMT. it is currently philippine GMT +8
	*
	* @param string $format 'Y-m-d'
	* @param int $timestamp strtotime("now");	
	* @return string
	*/		 		
	public static function getGmtDate($format, $timestamp = NULL){
		$timestamp = (empty($timestamp)) ? strtotime("now") : $timestamp ;
		$offset = (int) +8; //Setting::getValue('gmt');
	   //Offset is in hours from gmt, including a - sign if applicable.
	   //So lets turn offset into seconds
	   $offset = $offset * 60 * 60;
	   $timestamp = $timestamp + $offset;
		//Remember, adding a negative is still subtraction ;)
	   return gmdate($format, $timestamp);
	}

    public static function getCurrentYear() {
        return Tools::getGmtDate('Y');
    }

    public static function getNextYear() {
        $year = Tools::getGmtDate('Y-m-d');
        return Tools::getGmtDate('Y', strtotime($year . " + 1 year"));
    }

    public static function getPreviousYear() {
        $year = Tools::getGmtDate('Y-m-d');
        return Tools::getGmtDate('Y', strtotime($year . " - 1 year"));
    }
	
	public static function generateGenericCodeNumber($table, $prefix='')
	{
		if($table=='') {
			exit();	
		}
		$next_id = self::mysql_next_id($table);
		if($next_id<10)
		{
			$return = '1000'.$next_id;
		}elseif($next_id<100 && $next_id>9)
		{
			$return = '100'.$next_id;
		}elseif($next_id<1000 && $next_id>99)
		{
			$return = '10'.$next_id;
		}elseif($next_id<10000 && $next_id>999)
		{
			$return = '1'.$next_id;
		}elseif($next_id<100000 && $next_id>9999)
		{
			$return = $next_id;
		}
		$prefix = ($prefix) ? $prefix : '' ;
		return $prefix . '-'. $return;
	}
	
	public static function generateEmployeeId($table) {
		if($table=='') {
			exit();	
		}
		$next_id = self::mysql_next_id($table);
		if($next_id<10)
		{
			$return = '1000'.$next_id;
		}elseif($next_id<100 && $next_id>9)
		{
			$return = '100'.$next_id;
		}elseif($next_id<1000 && $next_id>99)
		{
			$return = '10'.$next_id;
		}elseif($next_id<10000 && $next_id>999)
		{
			$return = '1'.$next_id;
		}elseif($next_id<100000 && $next_id>9999)
		{
			$return = $next_id;
		}
		return date("Y") . '-'. $return;
	}
	
	public static function mysql_next_id($table) {
	
    	$result = mysql_query('SHOW TABLE STATUS LIKE "'.$table.'"');
    	$rows = mysql_fetch_assoc($result);
	   	return $rows['Auto_increment'];
	}
	
	public static function convertToValidUrl($string) {
		$string = preg_replace('/[^a-z0-9_ ]/i', '', $string);
		$string = preg_replace('/[ ]/i', '_', $string);
		return strtolower($string);
	}
	
	public static function getCoveredWeekDays($date) {
		list($year, $month, $day) = explode('-', $date);
		$week_number = Tools::getGmtDate('W', strtotime("$year-$month-$day"));
		$zxc = $year; //Get the current year
		$qwe = strtotime("$zxc-1-1"); //First day of the Year		
		//1 year has 52 week
		for( $week = 0; $week <= 52; $week++) {
			$asd = strtotime("+$week week +3 days" ,$qwe);		
			$valid = $asd;
			$weeks[Tools::getGmtDate('W', $valid)] = Tools::getGmtDate('Y-m-d', $valid);
			//echo "Week ".date('W', $valid).", ".Tools::getGmtDate('Y-m-d', $valid)."<br/>";
		}
		$end_date = $weeks[$week_number];
		$end_mktime = strtotime($end_date . "+7 days");
		$start_mktime = strtotime($end_date);
		$start_date = Tools::getGmtDate('Y-m-d', $start_mktime);
		
		while ($start_mktime < $end_mktime) {
			$days_mktime[] = $start_mktime; //date('Y-m-d', $start_mktime);
			$start_mktime = strtotime("+1 day", $start_mktime);
		}
		foreach ($days_mktime as $d) {
			$days[] = Tools::getGmtDate('Y-m-d', $d);
		}
		return $days;
	}

	public static function checkFileExist($filename) {

		if(file_exists($filename)) {
			$return = true;
		}else {
			$return = false;

		}
		return $return;
	}	
	
	public static function getCoveredWeeks($month, $year) {
		//list($year, $month, $day) = explode('-', $date);
		$week_number = date('W', strtotime("$year-$month-1"));
		$zxc = $year; //Get the current year
		$qwe = strtotime("$zxc-1-1"); //First day of the Year		
		//1 year has 52 week
		for( $week = 0; $week <= 52; $week++) {
			$asd = strtotime("+$week week +3 day" ,$qwe);
			if (date('m', $asd) == $month) {	
				$valid = $asd;
				$weeks[date('W', $valid)] = date('Y-m-d', $valid);
				//echo "Week ".date('W', $valid).", ".date('Y-m-d', $valid)."<br/>";
				$days[] = date('Y-m-d', $valid) . '/' . date('Y-m-d', strtotime("+6 days",$valid));
			}
		}
		return $days;
	}

    public static function getMonthNames() {
        return array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    }
	
	public static function getCoveredMonths($year) {
		for ($i = 1; $i <= 12; $i++) {
			$last_date = date('t', strtotime("$year-$i-1"));
			$months[] = date("Y-m-d", strtotime("$year-$i-1")) . '/' . date("Y-m-d", strtotime("$year-$i-$last_date"));
		}
		return $months;
	}

	public static function isInteger($value) {
		return (preg_match('/(?<!\S)\d++(?!\S)/', $value)) ? true : false ;
	}
	public static function hasValue($value) {
		return (strlen(trim($value)) > 0) ? true : false ;
	}
	
	public static function isValidDate($the_date) {		
		//return (preg_match('/(19|20)[0-9]{2}[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])/', $value)) ? true : false ;
		$is_date = false;
		$time = date('H:i:s', strtotime($the_date));
		$date = date('Y-m-d', strtotime($the_date));
		if ($date != '1970-01-01' && $time == '00:00:00') {
			$is_date = true;	
		}
		return $is_date;			
	}

    public static function isTime1LessThanTime2($time1, $time2) {    	
        $time1 = strtotime($time1);
        $time2 = strtotime($time2);
        if ($time1 <= $time2) {        	  	
        	return true;
        } else {        	
        	return false;
        }
    }
	
	public static function isValidTime($the_time) {
		$temp_value = (int) $the_time;
		if (strlen($temp_value) == 4 || strlen($temp_value) == 6 || strlen($temp_value) == 10 || strlen($temp_value) == 13 || strlen($temp_value) == 17) {
			return false;	
		}
		$is_time = false;
		$time = date('H:i:s', strtotime($the_time));
		$date = date('Y-m-d', strtotime($the_time));
		if ($date != '1970-01-01' && $time != '00:00:00') {
			$is_time = true;	
		}		
		return $is_time;	
	}	
	
	public static function isDate($value) {
		return (strtotime($value)!='') ? 1 : 0 ;
	}
	
	public static function friendlyTitle($draftTitle) {
		if(substr($draftTitle, -3) == '_id'){
			$draftTitle = substr($draftTitle, 0, -3);
		}
		$new_title = ucwords(str_replace('_', ' ', $draftTitle));
		
		return $new_title;	
	}
	
	public static function friendlyFormName($draftTitle) {
	
		if(is_numeric(substr($draftTitle,0,1))) {

				$new_title = 'Required_' .  strtolower(str_replace(' ', '_', $draftTitle));	
			
		}else {
			$new_title = strtolower(str_replace(' ', '_', $draftTitle));
		}

		return $new_title;	
	}
	
	//Usage
	//$current = Tools::getCurrentDateTime('Y-m-d h:i:s a','Asia/Manila');
	public static function getCurrentDateTime($format,$time_zone) {
		//date_default_timezone_set($time_zone);
		$current_time = time();
		$date_time    = date($format,$current_time);
		return $date_time;
	}
	
	//Usage
	//$current = Tools::limitCharater("Your Content", 100, " ");
	public static function limitCharater($string, $limit, $break = ".", $pad = "..."){
   	  $string = strip_tags($string);	
	// return with no change if string is shorter than $limit
	  if(strlen($string) <= $limit) return $string;
	
	  // is $break present between $limit and the end of the string?
	  if(false !== ($breakpoint = strpos($string, $break, $limit))) {
		if($breakpoint < strlen($string) - 1) {
		  $string = substr($string, 0, $breakpoint) . $pad;
		}
	  }
		
	  return $string;

	}
	
	public static function createRandomPasswordByLength($length) {
		$chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ023456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '' ;
		while ($i <= $length) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
		return $pass;
	}
	
	//Usage
	//$current = Tools::createRandomPassword();
	public static function createRandomPassword() {
		$chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ023456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '' ;
		while ($i <= 29) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
		return $pass;
	}
	
	/*
		Returns day format of the given $date. 'sun', 'mon', 'tue, 'wed', 'thu', 'fri', 'sat'
	*/
	public static function getDayFormat($date) {
		if (!empty($date)) {
			$days = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');
			$day_of_the_week = date('w', strtotime($date)); // gets the day of the week. 0=sunday, 1=monday, ....,  6=saturday		
			return $days[$day_of_the_week];
		}
	}
	
	public static function addLeadingZero($number) {
		$number = (int) $number;
		if ($number < 10 && strlen($number) == 1) {
			return "0{$number}";	
		} else {
			return $number;	
		}
	}
	
	public static function timeFormat($time) {
		return date('g:i a', strtotime($time));
	}
	
	public static function currencyFormat($value) {
		return number_format($value, '2', '.', ',');
	}
	
	public static function dateFormat($date) {
		return Tools::getGmtDate('Y-m-d', strtotime($date));
	}

	public static function convertDateFormat($date) {
		return Tools::getGmtDate('M j, Y', strtotime($date));
	}
	
	//$_FILE['filename'] 
	
	public static function uploadFile($files,$prefix='') {
		
		$len = strlen($files['filename']['name']);
		$pos = strpos($files['filename']['name'],'.');
		$extension_name =  substr($files['filename']['name'],strrpos($files['filename']['name'], '.') + 1);
		$handle = new upload($files['filename']);
		$path = $_SERVER['DOCUMENT_ROOT'] . FILES_FOLDER;
	
	   if ($handle->uploaded) {
			$handle->file_new_name_body   = $filename = $prefix.'_'.date('Y-m-d-H-i-s');
			$handle->file_overwrite 	  = true;
		
	       $handle->process($path);
	       if ($handle->processed) {
	         	
				$image =  $filename . ".". strtolower($extension_name); 
				
	            $handle->clean();
				$return['filename'] = $image;
			    $return['error'] =  '';
			  	$return['is_uploaded'] = true;
			 
	       } else {	          
			  $return['error'] =  $handle->error;
			  $return['is_uploaded'] = false;
	       }
	   }else {
			$return['error'] =  $handle->error;
			$return['is_uploaded'] = false;
	   }	
	   
	   return $return;
	}
	
	public static function isFileExist($filename) {		
		if(file_exists($_SERVER['DOCUMENT_ROOT'].$filename)) {
			$return = true;
		}else {
			$return = false;

		}
		return $return;
	}	

	public static function isFileExistDirectPath($filename) {		
		if(file_exists($filename)) {
			$return = true;
		}else {
			$return = false;

		}
		return $return;
	}	
	
	public static function removeFile($file='') {
		
		if($file!='') {
			$myFile = $file;
			$fh = fopen($myFile, 'w') or die("can't open file");
			
			fclose($fh);	
			unlink($myFile);
		}
	}
	
	/*
		Extracts between dates
		
		Usage: 
			$start_date = '2010-11-20';
			$end_date = '2010-11-25';
			$x = Tools::getBetweenDates($start_date, $end_date);
		
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
		$mk_end = strtotime($end_date);
        if ($mk_start > $mk_end) {
            $data[] = $start_date;
            return $data;
        }
		while ($mk_start <= $mk_end) {				
			$date = date('Y-m-d', $mk_start);
			$data[] = $date;
			$mk_start = strtotime($date . "+1 day");
		}
		return $data;
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
		Converts hour format to time format. 8.50 becomes 08:30
	*/
	public static function convertHourToTime($hour_format) {
		list($hours, $minutes) = explode('.', $hour_format);
		$minutes = ((float) "0.{$minutes}") ;
		return $hours . ':' . round(60 * $minutes);
	}

	/*
	 * @param string 
	   @param array cutoff period pattern
	   @param int - 0 pattern index to be use or substitute to day
	 * returns string
	*/
	public static function convertDateToCutoffPattern( $date = '', $cutoff_pattern = array(), $pattern_index_to_use = 0 ){
		$new_date = '';
		if( !empty($cutoff_pattern) ){					
			$month = date("m",strtotime($date));
			$day   = date("d",strtotime($date));
			$cutoff_day = $cutoff_pattern[$pattern_index_to_use];

			if( $month == 2 && $cutoff_day > 28 ){
				$new_date = date("Y-m-t",strtotime($date));
			}else{
				if( $cutoff_day == 31 ){
					$new_date = date("Y-m-t",strtotime($date));
				}else{
					$new_date = date("Y-m-{$cutoff_day}",strtotime($date));
				}				
			}
		}
		return $new_date;
	}
	
	/*
		Usage:
			$date = '2011-05-4';
			$cutoff[] = '21-5';
			$cutoff[] = '6-20';
			$x = Tools::getCutOffPeriod($date, $cutoff);
		Output:
			$dates['start'] = '2011-02-02';
			$dates['end'] = '2011-02-20';	
	*/
	public static function getCutOffPeriod($date, $patterns) {		
		list($year, $month, $day) = explode('-', $date);

		foreach ($patterns as $key => $cutoff) {
			list($start, $end) = explode('-', $cutoff);
			if ($start > $end) {
				$start_date = date("Y-m-{$start}", strtotime("{$date}"));
				$end_date = date("Y-m-{$end}", strtotime("{$start_date} +1 month"));
				if (Tools::isDateWithinDates($date, $start_date, $end_date)) {
					$cutoff_dates['start'] = $start_date;
					$cutoff_dates['end'] = $end_date;
                    $cutoff_dates['cutoff_number'] = $key + 1;
					return $cutoff_dates;
				}
				
				$start_date = date("Y-m-{$start}", strtotime("{$date} -1 month"));
				$end_date = date("Y-m-{$end}", strtotime("{$start_date} + 1 month"));
				if (Tools::isDateWithinDates($date, $start_date, $end_date)) {
					$cutoff_dates['start'] = date('Y-m-d', strtotime("$start_date"));
					$cutoff_dates['end'] = date('Y-m-d', strtotime("$end_date"));
                    $cutoff_dates['cutoff_number'] = $key + 1;
					return $cutoff_dates;
				}
			} else {				
				$month_days = date('t', strtotime("{$date}")); //Number of days in the given month - 28 to 31
				if ($month_days < $end) {
					$end = $month_days;	
				}				
				$start_date = date("Y-m-{$start}", strtotime("{$date}"));
				$end_date = date("Y-m-{$end}", strtotime("{$date}"));
				
				if (Tools::isDateWithinDates($date, $start_date, $end_date)) {
					$cutoff_dates['start'] = date('Y-m-d', strtotime("$start_date"));
					$cutoff_dates['end'] = date('Y-m-d', strtotime("$end_date"));
                    $cutoff_dates['cutoff_number'] = $key + 1;
					return $cutoff_dates;
				}
			}
		}		
	}
	/*
		Usage:
			$cutoffs = Array
			(
				[0] => 26-10
				[1] => 11-25
			)			
			$payouts = Array
			(
				[0] => 15
				[1] => end
			)
	*/
	public static function getPayoutDate($date, $cutoffs, $payouts) {		
		$cutoff[$cutoffs[0]] = date('d', strtotime('2012-01-'.$payouts[0]));
		$cutoff[$cutoffs[1]] = date('d', strtotime('2012-01-'.$payouts[1]));		
		$current 	  = Tools::getCutOffPeriod($date, $cutoffs);				
		$first 		  = date('j', strtotime($current['start']));		
		$second 	  = date('j', strtotime($current['end']));		
		$combined     = $first .'-'. $second;				
		$payout_day   = $cutoff[$combined];		
		$current_date = $current['end'];		
		$current_day  = date('d', strtotime($current_date));		
		
		if ($payout_day == 'end') {		
			$payout_date = date('Y-m-t', strtotime($current['end']));
		} else {						
			for ($i = 0; $i <= 60; $i++) {							
				if ($current_day == $payout_day) {		
					break;	
				}
				$current_mktime = strtotime($current_date. " +1 day");
				$current_date   = date('Y-m-d', $current_mktime);
				$current_day    = date('d', $current_mktime);
			}

			$payout_date = date('Y-m-d', $current_mktime);			
		}
		return $payout_date;
	}

	/*
		Usage : 
		$date    = "2014-12-01";
		$cycle	 = G_Salary_Cycle_Finder::findDefault();	
		$current = Tools::getCutOffPeriod($date, $cycle->getCutOffs());			
		$payout_date = Tools::getPayoutDateMod($current['end'], $cycle->getPayoutDays());
	*/

	public static function getPayoutDateMod($date, $payouts) {				
		$payout_date = '';

		$day   = date("d",strtotime($date));
		$month = date("m",strtotime($date)); 
		$year  = date("Y",strtotime($date));
		
		if( $day <= $payouts[0] && $day > $payouts[1] ){
			$new_day = $payouts[0];			
		}else{
			$new_day = $payouts[1];
		}

		$payout_date = date("Y-m-d",strtotime("{$year}-{$month}-{$new_day}"));

		return $payout_date;
	}
	
	public static function isDateWithinDates($date, $start_date, $end_date) {
		$dates = Tools::getBetweenDates($start_date, $end_date);
		foreach ($dates as $the_date) {
			if (strtotime($the_date) == strtotime($date)) {
				return true;
			}
		}
		return false;
	}
	
	public static function getNextId($table_name,$currend_id)
	{
		$sql = "
		    SELECT id
			FROM ".$table_name."
			WHERE id > ".Model::safeSql($currentid)."
			ORDER BY id ASC
			LIMIT 1
		";
	}
	
	public static function getPreviousId($table_name,$currend_id)
	{
		$sql = "
		    SELECT id
			FROM ".$table_name."
			WHERE ID > ".Model::safeSql($currentid)."
			ORDER BY id ASC
			LIMIT 1
		";
	}
	
	public static function sendMailSwiftMailer($subject,$email,$msg,$smtp,$port,$username,$password,$from,$attachment)
	{		
		$message11   = Swift_Message::newInstance();	
		$transport11 = Swift_SmtpTransport::newInstance($smtp, $port);							
		$transport11->setUsername($username);
		$transport11->setpassword($password);	
											
		$mailer11 = Swift_Mailer::newInstance($transport11);
		$message11->setSubject($subject);	
		$message11->setFrom(array($from['email'] => $from['title']));
		$message11->setTo($email);								
		//$attachment = complete url of file
		if($attachment){
			$message11->attach(Swift_Attachment::fromPath($attachment));
		}
		//
		$message11->setBody($msg , 'text/html');		
		$numsent = $mailer11->send($message11, $recipients);		
		
		return $numsent;
	}
	
	public static function isFirstTimeWithinSecondTime($first_time_in, $first_time_out, $second_time_in, $second_time_out) {
/*		$second_time_in = strtotime($second_time_in);
		$second_time_out = strtotime($second_time_out);		
		$first_time_in = strtotime($first_time_in);
		while ($second_time_in != $second_time_out) {
			if ($second_time_in == $first_time_in) {
				return true;
			} 			
			echo $date = date('H:i:s', $second_time_in);
			echo '<br>';
			$second_time_in = strtotime($date . "+1 minute");
		}
		return false;*/
		
		$first_time_in = strtotime($first_time_in);
		$first_time_out = strtotime($first_time_out);
		$second_time_in = strtotime($second_time_in);
		$second_time_out = strtotime($second_time_out);
		$return = false;
		if (($first_time_in >= $second_time_in && $first_time_in <= $second_time_out) && ($first_time_out >= $second_time_in && $first_time_out <= $second_time_out)) {
			$return = true;
		}
		return $return;
	}	
	//input 
	// $number=1;
	//Tools::getOrdinalSuffix($number,1);
	//output 1st <the word st will superscript)
	function getOrdinalSuffix($number,$sup=1) {
	
	  is_numeric($number) or trigger_error("<b>\"$value\"</b> is not a number!, The value must be a number in the function <b>ordinal_suffix()</b>", E_USER_ERROR);
		if(substr($number, -2, 2) == 11 || substr($number, -2, 2) == 12 || substr($number, -2, 2) == 13){
			$suffix = "th";
		}
		else if (substr($number, -1, 1) == 1){
			$suffix = "st";
		}
		else if (substr($number, -1, 1) == 2){
			$suffix = "nd";
		}
		else if (substr($number, -1, 1) == 3){
			$suffix = "rd";
		}
		else {
			$suffix = "th";
		}
		if($sup){
			$suffix = "<sup>" . $suffix . "</sup>";
		}
		return $number . $suffix;	
	}
	
	function getSubOrdinalSuffix($number,$sup=1) {
	
	  is_numeric($number) or trigger_error("<b>\"$value\"</b> is not a number!, The value must be a number in the function <b>ordinal_suffix()</b>", E_USER_ERROR);
		if(substr($number, -2, 2) == 11 || substr($number, -2, 2) == 12 || substr($number, -2, 2) == 13){
			$suffix = "th";
		}
		else if (substr($number, -1, 1) == 1){
			$suffix = "st";
		}
		else if (substr($number, -1, 1) == 2){
			$suffix = "nd";
		}
		else if (substr($number, -1, 1) == 3){
			$suffix = "rd";
		}
		else {
			$suffix = "th";
		}
		if($sup){
			$suffix = $suffix;			
		}
		return $number . $suffix;	
	}
	
	public function stringReplace($string)
	{
		//Replace SQL Special Char
		$string = str_replace( array( '\'', "'", '"', ',' , ';', '<', '>', '/', '?', '!', ':', '.', '(', ')'), '', $string);
		return trim($string);
	}
	
	
	/*
		Usage:
		$employee = G_Employee_Finder::findAll();
		Tools::showArray($employee); 
	*/
	public static function showArray($array) {
		echo '<pre>';
		print_r($array);
		exit;	
	}
	
	public static function convertEncryptedStringToArray($delimeter,$string) {
		$arr = explode($delimeter,$string);
		foreach($arr as $a){										
			$new_array[] = Utilities::decrypt($a);				
		}
		return $new_array;
	}

	public static function encryptArrayIndexValue($index_name = '', $data = array()) {		
		$new_data = $data;
		if( !empty($new_data) ){
			foreach( $new_data as $key => $value ){
				if( strtolower($key) == strtolower($index_name) ){
					$new_data[$key] = Utilities::encrypt($value);
				}
			}
		}

		return $new_data;
	}

	public static function encryptMulitDimeArrayIndexValue($index_name = '', $data = array()) {		
		$new_data = $data;
		if( !empty($new_data) ){
			foreach( $data as $key =>$sub_data ){
				foreach( $sub_data as $subKey => $value ){					
					if( strtolower($subKey) == strtolower($index_name) ){
						$new_data[$key][$subKey] = Utilities::encrypt($value);
					}
				}
			}
		}

		return $new_data;
	}
	
	public static function convertStringToArray($delimeter,$string) {
		$arr = explode($delimeter,$string);
		foreach($arr as $a){										
			$new_array[] = $a;				
		}
		return $new_array;
	}
	
	public static function getQuarterByMonth($monthNumber) {
	  return floor(($monthNumber - 1) / 3) + 1;
	}
	
	public static function convertDate1530($cdate){		
		$ddate         = date("Y-m-d",strtotime($cdate));				
		$d_start_date  = strtotime(date("Y-m-d", strtotime($ddate)));
		$month 		   = date("n",strtotime($cdate));					
		$start_day	   = date("j",$d_start_date);			
		if($month == 2){
			if($start_day < 15){
				$add_day = 15 - $start_day;	
			}elseif($start_day > 15){					
				$add_day = 28 - $start_day;
			}else{
				$add_day = 0;
			}
		}else{
			if($start_day < 15){							
				$add_day = 15 - $start_day;	
				//echo $add_day . '<br>';	
			}elseif($start_day > 15 && $start_day < 30){
				$add_day = 30 - $start_day;
			}elseif($start_day == 31){			
				$subtract_day = 1;
			}else{
				if($month == 1 && $start_day == 30){				
					$add_day = 0;
				}else{
					$add_day = 0;
				}
			}		
		}
					
		if($start_day == 31){
			$converterd_date = strtotime(date("Y-m-d", strtotime($ddate)) . "-" . $subtract_day . " day");			
		}else{
			$converterd_date = strtotime(date("Y-m-d", strtotime($ddate)) . "+" . $add_day . " day");	
		}
		return $converterd_date;
	}
	
	public static function getQuarterDay($monthNumber, $dayNumber, $yearNumber) {
	  $quarterDayNumber = 0;
	  $dayCountByMonth = array();
	
	  $startMonthNumber = ((self::getQuarterByMonth($monthNumber) - 1) * 3) + 1;
	
	  // Calculate the number of days in each month.
	  for ($i=1; $i<=12; $i++) {
		$dayCountByMonth[$i] = date("t", strtotime($yearNumber . "-" . $i . "-01"));
	  }
	
	  for ($i=$startMonthNumber; $i<=$monthNumber-1; $i++) {
		$quarterDayNumber += $dayCountByMonth[$i];
	  }
	
	  $quarterDayNumber += $dayNumber;
	
	  return $quarterDayNumber;
	}

	public static function getCurrentQuarterDay() {
	  return self::getQuarterDay(date('n'), date('j'), date('Y'));
	}
	
	/*
		Reference :
		
		$date1 = "2007-03-24";
		$date2 = "2009-06-26";
		
		$diff = abs(strtotime($date2) - strtotime($date1));
		
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		
		printf("%d years, %d months, %d days\n", $years, $months, $days);
		
		
	*/
	
	 public static function getDateDifference2($from, $to) {
	 	list($from_year, $from_month, $from_day) = explode('-', $from);
	 	list($to_year, $to_month, $to_day) = explode('-', $to);
		$from = mktime(0, 0, 0, $from_month, $from_day, $from_year);
		$to = mktime(0, 0, 0, $to_month, $to_day, $to_year);
		$date_diff = $to - $from;
		
		$return['years'] 	= floor($date_diff / (365*60*60*24));
		$return['months'] 	= floor(($date_diff - $years * 365*60*60*24) / (30*60*60*24));
		$return['days'] 	= floor(($date_diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		return $return;
	 }
	
	public static function getMonthDifference($start_date, $end_date) {
		$diff 	= Tools::getDateDifference2($start_date,$end_date);
		$months = $diff['months'];
		
		if(date('L',strtotime($end_date)) == true) {
			if(date("m-d",strtotime($end_date)) == "02-29" && date("M",strtotime($start_date)) == "Jan") {
				$months++;
			} 
		} else {
			if(date("m-d",strtotime($end_date)) == "02-28" && date("M",strtotime($start_date)) == "Jan") {
				$months++;
			}
		}
		//echo floor($days / 30) . '<br/>';
		//echo $days / 30 . '<br/>';
		return $months;
	}
	
	public static function convertXmlToArray($xmlUrl,$filename)
	{
		//CONVERT XML TO ARRAY
		$xmlUrl = $xmlUrl . $filename;	
		if(Tools::isFileExist($file)==true) {			
			$xmlStr = file_get_contents($xmlUrl);
			$xmlStr = simplexml_load_string($xmlStr);

			$xml2 = new Xml;
			$arrXml = $xml2->objectsIntoArray($xmlStr);				
			$return = $arrXml;
		}else {
			$return = 'No file exist';
		}
		
		return $return;
		
	}
		
	public function getDataUsingCurl($url) {
		$url = urlencode($url);
		$options = array(
	        CURLOPT_RETURNTRANSFER => true,     // return web page
	        CURLOPT_HEADER         => false,    // don't return headers
	        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
	        CURLOPT_ENCODING       => "",       // handle all encodings
	        CURLOPT_USERAGENT      => "spider", // who am i
	        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
	        CURLOPT_CONNECTTIMEOUT => 15,      // timeout on connect
	        CURLOPT_TIMEOUT        => 15,      // timeout on response
	        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
	
	    );	
	    $ch      = curl_init($url);	    
	    curl_setopt_array( $ch, $options );
	    $content = curl_exec( $ch );
	    $err     = curl_errno( $ch );
	    $errmsg  = curl_error( $ch );
	    $header  = curl_getinfo( $ch,CURLINFO_EFFECTIVE_URL );
	    curl_close( $ch );
	    return $content;
	}

	public function is_connected()
	{
	    $connected = @fsockopen("www.google.com", 80); 

	    if ($connected){
	        $return = true; //action when connected
	        fclose($connected);
	    }else{
	        $return = false; //action in connection failure
	    }
	    return $return;
	}

	public function get_client_ip() {
	    // Function to get the client IP address
	    $ipaddress = '';
	    if ($_SERVER['HTTP_CLIENT_IP'])
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if($_SERVER['HTTP_X_FORWARDED'])
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if($_SERVER['HTTP_FORWARDED_FOR'])
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if($_SERVER['HTTP_FORWARDED'])
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if($_SERVER['REMOTE_ADDR'])
			$ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}

	function getUserIP()
	{
	    $client  = @$_SERVER['HTTP_CLIENT_IP'];
	    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	    $remote  = $_SERVER['REMOTE_ADDR'];

	    if(filter_var($client, FILTER_VALIDATE_IP))
	    {
	        $ip = $client;
	    }
	    elseif(filter_var($forward, FILTER_VALIDATE_IP))
	    {
	        $ip = $forward;
	    }
	    else
	    {
	        $ip = $remote;
	    }

	    return $ip;
	}
	
	public function getRealIpAddr()
	{
	  //echo "<PRE>" . print_r($_SERVER, true) . "</PRE>";
	  if (!empty($_SERVER['HTTP_CLIENT_IP']))
	  //check ip from share internet
	  {
	    $ip=$_SERVER['HTTP_CLIENT_IP'];
	  }
	  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	  //to check ip is pass from proxy
	  {
	    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	  }
	  else
	  {
	    $ip=$_SERVER['REMOTE_ADDR'];
	  }
	  return $ip;
	}
	
	public function ip_is_private ($ip) {
	    $pri_addrs = array (
	                      '10.0.0.0|10.255.255.255', // single class A network
	                      '172.16.0.0|172.31.255.255', // 16 contiguous class B network
	                      '192.168.0.0|192.168.255.255', // 256 contiguous class C network
	                      '169.254.0.0|169.254.255.255', // Link-local address also refered to as Automatic Private IP Addressing
	                      '127.0.0.0|127.255.255.255' // localhost
	                     );
	
	    $long_ip = ip2long ($ip);
	    if ($long_ip != -1) {
	
	        foreach ($pri_addrs AS $pri_addr) {
	            list ($start, $end) = explode('|', $pri_addr);
	
	             // IF IS PRIVATE
	             if ($long_ip >= ip2long ($start) && $long_ip <= ip2long ($end)) {
	                 return true;
	             }
	        }
	    }
	
	    return false;
	}
	
	public static function getDayHourDifference($date_in,$date_out,$time_in,$time_out,$timezone = "Asia/Manila") {
		date_default_timezone_set($timezone);
		list($from_year, $from_month, $from_day) = explode('-', $date_in);
	 	list($to_year, $to_month, $to_day) = explode('-', $date_out);
		
		list($from_hour,$from_minutes,$from_seconds) = explode(':',$time_in);
		list($to_hour,$to_minutes,$to_seconds) = explode(':',$time_out);
		
		$from = mktime($from_hour, $from_minutes, $from_seconds, $from_month, $from_day, $from_year);
		$to = mktime($to_hour, $to_minutes, $to_seconds, $to_month, $to_day, $to_year);
		$date_diff = $to - $from;
		
		$fullDays    = floor($date_diff/(60*60*24));
	  	$fullHours   = floor(($date_diff-($fullDays*60*60*24))/(60*60));
	   	$fullMinutes = floor(($date_diff-($fullDays*60*60*24)-($fullHours*60*60))/60);
		$return['full_days'] 	= $fullDays;
		$return['full_hours']	= $fullHours;
		$return['full_minutes']	= $fullMinutes;
		
		$return['total_hour']	= ($fullDays * 24)+$fullHours+($fullMinutes / 60);
	   
	    //echo "Difference is $fullDays days, $fullHours hours and $fullMinutes minutes.";
		//Tools::showArray($return);
		return $return;
	}
	
	public static function getMonthEndDay($m) {
		$xtra_day = (date('L',strtotime($end_date)) == true ? '29' : '28');
		$month = array("Jan"=>31,"Feb"=>$xtra_day,"Mar"=>31,"Apr"=>30,"May"=>31,"Jun"=>30,"Jul"=>31,"Aug"=>31,"Sep"=>30,"Oct"=>31,"Nov"=>30,"Dec"=>31);
		
		return $month[$m];
		
	}
	
	//$xtra_day = (date('L',strtotime($end_date)) == true ? '29' : '28');
		//$month = array("Jan"=>31,"Feb"=>$xtra_day,"Mar"=>31,"Apr"=>30,"May"=>31,"Jun"=>30,"Jul"=>31,"Aug"=>31,"Sep"=>30,"Oct"=>31,"Nov"=>30,"Dec"=>31);


	function createDateRangeArray($strDateFrom,$strDateTo)
	{
	    // takes two dates formatted as YYYY-MM-DD and creates an
	    // inclusive array of the dates between the from and to dates.
	    // stockoverflow: RobertPitt

	    $aryRange=array();

	    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
	    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

	    if ($iDateTo>=$iDateFrom)
	    {
	        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
	        while ($iDateFrom<$iDateTo)
	        {
	            $iDateFrom+=86400; // add 24 hours
	            array_push($aryRange,date('Y-m-d',$iDateFrom));
	        }
	    }
	    return $aryRange;
	}	

	/*function isValidTime( $time = null ) {
		$is_valid = false;
		if( $datetime != '' && $datetime != '00:00:00' ){
			$is_valid = true;
		}

		return $is_valid;
	}*/

	public function isWithinTime($start_time, $end_time, $time) {

        $date1 = date("H:i:s ", strtotime($time));
        $date2 = date("H:i:s ", strtotime($start_time));
        $date3 = date("H:i:s ", strtotime($end_time));
        //echo "{$date1} > {$date2} && {$date1} <= {$date3} <br>";
        $f = DateTime::createFromFormat('H:i', $date2);
	    $t = DateTime::createFromFormat('H:i', $date3);
	    $i = DateTime::createFromFormat('H:i', $date1);

	    $f = new DateTime($date2);
	    $f->format('H:i');
	    $t = new DateTime($date3);
	    $t->format('H:i');
	    $i = new DateTime($date1);
	    $i->format('H:i');
	    

	    if ($f > $t) $t->modify('+1 day'); 
	    return ($f <= $i && $i <= $t) || ($f <= $i->modify('+1 day') && $i <= $t);
    }
    
    public function validateDateTime($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

}
?>