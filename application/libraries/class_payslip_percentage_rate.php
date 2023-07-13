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
			// $x = new Payslip_Percentage_Rate;
			// $x->setHolidaySpecialNightShiftOvertime(0.169);

class Payslip_Percentage_Rate {
	// protected $night_shift_diff;
	// protected $regular_overtime;
	// protected $night_shift_overtime;
	// protected $holiday_legal_nightshift;
	// protected $holiday_special_night_shift_overtime;
	// protected $holiday_special_restday_night_shift_overtime;
	// protected $holiday_legal_restday_night_shift_overtime;
	// protected $holiday_legal_nightshift_overtime;
	// protected $restday;
	// protected $restday_overtime;
	// protected $holiday_special;
	// protected $holiday_special_overtime;
	// protected $holiday_legal;
	// protected $holiday_legal_overtime;	
	// protected $holiday_special_restday;
	// protected $holiday_special_restday_overtime;
	// protected $holiday_legal_restday;
	// protected $holiday_legal_restday_overtime;
	// protected $regular_overtime_nightshift_differential;
	
	// public function __construct() {
		
	// }
	
	// public function setNightShiftDiff($value) {
	// 	$this->night_shift_diff = $value;
	// }
	
	// public function getNightShiftDiff() {
	// 	return $this->night_shift_diff;
	// }	
	
	// public function setRegularOvertime($value) {
	// 	$this->regular_overtime = $value;	
	// }
	
	// public function getRegularOvertime() {
	// 	return $this->regular_overtime;	
	// }
	
	// public function setNightShiftOvertime($value) {
	// 	$this->night_shift_overtime = $value;	
	// }

	// public function getNightShiftOvertime() {
	// 	return $this->night_shift_overtime;	
	// }

	// public function setHolidayLegalNightShift($value){
	// 	 $this->holiday_legal_nightshift = $value;
	// }

	// public function getHolidayLegalNightShift(){
	// 	return $this->holiday_legal_nightshift;
	// }


	// public function setHolidaySpecialNightShiftOvertime($value){
	// 	 $this->holiday_special_night_shift_overtime = $value;
	// }

	// public function getHolidaySpecialNightShiftOvertime(){
	// 	return $this->holiday_special_night_shift_overtime;
	// }

	// public function setHolidaySpecialRestDayNightShiftOvertime($value){
	// 	$this->holiday_special_restday_night_shift_overtime =  $value;
	// }

	// public function getHolidaySpecialRestDayNightShiftOvertime(){
	// 	return $this->holiday_special_restday_night_shift_overtime;
	// }

	// 	public function setHolidayLegalRestDayNightShiftOvertime($value){
	// 	$this->holiday_legal_restday_night_shift_overtime =  $value;
	// }

	// public function getHolidayLegalRestDayNightShiftOvertime(){
	// 	return $this->holiday_legal_restday_night_shift_overtime;
	// }

	// 		public function setHolidayLegalNightShiftOvertime($value){
	// 	$this->holiday_legal_nightshift_overtime =  $value;
	// }

	// public function getHolidayLegalNightShiftOvertime(){
	// 	return $this->holiday_legal_nightshift_overtime;
	// }

	// public function setRestDay($value) {
	// 	$this->restday = $value;	
	// }
	
	// public function getRestDay() {
	// 	return $this->restday;	
	// }	
	
	// public function setRestDayOvertime($value) {
	// 	$this->restday_overtime = $value;	
	// }
	
	// public function getRestDayOvertime() {
	// 	return $this->restday_overtime;	
	// }	
	
	// public function setHolidaySpecial($value) {
	// 	$this->holiday_special = $value;	
	// }
	
	// public function getHolidaySpecial() {
	// 	return $this->holiday_special;	
	// }
	
	// public function setHolidaySpecialOvertime($value) {
	// 	$this->holiday_special_overtime = $value;	
	// }
	
	// public function getHolidaySpecialOvertime() {
	// 	return $this->holiday_special_overtime;	
	// }		
	
	// public function setHolidayLegal($value) {
	// 	$this->holiday_legal = $value;	
	// }
	
	// public function getHolidayLegal() {
	// 	return $this->holiday_legal;	
	// }
	
	// public function setHolidayLegalOvertime($value) {
	// 	$this->holiday_legal_overtime = $value;	
	// }
	
	// public function getHolidayLegalOvertime() {
	// 	return $this->holiday_legal_overtime;	
	// }		
	
	// public function setHolidaySpecialRestday($value) {
	// 	$this->holiday_special_restday = $value;	
	// }
	
