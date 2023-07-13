<?php
class Employee_Request_Approver {
	
	protected $id;
	protected $request_type;
	protected $request_type_id;
	protected $position_employee_id;	
	protected $type;
	protected $level;
	protected $override_level;
	protected $message;
	protected $status;
	protected $remarks;
	
	const PENDING    = 'Pending';
	const APPROVE    = 'Approve';
	const DISAPPROVE = 'Disapproved';
	
	const APPROVED 		= 'Approved';
	const DISAPPROVED	= 'Disapproved';
	
	const EMPLOYEE_ID 	= 'Employee Id';
	const POSITION_ID 	= 'Position Id';
	const DEPARTMENT_ID = 'Department Id';
	
	const CURRENT = "Current";
	const GRANTED = "Granted";
	
	
	const OVERRIDE      = 0;
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setRequestType($value) {
		$this->request_type = $value;
	}
	
	public function getRequestType() {
		return $this->request_type;
	}
	
	public function setRequestTypeId($value) {
		$this->request_type_id = $value;
	}
	
	public function getRequestTypeId() {
		return $this->request_type_id;
	}
	
	public function setPositionEmployeeId($value) {
		$this->position_employee_id = $value;
	}
	
	public function getPositionEmployeeId() {
		return $this->position_employee_id;
	}
	
	public function setType($value) {
		$this->type = $value;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function setLevel($value) {
		$this->level = $value;
	}
	
	public function getLevel() {
		return $this->level;
	}
	
	public function setOverrideLevel($value) {
		$this->override_level = $value;
	}
	
	public function getOverrideLevel() {
		return $this->override_level;
	}
	
	public function setMessage($value) {
		$this->message = $value;
	}
	
	public function getMessage() {
		return $this->message;
	}
	
	public function setStatus($value) {
		$this->status = $value;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function setRemarks($value) {
		$this->remarks = $value;
	}
	
	public function getRemarks() {
		return $this->remarks;
	}
}
?>