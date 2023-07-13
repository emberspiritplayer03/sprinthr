<?php
/*
	Usage:
		$s = new Philhealth;
		$s->setCompanyShare(337);
		$s->setEmployeeShare(200);
		return $s;
*/
class Philhealth {
	protected $company_share;
	protected $employee_share;
	
	protected $salary_base;
	protected $salary_bracket;
	protected $from_salary;
	protected $to_salary;
	protected $monthly_contribution;	
	
	public function __construct() {
	
	}
	
	public function setCompanyShare($value) {
		$this->company_share = $value;
	}
	
	public function getCompanyShare() {
		return $this->company_share;
	}
	
	public function setEmployeeShare($value) {
		$this->employee_share = $value;
	}
	
	public function getEmployeeShare() {
		return $this->employee_share;
	}
	
	public function setSalaryBase($value) {
		$this->salary_base = $value;
	}
	
	public function getSalaryBase() {
		return $this->salary_base;
	}
	
	public function setSalaryBracket($value) {
		$this->salary_bracket = $value;
	}
	
	public function getSalaryBracket() {
		return $this->salary_bracket;
	}
	
	public function setFromSalary($value) {
		$this->from_salary = $value;
	}
	
	public function getFromSalary() {
		return $this->from_salary;
	}
	
	public function setToSalary($value) {
		$this->to_salary = $value;
	}
	
	public function getToSalary() {
		return $this->to_salary;
	}
	
	public function setMonthlyContribution($value) {
		$this->monthly_contribution = $value;
	}
	
	public function getMonthlyContribution() {
		return $this->monthly_contribution;
	}
}
?>