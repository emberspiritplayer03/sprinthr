<?php
class Benchmark_Bio_Controller extends Controller
{
	function __construct() {
		parent::__construct();
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
	}
	
	function payslipCompanyBenefits()
	{
		$period['start_date'] = "2013-12-01";
		$period['end_date']   = "2013-12-15";
		$eid = 25;
		echo "<pre>";
		$e = G_Employee_Finder::findById($eid);
		if($e){
			$be = new G_Employee_Benefit();	
			$be->addToPayslip($e,$period);
		}
	}
	
	function test_array()
	{
		$pos = "5Vi9sW9b3oQRFogWNHNoFfzeUDWyBJ6e24Mz7e9H7hE,NzonNsnlXgLm4QHfOMzThwO1QpiifoSPNzxc7t3W6uk";
		$arPos  = explode(",",$pos);
		foreach($arPos as $p){
			$pos = G_Job_Finder::findById(Utilities::decrypt($p));
			$newArPos[] = $pos->getId();			
			$arTitle[]  = $pos->getTitle() . "<br>";
		}
		print_r($newArPos);
		
		$pos_ids = implode(",",$newArPos);
		$desc = implode(",",$arTitle);	
		echo $desc;	
	}
	
	function computeEarnings()
	{
		$start_date = '2012-07-01';
		$end_date   = '2012-07-15';
		$e = G_Employee_Finder::findById(2);
		
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);
		if (!$p) {
			$p = new G_Payslip;
		}		
		$p->setPeriod($start_date, $end_date);
		$p->setEmployee($e);
		
		$a = G_Attendance_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);
		if (!$a) {
			$error = new G_Payslip_Error;
			$error->setMessage($e->getEmployeeCode() .': '. $e->getName() .' has no attendance');
			$error->setEmployeeId($e->getId());
			$error->setErrorTypeId(G_Payslip_Error::ERROR_NO_ATTENDANCE);
			$error->setDateLogged(Tools::getGmtDate('Y-m-d'));
			$error->setTimeLogged(Tools::getGmtDate('H:i:s'));
			$error->addError();
		}
				
		//$is_first_period = false;
		//if (date('j', strtotime($end_date)) == 15) {
			$is_first_period = true;
		//}
		
		$hour = G_Payslip_Hour_Finder::findByAttendanceFinder($a);
		$rate = G_Payslip_Percentage_Rate_Finder::findDefault();

		$s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($e, $end_date);		
		if (!$s) {
			$s = new G_Employee_Basic_Salary_History;
			$s->setBasicSalary(0);
		
			$error = new G_Payslip_Error;
			$error->setMessage($e->getEmployeeCode() .': '. $e->getName() .' has no salary record');
			$error->setEmployeeId($e->getId());
			$error->setErrorTypeId(G_Payslip_Error::ERROR_NO_SALARY);
			$error->setDateLogged(Tools::getGmtDate('Y-m-d'));
			$error->setTimeLogged(Tools::getGmtDate('H:i:s'));
			$error->addError();	
		}

		$position = G_Employee_Job_History_Finder::findByEmployeeAndDate($e, $end_date);
		if ($position) {
			$position_name = $position->getName();
			$labels[] = new Payslip_Label('Position', $position_name, 'position');
		}
		$salary_amount = $s->getBasicSalary();
		$salary_type = $s->getType();
		
		$c = Payslip_Amount_Calculator_Factory::get();
		$c->setPayslipHour($hour);
		$c->setPayslipRate($rate);	
		
		switch ($salary_type):
			case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:
				$c->setSalaryPerMonthAmount($salary_amount);
					$labels[] = new Payslip_Label('Monthly Rate', $salary_amount, 'monthly_rate');
					
				$per_day = $salary_amount / 26;
				$c->setSalaryPerDayAmount($per_day);
					$labels[] = new Payslip_Label('Daily Rate', $per_day, 'daily_rate');
					
				$per_hour = $per_day / 8;
				$c->setSalaryPerHourAmount($per_hour);
					$labels[] = new Payslip_Label('Hourly Rate', $per_hour, 'hourly_rate');
			break;
			case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:
				$c->setSalaryPerMonthAmount(0);
				
				$c->setSalaryPerDayAmount($salary_amount);
					$labels[] = new Payslip_Label('Daily Rate', $salary_amount, 'daily_rate');
					
				$per_hour = $salary_amount / 8;
				$c->setSalaryPerHourAmount($per_hour);
					$labels[] = new Payslip_Label('Hourly Rate', $per_hour, 'hourly_rate');
			break;
