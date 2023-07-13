<?php
class Loan_Calculator {
	protected $start_date;
	protected $loan_amount = 0;
	protected $deduction_type; //Monthly, Bi-monthly
	protected $months_to_pay = 0;
	protected $interest_rate;	
	protected $cutoff_period;
	
	public function __construct( $amount = 0) {
		if( $amount > 0 ){
			$this->loan_amount = $amount;
		}
	}
	
	public function setStartDate($value) {
		$date_format = date("Y-m-d",strtotime($value));
		$this->start_date = $date_format;	
	}

	public function setLoanAmount($value) {
		$this->loan_amount = $value;
	}

	public function setDeductionType($value){
		$this->deduction_type = $value;
	}

	public function setCutoffPeriod($value = ''){
		$this->cutoff_period = $value;
	}

	public function setMonthsToPay($value = 0) {
		$this->months_to_pay = $value;
	}

	public function setInterestRate($value) {
		$this->interest_rate = $value;
	}

	/*
	 * @param int 0 = by month / 1 = by cutoff - for bi-monthly
	*/
	public function getEndDate( $month_cutoff = 0, $period = array() ) {
		$end_date = '';
		if( $this->deduction_type != '' ){
			switch ($this->deduction_type) {
				case G_Employee_Loan::BI_MONTHLY:		
					if( $month_cutoff == 1 ){
						$end_date = $this->endDateByCutoff();//Will use number of cutoffs for counting dates
					}else{
						$end_date = $this->endDateBiMonthly();//Will use number of months for counting dates
					}					
					break;
				case G_Employee_Loan::MONTHLY:				
					$end_date = $this->endDateMonthly();
					break;		
				case G_Employee_Loan::WEEKLY:		
					$end_date = $this->endDateByCutoff($period);//Will use number of cutoffs for counting dates
					break;	
			}			
		}

		return $end_date;
	}

