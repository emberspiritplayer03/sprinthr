<?php
class G_Applicant_Requirements_Finder {

	public static function findById($id) {

		$sql = "
			SELECT 
			*
			FROM ".G_APPLICANT_REQUIREMENTS." a
			WHERE a.id=". Model::safeSql($id)." 
			LIMIT 1
		";

		return self::getRecord($sql);
	}
	
	public static function findByApplicantId($id) {
		$sql = "
			SELECT * 
			FROM " . G_APPLICANT_REQUIREMENTS ." 			
			WHERE applicant_id=".Model::safeSql($id)."
			
		";

		return self::getRecord($sql);
	}
	
	
	public static function findAll() {
		$sql = "
			SELECT * 
			FROM " . G_APPLICANT_REQUIREMENTS ." 			
			
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
		//print_r($row);
		$gcb = new G_Applicant_Requirements;
		$gcb->setId($row['id']);
		$gcb->setApplicantId($row['applicant_id']);
		$gcb->setRequirements($row['requirements']);
		$gcb->setDateUpdated($row['date_updated']);
		$gcb->setIsComplete($row['is_complete']);

		return $gcb;
	}
}
?>