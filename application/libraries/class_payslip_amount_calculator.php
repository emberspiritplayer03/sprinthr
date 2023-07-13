<?php
/* Usage
		$c = new Payslip_Amount_Calculator;
		$c->setPayslipHour($p);
		$c->setPayslipRate($x);
		$c->setSalaryPerHourAmount(277);
		$c->setSalaryPerDayAmount(500);
		
		$c->computeRegularOvertime();
		$c->computeRegularNightShift();
		$c->computeRegularLate();
		$c->computeRegularUndertime();
		$c->computeAbsent(2);
		$c->computeSuspension(3);
		
		$c->computeRestDay();
		$c->computeRestDayOvertime();
		$c->computeRestDayNightShift();		
		
		$c->computeHolidaySpecial();
		$c->computeHolidaySpecialOvertime();
		$c->computeHolidaySpecialNightShift();
		$c->computeHolidaySpecialRestDay();
		$c->computeHolidaySpecialRestDayOvertime();
		$c->computeHolidaySpecialRestDayNightShift();
		
		$c->computeHolidayLegal();
		$c->computeHolidayLegalOvertime();
		$c->computeHolidayLegalNightShift();
		$c->computeHolidayLegalRestDay();
		$c->computeHolidayLegalRestDayOvertime();
		$c->computeHolidayLegalRestDayNightShift();	
		
		$c->computeTotalHoliday();
		$c->computeTotalHolidayRestdayOvertime();
*/
class Payslip_Amount_Calculator {
	// Object
	protected $obj_payslip_hour; // Instance of G_Payslip_Hour
	protected $obj_payslip_rate; // Instance of G_Payslip_Percentage_Rate
	
	protected $per_hour_amount;
	protected $per_day_amount;
	protected $per_month_amount;
	
	public function __construct() {
		
	}
	
	/*
		$ph = Instance of Payslip_Hour
	*/
	public function setPayslipHour($ph) {
		$this->obj_payslip_hour = $ph;	
	}
	
	/*
		$pr = Instance of Payslip_Percentage_Rate
	*/
	public function setPayslipRate($pr) {
		$this->obj_payslip_rate = $pr;	
	}
	
	public function setSalaryPerHourAmount($value) {
		$this->per_hour_amount = $value;
	}
	
	public function getSalaryPerHourAmount() {
		return $this->per_hour_amount;
	}	
	
	public function setSalaryPerDayAmount($value) {
		$this->per_day_amount = $value;	
	}
	
	public function getSalaryPerDayAmount() {
		return $this->per_day_amount;	
	}	
	
	public function setSalaryPerMonthAmount($value) {
		$this->per_month_amount = $value;	
	}
	
	public function getSalaryPerMonthAmount() {
		return $this->per_month_amount;	
	}
	
	public function computeRegular() {
		$hours = $this->obj_payslip_hour->getRegular();
		return Tools::numberFormat($hours * $this->per_hour_amount, 2);	
	}	
	
	public function computeRegularOvertime() {
		$ot_hours = $this->obj_payslip_hour->getRegularOvertime();
		$ot_rate = $this->obj_payslip_rate->getRegularOvertime();
		return Tools::numberFormat($ot_hours * $this->per_hour_amount * ($ot_rate/100), 2);	
	}
	
	public function computeRegularNightShift() {
		$nightshift_hours = $this->obj_payslip_hour->getRegularNightShift();
		$nightshift_rate = $this->obj_payslip_rate->getNightShiftDiff();
		return Tools::numberFormat(($this->per_hour_amount * ($nightshift_rate/100)) * $nightshift_hours, 2);	
	}
	
	public function computeNightShiftOvertime() {
		$nightshift_hours = $this->obj_payslip_hour->getNightShiftOvertime();
		$nightshift_rate = $this->obj_payslip_rate->getNightShiftDiff();
		$ns_ot_rate = $this->obj_payslip_rate->getNightShiftOvertime();
		return Tools::numberFormat(($this->per_hour_amount * ($ns_ot_rate/100)) * $nightshift_hours * ($nightshift_rate/100), 2);	
	}
	
	public function computeRegularLate() {
		$late_hours = $this->obj_payslip_hour->getRegularLate();
		$late_hours = Tools::numberFormat($late_hours, 2);
		return Tools::numberFormat($late_hours * $this->per_hour_amount, 2);	
	}

		public function computePrevRegularLate($late_hours,$perhour) {
		return Tools::numberFormat($late_hours * $perhour, 2);	
	}
	public function computeCutRegularLate($late_hours,$perhour) {
		return Tools::numberFormat($late_hours * $perhour, 2);	
	}
	
