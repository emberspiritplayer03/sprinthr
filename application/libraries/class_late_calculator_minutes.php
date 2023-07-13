<?php
/*
	COMPUTE LATE IN MINUTES
*/
class Late_Calculator_Minutes extends Late_Calculator {
	public function __construct() {

	}
	
	public function computeLateHours() {
		$hours = parent::computeLateHours();
		return $hours / 60;
	}
}
?>