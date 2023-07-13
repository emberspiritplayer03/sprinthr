<?php


class G_Employee_Activity_Attendance_Manager
{


 public static function saveMultiple($es) {

 	$has_record = false;
        foreach ($es as $e) {

          $insert_sql_values[] = "
            (" . Model::safeSql($e->getId()) .",
            " . Model::safeSql($e->getActivityId()) .",
            " . Model::safeSql($e->getEmployeeId()) .",
            " . Model::safeSql($e->getProjectSiteId()) .",
            " . Model::safeSql($e->getFrequencyId()) .",
            " . Model::safeSql($e->getPayslipId()) .",
            " . Model::safeSql($e->getDate()) .",
            " . Model::safeSql($e->getActivityIn()) .",
            " . Model::safeSql($e->getActivityOut()) .",
            " . Model::safeSql($e->getActivityRawWorkedHrs()) .",
            " . Model::safeSql($e->getActivityDeductibleBreakHrs()) .",
            " . Model::safeSql($e->getActivityTotalAmountWorked()) .",
            '" . $e->getActivityTotalWorkedHrs() . "')";
            $has_record = true;
     }



      if ($has_record) {

			$insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". G_EMPLOYEE_ACTIVITY_ATTENDANCE ." (id,employee_activity_id,employee_id,project_site_id,frequency_id,payslip_id,date,activity_in,activity_out,activity_raw_worked_hrs,activity_deductible_break_hrs,total_amount_worked,activity_total_worked_hrs)
                VALUES ". $insert_sql_value ."
                ON DUPLICATE KEY UPDATE
                    employee_activity_id = VALUES(employee_activity_id),
                    employee_id = VALUES(employee_id),
                    project_site_id = VALUES(project_site_id),
                    frequency_id = VALUES(frequency_id),
                    payslip_id = VALUES(payslip_id),
                    date = VALUES(date),
                    activity_in = VALUES(activity_in),
                    activity_out = VALUES(activity_out),
                    activity_raw_worked_hrs = VALUES(activity_raw_worked_hrs),
                    activity_deductible_break_hrs = VALUES(activity_deductible_break_hrs),
                    total_amount_worked = VALUES(total_amount_worked),
                    activity_total_worked_hrs = VALUES(activity_total_worked_hrs)


                   
            ";        
			 // echo $sql_insert ."============";
            Model::runSql($sql_insert);   
            //not sure about this 
           // exit();      
		}

        if (mysql_errno() > 0) {
            //echo mysql_error();
            return false;
        } else {
            // TODO Use wrapper
            return mysql_insert_id();
        }



 }

  


  public static function save(G_Employee_Activity_Attendance $e) {
		$es[] = $e;
		//utilities::displayArray($es);exit();
        return self::saveMultiple($es);
  }

  public function deleteAttendanceFromTo($from, $to){

			 $sql = "
                DELETE FROM ". G_EMPLOYEE_ACTIVITY_ATTENDANCE ."
                WHERE  date between '".$from."' and '".$to."'";
            Model::runSql($sql);

  }
 



}

?>