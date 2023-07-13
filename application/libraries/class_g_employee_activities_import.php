<?php
class G_Employee_Activities_Import {
	protected $employee_code;	
	protected $employee_name;
	protected $designation;
	protected $activity;
	protected $date;
	protected $time_in;
	protected $date_out;
	protected $time_out;
	protected $reason;

	protected $project_site_name;
	protected $project_site_id;

    protected $employee_activities_list = array();
    protected $employees = array();
	
	protected $file_to_import;
	protected $obj_reader;

    public $errors = array();
    public $total_records = 0;
    public $imported_records = 0;
	
	public function __construct($file) {
		$this->file_to_import = $file;
		$inputFileType    = PHPExcel_IOFactory::identify($this->file_to_import);
		$objReader        = PHPExcel_IOFactory::createReader($inputFileType); 				
		$this->obj_reader = $objReader->load($this->file_to_import);
	}
	
	public function import() {	
		$is_imported = false;						
		$read_sheet  = $this->obj_reader->getActiveSheet();
		   
		//$highestColumn = $read_sheet->getHighestDataColumn();
		
		foreach ($read_sheet->getRowIterator() as $row) {
			$has_error   = false;
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
                   
                       if ($column == 'A' && $cell_value != '') {
							$this->employee_code = trim($cell_value);
						}
						
						if ($column == 'B' && $cell_value != '') {
							$this->project_site_name = trim($cell_value);
							$project_site = G_Project_Site::findByName($this->project_site_name);
							if($project_site){
								$this->project_site_id = $project_site->getId();
							}
							else{
								$has_error = true;
								$this->addError($current_row, 'Project Site not found');
							}

						}
						
						if ($column == 'C' && $cell_value != '') {
							$this->activity = trim($cell_value);
						}
						
						if ($column == 'D' && $cell_value != '') {
							$this->designation = trim($cell_value);
						}
						
						if ($column == 'E' && $cell_value != '') {
							
							if($this->isDate($cell_value)){
								$this->date = $this->convertToDate($cell_value);
								$this->date_out = $this->convertToDate($cell_value);
							}
							else{

								$has_error = true;
								$this->addError($current_row, 'Invalid date format');
							}
							
						}
						
						if ($column == 'F' && $cell_value != '' && $this->isTime($cell_value)) {
							$this->time_in = date("H:i:s", strtotime($cell_value));

						}
						
						if ($column == 'G' && $cell_value != '' && $this->isTime($cell_value)) {
							$this->time_out = date("H:i:s", strtotime($cell_value));
							
						}
				


		       }//end else
					
		    } //end foreach


		    if (!$has_error && $current_row > 1) {
               
				if ($this->employee_code != '' && $this->employee_code != 'Employee ID'  &&  $this->project_site_id != '' &&  $this->designation != '' && $this->activity != '' && $this->date != '' && $this->time_in != '' && $this->time_out != '') {

					if (Tools::isNightShift($this->time_out)) {
						$this->date_out = Tools::getTomorrowDate("{$this->date} {$this->time_out}");
					}
					$this->total_records++;
					$this->addEmployeeActivity($current_row);
					$this->emptyValues();
				}
				
			}




		}//end first foreach

		$is_imported = $this->saveMultiple();
		return $is_imported;

	}

    protected function saveMultiple() {

        return G_Employee_Activities_Manager::saveMultiple($this->employee_activities_list);
    }

    protected function addEmployeeActivity($current_row = 0) {
		$e = G_Employee_Finder::findByEmployeeCode($this->employee_code);
		
        if ($e) {
			$activity_date_time_in = $this->date . ' ' . $this->time_in;
			$activity_date_time_out = $this->date_out . ' ' . $this->time_out;
			
			$return = G_Employee_Activities_Helper::compareActivityToDTR($e->getId(), $activity_date_time_in, $activity_date_time_out);

			if ($return['is_invalid']) {
				$this->addError($current_row, $return['message']);
			}
			else {
				$this->setDesignation();
				$this->setActivity();
				//$this->setProjectSite();
				$check_duplicate = G_Employee_Activities_Finder::checkDuplicate($e->getId(), $this->designation, $this->activity, $this->date, $this->time_in, $this->date_out, $this->time_out, $this->project_site_id);
			
				if($check_duplicate){

					$this->addError($current_row, 'duplicate entry');
				}
				else{

					$this->employee_activities_list[] = G_Employee_Activities_Helper::create($e->getId(), $this->designation, $this->activity, $this->date, $this->time_in, $this->date_out, $this->time_out, $this->reason, $this->project_site_name, $this->project_site_id);
					$this->imported_records++;

				}
				
			}
		}
		else {
			$this->addError($current_row, 'Employee Code ' . $this->employee_code . " doesn't exists.");
		}
    }



    protected function setDesignation() {
		$designation = G_Activity_Category_Finder::findByName($this->designation);
		$inserted_id = 0;

		if (!$designation) {
			$designation = new G_Activity_Category();  
			$designation->setActivityCategoryName($this->designation);
			$designation->setDateCreated(date('Y-m-d'));
			$inserted_id = $designation->save();
		}
		else {
			$inserted_id = $designation->getId();
		}

		$this->designation = $inserted_id;
    }

    protected function setActivity() {
		$activity = G_Activity_Skills_Finder::findByName($this->activity);
		$inserted_id = 0;

		if (!$activity) {
			$activity = new G_Activity_Skills();  
			$activity->setActivitySkillsName($this->activity);
			$activity->setDateCreated(date('Y-m-d'));
			$inserted_id = $activity->save();
		}
		else {
			$inserted_id = $activity->getId();
		}

		$this->activity = $inserted_id;
    }
	
	private function emptyValues() {
		$this->employee_code 	= '';		
		$this->employee_name 	= '';		
		$this->designation  	= '';
		$this->activity 	 	= '';
		$this->date 	 		= '';
		$this->time_in 	 		= '';
		$this->date_out	 		= '';
		$this->time_out 	 	= '';
		$this->reason 	 		= '';
		$this->project_site_id 	= '';
		$this->project_site_name = '';
	}

    private function convertToDate($value) {
        $dates = explode('-', $value);
        $month = $dates[0];
        $day = $dates[1];
        $year = $dates[2];
        $date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
        return $date;
    }

    private function isDate($the_date) {
        $date_format = DateTime::createFromFormat("m-d-Y", $the_date);

        if ($date_format !== false && !array_sum($date_format->getLastErrors())) {
            return true;
        } else {
            return false;
        }
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

    protected function addError($current_row, $message) {
		$this->errors[] = 'Row '. $current_row .': ' . $message;
    }

    protected function hasData($read_sheet, $current_row, $highestColumn) {
		$rowData = array_filter(array_map('array_filter', $read_sheet->rangeToArray('A' . $current_row . ':' . $highestColumn . $current_row,NULL,TRUE,FALSE)));

		if (count($rowData) > 0) {
			return true;
		}

		return false;
    }
}
?>