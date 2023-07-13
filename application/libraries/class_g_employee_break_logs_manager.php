<?php
class G_Employee_Break_Logs_Manager {

	public static function save($model) {
		if ($model->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". G_EMPLOYEE_BREAK_LOGS;
			$sql_end   = " WHERE id = ". Model::safeSql($model->getId());		
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_EMPLOYEE_BREAK_LOGS;
		}
		
		$time = date("H:i:s",strtotime($model->getTime()));
		$type = $model->getType();

		$sql = $sql_start ."
			SET
			employee_id = ". Model::safeSql($model->getEmployeeId()) .",
			employee_code = '". $model->getEmployeeCode() ."',
			employee_name = ". Model::safeSql($model->getEmployeeName()) .",
			date = ". Model::safeSql($model->getDate()) .",
			time = ". Model::safeSql($time) .",
			type = ". Model::safeSql($type) .",	
			remarks = ". Model::safeSql($model->getRemarks()) .", is_transferred = 0, employee_device_id = 0
			". $sql_end ."		
		";			
		
        Model::runSql($sql);
		if (mysql_errno() > 0) {
			return false;
		}
		if ($action == 'insert') {
			return mysql_insert_id();
		} else if ($action == 'update') {
			return true;
		}
	}

    public static function saveMultiple($multiple_attendance) {
        $has_record = false;        
        foreach ($multiple_attendance as $a) {
            $insert_sql_values[] = "(". Model::safeSql($a->getId()) .",". Model::safeSql($a->getEmployeeId()) .",'". $a->getEmployeeCode() ."',". Model::safeSql($a->getDate()) .",". Model::safeSql($a->getTime()) .",". Model::safeSql($a->getType()) .",". Model::safeSql($a->getEmployeeName()) .")";
            $has_record = true;
		}

        if ($has_record) {
            $insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". G_EMPLOYEE_BREAK_LOGS ." (id, employee_id, employee_code, date, time, type, employee_name)
                VALUES ". $insert_sql_value ."
            ";           
            Model::runSql($sql_insert);
        }

        if (mysql_errno() > 0) {
            return false;
        } else {
            return true;
        }
    }
    
	public static function delete(G_Employee_Break_Logs $log){
		$affected_rows = 0;
		if(G_Employee_Break_Logs_Helper::sqlIsIdExists($log->getId()) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_BREAK_LOGS ."
				WHERE id =" . Model::safeSql($log->getId());			
			Model::runSql($sql);
			$affected_rows = mysql_affected_rows();
		}	

		return $affected_rows;
	}

	public static function resetLogsToNotTransferredByDateRange($date_from = '', $date_to = '') {
		$total_records_updated = 0;
		$sql = "
			UPDATE ". G_EMPLOYEE_BREAK_LOGS ."
			SET is_transferred =" . Model::safeSql(G_Attendance_Log::ISNOT_TRANSFERRED) . "
			WHERE date BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
		";				
		Model::runSql($sql);
		$total_records_updated = mysql_affected_rows();				
		return $total_records_updated;
	}	
}
?>