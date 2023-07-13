<?php
class G_Employee_Performance {
	
	public $id;
	public $company_structure_id;
	public $employee_id;
	public $performance_id;
	public $performance_title;
	public $reviewer_id;
	public $created_by;
	public $position;
	public $created_date;
	public $period_from;
	public $period_to;
	public $due_date;
	public $status;
	public $summary;
	public $kpi;	


	
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
		$this->company_structure_id = $value;	
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}
	
	public function setPerformanceId($value) {
		$this->performance_id = $value;	
	}
	
	public function getPerformanceId() {
		return $this->performance_id;
	}
	
	public function setPerformanceTitle($value) {
		$this->performance_title = $value;	
	}
	
	public function getPerformanceTitle() {
		return $this->performance_title;
	}
	
	public function setPosition($value) {
		$this->position = $value;	
	}
	
	public function getPosition() {
		return $this->position;
	}
	
	public function setReviewerId($value) {
		$this->reviewer_id = $value;	
	}
	
	public function getReviewerId() {
		return $this->reviewer_id;
	}
	
	public function setCreatedBy($value) {
		$this->created_by = $value;	
	}
	
	public function getCreatedBy() {
		return $this->created_by;
	}
	
	
	public function setCreatedDate($value) {
		$this->created_date = $value;	
	}
	
	public function getCreatedDate() {
		return $this->created_date;
	}
	
	public function setPeriodFrom($value) {
		$this->period_from = $value;	
	}
	
	public function getPeriodFrom() {
		return $this->period_from;
	}
	
	public function setPeriodTo($value) {
		$this->period_to = $value;	
	}
	
	public function getPeriodTo() {
		return $this->period_to;
	}
	
	public function setDueDate($value) {
		$this->due_date = $value;	
	}
	
	public function getDueDate() {
		return $this->due_date;
	}
	
	public function setSummary($value) {
		$this->summary = $value;
	}
	
	public function getSummary() {
		return $this->summary;
	}
	
	public function setStatus($value) {
		$this->status = $value;	
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function setKpi($value) {
		$this->kpi = $value;	
	}
	
	public function getKpi() {
		return $this->kpi;
	}
		
	public function save() {
		return G_Employee_Performance_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Performance_Manager::delete($this);
	}
}

?>