<?php
class Employee_Earnings {
	protected $id;
	protected $company_structure_id;
	protected $object_id;
	protected $applied_to;
	protected $title;
	protected $earning_type;
	protected $percentage;
	protected $percentage_multiplier;
	protected $amount;
	protected $payroll_period_id;
	protected $description;
	protected $object_description;
	protected $status;
	protected $is_taxable;
	protected $frequency_id;

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
	
	public function setObjectId($value) {
		$this->object_id = $value;
	}
	
	public function getObjectId() {
		return $this->object_id;
	}

	public function setAppliedTo($value) {
		$this->applied_to = $value;
	}
	
	public function getAppliedTo() {
		return $this->applied_to;
	}

	public function setTitle($value) {
		$this->title = $value;
	}
	
	public function getTitle() {
		return $this->title;
	}

	public function setEarningType($value) {
		$this->earning_type = $value;
		return $this;
	}
	
	public function getEarningType() {
		return $this->earning_type;
	}

	public function setPercentage($value) {
		$this->percentage = $value;
	}
	
	public function getPercentage() {
		return $this->percentage;
	}

	public function setPercentageMultiplier($value) {
		$this->percentage_multiplier = $value;
	}
	
	public function getPercentageMultiplier() {
		return $this->percentage_multiplier;
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

	public function setDescription($value) {
		$this->description = $value;
	}
	
	public function getDescription() {
		return $this->description;
	}

	public function setObjectDescription($value){
		$this->object_description = $value;
	}
	
	public function getObjectDescription(){
		return $this->object_description;
	}

	public function setStatus($value) {
		$this->status = $value;
	}
	
	public function getStatus() {
		return $this->status;
	}

	public function setIsTaxable($value) {
		$this->is_taxable = $value;
	}
	
	public function getIsTaxable() {
		return $this->is_taxable;
	}

		public function setFrequencyId($value) {
		$this->frequency_id = $value;
	}
	
	public function getFrequencyId() {
		return $this->frequency_id;
	}

}
?>