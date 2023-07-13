<?php
class G_Access_Rights {
	
	public $id;
	public $company_structure_id;
	public $user_group_id;
	public $policy_type;
	public $rights;
	public $date_added;
	
	const NO_ACCESS 	= 0;
	const HAS_ACCESS	= 1;
	const CAN_MANAGE	= 2;
	const CUSTOM		= 3;
	
	const YES   = 'Yes';
	const NO  	= 'No';
	
	const USER	= 'User';
	const GROUP	= 'Group';
	
	function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id= $value;	
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
	
	public function setPolicyType($value) {
		$this->policy_type = $value;	
	}
	
	public function getPolicyType() {
		return $this->policy_type;
	}
	
	public function setRights($value) {
		$this->rights = $value;	
	}
	
	public function getRights() {
		return $this->rights;
	}
	
	public function setDateAdded($value) {
		$this->date_added = $value;	
	}
	
	public function getDateAdded() {
		return $this->date_added;
	}
	
	public function save() {
		return G_Access_Rights_Manager::save($this);
	}
	
	public function delete() {
		return G_Access_Rights_Manager::delete($this);
	}
}

?>