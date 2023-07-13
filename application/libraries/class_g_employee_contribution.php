<?php
class G_Employee_Contribution {

	const YES = "Yes";
	const NO = "No";
	
	public $id;
	public $employee_id;
	public $sss_ee;
	public $pagibig_ee;
	public $philhealth_ee;
	public $sss_er;
	public $pagibig_er;
	public $philhealth_er;
	public $to_deduct;
			
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
	
	public function setSssEe($value) {
		$this->sss_ee = $value;	
	}
	
	public function getSssEe() {
		return $this->sss_ee;
	}
	
	public function setPagibigEe($value) {
		$this->pagibig_ee = $value;	
	}
	
	public function getPagibigEe() {
		return $this->pagibig_ee;
	}
	
	public function setPhilhealthEe($value) {
		$this->philhealth_ee = $value;	
	}
	
	public function getPhilhealthEe() {
		return $this->philhealth_ee;
	}
	
	
	public function setSssEr($value) {
		$this->sss_er = $value;	
	}
	
	public function getSssEr() {
		return $this->sss_er;
	}
	
	public function setPagibigEr($value) {
		$this->pagibig_er = $value;	
	}
	
	public function getPagibigEr() {
		return $this->pagibig_er;
	}
	
	public function setPhilhealthEr($value) {
		$this->philhealth_er = $value;	
	}
	
	public function getPhilhealthEr() {
		return $this->philhealth_er;
	}

	public function setToDeduct($value) {
		$this->to_deduct = $value;	
	}
	
	public function getToDeduct() {
		return $this->to_deduct;
	}
	
	public function updateEmployeeContribution() {
		$e = G_Employee_Helper::getEmployeeCurrentSalary();
		if(!empty($e)) {
			foreach($e as $key => $employees) {
				$employee = G_Employee_Finder::findById($employees['id']);
				if($employee) {
					$employee->addContribution($employees['basic_salary']);
				}
			}
		}
	}
	
	public function save() {
		return G_Employee_Contribution_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Contribution_Manager::delete($this);
	}
}

?>