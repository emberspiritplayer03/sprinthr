<?php
class G_Annualize_Tax extends Employee_Annualize_Tax {	

	protected $computed_data = array();
	protected $continue = false;

	const START_MONTH = 1;
	const END_MONTH   = 12;
	const CEILING_BONUS = 90000;
	public function __construct() {		
	}

	public function setDefaultFromAndEndDate($year = '') {
		if(empty($year)) {
			$year  = date("Y");
		}
		
		$start = $year . "-" . self::START_MONTH . "-" . "01";
		$end   = $year . "-" . self::END_MONTH . "-" . "01";

		$this->from_date = date("Y-m-d",strtotime($start));
		$this->to_date  = date("Y-m-t",strtotime($end));
		
		return $this;
	}

	public function setDateRange($range = array()) {
		$this->convertMonthNumberToDateRange($range);
	}

	/**
	* Skip validation
	* 
	* @return object instance	
	*/
	public function skipValidate() {
		$this->continue = true;

		return $this;
	}

	/**
	* Convert start and end month number to readable start / end date
	*
	* @param array range
	* @return void
	*/
	public function convertMonthNumberToDateRange($range = array()) {
		if( !empty($range) ){
			$start_date = $range['start_year'] . '-' . $range['start_month'] . '-01';
			$end_date   = $range['end_year'] . '-' . $range['end_month'] . '-01';
			$end_date   = date("Y-m-t",strtotime($end_date));

			$this->from_date = $start_date;
			$this->to_date   = $end_date;
		}
	}

	public function validate( $cutoff_period = '' ) {
		//Check if cutoff period is lock		
		if( $this->from_date != '' && $this->to_date != '' ){
			$is_lock = G_Cutoff_Period_Helper::isPeriodLockByDate($this->from_date, $this->to_date);

			if( $is_lock == G_Cutoff_Period::NO ){
				$this->continue = true;
			}
		}
		return $this;
	} 

	public function setCutoffPeriod( $cutoff_period ) {
		$a_cutoffs = explode("/", $cutoff_period);		
		$this->cutoff_start_date = $a_cutoffs[0];
		$this->cutoff_end_date   = $a_cutoffs[1];
	}

	/**
	*
	*
	* @param array employee_ids
	* @param boolean all_employee	
	*/

	public function compute( $employee_ids = array(), $all_employees = false ) {
		$data = array();
		if( $this->year > 0 ){
			if( $all_employee ){

			}else{
				//Payslip Data
				$fields   = array('employee_id','basic_pay','SUM(overtime)AS total_overtime', 'SUM(withheld_tax)AS total_tax','SUM(tardiness_amount)AS total_tardiness','SUM(sss)AS total_sss','SUM(pagibig)AS total_pagibig','SUM(philhealth)AS total_philhealth','SUM(gross_pay)AS total_gross_pay');
				$group_by = 'GROUP BY employee_id';
				$payslip = G_Payslip_Helper::sqlGetAllPayslipDataByYear($this->year,$fields,$group_by);
				//Utilities::displayArray($payslip);

				//Leave Converted
				$fields   = array('ea.object_id AS employee_id','SUM(ea.amount)AS leave_converted_total_amount','SUM(IF(ea.is_taxable = "Yes",ea.amount,0))AS leave_converted_taxable_amount','SUM(IF(ea.is_taxable = "No",ea.amount,0))AS leave_converted_nontaxable_amount');
				$group_by = 'GROUP BY ea.object_id';
				$earnings = G_Employee_Earnings_Helper::sqlGetAllEmployeesLeaveConversionByYear($this->year,$fields,$group_by);				
				$earnings_data = array();
				foreach( $earnings as $e ){														
					$earnings_data[$e['employee_id']] = array('leave_converted_total_amount' => $e['leave_converted_total_amount'], 'leave_converted_taxable_amount' => $e['leave_converted_taxable_amount'],'leave_converted_nontaxable_amount' => $e['leave_converted_nontaxable_amount']);
				}
				//Utilities::displayArray($earnings_data);
				

				//Yearly Bonus
				$fields = array('employee_id','SUM(amount)AS yearly_bonus_total_amount');
				$bonus  = G_Yearly_Bonus_Release_Date_Helper::sqlGetEmployeesYearlyBonusByYear($this->year,$fields);				
				$bonus_data = array();
				foreach( $bonus as $b ){
					$bonus_data[$b['employee_id']] = $b['yearly_bonus_total_amount'];	
				}

				//Utilities::displayArray($bonus_data);
				$payslip_data = array();
				foreach($payslip as $p){
					$eid = $p['employee_id'];
					unset($p['employee_id']);

					$new_gross_income   = $p['total_gross_pay'] + $bonus_data[$eid] + $earnings_data[$eid]['leave_converted_total_amount'];
					$payslip_data[$eid] = $p;
					$payslip_data[$eid]['13th_month']      = $bonus_data[$eid];
					$payslip_data[$eid]['leave_converted'] = $earnings_data[$eid]['leave_converted_total_amount'];
					$payslip_data[$eid]['new_total_gross_pay'] =  $new_gross_income;

					//Personal Exemption
					$pe = 0;
					$e = G_Employee_Finder::findById($eid);
					if( $e ){						
						$tax = new Tax_Calculator();
						$tax->setNumberOfDependent($e->getNumberDependent());
						$pe  = $tax->getPersonalExemptionAmount();
					}
					$payslip_data[$eid]['personal_exemption'] = $pe;

					//Taxable Income
					$taxable_income = $new_gross_income - $pe;
					$payslip_data[$eid]['taxable_income'] = $taxable_income;

					//Tax due
					$tax = new Tax_Table_Annual();
					$tax_data = $tax->getAnnualTaxBracket($taxable_income);					
					if( $tax_data ){
						$tax_due = $taxable_income - $tax_data['excess'];
						$tax_due = $tax_due * ($tax_data['percent'] / 100);
						$tax_due = $tax_due + $tax_data['first'];
					}else{
						$tax_due  = $taxable_income;
					}

					$payslip_data[$eid]['annual_tax_data'] = $tax_data;
					$payslip_data[$eid]['tax_due']         = $tax_due;
					$payslip_data[$eid]['tax_refund']      = $tax_due - $p['total_tax'];
				}



				Utilities::displayArray($payslip_data);
			}
		}

		exit;

		$this->computed_data = $data;
    }

