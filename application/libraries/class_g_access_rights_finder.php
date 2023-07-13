<?php
class G_Access_Rights_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT *
			FROM ". G_ACCESS_RIGHTS ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByUserGroupId($user_group_id) {
		$sql = "
			SELECT *
			FROM ". G_ACCESS_RIGHTS ." e
			WHERE 
			e.user_group_id = ". Model::safeSql($user_group_id) ."
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByUserGroupIdAndPolicyType($user_group_id,$policy_type) {
		$sql = "
			SELECT *
			FROM ". G_ACCESS_RIGHTS ." e
			WHERE 
			e.user_group_id = ". Model::safeSql($user_group_id) ." AND
			e.policy_type	= ". Model::safeSql($policy_type) . " 
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
		
		$e = new G_Access_Rights;
		$e->setId($row['id']);
		$e->setCompanyStructureId($row['company_structure_id']);
		$e->setUserGroupId($row['user_group_id']);
		$e->setPolicyType($row['policy_type']);
		$e->setRights($row['rights']);
		$e->setDateAdded($row['date_added']);

		return $e;
	}
}
?>