<?php
class G_Leave_Error {
	
	protected $id;
	protected $employee_id;
	protected $employee_code;
	protected $employee_name;
	protected $date_applied;
	protected $date_start;
	protected $date_end;
	protected $message;
	protected $is_fixed = NO;
	protected $error_type_id;
	
	const EMPLOYEE_DOES_NOT_EXIST 	= 1;
	
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
	
	public function setEmployeeCode($value) {
		$this->employee_code = $value;	
	}
	
	public function getEmployeeCode() {
		return $this->employee_code;	
	}
	
	public function setEmployeeName($value) {
		$this->employee_name = $value;	
	}
	
	public function getEmployeeName() {
		return $this->employee_name;	
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
	
	public function setMessage($value) {
		$this->message = $value;	
	}
	
	public function getMessage() {
		return $this->message;	
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
	
	public function getErrorTypeString() {
		if ($this->getErrorTypeId() == self::ERROR_NO_IN) {
			return 'NO TIME IN';
		} else if ($this->getErrorTypeId() == self::ERROR_NO_OUT) {
			return 'NO TIME OUT';
		}
	}
	
	public function addError() {
		return G_Leave_Error_Manager::add($this);
	}
}
?>