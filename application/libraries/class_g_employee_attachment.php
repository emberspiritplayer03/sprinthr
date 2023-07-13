<?php
class G_Employee_Attachment {
	
	public $id;
	public $employee_id;
	public $filename;
	public $description;
	public $size;	
	public $type;
	public $date_attached;
	public $added_by;
	public $screen;

	
	
	//objects
	protected $gcs;
		
	public function __construct($id) {
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
	
	public function setFilename($value) {
		$this->filename = $value;
	}
	
	public function getFilename() {
		return $this->filename;
	}
	
	public function setDescription($value) {
		$this->description = $value;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setSize($value) {
		$this->size = $value;
	}
	
	public function getSize() {
		return $this->size;
	}
	
	public function setType($value) {
		$this->type = $value;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function setDateAttached($value) {
		$this->date_attached = $value;
	}
	
	public function getDateAttached() {
		return $this->date_attached;
	}
	
	public function setAddedBy($value) {	
		$this->added_by = $value;		
	}
	
	public function getAddedBy() {
		return $this->added_by;
	}
	
	public function setScreen($value) {
		$this->screen = $value;
	}
	
	public function getScreen() {
		return $this->screen;
	}
		
	public function save () {
		return G_Employee_Attachment_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Attachment_Manager::delete($this);
	}
}
?>