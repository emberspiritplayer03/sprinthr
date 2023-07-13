<?php
class G_Applicant_Skills {
	
	public $id;
	public $applicant_id;
	public $skill;
	public $years_experience;
	public $comments;
	
	
	
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

	public function setSkill($value) {
		$this->skill = $value;	
	}
	
	public function getSkill() {
		return $this->skill;
	}
	
	public function setYearsExperience($value) {
		$this->years_experience = $value;	
	}
	
	public function getYearsExperience() {
		return $this->years_experience;
	}
	
	public function setComments($value) {
		$this->comments = $value;	
	}
	
	public function getComments() {
		return $this->comments;
	}
		
	public function save() {
		return G_Applicant_Skills_Manager::save($this);
	}
	
	public function delete() {
		return G_Applicant_Skills_Manager::delete($this);
	}
}

?>