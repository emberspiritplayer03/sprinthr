<?php
class G_Employee_Memo {
	
	public $id;
	public $employee_id;
	public $memo_id;
	public $title;
	public $memo;
	public $attachment;
	public $date_of_offense;
	public $offense_description;
	public $remarks;
	public $date_created;
	public $created_by;
	
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
	
	public function setMemoId($value) {
		$this->memo_id = $value;	
	}
	
	public function getMemoId() {
		return $this->memo_id;
	}
	
	public function setTitle($value) {
		$this->title = $value;	
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setMemo($value) {
		$this->memo = $value;	
	}
	
	public function getMemo() {
		return $this->memo;
	}
	
	public function setAttachment($value) {
		$this->attachment= $value;	
	}
	
	public function getAttachment() {
		return $this->attachment;
	}

	public function setDateOfOffense($value) {
		$this->date_of_offense= $value;	
	}
	
	public function getDateOfOffense() {
		return $this->date_of_offense;
	}

	public function setOffenseDescription($value) {
		$this->offense_description= $value;	
	}
	
	public function getOffenseDescription() {
		return $this->offense_description;
	}

	public function setRemarks($value) {
		$this->remarks= $value;	
	}
	
	public function getRemarks() {
		return $this->remarks;
	}
	
	public function setDateCreated($value) {
		$this->date_created = $value;	
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}
	
	public function setCreatedBy($value) {
		$this->created_by = $value;	
	}
	
	public function getCreatedBy() {
		return $this->created_by;
	}
		
	public function save() {
		return G_Employee_Memo_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Memo_Manager::delete($this);
	}

	public function getDisciplinaryData($query, $add_query) {

		$date_from = $query['date_from'];
		$date_to = $query['date_to'];
		$employee_id = explode(",", $query['employee_id']);
		$dept_section_id = explode(",", ['dept_section_id']);
		$employment_status_id = explode(",", $query['employment_status_id']);

		$project_site_id = $query['project_site_id'];

		foreach( $employee_id as $key => $eid ){
			 $employee_ids[$key] = Utilities::decrypt($eid);
		}

		foreach( $dept_section_id as $key => $eid ){
			$deptsection_ids[$key] = Utilities::decrypt($eid);
		}

		foreach( $employment_status_id as $key => $eid ){
			$employment_status_ids[$key] = Utilities::decrypt($eid);
		}	

		$data = G_Employee_Memo_Helper::getEmployeeMemoByEmployeeIdEmployeeDepartmentEmployeeStatus($employee_ids, $deptsection_ids, $employment_status_ids, $date_from, $date_to, $add_query, $project_site_id);

       return $data;
    }
}

?>