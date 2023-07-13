<?php
/*
	Usage:
		$s = new SSS;
		$s->setCompanyShare(337);
		$s->setEmployeeShare(200);
		$s->setCompanyEc(50);
		$s->setSalary(20000);
		return $s;
*/
class SSS {
	protected $company_share;
	protected $employee_share;
	protected $company_ec;
	protected $salary;
	
	protected $monthly_salary_credit;
	protected $from_salary;
	protected $to_salary;

	protected $provident_ee;
	protected $provident_er;
	
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
	
	public function setCompanyEc($value) {
		$this->company_ec = $value;
	}
	
	public function getCompanyEc() {
		return $this->company_ec;
	}
	
	public function setSalary($value) {
		$this->salary = $value;
	}
	
	public function getSalary() {
		return $this->salary;
	}
	
	public function setMonthlySalaryCredit($value) {
		$this->monthly_salary_credit = $value;
	}
	
	public function getMonthlySalaryCredit() {
		return $this->monthly_salary_credit;
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

	public function setProvidentEe($value) {
		$this->provident_ee = $value;
	}

	public function setProvidentEr($value) {
		$this->provident_er = $value;
	}

	public function getProvidentEe($value) {
		return $this->provident_ee;
	}

	public function getProvidentEr($value) {
		return $this->provident_er;
	}

}
?>