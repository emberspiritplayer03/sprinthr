<?php
class G_User_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT *
			FROM ". G_USER ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByUserGroupId($user_group_id) {
		$sql = "
			SELECT *
			FROM ". G_USER ." e
			WHERE e.user_group_id = ". Model::safeSql($user_group_id) ."	
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT *
			FROM ". G_USER ." 
			WHERE employee_id = ". Model::safeSql($employee_id) ."	
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByUsername($username)
	{
			$sql = "
			SELECT *
			FROM ". G_USER ." 
			WHERE username = ". Model::safeSql($username) ."	
	
		";
		return self::getRecord($sql);
	}
	
	public static function findByEmployeeIdUsername($employee_id,$username)
	{
			$sql = "
			SELECT *
			FROM ". G_USER ." 
			WHERE 
				employee_id	= ". Model::safeSql($employee_id) ." AND
				username 	= ". Model::safeSql($username) ."
	
		";
		return self::getRecord($sql);
	}
	
	public static function findAll($order_by, $limit) {
		$sql = "
			SELECT *
			FROM ". G_USER ."
			".$order_by."
			".$limit."
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
		
		$e = new G_User;
		$e->setId($row['id']);
		$e->setCompanyStructureId($row['company_structure_id']);
		$e->setUserGroupId($row['user_group_id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setEmploymentStatus($row['employment_status']);
		$e->setUsername($row['username']);
		$e->setHash($row['hash']);
		$e->setPassword($row['password']);
		$e->setModule($row['module']);
		$e->setReceiveNotification($row['receive_notification']);
		$e->setDateEntered($row['date_entered']);
		$e->setDateModified($row['date_modified']);
		$e->setModifiedUserId($row['modified_user_id']);
		$e->setCreatedBy($row['created_by']);
        $e->setIsAdmin($row['is_admin']);

		return $e;
	}
}
?>