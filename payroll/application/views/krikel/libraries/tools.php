<textarea name="textarea" id="textarea" cols="110" rows="50" wrap="off">
class Tools {

	
	public static function getTimeDifference($start, $end) {
		$uts['start']      =    strtotime( $start );
		$uts['end']        =    strtotime( $end );
		if( $uts['start']!==-1 && $uts['end']!==-1 )
		{
			if( $uts['end'] >= $uts['start'] )
			{
				$diff    =    $uts['end'] - $uts['start'];
				if( $days=intval((floor($diff/86400))) )
					$diff = $diff % 86400;
				if( $hours=intval((floor($diff/3600))) )
					$diff = $diff % 3600;
				if( $minutes=intval((floor($diff/60))) )
					$diff = $diff % 60;
				$diff    =    intval( $diff );            
				return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
			}
			else
			{
				trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
			}
		}
		else
		{
			trigger_error( "Invalid date/time data detected", E_USER_WARNING );
		}
		return( false );
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
	
	public static function generateEmployeeId() {
		$next_id = self::mysql_next_id('s_user');
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
	public static function isValidDate($value) {		
		return (preg_match('/(19|20)[0-9]{2}[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])/', $value)) ? true : false ;	
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

				$new_title = 'dumy_' .  strtolower(str_replace(' ', '_', $draftTitle));	
			
		}else {
			$new_title = strtolower(str_replace(' ', '_', $draftTitle));
		}
		
		
		
		return $new_title;	
	}
	
}

</textarea>
