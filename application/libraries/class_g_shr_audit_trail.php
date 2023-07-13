<?php
class G_Shr_Audit_Trail extends Shr_Audit_Trail {	
	
	// CONSTANTS
	const FAILED 		= 'FAILED';
	const SUCCESS 		= 'SUCCESS';
			
	public function __construct() {}	

	public function getShrUserIPAndCountry() {		
		return G_Shr_Audit_Trail_Helper::getShrUserIPAndCountry();
	}		
	
	public function save_shr_audit_trail() {	
		
		return G_Shr_Audit_Trail_Manager::save_shr_audit_trail_manager($this);
	}
	
	public function delete() {
		return G_Shr_Audit_Trail_Manager::delete($this);
	}
}
?>