	// public function getHolidaySpecialRestday() {
	// 	return $this->holiday_special_restday;	
	// }
	
	// public function setHolidaySpecialRestdayOvertime($value) {
	// 	$this->holiday_special_restday_overtime = $value;	
	// }
	
	// public function getHolidaySpecialRestdayOvertime() {
	// 	return $this->holiday_special_restday_overtime;	
	// }		
	
	// public function setHolidayLegalRestday($value) {
	// 	$this->holiday_legal_restday = $value;	
	// }
	
	// public function getHolidayLegalRestday() {
	// 	return $this->holiday_legal_restday;	
	// }
	
	// public function setHolidayLegalRestdayOvertime($value) {
	// 	$this->holiday_legal_restday_overtime = $value;	
	// }
	
	// public function getHolidayLegalRestdayOvertime() {
	// 	return $this->holiday_legal_restday_overtime;	
	// }	

	// public function setRegularOvertimeNightShiftDifferential($value) {
	// 	$this->regular_overtime_nightshift_differential = $value;
	// }	

	// public function getRegularOvertimeNightShiftDifferential() {
	// 	return $this->regular_overtime_nightshift_differential;
	// }

	protected $regular;
	protected $restDay;
	protected $holidaySpecial;
	protected $holidaySpecialRestday;
	protected $holidayLegal;
	protected $holidayLegalRestday;
	protected $holidayDouble;
	protected $holidayDoubleRestday;
	protected $regularOvertime;
	protected $restDayOvertime;
	protected $holidaySpecialOvertime;
	protected $holidaySpecialRestdayOvertime;
	protected $holidayLegalOvertime;
	protected $holidayLegalRestdayOvertime;
	protected $holidayDoubleOvertime;
	protected $holidayDoubleRestdayOvertime;
	protected $regularNightDifferential;
	protected $restDayNightDifferential;
	protected $holidaySpecialNightDifferential;
	protected $holidaySpecialRestdayNightDifferential;
	protected $holidayLegalNightDifferential;
	protected $holidayLegalRestdayNightDifferential;
	protected $holidayDoubleNightDifferential;
	protected $holidayDoubleRestdayNightDifferential;
	protected $regularNightDifferentialOvertime;
	protected $restDayNightDifferentialOvertime;
	protected $holidaySpecialNightDifferentialOvertime;
	protected $holidaySpecialRestdayNightDifferentialOvertime;
	protected $holidayLegalNightDifferentialOvertime;
	protected $holidayLegalRestdayNightDifferentialOvertime;
	protected $holidayDoubleNightDifferentialOvertime;
	protected $holidayDoubleRestdayNightDifferentialOvertime;



	/**
	 * Get the value of regular
	 */ 
	public function getRegular()
	{
		return $this->regular;
	}

	/**
	 * Set the value of regular
	 *
	 * @return  self
	 */ 
	public function setRegular($regular)
	{
		$this->regular = $regular;

		return $this;
	}

	/**
	 * Get the value of restDay
	 */ 
	public function getRestDay()
	{
		return $this->restDay;
	}

	/**
	 * Set the value of restDay
	 *
	 * @return  self
	 */ 
	public function setRestDay($restDay)
	{
		$this->restDay = $restDay;

		return $this;
	}

	/**
	 * Get the value of holidaySpecial
	 */ 
	public function getHolidaySpecial()
	{
		return $this->holidaySpecial;
	}

	/**
	 * Set the value of holidaySpecial
	 *
	 * @return  self
	 */ 
	public function setHolidaySpecial($holidaySpecial)
	{
		$this->holidaySpecial = $holidaySpecial;

		return $this;
	}

	/**
	 * Get the value of holidaySpecialRestday
	 */ 
	public function getHolidaySpecialRestday()
	{
		return $this->holidaySpecialRestday;
	}

	/**
	 * Set the value of holidaySpecialRestday
	 *
	 * @return  self
	 */ 
	public function setHolidaySpecialRestday($holidaySpecialRestday)
	{
		$this->holidaySpecialRestday = $holidaySpecialRestday;

		return $this;
	}

	/**
	 * Get the value of holidayLegal
	 */ 
	public function getHolidayLegal()
	{
		return $this->holidayLegal;
	}

	/**
	 * Set the value of holidayLegal
	 *
	 * @return  self
	 */ 
	public function setHolidayLegal($holidayLegal)
	{
		$this->holidayLegal = $holidayLegal;

		return $this;
	}

	/**
	 * Get the value of holidayLegalRestday
	 */ 
	public function getHolidayLegalRestday()
	{
		return $this->holidayLegalRestday;
	}

