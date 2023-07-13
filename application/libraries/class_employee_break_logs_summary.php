<?php
class Employee_Break_Logs_Summary {
	
	protected $id;
	protected $attendance_date;
	protected $employee_attendance_id = 0;
	protected $employee_id = 0;
	protected $schedule_id = 0;
	protected $required_log_break1 = 1;
	protected $log_break1_in_id = 0;
	protected $log_break1_in;
	protected $log_break1_out_id = 0;
	protected $log_break1_out;
	protected $required_log_break2 = 1;
	protected $log_break2_in_id = 0;
	protected $log_break2_in;
	protected $log_break2_out_id = 0;
	protected $log_break2_out;
	protected $required_log_break3 = 1;
	protected $log_break3_in_id = 0;
	protected $log_break3_in;
	protected $log_break3_out_id = 0;
	protected $log_break3_out;
	protected $log_ot_break1_in_id = 0;
	protected $log_ot_break1_in;
	protected $log_ot_break1_out_id = 0;
	protected $log_ot_break1_out;
	protected $log_ot_break2_in_id = 0;
	protected $log_ot_break2_in;
	protected $log_ot_break2_out_id = 0;
	protected $log_ot_break2_out;
	protected $total_break_hrs = 0;
	protected $has_early_break_out = false;
	protected $total_early_break_out_hrs = 0;
	protected $has_late_break_in = false;
	protected $total_late_break_in_hrs = 0;
	protected $has_incomplete_break_logs = false;
	protected $created_at;
		
	public function __construct() {
		$this->created_at = date('Y-m-d H:i:s');
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}	
	
	public function setAttendanceDate($value) {
		$this->attendance_date = $value;	
	}
	
	public function getAttendanceDate() {
		return $this->attendance_date;	
	}	
	
	public function setEmployeeAttendanceId($value) {
		$this->employee_attendance_id = $value;	
	}
	
	public function getEmployeeAttendanceId() {
		return $this->employee_attendance_id;	
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;	
	}
	
	public function setScheduleId($value) {
		$this->schedule_id = $value;	
	}
	
	public function getScheduleId() {
		return $this->schedule_id;	
	}
	
	public function setRequiredLogBreak1($value) {
		$this->required_log_break1 = $value;	
	}
	
	public function getRequiredLogBreak1() {
		return $this->required_log_break1;	
	}
	
	public function setLogBreak1OutId($value) {
		$this->log_break1_out_id = $value;	
	}
	
	public function getLogBreak1OutId() {
		return $this->log_break1_out_id;	
	}
	
	public function setLogBreak1Out($value) {
		$this->log_break1_out = $value;	
	}
	
	public function getLogBreak1Out() {
		return $this->log_break1_out;	
	}
	
	public function setLogBreak1InId($value) {
		$this->log_break1_in_id = $value;	
	}
	
	public function getLogBreak1InId() {
		return $this->log_break1_in_id;	
	}
	
	public function setLogBreak1In($value) {
		$this->log_break1_in = $value;	
	}
	
	public function getLogBreak1In() {
		return $this->log_break1_in;	
	}
	
	public function setRequiredLogBreak2($value) {
		$this->required_log_break2 = $value;	
	}
	
	public function getRequiredLogBreak2() {
		return $this->required_log_break2;	
	}
	
	public function setLogBreak2OutId($value) {
		$this->log_break2_out_id = $value;	
	}
	
	public function getLogBreak2OutId() {
		return $this->log_break2_out_id;	
	}
	
	public function setLogBreak2Out($value) {
		$this->log_break2_out = $value;	
	}
	
	public function getLogBreak2Out() {
		return $this->log_break2_out;	
	}
	
	public function setLogBreak2InId($value) {
		$this->log_break2_in_id = $value;	
	}
	
	public function getLogBreak2InId() {
		return $this->log_break2_in_id;	
	}
	
	public function setLogBreak2In($value) {
		$this->log_break2_in = $value;	
	}
	
	public function getLogBreak2In() {
		return $this->log_break2_in;	
	}
	
