<?php
class Cron_Controller extends Controller
{
	function __construct() {
		parent::__construct();
	}
	
	function index() {		
		$this->dailyTimesheet();		
	}
	
	function daily_update() {
		$this->payslipPeriod();
		$date = date('Y-m-d', strtotime("now"));
		$employees = G_Employee_Finder::findAllActiveByDate($date);
		foreach ($employees as $e) {
			G_Attendance_Helper::updateAttendance($e, $date);
		}
	}	

	function payslipPeriod() {
		$date = Tools::getGmtDate('Y-m-d');
		$cycle = G_Salary_Cycle_Finder::findDefault();
		$current = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
		G_Cutoff_Period_Manager::savePeriod($current['start'], $current['end'], G_Salary_Cycle::TYPE_SEMI_MONTHLY);	
	}
	
	function update_no_attendance() {
		G_Attendance_Helper::updateAllNoAttendanceDateByPeriod('2012-09-01', '2012-09-15');
	}
	
	function dailyTimesheet() {
		$logs = G_Attendance_Log_Finder::findAllYesterday();
		foreach ($logs as $log) {
			$time = date('H:i:s', strtotime($log->getTime()));
			$date = $log->getDate();
			$employee_id = $log->getEmployeeId();
			$timesheets[$employee_id][$date][$time] = $date;
		}
		
		$tr = new Timesheet_Raw_Reader;
		foreach ($timesheets as $id => $timesheet) {
			$e = G_Employee_Finder::findById($id);	
			if ($e) {
				$updated_times = $tr->getTimeInAndOut($timesheet);
				foreach ($updated_times as $date_in => $times) {
					list($time_in, $temp_date_in) = explode(' ', $times['in']);
					list($time_out, $date_out) = explode(' ', $times['out']);
					if ($time_in != '' && $time_out != '') {
						G_Attendance_Helper::recordTimecard($e, $date_in, $time_in, $time_out, $date_in, $date_out);
						G_Attendance_Helper::updateAttendance($e, $date_in);
					}
				}
			}						
		}
	}	
	
//	function dailyTimesheet($date, $database = '') {
//		$yesterday = $date;
//		$sql = "SELECT * FROM g_fp_attendance_log WHERE date = '{$yesterday}'";
//		$result = Model::runSql($sql);
//		while ($row = Model::fetchAssoc($result)) {
//			$time = date('H:i:s', strtotime($row['time']));
//			$timesheets[$row['user_id']][$row['date']][$time] = $row['date'];
//		}
//		$tr = new Timesheet_Raw_Reader;
//		foreach ($timesheets as $id => $timesheet) {
//			$e = G_Employee_Finder::findById($id);	
//			if ($e) {
//				$updated_times = $tr->getTimeInAndOut($timesheet);
//				foreach ($updated_times as $date_in => $times) {
//					list($time_in, $temp_date_in) = explode(' ', $times['in']);
//					list($time_out, $date_out) = explode(' ', $times['out']);
//					if ($time_in != '' && $time_out != '') {
//						G_Attendance_Helper::recordTimecard($e, $date_in, $time_in, $time_out, $date_in, $date_out);
//						G_Attendance_Helper::updateAttendance($e, $date_in);
//					}
//				}
//			}						
//		}
//	}
}
?>