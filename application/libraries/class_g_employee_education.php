<?php
class G_Employee_Education {
	
	public $id;
	public $employee_id;
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
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
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

	public function getAllUniqueCourse($fields = array(), $order_by = ''){
		$data = G_Employee_Education_Helper::sqlDistinctCourse($fields, $order_by);

		return $data;
	}
	
	public function save() {
		return G_Employee_Education_Manager::save($this);
	}

	/*
		Note :
		Array structure :
		Array
		(
		    [0] => ('STI','BSIT','2010-05-01','2012-03-03')
		    [1] => ('STI','COE','2012-05-01','2015-03-03')
		)
	*/

	public function bulkInsert( $data = array() ) {
		return G_Employee_Education_Manager::bulkInsert($data);
	}
	
	public function delete() {
		return G_Employee_Education_Manager::delete($this);
	}
}

?>