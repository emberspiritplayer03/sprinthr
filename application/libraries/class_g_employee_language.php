<?php
class G_Employee_Language {
	
	public $id;
	public $employee_id;
	public $language;
	public $fluency;
	public $competency;
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
	
	public function setLanguage($value) {
		$this->language = $value;	
	}
	
	public function getLanguage() {
		return $this->language;
	}
	
	public function setFluency($value) {
		$this->fluency = $value;	
	}
	
	public function getFluency() {
		return $this->fluency;
	}
	
	public function setCompetency($value) {
		$this->competency = $value;	
	}
	
	public function getCompetency() {
		return $this->competency;
	}
	
	public function setComments($value) {
		$this->comments = $value;	
	}
	
	public function getComments() {
		return $this->comments;
	}

	
		
	public function save() {
		return G_Employee_Language_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Language_Manager::delete($this);
	}
}

?>