	public function annualizeTax( $employee_ids = array(), $import_hmo_data = array() ) {
		$data   = array();
		$return = array('is_success' => false, 'message' => 'No records processed');

		if( $this->year > 0 && $this->continue ){
			$range = array('from' => $this->from_date, 'to' => $this->to_date);

			//We need to delete existing data before recreate
			G_Employee_Annualize_Tax_Manager::deleteExistingDataByEmployeeIdsAndDateRange($data['employee_ids'], $range);
			//Payslip Data
			$fields   = array('employee_id','basic_pay','SUM(overtime)AS total_overtime', 'SUM(withheld_tax)AS total_tax','SUM(tardiness_amount)AS total_tardiness','SUM(sss)AS total_sss','SUM(pagibig)AS total_pagibig','SUM(philhealth)AS total_philhealth','SUM(gross_pay)AS total_gross_pay');
			$group_by = 'GROUP BY employee_id';

			$payslip  = G_Payslip_Helper::sqlGetEmployeesPayslipDataByYearAndDateRange($this->year, $employee_ids, $range, $fields, $group_by);
			$fields_ot = array('other_earnings');
				// $payslip_other_earnings  = G_Payslip_Helper::sqlGetEmployeesPayslipDataByYearAndDateRangeOtherEarnings($this->year, $employee_ids, $range, $fields_ot, $group_by);


					
			//Utilities::displayArray($payslip); exit;

			//Leave Converted
			/*$fields   = array('ea.object_id AS employee_id','SUM(ea.amount)AS leave_converted_total_amount','SUM(IF(ea.is_taxable = "Yes",ea.amount,0))AS leave_converted_taxable_amount','SUM(IF(ea.is_taxable = "No",ea.amount,0))AS leave_converted_nontaxable_amount');*/
			$fields   = array('ea.object_id AS employee_id','SUM(ea.amount)AS leave_converted_total_amount');
			$group_by = 'GROUP BY ea.object_id';
			$earnings = G_Employee_Earnings_Helper::sqlGetEmployeesLeaveTaxableConvertedToCashByYearAndDateRange($this->year, $employee_ids, $range, $fields,$group_by);						
			$earnings_data = array();

			foreach( $earnings as $e ){														
				$earnings_data[$e['employee_id']]['leave_converted_total_amount'] = $e['leave_converted_total_amount'];
			}

			//Service Award
			$fields   = array('ea.object_id AS employee_id','SUM(ea.amount)AS service_award_taxable');
			$group_by = 'GROUP BY ea.object_id';
			$earnings = G_Employee_Earnings_Helper::sqlGetEmployeesServiceAwardTaxableByYearAndDateRange($this->year, $employee_ids, $range, $fields,$group_by);
			foreach( $earnings as $e ){														
				$earnings_data[$e['employee_id']]['service_award_taxable'] = $e['service_award_taxable'];
			}

			//Taxable Bonus
			$fields   = array('ea.object_id AS employee_id','SUM(ea.amount)AS bonus_taxable');
			$group_by = 'GROUP BY ea.object_id';
			$earnings = G_Employee_Earnings_Helper::sqlGetEmployeesBonusTaxableByYearAndDateRange($this->year, $employee_ids, $range, $fields,$group_by);

			foreach( $earnings as $e ){
			
				$earnings_data[$e['employee_id']]['bonus_taxable'] = $e['bonus_taxable'];
			}
			
			//Utilities::displayArray($earnings);
			//exit;

			//Yearly Bonus
			$fields = array('employee_id','SUM(amount)AS yearly_bonus_total_amount');
			$bonus  = G_Yearly_Bonus_Release_Date_Helper::sqlGetEmployeesYearlyBonusByYearAndDateRange($this->year, $employee_ids, $range, $fields);				
			$bonus_data = array();
			foreach( $bonus as $b ){
				$bonus_data[$b['employee_id']] = $b['yearly_bonus_total_amount'];	
			}

			//Utilities::displayArray($bonus_data);
			//exit;

			//Temp Data
			$migrated_data = array();
			$migrated  = new G_Migrate_Data();
			$temp_data = $migrated->getAllMigratedData($employee_ids);

			foreach( $temp_data as $td ){
				$migrated_data[$td['employee_id']][$td['field']] = $td['amount'];	
				$migrated_data[$td['employee_id']]['field'] = $td['field'];	
			}

			//Utilities::displayArray($payslip);
			//exit;
			
			$payslip_data = array();			
			foreach($payslip as $p){
				$eid = $p['employee_id'];
				unset($p['employee_id']);
				
				$total_yearly_bonus = $migrated_data[$eid]['13th_month'] + $bonus_data[$eid];

				if( $total_yearly_bonus >= self::CEILING_BONUS ){
					$new_bonus_amount = $total_yearly_bonus - self::CEILING_BONUS;
				}else{
					$new_bonus_amount = 0;
				}

                $range_custom = array('from' => $this->from_date, 'to' => $this->to_date);
                $p_other_deduction = G_Payslip_Helper::sqlGetEmployeesPayslipOtherDeduction($eid, $range_custom, $this->year);

                $service_tax_array = array();
                $union_dues_array  = array();
                foreach($p_other_deduction as $pod) {
                    $u_pod = unserialize($pod['other_deductions']);

                    foreach($u_pod as $u_pod_k) {
                        if( $u_pod_k->getVariable() == 'tax_bonus_service_award' ) {
                            $service_tax_array[$eid]['service_award_tax'] += $u_pod_k->getAmount();
                        }
                        if( $u_pod_k->getVariable() == 'union_dues' ) {
                            $union_dues_array[$eid]['union_dues'] += $u_pod_k->getAmount();
                        }

                    }
                }

                $p_other_labels = G_Payslip_Helper::sqlGetEmployeesPayslipLabels($eid, $range_custom, $this->year);
                $less_tax_refund_array = array();
				foreach($p_other_labels as $pkey => $labels) {
					$uns_labels = unserialize($labels['labels']);
					foreach($uns_labels as $uns_data) {
						if($uns_data->getVariable() == 'tax_refund') {
							$less_tax_refund_array[$eid]['less_tax_refund'] += $uns_data->getValue();
						}
					}
				}

				/*
				 * Add HMO to yearly gross income
				*/
				$custom_yearly_hmo_premium = 0;
				$custom_yearly_hmo_premium_taxable = 0;
				$custom_yearly_hmo_premium_nontaxable = 0;
				$custom_sss_maternity_differential_taxable = 0;
				$custom_sss_maternity_differential_nontaxable = 0;
				$custom_other_deductions_earnings_taxable = 0;
				$custom_other_deductions_earnings_nontaxable = 0;
				$custom_taxable_compensation_previous_employer = 0;
				$custom_tax_withheld_previous_employer = 0;
				//Taxable Compensation Previous Employer
				//Tax Withheld Previous Employer 

				if(!empty($import_hmo_data)) {
					foreach($import_hmo_data as $ihmod_key => $ihmod) {

						
						//echo $ihmod['eid'] . " : " . $ihmod['amount'];
						if($ihmod['eid'] == $eid) {
								//hmo

								if( strtolower(trim($ihmod['earning_title'])) == 'hmo' || strtolower(trim($ihmod['earning_title'])) == 'hmo premium'){
									 if( strtolower(trim($ihmod['taxable'])) == 'yes'){

	

									 	$custom_yearly_hmo_premium_taxable = $ihmod['amount'];
									 }else{

									 	$custom_yearly_hmo_premium_nontaxable = $ihmod['amount'];
									 }
								}
								//sss maternity SSS Maternity Differential
								if( strtolower(trim($ihmod['earning_title'])) == 'sss maternity differential' || strtolower(trim($ihmod['earning_title'])) == 'sss mat dif'){

									 if( strtolower(trim($ihmod['taxable'])) == 'yes'){

		
									 	$custom_sss_maternity_differential_taxable = $ihmod['amount'];
									 }else{

									 	$custom_sss_maternity_differential_nontaxable = $ihmod['amount'];
									 }
								}


								// Other deductions / earnings = other compensation
									if( strtolower(trim($ihmod['earning_title'])) != 'sss maternity differential' && strtolower(trim($ihmod['earning_title'])) != 'sss mat dif' &&
										strtolower(trim($ihmod['earning_title'])) != 'hmo premium' && 
										strtolower(trim($ihmod['earning_title'])) != 'hmo' && 
										strtolower(trim($ihmod['earning_title'])) != 'taxable compensation previous employer' &&
										 strtolower(trim($ihmod['earning_title'])) != 'tax withheld previous employer' ){

									 if( strtolower(trim($ihmod['taxable'])) == 'yes'){
									 
											// other compensation
									 	$custom_other_deductions_earnings_taxable += $ihmod['amount'];
									 	// echo $ihmod['earning_title'] ."<br>";
									 	// echo "taxable<br>";
									 	// echo $custom_other_deductions_earnings_taxable."<br>";
									 	
									 }else{
									 	// other compensation
									 
									 	$custom_other_deductions_earnings_nontaxable += $ihmod['amount'];
									 	// echo $ihmod['earning_title']."<br>";
									 	// echo "nontaxable<br>";
									 	// echo $custom_other_deductions_earnings_nontaxable."<br>";
									 }
								}

								// Taxable Compensation Previous Employer
								if( strtolower(trim($ihmod['earning_title'])) == 'taxable compensation previous employer'){

											
									 	$custom_taxable_compensation_previous_employer = $ihmod['amount'];
								
								}

							//Tax Withheld Previous Employer 
							if( strtolower(trim($ihmod['earning_title'])) == 'tax withheld previous employer'){
									
								
									 	$custom_tax_withheld_previous_employer = $ihmod['amount'];
									 
								} 
								// echo $custom_sss_maternity_differential_taxable;
								// echo "--<br>";
								// echo $custom_sss_maternity_differential_nontaxable;
						

						}

					}
			 }
			

				// new get

					$p_v2 = G_Payslip_Finder::findAllByYearIn($this->year, $fields, $eid);
				

					// const TAXABLE = 1;
					// const NON_TAXABLE = 2;
					$total_basic_pay = 0;
					$position_total_allowance = 0;
					$c_rotpay = 0;
					$c_service_award_tax = 0;
					$real_position_allowance = 0;
					$c_bonus_tax = 0;
						$total_yearly_bonus = 0; 
					foreach ($p_v2 as $poe) {
						$total_basic_pay += $poe->getBasicPay();


						// earnings
						$custom_earnings = $poe->getEarnings();
						foreach ($custom_earnings as $ce) {
							if($ce->getVariable() == "total_regular_ot_amount"){
								$total_regular_ot_amount += $ce->getAmount();
							}
						}
					

						// other earnings
							$custom_other_earnings = $poe->getOtherEarnings();
							foreach ($custom_other_earnings as $coe) {

									// echo "<pre>";
									// var_dump($coe);
									// echo "</pre>";
							
									if($coe->getLabel() == "POSITION ALLOWANCE :500.00"){
											$position_total_allowance += $coe->getAmount();
												
									}
									if($coe->getLabel() == "POSITION ALLOWANCE :750.00"){
											$position_total_allowance += $coe->getAmount();
									}

										$temp_string = strtolower($coe->getVariable());

								if (strpos($temp_string, 'special transpo') !== false) {
								    $temp_string = "special transpo";

								}

								switch ($temp_string) {
								 		case 'bonus':
										// if($coe->getTaxType() == 2) {
										// 	$return[$payslip->getEmployeeId()]['bonus'] += $coe->getAmount();
										// }
										if($coe->getTaxType() == 1) {
											$c_bonus_tax += $coe->getAmount();
										}

										break;

									case 'service award':
										if( $coe->getTaxType() == 1 ){
											$c_service_award_tax += $coe->getAmount();
										}else{
											$service_award += $coe->getAmount();
										}									
										break;
								
									default:
											if( stripos(strtolower($coe->getVariable()), 'position allowance') !== false ){								
										$real_position_allowance += $coe->getAmount();
								
									}
										break;
								}

							} 
							$yearly_bonus = G_Yearly_Bonus_Release_Date_Helper::getEmployeeTotalBonusByYear($poe->getEmployeeId(), $this->year);
								if( !empty($yearly_bonus) ){
									$total_yearly_bonus = $yearly_bonus['total_bonus'];
								}
							

							$custom_payslips_hours = $poe->getLabels();
							// v2
							foreach( $custom_payslips_hours as $l ){



								switch (strtolower($l->getVariable())) {
									
									case 'restday_amount':								
										$c_rotpay += $l->getValue();
										break;
													
									case (strtolower($l->getVariable()) == 'holiday_legal_amount' || strtolower($l->getVariable()) == 'holiday_special_amount'):
									$c_rotpay += $l->getValue();		
									break;	


									default:
										# code...
										break;
								}

								if( stripos(strtolower($l->getLabel()), 'ot amount') !== false ){
									//echo $l->getLabel() . "/" . $l->getValue() . "<br />"; 	
									$c_rotpay += $l->getValue();
								}
								if( stripos(strtolower($l->getLabel()), 'ns amount') !== false ){
									//echo $l->getLabel() . "/" . $l->getValue() . "<br />"; 	
									$c_rotpay += $l->getValue();
								}	


												
							}
			
							$total_ot_hours = 0;
					}
					//get
				// 	each(array)cho $total_regular_ot_amount;
				// 	echo "<hr>";
				// 	// echo $earnings_data[$eid]['leave_converted_total_amount'];
				// // 	echo "<hr>";
				// 	echo $total_ot_hours = $regular_ot_amount + $regular_ns_ot_amount +$restday_ot_amount + $restday_ns_ot_amount + $restday_special_ot_amount + $restday_special_ns_ot_amount + $restday_legal_ot_amount + $restday_legal_ns_ot_amount + $holiday_special_ot_amount + $holiday_special_ns_ot_amount + $holiday_legal_ot_amount + $holiday_legal_ns_ot_amount;
			
		


			

				//$tmp_government_deduction = $migrated_data[$eid]['sum_pagibig'] + $migrated_data[$eid]['sum_sss'] + $migrated_data[$eid]['sum_philhealth'];
				//$new_gross_income   = $new_gross_income - ($p['total_sss'] + $p['total_pagibig'] + $p['total_philhealth'] + $tmp_government_deduction);

				//$new_gross_income   = $p['total_gross_pay'] + $new_bonus_amount + $earnings_data[$eid]['leave_converted_total_amount'] + $earnings_data[$eid]['service_award_taxable'];

				$new_gross_income   = $p['total_gross_pay'] + $new_bonus_amount + $earnings_data[$eid]['leave_converted_total_amount'] + $earnings_data[$eid]['service_award_taxable'] + $earnings_data[$eid]['bonus_taxable'] + $custom_yearly_hmo_premium +$other_taxable_earnings_total;


				// echo $new_gross_income;

					// other earnings;

				if($total_yearly_bonus > 90000 ){
      $custom_taxable_13th_month  = $total_yearly_bonus - 90000;
       $custom_non_taxable_13_month = 90000;
    }else{
                $custom_taxable_13th_month = 0;
                $custom_non_taxable_13_month = $total_yearly_bonus;
   } 

 
				//  new computation form taxable income
			
					// echo $total_basic_pay ."<br>";
					// echo $real_position_allowance ."<br>";
					// echo $earnings_data[$eid]['leave_converted_total_amount'] ."<br>";
					// echo $c_rotpay ."<br>";
					// echo $c_service_award_tax ."<br>";
					// echo $custom_sss_maternity_differential_taxable ."<br>";
					// // echo $total_yearly_bonus;
					// echo $new_taxable_income . "br";

					// echo "<hr>";



					// echo $new_gross_income;
				$new_gross_income_less_gov_contribution   = $new_gross_income - ($p['total_sss'] + $p['total_pagibig'] + $p['total_philhealth']);
					// $new_taxable_income = $total_basic_pay + $real_position_allowance + $earnings_data[$eid]['leave_converted_total_amount'] + $c_rotpay + $c_service_award_tax + $custom_sss_maternity_differential_taxable + $c_bonus_tax + $custom_taxable_13th_month;
				 $basic_salary = $p['total_gross_pay'] - ( $real_position_allowance + $earnings_data[$eid]['leave_converted_total_amount'] + $c_rotpay  );
				 $new_taxable_income = ($basic_salary) + $real_position_allowance + $earnings_data[$eid]['leave_converted_total_amount'] + $c_rotpay + $c_service_award_tax + $custom_sss_maternity_differential_taxable + $c_bonus_tax + $custom_taxable_13th_month;


				 // echo $basic_salary;
				 // echo "<br>";
				 // echo $real_position_allowance;
				 // echo "<br>";
				 // echo $earnings_data[$eid]['leave_converted_total_amount'];
				 // echo "<br>";
				 // echo $c_rotpay;
				 // echo "<br>";
				 // echo $c_service_award_tax;
				 // echo "<br>";
				 // echo $custom_sss_maternity_differential_taxable;
				 // echo "<br>";
				 // echo $c_bonus_tax;
				 // echo "<br>";
				 // echo $custom_taxable_13th_month;
				 // echo "<br>";
				 // echo ($p['total_sss'] + $p['total_pagibig'] + $p['total_philhealth'] + $union_dues_array[$eid]['union_dues']);
				 // echo "<br>";
				$payslip_data[$eid] = $p;
				$payslip_data[$eid]['13th_month']      = $bonus_data[$eid] + $migrated_data[$eid]['13th_month'];
				$payslip_data[$eid]['leave_converted'] = $earnings_data[$eid]['leave_converted_total_amount'];
				// $payslip_data[$eid]['new_total_gross_pay'] =  $new_gross_income + $lea;
				$payslip_data[$eid]['new_total_gross_pay'] =  $new_taxable_income + ($p['total_sss'] + $p['total_pagibig'] + $p['total_philhealth'] + $union_dues_array[$eid]['union_dues']);
				$payslip_data[$eid]['migrated_grosspay']   =  $migrated_data[$eid]['gross_pay'];
				$payslip_data[$eid]['migrated_taxwheld']   =  $migrated_data[$eid]['taxwheld'];

				$payslip_data[$eid]['hmo_annual_premium'] = $custom_yearly_hmo_premium;
				$payslip_data[$eid]['hmo_annual_premium_taxable'] = $custom_yearly_hmo_premium_taxable;
				$payslip_data[$eid]['hmo_annual_premium_nontaxable'] = $custom_yearly_hmo_premium_nontaxable;
				$payslip_data[$eid]['sss_maternity_differential_taxable'] = $custom_sss_maternity_differential_taxable;
				$payslip_data[$eid]['sss_maternity_differential_nontaxable'] = $custom_sss_maternity_differential_nontaxable;
				$payslip_data[$eid]['other_deductions_earnings_taxable'] = $custom_other_deductions_earnings_taxable;
				$payslip_data[$eid]['other_deductions_earnings_nontaxable'] = $custom_other_deductions_earnings_nontaxable;
				$payslip_data[$eid]['taxable_compensation_previous_employer'] = $custom_taxable_compensation_previous_employer;
				$payslip_data[$eid]['tax_withheld_previous_employer'] = $custom_tax_withheld_previous_employer;
				// echo $eid . " - " . $custom_yearly_hmo_premium_taxable . " - " . $custom_yearly_hmo_premium_nontaxable . "<br>";
				/*if( $eid == 22 ){
					//echo $migrated_data[$eid]['13th_month'] . "/" . $bonus_data[$eid] . "/" . $earnings_data[$eid]['leave_converted_total_amount'];
				}*/ 				
				
				//$payslip_data[$eid]['new_total_tax'] 	   =  $payslip_data[$eid]['total_tax'] + $migrated_data[$eid]['taxwheld'];
				$payslip_data[$eid]['new_total_tax']       =  ($payslip_data[$eid]['total_tax'] + $service_tax_array[$eid]['service_award_tax']) - $less_tax_refund_array[$eid]['less_tax_refund'];

					
				/*if( $eid == 24 ){
					echo $migrated_data[$eid]['13th_month'] . "/" . $bonus_data[$eid] . "/" . $earnings_data[$eid]['leave_converted_total_amount'];
					echo '<hr />';
					echo $payslip_data[$eid]['total_tax'] . " " . $service_tax_array[$eid]['service_award_tax'];
				}*/

				//Personal Exemption
				$pe = 0;
				$e  = G_Employee_Finder::findById($eid);

				/* Note: disable the personal excemption due to new tax computation
				if( $e ){						
					$tax = new Tax_Calculator();
					$tax->setNumberOfDependent($e->getNumberDependent());
					$pe  = $tax->getPersonalExemptionAmount();
				}
				*/
				
				$payslip_data[$eid]['personal_exemption'] = $pe;

				//Taxable Income
				$taxable_income = $new_gross_income_less_gov_contribution - $pe;
				$taxable_income = $new_taxable_income - ($p['total_sss'] + $p['total_pagibig'] + $p['total_philhealth']);
				$deduct_union_dues_to_taxable_income = false;
				if($deduct_union_dues_to_taxable_income) {
					$taxable_income = $taxable_income - $union_dues_array[$eid]['union_dues'];	
				}

			
				 $payslip_data[$eid]['taxable_income'] = $taxable_income;

				//$payslip_data[$eid]['taxable_income'] = $new_gross_incomev2;
				
				//Tax due
				
				/* 
				 * Old tax table year 2017 below 
				*/
				/*
				$tax = new Tax_Table_Annual();
				$tax_data = $tax->getAnnualTaxBracket($taxable_income);
				if( $tax_data ){
					$tax_due = $taxable_income - $tax_data['excess'];
					$tax_due = $tax_due * ($tax_data['percent'] / 100);
					$tax_due = $tax_due + $tax_data['first'];
				}else{
					$tax_due  = $taxable_income;
				}*/

				/*
				 * New Tax Due Computation
				 * New annual tax table year 2018 (government new tax rule) 
				*/
					$tax_due = 0;
				$tax = new Tax_Table_Annual();
				$tax_data = $tax->getAnnualTaxBracketHB563($taxable_income);
				if( $tax_data ){
					$dependents = 0;
					$tax_table  = Tax_Table_Factory::getRevisedTax(Tax_Table::ANNUAL);
			        $tax_due_compute = new Tax_Calculator;
			        $tax_due_compute->setTaxTable($tax_table);
			        $tax_due_compute->setTaxableIncome($taxable_income);
			        $tax_due_compute->setNumberOfDependent($dependents);
			        $tax_due = round($tax_due_compute->computeHB563(), 2);   					
				}else{
					$tax_due  = $taxable_income;
				}
				// New Tax Due Computation - end

				if( $taxable_income <= 0 ){
					$tax_due = 0;
				}

				$payslip_data[$eid]['annual_tax_data'] = $tax_data;
				$payslip_data[$eid]['tax_due']         = $tax_due;

				//$payslip_data[$eid]['tax_refund']      = $tax_due - ($p['total_tax'] + $migrated_data[$eid]['taxwheld']);
				$payslip_data[$eid]['tax_refund']      = $tax_due - (($payslip_data[$eid]['total_tax'] + $service_tax_array[$eid]['service_award_tax']) - $less_tax_refund_array[$eid]['less_tax_refund']);
				


			
			}

			//Utilities::displayArray($payslip_data);
			//echo '<hr />';
			
			$bulk_data = $this->createBulkInsertData($payslip_data);

			// echo "<pre>";
			// var_dump($bulk_data);
			// echo "</pre>";

			//Utilities::displayArray($bulk_data);
			//exit;
			$payslip_data[$eid]['hmo_premium_taxable'] = 1;
			$payslip_data[$eid]['hmo_premium_nontaxable'] = 2;
			$fields = array('employee_id','year','from_date','to_date','gross_income_tax','less_personal_exemption','taxable_income','tax_due','tax_withheld_payroll','tax_refund_payable','cutoff_start_date','cutoff_end_date','date_created','hmo_premium','hmo_premium_taxable','hmo_premium_nontaxable','sss_maternity_differential_taxable','sss_maternity_differential_nontaxable','other_deductions_earnings_taxable','other_deductions_earnings_nontaxable','taxable_compensation_previous_employer','tax_withheld_previous_employer');
			// echo "---";
			// echo "<pre>";
			// var_dump($fields);
			// echo "</pre>";
			
			$total_inserted = G_Employee_Annualize_Tax_Manager::bulkInsertData($bulk_data, $fields);
			$return = array('is_success' => true, 'message' => "{$total_inserted} record(s) processed");
		}

		return $return;
	}

