<?php
/*
	Usage:
		//$file = $_FILES['timesheet']['tmp_name'];
		$file = BASE_PATH . 'files/sample_import_files/import_overtime.xlsx';
		$g = new G_Overtime_Import($file);		
		$g->import();
*/
class G_OB_Import {
	protected $company_structure_id;
	protected $employee_code;	
	protected $date_applied;
	protected $date_start;
	protected $date_end;
	protected $comments;
	protected $is_approved;

	protected $is_whole_day;
	protected $time_start;
	protected $time_end;

    protected $ob_request_list = array();
    protected $employees = array();
	protected $value_list = array();
	
	protected $file_to_import;
	protected $obj_reader;
	
	public function setCompanyStructureId($value){
		$this->company_structure_id = $value;
	}
	
	public function __construct($file) {
		$this->file_to_import = $file;
		$inputFileType    = PHPExcel_IOFactory::identify($this->file_to_import);
		$objReader        = PHPExcel_IOFactory::createReader($inputFileType); 				
		$this->obj_reader = $objReader->load($this->file_to_import);
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
					$this->employee_code = $cell_value;
				}
				
				if ($column == 'B' && $cell_value != '' && $this->isDate($cell_value)) {
					$this->date_start = $this->convertToDate($cell_value);
				}
				
				if ($column == 'C' && $cell_value != '' && $this->isDate($cell_value)) {
					$this->date_end = $this->convertToDate($cell_value);
				}
				
				if($column == 'D'){

						if(ucfirst($cell_value) ==  G_Employee_Official_Business_Request::NO){
							$this->is_whole_day = G_Employee_Official_Business_Request::NO;
						}
						else{
							$this->is_whole_day = G_Employee_Official_Business_Request::YES;
						}
				}


				if($column == 'E' && $cell_value != '' && $this->isTime($cell_value)){

					$this->time_start = date("H:i:s", strtotime($cell_value));
				}

				if($column == 'F' && $cell_value != '' && $this->isTime($cell_value)){

					$this->time_end = date("H:i:s", strtotime($cell_value));
				}


				if ($column == 'G' && $cell_value != '') {
					$this->comments = $cell_value;
				}
				
				if ($column == 'H' && $cell_value != '') {
					$this->is_approved = ucfirst($cell_value);
				}
				
				if ($this->employee_code != '' && $this->employee_code != 'Employee Code' &&  $this->date_start != '' && $this->date_end != '' && $this->is_approved != '') {
					//$is_saved = $this->save();
					//if($is_saved) {
					//	$is_imported = true;
					//}
					//$this->addValueToList();
                    if ($this->is_approved == G_Employee_Official_Business_Request::YES) {
                        $this->addApprovedOfficialBusiness();
                    } else {
                        $this->addOfficialBusiness();
                    }
					
				}
				//$this->emptyValues();
			}
		}		
			$is_imported = $this->saveMultipleOB();
			$this->saveMultipleAttendance();

			$request = $this->getAllByUnRequestOB();
		
			if(!empty($request)) {


				foreach ($request as $rData ) {

					$request_id   = $rData['id'];
		            $request_type = G_Request::PREFIX_OFFICIAL_BUSSINESS;
		            $requestor_id = $rData['employee_id'];
					$approver = array();
		            $gra = new G_Request_Approver();
		            $gra->setEmployeeId($requestor_id);
		            $approvers = $gra->getEmployeeRequestApprovers();

		            if(!empty($approvers)) {
			            foreach ($approvers as $appArray) {
			                foreach ($appArray as $key => $value) {
			                    $approver[] = Utilities::encrypt($value['employee_id']); 
			                }
			                
			            }

			            $r = new G_Request();
			            $r->setRequestorEmployeeId($requestor_id);
			            $r->setRequestId($request_id);
			            $r->setRequestType($request_type);
			            $r->saveEmployeeRequest($approver); //Save request approvers
			            
		        	} else {
		        		continue;
		        	}  
				}

			}
			

		return $is_imported;
	}

    protected function saveMultipleOB() {
      	return G_Employee_Official_Business_Request_Manager::saveMultiple($this->ob_request_list);
    }

    protected function getAllByUnRequestOB() {
      	return G_Employee_Official_Business_Request_Helper::getAllByUnRequest();
    }

    protected function saveMultipleAttendance() {
        foreach ($this->employees as $date => $es) {
            list($date_start, $date_end) = explode(' ', $date);
            $dates = Tools::getBetweenDates($date_start, $date_end);
            foreach ($dates as $the_date) {
                foreach ($es as $e) {
                    $a = G_Attendance_Helper::generateAttendance($e, $the_date);
                    $this->attendance_list[] = $a;
                }
            }
        }
       return G_Attendance_Helper::updateAttendanceByMultipleAttendance($this->attendance_list);
    }
	
	public function directImport($values) {
		return true;
	}

    protected function addOfficialBusiness() {
        $e = G_Employee_Finder::findByEmployeeCode($this->employee_code);
        if ($e) {
            $this->ob_request_list[] = G_Employee_Official_Business_Request_Helper::create($e->getId(), Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila'), $this->date_start, $this->date_end, $this->is_approved, $this->comments, $this->is_whole_day, $this->time_start, $this->time_end);
        }
    }

    protected function addApprovedOfficialBusiness() {
        $e = G_Employee_Finder::findByEmployeeCode($this->employee_code);
        if ($e) {
            $this->ob_request_list[] = G_Employee_Official_Business_Request_Helper::create($e->getId(), Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila'), $this->date_start, $this->date_end, $this->is_approved, $this->comments, $this->is_whole_day, $this->time_start, $this->time_end);
            $this->employees["{$this->date_start} {$this->date_end}"][] = $e;
        }
    }
	
	protected function save() {
		if ($this->company_structure_id) {			
			$e = G_Employee_Finder::findByEmployeeCode($this->employee_code);
			if($e){
				$gobr = new G_Employee_Official_Business_Request();
				$gobr->setCompanyStructureId($this->company_structure_id);
				$gobr->setEmployeeId($e->getId());
				$gobr->setDateApplied(Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila'));
				$gobr->setDateStart($this->date_start);	
				$gobr->setDateEnd($this->date_end);				
				$gobr->setComments($this->comments);				
				$gobr->setIsApproved($this->is_approved);				
				$gobr->setCreatedBy(Utilities::decrypt($_SESSION['sprint_hr']['employee_id']));				
				$gobr->setIsArchive(G_Employee_Official_Business_Request::NO);	
				$is_saved = $gobr->save();
			}else{
				$is_saved = '';
			}
		}
		return $is_saved;
	}
	
	private function addValueToList() {
		//$this->value_list[$this->employee_code][$this->date] = array('time_in' => $this->time_in, 'time_out' => $this->time_out, 'reason' => $this->reason);	
	}
	
	private function emptyValues() {
		$this->employee_code = '';		
		$this->date_applied  = '';
		$this->date_start 	 = '';
		$this->date_end 	 = '';
		$this->comments 	 = '';
		$this->is_approved	 = '';
		$this->is_whole_day	 = '';
		$this->time_start	 = '';
		$this->time_end	 = '';
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