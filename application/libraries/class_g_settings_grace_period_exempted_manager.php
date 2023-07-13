<?php

class G_Settings_Grace_Period_Exempted_Manager {


 public static function saveMultiple($es) {

        $has_record = false;
        foreach ($es as $e) {
        
        
            $insert_sql_values[] = "
            (" . Model::safeSql($e->getId()) .",
			'" . $e->getEmployeeId() . "')";


            $has_record = true;
		}
        

        if ($has_record) {

			$insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". G_GRACE_EXEMPTED ." (id, employee_id)
                VALUES ". $insert_sql_value ."
                ON DUPLICATE KEY UPDATE
                    employee_id = VALUES(employee_id)
            ";        
			 // echo $sql_insert ."============";
            Model::runSql($sql_insert);   
            //not sure about this 
           // exit();      
		}

        if (mysql_errno() > 0) {
            //echo mysql_error();
            return false;
        } else {
            // TODO Use wrapper
            return mysql_insert_id();
        }
    }

    public static function save(G_Settings_Grace_Period_Exempted $e) {
		$es[] = $e;
        return self::saveMultiple($es);
    }

    public static function delete(G_Settings_Grace_Period_Exempted $e){
		if(G_Settings_Grace_Period_Exempted_Finder::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_GRACE_EXEMPTED ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}

}

?>