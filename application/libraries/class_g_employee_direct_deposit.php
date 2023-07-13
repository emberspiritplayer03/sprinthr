<?php
class G_Employee_Direct_Deposit {
    const ACCOUNT_TYPE_SAVINGS = 'Savings';
    const ACCOUNT_TYPE_CHECKING = 'Checking';
	
	public $id;
	public $employee_id;
	public $bank_name;
	public $account;
	public $account_type = self::ACCOUNT_TYPE_SAVINGS;

	function __construct($id = '') {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}
	
	public function setBankName($value) {
		$this->bank_name = $value;	
	}
	
	public function getBankName() {
		return $this->bank_name;
	}
	
	public function setAccount($value) {
		$this->account = $value;	
	}
	
	public function getAccount() {
		return $this->account;
	}
	
	public function setAccountType($value) {
		$this->account_type = $value;	
	}
	
	public function getAccountType() {
		return $this->account_type;
	}
		
	public function save() {
		return G_Employee_Direct_Deposit_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Direct_Deposit_Manager::delete($this);
	}
}

?>