<?php
/*
	Usage:
		//$file = $_FILES['timesheet']['tmp_name'];
		$file = BASE_PATH . 'files/sample_import_files/import_overtime.xlsx';
		$g = new G_Employee_Import($file);
		$g->import();
*/
class G_Employee_Import {
    protected $required = array('employee_code', 'firstname', 'lastname', 'hired_date', 'salary_amount', 'salary_type',
                                'department', 'position', 'employment_status', 'birthdate', 'marital_status', 'gender', 'employee_status', 'cost_center');
    protected $required_import_salary = array('employee_code','salary_type','salary_amount','start_date','pay_frequency');
    protected $fields = array();
    protected $employee_codes = array();
    protected $date_created = '';

    private $error_missing_employee_codes = array();
    private $error_duplicate_employee_codes = array();
    private $error_duplicate_training = array();    
    private $error_empty_fields = array();
    private $successful_import = 0;
    private $error_exceed_employee_codes = array();

	protected $file_to_import;
	protected $obj_reader;
	
	public function setCompanyStructureId($value){
		$this->company_structure_id = $value;
	}
	
	public function __construct($file) {
		$this->file_to_import = $file;
		$inputFileType    = PHPExcel_IOFactory::identify($this->file_to_import);
		$objReader        = PHPExcel_IOFactory::createReader($inputFileType);
		$this->obj_reader = $objReader->load($this->file_to_import);

        $this->employee_codes = $this->getAllEmployeeCodes();
	}

    public function setDateCreated( $value ){
        $this->date_created = $value;
    }

    public function importUpdateEmployeeDetails() {
        $is_imported = false;                       
        
        $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS);

