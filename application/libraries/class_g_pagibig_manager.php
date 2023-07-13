<?php
class G_Pagibig_Manager {	
	public static function saveToEmployee(IEmployee $e, G_Pagibig $s) {		
		if ($s->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE g_employee_contribution";
			$sql_end   = " WHERE employee_id = ". Model::safeSql($e->getId());		
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO g_employee_contribution";
			$sql_end   = ",employee_id = ". Model::safeSql($e->getId());
		}
		
		$sql = $sql_start ."
			SET
			pagibig_ee   = " . Model::safeSql($s->getEmployeeShare()) .",
			pagibig_er	 = " . Model::safeSql($s->getCompanyShare()) ."
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
}
?>