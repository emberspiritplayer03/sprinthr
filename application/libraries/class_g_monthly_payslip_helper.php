<?php
/*
	$e = Employee_Factory::get(3);
	$ps = $e->getPayslip('2011-02-06', '2011-02-20');
	$ph = new Payslip_Helper($ps);
	echo $ph->getLabel('prepared_by') .'='. $ph->getValue('prepared_by');
*/

class G_Monthly_Payslip_Helper
{
	protected $payslip; // Instance of Payslip Class
	public $labels;
	protected $earnings;
	protected $deductions;

	public function __construct($payslip)
	{
		$this->payslip = $payslip;
		$this->arrangeLabels();
	}

	public function getLabel($variable)
	{
		$variable = strtolower($variable);
		return $this->labels[$variable]['label'];
	}

	public function getValue($variable)
	{
		$variable = strtolower($variable);
		return $this->labels[$variable]['value'];
	}

	public function getSubValue($variable)
	{
		$variable = strtolower($variable);
		echo $this->payslip['taxable']['value'];
		return $this->labels[$variable];
	}

	public function computeTotalDeductions($deduction_type = '')
	{
		$temp_deductions = (is_array($this->payslip->getDeductions())) ? $this->payslip->getDeductions() : array();
		$temp_other_deductions = (is_array($this->payslip->getOtherDeductions())) ? $this->payslip->getOtherDeductions() : array();
		$deductions 		   = array_merge($temp_deductions, $temp_other_deductions);
		foreach ($deductions as $d) {
			if (is_object($d)) {
				if (!empty($deduction_type)) {
					if ($d->getDeductionType() == $deduction_type || $deduction_type == '') {
						$array[$d->getLabel()] += $d->getAmount();
					}
				} else {
					$array[$d->getLabel()] += $d->getAmount();
				}
			}
		}
		return Tools::numberFormat(array_sum($array));
	}

	public function computeTotalEarnings($earning_type = '')
	{

		$temp_earnings = (is_array($this->payslip->getEarnings())) ? $this->payslip->getEarnings() : array();

		$temp_other_earnings = (is_array($this->payslip->getOtherEarnings())) ? $this->payslip->getOtherEarnings() : array();



		$earnings = array_merge($temp_earnings, $temp_other_earnings);

		foreach ($earnings as $er) {
			// if (is_object($er)) {
			// 	if (!empty($earning_type)) {
			// 		if ($er->getEarningType() == $earning_type) {

			// 			$array_earnings[$er->getLabel()] = $er->getAmount();
			// 		}
			// 	} else {

			// 		$array_earnings[$er->getLabel()] = $er->getAmount();
			// 	}

			// }
			$total_earnings_custom += $er->getAmount();
		}

		//return Tools::numberFormat(array_sum($array_earnings));
		return $total_earnings_custom;
	}

	private function arrangeLabels()
	{
		if (is_object($this->payslip)) {
			$labels = $this->payslip->getLabels();
			foreach ($labels as $l) {
				if (is_object($l)) {
					$variable = strtolower($l->getVariable());
					$this->labels[$variable]['label'] = $l->getLabel();
					$this->labels[$variable]['value'] = $l->getValue();
				}
			}

			$earnings = $this->payslip->getAllEarnings();
			foreach ($earnings as $e) {
				if (is_object($e)) {
					$variable = strtolower($e->getVariable());
					$this->labels[$variable]['label'] = $e->getLabel();
					$this->labels[$variable]['value'] = $e->getAmount();
				}
			}

			$deductions = $this->payslip->getAllDeductions();
			foreach ($deductions as $d) {
				if (is_object($d)) {
					$variable = strtolower($d->getVariable());
					$this->labels[$variable]['label'] = $d->getLabel();
					$this->labels[$variable]['value'] = $d->getAmount();
				}
			}
		}
	}

	public static function generatePayslipByEmployeeIdsPeriod($employee_ids, G_Monthly_Cutoff_Period $period)
	{
		$payslips = array();
		foreach ($employee_ids as $employee_id) {
			$e = G_Employee_Finder::findById($employee_id);
			if ($e && $period) {
				$pg = new G_Monthly_Payslip_Generator($period);
				$pg->setEmployee($e);
				$payslips[] = $pg->generate();
			}
		}
		if ($pg) {
			return $pg->save($payslips);
		}
	}

	public static function sqlGetPreviousEmployeePayslipDetailsByEmployeeId($employee_id = 0, $date_start = '', $date_end = '', $fields)
	{
		$row = array();

		if (!empty($fields)) {
			$sql_fields = implode(",", $fields);
		} else {
			$sql_fields = " * ";
		}

		$c = new G_Monthly_Cutoff_Period();
		$previous_cutoff = $c->getPreviousCutOffByDate($date_start);

		if ($previous_cutoff['start_date'] != '' && $previous_cutoff['end_date'] != '') {
			$period_start = $previous_cutoff['start_date'];
			$period_end   = $previous_cutoff['end_date'];

			$sql = "
				SELECT {$sql_fields}
				FROM g_employee_monthly_payslip
				WHERE employee_id = " . Model::safeSql($employee_id) . "
				AND period_start =" . Model::safeSql($period_start) . " AND period_end =" . Model::safeSql($period_end) . "
				ORDER BY id DESC
				LIMIT 1			
			";

			$result = Model::runSql($sql);
			$row = Model::fetchAssoc($result);
		}

		return $row;
	}

