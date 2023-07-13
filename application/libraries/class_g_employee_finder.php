<?php
class G_Employee_Finder {
    public static function getEmployeeFields() {
        return "e.id, e.company_structure_id, e.hash, e.employee_device_id, e.department_company_structure_id, e.employee_code, e.photo, e.firstname, e.salutation, e.lastname, e.middlename,e.extension_name, e.nickname, e.birthdate, e.gender, e.marital_status, e.nationality,e.number_dependent, e.sss_number, e.tin_number,e.pagibig_number,e.philhealth_number,is_tax_exempted,e.hired_date,e.resignation_date, e.endo_date, e.leave_date, e.eeo_job_category_id, e.terminated_date,e.employee_status_id,e.employment_status_id,e.e_is_archive,e.is_confidential, e.section_id, e.week_working_days, e.year_working_days,e.frequency_id,e.cost_center, e.project_site_id";
    }

	public static function searchActiveEmployeeByFirstnameAndLastnameWithTerminated($query) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ") 
			AND (e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::RESIGNED) . ")
			AND (e.firstname LIKE '%{$query}%' OR e.lastname LIKE '%{$query}%' OR e.employee_code LIKE '%{$query}%') 
		";		
		return self::getRecords($sql);	
	}	


	//for monthly payslip
	public static function findByMonthlyPayslipPeriodOrderByDepartmentCompanyStructure($start_date, $end_date, $is_confidential_qry = "") {

		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee_monthly_payslip p, g_employee e			
			WHERE (p.period_start = ". Model::safeSql($start_date) ." AND p.period_end = ". Model::safeSql($end_date) .")
			AND p.employee_id = e.id 
			{$is_confidential_qry}
			ORDER BY 
				(SELECT title FROM g_company_structure WHERE id = e.department_company_structure_id AND type = 'Department'),
				(SELECT title FROM g_company_structure WHERE id = e.section_id AND type = 'Section'),
				e.lastname ASC			
		";		
		
		return self::getRecords($sql);
	}


	public static function searchActiveEmployeeByFirstnameAndLastnameAndResigned($query) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ") 
			AND (e.employee_status_id = " . Model::safeSql(G_Settings_Employee_Status::RESIGNED) . ")
			AND (e.resignation_date <> '0000-00-00') 
			AND (e.firstname LIKE '%{$query}%' OR e.lastname LIKE '%{$query}%' OR e.employee_code LIKE '%{$query}%') 
		";		
		return self::getRecords($sql);	
	}


	public static function fidAllEmployeesByDepartmentByEmploymentStatusByField($department, $employement_status,$search, $is_additional_qry = '') {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM  g_employee e 
			WHERE e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " 
			{$is_additional_qry} 
		";		

		if($search != "all") {
			$sql .="
			AND	(
				e.firstname LIKE '%{$search}%' 
				OR e.lastname LIKE '%{$search}%'
				OR e.employee_code =" . Model::safeSql($search) . "
				OR e.marital_status LIKE '%{$search}%'
				) 
			";
		}

		if($department != "all") {
			$sql .="
			AND	e.department_company_structure_id = " . Model::safeSql($department) . " 
			";
		}

		if($employement_status != "all") {
			$sql .="
			AND	e.employee_status_id = " . Model::safeSql($employement_status) . " 
			";
		}


		return self::getRecords($sql);	
	}	

	public static function fidAllEmployeesByDepartmentByEmploymentStatusByFieldWithEmployeeTags($department, $employement_status,$search, $is_additional_qry = '',$project_site_id) {
		$sql = "
			SELECT ". self::getEmployeeFields() .",et.tags
			FROM  g_employee e 
				LEFT JOIN g_employee_tags et ON e.id = et.employee_id 
			WHERE e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " 
			{$is_additional_qry} 
		";		

		if($search != "all") {
			$sql .="
			AND	(
				e.firstname LIKE '%{$search}%' 
				OR e.lastname LIKE '%{$search}%'
				OR e.employee_code =" . Model::safeSql($search) . "
				OR e.marital_status LIKE '%{$search}%'
				) 
			";
		}

		if($department != "all") {
			$sql .="
			AND	e.department_company_structure_id = " . Model::safeSql($department) . " 
			";
		}


		if($employement_status != "all") {
			$sql .="
			AND	e.employment_status_id = " . Model::safeSql($employement_status) . " 
			";
		}

		if($project_site_id != 'all'){

			$sql .="
			AND	e.project_site_id = " . Model::safeSql($project_site_id) . " 
			";
		}

		return self::getRecords($sql);	
	}	
	
	public static function findAllByGroup(IGroup $g) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM ". EMPLOYEE ." e, ". G_EMPLOYEE_SUBDIVISION_HISTORY ." g
			WHERE g.company_structure_id = ". Model::safeSql($g->getId()) ."
			AND e.id = g.employee_id
			ORDER BY e.lastname
		";

		return self::getRecords($sql);
	}

	public static function findAllActiveRegularEmployees() {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM ". EMPLOYEE ." e
			WHERE e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
				AND e.employment_status_id = 3
			ORDER BY id ASC
		";

		return self::getRecords($sql);
	}

	public static function findAllActiveEmployees() {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM ". EMPLOYEE ." e
			WHERE e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "				
			ORDER BY id ASC
		";

		return self::getRecords($sql);
	}

	public static function findAllActiveEmployeesWithSelectedEmployeeFields($fields = "") {
		if($fields == '') {
			$fields = "e.id,e.firstname,e.lastname";
		}

		$sql = "
			SELECT ". $fields ."
			FROM ". EMPLOYEE ." e
			WHERE e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "				
			ORDER BY id ASC
		";

		$records = self::getRecords($sql);

		if($records) {

			$records_array = array();
			foreach($records as $r_key => $r) {
				$records_array[$r_key]['id']   = $r->getId();
				$records_array[$r_key]['name'] = $r->getFirstName() . " " . $r->getLastName();
			}

		}

		return $records_array;
	}	

	public static function findAllEmployeesWithNoRestDayByDateAndDepartmentSectionId( $date = '', $department_section_id = 0 ) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM " . EMPLOYEE . " e
			WHERE (e.department_company_structure_id = " . Model::safeSql($department_section_id) . " OR e.section_id = " . Model::safeSql($department_section_id) . ")
				AND (
					SELECT COUNT(rd.id)
					FROM " . G_EMPLOYEE_RESTDAY . " rd
					WHERE rd.employee_id = e.id 
						AND rd.date = " . Model::safeSql($date) . "
			) = 0
				AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
		";

		return self::getRecords($sql);
	}

	public static function findAllEmployeesByDepartmentSectionId($department_section_id = 0 ) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM " . EMPLOYEE . " e
			WHERE (e.department_company_structure_id = " . Model::safeSql($department_section_id) . " OR e.section_id = " . Model::safeSql($department_section_id) . ")				
				AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
		";		
		return self::getRecords($sql);
	}
	
	public static function findAllInArrayId($array_id) {
		//$array_id = "1,3,4"; <- coma separated
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE e.id IN(". $array_id .") AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "			
		";		
		return self::getRecords($sql);
	}
	
	public static function findAllByHiredDateRange($start_date, $end_date) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e			
			WHERE (e.hired_date >= ". Model::safeSql($start_date) ." AND e.hired_date <= ". Model::safeSql($end_date) .")
			ORDER BY e.lastname
		";		

		return self::getRecords($sql);
	}
	
	public static function findByPayslipPeriod($start_date, $end_date, $is_confidential_qry = "") {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee_payslip p, g_employee e			
			WHERE (p.period_start = ". Model::safeSql($start_date) ." AND p.period_end = ". Model::safeSql($end_date) .")
			AND p.employee_id = e.id 
			".$is_confidential_qry."
			ORDER BY e.lastname
		";
		return self::getRecords($sql);
	}

	public static function findByPayslipPeriodWithOptions($start_date, $end_date, $is_confidential_qry = "", $order_by = "") {

		$sql = "
				SELECT ". self::getEmployeeFields() ."
				FROM g_employee_payslip p, g_employee e			
				WHERE (p.period_start = ". Model::safeSql($start_date) ." AND p.period_end = ". Model::safeSql($end_date) .")
				AND p.employee_id = e.id 

				{$is_confidential_qry}
				{$order_by}
			";
		
		return self::getRecords($sql);
	}

	public static function findByPayslipPeriodOrderByDepartmentCompanyStructure($start_date, $end_date, $is_confidential_qry = "") {

		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee_payslip p, g_employee e			
			WHERE (p.period_start = ". Model::safeSql($start_date) ." AND p.period_end = ". Model::safeSql($end_date) .")
			AND p.employee_id = e.id 
			{$is_confidential_qry}
			ORDER BY 
				(SELECT title FROM g_company_structure WHERE id = e.department_company_structure_id AND type = 'Department'),
				(SELECT title FROM g_company_structure WHERE id = e.section_id AND type = 'Section'),
				e.lastname ASC			
		";		
		/*$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee_payslip p, g_employee e			
			WHERE (p.period_start = ". Model::safeSql($start_date) ." AND p.period_end = ". Model::safeSql($end_date) .")
			AND p.employee_id = e.id 
			{$is_confidential_qry}
			ORDER BY 
				(SELECT title FROM g_company_structure WHERE id = e.department_company_structure_id ASC) ASC
			e.department_company_structure_id DESC
		";*/
		
		return self::getRecords($sql);
	}

	public static function findByWeeklyPayslipPeriodOrderByDepartmentCompanyStructure($start_date, $end_date, $is_confidential_qry = "") {

		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee_weekly_payslip p, g_employee e			
			WHERE (p.period_start = ". Model::safeSql($start_date) ." AND p.period_end = ". Model::safeSql($end_date) .")
			AND p.employee_id = e.id 
			{$is_confidential_qry}
			ORDER BY 
				(SELECT title FROM g_company_structure WHERE id = e.department_company_structure_id AND type = 'Department'),
				(SELECT title FROM g_company_structure WHERE id = e.section_id AND type = 'Section'),
				e.lastname ASC			
		";		
		
		return self::getRecords($sql);
	}

	public static function findByPayslipPeriodAndCustomQry($start_date, $end_date, $is_confidential_qry = "", $custom_qry = "") {
		if( !empty( $custom_qry ) ){
			$sql_custom_qry = " AND ({$custom_qry})";
		}else{
			$sql_custom_qry = "";
		}

		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee_payslip p, g_employee e			
			WHERE (p.period_start = ". Model::safeSql($start_date) ." AND p.period_end = ". Model::safeSql($end_date) .")
			AND p.employee_id = e.id 
			{$is_confidential_qry}
			{$sql_custom_qry}
			ORDER BY e.lastname
		";		
		return self::getRecords($sql);
	}

	public static function findByEmployeeIdPayslipPeriod($employee_id, $start_date, $end_date) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e		
				LEFT JOIN g_employee_payslip p 
				ON 	p.employee_id = e.id
			WHERE (p.period_start = ". Model::safeSql($start_date) ." AND p.period_end = ". Model::safeSql($end_date) .")
			AND e.id = ". Model::safeSql($employee_id) ."
			ORDER BY e.lastname
		";
		return self::getRecords($sql);
	}
	
	public static function findByPayslipPeriodAndIsNotArchive($start_date, $end_date) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee_payslip p, g_employee e			
			WHERE (p.period_start = ". Model::safeSql($start_date) ." AND p.period_end = ". Model::safeSql($end_date) .")
			AND p.employee_id = e.id
			AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
			ORDER BY e.lastname
		";
		return self::getRecords($sql);
	}
	
	public static function findByPayslipDateRange($start_date, $end_date,$limit = '') {
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee_payslip p, g_employee e			
			WHERE (p.period_start >= " . Model::safeSql($start_date) . " AND p.period_end <= " . Model::safeSql($end_date) . ") 
			AND (p.employee_id = e.id)
			GROUP BY e.id 
			ORDER BY e.lastname ASC 			 
			" . $limit . "
		";						
		return self::getRecords($sql);
	}
	
	public static function findByPayslipDateRangeIsNotArchive($start_date, $end_date,$limit = '') {
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee_payslip p, g_employee e			
			WHERE (p.period_start >= " . Model::safeSql($start_date) . " AND p.period_end <= " . Model::safeSql($end_date) . ") 
			AND (p.employee_id = e.id)
			AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
			GROUP BY e.id 
			ORDER BY e.lastname ASC 			 
			" . $limit . "
		";						
		return self::getRecords($sql);
	}

    public static function findByEmployeeCodeWithLessFields($employee_code) {
        $sql = "
			SELECT e.id, e.firstname, e.lastname
			FROM g_employee e
			WHERE e.employee_code = ". Model::safeSql($employee_code) ."
			LIMIT 1
		";
        return self::getRecord($sql);
    }
	
	public static function findByEmployeeCode($employee_code) {
		if (!$employee_code) {
			return false;	
		}
		
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE e.employee_code = '{$employee_code}'
			LIMIT 1		
		";
		
		return self::getRecord($sql);
	}

	public static function findEmployeeCodeByEmployeeId($employee_id) {
		if (!$employee_id) {
			return false;	
		}
		
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE e.id = '{$employee_id}'
			LIMIT 1		
		";
		
		return self::getRecord($sql);
	}
	
	public static function searchActiveByExactFirstnameAndLastnameAndEmployeeCode($query) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e, g_employee_job_history eh		
			WHERE (e.firstname =" . Model::safeSql($query) . " OR e.lastname =" . Model::safeSql($query) . " OR e.employee_code =" . Model::safeSql($query) . ")
			AND (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ") 
			AND (e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::RESIGNED) . " 
				  AND  e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::TERMINATED) . 
				 ")
			AND e.id = eh.employee_id
			AND 
			(
				(
					". Model::safeSql(Tools::getGmtDate('Y-m-d')) ." >= eh.start_date
					AND
					". Model::safeSql(Tools::getGmtDate('Y-m-d')) ." <= eh.end_date
				)
				OR
				(
					". Model::safeSql(Tools::getGmtDate('Y-m-d')) ." >= eh.start_date
					AND
					eh.end_date = ''
				)
			)
			ORDER BY e.lastname		
		";	
		
		return self::getRecords($sql);	
	}	
	
	public static function searchActiveByExactFirstnameAndLastnameAndEmployeeCodeWithCriteriaTerminationDate($query,$ar_date, $is_confidential_qry = "", $employee_ids_qry = "") {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e, g_employee_job_history eh		
			WHERE ".$is_confidential_qry.$employee_ids_qry." (e.firstname =" . Model::safeSql($query) . " OR e.lastname =" . Model::safeSql($query) . " OR e.employee_code =" . Model::safeSql($query) . ")
			AND (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ") 
			AND (e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::RESIGNED) . " 
				  AND  e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::TERMINATED) . 
				 "
				  OR(
				  	e.terminated_date >= " . Model::safeSql($ar_date['from']) . "					
				  )
				 )
			AND e.id = eh.employee_id
			AND 
			(
				(
					". Model::safeSql(Tools::getGmtDate('Y-m-d')) ." >= eh.start_date
					AND
					". Model::safeSql(Tools::getGmtDate('Y-m-d')) ." <= eh.end_date
				)
				OR
				(
					". Model::safeSql(Tools::getGmtDate('Y-m-d')) ." >= eh.start_date
					AND
					eh.end_date = ''
				)
			) 
			
			ORDER BY e.lastname		
		";	
		//echo $sql;
		return self::getRecords($sql);	
	}	
	
	public static function findAllActiveByPeriod($from, $to) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e, g_employee_job_history eh
			WHERE e.id = eh.employee_id 
			AND 
			e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " 
			AND 
			(
				(
					eh.start_date >= ". Model::safeSql($from) ."
					AND
					eh.end_date <= ". Model::safeSql($to) ."
				)
				OR
				(
					eh.start_date >= ". Model::safeSql($from) ."
					AND
					eh.end_date = ''
				)
			)
			ORDER BY e.lastname
		";
		
		return self::getRecords($sql);
	}
	
	function findAllActiveWithTerminated($date = '',$limit="") {
		if ($date == '') {
			$date = Tools::getGmtDate('Y-m-d');
		}
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e, g_employee_job_history eh
			WHERE (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ")
			AND (terminated_date = '0000-00-00' OR ".Model::safeSql($date)." BETWEEN hired_date AND terminated_date)
			AND (e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::RESIGNED) . ")
			AND (e.id = eh.employee_id 
			OR
			(
				(
					". Model::safeSql($date) ." >= eh.start_date
					AND
					". Model::safeSql($date) ." <= eh.end_date
				)
				OR
				(
					". Model::safeSql($date) ." >= eh.start_date
					AND
					eh.end_date = ''
				)
			))
			ORDER BY e.lastname
			" . $limit . "
		";	

		return self::getRecords($sql);	
	}

	public static function findAll($order_by = '', $limit = '') {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM g_employee
			".$order_by."
			".$limit."	
		";
		return self::getRecords($sql);
	}

    public static function findAllActive($date = '', $limit = '', $is_confidential_qry = "", $employee_ids_qry = "") {
        if ($date == '') {
            $date = Tools::getGmtDate('Y-m-d');
        }
        $limit = ($limit != '')? 'LIMIT ' . $limit : '';
        $sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE ".$is_confidential_qry.$employee_ids_qry."
			    (
			        ". Model::safeSql($date) ." >= e.hired_date
			        
                ) 
                AND e.e_is_archive = ".Model::safeSql(G_Employee::NO)."
				
			ORDER BY e.lastname
			" . $limit . "
		";
        /*$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE ".$is_confidential_qry."
			    (
			        ". Model::safeSql($date) ." >= e.hired_date
			        AND e.leave_date = ''
                )
                OR
                (
                    ". Model::safeSql($date) ." >= e.hired_date
                    AND ". Model::safeSql($date) ." < e.leave_date
                ) 
				
			ORDER BY e.lastname
			" . $limit . "
		";*/

        return self::getRecords($sql);

    }

    public static function findAllActiveInCutoff($date = '', $limit = '', $is_confidential_qry = "", $employee_ids_qry = "") {
        if ($date == '') {
            $date = Tools::getGmtDate('Y-m-d');
        }
        $limit = ($limit != '')? 'LIMIT ' . $limit : '';
        $sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE ".$is_confidential_qry.$employee_ids_qry."
			    (
			        ". " e.hired_date <= " . Model::safeSql($date) . "
			        
                ) 
                AND e.e_is_archive = ".Model::safeSql(G_Employee::NO)."
				
			ORDER BY e.lastname
			" . $limit . "
		";
		
        /*$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE ".$is_confidential_qry."
			    (
			        ". Model::safeSql($date) ." >= e.hired_date
			        AND e.leave_date = ''
                )
                OR
                (
                    ". Model::safeSql($date) ." >= e.hired_date
                    AND ". Model::safeSql($date) ." < e.leave_date
                ) 
				
			ORDER BY e.lastname
			" . $limit . "
		";*/

        return self::getRecords($sql);

    }

    public static function findAllActiveByDateAndIsNotResignedAndIsNotTerminated($date = '', $limit = '', $is_confidential_qry = "", $employee_ids_qry = "") {
        if ($date == '') {
            $date = Tools::getGmtDate('Y-m-d');
        }
        $limit = ($limit != '')? 'LIMIT ' . $limit : '';
        $sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE ".$is_confidential_qry.$employee_ids_qry."
			    (
			        ". Model::safeSql($date) ." >= e.hired_date
			        
                ) 
                AND e.e_is_archive = ".Model::safeSql(G_Employee::NO)."
                AND (e.resignation_date = '0000-00-00' OR e.resignation_date = '' OR (e.resignation_date <> '' AND e.resignation_date < " . Model::safeSql($date) . ") ) AND (e.terminated_date = '0000-00-00' OR e.terminated_date = '' OR (e.terminated_date <> '' AND e.terminated_date < " . Model::safeSql($date) . ") )				
			ORDER BY e.lastname
			" . $limit . "
		";

        /*$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE ".$is_confidential_qry."
			    (
			        ". Model::safeSql($date) ." >= e.hired_date
			        AND e.leave_date = ''
                )
                OR
                (
                    ". Model::safeSql($date) ." >= e.hired_date
                    AND ". Model::safeSql($date) ." < e.leave_date
                ) 
				
			ORDER BY e.lastname
			" . $limit . "
		";*/

        return self::getRecords($sql);

    }

	/*
	 * REPLACED
	 */
	public static function findAllActiveOLD($date = '',$limit="") {
		if ($date == '') {
            $date = Tools::getGmtDate('Y-m-d');
        }
        $limit = ($limit!='')? 'LIMIT ' . $limit : '';
        $sql = "
			SELECT DISTINCT e.id, ". self::getEmployeeFields() ."
			FROM g_employee e, g_employee_job_history eh
			WHERE  (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ")
			AND (e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::TERMINATED) . "
				 AND e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::RESIGNED) . ")
			AND (e.id = eh.employee_id
			OR
			(
				(
					". Model::safeSql($date) ." >= eh.start_date
					AND
					". Model::safeSql($date) ." <= eh.end_date
				)
				OR
				(
					". Model::safeSql($date) ." >= eh.start_date
					AND
					eh.end_date = ''
				)
			))
			ORDER BY e.lastname
			" . $limit . "

		";

        return self::getRecords($sql);
	}

	public static function findAllActiveByIdAndDate($id, $date = '', $limit = '', $is_confidential_qry = "") {
        if ($date == '') {
            $date = Tools::getGmtDate('Y-m-d');
        }
        $limit = ($limit != '')? 'LIMIT ' . $limit : '';
        $sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE ".$is_confidential_qry." 
				e.id IN (".$id.") AND
			    (
			        ". Model::safeSql($date) ." >= e.hired_date
                ) 
				AND e.e_is_archive = ".Model::safeSql(G_Employee::NO)."
				
			ORDER BY e.lastname
			" . $limit . "
		";

        return self::getRecords($sql);

    }

    public static function findAllActiveInCutoffByIdAndDate($id, $date = '', $limit = '', $is_confidential_qry = "", $employee_ids_qry ="") {
        if ($date == '') {
            $date = Tools::getGmtDate('Y-m-d');
        }
        $limit = ($limit != '')? 'LIMIT ' . $limit : '';
        $sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE ".$is_confidential_qry.$employee_ids_qry." 
				e.id IN (".$id.") AND
			    (
			        ". " e.hired_date <= " . Model::safeSql($date) . "
                ) 
				AND e.e_is_archive = ".Model::safeSql(G_Employee::NO)."
				
			ORDER BY e.lastname
			" . $limit . "
		";

        return self::getRecords($sql);

    }
	
	public static function findAllActiveByDate($date, $is_confidential_qry = "") {
		return self::findAllActive($date,"", $is_confidential_qry);
	}

	public static function findAllEmployeesActiveByDateAndIsNotResignedAndIsNotTerminated($date, $is_confidential_qry = "", $employee_ids_qry = "") {
		return self::findAllActiveByDateAndIsNotResignedAndIsNotTerminated($date,"", $is_confidential_qry, $employee_ids_qry);
	}

	public static function searchByFirstnameAndLastname($query) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ") 
			AND (e.firstname LIKE '%{$query}%' OR e.lastname LIKE '%{$query}%') 
		";		
		
		return self::getRecords($sql);	
	}

	public static function searchByFirstnameAndLastnameAndNotInAllowedIp($query) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ") 
			AND (e.firstname LIKE '%{$query}%' OR e.lastname LIKE '%{$query}%')  
			AND e.id NOT IN(
				SELECT ai.employee_id 
				FROM " . ALLOWED_IP . " ai 				
			)
		";		
		
		return self::getRecords($sql);	
	}

	public static function searchUniqueRequestorsByFirstnameAndLastname($query) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ") 
			AND (e.firstname LIKE '%{$query}%' OR e.lastname LIKE '%{$query}%') 
			AND e.id NOT IN(
				SELECT r.employee_department_group_id 
				FROM " . REQUEST_APPROVERS_REQUESTORS . " r 
				WHERE r.employee_department_group =" . Model::safeSql(G_Request_Approver_Requestor::PREFIX_EMPLOYEE) . "				
			)
		";		
		
		return self::getRecords($sql);	
	}
	
	public static function searchActiveEmployeeByFirstnameAndLastname($query) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ") 
			AND (e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::RESIGNED) . "
				  AND e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::TERMINATED). " 
			    )
			AND (e.firstname LIKE '%{$query}%' OR e.lastname LIKE '%{$query}%') 
		";		
		return self::getRecords($sql);	
	}
	
	public static function searchByFirstnameMiddlenameLastnameEmployeeCode($query) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " 
			AND  (e.firstname LIKE '%{$query}%' OR e.lastname LIKE '%{$query}%' OR e.employee_code LIKE '%{$query}%' OR e.middlename LIKE '%{$query}%')
		";	

		//echo $sql;
	
		return self::getRecords($sql);	
	}

	public static function searchByFirstnameMiddlenameLastnameEmployeeCodeDefinedFields($query, $fields = array()) {
		
		if( $fields ){
			$sql_fields = implode(",", $fields);
		}

		$sql = "
			SELECT {$sql_fields}
			FROM g_employee e
			WHERE e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " 
			AND  (e.firstname LIKE '%{$query}%' OR e.lastname LIKE '%{$query}%' OR e.employee_code LIKE '%{$query}%' OR e.middlename LIKE '%{$query}%')
		";	
		
		return self::getRecords($sql);	
	}
	
	public static function findByFirstnameLastnameBirthdate($eAr,$company_structure_id) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " AND e.company_structure_id =" . Model::safeSql($company_structure_id) . ")
				AND(e.firstname =" . Model::safeSql($eAr['firstname']) . "
					AND e.lastname =" . Model::safeSql($eAr['lastname']) . "
					AND e.birthdate =" . Model::safeSql($eAr['birthdate']) . "
					)				
			LIMIT 1	
		";			
	
		return self::getRecord($sql);	
	}

    public static function searchAllByFirstnameAndLastnameAndEmployeeCode($query, $is_confidential_qry = "", $employee_ids_qry = "") {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE ".$is_confidential_qry.$employee_ids_qry." (e.firstname LIKE '%{$query}%' OR e.lastname LIKE '%{$query}%' OR e.employee_code LIKE '%{$query}%')
			AND (e.e_is_archive = ". Model::safeSql(G_Employee::NO) .") 
			ORDER BY e.lastname
		";
		return self::getRecords($sql);
    }

    public static function searchAllByFirstnameAndLastnameAndEmployeeCodeAndDepartmentNameAndSection($query, $employee_ids_qry = "") {
		/*$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e	
			WHERE (
					e.firstname LIKE '%{$query}%' OR e.lastname LIKE '%{$query}%' OR e.employee_code LIKE '%{$query}%'
					OR
					(
						SELECT c.title 
						FROM g_company_structure c
						WHERE c.id = e.department_company_structure_id
						ORDER BY c.id DESC
						LIMIT 1
					) LIKE '%{$query}%'
					OR 
					(
						SELECT c.title 
						FROM g_company_structure c
						WHERE c.id = e.section_id
						ORDER BY c.id DESC
						LIMIT 1
					) LIKE '%{$query}%'
				)   
				AND (e.e_is_archive = 'No')
			ORDER BY e.lastname
		";*/
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e	
			WHERE ".$employee_ids_qry."
				(
					e.firstname LIKE '%{$query}%' OR e.lastname LIKE '%{$query}%' OR e.employee_code LIKE '%{$query}%'
					OR
					(
						SELECT c.title 
						FROM g_company_structure c
						WHERE c.id = e.department_company_structure_id
						ORDER BY c.id DESC
						LIMIT 1
					) =" . Model::safeSql($query) . "
					OR 
					(
						SELECT c.title 
						FROM g_company_structure c
						WHERE c.id = e.section_id
						ORDER BY c.id DESC
						LIMIT 1
					) =" . Model::safeSql($query) . "
				)   
				AND (e.e_is_archive = 'No')
			ORDER BY e.lastname
		";			
		return self::getRecords($sql);
    }

	public static function searchActiveByFirstnameAndLastnameAndEmployeeCode($query) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e, g_employee_job_history eh
			WHERE (e.firstname LIKE '%{$query}%' OR e.lastname LIKE '%{$query}%' OR e.employee_code LIKE '%{$query}%')
			AND (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ")
			AND (e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::TERMINATED) . "
				  AND e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::RESIGNED) . "
				 )
			AND (e.id = eh.employee_id) 
			AND 
			(
				(
					". Model::safeSql(Tools::getGmtDate('Y-m-d')) ." >= eh.start_date
					AND
					". Model::safeSql(Tools::getGmtDate('Y-m-d')) ." <= eh.end_date
				)
				OR
				(
					". Model::safeSql(Tools::getGmtDate('Y-m-d')) ." >= eh.start_date
					AND
					eh.end_date = ''
				)
			)
			ORDER BY e.lastname
		";
		return self::getRecords($sql);
	}	
	
	public static function searchActiveByFirstnameAndLastnameAndEmployeeCodeWithCriteriaTerminationDate($query,$ar_date) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e, g_employee_job_history eh		
			WHERE (e.firstname LIKE '%{$query}%' OR e.lastname LIKE '%{$query}%' OR e.employee_code LIKE '%{$query}%')
			AND (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . ")
			AND (e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::TERMINATED) . "
				  OR(
				  	e.terminated_date >= " . Model::safeSql($ar_date['from']) . "					
				  )
				  AND e.employee_status_id <> " . Model::safeSql(G_Settings_Employee_Status::RESIGNED) . " 
				 )  
			AND (e.id = eh.employee_id) 
			AND 
			(
				(
					". Model::safeSql(Tools::getGmtDate('Y-m-d')) ." >= eh.start_date
					AND
					". Model::safeSql(Tools::getGmtDate('Y-m-d')) ." <= eh.end_date
				)
				OR
				(
					". Model::safeSql(Tools::getGmtDate('Y-m-d')) ." >= eh.start_date
					AND
					eh.end_date = ''
				)
			)
			ORDER BY e.lastname		
		";	
		//echo $sql;
		return self::getRecords($sql);	
	}	
	
	public static function searchById($id) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE e.id = ". Model::safeSql($id) ."
		";		
		return self::getRecords($sql);	
	}
	
	public static function findById($id) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE e.id = ". Model::safeSql($id) ." AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
			LIMIT 1		
		";
		
		return self::getRecord($sql);
	}
	
	public static function findById2($id) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE e.id = ". Model::safeSql($id) ."
			LIMIT 1		
		";
		//echo $sql;
		return self::getRecord($sql);
	}	
	
	public static function findByIdBothArchiveAndNot($id) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE e.id = ". Model::safeSql($id) ." 
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findByIdIsArchive($id) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE e.id = ". Model::safeSql($id) ." AND e.e_is_archive =" . Model::safeSql(G_Employee::YES) . "
			LIMIT 1		
		";
		return self::getRecord($sql);
	}
	
	public static function findBySchedule(G_Schedule $s) {
		define('AS_EMPLOYEE', 1);
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e, ". G_EMPLOYEE_GROUP_SCHEDULE ." s
			WHERE e.id = s.employee_group_id
			AND s.employee_group = ". Model::safeSql(AS_EMPLOYEE) ."
			AND s.schedule_template_id = ". Model::safeSql($s->getId()) ."
			ORDER BY e.lastname
		";
		return self::getRecords($sql);
	}
	
	/*
		$sg - Instance of G_Schedule_Group class
	*/
	public static function findByScheduleGroup($sg) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e, ". G_EMPLOYEE_GROUP_SCHEDULE ." s
			WHERE e.id = s.employee_group_id
			AND s.employee_group = ". Model::safeSql(ENTITY_EMPLOYEE) ."
			AND s.schedule_group_id  = ". Model::safeSql($sg->getId()) ."
			ORDER BY e.lastname
		";
		return self::getRecords($sql);
	}

	public static function findSpecificEmployeeByStaggeredScheduleGroup($sg, $emp, $date) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e, ". V2_EMPLOYEE_SCHEDULE_TYPE ." s
			WHERE e.id = ". Model::safeSql($emp) ."
			AND s.schedule_template_id  = ". Model::safeSql($sg->getId()) ."
			AND s.employee_id  = ". Model::safeSql($emp) ."
			AND s.date  = ". Model::safeSql($date) ."
			ORDER BY e.lastname
		";
		return self::getRecords($sql);
	}

	public static function findEmployeeByStaggeredScheduleGroup($emp) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e, ". V2_EMPLOYEE_SCHEDULE_TYPE ." s
			WHERE e.id = ". Model::safeSql($emp) ."
			AND s.employee_id = ". Model::safeSql($emp) ."
			ORDER BY e.lastname
		";
		return self::getRecords($sql);
	}

	public static function findByStaggeredScheduleGroup($sg) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e, ". V2_EMPLOYEE_SCHEDULE_TYPE ." s
			WHERE e.id = s.employee_group_id
			AND s.employee_id = ". Model::safeSql(ENTITY_EMPLOYEE) ."
			AND s.schedule_template_id  = ". Model::safeSql($sg->getId()) ."
			ORDER BY e.lastname
		";
		return self::getRecords($sql);
	}

	public static function findByStaggeredScheduleGroupSpecificEmployee($sg, $employee_id) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e, ". V2_EMPLOYEE_SCHEDULE_TYPE ." s
			WHERE e.id = ". Model::safeSql($employee_id) ."
			AND s.employee_id = ". Model::safeSql($employee_id) ."
			AND s.schedule_template_id  = ". Model::safeSql($sg->getId()) ."
			ORDER BY e.lastname
		";
		return self::getRecords($sql);
	}

	public static function findAllEmployeeByScheduleGroup($id) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			WHERE e.g_employee != ". Model::safeSql($id) ."
			ORDER BY e.lastname
		";
	
		return self::getRecords($sql);
	}
	/*public static function findAllEmployeeByScheduleGroup() {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e, ". G_EMPLOYEE_GROUP_SCHEDULE ." s
			ORDER BY e.lastname
		";
		return self::getRecords($sql);
	}*/
	
	/*public static function findByCompanyStructureId(G_Company_Structure $csid, $order_by = '', $limit = '') {
		
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT 
			a.id, a.employee_id,a.job_id, a.company_structure_id, a.job_vacancy_id, a.application_status_id, a.lastname, a.firstname, a.middlename,a.gender,a.marital_status,a.birthdate,a.birth_place, a.address, a.city, a.province, a.zip_code, a.country, a.home_telephone, a.mobile, a.email_address, a.qualification, a.applied_date_time, a.resume_name, a.resume_path,sas.status as application_status,j.title as position_applied
			 FROM g_employee a,  g_settings_application_status sas ,g_job as j
			 WHERE a.company_structure_id=".Model::safeSql($csid->getId())." AND a.job_id=j.id GROUP BY a.id
			".$order_by."
			".$limit."		
		";	
		echo $sql;	
		return self::getRecords($sql);
	}
	*/
	
	public static function searchEmployeeNotInTheSameGroup($query,$conditional_statment = "") {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM g_employee e
			LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " s
			ON e.id = s.employee_id
			WHERE (e.firstname LIKE '%{$query}%' OR e.lastname LIKE '%{$query}%') 
			$conditional_statment
			GROUP BY e.firstname, e.lastname
		";
		return self::getRecords($sql);	
	}
	
	public static function findAllActiveEmployeeAttendanceUsingCustomWhereStatement($where_string) {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM " . EMPLOYEE . " e
			LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " eh ON e.id = eh.employee_id
			LEFT JOIN " . G_EMPLOYEE_ATTENDANCE . " ea ON e.id = ea.employee_id
			WHERE 
				eh.end_date = ''
				" . $where_string . "
			GROUP BY e.id
			ORDER BY e.lastname
		";
		//echo $sql;
		//exit;
		return self::getRecords($sql);
	}

	public static function findAllInActiveEmployees() {
		$sql = "
			SELECT ". self::getEmployeeFields() ."
			FROM ". EMPLOYEE ." e
			WHERE e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
				AND e.employee_status_id = 5
				AND e.inactive_date != '0000-00-00'
			ORDER BY id ASC
		";

		return self::getRecords($sql);
	}	
	

	public static function getEC($basic_salary){
	$sql =	"select company_ec from `p_sss` where ".$basic_salary." between `from_salary` and `to_salary`";


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
		//print_r($records);
		//echo $records->getId();
	
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
		
		$e = new G_Employee;
		$e->setId($row['id']);
		$e->setCompanyStructureId($row['company_structure_id']);
		$e->setHash($row['hash']);
		$e->setEmployeeDeviceId($row['employee_device_id']);
		$e->setDepartmentCompanyStructureId($row['department_company_structure_id']);
		$e->setEmploymentStatusId($row['employment_status_id']);
		$e->setEmployeeStatusId($row['employee_status_id']);
		$e->setEmployeeCode($row['employee_code']);
		$e->setSalutation($row['salutation']);
		$e->setFirstname($row['firstname']);
		$e->setLastname($row['lastname']);
		$e->setMiddlename($row['middlename']);
		$e->setExtensionName($row['extension_name']);
		$e->setNickname($row['nickname']);
		$e->setBirthdate($row['birthdate']);
		$e->setGender($row['gender']);
		$e->setMaritalStatus($row['marital_status']);
		$e->setNationality($row['nationality']);
		$e->setNumberDependent($row['number_dependent']);
		$e->setSssNumber($row['sss_number']);
		$e->setTinNumber($row['tin_number']);
		$e->setPagibigNumber($row['pagibig_number']);
		$e->setPhilhealthNumber($row['philhealth_number']);
		$e->setIsTaxExempted($row['is_tax_exempted']);
		$e->setPhoto($row['photo']);
		$e->setHiredDate($row['hired_date']);
		$e->setResignationDate($row['resignation_date']);
		$e->setInactiveDate($row['inactive_date']);
		$e->setEndoDate($row['endo_date']);
        $e->setLeaveDate($row['leave_date']);
		$e->setTerminatedDate($row['terminated_date']);
		$e->setEeoJobCategoryId($row['eeo_job_category_id']);
		$e->setSectionId($row['section_id']);
		$e->setIsConfidential($row['is_confidential']);
		$e->setFrequencyId($row['frequency_id']);
		$e->setYearWorkingDays($row['year_working_days']);
		$e->setWeekWorkingDays($row['week_working_days']);
		$e->setIsArchive($row['e_is_archive']);
		$e->setTags($row['tags']);
		$e->setCostCenter($row['cost_center']);
		$e->setProjectSiteId($row['project_site_id']);
	//	print_r($e);
		return $e;
	}
	
	
}
?>