<?php
class Payslip_Amount_Calculator_IM extends Payslip_Amount_Calculator{
	public function __construct() {
		parent::__construct();
	}
	
	public function computeNightShiftOvertime() {
		$nightshift_hours = $this->obj_payslip_hour->getNightShiftOvertime();
		$nightshift_rate = $this->obj_payslip_rate->getNightShiftDiff();
		$nightshift_rate = ($nightshift_rate - 100) / 100;
		$ns_ot_rate = $this->obj_payslip_rate->getNightShiftOvertime();
		return Tools::numberFormat(($this->per_hour_amount * ($ns_ot_rate/100)) * $nightshift_hours * $nightshift_rate, 2);	
	}
	
	public function computeRegularOvertimeExcess() {
		$regular_ot_hours_excess = $this->obj_payslip_hour->getRegularOvertimeExcess();
		$ot_rate = (156.25 / 100);
		return Tools::numberFormat($regular_ot_hours_excess * $this->per_hour_amount * $ot_rate, 2);	
	}	
	
	public function computeNightShiftOvertimeExcess() {
		$nightshift_hours_excess = $this->obj_payslip_hour->getNightShiftOvertimeExcess();
		$nightshift_rate = $this->obj_payslip_rate->getNightShiftDiff();
		$nightshift_rate = ($nightshift_rate - 100) / 100;
		$ns_ot_rate = (156.25 / 100);
		return Tools::numberFormat(($this->per_hour_amount * $ns_ot_rate) * $nightshift_hours_excess * $nightshift_rate, 2);
	}
	
	public function computeRestDayOvertime() {
		$restday_ot_hours = $this->obj_payslip_hour->getRestDayOvertime();
		//$restday_rate = $this->obj_payslip_rate->getRestDay();
		$restday_ot_rate = $this->obj_payslip_rate->getRestDayOvertime();
		return Tools::numberFormat($restday_ot_hours * $this->per_hour_amount * ($restday_ot_rate/100), 2);	
	}

	public function computeRestDayOvertimeExcess() {
		$ot_hours_excess = $this->obj_payslip_hour->getRestDayOvertimeExcess();
		$ot_rate = (169 / 100);
		return Tools::numberFormat($ot_hours_excess * $this->per_hour_amount * $ot_rate, 2);	
	}	
	
	public function computeRestDayNightShiftOvertime() {
		$ot_hours = $this->obj_payslip_hour->getRestDayNightShiftOvertime();
		$ot_rate = $this->obj_payslip_rate->getRestDayOvertime();
		$ns_rate = $this->obj_payslip_rate->getNightShiftDiff();
		$ns_rate = ($ns_rate - 100) / 100;
		return Tools::numberFormat($ot_hours * $this->per_hour_amount * ($ot_rate/100) * $ns_rate , 2);	
	}
	
	public function computeHolidaySpecialNightShiftOvertime() {
		$ot_hours = $this->obj_payslip_hour->getHolidaySpecialNightShiftOvertime();
		$ot_rate = $this->obj_payslip_rate->getHolidaySpecialOvertime();
		$ns_rate = $this->obj_payslip_rate->getNightShiftDiff();
		$ns_rate = ($ns_rate - 100) / 100;
		return Tools::numberFormat($ot_hours * $this->per_hour_amount * ($ot_rate/100) * $ns_rate , 2);	
	}
	
	public function computeHolidayLegalNightShiftOvertime() {
		$ot_hours = $this->obj_payslip_hour->getHolidayLegalNightShiftOvertime();
		$ot_rate = $this->obj_payslip_rate->getHolidayLegalOvertime();
		$ns_rate = $this->obj_payslip_rate->getNightShiftDiff();
		$ns_rate = ($ns_rate - 100) / 100;
		return Tools::numberFormat($ot_hours * $this->per_hour_amount * ($ot_rate/100) * $ns_rate , 2);	
	}	
	
	public function computeHolidaySpecialNightShiftOvertimeExcess() {		
		$ot_hours = $this->obj_payslip_hour->getHolidaySpecialNightShiftOvertimeExcess();
		$rate = $this->obj_payslip_rate->getNightShiftDiff();
		$rate = ($rate - 100) / 100;
		$ns_ot_rate = (169 / 100);
		return Tools::numberFormat(($this->per_hour_amount * $ns_ot_rate) * $ot_hours * $rate, 2);
	}
	
