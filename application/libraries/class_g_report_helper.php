<?php
class G_Report_Helper {

    public function sqlEmployeePayslipDataByEmployeeIdAndDateRangeDepre($employee_ids = array(), $date_from = '', $date_to = ''){
    	$sql_date_from = date("Y-m-d",strtotime($date_from));
    	$sql_date_to   = date("Y-m-d",strtotime($date_to));    	
    	$sql_ids       = implode(",", $employee_ids);    	
    	$sql = "
			SELECT e.id AS epkid,
				CONCAT(e.lastname, ', ', e.firstname)AS employee_name, 
				e.tin_number,
				COALESCE(p.basic_pay * 2,0)AS monthly_salary, 
				COALESCE( 
					(SELECT TIMESTAMPDIFF(MONTH, bs.start_date, IF(bs.end_date IS NULL, " . Model::safeSql($date_to) . ",bs.end_date) )AS num_months 
					FROM g_employee_basic_salary_history bs 
					WHERE bs.employee_id = p.employee_id 
						AND bs.basic_salary = (p.basic_pay * 2)
					ORDER BY bs.id DESC 
					LIMIT 1)
				,0)AS months_stayed, 
				COALESCE(p.number_of_declared_dependents,0)AS qualified_dependents, 
				COALESCE( 
					(						
						SELECT (COUNT(pp.id)/2)*(pp.basic_pay * 2) AS total_months_processed
						FROM g_employee_payslip pp
						WHERE pp.period_start BETWEEN " . Model::safeSql($sql_date_from) . " AND " . Model::safeSql($sql_date_to) . "
							AND pp.basic_pay = p.basic_pay
							AND pp.employee_id = p.employee_id						
					)
				,0)AS gross_amount,
				COALESCE(SUM(p.gross_pay),0)AS total_gross_amount_payslip, 
				COALESCE(SUM(p.overtime),0)AS overtime_amount, 
				COALESCE(ROUND(SUM(p.month_13th) - SUM(p.tardiness_amount),0),0)AS total_13th_month, 
				COALESCE(SUM(p.taxable),0)AS taxable_allowance_amount, 
				COALESCE(SUM(p.sss),0)AS sss_amount, 
				COALESCE(SUM(p.philhealth),0)AS philhealth_amount, 
				COALESCE(SUM(p.pagibig),0)AS pagibig_amount, 
				COALESCE(SUM(p.non_taxable),0)AS non_taxable_amount, 
				COALESCE(SUM(p.withheld_tax),0)AS withheld_tax_amount,
				COALESCE(SUM(p.sss),0)AS sss_amount,
				COALESCE(SUM(p.pagibig),0)AS pagibig_amount,
				COALESCE(SUM(p.philhealth),0)AS philhealth_amount
			FROM g_employee_payslip p 
				LEFT JOIN g_employee e ON p.employee_id = e.id 
				LEFT JOIN g_employee_basic_salary_history bs ON p.employee_id = bs.employee_id 
			WHERE p.employee_id IN({$sql_ids}) 
				AND p.period_start BETWEEN " . Model::safeSql($sql_date_from) . " AND " . Model::safeSql($sql_date_to) . "
			GROUP BY p.employee_id,p.basic_pay 
			ORDER BY CONCAT(e.lastname, ', ', e.firstname) ASC 
			
		";		

		$result = Model::runSql($sql,true);		
		return $result;	
    }

    public function sqlStringEmployeesWithEducationalCourses($educational_courses = array()){
    	$sql = "";

    	foreach( $educational_courses as $course ){
	 		$educational_courses_values[] = " ed.course LIKE '%{$course}%'";
	 	}

	 	if( !empty($educational_courses_values) ){
	 		$sql_educational_courses_values = implode(" OR ", $educational_courses_values);
	 		$sql = "
	 			SELECT ed.employee_id 
	 			FROM " . G_EMPLOYEE_EDUCATION . " ed
	 			WHERE {$sql_educational_courses_values}
	 		";
	 	}

	 	return $sql;

    }

    public function sqlStringEmployeesWithSkills($skills = array()){
    	$sql = "";

    	foreach( $skills as $skill ){
	 		$skill_values[] = "es.skill LIKE '%{$skill}%'";
	 	}

	 	if( !empty($skill_values) ){
	 		$sql_skills_values = implode(" OR ", $skill_values);
	 		$sql = "
	 			SELECT es.employee_id 
	 			FROM " . G_EMPLOYEE_SKILLS . " es
	 			WHERE {$sql_skills_values}
	 		";
	 	}

	 	return $sql;

    }

    public static function sqlAllEmployeesCashFileYearlyBonusByPeriodStartAndEnd( $period_start = '', $period_end = '', $s_query = '' ){
    	$sql_query = $s_query;				
		/*$sql = "
			SELECT e.id, e.employee_code, CONCAT(e.firstname,' ',e.lastname)AS employee_name,
				dd.bank_name, dd.account,
				yb.amount AS net_pay 
			FROM " . YEARLY_BONUS_RELEASE_DATES . " yb
				LEFT JOIN " . EMPLOYEE . " e ON yb.employee_id = e.id 
				LEFT JOIN " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd ON yb.employee_id = dd.employee_id AND dd.account != ''				
			WHERE 
				yb.cutoff_start_date =" . Model::safeSql($period_start) . " AND yb.cutoff_end_date =" . Model::safeSql($period_end) . "	
				{$sql_query}						
			ORDER BY CONCAT(e.firstname, ' ', e.lastname) ASC 
		";*/		

		$sql = "
			SELECT e.id, e.employee_code, CONCAT(e.lastname,', ',e.firstname)AS employee_name,
				dd.bank_name, dd.account,
				yb.amount AS net_pay, 
				p.other_deductions, p.other_earnings 
			FROM " . YEARLY_BONUS_RELEASE_DATES . " yb
				LEFT JOIN " . EMPLOYEE . " e ON yb.employee_id = e.id 
				LEFT JOIN " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd ON yb.employee_id = dd.employee_id AND dd.account != ''				
				LEFT JOIN " . G_EMPLOYEE_PAYSLIP . " p ON e.id = p.employee_id 
			WHERE 
				yb.cutoff_start_date =" . Model::safeSql($period_start) . " AND yb.cutoff_end_date =" . Model::safeSql($period_end) . "	
				AND p.period_start =" . Model::safeSql($period_start) . " AND p.period_end =" . Model::safeSql($period_end) . "	
				{$sql_query}		
			GROUP BY p.employee_id				
			ORDER BY CONCAT(e.lastname, ' ', e.firstname) ASC 
		"; 			

		$result = Model::runSql($sql,true);
		return $result;

	}
	//monthly cash file
public static function sqlAllEmployeesCashFileYearlyBonusByMonthlyPeriodStartAndEnd( $period_start = '', $period_end = '', $s_query = '' ){
		$sql = "
			SELECT e.id, e.employee_code, CONCAT(e.firstname,' ',e.lastname)AS employee_name,
				dd.bank_name, dd.account,
				yb.amount AS net_pay, 
				p.other_deductions, p.other_earnings 
			FROM " . YEARLY_BONUS_RELEASE_DATES . " yb
				LEFT JOIN " . EMPLOYEE . " e ON yb.employee_id = e.id 
				LEFT JOIN " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd ON yb.employee_id = dd.employee_id AND dd.account != ''				
				LEFT JOIN " . G_EMPLOYEE_MONTHLY_PAYSLIP . " p ON e.id = p.employee_id 
			WHERE 
				yb.cutoff_start_date =" . Model::safeSql($period_start) . " AND yb.cutoff_end_date =" . Model::safeSql($period_end) . "	
				AND p.period_start =" . Model::safeSql($period_start) . " AND p.period_end =" . Model::safeSql($period_end) . "	
				{$sql_query}		
			GROUP BY p.employee_id				
			ORDER BY CONCAT(e.firstname, ' ', e.lastname) ASC 
		"; 			

		$result = Model::runSql($sql,true);
		return $result;

	}


	public static function sqlAllEmployeesCashFileYearlyBonusByWeeklyPeriodStartAndEnd( $period_start = '', $period_end = '', $s_query = '' ){
		$sql = "
			SELECT e.id, e.employee_code, CONCAT(e.lastname,', ',e.firstname)AS employee_name,
				dd.bank_name, dd.account,
				yb.amount AS net_pay, 
				p.other_deductions, p.other_earnings 
			FROM " . YEARLY_BONUS_RELEASE_DATES . " yb
				LEFT JOIN " . EMPLOYEE . " e ON yb.employee_id = e.id 
				LEFT JOIN " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd ON yb.employee_id = dd.employee_id AND dd.account != ''				
				LEFT JOIN " . G_EMPLOYEE_WEEKLY_PAYSLIP . " p ON e.id = p.employee_id 
			WHERE 
				yb.cutoff_start_date =" . Model::safeSql($period_start) . " AND yb.cutoff_end_date =" . Model::safeSql($period_end) . "	
				AND p.period_start =" . Model::safeSql($period_start) . " AND p.period_end =" . Model::safeSql($period_end) . "	
				{$sql_query}		
			GROUP BY p.employee_id				
			ORDER BY CONCAT(e.lastname, ' ', e.firstname) ASC 
		"; 			

		$result = Model::runSql($sql,true);
		return $result;

	}



    public static function sqlAllEmployeesCashFileByPeriodStartAndEnd( $period_start = '', $period_end = '', $s_query = '' ){
    	$sql_query = $s_query;				
		$sql = "
			SELECT e.id, e.employee_code, CONCAT(e.lastname,', ',e.firstname)AS employee_name,
				dd.bank_name, dd.account,
				p.net_pay, p.other_deductions, p.other_earnings 
			FROM " . EMPLOYEE . " e 
				LEFT JOIN " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd ON e.id = dd.employee_id AND dd.account != ''
				LEFT JOIN " . G_EMPLOYEE_PAYSLIP . " p ON e.id = p.employee_id 
			WHERE 
				p.period_start =" . Model::safeSql($period_start) . " AND p.period_end =" . Model::safeSql($period_end) . "	
				{$sql_query}			
			GROUP BY p.employee_id
			ORDER BY CONCAT(e.lastname, ' ', e.firstname) ASC 
		";		

		$result = Model::runSql($sql,true);
		return $result;

	}

