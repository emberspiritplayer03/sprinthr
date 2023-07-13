<?php
class G_Job_Application_Event {
	
	public $id;
	public $company_structure_id;
	public $applicant_id;
	public $date_time_created;
	public $created_by;
	public $hiring_manager_id;
	public $date_time_event;
	public $event_type;
	public $application_status_id;
	public $notes;
	public $remarks;


	
	function __construct() {
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
	
	public function setApplicantId($value) {
		$this->applicant_id = $value;	
	}
	
	public function getApplicantId() {
		return $this->applicant_id;
	}
	
	public function setDateTimeCreated($value) {
		$this->date_time_created = $value;	
	}
	
	public function getDateTimeCreated() {
		return $this->date_time_created;
	}
	
	public function setCreatedBy($value) {
		$this->created_by = $value;	
	}
	
	public function getCreatedBy() {
		return $this->created_by;
	}
	
	public function setHiringManagerId($value) {
		$this->hiring_manager_id= $value;	
	}
	
	public function getHiringManagerId() {
		return $this->hiring_manager_id;
	}
	
	public function setDateTimeEvent($value) {
		$this->date_time_event = $value;	
	}
	
	public function getDateTimeEvent() {
		return $this->date_time_event;
	}
	
	public function setEventType($value) {
		$this->event_type= $value;	
	}
	
	public function getEventType() {
		return $this->event_type;
	}
	
	public function setApplicationStatusId($value) {
		$this->application_status_id = $value;	
	}
	
	public function getApplicationStatusId() {
		return $this->application_status_id;
	}
	
	public function setNotes($value) {
		$this->notes = $value;	
	}
	
	public function getNotes() {
		return $this->notes;
	}
	
	public function setRemarks($value) {
		$this->remarks = $value;	
	}
	
	public function getRemarks() {
		return $this->remarks;
	}
			
	public function save() {
		return G_Job_Application_Event_Manager::save($this);
	}
	
	public function loadDefaultApplicationEventHistory($aid) {
		return G_Job_Application_Event_Helper::loadDefaultApplicationEventHistory($aid);
	}
	
	public function delete() {
		return G_Job_Application_Event_Manager::delete($this);
	}
}

?>