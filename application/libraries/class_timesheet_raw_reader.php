<?php
/*
	Reading raw timesheet data. It has multiple time in and out. This class gets the first time in and last time out for a certain day

	$timesheets[1]['2012-07-27']['20:05:42'] = '2012-07-27';
	$timesheets[1]['2012-07-27']['22:10:01'] = '2012-07-27';
	$timesheets[1]['2012-07-27']['01:15:02'] = '2012-07-27';	
	$timesheets[1]['2012-07-27']['03:30:03'] = '2012-07-27';
	$timesheets[1]['2012-07-28']['06:37:03'] = '2012-07-28';	
	$timesheets[2]['2012-07-27']['20:05:42'] = '2012-07-27';
	$timesheets[2]['2012-07-27']['22:10:01'] = '2012-07-27';
	$timesheets[2]['2012-07-27']['01:15:02'] = '2012-07-27';	
	$timesheets[2]['2012-07-27']['03:30:03'] = '2012-07-27';
	$timesheets[2]['2012-07-28']['06:37:03'] = '2012-07-28';
	
	$tr = new Timesheet_Raw_Reader;
	foreach ($timesheets as $id => $timesheet) {
		$user[$id] = $tr->getTimeInAndOut($timesheet);
	}		
*/
class Timesheet_Raw_Reader {
	protected $error = array();
	/*
		Output:
			Array
			(
				[2012-07-27] => Array
					(
						[in] => 20:05:42 2012-07-27
						[out] => 06:37:03 2012-07-28
					)
	
				[2012-07-28] => Array
					(
						[in] => 20:03:03 2012-07-28
						[out] => 06:32:03 2012-07-29
					)
	
				[2012-07-29] => Array
					(
						[in] => 20:32:03 2012-07-29
						[out] => 06:35:03 2012-07-30
					)
			)				
		$timesheet['2012-07-27']['20:05:42'] = '2012-07-27';
		$timesheet['2012-07-27']['22:10:01'] = '2012-07-27';
		$timesheet['2012-07-27']['01:15:02'] = '2012-07-27';	
		$timesheet['2012-07-27']['03:30:03'] = '2012-07-27';
		$timesheet['2012-07-28']['06:37:03'] = '2012-07-28';
		$tr = new Timesheet_Raw_Reader;
		$x = $tr->getTimeInAndOut($timesheet);			
	*/
	public function getTimeInAndOut($timesheets) {		
		$last_hour_diff = 0;
		$last_time = '';
		$last_date = '';
		foreach ($timesheets as $type => $timesheet) {
			ksort($timesheet);
			foreach ($timesheet as $date => $raw_time) {
				foreach ($raw_time as $current_time => $current_date) {
					if ($last_date == '') {
						$last_date = $current_date;	
					}				
					if ($last_time == '') {
						$last_time = $current_time;
					}
					if ($time_in == '') {
						$time_in = "{$current_time} {$current_date}";
					}
					$difference = Tools::getHoursDifference($last_time, $current_time);
					list($temp_time_in, $temp_date_in) = explode(' ', $time_in);
					$current_hour_diff = Tools::getHoursDifference($temp_time_in, $current_time);
					if ($current_hour_diff >= $last_hour_diff) {
						$time_out = "{$current_time} {$current_date}";
					} else {
						$new_timesheet[$temp_date_in]['in'] = $time_in;
						$new_timesheet[$temp_date_in]['out'] = $time_out;					
						$time_in = "{$current_time} {$current_date}";
						$time_out = '';
					}				
					$last_hour_diff = $current_hour_diff;
					$last_time = $current_time;
					$last_date = $current_date;	
				}		
			}
		}
		list($temp_time_in, $temp_date_in) = explode(' ', $time_in);	
		$new_timesheet[$temp_date_in]['in'] = $time_in;
		$new_timesheet[$temp_date_in]['out'] = $time_out;
		return $new_timesheet;
	}
	
	public function getTimesheet($timesheet) {
		ksort($timesheet);
		foreach ($timesheet as $date => $raw_time) {
			$the_in = $this->getIn($raw_time['in']);
			$the_out = $this->getOut($raw_time['out']);
			if ($the_in && $the_out) {
				$new_timesheet[$date]['in'] = $the_in['time'] .' '. $the_in['date'];
				$new_timesheet[$date]['out'] = $the_out['time'] .' '. $the_out['date'];
			} else {
				if (!$the_in && !$the_in) {
					$this->error[$date] = 'no both';
				} else if (!$the_in) {
					$this->error[$date] = 'no in';
				} else if (!$the_out) {
					$this->error[$date] = 'no out';
				}
			}	
		}
		echo '<pre>';
		print_r($new_timesheet);
		
		return $new_timesheet;
	}
	
	public function getError() {
		return $this->error;	
	}
	
	private function getIn($times) {
		$the_time = '';
		$the_date = '';
		foreach ($times as $time => $date) {
			if ($the_time == '' && $the_time == '') {
				if (strtotime($time) && strtotime($date))	 {
					$the_time = $time;
					$the_date = $date;	
					break;
				}
			}
		}
		if ($the_time == '' || $the_date == '') {
			return false;	
		} else {
			$return['time'] = $the_time;
			$return['date'] = $the_date;
			return $return;
		}
	}
	
	private function getOut($times) {
		$the_time = '';
		$the_date = '';
		foreach ($times as $time => $date) {
			if (strtotime($time) && strtotime($date))	 {
				$the_time = $time;
				$the_date = $date;
			}
		}
		if ($the_time == '' || $the_date == '') {
			return false;	
		} else {
			$return['time'] = $the_time;
			$return['date'] = $the_date;
			return $return;
		}

	}	
}
?>