<?php
class Sync_Data {
	protected $id;
	protected $table_name;
	protected $pk_id_local;
	protected $pk_id_live;
	protected $action;
	protected $is_sync;
	protected $date_modified;
	protected $date_created;	

	const YES = "Yes";
	const NO  = "No";
			
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
    public function setTableName($value) {    	
		$this->table_name = $value;
	}
	
	public function getTableName() {
		return $this->table_name;
	}
        
    public function setPkIdLocal($value) {
		$this->pk_id_local = $value;
	}
	
	public function getPkIdLocal() {
		return $this->pk_id_local;
	}

	public function setPkIdLive($value) {
		$this->pk_id_live = $value;
	}
	
	public function getPkIdLive() {
		return $this->pk_id_live;
	}

	public function setAction($value) {
		$this->action = $value;
	}
	
	public function getAction() {
		return $this->action;
	}

	public function setIsSync($value) {
		$this->is_sync = $value;
	}
	
	public function getIsSync() {
		return $this->is_sync;
	}
			
	public function setDateModified($value) {		
		$this->date_modified = $value;
	}
	
	public function getDateModified() {
		return $this->date_modified;
	}

	public function setDateCreated($value) {		
		$this->date_created = $value;
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}
}
?>