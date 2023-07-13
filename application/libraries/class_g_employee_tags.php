<?php
class G_Employee_Tags extends Employee_Tags {
	public $is_archive;
	public $date_created;
	
	protected $e;
	
	const YES = 'Yes';
	const NO  = 'No';
	
	public function __construct() {
		
	}
	
	public function setIsArchive($value) {
		$this->is_archive = $value;
	}
	
	public function getIsArchive() {
		return $this->is_archive;
	}
	
	public function setDateCreated($value) {
		$this->date_created = $value;
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}
		
	public function save(G_Employee $e) {		
		return G_Employee_Tags_Manager::save($this,$e);
	}
	
	public function delete() {
		return G_Employee_Tags_Manager::delete($this);
	}
}
?>