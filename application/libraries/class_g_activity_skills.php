<?php
class G_Activity_Skills extends Activity_Skills {

	public $id;
	public $activity_skills_name;
	public $activity_skills_description;
	public $date_started;
	public $date_ended;
	public $date_created;

	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}
	
	public function setActivitySkillsName($value) {
		$this->activity_skills_name = $value;	
	}
	
	public function getActivitySkillsName() {
		return $this->activity_skills_name;	
	}
	
	public function setActivitySkillsDescription($value) {
		$this->activity_skills_description = $value;	
	}
	
	public function getActivitySkillsDescription() {
		return $this->activity_skills_description;	
	}
	
	public function setDateStarted($value) {
		$this->date_started = $value;	
	}

	public function getDateStarted() {
		return $this->date_started;	
	}

	public function setDateEnded($value) {
		$this->date_ended = $value;	
	}

	public function getDateEnded() {
		return $this->date_ended;
	}

	public function setDateCreated($value) {
		$this->date_created = $value;	
	}
	
	public function getDateCreated() {
		return $this->date_created;	
	}

	public function saveActivity() {
		$return   = array();
		$is_saved = 0;
		if(!empty($this->activity_skills_name)) {
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

	public static function findAllActivity($csid='', $order_by = '', $limit = '') {
		
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		$where = ($csid != '') ? 'WHERE id = ' . Model::safeSql($csid) : '';
		
		$sql = "
			SELECT id,activity_skills_name,activity_skills_description,date_started,date_ended,date_created
			FROM " .G_ACTIVITY." 
			".$where."	
			".$order_by."
			".$limit."		
		";		

		return self::getRecords($sql);
	}

	private static function getRecords($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}
		while ($row = Model::fetchAssoc($result)) {
			//$records[$row['id']] = self::newObject($row);
			$records[] = $row;
		}
		return $records;
	}
			
	public function save() {
		return G_Activity_Skills_Manager::save($this);
	}
	
	public function delete() {		
		return G_Activity_Skills_Manager::delete($this);
	}

	public function update() {
		return G_Activity_Skills_Manager::update($this);
	}
	
}
