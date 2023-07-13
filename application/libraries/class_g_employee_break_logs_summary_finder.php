<?php
class G_Employee_Break_logs_Summary_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_BREAK_LOGS_SUMMARY ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return self::getRecord($sql);
	}
	
	public static function findByEmployeeAttendanceId($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_BREAK_LOGS_SUMMARY ." 
			WHERE employee_attendance_id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return self::getRecord($sql);
	}
	
	public static function findByEmployeeAndDate(IEmployee $e, $date) {
		$sql = "
			SELECT *
			FROM ". G_EMPLOYEE_BREAK_LOGS_SUMMARY ."
			WHERE employee_id = ". Model::safeSql($e->getId()) ."
			AND date_attendance = ". Model::safeSql($date) ."
			LIMIT 1		
		";
		return self::getRecord($sql);
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

	private static function getRecord($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}		
		$row = Model::fetchAssoc($result);
		$records = self::newObject($row);

		return $records;
	}
	
	private static function newObject($row) {
		
		$model = new G_Employee_Break_Logs_Summary;
		$model->setId($row['id']);
		$model->setAttendanceDate($row['attendance_date']);
		$model->setEmployeeAttendanceId($row['employee_attendance_id']);
		$model->setEmployeeId($row['employee_id']);
		$model->setScheduleId($row['schedule_id']);
		$model->setRequiredLogBreak1($row['required_log_break1']);
		$model->setLogBreak1InId($row['log_break1_in_id']);
		$model->setLogBreak1In($row['log_break1_in']);
		$model->setLogBreak1OutId($row['log_break1_out_id']);
		$model->setLogBreak1Out($row['log_break1_out']);
		$model->setRequiredLogBreak2($row['required_log_break2']);
		$model->setLogBreak2InId($row['log_break2_in_id']);
		$model->setLogBreak2In($row['log_break2_in']);
		$model->setLogBreak2OutId($row['log_break2_out_id']);
		$model->setLogBreak2Out($row['log_break2_out']);
		$model->setRequiredLogBreak3($row['required_log_break3']);
		$model->setLogBreak3InId($row['log_break3_in_id']);
		$model->setLogBreak3In($row['log_break3_in']);
		$model->setLogBreak3OutId($row['log_break3_out_id']);
		$model->setLogBreak3Out($row['log_break3_out']);
		$model->setLogOtBreak1InId($row['log_ot_break1_in_id']);
		$model->setLogOtBreak1In($row['log_ot_break1_in']);
		$model->setLogOtBreak1OutId($row['log_ot_break1_out_id']);
		$model->setLogOtBreak1Out($row['log_ot_break1_out']);
		$model->setLogOtBreak2InId($row['log_ot_break2_in_id']);
		$model->setLogOtBreak2In($row['log_ot_break2_in']);
		$model->setLogOtBreak2OutId($row['log_ot_break2_out_id']);
		$model->setLogOtBreak2Out($row['log_ot_break2_out']);
		$model->setTotalBreakHrs($row['total_break_hrs']);
		$model->setHasEarlyBreakOut($row['has_early_break_out']);
		$model->setTotalEarlyBreakOutHrs($row['total_early_break_out_hrs']);
		$model->setHasLateBreakIn($row['has_late_break_in']);
		$model->setTotalLateBreakInHrs($row['total_late_break_in_hrs']);
		$model->setCreatedAt($row['created_at']);

        return $model;
	}

}
?>