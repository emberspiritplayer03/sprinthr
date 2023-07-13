<?php
class G_Settings_Leave_Credit extends Settings_Leave_Credit {

	const NO 	= "No";
	const YES 	= "Yes";
	
	public function __construct() {}

	public function save() {		
		return G_Settings_Leave_Credit_Manager::save($this);
	}
	
	public function delete() {
		return G_Settings_Leave_Credit_Manager::delete($this);
	}	
	
}
?>