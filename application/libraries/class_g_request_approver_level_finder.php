<?php
class G_Request_Approver_Level_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . REQUEST_APPROVERS_LEVEL ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	
		
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . REQUEST_APPROVERS_LEVEL ." 			
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
        $grl = new G_Request_Approver_Level();
        $grl->setId($row['id']);   
        $grl->setRequestApproversId($row['request_approvers_id']);                    
        $grl->getEmployeeId($row['employee_id']);
        $grl->getEmployeeName($row['employee_name']);
        $grl->getLevel($row['level']);
        return $grl;
    }

}
?>