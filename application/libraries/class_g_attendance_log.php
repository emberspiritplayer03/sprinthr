<?php
/*
	Usage:
		$e = new G_Attendance_Log;
		$e->setEmployeeId(4);
		$e->setEmployeeCode(4);
		$e->setEmployeeName('ase');
		$e->setDate('2012-10-04');
		$e->setTime('08:00:00');
		$e->setType(G_Attendance_Log::TYPE_IN);	
		$e->save();		
*/
class G_Attendance_Log extends Attendance_Log {
	const TYPE_IN = 'in';
	const TYPE_OUT = 'out';
	
	const LOGS 	  	       = 'Logs';
	const INCOMPLETE_SWIPE = 'Incomplete Swipe';
	const MULTIPLE_SWIPE   = 'Multiple Swipe';

	const IS_TRANSFERRED = 1;
	const ISNOT_TRANSFERRED = 0;
	
	protected $id;
	protected $employee_code;
	protected $employee_id;
	protected $employee_name;
	protected $date_time_in;
	protected $date_time_out;	
	protected $raw_timesheet = array();

	public function __construct() {
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}	
	
	public function setEmployeeCode($value) {
		$this->employee_code = $value;	
	}
	
	public function getEmployeeCode() {
		return $this->employee_code;	
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;	
	}	
	
	public function setEmployeeName($value) {
		$this->employee_name = $value;	
	}
	
	public function getEmployeeName() {
		return $this->employee_name;	
	}	

	public function setDateTimeIn($date_time = array()){
		//Format : array(date => time)
		$this->date_time_in = $date_time;
	}

	public function setDateTimeOut($date_time = array()){
		//Format : array(date => time)
		$this->date_time_out = $date_time;
	}
	
	public function save() {
		return G_Attendance_Log_Manager::save($this);
	}

	public function updateUntransfferedDataByDate(){
		$return = false;
		if( !empty($this->date) ){
			G_Attendance_Log_Manager::updateUntransfferedDataByDate($this->date);
			$return = true;
		}

		return $return;
	}

	public function syncToAttendance(){
		$return = array();
		$return['is_successful'] = false;
		$return['message']       = "Cannot sync attendance";

		if( $this->id > 0 ){
			$log_data = G_Attendance_Log_Helper::sqlGetDataById($this->id);
			if( !empty($log_data) ){
				$e = G_Employee_Finder::findByEmployeeCode($log_data['employee_code']);
				if( $e ){
					$date = date("Y-m-d",strtotime($log_data['date']));
					$time = date("H:i:s",strtotime($log_data['time']));
					
					if( strtolower($log_data['type']) == self::TYPE_IN ){
						$raw_timesheet[$log_data['employee_code']]['in'][$date] = array($time => $date);
						//$e->punchIn($date, $time);	
					}else{
						$raw_timesheet[$log_data['employee_code']]['out'][$date_in] = array($time => $date);
						//$e->punchOut($date, $time);
					}
		    		
		    		$r = new G_Timesheet_Raw_Logger($raw_timesheet);
					$r->logTimesheet();

					$tr = new G_Timesheet_Raw_Filter($raw_timesheet);
					$tr->filterAndUpdateAttendance();

					$return['is_successful'] = true;
					$return['message']       = "Logs was successfully syn to attendance";
				}
			}			
		}

		return $return;
	}

	/*
	Usage:
		$file = $_FILES['timesheet']['tmp_name'];
		$logs = new G_Attendance_Log();
        $logs->importData($file);

	*/

	public function importData($file) {
		return G_Attendance_Log_Manager::importLogs($file);
	}

