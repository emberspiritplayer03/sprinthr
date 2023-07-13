<?php
class G_Report {
			
	public $from_date;
	public $to_date;	
	public $employee_ids;
	public $department_ids;	
	public $employment_status_ids;
	protected $s_employee_type;

	const REPORT_OPT1 = 'Manpower';
	const REPORT_OPT2 = 'Late';
	const REPORT_OPT3 = 'Leave';
	const REPORT_OPT4 = 'Present';
	const REPORT_OPT5 = 'Official Business';
	const REPORT_OPT6 = 'Overtime';

	public function __construct() {
		
	}

	public function setFromDate($value) {		
		$date_format = date("Y-m-d",strtotime($value));		
		$this->from_date = $date_format;
		return $this;
	}
	
	public function setToDate($value) {
		$date_format = date("Y-m-d",strtotime($value));
		$this->to_date = $date_format;
		return $this;
	}

	public function setEmployeeType($value = '') {
		$this->s_employee_type = $value;
		return $this;
	}

	/*$ids = array(1,2,3)*/
	public function setEmployeeIds($ids = array()){
		$this->employee_ids = $ids;
		return $this;
	}

	public function setDepartmentIds($ids = array()){
		$this->department_ids = $ids;
		return $this;
	}

	public function setEmploymentStatusIds( $ids = array() ){
		$this->employment_status_ids = $ids;
		return $this;
	}

	public function getReportOptions(){
		$data = array(self::REPORT_OPT1,self::REPORT_OPT2,self::REPORT_OPT3,self::REPORT_OPT4,self::REPORT_OPT5,self::REPORT_OPT6);
		
		return $data;
	}

	public function getEmployeesAlphaList() {
		$data = array();

		if( (!empty($this->employee_ids) && !empty($this->from_date) && !empty($this->to_date))  && (strtotime($this->from_date) <= strtotime($this->to_date)) ){

			$eids = $this->employee_ids;
			$date_from = $this->from_date;
			$date_to   = $this->to_date;
			$payslip_data = G_Report_Helper::sqlEmployeePayslipDataByEmployeeIdAndDateRange($eids, $date_from, $date_to);
			
			foreach($payslip_data as $key => $value){
				$employee_payslip_data[$value['epkid']][] = $value; //Group data by employee pkid
			}

			foreach($employee_payslip_data as $key => $values){ //Iterate compute employee total tax due
				
				$net_taxable_compensation = 0; //Reset for next employee
				$total_tax_withheld       = 0; //Reset total tax withheld
				$alpha_data = array(); //Reset alpha data

				foreach( $values as $subkey => $value ){					
					$net_taxable_calculator   = new Net_Taxable_Calculator();
					$net_taxable_compensation = $net_taxable_calculator->compute($value);
					$total_net_taxable_compensation += $net_taxable_compensation;				
					$total_tax_withheld       += $value['withheld_tax_amount'];          
					$payslip_data[$subkey]    = $value;
					$payslip_data[$subkey]['withholding_tax']      = $value['withheld_tax_amount'];    
					$payslip_data[$subkey]['taxable_compensation'] = $net_taxable_compensation;
					$payslip_data[$subkey]['additional_exemption'] = $net_taxable_calculator->computeAdditionalExemptions($value['qualified_dependents']);
					$payslip_data[$subkey]['personal_exemption']   = Net_Taxable_Calculator::FIXED_EXEMPTIONS;
				}
								
				$nt = new G_Net_Taxable_Table();
		        $nt->setNetTaxableCompensation($total_net_taxable_compensation);
		        $nt->setWithholdingTax($total_tax_withheld);
		        $alpha_data = $nt->getTaxDue();

				$data[$key]['taxable_data'] = $payslip_data;
				$data[$key]['alpha_data']   = $alpha_data;
				$data[$key]['alpha_data']['total_tax_withheld'] 			= $total_tax_withheld;
				$data[$key]['alpha_data']['total_net_taxable_compensation'] = $total_net_taxable_compensation;
			}
			
		}

		return $data;
	}

	public function getAllEmployeesAlphaList() {
		$data = array();		
		if( (!empty($this->from_date) && !empty($this->to_date)) && (strtotime($this->from_date) <= strtotime($this->to_date)) ){			

			$date_from = $this->from_date;
			$date_to   = $this->to_date;			
			$payslip_data = G_Report_Helper::sqlAllEmployeePayslipDataByDateRange($date_from, $date_to);
			
			foreach($payslip_data as $key => $value){
				$employee_payslip_data[$value['epkid']][] = $value; //Group data by employee pkid
			}
			
			foreach($employee_payslip_data as $key => $values){ //Iterate compute employee total tax due
				
				$net_taxable_compensation = 0; //Reset for next employee
				$total_tax_withheld       = 0; //Reset total tax withheld
				$total_net_taxable_compensation = 0; //Reset for next employee

				$alpha_data   = array(); //Reset alpha data
				$payslip_data = array(); 

				foreach( $values as $subkey => $value ){					
					$net_taxable_calculator   = new Net_Taxable_Calculator();
					$taxable_compensation     = $net_taxable_calculator->computeTaxableCompensation($value);
					$net_taxable_compensation = $net_taxable_calculator->compute($value);
					$total_net_taxable_compensation += $net_taxable_compensation;				
					$total_tax_withheld       += $value['withheld_tax_amount'];          
					$payslip_data[$subkey]    = $value;
					$payslip_data[$subkey]['withholding_tax']          = $value['withheld_tax_amount'];    
					$payslip_data[$subkey]['taxable_compensation']     = $taxable_compensation;
					$payslip_data[$subkey]['net_taxable_compensation'] = $net_taxable_compensation;
					$payslip_data[$subkey]['additional_exemption']     = $net_taxable_calculator->computeAdditionalExemptions($value['qualified_dependents']);
					$payslip_data[$subkey]['personal_exemption']       = Net_Taxable_Calculator::FIXED_EXEMPTIONS;
				}
								
				$nt = new G_Net_Taxable_Table();
		        $nt->setNetTaxableCompensation($total_net_taxable_compensation);
		        $nt->setWithholdingTax($total_tax_withheld);
		        $alpha_data = $nt->getTaxDue();

				$data[$key]['taxable_data'] = $payslip_data;
				$data[$key]['alpha_data']   = $alpha_data;
				$data[$key]['alpha_data']['total_tax_withheld'] 			= $total_tax_withheld;
				$data[$key]['alpha_data']['total_net_taxable_compensation'] = $total_net_taxable_compensation;
			}
			
		}

		return $data;
	}

	/*
		Usage :
		$employee_ids = array(1,2,3); //use array('all') if all employees
		$date_from    = '2014-01-23';
		$date_to      = '2014-02-15';
		$r = new G_Report();
		$r->setEmployeeIds($employee_ids);
		//$r->setDepartmentIds($department_ids); //if department will be use
		$r->setToDate($date_to);
		$r->setFromDate($date_from);
		$data = $r->getEmployeeSSSContributions();
	*/

	public function getEmployeeSSSContributions(){
		$data = array();

		if( !empty($this->from_date) && !empty($this->to_date) ){
			if( !empty($this->employee_ids) ){				
				$ids = implode(",",$this->employee_ids);				
				if( $ids == 'all' ){
					$data = G_Payslip_Helper::sqlAllEmployeesSSSContributionByDateRange($this->from_date, $this->to_date);
				}else{
					$data = G_Payslip_Helper::sqlEmployeesSSSContributionByEmployeeIdsAndByDateRange($ids, $this->from_date, $this->to_date);
				}
			}elseif( !empty($this->department_ids) ){
				$ids = implode(",",$this->department_ids);
				if( $ids == 'all' ){
					$data = G_Payslip_Helper::sqlAllEmployeesSSSContributionByDateRange($this->from_date, $this->to_date);
				}else{
					$data = G_Payslip_Helper::sqlEmployeesSSSContributionByDepartmentIdsAndByDateRange($ids, $this->from_date, $this->to_date);
				}
			}
		} 

		return $data;

	}

	public function getSSSContributions( $add_query = '') {
		$data = array();		
		if( !empty($this->from_date) && !empty($this->to_date) ){
			$data = G_Payslip_Helper::sqlAllEmployeesSSSContributionByDateRange($this->from_date, $this->to_date, $add_query);
		}

		return $data;
	}
	//new
		public function getSSSContributionsNoDup( $add_query = '') {
		$data = array();		

		if( !empty($this->from_date) && !empty($this->to_date) ){
			$data = G_Payslip_Helper::sqlAllEmployeesSSSContributionByDateRangeNoDup($this->from_date, $this->to_date, $add_query);

		}

		return $data;
	}
		public function getWeeklySSSContributionsNoDup( $add_query = '') {
		$data = array();		
		if( !empty($this->from_date) && !empty($this->to_date) ){
			$data = G_Weekly_Payslip_Helper::sqlAllEmployeesSSSContributionByDateRangeNoDup($this->from_date, $this->to_date, $add_query);
		}

		return $data;
	}
	//new


	public function getMonthlySSSContributionsNoDup( $add_query = '') {
		$data = array();		
		if( !empty($this->from_date) && !empty($this->to_date) ){
			$data = G_Monthly_Payslip_Helper::sqlAllEmployeesSSSContributionByDateRangeNoDup2($this->from_date, $this->to_date, $add_query);
		}

		return $data;
	}

	/**
	* Get 13th month data base on selected year
	*
	* @param string year 
	* @param string add_query
	* @return array
	*/
	public function getYearlyBonus( $year = '', $add_query = '') {
		$data = array();		
		if( $year != '' ){
			$release_date = G_Yearly_Bonus_Release_Date_Helper::sqlAllYearlyBonusReleaseDateByYear($year);
			if( !empty($release_date) ){				
				$query['year'] = $year;
				$data = G_Yearly_Bonus_Release_Date_Helper::getDataByYear($query, $add_query);
			}			
		}

		return $data;
	}

	public function getPhilhealthContributions( $add_query = '') {
		$data = array();		
		if( !empty($this->from_date) && !empty($this->to_date) ){
			//$data = G_Payslip_Helper::sqlAllEmployeesPhilhealthContributionByDateRange($this->from_date, $this->to_date, $add_query); Old computation (2017)
			$data = G_Payslip_Helper::sqlAllEmployeesRevisedPhilhealthContributionByDateRange($this->from_date, $this->to_date, $add_query);
		}

		return $data;
	}

		public function getPhilhealthContributionsNoDup( $add_query = '') {
		$data = array();		
		if( !empty($this->from_date) && !empty($this->to_date) ){
			//$data = G_Payslip_Helper::sqlAllEmployeesPhilhealthContributionByDateRange($this->from_date, $this->to_date, $add_query); Old computation (2017)
			$data = G_Payslip_Helper::sqlAllEmployeesRevisedPhilhealthContributionByDateRangeNoDup($this->from_date, $this->to_date, $add_query);
		}

		return $data;
	}

		public function getWeeklyPhilhealthContributionsNoDup( $add_query = '') {
		$data = array();		
		if( !empty($this->from_date) && !empty($this->to_date) ){
			//$data = G_Payslip_Helper::sqlAllEmployeesPhilhealthContributionByDateRange($this->from_date, $this->to_date, $add_query); Old computation (2017)
			$data = G_Weekly_Payslip_Helper::sqlAllEmployeesRevisedPhilhealthContributionByDateRangeNoDup($this->from_date, $this->to_date, $add_query);
		}

		return $data;
	}

		public function getMonthlyPhilhealthContributionsNoDup( $add_query = '') {
		$data = array();		
		if( !empty($this->from_date) && !empty($this->to_date) ){
			//$data = G_Payslip_Helper::sqlAllEmployeesPhilhealthContributionByDateRange($this->from_date, $this->to_date, $add_query); Old computation (2017)
			$data = G_Monthly_Payslip_Helper::sqlAllEmployeesRevisedPhilhealthContributionByDateRangeNoDup($this->from_date, $this->to_date, $add_query);
		}

		return $data;
	}

	public function getPagibigContributions( $add_query = '') {
		$data = array();		
		if( !empty($this->from_date) && !empty($this->to_date) ){
			// $data = G_Payslip_Helper::sqlAllEmployeesPagibigContributionByDateRange($this->from_date, $this->to_date, $add_query);
				$data = G_Payslip_Helper::sqlAllEmployeesPagibigContributionByDateRange($this->from_date, $this->to_date, $add_query);
			if( !empty($data) ){
				foreach( $data as $key => $d ){
					$a_labels = unserialize($d['labels']);	
					$data[$key]['pagibig_employer'] = 0;				
					foreach( $a_labels as $label ){
						$label_title = $label->getLabel();
						$label_value = $label->getValue();
						if( $label_title == 'Pagibig Employer' ){							
							$data[$key]['pagibig_employer'] = $label_value;
						}
					}	
					unset($data[$key]['labels']);				
				}
			}
		}

		return $data;
	}
//new
	public function getPagibigContributionsNoDup( $add_query = '') {
		$data = array();		
		if( !empty($this->from_date) && !empty($this->to_date) ){
			// $data = G_Payslip_Helper::sqlAllEmployeesPagibigContributionByDateRange($this->from_date, $this->to_date, $add_query);
				$data = G_Payslip_Helper::sqlAllEmployeesPagibigContributionByDateRangeNoDup($this->from_date, $this->to_date, $add_query);
			if( !empty($data) ){
				foreach( $data as $key => $d ){
					$a_labels = unserialize($d['labels']);	
					$data[$key]['pagibig_employer'] = 0;				
					foreach( $a_labels as $label ){
						$label_title = $label->getLabel();
						$label_value = $label->getValue();
						if( $label_title == 'Pagibig Employer' ){							
							$data[$key]['pagibig_employer'] = $label_value;
						}
					}	
					unset($data[$key]['labels']);				
				}
			}
		}

		return $data;
	}
	public function getWeeklyPagibigContributionsNoDup( $add_query = '') {
		$data = array();		
		if( !empty($this->from_date) && !empty($this->to_date) ){
			// $data = G_Payslip_Helper::sqlAllEmployeesPagibigContributionByDateRange($this->from_date, $this->to_date, $add_query);
				$data = G_Weekly_Payslip_Helper::sqlAllEmployeesPagibigContributionByDateRangeNoDup($this->from_date, $this->to_date, $add_query);



			if( !empty($data) ){
				foreach( $data as $key => $d ){
					$a_labels = unserialize($d['labels']);
					$data[$key]['pagibig_employer'] = 0;				
					foreach( $a_labels as $label ){
					    $label_title = $label->getLabel();
						$label_value = $label->getValue();
						if( $label_title == 'Pagibig Employer' ){							
							$data[$key]['pagibig_employer'] = $label_value;
						}
					}	
					unset($data[$key]['labels']);				
				}
			}
		}
		return $data;
	}

	public function getMonthlyPagibigContributionsNoDup( $add_query = '') {
		$data = array();		
		if( !empty($this->from_date) && !empty($this->to_date) ){
			// $data = G_Payslip_Helper::sqlAllEmployeesPagibigContributionByDateRange($this->from_date, $this->to_date, $add_query);
				$data = G_Monthly_Payslip_Helper::sqlAllEmployeesPagibigContributionByDateRangeNoDup($this->from_date, $this->to_date, $add_query);
			if( !empty($data) ){
				foreach( $data as $key => $d ){
					$a_labels = unserialize($d['labels']);	
					$data[$key]['pagibig_employer'] = 0;				
					foreach( $a_labels as $label ){
						$label_title = $label->getLabel();
						$label_value = $label->getValue();
						if( $label_title == 'Pagibig Employer' ){							
							$data[$key]['pagibig_employer'] = $label_value;
						}
					}	
					unset($data[$key]['labels']);				
				}
			}
		}

		return $data;
	}

//new
	/*
		Usage :
		$employee_ids = array(1,2,3); //use array('all') if all employees
		$date_from    = '2014-01-23';
		$date_to      = '2014-02-15';
		$r = new G_Report();
		$r->setEmployeeIds($employee_ids);
		//$r->setDepartmentIds($department_ids); //if department will be use
		$r->setToDate($date_to);
		$r->setFromDate($date_from);
		$data = $r->getEmployeeTaxContributions();
	*/

	public function getEmployeeTaxContributions(){
		$data = array();

		if( !empty($this->from_date) && !empty($this->to_date) ){
			if( !empty($this->employee_ids) ){				
				$ids = implode(",",$this->employee_ids);				
				if( $ids == 'all' ){
					$data = G_Payslip_Helper::sqlAllEmployeesTaxContributionByDateRange($this->from_date, $this->to_date);
				}else{
					$data = G_Payslip_Helper::sqlEmployeesTaxContributionByEmployeeIdsAndByDateRange($ids, $this->from_date, $this->to_date);
				}
			}elseif( !empty($this->department_ids) ){
				$ids = implode(",",$this->department_ids);
				if( $ids == 'all' ){
					$data = G_Payslip_Helper::sqlAllEmployeesTaxContributionByDateRange($this->from_date, $this->to_date);
				}else{
					$data = G_Payslip_Helper::sqlEmployeesTaxContributionByDepartmentIdsAndByDateRange($ids, $this->from_date, $this->to_date);
				}
			}
		} 

		return $data;

	}

	/*
		Usage :
		$employee_ids = array(1,2,3); //use array('all') if all employees
		$date_from    = '2014-01-23';
		$date_to      = '2014-02-15';
		$r = new G_Report();
		$r->setEmployeeIds($employee_ids);
		//$r->setDepartmentIds($department_ids); //if department will be use
		$r->setToDate($date_to);
		$r->setFromDate($date_from);
		$data = $r->getEmployeePhilhealthContributions();
	*/

