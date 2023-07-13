<?php
class Employee_Fixed_Contribution {
	protected $id;
	protected $employee_id;	
	protected $type;
	protected $ee_amount;
	protected $er_amount;
	protected $is_activated;	
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = (int) $value;
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

	public function setType($value) {
		$this->type = $value;
	}

	public function getType() {
		return $this->type;
	}

	public function setEEAmount($value) {
		$this->ee_amount = $value;
	}

	public function getEEAmount() {
		return $this->ee_amount;
	}

	public function setERAmount($value) {
		$this->er_amount = $value;
	}

	public function getERAmount() {
		return $this->er_amount;
	}

	public function setIsActivated($value) {
		$this->is_activated = $value;
	}

	public function getIsActivated() {
		return $this->is_activated;
	}
}
?>