		//monthly cashfile
	public static function sqlAllEmployeesCashFileByMonthlyPeriodStartAndEnd( $period_start = '', $period_end = '', $s_query = '' ){
    	$sql_query = $s_query;				
		$sql = "
			SELECT e.id, e.employee_code, CONCAT(e.firstname,' ',e.lastname)AS employee_name,
				dd.bank_name, dd.account,
				p.net_pay, p.other_deductions, p.other_earnings 
			FROM " . EMPLOYEE . " e 
				LEFT JOIN " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd ON e.id = dd.employee_id AND dd.account != ''
				LEFT JOIN " . G_EMPLOYEE_MONTHLY_PAYSLIP . " p ON e.id = p.employee_id 
			WHERE 
				p.period_start =" . Model::safeSql($period_start) . " AND p.period_end =" . Model::safeSql($period_end) . "	
				{$sql_query}			
			GROUP BY p.employee_id
			ORDER BY CONCAT(e.firstname, ' ', e.lastname) ASC 
		";		

		$result = Model::runSql($sql,true);
		return $result;

	}


	public static function sqlAllEmployeesCashFileByWeeklyPeriodStartAndEnd( $period_start = '', $period_end = '', $s_query = '' ){
    	$sql_query = $s_query;				
		$sql = "
			SELECT e.id, e.employee_code, CONCAT(e.lastname,', ',e.firstname)AS employee_name,
				dd.bank_name, dd.account,
				p.net_pay, p.other_deductions, p.other_earnings 
			FROM " . EMPLOYEE . " e 
				LEFT JOIN " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd ON e.id = dd.employee_id AND dd.account != ''
				LEFT JOIN " . G_EMPLOYEE_WEEKLY_PAYSLIP . " p ON e.id = p.employee_id 
			WHERE 
				p.period_start =" . Model::safeSql($period_start) . " AND p.period_end =" . Model::safeSql($period_end) . "	
				{$sql_query}			
			GROUP BY p.employee_id
			ORDER BY CONCAT(e.lastname, ' ', e.firstname) ASC 
		";		

		$result = Model::runSql($sql,true);
		return $result;

	}

	public static function sqlAllEmployeesCashFileByPeriodStartAndEndAndBonusAndServiceAwardOnly( $period_start = '', $period_end = '', $s_query = '' ){
		$cutoff = G_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
		if($cutoff) {

			$sql_query = $s_query;			
			$sql = "
				SELECT e.id, e.employee_code, CONCAT(e.firstname,' ',e.lastname)AS employee_name,
					dd.bank_name, dd.account, ee.title, ee.is_taxable, ee.amount as net_amount 
				FROM " . EMPLOYEE . " e 
					LEFT JOIN " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd ON e.id = dd.employee_id AND dd.account != ''
					LEFT JOIN " . G_EMPLOYEE_EARNINGS . " ee ON e.id = ee.object_id 
				WHERE ee.payroll_period_id = " . $cutoff->getId() . "
					{$sql_query}	
				ORDER BY CONCAT(e.firstname, ' ', e.lastname) ASC 
			";

			$result = Model::runSql($sql,true);
			return $result;

		} else {
			return null;
		}

	}

	public static function sqlAllEmployeesCashFileByWeeklyPeriodStartAndEndAndBonusAndServiceAwardOnly( $period_start = '', $period_end = '', $s_query = '' ){
		$cutoff = G_Weekly_Cutoff_Period_Finder::findByPeriod($period_start, $period_end);
		if($cutoff) {

			$sql_query = $s_query;			
			$sql = "
				SELECT e.id, e.employee_code, CONCAT(e.firstname,' ',e.lastname)AS employee_name,
					dd.bank_name, dd.account, ee.title, ee.is_taxable, ee.amount as net_amount 
				FROM " . EMPLOYEE . " e 
					LEFT JOIN " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd ON e.id = dd.employee_id AND dd.account != ''
					LEFT JOIN " . G_EMPLOYEE_EARNINGS . " ee ON e.id = ee.object_id 
				WHERE ee.payroll_period_id = " . $cutoff->getId() . "
					{$sql_query}	
				ORDER BY CONCAT(e.firstname, ' ', e.lastname) ASC 
			";

			$result = Model::runSql($sql,true);
			return $result;

		} else {
			return null;
		}

	}

	public static function sqlEmployeesCashFileByPeriodStartAndEnd( $employee_ids = '', $period_start = '', $period_end = '', $s_query = '' ){
		$sql_query = $s_query;		
		$sql = "
			SELECT e.id, e.employee_code, CONCAT(e.lastname,', ',e.firstname)AS employee_name,
				dd.bank_name, dd.account,
				p.net_pay, p.other_deductions, p.other_earnings 
			FROM " . EMPLOYEE . " e 
				LEFT JOIN " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd ON e.id = dd.employee_id AND dd.account != ''
				LEFT JOIN " . G_EMPLOYEE_PAYSLIP . " p ON e.id = p.employee_id 
			WHERE e.e_is_archive = ".Model::safeSql(G_Employee::NO)."
				AND e.id IN({$employee_ids})
				 
				AND (e.terminated_date > " . Model::safeSql($period_end) . " OR e.terminated_date = '0000-00-00')
				AND p.period_start =" . Model::safeSql($period_start) . " AND p.period_end =" . Model::safeSql($period_end) . "	
				{$sql_query}		
			ORDER BY CONCAT(e.lastname, ' ', e.firstname) ASC 
		";				
		$result = Model::runSql($sql,true);
		return $result;

	}

	public static function sqlEmployeesCashFileByWeeklyPeriodStartAndEnd( $employee_ids = '', $period_start = '', $period_end = '', $s_query = '' ){
		$sql_query = $s_query;		
		$sql = "
			SELECT e.id, e.employee_code, CONCAT(e.lastname,', ',e.firstname)AS employee_name,
				dd.bank_name, dd.account,
				p.net_pay, p.other_deductions, p.other_earnings 
			FROM " . EMPLOYEE . " e 
				LEFT JOIN " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd ON e.id = dd.employee_id AND dd.account != ''
				LEFT JOIN " . G_EMPLOYEE_WEEKLY_PAYSLIP . " p ON e.id = p.employee_id 
			WHERE e.e_is_archive = ".Model::safeSql(G_Employee::NO)."
				AND e.id IN({$employee_ids})
				 
				AND (e.terminated_date > " . Model::safeSql($period_end) . " OR e.terminated_date = '0000-00-00')
				AND p.period_start =" . Model::safeSql($period_start) . " AND p.period_end =" . Model::safeSql($period_end) . "	
				{$sql_query}		
			ORDER BY CONCAT(e.lastname, ' ', e.firstname) ASC 
		";				
		$result = Model::runSql($sql,true);
		return $result;

	}

	public static function sqlEmployeesCashFileYearlyBonusByPeriodStartAndEnd( $employee_ids = '', $period_start = '', $period_end = '', $s_query = '' ){
		$sql_query = $s_query;		
		$sql = "
			SELECT e.id, e.employee_code, CONCAT(e.lastname,', ',e.firstname)AS employee_name,
				dd.bank_name, dd.account,
				yb.amount AS net_pay 
			FROM " . YEARLY_BONUS_RELEASE_DATES . " yb 
				LEFT JOIN " . EMPLOYEE . " e ON yb.employee_id = e.id 
				LEFT JOIN " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd ON yb.employee_id = dd.employee_id AND dd.account != ''
			WHERE e.e_is_archive = ".Model::safeSql(G_Employee::NO)."
				AND yb.employee_id IN({$employee_ids})
				AND (e.terminated_date > " . Model::safeSql($period_end) . " OR e.terminated_date = '0000-00-00')
				AND yb.cutoff_start_date =" . Model::safeSql($period_start) . " AND yb.cutoff_end_date =" . Model::safeSql($period_end) . "	
				{$sql_query}		
			ORDER BY CONCAT(e.lastname, ' ', e.firstname) ASC 			
		";		
		$result = Model::runSql($sql,true);
		return $result;

	}

	//monthly cash file
	public static function sqlEmployeesCashFileByMonthlyPeriodStartAndEnd( $employee_ids = '', $period_start = '', $period_end = '', $s_query = '' ){
		$sql_query = $s_query;		
		$sql = "
			SELECT e.id, e.employee_code, CONCAT(e.firstname,' ',e.lastname)AS employee_name,
				dd.bank_name, dd.account,
				p.net_pay, p.other_deductions, p.other_earnings 
			FROM " . EMPLOYEE . " e 
				LEFT JOIN " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd ON e.id = dd.employee_id AND dd.account != ''
				LEFT JOIN " . G_EMPLOYEE_MONTHLY_PAYSLIP . " p ON e.id = p.employee_id 
			WHERE e.e_is_archive = ".Model::safeSql(G_Employee::NO)."
				AND e.id IN({$employee_ids})
				 
				AND (e.terminated_date > " . Model::safeSql($period_end) . " OR e.terminated_date = '0000-00-00')
				AND p.period_start =" . Model::safeSql($period_start) . " AND p.period_end =" . Model::safeSql($period_end) . "	
				{$sql_query}		
			ORDER BY CONCAT(e.firstname, ' ', e.lastname) ASC 
		";				
		$result = Model::runSql($sql,true);
		return $result;

	}

	public static function sqlEmployeesCashFileByPeriodStartAndEndDepre( $employee_ids = '', $period_start = '', $period_end = '', $s_query = '' ){
		$sql_query = $s_query;		
		$sql = "
			SELECT e.id, e.employee_code, CONCAT(e.firstname,' ',e.lastname)AS employee_name,
				dd.bank_name, dd.account,
				p.net_pay 
			FROM " . EMPLOYEE . " e 
				LEFT JOIN " . G_EMPLOYEE_DIRECT_DEPOSIT . " dd ON e.id = dd.employee_id
				LEFT JOIN " . G_EMPLOYEE_PAYSLIP . " p ON e.id = p.employee_id 
			WHERE e.e_is_archive = ".Model::safeSql(G_Employee::NO)."
				AND e.id IN({$employee_ids})
				AND (e.resignation_date > " . Model::safeSql($period_end) . " OR e.resignation_date = '0000-00-00')
				AND (e.endo_date > " . Model::safeSql($period_end) . " OR e.endo_date = '0000-00-00')
				AND (e.terminated_date > " . Model::safeSql($period_end) . " OR e.terminated_date = '0000-00-00')
				AND p.period_start =" . Model::safeSql($period_start) . " AND p.period_end =" . Model::safeSql($period_end) . "	
				{$sql_query}		
			ORDER BY CONCAT(e.firstname, ' ', e.lastname) ASC 
		";		
		$result = Model::runSql($sql,true);
		return $result;

	}

