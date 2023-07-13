<?php
// This class can get hours like: overtime, undertime, etc...

/* Usage
		$p = new Payslip_Hour;		
		$p->setRegularNightShift(20);
		$p->setRegularUndertime(2);
		$p->setRegularOvertime(2);
		$p->setRegularLate(2);
		
		$p->setRestDay(8);
		$p->setRestDayOvertime(2);
		$p->setRestdayNightShift(9);
		
		$p->setHolidaySpecial(5);
		$p->setHolidaySpecialOvertime(2);
		$p->setHolidaySpecialNightShift(9);
		$p->setHolidaySpecialRestDay(2);
		$p->setHolidaySpecialRestDayOvertime(2);		
		$p->setHolidaySpecialRestdayNightShift(2);		
		
		$p->setHolidayLegal(2);
		$p->setHolidayLegalOvertime(2);
		$p->setHolidayLegalNightShift(9);
		$p->setHolidayLegalRestDay(2);
		$p->setHolidayLegalRestDayOvertime(2);
		$p->setHolidayLegalRestdayNightShift(2);
*/

class Payslip_Hour {
	protected $regular_late;
	protected $regular_undertime;
	
	protected $regular;
	protected $regular_nightshift;
	protected $nightshift_overtime;
	protected $nightshift_overtime_excess;
	protected $regular_overtime;
	protected $regular_overtime_excess;
		
	protected $restday;
	protected $restday_overtime;
	protected $restday_overtime_excess;
	protected $restday_nightshift;
	protected $restday_nightshift_overtime;
	protected $restday_nightshift_overtime_excess;
	
	protected $holiday_special;
	protected $holiday_special_overtime;
	protected $holiday_special_overtime_excess;
	protected $holiday_special_nightshift;
	protected $holiday_special_nightshift_overtime;
	protected $holiday_special_nightshift_overtime_excess;
	protected $holiday_special_restday;
	protected $holiday_special_restday_overtime;
	protected $holiday_special_restday_nightshift;
	
	protected $holiday_legal;
	protected $holiday_legal_overtime;
	protected $holiday_legal_overtime_excess;
	protected $holiday_legal_nightshift;
	protected $holiday_legal_nightshift_overtime;
	protected $holiday_legal_nightshift_overtime_excess;
	protected $holiday_legal_restday;
	protected $holiday_legal_restday_overtime;
	protected $holiday_legal_restday_nightshift;
	
	public function __construct() {
		
	}
	
	public function setRegularNightShift($value) {
		$this->regular_nightshift = $value;
	}
	
	public function getRegularNightShift() {
		return $this->regular_nightshift;
	}
	
	public function setNightShiftOvertime($value) {
		$this->nightshift_overtime = $value;
	}
	
	public function getNightShiftOvertime() {
		return $this->nightshift_overtime;
	}
	
	public function setNightShiftOvertimeExcess($value) {
		$this->nightshift_overtime_excess = $value;
	}
	
	public function getNightShiftOvertimeExcess() {
		return $this->nightshift_overtime_excess;
	}
	
	public function setHolidaySpecialNightShiftOvertime($value) {
		$this->holiday_special_nightshift_overtime = $value;
	}
	
	public function getHolidaySpecialNightShiftOvertime() {
		return $this->holiday_special_nightshift_overtime;
	}
	
	public function setHolidaySpecialNightShiftOvertimeExcess($value) {
		$this->holiday_special_nightshift_overtime_excess = $value;
	}
	
	public function getHolidaySpecialNightShiftOvertimeExcess() {
		return $this->holiday_special_nightshift_overtime_excess;
	}
	
	public function setHolidayLegalOvertimeExcess($value) {
		$this->holiday_legal_overtime_excess = $value;
	}
	
	public function getHolidayLegalOvertimeExcess() {
		return $this->holiday_legal_overtime_excess;
	}
	
	public function setHolidayLegalNightShiftOvertime($value) {
		$this->holiday_legal_nightshift_overtime = $value;
	}
	
	public function getHolidayLegalNightShiftOvertime() {
		return $this->holiday_legal_nightshift_overtime;
	}
	
	public function setHolidayLegalNightShiftOvertimeExcess($value) {
		$this->holiday_legal_nightshift_overtime_excess = $value;
	}
	
	public function getHolidayLegalNightShiftOvertimeExcess() {
		return $this->holiday_legal_nightshift_overtime_excess;
	}							
			
	public function setRegularUndertime($value) {
		$this->regular_undertime = $value;	
	}
	
	public function getRegularUndertime() {
		return $this->regular_undertime;	
	}
	
	public function setRegular($value) {
		$this->regular = $value;	
	}
	
	public function getRegular() {
		return $this->regular;	
	}			
	
