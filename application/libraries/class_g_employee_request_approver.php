<?php
class G_Employee_Request_Approver extends Employee_Request_Approver {	
	
	//Object
	protected $ger;
	protected $gsra;
	
	public function __construct() {
		
	}
	
	public function save(G_Employee_Request $ger, G_Settings_Request_Approver $gsra) {		
		return G_Employee_Request_Approver_Manager::save($this, $gsra);
	}
	
	public function delete() {
		return G_Employee_Request_Approver_Manager::delete($this);
	}
	
	public function update() {		
		return G_Employee_Request_Approver_Manager::update($this);
	}
}
?>