    public function sqlManpowerLateReport($report_data = array(), $date_from = '', $date_to = ''){    	
		$employment_status   = $report_data['employment_status'];
		$skills 			 = $report_data['skills'];
		$gender 			 = $report_data['gender'];
		$educational_courses = $report_data['educational_courses'];
		$groups              = $report_data['groups'];
		$dept_id             = Utilities::decrypt($report_data['department_id']);

		$fields     = array('title');
		$department = array();
		$cs = new G_Company_Structure();
		$cs->setId($dept_id);
		$dept = $cs->getDepartmentDetailsById($fields);
		if( !empty($dept) ){
			$department = array($dept_id => $dept['title']);
		}

		if( empty($groups) ){
			$groups = $department;
		}

	 	$sql_educational_courses = self::sqlStringEmployeesWithEducationalCourses($educational_courses);
	 	$sql_skills              = self::sqlStringEmployeesWithSkills($skills);	 	

		foreach( $groups as $key => $group ){
			foreach( $employment_status as $status ){
				foreach( $gender as $g ){									
					$sql = "
						SELECT COUNT(at.id)as total_count
						FROM " . G_EMPLOYEE_ATTENDANCE . " at
							LEFT JOIN " . EMPLOYEE . " e ON at.employee_id = e.id
						WHERE at.late_hours > 0 AND
							at.date_attendance BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . " AND 
							e.department_company_structure_id =" . Model::safeSql($key) . " AND 
							e.gender =" . Model::safeSql($g) . " AND
							(e.resignation_date = '0000-00-00' OR e.resignation_date > CURDATE()) AND 
							(e.endo_date = '0000-00-00' OR e.endo_date > CURDATE()) AND 
							(e.terminated_date = '0000-00-00' OR e.terminated_date > CURDATE()) AND
							e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
							at.employee_id IN(
								SELECT jh.employee_id 
								FROM g_employee_job_history jh 
								WHERE jh.employment_status =" . Model::safeSql($status) . "
									AND jh.end_date = ''
							)
					";

					if( $sql_educational_courses != "" ){
						$sql .= " AND at.employee_id IN({$sql_educational_courses})";	
					}

					if( $sql_skills != "" ){
						$sql .= " AND at.employee_id IN({$sql_skills})";	
					} 
			
					$result = Model::runSql($sql);
					$row    = Model::fetchAssoc($result);						
					$data[$group][$status][$g] = $row['total_count'];
				}
			}
		}

		return $data;

    }

    public function sqlManpowerLateReportListedEmployee($report_data = array(), $date_from = '', $date_to = ''){    	
		$employment_status   = $report_data['employment_status'];
		$skills 			 = $report_data['skills'];
		$gender 			 = $report_data['gender'];
		$educational_courses = $report_data['educational_courses'];
		$groups              = $report_data['groups'];
		$dept_id             = Utilities::decrypt($report_data['department_id']);

		$fields     = array('title');
		$department = array();
		$cs = new G_Company_Structure();
		$cs->setId($dept_id);
		$dept = $cs->getDepartmentDetailsById($fields);
		if( !empty($dept) ){
			$department = array($dept_id => $dept['title']);
		}

		if( empty($groups) ){
			$groups = $department;
		}

	 	$sql_educational_courses = self::sqlStringEmployeesWithEducationalCourses($educational_courses);
	 	$sql_skills              = self::sqlStringEmployeesWithSkills($skills);	 	

		foreach( $groups as $key => $group ){
			foreach( $employment_status as $status ){
				foreach( $gender as $g ){									
					$sql = "
						SELECT e.id, e.employee_code, CONCAT(e.lastname, ', ', e.firstname)AS employee_name, e.gender, ed.course, es.skill
						FROM " . G_EMPLOYEE_ATTENDANCE . " at
							LEFT JOIN " . EMPLOYEE . " e ON at.employee_id = e.id
							LEFT JOIN ".G_EMPLOYEE_EDUCATION." ed ON ed.employee_id = e.id 
							LEFT JOIN ".G_EMPLOYEE_SKILLS." es ON es.employee_id = e.id
						WHERE at.late_hours > 0 AND
							at.date_attendance BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . " AND 
							e.department_company_structure_id =" . Model::safeSql($key) . " AND 
							e.gender =" . Model::safeSql($g) . " AND
							(e.resignation_date = '0000-00-00' OR e.resignation_date > CURDATE()) AND 
							(e.endo_date = '0000-00-00' OR e.endo_date > CURDATE()) AND 
							(e.terminated_date = '0000-00-00' OR e.terminated_date > CURDATE()) AND
							e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
							at.employee_id IN(
								SELECT jh.employee_id 
								FROM g_employee_job_history jh 
								WHERE jh.employment_status =" . Model::safeSql($status) . "
									AND jh.end_date = ''
							)
					";

					if( $sql_educational_courses != "" ){
						$sql .= " AND at.employee_id IN({$sql_educational_courses})";	
					}

					if( $sql_skills != "" ){
						$sql .= " AND at.employee_id IN({$sql_skills})";	
					} 
			
					$result = Model::runSql($sql,true);

					foreach($result as $r_key => $r_value) {
						$r_data[$group][$r_value['id']] = array(
							"employee_code" 	=> $r_value['employee_code'],
							"employee_name" 	=> $r_value['employee_name'],
							"employment_status" => $status,
							"gender" 			=> $r_value['gender'],
							"course" 			=> $r_value['course'],
							"skills" 			=> $r_value['skill']
						);
					}
				}
			}
		}

		return $r_data;

    }

    public function sqlManpowerPresentReport($report_data = array(), $date_from = '', $date_to = ''){    	
		$employment_status   = $report_data['employment_status'];
		$skills 			 = $report_data['skills'];
		$gender 			 = $report_data['gender'];
		$educational_courses = $report_data['educational_courses'];
		$groups              = $report_data['groups'];
		$dept_id             = Utilities::decrypt($report_data['department_id']);

		$fields     = array('title');
		$department = array();
		$cs = new G_Company_Structure();
		$cs->setId($dept_id);
		$dept = $cs->getDepartmentDetailsById($fields);
		if( !empty($dept) ){
			$department = array($dept_id => $dept['title']);
		}

		if( empty($groups) ){
			$groups = $department;
		}

	 	$sql_educational_courses = self::sqlStringEmployeesWithEducationalCourses($educational_courses);
	 	$sql_skills              = self::sqlStringEmployeesWithSkills($skills);	 	

		foreach( $groups as $key => $group ){
			foreach( $employment_status as $status ){
				foreach( $gender as $g ){									
					$sql = "
						SELECT COUNT(at.id)as total_count
						FROM " . G_EMPLOYEE_ATTENDANCE . " at
							LEFT JOIN " . EMPLOYEE . " e ON at.employee_id = e.id
						WHERE at.is_present > 0 AND
							at.date_attendance BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . " AND 
							e.department_company_structure_id =" . Model::safeSql($key) . " AND 
							e.gender =" . Model::safeSql($g) . " AND
							(e.resignation_date = '0000-00-00' OR e.resignation_date > CURDATE()) AND 
							(e.endo_date = '0000-00-00' OR e.endo_date > CURDATE()) AND 
							(e.terminated_date = '0000-00-00' OR e.terminated_date > CURDATE()) AND
							e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " AND 
							at.employee_id IN(
								SELECT jh.employee_id 
								FROM g_employee_job_history jh 
								WHERE jh.employment_status =" . Model::safeSql($status) . "
									AND jh.end_date = ''
							)
					";

					if( $sql_educational_courses != "" ){
						$sql .= " AND at.employee_id IN({$sql_educational_courses})";	
					}

					if( $sql_skills != "" ){
						$sql .= " AND at.employee_id IN({$sql_skills})";	
					} 

					$result = Model::runSql($sql);
					$row    = Model::fetchAssoc($result);						
					$data[$group][$status][$g] = $row['total_count'];
				}
			}
		}

		return $data;

    }

    public function sqlManpowerPresentReportListedEmployee($report_data = array(), $date_from = '', $date_to = ''){    	
		$employment_status   = $report_data['employment_status'];
		$skills 			 = $report_data['skills'];
		$gender 			 = $report_data['gender'];
		$educational_courses = $report_data['educational_courses'];
		$groups              = $report_data['groups'];
		$dept_id             = Utilities::decrypt($report_data['department_id']);

		$fields     = array('title');
		$department = array();
		$cs = new G_Company_Structure();
		$cs->setId($dept_id);
		$dept = $cs->getDepartmentDetailsById($fields);
		if( !empty($dept) ){
			$department = array($dept_id => $dept['title']);
		}

		if( empty($groups) ){
			$groups = $department;
		}

	 	$sql_educational_courses = self::sqlStringEmployeesWithEducationalCourses($educational_courses);
	 	$sql_skills              = self::sqlStringEmployeesWithSkills($skills);	 	

		foreach( $groups as $key => $group ){
			foreach( $employment_status as $status ){
				foreach( $gender as $g ){									
					$sql = "
						SELECT e.id, e.employee_code, CONCAT(e.lastname, ', ', e.firstname)AS employee_name, e.gender, ed.course, es.skill
						FROM " . G_EMPLOYEE_ATTENDANCE . " at
							LEFT JOIN " . EMPLOYEE . " e ON at.employee_id = e.id 
							LEFT JOIN ".G_EMPLOYEE_EDUCATION." ed ON ed.employee_id = at.employee_id 
							LEFT JOIN ".G_EMPLOYEE_SKILLS." es ON es.employee_id = at.employee_id 
						WHERE at.is_present > 0 AND
							at.date_attendance BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . " AND 
							e.department_company_structure_id =" . Model::safeSql($key) . " AND 
							e.gender =" . Model::safeSql($g) . " AND
							(e.resignation_date = '0000-00-00' OR e.resignation_date > CURDATE()) AND 
							(e.endo_date = '0000-00-00' OR e.endo_date > CURDATE()) AND 
							(e.terminated_date = '0000-00-00' OR e.terminated_date > CURDATE()) AND
							e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " AND 
							at.employee_id IN(
								SELECT jh.employee_id 
								FROM g_employee_job_history jh 
								WHERE jh.employment_status =" . Model::safeSql($status) . "
									AND jh.end_date = ''
							)
					";

					if( $sql_educational_courses != "" ){
						$sql .= " AND at.employee_id IN({$sql_educational_courses})";	
					}

					if( $sql_skills != "" ){
						$sql .= " AND at.employee_id IN({$sql_skills})";	
					} 

					$result = Model::runSql($sql,true);

					foreach($result as $r_key => $r_value) {
						$r_data[$group][$r_value['id']] = array(
							"employee_code" 	=> $r_value['employee_code'],
							"employee_name" 	=> $r_value['employee_name'],
							"employment_status" => $status,
							"gender" 			=> $r_value['gender'],
							"course" 			=> $r_value['course'],
							"skills" 			=> $r_value['skill']
						);
					}
				}
			}
		}

		return $r_data;

    }

