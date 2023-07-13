<?php
class Employee_Annualize_Tax {
	protected $id;
	protected $employee_id;	
	protected $year;
	protected $from_date;
	protected $to_date;
	protected $gross_income_tax;
	protected $less_personal_exemption;
	protected $taxable_income;
	protected $tax_due;
	protected $tax_withheld_payroll;
	protected $tax_refund_payable;
	protected $cutoff_start_date;
	protected $cutoff_end_date;
	protected $date_created;
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = (int) $value;
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

	public function setYear($value) {		
		$this->year = $value;
	}

	public function getYear() {
		return $this->year;
	}

	public function setFromDate($value) {		
		$this->from_date = $value;
	}

	public function getFromDate() {
		return $this->from_date;
	}

	public function setToDate($value) {		
		$this->to_date = $value;
	}

	public function getToDate() {
		return $this->to_date;
	}

	public function setGrossIncome($value) {		
		$this->gross_income = $value;
	}

	public function getGrossIncome() {
		return $this->gross_income;
	}

	public function setLessPersonalExemption($value) {		
		$this->less_personal_exemption = $value;
	}

	public function getLessPersonalExemption() {
		return $this->less_personal_exemption;
	}

	public function setTaxableIncome($value) {		
		$this->taxable_income = $value;
	}

	public function getTaxableIncome() {
		return $this->taxable_income;
	}

	public function setTaxDue($value) {		
		$this->tax_due = $value;
	}

	public function getTaxDue() {
		return $this->tax_due;
	}

	public function setTaxWithHeldPayroll($value) {		
		$this->tax_withheld_payroll = $value;
	}

	public function getTaxWithHeldPayroll() {
		return $this->tax_withheld_payroll;
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

	public function setDateCreated($value) {		
		$this->date_created = $value;
	}

	public function getDateCreated() {
		return $this->date_created;
	}
}
?>