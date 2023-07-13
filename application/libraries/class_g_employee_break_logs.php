<?php
class G_Employee_Break_Logs extends Employee_Break_Logs {

	const TYPE_BOUT = 'bout';
	const TYPE_BIN = 'bin';
	const TYPE_BOT_OUT = 'otbout';
	const TYPE_BOT_IN = 'otbin';

	const TYPE_B1_OUT = 'b1out';
	const TYPE_B1_IN = 'b1in';
	const TYPE_B2_OUT = 'b2out';
	const TYPE_B2_IN = 'b2in';
	const TYPE_B3_OUT = 'b3out';
	const TYPE_B3_IN = 'b3in';

	const TYPE_OT_B1_OUT = 'otb1out';
	const TYPE_OT_B1_IN = 'otb1in';
	const TYPE_OT_B2_OUT = 'otb2out';
	const TYPE_OT_B2_IN = 'otb2in';

	public $id;
	public $employee_id;
	public $employee_code;
	public $employee_name;
	public $date;
	public $time;
	public $type;
	public $remarks;
	public $sync;
	public $is_transferred;
	public $employee_device_id;

	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;	
	}
	
	public function setEmployeeCode($value) {
		$this->employee_code = $value;	
	}
	
	public function getEmployeeCode() {
		return $this->employee_code;	
	}
	
	public function setEmployeeName($value) {
		$this->employee_name = $value;	
	}
	
	public function getEmployeeName() {
		return $this->employee_name;	
	}
	
	public function setDate($value) {
		$this->date = $value;	
	}
	
	public function getDate() {
		return $this->date;	
	}
	
	public function setTime($value) {
		$this->time = $value;	
	}
	
	public function getTime() {
		return $this->time;	
	}
	
	public function setType($value) {
		$this->type = $value;	
	}
	
	public function getType() {
		return $this->type;	
	}
	
	public function setRemarks($value) {
		$this->remarks = $value;	
	}
	
	public function getRemarks() {
		return $this->remarks;	
	}
	
	public function setSync($value) {
		$this->sync = $value;	
	}
	
	public function getSync() {
		return $this->sync;	
	}
	
	public function setIsTransferred($value) {
		$this->is_transferred = $value;	
	}
	
	public function getIsTransferred() {
		return $this->is_transferred;	
	}
	
	public function setEmployeeDeviceId($value) {
		$this->employee_device_id = $value;	
	}
	
	public function getEmployeeDeviceId() {
		return $this->employee_device_id;	
	}
	
	public function save() {
		return G_Employee_Break_Logs_Manager::save($this);
	}

    public function changeTime($time) {
        $this->setTime($time);
        $this->save();
    }

    public function changeType($type, $time) {
        $this->setType($type);
        $this->save();
    }

	public function deleteLog() {
		$return['is_success'] = false;
		$return['message']    = "Cannot find record";

		if( $this->id > 0 ){
			$total_deleted = G_Employee_Break_Logs_Manager::delete($this);
			$return['is_success'] = true;
			$return['message']    = "Total records deleted <b>{$total_deleted}</b>";
		}

		return $return;
	}
	
}
?>