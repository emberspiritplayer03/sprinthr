<?php
class Payslip_Template {
	public $id;
	public $template_name;
	public $is_default;
	
	public function __construct() {}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setTemplateName($value) {
		$this->template_name = $value;
	}
	
	public function getTemplateName() {
		return $this->template_name;
	}
	
	public function setIsDefault($value) {
		$this->is_default = $value;
	}
	
	public function getIsDefault() {
		return $this->is_default;
	}
	
	
}
?>