<?php
class G_Settings_Requirement extends Settings_Requirement {
	
	public $is_archive;
	public $date_created;
	
	const YES = "Yes";
	const NO  = "No";
	
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
	
	public function archive() {		
		return G_Settings_Requirement_Manager::archive($this);	
	}
	
	public function restore() {		
		return G_Settings_Requirement_Manager::restore($this);	
	}
	
	public function save() {		
		return G_Settings_Requirement_Manager::save($this);	
	}
	
	public function delete() {
		return G_Settings_Requirement_Manager::delete($this);	
	}
}
?>