<?php
/*
	Usage:
		//$file = $_FILES['timesheet']['tmp_name'];
		$file = BASE_PATH . 'files/sample_import_files/import_overtime.xlsx';
		$g = new G_Overtime_Import($file);
		$g->import();
*/
class G_Overtime_Import {
	protected $employee_code;
	protected $date;
	protected $time_in;
	protected $time_out;
	protected $reason;

    protected $ot_status = G_Overtime::STATUS_APPROVED;

	protected $value_list = array();
    protected $overtime_list = array();
    protected $attendance_list = array();
    protected $employees = array();
	
	protected $file_to_import;
	protected $obj_reader;

    protected $has_error = false;
    protected $total_errors = 0;

    protected $error_messages = array();
    protected $error_datetime_count = 0;

	
	public function __construct($file) {
		$this->file_to_import = $file;
		$inputFileType = PHPExcel_IOFactory::identify($this->file_to_import);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType); 				
		$this->obj_reader = $objReader->load($this->file_to_import);
	}

    public function setStatus($value) {
        $this->ot_status = $value;
    }

    public function setAsApproved() {
        $this->ot_status = G_Overtime::STATUS_APPROVED;
    }

    public function setAsDisapproved() {
        $this->ot_status = G_Overtime::STATUS_DISAPPROVED;
    }

    public function setAsPending() {
        $this->ot_status = G_Overtime::STATUS_PENDING;;
    }

	public function import() {	
		$is_imported = false;						
		$read_sheet = $this->obj_reader->getActiveSheet();
		$row_counter     = 0;
		foreach ($read_sheet->getRowIterator() as $row) {
			if($row_counter > 0) {
				$this->emptyValues();
				$cellIterator = $row->getCellIterator();
			   	foreach ($cellIterator as $cell) {
					$current_row = $cell->getRow();
					$cell_value = $cell->getFormattedValue();
					$column = $cell->getColumn();
					$current_column = PHPExcel_Cell::columnIndexFromString($cell->getColumn());
					//$coord = $cell->getCoordinate();	

					if ($column == 'A' && $cell_value != '') {
						$this->employee_code = trim($cell_value);

						$e = G_Employee_Finder::findByEmployeeCode($this->employee_code);
						if(!$e){
							$msg = "Row: ". $current_row ."- Employee Code not found.";
							array_push($this->error_messages, $msg);
							$this->error_datetime_count++;
						}

					}
					
					//if ($column == 'B' && $cell_value != '' ) {
					if ($column == 'B' && $cell_value != '') {

						$valid = $this->isDate($cell_value);

						if($valid){
							$this->date = $this->convertToDate($cell_value);
							//$this->date = date("Y-m-d",strtotime($cell_value));
						}
						else{
							$this->date = $cell_value;
							$msg = "Row: ". $current_row ."- invalid date format.";
							array_push($this->error_messages, $msg);
							$this->error_datetime_count++;
						}
						
					}
					
					if ($column == 'C' && $cell_value != '' && $this->isTime($cell_value)) {
						$this->time_in = $cell_value;

					}

					if ($column == 'D' && $cell_value != '' && $this->isTime($cell_value)) {
						$this->time_out = $cell_value;						
						
					}

					if($column == 'E' && $cell_value != ''){
						$this->reason = $cell_value;	
					}


					


				} 

					 if($this->employee_code == ''){
						 	$msg = "Row: ". $current_row ."- Employee Code not specified.";
							array_push($this->error_messages, $msg);
							$this->error_datetime_count++;
					 }
					 if($this->date == ''){
						 	$msg = "Row: ". $current_row ."- Date Overtime not specified.";
							array_push($this->error_messages, $msg);
							$this->error_datetime_count++;
					 }
					  if($this->time_in == ''){
						 	$msg = "Row: ". $current_row ."- Overtime time in not specified.";
							array_push($this->error_messages, $msg);
							$this->error_datetime_count++;
					 }
					  if($this->time_out == ''){
						 	$msg = "Row: ". $current_row ."- Overtime out not specified.";
							array_push($this->error_messages, $msg);
							$this->error_datetime_count++;
					 }

					 if ($this->employee_code != '' && $this->date != '' && $this->time_in != '' && $this->time_out != '') {
							//$reason = $read_sheet->getCellByColumnAndRow($current_column, $current_row)->getValue();
							//if ($reason != '') {
							//	$this->reason = $reason;	
							//}
	                        $this->addOvertime();
							$this->addValueToList();
							//$this->emptyValues();
					}
					 

			}
			$row_counter++;
		}

        $is_imported = $this->saveMultipleOvertime();
        $this->saveMultipleAttendance();
        $this->addOvertimeError();

        $request = $this->getAllByUnRequestOT();
		if(!empty($request)) {
			foreach ($request as $rData ) {

				$request_id   = $rData['id'];
	            $request_type = G_Request::PREFIX_OVERTIME;
	            $requestor_id = $rData['employee_id'];
	            $approver = array();

	            $gra = new G_Request_Approver();
	            $gra->setEmployeeId($requestor_id);
	            $approvers = $gra->getEmployeeRequestApprovers();

	            foreach ($approvers as $appArray) {
	                foreach ($appArray as $key => $value) {
	                	if($key == 0){
	                    $approver[] = Utilities::encrypt($value['employee_id']);
	                    } 
	                }
	                
	            }
	            if(!empty($approver)) {
	            	$r = new G_Request();
		            $r->setRequestorEmployeeId($requestor_id);
		            $r->setRequestId($request_id);
		            $r->setRequestType($request_type);
		            $r->saveEmployeeRequest($approver); //Save request approvers
	            }
	            
			}
		}
			

		return $is_imported;
	}

    private function addOvertimeError() {
        foreach ($this->employees as $date => $es) {
            foreach ($es as $e) {
                $a = G_Attendance_Helper::generateAttendance($e, $date);
                if ($a) {
                    $err = new G_Overtime_Error_Checker();
                    $err->checkByAttendanceAndEmployee($a, $e);
                    if ($err->hasError()) {
                        $new_errors = $err->getErrors();
                        if ($errors) {
                            $errors = array_merge($errors, $new_errors);
                        } else {
                            $errors = $new_errors;
                        }
                    } else {
                        $err->fixErrors();
                    }
                }
            }
        }
        if ($errors) {
            $this->has_error = true;
            $this->total_errors = count($errors);
            G_Overtime_Error_Manager::saveMultiple($errors);
        }
    }

    public function getTotalErrors() {
        return $this->total_errors;
    }

    public function hasError() {
        return $this->has_error;
    }



	public function hasErrorDatetime(){
		return $this->error_datetime_count;
	}

	public function getErrorDatetime(){
		return $this->error_messages;
	}



	public function directImport($values) {
		return true;
	}

    protected function addOvertime() {
		$date = $this->date;
		$time_in = date('H:i:s', strtotime($this->time_in));
		$time_out = date('H:i:s', strtotime($this->time_out));
		$reason = $this->reason;

		$is_saved = false;
		$e = G_Employee_Finder::findByEmployeeCode($this->employee_code);
		if ($e) {
			$o = G_Overtime_Finder::findByEmployeeAndDate($e, $this->date);
			if (!$o) {
				$o = new G_Overtime;
			}
            $d = Tools::getAutoDateInAndOut($date, $time_in, $time_out);
			$o->setDate($date);
			$o->setTimeIn($time_in);
			$o->setTimeOut($time_out);
            $o->setDateIn($d['date_in']);
            $o->setDateOut($d['date_out']);
			$o->setEmployeeId($e->getId());
			$o->setReason($reason);
            $o->setStatus($this->ot_status);
            $o->setDateCreated(date("Y-m-d H:i:s"));
            $this->overtime_list[] = $o;

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

    protected function saveMultipleOvertime() {

        return G_Overtime_Manager::saveMultiple($this->overtime_list);
    }

    protected function getAllByUnRequestOT() {
      	return G_Overtime_Helper::getAllByUnRequest();
    }
	
	protected function saveOvertime() {
		$date = $this->date;
		$time_in = date('H:i:s', strtotime($this->time_in));
		$time_out = date('H:i:s', strtotime($this->time_out));
		$reason = $this->reason;

		$is_saved = false;
		$e = G_Employee_Finder::findByEmployeeCode($this->employee_code);
		if ($e) {
			$o = G_Overtime_Finder::findByEmployeeAndDate($e, $this->date);
			if (!$o) {
				$o = new G_Overtime;
			}
            $d = Tools::getAutoDateInAndOut($date, $time_in, $time_out);
			$o->setDate($date);
			$o->setTimeIn($time_in);
			$o->setTimeOut($time_out);
            $o->setDateIn($d['date_in']);
            $o->setDateOut($d['date_out']);
			$o->setEmployeeId($e->getId());
			$o->setReason($reason);
			$o->setDateCreated(date("Y-m-d H:i:s"));
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

	public function constructAssignApproverHtml(){
		$request_counter = 0;
		$action_url = url('attendance/_assign_approver_to_imported_overtime');
		$html = "<form id=\"assign-approver-form\" method=\"post\" action=\"{$action_url}\" >";
			foreach ($this->overtime_list as $o) {
				$request_counter++;
				$ids[] = $o->getEmployeeId();
	            $ot_list[] = array(
	                "employee_id"       => $o->getEmployeeId(),
	                "date_overtime"     => $o->getDate(),
	                "time_in"           => $o->getTimeIn(),
	                "time_out"          => $o->getTimeOut(),
	                "status"            => $o->getStatus()
	            );
	            $html .= "
	            	<input type=\"hidden\" name=\"request_emp_id[{$request_counter}]\" value=\"{$o->getEmployeeId()}\" >
	            	<input type=\"hidden\" name=\"request_date[{$request_counter}]\" value=\"{$o->getDate()}\" >
	            	<input type=\"hidden\" name=\"request_time_in[{$request_counter}]\" value=\"{$o->getTimeIn()}\" >
	            	<input type=\"hidden\" name=\"request_time_out[{$request_counter}]\" value=\"{$o->getTimeOut()}\" >
	            	<input type=\"hidden\" name=\"request_status[{$request_counter}]\" value=\"{$o->getStatus()}\" >
	            ";
	        }
	        $ids = array_values(array_filter(array_unique($ids)));
	        $emp_ids = implode(",",$ids);
			$fields = array("id,CONCAT(lastname,' ', firstname, ' ',middlename) as fullname");
			$employees = G_Employee_Helper::sqlMultipleEmployeeDetailsById($emp_ids, $fields);

			$html .= "<div class=\"alert alert-info\">Set Request Approver for each employee that filed overtime. </div>";
			foreach($employees as $key => $value) {
				$c = $key + 1;
		        $html .= "
		        	<a href=\"javascript:void(0);\" id=\"{$value['id']}\" class=\"btn-assign-approver\" style=\"text-decoration:none;\"><h4 class=\"approver-name\">{$c}. <b>{$value['fullname']}</b></h4></a>
		        ";

		        $gra = new G_Request_Approver();
				$gra->setEmployeeId($value['id']);
				$approvers = $gra->getEmployeeRequestApprovers();

				$html .= "<div id=\"wrapper-{$value['id']}\" class=\"hide-wrapper\" style=\"margin-left:20px; width:80%; display:none;\" >";
					if($approvers){

						foreach($approvers as $level => $approver) {
							$html .= " Level {$level} : <select name=\"approver[{$value['id']}][{$level}]\" style=\"width:200px; margin-bottom:1px\">";
								foreach($approver as $a) {
									$html .= "
										<option value=\"{$a['employee_id']}\">{$a['employee_name']}</option>
									";
								}
							$html .= "</select><br/>";
						}
					}else{
						$html .= "<div class=\"alert alert-error\">No approvers set.</div>";
					}
				$html .= "</div>";
	    	}
	    $html .= "<input type=\"submit\" value=\"Submit\" class=\"btn\" style=\"margin-left:0px;\" >";
	    $html .= "</form>";
        return $html;
	}
}
?>