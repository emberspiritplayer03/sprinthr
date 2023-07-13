<?php
/*
	This is used for importing timesheet.

	Usage:
		$file = $_FILES['timesheet']['tmp_name'];
		//$file = BASE_PATH . 'files/files/attendance.xls';
		
		$time = new Timesheet_Import($file);
		$return = $time->import();
*/
class Timesheet_Import {
	protected $file_to_import;
	
	public function __construct($file) {
		$this->file_to_import = $file;	
	}
	
	public function import() {
		$data = new Excel_Reader($this->file_to_import);
		$total_row = $data->countRow();
		
		$error_count = 0;
		$imported_count = 0;
		for ($i = 1; $i <= $total_row; $i++) {
			$time_in = '';
			$time_out = '';
			$overtime_in = '';
			$overtime_out = '';
			$excel_employee_code = (string) trim($data->getValue($i, 'A'));
			if ($excel_employee_code) {
				$e = G_Employee_Finder::findByEmployeeCode($excel_employee_code);
				if (!$e) {
					$error_count++;
					$error = new G_Attendance_Error;
					$error->setMessage("Invalid Employee ID: '{$excel_employee_code}'");
					$error->setErrorTypeId(G_Attendance_Error::ERROR_INVALID_EMPLOYEE_ID);
					$error->addError();
				}				
			}

			if ($e) {
				$excel_date = $data->getValue($i, 'B');	
				if (!strtotime($excel_date)) {
					continue;
				}
				$date = date('Y-m-d', strtotime($excel_date));
							
				$excel_overtime_in = $data->getValue($i, 'E');
				$excel_overtime_out = $data->getValue($i, 'F');
				$excel_time_in = $data->getValue($i, 'C');
				$excel_time_out = $data->getValue($i, 'D');
																			
				if (strtotime($excel_time_in)) {
					$time_in = date('H:i:s', strtotime($excel_time_in));					
				}
				
				if (strtotime($excel_time_out)) {
					$time_out = date('H:i:s', strtotime($excel_time_out));	
				}
				
				if (!strtotime($excel_time_in) || !strtotime($excel_time_out)) {
					$error_count++;
					$error = new G_Attendance_Error;
					$error->setMessage("Invalid Time Format");
					$error->setErrorTypeId(G_Attendance_Error::ERROR_INVALID_TIME);
					$error->addError();					
				}
				
				if (strtotime($excel_overtime_in) && strtotime($excel_overtime_out)) {
					$overtime_in = date('H:i:s', strtotime($excel_overtime_in));
					$overtime_out = date('H:i:s', strtotime($excel_overtime_out));
				}
				if ($time_in != '' && $time_out != '') {
					$imported = G_Attendance_Helper::recordTimecard($e, $date, $time_in, $time_out, '', '', $overtime_in, $overtime_out);
					G_Attendance_Helper::updateAttendance($e, $date);
				}
				if ($imported) {
					$imported_count++;	
				}
			}
		}
				
		if ($imported_count > 0) {
			$return['is_imported'] = true;
			if ($error_count > 0) {
				$return['message'] = 'Some records has been successfully imported. Fix '. $error_count .' errors found.';
			} else {
				$return['message'] = 'Timesheet has been successfully imported.';
			}
		} else {
			$return['message'] = 'There was a problem importing the timesheet. Please contact the administrator.';
		}
		return $return;
	}
}
?>