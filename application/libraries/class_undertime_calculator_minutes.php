<?php
class Undertime_Calculator_Minutes extends Undertime_Calculator {
	public function __construct() {

	}
	
	public function computeUndertimeHours() {
		$hours = parent::computeUndertimeHours();
		return $hours / 60;
	}
}
?>