<?php
class G_Tax_Table{
	public $id;
	public $company_structure_id;
	public $pay_frequency;
	public $status;
	public $d0;
	public $d1;
	public $d2;
	public $d3;
	public $d4;
	public $d5;
	public $d6;
	public $d7;
	public $d8;
	
	const MONTHLY = 'monthly';
	const SEMI_MONTHLY = 'semi_monthly';
	
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
	
	public function setStatus($value) {
		$this->status = $value;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function setPayFrequency($value) {
		$this->pay_frequency = $value;
	}
	
	public function getPayFrequency() {
		return $this->pay_frequency;
	}
	
	public function setD0($value) {
		$this->d0 = $value;
	}
	
	public function getD0() {
		return $this->d0;
	}
	
	public function setD1($value) {
		$this->d1 = $value;
	}
	
	public function getD1() {
		return $this->d1;
	}
	
	public function setD2($value) {
		$this->d2 = $value;
	}
	
	public function getD2() {
		return $this->d2;
	}
	
	public function setD3($value) {
		$this->d3 = $value;
	}
	
	public function getD3() {
		return $this->d3;
	}
	
	public function setD4($value) {
		$this->d4 = $value;
	}
	
	public function getD4() {
		return $this->d4;
	}
	
	public function setD5($value) {
		$this->d5 = $value;
	}
	
	public function getD5() {
		return $this->d5;
	}
	
	public function setD6($value) {
		$this->d6 = $value;
	}
	
	public function getD6() {
		return $this->d6;
	}
	
	public function setD7($value) {
		$this->d7 = $value;
	}
	
	public function getD7() {
		return $this->d7;
	}
	
	public function setD8($value) {
		$this->d8 = $value;
	}
	
	public function getD8() {
		return $this->d8;
	}
	
	public function save() {		
		return G_Tax_Table_Manager::save($this);
	}
	
	public function delete() {
		return G_Tax_Table_Manager::delete($this);
	}
}
?>