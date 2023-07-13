<?php
/*
	Usage:
		//$file = $_FILES['timesheet']['tmp_name'];
		$file = BASE_PATH . 'files/sample_import_files/import_overtime.xlsx';
		$g = new G_Overtime_Import($file);
		$g->import();
*/
class G_Employee_Leave_Available_Importer {
	protected $employee_code;
    protected $sick_leaves;
    protected $vacation_leaves;
    protected $other_leaves = array();
    protected $no_of_leaves = array();
    protected $year;

    protected $leave_available_list = array();
    protected $employees = array();
	
	protected $file_to_import;
	protected $obj_reader;
	
	public function __construct($file) {
        $this->year = date('Y');

		$this->file_to_import = $file;
		$inputFileType = PHPExcel_IOFactory::identify($this->file_to_import);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType); 				
		$this->obj_reader = $objReader->load($this->file_to_import);
	}

    public function setYear($value) {
        $this->year = $value;
    }

	public function import() {	
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

				if ($column == 'A' && $cell_value == 'Employee ID') {
					continue 2;
				}

				if ($column == 'A' && $cell_value != '') {
					$this->employee_code = $cell_value;
				}

				if ($column == 'B' && $cell_value != '') {
				    $this->sick_leaves = (float) $cell_value;

                    $vacation = $read_sheet->getCellByColumnAndRow($current_column, $current_row)->getValue();
                    if ($vacation == '') {
                        $this->addLeaveAvailable();
					    $this->emptyValues();
                        continue 2;
                    }
				}

				if ($column == 'C' && $cell_value != '') {
					$this->vacation_leaves = (float) $cell_value;

                    $this->addLeaveAvailable();
					$this->emptyValues();
                    continue 2;
				}
			}
		}
        $is_imported = $this->saveMultipleLeaveAvailable();
		return $is_imported;
	}

    public function importLeaveCredit(){                           
        $read_sheet  = $this->obj_reader->getActiveSheet();
        $is_imported = false;    
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
                                $this->employee_code = trim($cell_value);                                                                                                                     
                            }
                            break;                                                                  
                        default:  
                            $leave = G_Leave_Finder::findByName($column_header_value);                             
                            if( $leave && $cell_value != '' ){
                                $this->leave_id      = $leave->getId();
                                $this->credit_to_add = trim($cell_value);   
                                $this->saveUpdateLeaveAvailable();
                                $this->successful_import++;           
                                
                                $this->leave_id = 0;
                                $this->credit_to_add = 0;                                                           
                            }  
                            break;
                    }
                }
            }

            if( $current_row > 1 && $column_header_value <> 'employee id'  ){  
                $has_error = false;
                $this->emptyValues();
            }
        }
        
        if( $this->successful_import > 0 ){
            return true;
        }else{
            return false;
        }
        
    }

    public function importLeaveCreditOld(){
        $read_sheet = $this->obj_reader->getActiveSheet();

        $is_imported = false;                       
        foreach ($read_sheet->getRowIterator() as $row) {
            $this->emptyValues();
            $cellIterator = $row->getCellIterator();
            foreach ($cellIterator as $cell) {
                $current_row = $cell->getRow();
                $cell_value = $cell->getFormattedValue();
                $column = $cell->getColumn();
                $current_column = PHPExcel_Cell::columnIndexFromString($cell->getColumn());
                $coord = $cell->getCoordinate();

                if ($column == 'A' && $cell_value == 'Employee ID') {
                    continue 2;
                }     

                if ($column == 'A' && $cell_value != '') {
                    $this->employee_code = $cell_value;
                }                   

                if($column != 'A' && $cell_value != '') {
                    $get_leave_type = $read_sheet->rangeToArray($column . 1 . ':' . $column . 1 );      
                    $get_leave          = $get_leave_type[0];
                    $this->other_leaves[$this->employee_code][$get_leave[0]] = $cell_value; 
                }                         

                /*
                if ($column == 'B' && $cell_value != '') {
                    $this->sick_leaves = (float) $cell_value;

                    $vacation = $read_sheet->getCellByColumnAndRow($current_column, $current_row)->getValue();
                    if ($vacation == '') {
                        $this->addEmployeeLeaveAvailable();
                        $this->emptyValues();
                        continue 2;
                    }
                }

                if ($column == 'C' && $cell_value != '') {
                    $this->vacation_leaves = (float) $cell_value;

                    $this->addEmployeeLeaveAvailable();
                    $this->emptyValues();
                    continue 2;
                }
                */
            }
           
        }

        $this->addEmployeeLeaveAvailable();
        $this->emptyValues();          

        //$is_imported = $this->saveMultipleLeaveAvailable();
        $is_imported = $this->saveUpdateLeaveAvailable();
        return $is_imported;        

    }

    private function addEmployeeLeaveAvailable() {

        foreach($this->other_leaves as $key_employee_code => $leave_data) {
            $e = G_Employee_Finder::findByEmployeeCode($key_employee_code);
            if ($e) {   
                foreach($leave_data as $leave_key => $leave_alloted) {
                    $leave = G_Leave_Finder::findByName($leave_key);
                    if( $leave ){
                        $year = $this->year;               
                    $l = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $leave->getId(), $year);
                    if (!$l) {
                        $l = new G_Employee_Leave_Available;
                    }
                    $l->setEmployeeId($e->getId());
                    $l->setLeaveId($leave->getId());
                    $alloted = $l->getNoOfDaysAlloted();
                    $l->setNoOfDaysAlloted($alloted + $leave_alloted);
                    $available = $l->getNoOfDaysAvailable();
                    $l->setNoOfDaysAvailable($available + $leave_alloted);
                    $l->setCoveredYear($year);
                    $this->leave_available_list[] = $l;        
                    }                                
                }
            }
        }   
    }

    private function addLeaveAvailable() {
      $e = G_Employee_Finder::findByEmployeeCode($this->employee_code);
      if ($e) {
          if ($this->sick_leaves > 0) {
            $leave = G_Leave_Finder::findById(G_Leave::ID_SICK);
            $l = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $leave->getId(), $this->year);
            if (!$l) {
                $l = new G_Employee_Leave_Available;
            }
            $l->setEmployeeId($e->getId());
            $l->setLeaveId($leave->getId());
            $alloted = $l->getNoOfDaysAlloted();
            $l->setNoOfDaysAlloted($alloted + $this->sick_leaves);
            $available = $l->getNoOfDaysAvailable();
            $l->setNoOfDaysAvailable($available + $this->sick_leaves);
            $l->setCoveredYear($this->year);
            $this->leave_available_list[] = $l;
          }
          if ($this->vacation_leaves > 0) {
            $leave = G_Leave_Finder::findById(G_Leave::ID_VACATION);
            $l = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveIdYear($e->getId(), $leave->getId(), $this->year);
            if (!$l) {
                $l = new G_Employee_Leave_Available;
            }
            $l->setEmployeeId($e->getId());
            $l->setLeaveId($leave->getId());
            $alloted = $l->getNoOfDaysAlloted();
            $l->setNoOfDaysAlloted($alloted + $this->vacation_leaves);
            $available = $l->getNoOfDaysAvailable();
            $l->setNoOfDaysAvailable($available + $this->vacation_leaves);
            $l->setCoveredYear($this->year);
            $this->leave_available_list[] = $l;
          }
      }
    }

    protected function saveUpdateLeaveAvailable() {                    
        $e = G_Employee_Finder::findByEmployeeCode($this->employee_code);
        if( $e ){
            $l = new G_Employee_Leave_Available();
            $l->setEmployeeId($e->getId());
            $l->setCoveredYear($this->year);
            $l->setLeaveId($this->leave_id);
            $l->setNoOfDaysAlloted($this->credit_to_add);
            $l->setNoOfDaysAvailable($this->credit_to_add);
            $l->updateEmployeeLeaveCredits();
        }
    }

    protected function saveMultipleLeaveAvailable() {
        return G_Employee_Leave_Available_Manager::saveMulitple($this->leave_available_list);
    }

	private function emptyValues() {
		$this->employee_code = '';
		$this->sick_leaves = 0;
		$this->vacation_leaves = 0;
        $this->leave_id = 0;
        $this->credit_to_add = 0;
	}
}
?>