<?php
class G_Settings_Leave_General_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT *
			FROM ". G_SETTINGS_LEAVE_GENERAL ." 
			WHERE id = ". Model::safeSql($id) ."	
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findAll($order_by, $limit) {
		$sql = "
			SELECT *
			FROM ". G_SETTINGS_LEAVE_GENERAL ."
			".$order_by."
			".$limit."
		";
		return self::getRecords($sql);
	}

	public static function findDefaultLeaveGeneralRule() {
		$sql = "
			SELECT *
			FROM ". G_SETTINGS_LEAVE_GENERAL ." 
			WHERE id = ". Model::safeSql(G_Settings_Leave_General::DEFAULT_ID) ."	
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
		$u = new G_Settings_Leave_General();
		$u->setId($row['id']);
        $u->setConvertLeaveCriteria($row['convert_leave_criteria']);
		$u->setLeaveId($row['leave_id']);		
		return $u;
	}
}
?>