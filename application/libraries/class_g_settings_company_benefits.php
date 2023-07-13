<?php

class G_Settings_Company_Benefits extends Settings_Company_Benefits {
	
	const YES = "Yes";
	const NO  = "No";
	
	const EARNING   = "Earning";
	const DEDUCTION = "Deduction";	
	
	public function __construct() {
		
	}
	
	public function archive() {
		return G_Settings_Company_Benefits_Manager::archive($this);
	}
	
	public function restore() {
		return G_Settings_Company_Benefits_Manager::restore($this);
	}
							
	public function save() {
		return G_Settings_Company_Benefits_Manager::save($this);
	}
		
	public function delete() {
		G_Settings_Company_Benefits_Manager::delete($this);
	}
}
?>