    public function sqlManpowerLeaveReport($report_data = array(), $date_from = '', $date_to = ''){    	
		$employment_status   = $report_data['employment_status'];
		$skills 			 = $report_data['skills'];
		$gender 			 = $report_data['gender'];
		$educational_courses = $report_data['educational_courses'];
		$groups              = $report_data['groups'];
		$dept_id             = Utilities::decrypt($report_data['department_id']);

		$fields     = array('title');
		$department = array();
		$cs = new G_Company_Structure();
		$cs->setId($dept_id);
		$dept = $cs->getDepartmentDetailsById($fields);
		if( !empty($dept) ){
			$department = array($dept_id => $dept['title']);
		}

		if( empty($groups) ){
			$groups = $department;
		}

	 	$sql_educational_courses = self::sqlStringEmployeesWithEducationalCourses($educational_courses);
	 	$sql_skills              = self::sqlStringEmployeesWithSkills($skills);	 	

		foreach( $groups as $key => $group ){
			foreach( $employment_status as $status ){
				foreach( $gender as $g ){									
					$sql = "
						SELECT COUNT(el.id)as total_count
						FROM " . G_EMPLOYEE_LEAVE_REQUEST . " el
							LEFT JOIN " . EMPLOYEE . " e ON el.employee_id = e.id
						WHERE el.date_end BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . " AND 
							e.department_company_structure_id =" . Model::safeSql($key) . " AND 
							e.gender =" . Model::safeSql($g) . " AND
							(e.resignation_date = '0000-00-00' OR e.resignation_date > CURDATE()) AND 
							(e.endo_date = '0000-00-00' OR e.endo_date > CURDATE()) AND 
							(e.terminated_date = '0000-00-00' OR e.terminated_date > CURDATE()) AND
							e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " AND 
							el.employee_id IN(
								SELECT jh.employee_id 
								FROM g_employee_job_history jh 
								WHERE jh.employment_status =" . Model::safeSql($status) . "
									AND jh.end_date = ''
							)
					";

					if( $sql_educational_courses != "" ){
						$sql .= " AND el.employee_id IN({$sql_educational_courses})";	
					}

					if( $sql_skills != "" ){
						$sql .= " AND el.employee_id IN({$sql_skills})";	
					} 

					$result = Model::runSql($sql);
					$row    = Model::fetchAssoc($result);						
					$data[$group][$status][$g] = $row['total_count'];
				}
			}
		}

		return $data;

    }

    public function sqlManpowerLeaveReportListedEmployee($report_data = array(), $date_from = '', $date_to = ''){    	
		$employment_status   = $report_data['employment_status'];
		$skills 			 = $report_data['skills'];
		$gender 			 = $report_data['gender'];
		$educational_courses = $report_data['educational_courses'];
		$groups              = $report_data['groups'];
		$dept_id             = Utilities::decrypt($report_data['department_id']);

		$fields     = array('title');
		$department = array();
		$cs = new G_Company_Structure();
		$cs->setId($dept_id);
		$dept = $cs->getDepartmentDetailsById($fields);
		if( !empty($dept) ){
			$department = array($dept_id => $dept['title']);
		}

		if( empty($groups) ){
			$groups = $department;
		}

	 	$sql_educational_courses = self::sqlStringEmployeesWithEducationalCourses($educational_courses);
	 	$sql_skills              = self::sqlStringEmployeesWithSkills($skills);	 	

		foreach( $groups as $key => $group ){
			foreach( $employment_status as $status ){
				foreach( $gender as $g ){									
					$sql = "
						SELECT e.id, e.employee_code, CONCAT(e.lastname, ', ', e.firstname)AS employee_name, e.gender, ed.course, es.skill
						FROM " . G_EMPLOYEE_LEAVE_REQUEST . " el
							LEFT JOIN " . EMPLOYEE . " e ON el.employee_id = e.id
							LEFT JOIN ".G_EMPLOYEE_EDUCATION." ed ON ed.employee_id = e.id 
						LEFT JOIN ".G_EMPLOYEE_SKILLS." es ON es.employee_id = e.id
						WHERE el.date_end BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . " AND 
							e.department_company_structure_id =" . Model::safeSql($key) . " AND 
							e.gender =" . Model::safeSql($g) . " AND
							(e.resignation_date = '0000-00-00' OR e.resignation_date > CURDATE()) AND 
							(e.endo_date = '0000-00-00' OR e.endo_date > CURDATE()) AND 
							(e.terminated_date = '0000-00-00' OR e.terminated_date > CURDATE()) AND
							e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " AND 
							el.employee_id IN(
								SELECT jh.employee_id 
								FROM g_employee_job_history jh 
								WHERE jh.employment_status =" . Model::safeSql($status) . "
									AND jh.end_date = ''
							)
					";

					if( $sql_educational_courses != "" ){
						$sql .= " AND el.employee_id IN({$sql_educational_courses})";	
					}

					if( $sql_skills != "" ){
						$sql .= " AND el.employee_id IN({$sql_skills})";	
					} 

					$result = Model::runSql($sql,true);

					foreach($result as $r_key => $r_value) {
						$r_data[$group][$r_value['id']] = array(
							"employee_code" 	=> $r_value['employee_code'],
							"employee_name" 	=> $r_value['employee_name'],
							"employment_status" => $status,
							"gender" 			=> $r_value['gender'],
							"course" 			=> $r_value['course'],
							"skills" 			=> $r_value['skill']
						);
					}
				}
			}
		}

		return $r_data;

    }

    public function sqlManpowerOBReport($report_data = array(), $date_from = '', $date_to = ''){    	
		$employment_status   = $report_data['employment_status'];
		$skills 			 = $report_data['skills'];
		$gender 			 = $report_data['gender'];
		$educational_courses = $report_data['educational_courses'];
		$groups              = $report_data['groups'];
		$dept_id             = Utilities::decrypt($report_data['department_id']);

		$fields     = array('title');
		$department = array();
		$cs = new G_Company_Structure();
		$cs->setId($dept_id);
		$dept = $cs->getDepartmentDetailsById($fields);
		if( !empty($dept) ){
			$department = array($dept_id => $dept['title']);
		}

		if( empty($groups) ){
			$groups = $department;
		}

	 	$sql_educational_courses = self::sqlStringEmployeesWithEducationalCourses($educational_courses);
	 	$sql_skills              = self::sqlStringEmployeesWithSkills($skills);	 	

		foreach( $groups as $key => $group ){
			foreach( $employment_status as $status ){
				foreach( $gender as $g ){									
					$sql = "
						SELECT COUNT(ob.id)as total_count
						FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . " ob
							LEFT JOIN " . EMPLOYEE . " e ON ob.employee_id = e.id
						WHERE ob.date_end BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . " AND 
							e.department_company_structure_id =" . Model::safeSql($key) . " AND 
							e.gender =" . Model::safeSql($g) . " AND
							(e.resignation_date = '0000-00-00' OR e.resignation_date > CURDATE()) AND 
							(e.endo_date = '0000-00-00' OR e.endo_date > CURDATE()) AND 
							(e.terminated_date = '0000-00-00' OR e.terminated_date > CURDATE()) AND
							e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " AND 
							ob.employee_id IN(
								SELECT jh.employee_id 
								FROM g_employee_job_history jh 
								WHERE jh.employment_status =" . Model::safeSql($status) . "
									AND jh.end_date = ''
							)
					";

					if( $sql_educational_courses != "" ){
						$sql .= " AND ob.employee_id IN({$sql_educational_courses})";	
					}

					if( $sql_skills != "" ){
						$sql .= " AND ob.employee_id IN({$sql_skills})";	
					} 

					$result = Model::runSql($sql);
					$row    = Model::fetchAssoc($result);						
					$data[$group][$status][$g] = $row['total_count'];
				}
			}
		}

		return $data;

    }

