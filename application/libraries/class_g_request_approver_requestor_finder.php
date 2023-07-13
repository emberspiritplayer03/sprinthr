<?php
class G_Request_Approver_Requestor_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . REQUEST_APPROVERS_REQUESTORS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	
		
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . REQUEST_APPROVERS_REQUESTORS ." 			
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
        $grr = new G_Request_Approver_Requestor();
        $grr->getId($row['id']);   
        $grr->getRequestApproversId($row['request_approvers_id']);                    
        $grr->getEmployeeId($row['employee_id']);
        $grr->getEmployeeName($row['employee_name']);        
        return $grr;
    }

}
?>