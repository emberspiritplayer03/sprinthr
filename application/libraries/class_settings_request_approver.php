<?php
class Settings_Request_Approver {
	protected $id;
	protected $settings_request_id;
	protected $position_employee_id;	
	protected $type;
	protected $level;
	protected $override_level;
	
	/*const POSITION_ID = 2;
	const EMPLOYEE_ID = 1;*/ 
	
	const POSITION_ID = "Position Id";
	const EMPLOYEE_ID = "Employee Id"; 
	
	const GRANTED      = "Granted";
		
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setSettingsRequestId($value) {
		$this->settings_request_id = $value;
	}
	
	public function getSettingsRequestId() {
		return $this->settings_request_id;
	}
	
	public function setPositionEmployeeId($value) {
		$this->position_employee_id = $value;
	}
	
	public function getPositionEmployeeId() {
		return $this->position_employee_id;
	}
	
	public function setType($value) {
		$this->type = $value;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function setLevel($value) {
		$this->level = $value;
	}
	
	public function getLevel() {
		return $this->level;
	}
	
	public function setOverrideLevel($value) {
		$this->override_level = $value;
	}
	
	public function getOverrideLevel() {
		return $this->override_level;
	}
}
?>