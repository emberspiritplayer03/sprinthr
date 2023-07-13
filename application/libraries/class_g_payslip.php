<?php
class G_Payslip extends Payslip {
	protected $id;
	protected $employee;
	protected $employee_id;
	protected $labels;
	public    $payslip_array = array();

	function __construct() {

	}

	public function setEmployee(IEmployee $e) {
		$this->employee = $e;
	}

	public function getEmployee() {
		return $this->employee;
	}

	public function setEmployeeId($value) {
		$this->employee_id = $value;
	}

	public function getEmployeeId() {
		return $this->employee_id;
	}

	public function setId($value) {
		$this->id = $value;
	}

	public function getId() {
		return $this->id;
	}

	/*
		Usage:
		$p = new Payslip('2011-02-06', '2011-02-20');
		$l[] = new Payslip_Label('Prepared By', 'Marlito', 'prepared_by');
		$l[] = new Payslip_Label('Pay Date', 'Today', 'pay_date');
		$p->addLabels($l);
	*/
	public function addLabels($labels) {
		if (is_array($labels)) {
			foreach ($labels as $label) {
				$this->addLabel($label);
			}
		} else {
			$this->addLabel($labels);
		}
	}

	public function getLabels() {
		return $this->labels;
	}

	public function setLabels($value) {
		$this->labels = $value;
	}

	private function addLabel($label) {
		$this->labels[] = $label;
	}

	public function removeLabel($variable) {
		foreach ($this->labels as $key => $l) {
			if (strtolower($variable) == strtolower($l->getVariable())) {
				unset($this->labels[$key]);
			}
		}
	}

	public function getEmployeeBasicPayslipInfo() {
		$data = array();

		if( !empty($this->employee) ){
			$earnings = (array) self::getEarnings();
			$ph 	  = new G_Payslip_Helper($this);

			$data['salary_type']  = $ph->getValue('salary_type');
			$data['monthly_rate'] = number_format($ph->getValue('monthly_rate'),2);
			$data['daily_rate']   = number_format($ph->getValue('daily_rate'),2);
			$data['hourly_rate']  = number_format($ph->getValue('hourly_rate'),2) ;
		}

		return $data;
	}