//			case G_Employee_Basic_Salary_History::SALARY_TYPE_HOURLY:
//				$c->setSalaryPerMonthAmount(0);
//				$c->setSalaryPerDayAmount(0);
//				$c->setSalaryPerHourAmount($salary_amount);
//			break;			
		endswitch;
		$present_days = G_Attendance_Helper::countPresentDays($a);
			$labels[] = new Payslip_Label('Present Days', $present_days, 'present_days');
		$present_days_with_pay = G_Attendance_Helper::countPresentDaysWithPay($a);	
			$labels[] = new Payslip_Label('Present Days with Pay', $present_days_with_pay, 'present_days_with_pay');
		$absent_days_with_pay = G_Attendance_Helper::countAbsentDaysWithPay($a);
			$labels[] = new Payslip_Label('Absent Days with Pay', $absent_days_with_pay, 'absent_days_with_pay');
		$absent_days_without_pay = G_Attendance_Helper::countAbsentDaysWithoutPay($a);
			$labels[] = new Payslip_Label('Absent Days without Pay', $absent_days_without_pay, 'absent_days_without_pay');
		$absent_amount = $c->computeAbsent($absent_days_without_pay);
			$labels[] = new Payslip_Label('Absent Amount without Pay', $absent_amount, 'absent_amount');
		$suspended_days = G_Attendance_Helper::countSuspendedDays($a);
			$labels[] = new Payslip_Label('Suspended Days', $suspended_days, 'suspended_days');
		$suspended_amount = $c->computeSuspension($suspended_days);
			$labels[] = new Payslip_Label('Suspended Amount', $suspended_amount, 'suspended_amount');				
		$undertime_hours = $hour->getRegularUndertime();
			$labels[] = new Payslip_Label('Undertime Hours', $undertime_hours, 'undertime_hours');
		$undertime_amount = $c->computeRegularUndertime();
			$labels[] = new Payslip_Label('Undertime Amount', $undertime_amount, 'undertime_amount');
		$late_hours = $hour->getRegularLate();
			$labels[] = new Payslip_Label('Late Hours', $late_hours, 'late_hours');					
		$late_amount = $c->computeRegularLate();
			$labels[] = new Payslip_Label('Late Amount', $late_amount, 'late_amount');									
		
		// REGULAR
		$regular_hours = $hour->getRegular();
			$labels[] = new Payslip_Label('Regular Hours', $regular_hours, 'regular_hours');
		$regular_amount = $c->computeRegular();
			$labels[] = new Payslip_Label('Regular Amount', $regular_amount, 'regular_amount');		
		$regular_ot_hours = $hour->getRegularOvertime();
			$labels[] = new Payslip_Label('Regular OT Hours', $regular_ot_hours, 'regular_ot_hours');
		$regular_ot_amount = $c->computeRegularOvertime();
			$labels[] = new Payslip_Label('Regular OT Amount', $regular_ot_amount, 'regular_ot_amount');
		$regular_ot_hours_excess = $hour->getRegularOvertimeExcess();
			$labels[] = new Payslip_Label('Regular OT Excess Hours', $regular_ot_hours_excess, 'regular_ot_hours_excess');			
		$regular_ot_amount_excess = $c->computeRegularOvertimeExcess();
			$labels[] = new Payslip_Label('Regular OT Excess Amount', $regular_ot_amount_excess, 'regular_ot_amount_excess');
		$regular_ns_hours = $hour->getRegularNightShift();
			$labels[] = new Payslip_Label('Regular NS Hours', $regular_ns_hours, 'regular_ns_hours');
		$regular_ns_amount = $c->computeRegularNightShift();
			$labels[] = new Payslip_Label('Regular NS Amount', $regular_ns_amount, 'regular_ns_amount');
		$regular_ns_ot_hours = $hour->getNightShiftOvertime();
			$labels[] = new Payslip_Label('Regular NS OT Hours', $regular_ns_ot_hours, 'regular_ns_ot_hours');			
		$regular_ns_ot_amount = $c->computeNightShiftOvertime();
			$labels[] = new Payslip_Label('Regular NS OT Amount', $regular_ns_ot_amount, 'regular_ns_ot_amount');		
		$regular_ns_ot_hours_excess = $hour->getNightShiftOvertimeExcess();
			$labels[] = new Payslip_Label('Regular NS OT Excess Hours', $regular_ns_ot_hours_excess, 'regular_ns_ot_hours_excess');			
		$regular_ns_ot_amount_excess = $c->computeNightShiftOvertimeExcess();
			$labels[] = new Payslip_Label('Regular NS OT Excess Amount', $regular_ns_ot_amount_excess, 'regular_ns_ot_amount_excess');
					
		// RESTDAY
		$restday_hours = $hour->getRestDay();
			$labels[] = new Payslip_Label('Restday Hours', $restday_hours, 'restday_hours');
		$restday_amount = $c->computeRestDay();
			$labels[] = new Payslip_Label('Restday Amount', $restday_amount, 'restday_amount');
		$restday_ot_hours = $hour->getRestDayOvertime();
			$labels[] = new Payslip_Label('Restday OT Hours', $restday_ot_hours, 'restday_ot_hours');		
		$restday_ot_amount = $c->computeRestDayOvertime();
			$labels[] = new Payslip_Label('Restday OT Amount', $restday_ot_amount, 'restday_ot_amount');
		$restday_ot_hours_excess = $hour->getRestDayOvertimeExcess();
			$labels[] = new Payslip_Label('Restday OT Excess Hours', $restday_ot_hours_excess, 'restday_ot_hours_excess');		
		$restday_ot_amount_excess = $c->computeRestDayOvertimeExcess();
			$labels[] = new Payslip_Label('Restday OT Excess Amount', $restday_ot_amount_excess, 'restday_ot_amount_excess');							
		$restday_ns_hours = $hour->getRestdayNightShift();
			$labels[] = new Payslip_Label('Restday NS Hours', $restday_ns_hours, 'restday_ns_hours');	
		$restday_ns_amount = $c->computeRestDayNightShift();	
			$labels[] = new Payslip_Label('Restday NS Amount', $restday_ns_amount, 'restday_ns_amount');			
		$restday_ns_ot_hours = $hour->getRestDayNightShiftOvertime();
			$labels[] = new Payslip_Label('Restday NS OT Hours', $restday_ns_ot_hours, 'restday_ns_ot_hours');	
		$restday_ns_ot_amount = $c->computeRestDayNightShiftOvertime();	
			$labels[] = new Payslip_Label('Restday NS OT Amount', $restday_ns_ot_amount, 'restday_ns_ot_amount');
		$restday_ns_ot_hours_excess = $hour->getRestDayNightShiftOvertimeExcess();
			$labels[] = new Payslip_Label('Restday NS OT Excess Hours', $restday_ns_ot_hours_excess, 'restday_ns_ot_hours_excess');	
		$restday_ns_ot_amount_excess = $c->computeRestDayNightShiftOvertimeExcess();	
			$labels[] = new Payslip_Label('Restday NS OT Excess Amount', $restday_ns_ot_amount_excess, 'restday_ns_ot_amount_excess');
	
		// HOLIDAY SPECIAL
		$holiday_special_hours = $hour->getHolidaySpecial();
			$labels[] = new Payslip_Label('Holiday Special Hours', $holiday_special_hours, 'holiday_special_hours');
		$holiday_special_amount = $c->computeHolidaySpecial();
			$labels[] = new Payslip_Label('Holiday Special Amount', $holiday_special_amount, 'holiday_special_amount');			
		$holiday_special_ot_hours = $hour->getHolidaySpecialOvertime();
			$labels[] = new Payslip_Label('Holiday Special OT Hours', $holiday_special_ot_hours, 'holiday_special_ot_hours');			
		$holiday_special_ot_amount = $c->computeHolidaySpecialOvertime();
			$labels[] = new Payslip_Label('Holiday Special OT Amount', $holiday_special_ot_amount, 'holiday_special_ot_amount');
		$holiday_special_ot_hours_excess = $hour->getHolidaySpecialOvertimeExcess();
			$labels[] = new Payslip_Label('Holiday Special OT Excess Hours', $holiday_special_ot_hours_excess, 'holiday_special_ot_hours_excess');			
		$holiday_special_ot_amount_excess = $c->computeHolidaySpecialOvertimeExcess();
			$labels[] = new Payslip_Label('Holiday Special OT Excess Amount', $holiday_special_ot_amount_excess, 'holiday_special_ot_amount_excess');						
		$holiday_special_ns_hours = $hour->getHolidaySpecialNightShift();
			$labels[] = new Payslip_Label('Holiday Special NS Hours', $holiday_special_ns_hours, 'holiday_special_ns_hours');
		$holiday_special_ns_amount = $c->computeHolidaySpecialNightShift();
			$labels[] = new Payslip_Label('Holiday Special NS Amount', $holiday_special_nightshift_amount, 'holiday_special_nightshift_amount');			
		$holiday_special_ns_ot_hours = $hour->getHolidaySpecialNightShiftOvertime();
			$labels[] = new Payslip_Label('Holiday Special NS OT Hours', $holiday_special_ns_ot_hours, 'holiday_special_ns_ot_hours');
		$holiday_special_ns_ot_amount = $c->computeHolidaySpecialNightShiftOvertime();
			$labels[] = new Payslip_Label('Holiday Special NS OT Amount', $holiday_special_ns_ot_amount, 'holiday_special_ns_ot_amount');
		$holiday_special_ns_ot_hours_excess = $hour->getHolidaySpecialNightShiftOvertimeExcess();
			$labels[] = new Payslip_Label('Holiday Special NS OT Excess Hours', $holiday_special_ns_ot_hours_excess, 'holiday_special_ns_ot_hours_excess');
		$holiday_special_ns_ot_amount_excess = $c->computeHolidaySpecialNightShiftOvertimeExcess();
			$labels[] = new Payslip_Label('Holiday Special NS OT Excess Amount', $holiday_special_ns_ot_amount_excess, 'holiday_special_ns_ot_amount_excess');															
		$holiday_special_restday_hours = $hour->getHolidaySpecialRestDay();
			$labels[] = new Payslip_Label('Holiday Special Restday Hours', $holiday_special_restday_hours, 'holiday_special_restday_hours');
		$holiday_special_restday_amount = $c->computeHolidaySpecialRestDay();
			$labels[] = new Payslip_Label('Holiday Special Restday Amount', $holiday_special_restday_amount, 'holiday_special_restday_amount');			
		$holiday_special_restday_ot_hours = $hour->getHolidaySpecialRestDayOvertime();
			$labels[] = new Payslip_Label('Holiday Special Restday OT Hours', $holiday_special_restday_ot_hours, 'holiday_special_restday_ot_hours');
		$holiday_special_restday_ot_amount = $c->computeHolidaySpecialRestDayOvertime();
			$labels[] = new Payslip_Label('Holiday Special Restday OT Amount', $holiday_special_restday_ot_amount, 'holiday_special_restday_ot_amount');			
		$holiday_special_restday_nightshift_hours = $hour->getHolidaySpecialRestdayNightShift();
			$labels[] = new Payslip_Label('Holiday Special Restday NS Hours', $holiday_special_restday_nightshift_hours, 'holiday_special_restday_nightshift_hours');
		$holiday_special_restday_nightshift_amount = $c->computeHolidaySpecialRestDayNightShift();	
			$labels[] = new Payslip_Label('Holiday Special Restday NS Amount', $holiday_special_restday_nightshift_amount, 'holiday_special_restday_nightshift_amount');			
		
		// HOLIDAY LEGAL
		$holiday_legal_hours = $hour->getHolidayLegal();
			$labels[] = new Payslip_Label('Holiday Legal Hours', $holiday_legal_hours, 'holiday_legal_hours');
		$holiday_legal_amount = $c->computeHolidayLegal();
			$labels[] = new Payslip_Label('Holiday Legal Amount', $holiday_legal_amount, 'holiday_legal_amount');			
		$holiday_legal_ot_hours = $hour->getHolidayLegalOvertime();
			$labels[] = new Payslip_Label('Holiday Legal OT Hours', $holiday_legal_ot_hours, 'holiday_legal_ot_hours');
		$holiday_legal_ot_amount = $c->computeHolidayLegalOvertime();
			$labels[] = new Payslip_Label('Holiday Legal OT Amount', $holiday_legal_ot_amount, 'holiday_legal_ot_amount');	
		$holiday_legal_ot_hours_excess = $hour->getHolidayLegalOvertimeExcess();
			$labels[] = new Payslip_Label('Holiday Legal OT Excess Hours', $holiday_legal_ot_hours_excess, 'holiday_legal_ot_hours_excess');			
		$holiday_legal_ot_amount_excess = $c->computeHolidayLegalOvertimeExcess();
			$labels[] = new Payslip_Label('Holiday Legal OT Excess Amount', $holiday_legal_ot_amount_excess, 'holiday_legal_ot_amount_excess');								
		$holiday_legal_ns_hours = $hour->getHolidaySpecialNightShift();
			$labels[] = new Payslip_Label('Holiday Legal NS Hours', $holiday_legal_ns_hours, 'holiday_legal_ns_hours');
		$holiday_legal_ns_amount = $c->computeHolidayLegalNightShift();
			$labels[] = new Payslip_Label('Holiday Legal NS Amount', $holiday_legal_ns_amount, 'holiday_legal_ns_amount');			
		$holiday_legal_ns_ot_hours = $hour->getHolidayLegalNightShiftOvertime();
			$labels[] = new Payslip_Label('Holiday Legal NS OT Hours', $holiday_legal_ns_ot_hours, 'holiday_legal_ns_ot_hours');
		$holiday_legal_ns_ot_amount = $c->computeHolidayLegalNightShiftOvertime();
			$labels[] = new Payslip_Label('Holiday Legal NS OT Amount', $holiday_legal_ns_ot_amount, 'holiday_legal_ns_ot_amount');
		$holiday_legal_ns_ot_hours_excess = $hour->getHolidayLegalNightShiftOvertimeExcess();
			$labels[] = new Payslip_Label('Holiday Legal NS OT Excess Hours', $holiday_legal_ns_ot_hours_excess, 'holiday_legal_ns_ot_hours_excess');
		$holiday_legal_ns_ot_amount_excess = $c->computeHolidayLegalNightShiftOvertimeExcess();
			$labels[] = new Payslip_Label('Holiday Legal NS OT Excess Amount', $holiday_legal_ns_ot_amount_excess, 'holiday_legal_ns_ot_amount_excess');															
		$holiday_legal_restday_hours = $hour->getHolidayLegalRestDay();
			$labels[] = new Payslip_Label('Holiday Legal Restday Hours', $holiday_legal_restday_hours, 'holiday_legal_restday_hours');
		$holiday_legal_restday_amount = $c->computeHolidayLegalRestDay();
			$labels[] = new Payslip_Label('Holiday Legal Restday Amount', $holiday_legal_restday_amount, 'holiday_legal_restday_amount');			
		$holiday_legal_restday_ot_hours = $hour->getHolidayLegalRestDayOvertime();
			$labels[] = new Payslip_Label('Holiday Legal Restday OT Hours', $holiday_legal_restday_ot_hours, 'holiday_legal_restday_ot_hours');
		$holiday_legal_restday_ot_amount = $c->computeHolidayLegalRestDayOvertime();
			$labels[] = new Payslip_Label('Holiday Legal Restday OT Amount', $holiday_legal_restday_ot_amount, 'holiday_legal_restday_ot_amount');			
		$holiday_legal_restday_nightshift_hours = $hour->getHolidayLegalRestdayNightShift();
			$labels[] = new Payslip_Label('Holiday Legal Restday NS Hours', $holiday_legal_restday_nightshift_hours, 'holiday_legal_restday_nightshift_hours');
		$holiday_legal_restday_nightshift_amount = $c->computeHolidayLegalRestDayNightShift();
			$labels[] = new Payslip_Label('Holiday Legal Restday NS Amount', $holiday_legal_restday_nightshift_amount, 'holiday_legal_restday_nightshift_amount');

