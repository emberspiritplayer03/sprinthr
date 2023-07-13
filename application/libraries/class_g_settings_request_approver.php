<?php
class G_Settings_Request_Approver extends Settings_Request_Approver {
	
	//Object
	protected $gsr;
			
	public function __construct() {
		
	}
	
	public function save(G_Settings_Request $gsr) {		
		return G_Settings_Request_Approver_Manager::save($this, $gsr);
	}
	
	public function updateOverrideLevel() {		
		return G_Settings_Request_Approver_Manager::updateOverrideLevel($this);
	}
	
	public function delete() {
		return G_Settings_Request_Approver_Manager::delete($this);
	}
}
?>