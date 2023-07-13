<?php
class G_Cutoff_Period {
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
		$pp          = G_Settings_Pay_Period_Finder::findById(1);
		if( $pp ){									
			$payoutday     = explode(",", $pp->getPayOutDay());
			$cutoff        = explode(",", $pp->getCutOff());
			$first_cutoff  = explode("-",$cutoff[0]);
			$second_cutoff = explode("-",$cutoff[1]);
			if($first_cutoff[1] > $second_cutoff[1]){
				return date('F', strtotime($this->start_date));
			}else{
				return date('F', strtotime($this->end_date));
			}
		}
        return date('F', strtotime($this->end_date));
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

 //monthly earnings
    public function getCutoffPeriodsByYearMonthly() {
        $data = array();
        if( $this->year_tag > 0 ){
            $year_tag = $this->year_tag;
        }else{
            $year_tag = date("Y"); //Will fetch current year
        }

        $fields = array("id","CONCAT( DATE_FORMAT(period_end, '%Y'), ' - ', DATE_FORMAT(period_end, '%M'), ' - ', IF(cutoff_number = 1, 'A', 'B') ) AS cutoff");
        
        $data = G_Monthly_Cutoff_Period_Helper::sqlCutoffPeriodsByYearTag($year_tag, $fields);

        return $data;
    }


