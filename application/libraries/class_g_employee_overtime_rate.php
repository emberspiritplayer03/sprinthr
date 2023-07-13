<?php
class G_Employee_Overtime_Rate extends Employee_Overtime_Rate {
	
	public function __construct() {
		
	}
		
	public function save() {		
		return G_Employee_Overtime_Rate_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Overtime_Rate_Manager::delete($this);
	}
	
}
?>