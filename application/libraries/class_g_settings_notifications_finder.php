<?php
class G_Settings_Notifications_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_NOTIFICATIONS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	
	

	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_NOTIFICATIONS ." 			
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
		$at = new G_Settings_Notifications();
		$at->setId($row['id']);
		$at->setTitle($row['title']);
		$at->setSubModule($row['sub_module']);
		$at->setIsEnable($row['is_enable']);								
		return $at;
	}
	
}
?>