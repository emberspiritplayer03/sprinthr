<?php
class Group {	
	protected $id;
	protected $group_name;
	
	public function __construct() {

	}
	
	public function setId($id) {
		$this->id = $id;	
	}
	
	public function getId() {
		return $this->id;	
	}
	
	public function setName($value) {
		$this->group_name = $value;
	}
	
	public function getName() {
		return $this->group_name;	
	}
}
?>