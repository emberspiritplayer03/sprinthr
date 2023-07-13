<?php
class G_Employee_Contact_Details_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
				e.id, 
				e.employee_id, 
				e.address, 
				e.city, 
				e.province,
				e.zip_code, 
				e.country, 
				e.home_telephone, 
				e.mobile, 
				e.work_telephone, 
				e.work_email, 
				e.other_email
			FROM ". G_EMPLOYEE_CONTACT_DETAILS ." e
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
				e.address, 
				e.city, 
				e.province,
				e.zip_code, 
				e.country, 
				e.home_telephone, 
				e.mobile, 
				e.work_telephone, 
				e.work_email, 
				e.other_email
			FROM ". G_EMPLOYEE_CONTACT_DETAILS ." e
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
		
		$e = new G_Employee_Contact_Details;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setAddress($row['address']);
		$e->setCity($row['city']);
		$e->setProvince($row['province']);
		$e->setZipCode($row['zip_code']);
		$e->setCountry($row['country']);
		$e->setHomeTelephone($row['home_telephone']);
		$e->setMobile($row['mobile']);
		$e->setWorkTelephone($row['work_telephone']);
		$e->setWorkEmail($row['work_email']);
		$e->setOtherEmail($row['other_email']);

		return $e;
	}
}
?>