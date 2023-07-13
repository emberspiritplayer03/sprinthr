<?php
class G_Exam_Question {
	
	public $id;
	public $exam_id;
	public $question;	
	public $answer;
	public $order_by;
	public $type;
		
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setExamId($value) {
		$this->exam_id = $value;
	}
	
	public function getExamId() {
		return $this->exam_id;
	}
	
	public function setQuestion($value) {
		$this->question = $value;
	}
	
	public function getQuestion() {
		return $this->question;
	}
	
	public function setAnswer($value) {
		$this->answer = $value;
	}
	
	public function getAnswer() {
		return $this->answer;
	}
	
	public function setOrderBy($value) {
		$this->order_by = $value;
	}
	
	public function getOrderBy() {
		return $this->order_by;
	}
	
	public function setType($value) {
		$this->type = $value;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function save (G_Exam_Question $gcs) {
		return G_Exam_Question_Manager::save($this, $gcs);
	}
	
	public function delete() {
		return G_Exam_Question_Manager::delete($this);
	}
}
?>