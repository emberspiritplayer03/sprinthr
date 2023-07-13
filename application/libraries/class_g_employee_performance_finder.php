<?php
class G_Employee_Performance_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_PERFORMANCE ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_PERFORMANCE ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."	
	
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
		
		$e = new G_Employee_Performance;
		$e->setId($row['id']);
		$e->setCompanyStructureId($row['company_structure_id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setPerformanceId($row['performance_id']);
		$e->setPerformanceTitle($row['performance_title']);
		$e->setReviewerId($row['reviewer_id']);
		$e->setCreatedBy($row['created_by']);
		$e->setCreatedDate($row['created_date']);
		$e->setPosition($row['position']);
		$e->setPeriodFrom($row['period_from']);
		$e->setPeriodTo($row['period_to']);
		$e->setDueDate($row['due_date']);
		$e->setSummary($row['summary']);
		$e->setStatus($row['status']);
		$e->setKpi($row['kpi']);
		return $e;
	}
}
?>