	public function sqlEmployeesSSSContributionByEmployeeIdsAndByDateRange($employee_ids = '', $date_from = '', $date_to = '')
	{
		$sql = "
			SELECT CONCAT(e.lastname, ', ', e.firstname)AS employee_name, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, COALESCE(FORMAT(p.sss,2),0)AS sss_contribution
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id
			WHERE p.employee_id IN({$employee_ids})
				AND p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	public function sqlEmployeePayslipDataByEmployeeIdAndDateRange($employee_ids = array(), $date_from = '', $date_to = '')
	{
		$sql_date_from = date("Y-m-d", strtotime($date_from));
		$sql_date_to   = date("Y-m-d", strtotime($date_to));
		$today         = date("Y-m-d");
		$sql_values = implode(",", $employee_ids);
		$sql = "
			SELECT CONCAT(e.lastname, ', ', e.firstname)AS employee_name, 
				COALESCE(bs.basic_salary,0)AS monthly_salary,
				TIMESTAMPDIFF(MONTH, e.hired_date, '{$today}')AS months_stayed,
				COALESCE(e.number_dependent,0)AS qualified_dependents,
				COALESCE(SUM(p.gross_pay),0)AS gross_amount,
				COALESCE(SUM(p.overtime),0)AS overtime_amount,
				COALESCE(SUM(p.month_13th),0)AS total_13th_month,
				COALESCE(SUM(p.taxable),0)AS taxable_allowance_amount,
				COALESCE(SUM(p.sss),0)AS sss_amount,
				COALESCE(SUM(p.philhealth),0)AS philhealth_amount,
				COALESCE(SUM(p.pagibig),0)AS pagibig_amount,
				COALESCE(SUM(p.non_taxable),0)AS non_taxable_amount,
				COALESCE(SUM(p.withheld_tax),0)AS withheld_tax_amount
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id
				LEFT JOIN g_employee_basic_salary_history bs ON p.employee_id = bs.employee_id AND bs.end_date = ''
			WHERE p.employee_id IN({$sql_values})
				AND p.period_start BETWEEN " . Model::safeSql($sql_date_from) . " AND " . Model::safeSql($sql_date_to) . "
			ORDER BY CONCAT(e.lastname, ', ', e.firstname) ASC
		";
		//echo $sql;
		$result = Model::runSql($sql, true);
		return $result;
	}

	public function sqlIndividualEmployeePayslipDataByEmployeeIdAndDateRange($employee_id, $date_from = '', $date_to = '')
	{
		$sql_date_from = date("Y-m-d", strtotime($date_from));
		$sql_date_to   = date("Y-m-d", strtotime($date_to));

		$sql = "
			SELECT CONCAT(e.lastname, ', ', e.firstname)AS employee_name, 
				COALESCE(bs.basic_salary,0)AS monthly_salary,
				COALESCE(e.number_dependent,0)AS qualified_dependents,
				COALESCE(SUM(p.gross_pay),0)AS gross_amount,
				COALESCE(SUM(p.overtime),0)AS overtime_amount,
				COALESCE(SUM(p.month_13th),0)AS total_13th_month,
				COALESCE(SUM(p.taxable),0)AS taxable_allowance_amount,
				COALESCE(SUM(p.sss),0)AS sss_amount,
				COALESCE(SUM(p.philhealth),0)AS philhealth_amount,
				COALESCE(SUM(p.pagibig),0)AS pagibig_amount,
				COALESCE(SUM(p.non_taxable),0)AS non_taxable_amount,
				COALESCE(SUM(p.withheld_tax),0)AS withheld_tax_amount
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id
				LEFT JOIN g_employee_basic_salary_history bs ON p.employee_id = bs.employee_id AND bs.end_date = ''
			WHERE p.employee_id = " . $employee_id . "
				AND p.period_start BETWEEN " . Model::safeSql($sql_date_from) . " AND " . Model::safeSql($sql_date_to) . " 
				LIMIT 1
		";

		$result = Model::runSql($sql, true);
		$result_data = array();
		foreach ($result as $rkey => $rdata) {
			foreach ($rdata as $rrkey => $rrdata) {
				$result_data[$rrkey] = $rrdata;
			}
		}

		return $result_data;
	}

	public function sqlEmployeesTaxContributionByEmployeeIdsAndByDateRange($employee_ids = '', $date_from = '', $date_to = '')
	{
		$sql = "
			SELECT CONCAT(e.lastname, ', ', e.firstname)AS employee_name, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, COALESCE(FORMAT(p.withheld_tax,2),0)AS tax_contribution
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id
			WHERE p.employee_id IN({$employee_ids})
				AND p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	public function sqlEmployeesPhilhealthContributionByEmployeeIdsAndByDateRange($employee_ids = '', $date_from = '', $date_to = '')
	{
		$sql = "
			SELECT CONCAT(e.lastname, ', ', e.firstname)AS employee_name, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, COALESCE(FORMAT(p.philhealth,2),0)AS philhealth_contribution
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id
			WHERE p.employee_id IN({$employee_ids})
				AND p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	public function sqlEmployeesPagibigContributionByEmployeeIdsAndByDateRange($employee_ids = '', $date_from = '', $date_to = '')
	{
		$sql = "
			SELECT CONCAT(e.lastname, ', ', e.firstname)AS employee_name, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, COALESCE(FORMAT(p.pagibig,2),0)AS pagibig_contribution
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id
			WHERE p.employee_id IN({$employee_ids})
				AND p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	public function sqlAllEmployeesSSSContributionByDateRange($date_from = '', $date_to = '', $add_query)
	{
		$sql_add_query = '';
		if ($add_query != '') {
			$sql_add_query = $add_query;
		}

		$sql = "
			SELECT e.lastname, e.firstname, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, p.sss AS sss_contribution, e.sss_number, es.status,
				sss.company_share, sss.company_ec, e.employee_code,
				(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,                 
				COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = p.employee_id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id
				LEFT JOIN g_settings_employment_status es ON e.employment_status_id = es.id
				LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
				LEFT JOIN p_sss sss ON p.sss = sss.employee_share
			WHERE p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
				{$sql_add_query}
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}
	//new
	public function sqlAllEmployeesSSSContributionByDateRangeNoDup($date_from = '', $date_to = '', $add_query)
	{
		$sql_add_query = '';
		if ($add_query != '') {
			$sql_add_query = $add_query;
		}
		//LEFT JOIN p_sss sss ON p.sss = sss.employee_share
		$sql = "
			SELECT DISTINCT e.lastname, e.firstname, e.year_working_days,CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, p.sss AS sss_contribution, p.sss_er,e.sss_number, es.status,
				sss.company_share, sss.company_ec, e.employee_code, ebsh.basic_salary,
				(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,                 
				COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = p.employee_id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id
				LEFT JOIN g_employee_basic_salary_history ebsh ON p.employee_id = ebsh.employee_id
				LEFT JOIN g_settings_employment_status es ON e.employment_status_id = es.id
				LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
				LEFT JOIN p_sss sss ON p.sss = sss.employee_share

			WHERE 
			
		 p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			AND ebsh.end_date = ''

				{$sql_add_query}
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}
	//new

	//new
	public function sqlAllEmployeesSSSContributionByDateRangeNoDup2($date_from = '', $date_to = '', $add_query){
		$sql_add_query = '';
		if( $add_query != '' ){
			$sql_add_query = $add_query;
		}
	
		$getSSS = G_Settings_Monthly_Deduction_Breakdown_Finder::findByName('SSS');
		//utilities::displayArray($getSSS);exit();
	
		if($getSSS){
			$breakdownContainer = explode(':', $getSSS->getBreakdown());
	
			$c = G_Monthly_Cutoff_Period_Finder::findByPeriod($date_from, $date_to);

			if($c->getCutoffNumber() == 1){
				$percentage = $breakdownContainer[0]/100;
			}else{
				$percentage = $breakdownContainer[1]/100;
			}
	
		}
	
		$sql = "
		SELECT DISTINCT e.lastname, e.firstname, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, 
		CASE 
		WHEN p.sss-(sss.provident_ee *".$percentage.") IS NOT NULL
		THEN p.sss-(sss.provident_ee *".$percentage.")
		WHEN f_sss.ee_amount IS NOT NULL
		THEN p.sss
		ELSE 0
		END AS sss_contribution, e.sss_number, es.status,
		CASE
		WHEN sss.company_share *".$percentage." is not NULL
		THEN sss.company_share *".$percentage."
		WHEN f_sss.er_amount is not NULL
		THEN f_sss.er_amount*".$percentage."
		ELSE 0 
		END as company_share
			, sss.company_ec *".$percentage." as company_ec
			, sss.provident_ee*".$percentage." as provident_ee,
			sss.provident_er*".$percentage." as provident_er,
			e.employee_code,
			(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,                 
			COALESCE(esh.name,(
				SELECT name FROM `g_employee_subdivision_history`
				WHERE employee_id = p.employee_id 
					AND end_date <> ''
				ORDER BY end_date DESC
				LIMIT 1
			))AS department_name
		FROM g_employee_monthly_payslip p
			LEFT JOIN g_employee e ON p.employee_id = e.id
			LEFT JOIN g_settings_employment_status es ON e.employment_status_id = es.id
			LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
			LEFT JOIN p_sss sss ON p.sss/".$percentage." = (sss.employee_share+sss.provident_ee) 
			LEFT JOIN g_employee_fixed_contributions f_sss 
			ON p.employee_id = f_sss.employee_id AND f_sss.type = 'SSS' AND f_sss.is_activated = 1	
		WHERE p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			{$sql_add_query}
		ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";		
				
		$result = Model::runSql($sql,true);		
		return $result;
		}
	//new




	public function sqlAllEmployeesYearlyBonusByDateRange($date_from = '', $date_to = '', $add_query)
	{
		$sql_add_query = '';
		if ($add_query != '') {
			$sql_add_query = $add_query;
		}

		$sql = "
			SELECT e.employee_code, e.lastname, e.firstname, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, es.status,
				SUM(p.month_13th)AS total_yearly_bonus,
				COALESCE(ejh.name,(
	                SELECT name FROM `g_employee_job_history`
	                WHERE employee_id = e.id 
	                    AND end_date <> ''
	                ORDER BY end_date DESC 
	                LIMIT 1
                ))AS position,
				(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,                 
				COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = p.employee_id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name
			FROM g_employee_monthly_payslip p
				INNER JOIN g_employee e ON p.employee_id = e.id
				INNER JOIN g_settings_employment_status es ON e.employment_status_id = es.id				
				INNER JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
				INNER JOIN " . G_EMPLOYEE_JOB_HISTORY . " ejh ON esh.employee_id = ejh.employee_id AND ejh.end_date = ''
				INNER JOIN p_sss sss ON p.sss = sss.employee_share
			WHERE p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
				{$sql_add_query}			
			GROUP BY  p.employee_id
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC			
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	/*
	 * Old philhealth contribution (2017)
    */
	public function sqlAllEmployeesPhilhealthContributionByDateRange($date_from = '', $date_to = '', $add_query = '')
	{
		$sql_add_query = '';
		if ($add_query != '') {
			$sql_add_query = $add_query;
		}

		$sql = "
			SELECT e.lastname, e.firstname, e.middlename, e.birthdate, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, p.philhealth AS philhealth_contribution, e.philhealth_number,es.status,
				ph.company_share, e.employee_code,
				(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,                 
                COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = p.employee_id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id	
				LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
				LEFT JOIN g_settings_employment_status es ON e.employment_status_id = es.id			
				LEFT JOIN p_philhealth ph ON p.philhealth = ph.employee_share
			WHERE p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
				{$sql_add_query}
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}


	public function sqlAllEmployeesRevisedPhilhealthContributionByDateRange($date_from = '', $date_to = '', $add_query = '')
	{
		$sql_add_query = '';
		if ($add_query != '') {
			$sql_add_query = $add_query;
		}

		$sql = "
			SELECT e.lastname, e.firstname, e.middlename, e.birthdate, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, p.philhealth AS philhealth_contribution, e.philhealth_number,es.status,e.employee_code,
				(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,                 
                COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = p.employee_id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name,
                p.labels
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id	
				LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
				LEFT JOIN g_settings_employment_status es ON e.employment_status_id = es.id
			WHERE p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
				{$sql_add_query}
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}
	//new
	public function sqlAllEmployeesRevisedPhilhealthContributionByDateRangeNoDup($date_from = '', $date_to = '', $add_query = '')
	{
		$sql_add_query = '';
		if ($add_query != '') {
			$sql_add_query = $add_query;
		}

		$sql = "
			SELECT DISTINCT e.lastname, e.firstname, e.middlename, e.birthdate, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, p.philhealth AS philhealth_contribution, p.philhealth_er AS philhealth_er_contribution, ge.philhealth_er ,e.philhealth_number,es.status,e.employee_code,
				(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name,                 
                COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = p.employee_id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name, p.labels
			FROM g_employee_monthly_payslip p
			LEFT JOIN g_employee_contribution ge ON p.employee_id = ge.employee_id
				LEFT JOIN g_employee e ON p.employee_id = e.id	
					LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
						LEFT JOIN g_settings_employment_status es ON e.employment_status_id = es.id
			
			WHERE p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
				{$sql_add_query}
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}
	//new

	public function sqlAllEmployeesPagibigContributionByDateRange($date_from = '', $date_to = '', $add_query = '')
	{
		$sql_add_query = '';
		if ($add_query != '') {
			$sql_add_query = $add_query;
		}

		$sql = "
			SELECT e.lastname, e.firstname, e.middlename, e.extension_name, p.period_start, p.period_end, p.pagibig AS pagibig_contribution, p.labels, e.pagibig_number,es.status,
			(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name, e.employee_code,
                COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = p.employee_id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id
				LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
				LEFT JOIN g_settings_employment_status es ON e.employment_status_id = es.id								
			WHERE p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
				{$sql_add_query}
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	//new
	public function sqlAllEmployeesPagibigContributionByDateRangeNoDup($date_from = '', $date_to = '', $add_query = '')
	{
		$sql_add_query = '';
		if ($add_query != '') {
			$sql_add_query = $add_query;
		}

		$sql = "
			SELECT DISTINCT e.lastname, e.firstname, e.middlename, e.extension_name, p.period_start, p.period_end, p.pagibig AS pagibig_contribution, p.pagibig_er AS pagibig_er_contribution ,ge.pagibig_er,e.pagibig_number,es.status,
			(SELECT csA.title FROM `g_company_structure` csA WHERE csA.id = e.section_id ORDER BY csA.id DESC LIMIT 1 )AS section_name, e.employee_code,
                COALESCE(esh.name,(
                    SELECT name FROM `g_employee_subdivision_history`
                    WHERE employee_id = p.employee_id 
                        AND end_date <> ''
                    ORDER BY end_date DESC
                    LIMIT 1
                ))AS department_name
			FROM g_employee_monthly_payslip p
			LEFT JOIN g_employee_contribution ge ON p.employee_id = ge.employee_id
				LEFT JOIN g_employee e ON p.employee_id = e.id
				LEFT JOIN " . G_EMPLOYEE_SUBDIVISION_HISTORY . " esh ON e.id = esh.employee_id AND esh.end_date = ''
				LEFT JOIN g_settings_employment_status es ON e.employment_status_id = es.id								
			WHERE p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
				{$sql_add_query}
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}
	//new

	public static function sqlProcessedPayslipByDateRange($date_from = '', $date_to = '', $fields = array())
	{

		$date_from = date('Y-m-d', strtotime($date_from));
		$date_to   = date('Y-m-d', strtotime($date_to));

		if (!empty($fields)) {
			$sql_fields = implode(",", $fields);
		} else {
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_MONTHLY_PAYSLIP . " p					
			WHERE p.period_start =" . Model::safeSql($date_from) . " 
				AND p.period_end =" . Model::safeSql($date_to) . "
			ORDER BY p.id ASC 
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	public static function sqlGetAllPayslipDataByYear($year = '', $employee_ids = array(), $fields = array(), $group_by = '')
	{

		$date_from = date('Y-m-d', strtotime($date_from));
		$date_to   = date('Y-m-d', strtotime($date_to));

		if (!empty($fields)) {
			$sql_fields = implode(",", $fields);
		} else {
			$sql_fields = " * ";
		}

		$sql_add_query = "";
		if (!empty($employee_ids)) {
			$string_employee_ids = implode(",", $employee_ids);
			$sql_add_query = "AND p.employee_id IN({$string_employee_ids})";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_MONTHLY_PAYSLIP . " p					
			WHERE DATE_FORMAT(p.period_end,'%Y') =" . Model::safeSql($year) . " 
			{$sql_add_query}			
			{$group_by}				
			ORDER BY id ASC 			
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	//Custom
	public static function sqlGetEmployeesPayslipDataByYearAndDateRangeNotIncludedConfiEmployee($year = '', $employee_ids = array(), $range = array(), $fields = array(), $group_by = '')
	{

		$date_from = date('Y-m-d', strtotime($range['from']));
		$date_to   = date('Y-m-d', strtotime($range['to']));

		if (!empty($fields)) {
			$sql_fields = implode(",", $fields);
		} else {
			$sql_fields = " * ";
		}

		$sql_add_query = "";
		if (!empty($employee_ids)) {
			$string_employee_ids = implode(",", $employee_ids);
			$sql_add_query = " AND p.employee_id IN({$string_employee_ids})";
		}

		if (!empty($range)) {
			$start_date = date("Y-m-d", strtotime($range['from']));
			$end_date   = date("Y-m-d", strtotime($range['to']));
			$sql_add_query .= " AND p.period_end BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_date);
		}

		$employee_arr = array(20, 94, 3, 170, 69, 45, 324, 24, 14, 13, 171, 31, 29, 12, 5);
		$included_employee = '"' . implode('","', $employee_arr) . '"';
		$sql_add_query .= "AND employee_id NOT IN ($included_employee)";

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_MONTHLY_PAYSLIP . " p					
			WHERE DATE_FORMAT(p.period_end,'%Y') =" . Model::safeSql($year) . " 
			{$sql_add_query}			
			{$group_by}				
			ORDER BY id ASC 			
		";

		//echo $sql;

		$result = Model::runSql($sql, true);

		$result_with_key = array();

		foreach ($result as $rdata) {
			$result_with_key[$rdata['employee_id']] = $rdata;
		}

		return $result_with_key;
	}

	public static function sqlGetEmployeesPayslipDataByYearAndDateRangeConfiEmployeeAndRemoveJanuary($year = '', $employee_ids = array(), $range = array(), $fields = array(), $group_by = '')
	{

		$date_from = date('Y-m-d', strtotime($range['from']));
		$date_to   = date('Y-m-d', strtotime($range['to']));

		if (!empty($fields)) {
			$sql_fields = implode(",", $fields);
		} else {
			$sql_fields = " * ";
		}

		$sql_add_query = "";
		if (!empty($employee_ids)) {
			$string_employee_ids = implode(",", $employee_ids);
			$sql_add_query = " AND p.employee_id IN({$string_employee_ids})";
		}

		if (!empty($range)) {
			$start_date = date("Y-m-d", strtotime($range['from']));
			$end_date   = date("Y-m-d", strtotime($range['to']));
			$sql_add_query .= " AND p.period_end BETWEEN " . Model::safeSql('2016-02-01') . " AND " . Model::safeSql($end_date);
		}

		$employee_arr = array(20, 94, 3, 170, 69, 45, 324, 24, 14, 13, 171, 31, 29, 12, 5);
		$included_employee = '"' . implode('","', $employee_arr) . '"';
		$sql_add_query .= "AND employee_id IN ($included_employee)";

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_MONTHLY_PAYSLIP . " p					
			WHERE DATE_FORMAT(p.period_end,'%Y') =" . Model::safeSql($year) . " 
			{$sql_add_query}			
			{$group_by}				
			ORDER BY id ASC 			
		";

		//echo $sql;

		$result = Model::runSql($sql, true);

		$result_with_key = array();

		foreach ($result as $rdata) {
			$result_with_key[$rdata['employee_id']] = $rdata;
		}

		return $result_with_key;
	}

	public static function sqlGetEmployeesPayslipOtherDeduction($eid, $range, $year = '')
	{
		$date_from = date('Y-m-d', strtotime($range['from']));
		$date_to   = date('Y-m-d', strtotime($range['to']));

		$sql_fields = " p.other_deductions ";

		$sql_add_query = "";

		if (!empty($range)) {
			$start_date = date("Y-m-d", strtotime($range['from']));
			$end_date   = date("Y-m-d", strtotime($range['to']));
			$sql_add_query .= " AND p.period_end BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_date);
			$sql_add_query .= " AND p.employee_id = " . Model::safeSql($eid);
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_MONTHLY_PAYSLIP . " p					
			WHERE DATE_FORMAT(p.period_end,'%Y') =" . Model::safeSql($year) . " 
			{$sql_add_query}			
			{$group_by}				
			ORDER BY id ASC 			
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	public static function sqlGetEmployeesPayslipLabels($eid, $range, $year = '')
	{
		$date_from = date('Y-m-d', strtotime($range['from']));
		$date_to   = date('Y-m-d', strtotime($range['to']));

		$sql_fields = " p.labels ";

		$sql_add_query = "";

		if (!empty($range)) {
			$start_date = date("Y-m-d", strtotime($range['from']));
			$end_date   = date("Y-m-d", strtotime($range['to']));
			$sql_add_query .= " AND p.period_end BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_date);
			$sql_add_query .= " AND p.employee_id = " . Model::safeSql($eid);
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_MONTHLY_PAYSLIP . " p					
			WHERE DATE_FORMAT(p.period_end,'%Y') =" . Model::safeSql($year) . " 
			{$sql_add_query}			
			{$group_by}				
			ORDER BY id ASC 			
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	public static function sqlGetEmployeesPayslipDataByYearAndDateRange($year = '', $employee_ids = array(), $range = array(), $fields = array(), $group_by = '')
	{

		$date_from = date('Y-m-d', strtotime($range['from']));
		$date_to   = date('Y-m-d', strtotime($range['to']));

		if (!empty($fields)) {
			$sql_fields = implode(",", $fields);
		} else {
			$sql_fields = " * ";
		}

		$sql_add_query = "";
		if (!empty($employee_ids)) {
			$string_employee_ids = implode(",", $employee_ids);
			$sql_add_query = " AND p.employee_id IN({$string_employee_ids})";
		}

		if (!empty($range)) {
			$start_date = date("Y-m-d", strtotime($range['from']));
			$end_date   = date("Y-m-d", strtotime($range['to']));
			$sql_add_query .= " AND p.period_end BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_date);
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_MONTHLY_PAYSLIP . " p					
			WHERE DATE_FORMAT(p.period_end,'%Y') =" . Model::safeSql($year) . " 
			{$sql_add_query}			
			{$group_by}				
			ORDER BY id ASC 			
		";

		$result = Model::runSql($sql, true);
		return $result;
	}
	public static function sqlGetEmployeesPayslipDataByYearAndDateRangeOtherEarnings($year = '', $employee_ids = array(), $range = array(), $fields = array(), $group_by = '')
	{

		$date_from = date('Y-m-d', strtotime($range['from']));
		$date_to   = date('Y-m-d', strtotime($range['to']));

		if (!empty($fields)) {
			$sql_fields = implode(",", $fields);
		} else {
			$sql_fields = " * ";
		}

		$sql_add_query = "";
		if (!empty($employee_ids)) {
			$string_employee_ids = implode(",", $employee_ids);
			$sql_add_query = " AND p.employee_id IN({$string_employee_ids})";
		}

		if (!empty($range)) {
			$start_date = date("Y-m-d", strtotime($range['from']));
			$end_date   = date("Y-m-d", strtotime($range['to']));
			$sql_add_query .= " AND p.period_end BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_date);
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . G_EMPLOYEE_MONTHLY_PAYSLIP . " p					
			WHERE DATE_FORMAT(p.period_end,'%Y') =" . Model::safeSql($year) . " 
			{$sql_add_query}			
			{$group_by}				
			ORDER BY id ASC 			
		";

		$result = Model::runSql($sql);
		return $result;
	}

	private static function getObjects($sql, $employee)
	{
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;
		}
		while ($row = Model::fetchAssoc($result)) {
			$return[$row['id']] = self::newObject($row, $employee);
		}
		return $return;
	}



	public function sqlAllEmployeesTaxContributionByDateRange($date_from = '', $date_to = '')
	{
		$sql = "
			SELECT CONCAT(e.lastname, ', ', e.firstname)AS employee_name, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, COALESCE(FORMAT(p.withheld_tax,2),0)AS tax_contribution
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id
			WHERE p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	public function sqlAllEmployeesPhilhealthContributionByDateRange_depre($date_from = '', $date_to = '')
	{
		$sql = "
			SELECT CONCAT(e.lastname, ', ', e.firstname)AS employee_name, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, COALESCE(FORMAT(p.philhealth,2),0)AS philhealth_contribution
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id
			WHERE p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	public function sqlAllEmployeesPagibigContributionByDateRange_depre($date_from = '', $date_to = '')
	{
		$sql = "
			SELECT CONCAT(e.lastname, ', ', e.firstname)AS employee_name, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, COALESCE(FORMAT(p.pagibig,2),0)AS pagibig_contribution
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id
			WHERE p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	public function sqlEmployeesSSSContributionByDepartmentIdsAndByDateRange($department_ids = '', $date_from = '', $date_to = '')
	{
		$sql = "
			SELECT CONCAT(e.lastname, ', ', e.firstname)AS employee_name, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, COALESCE(FORMAT(p.sss,2),0)AS sss_contribution
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id
			WHERE e.department_company_structure_id IN({$department_ids})
				AND p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	public function sqlEmployeesTaxContributionByDepartmentIdsAndByDateRange($department_ids = '', $date_from = '', $date_to = '')
	{
		$sql = "
			SELECT CONCAT(e.lastname, ', ', e.firstname)AS employee_name, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, COALESCE(FORMAT(p.withheld_tax,2),0)AS tax_contribution
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id
			WHERE e.department_company_structure_id IN({$department_ids})
				AND p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	public function sqlEmployeesPhilhealthContributionByDepartmentIdsAndByDateRange($department_ids = '', $date_from = '', $date_to = '')
	{
		$sql = "
			SELECT CONCAT(e.lastname, ', ', e.firstname)AS employee_name, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, COALESCE(FORMAT(p.philhealth,2),0)AS philhealth_contribution
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id
			WHERE e.department_company_structure_id IN({$department_ids})
				AND p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	public function sqlEmployeesPagibigContributionByDepartmentIdsAndByDateRange($department_ids = '', $date_from = '', $date_to = '')
	{
		$sql = "
			SELECT CONCAT(e.lastname, ', ', e.firstname)AS employee_name, CONCAT(p.period_start, ' to ', p.period_end)AS cutoff_period, COALESCE(FORMAT(p.pagibig,2),0)AS pagibig_contribution
			FROM g_employee_monthly_payslip p
				LEFT JOIN g_employee e ON p.employee_id = e.id
			WHERE e.department_company_structure_id IN({$department_ids})
				AND p.period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			ORDER BY CONCAT(e.lastname, ', ', e.firstname), CONCAT(p.period_start, ' to ', p.period_end) ASC
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	public static function countByEmployeeAndPeriod(IEmployee $employee, $start_date, $end_date)
	{
		$sql = "
			SELECT count(*) as total
			FROM g_employee_monthly_payslip
			WHERE employee_id = " . Model::safeSql($employee->getId()) . "
			AND (period_start = " . Model::safeSql($start_date) . " AND period_end = " . Model::safeSql($end_date) . ")
			LIMIT 1			
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function countPeriod($start_date, $end_date)
	{
		$sql = "
			SELECT count(*) as total
			FROM g_employee_monthly_payslip
			WHERE (period_start = " . Model::safeSql($start_date) . " AND period_end = " . Model::safeSql($end_date) . ")
			LIMIT 1		
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function getExistingPeriod()
	{
		$sql = "
			SELECT p.period_start, p.period_end
			FROM g_employee_monthly_payslip p
			GROUP BY p.period_start, p.period_end
			ORDER BY p.period_start DESC
		";
		$result = Model::runSql($sql);
		$counter = 0;
		while ($row = Model::fetchAssoc($result)) {
			$return[$counter]['start'] = $row['period_start'];
			$return[$counter]['end'] = $row['period_end'];
			$counter++;
		}
		return $return;
	}

	public static function getPeriods()
	{
		$sql = "
			SELECT p.id, p.period_start, p.period_end, p.payout_date, is_lock
			FROM g_employee_monthly_payslip p
			GROUP BY p.period_start, p.period_end
			ORDER BY p.period_start DESC
		";
		$result = Model::runSql($sql);
		$counter = 0;
		while ($row = Model::fetchAssoc($result)) {
			$return[$counter]['id']      = $row['id'];
			$return[$counter]['start']   = $row['period_start'];
			$return[$counter]['end']     = $row['period_end'];
			$return[$counter]['is_lock'] = $row['is_lock'];
			$counter++;
		}
		return $return;
	}

	public static function getPayrollPeriodsByYearTag($year_tag)
	{
		$sql = "
			SELECT p.id, p.period_start, p.period_end, p.payout_date, is_lock
			FROM g_employee_monthly_payslip p
			WHERE p.year_tag =" . Model::safeSql($year_tag) . "
			GROUP BY p.period_start, p.period_end
			ORDER BY p.period_start DESC
		";
		$result = Model::runSql($sql);
		$counter = 0;
		while ($row = Model::fetchAssoc($result)) {
			$return[$counter]['id']      = $row['id'];
			$return[$counter]['start']   = $row['period_start'];
			$return[$counter]['end']     = $row['period_end'];
			$return[$counter]['is_lock'] = $row['is_lock'];
			$counter++;
		}
		return $return;
	}

	public static function getPeriodPayoutDate($from, $to)
	{
		$sql = "
			SELECT p.payout_date
			FROM g_employee_monthly_payslip p
			WHERE period_start = " . Model::safeSql($from) . "
			AND period_end = " . Model::safeSql($to) . "
			LIMIT 1
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['payout_date'];
	}

	public static function updatePayslip(IEmployee $e, $start_date, $end_date)
	{
		//$p = self::generatePayslip($e, $start_date, $end_date);
		//return $p->save();
		$c = G_Company_Factory::get();
		$period = G_Monthly_Cutoff_Period_Finder::findByPeriod($start_date, $end_date);
		return $c->generatePayslipByEmployee($e, $start_date, $end_date, $period->getCutoffNumber());
	}

	public static function updatePayslipByEmployeeAndDate($e, $date)
	{
		$c = G_Monthly_Cutoff_Period_Finder::findByDate($date);
		if ($c) {
			$start_date = $c->getStartDate();
			$end_date = $c->getEndDate();
			$ps = self::generatePayslip($e, $start_date, $end_date);
			return $ps->save();
		}
	}

	public static function updatePayslipIfExistByEmployeeAndDate($e, $date)
	{
		$c = G_Monthly_Cutoff_Period_Finder::findByDate($date);
		if ($c) {
			$start_date = $c->getStartDate();
			$end_date = $c->getEndDate();
			$p = G_Payslip_Finder::findByEmployeeAndPeriod($e, $start_date, $end_date);
			if ($p) {
				$ps = self::generatePayslip($e, $start_date, $end_date);
				return $ps->save();
			}
		}
	}

	public static function updatePayslipsByPeriod($start_date, $end_date)
	{
		$c = G_Monthly_Cutoff_Period_Finder::findByPeriod($start_date, $end_date);
		$employees = G_Employee_Finder::findAllActiveByDate($start_date);
		foreach ($employees as $id => $e) {
			$p = G_Monthly_Payslip_Helper::generatePayslip($e, $start_date, $end_date);
			if ($p) {
				if ($c) {
					$payout_date = $c->getPayoutDate();
					$p->setPayoutDate($payout_date);
				}
				$p->save();
				//Add Earnings
				$ea = G_Employee_Earnings_Helper::addEarningsToPayslip($e, $start_date, $end_date);
				//

				//Add Deductions
				$de = G_Employee_Deductions_Helper::addDeductionsToPayslip($e, $start_date, $end_date);
				//

				//Add Benefits
				$period['start_date'] = $start_date;
				$period['end_date']   = $end_date;

				$be = new G_Employee_Benefit();
				$be->addToPayslip($e, $period);
				//

				//Add Loans
				$loans = G_Employee_Loan_Helper::addLoansToPaySlip($e, $start_date, $end_date);
				//
			}
		}
	}

	// DEPRECATED
	public static function generatePayslipsByPeriod($start_date, $end_date)
	{
		$employees = G_Employee_Finder::findAllActiveByDate($start_date);
		foreach ($employees as $id => $e) {
			$p = G_Monthly_Payslip_Helper::generatePayslip($e, $start_date, $end_date);
			if ($p) {
				$p->save();
			} else {
				continue;
			}
		}
	}

	/*
     * Deprecated - Use G_Company_Factory::generatePayslipByEmployee()
     */
	public static function generatePayslip(IEmployee $e, $start_date, $end_date)
	{
		$c = G_Company_Factory::get();
		$period = G_Monthly_Cutoff_Period_Finder::findByPeriod($start_date, $end_date);
		$p = $c->generatePayslipByEmployee($e, $start_date, $end_date, $period->getCutoffNumber());
		return $p;
	}

	public static function getAllPayslipsByPeriodGroupByEmployee($from, $to)
	{
		$sql = "
			SELECT e.id, p.period_start, p.period_end, p.payout_date, p.basic_pay, p.gross_pay, p.net_pay, p.earnings, p.other_earnings, p.deductions, p.other_deductions, p.labels,
					p.taxable, p.withheld_tax, p.month_13th, p.sss, p.pagibig, p.philhealth, p.total_earnings, p.total_deductions
			FROM
			 	(
					SELECT p2.id, p2.employee_id, p2.period_start, p2.period_end, p2.payout_date, p2.basic_pay, p2.gross_pay, p2.net_pay, p2.earnings, p2.other_earnings, p2.deductions, p2.other_deductions, p2.labels,
							p2.taxable, p2.withheld_tax, p2.month_13th, p2.sss, p2.pagibig, p2.philhealth, p2.total_earnings, p2.total_deductions
					FROM " . G_EMPLOYEE_MONTHLY_PAYSLIP . " p2
					WHERE 
					( p2.period_start = " . Model::safeSql($from) . " AND p2.period_end = " . Model::safeSql($to) . ")

					GROUP BY p2.employee_id
				) AS p
		
			INNER JOIN " . EMPLOYEE . " e ON e.id = p.employee_id				
		
			ORDER BY e.lastname

		";
		//		AND e.id = 47
		//  		$sql = "SELECT e.id, p.period_start, p.period_end, p.payout_date, p.basic_pay, p.gross_pay, p.net_pay, p.earnings, p.other_earnings, p.deductions, p.other_deductions, p.labels, p.taxable, p.withheld_tax, p.month_13th, p.sss, p.pagibig, p.philhealth, p.total_earnings, p.total_deductions
		// FROM (
		//AND p2.id IN (35588,35635,35606,35563,35609,35612,35517,35472, 35637,35619,35607,35502,35533,35658)

		// SELECT p2.id, p2.employee_id, p2.period_start, p2.period_end, p2.payout_date, p2.basic_pay, p2.gross_pay, p2.net_pay, p2.earnings, p2.other_earnings, p2.deductions, p2.other_deductions, p2.labels, p2.taxable, p2.withheld_tax, p2.month_13th, p2.sss, p2.pagibig, p2.philhealth, p2.total_earnings, p2.total_deductions
		// FROM g_employee_weekly_payslip p2
		// WHERE (
		// p2.period_start = '2019-03-26'
		// AND p2.period_end = '2019-04-10'
		// )
		// GROUP BY p2.employee_id
		// ) AS p
		// INNER JOIN g_employee e ON e.id = p.employee_id
		// ORDER BY e.lastname

		//  		";

		$result = Model::runSql($sql);
		while ($row = Model::fetchAssoc($result)) {
			$records[$row['id']] = $row;
		}

		// echo "<pre>";
		// var_dump($records);
		// echo "</pre>";	
		return $records;
	}
	/*
     * @param array $a Array instance of G_Attendance
     */
	public static function computeLegalAmountDepre($attendance, $rate, $daily_amount, $hourly_amount)
	{
		$amount             = 0;
		$omit_100 = 100; // if legal, additional 200% pay to employee. we will compute it from 100% because the other 100% is in the regular pay already
		$holiday_legal_rate = $rate->getHolidayLegal() - $omit_100;

		foreach ($attendance as $a) {
			$employee_id = $a->getEmployeeId();
			$date        = $a->getDate();
			$e = new G_Employee();
			$e->setId($employee_id);
			$salary = $e->getSalary($date);

			if (!empty($salary) && $salary->getType() == 'Monthly') {
				$holiday_legal_divisor = $holiday_legal_rate;
			} else {
				$holiday_legal_divisor = 100;
			}

			if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
				$h = $a->getHoliday();
				if (!empty($h) && $h->isLegal()) {
					$t = $a->getTimesheet();
					$hours_worked = $t->getTotalHoursWorked();
					/* if ($hours_worked >= 8) {
                        $hours_worked = 8;
                    }*/
					if ($a->isOfficialBusiness()) {
						$hours_worked = 8;
					}
					$hours_worked = Tools::numberFormat($hours_worked, 2);
					$temp_amount = (float) Tools::numberFormat($hours_worked * $hourly_amount * ($holiday_legal_rate / $holiday_legal_divisor), 2);
					$amount = $amount + $temp_amount;
				}
			}
		}
		return $amount;
	}

	public static function computeLegalAmount($attendance, $rate, $daily_amount, $hourly_amount, $custom_ot = array(), $mandated_status = 'Enable')
	{
		$amount               = 0;
		$omit_100 			  = 100; // if legal, additional 200% pay to employee. we will compute it from 100% because the other 100% is in the regular pay already
		// if (strtolower($mandated_status) == 'enable') {
		// 	$holiday_legal_rate   = $rate->getHolidayLegal() - $omit_100;
		// } else {
		// 	$holiday_legal_rate = 100 - $omit_100;
		// }

		$total_hrs_worked     = 0;
		$new_amount           = 0;
		$deductible_breaktime = 0;

		foreach ($attendance as $a) {
			$is_with_custom_ot = false;
			$employee_id = $a->getEmployeeId();
			$date        = $a->getDate();
			$t 			 = $a->getTimesheet();
			$e = new G_Employee();
			$e->setId($employee_id);
			$salary = $e->getSalary($date);

			//if employee have assign schedule, proceed the computation, if not 0 value - start
			$ee        	 = G_Employee_Finder::findById($employee_id);
			$specific_sched = G_Schedule_Specific_Finder::findByEmployeeAndDate($ee, $date);
			$sched       = G_Schedule_Group_Finder::findByEmployeeAndDateStartEnd($ee, $date);
			if (!$sched && !$specific_sched) {
				$sched = G_Schedule_Finder::findDefaultByDate($date);
			}
			//if employee have assign schedule, proceed the computation, if not 0 value - end            

			//Custom OT
			if (!empty($custom_ot) && isset($custom_ot[$a->getDate()]) && $custom_ot[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_HOLIDAY && $t->getTotalHoursWorked() > 0 && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time'])) {
				$timestamp_start = $custom_ot[$a->getDate()]['start_time'];
				$timestamp_end   = $custom_ot[$a->getDate()]['end_time'];

				$emp = G_Employee_Finder::findById($a->getEmployeeId());
				$day_type[]               = "applied_to_legal_holiday";

				$schedule['schedule_in']  = $t->getScheduledDateIn() . " " .  $t->getScheduledTimeIn();
				$schedule['schedule_out'] = $t->getScheduledDateOut() . " " .  $t->getScheduledTimeOut();

				$schedule['actual_in']    = $t->getTimeIn();
				$schedule['actual_out']   = $t->getTimeOut();
				$deductible_breaktime     = $emp->getTotalBreakTimeHrsDeductible($schedule, $day_type);

				if ($timestamp_start > $timestamp_end) {
					$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];

					$date = new DateTime($a->getDate());
					$date->modify('+1 day');
					$date_end = $date->format('Y-m-d');
					$date_end = $date_end . " " . $custom_ot[$a->getDate()]['end_time'];
				} else {
					$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
					$date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
				}
				$custom_ot_hours_worked = Tools::computeHoursDifferenceByDateTime($date_start, $date_end) - $deductible_breaktime;
				$is_with_custom_ot = true;
			}

			// if (!empty($salary) && $salary->getType() == 'Monthly') {
			// 	$holiday_legal_divisor = $holiday_legal_rate;
			// } else {
			// 	$holiday_legal_divisor = 100;
			// }

			if ($a->isHoliday() && !$a->isOfficialBusiness()) {

				$h = $a->getHoliday();
				if ($h && $h->isLegal()) {

					if ($specific_sched || $sched) {

						$employee_id   = $a->getEmployeeId();
						$previous_date = date("Y-m-d", strtotime("-1 days", strtotime($date)));
						$fields        = array("ea.is_present", "ea.is_paid", "ea.is_leave", "ea.is_ob");
						$yesterday	   = G_Attendance_Helper::sqlGetNearestRegularDay($employee_id, $date); //Get previous date attendance 

						$cutoff_period = G_Monthly_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($date);

						if (($a->isPresent() && $yesterday['is_present'] && !$a->isRestDay()) || ($a->isPresent() && $yesterday['is_leave'] == 1 && $yesterday['is_paid'] == 1 && !$a->isRestDay()) || ($a->isPresent() && $yesterday['is_ob'] == 1 && $yesterday['is_paid'] == 1 && !$a->isRestDay())  || ($a->isPresent() && $a->isPaid() && !$a->isRestDay())) { //if both previous date and holiday is present or on leave with pay before holiday and present on holiday - double pay						
							$hours_worked = $t->getTotalHoursWorked();
							if ($is_with_custom_ot) {
								$hours_worked = $custom_ot_hours_worked;
							}

							if (strtolower($mandated_status) == 'enable') {
								// $new_amount  += $hourly_amount * (($rate->getHolidayLegal() - $omit_100) / 100) * $hours_worked;    
								// $new_amount  += $hourly_amount * (($omit_100) / 100) * $hours_worked;    
								$new_amount  += $hourly_amount * (($rate->getHolidayLegal()) / 100) * $hours_worked;
							} else {
								// $new_amount  += $hourly_amount * ((100 - $omit_100) / 100) * $hours_worked;    
								// $new_amount  += $hourly_amount * (($omit_100) / 100) * $hours_worked;    
								$new_amount  += $hourly_amount * ((100) / 100) * $hours_worked;
							}
						} elseif ((!$a->isPresent() && $yesterday['is_present']) && ($previous_date >= $cutoff_period['period_start']) && ($previous_date <= $cutoff_period['period_end'])) {

							$hours_worked = $t->getTotalScheduleHours();
							if ($is_with_custom_ot) {
								$hours_worked = $custom_ot_hours_worked;
							}

							$new_amount += 0;
						} else { //Regular paid if absent before holiday and present on holiday or vice versa                    	                    	
							if ($t) {
								$required_schedule_total_hrs = $t->getTotalScheduleHours();
								$hours_worked = $t->getTotalHoursWorked();
								if ($is_with_custom_ot) {
									$hours_worked = $custom_ot_hours_worked;
								}

								$new_amount += 0;
							}
						}
					} else {
						$new_amount += 0;
					}
				}
			}
		}

		return $new_amount;
	}
	//new

	public static function computeCutLegalAmount($attendance, $rate, $daily_amount, $hourly_amount, $custom_ot = array(), $mandated_status = 'Enable')
	{
		$amount               = 0;
		$omit_100 			  = 100; // if legal, additional 200% pay to employee. we will compute it from 100% because the other 100% is in the regular pay already
		if (strtolower($mandated_status) == 'enable') {
			$holiday_legal_rate   = $rate->getHolidayLegal() - $omit_100;
		} else {
			$holiday_legal_rate = 100 - $omit_100;
		}

		$total_hrs_worked     = 0;
		$new_amount           = 0;
		$deductible_breaktime = 0;

		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {

				$is_with_custom_ot = false;
				$employee_id = $a->getEmployeeId();
				$date        = $a->getDate();
				$t 			 = $a->getTimesheet();
				$e = new G_Employee();
				$e->setId($employee_id);
				$salary = $e->getSalary($date);

				//if employee have assign schedule, proceed the computation, if not 0 value - start
				$ee        	 = G_Employee_Finder::findById($employee_id);
				$specific_sched = G_Schedule_Specific_Finder::findByEmployeeAndDate($ee, $date);
				$sched       = G_Schedule_Group_Finder::findByEmployeeAndDateStartEnd($ee, $date);
				if (!$sched && !$specific_sched) {
					$sched = G_Schedule_Finder::findDefaultByDate($date);
				}
				//if employee have assign schedule, proceed the computation, if not 0 value - end            

				//Custom OT
				if (!empty($custom_ot) && isset($custom_ot[$a->getDate()]) && $custom_ot[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_HOLIDAY && $t->getTotalHoursWorked() > 0 && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time'])) {
					$timestamp_start = $custom_ot[$a->getDate()]['start_time'];
					$timestamp_end   = $custom_ot[$a->getDate()]['end_time'];

					$emp = G_Employee_Finder::findById($a->getEmployeeId());
					$day_type[]               = "applied_to_legal_holiday";

					$schedule['schedule_in']  = $t->getScheduledDateIn() . " " .  $t->getScheduledTimeIn();
					$schedule['schedule_out'] = $t->getScheduledDateOut() . " " .  $t->getScheduledTimeOut();

					$schedule['actual_in']    = $t->getTimeIn();
					$schedule['actual_out']   = $t->getTimeOut();
					$deductible_breaktime     = $emp->getTotalBreakTimeHrsDeductible($schedule, $day_type);

					if ($timestamp_start > $timestamp_end) {
						$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];

						$date = new DateTime($a->getDate());
						$date->modify('+1 day');
						$date_end = $date->format('Y-m-d');
						$date_end = $date_end . " " . $custom_ot[$a->getDate()]['end_time'];
					} else {
						$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
						$date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
					}
					$custom_ot_hours_worked = Tools::computeHoursDifferenceByDateTime($date_start, $date_end) - $deductible_breaktime;
					$is_with_custom_ot = true;
				}

				if (!empty($salary) && $salary->getType() == 'Monthly') {
					$holiday_legal_divisor = $holiday_legal_rate;
				} else {
					$holiday_legal_divisor = 100;
				}

				if ($a->isHoliday() && !$a->isOfficialBusiness()) {

					$h = $a->getHoliday();
					if ($h && $h->isLegal()) {

						if ($specific_sched || $sched) {

							$employee_id   = $a->getEmployeeId();
							$previous_date = date("Y-m-d", strtotime("-1 days", strtotime($date)));
							$fields        = array("ea.is_present", "ea.is_paid", "ea.is_leave", "ea.is_ob");
							$yesterday	   = G_Attendance_Helper::sqlGetNearestRegularDay($employee_id, $date); //Get previous date attendance 

							$cutoff_period = G_Monthly_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($date);
							if (($a->isPresent() && $yesterday['is_present']) || ($a->isPresent() && $yesterday['is_leave'] == 1 && $yesterday['is_paid'] == 1) || ($a->isPresent() && $yesterday['is_ob'] == 1 && $yesterday['is_paid'] == 1)  || ($a->isPresent() && $a->isPaid())) { //if both previous date and holiday is present or on leave with pay before holiday and present on holiday - double pay						
								$hours_worked = $t->getTotalHoursWorked();
								if ($is_with_custom_ot) {
									$hours_worked = $custom_ot_hours_worked;
								}

								if (strtolower($mandated_status) == 'enable') {
									$new_amount  += $hourly_amount * (($rate->getHolidayLegal() - $omit_100) / 100) * $hours_worked;
								} else {
									$new_amount  += $hourly_amount * ((100 - $omit_100) / 100) * $hours_worked;
								}
							} elseif ((!$a->isPresent() && $yesterday['is_present']) && ($previous_date >= $cutoff_period['period_start']) && ($previous_date <= $cutoff_period['period_end'])) {

								$hours_worked = $t->getTotalScheduleHours();
								if ($is_with_custom_ot) {
									$hours_worked = $custom_ot_hours_worked;
								}

								$new_amount += 0;
							} else { //Regular paid if absent before holiday and present on holiday or vice versa                    	                    	
								if ($t) {
									$required_schedule_total_hrs = $t->getTotalScheduleHours();
									$hours_worked = $t->getTotalHoursWorked();
									if ($is_with_custom_ot) {
										$hours_worked = $custom_ot_hours_worked;
									}

									$new_amount += 0;
								}
							}
						} else {
							$new_amount += 0;
						}
					}
				}
			}
		}

		return $new_amount;
	}
	public static function computePrevLegalAmount($attendance, $rate, $daily_amount, $hourly_amount, $custom_ot = array(), $mandated_status = 'Enable')
	{
		$amount               = 0;
		$omit_100 			  = 100; // if legal, additional 200% pay to employee. we will compute it from 100% because the other 100% is in the regular pay already
		if (strtolower($mandated_status) == 'enable') {
			$holiday_legal_rate   = $rate->getHolidayLegal() - $omit_100;
		} else {
			$holiday_legal_rate = 100 - $omit_100;
		}

		$total_hrs_worked     = 0;
		$new_amount           = 0;
		$deductible_breaktime = 0;

		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {

				$is_with_custom_ot = false;
				$employee_id = $a->getEmployeeId();
				$date        = $a->getDate();
				$t 			 = $a->getTimesheet();
				$e = new G_Employee();
				$e->setId($employee_id);
				$salary = $e->getSalary($date);

				//if employee have assign schedule, proceed the computation, if not 0 value - start
				$ee        	 = G_Employee_Finder::findById($employee_id);
				$specific_sched = G_Schedule_Specific_Finder::findByEmployeeAndDate($ee, $date);
				$sched       = G_Schedule_Group_Finder::findByEmployeeAndDateStartEnd($ee, $date);
				if (!$sched && !$specific_sched) {
					$sched = G_Schedule_Finder::findDefaultByDate($date);
				}
				//if employee have assign schedule, proceed the computation, if not 0 value - end            

				//Custom OT
				if (!empty($custom_ot) && isset($custom_ot[$a->getDate()]) && $custom_ot[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_HOLIDAY && $t->getTotalHoursWorked() > 0 && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time'])) {
					$timestamp_start = $custom_ot[$a->getDate()]['start_time'];
					$timestamp_end   = $custom_ot[$a->getDate()]['end_time'];

					$emp = G_Employee_Finder::findById($a->getEmployeeId());
					$day_type[]               = "applied_to_legal_holiday";

					$schedule['schedule_in']  = $t->getScheduledDateIn() . " " .  $t->getScheduledTimeIn();
					$schedule['schedule_out'] = $t->getScheduledDateOut() . " " .  $t->getScheduledTimeOut();

					$schedule['actual_in']    = $t->getTimeIn();
					$schedule['actual_out']   = $t->getTimeOut();
					$deductible_breaktime     = $emp->getTotalBreakTimeHrsDeductible($schedule, $day_type);

					if ($timestamp_start > $timestamp_end) {
						$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];

						$date = new DateTime($a->getDate());
						$date->modify('+1 day');
						$date_end = $date->format('Y-m-d');
						$date_end = $date_end . " " . $custom_ot[$a->getDate()]['end_time'];
					} else {
						$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
						$date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
					}
					$custom_ot_hours_worked = Tools::computeHoursDifferenceByDateTime($date_start, $date_end) - $deductible_breaktime;
					$is_with_custom_ot = true;
				}

				if (!empty($salary) && $salary->getType() == 'Monthly') {
					$holiday_legal_divisor = $holiday_legal_rate;
				} else {
					$holiday_legal_divisor = 100;
				}

				if ($a->isHoliday() && !$a->isOfficialBusiness()) {

					$h = $a->getHoliday();
					if ($h && $h->isLegal()) {

						if ($specific_sched || $sched) {

							$employee_id   = $a->getEmployeeId();
							$previous_date = date("Y-m-d", strtotime("-1 days", strtotime($date)));
							$fields        = array("ea.is_present", "ea.is_paid", "ea.is_leave", "ea.is_ob");
							$yesterday	   = G_Attendance_Helper::sqlGetNearestRegularDay($employee_id, $date); //Get previous date attendance 

							$cutoff_period = G_Monthly_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($date);
							if (($a->isPresent() && $yesterday['is_present']) || ($a->isPresent() && $yesterday['is_leave'] == 1 && $yesterday['is_paid'] == 1) || ($a->isPresent() && $yesterday['is_ob'] == 1 && $yesterday['is_paid'] == 1)  || ($a->isPresent() && $a->isPaid())) { //if both previous date and holiday is present or on leave with pay before holiday and present on holiday - double pay						
								$hours_worked = $t->getTotalHoursWorked();
								if ($is_with_custom_ot) {
									$hours_worked = $custom_ot_hours_worked;
								}

								if (strtolower($mandated_status) == 'enable') {
									$new_amount  += $hourly_amount * (($rate->getHolidayLegal() - $omit_100) / 100) * $hours_worked;
								} else {
									$new_amount  += $hourly_amount * ((100 - $omit_100) / 100) * $hours_worked;
								}
							} elseif ((!$a->isPresent() && $yesterday['is_present']) && ($previous_date >= $cutoff_period['period_start']) && ($previous_date <= $cutoff_period['period_end'])) {

								$hours_worked = $t->getTotalScheduleHours();
								if ($is_with_custom_ot) {
									$hours_worked = $custom_ot_hours_worked;
								}

								$new_amount += 0;
							} else { //Regular paid if absent before holiday and present on holiday or vice versa                    	                    	
								if ($t) {
									$required_schedule_total_hrs = $t->getTotalScheduleHours();
									$hours_worked = $t->getTotalHoursWorked();
									if ($is_with_custom_ot) {
										$hours_worked = $custom_ot_hours_worked;
									}

									$new_amount += 0;
								}
							}
						} else {
							$new_amount += 0;
						}
					}
				}
			}
		}

		return $new_amount;
	}

	//new
	public static function computeLegalAmountDoublePay($attendance, $rate, $daily_amount, $hourly_amount, $custom_ot = array(), $mandated_status = 'Enable')
	{
		$amount             = 0;
		$omit_100 = 100; // if legal, additional 200% pay to employee. we will compute it from 100% because the other 100% is in the regular pay already
		if (strtolower($mandated_status) == 'enable') {
			$holiday_legal_rate = $rate->getHolidayLegal();
		} else {
			$holiday_legal_rate = 100;
		}
		//var_dump($attendance);
		$total_hrs_worked   = 0;
		$new_amount         = 0;
		//$hourly_amount * ($rate->getHolidayLegal() / 100) * $num_hrs;
		foreach ($attendance as $a) {
			$is_with_custom_ot = false;
			$employee_id = $a->getEmployeeId();
			$date        = $a->getDate();
			$t 			 = $a->getTimesheet();

			$e = new G_Employee();
			$e->setId($employee_id);
			$salary = $e->getSalary($date);

			$attendance_breaks = G_Employee_Break_logs_Summary_Finder::findByEmployeeAttendanceId($a->getId());
			$breaks = $a->groupTimesheetData()['breaktime'];
			$breaktime_hours = 0;
			if (!$attendance_breaks) {
				foreach ($breaks as $break) {
					$tarray = explode(" to ", $break);
					$breaktime_hours += Tools::computeHoursDifferenceByDateTime(date("H:i:s", strtotime($tarray[0])), date("H:i:s", strtotime($tarray[1])));
				}
			} else {
				$breaktime_hours = $t->getTotalDeductibleBreaktimeHours();
			}

			//if employee have assign schedule, proceed the computation, if not 0 value - start
			$ee        	 = G_Employee_Finder::findById($employee_id);
			$specific_sched = G_Schedule_Specific_Finder::findByEmployeeAndDate($ee, $date);
			$sched       = G_Schedule_Group_Finder::findByEmployeeAndDateStartEnd($ee, $date);
			if (!$sched && !$specific_sched) {
				$sched = G_Schedule_Finder::findDefaultByDate($date);
			}
			//if employee have assign schedule, proceed the computation, if not 0 value - end   


			if (!empty($custom_ot) && isset($custom_ot[$a->getDate()]) && $custom_ot[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_HOLIDAY && $t->getTotalHoursWorked() > 0 && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time'])) {

				$timestamp_start = $custom_ot[$a->getDate()]['start_time'];
				$timestamp_end   = $custom_ot[$a->getDate()]['end_time'];
				if ($timestamp_start > $timestamp_end) {

					$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
					//$date_end   = date("Y-m-d",strtotime("+1 day", $a->getDate())) . " " . $custom_ot[$a->getDate()]['end_time'];
					$date = new DateTime($a->getDate());
					$date->modify('+1 day');
					$date_end = $date->format('Y-m-d');
					$date_end = $date_end . " " . $custom_ot[$a->getDate()]['end_time'];
				} else {

					$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
					$date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
				}
				$custom_ot_hours_worked = Tools::computeHoursDifferenceByDateTime($date_start, $date_end);
				$is_with_custom_ot = true;
			}

			if (!empty($salary) && $salary->getType() == 'Monthly') {
				$holiday_legal_divisor = $holiday_legal_rate;
			} else {
				$holiday_legal_divisor = 100;
			}

			if ($a->isHoliday() && !$a->isOfficialBusiness() && !$a->isRestday()) {
				$h = $a->getHoliday();
				if ($h && $h->isLegal()) {


					if ($specific_sched || $sched) {

						$employee_id   = $a->getEmployeeId();
						$previous_date = date("Y-m-d", strtotime("-1 days", strtotime($date)));
						$fields        = array("ea.is_present", "ea.is_paid", "ea.is_leave", "ea.is_ob");
						$yesterday	   = G_Attendance_Helper::sqlGetNearestRegularDay($employee_id, $date); //Get previous date attendance                    

						if (($a->isPresent() && $yesterday['is_present']) || ($a->isPresent() && $yesterday['is_leave'] == 1 && $yesterday['is_paid'] == 1) || ($a->isPresent() && $yesterday['is_ob'] == 1 && $yesterday['is_paid'] == 1)) {
							//if both previous date and holiday is present or on leave with pay before holiday and present on holiday - double pay
							$hours_worked = $t->getTotalScheduleHours();

							if ($is_with_custom_ot) {
								$hours_worked = $custom_ot_hours_worked;
							}

							if (strtolower($mandated_status) == 'enable') {

								// $hours_worked = $t->getTotalHoursWorked();
								$hours_worked  = $hours_worked - $breaktime_hours;

								$new_amount += ($hours_worked * $hourly_amount) * ($rate->getHolidayLegal() / 100);
							} else {
								$hours_worked  = $hours_worked - $breaktime_hours;
								$new_amount += ($hours_worked * $hourly_amount) * (100 / 100);
							}
						} elseif ((!$a->isPresent() && $yesterday['is_present']) || (!$a->isPresent() && $yesterday['is_leave'] == 1 && $yesterday['is_paid'] == 1) || ($a->isPresent() && $yesterday['is_ob'] == 1 && $yesterday['is_paid'] == 1) || $yesterday['is_restday']) {

							$hours_worked = 8;
							if ($is_with_custom_ot) {

								$hours_worked = $custom_ot_hours_worked;
							}
							$new_amount  += $hourly_amount * $hours_worked;
						} else {
							// echo "6";
							//Regular paid if absent before holiday and present on holiday or vice versa                    	                    	         	
							if ($t && $a->isPresent()) {
								// echo "7";                        	
								$required_schedule_total_hrs = $t->getTotalScheduleHours();
								$hours_worked = $t->getTotalHoursWorked();
								// echo $hours_worked;
								// $break = G_Employee_Breaktime_Finder::findByEmployeeIdAndDate($employee_id,$date);
								// var_dump($employee_id);
								// var_dump($date);



								if ($is_with_custom_ot) {
									// echo "8";
									$hours_worked = $custom_ot_hours_worked;
								}


								$new_amount  += $hourly_amount * ($hours_worked - $breaktime_hours);
								// echo "9";   
							}
						}
					} else {

						$new_amount += 0;
					}
				}
			}
		}

		return $new_amount;
	}

	public static function computeLegalOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday()) {
				$h = $a->getHoliday();
				if (!empty($h) && $h->isLegal()) {
					$t = $a->getTimesheet();
					$holiday_legal_ot_hours = $t->getLegalOvertimeHours() + $t->getLegalOvertimeExcessHours();

					if (strtolower($mandated_status) == 'enable') {
						$holiday_legal_rate     = $rate->getHolidayLegal() - 100;
						$holiday_legal_ot_rate  = $rate->getHolidayLegalOvertime();
					} else {
						$holiday_legal_rate = 100;
						$holiday_legal_ot_rate = 100;
					}

					// $temp_amount = (float) Tools::numberFormat(($holiday_legal_ot_hours * ($hourly_amount * ($holiday_legal_rate/100))) * ($holiday_legal_ot_rate/100), 2);
					// $temp_amount = (float) Tools::numberFormat(($hourly_amount * $holiday_legal_ot_hours) * ($holiday_legal_ot_rate/100), 2);
					$temp_amount = (float) (($hourly_amount * $holiday_legal_ot_hours) * ($holiday_legal_ot_rate / 100));
					$amount = $amount + $temp_amount;
				}
			}
		}
		return $amount;
	}
	//new
	public static function computeCutLegalOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isHoliday()) {
					$h = $a->getHoliday();
					if (!empty($h) && $h->isLegal()) {
						$t = $a->getTimesheet();
						$holiday_legal_ot_hours = $t->getLegalOvertimeHours() + $t->getLegalOvertimeExcessHours();

						if (strtolower($mandated_status) == 'enable') {
							$holiday_legal_rate     = $rate->getHolidayLegal() - 100;
							$holiday_legal_ot_rate  = $rate->getHolidayLegalOvertime();
						} else {
							$holiday_legal_rate = 100;
							$holiday_legal_ot_rate = 100;
						}

						$temp_amount = (float) Tools::numberFormat(($holiday_legal_ot_hours * ($hourly_amount * ($holiday_legal_rate / 100))) * ($holiday_legal_ot_rate / 100), 2);
						$amount = $amount + $temp_amount;
					}
				}
			}
		}
		return $amount;
	}
	public static function computePrevLegalOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isHoliday()) {
					$h = $a->getHoliday();
					if (!empty($h) && $h->isLegal()) {
						$t = $a->getTimesheet();
						$holiday_legal_ot_hours = $t->getLegalOvertimeHours() + $t->getLegalOvertimeExcessHours();

						if (strtolower($mandated_status) == 'enable') {
							$holiday_legal_rate     = $rate->getHolidayLegal() - 100;
							$holiday_legal_ot_rate  = $rate->getHolidayLegalOvertime();
						} else {
							$holiday_legal_rate = 100;
							$holiday_legal_ot_rate = 100;
						}

						$temp_amount = (float) Tools::numberFormat(($holiday_legal_ot_hours * ($hourly_amount * ($holiday_legal_rate / 100))) * ($holiday_legal_ot_rate / 100), 2);
						$amount = $amount + $temp_amount;
					}
				}
			}
		}
		return $amount;
	}
	//new
	/*
     * @param array $a Array instance of G_Attendance
     */
	public static function computeSpecialAmountDepre($attendance, $rate, $daily_amount, $hourly_amount)
	{
		$amount = 0;

		$omit_100 = 100; // if legal, additional 130% pay to employee. we will compute it from 30% because the other 100% is in the regular pay already
		$holiday_special_rate = $rate->getHolidaySpecial() - $omit_100;

		foreach ($attendance as $a) {
			if ($a->isPresent() && ($a->isHoliday())) {
				$h = $a->getHoliday();
				if (!empty($h) && $h->isSpecial()) {
					$t = $a->getTimesheet();
					$hours_worked = $t->getTotalHoursWorked();

					if ($a->isOfficialBusiness()) {
						$hours_worked = 8;
					}

					if ($hours_worked >= 8) {
						$hours_worked = 8;
					}

					$hours_worked = Tools::numberFormat($hours_worked, 2);
					$multiplier = ($holiday_special_rate / 100);
					$temp_amount = (float) Tools::numberFormat($hours_worked * $hourly_amount * ($holiday_special_rate / 100), 2);
					$amount = $amount + $temp_amount;
				}
			}
		}
		return $amount;
	}

	public static function computeSpecialAmount($attendance, $rate, $daily_amount, $hourly_amount, $custom_ot = array(), $custom_ot_dis = array(), $mandated_status = 'Enable')
	{
		$amount = 0;

		$omit_100 = 100; // if legal, additional 130% pay to employee. we will compute it from 30% because the other 100% is in the regular pay already

		if (strtolower($mandated_status) == 'enable') {
			$holiday_special_rate = $rate->getHolidaySpecial() - $omit_100;

			$new_hourly_rate      = $hourly_amount * ($rate->getHolidaySpecial() / 100);
		} else {
			$holiday_special_rate = 100 - $omit_100;

			$new_hourly_rate      = $hourly_amount * 1.00;
		}



		foreach ($attendance as $a) {
			if (($a->isPresent() && $a->isHoliday()) && !$a->isRestday()) {
				$h = $a->getHoliday();
				$t = $a->getTimesheet();
				if (!empty($h) && $h->isSpecial()) {

					//if employee have assign schedule, proceed the computation, if not 0 value - start
					$employee_id = $a->getEmployeeId();
					$date  		 = $a->getDate();
					$e        	 = G_Employee_Finder::findById($employee_id);
					$specific_sched = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, $date);
					$sched       = G_Schedule_Group_Finder::findByEmployeeAndDateStartEnd($e, $date);
					if (!$sched && !$specific_sched) {
						$sched = G_Schedule_Finder::findDefaultByDate($date);
					}
					//if employee have assign schedule, proceed the computation, if not 0 value - end

					if ($specific_sched || $sched) {

						if (!empty($custom_ot) && isset($custom_ot[$a->getDate()]) && $custom_ot[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_HOLIDAY && $t->getTotalHoursWorked() > 0) {

							$timestamp_start = $custom_ot[$a->getDate()]['start_time'];
							$timestamp_end   = $custom_ot[$a->getDate()]['end_time'];
							if ($timestamp_start > $timestamp_end) {
								$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
								$date = new DateTime($a->getDate());
								$date->modify('+1 day');
								$date_end = $date->format('Y-m-d');
								$date_end = $date_end . " " . $custom_ot[$a->getDate()]['end_time'];
							} else {
								$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
								$date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
							}

							$ceiling_hours_worked_holiday_special_amount = 0;
							$ceiling_hours_worked_holiday_special_amount = ($t->totalHrsWorkedBaseOnSchedule() / 2) + $t->getTotalDeductibleBreaktimeHours();

							$hours_worked = Tools::computeHoursDifferenceByDateTime($date_start, $date_end);

							if ($hours_worked >= $ceiling_hours_worked_holiday_special_amount) {
								$hours_worked = $hours_worked - $t->getTotalDeductibleBreaktimeHours();
							} else {
								$hours_worked = $hours_worked;
							}
						} else {
							$t = $a->getTimesheet();

							$hours_worked = $t->getTotalHoursWorked();
							if ($a->isOfficialBusiness()) {
								$hours_worked = 8;
							}
						}

						// if ($hours_worked >= 8) {
						// 	$hours_worked = 8;
						// }

						if (!empty($custom_ot_dis) && isset($custom_ot_dis[$a->getDate()]) && $custom_ot_dis[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_HOLIDAY) {
							$hours_worked = 0;
						}
					} else {
						$hours_worked = 0;
					}

					$hours_worked = Tools::numberFormat($hours_worked, 2);
					$total_hrs_worked += $hours_worked;
				}
			}
		}

		if (strtolower($mandated_status) == 'enable') {
			$new_amount = (float) Tools::numberFormat($total_hrs_worked * $hourly_amount * ($rate->getHolidaySpecial() / 100), 2);
		} else {
			$new_amount = (float) Tools::numberFormat($total_hrs_worked * $hourly_amount * (100 / 100), 2);
		}

		// var_dump($total_hrs_worked); exit;
		return $new_amount;
	}
	//new
	public static function computePrevSpecialAmount($attendance, $rate, $daily_amount, $hourly_amount, $custom_ot = array(), $custom_ot_dis = array(), $mandated_status = 'Enable')
	{
		$amount = 0;

		$omit_100 = 100; // if legal, additional 130% pay to employee. we will compute it from 30% because the other 100% is in the regular pay already

		if (strtolower($mandated_status) == 'enable') {
			$holiday_special_rate = $rate->getHolidaySpecial() - $omit_100;

			$new_hourly_rate      = $hourly_amount * ($rate->getHolidaySpecial() / 100);
		} else {
			$holiday_special_rate = 100 - $omit_100;

			$new_hourly_rate      = $hourly_amount * 1.00;
		}

		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if (($a->isPresent() && $a->isHoliday()) && !$a->isRestday()) {
					$h = $a->getHoliday();
					$t = $a->getTimesheet();
					if (!empty($h) && $h->isSpecial()) {

						//if employee have assign schedule, proceed the computation, if not 0 value - start
						$employee_id = $a->getEmployeeId();
						$date  		 = $a->getDate();
						$e        	 = G_Employee_Finder::findById($employee_id);
						$specific_sched = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, $date);
						$sched       = G_Schedule_Group_Finder::findByEmployeeAndDateStartEnd($e, $date);
						if (!$sched && !$specific_sched) {
							$sched = G_Schedule_Finder::findDefaultByDate($date);
						}
						//if employee have assign schedule, proceed the computation, if not 0 value - end

						if ($specific_sched || $sched) {

							if (!empty($custom_ot) && isset($custom_ot[$a->getDate()]) && $custom_ot[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_HOLIDAY && $t->getTotalHoursWorked() > 0) {

								$timestamp_start = $custom_ot[$a->getDate()]['start_time'];
								$timestamp_end   = $custom_ot[$a->getDate()]['end_time'];
								if ($timestamp_start > $timestamp_end) {
									$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
									$date = new DateTime($a->getDate());
									$date->modify('+1 day');
									$date_end = $date->format('Y-m-d');
									$date_end = $date_end . " " . $custom_ot[$a->getDate()]['end_time'];
								} else {
									$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
									$date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
								}

								$ceiling_hours_worked_holiday_special_amount = 0;
								$ceiling_hours_worked_holiday_special_amount = ($t->totalHrsWorkedBaseOnSchedule() / 2) + $t->getTotalDeductibleBreaktimeHours();

								$hours_worked = Tools::computeHoursDifferenceByDateTime($date_start, $date_end);

								if ($hours_worked >= $ceiling_hours_worked_holiday_special_amount) {
									$hours_worked = $hours_worked - $t->getTotalDeductibleBreaktimeHours();
								} else {
									$hours_worked = $hours_worked;
								}
							} else {
								$t = $a->getTimesheet();

								$hours_worked = $t->getTotalHoursWorked();
								if ($a->isOfficialBusiness()) {
									$hours_worked = 8;
								}
							}

							if ($hours_worked >= 8) {
								$hours_worked = 8;
							}

							if (!empty($custom_ot_dis) && isset($custom_ot_dis[$a->getDate()]) && $custom_ot_dis[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_HOLIDAY) {
								$hours_worked = 0;
							}
						} else {
							$hours_worked = 0;
						}

						$hours_worked = Tools::numberFormat($hours_worked, 2);
						$total_hrs_worked += $hours_worked;
					}
				}
			}


			///--
		}

		if (strtolower($mandated_status) == 'enable') {
			$new_amount = (float) Tools::numberFormat($total_hrs_worked * $hourly_amount * ($rate->getHolidaySpecial() / 100), 2);
		} else {
			$new_amount = (float) Tools::numberFormat($total_hrs_worked * $hourly_amount * (100 / 100), 2);
		}

		return $new_amount;
	}
	public static function computeCutSpecialAmount($attendance, $rate, $daily_amount, $hourly_amount, $custom_ot = array(), $custom_ot_dis = array(), $mandated_status = 'Enable')
	{
		$amount = 0;

		$omit_100 = 100; // if legal, additional 130% pay to employee. we will compute it from 30% because the other 100% is in the regular pay already        
		if (strtolower($mandated_status) == 'enable') {
			$holiday_special_rate = $rate->getHolidaySpecial() - $omit_100;

			$new_hourly_rate      = $hourly_amount * ($rate->getHolidaySpecial() / 100);
		} else {
			$holiday_special_rate = 100 - $omit_100;

			$new_hourly_rate      = $hourly_amount * 1.00;
		}

		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if (($a->isPresent() && $a->isHoliday()) && !$a->isRestday()) {
					$h = $a->getHoliday();
					$t = $a->getTimesheet();
					if (!empty($h) && $h->isSpecial()) {

						//if employee have assign schedule, proceed the computation, if not 0 value - start
						$employee_id = $a->getEmployeeId();
						$date  		 = $a->getDate();
						$e        	 = G_Employee_Finder::findById($employee_id);
						$specific_sched = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, $date);
						$sched       = G_Schedule_Group_Finder::findByEmployeeAndDateStartEnd($e, $date);
						if (!$sched && !$specific_sched) {
							$sched = G_Schedule_Finder::findDefaultByDate($date);
						}
						//if employee have assign schedule, proceed the computation, if not 0 value - end

						if ($specific_sched || $sched) {

							if (!empty($custom_ot) && isset($custom_ot[$a->getDate()]) && $custom_ot[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_HOLIDAY && $t->getTotalHoursWorked() > 0) {

								$timestamp_start = $custom_ot[$a->getDate()]['start_time'];
								$timestamp_end   = $custom_ot[$a->getDate()]['end_time'];
								if ($timestamp_start > $timestamp_end) {
									$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
									$date = new DateTime($a->getDate());
									$date->modify('+1 day');
									$date_end = $date->format('Y-m-d');
									$date_end = $date_end . " " . $custom_ot[$a->getDate()]['end_time'];
								} else {
									$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
									$date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
								}

								$ceiling_hours_worked_holiday_special_amount = 0;
								$ceiling_hours_worked_holiday_special_amount = ($t->totalHrsWorkedBaseOnSchedule() / 2) + $t->getTotalDeductibleBreaktimeHours();

								$hours_worked = Tools::computeHoursDifferenceByDateTime($date_start, $date_end);

								if ($hours_worked >= $ceiling_hours_worked_holiday_special_amount) {
									$hours_worked = $hours_worked - $t->getTotalDeductibleBreaktimeHours();
								} else {
									$hours_worked = $hours_worked;
								}
							} else {
								$t = $a->getTimesheet();

								$hours_worked = $t->getTotalHoursWorked();
								if ($a->isOfficialBusiness()) {
									$hours_worked = 8;
								}
							}

							if ($hours_worked >= 8) {
								$hours_worked = 8;
							}

							if (!empty($custom_ot_dis) && isset($custom_ot_dis[$a->getDate()]) && $custom_ot_dis[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_HOLIDAY) {
								$hours_worked = 0;
							}
						} else {
							$hours_worked = 0;
						}

						$hours_worked = Tools::numberFormat($hours_worked, 2);
						$total_hrs_worked += $hours_worked;
					}
				}
			}


			///--
		}

		if (strtolower($mandated_status) == 'enable') {
			$new_amount = (float) Tools::numberFormat($total_hrs_worked * $hourly_amount * ($rate->getHolidaySpecial() / 100), 2);
		} else {
			$new_amount = (float) Tools::numberFormat($total_hrs_worked * $hourly_amount * (100 / 100), 2);
		}

		return $new_amount;
	}
	//new
	public static function computeSpecialAmountNoOmit($attendance, $rate, $daily_amount, $hourly_amount, $custom_ot = array(), $custom_ot_dis = array(), $mandated_status = 'Enable')
	{
		$amount = 0;

		$omit_100 = 100; // if legal, additional 130% pay to employee. we will compute it from 30% because the other 100% is in the regular pay already
		//$holiday_special_rate = $rate->getHolidaySpecial() - $omit_100;

		if (strtolower($mandated_status) == 'enable') {
			$holiday_special_rate = $rate->getHolidaySpecial();

			$new_hourly_rate      = $hourly_amount * ($rate->getHolidaySpecial() / 100);
		} else {
			$holiday_special_rate = 100;

			$new_hourly_rate      = $hourly_amount * 1.00;
		}
		$total_hrs_worked = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && ($a->isHoliday()) && !$a->isOfficialBusiness() && !$a->isRestday()) {

				//if ($a->isPresent() && ($a->isHoliday()) && !$a->isRestday() && !$a->isOfficialBusiness()) {
				$h = $a->getHoliday();
				$t = $a->getTimesheet();

				if (!empty($h) && $h->isSpecial()) {


					//if employee have assign schedule, proceed the computation, if not 0 value - start
					$employee_id = $a->getEmployeeId();
					$date  		 = $a->getDate();
					$e        	 = G_Employee_Finder::findById($employee_id);
					$specific_sched = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, $date);
					$sched       = G_Schedule_Group_Finder::findByEmployeeAndDateStartEnd($e, $date);
					if (!$sched && !$specific_sched) {
						$sched = G_Schedule_Finder::findDefaultByDate($date);
					}
					//if employee have assign schedule, proceed the computation, if not 0 value - end  

					if ($specific_sched || $sched) {

						if (!empty($custom_ot) && isset($custom_ot[$a->getDate()]) && $custom_ot[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_HOLIDAY && $t->getTotalHoursWorked() > 0 && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time'])) {

							$timestamp_start = $custom_ot[$a->getDate()]['start_time'];
							$timestamp_end   = $custom_ot[$a->getDate()]['end_time'];
							if ($timestamp_start > $timestamp_end) {

								$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
								//$date_end   = date("Y-m-d",strtotime("+1 day", $a->getDate())) . " " . $custom_ot[$a->getDate()]['end_time'];
								$date = new DateTime($a->getDate());
								$date->modify('+1 day');
								$date_end = $date->format('Y-m-d');
								$date_end = $date_end . " " . $custom_ot[$a->getDate()]['end_time'];
							} else {

								$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
								$date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
							}
							$hours_worked = $t->getTotalHoursWorked();
							if ($hours_worked >= 8) {
								$hours_worked = 8;
							} else {
								$hours_worked = Tools::computeHoursDifferenceByDateTime($date_start, $date_end);
							}
							//$hours_worked = Tools::computeHoursDifferenceByDateTime($date_start, $date_end);

						} else {

							$hours_worked = $t->getTotalHoursWorked();
							if ($hours_worked >= 8) {
								$hours_worked = 8;
							}
							if ($a->isOfficialBusiness()) {
								$hours_worked = 8;
							}
						}

						if (!empty($custom_ot_dis) && isset($custom_ot_dis[$a->getDate()]) && $custom_ot_dis[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_HOLIDAY) {

							$hours_worked = 0;
						}
					} else {


						$hours_worked = 0;
					}

					$hours_worked = Tools::numberFormat($hours_worked, 2);
					$total_hrs_worked += $hours_worked;
				}
			}
		}

		if (strtolower($mandated_status) == 'enable') {
			$new_amount = (float) Tools::numberFormat($total_hrs_worked * $hourly_amount * ($rate->getHolidaySpecial() / 100), 2);
			$total_hrs_worked * $hourly_amount * ($rate->getHolidaySpecial() / 100);
			// echo $total_hrs_worked . " * " . $hourly_amount ." * ". "(".$rate->getHolidaySpecial() ."/100)";
		} else {

			$new_amount = (float) Tools::numberFormat($total_hrs_worked * $hourly_amount * (100 / 100), 2);
		}

		return $new_amount;
	}

	public static function computeRestDaySpecialAmount($attendance, $rate, $daily_amount, $hourly_amount, $custom_ot = array(), $mandated_status = 'Enable')
	{
		$amount = 0;


		foreach ($attendance as $a) {

			$t = $a->getTimesheet();
			if ($a->isPresent() && $a->isHoliday() && $a->isRestday() && !$a->isOfficialBusiness() && ($t->getScheduledDateIn() != '' && $t->getScheduledDateOut() != '') && ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeOut() != '')) {
				//if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {

				//if employee have assign schedule, proceed the computation, if not 0 value - start
				$employee_id = $a->getEmployeeId();
				$date        = $a->getDate();
				$ee        	 = G_Employee_Finder::findById($employee_id);
				$specific_sched = G_Schedule_Specific_Finder::findByEmployeeAndDate($ee, $date);
				$sched       = G_Schedule_Group_Finder::findByEmployeeAndDateStartEnd($ee, $date);
				if (!$sched && !$specific_sched) {
					$sched = G_Schedule_Finder::findDefaultByDate($date);
				}
				//if employee have assign schedule, proceed the computation, if not 0 value - end     

				$h = $a->getHoliday();
				if (!empty($h) && $h->isSpecial()) {

					$t = $a->getTimesheet();
					if ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {

						$hours_worked = $t->getTotalHoursWorked();
						if ($a->isOfficialBusiness()) {
							$hours_worked = 8;
						}

						if ($specific_sched || $sched) {

							if (!empty($custom_ot) && isset($custom_ot[$a->getDate()])  && $t->getTotalHoursWorked() > 0 && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time'])) {

								$timestamp_start = $custom_ot[$a->getDate()]['start_time'];
								$timestamp_end   = $custom_ot[$a->getDate()]['end_time'];

								$e = G_Employee_Finder::findById($a->getEmployeeId());
								$day_type[] = "applied_to_restday";

								$schedule['schedule_in']  = $timestamp_start;
								$schedule['schedule_out'] = $timestamp_end;
								$schedule['actual_in']    = $t->getTimeIn();
								$schedule['actual_out']   = $t->getTimeOut();
								$deductible_breaktime     = $e->getTotalBreakTimeHrsDeductible($schedule, $day_type);

								if ($timestamp_start > $timestamp_end) {
									$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
									$date_end   = date("Y-m-d", strtotime("+1 day", $a->getDate())) . " " . $custom_ot[$a->getDate()]['end_time'];
								} else {
									$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
									$date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
								}
								$hours_worked = Tools::computeHoursDifferenceByDateTime($date_start, $date_end) - $deductible_breaktime;
								if ($hours_worked >= $t->totalHrsWorkedBaseOnSchedule()) {
									$hours_worked = $t->totalHrsWorkedBaseOnSchedule();
								}
							}
						} else {
							$hours_worked = 0;
						}
					}

					if (strtolower($mandated_status) == 'enable') {
						$holiday_special_rate = $rate->getHolidaySpecialRestday() / 100;
					} else {
						$holiday_special_rate = 1.00;
					}

					$hours_worked = (float) $hours_worked;
					//old  $hourly_amount = $hourly_amount * $holiday_special_rate;
					$temp_amount = (float) Tools::numberFormat($hours_worked * $hourly_amount * $holiday_special_rate, 2);
					$amount = $amount + $temp_amount;
				}
			}
		}
		return $amount;
	}
	//new
	public static function computePrevRestDaySpecialAmount($attendance, $rate, $daily_amount, $hourly_amount, $custom_ot = array(), $mandated_status = 'Enable')
	{
		$amount = 0;


		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				$t = $a->getTimesheet();
				if ($a->isPresent() && $a->isHoliday() && $a->isRestday() && !$a->isOfficialBusiness() && ($t->getScheduledDateIn() != '' && $t->getScheduledDateOut() != '') && ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeOut() != '')) {
					//if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {

					//if employee have assign schedule, proceed the computation, if not 0 value - start
					$employee_id = $a->getEmployeeId();
					$date        = $a->getDate();
					$ee        	 = G_Employee_Finder::findById($employee_id);
					$specific_sched = G_Schedule_Specific_Finder::findByEmployeeAndDate($ee, $date);
					$sched       = G_Schedule_Group_Finder::findByEmployeeAndDateStartEnd($ee, $date);
					if (!$sched && !$specific_sched) {
						$sched = G_Schedule_Finder::findDefaultByDate($date);
					}
					//if employee have assign schedule, proceed the computation, if not 0 value - end     

					$h = $a->getHoliday();
					if (!empty($h) && $h->isSpecial()) {

						$t = $a->getTimesheet();
						if ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {

							$hours_worked = $t->getTotalHoursWorked();
							if ($a->isOfficialBusiness()) {
								$hours_worked = 8;
							}

							if ($specific_sched || $sched) {

								if (!empty($custom_ot) && isset($custom_ot[$a->getDate()])  && $t->getTotalHoursWorked() > 0 && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time'])) {

									$timestamp_start = $custom_ot[$a->getDate()]['start_time'];
									$timestamp_end   = $custom_ot[$a->getDate()]['end_time'];

									$e = G_Employee_Finder::findById($a->getEmployeeId());
									$day_type[] = "applied_to_restday";

									$schedule['schedule_in']  = $timestamp_start;
									$schedule['schedule_out'] = $timestamp_end;
									$schedule['actual_in']    = $t->getTimeIn();
									$schedule['actual_out']   = $t->getTimeOut();
									$deductible_breaktime     = $e->getTotalBreakTimeHrsDeductible($schedule, $day_type);

									if ($timestamp_start > $timestamp_end) {
										$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
										$date_end   = date("Y-m-d", strtotime("+1 day", $a->getDate())) . " " . $custom_ot[$a->getDate()]['end_time'];
									} else {
										$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
										$date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
									}
									$hours_worked = Tools::computeHoursDifferenceByDateTime($date_start, $date_end) - $deductible_breaktime;
									if ($hours_worked >= $t->totalHrsWorkedBaseOnSchedule()) {
										$hours_worked = $t->totalHrsWorkedBaseOnSchedule();
									}
								}
							} else {
								$hours_worked = 0;
							}
						}

						if (strtolower($mandated_status) == 'enable') {
							$holiday_special_rate = $rate->getHolidaySpecialRestday() / 100;
						} else {
							$holiday_special_rate = 1.00;
						}

						$hours_worked = (float) $hours_worked;
						//old  $hourly_amount = $hourly_amount * $holiday_special_rate;
						$temp_amount = (float) Tools::numberFormat($hours_worked * $hourly_amount * $holiday_special_rate, 2);
						$amount = $amount + $temp_amount;
					}
				}
			}


			///--
		}
		return $amount;
	}

	public static function computeCutRestDaySpecialAmount($attendance, $rate, $daily_amount, $hourly_amount, $custom_ot = array(), $mandated_status = 'Enable')
	{
		$amount = 0;


		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				$t = $a->getTimesheet();
				if ($a->isPresent() && $a->isHoliday() && $a->isRestday() && !$a->isOfficialBusiness() && ($t->getScheduledDateIn() != '' && $t->getScheduledDateOut() != '') && ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeOut() != '')) {
					//if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {

					//if employee have assign schedule, proceed the computation, if not 0 value - start
					$employee_id = $a->getEmployeeId();
					$date        = $a->getDate();
					$ee        	 = G_Employee_Finder::findById($employee_id);
					$specific_sched = G_Schedule_Specific_Finder::findByEmployeeAndDate($ee, $date);
					$sched       = G_Schedule_Group_Finder::findByEmployeeAndDateStartEnd($ee, $date);
					if (!$sched && !$specific_sched) {
						$sched = G_Schedule_Finder::findDefaultByDate($date);
					}
					//if employee have assign schedule, proceed the computation, if not 0 value - end     

					$h = $a->getHoliday();
					if (!empty($h) && $h->isSpecial()) {

						$t = $a->getTimesheet();
						if ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {

							$hours_worked = $t->getTotalHoursWorked();
							if ($a->isOfficialBusiness()) {
								$hours_worked = 8;
							}

							if ($specific_sched || $sched) {

								if (!empty($custom_ot) && isset($custom_ot[$a->getDate()])  && $t->getTotalHoursWorked() > 0 && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time'])) {

									$timestamp_start = $custom_ot[$a->getDate()]['start_time'];
									$timestamp_end   = $custom_ot[$a->getDate()]['end_time'];

									$e = G_Employee_Finder::findById($a->getEmployeeId());
									$day_type[] = "applied_to_restday";

									$schedule['schedule_in']  = $timestamp_start;
									$schedule['schedule_out'] = $timestamp_end;
									$schedule['actual_in']    = $t->getTimeIn();
									$schedule['actual_out']   = $t->getTimeOut();
									$deductible_breaktime     = $e->getTotalBreakTimeHrsDeductible($schedule, $day_type);

									if ($timestamp_start > $timestamp_end) {
										$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
										$date_end   = date("Y-m-d", strtotime("+1 day", $a->getDate())) . " " . $custom_ot[$a->getDate()]['end_time'];
									} else {
										$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
										$date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
									}
									$hours_worked = Tools::computeHoursDifferenceByDateTime($date_start, $date_end) - $deductible_breaktime;
									if ($hours_worked >= $t->totalHrsWorkedBaseOnSchedule()) {
										$hours_worked = $t->totalHrsWorkedBaseOnSchedule();
									}
								}
							} else {
								$hours_worked = 0;
							}
						}

						if (strtolower($mandated_status) == 'enable') {
							$holiday_special_rate = $rate->getHolidaySpecialRestday() / 100;
						} else {
							$holiday_special_rate = 1.00;
						}

						$hours_worked = (float) $hours_worked;
						//old  $hourly_amount = $hourly_amount * $holiday_special_rate;
						$temp_amount = (float) Tools::numberFormat($hours_worked * $hourly_amount * $holiday_special_rate, 2);
						$amount = $amount + $temp_amount;
					}
				}
			}


			///--
		}
		return $amount;
	}
	//new
	public static function computeRestDayLegalOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$t = $a->getTimesheet();
			if ($a->isPresent() && $a->isHoliday() && $a->isRestday() && !$a->isOfficialBusiness() && ($t->getScheduledDateIn() != '' && $t->getScheduledDateOut() != '') && ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeOut() != '')) {
				//if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
				$h = $a->getHoliday();
				if (!empty($h) && $h->isLegal()) {
					$t = $a->getTimesheet();
					$ot_hours_worked = $t->getTotalOvertimeHours();
					if ($a->isOfficialBusiness()) {
						$hours_worked = 8;
					}

					if (strtolower($mandated_status) == 'enable') {
						$holiday_legal_rest_day_overtime_rate = $rate->getHolidayLegalRestdayOvertime() / 100;
					} else {
						$holiday_legal_rest_day_overtime_rate = 1.00;
					}

					$ot_hours_worked = (float) $ot_hours_worked;
					//$hourly_amount = $hourly_amount * $holiday_legal_rate;

					$temp_amount = (float) Tools::numberFormat($ot_hours_worked * $hourly_amount * $holiday_legal_rest_day_overtime_rate, 2);

					$amount = $amount + $temp_amount;
				}
			}
		}

		return $amount;
	}
	//newstart
	public static function computePrevRestDayLegalOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				$t = $a->getTimesheet();
				if ($a->isPresent() && $a->isHoliday() && $a->isRestday() && !$a->isOfficialBusiness() && ($t->getScheduledDateIn() != '' && $t->getScheduledDateOut() != '') && ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeOut() != '')) {
					//if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
					$h = $a->getHoliday();
					if (!empty($h) && $h->isLegal()) {
						$t = $a->getTimesheet();
						$ot_hours_worked = $t->getTotalOvertimeHours();
						if ($a->isOfficialBusiness()) {
							$hours_worked = 8;
						}

						if (strtolower($mandated_status) == 'enable') {
							$holiday_legal_rest_day_overtime_rate = $rate->getHolidayLegalRestdayOvertime() / 100;
						} else {
							$holiday_legal_rest_day_overtime_rate = 1.00;
						}

						$ot_hours_worked = (float) $ot_hours_worked;
						//$hourly_amount = $hourly_amount * $holiday_legal_rate;

						$temp_amount = (float) Tools::numberFormat($ot_hours_worked * $hourly_amount * $holiday_legal_rest_day_overtime_rate, 2);

						$amount = $amount + $temp_amount;
					}
				}
			}
		}

		return $amount;
	}
	public static function computeCutRestDayLegalOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				$t = $a->getTimesheet();
				if ($a->isPresent() && $a->isHoliday() && $a->isRestday() && !$a->isOfficialBusiness() && ($t->getScheduledDateIn() != '' && $t->getScheduledDateOut() != '') && ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeOut() != '')) {
					//if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
					$h = $a->getHoliday();
					if (!empty($h) && $h->isLegal()) {
						$t = $a->getTimesheet();
						$ot_hours_worked = $t->getTotalOvertimeHours();
						if ($a->isOfficialBusiness()) {
							$hours_worked = 8;
						}

						if (strtolower($mandated_status) == 'enable') {
							$holiday_legal_rest_day_overtime_rate = $rate->getHolidayLegalRestdayOvertime() / 100;
						} else {
							$holiday_legal_rest_day_overtime_rate = 1.00;
						}

						$ot_hours_worked = (float) $ot_hours_worked;
						//$hourly_amount = $hourly_amount * $holiday_legal_rate;

						$temp_amount = (float) Tools::numberFormat($ot_hours_worked * $hourly_amount * $holiday_legal_rest_day_overtime_rate, 2);

						$amount = $amount + $temp_amount;
					}
				}
			}
		}

