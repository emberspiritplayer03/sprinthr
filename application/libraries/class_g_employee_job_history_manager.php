<?php
class G_Employee_Job_History_Manager {
	public static function save(G_Employee_Job_History $e) {
		if (G_Employee_Job_History_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_JOB_HISTORY . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_JOB_HISTORY . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			job_id				= " . Model::safeSql($e->getJobId()) .",
			name				= " . Model::safeSql($e->getName()) .",
			employment_status	= " . Model::safeSql($e->getEmploymentStatus()) .",
			start_date			= " . Model::safeSql($e->getStartDate()) .",
			end_date			= " . Model::safeSql($e->getEndDate()) ."
			 "
		
			. $sql_end ."	
		
		";	
		//echo $sql;
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function resetEmployeeDefaultJob(G_Employee_Job_History $e){
		$sql = "
			UPDATE ". G_EMPLOYEE_JOB_HISTORY ."
			SET end_date =" . Model::safeSql($e->getEndDate()) . "
			WHERE employee_id =" . Model::safeSql($e->getEmployeeId());
		Model::runSql($sql);
	}

		public static function resetEmployeeByJobHistoryId(G_Employee_Job_History $e,$id){
		$sql = "
			UPDATE ". G_EMPLOYEE_JOB_HISTORY ."
			SET end_date =" . Model::safeSql($e->getEndDate()) . "
			WHERE employee_id =" . Model::safeSql($e->getEmployeeId())  ." AND id = " .$id;
		Model::runSql($sql);


	}

	public static function updateJobHistoryEndDate(G_Employee_Job_History $e,$id,$end_date){
		$sql = "
			UPDATE ". G_EMPLOYEE_JOB_HISTORY ."
			SET end_date =" . Model::safeSql($end_date). "
			WHERE employee_id =" . Model::safeSql($e->getEmployeeId())  ." AND id = " .$id;
		Model::runSql($sql);


	}
		
	public static function delete(G_Employee_Job_History $e){
		if(G_Employee_Job_History_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_JOB_HISTORY ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>