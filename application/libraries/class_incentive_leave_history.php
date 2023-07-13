<?php
class Incentive_Leave_History {
	public $id;
	public $month_number;
	public $year;
	public $total_given;	
	public $date_process;	
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setMonthNumber($value) {
		$this->month_number = $value;
	}
	
	public function getMonthNumber() {
		return $this->month_number;
	}
	
	public function setYear($value) {
		$this->year = $value;
	}
	
	public function getYear() {
		return $this->year;
	}
	
	public function setTotalGiven($value) {
		$this->total_given = $value;
	}
	
	public function getTotalGiven() {
		return $this->total_given;
	}
	
	public function setDateProcess($value) {
		$this->date_process = $value;
	}
	
	public function getDateProcess() {
		return $this->date_process;
	}
}
?>