	public function getBasicEarnings() {		
		$new_earnings = array();		
	
		if( !empty($this->employee) ){			

			$earnings = (array) self::getEarnings();		
			$ph 	  = new G_Payslip_Helper($this);

			//var_dump(expression)
			$total_hrs_worked     = $ph->getValue('regular_hours');	
			$total_present_days   = ($ph->getValue('present_days_with_pay') + $ph->getValue('days_leave_with_pay'));					
			$total_rd_hrs_worked  = $ph->getValue('restday_hours');	
			$total_regular_overtime_hrs    = $ph->getValue('regular_ot_hours');							
			$total_legal_holiday_hrs       = $ph->getValue('holiday_legal_hours');
			$total_special_holiday_hrs     = $ph->getValue('holiday_special_hours');
			$total_regular_ns_overtime_hrs = $ph->getValue('regular_ns_ot_hours');
			$total_regular_ns 		       = $ph->getValue('regular_ns_hours');
			$total_special_overtime_hrs    = $ph->getValue('holiday_special_ot_hours');
			$total_special_ns_hrs 		   = $ph->getValue('holiday_special_ns_hours');
			$total_special_ns_overtime_hrs = $ph->getValue('holiday_special_ns_ot_hours');
			$total_legal_overtime_hrs      = $ph->getValue('holiday_legal_ot_hours');
			$total_legal_ns_hrs			   = $ph->getValue('holiday_legal_ns_hours');
			$total_legal_ns_overtime_hrs   = $ph->getValue('holiday_legal_ns_ot_hours');
			$total_rd_ns_hrs 			   = $ph->getValue('restday_ns_hours');
			$total_rd_special_hrs          = $ph->getValue('restday_special_hours');
			$total_rd_special_overtime_hrs = $ph->getValue('restday_special_ot_hours');
			$total_rd_special_ns_hrs       = $ph->getValue('restday_special_ns_hours');
			$total_rd_special_ns_overtime_hrs = $ph->getValue('restday_special_ns_ot_hours');
			$total_rd_legal_hrs 		      = $ph->getValue('restday_legal_hours');
			$total_rd_legal_overtime_hrs      = $ph->getValue('restday_legal_ot_hours');

			//$total_rd_legal_ns_overtime_hrs      = $ph->getValue('total_rest_day_ns_ot');
			$total_restday_ns_ot_hours = $ph->getValue('restday_ns_ot_hours');

			$total_rd_legal_ns_overtime_hrs   = $ph->getValue('restday_legal_ns_ot_hours');
			$total_rest_day_ot 				  = $ph->getValue('restday_ot_hours');

		 $total_rest_day_legal_ns = $ph->getValue('restday_legal_ns_hours');
			$total_rest_day_legal_ns_ot      = $ph->getValue('restday_legal_ns_ot_hours');



			$total_ceta_days				  = $ph->getValue('ceta_days_with_pay');
			$total_sea_days 				  = $ph->getValue('sea_days_with_pay');

			foreach($earnings as $earning){
				$variable_name = $earning->getVariable();
				$label 		   = $earning->getLabel();
				$amount        = $earning->getAmount();

				$new_earnings[$variable_name]['label'] = $label;
				$new_earnings[$variable_name]['amount'] = $amount;

				switch ($variable_name) {

					case 'basic_pay':
						$new_earnings[$variable_name]['total_hours'] = $total_hrs_worked;
						$new_earnings[$variable_name]['total_days']  = $total_present_days;
						break;
					case 'total_rest_day':
						$new_earnings[$variable_name]['total_hours'] = $total_rd_hrs_worked;
						break;
					case 'total_regular_ot_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_regular_overtime_hrs;
						break;
					case 'total_legal_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_legal_holiday_hrs;
						break;
					case 'total_special_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_special_holiday_hrs;
						break;
					case 'total_regular_ns_ot_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_regular_ns_overtime_hrs;
						break;
					case 'total_regular_ns_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_regular_ns;
						break;
					case 'total_special_ot_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_special_overtime_hrs;
						break;
					case 'total_special_ns_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_special_ns_hrs;
						break;
					case 'total_special_ns_ot_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_special_ns_overtime_hrs;
						break;
					case 'total_legal_ot_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_legal_overtime_hrs;
						break;
					case 'total_legal_ns_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_legal_ns_hrs;
						break;
					case 'total_legal_ns_ot_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_legal_ns_overtime_hrs;
						break;
					case 'total_rest_day_ns':
						$new_earnings[$variable_name]['total_hours'] = $total_rd_ns_hrs;
						break;
					case 'total_rest_day_special':
						$new_earnings[$variable_name]['total_hours'] = $total_rd_special_hrs;
						break;
					case 'total_rest_day_special_ot':
						$new_earnings[$variable_name]['total_hours'] = $total_rd_special_overtime_hrs;
						break;
					case 'total_rest_day_special_ns':
						$new_earnings[$variable_name]['total_hours'] = $total_rd_special_ns_hrs;
						break;
					case 'total_rest_day_special_ns_ot':
						$new_earnings[$variable_name]['total_hours'] = $total_rd_special_ns_overtime_hrs;
						break;
					case 'total_rest_day_legal':
						$new_earnings[$variable_name]['total_hours'] = $total_rd_legal_hrs;
						break;
					case 'total_rest_day_legal_ot':
						$new_earnings[$variable_name]['total_hours'] = $total_rd_legal_overtime_hrs;
						break;
						case 'total_rest_day_legal_ns':
						$new_earnings[$variable_name]['total_hours'] = $total_rest_day_legal_ns;
						break;
					case 'total_rest_day_legal_ns_ot': //here
						$new_earnings[$variable_name]['total_hours'] = $total_rest_day_legal_ns_ot;
						break;
					case 'total_rest_day_ot':
						$new_earnings[$variable_name]['total_hours'] = $total_rest_day_ot;
						break;
					case 'total_rest_day_ns_ot':
						$new_earnings[$variable_name]['total_hours'] = $total_restday_ns_ot_hours;
						break;
					case 'total_ceta_amount':
						$new_earnings[$variable_name]['total_days'] = $total_ceta_days;
						break;
					case 'total_sea_amount':
						$new_earnings[$variable_name]['total_days'] = $total_sea_days;
						break;
					default:
						break;
				}

			}
		}
		//Utilities::displayArray($new_earnings);
		return $new_earnings;
	}