        $read_sheet  = $this->obj_reader->getActiveSheet();
        $has_error   = false;        
        foreach ($read_sheet->getRowIterator() as $row) {           
            $this->emptyValues();
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
                    switch ($column_header_value) {
                        case 'employee id':
                            if( $cell_value != '' ){
                                $employee_code = trim($cell_value);
                                $this->fields['employee_code'] = $employee_code;                                
                                if (!$this->isEmployeeCodeExists($employee_code)) {                                   
                                    $has_error = true;
                                }                                
                            }
                            break;
                        case 'number of dependent':
                            if( $cell_value == '' ){
                                $cell_value = 0;
                            }
                            $this->fields['dependent'] = trim($cell_value);
                            break;                        
                        default:                           
                            break;
                    }
                }
            }

            if( $current_row > 1 ){                               
                if (!$has_error) {                           
                    $this->updateEmployeeDetails();
                    $this->successful_import++;                    
                }

                $has_error = false;
                $this->emptyValues();
            }
        }
            
        return true;
    }
	
	public function import() {	
		$is_imported = false;						
        
        $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS);
        $valid_working_days_options  = $sv->validWorkingDaysOptions();             
        $default_year_working_days   = $sv->getVariableValue(); 
        $valid_working_days_variable = array_search($default_year_working_days, $valid_working_days_options);
        $default_week_working_days   = $sv->getVariableDescription( $valid_working_days_variable );        

		$read_sheet  = $this->obj_reader->getActiveSheet();
		foreach ($read_sheet->getRowIterator() as $row) {
            $a_education         = array();
            $a_emergency_contact = array();
			$this->emptyValues();
			$cellIterator = $row->getCellIterator();

		   	foreach ($cellIterator as $cell) {				
				$current_row    = $cell->getRow();
				$cell_value     = $cell->getFormattedValue();
				$column         = $cell->getColumn();
				$current_column = PHPExcel_Cell::columnIndexFromString($cell->getColumn());               
				//$coord = $cell->getCoordinate();

                if ($current_row == 1) {
                    //echo "Row : {$current_row} - Cell Value : {$cell_value} / ";
                    $column_header[$column] = strtolower(trim($cell_value));                    
                }else{                    
                    if ($column == 'A' && $cell_value != '') {
                        $this->fields['employee_code'] = $cell_value;
                    }
                    if ($column == 'B' && $cell_value != '') {
                        $this->fields['firstname'] = $cell_value;
                    }
                    if ($column == 'C' && $cell_value != '') {
                        $this->fields['lastname'] = $cell_value;
                    }
                    if ($column == 'D' && $cell_value != '') {
                        $this->fields['middlename'] = $cell_value;
                    }
                    if ($column == 'E' && $cell_value != '' && $this->isDate($cell_value)) {
                        $this->fields['hired_date'] = $this->convertToDate($cell_value);
                    }
                    if ($column == 'F' && $cell_value != '') {
                        $this->fields['salary_amount'] = (float) $cell_value;
                    }
                    if ($column == 'G' && $cell_value != '') {
                        $this->fields['salary_type'] = ucfirst(strtolower($cell_value));
                    }
                    if ($column == 'H' && $cell_value != '') {
                        $this->fields['no_of_dependent'] = (int) $cell_value;
                    }
                    if ($column == 'I' && $cell_value != '') {
                        $this->fields['department'] = $cell_value;
                    }
                    if ($column == 'J' && $cell_value != '') {
                        $this->fields['position'] = $cell_value;
                    }
                    if ($column == 'K' && $cell_value != '') {
                        $this->fields['employment_status'] = $cell_value;
                    }
                    if ($column == 'L' && $cell_value != '' && $this->isDate($cell_value)) {
                        $this->fields['birthdate'] = $this->convertToDate($cell_value);
                    }
                    if ($column == 'M' && $cell_value != '') {
                        $this->fields['marital_status'] = $cell_value;
                    }
                    if ($column == 'N' && $cell_value != '') {
                        $this->fields['gender'] = $cell_value;
                    }
                    if ($column == 'O' && $cell_value != '') {
                        $this->fields['sss_number'] = $cell_value;
                    }
                    if ($column == 'P' && $cell_value != '') {
                        $this->fields['pagibig_number'] = $cell_value;
                    }
                    if ($column == 'Q' && $cell_value != '') {
                        $this->fields['philhealth_number'] = $cell_value;
                    }
                    if ($column == 'R' && $cell_value != '') {
                        $this->fields['tin_number'] = $cell_value;
                    }
                    if ($column == 'S' && $cell_value != '') {
                        $this->fields['address'] = $cell_value;
                    }
                    if ($column == 'T' && $cell_value != '') {
                        $this->fields['city'] = $cell_value;
                    }
                    if ($column == 'U' && $cell_value != '') {
                        $this->fields['province'] = $cell_value;
                    }
                    if ($column == 'V' && $cell_value != '') {
                        $this->fields['zip_code'] = $cell_value;
                    }
                    if ($column == 'W' && $cell_value != '') {
                        $this->fields['home_phone'] = $cell_value;
                    }
                    if ($column == 'X' && $cell_value != '') {
                        $this->fields['mobile'] = $cell_value;
                    }
                    if ($column == 'Y' && $cell_value != '') {
                        $this->fields['personal_email'] = $cell_value;
                    }
                    if ($column == 'Z' && $cell_value != '') {
                        $this->fields['work_phone'] = $cell_value;
                    }
                    if ($column == 'AA' && $cell_value != '') {
                        $this->fields['work_email'] = $cell_value;
                    }
                    if ($column == 'AB' && $cell_value != '') {
                        $this->fields['bank_name'] = $cell_value;
                    }
                    if ($column == 'AC' && $cell_value != '') {
                        $this->fields['bank_account'] = $cell_value;
                    }
                    if ($column == 'AD' && $cell_value != '') {
                        $this->fields['extension_name'] = $cell_value;
                    }
                    if ($column == 'AE' && $cell_value != '') {
                        $this->fields['nickname'] = $cell_value;
                    }

                    if ($column == 'AF' && $cell_value != '') {
                        $this->fields['section'] = $cell_value;
                    }

                    if ($column == 'AG' && $cell_value != '') {
                        if(strtolower($cell_value) == strtolower(G_Employee::YES)) {                        
                            $cell_value = 1;
                        }else{
                            $cell_value = 0;
                        }
                        $this->fields['is_confidential'] = $cell_value;
                    }

                    //Valid working days
                    if( $column == 'AH' && trim($cell_value) != '' ) {                         
                        if( array_key_exists(trim($cell_value), $valid_working_days_options) ) {                              
                            $key = trim($cell_value);                                                                                            
                            $week_working_days = $sv->getVariableDescription( trim($cell_value) ); //Convert code to description                            
                            $year_working_days = $valid_working_days_options[$key];  //Convert cell value to numeric equivalent                           
                        }else{                            
                            $year_working_days = $default_year_working_days;
                            $week_working_days = $default_week_working_days;
                        }
                        $this->fields['year_working_days'] = $year_working_days;
                        $this->fields['week_working_days'] = $week_working_days;
                    }elseif( $column == 'AH' && trim($cell_value) == '' ){
                        $this->fields['year_working_days'] = $default_year_working_days;
                        $this->fields['week_working_days'] = $default_week_working_days;
                    }

                    //Other Details
                    if( $column == 'AI' && $cell_value != '' ){
                        $this->fields['other_details'] = $cell_value;
                    }

                    $column_header_value = strtolower(trim($column_header[$column]));

                    $replace = ['(weekly, bi monthly)', '(weekly, bi-monthly)', '(weekly,bi monthly)', '(weekly, bimonthly)', '(weekly,bimonthly)', '(weekly,bi-monthly)'];

                    $column_header_value = rtrim(str_replace($replace, "", $column_header_value));

                    //echo "Column Header : {$column_header_value} / ";
                    // echo $column_header_value;
                    switch ($column_header_value) {
                        case 'other details':
                            if( $cell_value != '' ){
                                $this->fields['other_details'] = trim($cell_value);
                            }
                            break;
                        case 'nationality':
                            if( $cell_value != '' ){
                                $this->fields['nationality'] = trim($cell_value);
                            }
                            break;
                        case 'tags':
                            if( $cell_value != '' ){
                                $this->fields['tags'] = trim($cell_value);
                            }
                            break;
                        case 'emergency contact':
                            if( $cell_value != '' ){                                
                                $a_emergency_contact[] = trim($cell_value);
                            }
                            break;
                        case 'education':
                            if( $cell_value != '' ){                                                   
                                $a_education[] = trim($cell_value);
                            }
                            break;
                        case 'employee status':
                            if( $cell_value != '' ){
                                $this->fields['employee_status'] = trim($cell_value);
                            }
                            break;   
                        case 'endo date':
                            if( $cell_value != '' ){
                                $this->fields['endo_date'] = $this->convertToDate(trim($cell_value));   
                            }
                            break;
                        case 'pay frequency':
                            if( $cell_value != '' ){
                                $pay_frequency = str_replace(['bimonthly', 'bi monthly'], 'Bi-Monthly', strtolower(trim($cell_value)));
                                $pay_frequency = ucfirst($pay_frequency);
                                $this->fields['pay_frequency'] = $pay_frequency;

                                if( !$this->isPayFrequencyExists($pay_frequency) ){                                 
                                    $has_error = true;
                                }
                            }
                            break;  
                        case 'project site':
                            if( $cell_value != '' ){
                                $this->fields['cost_center'] = trim($cell_value);
                            }
                            break;      
                        default:                           
                            break;
                    }
                }
			}
            $employee_code = $this->fields['employee_code'];
            $has_error = false;

            $employees = G_Employee_Finder::findAllActiveEmployees();
            $current_total_employees = count($employees);

            if ($current_total_employees >= G_Employee::MAX_EMPLOYEES) {
                $this->addErrorExceedEmployeeCode($employee_code);
                $has_error = true;
            }
            else {
                if ($this->isAlreadyExist($employee_code)) {
                    // echo 7;
                    $this->addErrorDuplicateEmployeeCode($employee_code);
                    $has_error = true;
                }
                if (!$this->isRequiredFieldsHaveValues()) {                
                    // echo 8;
                    $this->addErrorEmptyRequiredField($employee_code);
                    $has_error = true;
                }
                if (!$has_error) {
                    $this->fields['education']         = $a_education;
                    $this->fields['emergency_contact'] = $a_emergency_contact;
                    $this->addEmployee();
                    $this->successful_import++;
                }
            }
            $this->emptyValues();
		}
            
		return true;
	}

    public function importSalary() {  
        $is_imported = false;                       
        
        $sv = new G_Sprint_Variables(G_Sprint_Variables::FIELD_DEFAULT_TOTAL_WRKNG_DAYS);

        $read_sheet  = $this->obj_reader->getActiveSheet();
        $has_error   = false;        
        foreach ($read_sheet->getRowIterator() as $row) {           
            $this->emptyValues();
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

                    $replace = ['(weekly, bi monthly)', '(weekly, bi-monthly)', '(weekly,bi monthly)', '(weekly, bimonthly)', '(weekly,bimonthly)', '(weekly,bi-monthly)'];

                    $column_header_value = rtrim(str_replace($replace, "", $column_header_value));

                    switch ($column_header_value) {
                        case 'employee id':
                            if( $cell_value != '' ){
                                $employee_code = trim($cell_value);
                                $this->fields['employee_code'] = $employee_code;                                
                                if (!$this->isEmployeeCodeExists($employee_code)) {                                   
                                    $has_error = true;
                                }                                
                            }
                            break;
                        case 'salary type':
                            if( $cell_value != '' ){
                            	$salary_type = ucfirst($cell_value);
                                $this->fields['salary_type'] = trim($cell_value);

                                if( !$this->isSalaryTypeExists($salary_type) ){                                 
                                    $has_error = true;
                                }
                            }
                            break;
                        case 'salary amount':
                            if( $cell_value != '' ){
                                $this->fields['salary_amount'] = trim($cell_value);
                            }
                            break;
                        case 'start date':
                            if( $cell_value != '' ){   
                                $date_format = $this->convertToDate(trim($cell_value));                                   
                                $this->fields['start_date'] = $date_format;

                                if( strtotime($date_format) <= 0 ){
                                    $has_error = true;
                                }
                            }
                            break;
                        case 'end date':
                            if( $cell_value != '' ){
                                $date_format = $this->convertToDate(trim($cell_value));                                                   
                                $this->fields['end_date'] = $date_format;

                                if( strtotime(trim($date_format)) <= 0 ){                                
                                    $has_error = true;
                                }
                            }
                            break;  
                        case 'pay frequency':
                            if( $cell_value != '' ){
                                $pay_frequency = str_replace(['bimonthly', 'bi monthly'], 'Bi-Monthly', strtolower(trim($cell_value)));
                                $pay_frequency = ucfirst($pay_frequency);
                                $this->fields['pay_frequency'] = $pay_frequency;

                                if( !$this->isPayFrequencyExists($pay_frequency) ){                                 
                                    $has_error = true;
                                }
                            }
                            break;                                            
                        default:                           
                            break;
                    }
                }
            }

            if( $current_row > 1 ){   
                 if (!$this->isImportSalaryRequiredFieldsHaveValues()) {                                          
                    $this->addErrorEmptyRequiredField($employee_code);
                    $has_error = true;
                }

                if (!$has_error) {                           
                    $this->addEmployeeSalary();
                    $this->successful_import++;                    
                }

                $has_error = false;
                $this->emptyValues();
            }
        }
            
        return true;
    }

    public function importTraining() {
        $is_imported = false;

        $read_sheet  = $this->obj_reader->getActiveSheet();
        $has_error   = false;
        foreach ($read_sheet->getRowIterator() as $row) {
            $this->emptyValues();
            $cellIterator = $row->getCellIterator();
            $employee_code;
            foreach ($cellIterator as $cell) {
                $current_row    = $cell->getRow();
                $cell_value     = $cell->getFormattedValue();
                $column         = $cell->getColumn();
                $current_column = PHPExcel_Cell::columnIndexFromString($cell->getColumn());

                if ($current_row == 1) {
                    $column_header[$column] = strtolower(trim($cell_value));
                }else{
                    $column_header_value = strtolower(trim($column_header[$column]));
                    //echo "column header:".$column_header_value."<br>";
                    switch ($column_header_value) {
                        case 'employee no.':
                            if( $cell_value != '' ){
                                $employee_code = trim($cell_value);

                                /*
                                if (!$this->isEmployeeCodeExists($employee_code)) {
                                    $has_error = true;
                                }
                                */

                                $ec = G_Employee_Finder::findByEmployeeCode($employee_code);
                                if ($ec)
                                {
                                  $employee_code = $ec->getId();
                                  $this->fields['employee_id'] = $employee_code;
                                  //echo "employee code recognized:".$this->fields['employee_id']."<br>";
                                }

                            }
                            break;
                        case 'training':
                            if( $cell_value != '' ){
                                //$salary_type = $cell_value;
                                $this->fields['description'] = trim($cell_value);
                            }
                            break;

                        case 'from(date)':
                            if( $cell_value != '' ){
                              $date_format = $this->convertToDate(trim($cell_value));
                              $this->fields['from_date'] = $date_format;

                              if( strtotime($date_format) <= 0 ){
                                  $has_error = true;
                              }
                            }
                            break;

                        case 'to (date)':
                            if( $cell_value != '' ){
                                $date_format = $this->convertToDate(trim($cell_value));
                                $this->fields['to_date'] = $date_format;

                                if( strtotime($date_format) <= 0 ){
                                    $has_error = true;
                                }
                            }
                            break;
                        case 'provider/trainor':
                            if( $cell_value != '' ){
                                $this->fields['provider'] = trim($cell_value);

                            }
                            break;
                        case 'location':
                            if( $cell_value != '' ){
                                $this->fields['location'] = trim($cell_value);
                                //echo "location: ".$this->fields['location']."<br>";
                            }
                            break;
                        default:
                            break;
                    }
                }
            } //end of cell loop

            if( $current_row > 1 ){
                $has_error = false;

                /*
                 if (!$this->isImportTrainingRequiredFieldsHaveValues()) {
                    $this->addErrorEmptyRequiredField($employee_code);
                    $has_error = true;
                }
                */

                if ((!isset($this->fields['employee_id']))) {
                    $this->addErrorMissingEmployeeCode($current_row); //pass row index instead of employe code
                    $has_error = true;
                    //echo "employee id checked:" . $this->fields['employee_id']. "employee code not recognized <br>";
                }

                //check if training exists
                $from_date   = $this->fields['from_date'];
                $to_date     = $this->fields['to_date'];
                $provider    = $this->fields['provider'];
                $location    = $this->fields['location'];
                $description = $this->fields['description'];

                if (isset($this->fields['employee_id']))
                {
                  $employee_id = $this->fields['employee_id'];
                  $y = $this->doesTrainingExist($employee_id, $from_date, $to_date, $provider, $location, $description);
                  if ($y)
                  {
                    $this->addErrorDuplicateTraining($current_row);
                    $has_error = true;
                  }
                }

                if (!$has_error) {
                    $this->addTraining();
                    $this->successful_import++;
                }

            }
        }

        /*if (!$has_error) {
            $this->addTraining();
            $this->successful_import++;
        }*/

        $has_error = false;
        $this->emptyValues();
        return true;
    }

    protected function isSalaryTypeExists( $salary_type = '' ) {
        $is_exists = false;
        $valid_salary_type = array(G_Employee_Basic_Salary_History::SALARY_TYPE_MONTHLY,G_Employee_Basic_Salary_History::SALARY_TYPE_DAILY);
        if( trim($salary_type) != '' && in_array($salary_type, $valid_salary_type) ){
            $is_exists = true;
        }

        return $is_exists;
    }

    protected function isPayFrequencyExists( $pay_frequency = '' ) {
        $is_exists = false;
        $valid_pay_frequency = array(G_Settings_Pay_Period::NAME_BI_MONTHLY,G_Settings_Pay_Period::NAME_WEEKLY);
        if( trim($pay_frequency) != '' && in_array($pay_frequency, $valid_pay_frequency) ){
            $is_exists = true;
        }

        return $is_exists;
    }

    public function updateEmployeeDetails() {
        $employee_code = $this->fields['employee_code'];
        $dependent     = $this->fields['dependent'];
        
        $e = G_Employee_Finder::findByEmployeeCode($employee_code);
        if( $e ){
            //Update dependents
            $e->updateDependents($dependent);
        }
    }

    protected function isEmployeeCodeExists( $employee_code = '' ) {
        $is_exists = false;
        if( trim($employee_code) != '' ){
            $is_exists = G_Employee_Helper::sqlIsEmployeeCodeExists($employee_code);
        }
        return $is_exists;
    }

    protected function doesTrainingExist($employee_id, $from_date, $to_date, $provider, $location, $description)
    {

      $x = G_Employee_Training_Finder::findTraining($employee_id, $from_date, $to_date, $provider, $location, $description);
      if(!$x)
      {
        $does_exist = false;
        return $does_exist;
      }
      $sq = G_Employee_Training_Finder::returnSql($employee_id, $from_date, $to_date, $provider, $location, $description);
      $does_exist = true;

      return $does_exist;
    }    

    protected function getAllEmployeeCodes() {
        return G_Employee_Helper::getAllEmployeeCodes();
    }

    public function getTotalSuccessfulImport() {
        return $this->successful_import;
    }

    private function isRequiredFieldsHaveValues() {
        foreach ($this->required as $field) {
            $value = trim($this->fields[$field]);
            if ($value == '') {               
                return false;
            }
        }
        return true;
    }

    private function isImportSalaryRequiredFieldsHaveValues() {
        foreach ($this->required_import_salary as $field) {            
            $value = trim($this->fields[$field]);            
            if ($value == '') {
                return false;
            }
        }
        return true;
    }

    public function getDuplicateEmployeeCodes() {
        return $this->error_duplicate_employee_codes;
    }

    public function getEmptyRequiredFields() {
        return $this->error_empty_fields;
    }

    public function getMissingEmployeeCode()
    {
      return $this->error_missing_employee_codes;
    }    

    public function getDuplicateTraining()
    {
      return $this->error_duplicate_training;
    }    

    public function getExceedEmployeeCodes() {
        return $this->error_exceed_employee_codes;
    }

    protected function addErrorDuplicateEmployeeCode($employee_code) {
        $this->error_duplicate_employee_codes[] = $employee_code;
    }

    protected function addErrorEmptyRequiredField($employee_code) {

        $this->error_empty_fields[] = $employee_code;
    }

    protected function addErrorExceedEmployeeCode($employee_code) {
        $this->error_exceed_employee_codes[] = $employee_code;
    }

    private function isAlreadyExist($employee_code) {
        if (in_array($employee_code, $this->employee_codes)) {
            return true;
        } else {
            return false;
        }
    }

    protected function addErrorMissingEmployeeCode($row)
    {
      $this->error_missing_employee_codes[] = $row;
    }    

    protected function addErrorDuplicateTraining($row_index)
    {
      $this->error_duplicate_training[] = $row_index;
    }    

    protected function addEmployeeSalary() {

        $employee_code = $this->fields['employee_code'];
        $salary_type   = ucfirst($this->fields['salary_type']);
        $salary_amount = $this->fields['salary_amount'];

        $start_date    = $this->fields['start_date'];
        $start_date    = date("Y-m-d",strtotime($start_date));

        $end_date      = $this->fields['end_date'];
        $pay_frequency = $this->fields['pay_frequency'];
        $is_present    = false;
        if( trim($end_date) != "" ){
            $end_date      = date("Y-m-d",strtotime($end_date));            
        }else{
            $is_present    = true;
        }

        $e = G_Employee_Finder::findByEmployeeCode($employee_code);
        if( $e ){           
            $pay_period = G_Settings_Pay_Period_Finder::findByName($pay_frequency);

            if( $is_present ){
                $count = G_Employee_Basic_Salary_History_Helper::countTotalHistoryByEmployeeId($e->getId());
                if($count > 0){
                    $salary = new G_Employee_Basic_Salary_History();
                    $salary->setEmployeeId($e->getId());
                    //$salary->setEndDate(date('Y-m-d'));
                    $salary->setEndDate($start_date);
                    $salary->resetEmployeePresentSalary();
                    $e->setFrequencyId($pay_period->id);
                    $e->save();
                }
            }

            $employee_salary = new G_Employee_Basic_Salary_History();               
            $employee_salary->setEmployeeId($e->getId());
            $employee_salary->setJobSalaryRateId(0);
            $employee_salary->setBasicSalary($salary_amount);
            $employee_salary->setType($salary_type);
            $employee_salary->setFrequencyId($pay_period->id);
            $employee_salary->setPayPeriodId($pay_period->id);
            $employee_salary->setStartDate($start_date);
            
            $end_date = ($is_present ? '' : $end_date);
            
            $employee_salary->setEndDate($end_date);
            $employee_salary->save();   

            if($is_present){
                //Update Employee Contribution
                $e->addContribution($salary_amount);
            }
        }
    }

    protected function addTraining()
    {
        $employee_code = $this->fields['employee_id'];
        $from_date = $this->fields['from_date'];
        $to_date = $this->fields['to_date'];
        $description = $this->fields['description'];
        $provider = $this->fields['provider'];
        $location = $this->fields['location'];

        $ts = new G_Employee_Training();
        $ts->setEmployeeId($employee_code);
        $ts->setFromDate($from_date);
        $ts->setToDate($to_date);
        $ts->setDescription($description);
        $ts->setProvider($provider);
        $ts->setLocation($location);

        $ts->insertTraining();
    }    

    protected function addEmployee() {
        $cs = G_Company_Structure_Finder::findByMainParent();

        $employee_code = $this->fields['employee_code'];
        $firstname     = $this->fields['firstname'];
        $lastname      = $this->fields['lastname'];
        $middlename    = $this->fields['middlename'];
        $hired_date    = $this->fields['hired_date'];
        $salary_amount = $this->fields['salary_amount'];
        $salary_type   = $this->fields['salary_type'];
        $no_of_dependent   = $this->fields['no_of_dependent'];
        $department        = $this->fields['department'];
        $position          = $this->fields['position'];
        $employment_status = $this->fields['employment_status'];
        $birthdate         = $this->fields['birthdate'];
        $marital_status    = $this->fields['marital_status'];
        $gender            = $this->fields['gender'];
        $sss_number        = $this->fields['sss_number'];
        $pagibig_number    = $this->fields['pagibig_number'];
        $philhealth_number = $this->fields['philhealth_number'];
        $tin_number        = $this->fields['tin_number'];
        $address           = $this->fields['address'];
        $city              = $this->fields['city'];
        $province          = $this->fields['province'];
        $zip_code          = $this->fields['zip_code'];
        $home_phone        = $this->fields['home_phone'];
        $mobile            = $this->fields['mobile'];
        $personal_email    = $this->fields['personal_email'];
        $work_phone        = $this->fields['work_phone'];
        $work_email        = $this->fields['work_email'];
        $bank_name         = $this->fields['bank_name'];
        $bank_account      = $this->fields['bank_account'];
        $extension_name    = $this->fields['extension_name'];
        $nickname          = $this->fields['nickname'];
        $section           = $this->fields['section'];
        $is_confidential   = $this->fields['is_confidential'];
        $e_year_working_days = $this->fields['year_working_days'];
        $e_week_working_days = $this->fields['week_working_days'];
        $education         = $this->fields['education'];
        $emergency_contact = $this->fields['emergency_contact'];
        $nationality       = $this->fields['nationality'];
        $tags              = $this->fields['tags'];
        $employee_status   = $this->fields['employee_status'];
        $endo_date         = $this->fields['endo_date'];
        $pay_frequency = $this->fields['pay_frequency'];
        $cost_center = $this->fields['cost_center'];

        //pay frequency
        $pay_period = G_Settings_Pay_Period_Finder::findByName($pay_frequency);
        
        if ($pay_period) {
            $frequency_id = $pay_period->id;
        }
        else {
            $frequency_id = 1;
        }

        //Employee Status
        $es_id = 0;
        $es    = G_Settings_Employee_Status_Finder::findByName($employee_status);
        if( $es ){
            $es_id = $es->getId();
        }else{
            if( trim( $employee_status ) != '' ){
                //Create new employee status
                $es_date_created = date("Y-m-d",strtotime($this->date_created));
                $es = new G_Settings_Employee_Status();
                $es->setName($employee_status);     
                $es->setCompanyStructureId($cs->getId());  
                $es->setIsArchive(G_Settings_Employee_Status::NO);
                $es->setDateCreated($es_date_created);
                $es_id = $es->save();
            }
        }

        //project site 
        $project_site_name = strtolower($cost_center); 

        //check if project site exist using project site name
        $project = G_Project_Site_Finder::findProjectSiteByName($project_site_name);

        if($project){
            $project_site_id = $project->getId();
        }
        else{ //if not exist insert

            $ps = new G_Project_Site_Extends;
            $ps->setprojectname($project_site_name);
            $ps->setlocation('');
            $ps->setProjectDescription('');
            $ps->setStart_date('');
            $ps->setEnd_date('');
            $save = $ps->setProjectSite();

            if($save){
                 $project = G_Project_Site_Finder::findProjectSiteByName($project_site_name);
                  if($project){
                     $project_site_id = $project->getId();
                  }
            }

        }

       // var_dump($project_site_id);exit;



        $e = $cs->hireEmployee($employee_code, $firstname, $lastname, $middlename, $birthdate, $gender, $marital_status,
                $no_of_dependent, $hired_date, $department, $position, $employment_status, $salary_amount, $salary_type, $frequency_id,
                $sss_number, $tin_number, $pagibig_number, $philhealth_number, $extension_name, $nickname, $section, $is_confidential, $e_week_working_days, $e_year_working_days, $nationality, $es_id, $cost_center,$project_site_id);
        if ($e) {
            $e->setEndoDate($endo_date);
            $e->save();

            $e->addContactDetails($address, $city, $province, $zip_code, $home_phone, $mobile, $work_phone, $work_email, $personal_email);
            $e->addBankAccount($bank_name, $bank_account);
            $e->createBulkEducationArray($education)->bulkAddEducation();           
            $e->createBulkEmergencyContactArray($emergency_contact)->bulkAddEmergencyContact();   

            //Employee Tags
            if( trim($tags) != '' ){          
                $tags = str_replace(" ","",trim($tags));                  
                $t = new G_Employee_Tags();
                $t->setCompanyStructureId($cs->getId());
                $t->setTags($tags);
                $t->setIsArchive(G_Employee_Tags::NO);
                $t->setDateCreated($this->date_created);
                $t->save($e);              
            }

            //Save other details
            $employee_id   = $e->getId();
            $other_details = $this->fields['other_details'];
            if( !empty($other_details) ){
                $arr_other_fields = explode("/", $other_details);
                foreach( $arr_other_fields as $field ){
                    $field_array = explode("=", $field);
                    $label = trim($field_array[0]);
                    $value = trim($field_array[1]);
                    if( $label != "" && $value != "" ){                     
                        $dynamic_field_data[$employee_id][] = array("other_details_label" => $label, "other_details_value" => $value);
                    }
                } 
                $ed = new G_Employee_Dynamic_Field();
                $ed->bulkInsertDynamicField($dynamic_field_data);
            }
        }
    }

	private function emptyValues() {
        unset($this->fields);
	}

    private function convertToDate($value) {
        $valid_format = array("m-d-Y" => "-","m/d/Y" => "/");
        foreach( $valid_format as $format => $delimeter ){
            $date_format = DateTime::createFromFormat($format, $value);
            if ($date_format !== false && !array_sum($date_format->getLastErrors())) {
                $dates = explode($delimeter, $value);
                $month = $dates[0];
                $day   = $dates[1];
                $year  = $dates[2];
                $date  = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
                return $date;         
            }
        }
    }

    private function isDate($the_date) {     
        $valid_format = array("m-d-Y","m/d/Y");
        foreach( $valid_format as $format ){
            $date_format = DateTime::createFromFormat($format, $the_date);
            if ($date_format !== false && !array_sum($date_format->getLastErrors())) {
                return true;                
            }
        }

       /* $date_format = DateTime::createFromFormat("m-d-Y", $the_date);

        if ($date_format !== false && !array_sum($date_format->getLastErrors())) {
            return true;
        } else {
            return false;
        }*/
    }
}
?>