<?php
class G_Employee_Activities extends Employee_Activities {

	public $id;
	public $employee_id;
	public $activity_category_id;
	public $activity_skills_id;
	public $date;
	public $time_in;
	public $date_out;
	public $time_out;
	public $reason;
	public $project_site_name;
	public $project_site_id;
	public $date_created;

	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}

	public function setProjectSiteId($value) {
		$this->project_site_id = $value;	
	}
	
	public function getProjectSiteId() {
		return $this->project_site_id;	
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;	
	}
	
	public function setActivityCategoryId($value) {
		$this->activity_category_id = $value;	
	}
	
	public function getActivityCategoryId() {
		return $this->activity_category_id;	
	}
	
	public function setActivitySkillsId($value) {
		$this->activity_skills_id = $value;	
	}
	
	public function getActivitySkillsId() {
		return $this->activity_skills_id;	
	}
	
	public function setDate($value) {
		$this->date = $value;	
	}
	
	public function getDate() {
		return $this->date;	
	}
	
	public function setTimeIn($value) {
		$this->time_in = $value;	
	}
	
	public function getTimeIn() {
		return $this->time_in;	
	}
	
	public function setDateOut($value) {
		$this->date_out = $value;	
	}
	
	public function getDateOut() {
		return $this->date_out;	
	}
	
	public function setTimeOut($value) {
		$this->time_out = $value;	
	}
	
	public function getTimeOut() {
		return $this->time_out;	
	}
	
	public function setDateCreated($value) {
		$this->date_created = $value;	
	}
	
	public function getDateCreated() {
		return $this->date_created;	
	}
	
	public function setReason($value) {
		$this->reason = $value;	
	}
	
	public function getReason() {
		return $this->reason;	
	}

	public function setProjectSiteName($project_site_name)
	{
		$this->project_site_name = $project_site_name;
	}

	public function getProjectSiteName()
	{
		return $this->project_site_name;
	}

	public function saveActivity() {
		$return   = array();
		$is_saved = 0;
		if( !empty($this->employee_id) && !empty($this->activity_category_id) && !empty($this->activity_skills_id) && !empty($this->date) && !empty($this->time_in) && !empty($this->time_out) ) {
			$is_saved = self::save(); //Save request
											
			if( $is_saved ){
				$return['is_success'] = true;
				$return['message']    = "Record saved";
			}else{
				$return['is_success'] = false;
				$return['message']    = "Cannot save record";
			}	
		}else{
			$return['is_success'] = false;
			$return['message']    = "Invalid form entries";
		}

		$return['last_inserted_id'] = $is_saved;

		return $return;	
	}
	
	public function save() {
		return G_Employee_Activities_Manager::save($this);
	}

	public function delete($eid) {
		return G_Employee_Activities_Manager::delete($eid);
	}

 

}
?>