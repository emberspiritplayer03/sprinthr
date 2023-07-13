<?php
class Role_Actions {
	protected $id;
	protected $role_id;
	protected $module;
	protected $action;	
			
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
    public function setRoleId($value) {    	
		$this->role_id = $value;
	}
	
	public function getRoleId() {
		return $this->role_id;
	}
        
    public function setModule($value) {
		$this->module = $value;
	}
	
	public function getModule() {
		return $this->module;
	}
			
	public function setAction($value) {		
		$this->action = $value;
	}
	
	public function getAction() {
		return $this->action;
	}
}
?>