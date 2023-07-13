<?php
class Payroll_Variables {
	protected $id;
	protected $number_of_days;	
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
    public function setNumberOfDays($value) {    	
		$this->number_of_days = $value;
	}
	
	public function getNumberOfDays() {
		return $this->number_of_days;
	}
}
?>