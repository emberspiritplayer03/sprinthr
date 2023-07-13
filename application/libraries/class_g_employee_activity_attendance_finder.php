<?php


class G_Employee_Activity_Attendance_Finder
{


    public static function getEmployeeFields() {
        return "ea.id,ea.employee_activity_id,ea.employee_id,ea.project_site_id,ea.frequency_id,ea.payslip_id,ea.date,ea.activity_in,ea.activity_out,ea.activity_raw_worked_hrs,ea.activity_deductible_break_hrs,ea.activity_total_worked_hrs,total_amount_worked";
    }


    public function findActivityByDateFromTo($frequency_id,$from,$to){

        $sql = "
            SELECT ". self::getEmployeeFields() ."
            FROM g_employee_activity_attendance ea
            WHERE ea.date between ".Model::safeSql($from)." AND ".Model::safeSql($to)."
            AND ea.frequency_id = ".Model::safeSql($frequency_id)."

        ";      
        $result = Model::runSql($sql, true);
        return $result; 


    }

     public function findActivityByDateFromToAndProjectSiteId($frequency_id,$from,$to,$pid){

        $sql = "
            SELECT ". self::getEmployeeFields() ."
            FROM g_employee_activity_attendance ea
            WHERE ea.date between ".Model::safeSql($from)." AND ".Model::safeSql($to)."
            AND ea.project_site_id=".Model::safeSql($pid)." 
            AND ea.frequency_id = ".Model::safeSql($frequency_id)."
        ";      
        $result = Model::runSql($sql, true);
        return $result; 


    }


    private static function getRecord($sql) {
        $result = Model::runSql($sql);
        $total = mysql_num_rows($result);
        if ($total == 0) {
            return false;   
        }       
        $row = Model::fetchAssoc($result);
        $records = self::newObject($row);

        return $records;
        //print_r($records);
        //echo $records->getId();
    
    }
    

    private static function getRecords($sql) {
        $result = Model::runSql($sql);
        $total = mysql_num_rows($result);
        if ($total == 0) {
            return false;   
        }
        while ($row = Model::fetchAssoc($result)) {
            $records[$row['id']] = self::newObject($row);
        }
        return $records;
    }
    
    private static function newObject($row) {
        
        $e = new G_Employee_Activity_Attendance;
        $e->setId($row['id']);
        $e->setActivityId($row['employee_activity_id']);
        $e->setEmployeeId($row['employee_id']);
        $e->setProjectSiteId($row['project_site_id']);
        $e->setFrequencyId($row['frequency_id']);
        $e->setPayslipId($row['payslip_id']);
        $e->setDate($row['date']);
        $e->setActivityIn($row['activity_in']);
        $e->setActivityOut($row['activity_out']);
        $e->setActivityRawWorkedHrs($row['activity_raw_worked_hrs']);
        $e->setActivityDeductibleBreakHrs($row['activity_deductible_break_hrs']);
        $e->setActivityTotalWorkedHrs($row['activity_total_worked_hrs']);
        $e->setActivityTotalAmountWorked($row['total_amount_worked']);
    //  print_r($e);
        return $e;
    }


}

?>