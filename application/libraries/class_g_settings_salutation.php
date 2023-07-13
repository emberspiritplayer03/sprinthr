<?php
class G_Settings_Salutation extends Settings_Salutation {
	
	public function __construct() {
		
	}
	
	public function save() {		
		return G_Settings_Salutation_Manager::save($this);
	}
	
	public function delete() {
		return G_Settings_Salutation_Manager::delete($this);
	}
}
?>