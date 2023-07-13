<?php
class G_Employee_Activities_Finder {
	
	public static function findById($id) {
		$sql = "
			SELECT *
			FROM ". G_EMPLOYEE_ACTIVITIES ." ea
			WHERE ea.id = ". Model::safeSql($id) ."
			LIMIT 1		
		";
		
		return self::getRecord($sql);
	}
	
	public static function findByActivityId($id) {
		$sql = "
			SELECT ea.id
			FROM ". G_EMPLOYEE_ACTIVITIES ." ea
			LEFT JOIN ". G_ACTIVITY_SKILLS ." s ON ea.activity_skills_id = s.id 
			WHERE s.id = ". Model::safeSql($id) ."
		";
		
		return self::getRecords($sql);
	}
	
	public static function findByDesignationId($id) {
		$sql = "
			SELECT ea.id
			FROM ". G_EMPLOYEE_ACTIVITIES ." ea
			LEFT JOIN ". G_ACTIVITY_CATEGORY ." s ON ea.activity_category_id = s.id 
			WHERE s.id = ". Model::safeSql($id) ."
		";
		
		return self::getRecords($sql);
	}

	public static function findActivityFromTo($from, $to) {
		$sql = "
			SELECT *
			FROM ". G_EMPLOYEE_ACTIVITIES ." 
			WHERE date BETWEEN ". Model::safeSql($from) ." AND  ". Model::safeSql($to) ."
		";
		
		return self::getRecords($sql);
	}



	public function checkDuplicate($employee_id, $activity_category_id, $activity_skills_id, $date, $time_in, $date_out, $time_out, $project_site_id) {

		$sql = "
				SELECT id FROM ". G_EMPLOYEE_ACTIVITIES ."
				WHERE employee_id = ". Model::safeSql($employee_id)."
				AND activity_category_id = ". Model::safeSql($activity_category_id)."
				AND activity_skills_id =  ". Model::safeSql($activity_skills_id)."
				AND date = ". Model::safeSql($date)."
				AND time_in = ". Model::safeSql($time_in)."
				AND date_out = ". Model::safeSql($date_out)."
				AND time_out = ". Model::safeSql($time_out)."
				AND project_site_id = ". Model::safeSql($project_site_id)."
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
			$records[$row['id']] = self::newObject($row);
        }
		return $records;
	}

	private static function getRecord($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}		
		$row = Model::fetchAssoc($result);
		$records = self::newObject($row);

		return $records;
	}
	
	private static function newObject($row) {
		
		$e = new G_Employee_Activities;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setActivityCategoryId($row['activity_category_id']);
		$e->setActivitySkillsId($row['activity_skills_id']);
		$e->setDate($row['date']);
		$e->setTimeIn($row['time_in']);
		$e->setDateOut($row['date_out']);
		$e->setTimeOut($row['time_out']);
		$e->setReason($row['reason']);
		$e->setDateCreated($row['date_created']);
		$e->setProjectSiteId($row['project_site_id']);
		$e->setProjectSiteName($row['project_site']);


        return $e;
	}

}
?>