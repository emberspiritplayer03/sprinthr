<?php
/*
	Usage:
		//$file = $_FILES['timesheet']['tmp_name'];
		$file = BASE_PATH . 'files/sample_import_files/import_overtime.xlsx';
		$g = new G_Restday_Import($file);
		$g->import();
*/
class G_Restday_Import {
	protected $employee_code;
	protected $date;
	protected $time_in;
	protected $time_out;
	protected $reason;
	
	protected $value_list = array();
    protected $restday_list = array();
    protected $attendance_list = array();
    protected $employees = array();
	
	protected $file_to_import;
	protected $obj_reader;
	
	public function __construct($file) {
		$this->file_to_import = $file;
		$inputFileType = PHPExcel_IOFactory::identify($this->file_to_import);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType); 				
		$this->obj_reader = $objReader->load($this->file_to_import);
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

				if ($column == 'A' && $cell_value != '') {
					$this->employee_code = $cell_value;
				}

				if ($column == 'B' && $cell_value != '' && $this->isDate($cell_value)) {
					//$this->date = date('Y-m-d', strtotime($cell_value));
                    $this->date = $this->convertToDate($cell_value);
				}
				
				if ($column == 'C' && $cell_value != '' && $this->isTime($cell_value)) {
                    $this->time_in = $this->convertToTime($cell_value);
				} else if ($this->employee_code != '' && $this->date != '' && $column == 'C') {
                    $this->addRestday();
    				$this->addValueToList();
    				$this->emptyValues();
                    break;
				}

				if ($column == 'D' && $cell_value != '' && $this->isTime($cell_value)) {
					$this->time_out = $this->convertToTime($cell_value);

                    $the_e = $read_sheet->getCellByColumnAndRow($current_column + 1, $current_row)->getValue();
                    if (!$the_e) {
                        $this->addRestday();
      				    $this->addValueToList();
      				    $this->emptyValues();
                        break;
                    }
                } else if ($this->employee_code != '' && $this->date != '' && $column == 'D') {
                    $this->addRestday();
    				$this->addValueToList();
    				$this->emptyValues();
                    break;
                }

				if ($column == 'E' && $cell_value != '') {
					$this->reason = $cell_value;
                    $this->addRestday();
    				$this->addValueToList();
    				$this->emptyValues();
                    break;
                } else if ($this->employee_code != '' && $this->date != '' && $column == 'E') {
                    $this->addRestday();
    				$this->addValueToList();
    				$this->emptyValues();
                    break;
                }

  				//$reason = $read_sheet->getCellByColumnAndRow($current_column, $current_row)->getValue();
  				//if ($reason != '') {
  				//	$this->reason = $reason;
  			    //}
			}
		}

        $is_imported = $this->saveMultipleRestday();
        $this->saveMultipleAttendance();
		
		//if (count($this->value_list) > 0) {
		//	return $this->directImport($this->value_list);
		//} else {
		//	return false;	
		//}
		return $is_imported;
	}
	
	/*
		$values: 
		
		Array
		(
			[EMP001] => Array
				(
					[2012-08-16] => Array
						(
							[time_in] => 9pm
							[time_out] => 6am
							[reason] => Testing
						)		
				)		
			[EMP002] => Array
				(
					[2012-08-18] => Array
						(
							[time_in] => 9pm
							[time_out] => 6am
							[reason] => Testing
						)		
					[2012-08-19] => Array
						(
							[time_in] => 9pm
							[time_out] => 6am
							[reason] => Testing
						)		
				)	
		)
	*/
	public function directImport($values) {
		return true;
	}

    protected function addRestday() {
		$date = $this->date;
        $time_in = $this->time_in;
        $time_out = $this->time_out;
		$reason = $this->reason;

		$is_saved = false;
        $e = G_Employee_Finder::findByEmployeeCode($this->employee_code);
		if ($e) {
			$o = G_Restday_Finder::findByEmployeeAndDate($e, $this->date);
			if (!$o) {
				$o = new G_Restday;
			}
			$o->setDate($date);
			$o->setTimeIn($time_in);
			$o->setTimeOut($time_out);
			$o->setEmployeeId($e->getId());
			$o->setReason($reason);
            $this->restday_list[] = $o;

            $this->employees[$date][] = $e;
		}
    }

    protected function saveMultipleAttendance() {
        foreach ($this->employees as $date => $es) {
            foreach ($es as $e) {
                $a = G_Attendance_Helper::generateAttendance($e, $date);
                $this->attendance_list[] = $a;
            }
        }
        return G_Attendance_Helper::updateAttendanceByMultipleAttendance($this->attendance_list);
    }

    protected function saveMultipleRestday() {
        return G_Restday_Manager::saveMultiple($this->restday_list);
    }

    // Deprecated
	protected function save() {
		$date = $this->date;
		$time_in = date('H:i:s', strtotime($this->time_in));
		$time_out = date('H:i:s', strtotime($this->time_out));
		$reason = $this->reason;
		
		$is_saved = false;

		$e = G_Employee_Finder::findByEmployeeCode($this->employee_code);
		if ($e) {
			$o = G_Restday_Finder::findByEmployeeAndDate($e, $this->date);
			if (!$o) {
				$o = new G_Restday;
			}
			$o->setDate($date);
			$o->setTimeIn($time_in);
			$o->setTimeOut($time_out);
			$o->setEmployeeId($e->getId());
			$o->setReason($reason);
			$is_saved = $o->save();

			G_Attendance_Helper::updateAttendance($e, $date);
		}
		return $is_saved;
	}
	
	private function addValueToList() {
		$this->value_list[$this->employee_code][$this->date] = array('time_in' => $this->time_in, 'time_out' => $this->time_out, 'reason' => $this->reason);	
	}
	
	private function emptyValues() {
		$this->employee_code = '';
		$this->date = '';
		$this->time_in = '';
		$this->time_out = '';
		$this->reason = '';
	}

    private function convertToDate($value) {
        $dates = explode('-', $value);
        $month = $dates[0];
        $day = $dates[1];
        $year = $dates[2];
        $date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
        return $date;
    }

    private function convertToTime($value) {
        return date('H:i:s', strtotime($value));
    }
		
	private function isDate($the_date) {
//		$is_date = false;
//		echo $time = date('H:i:s', strtotime($the_date));
//		$date = date('Y-m-d', strtotime($the_date));
//		if ($date != '1970-01-01' && $time == '00:00:00') {
//			$is_date = true;
//		}
//		return $is_date;

        $date_format = DateTime::createFromFormat("m-d-Y", $the_date);

        if ($date_format !== false && !array_sum($date_format->getLastErrors())) {
            return true;
        } else {
            return false;
        }
		//return strtotime($the_date);
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