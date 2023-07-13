<?php
error_reporting(0);

define("BASE_PATH", str_replace("\\", "/", realpath(dirname(__FILE__))).'/');

class TestTimesheetErrors extends UnitTestCase {

    function cutoffDetails() {

    	$details['employee_code']     = 7983;
    	$details['cutoff_start_date'] = '2018-02-26';
    	$details['cutoff_end_date']   = '2018-03-10';

    	$details['month'] 			  = 03;
    	$details['cutoff_number'] 	  = 1;
    	$details['year'] 		 	  = 2018;

    	return $details;
    }

    function employeeAttendanceVariables() {

    	/* start -----*/
        $employee_att['2018-03-01']['date_in']  = "2018-03-01";
        $employee_att['2018-03-01']['date_out'] = "2018-03-01";
        $employee_att['2018-03-01']['time_in']  = "08:00:00";
        $employee_att['2018-03-01']['time_out'] = "20:30:00";

        $employee_att['2018-03-01']['expected_ot']        = 3.50;
        $employee_att['2018-03-01']['expected_late']      = 0.50;
        $employee_att['2018-03-01']['expected_undertime'] = 0;
        $employee_att['2018-03-01']['expected_ns_hours']  = 0;
        $employee_att['2018-03-01']['expected_reg_ns_ot_hours']   = 0;          
        $employee_att['2018-03-01']['expected_legal_ns_ot_hours'] = 0;          
        $employee_att['2018-03-01']['expected_spec_ns_ot_hours']  = 0;          
        /* end -----*/

    	/* start -----*/
        $employee_att['2018-03-08']['date_in']  = "2018-03-08";
        $employee_att['2018-03-08']['date_out'] = "2018-03-08";
        $employee_att['2018-03-08']['time_in']  = "20:30:00";
        $employee_att['2018-03-08']['time_out'] = "07:30:00";

        $employee_att['2018-03-08']['expected_ot']        = 1;
        $employee_att['2018-03-08']['expected_late']      = 0;
        $employee_att['2018-03-08']['expected_undertime'] = 0;   
        $employee_att['2018-03-08']['expected_ns_hours']  = 8;
        $employee_att['2018-03-08']['expected_reg_ns_ot_hours']   = 0;          
        $employee_att['2018-03-08']['expected_legal_ns_ot_hours'] = 2.9333;          
        $employee_att['2018-03-08']['expected_spec_ns_ot_hours']  = 0;
        /* end -----*/

    	/* start -----*/
        $employee_att['2018-03-09']['date_in']  = "2018-03-09";
        $employee_att['2018-03-09']['date_out'] = "2018-03-09";
        $employee_att['2018-03-09']['time_in']  = "20:30:00";
        $employee_att['2018-03-09']['time_out'] = "07:30:00";

        $employee_att['2018-03-09']['expected_ot']        = 1;
        $employee_att['2018-03-09']['expected_late']      = 0;
        $employee_att['2018-03-09']['expected_undertime'] = 0;   
        $employee_att['2018-03-09']['expected_ns_hours']  = 8;    
        $employee_att['2018-03-09']['expected_reg_ns_ot_hours']   = 2.9333;          
        $employee_att['2018-03-09']['expected_legal_ns_ot_hours'] = 0;          
        $employee_att['2018-03-09']['expected_spec_ns_ot_hours']  = 0;              
        /* end -----*/

    	/* start -----*/
        $employee_att['2018-03-06']['date_in']  = "2018-03-06";
        $employee_att['2018-03-06']['date_out'] = "2018-03-06";
        $employee_att['2018-03-06']['time_in']  = "08:00:00";
        $employee_att['2018-03-06']['time_out'] = "17:30:00";

        $employee_att['2018-03-06']['expected_ot']        = 0.00;
        $employee_att['2018-03-06']['expected_late']      = 0.50;
        $employee_att['2018-03-06']['expected_undertime'] = 0;
        $employee_att['2018-03-06']['expected_ns_hours']  = 0;
        $employee_att['2018-03-06']['expected_reg_ns_ot_hours']   = 0;          
        $employee_att['2018-03-06']['expected_legal_ns_ot_hours'] = 0;          
        $employee_att['2018-03-06']['expected_spec_ns_ot_hours']  = 0;          
        /* end -----*/            

        return $employee_att;
    }

