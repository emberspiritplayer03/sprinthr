<?php
class Daily_Time_Record {
	protected $employee_code;
	protected $employee_name;
	protected $date;
	protected $time;
	
	public function __construct() {
		
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
	
	public function setDate($value) {
		$this->date = $value;
	}
	
	public function getDate() {
		return $this->date;	
	}
	
	public function setTime($value) {
		$this->time = $value;	
	}
	
	public function getTime() {
		return $this->time;	
	}
	
	public function punch() {
		$this->time = Tools::getGmtDate('H:i:s');
		$this->date = Tools::getGmtDate('Y-m-d');
		$this->save();
	}
	
	public function save() {
		return G_Daily_Time_Record_Manager::save($this);	
	}
}
?>