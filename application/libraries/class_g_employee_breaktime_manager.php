<?php
class G_Employee_Breaktime_Manager {
	public static function save(G_Employee_Breaktime $glt) {
		if (G_Employee_Breaktime_Helper::isIdExist($glt) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_BREAKTIME . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($glt->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_BREAKTIME . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id =" . Model::safeSql($glt->getEmployeeId()) . ",
			date  		     =" . Model::safeSql($glt->getDate()) . ",
			time_in 	 		 =" . Model::safeSql($glt->getTimeIn()) . ",									
			time_out 	 		 =" . Model::safeSql($glt->getTimeOut()) . ",									
			late_hours 		 =" . Model::safeSql($glt->getLateHours()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Breaktime $glt){
		if(G_Employee_Breaktime_Helper::isIdExist($glt) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_BREAKTIME ."
				WHERE id =" . Model::safeSql($glt->getId());
			Model::runSql($sql);
		}	
	}
}
?>