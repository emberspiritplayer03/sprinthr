<?php
class G_Employee_Benefit_Finder {

    public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_BENEFITS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return self::getRecord($sql);
	}	
	
	public static function findByBenefitId($benefit_id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_BENEFITS ." 
			WHERE benefit_id =". Model::safeSql($benefit_id) ."
			LIMIT 1
		";		
		return self::getRecord($sql);
	}	
	
	public static function findAllByBenefitId($benefit_id, $order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_BENEFITS ." 	
			WHERE benefit_id =" . Model::safeSql($benefit_id) . "		
			" . $order_by . "
			" . $limit . "		
		";
		return self::getRecords($sql);
	}
	
	public static function findAllByEmployeeIdAndAppliendToAllEmployee($employee_id, $order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_BENEFITS ." 	
			WHERE obj_id =" . Model::safeSql($employee_id) . "
				AND obj_type =" . Model::safeSql(G_Employee_Benefit::EMPLOYEE) . "
				AND apply_to_all =" . Model::safeSql(G_Employee_Benefit::EMPLOYEE) . " 		
			" . $order_by . "
			" . $limit . "		
		";
		return self::getRecords($sql);
	}
	
	public static function findAllEmployeeByBenefitId($benefit_id, $order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_BENEFITS ." 	
			WHERE benefit_id =" . Model::safeSql($benefit_id) . "
				AND obj_type =" . Model::safeSql(G_Employee_Benefit::EMPLOYEE) . "		
			" . $order_by . "
			" . $limit . "		
		";
		return self::getRecords($sql);
	}
	
	public static function findAllByEmployeeId($employee_id, $order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_BENEFITS ." 	
			WHERE employee_id =" . Model::safeSql($employee_id) . "		
			" . $order_by . "
			" . $limit . "		
		";
		return self::getRecords($sql);
	}
		
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_BENEFITS ." 			
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
               
        $geb = new G_Employee_Benefit();
        $geb->setId($row['id']);
        $geb->setObjId($row['obj_id']);
		$geb->setObjType($row['obj_type']);
		$geb->setApplyToAll($row['apply_to_all']);
        $geb->setBenefitId($row['benefit_id']);        
        $geb->setDateCreated($row['date_created']);        
        return $geb;
    }

}
?>