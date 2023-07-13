<?php
class G_Employee_Loan extends Employee_Loan {
	
	//object
	protected $e;

	protected $is_lock;
	protected $is_archive;
	protected $date_created;

	protected $reference_number;
	protected $remarks;
	protected $date_paid;
	protected $fields;
	protected $employee_name;
	protected $loan_title;
	protected $cutoff_period;
	protected $gross_pay;
	
	protected $date_deducted;

	protected $a_loan_schedules = array();
	protected $a_employee_loans_to_deduct = array();

	protected $continue = false;

	const IN_PROGRESS 	= 'In Progress';
	const CANCELLED		= 'Cancelled';
	const STOP 			= 'Stop';	
	const DONE			= 'Done';
	const PENDING       = 'Pending';
	
	const BI_MONTHLY    = "Bi-monthly";
	const MONTHLY		= "Monthly";
	const WEEKLY 	    = "Weekly";
	const DAILY 		= "Daily";
	const QUARTERLY		= "Quarterly";

	const DEFAULT_INTEREST = 0;
	
	const YES = 'Yes';
	const NO  = 'No';
			
	public function __construct( $id = 0 ) {
		if( $id > 0 ){
			$this->id = $id;
		}
	}

	public function getDefaultInterest() {
		return self::DEFAULT_INTEREST;
	}

	public function getEmployeesLoansDeducted(){
		return $this->a_employee_loans_to_deduct;
	}

	public function setAsLock(){
		$this->is_lock = self::YES;
	}

	public function setAsUnlock(){
		$this->is_lock = self::NO;
	}


	public function setAsIsNotArchive(){
		$this->is_archive = self::NO;
	}

	public function getValidBiMonthlyFrequencyOptions() {
		$a_options = array(1 => "First Cutoff Period", 2 => "Second Cutoff Period");
		return $a_options;
	}

	public function getValidLoanDeductionTypeList() {
		$a_deduction_types = array(self::BI_MONTHLY, self::MONTHLY, self::WEEKLY);
		return $a_deduction_types;
	}

	public function setCutoffPeriod( $values = array() ) {
		$this->cutoff_period = $values;
	}

	public function getCutoffPeriod() {
		return $this->cutoff_period;
	}

	public function setEmployeeName( $value = '' ){
		$this->employee_name = $value;
	}  

	public function getEmployeeName() {
		return $this->employee_name;
	}

	public function setAsPending(){
		$this->status = self::PENDING;
	}

	public function setAsCancelled(){
		$this->status = self::CANCELLED;
	}

	public function setAsDone(){
		$this->status = self::DONE;
	}

	public function setAsStop(){
		$this->status = self::STOP;
	}

	public function setAsInProgress(){
		$this->status = self::IN_PROGRESS;
	}

/*	public function setLoanTitle( $value = '' ) {
		$this->setLoanTitle = $value;
	}
*/

	public function setLoanTitle($value) { 
		$this->loan_title = $value;
	}

	public function getLoanTitle() {
		return $this->loan_title;
	}

	public function getDatePattern(){
		$pattern = "(" . $this->start_date . " - " . $this->end_date . ")";
		return $pattern;
	}

	public function setFields($value = array()){
		$this->fields = $value;
	}

	public function setReferenceNumber($value) { 
		$this->reference_number = $value;
	}

	public function setRemarks($value) {
		$this->remarks = $value;
	}

	public function setDatePaid($value) {		
		$this->date_paid = $value;
	}

	public function setIsLock($value) {
		$this->is_lock = $value;
	}
	
	public function getIsLock() {
		return $this->is_lock;
	}
	
	public function setDateCreated($value) {
		$this->date_created = $value;
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}
	
	public function setIsArchive($value) {
		$this->is_archive = $value;
	}
	
	public function getIsArchive() {
		return $this->is_archive;
	}

	/*
	 * Creating loan details
	 * Returns instance of this object
	*/
	public function createGovernmentLoanDetails(){
		$valid_loan_deduction_type = self::getValidLoanDeductionTypeList();				
		if( $this->employee_id > 0 && $this->loan_type_id > 0 && in_array($this->deduction_type, $valid_loan_deduction_type) && $this->loan_amount > 0 ){				
			$period = $this->cutoff_period;
			//Compute loan details - end date, total amount to pay with interest and monthly due			
			$lc = new Loan_Calculator();

			$lc->setDeductionType($this->deduction_type);	
			$loan_start_date = $lc->getStartDate($period);

			$lc->setStartDate($loan_start_date);
			$lc->setMonthsToPay($this->months_to_pay);

			if (strtolower($this->deduction_type) == 'weekly') {
				$loan_end_date   = $lc->getEndDate(1, $period);
			}
			else {
				$loan_end_date   = $lc->getEndDate(1);
			}

			//echo "Start Date : {$loan_start_date} / End Date : {$loan_end_date} / Months to pay : {$this->months_to_pay}";

			//Employee details
			$fields = array("CONCAT(firstname, ' ', lastname)AS employee_name");
			$e_data = G_Employee_Helper::sqlGetEmployeeDetailsById($this->employee_id, $fields);	

			//Loan title
			$fields = array("loan_type");
			$l_data = G_Loan_Type_Helper::sqlGetLoanTypeDetailsById($this->loan_type_id, $fields);		

			//Set missing object properties
			if( !empty($e_data) && !empty($l_data) ){	
				$this->total_amount_to_pay  = $this->loan_amount; //No interest 
				$this->end_date 	 = $loan_end_date;
				$this->start_date    = $loan_start_date;
				$this->employee_name = $e_data['employee_name'];
				$this->loan_title    = $l_data['loan_type'];				
			}
		}

		return $this;
	}

