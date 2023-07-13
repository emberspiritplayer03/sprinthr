<?php
class G_Employee_Benefits_Main_Helper {

    public static function isIdExist(G_Employee_Benefits_Main $gebm) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_BENEFITS_MAIN ."
			WHERE id = ". Model::safeSql($gebm->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_BENEFITS_MAIN			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlFindByEmployeeDepartmentIdAndBenefitIdAndAppliedTo( $object_id = 0, $benefit_id = 0, $applied_to, $fields = array() ){
		if( !empty( $fields ) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_BENEFITS_MAIN . "
			WHERE benefit_id =" . Model::safeSql($benefit_id) . "
				AND applied_to =" . Model::safeSql($applied_to) . "
				AND employee_department_id =" . Model::safeSql($object_id) . "
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlIsBenfitIdAssignedToAllEmployee( $benefit_id = 0 ){
		$sql = "
			SELECT COUNT(id)AS total
			FROM " . G_EMPLOYEE_BENEFITS_MAIN . "
			WHERE benefit_id =" . Model::safeSql($benefit_id) . "
				AND applied_to =" . Model::safeSql(Employee_Benefits_Main::ALL_EMPLOYEE) . "
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);

		if( $row['total'] > 0 ){
			$return = true;
		}else{
			$return = false;
		}

		return $return;
	}

	public static function sqlIsBenfitIdAssignedToEmployee( $benefit_id = 0, $employee_id = 0 ){
		$sql = "
			SELECT COUNT(id)AS total
			FROM " . G_EMPLOYEE_BENEFITS_MAIN . "
			WHERE benefit_id =" . Model::safeSql($benefit_id) . "				
				AND applied_to =" . Model::safeSql(Employee_Benefits_Main::ALL_EMPLOYEE) . "
				OR (benefit_id =" . Model::safeSql($benefit_id) . " AND employee_department_id =" . Model::safeSql($employee_id) . " AND applied_to =" . Model::safeSql(Employee_Benefits_Main::EMPLOYEE) . ")
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		
		if( $row['total'] > 0 ){
			$return = true;
		}else{
			$return = false;
		}

		return $return;
	}

