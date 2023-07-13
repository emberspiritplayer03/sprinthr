<?php
class G_Request_Finder {

    public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . REQUESTS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}

	public static function findByRequestIdAndRequestType($request_id = 0, $request_type = '') {
		$sql = "
			SELECT * 
			FROM " . REQUESTS ." 
			WHERE request_id =". Model::safeSql($request_id) ."
				AND request_type =" . Model::safeSql($request_type) . "
			LIMIT 1
		";				
		return self::getRecord($sql);
	}	
		
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . REQUESTS ." 			
			" . $order_by . "
			" . $limit . "		
		";
		return self::getRecords($sql);
	}

	public static function findByRequestorIdAndRequestIdAndRequestType($requestor_employee_id,$request_id,$request_type) {
		$sql = "
			SELECT * 
			FROM " . REQUESTS ." 
			WHERE requestor_employee_id =". Model::safeSql($requestor_employee_id) ." 
				AND request_id =". Model::safeSql($request_id) ." 
				AND request_type =". Model::safeSql($request_type) ." 
			ORDER BY id ASC

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
        $gr = new G_Request();
        $gr->setId($row['id']);   
        $gr->setRequestorEmployeeId($row['requestor_employee_id']);                    
        $gr->setRequestId($row['request_id']);
        $gr->setRequestType($row['request_type']);     
        $gr->setApproverEmployeeId($row['approver_employee_id']);
        $gr->setApproverName($row['approver_name']);    
        $gr->setStatus($row['status']);
        $gr->setIsLock($row['is_lock']);    
        $gr->setRemarks($row['remarks']);
        $gr->setActionDate($row['action_date']);       
        return $gr;
    }

}
?>