<?php
class G_Activity_Skills_Manager {

    public static function save(G_Activity_Skills $e) {
        $es[] = $e;
        return self::saveMultiple($es);
    }
    
    public static function saveMultiple($activity_skills_objects) {
        $has_record = false;
        foreach ($activity_skills_objects as $o) {
            $insert_sql_values[] = "
                (". Model::safeSql($o->getId()) .",
                ". Sql::safeSql($o->getActivitySkillsName()) .",
                ". Sql::safeSql($o->getActivitySkillsDescription()) .",
                ". Sql::safeSql($o->getDateStarted()) .",
                ". Sql::safeSql($o->getDateEnded()) .",
                ". Sql::safeSql($o->getDateCreated()) .")";
            $has_record = true;
        }

        if ($has_record) {
            $insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". G_ACTIVITY_SKILLS ." (id, activity_skills_name, activity_skills_description, date_started, date_ended, date_created)
                VALUES ". $insert_sql_value ."
                ON DUPLICATE KEY UPDATE
                    activity_skills_name = VALUES(activity_skills_name),
                    activity_skills_description = VALUES(activity_skills_description),
                    date_started = VALUES(date_started),
                    date_ended = VALUES(date_ended),
                    date_created = VALUES(date_created)
            ";
            Sql::runSql($sql_insert);
        }

        if (Sql::getErrorNumber() > 0) {
            //echo mysql_error();
            return false;
        } else {
            $insert_id = Sql::getInsertId();
            if ($insert_id > 0) {
                return $insert_id;
            } else {
                return true;
            }
        }
    }
		
	public static function delete(G_Activity_Skills $model){
		if(G_Activity_Skills_Helper::isIdExist($model) > 0){
			$sql = "
				DELETE FROM ". G_ACTIVITY_SKILLS ."
				WHERE id =" . Model::safeSql($model->getId());
			Model::runSql($sql);
		}	
	}

    public static function update(G_Activity_Skills $e){
		//count if exist not done
		$sql_start = "UPDATE " . G_ACTIVITY. " ";
		$sql_end   = "WHERE id = " . Model::safeSql($e->getId());
		$sql = $sql_start . "
			SET
				activity_skills_name        =". Model::safeSql($e->getActivitySkillsName()).", 
				activity_skills_description    =". Model::safeSql($e->getActivitySkillsDescription()).",
                date_started    =". Model::safeSql($e->getDateStarted()).",
				date_ended =". Model::safeSql($e->getDateEnded()). 
				$sql_end;
		Model::runSql($sql);
		return mysql_insert_id();
    }

}
?>