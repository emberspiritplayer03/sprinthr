<?php
class Employee_Overtime_Rate{
	public $id;
	public $employee_id;
	public $ot_rate;

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

	public function setOtRate($value) {
		$this->ot_rate = $value;
	}
	
	public function getOtRate() {
		return $this->ot_rate;
	}
}
?>