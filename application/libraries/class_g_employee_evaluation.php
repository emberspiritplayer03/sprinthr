<?php

	class G_Employee_Evaluation{
	
	protected $id;
	protected $employee_id;
	protected $score;
	protected $attachment;
	protected $evaluation_date;
    protected $next_evaluation_date;
	protected $date_created;
	protected $is_archive;
	  
	
	const YES = 'Yes';
	const NO  = 'No';


	public function __construct() {
		
	}




	public function setId($value) {
		$this->id = $value;	
	}

	public function getId() {
		return $this->id;	
	}


	public function setEmployeeId($value){

		$this->employee_id = Utilities::decrypt($value);
	}

	public function getEmployeeId(){

		return $this->employee_id;
	}

	public function setEmployeeId2($value){

		$this->employee_id = $value;
	}

	public function getEmployeeId2(){

		return $this->employee_id;
	}



	public function setEvaluationDate($value) {
		$value = date("Y-m-d",strtotime($value));
		$this->evaluation_date = $value;	
	}
	
	public function getEvaluationDate() {
		return $this->evaluation_date;	
	}


	public function setNextEvaluationDate($value) {
		$value = date("Y-m-d",strtotime($value));
		$this->next_evaluation_date = $value;	
	}
	
	public function getNextEvaluationDate() {
		return $this->next_evaluation_date;	
	}



	public function setDateCreated($value) {
		$value = date("Y-m-d",strtotime($value));
		$this->date_created = $value;	
	}
	
	public function getDateCreated() {
		return $this->date_created;	
	}


	public function setIsArchive($value) {
		
		$this->is_archive = $value;	
	}
	
	public function getIsArchive() {
		return $this->is_archive;	
	}


	public function setScore($value){
		$this->score = $value;	

	}


	public function getScore(){
		return $this->score;	

	}


	public function setAttachment($value){
		 $this->attachment = $value;	

	}


	public function gettAttachment(){
		return $this->attachment;	

	}



	public function save() {		
		return G_Employee_Evaluation_Manager::save($this);
	}
	
 



}


?>