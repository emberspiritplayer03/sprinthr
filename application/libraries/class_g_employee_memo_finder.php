<?php
class G_Employee_Memo_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
			*
			FROM ". G_EMPLOYEE_MEMO ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		
		return self::getRecord($sql);
	}
	
	public static function findByMemoId($memo_id) {
		$sql = "
			SELECT 
			*	
			FROM ". G_EMPLOYEE_MEMO ." e
			WHERE e.memo_id = ". Model::safeSql($memo_id) ."	
	
		";

		return self::getRecords($sql);
	}	
	
	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT 
			*	
			FROM ". G_EMPLOYEE_MEMO ." e
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
		
		$e = new G_Employee_Memo;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setMemoId($row['memo_id']);
		$e->setTitle($row['title']);
		$e->setMemo($row['memo']);
		$e->setAttachment($row['attachment']);
		$e->setDateOfOffense($row['date_of_offense']);
		$e->setOffenseDescription($row['offense_description']);
		$e->setRemarks($row['remarks']);
		$e->setDateCreated($row['date_created']);
		$e->setCreatedBy($row['created_by']);

		return $e;
	}
}
?>