	public function getEmployeePhilhealthContributions(){
		$data = array();

		if( !empty($this->from_date) && !empty($this->to_date) ){
			if( !empty($this->employee_ids) ){				
				$ids = implode(",",$this->employee_ids);				
				if( $ids == 'all' ){
					$data = G_Payslip_Helper::sqlAllEmployeesPhilhealthContributionByDateRange($this->from_date, $this->to_date);
				}else{
					$data = G_Payslip_Helper::sqlEmployeesPhilhealthContributionByEmployeeIdsAndByDateRange($ids, $this->from_date, $this->to_date);
				}
			}elseif( !empty($this->department_ids) ){
				$ids = implode(",",$this->department_ids);
				if( $ids == 'all' ){
					$data = G_Payslip_Helper::sqlAllEmployeesPhilhealthContributionByDateRange($this->from_date, $this->to_date);
				}else{
					$data = G_Payslip_Helper::sqlEmployeesPhilhealthContributionByDepartmentIdsAndByDateRange($ids, $this->from_date, $this->to_date);
				}
			}
		} 

		return $data;

	}

	/*
		Usage :
		$employee_ids = array(1,2,3); //use array('all') if all employees
		$date_from    = '2014-01-23';
		$date_to      = '2014-02-15';
		$r = new G_Report();
		$r->setEmployeeIds($employee_ids);
		//$r->setDepartmentIds($department_ids); //if department will be use
		$r->setToDate($date_to);
		$r->setFromDate($date_from);
		$data = $r->getEmployeePagibigContributions();
	*/

	public function getEmployeePagibigContributions(){
		$data = array();

		if( !empty($this->from_date) && !empty($this->to_date) ){
			if( !empty($this->employee_ids) ){				
				$ids = implode(",",$this->employee_ids);				
				if( $ids == 'all' ){
					$data = G_Payslip_Helper::sqlAllEmployeesPagibigContributionByDateRange($this->from_date, $this->to_date);
				}else{
					$data = G_Payslip_Helper::sqlEmployeesPagibigContributionByEmployeeIdsAndByDateRange($ids, $this->from_date, $this->to_date);
				}
			}elseif( !empty($this->department_ids) ){
				$ids = implode(",",$this->department_ids);
				if( $ids == 'all' ){
					$data = G_Payslip_Helper::sqlAllEmployeesPagibigContributionByDateRange($this->from_date, $this->to_date);
				}else{
					$data = G_Payslip_Helper::sqlEmployeesPagibigContributionByDepartmentIdsAndByDateRange($ids, $this->from_date, $this->to_date);
				}
			}
		} 
		return $data;
	}

	public function generateManpowerReport($manpower_data, $add_query) {
		$data = array();
		if( $this->from_date != '' && $this->to_date != '' ){
			$fields = array('id','code','status');
			$ids = array(1,3,4,5);
			$employment_status = G_Settings_Employment_Status_Helper::sqlGetEmploymentStatusByIds($ids, $fields);
			foreach( $employment_status as $status ){
				$query = array(
					'employment_status_id' => $status['id'],
					'gender' => 'Male',
					'date_from' => $this->from_date,
					'date_to' => $this->to_date
				);

				//Male
				$data['current'][$status['status']]['Male'] = G_Report_Helper::manpowerCountByEmploymentStatusId($query, $add_query);

				//Female
				$query['gender'] = 'Female';
				$data['current'][$status['status']]['Female'] = G_Report_Helper::manpowerCountByEmploymentStatusId($query, $add_query);

				//Prev Date
				$prev_date_to   = date("Y-m-t",strtotime($this->from_date . " -1 month"));	
				$prev_date_from = date("Y-m-01",strtotime($prev_date_from));	
				$query = array(
					'employment_status_id' => $status['id'],
					'gender' => 'Male',
					'date_from' => $prev_date_from,
					'date_to' => $prev_date_to
				);

				//Male
				$data['previous'][$status['status']]['Male'] = G_Report_Helper::manpowerCountByEmploymentStatusId($query, $add_query);

				//Female
				$query['gender'] = 'Female';
				$data['previous'][$status['status']]['Female'] = G_Report_Helper::manpowerCountByEmploymentStatusId($query, $add_query);	
			}			
		}		
		return $data;
	}

	public function generateManpowerReportDetailed($manpower_data, $add_query, $add_query2) {
		$data = array();
		$dept_a = array();
		if( $this->from_date != '' && $this->to_date != '' ){
			$fields = array('id','code','status');
			$ids = array(1,3,4,5);
			$employment_status = G_Settings_Employment_Status_Helper::sqlGetEmploymentStatusByIds($ids, $fields);

			$dept = G_Company_Structure_Finder::findAllDepartmentsIsNotArchiveByBranchIdAndParentIdIncludeArchive(1,1);

			foreach($dept as $deptk => $d) {

				$dept_id = $d->getId();
				foreach( $employment_status as $status ){

					$query = array(
						'employment_status_id' => $status['id'],
						'gender' => 'Male',
						'date_from' => $this->from_date,
						'date_to' => $this->to_date
					);

					//Male
					//$data['current'][$status['status']]['Male'] = G_Report_Helper::manpowerCountByEmploymentStatusIdByDepartmentId($query, $add_query, $dept_id);
					$data[$status['status']]['Male'] = G_Report_Helper::manpowerCountByEmploymentStatusIdByDepartmentId($query, $add_query, $add_query2, $dept_id);

					//Female
					$query['gender'] = 'Female';
					//$data['current'][$status['status']]['Female'] = G_Report_Helper::manpowerCountByEmploymentStatusIdByDepartmentId($query, $add_query, $dept_id);
					$data[$status['status']]['Female'] = G_Report_Helper::manpowerCountByEmploymentStatusIdByDepartmentId($query, $add_query, $add_query2, $dept_id);

					//Prev Date
					$prev_date_to   = date("Y-m-t",strtotime($this->from_date . " -1 month"));	
					$prev_date_from = date("Y-m-01",strtotime($prev_date_from));	
					$query = array(
						'employment_status_id' => $status['id'],
						'gender' => 'Male',
						'date_from' => $prev_date_from,
						'date_to' => $prev_date_to
					);

					//Male
					//$data['previous'][$status['status']]['Male'] = G_Report_Helper::manpowerCountByEmploymentStatusIdByDepartmentId($query, $add_query, $dept_id);
					//$data[$status['status']]['Male'] += G_Report_Helper::manpowerCountByEmploymentStatusIdByDepartmentId($query, $add_query, $dept_id);

					//Female
					//$query['gender'] = 'Female';
					//$data['previous'][$status['status']]['Female'] = G_Report_Helper::manpowerCountByEmploymentStatusIdByDepartmentId($query, $add_query, $dept_id);	
					//$data[$status['status']]['Female'] += G_Report_Helper::manpowerCountByEmploymentStatusIdByDepartmentId($query, $add_query, $dept_id);	

				}

				$dept_a[$d->getTitle()] = $data;

			}

			
		}	

		return $dept_a;
	}	

	public function generateManpowerReportDetailedPerSection($manpower_data, $add_query, $add_query2) {
		$data = array();
		$section_a = array();
		if( $this->from_date != '' && $this->to_date != '' ){
			$fields = array('id','code','status');
			$ids = array(1,3,4,5);
			$employment_status = G_Settings_Employment_Status_Helper::sqlGetEmploymentStatusByIds($ids, $fields);

			//$section = G_Company_Structure_Finder::findAllSectionsIsNotArchiveByBranchIdAndParentIdIncludeArchive(1,1);			
			$section = G_Employee_Helper::getEmployeeSectionByGroup($this->to_date, $add_query);

			foreach($section as $deptk => $d) {

				//$dept_id = $d->getId();
				$section_id       = $d['section_id'];
				$department_id    = $d['department_company_structure_id'];
				foreach( $employment_status as $status ){

					$query = array(
						'employment_status_id' => $status['id'],
						'gender' => 'Male',
						'date_from' => $this->from_date,
						'date_to' => $this->to_date
					);

					//Male
					$data[$status['status']]['Male'] = G_Report_Helper::manpowerCountByEmploymentStatusIdBySectionId($query, $add_query, $add_query2, $section_id, $department_id);

					//Female
					$query['gender'] = 'Female';
					$data[$status['status']]['Female'] = G_Report_Helper::manpowerCountByEmploymentStatusIdBySectionId($query, $add_query, $add_query2, $section_id, $department_id);

					//Prev Date
					/*
					$prev_date_to   = date("Y-m-t",strtotime($this->from_date . " -1 month"));	
					$prev_date_from = date("Y-m-01",strtotime($prev_date_from));	
					$query = array(
						'employment_status_id' => $status['id'],
						'gender' => 'Male',
						'date_from' => $prev_date_from,
						'date_to' => $prev_date_to
					);
					*/

				}

				$department = G_Company_Structure_Finder::findById($department_id);
				$section    = G_Company_Structure_Finder::findById($section_id);

				$section_a[$department->getId() . '-' . $section->getId()] = $data;
				
			}
			
		}	

		return $section_a;
	}		

	public function generateManpowerReportDepre($manpower_data = array()){
		$data = array();
		if( !empty($this->from_date) && !empty($this->to_date) ){		
			$report_type = $manpower_data['report_type'];			
			switch ($report_type) {
				case self::REPORT_OPT1: //Manpower
					if($manpower_data['employee_listing'] == "Yes") {
						$data = G_Report_Helper::sqlManpowerReportListedEmployee($manpower_data, $this->from_date, $this->to_date);
					}else{
						$data = G_Report_Helper::sqlManpowerReport($manpower_data, $this->from_date, $this->to_date);
					}
																
					break;
				case self::REPORT_OPT2: //Late
					if($manpower_data['employee_listing'] == "Yes") {
						$data = G_Report_Helper::sqlManpowerLateReportListedEmployee($manpower_data, $this->from_date, $this->to_date);
					}else{					
						$data = G_Report_Helper::sqlManpowerLateReport($manpower_data, $this->from_date, $this->to_date);
					}
					break;
				case self::REPORT_OPT3: //Leave
					if($manpower_data['employee_listing'] == "Yes") {
						$data = G_Report_Helper::sqlManpowerLeaveReportListedEmployee($manpower_data, $this->from_date, $this->to_date);
					}else{
						$data = G_Report_Helper::sqlManpowerLeaveReport($manpower_data, $this->from_date, $this->to_date);
					}
					break;
				case self::REPORT_OPT4: //Present
					if($manpower_data['employee_listing'] == "Yes") {
						$data = G_Report_Helper::sqlManpowerPresentReportListedEmployee($manpower_data, $this->from_date, $this->to_date);
					}else{
						$data = G_Report_Helper::sqlManpowerPresentReport($manpower_data, $this->from_date, $this->to_date);
					}
					break;
				case self::REPORT_OPT5: //Official Business				
					if($manpower_data['employee_listing'] == "Yes") {
						$data = G_Report_Helper::sqlManpowerOBReportListedEmployee($manpower_data, $this->from_date, $this->to_date);
					}else{
						$data = G_Report_Helper::sqlManpowerOBReport($manpower_data, $this->from_date, $this->to_date);
					}
					break;					
				case self::REPORT_OPT6: //Overtime
					if($manpower_data['employee_listing'] == "Yes") {
						$data = G_Report_Helper::sqlManpowerOTReportListedEmployee($manpower_data, $this->from_date, $this->to_date);
					}else{
						$data = G_Report_Helper::sqlManpowerOTReport($manpower_data, $this->from_date, $this->to_date);
					}
					break;	
				default:					
					break;
			}
		}

		return $data;
	}

	public function cashFileReport( $qry_add_on = array(), $show_yearly_bonus, $frequency_id = 0) {	
		$a_return = array();
		if( !empty($this->from_date) && !empty($this->to_date) ){	
			$s_query = '';
			$this->s_employee_type = trim(strtolower($this->s_employee_type));
			switch ($this->s_employee_type) {	
			case 'confidential':	
				$s_query = "AND (e.is_confidential = 1)";	
				break;
			case 'non-confidential':
				$s_query = "AND (e.is_confidential = 0)";
				break;
			default: //All	
				break;	
			}

			if( !empty($qry_add_on) && $this->s_employee_type != "" ){
				$s_query .= " AND " . implode(" AND ", $qry_add_on);
			}elseif( !empty($qry_add_on) ){
				$s_query .= implode(" AND ", $qry_add_on);
			}

			$s_employee_ids = '';	
			if( !empty($this->employee_ids) ){
				$s_employee_ids = implode(",", $this->employee_ids);
				if( $show_yearly_bonus == 'true' ){
					$data = G_Report_Helper::sqlEmployeesCashFileYearlyBonusByPeriodStartAndEnd( $s_employee_ids, $this->from_date, $this->to_date, $s_query );
				} else {		

					if ($frequency_id == 2) {
						$data = G_Report_Helper::sqlEmployeesCashFileByWeeklyPeriodStartAndEnd( $s_employee_ids, $this->from_date, $this->to_date, $s_query );
					}
					else if ($frequency_id == 3) {
						$data = G_Report_Helper::sqlEmployeesCashFileByMonthlyPeriodStartAndEnd( $s_employee_ids, $this->from_date, $this->to_date, $s_query );
					}
					else {
						$data = G_Report_Helper::sqlEmployeesCashFileByPeriodStartAndEnd( $s_employee_ids, $this->from_date, $this->to_date, $s_query );
					}

				}	
			}else{	
				if( $show_yearly_bonus == 'true' ){

					if ($frequency_id == 2) {
						
						$data = G_Report_Helper::sqlAllEmployeesCashFileYearlyBonusByWeeklyPeriodStartAndEnd( $this->from_date, $this->to_date, $s_query );
					}
					else if ($frequency_id == 3) {
						
						$data = G_Report_Helper::sqlAllEmployeesCashFileYearlyBonusByMonthlyPeriodStartAndEnd( $this->from_date, $this->to_date, $s_query );
					}

					else {
						$data = G_Report_Helper::sqlAllEmployeesCashFileYearlyBonusByPeriodStartAndEnd( $this->from_date, $this->to_date, $s_query );
					}

				} else {
					
					if ($frequency_id == 2) {
						$data = G_Report_Helper::sqlAllEmployeesCashFileByWeeklyPeriodStartAndEnd( $this->from_date, $this->to_date, $s_query );
					}
						else if ($frequency_id == 3) {
						$data = G_Report_Helper::sqlAllEmployeesCashFileByMonthlyPeriodStartAndEnd( $this->from_date, $this->to_date, $s_query );
					}
					else {
						$data = G_Report_Helper::sqlAllEmployeesCashFileByPeriodStartAndEnd( $this->from_date, $this->to_date, $s_query );
					}

				}	
		}	
			$a_return = $data;
		}

		return $a_return;
	}	

	public function cashFileReportBonusServiceAwardOnly( $qry_add_on = array(), $show_yearly_bonus ) {
		$a_return = array();

		if( !empty($this->from_date) && !empty($this->to_date) ){
			$s_query = '';
			$this->s_employee_type = trim(strtolower($this->s_employee_type));
			switch ($this->s_employee_type) {	
			case 'confidential':	
				$s_query = "AND (e.is_confidential = 1)";	
				break;
			case 'non-confidential':
				$s_query = "AND (e.is_confidential = 0)";
				break;
			default: //All	
				break;	
			}

			if( !empty($qry_add_on) && $this->s_employee_type != "" ){
				$s_query .= " AND " . implode(" AND ", $qry_add_on);
			}elseif( !empty($qry_add_on) ){
				$s_query .= implode(" AND ", $qry_add_on);
			}

			$data = G_Report_Helper::sqlAllEmployeesCashFileByPeriodStartAndEndAndBonusAndServiceAwardOnly( $this->from_date, $this->to_date, $s_query );

			$cashfile_earnings_array = array();
			$total_employee_net_amount = 0;
            $net_amount    = 0;
            $tax_amount    = 0;

			foreach($data as $dkey => $d) {

				if( $d['title'] == "Service Award" || $d['title'] == "Bonus" ) {

		            if($d['is_taxable'] == "Yes") {

		            	$e = G_Employee_Finder::findbyId($d['id']);

		            	$s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($e, $this->to_date);
				        $pay_period_id = $s->getPayPeriodId();
				        $pay_period    = G_Settings_Pay_Period_Finder::findById($pay_period_id);

						$new_tax_computation = true; //for 2018 new tax computation

			            if($new_tax_computation) {
			                if ($pay_period->getPayPeriodCode() == G_Settings_Pay_Period::TYPE_BI_MONTHLY) {
			                    $tax_table = Tax_Table_Factory::getRevisedTax(Tax_Table::SEMI_MONTHLY);
			                } elseif ($pay_period->getPayPeriodCode() == G_Settings_Pay_Period::TYPE_MONTHLY) {
			                    $tax_table = Tax_Table_Factory::getRevisedTax(Tax_Table::MONTHLY);
			                }
			            } else {
			                if ($pay_period->getPayPeriodCode() == G_Settings_Pay_Period::TYPE_BI_MONTHLY) {
			                    $tax_table = Tax_Table_Factory::get(Tax_Table::SEMI_MONTHLY);
			                } elseif ($pay_period->getPayPeriodCode() == G_Settings_Pay_Period::TYPE_MONTHLY) {
			                    $tax_table = Tax_Table_Factory::get(Tax_Table::MONTHLY);
			                }
			            }			           
						
			            $tax = new Tax_Calculator;
			            $tax->setTaxTable($tax_table);
			            $tax->setTaxableIncome($d['net_amount']);
			            if ($e->getNumberDependent() > 4) {
			                $dependents = 4;
			            } else {
			                $dependents	= $e->getNumberDependent();
			            }	

			            $dependents = 0;

			            $tax->setNumberOfDependent($dependents);
			            
			            if($new_tax_computation) {
			            	$tax_amount = round($tax->computeHB563(), 2);	            
			            } else {
			            	$tax_amount = round($tax->compute(), 2);	
			            }
			            
		            } else {
		            	$tax_amount = 0;
		            }

		            $cashfile_earnings_array[$d['id']]['id']            = $d['id'];
		            $cashfile_earnings_array[$d['id']]['employee_code'] = $d['employee_code'];
		            $cashfile_earnings_array[$d['id']]['employee_name'] = $d['employee_name'];
		            $cashfile_earnings_array[$d['id']]['bank_name']     = $d['bank_name'];
		            $cashfile_earnings_array[$d['id']]['account']       = $d['account'];
		            $cashfile_earnings_array[$d['id']]['net_amount']    += $d['net_amount']; 

		            $cashfile_earnings_array[$d['id']]['tax_amount']	+= $tax_amount; 

				}

			}


			$a_return 	= $cashfile_earnings_array;

		}

		return $a_return;
	}


