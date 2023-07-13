<?php

	class G_Monthly_Cutoff_Period{
		protected $id;
	protected $year_tag;
	protected $start_date;
	protected $end_date;
	protected $payout_date;
    protected $cutoff_number;
	protected $salary_cycle_id;
	protected $is_lock;
	protected $number_of_months;
    protected $is_payroll_generated = self::NO;
    
	
	const YES = 'Yes';
	const NO  = 'No';
	
	const YEAR_START = 2000;
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;	
	}

    public function setPayrollAsGenerated() {
        $this->is_payroll_generated = self::YES;
    }

    public function isPayrollGenerated() {
        if ($this->is_payroll_generated == self::YES) {
            return true;
        } else {
            return false;
        }
    }

    public function setNumberOfMonths($value = 0){
    	$this->number_of_months = (int)$value;
    	return $this;
    }

    public function setIsPayrollGenerated($value) {
        $this->is_payroll_generated = $value;
    }

    public function getIsPayrollGenerated() {
        return $this->is_payroll_generated;
    }
	
	public function getId() {
		return $this->id;	
	}

    public function setCutoffNumber($value) {
        $this->cutoff_number = $value;
    }

    public function getCutoffNumber() {
        return $this->cutoff_number;
    }

    /*
     * @return A or B
     */
    public function getCutoffCharacter() {
        $cutoff_number = $this->getCutoffNumber();
        if ($cutoff_number == 1) {
            return 'A';
        } else if ($cutoff_number == 2) {
            return 'B';
        }
    }
	
	/*
		@param $value 'YYYY-MM-DD'
	*/
	
	public function setYearTag($value) {
		$this->year_tag = $value;	
		return $this;
	}

	public function getYearTag() {
		return $this->year_tag;	
	}	
	
	public function setStartDate($value) {
		$value = date("Y-m-d",strtotime($value));
		$this->start_date = $value;
	}

    public function getMonthOld() {
        return date('F', strtotime($this->start_date));
    }

    public function getMonth() {
        return date('F', strtotime($this->end_date));
    }

    public function getMonth2() {
        return date('F', strtotime($this->start_date));
    }

	public function getStartDate() {
		return $this->start_date;	
	}
	
	public function setEndDate($value) {
		$value = date("Y-m-d",strtotime($value));
		$this->end_date = $value;	
	}
	
	public function getEndDate() {
		return $this->end_date;	
	}
	
	public function setPayoutDate($value) {
		$this->payout_date = $value;	
	}
	
	public function getPayoutDate() {
		return $this->payout_date;	
	}
	
	public function setSalaryCycleId($value) {
		$this->salary_cycle_id = $value;	
	}
	
	public function getSalaryCycleId() {
		return $this->salary_cycle_id;	
	}
	
	public function setIsLock($value) {
		$this->is_lock = $value;	
	}
	
	public function getIsLock() {
		return $this->is_lock;	
	}

    public function isLocked() {
        if ($this->is_lock == self::YES) {
            return true;
        } else {
            return false;
        }
    }



    public function getCutoffDataByStartAndEndDate($fields = array()) {
    	$data = array();

    	if( $this->start_date != '' && $this->end_date != '' ){
    		$data = G_Monthly_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd( $this->start_date, $this->end_date, $fields );
    	} 

    	return $data;
    }



    function generateJanuaryCutoff($year){
		$sv = new G_Monthly_Cutoff_Period();
		$m = '01';
		$expected_cutoff = $sv->expectedCutOffPeriodsByMonthAndYear($m, $year);        
		$fields = array("payout_day");
		$default_pay_out = G_Settings_Pay_Period_Helper::findByCode($fields);  


		$payout_days = explode(",", $default_pay_out['payout_day']);



		foreach($expected_cutoff as $exp_key => $exp_c) {
			$start_cutoff_date = $exp_c['start_date'];
			$end_cutoff_date   = $exp_c['end_date'];

			$month = new DateTime($end_cutoff_date);
			
			$month = $month->format("m");

			$cutoff_number = 1;
			$payout_date   = $year . "-" . $month . "-" . $payout_days[0];

			

			$cutoff_exist = G_Monthly_Cutoff_Period_Helper::isCutoffPeriodStartAndEndExists($start_cutoff_date, $end_cutoff_date);
			
			if(!$cutoff_exist) {
				//echo $start_cutoff_date . ' : ' . $end_cutoff_date;
				$sv->setYearTag($year);
				$sv->setStartDate($start_cutoff_date);
				$sv->setEndDate($end_cutoff_date);
				$sv->setPayoutDate($payout_date);
				$sv->setCutoffNumber($cutoff_number);
				$sv->setSalaryCycleId(2);
				$sv->setIsPayrollGenerated(G_Monthly_Cutoff_Period::NO);
				$sv->setIsLock(G_Monthly_Cutoff_Period::NO);     
				$sv->save();   		
			}
		}
	}




    	public function generateIniCutOffPeriods( $data ) {

    			//$payoutday = $data[1]['payoutday'];

    			$year = date('Y');
    			$year = new DateTime($year);
    			$year = $year->format('Y');



    			self::generateJanuaryCutoff($year);

    			$return['is_success'] = false;
				$return['message']    = "Cannot create records";
				//$insert_values = array();

				if( !empty($data) ){
    
					$start_cutoff = $data[1]['a'];
					$end_cutoff = $data[1]['b'];
					$payoutday = $data[1]['payoutday'];


					$c   = new G_Monthly_Cutoff_Period();

					if( $this->number_of_months > 0 ){
							$number_of_cutoff_to_generate = $this->number_of_months;
						}else{
							$number_of_cutoff_to_generate = 4; 
						}


				  $pattern[] = "{$start_cutoff}-{$end_cutoff}";



				for( $counter = 0; $counter < $number_of_cutoff_to_generate; $counter++ ){		

					   $current_year = date("Y");

						$date  = "{$current_year}-12-01";

						$date = new DateTime("{$date} -{$counter} months");
						$date =	$date->format("Y-m-1");


						$month = new DateTime($date);
						$month = $month->format("m");

						$year = new DateTime($date);
						$year = $year->format("Y");

						//$date  = date("Y-m-1",strtotime("{$date} -{$counter} months"));						
						//$month = date("m",strtotime($date));
						//$year  = date("Y",strtotime($date));

						$first_date  = "{$year}-{$month}-{$start_cutoff}";
						$second_date = "{$year}-{$month}-{$end_cutoff}";


						$first_cutoff  = Tools::getCutOffPeriodMonthly($first_date, $pattern);

						$first_year = new DateTime($first_cutoff['end']);
						$first_year = $first_year->format("Y");

						$first_month = new DateTime($first_cutoff['end']);
						$first_month = $first_month->format("m");

						
						//$first_year   = date("Y",strtotime($first_cutoff['end']));
						//$first_month  = date("m",strtotime($first_cutoff['end']));

						//getting payout day values

						if( ($payoutday == 31) || ($first_month == 2 && $payoutday > 28) ){

							$first_payday = new DateTime($first_cutoff['end']);
							$first_payday = $first_payday->format("Y-m-t");
							//$first_payday = date("Y-m-t", strtotime($first_cutoff['end']));

						}else{
							$first_payday  = "{$first_year}-{$first_month}-{$payoutday}";
						}



						

						//First Cutoff
			                $c = G_Monthly_Cutoff_Period_Finder::findAllCutoffByYear($current_year);

							$is_exists = G_Monthly_Cutoff_Period_Helper::isCutoffPeriodStartAndEndExists($first_cutoff['start'], $first_cutoff['end']);			
							if( !$is_exists ){
								//$year = date("Y",strtotime($first_cutoff['end']));
								$year = new DateTime($first_cutoff['end']);
								$year = $year->format("Y");
								
								$gcp = new G_Monthly_Cutoff_Period();
								$gcp->setYearTag($year);
								$gcp->setStartDate($first_cutoff['start']);
								$gcp->setEndDate($first_cutoff['end']);
								$gcp->setCutoffNumber($first_cutoff['cutoff_number']);
								$gcp->setPayoutDate($first_payday);
								$gcp->setSalaryCycleId(G_Salary_Cycle::TYPE_SEMI_MONTHLY);
								$gcp->setIsLock(G_Monthly_Cutoff_Period::NO);
								$gcp->save();
							}

						$cutoffs[] = $first_cutoff['start'] . " - " . $first_cutoff['end']  ;

					}//for loop


					$return['is_success'] = true;
					$return['message']    = "Cutoff periods was successfully created";
					$return['cutoffs']    = $cutoffs;


				}//end if $data



		} //end generateIniCutOffPeriods


 public function expectedMonthlyCutOffPeriodsByMonthAndYear( $month = 0, $year ){    	
    	$data = array();
    	if( $month > 0 && !empty($year) ){
    		$fields = array("cut_off");
    			// $month = 2;
    			// $year = '2020';
    			$cutoff_periods = G_Monthly_Cutoff_Period_Helper::getExpectedCutoffsByMonthAndYear($month,$year);
    				$data = $cutoff_periods;

    	}

    	return $data;
    }	




	public function getMonthMonthlyCutoffPeriods( $month = '', $year = '' ) {
    	$cutoff_periods = array();
    	$data = self::expectedMonthlyCutOffPeriodsByMonthAndYear($month, $year);
    	
    	if( !empty($data) ){
    		foreach( $data as $key => $value ){
    			// echo "<pre>";
    			// var_dump($value);
    			// echo "</pre>";
    			$cutoff_data = G_Monthly_Cutoff_Period_Finder::findByPeriod($value['period_start'], $value['period_end']);
    			if( !empty($cutoff_data) ){
    				$cutoff_periods[$key] = $cutoff_data;
    			}    			
    		} 
    	}

    	return $cutoff_periods;
    }




    public function lockPayrollPeriod() {	
    	
		$return   = array();
		$is_debug = false;

		if( $this->id > 0 ){
			$date_from = $this->start_date;
	        $date_to   = $this->end_date;

			$fields = array("p.employee_id","p.gross_pay");
			$processed_payslip_data = G_Payslip_Helper::sqlProcessedPayslipByDateRange($date_from, $date_to, $fields);
			foreach( $processed_payslip_data as $payslip ){
				//Update loans table				
	            $gross       = $payslip['gross_pay'];
	            $employee_id = $payslip['employee_id'];
	            $l = new G_Employee_Loan();
	            $l->setEmployeeId($employee_id);
	            $result_loans = $l->setGrossPay($gross)->applyLoansGrossPayLimit()->getScheduledUnpaidLoans( array('date_from' => $date_from, 'date_to' => $date_to) )->adjustEmployeeLoansDeductionBaseOnGrossPay()->updateLoanSchedule();
				//End loans table
			}

			if( $is_debug ){
				echo "Start date : {$date_from} / End date : {$date_to}";
				Utilities::displayArray($processed_payslip_data);
			}

			$return = G_Monthly_Cutoff_Period_Manager::lockPayrollPeriod($this);
		}

		return $return;
	}




  public function expectedCutOffPeriodsByMonthAndYear( $month = 0, $year ){ 



    	$data = array();
    	if( $month > 0 && !empty($year) ){
    		$fields = array("cut_off");
    		$default_pay_period = G_Settings_Pay_Period_Helper::sqlMonthlyPayPeriod($fields);

    		if( !empty($default_pay_period) ){  

    			$cutoff_data = explode(",", trim($default_pay_period['cut_off']));    
    			$pattern[]   = $cutoff_data[0];
				//$pattern[]   = $cutoff_data[1];
				$cutoff_data = array_filter($cutoff_data); 


    			foreach( $cutoff_data as $key => $cutoff ){    				    				
    				$days_cutoff = explode("-", $cutoff);
					$start_day   = $days_cutoff[0];
					$end_day     = $days_cutoff[1];
					 

					
					
					
					$cutoff_start_date = $year.'-'.$month.'-'.$start_day;
					//$cutoff_start_date = date("Y-m-d", strtotime($cutoff_start_date));
					

					$cutoff_start_date  = new DateTime($cutoff_start_date);


					$cutoff_start_date = $cutoff_start_date->format("Y-m-d");




					
					if( ($month == 2 && $end_day >= 30 ) || $end_day > 30 ){																					
						//$cutoff_end_date = date("Y-m-t", strtotime("{$year}-{$month}-1"));
						$cutoff_end_date = new DateTime("{$year}-{$month}-1");
						$cutoff_end_date =  $cutoff_end_date->format("Y-m-t");

						


					}else{
						$cutoff_end_date   = new DateTime("{$year}-{$month}-{$end_day}");
						//$cutoff_end_date = date("Y-m-d", strtotime($cutoff_end_date));
						$cutoff_end_date =  $cutoff_end_date->format("Y-m-d");


						
					}





					$date = "{$year}-{$month}-1";

					//$date = date("Y-m-d", strtotime($date));

					$date = new DateTime($date);
					$date = $date->format("Y-m-d");

					

					//echo "Start : {$cutoff_start_date} / End : {$cutoff_end_date} <br />";
					if( $start_day > $end_day ){									
						$cutoff_start_date = date("Y-m-d", strtotime("-1 month",strtotime($cutoff_start_date)));

						$cutoff    = Tools::getCutOffPeriod($cutoff_start_date, $pattern);
					}else{
						$cutoff    = Tools::getCutOffPeriod($cutoff_end_date, $pattern);	
					}
					
					

					$cutoffs[$key]['start_date'] = $cutoff['start'];
					$cutoffs[$key]['end_date']   = $cutoff['end'];					
    			}
    			
    			$data = $cutoffs;    			
    		}
    	}

    	return $data;
    }



    	public function getNextCutOff() {
		$next_cuttoff_data = array();

		if( $this->id > 0 ){
			$current_cutoff = G_Cutoff_Period_Helper::sqlCutOffDataById($this->id);
			if( !empty($current_cutoff) ){
				$current_period_end = $current_cutoff['period_end'];
				$date = new DateTime($current_period_end);
				$date->modify('+1 day');
				$query_period_start = $date->format('Y-m-d');
				$next_cuttoff_data  = G_Cutoff_Period_Helper::sqlCutOffDataByPeriodStart($query_period_start);
			}
		}

		return $next_cuttoff_data;
	}

	public function getPreviousCutOff() {
		$previous_cuttoff_data = array();

		if( $this->id > 0 ){
			$current_cutoff = G_Cutoff_Period_Helper::sqlCutOffDataById($this->id);
			if( !empty($current_cutoff) ){
				$current_period_start = $current_cutoff['period_start'];
				$date = new DateTime($current_period_start);
				$date->modify('-1 day');
				$query_period_end = $date->format('Y-m-d');
				$previous_cuttoff_data  = G_Cutoff_Period_Helper::sqlCutOffDataByPeriodEnd($query_period_end);
			}
		}

		return $previous_cuttoff_data;
	}

 public function generateCutoffPeriodByMonthAndYearAndCutoffNumber( $month_number = 0, $year, $cutoff_number = 0 ) {    	
    	$data['message'] 		 = "No cutoff period(s) to generate";
		$data['total_generated'] = 0;

    	if( $cutoff_number > 0 ){        		    		
    					
			$fields = array("cut_off","payout_day");
			$default_pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields); 

			$cutoff_data = explode(",", $default_pay_period['cut_off']);
			$payout_day  = explode(",", $default_pay_period['payout_day']);

			if( $cutoff_number == 1 ){
				$to_generate_cutoff        = $cutoff_data[0];    						
				$to_generate_payout_day    = $payout_day[0];
				$to_generate_cutoff_period = 1;
			}else{
				$to_generate_cutoff     = $cutoff_data[1];
				$to_generate_payout_day = $payout_day[1];
				$to_generate_cutoff_period = 2;
			}
			
			$days_cutoff = explode("-", $to_generate_cutoff);
			$start_day   = $days_cutoff[0];
			$end_day     = $days_cutoff[1];

			if( ($month == 2 && $start_day == 28) || $start_day >= 30 ){
				$cutoff_start_date = date("Y-m-t", strtotime("{$year}-{$month_number}-1"));
			}else{
				$cutoff_start_date   = date("Y-m-d", strtotime("{$year}-{$month_number}-{$start_day}"));
			}

			if( ($month == 2 && $end_day == 28) || $end_day >= 30 ){
				$cutoff_end_date = date("Y-m-t", strtotime("{$year}-{$month_number}-1"));
			}else{
				$cutoff_end_date   = date("Y-m-d", strtotime("{$year}-{$month_number}-{$end_day}"));
			}

			$pattern[] = $cutoff_data[0];
			$pattern[] = $cutoff_data[1];
			$cutoff    = Tools::getCutOffPeriod($cutoff_start_date, $pattern);
			
			//Payout date
			$payout_date = "{$year}-{$month_number}-{$to_generate_payout_day}";
			if( ($to_generate_payout_day >= 31) || ($month == 2 && $start_day == 28) ){				
				$formatted_payout_date   = date("Y-m-t", strtotime("{$year}-{$month_number}-1"));
			}else{
				$formatted_payout_date   = date("Y-m-d",strtotime($payout_date));
			}

			//Save data
			$this->year_tag             = $year;
			$this->start_date           = $cutoff['start'];
			$this->end_date 			= $cutoff['end'];
			$this->payout_date 			= $formatted_payout_date;
			$this->cutoff_number 		= $to_generate_cutoff_period;
			$this->salary_cycle_id      = 2;
			$this->is_lock 				= self::NO;
			$this->is_payroll_generated = self::NO;

			self::save();

			$data['message'] 		 = "Cutoff Period Generated : " . $cutoff['start'] . " to " . $cutoff['end'];
			$data['total_generated'] = 1;
    	}

    	return $data;
    }
    

 public function generateCutoffPeriodByMonthAndYear( $month_number = 0, $year = 0 ) {
    	$data['message'] 		 = "No cutoff period(s) to generate";
		$data['total_generated'] = 0;

    	$current_year 		     = date("Y");
    	$total_periods_per_month = 2;

    	if( $month_number > 0 && $year <= $current_year ){    		
    		$date_string   = "{$year}-{$month_number}-01";
    		$month_text    = date("F",strtotime($date_string));

    		$periods = self::expectedCutOffPeriodsByMonthAndYear($month_number, $year);    		
    		foreach($periods as $period){
    			$period_start[] = "'" . $period['start_date'] . "'";
    			$period_end[]   = "'" . $period['end_date'] . "'";
     		}
     		
    		$periods       = G_Monthly_Cutoff_Period_Helper::sqlAllByPeriodStartAndPeriodEnd($period_start, $period_end);     		
    		$total_periods = count($periods);
    		
    		if( $total_periods >= 2 ){    			
    			$data['message'] 		 = "Cutoff Periods were already generated for the month of {$month_text} - {$year}";
    			$data['total_generated'] = 0;
    		}else{    			
    			$total_generated = $total_periods_per_month - $total_periods;
    			if( $total_periods <= 0 ){
    				//Generate all cutoff for given month
    				$data1 = self::generateCutoffPeriodByMonthAndYearAndCutoffNumber($month_number, $year, 1);
    				$data2 = self::generateCutoffPeriodByMonthAndYearAndCutoffNumber($month_number, $year, 2);

    				$total_generated = $data1['total_generated'] + $data2['total_generated'];
    				$data['total_generated'] =  $total_generated;
    				$data['message']         = $data1['message'] . " / " . $data2['message'];

    			}else{
    				//Generate missing cutoff
    				foreach( $periods as $period ){
    					$existing_cutoff_period = $period['cutoff_number'];
    					if( $existing_cutoff_period == 1 ){    					
    						$to_generate_cutoff_period = 2;
    					}else{    						    						
    						$to_generate_cutoff_period = 1;
    					}
    					$data = self::generateCutoffPeriodByMonthAndYearAndCutoffNumber($month_number, $year, $to_generate_cutoff_period);
    					$data['total_generated'] = 1;
    				}
    			}

    		}
    	}

    	return $data;
    }





	public function getPreviousCutOffByDate($date, $year = '') {
		$data['start_date'] = '';
		$data['end_date']   = '';
		$data['date']       = $date;
		$current_year       = date("Y");

		if($year == '') {
			$year = date("Y", strtotime($date));
		}		

		if( $date != '' ){
			$fields = array("cut_off");

			$default_pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);

            /*
             * Old codes for cutoff period 2018
            */
			/*if($year == date("Y")) {
				$default_pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);
			} else {
				$default_pay_period = array('cut_off' => '26-10,11-25');
			}*/		

			if( !empty($default_pay_period) ){				
				$cutoff_data = explode(",", $default_pay_period['cut_off']);    
				$pattern[]   = $cutoff_data[0];
				$pattern[]   = $cutoff_data[1];
				$cutoff    = Tools::getCutOffPeriod($date, $pattern);

				//Get Previous Cutoff data
				$previous_date   = date("Y-m-d",strtotime("-1 days",strtotime($cutoff['start'])));
				if( date("d",strtotime($previous_date)) == 31 ){
					$previous_date   = date("Y-m-d",strtotime("-1 days",strtotime($previous_date)));
				}				
				$previous_cutoff = Tools::getCutOffPeriod($previous_date, $pattern);				
				$field = array("id");
				$cutoff_data = G_Monthly_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($previous_cutoff['start'], $previous_cutoff['end'], $field);				
				if( empty($cutoff_data) ){
					$month = date("m",strtotime($previous_cutoff['start']));
					$year  = date("Y",strtotime($previous_cutoff['start']));
					self::generateCutoffPeriodByMonthAndYear($month, $year);
					$cutoff_data = G_Monthly_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($previous_cutoff['start'], $previous_cutoff['end'], $field);
				}
				$cutoff_data = Tools::encryptArrayIndexValue("id",$cutoff_data);

				$data['eid']        = $cutoff_data['id'];
 				$data['start_date'] = $previous_cutoff['start'];
				$data['end_date']   = $previous_cutoff['end'];
			}
		}

		return $data;
	}

	public function getNextCutOffByDate($date, $year = '') {
		$data['start_date'] = '';
		$data['end_date']   = '';
		$data['date']       = $date;
		$current_year       = date("Y");	

		if($year == '') {
			$year = date("Y", strtotime($date));
		}			

		if( $date != '' ){
			$fields = array("cut_off");

			if($year == date("Y")) {
				$default_pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);
			} else {
				$default_pay_period = array('cut_off' => 'Saturday   -    Friday');
			}

			if( !empty($default_pay_period) ){		
				$cutoff_data = explode(",", $default_pay_period['cut_off']);    
				$pattern[]   = $cutoff_data[0];
				$pattern[]   = $cutoff_data[1];
				$cutoff    = Tools::getCutOffPeriod($date, $pattern);

				//Get Next Cutoff data
				$next_date   = date("Y-m-d",strtotime("+1 days",strtotime($cutoff['end'])));
				if( date("d",strtotime($next_date)) == 31 ){
					$next_date   = date("Y-m-d",strtotime("+1 days",strtotime($next_date)));
				}		
				$next_cutoff = Tools::getCutOffPeriod($next_date, $pattern);

				$field = array("id");
				$cutoff_data = G_Monthly_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($next_cutoff['start'], $next_cutoff['end'], $field);

				if( empty($cutoff_data) ){
					$month = date("m",strtotime($next_cutoff['start']));
					$year  = date("Y",strtotime($next_cutoff['start']));
					self::generateCutoffPeriodByMonthAndYear($month, $year);
					$cutoff_data = G_Monthly_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($next_cutoff['start'], $next_cutoff['end'], $field);
				}
				$cutoff_data = Tools::encryptArrayIndexValue("id",$cutoff_data);

				$data['eid']        = $cutoff_data['id'];
				$data['start_date'] = $next_cutoff['start'];
				$data['end_date']   = $next_cutoff['end'];
			}
		}

		return $data;
	}


	public function getCurrentCutoffPeriod($date = '') {
    	$data['current_cutoff']['start'] = '';
		$data['current_cutoff']['end']   = '';
		$data['cutoff_number'] = 0;
		$data['id'] = 0;

    	if( !empty($date) ){    	
    		$month = date("m",strtotime($date));
    		$year  = date("Y",strtotime($date));
    	
    		$expected_cutoff = self::expectedCutOffPeriodsByMonthAndYear($month, $year);    		

    		$date_compare    = strtotime($date);    		
    		foreach( $expected_cutoff as $key => $cutoff ){
    			$cutoff_period = $key;
    			$start_date     = strtotime($cutoff['start_date']);
    			$end_date       = strtotime($cutoff['end_date']);

    			//if( $start_date <= $date_compare && $end_date >= $date_compare ){
    				$fields = array("id","is_lock");
    				$cutoff_data = G_Monthly_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($cutoff['start_date'],$cutoff['end_date'],$fields);
    				$cutoff_data = Tools::encryptArrayIndexValue("id",$cutoff_data);     				
    				$data['id']   = $cutoff_data['id'];
    				$data['date'] = $date;
    				$data['is_lock'] = $cutoff_data['is_lock'];
    				$data['current_cutoff']['start'] = $cutoff['start_date'];
    				$data['current_cutoff']['end']   = $cutoff['end_date'];
    				$data['cutoff_number'] = $cutoff_period;
    			//}
    		}    		
    	}

    	return $data;
    }




    public function getCutOffIdByYearMonthAndPeriod($payroll_period) {
		$a = explode('-', $payroll_period);
		$year = $a[0];
		$month = $a[1];
		
		if($a[2] == 'A') {
		 	$period = 1;
		} 

		$period_id = G_Monthly_Cutoff_Period_Finder::findByYearMonthAndPeriod($year, $month, $period);

		if(!$period_id) {
			$period_id = G_Monthly_Cutoff_Period_Finder::findByYearMonthAndPeriodEnd($year, $month, $period);
		}

    	return $period_id;
    }



public function unLockPayrollPeriod() {		
		return G_Monthly_Cutoff_Period_Manager::unLockPayrollPeriod($this);
	}




public function save() {		
		return G_Monthly_Cutoff_Period_Manager::save($this);
	}
	
 
public function deleteAllByYear($year) {
		G_Monthly_Cutoff_Period_Manager::deleteAllByYear($year);
		return $this;
	}

} //end parent class
	
?>