<?php

class G_Settings_Grace_Period_Exempted {

	public $id;
	public $employee_id;

	function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}

	public function getId() {
		return $this->id;
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}

	public function getEmployeeId() {
		return $this->employee_id;
	}

	public function save() {        
		return G_Settings_Grace_Period_Exempted_Manager::save($this);
	}


	public function delete() {
		
		return G_Settings_Grace_Period_Exempted_Manager::delete($this);
	}


}


?>