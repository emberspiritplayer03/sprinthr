<?php
class G_Overtime_Manager {
    /*
     *  $overtime_objects - array of G_Overtime
     */
    public static function saveMultiple($overtime_objects) {
        $has_record = false;                
        foreach ($overtime_objects as $o) {
            $insert_sql_values[] = "
                (". Model::safeSql($o->getId()) .",
                ". Model::safeSql($o->getDate()) .",
                ". Model::safeSql($o->getTimeIn()) .",
                ". Model::safeSql($o->getTimeOut()) .",
                ". Model::safeSql($o->getReason()) .",
                ". Model::safeSql($o->getArchiveStatus()) .",
                ". Model::safeSql($o->getStatus()) .",
                ". Model::safeSql($o->getDateIn()) .",
                ". Model::safeSql($o->getDateOut()) .",
                ". Model::safeSql($o->getDateCreated()) .",
                ". Model::safeSql($o->getEmployeeId()) .")";
            $has_record = true;
        }
        if ($has_record) {          
            $insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". G_EMPLOYEE_OVERTIME ." (id, date, time_in, time_out, reason, is_archived, status, date_in, date_out, date_created, employee_id)
                VALUES ". $insert_sql_value ."
                ON DUPLICATE KEY UPDATE
                    date = VALUES(date),
                    time_in = VALUES(time_in),
                    time_out = VALUES(time_out),
                    reason = VALUES(reason),
                    is_archived = VALUES(is_archived),
                    status = VALUES(status),
                    date_in = VALUES(date_in),
                    date_out = VALUES(date_out),
                    date_created = VALUES(date_created),
                    employee_id = VALUES(employee_id)
            ";                                                     
            Model::runSql($sql_insert);
        }

        if (mysql_errno() > 0) {
            //echo mysql_error();
            return false;
        } else {
            //return true;
            $insert_id = mysql_insert_id();
            if ($insert_id > 0) {
                return $insert_id;
            } else {
                return true;
            }
        }
    }

    public static function save($o) {
        $os[] = $o;
        return self::saveMultiple($os);
    }

    /*
	public static function save($o) {
		if ($o->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". G_EMPLOYEE_OVERTIME;
			$sql_end   = " WHERE id = ". Model::safeSql($o->getId());		
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_EMPLOYEE_OVERTIME;
			//$sql_end   = ",public_id = ". Model::safeSql($public_id);
		}
		
		 $sql = $sql_start ."
			SET
			employee_id = ". Model::safeSql($o->getEmployeeId()) .",
			date = ". Model::safeSql($o->getDate()) .",
			time_in = ". Model::safeSql($o->getTimeIn()) .",
			time_out = ". Model::safeSql($o->getTimeOut()) .",
			reason = ". Model::safeSql($o->getReason()) .",
            status = ". Model::safeSql($o->getStatus()) .",
            is_archived = ". Model::safeSql($o->getArchiveStatus()) ."
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
	}*/
	
	public static function delete($o) {
		$sql = "
			DELETE FROM ". G_EMPLOYEE_OVERTIME ."
			WHERE id = ". Model::safeSql($o->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}	

    public static function deleteAutoOvertimePendingRequestByEmployeeAndDate($employee_id = 0, $date = '') {
        $s_reason = G_Overtime::AUTO_OVERTIME_DESCRIPTION;
        $sql_date = date("Y-m-d",strtotime($date));
        $sql = "
            DELETE FROM ". G_EMPLOYEE_OVERTIME ."
            WHERE employee_id = ". Model::safeSql($employee_id) ."
                AND date =" . Model::safeSql($sql_date) . "
                AND status =" . Model::safeSql(G_Overtime::STATUS_PENDING) . "
                AND reason =" . Model::safeSql($s_reason) . "
        ";
        Model::runSql($sql);
        return (mysql_affected_rows() >= 1) ? true : false ;
    }   

    public static function deleteAutoOvertimeRequestByEmployeeAndDate($employee_id = 0, $date = '') {
        $s_reason = G_Overtime::AUTO_OVERTIME_DESCRIPTION;
        $sql_date = date("Y-m-d",strtotime($date));
        $sql = "
            DELETE FROM ". G_EMPLOYEE_OVERTIME ." 
            WHERE employee_id = ". Model::safeSql($employee_id) ."
                AND date =" . Model::safeSql($sql_date) . "
                AND (
                    status =" . Model::safeSql(G_Overtime::STATUS_APPROVED) . " 
                    OR status =" . Model::safeSql(G_Overtime::STATUS_PENDING) . "
                    OR status =" . Model::safeSql(G_Overtime::STATUS_DISAPPROVED) . " 
                )                       
                AND reason =" . Model::safeSql($s_reason) . "
        ";         
        Model::runSql($sql);
        return (mysql_affected_rows() >= 1) ? true : false ;
    }   
}
?>