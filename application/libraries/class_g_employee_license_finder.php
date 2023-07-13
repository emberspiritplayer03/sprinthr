<?php
class G_Employee_License_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_LICENSE ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByVehicle($employee_id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_LICENSE ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."
			AND license_type='drivers license'	
	
		";

		return self::getRecords($sql);
	}
	
	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT 
				*
			FROM ". G_EMPLOYEE_LICENSE ." e
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
		
		$e = new G_Employee_License;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setLicenseType($row['license_type']);
		$e->setLicenseNumber($row['license_number']);
		$e->setIssuedDate($row['issued_date']);
		$e->setExpiryDate($row['expiry_date']);	
		$e->setNotes($row['notes']);	
		
		return $e;
	}
}
?>