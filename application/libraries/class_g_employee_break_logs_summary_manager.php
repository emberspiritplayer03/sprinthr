<?php
class G_Employee_Break_Logs_Summary_Manager {

	public static function save($model) {
		if ($model->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". G_EMPLOYEE_BREAK_LOGS_SUMMARY;
			$sql_end   = " WHERE id = ". Model::safeSql($model->getId());		
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_EMPLOYEE_BREAK_LOGS_SUMMARY;
		}

		$sql = $sql_start ."
			SET
				attendance_date = ". Model::safeSql($model->getAttendanceDate()) .",
				employee_attendance_id = ". Model::safeSql($model->getEmployeeAttendanceId()) .",
				employee_id = ". Model::safeSql($model->getEmployeeId()) .",
				schedule_id = ". Model::safeSql($model->getScheduleId()) .",
				required_log_break1 = ". Model::safeSql($model->getRequiredLogBreak1()) .",
				log_break1_in_id = ". Model::safeSql($model->getLogBreak1InId()) .",
				log_break1_in = ". Model::safeSql($model->getLogBreak1In()) .",
				log_break1_out_id = ". Model::safeSql($model->getLogBreak1OutId()) .",
				log_break1_out = ". Model::safeSql($model->getLogBreak1Out()) .",
				required_log_break2 = ". Model::safeSql($model->getRequiredLogBreak2()) .",
				log_break2_in_id = ". Model::safeSql($model->getLogBreak2InId()) .",
				log_break2_in = ". Model::safeSql($model->getLogBreak2In()) .",
				log_break2_out_id = ". Model::safeSql($model->getLogBreak2OutId()) .",
				log_break2_out = ". Model::safeSql($model->getLogBreak2Out()) .",
				required_log_break3 = ". Model::safeSql($model->getRequiredLogBreak3()) .",
				log_break3_in_id = ". Model::safeSql($model->getLogBreak3InId()) .",
				log_break3_in = ". Model::safeSql($model->getLogBreak3In()) .",
				log_break3_out_id = ". Model::safeSql($model->getLogBreak3OutId()) .",
				log_break3_out = ". Model::safeSql($model->getLogBreak3Out()) .",
				log_ot_break1_in_id = ". Model::safeSql($model->getLogOtBreak1InId()) .",
				log_ot_break1_in = ". Model::safeSql($model->getLogOtBreak1In()) .",
				log_ot_break1_out_id = ". Model::safeSql($model->getLogOtBreak1OutId()) .",
				log_ot_break1_out = ". Model::safeSql($model->getLogOtBreak1Out()) .",
				log_ot_break2_in_id = ". Model::safeSql($model->getLogOtBreak2InId()) .",
				log_ot_break2_in = ". Model::safeSql($model->getLogOtBreak2In()) .",
				log_ot_break2_out_id = ". Model::safeSql($model->getLogOtBreak2OutId()) .",
				log_ot_break2_out = ". Model::safeSql($model->getLogOtBreak2Out()) .",
				total_break_hrs = ". Model::safeSql($model->getTotalBreakHrs()) .",
				has_early_break_out = ". Model::safeSql($model->getHasEarlyBreakOut()) .",
				total_early_break_out_hrs = ". Model::safeSql($model->getTotalEarlyBreakOutHrs()) .",
				has_late_break_in = ". Model::safeSql($model->getHasLateBreakIn()) .",
				total_late_break_in_hrs = ". Model::safeSql($model->getTotalLateBreakInHrs()) .",
				has_incomplete_break_logs = ". Model::safeSql($model->getHasIncompleteBreakLogs()) .",
				created_at = ". Model::safeSql($model->getCreatedAt()) ."
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
    
	public static function delete(G_Employee_Break_Logs_Summary $model){
		$affected_rows = 0;
		
		if(G_Employee_Break_Logs_Summary_Helper::sqlIsIdExists($model->getId()) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_BREAK_LOGS_SUMMARY ."
				WHERE id =" . Model::safeSql($model->getId());			
			Model::runSql($sql);
			$affected_rows = mysql_affected_rows();
		}	

		return $affected_rows;
	}

    public static function deleteAllAttendanceByDateRange($date_from = '', $date_to = '') {        
        $date_from = date("Y-m-d",strtotime($date_from));
        $date_to   = date("Y-m-d",strtotime($date_to));
        if( strtotime($date_from) <= strtotime($date_to) ){
            $sql = "
                DELETE FROM ". G_EMPLOYEE_BREAK_LOGS_SUMMARY ."
                WHERE attendance_date BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
            ";
            Model::runSql($sql);
            return true;
        }else{
            return false;
        }
    } 
}
?>