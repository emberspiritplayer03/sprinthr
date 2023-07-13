<?php
class G_Applicant_Examination {
	
	public $id;
	public $company_structure_id;
	public $applicant_id;
	public $exam_id;
	public $title;
	public $description;
	public $exam_code;
	public $passing_percentage;
	public $schedule_date;
	public $date_taken;
	public $status;
	public $result;
	public $questions;
	public $time_duration;
	public $scheduled_by;
	
	const PENDING   = 'Pending';
	const CANCELLED = 'Cancelled';

		
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
		$this->company_structure_id= $value;
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
	
	public function setExamId($value) {
		$this->exam_id = $value;
	}
	
	public function getExamId() {
		return $this->exam_id;
	}
	
	public function setTitle($value) {
		$this->title = $value;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setDescription($value) {
		$this->description= $value;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setExamCode($value) {
		$this->exam_code= $value;
	}
	
	public function getExamCode() {
		return $this->exam_code;
	}
	
	public function setPassingPercentage($value) {
		$this->passing_percentage = $value;
	}
	
	public function getPassingPercentage() {
		return $this->passing_percentage;
	}
	
	public function setScheduleDate($value) {
		$this->schedule_date = $value;
	}
	
	public function getScheduleDate() {
		return $this->schedule_date;
	}
	
	public function setDateTaken($value) {
		$this->date_taken = $value;
	}
	
	public function getDateTaken() {
		return $this->date_taken;
	}
	
	public function setStatus($value) {
		$this->status = $value;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function setResult($value) {
		$this->result = $value;
	}
	
	public function getResult() {
		return $this->result;
	}
	
	public function setQuestions($value) {
		$this->questions = $value;
	}
	
	public function getQuestions() {
		return $this->questions;
	}
	
	public function setTimeDuration($value) {
		$this->time_duration = $value;
	}
	
	public function getTimeDuration() {
		return $this->time_duration;
	}
	
	public function setScheduledBy($value) {
		$this->scheduled_by = $value;
	}
	
	public function getScheduledBy() {
		return $this->scheduled_by;
	}
	

		
	public function save(G_Applicant_Examination $gcs) {
		return G_Applicant_Examination_Manager::save($this, $gcs);
	}
	
	public function cancel(G_Applicant_Examination $gcs) {
		return G_Applicant_Examination_Manager::cancel($this, $gcs);
	}
	
	public function delete() {
		return G_Applicant_Examination_Manager::delete($this);
	}
	
	public function deleteAllByApplicantId() {
		return G_Applicant_Examination_Manager::deleteAllByApplicantId($this);
	}	
}
?>