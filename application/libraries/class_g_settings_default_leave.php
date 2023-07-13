<?php
class G_Settings_Default_Leave {
	
	public $id;
	public $company_structure_id;
	public $leave_type_id;
	public $number_of_days_default;
	
	 
	function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id= $value;	
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function setLeaveTypeId($value) {
		$this->leave_type_id = $value;	
	}
	
	public function getLeaveTypeId() {
		return $this->leave_type_id;
	}
	
	public function setNumberOfDaysDefault($value) {
		$this->number_of_days_default = $value;	
	}
	
	public function getNumberOfDaysDefault() {
		return $this->number_of_days_default;
	}
		
	
	public function save() {
		return G_Settings_Default_Leave_Manager::save($this);
	}
	
	public function delete() {
		return G_Settings_Default_Leave_Manager::delete($this);
	}
}

?>