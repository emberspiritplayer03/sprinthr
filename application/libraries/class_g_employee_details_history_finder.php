<?php
class G_Employee_Details_History_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT *
			FROM ". G_EMPLOYEE_DETAILS_HISTORY ." e
			WHERE e.id = ". Model::safeSql($id) ."	
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
		//print_r($records);
		//echo $records->getId();
	
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
		$e = new G_Employee_Details_History;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setEmployeeCode($row['employee_code']);
		$e->setModifiedBy($row['modified_by']);
		$e->setRemarks($row['remarks']);
		$e->setHistoryDate($row['history_date']);
		$e->setDateModified($row['date_modified']);
		$e->setIsArchive($row['is_archive']);
		return $e;
	}
	
	
}
?>