    public function addAttendanceLog() {
    	$return = array();

    	if( (!empty($this->date_time_in) || !empty($this->date_time_out)) && !empty($this->employee_id)){
    		$e = G_Employee_Finder::findById($this->employee_id); //Get Employee data

    		if($e){

    			$this->employee_code = $e->getEmployeeCode();
    			$this->employee_name = $e->getLastname() . ", " . $e->getFirstname();
    			    			
    			$data_in = $this->date_time_in;
    			foreach($data_in as $key => $value){
    				$time_in = date("H:i:s", strtotime($value));
    				$date_in = date("Y-m-d", strtotime($key));	
    			}
    			
    			$data_out = $this->date_time_out;
    			foreach($data_out as $key => $value){
    				$time_out = date("H:i:s", strtotime($value));
    				$date_out = date("Y-m-d", strtotime($key));	
    			}

				if (!empty($this->date_time_in)) {
					$this->date = $date_in;
					$this->time = $time_in;
					$this->type = self::TYPE_IN;
					$this->save(); //Save Attendance Log : IN
				}

				if (!empty($this->date_time_out)) {
					$this->date = $date_out;
					$this->time = $time_out;
					$this->type = self::TYPE_OUT;
					$this->save(); //Save Attendance Log : OUT
				}

    			$af = G_Attendance_Finder::findByEmployeeAndDate($e, $date_in); 

    			if($af){
    				//Change timesheet if set date already exists
    				$af->changeTimeOut($time_out);
    				$af->changeTimeIn($time_in);

    			}else{

    				//Create timesheet data			    		
					$raw_timesheet[$this->employee_code]['in'][$date_in] = array($time_in => $date_out);
		    		$raw_timesheet[$this->employee_code]['out'][$date_out] = array($time_out => $date_out);
		    		
		    		$r = new G_Timesheet_Raw_Logger($raw_timesheet);
					$r->logTimesheet();

					$tr = new G_Timesheet_Raw_Filter($raw_timesheet);
					$tr->filterAndUpdateAttendance();
    			}

    			$return['is_saved'] = true;
				$return['message']  = 'Record was successfully saved.';
    		}else{
    			$return['is_saved'] = false;
				$return['message']  = 'Data Error';
    		}
    	}else{
    		$return['is_saved'] = false;
			$return['message']  = 'Data Error';
    	}

    	return $return;
    }

    public function updateFpLogsEntries( $data = array() ) {    	
    	if( !empty($data) && $this->employee_code != '' ){      	
    		$data_in  = $data['in'];
    		$data_out = $data['out'];

    		foreach( $data_in as $key => $values ){    					
    			$date = $values['date'];
    			$time = $values['time'];
    			$type = $values['type'];
    			$id   = $key;
    			$new_data   = array("time" => $time, "type" => $type);    			    			
    			$fields     = array('time','type');
    			$is_success = G_Attendance_Log_Manager::updateAttendanceLogsById($id, $new_data, $fields);
    			if( $is_success  ){    			    				
    				$timesheet[$this->employee_code]['in'][$date] = array($time => $date);
    			}
    		}

    		foreach( $data_out as $key => $values ){    					
    			$date = $values['date'];
    			$time = $values['time'];
    			$type = $values['type'];
    			$id   = $key;
    			$new_data   = array("time" => $time, "type" => $type);    			    			
    			$fields     = array('time','type');
    			$is_success = G_Attendance_Log_Manager::updateAttendanceLogsById($id, $new_data, $fields);
    			if( $is_success  ){    				
    				$timesheet[$this->employee_code]['out'][$date] = array($time => $date);
    			}
    		}

    		$this->raw_timesheet = $timesheet;
    	}
    	return $this;
    }    

    public function updateAttendance() {    
    	$return['is_success'] = false;
    	$return['message']    = 'Cannot update record';	    	    	
    	if( !empty($this->raw_timesheet) ){    		
    		$timesheet = $this->raw_timesheet;
    		$tr = new G_Timesheet_Raw_Filter($timesheet);
       		$tr->filterAndUpdateAttendance();
       		$return['is_success'] = true;
    		$return['message']    = 'Attendance was successfully updated';	    	    	
    	}

    	return $return;
    }

    /*
     * string $time HH:MM:SS
     */

    public function changeTime($time) {
        $this->setTime($time);
        $this->save();

        // CHANGE THE TIMESHEET
        $employee_code = $this->getEmployeeCode();
        $e = G_Employee_Finder::findByEmployeeCode($employee_code);
        $af = G_Attendance_Finder::findByEmployeeAndDate($e, $this->getDate());
		if($af){
			if ($this->getType() == self::TYPE_IN) {
				$af->changeTimeIn($time);
			} else if ($this->getType() == self::TYPE_OUT) {
				$af->changeTimeOut($time);
			}
		}
    }

    public function changeType($type, $time) {
        $this->setType($type);
        $this->save();

        // CHANGE THE TIMESHEET
        $employee_code = $this->getEmployeeCode();
        $e = G_Employee_Finder::findByEmployeeCode($employee_code);
        $af = G_Attendance_Finder::findByEmployeeAndDate($e, $this->getDate());
        if($af){
			if ($this->getType() == self::TYPE_IN) {
				$af->changeTimeIn($time);
			} else if ($this->getType() == self::TYPE_OUT) {
				$af->changeTimeOut($time);
			}
		}
    }

	public function deleteLog() {
		$return['is_success'] = false;
		$return['message']    = "Cannot find record";

		if( $this->id > 0 ){
			$total_deleted = G_Fp_Attendance_Logs_Manager::delete($this);
			$return['is_success'] = true;
			$return['message']    = "Total records deleted <b>{$total_deleted}</b>";
		}

		return $return;
	}
}
?>