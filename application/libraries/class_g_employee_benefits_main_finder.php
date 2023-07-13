<?php
class G_Employee_Benefits_Main_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_BENEFITS_MAIN ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	
		
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_BENEFITS_MAIN ." 			
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
               
        $gebm = new G_Employee_Benefits_Main();
        $gebm->setId($row['id']);
        $gebm->setCompanyStructureId($row['company_structure_id']);
        $gebm->setEmployeeDepartmentId($row['employee_department_id']);        
        $gebm->setBenefitId($row['benefit_id']);                
        $gebm->setCriteria($row['criteria']);          
        $gebm->setCustomCriteria($row['custom_criteria']);                
        $gebm->setAppliedTo($row['applied_to']);        
		$gebm->setExcludedEmployeeId($row['excluded_emplooyee_id']);                       
        return $gebm;
    }

}
?>