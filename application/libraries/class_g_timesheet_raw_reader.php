<?php
/*
	DEPRECATED - USE g_timesheet_raw_filter INSTEAD

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
	
	$tr = new G_Timesheet_Raw_Reader($timesheets);
	$x = $tr->getTimesheet();
*/
class G_Timesheet_Raw_Reader {
	protected $timesheets;
	protected $current_timesheet;
	protected $current_employee_id;
	protected $errors_no_out;
	
	public function __construct($timesheets) {
		$this->timesheets = $timesheets;
	}
	
	public function getTimesheet() {
		$timesheets = $this->timesheets;		
		ksort($timesheets);
		foreach ($timesheets as $employee_id => $timesheet) {
			ksort($timesheet);
			$this->current_timesheet = $timesheet;
			$this->current_employee_id = $employee_id;
			$data = $this->getValidTimesheet($employee_id);
			if ($data) {
				$return[$employee_id] = $data;
			}
		}
		return $return;
	}
	
	private function getValidTimesheet($employee_id) {
		$ins = $this->getDateIns();
		foreach ($ins as $date_in => $times) {
			if ($last_date_in) {
				$first_time = $this->getFirstTime($times);
				$days = Tools::getDayDifference($last_date_in, $date_in);
				$hours = Tools::computeHoursDifference($last_time, $first_time);
				//echo "{$last_date_in} {$last_time} - {$date_in} {$first_time} = {$hours}";
				//echo '<br>';
				if (($hours <= 8) && $days <= 1 && $this->isNightShift($last_times) && $this->isMorning($times)) {
					continue;
				}
			}		
			$date_time_out = $this->findDateOutAndTimeOut($date_in, $times);			
			if ($date_time_out) {
				$time_in = $this->getFirstTime($times);
				$date_out = $date_time_out['date_out'];
				$time_out = $date_time_out['time_out'];
				
				$return[$date_in]['in'] = "{$time_in} {$date_in}";
				$return[$date_in]['out'] = "{$time_out} {$date_out}";				
			} else {
				if ($employee_id) {
					$this->addErrorNoOut($employee_id, $date_in, $time_in);
				}
			}
			$last_time = $this->getLastTime($times);
			$last_times = $times;
			$last_date_in = $date_in;
		}
		return $return;		
	}
	
	private function addErrorNoOut($employee_id, $date_in, $time_in) {
		$this->errors_no_out[$employee_id]['in'][$date_in][$time_in] = $date_in;
	}
	
	public function getErrorsNoOut() {
		return $this->errors_no_out;	
	}
	
	private function getDateIns() {
		$ins = $this->current_timesheet['in'];
		ksort($ins);
		return $ins;
	}
	
	private function getDateOuts() {
		$outs = $this->current_timesheet['out'];
		ksort($outs);
		return $outs;
	}
	
	private function findDateOutAndTimeOut($date_in, $time_ins) {
		$outs = $this->getDateOuts();
		foreach ($outs as $date_out => $time_outs) {
			$is_matched = $this->isMatchedDateOutAndTimeOut($date_in, $time_ins, $date_out, $time_outs);
			if ($is_matched) {
				$date['date_out'] = $date_out;
				$date['time_out'] = $this->getLastTime($time_outs);
				return $date;
			}
		}
	}
	
	private function isMatchedDateOutAndTimeOut($date_in, $time_ins, $date_out, $time_outs) {
		$is_ns = $this->isNightShift($time_ins);
		$date_in_tomorrow = date('Y-m-d', strtotime($date_in . ' +1 day'));
		if ($date_in == $date_out && !$is_ns) {
			return true;
		} else if ($date_out == $date_in_tomorrow && $is_ns) {
			return true;
		} else {
			return false;	
		}
	}
	
	private function getLastTime($times) {
		foreach ($times as $time => $date) {
			$last_time = $time;	
		}
		return $last_time;
	}
	
	private function getFirstTime($times) {
		foreach ($times as $time => $date) {
			$first_time = $time;
			return $first_time;
		}
	}	
	
	private function isNightShift($times) {
		$ns_time_start = strtotime('17:00:00');
		$ns_time_end = strtotime('23:59:00');
		
		foreach ($times as $time => $date) {
			$time = strtotime($time);
			if ($time >= $ns_time_start && $time <= $ns_time_end) {
				return true;
			} else {
				return false;	
			}
		}
	}
	
	private function isMorning($times) {
		$time_start = strtotime('00:00:00');
		$time_end = strtotime('11:59:00');
		
		foreach ($times as $time => $date) {
			$time = strtotime($time);
			if ($time >= $time_start && $time <= $time_end) {
				return true;
			} else {
				return false;	
			}
		}
	}	
}
?>