//		echo '<br>present_days: '. $present_days;
//		echo '<br>present_days_with_pay: '. $present_days_with_pay;
//		echo '<br>absent_days_with_pay: '. $absent_days_with_pay;
//		echo '<br>absent_days_without_pay: '. $absent_days_without_pay;
//		echo '<br>suspended_days: '. $suspended_days;
//		echo '<br>undertime hours: '. $undertime_hours;
//		echo '<br>undertime amount: '. $undertime_amount;
//		echo '<br>late hours: '. $late_hours;
//		echo '<br>late amount: '. $late_amount;			
//		echo '<br>regular hours: '. $regular_hours;
//		echo '<br>regular amount: '. $regular_amount;
//		echo '<br>regular ot hours: '. $regular_ot_hours;
//		echo '<br>regular ot amount: '. $regular_ot_amount;
//		echo '<br>regular ot hours excess: '. $regular_ot_hours_excess;
//		echo '<br>regular ot amount excess: '. $regular_ot_amount_excess;
//		echo '<br>regular ns hours: '. $regular_ns_hours;
//		echo '<br>regular ns amount: '. $regular_ns_amount;
//		echo '<br>regular ns ot hours: '. $regular_ns_ot_hours;
//		echo '<br>regular ns ot amount: '. $regular_ns_ot_amount;
//		echo '<br>regular ns ot hours excess: '. $regular_ns_ot_hours_excess;
//		echo '<br>regular ns ot amount excess: '. $regular_ns_ot_amount_excess;	
//		echo '<br>restday hours: '. $restday_hours;	
//		echo '<br>restday amount: '. $restday_amount;
//		echo '<br>restday ot hours: '. $restday_ot_hours;
//		echo '<br>restday ot amount: '. $restday_ot_amount;
//		echo '<br>restday ot hours excess: '. $restday_ot_hours_excess;
//		echo '<br>restday ot amount excess: '. $restday_ot_amount_excess;
//		echo '<br>restday ns hours: '. $restday_ns_hours;
//		echo '<br>restday ns amount: '. $restday_ns_amount;
//		echo '<br>restday ns ot hours: '. $restday_ns_ot_hours;
//		echo '<br>restday ns ot amount: '. $restday_ns_ot_amount;
//		echo '<br>restday ns ot hours excess: '. $restday_ns_ot_hours_excess;
//		echo '<br>restday ns ot amount excess: '. $restday_ns_ot_amount_excess;	
//		echo '<br>holiday special hours: '. $holiday_special_hours;	
//		echo '<br>holiday special amount: '. $holiday_special_amount;
//		echo '<br>holiday special ot hours: '. $holiday_special_ot_hours;
//		echo '<br>holiday special ot amount: '. $holiday_special_ot_amount;
//		echo '<br>holiday special ot hours excess: '. $holiday_special_ot_hours_excess;
//		echo '<br>holiday special ot amount excess: '. $holiday_special_ot_amount_excess;
//		echo '<br>holiday special ns ot hours: '. $holiday_special_ns_ot_hours;
//		echo '<br>holiday special ns ot amount: '. $holiday_special_ns_ot_amount;
//		echo '<br>holiday special ns ot hours excess: '. $holiday_special_ns_ot_hours_excess;
//		echo '<br>holiday special ns ot amount excess: '. $holiday_special_ns_ot_amount_excess;
//		echo '<br>holiday special restday hours: '. $holiday_special_restday_hours;
//		echo '<br>holiday special restday amount: '. $holiday_special_restday_amount;
//		echo '<br>holiday special restday ot hours: '. $holiday_special_restday_ot_hours;
//		echo '<br>holiday special restday ot amount: '. $holiday_special_restday_ot_amount;
//		echo '<br>holiday special restday nightshift hours: '. $holiday_special_restday_nightshift_hours;
//		echo '<br>holiday special restday nightshift amount: '. $holiday_special_restday_nightshift_amount;		
//		echo '<br>holiday legal hours: '. $holiday_legal_hours;	
//		echo '<br>holiday legal amount: '. $holiday_legal_amount;
//		echo '<br>holiday legal ot hours: '. $holiday_legal_ot_hours;
//		echo '<br>holiday legal ot amount: '. $holiday_legal_ot_amount;
//		echo '<br>holiday legal ot hours excess: '. $holiday_legal_ot_hours_excess;
//		echo '<br>holiday legal ot amount excess: '. $holiday_legal_ot_amount_excess;
//		echo '<br>holiday legal ns ot hours: '. $holiday_legal_ns_ot_hours;
//		echo '<br>holiday legal ns ot amount: '. $holiday_legal_ns_ot_amount;
//		echo '<br>holiday legal ns ot hours excess: '. $holiday_legal_ns_ot_hours_excess;
//		echo '<br>holiday legal ns ot amount excess: '. $holiday_legal_ns_ot_amount_excess;
//		echo '<br>holiday legal restday hours: '. $holiday_legal_restday_hours;
//		echo '<br>holiday legal restday amount: '. $holiday_legal_restday_amount;
//		echo '<br>holiday legal restday ot hours: '. $holiday_legal_restday_ot_hours;
//		echo '<br>holiday legal restday ot amount: '. $holiday_legal_restday_ot_amount;
//		echo '<br>holiday legal restday nightshift hours: '. $holiday_legal_restday_nightshift_hours;
//		echo '<br>holiday legal restday nightshift amount: '. $holiday_legal_restday_nightshift_amount;		

		$total_overtime_amount = $c->computeTotalOvertime();		
		echo $total_overtime_amount;
		exit;
		
			$labels[] = new Payslip_Label('Total OT Amount', $total_overtime_amount, 'total_overtime_amount');
		$total_nightshift_hours = $hour->getTotalNightShift();
			$labels[] = new Payslip_Label('Total NS Hours', $total_nightshift_hours, 'total_nightshift_hours');
		$total_nightshift_amount = $c->computeTotalNightShift();
			$labels[] = new Payslip_Label('Total NS Amount', $total_nightshift_amount, 'total_nightshift_amount');
		
		switch ($salary_type):
			case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:			
				if (Tools::isDateWithinDates($e->getHiredDate(), $start_date, $end_date)) { // pro rated
					$basic_pay = $present_days_with_pay * $salary_amount;					
				} else {
					$basic_pay = $salary_amount / 2;
				}
			break;
			case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:
				$basic_pay = $present_days_with_pay * $salary_amount;
			break;		
		endswitch;	
											
		// EARNINGS
		$total_earnings = 0;

		$total_earnings += $basic_pay;
		$obj_earning = new Earning('Basic Pay', $basic_pay);
		$obj_earning->setVariable('basic_pay');
		$ers[] = $obj_earning;
		
		$total_earnings += $total_overtime_amount;
		$obj_earning = new Earning('Overtime', $total_overtime_amount);
		$obj_earning->setVariable('total_ot_amount');
		$ers[] = $obj_earning;
		
		$total_earnings += $total_nightshift_amount;
		$obj_earning = new Earning('Nightshift', $total_nightshift_amount);
		$obj_earning->setVariable('total_ns_amount');
		$ers[] = $obj_earning;									
		
		//$p->addEarnings($ers);
		$p->setEarnings($ers);
		
		// DEDUCTIONS
		$total_deduction = 0;
		
		if ($is_first_period) {
			$sss = G_SSS_Finder::findBySalary($salary_amount);
			if ($sss) {				
				$multiplier = G_Settings_Deduction_Breakdown_Helper::getCurrentPayPeriodPercentageDeductedByEmployeedAndDeductible($e,G_Settings_Deduction_Breakdown::SSS,$end_date);				
				$sss_amount = $sss->getEmployeeShare();
				
				$sss_amount = $sss_amount * ($multiplier / 100);
				
				$sss_ee = $sss_amount;
				$sss_er = $sss->getCompanyShare();
				$total_deductions += $sss_amount;
				$obj_deduct = new Deduction('SSS', $sss_amount);
				$obj_deduct->setVariable('sss');
				$deductions[] = $obj_deduct;
				$p->setSSS($sss_amount);
				$labels[] = new Payslip_Label('SSS Employer', $sss_er, 'sss_er');		
			}
			
			$phealth = G_Philhealth_Finder::findBySalary($salary_amount);
			if ($phealth) {
				$multiplier = G_Settings_Deduction_Breakdown_Helper::getCurrentPayPeriodPercentageDeductedByEmployeedAndDeductible($e,G_Settings_Deduction_Breakdown::PHIL_HEALTH,$end_date);				
				
				$phealth_amount = $phealth->getEmployeeShare();
				$phealth_amount = $phealth_amount * ($multiplies / 100);
				
				$phealth_er = $phealth->getCompanyShare();
				$total_deductions += $phealth_amount;
				$obj_deduct = new Deduction('Philhealth', $phealth_amount);
				$obj_deduct->setVariable('philhealth');
				$deductions[] = $obj_deduct;
				$p->setPhilhealth($phealth_amount);
				$labels[] = new Payslip_Label('PHIC Employer', $phealth_er, 'philhealth_er');	
			}
			
			$pagibig = G_Pagibig_Finder::findBySalary($salary_amount);
			if ($pagibig) {
				$multiplier = G_Settings_Deduction_Breakdown_Helper::getCurrentPayPeriodPercentageDeductedByEmployeedAndDeductible($e,G_Settings_Deduction_Breakdown::PHIL_HEALTH,$end_date);				
												
				$pagibig_amount = $pagibig->getEmployeeShare();
				$pagibig_amount = $pagibig_amount * ($multiplier / 100);
				
				$pagibig_er = $pagibig->getCompanyShare();
				$total_deductions += $pagibig_amount;
				$obj_deduct = new Deduction('Pagibig', $pagibig_amount);
				$obj_deduct->setVariable('pagibig');
				$deductions[] = $obj_deduct;
				$p->setPagibig($pagibig_amount);	
				$labels[] = new Payslip_Label('Pagibig Employer', $pagibig_er, 'pagibig_er');		
			}
		}
		
		//EMPLOYEE DEDUCTIONS - LOANS
		/*$loan = G_Employee_Loan_Helper::generateEmployeeLoan($e,$start_date,$end_date);
		if($loan){			
			$total_deductions += $loan['total_amount'];
			$obj_deduct = new Deduction($loan['label'], $loan['total_amount']);
			$obj_deduct->setVariable('employee_deduction');
			$deductions[] = $obj_deduct;			
			$labels[] = new Payslip_Label($loan['label'], $loan['total_amount'], 'employee_deduction');		
			$p->setDeductions($deductions);	
		}*/
		//
		
		// BALANCES (MEDICAL, ID, UNIFORM)
