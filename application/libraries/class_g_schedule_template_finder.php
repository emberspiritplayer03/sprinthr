<?php
class G_Schedule_Template_Finder {

	public static function findById($id) {
		$sql = "
			SELECT s.id, s.schedule_type, s.schedule_name, s.required_working_hours, s.schedule_in, s.schedule_out, s.break_out, s.break_in
			FROM ". V2_SCHEDULE_TEMPLATE ." s
			WHERE s.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}

	public static function findByScheduleName($schedule_name) {
		$sql = "
			SELECT s.id, s.schedule_type, s.schedule_name, s.required_working_hours, s.schedule_in, s.schedule_out, s.break_out, s.break_in
			FROM ". V2_SCHEDULE_TEMPLATE ." s
			WHERE s.schedule_name = ". Model::safeSql($schedule_name) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findAll() {
		$sql = "
			SELECT s.id, s.schedule_type, s.schedule_name, s.required_working_hours, s.schedule_in, s.schedule_out, s.break_out, s.break_in
			FROM ". V2_SCHEDULE_TEMPLATE ." s
			ORDER BY s.schedule_name ASC
		";
		return self::getRecords($sql);
	}

	public static function findByScheduleType($schedule_type) {
		$sql = "
			SELECT s.id, s.schedule_type, s.schedule_name, s.required_working_hours, s.schedule_in, s.schedule_out, s.break_out, s.break_in
			FROM ". V2_SCHEDULE_TEMPLATE ." s
			WHERE s.schedule_type = ". Model::safeSql($schedule_type) ."
		";
		return self::getRecords($sql);
	}

	public static function findDefault($name) {
		$sql = "
			SELECT s.id, s.schedule_type, s.schedule_name, s.required_working_hours, s.schedule_in, s.schedule_out, s.break_out, s.break_in
			FROM ". V2_SCHEDULE_TEMPLATE ." s
			WHERE s.schedule_name = ". Model::safeSql($name) ."
			LIMIT 1
		";
		return self::getRecords($sql);
	}

	public static function findAllToUpdate($name) {
		$sql = "
			SELECT s.id, s.schedule_type, s.schedule_name, s.required_working_hours, s.schedule_in, s.schedule_out, s.break_out, s.break_in
			FROM ". V2_SCHEDULE_TEMPLATE ." s
			WHERE s.schedule_name = ". Model::safeSql($name) ."
			LIMIT 1
		";
		return self::getRecords($sql);
	}
	
	public static function findAllByName($name) {
		$sql = "
			SELECT s.id, s.schedule_type, s.schedule_name, s.required_working_hours, s.schedule_in, s.schedule_out, s.break_out, s.break_in
			FROM ". V2_SCHEDULE_TEMPLATE ." s
			WHERE s.schedule_name = ". Model::safeSql($name) ."
			ORDER BY s.is_default DESC, s.schedule_name ASC
		";
		return self::getRecords($sql);
	}
	
	/*
		$groups = returned value from G_Group_Finder::findAllByEmployee($e);
	*/
	public static function findAllByGroupsAndDate($groups, $date) {
		foreach ($groups as $g) {
			$sched = self::findByGroupAndDate($g, $date);	
			if ($sched) {
				$schedules[$sched->getId()] = $sched;	
			}
		}
		return $schedules;
	}
	
	/*
		$groups = returned value from G_Group_Finder::findAllByEmployee($e);
	*/	
	public static function findByGroupsAndDate($groups, $date) {
		foreach ($groups as $g) {
			$sched = self::findByGroupAndDate($g, $date);	
			if ($sched) {
				return $sched;
			}
		}
		return false;
	}

    public static function findByGroupAndDate(IGroup $g, $date) {
        $day = Tools::getDayFormat($date);
        $s = self::findActiveByGroup($g, $date);
        if ($s) {
            $schedule_type_id = $s->getScheduleGroupId();
            $sql = "
                SELECT s.id, s.schedule_type, s.schedule_name, s.required_working_hours, s.schedule_in, s.schedule_out, s.break_out, s.break_in
                FROM ". V2_EMPLOYEE_SCHEDULE_TYPE ." es, ". V2_SCHEDULE_TEMPLATE ." s
                WHERE s.working_days LIKE '%". $day ."%'
                AND s.schedule_type_id = ". Model::safeSql($schedule_type_id) ."
                AND es.schedule_type_id = s.schedule_type_id
                LIMIT 1
			";
            return self::getRecord($sql);
        }
    }

