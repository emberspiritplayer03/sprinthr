<?php
class G_Applicant_Examination_Finder {

	public static function findById($id) {

		$sql = "
			SELECT 
			*
			FROM ". G_APPLICANT_EXAMINATION ." a
			WHERE a.id=". Model::safeSql($id)." 
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByExamCode($exam_code) {

		$sql = "
			SELECT 
			*
			FROM ". G_APPLICANT_EXAMINATION ." a
			WHERE a.exam_code=". Model::safeSql($exam_code)." 
			LIMIT 1
		";

		return self::getRecord($sql);
	}
	
	public static function findByApplicantId($id) {
		$sql = "
			SELECT * 
			FROM " . G_APPLICANT_EXAMINATION ." 			
			WHERE applicant_id=".Model::safeSql($id)."
			
		";

		return self::getRecords($sql);
	}
	
	public static function findByApplicantId2($id) {
		$sql = "
			SELECT * 
			FROM " . G_APPLICANT_EXAMINATION ." 			
			WHERE applicant_id=".Model::safeSql($id)."
			LIMIT 1
			
		";

		return self::getRecord($sql);
	}
	
	public static function findAllByApplicantId($id){
		$sql = "
			SELECT * 
			FROM " . G_APPLICANT_EXAMINATION ." 			
			WHERE applicant_id=".Model::safeSql($id)."			
		";

		return self::getRecords($sql);		
	}
	
	public static function findByCompanyStructureId($id) {
		$sql = "
			SELECT * 
			FROM " . G_APPLICANT_EXAMINATION ." 			
			WHERE company_structure_id=".Model::safeSql($id)."
			
		";

		return self::getRecords($sql);
	}
	
	
	public static function findAll() {
		$sql = "
			SELECT * 
			FROM " . G_APPLICANT_EXAMINATION ." 			
			
		";
		return self::getRecords($sql);
	}
	
	public static function findScheduleDate($date) {
		$sql = "
			SELECT * 
			FROM " . G_APPLICANT_EXAMINATION ." 			
			WHERE schedule_date LIKE '%".$date."%'
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
		$gcb = new G_Applicant_Examination($row['id']);
		$gcb->setCompanyStructureId($row['company_structure_id']);
		$gcb->setApplicantId($row['applicant_id']);
		$gcb->setExamId($row['exam_id']);
		$gcb->setTitle($row['title']);
		$gcb->setDescription($row['description']);
		$gcb->setExamCode($row['exam_code']);
		$gcb->setPassingPercentage($row['passing_percentage']);
		$gcb->setScheduleDate($row['schedule_date']);
		$gcb->setDateTaken($row['date_taken']);
		$gcb->setStatus($row['status']);
		$gcb->setResult($row['result']);
		$gcb->setQuestions($row['questions']);
		$gcb->setTimeDuration($row['time_duration']);
		$gcb->setScheduledBy($row['scheduled_by']);
	
		return $gcb;
	}
}
?>