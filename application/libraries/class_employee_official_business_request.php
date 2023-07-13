<?php
class Employee_Official_Business_Request {
	public $id;
	public $company_structure_id = 1;
	public $employee_id;
	public $date_applied;	
	public $date_start;
	public $date_end;
	public $comments;	
	
	const PENDING 		= 'Pending';
	const APPROVED 		= 'Approved';
	const DISAPPROVED	= 'Disapproved';
	
	const YES = 'Yes';
	const NO  = 'No';
		
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
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}
	
	public function setDateApplied($value) {
		$this->date_applied = $value;
	}
	
	public function getDateApplied() {
		return $this->date_applied;
	}
	
	public function setDateStart($value) {
		$this->date_start = $value;
	}
	
	public function getDateStart() {
		return $this->date_start;
	}
	
	public function setDateEnd($value) {
		$this->date_end = $value;
	}
	
	public function getDateEnd() {
		return $this->date_end;
	}
	
	public function setComments($value) {
		$this->comments = $value;
	}
	
	public function getComments() {
		return $this->comments;
	}
}
?>