		public function cashFileReportBonusServiceAwardOnlyFilterStatus( $qry_add_on = array(), $show_yearly_bonus, $s_from, $s_to, $frequency_id = 0)  {
		$a_return = array();

		if( !empty($this->from_date) && !empty($this->to_date) ){
			$s_query = '';
			$this->s_employee_type = trim(strtolower($this->s_employee_type));
			switch ($this->s_employee_type) {	
			case 'confidential':	
				$s_query = "AND (e.is_confidential = 1)";	
				break;
			case 'non-confidential':
				$s_query = "AND (e.is_confidential = 0)";
				break;
			default: //All	
				break;	
			}

			if( !empty($qry_add_on) && $this->s_employee_type != "" ){
				$s_query .= " AND " . implode(" AND ", $qry_add_on);
			}elseif( !empty($qry_add_on) ){
				$s_query .= implode(" AND ", $qry_add_on);
			}

			if ($frequency_id == 2) {
				$data = G_Report_Helper::sqlAllEmployeesCashFileByWeeklyPeriodStartAndEndAndBonusAndServiceAwardOnly( $this->from_date, $this->to_date, $s_query );
			}
			else {
				$data = G_Report_Helper::sqlAllEmployeesCashFileByPeriodStartAndEndAndBonusAndServiceAwardOnly( $this->from_date, $this->to_date, $s_query );
			}

			$cashfile_earnings_array = array();
			$total_employee_net_amount = 0;
            $net_amount    = 0;
            $tax_amount    = 0;

			foreach($data as $dkey => $d) {

				if( $d['title'] == "Service Award" || $d['title'] == "Bonus" ) {
				  


				   $active_employess = G_Employee_Status_History_Helper::findInactiveEmployeesByIdAndInBetweenDates($d['id'], $s_from, $s_to);

				   	if($active_employess['status'] == NULL){
				   				            if($d['is_taxable'] == "Yes") {

		            	$e = G_Employee_Finder::findbyId($d['id']);

		            	$s = G_Employee_Basic_Salary_History_Finder::findByEmployeeAndDate($e, $this->to_date);
				        $pay_period_id = $s->getPayPeriodId();
				        $pay_period    = G_Settings_Pay_Period_Finder::findById($pay_period_id);




						$new_tax_computation = true; //for 2018 new tax computation

			            if($new_tax_computation) {
			                if ($pay_period->getPayPeriodCode() == G_Settings_Pay_Period::TYPE_BI_MONTHLY) {
			                    $tax_table = Tax_Table_Factory::getRevisedTax(Tax_Table::SEMI_MONTHLY);
			                } elseif ($pay_period->getPayPeriodCode() == G_Settings_Pay_Period::TYPE_MONTHLY) {
			                    $tax_table = Tax_Table_Factory::getRevisedTax(Tax_Table::MONTHLY);
			                }
			            } else {
			                if ($pay_period->getPayPeriodCode() == G_Settings_Pay_Period::TYPE_BI_MONTHLY) {
			                    $tax_table = Tax_Table_Factory::get(Tax_Table::SEMI_MONTHLY);
			                } elseif ($pay_period->getPayPeriodCode() == G_Settings_Pay_Period::TYPE_MONTHLY) {
			                    $tax_table = Tax_Table_Factory::get(Tax_Table::MONTHLY);
			                }
			            }			           
						
			            $tax = new Tax_Calculator;
			            $tax->setTaxTable($tax_table);
			            $tax->setTaxableIncome($d['net_amount']);
			            if ($e->getNumberDependent() > 4) {
			                $dependents = 4;
			            } else {
			                $dependents	= $e->getNumberDependent();
			            }	

			            $dependents = 0;

			            $tax->setNumberOfDependent($dependents);
			            
			            if($new_tax_computation) {
			            	$tax_amount = round($tax->computeHB563(), 2);	            
			            } else {
			            	$tax_amount = round($tax->compute(), 2);	
			            }
			            
		            } else {
		            	$tax_amount = 0;
		            }

		            $cashfile_earnings_array[$d['id']]['id']            = $d['id'];
		            $cashfile_earnings_array[$d['id']]['employee_code'] = $d['employee_code'];
		            $cashfile_earnings_array[$d['id']]['employee_name'] = $d['employee_name'];
		            $cashfile_earnings_array[$d['id']]['bank_name']     = $d['bank_name'];
		            $cashfile_earnings_array[$d['id']]['account']       = $d['account'];
		            $cashfile_earnings_array[$d['id']]['net_amount']    += $d['net_amount']; 

		            $cashfile_earnings_array[$d['id']]['tax_amount']	+= $tax_amount; 
				   	}

				 


				}

			}


			$a_return 	= $cashfile_earnings_array;

		}

		return $a_return;
	}



	/*
	public function cashFileReport( $qry_add_on = array(), $show_yearly_bonus = false ) {
		$a_return = array();
		if( !empty($this->from_date) && !empty($this->to_date) ){			
			$s_query = '';
			$this->s_employee_type = trim(strtolower($this->s_employee_type));
			switch ($this->s_employee_type) {				
				case 'confidential':						
					$s_query = "AND (e.is_confidential = 1)";			
					break;
				case 'non-confidential':
					$s_query = "AND (e.is_confidential = 0)";
					break;
				default: //All		
					break;				
			}

			if( !empty($qry_add_on) && $this->s_employee_type != "" ){
				$s_query .= " AND " . implode(" AND ", $qry_add_on);
			}elseif( !empty($qry_add_on) ){
				$s_query .= implode(" AND ", $qry_add_on);
			}
			$s_employee_ids = '';							
			if( !empty($this->employee_ids) ){				
				$s_employee_ids = implode(",", $this->employee_ids);
				if( $show_yearly_bonus ){					
					$data = G_Report_Helper::sqlEmployeesCashFileYearlyBonusByPeriodStartAndEnd( $s_employee_ids, $this->from_date, $this->to_date, $s_query );
				}else{
					$data = G_Report_Helper::sqlEmployeesCashFileByPeriodStartAndEnd( $s_employee_ids, $this->from_date, $this->to_date, $s_query );
				}				
			}else{				
				if( $show_yearly_bonus ){	
					$data = G_Report_Helper::sqlAllEmployeesCashFileYearlyBonusByPeriodStartAndEnd( $this->from_date, $this->to_date, $s_query );
				}else{
					$data = G_Report_Helper::sqlAllEmployeesCashFileByPeriodStartAndEnd( $this->from_date, $this->to_date, $s_query );
				}				
			}			
			$a_return = $data;
		}

		return $a_return;
	}
	*/

	public function loanReport( $options = array() ) {
		$data = array();
		if( !empty($options) ){
			
			$loan_type_id    = Utilities::decrypt(trim($options['loan_type']));
			$employee_ids    = explode(",", $options['loans_employee_id']);
			$deptsection_ids = explode(",", $options['loans_dept_section_id']);
			$employment_status_ids = explode(",", $options['loans_employment_status_id']);
			$loan_report_type = $options['loan_report_type'];

			//Decrypt IDS
			foreach( $employee_ids as $key => $eid ){
				$employee_ids[$key] = Utilities::decrypt($eid);
			}

			foreach( $deptsection_ids as $key => $eid ){
				$deptsection_ids[$key] = Utilities::decrypt($eid);
			}

			foreach( $employment_status_ids as $key => $eid ){
				$employment_status_ids[$key] = Utilities::decrypt($eid);
			}

			$loan = new G_Employee_Loan();
			$loan->setLoanTypeId();
			switch ($loan_report_type) {
				case 1:
					$year_tag = $options['year_tag'][1];
					$period   = $options['period'][1];

					$part_period  = explode("/", $period);
					$date_start = $part_period[0];
					$date_end   = $part_period[1];

					$data['employees'] = $loan->getEmployeesLoanByLoanTypeAndStartAndEndDate($loan_type_id, $employee_ids, array($date_start,$date_end), $employee_ids);					
					break;
				case 2 :
					$year_tag = $options['year_tag'][2];
					$month    = $options['period'][2];
					$data['employees'] = $loan->getEmployeesLoansByMonth($loan_type_id, $month, $employee_ids);					
					break;
				default:
					return $data;					
					break;
			}

			Utilities::displayArray($data);
			Utilities::displayArray($employee_ids);
			Utilities::displayArray($deptsection_ids);
			Utilities::displayArray($employment_status_ids);
			exit;

		}

		return $data;
	}

	/*
		Usage : 
		$data = array();
		$s_from = "2015-03-26";
		$s_to   = "2015-04-20";
		$report = new G_Report();
		$report->setFromDate($s_from);
		$report->setToDate($s_to);
		$data = $report->employeesWorkAgainstSchedule(); 
	*/

	public function employeesWorkAgainstSchedule() {
		$data = array();
		$i_total_early_in  = 0;
		$i_total_late      = 0;
		$i_total_early_out = 0;

		if( strtotime( $this->from_date ) <= strtotime($this->to_date) ){
			$s_from = date("Y-m-d", strtotime($this->from_date));
			$s_to   = date("Y-m-d", strtotime($this->to_date));
			$attendance = G_Attendance_Finder::findAllEmployeeAttendanceByStartAndEndDate($s_from, $s_to);
			
			foreach( $attendance as $a ){				
				$o_t = $a->getTimeSheet();
				//Utilities::displayArray($a);
				if( !empty($o_t) ){
					$s_schedule_date_in  = $o_t->getScheduledDateIn();
					$s_schedule_date_out = $o_t->getScheduledDateOut();
					$s_schedule_time_in  = $o_t->getScheduledTimeIn();
					$s_schedule_time_out = $o_t->getScheduledTimeOut();

					$s_actual_date_in  = $o_t->getDateIn();
					$s_actual_date_out = $o_t->getDateOut();
					$s_actual_time_in  = $o_t->getTimeIn();
					$s_actual_time_out = $o_t->getTimeOut();

					$new_schedule_in  = "{$s_schedule_date_in} {$s_schedule_time_in}";
					$new_schedule_out = "{$s_schedule_date_out} {$s_schedule_time_out}";

					$new_actual_in    = "{$s_actual_date_in} {$s_actual_time_in}";
					$new_actual_out   = "{$s_actual_date_out} {$s_actual_time_out}";

					//echo "New Schedule IN : {$new_schedule_in} / New Schedule OUT : {$new_schedule_out} / New Actual IN : {$new_actual_in} / New Actual OUT : {$new_actual_out}<br/>";

					if( strtotime($new_schedule_in) > strtotime($new_actual_in) ){
						//Early IN
						$i_total_early_in += 1;
						$data['early_in'][] =  array(
							'employee_id' => $a->getEmployeeId(),
							'date' => $a->getDate()
						);

					}elseif( strtotime($new_schedule_in) < strtotime($new_actual_in) ){
						//Late					
						$i_total_late += 1;						
						$data['late'][] =  array(
							'employee_id' => $a->getEmployeeId(),
							'date' => $a->getDate()
						);
					}elseif( strtotime($new_schedule_out) > strtotime($new_actual_out) ){
						//Early OUT
						$i_total_early_out += 1;
						$data['early_out'][] =  array(
							'employee_id' => $a->getEmployeeId(),
							'date' => $a->getDate()
						);
					}
				}	
			}
			$data['total_early_in']  = $i_total_early_in;
			$data['total_late']      = $i_total_late;
			$data['total_early_out'] = $i_total_early_out;
		}

		return $data;
	}

	public function summaryWorkAgainstSchedule() {
		$data = array();

		if( strtotime($this->from_date) <= strtotime($this->to_date) ){			
			$data = G_Report_Helper::sqlSummaryWorkAgainstSchedule( $this->from_date, $this->to_date );			
		}

		return $data;
	}

	public function incorrectShift() {
		$data = array();

		if( strtotime($this->date_from) <= strtotime($this->date_to) ) {
			$ids['eids'] = explode(",", trim($this->employee_ids));
			$ids['dept_section_ids'] 	   = explode(",", trim($this->department_ids));
			$ids['employment_status_ids']  = explode(",", trim($this->employment_status_ids));

			$new_ids = array();

			foreach( $ids as $key => $value ){
				foreach( $value as $subKey => $subValue ){					
					if( trim($subValue) != "" ){
						$new_ids[$key][] = Utilities::decrypt($subValue);
					}
				}
			}

			foreach( $new_ids as $key => $value ){
				switch ($key) {					
					case 'dept_section_ids':
						$s_dept_section_ids = implode(",", $value);
						break;
					case 'employment_status_ids':
						$s_employment_status_ids = implode(",", $value);
						break;
					default:
						# code...
						break;
				}
			}
			
			$fields = array("id");
			//Fetch all employees within dept_section_ids
			$a_dept_employees    = G_Employee_Helper::sqlGetAllEmployeeByDepartmentId($s_dept_section_ids, $fields);
			//Fetch all employees within employment status ids
			$a_estatus_employees = G_Employee_Helper::sqlGetAllEmployeeByEmploymentStatusId($s_employment_status_ids, $fields);

			foreach( $new_ids['eids'] as $key => $value ){
				$eids[] = $value;
			}
			
			foreach( $a_dept_employees as $value ){
				if( !in_array($value['id'], $eids) ){
					$eids[] = $value['id'];
				}
			}

			foreach( $a_estatus_employees as $value ){
				if( !in_array($value['id'], $eids) ){
					$eids[] = $value['id'];
				}
			}
						
			//Fetch attendance within given date range and employee ids
			$fields = array("e.employee_code", "CONCAT(e.firstname, ', ', e.lastname)AS employee_name", "DATE_FORMAT(ea.date_attendance,'%M %d, %Y')AS date_attendance"); 
			$s_ids  = implode(",", $eids);
			$data   = G_Attendance_Helper::sqlEmployeesAttendanceWithIncorrectShiftByEmployeeIdAndDateRange($s_ids, $this->from_date, $this->to_date, $fields);			
			$a_incorrect_shift = array();
			foreach( $data as $d ){
				$a_incorrect_shift[trim($d['date_attendance'])][trim($d['employee_code'])][] = array("employee_name" => trim($d['employee_name']), "remarks" => "<b>" . $d['date_attendance'] . "</b> has incorrect shift");
			}

			//Duplicate schedule
			$fields   = array("g.employee_group_id","CONCAT(e.firstname, ', ', e.lastname)AS employee_name","e.employee_code","DATE_FORMAT(g.date_start,'%M %d, %Y')AS schedule_start_date");
			$a_shifts = G_Employee_Group_Schedule_Helper::sqlEmployeeSchedulesByDateRange($s_ids, $this->from_date, $this->to_date, $fields);

			//Get all with duplicates
			$prev_date = '';
			$prev_code = 0;
			$a_with_conflict_shifts = array();
			foreach( $a_shifts as $shift ){
				if( $prev_date != "" && $prev_code != "" && $prev_date === $shift['schedule_start_date'] && $prev_code === $shift['employee_group_id'] ){
					$a_with_conflict_shifts[] = $shift;
					$prev_code 				  = $shift['employee_group_id'];
				}
				$prev_date = $shift['schedule_start_date'];
				$prev_code = $shift['employee_group_id'];
			}

			//Add duplicate schedule to incorrect shift data
			foreach( $a_with_conflict_shifts as $shift ){
				$a_incorrect_shift[$shift['schedule_start_date']][$shift['employee_code']][] = array("employee_name" => trim($shift['employee_name']), "remarks" => "<b>" . $shift['schedule_start_date'] . "</b> has duplicate schedule");
			}

			$data = $a_incorrect_shift;
		}

		return $data;
	}

