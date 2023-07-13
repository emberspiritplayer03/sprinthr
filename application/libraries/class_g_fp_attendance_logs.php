<?php
class G_Fp_Attendance_Logs {	
	protected $id;
	protected $employee_code;
	protected $user_id;
	protected $date;
	protected $logs = array();

	public function __construct( $value = '' ) {
		if( $value != '' ){
			$this->date = date("Y-m-d",strtotime($value));
		}
	}

	public function setId( $value = 0 ){
		$this->id = $value;
		return $this;
	}

	public function getId(){		
		return $this->id;
	}

	public function setEmployeeCode( $value = '' ) {
		$this->employee_code = $value;
		return $this;
	}

	public function setUserId( $value = '' ){
		$this->user_id = $value;
		return $this;
	}

	public function setDate( $value = '' ){
		$s_date = date("Y-m-d",strtotime($value));
		$this->date = $s_date;
		return $this;
	}

	public function isIdExists( $id = 0 ) {
		$is_exists = false;
		if( $this->id == "" && $id > 0 ){
			$this->id = $id;
		}

		if( $this->id > 0 ){ 
			$is_exists = G_Fp_Attendance_Logs_Helper::sqlIsIdExists($this->id);
		}

		return $is_exists;
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

	public function getEmployeeLogs() {
		$data = array();
		if( $this->employee_code != '' && $this->date != '' ){			
			$fields = array("id","employee_code","date","time","LOWER(type)AS type");
			$data   = G_Fp_Attendance_Logs_Helper::sqlGetEmployeeLogsByEmployeeCodeAndDate( $this->employee_code, $this->date, $fields );			
			$this->logs = $data;
		}

		return $this;
	}

	public function groupData() {
		if( !empty($this->logs) ){			
			$logs     = $this->logs;
			$new_logs = array();
			foreach( $logs as $log){
				$new_logs[$log['date']][$log['type']][] = array("id" => $log['id'], "date" => $log['date'], "time" => $log['time']);
			}

			$this->logs = $new_logs;
		}

		return $this;
	}

	public function getProperty( $property = '' ){
		if( property_exists($this, $property) ){
			return $this->{$property};
		}else{
			return false;
		}
		
	}
}
?>