	public function getBasicEarningsDepre() {
		$new_earnings = array();

		if( !empty($this->employee) && !empty($this->period_start_date) && !empty($this->period_end_date) ){
			$at = G_Attendance_Finder::findByEmployeeAndPeriod($this->employee, $this->period_start_date, $this->period_end_date);

			$total_hrs_worked     = 0;
			$total_days_worked    = 0;
			$total_rd_days_worked = 0;
			$total_rd_hrs_worked  = 0;
			$total_regular_overtime_hrs = 0;
			$total_legal_overtime_hrs 	= 0;
			$total_legal_holiday_hrs    = 0;
			$total_special_holiday_hrs  = 0;

			foreach( $at as $t ){
				$data = $t->groupTimesheetData();
				foreach( $data as $key => $value ){
					switch ($key) {
						case 'attendance':

							$required_hrs_work = $data['schedule']['Total Required Working HRS (Less Break Time)'];
							if( $value['Total HRS Worked (Less Break Time)'] <= $required_hrs_work ){
								$hrs_worked = $value['Total HRS Worked (Less Break Time)'];
							}else{
								$hrs_worked = $required_hrs_work;
							}

							$total_regular_ns  += $value['Total Night Shift Hours'];
							$total_days_worked += 1;
							$total_hrs_worked  += $hrs_worked;
							break;
						case 'restday':
							$total_rd_days_worked += 1;
							$total_rd_hrs_worked  += $value['Total HRS'];
							break;
						case 'overtime':
							$total_regular_overtime_hrs    += $value['Regular Overtime HRS'];
							$total_legal_overtime_hrs      += $value['Legal Holiday Overtime HRS'];
							$total_regular_ns_overtime_hrs += $value['Regular Night Shift Overtime HRS'];
							break;
						case 'holiday':
							$total_legal_holiday_hrs   += $value['Legal Holiday Total HRS'];
							$total_special_holiday_hrs += $value['Special Holiday Total HRS'];
						default:
							break;
					}
				}
			}

			$earnings = (array) self::getEarnings();
			foreach($earnings as $earning){
				$variable_name = $earning->getVariable();
				$label 		   = $earning->getLabel();
				$amount        = $earning->getAmount();

				$new_earnings[$variable_name]['label'] = $label;
				$new_earnings[$variable_name]['amount'] = $amount;

				switch ($variable_name) {
					case 'basic_pay':
						$new_earnings[$variable_name]['total_hours'] = $total_hrs_worked;
						$new_earnings[$variable_name]['total_days']  = $total_days_worked;
						break;
					case 'total_rest_day':
						$new_earnings[$variable_name]['total_hours'] = $total_rd_hrs_worked;
						$new_earnings[$variable_name]['total_days']  = $total_rd_days_worked;
						break;
					case 'total_regular_ot_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_regular_overtime_hrs;
						break;
					case 'total_legal_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_legal_overtime_hrs;
						break;
					case 'total_special_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_special_holiday_hrs;
						break;
					case 'total_legal_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_legal_holiday_hrs;
						break;
					case 'total_regular_ns_ot_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_regular_ns_overtime_hrs;
					case 'total_regular_ns_amount':
						$new_earnings[$variable_name]['total_hours'] = $total_regular_ns;
					default:
						break;
				}

			}
		}

		return $new_earnings;
	}

