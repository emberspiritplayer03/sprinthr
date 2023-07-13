<?php
class G_Yearly_Bonus extends Yearly_Bonus_Release_Date {

	const YEARLY_BONUS_DESCRIPTION_1 = '13th Month';
	const CEILING_NON_TAXABLE        = 90000;

	public function __construct() {		
	}

	private function isCutoffPeriodLock() {
		//Check if cutoff period is lock
		if($this->frequency == 1){
			$is_lock = G_Cutoff_Period_Helper::isPeriodLockByDate($this->cutoff_start_date, $this->cutoff_end_date);
		}
		else{
			$is_lock = G_Weekly_Cutoff_Period_Helper::isPeriodLockByDate($this->cutoff_start_date, $this->cutoff_end_date);
		}
		
		if( $is_lock == G_Cutoff_Period::YES ){
			return true;
		}else{
			return false;
		}
	}

	/**
	* Process 13thmonth
	*
	* @param array yearly_bonus_data - year, action, selected
	* @return array
	*/
	public function processYearlyBonus( $yearly_bonus_data = array() ) {
		$return = array('is_success' => false, 'message' => '0 record(s) processed');
		$data   = array();

		$return['is_success'] = false;
		$return['message']    = 'Cannot save record';

		if( !empty($yearly_bonus_data['cutoff']) ){						
			$a_cutoffs = explode("/", $yearly_bonus_data['cutoff']);			
			$this->cutoff_start_date = trim($a_cutoffs[0]);
			$this->cutoff_end_date   = trim($a_cutoffs[1]);
			$this->year_released 	 = $yearly_bonus_data['year'];
		}else{			
			return $return;
		}
		
		if( $this->isCutoffPeriodLock() ){
			$return = array('is_success' => false, 'message' => 'Selected cutoff is already lock. Cannot process yearly bonus!');
			return $return;
		} 

	
		if( !empty($yearly_bonus_data) && isset($yearly_bonus_data['year']) ){									
			//Check if cutoff start and enddate already exist
			if( $id = G_Yearly_Bonus_Release_Date_Helper::isCutOffStartAndEndDateExist($this) ){
 				$this->id = $id;
 				$this->modified = date("Y-m-d H:i:s");	
			}else{
				$this->created  = date("Y-m-d H:i:s");	
			}
			
			// $data = $this->createBulkData($yearly_bonus_data['action'], $yearly_bonus_data['selected'], $yearly_bonus_data['percentage'], $yearly_bonus_data['deduct_tardiness']);

			$data = $this->createBulkDataRev($yearly_bonus_data['action'], $yearly_bonus_data['selected'], $yearly_bonus_data['percentage'], $yearly_bonus_data['deduct_tardiness'],$yearly_bonus_data['cutoff'], $yearly_bonus_data['frequency'],$yearly_bonus_data['payroll_start']);
			//We need to delete existing data before recreate

			//$return['data'] = $data;
		
			G_Yearly_Bonus_Release_Date_Manager::deleteExistingDataByEmployeeIdsAndByStartAndEndMonthAndYearRev($data['employee_ids'], $this->cutoff_start_date, $this->cutoff_end_date, $this->year_released);				
			
			$fields = array('employee_id', 'total_basic_pay', 'amount','taxable_amount','tax','total_bonus_amount','year_released','month_start','month_end','cutoff_start_date','cutoff_end_date','percentage','deducted_amount','created','modified','generate_based','deduction_month_start', 'deduction_month_end','payroll_start_date');

			$total_inserted = G_Yearly_Bonus_Release_Date_Manager::bulkInsertData($data['yearly_bonus'], $fields);
			if( $total_inserted <= 0 ){
				$total_inserted = 0;
			}

			$return['is_success'] = true;
			$return['message']    = 'Total records processed ' . $total_inserted;
			
		}

		return $return;
	}

