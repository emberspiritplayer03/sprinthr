<?php
class G_Payment_History extends Payment_History {	
	protected $id;
	
	public function __construct() {
		parent::__construct();	
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}
	
	public function save() {
		return G_Payment_History_Manager::save($this);	
	}
}
?>