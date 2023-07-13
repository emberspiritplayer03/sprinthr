<?php
class G_Employee_Contribution_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_CONTRIBUTION ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findCurrentContribution(IEmployee $e) {
		$sql = "
			SELECT 
			*

			FROM ". G_EMPLOYEE_CONTRIBUTION ." e
			WHERE e.employee_id = ". Model::safeSql($e->getId()) ."	

			LIMIT 1		
		";
		return self::getRecord($sql);	
	}
	
	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_CONTRIBUTION ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."	
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
		
		$e = new G_Employee_Contribution;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setSssEe($row['sss_ee']);
		$e->setPagibigEe($row['pagibig_ee']);
		$e->setPhilhealthEe($row['philhealth_ee']);
		$e->setSssEr($row['sss_er']);
		$e->setPagibigEr($row['pagibig_er']);
		$e->setPhilhealthEr($row['philhealth_er']);
		$e->setToDeduct($row['to_deduct']);
		return $e;
	}
}
?>