    function expectedPayslipOutput() {
    	/* Earnings */
    	$output['basic_pay'] 					= 6860;
    	$output['total_regular_ot_amount'] 		= 821.99;
    	$output['total_regular_ns_ot_amount'] 	= 24.111433569785;
    	$output['total_regular_ns_amount'] 		= 52.61;
    	$output['total_legal_amount'] 			= 1052.1472392638;
    	$output['total_legal_ot_amount'] 		= 256.46;
    	$output['total_legal_ns_amount'] 		= 157.82;
    	$output['total_legal_ns_ot_amount'] 	= 75.23;
    	$output['total_ceta_amount'] 			= 0.00;
    	$output['total_sea_amount'] 			= 0.00;

    	/* Other Earnings */
    	$output['POSITION ALLOWANCE :500.00']	= 250;
    	$output['Meal Allowance'] 				= 210;
    	$output['Transpo Allowance'] 			= 210;
    	$output['Rice Allowance With 4 Absents']= 650;
    	$output['OT Allowance'] 				= 140;

    	/* Deductions */
    	$output['late_amount'] 		= 65.76;
    	$output['undertime_amount'] = 98.65;
    	$output['absent_amount'] 	= 1578.22;
    	$output['sss'] 				= 581.30;
    	$output['pagibig'] 			= 0.00;
    	$output['philhealth'] 		= 188.65;
    	$output['witholding tax'] 	= 0.00;

    	/* SUMMARY */
    	$output['total_earnings'] 	= 10689.25;
    	$output['total_deductions'] = 2512.57;
    	$output['net_pay'] 			= 8176.68;

    	/* Other Deductions */
    	$output['tax_bonus_service_award'] 	= 0;

    	return $output;
    }    

