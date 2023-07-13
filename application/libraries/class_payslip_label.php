<?php
class Payslip_Label {
	public $label;
	public $value;
	public $variable;
	
	public function __construct($label, $value, $variable) {
		$this->label = $label;
		$this->value = $value;
		$this->variable = $variable;
	}
	
	public function getLabel() {
		return $this->label;	
	}
	
	public function getValue() {
		return $this->value;	
	}
	
	public function getVariable() {
		return $this->variable;	
	}
}
?>