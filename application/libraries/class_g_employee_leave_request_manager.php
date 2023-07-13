<?php
class G_Employee_Leave_Request_Manager {
    /*
     *  $leave_request_objects - array of G_Employee_Leave_Request
     */
    public static function saveMultiple($leave_request_objects) {
        $has_record = false;
        foreach ($leave_request_objects as $o) {
            $insert_sql_values[] = "
                (". Sql::safeSql($o->getId()) .",
                ". Sql::safeSql($o->getCompanyStructureId()) .",
                ". Sql::safeSql($o->getEmployeeId()) .",
                ". Sql::safeSql($o->getLeaveId()) .",
                ". Sql::safeSql($o->getDateApplied()) .",
                ". Sql::safeSql($o->getTimeApplied()) .",
                ". Sql::safeSql($o->getDateStart()) .",
                ". Sql::safeSql($o->getDateEnd()) .",
                ". Sql::safeSql($o->getApplyHalfDayDateStart()) .",
                ". Sql::safeSql($o->getApplyHalfDayDateEnd()) .",
                ". Sql::safeSql($o->getLeaveComments()) .",
                ". Sql::safeSql($o->getIsApproved()) .",
                ". Sql::safeSql($o->getIsPaid()) .",
                ". Sql::safeSql($o->getCreatedBy()) .",
                ". Sql::safeSql($o->getIsArchive()) .")";
            $has_record = true;
        }

        if ($has_record) {
            $insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". G_EMPLOYEE_LEAVE_REQUEST ." (id, company_structure_id, employee_id, leave_id, date_applied, time_applied, date_start, date_end, apply_half_day_date_start, apply_half_day_date_end, leave_comments, is_approved, is_paid, created_by, is_archive)
                VALUES ". $insert_sql_value ."
                ON DUPLICATE KEY UPDATE
                    company_structure_id = VALUES(company_structure_id),
                    employee_id = VALUES(employee_id),
                    leave_id = VALUES(leave_id),
                    date_applied = VALUES(date_applied),
                    time_applied = VALUES(time_applied),
                    date_start = VALUES(date_start),
                    date_end = VALUES(date_end),
                    apply_half_day_date_start = VALUES(apply_half_day_date_start),
                    apply_half_day_date_end = VALUES(apply_half_day_date_end),
                    leave_comments = VALUES(leave_comments),
                    is_approved = VALUES(is_approved),
                    is_paid = VALUES(is_paid),
                    created_by = VALUES(created_by),
                    is_archive = VALUES(is_archive)
            ";
            Sql::runSql($sql_insert);
        }

        if (Sql::getErrorNumber() > 0) {
            //echo mysql_error();
            return false;
        } else {
            $insert_id = Sql::getInsertId();
            if ($insert_id > 0) {
                return $insert_id;
            } else {
                return true;
            }
        }
    }

    public static function save(G_Employee_Leave_Request $e) {
        $es[] = $e;
        return self::saveMultiple($es);
    }

    /*
    * Deprecated
    */
	public static function save_old(G_Employee_Leave_Request $e) {
		if ($e->getId() > 0 ) {
			$action = "update";
			$sql_start = "UPDATE ". G_EMPLOYEE_LEAVE_REQUEST . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());
		}else{
			$action = "insert";
			$sql_start = "INSERT INTO ". G_EMPLOYEE_LEAVE_REQUEST . "";
			$sql_end   = "";
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id	  = " . Model::safeSql($e->getCompanyStructureId()) .",
			employee_id				  = " . Model::safeSql($e->getEmployeeId()) .",
			leave_id		   		  = " . Model::safeSql($e->getLeaveId()) .",
			date_applied			  = " . Model::safeSql($e->getDateApplied()) .",
            time_applied			  = " . Model::safeSql($e->getTimeApplied()) .",
			date_start				  = " . Model::safeSql($e->getDateStart()) .",
			date_end				  = " . Model::safeSql($e->getDateEnd()) .",
			apply_half_day_date_start = " . Model::safeSql($e->getApplyHalfDayDateStart()) .",
			apply_half_day_date_end   = " . Model::safeSql($e->getApplyHalfDayDateEnd()) .",
			leave_comments			  = " . Model::safeSql($e->getLeaveComments()) .",
			is_approved				  = " . Model::safeSql($e->getIsApproved()) .",
			is_paid					  = " . Model::safeSql($e->getIsPaid()) .",
			created_by				  = " . Model::safeSql($e->getCreatedBy()) .",
			is_archive				  = " . Model::safeSql($e->getIsArchive()) ."
			"
			. $sql_end ."	
		
		";	
	
		Model::runSql($sql);
		if($action == "update") {
			return $e->getId();
		} else {
			return mysql_insert_id();
		}
		
	}

    public static function bulkInsertData( $a_bulk_insert = array(), $fields = array() ) {
        $return = false;
        if( !empty( $a_bulk_insert ) ){

            if( !empty($fields) ){
                $sql_fields = implode(",", $fields);
            }else{
                $sql_fields = "company_structure_id,employee_id,leave_id,date_applied,time_applied,date_start,date_end,leave_comments,is_approved,is_paid,created_by,is_archive,apply_half_day_date_start";
            }

            $sql_values = implode(",", $a_bulk_insert);
            $sql        = "
                INSERT INTO " . G_EMPLOYEE_LEAVE_REQUEST . "({$sql_fields})
                VALUES{$sql_values}
            ";            
            Model::runSql($sql);
            $return = true;
        }

        return $return;
    }
		
	public static function delete(G_Employee_Leave_Request $e){
		if(G_Employee_Leave_Request_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_LEAVE_REQUEST ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>