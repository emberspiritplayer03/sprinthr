<?php
class G_Break_Time_Schedule_Details_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . BREAK_TIME_SCHEDULE_DETAILS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	
		
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . BREAK_TIME_SCHEDULE_DETAILS ." 			
			" . $order_by . "
			" . $limit . "		
		";
		return self::getRecords($sql);
	}

	public static function findbByHeaderId($header_id){


		$sql = "
			SELECT * 
			FROM " . BREAK_TIME_SCHEDULE_DETAILS ." 
			WHERE header_id =". Model::safeSql($header_id) ."
			LIMIT 1
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
        $gbd = new G_Break_Time_Schedule_Details();
        $gbd->setId($row['id']);   
        $gbd->setHeaderId($row['header_id']);                    
        $gbd->setObjId($row['obj_id']);
        $gbd->setObjType($row['obj_type']);     
        $gbd->setBreakIn($row['break_in']);
        $gbd->setBreakOut($row['break_out']);    
        $gbd->setToDeduct($row['to_deduct']);
        $gbd->setAppliedToLegalHoliday($row['applied_to_legal_holiday']);
        $gbd->setAppliedToSpecialHoliday($row['applied_to_special_holiday']);
        $gbd->setAppliedToRestDay($row['applied_to_restday']);
        $gbd->setAppliedToRegularDay($row['applied_to_regular_day']);
        return $gbd;
    }

}
?>