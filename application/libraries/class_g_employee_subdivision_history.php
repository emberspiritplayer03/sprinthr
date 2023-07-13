<?php
class G_Employee_Subdivision_History {
	
	public $id;
	public $employee_id;
	public $company_structure_id;
	public $name;
	public $type;
	public $start_date;
	public $end_date;

	const DEPARTMENT = "Department";
	const GROUP		 = "Group";
	const TEAM		 = "Team";
	
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
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;	
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function setName($value) {
		$this->name = $value;	
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setType($value) {
		$this->type = $value;	
	}
	
	public function getType() {
		return $this->type;
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
		return G_Employee_Subdivision_History_Manager::save($this);
	}
	
	public function resetEmployeePresentSubdivision() {
		return G_Employee_Subdivision_History_Manager::resetEmployeePresentSubdivision($this);
	}
	public function resetEmployeePresentSubdivisionBySubdivisionHistory($history_id) {
		return G_Employee_Subdivision_History_Manager::resetEmployeePresentSubdivisionBySubdivisionHistory($this,$history_id);
	}
	public function updateSubdivisionBySubdivisionHistoryEndDate($history_id,$end_date) {
		return G_Employee_Subdivision_History_Manager::updateSubdivisionBySubdivisionHistoryEndDate($this,$history_id,$end_date);
	}
	
	public function delete() {
		return G_Employee_Subdivision_History_Manager::delete($this);
	}
}

?>