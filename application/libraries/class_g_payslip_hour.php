<?php
class G_Payslip_Hour extends Payslip_Hour {
	protected $present_days;
	protected $present_days_with_pay;
	protected $absent_days;
	protected $absent_days_with_pay;
	protected $absent_days_without_pay;
	protected $total_hours_worked;
	
	public function __construct() {
		
	}
	
	public function computeLateMinutes() {
		$hour = $this->getRegularLate();
		$minutes = $hour * 60;
		return number_format($minutes, 2);
	}
	
	public function computeUndertimeMinutes() {
		$hour = $this->getRegularUndertime();
		$minutes = $hour * 60;
		return number_format($minutes, 2);
	}	
	
	public function getTotalHoursWorked() {
		return $this->total_hours_worked;	
	}
	
	public function setPresentDays($value) {
		$this->present_days = $value;
	}	
	
	public function getPresentDays() {
		return $this->present_days;
	}
	
	public function setPresentDaysWithPay($value) {
		$this->present_days_with_pay = $value;
	}	
	
	public function getPresentDaysWithPay() {
		return $this->present_days_with_pay;
	}
	
	public function setAbsentDays($value) {
		$this->absent_days = $value;
	}	
	
	public function getAbsentDays() {
		return $this->absent_days;
	}	
	
	public function setAbsentDaysWithPay($value) {
		$this->absent_days_with_pay = $value;
	}	
	
	public function getAbsentDaysWithPay() {
		return $this->absent_days_with_pay;
	}
	
	public function setAbsentDaysWithoutPay($value) {
		$this->absent_days_without_pay = $value;
	}
	
	public function getAbsentDaysWithoutPay() {
		return $this->absent_days_without_pay;
	}	
	
	public function getTotalNightShift() {
		return $this->getRegularNightShift() + $this->getRestdayNightShift() + $this->getHolidaySpecialNightShift() + $this->getHolidaySpecialRestdayNightShift() + $this->getHolidayLegalNightShift() + $this->getHolidayLegalRestdayNightShift();
	}
	
	public function computeTotalHoursWorked() {
		return $this->getRegular();
	}
}
?>