	public function getTardinessDeductions() {
		$new_deductions = array();
		if( !empty($this->employee) ){

			$deductions = (array) self::getDeductions();
			$ph 	    = new G_Payslip_Helper($this);

			$total_late_hrs       = $ph->getValue('late_hours');
			$total_undertime_hrs  = $ph->getValue('undertime_hours');
			$total_absent_days    = $ph->getValue('absent_days_without_pay');

			foreach($deductions as $deduction){
				$variable_name = $deduction->getVariable();
				$label 		   = $deduction->getLabel();
				$amount        = $deduction->getAmount();

				$new_deductions[$variable_name]['label'] = $label;
				$new_deductions[$variable_name]['amount'] = $amount;

				switch ($variable_name) {
					case 'late_amount':
						$new_deductions[$variable_name]['total_hours'] = $total_late_hrs;
						break;
					case 'undertime_amount':
						$new_deductions[$variable_name]['total_hours'] = $total_undertime_hrs;
						break;
					case 'absent_amount':
						$new_deductions[$variable_name]['total_days']  = $total_absent_days;
						break;
					default:
						break;
				}

			}
		}

		return $new_deductions;
	}

	public function getTardinessDeductionsDepre() {
		$new_deductions = array();
		if( !empty($this->employee) && !empty($this->period_start_date) && !empty($this->period_end_date) ){
			$at = G_Attendance_Finder::findByEmployeeAndPeriod($this->employee, $this->period_start_date, $this->period_end_date);

			$total_late_hrs       = 0;
			$total_undertime_hrs  = 0;
			$total_absent_days    = 0;
			$total_absent_hrs     = 0;

			foreach( $at as $t ){
				$data = $t->groupTimesheetData();
				foreach( $data as $key => $value ){
					switch ($key) {
						case 'tardiness':
							$total_late_hrs 	 += $value['Total Late HRS'];
							$total_undertime_hrs += $value['Total Undertime HRS'];
							$total_absent_days   += $value['Total Absent Days'];
						default:
							break;
					}
				}
			}

			$deductions = (array) self::getDeductions();
			foreach($deductions as $deduction){
				$variable_name = $deduction->getVariable();
				$label 		   = $deduction->getLabel();
				$amount        = $deduction->getAmount();

				$new_deductions[$variable_name]['label'] = $label;
				$new_deductions[$variable_name]['amount'] = $amount;

				switch ($variable_name) {
					case 'late_amount':
						$new_deductions[$variable_name]['total_hours'] = $total_late_hrs;
						break;
					case 'undertime_amount':
						$new_deductions[$variable_name]['total_hours'] = $total_undertime_hrs;
						break;
					case 'absent_amount':
						$new_deductions[$variable_name]['total_days']  = $total_absent_days;
						break;
					default:
						break;
				}

			}
		}

		return $new_deductions;
	}