    public function getCurrentCutoffPeriodMonthly($date = '') {
        $data['current_cutoff']['start'] = '';
        $data['current_cutoff']['end']   = '';
        $data['cutoff_number'] = 0;
        $data['id'] = 0;

        if( !empty($date) ){        
            $month = date("m",strtotime($date));
            $year  = date("Y",strtotime($date));
        
            $expected_cutoff = self::expectedCutOffPeriodsByMonthAndYearMonthly($month, $year);   


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


    public function expectedCutOffPeriodsByMonthAndYearMonthly( $month = 0, $year ){ 

        $data = array();
        if( $month > 0 && !empty($year) ){
            $fields = array("cut_off");
            $default_pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);       
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




    public function expectedCutOffPeriodsByMonthAndYear( $month = 0, $year ){ 

    	$data = array();
    	if( $month > 0 && !empty($year) ){
    		$fields = array("cut_off");
    		$default_pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);    	
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

    public function expectedCutOffPeriodsByMonthAndYearWeekly( $month = 0, $year ){ 

        $data = array();
        if( $month > 0 && !empty($year) ){
            $fields = array("cut_off");
            // echo "<pre>";
            // var_dump($fields);
            // echo "</pre>";
            $default_pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);       
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

    public function getCutoffPeriodsByPreviousNotLockYear() {
        $data = array();
        if( $this->year_tag > 0 ){
            $year_tag = $this->year_tag - 1;
        }else{
            $year_tag = date("Y") - 1; //Will fetch current year
        }

        $fields = array("id","CONCAT( DATE_FORMAT(period_end, '%Y'), ' - ', DATE_FORMAT(period_end, '%M'), ' - ', IF(cutoff_number = 1, 'A', 'B') ) AS cutoff");
        $data = G_Cutoff_Period_Helper::sqlCutoffPeriodsByYearTagAndNotLock($year_tag, $fields);

        return $data;
    }

    public function getCutoffPeriodsByYear() {
    	$data = array();
    	if( $this->year_tag > 0 ){
    		$year_tag = $this->year_tag;
    	}else{
    		$year_tag = date("Y"); //Will fetch current year
    	}

    	$fields = array("id","CONCAT( DATE_FORMAT(period_end, '%Y'), ' - ', DATE_FORMAT(period_end, '%M'), ' - ', IF(cutoff_number = 1, 'A', 'B') ) AS cutoff");
        
    	$data = G_Cutoff_Period_Helper::sqlCutoffPeriodsByYearTag($year_tag, $fields);

    	return $data;
    }

	public function getCutoffPeriodsByYearNotLock() {
    	$data = array();
    	if( $this->year_tag > 0 ){
    		$year_tag = $this->year_tag;
    	}else{
    		$year_tag = date("Y"); //Will fetch current year
    	}

    	$fields = array("id","CONCAT( DATE_FORMAT(period_end, '%Y'), ' - ', DATE_FORMAT(period_end, '%M'), ' - ', IF(cutoff_number = 1, 'A', 'B') ) AS cutoff");
        
    	$data = G_Cutoff_Period_Helper::sqlCutoffPeriodsByYearTagAndNotLock($year_tag, $fields);

    	return $data;
    }

    public function getCutoffPeriodsByYearWeekly() {
        $data = array();
        if( $this->year_tag > 0 ){
            $year_tag = $this->year_tag;
        }else{
            $year_tag = date("Y"); //Will fetch current year
        }

        $fields = array("id","CONCAT( DATE_FORMAT(period_end, '%Y'), ' - ', DATE_FORMAT(period_start, '%M'), ' - ', CASE
    WHEN cutoff_number = 1 THEN 'A'
    WHEN cutoff_number = 2 THEN 'B'
    WHEN cutoff_number = 3 THEN 'C'
    WHEN cutoff_number = 4 THEN 'D'
    ELSE 'E'
    END ) AS cutoff");
        
        $data = G_Weekly_Cutoff_Period_Helper::sqlCutoffPeriodsByYearTag($year_tag, $fields);
        
        return $data;
    }

	public function getCutoffPeriodsByYearWeeklyNotLock() {
        $data = array();
        if( $this->year_tag > 0 ){
            $year_tag = $this->year_tag;
        }else{
            $year_tag = date("Y"); //Will fetch current year
        }

        $fields = array("id","CONCAT( DATE_FORMAT(period_end, '%Y'), ' - ', DATE_FORMAT(period_start, '%M'), ' - ', CASE
    WHEN cutoff_number = 1 THEN 'A'
    WHEN cutoff_number = 2 THEN 'B'
    WHEN cutoff_number = 3 THEN 'C'
    WHEN cutoff_number = 4 THEN 'D'
    ELSE 'E'
    END ) AS cutoff");
        
        $data = G_Weekly_Cutoff_Period_Helper::sqlCutoffPeriodsByYearTagNotLock($year_tag, $fields);
        
        return $data;
    }

    public function getCutOffIdByYearMonthAndPeriod($payroll_period) {
		$a = explode('-', $payroll_period);
		$year = $a[0];
		$month = $a[1];
		
		if($a[2] == 'A') {
		 	$period = 1;
		} else {
			$period = 2;
		}

		$period_id = G_Cutoff_Period_Finder::findByYearMonthAndPeriod($year, $month, $period);

		if(!$period_id) {
			$period_id = G_Cutoff_Period_Finder::findByYearMonthAndPeriodEnd($year, $month, $period);
		}

    	return $period_id;
    }

    public function getValidCutOffPeriodsByMonthAndYear( $month = 0, $year ) {
    	$data = array();

    	if( $month > 0 && !empty($year) ){
    		$fields = array("cut_off");
    		$default_pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod($fields);
    		if( !empty($default_pay_period) ){
    			$cutoff_data = explode(",", $default_pay_period['cut_off']);    
    			$pattern[]   = $cutoff_data[0];
				$pattern[]   = $cutoff_data[1]; 

    			foreach( $cutoff_data as $key => $cutoff ){
    				//$days_cutoff = explode("-", $to_generate_cutoff);    				
    				$days_cutoff = explode("-", $cutoff);
					$start_day   = $days_cutoff[0];
					$end_day     = $days_cutoff[1];

					$cutoff_start_date = "{$year}-{$month}-{$end_day}";

					if( $month == 2 || $end_day >= 30 ){
						$cutoff_end_date = date("Y-m-t", strtotime("{$year}-{$month}-1"));
					}else{
						$cutoff_end_date   = "{$year}-{$month}-{$end_day}";
					}

					$cutoff    = Tools::getCutOffPeriod($cutoff_start_date, $pattern);
					$cutoffs[$key]['start_date'] = $cutoff['start'];
					$cutoffs[$key]['end_date']   = $cutoff['end'];					
    			}
    			
    			$periods = G_Cutoff_Period_Finder::findByMonthYear($month, $year);      
    			foreach($cutoffs as $cutoff){
    				$start_date[] = "'" . $cutoff['start_date'] . "'";
    				$end_date[]   = "'" . $cutoff['end_date'] . "'";
    			}

    			$data = G_Cutoff_Period_Finder::findAllByPeriodStartAndPeriodEnd($start_date, $end_date);      
    		}
    	}

    	return $data;
    }

    public function getCutoffDataByStartAndEndDate($fields = array()) {
    	$data = array();

    	if( $this->start_date != '' && $this->end_date != '' ){
    		$data = G_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd( $this->start_date, $this->end_date, $fields );
    	} 

    	return $data;
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
    
    /*
		Usage : 
		$month = 10;
		$year  = 2015;

		$period = new G_Cutoff_Period();
		$data = $period->generateCutoffPeriodByMonthAndYear($month, $year);
		Utilities::displayArray($data);
    */

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
     		
    		$periods       = G_Cutoff_Period_Helper::sqlAllByPeriodStartAndPeriodEnd($period_start, $period_end);     		
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

    /*
		Usage :
		$date = date("Y-m-d");

		$cp = new G_Cutoff_Period();
		$data = $cp->getCurrentCutoffPeriod($date);
    */

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
    				$cutoff_data = G_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($cutoff['start_date'],$cutoff['end_date'],$fields);
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

        public function getCurrentCutoffPeriodWeekly($date = '') {
        $data['current_cutoff']['start'] = '';
        $data['current_cutoff']['end']   = '';
        $data['cutoff_number'] = 0;
        $data['id'] = 0;

        if( !empty($date) ){        
            $month = date("m",strtotime($date));
            $year  = date("Y",strtotime($date));
        
            $expected_cutoff = self::expectedCutOffPeriodsByMonthAndYearWeekly($month, $year);            
            // echo "<br>";
            // echo "<pre>";
            // var_dump($expected_cutoff);
            // echo "</pre>";

            $date_compare    = strtotime($date);            
            foreach( $expected_cutoff as $key => $cutoff ){
                $cutoff_period = $key;
                $start_date     = strtotime($cutoff['start_date']);
                $end_date       = strtotime($cutoff['end_date']);

                //if( $start_date <= $date_compare && $end_date >= $date_compare ){
                    $fields = array("id","is_lock");
                    $cutoff_data = G_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($cutoff['start_date'],$cutoff['end_date'],$fields);
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


    public function getCurrentCutoffPeriodByDates($dates = '') {
    	$data['current_cutoff']['start'] = '';
		$data['current_cutoff']['end']   = '';
		$data['cutoff_number'] = 0;
		$data['id'] = 0;

    	if( !empty($dates) ){    	
    		//$expected_cutoff = self::expectedCutOffPeriodsByMonthAndYear($month, $year);    
    		$expected_cutoff = G_Cutoff_Period_Finder::findByPeriod($dates[0], $dates[1]);
			$cutoff_period  = $expected_cutoff->getCutoffNumber();
			$start_date     = $expected_cutoff->getStartDate();
			$end_date       = $expected_cutoff->getEndDate();

			$fields = array("id","is_lock");
			$cutoff_data = G_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($start_date,$end_date,$fields);
			$data['id']   = Utilities::encrypt($expected_cutoff->getId());
			$data['date'] = $expected_cutoff->getStartDate();
			$data['is_lock'] = $expected_cutoff->getIsLock();
			$data['current_cutoff']['start'] = $start_date;
			$data['current_cutoff']['end']   = $end_date;
			$data['cutoff_number'] = $cutoff_period;
    	}

    	return $data;
    }

    public function getMonthCutoffPeriods( $month = '', $year = '' ) {

    	$cutoff_periods = array();
    	$data = self::expectedCutOffPeriodsByMonthAndYear($month, $year);

    	if( !empty($data) ){
    		foreach( $data as $key => $value ){
    			$cutoff_data = G_Cutoff_Period_Finder::findByPeriod($value['start_date'], $value['end_date']);
    			if( !empty($cutoff_data) ){
    				$cutoff_periods[$key] = $cutoff_data;
    			}    			
    		} 
    	}

    	return $cutoff_periods;
    }

    public function getCurrentCutoffPeriod_depre($date = '') {
    	$data = array();

    	if( !empty($date) ){
    		$data = G_Cutoff_Period_Helper::sqlGetCurrentCutoffPeriod($date);
    	}

    	return $data;
    }

    public function getDefaultCutoffP() {
    	$data = array();
    	$data = G_Cutoff_Period_Helper::sqlGetAllCutOffPeriods();
    	return $data;
    }

    public function generateCutOffPeriodsByDate($date) {
		return G_Cutoff_Period_Helper::generateCutOffPeriodsByDate($date);
	}

	/*
		Create initial cutoff periods
		Usage : 
		$data[1]['a']      = 1;
		$data[1]['b']      = 15;
		$data[1]['payday'] = 16;
		$data[2]['a']      = 16;
		$data[2]['b']      = 31;
		$data[2]['payday'] = 31;
		$c = new G_Cutoff_Period();
		$return = $c->generateIniCutOffPeriods($data);	//returns array
	*/

	public function generateIniCutOffPeriods( $data ) {




		$return['is_success'] = false;
		$return['message']    = "Cannot create records";

		if( !empty($data) ){
			$first_cutoff_a       = $data[1]['a'];
			$first_cutoff_b	      = $data[1]['b'];
			$first_cutoff_payday  = $data[1]['payday'];		
			$second_cutoff_a 	  = $data[2]['a'];
			$second_cutoff_b      = $data[2]['b'];
			$second_cutoff_payday = $data[2]['payday'];

			$c     = new G_Cutoff_Period();
            //old
			$cycle = G_Salary_Cycle_Finder::findDefault();
            //$cycle = 12;				
           
			//Generate previous 3months and current cutoff					
			$pattern[] = "{$first_cutoff_a}-{$first_cutoff_b}";
			$pattern[] = "{$second_cutoff_a}-{$second_cutoff_b}";

			if( $this->number_of_months > 0 ){
				$number_of_cutoff_to_generate = $this->number_of_months;
			}else{
				$number_of_cutoff_to_generate = 4; 
			}

			for( $counter = 0; $counter < $number_of_cutoff_to_generate; $counter++ ){			
				//$date  = date("Y-m-1");

				$current_year = date("Y");
				$date  = "{$current_year}-12-01";
				$date  = date("Y-m-1",strtotime("{$date} -{$counter} months"));	

				$month = date("m",strtotime($date));
				$year  = date("Y",strtotime($date));
				
				$first_date  = "{$year}-{$month}-{$first_cutoff_a}";
				$second_date = "{$year}-{$month}-{$second_cutoff_a}";

				$first_date = date("Y-m-d", strtotime("-1 month",strtotime($first_date)));	

				$first_cutoff  = Tools::getCutOffPeriod($first_date, $pattern);
				$second_cutoff = Tools::getCutOffPeriod($second_date, $pattern);
			
				$first_year   = date("Y",strtotime($first_cutoff['end']));
				$first_month  = date("m",strtotime($first_cutoff['end']));
				$second_year  = date("Y",strtotime($second_cutoff['end']));
				$second_month = date("m",strtotime($second_cutoff['end']));

				if( ($first_cutoff_payday == 31) || ($first_month == 2 && $first_cutoff_payday > 28) ){
					$first_payday = date("Y-m-t", strtotime($first_cutoff['end']));
				}else{
					$first_payday  = "{$first_year}-{$first_month}-{$first_cutoff_payday}";
				}

				if( ($second_cutoff_payday == 31) || ($second_month == 2 && $second_cutoff_payday > 28) ){
					$second_payday = date("Y-m-t", strtotime($first_cutoff['end']));
				}else{
					$second_payday = "{$second_year}-{$second_month}-{$second_cutoff_payday}";
				}

				//echo $first_cutoff['start'] . " - " . $first_cutoff['end'] . " / "  . $second_cutoff['start'] . " / " . $second_cutoff['end'] . " <br />";

				//First Cutoff
                $c = G_Cutoff_Period_Finder::findAllCutoffByYear($current_year);

				$is_exists = G_Cutoff_Period_Helper::isCutoffPeriodStartAndEndExists($first_cutoff['start'], $first_cutoff['end']);			
				if( !$is_exists ){
					$year = date("Y",strtotime($first_cutoff['end']));
					$gcp = new G_Cutoff_Period();
					$gcp->setYearTag($year);
					$gcp->setStartDate($first_cutoff['start']);
					$gcp->setEndDate($first_cutoff['end']);
					$gcp->setCutoffNumber($first_cutoff['cutoff_number']);
					$gcp->setPayoutDate($first_payday);
					$gcp->setSalaryCycleId(G_Salary_Cycle::TYPE_SEMI_MONTHLY);
					$gcp->setIsLock(G_Cutoff_Period::NO);
					$gcp->save();
				}

				//Second Cutoff		
				$is_exists = G_Cutoff_Period_Helper::isCutoffPeriodStartAndEndExists($second_cutoff['start'], $second_cutoff['end']);			
				if( !$is_exists ){	
					$year = date("Y",strtotime($second_cutoff['end']));
					$gcp = new G_Cutoff_Period();
					$gcp->setYearTag($year);
					$gcp->setStartDate($second_cutoff['start']);
					$gcp->setEndDate($second_cutoff['end']);
					$gcp->setCutoffNumber($second_cutoff['cutoff_number']);
					$gcp->setPayoutDate($second_payday);
					$gcp->setSalaryCycleId(G_Salary_Cycle::TYPE_SEMI_MONTHLY);
					$gcp->setIsLock(G_Cutoff_Period::NO);
					$gcp->save();
				}

				$cutoffs[] = $first_cutoff['start'] . " - " . $first_cutoff['end'] . " / " . $second_cutoff['start']  . " - " . $second_cutoff['end'];
			}	

			//Update settings pay period
			$pay_period = G_Settings_Pay_Period_Finder::findDefault();			
			if( $pay_period ){
				$new_cutoff     = "{$first_cutoff_a}-{$first_cutoff_b},{$second_cutoff_a}-{$second_cutoff_b}";
				$new_payout_day = "{$first_cutoff_payday},{$second_cutoff_payday}";
				$pay_period->setCutOff($new_cutoff);
				$pay_period->setPayOutDay($new_payout_day);
				$pay_period->updateDefault();
			}

			$return['is_success'] = true;
			$return['message']    = "Cutoff periods was successfully created";
			$return['cutoffs']    = $cutoffs;
		}

		return $return;
		
	}

	public function generateIniCutOffPeriodsByYear( $data ) {
		$return['is_success'] = false;
		$return['message']    = "Cannot create records";

		if( !empty($data) ){
			$first_cutoff_a       = $data[1]['a'];
			$first_cutoff_b	      = $data[1]['b'];
			$first_cutoff_payday  = $data[1]['payday'];		
			$second_cutoff_a 	  = $data[2]['a'];
			$second_cutoff_b      = $data[2]['b'];
			$second_cutoff_payday = $data[2]['payday'];

			$c     = new G_Cutoff_Period();
			$cycle = G_Salary_Cycle_Finder::findDefault();				

			//Generate previous 3months and current cutoff					
			$pattern[] = "{$first_cutoff_a}-{$first_cutoff_b}";
			$pattern[] = "{$second_cutoff_a}-{$second_cutoff_b}";

			if( $this->number_of_months > 0 ){
				$number_of_cutoff_to_generate = $this->number_of_months;
			}else{
				$number_of_cutoff_to_generate = 4; 
			}

			for( $counter = 0; $counter < $number_of_cutoff_to_generate; $counter++ ){			
				$current_year = $data['year'];
				$date  = "{$current_year}-12-01";
				$date  = date("Y-m-1",strtotime("{$date} -{$counter} months"));						
				$month = date("m",strtotime($date));
				$year  = date("Y",strtotime($date));
				
				$first_date  = "{$year}-{$month}-{$first_cutoff_a}";			
				$second_date = "{$year}-{$month}-{$second_cutoff_a}";

				$first_cutoff  = Tools::getCutOffPeriod($first_date, $pattern);
				$second_cutoff = Tools::getCutOffPeriod($second_date, $pattern);
			
				$first_year   = date("Y",strtotime($first_cutoff['end']));
				$first_month  = date("m",strtotime($first_cutoff['end']));
				$second_year  = date("Y",strtotime($second_cutoff['end']));
				$second_month = date("m",strtotime($second_cutoff['end']));

				if( ($first_cutoff_payday == 31) || ($first_month == 2 && $first_cutoff_payday > 28) ){
					$first_payday = date("Y-m-t", strtotime($first_cutoff['end']));
				}else{
					$first_payday  = "{$first_year}-{$first_month}-{$first_cutoff_payday}";
				}

				if( ($second_cutoff_payday == 31) || ($second_month == 2 && $second_cutoff_payday > 28) ){
					$second_payday = date("Y-m-t", strtotime($first_cutoff['end']));
				}else{
					$second_payday = "{$second_year}-{$second_month}-{$second_cutoff_payday}";
				}

				//echo $first_cutoff['start'] . " - " . $first_cutoff['end'] . " / "  . $second_cutoff['start'] . " / " . $second_cutoff['end'] . " <hr />";

				//First Cutoff
				$is_exists = G_Cutoff_Period_Helper::isCutoffPeriodStartAndEndExists($first_cutoff['start'], $first_cutoff['end']);			
				if( !$is_exists ){
					$year = date("Y",strtotime($first_cutoff['end']));
					$gcp = new G_Cutoff_Period();
					$gcp->setYearTag($year);
					$gcp->setStartDate($first_cutoff['start']);
					$gcp->setEndDate($first_cutoff['end']);
					$gcp->setCutoffNumber($first_cutoff['cutoff_number']);
					$gcp->setPayoutDate($first_payday);
					$gcp->setSalaryCycleId(G_Salary_Cycle::TYPE_SEMI_MONTHLY);
					$gcp->setIsLock(G_Cutoff_Period::NO);
					$gcp->save();
				}

				//Second Cutoff		
				$is_exists = G_Cutoff_Period_Helper::isCutoffPeriodStartAndEndExists($second_cutoff['start'], $second_cutoff['end']);			
				if( !$is_exists ){	
					$year = date("Y",strtotime($second_cutoff['end']));
					$gcp = new G_Cutoff_Period();
					$gcp->setYearTag($year);
					$gcp->setStartDate($second_cutoff['start']);
					$gcp->setEndDate($second_cutoff['end']);
					$gcp->setCutoffNumber($second_cutoff['cutoff_number']);
					$gcp->setPayoutDate($second_payday);
					$gcp->setSalaryCycleId(G_Salary_Cycle::TYPE_SEMI_MONTHLY);
					$gcp->setIsLock(G_Cutoff_Period::NO);
					$gcp->save();
				}

				$cutoffs[] = $first_cutoff['start'] . " - " . $first_cutoff['end'] . " / " . $second_cutoff['start']  . " - " . $second_cutoff['end'];
			}	

			//Update settings pay period
			$pay_period = G_Settings_Pay_Period_Finder::findDefault();			
			if( $pay_period ){
				$new_cutoff     = "{$first_cutoff_a}-{$first_cutoff_b},{$second_cutoff_a}-{$second_cutoff_b}";
				$new_payout_day = "{$first_cutoff_payday},{$second_cutoff_payday}";
				$pay_period->setCutOff($new_cutoff);
				$pay_period->setPayOutDay($new_payout_day);
				$pay_period->updateDefault();
			}

			$return['is_success'] = true;
			$return['message']    = "Cutoff periods was successfully created";
			$return['cutoffs']    = $cutoffs;
		}

		return $return;
		
	}


	public function expectedCutoffPeriodByYear( $year = '' ) {
		$data = array();

		$pay_period = G_Settings_Pay_Period_Helper::sqlDefaultPayPeriod();
		$part_cutoff_periods = explode(",", $pay_period['cut_off']);
		$a_cutoff   = explode("-", $part_cutoff_periods[0]);
		$b_cutoff   = explode("-", $part_cutoff_periods[1]);
		$payout_day = explode(",",$pay_period['payout_day']);

		$first_cutoff_a       = $a_cutoff[0];
		$first_cutoff_b	      = $a_cutoff[1];
		$first_cutoff_payday  = $payout_day[0];		
		$second_cutoff_a 	  = $b_cutoff[0];
		$second_cutoff_b      = $b_cutoff[1];
		$second_cutoff_payday = $payout_day[1];		

		$cycle = G_Salary_Cycle_Finder::findDefault();	

		$pattern[] = "{$first_cutoff_a}-{$first_cutoff_b}";
		$pattern[] = "{$second_cutoff_a}-{$second_cutoff_b}";

		$number_of_cutoff_to_generate = 11; 

		for( $counter = 0; $counter <= $number_of_cutoff_to_generate; $counter++ ){			
			//$date  = date("Y-m-1");
			if( $year == '' ){
				$year_tag = date("Y");
			}else{
				$year_tag = $year;
			}

			$date  = "{$year_tag}-12-01";			
			$date  = date("Y-m-1",strtotime("{$date} -{$counter} months"));						
			$month = date("m",strtotime($date));
			$year  = date("Y",strtotime($date));
			
			$first_date  = "{$year}-{$month}-{$first_cutoff_a}";
			$second_date = "{$year}-{$month}-{$second_cutoff_a}";

			$first_cutoff  = Tools::getCutOffPeriod($first_date, $pattern);
			$second_cutoff = Tools::getCutOffPeriod($second_date, $pattern);
		
			$first_year   = date("Y",strtotime($first_cutoff['end']));
			$first_month  = date("m",strtotime($first_cutoff['end']));
			$second_year  = date("Y",strtotime($second_cutoff['end']));
			$second_month = date("m",strtotime($second_cutoff['end']));

			if( ($first_cutoff_payday == 31) || ($first_month == 2 && $first_cutoff_payday > 28) ){
				$first_payday = date("Y-m-t", strtotime($first_cutoff['end']));
			}else{
				$first_payday  = "{$first_year}-{$first_month}-{$first_cutoff_payday}";
			}

			if( ($second_cutoff_payday == 31) || ($second_month == 2 && $second_cutoff_payday > 28) ){
				$second_payday = date("Y-m-t", strtotime($first_cutoff['end']));
			}else{
				$second_payday = "{$second_year}-{$second_month}-{$second_cutoff_payday}";
			}

			$month_index = date("F",strtotime($first_cutoff['end']));

			$data[$month_index]['a'] = $first_cutoff['start'] . "/" . $first_cutoff['end'];
			$data[$month_index]['b'] = $second_cutoff['start'] . "/" . $second_cutoff['end'];
		}

		return $data;
		
	}
	
	public function generatePayrollPeriodByYear($year) {
		return G_Cutoff_Period_Helper::generatePayrollPeriodByYear($year);
	}

	/*
		Usage :
		$cp = new G_Cutoff_Period();
		$cp->generateCurrentCutoffPeriod(); //Will generate, if not exists, current cutoff period
	*/

	public function generateCurrentCutoffPeriod() {		
		$year          = date('Y');
		$date    	   = date('Y-m-d');
		$cycle   	   = G_Salary_Cycle_Finder::findDefault();		
		if( $cycle ){
			$current       = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
			$payout_date   = Tools::getPayoutDate($current['start'], $cycle->getCutOffs(), $cycle->getPayoutDays());
			$period_start  = $current['start'];
			$period_end    = $current['end'];
			$cutoff_number = $current['cutoff_number'];
			$cycle_id      = 2;

			$is_current_pay_period_exists = G_Cutoff_Period_Helper::sqlIsCutoffPeriodExists($period_start, $period_end);
			if( $is_current_pay_period_exists <= 0 ){
				//Generate current cutoff
				$this->year_tag         = $year;
				$this->start_date       = $period_start;
				$this->end_date         = $period_end;
				$this->payout_date      = $payout_date;
				$this->salary_cycle_id  = $cycle_id;
				$this->cutoff_number	= $cutoff_number;
				$this->is_lock  		= self::NO;
				$this->is_payroll_generated = self::NO;
				$this->save();
			}
		}
	}

	/*
		Create initial cutoff periods
		Usage : 
		$data[1]['a']      = 1;
		$data[1]['b']      = 15;
		$data[1]['payday'] = 16;
		$data[2]['a']      = 16;
		$data[2]['b']      = 31;
		$data[2]['payday'] = 31;
		$c = new G_Cutoff_Period();
		$return = $c->generateIniCutOffPeriods($data);	//returns array
	*/

	public function generateYearlyCutoffPeriods() {
		$year   = date('Y');
		$total_cutoff = G_Cutoff_Period_Helper::countTotalCutoffByYear($year);
		if( $total_cutoff <= 0 ){
			//Generate cutoff periods
			$pp = G_Settings_Pay_Period_Finder::findDefault();
			if( $pp ){
				$cutoff   = explode(",", $pp->getCutOff());
				$cutoff_a = explode("-", $cutoff[0]); 
				$cutoff_b = explode("-", $cutoff[1]);
				$payout   = explode(",", $pp->getPayOutDay());

				$data[1]['a']      = $cutoff_a[0];
				$data[1]['b']      = $cutoff_a[1];
				$data[1]['payday'] = $payout[0];
				$data[2]['a']      = $cutoff_b[0];;
				$data[2]['b']      = $cutoff_b[1];;
				$data[2]['payday'] = $payout[1];
			}
		} 
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
				$cutoff_data = G_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($previous_cutoff['start'], $previous_cutoff['end'], $field);				
				if( empty($cutoff_data) ){
					$month = date("m",strtotime($previous_cutoff['start']));
					$year  = date("Y",strtotime($previous_cutoff['start']));
					self::generateCutoffPeriodByMonthAndYear($month, $year);
					$cutoff_data = G_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($previous_cutoff['start'], $previous_cutoff['end'], $field);
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
				$cutoff_data = G_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($next_cutoff['start'], $next_cutoff['end'], $field);

				if( empty($cutoff_data) ){
					$month = date("m",strtotime($next_cutoff['start']));
					$year  = date("Y",strtotime($next_cutoff['start']));
					self::generateCutoffPeriodByMonthAndYear($month, $year);
					$cutoff_data = G_Cutoff_Period_Helper::sqlCutoffPeriodByPeriodStartAndPeriodEnd($next_cutoff['start'], $next_cutoff['end'], $field);
				}
				$cutoff_data = Tools::encryptArrayIndexValue("id",$cutoff_data);

				$data['eid']        = $cutoff_data['id'];
				$data['start_date'] = $next_cutoff['start'];
				$data['end_date']   = $next_cutoff['end'];
			}
		}

		return $data;
	}
	
	public function save() {		
		return G_Cutoff_Period_Manager::save($this);
	}
	
	public function savePayrollPeriodByYear($year) {
		$this->deleteAllByYear($year);
		return G_Cutoff_Period_Helper::savePayrollPeriodByYear($year);
	}
	
	public function lockAllPayrollPeriodBySelectedYear($selected_year) {		
		return G_Cutoff_Period_Manager::lockAllPayrollPeriodBySelectedYear($selected_year);
	}
	
	/*
	 * Locks payroll period data for editing
	 * @returns array
	*/
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

			$return = G_Cutoff_Period_Manager::lockPayrollPeriod($this);
		}

		return $return;
	}
	
	/*old unlock payperiod	
	public function unLockPayrollPeriod() {		
		return G_Cutoff_Period_Manager::unLockPayrollPeriod($this);
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

            
            $return = G_Cutoff_Period_Manager::unLockPayrollPeriod($this);
        }

        return $return;
    }


	public function deleteAllCutOffPeriods() {
		G_Cutoff_Period_Manager::deleteAllCutOffPeriods();
		return $this;
	}
	
	public function deleteAllByYear($year) {
		G_Cutoff_Period_Manager::deleteAllByYear($year);
		return $this;
	}
}
?>