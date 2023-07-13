<?php
class Attendance_Sync_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		$this->c_date = Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
	}

	function _sync_employee_attendance()
	{
		//$date = $this->c_date;		
		$date = date("Y-m-d");		
		$a = new G_Attendance();		
		$return = $a->syncFpLogsToattendance($date);		
	}
}
?>