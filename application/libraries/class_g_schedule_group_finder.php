<?php
class G_Schedule_Group_Finder {

	public static function findAllByMonthAndYearWithDefault($month, $year) {
		$sql = "
			SELECT g.id, g.public_id, g.schedule_name,g.grace_period, g.is_default, g.effectivity_date, g.end_date
			FROM ". G_SCHEDULE_GROUP ." g
            WHERE (MONTH(g.effectivity_date) = ". Model::safeSql($month) ."
            AND YEAR(g.effectivity_date) = ". Model::safeSql($year) .")
            OR g.is_default = ". YES ."
			ORDER BY g.is_default DESC, g.effectivity_date DESC
		";
		return self::getRecords($sql);
	}

	public static function findAllByDate($date) {
		$sql = "
			SELECT g.id, g.public_id, g.schedule_name,g.grace_period, g.is_default, g.effectivity_date, g.end_date
			FROM ". G_SCHEDULE_GROUP ." g
            WHERE (g.effectivity_date >= " . Model::safeSql($date) . " AND g.end_date <= " . Model::safeSql($date) . ")
			ORDER BY g.is_default DESC, g.effectivity_date DESC
		";
		return self::getRecords($sql);
	}

    public static function findAllByEmployee($e) {
        $sql = "
            SELECT g.id, g.public_id, g.schedule_name,g.grace_period, g.is_default, g.effectivity_date
			FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE_GROUP ." g
			WHERE es.schedule_group_id = g.id
			AND es.employee_group_id = ". Model::safeSql($e->getId()) ."
			AND es.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
            ORDER BY es.date_start DESC, es.schedule_group_id DESC
        ";
        return self::getRecords($sql);
    }

    public static function findAllByEmployeeAndMonthAndYear($e, $month, $year) {
        $sql = "
            SELECT g.id, g.public_id, g.schedule_name,g.grace_period, g.is_default, g.effectivity_date
			FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE_GROUP ." g
			WHERE es.schedule_group_id = g.id
			AND es.employee_group_id = ". Model::safeSql($e->getId()) ."
			AND es.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
            AND MONTH(g.effectivity_date) = ". Model::safeSql($month) ."
            AND YEAR(g.effectivity_date) = ". Model::safeSql($year) ."
            ORDER BY es.date_start DESC, es.schedule_group_id DESC
        ";        
        return self::getRecords($sql);
    }

    /*
    *   $g = G_Group
    */
    public static function findAllByGroupAndMonthAndYear($g, $month, $year) {
        $sql = "
            SELECT g.id, g.public_id, g.schedule_name,g.grace_period, g.is_default, g.effectivity_date
			FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE_GROUP ." g
			WHERE es.schedule_group_id = g.id
			AND es.employee_group_id = ". Model::safeSql($g->getId()) ."
			AND es.employee_group = ". Model::safeSql(ENTITY_GROUP) ."
            AND MONTH(g.effectivity_date) = ". Model::safeSql($month) ."
            AND YEAR(g.effectivity_date) = ". Model::safeSql($year) ."
            ORDER BY es.date_start DESC, es.schedule_group_id DESC
        ";
        return self::getRecords($sql);
    }

    /*
    *   $g = G_Group
    */
    public static function findAllByGroup($g) {
        $sql = "
            SELECT g.id, g.public_id, g.schedule_name,g.grace_period, g.is_default, g.effectivity_date
			FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE_GROUP ." g
			WHERE es.schedule_group_id = g.id
			AND es.employee_group_id = ". Model::safeSql($g->getId()) ."
			AND es.employee_group = ". Model::safeSql(ENTITY_GROUP) ."
            ORDER BY es.date_start DESC, es.schedule_group_id DESC
        ";
        return self::getRecords($sql);
    }

    public static function findByEmployeeAndDateStartEnd($e, $date) {
        $sql = "
            SELECT g.id, g.public_id, g.schedule_name,g.grace_period, g.is_default, g.effectivity_date
			FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE_GROUP ." g
			WHERE es.schedule_group_id = g.id
			AND es.employee_group_id = ". Model::safeSql($e->getId()) ."
			AND es.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
            AND ". Model::safeSql($date) ." >= g.effectivity_date
            AND ". Model::safeSql($date) ." <= g.end_date
            ORDER BY es.date_start DESC, es.schedule_group_id DESC
		";        
        return self::getRecords($sql);
    }    
	
	public static function findById($id) {
		$sql = "
			SELECT *
			FROM ". G_SCHEDULE_GROUP ." g
			WHERE g.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";		
		return self::getRecord($sql);
	}
	
	public static function findByPublicId($id) {
		$sql = "
			SELECT g.id, g.public_id, g.schedule_name,g.grace_period, g.is_default, g.effectivity_date, g.end_date
			FROM ". G_SCHEDULE_GROUP ." g
			WHERE g.public_id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findAll() {
		$sql = "
			SELECT g.id, g.public_id, g.schedule_name,g.grace_period, g.is_default, g.effectivity_date
			FROM ". G_SCHEDULE_GROUP ." g
			ORDER BY g.is_default DESC, g.effectivity_date DESC
		";
		return self::getRecords($sql);
	}

	public static function findAllSchedule() {
		$sql = "
			SELECT g.id, g.public_id, g.schedule_name,g.grace_period, g.is_default, g.effectivity_date, g.end_date
			FROM ". G_SCHEDULE_GROUP ." g
			ORDER BY g.is_default DESC, g.effectivity_date DESC
		";
		return self::getRecords($sql);
	}
	
	public static function findByName($name) {
		$sql = "
			SELECT g.id, g.public_id, g.schedule_name,g.grace_period, g.is_default, g.effectivity_date
			FROM ". G_SCHEDULE_GROUP ." g
			WHERE g.schedule_name = ". Model::safeSql($name) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}

	public static function findByNameAndEffectivityDate($name, $date) {
		$sql = "
			SELECT g.id, g.public_id, g.schedule_name,g.grace_period, g.is_default, g.effectivity_date
			FROM ". G_SCHEDULE_GROUP ." g
			WHERE g.schedule_name = ". Model::safeSql($name) ."
            AND g.effectivity_date = ". Model::safeSql($date) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}

	/**
	* Get all schedules in between dates
	*
    *@param string date | null $strat_date   
    *@return array
    */
    public static function findAllInBetweenDate($start_date, $end_date) {
        $sql = "
            SELECT g.id, g.public_id, g.schedule_name,g.grace_period, g.is_default, g.effectivity_date
			FROM " . G_SCHEDULE_GROUP ." g
			WHERE g.effectivity_date >=" . Model::safeSql($start_date) . " AND g.end_date <=" . Model::safeSql($end_date) . "			
            ORDER BY g.effectivity_date ASC
        ";        
        return self::getRecords($sql);
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
		$e = new G_Schedule_Group();
		$e->setId($row['id']);
		$e->setName($row['schedule_name']);
		$e->setGracePeriod($row['grace_period']);
		$e->setPublicId($row['public_id']);
		$e->setEffectivityDate($row['effectivity_date']);
		$e->setEndDate($row['end_date']);
        $schedules = G_Schedule_Finder::findAllByScheduleGroup($e);
        $e->setSchedules($schedules);
		if ($row['is_default']) {
			$e->setAsDefault();
		}
		return $e;
	}
}
?>