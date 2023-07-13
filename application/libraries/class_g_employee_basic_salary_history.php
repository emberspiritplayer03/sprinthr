<?php
class G_Employee_Basic_Salary_History {
	const SALARY_TYPE_MONTHLY = 'Monthly';
	const SALARY_TYPE_DAILY = 'Daily';
	const SALARY_TYPE_HOURLY = 'Hourly';
	
	public $id;
	public $employee_id;
	public $job_salary_rate_id;
	public $basic_salary;
	public $type;
	public $frequency_id;
	public $pay_period_id;
	public $start_date;
	public $end_date;
	
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

    public function isDaily() {
        $type = $this->getType();
        if ($type == self::SALARY_TYPE_DAILY) {
            return true;
        } else {
            return false;
        }
    }

    public function isMonthly() {
        $type = $this->getType();
        if ($type == self::SALARY_TYPE_MONTHLY) {
            return true;
        } else {
            return false;
        }
    }
	
	public function setJobSalaryRateId($value) {
		$this->job_salary_rate_id = $value;	
	}
	
	public function getJobSalaryRateId() {
		return $this->job_salary_rate_id;
	}
	
	public function setBasicSalary($value) {
		$this->basic_salary = $value;	
	}
	
	public function getBasicSalary() {
		return $this->basic_salary;
	}
	
	public function setType($value) {
		$this->type = $value;	
	}
	
	public function getType() {
		return $this->type;
	}

		public function setFrequencyId($frequency_id) {
		$this->frequency_id = $frequency_id;	
	}
	
	public function getFrequencyId() {
		return $this->frequency_id;
	}
	
	public function setPayPeriodId($value) {
		$this->pay_period_id = $value;	
	}
	
	public function getPayPeriodId() {
		return $this->pay_period_id;
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
		return G_Employee_Basic_Salary_History_Manager::save($this);
	}
	
	public function resetEmployeePresentSalary() {
		return G_Employee_Basic_Salary_History_Manager::resetEmployeePresentSalary($this);
	}
	
	public function delete() {
		return G_Employee_Basic_Salary_History_Manager::delete($this);
	}

}

?>