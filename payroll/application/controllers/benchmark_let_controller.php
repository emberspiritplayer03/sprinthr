<?php
class Benchmark_Let_Controller extends Controller
{
	function __construct() {
		parent::__construct();
	}
	
	function index() {		
//		$timesheet[1]['in']['2012-07-27']['20:05:42'] = '2012-07-27';
//		$timesheet[1]['in']['2012-07-27']['22:10:01'] = '2012-07-27';
//		$timesheet[1]['in']['2012-07-27']['01:15:02'] = '2012-07-27';	
//		$timesheet[1]['in']['2012-07-27']['03:30:03'] = '2012-07-27';
//		$timesheet[1]['out']['2012-07-28']['06:37:03'] = '2012-07-28';
//		$timesheet[1]['out']['2012-07-28']['06:45:03'] = '2012-07-28';
//		$timesheet[1]['in']['2012-07-28']['20:45:03'] = '2012-07-28';
//		$timesheet[1]['in']['2012-07-28']['20:50:03'] = '2012-07-28';
//		$timesheet[1]['out']['2012-07-29']['06:45:03'] = '2012-07-29';
//		$timesheet[1]['in']['2012-07-29']['08:45:03'] = '2012-07-29';
//		$timesheet[1]['in']['2012-07-29']['08:50:03'] = '2012-07-29';
//		$timesheet[1]['in']['2012-07-29']['08:55:03'] = '2012-07-29';	
//		$timesheet[1]['out']['2012-07-29']['20:45:03'] = '2012-07-29';
//		$timesheet[1]['out']['2012-07-29']['20:50:03'] = '2012-07-29';
//		$timesheet[1]['out']['2012-07-29']['20:55:03'] = '2012-07-29';
//		$timesheet[1]['out']['2012-07-29']['21:55:03'] = '2012-07-29';
//		$timesheet[1]['in']['2012-07-30']['08:55:03'] = '2012-07-30';
//		$timesheet[1]['in']['2012-08-01']['08:30:03'] = '2012-08-01';
//		$timesheet[1]['out']['2012-08-02']['08:30:03'] = '2012-08-02';
//		$timesheet[1]['out']['2012-08-03']['08:30:03'] = '2012-08-03';

		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);	
	
		$file = BASE_PATH . 'files/files/consolidated logs.xlsx';
		$c = new Timesheet_Raw_Converter_IM($file);
		$dates = $c->convert();
		//echo '<pre>';
		//print_r($dates);

		$tr = new G_Timesheet_Raw_Reader($dates);
		$x = $tr->getTimesheet();
		echo '<pre>';
		print_r($x);
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
		$file = BASE_PATH . 'files/files/import_dtr_im.xlsx';
		//G_Attendance_Helper::importTimesheet($file);
		$time = new G_Timesheet_Import_IM($file);
		$is_imported = $time->import();		
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
		$file = BASE_PATH . 'files/files/import_schedule_specific.xlsx';
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
}
?>