<?php
class Employee_Factory {
	public static function get($employee_id) {
		return G_Employee_Finder::findById($employee_id);
	}
	
	public static function getBothArchiveAndNot($employee_id) {
		return G_Employee_Finder::findByIdBothArchiveAndNot($employee_id);
	}
}
?>