<?php
class G_Group_Manager {
    /*
     * @param array $groups Array instance of G_Group
     */
    public static function saveMultiple($groups) {
        /*$has_record = false;
        foreach ($groups as $o) {
            $insert_sql_values[] = "
                (". Model::safeSql($o->getId()) .",
                ". Model::safeSql($o->getName()) .",
                ". Model::safeSql($o->getDescription()) .",
                ". Model::safeSql($o->getType()) .")";
            $has_record = true;
        }

        if ($has_record) {
            $insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". G_EMPLOYEE_OVERTIME ." (id, title, description, type)
                VALUES ". $insert_sql_value ."
                ON DUPLICATE KEY UPDATE
                    title = VALUES(title),
                    description = VALUES(description),
                    type = VALUES(type)
            ";
            Model::runSql($sql_insert);
        }

        if (mysql_errno() > 0) {
            //echo mysql_error();
            return false;
        } else {
            echo $insert_id = Sql::getInsertId();
            if ($insert_id == 0) {
                return true;
            } else {
                return $insert_id;
            }
        }*/
    }

    public static function save($o) {
        /*
        $os[] = $o;
        return self::saveMultiple($os);*/
    }
	
	public static function addEmployee(IGroup $g, IEmployee $e, $start_date, $end_date) {
		if (!$start_date) {
			$start_date = Tools::getGmtDate('Y-m-d');	
		}
		//if ($g->getId() > 0) {
		//	$action = 'update';
		//	$sql_start = "UPDATE ". G_EMPLOYEE_SCHEDULE;
		//	$sql_end   = " WHERE id = ". Model::safeSql($g->getId());		
		//} else {
			$action = 'insert';
			$sql_start = "INSERT INTO ". G_EMPLOYEE_SUBDIVISION_HISTORY;
		//}
		
		$sql = $sql_start ."
			SET
			employee_id = ". Model::safeSql($e->getId()) .",
			company_structure_id = ". Model::safeSql($g->getId()) .",
			name = ". Model::safeSql($g->getName()) .",
			start_date = ". Model::safeSql($start_date) .",
			end_date = ". Model::safeSql($end_date) ."
			". $sql_end ."		
		";			
		
		Model::runSql($sql);
		if (mysql_errno() > 0) {
			return false;
		}
		if ($action == 'insert') {
			return mysql_insert_id();
		} else if ($action == 'update') {
			return true;
		}
	}
	
	/*
		Variables
		$g - Instance of G_Group class
	*/		
	public static function delete($sf) {
		$sql = "
			DELETE FROM ". G_EMPLOYEE_SUBDIVISION_HISTORY ."
			WHERE id = ". Model::safeSql($g->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}		
}
?>