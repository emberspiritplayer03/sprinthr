<?php
class G_Schedule_Settings_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . V2_SCHEDULE_SETTINGS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return self::getRecord($sql);
	}	
		
	public static function findAll() {
		$sql = "
			SELECT * 
			FROM " . V2_SCHEDULE_SETTINGS ." 			
			LIMIT 1		
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
               
        $settings = new G_Schedule_Settings();
        $settings->setId($row['id']);
        $settings->setShift($row['shift']);
        $settings->setFlexible($row['flexible']);        
        $settings->setCompressed($row['compressed']); 
		$settings->setStaggered($row['staggered']);       
		$settings->setSecurity($row['security']);
		$settings->setActual($row['actual']);
		$settings->setPerTrip($row['per_trip']);        
        return $settings;
    }

}
?>