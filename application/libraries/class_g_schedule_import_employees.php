<?php
/*
	THIS IS USED TO IMPORT BULK EMPLOYEES INTO SPECIFIC SCHEDULE GROUP

	Usage:
		//$file = $_FILES['timesheet']['tmp_name'];
		$file = BASE_PATH . 'files/sample_import_files/import_schedule_by_employees.xlsx';
		$sg = G_Schedule_Group_Finder::findById(1);
		$g = new G_Schedule_Import_Employees($file);		
		$g->import($sg);	
*/
class G_Schedule_Import_Employees {
	protected $file_to_import;
	protected $obj_reader;
	
	protected $employees;
	protected $effectivity_date;
    protected $obj_schedule_group;
	
	public function __construct($file) {
		$this->file_to_import = $file;
		$inputFileType = PHPExcel_IOFactory::identify($this->file_to_import);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType); 				
		$this->obj_reader = $objReader->load($this->file_to_import);
	}

    // Deprecated
	public function setEffectivityDate($date) {
		$this->effectivity_date = $date;	
	}
	
	public function addEmployee($employee) {
		$this->employees[] = $employee;
	}
	
	public function getEmployees() {
		return $this->employees;	
	}	
	
	/*
		$sg - Instance of G_Schedule_Group class
	*/
	public function import($sg) {
		if (!$sg) {
			return false;
		}
        $this->obj_schedule_group = $sg;
		$is_true = false;
		$is_imported = false;
		$read_sheet = $this->obj_reader->getActiveSheet();
		foreach ($read_sheet->getRowIterator() as $row) {
		   $cellIterator = $row->getCellIterator();
		   foreach ($cellIterator as $cell) {
			  if (($cell->getValue() != "")) {
				// $coord = $cell->getCoordinate();
				 //echo "Cell:$coord - " . $cell->getValue() ."<BR>";
				 //echo "Cell:". $cell->columnIndexFromString($cell->getColumn()) ." - " . $cell->getValue() ."<BR>";
				$e = G_Employee_Finder::findByEmployeeCode($cell->getValue());
				if ($e) {
				    $is_assigned = G_Schedule_Group_Helper::isEmployeeAlreadyAssigned($e, $sg);
                    if (!$is_assigned) {
					    $this->addEmployee($e);
                    }
					//$is_true = G_Schedule_Group_Manager::assignToEmployee($e, $sg, $this->effectivity_date);
					//if ($is_true) {
					//	$is_imported = true;
					//}
				 }
			  }
		   }
		}

        $is_imported = $this->saveMultipleEmployee();
        $this->saveMultipleAttendance();

		return $is_imported;
	}
    protected function saveMultipleAttendance() {
        $effectivity_date = $this->obj_schedule_group->getEffectivityDate();
      	$c = G_Cutoff_Period_Finder::findByDate($effectivity_date);
      	if ($c) {
            $start_date = $effectivity_date;
      		$end_date = $c->getEndDate();
        }

        // UPDATE ATTENDANCE
        if ($c) {
            G_Attendance_Helper::updateAttendanceByEmployeesAndPeriod($this->employees, $start_date, $end_date);
        }
    }

    protected function saveMultipleEmployee() {
        return $this->obj_schedule_group->assignToMultipleEmployees($this->employees);
    }
}
?>