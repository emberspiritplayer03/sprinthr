<?php
class G_Employee_Attachment_Finder {

	public static function findById($id) {

		$sql = "
			SELECT 
			*
			FROM g_employee_attachment a
			WHERE a.id=". Model::safeSql($id)." 
			LIMIT 1
		";

		return self::getRecord($sql);
	}
	
	public static function findByEmployeeId($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_ATTACHMENT ." 			
			WHERE employee_id=".Model::safeSql($id)."
			
		";

		return self::getRecords($sql);
	}
	
	
	public static function findAll() {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_ATTACHMENT ." 			
			
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
		$gcb = new G_Employee_Attachment($row['id']);
		$gcb->setEmployeeId($row['employee_id']);
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