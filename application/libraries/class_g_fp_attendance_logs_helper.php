<?php
class G_Fp_Attendance_Logs_Helper {

    public static function sqlGetEmployeeLogsByEmployeeCodeAndDate($employee_code = '', $date = '', $fields) {
    	if( !empty($fields) ){
    		$sql_fields = implode(",", $fields);
    	}else{
    		$sql_fields = " * ";
    	}

		$sql = "
            SELECT {$sql_fields}
            FROM ". G_ATTENDANCE_LOG ." 
            WHERE employee_code ='{$employee_code}'
            	AND `date` =" . Model::safeSql($date) . "
            ORDER BY date, type ASC
        ";         
       $records = Model::runSql($sql,true);       
       return $records;
	}

    public static function sqlGetEmployeeLogsByUserIdAndDate($user_id = '', $date = '', $fields) {
        if( !empty($fields) ){
            $sql_fields = implode(",", $fields);
        }else{
            $sql_fields = " * ";
        }

        $sql = "
            SELECT {$sql_fields}
            FROM ". G_ATTENDANCE_LOG ." 
            WHERE user_id =" . Model::safeSql($user_id) . "
                AND `date` =" . Model::safeSql($date) . "
            ORDER BY date, type ASC
        ";  
       $records = Model::runSql($sql,true);       
       return $records;
    }

    public static function sqlCountMultipleInOutByDateRange($from = '', $to = '') {

        $sqlin = "
            SELECT COUNT(eal.id) AS total_in, eal.date as attendance_date, eal.employee_code, eal.employee_name  
            FROM " . G_ATTENDANCE_LOG . " eal
            WHERE eal.date BETWEEN " . Model::safeSql($from) . " AND " . Model::safeSql($to) . "
            AND eal.type = 'in' AND (eal.employee_name != '' OR eal.user_id != 0)
            GROUP BY attendance_date, eal.employee_code
        ";
        $records_in = Model::runSql($sqlin,true);       

        $multiple_in_array = array();
        foreach($records_in as $rin) {
            if($rin['total_in'] > 1 ) {
                $multiple_in_array[] = $rin;
            }
        }

        $sqlout = "
            SELECT COUNT(eal.id) AS total_out, eal.date as attendance_date, eal.employee_code, eal.employee_name  
            FROM " . G_ATTENDANCE_LOG . " eal
            WHERE eal.date BETWEEN " . Model::safeSql($from) . " AND " . Model::safeSql($to) . "
            AND eal.type = 'out' AND (eal.employee_name != '' OR eal.user_id != 0)
            GROUP BY attendance_date, eal.employee_code
        ";
        $records_out = Model::runSql($sqlout,true);       

        $multiple_out_array = array();
        foreach($records_out as $rout) {
            if($rout['total_out'] > 1 ) {
                $multiple_out_array[] = $rout;
            }
        }        

        $total_multiple_in_out = (count($multiple_in_array) + count($multiple_out_array));
        return $total_multiple_in_out;
    }

    public static function sqlGetMultipleInOutByDateRange($from = '', $to = '') {
        $sqlin = "
            SELECT COUNT(eal.id) AS total_in, eal.date as attendance_date, eal.employee_code, eal.employee_name, eal.type as type  
            FROM " . G_ATTENDANCE_LOG . " eal
            WHERE eal.date BETWEEN " . Model::safeSql($from) . " AND " . Model::safeSql($to) . "
            AND eal.type = 'in' AND eal.employee_name != ''
            GROUP BY attendance_date, eal.employee_code
        ";
        $records_in = Model::runSql($sqlin,true);       

        $multiple_in_array = array();
        foreach($records_in as $rin) {
            if($rin['total_in'] > 1 ) {
                $multiple_in_array[] = $rin;
            }
        }

        $sqlout = "
            SELECT COUNT(eal.id) AS total_out, eal.date as attendance_date, eal.employee_code, eal.employee_name, eal.type as type   
            FROM " . G_ATTENDANCE_LOG . " eal
            WHERE eal.date BETWEEN " . Model::safeSql($from) . " AND " . Model::safeSql($to) . "
            AND eal.type = 'out' AND eal.employee_name != ''
            GROUP BY attendance_date, eal.employee_code
        ";
        $records_out = Model::runSql($sqlout,true);       

        $multiple_out_array = array();
        foreach($records_out as $rout) {
            if($rout['total_out'] > 1 ) {
                $multiple_out_array[] = $rout;
            }
        }        

        $in_out_data = array_merge($multiple_in_array,$multiple_out_array);

        return $in_out_data;
    }    

    public static function sqlIsIdExists($id) {
        $is_exists = false;

        $sql = "
            SELECT COUNT(id) as total
            FROM " . G_ATTENDANCE_LOG ."
            WHERE id = ". Model::safeSql($id) ."
        ";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);

        if( $row['total'] > 0 ){
            $is_exists = true;
        }
        
        return $is_exists;
    }

    public static function sqlGetPairedLogsByEmployeeIdDate($employee_id, $date) {
        $date_from = date("Y-m-d", strtotime($date));
        $date_to = date("Y-m-d", strtotime("+1 days", strtotime($date)));
        $logs = array();

        $sql_in = "
            SELECT eal.date, eal.time, eal.employee_code, eal.employee_name, eal.type as type  
            FROM " . G_ATTENDANCE_LOG . " eal
            WHERE eal.date BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
            AND type = 'in'
            ORDER BY date ASC, time ASC
        ";
        $records_in = Model::runSql($sql_in,true);

        foreach($records_in as $rin) {
            if (!array_key_exists($rin['type'],$logs)) {
                $logs[$rin['type']] = $rin;
            }
        }

        $sql_out = "
            SELECT eal.date, eal.time, eal.employee_code, eal.employee_name, eal.type as type  
            FROM " . G_ATTENDANCE_LOG . " eal
            WHERE eal.date BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
            AND type = 'out'
            ORDER BY date DESC, time DESC
        ";
        $records_out = Model::runSql($sql_out,true);

        foreach($records_out as $rout) {
            if (!array_key_exists($rout['type'],$logs)) {
                $logs[$rout['type']] = $rout;
            }
            else {
                if ($date_from != $rout['date']) {
                    $logs[$rout['type']] = $rout;
                }
            }
        }
         
        return $logs;
    }  


     public static function findDeviceNoByEmployeeIdTypeTimeDate($employee_id, $type, $time, $date){
        $sql = "
        SELECT SUBSTRING_INDEX(`remarks`, ':', -1 ) as device_no FROM `g_fp_attendance_log` WHERE 
        user_id = " . Model::safeSql($employee_id) . "
        AND type = " . Model::safeSql($type) . "
        AND SUBSTRING_INDEX(`time`,':', 2 ) = " . Model::safeSql(date("H:i", strtotime($time))) . "
        AND date = " . Model::safeSql($date) . "
        LIMIT 1";

        $result = Model::runSql($sql,true); 
        return $result[0]['device_no'];
    }
}
?>