	function testTimesheetPerCutoff() {	

		$cutoff_details = $this->cutoffDetails();

		$employee_code   	= $cutoff_details['employee_code'];
        $cutoff_start_date  = $cutoff_details['cutoff_start_date'];
        $cutoff_end_date    = $cutoff_details['cutoff_end_date'];

		$e = G_Employee_Finder::findByEmployeeCode($employee_code);
		$employee_id = $e->getId();
		$s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($e, $cutoff_end_date);


		echo "<b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 24px;'>Timesheet: " . $e->getLastName(). " " . $e->getFirstName() . "</b><hr />";

		echo '
					<table width="100%" class="">  
					  <tr>
					  	<td>&nbsp;</td>
					    <td><strong>Date</strong></td>
					    <td><strong>Day</strong></td>
					    <td><strong>In-Out</strong></td>
					    <td><strong>OT In-Out</strong></td>
					    <td><strong>Total Hrs</strong></td>
					    <td><strong>Total OT Hrs</strong></td>

					    <td><strong>NS Hrs</strong></td>
					    <td><strong>Reg. NS OT Hrs</strong></td>
					    <td><strong>Legal NS OT Hrs</strong></td>
					    <td><strong>Spec NS OT Hrs</strong></td>

					    <td><strong>Late Hours</strong></td>
					    <td><strong>Undertime Hours</strong></td>


					  </tr>
			';		

		$emp_attendance   = $this->employeeAttendanceVariables(); 
		$total_attendance = count($emp_attendance);

		if( $e ){					
			$output = "";

			$rec_total = 0;
			foreach( $emp_attendance as $date_attendance_key => $attendande_data ) {
				$date_attendance = $date_attendance_key;
				$date_in  		 = $attendande_data['date_in'];
				$time_in  		 = $attendande_data['time_in'];

				$date_out 		 = $attendande_data['date_out'];
				$time_out 		 = $attendande_data['time_out'];

				$date_time_in  = array($date_in => $time_in);
				$date_time_out = array($date_out => $time_out); 

				$at = new G_Attendance_Log();
				$at->setEmployeeId($e->getId());				
				$at->setDateTimeIn($date_time_in);
				$at->setDateTimeOut($date_time_out);
				//$return = $at->addAttendanceLog();
				$return = true;

				$from = $date_in;
				$to   = $date_out;				
				//G_Attendance_Helper::updateAttendanceByEmployeeIdPeriod($employee_id, $from, $to);
				if($return) {

					$error_ot_color 		= "";
					$error_ns_color 		= "";
					$error_reg_ns_ot_color 	= "";
					$error_lg_ns_ot_color 	= "";
					$error_sc_ns_ot_color 	= "";
					$error_late_color 		= "";
					$error_undertime_color 	= "";			

					$a = G_Attendance_Finder::findByEmployeeAndDate($e, $date_attendance);
					$t = $a->getTimesheet();

					if(preg_replace('/\s+/', ' ', $t->getTotalOvertimeHours()) != preg_replace('/\s+/', ' ', $attendande_data['expected_ot'])) {
						$error_ot_color = "#f04d4d";
					}

					if(preg_replace('/\s+/', ' ', $t->getLateHours()) != preg_replace('/\s+/', ' ', $attendande_data['expected_late'])) {
						$error_late_color = "#f04d4d";
					}

					if(preg_replace('/\s+/', ' ', $t->getUndertimeHours()) != preg_replace('/\s+/', ' ', $attendande_data['expected_undertime'])) {
						$error_undertime_color = "#f04d4d";
					}

					if(preg_replace('/\s+/', ' ', $t->getNightShiftHours()) != preg_replace('/\s+/', ' ', $attendande_data['expected_ns_hours'])) {
						$error_ns_color = "#f04d4d";
					}

					if(preg_replace('/\s+/', ' ', $t->getRegularOvertimeNightShiftHours()) != preg_replace('/\s+/', ' ', $attendande_data['expected_reg_ns_ot_hours'])) {
						$error_reg_ns_ot_color = "#f04d4d";
					}

					if(preg_replace('/\s+/', ' ', $t->getLegalOvertimeNightShiftHours()) != preg_replace('/\s+/', ' ', $attendande_data['expected_legal_ns_ot_hours'])) {
						$error_lg_ns_ot_color = "#f04d4d";
					}

					if(preg_replace('/\s+/', ' ', $t->getSpecialOvertimeHours()) != preg_replace('/\s+/', ' ', $attendande_data['expected_spec_ns_ot_hours'])) {
						$error_sc_ns_ot_color = "#f04d4d";
					}	

					/*
					echo '<pre>';
					print_r($t);
					echo '</pre>';
					*/

					if( $t->getOverTimeIn() != '' || $t->getOverTimeOut() != '' ){ 
						$overtime = Tools::timeFormat($t->getOverTimeIn()) . '-'. Tools::timeFormat($t->getOverTimeOut());	
					} else { $overtime = '-'; }

					echo '
					  <tr>
					  	<td>Output: </td>
					    <td>' . $a->getDate() . '</td>
					    <td>' . date('D', strtotime($a->getDate())) . '</td>
					    <td>'. Tools::timeFormat($t->getTimeIn()) . '-'. Tools::timeFormat($t->getTimeOut()) .'</td>
					    <td>'. $overtime .'</td>
					    <td>' . Tools::convertHourToTime($t->getTotalHoursWorked()) . '/' . $t->getTotalHoursWorked() . '</td>
					    <td style="background-color: ' . $error_ot_color . ';">' . Tools::convertHourToTime($t->getTotalOvertimeHours()) . '/' . $t->getTotalOvertimeHours() . '</td>

					    <td style="background-color: ' . $error_ns_color . ';">' . Tools::convertHourToTime($t->getNightShiftHours()) . '/' . $t->getNightShiftHours() . '</td>
					    <td style="background-color: ' . $error_reg_ns_ot_color . ';">' . Tools::convertHourToTime($t->getRegularOvertimeNightShiftHours()) . '/' . $t->getRegularOvertimeNightShiftHours() . '</td>
					    <td style="background-color: ' . $error_lg_ns_ot_color . ';">' . Tools::convertHourToTime($t->getLegalOvertimeNightShiftHours()) . '/' . $t->getLegalOvertimeNightShiftHours() . '</td>
					    <td style="background-color: ' . $error_sc_ns_ot_color . ';">' . Tools::convertHourToTime($t->getSpecialOvertimeHours()) . '/'. $t->getSpecialOvertimeHours() . '</td>

					    <td style="background-color: ' . $error_late_color . ';">' . Tools::convertHourToTime($t->getLateHours()) . '/' . $t->getLateHours() . '</td>
					    <td style="background-color: ' . $error_undertime_color . ';">' . Tools::convertHourToTime($t->getUndertimeHours()) . '/' . $t->getUndertimeHours() . '</td>
					  </tr>
					';	

					$error_ot_color 		= !empty($error_ot_color) ? $error_ot_color : '#41fe2e';
					$error_ns_color 		= !empty($error_ns_color) ? $error_ns_color : '#41fe2e';
					$error_reg_ns_ot_color 	= !empty($error_reg_ns_ot_color) ? $error_reg_ns_ot_color : '#41fe2e';
					$error_lg_ns_ot_color 	= !empty($error_lg_ns_ot_color) ? $error_lg_ns_ot_color : '#41fe2e';
					$error_sc_ns_ot_color 	= !empty($error_sc_ns_ot_color) ? $error_sc_ns_ot_color : '#41fe2e';
					$error_late_color 		= !empty($error_late_color) ? $error_late_color : '#41fe2e';
					$error_undertime_color 	= !empty($error_undertime_color) ? $error_undertime_color : '#41fe2e';

					echo '
					  <tr>
					  	<td style="background-color: #41fe2e;">Expected Output: </td>
					    <td style="background-color: #41fe2e;">-</td>
					    <td style="background-color: #41fe2e;">-</td>
					    <td style="background-color: #41fe2e;">-</td>
					    <td style="background-color: #41fe2e;">-</td>
					    <td style="background-color: #41fe2e;">-</td>
					    <td style="background-color: ' . $error_ot_color . ';">' . Tools::convertHourToTime($attendande_data['expected_ot']) . '/' . $attendande_data['expected_ot'] . '</td>

					    <td style="background-color: ' . $error_ns_color . ';">' . Tools::convertHourToTime($attendande_data['expected_ns_hours']). '/' . $attendande_data['expected_ns_hours'] .  '</td>
					    <td style="background-color: ' . $error_reg_ns_ot_color . ';">' . Tools::convertHourToTime($attendande_data['expected_reg_ns_ot_hours']). '/' . $attendande_data['expected_reg_ns_ot_hours'] .  '</td>
					    <td style="background-color: ' . $error_lg_ns_ot_color . ';">' . Tools::convertHourToTime($attendande_data['expected_legal_ns_ot_hours']). '/' . $attendande_data['expected_legal_ns_ot_hours'] .  '</td>
					    <td style="background-color: ' . $error_sc_ns_ot_color . ';">' . Tools::convertHourToTime($attendande_data['expected_spec_ns_ot_hours']). '/' . $attendande_data['expected_spec_ns_ot_hours'] .  '</td>

					    <td style="background-color: ' . $error_late_color . ';">' . Tools::convertHourToTime($attendande_data['expected_late']) . '/' . $attendande_data['expected_late'] . '</td>
					    <td style="background-color: ' . $error_undertime_color . ';">' . Tools::convertHourToTime($attendande_data['expected_undertime']) . '/' . $attendande_data['expected_undertime'] . '</td>
					  </tr>
					';										

				}

				$rec_total++;

				if($rec_total == $total_attendance) {
					echo "</table>";
				}

				$this->assertEqual($t->getTotalOvertimeHours(), $attendande_data['expected_ot']);
				$this->assertEqual($t->getLateHours(), $attendande_data['expected_late']);
				$this->assertEqual($t->getUndertimeHours(), $attendande_data['expected_undertime']);
				$this->assertEqual($t->getNightShiftHours(), $attendande_data['expected_ns_hours']);
				$this->assertEqual($t->getRegularOvertimeNightShiftHours(), $attendande_data['expected_reg_ns_ot_hours']);
				$this->assertEqual($t->getLegalOvertimeNightShiftHours(), $attendande_data['expected_legal_ns_ot_hours']);
				$this->assertEqual($t->getSpecialOvertimeHours(), $attendande_data['expected_spec_ns_ot_hours']);				
			}

		}

	}

