<?php
class G_Schedule_Manager {	
	public static function save(G_Schedule $s) {
		$public_id = uniqid();		
		if ($s->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". G_SCHEDULE;
			$sql_end   = " WHERE id = ". Model::safeSql($s->getId());		
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_SCHEDULE;
			$sql_end   = ",public_id = ". Model::safeSql($public_id);
		}
		
		$sql = $sql_start ."
			SET
			schedule_name  =" . Model::safeSql($s->getName()) .",
			grace_period	=" . Model::safeSql($s->getGracePeriod()) . ",		
			working_days	=" . Model::safeSql($s->getWorkingDays()) .",
			time_in 			=" . Model::safeSql($s->getTimeIn()) .",
			time_out 		=" . Model::safeSql($s->getTimeOut()) ."
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
	
	/*
		$sg - Instance of G_Schedule_Group class
		$s - Instance of G_Schedule class
	*/
	public static function saveToScheduleGroup($sg, $s) {
		$sql = "
			UPDATE ". G_SCHEDULE ." SET
			schedule_group_id = " . Model::safeSql($sg->getId()) ."
			WHERE id = ". Model::safeSql($s->getId()) ."
		";	
		Model::runSql($sql);
		if (mysql_errno() > 0) {
			return false;
		}
		return true;
	}
	
	public static function assignToEmployee(IEmployee $e, G_Schedule $s, $start_date, $end_date) {		
		$sql = "
			INSERT INTO ". G_EMPLOYEE_GROUP_SCHEDULE ." (employee_group_id, schedule_id, date_start, date_end, employee_group)
			VALUES (
				". Model::safeSql($e->getId()) .",
				". Model::safeSql($s->getId()) .",
				". Model::safeSql($start_date) .",
				". Model::safeSql($end_date) .",
				". Model::safeSql(ENTITY_EMPLOYEE) ."				
			)
		";
		
		Model::runSql($sql);
		return mysql_insert_id();
	}

	public static function assignToEmployeeWithGroup(IEmployee $e, G_Schedule $s, $start_date, $end_date) {		
		$sql = "
			INSERT INTO ". G_EMPLOYEE_GROUP_SCHEDULE ." (employee_group_id, schedule_group_id, schedule_id, date_start, date_end, employee_group)
			VALUES (
				". Model::safeSql($e->getId()) .",
                ". Model::safeSql($s->getScheduleGroupId()) .",
				". Model::safeSql($s->getId()) .",
				". Model::safeSql($start_date) .",
				". Model::safeSql($end_date) .",
				". Model::safeSql(ENTITY_EMPLOYEE) ."				
			)
		";
		
		Model::runSql($sql);
		return mysql_insert_id();
	}
	
	public static function assignToGroup(IGroup $g, G_Schedule $s, $start_date, $end_date) {		
		$sql = "
			INSERT INTO ". G_EMPLOYEE_GROUP_SCHEDULE ." (employee_group_id, schedule_id, date_start, date_end, employee_group)
			VALUES (
				". Model::safeSql($g->getId()) .",
				". Model::safeSql($s->getId()) .",
				". Model::safeSql($start_date) .",
				". Model::safeSql($end_date) .",
				". Model::safeSql(ENTITY_GROUP) ."				
			)
		";
		Model::runSql($sql);
		return mysql_insert_id();
	}	
	
	public static function setDefaultSchedule(G_Schedule $s) {
		$sql = "
			UPDATE ". G_SCHEDULE ."
			SET is_default = 1 
			WHERE id = ". Model::safeSql($s->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}
	
	public static function removeEmployee(IEmployee $e, G_Schedule $s) {
		$sql = "
			DELETE FROM ". G_EMPLOYEE_GROUP_SCHEDULE ."
			WHERE employee_group_id = ". Model::safeSql($e->getId()) ."
			AND schedule_id = ". Model::safeSql($s->getId()) ."
			AND employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}
	
	public static function removeGroup(IGroup $g, G_Schedule $s) {
		$sql = "
			DELETE FROM ". G_EMPLOYEE_GROUP_SCHEDULE ."
			WHERE employee_group_id = ". Model::safeSql($g->getId()) ."
			AND schedule_id = ". Model::safeSql($s->getId()) ."
			AND employee_group = ". Model::safeSql(ENTITY_GROUP) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}
	
	public static function deleteSchedule(G_Schedule $s) {
		$sql = "
			DELETE FROM ". G_SCHEDULE ."
			WHERE id = ". Model::safeSql($s->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}
}
?>