	public function setRegularOvertime($value) {
		$this->regular_overtime = $value;	
	}
	
	public function getRegularOvertime() {
		return $this->regular_overtime;	
	}
	
	public function setRegularOvertimeExcess($value) {
		$this->regular_overtime_excess = $value;	
	}
	
	public function getRegularOvertimeExcess() {
		return $this->regular_overtime_excess;	
	}		
	
	public function setRegularLate($value) {
		$this->regular_late = $value;	
	}
	
	public function getRegularLate() {
		return $this->regular_late;	
	}	
	
	public function setRestDay($value) {
		$this->restday = $value;	
	}
	
	public function getRestDay() {
		return $this->restday;	
	}	
	
	public function setRestDayOvertime($value) {
		$this->restday_overtime = $value;	
	}
	
	public function getRestDayOvertime() {
		return $this->restday_overtime;	
	}
	
	public function setRestDayOvertimeExcess($value) {
		$this->restday_overtime_excess = $value;	
	}
	
	public function getRestDayOvertimeExcess() {
		return $this->restday_overtime_excess;	
	}	
			
	public function setRestdayNightShift($value) {
		$this->restday_nightshift = $value;	
	}
	
	public function getRestdayNightShift() {
		return $this->restday_nightshift;	
	}
	
	public function setRestDayNightShiftOvertime($value) {
		$this->restday_nightshift_overtime = $value;	
	}
	
	public function getRestDayNightShiftOvertime() {
		return $this->restday_nightshift_overtime;	
	}
	
	public function setRestDayNightShiftOvertimeExcess($value) {
		$this->restday_nightshift_overtime_excess = $value;	
	}
	
	public function getRestDayNightShiftOvertimeExcess() {
		return $this->restday_nightshift_overtime_excess;	
	}			
			
	public function setHolidaySpecial($value) {
		$this->holiday_special = $value;	
	}
	
	public function getHolidaySpecial() {
		return $this->holiday_special;	
	}	
	
	public function setHolidaySpecialOvertime($value) {
		$this->holiday_special_overtime = $value;	
	}
	
	public function getHolidaySpecialOvertime() {
		return $this->holiday_special_overtime;	
	}
	
	public function setHolidaySpecialOvertimeExcess($value) {
		$this->holiday_special_overtime_excess = $value;	
	}
	
	public function getHolidaySpecialOvertimeExcess() {
		return $this->holiday_special_overtime_excess;	
	}		
	
	public function setHolidaySpecialNightShift($value) {
		$this->holiday_special_nightshift = $value;	
	}
	
	public function getHolidaySpecialNightShift() {
		return $this->holiday_special_nightshift;	
	}		
	
	public function setHolidaySpecialRestDay($value) {
		$this->holiday_special_restday = $value;	
	}
	
	public function getHolidaySpecialRestDay() {
		return $this->holiday_special_restday;	
	}	
	
	public function setHolidaySpecialRestDayOvertime($value) {
		$this->holiday_special_restday_overtime = $value;	
	}
	
	public function getHolidaySpecialRestDayOvertime() {
		return $this->holiday_special_restday_overtime;	
	}	
	
	public function setHolidaySpecialRestdayNightShift($value) {
		$this->holiday_special_restday_nightshift = $value;
	}
	
	public function getHolidaySpecialRestdayNightShift() {
		return $this->holiday_special_restday_nightshift;
	}	
	
	public function setHolidayLegal($value) {
		$this->holiday_legal = $value;	
	}
	
	public function getHolidayLegal() {
		return $this->holiday_legal;	
	}	
	
	public function setHolidayLegalOvertime($value) {
		$this->holiday_legal_overtime = $value;	
	}
	
	public function getHolidayLegalOvertime() {
		return $this->holiday_legal_overtime;	
	}	
	
	public function setHolidayLegalNightShift($value) {
		$this->holiday_legal_nightshift = $value;	
	}
	
	public function getHolidayLegalNightShift() {
		return $this->holiday_legal_nightshift;	
	}		
	
	public function setHolidayLegalRestDay($value) {
		$this->holiday_legal_restday = $value;	
	}
	
	public function getHolidayLegalRestDay() {
		return $this->holiday_legal_restday;	
	}	
	
	public function setHolidayLegalRestDayOvertime($value) {
		$this->holiday_legal_restday_overtime = $value;	
	}
	
	public function getHolidayLegalRestDayOvertime() {
		return $this->holiday_legal_restday_overtime;	
	}	
	
	public function setHolidayLegalRestdayNightShift($value) {
		$this->holiday_legal_restday_nightshift = $value;
	}
	
	public function getHolidayLegalRestdayNightShift() {
		return $this->holiday_legal_restday_nightshift;
	}
}
?>