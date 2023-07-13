<?php
class G_Applicant_Requirements{

	public $id;
	public $applicant_id;
	public $requirements;
	public $date_updated;
	public $is_complete;
		
	public function __construct() {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setApplicantId($value) {
		$this->applicant_id = $value;
	}
	
	public function getApplicantId() {
		return $this->applicant_id;
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
	
	public function loadDefaultApplicantRequirements($aid,$xml_file) {		
		return G_Applicant_Requirements_Helper::loadDefaultApplicantRequirements($aid,$xml_file);
	}
	
	public function loadDefaultRequirements() {		
		return G_Applicant_Requirements_Helper::loadDefaultRequirements($this);
	}
		
	public function save(G_Applicant_Requirements $gcs) {
		return G_Applicant_Requirements_Manager::save($this, $gcs);
	}
	
	public function delete() {
		return G_Applicant_Requirements_Manager::delete($this);
	}
}
?>