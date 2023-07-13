<?php
class G_Employee_User_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_USER ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	

	public static function findByEmployeeId($employee_id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_USER ." 
			WHERE employee_id =". Model::safeSql($employee_id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}
		
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_USER ." 			
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
               
        $geu = new G_Employee_User();
        $geu->setId($row['id']);
        $geu->setCompanyStructureId($row['company_structure_id']);
        $geu->setEmployeeId($row['employee_id']);        
        $geu->setUsername($row['username']);                
        $geu->setPassword($row['password']);                
        $geu->setRoleId($row['role_id']);                
        $geu->setDateCreated($row['date_created']);                
        $geu->setLastModified($row['last_modified']);                
        $geu->setIsArchive($row['is_archive']);                
        return $geu;
    }

}
?>