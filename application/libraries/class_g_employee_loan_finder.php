<?php
class G_Employee_Loan_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_LOAN ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	

	public static function findAllInProgressAndIsNotArchiveLoanByEmployeeIdAndWithinCutoffPeriodDepre($employee_id = 0, $start_date = '', $end_date = ''){
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_LOAN ." 
			WHERE employee_id =" . Model::safeSql($employee_id) . "
				AND status =" . Model::safeSql(G_Employee_Loan::IN_PROGRESS) . "
				AND is_archive =" . Model::safeSql(G_Employee_Loan::NO) . " 				
				AND (start_date + INTERVAL 1 DAY) BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_date) . "			
		";		
		return self::getRecords($sql);
	}

	public static function findAllInProgressAndIsNotArchiveLoanByEmployeeIdAndWithinCutoffPeriod($employee_id = 0, $start_date = '', $end_date = ''){
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_LOAN ." 
			WHERE employee_id =" . Model::safeSql($employee_id) . "
				AND status =" . Model::safeSql(G_Employee_Loan::IN_PROGRESS) . "
				AND is_archive =" . Model::safeSql(G_Employee_Loan::NO) . " 				
				AND start_date <=" . Model::safeSql($start_date) . "	AND end_date >=" . Model::safeSql($end_date) . "			
		";					
		return self::getRecords($sql);
	}

	public static function findAllByCompanyStructureIdAndEmployeeIdAndWithinCutoffPeriod($company_structure_id = 0, $employee_id =0, $cutoff =array()){
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_LOAN ." 
			WHERE company_structure_id =". Model::safeSql($company_structure_id) ."
				AND employee_id =" . Model::safeSql($employee_id) . "
				AND loan_type_id =" . Model::safeSql($loan_type_id) . "
				AND start_date =" . Model::safeSql($start_date) . "
			ORDER BY id DESC
			LIMIT 1
		";
		
		return self::getRecords($sql);
	}

	public static function findByCompanyStructureIdEmployeeIdLoanTypeIdAndStartDate($company_structure_id = 0, $employee_id = 0, $loan_type_id = 0, $start_date = ''){

		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_LOAN ." 
			WHERE company_structure_id =". Model::safeSql($company_structure_id) ."
				AND employee_id =" . Model::safeSql($employee_id) . "
				AND loan_type_id =" . Model::safeSql($loan_type_id) . "
				AND start_date =" . Model::safeSql($start_date) . "
			ORDER BY id DESC
			LIMIT 1
		";
		
		return self::getRecord($sql);

	}

	//check for existing duplicate
	public static function checkEmployeeLoanDuplicate($company_structure_id = 0, $employee_id = 0, $loan_type_id = 0, $start_date = '',$end_date = '', $amount = 0){

		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_LOAN ." 
			WHERE company_structure_id =". Model::safeSql($company_structure_id) ."
				AND employee_id =" . Model::safeSql($employee_id) . "
				AND loan_type_id =" . Model::safeSql($loan_type_id) . "
				AND start_date =" . Model::safeSql($start_date) . "
				AND end_date =" . Model::safeSql($end_date) . "
				AND total_amount_to_pay =" .Model::safeSql(floatval($amount)). "
			ORDER BY id DESC
			LIMIT 1
		";
		
		return self::getRecord($sql);

	}


	public static function findByEmployeeIdStatusAndNotArchive($employee_id = 0){

		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_LOAN ." 
			WHERE employee_id =" . Model::safeSql($employee_id) . "
			AND status =" . Model::safeSql(G_Employee_Loan::PENDING) . "
			AND is_archive =" . Model::safeSql(G_Employee_Loan::NO) . "
			ORDER BY id DESC 
		";
		
		return self::getRecords($sql);

	}	
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_LOAN ." 			
			".$order_by."
			".$limit."		
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
		$gel = new G_Employee_Loan();
		$gel->setId($row['id']);
		$gel->setCompanyStructureId($row['company_structure_id']);
		$gel->setEmployeeId($row['employee_id']);
		$gel->setLoanTypeId($row['loan_type_id']);
		$gel->setEmployeeName($row['employee_name']);
		$gel->setLoanTitle($row['loan_title']);	
		$gel->setInterestRate($row['interest_rate']);				
		$gel->setLoanAmount($row['loan_amount']);
		$gel->setAmountPaid($row['amount_paid']);	
		$gel->setMonthsToPay($row['months_to_pay']);
		$gel->setTotalAmountToPay($row['total_amount_to_pay']);
		$gel->setDeductionPerPeriod($row['deduction_per_period']);
		$gel->setDeductionType($row['deduction_type']);		
		$gel->setCutoffPeriod($row['cutoff_period']);
		$gel->setStartDate($row['start_date']);				
		$gel->setEndDate($row['end_date']);			
		$gel->setIsLock($row['is_lock']);	
		$gel->setStatus($row['status']);				
		$gel->setIsArchive($row['is_archive']);				
		$gel->setDateCreated($row['date_created']);				
		return $gel;
	}
}
?>