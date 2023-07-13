<?php
/*
	Usage:
		//$file = $_FILES['timesheet']['tmp_name'];
		$file = BASE_PATH . 'files/sample_import_files/import_overtime.xlsx';
		$g = new G_Overtime_Import($file);
		$g->import();
*/
class G_Employee_Loan_Importer {

	protected $c_date;
    protected $employee_code;
    protected $employee_id;
    protected $frequency_id;
    protected $start_date;
    protected $amount;
    protected $percentage;
    protected $loan_type_id;
    protected $num_cutoffs_to_pay;

    protected $amount_per_cutoff;
	
	protected $file_to_import;
	protected $obj_reader;

	protected $loan_list = array();
	protected $errorDuplicate = array();
	protected $errorColumn = array();
	
	public function __construct($file) {
       
		$this->file_to_import = $file;
		$inputFileType = PHPExcel_IOFactory::identify($this->file_to_import);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType); 				
		$this->obj_reader = $objReader->load($this->file_to_import);
		$this->c_date = Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
	}


 public function import(){                           
      $is_imported = false;						
		$read_sheet = $this->obj_reader->getActiveSheet();
		foreach ($read_sheet->getRowIterator() as $row) {
			$this->emptyValues();
			$cellIterator = $row->getCellIterator();
		   	foreach ($cellIterator as $cell) {
				$current_row = $cell->getRow();
				$cell_value = $cell->getFormattedValue();
				$column = $cell->getColumn();
				$current_column = PHPExcel_Cell::columnIndexFromString($cell->getColumn());
				//$coord = $cell->getCoordinate();
				if($current_row > 1){

					if($column == 'A' && $cell_value != '') {
						$this->employee_code = trim($cell_value);
						$e = G_Employee_Finder::findByEmployeeCode($this->employee_code);
						if($e){
							$this->employee_id = $e->getId();
						}
						else{
							$this->AddColumnError($current_row, 'employee code does not exist');
						}
					}

					if($column == 'B' && $cell_value != ''){
						$loan_type_name = trim($cell_value);
						$loan = G_Loan_Type_Finder::findByLoanType($loan_type_name);
						if($loan){
							$this->loan_type_id = $loan->getId();
						}
						else{
							$this->AddColumnError($current_row, 'loan type does not exist');
						}
					}

					if($column == 'C' && $cell_value != ''){
						$this->amount = $cell_value;
					}

					if($column == 'D' && $cell_value != ''){
						
						$this->percentage = $cell_value;
					}

					if($column == 'E' && $cell_value != ''){
						$this->start_date = $cell_value;
					}

					if($column == 'F' && $cell_value != ''){
						$this->num_cutoffs_to_pay = $cell_value;
					}


					if($this->employee_id != "" && $this->loan_type_id != "" && $this->amount != "" && $this->percentage != "" && $this->start_date != "" && $this->num_cutoffs_to_pay != ""){

						$this->addLoanEmployee();
					}


				}//end if current row > 1
				
			}//end celliterator foreach
		}
        $is_imported = $this->saveMultipleLoan();
	    return $is_imported;
        
    }


  public function importGovtLoan(){

  	$is_imported = false;						
		$read_sheet = $this->obj_reader->getActiveSheet();
		foreach ($read_sheet->getRowIterator() as $row) {
			$this->emptyValues();
			$cellIterator = $row->getCellIterator();
		   	foreach ($cellIterator as $cell) {
				$current_row = $cell->getRow();
				$cell_value = $cell->getFormattedValue();
				$column = $cell->getColumn();
				$current_column = PHPExcel_Cell::columnIndexFromString($cell->getColumn());
				//$coord = $cell->getCoordinate();
				if($current_row > 1){

					if($column == 'A' && $cell_value != '') {
						$this->employee_code = trim($cell_value);
						$e = G_Employee_Finder::findByEmployeeCode($this->employee_code);
						if($e){
							$this->employee_id = $e->getId();
						}
						else{
							$this->AddColumnError($current_row, 'employee code does not exist');
						}
					}

					if($column == 'B' && $cell_value != ''){
						$loan_type_name = trim($cell_value);
						$loan = G_Loan_Type_Finder::findByLoanType($loan_type_name);
						if($loan){
							$this->loan_type_id = $loan->getId();
						}
						else{
							$this->AddColumnError($current_row, 'loan type does not exist');
						}
					}

					if($column == 'C' && $cell_value != ''){
						$this->amount = $cell_value;
					}

					if($column == 'D' && $cell_value != ''){
						
						$this->amount_per_cutoff = $cell_value;
					}

					if($column == 'E' && $cell_value != ''){
						$this->start_date = $cell_value;
					}

					if($column == 'F' && $cell_value != ''){
						$this->num_cutoffs_to_pay = $cell_value;
					}


					if($this->employee_id != "" && $this->loan_type_id != "" && $this->amount != "" && $this->amount_per_cutoff != "" && $this->start_date != "" && $this->num_cutoffs_to_pay != ""){

						$this->addGovtLoanEmployee();
					}


				}//end if current row > 1
				
			}//end celliterator foreach
		}
        $is_imported = $this->saveMultipleGovtLoan();
	    return $is_imported;

  }


    public function AddColumnError($row, $message){

    	 $this->errorColumn[$row][] = $message;
    }

    public function getColumnError(){
    	return $this->errorColumn;
    }



    public function addLoanEmployee(){

    	$this->loan_list[] = array('employee_id' => $this->employee_id, 'loan_type_id' => $this->loan_type_id, 'amount'=>$this->amount , 'percentage'=>$this->percentage , 'start_date'=>$this->start_date , 'num_cutoffs_to_pay'=> $this->num_cutoffs_to_pay);
    }


     public function addGovtLoanEmployee(){

    	$this->loan_list[] = array('employee_id' => $this->employee_id, 'loan_type_id' => $this->loan_type_id, 'amount'=>$this->amount , 'amount_per_cutoff'=>$this->amount_per_cutoff, 'start_date'=>$this->start_date , 'num_cutoffs_to_pay'=> $this->num_cutoffs_to_pay);
    }



    public function saveMultipleGovtLoan(){

    	//utilities::displayArray($this->loan_list);exit();
    	$count = 0;

    	foreach($this->loan_list as $el){

    		$employee_id = $el['employee_id'];

    		$e = G_Employee_Finder::findById($employee_id);
    		if($e){
    			$frequency_id = $e->getFrequencyId();
    			$company_structure_id = $e->getCompanyStructureId();
    			$emp_code = $e->getEmployeeCode();
    		}

    		if($frequency_id == 1){
    			$deduction_type = 'Bi-monthly';
    		}
    		else if($frequency_id == 2){
    			$deduction_type = 'Weekly';
    		}

    		$loan_type_id = $el['loan_type_id'];
    		$amount = $el['amount'];
    		$percentage = 0;
    		$deduction_per_period = $el['amount_per_cutoff'];
    		$start_date = explode('-',$el['start_date']);
    		$num_cutoffs_to_pay = $el['num_cutoffs_to_pay'];

    		$period['year'] = $start_date[0];
    		$period['month'] = $start_date[1];
    		$period['cutoff'] = strtolower($start_date[2]);

    		$breakdown = $this->computeBreakdown($amount,$num_cutoffs_to_pay,$percentage,$deduction_type,$period);

    		//check records for duplicate
             $check = G_Employee_Loan_Finder::checkEmployeeLoanDuplicate($company_structure_id,$employee_id,$loan_type_id,$breakdown['loan_start_date'],$breakdown['loan_end_date'],$amount);
    		
    		if(!$check){
    				//save to database
				$loan = new G_Employee_Loan();
				$loan->setDateCreated($this->c_date);
				$loan->setCompanyStructureid($company_structure_id);		
				$loan->setEmployeeId($employee_id);
				$loan->setMonthsToPay($num_cutoffs_to_pay);
				$loan->setDeductionPerPeriod($deduction_per_period);
				$loan->setLoanTypeId($loan_type_id);
				$loan->setInterestRate(0);
				$loan->setLoanAmount($amount);					
				$loan->setCutoffPeriod($period);		
				$loan->setDeductionType($deduction_type);			
				$loan->setAsPending();
				$loan->setAsLock();
				$loan->setAsIsNotArchive();					
				$json = $loan->createGovernmentLoanDetails()->createGovernmentLoanSchedule()->saveEmployeeLoanDetails()->saveEmployeeLoanSchedules();

					if($json['is_success'] == true){
						$count++;
					}
    		}else{

    			$this->addErrorDuplicate($emp_code);

    		}
    		//end check
    	
			
    	}
    	
    	return $count;

    }





    public function saveMultipleLoan(){

    	//utilities::displayArray($this->loan_list);exit();
    	$count = 0;

    	foreach($this->loan_list as $el){

    		$employee_id = $el['employee_id'];

    		$e = G_Employee_Finder::findById($employee_id);
    		if($e){
    			$frequency_id = $e->getFrequencyId();
    			$company_structure_id = $e->getCompanyStructureId();
    			$emp_code = $e->getEmployeeCode();
    		}

    		
    		if($frequency_id == 2){
    			$deduction_type = 'Weekly';
    		}
    		elseif($frequency_id == 3){
    			$deduction_type = 'Monthly';
    		}
    		else{
    			$deduction_type = 'Bi-monthly';
    		}

    		$loan_type_id = $el['loan_type_id'];
    		$amount = $el['amount'];
    		$percentage = $el['percentage'];
    		$start_date = explode('-',$el['start_date']);
    		$num_cutoffs_to_pay = $el['num_cutoffs_to_pay'];

    		$period['year'] = $start_date[0];
    		$period['month'] = $start_date[1];
    		$period['cutoff'] = strtolower($start_date[2]);

    		$breakdown = $this->computeBreakdown($amount,$num_cutoffs_to_pay,$percentage,$deduction_type,$period);

    		//check records for duplicate
             $check = G_Employee_Loan_Finder::checkEmployeeLoanDuplicate($company_structure_id,$employee_id,$loan_type_id,$breakdown['loan_start_date'],$breakdown['loan_end_date'],$amount);
    		
    		if(!$check){
    				//save to database
	    		$loan = new G_Employee_Loan();
					$loan->setDateCreated($this->c_date);
					$loan->setCompanyStructureid($company_structure_id);		
					$loan->setEmployeeId($employee_id);
					$loan->setLoanTypeId($loan_type_id);
					$loan->setLoanAmount($amount);		
					$loan->setMonthsToPay($num_cutoffs_to_pay);		
					$loan->setInterestRate($percentage);
					$loan->setCutoffPeriod($period);		
					$loan->setDeductionType($deduction_type);			
					$loan->setAsPending();
					$loan->setAsLock();
					$loan->setAsIsNotArchive();			
					$json = $loan->createLoanDetails()->createLoanSchedule()->saveEmployeeLoanDetails()->saveEmployeeLoanSchedules();

					if($json['is_success'] == true){
						$count++;
					}
    		}else{

    			$this->addErrorDuplicate($emp_code);

    		}
    		//end check
    	
			
    	}

    	return $count;

    }

    public function addErrorDuplicate($employee_id){
    	$this->errorDuplicate[] = $employee_id;
    }

    public function getErrorDuplicate(){
    	return $this->errorDuplicate;
    }


    public function computeBreakdown($amount,$months_to_pay,$interest_rate,$deduction_type,$period,$govt_period){

		$lc = new Loan_Calculator($amount);
		$lc->setMonthsToPay($months_to_pay);
		$lc->setInterestRate($interest_rate);
		$lc->setDeductionType($deduction_type);		
		$loan_data = $lc->computeLoanNew($period,$govt_period);
	
		$data['loan_amount_with_interest'] = number_format($loan_data['total_amount_to_pay'],2);
		$data['expected_due']  = number_format($loan_data['monthly_due'],2);
		$data['loan_end_date'] = $loan_data['end_date'];
		$data['loan_start_date'] = $loan_data['start_date'];

		return $data;

    }



	private function emptyValues() {
		$this->employee_code = '';
		$this->loan_type_id = '';
		$this->amount  = '';
		$this->percentage  = '';
		$this->start_date  = '';
		$this->num_cutoffs_to_pay  = '';
		$this->amount_per_cutoff = '';
	}


}


?>