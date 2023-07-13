<?php
/*
	Usage:
		//$file = $_FILES['timesheet']['tmp_name'];
		$file = BASE_PATH . 'files/sample_import_files/import_overtime.xlsx';
		$g = new G_Overtime_Import($file);		
		$g->import();
*/
class G_Earnings_Import {
	protected $employee_code;
	protected $payroll_period;
	protected $status;
	protected $title;
	protected $remarks;
	protected $amount;
	protected $is_taxable;
	protected $data = array();
	
	protected $value_list = array();
	

	protected $file_to_import;
	protected $obj_reader;
	
	public function __construct($file) {
		$this->file_to_import = $file;
		$inputFileType    = PHPExcel_IOFactory::identify($this->file_to_import);
		$objReader        = PHPExcel_IOFactory::createReader($inputFileType); 				
		$this->obj_reader = $objReader->load($this->file_to_import);
	}
	
	public function setPayrollPeriodId($value) {
		$this->payroll_period = $value;		
	}

	public function import() {	
		$is_imported = false;						
		$read_sheet  = $this->obj_reader->getActiveSheet();
		
		foreach ($read_sheet->getRowIterator() as $row) {
			$this->emptyValues();
			$cellIterator = $row->getCellIterator();
			
		   	foreach ($cellIterator as $cell) {				
				$current_row    = $cell->getRow();
				$cell_value     = $cell->getFormattedValue();	
				$column         = $cell->getColumn();
				$current_column = PHPExcel_Cell::columnIndexFromString($cell->getColumn());
				//$coord = $cell->getCoordinate();	

				if ($column == 'A' && $cell_value != '') {
					$this->employee_code = ucfirst($cell_value);
				}
				
				if ($column == 'B' && $cell_value != '') {
					$this->status = ucfirst($cell_value);
				}
				
				if ($column == 'C' && $cell_value != '') {
					$this->title = $cell_value;
				}
				
				if ($column == 'D' && $cell_value != '') {
					$this->remarks = $cell_value;
				}
				
				if ($column == 'E' && $cell_value != '') {
					$this->amount = $cell_value;
				}
				
				if ($column == 'F' && $cell_value != '') {
					$this->is_taxable = ucfirst($cell_value);
				}
				
				if ($this->employee_code != '' && $this->employee_code != 'Employee Code' && $this->status != '' && $this->title != '' && $this->remarks != '' && $this->amount != '' && $this->is_taxable != '') {					
					$is_saved = $this->save();
					if ($is_saved) {
						$is_imported = true;	
					}
					$this->addValueToList();
					$this->emptyValues();
				}
			}
		}		
		
		return $is_imported;
	}
	
	public function directImport($values) {
		return true;
	}
	
	protected function constructEmployeeId() {
		$arEmp  = explode(",",$this->employee_code);
		foreach($arEmp as $e){			
			if($e != 'All Employee'){				
				$emp = G_Employee_Finder::findByEmployeeCode($e);
				if($emp){
					$newEmp[] = $emp->getId();						
				}
			}else{
				$newEmp[] = $e;
			}
		}		
		return implode(",",$newEmp);
	}
	
