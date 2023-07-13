<?php
class G_Employee_Request extends Employee_Request {	
	
	//Objects
	protected $gsr;
	protected $ge;
	protected $gelr;
	
	const ACTIVE   = 1;
	const INACTIVE = 0;
	
	const ARCHIVE = 1;
		
	public function __construct() {
		
	}
	
	public function save(G_Settings_Request $gsr, G_Employee $ge) {
		return G_Employee_Request_Manager::save($this, $gsr, $ge);
	}
	
	public function delete() {
		return G_Employee_Request_Manager::delete($this);
	}
	
	public function save_leave_request(G_Settings_Request $gsr, G_Employee $ge, G_Employee_Leave_Request $gelr) {
		return G_Employee_Request_Manager::save_leave_request($this, $gsr, $ge, $gelr);
	}
	
	public function save_overtime_request(G_Settings_Request $gsr, G_Employee $ge, G_Employee_Overtime_Request $gelr) {
		return G_Employee_Request_Manager::save_leave_request($this, $gsr, $ge, $gelr);
	}
	
	public function save_rest_day_request(G_Settings_Request $gsr, G_Employee $ge, G_Employee_Rest_Day_Request $gelr) {
		return G_Employee_Request_Manager::save_rest_day_request($this, $gsr, $ge, $gelr);
	}
	
	public function save_change_schedule_request(G_Settings_Request $gsr, G_Employee $ge, G_Employee_Change_Schedule_Request $gelr) {
		return G_Employee_Request_Manager::save_change_schedule_request($this, $gsr, $ge, $gelr);
	}
	
	public function update() {
		return G_Employee_Request_Manager::update($this);
	}
}
?>