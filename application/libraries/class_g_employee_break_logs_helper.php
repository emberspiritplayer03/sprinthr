<?php
class G_Employee_Break_Logs_Helper {

    public static function sqlIsIdExists($id) {
        $is_exists = false;

        $sql = "
            SELECT COUNT(id) as total
            FROM " . G_EMPLOYEE_BREAK_LOGS ."
            WHERE id = ". Model::safeSql($id) ."
        ";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);

        if( $row['total'] > 0 ){
            $is_exists = true;
        }
        
        return $is_exists;
    }

	public static function sqlGetAllLogsNotTransferredByDateRange( $from = '', $to = '' ) {
		$value_from = date("Y-m-d",strtotime($from));
		$value_to   = date("Y-m-d",strtotime($to));

		$sql = "
			SELECT  id, employee_id, employee_code, date, time, type
			FROM ". G_EMPLOYEE_BREAK_LOGS ."
			WHERE date BETWEEN " . Model::safeSql($value_from) . " AND " . Model::safeSql($value_to) . "
				AND is_transferred =" . Model::safeSql(G_Attendance_Log::ISNOT_TRANSFERRED) . "		
		";			
		$records = Model::runSql($sql,true);
		return $records;
	}
	
	public static function countLogByEmployeeCodeDateTimeType($employee_code, $date, $time, $type) {
		$sql = "
			SELECT COUNT(*) AS total
			FROM ". G_EMPLOYEE_BREAK_LOGS ."
			WHERE employee_code = ". Model::safeSql($employee_code) ."
			AND date = ". Model::safeSql($date) ."
			AND time = ". Model::safeSql($time) ."
			AND type = ". Model::safeSql($type) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
    
}
?>