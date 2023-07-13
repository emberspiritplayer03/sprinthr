<?php
class Settings_Leave_General {
	
	protected $id;
	protected $convert_leave_criteria;
	protected $leave_id;
	
	function __construct() {}
	//id
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;
	}
	//convert_leave_criteria
	public function setConvertLeaveCriteria($value) {
		$this->convert_leave_criteria = $value;	
	}
	
	public function getConvertLeaveCriteria() {
		return $this->convert_leave_criteria;
	}	
	//leave_id
	public function setLeaveId($value) {
		$this->leave_id = $value;	
	}
	
	public function getLeaveId() {
		return $this->leave_id;
	}	

}

?>
