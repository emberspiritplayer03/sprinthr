<?php
/*
	$tax = new Tax_Table_Item;
	$tax->setName('Z');
	$tax->setNumberOfDependent(1);

	$range = new Tax_Salary_Range;
	$range->setSalaryRange(10000);
	$range->setExemptionAmount(10);
	$range->setPercentage(5);	
			
	$tax->addSalaryRange($range);
*/
class Tax_Salary_Range {
	protected $salary_start;
	protected $salary_end;
	protected $exemption_amount;
	protected $percentage;
	
	public function __construct() {
		
	}
	
	public function setSalaryRange($salary_start, $salary_end = '') {
		$this->salary_start = $salary_start;
		$this->salary_end = $salary_end;	
	}
	
	public function setSalaryStart($value) {
		$this->salary_start = $value;
	}
	
	public function getSalaryStart() {
		return $this->salary_start;
	}

	public function setSalaryEnd($value) {
		$this->salary_end = $value;
	}
	
	public function getSalaryEnd() {
		return $this->salary_end;
	}
	
	public function setExemptionAmount($value) {
		$this->exemption_amount = $value;	
	}
	
	public function getExemptionAmount($value) {
		return $this->exemption_amount;	
	}	

	public function setPercentage($value) {
		$this->percentage = $value;	
	}
	
	public function getPercentage($value) {
		return $this->percentage;	
	}
}
?>