	public function computeHolidayLegalNightShiftOvertimeExcess() {		
		$ot_hours = $this->obj_payslip_hour->getHolidayLegalNightShiftOvertimeExcess();
		$rate = $this->obj_payslip_rate->getNightShiftDiff();
		$rate = ($rate - 100) / 100;
		$ns_ot_rate = (260 / 100);
		return Tools::numberFormat(($this->per_hour_amount * $ns_ot_rate) * $ot_hours * $rate, 2);
	}	

	public function computeRestDayNightShiftOvertimeExcess() {		
		$nightshift_hours_excess = $this->obj_payslip_hour->getRestDayNightShiftOvertimeExcess();
		$nightshift_rate = $this->obj_payslip_rate->getNightShiftDiff();
		$nightshift_rate = ($nightshift_rate - 100) / 100;
		$ns_ot_rate = (169 / 100);
		return Tools::numberFormat(($this->per_hour_amount * $ns_ot_rate) * $nightshift_hours_excess * $nightshift_rate, 2);
	}
	
	public function computeHolidaySpecialOvertime() {
		$hours = $this->obj_payslip_hour->getHolidaySpecialOvertime();
		$rate = $this->obj_payslip_rate->getHolidaySpecialOvertime();
		$rate = ($rate / 100);
		return Tools::numberFormat($hours * $this->per_hour_amount * $rate, 2);	
	}
	
	public function computeHolidayLegalOvertime() {
		$hours = $this->obj_payslip_hour->getHolidayLegalOvertime();
		$rate = $this->obj_payslip_rate->getHolidayLegalOvertime();
		$rate = ($rate / 100);
		return Tools::numberFormat($hours * $this->per_hour_amount * $rate, 2);	
	}	

	public function computeHolidaySpecialOvertimeExcess() {
		$ot_hours_excess = $this->obj_payslip_hour->getHolidaySpecialOvertimeExcess();
		$ot_rate = (169 / 100);
		return Tools::numberFormat($ot_hours_excess * $this->per_hour_amount * $ot_rate, 2);	
	}
	
	public function computeHolidayLegalOvertimeExcess() {
		$ot_hours_excess = $this->obj_payslip_hour->getHolidayLegalOvertimeExcess();
		$ot_rate = (260 / 100);
		return Tools::numberFormat($ot_hours_excess * $this->per_hour_amount * $ot_rate, 2);	
	}
	
	public function computeRegularNightShift() {
		$hours = $this->obj_payslip_hour->getRegularNightShift();
		$rate = $this->obj_payslip_rate->getNightShiftDiff();
		$rate = ($rate - 100) / 100;
		return Tools::numberFormat($this->per_hour_amount * $rate * $hours, 2);	
	}
	
	public function computeTotalNightShift() {
		return Tools::numberFormat($this->computeRegularNightShift() + $this->computeRestDayNightShift() + $this->computeHolidaySpecialNightShift() + $this->computeHolidaySpecialRestDayNightShift() + $this->computeHolidayLegalNightShift() + $this->computeHolidayLegalRestDayNightShift);
	}	
	
	public function computeTotalOvertime() {
		return Tools::numberFormat($this->computeRegularOvertime() + 
			$this->computeNightShiftOvertime() + 
			$this->computeNightShiftOvertimeExcess() +			
			$this->computeRestDayNightShiftOvertime() +
			$this->computeRestDayOvertimeExcess() +
			$this->computeRestDayOvertime() + 			
			$this->computeHolidaySpecialOvertime() + 
			$this->computeHolidaySpecialRestDayOvertime() +
			$this->computeHolidaySpecialOvertimeExcess() +
			$this->computeHolidaySpecialNightShiftOvertimeExcess() +
			$this->computeHolidaySpecialNightShiftOvertime() +			
			$this->computeHolidayLegalOvertime() + 
			$this->computeHolidayLegalRestDayOvertime() +
			$this->computeHolidayLegalOvertimeExcess() +
			$this->computeHolidayLegalNightShiftOvertimeExcess() + 
			$this->computeHolidayLegalNightShiftOvertime() +
			$this->computeRestDayNightShiftOvertimeExcess() +
			$this->computeRegularOvertimeExcess()			
		, 2);		
	}
}

?>