    public static function findByEmployeeAndDate(IEmployee $e, $date) {


		$sql = "
                SELECT s.id, s.schedule_type, s.schedule_name, s.required_working_hours, s.schedule_in, s.schedule_out, s.break_out, s.break_in
                FROM " . V2_EMPLOYEE_SCHEDULE_TYPE . " es, " . V2_SCHEDULE_TEMPLATE . " s
                WHERE es.date 			= " . Model::safeSql($date) . "
                AND es.employee_id 		= " . Model::safeSql($e->getId()) . "
				AND s.id 				= es.schedule_template_id
                LIMIT 1
            ";
		Utilities::displayArray($sql);
		return self::getRecord($sql);
        
    } 

    public static function findScheduleByEmployeeIdAndDate($id = '', $date_from = '', $date_to = '') {
		if ($date == '') {
			$date = Tools::getGmtDate('Y-m-d');
		}    	

        $day = Tools::getDayFormat($date);
        $s = self::findActiveByEmployeeIdAndDate($id, $date_from, $date_to); 
        //$s = self::findActiveByEmployeeId($id, $date_from);

        if ($s) {

            $schedule_type_id = $s->getScheduleGroupId();
            $sql = "
                SELECT s.id,  s.schedule_name,s.working_days, s.schedule_in, s.schedule_out, es.date_start, es.date_end, s.schedule_type_id
                FROM ". V2_EMPLOYEE_SCHEDULE_TYPE ." es, ". V2_SCHEDULE_TEMPLATE ." s
                WHERE s.working_days LIKE '%". $day ."%'
                AND s.schedule_type_id = ". Model::safeSql($schedule_type_id) ."
                AND es.schedule_type_id = s.schedule_type_id
                LIMIT 1
            ";
            return self::getRecord($sql);
        }
    }

	public static function findActiveByEmployeeIdAndDate($id, $date_from = '', $date_to = '') {
		if ($date == '') {
			$date = Tools::getGmtDate('Y-m-d');
		}

		$sql = "
			SELECT s.id,  s.schedule_name,s.working_days, s.schedule_in, s.schedule_out, es.date_start, es.date_end, s.schedule_type_id
			FROM ". V2_EMPLOYEE_SCHEDULE_TYPE ." es, ". V2_SCHEDULE_TEMPLATE ." s
			WHERE es.schedule_type_id = s.schedule_type_id
			AND ((". Model::safeSql($date_from) ." >= es.date_start AND (es.date_end = '0000-00-00' OR es.date_end = '')) OR (". Model::safeSql($date_from) ." >= es.date_start AND ". Model::safeSql($date_to) ." <= es.date_end))
			AND es.employee_group_id = ". Model::safeSql($id) ."
			AND es.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
			AND es.date_start = 
				(
					SELECT es2.date_start 
					FROM ". V2_EMPLOYEE_SCHEDULE_TYPE ." es2, ". V2_SCHEDULE_TEMPLATE ." s2
					WHERE es2.date_start <= ". Model::safeSql($date_from) ."
					AND es2.schedule_type_id = s2.schedule_type_id
					AND es2.employee_group_id = ". Model::safeSql($id) ."
					AND es2.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
					ORDER BY es2.date_start DESC, es2.schedule_type_id DESC
					LIMIT 1
				)
			ORDER BY es.date_start DESC, es.schedule_type_id DESC
			LIMIT 1
	
		";

		return self::getRecord($sql);
	}       
	
