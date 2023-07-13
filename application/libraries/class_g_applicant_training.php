<?php
class G_Applicant_Training {
	
	public $id;
	public $applicant_id;
	public $from_date;
	public $to_date;
	public $description;
	public $provider;
	public $location;
	public $cost;
	public $renewal_date;
	
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
	
	public function setFromDate($value) {
		$this->from_date = $value;	
	}
	
	public function getFromDate() {
		return $this->from_date;
	}
	
	public function setToDate($value) {
		$this->to_date = $value;	
	}
	
	public function getToDate() {
		return $this->to_date;
	}
	
	public function setDescription($value) {
		$this->description = $value;	
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setProvider($value) {
		$this->provider = $value;	
	}
	
	public function getProvider() {
		return $this->provider;
	}
	
	public function setLocation($value) {
		$this->location = $value;	
	}
	
	public function getLocation() {
		return $this->location;
	}
	
	public function setCost($value) {
		$this->cost = $value;	
	}
	
	public function getCost() {
		return $this->cost;
	}
	
	public function setRenewalDate($value) {
		$this->renewal_date = $value;	
	}
	
	public function getRenewalDate() {
		return $this->renewal_date;
	}
	
	
		
	public function save() {
		return G_Applicant_Training_Manager::save($this);
	}
	
	public function delete() {
		return G_Applicant_Training_Manager::delete($this);
	}
}

?>