	/**
	 * Create bulk data for yearly bonus
	 *
	 * @param int action - 1 = selected / 2 = all
	 * @param array selected = employee pkid list
	 * @return array
	*/
	public function createBulkData( $action = 0, $selected = array(), $percentage = 0, $deduct_tardiness = 0){	
		$data = array();

		if($cutoff_period != "" ){

		}

			$a_cutoffs = explode("/", $cutoff_period);			
			$cutoff_start_date = trim($a_cutoffs[0]);
			$cutoff_end_date   = trim($a_cutoffs[1]);
			

		$e = new G_Employee();
		$query['year'] = $this->year_released;
		$add_query 	   = '';

		$now = date('Y-m-d');
		$p   = G_Cutoff_Period_Finder::findByDate($now);
	    if ($p) {
	        $cutoff_id = $p->getId();		        
	    }		

	    $start_date = date("Y") . "-" . $this->month_start . "-01";
	    $start_date = date("Y-m-d",strtotime($start_date));
	    $end_date   = date("Y") . "-" . $this->month_end . "-01";
	    $end_date   = date("Y-m-t",strtotime($end_date));

	    $query['start_date'] = $start_date;
	    $query['end_date']   = $end_date;


	    $query['percentage'] 			= $percentage;
	    $query['deduct_tardiness']   	= $deduct_tardiness;
	   
		switch ($action) {
			case 1: //Selected				
				$query['employee_ids'] = $selected;		
				$yearly_bonus_data 	   = $e->getEmployeesYearlyBonus($query, $add_query);										
				break;
			case 2: //All
				$yearly_bonus_data 	   = $e->getEmployeesYearlyBonus($query, $add_query);		
				break;
			default:					
				break;
		}



		$to_earnings_data = array();
		if( $yearly_bonus_data ){								
			$yearly_bonus = array();
			$employee_ids = array();
			$year         = date("Y");
			foreach( $yearly_bonus_data as $key => $ybd ){
				$employee_ids[] = $ybd['employee_pkid'];

				//Taxable - Nontaxable amount
				$taxable_nontaxable = array();
				$taxable_nontaxable = $this->computeTaxableAmount($ybd['sum_yearly_bonus'], $ybd['number_dependent']);


				$yearly_bonus[] = "(" . Model::safeSql($ybd['employee_pkid']) . "," . Model::safeSql($ybd['sum_yearly_bonus']) . "," . Model::safeSql($taxable_nontaxable['taxable_amount']) . "," . Model::SafeSql($taxable_nontaxable['tax']) . "," . Model::safeSql($taxable_nontaxable['new_amount']) . "," . Model::safeSql($year) . "," . Model::safeSql($this->month_start) . "," . Model::safeSql($this->month_end) . "," . Model::safeSql($this->cutoff_start_date) . "," . Model::safeSql($this->cutoff_end_date) . "," . Model::safeSql($query['percentage']) ."," . Model::safeSql($ybd['deducted_amount']) .",". Model::safeSql($this->created) . "," . Model::safeSql($this->modified) . ")";
			}
		}			

		$data['yearly_bonus'] = $yearly_bonus;
		$data['employee_ids'] = $employee_ids;
		
		return $data;
	}

