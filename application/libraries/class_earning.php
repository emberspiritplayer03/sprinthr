<?php
class Earning {
	const TAXABLE = 1;
	const NON_TAXABLE = 2;
	const EARNING_TYPE_STANDARD = 1;
	const EARNING_TYPE_ADJUSTMENT = 2;
	const EARNING_TYPE_ALLOWANCE = 3;
	const EARNING_TYPE_BONUS = 4;
	const EARNING_TYPE_ADVANCE = 5;
	
	protected $variable;
	protected $label;
	protected $amount;
	protected $earning_type;
	protected $tax_type;
	
	public function __construct($label, $amount, $tax_type = self::TAXABLE, $earning_type = self::EARNING_TYPE_STANDARD) {
		$this->label = $label;
		$this->variable = $label;
		$this->amount = $amount;
		$this->earning_type = $earning_type;
		$this->tax_type = $tax_type;
	}

    public function isTaxable() {
        if ($this->tax_type == self::TAXABLE) {
            return true;
        } else {
            return false;
        }
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
	
	public function getEarningType() {
		return $this->earning_type;	
	}
	
	public function getTaxType() {
		return $this->tax_type;	
	}
}
?>