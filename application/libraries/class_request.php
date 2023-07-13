<?php
class Request {
	protected $id;
	protected $requestor_employee_id;	
	protected $request_id;
	protected $request_type;
	protected $approver_employee_id;
	protected $approver_name;
	protected $status;
	protected $is_lock;
	protected $remarks;
	protected $action_date;
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = (int) $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
    public function setRequestorEmployeeId($value) {    	
		$this->requestor_employee_id = $value;
	}
	
	public function getRequestorEmployeeId() {
		return $this->requestor_employee_id;
	}

	public function setRequestId($value) {
		$this->request_id = $value;
	}

	public function getRequestId() {
		return $this->request_id;
	}

	public function setRequestType($value) {
		$this->request_type = $value;
	}

	public function getRequestType() {
		return $this->request_type;
	}

	public function setApproverEmployeeId($value) {
		$this->approver_employee_id = $value;
	}

	public function getApproverEmployeeId() {
		return $this->approver_employee_id;
	}

	public function setApproverName($value) {
		$this->approver_name = $value;
	}

	public function getApproverName() {
		return $this->approver_name;
	}

	public function setStatus($value) {
		$this->status = $value;
	}

	public function getStatus() {
		return $this->status;
	}

	public function setIsLock($value) {
		$this->is_lock = $value;
	}

	public function getIsLock() {
		return $this->is_lock;
	}

	public function setRemarks($value) {
		$this->remarks = $value;
	}

	public function getRemarks() {
		return $this->remarks;
	}

	public function setActionDate($value) {
		$date_format = date("Y-m-d", strtotime($value));
		$this->action_date = $date_format;
	}

	public function getActionDate() {
		return $this->action_date;
	}
}
?>