	public function incorrectShiftWithAddQuery($add_query) {
		$data = array();

		if( strtotime($this->date_from) <= strtotime($this->date_to) ) {
			$ids['eids'] = explode(",", trim($this->employee_ids));
			$ids['dept_section_ids'] 	   = explode(",", trim($this->department_ids));
			$ids['employment_status_ids']  = explode(",", trim($this->employment_status_ids));

			$new_ids = array();

			foreach( $ids as $key => $value ){
				foreach( $value as $subKey => $subValue ){					
					if( trim($subValue) != "" ){
						$new_ids[$key][] = Utilities::decrypt($subValue);
					}
				}
			}

			foreach( $new_ids as $key => $value ){
				switch ($key) {					
					case 'dept_section_ids':
						$s_dept_section_ids = implode(",", $value);
						break;
					case 'employment_status_ids':
						$s_employment_status_ids = implode(",", $value);
						break;
					default:
						# code...
						break;
				}
			}
			
			$fields = array("id");
			//Fetch all employees within dept_section_ids
			$a_dept_employees    = G_Employee_Helper::sqlGetAllEmployeeByDepartmentId($s_dept_section_ids, $fields);
			//Fetch all employees within employment status ids
			$a_estatus_employees = G_Employee_Helper::sqlGetAllEmployeeByEmploymentStatusId($s_employment_status_ids, $fields);

			foreach( $new_ids['eids'] as $key => $value ){
				$eids[] = $value;
			}
			
			foreach( $a_dept_employees as $value ){
				if( !in_array($value['id'], $eids) ){
					$eids[] = $value['id'];
				}
			}

			foreach( $a_estatus_employees as $value ){
				if( !in_array($value['id'], $eids) ){
					$eids[] = $value['id'];
				}
			}
						
			//Fetch attendance within given date range and employee ids
			$fields = array("e.employee_code", "CONCAT(e.lastname, ', ', e.firstname)AS employee_name", "DATE_FORMAT(ea.date_attendance,'%M %d, %Y')AS date_attendance"); 
			$s_ids  = implode(",", $eids);
			//$data   = G_Attendance_Helper::sqlEmployeesAttendanceWithIncorrectShiftByEmployeeIdAndDateRange($s_ids, $this->from_date, $this->to_date, $fields);			
			$data   = G_Attendance_Helper::sqlEmployeesAttendanceWithIncorrectShiftByEmployeeIdAndDateRangeWithAddQuery($s_ids, $this->from_date, $this->to_date, $fields, $add_query);			
			$a_incorrect_shift = array();
			foreach( $data as $d ){
				$a_incorrect_shift[trim($d['date_attendance'])][trim($d['employee_code'])][] = array("employee_name" => trim($d['employee_name']), "department_name" => trim($d['department_name']), "section_name" => trim($d['section_name']), "position" => trim($d['position']), "remarks" => "<b>" . $d['date_attendance'] . "</b> has incorrect shift");
			}

			//Duplicate schedule
			$fields   = array("g.employee_group_id","CONCAT(e.lastname, ', ', e.firstname)AS employee_name","e.employee_code","DATE_FORMAT(g.date_start,'%M %d, %Y')AS schedule_start_date");
			$a_shifts = G_Employee_Group_Schedule_Helper::sqlEmployeeSchedulesByDateRange($s_ids, $this->from_date, $this->to_date, $fields);

			//Get all with duplicates
			$prev_date = '';
			$prev_code = 0;
			$a_with_conflict_shifts = array();
			foreach( $a_shifts as $shift ){
				if( $prev_date != "" && $prev_code != "" && $prev_date === $shift['schedule_start_date'] && $prev_code === $shift['employee_group_id'] ){
					$a_with_conflict_shifts[] = $shift;
					$prev_code 				  = $shift['employee_group_id'];
				}
				$prev_date = $shift['schedule_start_date'];
				$prev_code = $shift['employee_group_id'];
			}

			//Add duplicate schedule to incorrect shift data
			foreach( $a_with_conflict_shifts as $shift ){
				$a_incorrect_shift[$shift['schedule_start_date']][$shift['employee_code']][] = array("employee_name" => trim($shift['employee_name']), "department_name" => trim($d['department_name']), "section_name" => trim($d['section_name']), "position" => trim($d['position']), "remarks" => "<b>" . $shift['schedule_start_date'] . "</b> has duplicate schedule");
			}

			$data = $a_incorrect_shift;
		}

		return $data;
	}	

	public function allIncorrectShift() {
		$data = array();
		if( strtotime($this->date_from) <= strtotime($this->date_to) ) {
			$fields = array("e.employee_code, CONCAT(e.firstname, ', ', e.lastname)AS employee_name", "DATE_FORMAT(ea.date_attendance,'%M %d, %Y')AS date_attendance"); 			
			$data = G_Attendance_Helper::sqlAllWithIncorrectShiftByEmployeeIdAndDateRange($this->from_date, $this->to_date, $fields);		
			$a_incorrect_shift = array();

			foreach( $data as $d ){
				$a_incorrect_shift[trim($d['date_attendance'])][trim($d['employee_code'])][] = array("employee_name" => trim($d['employee_name']), "remarks" => "<b>" . $d['date_attendance'] . "</b> has incorrect shift");
			}

			//Duplicate	schedule
			$fields   = array("g.employee_group_id","CONCAT(e.firstname, ', ', e.lastname)AS employee_name","e.employee_code","DATE_FORMAT(g.date_start,'%M %d, %Y')AS schedule_start_date");
			$a_shifts = G_Employee_Group_Schedule_Helper::sqlAllEmployeeSchedulesByDateRange($this->from_date, $this->to_date, $fields);

			//Get all with duplicates
			$prev_date = '';
			$prev_code = 0;
			$a_with_conflict_shifts = array();
			
			foreach( $a_shifts as $shift ){
				if( $prev_date != "" && $prev_code != "" && $prev_date === $shift['schedule_start_date'] && $prev_code === $shift['employee_group_id'] ){
					$a_with_conflict_shifts[] = $shift;
					$prev_code 				  = $shift['employee_group_id'];
				}
				$prev_date = $shift['schedule_start_date'];
				$prev_code = $shift['employee_group_id'];
			}

			//Add duplicate schedule to incorrect shift data
			foreach( $a_with_conflict_shifts as $shift ){
				$a_incorrect_shift[$shift['schedule_start_date']][$shift['employee_code']][] = array("employee_name" => trim($shift['employee_name']), "remarks" => "<b>" . $shift['schedule_start_date'] . "</b> has duplicate schedule");
			}

			$data = $a_incorrect_shift;

		}
		return $data;
	}	
	public function allIncorrectShiftWithAddQuery($add_query) {
		$data = array();
		if( strtotime($this->date_from) <= strtotime($this->date_to) ) {
			$fields = array("e.employee_code, CONCAT(e.lastname, ', ', e.firstname)AS employee_name", "DATE_FORMAT(ea.date_attendance,'%M %d, %Y')AS date_attendance"); 			
			//$data = G_Attendance_Helper::sqlAllWithIncorrectShiftByEmployeeIdAndDateRange($this->from_date, $this->to_date, $fields);		
			$data = G_Attendance_Helper::sqlAllWithIncorrectShiftByEmployeeIdAndDateRangeWithAddQuery($this->from_date, $this->to_date, $fields, $add_query);		

			$a_incorrect_shift = array();
			foreach( $data as $d ){
				$a_incorrect_shift[trim($d['date_attendance'])][trim($d['employee_code'])][] = array("employee_name" => trim($d['employee_name']), "department_name" => trim($d['department_name']), "section_name" => trim($d['section_name']), "position" => trim($d['position']), "remarks" => "<b>" . $d['date_attendance'] . "</b> has incorrect shift");
			}

			//Duplicate	schedule
			$fields   = array("g.employee_group_id","CONCAT(e.lastname, ', ', e.firstname)AS employee_name","e.employee_code","DATE_FORMAT(g.date_start,'%M %d, %Y')AS schedule_start_date");
			$a_shifts = G_Employee_Group_Schedule_Helper::sqlAllEmployeeSchedulesByDateRange($this->from_date, $this->to_date, $fields);

			//Get all with duplicates
			$prev_date = '';
			$prev_code = 0;
			$a_with_conflict_shifts = array();
			
			foreach( $a_shifts as $shift ){
				if( $prev_date != "" && $prev_code != "" && $prev_date === $shift['schedule_start_date'] && $prev_code === $shift['employee_group_id'] ){
					$a_with_conflict_shifts[] = $shift;
					$prev_code 				  = $shift['employee_group_id'];
				}
				$prev_date = $shift['schedule_start_date'];
				$prev_code = $shift['employee_group_id'];
			}

			//Add duplicate schedule to incorrect shift data
			foreach( $a_with_conflict_shifts as $shift ){
				$a_incorrect_shift[$shift['schedule_start_date']][$shift['employee_code']][] = array("employee_name" => trim($shift['employee_name']), "department_name" => trim($d['department_name']), "section_name" => trim($d['section_name']), "position" => trim($d['position']), "remarks" => "<b>" . $shift['schedule_start_date'] . "</b> has duplicate schedule");
			}

			$data = $a_incorrect_shift;
		}
		return $data;
	}	

	public function getEmployeesLoanData( $query = array(), $add_query = array(), $cutoff_period = null )
  	{
	    $loans_data = array();
	    $loans_data = G_Report_Helper::getEmployeesLoanData($query, $add_query);
	    $loans_group_data = array();
	    $loans_ids = array();

	    foreach( $loans_data as $loan ){
	      $loans_payment_data = G_Employee_Loan_Payment_Schedule_Helper::sqlGetDataByLoanId($loan['loan_pkid'], '', $cutoff_period);

	      $loans_group_data[$loan['employee_pkid']]['employee_details'] = array(
	        'employee_code' => $loan['employee_code'],
	        'lastname' => $loan['lastname'],
	        'firstname' => $loan['firstname'],
	        'middlename' => $loan['middlename'],
	        'extension_name' => $loan['extension_name'],
	        'sss_number' => $loan['sss_number'],
	        'philhealth_number' => $loan['philhealth_number'],
	        'pagibig_number' => $loan['pagibig_number'],
	        'department_name' => $loan['department_name'],
	        'section_name' => $loan['section_name'],
	        'position' => $loan['position'],
	        'employee_status' => $loan['employee_status'],
	        'birthdate' => $loan['birthdate']

	      );

	      $loans_group_data[$loan['employee_pkid']]['loan_header'][$loan['loan_pkid']]['header'] = array(
	        'loan_title' => $loan['loan_title'],
	        'loan_amount' => $loan['loan_amount'],
	        'months_to_pay' => $loan['months_to_pay'],
	        'deduction_type' => $loan['deduction_type'],
	        'start_date' => $loan['start_date'],
	        'end_date' => $loan['end_date'],
	        'total_amount_to_pay' => $loan['total_amount_to_pay'],
	        'amount_paid' => $loan['amount_paid']
	      );

	      $loan_ids[] = $loan['loan_pkid'];
	    }
	    
	    //Fetch all loans payment in loans_ids array    
	    $loans_payment_schedule   = G_Employee_Loan_Payment_Schedule_Helper::sqlGetDataByLoanIds($loan_ids, '', $cutoff_period);
	    $loans_payment_group_data = array();
	    $valid_loan_payment_date  = array();
	    foreach( $loans_payment_schedule as $ps ){
	      $date_key = trim($ps['loan_payment_scheduled_date']);
	      if( $date_key != '' ){
	        $loans_payment_group_data[$ps['loan_id']]['loans_payment'][$date_key] = $ps;      
	        $valid_loan_payment_date[] = $date_key;
	      }     
	    }

	    //Merge with group data
	    foreach( $loans_group_data as $key => $group_data ){
	      foreach( $group_data['loan_header'] as $subKey => $subData ){ 
	        $loans_group_data[$key]['loan_header'][$subKey]['payment'] = $loans_payment_group_data[$subKey]['loans_payment'];
	      }     
	    }

	    $unique_date = array_unique($valid_loan_payment_date);
	    $loans_group_data['valid_date_payment'] = $unique_date;   
	    return $loans_group_data;
  	}

	public function getEmployeesLoanData_depre( $query = array(), $add_query = array() )
	{
		$loans_data = array();
		$loans_data = G_Report_Helper::getEmployeesLoanData($query, $add_query);
		$loans_group_data = array();
		$loans_ids = array();
		foreach( $loans_data as $loan ){
			$loans_payment_data = G_Employee_Loan_Payment_Schedule_Helper::sqlGetDataByLoanId($loan['loan_pkid']);
			$loans_group_data[$loan['employee_pkid']]['employee_details'] = array(
				'employee_code' => $loan['employee_code'],
				'lastname' => $loan['lastname'],
				'firstname' => $loan['firstname'],
				'department_name' => $loan['department_name'],
				'position' => $loan['position'],
				'employee_status' => $loan['employee_status']		
			);
			$loans_group_data[$loan['employee_pkid']]['loan_header'][$loan['loan_pkid']]['header'] = array(
				'loan_title' => $loan['loan_title'],
				'loan_amount' => $loan['loan_amount'],
				'months_to_pay' => $loan['months_to_pay'],
				'deduction_type' => $loan['deduction_type'],
				'start_date' => $loan['start_date'],
				'end_date' => $loan['end_date'],
				'total_amount_to_pay' => $loan['total_amount_to_pay'],
				'amount_paid' => $loan['amount_paid']
			);

			$loan_ids[] = $loan['loan_pkid'];
		}
		
		//Fetch all loans payment in loans_ids array		
		$loans_payment_schedule   = G_Employee_Loan_Payment_Schedule_Helper::sqlGetDataByLoanIds($loan_ids);
		$loans_payment_group_data = array();
		$valid_loan_payment_date  = array();
		foreach( $loans_payment_schedule as $ps ){
			$date_key = trim($ps['loan_payment_scheduled_date']);
			if( $date_key != '' ){
				$loans_payment_group_data[$ps['loan_id']]['loans_payment'][$date_key] = $ps;			
				$valid_loan_payment_date[] = $date_key;
			}			
		}

		//Merge with group data
		foreach( $loans_group_data as $key => $group_data ){
			foreach( $group_data['loan_header'] as $subKey => $subData ){	
				$loans_group_data[$key]['loan_header'][$subKey]['payment'] = $loans_payment_group_data[$subKey]['loans_payment'];
			}			
		}

		$unique_date = array_unique($valid_loan_payment_date);
		$loans_group_data['valid_date_payment'] = $unique_date;		
		return $loans_group_data;
	}

	/**
	* Get other earnings
	*
	* @param string payroll period
	* @param array earnings
	* @return array
	*/
	public function getOtherEarningsReport( $payroll_period = '', $earnings = array() ) {
		$data = array();
		if( !empty($payroll_period) ){			
			$cutoff = explode("/", $payroll_period);
			$fields = array('id');
			$period = G_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($cutoff[0], $cutoff[1],$fields);

			if( !empty($period) ){				
				if( !empty($earnings) ){					
					$str_earnings = implode(",", $earnings);					
				}
				$fields = array('object_description','title','amount','is_taxable');
				$data = G_Report_Helper::sqlEmployeeEarningsByCutoffPeriodId($period['id'], $str_earnings, $fields);
			}
		}
		return $data;
	}

	/**
     * Get annualized tax per employee
     * 
     *@param data array
	*/
	public function getEmployeeAnnualizedTax( $data = array() ) {
		$data = array();

		if( isset($data['year']) && isset($data['e_employee_id']) ){
			$employee_id =  Utilities::decrypt($data['e_employee_id']);
			$year        = $data['year'];

			$atax = new G_Annualize_Tax();
			//$data = $atax->

		}else{

		}
	}