 //new
public function createBulkDataRev( $action = 0, $selected = array(), $percentage = 0, $deduct_tardiness = 0,$cutoff_period = "", $frequency, $payroll_start ){	
		$data = array();

		if($cutoff_period != "" ){

		}

			$a_cutoffs = explode("/", $cutoff_period);			
			$cutoff_start_date = trim($a_cutoffs[0]);
			$cutoff_end_date   = trim($a_cutoffs[1]);

		$e = new G_Employee();
		$query['year'] = $this->year_released;
		$add_query 	   = '';

		$now = date('Y-m-d');
		if($frequency == 1){

			$p   = G_Cutoff_Period_Finder::findByDate($now);
		}
		else{
			$p   = G_Weekly_Cutoff_Period_Finder::findByDate($now);
		}
		
	    if ($p) {
	        $cutoff_id = $p->getId();		        
	    }		

	    //$cutoff_end_date = date('Y-m-d', $cutoff_end_date);
	    $payroll_end = explode('-',$cutoff_end_date);
	    $end_month = $payroll_end[1];
	    $end_day = $payroll_end[2];
		
	    //checking if may na generate na 13th month
	    $has_generated = G_Yearly_Bonus_Release_Date_Finder::checkBonusGeneration(date("Y"));

	    if($has_generated){

	    	if($cutoff_start_date != $has_generated->getCutoffStartDate() && $cutoff_end_date != $has_generated->getCutoffEndDate())
	    	{

	    		 $cutoff_end = $has_generated->getCutoffEndDate();
	    		 $start_date = date('Y-m-d', strtotime($cutoff_end. ' +1 days'));

	    		 $adj = explode('-', $start_date);
	    		 $payroll_start = $adj[1];
	    	}
	    	else{

	    		$start_date = date("Y") . "-" . $payroll_start . "-01"; // payroll start
	    	}
	    	

	    }
	    else{
	    	 $start_date = date("Y") . "-" . $payroll_start . "-01"; // payroll start
	    }

	    //var_dump($start_date);exit();

	   
	    $start_date = date("Y-m-d",strtotime($start_date));
	  
	    $end_date   = date("Y") . "-" . $end_month . "-" .$end_day; //based sa cutoff release
	    $end_date   = date("Y-m-d",strtotime($end_date));

	    $query['start_date'] = $start_date;
	    $query['end_date']   = $end_date;

	    //$this->month $this->month_end  = limit ng deduction
	    //getting tardiness
	    //example feb -august

	    $deduction_month_start =  $this->month_start;
	    $deduction_month_end = $this->month_end;

	    $deduction_month = array();
	    if($deduct_tardiness > 0){


	    	 for($x = $this->month_start; $x <= $this->month_end; $x++){
	    	 	array_push($deduction_month, $x);
	    	 }

	    }
	    else{
	    	$deduction_month_start = 0;
	    	$deduction_month_end = 0;
	    }

	    $query['deduction_month'] = $deduction_month;
	    $query['percentage'] 			= $percentage;
	    $query['deduct_tardiness']   	= $deduct_tardiness;

	    $query['frequency']   	= $frequency;


	    //var_dump($query);exit();
	   
		switch ($action) {
			case 1: //Selected				
				$query['employee_ids'] = $selected;		
				$yearly_bonus_data 	   = $e->getEmployeesYearlyBonus($query, $add_query);										
				break;
			case 2: //All
				$yearly_bonus_data 	   = $e->getEmployeesYearlyBonusRev($query, $add_query, $cutoff_start_date);		
				break;
			default:					
				break;
		}



		$to_earnings_data = array();
		if( $yearly_bonus_data ){								
			$yearly_bonus = array();
			$employee_ids = array();
			$year         = date("Y");
			foreach( $yearly_bonus_data as $key => $ybd ){
				$employee_ids[] = $ybd['employee_pkid'];

				//Taxable - Nontaxable amount
				$taxable_nontaxable = array();
				$taxable_nontaxable = $this->computeTaxableAmount($ybd['sum_yearly_bonus'], $ybd['number_dependent']);


				$yearly_bonus[] = "(" . Model::safeSql($ybd['employee_pkid']) . ",".  Model::safeSql($ybd['total_basic_pay']) . "," . Model::safeSql($ybd['sum_yearly_bonus']) . "," . Model::safeSql($taxable_nontaxable['taxable_amount']) . "," . Model::SafeSql($taxable_nontaxable['tax']) . "," . Model::safeSql($taxable_nontaxable['new_amount']) . "," . Model::safeSql($year) . "," . Model::safeSql($payroll_start) . "," . Model::safeSql($end_month) . "," . Model::safeSql($this->cutoff_start_date) . "," . Model::safeSql($this->cutoff_end_date) . "," . Model::safeSql($query['percentage']) ."," . Model::safeSql($ybd['deducted_amount']) .",". Model::safeSql($this->created) . "," . Model::safeSql($this->modified) ."," . Model::safeSql($ybd['generate_based'])  ."," . Model::safeSql($deduction_month_start) ."," . Model::safeSql($deduction_month_end)."," . Model::safeSql($start_date).")";
			}
		}			

		$data['yearly_bonus'] = $yearly_bonus;
		$data['employee_ids'] = $employee_ids;
		
		return $data;
	}
 //new


