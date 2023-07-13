<?php
/*
Usage:
		$tax_table = Tax_Table_Factory::get(Tax_Table::MONTHLY);
		$tax = new Tax_Calculator;
		$tax->setTaxTable($tax_table);
		$tax->setTaxableIncome(4500);
		$tax->setMaritalStatus('single');
		$tax->setIsZeroExemption(true);
		$tax->setNumberOfDependent(0);
		echo $tax->compute();
*/
		
class Tax_Calculator {
	protected $tax_table; // Instance of class Tax_Table
	protected $taxable_income;
	protected $marital_status; // single, married
	protected $number_of_dependent;
	protected $zero_exemption = false;
	protected $pe_max_dependent = 4;
	protected $basic_pe = 50000;
	protected $add_pe   = 25000;

	public function __construct() {
		
	}
	
	public function setTaxTable($value) {
		$this->tax_table = $value;	
	}
	
	public function getTaxTable() {
		return $this->tax_table;	
	}
	
	public function setTaxableIncome($value) {
		$this->taxable_income = $value;	
	}
	
	public function getTaxableIncome() {
		return $this->taxable_income;	
	}
	
	/*
		$value - 'single', 'married
	*/
	public function setMaritalStatus($value) {
		$this->marital_status = $value;	
	}
	
	public function getMaritalStatus() {
		return $this->marital_status;
	}
	
	public function setNumberOfDependent($value) {
		$this->number_of_dependent = $value;	
	}
	
	public function setIsZeroExemption() {
		$this->zero_exemption = true;
	}
	
	public function isZeroExemption() {
		return $this->zero_exemption;
	}
	
	public function getNumberOfDependent() {
		return $this->number_of_dependent;
	}
	
	public function compute() {
		$tax = 0;
		if ($this->isZeroExemption()) {
			$salary_ranges = $this->getSalaryRangesByZeroExemption();
		} else {
			$salary_ranges = $this->getSalaryRangesByDependent($this->number_of_dependent);	
		}
		
		$arranged_salary_ranges = $this->sortBySalaryStart($salary_ranges);				
		foreach ($arranged_salary_ranges as $key => $salary_range) {
			$salary_start = (float) $key;
			if ((float) $this->taxable_income >= $salary_start) {				
				$tax = ($this->taxable_income - $salary_start) * ($salary_range->getPercentage()/100) + $salary_range->getExemptionAmount();
			}
		}
		return $tax;
	}

	/*
	 * For 2018 Tax Computation
	*/
	public function computeHB563() {
		$tax = 0;
		$tax_table_range = $this->tax_table;
		$annual_tax_income = $this->taxable_income;

		foreach($tax_table_range as $tkey => $tax_range) {
			if($annual_tax_income >= $tax_range['from'] && $annual_tax_income <= $tax_range['to']) {
				$_tax_range = $tax_range;
			}
		}

		$tax = ($annual_tax_income - $_tax_range['from']) * ($_tax_range['rate']/100) + $_tax_range['fixed'];

		return $tax;
	}	


	public function getPersonalExemptionAmount() {
		$pe = 0;
		if( $this->number_of_dependent > 0 ){
			$number_of_dependent = $this->number_of_dependent;
			if( $this->number_of_dependent > $this->pe_max_dependent ){
				$number_of_dependent = $this->pe_max_dependent;
			}
			$pe = $this->basic_pe + ($number_of_dependent * $this->add_pe);
		}else{
			$pe = $this->basic_pe;
		}

		return $pe;
	}
	
	private function getSalaryRangesByZeroExemption() {
		$table_items = $this->tax_table->getTableItems();
		foreach ($table_items as $table_item) {
			if ($table_item->isZeroExemption()) {
				return $table_item->getSalaryRanges();
			}
		}
	}	
	
	private function getSalaryRangesByDependent($number_of_dependent) {
		$table_items = $this->tax_table->getTableItems();
		foreach ($table_items as $table_item) {
			if ($table_item->getNumberOfDependent() == $number_of_dependent && !$table_item->isZeroExemption()) {
				return $table_item->getSalaryRanges();
			}
		}
	}
	
	private function sortBySalaryStart($salary_ranges) {
		$ranges = array();
		foreach ($salary_ranges as $salary_range) {
			$ranges[$salary_range->getSalaryStart()] = $salary_range;
		}
		ksort($ranges);
		return $ranges;
	}
}	
?>