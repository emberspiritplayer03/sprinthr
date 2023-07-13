<?php
class G_Schedule_Group_Manager {
	/*
		$s - Instance of G_Schedule_Group class
	*/
	public static function save($s) {
		$public_id = uniqid();
		if ($s->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE ". G_SCHEDULE_GROUP;
			$sql_end   = " WHERE id = ". Model::safeSql($s->getId());		
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_SCHEDULE_GROUP;
			$sql_end   = ",public_id = ". Model::safeSql($public_id);
		}
		
		$sql = $sql_start ."
			SET
			schedule_name    = ". Model::safeSql($s->getName()) .",
			grace_period     = ". Model::safeSql($s->getGracePeriod()) . ",
			end_date         = ". Model::safeSql($s->getEndDate()) . ",
			effectivity_date = ". Model::safeSql($s->getEffectivityDate()) ."
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
    *   Assigns this schedule group to multiple employees
    *
    *   $es = array of G_Employee
    *   $sg = instance of G_Schedule_Group class
    */
    public static function assignToMultipleEmployees($es, $sg) {
        $has_record = false;
        $effectivity_date = $sg->getEffectivityDate();
        foreach ($es as $e) {
            $insert_sql_values[] = "
                (". Model::safeSql($e->getId()) .",
                ". Model::safeSql(ENTITY_EMPLOYEE) .",
                ". Model::safeSql($effectivity_date) .",
                ". Model::safeSql($sg->getId()) .")";
            $has_record = true;
        }

        if ($has_record) {
            $insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". G_EMPLOYEE_GROUP_SCHEDULE ." (employee_group_id, employee_group, date_start, schedule_group_id)
                VALUES ". $insert_sql_value ."
            ";
            Model::runSql($sql_insert);
        }

        if (mysql_errno() > 0) {
            //echo mysql_error();
            return false;
        } else {
            return true;
        }
    }
	
	/*
		Variables
		$sg - Instance of G_Schedule_Group class
	*/
	public static function assignToEmployee(IEmployee $e, $sg, $start_date, $end_date) {
		$is_assigned = G_Schedule_Group_Helper::isEmployeeAlreadyAssigned($e, $sg);
		
		if ($is_assigned) {
			$action = 'update';
			$sql_start = "UPDATE ". G_EMPLOYEE_GROUP_SCHEDULE;
			$sql_end   = " WHERE employee_group_id = ". Model::safeSql($e->getId()) ."
				AND employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
				AND schedule_group_id = ". Model::safeSql($sg->getId()) ."
			";	
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_EMPLOYEE_GROUP_SCHEDULE;
			$sql_end   = ",employee_group_id = ". Model::safeSql($e->getId()). ",
				employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) .",
				schedule_group_id = ". Model::safeSql($sg->getId()) ."
			";
		}
		
		$sql = $sql_start ."
			SET
			date_start = ". Model::safeSql($start_date) .",
			date_end = ". Model::safeSql($end_date) ."
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
				
//		$sql = "
//			INSERT INTO ". G_EMPLOYEE_GROUP_SCHEDULE ." (employee_group_id, schedule_group_id, date_start, date_end, employee_group)
//			VALUES (
//				". Model::safeSql($e->getId()) .",
//				". Model::safeSql($sg->getId()) .",
//				". Model::safeSql($start_date) .",
//				". Model::safeSql($end_date) .",
//				". Model::safeSql(ENTITY_EMPLOYEE) ."				
//			)
//		";
//		Model::runSql($sql);
//		if (mysql_errno() > 0) {
//			return false;
//		}		
//		return mysql_insert_id();
	}
	
	public static function assignToGroup(IGroup $g, $sg, $start_date, $end_date) {		
		$sql = "
			INSERT INTO ". G_EMPLOYEE_GROUP_SCHEDULE ." (employee_group_id, schedule_group_id, date_start, date_end, employee_group)
			VALUES (
				". Model::safeSql($g->getId()) .",
				". Model::safeSql($sg->getId()) .",
				". Model::safeSql($start_date) .",
				". Model::safeSql($end_date) .",
				". Model::safeSql(ENTITY_GROUP) ."				
			)
		";
		
		Model::runSql($sql);
		return mysql_insert_id();
	}
	
	/*
		Variables
		$sg - Instance of G_Schedule_Group class
	*/
	public static function removeEmployee(IEmployee $e, $sg) {
		$sql = "
			DELETE FROM ". G_EMPLOYEE_GROUP_SCHEDULE ."
			WHERE employee_group_id = ". Model::safeSql($e->getId()) ."
			AND schedule_group_id = ". Model::safeSql($sg->getId()) ."
			AND employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
		";
		Model::runSql($sql);
		//return (mysql_affected_rows() >= 1) ? true : false ;
		if (mysql_errno() > 0) {
			return false;
		} else {
		    return true;
		}
	}
	
	/*
		Variables
		$sg - Instance of G_Schedule_Group class
	*/	
	public static function removeGroup(IGroup $g, $sg) {
		$sql = "
			DELETE FROM ". G_EMPLOYEE_GROUP_SCHEDULE ."
			WHERE employee_group_id = ". Model::safeSql($g->getId()) ."
			AND schedule_group_id = ". Model::safeSql($sg->getId()) ."
			AND employee_group = ". Model::safeSql(ENTITY_GROUP) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}
	
	/*
		Variables
		$sg - Instance of G_Schedule_Group class
	*/		
	public static function delete($sg) {
		$sql = "
			DELETE FROM ". G_SCHEDULE_GROUP ."
			WHERE id = ". Model::safeSql($sg->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}
	
	public static function setDefaultGroup($sg) {
		$sql = "
			UPDATE ". G_SCHEDULE_GROUP ."
			SET is_default = 1 
			WHERE id = ". Model::safeSql($sg->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}		
}
?>