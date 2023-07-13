<?php
/*
    DEPRECATED since 2014-01-14

	THIS IS USED TO IMPORT SPECIFIC EMPLOYEE SCHEDULE (THIS IS ALSO USED FOR CHANGE SCHEDULE)

	Usage:
		//$file = $_FILES['timesheet']['tmp_name'];
		$file = BASE_PATH . 'files/sample_import_files/import_schedule_specific.xlsx';
		$g = new G_Schedule_Import_Dates($file);		
		$g->import();
*/
class G_Schedule_Import_Dates {
	protected $file_to_import;
	protected $obj_reader;
	
	public function __construct($file) {
		$this->file_to_import = $file;
		$inputFileType = PHPExcel_IOFactory::identify($this->file_to_import);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType); 				
		$this->obj_reader = $objReader->load($this->file_to_import);
	}

	public function import() {			
		$employee_code = '';
		$date_start = '';
		$date_end = '';
		$time_start = '';
		$time_end = '';
							
		$read_sheet = $this->obj_reader->getActiveSheet();
		foreach ($read_sheet->getRowIterator() as $row) {
			$cellIterator = $row->getCellIterator();
		   	foreach ($cellIterator as $cell) {
				//$cell_value = $cell->getValue();	
				$cell_value = $cell->getFormattedValue();	
				if (($cell_value != "")) {
					if ($employee_code == '') {
						if (!$this->isDate($cell_value) && $this->isTime($cell_value)) {
							$employee_code = $cell_value;//$this->convertToValidDay($cell_value);
							continue;	
						}
					}
									
					$is_date = false;
					if ($this->isDate($cell_value)) {	
						//echo $cell_value;	
						//echo '<br>';						
						$is_date = true;
					}
					
					$is_time = false;
					if ($this->isTime($cell_value)) {							
						$is_time = true;
					}
					
					if ($is_date || $is_time) {
						if ($employee_code != '') {
							if ($is_date) {								
								if ($date_start == '') {
									$date_start = $cell_value;
									$schedules[$employee_code][$date_start]['date_in'] = $date_start;
									$schedules[$employee_code][$date_start]['employee_code'] = $employee_code;
								} else if ($date_end == '') {
									$date_end = $cell_value;
									$schedules[$employee_code][$date_start]['date_out'] = $date_end;
									$schedules[$employee_code][$date_start]['employee_code'] = $employee_code;									
								}
								
								if ($time_start != '' && $time_end != '') {
									$employee_code = '';
									$time_start = '';
									$time_end = '';
									$date_start = '';
									$date_end = '';
								}								
							}
							if ($is_time) {
								if ($time_start == '') {
									$time_start = $cell_value;
									$schedules[$employee_code][$date_start]['time_in'] = $time_start;
									$schedules[$employee_code][$date_start]['employee_code'] = $employee_code;
								} else if ($time_end == '') {
									$time_end = $cell_value;
									$schedules[$employee_code][$date_start]['time_out'] = $time_end;	
									$schedules[$employee_code][$date_start]['employee_code'] = $employee_code;
									
									if ($date_start != '') {
										$employee_code = '';
										$time_start = '';
										$time_end = '';
										$date_start = '';
										$date_end = '';
									}
								}
							}
						}
					} else {
						unset($schedules[$employee_code]);
						$employee_code = $cell_value;
						$time_start = '';
						$time_end = '';
						$date_start = '';
						$date_end = '';
					}
			  	}
		   	}
		}
		if (count($schedules) > 0) {
			return $this->_import($schedules);
		} else {
			return false;
		}
	}
	
	/*
		$schedules:
	
		Array
		(
			[EMP001] => Array
				(
					[2012-08-16] => Array
						(
							[date_in] => 2012-08-16
							[employee_code] => 2341
							[time_in] => 9pm
							[time_out] => 6am
						)		
				)		
			[EMP002] => Array
				(
					[2012-08-18] => Array
						(
							[date_in] => 2012-08-18
							[employee_code] => 2342
							[time_in] => 9am
							[time_out] => 6am
						)		
					[2012-08-19] => Array
						(
							[date_in] => 2012-08-19
							[employee_code] => 2342
							[time_in] => 9am
							[time_out] => 6am
						)		
				)	
		)
	*/
	private function _import($schedules) {
		$is_true = false;
		$is_imported = false;
		foreach ($schedules as $employee_code => $schedule) {
			$e = G_Employee_Finder::findByEmployeeCode($employee_code);
			foreach ($schedule as $schedule_date => $schedule) {
				$time_in = date('H:i:s', strtotime($schedule['time_in']));
				$time_out = date('H:i:s', strtotime($schedule['time_out']));
				if ($e) {
					if (strtotime($schedule['date_in']) && strtotime($schedule['date_out'])) {
						$date_in = date('Y-m-d', strtotime($schedule['date_in']));
						$date_out = date('Y-m-d', strtotime($schedule['date_out']));
						
						$ss = G_Schedule_Specific_Finder::findByEmployeeAndStartAndEndDate($e, $date_in, $date_out);
						if (!$ss) {
							$ss = new G_Schedule_Specific;
						}					
						$ss->setDateStart($date_in);
						$ss->setDateEnd($date_out);
						$ss->setTimeIn($time_in);
						$ss->setTimeOut($time_out);
						$ss->setEmployeeId($e->getId());
						$ss->save();
						
						$dates = Tools::getBetweenDates($date_in, $date_out);
						foreach ($dates as $date) {
							$is_true = G_Attendance_Helper::updateAttendance($e, $date);
							if ($is_true) {
								$is_imported = true;	
							}									
						}														
					} else if (strtotime($schedule['date_in'])) {						
						$date = date('Y-m-d', strtotime($schedule['date_in']));
						$ss = G_Schedule_Specific_Finder::findByEmployeeAndStartAndEndDate($e, $date, $date);
						if (!$ss) {
							$ss = new G_Schedule_Specific;
						}					
						$ss->setDateStart($date);
						$ss->setDateEnd($date);
						$ss->setTimeIn($time_in);
						$ss->setTimeOut($time_out);
						$ss->setEmployeeId($e->getId());
						$ss->save();
						
						$is_true = G_Attendance_Helper::updateAttendance($e, $date);
						if ($is_true) {
							$is_imported = true;	
						}														
					}
				}
			}
		}
		return $is_imported;
	}
	
	private function isDate($the_date) {
		//echo $the_date;
		//echo '<br>';	
		$is_date = false;
		$invalid_value = array('12am','1am','2am','3am','4am','5am','6am','7am','8am','9am','10am','11am','12pm','1pm','2pm','3pm','4pm','5pm','6pm','7pm','8pm','9pm','10pm','11pm','2400');
		if (in_array($the_date, $invalid_value)) {
			return false;
		}
		
		$time = date('H:i:s', strtotime($the_date));
		$date = date('Y-m-d', strtotime($the_date));
		if ($date != '1970-01-01' && $time == '00:00:00') {
			$is_date = true;	
		}
		return $is_date;	
	}
	
	private function isTime($the_time) {		
		$temp_value = (int) $the_time;
		
		$invalid_value = array('12am','1am','2am','3am','4am','5am','6am','7am','8am','9am','10am','11am','12pm','1pm','2pm','3pm','4pm','5pm','6pm','7pm','8pm','9pm','10pm','11pm');
		if (in_array($the_time, $invalid_value)) {
			return true;
		}
				
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