    function testPayrollComputation() {
    	$cutoff_details  = $this->cutoffDetails();
    	$expected_output = $this->expectedPayslipOutput();

    	echo "<br /><b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 24px;'>Payroll Computation</b><br />";
    	echo " <b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 24px;'>Cutoff: ". $cutoff_details['cutoff_start_date'] ." to " . $cutoff_details['cutoff_end_date'] . " </b> ";
    	echo  "<br />Note: red with error<hr />";

    	$employee_code = $cutoff_details['employee_code'];
    	$e = G_Employee_Finder::findByEmployeeCode($employee_code);

    	if($e) {
	        $month 				= $cutoff_details['month'];
	        $cutoff_number  	= $cutoff_details['cutoff_number'];
	        $year 		    	= $cutoff_details['year'];
	        $selected_employee 	= $e->getId();

	        if ($year == '') {
	            $year = Tools::getGmtDate('Y');
	        } 

	        $additional_qry = "";

	        $c = new G_Company;
	        $c->setFilteredEmployeeId($selected_employee);
	        $c->setAdditionalQuery($additional_qry);
	        $payslips = $c->generatePayslip($month, $cutoff_number, $year);    

	        if($payslips) {
	        	//echo "Payroll has been successfully generated.<hr />";
	        	$payslip = $payslips[0];

	        	$employee_earnings        = $payslip->getBasicEarnings();
	        	$employee_other_earnings  = $payslip->getOtherEarnings();
	        	$employee_deductons       = $payslip->getDeductions();
	        	$employee_other_deductons = $payslip->getOtherDeductions();

	        	echo "<b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 14px;'>Earnings: " . $e->getLastName(). " " . $e->getFirstName() . "</b><br />";
				echo "
							<table width='100%'' class=''>  
							  <tr style='background-color:#dad3d3'>
							  	<td>&nbsp;</td>
							    <td><strong>Earnings</strong></td>
							    <td><strong>Days/Hours</strong></td>
							    <td><strong>Actual</strong></td>
							    <td><strong>Expected</strong></td>
							    <td><strong>--</strong></td>
							  </tr>
					";		 

				foreach($employee_earnings as $ee_key => $eed) {

					$label                = $eed["label"];
					$days_horus           = isset($eed['total_days']) ? $eed['total_days'] . ' day/s' : $eed['total_hours'] . ' hr/s';
					$actual_computation   = preg_replace('/\s+/', ' ', $eed['amount']); 
					$expected_computation = preg_replace('/\s+/', ' ', $expected_output[$ee_key]); 

					$error_color = "";

					if(!empty($expected_computation) && !empty($actual_computation)) {
						if($expected_computation != $actual_computation) {
							$error_color = "#f04d4d";
						}
					}

					echo '
							<tr style="background-color: #f9f7f7;">
								<td>&nbsp;</td>
							    <td style="background-color: '. $error_color .'">' . $label . '<br /> <div style="font-size: 12px;">' . $ee_key. '</div> </td>
							    <td style="background-color: '. $error_color .'">' . $days_horus . '</td>
							    <td style="background-color: '. $error_color .'">' . $actual_computation . '</strong></td>
							    <td style="background-color: '. $error_color .'">' . $expected_computation . ' </td>
							    <td>--</td>
							</tr>
						';    

					$this->assertEqual($actual_computation, $expected_computation); 
				}  	

				echo "</table>";

				echo "<b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 14px;'>Other Earnings: " . $e->getLastName(). " " . $e->getFirstName() . "</b><br />";
				echo '
							<table width="100%" class="">  
							  <tr>
							  	<td>&nbsp;</td>
							    <td><strong>Earnings</strong></td>
							    <td><strong>Actual Computation</strong></td>
							    <td><strong>Expected Computation</strong></td>
							    <td><strong>--</strong></td>
							  </tr>
					';		

				foreach($employee_other_earnings as $eoe_key => $eoed) {

					$label = $eoed->getLabel();
					$actual_ocomputation   = preg_replace('/\s+/', ' ', $eoed->getAmount());
					$expected_ocomputation = preg_replace('/\s+/', ' ', $expected_output[$eoed->getVariable()]); 

					$error_color = "";
					if(!empty($expected_ocomputation) && !empty($actual_ocomputation)) {
						if($expected_ocomputation != $actual_ocomputation) {
							$error_color = "#f04d4d";
						}	
					}	

					echo '
							<tr>
								<td>&nbsp;</td>
							    <td style="background-color: '. $error_color .'">' . $label . '</td>
							    <td style="background-color: '. $error_color .'">' . $actual_ocomputation . '</strong></td>
							    <td style="background-color: '. $error_color .'">' . $expected_ocomputation . ' </td>
							    <td>--</td>
							</tr>
						';    

					$this->assertEqual($actual_ocomputation, $expected_ocomputation);
				}        	

				echo "</table>";
				echo '<br />';
				echo "<b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 14px;'>Deductions: " . $e->getLastName(). " " . $e->getFirstName() . "</b><br />";
				echo '
							<table width="100%" class="">  
							  <tr>
							  	<td>&nbsp;</td>
							    <td><strong>Deductions</strong></td>
							    <td><strong>Actual Computation</strong></td>
							    <td><strong>Expected Computation</strong></td>
							    <td><strong>--</strong></td>
							  </tr>
					';

				foreach($employee_deductons as $ed_key => $edd) {
					$label = $edd->getLabel();
					$actual_dcomputation   = preg_replace('/\s+/', ' ', $edd->getAmount());
					$expected_dcomputation = preg_replace('/\s+/', ' ', $expected_output[$edd->getVariable()]); 

					$error_color = "";

					if(!empty($actual_dcomputation) && !empty($expected_dcomputation)) {
						if($actual_dcomputation != $expected_dcomputation) {
							$error_color = "#f04d4d";
						}						
					}

					echo '
							<tr>
								<td>&nbsp;</td>
							    <td style="background-color: '. $error_color .'">' . $label . '<br /> <div style="font-size: 12px;">' . $edd->getVariable() . '</td>
							    <td style="background-color: '. $error_color .'">' . $actual_dcomputation . '</strong></td>
							    <td style="background-color: '. $error_color .'">' . $expected_dcomputation . ' </td>
							    <td>--</td>
							</tr>
						';  

					$this->assertEqual($actual_dcomputation, $expected_dcomputation); 
				}

				echo "</table>";
				echo "<b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 14px;'>Other Deductions: " . $e->getLastName(). " " . $e->getFirstName() . "</b><br />";
				echo '
							<table width="100%" class="">  
							  <tr>
							  	<td>&nbsp;</td>
							    <td><strong>Deductions</strong></td>
							    <td><strong>Actual Computation</strong></td>
							    <td><strong>Expected Computation</strong></td>
							    <td><strong>--</strong></td>
							  </tr>
					';

					
				foreach($employee_other_deductons as $eod_key => $eodd) {
					$label = $eodd->getLabel();
					$actual_odcomputation   = preg_replace('/\s+/', ' ', $eodd->getAmount());
					$expected_odcomputation = preg_replace('/\s+/', ' ', $expected_output[$eodd->getVariable()]); 

					$error_colord = "";	
					//if(!empty($actual_odcomputation) && !empty($expected_odcomputation)) {
						if($actual_odcomputation != $expected_odcomputation) {
							$error_colord = "#f04d4d";
						}						
					//}

					echo '
							<tr>
								<td>&nbsp;</td>
							    <td style="background-color: '. $error_colord .'">' . $label . '<br /> <div style="font-size: 12px;">' . $eodd->getVariable() . '</td>
							    <td style="background-color: '. $error_colord .'">' . $actual_odcomputation . '</strong></td>
							    <td style="background-color: '. $error_colord .'">' . $expected_odcomputation . ' </td>
							    <td>--</td>
							</tr>
						';  

					$this->assertEqual($actual_odcomputation, $expected_odcomputation); 
				}

				echo "</table>";

				/*echo "<b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 14px;'>Summary: " . $e->getLastName(). " " . $e->getFirstName() . "</b><br />";
				echo '
							<table width="100%" class="">  
							  <tr>
							  	<td>&nbsp;</td>
							    <td><strong></strong></td>
							    <td><strong>Actual Total</strong></td>
							    <td><strong>Expected Total</strong></td>
							    <td><strong>--</strong></td>
							  </tr>
					';

				echo '
					  <tr>
					  	<td>&nbsp;</td>
					    <td>Total Earnings</td>
					    <td><strong>-</strong></td>
					    <td><strong>-</strong></td>
					    <td><strong>--</strong></td>
					  </tr>				
					';

				echo '
					  <tr>
					  	<td>&nbsp;</td>
					    <td>Total Deductions</td>
					    <td><strong>-</strong></td>
					    <td><strong>-</strong></td>
					    <td><strong>--</strong></td>
					  </tr>				
					';	

				echo '
					  <tr>
					  	<td>&nbsp;</td>
					    <td><Total Netpay</td>
					    <td><strong>-</strong></td>
					    <td><strong>-</strong></td>
					    <td><strong>--</strong></td>
					  </tr>				
					';						

				echo "</table>";*/	


	        } else {
	        	echo 'Payslip Not Found';
	        }
    	}

    }	