	public function endDateByCutoff($period = array()) {
		$new_date = '';
		if( $this->start_date != '' ){
			$fields = array("cut_off");
			
			if (strtolower($this->deduction_type) == 'weekly') {
				$default_pay_period = G_Settings_Pay_Period_Helper::sqlWeeklyPayPeriod($fields);

				$year = $period['year'];

				$month_number = date("m", strtotime($period['month']));

				$start_date_day = $this->start_date;
				while( $x <= $this->months_to_pay ){	
					$x++;
					if( $x >= $this->months_to_pay ){	
						break;
					}	

					$cutoffs = G_Weekly_Cutoff_Period_Finder::findAllByMonthYear($year, $month_number);	

							
					if ($cutoffs) {
						foreach ($cutoffs as $key => $cutoff) {
							if ($start_date_day == $cutoff->getEndDate()) {
								if (isset($cutoffs[$key+1])) {
									$new_date = $cutoffs[$key+1]->getEndDate();
								}
								else {
									if ($month_number == 12) {
										$month_number = 1;
										$year++;
									}
									else {
										$month_number++;
									}

									$next_cutoff = G_Weekly_Cutoff_Period_Finder::findAllByMonthYearCutoffNumber($year, $month_number, 1);

									if ($next_cutoff) {
										$new_date = strtotime($cutoff->getEndDate());
										$new_date = date("Y-m-d",strtotime("+7 day", $new_date));	
									}
									else {
										$new_date = strtotime($cutoff->getEndDate());
										$new_date = date("Y-m-d",strtotime("+7 day", $new_date));
									}
								}
	
								break;
							}							
						}	
					}	
					else {
						$new_date = strtotime($cutoff->getEndDate());
						$new_date = date("Y-m-d",strtotime("+7 day", $new_date));	
					}
					
					$start_date_day = $new_date;

				}

			}
			else {
				$default_pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);
				$cutoff_data = explode(",", $default_pay_period['cut_off']);    
				$cutoff_a    = explode("-", $cutoff_data[0]);
				$cutoff_b    = explode("-", $cutoff_data[1]);		
	
				$start_date_day = date("d",strtotime($this->start_date));
				if( in_array($start_date_day, $cutoff_a) ){
					$start_pattern = 'a';	
					$x        = 1;			
				}else{
					$start_pattern = 'b';
					$x        = 0;				
				}
				$start_pattern = 'b';				
	
				$new_date = $this->start_date;
				//$x = 1;
				
				while( $x < $this->months_to_pay ){				
					if( $start_pattern == 'b' ){
						$new_date = Tools::convertDateToCutoffPattern($new_date,$cutoff_b,1);	
						$start_pattern = '';				
						$x++;
					}else{
						$new_date_a = Tools::convertDateToCutoffPattern($new_date,$cutoff_a,1);						
						$new_date   = $new_date_a;
						$x++;
						if( $x >= $this->months_to_pay ){						
							break;
						}		
	
						$new_date_b = Tools::convertDateToCutoffPattern($new_date,$cutoff_b,1);
						$new_date   = $new_date_b;
						$x++;
						if( $x >= $this->months_to_pay ){						
							break;
						}
					}					
					$new_date = date("Y-m-d",strtotime("first day of +1 month",strtotime($new_date)));									
				}	
			}		
		}
		return $new_date;
	}

	public function getStartDate( $period = array() ){
		$loan_start_date  = '';
		if( !empty($period) ){
			$i_year     = $period['year'];
			$s_cutoff   = $period['cutoff'];
			$i_month    = date("m",strtotime($i_year . "-" . $period['month'] . "-" . "01"));


			$fields = array("cut_off");

			if (strtolower($this->deduction_type) == 'weekly') {

				$default_pay_period = G_Settings_Pay_Period_Helper::sqlWeeklyPayPeriod($fields);

				$month_number = date("m", strtotime($period['month']));

				$cutoffs = G_Weekly_Cutoff_Period_Finder::findAllByMonthYear($period['year'], $month_number);
			
				switch( $s_cutoff ){
					case 'a':		
					
						$loan_start_date = date("Y-m-d",strtotime($cutoffs[0]->getEndDate()));
	
						break;
					case 'b':
						$loan_start_date = date("Y-m-d",strtotime($cutoffs[1]->getEndDate()));

						break;
					case 'c':
						$loan_start_date = date("Y-m-d",strtotime($cutoffs[2]->getEndDate()));

						break;
					case 'd':
						$loan_start_date = date("Y-m-d",strtotime($cutoffs[3]->getEndDate()));

						break;
					case 'e':
						$loan_start_date = date("Y-m-d",strtotime($cutoffs[4]->getEndDate()));

						break;
				}
			}
			else{

				$default_pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);

				$cutoff_data = explode(",", $default_pay_period['cut_off']);    
				$cutoff_a    = explode("-", $cutoff_data[0]);
				$cutoff_b    = explode("-", $cutoff_data[1]);	
			
				switch( $s_cutoff ){
					case 'a':						
					$loan_start_date = $cutoff_a[1];
					$loan_start_date = "{$i_year}-{$i_month}-{$loan_start_date}";
	
					break;
					case 'b':
						$start_date_day   = $cutoff_b[1];							
						if( $i_month == 2 && $start_date_day > 28 ){
							$feb_date = date("Y-m-t",strtotime("{$i_year}-{$i_month}-01"));
							$loan_start_date = $feb_date;
						}elseif( $start_date_day == 31 ){
							$new_date = date("Y-m-t",strtotime("{$i_year}-{$i_month}-01"));
							$loan_start_date = $new_date;
						}else{
							$loan_start_date = "{$i_year}-{$i_month}-{$start_date_day}";
						}
						break;
				}
			}	
		}

		return $loan_start_date;
	}

	private function endDateMonthly() {
		$fields = array("cut_off");
		$default_pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);
		$cutoff_data = explode(",", $default_pay_period['cut_off']);    
		$cutoff_a    = explode("-", $cutoff_data[0]);
		$cutoff_b    = explode("-", $cutoff_data[1]);

		//Will use end date
		$start_date_a = $cutoff_a[1];
		$start_date_b = $cutoff_b[1];

		$plus_months = $this->months_to_pay - 1;		
		$start_date_day = date("d",strtotime($this->start_date));

		if( $start_date_day > $start_date_a ){
			$new_start_date_day = $start_date_b;
		}else{
			$new_start_date_day = $start_date_a;
		}

		$end_date = date("Y-m-d",strtotime("first day of +{$plus_months} month",strtotime($this->start_date))); //Use first day to not skip february
		$end_date_month = date("m",strtotime($end_date));

		if( $end_date_month == 2 && $start_date_day > 28 ){
			$end_date = date("Y-m-t",strtotime($end_date));
		}else{					
			$end_date = date("Y-m-{$new_start_date_day}",strtotime($end_date));			
		}
		return $end_date;
	}

	private function endDateBiMonthly() {		
		$start_date_day = date("d",strtotime($this->start_date));
		$fields = array("cut_off");
		$default_pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);
		$cutoff_data = explode(",", $default_pay_period['cut_off']);    
		$cutoff_a    = explode("-", $cutoff_data[0]);
		$cutoff_b    = explode("-", $cutoff_data[1]);

		if( in_array($start_date_day, $cutoff_a) ){ //if start date day falls within cutoff a get cutoff b end period 
			$new_day     = $cutoff_b[1];
			$plus_months = $this->months_to_pay - 1;
		}elseif( in_array($start_date_day, $cutoff_b) ){
			$new_day     = $cutoff_a[1];
			$plus_months = $this->months_to_pay;		
		}else{
			if( $start_date_day > $cutoff_a[1] ){
				$new_day     = $cutoff_b[1];
			}else{
				$new_day     = $cutoff_a[1];
			}
			$plus_months = $this->months_to_pay;	
		}

		$datetime = new DateTime($this->start_date);
		if( $datetime->format("d") > $cutoff_a[1] ){
			$new_day =  $cutoff_a[1];			
		}
		
		$new_start_date = strtotime(date("Y-m-01",strtotime($this->start_date)));
		$new_start_date = date("Y-m-d",strtotime("first day of +{$plus_months} month",$new_start_date));		
		$new_start_date_day = date("m",strtotime($new_start_date));
		if( $new_start_date_day == 2 && $new_day > 28 ){			
			$end_date = date("Y-m-t",strtotime($new_start_date));
		}else{
			$end_date     =  date("Y-m-{$new_day}",strtotime($new_start_date));
		}
		
		return $end_date;
	}

	public function expectedLoanEndDate() {
		$end_date        = '';
		$a_cutoff_period = explode(",", $this->cutoff_period);
		$count_cutoff    = 0;
		foreach( $a_cutoff_period as $period ){
			if( trim($period) != '' ){
				$count_cutoff++;
			}
		}

		$total_cutoff = $this->months_to_pay * $count_cutoff;
		$cutoff_year  = date("Y",strtotime($this->start_date));
		$fields = array("payout_date");
		if( $count_cutoff == 2 ){						
			$periods = G_Cutoff_Period_Helper::sqlCutoffPeriodsByYearTagAndStartPeriod($cutoff_year, $this->start_date, $fields, $total_cutoff);
		}elseif( $count_cutoff == 1 ){			
			$cutoff_number = trim($a_cutoff_period[0]);
			$periods = G_Cutoff_Period_Helper::sqlCutoffPeriodsByYearTagAndStartPeriodAndCutoffNumber($cutoff_year, $this->start_date, $cutoff_number, $fields, $total_cutoff);
		}
		
		$last_data = end($periods);
		if( !empty($last_data) ){
			$end_date = $last_data['payout_date'];
		}

		return $end_date;
	}

	public function totalAmountToPay() {		
		$total_amount_to_pay = $this->loan_amount + ($this->loan_amount * ($this->interest_rate/100));
		return $total_amount_to_pay;

	}

	private function monthlyDue() {
		$monthly_due         = 0;
		$total_amount_to_pay = $this->totalAmountToPay();
		if( $this->deduction_type == G_Employee_Loan::BI_MONTHLY ){			
			//$monthly_due = $total_amount_to_pay / ($this->months_to_pay * 2);
			$monthly_due = ($total_amount_to_pay / $this->months_to_pay);
		}elseif( $this->deduction_type == G_Employee_Loan::MONTHLY ){
			$monthly_due = ($total_amount_to_pay / $this->months_to_pay);
		}elseif( $this->deduction_type == G_Employee_Loan::WEEKLY ){
			$monthly_due = ($total_amount_to_pay / $this->months_to_pay);
		}
		return $monthly_due;
	}

	public function expectedDue() {
		$amount_to_pay = self::totalAmountToPay();
		$expected_due  = 0;
		if( $this->cutoff_period != '' && $amount_to_pay > 0 && $this->months_to_pay > 0 ){						
			$a_cutoff_period = explode(",", $this->cutoff_period);
			$count_cutoff = 0;
			foreach( $a_cutoff_period as $period ){
				if( trim($period) != '' ){
					$count_cutoff++;
				}
			}

			$expected_due = $amount_to_pay / ($this->months_to_pay * $count_cutoff);
		}

		return $expected_due;
	}

	public function computeLoanDepre() {
		$data = array();		
		if( !empty($this->start_date) && !empty($this->loan_amount) && !empty($this->deduction_type) && !empty($this->months_to_pay) ){

			$data['end_date']            = $this->getEndDate();
			$data['total_amount_to_pay'] = number_format($this->totalAmountToPay(),2, ".", "");
			$data['monthly_due']         = number_format($this->monthlyDue(),2, ".", "");
			$data['is_valid']			 = true;
		}else{
			$data['end_date']            = '';
			$data['total_amount_to_pay'] = 0;
			$data['monthly_due']         = 0;
			$data['is_valid']			 = false;
		}

		return $data;
	}

	public function computeGovernmentLoan($period = array()) {
		$data = array();				
		if( !empty($period) && $this->loan_amount > 0 && $this->months_to_pay > 0 && $this->deduction_type != '' ){		
			switch ($this->deduction_type) {
				case G_Employee_Loan::BI_MONTHLY:

					break;
				case G_Employee_Loan::MONTHLY:		

					break;
				default:
					$data['start_date']			 = '';
					$data['end_date']            = '';
					$data['total_amount_to_pay'] = 0;					
					$data['is_valid']			 = false;
					break;
			}	
		}

		return $data;
	}

	public function computeLoan($period = array()){
		$data = array();				
		if( !empty($period) && $this->loan_amount > 0 && $this->months_to_pay > 0 && $this->deduction_type != '' ){					
			switch ($this->deduction_type) {
				case G_Employee_Loan::BI_MONTHLY:					
					$this->start_date = $this->getStartDate($period);
					$data['start_date']			 = $this->start_date;
					$data['end_date'] 		     = $this->getEndDate(1);
					$data['total_amount_to_pay'] = number_format($this->totalAmountToPay(),2, ".", "");
					$data['monthly_due']         = number_format($this->monthlyDue(),2, ".", "");
					$data['is_valid']			 = true;
					break;
				case G_Employee_Loan::MONTHLY:												
					$i_year     = $period['year'];
					$s_cutoff   = $period['cutoff'];
					$i_month    = date("m",strtotime($i_year . "-" . $period['month'] . "-" . "01"));								

					$fields = array("cut_off");
					$default_pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);
					$cutoff_data = explode(",", $default_pay_period['cut_off']);    
					$cutoff_a    = explode("-", $cutoff_data[0]);
					$cutoff_b    = explode("-", $cutoff_data[1]);		

					switch( $s_cutoff ){
						case 'a':												
						$this->start_date = $this->getStartDate($period);

						$end_date = $this->getEndDate();
						$end_date_day   = date("d",strtotime($end_date));
						$end_date_month = date("m",strtotime($end_date));

						break;
						case 'b':
							$start_date_day   = $cutoff_b[1];		
							$this->start_date = $this->getStartDate($period);	

							$end_date 		= $this->getEndDate();
							$end_date_day   = date("d",strtotime($end_date));
							$end_date_month = date("m",strtotime($end_date));
							
							if( $start_date_day > 28 && $end_date_month == 2 ){
								$end_date = date("Y-m-t",strtotime($end_date));
							}else{								
								$end_date = date("Y-m-{$start_date_day}",strtotime($end_date));
							}
							break;
					}

					$data['start_date']			 = $this->start_date;
					$data['end_date'] 		     = $end_date;
					$data['total_amount_to_pay'] = number_format($this->totalAmountToPay(),2, ".", "");
					$data['monthly_due']         = number_format($this->monthlyDue(),2, ".", "");
					$data['is_valid']			 = true;
					break;
				case G_Employee_Loan::WEEKLY:	
				/* $cutoff_periods = G_Weekly_Cutoff_Period_Finder::findAllCutoffByYear($period['year']);

				 	$i_year     = $period['year'];
					$s_cutoff   = $period['cutoff'];
					$i_month    = date("m",strtotime($i_year . "-" . $period['month'] . "-" . "01"));
				
					if ( $s_cutoff == 'a' ) {
          $cutoff_number = '1';
        } else if ( $s_cutoff == 'b' ) {
          $cutoff_number = '2';
        } else if( $s_cutoff == 'c' ){
        	$cutoff_number =  '3';
        } else if( $s_cutoff == 'd' ){
        		$cutoff_number =  '4';
        } else if ( $s_cutoff == 'e' ){
        		$cutoff_number =  '5';
        }

					$from_form_ym = $i_year ."-".$i_month;
				
				 foreach ($cutoff_periods as $key=>$c){
				 			 
				 $start_cutoff_date = $c->getStartDate(); //2020-02-14
				 //var_dump($start_cutoff_date);
				 $getMonth = explode("-", $start_cutoff_date);
					$from_cutoff_year_and_month = $getMonth['0'] . "-".$getMonth[1];

				 $from_cutoff_co_number = $c->getCutoffNumber();
				 	

				 if($from_form_ym == $from_cutoff_year_and_month){
				 	if($from_cutoff_co_number == $cutoff_number){
				 		$temp_key = $key;
				 	
				 	}
				 }	
 
				 }

				  foreach ($cutoff_periods as $key=>$c){
          $months_to_pay = $this->months_to_pay - 1;

          if($temp_key+$months_to_pay == $key){
          	$weekly_end_date =  $c->getEndDate();
          }

				  }	
				 
					$this->start_date = $this->getStartDate($period);
					$data['start_date']			 = $this->start_date;
					$data['end_date'] 		     = $this->getEndDate(1, $period);
					$data['total_amount_to_pay'] = number_format($this->totalAmountToPay(),2, ".", "");
					// $data['monthly_due']         = number_format($this->monthlyDue(),2, ".", "");
					$data['monthly_due']         = number_format($this->totalAmountToPay()/$this->months_to_pay,2, ".", "");
					$data['is_valid']			 = true;
					break;*/
					$this->start_date = $this->getStartDate($period);

					$counter = 0;
					$term_count = $this->months_to_pay;
					$x = $this->start_date;

					while($counter <= $term_count){

						$x = date('Y-m-d', strtotime('+1 week', strtotime($x)));
						$counter++;

					}

					//var_dump($x);exit();

					$data['start_date']			 = $this->start_date;
					$data['end_date'] 		     = $x;
					$data['total_amount_to_pay'] = number_format($this->totalAmountToPay(),2, ".", "");
					$data['monthly_due']         = number_format($this->monthlyDue(),2, ".", "");
					$data['is_valid']			 = true;
					break;


				default:
					$data['start_date']			 = '';
					$data['end_date']            = '';
					$data['total_amount_to_pay'] = 0;
					$data['monthly_due']         = 0;
					$data['is_valid']			 = false;
					break;
			}
		}		
		return $data;
	}

	public function computeLoanNew($period = array(),$govt_period = array()){
		$data = array();				
		if( !empty($period) && $this->loan_amount > 0 && $this->months_to_pay > 0 && $this->deduction_type != '' ){					
			switch ($this->deduction_type) {
				case G_Employee_Loan::BI_MONTHLY:					
					$this->start_date = $this->getStartDate($period);
					$data['start_date']			 = $this->start_date;
					$data['end_date'] 		     = $this->getEndDate(1);
					$data['total_amount_to_pay'] = number_format($this->totalAmountToPay(),2, ".", "");
					$data['monthly_due']         = number_format($this->monthlyDue(),2, ".", "");
					$data['is_valid']			 = true;
					break;
				case G_Employee_Loan::MONTHLY:												
					$i_year     = $period['year'];
					$s_cutoff   = $period['cutoff'];
					$i_month    = date("m",strtotime($i_year . "-" . $period['month'] . "-" . "01"));								

					$fields = array("cut_off");
					$default_pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);
					$cutoff_data = explode(",", $default_pay_period['cut_off']);    
					$cutoff_a    = explode("-", $cutoff_data[0]);
					$cutoff_b    = explode("-", $cutoff_data[1]);		

					switch( $s_cutoff ){
						case 'a':												
						$this->start_date = $this->getStartDate($period);

						$end_date = $this->getEndDate();
						$end_date_day   = date("d",strtotime($end_date));
						$end_date_month = date("m",strtotime($end_date));

						break;
						case 'b':
							$start_date_day   = $cutoff_b[1];		
							$this->start_date = $this->getStartDate($period);	

							$end_date 		= $this->getEndDate();
							$end_date_day   = date("d",strtotime($end_date));
							$end_date_month = date("m",strtotime($end_date));
							
							if( $start_date_day > 28 && $end_date_month == 2 ){
								$end_date = date("Y-m-t",strtotime($end_date));
							}else{								
								$end_date = date("Y-m-{$start_date_day}",strtotime($end_date));
							}
							break;
					}

					$data['start_date']			 = $this->start_date;
					$data['end_date'] 		     = $end_date;
					$data['total_amount_to_pay'] = number_format($this->totalAmountToPay(),2, ".", "");
					$data['monthly_due']         = number_format($this->monthlyDue(),2, ".", "");
					$data['is_valid']			 = true;
					break;
				case G_Employee_Loan::WEEKLY:	
				 /* previous function//
				 $cutoff_periods = G_Weekly_Cutoff_Period_Finder::findAllCutoffByYear($period['year']);

				 	$i_year     = $period['year'];
					$s_cutoff   = $period['cutoff'];
					$i_month    = date("m",strtotime($i_year . "-" . $period['month'] . "-" . "01"));
					// echo $s_cutoff;
					// echo "<--";
						$s_cutoff_g   = $govt_period['cutoff'];

					if ( $s_cutoff_g == 'a' ) {
				          $cutoff_number = '1';
				        } else if ( $s_cutoff_g == 'b' ) {
				          $cutoff_number = '2';
				        } else if( $s_cutoff_g == 'c' ){
				        	$cutoff_number =  '3';
				        } else if( $s_cutoff_g == 'd' ){
				        		$cutoff_number =  '4';
				        } else if ( $s_cutoff_g == 'e' ){
				        		$cutoff_number =  '5';
				        }

					$from_form_ym = $i_year ."-".$i_month;
					//echo $cutoff_number;
				 foreach ($cutoff_periods as $key=>$c){
				 			 
					 $start_cutoff_date = $c->getStartDate(); //2020-02-14
						 //var_dump($start_cutoff_date);
					 $getMonth = explode("-", $start_cutoff_date);
					 $from_cutoff_year_and_month = $getMonth['0'] . "-".$getMonth[1];

					 $from_cutoff_co_number = $c->getCutoffNumber();
				 	

					 if($from_form_ym == $from_cutoff_year_and_month){

				 		if($from_cutoff_co_number == $cutoff_number){
				 				$temp_key = $key;

				 		// echo "---";
				 		//  echo $cutoff_number;
				 		//  echo "adsasd";
				 		}
					 }	
 
				 }

				  foreach ($cutoff_periods as $key=>$c){
			          $months_to_pay = $this->months_to_pay - 1;

			          if($temp_key+$months_to_pay == $key){
			          	$weekly_end_date =  $c->getEndDate();
			          }

				  }	
				  // echo $weekly_end_date;
				  // echo "<br>";
					$this->start_date = $this->getStartDate($period);
					$data['start_date']			 = $this->start_date;
					// $data['end_date'] 		     = $this->getEndDate(1, $period);
					$data['end_date'] = $weekly_end_date;
					$data['total_amount_to_pay'] = number_format($this->totalAmountToPay(),2, ".", "");
					// $data['monthly_due']         = number_format($this->monthlyDue(),2, ".", "");
					$data['monthly_due']         = number_format($this->totalAmountToPay()/$this->months_to_pay,2, ".", "");
					$data['is_valid']			 = true;
					break;*/

					$this->start_date = $this->getStartDate($period);

					$counter = 0;
					$term_count = $this->months_to_pay;
					$x = $this->start_date;

					while($counter <= $term_count){

						$x = date('Y-m-d', strtotime('+1 week', strtotime($x)));
						$counter++;

					}
					$data['start_date']			 = $this->start_date;
					$data['end_date'] 		     = $x;
					$data['total_amount_to_pay'] = number_format($this->totalAmountToPay(),2, ".", "");
					$data['monthly_due']         = number_format($this->monthlyDue(),2, ".", "");
					$data['is_valid']			 = true;
					break;


				default:
					$data['start_date']			 = '';
					$data['end_date']            = '';
					$data['total_amount_to_pay'] = 0;
					$data['monthly_due']         = 0;
					$data['is_valid']			 = false;
					break;
			}
		}		
		return $data;
	}
}
?>