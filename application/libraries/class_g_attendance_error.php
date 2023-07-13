<?php
class G_Attendance_Error {
	const ERROR_INVALID_EMPLOYEE_ID = 1;
	const ERROR_INVALID_TIME = 2;
	const ERROR_INVALID_OT = 3;
	const ERROR_INVALID_DATE = 4;
	const ERROR_NO_OUT = 5;
	const ERROR_NO_IN = 6;
	
	protected $id;
	protected $message;
	protected $is_fixed = NO;
	protected $error_type_id;
	protected $employee_id;
	protected $employee_code;
	protected $date;
	
	function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}
	
	public function setMessage($value) {
		$this->message = $value;	
	}
	
	public function getMessage() {
		return $this->message;	
	}
	
	public function setDate($value) {
		$this->date = $value;	
	}
	
	public function getDate() {
		return $this->date;	
	}
	
	public function setEmployeeCode($value) {
		$this->employee_code = $value;	
	}
	
	public function getEmployeeCode() {
		return $this->employee_code;	
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;	
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
		return G_Attendance_Error_Manager::add($this);
	}
}
?>