<?php
class Employee_Benefits_Main {
	protected $id;
	protected $company_structure_id;
	protected $employee_department_id;
	protected $benefit_id;
	protected $applied_to;

	const EMPLOYEE     = 'Employee';
	const DEPARTMENT   = 'Department';
	const EMPLOYMENT_STATUS = 'Employment Status';
	const ALL_EMPLOYEE = 'All Employees';

	const YES = 'Yes';	
	const NO  = 'No';
	
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

	public function setEmployeeDepartmentId($value) {
		$this->employee_department_id = $value;
	}

	public function getEmployeeDepartmentId() {
		return $this->employee_department_id;
	}

	public function setBenefitId($value) {
		$this->benefit_id = $value;
	}

	public function getBenefitId() {
		return $this->benefit_id;
	}

	public function setAppliedTo($value) {
		$this->applied_to = $value;
		return $this;
	}

	public function getAppliedTo() {
		return $this->applied_to;
	} 
}
?>