<?php
class G_Request_Approver_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . REQUEST_APPROVERS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	
		
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . REQUEST_APPROVERS ." 			
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
        $gra = new G_Request_Approver();
        $gra->setId($row['id']);   
        $gra->setTitle($row['number_of_days']);                    
        $gra->setApproversName($row['approvers_name']);
        $gra->setRequestorsName($row['requestors_name']);
        $gra->setDateCreated($row['date_created']);
        return $gra;
    }

}
?>