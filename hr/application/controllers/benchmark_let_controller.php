<?php
class Benchmark_Let_Controller extends Controller
{
	function __construct() {
		parent::__construct();
	}
	
	function index() {	
		echo 'test';
		$e = G_Employee_Finder::findById(244);
		$s = G_Attendance_Helper::generateAttendance($e, '2015-05-05');
		//echo '<pre>';
		//print_r($s);
		exit;
	}
	
	function import_overtime() {
		//$file = $_FILES['timesheet']['tmp_name'];
		$file = BASE_PATH . 'files/files/import_overtime.xlsx';
		$g = new G_Overtime_Import($file);		
		if ($g->import()) {
			echo 'yehey';	
		} else {
			echo 'no';	
		}
	}

	function import_timesheet() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);		
		$file = BASE_PATH . 'files/files/dtr.xlsx';
		//G_Attendance_Helper::importTimesheet($file);
		//$time = new G_Timesheet_Import_IM($file);
		//$is_imported = $time->import();
		
		$time = new Timesheet_Raw_Converter_IM($file);
		$raw_timesheet = $time->convert();	
		echo '<pre>';
		print_r($raw_timesheet);
		
		$r = new G_Timesheet_Raw_Logger($raw_timesheet);
		$r->logTimesheet();
		
		$tr = new G_Timesheet_Raw_Filter($raw_timesheet);
		$ts = $tr->filter();	
		echo '<pre>';
		print_r($ts);
	}
	
	function import_weekly_schedule() {
		$file = BASE_PATH . 'files/files/import_schedule_weekly.xlsx';
		$date = '2012-09-15';
		$g = new G_Schedule_Import_Weekly($file);	
		$g->setEffectivityDate($date);			
		$g->import();
		$es = $g->getEmployees();
		echo '<pre>';
		print_r($es);
	}
	
	function import_changed_schedule() {
		$file = BASE_PATH . 'files/files/change sched.xlsx';
		$g = new G_Schedule_Import_Dates($file);	
		if ($g->import()) {
			echo 'yes';	
		}
	}	
	
	function import_restday() {
		//$file = $_FILES['timesheet']['tmp_name'];
		$file = BASE_PATH . 'files/files/import_restday.xlsx';
		$g = new G_Restday_Import($file);		
		$g->import();		
	}
	
	function attendance() {
		$e = G_Employee_Finder::findById(2347);
		$s = G_Attendance_Helper::generateAttendance($e, '2012-10-07');	
		echo '<pre>';
		print_r($s);
	}
}
?>