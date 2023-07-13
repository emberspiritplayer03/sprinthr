<?php
class G_Employee_Skills {
	
	public $id;
	public $employee_id;
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
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
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
		return G_Employee_Skills_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Skills_Manager::delete($this);
	}
}

?>