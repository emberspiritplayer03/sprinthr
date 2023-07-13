<?php
class Excluded_Employee_Deduction {
	protected $id;
	protected $employee_id;
	protected $payroll_period_id;
	protected $new_payroll_period_id;
	protected $variable_name;	
	protected $amount;	
	protected $action;
	protected $date_created;

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

	public function setPayrollPeriodId($value) {
		$this->payroll_period_id = $value;
	}
	
	public function getPayrollPeriodId() {
		return $this->payroll_period_id;
	}

	public function setNewPayrollPeriodId($value) {
		$this->new_payroll_period_id = $value;
	}
	
	public function getNewPayrollPeriodId() {
		return $this->new_payroll_period_id;
	}

	public function setVariableName($value) {
		$this->variable_name = $value;
	}
	
	public function getVariableName() {
		return $this->variable_name;
	}

	public function setAmount($value) {
		$this->amount = $value;
	}
	
	public function getAmount() {
		return $this->amount;
	}

	public function setAction($value) {
		$this->action = $value;
	}
	
	public function getAction() {
		return $this->action;
	}

	public function setDateCreated($value) {
		$this->date_created = $value;
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}

}
?>