<?php
class G_Employee_Emergency_Contact_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
				e.id, 
				e.employee_id, 
				e.person,
				e.relationship, 
				e.home_telephone, 
				e.mobile,
				e.work_telephone,
				e.address

			FROM ". G_EMPLOYEE_EMERGENCY_CONTACT ." e
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
				e.person,
				e.relationship, 
				e.home_telephone, 
				e.mobile,
				e.work_telephone,
				e.address
			FROM ". G_EMPLOYEE_EMERGENCY_CONTACT ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."	
	
		";

		return self::getRecords($sql);
	}
	
	public static function findSingleEmployeeEmergencyContact($employee_id) {
		$sql = "
			SELECT 
				e.id, 
				e.employee_id, 
				e.person,
				e.relationship, 
				e.home_telephone, 
				e.mobile,
				e.work_telephone,
				e.address
			FROM ". G_EMPLOYEE_EMERGENCY_CONTACT ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."
			LIMIT 1
	
		";

		return self::getRecord($sql);
	}
	
	public static function findRecordByEmployeeIdPersonMobile($employee_id,$person,$mobile) {
		$sql = "
			SELECT 
				e.id, 
				e.employee_id, 
				e.person,
				e.relationship, 
				e.home_telephone, 
				e.mobile,
				e.work_telephone,
				e.address
			FROM ". G_EMPLOYEE_EMERGENCY_CONTACT ." e
			WHERE 
				e.employee_id 	= ". Model::safeSql($employee_id) ." AND
				e.person 		= ". Model::safeSql($person) ." AND
				e.mobile 		= ". Model::safeSql($mobile) ."
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
		
		$e = new G_Employee_Emergency_Contact;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setPerson($row['person']);
		$e->setRelationship($row['relationship']);
		$e->setHomeTelephone($row['home_telephone']);
		$e->setMobile($row['mobile']);
		$e->setWorkTelephone($row['work_telephone']);
		$e->setAddress($row['address']);
		return $e;
	}
}
?>