<?php
class G_Employee_Deductions_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 
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
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 
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
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 
			WHERE is_archive =" . Model::safeSql(G_Employee_Deductions::YES) . "			
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
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 
			WHERE is_archive =" . Model::safeSql(G_Employee_Deductions::NO) . "			
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
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 
			WHERE status =" . Model::safeSql(G_Employee_Deductions::PENDING) . "			
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
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 
			WHERE status =" . Model::safeSql(G_Employee_Deductions::APPROVED) . "			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}
	
	public static function findAllByStatus($status,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 
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
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 
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
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . "			
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
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . "	
				AND is_archive =" . Model::safeSql(G_Employee_Deductions::NO) . " 		
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
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . " AND status =" . Model::safeSql(G_Employee_Deductions::PENDING) . "			
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
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . " 
				AND status =" . Model::safeSql(G_Employee_Deductions::PENDING) . "
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
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . " 
				AND status =" . Model::safeSql(G_Employee_Deductions::PENDING) . "
				AND company_structure_id =" . Model::safesql($company_structure_id) . "
				AND is_archive =" . Model::safeSql(G_Employee_Deductions::NO) . "			
			".$order_by."
			".$limit."		
		";		
		return self::getRecords($sql);
	}
	
	public static function findAllApprovedByPayrollPeriodIdAndCompanyStructureIdAndIsNotArchive($payroll_period_id,$company_structure_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . " 
				AND status =" . Model::safeSql(G_Employee_Deductions::APPROVED) . "
				AND company_structure_id =" . Model::safesql($company_structure_id) . "
				AND is_archive =" . Model::safeSql(G_Employee_Deductions::NO) . "			
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
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . " 				
				AND company_structure_id =" . Model::safesql($company_structure_id) . "
				AND is_archive =" . Model::safeSql(G_Employee_Deductions::YES) . "			
			".$order_by."
			".$limit."		
		";		
		return self::getRecords($sql);
	}
	
	public static function findAllByEmployeeId($employee_id,$order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 
			WHERE employee_id =" . Model::safeSql($employee_id) . "			
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
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 			
			".$order_by."
			".$limit."		
		";
		
		return self::getRecords($sql);
	}

	public static function findAllByPayrollPeriodIdAndIsMovedDeduction($payroll_period_id) {
		
		$sql = "
			SELECT * 
			FROM " . G_EMPLOYEE_DEDUCTIONS ." 			
			WHERE payroll_period_id =" . Model::safeSql($payroll_period_id) . "
            	AND is_moved_deduction = 1
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
		$gee = new G_Employee_Deductions();
		$gee->setId($row['id']);
		$gee->setEmployeeId($row['employee_id']);
		$gee->setDepartmentSectionId($row['department_section_id']);
		$gee->setEmploymentStatusId($row['employment_status_id']);
		$gee->setCompanyStructureId($row['company_structure_id']);
		$gee->setTitle($row['title']);		
		$gee->setRemarks($row['remarks']);				
		$gee->setAmount($row['amount']);				
		$gee->setPayrollPeriodId($row['payroll_period_id']);				
		$gee->setApplyToAllEmployee($row['apply_to_all_employee']);				
		$gee->setStatus($row['status']);				
		$gee->setTaxable($row['is_taxable']);
		$gee->setFrequencyId($row['frequency_id']);				
		$gee->setIsArchive($row['is_archive']);					
		$gee->setDateCreated($row['date_created']);	
		$gee->setIsMovedDeduction($row['is_moved_deduction']);								
		return $gee;
	}
}
?>