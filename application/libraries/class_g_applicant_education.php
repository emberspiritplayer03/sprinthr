<?php
class G_Applicant_Education {
	
	public $id;
	public $applicant_id;
	public $institute;
	public $course;
	public $year;
	public $start_date;
	public $end_date;
	public $gpa_score;
	public $attainment;
	
	
	function __construct($id) {
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
	
	public function setInstitute($value) {
		$this->institute = $value;	
	}
	
	public function getInstitute() {
		return $this->institute;
	}
	
	public function setCourse($value) {
		$this->course = $value;	
	}
	
	public function getCourse() {
		return $this->course;
	}
	
	public function setYear($value) {
		$this->year = $value;	
	}
	
	public function getYear() {
		return $this->year;
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
	
	public function setGpaScore($value) {
		$this->gpa_score = $value;	
	}
	
	public function getGpaScore() {
		return $this->gpa_score;
	}
	
	public function setAttainment($value) {
		$this->attainment = $value;	
	}
	
	public function getAttainment() {
		return $this->attainment;
	}
	
	public function save() {
		return G_Applicant_Education_Manager::save($this);
	}
	
	public function delete() {
		return G_Applicant_Education_Manager::delete($this);
	}
}

?>