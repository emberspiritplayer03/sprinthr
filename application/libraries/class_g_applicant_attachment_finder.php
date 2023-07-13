<?php
class G_Applicant_Attachment_Finder {

	public static function findById($id) {

		$sql = "
			SELECT 
			*
			FROM g_applicant_attachment a
			WHERE a.id=". Model::safeSql($id)." 
			LIMIT 1
		";

		return self::getRecord($sql);
	}
	
	public static function findByApplicantId($id) {
		$sql = "
			SELECT * 
			FROM " . G_APPLICANT_ATTACHMENT ." 			
			WHERE applicant_id=".Model::safeSql($id)."
			
		";

		return self::getRecords($sql);
	}
	
	
	public static function findAll() {
		$sql = "
			SELECT * 
			FROM " . G_APPLICANT_ATTACHMENT ." 			
			
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
		$gcb = new G_Applicant_Attachment($row['id']);
		$gcb->setApplicantId($row['applicant_id']);
		$gcb->setName($row['name']);
		$gcb->setFilename($row['filename']);
		$gcb->setDescription($row['description']);
		$gcb->setSize($row['size']);
		$gcb->setType($row['type']);
		$gcb->setDateAttached($row['date_attached']);
		$gcb->setAddedBy($row['added_by']);
		$gcb->setScreen($row['screen']);

		return $gcb;
	}
}
?>