		return $amount;
	}
	//newend
	public static function computeSpecialOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday()) {
				$h = $a->getHoliday();
				if (!empty($h) && $h->isSpecial()) {
					$t = $a->getTimesheet();
					$holiday_special_ot_hours = $t->getSpecialOvertimeHours() + $t->getSpecialOvertimeExcessHours();

					if (strtolower($mandated_status) == 'enable') {
						$holiday_special_rate = $rate->getHolidaySpecial();
						$holiday_special_ot_rate = $rate->getHolidaySpecialOvertime();
					} else {
						$holiday_special_rate = 100;
						$holiday_special_ot_rate = 100;
					}

					$holiday_special_ot_hours = Tools::numberFormat($holiday_special_ot_hours, 2);
					// $temp_amount = (float) Tools::numberFormat(($holiday_special_ot_hours * ($hourly_amount * ($holiday_special_rate/100))) * ($holiday_special_ot_rate/100), 2);
					// $temp_amount = (float) Tools::numberFormat(($hourly_amount * $holiday_special_ot_hours) * ($holiday_special_ot_rate/100), 2);
					$temp_amount = (float) ($hourly_amount * $holiday_special_ot_hours) * ($holiday_special_ot_rate / 100);
					$amount = $amount + $temp_amount;
				}
			}
		}
		return $amount;
	}
	//new
	public static function computePrevSpecialOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isHoliday()) {
					$h = $a->getHoliday();
					if (!empty($h) && $h->isSpecial()) {
						$t = $a->getTimesheet();
						$holiday_special_ot_hours = $t->getSpecialOvertimeHours() + $t->getSpecialOvertimeExcessHours();

						if (strtolower($mandated_status) == 'enable') {
							$holiday_special_rate = $rate->getHolidaySpecial();
							$holiday_special_ot_rate = $rate->getHolidaySpecialOvertime();
						} else {
							$holiday_special_rate = 100;
							$holiday_special_ot_rate = 100;
						}

						$holiday_special_ot_hours = Tools::numberFormat($holiday_special_ot_hours, 2);
						$temp_amount = (float) Tools::numberFormat(($holiday_special_ot_hours * ($hourly_amount * ($holiday_special_rate / 100))) * ($holiday_special_ot_rate / 100), 2);
						$amount = $amount + $temp_amount;
					}
				}
			}
		}
		return $amount;
	}
	public static function computeCutSpecialOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isHoliday()) {
					$h = $a->getHoliday();
					if (!empty($h) && $h->isSpecial()) {
						$t = $a->getTimesheet();
						$holiday_special_ot_hours = $t->getSpecialOvertimeHours() + $t->getSpecialOvertimeExcessHours();

						if (strtolower($mandated_status) == 'enable') {
							$holiday_special_rate = $rate->getHolidaySpecial();
							$holiday_special_ot_rate = $rate->getHolidaySpecialOvertime();
						} else {
							$holiday_special_rate = 100;
							$holiday_special_ot_rate = 100;
						}

						$holiday_special_ot_hours = Tools::numberFormat($holiday_special_ot_hours, 2);
						$temp_amount = (float) Tools::numberFormat(($holiday_special_ot_hours * ($hourly_amount * ($holiday_special_rate / 100))) * ($holiday_special_ot_rate / 100), 2);
						$amount = $amount + $temp_amount;
					}
				}
			}
		}
		return $amount;
	}
	//new
	public static function computeRestDaySpecialOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$t = $a->getTimesheet();
			if ($a->isPresent() && $a->isHoliday() && $a->isRestday() && !$a->isOfficialBusiness() && ($t->getScheduledDateIn() != '' && $t->getScheduledDateOut() != '') && ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeOut() != '')) {
				//if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
				$h = $a->getHoliday();
				if (!empty($h) && $h->isSpecial()) {

					$t = $a->getTimesheet();
					if ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {

						$ot_hours = $t->getRestDaySpecialOvertimeHours() + $t->getRestDaySpecialOvertimeExcessHours();

						if (strtolower($mandated_status) == 'enable') {
							$day_rate = $rate->getHolidaySpecialRestday() / 100;
							$ot_rate = $rate->getHolidaySpecialRestdayOvertime() / 100;
						} else {
							$day_rate = 1.00;
							$ot_rate = 1.00;
						}

						// $hourly_amount = $hourly_amount * $day_rate * $ot_rate;
						$hourly_amount = $hourly_amount * $ot_hours;

						// $temp_amount = (float) Tools::numberFormat($hourly_amount * $ot_rate, 2);
						$temp_amount = (float) ($hourly_amount * $ot_rate);
						$amount = $amount + $temp_amount;
					}
				}
			}
		}
		return $amount;
	}

	//new
	public static function computePrevRestDaySpecialOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				$t = $a->getTimesheet();
				if ($a->isPresent() && $a->isHoliday() && $a->isRestday() && !$a->isOfficialBusiness() && ($t->getScheduledDateIn() != '' && $t->getScheduledDateOut() != '') && ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeOut() != '')) {
					//if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
					$h = $a->getHoliday();
					if (!empty($h) && $h->isSpecial()) {

						$t = $a->getTimesheet();
						if ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {

							$ot_hours = $t->getRestDaySpecialOvertimeHours() + $t->getRestDaySpecialOvertimeExcessHours();

							if (strtolower($mandated_status) == 'enable') {
								$day_rate = $rate->getHolidaySpecialRestday() / 100;
								$ot_rate = $rate->getHolidaySpecialRestdayOvertime() / 100;
							} else {
								$day_rate = 1.00;
								$ot_rate = 1.00;
							}

							//old$hourly_amount = $hourly_amount * $day_rate * $ot_rate;

							$temp_amount = (float) Tools::numberFormat($ot_hours * $hourly_amount * $day_rate * $ot_rate, 2);
							$amount = $amount + $temp_amount;
						}
					}
				}
			}
		}
		return $amount;
	}
	public static function computeCutRestDaySpecialOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				$t = $a->getTimesheet();
				if ($a->isPresent() && $a->isHoliday() && $a->isRestday() && !$a->isOfficialBusiness() && ($t->getScheduledDateIn() != '' && $t->getScheduledDateOut() != '') && ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeOut() != '')) {
					//if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
					$h = $a->getHoliday();
					if (!empty($h) && $h->isSpecial()) {

						$t = $a->getTimesheet();
						if ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {

							$ot_hours = $t->getRestDaySpecialOvertimeHours() + $t->getRestDaySpecialOvertimeExcessHours();

							if (strtolower($mandated_status) == 'enable') {
								$day_rate = $rate->getHolidaySpecialRestday() / 100;
								$ot_rate = $rate->getHolidaySpecialRestdayOvertime() / 100;
							} else {
								$day_rate = 1.00;
								$ot_rate = 1.00;
							}

							//old$hourly_amount = $hourly_amount * $day_rate * $ot_rate;

							$temp_amount = (float) Tools::numberFormat($ot_hours * $hourly_amount * $day_rate * $ot_rate, 2);
							$amount = $amount + $temp_amount;
						}
					}
				}
			}
		}
		return $amount;
	}
	//new


	public static function computeRestDayLegalAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {

			$t = $a->getTimesheet();
			if ($a->isPresent() && $a->isHoliday() && $a->isRestday() && !$a->isOfficialBusiness() && ($t->getScheduledDateIn() != '' && $t->getScheduledDateOut() != '') && ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeOut() != '')) {
				//if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
				$h = $a->getHoliday();
				if (!empty($h) && $h->isLegal()) {

					//if employee have assign schedule, proceed the computation, if not 0 value - start
					$employee_id = $a->getEmployeeId();
					$date        = $a->getDate();
					$ee        	 = G_Employee_Finder::findById($employee_id);
					$specific_sched = G_Schedule_Specific_Finder::findByEmployeeAndDate($ee, $date);
					$sched       = G_Schedule_Group_Finder::findByEmployeeAndDateStartEnd($ee, $date);
					if (!$sched && !$specific_sched) {
						$sched = G_Schedule_Finder::findDefaultByDate($date);
					}
					//if employee have assign schedule, proceed the computation, if not 0 value - end     

					if ($specific_sched || $sched) {

						$t = $a->getTimesheet();
						$hours_worked = $t->getTotalHoursWorked();

						if ($a->isOfficialBusiness()) {
							$hours_worked = 8;
						}
						$scheduled_hours_work = $t->getTotalScheduleHours();

						if (strtolower($mandated_status) == 'enable') {
							$holiday_legal_rate = $rate->getHolidayLegalRestday() / 100;
						} else {
							$holiday_legal_rate = 1.00;
						}

						if ($scheduled_hours_work <= $hours_worked) {

							$temp_amount = (float) Tools::numberFormat($holiday_legal_rate * $daily_amount, 2);
						} else {

							$hours_worked = (float) $hours_worked;
							$hourly_amount = $hourly_amount * $holiday_legal_rate;
							$temp_amount = (float) Tools::numberFormat($hours_worked * $hourly_amount, 2);
						}


						$amount = $amount + $temp_amount;
					} else {

						$amount += 0;
					}
				}
			}
		}
		return $amount;
	}

	//newstart
	public static function computePrevRestDayLegalAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				$t = $a->getTimesheet();
				if ($a->isPresent() && $a->isHoliday() && $a->isRestday() && !$a->isOfficialBusiness() && ($t->getScheduledDateIn() != '' && $t->getScheduledDateOut() != '') && ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeOut() != '')) {
					//if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
					$h = $a->getHoliday();
					if (!empty($h) && $h->isLegal()) {

						//if employee have assign schedule, proceed the computation, if not 0 value - start
						$employee_id = $a->getEmployeeId();
						$date        = $a->getDate();
						$ee        	 = G_Employee_Finder::findById($employee_id);
						$specific_sched = G_Schedule_Specific_Finder::findByEmployeeAndDate($ee, $date);
						$sched       = G_Schedule_Group_Finder::findByEmployeeAndDateStartEnd($ee, $date);
						if (!$sched && !$specific_sched) {
							$sched = G_Schedule_Finder::findDefaultByDate($date);
						}
						//if employee have assign schedule, proceed the computation, if not 0 value - end     

						if ($specific_sched || $sched) {

							$t = $a->getTimesheet();
							$hours_worked = $t->getTotalHoursWorked();

							if ($a->isOfficialBusiness()) {
								$hours_worked = 8;
							}
							$scheduled_hours_work = $t->getTotalScheduleHours();

							if (strtolower($mandated_status) == 'enable') {
								$holiday_legal_rate = $rate->getHolidayLegalRestday() / 100;
							} else {
								$holiday_legal_rate = 1.00;
							}

							if ($scheduled_hours_work <= $hours_worked) {

								$temp_amount = (float) Tools::numberFormat($holiday_legal_rate * $daily_amount, 2);
							} else {

								$hours_worked = (float) $hours_worked;
								$hourly_amount = $hourly_amount * $holiday_legal_rate;
								$temp_amount = (float) Tools::numberFormat($hours_worked * $hourly_amount, 2);
							}


							$amount = $amount + $temp_amount;
						} else {

							$amount += 0;
						}
					}
				}
			}


			///----
		}
		return $amount;
	}
	public static function computeCutRestDayLegalAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				$t = $a->getTimesheet();
				if ($a->isPresent() && $a->isHoliday() && $a->isRestday() && !$a->isOfficialBusiness() && ($t->getScheduledDateIn() != '' && $t->getScheduledDateOut() != '') && ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeOut() != '')) {
					//if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
					$h = $a->getHoliday();
					if (!empty($h) && $h->isLegal()) {

						//if employee have assign schedule, proceed the computation, if not 0 value - start
						$employee_id = $a->getEmployeeId();
						$date        = $a->getDate();
						$ee        	 = G_Employee_Finder::findById($employee_id);
						$specific_sched = G_Schedule_Specific_Finder::findByEmployeeAndDate($ee, $date);
						$sched       = G_Schedule_Group_Finder::findByEmployeeAndDateStartEnd($ee, $date);
						if (!$sched && !$specific_sched) {
							$sched = G_Schedule_Finder::findDefaultByDate($date);
						}
						//if employee have assign schedule, proceed the computation, if not 0 value - end     

						if ($specific_sched || $sched) {

							$t = $a->getTimesheet();
							$hours_worked = $t->getTotalHoursWorked();

							if ($a->isOfficialBusiness()) {
								$hours_worked = 8;
							}
							$scheduled_hours_work = $t->getTotalScheduleHours();

							if (strtolower($mandated_status) == 'enable') {
								$holiday_legal_rate = $rate->getHolidayLegalRestday() / 100;
							} else {
								$holiday_legal_rate = 1.00;
							}

							if ($scheduled_hours_work <= $hours_worked) {

								$temp_amount = (float) Tools::numberFormat($holiday_legal_rate * $daily_amount, 2);
							} else {

								$hours_worked = (float) $hours_worked;
								$hourly_amount = $hourly_amount * $holiday_legal_rate;
								$temp_amount = (float) Tools::numberFormat($hours_worked * $hourly_amount, 2);
							}


							$amount = $amount + $temp_amount;
						} else {

							$amount += 0;
						}
					}
				}
			}


			///----
		}
		return $amount;
	}
	//newend
	public static function computeRestDayLegalOvertimeAmountDepre02($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
				$h = $a->getHoliday();
				if (!empty($h) && $h->isLegal()) {
					$t = $a->getTimesheet();
					$ot_hours = $t->getRestDayLegalOvertimeHours() + $t->getRestDayLegalOvertimeExcessHours();

					if (strtolower($mandated_status) == 'enable') {
						$day_rate = $rate->getHolidayLegalRestday() / 100;
						$ot_rate = $rate->getHolidayLegalRestdayOvertime() / 100;
					} else {
						$day_rate = 1.00;
						$ot_rate = 1.00;
					}

					$hourly_amount = $hourly_amount * $day_rate * $ot_rate;

					$temp_amount = (float) Tools::numberFormat($ot_hours * $hourly_amount, 2);
					$amount = $amount + $temp_amount;
				}
			}
		}
		return $amount;
	}

	public static function computeRestDaySpecialOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
				$h = $a->getHoliday();
				if (!empty($h) && $h->isSpecial()) {

					$t = $a->getTimesheet();
					if ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {

						$ot_hours = $t->getRestDaySpecialOvertimeNightShiftHours() + $t->getRestDaySpecialOvertimeNightShiftExcessHours();

						if (strtolower($mandated_status) == 'enable') {
							$day_rate = $rate->getHolidaySpecialRestday() / 100;
							$ot_rate = $rate->getHolidaySpecialRestdayOvertime() / 100;
							$ns_rate = $rate->getHolidaySpecialRestdayNightDifferentialOvertime() / 100;
						} else {
							$day_rate = 1.00;
							$ot_rate = 1.00;
							$ns_rate = 1.00;
						}
						//  $day_rate = $rate->getHolidaySpecialRestday() / 100;
						//  $ot_rate = $rate->getHolidaySpecialRestdayOvertime() / 100;
						// //old $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
						// $ns_rate = $rate->getHolidaySpecialRestdayNightDifferentialOvertime();
						//  //$hourly_amount = $hourly_amount * $day_rate * $ot_rate;

						$temp_amount = (float) Tools::numberFormat($ot_hours * $hourly_amount * $ns_rate, 2);
						$amount = $amount + $temp_amount;
					}
				}
			}
		}
		return $amount;
	}
	//newstart
	public static function computePrevRestDaySpecialOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
					$h = $a->getHoliday();
					if (!empty($h) && $h->isSpecial()) {

						$t = $a->getTimesheet();
						if ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {

							$ot_hours = $t->getRestDaySpecialOvertimeNightShiftHours() + $t->getRestDaySpecialOvertimeNightShiftExcessHours();

							if (strtolower($mandated_status) == 'enable') {
								$day_rate = $rate->getHolidaySpecialRestday() / 100;
								$ot_rate = $rate->getHolidaySpecialRestdayOvertime() / 100;
								$ns_rate = $rate->getHolidaySpecialRestdayNightDifferentialOvertime() / 100;
							} else {
								$day_rate = 1.00;
								$ot_rate = 1.00;
								$ns_rate = 1.00;
							}
							//  $day_rate = $rate->getHolidaySpecialRestday() / 100;
							//  $ot_rate = $rate->getHolidaySpecialRestdayOvertime() / 100;
							// //old $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
							// $ns_rate = $rate->getHolidaySpecialRestdayNightDifferentialOvertime();
							//  //$hourly_amount = $hourly_amount * $day_rate * $ot_rate;
							$temp_amount = (float) Tools::numberFormat($ot_hours * $hourly_amount * $ns_rate, 2);
							$amount = $amount + $temp_amount;
						}
					}
				}
			}
		}
		return $amount;
	}

	public static function computeCutRestDaySpecialOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
					$h = $a->getHoliday();
					if (!empty($h) && $h->isSpecial()) {

						$t = $a->getTimesheet();
						if ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeIn() != '') {

							$ot_hours = $t->getRestDaySpecialOvertimeNightShiftHours() + $t->getRestDaySpecialOvertimeNightShiftExcessHours();

							if (strtolower($mandated_status) == 'enable') {
								$day_rate = $rate->getHolidaySpecialRestday() / 100;
								$ot_rate = $rate->getHolidaySpecialRestdayOvertime() / 100;
								$ns_rate = $rate->getHolidaySpecialRestdayNightDifferentialOvertime() / 100;
							} else {
								$day_rate = 1.00;
								$ot_rate = 1.00;
								$ns_rate = 1.00;
							}

							//  $day_rate = $rate->getHolidaySpecialRestday() / 100;
							//  $ot_rate = $rate->getHolidaySpecialRestdayOvertime() / 100;
							// //old $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
							// $ns_rate = $rate->getHolidaySpecialRestdayNightDifferentialOvertime();
							//  //$hourly_amount = $hourly_amount * $day_rate * $ot_rate;
							$temp_amount = (float) Tools::numberFormat($ot_hours * $hourly_amount * $ns_rate, 2);
							$amount = $amount + $temp_amount;
						}
					}
				}
			}
		}
		return $amount;
	}
	//newend
	public static function computeRestDayLegalOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
				$h = $a->getHoliday();
				if (!empty($h) && $h->isLegal()) {
					$t = $a->getTimesheet();
					$ot_hours = $t->getRestDayLegalOvertimeNightShiftHours() + $t->getRestDayLegalOvertimeNightShiftExcessHours();

					//  $day_rate = $rate->getHolidayLegalRestday() / 100;
					//  $ot_rate = $rate->getHolidayLegalRestdayOvertime() / 100;
					// //old $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
					// $ns_rate = $rate->getHolidayLegalRestdayNightDifferentialOvertime();
					if (strtolower($mandated_status) == 'enable') {
						$day_rate = $rate->getHolidayLegalRestday() / 100;
						$ot_rate = $rate->getHolidayLegalRestdayOvertime() / 100;
						$ns_rate = $rate->getHolidayLegalRestdayNightDifferentialOvertime() / 100;
					} else {
						$day_rate = 1.00;
						$ot_rate = 1.00;
						$ns_rate = 1.00;
					}

					//$hourly_amount = $hourly_amount * $day_rate * $ot_rate;

					$temp_amount = (float) Tools::numberFormat($ot_hours * $hourly_amount * $ns_rate, 2);
					$amount = $amount + $temp_amount;
				}
			}
		}
		return $amount;
	}
	//startnew
	public static function computePrevRestDayLegalOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
					$h = $a->getHoliday();
					if (!empty($h) && $h->isLegal()) {
						$t = $a->getTimesheet();
						$ot_hours = $t->getRestDayLegalOvertimeNightShiftHours() + $t->getRestDayLegalOvertimeNightShiftExcessHours();

						//  $day_rate = $rate->getHolidayLegalRestday() / 100;
						//  $ot_rate = $rate->getHolidayLegalRestdayOvertime() / 100;
						// //old $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
						// $ns_rate = $rate->getHolidayLegalRestdayNightDifferentialOvertime();
						if (strtolower($mandated_status) == 'enable') {
							$day_rate = $rate->getHolidayLegalRestday() / 100;
							$ot_rate = $rate->getHolidayLegalRestdayOvertime() / 100;
							$ns_rate = $rate->getHolidayLegalRestdayNightDifferentialOvertime() / 100;
						} else {
							$day_rate = 1.00;
							$ot_rate = 1.00;
							$ns_rate = 1.00;
						}

						//$hourly_amount = $hourly_amount * $day_rate * $ot_rate;

						$temp_amount = (float) Tools::numberFormat($ot_hours * $hourly_amount * $ns_rate, 2);
						$amount = $amount + $temp_amount;
					}
				}
			}
		}
		return $amount;
	}
	public static function computeCutRestDayLegalOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isHoliday() && $a->isRestday()) {
					$h = $a->getHoliday();
					if (!empty($h) && $h->isLegal()) {
						$t = $a->getTimesheet();
						$ot_hours = $t->getRestDayLegalOvertimeNightShiftHours() + $t->getRestDayLegalOvertimeNightShiftExcessHours();

						//  $day_rate = $rate->getHolidayLegalRestday() / 100;
						//  $ot_rate = $rate->getHolidayLegalRestdayOvertime() / 100;
						// //old $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
						// $ns_rate = $rate->getHolidayLegalRestdayNightDifferentialOvertime();
						if (strtolower($mandated_status) == 'enable') {
							$day_rate = $rate->getHolidayLegalRestday() / 100;
							$ot_rate = $rate->getHolidayLegalRestdayOvertime() / 100;
							$ns_rate = $rate->getHolidayLegalRestdayNightDifferentialOvertime() / 100;
						} else {
							$day_rate = 1.00;
							$ot_rate = 1.00;
							$ns_rate = 1.00;
						}

						//$hourly_amount = $hourly_amount * $day_rate * $ot_rate;

						$temp_amount = (float) Tools::numberFormat($ot_hours * $hourly_amount * $ns_rate, 2);
						$amount = $amount + $temp_amount;
					}
				}
			}
		}
		return $amount;
	}
	//newend
	public static function computeRestDayAmount($attendance, $rate, $daily_amount, $hourly_amount, $custom_ot = array(), $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			$t = $a->getTimesheet();
			if ($a->isPresent() && $a->isRestday() && !$a->isHoliday() && !$a->isOfficialBusiness() && ($t->getScheduledDateIn() != '' && $t->getScheduledDateOut() != '') && ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeOut() != '')) {
				if (!empty($custom_ot) && isset($custom_ot[$a->getDate()]) && $custom_ot[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_RESTDAY && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time'])) {
					$timestamp_start = $custom_ot[$a->getDate()]['start_time'];
					$timestamp_end   = $custom_ot[$a->getDate()]['end_time'];
					if ($timestamp_start > $timestamp_end) {
						$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
						$date = new DateTime($a->getDate());
						$date->modify('+1 day');
						$date_end = $date->format('Y-m-d');
						$date_end = $date_end . " " . $custom_ot[$a->getDate()]['end_time'];
					} else {
						$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
						$date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
					}
					$restday_hours = Tools::computeHoursDifferenceByDateTime($date_start, $date_end);

					$day_type = array();
					if ($a->isRestday()) {
						$day_type[] = "applied_to_restday";
					} else {
						$day_type[] = "applied_to_regular_day";
					}

					$schedule['schedule_in']  = $t->getScheduledDateIn() . " " . $t->getScheduledTimeIn();
					$schedule['schedule_out'] = $t->getScheduledDateOut() . " " . $t->getScheduledTimeOut();
					$schedule['actual_in']    = $t->getTimeIn();
					$schedule['actual_out']   = $t->getTimeOut();
					$e = new G_Employee();
					$e->setId($a->getEmployeeId());
					$deductible_breaktime     = $e->getTotalBreakTimeHrsDeductible($schedule, $day_type);
					$breaktime_details        = $e->getEmployeeBreakTimeBySchedule($schedule, $day_type);
					if ($restday_hours > $deductible_breaktime) {

						$break_details = explode(" to ", $breaktime_details[0]);

						$actual_timein  = $t->getTimeIn();
						$breaktime_in   = substr($break_details[0], 0, -3);
						$breaktime_out  = substr($break_details[1], 0, -3);

						$breaktime_in_24_format  = '';
						$breaktime_out_24_format = '';

						if (strtotime($actual_timein) <= strtotime($breaktime_in)) {
							$breaktime_in_24_format  = date("H:i:s", strtotime($break_details[0]));
							$breaktime_out_24_format = date("H:i:s", strtotime($break_details[1]));
							if (Tools::isTimeMorning($t->getTimeIn()) && Tools::isTimeBetweenHours($t->getTimeOut(), $breaktime_in_24_format, $breaktime_out_24_format)) {
								$restday_hours = 4.50;
							} else {
								$restday_hours = $restday_hours - $deductible_breaktime;
							}
						} else {
							$total_schedule_hours_plus_break = $t->getTotalScheduleHours() + $t->getTotalDeductibleBreaktimeHours();

							if ($restday_hours >= $t->getTotalScheduleHours()) {
								$restday_hours = $restday_hours - $deductible_breaktime;
							} else {
								$restday_hours = $restday_hours;
							}
						}
					}
				} else {
					$t = $a->getTimesheet();
					//$restday_hours = $t->getTotalHoursWorked();
					$restday_hours = $t->totalHrsWorkedBaseOnSchedule();
					/*if ($restday_hours > 8) {
	                    $restday_hours = 8;
	                }*/
					if ($a->isOfficialBusiness()) {
						$restday_hours = 8;
					}


					if (strtotime($t->getTimeIn()) > strtotime($t->getScheduledTimeIn())) {
						$in_diff = Tools::computeHoursDifferenceByDateTime($t->getScheduledTimeIn(), $t->getTimeIn());
						$restday_hours -= $in_diff;
					}
				}

				if (strtolower($mandated_status) == 'enable') {
					$restday_rate = $rate->getRestDay();
				} else {
					$restday_rate = 100;
				}

				$restday_hours = Tools::numberFormat($restday_hours, 2);
				$temp_amount = (float) Tools::numberFormat($restday_hours * $hourly_amount * ($restday_rate / 100), 2);
				$amount = $amount + $temp_amount;
			}
		}
		return $amount;
	}
	//new
	public static function computePrevRestDayAmount($attendance, $rate, $daily_amount, $hourly_amount, $custom_ot = array(), $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {

			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				$t = $a->getTimesheet();
				if ($a->isPresent() && $a->isRestday() && !$a->isHoliday() && !$a->isOfficialBusiness() && ($t->getScheduledDateIn() != '' && $t->getScheduledDateOut() != '') && ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeOut() != '')) {
					if (!empty($custom_ot) && isset($custom_ot[$a->getDate()]) && $custom_ot[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_RESTDAY && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time'])) {
						$timestamp_start = $custom_ot[$a->getDate()]['start_time'];
						$timestamp_end   = $custom_ot[$a->getDate()]['end_time'];
						if ($timestamp_start > $timestamp_end) {
							$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
							$date = new DateTime($a->getDate());
							$date->modify('+1 day');
							$date_end = $date->format('Y-m-d');
							$date_end = $date_end . " " . $custom_ot[$a->getDate()]['end_time'];
						} else {
							$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
							$date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
						}
						$restday_hours = Tools::computeHoursDifferenceByDateTime($date_start, $date_end);

						$day_type = array();
						if ($a->isRestday()) {
							$day_type[] = "applied_to_restday";
						} else {
							$day_type[] = "applied_to_regular_day";
						}

						$schedule['schedule_in']  = $t->getScheduledDateIn() . " " . $t->getScheduledTimeIn();
						$schedule['schedule_out'] = $t->getScheduledDateOut() . " " . $t->getScheduledTimeOut();
						$schedule['actual_in']    = $t->getTimeIn();
						$schedule['actual_out']   = $t->getTimeOut();
						$e = new G_Employee();
						$e->setId($a->getEmployeeId());
						$deductible_breaktime     = $e->getTotalBreakTimeHrsDeductible($schedule, $day_type);
						$breaktime_details        = $e->getEmployeeBreakTimeBySchedule($schedule, $day_type);
						if ($restday_hours > $deductible_breaktime) {

							$break_details = explode(" to ", $breaktime_details[0]);

							$actual_timein  = $t->getTimeIn();
							$breaktime_in   = substr($break_details[0], 0, -3);
							$breaktime_out  = substr($break_details[1], 0, -3);

							$breaktime_in_24_format  = '';
							$breaktime_out_24_format = '';

							if (strtotime($actual_timein) <= strtotime($breaktime_in)) {
								$breaktime_in_24_format  = date("H:i:s", strtotime($break_details[0]));
								$breaktime_out_24_format = date("H:i:s", strtotime($break_details[1]));
								if (Tools::isTimeMorning($t->getTimeIn()) && Tools::isTimeBetweenHours($t->getTimeOut(), $breaktime_in_24_format, $breaktime_out_24_format)) {
									$restday_hours = 4.50;
								} else {
									$restday_hours = $restday_hours - $deductible_breaktime;
								}
							} else {
								$total_schedule_hours_plus_break = $t->getTotalScheduleHours() + $t->getTotalDeductibleBreaktimeHours();

								if ($restday_hours >= $t->getTotalScheduleHours()) {
									$restday_hours = $restday_hours - $deductible_breaktime;
								} else {
									$restday_hours = $restday_hours;
								}
							}
						}
					} else {
						$t = $a->getTimesheet();
						//$restday_hours = $t->getTotalHoursWorked();
						$restday_hours = $t->totalHrsWorkedBaseOnSchedule();
						/*if ($restday_hours > 8) {
	                    $restday_hours = 8;
	                }*/
						if ($a->isOfficialBusiness()) {
							$restday_hours = 8;
						}


						if (strtotime($t->getTimeIn()) > strtotime($t->getScheduledTimeIn())) {
							$in_diff = Tools::computeHoursDifferenceByDateTime($t->getScheduledTimeIn(), $t->getTimeIn());
							$restday_hours -= $in_diff;
						}
					}


					if (strtolower($mandated_status) == 'enable') {
						$restday_rate = $rate->getRestDay();
					} else {
						$restday_rate = 100;
					}

					$restday_hours = Tools::numberFormat($restday_hours, 2);
					$temp_amount = (float) Tools::numberFormat($restday_hours * $hourly_amount * ($restday_rate / 100), 2);
					$amount = $amount + $temp_amount;
				}
			}

			//---
		}
		return $amount;
	}

	public static function computeCutRestDayAmount($attendance, $rate, $daily_amount, $hourly_amount, $custom_ot = array(), $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {

			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				$t = $a->getTimesheet();
				if ($a->isPresent() && $a->isRestday() && !$a->isHoliday() && !$a->isOfficialBusiness() && ($t->getScheduledDateIn() != '' && $t->getScheduledDateOut() != '') && ($t->getScheduledTimeIn() != '' && $t->getScheduledTimeOut() != '')) {
					if (!empty($custom_ot) && isset($custom_ot[$a->getDate()]) && $custom_ot[$a->getDate()]['day_type'] == G_Custom_Overtime::DAY_TYPE_RESTDAY && Tools::isValidTime($custom_ot[$a->getDate()]['start_time']) && Tools::isValidTime($custom_ot[$a->getDate()]['end_time'])) {
						$timestamp_start = $custom_ot[$a->getDate()]['start_time'];
						$timestamp_end   = $custom_ot[$a->getDate()]['end_time'];
						if ($timestamp_start > $timestamp_end) {
							$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
							$date = new DateTime($a->getDate());
							$date->modify('+1 day');
							$date_end = $date->format('Y-m-d');
							$date_end = $date_end . " " . $custom_ot[$a->getDate()]['end_time'];
						} else {
							$date_start = $a->getDate() . " " . $custom_ot[$a->getDate()]['start_time'];
							$date_end   = $a->getDate() . " " . $custom_ot[$a->getDate()]['end_time'];
						}
						$restday_hours = Tools::computeHoursDifferenceByDateTime($date_start, $date_end);

						$day_type = array();
						if ($a->isRestday()) {
							$day_type[] = "applied_to_restday";
						} else {
							$day_type[] = "applied_to_regular_day";
						}

						$schedule['schedule_in']  = $t->getScheduledDateIn() . " " . $t->getScheduledTimeIn();
						$schedule['schedule_out'] = $t->getScheduledDateOut() . " " . $t->getScheduledTimeOut();
						$schedule['actual_in']    = $t->getTimeIn();
						$schedule['actual_out']   = $t->getTimeOut();
						$e = new G_Employee();
						$e->setId($a->getEmployeeId());
						$deductible_breaktime     = $e->getTotalBreakTimeHrsDeductible($schedule, $day_type);
						$breaktime_details        = $e->getEmployeeBreakTimeBySchedule($schedule, $day_type);
						if ($restday_hours > $deductible_breaktime) {

							$break_details = explode(" to ", $breaktime_details[0]);

							$actual_timein  = $t->getTimeIn();
							$breaktime_in   = substr($break_details[0], 0, -3);
							$breaktime_out  = substr($break_details[1], 0, -3);

							$breaktime_in_24_format  = '';
							$breaktime_out_24_format = '';

							if (strtotime($actual_timein) <= strtotime($breaktime_in)) {
								$breaktime_in_24_format  = date("H:i:s", strtotime($break_details[0]));
								$breaktime_out_24_format = date("H:i:s", strtotime($break_details[1]));
								if (Tools::isTimeMorning($t->getTimeIn()) && Tools::isTimeBetweenHours($t->getTimeOut(), $breaktime_in_24_format, $breaktime_out_24_format)) {
									$restday_hours = 4.50;
								} else {
									$restday_hours = $restday_hours - $deductible_breaktime;
								}
							} else {
								$total_schedule_hours_plus_break = $t->getTotalScheduleHours() + $t->getTotalDeductibleBreaktimeHours();

								if ($restday_hours >= $t->getTotalScheduleHours()) {
									$restday_hours = $restday_hours - $deductible_breaktime;
								} else {
									$restday_hours = $restday_hours;
								}
							}
						}
					} else {
						$t = $a->getTimesheet();
						//$restday_hours = $t->getTotalHoursWorked();
						$restday_hours = $t->totalHrsWorkedBaseOnSchedule();
						/*if ($restday_hours > 8) {
	                    $restday_hours = 8;
	                }*/
						if ($a->isOfficialBusiness()) {
							$restday_hours = 8;
						}


						if (strtotime($t->getTimeIn()) > strtotime($t->getScheduledTimeIn())) {
							$in_diff = Tools::computeHoursDifferenceByDateTime($t->getScheduledTimeIn(), $t->getTimeIn());
							$restday_hours -= $in_diff;
						}
					}

					if (strtolower($mandated_status) == 'enable') {
						$restday_rate = $rate->getRestDay();
					} else {
						$restday_rate = 100;
					}

					$restday_hours = Tools::numberFormat($restday_hours, 2);
					$temp_amount = (float) Tools::numberFormat($restday_hours * $hourly_amount * ($restday_rate / 100), 2);
					$amount = $amount + $temp_amount;
				}
			}

			//---
		}
		return $amount;
	}
	//new
	//
	/*public static function computeRegularAmount($attendance, $daily_amount, $hourly_amount) {

        $amount = 0;                     
        foreach ($attendance as $a) {
        	$employee_id = $a->getEmployeeId();        	
            //if ($a->isPresent() && $a->isRestday() && !$a->isHoliday()) {
        	$employee_id = $a->getEmployeeId();        	
           	if ($a->isPaid() && $a->isPresent() && !$a->isRestday()) { // march 4, 2015 - let           		        		
                $t = $a->getTimesheet();
                $regular_hours = $t->getTotalHoursWorked();
                if ($regular_hours > 8) {
                    $regular_hours = 8;
                }
                if ($a->isOfficialBusiness()) {
                    $regular_hours = 8;
                }

                $regular_hours = Tools::numberFormat($regular_hours, 2);
                $temp_amount = (float) Tools::numberFormat($regular_hours * $hourly_amount, 2);
                $amount = $amount + $temp_amount;
            }
        }
        return $amount;
    }*/

	public static function computeRegularAmount($attendance, $daily_amount, $hourly_amount)
	{

		$amount    = 0;
		$total_hrs = 0;
		foreach ($attendance as $a) {
			$employee_id = $a->getEmployeeId();
			//if ($a->isPresent() && $a->isRestday() && !$a->isHoliday()) {
			$employee_id = $a->getEmployeeId();
			if ($a->isPaid() && $a->isPresent() && !$a->isRestday()) { // march 4, 2015 - let           		        		
				$t = $a->getTimesheet();
				$regular_hours = $t->getTotalHoursWorked() + $t->getTotalOvertimeHours();
				$date = $a->getDate();
				//echo "Date : {$date} / Employee ID : {$employee_id} / Total HRS Worked : {$regular_hours}<br>";

				/*if ($regular_hours > 8) {
                    $regular_hours = 8;
                }*/
				if ($a->isOfficialBusiness()) {
					$regular_hours = 8;
				}

				$regular_hours = Tools::numberFormat($regular_hours, 2);
				$total_hrs    += $regular_hours;
			}
		}

		//echo "Total HRS : {$total_hrs} / Employee ID {$employee_id}<br>";
		$amount = (float) Tools::numberFormat($total_hrs * $hourly_amount, 2);
		return $amount;
	}

	public static function computeRestDayOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isRestday() && !$a->isHoliday() && !$a->isOfficialBusiness()) {
				$t = $a->getTimesheet();

				if (strtolower($mandated_status) == 'enable') {
					// $rd_rate = $rate->getRestDay() / 100;
					$rd_ot_rate = $rate->getRestDayOvertime() / 100;
				} else {
					// $rd_rate = 1.00;
					$rd_ot_rate = 1.00;
				}

				$rd_ot_hours = $t->getRestDayOvertimeHours() + $t->getRestDayLegalOvertimeExcessHours();

				// $temp_amount = (float) Tools::numberFormat(($rd_ot_hours * ($hourly_amount * $rd_rate)) * $rd_ot_rate, 2);
				$temp_amount = (float) Tools::numberFormat(($rd_ot_hours * ($hourly_amount)) * $rd_ot_rate, 2);
				$amount = $amount + $temp_amount;
			}
		}
		return $amount;
	}
	//new
	public static function computePrevRestDayOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {

			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isRestday() && !$a->isHoliday() && !$a->isOfficialBusiness()) {
					$t = $a->getTimesheet();

					if (strtolower($mandated_status) == 'enable') {
						// $rd_rate = $rate->getRestDay() / 100;
						$rd_ot_rate = $rate->getRestDayOvertime() / 100;
					} else {
						// $rd_rate = 1.00;
						$rd_ot_rate = 1.00;
					}
	
					$rd_ot_hours = $t->getRestDayOvertimeHours() + $t->getRestDayLegalOvertimeExcessHours();
	
					// $temp_amount = (float) Tools::numberFormat(($rd_ot_hours * ($hourly_amount * $rd_rate)) * $rd_ot_rate, 2);
					$temp_amount = (float) Tools::numberFormat(($rd_ot_hours * ($hourly_amount)) * $rd_ot_rate, 2);
					$amount = $amount + $temp_amount;
				}
			}
		}
		return $amount;
	}
	public static function computeCutRestDayOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		foreach ($attendance as $a) {

			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isRestday() && !$a->isHoliday() && !$a->isOfficialBusiness()) {
					$t = $a->getTimesheet();

					if (strtolower($mandated_status) == 'enable') {
						// $rd_rate = $rate->getRestDay() / 100;
						$rd_ot_rate = $rate->getRestDayOvertime() / 100;
					} else {
						// $rd_rate = 1.00;
						$rd_ot_rate = 1.00;
					}
	
					$rd_ot_hours = $t->getRestDayOvertimeHours() + $t->getRestDayLegalOvertimeExcessHours();
	
					// $temp_amount = (float) Tools::numberFormat(($rd_ot_hours * ($hourly_amount * $rd_rate)) * $rd_ot_rate, 2);
					$temp_amount = (float) Tools::numberFormat(($rd_ot_hours * ($hourly_amount)) * $rd_ot_rate, 2);
					$amount = $amount + $temp_amount;
				}
			}
		}
		return $amount;
	}

	//new
	public static function computeRegularOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		$hrly_amount = $hourly_amount;
		// foreach ($attendance as $a) {
		//     if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {                
		//         $t = $a->getTimesheet();

		//         if (strtolower($mandated_status) == 'enable') {
		//         	$ot_rate = $rate->getRegularOvertime() / 100;
		//         }
		//         else {
		//         	$ot_rate = 1.00;
		//         }
		//          $regular_ot_hours = G_Attendance_Helper::getTotalOvertimeHours($attendance);

		//          $ot_hours = $t->getRegularOvertimeHours() + $t->getRegularOvertimeExcessHours();
		//         //$ot_hours = $regular_ot_hours + $t->getRegularOvertimeExcessHours();
		//        echo $t->getRegularOvertimeHours();
		//         $total_ot_hours += $ot_hours;
		//         //echo $ot_hours . "<br />";
		//         $hourly_amount = $hourly_amount * $ot_rate;                
		//         $temp_amount = (float) Tools::numberFormat($ot_hours * $hourly_amount, 2);
		//         $amount = $amount + $temp_amount;
		//     }
		// }

		if (strtolower($mandated_status) == 'enable') {
			$ot_rate = $rate->getRegularOvertime() / 100;
		} else {
			$ot_rate = 1.00;
		}
		$regular_ot_hours = G_Attendance_Helper::getTotalOvertimeHours($attendance);

		$regular_ot_hours = G_Attendance_Helper::getTotalOvertimeHours($attendance);
		$new_amount = (float) Tools::numberFormat($regular_ot_hours * $hrly_amount * $ot_rate, 2);
		//$new_amount = (float) Tools::numberFormat($total_ot_hours * $hrly_amount * $ot_rate,2);

		return $new_amount;
	}
	//new
	public static function computeCutRegularOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		$hrly_amount = $hourly_amount;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
					$t = $a->getTimesheet();

					if (strtolower($mandated_status) == 'enable') {
						$ot_rate = $rate->getRegularOvertime() / 100;
					} else {
						$ot_rate = 1.00;
					}

					$ot_hours = $t->getRegularOvertimeHours() + $t->getRegularOvertimeExcessHours();
					$total_ot_hours += $ot_hours;
					//echo $ot_hours . "<br />";
					$hourly_amount = $hourly_amount * $ot_rate;
					$temp_amount = (float) Tools::numberFormat($ot_hours * $hourly_amount, 2);
					$amount = $amount + $temp_amount;
				}
			}
		}

		$new_amount = (float) Tools::numberFormat($total_ot_hours * $hrly_amount * $ot_rate, 2);

		return $new_amount;
	}
	public static function computePrevRegularOvertimeAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$amount = 0;
		$hrly_amount = $hourly_amount;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
					$t = $a->getTimesheet();

					if (strtolower($mandated_status) == 'enable') {
						$ot_rate = $rate->getRegularOvertime() / 100;
					} else {
						$ot_rate = 1.00;
					}

					$ot_hours = $t->getRegularOvertimeHours() + $t->getRegularOvertimeExcessHours();
					$total_ot_hours += $ot_hours;
					//echo $ot_hours . "<br />";
					$hourly_amount = $hourly_amount * $ot_rate;
					$temp_amount = (float) Tools::numberFormat($ot_hours * $hourly_amount, 2);
					$amount = $amount + $temp_amount;
				}
			}
		}

		$new_amount = (float) Tools::numberFormat($total_ot_hours * $hrly_amount * $ot_rate, 2);

		return $new_amount;
	}
	//new
	/**
	 * Compute Special Rate Overtime 
	 *
	 * @param attendance array
	 * @param rate float
	 * @return float
	 */
	public static function computeSpecialRateRegularOvertimeAmount($attendance, $rate)
	{
		$amount = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
				$t = $a->getTimesheet();
				$ot_hours = $t->getRegularOvertimeHours() + $t->getRegularOvertimeExcessHours();
				$total_ot_hours += $ot_hours;
			}
		}

		$ot_amount = (float) Tools::numberFormat($total_ot_hours * $rate, 2);
		//echo "New Total Amount : {$new_amount}";


		return $ot_amount;
	}

	public static function computeRestDayOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$t = $a->getTimesheet();
			if ($a->isPresent() && $a->isRestday() && !$a->isHoliday()) {
				if ($t) {
					if (strtolower($mandated_status) == 'enable') {
						// $rd_rate = $rate->getRestDay() / 100;
						// $ot_rate = $rate->getRestDayOvertime() / 100;
						// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
						$ns_rate = ($rate->getRestDayNightDifferentialOvertime()) / 100;
					} else {
						$rd_rate = 1.00;
						$ot_rate = 1.00;
						$ns_rate = 1.00;
					}

					// $hourly_amount = ($hourly_amount * $rd_rate * $ot_rate);
					$ot_hours = (float) $t->getRestDayOvertimeNightShiftHours() + $t->getRestDayOvertimeNightShiftExcessHours();

					$temp_total = (float) Tools::numberFormat($ot_hours * $hourly_amount * $ns_rate, 2);
					$total = $total + $temp_total;
				}
			}
		}
		return $total;
	}
	//newstart
	public static function computePrevRestDayOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				$t = $a->getTimesheet();
				if ($a->isPresent() && $a->isRestday() && !$a->isHoliday()) {
					if ($t) {
						if (strtolower($mandated_status) == 'enable') {
							// $rd_rate = $rate->getRestDay() / 100;
							// $ot_rate = $rate->getRestDayOvertime() / 100;
							// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
							$ns_rate = ($rate->getRestDayNightDifferentialOvertime()) / 100;
						} else {
							// $rd_rate = 1.00;
							// $ot_rate = 1.00;
							$ns_rate = 1.00;
						}

						// $hourly_amount = ($hourly_amount * $rd_rate * $ot_rate);
						$ot_hours = (float) $t->getRestDayOvertimeNightShiftHours() + $t->getRestDayOvertimeNightShiftExcessHours();

						$temp_total = (float) Tools::numberFormat($ot_hours * $hourly_amount * $ns_rate, 2);
						$total = $total + $temp_total;
					}
				}
			}
		}
		return $total;
	}
	public static function computeCutRestDayOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				$t = $a->getTimesheet();
				if ($a->isPresent() && $a->isRestday() && !$a->isHoliday()) {
					if ($t) {
						if (strtolower($mandated_status) == 'enable') {
							// $rd_rate = $rate->getRestDay() / 100;
							// $ot_rate = $rate->getRestDayOvertime() / 100;
							// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
							$ns_rate = ($rate->getRestDayNightDifferentialOvertime()) / 100;
						} else {
							$rd_rate = 1.00;
							$ot_rate = 1.00;
							$ns_rate = 1.00;
						}

						// $hourly_amount = ($hourly_amount * $rd_rate * $ot_rate);
						$ot_hours = (float) $t->getRestDayOvertimeNightShiftHours() + $t->getRestDayOvertimeNightShiftExcessHours();

						$temp_total = (float) Tools::numberFormat($ot_hours * $hourly_amount * $ns_rate, 2);
						$total = $total + $temp_total;
					}
				}
			}
		}
		return $total;
	}
	//newend
	public static function computeRegularOvertimeNightShiftAmountDepre($attendance, $rate, $daily_amount, $hourly_amount)
	{
		$total = 0;
		$rate_per_hour = $hourly_amount;
		foreach ($attendance as $a) {
			if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
				$t = $a->getTimesheet();
				if ($t) {
					// $ot_rate = $rate->getRegularOvertime() / 100;
					// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
					$ns_rate = ($rate->getRestDayNightDifferentialOvertime()) / 100;
					//Utilities::displayArray($a);                                        
					// $hourly_amount = ($hourly_amount * $ot_rate);
					$ot_hours = (float) $t->getRegularOvertimeNightShiftHours() + $t->getRegularOvertimeNightShiftExcessHours();
					// $total_ot_hrs += $ot_hours;
					//echo "OT Hours : {$ot_hours} / Hourly Amount : {$hourly_amount} / Rate : {$ns_rate} <br>";         
					$temp_total = (float) Tools::numberFormat($ot_hours * $hourly_amount * $ns_rate, 2);
					$total = $total + $temp_total;
				}
			}
		}

		return $total;
	}

	public static function computeRegularOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		$rate_per_hour   = $hourly_amount;
		$total_ot_hrs = 0;
		// if (strtolower($mandated_status) == 'enable') {
		// 	// $nightshift_rate = ($rate->getNightShiftDiff() - 100) / 100;
		// 	$nightshift_rate = ($rate->getRegularNightDifferentialOvertime()) / 100;
		// } else {
		// 	$nightshift_rate = 1.00;
		// }

		foreach ($attendance as $a) {
			if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
				$t = $a->getTimesheet();
				if ($t) {
					$ot_hours = (float) $t->getRegularOvertimeNightShiftHours() + $t->getRegularOvertimeNightShiftExcessHours();
					$total_ot_hrs += $ot_hours;
				}
			}
		}

		if (strtolower($mandated_status) == 'enable') {
			$ot_rate = ($rate->getRegularNightDifferentialOvertime() / 100);
		} else {
			$ot_rate = 1.00;
		}

		// $total = ($rate_per_hour * $nightshift_rate) * $ot_rate * $total_ot_hrs;
		$total = ( $total_ot_hrs * $rate_per_hour ) * $ot_rate;

		return $total;
	}
	//new
	public static function computeCutRegularOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		$rate_per_hour   = $hourly_amount;
		$total_ot_hrs = 0;

		// if (strtolower($mandated_status) == 'enable') {
		// 	$nightshift_rate = ($rate->getNightShiftDiff() - 100) / 100;
		// } else {
		// 	$nightshift_rate = 1.00;
		// }

		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
					$t = $a->getTimesheet();
					if ($t) {
						$ot_hours = (float) $t->getRegularOvertimeNightShiftHours() + $t->getRegularOvertimeNightShiftExcessHours();
						$total_ot_hrs += $ot_hours;
					}
				}
			}
		}

		if (strtolower($mandated_status) == 'enable') {
			$ot_rate = ($rate->getRegularNightDifferentialOvertime() / 100);
		} else {
			$ot_rate = 1.00;
		}

		// $total = ($rate_per_hour * $nightshift_rate) * $ot_rate * $total_ot_hrs;
		$total = ( $total_ot_hrs * $rate_per_hour ) * $ot_rate;

		return $total;
	}
	public static function computePrevRegularOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		$rate_per_hour   = $hourly_amount;
		$total_ot_hrs = 0;
		
		// if (strtolower($mandated_status) == 'enable') {
		// 	$nightshift_rate = ($rate->getNightShiftDiff() - 100) / 100;
		// } else {
		// 	$nightshift_rate = 1.00;
		// }

		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
					$t = $a->getTimesheet();
					if ($t) {
						$ot_hours = (float) $t->getRegularOvertimeNightShiftHours() + $t->getRegularOvertimeNightShiftExcessHours();
						$total_ot_hrs += $ot_hours;
					}
				}
			}
		}

		if (strtolower($mandated_status) == 'enable') {
			$ot_rate = ($rate->getRegularNightDifferentialOvertime() / 100);
		} else {
			$ot_rate = 1.00;
		}

		// $total = ($rate_per_hour * $nightshift_rate) * ($rate->getRegularOvertimeNightShiftDifferential() / 100) * $total_ot_hrs;
		$total = ( $total_ot_hrs * $rate_per_hour ) * $ot_rate;

		return $total;
	}
	//new 

	public static function computeRegularNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
				$t = $a->getTimesheet();
				if ($t) {
					$ns_hours = $t->getNightShiftHours();

					if (strtolower($mandated_status) == 'enable') {
						// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
						$ns_rate = ($rate->getRegularNightDifferential()) / 100;
					} else {
						$ns_rate = 1.00;
					}

					$temp_total = (float) Tools::numberFormat($hourly_amount * $ns_rate * $ns_hours, 2);
					$total = $total + $temp_total;
				}
			}
		}
		return $total;
	}
	//new
	public static function computeCutRegularNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
					$t = $a->getTimesheet();
					if ($t) {
						$ns_hours = $t->getNightShiftHours();

						if (strtolower($mandated_status) == 'enable') {
							// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
							$ns_rate = ($rate->getRegularNightDifferential()) / 100;
						} else {
							$ns_rate = 1.00;
						}

						$temp_total = (float) Tools::numberFormat($hourly_amount * $ns_rate * $ns_hours, 2);
						$total = $total + $temp_total;
					}
				}
			}
		}
		return $total;
	}
	public static function computePrevRegularNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && !$a->isRestday() && !$a->isHoliday()) {
					$t = $a->getTimesheet();
					if ($t) {
						$ns_hours = $t->getNightShiftHours();

						if (strtolower($mandated_status) == 'enable') {
							// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
							$ns_rate = ($rate->getRegularNightDifferential()) / 100;
						} else {
							$ns_rate = 1.00;
						}

						$temp_total = (float) Tools::numberFormat($hourly_amount * $ns_rate * $ns_hours, 2);
						$total = $total + $temp_total;
					}
				}
			}
		}
		return $total;
	}
	//new
	public static function computeRestDayNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isRestday() && !$a->isHoliday() && !$a->isOfficialBusiness()) {
				$t = $a->getTimesheet();
				if ($t) {
					$ns_hours = $t->getNightShiftHours();

					if (strtolower($mandated_status) == 'enable') {
						// $rd_rate = $rate->getRestDay() / 100;
						// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
						$rdns_rate = ($rate->getRestDayNightDifferential()) / 100;
					} else {
						// $rd_rate = 1.00;
						// $ns_rate = 1.00;
						$rdns_rate = 1.00;
					}

					// $temp_total = (float) Tools::numberFormat($hourly_amount * $rd_rate * $ns_rate * $ns_hours, 2);
					$temp_total = (float) Tools::numberFormat($hourly_amount * $rdns_rate * $ns_hours, 2);
					$total = $total + $temp_total;
				}
			}
		}
		return $total;
	}
	//newstart
	public static function computePrevRestDayNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isRestday() && !$a->isHoliday() && !$a->isOfficialBusiness()) {
					$t = $a->getTimesheet();
					if ($t) {
						$ns_hours = $t->getNightShiftHours();

						if (strtolower($mandated_status) == 'enable') {
							// $rd_rate = $rate->getRestDay() / 100;
							// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
							$rdns_rate = ($rate->getRestDayNightDifferential()) / 100;
						} else {
							// $rd_rate = 1.00;
							// $ns_rate = 1.00;
							$rdns_rate = 1.00;
						}

						// $temp_total = (float) Tools::numberFormat($hourly_amount * $rd_rate * $ns_rate * $ns_hours, 2);
						$temp_total = (float) Tools::numberFormat($hourly_amount * $rdns_rate * $ns_hours, 2);
						$total = $total + $temp_total;
					}
				}
			}
		}
		return $total;
	}
	public static function computeCutRestDayNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isRestday() && !$a->isHoliday() && !$a->isOfficialBusiness()) {
					$t = $a->getTimesheet();
					if ($t) {
						$ns_hours = $t->getNightShiftHours();

						if (strtolower($mandated_status) == 'enable') {
							// $rd_rate = $rate->getRestDay() / 100;
							// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
							$rdns_rate = ($rate->getRestDayNightDifferential()) / 100;
						} else {
							// $rd_rate = 1.00;
							// $ns_rate = 1.00;
							$rdns_rate = 1.00;
						}

						// $temp_total = (float) Tools::numberFormat($hourly_amount * $rd_rate * $ns_rate * $ns_hours, 2);
						$temp_total = (float) Tools::numberFormat($hourly_amount * $rdns_rate * $ns_hours, 2);
						$total = $total + $temp_total;
					}
				}
			}
		}
		return $total;
	}
	//newend
	public static function computeRestDaySpecialNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isRestday() && $a->isHoliday()) {
				$h = $a->getHoliday();
				if ($h && $h->isSpecial()) {
					$t = $a->getTimesheet();
					if ($t) {
						$ns_hours = $t->getNightShiftHours();

						if (strtolower($mandated_status) == 'enable') {
							// $rd_rate = $rate->getHolidaySpecialRestday() / 100;
							// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
							$rdns_rate = ($rate->getHolidaySpecialRestdayNightDifferential()) / 100;
						} else {
							// $rd_rate = 1.00;
							// $ns_rate = 1.00;
							$rdns_rate = 1.00;
						}

						// $temp_total = (float) Tools::numberFormat($hourly_amount * $rd_rate * $ns_rate * $ns_hours, 2);
						$temp_total = (float) Tools::numberFormat($hourly_amount * $rdns_rate * $ns_hours, 2);
						$total = $total + $temp_total;
					}
				}
			}
		}
		return $total;
	}
	//newstart
	public static function computePrevRestDaySpecialNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {

				if ($a->isPresent() && $a->isRestday() && $a->isHoliday()) {
					$h = $a->getHoliday();
					if ($h && $h->isSpecial()) {
						$t = $a->getTimesheet();
						if ($t) {
							$ns_hours = $t->getNightShiftHours();

							if (strtolower($mandated_status) == 'enable') {
								// $rd_rate = $rate->getHolidaySpecialRestday() / 100;
								// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
								$rdns_rate = ($rate->getHolidaySpecialRestdayNightDifferential()) / 100;
							} else {
								// $rd_rate = 1.00;
								// $ns_rate = 1.00;
								$rdns_rate = 1.00;
							}
	
							// $temp_total = (float) Tools::numberFormat($hourly_amount * $rd_rate * $ns_rate * $ns_hours, 2);
							$temp_total = (float) Tools::numberFormat($hourly_amount * $rdns_rate * $ns_hours, 2);
							$total = $total + $temp_total;
						}
					}
				}
			}
		}
		return $total;
	}
	public static function computeCutRestDaySpecialNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {

				if ($a->isPresent() && $a->isRestday() && $a->isHoliday()) {
					$h = $a->getHoliday();
					if ($h && $h->isSpecial()) {
						$t = $a->getTimesheet();
						if ($t) {
							$ns_hours = $t->getNightShiftHours();


							if (strtolower($mandated_status) == 'enable') {
								// $rd_rate = $rate->getHolidaySpecialRestday() / 100;
								// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
								$rdns_rate = ($rate->getHolidaySpecialRestdayNightDifferential()) / 100;
							} else {
								// $rd_rate = 1.00;
								// $ns_rate = 1.00;
								$rdns_rate = 1.00;
							}
	
							// $temp_total = (float) Tools::numberFormat($hourly_amount * $rd_rate * $ns_rate * $ns_hours, 2);
							$temp_total = (float) Tools::numberFormat($hourly_amount * $rdns_rate * $ns_hours, 2);
							$total = $total + $temp_total;
						}
					}
				}
			}
		}
		return $total;
	}
	//newend

	public static function computeRestDayLegalNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isRestday() && $a->isHoliday()) {
				$h = $a->getHoliday();
				if ($h && $h->isLegal()) {
					$t = $a->getTimesheet();
					if ($t) {
						$ns_hours = $t->getNightShiftHours();

						if (strtolower($mandated_status) == 'enable') {
							// $rd_rate = $rate->getHolidayLegalRestday() / 100;
							// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
							$rdns_rate = ($rate->getHolidayLegalRestdayNightDifferential()) / 100;
						} else {
							// $rd_rate = 1.00;
							// $ns_rate = 1.00;
							$rdns_rate = 1.00;
						}

						// $temp_total = (float) Tools::numberFormat($hourly_amount * $rd_rate * $ns_rate * $ns_hours, 2);
						$temp_total = (float) Tools::numberFormat($hourly_amount * $rdns_rate * $ns_hours, 2);
						$total = $total + $temp_total;
					}
				}
			}
		}
		return $total;
	}
	//newstart
	public static function computePrevRestDayLegalNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isRestday() && $a->isHoliday()) {
					$h = $a->getHoliday();
					if ($h && $h->isLegal()) {
						$t = $a->getTimesheet();
						if ($t) {
							$ns_hours = $t->getNightShiftHours();

							if (strtolower($mandated_status) == 'enable') {
								// $rd_rate = $rate->getHolidayLegalRestday() / 100;
								// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
								$rdns_rate = ($rate->getHolidayLegalRestdayNightDifferential()) / 100;
							} else {
								// $rd_rate = 1.00;
								// $ns_rate = 1.00;
								$rdns_rate = 1.00;
							}
	
							// $temp_total = (float) Tools::numberFormat($hourly_amount * $rd_rate * $ns_rate * $ns_hours, 2);
							$temp_total = (float) Tools::numberFormat($hourly_amount * $rdns_rate * $ns_hours, 2);
							$total = $total + $temp_total;
						}
					}
				}
			}
		}
		return $total;
	}
	public static function computeCutRestDayLegalNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isRestday() && $a->isHoliday()) {
					$h = $a->getHoliday();
					if ($h && $h->isLegal()) {
						$t = $a->getTimesheet();
						if ($t) {
							$ns_hours = $t->getNightShiftHours();

							if (strtolower($mandated_status) == 'enable') {
								// $rd_rate = $rate->getHolidayLegalRestday() / 100;
								// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
								$rdns_rate = ($rate->getHolidayLegalRestdayNightDifferential()) / 100;
							} else {
								// $rd_rate = 1.00;
								// $ns_rate = 1.00;
								$rdns_rate = 1.00;
							}
	
							// $temp_total = (float) Tools::numberFormat($hourly_amount * $rd_rate * $ns_rate * $ns_hours, 2);
							$temp_total = (float) Tools::numberFormat($hourly_amount * $rdns_rate * $ns_hours, 2);
							$total = $total + $temp_total;
						}
					}
				}
			}
		}
		return $total;
	}
	//newend
	public static function computeSpecialNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && !$a->isRestday() && $a->isHoliday()) {
				$h = $a->getHoliday();
				if ($h && $h->isSpecial()) {
					$t = $a->getTimesheet();
					if ($t) {
						$ns_hours = $t->getNightShiftHours();

						if (strtolower($mandated_status) == 'enable') {
							// $day_rate = $rate->getHolidaySpecial() / 100;
							// $ns_rate = ($rate->getNightShiftDiff()) / 100;
							$dayns_rate = ($rate->getHolidaySpecialNightDifferential()) / 100;
						} else {
							// $day_rate = 1.00;
							// $ns_rate = 1.00;
							$dayns_rate = 1.00;
						}

						// $temp_total = (float) Tools::numberFormat($hourly_amount * $day_rate * $ns_rate * $ns_hours, 2);
						$temp_total = (float) Tools::numberFormat($hourly_amount * $dayns_rate * $ns_hours, 2);
						$total = $total + $temp_total;
					}
				}
			}
		}
		return $total;
	}
	//newstart
	public static function computePrevSpecialNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && !$a->isRestday() && $a->isHoliday()) {
					$h = $a->getHoliday();
					if ($h && $h->isSpecial()) {
						$t = $a->getTimesheet();
						if ($t) {
							$ns_hours = $t->getNightShiftHours();

							if (strtolower($mandated_status) == 'enable') {
								// $day_rate = $rate->getHolidaySpecial() / 100;
								// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
								$dayns_rate = ($rate->getHolidaySpecialNightDifferential()) / 100;
							} else {
								// $day_rate = 1.00;
								// $ns_rate = 1.00;
								$dayns_rate = 1.00;
							}
	
							// $temp_total = (float) Tools::numberFormat($hourly_amount * $day_rate * $ns_rate * $ns_hours, 2);
							$temp_total = (float) Tools::numberFormat($hourly_amount * $dayns_rate * $ns_hours, 2);
							$total = $total + $temp_total;
						}
					}
				}
			}
		}
		return $total;
	}
	public static function computeCutSpecialNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && !$a->isRestday() && $a->isHoliday()) {
					$h = $a->getHoliday();
					if ($h && $h->isSpecial()) {
						$t = $a->getTimesheet();
						if ($t) {
							$ns_hours = $t->getNightShiftHours();

							if (strtolower($mandated_status) == 'enable') {
								// $day_rate = $rate->getHolidaySpecial() / 100;
								// $ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
								$dayns_rate = ($rate->getHolidaySpecialNightDifferential()) / 100;
							} else {
								// $day_rate = 1.00;
								// $ns_rate = 1.00;
								$dayns_rate = 1.00;
							}
	
							// $temp_total = (float) Tools::numberFormat($hourly_amount * $day_rate * $ns_rate * $ns_hours, 2);
							$temp_total = (float) Tools::numberFormat($hourly_amount * $dayns_rate * $ns_hours, 2);
							$total = $total + $temp_total;
						}
					}
				}
			}
		}
		return $total;
	}
	//newend
	public static function computeLegalNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && !$a->isRestday() && $a->isHoliday()) {
				$h = $a->getHoliday();
				if ($h && $h->isLegal()) {
					$t = $a->getTimesheet();
					if ($t) {
						$ns_hours = $t->getNightShiftHours();

						//$day_rate = $rate->getHolidayLegal() / 100;

						if (strtolower($mandated_status) == 'enable') {
							// $ns_rate = $rate->getHolidayLegalNightShift();
							$ns_rate = ($rate->getHolidayLegalNightDifferential() / 100);
						} else {
							$ns_rate = 1.00;
						}

						$temp_total = (float) Tools::numberFormat($hourly_amount * $ns_rate * $ns_hours, 2);
						$total = $total + $temp_total;
					}
				}
			}
		}
		return $total;
	}
	//new
	public static function computeCutLegalNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && !$a->isRestday() && $a->isHoliday()) {
					$h = $a->getHoliday();
					if ($h && $h->isLegal()) {
						$t = $a->getTimesheet();
						if ($t) {
							$ns_hours = $t->getNightShiftHours();

							//old$day_rate = $rate->getHolidayLegal() / 100;
							if (strtolower($mandated_status) == 'enable') {
								// $ns_rate = $rate->getHolidayLegalNightShift();
								$ns_rate = ($rate->getHolidayLegalNightDifferential() / 100);
							} else {
								$ns_rate = 1.00;
							}
	
							$temp_total = (float) Tools::numberFormat($hourly_amount * $ns_rate * $ns_hours, 2);
							$total = $total + $temp_total;
						}
					}
				}
			}
		}
		return $total;
	}
	public static function computePrevLegalNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && !$a->isRestday() && $a->isHoliday()) {
					$h = $a->getHoliday();
					if ($h && $h->isLegal()) {
						$t = $a->getTimesheet();
						if ($t) {
							$ns_hours = $t->getNightShiftHours();

							//$day_rate = $rate->getHolidayLegal() / 100;
							if (strtolower($mandated_status) == 'enable') {
								// $ns_rate = $rate->getHolidayLegalNightShift();
								$ns_rate = ($rate->getHolidayLegalNightDifferential() / 100);
							} else {
								$ns_rate = 1.00;
							}
	
							$temp_total = (float) Tools::numberFormat($hourly_amount * $ns_rate * $ns_hours, 2);
							$total = $total + $temp_total;
						}
					}
				}
			}
		}
		return $total;
	}
	//new
	public static function computeLegalOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
				$h = $a->getHoliday();
				if ($h && $h->isLegal()) {
					$t = $a->getTimesheet();
					if ($t) {
						// $day_rate = $rate->getHolidayLegal() / 100;
						// $ot_rate = $rate->getHolidayLegalOvertime() / 100;
						// //$ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
						// 	$ns_rate = $rate->getHolidayLegalNightShiftOvertime();
						// //$hourly_amount = ($hourly_amount * $day_rate * $ot_rate);

						if (strtolower($mandated_status) == 'enable') {
							// $day_rate = $rate->getHolidayLegal() / 100;
							// $ot_rate = $rate->getHolidayLegalOvertime() / 100;
							// $ns_rate = $rate->getHolidayLegalNightShiftOvertime();
							$ns_rate = ($rate->getHolidayLegalNightDifferentialOvertime() / 100);
						} else {
							// $day_rate = 1.00;
							// $ot_rate = 1.00;
							$ns_rate = 1.00;
						}

						$ot_hours = (float) $t->getLegalOvertimeNightShiftHours() + $t->getLegalOvertimeNightShiftExcessHours();

						$temp_total = (float) Tools::numberFormat($ot_hours * $hourly_amount * $ns_rate, 2);
						$total = $total + $temp_total;
					}
				}
			}
		}
		return $total;
	}
	//new
	public static function computeCutLegalOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
					$h = $a->getHoliday();
					if ($h && $h->isLegal()) {
						$t = $a->getTimesheet();
						if ($t) {
							// $day_rate = $rate->getHolidayLegal() / 100;
							// $ot_rate = $rate->getHolidayLegalOvertime() / 100;
							// //$ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
							// 	$ns_rate = $rate->getHolidayLegalNightShiftOvertime();
							// //$hourly_amount = ($hourly_amount * $day_rate * $ot_rate);

							if (strtolower($mandated_status) == 'enable') {
								// $day_rate = $rate->getHolidayLegal() / 100;
								// $ot_rate = $rate->getHolidayLegalOvertime() / 100;
								// $ns_rate = $rate->getHolidayLegalNightShiftOvertime();
								$ns_rate = ($rate->getHolidayLegalNightDifferentialOvertime() / 100);
							} else {
								// $day_rate = 1.00;
								// $ot_rate = 1.00;
								$ns_rate = 1.00;
							}
	
							$ot_hours = (float) $t->getLegalOvertimeNightShiftHours() + $t->getLegalOvertimeNightShiftExcessHours();
	
							$temp_total = (float) Tools::numberFormat($ot_hours * $hourly_amount * $ns_rate, 2);
							$total = $total + $temp_total;
						}
					}
				}
			}
		}
		return $total;
	}
	public static function computePrevLegalOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
					$h = $a->getHoliday();
					if ($h && $h->isLegal()) {
						$t = $a->getTimesheet();
						if ($t) {
							// $day_rate = $rate->getHolidayLegal() / 100;
							// $ot_rate = $rate->getHolidayLegalOvertime() / 100;
							// //$ns_rate = ($rate->getNightShiftDiff() - 100) / 100;
							// 	$ns_rate = $rate->getHolidayLegalNightShiftOvertime();
							// //$hourly_amount = ($hourly_amount * $day_rate * $ot_rate);

							if (strtolower($mandated_status) == 'enable') {
								// $day_rate = $rate->getHolidayLegal() / 100;
								// $ot_rate = $rate->getHolidayLegalOvertime() / 100;
								// $ns_rate = $rate->getHolidayLegalNightShiftOvertime();
								$ns_rate = ($rate->getHolidayLegalNightDifferentialOvertime() / 100);
							} else {
								// $day_rate = 1.00;
								// $ot_rate = 1.00;
								$ns_rate = 1.00;
							}
	
							$ot_hours = (float) $t->getLegalOvertimeNightShiftHours() + $t->getLegalOvertimeNightShiftExcessHours();
	
							$temp_total = (float) Tools::numberFormat($ot_hours * $hourly_amount * $ns_rate, 2);
							$total = $total + $temp_total;
						}
					}
				}
			}
		}
		return $total;
	}
	//new
	public static function computeSpecialOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
				$h = $a->getHoliday();
				if ($h && $h->isSpecial()) {
					$t = $a->getTimesheet();
					if ($t) {
						if (strtolower($mandated_status) == 'enable') {
							$day_rate = $rate->getHolidaySpecial() / 100;
							$ot_rate = $rate->getHolidaySpecialOvertime() / 100;
							// $ns_rate = $rate->getHolidaySpecialNightShiftOvertime();
							$ns_rate = ($rate->getHolidaySpecialNightDifferentialOvertime() / 100);
						} else {
							$day_rate = 1.00;
							$ot_rate = 1.00;
							$ns_rate = 1.00;
						}

						$ot_hours = (float) $t->getSpecialOvertimeNightShiftHours() + $t->getSpecialOvertimeNightShiftExcessHours();

						$temp_total = (float) Tools::numberFormat($ot_hours * $hourly_amount * $ns_rate, 2);
						$total = $total + $temp_total;
					}
				}
			}
		}
		return $total;
	}
	//newstart
	public static function computePrevSpecialOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 25", strtotime($date));
			$date_end = date("M t", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
					$h = $a->getHoliday();
					if ($h && $h->isSpecial()) {
						$t = $a->getTimesheet();
						if ($t) {
							if (strtolower($mandated_status) == 'enable') {
								$day_rate = $rate->getHolidaySpecial() / 100;
								$ot_rate = $rate->getHolidaySpecialOvertime() / 100;
								// $ns_rate = $rate->getHolidaySpecialNightShiftOvertime();
								$ns_rate = ($rate->getHolidaySpecialNightDifferentialOvertime() / 100);
							} else {
								$day_rate = 1.00;
								$ot_rate = 1.00;
								$ns_rate = 1.00;
							}
	
							$ot_hours = (float) $t->getSpecialOvertimeNightShiftHours() + $t->getSpecialOvertimeNightShiftExcessHours();
	
							$temp_total = (float) Tools::numberFormat($ot_hours * $hourly_amount * $ns_rate, 2);
							$total = $total + $temp_total;
						}
					}
				}
			}
		}
		return $total;
	}
	public static function computeCutSpecialOvertimeNightShiftAmount($attendance, $rate, $daily_amount, $hourly_amount, $mandated_status = 'Enable')
	{
		$total = 0;
		foreach ($attendance as $a) {
			$date = date("M d", strtotime($a->getDate()));
			$date_start = date("M 01", strtotime($date));
			$date_end = date("M 10", strtotime($date));
			if (($date >= $date_start) && ($date <= $date_end)) {
				if ($a->isPresent() && $a->isHoliday() && !$a->isRestday()) {
					$h = $a->getHoliday();
					if ($h && $h->isSpecial()) {
						$t = $a->getTimesheet();
						if ($t) {
							if (strtolower($mandated_status) == 'enable') {
								$day_rate = $rate->getHolidaySpecial() / 100;
								$ot_rate = $rate->getHolidaySpecialOvertime() / 100;
								// $ns_rate = $rate->getHolidaySpecialNightShiftOvertime();
								$ns_rate = ($rate->getHolidaySpecialNightDifferentialOvertime() / 100);
							} else {
								$day_rate = 1.00;
								$ot_rate = 1.00;
								$ns_rate = 1.00;
							}
	
							$ot_hours = (float) $t->getSpecialOvertimeNightShiftHours() + $t->getSpecialOvertimeNightShiftExcessHours();
	
							$temp_total = (float) Tools::numberFormat($ot_hours * $hourly_amount * $ns_rate, 2);
							$total = $total + $temp_total;
						}
					}
				}
			}
		}
		return $total;
	}
	//newend

	public static function wrapPayslipSection($payslip_array, $section, $template)
	{

		$wrap_earnings_array 			= array();
		$wrap_loan_leave_balance_array 	= array();
		$wrap_deduction_array			= array();
		$wrap_other_earnings_deduction  = array();
		$wrap_breakdown_array			= array();
		$wrap_all_arrays                = array();

		$emp_earnings 			= unserialize($payslip_array['earnings']);
		$emp_other_earnings 	= unserialize($payslip_array['other_earnings']);
		$emp_deduction 			= unserialize($payslip_array['deductions']);
		$emp_other_deductions	= unserialize($payslip_array['other_deductions']);
		$emp_labels			 	= unserialize($payslip_array['labels']);

		//echo '<pre>';
		//print_r($emp_earnings);
		//print_r($emp_other_earnings);
		//print_r($emp_deduction);
		//print_r($emp_other_deductions);
		//echo '</pre>';

		if ($section == 'earnings') {

			foreach ($emp_earnings as $earnings) {
				$variable 							   = strtolower($earnings->getVariable());
				$wrap_earnings_array[$variable]['label'] = $earnings->getLabel();
				$wrap_earnings_array[$variable]['value'] = $earnings->getAmount();
			}

			$earning_from_deduct_tardi = $emp_deduction[0]; //tardines or late			
			$wrap_earnings_array[$earning_from_deduct_tardi->getVariable()]['label'] = $earning_from_deduct_tardi->getLabel();
			$wrap_earnings_array[$earning_from_deduct_tardi->getVariable()]['value'] = $earning_from_deduct_tardi->getAmount();

			$earning_from_deduct_undertime = $emp_deduction[1]; //undertime
			$wrap_earnings_array[$earning_from_deduct_undertime->getVariable()]['label'] = $earning_from_deduct_undertime->getLabel();
			$wrap_earnings_array[$earning_from_deduct_undertime->getVariable()]['value'] = $earning_from_deduct_undertime->getAmount();

			$earning_from_deduct_absent = $emp_deduction[2]; //absent
			$wrap_earnings_array[$earning_from_deduct_absent->getVariable()]['label'] = $earning_from_deduct_absent->getLabel();
			$wrap_earnings_array[$earning_from_deduct_absent->getVariable()]['value'] = $earning_from_deduct_absent->getAmount();

			$wrap_arrays = $wrap_earnings_array;
		} elseif ($section == 'deductions') {

			foreach ($emp_deduction as $deduction) {
				$variable_deduction   							  = strtolower($deduction->getVariable());
				$wrap_deduction_array[$variable_deduction]['label'] = $deduction->getLabel();
				$wrap_deduction_array[$variable_deduction]['value'] = $deduction->getAmount();
			}

			foreach ($emp_other_deductions as $other_deduction) {
				$variable_other_deduction   								= strtolower(preg_replace('/\s+/', '_', $other_deduction->getLabel()));
				$wrap_deduction_array[$variable_other_deduction]['label'] = $other_deduction->getLabel();
				$wrap_deduction_array[$variable_other_deduction]['value'] = $other_deduction->getAmount();
			}

			$wrap_arrays = $wrap_deduction_array;
		} elseif ($section == 'loan_leave_balance') {
		} elseif ($section == 'other_earnings_deductions') {
		} elseif ($section == 'breakdown') {
		}

		return $wrap_arrays;
	}

	public static function computeEmployeeYearlyPayslipBreakdown($employee_id)
	{
		$yearS = mktime(0, 0, 0, 1, 1,  date('Y'));
		$yearE = mktime(0, 0, 0, 12, 31,  date('Y'));

		$start_date = date('Y-m-d', $yearS);
		$end_data   = date('Y-m-d', $yearE);

		$sql = "
			SELECT 
				SUM(p.basic_pay) as y_basic_pay, SUM(p.gross_pay) as y_gross_pay, SUM(p.total_earnings) as y_total_earnings, 
				SUM(p.total_deductions) as y_total_deductions, SUM(p.net_pay) as y_net_pay, SUM(p.taxable) as y_taxable, 
				SUM(p.non_taxable) as y_non_taxable, SUM(p.withheld_tax) as y_withheld_tax, SUM(p.month_13th) as y_month_13th, 
				SUM(p.sss) as y_sss, SUM(p.pagibig) as y_pagibig, SUM(p.philhealth) as y_philhealth, SUM(p.overtime) as y_overtime, 
				SUM(p.tardiness_amount) as y_tardiness_amount
			FROM " . G_EMPLOYEE_MONTHLY_PAYSLIP . " p
			WHERE p.employee_id = " . Model::safeSql($employee_id) . "
				AND (p.period_start BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_data) . " OR p.period_end BETWEEN " . Model::safeSql($start_date) . " AND " . Model::safeSql($end_data) . ")
			LIMIT 1
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function computeEmployeeYearlyPayslipBreakdownByEndDate($employee_id, $end_date = '')
	{
		if ($end_date == '') {
			$sql_end_date = date("Y-m-d");
		} else {
			$sql_end_date = $end_date;
		}

		$sql_year = date("Y", strtotime($sql_end_date));

		$sql = "
			SELECT 
				SUM(p.basic_pay) as y_basic_pay, SUM(p.gross_pay) as y_gross_pay, SUM(p.total_earnings) as y_total_earnings, 
				SUM(p.total_deductions) as y_total_deductions, SUM(p.net_pay) as y_net_pay, SUM(p.taxable) as y_taxable, 
				SUM(p.non_taxable) as y_non_taxable, SUM(p.withheld_tax) as y_withheld_tax, SUM(p.month_13th) as y_month_13th, 
				SUM(p.sss) as y_sss, SUM(p.pagibig) as y_pagibig, SUM(p.philhealth) as y_philhealth, SUM(p.overtime) as y_overtime, 
				SUM(p.tardiness_amount) as y_tardiness_amount
			FROM " . G_EMPLOYEE_MONTHLY_PAYSLIP . " p
			WHERE p.employee_id = " . Model::safeSql($employee_id) . "
				AND p.period_end <= " . Model::safeSql($sql_end_date) . "
				AND YEAR(p.period_end) =" . Model::safeSql($sql_year) . "
			LIMIT 1
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public function sqlGetPayslipDataByDateRange($date_from = '', $date_to = '', $fields = array())
	{
		$sql_fields = " * ";
		if (!empty($fields)) {
			$sql_fields = implode(",", $fields);
		}

		$sql = "
			SELECT {$sql_fields}
			FROM g_employee_monthly_payslip				
			WHERE period_start BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "							
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	public function sqlGetPayslipDataByYear($year)
	{
		$sql_fields = " * ";
		if (!empty($fields)) {
			$sql_fields = implode(",", $fields);
		}

		$sql = "
			SELECT *
			FROM g_employee_monthly_payslip				
			WHERE DATE_FORMAT(period_start,'%Y')
		";

		$result = Model::runSql($sql, true);
		return $result;
	}

	public static function sqlGetPayslipIdsByPeriod($start_date, $end_date)
	{
		$sql = "
			SELECT id, payout_date, period_start, period_end
			FROM g_employee_monthly_payslip
			WHERE (period_start = " . Model::safeSql($start_date) . " AND period_end = " . Model::safeSql($end_date) . ")		
		";
		//return self::getObjects($sql);
		$result = Model::runSql($sql, true);
		return $result;
	}

	public static function isIdExist(G_Monthly_Payslip $p)
	{
		$sql = "
			SELECT COUNT(*) as total
			FROM g_employee_monthly_payslip
			WHERE id = " . Model::safeSql($p->getId()) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
}
