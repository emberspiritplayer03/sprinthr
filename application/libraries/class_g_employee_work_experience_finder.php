<?php
class G_Employee_Work_Experience_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_WORK_EXPERIENCE ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		
		return self::getRecord($sql);
	}
	
	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				*

			FROM ". G_EMPLOYEE_WORK_EXPERIENCE ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."	
	
		";

		return self::getRecords($sql);
	}
	
	public static function findPreviousEmployerByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_WORK_EXPERIENCE ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."
			ORDER BY to_date DESC
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
		
		$e = new G_Employee_Work_Experience;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setCompany($row['company']);
		$e->setAddress($row['address']);
		$e->setJobTitle($row['job_title']);
		$e->setFromDate($row['from_date']);
		$e->setToDate($row['to_date']);
		$e->setComment($row['comment']);
		

		return $e;
	}
}
?>