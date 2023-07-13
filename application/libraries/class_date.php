<?php
//Updated Feb 8,2012 | Marvin
class Date {
	
	protected $date;
	
	public function __construct($date) {
		$this->date = $date;
	}
	
	
	//input 11:00:00
	//output 11:00 am
	public function convertMilitaryTo12Hours($time) {
		return date("g:i a", strtotime($time));
	}
	
	
	//$data = "Thu Sep 27 2009 12:30:00 GMT-0700 (Pacific Daylight Time)";
	// return 2009-8-27-12-30 // 2009 Sep 27 12:30
	// Date::convertFormatStringToDate($data);
	//note:: 1 is Jan // this is for the jquery calendar convertion
	public function convertFormatStringToDate($data)
	{
		//get the month //Ex Sep
		//return int
	
		$month = substr($data,4,3); //return Oct
		$year = substr($data,11,4); //2009
		$day = substr($data,8,2); //01
		$hh = substr($data,16,2); //08
		$mm = substr($data,19,2); //30
			$arrayMonth  = array(1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"May",6=>"Jun", 7=>"Jul",8=>"Aug",9=>"Sep",10=>"Oct",11=>"Nov",12=>"Dec");
			
			$intMonth = array_search($month,$arrayMonth);
			return $year ."-". $intMonth ."-". $day ."-". $hh ."-". $mm;
	}
	//input Oct 01, 2009
	//date("mdy"); // 031001
	//output 031001
	public function convertDateFormatToDateInt($data) {
		
		$month = substr($data,0,3); //return Oct
		$year = substr($data,10,2); //2009
		$day = substr($data,4,2); //01
		
			$arrayMonth  = array("01"=>"Jan","02"=>"Feb","03"=>"Mar","04"=>"Apr","05"=>"May","06"=>"Jun", "07"=>"Jul","08"=>"Aug","09"=>"Sep","10"=>"Oct","11"=>"Nov",12=>"Dec");
			
			$intMonth = array_search($month,$arrayMonth);
			return $intMonth . $day .  $year;
	}
	
	//input Oct 01, 2009
	//output 2009-10-01
	public function convertDateFormatToDateDataType($data) {
	
		$month = substr($data,0,3); //return Oct
		$year = substr($data,10,4); //2009
		$day = substr($data,4,2); //01
		
			$arrayMonth  = array("01"=>"Jan","02"=>"Feb","03"=>"Mar","04"=>"Apr","05"=>"May","06"=>"Jun", "07"=>"Jul","08"=>"Aug","09"=>"Sep","10"=>"Oct","11"=>"Nov",12=>"Dec");
			
			$intMonth = array_search($month,$arrayMonth);
			return  $year . "-" . $intMonth . "-" . $day ;
	
	}
	
	//input 800
	//output 08:00
	//input 1330
	//output 13:30
	public function convert24to24hours($data,$separator=":")
	{
		
		if(strlen($data)<2){
				return substr_replace($data,"00". $separator. "0",0,0);
		}elseif(strlen($data)<3){
				return substr_replace($data,"00".$separator,0,0);
		}elseif(strlen($data)<4)
		{
				return "0". substr_replace($data,$separator,1,0);
		}else
		{
				return substr_replace($data,$separator,2,0);
		}
	}
	
	//input 24hours 1330
	//return 12hours // 01:30 PM 
	//return 24hours // 24 hours and fixed the time (example 660 will be 700(60minutes = 1hour))
	
