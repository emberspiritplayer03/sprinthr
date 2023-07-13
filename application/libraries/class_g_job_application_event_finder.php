<?php
class G_Job_Application_Event_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT 
			*
			FROM ". G_JOB_APPLICATION_EVENT ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		
		return self::getRecord($sql);
	}
	
	public static function findByApplicantId2($applicant_id) {
		$sql = "
			SELECT 
			*
			FROM ". G_JOB_APPLICATION_EVENT ." e
			WHERE e.applicant_id = ". Model::safeSql($applicant_id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByApplicantId($applicant_id) {
		$sql = "
			SELECT 
			*
			FROM ". G_JOB_APPLICATION_EVENT ." e
			WHERE e.applicant_id = ". Model::safeSql($applicant_id) ."	
			ORDER BY e.id ASC
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
		
		$e = new G_Job_Application_Event;
		$e->setId($row['id']);
		$e->setCompanyStructureId($row['company_structure_id']);
		$e->setApplicantid($row['applicant_id']);
		$e->setDateTimeCreated($row['date_time_created']);
		$e->setCreatedBy($row['created_by']);
		$e->setHiringManagerId($row['hiring_manager_id']);
		$e->setDateTimeEvent($row['date_time_event']);
		$e->setEventType($row['event_type']);
		$e->setApplicationStatusId($row['application_status_id']);
		$e->setNotes($row['notes']);
		$e->setRemarks($row['remarks']);

		return $e;
	}
}
?>