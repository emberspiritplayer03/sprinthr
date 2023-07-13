<?php
class G_Performance {
	
	public $id;
	public $company_structure_id;
	public $title;
	public $job_id;	
	public $description;
	public $date_created;
	public $created_by;
	public $is_archive;

	const YES = 1;
	const NO  = 0;
		
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setTitle($value) {
		$this->title = $value;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function setJobId($value) {
		$this->job_id= $value;
	}
	
	public function getJobId() {
		return $this->job_id;
	}
	
	public function setDescription($value) {
		$this->description = $value;
	}
	
	public function getDescription() {
		return $this->description;
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
	
	public function setIsArchive($value) {
		$this->is_archive = $value;
	}
	
	public function getIsArchive() {
		return $this->is_archive;
	}
	
	
	public function save (G_Performance $gcs) {
		return G_Performance_Manager::save($this, $gcs);
	}
	
	public function archive (G_Performance $gcs) {
		return G_Performance_Manager::archive($this, $gcs);
	}
	
	public function restore (G_Performance $gcs) {
		return G_Performance_Manager::restore($this, $gcs);
	}
	
	public function delete() {
		return G_Performance_Manager::delete($this);
	}
}
?>