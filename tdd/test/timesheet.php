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
	    	/*$attendance_date = "2018-04-28";
	        $employee_att[$attendance_date]['date_in']  = "2018-04-28";
	        $employee_att[$attendance_date]['date_out'] = "2018-04-29";
	        $employee_att[$attendance_date]['time_in']  = "19:30:00";
	        $employee_att[$attendance_date]['time_out'] = "09:30:00";

	        $employee_att[$attendance_date]['expected_ot']        = 4.50;
	        $employee_att[$attendance_date]['expected_late']      = 0.00;
	        $employee_att[$attendance_date]['expected_undertime'] = 0.00;
	        $employee_att[$attendance_date]['expected_ns_hours']  = 6.00;
	        $employee_att[$attendance_date]['expected_reg_ns_ot_hours']   = 0.00;
	        $employee_att[$attendance_date]['expected_legal_ns_ot_hours'] = 0.00;          
	        $employee_att[$attendance_date]['expected_spec_ns_ot_hours']  = 0.00;          

	        $employee_att[$attendance_date]['expected_rd_ot_hours']  	= 4.50;
	        $employee_att[$attendance_date]['expected_rd_ot_ns_hours']  = 1.00;

	        $employee_att[$attendance_date]['expected_restday_legal_overtime_hours']  	  = 0.00;
	        $employee_att[$attendance_date]['expected_restday_legal_overtime_ns_hours']   = 0.00;

	        $employee_att[$attendance_date]['expected_restday_special_overtime_hours']    = 0.00;
	        $employee_att[$attendance_date]['expected_restday_special_overtime_ns_hours'] = 0.00;*/
        /* end -----*/

		/* start -----*/
			$attendance_date = "2018-05-01";
			$employee_att[$attendance_date]['add_schedule'] 	 = true;
			$employee_att[$attendance_date]['schedule_date'] 	 = "2018-05-01";
			$employee_att[$attendance_date]['schedule_end_date'] = "2018-05-01";
			$employee_att[$attendance_date]['schedule_time_in']  = "8:30 am";
			$employee_att[$attendance_date]['schedule_time_out'] = "6:00 pm";    

	        $employee_att[$attendance_date]['date_in']  = "2018-05-01";
	        $employee_att[$attendance_date]['date_out'] = "2018-05-01";
	        $employee_att[$attendance_date]['time_in']  = "09:00:00";
	        $employee_att[$attendance_date]['time_out'] = "18:30:00";

	        $employee_att[$attendance_date]['expected_ot']        = 0.00;
	        $employee_att[$attendance_date]['expected_late']      = 0.50;
	        $employee_att[$attendance_date]['expected_undertime'] = 0.00;
	        $employee_att[$attendance_date]['expected_ns_hours']  = 0.00;
	        $employee_att[$attendance_date]['expected_reg_ns_ot_hours']   = 0.00;
	        $employee_att[$attendance_date]['expected_legal_ns_ot_hours'] = 0.00;          
	        $employee_att[$attendance_date]['expected_spec_ns_ot_hours']  = 0.00;          

	        $employee_att[$attendance_date]['expected_rd_ot_hours']  	= 0.00;
	        $employee_att[$attendance_date]['expected_rd_ot_ns_hours']  = 0.00;

	        $employee_att[$attendance_date]['expected_restday_legal_overtime_hours']  	  = 0.00;
	        $employee_att[$attendance_date]['expected_restday_legal_overtime_ns_hours']   = 0.00;

	        $employee_att[$attendance_date]['expected_restday_special_overtime_hours']    = 0.00;
	        $employee_att[$attendance_date]['expected_restday_special_overtime_ns_hours'] = 0.00;			
		/* end -----*/

		/* start -----*/
			$attendance_date = "2018-05-02";
			$employee_att[$attendance_date]['add_schedule'] 	 = true;
			$employee_att[$attendance_date]['schedule_date'] 	 = "2018-05-02";
			$employee_att[$attendance_date]['schedule_end_date'] = "2018-05-02";
			$employee_att[$attendance_date]['schedule_time_in']  = "8:30 am";
			$employee_att[$attendance_date]['schedule_time_out'] = "6:00 pm";    

	        $employee_att[$attendance_date]['date_in']  = "2018-05-02";
	        $employee_att[$attendance_date]['date_out'] = "2018-05-02";
	        $employee_att[$attendance_date]['time_in']  = "09:00:00";
	        $employee_att[$attendance_date]['time_out'] = "18:30:00";

	        $employee_att[$attendance_date]['expected_ot']        = 0.00;
	        $employee_att[$attendance_date]['expected_late']      = 0.50;
	        $employee_att[$attendance_date]['expected_undertime'] = 0.00;
	        $employee_att[$attendance_date]['expected_ns_hours']  = 0.00;
	        $employee_att[$attendance_date]['expected_reg_ns_ot_hours']   = 0.00;
	        $employee_att[$attendance_date]['expected_legal_ns_ot_hours'] = 0.00;          
	        $employee_att[$attendance_date]['expected_spec_ns_ot_hours']  = 0.00;          

	        $employee_att[$attendance_date]['expected_rd_ot_hours']  	= 0.00;
	        $employee_att[$attendance_date]['expected_rd_ot_ns_hours']  = 0.00;

	        $employee_att[$attendance_date]['expected_restday_legal_overtime_hours']  	  = 0.00;
	        $employee_att[$attendance_date]['expected_restday_legal_overtime_ns_hours']   = 0.00;

	        $employee_att[$attendance_date]['expected_restday_special_overtime_hours']    = 0.00;
	        $employee_att[$attendance_date]['expected_restday_special_overtime_ns_hours'] = 0.00;			
		/* end -----*/		

        return $employee_att;
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

					    <td><strong>Total RD OT Hrs</strong></td>
					    <td><strong>Total RD NS OT Hrs</strong></td>

						<td><strong>Total RD LEG OT Hrs</strong></td>
					    <td><strong>Total RD LEG NS OT Hrs</strong></td>
					    <td><strong>Total RD SPCl OT Hrs</strong></td>
					    <td><strong>Total RD SPCl NS OT Hrs</strong></td>					    

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

				if($attendande_data['add_schedule'] == true) {
					$employee_id = (int) $e->getId();
					$start_date  = $attendande_data['schedule_date'];
					$end_date 	 = $attendande_data['schedule_end_date'];
					$time_in 	 = $attendande_data['schedule_time_in'];
					$time_out 	 = $attendande_data['schedule_time_out'];
			        $is_restday  = $attendande_data['is_restday'];					

					if (Tools::isValidDate($start_date) && Tools::isValidTime($time_in) && Tools::isValidTime($time_out)) {
						$start_date = date('Y-m-d', strtotime($start_date));
						$time_in = date('H:i:s', strtotime($time_in));
						$time_out = date('H:i:s', strtotime($time_out));
						
						$e = G_Employee_Finder::findById($employee_id);
						if ($e) {
							if (Tools::isValidDate($end_date)) {
								$end_date = date('Y-m-d', strtotime($end_date));
							} else {
								$end_date = $start_date;	
							}
							
							if (strtotime($end_date) >= strtotime($start_date)) {
								$s = G_Schedule_Specific_Finder::findByEmployeeAndStartAndEndDate($e, $start_date, $end_date);
								if (!$s) {
									$s = new G_Schedule_Specific;
								}				
								$s->setDateStart($start_date);
								$s->setDateEnd($end_date);
								$s->setTimeIn($time_in);
								$s->setTimeOut($time_out);
								$s->setEmployeeId($e->getId());
								$is_saved = $s->save();

								$dates = Tools::getBetweenDates($start_date, $end_date);

			                    foreach ($dates as $date) {
			                        $r = G_Restday_Finder::findByEmployeeAndDate($e, $date);

			                        if( $r ){

			                        	if( $is_restday <> 'yes' ){
			                        		$r->removeFromRestDay();
			                        	}
			                        }else{
			                        	if( $is_restday == 'yes' ){
			                        		$r = new G_Restday;
				                        	$r->setDate($date);
				                            $r->setTimeIn($time_in);
				                            $r->setTimeOut($time_out);
				                            $r->setEmployeeId($e->getId());
				                            $r->save();
			                        	}                 

			                        }
			                    }

								if ($is_saved) {

									G_Attendance_Helper::updateAttendanceByEmployeeAndPeriod($e, $start_date, $end_date);
											
								} else {
									echo 'There was a problem saving the schedule. Please contact the administrator';	
									exit;
								}					
							} else {
								echo 'Start Date must not greater than End Date';
								exit;
							}				
						} else {
							echo 'Employee was not found';
							exit;
						}
					} else {
						echo 'Schedule has not been saved. Invalid time or date format.';
						exit;
					}			 
					       
				}

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
				$return = $at->addAttendanceLog();
				//$return = true;

				$from = $date_in;
				$to   = $date_out;				
				//G_Attendance_Helper::updateAttendanceByEmployeeIdPeriod($employee_id, $from, $to);
				if($return) {

					$error_ot_color 		= "";
					$error_ns_color 		= "";

					$error_restday_ot_color 	= "";
					$error_restday_ns_ot_color  = "";

					$error_reg_ns_ot_color 	= "";
					$error_lg_ns_ot_color 	= "";
					$error_sc_ns_ot_color 	= "";
					$error_late_color 		= "";
					$error_undertime_color 	= "";			

					$error_restday_legal_ot_color 	 = "";
					$error_restday_legal_ns_ot_color = "";
					$error_restday_spcl_ot_color 	 = "";
					$error_restday_spcl_ns_ot_color  = "";

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

					if(preg_replace('/\s+/', ' ', $t->getRestDayOvertimeHours()) != preg_replace('/\s+/', ' ', $attendande_data['expected_rd_ot_hours'])) {
						$error_restday_ot_color = "#f04d4d";
					}

					if(preg_replace('/\s+/', ' ', $t->getRestDayOvertimeNightShiftHours()) != preg_replace('/\s+/', ' ', $attendande_data['expected_rd_ot_ns_hours'])) {
						$error_restday_ns_ot_color = "#f04d4d";
					}						

					if(preg_replace('/\s+/', ' ', $t->getRestDayLegalOvertimeHours()) != preg_replace('/\s+/', ' ', $attendande_data['expected_restday_legal_overtime_hours'])) {
						$error_restday_legal_ot_color = "#f04d4d";
					}

					if(preg_replace('/\s+/', ' ', $t->getRestDayLegalOvertimeNightShiftHours()) != preg_replace('/\s+/', ' ', $attendande_data['expected_restday_legal_overtime_ns_hours'])) {
						$error_restday_legal_ns_ot_color = "#f04d4d";
					}

					if(preg_replace('/\s+/', ' ', $t->getRestDaySpecialOvertimeHours()) != preg_replace('/\s+/', ' ', $attendande_data['expected_restday_special_overtime_hours'])) {
						$error_restday_spcl_ot_color = "#f04d4d";
					}

					if(preg_replace('/\s+/', ' ', $t->getRestDaySpecialOvertimeNightShiftHours()) != preg_replace('/\s+/', ' ', $attendande_data['expected_restday_special_overtime_ns_hours'])) {
						$error_restday_spcl_ns_ot_color = "#f04d4d";
					}

					/*echo '<pre>';
					print_r($t);
					echo '</pre>';*/

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

					    <td style="background-color: ' . $error_restday_ot_color . ';">' . Tools::convertHourToTime($t->getRestDayOvertimeHours()) . '/' . $t->getRestDayOvertimeHours() . '</td>
					    <td style="background-color: ' . $error_restday_ns_ot_color . ';">' . Tools::convertHourToTime($t->getRestDayOvertimeNightShiftHours()) . '/' . $t->getRestDayOvertimeNightShiftHours() . '</td>

					    <td style="background-color: ' . $error_restday_legal_ot_color . ';">' . Tools::convertHourToTime($t->getRestDayLegalOvertimeHours()) . '/' . $t->getRestDayLegalOvertimeHours() . '</td>
					    <td style="background-color: ' . $error_restday_legal_ns_ot_color . ';">' . Tools::convertHourToTime($t->getRestDayLegalOvertimeNightShiftHours()) . '/' . $t->getRestDayLegalOvertimeNightShiftHours() . '</td>
					    <td style="background-color: ' . $error_restday_spcl_ot_color . ';">' . Tools::convertHourToTime($t->getRestDaySpecialOvertimeHours()) . '/' . $t->getRestDaySpecialOvertimeHours() . '</td>
					    <td style="background-color: ' . $error_restday_spcl_ns_ot_color . ';">' . Tools::convertHourToTime($t->getRestDaySpecialOvertimeNightShiftHours()) . '/' . $t->getRestDaySpecialOvertimeNightShiftHours() . '</td>					    

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

					$error_restday_ot_color 	= !empty($error_restday_ot_color) ? $error_restday_ot_color : '#41fe2e';
					$error_restday_ns_ot_color 	= !empty($error_restday_ns_ot_color) ? $error_restday_ns_ot_color : '#41fe2e';

					$error_restday_legal_ot_color 	 = !empty($error_restday_legal_ot_color) ? $error_restday_legal_ot_color : '#41fe2e';
					$error_restday_legal_ns_ot_color = !empty($error_restday_legal_ns_ot_color) ? $error_restday_legal_ns_ot_color : '#41fe2e';
					$error_restday_spcl_ot_color 	 = !empty($error_restday_spcl_ot_color) ? $error_restday_spcl_ot_color : '#41fe2e';
					$error_restday_spcl_ns_ot_color  = !empty($error_restday_spcl_ns_ot_color) ? $error_restday_spcl_ns_ot_color : '#41fe2e';

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

						<td style="background-color: ' . $error_restday_ot_color . ';">' . Tools::convertHourToTime($attendande_data['expected_rd_ot_hours']). '/' . $attendande_data['expected_rd_ot_hours'] .  '</td>
						<td style="background-color: ' . $error_restday_ns_ot_color . ';">' . Tools::convertHourToTime($attendande_data['expected_rd_ot_ns_hours']). '/' . $attendande_data['expected_rd_ot_ns_hours'] .  '</td>					    

						<td style="background-color: ' . $error_restday_legal_ot_color . ';">' . Tools::convertHourToTime($attendande_data['expected_restday_legal_overtime_hours']). '/' . $attendande_data['expected_restday_legal_overtime_hours'] .  '</td>
						<td style="background-color: ' . $error_restday_legal_ns_ot_color . ';">' . Tools::convertHourToTime($attendande_data['expected_restday_legal_overtime_ns_hours']). '/' . $attendande_data['expected_restday_legal_overtime_ns_hours'] .  '</td>
						<td style="background-color: ' . $error_restday_spcl_ot_color . ';">' . Tools::convertHourToTime($attendande_data['expected_restday_special_overtime_hours']). '/' . $attendande_data['expected_restday_special_overtime_hours'] .  '</td>
						<td style="background-color: ' . $error_restday_spcl_ns_ot_color . ';">' . Tools::convertHourToTime($attendande_data['expected_restday_special_overtime_ns_hours']). '/' . $attendande_data['expected_restday_special_overtime_ns_hours'] .  '</td>

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

				$this->assertEqual($t->getRestDayOvertimeHours(), $attendande_data['expected_rd_ot_hours']);
				$this->assertEqual($t->getRestDayOvertimeNightShiftHours(), $attendande_data['expected_rd_ot_ns_hours']);

				$this->assertEqual($t->getRestDayLegalOvertimeHours(), $attendande_data['expected_restday_legal_overtime_hours']);
				$this->assertEqual($t->getRestDayLegalOvertimeNightShiftHours(), $attendande_data['expected_restday_legal_overtime_ns_hours']);
				$this->assertEqual($t->getRestDaySpecialOvertimeHours(), $attendande_data['expected_restday_special_overtime_hours']);
				$this->assertEqual($t->getRestDaySpecialOvertimeNightShiftHours(), $attendande_data['expected_restday_special_overtime_ns_hours']);
			}

		}

	}	
		
	
}
