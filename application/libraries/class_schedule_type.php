<?php
class Schedule_Type {	
	protected $id;
	protected $name;
	protected $auto_schedule;
	protected $is_shift;
	protected $is_compressed;
	protected $is_staggered;
	protected $staggered_required_hours;
	protected $compressed_required_hours;
	protected $is_flexible;
	protected $flexible_required_hours;
	protected $is_compressed_staggered;
	protected $compressed_staggered_required_hours;
	protected $is_compressed_flexible;
	protected $compressed_flexible_required_hours;
	protected $is_security;
	protected $is_actual_hours;
	protected $tardiness_range;
	protected $tardiness_grace_period;
	protected $overtime_range;
	protected $overtime_grace_period;
	protected $undertime_range;
	protected $undertime_grace_period;	
	
	function __construct() {

	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}	

	public function setName($value) {
		$this->name = $value;	
	}
	
	public function getName() {
		return $this->name;	
	}

	public function setAutoSchedule($value) {
		$this->auto_schedule = $value;	
	}
	
	public function getAutoSchedule() {
		return $this->auto_schedule;	
	}	

	public function setStaggeredRequiredHours($value) {
		$this->staggered_required_hours = $value;	
	}
	
	public function getStaggeredRequiredHours() {
		return $this->staggered_required_hours;	
	}

	public function setCompressedRequiredHours($value) {
		$this->compressed_required_hours = $value;	
	}
	
	public function getCompressedRequiredHours() {
		return $this->compressed_required_hours;	
	}
	
	public function setIsShift($value) {
		$this->is_shift = $value;	
	}
	
	public function getIsShift() {
		return $this->is_shift;	
	}

	public function setIsCompressed($value) {
		$this->is_compressed = $value;	
	}
	
	public function getIsCompressed() {
		return $this->is_compressed;	
	}

	public function setIsStaggered($value) {
		$this->is_staggered = $value;	
	}
	
	public function getIsStaggered() {
		return $this->is_staggered;	
	}

	public function setIsFlexible($value) {
		$this->is_flexible = $value;	
	}
	
	public function getIsFlexible() {
		return $this->is_flexible;	
	}

	public function setFlexibleRequiredHours($value) {
		$this->flexible_required_hours = $value;	
	}
	
	public function getFlexibleRequiredHours() {
		return $this->flexible_required_hours;	
	}

	public function setIsCompressedStaggered($value) {
		$this->is_compressed_staggered = $value;	
	}
	
	public function getIsCompressedStaggered() {
		return $this->is_compressed_staggered;	
	}

	public function setCompressedStaggeredRequiredHours($value) {
		$this->compressed_staggered_required_hours = $value;	
	}
	
	public function getCompressedStaggeredRequiredHours() {
		return $this->compressed_staggered_required_hours;	
	}
	
	public function setIsCompressedFlexible($value) {
		$this->is_compressed_flexible = $value;	
	}
	
	public function getIsCompressedFlexible() {
		return $this->is_compressed_flexible;	
	}

	public function setCompressedFlexibleRequiredHours($value) {
		$this->compressed_flexible_required_hours = $value;	
	}
	
	public function getCompressedFlexibleRequiredHours() {
		return $this->compressed_flexible_required_hours;	
	}

	public function setIsSecurity($value) {
		$this->is_security = $value;	
	}
	
	public function getIsSecurity() {
		return $this->is_security;	
	}

	public function setIsActualHours($value) {
		$this->is_actual_hours = $value;	
	}
	
	public function getIsActualHours() {
		return $this->is_actual_hours;	
	}

	public function setTardinessRange($value) {
		$this->tardiness_range = $value;	
	}
	
	public function getTardinessRange() {
		return $this->tardiness_range;	
	}

	public function setTardinessGracePeriod($value) {
		$this->tardiness_grace_period = $value;	
	}
	
	public function getTardinessGracePeriod() {
		return $this->tardiness_grace_period;	
	}

	public function setOvertimeRange($value) {
		$this->overtime_range = $value;	
	}
	
	public function getOvertimeRange() {
		return $this->overtime_range;	
	}

	public function setOvertimeGracePeriod($value) {
		$this->overtime_grace_period = $value;	
	}
	
	public function getOvertimeGracePeriod() {
		return $this->overtime_grace_period;	
	}

	public function setUndertimeRange($value) {
		$this->undertime_range = $value;	
	}
	
	public function getUndertimeRange() {
		return $this->undertime_range;	
	}

	public function setUndertimeGracePeriod($value) {
		$this->undertime_grace_period = $value;	
	}
	
	public function getUndertimeGracePeriod() {
		return $this->undertime_grace_period;	
	}
}
?>