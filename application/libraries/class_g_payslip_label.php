<?php
class G_Payslip_Label {
	protected $label;
	protected $value;
	protected $variable;
	
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