	public function computeRegularUndertime() {
		$ut_hours = $this->obj_payslip_hour->getRegularUndertime();
		$ut_hours = Tools::numberFormat($ut_hours, 2);
		return Tools::numberFormat($ut_hours * $this->per_hour_amount, 2);	
	}
	//new
	public function computePrevRegularUndertime($ud_hours,$perhour) {
		return Tools::numberFormat($ud_hours * $perhour, 2);	
	}
		public function computeCutRegularUndertime($ud_hours,$perhour) {
		return Tools::numberFormat($ud_hours * $perhour, 2);	
	}
	//new
	public function computePrevAbsent($days_absent,$per_day) {
		return Tools::numberFormat($days_absent * $per_day, 2);	
	}
		public function computeCutAbsent($days_absent,$per_day) {
		return Tools::numberFormat($days_absent * $per_day, 2);	
	}

	public function computeAbsent($days_absent) {
		return Tools::numberFormat($days_absent * $this->per_day_amount, 2);	
	}
	
	public function computeSuspension($days_suspended) {
		return Tools::numberFormat($days_suspended * $this->per_day_amount, 2);	
	}
	
	public function computeRestDay() {
		$restday_hours = $this->obj_payslip_hour->getRestDay();
		$restday_rate = $this->obj_payslip_rate->getRestDay();
		$restday_hours = Tools::numberFormat($restday_hours, 2);
		return Tools::numberFormat($restday_hours * $this->per_hour_amount * ($restday_rate/100), 2);
	}
	
	public function computeRestDayOvertime() {
		$restday_ot_hours = $this->obj_payslip_hour->getRestDayOvertime();
		$restday_rate = $this->obj_payslip_rate->getRestDay();
		$restday_ot_rate = $this->obj_payslip_rate->getRestDayOvertime();
		$restday_ot_hours = Tools::numberFormat($restday_ot_hours, 2);
		return Tools::numberFormat(($restday_ot_hours * ($this->per_hour_amount * ($restday_rate/100))) * ($restday_ot_rate/100), 2);	
	}
	
	public function computeRestDayNightShift() {
		$restday_nightshift_hours = $this->obj_payslip_hour->getRestdayNightShift();
		$nightshift_rate = $this->obj_payslip_rate->getNightShiftDiff();
		$restday_rate = $this->obj_payslip_rate->getRestDay();
		$restday_nightshift_hours = Tools::numberFormat($restday_nightshift_hours, 2);
		return Tools::numberFormat($restday_nightshift_hours * $this->per_hour_amount * ($nightshift_rate/100) * ($restday_rate/100), 2);	
	}
	
	public function computeHolidaySpecial() {
		$hours = $this->obj_payslip_hour->getHolidaySpecial();
		$rate = $this->obj_payslip_rate->getHolidaySpecial();
		return Tools::numberFormat($hours * $this->per_hour_amount * ($rate/100), 2);	
	}
	
	public function computeHolidaySpecialOvertime() {
		$holiday_special_ot_hours = $this->obj_payslip_hour->getHolidaySpecialOvertime();
		$holiday_special_rate = $this->obj_payslip_rate->getHolidaySpecial();
		$holiday_special_ot_rate = $this->obj_payslip_rate->getHolidaySpecialOvertime();
		$holiday_special_ot_hours = Tools::numberFormat($holiday_special_ot_hours, 2);
		return Tools::numberFormat(($holiday_special_ot_hours * ($this->per_hour_amount * ($holiday_special_rate/100))) * ($holiday_special_ot_rate/100), 2);	
	}
	
	public function computeHolidaySpecialNightShift() {
		$hours = $this->obj_payslip_hour->getHolidaySpecialNightShift();
		$hours = Tools::numberFormat($hours, 2);
		return Tools::numberFormat($hours * $this->per_hour_amount * ($this->obj_payslip_rate->getNightShiftDiff()/100) * ($this->obj_payslip_rate->getHolidaySpecial()/100), 2);	
	}
	
	public function computeHolidaySpecialRestDay() {
		$holiday_special_restday_hours = $this->obj_payslip_hour->getHolidaySpecialRestDay();
		$holiday_special_restday_rate = $this->obj_payslip_rate->getHolidaySpecialRestday();
		$holiday_special_restday_hours = Tools::numberFormat($holiday_special_restday_hours, 2);
		return Tools::numberFormat($holiday_special_restday_hours * $this->per_hour_amount * ($holiday_special_restday_rate/100), 2);	
	}
	
	public function computeHolidaySpecialRestDayOvertime() {
		$holiday_special_restday_ot_hours = $this->obj_payslip_hour->getHolidaySpecialRestDayOvertime();
		$holiday_special_restday_rate = $this->obj_payslip_rate->getHolidaySpecialRestday();
		$holiday_special_restday_ot_rate = $this->obj_payslip_rate->getHolidaySpecialRestdayOvertime();
		$holiday_special_restday_ot_hours = Tools::numberFormat($holiday_special_restday_ot_hours, 2);
		return Tools::numberFormat(($holiday_special_restday_ot_hours * ($this->per_hour_amount * ($holiday_special_restday_rate/100))) * ($holiday_special_restday_ot_rate/100), 2);	
	}
	
