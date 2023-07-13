<?php
/* Usage
		$x = new Payslip_Percentage_Rate;
		$x->setNightShiftDiff(110); // 110%
		$x->setRegularOvertime(125); // 125%
		$x->setNightShiftOvertime(125);
		$x->setRestDay(120);
		$x->setRestDayOvertime(125);
		$x->setHolidaySpecial(130);
		$x->setHolidaySpecialOvertime(130);
		
		$x->setHolidayLegal(200);
		$x->setHolidaySpecialRestday(130);
		$x->setHolidayLegalRestday(200);
*/

class G_Attendance_Rate {
	protected $night_shift_diff;
	protected $regular_overtime;
	protected $night_shift_overtime;
	protected $holiday_special_night_shift_overtime;
	protected $holiday_special_restday_night_shift_overtime;
	protected $holiday_legal_restday_night_shift_overtime;
	protected $restday;
	protected $restday_overtime;
	protected $holiday_special;
	protected $holiday_special_overtime;
	protected $holiday_legal;
	protected $holiday_legal_overtime;	
	protected $holiday_special_restday;
	protected $holiday_special_restday_overtime;
	protected $holiday_legal_restday;
	protected $holiday_legal_restday_overtime;
	
	public function __construct() {
		
	}
	
	public function setNightShiftDiff($value) {
		$this->night_shift_diff = $value;
	}
	
	public function getNightShiftDiff() {
		return $this->night_shift_diff;
	}	
	
	public function setRegularOvertime($value) {
		$this->regular_overtime = $value;	
	}
	
	public function getRegularOvertime() {
		return $this->regular_overtime;	
	}
	
	public function setNightShiftOvertime($value) {
		$this->night_shift_overtime = $value;	
	}
	
	public function getNightShiftOvertime() {
		return $this->night_shift_overtime;	
	}

	public function setHolidaySpecialNightShiftOvertime($value){
		$this->holiday_special_night_shift_overtime =  $value;
	}

	public function getHolidaySpecialNightShiftOvertime(){
		return $this->holiday_special_night_shift_overtime;
	}

	public function setHolidaySpecialRestDayNightShiftOvertime($value){
		$this->holiday_special_restday_night_shift_overtime =  $value;
	}

	public function getHolidaySpecialRestDayNightShiftOvertime(){
		return $this->holiday_special_restday_night_shift_overtime;
	}

	public function setHolidayLegalRestDayNightShiftOvertime($value){
		$this->holiday_legal_restday_night_shift_overtime =  $value;
	}

	public function getHolidayLegalRestDayNightShiftOvertime(){
		return $this->holiday_legal_restday_night_shift_overtime;
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
	
	public function setHolidaySpecialRestday($value) {
		$this->holiday_special_restday = $value;	
	}
	
	public function getHolidaySpecialRestday() {
		return $this->holiday_special_restday;	
	}
	
	public function setHolidaySpecialRestdayOvertime($value) {
		$this->holiday_special_restday_overtime = $value;	
	}
	
	public function getHolidaySpecialRestdayOvertime() {
		return $this->holiday_special_restday_overtime;	
	}		
	
	public function setHolidayLegalRestday($value) {
		$this->holiday_legal_restday = $value;	
	}
	
	public function getHolidayLegalRestday() {
		return $this->holiday_legal_restday;	
	}
	
	public function setHolidayLegalRestdayOvertime($value) {
		$this->holiday_legal_restday_overtime = $value;	
	}
	
	public function getHolidayLegalRestdayOvertime() {
		return $this->holiday_legal_restday_overtime;	
	}		
}
?>