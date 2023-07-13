<?php
class G_Employee_Dependent_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
				e.id, 
				e.employee_id, 
				e.name,
				e.relationship, 
				e.birthdate

			FROM ". G_EMPLOYEE_DEPENDENT ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		
		return self::getRecord($sql);
	}
	
	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				e.id, 
				e.employee_id, 
				e.name,
				e.relationship, 
				e.birthdate

			FROM ". G_EMPLOYEE_DEPENDENT ." e
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
		
		$e = new G_Employee_Dependent;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setName($row['name']);
		$e->setRelationship($row['relationship']);
		$e->setBirthdate($row['birthdate']);

		return $e;
	}
}
?>