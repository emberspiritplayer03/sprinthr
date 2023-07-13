<?php
class G_Settings_Grace_Period_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT *
			FROM ". G_SETTINGS_GRACE_PERIOD ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";		
		return self::getRecord($sql);
	}
	
	public static function findByDefault() {
		$sql = "
			SELECT *
			FROM ". G_SETTINGS_GRACE_PERIOD ." e
			WHERE e.is_default = ". Model::safeSql(1) ."	
			LIMIT 1		
		";				
		return self::getRecord($sql);
	}
	
	public static function findByUserGroupId($user_group_id) {
		$sql = "
			SELECT *
			FROM ". G_SETTINGS_GRACE_PERIOD ." e
			WHERE 
			e.user_group_id = ". Model::safeSql($user_group_id) ."
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByUserGroupIdAndPolicyType($user_group_id,$policy_type) {
		$sql = "
			SELECT *
			FROM ". G_SETTINGS_GRACE_PERIOD ." e
			WHERE 
			e.user_group_id = ". Model::safeSql($user_group_id) ." AND
			e.policy_type	= ". Model::safeSql($policy_type) . " 
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findAllCompanyStructureIdActive($csid,$order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_GRACE_PERIOD ." 			
			WHERE company_structure_id = ". Model::safeSql($csid) ." 
			AND is_archive = ". Model::safeSql(0) ." 
			" . $order_by . "
			" . $limit . "		
		";
		return self::getRecords($sql);
	}
	
	public static function findAllByCompanyStructureIsNotArchive($csid,$order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_GRACE_PERIOD ." 			
			WHERE company_structure_id = ". Model::safeSql($csid) ." 
			AND is_archive = ". Model::safeSql(0) ." 
			" . $order_by . "
			" . $limit . "		
		";
		return self::getRecords($sql);
	}
	
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_GRACE_PERIOD ." 			
			" . $order_by . "
			" . $limit . "		
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
		
		$e = new G_Settings_Grace_Period;
		$e->setId($row['id']);
		$e->setCompanyStructureId($row['company_structure_id']);
		$e->setTitle($row['title']);
		$e->setDescription($row['description']);
		$e->setIsArchive($row['is_archive']);
		$e->setIsDefault($row['is_default']);
		$e->setNumberMinuteDefault($row['number_minute_default']);
		return $e;
	}
}
?>