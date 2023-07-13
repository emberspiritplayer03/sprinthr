<?php
class G_Applicant_Attachment {
	
	public $id;
	public $applicant_id;
	public $name;
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
	
	public function setApplicantId($value) {
		$this->applicant_id = $value;
	}
	
	public function getApplicantId() {
		return $this->applicant_id;
	}
	
	public function setName($value) {
		$this->name = $value;
	}
	
	public function getName() {
		return $this->name;
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
		
	public function save (G_Applicant_Attachment $gcs) {
		return G_Applicant_Attachment_Manager::save($this, $gcs);
	}
	
	public function delete() {
		return G_Applicant_Attachment_Manager::delete($this);
	}
}
?>