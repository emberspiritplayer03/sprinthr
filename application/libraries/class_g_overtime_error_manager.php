<?php
class G_Overtime_Error_Manager {
    /*
    * param $error - Instance of G_Overtime_Error
    */
    public static function save($error) {
        $errors[] = $error;
        return self::saveMultiple($errors);
    }
    /*
     * param $errors - array of G_Overtime_Error
     */
    public static function saveMultiple($errors) {
        $has_record = false;
        foreach ($errors as $o) {
            $insert_sql_values[] = "
                (". Model::safeSql($o->getId()) .",
                ". Model::safeSql($o->getDate()) .",
                ". Model::safeSql($o->getEmployeeId()) .",
                ". Model::safeSql($o->getMessage()) .",
                ". Model::safeSql($o->isFixed()) .",
                ". Model::safeSql($o->getTimeIn()) .",
                ". Model::safeSql($o->getTimeOut()) .",
                ". Model::safeSql($o->getEmployeeName()) .",
                ". Model::safeSql($o->getEmployeeCode()) .",
                ". Model::safeSql($o->getErrorTypeId()) .")";
            $has_record = true;
        }

        if ($has_record) {
            $insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". G_OVERTIME_ERROR ." (id, date_attendance, employee_id, message, is_fixed, time_in, time_out, employee_name, employee_code, error_type_id)
                VALUES ". $insert_sql_value ."
                ON DUPLICATE KEY UPDATE
                    date_attendance = VALUES(date_attendance),
                    employee_id = VALUES(employee_id),
                    message = VALUES(message),
                    is_fixed = VALUES(is_fixed),
                    time_in = VALUES(time_in),
                    time_out = VALUES(time_out),
                    employee_name = VALUES(employee_name),
                    employee_code = VALUES(employee_code),
                    error_type_id = VALUES(error_type_id)
            ";
            Model::runSql($sql_insert);
        }

        if (mysql_errno() > 0) {
            //echo mysql_error();
            return false;
        } else {
            return true;
        }
    }

	public static function add($e) {
		if ($e->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". G_OVERTIME_ERROR;
			$sql_end   = " WHERE id = ". Model::safeSql($e->getId());		
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_OVERTIME_ERROR;
			//$sql_end   = ",public_id = ". Model::safeSql($public_id);
		}
		
		$sql = $sql_start ."
			SET
			employee_id   	= " . Model::safeSql($e->getEmployeeId()) .",
			employee_code  	= " . Model::safeSql($e->getEmployeeCode()) .",
			employee_name  	= " . Model::safeSql($e->getEmployeeName()) .",
			date_attendance	= " . Model::safeSql($e->getDate()) .",
			time_in			= " . Model::safeSql($e->getTimeIn()) .",
			time_out		= " . Model::safeSql($e->getTimeOut()) .",
			message        	= " . Model::safeSql($e->getMessage()) .",
			is_fixed		= " . Model::safeSql($e->isFixed()) .",
			error_type_id	= " . Model::safeSql($e->getErrorTypeId()) ."
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
}
?>