<?php
class G_Break_Time_Schedule_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . BREAK_TIME_SCHEDULE ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	

	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . BREAK_TIME_SCHEDULE ." 			
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
        $gbts = new G_Break_Time_Schedule();
        $gbts->setId($row['id']);   
        $gbts->setScheduleIn($row['schedule_in']);                    
        $gbts->setScheduleOut($row['schedule_out']);
        $gbts->setBreakIn($row['break_in']);     
        $gbts->setBreakOut($row['break_out']);
        $gbts->setTotalHrsBreak($row['total_hrs_break']);    
        $gbts->setToDeduct($row['to_deduct']);
        $gbts->setTotalHrsToDeduct($row['total_hrs_to_deduct']);
        return $gbts;
    }

}
?>