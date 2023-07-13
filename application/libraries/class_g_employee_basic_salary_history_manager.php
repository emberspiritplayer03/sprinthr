<?php
class G_Employee_Basic_Salary_History_Manager {
	public static function save(G_Employee_Basic_Salary_History $e) {
		if (G_Employee_Basic_Salary_History_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_BASIC_SALARY_HISTORY . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_BASIC_SALARY_HISTORY . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			job_salary_rate_id	= " . Model::safeSql($e->getJobSalaryRateId()) .",
			basic_salary 		= " . Model::safeSql($e->getBasicSalary()) .",
			pay_period_id		= " . Model::safeSql($e->getPayPeriodId()) .",
			type				= " . Model::safeSql($e->getType()) .",
			frequency_id				= " . Model::safeSql($e->getFrequencyId()) .",
			start_date			= " . Model::safeSql($e->getStartDate()) .",
			end_date			= " . Model::safeSql($e->getEndDate()) ." "
			. $sql_end ."	
		
		";	
		//echo $sql;
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function resetEmployeePresentSalary(G_Employee_Basic_Salary_History $e){
		$sql = "
			UPDATE ". G_EMPLOYEE_BASIC_SALARY_HISTORY ."
			SET end_date =" . Model::safeSql($e->getEndDate()) . "
			WHERE end_date = '' AND employee_id =" . Model::safeSql($e->getEmployeeId());		
		Model::runSql($sql);	
	}
		
	public static function delete(G_Employee_Basic_Salary_History $e){
		if(G_Employee_Basic_Salary_History_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_BASIC_SALARY_HISTORY ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>