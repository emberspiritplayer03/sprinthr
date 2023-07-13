<?php
class G_Settings_Request extends Settings_Request {
	
	public $is_active;
	public $is_archive;
	public $date_created;
	
	public function __construct() {
		
	}
	
	public function setIsActive($value) {
		$this->is_active = $value;
	}
	
	public function getIsActive() {
		return $this->is_active;
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
	
	public function save() {		
		return G_Settings_Request_Manager::save($this);
	}
	
	public function archive() {		
		return G_Settings_Request_Manager::archive($this);
	}
	
	public function delete() {
		return G_Settings_Request_Manager::delete($this);
	}
}
?>