//		$payments = G_Payment_Finder::findByEmployee($e);
//		if (count($payments) > 0) {
//			foreach ($payments as $payment) {
//				$total_amount = $payment->getTotalAmount();
//				$histories = $payment->getPaymentHistories();
//				$total_amount_paid = 0;
//				foreach ($histories as $history) {
//					$total_amount_paid += $history->getAmountPaid();	
//				}
//				if (strtolower($payment->getName()) == 'placement fee') {
//					$placement_fee = $salary_amount * 0.03 * $present_days;
//					$total_deductions += $placement_fee;
//					$obj_deduct = new Deduction('Placement Fee', $placement_fee);
//					$obj_deduct->setVariable('placement fee');
//					$deductions[] = $obj_deduct;	
//				} else if ($total_amount_paid < $total_amount) {
//					$partial_amount = $total_amount / 4;
//					$pb = new G_Payment_History;
//					$pb->setAmountPaid($partial_amount);
//					$pb->setDatePaid($end_date);
//					$payment->addPaymentHistory($pb);	
//					$payment->saveToEmployee($e);
//					
//					$total_deductions += $partial_amount;
//					$obj_deduct = new Deduction($payment->getName(), $partial_amount);
//					$obj_deduct->setVariable($payment->getName());
//					$deductions[] = $obj_deduct;				
//				}
//			}
//		}
		
		if ($late_amount > 0) {
			$total_deductions += $late_amount;
			$obj_deduct = new Deduction('Late', $late_amount);
			$obj_deduct->setVariable('late_amount');
			$deductions[] = $obj_deduct;
		}
		
		if ($undertime_amount > 0) {			
			$total_deductions += $undertime_amount;
			$obj_deduct = new Deduction('Undertime', $undertime_amount);
			$obj_deduct->setVariable('undertime_amount');
			$deductions[] = $obj_deduct;
		}
		
		if ($absent_amount > 0) {
			$total_deductions += $absent_amount;
			$obj_deduct = new Deduction('Absent Amount', $absent_amount);
			$obj_deduct->setVariable('absent_amount');
			$deductions[] = $obj_deduct;
		}
		
		if ($suspended_amount > 0) {
			$total_deductions += $suspended_amount;
			$obj_deduct = new Deduction('Suspended Amount', $suspended_amount);
			$obj_deduct->setVariable('suspended_amount');
			$deductions[] = $obj_deduct;
		}
		
		
	}
	
	function computeEarningsSub()
	{
		$start_date = '2012-07-01';
		$end_date   = '2012-07-15';
		$e = G_Employee_Finder::findById(2);
		
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);
		if (!$p) {
			$p = new G_Payslip;
		}		
		$p->setPeriod($start_date, $end_date);
		$p->setEmployee($e);
		
		$a = G_Attendance_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);
		if (!$a) {
			$error = new G_Payslip_Error;
			$error->setMessage($e->getEmployeeCode() .': '. $e->getName() .' has no attendance');
			$error->setEmployeeId($e->getId());
			$error->setErrorTypeId(G_Payslip_Error::ERROR_NO_ATTENDANCE);
			$error->setDateLogged(Tools::getGmtDate('Y-m-d'));
			$error->setTimeLogged(Tools::getGmtDate('H:i:s'));
			$error->addError();
		}
				
		//$is_first_period = false;
		//if (date('j', strtotime($end_date)) == 15) {
			$is_first_period = true;
		//}
		
		$hour = G_Payslip_Hour_Finder::findByAttendanceFinder($a);
		$rate = G_Payslip_Percentage_Rate_Finder::findDefault();

		$s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($e, $end_date);		
		if (!$s) {
			$s = new G_Employee_Basic_Salary_History;
			$s->setBasicSalary(0);
		
			$error = new G_Payslip_Error;
			$error->setMessage($e->getEmployeeCode() .': '. $e->getName() .' has no salary record');
			$error->setEmployeeId($e->getId());
			$error->setErrorTypeId(G_Payslip_Error::ERROR_NO_SALARY);
			$error->setDateLogged(Tools::getGmtDate('Y-m-d'));
			$error->setTimeLogged(Tools::getGmtDate('H:i:s'));
			$error->addError();	
		}

		$position = G_Employee_Job_History_Finder::findByEmployeeAndDate($e, $end_date);
		if ($position) {
			$position_name = $position->getName();
			$labels[] = new Payslip_Label('Position', $position_name, 'position');
		}
		$salary_amount = $s->getBasicSalary();
		$salary_type = $s->getType();
		
		$c = Payslip_Amount_Calculator_Factory::get();
		$c->setPayslipHour($hour);
		$c->setPayslipRate($rate);	
		
		switch ($salary_type):
			case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:
				$c->setSalaryPerMonthAmount($salary_amount);
					$labels[] = new Payslip_Label('Monthly Rate', $salary_amount, 'monthly_rate');
					
				$per_day = $salary_amount / 26;
				$c->setSalaryPerDayAmount($per_day);
					$labels[] = new Payslip_Label('Daily Rate', $per_day, 'daily_rate');
					
				$per_hour = $per_day / 8;
				$c->setSalaryPerHourAmount($per_hour);
					$labels[] = new Payslip_Label('Hourly Rate', $per_hour, 'hourly_rate');
			break;
			case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:
				$c->setSalaryPerMonthAmount(0);
				
				$c->setSalaryPerDayAmount($salary_amount);
					$labels[] = new Payslip_Label('Daily Rate', $salary_amount, 'daily_rate');
					
				$per_hour = $salary_amount / 8;
				$c->setSalaryPerHourAmount($per_hour);
					$labels[] = new Payslip_Label('Hourly Rate', $per_hour, 'hourly_rate');
			break;