	/**
    * Generate alphalist report
    *
    *@param year int
    *@param options array
    *@return array
	*/
	public function alphaListReport( $year, $options ) {
		$return = array();
		if( $year > 0 ){			

			$payslips         = G_Payslip_Finder::findAllByYearWithOptions($year, $options);

			$weekly_payslips  =  G_Weekly_Payslip_Finder::findAllByYearWithOptions($year, $options);		
			
	        $current_employee = G_Employee_Helper::getCurrentEmployeeByYear($year);	


	        if(!empty($payslips) && !empty($weekly_payslips)){
	        	$all_payslips = array_merge($payslips,$weekly_payslips);
	        }
	        elseif(!empty($payslips) && empty($weekly_payslips)){
	        	$all_payslips = $payslips;
	        }
	        elseif(empty($payslips) && !empty($weekly_payslips)){
	        	$all_payslips = $weekly_payslips;
	        }
 			 
	   // echo "<pre>";
	   // var_dump($payslips);
	   // echo "</pre>";
			//$payslips_s = G_Payslip_Finder::findAllByYearAndEmployeeId($year,61);
			//Utilities::displayArray($payslips);
			//exit;			

			//Temp Data
			$tmp_migrated = G_Migrate_Data_Helper::getAllDataByYear($year);			

			$migrated     = array();			
			foreach( $tmp_migrated as $data ){
				$migrated[$data['employee_id']][$data['field']] = $data['amount'];
			}

			//Annualized Tax
			$atax = G_Employee_Annualize_Tax_Helper::getAllAnnualizedTaxByYear($year);		

			$e_atax = array();
			foreach( $atax as $tax ){
				
				$e_atax[$tax['employee_id']]['tax_withheld_payroll'] = $tax['tax_withheld_payroll'];
				$e_atax[$tax['employee_id']]['tax_refund_payable']   = $tax['tax_refund_payable'];
				$e_atax[$tax['employee_id']]['tax_due']   = $tax['tax_due'];
				$e_atax[$tax['employee_id']]['hmo_premium']   = $tax['hmo_premium'];
				$e_atax[$tax['employee_id']]['hmo_premium_taxable']   = $tax['hmo_premium_taxable'];
				$e_atax[$tax['employee_id']]['hmo_premium_nontaxable']   = $tax['hmo_premium_nontaxable'];
					$e_atax[$tax['employee_id']]['sss_maternity_differential_taxable']   = $tax['sss_maternity_differential_taxable'];
					$e_atax[$tax['employee_id']]['sss_maternity_differential_nontaxable']   = $tax['sss_maternity_differential_nontaxable'];
				$e_atax[$tax['employee_id']]['other_deductions_earnings_taxable']   = $tax['other_deductions_earnings_taxable'];
			$e_atax[$tax['employee_id']]['other_deductions_earnings_nontaxable']   = $tax['other_deductions_earnings_nontaxable'];
				$e_atax[$tax['employee_id']]['taxable_compensation_previous_employer']   = $tax['taxable_compensation_previous_employer'];
					$e_atax[$tax['employee_id']]['tax_withheld_previous_employer']   = $tax['tax_withheld_previous_employer'];


			}
			

						
			if( $all_payslips ){
				$eid = '';				
				foreach( $all_payslips as $payslip ){
					
				
					if(in_array($payslip->getEmployeeId(), $current_employee)) {



						if( $eid <> $payslip->getEmployeeId() ){						
							$e = G_Employee_Helper::getEmployeeDataById($payslip->getEmployeeId(), $year);
							$eid = $payslip->getEmployeeId();

							if( $e ){
								$year_hired_date  = date("Y",strtotime($e['hired_date']));
								if( $year_hired_date > $year ){
									continue;
								}
							}

							// echo "</pre>";
							// var_dump($eid);
							// echo "</pre>";
							// echo "<br>";
							//Temp Data								
							if( $e ) {


								$return[$payslip->getEmployeeId()]['13th_month'] += $migrated[$payslip->getEmployeeId()]['13th_month'];						
								$return[$payslip->getEmployeeId()]['nd_pay']     += $migrated[$payslip->getEmployeeId()]['sum_nd_pay'];					
								$return[$payslip->getEmployeeId()]['basic_pay']  += $migrated[$payslip->getEmployeeId()]['basic_pay'] + $migrated[$payslip->getEmployeeId()]['sum_bl_pay'] + $migrated[$payslip->getEmployeeId()]['sum_fl_pay'] + $migrated[$payslip->getEmployeeId()]['sum_pt_pay'] + $migrated[$payslip->getEmployeeId()]['paid_leaves'];	
								$return[$payslip->getEmployeeId()]['sss'] 		 += $migrated[$payslip->getEmployeeId()]['sum_sss'];	
								$return[$payslip->getEmployeeId()]['philhealth'] += $migrated[$payslip->getEmployeeId()]['sum_philhealth'];	
								$return[$payslip->getEmployeeId()]['pagibig']    += $migrated[$payslip->getEmployeeId()]['sum_pagibig'];	
								$return[$payslip->getEmployeeId()]['taxwheld']   += $e_atax[$payslip->getEmployeeId()]['tax_due'];
								// 
								$return[$payslip->getEmployeeId()]['tax_withheld_payroll']   += $e_atax[$payslip->getEmployeeId()]['tax_withheld_payroll'];
								// 
								$return[$payslip->getEmployeeId()]['withheld_tax']   += $migrated[$payslip->getEmployeeId()]['withheld_tax'];
								$return[$payslip->getEmployeeId()]['hmo_premium']  += $e_atax[$payslip->getEmployeeId()]['hmo_premium'];
								// new
								$return[$payslip->getEmployeeId()]['hmo_premium_taxable']  += $e_atax[$payslip->getEmployeeId()]['hmo_premium_taxable'];
								$return[$payslip->getEmployeeId()]['hmo_premium_nontaxable']  += $e_atax[$payslip->getEmployeeId()]['hmo_premium_nontaxable'];
								$return[$payslip->getEmployeeId()]['sss_maternity_differential_taxable']  += $e_atax[$payslip->getEmployeeId()]['sss_maternity_differential_taxable'];
								$return[$payslip->getEmployeeId()]['sss_maternity_differential_nontaxable']  += $e_atax[$payslip->getEmployeeId()]['sss_maternity_differential_nontaxable'];
								$return[$payslip->getEmployeeId()]['other_deductions_earnings_taxable']  += $e_atax[$payslip->getEmployeeId()]['other_deductions_earnings_taxable'];
								$return[$payslip->getEmployeeId()]['other_deductions_earnings_nontaxable']  += $e_atax[$payslip->getEmployeeId()]['other_deductions_earnings_nontaxable'];
								$return[$payslip->getEmployeeId()]['taxable_compensation_previous_employer']  += $e_atax[$payslip->getEmployeeId()]['taxable_compensation_previous_employer'];
								$return[$payslip->getEmployeeId()]['tax_withheld_previous_employer']  += $e_atax[$payslip->getEmployeeId()]['tax_withheld_previous_employer'];

								// new
								$return[$payslip->getEmployeeId()]['paid_holiday'] += $migrated[$payslip->getEmployeeId()]['sum_paid_holiday'] + $migrated[$payslip->getEmployeeId()]['sum_holiday_pay'];	
								//$return[$payslip->getEmployeeId()]['grosspay']   += $migrated[$payslip->getEmployeeId()]['gross_pay'];	
								$return[$payslip->getEmployeeId()]['transpo_allowance']   += $migrated[$payslip->getEmployeeId()]['transportation_allowance'];	
								$return[$payslip->getEmployeeId()]['meal_allowance']   += $migrated[$payslip->getEmployeeId()]['meal_allowance'];	
								$return[$payslip->getEmployeeId()]['rice_allowance']   += $migrated[$payslip->getEmployeeId()]['rice_allowance'];	
								$return[$payslip->getEmployeeId()]['position_allowance']   += $migrated[$payslip->getEmployeeId()]['position_allowance'];	
								//$return[$payslip->getEmployeeId()]['non_taxable_leave_converted']   += $migrated[$payslip->getEmployeeId()]['paid_leaves'];	
								$return[$payslip->getEmployeeId()]['grosspay']   += $migrated[$payslip->getEmployeeId()]['gross_pay'];	
								//$return[$payslip->getEmployeeId()]['personal_exemption'] += $migrated[$payslip->getEmployeeId()]['personal_exemption'];
								$return[$payslip->getEmployeeId()]['rotpay'] += $migrated[$payslip->getEmployeeId()]['sum_sunday_pay'] + $migrated[$payslip->getEmployeeId()]['sum_special_holiday_pay'] + $migrated[$payslip->getEmployeeId()]['sum_pholiday_sunday_pay'] + $migrated[$payslip->getEmployeeId()]['rot_pay'];

								//13thmonth					
								$total_yearly_bonus = 0;
								$return[$payslip->getEmployeeId()]['13th_month'] = 0;
								$yearly_bonus = G_Yearly_Bonus_Release_Date_Helper::getEmployeeTotalBonusByYear($payslip->getEmployeeId(), $year);
								

								if( !empty($yearly_bonus) ){
									$total_yearly_bonus = $yearly_bonus['total_bonus'];
								}
								$return[$payslip->getEmployeeId()]['13th_month']   += $total_yearly_bonus;

								$year_hired_date  = date("Y",strtotime($e['hired_date']));
								$month_hired_date = date("m",strtotime($e['hired_date'])); 
								$day_hired_date   = date("d",strtotime($e['hired_date'])); 

								//if( $month_hired_date > 1 && $year_hired_date == $year ){
								if( $year_hired_date == $year ){
									$return[$payslip->getEmployeeId()]['start_period'] = "{$month_hired_date}/{$day_hired_date}";							
								}else{
									$return[$payslip->getEmployeeId()]['start_period'] = "01/01";
								}

								$return[$payslip->getEmployeeId()]['end_period'] = '12/31';

								if( $e['endo_date'] != '0000-00-00') {
									$year_hired_date  = date("Y",strtotime($e['endo_date']));
									$month_hired_date = date("m",strtotime($e['endo_date'])); 
									$day_hired_date   = date("d",strtotime($e['endo_date'])); 
									$return[$payslip->getEmployeeId()]['end_period']   = "{$month_hired_date}/{$day_hired_date}";		
								}

								if( $e['resignation_date'] != '0000-00-00') {
									$year_hired_date  = date("Y",strtotime($e['resignation_date']));
									$month_hired_date = date("m",strtotime($e['resignation_date'])); 
									$day_hired_date   = date("d",strtotime($e['resignation_date']));
									$return[$payslip->getEmployeeId()]['end_period']   = "{$month_hired_date}/{$day_hired_date}";	
								}

								if( $e['terminated_date'] != '0000-00-00') {
									$year_hired_date  = date("Y",strtotime($e['terminated_date']));
									$month_hired_date = date("m",strtotime($e['terminated_date'])); 
									$day_hired_date   = date("d",strtotime($e['terminated_date']));
									$return[$payslip->getEmployeeId()]['end_period']   = "{$month_hired_date}/{$day_hired_date}";	
								}

						    }
						}
													$e_atax[$payslip->getEmployeeId()]['tax_due'] = 0;
													$e_atax[$payslip->getEmployeeId()]['tax_withheld_payroll'] = 0;

						if( $e ){		
							// if( $payslip->getStartDate() == '2019-03-26'){
								
							// 		echo $e['lastname'] .",". $e['firstname'] ." == "; 
							// echo $payslip->getStartDate() . " == " . $payslip->getGrossPay() . "<br>";
							// }
						
							$return[$payslip->getEmployeeId()]['employee_id'] = $e['employee_code'];							
							$return[$payslip->getEmployeeId()]['employee_pkid'] = $e['employee_pkid'];
							$return[$payslip->getEmployeeId()]['lastname']    = $e['lastname'];
							$return[$payslip->getEmployeeId()]['firstname']   = $e['firstname'];
							$return[$payslip->getEmployeeId()]['middlename']  = $e['middlename'];
							$return[$payslip->getEmployeeId()]['resignation_date'] = $e['resignation_date'];
							$return[$payslip->getEmployeeId()]['endo_date']   = $e['endo_date'];
							$return[$payslip->getEmployeeId()]['terminated_date'] = $e['terminated_date'];
							$return[$payslip->getEmployeeId()]['hired_date']  = $e['hired_date'];						
							$return[$payslip->getEmployeeId()]['year_working_days'] = $e['year_working_days'];						
							$return[$payslip->getEmployeeId()]['present_salary'] = $e['present_salary'];	
							$return[$payslip->getEmployeeId()]['birthdate']   = date("m/d/Y", strtotime($e['birthdate']));						

							$return[$payslip->getEmployeeId()]['address']     = $e['address'];
							$return[$payslip->getEmployeeId()]['address_zipcode'] = $e['zip_code'];

							$return[$payslip->getEmployeeId()]['tin_number']  = $e['tin_number'];

							$return[$payslip->getEmployeeId()]['philhealth_number'] = $e['philhealth_number'];
							$return[$payslip->getEmployeeId()]['pagibig_number']    = $e['pagibig_number'];
							$return[$payslip->getEmployeeId()]['sss_number']        = $e['sss_number'];

							$return[$payslip->getEmployeeId()]['employee_status'] = $e['employee_status'];
							$return[$payslip->getEmployeeId()]['employment_status'] = $e['employment_status'];
							$return[$payslip->getEmployeeId()]['department_name'] = $e['department_name'];
							$return[$payslip->getEmployeeId()]['section_name'] = $e['section_name'];
							$return[$payslip->getEmployeeId()]['civil_status'] = $e['marital_status'];
							$return[$payslip->getEmployeeId()]['number_dependent'] = $e['number_dependent'];

							$total_dependents   = $e['number_dependent'];
							//$return[$payslip->getEmployeeId()]['personal_exemption'] = 0;
							//if( $total_dependents > 0 ){							
								/*
								 * Note: Personal excemption is remove in 2018 new revise government tax computation
								*/
								/*$net_taxable_calculator   = new Net_Taxable_Calculator();
								$personal_exemption = $net_taxable_calculator->computeAdditionalExemptions($e['number_dependent']);
								$personal_exemption = 0;
								$return[$payslip->getEmployeeId()]['personal_exemption'] = $personal_exemption;	*/							
							//}

							$personal_exemption = 0;
							$return[$payslip->getEmployeeId()]['personal_exemption'] = $personal_exemption;								

							$return[$payslip->getEmployeeId()]['basic_pay']  += $payslip->getBasicPay();
							$return[$payslip->getEmployeeId()]['sss'] 		 += $payslip->getSSS();
							$return[$payslip->getEmployeeId()]['philhealth'] += $payslip->getPhilhealth();
							$return[$payslip->getEmployeeId()]['pagibig']    += $payslip->getPagibig();
							//$return[$payslip->getEmployeeId()]['taxwheld']   += $payslip->getWithheldTax();					
							$return[$payslip->getEmployeeId()]['grosspay']   += $payslip->getGrossPay();						
							$return[$payslip->getEmployeeId()]['withheld_tax']   += $payslip->getWithheldTax();
					// echo "<br>";
					// echo $payslip->getStartDate();
	
												
					// 		var_dump($payslip->getGrossPay());
					// 		echo "<hr>";
							//Labels
							$labels = $payslip->getLabels();	

							foreach( $labels as $l ){
								switch (strtolower($l->getVariable())) {
									case 'absent_amount':
										$return[$payslip->getEmployeeId()]['absences'] += $l->getValue();
										break;
									case 'suspended_amount':
										$return[$payslip->getEmployeeId()]['absences'] += $l->getValue();
										break;
									case 'undertime_amount':
										$return[$payslip->getEmployeeId()]['undertime'] += $l->getValue();
										break;
									case 'late_amount':
										$return[$payslip->getEmployeeId()]['tardiness'] += $l->getValue();
										break;	
									case 'restday_amount':								
										$return[$payslip->getEmployeeId()]['rotpay'] += $l->getValue();
										break;
									case 'salary_type':								
										$return[$payslip->getEmployeeId()]['salary_type'] = $l->getValue();
										break;	
									case 'daily_rate':								
										$return[$payslip->getEmployeeId()]['daily_rate'] = $l->getValue();
										break;									
									/*case 'regular_ns_amount':
										$return[$payslip->getEmployeeId()]['nd_pay'] += $l->getValue();
										break;*/
									/*case 'regular_ns_ot_hours':
										break;*/	
									/*case 'regular_ot_amount':
										$return[$payslip->getEmployeeId()]['rotpay'] += $l->getValue();		
										break;*/
									case (strtolower($l->getVariable()) == 'holiday_legal_amount' || strtolower($l->getVariable()) == 'holiday_special_amount'):
										$return[$payslip->getEmployeeId()]['paid_holiday'] += $l->getValue();		
										break;			
									/*case 'tax_refund':
										$return[$payslip->getEmployeeId()]['taxwheld']  = $payslip->getWithheldTax() + $migrated[$payslip->getEmployeeId()]['taxwheld'] - $l->getValue();		
										break;*/
									default:
										# code...
										break;
								}

								if( stripos(strtolower($l->getLabel()), 'ot amount') !== false ){
									//echo $l->getLabel() . "/" . $l->getValue() . "<br />"; 	
									$return[$payslip->getEmployeeId()]['rotpay'] += $l->getValue();
								}	

								if( stripos(strtolower($l->getLabel()), 'ns amount') !== false ){
									//echo $l->getLabel() . "/" . $l->getValue() . "<br />"; 	
									$return[$payslip->getEmployeeId()]['nd_pay'] += $l->getValue();
								}						
							}


						 

							//Earnings

							$earnings = $payslip->getEarnings();

							foreach( $earnings as $ea ){
								switch (strtolower($ea->getVariable())) {
									case 'total_ceta_amount':
										$return[$payslip->getEmployeeId()]['ctpa_sea'] += $ea->getAmount();
										break;
									default:
										# code...
										break;
								}
							}

							//Other Earnings
							$other_earnings = $payslip->getOtherEarnings();
						
							
							foreach( $other_earnings as $oe ){ 

								$temp_string = strtolower($oe->getVariable());

								if (strpos($temp_string, 'special transpo') !== false) {
								    $temp_string = "special transpo";

								}

								switch ($temp_string) {
								 
								 case 'special transpo' :
								 $return[$payslip->getEmployeeId()]['special_transpo'] += $oe->getAmount();
										break;
									case 'adjustment':
										$return[$payslip->getEmployeeId()]['adjustment'] += $oe->getAmount();
										break;
									case 'bonus':
										if($oe->getTaxType() == 2) {
											$return[$payslip->getEmployeeId()]['bonus'] += $oe->getAmount();
										}
										if($oe->getTaxType() == 1) {
											$return[$payslip->getEmployeeId()]['bonus_tax'] += $oe->getAmount();
										}

										break;
									case 'service award':
										if( $oe->getTaxType() == 1 ){
											$return[$payslip->getEmployeeId()]['service_award_tax'] += $oe->getAmount();
										}else{
											$return[$payslip->getEmployeeId()]['service_award'] += $oe->getAmount();
										}									
										break;
									case 'non_taxable_converted_leave':
										$return[$payslip->getEmployeeId()]['non_taxable_leave_converted'] += $oe->getAmount();
										break;
									case 'taxable_converted_leave':
										$return[$payslip->getEmployeeId()]['taxable_leave_converted'] += $oe->getAmount();
										break;
									/*case 'rice allowance':
										$return[$payslip->getEmployeeId()]['rice_allowance'] += $oe->getAmount();
										break;*/
									case 'meal allowance':
										$return[$payslip->getEmployeeId()]['meal_allowance'] += $oe->getAmount();
										break;
									case 'ot allowance': 
										$return[$payslip->getEmployeeId()]['ot_allowance'] += $oe->getAmount();
										break;
									case 'transpo allowance':
										$return[$payslip->getEmployeeId()]['transpo_allowance'] += $oe->getAmount();
										break;
									case 'non taxable converted leave':
										$return[$payslip->getEmployeeId()]['non_taxable_leave_converted']   += $oe->getAmount();	
										break;
									case 'taxable converted leave':
										$return[$payslip->getEmployeeId()]['taxable_leave_converted']   += $oe->getAmount();	
										break;
	                                /*case 'rice allowance':
	                                    $return[$payslip->getEmployeeId()]['rice_allowance'] += $oe->getAmount();
	                                    break;*/									
	                                case 'meal allowance':
	                                    $return[$payslip->getEmployeeId()]['meal_allowance'] += $oe->getAmount();
	                                    break;
	                                case 'ot allowance': 
	                                    $return[$payslip->getEmployeeId()]['ot_allowance'] += $oe->getAmount();
	                                    break;
	                                case 'transpo allowance':
	                                    $return[$payslip->getEmployeeId()]['transpo_allowance'] += $oe->getAmount();
	                                    break;
	                                case 'non taxable converted leave':
	                                    $return[$payslip->getEmployeeId()]['non_taxable_leave_converted']   += $oe->getAmount();    
	                                    break;
	                                case 'taxable converted leave':
	                                    $return[$payslip->getEmployeeId()]['taxable_leave_converted']   += $oe->getAmount();    
	                                    break;									
									default:
										$is_other_earnings = true;
										if( stripos(strtolower($oe->getVariable()), 'position allowance') !== false ){								
											$return[$payslip->getEmployeeId()]['position_allowance'] += $oe->getAmount();
											$is_other_earnings = false;
										}

										if( stripos(strtolower($oe->getVariable()), 'ctpa/sea') !== false ){
											$return[$payslip->getEmployeeId()]['ctpa_sea'] += $oe->getAmount();
											$is_other_earnings = false;
										}

										if( stripos(strtolower($oe->getVariable()), 'rice allowance') !== false ){															
											$return[$payslip->getEmployeeId()]['rice_allowance'] += $oe->getAmount();
											$is_other_earnings = false;
										}

										if( $is_other_earnings ){
											$return[$payslip->getEmployeeId()]['other_earnings'] += $oe->getAmount();
										}
										break;
								}

							}//

							//Other Deductions
							$other_deductions = $payslip->getOtherDeductions();					

							/*echo '<pre>';
							print_r($other_deductions);
							echo '</pre>';*/

							foreach( $other_deductions as $ods ){
								switch (strtolower($ods->getVariable())) {
	                                case 'union_dues':
	                                    $return[$payslip->getEmployeeId()]['union_dues'] += $ods->getAmount();   
	                                    break;							
									default:
										# code...
										break;
								}
							}
							//echo '<hr />';
						}

					} // in_array end
						
				}	

				//Annualized
				$atax   = array();
				$fields = array('employee_id','tax_due');
				$annualized_tax = G_Employee_Annualize_Tax_Helper::getAllDataByYear($year, $fields);
				foreach( $annualized_tax as $data ){
					if( isset($return[$data['employee_id']]) ){
						$return[$data['employee_id']]['taxwheld'] -= $data['tax_due'];
					}					
				}
			}
		}


		return $return;
	}