	public function customConfiEmployeePayslipJanuary() {
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
		
		$emp_payslip_a[3]['basic_pay']        = 11125.00;
		$emp_payslip_a[3]['total_overtime']   = 810.00;
		$emp_payslip_a[3]['total_tax']        = 2893.33;
		$emp_payslip_a[3]['total_tardiness']  = 0;
		$emp_payslip_a[3]['total_sss']        = 581.30;
		$emp_payslip_a[3]['total_pagibig']    = 2317.41;
		$emp_payslip_a[3]['total_philhealth'] = 275.00;
		$emp_payslip_a[3]['total_gross_pay']  = 27370.00;

		$emp_payslip_a[20]['basic_pay']        = 15860.00;
		$emp_payslip_a[20]['total_overtime']   = 3021.60;
		$emp_payslip_a[20]['total_tax']        = 5607.50;
		$emp_payslip_a[20]['total_tardiness']  = 3.04;
		$emp_payslip_a[20]['total_sss']        = 581.30;
		$emp_payslip_a[20]['total_pagibig']    = 1728.20;
		$emp_payslip_a[20]['total_philhealth'] = 387.50;
		$emp_payslip_a[20]['total_gross_pay']  = 40238.56;		

		$emp_payslip_a[94]['basic_pay']        = 14760.00;
		$emp_payslip_a[94]['total_overtime']   = 3021.60;
		$emp_payslip_a[94]['total_tax']        = 4569.31;
		$emp_payslip_a[94]['total_tardiness']  = 366.41;
		$emp_payslip_a[94]['total_sss']        = 581.30;
		$emp_payslip_a[94]['total_pagibig']    = 3418.96;
		$emp_payslip_a[94]['total_philhealth'] = 362.50;
		$emp_payslip_a[94]['total_gross_pay']  = 34603.59;

		$emp_payslip_a[170]['basic_pay']        = 11710.00;
		$emp_payslip_a[170]['total_overtime']   = 0;
		$emp_payslip_a[170]['total_tax']        = 3478.80;
		$emp_payslip_a[170]['total_tardiness']  = 0;
		$emp_payslip_a[170]['total_sss']        = 581.30;
		$emp_payslip_a[170]['total_pagibig']    = 0;
		$emp_payslip_a[170]['total_philhealth'] = 287.50;
		$emp_payslip_a[170]['total_gross_pay']  = 27470.00;

		$emp_payslip_a[69]['basic_pay']        = 10710.00;
		$emp_payslip_a[69]['total_overtime']   = 1920.00;
		$emp_payslip_a[69]['total_tax']        = 3487.45;
		$emp_payslip_a[69]['total_tardiness']  = 0;
		$emp_payslip_a[69]['total_sss']        = 581.30;
		$emp_payslip_a[69]['total_pagibig']    = 0;
		$emp_payslip_a[69]['total_philhealth'] = 287.50;
		$emp_payslip_a[69]['total_gross_pay']  = 27470.00;

		$emp_payslip_a[45]['basic_pay']        = 10460.00;
		$emp_payslip_a[45]['total_overtime']   = 0;
		$emp_payslip_a[45]['total_tax']        = 2145.20;
		$emp_payslip_a[45]['total_tardiness']  = 0;
		$emp_payslip_a[45]['total_sss']        = 581.30;
		$emp_payslip_a[45]['total_pagibig']    = 1971.11;
		$emp_payslip_a[45]['total_philhealth'] = 250.00;
		$emp_payslip_a[45]['total_gross_pay']  = 24629.18;

		$emp_payslip_a[324]['basic_pay']        = 35000.00; //17500.00;
		$emp_payslip_a[324]['total_overtime']   = 0;
		$emp_payslip_a[324]['total_tax']        = 7347.02;
		$emp_payslip_a[324]['total_tardiness']  = 0;
		$emp_payslip_a[324]['total_sss']        = 581.30;
		$emp_payslip_a[324]['total_pagibig']    = 0;
		$emp_payslip_a[324]['total_philhealth'] = 437.50;
		$emp_payslip_a[324]['total_gross_pay']  = 37100.00; //39110.00;

		$emp_payslip_a[24]['basic_pay']        = 28735.00;
		$emp_payslip_a[24]['total_overtime']   = 0;
		$emp_payslip_a[24]['total_tax']        = 13617.85;
		$emp_payslip_a[24]['total_tardiness']  = 156.99;
		$emp_payslip_a[24]['total_sss']        = 581.30;
		$emp_payslip_a[24]['total_pagibig']    = 7654.93;
		$emp_payslip_a[24]['total_philhealth'] = 437.50;
		$emp_payslip_a[24]['total_gross_pay']  = 59413.01; //61623.01;


		$emp_payslip_a[14]['basic_pay']        = 11870.00;
		$emp_payslip_a[14]['total_overtime']   = 0;
		$emp_payslip_a[14]['total_tax']        = 2020.16;
		$emp_payslip_a[14]['total_tardiness']  = 0;
		$emp_payslip_a[14]['total_sss']        = 581.30;
		$emp_payslip_a[14]['total_pagibig']    = 0;
		$emp_payslip_a[14]['total_philhealth'] = 287.50;
		$emp_payslip_a[14]['total_gross_pay']  = 31550.00;

		$emp_payslip_a[13]['basic_pay']        = 13595.00;
		$emp_payslip_a[13]['total_overtime']   = 5880.00;
		$emp_payslip_a[13]['total_tax']        = 5079.95;
		$emp_payslip_a[13]['total_tardiness']  = 0;
		$emp_payslip_a[13]['total_sss']        = 581.30;
		$emp_payslip_a[13]['total_pagibig']    = 1391.66;
		$emp_payslip_a[13]['total_philhealth'] = 337.50;
		$emp_payslip_a[13]['total_gross_pay']  = 39525.52;

		$emp_payslip_a[171]['basic_pay']        = 15960.00;
		$emp_payslip_a[171]['total_overtime']   = 450.00;
		$emp_payslip_a[171]['total_tax']        = 4694.81;
		$emp_payslip_a[171]['total_tardiness']  = 15.30;
		$emp_payslip_a[171]['total_sss']        = 581.30;
		$emp_payslip_a[171]['total_pagibig']    = 0;
		$emp_payslip_a[171]['total_philhealth'] = 387.50;
		$emp_payslip_a[171]['total_gross_pay']  = 36424.70;

		$emp_payslip_a[31]['basic_pay']        = 8730.00;
		$emp_payslip_a[31]['total_overtime']   = 0;
		$emp_payslip_a[31]['total_tax']        = 1859.66;
		$emp_payslip_a[31]['total_tardiness']  = 0;
		$emp_payslip_a[31]['total_sss']        = 581.30;
		$emp_payslip_a[31]['total_pagibig']    = 1666.98;
		$emp_payslip_a[31]['total_philhealth'] = 212.50;
		$emp_payslip_a[31]['total_gross_pay']  = 21099.18;

		$emp_payslip_a[29]['basic_pay']        = 9185.00;
		$emp_payslip_a[29]['total_overtime']   = 5033.50;
		$emp_payslip_a[29]['total_tax']        = 3325.20;
		$emp_payslip_a[29]['total_tardiness']  = 0;
		$emp_payslip_a[29]['total_sss']        = 581.30;
		$emp_payslip_a[29]['total_pagibig']    = 0;
		$emp_payslip_a[29]['total_philhealth'] = 225.00;
		$emp_payslip_a[29]['total_gross_pay']  = 27623.50;

		$emp_payslip_a[12]['basic_pay']        = 13345.00;
		$emp_payslip_a[12]['total_overtime']   = 5196.24;
		$emp_payslip_a[12]['total_tax']        = 5158.94;
		$emp_payslip_a[12]['total_tardiness']  = 0;
		$emp_payslip_a[12]['total_sss']        = 581.30;
		$emp_payslip_a[12]['total_pagibig']    = 0;
		$emp_payslip_a[12]['total_philhealth'] = 325.00;
		$emp_payslip_a[12]['total_gross_pay']  = 36479.90;


		$emp_payslip_a[5]['basic_pay']        = 10055.00;
		$emp_payslip_a[5]['total_overtime']   = 0;
		$emp_payslip_a[5]['total_tax']        = 1979.71;
		$emp_payslip_a[5]['total_tardiness']  = 0;
		$emp_payslip_a[5]['total_sss']        = 581.30;
		$emp_payslip_a[5]['total_pagibig']    = 4317.56;
		$emp_payslip_a[5]['total_philhealth'] = 250.00;
		$emp_payslip_a[5]['total_gross_pay']  = 23970.00;	

		return $emp_payslip_a;
	}			

