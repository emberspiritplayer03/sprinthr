<?php
class G_Payslip_Error {
	const ERROR_NO_SALARY = 1;
	const ERROR_NO_ATTENDANCE = 2;
	
	protected $id;
	protected $employee_id;
	protected $message;
	protected $is_fixed = NO;
	protected $date_logged;
	protected $time_logged;
	protected $error_type_id;
	
	function __construct() {
		
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
	
	public function setMessage($value) {
		$this->message = $value;	
	}
	
	public function getMessage() {
		return $this->message;	
	}
	
	public function setDateLogged($value) {
		$this->date_logged = $value;	
	}
	
	public function getDateLogged() {
		return $this->date_logged;	
	}
	
	public function setTimeLogged($value) {
		$this->time_logged = $value;	
	}
	
	public function getTimeLogged() {
		return $this->time_logged;	
	}		
	
	public function setAsFixed() {
		$this->is_fixed = YES;
	}
	
	public function setAsNotFixed() {
		$this->is_fixed = NO;	
	}
	
	public function isFixed() {
		return $this->is_fixed;	
	}
	
	public function setErrorTypeId($value) {
		$this->error_type_id = $value;	
	}
	
	public function getErrorTypeId() {
		return $this->error_type_id;	
	}	
	
	public function addError() {
		return G_Payslip_Error_Manager::add($this);
	}
}
?>