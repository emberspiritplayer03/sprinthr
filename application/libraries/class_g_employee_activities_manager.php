<?php
class G_Employee_Activities_Manager {

    public static function save(G_Employee_Activities $e) {
        $es[] = $e;
       // utilities::displayArray($es);exit();
        return self::saveMultiple($es);
    }
    
    public static function saveMultiple($employee_activity_objects) {
        $has_record = false;
        foreach ($employee_activity_objects as $o) {
            $insert_sql_values[] = "
                (". Model::safeSql($o->getId()) .",
                ". Sql::safeSql($o->getEmployeeId()) .",
                ". Sql::safeSql($o->getProjectSiteId()) .",
                ". Sql::safeSql($o->getProjectSiteName()) .",
                ". Sql::safeSql($o->getActivityCategoryId()) .",
                ". Sql::safeSql($o->getActivitySkillsId()) .",
                ". Sql::safeSql($o->getDate()) .",
                ". Sql::safeSql($o->getTimeIn()) .",
                ". Sql::safeSql($o->getDateOut()) .",
                ". Sql::safeSql($o->getTimeOut()) .",
                ". Sql::safeSql($o->getReason()) .",
                ". Sql::safeSql($o->getDateCreated()) .")";
            $has_record = true;
        }

        if ($has_record) {
            $insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". G_EMPLOYEE_ACTIVITIES ." (id, employee_id,project_site_id, project_site,activity_category_id, activity_skills_id, date, time_in, date_out, time_out, reason, date_created)
                VALUES ". $insert_sql_value ."
                ON DUPLICATE KEY UPDATE
                    employee_id = VALUES(employee_id),
                    project_site_id = VALUES(project_site_id),
                    project_site = VALUES(project_site),
                    activity_category_id = VALUES(activity_category_id),
                    activity_skills_id = VALUES(activity_skills_id),
                    date = VALUES(date),
                    time_in = VALUES(time_in),
                    date_out = VALUES(date_out),
                    time_out = VALUES(time_out),
                    reason = VALUES(reason),
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



   public static function delete($eid){

          $total_deleted = 0;

            $sql = "
                DELETE FROM ". G_EMPLOYEE_ACTIVITIES ."
                WHERE id =" . Model::safeSql($eid);
            Model::runSql($sql);
            $total_deleted = mysql_affected_rows();


          return $total_deleted;
    
    }

    

}
?>