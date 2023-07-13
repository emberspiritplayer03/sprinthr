<?php
class G_Settings_Employee_Field_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
				*

			FROM ". G_SETTINGS_EMPLOYEE_FIELD ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		
		return self::getRecord($sql);
	}
	
	public static function findByScreen($screen) {
		$sql = "
			SELECT 
				*
			FROM ". G_SETTINGS_EMPLOYEE_FIELD ." e
			WHERE e.screen = ". Model::safeSql($screen) ."	
	
		";

		return self::getRecords($sql);
	}
	
	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				*
			FROM ". G_SETTINGS_EMPLOYEE_FIELD ." e
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
		
		$e = new G_Settings_Employee_Field;
		$e->setId($row['id']);
		$e->setCompanyStructureId($row['company_structure_id']);
		$e->setTitle($row['title']);
		$e->setScreen($row['screen']);
		$e->setDefault($row['default']);

		return $e;
	}
}
?>