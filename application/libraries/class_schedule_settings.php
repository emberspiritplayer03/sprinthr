<?php
class Schedule_Settings {
	protected $id;
	protected $shift;
	protected $flexible;
	protected $compressed;
	protected $staggered;
	protected $security;
	protected $actual;
	protected $per_trip;
			
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}

	public function setShift($value) {
		$this->shift = $value;
	}
	
	public function getShift() {
		return $this->shift;
	}

	public function setFlexible($value) {
		$this->flexible = $value;
	}
	
	public function getFlexible() {
		return $this->flexible;
	}

	public function setCompressed($value) {
		$this->compressed = $value;
	}
	
	public function getCompressed() {
		return $this->compressed;
	}

	public function setStaggered($value) {
		$this->staggered = $value;
	}
	
	public function getStaggered() {
		return $this->staggered;
	}

	public function setSecurity($value) {
		$this->security = $value;
	}
	
	public function getSecurity() {
		return $this->security;
	}

	public function setActual($value) {
		$this->actual = $value;
	}
	
	public function getActual() {
		return $this->actual;
	}

	public function setPerTrip($value) {
		$this->per_trip = $value;
	}
	
	public function getPerTrip() {
		return $this->per_trip;
	}
	
}
?>