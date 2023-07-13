<?php
class Employee_User {
	protected $id;
	protected $company_structure_id;
	protected $employee_id;
	protected $role_id;
	protected $username;
	protected $password;	
	protected $date_created;
	protected $last_modified;
	protected $is_archive;	

	const YES = "Yes";
	const NO  = "No";
			
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
    public function setCompanyStructureId($value) {    	
		$this->company_structure_id = $value;
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}

	public function setEmployeeId($value) {
		$this->employee_id = $value;
	}

	public function getEmployeeId() {
		return $this->employee_id;
	}

	public function setRoleId($value) {
		$this->role_id = $value;
	}

	public function getRoleId() {
		return $this->role_id;
	}

	public function setUsername($value) {
		$this->username = $value;
	}

	public function getUsername() {
		return $this->username;
	}

	public function setPassword($value) {
		$this->password = $value;
	}

	public function getPassword() {
		return $this->password;
	}

	public function setDateCreated($value) {
		$date_formatted = date("Y-m-d H:i:s",strtotime($value));
		$this->date_created = $date_formatted;
	}

	public function getDateCreated() {
		return $this->date_created;
	}

	public function setLastModified($value) {
		$date_formatted = date("Y-m-d H:i:s",strtotime($value));
		$this->last_modified = $date_formatted;
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