    public function sqlManpowerOBReportListedEmployee($report_data = array(), $date_from = '', $date_to = ''){    	
		$employment_status   = $report_data['employment_status'];
		$skills 			 = $report_data['skills'];
		$gender 			 = $report_data['gender'];
		$educational_courses = $report_data['educational_courses'];
		$groups              = $report_data['groups'];
		$dept_id             = Utilities::decrypt($report_data['department_id']);

		$fields     = array('title');
		$department = array();
		$cs = new G_Company_Structure();
		$cs->setId($dept_id);
		$dept = $cs->getDepartmentDetailsById($fields);
		if( !empty($dept) ){
			$department = array($dept_id => $dept['title']);
		}

		if( empty($groups) ){
			$groups = $department;
		}

	 	$sql_educational_courses = self::sqlStringEmployeesWithEducationalCourses($educational_courses);
	 	$sql_skills              = self::sqlStringEmployeesWithSkills($skills);	 	

		foreach( $groups as $key => $group ){
			foreach( $employment_status as $status ){
				foreach( $gender as $g ){									
					$sql = "
						SELECT e.id, e.employee_code, CONCAT(e.lastname, ', ', e.firstname)AS employee_name, e.gender, ed.course, es.skill 
						FROM " . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . " ob
							LEFT JOIN " . EMPLOYEE . " e ON ob.employee_id = e.id 
							LEFT JOIN ".G_EMPLOYEE_EDUCATION." ed ON ed.employee_id = e.id 
							LEFT JOIN ".G_EMPLOYEE_SKILLS." es ON es.employee_id = e.id
						WHERE ob.date_end BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . " AND 
							e.department_company_structure_id =" . Model::safeSql($key) . " AND 
							e.gender =" . Model::safeSql($g) . " AND
							(e.resignation_date = '0000-00-00' OR e.resignation_date > CURDATE()) AND 
							(e.endo_date = '0000-00-00' OR e.endo_date > CURDATE()) AND 
							(e.terminated_date = '0000-00-00' OR e.terminated_date > CURDATE()) AND
							e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " AND 
							ob.employee_id IN(
								SELECT jh.employee_id 
								FROM g_employee_job_history jh 
								WHERE jh.employment_status =" . Model::safeSql($status) . "
									AND jh.end_date = ''
							)
					";

					if( $sql_educational_courses != "" ){
						$sql .= " AND ob.employee_id IN({$sql_educational_courses})";	
					}

					if( $sql_skills != "" ){
						$sql .= " AND ob.employee_id IN({$sql_skills})";	
					} 

					$result = Model::runSql($sql,true);

					foreach($result as $r_key => $r_value) {
						$r_data[$group][$r_value['id']] = array(
							"employee_code" 	=> $r_value['employee_code'],
							"employee_name" 	=> $r_value['employee_name'],
							"employment_status" => $status,
							"gender" 			=> $r_value['gender'],
							"course" 			=> $r_value['course'],
							"skills" 			=> $r_value['skill']
						);
					}
				}
			}
		}

		return $r_data;

    }

    public function sqlManpowerOTReport($report_data = array(), $date_from = '', $date_to = ''){    	
		$employment_status   = $report_data['employment_status'];
		$skills 			 = $report_data['skills'];
		$gender 			 = $report_data['gender'];
		$educational_courses = $report_data['educational_courses'];
		$groups              = $report_data['groups'];
		$dept_id             = Utilities::decrypt($report_data['department_id']);

		$fields     = array('title');
		$department = array();
		$cs = new G_Company_Structure();
		$cs->setId($dept_id);
		$dept = $cs->getDepartmentDetailsById($fields);
		if( !empty($dept) ){
			$department = array($dept_id => $dept['title']);
		}

		if( empty($groups) ){
			$groups = $department;
		}

	 	$sql_educational_courses = self::sqlStringEmployeesWithEducationalCourses($educational_courses);
	 	$sql_skills              = self::sqlStringEmployeesWithSkills($skills);	 	

		foreach( $groups as $key => $group ){
			foreach( $employment_status as $status ){
				foreach( $gender as $g ){									
					$sql = "
						SELECT COUNT(ot.id)as total_count
						FROM " . G_EMPLOYEE_OVERTIME . " ot
							LEFT JOIN " . EMPLOYEE . " e ON ot.employee_id = e.id
						WHERE ot.date BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . " AND 
							e.department_company_structure_id =" . Model::safeSql($key) . " AND 
							e.gender =" . Model::safeSql($g) . " AND
							(e.resignation_date = '0000-00-00' OR e.resignation_date > CURDATE()) AND 
							(e.endo_date = '0000-00-00' OR e.endo_date > CURDATE()) AND 
							(e.terminated_date = '0000-00-00' OR e.terminated_date > CURDATE()) AND
							e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " AND 
							ot.employee_id IN(
								SELECT jh.employee_id 
								FROM g_employee_job_history jh 
								WHERE jh.employment_status =" . Model::safeSql($status) . "
									AND jh.end_date = ''
							)
					";

					if( $sql_educational_courses != "" ){
						$sql .= " AND ot.employee_id IN({$sql_educational_courses})";	
					}

					if( $sql_skills != "" ){
						$sql .= " AND ot.employee_id IN({$sql_skills})";	
					} 

					$result = Model::runSql($sql);
					$row    = Model::fetchAssoc($result);						
					$data[$group][$status][$g] = $row['total_count'];
				}
			}
		}

		return $data;

    }

    public function sqlManpowerOTReportListedEmployee($report_data = array(), $date_from = '', $date_to = ''){    	
		$employment_status   = $report_data['employment_status'];
		$skills 			 = $report_data['skills'];
		$gender 			 = $report_data['gender'];
		$educational_courses = $report_data['educational_courses'];
		$groups              = $report_data['groups'];
		$dept_id             = Utilities::decrypt($report_data['department_id']);

		$fields     = array('title');
		$department = array();
		$cs = new G_Company_Structure();
		$cs->setId($dept_id);
		$dept = $cs->getDepartmentDetailsById($fields);
		if( !empty($dept) ){
			$department = array($dept_id => $dept['title']);
		}

		if( empty($groups) ){
			$groups = $department;
		}

	 	$sql_educational_courses = self::sqlStringEmployeesWithEducationalCourses($educational_courses);
	 	$sql_skills              = self::sqlStringEmployeesWithSkills($skills);	 	

		foreach( $groups as $key => $group ){
			foreach( $employment_status as $status ){
				foreach( $gender as $g ){									
					$sql = "
						SELECT e.id, e.employee_code, CONCAT(e.lastname, ', ', e.firstname)AS employee_name, e.gender, ed.course, es.skill
						FROM " . G_EMPLOYEE_OVERTIME . " ot
							LEFT JOIN " . EMPLOYEE . " e ON ot.employee_id = e.id 
							LEFT JOIN ".G_EMPLOYEE_EDUCATION." ed ON ed.employee_id = e.id 
							LEFT JOIN ".G_EMPLOYEE_SKILLS." es ON es.employee_id = e.id
						WHERE ot.date BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . " AND 
							e.department_company_structure_id =" . Model::safeSql($key) . " AND 
							e.gender =" . Model::safeSql($g) . " AND
							(e.resignation_date = '0000-00-00' OR e.resignation_date > CURDATE()) AND 
							(e.endo_date = '0000-00-00' OR e.endo_date > CURDATE()) AND 
							(e.terminated_date = '0000-00-00' OR e.terminated_date > CURDATE()) AND
							e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " AND 
							ot.employee_id IN(
								SELECT jh.employee_id 
								FROM g_employee_job_history jh 
								WHERE jh.employment_status =" . Model::safeSql($status) . "
									AND jh.end_date = ''
							)
					";

					if( $sql_educational_courses != "" ){
						$sql .= " AND ot.employee_id IN({$sql_educational_courses})";	
					}

					if( $sql_skills != "" ){
						$sql .= " AND ot.employee_id IN({$sql_skills})";	
					} 

					$result = Model::runSql($sql,true);

					foreach($result as $r_key => $r_value) {
						$r_data[$group][$r_value['id']] = array(
							"employee_code" 	=> $r_value['employee_code'],
							"employee_name" 	=> $r_value['employee_name'],
							"employment_status" => $status,
							"gender" 			=> $r_value['gender'],
							"course" 			=> $r_value['course'],
							"skills" 			=> $r_value['skill']
						);
					}
				}
			}
		}

		return $r_data;

    }

    public function sqlManpowerReport($report_data = array(), $date_from = '', $date_to = ''){    	
		$employment_status   = $report_data['employment_status'];
		$skills 			 = $report_data['skills'];
		$gender 			 = $report_data['gender'];
		$educational_courses = $report_data['educational_courses'];
		$groups              = $report_data['groups'];
		$covered_date		 = $report_data['covered_date'];
		$dept_id             = Utilities::decrypt($report_data['department_id']);

		$fields     = array('title');
		$department = array();
		$cs = new G_Company_Structure();
		$cs->setId($dept_id);
		$dept = $cs->getDepartmentDetailsById($fields);
		if( !empty($dept) ){
			$department = array($dept_id => $dept['title']);
		}

		if( empty($groups) ){
			$groups = $department;
		}

		if($covered_date == "hired_date") {
			$add_sql = " AND (e.hired_date >= '{$date_from}' AND e.hired_date <= '{$date_to}') AND (e.resignation_date = '0000-00-00' OR e.resignation_date > CURDATE()) AND (e.endo_date = '0000-00-00' OR e.endo_date > CURDATE()) AND (e.terminated_date = '0000-00-00' OR e.terminated_date > CURDATE())";
		}else{
			$add_sql = " AND (e.resignation_date >= '{$date_from}' AND e.resignation_date <= '{$date_to}')";
		}

	 	$sql_educational_courses = self::sqlStringEmployeesWithEducationalCourses($educational_courses);
	 	$sql_skills              = self::sqlStringEmployeesWithSkills($skills);	 	


		foreach( $groups as $key => $group ){
			foreach( $employment_status as $status ){
				foreach( $gender as $g ){									
					/*$sql = "
						SELECT COUNT(e.id)as total_count
						FROM " .  EMPLOYEE . " e 
						WHERE e.department_company_structure_id =" . Model::safeSql($key) . " AND 
							(e.resignation_date = '0000-00-00' OR e.resignation_date > CURDATE()) AND 
							(e.endo_date = '0000-00-00' OR e.endo_date > CURDATE()) AND 
							(e.terminated_date = '0000-00-00' OR e.terminated_date > CURDATE()) AND
							e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " AND 							
							e.gender =" . Model::safeSql($g) . " AND
							e.id IN(
								SELECT jh.employee_id 
								FROM g_employee_job_history jh 
								WHERE jh.employment_status =" . Model::safeSql($status) . "
									AND jh.end_date = ''
							)
					";		*/	

					$sql = "
						SELECT COUNT(e.id)as total_count
						FROM " .  EMPLOYEE . " e 
						WHERE e.department_company_structure_id =" . Model::safeSql($key) . " AND 
							e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " AND 							
							e.gender =" . Model::safeSql($g) . " AND
							e.id IN(
								SELECT jh.employee_id 
								FROM g_employee_job_history jh 
								WHERE jh.employment_status =" . Model::safeSql($status) . "
							) " . $add_sql . "
					";		
					
					if( $sql_educational_courses != "" ){
						$sql .= " AND e.id IN({$sql_educational_courses})";	
					}

					if( $sql_skills != "" ){
						$sql .= " AND e.id IN({$sql_skills})";	
					} 

					$result = Model::runSql($sql);
					$row    = Model::fetchAssoc($result);						
					$data[$group][$status][$g] = $row['total_count'];
				}
			}
		}

		return $data;

    }