	public function getProcessedPayroll($from, $to, $additional_qry) {

        $e = new G_Employee();
        $data = $e->getProcessedAndUnprocessedEmployeePayrollByCutoff($from, $to, $additional_qry );
        $processed_payroll_data = $data['processed_payroll_data'];
        //Utilities::displayArray($processed_payroll_data);

        // get required label from serialized data -- (LABEL NAME)
        $required_labels = array("Late Amount","Undertime Amount","Absent Amount","SSS","Pagibig","Philhealth","Salary Rate");

        // decalre those column that has checkbox -- (LABEL NAME)
        $d['col_with_checkbox'] = array("Late Amount","Undertime Amount","Absent Amount","SSS","Pagibig","Philhealth");

        // initial header -- default header
        $d['label_header'] = array(
        	"employee_id" => "Employee ID",
        	"employee_name" => "Employee Name",
        	"basic_pay" 	=> "Basic Pay",
        	"net_pay"		=> "Net Pay",
        	"gross_pay"		=> "Gross Pay"
        );

	    foreach($processed_payroll_data as $key => $value) {
	    	$arr_deductions 		= unserialize($value['deductions']);
	    	$arr_other_deductions 	= unserialize($value['other_deductions']);
	    	$arr_labels 			= unserialize($value['labels']);

	    	//Utilities::displayArray($arr_labels);
	    	//Utilities::displayArray($arr_deductions);

	    	// initial row_data
	    	$d['row_data'][$key] = array(
	    		"employee_id" => $value['employee_code'],
	    		"employee_name" => $value['firstname'] . " " .$value['lastname'],
	    		"basic_pay"	=> $value['basic_pay'],
	    		"net_pay"	=> $value['net_pay'],
	    		"gross_pay"	=> $value['gross_pay'],
	    	);

	    	//FROM LABELS
	    	foreach($arr_labels as $l_key => $labels) {
	    		if( in_array($labels->getLabel(),$required_labels) ) {
	    			//array_push($d['label_header'],$labels->getLabel());
	    			$d['label_header'][$labels->getVariable()] = $labels->getLabel();
	    			$d['label_header'] = array_unique($d['label_header']);
	    			$d['row_data'][$key][$labels->getVariable()] = $labels->getValue();
	    		}
	    	}

	    	//FROM DEDUCTIONS
	    	foreach($arr_deductions as $d_key => $deduction) {
	    		if( in_array($deduction->getLabel(),$required_labels) ) {
	    			//array_push($d['label_header'],$deduction->getLabel());
	    			$d['label_header'][$deduction->getVariable()] = $deduction->getLabel();
	    			$d['label_header'] = array_unique($d['label_header']);
	    			$d['row_data'][$key][$deduction->getVariable()] = $deduction->getAmount();
	    		}
	    	}

	    	//FROM OTHER DEDUCTIONS
	    	foreach($arr_other_deductions as $od_key => $other_deduction) {
    			//array_push($d['label_header'],$other_deduction->getLabel());
    			if($other_deduction->getVariable() != "employee_deduction") {
    				$d['label_header'][$other_deduction->getVariable()] = $other_deduction->getLabel();
    				$d['label_header'] = array_unique($d['label_header']);
    				$d['row_data'][$key][$other_deduction->getVariable()] = $other_deduction->getAmount();
    			}


    			// Automatically add checkbox on dynamic deduction
    			array_push($d['col_with_checkbox'],$other_deduction->getLabel());

	    	}
	    }
	    //Utilities::displayArray($d);
	    return $d;
	}

	public function save() {
		return G_Payslip_Manager::save($this);
	}

	public function wrapPayslipArray($payslip_data) {
		$this->payslip_array = $payslip_data;
		return $this;
	}