	public function alphaListReportSummarized( $year, $options ) {
		

		if( $year > 0 ){

			$selected_year = $year;
			if( $selected_year == '' || $selected_year <= 0 ){
				$selected_year = date("Y");
			}		

		
		  	$selected_year = $selected_year;
		  	$cutoff_periods = G_Cutoff_Period_Finder::findAllByYear($selected_year);

		  	$payslips = G_Payslip_Finder::findAllByYearWithOptions($year, $options);		
		  		
			$current_employee = G_Employee_Helper::getCurrentEmployeeByYear($year);



	  		//Temp Data				
	  		
	  		$array_merge_data = array();
	  		
	  			
	  			foreach ($cutoff_periods as $key => $cutoff_period_val) {

					$cutoff_date = $cutoff_period_val->getStartDate(); 

					$cutoff_number = $cutoff_period_val->getCutoffNumber();
					$payslipsCutoff = G_Payslip_Finder::findAllByCutOffWithOptions($cutoff_date, $options);
					$payslipsCutoffTest = G_Payslip_Finder::findAllByCutOffWithOptionsByEmployeeId($cutoff_date, $options,$fields,$current_employee);

				// foreach ($payslipsCutoffTest as $test) {

				// 	echo $test->getEmployeeId(). " -> ";
					
				// 	echo $test->getStartDate() . "<br>";
							
				
				// }


			   	if( $payslipsCutoff ){
						$eid = '';	
						foreach( $payslipsCutoff as $payslip ){
								
								if(in_array($payslip->getEmployeeId(), $current_employee)) {

									if( $eid <> $payslip->getEmployeeId() ){	

										$e = G_Employee_Helper::getEmployeeDataById($payslip->getEmployeeId(), $year);
										$eid = $payslip->getEmployeeId();
										
										if( $e ){
											$year_hired_date  = date("Y",strtotime($e['hired_date']));
											if( $year_hired_date > $year ){

												continue;
											}
										}
										

									
										//Other Earnings
										$other_earnings = $payslip->getOtherEarnings();

										foreach( $other_earnings as $oe ){ 
											$temp_string = strtolower($oe->getVariable());

											if (strpos($temp_string, 'special transpo') !== false) {
											    $temp_string = "special transpo";

											}


										switch ($temp_string) {
								 
									
									case 'service award':
										if( $oe->getTaxType() == 1 ){
											
											$temp_service_award_tax += $oe->getAmount();
										}else{
											$temp_service_award += $oe->getAmount();
											
										}									
										break;
									
									case 'taxable converted leave':
										$temp_taxable_leave_converted  += $oe->getAmount();
										
										break;
	                               							
									default:
										$is_other_earnings = true;
										
										break;
								}



										}
										// temp 
										

										if( $e ){

											
											
											$temp_sss 		 += $payslip->getSSS();
											$temp_philhealth += $payslip->getPhilhealth();
											$temp_pagibig    += $payslip->getPagibig();
											$temp_gross_pay   += $payslip->getGrossPay();
											$temp_withheld_tax += $payslip->getWithheldTax();

											// echo $temp_gross_pay . "+=" . $payslip->getGrossPay() ." = " .$temp_gross_pay."<br>";

											$taxable = $payslip->getTaxable();
											$nontaxable = $payslip->getNonTaxable();
											// Guide
											// const TAXABLE = 1;
    										// const NON_TAXABLE = 2;
											// $earnings = $payslip->getTotalEarnings();
											$other_earnings = $payslip->getOtherEarnings();

											foreach ($other_earnings as $key => $value) {				
												if($value->isTaxable()){
													
													$store_other_earnings_taxable += $value->getAmount();												
												}
												else{													
													$store_other_earnings_nontaxable += $value->getAmount();
													
												}
											}
											
											$sub_total_taxable = $taxable; 
											$sub_total_nontaxable = $nontaxable;

											$temp_taxable += $sub_total_taxable;
											$temp_nontaxable += $sub_total_nontaxable;

										}	


									}

									
								}
						
								
						}
					
						
						$data['cutoff_number'] = $cutoff_number;
						$data['cutoff_date'] = $cutoff_date;
						$data['total_gross_pay'] = $temp_gross_pay;
						$data['total_taxable'] = $temp_taxable;
						$data['total_nontaxable'] = $temp_nontaxable;
						$data['total_sss'] = $temp_sss;
						$data['total_philhealth'] = $temp_philhealth;
						$data['total_pagibig'] = $temp_pagibig;
						$data['total_withheld_tax'] = $temp_withheld_tax;	
						$data['total_service_award_tax'] = $temp_service_award_tax;
						$data['total_taxable_leave_converted'] = $temp_taxable_leave_converted;


						array_push($array_merge_data, $data);

						$temp_gross_pay = 0;
						$temp_taxable= 0;
						$temp_nontaxable = 0;
						$temp_sss = 0;
						$temp_philhealth = 0;
						$temp_pagibig = 0;
						$temp_withheld_tax = 0;	
						$employee_counter = 0;
						$temp_taxable_leave_converted = 0; 
						$temp_service_award_tax = 0;

						
					}

				}//end foreach
	  			
	  		





			// $array_merge_data = array();

				// foreach ($cutoff_periods as $key => $cutoff_period_val) {

				// 	$cutoff_date = $cutoff_period_val->getStartDate(); 

				// 	$cutoff_number = $cutoff_period_val->getCutoffNumber();
				// 	$payslipsCutoff = G_Payslip_Finder::findAllByCutOffWithOptions($cutoff_date, $options);
					
				// 	// echo "<pre>";
				// 	// var_dump($payslipsCutoffTest);
				// 	// echo "</pre>";


			 //   	if( $payslipsCutoff ){
				// 		$eid = '';	
				// 		foreach( $payslipsCutoff as $payslip ){

				// 				if(in_array($payslip->getEmployeeId(), $current_employee)) {

				// 					if( $eid <> $payslip->getEmployeeId() ){	

				// 						$e = G_Employee_Helper::getEmployeeDataById($payslip->getEmployeeId(), $year);
				// 						$eid = $payslip->getEmployeeId();
										
				// 						if( $e ){
				// 							$year_hired_date  = date("Y",strtotime($e['hired_date']));
				// 							if( $year_hired_date > $year ){

				// 								continue;
				// 							}
				// 						}

				// 						// temp 
										

				// 						if( $e ){

											
											
				// 							$temp_sss 		 += $payslip->getSSS();
				// 							$temp_philhealth += $payslip->getPhilhealth();
				// 							$temp_pagibig    += $payslip->getPagibig();
				// 							$temp_gross_pay   += $payslip->getGrossPay();
				// 							$temp_withheld_tax += $payslip->getWithheldTax();

				// 							$taxable = $payslip->getTaxable();
				// 							$nontaxable = $payslip->getNonTaxable();
				// 							// Guide
				// 							// const TAXABLE = 1;
    // 										// const NON_TAXABLE = 2;
				// 							// $earnings = $payslip->getTotalEarnings();
				// 							$other_earnings = $payslip->getOtherEarnings();

				// 							foreach ($other_earnings as $key => $value) {				
				// 								if($value->isTaxable()){
													
				// 									$store_other_earnings_taxable += $value->getAmount();												
				// 								}
				// 								else{													
				// 									$store_other_earnings_nontaxable += $value->getAmount();
													
				// 								}
				// 							}
											
				// 							$sub_total_taxable = $taxable; 
				// 							$sub_total_nontaxable = $nontaxable;

				// 							$temp_taxable += $sub_total_taxable;
				// 							$temp_nontaxable += $sub_total_nontaxable;

				// 						}	


				// 					}

									
				// 				}
						

				// 		}
					
					
				// 		$data['cutoff_number'] = $cutoff_number;
				// 		$data['cutoff_date'] = $cutoff_date;
				// 		$data['total_gross_pay'] = $temp_gross_pay;
				// 		$data['total_taxable'] = $temp_taxable;
				// 		$data['total_nontaxable'] = $temp_nontaxable;
				// 		$data['total_sss'] = $temp_sss;
				// 		$data['total_philhealth'] = $temp_philhealth;
				// 		$data['total_pagibig'] = $temp_pagibig;
				// 		$data['total_withheld_tax'] = $temp_withheld_tax;	

				
				// 		array_push($array_merge_data, $data);

				// 		$temp_gross_pay = 0;
				// 		$temp_taxable= 0;
				// 		$temp_nontaxable = 0;
				// 		$temp_sss = 0;
				// 		$temp_philhealth = 0;
				// 		$temp_pagibig = 0;
				// 		$temp_withheld_tax = 0;	
				// 		$employee_counter = 0;

						
				// 	}

				// }//end foreach
		}
		// echo "<pre>";
		// var_dump($array_merge_data);
		// echo "</pre>";
		return $array_merge_data;
	}