	public function convert24to12Hours($data)
	{
		if($data=='CLOSED')
		{
			return 'CLOSED';
			exit;
		}
		$data = (int) $data; 
		//echo $data;
		if($data<=1259) 
		{
			//echo strlen($data);
			if(strlen($data)<2){
				return substr_replace($data,"12:0",0,0) . " AM";
			}
			elseif(strlen($data)<3){
				return substr_replace($data,"12:",0,0) . " AM";
			}elseif(strlen($data)<4)
			{
				return "0". substr_replace($data,":",1,0) . " AM";
			}else {
				if($data>=1200)
				{
				return substr_replace($data,":",2,0) . " NN";
				}else
				{
				return substr_replace($data,":",2,0) . " AM";
				}
			
			}
			
		}else 
		{
			
			$time =  $data - 1200;
			if($time<1000){
			
				return "0" . substr_replace($time,":",1,0) . " PM";
			
			}else {
			
				if($time>=1200)
				{
				return substr_replace($time,":",2,0) . " MN";
				}else
				{
				return substr_replace($time,":",2,0) . " PM";
				}
				//return substr_replace($time,":",2,0) . " AM";
			}
		}	
	}
	
	
	//input 12hours 1:00 PM
	//return 24hours // 1300
	public function convert12to24Hours($data)
	{
		
		if(strlen($data)==7)
		{
			//example 1:30 AM //strelen is 7
			
			$string =  substr($data,0,4);
			$time24 = substr_replace($string,'',1,1);
		}elseif(strlen($data)==8)
		{
			//example 11:30 AM / strlen is 8
			$string = substr($data,0,5);
			$time24 = substr_replace($string,'',2,1);
		}
		
		//get the AM PM NN MN
		$daylight = substr($data,-2);
		
		if($daylight=='PM')
		{
			
			return $time24 + 1200;
		
		}elseif ($daylight=='NN')
		{
			return $time24;
		}elseif ($daylight=='MN'){
			$time = $time24 + 1200;
			if($time>2400)
			{
				return $time - 2400;
			}else{
				return 0;
			}
			
		}elseif($daylight=='AM')
		{
			
			if($time24>1200)
			{
				return $time = $time24-1200;
			}else {
				return $time24;
			}
		}
	}
	
	//input 2009-10-27 10:00:00 or 2009-10-27
	//output 27th day of  October, 2009
	function convertDateIntIntoDateString($data, $type=1)
	{
		if(strlen($data)>10)
		{
			if($type==1) {
				list($year,$month,$day) = split("-",substr($data,0,10));
				list($h,$m,$s) = split(":",substr($data,11,8));
				$return = date("l jS \of F Y h:i:s a", mktime($h, $m, $s, $month, $day, $year));	
			}elseif($type==2) {
				list($year,$month,$day) = split("-",substr($data,0,10));
				list($h,$m,$s) = split(":",substr($data,11,8));
				$return = date("F j\, Y", mktime($h, $m, $s, $month, $day, $year));	 //September 13,2010	
			}
			
		}else
		{
			if($type==1) {
				list($year,$month,$day) = split("-",$data);
				$return = date("F j\, Y", mktime(0, 0, 0, $month, $day, $year)); //September 13,2010	
			}elseif($type==2) {
				list($year,$month,$day) = split("-",$data);
				$return = date("l jS \of F Y", mktime(0, 0, 0, $month, $day, $year)); //Wednesday 1st of  September 2010
				
			}elseif($type==3) {
				list($year,$month,$day) = split("-",$data);
				$return = date("l", mktime(0, 0, 0, $month, $day, $year)); //Wednesday
			}elseif($type==4) {
				list($year,$month,$day) = split("-",$data);
				$return = date("M j\, Y", mktime(0, 0, 0, $month, $day, $year)); //Wednesday
			}
			
		}
		return $return;
	}
	
