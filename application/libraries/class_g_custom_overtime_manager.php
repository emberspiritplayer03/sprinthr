<?php
class G_Custom_Overtime_Manager {
	public static function save(G_Custom_Overtime $co) {
		if (G_Custom_Overtime_Helper::isIdExist($co) > 0 ) {
			$sql_start = "UPDATE ". G_CUSTOM_OVERTIME . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($co->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_CUSTOM_OVERTIME . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id =" . Model::safeSql($co->getEmployeeId()) . ",
			date        =" . Model::safeSql($co->getDate()) . ",
			start_time  =" . Model::safeSql($co->getStartTime()) . ",		
			end_time 	=" . Model::safeSql($co->getEndTime()) . ",					
			status      =" . Model::safeSql($co->getStatus()) . ",					
			day_type 	=" . Model::safeSql($co->getDayType()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Custom_Overtime $co){
		if(G_Custom_Overtime_Helper::isIdExist($co) > 0){
			$sql = "
				DELETE FROM ". G_CUSTOM_OVERTIME ."
				WHERE id =" . Model::safeSql($co->getId());
			Model::runSql($sql);
		}	
	}
}
?>