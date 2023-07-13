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
		$date = $this->c_date;					
		$a = new G_Attendance();		
		$return = $a->syncFpLogsToattendance($this->c_date);		
	}

	function ajax_sync_attendance()
	{
		$date = $this->c_date;					
		$a = new G_Attendance();		
		$return['is_success'] = $a->syncFpLogsToattendance($this->c_date);	
		$return['message']    = "Data Synchronizing completed";
		echo json_encode($return);
	}

	function ajax_sync_attendance_with_date_range()
	{
		$data = $_POST;	
		$from = $data['date_from'];
		$to   = $data['date_to'];					
		$a = new G_Attendance();		
		$return['is_success'] = $a->syncFpLogsToattendanceWithDateRange($from, $to);	
		$return['message']    = "Data Synchronizing completed";
		echo json_encode($return);

		//General Reports / Shr Audit Trail
        $this->triggerShrAuditTrail($this->global_user_username, $this->global_user_position, 'TIMEKEEPING', ACTION_SYNC, 'Attendance Records of ', '', $from, $to, 1, '', '');
	}
}
?>