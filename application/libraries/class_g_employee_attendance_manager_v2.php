<?php
class G_Employee_Attendance_Manager_V2 {	
	public static function updateScheduleTemplate($id, $employe_already_assigned) {		
		
		if ($id) {
			$action = 'update';
			$sql_start = "UPDATE ". G_EMPLOYEE_ATTENDANCE_V2;
			$sql_end   = "WHERE id = ". Model::safeSql($id);	
		}
		
		$sql = $sql_start ."
			SET
			date_attendance			=" . Model::safeSql($employe_already_assigned->date) .",
			employee_schedule_id	=" . Model::safeSql($employe_already_assigned->id) .",
			schedule_type  			=" . Model::safeSql($employe_already_assigned->schedule_type) ."
			". $sql_end ."		
		";	
		Model::runSql($sql);
		if ($action == 'insert') {
			return mysql_insert_id();
		} else if ($action == 'update') {
			return true;
		}		
	}

	public static function delete(G_Employee_Attendance_V2 $fp){
		$affected_rows = 0;
		if($fp->getId() > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_ATTENDANCE_V2 ."
				WHERE id =" . Model::safeSql($fp->getId());			
			Model::runSql($sql);
			$affected_rows = mysql_affected_rows();
		}	

		return $affected_rows;
	}
}
?>