	private function computeTaxableAmount( $amount = 0, $dependent = 0 ) {
		$return = array();
		$taxable_amount = 0;
		$tax_amount     = 0;
		$total_bonus_amount = $amount;
		$new_tax_computation = true; //for 2018 new tax computation

		if( $amount > self::CEILING_NON_TAXABLE ){

			if($new_tax_computation) {

				$tax_table      = Tax_Table_Factory::getRevisedTax(Tax_Table::SEMI_MONTHLY);
				if(!empty($ybd['sum_yearly_bonus'])) {
					$taxable_amount = $ybd['sum_yearly_bonus'] - self::CEILING_NON_TAXABLE;
				} else {
					$taxable_amount = $amount - self::CEILING_NON_TAXABLE;
				}
				
				$tax = new Tax_Calculator;
	            $tax->setTaxTable($tax_table);
	            $tax->setTaxableIncome($taxable_amount);
	            $tax->setNumberOfDependent($dependent);
	            $tax_amount = round($tax->computeHB563(), 2);
	            //$total_bonus_amount -= $tax_amount;

			} else {

				$tax_table 		= Tax_Table_Factory::get(Tax_Table::SEMI_MONTHLY);
				if(!empty($ybd['sum_yearly_bonus'])) {
					$taxable_amount = $ybd['sum_yearly_bonus'] - self::CEILING_NON_TAXABLE;
				} else {
					$taxable_amount = $amount - self::CEILING_NON_TAXABLE;
				}
				
				$tax = new Tax_Calculator;
	            $tax->setTaxTable($tax_table);
	            $tax->setTaxableIncome($taxable_amount);		           
	            $tax->setNumberOfDependent($dependent);
	            $tax_amount = round($tax->compute(), 2);
	            $total_bonus_amount -= $tax_amount;

			}

		}

		$return = array(
			'new_amount' => $total_bonus_amount,
			'tax' => $tax_amount,
			'taxable_amount' => $taxable_amount
		);

		return $return;
	}

	/**
	*
	* @param string from
	* @param string to
	* @return array 
	*/
	public function getEmployeeYearlyBonusByStartAndEndCutoff($from = '', $to = '') {
		$return = array();

		if( $from != '' && $to != '' ){
			$range  = array('from' => $from, 'to' => $to);
			$fields = array('total_bonus_amount','tax');
			$data   = G_Yearly_Bonus_Release_Date_Helper::getEmployeeDataByStartAndEndCutoff($this->employee_id,$range, $fields);
			$return['amount'] = $data['total_bonus_amount'];
			$return['tax']    = $data['tax'];
		}
		
		return $return;
	}

	public function getPreviousEmployeeYearlyBonus($from = '', $to = '') {
		$return = array();

		if( $from != '' && $to != '' ){
			$range  = array('from' => $from, 'to' => $to);
			$fields = array('total_bonus_amount','tax');
			$data   = G_Yearly_Bonus_Release_Date_Helper::getPreviousEmployeeData($this->employee_id,$range, $fields);
			$return['amount'] = $data['total_bonus_amount'];
			$return['tax']    = $data['tax'];
		}
		
		return $return;
	}	
	
	public function save() {		
		return G_Yearly_Bonus_Release_Date_Manager::save($this);
	}
	
	public function delete() {
		return G_Yearly_Bonus_Release_Date_Manager::delete($this);
	}