    public function sqlManpowerReportListedEmployee($report_data = array(), $date_from = '', $date_to = ''){    	
		$employment_status   = $report_data['employment_status'];
		$skills 			 = $report_data['skills'];
		$gender 			 = $report_data['gender'];
		$educational_courses = $report_data['educational_courses'];
		$groups              = $report_data['groups'];
		$covered_date		 = $report_data['covered_date'];
		$dept_id             = Utilities::decrypt($report_data['department_id']);

		$fields     = array('title');
		$department = array();
		$cs = new G_Company_Structure();
		$cs->setId($dept_id);
		$dept = $cs->getDepartmentDetailsById($fields);
		if( !empty($dept) ){
			$department = array($dept_id => $dept['title']);
		}

		if( empty($groups) ){
			$groups = $department;
		}

		if($covered_date == "hired_date") {
			$add_sql = " AND (e.hired_date >= '{$date_from}' AND e.hired_date <= '{$date_to}') AND (e.resignation_date = '0000-00-00' OR e.resignation_date > CURDATE()) AND (e.endo_date = '0000-00-00' OR e.endo_date > CURDATE()) AND (e.terminated_date = '0000-00-00' OR e.terminated_date > CURDATE())";
		}else{
			$add_sql = " AND (e.resignation_date >= '{$date_from}' AND e.resignation_date <= '{$date_to}')";
		}

	 	$sql_educational_courses = self::sqlStringEmployeesWithEducationalCourses($educational_courses);
	 	$sql_skills              = self::sqlStringEmployeesWithSkills($skills);	 	

		foreach( $groups as $key => $group ){
			foreach( $employment_status as $status ){
				foreach( $gender as $g ){									
					/*$sql = "
						SELECT e.id, e.employee_code, CONCAT(e.lastname, ', ', e.firstname)AS employee_name, e.gender, ed.course, es.skill
						FROM " .  EMPLOYEE . " e 
						LEFT JOIN ".G_EMPLOYEE_EDUCATION." ed ON ed.employee_id = e.id 
						LEFT JOIN ".G_EMPLOYEE_SKILLS." es ON es.employee_id = e.id
						WHERE e.department_company_structure_id =" . Model::safeSql($key) . " AND 
							(e.resignation_date = '0000-00-00' OR e.resignation_date > CURDATE()) AND 
							(e.endo_date = '0000-00-00' OR e.endo_date > CURDATE()) AND 
							(e.terminated_date = '0000-00-00' OR e.terminated_date > CURDATE()) AND
							e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " AND 							
							e.gender =" . Model::safeSql($g) . " AND
							e.id IN(
								SELECT jh.employee_id 
								FROM g_employee_job_history jh 
								WHERE jh.employment_status =" . Model::safeSql($status) . "
									AND jh.end_date = ''
							)
					";*/

					$sql = "
						SELECT e.id, e.employee_code, CONCAT(e.lastname, ', ', e.firstname)AS employee_name, e.gender, ed.course, es.skill
						FROM " .  EMPLOYEE . " e 
						LEFT JOIN ".G_EMPLOYEE_EDUCATION." ed ON ed.employee_id = e.id 
						LEFT JOIN ".G_EMPLOYEE_SKILLS." es ON es.employee_id = e.id
						WHERE e.department_company_structure_id =" . Model::safeSql($key) . " AND 
							e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " AND 							
							e.gender =" . Model::safeSql($g) . " AND
							e.id IN(
								SELECT jh.employee_id 
								FROM g_employee_job_history jh 
								WHERE jh.employment_status =" . Model::safeSql($status) . "
							) ". $add_sql . " 
					";					
					
					if( $sql_educational_courses != "" ){
						$sql .= " AND e.id IN({$sql_educational_courses})";	
					}

					if( $sql_skills != "" ){
						$sql .= " AND e.id IN({$sql_skills})";	
					} 
					
					$result = Model::runSql($sql,true);

					foreach($result as $r_key => $r_value) {
						$r_data[$group][$r_value['id']] = array(
							"employee_code" 	=> $r_value['employee_code'],
							"employee_name" 	=> $r_value['employee_name'],
							"employment_status" => $status,
							"gender" 			=> $r_value['gender'],
							"course" 			=> $r_value['course'],
							"skills" 			=> $r_value['skill']
						);
					}
					
				}
			}
		}
		//Utilities::displayArray($r_data);
		return $r_data;

    }