//			case G_Employee_Basic_Salary_History::SALARY_TYPE_HOURLY:
//				$c->setSalaryPerMonthAmount(0);
//				$c->setSalaryPerDayAmount(0);
//				$c->setSalaryPerHourAmount($salary_amount);
//			break;			
		endswitch;
		$present_days = G_Attendance_Helper::countPresentDays($a);
			$labels[] = new Payslip_Label('Present Days', $present_days, 'present_days');
		$present_days_with_pay = G_Attendance_Helper::countPresentDaysWithPay($a);	
			$labels[] = new Payslip_Label('Present Days with Pay', $present_days_with_pay, 'present_days_with_pay');
		$absent_days_with_pay = G_Attendance_Helper::countAbsentDaysWithPay($a);
			$labels[] = new Payslip_Label('Absent Days with Pay', $absent_days_with_pay, 'absent_days_with_pay');
		$absent_days_without_pay = G_Attendance_Helper::countAbsentDaysWithoutPay($a);
			$labels[] = new Payslip_Label('Absent Days without Pay', $absent_days_without_pay, 'absent_days_without_pay');
		$absent_amount = $c->computeAbsent($absent_days_without_pay);
			$labels[] = new Payslip_Label('Absent Amount without Pay', $absent_amount, 'absent_amount');
		$suspended_days = G_Attendance_Helper::countSuspendedDays($a);
			$labels[] = new Payslip_Label('Suspended Days', $suspended_days, 'suspended_days');
		$suspended_amount = $c->computeSuspension($suspended_days);
			$labels[] = new Payslip_Label('Suspended Amount', $suspended_amount, 'suspended_amount');				
		$undertime_hours = $hour->getRegularUndertime();
			$labels[] = new Payslip_Label('Undertime Hours', $undertime_hours, 'undertime_hours');
		$undertime_amount = $c->computeRegularUndertime();
			$labels[] = new Payslip_Label('Undertime Amount', $undertime_amount, 'undertime_amount');
		$late_hours = $hour->getRegularLate();
			$labels[] = new Payslip_Label('Late Hours', $late_hours, 'late_hours');					
		$late_amount = $c->computeRegularLate();
			$labels[] = new Payslip_Label('Late Amount', $late_amount, 'late_amount');									
		
		// REGULAR
		$regular_hours = $hour->getRegular();
			$labels[] = new Payslip_Label('Regular Hours', $regular_hours, 'regular_hours');
		$regular_amount = $c->computeRegular();
			$labels[] = new Payslip_Label('Regular Amount', $regular_amount, 'regular_amount');		
		$regular_ot_hours = $hour->getRegularOvertime();
			$labels[] = new Payslip_Label('Regular OT Hours', $regular_ot_hours, 'regular_ot_hours');
		$regular_ot_amount = $c->computeRegularOvertime();
			$labels[] = new Payslip_Label('Regular OT Amount', $regular_ot_amount, 'regular_ot_amount');
		$regular_ot_hours_excess = $hour->getRegularOvertimeExcess();
			$labels[] = new Payslip_Label('Regular OT Excess Hours', $regular_ot_hours_excess, 'regular_ot_hours_excess');			
		//$regular_ot_amount_excess = $c->computeRegularOvertimeExcess();
			//$labels[] = new Payslip_Label('Regular OT Excess Amount', $regular_ot_amount_excess, 'regular_ot_amount_excess');
		$regular_ns_hours = $hour->getRegularNightShift();
			$labels[] = new Payslip_Label('Regular NS Hours', $regular_ns_hours, 'regular_ns_hours');
		$regular_ns_amount = $c->computeRegularNightShift();
			$labels[] = new Payslip_Label('Regular NS Amount', $regular_ns_amount, 'regular_ns_amount');
		$regular_ns_ot_hours = $hour->getNightShiftOvertime();
			$labels[] = new Payslip_Label('Regular NS OT Hours', $regular_ns_ot_hours, 'regular_ns_ot_hours');			
		$regular_ns_ot_amount = $c->computeNightShiftOvertime();
			$labels[] = new Payslip_Label('Regular NS OT Amount', $regular_ns_ot_amount, 'regular_ns_ot_amount');		
		$regular_ns_ot_hours_excess = $hour->getNightShiftOvertimeExcess();
			$labels[] = new Payslip_Label('Regular NS OT Excess Hours', $regular_ns_ot_hours_excess, 'regular_ns_ot_hours_excess');			
		//$regular_ns_ot_amount_excess = $c->computeNightShiftOvertimeExcess();
			//$labels[] = new Payslip_Label('Regular NS OT Excess Amount', $regular_ns_ot_amount_excess, 'regular_ns_ot_amount_excess');
					
		// RESTDAY
		$restday_hours = $hour->getRestDay();
			$labels[] = new Payslip_Label('Restday Hours', $restday_hours, 'restday_hours');
		$restday_amount = $c->computeRestDay();
			$labels[] = new Payslip_Label('Restday Amount', $restday_amount, 'restday_amount');
		$restday_ot_hours = $hour->getRestDayOvertime();
			$labels[] = new Payslip_Label('Restday OT Hours', $restday_ot_hours, 'restday_ot_hours');		
		$restday_ot_amount = $c->computeRestDayOvertime();
			$labels[] = new Payslip_Label('Restday OT Amount', $restday_ot_amount, 'restday_ot_amount');
		$restday_ot_hours_excess = $hour->getRestDayOvertimeExcess();
			$labels[] = new Payslip_Label('Restday OT Excess Hours', $restday_ot_hours_excess, 'restday_ot_hours_excess');		
		//$restday_ot_amount_excess = $c->computeRestDayOvertimeExcess();
			//$labels[] = new Payslip_Label('Restday OT Excess Amount', $restday_ot_amount_excess, 'restday_ot_amount_excess');							
		$restday_ns_hours = $hour->getRestdayNightShift();
			$labels[] = new Payslip_Label('Restday NS Hours', $restday_ns_hours, 'restday_ns_hours');	
		$restday_ns_amount = $c->computeRestDayNightShift();	
			$labels[] = new Payslip_Label('Restday NS Amount', $restday_ns_amount, 'restday_ns_amount');			
		$restday_ns_ot_hours = $hour->getRestDayNightShiftOvertime();
			$labels[] = new Payslip_Label('Restday NS OT Hours', $restday_ns_ot_hours, 'restday_ns_ot_hours');	
		//$restday_ns_ot_amount = $c->computeRestDayNightShiftOvertime();	
			//$labels[] = new Payslip_Label('Restday NS OT Amount', $restday_ns_ot_amount, 'restday_ns_ot_amount');
		$restday_ns_ot_hours_excess = $hour->getRestDayNightShiftOvertimeExcess();
			$labels[] = new Payslip_Label('Restday NS OT Excess Hours', $restday_ns_ot_hours_excess, 'restday_ns_ot_hours_excess');	
		//$restday_ns_ot_amount_excess = $c->computeRestDayNightShiftOvertimeExcess();	
			//$labels[] = new Payslip_Label('Restday NS OT Excess Amount', $restday_ns_ot_amount_excess, 'restday_ns_ot_amount_excess');
	
		// HOLIDAY SPECIAL
		$holiday_special_hours = $hour->getHolidaySpecial();
			$labels[] = new Payslip_Label('Holiday Special Hours', $holiday_special_hours, 'holiday_special_hours');
		$holiday_special_amount = $c->computeHolidaySpecial();
			$labels[] = new Payslip_Label('Holiday Special Amount', $holiday_special_amount, 'holiday_special_amount');			
		$holiday_special_ot_hours = $hour->getHolidaySpecialOvertime();
			$labels[] = new Payslip_Label('Holiday Special OT Hours', $holiday_special_ot_hours, 'holiday_special_ot_hours');			
		$holiday_special_ot_amount = $c->computeHolidaySpecialOvertime();
			$labels[] = new Payslip_Label('Holiday Special OT Amount', $holiday_special_ot_amount, 'holiday_special_ot_amount');
		$holiday_special_ot_hours_excess = $hour->getHolidaySpecialOvertimeExcess();
			$labels[] = new Payslip_Label('Holiday Special OT Excess Hours', $holiday_special_ot_hours_excess, 'holiday_special_ot_hours_excess');			
		//$holiday_special_ot_amount_excess = $c->computeHolidaySpecialOvertimeExcess();
			//$labels[] = new Payslip_Label('Holiday Special OT Excess Amount', $holiday_special_ot_amount_excess, 'holiday_special_ot_amount_excess');						
		$holiday_special_ns_hours = $hour->getHolidaySpecialNightShift();
			$labels[] = new Payslip_Label('Holiday Special NS Hours', $holiday_special_ns_hours, 'holiday_special_ns_hours');
		$holiday_special_ns_amount = $c->computeHolidaySpecialNightShift();
			$labels[] = new Payslip_Label('Holiday Special NS Amount', $holiday_special_nightshift_amount, 'holiday_special_nightshift_amount');			
		$holiday_special_ns_ot_hours = $hour->getHolidaySpecialNightShiftOvertime();
			$labels[] = new Payslip_Label('Holiday Special NS OT Hours', $holiday_special_ns_ot_hours, 'holiday_special_ns_ot_hours');
		//$holiday_special_ns_ot_amount = $c->computeHolidaySpecialNightShiftOvertime();
			//$labels[] = new Payslip_Label('Holiday Special NS OT Amount', $holiday_special_ns_ot_amount, 'holiday_special_ns_ot_amount');
		$holiday_special_ns_ot_hours_excess = $hour->getHolidaySpecialNightShiftOvertimeExcess();
			$labels[] = new Payslip_Label('Holiday Special NS OT Excess Hours', $holiday_special_ns_ot_hours_excess, 'holiday_special_ns_ot_hours_excess');
		//$holiday_special_ns_ot_amount_excess = $c->computeHolidaySpecialNightShiftOvertimeExcess();
			//$labels[] = new Payslip_Label('Holiday Special NS OT Excess Amount', $holiday_special_ns_ot_amount_excess, 'holiday_special_ns_ot_amount_excess');															
		$holiday_special_restday_hours = $hour->getHolidaySpecialRestDay();
			$labels[] = new Payslip_Label('Holiday Special Restday Hours', $holiday_special_restday_hours, 'holiday_special_restday_hours');
		$holiday_special_restday_amount = $c->computeHolidaySpecialRestDay();
			$labels[] = new Payslip_Label('Holiday Special Restday Amount', $holiday_special_restday_amount, 'holiday_special_restday_amount');			
		$holiday_special_restday_ot_hours = $hour->getHolidaySpecialRestDayOvertime();
			$labels[] = new Payslip_Label('Holiday Special Restday OT Hours', $holiday_special_restday_ot_hours, 'holiday_special_restday_ot_hours');
		$holiday_special_restday_ot_amount = $c->computeHolidaySpecialRestDayOvertime();
			$labels[] = new Payslip_Label('Holiday Special Restday OT Amount', $holiday_special_restday_ot_amount, 'holiday_special_restday_ot_amount');			
		$holiday_special_restday_nightshift_hours = $hour->getHolidaySpecialRestdayNightShift();
			$labels[] = new Payslip_Label('Holiday Special Restday NS Hours', $holiday_special_restday_nightshift_hours, 'holiday_special_restday_nightshift_hours');
		$holiday_special_restday_nightshift_amount = $c->computeHolidaySpecialRestDayNightShift();	
			$labels[] = new Payslip_Label('Holiday Special Restday NS Amount', $holiday_special_restday_nightshift_amount, 'holiday_special_restday_nightshift_amount');			
		
		// HOLIDAY LEGAL
		$holiday_legal_hours = $hour->getHolidayLegal();
			$labels[] = new Payslip_Label('Holiday Legal Hours', $holiday_legal_hours, 'holiday_legal_hours');
		$holiday_legal_amount = $c->computeHolidayLegal();
			$labels[] = new Payslip_Label('Holiday Legal Amount', $holiday_legal_amount, 'holiday_legal_amount');			
		$holiday_legal_ot_hours = $hour->getHolidayLegalOvertime();
			$labels[] = new Payslip_Label('Holiday Legal OT Hours', $holiday_legal_ot_hours, 'holiday_legal_ot_hours');
		$holiday_legal_ot_amount = $c->computeHolidayLegalOvertime();
			$labels[] = new Payslip_Label('Holiday Legal OT Amount', $holiday_legal_ot_amount, 'holiday_legal_ot_amount');	
		$holiday_legal_ot_hours_excess = $hour->getHolidayLegalOvertimeExcess();
			$labels[] = new Payslip_Label('Holiday Legal OT Excess Hours', $holiday_legal_ot_hours_excess, 'holiday_legal_ot_hours_excess');			
		//$holiday_legal_ot_amount_excess = $c->computeHolidayLegalOvertimeExcess();
			//$labels[] = new Payslip_Label('Holiday Legal OT Excess Amount', $holiday_legal_ot_amount_excess, 'holiday_legal_ot_amount_excess');								
		$holiday_legal_ns_hours = $hour->getHolidaySpecialNightShift();
			$labels[] = new Payslip_Label('Holiday Legal NS Hours', $holiday_legal_ns_hours, 'holiday_legal_ns_hours');
		$holiday_legal_ns_amount = $c->computeHolidayLegalNightShift();
			$labels[] = new Payslip_Label('Holiday Legal NS Amount', $holiday_legal_ns_amount, 'holiday_legal_ns_amount');			
		$holiday_legal_ns_ot_hours = $hour->getHolidayLegalNightShiftOvertime();
			$labels[] = new Payslip_Label('Holiday Legal NS OT Hours', $holiday_legal_ns_ot_hours, 'holiday_legal_ns_ot_hours');
		//$holiday_legal_ns_ot_amount = $c->computeHolidayLegalNightShiftOvertime();
			//$labels[] = new Payslip_Label('Holiday Legal NS OT Amount', $holiday_legal_ns_ot_amount, 'holiday_legal_ns_ot_amount');
		$holiday_legal_ns_ot_hours_excess = $hour->getHolidayLegalNightShiftOvertimeExcess();
			$labels[] = new Payslip_Label('Holiday Legal NS OT Excess Hours', $holiday_legal_ns_ot_hours_excess, 'holiday_legal_ns_ot_hours_excess');
		//$holiday_legal_ns_ot_amount_excess = $c->computeHolidayLegalNightShiftOvertimeExcess();
			//$labels[] = new Payslip_Label('Holiday Legal NS OT Excess Amount', $holiday_legal_ns_ot_amount_excess, 'holiday_legal_ns_ot_amount_excess');															
		$holiday_legal_restday_hours = $hour->getHolidayLegalRestDay();
			$labels[] = new Payslip_Label('Holiday Legal Restday Hours', $holiday_legal_restday_hours, 'holiday_legal_restday_hours');
		$holiday_legal_restday_amount = $c->computeHolidayLegalRestDay();
			$labels[] = new Payslip_Label('Holiday Legal Restday Amount', $holiday_legal_restday_amount, 'holiday_legal_restday_amount');			
		$holiday_legal_restday_ot_hours = $hour->getHolidayLegalRestDayOvertime();
			$labels[] = new Payslip_Label('Holiday Legal Restday OT Hours', $holiday_legal_restday_ot_hours, 'holiday_legal_restday_ot_hours');
		$holiday_legal_restday_ot_amount = $c->computeHolidayLegalRestDayOvertime();
			$labels[] = new Payslip_Label('Holiday Legal Restday OT Amount', $holiday_legal_restday_ot_amount, 'holiday_legal_restday_ot_amount');			
		$holiday_legal_restday_nightshift_hours = $hour->getHolidayLegalRestdayNightShift();
			$labels[] = new Payslip_Label('Holiday Legal Restday NS Hours', $holiday_legal_restday_nightshift_hours, 'holiday_legal_restday_nightshift_hours');
		$holiday_legal_restday_nightshift_amount = $c->computeHolidayLegalRestDayNightShift();
			$labels[] = new Payslip_Label('Holiday Legal Restday NS Amount', $holiday_legal_restday_nightshift_amount, 'holiday_legal_restday_nightshift_amount');