	public function computeHolidaySpecialRestDayNightShift() {
		$hours = $this->obj_payslip_hour->getHolidaySpecialRestdayNightShift();
		$hours = Tools::numberFormat($hours, 2);
		return Tools::numberFormat($hours * $this->per_hour_amount * ($this->obj_payslip_rate->getNightShiftDiff()/100) * ($this->obj_payslip_rate->getHolidaySpecialRestday()/100), 2);	
	}
	
	public function computeHolidayLegal() {
		$holiday_legal_hours = $this->obj_payslip_hour->getHolidayLegal();
		$holiday_legal_rate = $this->obj_payslip_rate->getHolidayLegal();
		$holiday_legal_hours = Tools::numberFormat($holiday_legal_hours, 2);
		return Tools::numberFormat($holiday_legal_hours * $this->per_hour_amount * ($holiday_legal_rate/100), 2);
	}
	
	public function computeHolidayLegalOvertime() {
		$holiday_legal_ot_hours = $this->obj_payslip_hour->getHolidayLegalOvertime();
		$holiday_legal_rate = $this->obj_payslip_rate->getHolidayLegal();
		$holiday_legal_ot_rate = $this->obj_payslip_rate->getHolidayLegalOvertime();
		$holiday_legal_ot_hours = Tools::numberFormat($holiday_legal_ot_hours, 2);
		return Tools::numberFormat(($holiday_legal_ot_hours * ($this->per_hour_amount * ($holiday_legal_rate/100))) * ($holiday_legal_ot_rate/100), 2);	
	}
	
	public function computeHolidayLegalNightShift() {
		$hours = $this->obj_payslip_hour->getHolidayLegalNightShift();
		$hours = Tools::numberFormat($hours, 2);
		return Tools::numberFormat($hours * $this->per_hour_amount * ($this->obj_payslip_rate->getNightShiftDiff()/100) * ($this->obj_payslip_rate->getHolidayLegal()/100), 2);	
	}
	
	public function computeHolidayLegalRestDay() {
		$holiday_legal_restday = $this->obj_payslip_hour->getHolidayLegalRestDay();
		$holiday_legal_restday_rate = $this->obj_payslip_rate->getHolidayLegalRestDay();
		$holiday_legal_restday = Tools::numberFormat($holiday_legal_restday, 2);
		return Tools::numberFormat($holiday_legal_restday * $this->per_hour_amount * ($holiday_legal_restday_rate/100));	
	}
	
	public function computeHolidayLegalRestDayOvertime() {
		$holiday_legal_restday_ot_hours = $this->obj_payslip_hour->getHolidayLegalRestDayOvertime();
		$holiday_legal_restday_rate = $this->obj_payslip_rate->getHolidayLegalRestday();
		$holiday_legal_restday_ot_rate = $this->obj_payslip_rate->getHolidayLegalRestdayOvertime();
		$holiday_legal_restday_ot_hours = Tools::numberFormat($holiday_legal_restday_ot_hours, 2);
		return Tools::numberFormat(($holiday_legal_restday_ot_hours * ($this->per_hour_amount * ($holiday_legal_restday_rate/100))) * ($holiday_legal_restday_ot_rate/100), 2);	
	}
	
	public function computeHolidayLegalRestDayNightShift() {
		$hour = $this->obj_payslip_hour->getHolidayLegalRestdayNightShift();
		$hour = Tools::numberFormat($hour, 2);
		return Tools::numberFormat($hour * $this->per_hour_amount * ($this->obj_payslip_rate->getNightShiftDiff()/100) * ($this->obj_payslip_rate->getHolidayLegalRestday()/100), 2);	
	}
	
	public function computeTotalNightShift() {
		return Tools::numberFormat($this->computeRegularNightShift() + $this->computeRestDayNightShift() + $this->computeHolidaySpecialNightShift() + $this->computeHolidaySpecialRestDayNightShift() + $this->computeHolidayLegalNightShift() + $this->computeHolidayLegalRestDayNightShift);
	}
	
	public function computeTotalHoliday() {
		return Tools::numberFormat($this->computeHolidaySpecial() + $this->computeHolidayLegal() + $this->computeHolidaySpecialRestDay() + $this->computeHolidayLegalRestDay());	
	}
	
	public function computeTotalOvertime() {					
		return Tools::numberFormat($this->computeRegularOvertime() + $this->computeNightShiftOvertime() + $this->computeRestDayOvertime() + $this->computeHolidaySpecialOvertime() + $this->computeHolidayLegalOvertime() + $this->computeHolidaySpecialRestDayOvertime() + $this->computeHolidayLegalRestDayOvertime());	
	}
}

?>