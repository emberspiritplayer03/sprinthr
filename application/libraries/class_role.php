<?php
class Role {
	protected $id;
	protected $name;
	protected $role_description;
	protected $date_created;
	protected $last_modified;
	protected $is_archive;	
			
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
    public function setName($value) {
    	$format = ucwords($value);
		$this->name = $format;
	}
	
	public function getName() {
		return $this->name;
	}
        
    public function setDescription($value) {
		$this->description = $value;
	}
	
	public function getDescription() {
		return $this->description;
	}
			
	public function setDateCreated($value) {
		$date_format = date("Y-m-d H:i:s",strtotime($value));
		$this->date_created = $date_format;
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}

	public function setLastModified($value) {
		$date_format = date("Y-m-d H:i:s",strtotime($value));
		$this->last_modified = $date_format;
	}
	
	public function getLastModified() {
		return $this->last_modified;
	}

	public function setIsArchive($value) {
		$this->is_archive = $value;
	}
	
	public function getIsArchive() {
		return $this->is_archive;
	}
}
?>