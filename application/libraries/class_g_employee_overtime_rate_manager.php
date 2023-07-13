<?php
class G_Employee_Overtime_Rate_Manager {
	public static function save(G_Employee_Overtime_Rate $or) {
		if (G_Employee_Overtime_Rate_Helper::isIdExist($or) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_OVERTIME_RATES . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($or->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_OVERTIME_RATES . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id =" . Model::safeSql($or->getEmployeeId()) . ",									
			ot_rate 		 	 =" . Model::safeSql($or->getOtRate()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Overtime_Rate $or){
		if(G_Employee_Overtime_Rate_Helper::isIdExist($or) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_OVERTIME_RATES ."
				WHERE id =" . Model::safeSql($or->getId());
			Model::runSql($sql);
		}	
	}
}
?>