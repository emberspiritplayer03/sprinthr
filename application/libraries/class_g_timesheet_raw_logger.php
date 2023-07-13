<?php
/*
	INSERT LOGS OR DTR TO DATABASE. 
	
	$raw_timesheet[1]['in']['2012-10-18']['20:03:00'] = '2012-10-18';
	$raw_timesheet[1]['out']['2012-10-19']['07:15:00'] = '2012-10-19';
	$raw_timesheet[1]['in']['2012-10-20']['20:04:00'] = '2012-10-20';
	$raw_timesheet[1]['out']['2012-10-21']['07:16:00'] = '2012-10-21';
	$raw_timesheet[1]['out']['2012-10-22']['05:35:00'] = '2012-10-22';
	$raw_timesheet[1]['out']['2012-10-22']['06:35:00'] = '2012-10-22';
	$raw_timesheet[1]['out']['2012-10-22']['07:35:00'] = '2012-10-22';
	
	$r = G_Timesheet_Raw_Logger($timesheets);
	$r->logTimesheet();
*/
	
class G_Timesheet_Raw_Logger {
	protected $raw_timesheets;
	protected $employee_id;
	protected $type; // in or out
	protected $date;
	protected $time;
	protected $logs = array();

	protected $break_logs = array();
	
	public function __construct($raw_timesheets) {
		$this->raw_timesheets = $raw_timesheets;
	}
	
	public function logTimesheet() {
		$timesheets = $this->raw_timesheets;		
		foreach ($timesheets as $employee_code => $timesheet) {		
			$fields   = array("id","CONCAT(lastname, ' ', firstname, ' ', middlename)AS employee_name");
			$employee = G_Employee_Helper::sqlGetEmployeeDetailsByEmployeeCode($employee_code,$fields);

			if ($employee) {
				ksort($timesheet);
				$ins = $timesheet['in'];
				$outs = $timesheet['out'];
				$this->logIns($ins, $employee_code);
				$this->logOuts($outs, $employee_code);
	
				$breaks = $timesheet['breaks'];
				ksort($breaks);
				$this->logBreaks($breaks, $employee_code);
			}
		}
		$this->_logTimesheet($this->logs);
		
		if (count($this->break_logs) > 0) {
			$this->saveBreakLogs($this->break_logs);
		}

	}

    private function _logTimesheet($logs) {
        G_Attendance_Log_Manager::saveMultiple($logs);
    }

    private function saveBreakLogs($break_logs) {
        G_Employee_Break_Logs_Manager::saveMultiple($break_logs);
    }
	
	/*
		Array
		(
			[2012-09-16] => Array
				(
					[17:01:00] => 2012-09-16
				)
			[2012-09-17] => Array
				(
					[07:53:00] => 2012-09-17
				)
			[2012-09-18] => Array
				(
					[07:46:00] => 2012-09-18
				)
			[2012-09-19] => Array
				(
					[19:12:00] => 2012-09-19
				)
		)	
	*/
	
	private function logIns($ins, $employee_code) {
		foreach ($ins as $date => $time_date) {
			if (strtotime($date)) {
				foreach ($time_date as $time => $start_date) {
					if (strtotime($time) && strtotime($start_date)) {
						$final_start_date = date('Y-m-d', strtotime($start_date));
						$final_start_time = date('H:i:s', strtotime($time));
						
						//$a = G_Attendance_Log_Finder::findByEmployeeCodeDateTimeType($employee_code, $final_start_date, $final_start_time, G_Attendance_Log::TYPE_IN);
						
						$total    = G_Attendance_Log_Helper::countLogByEmployeeCodeDateTimeType($employee_code, $final_start_date, $final_start_time, G_Attendance_Log::TYPE_IN);


						$fields   = array("id","CONCAT(lastname, ' ', firstname, ' ', middlename)AS employee_name");
						$employee = G_Employee_Helper::sqlGetEmployeeDetailsByEmployeeCode($employee_code,$fields);

						if ($total == 0) {
							
							if($employee){
								$name = $employee['employee_name'];
								$eid  = $employee['id'];
							}else{
								$name = '';
								$eid  = '';
							}

                            $a = new G_Attendance_Log;
                            $a->setEmployeeId($eid);
                            $a->setEmployeeName($name);
                            $a->setEmployeeCode($employee_code);
                            $a->setDate($final_start_date);
                            $a->setTime($final_start_time);
                            $a->setType(G_Attendance_Log::TYPE_IN);
                            $this->logs[] = $a;

							/*$a = new G_Attendance_Log;
							$a->setEmployeeCode($employee_code);
							$a->setDate($final_start_date);
							$a->setTime($final_start_time);
							$a->setType(G_Attendance_Log::TYPE_IN);	
							$a->save();*/
						}else{
							/*$a = G_Attendance_Log_Finder::findByEmployeeCodeDateTimeType($employee_code, $final_start_date, $final_start_time, G_Attendance_Log::TYPE_IN);
							if($a){
								//echo 2;
								$a->setEmployeeCode($employee_code);
								$a->setDate($final_start_date);
								$a->setTime($final_start_time);
								$a->setType(G_Attendance_Log::TYPE_IN);	
								$a->update();
							}*/
						}
					}
				}
			}
		}
	}
	
