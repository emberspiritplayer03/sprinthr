<?php
class G_Payslip_Generator implements IGenerator
{
    protected $start_date;
    protected $end_date;
    protected $employee;
    protected $cutoff_period;
    protected $employees = array();
    protected $employees_special_ot_rate = array();

    private $payslips = array();

    protected $excluded_employee_deduction = array();
    protected $frequency_id = 1;

    /*
     * @param array $employees array instance of G_Employee
     */
    public function __construct(G_Cutoff_Period $period, $employees = '')
    {
        $this->employees = $employees;
        $this->cutoff_period = $period;
        $this->start_date = $period->getStartDate();
        $this->end_date = $period->getEndDate();
    }

    public function setEmployee(IEmployee $e)
    {
        $this->employee = $e;
    }

    /*
     * @param array $employees Array instance of G_Employee
     */
    public function setEmployees($employees)
    {
        $this->employees = $employees;
    }

    public function setExcludedEmployeeDeduction($value)
    {
        $this->excluded_employee_deduction = $value;
    }

    public function save($payslips)
    {
        return G_Payslip_Manager::saveMultiple($payslips);
    }

    /**
     * Set employees_special_ot_rate method
     *
     */
    public function getEmployeesWithSpecialOtRate()
    {
        $fields = array('employee_id', 'ot_rate');
        $employee_ot_rate = G_Employee_Overtime_Rate_Helper::getAllData($fields);
        foreach ($employee_ot_rate as $rate) {
            $this->employees_special_ot_rate[$rate['employee_id']] = $rate['ot_rate'];
        }
    }

    /*
     * @return mixed G_Payslip or array of G_Payslip
     */
    public function generate()
    {
        $this->setPeriodAsGenerated();
        $this->getEmployeesWithSpecialOtRate();
        if ($this->employee) {
            return $this->generateByEmployee($this->employee);
        } else if ($this->employees) {
            $this->readyTimeLimit();
            return $this->generateByEmployees($this->employees);
        }
    }

    private function setPeriodAsGenerated()
    {
        $this->cutoff_period->setPayrollAsGenerated();
        $this->cutoff_period->save();
    }

    private function readyTimeLimit()
    {
        ini_set("memory_limit", "999M");
        set_time_limit(999999999999999999999);
    }

    /*
     * @param array $employees Array instance of G_Employee
     */
    private function generateByEmployees($employees)
    {
        foreach ($employees as $e) {
            $payslip = $this->generateByEmployee($e);
            if ($payslip) {
                $this->payslips[] = $payslip;
            } else {
                continue;
            }
        }
        return $this->payslips;
    }

    private function generateByEmployee(IEmployee $e)
    {

        $fields = array('date', 'start_time', 'end_time', 'day_type');
        $cot = G_Custom_Overtime_Helper::getAllEmployeeApprovedCustomOvertimeByDateRange($e->getId(), $this->start_date, $this->end_date, $fields);
        $custom_ot = array();
        foreach ($cot as $ot) {
            $custom_ot[$ot['date']] = array('start_time' => $ot['start_time'], 'end_time' => $ot['end_time'], 'day_type' => $ot['day_type']);
        }


        $disapproved_custom_ot = G_Custom_Overtime_Helper::getAllEmployeeDisApprovedCustomOvertimeByDateRange($e->getId(), $this->start_date, $this->end_date, $fields);
        $custom_ot_disapproved = array();
        foreach ($disapproved_custom_ot as $dot) {
            $custom_ot_disapproved[$dot['date']] = array('start_time' => $dot['start_time'], 'end_time' => $dot['end_time'], 'day_type' => $dot['day_type']);
        }

        $start_date = $this->start_date;
        $end_date   = $this->end_date;
        $cutoff_number = $this->cutoff_period->getCutoffNumber();
        $excluded_employee_deduction = $this->excluded_employee_deduction;
        $excluded_employee_deduction[$e->getId()] = array_unique($excluded_employee_deduction[$e->getId()]);
        if (!$e) {
            return false;
        }

        G_Attendance_Helper::finalizeAttendance($e, $start_date, $end_date);

        $p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);
        if (!$p) {
            $p = new G_Payslip;
        }

        $p->setPeriod($start_date, $end_date);
        $p->setEmployee($e);

