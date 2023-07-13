<?php
class G_Settings_Employee_Benefit_Finder {

    public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_EMPLOYEE_BENEFITS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}		

	public static function findByName($name) {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_EMPLOYEE_BENEFITS ." 
			WHERE name =". Model::safeSql($name) ."
			AND is_archive = 'No'
			LIMIT 1
		";		

		return self::getRecord($sql);
	}
	
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_EMPLOYEE_BENEFITS ." 			
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
               
        $gseb = new G_Settings_Employee_Benefit();
        $gseb->setId($row['id']);
        $gseb->setCode($row['code']);
        $gseb->setName($row['name']);        
        $gseb->setDescription($row['description']);        
        $gseb->setAmount($row['amount']);        
        $gseb->setIsTaxable($row['is_taxable']);
        $gseb->setCutOff($row['cutoff']);
        $gseb->setMultipliedBy($row['multiplied_by']);
        $gseb->setIsArchive($row['is_archive']);
        $gseb->setDateCreated($row['date_created']);
        $gseb->setDateLastModified($row['date_last_modified']);
        return $gseb;
    }

}
?>