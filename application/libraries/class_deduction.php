<?php
class Deduction {
	const DEDUCTION_TYPE_STANDARD = 1;
	const DEDUCTION_TYPE_ADVANCE = 2;
	const DEDUCTION_TYPE_LOAN = 3;
	const DEDUCTION_TYPE_TAX = 4;
	
	protected $label;
	protected $variable;
	protected $amount;
	protected $deduction_type;
	
	//protected $is_default;
	
	public function __construct($label, $amount, $deduction_type = self::DEDUCTION_TYPE_STANDARD) {
		$this->label = $label;
		$this->variable = $label;
		$this->amount = $amount;
		$this->deduction_type = $deduction_type;
		//$this->is_default = $is_default;
	}
	
	public function getDeductionType() {
		return $this->deduction_type;
	}
	
	public function setVariable($value) {
		$this->variable = $value;
	}
	
	public function getVariable() {
		return $this->variable;
	}
	
	public function setLabel($value) {
		$this->label = $value;	
	}
	
	public function getLabel() {
		return $this->label;	
	}
	
	public function setAmount($value) {
		$this->amount = $value;	
	}
	
	public function getAmount() {
		return $this->amount;	
	}
	
	//public function isDefault() {
	//	return $this->is_default;
	//}
}
?>