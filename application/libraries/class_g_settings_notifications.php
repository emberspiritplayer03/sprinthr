<?php
class G_Settings_Notifications extends Settings_Notifications {	
	
	// CONSTANTS
	const YES 			= 'Yes';
	const NO 		    = 'No';
			
	public function __construct() {}		
	
	public function save() {		
		return G_Settings_Notifications_Manager::save($this);
	}
	
	public function delete() {
		return G_Settings_Notifications_Manager::delete($this);
	}
}
?>