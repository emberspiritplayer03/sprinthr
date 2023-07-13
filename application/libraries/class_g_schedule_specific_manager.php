<?php
class G_Schedule_Specific_Manager {

    /*
     *  $schedule_objects - array of G_Schedule_Specific
     */
    public static function saveMultiple($schedule_objects) {
        $has_record = false;
        foreach ($schedule_objects as $o) {
            $insert_sql_values[] = "
                (". Model::safeSql($o->getId()) .",
                ". Model::safeSql($o->getDateStart()) .",
                ". Model::safeSql($o->getDateEnd()) .",
                ". Model::safeSql($o->getTimeIn()) .",
                ". Model::safeSql($o->getTimeOut()) .",
                ". Model::safeSql($o->getEmployeeId()) .")";
            $has_record = true;
        }

        if ($has_record) {
            $insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". G_EMPLOYEE_SCHEDULE ." (id, date_start, date_end, time_in, time_out, employee_id)
                VALUES ". $insert_sql_value ."
                ON DUPLICATE KEY UPDATE
                    date_start = VALUES(date_start),
                    date_end = VALUES(date_end),
                    time_in = VALUES(time_in),
                    time_out = VALUES(time_out),
                    employee_id = VALUES(employee_id)
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

	/*
		$ss - Instance of G_Schedule_Specific class
	*/
	public static function save($ss) {
		if ($ss->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". G_EMPLOYEE_SCHEDULE;
			$sql_end   = " WHERE id = ". Model::safeSql($ss->getId());		
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_EMPLOYEE_SCHEDULE;
			//$sql_end   = ",public_id = ". Model::safeSql($public_id);
		}
		
		$sql = $sql_start ."
			SET
			employee_id = ". Model::safeSql($ss->getEmployeeId()) .",
			date_start = ". Model::safeSql($ss->getDateStart()) .",
			date_end = ". Model::safeSql($ss->getDateEnd()) .",
			time_in = ". Model::safeSql($ss->getTimeIn()) .",
			time_out = ". Model::safeSql($ss->getTimeOut()) ."
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
	
	/*
		Variables
		$sf - Instance of G_Schedule_Specific class
	*/		
	public static function delete($sf) {
		$sql = "
			DELETE FROM ". G_EMPLOYEE_SCHEDULE ."
			WHERE id = ". Model::safeSql($sf->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}		
}
?>