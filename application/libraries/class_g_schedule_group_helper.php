<?php
class G_Schedule_Group_Helper {
	public static function countByNameAndEffectivityDate($name, $date) {
		$sql = "
			SELECT COUNT(*) AS total
			FROM ". G_SCHEDULE_GROUP ." g
			WHERE g.schedule_name = ". Model::safeSql($name) ."
            AND g.effectivity_date = ". Model::safeSql($date) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

    /*
    *   Get all schedule groups of an employee its either assigned to him/her or to his/her group
    *
    *   Returns array of G_Schedule_Group class
    */
    public static function getAllScheduleGroupsByEmployee($e) {
        $schedules = G_Schedule_Group_Finder::findAllByEmployee($e);
        $g = G_Group_Finder::findLatestByEmployee($e);
        if ($g) {
            $schedules2 = G_Schedule_Group_Finder::findAllByGroup($g);
        }

        if (is_array($schedules) && is_array($schedules2)) {
            return array_merge($schedules, $schedules2);
        } else if (is_array($schedules)) {
            return $schedules;
        } else if (is_array($schedules2)) {
            return $schedules2;
        } else {
            return false;
        }
    }

    /*
    *   Get schedule groups of an employee its either assigned to him/her or to his/her group by month and year
    *
    *   Returns array of G_Schedule_Group class
    */
    public static function getAllScheduleGroupsByEmployeeAndMonthAndYear($e, $month, $year) {
        $schedules = G_Schedule_Group_Finder::findAllByEmployeeAndMonthAndYear($e, $month, $year);
        $g = G_Group_Finder::findLatestByEmployee($e);

        if ($g) {
            $schedules2 = G_Schedule_Group_Finder::findAllByGroupAndMonthAndYear($g, $month, $year);
        }  

        if (is_array($schedules) && is_array($schedules2)) {
            return array_merge($schedules, $schedules2);
        } else if (is_array($schedules)) {
            return $schedules;
        } else if (is_array($schedules2)) {
            return $schedules2;
        } else {
            return false;
        }
    }

	/*
		$s - Instance of G_Schedule_Group class
	*/
	public static function isGroupAlreadyAssigned(IGroup $g, $s) {
		$sql = "
			SELECT COUNT(*) as total
			FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." s
			WHERE s.employee_group_id = ". Model::safeSql($g->getId()) ."
			AND s.schedule_group_id = ". Model::safeSql($s->getId()) ."
			AND s.employee_group = ". Model::safeSql(ENTITY_GROUP) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return ($row['total'] > 0) ? true : false;
	}
	
	/*
		$s - Instance of G_Schedule_Group class
	*/	
	public static function isEmployeeAlreadyAssigned(IEmployee $e, $s) {
		$sql = "
			SELECT COUNT(*) as total
			FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." s
			WHERE s.employee_group_id = ". Model::safeSql($e->getId()) ."
			AND s.schedule_group_id = ". Model::safeSql($s->getId()) ."
			AND s.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return ($row['total'] > 0) ? true : false;
	}
	
	public static function countMembers(G_Schedule $s) {
		$sql = "
			SELECT COUNT(*) as total
			FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." s
			WHERE s.schedule_group_id = ". Model::safeSql($s->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	/*
		$sg - Instance of G_Schedule_Group class
	*/
	public static function updateEmployeeStartAndEndDate($sg, $date_start, $date_end) {
		$employees = G_Employee_Finder::findByScheduleGroup($sg);	
		foreach ($employees as $e) {
			$sg->assignToEmployee($e, $date_start, $date_end);
		}
	}
	
	/*
		
		
		$sg - Instance of G_Schedule_Group class
	*/
//	public static function countByEmployeeAndScheduleGroup($e, $sg) {
//		$sql = "
//			SELECT COUNT(*) AS total
//			FROM ". G_EMPLOYEE_GROUP_SCHEDULE ."
//			WHERE employee_group_id = ". Model::safeSql($e->getId()) ."
//			AND employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
//			AND schedule_group_id = ". Model::safeSql($sg->getId()) ."
//		";
//		$result = Model::runSql($sql);
//		$row = Model::fetchAssoc($result);
//		return $row['total'];
//	}

	public static function getEmployeeWithNoSchedule() {
		$sql = "
            SELECT CONCAT(e.lastname , ', ' , e.firstname) as employee_name, esh.name as department_name
            FROM " . EMPLOYEE . " e	
            LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id 	
           	    WHERE e.department_company_structure_id NOT IN (
            		SELECT employee_group_id
					FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE_GROUP ." g
					WHERE es.schedule_group_id = g.id
					AND es.employee_group = ". Model::safeSql(ENTITY_GROUP) ."
		            AND g.end_date >= NOW()
                ) AND e.id NOT IN (
            		SELECT employee_group_id
					FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE_GROUP ." g
					WHERE es.schedule_group_id = g.id
					AND es.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
		            AND g.end_date >= NOW() )
        ";

        $result = Model::runSql($sql,true);
		return $result;
    }

	public static function countEmployeeWithNoSchedule() {
		$sql = "
            SELECT COUNT(e.id) as total
            FROM " . EMPLOYEE . " e
           	    WHERE e.department_company_structure_id NOT IN (
            		SELECT employee_group_id
					FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE_GROUP ." g
					WHERE es.schedule_group_id = g.id
					AND es.employee_group = ". Model::safeSql(ENTITY_GROUP) ."
		            AND g.end_date >= NOW()
                ) AND e.id NOT IN (
            		SELECT employee_group_id
					FROM ". G_EMPLOYEE_GROUP_SCHEDULE ." es, ". G_SCHEDULE_GROUP ." g
					WHERE es.schedule_group_id = g.id
					AND es.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
		            AND g.end_date >= NOW() )
		        AND e.employee_status_id = 1
        ";

        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
    }

}
?>