<?php
class G_User {
	
	public $id;
	public $company_structure_id;
	public $user_group_id;
	public $employee_id;
	public $employment_status;
	public $username;
	public $hash;
	public $password;
	public $module;
	public $receive_notification;
	public $date_entered;
	public $date_modified;
	public $modified_user_id;
	public $created_by;
    protected $is_admin;

	function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}

	public function getId() {
		return $this->id;
	}

    public function setIsAdmin($value) {
      $this->is_admin = $value;
    }

    public function isAdmin() {
      return $this->is_admin;
    }
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;	
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function setUserGroupId($value) {
		$this->user_group_id = $value;	
	}
	
	public function getUserGroupId() {
		return $this->user_group_id;
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}
	
	public function setEmploymentStatus($value) {
		$this->employment_status = $value;	
	}
	
	public function getEmploymentStatus() {
		return $this->employment_status;
	}
	
	public function setUsername($value) {
		$this->username = $value;	
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function setHash($value) {
		$this->hash = $value;	
	}
	
	public function getHash() {
		return $this->hash;
	}
	
	public function setPassword($value) {
		$this->password = $value;	
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function setModule($value) {
		$this->module = $value;	
	}
	
	public function getModule() {
		return $this->module;
	}
	
	public function setReceiveNotification($value) {
		$this->receive_notification = $value;	
	}
	
	public function getReceiveNotification() {
		return $this->receive_notification;
	}
	
	public function setDateEntered($value) {
		$this->date_entered = $value;	
	}
	
	public function getDateEntered() {
		return $this->date_entered;
	}
	
	
	public function setDateModified($value) {
		$this->date_modified = $value;	
	}
	
	public function getDateModified() {
		return $this->date_modified;
	}
	
	public function setModifiedUserId($value) {
		$this->modified_user_id = $value;	
	}
	
	public function getModifiedUserId() {
		return $this->modified_user_id;
	}
	
	public function setCreatedBy($value) {
		$this->created_by = $value;	
	}
	
	public function getCreatedBy() {
		return $this->created_by;
	}
			
	public function save() {
		return G_User_Manager::save($this);
	}

	public function saveAsAdmin() {
		return G_User_Manager::saveAsAdmin($this);
	}
	
	public function updatePassword() {
		return G_User_Manager::updatePassword($this);
	}
	
	public function delete() {
		return G_User_Manager::delete($this);
	}
}

?>