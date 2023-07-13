<?php
class G_Applicant_License_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
				*
			FROM ". G_APPLICANT_LICENSE ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByApplicantId($applicant_id) {
		$sql = "
			SELECT 
				*
			FROM ". G_APPLICANT_LICENSE ." e
			WHERE e.applicant_id = ". Model::safeSql($applicant_id) ."	
	
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
		
		$e = new G_Applicant_License;
		$e->setId($row['id']);
		$e->setApplicantId($row['applicant_id']);
		$e->setLicenseType($row['license_type']);
		$e->setLicenseNumber($row['license_number']);
		$e->setIssuedDate($row['issued_date']);
		$e->setExpiryDate($row['expiry_date']);	
		
		return $e;
	}
}
?>