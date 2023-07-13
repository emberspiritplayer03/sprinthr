<?php
class G_Sprint_Variables_Finder {

    public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . SPRINT_VARIABLES ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	

	public static function findByVariableName($variable_name = '') {
		$sql = "
			SELECT * 
			FROM " . SPRINT_VARIABLES ." 
			WHERE variable_name =". Model::safeSql($variable_name) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	
		
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . SPRINT_VARIABLES ." 			
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
        $sv = new G_Sprint_Variables();
        $sv->setId($row['id']);   
        $sv->setVariableName($row['variable_name']);                    
        $sv->setValue($row['value']);    
        $sv->setCustomValueA($row['custom_value_a']);    
        return $sv;
    }

}
?>