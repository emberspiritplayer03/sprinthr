<?php
class Sprint_Variables {
	protected $id;
	protected $variable_name;	
	protected $variable_value;
	protected $custom_value_a;
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = (int) $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
    public function setVariableName($value) {    	
		$this->variable_name = trim(strtolower($value));
		return $this;
	}
	
	public function getVariableName() {
		return $this->variable_name;
	}

	public function setValue($value) {		
		$this->variable_value = $value;
	}

	public function getValue() {
		return $this->variable_value;
	}

	public function setCustomValueA($value) {		
		$this->custom_value_a = $value;
	}

	public function getCustomValueA() {
		return $this->custom_value_a;
	}
}
?>