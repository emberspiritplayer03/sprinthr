<?php
class G_Restday_Manager {
    /*
     *  $restday_objects - array of G_Restday
     */
    public static function saveMultiple($restday_objects) {
        $has_record = false;
        foreach ($restday_objects as $o) {
            $insert_sql_values[] = "
                (". Model::safeSql($o->getId()) .",
                ". Model::safeSql($o->getDate()) .",
                ". Model::safeSql($o->getTimeIn()) .",
                ". Model::safeSql($o->getTimeOut()) .",
                ". Model::safeSql($o->getReason()) .",
                ". Model::safeSql($o->getEmployeeId()) .")";
            $has_record = true;
        }

        if ($has_record) {
            $insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". G_EMPLOYEE_RESTDAY ." (id, date, time_in, time_out, reason, employee_id)
                VALUES ". $insert_sql_value ."
                ON DUPLICATE KEY UPDATE
                    date = VALUES(date),
                    time_in = VALUES(time_in),
                    time_out = VALUES(time_out),
                    reason = VALUES(reason),
                    employee_id = VALUES(employee_id)
            ";            
            Model::runSql($sql_insert);
        }

        if (mysql_errno() > 0) {            
            return false;
        } else {
            return true;
        }
    }

    public static function deleteMultiple($restday_objects) {
        $has_record = false;
        foreach ($restday_objects as $o) {
        	$date = $o->getDate();
        	$employee_id = $o->getEmployeeId();
        	if( trim($date) != '' && trim($employee_id) != '' ){
        		$sql = "
					DELETE FROM ". G_EMPLOYEE_RESTDAY ."
					WHERE date =" . Model::safeSql($date) . " AND employee_id = ". Model::safeSql($employee_id) ."
				";				
				Model::runSql($sql);
        	}        	
        }
    }

	public static function save($o) {
		if ($o->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". G_EMPLOYEE_RESTDAY;
			$sql_end   = " WHERE id = ". Model::safeSql($o->getId());		
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_EMPLOYEE_RESTDAY;
			//$sql_end   = ",public_id = ". Model::safeSql($public_id);
		}
		
		 $sql = $sql_start ."
			SET
			employee_id = ". Model::safeSql($o->getEmployeeId()) .",
			date = ". Model::safeSql($o->getDate()) .",
			time_in = ". Model::safeSql($o->getTimeIn()) .",
			time_out = ". Model::safeSql($o->getTimeOut()) .",
			reason = ". Model::safeSql($o->getReason()) ."
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

	public static function deleteByDateAndEmployeeId($date = '', $employee_id = 0) {
		$sql = "
			DELETE FROM ". G_EMPLOYEE_RESTDAY ."
			WHERE date =" . Model::safeSql($date) . " AND employee_id = ". Model::safeSql($employee_id) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}	
	
	public static function delete($o) {
		$sql = "
			DELETE FROM ". G_EMPLOYEE_RESTDAY ."
			WHERE id = ". Model::safeSql($o->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}	
}
?>