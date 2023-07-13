<?php
class G_Exam {
	
	public $id;
	public $company_structure_id;
	public $title;	
	public $description;
	public $applicable_to_job;
	public $apply_to_all_jobs;
	public $passing_percentage;
	public $time_duration;
	public $created_by;
	public $date_created;

	const YES = 'Yes';
	const NO  = 'No';	
	
	//objects
	public $gcs;
		
	public function __construct($id) {
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
	
	public function setTitle($value) {
		$this->title = $value;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setApplicableToJob($value) {
		$this->applicable_to_job = $value;
	}
	
	public function getApplicableToJob() {
		return $this->applicable_to_job;
	}
	
	public function setApplyToAllJobs($value) {
		$this->apply_to_all_jobs = $value;
	}
	
	public function getApplyToAllJobs() {
		return $this->apply_to_all_jobs;
	}
	
	public function setDescription($value) {
		$this->description = $value;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setPassingPercentage($value) {
		$this->passing_percentage = $value;
	}
	
	public function getPassingPercentage() {
		return $this->passing_percentage;
	}
	
	public function setTimeDuration($value) {
		$this->time_duration = $value;
	}
	
	public function getTimeDuration() {
		return $this->time_duration;
	}
	
	public function setCreatedBy($value) {
		$this->created_by = $value;
	}
	
	public function getCreatedBy() {
		return $this->created_by;
	}
	
	public function setDateCreated($value) {
		$this->date_created = $value;
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}
			
	public function save (G_Exam $gcs) {
		return G_Exam_Manager::save($this, $gcs);
	}
	
	public function sendExaminationToApplicant($job_id,$applicant_id) {
		return G_Exam_Helper::examinationTagToJob($job_id,$applicant_id);
	}
	
	public function delete() {
		return G_Exam_Manager::delete($this);
	}
}
?>