	public function CustomConfiEmpPayslipJanuary()
	{
		$emp_payslip_a = array();

		/*
			Note:
			`3 = ENDIAPE HILDA
			`20 = CUADRA CLARITO, JR.
			`94 = MEDINA TALATAGOD
			`170 = ICHINOSE JOSEPHINE
			`69 = LAPITAN ALEXANDER
			`45 = MALATE MAYBEL
			`324 = NACODAM CLAODIA
			`24 = ISABELA MELODY
			`14 = QUIJANO MARIA CECILIA
			`13 = TAN JERRY
			`171 = URSUA ANALIZA
			`31 = AGUILAR VANESSA
			`29 = BATULAN MANOLO
			`12 = HERODIAS ELEZAR
			`5 = MADRIAGA DORIS DEE
		*/
		
		$emp_payslip_a[3]['basic_pay']   = 11125.00;
		$emp_payslip_a[3]['absences']    = 0;
		$emp_payslip_a[3]['undertime']   = 0;
		$emp_payslip_a[3]['tardiness']   = 0;
		$emp_payslip_a[3]['adjustment']  = 0;
		$emp_payslip_a[3]['sss']         = 581.30;
		$emp_payslip_a[3]['philhealth']  = 275.00;
		$emp_payslip_a[3]['pagibig']     = 2317.41;
		$emp_payslip_a[3]['taxwheld']    = 0; //2893.33;
		$emp_payslip_a[3]['rice_allowance']     = 750.00;
		$emp_payslip_a[3]['position_allowance'] = 1050.00 + 1050.00;
		$emp_payslip_a[3]['rotpay']             = 810.00;
		$emp_payslip_a[3]['nd_pay']             = 0;
		$emp_payslip_a[3]['meal_allowance']     = 600.00;
		$emp_payslip_a[3]['ot_allowance']       = 60.00;
		$emp_payslip_a[3]['other_earnings']     = 100.00 + 100.00;
		$emp_payslip_a[3]['transpo_allowance']  = 600.00;
		$emp_payslip_a[3]['grosspay']           = 27370.00 - 2210.00;

		$emp_payslip_a[20]['basic_pay']   = 15860.00;
		$emp_payslip_a[20]['absences']    = 0;
		$emp_payslip_a[20]['undertime']   = 0;
		$emp_payslip_a[20]['tardiness']   = 3.04;
		$emp_payslip_a[20]['adjustment']  = 0;
		$emp_payslip_a[20]['sss']         = 581.30;
		$emp_payslip_a[20]['philhealth']  = 387.50;
		$emp_payslip_a[20]['pagibig']     = 1728.20;
		$emp_payslip_a[20]['taxwheld']    = 0; //5607.50;
		$emp_payslip_a[20]['rice_allowance']     = 700.00;
		$emp_payslip_a[20]['position_allowance'] = 1300.00 + 1300.00;
		$emp_payslip_a[20]['rotpay']             = 3021.60;
		$emp_payslip_a[20]['nd_pay']             = 0;
		$emp_payslip_a[20]['meal_allowance']     = 600.00;
		$emp_payslip_a[20]['ot_allowance']       = 200.00;
		$emp_payslip_a[20]['other_earnings']     = 400.00 + 400.00;
		$emp_payslip_a[20]['transpo_allowance']  = 600.00;
		$emp_payslip_a[20]['grosspay']           = 40238.56 - 2900.00;
		
		$emp_payslip_a[94]['basic_pay']   = 14760.00;
		$emp_payslip_a[94]['absences']    = 0;
		$emp_payslip_a[94]['undertime']   = 0;
		$emp_payslip_a[94]['tardiness']   = 366.41;
		$emp_payslip_a[94]['adjustment']  = 0;
		$emp_payslip_a[94]['sss']         = 581.30;
		$emp_payslip_a[94]['philhealth']  = 362.50;
		$emp_payslip_a[94]['pagibig']     = 3418.96;
		$emp_payslip_a[94]['taxwheld']    = 0; //4569.31;
		$emp_payslip_a[94]['rice_allowance']     = 650.00;
		$emp_payslip_a[94]['position_allowance'] = 1300.00 + 1300.00;
		$emp_payslip_a[94]['rotpay']             = 0;
		$emp_payslip_a[94]['nd_pay']             = 0;
		$emp_payslip_a[94]['meal_allowance']     = 600.00;
		$emp_payslip_a[94]['ot_allowance']       = 0;
		$emp_payslip_a[94]['other_earnings']     = 500.00 + 500.00;
		$emp_payslip_a[94]['transpo_allowance']  = 600.00;
		$emp_payslip_a[94]['grosspay']           = 34603.59 - 2850.00;

		$emp_payslip_a[170]['basic_pay']   = 11710.00;
		$emp_payslip_a[170]['absences']    = 0;
		$emp_payslip_a[170]['undertime']   = 0;
		$emp_payslip_a[170]['tardiness']   = 0;
		$emp_payslip_a[170]['adjustment']  = 0;
		$emp_payslip_a[170]['sss']         = 581.30;
		$emp_payslip_a[170]['philhealth']  = 287.50;
		$emp_payslip_a[170]['pagibig']     = 0;
		$emp_payslip_a[170]['taxwheld']    = 0; //3478.80;
		$emp_payslip_a[170]['rice_allowance']     = 750.00;
		$emp_payslip_a[170]['position_allowance'] = 1050.00 + 1050.00;
		$emp_payslip_a[170]['rotpay']             = 0;
		$emp_payslip_a[170]['nd_pay']             = 0;
		$emp_payslip_a[170]['meal_allowance']     = 600.00;
		$emp_payslip_a[170]['ot_allowance']       = 0;
		$emp_payslip_a[170]['other_earnings']     = 0;
		$emp_payslip_a[170]['transpo_allowance']  = 600.00;
		$emp_payslip_a[170]['grosspay']           = 27470.00 - 1950.00;
		
		$emp_payslip_a[69]['basic_pay']   = 10710.00;
		$emp_payslip_a[69]['absences']    = 0;
		$emp_payslip_a[69]['undertime']   = 0;
		$emp_payslip_a[69]['tardiness']   = 0;
		$emp_payslip_a[69]['adjustment']  = 0;
		$emp_payslip_a[69]['sss']         = 581.30;
		$emp_payslip_a[69]['philhealth']  = 262.50;
		$emp_payslip_a[69]['pagibig']     = 2591.98;
		$emp_payslip_a[69]['taxwheld']    = 0; //3487.45; 
		$emp_payslip_a[69]['rice_allowance']     = 750.00;
		$emp_payslip_a[69]['position_allowance'] = 1050.00 + 1050.00;
		$emp_payslip_a[69]['rotpay']             = 1920.00;
		$emp_payslip_a[69]['nd_pay']             = 0;
		$emp_payslip_a[69]['meal_allowance']     = 630.00;
		$emp_payslip_a[69]['ot_allowance']       = 140.00;
		$emp_payslip_a[69]['other_earnings']     = 164.59 + 164.59;
		$emp_payslip_a[69]['transpo_allowance']  = 630.00;
		$emp_payslip_a[69]['grosspay']           = 27919.18 - 2479.18;
		
		$emp_payslip_a[45]['basic_pay']   = 10460.00;
		$emp_payslip_a[45]['absences']    = 5614.31;
		$emp_payslip_a[45]['undertime']   = 0;
		$emp_payslip_a[45]['tardiness']   = 0;
		$emp_payslip_a[45]['adjustment']  = 0;
		$emp_payslip_a[45]['sss']         = 581.30;
		$emp_payslip_a[45]['philhealth']  = 250.00;
		$emp_payslip_a[45]['pagibig']     = 1971.11;
		$emp_payslip_a[45]['taxwheld']    = 0; //2145.20; 
		$emp_payslip_a[45]['rice_allowance']     = 500.00;
		$emp_payslip_a[45]['position_allowance'] = 1050.00 + 1050.00;
		$emp_payslip_a[45]['rotpay']             = 0;
		$emp_payslip_a[45]['nd_pay']             = 0;
		$emp_payslip_a[45]['meal_allowance']     = 390.00;
		$emp_payslip_a[45]['ot_allowance']       = 0;
		$emp_payslip_a[45]['other_earnings']     = 164.59 + 164.59;
		$emp_payslip_a[45]['transpo_allowance']  = 390.00;
		$emp_payslip_a[45]['grosspay']           = 24629.18 + 4005.13;

		$emp_payslip_a[324]['basic_pay']   = 17500.00;
		$emp_payslip_a[324]['absences']    = 0;
		$emp_payslip_a[324]['undertime']   = 0;
		$emp_payslip_a[324]['tardiness']   = 0;
		$emp_payslip_a[324]['adjustment']  = 0;
		$emp_payslip_a[324]['sss']         = 581.30;
		$emp_payslip_a[324]['philhealth']  = 437.50;
		$emp_payslip_a[324]['pagibig']     = 0;
		$emp_payslip_a[324]['taxwheld']    = 0; //7347.02; 
		$emp_payslip_a[324]['rice_allowance']     = 750.00;
		$emp_payslip_a[324]['position_allowance'] = 1050.00 + 1050.00;
		$emp_payslip_a[324]['rotpay']             = 0;
		$emp_payslip_a[324]['nd_pay']             = 0;
		$emp_payslip_a[324]['meal_allowance']     = 630.00;
		$emp_payslip_a[324]['ot_allowance']       = 0;
		$emp_payslip_a[324]['other_earnings']           = 0;
		$emp_payslip_a[324]['transpo_allowance']  = 630.00;
		$emp_payslip_a[324]['grosspay']           = 39110.00 - 2010.00;

		$emp_payslip_a[24]['basic_pay']   = 28735.00;
		$emp_payslip_a[24]['absences']    = 0;
		$emp_payslip_a[24]['undertime']   = 0;
		$emp_payslip_a[24]['tardiness']   = 156.99;
		$emp_payslip_a[24]['adjustment']  = 0;
		$emp_payslip_a[24]['sss']         = 581.30;
		$emp_payslip_a[24]['philhealth']  = 437.50;
		$emp_payslip_a[24]['pagibig']     = 7654.93; 
		$emp_payslip_a[24]['taxwheld']    = 0; //13617.85; 
		$emp_payslip_a[24]['rice_allowance']     = 650.00;
		$emp_payslip_a[24]['position_allowance'] = 1050.00 + 1050.00;
		$emp_payslip_a[24]['rotpay']             = 0;
		$emp_payslip_a[24]['nd_pay']             = 0;
		$emp_payslip_a[24]['meal_allowance']     = 630.00;
		$emp_payslip_a[24]['ot_allowance']       = 0;
		$emp_payslip_a[24]['other_earnings']     = 150.00 + 150.00;
		$emp_payslip_a[24]['transpo_allowance']  = 630.00;
		$emp_payslip_a[24]['grosspay']           = 61623.01 - 2210.00;

		$emp_payslip_a[14]['basic_pay']   = 11870.00;
		$emp_payslip_a[14]['absences']    = 0;
		$emp_payslip_a[14]['undertime']   = 0;
		$emp_payslip_a[14]['tardiness']   = 0;
		$emp_payslip_a[14]['adjustment']  = 0;
		$emp_payslip_a[14]['sss']         = 581.30;
		$emp_payslip_a[14]['philhealth']  = 287.50;
		$emp_payslip_a[14]['pagibig']     = 0; 
		$emp_payslip_a[14]['taxwheld']    = 0; //2020.16; 
		$emp_payslip_a[14]['rice_allowance']     = 750.00;
		$emp_payslip_a[14]['position_allowance'] = 1050.00 + 1050.00;
		$emp_payslip_a[14]['rotpay']             = 0;
		$emp_payslip_a[14]['nd_pay']             = 0;
		$emp_payslip_a[14]['meal_allowance']     = 630.00;
		$emp_payslip_a[14]['ot_allowance']       = 0;
		$emp_payslip_a[14]['other_earnings']     = 400.00 + 400.00;
		$emp_payslip_a[14]['transpo_allowance']  = 3530.00;
		$emp_payslip_a[14]['grosspay']           = 31550.00 - 5710.00;
		
		$emp_payslip_a[13]['basic_pay']   = 13595.00;
		$emp_payslip_a[13]['absences']    = 1042.43;
		$emp_payslip_a[13]['undertime']   = 0;
		$emp_payslip_a[13]['tardiness']   = 0;
		$emp_payslip_a[13]['adjustment']  = 0;
		$emp_payslip_a[13]['sss']         = 581.30;
		$emp_payslip_a[13]['philhealth']  = 337.50;
		$emp_payslip_a[13]['pagibig']     = 1391.66; 
		$emp_payslip_a[13]['taxwheld']    = 0; //5079.95; 
		$emp_payslip_a[13]['rice_allowance']     = 750.00;
		$emp_payslip_a[13]['position_allowance'] = 1050.00 + 1050.00;
		$emp_payslip_a[13]['rotpay']             = 5880.00;
		$emp_payslip_a[13]['nd_pay']             = 1405.52;
		$emp_payslip_a[13]['meal_allowance']     = 600.00;
		$emp_payslip_a[13]['ot_allowance']       = 200.00;
		$emp_payslip_a[13]['other_earnings']     = 400.00 + 400.00;
		$emp_payslip_a[13]['transpo_allowance']  = 600.00;
		$emp_payslip_a[13]['grosspay']           = 39525.52 - 1907.57;

		$emp_payslip_a[171]['basic_pay']   = 15960.00;
		$emp_payslip_a[171]['absences']    = 0;
		$emp_payslip_a[171]['undertime']   = 0;
		$emp_payslip_a[171]['tardiness']   = 15.30;
		$emp_payslip_a[171]['adjustment']  = 0;
		$emp_payslip_a[171]['sss']         = 581.30;
		$emp_payslip_a[171]['philhealth']  = 387.50;
		$emp_payslip_a[171]['pagibig']     = 0; 
		$emp_payslip_a[171]['taxwheld']    = 0; //4694.81; 
		$emp_payslip_a[171]['rice_allowance']     = 750.00;
		$emp_payslip_a[171]['position_allowance'] = 1050.00 + 1050.00;
		$emp_payslip_a[171]['rotpay']             = 450.00;
		$emp_payslip_a[171]['nd_pay']             = 0;
		$emp_payslip_a[171]['meal_allowance']     = 600.00;
		$emp_payslip_a[171]['ot_allowance']       = 20.00;
		$emp_payslip_a[171]['other_earnings']           = 0;
		$emp_payslip_a[171]['transpo_allowance']  = 600.00;
		$emp_payslip_a[171]['grosspay']           = 36424.70 - 1970.00;
		
		$emp_payslip_a[31]['basic_pay']   = 8730.00;
		$emp_payslip_a[31]['absences']    = 0;
		$emp_payslip_a[31]['undertime']   = 0;
		$emp_payslip_a[31]['tardiness']   = 0;
		$emp_payslip_a[31]['adjustment']  = 0;
		$emp_payslip_a[31]['sss']         = 581.30;
		$emp_payslip_a[31]['philhealth']  = 212.50;
		$emp_payslip_a[31]['pagibig']     = 1666.98;
		$emp_payslip_a[31]['taxwheld']    = 0; //1859.66; 
		$emp_payslip_a[31]['rice_allowance']     = 700.00;
		$emp_payslip_a[31]['position_allowance'] = 675.00 + 675.00;
		$emp_payslip_a[31]['rotpay']             = 0;
		$emp_payslip_a[31]['nd_pay']             = 0;
		$emp_payslip_a[31]['meal_allowance']     = 630.00;
		$emp_payslip_a[31]['ot_allowance']       = 0;
		$emp_payslip_a[31]['other_earnings']     = 164.59 + 164.59;
		$emp_payslip_a[31]['transpo_allowance']  = 630.00;
		$emp_payslip_a[31]['grosspay']           = 21099.18 - 2289.18;

		$emp_payslip_a[29]['basic_pay']   = 9185.00;
		$emp_payslip_a[29]['absences']    = 0;
		$emp_payslip_a[29]['undertime']   = 0;
		$emp_payslip_a[29]['tardiness']   = 0;
		$emp_payslip_a[29]['adjustment']  = 0;
		$emp_payslip_a[29]['sss']         = 581.30;
		$emp_payslip_a[29]['philhealth']  = 225.00;
		$emp_payslip_a[29]['pagibig']     = 0;
		$emp_payslip_a[29]['taxwheld']    = 0; //3325.20; 
		$emp_payslip_a[29]['rice_allowance']     = 750.00;
		$emp_payslip_a[29]['position_allowance'] = 675.00 + 675.00;
		$emp_payslip_a[29]['rotpay']             = 5033.50;
		$emp_payslip_a[29]['nd_pay']             = 0;
		$emp_payslip_a[29]['meal_allowance']     = 630.00;
		$emp_payslip_a[29]['ot_allowance']       = 360.00;
		$emp_payslip_a[29]['other_earnings']     = 250.00 + 250.00;
		$emp_payslip_a[29]['transpo_allowance']  = 630.00;
		$emp_payslip_a[29]['grosspay']           = 27623.50 - 2870.00;

		$emp_payslip_a[12]['basic_pay']   = 13345.00;
		$emp_payslip_a[12]['absences']    = 0;
		$emp_payslip_a[12]['undertime']   = 0;
		$emp_payslip_a[12]['tardiness']   = 0;
		$emp_payslip_a[12]['adjustment']  = 0;
		$emp_payslip_a[12]['sss']         = 581.30;
		$emp_payslip_a[12]['philhealth']  = 325.00;
		$emp_payslip_a[12]['pagibig']     = 0;
		$emp_payslip_a[12]['taxwheld']    = 0; //5158.94; 
		$emp_payslip_a[12]['rice_allowance']     = 700.00;
		$emp_payslip_a[12]['position_allowance'] = 675.00 + 675.00;
		$emp_payslip_a[12]['rotpay']             = 5196.24;
		$emp_payslip_a[12]['nd_pay']             = 463.66;
		$emp_payslip_a[12]['meal_allowance']     = 510.00;
		$emp_payslip_a[12]['ot_allowance']       = 260.00;
		$emp_payslip_a[12]['other_earnings']     = 400.00 + 400.00;
		$emp_payslip_a[12]['transpo_allowance']  = 510.00;
		$emp_payslip_a[12]['grosspay']           = 36479.90 - 2780.00;

		$emp_payslip_a[5]['basic_pay']   = 10055.00;
		$emp_payslip_a[5]['absences']    = 0;
		$emp_payslip_a[5]['undertime']   = 0;
		$emp_payslip_a[5]['tardiness']   = 0;
		$emp_payslip_a[5]['adjustment']  = 0;
		$emp_payslip_a[5]['sss']         = 581.30;
		$emp_payslip_a[5]['philhealth']  = 250.00;
		$emp_payslip_a[5]['pagibig']     = 4317.56;
		$emp_payslip_a[5]['taxwheld']    = 0; //1979.71; 
		$emp_payslip_a[5]['rice_allowance']     = 750.00;
		$emp_payslip_a[5]['position_allowance'] = 675.00 + 675.00;
		$emp_payslip_a[5]['rotpay']             = 0;
		$emp_payslip_a[5]['nd_pay']             = 0;
		$emp_payslip_a[5]['meal_allowance']     = 630.00;
		$emp_payslip_a[5]['ot_allowance']       = 0;
		$emp_payslip_a[5]['other_earnings']     = 250.00 + 250.00;
		$emp_payslip_a[5]['transpo_allowance']  = 630.00;
		$emp_payslip_a[5]['grosspay']           = 23970.00 - 2510.00;

		return $emp_payslip_a;
	}	