	public static function sqlCountTotalEmployeesEnrolledToBenefit($benefit_id = 0) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_BENEFITS_MAIN . "
			WHERE benefit_id =" . Model::safeSql($benefit_id) . "
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlCountTotalEnrolledToBenefit($benefit_id = 0) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_BENEFITS_MAIN . "
			WHERE benefit_id =" . Model::safeSql($benefit_id) . "
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlAllEmployeeBenefitsByEmployeeId($employee_id = 0, $order_by = "", $limit = ""){				
		if( !empty($order_by) ){
			$order_by = "ORDER BY {$order_by}";
		}

		$sql = "
			SELECT eb.id, b.code, b.name, b.amount, eb.applied_to, b.is_taxable 
			FROM " . G_EMPLOYEE_BENEFITS_MAIN . " eb			
				LEFT JOIN " . G_SETTINGS_EMPLOYEE_BENEFITS . " b ON eb.benefit_id = b.id
			WHERE 
				(
					eb.employee_department_id =" . Model::safeSql($employee_id) . "				
					AND eb.applied_to =" . Model::safeSql(Employee_Benefits_Main::EMPLOYEE) . "
				)OR(
					eb.applied_to =" . Model::safeSql(Employee_Benefits_Main::ALL_EMPLOYEE) . "
				)
			{$order_by}
			{$limit}
		";		
						
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlAllEmployeeBenefitsByObjectIdAndAppliedToAndCriteriaDepre($object_id = 0, $applied_to = '', $criteria = '', $cutoff = 0, $order_by = "", $limit = ""){				
		if( !empty($order_by) ){
			$order_by = "ORDER BY {$order_by}";
		}

		if( $criteria == G_Employee_Benefits_Main::NO_CRITERIA ){
			$sql_criteria = " ";
		}elseif( $criteria != '' ){
			//$sql_criteria = " AND eb.criteria LIKE '%{$criteria}%'";
			$sql_criteria = " AND eb.criteria =" . Model::safeSql($criteria);
		}else{
			$sql_criteria = " AND eb.criteria = ''";
		}

		if( $cutoff > 0 ){
			$sql_cutoff ="AND b.cutoff IN({$cutoff})";
		}else{
			$sql_cutoff ="";
		}
		
		$sql = "
			SELECT b.code, b.name, b.amount, b.is_taxable 
			FROM " . G_EMPLOYEE_BENEFITS_MAIN . " eb			
				LEFT JOIN " . G_SETTINGS_EMPLOYEE_BENEFITS . " b ON eb.benefit_id = b.id
			WHERE 
				eb.employee_department_id =" . Model::safeSql($object_id) . "				
				AND eb.applied_to =" . Model::safeSql($applied_to) . "
				{$sql_criteria}
				{$sql_cutoff}
			{$order_by}
			{$limit}
		";				
		$sql;
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlAllEmployeeBenefitsByObjectIdAndAppliedToAndCriteria($object_id = 0, $applied_to = '', $cutoff = 0, $criteria = "", $order_by = "", $limit = ""){				
		if( !empty($order_by) ){
			$order_by = "ORDER BY {$order_by}";
		}

		$sql_criteria = " ";
		/*if( $criteria == G_Employee_Benefits_Main::NO_CRITERIA ){
			$sql_criteria = " ";
		}elseif( $criteria != '' ){
			//$sql_criteria = " AND eb.criteria LIKE '%{$criteria}%'";
			$sql_criteria = " AND eb.criteria != ''";
		}else{
			$sql_criteria = " AND eb.criteria = ''";
		}*/

		if( $cutoff > 0 ){
			$sql_cutoff ="AND b.cutoff IN({$cutoff})";
		}else{
			$sql_cutoff ="";
		}
		
		$sql = "
			SELECT b.id, b.code, b.name, b.amount, b.is_taxable, eb.criteria, eb.custom_criteria, b.multiplied_by, eb.excluded_emplooyee_id
			FROM " . G_EMPLOYEE_BENEFITS_MAIN . " eb			
				LEFT JOIN " . G_SETTINGS_EMPLOYEE_BENEFITS . " b ON eb.benefit_id = b.id
			WHERE 
				eb.employee_department_id =" . Model::safeSql($object_id) . "				
				AND eb.applied_to =" . Model::safeSql($applied_to) . "
				AND b.is_archive='No'
				{$sql_criteria}
				{$sql_cutoff}
			{$order_by}
			{$limit}
		";		
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlAllEmployeeBenefitsByEmployeeIdAndCompanyStructureId($employee_id = 0, $company_structure_id = 0, $order_by = "", $limit = ""){				
		if( !empty($order_by) ){
			$order_by = "ORDER BY {$order_by}";
		}

		$sql = "
			SELECT eb.id, b.code, b.name, b.amount, eb.applied_to, b.is_taxable 
			FROM " . G_EMPLOYEE_BENEFITS_MAIN . " eb			
				LEFT JOIN " . G_SETTINGS_EMPLOYEE_BENEFITS . " b ON eb.benefit_id = b.id
			WHERE eb.company_structure_id =" . Model::safeSql($company_structure_id) . " 
				AND (
						(
							eb.employee_department_id =" . Model::safeSql($employee_id) . "				
							AND eb.applied_to =" . Model::safeSql(Employee_Benefits_Main::EMPLOYEE) . "
						)OR(
							eb.applied_to =" . Model::safeSql(Employee_Benefits_Main::ALL_EMPLOYEE) . "
						)
						
				)
			{$order_by}
			{$limit}
		";						
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlAllEmployeesEnrolledToBenefit($benefit_id, $order_by = "", $limit = ""){				
		if( !empty($order_by) ){
			$order_by = "ORDER BY {$order_by}";
		}

		$sql = "
			SELECT b.id,
				IF( b.applied_to =" . Model::safeSql(Employee_Benefits_Main::ALL_EMPLOYEE) . ",'Applied to All Employees', CONCAT(e.lastname, ', ', e.firstname) )AS remarks
			FROM " . G_EMPLOYEE_BENEFITS_MAIN ." b
				LEFT JOIN " . EMPLOYEE . " e ON b.employee_department_id = e.id
			WHERE b.benefit_id =" . Model::safeSql($benefit_id) . "
			{$order_by}
			{$limit}
		";				
		
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlAllDataByBenefitId($benefit_id, $order_by = "", $limit = ""){				
		if( !empty($order_by) ){
			$order_by = "ORDER BY {$order_by}";
		}

		$sql = "
			SELECT b.id, b.description, b.criteria, b.custom_criteria, b.applied_to, b.excluded_emplooyee_id
			FROM " . G_EMPLOYEE_BENEFITS_MAIN ." b				
			WHERE b.benefit_id =" . Model::safeSql($benefit_id) . "
			{$order_by}
			{$limit}
		";				
		
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function sqlSearchEmployeesByKeywordNotEnrolledToBenefit($benefit_id = 0, $keyword = ""){				
		$sql = "
			SELECT e.id AS employee_id, CONCAT(e.lastname, ',', e.firstname)AS employee_name
			FROM " . EMPLOYEE . " e 				
			WHERE e.id NOT IN (
				SELECT b.employee_department_id 
				FROM " . G_EMPLOYEE_BENEFITS_MAIN . " b 
				WHERE b.applied_to =" . Model::safeSql(Employee_Benefits_Main::EMPLOYEE) . "
				AND  b.benefit_id =" . Model::safeSql($benefit_id) . "				
			) AND (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ") 
			  AND (e.firstname LIKE '%{$keyword}%' OR e.lastname LIKE '%{$keyword}%') 
		";
		
		$result = Model::runSql($sql,true);		
		return $result;	
	}

	public static function sqlSearchDepartmentSectionByKeywordNotEnrolledToBenefit($benefit_id = 0, $keyword = ""){				
		$sql = "
			SELECT cs.id AS dept_section_id, cs.title
			FROM " . COMPANY_STRUCTURE . " cs			
			WHERE cs.id NOT IN (
				SELECT b.employee_department_id 
				FROM " . G_EMPLOYEE_BENEFITS_MAIN . " b 
				WHERE b.applied_to =" . Model::safeSql(Employee_Benefits_Main::DEPARTMENT) . "
				AND  b.benefit_id =" . Model::safeSql($benefit_id) . "				
			) AND (cs.is_archive =" . Model::safeSql(G_Company_Structure::NO) . ") 
			  AND (cs.title LIKE '%{$keyword}%') 
		";
		
		$result = Model::runSql($sql,true);		
		return $result;	
	}

	public static function sqlSearchEmploymentStatusByKeywordNotEnrolledToBenefit($benefit_id = 0, $keyword = ""){				
		/*$sql = "
			SELECT es.id AS employment_status_id, es.status
			FROM " . EMPLOYMENT_STATUS . " es			
			WHERE es.id NOT IN (
				SELECT b.employee_department_id 
				FROM " . G_EMPLOYEE_BENEFITS_MAIN . " b 
				WHERE b.applied_to =" . Model::safeSql(Employee_Benefits_Main::EMPLOYMENT_STATUS) . "
				AND  b.benefit_id =" . Model::safeSql($benefit_id) . "				
			) AND (es.status LIKE '%{$keyword}%') 
		";*/

		$sql = "
			SELECT es.id AS employment_status_id, es.status
			FROM " . EMPLOYMENT_STATUS . " es			
			WHERE es.status LIKE '%{$keyword}%'
		";		

		$result = Model::runSql($sql,true);		
		return $result;	
	}

	/**
	 * 
	 * @param string benefit_name
	 * @param int employee_id
	 * @return array
	*/
	public static function sqlGetEmployeeBenefitsByNameAndEmployeeId($search = '', $employee_id = 0){				
		$sql = "
			SELECT sebm.amount
			FROM " . G_SETTINGS_EMPLOYEE_BENEFITS . " sebm 
				LEFT JOIN " . G_EMPLOYEE_BENEFITS_MAIN . " ebm ON sebm.id = ebm.benefit_id
			WHERE sebm.name LIKE '%{$search}%' 
				AND ebm.employee_department_id =" . Model::safeSql($employee_id) . "
				AND ebm.applied_to =" . Model::safeSql(Employee_Benefits_Main::EMPLOYEE) . "
		";
		
		$result = Model::runSql($sql,true);		
		return $result;	
	}
}
?>