        $a = G_Attendance_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);

        if (!$a) {
            /*
            $error = new G_Payslip_Error;
            $error->setMessage($e->getEmployeeCode() .': '. $e->getName() .' has no attendance');
            $error->setEmployeeId($e->getId());
            $error->setErrorTypeId(G_Payslip_Error::ERROR_NO_ATTENDANCE);
            $error->setDateLogged(Tools::getGmtDate('Y-m-d'));
            $error->setTimeLogged(Tools::getGmtDate('H:i:s'));
            $error->addError();
            */
            return false;
        }

        $is_emp_inactive = 0;
        $is_emp_inactive = G_Employee_Status_History_Helper::findInactiveEmployeeByIdAndInBetweenDates($e, $start_date, $end_date);
        $count_present_days = G_Attendance_Helper::countPresentDays($a);
        if($count_present_days<1)
        return false;

        $employee_status = $e->getEmployeeStatusId();

        $is_disable_computation = false;
        if ($is_emp_inactive > 0 && $count_present_days <= 0) {
            $is_disable_computation = true;
        } elseif ($employee_status == 5 && $count_present_days <= 0) {
            $is_disable_computation = true;
        }

        $is_first_period = true;


        $s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($e, $end_date);
        //var_dump($s);
        $history = G_Employee_Basic_Salary_History_Finder::findByEmployeeId($s->employee_id);
        $historyCtr = 0;
        
        if ($s->frequency_id != $this->frequency_id) {
            return false;
        }

        foreach ($history as $key => $value) {
            if ($historyCtr == 1) {
                $previous_salary =  $value->basic_salary;
                // echo "<pre>";
                // var_dump($value);
                // echo "</pre>";
            }
            $historyCtr++;
        }

        if (!$s) {

            if (empty($s) && $e->getEmployeeStatusId() == 2) {

                $s = G_Employee_Basic_Salary_History_Finder::findByResignedEmployeeAndDate($e, $end_date);

                if (!$s) {
                    $s = new G_Employee_Basic_Salary_History;
                    $s->setBasicSalary(0);
                }
            } else {

                $s = new G_Employee_Basic_Salary_History;
                $s->setBasicSalary(0);
            }
        }

        $position = G_Employee_Job_History_Finder::findByEmployeeAndDate($e, $end_date);
        if ($position) {
            $position_name = $position->getName();
            $labels[] = new Payslip_Label('Position', $position_name, 'position');
        }

        if ($is_disable_computation) {
            $salary_amount = 0;
        } else {
            $salary_amount = $s->getBasicSalary();
        }


        $salary_type   = $s->getType();

        $hour = G_Payslip_Hour_Finder::findByAttendanceFinder($a);
        $rate = G_Payslip_Percentage_Rate_Finder::findRateBySalaryType($salary_type);
        if (!$rate) {
            $rate = G_Payslip_Percentage_Rate_Finder::findDefault();
        }
        //here

        $c = Payslip_Amount_Calculator_Factory::get();
        $c->setPayslipHour($hour);
        $c->setPayslipRate($rate);

        $per_day  = 0;
        $per_hour = 0;
        $total_ot_amount = 0;

        //Employee number of days per year
        $working_days = $e->getYearWorkingDays();
        if ($working_days <= 0) {
            $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS);
            $working_days = $sv->getVariableValue();
        }
        //for test

        $cutoffa = $this->cutoff_period->getStartDate();
        $cutoffb = $this->cutoff_period->getEndDate();
        $startnewsalary = $s->getStartDate();
        $pperiod  = G_Settings_Pay_Period_Finder::findDefault();
        $payperiod_date = $pperiod->getCutOff();

        if ($payperiod_date == "26-10,11-25") {
            if (($startnewsalary >= $cutoffa) && ($startnewsalary <= $cutoffb)) {
                $check2631 = true;
            }
        }

        //bypass check2631 checking - reason:cause error
         $check2631 = false;

        if ($check2631) {

            $working_days = $e->getYearWorkingDays();
            $prev_employee_monthly_rate = $previous_salary;
            if ($prev_employee_monthly_rate == 0) {

                $prev_employee_monthly_rate = $salary_amount;
                $prev_per_day               = ($salary_amount * 12) / $working_days;
                $prev_monthly_rate_daily    = $salary_amount;
                $prev_per_hour = $prev_per_day / 8;
                $prev_ceta_sea_employee_rate = $previous_salary / ($working_days / 12);
            } else {

                $prev_employee_monthly_rate = $previous_salary;
                $prev_per_day               = ($previous_salary * 12) / $working_days;
                $prev_monthly_rate_daily    = $previous_salary;
                $prev_per_hour = $prev_per_day / 8;
                $prev_ceta_sea_employee_rate = $previous_salary / ($working_days / 12);
            }
        }

        //for test

        switch ($salary_type):
            case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:
                $c->setSalaryPerMonthAmount($salary_amount);
                $labels[] = new Payslip_Label('Monthly Rate', $salary_amount, 'monthly_rate');
                $labels[] = new Payslip_Label('Salary Type', $salary_type, "salary_type");
                $labels[] = new Payslip_Label('Salary Rate', $salary_amount, 'salary_rate');

                $employee_monthly_rate = $salary_amount;
                $per_day               = ($salary_amount * 12) / $working_days;
                $monthly_rate_daily    = $salary_amount;
                $c->setSalaryPerDayAmount($per_day);

                $per_hour = $per_day / 8;
                $c->setSalaryPerHourAmount($per_hour);

                $labels[] = new Payslip_Label('Hourly Rate', $per_hour, 'hourly_rate');
                $labels[] = new Payslip_Label('Daily Rate', $per_day, 'daily_rate');

                $ceta_sea_employee_rate = $salary_amount / ($working_days / 12);
                break;

            case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:
                $monthly_rate_daily    = ($salary_amount * $working_days) / 12;
                $employee_monthly_rate = $monthly_rate_daily;
                $per_day            = ($monthly_rate_daily * 12) / $working_days;
                $per_hour           = $per_day / 8;

                $c->setSalaryPerMonthAmount($monthly_rate_daily);
                //$c->setSalaryPerMonthAmount($monthly_rate_daily);
                $c->setSalaryPerDayAmount($per_day);
                $c->setSalaryPerHourAmount($per_hour);

                $labels[] = new Payslip_Label('Daily Rate', $salary_amount, 'daily_rate');
                $labels[] = new Payslip_Label('Salary Type', $salary_type, "salary_type");
                $labels[] = new Payslip_Label('Salary Rate', $salary_amount, 'salary_rate');
                $labels[] = new Payslip_Label('Hourly Rate', $per_hour, 'hourly_rate');

                $ceta_sea_employee_rate = $monthly_rate_daily / ($working_days / 12);
                break;
        /*case G_Employee_Basic_Salary_History::SALARY_TYPE_HOURLY:
				$c->setSalaryPerMonthAmount(0);
				$c->setSalaryPerDayAmount(0);
				$c->setSalaryPerHourAmount($salary_amount);
			break;*/
        endswitch;

        if ($is_disable_computation) {
            $present_days = 0;
        } else {
            $present_days = G_Attendance_Helper::countPresentDays($a);
        }

        $labels[] = new Payslip_Label('Present Days', $present_days, 'present_days');

        /*$actual_present_days = G_Attendance_Helper::countActualPresentDays($a);
        $labels[] = new Payslip_Label('Actual Present Days', $actual_present_days, 'actual_present_days');   */

        switch ($salary_type):
            case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:
                $present_days_with_pay = G_Attendance_Helper::countPresentDaysWithPay($a);
                break;
            case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:
                $present_days_with_pay = G_Attendance_Helper::countPresentRegularDaysOnlyWithPay($a);
                break;
        endswitch;

        if ($is_disable_computation) {
            $present_days_with_pay = 0;
        }

        $labels[] = new Payslip_Label('Present Days with Pay', $present_days_with_pay, 'present_days_with_pay');


        //$absent_days_with_pay = G_Attendance_Helper::countAbsentDaysWithPay($a);
        //	$labels[] = new Payslip_Label('Absent Days with Pay', $absent_days_with_pay, 'absent_days_with_pay');
        //$ob_days = G_Attendance_Helper::countOBDays($a);
        //    $labels[] = new Payslip_Label('OB Days', $ob_days, 'ob_days');
        //test

        // $monthly_rate_daily    = ($salary_amount * $working_days) / 12;               
        //        $employee_monthly_rate = $monthly_rate_daily;
        //        $per_day            = ($monthly_rate_daily * 12) / $working_days;
        //        $per_hour           = $per_day / 8;

        //        $c->setSalaryPerMonthAmount($monthly_rate_daily);                
        //        //$c->setSalaryPerMonthAmount($monthly_rate_daily);
        //        $c->setSalaryPerDayAmount($per_day);
        //        $c->setSalaryPerHourAmount($per_hour);

        //        $labelstest[] = new Payslip_Label('Daily Rate', $salary_amount, 'daily_rate');
        //        $labelstest[] = new Payslip_Label('Salary Type', $salary_type, "salary_type");
        //        $labelstest[] = new Payslip_Label('Salary Rate', $salary_amount, 'salary_rate'); 
        //        $labelstest[] = new Payslip_Label('Hourly Rate', $per_hour, 'hourly_rate');
        //        echo "<pre>";
        //        var_dump($labelstest);
        //        echo "</pre>";
        //test
        //Absent
        if (in_array('absent_amount', $excluded_employee_deduction[$e->getId()])) {
            $absent_days_without_pay = 0;
            $absent_amount = 0;
        } else {
            if ($salary_type == G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY) {
                $absent_days_without_pay = G_Attendance_Helper::countDaysWithoutPay($a);

                $prev_absent_days_without_pay =  G_Attendance_Helper::countPrevDaysWithoutPay($a);
                $cut_absent_days_without_pay = G_Attendance_Helper::countCutDaysWithoutPay($a);



                $cut_absent_amount = (float) $c->computeCutAbsent($absent_days_without_pay - $prev_absent_days_without_pay, $per_day);

                $absent_amount = (float) $c->computeAbsent($absent_days_without_pay);

                $prev_absent_amount = (float) $c->computePrevAbsent($prev_absent_days_without_pay, $prev_per_day);

                if ($check2631) {
                    $absent_amount = $cut_absent_amount + $prev_absent_amount;
                }
            } else if ($salary_type == G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY) {
                $absent_days_without_pay = G_Attendance_Helper::countDaysWithoutPay($a);
                $absent_amount = 0;
            }
        }

        if ($is_disable_computation) {
            $absent_days_without_pay  = 0;
            $absent_amount            = 0;
        }

        $labels[] = new Payslip_Label('Absent Days without Pay', $absent_days_without_pay, 'absent_days_without_pay');
        $labels[] = new Payslip_Label('Absent Amount without Pay', $absent_amount, 'absent_amount');

        $suspended_days = G_Attendance_Helper::countSuspendedDays($a);
        $labels[] = new Payslip_Label('Suspended Days', $suspended_days, 'suspended_days');
        $suspended_amount = (float) $c->computeSuspension($suspended_days);
        $labels[] = new Payslip_Label('Suspended Amount', $suspended_amount, 'suspended_amount');

        if (in_array('undertime_amount', $excluded_employee_deduction[$e->getId()])) {
            $undertime_hours  = 0;
            $undertime_amount = 0;
        } else {
            $undertime_hours  = $hour->getRegularUndertime();
            $undertime_amount = (float) $c->computeRegularUndertime();


            $prev_undertime_hours = G_Attendance_Helper::countPrevUndertimeHours($a);
            $cut_undertime_hours = G_Attendance_Helper::countCutUndertimeHours($a);

            $prev_undertime_amount = (float) $c->computePrevRegularUndertime($prev_undertime_hours, $prev_per_hour);
            $cut_undertime_amount = (float) $c->computeCutRegularUndertime($cut_undertime_hours, $per_hour);
        }

        if ($check2631) {
            $undertime_amount = $prev_undertime_amount + $cut_undertime_amount;
        }

        // echo "<br>";
        // echo $cut_undertime_hours;

        // var_dump($hour) ;
        // echo "---";
        // echo $prev_per_hour;
        // echo "<hr>";

        // echo $prev_undertime_amount;
        // echo "<br>";
        // echo $cut_undertime_amount;

        if ($is_disable_computation) {
            $undertime_hours  = 0;
            $undertime_amount = 0;
        }


        $labels[] = new Payslip_Label('Undertime Hours', $undertime_hours, 'undertime_hours');
        $labels[] = new Payslip_Label('Undertime Amount', $undertime_amount, 'undertime_amount');

        if (in_array('late_amount', $excluded_employee_deduction[$e->getId()])) {
            $late_hours  = 0;
            $late_amount = 0;
        } else {
            $late_hours  = $hour->getRegularLate();
            $late_amount = (float) $c->computeRegularLate();

            // $cut_late_amount =  (float) $c->computePrevRegularLate($prev_per_hour);
            // $late_amount = $cut_late_amount;

            $prev_late_hours = G_Attendance_Helper::countPrevRegularLateHours($a);
            $cut_late_hours = G_Attendance_Helper::countCutRegularLateHours($a);

            $prev_late_amount = (float) $c->computePrevRegularLate($prev_late_hours, $prev_per_hour);
            $cut_late_amount = (float) $c->computeCutRegularLate($cut_late_hours, $per_hour);
        }

        if ($check2631) {
            $late_amount = $prev_late_amount + $cut_late_amount;
        }

        if ($is_disable_computation) {
            $late_hours  = 0;
            $late_amount = 0;
        }

        $labels[] = new Payslip_Label('Late Hours', $late_hours, 'late_hours');
        $labels[] = new Payslip_Label('Late Amount', $late_amount, 'late_amount');

        //LEAVE WITH PAY
        $total_days_leave_with_pay = G_Attendance_Helper::countDaysLeaveWithPay($a);
        if ($is_disable_computation) {
            $total_days_leave_with_pay = 0;
        }

        $labels[] = new Payslip_Label('Days Leave with pay', $total_days_leave_with_pay, 'days_leave_with_pay');

        //REST DAY PRESENT
        $total_present_rest_day    = G_Attendance_Helper::countDaysPresentRestDay($a);
        if ($is_disable_computation) {
            $total_present_rest_day = 0;
        }
        $labels[] = new Payslip_Label('Days Rest Day Is Present', $total_present_rest_day, 'days_present_rest_day');

        //HOLIDAY DAY PRESENT
        $total_present_holiday   = G_Attendance_Helper::countDaysPresentHoliday($a);
        if ($is_disable_computation) {
            $total_present_holiday = 0;
        }
        $labels[] = new Payslip_Label('Days Holiday Present', $total_present_holiday, 'days_present_holiday');

        // REGULAR

        $regular_hours = G_Attendance_Helper::getTotalRegularHours($a);
        // echo "<pre>";
        // var_dump($a);
        // echo "</pre>";
        if ($is_disable_computation) {
            $regular_hours = 0;
        }
        $labels[] = new Payslip_Label('Regular Hours', $regular_hours, 'regular_hours');
        $regular_amount = G_Payslip_Helper::computeRegularAmount($a, $per_day, $per_hour);
        if ($is_disable_computation) {
            $regular_amount = 0;
        }
        $labels[] = new Payslip_Label('Regular Amount', $regular_amount, 'regular_amount');

        $regular_ot_hours = G_Attendance_Helper::getTotalOvertimeHours($a);

        $cut_regular_ot_hours = G_Attendance_Helper::getCutTotalOvertimeHours($a);
        $prev_regular_ot_hours = G_Attendance_Helper::getPrevTotalOvertimeHours($a);


        //testtt

        //check Mandated Payroll Rates for Weekly employees status
        $sv = G_Sprint_Variables_Finder::findByVariableName(G_Sprint_Variables::FIELD_DEFAULT_BIMONTHLY_PAYROLL_RATES);

        $mandated_status = 'Enable';

        if ($sv) {
            $mandated_status = $sv->getValue();
        }

        //echo $regular_ot_hours; 
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $regular_ot_amount = $regular_ot_hours * $special_rate;
        } else {
            $regular_ot_amount = G_Payslip_Helper::computeRegularOvertimeAmount($a, $rate, $per_day, $per_hour, $mandated_status);

            $cut_regular_ot_amount = G_Payslip_Helper::computeCutRegularOvertimeAmount($a, $rate, $per_day, $per_hour, $mandated_status);
            $prev_regular_ot_amount = G_Payslip_Helper::computePrevRegularOvertimeAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status);
        }

        if ($check2631) {
            $regular_ot_amount = $cut_regular_ot_amount + $prev_regular_ot_amount;
        }

        if ($is_disable_computation) {
            $regular_ot_amount = 0;
        }

        $labels[] = new Payslip_Label('Regular OT Amount', $regular_ot_amount, 'regular_ot_amount');

        $regular_ns_hours = G_Attendance_Helper::getTotalNightShiftHours($a);
        if ($is_disable_computation) {
            $regular_ns_hours = 0;
        }
        $labels[] = new Payslip_Label('Regular NS Hours', $regular_ns_hours, 'regular_ns_hours');
        $regular_ns_amount = G_Payslip_Helper::computeRegularNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);


        $cut_regular_ns_amount = G_Payslip_Helper::computeCutRegularNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);
        $prev_regular_ns_amount = G_Payslip_Helper::computePrevRegularNightShiftAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status);

        if ($check2631) {
            $regular_ns_amount = $cut_regular_ns_amount + $prev_regular_ns_amount;
        }

        if ($is_disable_computation) {
            $regular_ns_amount = 0;
        }
        $labels[] = new Payslip_Label('Regular NS Amount', $regular_ns_amount, 'regular_ns_amount');

        $regular_ns_ot_hours = G_Attendance_Helper::getTotalNightShiftOvertimeHours($a);
        if ($is_disable_computation) {
            $regular_ns_ot_hours = 0;
        }
        $labels[] = new Payslip_Label('Regular NS OT Hours', $regular_ns_ot_hours, 'regular_ns_ot_hours');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $regular_ns_ot_amount = $regular_ns_ot_hours * $special_rate;
        } else {
            $regular_ns_ot_amount = G_Payslip_Helper::computeRegularOvertimeNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);



            $cut_regular_ns_ot_amount = G_Payslip_Helper::computeCutRegularOvertimeNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);
            $prev_regular_ns_ot_amount =  G_Payslip_Helper::computePrevRegularOvertimeNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);
        }
        if ($check2631) {
            $regular_ns_ot_amount = $cut_regular_ns_ot_amount + $prev_regular_ns_ot_amount;
        }
        if ($is_disable_computation) {
            $regular_ns_ot_amount = 0;
        }

        $total_reg_ot_hours = 0;
        foreach ($a as $ad) {
            if ($ad->isPresent() && !$ad->isRestday() && !$ad->isHoliday()) {
                $tt = $ad->getTimesheet();
                $ot_reg_hours = $tt->getRegularOvertimeHours() + $tt->getRegularOvertimeExcessHours();
                $total_reg_ot_hours += $ot_reg_hours;
            }
        }
        //echo $total_reg_ot_hours;
        $regular_ot_hours += $regular_ns_ot_hours;

        if ($is_disable_computation) {
            $regular_ns_ot_amount = 0;
            $total_reg_ot_hours   = 0;
            $regular_ot_hours     = 0;
        }

        $labels[] = new Payslip_Label('Regular OT Hours', $total_reg_ot_hours, 'regular_ot_hours');

        $labels[] = new Payslip_Label('Regular NS OT Amount', $regular_ns_ot_amount, 'regular_ns_ot_amount');
        $total_ot_amount += $regular_ot_amount + $regular_ns_ot_amount;

        if ($is_disable_computation) {
            $total_ot_amount = 0;
        }

        // REST DAY
        $restday_hours = G_Attendance_Helper::getTotalRestDayHours($a, $custom_ot);

        if ($is_disable_computation) {
            $restday_hours = 0;
        }

        $labels[] = new Payslip_Label('Restday Hours', $restday_hours, 'restday_hours');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $restday_amount = $restday_hours * $special_rate;
        } else {
            $restday_amount = G_Payslip_Helper::computeRestDayAmount($a, $rate, $per_day, $per_hour, $custom_ot, $mandated_status); //$c->computeRestDay();

            $prev_restday_amount = G_Payslip_Helper::computePrevRestDayAmount($a, $rate, $prev_per_day, $prev_per_hour, $custom_ot, $mandated_status);
            $cut_restday_amount =  G_Payslip_Helper::computeCutRestDayAmount($a, $rate, $per_day, $per_hour, $custom_ot, $mandated_status);
        }

        if ($check2631) {
            $restday_amount = $prev_restday_amount + $cut_restday_amount;
        }

        if ($is_disable_computation) {
            $restday_amount = 0;
        }

        $labels[] = new Payslip_Label('Restday Amount', $restday_amount, 'restday_amount');

        $restday_ot_hours = G_Attendance_Helper::getTotalRestDayOvertimeHours($a);

        if ($is_disable_computation) {
            $restday_ot_hours = 0;
        }

        $labels[] = new Payslip_Label('Restday OT Hours', $restday_ot_hours, 'restday_ot_hours');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $restday_ot_amount = $restday_ot_hours * $special_rate;
        } else {
            $restday_ot_amount = G_Payslip_Helper::computeRestDayOvertimeAmount($a, $rate, $per_day, $per_hour, $mandated_status);

            $prev_restday_ot_amount = G_Payslip_Helper::computePrevRestDayOvertimeAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status);
            $cut_restday_ot_amount = G_Payslip_Helper::computeCutRestDayOvertimeAmount($a, $rate, $per_day, $per_hour, $mandated_status);
        }
        if ($check2631) {
            $restday_ot_amount = $prev_restday_ot_amount + $cut_restday_ot_amount;
        }

        if ($is_disable_computation) {
            $restday_ot_amount = 0;
        }

        $labels[] = new Payslip_Label('Restday OT Amount', $restday_ot_amount, 'restday_ot_amount');

        $restday_ns_hours = G_Attendance_Helper::getTotalRestDayNightShiftHours($a);
        if ($is_disable_computation) {
            $restday_ns_hours = 0;
        }
        $labels[] = new Payslip_Label('Restday NS Hours', $restday_ns_hours, 'restday_ns_hours');
        //
        $restday_ns_amount = G_Payslip_Helper::computeRestDayNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status); //$c->computeRegularNightShift();

        $prev_restday_ns_amount = G_Payslip_Helper::computePrevRestDayNightShiftAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status);
        $cut_restday_ns_amount = G_Payslip_Helper::computeCutRestDayNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);

        if ($check2631) {
            $restday_ns_amount = $prev_restday_ns_amount + $cut_restday_ns_amount;
        }
        if ($is_disable_computation) {
            $restday_ns_amount = 0;
        }

        $labels[] = new Payslip_Label('Restday NS Amount', $restday_ns_amount, 'restday_ns_amount');

        $restday_ns_ot_hours = G_Attendance_Helper::getTotalRestDayNightShiftOvertimeHours($a);


        if ($is_disable_computation) {
            $restday_ns_ot_hours = 0;
        }

        $labels[] = new Payslip_Label('Restday NS OT Hours', $restday_ns_ot_hours, 'restday_ns_ot_hours');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $restday_ns_ot_amount = $restday_ns_ot_hours * $special_rate;
        } else {
            $restday_ns_ot_amount = G_Payslip_Helper::computeRestDayOvertimeNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status); //$c->computeRestDayNightShiftOvertime();
            $prev_restday_ns_ot_amount =   G_Payslip_Helper::computePrevRestDayOvertimeNightShiftAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status);
            $cut_restday_ns_ot_amount =  G_Payslip_Helper::computeCutRestDayOvertimeNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);
        }
        if ($check2631) {
            $restday_ns_ot_amount = $prev_restday_ns_ot_amount + $cut_restday_ns_ot_amount;
        }
        if ($is_disable_computation) {
            $restday_ns_ot_amount = 0;
        }

        $labels[] = new Payslip_Label('Restday NS OT Amount', $restday_ns_ot_amount, 'restday_ns_ot_amount');

        $total_ot_amount += $restday_ot_amount + $restday_ns_ot_amount;
        if ($is_disable_computation) {
            $total_ot_amount = 0;
        }

        // REST DAY SPECIAL
        $restday_special_hours = G_Attendance_Helper::getTotalHolidaySpecialRestdayHours($a, $custom_ot);

        if ($is_disable_computation) {
            $restday_special_hours = 0;
        }
        $labels[] = new Payslip_Label('Rest Day Special Hours', $restday_special_hours, 'restday_special_hours');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $restday_special_amount = $restday_special_hours * $special_rate;
        } else {
            $restday_special_amount = G_Payslip_Helper::computeRestDaySpecialAmount($a, $rate, $per_day, $per_hour, $custom_ot, $mandated_status);

            $prev_restday_special_amount = G_Payslip_Helper::computePrevRestDaySpecialAmount($a, $rate, $prev_per_day, $prev_per_hour, $custom_ot, $mandated_status);
            $cut_restday_special_amount = G_Payslip_Helper::computeCutRestDaySpecialAmount($a, $rate, $per_day, $per_hour, $custom_ot, $mandated_status);
        }
        if ($check2631) {
            $restday_special_amount = $prev_restday_special_amount + $cut_restday_special_amount;
        }
        if ($is_disable_computation) {
            $restday_special_amount = 0;
        }

        $labels[] = new Payslip_Label('Rest Day Special Amount', $restday_special_amount, 'restday_special_amount');

        $restday_special_ot_hours = G_Attendance_Helper::getTotalHolidaySpecialRestdayOvertimeHours($a);
        if ($is_disable_computation) {
            $restday_special_ot_hours = 0;
        }
        $labels[] = new Payslip_Label('Rest Day Special OT Hours', $restday_special_ot_hours, 'restday_special_ot_hours');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $restday_special_ot_amount = $restday_special_ot_hours * $special_rate;
        } else {
            $restday_special_ot_amount = G_Payslip_Helper::computeRestDaySpecialOvertimeAmount($a, $rate, $per_day, $per_hour, $mandated_status);

            $prev_restday_special_ot_amount = G_Payslip_Helper::computePrevRestDaySpecialOvertimeAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status);
            $cut_restday_special_ot_amount = G_Payslip_Helper::computeCutRestDaySpecialOvertimeAmount($a, $rate, $per_day, $per_hour, $mandated_status);
        }

        if ($check2631) {
            $restday_special_ot_amount = $prev_restday_special_ot_amount + $cut_restday_special_ot_amount;
        }
        if ($is_disable_computation) {
            $restday_special_ot_amount = 0;
        }

        $labels[] = new Payslip_Label('Rest Day Special OT Amount', $restday_special_ot_amount, 'restday_special_ot_amount');

        $restday_special_ns_hours = G_Attendance_Helper::getTotalHolidaySpecialRestdayNightShiftHours($a);

        if ($is_disable_computation) {
            $restday_special_ns_hours = 0;
        }

        $labels[] = new Payslip_Label('Rest Day Special NS Hours', $restday_special_ns_hours, 'restday_special_ns_hours');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $restday_special_ns_amount = $restday_special_ns_hours * $special_rate;
        } else {
            $restday_special_ns_amount = G_Payslip_Helper::computeRestDaySpecialNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status); //$c->computeRegularNightShift();

            $prev_restday_special_ns_amount = G_Payslip_Helper::computePrevRestDaySpecialNightShiftAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status);
            $cut_restday_special_ns_amount =  G_Payslip_Helper::computeCutRestDaySpecialNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);
        }
        if ($check2631) {
            $restday_special_ns_amount = $prev_restday_special_ns_amount + $cut_restday_special_ns_amount;
        }
        if ($is_disable_computation) {
            $restday_special_ns_amount = 0;
        }

        $labels[] = new Payslip_Label('Rest Day Special NS Amount', $restday_special_ns_amount, 'restday_special_ns_amount');

        $restday_special_ns_ot_hours = G_Attendance_Helper::getTotalHolidaySpecialRestDayNightShiftOvertimeHours($a);
        if ($is_disable_computation) {
            $restday_special_ns_ot_hours = 0;
        }
        $labels[] = new Payslip_Label('Rest Day Special NS OT Hours', $restday_special_ns_ot_hours, 'restday_special_ns_ot_hours');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $restday_special_ns_ot_amount = $restday_special_ns_ot_hours * $special_rate;
        } else {
            //startdito
            $restday_special_ns_ot_amount = G_Payslip_Helper::computeRestDaySpecialOvertimeNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);

            $prev_restday_special_ns_ot_amount = G_Payslip_Helper::computePrevRestDaySpecialOvertimeNightShiftAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status);
            $cut_restday_special_ns_ot_amount = G_Payslip_Helper::computeCutRestDaySpecialOvertimeNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);
        }
        if ($check2631) {
            $restday_special_ns_ot_amount = $prev_restday_special_ns_ot_amount + $cut_restday_special_ns_ot_amount;
        }
        if ($is_disable_computation) {
            $restday_special_ns_ot_amount = 0;
        }

        $labels[] = new Payslip_Label('Rest Day Special NS OT Amount', $restday_special_ns_ot_amount, 'restday_special_ns_ot_amount');

        $total_ot_amount += $restday_special_ot_amount + $restday_special_ns_ot_amount;

        // REST DAY LEGAL
        $restday_legal_hours = G_Attendance_Helper::getTotalHolidayLegalRestdayHours($a,$custom_ot);
        //echo $restday_legal_hours;
        if ($is_disable_computation) {
            $restday_legal_hours = 0;
        }

        $labels[] = new Payslip_Label('Rest Day Legal Hours', $restday_legal_hours, 'restday_legal_hours');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $restday_legal_amount = $restday_legal_hours * $special_rate;
        } else {
            $restday_legal_amount = G_Payslip_Helper::computeRestDayLegalAmount($a, $rate, $per_day, $per_hour, $mandated_status);
            //herena
            $prev_restday_legal_amount = G_Payslip_Helper::computePrevRestDayLegalAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status);
            $cut_restday_legal_amount = G_Payslip_Helper::computeCutRestDayLegalAmount($a, $rate, $per_day, $per_hour, $mandated_status);
        }

        if ($check2631) {
            $restday_legal_amount = $prev_restday_legal_amount + $cut_restday_legal_amount;
        }
        if ($is_disable_computation) {
            $restday_legal_amount = 0;
        }

        $labels[] = new Payslip_Label('Rest Day Legal Amount', $restday_legal_amount, 'restday_legal_amount');

        $restday_legal_ot_hours = G_Attendance_Helper::getTotalHolidayLegalRestdayOvertimeHours($a);

        if ($is_disable_computation) {
            $restday_legal_ot_hours = 0;
        }

        $labels[] = new Payslip_Label('Rest Day Legal OT Hours', $restday_legal_ot_hours, 'restday_legal_ot_hours');

        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $restday_legal_ot_amount = $restday_legal_ot_hours * $special_rate;
        } else {

            $restday_legal_ot_amount = G_Payslip_Helper::computeRestDayLegalOvertimeAmount($a, $rate, $per_day, $per_hour, $mandated_status);

            $prev_restday_legal_ot_amount = G_Payslip_Helper::computePrevRestDayLegalOvertimeAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status);
            $cut_restday_legal_ot_amount = G_Payslip_Helper::computeCutRestDayLegalOvertimeAmount($a, $rate, $per_day, $per_hour, $mandated_status);
        }
        if ($check2631) {
            $restday_legal_ot_amount = $prev_restday_legal_ot_amount + $cut_restday_legal_ot_amount;
        }
        if ($is_disable_computation) {

            $restday_legal_ot_amount = 0;
        }

        $labels[] = new Payslip_Label('Rest Day Legal OT Amount', $restday_legal_ot_amount, 'restday_legal_ot_amount');

        $restday_legal_ns_hours = G_Attendance_Helper::getTotalHolidayLegalRestdayNightShiftHours($a);
        if ($is_disable_computation) {
            $restday_legal_ns_hours = 0;
        }
        //herena

        $labels[] = new Payslip_Label('Rest Day Legal NS Hours', $restday_legal_ns_hours, 'restday_legal_ns_hours');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $restday_legal_ns_amount = $restday_legal_ns_hours * $special_rate;
        } else {
            $restday_legal_ns_amount = G_Payslip_Helper::computeRestDayLegalNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status); //$c->computeRegularNightShift();

            $prev_restday_legal_ns_amount = G_Payslip_Helper::computePrevRestDayLegalNightShiftAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status);
            $cut_restday_legal_ns_amount = G_Payslip_Helper::computeCutRestDayLegalNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);
            //last
        }
        if ($check2631) {
            $restday_legal_ns_amount = $prev_restday_legal_ns_amount + $cut_restday_legal_ns_amount;
        }

        if ($is_disable_computation) {
            $restday_legal_ns_amount = 0;
        }

        $labels[] = new Payslip_Label('Rest Day Legal NS Amount', $restday_legal_ns_amount, 'restday_legal_ns_amount');
        //last

        $restday_legal_ns_ot_hours = G_Attendance_Helper::getTotalHolidayLegalRestDayNightShiftOvertimeHours($a);

        if ($is_disable_computation) {
            $restday_legal_ns_ot_hours = 0;
        }
        $labels[] = new Payslip_Label('Rest Day Legal NS OT Hours', $restday_legal_ns_ot_hours, 'restday_legal_ns_ot_hours');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $restday_legal_ns_ot_amount = $restday_legal_ns_ot_hours * $special_rate;
        } else {
            $restday_legal_ns_ot_amount = G_Payslip_Helper::computeRestDayLegalOvertimeNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);

            $prev_restday_legal_ns_ot_amount = G_Payslip_Helper::computePrevRestDayLegalOvertimeNightShiftAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status);
            $cut_restday_legal_ns_ot_amount = G_Payslip_Helper::computeCutRestDayLegalOvertimeNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);
        }


        if ($check2631) {
            $restday_legal_ns_ot_amount = $prev_restday_legal_ns_ot_amount + $cut_restday_legal_ns_ot_amount;
        }
        if ($is_disable_computation) {
            $restday_legal_ns_ot_amount = 0;
        }

        $labels[] = new Payslip_Label('Rest Day Legal NS OT Amount', $restday_legal_ns_ot_amount, 'restday_legal_ns_ot_amount');

        $total_ot_amount += $restday_legal_ot_amount + $restday_legal_ns_ot_amount;

        // HOLIDAY SPECIAL
        $holiday_special_hours = G_Attendance_Helper::getTotalHolidaySpecialHours($a, $custom_ot); //$hour->getHolidaySpecial();             


        // var_dump($holiday_special_hours); exit;
        if ($is_disable_computation) {
            $holiday_special_hours = 0;
        }

        $labels[] = new Payslip_Label('Holiday Special Hours', $holiday_special_hours, 'holiday_special_hours');
        switch ($salary_type):
            case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:
                $holiday_special_amount = G_Payslip_Helper::computeSpecialAmount($a, $rate, $per_day, $per_hour, $custom_ot, $custom_ot_disapproved, $mandated_status);

                $prev_holiday_special_amount = G_Payslip_Helper::computePrevSpecialAmount($a, $rate, $prev_per_day, $prev_per_hour, $custom_ot, $custom_ot_disapproved, $mandated_status);
                $cut_holiday_special_amount = G_Payslip_Helper::computeCutSpecialAmount($a, $rate, $per_day, $per_hour, $custom_ot, $custom_ot_disapproved, $mandated_status);
                break;
            case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:
                $holiday_special_amount = G_Payslip_Helper::computeSpecialAmount($a, $rate, $per_day, $per_hour, $custom_ot, $custom_ot_disapproved, $mandated_status);
                break;
        endswitch;
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $holiday_special_amount = $holiday_special_hours * $special_rate;
        }


        if ($check2631) {
            $holiday_special_amount = $prev_holiday_special_amount + $cut_holiday_special_amount;
        }
        if ($is_disable_computation) {
            $holiday_special_amount = 0;
        }

        $labels[] = new Payslip_Label('Holiday Special Amount', $holiday_special_amount, 'holiday_special_amount');

        $holiday_special_ot_hours = G_Attendance_Helper::getTotalHolidaySpecialOvertimeHours($a); //$hour->getHolidaySpecialOvertime();

        if ($is_disable_computation) {
            $holiday_special_ot_hours = 0;
        }


        $labels[] = new Payslip_Label('Holiday Special OT Hours', $holiday_special_ot_hours, 'holiday_special_ot_hours');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $holiday_special_ot_amount = $holiday_special_ot_hours * $special_rate;
        } else {
            $holiday_special_ot_amount = G_Payslip_Helper::computeSpecialOvertimeAmount($a, $rate, $per_day, $per_hour, $mandated_status); //$c->computeHolidaySpecialOvertime();

            $prev_holiday_special_ot_amount =  G_Payslip_Helper::computePrevSpecialOvertimeAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status);
            $cut_holiday_special_ot_amount =  G_Payslip_Helper::computeCutSpecialOvertimeAmount($a, $rate, $per_day, $per_hour, $mandated_status);
        }

        // echo $cut_holiday_special_ot_amount; 
        if ($check2631) {
            $holiday_special_ot_amount =  $prev_holiday_special_ot_amount + $cut_holiday_special_ot_amount;
        }

        if ($is_disable_computation) {
            $holiday_special_ot_amount = 0;
        }

        $labels[] = new Payslip_Label('Holiday Special OT Amount', $holiday_special_ot_amount, 'holiday_special_ot_amount');

        $holiday_special_ns_hours = G_Attendance_Helper::getTotalHolidaySpecialNightShiftHours($a);
        if ($is_disable_computation) {
            $holiday_special_ns_hours = 0;
        }
        $labels[] = new Payslip_Label('Holiday Special NS Hours', $holiday_special_ns_hours, 'holiday_special_ns_hours');
        $holiday_special_ns_amount = G_Payslip_Helper::computeSpecialNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);

        $prev_holiday_special_ns_amount = G_Payslip_Helper::computePrevSpecialNightShiftAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status);
        $cut_holiday_special_ns_amount = G_Payslip_Helper::computeCutSpecialNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);
     
        if ($check2631) {
            $holiday_special_ns_amount = $prev_holiday_special_ns_amount + $cut_holiday_special_ns_amount;
        }
        if ($is_disable_computation) {
            $holiday_special_ns_amount = 0;
        }

        $labels[] = new Payslip_Label('Holiday Special NS Amount', $holiday_special_ns_amount, 'holiday_special_ns_amount');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $holiday_special_ns_ot_hours = $holiday_special_ns_amount * $special_rate;
        } else {
            $holiday_special_ns_ot_hours = G_Attendance_Helper::getTotalHolidaySpecialNightShiftOvertimeHours($a);
        }

        if ($is_disable_computation) {
            $holiday_special_ns_ot_hours = 0;
        }
        $labels[] = new Payslip_Label('Holiday Special NS OT Hours', $holiday_special_ns_ot_hours, 'holiday_special_ns_ot_hours');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $holiday_special_ns_ot_amount = $holiday_special_ns_ot_hours * $special_rate;
        } else {
            $holiday_special_ns_ot_amount = G_Payslip_Helper::computeSpecialOvertimeNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);

            $prev_holiday_special_ns_ot_amount = G_Payslip_Helper::computePrevSpecialOvertimeNightShiftAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status);
            $cut_holiday_special_ns_ot_amount = G_Payslip_Helper::computeCutSpecialOvertimeNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status);
        }
        if ($check2631) {
            $holiday_special_ns_ot_amount = $prev_holiday_special_ns_ot_amount + $cut_holiday_special_ns_ot_amount;
        }
        if ($is_disable_computation) {
            $holiday_special_ns_ot_amount = 0;
        }
        $labels[] = new Payslip_Label('Holiday Special NS OT Amount', $holiday_special_ns_ot_amount, 'holiday_special_ns_ot_amount');

        $total_ot_amount += $holiday_special_ot_amount + $holiday_special_ns_ot_amount;

        //// HOLIDAY LEGAL
        $holiday_legal_hours = G_Attendance_Helper::getTotalHolidayLegalHours($a, $custom_ot);

        if ($is_disable_computation) {
            $holiday_legal_hours = 0;
        }

        $labels[] = new Payslip_Label('Holiday Legal Hours', $holiday_legal_hours, 'holiday_legal_hours');
        //here
        switch ($salary_type):
            case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:
                $holiday_legal_amount = G_Payslip_Helper::computeLegalAmount($a, $rate, $per_day, $per_hour, $custom_ot, $mandated_status); //$c->computeHolidayLegal(); 
                // $holiday_legal_amount = $holiday_legal_hours * $per_hour * 1;

                $cut_holiday_legal_amount = G_Payslip_Helper::computeCutLegalAmount($a, $rate, $per_day, $per_hour, $custom_ot, $mandated_status);


                $prev_holiday_legal_amount = G_Payslip_Helper::computePrevLegalAmount($a, $rate, $prev_per_day, $prev_per_hour, $custom_ot, $mandated_status);
                // var_dump($holiday_legal_amount); exit;  


                break;
            case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:
                // $holiday_legal_amount = G_Payslip_Helper::computeLegalAmountDoublePay($a, $rate, $per_day, $per_hour, $custom_ot, $mandated_status); //$c->computeHolidayLegal();           
                $holiday_legal_amount = G_Payslip_Helper::computeLegalAmount($a, $rate, $per_day, $per_hour, $custom_ot, $mandated_status); //$c->computeHolidayLegal();           
                // var_dump($holiday_legal_amount); exit;  
                break;
                break;
        endswitch;
        // var_dump(true); exit;  
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $holiday_legal_amount = $holiday_legal_hours * $special_rate;
        }
        if ($check2631) {
            $holiday_legal_amount = $cut_holiday_legal_amount + $prev_holiday_legal_amount;
        }
        if ($is_disable_computation) {
            $holiday_legal_amount = 0;
        }

        $labels[] = new Payslip_Label('Holiday Legal Amount', $holiday_legal_amount, 'holiday_legal_amount');

        $holiday_legal_ot_hours = G_Attendance_Helper::getTotalHolidayLegalOvertimeHours($a);
        if ($is_disable_computation) {
            $holiday_legal_ot_hours = 0;
        }
        $labels[] = new Payslip_Label('Holiday Legal OT Hours', $holiday_legal_ot_hours, 'holiday_legal_ot_hours');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $holiday_legal_ot_amount = $holiday_legal_ot_hours * $special_rate;
        } else {
            $holiday_legal_ot_amount = G_Payslip_Helper::computeLegalOvertimeAmount($a, $rate, $per_day, $per_hour, $mandated_status); //$c->computeHolidayLegalOvertime();

            $cut_holiday_legal_ot_amount =  G_Payslip_Helper::computeCutLegalOvertimeAmount($a, $rate, $per_day, $per_hour, $mandated_status); //$c->computeHolidayLegalOvertime();

            $prev_holiday_legal_ot_amount = G_Payslip_Helper::computePrevLegalOvertimeAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status); //$c->computeHolidayLegalOvertime();

        }

        if ($is_disable_computation) {
            $holiday_legal_ot_amount = 0;
        }

        $labels[] = new Payslip_Label('Holiday Legal OT Amount', $holiday_legal_ot_amount, 'holiday_legal_ot_amount');

        $holiday_legal_ns_hours = G_Attendance_Helper::getTotalHolidayLegalNightShiftHours($a);

        if ($is_disable_computation) {
            $holiday_legal_ns_hours = 0;
        }

        $labels[] = new Payslip_Label('Holiday Legal NS Hours', $holiday_legal_ns_hours, 'holiday_legal_ns_hours');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $holiday_legal_ns_amount = $holiday_legal_ns_hours * $special_rate;
        } else {
            $holiday_legal_ns_amount = G_Payslip_Helper::computeLegalNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status); //$c->computeRegularNightShift();

            $cut_holiday_legal_ns_amount =  G_Payslip_Helper::computeCutLegalNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status); //$c->computeRegularNightShift();
            $prev_holiday_legal_ns_amount =  G_Payslip_Helper::computePrevLegalNightShiftAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status); //$c->computeRegularNightShift();


        }

        if ($check2631) {
            $holiday_legal_ns_amount = $cut_holiday_legal_ns_amount + $prev_holiday_legal_ns_amount;
        }
        if ($is_disable_computation) {
            $holiday_legal_ns_amount = 0;
        }

        $labels[] = new Payslip_Label('Holiday Legal NS Amount', $holiday_legal_ns_amount, 'holiday_legal_ns_amount');

        $holiday_legal_ns_ot_hours = G_Attendance_Helper::getTotalHolidayLegalNightShiftOvertimeHours($a);

        if ($is_disable_computation) {
            $holiday_legal_ns_ot_hours = 0;
        }

        $labels[] = new Payslip_Label('Holiday Legal NS OT Hours', $holiday_legal_ns_ot_hours, 'holiday_legal_ns_ot_hours');
        if (array_key_exists($e->getId(), $this->employees_special_ot_rate)) {
            $special_rate = $this->employees_special_ot_rate[$e->getId()];
            $holiday_legal_ns_ot_amount = $holiday_legal_ns_ot_hours * $special_rate;
        } else {
            $holiday_legal_ns_ot_amount = G_Payslip_Helper::computeLegalOvertimeNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status); //$c->computeHolidayLegalNightShiftOvertime();

            $cut_holiday_legal_ns_ot_amount = G_Payslip_Helper::computeCutLegalOvertimeNightShiftAmount($a, $rate, $per_day, $per_hour, $mandated_status); //$c->computeHolidayLegalNightShiftOvertime();
            $prev_holiday_legal_ns_ot_amount = G_Payslip_Helper::computePrevLegalOvertimeNightShiftAmount($a, $rate, $prev_per_day, $prev_per_hour, $mandated_status); //$c->computeHolidayLegalNightShiftOvertime();

        }
        if ($check2631) {
            $holiday_legal_ns_ot_amount = $cut_holiday_legal_ns_ot_amount + $prev_holiday_legal_ns_ot_amount;
        }
        if ($is_disable_computation) {
            $holiday_legal_ns_ot_amount = 0;
        }

        $labels[] = new Payslip_Label('Holiday Legal NS OT Amount', $holiday_legal_ns_ot_amount, 'holiday_legal_ns_ot_amount');

        $total_ot_amount += $holiday_legal_ot_amount + $holiday_legal_ns_ot_amount;

        $basic_pay = 0;
        switch ($salary_type):
            case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:
                if (Tools::isDateWithinDates($e->getHiredDate(), $start_date, $end_date) && $e->getHiredDate() != $start_date) { // pro rated
                    //$basic_pay = ($present_days_with_pay + $total_present_holiday) * $salary_amount;
                    $basic_pay = $salary_amount / 2;
                } else {
                    $basic_pay = $salary_amount / 2;
                }
                break;
            case G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY:
                $total_hrs_worked = G_Attendance_Helper::sqlSumRestDayAndHolidayTotalHrsWorkedByEmployeeIdAndDateRange($e->getId(), $this->cutoff_period->getStartDate(), $this->cutoff_period->getEndDate());
                // $new_per_hour     = number_format(8.75 * $per_hour,2);                
                // $new_per_hour     = number_format(8.75 * $per_hour,2, '.', '');                
                // $basic_pay        = ($present_days_with_pay * $new_per_hour);
                $basic_pay        = ($present_days_with_pay * $per_day);

                break;
        endswitch;

        if ($is_disable_computation) {
            $basic_pay = 0;
        }

        // EARNINGS
        $total_earnings               = 0;
        $total_overtime_amount        = 0;
        $total_night_shift_amount     = 0;
        $total_restday_holiday_amount = 0;
        $total_taxable_earning        = 0;
        $total_non_taxable_earning    = 0;

        $total_earnings += $basic_pay;
        $a_total_earnings[] = $basic_pay;

        $obj_earning = new Earning('Basic Pay', $basic_pay);
        $obj_earning->setVariable('basic_pay');
        $ers[] = $obj_earning;

        if ($regular_ot_amount > 0) {
            $total_earnings += $regular_ot_amount;
            $total_overtime_amount += $regular_ot_amount;
            $obj_earning = new Earning('Regular OT', $regular_ot_amount);
            $obj_earning->setVariable('total_regular_ot_amount');
            $ers[] = $obj_earning;
        }
        if ($regular_ns_ot_amount > 0) {
            $total_earnings += $regular_ns_ot_amount;
            $total_night_shift_amount = $regular_ns_ot_amount;
            $obj_earning = new Earning('Regular NS OT', $regular_ns_ot_amount);
            $obj_earning->setVariable('total_regular_ns_ot_amount');
            $ers[] = $obj_earning;
        }
        if ($regular_ns_amount > 0) {
            $total_earnings += $regular_ns_amount;
            $total_night_shift_amount += $regular_ns_amount;
            $obj_earning = new Earning('Regular NS', $regular_ns_amount);
            $obj_earning->setVariable('total_regular_ns_amount');
            $ers[] = $obj_earning;
        }
        if ($holiday_special_amount > 0) {
            $total_earnings += $holiday_special_amount;
            $total_restday_holiday_amount += $holiday_special_amount;
            $obj_earning = new Earning('Holiday Special', $holiday_special_amount);
            $obj_earning->setVariable('total_special_amount');
            $ers[] = $obj_earning;
        }
        if ($holiday_special_ot_amount > 0) {
            $total_earnings += $holiday_special_ot_amount;
            $total_overtime_amount += $holiday_special_ot_amount;
            $obj_earning = new Earning('Holiday Special OT', $holiday_special_ot_amount);
            $obj_earning->setVariable('total_special_ot_amount');
            $ers[] = $obj_earning;
        }
        if ($holiday_special_ns_amount > 0) {
            $total_earnings += $holiday_special_ns_amount;
            $total_night_shift_amount += $holiday_special_ns_amount;
            $obj_earning = new Earning('Holiday Special NS', $holiday_special_ns_amount);
            $obj_earning->setVariable('total_special_ns_amount');
            $ers[] = $obj_earning;
        }
        if ($holiday_special_ns_ot_amount > 0) {
            $total_earnings += $holiday_special_ns_ot_amount;
            $total_night_shift_amount += $holiday_special_ns_ot_amount;
            $obj_earning = new Earning('Holiday Special NS OT', $holiday_special_ns_ot_amount);
            $obj_earning->setVariable('total_special_ns_ot_amount');
            $ers[] = $obj_earning;
        }
        if ($holiday_legal_amount > 0) {
            $total_earnings += $holiday_legal_amount;
            $total_restday_holiday_amount += $holiday_legal_amount;
            $obj_earning = new Earning('Holiday Legal', $holiday_legal_amount);
            $obj_earning->setVariable('total_legal_amount');
            $ers[] = $obj_earning;
        }
        if ($holiday_legal_ot_amount > 0) {

            $total_earnings += $holiday_legal_ot_amount;
            $total_overtime_amount += $holiday_legal_ot_amount;
            $obj_earning = new Earning('Holiday Legal OT', $holiday_legal_ot_amount);
            $obj_earning->setVariable('total_legal_ot_amount');
            $ers[] = $obj_earning;
        }
        if ($holiday_legal_ns_amount > 0) {
            $total_earnings += $holiday_legal_ns_amount;
            $total_night_shift_amount += $holiday_legal_ns_amount;
            $obj_earning = new Earning('Holiday Legal NS', $holiday_legal_ns_amount);
            $obj_earning->setVariable('total_legal_ns_amount');
            $ers[] = $obj_earning;
        }
        if ($holiday_legal_ns_ot_amount > 0) {
            $total_earnings += $holiday_legal_ns_ot_amount;
            $total_night_shift_amount += $holiday_legal_ns_ot_amount;
            $obj_earning = new Earning('Holiday Legal NS OT', $holiday_legal_ns_ot_amount);
            $obj_earning->setVariable('total_legal_ns_ot_amount');
            $ers[] = $obj_earning;
        }
        if ($restday_amount > 0) {
            $total_earnings += $restday_amount;
            $total_restday_holiday_amount += $restday_amount;
            $obj_earning = new Earning('Rest Day', $restday_amount);
            $obj_earning->setVariable('total_rest_day');
            $ers[] = $obj_earning;
        }
        if ($restday_ot_amount > 0) {
            $total_earnings += $restday_ot_amount;
            $total_overtime_amount += $restday_ot_amount;
            $obj_earning = new Earning('Rest Day OT', $restday_ot_amount);
            $obj_earning->setVariable('total_rest_day_ot');
            $ers[] = $obj_earning;
        }
        if ($restday_ns_amount > 0) {
            $total_earnings += $restday_ns_amount;
            $total_night_shift_amount += $restday_ns_amount;
            $obj_earning = new Earning('Rest Day NS', $restday_ns_amount);
            $obj_earning->setVariable('total_rest_day_ns');
            $ers[] = $obj_earning;
        }
        if ($restday_ns_ot_amount > 0) {
            $total_earnings += $restday_ns_ot_amount;
            $total_night_shift_amount += $restday_ns_ot_amount;
            $obj_earning = new Earning('Rest Day NS OT', $restday_ns_ot_amount);
            $obj_earning->setVariable('total_rest_day_ns_ot');
            $ers[] = $obj_earning;
        }
        if ($restday_special_amount > 0) {
            $total_earnings += $restday_special_amount;
            $total_restday_holiday_amount += $restday_special_amount;
            $obj_earning = new Earning('Rest Day Special', $restday_special_amount);
            $obj_earning->setVariable('total_rest_day_special');
            $ers[] = $obj_earning;
        }
        if ($restday_special_ot_amount > 0) {
            $total_earnings += $restday_special_ot_amount;
            $total_overtime_amount += $restday_special_ot_amount;
            $obj_earning = new Earning('Rest Day Special OT', $restday_special_ot_amount);
            $obj_earning->setVariable('total_rest_day_special_ot');
            $ers[] = $obj_earning;
        }
        if ($restday_special_ns_amount > 0) {
            $total_earnings += $restday_special_ns_amount;
            $total_night_shift_amount += $restday_special_ns_amount;
            $obj_earning = new Earning('Rest Day Special NS', $restday_special_ns_amount);
            $obj_earning->setVariable('total_rest_day_special_ns');
            $ers[] = $obj_earning;
        }
        if ($restday_special_ns_ot_amount > 0) {
            $total_earnings += $restday_special_ns_ot_amount;
            $total_night_shift_amount += $restday_special_ns_ot_amount;
            $obj_earning = new Earning('Rest Day Special NS OT', $restday_special_ns_ot_amount);
            $obj_earning->setVariable('total_rest_day_special_ns_ot');
            $ers[] = $obj_earning;
        }
        if ($restday_legal_amount > 0) {
            $total_earnings += $restday_legal_amount;
            $total_restday_holiday_amount += $restday_legal_amount;
            $obj_earning = new Earning('Rest Day Legal', $restday_legal_amount);
            $obj_earning->setVariable('total_rest_day_legal');
            $ers[] = $obj_earning;
        }

        if ($restday_legal_ot_amount > 0) {
            $total_earnings += $restday_legal_ot_amount;
            $total_overtime_amount += $restday_legal_ot_amount;
            $obj_earning = new Earning('Rest Day Legal OT', $restday_legal_ot_amount);
            $obj_earning->setVariable('total_rest_day_legal_ot');
            $ers[] = $obj_earning;
        }
        //
        if ($restday_legal_ns_amount > 0) {
            $total_earnings += $restday_legal_ns_amount;
            $total_night_shift_amount += $restday_legal_ns_amount;
            $obj_earning = new Earning('Rest Day Legal NS', $restday_legal_ns_amount);
            $obj_earning->setVariable('total_rest_day_legal_ns');
            $ers[] = $obj_earning;
        } //
        if ($restday_legal_ns_ot_amount > 0) {
            $total_earnings += $restday_legal_ns_ot_amount;
            $total_night_shift_amount += $restday_legal_ns_ot_amount;
            $obj_earning = new Earning('Rest Day Legal NS OT', $restday_legal_ns_ot_amount);
            $obj_earning->setVariable('total_rest_day_legal_ns_ot');
            $ers[] = $obj_earning;
        }
        $new_gross_pay += $total_earnings;

        //EARNINGS : CETA / SEA        
        $data   = array(
            "attendance" => $a,
            "daily_rate" => $ceta_sea_employee_rate
        );

        $ceta_sea = $e->generateCetaSea($data, $salary_type);
        $ceta_sea_valid_days = $ceta_sea['total_counted_days'];

        $obj_earning = new Earning('CETA', $ceta_sea['ceta_amount']);
        $obj_earning->setVariable('total_ceta_amount');
        $ers[] = $obj_earning;
        $total_earnings += $ceta_sea['ceta_amount'];
        $total_ceta_sea += $ceta_sea['ceta_amount'];

        $obj_earning = new Earning('CTPA', $ceta_sea['sea_amount']);
        $obj_earning->setVariable('total_sea_amount');
        $ers[] = $obj_earning;
        $total_earnings += $ceta_sea['sea_amount'];
        $total_ceta_sea += $ceta_sea['sea_amount'];

        if ($is_disable_computation) {
            $ceta_sea_valid_days = 0;
            $ceta_sea_valid_days = 0;
        }

        $labels[] = new Payslip_Label('CETA days with pay', $ceta_sea_valid_days, 'ceta_days_with_pay');
        $labels[] = new Payslip_Label('CTPA days with pay', $ceta_sea_valid_days, 'sea_days_with_pay');

        //$p->addEarnings($ers);

        $p->setEarnings($ers);

        $employee_other_earnings = array();
        $total_other_earnings    = 0;
        //EARNINGS : BENEFITS        
        $leave_absent_total = $absent_days_without_pay + $total_days_leave_with_pay;
        $b               = new G_Employee_Benefits_Main();
        $custom_criteria = array(G_Employee_Benefits_Main::CUSTOM_CRITERIA_ABSENT_DAYS => $absent_days_without_pay, G_Employee_Benefits_Main::CUSTOM_CRITERIA_LEAVE_DAYS => $total_days_leave_with_pay, G_Employee_Benefits_Main::CUSTOM_CRITERIA_ABSENT_LEAVE_DAYS => $leave_absent_total);
        $applied_to = $b->validAppliedToOptions();

        $criteria   = '';
        $cutoff     = array($cutoff_number, G_Settings_Employee_Benefit::OCCURANCE_ALL);
        $benefits_a = $b->setCutoffEndDate($this->end_date)->setCriteria($criteria)->setEmployeeCustomCriteria($custom_criteria)->getEmployeeBenefitsWithCriteria($e, $applied_to, $cutoff)->convertBenefitsToEarningsArray();

        $a_criteria = array();
        if ($undertime_amount == 0) {
            $a_criteria[] = G_Employee_Benefits_Main::CRITERIA_NO_UNDERTIME;
        }

        if ($late_amount == 0) {
            $a_criteria[] = G_Employee_Benefits_Main::CRITERIA_NO_LATE;
        }

        if ($absent_amount == 0) {
            $a_criteria[] = G_Employee_Benefits_Main::CRITERIA_NO_ABSENT;
        }

        if ($total_days_leave_with_pay == 0) {
            $a_criteria[] = G_Employee_Benefits_Main::CRITERIA_NO_LEAVE;
        }

        //echo "Undertime : {$undertime_amount} / Late : {$late_amount} / Absent : {$absent_amount} / Leave : {$total_days_leave_with_pay}";

        $benefits = array();
        $criteria = implode(",", $a_criteria);
        $criteria = trim($criteria);
        $cutoff     = array($cutoff_number, G_Settings_Employee_Benefit::OCCURANCE_ALL);
        $benefits_b = $b->setCutoffEndDate($this->end_date)->setCriteria($criteria)->setEmployeeCustomCriteria($custom_criteria)->getEmployeeBenefitsWithCriteria($e, $applied_to, $cutoff)->convertBenefitsToEarningsArray();
        $benefits = array_merge($benefits_a, $benefits_b);
        $benefits = array_unique($benefits, SORT_REGULAR);
        $add_to_contri_gross = 0;

        $a_add_to_gross_labels  = array('position allowance');
        $a_string_find_ceta_sea = "CTPA/SEA";

        if (!$is_disable_computation) {
            if ($benefits) {
                $benefits_present_days        = G_Attendance_Helper::countPresentRegularDaysWithPayNoRestDay($a);
                $custom_benefits_present_days = G_Attendance_Helper::countPresentRegularHolidayDaysWithPay($a);
                foreach ($benefits as $benefit) {
                    $amount = $benefit->getAmount();
                    $a_amount = explode("/", $amount);
                    if (!empty($a_amount[1])) {
                        if (stripos($benefit->getLabel(), $a_string_find_ceta_sea) !== false) {
                            $new_amount = $a_amount[0] * $custom_benefits_present_days; //Number of days for ceta/sea
                        } else {
                            $new_amount = $a_amount[0] * $benefits_present_days;
                        }

                        $benefit->setAmount($new_amount);
                    }

                    if ($benefit->isTaxable()) {
                        $total_taxable_earning += $benefit->getAmount();
                    } else {
                        $total_non_taxable_earning += $benefit->getAmount();
                    }
                    $total_other_earnings += $benefit->getAmount();

                    //Selected benefit will added to gross pay contri
                    $add_to_gross_benefit_title =  $benefit->getLabel();
                    $add_to_gross_benefit_title = strtolower($add_to_gross_benefit_title);
                    foreach ($a_add_to_gross_labels as $label) {
                        if (stripos($add_to_gross_benefit_title, $label) !== false) {
                            $add_to_contri_gross += $benefit->getAmount();
                            $new_gross_pay += $benefit->getAmount();
                        }
                    }
                }
                $employee_other_earnings = $benefits;
            }
        }
        //END BENEFITS


        // EARNINGS : OVERTIME ALLOWANCE
        $os = new Overtime_Settings();
        $os->setEmployee($e);
        $os->setAttendance($a);
        $os->setCutoffPeriod($this->cutoff_period);
        $os_ot_allowance = $os->getEmployeeOvertimeAllowance();

        if ($os_ot_allowance) {
            if (!$is_disable_computation) {
                foreach ($os_ot_allowance as $ot_allowance) {
                    if ($ot_allowance->isTaxable()) {
                        $total_taxable_earning += $ot_allowance->getAmount();
                    } else {
                        $total_non_taxable_earning += $ot_allowance->getAmount();
                    }
                    $total_other_earnings += $ot_allowance->getAmount();
                }
                $employee_other_earnings = array_merge($employee_other_earnings, $os_ot_allowance);
            }
        }

        // EARNINGS : OTHER EARNINGS               
        $other_earnings = array();
        $e_cutoff['id']   = $this->cutoff_period->getId();
        $e_cutoff['from'] = $this->cutoff_period->getStartDate();
        $e_cutoff['to']   = $this->cutoff_period->getEndDate();
        $other_earnings = $e->getEmployeeEarningsByCutoffPeriod($e_cutoff);

        $total_taxable_constant_bonus     = 0;
        $earnings_bonus_to_add_in_taxable = 0;
        $earnings_to_gross_key = array('adjustment');
        $earnings_bonus_key    = array('bonus', 'service award');
        $earnings_bonus_to_add_in_taxable_key = array('bonus');
        if ($other_earnings) {
            foreach ($other_earnings as $other_earning) {
                $ea_variable_name = strtolower($other_earning->getVariable());

                if (!$is_disable_computation) {
                    foreach ($earnings_to_gross_key as $gross_key) {
                        if (stripos($ea_variable_name, $gross_key) !== false) {
                            $new_gross_pay += $other_earning->getAmount();
                        }
                    }
                }

                foreach ($earnings_bonus_key as $bonus) {
                    if (stripos($ea_variable_name, $bonus) !== false) {
                        if ($other_earning->isTaxable()) {
                            $total_taxable_constant_bonus += $other_earning->getAmount();
                        }
                    }
                }

                foreach ($earnings_bonus_to_add_in_taxable_key as $bonus_only) {
                    if (stripos($ea_variable_name, $bonus_only) !== false) {
                        if (!$other_earning->isTaxable()) {
                            $earnings_bonus_to_add_in_taxable += $other_earning->getAmount();
                        }
                    }
                }

                if ($other_earning->isTaxable()) {
                    $total_taxable_earning += $other_earning->getAmount();
                } else {
                    $total_non_taxable_earning += $other_earning->getAmount();
                }

                $total_other_earnings += $other_earning->getAmount();
            }
            $employee_other_earnings = array_merge($employee_other_earnings, $other_earnings);
        }

        $total_taxable_earning = $total_taxable_earning - $total_taxable_constant_bonus;

        $bonus_taxable_earnings = 0;

        // 13 month pay
        $yb = new G_Yearly_Bonus();
        $yb->setEmployeeId($e->getId());
        $yearly_bonus = $yb->getEmployeeYearlyBonusByStartAndEndCutoff($start_date, $end_date);

        if (!empty($yearly_bonus)) {
            $yearly_bonus_earnings[] = new Earning('13th Month Bonus', $yearly_bonus['amount'], Earning::NON_TAXABLE);
            $labels[] = new Payslip_Label('13th Month Bonus', $yearly_bonus['amount'], '13th_month_bonus');

            $employee_other_earnings = array_merge($employee_other_earnings, $yearly_bonus_earnings);

            $previous_yearly_bonus = $yb->getPreviousEmployeeYearlyBonus($start_date, $end_date);

            $add_previous_bonus = 0;
            if (!empty($previous_yearly_bonus)) {
                $add_previous_bonus += $previous_yearly_bonus['amount'];
            }

            if (($yearly_bonus['amount'] + $earnings_bonus_to_add_in_taxable + $add_previous_bonus) > G_Yearly_Bonus::CEILING_NON_TAXABLE) {
                $bonus_taxable_earnings      += ($yearly_bonus['amount'] + $earnings_bonus_to_add_in_taxable + $add_previous_bonus) - G_Yearly_Bonus::CEILING_NON_TAXABLE;
                $total_non_taxable_earning   += G_Yearly_Bonus::CEILING_NON_TAXABLE;
            } else {
                $total_non_taxable_earning += $yearly_bonus['amount'];
            }

            $total_other_earnings += $yearly_bonus['amount'];
        }

        // DEDUCTIONS
        $total_deductions = 0;
        if ($late_amount >= 0) {

            if ($is_disable_computation) {
                $late_amount = 0;
            }

            $total_deductions       += $late_amount;
            $obj_deduct = new Deduction('Late', $late_amount);
            $obj_deduct->setVariable('late_amount');
            $deductions[] = $obj_deduct;
        }

        if ($undertime_amount >= 0) {

            if ($is_disable_computation) {
                $undertime_amount = 0;
            }

            $total_deductions       += $undertime_amount;
            $obj_deduct = new Deduction('Undertime', $undertime_amount);
            $obj_deduct->setVariable('undertime_amount');
            $deductions[] = $obj_deduct;
        }

        if ($absent_amount >= 0) {

            if ($is_disable_computation) {
                $absent_amount = 0;
            }

            $total_deductions       += $absent_amount;
            $obj_deduct = new Deduction('Absent Amount', $absent_amount);
            $obj_deduct->setVariable('absent_amount');
            $deductions[] = $obj_deduct;
        }

        if ($suspended_amount > 0) {

            if ($is_disable_computation) {
                $suspended_amount = 0;
            }

            $total_deductions       += $suspended_amount;
            $obj_deduct = new Deduction('Suspended Amount', $suspended_amount);
            $obj_deduct->setVariable('suspended_amount');
            $deductions[] = $obj_deduct;
        }

        // OTHER DEDUCTIONS
        $employee_other_deductions       = array();
        $total_other_deductions          = 0;
        $other_deductions                = G_Employee_Deductions_Helper::getOtherDeductions($e, $this->cutoff_period);
        $deduction_to_gross_key = array('adjustment');

        if ($other_deductions) {
            foreach ($other_deductions as $other_deduction) {

                $de_variable_name = $other_deduction->getVariable();
                foreach ($deduction_to_gross_key as $gross_key) {
                    if (stripos($de_variable_name, $gross_key) !== false) {
                        $new_gross_pay -= $other_deduction->getAmount();
                    }
                }

                if (in_array($other_deduction->getVariable(), $excluded_employee_deduction[$e->getId()])) {
                    $new_deduction = new Deduction($other_deduction->getLabel(), 0);
                    $employee_total_other_deductions += 0;
                    $employee_other_deductions[]      = $new_deduction;
                } else {
                    $employee_total_other_deductions += $other_deduction->getAmount();
                    $employee_other_deductions[]      = $other_deduction;
                }

                if ($de_variable_name == 'union_dues') {
                    $union_dues_amount = $other_deduction->getAmount();
                }
            }

            $total_other_deductions = $employee_total_other_deductions;
        }


        // OTHER DEDUCTION - Late Breaktime In/Out
        $late_hours_breaktime = G_Employee_Breaktime_Helper::getLateHoursByEmployeeIdPeriod($e->getId(), $start_date, $end_date);
        if ($late_hours_breaktime > 0) {
            $late_amount_breaktime = Tools::numberFormat($late_hours_breaktime * $per_hour, 2);
            if ($late_amount_breaktime > 0) {
                $new_deduction = new Deduction("Late Amount Breaktime", $late_amount_breaktime);
                $employee_other_deductions[]      = $new_deduction;
                $total_other_deductions += $late_amount_breaktime;
            }
        }

        // TAXABLE INCOME
        //$gross_pay        = $basic_pay + $total_taxable_earning + $total_non_taxable_earning + $total_restday_holiday_amount + $total_overtime_amount + $total_night_shift_amount;  
        $gross_pay = $total_earnings;
        $tardiness_amount = $late_amount + $undertime_amount + $absent_amount + $suspended_amount;

        // CONTRIBUTIONS  
        $contri_salary_amount = $salary_amount;
        $contri_gross_pay     = $gross_pay + $add_to_contri_gross;
        //$contri_monthly_rate_daily = $monthly_rate_daily;
        $contri_monthly_rate_daily = $contri_gross_pay;

        switch ($salary_type):
            case G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY:
                //$contri_salary_amount = $contri_salary_amount - $tardiness_amount;
                $contri_gross_pay     = $contri_gross_pay - ($tardiness_amount + $ceta_sea['ceta_amount'] + $ceta_sea['sea_amount']);
                break;
        endswitch;

        $salary['basic_pay']   = $contri_salary_amount;
        $salary['gross_pay']   = $contri_gross_pay;
        $salary['monthly_pay'] = $contri_monthly_rate_daily;
        $cutoff                = $this->cutoff_period;

        $contri = $e->getEmployeeContributionsByCutoffNumber($salary, $cutoff);

        /*echo '<pre>';
        print_r($salary);
        print_r($contri);
        echo '</pre>';*/

        if (in_array('sss', $excluded_employee_deduction[$e->getId()])) {
            $sss_amount  = 0;
            $sss_er      = 0;
        } else {
            $sss_amount = str_replace(",", "", $contri['SSS']['data']['ee']);
            $sss_er     = str_replace(",", "", $contri['SSS']['data']['er']);
        }

        if (in_array('pagibig', $excluded_employee_deduction[$e->getId()])) {
            $pagibig_amount   = 0;
            $pagibig_er       = 0;
            //$pagibig_2_amount = 0;
        } else {
            $pagibig_amount   = str_replace(",", "", $contri['HDMF']['data']['ee']);
            $pagibig_er       = str_replace(",", "", $contri['HDMF']['data']['er']);
            //$pagibig_2_amount = $contri['HDMF']['data']['ee2'];
        }

        if (in_array('philhealth', $excluded_employee_deduction[$e->getId()])) {
            $phealth_amount = 0;
            $phealth_er     = 0;
        } else {
            $phealth_amount = $contri['Phil Health']['data']['ee'];
            $phealth_er     = $contri['Phil Health']['data']['er'];
        }

        if ($is_disable_computation) {
            $sss_amount     = 0;
            $sss_er         = 0;
            $pagibig_amount = 0;
            $pagibig_er     = 0;
            $phealth_amount = 0;
            $phealth_er     = 0;
        }

        $total_deductions += $sss_amount;
        $obj_deduct = new Deduction('SSS', $sss_amount);
        $obj_deduct->setVariable('sss');
        $deductions[] = $obj_deduct;
        $p->setSSS($sss_amount);
        $labels[] = new Payslip_Label('SSS Employer', $sss_er, 'sss_er');

        $total_deductions += $pagibig_amount;
        $obj_deduct = new Deduction('Pagibig', $pagibig_amount);
        $obj_deduct->setVariable('pagibig');
        $deductions[] = $obj_deduct;

        $p->setPagibig($pagibig_amount);
        $labels[] = new Payslip_Label('Pagibig Employer', $pagibig_er, 'pagibig_er');

        /* FOR PAGIBIG2 */
        /*
            $total_deductions += $pagibig_2_amount;
            $obj_deduct = new Deduction('Pagibig2', $pagibig_2_amount);
            $obj_deduct->setVariable('pagibig2');
            $deductions[] = $obj_deduct;
            $labels[] = new Payslip_Label('Pagibig2', $pagibig_2_amount, 'pagibig_2');
        */
        /* FOR PAGIBIG2 - END */

        $total_deductions += $phealth_amount;
        $obj_deduct        = new Deduction('Philhealth', $phealth_amount);
        $obj_deduct->setVariable('philhealth');
        $deductions[] = $obj_deduct;
        $p->setPhilhealth($phealth_amount);
        $labels[] = new Payslip_Label('PHIC Employer', $phealth_er, 'philhealth_er');

        $total_government_contribution = $sss_amount + $pagibig_amount + $phealth_amount;

        //$taxable_income   = $gross_pay - ($tardiness_amount + $sss_amount + $phealth_amount + $pagibig_amount + $total_non_taxable_earning);
        //$gross_pay        += $total_other_earnings;
        $taxable_income   = ($gross_pay + $total_taxable_earning) - ($tardiness_amount + $total_government_contribution + $union_dues_amount);
        // ditotayoedit
        // 4519.9

        // echo "(".$gross_pay. " + " .$total_taxable_earning. ") - (".$tardiness_amount. " + ".$total_government_contribution . " - " .$union_dues_amount .")";

        $month_13th       = $basic_pay / 12;
        $gross_pay        = ($gross_pay + $add_to_contri_gross) - ($tardiness_amount + $total_ceta_sea);

        //Employee loans
        $date_from = $this->start_date;
        $date_to   = $this->end_date;
        $loan = array('gross' => $gross_pay, 'date_from' => $date_from, 'date_to' => $date_to);
        $loans_deducted = $e->getEmployeeScheduledUnpaidLoans($loan);

        $is_with_deduction = false;
        foreach ($loans_deducted['deducted'] as $key => $ld) {
            $is_with_deduction       = true;

            $obj_deduct = new Deduction($ld['loan_title'], $ld['deducted']);
            $obj_deduct->setVariable('employee_deduction');
            $employee_other_deductions[] = $obj_deduct;

            $labels[] = new Payslip_Label($ld['loan_title'], $ld['deducted'], 'employee_deduction');
            $total_loans_deducted += $ld['deducted'];
        }

        if ($is_with_deduction) {
            $total_other_deductions += $loans_deducted['total_deducted'];
            $labels[] = new Payslip_Label('Total other deductions', $total_other_deductions, 'total_other_deductions');
            $labels[] = new Payslip_Label('Total loan amount deducted', $loans_deducted['total_deducted'], 'total_loan_amount_deducted');
        }
        //End employee loans

        //$multiplier     = G_Settings_Deduction_Breakdown_Helper::getCurrentPayPeriodPercentageDeductedByEmployeedAndDeductible($e,G_Settings_Deduction_Breakdown::TAX,$end_date);
        $multiplier     = G_Settings_Deduction_Breakdown_Helper::getDeductionPercentage($cutoff_number, G_Settings_Deduction_Breakdown::TAX);
        $taxable_income = $taxable_income * ($multiplier / 100);

        $pay_period_id = $s->getPayPeriodId();
        $pay_period    = G_Settings_Pay_Period_Finder::findById($pay_period_id);

        $new_tax_computation = true; //for 2018 new tax computation
        if ($pay_period) {

            if ($new_tax_computation) {
                if ($pay_period->getPayPeriodCode() == G_Settings_Pay_Period::TYPE_BI_MONTHLY) {
                    $tax_table = Tax_Table_Factory::getRevisedTax(Tax_Table::SEMI_MONTHLY);
                } elseif ($pay_period->getPayPeriodCode() == G_Settings_Pay_Period::TYPE_MONTHLY) {
                    $tax_table = Tax_Table_Factory::getRevisedTax(Tax_Table::MONTHLY);
                }
            } else {
                if ($pay_period->getPayPeriodCode() == G_Settings_Pay_Period::TYPE_BI_MONTHLY) {
                    $tax_table = Tax_Table_Factory::get(Tax_Table::SEMI_MONTHLY);
                } elseif ($pay_period->getPayPeriodCode() == G_Settings_Pay_Period::TYPE_MONTHLY) {
                    $tax_table = Tax_Table_Factory::get(Tax_Table::MONTHLY);
                }
            }

            $tax = new Tax_Calculator;
            $tax->setTaxTable($tax_table);
            $tax->setTaxableIncome($taxable_income);
            if ($e->getNumberDependent() > 4) {
                $dependents = 4;
            } else {
                $dependents    = $e->getNumberDependent();
            }

            $tax->setNumberOfDependent($dependents);

            if ($new_tax_computation) {

                $witholding_tax = round($tax->computeHB563(), 2);
            } else {
                $witholding_tax = round($tax->compute(), 2);
            }

            $tax->setTaxableIncome($total_taxable_constant_bonus);
            if ($new_tax_computation) {
                $witholding_tax_bonus = round($tax->computeHB563(), 2);
            } else {
                $witholding_tax_bonus = round($tax->compute(), 2);
            }

            $tax->setTaxableIncome($bonus_taxable_earnings);
            if ($new_tax_computation) {
                $witholding_tax_bonus_other = round($tax->computeHB563(), 2);
            } else {
                $witholding_tax_bonus_other = round($tax->compute(), 2);
            }

            $obj_deduct = new Deduction('Bonus / Service Award Witholding Tax', $witholding_tax_bonus + $witholding_tax_bonus_other);
            $obj_deduct->setVariable('tax_bonus_service_award');
            $employee_other_deductions[] = $obj_deduct;

            $sv    = new G_Sprint_Variables('minimum_rate');
            $minimum_rate_value = $sv->getVariableValue();
            if ($e->getIsTaxExempted() == G_Employee::YES || $per_day <= $minimum_rate_value) {
                $witholding_tax = 0;
            }

            //Annualized Tax            
            $cutoff_period = array('from' => $start_date, 'to' => $end_date);
            $fields        = array('tax_due', 'tax_refund_payable');
            $annualized_tax = G_Employee_Annualize_Tax_Helper::getAnnualizedTaxByEmployeeIdAndCutoffPeriod($e->getId(), $cutoff_period, $fields);
            $org_tax       = $witholding_tax;
            if (!empty($annualized_tax)) {
                $witholding_tax = $witholding_tax + ($annualized_tax['tax_refund_payable']);
                $labels[] = new Payslip_Label('Tax Refund', $annualized_tax['tax_refund_payable'], 'tax_refund');
                $labels[] = new Payslip_Label('Original Tax Withheld', $org_tax, 'org_tax_withheld');
                $obj_deduct->setVariable('tax_refund');
                $employee_other_deductions[] = $obj_deduct;
            }

            //if ($witholding_tax > 0) {
            $total_deductions += $witholding_tax;
            $obj_deduct = new Deduction('Witholding Tax', $witholding_tax);
            $obj_deduct->setVariable('witholding tax');
            $deductions[] = $obj_deduct;
            //}
        }

        $total_deductions += $total_other_deductions;
        $total_earnings   += $total_other_earnings;
        $new_gross_pay    -= $tardiness_amount;
        $net_pay = round($total_earnings, 2) - round($total_deductions, 2);
        //$net_pay = $gross_pay + $total_other_earnings) - ($tardiness_amount + $total_government_contribution + $total_other_deductions);
        $net_pay = round($net_pay, 2);
        $declared_dependents = $dependents;

        //$p->addDeductions($deductions);       
        $p->setDeductions($deductions);
        $p->setOtherDeductions($employee_other_deductions);
        $p->setOtherEarnings($employee_other_earnings);
        //$p->addLabels($labels);
        $p->setLabels($labels);

        $p->setTardinessAmount($tardiness_amount);
        $p->setBasicPay($basic_pay);
        $p->setOvertime($total_ot_amount);
        $p->setDeclaredDependents($declared_dependents);
        //$p->setGrossPay($gross_pay);
        $p->setGrossPay($new_gross_pay);
        $p->setTotalDeductions($total_deductions);
        $p->setTotalEarnings($total_earnings);
        $p->setNetPay($net_pay);
        $p->set13thMonth($month_13th);
        $p->setTaxable($taxable_income);
        $p->setTaxableBenefits($total_taxable_earning);
        $p->setNonTaxable($total_non_taxable_earning);
        $p->setNonTaxableBenefits($total_non_taxable_earning);
        $p->setSSS($sss_amount);
        $p->setPagibig($pagibig_amount);
        $p->setPhilhealth($phealth_amount);
        $p->setWithheldTax($witholding_tax);

        return $p;
    }
}
