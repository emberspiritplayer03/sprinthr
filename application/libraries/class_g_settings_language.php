<?php
class G_Settings_Language extends Settings_Language {
	
	public function __construct() {
		
	}
	
	public function save() {		
		return G_Settings_Language_Manager::save($this);
	}
	
	public function delete() {
		return G_Settings_Language_Manager::delete($this);
	}
}
?>