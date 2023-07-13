<?php
/*
	$file = BASE_PATH . 'files/files/import_dtr_im.xlsx';
	$time = new G_Timesheet_Import_IM($file);
	$time->import();
*/
class G_Timesheet_Import_IM extends Timesheet_Import {
	
	public function __construct($file) {
		parent::__construct($file);
	}
	
	public function import() {
		$inputFileType = PHPExcel_IOFactory::identify($this->file_to_import);  
		$objReader = PHPExcel_IOFactory::createReader($inputFileType); 				
		$objReader = $objReader->load($this->file_to_import);
		
		$read_sheet = $objReader->getActiveSheet();
		
		$column_position = 0;
		$excluded_headers = array('A1','B1','C1','D1','E1');
		
		$timesheets = array();
		$is_true = false;
		$is_imported = false;
		$error = '';
		foreach ($read_sheet->getRowIterator() as $row) {
		   $cellIterator = $row->getCellIterator();
		   foreach ($cellIterator as $cell) {
			  $cell_value = $cell->getFormattedValue();
			  if (($cell_value != "")) {				  
				 $coord = $cell->getCoordinate();
				if(!in_array($coord, $excluded_headers)) {
					if($column_position == 0) {
						$id = $cell_value; // get the id of 
					} else if($column_position == 3) {
						$type = $cell_value;
					} else if($column_position == 4) {
						$cell_value = trim($cell_value);
						list($date, $time) = explode(' ', $cell_value);
						$date = date('Y-m-d',strtotime($date));
						$time = date('H:i:s',strtotime($time));
						$timesheets[$id][$date][$time] = $date;			
					}					
					$column_position++;
					if($column_position == 5) {
						$column_position = 0;							
					}
					 
				} 
			  }
		   }
		}
		$tr = new Timesheet_Raw_Reader;
		foreach ($timesheets as $employee_code => $timesheet) {
			$e = G_Employee_Finder::findByEmployeeCode($employee_code);	
			if ($e) {
				$updated_times = $tr->getTimeInAndOut($timesheet);
				foreach ($updated_times as $date_in => $times) {
					list($time_in, $temp_date_in) = explode(' ', $times['in']);
					list($time_out, $date_out) = explode(' ', $times['out']);
					if ($time_in != '' && $time_out != '') {
						$is_true = G_Attendance_Helper::recordTimecard($e, $date_in, $time_in, $time_out, $date_in, $date_out);
						if ($is_true) {
							$is_imported = true;
							G_Attendance_Helper::updateAttendance($e, $date_in);
						} else {
							$error .= $employee_code . ', ';	
						}
					}
				}
			} else {
				$error .= $employee_code . ', ';	
			}
		}
		$return['is_imported'] = $is_imported;
		if ($is_imported) {
			if ($error == '') {
				$return['message'] = 'Timesheet has been successfully imported';
			} else {
				$return['message'] = 'Timesheet has been imported but has an error: '. $error;
			}
		} else {
			$return['message'] = 'An error occured. Please contact the administrator: '. $error;
		}
		return $return;
	}
}
?>