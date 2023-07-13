<?php
class G_Activity_Category extends Activity_Category {

	public $id;
	public $activity_category_name;
	public $activity_category_description;
	public $date_created;

	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}
	
	public function setActivityCategoryName($value) {
		$this->activity_category_name = $value;	
	}
	
	public function getActivityCategoryName() {
		return $this->activity_category_name;	
	}
	
	public function setActivityCategoryDescription($value) {
		$this->activity_category_description = $value;	
	}
	
	public function getActivityCategoryDescription() {
		return $this->activity_category_description;	
	}
	
	public function setDateCreated($value) {
		$this->date_created = $value;	
	}
	
	public function getDateCreated() {
		return $this->date_created;	
	}

	public function saveDesignation() {
		$return   = array();
		$is_saved = 0;
		if(!empty($this->activity_category_name)) {
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
		return G_Activity_Category_Manager::save($this);
	}
	
	public function delete() {		
		return G_Activity_Category_Manager::delete($this);
	}
	
}
?>