<?php
/*
	Usage:
		$s = new Pagibig;
		$s->setCompanyShare(337);
		$s->setEmployeeShare(200);
		return $s;
*/
class Pagibig {
	protected $company_share;
	protected $employee_share;
	
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
}
?>