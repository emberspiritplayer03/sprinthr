<?php
class Cron_Controller extends Controller
{
	function __construct() {
		parent::__construct();
	}
	
	function index() {		
		//$this->add_daily_attendance();
		$this->update_payslip_period();
		//$this->update_yesterday_timesheet();
		
		echo 'cron works!';	
	}
	
	function add_daily_attendance() {
		$date = date('Y-m-d', strtotime("now"));
		G_Attendance_Helper::updateAttendanceByAllActiveEmployees($date);
	}	

	function update_payslip_period() {
		G_Cutoff_Period_Helper::addNewPeriod();	
	}
	
	function update_yesterday_timesheet() {
		$logs = G_Attendance_Log_Finder::findAllYesterdayUntilNow();
		$timesheets = G_Attendance_Log_Helper::convertLogsToTimesheets($logs);
		$tr = new G_Timesheet_Raw_Filter($timesheets);
		$tr->filterAndAdd();
	}
}
?>