<?php
/*
	Usage:
		//$file = $_FILES['timesheet']['tmp_name'];
		$file = BASE_PATH . 'files/sample_import_files/import_overtime.xlsx';
		$g = new G_Schedule_Specific_Import($file);
		$g->import();
*/
class G_Schedule_Specific_Import {
	protected $employee_code;
	protected $date;
	protected $time_in;	
	protected $time_out;
	protected $reason;
	
	protected $value_list = array();
	protected $rest_day_list = array();
    protected $schedule_list = array();
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
		$is_imported   = false;						
		$read_sheet    = $this->obj_reader->getActiveSheet();
		$column_header = array();
		foreach ($read_sheet->getRowIterator() as $row) {
			$this->emptyValues();
			$cellIterator = $row->getCellIterator();
		   	foreach ($cellIterator as $cell) {		   		
				$current_row = $cell->getRow();
				$cell_value = $cell->getFormattedValue();
				$column = $cell->getColumn();
				$current_column = PHPExcel_Cell::columnIndexFromString($cell->getColumn());
				//$coord = $cell->getCoordinate();	
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
                        case 'date':
                            if( $cell_value != '' && $this->isDate($cell_value) ){
                            	$this->date = $this->convertToDate($cell_value);                      
                            }
                        break;
                        case 'time in':
                            if( $cell_value != '' && $this->isTime($cell_value) ){
                                $this->time_in = $this->convertToTime($cell_value);                         
                            }
                        break;
                        case 'time out':
                            if( $cell_value != '' && $this->isTime($cell_value) ){
                                $this->time_out = $this->convertToTime($cell_value);	                          
                            }
                        break;
                        case 'is restday':
                            $s_restday = mb_strtolower($cell_value);					
							if( trim($s_restday) == 'yes' ){						
								$this->addRestDayToList();
							}else{
								$this->removeToRestDayList();
							}
                        break;
                        default : 
                        break;
                    }
                }				
			}

			if ($this->employee_code != '' && $this->date != '' && $this->time_in != '' && $this->time_out != '') {				
                $this->addSchedule();
				$this->addValueToList();				
			}
		}
        $is_imported = $this->saveMultipleSchedule();
        $this->deleteRestDayChangeSchedule();
        $this->saveRestDayChangeSchedule();
        $this->saveMultipleAttendance();
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

    protected function addSchedule() {
		$date = $this->date;
		$time_in = $this->time_in;
		$time_out = $this->time_out;

		$is_saved = false;
        $e = G_Employee_Finder::findByEmployeeCode($this->employee_code);
		if ($e) {
			$s = G_Schedule_Specific_Finder::findByEmployeeAndDate($e, $date);
			if (!$s) {
				$s = new G_Schedule_Specific;
			}
    		$s->setDateStart($date);
    		$s->setDateEnd($date);
    		$s->setTimeIn($time_in);
    		$s->setTimeOut($time_out);
    		$s->setEmployeeId($e->getId());
            $this->schedule_list[] = $s;

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

    protected function deleteRestDayChangeSchedule() {
    	$a_remove_rest_day = $this->remove_to_rest_day;
    	if( !empty($a_remove_rest_day) ){
    		//Remove all restday which are set to not
    		G_Restday_Manager::deleteMultiple($a_remove_rest_day);    		
    	}
    }

    protected function saveRestDayChangeSchedule() {
    	$a_rest_day = $this->rest_day;
    	if( !empty($a_rest_day) ){
    		//Delete all previous requests    		
    		G_Restday_Manager::deleteMultiple($a_rest_day);
    		G_Restday_Manager::saveMultiple($a_rest_day);
    	}
    }

    protected function saveMultipleSchedule() {
        return G_Schedule_Specific_Manager::saveMultiple($this->schedule_list);
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

	private function addRestDayToList(){
		$e = G_Employee_Finder::findByEmployeeCode($this->employee_code);		
		if( $e ){			
			$r = new G_Restday;
			$r->setDate($this->date);
	        $r->setTimeIn($this->time_in);
	        $r->setTimeOut($this->time_out);
	        $r->setEmployeeId($e->getId());     
			$this->rest_day[] = $r;		
		}
	}

	private function removeToRestDayList(){
		$e = G_Employee_Finder::findByEmployeeCode($this->employee_code);		
		if( $e ){			
			$r = new G_Restday;
			$r->setDate($this->date);
	        $r->setTimeIn($this->time_in);
	        $r->setTimeOut($this->time_out);
	        $r->setEmployeeId($e->getId());     
			$this->remove_to_rest_day[] = $r;		
		}
	}
	
	private function emptyValues() {
		$this->employee_code = '';
		$this->date = '';
		$this->time_in = '';
		$this->time_out = '';
		$this->reason = '';		
	}

    private function convertToTime($value) {
        return date('H:i:s', strtotime($value));
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