	/**
	* Process 13thmonth using import file
	*
	* @param array yearly_bonus_data - year, cutoff, file
	* @return array
	*/
	public function importYearlyBonus( $yearly_bonus_data = array() ) {
		$return = array('is_success' => false, 'message' => 'Total imported data 0');
		$yearly_bonus = array();

		if( !empty($yearly_bonus_data['cutoff']) ){						
			$a_cutoffs = explode("/", $yearly_bonus_data['cutoff']);			
			$this->cutoff_start_date = trim($a_cutoffs[0]);
			$this->cutoff_end_date   = trim($a_cutoffs[1]);
			$this->year_released 	 = $yearly_bonus_data['year'];
			$this->created  		 = date("Y-m-d H:i:s");	
		}else{			
			return $return;
		}

		if( $this->isCutoffPeriodLock() ){
			$return = array('is_success' => false, 'message' => 'Selected cutoff is already lock. Cannot process yearly bonus!');
			return $return;
		} 

		$inputFileType = PHPExcel_IOFactory::identify($yearly_bonus_data['file']);
		$objReader     = PHPExcel_IOFactory::createReader($inputFileType); 				
		$this->obj_reader = $objReader->load($yearly_bonus_data['file']);

		$read_sheet   = $this->obj_reader->getActiveSheet();        
        $import_data  = array(); 
        $employee_ids = array(); 
        $error_messages = array();

        $total_valid_records = 0;
        $total_not_imported  = 0;        
        $counter     = 0;    

        foreach ($read_sheet->getRowIterator() as $row) {                       
            $cellIterator = $row->getCellIterator();

            foreach ($cellIterator as $cell) {              
                $current_row    = $cell->getRow();
                $cell_value     = $cell->getFormattedValue();
                $column         = $cell->getColumn();
                $current_column = PHPExcel_Cell::columnIndexFromString($cell->getColumn());               
              
                if ($current_row == 1) {                   
                    $column_header[$column] = strtolower(trim($cell_value));                    
                }else{   

                	$import_data[$counter]['current_row'] = $current_row;

                    $column_header_value = strtolower(trim($column_header[$column]));                               
                    switch ($column_header_value) {
                        case 'employee code':
                            if( $cell_value != '' ){                            	
                                $import_data[$counter]['employee_code'] = trim($cell_value);                                                                                                                     
                            }
                            break; 

                        case 'basic pay':
                        	if( $cell_value != '' ){                            	
                                $import_data[$counter]['basic_pay'] = trim($cell_value);                                                                                                                     
                            }
                            else{
                            	$import_data[$counter]['basic_pay'] = 0;
                            }

                        break;

                        case 'absent':

                        	if( $cell_value != '' ){                            	
                                $import_data[$counter]['deducted_amount'] = trim($cell_value);                                                                                                                     
                            }
                            else{
                            	$import_data[$counter]['deducted_amount'] = 0;
                            }

                        break;

                        case 'percentage':

                        	if( $cell_value != '' ){                            	
                                $import_data[$counter]['percentage'] = trim($cell_value);                                                                                                                     
                            }
                            else{
                            	$import_data[$counter]['percentage'] = 0;
                            }

                        break;

                        case 'amount':
                        	$import_data[$counter]['amount'] = trim($cell_value);
                        default:                              
                            break;
                    }                   
                }
            }
            if( $import_data[$counter]['employee_code'] != '' ){
            	//Check if employee code is valid
	            $fields = array('id','number_dependent');
	            $data   = G_Employee_Helper::sqlGetEmployeeDetailsByEmployeeCode($import_data[$counter]['employee_code'],$fields);
	            if( $data['id'] > 0 ){            	
	            	$employee_ids[] = $data['id'];
	            	$import_data[$counter]['eid'] = $data['id'];
	            	$import_data[$counter]['number_dependent'] = $data['number_dependent'];
	            	$total_valid_records++;
	            }else{

	            	$err_message =  'Row: '. $import_data[$counter]['current_row'] ." : Employee code ( ".$import_data[$counter]['employee_code']." ) does not exist.";

	            	array_push($error_messages, $err_message);

	            	unset($import_data[$counter]);
	            	$total_not_imported++;
	            }
            }            
            $counter++;
        }

        foreach( $import_data as $d ){

        	//Taxable - Nontaxable amount
			$taxable_nontaxable = array();
			$taxable_nontaxable = $this->computeTaxableAmount($d['amount'], $d['number_dependent']);

        	$yearly_bonus[] = "(" . Model::safeSql($d['eid']) . "," . Model::safeSql($d['basic_pay']) . "," . Model::safeSql($d['amount']) . "," . Model::safeSql($taxable_nontaxable['taxable_amount']) . "," . Model::SafeSql($taxable_nontaxable['tax']) . ",". Model::safeSql($d['percentage']) . "," . Model::safeSql($taxable_nontaxable['new_amount']) . "," . Model::safeSql($d['deducted_amount']) . "," . Model::safeSql($this->year_released) . "," . Model::safeSql($this->month_start) . "," . Model::safeSql($this->month_end) . "," . Model::safeSql($this->cutoff_start_date) . "," . Model::safeSql($this->cutoff_end_date) . "," . Model::safeSql($this->created) . ", 'imported file')";
        }
       
        if( $total_valid_records > 0 ){
        	//We need to delete existing data before recreate
			G_Yearly_Bonus_Release_Date_Manager::deleteExistingDataByEmployeeIdsAndByStartAndEndMonthAndYear($employee_ids, $this->cutoff_start_date, $this->cutoff_end_date, $this->year_released);	

			$fields = array('employee_id','total_basic_pay','amount','taxable_amount','tax','percentage','total_bonus_amount','deducted_amount','year_released','month_start','month_end','cutoff_start_date','cutoff_end_date','created','generate_based');
			$total_inserted = G_Yearly_Bonus_Release_Date_Manager::bulkInsertData($yearly_bonus, $fields);

			$return['is_success'] = true;
			$return['message']    = "Total records processed " . $total_valid_records . " / Total records not imported " . $total_not_imported;
        }

        if($total_not_imported > 0){

        	  foreach($error_messages as $value){
        	  	   $msg .= "<br>".$value;
        	  }

        	  $return['message']   .= $msg;
        }
       

        return $return;
	}
}
?>