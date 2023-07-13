<?php
class G_Group_Restday_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . GROUP_RESTDAY ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	

	public static function findByGroupIdAndDate($group_id, $date) {
		$sql = "
			SELECT g.id, g.group_id, g.date
			FROM ". GROUP_RESTDAY ." g
			WHERE g.group_id = ". Model::safeSql($group_id) ."
			AND g.date = ". Model::safeSql($date) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
		
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . GROUP_RESTDAY ." 			
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
        $grd = new G_Group_Restday();
        $grd->setId($row['id']);   
        $grd->setGroupId($row['group_id']);                    
        $grd->setDate($row['date']);        
        return $grd;
    }

}
?>