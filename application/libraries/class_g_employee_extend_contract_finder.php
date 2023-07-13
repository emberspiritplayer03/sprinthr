<?php
class G_Employee_Extend_Contract_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
			*
			FROM ". G_EMPLOYEE_EXTEND_CONTRACT ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		
		return self::getRecord($sql);
	}
	
	public static function findByCurrentContract($id) {
		$sql = "
			SELECT 
			*
			FROM ". G_EMPLOYEE_EXTEND_CONTRACT ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			AND is_done=0
			LIMIT 1		
		";
		
		return self::getRecord($sql);
	}
	
	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_EXTEND_CONTRACT ." e
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
		
		$e = new G_Employee_Extend_Contract;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setStartDate($row['start_date']);
		$e->setEndDate($row['end_date']);
		$e->setAttachment($row['attachment']);
		$e->setRemarks($row['remarks']);
		$e->setIsDone($row['is_done']);
		return $e;
	}
}
?>