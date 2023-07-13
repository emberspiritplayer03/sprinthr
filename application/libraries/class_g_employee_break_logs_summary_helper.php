<?php
class G_Employee_Break_Logs_Summary_Helper {

    public static function sqlIsIdExists($id) {
        $is_exists = false;

        $sql = "
            SELECT COUNT(id) as total
            FROM " . G_EMPLOYEE_BREAK_LOGS_SUMMARY ."
            WHERE id = ". Model::safeSql($id) ."
        ";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);

        if( $row['total'] > 0 ){
            $is_exists = true;
        }
        
        return $is_exists;
    }

	public static function computeTotalBreakHrs( $attendance_breaks ) {
        $total_break_hrs = 0;
        
        if ($attendance_breaks) {
            if (
                $attendance_breaks->getLogBreak1Out() &&
                $attendance_breaks->getLogBreak1In() &&
                Tools::validateDateTime($attendance_breaks->getLogBreak1Out()) &&
                Tools::validateDateTime($attendance_breaks->getLogBreak1In()) &&
                $attendance_breaks->getLogBreak1Out() < $attendance_breaks->getLogBreak1In()
            ) {
                $total_break_hrs += Tools::computeHoursDifferenceByDateTime($attendance_breaks->getLogBreak1Out(), $attendance_breaks->getLogBreak1In());
            }
            
            if (
                $attendance_breaks->getLogBreak2Out() &&
                $attendance_breaks->getLogBreak2In() &&
                Tools::validateDateTime($attendance_breaks->getLogBreak2Out()) &&
                Tools::validateDateTime($attendance_breaks->getLogBreak2In()) &&
                $attendance_breaks->getLogBreak2Out() < $attendance_breaks->getLogBreak2In()
            ) {
                $total_break_hrs += Tools::computeHoursDifferenceByDateTime($attendance_breaks->getLogBreak2Out(), $attendance_breaks->getLogBreak2In());
            }
            
            if (
                $attendance_breaks->getLogBreak3Out() &&
                $attendance_breaks->getLogBreak3In() &&
                Tools::validateDateTime($attendance_breaks->getLogBreak3Out()) &&
                Tools::validateDateTime($attendance_breaks->getLogBreak3In()) &&
                $attendance_breaks->getLogBreak3Out() < $attendance_breaks->getLogBreak3In()
            ) {
                $total_break_hrs += Tools::computeHoursDifferenceByDateTime($attendance_breaks->getLogBreak3Out(), $attendance_breaks->getLogBreak3In());
            }
            
            if (
                $attendance_breaks->getLogOtBreak1Out() &&
                $attendance_breaks->getLogOtBreak1In() &&
                Tools::validateDateTime($attendance_breaks->getLogOtBreak1Out()) &&
                Tools::validateDateTime($attendance_breaks->getLogOtBreak1In()) &&
                $attendance_breaks->getLogOtBreak1Out() < $attendance_breaks->getLogOtBreak1In()
            ) {
                $total_break_hrs += Tools::computeHoursDifferenceByDateTime($attendance_breaks->getLogOtBreak1Out(), $attendance_breaks->getLogOtBreak1In());
            }
            
            if (
                $attendance_breaks->getLogOtBreak2Out() &&
                $attendance_breaks->getLogOtBreak2In() &&
                Tools::validateDateTime($attendance_breaks->getLogOtBreak2Out()) &&
                Tools::validateDateTime($attendance_breaks->getLogOtBreak2In()) &&
                $attendance_breaks->getLogOtBreak2Out() < $attendance_breaks->getLogOtBreak2In()
            ) {
                $total_break_hrs += Tools::computeHoursDifferenceByDateTime($attendance_breaks->getLogOtBreak2Out(), $attendance_breaks->getLogOtBreak2In());
            }
        }

        return $total_break_hrs;
    }

