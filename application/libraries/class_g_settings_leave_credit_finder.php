<?php
class G_Settings_Leave_Credit_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT *
			FROM ". G_SETTINGG_LEAVE_CREDIT ." 
			WHERE id = ". Model::safeSql($id) ."	
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findAll($order_by, $limit) {
		$sql = "
			SELECT *
			FROM ". G_SETTINGG_LEAVE_CREDIT ."
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
		$u = new G_Settings_Leave_Credit();
		$u->setId($row['id']);
        $u->setEmploymentYears($row['employment_years']);
		$u->setDefaultCredit($row['default_credit']);
		$u->setLeaveId($row['leave_id']);		
		$u->setEmploymentStatusId($row['employment_status_id']);		
		$u->setIsArchived($row['is_archived']);		
		return $u;
	}
}
?>