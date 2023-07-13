<?php
class Allowed_Ip {
	protected $id;
	protected $ip_address;
	protected $employee_id;
	protected $date_modified;
	protected $date_created;	
			
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
    public function setIpAddress($value) {    	
		$this->ip_address = $value;
	}
	
	public function getIpAddress() {
		return $this->ip_address;
	}
        
    public function setEmployeeId($value) {
		$this->employee_id = $value;
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}
			
	public function setDateModified($value) {		
		$this->date_modified = $value;
	}
	
	public function getDateModified() {
		return $this->date_modified;
	}

	public function setDateCreated($value) {		
		$this->date_created = $value;
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}
}
?>