	public function annualizeTaxCustomize( $employee_ids = array() ) {
		$data   = array();
		$return = array('is_success' => false, 'message' => 'No records processed');

		if( $this->year > 0 && $this->continue ){
			$range = array('from' => $this->from_date, 'to' => $this->to_date);

			//We need to delete existing data before recreate
			G_Employee_Annualize_Tax_Manager::deleteExistingDataByEmployeeIdsAndDateRange($data['employee_ids'], $range);						

			//Payslip Data
			$fields   = array('employee_id','basic_pay','SUM(overtime)AS total_overtime', 'SUM(withheld_tax)AS total_tax','SUM(tardiness_amount)AS total_tardiness','SUM(sss)AS total_sss','SUM(pagibig)AS total_pagibig','SUM(philhealth)AS total_philhealth','SUM(gross_pay)AS total_gross_pay');
			$group_by = 'GROUP BY employee_id';

			//$payslip  				             = G_Payslip_Helper::sqlGetEmployeesPayslipDataByYearAndDateRange($this->year, $employee_ids, $range, $fields, $group_by);
			$payslip_not_included_confi_employee = G_Payslip_Helper::sqlGetEmployeesPayslipDataByYearAndDateRangeNotIncludedConfiEmployee($this->year, $employee_ids, $range, $fields, $group_by);
			$payslip_confi_employee_and_remove_january = G_Payslip_Helper::sqlGetEmployeesPayslipDataByYearAndDateRangeConfiEmployeeAndRemoveJanuary($this->year, $employee_ids, $range, $fields, $group_by);

			$manual_payslip_changes = $this->customConfiEmployeePayslipJanuary();

			$payslip_combine = array_merge($payslip_not_included_confi_employee,$payslip_confi_employee_and_remove_january);

			$payslip = array(); 
			foreach($payslip_combine as $rdata) {
				$payslip[$rdata['employee_id']] = $rdata;
			}			

			/*
			* Add payslip data manually on the annualize tax (for the month of january 2016 Only) - start
			*/

			foreach($manual_payslip_changes as $mpkey => $mpkeyd) {

				if( !empty($payslip[$mpkey]) ) {

					$payslip[$mpkey]['total_overtime']   += $mpkeyd['total_overtime'];
					$payslip[$mpkey]['total_tax']  		 += $mpkeyd['total_tax'];
					$payslip[$mpkey]['total_tardiness']  += $mpkeyd['total_tardiness'];
					$payslip[$mpkey]['total_sss']        += $mpkeyd['total_sss'];
					$payslip[$mpkey]['total_pagibig']    += $mpkeyd['total_pagibig'];
					$payslip[$mpkey]['total_philhealth'] += $mpkeyd['total_philhealth'];
					$payslip[$mpkey]['total_gross_pay']  += $mpkeyd['total_gross_pay'];

				} 
			}

			/*
			* Add payslip data manually on the annualize task (for the month of january 2016 Only) - end
			*/				

			//Utilities::displayArray($payslip);
			//exit;

			//Leave Converted
			/*$fields   = array('ea.object_id AS employee_id','SUM(ea.amount)AS leave_converted_total_amount','SUM(IF(ea.is_taxable = "Yes",ea.amount,0))AS leave_converted_taxable_amount','SUM(IF(ea.is_taxable = "No",ea.amount,0))AS leave_converted_nontaxable_amount');*/
			$fields   = array('ea.object_id AS employee_id','SUM(ea.amount)AS leave_converted_total_amount');
			$group_by = 'GROUP BY ea.object_id';
			$earnings = G_Employee_Earnings_Helper::sqlGetEmployeesLeaveTaxableConvertedToCashByYearAndDateRange($this->year, $employee_ids, $range, $fields,$group_by);						
			$earnings_data = array();
			foreach( $earnings as $e ){														
				$earnings_data[$e['employee_id']]['leave_converted_total_amount'] = $e['leave_converted_total_amount'];
			}

			//Service Award
			$fields   = array('ea.object_id AS employee_id','SUM(ea.amount)AS service_award_taxable');
			$group_by = 'GROUP BY ea.object_id';
			$earnings = G_Employee_Earnings_Helper::sqlGetEmployeesServiceAwardTaxableByYearAndDateRange($this->year, $employee_ids, $range, $fields,$group_by);
			foreach( $earnings as $e ){														
				$earnings_data[$e['employee_id']]['service_award_taxable'] = $e['service_award_taxable'];
			}

			/*Utilities::displayArray($earnings_data);
			exit;*/

			//Yearly Bonus
			$fields = array('employee_id','SUM(amount)AS yearly_bonus_total_amount');
			$bonus  = G_Yearly_Bonus_Release_Date_Helper::sqlGetEmployeesYearlyBonusByYearAndDateRange($this->year, $employee_ids, $range, $fields);				
			$bonus_data = array();
			foreach( $bonus as $b ){
				$bonus_data[$b['employee_id']] = $b['yearly_bonus_total_amount'];	
			}

			//Utilities::displayArray($bonus_data);
			//exit;

			//Temp Data
			$migrated_data = array();
			$migrated  = new G_Migrate_Data();
			$temp_data = $migrated->getAllMigratedData($employee_ids);

			foreach( $temp_data as $td ){
				$migrated_data[$td['employee_id']][$td['field']] = $td['amount'];	
				$migrated_data[$td['employee_id']]['field'] = $td['field'];	
			}

			//Utilities::displayArray($temp_data);
			//exit;
			
			$payslip_data = array();			
			foreach($payslip as $p){
				$eid = $p['employee_id'];
				unset($p['employee_id']);

				$total_yearly_bonus = $migrated_data[$eid]['13th_month'] + $bonus_data[$eid];

				if( $total_yearly_bonus >= self::CEILING_BONUS ){
					$new_bonus_amount = $total_yearly_bonus - self::CEILING_BONUS;
				}else{
					$new_bonus_amount = 0;
				}

				if($eid == 324) {}

			//if($eid != 324) {
				if($this->year == 2016) {
					$range_custome = array('from' => '2016-12-01', 'to' => '2016-12-31');
					$p_other_deduction = G_Payslip_Helper::sqlGetEmployeesPayslipOtherDeduction($eid, $range_custome, $this->year);

					$service_tax_array = array();
					foreach($p_other_deduction as $pod) {
						$u_pod = unserialize($pod['other_deductions']);
						foreach($u_pod as $u_pod_k) {
							if( $u_pod_k->getVariable() == 'tax_bonus_service_award' ) {
								$service_tax_array[$eid]['service_award_tax'] += $u_pod_k->getAmount();
								//echo $u_pod_k->getVariable() . ' = ' . $u_pod_k->getAmount() . '<br />';
							}
						}
					}

					//echo 'this is it: '. $service_tax_array[$eid]['service_award_tax'];
				}
			//}

				//$new_gross_income   = $p['total_gross_pay'] + $new_bonus_amount + $earnings_data[$eid]['leave_converted_total_amount'] + $migrated_data[$eid]['gross_pay'] + $earnings_data[$eid]['service_award_taxable'];
				$new_gross_income   = $p['total_gross_pay'] + $new_bonus_amount + $earnings_data[$eid]['leave_converted_total_amount'] + $earnings_data[$eid]['service_award_taxable'];

				$tmp_government_deduction = $migrated_data[$eid]['sum_pagibig'] + $migrated_data[$eid]['sum_sss'] + $migrated_data[$eid]['sum_philhealth'];
				
				//$new_gross_income   = $new_gross_income - ($p['total_sss'] + $p['total_pagibig'] + $p['total_philhealth'] + $tmp_government_deduction);
				$new_gross_income   = $new_gross_income - ($p['total_sss'] + $p['total_pagibig'] + $p['total_philhealth']);


				/*
					Note: This is for 2016 annualize tax changes
				*/
				if($this->year == 2016) {

					if($eid == 324) {
						$new_gross_income += 15027.50;
					}

					if($eid == 24) {
						$new_gross_income += 63750.50;
					}				

				}

				$payslip_data[$eid] = $p;
				$payslip_data[$eid]['13th_month']      = $bonus_data[$eid] + $migrated_data[$eid]['13th_month'];
				$payslip_data[$eid]['leave_converted'] = $earnings_data[$eid]['leave_converted_total_amount'];
				$payslip_data[$eid]['new_total_gross_pay'] =  $new_gross_income;
				$payslip_data[$eid]['migrated_grosspay']   =  $migrated_data[$eid]['gross_pay'];
				$payslip_data[$eid]['migrated_taxwheld']   =  $migrated_data[$eid]['taxwheld'];
				
				//$payslip_data[$eid]['new_total_tax'] 	   =  $payslip_data[$eid]['total_tax'] + $migrated_data[$eid]['taxwheld'];
				$payslip_data[$eid]['new_total_tax'] 	   =  $payslip_data[$eid]['total_tax'] + $service_tax_array[$eid]['service_award_tax'];

				//Personal Exemption
				$pe = 0;
				$e  = G_Employee_Finder::findById($eid);
				if( $e ){						
					$tax = new Tax_Calculator();
					$tax->setNumberOfDependent($e->getNumberDependent());
					$pe  = $tax->getPersonalExemptionAmount();
				}
				$payslip_data[$eid]['personal_exemption'] = $pe;

				//Taxable Income
				$taxable_income = $new_gross_income - $pe;

				$payslip_data[$eid]['taxable_income'] = $taxable_income;

				//Tax due
				$tax = new Tax_Table_Annual();
				$tax_data = $tax->getAnnualTaxBracket($taxable_income);					
				if( $tax_data ){
					$tax_due = $taxable_income - $tax_data['excess'];
					$tax_due = $tax_due * ($tax_data['percent'] / 100);
					$tax_due = $tax_due + $tax_data['first'];
				}else{
					$tax_due  = $taxable_income;
				}

				if( $taxable_income <= 0 ){
					$tax_due = 0;
				}

				$payslip_data[$eid]['annual_tax_data'] = $tax_data;
				$payslip_data[$eid]['tax_due']         = $tax_due;
				//$payslip_data[$eid]['tax_refund']      = $tax_due - ($p['total_tax'] + $migrated_data[$eid]['taxwheld']);
				$payslip_data[$eid]['tax_refund']      = $tax_due - ($payslip_data[$eid]['total_tax'] + $service_tax_array[$eid]['service_award_tax']);

			}

			//Utilities::displayArray($service_tax_array);
			//exit;

			$bulk_data = $this->createBulkInsertData($payslip_data);

			$fields = array('employee_id','year','from_date','to_date','gross_income_tax','less_personal_exemption','taxable_income','tax_due','tax_withheld_payroll','tax_refund_payable','cutoff_start_date','cutoff_end_date','date_created');
			$total_inserted = G_Employee_Annualize_Tax_Manager::bulkInsertData($bulk_data, $fields);

			$return = array('is_success' => true, 'message' => "{$total_inserted} record(s) processed");
		}

		return $return;
	}


