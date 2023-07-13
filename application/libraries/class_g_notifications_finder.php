<?php
class G_Notifications_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_NOTIFICATIONS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	

	public static function findAll($order_by = '', $limit = '') {
		
		$sql = "
			SELECT * 
			FROM " . G_NOTIFICATIONS ." 			
			".$order_by."
			".$limit."		
		";
		return self::getRecords($sql);
	}
    
    public static function findByEventType($event_type) {
		$sql = "
			SELECT * 
			FROM " . G_NOTIFICATIONS ." 
			WHERE event_type =". Model::safeSql($event_type) ."
			LIMIT 1
		";

		return self::getRecord($sql);
	}

    public static function findByEventTypeNull() {
		$sql = "
			SELECT * 
			FROM " . G_NOTIFICATIONS ." 
			WHERE event_type = ''
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
		$n = new G_Notifications();
		$n->setId($row['id']);
		$n->setEventType($row['event_type']);
		$n->setDescription($row['description']);
		$n->setStatus($row['status']);
		$n->setItem($row['item']);
        $n->setDateModified($row['date_modified']);
		$n->setDateCreated($row['date_created']);
		return $n;
	}
	
}
?>