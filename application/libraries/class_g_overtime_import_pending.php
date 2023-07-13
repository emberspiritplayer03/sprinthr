<?php
/*
	Usage:
		//$file = $_FILES['timesheet']['tmp_name'];
		$file = BASE_PATH . 'files/sample_import_files/import_overtime.xlsx';
		$g = new G_Overtime_Import($file);		
		$g->import();
	
*/
class G_Overtime_Import_Pending extends G_Overtime_Import {
	protected $created_by;
	
	public function __construct($file) {
		parent::__construct($file);	
	}
	
	public function setCreatedBy($value) {
		$this->created_by = $value;
	}
	
	protected function saveOvertime() {
		$date = $this->date;
		$time_in = date('H:i:s', strtotime($this->time_in));
		$time_out = date('H:i:s', strtotime($this->time_out));
		$reason = $this->reason;
		
		$is_saved = true;
		$e = G_Employee_Finder::findByEmployeeCode($this->employee_code);
		
		if($e) {
			$job = G_Employee_Job_History_Finder::findCurrentJob($e);
		}

		if ($job) {
			$validate_import = G_Overtime_Helper::validateImportOvertime($e,$date,$time_in,$time_out);
			//echo $time_in;
			//Tools::showArray($job);
			if($validate_import == true) {
				$o = G_Employee_Overtime_Request_Finder::findByEmployeeIdAndDate($e->getId(), $this->date);

				if(!$o) {
					$o = new G_Employee_Overtime_Request();
				}
				
				$o->setEmployeeId($e->getId());
				$o->setDateApplied($date);
				$o->setDateStart($date);
				$o->setTimeIn($time_in);
				$o->setTimeOut($time_out);
				$o->setOvertimeComments($reason);
				$o->setIsApproved(G_Employee_Overtime_Request::PENDING);
				$o->setIsArchive(G_Employee_Overtime_Request::NO);
				$o->setCreatedBy($this->created_by);
				$o->save();
				
				//$is_saved = true;
				
				G_Attendance_Helper::updateAttendance($e, $date);	
			}
			
		} else {
			$error = new G_Overtime_Error;
			$error->setEmployeeId();
			$error->setEmployeeCode($this->employee_code);
			$error->setDate($date);
			$error->setTimeIn($time_in);
			$error->setTimeOut($time_out);
			$error->setMessage("Cannot find employee_code : " .$this->employee_code . " Or there is no existing job history under employee_code : " . $this->employee_code);
			$error->setErrorTypeId(G_Overtime_Error::INVALID_EMPLOYEE_ID);
			$error->addError();
			
			$_SESSION['error']['import_overtime']++;
			//$is_saved = true;
		}
		return $is_saved;
	}
}
?>