	public function createGovernmentLoanSchedule() {	
		$valid_loan_deduction_type = self::getValidLoanDeductionTypeList();	
		if( $valid_loan_deduction_type && $this->months_to_pay > 0 && $this->start_date != "" && $this->end_date != "" ){					
			$time_stamp_start_date = strtotime($this->start_date);
			$time_stamp_end_date   = strtotime($this->end_date);		

			$fields = array("cut_off");
			$default_pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);
			$cutoff_data = explode(",", $default_pay_period['cut_off']);    
			$cutoff_a    = explode("-", $cutoff_data[0]);
			$cutoff_b    = explode("-", $cutoff_data[1]);

			$a_cutoff_day = $cutoff_a[1];
			$b_cutoff_day = $cutoff_b[1];

			$start_cutoff_pattern = $this->cutoff_period['cutoff'];

			switch ($this->deduction_type) {
				case self::MONTHLY:		
					$total_terms = $this->months_to_pay;
					$cutoff_pattern_selected = $this->cutoff_period['cutoff'];
					if( $cutoff_pattern_selected == 'a' ){
						$pattern = $cutoff_a;
					}else{
						$pattern = $cutoff_b;
					}

					$x = $time_stamp_start_date;     
					while ( $x <= $time_stamp_end_date ){
						$x_date  = date("Y-m-d",$x);
						$x_day   = date("d",$x);
						$x_month = date("m",$x);

						if( $count_terms <= $total_terms ){
							$new_date = Tools::convertDateToCutoffPattern($x_date, $pattern, 1);	
							if( $new_date != '' && strtotime($new_date) <= $time_stamp_end_date ){
								$periods[] = $new_date;
								$count_terms++;	
							}else{
								break;
							}
						}else{
							break;
						}

						$x = strtotime("first day of +1 month",$x);			
					}

					break;
				case self::BI_MONTHLY:
					$total_terms = $this->months_to_pay;
					$count_terms = 0; //Controls valid days
					$sum_terms_amount = 0;
					
					$x = $time_stamp_start_date;     
					while ( $x <= $time_stamp_end_date ){
						$x_date  = date("Y-m-d",$x);
						$x_day   = date("d",$x);
						$x_month = date("m",$x);

						if( $count_terms <= $total_terms ){
							if( $x_day > $a_cutoff_day ){																
								$new_date_b = Tools::convertDateToCutoffPattern($x_date, $cutoff_b, 1);	
								$new_date_a = '';							
								//echo "Date : {$x_date} / Date A : {$new_date_a} / Date B : {$new_date_b}<br />";
								if( $new_date_b != '' && strtotime($new_date_b) <= $time_stamp_end_date ){
									$periods[] = $new_date_b;
									$count_terms++;	
								}else{
									break;
								}
							}else{								
								$new_date_a = Tools::convertDateToCutoffPattern($x_date, $cutoff_a, 1);								
								$new_date_b = Tools::convertDateToCutoffPattern($x_date, $cutoff_b, 1);								
								//echo "Date : {$x_date} / Date A : {$new_date_a} / Date B : {$new_date_b}<br />";
								if( $new_date_a != '' && strtotime($new_date_a) <= $time_stamp_end_date ){
									$periods[] = $new_date_a;	
									$count_terms++;
								}else{								
									break;
								}

								if( $new_date_b != '' && strtotime($new_date_b) <= $time_stamp_end_date ){
									$periods[] = $new_date_b;
									$count_terms++;	
								}else{
									break;
								}
							}
						}else{							
							break;
						}

						$x = strtotime("first day of +1 month",$x);						
					}				
					break;
				case self::WEEKLY:	
					$time_stamp_start_date = date("Y-m-d",$time_stamp_start_date);
					$time_stamp_end_date = date("Y-m-d",$time_stamp_end_date);

					$cutoffs_range = G_Weekly_Cutoff_Period_Finder::findAllByPeriodEndRange($time_stamp_start_date, $time_stamp_end_date);	
					
					foreach ($cutoffs_range as $key => $cutoff) {
						$periods[] = $cutoff->getEndDate();
					}
							
					break;
				default:
				break;
			}

			//Add amount to pay per term
			$total_amount   = 0;
			$period_counter = 0;
			$total_terms    = 1;
			$loan_schedules = array();

			foreach( $periods as $period ){
				$total_amount += $this->deduction_per_period;
				$loan_schedules[$period_counter]['loan_payment_schedule'] = $period;
				$loan_schedules[$period_counter]['employee_id']		      = $this->employee_id;		
				if( $total_amount <= $this->total_amount_to_pay ){
					$loan_schedules[$period_counter]['due_amount'] = $this->deduction_per_period;
				}else{										
					$loan_schedules[$period_counter]['due_amount'] = $this->deduction_per_period - ($total_amount - $this->total_amount_to_pay);					
					break;
				}				

				if( $total_terms >= $this->months_to_pay ){	
					if( $total_amount != $this->deduction_per_period ){												
						$new_amount = $this->total_amount_to_pay - $total_amount;						
						$loan_schedules[$period_counter]['due_amount'] += $new_amount;
					}				
					break;
				} 

				$total_terms++;
				$period_counter++;			
			}			
			$this->a_loan_schedules = $loan_schedules;
		}
		//utilities::displayArray($this);exit();
		return $this;
	}

	/*
	 * Creating loan details
	 * Returns instance of this object
	*/
	public function createLoanDetails() {
		$valid_loan_deduction_type = self::getValidLoanDeductionTypeList();				
		if( $this->employee_id > 0 && $this->loan_type_id > 0 && in_array($this->deduction_type, $valid_loan_deduction_type) && $this->loan_amount > 0 ){				
			
			$period = $this->cutoff_period;

			//Compute loan details - end date, total amount to pay with interest and monthly due
			$lc = new Loan_Calculator($this->loan_amount);
			$lc->setMonthsToPay($this->months_to_pay);
			$lc->setInterestRate($this->interest_rate);
			$lc->setDeductionType($this->deduction_type);		
			$loan_data = $lc->computeLoan($period);

			//Employee details
			$fields = array("CONCAT(firstname, ' ', lastname)AS employee_name");
			$e_data = G_Employee_Helper::sqlGetEmployeeDetailsById($this->employee_id, $fields);	

			//Loan title
			$fields = array("loan_type");
			$l_data = G_Loan_Type_Helper::sqlGetLoanTypeDetailsById($this->loan_type_id, $fields);		

			//Set missing object properties
			if( !empty($loan_data) && !empty($e_data) && !empty($l_data) ){				
				$this->deduction_per_period = $loan_data['monthly_due'];
				$this->total_amount_to_pay  = $loan_data['total_amount_to_pay']; //With interest
				$this->end_date 	 		= $loan_data['end_date'];
				$this->start_date    		= $loan_data['start_date'];
				$this->employee_name = $e_data['employee_name'];
				$this->loan_title    = $l_data['loan_type'];				
			}
		}

		return $this;
	}

	public function createLoanDetailsDepre() {
		$valid_loan_deduction_type = self::getValidLoanDeductionTypeList();
		if( $this->employee_id > 0 && $this->loan_type_id > 0 && in_array($this->deduction_type, $valid_loan_deduction_type) ){			
			//Create loan details

			if( $this->deduction_type == self::BI_MONTHLY ){
				$this->cutoff_period = "1,2";
			}

			$l = new Loan_Calculator( $this->loan_amount );
			$l->setDeductionType($deduction_type);
			$l->setStartDate($this->start_date);
			$l->setInterestRate($this->interest_rate);	
			$l->setCutoffPeriod($this->cutoff_period);	
			$l->setMonthsToPay($this->months_to_pay);	
			$loan_amount_with_interest = $l->totalAmountToPay();
			$expected_due  = $l->expectedDue();
			$loan_end_date = $l->expectedLoanEndDate(); 
			
			$this->deduction_per_period = $expected_due;
			$this->total_amount_to_pay  = $loan_amount_with_interest;
			$this->end_date = $loan_end_date;

			//Employee details
			$fields = array("CONCAT(firstname, ' ', lastname)AS employee_name");
			$e_data = G_Employee_Helper::sqlGetEmployeeDetailsById($this->employee_id, $fields);
			if( !empty($e_data) ){
				$this->employee_name = $e_data['employee_name'];
			}

			//Loan title
			$fields = array("loan_type");
			$l_data = G_Loan_Type_Helper::sqlGetLoanTypeDetailsById($this->loan_type_id, $fields);
			if( !empty($l_data) ){
				$this->loan_title = $l_data['loan_type'];
			}
		}		
		return $this;
	}

	public function createLoanSchedule() {			
		$valid_loan_deduction_type = self::getValidLoanDeductionTypeList();	
		if( $valid_loan_deduction_type && $this->months_to_pay > 0 && $this->start_date != "" && $this->end_date != "" ){						
			$data_periods 		  = $this->cutoff_period;			
			$months_difference    = $this->months_to_pay;
			$deduction_start_year = date("Y",strtotime($this->start_date));
			$fields  			  = array("period_end");

			$fields = array("cut_off");
			$default_pay_period   = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);  

			$cutoff_data = explode(",", $default_pay_period['cut_off']); //Cutoff pattern will be supplied for the day in date of schedule
			$cutoff_a    = explode("-", $cutoff_data[0]);
			$cutoff_b    = explode("-", $cutoff_data[1]);

			$a_cutoff_day = $cutoff_a[1];
			$b_cutoff_day = $cutoff_b[1];

			$time_stamp_start_date = strtotime($this->start_date);
			$time_stamp_end_date   = strtotime($this->end_date);									
			$counter = 0;
			switch ($this->deduction_type) {
				case self::MONTHLY:		
					$total_terms = $this->months_to_pay;
					$cutoff_pattern_selected = $this->cutoff_period['cutoff'];
					if( $cutoff_pattern_selected == 'a' ){
						$pattern = $cutoff_a;
					}else{
						$pattern = $cutoff_b;
					}

					$x = $time_stamp_start_date;     
					while ( $x <= $time_stamp_end_date ){
						$x_date  = date("Y-m-d",$x);
						$x_day   = date("d",$x);
						$x_month = date("m",$x);

						if( $count_terms <= $total_terms ){
							$new_date = Tools::convertDateToCutoffPattern($x_date, $pattern, 1);	
							if( $new_date != '' && strtotime($new_date) <= $time_stamp_end_date ){
								$periods[] = $new_date;
								$count_terms++;	
							}else{
								break;
							}
						}else{
							break;
						}

						$x = strtotime("first day of +1 month",$x);			
					}

					break;
				case self::BI_MONTHLY:								
					$total_terms = $this->months_to_pay;
					$count_terms = 0; //Controls valid days
					$sum_terms_amount = 0;
					
					$x = $time_stamp_start_date;     
					while ( $x <= $time_stamp_end_date ){
						$x_date  = date("Y-m-d",$x);
						$x_day   = date("d",$x);
						$x_month = date("m",$x);

						if( $count_terms <= $total_terms ){
							if( $x_day > $a_cutoff_day ){																
								$new_date_b = Tools::convertDateToCutoffPattern($x_date, $cutoff_b, 1);									
								$new_date_a = '';									
								if( $new_date_b != '' && strtotime($new_date_b) <= $time_stamp_end_date ){
									$periods[] = $new_date_b;
									$count_terms++;	
								}else{
									break;
								}
							}else{								
								$new_date_a = Tools::convertDateToCutoffPattern($x_date, $cutoff_a, 1);								
								$new_date_b = Tools::convertDateToCutoffPattern($x_date, $cutoff_b, 1);								
								//echo "Date : {$x_date} / Date A : {$new_date_a} / Date B : {$new_date_b}<br />";
								if( $new_date_a != '' && strtotime($new_date_a) <= $time_stamp_end_date ){
									$periods[] = $new_date_a;	
									$count_terms++;
								}else{								
									break;
								}

								if( $new_date_b != '' && strtotime($new_date_b) <= $time_stamp_end_date ){
									$periods[] = $new_date_b;
									$count_terms++;	
								}else{
									break;
								}
							}
						}else{							
							break;
						}

						$x = strtotime("first day of +1 month",$x);	
						//echo "Timestamp start : {$x} - Timestamp end : {$time_stamp_end_date} / Date : {$new_date}<br />";	

						$counter++;
						/*echo $counter;
						if( $counter == 15 ){
							exit;
						}	*/

					}				
					break;
				case self::WEEKLY:	
				    /*previous function//
					$time_stamp_start_date = date("Y-m-d",$time_stamp_start_date);
					$time_stamp_end_date = date("Y-m-d",$time_stamp_end_date);

					$cutoffs_range = G_Weekly_Cutoff_Period_Finder::findAllByPeriodEndRange($time_stamp_start_date, $time_stamp_end_date);	
					
					foreach ($cutoffs_range as $key => $cutoff) {
						$periods[] = $cutoff->getEndDate();
					}
							
					break;
					*/

					$total_terms = $this->months_to_pay;

					$count_terms = 1; //Controls valid days

					$time_stamp_start_date = date("Y-m-d",$time_stamp_start_date);
					$time_stamp_end_date = date("Y-m-d",$time_stamp_end_date);

					$deduction_amount = $this->deduction_per_period;
					$total_amount = $this->loan_amount;

					$subtotal = 0;
					$balance = 0;

					$x = $time_stamp_start_date;

					while ( $x <= $time_stamp_end_date ){
						$x_date  = date("Y-m-d",$x);
						$x_day   = date("d",$x);
						$x_month = date("m",$x);

						if( $count_terms <= $total_terms ){

							if($count_terms == $total_terms){

								$subtotal = $subtotal + $deduction_amount;
								$balance = round($total_amount - $subtotal, 2);

								$periods[$count_terms]['amount_due'] =  $deduction_amount + $balance;

							}
							else{
                               
                               $subtotal = $subtotal + $deduction_amount;
                               $periods[$count_terms]['amount_due'] =  $deduction_amount;
							}

							$periods[$count_terms]['date_sched'] = $x;
							
							$count_terms++;

						}else{							
							break;
						}

						//$x = strtotime("+1 week", $x_date);

						$x = date('Y-m-d', strtotime('+1 week', strtotime($x)));

						
						//echo "Timestamp start : {$x} - Timestamp end : {$time_stamp_end_date} / Date : {$new_date}<br />";	

						$counter++;
						/*echo $counter;
						if( $counter == 15 ){
							exit;
						}	*/

					}	

					break;


				default:
				break;
			}
			
			if( !empty($periods) ){
				$period_counter = 1; //For array index
				if($this->deduction_type == self::WEEKLY){

					foreach( $periods as $key => $sched ){					
					$loan_schedules[$period_counter]['loan_payment_schedule'] = $sched['date_sched'];
					$loan_schedules[$period_counter]['due_amount']            = $sched['amount_due'];
					$loan_schedules[$period_counter]['employee_id']		      = $this->employee_id;	
					$period_counter++;			
					}

				}else{
					foreach( $periods as $date ){					
					$loan_schedules[$period_counter]['loan_payment_schedule'] = $date;
					$loan_schedules[$period_counter]['due_amount']            = $this->deduction_per_period;
					$loan_schedules[$period_counter]['employee_id']		      = $this->employee_id;	
					$period_counter++;			
					}
				}
						
				$this->a_loan_schedules = $loan_schedules;
			}


		}
		return $this;
	}	

	public function createLoanScheduleDepre() {
		if( $this->deduction_per_period > 0 && $this->months_to_pay > 0 && $this->start_date != "" && $this->cutoff_period != "" ){
			$start_date = $this->start_date;
			$a_cutoff_period = explode(",", $this->cutoff_period);
			$count_cutoff = 0;
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

			$loan_schedules = array();
			$period_counter = 1;
			foreach( $periods as $period ){
				$date = $period['payout_date'];
				$loan_schedules[$period_counter]['loan_payment_schedule'] = $date;
				$loan_schedules[$period_counter]['due_amount']            = $this->deduction_per_period;
				$loan_schedules[$period_counter]['employee_id']		      = $this->employee_id;				
				$period_counter++;
			}
			
			$this->a_loan_schedules = $loan_schedules;
		}

		return $this;
	}

	public function getLoanSchedule( $fields = array() ) {
		$data = array();

		if( $this->id > 0 ){
			$data = G_Employee_Loan_Payment_Schedule_Helper::sqlGetDataByLoanId( $this->id, $fields );
		}

		return $data;

	}

	public function getLoanDetails() {
		$data = array();

		if( !empty($this->id) ){
			$data = G_Employee_Loan_Helper::sqlLoanDetailsById($this->id, $this->fields);
		}

		return $data;
	}

	/*
		Usage :
		$l = new G_Employee_Loan();
		$l->setStartDate($start);
		$l->setEndDate($end);
		$l->setReferenceNumber($reference_number); //optional
		$l->setRemarks($remarks); //optional - if not set will generate default 
		$l->generateEmployeeLoan($e); // $e = Employee object
	*/

	public function generateEmployeeLoan(G_Employee $e) {
		$return 		 = array();
		$date   		 = date("Y-m-d H:i:s");
		$count			 = 0;
		$amount_deducted = 0;

		if( !empty($e) && !empty($this->start_date) && !empty($this->end_date) ){
			//Check if loan is already deducted in current payroll period. 
			$pattern         	 = $this->getDatePattern();
			$is_already_deducted = G_Employee_Loan_Payment_History_Helper::sqlIsWithPaymentByEmployeeIdAndDatePattern($e->getId(), $pattern);
			$gel 		         = G_Employee_Loan_Finder::findAllInProgressAndIsNotArchiveLoanByEmployeeIdAndWithinCutoffPeriod($e->getId(), $this->start_date, $this->end_date);				
			$default_remarks = "Deducted to Payroll - {$pattern}";		
			if( $gel ){				
				foreach($gel as $l){							    
					if( empty($this->remarks) ){						
						$l->setRemarks($default_remarks);
					}else{
						$l->setRemarks($this->remarks . " " . $pattern);
					}					

					$l->setDatePaid($date);
					$l->setReferenceNumber($this->reference_number);

					if( $is_already_deducted <= 0 ){
						$l->addToHistory();
					}

					$count++;
					$return['processed_loans'][$l->getId()]['loan_type']       = $l->getLoanTypeName();
					$return['processed_loans'][$l->getId()]['amount_deducted'] = $l->getDeductionPerPeriod();					
				}				
			}

			if( $count > 0 ){				
				$return['is_with_loan']    = true;
			}else{
				$return['is_with_loan']    = false;
			}
			

		}else{
			$return['processed_loans'] = 0;
			$return['is_with_loan']    = false;
		}

		return $return;
	}

	/*
		Usage : 
		$l = new G_Employee_Loan();
		$l->setLoanTypeId(1);
		$loan_type_name = $l->getLoanTypeName();
	*/

	public function getLoanTypeName() {
		$loan_type_name = '';
		
		if( !empty($this->loan_type_id) ){
			$loan_type_name = G_Loan_Type_Helper::sqlLoanTypeNameById($this->loan_type_id);
		}

		return $loan_type_name;
	}

	public function saveEmployeeLoan() {
		$return = array();

		if( !empty($this->start_date) && !empty($this->loan_amount) && !empty($this->deduction_type) && !empty($this->months_to_pay) ){

			$lc = new Loan_Calculator();
	        $lc->setStartDate($this->start_date);
	        $lc->setLoanAmount($this->loan_amount);
	        $lc->setInterestRate($this->interest_rate);
	        $lc->setDeductionType($this->deduction_type);
	        $lc->setMonthsToPay($this->months_to_pay);
	        $loan_data = $lc->computeLoan();

	        if( $loan_data['is_valid'] ){
	        	$this->end_date             = $loan_data['end_date'];
	        	$this->deduction_per_period = $loan_data['monthly_due'];
	        	$this->total_amount_to_pay  = $loan_data['total_amount_to_pay'];
	        	$this->amount_paid			= 0;	        	
	        	$this->is_lock 				= self::YES;
	        	$this->status 				= self::IN_PROGRESS;
	        	$this->is_archive			= self::NO;

	        	$last_id = self::save();	

	        	$return['is_successful'] = true;
	        	$return['message']		 = 'Record saved';
	        	$return['last_id']       = $last_id;        	
	        }else{
	        	$return['is_successful'] = false;
	        	$return['message']       = 'Invalid data';
	        	$return['last_id']		 = 0;
	        }

		}else{
			$return['is_successful'] = false;
	        $return['message']       = 'Invalid data';
	        $return['last_id']		 = 0;
		}	

		return $return;
	}

	public function addToHistory() {
		$return = array();

		if( !empty($this->id) ){

			if( $this->status == self::IN_PROGRESS ){ //Add to history only if loan status is in progress
				//Add to loan history
				$lh = new G_Employee_Loan_Payment_History();			
				$lh->setCompanyStructureId($this->company_structure_id);
				$lh->setEmployeeId($this->employee_id);
				$lh->setEmployeeLoanId($this->id);					
				$lh->setDeductionType($this->deduction_type);					
				$lh->setReferenceNumber($this->reference_number);					
				$lh->setBalance($this->total_amount_to_pay - $this->amount_paid);								
				$lh->setAmountPaid($this->deduction_per_period);									
				$lh->setDatePaid($this->date_paid);		
				$lh->setRemarks($this->remarks);
				$lh->save();	

				//Add deduction per period in loan amount paid
				$this->amount_paid = $this->amount_paid + $this->deduction_per_period;
				$this->addAmountPaid();

				if( $this->amount_paid >= $this->total_amount_to_pay ){ //Close loan if amount paid is equal with total amount to pay
					$this->status = self::DONE; 
					$this->updateLoanStatus(); 
				}

				$return['new_balance']   = number_format($this->total_amount_to_pay - $this->amount_paid,2,".","");
				$return['amount_paid']   = number_format($this->deduction_per_period,2,".","");

				$return['is_successful'] = true;
	        	$return['message']       = 'Successfully added to history';
			}else{
				$return['new_balance']   = 0;
				$return['amount_paid']   = 0;
				$return['is_successful'] = false;
	        	$return['message']       = 'Loan payment already completed';
			}
		}else{
			$return['new_balance']     = 0;
			$return['amount_paid'] 	   = 0;
			$return['is_successful']   = false;
        	$return['message']         = 'Invalid data';
		}

		return $return;
	}

	/*
	 * @param array 
	 * @return array
	*/
	public function addPaymentSchedule( $data = array() ) {
		$return['is_successful'] = false;
		$return['message']       = 'Invalid data';
		$return['last_inserted_id'] = 0;

		if( !empty($data) && $this->id > 0 ){
			$ls = new G_Employee_Loan_Payment_History();
			foreach( $data as $key => $d ){				
				$ls->setData( array($key => $d) );
			}	
			$ls->setAsUnlock();
			$ls->setLoanId($this->id);
			$ls->setEmployeeId($this->employee_id);
			$return = $ls->save();
		}

		return $return;
	}

	/*
	 * @returns array
	*/
	public function getLoanNotification() {
		$notification['is_with_notification'] = false;
		$notification['message'] = '';
		if( $this->id > 0 ){
			$fields = array("total_amount_to_pay");
			$data = G_Employee_Loan_Helper::sqlLoanDetailsById($this->id, $fields);			
			if( !empty($data) ){
				$loan_amount   = $data['total_amount_to_pay'];
				$amount_to_pay = G_Employee_Loan_Payment_History_Helper::sqlSumAmountToPayByLoanId($this->id);				
				if( $loan_amount > $amount_to_pay ){
					$amount_difference = $loan_amount - $amount_to_pay;
					$amount_difference = number_format($amount_difference,2);

					$notification['is_with_notification'] = true;
					$notification['message'] 			  = "You have remaining balance due of Php <b>{$amount_difference}</b>. You can add it to the remaining payments or create a new entry.";
				}elseif( $amount_to_pay > $loan_amount ){
					$amount_difference = $amount_to_pay - $loan_amount;
					$amount_difference = number_format($amount_difference,2);

					$notification['is_with_notification'] = true;
					$notification['message'] 			  = "You have excess amount of Php <b>{$amount_difference}</b>. You can deduct it to the remaining payments or create a new entry.";
				}  
			}
		}		
		return $notification;
	}

	public function updateAmountPaid() {
		$new_total_amount_paid = 0;
		if( $this->id > 0 ){
			$new_total_amount_paid = G_Employee_Loan_Payment_History_Helper::sqlSumAmountPaidByLoanId($this->id);
			G_Employee_Loan_Manager::updateAmountPaid($this->id, $new_total_amount_paid);
		}

		return $new_total_amount_paid;
	}

	public function updateAllFinishedLoans() {
		$total_updated_records = 0;
		$total_updated_records = G_Employee_Loan_Manager::updateAllFinishedLoans();
		return $total_updated_records;
	}

	public function addAmountPaid() {
		return G_Employee_Loan_Manager::updateAmountPaid($this->id, $this->amount_paid);
	}

	public function updateLoanStatus() {
		return G_Employee_Loan_Manager::updateLoanStatus($this->id, $this->status);
	}

	public function setGrossPay( $value = 0 ){
		$this->gross_pay = $value;
		return $this;
	}

	/*
	 * Adjust gross pay base on set payroll settings for loans grosspay limit
	 * @returns this object
	*/
	public function applyLoansGrossPayLimit( $gross_pay_amount = 0 ) {							
		if( $this->gross_pay == 0 ){						
			$this->gross_pay = $gross_pay_amount;
		}

		$sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_LOANS_GROSS_LIMIT);
        $loans_grosspay_percentage_limit = $sv->getVariableValue();         
        if( $loans_grosspay_percentage_limit > 0 ){
            $new_gross_pay_limit = $this->gross_pay * ($loans_grosspay_percentage_limit/100);
        }else{
            $new_gross_pay_limit = $this->gross_pay;
        }

        $this->gross_pay = $new_gross_pay_limit;               
        return $this;
    }

	/*
	 * @param array
	 * @return this object
	*/
	public function getScheduledUnpaidLoans( $loan_data = array() ) {
		$data = array();
		if( $this->employee_id > 0 && !empty($loan_data) ){
			$date_from = date('Y-m-d',strtotime($loan_data['date_from']));
			$date_to   = date('Y-m-d',strtotime($loan_data['date_to']));

			$fields = array('ls.id','l.loan_title','(ls.amount_to_pay - ls.amount_paid)AS balance');
			$loans_data   = G_Employee_Loan_Payment_History_Helper::sqlEmployeeScheduledUnpaidLoans($this->employee_id, $date_from, $date_to, $fields);
			$this->a_employee_loans_to_deduct = $loans_data;		
			$this->cutoff_period = "{$date_from} to {$date_to}";
			$this->date_deducted = $date_to;	
		}

		return $this;
	}

	/*
	 * Adjust employee loans deduction base on gross pay
	 * @return array
	*/
	public function adjustEmployeeLoansDeductionBaseOnGrossPay() {
		$a_loans_to_deduct = array();
		if( !empty($this->a_employee_loans_to_deduct) && $this->gross_pay > 0 ){
			$gross = $this->gross_pay;
	        $total_deducted    = 0;
	        $a_loans_to_deduct = array();
	        $loans_data 	   = $this->a_employee_loans_to_deduct;
	        foreach($loans_data as $key => $ld){                               
	            $total_deducted += $ld['balance'];
	            if( $total_deducted > $gross ){                                  
	                $new_total_deducted = $gross - ($total_deducted - $ld['balance']);
	                $a_loans_to_deduct['deducted'][$key] = array('id' => $ld['id'], 'loan_title' => $ld['loan_title'], 'balance' => $ld['balance'] , 'deducted' => $new_total_deducted);
	                $total_deducted = ($total_deducted - $ld['balance']) + $new_total_deducted;
	                break;
	            }
	            $a_loans_to_deduct['deducted'][$key] = $ld;
	            $a_loans_to_deduct['deducted'][$key]['deducted'] = $ld['balance'];
	        }

	        $a_loans_to_deduct['total_deducted'] = $total_deducted;	
	        $this->a_employee_loans_to_deduct = $a_loans_to_deduct;	        
		}

		return $this;	
	}

	public function saveEmployeeLoanDetails() {		
		$id = G_Employee_Loan_Manager::save($this);
		if( $id > 0 ){
			$this->id = $id;
		}

		return $this;
	}

	public function saveEmployeeLoanSchedules() {
		$return['is_success'] = false;
		$return['message']    = 'Error in saving loan schedules';		
		if( $this->id > 0 && !empty($this->a_loan_schedules) ){					
			foreach( $this->a_loan_schedules as $key => $schedule ){								
				$bulk_data[$key] = "(" . Model::safeSql($schedule['loan_payment_schedule']) . "," . Model::safeSql($schedule['due_amount']) . "," . Model::safeSql($schedule['employee_id']) . "," . Model::safeSql($this->id) . ")";
			}

			$fields   = array("loan_payment_scheduled_date","amount_to_pay","employee_id","loan_id");
			$schedule = new G_Employee_Loan_Payment_Schedule();
			$return = $schedule->bulkSave($bulk_data, $fields);
		}

		return $return;
	}

	public function loanBalance() {
		$loan_balance = 0;

		if( $this->id > 0 ){
			$loan_balance = $this->total_amount_to_pay - $this->amount_paid;
		}

		return $loan_balance;
	}

	/*
	 * @param array optional
	 * @returns array
	*/
	public function updateLoanSchedule( $loans_data = array() ) {
		$return   = array('message' => '');
		$is_debug = false;

		if( empty($this->a_employee_loans_to_deduct) ){
			$this->a_employee_loans_to_deduct = $loans_data;
		}

		if( $this->employee_id > 0 && !empty($this->a_employee_loans_to_deduct) ){
			$loans_to_deduct_data = $this->a_employee_loans_to_deduct['deducted'];
			$loans_total_deducted = $this->a_employee_loans_to_deduct['total_deducted'];

			$total_updated_records = 0;
			$loan_ids = array();
			foreach( $loans_to_deduct_data as $ld ){
				$lh = G_Employee_Loan_Payment_History_Finder::findById($ld['id']);
				if( $lh ){														
					$new_amount_paid = $lh->getAmountPaid() + $ld['deducted'];
					/*if( $new_amount_paid >= $lh->getAmountToPay() ){
						$lh->setAsLock();
					}*/
					$lh->setAsLock();
					$lh->setAmountPaid($new_amount_paid);
					$lh->setDefaultRemarks($this->cutoff_period);
					$lh->setDatePaid($this->date_deducted);
					$result = $lh->update();

					if( $result['is_success'] ){
						$total_updated_records++;
						$loan_ids[] = $lh->getLoanId();
					}
				}
			}

			$loan_ids = array_unique($loan_ids);
			foreach( $loan_ids as $id ){
				$this->id = $id;
				$this->updateAmountPaid();
			}

			$this->updateAllFinishedLoans();

			$return['message'] = "Total updated loans records <b>{$total_updated_records}</b>";

			if( $is_debug ){
				Utilities::displayArray($loan_ids);
				Utilities::displayArray($this->a_employee_loans_to_deduct);
				Utilities::displayArray($this);
			}
		}

		return $return;
	}

	public function getLoanBalance( $loan_title = '' ) {
		$balance = 0;		
		if( $loan_title != '' && $this->employee_id > 0 ){
			$balance = G_Employee_Loan_Helper::sqlEmployeeLoanBalanceByLoanTitle( $this->employee_id, $loan_title );
		}

		return $balance;
	}

	public function getLoanBalanceFromStartPeriod( $loan_title = '', $to = '' ) {
		$balance = 0;	

		if( $loan_title != '' && $this->employee_id > 0 && $to != '' ){
			$balance    = G_Employee_Loan_Helper::sqlEmployeeLoanTotalAmountToPayByLoanTitle( $this->employee_id, $loan_title);
			$total_paid = G_Employee_Loan_Payment_Schedule_Helper::sqlEmployeeTotalAmountPaidByLoanTitleAndUptoDate( $this->employee_id, $loan_title, $to );
			$balance -= $total_paid;
		}

		return $balance;
	}
	
	public function save() {
		return G_Employee_Loan_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Loan_Manager::delete($this);
	}

	public function getLoanData($query, $add_query) {
        return G_Employee_Loan_Helper::getLoanData($query, $add_query);
    }

    public function getLoanDataSemiMonthLoanRegister($query, $add_query) {
        return G_Employee_Loan_Helper::getLoanDataSemiMonthLoanRegister($query, $add_query);
    }   

    public function getLoanDataMonthlyLoanRegister($query, $add_query) {
        return G_Employee_Loan_Helper::getLoanDataMonthlyLoanRegister($query, $add_query);
    }  
}
?>