	/**
	 * Set the value of holidayLegalRestday
	 *
	 * @return  self
	 */ 
	public function setHolidayLegalRestday($holidayLegalRestday)
	{
		$this->holidayLegalRestday = $holidayLegalRestday;

		return $this;
	}

	/**
	 * Get the value of holidayDouble
	 */ 
	public function getHolidayDouble()
	{
		return $this->holidayDouble;
	}

	/**
	 * Set the value of holidayDouble
	 *
	 * @return  self
	 */ 
	public function setHolidayDouble($holidayDouble)
	{
		$this->holidayDouble = $holidayDouble;

		return $this;
	}

	/**
	 * Get the value of holidayDoubleRestday
	 */ 
	public function getHolidayDoubleRestday()
	{
		return $this->holidayDoubleRestday;
	}

	/**
	 * Set the value of holidayDoubleRestday
	 *
	 * @return  self
	 */ 
	public function setHolidayDoubleRestday($holidayDoubleRestday)
	{
		$this->holidayDoubleRestday = $holidayDoubleRestday;

		return $this;
	}

	/**
	 * Get the value of regularOvertime
	 */ 
	public function getRegularOvertime()
	{
		return $this->regularOvertime;
	}

	/**
	 * Set the value of regularOvertime
	 *
	 * @return  self
	 */ 
	public function setRegularOvertime($regularOvertime)
	{
		$this->regularOvertime = $regularOvertime;

		return $this;
	}

	/**
	 * Get the value of restDayOvertime
	 */ 
	public function getRestDayOvertime()
	{
		return $this->restDayOvertime;
	}

	/**
	 * Set the value of restDayOvertime
	 *
	 * @return  self
	 */ 
	public function setRestDayOvertime($restDayOvertime)
	{
		$this->restDayOvertime = $restDayOvertime;

		return $this;
	}

	/**
	 * Get the value of holidaySpecialOvertime
	 */ 
	public function getHolidaySpecialOvertime()
	{
		return $this->holidaySpecialOvertime;
	}

	/**
	 * Set the value of holidaySpecialOvertime
	 *
	 * @return  self
	 */ 
	public function setHolidaySpecialOvertime($holidaySpecialOvertime)
	{
		$this->holidaySpecialOvertime = $holidaySpecialOvertime;

		return $this;
	}

	/**
	 * Get the value of holidaySpecialRestdayOvertime
	 */ 
	public function getHolidaySpecialRestdayOvertime()
	{
		return $this->holidaySpecialRestdayOvertime;
	}

	/**
	 * Set the value of holidaySpecialRestdayOvertime
	 *
	 * @return  self
	 */ 
	public function setHolidaySpecialRestdayOvertime($holidaySpecialRestdayOvertime)
	{
		$this->holidaySpecialRestdayOvertime = $holidaySpecialRestdayOvertime;

		return $this;
	}

	/**
	 * Get the value of holidayLegalOvertime
	 */ 
	public function getHolidayLegalOvertime()
	{
		return $this->holidayLegalOvertime;
	}

	/**
	 * Set the value of holidayLegalOvertime
	 *
	 * @return  self
	 */ 
	public function setHolidayLegalOvertime($holidayLegalOvertime)
	{
		$this->holidayLegalOvertime = $holidayLegalOvertime;

		return $this;
	}

	/**
	 * Get the value of holidayLegalRestdayOvertime
	 */ 
	public function getHolidayLegalRestdayOvertime()
	{
		return $this->holidayLegalRestdayOvertime;
	}

	/**
	 * Set the value of holidayLegalRestdayOvertime
	 *
	 * @return  self
	 */ 
	public function setHolidayLegalRestdayOvertime($holidayLegalRestdayOvertime)
	{
		$this->holidayLegalRestdayOvertime = $holidayLegalRestdayOvertime;

		return $this;
	}

	/**
	 * Get the value of holidayDoubleOvertime
	 */ 
	public function getHolidayDoubleOvertime()
	{
		return $this->holidayDoubleOvertime;
	}

	/**
	 * Set the value of holidayDoubleOvertime
	 *
	 * @return  self
	 */ 
	public function setHolidayDoubleOvertime($holidayDoubleOvertime)
	{
		$this->holidayDoubleOvertime = $holidayDoubleOvertime;

		return $this;
	}

	/**
	 * Get the value of holidayDoubleRestdayOvertime
	 */ 
	public function getHolidayDoubleRestdayOvertime()
	{
		return $this->holidayDoubleRestdayOvertime;
	}

