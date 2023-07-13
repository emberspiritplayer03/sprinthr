<?php
class Employee_Deductions {
	public $id;
	public $company_structure_id;
	public $employee_id;
	public $department_section_id;
	public $employment_status_id;
	public $title;
	public $amount;
	public $payroll_period_id;
	public $apply_to_all_employee;	
	public $status;
	public $is_moved_deduction;
	public $taxable;
	public $frequency_id;
	
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}

	public function setDepartmentSectionId($value) {
		$this->department_section_id = $value;
	}
	
	public function getDepartmentSectionId() {
		return $this->department_section_id;
	}

	public function setEmploymentStatusId($value) {
		$this->employment_status_id = $value;
	}
	
	public function getEmploymentStatusId() {
		return $this->employment_status_id;
	}
	
	public function setTitle($value) {
		$this->title = $value;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setAmount($value) {
		$this->amount = $value;
	}
	
	public function getAmount() {
		return $this->amount;
	}
	
	public function setPayrollPeriodId($value) {
		$this->payroll_period_id = $value;
	}
	
	public function getPayrollPeriodId() {
		return $this->payroll_period_id;
	}
	
	public function setApplyToAllEmployee($value) {
		$this->apply_to_all_employee = $value;
	}
	
	public function getApplyToAllEmployee() {
		return $this->apply_to_all_employee;
	}
	
	public function setStatus($value) {
		$this->status = $value;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function setTaxable($value) {
		$this->taxable = $value;
	}
	
	public function getTaxable() {
		return $this->taxable;
	}

	public function setFrequencyId($value) {
		$this->frequency_id = $value;
	}
	
	public function getFrequencyId() {
		return $this->frequency_id;
	}

	public function setIsMovedDeduction($value) {
		$this->is_moved_deduction = $value;
	}
	
	public function getIsMovedDeduction() {
		return $this->is_moved_deduction;
	}
}
?>