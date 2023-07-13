<?php
class G_Employee_Earnings_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}	
	
	public static function findAllByCompanyStructureId($company_structure_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE company_structure_id =" . Model::safeSql($company_structure_id) . "			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllIsArchive($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE is_archive =" . Model::safeSql(G_Employee_Earnings::YES) . "			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllIsNotArchive($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE is_archive =" . Model::safeSql(G_Employee_Earnings::NO) . "			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllPending($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE status =" . Model::safeSql(G_Employee_Earnings::PENDING) . "			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllApproved($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE status =" . Model::safeSql(G_Employee_Earnings::APPROVED) . "			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}

	public static function findAllApprovedByEmployeeIdAndTitleAndYear( $employee_id = null, $year = null, $query = null ) {
		$search_query 	= $query;
		$sql = "
			SELECT amount, title, object_id, date_created 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE status =" . Model::safeSql(G_Employee_Earnings::APPROVED) . "			
			AND object_id =" . $employee_id . "			
			AND title LIKE '%{$search_query}%'
			AND YEAR(date_created) = '{$year}'
			AND is_archive =" . Model::safeSql(G_Employee_Earnings::NO) . "	
			".$order_by."
			".$limit."		
		";
		
		$records = Model::runSql($sql,true);
		return $records;		
	}
	
	public static function findAllByStatus($status,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE status =" . Model::safeSql($status) . "			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllByCompanyStructureIdAndPayrollPeriodId($company_structure_id,$payroll_period_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . "
				AND company_structure_id =" . Model::safeSql($company_structure_id) . "			
			".$order_by."
			".$limit."		
		";
		return self::getRecords($sql);
	}
	
	public static function findAllByPayrollPeriodId($payroll_period_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . "			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}

	public static function findAllIsNotArchiveAndISApprovedByPeriodId($payroll_period_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . "			
				AND status =" . Model::safeSql(G_Employee_Earnings::APPROVED) . "
				AND is_archive =" . Model::safeSql(G_Employee_Earnings::NO) . "
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllIsNotArchiveByPayrollPeriodIdAndCompanyStructureId($payroll_period_id,$company_structure_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . "	
				AND is_archive =" . Model::safeSql(G_Employee_Earnings::NO) . " 		
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllPendingsByPayrollPeriodId($payroll_period_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . " AND status =" . Model::safeSql(G_Employee_Earnings::PENDING) . "			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllPendingsByPayrollPeriodIdAndCompanyStructureId($payroll_period_id,$company_structure_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . " 
				AND status =" . Model::safeSql(G_Employee_Earnings::PENDING) . "
				AND company_structure_id =" . Model::safesql($company_structure_id) . "			
			".$order_by."
			".$limit."		
		";		
		return self::getRecords($sql);
	}
	
	public static function findAllPendingsByPayrollPeriodIdAndCompanyStructureIdAndIsNotArchive($payroll_period_id,$company_structure_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . " 
				AND status =" . Model::safeSql(G_Employee_Earnings::PENDING) . "
				AND company_structure_id =" . Model::safesql($company_structure_id) . "
				AND is_archive =" . Model::safeSql(G_Employee_Earnings::NO) . "			
			".$order_by."
			".$limit."		
		";		
		return self::getRecords($sql);
	}
	
	public static function findAllApprovedByPayrollPeriodIdAndCompanyStructureIdAndIsNotArchive($payroll_period_id,$company_structure_id,$frequency_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . " 
				AND status =" . Model::safeSql(G_Employee_Earnings::APPROVED) . "
				AND company_structure_id =" . Model::safesql($company_structure_id) . "
				AND is_archive =" . Model::safeSql(G_Employee_Earnings::NO) . "		
				AND frequency_id = ".Model::safeSql($frequency_id).";	
			".$order_by."
			".$limit."		
		";		

		return self::getRecords($sql);
	}
	
	public static function findAllIsArchiveByPayrollPeriodIdAndCompanyStructureId($payroll_period_id,$company_structure_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . " 				
				AND company_structure_id =" . Model::safesql($company_structure_id) . "
				AND is_archive =" . Model::safeSql(G_Employee_Earnings::YES) . "			
			".$order_by."
			".$limit."		
		";		
		return self::getRecords($sql);
	}	
	
	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_EARNINGS ." 			
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
		$gee = new G_Employee_Earnings();
		$gee->setId($row['id']);
		$gee->setCompanyStructureId($row['company_structure_id']);
		$gee->setObjectId($row['object_id']);
		$gee->setObjectDescription($row['object_description']);
		$gee->setAppliedTo($row['applied_to']);
		$gee->setTitle($row['title']);
		$gee->setEarningType($row['earning_type']);								
		$gee->setPercentage($row['percentage']);				
		$gee->setPercentageMultiplier($row['percentage_multiplier']);				
		$gee->setAmount($row['amount']);				
		$gee->setPayrollPeriodId($row['payroll_period_id']);				
		$gee->setDescription($row['description']);
		$gee->setStatus($row['status']);
		$gee->setIsTaxable($row['is_taxable']);
		$gee->setRemarks($row['remarks']);		
		$gee->setIsArchive($row['is_archive']);					
		$gee->setDateCreated($row['date_created']);								
		return $gee;
	}
}
?>