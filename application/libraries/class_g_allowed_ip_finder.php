<?php
class G_Allowed_Ip_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . ALLOWED_IP ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	
		
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . ALLOWED_IP ." 			
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
               
        $gai = new G_Allowed_Ip();
        $gai->setId($row['id']);
        $gai->setIpAddress($row['ip_address']);
        $gai->setEmployeeId($row['employee_id']);        
        $gai->setDateModified($row['date_modified']);   
        $gai->setDateCreated($row['date_created']);              
        return $gai;
    }

}
?>