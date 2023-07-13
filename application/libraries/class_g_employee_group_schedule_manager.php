<?php
class G_Employee_Group_Schedule_Manager {
	public static function save(G_Employee_Group_Schedule $gegs) {
		if (G_Employee_Group_Schedule_Helper::isIdExist($gsr) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_GROUP_SCHEDULE . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gegs->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_SETTINGS_REQUIREMENTS . " ";
			$sql_end   = " ";		
		}
		
		$sql = $sql_start ."
			SET
			employee_group_id =" . Model::safeSql($gegs->getEmployeeGroupId()) . ",
			schedule_group_id =" . Model::safeSql($gegs->getScheduleGroupId()) . ",
			schedule_id	  	  =" . Model::safeSql($gegs->getScheduleId()) . ",		
			date_start	  	  =" . Model::safeSql($gegs->getDateStart()) . ",		
			date_end	  	  =" . Model::safeSql($gegs->getDateEnd()) . ",		
			employee_group 	  =" . Model::safeSql($gegs->getEmployeeGroup()) . " "				
			. $sql_end ."	
		
		";		
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Group_Schedule $gegs){
		if (G_Employee_Group_Schedule_Helper::isIdExist($gegs) > 0 ) {
			$sql = "
				DELETE FROM ". G_EMPLOYEE_GROUP_SCHEDULE ."
				WHERE id =" . Model::safeSql($gegs->getId());
			Model::runSql($sql);
		}	
	}
}
?>