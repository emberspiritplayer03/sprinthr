<?php
class Request_Approver_Requestor {
	protected $id;
	protected $request_approvers_id;	
	protected $employee_id;
	protected $employee_name;
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = (int) $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
    public function setRequestApproversId($value) {    	
		$this->request_approvers_id = $value;
	}
	
	public function getRequestApproversId() {
		return $this->request_approvers_id;
	}

	public function setEmployeeId($value) {
		$this->employee_id = $value;
	}

	public function getEmployeeId() {
		return $this->employee_id;
	}

	public function setEmployeeName($value) {
		$this->employee_name = $value;
	}

	public function getEmployeeName() {
		return $this->employee_name;
	}
}
?>