<?php
/*
	Usage:
		$e = Employee_Factory::get(7);
		$s = new G_SSS;
		$s->setCompanyShare(500);
		$s->setEmployeeShare(200);
		$s->saveToEmployee($e);
		return $s;
*/
class G_SSS extends SSS {
	protected $id;
	
	public function __construct() {
	
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}
	
	public function saveToEmployee(IEmployee $e) {
		return G_SSS_Manager::saveToEmployee($e, $this);	
	}

	public function update() {
		return G_SSS_Manager::update($this);
	}

	public function importSSSTable($file) {
		return G_SSS_Manager::importSSSTable($file);
	}
}
?>