	/**
	 * Set the value of holidayDoubleRestdayOvertime
	 *
	 * @return  self
	 */ 
	public function setHolidayDoubleRestdayOvertime($holidayDoubleRestdayOvertime)
	{
		$this->holidayDoubleRestdayOvertime = $holidayDoubleRestdayOvertime;

		return $this;
	}

	/**
	 * Get the value of regularNightDifferential
	 */ 
	public function getRegularNightDifferential()
	{
		return $this->regularNightDifferential;
	}

	/**
	 * Set the value of regularNightDifferential
	 *
	 * @return  self
	 */ 
	public function setRegularNightDifferential($regularNightDifferential)
	{
		$this->regularNightDifferential = $regularNightDifferential;

		return $this;
	}

	/**
	 * Get the value of restDayNightDifferential
	 */ 
	public function getRestDayNightDifferential()
	{
		return $this->restDayNightDifferential;
	}

	/**
	 * Set the value of restDayNightDifferential
	 *
	 * @return  self
	 */ 
	public function setRestDayNightDifferential($restDayNightDifferential)
	{
		$this->restDayNightDifferential = $restDayNightDifferential;

		return $this;
	}

	/**
	 * Get the value of holidaySpecialNightDifferential
	 */ 
	public function getHolidaySpecialNightDifferential()
	{
		return $this->holidaySpecialNightDifferential;
	}

	/**
	 * Set the value of holidaySpecialNightDifferential
	 *
	 * @return  self
	 */ 
	public function setHolidaySpecialNightDifferential($holidaySpecialNightDifferential)
	{
		$this->holidaySpecialNightDifferential = $holidaySpecialNightDifferential;

		return $this;
	}

	/**
	 * Get the value of holidaySpecialRestdayNightDifferential
	 */ 
	public function getHolidaySpecialRestdayNightDifferential()
	{
		return $this->holidaySpecialRestdayNightDifferential;
	}

	/**
	 * Set the value of holidaySpecialRestdayNightDifferential
	 *
	 * @return  self
	 */ 
	public function setHolidaySpecialRestdayNightDifferential($holidaySpecialRestdayNightDifferential)
	{
		$this->holidaySpecialRestdayNightDifferential = $holidaySpecialRestdayNightDifferential;

		return $this;
	}

	/**
	 * Get the value of holidayLegalNightDifferential
	 */ 
	public function getHolidayLegalNightDifferential()
	{
		return $this->holidayLegalNightDifferential;
	}

	/**
	 * Set the value of holidayLegalNightDifferential
	 *
	 * @return  self
	 */ 
	public function setHolidayLegalNightDifferential($holidayLegalNightDifferential)
	{
		$this->holidayLegalNightDifferential = $holidayLegalNightDifferential;

		return $this;
	}

	/**
	 * Get the value of holidayLegalRestdayNightDifferential
	 */ 
	public function getHolidayLegalRestdayNightDifferential()
	{
		return $this->holidayLegalRestdayNightDifferential;
	}

	/**
	 * Set the value of holidayLegalRestdayNightDifferential
	 *
	 * @return  self
	 */ 
	public function setHolidayLegalRestdayNightDifferential($holidayLegalRestdayNightDifferential)
	{
		$this->holidayLegalRestdayNightDifferential = $holidayLegalRestdayNightDifferential;

		return $this;
	}

	/**
	 * Get the value of holidayDoubleNightDifferential
	 */ 
	public function getHolidayDoubleNightDifferential()
	{
		return $this->holidayDoubleNightDifferential;
	}

	/**
	 * Set the value of holidayDoubleNightDifferential
	 *
	 * @return  self
	 */ 
	public function setHolidayDoubleNightDifferential($holidayDoubleNightDifferential)
	{
		$this->holidayDoubleNightDifferential = $holidayDoubleNightDifferential;

		return $this;
	}

	/**
	 * Get the value of holidayDoubleRestdayNightDifferential
	 */ 
	public function getHolidayDoubleRestdayNightDifferential()
	{
		return $this->holidayDoubleRestdayNightDifferential;
	}

	/**
	 * Set the value of holidayDoubleRestdayNightDifferential
	 *
	 * @return  self
	 */ 
	public function setHolidayDoubleRestdayNightDifferential($holidayDoubleRestdayNightDifferential)
	{
		$this->holidayDoubleRestdayNightDifferential = $holidayDoubleRestdayNightDifferential;

		return $this;
	}

	/**
	 * Get the value of regularNightDifferentialOvertime
	 */ 
	public function getRegularNightDifferentialOvertime()
	{
		return $this->regularNightDifferentialOvertime;
	}

