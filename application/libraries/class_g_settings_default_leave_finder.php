<?php
class G_Settings_Default_Leave_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT *
			FROM ". G_SETTINGS_DEFAULT_LEAVE ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByLeaveTypeId($id,$cid) {
		$sql = "
			SELECT *
			FROM ". G_SETTINGS_DEFAULT_LEAVE ." e
			WHERE e.company_structure_id = ". Model::safeSql($cid) ."	
			AND e.leave_type_id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByUserGroupId($user_group_id) {
		$sql = "
			SELECT *
			FROM ". G_SETTINGS_DEFAULT_LEAVE ." e
			WHERE 
			e.user_group_id = ". Model::safeSql($user_group_id) ."
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
	
	public $company_structure_id;
	public $leave_type_id;
	public $number_of_days_default;
	
	private static function newObject($row) {
		
		$e = new G_Settings_Default_Leave;
		$e->setId($row['id']);
		$e->setCompanyStructureId($row['company_structure_id']);
		$e->setLeaveTypeId($row['leave_type_id']);
		$e->setNumberOfDaysDefault($row['number_of_days_default']);
		return $e;
	}
}
?>