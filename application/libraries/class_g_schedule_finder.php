<?php
class G_Schedule_Finder {
    public static function findAllByEmployee($e) {
        $sql = "
            SELECT s.id, s.public_id, s.schedule_name,s.grace_period, s.working_days, s.time_in, s.time_out, es.date_start, es.date_end, s.is_default, s.schedule_group_id
			FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE ." s
			WHERE es.schedule_group_id = s.schedule_group_id
			AND es.employee_group_id = ". Model::safeSql($e->getId()) ."
			AND es.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
            ORDER BY es.date_start DESC, es.schedule_group_id DESC
        ";
        return self::getRecords($sql);
    }
	/*
		Find the active schedule regardless if the employee has work or no work on the $date given.
		If $date is not supplied, it gets the current date.
	*/
	public static function findActiveByEmployee(IEmployee $e, $date = '') {
		if ($date == '') {
			$date = Tools::getGmtDate('Y-m-d');
		}
		$sql = "
			SELECT s.id, s.public_id, s.schedule_name,s.grace_period, s.working_days, s.time_in, s.time_out, es.date_start, es.date_end, s.is_default, s.schedule_group_id
			FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE ." s
			WHERE es.schedule_group_id = s.schedule_group_id
			AND ((". Model::safeSql($date) ." >= es.date_start AND (es.date_end = '0000-00-00' OR es.date_end = '')) OR (". Model::safeSql($date) ." >= es.date_start AND ". Model::safeSql($date) ." <= es.date_end))
			AND es.employee_group_id = ". Model::safeSql($e->getId()) ."
			AND es.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
			AND es.date_start = 
				(
					SELECT es2.date_start 
					FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es2, ". G_SCHEDULE ." s2
					WHERE es2.date_start <= ". Model::safeSql($date) ."
					AND es2.schedule_group_id = s2.schedule_group_id
					AND es2.employee_group_id = ". Model::safeSql($e->getId()) ."
					AND es2.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
					ORDER BY es2.date_start DESC, es2.schedule_group_id DESC
					LIMIT 1
				)
			ORDER BY es.date_start DESC, es.schedule_group_id DESC
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
			SELECT s.id, s.public_id, s.schedule_name,s.grace_period, s.working_days, s.time_in, s.time_out, es.date_start, es.date_end, s.is_default, s.schedule_group_id
			FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE ." s
			WHERE es.schedule_group_id = s.schedule_group_id
			AND ((". Model::safeSql($date) ." >= es.date_start AND (es.date_end = '0000-00-00' OR es.date_end = '')) OR (". Model::safeSql($date) ." >= es.date_start AND ". Model::safeSql($date) ." <= es.date_end))
			AND es.employee_group_id = ". Model::safeSql($id) ."
			AND es.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
			AND es.date_start = 
				(
					SELECT es2.date_start 
					FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es2, ". G_SCHEDULE ." s2
					WHERE es2.date_start <= ". Model::safeSql($date) ."
					AND es2.schedule_group_id = s2.schedule_group_id
					AND es2.employee_group_id = ". Model::safeSql($id) ."
					AND es2.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
					ORDER BY es2.date_start DESC, es2.schedule_group_id DESC
					LIMIT 1
				)
			ORDER BY es.date_start DESC, es.schedule_group_id DESC
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
			SELECT s.id, s.public_id, s.schedule_name, s.grace_period, s.working_days, s.time_in, s.time_out, es.date_start, es.date_end, s.is_default, s.schedule_group_id
			FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE ." s
			WHERE es.schedule_group_id = s.schedule_group_id
			AND ((". Model::safeSql($date) ." >= es.date_start AND (es.date_end = '0000-00-00' OR es.date_end = '')) OR (". Model::safeSql($date) ." >= es.date_start AND ". Model::safeSql($date) ." <= es.date_end))
			AND es.employee_group_id = ". Model::safeSql($g->getId()) ."
			AND es.employee_group = ". Model::safeSql(ENTITY_GROUP) ."
			AND es.date_start = 
				(
					SELECT es2.date_start 
					FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es2, ". G_SCHEDULE ." s2
					WHERE es2.date_start <= ". Model::safeSql($date) ."
					AND es2.schedule_group_id = s2.schedule_group_id
					AND es2.employee_group_id = ". Model::safeSql($g->getId()) ."
					AND es2.employee_group = ". Model::safeSql(ENTITY_GROUP) ."
					ORDER BY es2.date_start DESC, es2.schedule_group_id DESC
					LIMIT 1
				)
			ORDER BY es.date_start DESC, es.schedule_group_id DESC
			LIMIT 1
	
		";
		return self::getRecord($sql);
	}	

