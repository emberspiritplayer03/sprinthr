<?php
class Settings_Policy {
	public $id;
	public $policy;
	public $description;
	public $is_active;	
	
	public function __construct() {
		
	}
	//id
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	//policy
	public function setPolicy($value) {
		$this->policy = $value;
	}
	
	public function getPolicy() {
		return $this->policy;
	}
	//description
	public function setDescription($value) {
		$this->description = $value;
	}
	
	public function getDescription() {
		return $this->description;
	}
	//status
	public function setIsActive($value) {
		$this->is_active = $value;
	}
	
	public function getIsActive() {
		return $this->is_active;
	}

}
?>