<?php
/*
	Usage:
		//$file = $_FILES['timesheet']['tmp_name'];
		$file = BASE_PATH . 'files/sample_import_files/import_overtime.xlsx';
		$g = new G_Overtime_Import($file);		
		$g->import();
*/
class G_Undertime_Import {
	protected $employee_code;
	protected $user_id;
	protected $company_structure_id;
	protected $date_of_undertime;
	protected $time_out;
	protected $reason;
	protected $status;	
	
	protected $value_list = array();
	
	protected $file_to_import;
	protected $obj_reader;
	
	public function __construct($file) {
		$this->file_to_import = $file;
		$inputFileType    = PHPExcel_IOFactory::identify($this->file_to_import);
		$objReader        = PHPExcel_IOFactory::createReader($inputFileType); 				
		$this->obj_reader = $objReader->load($this->file_to_import);
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;		
	}
	
	public function setUserId($value) {
		$this->user_id = $value;		
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
					$this->date_of_undertime = ucfirst($cell_value);
				}
				
				if ($column == 'C' && $cell_value != '') {
					$this->time_out = $cell_value;
				}
				
				if ($column == 'D' && $cell_value != '') {
					$this->reason = $cell_value;
				}
				
				if ($column == 'E' && $cell_value != '') {
					$this->status = $cell_value;
				}
			
				if ($this->employee_code != '' && $this->employee_code != 'Employee Code' && $this->date_of_undertime != '' && $this->time_out != '' && $this->reason != '' && $this->status != '') {					
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
	
	protected function save() {		
		$e 		  = G_Employee_Finder::findByEmployeeCode($this->employee_code);
		$is_saved = false;
		if($e){
			$gur = new G_Employee_Undertime_Request();
			$gur->setDateApplied(Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila'));
			$gur->setCompanyStructureId(Utilities::decrypt($this->company_structure_id));
			$gur->setEmployeeId($e->getId());			
			$gur->setDateOfUndertime($this->date_of_undertime);	
			$gur->setTimeOut($this->time_out);				
			$gur->setReason($this->reason);				
			$gur->setIsApproved($this->status);	
			$gur->setCreatedBy($this->user_id);				
			$gur->setIsArchive(G_Employee_Undertime_Request::NO);				
			$is_saved = $gur->save();
		}
		return $is_saved;
	}
	
	private function addValueToList() {
		//$this->value_list[$this->employee_code][$this->date] = array('time_in' => $this->time_in, 'time_out' => $this->time_out, 'reason' => $this->reason);	
	}
	
	private function emptyValues() {
		$this->employee_code  	 = '';		
		$this->date_of_undertime = '';
		$this->time_out 		 = '';
		$this->reason 		  	 = '';
		$this->status 		  	 = '';		
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
}
?>