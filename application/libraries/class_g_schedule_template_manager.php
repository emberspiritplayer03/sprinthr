<?php
class G_Schedule_Template_Manager {	
	public static function save(G_Schedule_Template $s) {	
		if ($s->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". V2_SCHEDULE_TEMPLATE;
			$sql_end   = "WHERE id = ". Model::safeSql($s->getId());
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". V2_SCHEDULE_TEMPLATE;
		}
		
		$sql = $sql_start ."
			SET
			schedule_type				=" . Model::safeSql($s->getScheduleType()) .",
			schedule_name  				=" . Model::safeSql($s->getName()) .",
			required_working_hours		=" . Model::safeSql($s->getRequiredWorkingHours()) .",
			schedule_in 				=" . Model::safeSql($s->getScheduleIn()) .",
			schedule_out				=" . Model::safeSql($s->getScheduleOut()) .",
			break_out 					=" . Model::safeSql($s->getBreakOut()) .",
			break_in 					=" . Model::safeSql($s->getBreakIn()) ."
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

	public static function saveShiftAm(G_Schedule_Template $s) {	
		if ($s->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". V2_SCHEDULE_TEMPLATE;
			$sql_end   = "WHERE id = ". Model::safeSql($s->getId());
			//$sql_start_schedule_type = "UPDATE ". V2_SCHEDULE_TYPE;
			//$sql_end_schedule_type   = " WHERE id = ". Model::safeSql($s->getScheduleTypeId());		
		} else {
			/*$action = 'insert';
			$sql_start = "INSERT INTO ". V2_SCHEDULE_TEMPLATE;
			$sql_end   = ",public_id = ". Model::safeSql($public_id);*/
		}
		
		$sql = $sql_start ."
			SET
			start_date 			=" . Model::safeSql($s->getScheduleIn()) .",
			end_date 			=" . Model::safeSql($s->getScheduleOut()) ."
			". $sql_end ."		
		";	

		/*$sql_schedule_type = $sql_start_schedule_type ."
			SET
			compressed_required_hours 		=" . Model::safeSql($s->getHours()) ."
			". $sql_end_schedule_type ."		
		";*/

		Model::runSql($sql);
		//Model::runSql($sql_schedule_type);
		if (mysql_errno() > 0) {
			return false;
		}
		if ($action == 'insert') {
			return mysql_insert_id();
		} else if ($action == 'update') {
			return true;
		}			
	}

	public static function saveCompress(G_Schedule_Template $s) {	
		if ($s->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". V2_SCHEDULE_TEMPLATE;
			$sql_end   = "WHERE id = ". Model::safeSql($s->getId());
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". V2_SCHEDULE_TEMPLATE;
		}
		
		$sql = $sql_start ."
			SET
			schedule_type				=" . Model::safeSql($s->getScheduleType()) .",
			schedule_name  				=" . Model::safeSql($s->getName()) .",
			required_working_hours		=" . Model::safeSql($s->getRequiredWorkingHours()) .",
			schedule_in 				=" . Model::safeSql($s->getScheduleIn()) .",
			schedule_out				=" . Model::safeSql($s->getScheduleOut()) .",
			break_out 					=" . Model::safeSql($s->getBreakOut()) .",
			break_in 					=" . Model::safeSql($s->getBreakIn()) ."
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

	public static function saveStaggered(G_Schedule_Template $s) {
		if ($s->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". V2_SCHEDULE_TEMPLATE;
			$sql_end   = "WHERE id = ". Model::safeSql($s->getId());
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". V2_SCHEDULE_TEMPLATE;
		}
		
		$sql = $sql_start ."
			SET
			schedule_type				=" . Model::safeSql($s->getScheduleType()) .",
			schedule_name  				=" . Model::safeSql($s->getName()) .",
			required_working_hours		=" . Model::safeSql($s->getRequiredWorkingHours()) .",
			schedule_in 				=" . Model::safeSql($s->getScheduleIn()) .",
			schedule_out				=" . Model::safeSql($s->getScheduleOut()) .",
			break_out 					=" . Model::safeSql($s->getBreakOut()) .",
			break_in 					=" . Model::safeSql($s->getBreakIn()) ."
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
	
	public static function saveFlexible(G_Schedule_Template $s) {	
		if ($s->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". V2_SCHEDULE_TEMPLATE;
			$sql_end   = "WHERE id = ". Model::safeSql($s->getId());
			$sql_start_schedule_type = "UPDATE ". V2_SCHEDULE_TYPE;
			$sql_end_schedule_type   = " WHERE id = ". Model::safeSql($s->getScheduleTypeId());		
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". V2_SCHEDULE_TEMPLATE;
		}
		
		$sql = $sql_start ."
			SET
			schedule_type_id	=" . Model::safeSql($s->getScheduleTypeId()) .",
			schedule_name  		=" . Model::safeSql($s->getName()) .",
			working_days		=" . Model::safeSql($s->getWorkingDays()) .",
			schedule_in 		=" . Model::safeSql($s->getScheduleIn()) .",
			schedule_out		=" . Model::safeSql($s->getScheduleOut()) .",
			start_date 			=" . Model::safeSql($s->getStartDate()) .",
			end_date 			=" . Model::safeSql($s->getEndDate()) ."
			". $sql_end ."		
		";	

		$sql_schedule_type = $sql_start_schedule_type ."
			SET
			flexible_required_hours 		=" . Model::safeSql($s->getHours()) ."
			". $sql_end_schedule_type ."		
		";	

		Model::runSql($sql);
		Model::runSql($sql_schedule_type);
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
		$s - Instance of V2_SCHEDULE_TEMPLATE class
	*/
	public static function saveToScheduleGroup($sg, $s) {
		$sql = "
			UPDATE ". V2_SCHEDULE_TEMPLATE ." SET
			schedule_type_id = " . Model::safeSql($sg->getId()) ."
			WHERE id = ". Model::safeSql($s->getId()) ."
		";	
		Model::runSql($sql);
		if (mysql_errno() > 0) {
			return false;
		}
		return true;
	}

	public static function assignToEmployee(IEmployee $e, G_Schedule_Template $s, $employe_already_assigned, $date) {		
		
		if ($employe_already_assigned) {
			$action = 'update';
			$sql_start = "UPDATE ". V2_EMPLOYEE_SCHEDULE_TYPE;
			$sql_end   = "WHERE id = ". Model::safeSql($employe_already_assigned->id);	
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". V2_EMPLOYEE_SCHEDULE_TYPE;
		}
		
		$sql = $sql_start ."
			SET
			date					=" . Model::safeSql($date) .",
			employee_id				=" . Model::safeSql($e->getId()) .",
			schedule_template_id	=" . Model::safeSql($s->getId()) .",
			schedule_type  			=" . Model::safeSql($s->getScheduleType()) ."
			". $sql_end ."		
		";	
		Model::runSql($sql);
		if ($action == 'insert') {
			return mysql_insert_id();
		} else if ($action == 'update') {
			return true;
		}		
	}
	/* Old assignToEmployee - multiple schedule to
	public static function assignToEmployee(IEmployee $e, G_Schedule_Template $s, $start_date, $end_date, $schedule_type_id) {		
		$sql = "
			INSERT INTO ". V2_EMPLOYEE_SCHEDULE_TYPE ." (employee_id, schedule_type_id, schedule_id)
			VALUES (
				". Model::safeSql($e->getId()) .",
				". Model::safeSql($schedule_type_id) .",
				". Model::safeSql($s->getId()) ."
			)
		";
		Model::runSql($sql);
		return mysql_insert_id();
	}*/

	public static function assignToEmployeeWithGroup(IEmployee $e, G_Schedule_Template $s) {		
		$sql = "
			INSERT INTO ". V2_EMPLOYEE_SCHEDULE_TYPE ." (employee_id, schedule_type_id, schedule_id)
			VALUES (
				". Model::safeSql($e->getId()) .",
				". Model::safeSql($schedule_type_id) .",
				". Model::safeSql($s->getId()) ."
			)
		";
		
		Model::runSql($sql);
		return mysql_insert_id();
	}
	
	public static function assignToGroup(IGroup $g, G_Schedule_Template $s, $start_date, $end_date) {		
		$sql = "
			INSERT INTO ". V2_EMPLOYEE_SCHEDULE_TYPE ." (employee_id, schedule_type_id, schedule_id)
			VALUES (
				". Model::safeSql($e->getId()) .",
				". Model::safeSql($schedule_type_id) .",
				". Model::safeSql($s->getId()) ."
			)
		";
		
		Model::runSql($sql);
		return mysql_insert_id();
	}	
	
	public static function setDefaultSchedule(G_Schedule_Template $s) {
		$sql = "
			UPDATE ". V2_EMPLOYEE_SCHEDULE_TYPE ."
			SET is_default = 1 
			WHERE id = ". Model::safeSql($s->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}
	
	public static function removeEmployee(IEmployee $e, G_Schedule_Template $s) {
		$sql = "
			DELETE FROM ". V2_EMPLOYEE_SCHEDULE_TYPE ."
			WHERE employee_id = ". Model::safeSql($e->getId()) ."
			AND schedule_id = ". Model::safeSql($s->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}

	public static function removeAllEmployee(G_Schedule_Template $s) {
		$sql = "
			DELETE FROM ". V2_EMPLOYEE_SCHEDULE_TYPE ."
			WHERE schedule_id = ". Model::safeSql($s->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}

	public static function removeEmployeeInEmployeeList($e) {
		$sql = "
			DELETE FROM ". V2_EMPLOYEE_SCHEDULE_TYPE ."
			WHERE employee_id = ". Model::safeSql($e) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}
	
	public static function removeGroup(IGroup $g, G_Schedule_Template $s) {
		$sql = "
			DELETE FROM ". V2_EMPLOYEE_SCHEDULE_TYPE ."
			WHERE employee_id = ". Model::safeSql($g->getId()) ."
			AND schedule_id = ". Model::safeSql($s->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}
	
	public static function deleteSchedule($id) {
		$sql = "
			DELETE FROM ". V2_SCHEDULE_TEMPLATE ."
			WHERE id = ". Model::safeSql($id) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}
}
?>