	//load the time base on the duration
	//$duration = 15mins, time_start=800, time_end=1200
	//RETURN 8:00 AM,8:15 AM,8:30 AM, 8:45 AM
	public function loadTimeWithDuration($duration,$start,$end,$option=12)
	{
		
		//check if the duration is more than 1hour
		if($duration>=60)
		{
			$temp= (int) ($duration/60);
			$new_duration = $temp * 100;
			
			$temp2 = $temp * 60;
			$min = $duration - $temp2;
			
			$duration = $new_duration + $min;
		
		}
		
		//check if the duration is fit on the time span
		$time_diff = Date::get_time_diff(Date::convert24to24hours($start),Date::convert24to24hours($end)); //example 800 and 900 time_diff = days=0 hours=1 min=0
		$time_diff_min = $time_diff['hours'] * 60;
		$time_slot =	$time_diff['minutes'] + $time_diff_min;
		$temp = ($time_slot /60) * 100;
		
		if($temp<$duration)
		{
			return;
			exit;
		}
		
		if($option==12)
		{
		//get 12 hours format
			for($i=$start;$i<=$end;$i+=$duration)
			{
				if(substr($i,-2)<60)
				{//this is for minute
					
					$time[] = Date::convert24to12Hours($i);	
				}
				else{
				//this is if the duration is hour
					$i +=100;
					$i = $i - 60;
					$time[] = Date::convert24to12Hours($i);	
				}
			}
		}else
		{
			//get 24hours format
			for($i=$start;$i<=$end;$i+=$duration)
			{
				if(substr($i,-2)<60)
				{
					
					$time[] = $i; //Date::convert12to24Hours($i);
					
				}
				else{
					$i +=100;
					$i = $i - 60;
					
					$time[] = $i; //Date::convert12to24Hours($i);
					
				}
			}
		
		}
		
		return $time;
		
	
	}
	// a START time value
	//	$start = '09:00';
	// an END time value
	//	$end   = '13:00';
	// output 04:00
	function get_time_difference($start,$end)
	{
		if($diff=Date::get_time_diff($start, $end))
		{
		 	return sprintf( '%02d:%02d', $diff['hours'], $diff['minutes'] );
		}
		else
		{
		  	return "Hours: Error";
		}
	}
	
	//get the time difference
	// example start = 03:30, end = 05:30 
	//output Array ( [days] => 0 [hours] => 2 [minutes] => 0 [seconds] => 0 ) 
	
	function get_time_diff($start, $end)
	{
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
				trigger_error( "Ending date/time is earlier than the start date/time or start is equal to end time", E_USER_WARNING );
			}
		}
		else
		{
			trigger_error( "Invalid date/time data detected", E_USER_WARNING );
		}
		return( false);
	}
	
	/*
	*Input: $begin = "2008-11-01 22:45:00";
	*Input: $end = "2009-12-04 13:44:01";
	*output: array('years','months',days',hours,days,minutes);
	*/
	
	public static function get_day_diff($begin,$end) {

		$diff = abs(strtotime($end) - strtotime($begin));
		
		if( abs(strtotime($begin)) > abs(strtotime($end))  )
		{
			$return=  0;
			
		}else {
			$years = floor($diff / (365*60*60*24)); $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			
			$hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));
			
			$minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
			
			$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
			$return =  ( array('years'=>$years,'months'=>$months,'days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$seconds) );
		}
		
	
		
		//printf("%d years, %d months, %d days, %d hours, %d minuts\n, %d seconds\n", $years, $months, $days, $hours, $minuts, $seconds); 
		
		return $return;
			
	}
	
	//input integer 1-12
	//output january - december
	function getMonthName($int='') {

		$monthNum = $int;
		if($monthNum!='') {

			$monthName = date("F", mktime(0, 0, 0, $monthNum, 10));	
		}
		return $monthName;
	}

	function add_date($givendate,$day=0,$mth=0,$yr=0) {
     	 $cd = strtotime($givendate);
      	 $newdate = date('Y-m-d h:i:s', mktime(date('h',$cd), date('i',$cd), date('s',$cd), date('m',$cd)+$mth,date('d',$cd)+$day, date('Y',$cd)+$yr));
	  
      return $newdate;
     }

//$sql = "SELECT INSERT(SUBSTR(time_start,12),3,1,':') AS time_start, INSERT(SUBSTR(time_end,12),3,1,':') AS time_end FROM oba_appointment WHERE employee_id={$employee_id} AND date like '$date%'  ORDER BY date";
}

?>