<?php
class G_Settings_Policy extends Settings_Policy {
	
	const REQUEST_OT_WHEN_LATE = 1; //refer to g_settings_policy
	const IS_ACTIVATED 			= 'Yes';
	const IS_DEACTIVATED 		= 'No';
		
	public function __construct() {}

	public function OvertimePolicyWhenLate() {
		$ot_policy = G_Settings_Policy_Finder::findById(G_Settings_Policy::REQUEST_OT_WHEN_LATE);	
		return $ot_policy->getIsActive();	
	}	
	
	public function save() {		
		return G_Settings_Policy_Manager::save($this);
	}
	
	public function delete() {
		return G_Settings_Policy_Manager::delete($this);
	}
}
?>