<?php

		class G_Weekly_Cutoff_Period{
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

			public function setId($id){
				$this->id = $id;
			}

   public function setPayrollAsGenerated() {
       $this->is_payroll_generated = self::YES;
   }
   
			public function getId(){
				return $this->id;
			}

			public function	setYearTag($year_tag){
				$this->year_tag = $year_tag;
			}
			public function getYearTag(){
				return $this->year_tag;
			}

			public function setStartDate($start_date){
				$start_date = date("Y-m-d",strtotime($start_date));
				$this->start_date = $start_date;
			}
			public function getStartDate(){
				return $this->start_date;
			}

			public function setEndDate($end_date){
				$end_date = date("Y-m-d",strtotime($end_date));
				$this->end_date = $end_date;
			}
			public function	getEndDate(){
				return $this->end_date;
			}

			public function setPayoutDate($payout_date){
				$this->payout_date = $payout_date;
			}
			public function getPayoutDate(){
				return $this->payout_date;
			}

			public function setCutoffNumber($cutoff_number){
				$this->cutoff_number = $cutoff_number;
			}
			public function getCutoffNumber(){
				return $this->cutoff_number;
			}
			 /*
     * @return A or B
     */
    	public function getCutoffCharacter() {
        $cutoff_number = $this->getCutoffNumber();
        if ( $cutoff_number == 1 ) {
          return 'A';
        } else if ( $cutoff_number == 2 ) {
          return 'B';
        } else if( $cutoff_number == 3 ){
        	return 'C';
        } else if( $cutoff_number == 4 ){
        	return 'D';
        } else if ( $cutoff_number == 5 ){
        	return 'E';
        }
    	}

			public function setSalaryCycleId($salary_cycle_id){
				$this->salary_cycle_id = $salary_cycle_id;
			}
			public function getSalaryCycleId(){
				return $this->salary_cycle_id;
			}

			public function setIsLock($is_lock){
				$this->is_lock = $is_lock;
			}
			public function getIsLock(){
				return $this->is_lock;
			}
			public function isLocked() {
        if ($this->is_lock == self::YES) {
            return true;
        } else {
            return false;
        }
    		}

			public function setNumberOfMonths($number_of_months){
				$this->number_of_months = $number_of_months;
			}
			public function getNumberOfMonths(){
				return $this->number_of_months;
			}

			public function setIsPayrollGenerated($is_payroll_generated){
				$this->is_payroll_generated = $is_payroll_generated;
			}
			public function getIsPayrollGenerated(){
				return $this->is_payroll_generated;
			}

			public function isPayrollGenerated() {
        if ($this->is_payroll_generated == self::YES) {
            return true;
        } else {
            return false;
        }
    	}


    	public function expectedWeeklyCutOffPeriodsByMonthAndYear( $month = 0, $year ){    	
    	$data = array();
    	if( $month > 0 && !empty($year) ){
    		$fields = array("cut_off");
    			// $month = 2;
    			// $year = '2020';
    			$cutoff_periods = G_Weekly_Cutoff_Period_Helper::getExpectedCutoffsByMonthAndYear($month,$year);
    				$data = $cutoff_periods;

    	}

    	return $data;
    }	

    	public function getMonthWeeklyCutoffPeriods( $month = '', $year = '' ) {
    	$cutoff_periods = array();
    	$data = self::expectedWeeklyCutOffPeriodsByMonthAndYear($month, $year);
    	
    	if( !empty($data) ){
    		foreach( $data as $key => $value ){
    			// echo "<pre>";
    			// var_dump($value);
    			// echo "</pre>";
    			$cutoff_data = G_Weekly_Cutoff_Period_Finder::findByPeriod($value['period_start'], $value['period_end']);
    			if( !empty($cutoff_data) ){
    				$cutoff_periods[$key] = $cutoff_data;
    			}    			
    		} 
    	}

    	return $cutoff_periods;
    }

	public function getCutoffDataByStartAndEndDate($fields = array()) {
    	$data = array();

    	if( $this->start_date != '' && $this->end_date != '' ){
    		$data = G_Weekly_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd( $this->start_date, $this->end_date, $fields );
    	} 

    	return $data;
    }

    public function setDateCreatedYearAndDayStart($created,$given_year,$start_day){

    }
	
				public function save() {		
					return G_Weekly_Cutoff_Period_Manager::save($this);
				}

    public function getMonth() {
        return date('F', strtotime($this->start_date));
    }

				public function getNextCutOff() {
					$next_cuttoff_data = array();

					if( $this->id > 0 ){
						$current_cutoff = G_Weekly_Cutoff_Period_Helper::sqlCutOffDataById($this->id);
						if( !empty($current_cutoff) ){
							$current_period_end = $current_cutoff['period_end'];
							$date = new DateTime($current_period_end);
							$date->modify('+1 day');
							$query_period_start = $date->format('Y-m-d');
							$next_cuttoff_data  = G_Weekly_Cutoff_Period_Helper::sqlCutOffDataByPeriodStart($query_period_start);
						}
					}

					return $next_cuttoff_data;
				}

				public function getPreviousCutOff() {
					$previous_cuttoff_data = array();

					if( $this->id > 0 ){
						$current_cutoff = G_Weekly_Cutoff_Period_Helper::sqlCutOffDataById($this->id);
						if( !empty($current_cutoff) ){
							$current_period_start = $current_cutoff['period_start'];
							$date = new DateTime($current_period_start);
							$date->modify('-1 day');
							$query_period_end = $date->format('Y-m-d');
							$previous_cuttoff_data  = G_Weekly_Cutoff_Period_Helper::sqlCutOffDataByPeriodEnd($query_period_end);
						}
					}

					return $previous_cuttoff_data;
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
    				$cutoff_data = G_Weekly_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($cutoff['start_date'],$cutoff['end_date'],$fields);
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
			    

    public function expectedCutOffPeriodsByMonthAndYear( $month = 0, $year ){ 

    	$data = array();
    	if( $month > 0 && !empty($year) ){
    		$fields = array("cut_off");
    		$default_pay_period = G_Settings_Pay_Period_Helper::sqlWeeklyPayPeriod($fields);    	
    		if( !empty($default_pay_period) ){      			
    			$cutoff_data = explode(",", trim($default_pay_period['cut_off']));    
    			$pattern[]   = $cutoff_data[0];
				$pattern[]   = $cutoff_data[1];
				$cutoff_data = array_filter($cutoff_data); 					
    			foreach( $cutoff_data as $key => $cutoff ){    				    				
    				$days_cutoff = explode("-", $cutoff);
					$start_day   = $days_cutoff[0];
					$end_day     = $days_cutoff[1];
					
					$cutoff_start_date = "{$year}-{$month}-{$start_day}";
					$cutoff_start_date = date("Y-m-d", strtotime($cutoff_start_date));
					
					if( ($month == 2 && $end_day > 30 ) || $end_day > 30 ){																					
						$cutoff_end_date = date("Y-m-t", strtotime("{$year}-{$month}-1"));
					}else{
						$cutoff_end_date   = "{$year}-{$month}-{$end_day}";
					}

					$date = "{$year}-{$month}-1";
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

						$default_pay_period = G_Settings_Pay_Period_Helper::sqlWeeklyPayPeriod($fields);

			            /*
			             * Old codes for cutoff period 2018
			            */
						/*if($year == date("Y")) {
							$default_pay_period = G_Settings_Pay_Period_Helper::sqlWeeklyPayPeriod($fields);
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
							$cutoff_data = G_Weekly_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($previous_cutoff['start'], $previous_cutoff['end'], $field);				
							if( empty($cutoff_data) ){
								$month = date("m",strtotime($previous_cutoff['start']));
								$year  = date("Y",strtotime($previous_cutoff['start']));
								self::generateCutoffPeriodByMonthAndYear($month, $year);
								$cutoff_data = G_Weekly_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($previous_cutoff['start'], $previous_cutoff['end'], $field);
							}
							$cutoff_data = Tools::encryptArrayIndexValue("id",$cutoff_data);

							$data['eid']        = $cutoff_data['id'];
			 				$data['start_date'] = $previous_cutoff['start'];
							$data['end_date']   = $previous_cutoff['end'];
						}
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
     		
    		$periods       = G_Weekly_Cutoff_Period_Helper::sqlAllByPeriodStartAndPeriodEnd($period_start, $period_end);     		
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

    

    public function generateCutoffPeriodByMonthAndYearAndCutoffNumber( $month_number = 0, $year, $cutoff_number = 0 ) {    	
    	$data['message'] 		 = "No cutoff period(s) to generate";
		$data['total_generated'] = 0;

    	if( $cutoff_number > 0 ){        		    		
    					
			$fields = array("cut_off","payout_day");
			$default_pay_period = G_Settings_Pay_Period_Helper::sqlWeeklyPayPeriod($fields); 

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


    // lock cutoff

    public function lockPayrollPeriod() {	
    	
		$return   = array();
		$is_debug = false;

		if( $this->id > 0 ){
			$date_from = $this->start_date;
	        $date_to   = $this->end_date;

			$fields = array("p.employee_id","p.gross_pay");
			$processed_payslip_data = G_Weekly_Payslip_Helper::sqlProcessedPayslipByDateRange($date_from, $date_to, $fields);
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
			
			$return = G_Weekly_Cutoff_Period_Manager::lockPayrollPeriod($this);
		}

		return $return;
	}

	//old unlock payperiod
	/*public function unLockPayrollPeriod() {
				
		return G_Weekly_Cutoff_Period_Manager::unLockPayrollPeriod($this);
	}*/

    //test alex - unlock payperiod
	public function unLockPayrollPeriod() {
			
		$return   = array();
		$is_debug = false;

		if( $this->id > 0 ){
			$date_from = $this->start_date;
	        $date_to   = $this->end_date;

	        $loan = G_Employee_Loan_Payment_History_Helper::sqlEmployeeScheduledUnpaidLoans2($date_from,$date_to);
	       
	        if($loan){
	        	foreach($loan as $ld){
	        		$loan_details = G_Employee_Loan_Finder::findById($ld['loan_id']);
	        		if($loan_details){

	        			$total_paid = $ld['amount_paid'] - $ld['amount_to_pay'];

	        			$lh = G_Employee_Loan_Payment_History_Finder::findByLoanIdAndDateScheduled($ld['loan_id'], $ld['loan_payment_scheduled_date']);

	        			 if($lh){

	        			 	$lh->setAmountPaid(0);								
							$lh->setDatePaid('');									
							$lh->setRemarks('');		
							$lh->setIsLock('No');
							$lh->save();	

	        			 }

	        			$loan_details->setAmountPaid($total_paid);
	        			$loan_details->setAsUnlock();
	        			$loan_details->setStatus(G_Employee_Loan::PENDING);
	        			$loan_details->save();
	        		}
	        	}
	        }

			
			$return = G_Weekly_Cutoff_Period_Manager::unLockPayrollPeriod($this);
		}

		return $return;
	}


	public function getCutoffPeriodsByYear() {
    	// $data = array();
    	// if( $this->year_tag > 0 ){
    	// 	$year_tag = $this->year_tag;
    	// }else{
    	// 	$year_tag = date("Y"); //Will fetch current year
    	// }

    	// $fields = array("id","CONCAT( DATE_FORMAT(period_end, '%Y'), ' - ', DATE_FORMAT(period_end, '%M'), ' - ', IF(cutoff_number = 1, 'A', 'B','C','D','E') ) AS cutoff");

    	// echo $fields;
    	// $data = G_Weekly_Cutoff_Period_Helper::sqlCutoffPeriodsByYearTag($year_tag, $fields);

    	// return $data;
    }


    	public function getCutOffIdByYearMonthAndPeriod($payroll_period) {
		$a = explode('-', $payroll_period);
		$year = $a[0];
		$month = $a[1];
		
		if($a[2] == 'A') {
		 	$period = 1;
		} elseif($a[2] == 'B'){
			$period = 2;
		}elseif ($a[2] == 'C' ) {
			$period = 3;
		}elseif ($a[2] == 'D') {
			$period = 4;
		}else{
			$period = 5;
		}


		$period_id = G_Weekly_Cutoff_Period_Finder::findByYearMonthAndPeriod();

		//$period_id = G_Cutoff_Period_Finder::findByYearMonthAndPeriod($year, $month, $period);

		if(!$period_id) {
			//$period_id = G_Weekly_Cutoff_Period_Finder::findByYearMonthAndPeriodEnd($year, $month, $period);
			$period_id = G_Weekly_Cutoff_Period_Finder::findByYearMonthAndPeriodStart($year, $month, $period);
		}

    	return $period_id;
    }




		}

?>