<?php
class G_Employee_Attendance_Correction_Request_Manager {
	public static function save(G_Employee_Attendance_Correction_Request $e) {
		if (G_Employee_Attendance_Correction_Request_Helper::isIdExist($e) > 0 ) {
			$action = "update";
			$sql_start = "UPDATE ". G_EMPLOYEE_ATTENDANCE_CORRECTION_REQUEST . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$action = "insert";
			$sql_start = "INSERT INTO ". G_EMPLOYEE_ATTENDANCE_CORRECTION_REQUEST . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id= " . Model::safeSql($e->getCompanyStructureId()) .",
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			date_applied		= " . Model::safeSql($e->getDateApplied()) .",
			date_in				= " . Model::safeSql($e->getDateIn()) .",
			time_in				= " . Model::safeSql($e->getTimeIn()) .",
			time_out			= " . Model::safeSql($e->getTimeOut()) .",
			correct_date_in		= " . Model::safeSql($e->getCorrectDateIn()) .",
			correct_time_in		= " . Model::safeSql($e->getCorrectTimeIn()) .",
			correct_time_out	= " . Model::safeSql($e->getCorrectTimeOut()) .",
			comment				= " . Model::safeSql($e->getComment()) .",
			is_approved			= " . Model::safeSql($e->getIsApproved()) .",
			is_archive			= " . Model::safeSql($e->getIsArchive()) ."
			"
			. $sql_end ."	
		
		";	
	
		Model::runSql($sql);
		if($action == "") {
			return $e->getId();
		} else  {
			return mysql_insert_id();
		}
		
	}
		
	public static function delete(G_Employee_Attendance_Correction_Request $e){
		if(G_Employee_Attendance_Correction_Request_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_ATTENDANCE_CORRECTION_REQUEST ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>