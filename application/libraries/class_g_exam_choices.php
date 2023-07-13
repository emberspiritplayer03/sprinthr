<?php
class G_Exam_Choices {
	
	public $id;
	public $exam_question_id;
	public $choices;	
	public $order_by;

		
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setExamQuestionId($value) {
		$this->exam_question_id = $value;
	}
	
	public function getExamQuestionId() {
		return $this->exam_question_id;
	}
	
	public function setChoices($value) {
		$this->choices = $value;
	}
	
	public function getChoices() {
		return $this->choices;
	}
	
	public function setOrderBy($value) {
		$this->order_by= $value;
	}
	
	public function getOrderBy() {
		return $this->order_by;
	}
	
	public function save (G_Exam_Choices $gcs) {
		return G_Exam_Choices_Manager::save($this, $gcs);
	}
	
	public function delete() {
		return G_Exam_Choices_Manager::delete($this);
	}
}
?>