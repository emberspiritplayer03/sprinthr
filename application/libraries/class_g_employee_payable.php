<?php
class G_Employee_Payable {
	
	public $id;
	public $employee_id;
	public $balance_name;
	public $total_amount;



	
	function __construct($id) {
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
	
	public function setBalanceName($value) {
		$this->balance_name = $value;	
	}
	
	public function getBalanceName() {
		return $this->balance_name;
	}
	
	public function setTotalAmount($value) {
		$this->total_amount = $value;	
	}
	
	public function getTotalAmount() {
		return $this->total_amount;
	}
	
	
		
	public function save() {
		return G_Employee_Payable_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Payable_Manager::delete($this);
	}
}

?>