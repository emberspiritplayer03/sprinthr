<?php
class G_Performance_Indicator {
	
	public $id;
	public $performance_id;
	public $title;
	public $description;	
	public $rate_min;
	public $rate_max;
	public $rate_default;
	public $order_by;
	public $is_active;

		
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setPerformanceId($value) {
		$this->performance_id= $value;
	}
	
	public function getPerformanceId() {
		return $this->performance_id;
	}
	
	public function setTitle($value) {
		$this->title = $value;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setDescription($value) {
		$this->description = $value;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setRateMin($value) {
		$this->rate_min = $value;
	}
	
	public function getRateMin() {
		return $this->rate_min;
	}
	
	public function setRateMax($value) {
		$this->rate_max = $value;
	}
	
	public function getRateMax() {
		return $this->rate_max;
	}
	
	public function setRateDefault($value) {
		$this->rate_default = $value;
	}
	
	public function getRateDefault() {
		return $this->rate_default;
	}
	
	public function setOrderBy($value) {
		$this->order_by = $value;
	}
	
	public function getOrderBy() {
		return $this->order_by;
	}
	
	public function setIsActive($value) {
		$this->is_active = $value;
	}
	
	public function getIsActive() {
		return $this->is_active;
	}
	
	public function save (G_Performance_Indicator $gcs) {
		return G_Performance_Indicator_Manager::save($this, $gcs);
	}
	
	public function delete() {
		return G_Performance_Indicator_Manager::delete($this);
	}
}
?>