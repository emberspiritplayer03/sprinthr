<?php
/*
	THIS IS USED TO CREATE WEEKLY SCHEDULE AND ASSIGN BULK EMPLOYEES TO IT.

	Usage:
		//$file = $_FILES['timesheet']['tmp_name'];
		$file = BASE_PATH . 'files/sample_import_files/import_schedule_weekly.xlsx';
		$g = new G_Schedule_Import_Weekly($file);		
		$g->import();
*/
class G_Schedule_Import_Weekly {
	protected $file_to_import;
	protected $obj_reader;
	protected $effectivity_date;
	protected $end_date;
    protected $schedule_name = 'New Schedule';
	
	protected $employees;
	
	public function __construct($file) {
		$this->file_to_import = $file;
		$inputFileType = PHPExcel_IOFactory::identify($this->file_to_import);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType); 				
		$this->obj_reader = $objReader->load($this->file_to_import);
	}
	
	public function setEmployees($employees) {
		$this->employees = $employees;	
	}
	
	public function getEmployees() {
		return $this->employees;	
	}
	
	public function setEffectivityDate($date) {		
		$this->effectivity_date = $date;	
	}

	public function setEndDate($value) {
		$date_formatted = date("Y-m-d",strtotime($value));
		$this->end_date = $date_formatted;
	}

    public function setScheduleName($value) {
        $this->schedule_name = $value;
    }

    public function getScheduleName($value) {
        return $this->schedule_name;
    }

	public function import() {			
		$current_day = '';
		$current_day_time_in = '';
		$current_day_time_out = '';
							
		$read_sheet = $this->obj_reader->getActiveSheet();
		foreach ($read_sheet->getRowIterator() as $row) {
			$cellIterator = $row->getCellIterator();
		   	foreach ($cellIterator as $cell) {
				//$cell_value = $cell->getValue();
				$cell_value = $cell->getFormattedValue();
				if (($cell_value != "")) {
					if ($current_day == '') {
						if ($this->isDay($cell_value)) {
							$current_day = $this->convertToValidDay($cell_value);
							continue;	
						}
					}
					
					$is_day = false;
					if ($this->isDay($cell_value)) {
						$is_day = true;
					}
					
					$is_time = false;
					if ($this->isTime($cell_value)) {					
						$is_time = true;
					}
					
					$employees[] = $cell_value;
					
					if ($is_day || $is_time) {
						if ($current_day != '') {
							if ($is_day) {
								if ($current_day_time_in == '' || $current_day_time_out == '') {
									$current_day = $this->convertToValidDay($cell_value);
									$current_day_time_in = '';
									$current_day_time_out = '';
									continue;
								}								
							}
							if ($is_time) {
								if ($current_day_time_in == '' && $current_day_time_out == '') {
									$current_day_time_in = $cell_value;
									$the_days[$current_day]['time_in'] = $current_day_time_in;
								} else if ($current_day_time_in != '' && $current_day_time_out == '') {
									$current_day_time_out = $cell_value;
									$the_days[$current_day]['time_out'] = $current_day_time_out;								
									$current_day = '';
									$current_day_time_in = '';
									$current_day_time_out = '';					
								}
							}
						}
					} /*else {
						$employees[] = $cell_value;
					}*/
			  	}
		   	}
		}

		if (count($the_days) > 0) {
			$group = $this->createSchedule($the_days);
            //if (!$this->hasDuplicate($group)) {
    			if ($group) {
    				$this->setEmployees($employees);
    				$this->assignScheduleGroup($employees, $group);
    				return true;
    			} else {
    				return false;
    			}
            //} else {
            //    return false;
            //}
		} else {
			return false;	
		}

	}

    private function hasDuplicate($group) {
        $name = $group->getName();
        $date = $group->getEffectivityDate();
        $total = G_Schedule_Group_Helper::countByNameAndEffectivityDate($name, $date);
        if ($total > 0) {
            return true;
        } else {
            return false;
        }
    }
	
	/*
		$employees[] = 'G001';
		$employees[] = 'G002';
		$employees[] = 'G003';
		$employees[] = 'G004';
		$group - Instance of G_Schedule_Group class
	*/
	private function assignScheduleGroup($employees, $group) {
		$is_true = false;
		foreach ($employees as $employee_code) {
			$e = G_Employee_Finder::findByEmployeeCode($employee_code);
			if ($e) {
				$is_assigned = $group->assignToEmployee($e, $this->effectivity_date);
				if ($is_assigned) {
					$is_true = true;	
				}
			}
		}
		return $is_true;
	}
	
	/*
		$value = 'm-f';
		
		Output:
			$value = 'mon,tue,wed,thu,fri';
	*/
	private function convertToValidDay($value) {
		return str_replace(' ', '', $value);
	}
	
	private function createSchedule($schedules) {
		$g = new G_Schedule_Group;
		$g->setName($this->schedule_name);
		$g->setEffectivityDate($this->effectivity_date);
		$g->setEndDate($this->end_date);
		$group_id = $g->save();
		$group = G_Schedule_Group_Finder::findById($group_id);
		if ($group) {
			foreach ($schedules as $day => $times) {			
				if (strtotime($times['time_in']) && strtotime($times['time_out'])) {
					$time_in = date('H:i:s', strtotime($times['time_in']));
					$time_out = date('H:i:s', strtotime($times['time_out']));	
					$s = new G_Schedule;
					$s->setName($this->schedule_name);
					$s->setWorkingDays($day);
					$s->setTimeIn($time_in);
					$s->setTimeOut($time_out);
					$id = $s->save();
					$s = G_Schedule_Finder::findById($id);
					if ($s) {
						$s->saveToScheduleGroup($group);
					}
				}
			}
		}
		return $group;
	}
	
	/*
		$the_day - 'mon,tue,wed,thu,fri,sat,sun';
	*/
	private function isDay($the_day) {
		$is_day = false;
		$days = array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun');
		$temp_days = explode(',', $the_day);
		foreach ($temp_days as $temp_day) {
			if (in_array(trim($temp_day), $days)) {
				$is_day = true;
				break;
			}
		}
		return $is_day;	
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