//		echo '<br>present_days: '. $present_days;
//		echo '<br>present_days_with_pay: '. $present_days_with_pay;
//		echo '<br>absent_days_with_pay: '. $absent_days_with_pay;
//		echo '<br>absent_days_without_pay: '. $absent_days_without_pay;
//		echo '<br>suspended_days: '. $suspended_days;
//		echo '<br>undertime hours: '. $undertime_hours;
//		echo '<br>undertime amount: '. $undertime_amount;
//		echo '<br>late hours: '. $late_hours;
//		echo '<br>late amount: '. $late_amount;			
//		echo '<br>regular hours: '. $regular_hours;
//		echo '<br>regular amount: '. $regular_amount;
//		echo '<br>regular ot hours: '. $regular_ot_hours;
//		echo '<br>regular ot amount: '. $regular_ot_amount;
//		echo '<br>regular ot hours excess: '. $regular_ot_hours_excess;
//		echo '<br>regular ot amount excess: '. $regular_ot_amount_excess;
//		echo '<br>regular ns hours: '. $regular_ns_hours;
//		echo '<br>regular ns amount: '. $regular_ns_amount;
//		echo '<br>regular ns ot hours: '. $regular_ns_ot_hours;
//		echo '<br>regular ns ot amount: '. $regular_ns_ot_amount;
//		echo '<br>regular ns ot hours excess: '. $regular_ns_ot_hours_excess;
//		echo '<br>regular ns ot amount excess: '. $regular_ns_ot_amount_excess;	
//		echo '<br>restday hours: '. $restday_hours;	
//		echo '<br>restday amount: '. $restday_amount;
//		echo '<br>restday ot hours: '. $restday_ot_hours;
//		echo '<br>restday ot amount: '. $restday_ot_amount;
//		echo '<br>restday ot hours excess: '. $restday_ot_hours_excess;
//		echo '<br>restday ot amount excess: '. $restday_ot_amount_excess;
//		echo '<br>restday ns hours: '. $restday_ns_hours;
//		echo '<br>restday ns amount: '. $restday_ns_amount;
//		echo '<br>restday ns ot hours: '. $restday_ns_ot_hours;
//		echo '<br>restday ns ot amount: '. $restday_ns_ot_amount;
//		echo '<br>restday ns ot hours excess: '. $restday_ns_ot_hours_excess;
//		echo '<br>restday ns ot amount excess: '. $restday_ns_ot_amount_excess;	
//		echo '<br>holiday special hours: '. $holiday_special_hours;	
//		echo '<br>holiday special amount: '. $holiday_special_amount;
//		echo '<br>holiday special ot hours: '. $holiday_special_ot_hours;
//		echo '<br>holiday special ot amount: '. $holiday_special_ot_amount;
//		echo '<br>holiday special ot hours excess: '. $holiday_special_ot_hours_excess;
//		echo '<br>holiday special ot amount excess: '. $holiday_special_ot_amount_excess;
//		echo '<br>holiday special ns ot hours: '. $holiday_special_ns_ot_hours;
//		echo '<br>holiday special ns ot amount: '. $holiday_special_ns_ot_amount;
//		echo '<br>holiday special ns ot hours excess: '. $holiday_special_ns_ot_hours_excess;
//		echo '<br>holiday special ns ot amount excess: '. $holiday_special_ns_ot_amount_excess;
//		echo '<br>holiday special restday hours: '. $holiday_special_restday_hours;
//		echo '<br>holiday special restday amount: '. $holiday_special_restday_amount;
//		echo '<br>holiday special restday ot hours: '. $holiday_special_restday_ot_hours;
//		echo '<br>holiday special restday ot amount: '. $holiday_special_restday_ot_amount;
//		echo '<br>holiday special restday nightshift hours: '. $holiday_special_restday_nightshift_hours;
//		echo '<br>holiday special restday nightshift amount: '. $holiday_special_restday_nightshift_amount;		
//		echo '<br>holiday legal hours: '. $holiday_legal_hours;	
//		echo '<br>holiday legal amount: '. $holiday_legal_amount;
//		echo '<br>holiday legal ot hours: '. $holiday_legal_ot_hours;
//		echo '<br>holiday legal ot amount: '. $holiday_legal_ot_amount;
//		echo '<br>holiday legal ot hours excess: '. $holiday_legal_ot_hours_excess;
//		echo '<br>holiday legal ot amount excess: '. $holiday_legal_ot_amount_excess;
//		echo '<br>holiday legal ns ot hours: '. $holiday_legal_ns_ot_hours;
//		echo '<br>holiday legal ns ot amount: '. $holiday_legal_ns_ot_amount;
//		echo '<br>holiday legal ns ot hours excess: '. $holiday_legal_ns_ot_hours_excess;
//		echo '<br>holiday legal ns ot amount excess: '. $holiday_legal_ns_ot_amount_excess;
//		echo '<br>holiday legal restday hours: '. $holiday_legal_restday_hours;
//		echo '<br>holiday legal restday amount: '. $holiday_legal_restday_amount;
//		echo '<br>holiday legal restday ot hours: '. $holiday_legal_restday_ot_hours;
//		echo '<br>holiday legal restday ot amount: '. $holiday_legal_restday_ot_amount;
//		echo '<br>holiday legal restday nightshift hours: '. $holiday_legal_restday_nightshift_hours;
//		echo '<br>holiday legal restday nightshift amount: '. $holiday_legal_restday_nightshift_amount;		

		$total_overtime_amount = $c->computeTotalOvertime();		
		echo $total_overtime_amount;
		exit;
		
			$labels[] = new Payslip_Label('Total OT Amount', $total_overtime_amount, 'total_overtime_amount');
		$total_nightshift_hours = $hour->getTotalNightShift();
			$labels[] = new Payslip_Label('Total NS Hours', $total_nightshift_hours, 'total_nightshift_hours');
		$total_nightshift_amount = $c->computeTotalNightShift();
			$labels[] = new Payslip_Label('Total NS Amount', $total_nightshift_amount, 'total_nightshift_amount');
		
		switch ($salary_type):
			case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:			
				if (Tools::isDateWithinDates($e->getHiredDate(), $start_date, $end_date)) { // pro rated
					$basic_pay = $present_days_with_pay * $salary_amount;					
				} else {
					$basic_pay = $salary_amount / 2;
				}
			break;
			case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:
				$basic_pay = $present_days_with_pay * $salary_amount;
			break;		
		endswitch;	
											
		// EARNINGS
		$total_earnings = 0;

		$total_earnings += $basic_pay;
		$obj_earning = new Earning('Basic Pay', $basic_pay);
		$obj_earning->setVariable('basic_pay');
		$ers[] = $obj_earning;
		
		$total_earnings += $total_overtime_amount;
		$obj_earning = new Earning('Overtime', $total_overtime_amount);
		$obj_earning->setVariable('total_ot_amount');
		$ers[] = $obj_earning;
		
		$total_earnings += $total_nightshift_amount;
		$obj_earning = new Earning('Nightshift', $total_nightshift_amount);
		$obj_earning->setVariable('total_ns_amount');
		$ers[] = $obj_earning;									
		
		//$p->addEarnings($ers);
		$p->setEarnings($ers);
		
		// DEDUCTIONS
		$total_deduction = 0;
		
		if ($is_first_period) {
			$sss = G_SSS_Finder::findBySalary($salary_amount);
			if ($sss) {				
				$multiplier = G_Settings_Deduction_Breakdown_Helper::getCurrentPayPeriodPercentageDeductedByEmployeedAndDeductible($e,G_Settings_Deduction_Breakdown::SSS,$end_date);				
				$sss_amount = $sss->getEmployeeShare();
				
				$sss_amount = $sss_amount * ($multiplier / 100);
				
				$sss_ee = $sss_amount;
				$sss_er = $sss->getCompanyShare();
				$total_deductions += $sss_amount;
				$obj_deduct = new Deduction('SSS', $sss_amount);
				$obj_deduct->setVariable('sss');
				$deductions[] = $obj_deduct;
				$p->setSSS($sss_amount);
				$labels[] = new Payslip_Label('SSS Employer', $sss_er, 'sss_er');		
			}
			
			$phealth = G_Philhealth_Finder::findBySalary($salary_amount);
			if ($phealth) {
				$multiplier = G_Settings_Deduction_Breakdown_Helper::getCurrentPayPeriodPercentageDeductedByEmployeedAndDeductible($e,G_Settings_Deduction_Breakdown::PHIL_HEALTH,$end_date);				
				
				$phealth_amount = $phealth->getEmployeeShare();
				$phealth_amount = $phealth_amount * ($multiplies / 100);
				
				$phealth_er = $phealth->getCompanyShare();
				$total_deductions += $phealth_amount;
				$obj_deduct = new Deduction('Philhealth', $phealth_amount);
				$obj_deduct->setVariable('philhealth');
				$deductions[] = $obj_deduct;
				$p->setPhilhealth($phealth_amount);
				$labels[] = new Payslip_Label('PHIC Employer', $phealth_er, 'philhealth_er');	
			}
			
			$pagibig = G_Pagibig_Finder::findBySalary($salary_amount);
			if ($pagibig) {
				$multiplier = G_Settings_Deduction_Breakdown_Helper::getCurrentPayPeriodPercentageDeductedByEmployeedAndDeductible($e,G_Settings_Deduction_Breakdown::PHIL_HEALTH,$end_date);				
												
				$pagibig_amount = $pagibig->getEmployeeShare();
				$pagibig_amount = $pagibig_amount * ($multiplier / 100);
				
				$pagibig_er = $pagibig->getCompanyShare();
				$total_deductions += $pagibig_amount;
				$obj_deduct = new Deduction('Pagibig', $pagibig_amount);
				$obj_deduct->setVariable('pagibig');
				$deductions[] = $obj_deduct;
				$p->setPagibig($pagibig_amount);	
				$labels[] = new Payslip_Label('Pagibig Employer', $pagibig_er, 'pagibig_er');		
			}
		}
		
		//EMPLOYEE DEDUCTIONS - LOANS
		/*$loan = G_Employee_Loan_Helper::generateEmployeeLoan($e,$start_date,$end_date);
		if($loan){			
			$total_deductions += $loan['total_amount'];
			$obj_deduct = new Deduction($loan['label'], $loan['total_amount']);
			$obj_deduct->setVariable('employee_deduction');
			$deductions[] = $obj_deduct;			
			$labels[] = new Payslip_Label($loan['label'], $loan['total_amount'], 'employee_deduction');		
			$p->setDeductions($deductions);	
		}*/
		//
		
		// BALANCES (MEDICAL, ID, UNIFORM)
