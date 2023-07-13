<?php
class G_Excluded_Employee_Deduction_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . EXCLUDED_EMPLOYEE_DEDUCTION ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	
		
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . EXCLUDED_EMPLOYEE_DEDUCTION ." 			
			" . $order_by . "
			" . $limit . "		
		";
		return self::getRecords($sql);
	}

	public static function findAllByPayrollPeriodIdAndAction($payroll_period_id, $action) {
		$sql = "
			SELECT * 
			FROM " . EXCLUDED_EMPLOYEE_DEDUCTION ." 			
			WHERE payroll_period_id =". Model::safeSql($payroll_period_id) ."	
			AND action =". Model::safeSql($action) ."	
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
               
        $o = new G_Excluded_Employee_Deduction();
        $o->setId($row['id']);
        $o->setEmployeeId($row['employee_id']);
        $o->setPayrollPeriodId($row['payroll_period_id']);    
        $o->setNewPayrollPeriodId($row['new_payroll_period_id']);      
        $o->setVariableName($row['variable_name']);   
        $o->setAmount($row['amount']); 
        $o->setAction($row['action']); 
        $o->setDateCreated($row['date_created']);           
        return $o;
    }

}
?>