	protected function save() {		
		$emp = $this->constructEmployeeId();		
		$gcp = G_Cutoff_Period_Finder::findById($this->payroll_period);		
		$is_saved = false;
		if ($gcp) {
			$gee = new G_Employee_Earnings();	
			$gee->setDateCreated(Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila'));
			$gee->setCompanyStructureId($_SESSION['sprint_hr']['company_structure_id']);
			$gee->setTitle($this->title);		
			$gee->setRemarks($this->remarks);				
			$gee->setAmount($this->amount);				
			$gee->setPayrollPeriodId($gcp->getId());			
			$gee->setEmployeeId(serialize($emp));	
			$gee->setTaxable($this->is_taxable);
			$gee->setStatus($this->status);				
			$gee->setIsArchive(G_Employee_Earnings::NO);		
			$pos = strpos($emp, "All Employee");
			if ($pos === false) {
				$gee->setApplyToAllEmployee(G_Employee_Earnings::NO);				
			}else{
				$gee->setApplyToAllEmployee(G_Employee_Earnings::YES);				
			}							
			$is_saved = $gee->save();
		}
		return $is_saved;
	}
	
	private function addValueToList() {
		//$this->value_list[$this->employee_code][$this->date] = array('time_in' => $this->time_in, 'time_out' => $this->time_out, 'reason' => $this->reason);	
	}
	
	private function emptyValues() {
		$this->employee_code  = '';		
		$this->status 		  = '';
		$this->title 		  = '';
		$this->remarks 		  = '';
		$this->amount 		  = '';
		$this->is_taxable	  = '';
	}
		
	private function isDate($the_date) {	
//		$is_date = false;
//		echo $time = date('H:i:s', strtotime($the_date));
//		$date = date('Y-m-d', strtotime($the_date));
//		if ($date != '1970-01-01' && $time == '00:00:00') {
//			$is_date = true;	
//		}
//		return $is_date;
		return strtotime($the_date);
	}
	
	private function isTime($the_time) {		
		$temp_value = (int) $the_time;
		if (strlen($temp_value) == 4 || strlen($temp_value) == 6 || strlen($temp_value) == 10 || strlen($temp_value) == 13 || strlen($temp_value) == 17) {
			return false;	
		}
		$is_time = false;
		$time = date('H:i:s', strtotime($the_time));
		$date = date('Y-m-d', strtotime($the_time));
		if ($date != '1970-01-01' && $time != '00:00:00') {
			$is_time = true;	
		}		
		return $is_time;	
	}

	public function setImportFile($file){
		$this->file_to_import = $file;
		$inputFileType    = PHPExcel_IOFactory::identify($this->file_to_import);
		$objReader        = PHPExcel_IOFactory::createReader($inputFileType);
		$this->obj_reader = $objReader->load($this->file_to_import);
		return $this;
	}

	public function createImportBulkData(){
		$read_sheet  = $this->obj_reader->getActiveSheet();

		$counter = 0;
		$new_data = array();
		foreach ($read_sheet->getRowIterator() as $row) {   
			$counter++;         
			$cellIterator = $row->getCellIterator();
		   	foreach ($cellIterator as $cell) {				
				$current_row    = $cell->getRow();
				$cell_value     = $cell->getFormattedValue();
				$column         = $cell->getColumn();
				$current_column = PHPExcel_Cell::columnIndexFromString($cell->getColumn());   
					
				         		
                if ($current_row == 1) {                    
                    $column_header[$column] = strtolower(trim($cell_value));                    
                }else{   
                	$column_header_value = strtolower(trim($column_header[$column]));
                    $cell_value          = trim($cell_value);
                    if( $cell_value != ""){
                    	if((stripos($column_header_value, 'earning type')) !== false) {
                    		$data[$counter]['earning_type'] = $cell_value; 
                    	} elseif((stripos($column_header_value, 'percentage type')) !== false){
                    		$data[$counter]['percentage_multiplier'] = $cell_value; 
                    	} else {
                    		// echo "<pre>";
                    		// 	var_dump($column_header_value);
							// 	echo "</pre>";
                    		// switch ($column_header_value) {

	                    	// 	// case 'applied type':
	                    	// 	// 	if($cell_value == "employee id") {
	                    	// 	// 		$data[$counter]['applied_type'] = G_Employee_Earnings::APPLIED_TO_EMPLOYEE;
	                    	// 	// 	}elseif($cell_value == "all"){
	                    	// 	// 		$data[$counter]['applied_type'] = G_Employee_Earnings::APPLIED_TO_ALL;
	                    	// 	// 		$data[$counter]['object_description'] = 'Applied to all employees';
	                    	// 	// 	}elseif($cell_value == "department"){
	                    	// 	// 		$data[$counter]['applied_type'] = G_Employee_Earnings::APPLIED_TO_DEPARTMENT;
	                    	// 	// 	}elseif($cell_value == "employment status"){
	                    	// 	// 		$data[$counter]['applied_type'] = G_Employee_Earnings::APPLIED_TO_EMPLOYMENT_STATUS;
	                    	// 	// 	}
	                    	// 	// 	break;   
	                    	// 	case 'employee code':	
							// 		$data[$counter]['applied_to'] = $cell_value;
		                    //         break;
		                    //     case 'title':
		                    //    		$data[$counter]['title'] = $cell_value;                        	
		                    //         break;   
	                        //     case 'add to payroll period':
							// 		$period_id_weekly = G_Weekly_Cutoff_Period::getCutOffIdByYearMonthAndPeriod($cell_value);
							// 		$period_id_weekly->getId();

	                       	// 		$period_id = G_Cutoff_Period::getCutOffIdByYearMonthAndPeriod($cell_value);
	                       	// 		$data[$counter]['payroll_period_id'] = $period_id->getId();


	                        //     	break; 
	                        //    	case 'is taxable':
	                       	// 		$data[$counter]['is_taxable'] = $cell_value;                        	
	                        //     	break; 
	                        //     case 'amount':
	                       	// 		$data[$counter]['amount'] = $cell_value;                        	
	                        //     	break; 
	                        //     case 'remarks':
	                       	// 		$data[$counter]['remarks'] = $cell_value;                        	
	                        //     	break;
	                        //     case 'frequency type':
	                        //   		$data[$counter]['frequency_type'] = $cell_value;
	                        //   		break;           
		                    //     default:                           
		                    //         break;
							// }
							
							
								if((strpos($column_header_value, 'employee code') !== false))
								{
									$data[$counter]['applied_to'] = $cell_value;
								}

								if((strpos($column_header_value, 'title') !== false))
								{
									$data[$counter]['title'] = $cell_value;      
								}

								if((strpos($column_header_value, 'add to payroll period') !== false))
								{
									$period_id_monthly = G_Monthly_Cutoff_Period::getCutOffIdByYearMonthAndPeriod($cell_value);
									$period_id_weekly = G_Weekly_Cutoff_Period::getCutOffIdByYearMonthAndPeriod($cell_value);
									$period_id_weekly->getId();
	                       			$period_id = G_Cutoff_Period::getCutOffIdByYearMonthAndPeriod($cell_value);
	                       			$data[$counter]['payroll_period_id'] = $period_id->getId();
								}
							
								if((strpos($column_header_value, 'is taxable') !== false)) 
								{
									$data[$counter]['is_taxable'] = $cell_value;    
								}
														
								if((strpos($column_header_value, 'amount') !== false))
								{
									$data[$counter]['amount'] = $cell_value;   
								}
									                        	
								if((strpos($column_header_value, 'remarks') !== false))
								{
									$data[$counter]['remarks'] = $cell_value;    
								}
									                       	
								if((strpos($column_header_value, 'frequency type') !== false))
								{
									$data[$counter]['frequency_type'] = $cell_value;
								}
									  

	                    }	
                    }
                }
			}
			
			if($data[$counter]['frequency_type'] == 2){
	            $data[$counter]['payroll_period_id'] = $period_id_weekly->getId();
	   
			}

			else if($data[$counter]['frequency_type'] == 3){
	             $data[$counter]['payroll_period_id'] = $period_id_monthly->getId();
	   
			}
			// echo "<pre>";
			// var_dump($data);
			// echo "</pre>";
			if($data[$counter]['earning_type'] == G_Employee_Earnings::EARNING_TYPE_PERCENTAGE){

				if($data[$counter]['percentage_multiplier'] == G_Employee_Earnings::PERCENTAGE_SELECTION_MONTHLY){
					$data[$counter]['description'] = $data[$counter]['amount']."% of Monthly Salary";
				}elseif ($data[$counter]['percentage_multiplier'] == G_Employee_Earnings::PERCENTAGE_SELECTION_DAILY) {
					$data[$counter]['description'] = $data[$counter]['amount']."% of Daily Salary";
				}

				$data[$counter]['percentage'] = $data[$counter]['amount'];
				$data[$counter]['amount'] = 0;
			} elseif($data[$counter]['earning_type'] == G_Employee_Earnings::EARNING_TYPE_AMOUNT) {
				$data[$counter]['description'] = $data[$counter]['amount'];
				$data[$counter]['percentage']  =  0;
			}
			//for default employee id type
			$data[$counter]['applied_type'] = 1;
			if($data[$counter]['applied_type'] == G_Employee_Earnings::APPLIED_TO_EMPLOYEE) {
	
            	$applied_to  = explode(",",trim($data[$counter]['applied_to']));            	
            	$new_counter = $counter + 1;
            	foreach ($applied_to as $key => $value) {

            		$employee_id = G_Employee_Finder::findByEmployeeCode(trim($value));
            		if( $employee_id ){            		  			            			
            			$new_data[$new_counter]['object_description'] = $employee_id->getName();
	        			$new_data[$new_counter]['applied_type']       = $data[$counter]['applied_type']; 
	            		$new_data[$new_counter]['applied_to'] 		  = $employee_id->id; 
	            		$new_data[$new_counter]['description'] 		  = $data[$counter]['description'];	            		
	            		$new_data[$new_counter]['title'] 			  = $data[$counter]['title']; 
	            		$new_data[$new_counter]['payroll_period_id']  = $data[$counter]['payroll_period_id']; 
	            		$new_data[$new_counter]['earning_type']       = $data[$counter]['earning_type']; 
	            		$new_data[$new_counter]['is_taxable'] 	      = $data[$counter]['is_taxable']; 
	            		$new_data[$new_counter]['amount'] 			  = $data[$counter]['amount']; 
	            		$new_data[$new_counter]['percentage'] 		  = $data[$counter]['percentage']; 
	            		$new_data[$new_counter]['percentage_multiplier'] = $data[$counter]['percentage_multiplier']; 
	            		$new_data[$new_counter]['remarks'] 			     = $data[$counter]['remarks'];   
	            		$new_data[$new_counter]['frequency_type'] = $data[$counter]['frequency_type'];

	            		if($data[$counter]['applied_type'] == G_Employee_Earnings::APPLIED_TO_DEPARTMENT){
				    		$dept = strtolower($data[$counter]['applied_to']);
				    		$department = G_Company_Structure_Finder::findByTitle($dept);
				    		if( $department ){
				    			$new_data[$new_counter]['applied_to'] = $department->getId();
				    			$new_data[$new_counter]['object_description'] = "Department : ". $department->getTitle();
				    		}
				        }elseif($data[$counter]['applied_type'] == G_Employee_Earnings::APPLIED_TO_EMPLOYMENT_STATUS){
				        	$stat = strtolower($data[$counter]['applied_to']);
			        		$employment_status = G_Settings_Employment_Status_Finder::findByStatus($stat);
			        		if( $employment_status ){
			        			$new_data[$counter]['applied_to'] = $employment_status->getId();
			        			$new_data[$counter]['object_description'] = "Employment status : ". $employment_status->getStatus();
			        		}     	
				        }


	            		$new_counter++;    	            		
            		}   		            		
            	}            	
            	unset($data[$counter]);            	
	        }
		}

		foreach ($new_data as $key => $value) {
			$values[] = "(1,".Model::safeSql($value['applied_to']) .",". Model::safeSql($value['object_description']) .",".Model::safeSql($value['frequency_type']) .",". Model::safeSql($value['applied_type']) .",".Model::safeSql($value['title']) .",". Model::safeSql($value['remarks']) .",". Model::safeSql($value['earning_type']) ."," .Model::safeSql($value['percentage']) .",". Model::safeSql($value['percentage_multiplier']) .",". Model::safeSql($value['amount'])."," . Model::safeSql($value['payroll_period_id']) .",". Model::safeSql($value['description']) .",". "'Approved'" .",".Model::safeSql($value['is_taxable']) .","."'No'".",".Model::safeSql(date('Y-m-d H:i:s')).")";
		}
	
		$this->data = $values;  				
		return $this;
	}

	public function bulkSave() {
		$is_imported = false;	
		$return['message']    = 'Cannot save data';
	
		if(!empty($this->data) ){			
			
			$is_success = G_Employee_Earnings_Manager::bulkInsertData($this->data);
			if( $is_success ){
				
				$is_imported = true;	
			}
		}
		return $is_imported;
	}

}
?>