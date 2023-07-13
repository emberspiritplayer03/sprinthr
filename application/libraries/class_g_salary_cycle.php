<?php
class G_Salary_Cycle {
	const TYPE_WEEKLY = 1;
	const TYPE_SEMI_MONTHLY = 2;
	const TYPE_MONTHLY = 3;
	
	protected $type;
	protected $cutoffs;
	protected $payout_days;
	
	public function __construct($cutoffs, $type = self::TYPE_SEMI_MONTHLY) {
		$this->cutoffs = (array) $cutoffs;
		$this->type = $type;
	}
	
	public function getCutOffs() {
		return $this->cutoffs;
	}
	
	public function setCutOffs($value) {
		$this->cutoffs = $value;
	}
	
	public function getPayoutDays() {
		return $this->payout_days;
	}
	
	public function setPayoutDays($value) {
		$this->payout_days = $value;
	}	
	
	public function getType() {
		return $this->type;
	}
	
	public function setType($value) {
		$this->type = $value;
	}
}
?>