	public static function earlyLateBreaks( $attendance_breaks = null, $schedule_breaks = array() ) {
        $has_early_break_out = false;
        $has_late_break_in = false;
        $total_early_break_out_hrs = 0;
        $total_late_break_in_hrs = 0;
        $unused_deductible_break_hrs = 0;
        $iteration = 1;
        
        if ($attendance_breaks) {
            foreach ($schedule_breaks as $key => $schedule_break) {
                $has_true_condition = false;
                $schedule_break_out = date('Y-m-d H:i:s', strtotime($attendance_breaks->getAttendanceDate() . ' ' . $schedule_break['break_in']));
                $schedule_break_in = date('Y-m-d H:i:s', strtotime($attendance_breaks->getAttendanceDate() . ' ' . $schedule_break['break_out']));
                
                $to_required_logs = $schedule_break['to_required_logs'];

                if ($schedule_break_out > $schedule_break_in) {
                    $schedule_break_in = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($schedule_break_in)));;
                }
            
                if($to_required_logs)
                {
                    if (
                        $attendance_breaks->{'getLogBreak'. $iteration .'Out'}() &&
                        Tools::validateDateTime($attendance_breaks->{'getLogBreak'. $iteration .'Out'}()) &&
                        $schedule_break_in >= $attendance_breaks->{'getLogBreak'. $iteration .'Out'}() &&
                        $attendance_breaks->{'getLogBreak'. $iteration .'Out'}() < $schedule_break_out
                    ) {
                        $has_early_break_out = true;
                        $total_early_break_out_hrs += Tools::computeHoursDifferenceByDateTime($attendance_breaks->{'getLogBreak'. $iteration .'Out'}(), $schedule_break_out);
    
                        $has_true_condition = true;
                    }
                
                    if (
                        $attendance_breaks->{'getLogBreak'. $iteration .'In'}() &&
                        Tools::validateDateTime($attendance_breaks->{'getLogBreak'. $iteration .'In'}()) &&
                        $attendance_breaks->{'getLogBreak'. $iteration .'In'}() >= $schedule_break_out &&
                        $attendance_breaks->{'getLogBreak'. $iteration .'In'}() > $schedule_break_in
                    ) {
                        $has_late_break_in = true;
                        $total_late_break_in_hrs += Tools::computeHoursDifferenceByDateTime($attendance_breaks->{'getLogBreak'. $iteration .'In'}(), $schedule_break_in);
    
                        $has_true_condition = true;
                    }
                }

                $iteration++;

                if ($iteration > 3) {
                    break;
                }
            
                // //OT break1
                // if (
                //     $attendance_breaks->getLogOtBreak1Out() &&
                //     Tools::validateDateTime($attendance_breaks->getLogOtBreak1Out()) &&
                //     $schedule_break_in >= $attendance_breaks->getLogOtBreak1Out() &&
                //     $attendance_breaks->getLogOtBreak1Out() < $schedule_break_out
                // ) {
                //     $has_early_break_out = true;
                //     $total_early_break_out_hrs += Tools::computeHoursDifferenceByDateTime($attendance_breaks->getLogOtBreak1Out(), $schedule_break_out);

                //     $has_true_condition = true;
                // }

                // if (
                //     $attendance_breaks->getLogOtBreak1In() &&
                //     Tools::validateDateTime($attendance_breaks->getLogOtBreak1In()) &&
                //     $attendance_breaks->getLogOtBreak1In() >= $schedule_break_out &&
                //     $attendance_breaks->getLogOtBreak1In() > $schedule_break_in
                // ) {
                //     $has_late_break_in = true;
                //     $total_late_break_in_hrs += Tools::computeHoursDifferenceByDateTime($attendance_breaks->getLogOtBreak1In(), $schedule_break_in);

                //     $has_true_condition = true;
                // }
            
                // //OT break2
                // if (
                //     $attendance_breaks->getLogOtBreak2Out() &&
                //     Tools::validateDateTime($attendance_breaks->getLogOtBreak2Out()) &&
                //     $schedule_break_in >= $attendance_breaks->getLogOtBreak2Out() &&
                //     $attendance_breaks->getLogOtBreak2Out() < $schedule_break_out
                // ) {
                //     $has_early_break_out = true;
                //     $total_early_break_out_hrs += Tools::computeHoursDifferenceByDateTime($attendance_breaks->getLogOtBreak2Out(), $schedule_break_out);

                //     $has_true_condition = true;
                // }

                // if (
                //     $attendance_breaks->getLogOtBreak2In() &&
                //     Tools::validateDateTime($attendance_breaks->getLogOtBreak2In()) &&
                //     $attendance_breaks->getLogOtBreak2In() >= $schedule_break_out &&
                //     $attendance_breaks->getLogOtBreak2In() > $schedule_break_in
                // ) {
                //     $has_late_break_in = true;
                //     $total_late_break_in_hrs += Tools::computeHoursDifferenceByDateTime($attendance_breaks->getLogOtBreak2In(), $schedule_break_in);

                //     $has_true_condition = true;
                // }

                // if (!$has_true_condition) {
                //     $unused_deductible_break_hrs += (float)$schedule_break['total_hrs_deductible'];
                // }

                // if ($has_early_break_out && $has_late_break_in) {
                //     break;
                // }
            }
        }
            
        return array(
            'has_early_break_out' => $has_early_break_out,
            'has_late_break_in' => $has_late_break_in,
            'total_early_break_out_hrs' => $total_early_break_out_hrs,
            'total_late_break_in_hrs' => $total_late_break_in_hrs,
            'unused_deductible_break_hrs' => $unused_deductible_break_hrs
        );
    }
    
	public static function getAvailableBreakTypes( $attendance_breaks ) {
        $available_types = array();

        if ($attendance_breaks->getLogBreak1Out() && in_array(G_Employee_Break_Logs::TYPE_B1_OUT, $available_types) == false) {
            $available_types[] = G_Employee_Break_Logs::TYPE_B1_OUT;   
        }

        if ($attendance_breaks->getLogBreak1In() && in_array(G_Employee_Break_Logs::TYPE_B1_IN, $available_types) == false) {
            $available_types[] = G_Employee_Break_Logs::TYPE_B1_IN;   
        }

        if ($attendance_breaks->getLogBreak2Out() && in_array(G_Employee_Break_Logs::TYPE_B2_OUT, $available_types) == false) {
            $available_types[] = G_Employee_Break_Logs::TYPE_B2_OUT;   
        }

        if ($attendance_breaks->getLogBreak2In() && in_array(G_Employee_Break_Logs::TYPE_B2_IN, $available_types) == false) {
            $available_types[] = G_Employee_Break_Logs::TYPE_B2_IN;   
        }

        if ($attendance_breaks->getLogBreak3Out() && in_array(G_Employee_Break_Logs::TYPE_B3_OUT, $available_types) == false) {
            $available_types[] = G_Employee_Break_Logs::TYPE_B3_OUT;   
        }

        if ($attendance_breaks->getLogBreak3In() && in_array(G_Employee_Break_Logs::TYPE_B3_IN, $available_types) == false) {
            $available_types[] = G_Employee_Break_Logs::TYPE_B3_IN;   
        }

        if ($attendance_breaks->getLogOtBreak1Out() && in_array(G_Employee_Break_Logs::TYPE_OT_B1_OUT, $available_types) == false) {
            $available_types[] = G_Employee_Break_Logs::TYPE_OT_B1_OUT;   
        }

        if ($attendance_breaks->getLogOtBreak1In() && in_array(G_Employee_Break_Logs::TYPE_OT_B1_IN, $available_types) == false) {
            $available_types[] = G_Employee_Break_Logs::TYPE_OT_B1_IN;   
        }

        if ($attendance_breaks->getLogOtBreak2Out() && in_array(G_Employee_Break_Logs::TYPE_OT_B2_OUT, $available_types) == false) {
            $available_types[] = G_Employee_Break_Logs::TYPE_OT_B2_OUT;   
        }

        if ($attendance_breaks->getLogOtBreak2In() && in_array(G_Employee_Break_Logs::TYPE_OT_B2_IN, $available_types) == false) {
            $available_types[] = G_Employee_Break_Logs::TYPE_OT_B2_IN;   
        }

        return $available_types;
    }
    
	public static function getRequiredBreakTypes( $attendance_breaks ) {
        $required_types = array();

        if ($attendance_breaks->getRequiredLogBreak1()) {
            if (in_array(G_Employee_Break_Logs::TYPE_B1_OUT, $required_types) == false) {
                $required_types[] = G_Employee_Break_Logs::TYPE_B1_OUT;   
            }

            if (in_array(G_Employee_Break_Logs::TYPE_B1_IN, $required_types) == false) {
                $required_types[] = G_Employee_Break_Logs::TYPE_B1_IN;   
            }
        }

        if ($attendance_breaks->getRequiredLogBreak2()) {
            if (in_array(G_Employee_Break_Logs::TYPE_B2_OUT, $required_types) == false) {
                $required_types[] = G_Employee_Break_Logs::TYPE_B2_OUT;   
            }

            if (in_array(G_Employee_Break_Logs::TYPE_B2_IN, $required_types) == false) {
                $required_types[] = G_Employee_Break_Logs::TYPE_B2_IN;   
            }
        }

        if ($attendance_breaks->getRequiredLogBreak3()) {
            if (in_array(G_Employee_Break_Logs::TYPE_B3_OUT, $required_types) == false) {
                $required_types[] = G_Employee_Break_Logs::TYPE_B3_OUT;   
            }

            if (in_array(G_Employee_Break_Logs::TYPE_B3_IN, $required_types) == false) {
                $required_types[] = G_Employee_Break_Logs::TYPE_B3_IN;   
            }
        }

        return $required_types;
    }

    public static function getTotalEarlyBreakOutHours($attendance) {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent()) {
                $attendance_breaks = G_Employee_Break_logs_Summary_Finder::findByEmployeeAttendanceId($a->getId());

                if ($attendance_breaks) {
                    $temp_total = $attendance_breaks->getTotalEarlyBreakOutHrs();
                    $total += $temp_total;
                }
            }
        }
        return $total;
    }

    public static function getTotalLateBreakInHours($attendance) {
        $total = 0;
        foreach ($attendance as $a) {
            if ($a->isPresent()) {
                $attendance_breaks = G_Employee_Break_logs_Summary_Finder::findByEmployeeAttendanceId($a->getId());

                if ($attendance_breaks) {
                    $temp_total = $attendance_breaks->getTotalLateBreakInHrs();
                    $total += $temp_total;
                }
            }
        }
        return $total;
    }

	public static function matchBreakLogsToScheduleBreak( $attendance_breaks = null, $schedule_breaks = array() ) {
        $breaks = array(
            array(
                'type_out'      => G_Employee_Break_Logs::TYPE_B1_OUT,
                'log_id_out'    => $attendance_breaks->getLogBreak1OutId(),
                'datetime_out'  => $attendance_breaks->getLogBreak1Out(),
                'type_in'       => G_Employee_Break_Logs::TYPE_B1_IN,
                'log_id_in'     => $attendance_breaks->getLogBreak1InId(),
                'datetime_in'   => $attendance_breaks->getLogBreak1In()
            ),
            array(
                'type_out'      => G_Employee_Break_Logs::TYPE_B2_OUT,
                'log_id_out'    => $attendance_breaks->getLogBreak2OutId(),
                'datetime_out'  => $attendance_breaks->getLogBreak2Out(),
                'type_in'       => G_Employee_Break_Logs::TYPE_B2_IN,
                'log_id_in'     => $attendance_breaks->getLogBreak2InId(),
                'datetime_in'   => $attendance_breaks->getLogBreak2In()
            ),
            array(
                'type_out'      => G_Employee_Break_Logs::TYPE_B3_OUT,
                'log_id_out'    => $attendance_breaks->getLogBreak3OutId(),
                'datetime_out'  => $attendance_breaks->getLogBreak3Out(),
                'type_in'       => G_Employee_Break_Logs::TYPE_B3_IN,
                'log_id_in'     => $attendance_breaks->getLogBreak3InId(),
                'datetime_in'   => $attendance_breaks->getLogBreak3In()
            )
        );

        $has_incomplete_break_logs = false;

        $attendance_breaks->setLogBreak1OutId('');
        $attendance_breaks->setLogBreak1Out('');
        $attendance_breaks->setLogBreak1InId('');
        $attendance_breaks->setLogBreak1In('');
        $attendance_breaks->setLogBreak2OutId('');
        $attendance_breaks->setLogBreak2Out('');
        $attendance_breaks->setLogBreak2InId('');
        $attendance_breaks->setLogBreak2In('');
        $attendance_breaks->setLogBreak3OutId('');
        $attendance_breaks->setLogBreak3Out('');
        $attendance_breaks->setLogBreak3InId('');
        $attendance_breaks->setLogBreak3In('');

        if ($attendance_breaks) {
            $count = 0;

            foreach ($schedule_breaks as $key => $schedule_break) {
                // $schedule_break_out = date('Y-m-d H:i:s', strtotime($attendance_breaks->getAttendanceDate() . ' ' . $schedule_break['break_in']));
                // $schedule_break_in = date('Y-m-d H:i:s', strtotime($attendance_breaks->getAttendanceDate() . ' ' . $schedule_break['break_out']));
    
                // if ($schedule_break_out > $schedule_break_in) {
                //     $schedule_break_in = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($schedule_break_in)));;
                // }
                $to_deduct = $schedule_break['to_deduct'];
                $to_required_logs = $schedule_break['to_required_logs'];

                foreach ($breaks as $break_key => $break) {
                    if ($to_deduct) {
                        $attendance_breaks->{'setRequiredLogBreak'. ($count + 1)}($to_required_logs);
                        $attendance_breaks->{'setLogBreak'. ($count + 1) .'OutId'}($break['log_id_out']);
                        $attendance_breaks->{'setLogBreak'. ($count + 1) .'Out'}($break['datetime_out']);
                        $attendance_breaks->{'setLogBreak'. ($count + 1) .'InId'}($break['log_id_in']);
                        $attendance_breaks->{'setLogBreak'. ($count + 1) .'In'}($break['datetime_in']);

                        if($to_required_logs)
                        {
                            if (empty($break['datetime_out']) || empty($break['datetime_in'])) 
                            {
                                $has_incomplete_break_logs = true;
                            }
                        }
                        

                        unset($breaks[$break_key]);
                    }
                    else {
                        // $attendance_breaks->{'setRequiredLogBreak'. ($count + 1)}(false);
                        $attendance_breaks->{'setRequiredLogBreak'. ($count + 1)}($to_required_logs);
                    }

                    break;

                    // if (
                    //     $break['datetime_in'] >= $schedule_break_out &&
                    //     $schedule_break_in >= $break['datetime_out']
                    // ) {
                    //     $attendance_breaks->{'setLogBreak'. ($count + 1) .'OutId'}($break['log_id_out']);
                    //     $attendance_breaks->{'setLogBreak'. ($count + 1) .'Out'}($break['datetime_out']);
                    //     $attendance_breaks->{'setLogBreak'. ($count + 1) .'InId'}($break['log_id_in']);
                    //     $attendance_breaks->{'setLogBreak'. ($count + 1) .'In'}($break['datetime_in']);

                    //     unset($breaks[$break_key]);
                    //     break;
                    // }
                }

                $count++;
            }

            // foreach ($breaks as $break_key => $break) {
            //     if ($break['type_out'] == G_Employee_Break_Logs::TYPE_B1_OUT && $break['type_in'] == G_Employee_Break_Logs::TYPE_B1_IN) {
            //         $attendance_breaks->setLogBreak1OutId($break['log_id_out']);
            //         $attendance_breaks->setLogBreak1Out($break['datetime_out']);
            //         $attendance_breaks->setLogBreak1InId($break['log_id_in']);
            //         $attendance_breaks->setLogBreak1In($break['datetime_in']);
            //     }
            //     elseif ($break['type_out'] == G_Employee_Break_Logs::TYPE_B2_OUT && $break['type_in'] == G_Employee_Break_Logs::TYPE_B2_IN) {
            //         $attendance_breaks->setLogBreak2OutId($break['log_id_out']);
            //         $attendance_breaks->setLogBreak2Out($break['datetime_out']);
            //         $attendance_breaks->setLogBreak2InId($break['log_id_in']);
            //         $attendance_breaks->setLogBreak2In($break['datetime_in']);
            //     }
            //     elseif ($break['type_out'] == G_Employee_Break_Logs::TYPE_B3_OUT && $break['type_in'] == G_Employee_Break_Logs::TYPE_B3_IN) {
            //         $attendance_breaks->setLogBreak3OutId($break['log_id_out']);
            //         $attendance_breaks->setLogBreak3Out($break['datetime_out']);
            //         $attendance_breaks->setLogBreak3InId($break['log_id_in']);
            //         $attendance_breaks->setLogBreak3In($break['datetime_in']);
            //     }
            // }
        }
        
        $attendance_breaks->setHasIncompleteBreakLogs($has_incomplete_break_logs);
            
        return $attendance_breaks;
    }

    public static function checkHasIncompleteBreakByDateRangeEmployeeId($from, $to, $employee_id) {
        $has_incomplete_break_logs = false;

        $sql = "
            SELECT COUNT(id) as total
            FROM " . G_EMPLOYEE_BREAK_LOGS_SUMMARY ."
            WHERE attendance_date BETWEEN " . Model::safeSql($from) . " AND " . Model::safeSql($to) . "
            AND employee_id = ". Model::safeSql($employee_id) ."
            AND has_incomplete_break_logs = 1
        ";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);

        if( $row['total'] > 0 ){
            $has_incomplete_break_logs = true;
        }
        
        return $has_incomplete_break_logs;
    }

    public static function checkHasIncompleteBreakLogs() {
        $_flag = false;

        $sql = "
            SELECT COUNT(id) as total
            FROM " . G_EMPLOYEE_BREAK_LOGS_SUMMARY ."
            has_incomplete_break_logs = 1
        ";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);

        if( $row['total'] > 0 )
        {
            $_flag = true;
        }
        
        return $_flag;
    }

    public static function checkHasLateBreakIn() {
        $_flag = false;

        $sql = "
            SELECT COUNT(id) as total
            FROM " . G_EMPLOYEE_BREAK_LOGS_SUMMARY ."
            has_late_break_in = 1
        ";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);

        if( $row['total'] > 0 )
        {
            $_flag = true;
        }
        
        return $_flag;
    }

    public static function checkHasEarlyBreakOut() {
        $_flag = false;

        $sql = "
            SELECT COUNT(id) as total
            FROM " . G_EMPLOYEE_BREAK_LOGS_SUMMARY ."
            has_early_break_out = 1
        ";

        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);

        if( $row['total'] > 0 )
        {
            $_flag = true;
        }
        
        return $_flag;
    }

}
?>