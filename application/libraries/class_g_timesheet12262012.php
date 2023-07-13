<?php
class G_Timesheet extends Timesheet {
	protected $nightshift_overtime_excess_hours;
	protected $overtime_excess_hours;
	protected $restday_overtime_hours;
			
	public function __construct() {
	}
	
	public function setNightShiftOvertimeExcessHours($value) {
		$this->nightshift_overtime_excess_hours = $value;	
	}
	
	public function getNightShiftOvertimeExcessHours() {
		return $this->nightshift_overtime_excess_hours;	
	}
	
	public function setOvertimeExcessHours($value) {
		$this->overtime_excess_hours = $value;	
	}
	
	public function getRestDayOvertimeHours() {
		return $this->restday_overtime_hours;	
	}
	
	public function getOvertimeExcessHours() {
		return $this->overtime_excess_hours;	
	}
	
	public function computeTotalScheduledHours() {
		//return Tools::computeHoursDifference($this->scheduled_time_in, $this->scheduled_time_out);	
		return Tools::getHoursDifference($this->scheduled_time_in, $this->scheduled_time_out);	
	}
	
	public function computeTotalActualHours() {
		//return Tools::computeHoursDifference($this->getTimeIn(), $this->getTimeOut());	
		return Tools::getHoursDifference($this->getTimeIn(), $this->getTimeOut());
	}	
}
?>