	private function createBulkInsertData( $data = array() ) {
		$return = array();

		if( !empty($data) ){
			foreach( $data as $eid => $values ){
				$created  = date("Y-m-d H:i:s");
				//$return[] = "(" . Model::safeSql($eid) . "," . Model::safeSql($this->year) . "," . Model::safeSql($this->from_date) . "," . Model::safeSql($this->to_date) . "," . Model::safeSql($values['new_total_gross_pay']) . "," . Model::safeSql($values['personal_exemption']) . "," . Model::safeSql($values['taxable_income']) . "," . Model::safeSql($values['tax_due']) . "," . Model::safeSql($values['new_total_tax']) . "," . Model::safeSql($values['tax_refund']) . "," . Model::safeSql($this->cutoff_start_date) . "," . Model::safeSql($this->cutoff_end_date) . "," . Model::safeSql($created) . ")";
				$return[] = "(" . Model::safeSql($eid) . "," . Model::safeSql($this->year) . "," . Model::safeSql($this->from_date) . "," . Model::safeSql($this->to_date) . "," . Model::safeSql($values['new_total_gross_pay']) . "," . Model::safeSql($values['personal_exemption']) . "," . Model::safeSql($values['taxable_income']) . "," . Model::safeSql($values['tax_due']) . "," . Model::safeSql($values['new_total_tax']) . "," . Model::safeSql($values['tax_refund']) . "," . Model::safeSql($this->cutoff_start_date) . "," . Model::safeSql($this->cutoff_end_date) . "," . Model::safeSql($created) . "," . Model::safeSql($values['hmo_annual_premium']) . ",".Model::safeSql($values['hmo_annual_premium_taxable']).",".$values['hmo_annual_premium_nontaxable'].",".$values['sss_maternity_differential_taxable'].",".$values['sss_maternity_differential_nontaxable'].",".$values['other_deductions_earnings_taxable'].",".$values['other_deductions_earnings_nontaxable'].",".$values['taxable_compensation_previous_employer'].",".$values['tax_withheld_previous_employer'].")";
			}
		}

		return $return;
	}
							
	public function save() {
		return G_Employee_Annualize_Tax_Manager::save($this);
	}

	public function delete() {
		return G_Employee_Annualize_Tax_Manager::delete($this);
	}
}
?>