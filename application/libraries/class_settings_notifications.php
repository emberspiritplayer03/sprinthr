<?php
class Settings_Notifications {
	public $id;
	public $title;
	public $sub_module;
	public $is_enable;
	
	public function __construct() {
		
	}
	//id
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	//title
	public function setTitle($value) {
		$this->title = $value;
	}
	
	public function getTitle() {
		return $this->title;
	}
	//sub_module
	public function setSubModule($value) {
		$this->sub_module = $value;
	}
	
	public function getSubModule() {
		return $this->sub_module;
	}
	//is_enable
	public function setIsEnable($value) {
		$this->is_enable = $value;
	}
	
	public function getIsEnable() {
		return $this->is_enable;
	}

}
?>