	/*
		$sg - Instance of G_Schedule_Group class
	*/
	public static function findByScheduleGroupAndTimeInAndOut($sg, $schedule_in, $schedule_out) {
		$sql = "
			SELECT s.id,  s.schedule_name, s.working_days, s.schedule_in, s.schedule_out, s.schedule_type_id
			FROM ". V2_SCHEDULE_TEMPLATE ." s
			WHERE s.schedule_type_id = ". Model::safeSql($sg->getId()) ."
			AND s.schedule_in = ". Model::safeSql($schedule_in) ."
			AND s.schedule_out = ". Model::safeSql($schedule_out) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}

    /*
    *   $sg = G_Schedule_Group
    */

	public static function findAllByScheduleGroup($sg) {
		$sql = "
			SELECT s.id, s.schedule_type, s.schedule_name, s.required_working_hours, s.schedule_in, s.schedule_out, s.break_out, s.break_in
			FROM ". V2_SCHEDULE_TEMPLATE ." s
			WHERE s.schedule_type_id = ". Model::safeSql($sg->getId()) ."
		";
		return self::getRecords($sql);
	}
	
	public static function findActiveByEmployee(IEmployee $e, $date = '') {
		if ($date == '') {
			$date = Tools::getGmtDate('Y-m-d');
		}
		$sql = "
			SELECT s.id,  s.schedule_name,s.working_days, s.schedule_in, s.schedule_out, es.date_start, es.date_end, s.schedule_type_id
			FROM ". V2_EMPLOYEE_SCHEDULE_TYPE ." es, ". V2_SCHEDULE_TEMPLATE ." s
			WHERE es.schedule_type_id = s.schedule_type_id
			AND ((". Model::safeSql($date) ." >= es.date_start AND (es.date_end = '0000-00-00' OR es.date_end = '')) OR (". Model::safeSql($date) ." >= es.date_start AND ". Model::safeSql($date) ." <= es.date_end))
			AND es.employee_id = ". Model::safeSql($e->getId()) ."
			ORDER BY es.date_start DESC, es.schedule_type_id DESC
			LIMIT 1
	
		";

		return self::getRecord($sql);
	}

	/*
		Find the active schedule regardless if the employee has work or no work on the $date given.
		If $date is not supplied, it gets the current date.
	*/
	public static function findActiveByEmployeeId($id, $date = '') {
		if ($date == '') {
			$date = Tools::getGmtDate('Y-m-d');
		}
		$sql = "
			SELECT s.id,  s.schedule_name,s.working_days, s.schedule_in, s.schedule_out, es.date_start, es.date_end, s.schedule_type_id
			FROM ". V2_EMPLOYEE_SCHEDULE_TYPE ." es, ". V2_SCHEDULE_TEMPLATE ." s
			WHERE es.schedule_type_id = s.schedule_type_id
			AND ((". Model::safeSql($date) ." >= es.date_start AND (es.date_end = '0000-00-00' OR es.date_end = '')) OR (". Model::safeSql($date) ." >= es.date_start AND ". Model::safeSql($date) ." <= es.date_end))
			AND es.employee_id = ". Model::safeSql($id) ."
			ORDER BY es.date_start DESC, es.schedule_type_id DESC
			LIMIT 1
	
		";
		return self::getRecord($sql);
	}
	
	public static function findActiveByGroups($groups) {
		foreach ($groups as $g) {
			$sched = self::findActiveByGroup($g);	
			if ($sched) {
				return $sched;
			}
		}
		return false;
	}
	
	/*
		Find the active schedule regardless if the employee has work or no work on the $date given.
		If $date is not supplied, it gets the current date.
	*/
	public static function findActiveByGroup(IGroup $g, $date = '') {
		if ($date == '') {
			$date = Tools::getGmtDate('Y-m-d');
		}
		$sql = "
			SELECT s.id,  s.schedule_name, s.working_days, s.schedule_in, s.schedule_out, es.date_start, es.date_end, s.schedule_type_id
			FROM ". V2_EMPLOYEE_SCHEDULE_TYPE ." es, ". V2_SCHEDULE_TEMPLATE ." s
			WHERE es.schedule_type_id = s.schedule_type_id
			AND ((". Model::safeSql($date) ." >= es.date_start AND (es.date_end = '0000-00-00' OR es.date_end = '')) OR (". Model::safeSql($date) ." >= es.date_start AND ". Model::safeSql($date) ." <= es.date_end))
			AND es.employee_id = ". Model::safeSql($g->getId()) ."
			AND es.date_start = 
				(
					SELECT es2.date_start 
					FROM ". V2_EMPLOYEE_SCHEDULE_TYPE ." es2, ". V2_SCHEDULE_TEMPLATE ." s2
					WHERE es2.date_start <= ". Model::safeSql($date) ."
					AND es2.schedule_type_id = s2.schedule_type_id
					AND es2.employee_id = ". Model::safeSql($g->getId()) ."
					ORDER BY es2.date_start DESC, es2.schedule_type_id DESC
					LIMIT 1
				)
			ORDER BY es.date_start DESC, es.schedule_type_id DESC
			LIMIT 1
	
		";
		return self::getRecord($sql);
	}
	
	private static function getRecord($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}		
		$row = Model::fetchAssoc($result);
		$records = self::newObject($row);	
		return $records;
	}
	
	private static function getRecords($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}
		while ($row = Model::fetchAssoc($result)) {
			$records[$row['id']] = self::newObject($row);
		}
		return $records;
	}
	
	private static function newObject($row) {
		$s = new G_Schedule_Template;
		$s->setId($row['id']);
		$s->setScheduleType($row['schedule_type']);
		$s->setName($row['schedule_name']);
		$s->setRequiredWorkingHours($row['required_working_hours']);
		$s->setScheduleIn($row['schedule_in']);
		$s->setScheduleOut($row['schedule_out']);
		$s->setBreakIn($row['break_in']);
		$s->setBreakOut($row['break_out']);
		return $s;
	}
}
?>