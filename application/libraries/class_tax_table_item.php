<?php
class Tax_Table_Item {
	protected $item_name;
	protected $number_of_dependent;
	protected $is_zero_exemption = false;
	protected $salary_ranges = array();	
	
	public function __construct() {
		
	}
	
	public function setName($value) {
		$this->item_name = $value;	
	}
	
	public function getName() {
		return $this->item_name;	
	}
	
	public function setIsZeroExemption($value) {
		$this->is_zero_exemption = $value;	
	}
	
	public function isZeroExemption() {
		return $this->is_zero_exemption;	
	}
	
	public function setNumberOfDependent($value) {
		$this->number_of_dependent = $value;	
	}
	
	public function getNumberOfDependent() {
		return $this->number_of_dependent;	
	}
	
	/*
		$value - Instance of class Tax_Salary_Range
	*/
	public function addSalaryRange($value) {
		$this->salary_ranges[] = $value;
	}
	
	public function getSalaryRanges() {
		return $this->salary_ranges;	
	}
}
?>