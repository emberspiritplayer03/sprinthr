<?php
class G_Employee_Dependent {
	
	public $id;
	public $employee_id;
	public $name;
	public $relationship;
	public $birthdate;


	
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
	
	public function setName($value) {
		$this->name = $value;	
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setRelationship($value) {
		$this->relationship = $value;	
	}

	public function setDefaultRelationship() {
		$this->relationship = 'Sibling';
	}
	
	public function getRelationship() {
		return $this->relationship;
	}
	
	public function setBirthdate($value) {
		$date_formatted = date("Y-m-d",strtotime($value));
		$this->birthdate = $date_formatted;	
	}
	
	public function getBirthdate() {
		return $this->birthdate;
	}

	public function deleteAllEmployeeDependents(){
		if( $this->employee_id > 0 ){
			$number_of_dependents_deleted = G_Employee_Dependent_Manager::deleteAllEmployeeDependents($this->employee_id);
		}

		return $this;
	}

	public function defaultDependents($number_to_insert = 0) {
		if( !empty($this->employee_id) && $number_to_insert > 0 ){
			$return = G_Employee_Dependent_Manager::insertDefaultNumberOfDependents($this, $number_to_insert);
		}else{
			$return = false;
		}

		return $return;
	}

	public function updateEmployeeDataTotalDependents() {
		if( !empty($this->employee_id) ){
			$total_dependents = G_Employee_Dependent_Helper::sqlCountTotalDependentsByEmployeeId($this->employee_id);
			$return           = G_Employee_Manager::updateEmployeeTotalDependentsByPkId($this->employee_id, $total_dependents);
		}else{
			$return = false;
		}

		return $return;

	}
		
	public function save() {
		return G_Employee_Dependent_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Dependent_Manager::delete($this);
	}
}

?>