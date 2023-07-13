<?php
class G_Employee_Requirements{

	public $id;
	public $employee_id;
	public $requirements;
	public $date_updated;
	public $is_complete;
		
	public function __construct($id) {
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
	
	public function setRequirements($value) {
		$this->requirements = $value;
	}
	
	public function getRequirements() {
		return $this->requirements;
	}
	
	public function setDateUpdated($value) {
		$this->date_updated = $value;
	}
	
	public function getDateUpdated() {
		return $this->date_updated;
	}
	
	public function setIsComplete($value) {
		$this->is_complete = $value;
	}
	
	public function getIsComplete() {
		return $this->is_complete;
	}
		
	public function save (G_Employee_Requirements $gcs) {
		return G_Employee_Requirements_Manager::save($this, $gcs);
	}
	
	public function delete() {
		return G_Employee_Requirements_Manager::delete($this);
	}
}
?>