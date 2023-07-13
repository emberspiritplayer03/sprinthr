<?php
class G_Break_Time_Schedule_Header_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . BREAK_TIME_SCHEDULE_HEADER ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	

	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . BREAK_TIME_SCHEDULE_HEADER ." 			
			" . $order_by . "
			" . $limit . "		
		";
		return self::getRecords($sql);
	}


	public static function findByScheduleTimeInandOut2($time_in, $time_out,$name, $date){

		$sql = "
			SELECT * 
			FROM " . BREAK_TIME_SCHEDULE_HEADER ." 
			WHERE schedule_in =". Model::safeSql($time_in) ."
			AND schedule_out =". Model::safeSql($time_out) ."
			AND applied_to = ". Model::safeSql($name) ."
			AND date_start <= ". Model::safeSql($date) ."
			ORDER BY id DESC LIMIT 1

		";		
		return 
		self::getRecord($sql);


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
        $gbh = new G_Break_Time_Schedule_Header();
        $gbh->setId($row['id']);   
        $gbh->setScheduleIn($row['schedule_in']);                    
        $gbh->setScheduleOut($row['schedule_out']);
        $gbh->setBreakTimeSchedules($row['break_time_schedules']);             
        $gbh->setAppliedTo($row['applied_to']);
        $gbh->setDateStart($row['date_start']);
        $gbh->setDateCreated($row['date_created']);
        return $gbh;
    }

}
?>