	public function setRequiredLogBreak3($value) {
		$this->required_log_break3 = $value;	
	}
	
	public function getRequiredLogBreak3() {
		return $this->required_log_break3;	
	}
	
	public function setLogBreak3OutId($value) {
		$this->log_break3_out_id = $value;	
	}
	
	public function getLogBreak3OutId() {
		return $this->log_break3_out_id;	
	}
	
	public function setLogBreak3Out($value) {
		$this->log_break3_out = $value;	
	}
	
	public function getLogBreak3Out() {
		return $this->log_break3_out;	
	}
	
	public function setLogBreak3InId($value) {
		$this->log_break3_in_id = $value;	
	}
	
	public function getLogBreak3InId() {
		return $this->log_break3_in_id;	
	}
	
	public function setLogBreak3In($value) {
		$this->log_break3_in = $value;	
	}
	
	public function getLogBreak3In() {
		return $this->log_break3_in;	
	}
	
	public function setLogOtBreak1OutId($value) {
		$this->log_ot_break1_out_id = $value;	
	}
	
	public function getLogOtBreak1OutId() {
		return $this->log_ot_break1_out_id;	
	}
	
	public function setLogOtBreak1Out($value) {
		$this->log_ot_break1_out = $value;	
	}
	
	public function getLogOtBreak1Out() {
		return $this->log_ot_break1_out;	
	}
	
	public function setLogOtBreak1InId($value) {
		$this->log_ot_break1_in_id = $value;	
	}
	
	public function getLogOtBreak1InId() {
		return $this->log_ot_break1_in_id;	
	}
	
	public function setLogOtBreak1In($value) {
		$this->log_ot_break1_in = $value;	
	}
	
	public function getLogOtBreak1In() {
		return $this->log_ot_break1_in;	
	}
	
	public function setLogOtBreak2OutId($value) {
		$this->log_ot_break2_out_id = $value;	
	}
	
	public function getLogOtBreak2OutId() {
		return $this->log_ot_break2_out_id;	
	}
	
	public function setLogOtBreak2Out($value) {
		$this->log_ot_break2_out = $value;	
	}
	
	public function getLogOtBreak2Out() {
		return $this->log_ot_break2_out;	
	}
	
	public function setLogOtBreak2InId($value) {
		$this->log_ot_break2_in_id = $value;	
	}
	
	public function getLogOtBreak2InId() {
		return $this->log_ot_break2_in_id;	
	}
	
	public function setLogOtBreak2In($value) {
		$this->log_ot_break2_in = $value;	
	}
	
	public function getLogOtBreak2In() {
		return $this->log_ot_break2_in;	
	}
	
	public function setTotalBreakHrs($value) {
		$this->total_break_hrs = $value;	
	}
	
	public function getTotalBreakHrs() {
		return $this->total_break_hrs;	
	}
	
	public function setHasEarlyBreakOut($value) {
		$this->has_early_break_out = $value;	
	}
	
	public function getHasEarlyBreakOut() {
		return $this->has_early_break_out;	
	}
	
	public function setTotalEarlyBreakOutHrs($value) {
		$this->total_early_break_out_hrs = $value;	
	}
	
	public function getTotalEarlyBreakOutHrs() {
		return $this->total_early_break_out_hrs;	
	}
	
	public function setHasLateBreakIn($value) {
		$this->has_late_break_in = $value;	
	}
	
	public function getHasLateBreakIn() {
		return $this->has_late_break_in;	
	}
	
	public function setTotalLateBreakInHrs($value) {
		$this->total_late_break_in_hrs = $value;	
	}
	
	public function getTotalLateBreakInHrs() {
		return $this->total_late_break_in_hrs;	
	}
	
	public function setCreatedAt($value) {
		$this->created_at = $value;	
	}
	
	public function getCreatedAt() {
		return $this->created_at;	
	}
	
	public function setHasIncompleteBreakLogs($value) {
		$this->has_incomplete_break_logs = $value;	
	}
	
	public function getHasIncompleteBreakLogs() {
		return $this->has_incomplete_break_logs;	
	}
}
?>