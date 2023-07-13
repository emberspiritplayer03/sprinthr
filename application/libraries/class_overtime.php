<?php
/*
	Usage:
	$o = new Overtime;
	$o->setDate('2012-09-10');
	$o->setTimeIn('18:00:00');
	$o->setTimeOut('23:00:00');
*/
class Overtime {
	protected $date;
	protected $time_in;
	protected $time_out;
	
	public function __construct() {
		
	}
	
	public function setDate($value) {
		$this->date = $value;	
	}
	
	public function getDate() {
		return $this->date;	
	}
	
	public function setTimeIn($value) {
		$this->time_in = $value;	
	}
	
	public function getTimeIn() {
		return $this->time_in;	
	}
	
	public function setTimeOut($value) {
		$this->time_out = $value;
	}
	
	public function getTimeOut() {
		return $this->time_out;	
	}
}
?>