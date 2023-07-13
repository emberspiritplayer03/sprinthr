<?php
class G_Group_Finder {
    public static function findAllDepartments() {
        $sql = "
			SELECT cs.id, cs.title, cs.description, cs.type, cs.parent_id
			FROM ". COMPANY_STRUCTURE ." cs
			WHERE cs.type = ". Model::safeSql(G_Group::TYPE_DEPARTMENT) ."
		";
        return self::getRecords($sql);
    }

	public static function findRecentByEmployee(IEmployee $e) {
		$sql = "
			SELECT cs.id, cs.title, cs.description, cs.type, cs.parent_id
			FROM ". G_EMPLOYEE_SUBDIVISION_HISTORY ." g, ". COMPANY_STRUCTURE ." cs
			WHERE employee_id = ". Model::safeSql($e->getId()) ."
			AND g.company_structure_id = cs.id
			ORDER BY g.start_date DESC, id DESC
			LIMIT 1
		";
		return self::getRecord($sql);
	}

    public static function findByName($name) {
		$sql = "
			SELECT cs.id, cs.title, cs.description, cs.type, cs.parent_id
			FROM g_company_structure cs
			WHERE cs.title = ". Model::safeSql($name) ."
			LIMIT 1
		";
		return self::getRecord($sql);
    }

	public static function searchByGroupName($query) {
		$sql = "
			SELECT cs.id, cs.title, cs.description, cs.type, cs.parent_id
			FROM g_company_structure cs
			WHERE (cs.title LIKE '%{$query}%')
		";		
		return self::getRecords($sql);
	}
		
	public static function findById($id) {
		$sql = "
			SELECT cs.id, cs.title, cs.description, cs.type, cs.parent_id
			FROM g_company_structure cs
			WHERE cs.id = ". Model::safeSql($id) ."
			LIMIT 1
		";		
		return self::getRecord($sql);
	}
	
	public static function findAll() {
		$sql = "
			SELECT cs.id, cs.title, cs.description, cs.type, cs.parent_id
			FROM g_company_structure cs
			ORDER BY cs.id
		";		
		return self::getRecords($sql);	
	}	
	
	public static function findByEmployee(IEmployee $e) {
		$sql = "
			SELECT cs.id, cs.title, cs.description, cs.type, cs.parent_id
			FROM g_employee e, g_company_structure cs
			WHERE e.id = ". Model::safeSql($e->getId()) ."
			AND e.company_structure_id = cs.id
			LIMIT 1
		";
		return self::getRecord($sql);	
	}
    /*
     * Find the latest and recent added group to an employee
     * @return Instance of G_Group
     */
    public static function findLatestByEmployee(IEmployee $e) {
        $sql = "
			SELECT cs.id, cs.title, cs.description, cs.type, cs.parent_id
			FROM ". G_EMPLOYEE_SUBDIVISION_HISTORY ." g, ". COMPANY_STRUCTURE ." cs
			WHERE g.employee_id = ". Model::safeSql($e->getId()) ."
			AND g.company_structure_id = cs.id
			AND (cs.is_archive = 'No' OR cs.is_archive = '')
			ORDER BY g.start_date DESC, g.id DESC
			LIMIT 1
		";
        return self::getRecord($sql);
    }
	
	public static function findAllByEmployee(IEmployee $e) {
		$sql = "
			SELECT cs.id, cs.title, cs.description, cs.type, cs.parent_id
			FROM ". G_EMPLOYEE_SUBDIVISION_HISTORY ." g, ". COMPANY_STRUCTURE ." cs
			WHERE g.employee_id = ". Model::safeSql($e->getId()) ."
			AND g.company_structure_id = cs.id
			ORDER BY g.start_date DESC, id DESC
		";
		return self::getRecords($sql);	
	}	
	
	public static function findBySchedule(G_Schedule $s) {
		$sql = "
			SELECT cs.id, cs.title, cs.description, cs.type, cs.parent_id
			FROM g_company_structure cs, ". G_EMPLOYEE_GROUP_SCHEDULE ." s
			WHERE cs.id = s.employee_group_id
			AND s.employee_group = ". Model::safeSql(ENTITY_GROUP) ."
			AND s.schedule_id = ". Model::safeSql($s->getId()) ."
			ORDER BY cs.title
		";
		return self::getRecords($sql);
	}
	
	/*
		$sg - Instance of G_Schedule_Group class
	*/
	public static function findByScheduleGroup($sg) {
		$sql = "
			SELECT cs.id, cs.title, cs.description, cs.type, cs.parent_id
			FROM g_company_structure cs, ". G_EMPLOYEE_GROUP_SCHEDULE ." s
			WHERE cs.id = s.employee_group_id
			AND s.employee_group = ". Model::safeSql(ENTITY_GROUP) ."
			AND s.schedule_group_id = ". Model::safeSql($sg->getId()) ."
			ORDER BY cs.title
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
		$s = new G_Group;
		$s->setId($row['id']);
		$s->setName($row['title']);
		$s->setDescription($row['description']);
        $s->setType($row['type']);
        $s->setParentId($row['parent_id']);
		return $s;
	}
}
?>