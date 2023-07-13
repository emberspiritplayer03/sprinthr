<?php
class G_Overtime_Error {
    // ACTIVE CONSTANTS
    const ACTUAL_OUT_LESS_THAN_ACTUAL_IN = 'actual_out_less_than_actual_in';
    const OT_OUT_GREATER_THAN_ACTUAL_OUT = 'ot_out_greater_than_actual_out';
    const ERROR_NO_ACTUAL_TIME = 'no_actual_time';

    const ERROR_FIXED_NO = 'No';
    const ERROR_FIXED_YES = 'Yes';

    // INACTIVE CONSTANTS
	const INVALID_SCHEDULE_TIME_INOUT 	= 1;
	const INVALID_ACTUAL_TIME_INOUT 	= 2;
	const LATE 							= 3;
	const ATO_LESS_THAN_STO 			= 4;
	const OT_START_LESS_THAN_STO 		= 5;
	const OT_END_GREATER_THAN_ATO 		= 6;
	const OT_START_LESS_THAN_ATS 		= 7;
	const INVALID_EMPLOYEE_ID 			= 8;
	const ABSENT 						= 9;
	const OT_LESS_THAN_30				= 10;
	const RWH_LESS_THAN_TWH				= 11;
	
	protected $id;
	protected $employee_id;
	protected $employee_code;
	protected $employee_name;
	protected $date;
	protected $time_in;
	protected $time_out;
	protected $message;
	protected $is_fixed = self::ERROR_FIXED_NO;
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
	
	public function setTimeIn($value) {
		$this->time_in = $value;
	}
	
	public function getTimeIn() {
		return $this->time_in;	
	}
	
	public function setTimeOut($value) {
		$this->time_out = $value;	
	}
	
	public function getTimeOut() {
		return $this->time_out;	
	}

	public function setAsFixed() {
		$this->is_fixed = self::ERROR_FIXED_YES;
	}
	
	public function setAsNotFixed() {
		$this->is_fixed = self::ERROR_FIXED_NO;
	}
	
	public function isFixed() {
		return $this->is_fixed;	
	}

    public function setIsFixed($value) {
        $this->is_fixed = $value;
    }
	
	public function setErrorTypeId($value) {
		$this->error_type_id = $value;	
	}
	
	public function getErrorTypeId() {
		return $this->error_type_id;	
	}

    /*
     * Deprecated
     */
	public function getErrorTypeString() {
		if ($this->getErrorTypeId() == self::ERROR_NO_IN) {
			return 'NO TIME IN';
		} else if ($this->getErrorTypeId() == self::ERROR_NO_OUT) {
			return 'NO TIME OUT';
		}
	}

    public function save() {
        return G_Overtime_Error_Manager::save($this);
    }
	
	public function addError() {
		return G_Overtime_Error_Manager::add($this);
	}
}
?>