//		$payments = G_Payment_Finder::findByEmployee($e);
//		if (count($payments) > 0) {
//			foreach ($payments as $payment) {
//				$total_amount = $payment->getTotalAmount();
//				$histories = $payment->getPaymentHistories();
//				$total_amount_paid = 0;
//				foreach ($histories as $history) {
//					$total_amount_paid += $history->getAmountPaid();	
//				}
//				if (strtolower($payment->getName()) == 'placement fee') {
//					$placement_fee = $salary_amount * 0.03 * $present_days;
//					$total_deductions += $placement_fee;
//					$obj_deduct = new Deduction('Placement Fee', $placement_fee);
//					$obj_deduct->setVariable('placement fee');
//					$deductions[] = $obj_deduct;	
//				} else if ($total_amount_paid < $total_amount) {
//					$partial_amount = $total_amount / 4;
//					$pb = new G_Payment_History;
//					$pb->setAmountPaid($partial_amount);
//					$pb->setDatePaid($end_date);
//					$payment->addPaymentHistory($pb);	
//					$payment->saveToEmployee($e);
//					
//					$total_deductions += $partial_amount;
//					$obj_deduct = new Deduction($payment->getName(), $partial_amount);
//					$obj_deduct->setVariable($payment->getName());
//					$deductions[] = $obj_deduct;				
//				}
//			}
//		}
		
		if ($late_amount > 0) {
			$total_deductions += $late_amount;
			$obj_deduct = new Deduction('Late', $late_amount);
			$obj_deduct->setVariable('late_amount');
			$deductions[] = $obj_deduct;
		}
		
		if ($undertime_amount > 0) {			
			$total_deductions += $undertime_amount;
			$obj_deduct = new Deduction('Undertime', $undertime_amount);
			$obj_deduct->setVariable('undertime_amount');
			$deductions[] = $obj_deduct;
		}
		
		if ($absent_amount > 0) {
			$total_deductions += $absent_amount;
			$obj_deduct = new Deduction('Absent Amount', $absent_amount);
			$obj_deduct->setVariable('absent_amount');
			$deductions[] = $obj_deduct;
		}
		
		if ($suspended_amount > 0) {
			$total_deductions += $suspended_amount;
			$obj_deduct = new Deduction('Suspended Amount', $suspended_amount);
			$obj_deduct->setVariable('suspended_amount');
			$deductions[] = $obj_deduct;
		}
		
		
	}
	
	function testUrlSubFolder()
	{
		echo hr_url('test/test');
	}
	
	function _load_total_pages()
	{
		sleep(1);
		$count = G_Employee_Helper::countTotalPayslipDateRange($_POST['date_start'],$_POST['date_to']);
		$pages = floor($count/40);
		$this->var['pages'] = $pages;
		$this->view->render('benchmark/bio/_total_pages.php', $this->var);
	}
	
	function _load_philhealth_total_pages()
	{
		sleep(1);
		$count = G_Employee_Helper::countTotalPayslipDateRange($_POST['date_start'],$_POST['date_to']);				
		$pages = floor($count/40);
		$this->var['pages'] = $pages;
		$this->view->render('benchmark/bio/philhealth/_total_pages.php', $this->var);
	}
	
	function pagibig()
	{	
		$info = G_Company_Info_Finder::findByCompanyStructureId($this->company_structure_id);
		if($info){
			$structure = G_Company_Structure_Finder::findById($info->getCompanyStructureId());;
			
			if($structure){
				$this->var['branch']		  = G_Company_Branch_Finder::findById($structure->getCompanyBranchId());
			}
			
			$total_employees = G_Employee_Helper::countTotalPayslipIsNotArchiveDateRange($_POST['pagibig_date_start'],$_POST['pagibig_date_to']);
			
			$this->var['info'] 			  = $info;
			$this->var['structure']	      = $structure;			
			$this->var['total_employees'] = $total_employees;
			$this->var['from']            = $_POST['pagibig_date_from'];//'2012-05-11';
			$this->var['to'] 		      = $_POST['pagibig_date_to'];//'2012-05-25';					
			$this->var['submission_date'] = date('d-M-y', strtotime($_POST['pagibig_submission_date']));			
			$this->var['pagibig_total']   = G_Employee_Helper::countTotalPagibigDeductionByDateRange($_POST['pagibig_date_from'], $_POST['pagibig_date_to']);
			
			if($_POST['limit_start'] == 'all'){
 				 $pages = floor($total_employees/40);
				 for($i=1;$i<=$pages;$i++){ 
					$limit_start += 40; 
					$this->var['filename']  = 'pagibig_monitoring.xls';
				 	$this->var['employees'] = G_Employee_Finder::findByPayslipDateRangeIsNotArchive($_POST['pagibig_date_from'], $_POST['pagibig_date_to'],$limit_start . ',40');			
					$this->view->render('benchmark/bio/pagibig_download.php', $this->var);
					//$this->view->render('benchmark/bio/pagibig_download_noheader.php', $this->var);
				 }				
			}else{
				$this->var['filename']  = 'pagibig_monitoring.xls';
				$this->var['employees'] = G_Employee_Finder::findByPayslipDateRangeIsNotArchive($_POST['pagibig_date_from'], $_POST['pagibig_date_to'],$_POST['limit_start'] . ',40');			
				$this->var['employees'] = G_Employee_Finder::findByPayslipDateRangeIsNotArchive($_POST['pagibig_date_from'], $_POST['pagibig_date_to'],$_POST['limit_start'] . ',40');			
				$this->view->render('benchmark/bio/pagibig_download.php', $this->var);
			}
		}
	}
	
	function testEarningsConstructor()
	{
		echo '<pre>';
		$ear_obj = new Earning('test', 200, Earning::TAXABLE, (int) $earning_type);
		
		//print_r($ear_obj);
		$ear[] = $ear_obj = new Earning('test', 200, Earning::TAXABLE, (int) $earning_type);
		print_r($ear);
	}
	
	function testSearchArray()
	{
		$sAr = array(0 => 143, 1 => 43, 2 => 243);
		$key = array_search('4', $sAr);
		echo $key;
	}	
	
	function addLoanDeductions()
	{
		echo '<pre>';
		$start_date = '2012-12-16';
		$end_date   = '2012-12-31';
		
		//Adjust pay period date
			$day   = date("d",strtotime($end_date));
			$year  = date("Y",strtotime($end_date));
			$month = date("m",strtotime($end_date));
			if($day == 28){
				$new_day = $day - 28;
				
			}elseif($day > 15){
				$new_day = $day - 30;
			}else{
				$new_day = $day - 15;
			}
			$new_day 	   = $day - $new_day;
			$loan_end_date = $year . '-' . $month . '-' . $new_day;
		//
		
		$eid        = 4;
		$e    = G_Employee_Finder::findById($eid);		
		if($e){
			$loan = G_Employee_Loan_Helper::getLoanDeductionIsNotArchiveByEmployeeIdAndCompanyStructureIdAndPayDate($e->getId(),$this->company_structure_id,$loan_end_date);
			print_r($loan);
			foreach($loan as $l){						
				//Add to Payroll
				$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);
				if($p){
					$deduction_type = (int) $_POST['deduction_type'];
					
					$ph  = new G_Payslip_Helper($p);	
					$d[] = new Deduction($l['loan_type'], $l['amount'], $deduction_type);	
					
					if ($d) {						
						$p->addOtherDeductions($d);																	
						$p->save();						
						
						$ph    	   = new G_Payslip_Helper($p);
						$gross_pay = $ph->computeTotalEarnings();
						$net_pay   = $gross_pay - $ph->computeTotalDeductions();
						$p->setNetPay($net_pay);
						$p->setGrossPay($gross_pay);
						$p->save();
					} else {
												
					}							
				}
				//
				
				//Update Loans table and dependencies
					//Append payment in loan payment breakdown
						$gelpb = new G_Employee_Loan_Payment_Breakdown();						
						$gelpb->setLoanId($l['loan_id']);
						$gelpb->setEmployeeId($e->getId());
						$gelpb->setLoanPaymentId($l['loan_detail_id']);					
						$gelpb->setReferenceNumber('');					
						$gelpb->setAmountPaid($l['amount']);					
						$gelpb->setDatePaid($end_date);								
						$gelpb->setRemarks('Deducted in Payroll');
						$gelpb->save();
						
						$geld          = G_Employee_Loan_Details_Finder::findById($l['loan_detail_id']);
						$sum_breakdown = G_Employee_Loan_Payment_Breakdown_Helper::sumTotalPaymentsByLoanPaymentId($geld);						
						echo 'Sum Breakdown:' . $sum_breakdown . '<br>';
					//
					//Update loan details												
						$new_amount = $geld->getAmount() - $sum_breakdown;
						echo 'New Amount : ' . $new_amount;
						$geld->setAmount($new_amount);	
						$geld->setAmountPaid($sum_breakdown);		
						if($new_amount > 0){
							$is_paid = G_Employee_Loan_Details::NO;
							$remarks = "With balance";
						}else{
							$is_paid = G_Employee_Loan_Details::YES;
							$remarks = "Fully Paid";
						}
						
						$geld->setIsPaid($is_paid);
						$geld->setRemarks($remarks);					
						$geld->save();
					//					
					//Update Header Loan Table
						$gel = G_Employee_Loan_Finder::findById($l['loan_id']);	
						$balance = $gel->getLoanAmount() - $sum_breakdown;					
						if($balance == $gel->getLoanAmount()){
							$gel->setStatus(G_Employee_Loan::DONE);
						}
						$gel->setBalance($balance);							
						$gel->save();
					//
				//
			}
		}
	}
	
	function addOtherEarnings()
	{
		echo '<pre>';
		$payroll_period_id = 30;
		$eid               = 11;
		
		$e  = G_Employee_Finder::findById($eid);
		$cp = G_Cutoff_Period_Finder::findById($payroll_period_id);
		
		if($cp){
			$earnings = G_Employee_Earnings_Finder::findAllApprovedByPayrollPeriodIdAndCompanyStructureIdAndIsNotArchive($cp->getId(),1);
			//print_r($earnings);
			foreach($earnings as $ea){
				//Convert to array employee id
				$eid = unserialize($ea->getEmployeeId());
				$eAr = explode(",",$eid);				
				//print_r($eAr);
				//Search in array 				
				if(in_array("All Employee", $eAr)){					
					$is_saved = $this->addToOtherEarnings($ea->getTitle(),$ea->getAmount(),$ea->getTaxable(),$cp,$e);
				}else{										
					if(in_array($e->getId(), $eAr)){
						echo 2;
						$is_saved = $this->addToOtherEarnings($ea->getTitle(),$ea->getAmount(),$ea->getTaxable(),$cp,$e);
					}
				}
			}
		}
	}
	
	function addToOtherEarnings($label,$amount,$is_taxable,$cp,$e) 
	{
		$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $cp->getStartDate(), $cp->getEndDate());
		if($p){
			$ph = new G_Payslip_Helper($p);
			
			$gross_pay = $ph->computeTotalEarnings();
			$net_pay   = $gross_pay - $ph->computeTotalDeductions();
			
			if($is_taxable == G_Employee_Earnings::YES){
				$taxable = Earning::TAXABLE;
			}else{
				$taxable = 0;
			}
			
			$ear[] = $ear_obj = new Earning($label, $amount, $taxable, (int) $earning_type);			
			$p->addOtherEarnings($ear);			
			$p->setGrossPay($gross_pay);
			$p->setNetPay($net_pay);
			$p->save();
			return true;
		}
	}
	
	function otherEarnings()
	{
		echo '<pre>';
		$id  = 32;
		$sql = "
			SELECT id, payout_date, period_start, period_end, basic_pay, gross_pay, net_pay, earnings, other_earnings, deductions, other_deductions, labels,
				taxable, withheld_tax, month_13th, sss, pagibig, philhealth
			FROM g_employee_payslip
			WHERE id = ". Model::safeSql($id) ."			
			LIMIT 1		
		";
		$rec = Model::runSql($sql,true);
		foreach($rec as $r){
			$d = unserialize($r['other_earnings']);
		}
		print_r($d);
		
		
	}
	
	function philhealth()
	{	
		$info = G_Company_Info_Finder::findByCompanyStructureId($this->company_structure_id);
		if($info){
			$structure = G_Company_Structure_Finder::findById($info->getCompanyStructureId());;
			
			if($structure){
				$this->var['branch']		  = G_Company_Branch_Finder::findById($structure->getCompanyBranchId());
			}
			
			$total_employees = G_Employee_Helper::countTotalPayslipIsNotArchiveDateRange($_POST['philhealth_date_from'],$_POST['philhealth_date_to']);			
			$pages 		  = floor($total_employees/40);
			$current_page = $_POST['philhealth_limit_start'] / 40;
			
			$this->var['total_pages']	  = $pages;
			$this->var['current_page']	  = $current_page;
  			$this->var['info'] 			  = $info;
			$this->var['structure']	      = $structure;			
			$this->var['total_employees'] = $total_employees;
			$this->var['from']            = $_POST['philhealth_date_from'];//'2012-05-11';
			$this->var['to'] 		         = $_POST['philhealth_date_to'];//'2012-05-25';						
			$this->var['submission_date'] = date('d-M-y', strtotime($_POST['philhealth_submission_date']));						
			
			if($_POST['philhealth_limit_start'] == 'all'){ 				
				 for($i=1;$i<=$pages;$i++){ 
					$limit_start += 40; 					
					$this->var['filename']  	  = 'philhealth.xls';
				 	$this->var['employees'] = G_Employee_Finder::findByPayslipDateRangeIsNotArchive($_POST['philhealth_date_from'], $_POST['philhealth_date_to'],$limit_start . ',40');			
					$this->view->render('benchmark/bio/philhealth/philhealth_download.php', $this->var);					
				 }				
			}else{
				$this->var['filename']  	  = 'philhealth.xls';
				$this->var['employees'] = G_Employee_Finder::findByPayslipDateRangeIsNotArchive($_POST['philhealth_date_from'], $_POST['philhealth_date_to'],$_POST['philhealth_limit_start'] . ',40');			
				$this->view->render('benchmark/bio/philhealth/philhealth_download.php', $this->var);
			}
		}
	}
	
	function philhealth_no_pagination()
	{	
		$info = G_Company_Info_Finder::findByCompanyStructureId($this->company_structure_id);
		if($info){
			$structure = G_Company_Structure_Finder::findById($info->getCompanyStructureId());;
			
			$this->var['info'] 			  = $info;
			$this->var['structure']	      = $structure;
			if($structure){
				$this->var['branch']		  = G_Company_Branch_Finder::findById($structure->getCompanyBranchId());
			}
			
			$this->var['from']            = $_POST['philhealth_date_from'];//'2012-05-11';
			$this->var['to'] 		      = $_POST['philhealth_date_to'];//'2012-05-25';		
			$this->var['month_covered']   = date('F', strtotime($_POST['philhealth_date_from']));
			$this->var['year_covered']    = date('Y', strtotime($_POST['philhealth_date_to']));		
			$this->var['submission_date'] = date('d-M-y', strtotime($_POST['submission_date']));						
			$this->var['filename']  	  = 'philhealth.xls';
			$this->var['employees'] = G_Employee_Finder::findByPayslipDateRange($_POST['philhealth_date_from'], $_POST['philhealth_date_to']);			
			$this->view->render('benchmark/bio/philhealth/philhealth_download.php', $this->var);
		}
	}
	
	function pagibigDeductions()
	{
		$pd = G_Employee_Helper::countTotalPagibigDeductionByDateRange('2012-08-01','2012-08-31');
		print_r($pd);
		echo $pd['ee'];
	}
	
	function _tree_view_asyn()
	{		
		$tree = Tree_View::asynCompanyStructure($_POST['root']);
		echo $tree;
	}
}
?>