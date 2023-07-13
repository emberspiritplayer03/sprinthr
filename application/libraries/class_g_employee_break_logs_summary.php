<?php
class G_Employee_Break_Logs_Summary extends Employee_Break_Logs_Summary {
		
	public function __construct() {
		
	}
	
	public function employee_attendance() {
		if ($this->getEmployeeAttendanceId() === 0) {
			return false;	
		}

		return G_Attendance_Finder::findById($this->getEmployeeAttendanceId());
	}
	
	public function employee() {
		if ($this->getEmployeeId() === 0) {
			return false;	
		}

		return G_Employee_Finder::findById($this->getEmployeeId());
	}
	
	public function schedule() {
		if ($this->getScheduleId() === 0) {
			return false;	
		}

		return G_Schedule_Finder::findById($this->getScheduleId());
	}
	
	public function save() {
		return G_Employee_Break_Logs_Summary_Manager::save($this);
	}

	public function delete() {
		$deleted_records = 0;

		if( $this->id > 0 ){
			$total_deleted = G_Employee_Break_Logs_Summary_Manager::delete($this);
			$deleted_records = $total_deleted;
		}

		return $deleted_records;
	}
}
?>