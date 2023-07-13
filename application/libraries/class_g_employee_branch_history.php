<?php
class G_Employee_Branch_History {
	
	public $id;
	public $employee_id;
	public $company_branch_id;
	public $branch_name;
	public $start_date;
	public $end_date;


	
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
	
	public function setCompanyBranchId($value) {
		$this->company_branch_id = $value;	
	}
	
	public function getCompanyBranchId() {
		return $this->company_branch_id;
	}
	
	public function setBranchName($value) {
		$this->branch_name = $value;	
	}
	
	public function getBranchName() {
		return $this->branch_name;
	}
	
	public function setStartDate($value) {
		$this->start_date = $value;	
	}
	
	public function getStartDate() {
		return $this->start_date;
	}
	
	public function setEndDate($value) {
		$this->end_date = $value;	
	}
	
	public function getEndDate() {
		return $this->end_date;
	}
	

		
	public function save() {
		return G_Employee_Branch_History_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Branch_History_Manager::delete($this);
	}
}

?>