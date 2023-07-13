<?php
class G_Employee_Fixed_Contribution_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_FIXED_CONTRI ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	
		
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . G_FIXED_CONTRI ." 			
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
        $gefc = new G_Employee_Fixed_Contribution();
        $gefc->setId($row['id']);   
        $gefc->setEmployeeId($row['employee_id']);                    
        $gefc->setType($row['type']);
        $gefc->setEEAmount($row['ee_amount']);     
        $gefc->setERAmount($row['er_amount']);
        $gefc->setIsActivated($row['is_activated']);            
        return $gefc;
    }

}
?>