	public static function findById($id) {
		$sql = "
			SELECT s.id, s.public_id, s.schedule_name, s.working_days, s.time_in, s.time_out, s.is_default, s.schedule_group_id
			FROM ". G_SCHEDULE ." s
			WHERE s.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}

	public static function findByScheduleName($schedule_name) {
		$sql = "
			SELECT s.id, s.public_id, s.schedule_name, s.working_days, s.time_in, s.time_out, s.is_default, s.schedule_group_id
			FROM ". G_SCHEDULE ." s
			WHERE s.schedule_name = ". Model::safeSql($schedule_name) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByPublicId($id) {
		$sql = "
			SELECT s.id, s.public_id, s.schedule_name, s.working_days, s.time_in, s.time_out, s.is_default, s.schedule_group_id
			FROM ". G_SCHEDULE ." s
			WHERE s.public_id = ". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	
	
	public static function findAll() {
		$sql = "
			SELECT s.id, s.public_id, s.schedule_name, s.working_days, s.time_in, s.time_out, s.is_default, s.schedule_group_id
			FROM ". G_SCHEDULE ." s
			ORDER BY s.is_default DESC, s.schedule_name ASC
		";
		return self::getRecords($sql);
	}
	
	public static function findAllByName($name) {
		$sql = "
			SELECT s.id, s.public_id, s.schedule_name, s.working_days, s.time_in, s.time_out, s.is_default, s.schedule_group_id
			FROM ". G_SCHEDULE ." s
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
	/*
	public static function findByGroupAndDate(IGroup $g, $date) {
		$day = Tools::getDayFormat($date);
		$sql = "
			SELECT s.id, s.public_id, s.schedule_name, s.working_days, s.time_in, s.time_out, es.date_start, es.date_end, s.is_default, s.schedule_group_id
			FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE ." s
			WHERE s.working_days LIKE '%". $day ."%'
			AND es.schedule_group_id = s.schedule_group_id
			AND ((". Model::safeSql($date) ." >= es.date_start AND (es.date_end = '0000-00-00' OR es.date_end = '')) OR (". Model::safeSql($date) ." >= es.date_start AND ". Model::safeSql($date) ." <= es.date_end))
			AND es.employee_group_id = ". Model::safeSql($g->getId()) ."
			AND es.employee_group = ". Model::safeSql(ENTITY_GROUP) ."
			AND es.date_start = 
				(
					SELECT es2.date_start
					FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es2, ". G_SCHEDULE ." s2
					WHERE es2.date_start <= ". Model::safeSql($date) ."
					AND es2.schedule_group_id = s2.schedule_group_id
					AND es2.employee_group_id = ". Model::safeSql($g->getId()) ."
					AND es2.employee_group = ". Model::safeSql(ENTITY_GROUP) ."
					ORDER BY es2.date_start DESC
					LIMIT 1
				)
			ORDER BY es.date_start DESC
			LIMIT 1
		";
		return self::getRecord($sql);
	}*/

    public static function findByGroupAndDate(IGroup $g, $date) {
        $day = Tools::getDayFormat($date);
        $s = self::findActiveByGroup($g, $date);
        if ($s) {
            $schedule_group_id = $s->getScheduleGroupId();
            $sql = "
                SELECT s.id, s.public_id, s.schedule_name,s.grace_period, s.working_days, s.time_in, s.time_out, es.date_start, es.date_end, s.is_default, s.schedule_group_id
                FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE ." s
                WHERE s.working_days LIKE '%". $day ."%'
                AND s.schedule_group_id = ". Model::safeSql($schedule_group_id) ."
                AND es.schedule_group_id = s.schedule_group_id
                LIMIT 1
			";
            return self::getRecord($sql);
        }
    }

    public static function findByEmployeeAndDate(IEmployee $e, $date) {
        $day = Tools::getDayFormat($date);
        $s = self::findActiveByEmployee($e, $date);
        if ($s) {
            $schedule_group_id = $s->getScheduleGroupId();
            $sql = "
                SELECT s.id, s.public_id, s.schedule_name,s.grace_period, s.working_days, s.time_in, s.time_out, es.date_start, es.date_end, s.is_default, s.schedule_group_id
                FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE ." s
                WHERE s.working_days LIKE '%". $day ."%'
                AND s.schedule_group_id = ". Model::safeSql($schedule_group_id) ."
                AND es.schedule_group_id = s.schedule_group_id
                LIMIT 1
            ";
            
            return self::getRecord($sql);
        }
    } 

    public static function findScheduleByEmployeeIdAndDate($id = '', $date_from = '', $date_to = '') {
		if ($date == '') {
			$date = Tools::getGmtDate('Y-m-d');
		}    	

        $day = Tools::getDayFormat($date);
        $s = self::findActiveByEmployeeIdAndDate($id, $date_from, $date_to); 
        //$s = self::findActiveByEmployeeId($id, $date_from);

        if ($s) {

            $schedule_group_id = $s->getScheduleGroupId();
            $sql = "
                SELECT s.id, s.public_id, s.schedule_name,s.grace_period, s.working_days, s.time_in, s.time_out, es.date_start, es.date_end, s.is_default, s.schedule_group_id
                FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE ." s
                WHERE s.working_days LIKE '%". $day ."%'
                AND s.schedule_group_id = ". Model::safeSql($schedule_group_id) ."
                AND es.schedule_group_id = s.schedule_group_id
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
			SELECT s.id, s.public_id, s.schedule_name,s.grace_period, s.working_days, s.time_in, s.time_out, es.date_start, es.date_end, s.is_default, s.schedule_group_id
			FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE ." s
			WHERE es.schedule_group_id = s.schedule_group_id
			AND ((". Model::safeSql($date_from) ." >= es.date_start AND (es.date_end = '0000-00-00' OR es.date_end = '')) OR (". Model::safeSql($date_from) ." >= es.date_start AND ". Model::safeSql($date_to) ." <= es.date_end))
			AND es.employee_group_id = ". Model::safeSql($id) ."
			AND es.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
			AND es.date_start = 
				(
					SELECT es2.date_start 
					FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es2, ". G_SCHEDULE ." s2
					WHERE es2.date_start <= ". Model::safeSql($date_from) ."
					AND es2.schedule_group_id = s2.schedule_group_id
					AND es2.employee_group_id = ". Model::safeSql($id) ."
					AND es2.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
					ORDER BY es2.date_start DESC, es2.schedule_group_id DESC
					LIMIT 1
				)
			ORDER BY es.date_start DESC, es.schedule_group_id DESC
			LIMIT 1
	
		";

		return self::getRecord($sql);
	}       

    /*
	public static function findByEmployeeAndDate(IEmployee $e, $date) {
		$day = Tools::getDayFormat($date);
		$sql = "
			SELECT s.id, s.public_id, s.schedule_name,s.grace_period, s.working_days, s.time_in, s.time_out, es.date_start, es.date_end, s.is_default, s.schedule_group_id
			FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE ." s
			WHERE s.working_days LIKE '%". $day ."%'
			AND es.schedule_group_id = s.schedule_group_id
			AND ((". Model::safeSql($date) ." >= es.date_start AND (es.date_end = '0000-00-00' OR es.date_end = '')) OR (". Model::safeSql($date) ." >= es.date_start AND ". Model::safeSql($date) ." <= es.date_end))
			AND es.employee_group_id = ". Model::safeSql($e->getId()) ."
			AND es.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
			AND es.date_start = 
				(
					SELECT es2.date_start
					FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es2, ". G_SCHEDULE ." s2
					WHERE es2.date_start <= ". Model::safeSql($date) ."
					AND es2.schedule_group_id = s2.schedule_group_id
					AND es2.employee_group_id = ". Model::safeSql($e->getId()) ."
					AND es2.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
					ORDER BY es2.date_start DESC, es2.schedule_group_id DESC
					LIMIT 1
				)
			ORDER BY es.date_start DESC, es.schedule_group_id DESC
			LIMIT 1
		";		
		return self::getRecord($sql);
	}*/
	
	public static function findDefaultByDate($date) {
		$day = Tools::getDayFormat($date);
		$sql = "
			SELECT s.id, s.public_id, s.schedule_name,s.grace_period, s.working_days, s.time_in, s.time_out, s.is_default, s.schedule_group_id
			FROM ". G_SCHEDULE ." s, ". G_SCHEDULE_GROUP ." g
			WHERE s.working_days LIKE '%". $day ."%'
			AND g.id = s.schedule_group_id
			AND g.is_default = ". YES ."
			LIMIT 1
		";		
		return self::getRecord($sql);
	}
	
	/*
		$sg - Instance of G_Schedule_Group class
	*/
	public static function findByScheduleGroupAndTimeInAndOut($sg, $time_in, $time_out) {
		$sql = "
			SELECT s.id, s.public_id, s.schedule_name, s.working_days, s.time_in, s.time_out, s.is_default, s.schedule_group_id
			FROM ". G_SCHEDULE ." s
			WHERE s.schedule_group_id = ". Model::safeSql($sg->getId()) ."
			AND s.time_in = ". Model::safeSql($time_in) ."
			AND s.time_out = ". Model::safeSql($time_out) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}

    /*
    *   $sg = G_Schedule_Group
    */
	public static function findAllByScheduleGroup($sg) {
		$sql = "
			SELECT s.id, s.public_id, s.schedule_name, s.working_days, s.time_in, s.time_out, s.is_default, s.schedule_group_id
			FROM ". G_SCHEDULE ." s
			WHERE s.schedule_group_id = ". Model::safeSql($sg->getId()) ."
		";
		return self::getRecords($sql);
	}
	
	public static function findAllByScheduleGroupId($id) {
		$sql = "
			SELECT s.id, s.public_id, s.schedule_name, s.working_days, s.time_in, s.time_out, s.is_default, s.schedule_group_id
			FROM ". G_SCHEDULE ." s
			WHERE s.schedule_group_id = ". Model::safeSql($id) ."
		";
		return self::getRecords($sql);
	}
	
	public static function findAllDefault() {
		$sql = "
			SELECT s.id, s.public_id, s.schedule_name, s.working_days, s.time_in, s.time_out, s.is_default, s.schedule_group_id
			FROM ". G_SCHEDULE ." s, ". G_SCHEDULE_GROUP ." g
			WHERE s.schedule_group_id = g.id
			AND g.is_default = ". Model::safeSql(YES) ."
		";
		return self::getRecords($sql);
	}	

	public static function getWorkingDaysByGroupId($group_id, $name) {
		$sql = "
			SELECT s.id, s.public_id, s.schedule_name, s.working_days, s.time_in, s.time_out, s.is_default, s.schedule_group_id
			FROM ". G_SCHEDULE ." s
			WHERE s.schedule_name = ". Model::safeSql($name) ."
			AND s.schedule_group_id = ". Model::safeSql($group_id) ."
			ORDER BY s.id ASC
		";
		return self::getRecord($sql);
	}

	public static function getNumWorkingDaysByGroupId($group_id, $name) {
		$sql = "
			SELECT s.id, s.public_id, s.schedule_name, s.working_days, s.time_in, s.time_out, s.is_default, s.schedule_group_id
			FROM ". G_SCHEDULE ." s
			WHERE s.schedule_name = ". Model::safeSql($name) ."
			AND s.schedule_group_id = ". Model::safeSql($group_id) ."
		";
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		return $total;
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
		$s = new G_Schedule;
		$s->setId($row['id']);
		$s->setPublicId($row['public_id']);
		$s->setScheduleGroupId($row['schedule_group_id']);
		$s->setName($row['schedule_name']);
		$s->setWorkingDays($row['working_days']);
		$s->setTimeIn($row['time_in']);
		$s->setTimeOut($row['time_out']);
		$s->setDateStart($row['date_start']);
		$s->setDateEnd($row['date_end']);
		if ($row['is_default']) {
			$s->setAsDefault();	
		}
		return $s;
	}
}
?>