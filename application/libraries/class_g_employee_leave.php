<?php
class G_Employee_Leave_Available {
	
	public $id;
	public $employee_id;
	public $leave_id;
	public $no_of_days_alloted;
	public $no_of_days_available;

	
	
	function __construct($id) {
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
	
	public function setLeaveId($value) {
		$this->leave_id = $value;	
	}
	
	public function getLeaveId() {
		return $this->leave_id;
	}
	
	public function setNoOfDaysAlloted($value) {
		$this->no_of_days_alloted = $value;	
	}
	
	public function getNoOfDaysAlloted() {
		return $this->no_of_days_alloted;
	}
	
	public function setNoOfDaysAvailable($value) {
		$this->no_of_days_available = $value;	
	}
	
	public function getNoOfDaysAvailable() {
		return $this->no_of_days_available;
	}
	
	public function save() {
		return G_Employee_Leave_Available_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Leave_Available_Manager::delete($this);
	}
}

?>