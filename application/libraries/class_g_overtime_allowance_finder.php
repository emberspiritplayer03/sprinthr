<?php
class G_Overtime_Allowance_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_OVERTIME_ALLOWANCE ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	
		
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . G_OVERTIME_ALLOWANCE ." 			
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
               
        $g = new G_Overtime_Allowance();
        $g->setId($row['id']);
        $g->setObjectId($row['object_id']);
        $g->setObjectType($row['object_type']);
        $g->setAppliedDayType($row['applied_day_type']);
        $g->setOtAllowance($row['ot_allowance']);
        $g->setMultiplier($row['multiplier']);        
        $g->setMaxOtAllowance($row['max_ot_allowance']);   
        $g->setDateStart($row['date_start']);   
        $g->setDescription($row['description']);   
        $g->setDescriptionDayType($row['description_day_type']);
        $g->setDateCreated($row['date_created']);              
        return $g;
    }

}
?>