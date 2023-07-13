<?php
class Timesheet {
		
	public $time_in;
	public $time_out;
	protected $date_in;
	protected $date_out;
	protected $total_hours_worked;
	protected $scheduled_time_in;
	protected $scheduled_time_out;
	protected $night_shift_hours;
	protected $night_shift_overtime_hours;
//	protected $night_shift_hours_special;
//	protected $night_shift_hours_legal;
//	protected $holiday_hours_special;
//	protected $holiday_hours_legal;
	protected $overtime_hours;
	protected $late_hours;
	protected $undertime_hours;
	protected $overtime_in;
	protected $overtime_out;
	protected $early_overtime_in;
	protected $early_overtime_out;
		
	public function __construct() {
		
	}
	
	public function setTimeIn($value) {
		$this->time_in = $value;	
	}
	
	public function getTimeIn() {
		return $this->time_in;	
	}
	
	public function setTimeOut($value) {
		$this->time_out = $value;	
	}
	
	public function getTimeOut() {
		return $this->time_out;	
	}
	
	public function setDateIn($value) {
		$this->date_in = $value;	
	}
	
	public function getDateIn() {
		return $this->date_in;	
	}
	
	public function setDateOut($value) {
		$this->date_out = $value;	
	}
	
	public function getDateOut() {
		return $this->date_out;	
	}		
	
	public function setTotalHoursWorked($value) {
		$this->total_hours_worked = $value;	
	}
	
	public function getTotalHoursWorked() {
		return $this->total_hours_worked;	
	}
	
	public function setScheduledTimeIn($value) {
		$this->scheduled_time_in = $value;
	}
	
	public function getScheduledTimeIn() {
		return $this->scheduled_time_in;
	}
	
	public function setScheduledTimeOut($value) {
		$this->scheduled_time_out = $value;	
	}
	
	public function getScheduledTimeOut() {
		return $this->scheduled_time_out;
	}
	
	public function setNightShiftHours($value) {
		$this->night_shift_hours = $value;	
	}
	
	public function getNightShiftHours() {
		return $this->night_shift_hours;	
	}

    /*
    * Deprecated - Use setOvertimeNightShiftHours();
    */
	public function setNightShiftOvertimeHours($value) {
		$this->night_shift_overtime_hours = $value;
	}

    /*
    * Deprecated - Use getOvertimeNightShiftHours();
    */
	public function getNightShiftOvertimeHours() {
		return $this->night_shift_overtime_hours;
	}

	public function setOvertimeNightShiftHours($value) {
		$this->night_shift_overtime_hours = $value;
	}

	public function getOvertimeNightShiftHours() {
		return $this->night_shift_overtime_hours;
	}

	public function setNightShiftHoursSpecial($value) {
		$this->night_shift_hours_special = $value;
	}
	
	public function getNightShiftHoursSpecial() {
		return $this->night_shift_hours_special;
	}

	public function setNightShiftHoursLegal($value) {
		$this->night_shift_hours_legal = $value;
	}
	
	public function getNightShiftHoursLegal() {
		return $this->night_shift_hours_legal;
	}
	
	public function setHolidayHoursSpecial($value) {
		$this->holiday_hours_special = $value;
	}
	
	public function getHolidayHoursSpecial() {
		return $this->holiday_hours_special;
	}
	
	public function setHolidayHoursLegal($value) {
		$this->holiday_hours_legal = $value;
	}
	
	public function getHolidayHoursLegal() {
		return $this->holiday_hours_legal;
	}
	
	public function setOvertimeHours($value) {
		$this->overtime_hours = $value;	
	}
	
	public function getOvertimeHours() {
		return $this->overtime_hours;	
	}
	
	public function setLateHours($value) {
		$this->late_hours = $value;	
	}
	
	public function getLateHours() {
		return $this->late_hours;	
	}

    public function getLateMinutes() {
        return $this->late_hours * 60;
    }
	
	public function setUndertimeHours($value) {
		$this->undertime_hours = $value;	
	}
	
	public function getUndertimeHours() {
		return $this->undertime_hours;	
	}

    public function getUndertimeMinutes() {
        return $this->undertime_hours * 60;
    }
	
	public function setOverTimeIn($value) {
		$this->overtime_in = $value;
	}
	
	public function getOverTimeIn() {
		return $this->overtime_in;
	}
	
	public function setOverTimeOut($value) {
		$this->overtime_out = $value;
	}
	
	public function getOverTimeOut() {
		return $this->overtime_out;
	}
	
	public function setEarlyOverTimeIn($value) {
		$this->early_overtime_in = $value;
	}
	
	public function getEarlyOverTimeIn() {
		return $this->early_overtime_in;
	}	
	
	public function setEarlyOverTimeOut($value) {
		$this->early_overtime_out = $value;
	}
	
	public function getEarlyOverTimeOut() {
		return $this->early_overtime_out;
	}
}
?>