	function testCutoffPayslip() {	

		$cutoff_details = $this->cutoffDetails();

		$employee_code = $cutoff_details['employee_code'];
        $from  		   = $cutoff_details['cutoff_start_date'];
        $to    		   = $cutoff_details['cutoff_end_date'];

		$e = G_Employee_Finder::findByEmployeeCode($employee_code);

		if($e) {
			$employee_id = $e->getId();
			$s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($e, $cutoff_end_date);
			$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $from, $to);

			if($p) {

				$ph 			= new G_Payslip_Helper($p);
				$new_earnings   = $p->getBasicEarnings();
				$new_deductions = $p->getTardinessDeductions();
				$payslip_info   = $p->getEmployeeBasicPayslipInfo();
				$other_earnings = $p->getOtherEarnings();
				$other_deductions = $p->getOtherDeductions();
		 		$total_earnings   = $ph->computeTotalEarnings();
				$total_deductions = $ph->computeTotalDeductions();		
				$net_pay 		  = $p->getNetPay();		

				/*
				echo '<pre>';
				print_r($new_earnings);
				print_r($new_deductions);
				print_r($payslip_info);
				echo '</pre>';
				*/

				echo "<br /><b style='color: #1873b7; text-shadow: 0 1px #ffffff; font-size: 24px;'>Payslip: " . $e->getLastName(). " " . $e->getFirstName() . "</b><br />";		
				echo "Salary Type: " . $payslip_info['salary_type'] . "/ Monthly Rate :" . $payslip_info['monthly_rate'] . "/ Daily Rate :" . $payslip_info['daily_rate'] . "/ Hourly Rate :" . $payslip_info['hourly_rate'] . "<hr />";

				echo "<b style='text-shadow: 0 1px #ffffff; font-size: 20px;'>Earnings: " . "</b><br />";		
				echo '
							<table width="100%" class="">  
							  <tr style="background-color: #6fd7ff;">
							    <td><strong>Name</strong></td>
							    <td><strong>Days/Hours</strong></td>
							    <td><strong>Amount</strong></td>
							    <td><strong>Computation</strong></td>
							  </tr>
					';	

				foreach($new_earnings as $earning) {

				  	$total_hrs  = number_format($earning['total_hours'],2);
				  	$total_days = number_format($earning['total_days'],0);
				  	if( $total_days > 0 ){
				      $to_string = "{$total_days} days";
				    }elseif( $total_hrs > 0 ){
				      $to_string = "{$total_hrs} hrs";
				    }else{
				      $to_string = "";
				    }

				    if( $payslip_info['salary_type'] == 'Monthly' && $earning['label'] == 'Basic Pay' ){
				      $to_string = "";
				    }

					echo '
						  	<tr style="background-color: #e3e3e3;">
							    <td>' . $earning['label'] . '</td>
							    <td>' . $to_string . '</td>
							    <td>' . Tools::currencyFormat($earning['amount']) . '</td>
							    <td>-</td>
						  	</tr>
						';					

				}

				foreach ($other_earnings as $oear){
				if($oear->getAmount() != 0) {
					echo '
						  	<tr style="background-color: #e3e3e3;">
							    <td>' . $oear->getLabel() . '</td>
							    <td>' . '' . '</td>
							    <td>' . Tools::currencyFormat($oear->getAmount()) . '</td>
							    <td>-</td>
						  	</tr>
						';					
					}					
				}


				echo '
					  	<tr style="background-color: #e3e3e3;">
						    <td>' . '' . '</td>
						    <td style="float:right;">' . 'Total Earnings: ' . '</td>
						    <td>' . Tools::currencyFormat($total_earnings) . '</td>
						    <td>-</td>
					  	</tr>
					';					

				echo '</table>';				

				echo "<b style='text-shadow: 0 1px #ffffff; font-size: 20px;'>Deductions: " . "</b><br />";	
				echo '
							<table width="100%" class="">  
							  <tr style="background-color: #6fd7ff;">
							    <td><strong>Name</strong></td>
							    <td><strong>Hours</strong></td>
							    <td><strong>Amount</strong></td>
							    <td><strong>Computation</strong></td>
							  </tr>
					';	

				foreach($new_deductions as $deduction) {
				  	$total_hrs  = number_format($deduction['total_hours'],2);
				    $total_days = $deduction['total_days'];

				  	if( $total_hrs > 0 ){
				  		$to_string = "<b>({$total_hrs} hrs)</b>";
				  	}elseif( $total_days > 0 ){
				  		$to_string = "<b>({$total_days} days)</b>";
				  	}else{
				  		$to_string = "";
				  	}			

					echo '
						  	<tr style="background-color: #e3e3e3;">
							    <td>' . $deduction['label'] . '</td>
							    <td>' . $to_string . '</td>
							    <td>' . Tools::currencyFormat($deduction['amount']) . '</td>
							    <td>-</td>
						  	</tr>
						';	

				}

				foreach ($other_deductions as $oear){
				if($oear->getAmount() != 0) {
					echo '
						  	<tr style="background-color: #e3e3e3;">
							    <td>' . $oear->getLabel() . '</td>
							    <td>' . '' . '</td>
							    <td>' . Tools::currencyFormat($oear->getAmount()) . '</td>
							    <td>-</td>
						  	</tr>
						';					
					}					
				}	
				
				echo '
					  	<tr style="background-color: #e3e3e3;">
						    <td>' . '' . '</td>
						    <td style="float:right;">' . 'Total Deductions: ' . '</td>
						    <td>' . Tools::currencyFormat($total_deductions) . '</td>
						    <td>-</td>
					  	</tr>
					';								
					
				echo '</table>';											

				echo "<b style='text-shadow: 0 1px #ffffff; font-size: 20px;'>Summary: " . "</b><br />";
				echo '
							<table width="100%" class="">  
							  <tr style="background-color: #6fd7ff;">
							    <td><strong>Earnings</strong></td>
							    <td><strong>Deduction</strong></td>
							    <td><strong>Net Pay</strong></td>
							  </tr>
					';		

				echo '	<tr style="background-color: #e3e3e3;">
						    <td><strong>' . Tools::currencyFormat($total_earnings) . '</strong></td>
						    <td><strong>' . Tools::currencyFormat($total_deductions) . '</strong></td>
						    <td><strong>' . Tools::currencyFormat($net_pay) . '</strong></td>
						 </tr>
					';

				echo '</table>';									

			}

		}

		
	}
		
	
}
