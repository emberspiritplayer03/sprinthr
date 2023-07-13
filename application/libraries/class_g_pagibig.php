<?php
/*
	Usage:
		$e = Employee_Factory::get(7);
		$s = new G_Pagibig;
		$s->setCompanyShare(500);
		$s->setEmployeeShare(200);
		$s->saveToEmployee($e);
		return $s;
*/
class G_Pagibig extends Pagibig {
	protected $id;
	
	const LIMIT_SALARY_TO = 5000;

	public function __construct() {
	
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}
	
	public function saveToEmployee(IEmployee $e) {
		return G_Pagibig_Manager::saveToEmployee($e, $this);	
	}
}
?>