<?php
class G_Employee_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT e.id, e.employee_code, e.firstname, e.lastname
			FROM g_employee e
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
		$e = new G_Employee();
		$e->setId($row['id']);
		$e->setEmployeeCode($row['employee_code']);
		$e->setFirstname($row['firstname']);
		$e->setLastname($row['lastname']);
		return $e;
	}
}
?>