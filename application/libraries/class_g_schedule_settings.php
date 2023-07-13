<?php

class G_Schedule_Settings extends Schedule_Settings {
	
	public function __construct() {
		
	}
							
	public function save() {
		return G_Schedule_Settings_Manager::save($this);
	}
		
}
?>