    public function sqlSummaryWorkAgainstScheduleOld( $from_date = '', $to_date = '' ) {
    	$sql_from = date("Y-m-d",strtotime($from_date));
    	$sql_to   = date("Y-m-d",strtotime($to_date));
    	$sql = "
    		SELECT 
			 (
				 SELECT COUNT(id)
				 FROM " . G_EMPLOYEE_ATTENDANCE . " 
			   WHERE STR_TO_DATE(CONCAT(scheduled_date_in, ' ', scheduled_time_in), '%Y-%m-%d %H:%i:%s') <
					STR_TO_DATE(CONCAT(actual_date_in, ' ', actual_time_in), '%Y-%m-%d %H:%i:%s') 
					AND date_attendance BETWEEN " . Model::safeSql($sql_from) . " AND " . Model::safeSql($sql_to) . "
			 )AS total_late,
			(
				SELECT COUNT(id)
				FROM " . G_EMPLOYEE_ATTENDANCE . " 
			  WHERE STR_TO_DATE(CONCAT(scheduled_date_out, ' ', scheduled_time_out), '%Y-%m-%d %H:%i:%s') >
					STR_TO_DATE(CONCAT(actual_date_out, ' ', actual_time_out), '%Y-%m-%d %H:%i:%s') 
					AND date_attendance BETWEEN " . Model::safeSql($sql_from) . " AND " . Model::safeSql($sql_to) . "
			) AS total_early_out,
			(
				SELECT COUNT(id)
				FROM " . G_EMPLOYEE_ATTENDANCE . " 
			  WHERE STR_TO_DATE(CONCAT(scheduled_date_in, ' ', scheduled_time_in), '%Y-%m-%d %H:%i:%s') >
					STR_TO_DATE(CONCAT(actual_date_in, ' ', actual_time_in), '%Y-%m-%d %H:%i:%s') 
					AND date_attendance BETWEEN " . Model::safeSql($sql_from) . " AND " . Model::safeSql($sql_to) . "
			) AS total_early_in
    	";
    	
    	$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);	
		return $row;	
    }

     public function sqlSummaryWorkAgainstSchedule( $from_date = '', $to_date = '' ) {
    	$sql_from = date("Y-m-d",strtotime($from_date));
    	$sql_to   = date("Y-m-d",strtotime($to_date));
    	$sql = "
    		SELECT COUNT(id)AS total
			FROM " . G_EMPLOYEE_ATTENDANCE . " 
			WHERE 	(STR_TO_DATE(CONCAT(scheduled_date_in, ' ', scheduled_time_in), '%Y-%m-%d %H:%i:%s') >
					STR_TO_DATE(CONCAT(actual_date_in, ' ', actual_time_in), '%Y-%m-%d %H:%i:%s')) 
					AND
					(STR_TO_DATE(CONCAT(scheduled_date_out, ' ', scheduled_time_out), '%Y-%m-%d %H:%i:%s') >
					STR_TO_DATE(CONCAT(actual_date_out, ' ', actual_time_out), '%Y-%m-%d %H:%i:%s')) 
					OR
					(STR_TO_DATE(CONCAT(scheduled_date_in, ' ', scheduled_time_in), '%Y-%m-%d %H:%i:%s') <
					STR_TO_DATE(CONCAT(actual_date_in, ' ', actual_time_in), '%Y-%m-%d %H:%i:%s')) 
					AND
					(STR_TO_DATE(CONCAT(scheduled_date_out, ' ', scheduled_time_out), '%Y-%m-%d %H:%i:%s') <
					STR_TO_DATE(CONCAT(actual_date_out, ' ', actual_time_out), '%Y-%m-%d %H:%i:%s'))
					
					AND date_attendance BETWEEN " . Model::safeSql($sql_from) . " AND " . Model::safeSql($sql_to) . "		
    	";
    	
    	$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);	
		return $row;	
    }

    public function sqlEmployeePayslipDataByEmployeeIdAndDateRange($employee_ids = array(), $date_from = '', $date_to = ''){
    	$sql_date_from = date("Y-m-d",strtotime($date_from));
    	$sql_date_to   = date("Y-m-d",strtotime($date_to));    	
    	$sql_ids       = implode(",", $employee_ids);    	
    	$sql = "
			SELECT e.id AS epkid,
				CONCAT(e.lastname, ', ', e.firstname)AS employee_name, 
				e.tin_number,
				COALESCE(p.basic_pay * 2,0)AS monthly_salary, 
				COALESCE( 
					(
						SELECT TRUNCATE(COUNT(pp.id)/2,1)AS total_months_processed
						FROM g_employee_payslip pp
						WHERE pp.period_start BETWEEN " . Model::safeSql($sql_date_from) . " AND " . Model::safeSql($sql_date_to) . "
							AND pp.basic_pay = p.basic_pay
							AND pp.employee_id = p.employee_id
					)
				,0)AS months_stayed, 
				COALESCE(p.number_of_declared_dependents,0)AS qualified_dependents, 
				COALESCE( 
					TRUNCATE(						
						(SELECT (COUNT(pp.id)/2)*(pp.basic_pay * 2) AS total_months_processed
						FROM g_employee_payslip pp
						WHERE pp.period_start BETWEEN " . Model::safeSql($sql_date_from) . " AND " . Model::safeSql($sql_date_to) . "
							AND pp.basic_pay = p.basic_pay
							AND pp.employee_id = p.employee_id)						
					,2)
				,0)AS gross_amount,
				COALESCE(SUM(p.gross_pay),0)AS total_gross_amount_payslip, 
				COALESCE(SUM(p.overtime),0)AS overtime_amount, 
				COALESCE(ROUND(SUM(p.month_13th) - SUM(p.tardiness_amount),0),0)AS total_13th_month, 
				COALESCE(SUM(p.taxable),0)AS taxable_allowance_amount, 
				COALESCE(SUM(p.sss),0)AS sss_amount, 
				COALESCE(SUM(p.philhealth),0)AS philhealth_amount, 
				COALESCE(SUM(p.pagibig),0)AS pagibig_amount, 
				COALESCE(SUM(p.non_taxable),0)AS non_taxable_amount, 
				COALESCE(SUM(p.withheld_tax),0)AS withheld_tax_amount,
				COALESCE(SUM(p.sss),0)AS sss_amount,
				COALESCE(SUM(p.pagibig),0)AS pagibig_amount,
				COALESCE(SUM(p.philhealth),0)AS philhealth_amount
			FROM g_employee_payslip p 
				LEFT JOIN g_employee e ON p.employee_id = e.id 
				LEFT JOIN g_employee_basic_salary_history bs ON p.employee_id = bs.employee_id 
			WHERE p.employee_id IN({$sql_ids}) 
				AND p.period_start BETWEEN " . Model::safeSql($sql_date_from) . " AND " . Model::safeSql($sql_date_to) . "
			GROUP BY p.employee_id,p.basic_pay 
			ORDER BY CONCAT(e.lastname, ', ', e.firstname) ASC 
			
		";		

		$result = Model::runSql($sql,true);		
		return $result;	
    }

    public function sqlAllEmployeePayslipDataByDateRangeDepre($date_from = '', $date_to = ''){    	
    	$sql_date_from = date("Y-m-d",strtotime($date_from));
    	$sql_date_to   = date("Y-m-d",strtotime($date_to));    	
    	$sql_ids       = implode(",", $employee_ids);    	
    	$sql = "
			SELECT e.id AS epkid,
				CONCAT(e.lastname, ', ', e.firstname)AS employee_name, 
				e.tin_number,
				COALESCE(p.basic_pay * 2,0)AS monthly_salary, 
				COALESCE( 
					(SELECT TIMESTAMPDIFF(MONTH, bs.start_date, IF(bs.end_date IS NULL, " . Model::safeSql($date_to) . ",bs.end_date) )AS num_months 
					FROM g_employee_basic_salary_history bs 
					WHERE bs.employee_id = p.employee_id 
						AND bs.basic_salary = (p.basic_pay * 2)
					ORDER BY bs.id DESC 
					LIMIT 1)
				,0)AS months_stayed, 
				COALESCE(p.number_of_declared_dependents,0)AS qualified_dependents, 
				COALESCE( 
					TRUNCATE(						
						(SELECT (COUNT(pp.id)/2)*(pp.basic_pay * 2) AS total_months_processed
						FROM g_employee_payslip pp
						WHERE pp.period_start BETWEEN " . Model::safeSql($sql_date_from) . " AND " . Model::safeSql($sql_date_to) . "
							AND pp.basic_pay = p.basic_pay
							AND pp.employee_id = p.employee_id)						
					,2)
				,0)AS gross_amount,
				COALESCE(SUM(p.gross_pay),0)AS total_gross_amount_payslip, 
				COALESCE(SUM(p.overtime),0)AS overtime_amount, 
				COALESCE(ROUND(SUM(p.month_13th) - SUM(p.tardiness_amount),0),0)AS total_13th_month, 
				COALESCE(SUM(p.taxable_benefits),0)AS taxable_allowance_amount, 
				COALESCE(SUM(p.sss),0)AS sss_amount, 
				COALESCE(SUM(p.philhealth),0)AS philhealth_amount, 
				COALESCE(SUM(p.pagibig),0)AS pagibig_amount, 
				COALESCE(SUM(p.non_taxable_benefits),0)AS non_taxable_amount, 
				COALESCE(SUM(p.withheld_tax),0)AS withheld_tax_amount,
				COALESCE(SUM(p.sss),0)AS sss_amount,
				COALESCE(SUM(p.pagibig),0)AS pagibig_amount,
				COALESCE(SUM(p.philhealth),0)AS philhealth_amount
			FROM g_employee_payslip p 
				LEFT JOIN g_employee e ON p.employee_id = e.id 
				LEFT JOIN g_employee_basic_salary_history bs ON p.employee_id = bs.employee_id 
			WHERE p.employee_id IN(
					SELECT ee.id 
					FROM g_employee ee
					WHERE ee.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
				) 
				AND p.period_start BETWEEN " . Model::safeSql($sql_date_from) . " AND " . Model::safeSql($sql_date_to) . "
			GROUP BY p.employee_id,p.basic_pay 
			ORDER BY CONCAT(e.lastname, ', ', e.firstname) ASC 
			
		";	
		
		$result = Model::runSql($sql,true);		
		return $result;	
    }

    public function sqlAllEmployeePayslipDataByDateRange($date_from = '', $date_to = ''){    	
    	$sql_date_from = date("Y-m-d",strtotime($date_from));
    	$sql_date_to   = date("Y-m-d",strtotime($date_to));    	
    	$sql_ids       = implode(",", $employee_ids);    	
    	$sql = "
			SELECT e.id AS epkid,
				CONCAT(e.lastname, ', ', e.firstname)AS employee_name, 
				e.tin_number,
				COALESCE(p.basic_pay * 2,0)AS monthly_salary, 
				COALESCE( 
					(
						SELECT TRUNCATE(COUNT(pp.id)/2,1)AS total_months_processed
						FROM g_employee_payslip pp
						WHERE pp.period_start BETWEEN " . Model::safeSql($sql_date_from) . " AND " . Model::safeSql($sql_date_to) . "
							AND pp.basic_pay = p.basic_pay
							AND pp.employee_id = p.employee_id
					)
				,0)AS months_stayed, 
				COALESCE(p.number_of_declared_dependents,0)AS qualified_dependents, 
				COALESCE( 
					TRUNCATE(						
						(SELECT (COUNT(pp.id)/2)*(pp.basic_pay * 2) AS total_months_processed
						FROM g_employee_payslip pp
						WHERE pp.period_start BETWEEN " . Model::safeSql($sql_date_from) . " AND " . Model::safeSql($sql_date_to) . "
							AND pp.basic_pay = p.basic_pay
							AND pp.employee_id = p.employee_id)						
					,2)
				,0)AS gross_amount,
				COALESCE(SUM(p.gross_pay),0)AS total_gross_amount_payslip, 
				COALESCE(SUM(p.overtime),0)AS overtime_amount, 
				COALESCE(ROUND(SUM(p.month_13th) - SUM(p.tardiness_amount),0),0)AS total_13th_month, 
				COALESCE(SUM(p.taxable_benefits),0)AS taxable_allowance_amount, 
				COALESCE(SUM(p.sss),0)AS sss_amount, 
				COALESCE(SUM(p.philhealth),0)AS philhealth_amount, 
				COALESCE(SUM(p.pagibig),0)AS pagibig_amount, 
				COALESCE(SUM(p.non_taxable_benefits),0)AS non_taxable_amount, 
				COALESCE(SUM(p.withheld_tax),0)AS withheld_tax_amount,
				COALESCE(SUM(p.sss),0)AS sss_amount,
				COALESCE(SUM(p.pagibig),0)AS pagibig_amount,
				COALESCE(SUM(p.philhealth),0)AS philhealth_amount
			FROM g_employee_payslip p 
				LEFT JOIN g_employee e ON p.employee_id = e.id 
				LEFT JOIN g_employee_basic_salary_history bs ON p.employee_id = bs.employee_id 
			WHERE p.employee_id IN(
					SELECT ee.id 
					FROM g_employee ee
					WHERE ee.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
				) 
				AND p.period_start BETWEEN " . Model::safeSql($sql_date_from) . " AND " . Model::safeSql($sql_date_to) . "
			GROUP BY p.employee_id,p.basic_pay 
			ORDER BY CONCAT(e.lastname, ', ', e.firstname) ASC 
			
		";	
		
		$result = Model::runSql($sql,true);		
		return $result;	
    }

    public function getEmployeesLoanData( $query = array(), $add_query = array() ) {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        
        if($query['status'] != '' && $query['status'] != 'all'){
			$search .= " AND elr.is_approved =" . Model::safeSql($query['status']);			
		}

		if( $query['loan_type'] != 'all' ){
			$search .= " AND el.loan_type_id =" . Model::safeSql($query['loan_type']) . "";
		}
        
        $sql = "
            SELECT e.id AS employee_pkid, e.sss_number, e.philhealth_number, e.pagibig_number, el.id AS loan_pkid, e.employee_code, e.lastname, e.middlename, e.firstname, e.extension_name, es.status AS employee_status,
            	(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
               	COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position, e.birthdate, 
				el.loan_title, el.loan_amount, el.months_to_pay, el.deduction_type, el.start_date, el.end_date, el.total_amount_to_pay, el.amount_paid
			FROM ". G_EMPLOYEE ." e
                INNER JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                INNER JOIN " . EMPLOYMENT_STATUS . " es ON e.employment_status_id = es.id
                INNER JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
                INNER JOIN " . G_EMPLOYEE_LOAN . " el ON e.id = el.employee_id                
			WHERE el.is_archive =" . Model::safeSql(G_Employee_Loan::NO) . "
				{$sql_add_query}                
                " . $search . "
        ";
        		
		$result = Model::runSql($sql,true);		
		return $result;
    }

    public function getEmployeesLoanData_depre( $query = array(), $add_query = array() ) {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        
        if($query['status'] != '' && $query['status'] != 'all'){
			$search .= " AND elr.is_approved =" . Model::safeSql($query['status']);			
		}

		if( $query['loan_type'] != 'all' ){
			$search .= " AND el.loan_type_id =" . Model::safeSql($query['loan_type']) . "";
		}
        
        $sql = "
            SELECT e.id AS employee_pkid, el.id AS loan_pkid, e.employee_code, e.lastname, e.firstname, es.status AS employee_status,
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
               	COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position,
				el.loan_title, el.loan_amount, el.months_to_pay, el.deduction_type, el.start_date, el.end_date, el.total_amount_to_pay, el.amount_paid
			FROM ". G_EMPLOYEE ." e
                INNER JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                INNER JOIN " . EMPLOYMENT_STATUS . " es ON e.employment_status_id = es.id
                INNER JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
                INNER JOIN " . G_EMPLOYEE_LOAN . " el ON e.id = el.employee_id                
			WHERE el.is_archive =" . Model::safeSql(G_Employee_Loan::NO) . "
				{$sql_add_query}                
                " . $search . "
        ";
 		
		$result = Model::runSql($sql,true);		
		return $result;
    }

    public function getEmployeesLoansPaymentData( $query = array() ) {
    	$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

		if($query['search_field'] != '' && $query['search_field'] != 'all'){
			if($query['search_field'] == 'birthdate') {
				$query_search = $query['birthdate'];
			}else{
				$query_search = $query['search'];
			}
			$search = " AND e." . $query['search_field'] . "=" . Model::safeSql($query_search);				
		}
		
		if($query['department_applied'] != '' && $query['department_applied'] != 'all'){
			$search .= " AND esh.company_structure_id =" . Model::safeSql($query['department_applied']);			
		}
        
        if($query['status'] != '' && $query['status'] != 'all'){
			$search .= " AND elr.is_approved =" . Model::safeSql($query['status']);			
		}

		if( !$query['all_loan_types'] ){
			$search .= " AND el.loan_type_id =" . Model::safeSql($query['loan_type']) . "";
		}
        
        $sql = "
            SELECT e.id AS employee_pkid, el.id AS loan_pkid, e.employee_code, e.lastname, e.firstname, 
            	COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = e.id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,  
               	COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position,
				el.loan_title, el.loan_amount, el.months_to_pay, el.deduction_type, el.start_date, el.end_date, el.total_amount_to_pay
			FROM ". G_EMPLOYEE ." e
                LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
                LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
                LEFT JOIN " . EMPLOYEE_LOAN_PAYMENT_SCHEDULE . " el ON e.id = el.employee_id                
			WHERE el.is_archive =" . Model::safeSql(G_Employee_Loan::NO) . "
				{$sql_add_query}                
                " . $search . "
        ";
 		
		$result = Model::runSql($sql,true);		
		return $result;
    }

    public static function sqlEmployeeEarningsByCutoffPeriodId($cutoff_id = 0, $earnings_title = '', $fields = array()){
		$add_condition = '';

		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		if( !empty($earnings_title) ){
			$add_condition = " AND FIND_IN_SET(title,'{$earnings_title}')";
		}



		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_EARNINGS . "
			WHERE payroll_period_id =" . Model::safeSql($cutoff_id) . "
				AND status =" . Model::safeSql(G_Employee_Earnings::APPROVED) . "
			{$add_condition}
			{$order_by}
			{$limit}
		";					
		$records = Model::runSql($sql,true);
		return $records;
	}

	public static function manpowerCountByEmploymentStatusId( $query = array(), $add_query = '' ) {
		$sql_employment_id = $query['employment_status_id'];
		$sql_gender = $query['gender'];
		$sql_from = date("Y-m-d",strtotime($query['date_from']));
		$sql_to   = date("Y-m-d",strtotime($query['date_to']));

		$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

        $sql = "
    		SELECT 
			 (
				SELECT COUNT(id)
				FROM " . EMPLOYEE . " e 
			    WHERE e.hired_date <= " . Model::safeSql($sql_to) . " 
			   		AND e.employment_status_id =" . Model::safeSql($sql_employment_id) . " 
			   		AND e.gender =" . Model::safeSql($sql_gender) . "
			   		AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "               
					{$sql_add_query}
			 )AS total_employees,
			(
				SELECT COUNT(id)
				FROM " . EMPLOYEE . " e
			  	WHERE e.hired_date <= " . Model::safeSql($sql_to) . " AND e.hired_date >= " . Model::safeSql($sql_from) . " 
			   		AND e.employment_status_id =" . Model::safeSql($sql_employment_id) . "
			   		AND e.gender =" . Model::safeSql($sql_gender) . "
			   		AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "               
					{$sql_add_query}
			) AS total_newly_hired,
			(
				SELECT COUNT(id)
				FROM " . EMPLOYEE . " e
			  	WHERE e.resignation_date <= " . Model::safeSql($sql_to) . " AND e.resignation_date >= " . Model::safeSql($sql_from) . " 
			  		AND e.employment_status_id =" . Model::safeSql($sql_employment_id) . "
			   		AND e.gender =" . Model::safeSql($sql_gender) . "
			   		AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "               
					{$sql_add_query}
			) AS total_resigned
    	";	
    	
		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function manpowerCountByEmploymentStatusIdByDepartmentId( $query = array(), $add_query = '', $add_query2 = '', $dept_id = 0 ) {
		$sql_employment_id = $query['employment_status_id'];
		$sql_gender = $query['gender'];
		$sql_from = date("Y-m-d",strtotime($query['date_from']));
		$sql_to   = date("Y-m-d",strtotime($query['date_to']));

		$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

    	$sql_add_query2 = '';
    	if( $add_query2 != '' ){
    		$sql_add_query2 = $add_query2;
    	}

    	//echo $sql_add_query;
    	//echo '<hr />';

		$sql = "
    		SELECT 
			 (
				SELECT COUNT(id)
				FROM " . EMPLOYEE . " e 
			    WHERE e.hired_date <= " . Model::safeSql($sql_to) . " 
			   		AND e.employment_status_id =" . Model::safeSql($sql_employment_id) . " 
			   		AND e.gender =" . Model::safeSql($sql_gender) . "
			   		AND e.department_company_structure_id =" . Model::safeSql($dept_id) . "               
			   		AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "               
					{$sql_add_query}
			 )AS total_employees ";

		if( $add_query2 != '' ){

			$sql .= ", 
				 (
					SELECT COUNT(id)
					FROM " . EMPLOYEE . " e
				  	 WHERE e.hired_date <= " . Model::safeSql($sql_to) . " 
				   		AND e.employment_status_id =" . Model::safeSql($sql_employment_id) . "
				   		AND e.gender =" . Model::safeSql($sql_gender) . "
				   		AND e.department_company_structure_id =" . Model::safeSql($dept_id) . "  
				   		AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "               
						{$sql_add_query2}
				) AS total_others
	    	"; 

		}   	

        /*
        $sql = "
    		SELECT 
			 (
				SELECT COUNT(id)
				FROM " . EMPLOYEE . " e 
			    WHERE e.hired_date <= " . Model::safeSql($sql_to) . " 
			   		AND e.employment_status_id =" . Model::safeSql($sql_employment_id) . " 
			   		AND e.gender =" . Model::safeSql($sql_gender) . "
			   		AND e.department_company_structure_id =" . Model::safeSql($dept_id) . "               
			   		AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "               
					{$sql_add_query}
			 )AS total_employees,
			(
				SELECT COUNT(id)
				FROM " . EMPLOYEE . " e
			  	WHERE e.hired_date <= " . Model::safeSql($sql_to) . " AND e.hired_date >= " . Model::safeSql($sql_from) . " 
			   		AND e.employment_status_id =" . Model::safeSql($sql_employment_id) . "
			   		AND e.gender =" . Model::safeSql($sql_gender) . "
			   		AND e.department_company_structure_id =" . Model::safeSql($dept_id) . "  
			   		AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "               
					{$sql_add_query}
			) AS total_newly_hired,
			(
				SELECT COUNT(id)
				FROM " . EMPLOYEE . " e
			  	WHERE e.resignation_date <= " . Model::safeSql($sql_to) . " AND e.resignation_date >= " . Model::safeSql($sql_from) . " 
			  		AND e.employment_status_id =" . Model::safeSql($sql_employment_id) . "
			   		AND e.gender =" . Model::safeSql($sql_gender) . "
			   		AND e.department_company_structure_id =" . Model::safeSql($dept_id) . "  
			   		AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "               
					{$sql_add_query}
			) AS total_resigned
    	";
    	*/	

    	//echo $sql;
    	//echo '<hr />';
    	
		$result = Model::runSql($sql,true);		
		return $result;
	}

	public static function manpowerCountByEmploymentStatusIdBySectionId( $query = array(), $add_query = '', $add_query2 = '', $section_id = 0, $dept_id = 0 ) {
		$sql_employment_id = $query['employment_status_id'];
		$sql_gender = $query['gender'];
		$sql_from = date("Y-m-d",strtotime($query['date_from']));
		$sql_to   = date("Y-m-d",strtotime($query['date_to']));

		$sql_add_query = '';
    	if( $add_query != '' ){
    		$sql_add_query = $add_query;
    	}

    	$sql_add_query2 = '';
    	if( $add_query2 != '' ){
    		$sql_add_query2 = $add_query2;
    	}

		$sql = "
    		SELECT 
			 (
				SELECT COUNT(id)
				FROM " . EMPLOYEE . " e 
			    WHERE e.hired_date <= " . Model::safeSql($sql_to) . " 
			   		AND e.employment_status_id =" . Model::safeSql($sql_employment_id) . " 
			   		AND e.gender =" . Model::safeSql($sql_gender) . "
			   		AND e.section_id =" . Model::safeSql($section_id) . "
			   		AND e.department_company_structure_id =" . Model::safeSql($dept_id) . "
			   		AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "
					{$sql_add_query}
			 )AS total_employees ";

		if( $add_query2 != '' ){

			$sql .= ", 
				 (
					SELECT COUNT(id)
					FROM " . EMPLOYEE . " e
				  	 WHERE e.hired_date <= " . Model::safeSql($sql_to) . " 
				   		AND e.employment_status_id =" . Model::safeSql($sql_employment_id) . "
				   		AND e.gender =" . Model::safeSql($sql_gender) . "
				   		AND e.section_id =" . Model::safeSql($section_id) . "
				   		AND e.department_company_structure_id =" . Model::safeSql($dept_id) . "
				   		AND e.e_is_archive =" . Model::safeSql(G_Employee::NO) . "               
						{$sql_add_query2}
				) AS total_others
	    	"; 

		} 
    	
		$result = Model::runSql($sql,true);		

		return $result;
	}	
}
?>