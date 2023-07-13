<?php
class G_Employee_Annualize_Tax_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . ANNUALIZE_TAX ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	
		
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . ANNUALIZE_TAX ." 			
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
        $at = new G_Employee_Annualize_Tax();
        $at->setId($row['id']);   
        $at->setEmployeeId($row['employee_id']);                    
        $at->setYear($row['year']);        
        $at->setFromDate($row['from_date']);   
        $at->setToDate($row['to_date']);                    
        $at->setGrossIncomeTax($row['gross_income_tax']);        
        $at->setLessPersonalExemption($row['less_personal_exemption']);   
        $at->setTaxableIncome($row['taxable_income']);                    
        $at->setTaxDue($row['tax_due']);        
        $at->setTaxWithHeldPayroll($row['tax_withheld_payroll']);   
        $at->setTaxRefundPayable($row['tax_refund_payable']);                            
        $at->setCutoffStartDate($row['cutoff_start_date']);                            
        $at->setCutoffEndDate($row['cutoff_end_date']);                            
        $at->setDateCreated($row['date_created']);                            
        return $at;
    }

}
?>