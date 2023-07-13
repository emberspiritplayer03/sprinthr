<?php
class Yearly_Bonus_Release_Date {
	public $id;
	public $employee_id;
	public $amount;
	public $taxable_amount;
	public $tax;
	public $total_bonus_amount;
	public $year_released;
	public $month_start;
	public $month_end;
	public $cutoff_start_date;
	public $cutoff_end_date;
	public $percentage;
	public $deducted_amount;
	public $created;	
	public $modified;

	public $frequency;

	public $payroll_start_date;

	
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

	public function setAmount($value) {
		$this->amount = $value;
	}
	
	public function getAmount() {
		return $this->amount;
	}

	public function setTaxableAmount($value) {
		$this->taxable_amount = $value;
	}
	
	public function getTaxableAmount() {
		return $this->taxable_amount;
	}

	public function setTax($value) {
		$this->tax = $value;
	}
	
	public function getTax() {
		return $this->tax;
	}

	public function setTotalBonusAmount($value) {
		$this->total_bonus_amount = $value;
	}
	
	public function getTotalBonusAmount() {
		return $this->total_bonus_amount;
	}
	
	public function setYearReleased($value) {
		$this->year_released = $value;
	}
	
	public function getYearReleased() {
		return $this->year_released;
	}

	public function setMonthStart($value) {
		$this->month_start = $value;
	}
	
	public function getMonthStart() {
		return $this->month_start;
	}

	public function setMonthEnd($value) {
		$this->month_end = $value;
	}
	
	public function getMonthEnd() {
		return $this->month_end;
	}
	
	public function setCutoffStartDate($value) {
		$this->cutoff_start_date = $value;
	}
	
	public function getCutoffStartDate() {
		return $this->cutoff_start_date;
	}

	public function setCutoffEndDate($value) {
		$this->cutoff_end_date = $value;
	}
	
	public function getCutoffEndDate() {
		return $this->cutoff_end_date;
	}

	public function setPercentage($value) {
		$this->percentage = $value;
	}
	
	public function getPercentage() {
		return $this->percentage;
	}

	public function setDeductedAmount($value) {
		$this->deducted_amount = $value;
	}
	
	public function getDeductedAmount() {
		return $this->deducted_amount;
	}
	
	public function setCreated($value) {
		$this->created = $value;
	}
	
	public function getCreated() {
		return $this->created;
	}
	
	public function setModified($value) {
		$this->modified = $value;
	}
	
	public function getModified() {
		return $this->modified;
	}


	public function setFrequency($value){

		$this->frequency = $value;

	}

	public function getFrequency(){
		return $this->frequency;
	}



	public function setPayrollStartDate($value) {
		$this->payroll_start_date = $value;
	}
	
	public function getPayrollStartDate() {
		return $this->payroll_start_date;
	}

}
?>