	public function getPayslipData($section, $template, $fields = NULL, $cutoff = array()) {
		
		$payslip_array 			= $this->payslip_array;
    	$emp_earnings 			= unserialize($payslip_array['earnings']);
    	$emp_other_earnings 	= unserialize($payslip_array['other_earnings']);
    	$emp_deduction 			= unserialize($payslip_array['deductions']);
    	$emp_other_deductions	= unserialize($payslip_array['other_deductions']);
    	$emp_labels			 	= unserialize($payslip_array['labels']);    	
    	
    	if($section == 'earnings' && $template == 2) { //for template 2 (daiichi)

    		foreach ($emp_earnings as $earnings) {
				$variable 							     = strtolower($earnings->getVariable());
				$wrap_earnings_array[$variable]['label'] = $earnings->getLabel();
				$wrap_earnings_array[$variable]['value'] = $earnings->getAmount();
			}

			$earning_from_deduct_tardi = $emp_deduction[0]; //tardines or late
			$wrap_earnings_array[$earning_from_deduct_tardi->getVariable()]['label'] = $earning_from_deduct_tardi->getLabel();
			$wrap_earnings_array[$earning_from_deduct_tardi->getVariable()]['value'] = $earning_from_deduct_tardi->getAmount();

			$earning_from_deduct_undertime = $emp_deduction[1]; //undertime
			$wrap_earnings_array[$earning_from_deduct_undertime->getVariable()]['label'] = $earning_from_deduct_undertime->getLabel();
			$wrap_earnings_array[$earning_from_deduct_undertime->getVariable()]['value'] = $earning_from_deduct_undertime->getAmount();

			$earning_from_deduct_absent = $emp_deduction[2]; //absent
			$wrap_earnings_array[$earning_from_deduct_absent->getVariable()]['label'] = $earning_from_deduct_absent->getLabel();
			$wrap_earnings_array[$earning_from_deduct_absent->getVariable()]['value'] = $earning_from_deduct_absent->getAmount();

			$wrap_arrays = $wrap_earnings_array;

		}elseif ($section == 'other_earnings' && $template == 2) {
    		foreach ($emp_other_earnings as $o_earnings) {
				$variable 							     = strtolower( preg_replace('/\s+/', '_', $o_earnings->getVariable()) );
				$wrap_earnings_array[$variable]['label'] = $o_earnings->getLabel();
				$wrap_earnings_array[$variable]['value'] += $o_earnings->getAmount();
			}

			$wrap_arrays = $wrap_earnings_array;
    	}elseif($section == 'deductions' && $template == 2) { //for template 2 (daiichi)

    		foreach($emp_deduction as $deduction) {
			  $variable_deduction   							  = strtolower($deduction->getVariable());
			  $wrap_deduction_array[$variable_deduction]['label'] = $deduction->getLabel();
			  $wrap_deduction_array[$variable_deduction]['value'] += $deduction->getAmount();
    		}

    		foreach($emp_other_deductions as $other_deduction) {    		
			  $variable_other_deduction   								= strtolower( preg_replace('/\s+/', '_', $other_deduction->getLabel())  );
			  $wrap_deduction_array[$variable_other_deduction]['label'] = $other_deduction->getLabel();
			  $wrap_deduction_array[$variable_other_deduction]['value'] += $other_deduction->getAmount();
    		}

    		$wrap_arrays = $wrap_deduction_array;
    	}elseif($section == 'breakdown' && $template == 2) { //for template 2 (daiichi)

    		foreach($emp_labels as $labels) {
				$variable 							     = strtolower($labels->getVariable());
				$wrap_labels_array[$variable]['label'] = $labels->getLabel();
				$wrap_labels_array[$variable]['value'] = $labels->getValue();
    		}

    		$wrap_arrays = $wrap_labels_array;

    	}elseif($section == 'loan_leave' && $template == 2){

    		foreach($this->payslip_array as $labels) {

				$variable 							    = strtolower(preg_replace('/\s+/', '_', $labels['loan_type']));
				$wrap_labels_array[$variable]['label']  = $labels['loan_type'];
				$wrap_labels_array[$variable]['value'] += $labels['loan_amount'] - $labels['amount_paid'];
    		}

    		$wrap_arrays = $wrap_labels_array;
    	}elseif($section == 'earnings' && $template == 3) { //for template3 (artnature)
    		foreach ($emp_earnings as $earnings) {
				$variable 							     = strtolower($earnings->getVariable());
				$wrap_earnings_array[$variable]['label'] = $earnings->getLabel();
				$wrap_earnings_array[$variable]['value'] = $earnings->getAmount();
			}

			$earning_from_deduct_tardi = $emp_deduction[0]; //tardines or late
			$wrap_earnings_array[$earning_from_deduct_tardi->getVariable()]['label'] = $earning_from_deduct_tardi->getLabel();
			$wrap_earnings_array[$earning_from_deduct_tardi->getVariable()]['value'] = $earning_from_deduct_tardi->getAmount();

			$earning_from_deduct_undertime = $emp_deduction[1]; //undertime
			$wrap_earnings_array[$earning_from_deduct_undertime->getVariable()]['label'] = $earning_from_deduct_undertime->getLabel();
			$wrap_earnings_array[$earning_from_deduct_undertime->getVariable()]['value'] = $earning_from_deduct_undertime->getAmount();

			$earning_from_deduct_absent = $emp_deduction[2]; //absent
			$wrap_earnings_array[$earning_from_deduct_absent->getVariable()]['label'] = $earning_from_deduct_absent->getLabel();
			$wrap_earnings_array[$earning_from_deduct_absent->getVariable()]['value'] = $earning_from_deduct_absent->getAmount();

			$wrap_arrays = $wrap_earnings_array;

		}elseif($section == 'earnings' && $template == 4) { //for template4 (matex)
    		foreach ($emp_earnings as $earnings) {
				$variable 							     = strtolower($earnings->getVariable());
				$wrap_earnings_array[$variable]['label'] = $earnings->getLabel();
				$wrap_earnings_array[$variable]['value'] = $earnings->getAmount();
			}

			$earning_from_deduct_tardi = $emp_deduction[0]; //tardines or late
			$wrap_earnings_array[$earning_from_deduct_tardi->getVariable()]['label'] = $earning_from_deduct_tardi->getLabel();
			$wrap_earnings_array[$earning_from_deduct_tardi->getVariable()]['value'] = $earning_from_deduct_tardi->getAmount();

			$earning_from_deduct_undertime = $emp_deduction[1]; //undertime
			$wrap_earnings_array[$earning_from_deduct_undertime->getVariable()]['label'] = $earning_from_deduct_undertime->getLabel();
			$wrap_earnings_array[$earning_from_deduct_undertime->getVariable()]['value'] = $earning_from_deduct_undertime->getAmount();

			$earning_from_deduct_absent = $emp_deduction[2]; //absent
			$wrap_earnings_array[$earning_from_deduct_absent->getVariable()]['label'] = $earning_from_deduct_absent->getLabel();
			$wrap_earnings_array[$earning_from_deduct_absent->getVariable()]['value'] = $earning_from_deduct_absent->getAmount();

			$wrap_arrays = $wrap_earnings_array;

    	}elseif($section == 'deductions' && $template == 3) { //for template3 (artnature)
    		foreach($emp_deduction as $deduction) {
    			foreach($fields as $field) {
    				if($field == strtolower($deduction->getVariable())) {
    					$variable_deduction   							  = strtolower($deduction->getVariable());
						$wrap_deduction_array[$variable_deduction]['label'] = $deduction->getLabel();
						$wrap_deduction_array[$variable_deduction]['value'] = $deduction->getAmount();
    				}
    			}
    		}

    		foreach($emp_other_deductions as $other_deduction) {
    			$variable_other_deduction  = strtolower( preg_replace('/\s+/', '_', $other_deduction->getLabel())  );
    			foreach($fields as $field) {
    				if($field == $variable_other_deduction) {
					  $wrap_deduction_array[$variable_other_deduction]['label'] = $other_deduction->getLabel();
					  $wrap_deduction_array[$variable_other_deduction]['value'] = $other_deduction->getAmount();
    				}
    			}

    		}

    		$wrap_arrays = $wrap_deduction_array;

		}elseif($section == 'deductions' && $template == 4) { //for template4 (matex)
    		foreach($emp_deduction as $deduction) {
    			foreach($fields as $field) {
    				if($field == strtolower($deduction->getVariable())) {
    					$variable_deduction   							  = strtolower($deduction->getVariable());
						$wrap_deduction_array[$variable_deduction]['label'] = $deduction->getLabel();
						$wrap_deduction_array[$variable_deduction]['value'] = $deduction->getAmount();
    				}
    			}
    		}

    		foreach($emp_other_deductions as $other_deduction) {
    			$variable_other_deduction  = strtolower( preg_replace('/\s+/', '_', $other_deduction->getLabel())  );
    			foreach($fields as $field) {
    				if($field == $variable_other_deduction) {
					  $wrap_deduction_array[$variable_other_deduction]['label'] = $other_deduction->getLabel();
					  $wrap_deduction_array[$variable_other_deduction]['value'] = $other_deduction->getAmount();
    				}
    			}

    		}

    		$wrap_arrays = $wrap_deduction_array;

    	}elseif($section == 'breakdown' && $template == 3) { //for template 3 (artnature)

    		foreach($emp_labels as $labels) {
				$variable 							     = strtolower($labels->getVariable());
				$wrap_labels_array[$variable]['label'] = $labels->getLabel();
				$wrap_labels_array[$variable]['value'] = $labels->getValue();
    		}

    		$wrap_arrays = $wrap_labels_array;

    	}elseif($section == 'breakdown' && $template == 4) { //for template 4 (matex)

    		foreach($emp_labels as $labels) {
				$variable 							     = strtolower($labels->getVariable());
				$wrap_labels_array[$variable]['label'] = $labels->getLabel();
				$wrap_labels_array[$variable]['value'] = $labels->getValue();
    		}

    		$wrap_arrays = $wrap_labels_array;

    	}elseif( $section == 'loan_balance' ){     		
    		if( !empty($cutoff) ){    			
    			$from = date("Y-m-d",strtotime($cutoff[0]));		
    			$to   = date("Y-m-d",strtotime($cutoff[1]));    			
	    		$loan = new G_Employee_Loan();    		

	    		$loan->setEmployeeId($payslip_array['id']);
	    		/*$wrap_labels_array['pagibig_loan']['label']	= "Pagibig Loan";
	    		$wrap_labels_array['pagibig_loan']['value'] = $loan->getLoanBalanceFromStartPeriod("Pagibig Loan", $to);

	    		$wrap_labels_array['sss_loan']['label'] 		= "SSS Loan";
	    		$wrap_labels_array['sss_loan']['value'] 		= $loan->getLoanBalanceFromStartPeriod("SSS Loan", $to);

	    		
	    		$wrap_labels_array['sss_salary_loan']['label'] 	= "SSS Salary Loan";
	    		$wrap_labels_array['sss_salary_loan']['value'] 	= $loan->getLoanBalanceFromStartPeriod("SSS Salary Loan", $to);	

	    		$wrap_labels_array['sss_calamity_loan']['label']  = "SSS Calamity Loan";
	    		$wrap_labels_array['sss_calamity_loan']['value']  = $loan->getLoanBalanceFromStartPeriod("SSS Calamity Loan", $to);	    		

	    		$wrap_labels_array['pagibig_calamity_loan']['label']  = "Pagibig Calamity Loan";
	    		$wrap_labels_array['pagibig_calamity_loan']['value']  = $loan->getLoanBalanceFromStartPeriod("Pagibig Calamity Loan", $to);	    			    		

	    		$wrap_labels_array['pagibig_salary_loan']['label']  = "Pagibig Salary Loan";
	    		$wrap_labels_array['pagibig_salary_loan']['value']  = $loan->getLoanBalanceFromStartPeriod("Pagibig Salary Loan", $to);	    			    		

	    		$wrap_labels_array['salary_loan']['label'] 		= "Salary Loan";
	    		$wrap_labels_array['salary_loan']['value']		= $loan->getLoanBalanceFromStartPeriod("Company Loan", $to);

	    		$wrap_labels_array['emergency_loan']['label'] 	= "Emergency Loan";
	    		$wrap_labels_array['emergency_loan']['value']	= $loan->getLoanBalanceFromStartPeriod("Emergency Loan", $to);

	    		$wrap_labels_array['hmo']['label'] 				= "HMO";
	    		$wrap_labels_array['hmo']['value'] 				= $loan->getLoanBalanceFromStartPeriod("HMO", $to);

	    		$wrap_labels_array['educational_loan']['label'] = "Education Loan";
	    		$wrap_labels_array['educational_loan']['value'] = $loan->getLoanBalanceFromStartPeriod("Education Loan", $to);*/


	    		$g_loan = G_Loan_Type_Finder::findAllIsNotArchive();
	    		foreach($g_loan as $g){
	    			$name = trim($g->getLoanType());
	    			$balance = $loan->getLoanBalanceFromStartPeriod($name, $to);
	    			$label = str_replace(' ', '_', strtolower($name));

	    			$wrap_labels_array[$label]['label'] = $name;
	    			$wrap_labels_array[$label]['value'] = $balance;

	    		}


	    		$wrap_arrays = $wrap_labels_array;

	    	}
    	}elseif( $section == 'yearly_bonus' ){   
    		$from = date("Y-m-d",strtotime($cutoff[0]));		
    		$to   = date("Y-m-d",strtotime($cutoff[1])); 
    		$yb = new G_Yearly_Bonus();
    		$yb->setEmployeeId($payslip_array['id']);

    		$wrap_labels_array['yearly_bonus']['label'] = "13th Month Bonus";
	    	$wrap_labels_array['yearly_bonus']['value'] = $yb->getEmployeeYearlyBonusByStartAndEndCutoff($from, $to);	 
	    	
	    	$wrap_arrays = $wrap_labels_array;   	   	
    	}    	

    	return $wrap_arrays;
	}

	public function generatePayslip() {
		//G_Payslip_Manager
	}

	public function delete() {
		return G_Payslip_Manager::delete($this);
	}	
}
?>
