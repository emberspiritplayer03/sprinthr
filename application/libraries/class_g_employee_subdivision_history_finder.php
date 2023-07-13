<?php
class G_Employee_Subdivision_History_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
				*

			FROM ". G_EMPLOYEE_SUBDIVISION_HISTORY ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findAllCurrentEmployeesByCompanyStructureId($company_structure_id) {
		$sql = "
			SELECT 
			*
			FROM ". G_EMPLOYEE_SUBDIVISION_HISTORY ." e
			WHERE e.company_structure_id = ". Model::safeSql($company_structure_id) ."	
			AND e.end_date=''			
		";
		return self::getRecords($sql);	
	}
	
	
	public static function findCurrentSubdivision(G_Employee $e) {
		$sql = "
			SELECT 
			*
			FROM ". G_EMPLOYEE_SUBDIVISION_HISTORY ." e
			WHERE e.employee_id = ". Model::safeSql($e->getId()) ."	
			AND end_date=''
			LIMIT 1		
		";

		return self::getRecord($sql);	
	}

	//reset to active
	public static function findCurrentSubdivision2(G_Employee $e) {
		$sql = "
			SELECT 
			*
			FROM ". G_EMPLOYEE_SUBDIVISION_HISTORY ." e
			WHERE e.employee_id = ". Model::safeSql($e->getId()) ."	
			ORDER BY e.id DESC
			LIMIT 1		
		";

		return self::getRecord($sql);	
	}
	
	public static function findRecentHistoryByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_SUBDIVISION_HISTORY ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."	
			ORDER BY start_date desc
			LIMIT 1
		";

		return self::getRecord($sql);
	}
	
	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_SUBDIVISION_HISTORY ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."	
			ORDER BY start_date desc
		";

		return self::getRecords($sql);
	}
		public static function findPresentByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_SUBDIVISION_HISTORY ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."	
			ORDER BY start_date desc
			LIMIT 1
		";

		return self::getRecord($sql);
	}
	
	public static function findEmployeeCurrentDepartment($employee_id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_SUBDIVISION_HISTORY ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ." AND
			type = '" . G_Employee_Subdivision_History::DEPARTMENT . "' AND
			end_date = ''
			ORDER BY start_date desc
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByCompanyStructureId($company_structure_id) {
		$sql = "
			SELECT *
			FROM " . G_EMPLOYEE_SUBDIVISION_HISTORY ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function searchEmployeeByCompanyStructureId($query,$conditional_statment = "") {
		$sql = "
			SELECT e.id,s.company_structure_id, CONCAT(e.firstname, ' ' , e.lastname) as name
			FROM " . G_EMPLOYEE_SUBDIVISION_HISTORY . " s
			LEFT JOIN g_employee e
			ON s.employee_id = e.id
			WHERE (e.firstname LIKE '%{$query}%' OR e.lastname LIKE '%{$query}%') 
			$conditional_statment
			GROUP BY name
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
		
		$e = new G_Employee_Subdivision_History;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setCompanyStructureId($row['company_structure_id']);
		$e->setName($row['name']);
		$e->setType($row['type']);
		$e->setStartDate($row['start_date']);
		$e->setEndDate($row['end_date']);
		return $e;
	}
}
?>