	/**
	 * Set the value of regularNightDifferentialOvertime
	 *
	 * @return  self
	 */ 
	public function setRegularNightDifferentialOvertime($regularNightDifferentialOvertime)
	{
		$this->regularNightDifferentialOvertime = $regularNightDifferentialOvertime;

		return $this;
	}

	/**
	 * Get the value of restDayNightDifferentialOvertime
	 */ 
	public function getRestDayNightDifferentialOvertime()
	{
		return $this->restDayNightDifferentialOvertime;
	}

	/**
	 * Set the value of restDayNightDifferentialOvertime
	 *
	 * @return  self
	 */ 
	public function setRestDayNightDifferentialOvertime($restDayNightDifferentialOvertime)
	{
		$this->restDayNightDifferentialOvertime = $restDayNightDifferentialOvertime;

		return $this;
	}

	/**
	 * Get the value of holidaySpecialNightDifferentialOvertime
	 */ 
	public function getHolidaySpecialNightDifferentialOvertime()
	{
		return $this->holidaySpecialNightDifferentialOvertime;
	}

	/**
	 * Set the value of holidaySpecialNightDifferentialOvertime
	 *
	 * @return  self
	 */ 
	public function setHolidaySpecialNightDifferentialOvertime($holidaySpecialNightDifferentialOvertime)
	{
		$this->holidaySpecialNightDifferentialOvertime = $holidaySpecialNightDifferentialOvertime;

		return $this;
	}

	/**
	 * Get the value of holidaySpecialRestdayNightDifferentialOvertime
	 */ 
	public function getHolidaySpecialRestdayNightDifferentialOvertime()
	{
		return $this->holidaySpecialRestdayNightDifferentialOvertime;
	}

	/**
	 * Set the value of holidaySpecialRestdayNightDifferentialOvertime
	 *
	 * @return  self
	 */ 
	public function setHolidaySpecialRestdayNightDifferentialOvertime($holidaySpecialRestdayNightDifferentialOvertime)
	{
		$this->holidaySpecialRestdayNightDifferentialOvertime = $holidaySpecialRestdayNightDifferentialOvertime;

		return $this;
	}

	/**
	 * Get the value of holidayLegalNightDifferentialOvertime
	 */ 
	public function getHolidayLegalNightDifferentialOvertime()
	{
		return $this->holidayLegalNightDifferentialOvertime;
	}

	/**
	 * Set the value of holidayLegalNightDifferentialOvertime
	 *
	 * @return  self
	 */ 
	public function setHolidayLegalNightDifferentialOvertime($holidayLegalNightDifferentialOvertime)
	{
		$this->holidayLegalNightDifferentialOvertime = $holidayLegalNightDifferentialOvertime;

		return $this;
	}

	/**
	 * Get the value of holidayLegalRestdayNightDifferentialOvertime
	 */ 
	public function getHolidayLegalRestdayNightDifferentialOvertime()
	{
		return $this->holidayLegalRestdayNightDifferentialOvertime;
	}

	/**
	 * Set the value of holidayLegalRestdayNightDifferentialOvertime
	 *
	 * @return  self
	 */ 
	public function setHolidayLegalRestdayNightDifferentialOvertime($holidayLegalRestdayNightDifferentialOvertime)
	{
		$this->holidayLegalRestdayNightDifferentialOvertime = $holidayLegalRestdayNightDifferentialOvertime;

		return $this;
	}

	/**
	 * Get the value of holidayDoubleNightDifferentialOvertime
	 */ 
	public function getHolidayDoubleNightDifferentialOvertime()
	{
		return $this->holidayDoubleNightDifferentialOvertime;
	}

	/**
	 * Set the value of holidayDoubleNightDifferentialOvertime
	 *
	 * @return  self
	 */ 
	public function setHolidayDoubleNightDifferentialOvertime($holidayDoubleNightDifferentialOvertime)
	{
		$this->holidayDoubleNightDifferentialOvertime = $holidayDoubleNightDifferentialOvertime;

		return $this;
	}

	/**
	 * Get the value of holidayDoubleRestdayNightDifferentialOvertime
	 */ 
	public function getHolidayDoubleRestdayNightDifferentialOvertime()
	{
		return $this->holidayDoubleRestdayNightDifferentialOvertime;
	}

	/**
	 * Set the value of holidayDoubleRestdayNightDifferentialOvertime
	 *
	 * @return  self
	 */ 
	public function setHolidayDoubleRestdayNightDifferentialOvertime($holidayDoubleRestdayNightDifferentialOvertime)
	{
		$this->holidayDoubleRestdayNightDifferentialOvertime = $holidayDoubleRestdayNightDifferentialOvertime;

		return $this;
	}
}
?>