	public function alphaListReportCustomFixJanuaryPayslip( $year, $options ){
		$return = array();
		$manual_payslip_changes = $this->CustomConfiEmpPayslipJanuary();

		if( $year > 0 ){			
			$payslips1 = G_Payslip_Finder::findAllByYearWithOptionsCustomAddSelectedEmpAndRemoveJanuary($year, $options);			
			$payslips2 = G_Payslip_Finder::findAllByYearWithOptionsCustomRemoveSelectedEmp($year, $options);		

			$payslips = array_merge($payslips1,$payslips2);

			//Temp Data
			$tmp_migrated = G_Migrate_Data_Helper::getAllDataByYear($year);			

			$migrated     = array();			
			foreach( $tmp_migrated as $data ){
				$migrated[$data['employee_id']][$data['field']] = $data['amount'];
			}

			//Annualized Tax
			$atax = G_Employee_Annualize_Tax_Helper::getAllAnnualizedTaxByYear($year);			
			$e_atax = array();
			foreach( $atax as $tax ){
				$e_atax[$tax['employee_id']]['tax_withheld_payroll'] = $tax['tax_withheld_payroll'];
				$e_atax[$tax['employee_id']]['tax_refund_payable']   = $tax['tax_refund_payable'];
				$e_atax[$tax['employee_id']]['tax_due']   = $tax['tax_due'];
			}
						
			if( $payslips ){
				$eid = '';				
				foreach( $payslips as $payslip ){
					
					if( $eid <> $payslip->getEmployeeId() ){						
						$e = G_Employee_Helper::getEmployeeDataById($payslip->getEmployeeId(), $year);
						$eid = $payslip->getEmployeeId();

						if( $e ){
							$year_hired_date  = date("Y",strtotime($e['hired_date']));
							if( $year_hired_date > $year ){
								continue;
							}
						}

						//Temp Data		
						if( $e ){						
							$return[$payslip->getEmployeeId()]['13th_month'] += $migrated[$payslip->getEmployeeId()]['13th_month'];						
							$return[$payslip->getEmployeeId()]['nd_pay']     += $migrated[$payslip->getEmployeeId()]['sum_nd_pay'];					
							$return[$payslip->getEmployeeId()]['basic_pay']  += $migrated[$payslip->getEmployeeId()]['basic_pay'] + $migrated[$payslip->getEmployeeId()]['sum_bl_pay'] + $migrated[$payslip->getEmployeeId()]['sum_fl_pay'] + $migrated[$payslip->getEmployeeId()]['sum_pt_pay'] + $migrated[$payslip->getEmployeeId()]['paid_leaves'];	
							$return[$payslip->getEmployeeId()]['sss'] 		 += $migrated[$payslip->getEmployeeId()]['sum_sss'];	
							$return[$payslip->getEmployeeId()]['philhealth'] += $migrated[$payslip->getEmployeeId()]['sum_philhealth'];	
							$return[$payslip->getEmployeeId()]['pagibig']    += $migrated[$payslip->getEmployeeId()]['sum_pagibig'];	
							$return[$payslip->getEmployeeId()]['taxwheld']   += $e_atax[$payslip->getEmployeeId()]['tax_due'];
							$return[$payslip->getEmployeeId()]['paid_holiday'] += $migrated[$payslip->getEmployeeId()]['sum_paid_holiday'] + $migrated[$payslip->getEmployeeId()]['sum_holiday_pay'];	
							$return[$payslip->getEmployeeId()]['transpo_allowance']   += $migrated[$payslip->getEmployeeId()]['transportation_allowance'];	
							$return[$payslip->getEmployeeId()]['meal_allowance']   += $migrated[$payslip->getEmployeeId()]['meal_allowance'];	
							$return[$payslip->getEmployeeId()]['rice_allowance']   += $migrated[$payslip->getEmployeeId()]['rice_allowance'];	
							$return[$payslip->getEmployeeId()]['position_allowance']   += $migrated[$payslip->getEmployeeId()]['position_allowance'];	
							$return[$payslip->getEmployeeId()]['grosspay']   += $migrated[$payslip->getEmployeeId()]['gross_pay'];	
							$return[$payslip->getEmployeeId()]['rotpay'] += $migrated[$payslip->getEmployeeId()]['sum_sunday_pay'] + $migrated[$payslip->getEmployeeId()]['sum_special_holiday_pay'] + $migrated[$payslip->getEmployeeId()]['sum_pholiday_sunday_pay'] + $migrated[$payslip->getEmployeeId()]['rot_pay'];

							//13thmonth					
							$total_yearly_bonus = 0;
							$yearly_bonus = G_Yearly_Bonus_Release_Date_Helper::getEmployeeTotalBonusByYear($payslip->getEmployeeId(), $year);
							if( !empty($yearly_bonus) ){
								$total_yearly_bonus = $yearly_bonus['total_bonus'];
							}
							$return[$payslip->getEmployeeId()]['13th_month']   += $total_yearly_bonus;

							$year_hired_date  = date("Y",strtotime($e['hired_date']));
							$month_hired_date = date("m",strtotime($e['hired_date'])); 
							$day_hired_date   = date("d",strtotime($e['hired_date'])); 

							if( $month_hired_date > 1 && $year_hired_date == $year ){
								$return[$payslip->getEmployeeId()]['start_period'] = "{$month_hired_date}/{$day_hired_date}";							
							}else{
								$return[$payslip->getEmployeeId()]['start_period'] = "01/01";
							}

							$return[$payslip->getEmployeeId()]['end_period'] = '12/31';

							if( $e['endo_date'] != '0000-00-00') {
								$year_hired_date  = date("Y",strtotime($e['endo_date']));
								$month_hired_date = date("m",strtotime($e['endo_date'])); 
								$day_hired_date   = date("d",strtotime($e['endo_date'])); 
								$return[$payslip->getEmployeeId()]['end_period']   = "{$month_hired_date}/{$day_hired_date}";		
							}

							if( $e['terminated_date'] != '0000-00-00') {
								$year_hired_date  = date("Y",strtotime($e['terminated_date']));
								$month_hired_date = date("m",strtotime($e['terminated_date'])); 
								$day_hired_date   = date("d",strtotime($e['terminated_date']));
								$return[$payslip->getEmployeeId()]['end_period']   = "{$month_hired_date}/{$day_hired_date}";	
							}

							if( $e['resignation_date'] != '0000-00-00') {
								$year_hired_date  = date("Y",strtotime($e['resignation_date']));
								$month_hired_date = date("m",strtotime($e['resignation_date'])); 
								$day_hired_date   = date("d",strtotime($e['resignation_date']));
								$return[$payslip->getEmployeeId()]['end_period']   = "{$month_hired_date}/{$day_hired_date}";	
							}
						}
					}

					if( $e ){

						$return[$payslip->getEmployeeId()]['employee_id'] = $e['employee_code'];
						$return[$payslip->getEmployeeId()]['firstname']   = $e['firstname'];
						$return[$payslip->getEmployeeId()]['middlename']  = $e['middlename'];
						$return[$payslip->getEmployeeId()]['lastname']    = $e['lastname'];
						$return[$payslip->getEmployeeId()]['resignation_date'] = $e['resignation_date'];
						$return[$payslip->getEmployeeId()]['endo_date']   = $e['endo_date'];
						$return[$payslip->getEmployeeId()]['terminated_date'] = $e['terminated_date'];
						$return[$payslip->getEmployeeId()]['hired_date']  = $e['hired_date'];						
						$return[$payslip->getEmployeeId()]['year_working_days'] = $e['year_working_days'];						
						$return[$payslip->getEmployeeId()]['present_salary'] = $e['present_salary'];	
						$return[$payslip->getEmployeeId()]['birthdate']   = date("m/d/Y", strtotime($e['birthdate']));						

						$return[$payslip->getEmployeeId()]['address']     = $e['address'];
						$return[$payslip->getEmployeeId()]['address_zipcode'] = $e['zip_code'];

						$return[$payslip->getEmployeeId()]['tin_number']  = $e['tin_number'];

						$return[$payslip->getEmployeeId()]['philhealth_number'] = $e['philhealth_number'];
						$return[$payslip->getEmployeeId()]['pagibig_number']    = $e['pagibig_number'];
						$return[$payslip->getEmployeeId()]['sss_number']        = $e['sss_number'];

						$return[$payslip->getEmployeeId()]['employee_status'] = $e['employee_status'];
						$return[$payslip->getEmployeeId()]['employment_status'] = $e['employment_status'];
						$return[$payslip->getEmployeeId()]['department_name'] = $e['department_name'];
						$return[$payslip->getEmployeeId()]['section_name'] = $e['section_name'];
						$return[$payslip->getEmployeeId()]['civil_status'] = $e['marital_status'];
						$return[$payslip->getEmployeeId()]['number_dependent'] = $e['number_dependent'];

						$total_dependents   = $e['number_dependent'];
						//$return[$payslip->getEmployeeId()]['personal_exemption'] = 0;
						//if( $total_dependents > 0 ){							
							$net_taxable_calculator   = new Net_Taxable_Calculator();
							$personal_exemption = $net_taxable_calculator->computeAdditionalExemptions($e['number_dependent']);
							$return[$payslip->getEmployeeId()]['personal_exemption'] = $personal_exemption;
						//}

						$return[$payslip->getEmployeeId()]['basic_pay']  += $payslip->getBasicPay();
						$return[$payslip->getEmployeeId()]['sss'] 		 += $payslip->getSSS();
						$return[$payslip->getEmployeeId()]['philhealth'] += $payslip->getPhilhealth();
						$return[$payslip->getEmployeeId()]['pagibig']    += $payslip->getPagibig();
						//$return[$payslip->getEmployeeId()]['taxwheld']   += $payslip->getWithheldTax();					
						$return[$payslip->getEmployeeId()]['grosspay']   += $payslip->getGrossPay();						

						//Labels
						$labels = $payslip->getLabels();						
						foreach( $labels as $l ){
							switch (strtolower($l->getVariable())) {
								case 'absent_amount':
									$return[$payslip->getEmployeeId()]['absences'] += $l->getValue();
									break;
								case 'suspended_amount':
									$return[$payslip->getEmployeeId()]['absences'] += $l->getValue();
									break;
								case 'undertime_amount':
									$return[$payslip->getEmployeeId()]['undertime'] += $l->getValue();
									break;
								case 'late_amount':
									$return[$payslip->getEmployeeId()]['tardiness'] += $l->getValue();
									break;	
								case 'restday_amount':								
									$return[$payslip->getEmployeeId()]['rotpay'] += $l->getValue();
									break;								
								/*case 'regular_ns_amount':
									$return[$payslip->getEmployeeId()]['nd_pay'] += $l->getValue();
									break;*/
								/*case 'regular_ns_ot_hours':
									break;*/	
								/*case 'regular_ot_amount':
									$return[$payslip->getEmployeeId()]['rotpay'] += $l->getValue();		
									break;*/
								case (strtolower($l->getVariable()) == 'holiday_legal_amount' || strtolower($l->getVariable()) == 'holiday_special_amount'):
									$return[$payslip->getEmployeeId()]['paid_holiday'] += $l->getValue();		
									break;			
								/*case 'tax_refund':
									$return[$payslip->getEmployeeId()]['taxwheld']  = $payslip->getWithheldTax() + $migrated[$payslip->getEmployeeId()]['taxwheld'] - $l->getValue();		
									break;*/
								default:
									# code...
									break;
							}

							if( stripos(strtolower($l->getLabel()), 'ot amount') !== false ){
								//echo $l->getLabel() . "/" . $l->getValue() . "<br />"; 	
								$return[$payslip->getEmployeeId()]['rotpay'] += $l->getValue();
							}	

							if( stripos(strtolower($l->getLabel()), 'ns amount') !== false ){
								//echo $l->getLabel() . "/" . $l->getValue() . "<br />"; 	
								$return[$payslip->getEmployeeId()]['nd_pay'] += $l->getValue();
							}						
						}

						//Earnings
						$earnings = $payslip->getEarnings();
						foreach( $earnings as $ea ){
							switch (strtolower($ea->getVariable())) {
								case 'total_ceta_amount':
									$return[$payslip->getEmployeeId()]['ctpa_sea'] += $ea->getAmount();
									break;
								default:
									# code...
									break;
							}
						}

						//Other Earnings
						$other_earnings = $payslip->getOtherEarnings();

						foreach( $other_earnings as $oe ){
							switch (strtolower($oe->getVariable())) {
								case 'adjustment':
									$return[$payslip->getEmployeeId()]['adjustment'] += $oe->getAmount();
									break;
								case 'bonus':
									if($oe->getTaxType() == 2) {
										$return[$payslip->getEmployeeId()]['bonus'] += $oe->getAmount();
									}
									if($oe->getTaxType() == 1) {
										$return[$payslip->getEmployeeId()]['bonus_tax'] += $oe->getAmount();
									}
									break;
								case 'service award':
									if( $oe->getTaxType() == 1 ){
										$return[$payslip->getEmployeeId()]['service_award_tax'] += $oe->getAmount();
									}else{
										$return[$payslip->getEmployeeId()]['service_award'] += $oe->getAmount();
									}									
									break;
								case 'non_taxable_converted_leave':
									$return[$payslip->getEmployeeId()]['non_taxable_leave_converted'] += $oe->getAmount();
									break;
								case 'taxable_converted_leave':
									$return[$payslip->getEmployeeId()]['taxable_leave_converted'] += $oe->getAmount();
									break;
								/*case 'rice allowance':
									$return[$payslip->getEmployeeId()]['rice_allowance'] += $oe->getAmount();
									break;*/
								case 'meal allowance':
									$return[$payslip->getEmployeeId()]['meal_allowance'] += $oe->getAmount();
									break;
								case 'ot allowance': 
									$return[$payslip->getEmployeeId()]['ot_allowance'] += $oe->getAmount();
									break;
								case 'transpo allowance':
									$return[$payslip->getEmployeeId()]['transpo_allowance'] += $oe->getAmount();
									break;
								case 'non taxable converted leave':
									$return[$payslip->getEmployeeId()]['non_taxable_leave_converted']   += $oe->getAmount();	
									break;
								case 'taxable converted leave':
									$return[$payslip->getEmployeeId()]['taxable_leave_converted']   += $oe->getAmount();	
									break;
								default:
									$is_other_earnings = true;
									if( stripos(strtolower($oe->getVariable()), 'position allowance') !== false ){								
										$return[$payslip->getEmployeeId()]['position_allowance'] += $oe->getAmount();
										$is_other_earnings = false;
									}

									if( stripos(strtolower($oe->getVariable()), 'ctpa/sea') !== false ){
										$return[$payslip->getEmployeeId()]['ctpa_sea'] += $oe->getAmount();
										$is_other_earnings = false;
									}

									if( stripos(strtolower($oe->getVariable()), 'rice allowance') !== false ){															
										$return[$payslip->getEmployeeId()]['rice_allowance'] += $oe->getAmount();
										$is_other_earnings = false;
									}

									if( $is_other_earnings ){
										$return[$payslip->getEmployeeId()]['other_earnings'] += $oe->getAmount();
									}
									break;
							}
						}

						//Other Deductions
						$other_deductions = $payslip->getOtherDeductions();															
						foreach( $other_deductions as $od ){
							switch (strtolower($od->getVariable())) {
								/*case 'tax_bonus_service_award':									
									$return[$payslip->getEmployeeId()]['service_award_tax'] += $od->getAmount();
									# code...
									break;	*/
								case 'adjustment':
									$return[$payslip->getEmployeeId()]['adjustment'] -= $oe->getAmount();
									break;						
								default:
									# code...
									break;
							}
						}
					}						
				}	

				//Annualized
				$atax   = array();
				$fields = array('employee_id','tax_due');
				$annualized_tax = G_Employee_Annualize_Tax_Helper::getAllDataByYear($year, $fields);
				foreach( $annualized_tax as $data ){
					if( isset($return[$data['employee_id']]) ){
						$return[$data['employee_id']]['taxwheld'] -= $data['tax_due'];
					}					
				}

			}
		}

		/*
		* Add payslip data manually on the alphalist report (for the month of january 2016 Only) - start
		*/

		
		foreach($manual_payslip_changes as $mpkey => $mpkeyd) {

			if( !empty($return[$mpkey]) ) {

				$return[$mpkey]['basic_pay']  += $mpkeyd['basic_pay'];
				$return[$mpkey]['absences']   += $mpkeyd['absences'];
				$return[$mpkey]['undertime']  += $mpkeyd['undertime'];
				$return[$mpkey]['tardiness']  += $mpkeyd['tardiness'];
				$return[$mpkey]['adjustment'] += $mpkeyd['adjustment'];
				$return[$mpkey]['sss'] 		  += $mpkeyd['sss'];
				$return[$mpkey]['philhealth'] += $mpkeyd['philhealth'];
				$return[$mpkey]['pagibig']    += $mpkeyd['pagibig'];
				$return[$mpkey]['taxwheld']   += $mpkeyd['taxwheld'];
				$return[$mpkey]['rice_allowance'] 		+= $mpkeyd['rice_allowance'];
				$return[$mpkey]['position_allowance'] 	+= $mpkeyd['position_allowance'];
				$return[$mpkey]['rotpay']  				+= $mpkeyd['rotpay']; //ot pay
				$return[$mpkey]['nd_pay'] 				+= $mpkeyd['nd_pay'];
				$return[$mpkey]['meal_allowance'] 	 += $mpkeyd['meal_allowance'];
				$return[$mpkey]['ot_allowance'] 	 += $mpkeyd['ot_allowance'];
				$return[$mpkey]['other_earnings'] 	 += $mpkeyd['other_earnings'];
				$return[$mpkey]['transpo_allowance'] += $mpkeyd['transpo_allowance'];
				$return[$mpkey]['grosspay'] 		 += $mpkeyd['grosspay'];

			} 
		}
		

		/*
		* Add payslip data manually on the alphalist report (for the month of january 2016 Only) - end
		*/		

		return $return;
	}	

	/**
    * Generate shifting schedule report
    *
    *@param date date
    *@param string addon_query   
    *@return array
	*/
	public function shiftScheduleReport( $date_from = '', $date_to = '', $addon_query = '' ){					
		$employees   = G_Employee_Helper::getAllActiveEmployee( $addon_query );		
		
		/*
			$sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_NIGHTSHIFT_HOUR);
	        $night_shift_hr = $sv->getVariableValue();
	        $a_nightshift   = explode("to", $night_shift_hr);
	        $ns_start       = strtotime($a_nightshift[0]);
        */

        $ns_start = "06:00:00";
        
        $night_sched = array();
        $day_sched   = array();

		foreach( $employees as $e ){
			//$ss = G_Schedule_Specific_Finder::findEmployeeScheduleByEmployeeIdAndDateRange($e['id'], $date_from, $date_to);        
			$ss = G_Schedule_Specific_Finder::findChangeEmployeeScheduleByEmployeeIdAndDateRange($e['id'], $date_from, $date_to);        

			
			/*if($e['id'] == 9) {
				echo 'sese';
				echo '<pre>';
				print_r($ss);
				echo '</pre>';
			}*/

			if( $ss ){
				$end = $ss->getTimeOut();
				if( $end <= $ns_start ){
					$night_sched[$e['id']]['employee'] = $e;
					$night_sched[$e['id']]['schedule'] = $ss;
				}else{
					$day_sched[$e['id']]['employee'] = $e;
					$day_sched[$e['id']]['schedule'] = $ss;
				}						
			}else{							

				$s = G_Schedule_Finder::findScheduleByEmployeeIdAndDate($e['id'], $date_from, $date_to);			

				
				/*if($e['id'] == 9) {
					echo 'har har';
					echo '<pre>';
					print_r($s);
					echo '</pre>';
				}*/				 

				 if( $s ){
				 	$end = $s->getTimeOut();
				 	$date_start = $s->getDateStart();
				 	$date_end = $s->getDateEnd();
					if( $end <= $ns_start ){
						if($date_from >= $date_start && $date_to <= $date_end) {
							$night_sched[$e['id']]['employee'] = $e;
							$night_sched[$e['id']]['schedule'] = $s;							
						} else {
							$day_sched[$e['id']]['employee'] = $e;
							$day_sched[$e['id']]['schedule'] = $s;							
						}
					}else{
						$day_sched[$e['id']]['employee'] = $e;
						$day_sched[$e['id']]['schedule'] = $s;
					}	
				 }else{
				 	$default_schedule = G_Schedule_Finder::findDefaultByDate($date);
				 	if( $default_schedule ){
				 		$end = $default_schedule->getTimeOut();
						if( $end <= $ns_start ){
							$night_sched[$e['id']]['employee'] = $e;
							$night_sched[$e['id']]['schedule'] = $default_schedule;
						}else{
							$day_sched[$e['id']]['employee'] = $e;
							$day_sched[$e['id']]['schedule'] = $default_schedule;
						}	
				 	}
				 }
			}
		}

		$return = array(
			'day_schedule' => $day_sched,
			'night_schedule' => $night_sched
		);

		return $return;
	}
}
?>