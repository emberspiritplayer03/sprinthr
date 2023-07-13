<?php
class G_Employee_Dynamic_Field_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
				*

			FROM ". G_EMPLOYEE_DYNAMIC_FIELD ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		
		return self::getRecord($sql);
	}
	
	public static function findBySettingsEmployeeFieldId($settings_employee_field_id,$e) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_DYNAMIC_FIELD ." e
			WHERE e.settings_employee_field_id = ". Model::safeSql($settings_employee_field_id) ."	
			AND e.employee_id=".Model::safeSql($e->id)."
			LIMIT 1		
		";

		return self::getRecord($sql);
	}
	
	public static function findFieldNotUnderSettingsEmployeeField($screen,$e) {
			
	}
	
	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				*

			FROM ". G_EMPLOYEE_DYNAMIC_FIELD ." e
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
		
		$e = new G_Employee_Dynamic_Field;
		$e->setId($row['id']);
		$e->setSettingsEmployeeFieldId($row['settings_employee_field_id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setTitle($row['title']);
		$e->setValue($row['value']);
		$e->setScreen($row['screen']);

		return $e;
	}
}
?>