	private function logOuts($outs, $employee_code) {
		foreach ($outs as $date => $time_date) {
			if (strtotime($date)) {
				foreach ($time_date as $time => $end_date) {
					if (strtotime($time) && strtotime($end_date)) {
						$final_end_date = date('Y-m-d', strtotime($end_date));
						$final_end_time = date('H:i:s', strtotime($time));
						//echo "Log Out Date" . $final_end_date . "<br />";
						$total = G_Attendance_Log_Helper::countLogByEmployeeCodeDateTimeType($employee_code, $final_end_date, $final_end_time, G_Attendance_Log::TYPE_OUT);

						$fields   = array("id","CONCAT(lastname, ' ', firstname, ' ', middlename)AS employee_name");
						$employee = G_Employee_Helper::sqlGetEmployeeDetailsByEmployeeCode($employee_code,$fields);

						if ($total == 0) {

							if($employee){
								$name = $employee['employee_name'];
								$eid  = $employee['id'];
							}else{
								$name = '';
								$eid  = '';
							}
							
							$a = new G_Attendance_Log;	
							$a->setEmployeeId($eid);
                            $a->setEmployeeName($name);					
							$a->setEmployeeCode($employee_code);
							$a->setDate($final_end_date);
							$a->setTime($final_end_time);
							$a->setType(G_Attendance_Log::TYPE_OUT);
							$this->logs[] = $a;
							//$a->save();
						}else{
							/*$a = G_Attendance_Log_Finder::findByEmployeeCodeDateTimeType($employee_code, $final_start_date, $final_start_time, G_Attendance_Log::TYPE_IN);
							if($a){
								//echo 2;
								$a->setEmployeeCode($employee_code);
								$a->setDate($final_start_date);
								$a->setTime($final_start_time);
								$a->setType(G_Attendance_Log::TYPE_IN);	
								$a->update();
							}*/
						}
					}
				}
			}
		}
	}
	
	private function logBreaks($breaks, $employee_code) {
		foreach ($breaks as $key => $break) {
			if (isset($break['date']) && strtotime($break['date']) && isset($break['time']) && strtotime($break['time'])) {
				$date = $break['date'];
				$time = $break['time'];
				$type = strtolower($break['type']);

				$total = G_Employee_Break_Logs_Helper::countLogByEmployeeCodeDateTimeType($employee_code, $date, $time, $type);

				$fields   = array("id","CONCAT(lastname, ' ', firstname, ' ', middlename)AS employee_name");
				$employee = G_Employee_Helper::sqlGetEmployeeDetailsByEmployeeCode($employee_code,$fields);

				if ($total == 0) {

					if($employee){
						$name = $employee['employee_name'];
						$eid  = $employee['id'];
					}else{
						$name = '';
						$eid  = '';
					}
					
					$a = new G_Employee_Break_Logs;	
					$a->setEmployeeId($eid);
					$a->setEmployeeCode($employee_code);
					$a->setEmployeeName($name);					
					$a->setDate($date);
					$a->setTime($time);
					$a->setType($type);
					$this->break_logs[] = $a;
				}
			}
		}
	}
}
?>