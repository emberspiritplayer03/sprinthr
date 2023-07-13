<?php
class Applicant_Logs {
	public $id;
	public $ip;
	public $country;
	public $firstname;
	public $lastname;	
	public $email;
	public $password;
	public $status;
	public $date_time_created;
	public $date_time_validated;
	public $link;
	public $is_password_change;
	
	public function __construct() {
		
	}
	//id
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	//ip
	public function setIp($value) {
		$this->ip = $value;
	}
	
	public function getIp() {
		return $this->ip;
	}
	//country
	public function setCountry($value) {
		$this->country = $value;
	}
	
	public function getCountry() {
		return $this->country;
	}
	//firstname
	public function setFirstName($value) {
		$this->firstname = $value;
	}
	
	public function getFirstName() {
		return $this->firstname;
	}
	//lastname
	public function setLastName($value) {
		$this->lastname = $value;
	}
	
	public function getLastName() {
		return $this->lastname;
	}
	//email;
	public function setEmail($value) {
		$this->email = $value;
	}
	
	public function getEmail() {
		return $this->email;
	}
	//password;
	public function setPassword($value) {
		$this->password = $value;
	}
	
	public function getPassword() {
		return $this->password;
	}
	//status;
	public function setStatus($value) {
		$this->status = $value;
	}
	
	public function getStatus() {
		return $this->status;
	}
	//date_time_created;
	public function setDateTimeCreated($value) {
		$this->date_time_created = $value;
	}
	
	public function getDateTimeCreated() {
		return $this->date_time_created;
	}
	//date_time_validated;
	public function setDateTimeValidated($value) {
		$this->date_time_validated = $value;
	}
	
	public function getDateTimeValidated() {
		return $this->date_time_validated;
	}	
	//link;
	public function setLink($value) {
		$this->link = $value;
	}
	
	public function getLink() {
		return $this->link;
	}
	//is_password_change
	public function setIsPasswordChange($value) {
		$this->is_password_change = $value;
	}
	
	public function getIsPasswordChange() {
		return $this->is_password_change;
	}

}
?>