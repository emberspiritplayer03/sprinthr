<?php
class G_Activity_Category_Manager {

    public static function save(G_Activity_Category $e) {
        $es[] = $e;
        return self::saveMultiple($es);
    }
    
    public static function saveMultiple($activity_category_objects) {
        $has_record = false;
        foreach ($activity_category_objects as $o) {
            $insert_sql_values[] = "
                (". Model::safeSql($o->getId()) .",
                ". Sql::safeSql($o->getActivityCategoryName()) .",
                ". Sql::safeSql($o->getActivityCategoryDescription()) .",
                ". Sql::safeSql($o->getDateCreated()) .")";
            $has_record = true;
        }

        if ($has_record) {
            $insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". G_ACTIVITY_CATEGORY ." (id, activity_category_name, activity_category_description, date_created)
                VALUES ". $insert_sql_value ."
                ON DUPLICATE KEY UPDATE
                    activity_category_name = VALUES(activity_category_name),
                    activity_category_description = VALUES(activity_category_description),
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
		
	public static function delete(G_Activity_Category $model){
		if(G_Activity_Category_Helper::isIdExist($model) > 0){
			$sql = "
				DELETE FROM ". G_ACTIVITY_CATEGORY ."
				WHERE id =" . Model::safeSql($model->getId());
			Model::runSql($sql);
		}	
	}

}
?>